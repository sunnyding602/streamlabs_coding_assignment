$(function() {
  const messages = $("#messages");
  const messageInput = $("#m");
  const theForm = $("form");
  const chat = document.getElementById("chat");
  let currentRoomId = "";

  socket.on("joined-room", roomId => {
    currentRoomId = roomId;
    console.log("welcome to the chat room.");
  });

  socket.on("receive-message", function(msg) {
    messages.append($("<li>").text(msg.userName + ": " + msg.text));
    chat.scrollTop = chat.scrollHeight;
  });

  messageInput.keypress(function(e) {
    // Enter pressed?
    if (e.which == 10 || e.which == 13) {
      e.preventDefault();
      theForm.submit();
    }
  });

  theForm.submit(function() {
    if (!nickname) {
      window.location.assign(login_uri);
      return false;
    }
    let message = {
      text: messageInput.val(),
      room: currentRoomId,
      userName: nickname
    };

    socket.emit("send-message", message);
    messageInput.val("");
    return false;
  });

  socket.emit("join-room", {
    room: "general"
  });
});

//twitch live stream
var options = {
  width: 854,
  height: 480,
  channel: "loserfruit"
};
var player = new Twitch.Player("player_div_id", options);
player.setVolume(0.5);
