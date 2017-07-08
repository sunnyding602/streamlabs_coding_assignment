<?php
require_once getenv('SITE_ROOT').'/include/init.php';
require_once getenv('SITE_ROOT').'/vendor/autoload.php';


if(!is_login()){
    $client = new Google_Client();
    $client->setAuthConfig(getenv('SITE_ROOT').'/config/client_secrets.json');
    $client->addScope(Google_Service_YouTube::YOUTUBE_FORCE_SSL);

    if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
        $client->setAccessToken($_SESSION['access_token']);
        $youtube = new Google_Service_YouTube($client);
        list($third_party_id, $username)= listChannelMine($youtube,
            'id,snippet', 
            array('mine' => true));
        
        $channel = 'youtube';
        $userModel = User::getInstance();
        $is_registered = $userModel->is_registered($third_party_id, $channel);

        if(!$is_registered){
            //register user then login
            $userModel->register($username, $third_party_id, $channel);
        }
        login($username);

        $redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] . '/';
        header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));

    } else {
        $redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] . '/oauth2callback.php';
        header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
    }
}


var_dump($user['username']);


function listChannelMine($service, $part, $params) {
    $params = array_filter($params);
    $response = $service->channels->listChannels(
        $part,
        $params
    );
    return array($response['modelData']['items'][0]['id'], $response['modelData']['items'][0]['snippet']['title']);
}
?>
<!doctype html>
<html>
  <head>
    <title>Socket.IO chat</title>
    <style>
      * { margin: 0; padding: 0; box-sizing: border-box; }
      body { font: 13px Helvetica, Arial; }
      form { background: #000; padding: 3px; position: fixed; bottom: 0; width: 100%; }
      form input { border: 0; padding: 10px; width: 90%; margin-right: .5%; }
      form button { width: 9%; background: rgb(130, 224, 255); border: none; padding: 10px; }
      #messages { list-style-type: none; margin: 0; padding: 0; }
      #messages li { padding: 5px 10px; }
      #messages li:nth-child(odd) { background: #eee; }
      #messages { margin-bottom: 40px }
    </style>
  </head>
  <body>
    <ul id="messages"></ul>
    <form action="">
      <input id="m" autocomplete="off" /><button>Send</button>
    </form>
    <script src="https://cdn.socket.io/socket.io-1.2.0.js"></script>
    <script src="https://code.jquery.com/jquery-1.11.1.js"></script>
    <script>
      $(function () {
        var socket = io('http://localhost:3000');
        $('form').submit(function(){
          socket.emit('chat message', $('#m').val());
          $('#m').val('');
          return false;
        });
        socket.on('chat message', function(msg){
          $('#messages').append($('<li>').text(msg));
          window.scrollTo(0, document.body.scrollHeight);
        });
      });
    </script>
  </body>
</html>
