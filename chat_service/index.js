const TwitchBot = require("node-twitchbot");

const Bot = new TwitchBot({
  username: "sunnyding602",
  oauth: "oauth:phohofvz0dtr0vrne6i47y3tg9m7e4",
  channel: "loserfruit"
});

var app = require("express")();
var http = require("http").Server(app);
var io = require("socket.io")(http);
var port = process.env.PORT || 3000;

var mysql = require("mysql");
var connection = mysql.createConnection({
  host: "localhost",
  user: "root",
  password: "123456",
  database: "live_video"
});

connection.connect();

let messages = [];

let msgReceived = function() {
  messages.push(Date.now());
  delOutDatedMsg();
};

let delOutDatedMsg = function() {
  for (let i = 0; i < messages.length; i++) {
    if (
      messages[i] < messages[messages.length - 1] - 1000 ||
      messages[i] < Date.now() - 1000
    ) {
      messages.shift();
    } else {
      break;
    }
  }
};

let saveMsg = function(username, text, channel) {
  connection.query(
    "INSERT INTO chat (username, msg, room, ctime) VALUES (?, ?, ?, ?)",
    [username, text, channel, new Date().Format("yyyy-MM-dd hh:mm:ss")],
    function(error, results, fields) {
      if (error) throw error;
    }
  );
};

Date.prototype.Format = function(fmt) {
  var o = {
    "M+": this.getMonth() + 1,
    "d+": this.getDate(),
    "h+": this.getHours(),
    "m+": this.getMinutes(),
    "s+": this.getSeconds(),
    "q+": Math.floor((this.getMonth() + 3) / 3),
    S: this.getMilliseconds()
  };
  if (/(y+)/.test(fmt))
    fmt = fmt.replace(
      RegExp.$1,
      (this.getFullYear() + "").substr(4 - RegExp.$1.length)
    );
  for (var k in o)
    if (new RegExp("(" + k + ")").test(fmt))
      fmt = fmt.replace(
        RegExp.$1,
        RegExp.$1.length == 1 ? o[k] : ("00" + o[k]).substr(("" + o[k]).length)
      );
  return fmt;
};

setInterval(function() {
  delOutDatedMsg();
  io.emit("stats", messages.length);
}, 1000);

/* Connect bot to Twitch IRC */
Bot.connect()
  .then(() => {
    /* Listen for all messages in channel */
    Bot.listen((err, chatter) => {
      if (err) {
        console.log(err);
      } else {
        msgReceived();
        //console.log(chatter.user + ': ' + chatter.msg);
        msg = { text: chatter.msg, room: "general", userName: chatter.user };
        saveMsg(chatter.user, chatter.msg, chatter.channel);
        io.to(msg.room).emit("receive-message", msg);
      }
    });

    io.on("connection", function(socket) {
      socket.on("join-room", data => {
        socket.join(data.room);

        socket.emit("joined-room", data.room);
      });

      socket.on("send-message", msg => {
        console.log(msg.text);
        Bot.msg(msg.text);
      });
    });
  })
  .catch(err => {
    console.log("Connection error!");
    console.log(err);
  });

http.listen(port, function() {
  console.log("listening on *:" + port);
});
