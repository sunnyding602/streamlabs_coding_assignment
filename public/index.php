<?php
require_once getenv('SITE_ROOT').'/include/init.php';
require_once getenv('SITE_ROOT').'/vendor/autoload.php';

if(!is_login()){
    //calling this function just to set gloal variable $user
}
$site_url = 'http://' . $_SERVER['HTTP_HOST'];
$login_uri = $site_url.'/login.php';
$socket_uri = 'http://' . $_SERVER['HTTP_HOST'].':3000';
?>
<!doctype html>
<html>
    <head>
        <title>Streams Lab Coding Assignment</title>
        <link rel="stylesheet" type="text/css" href="css/mystyle.css">
        <script src= "http://player.twitch.tv/js/embed/v1.js"></script>
    </head>
    <body>
        <nav class="top-nav pos-fixed full-width top-0">
            <li class="login">
<?php
                if(empty($user['username'])){
?>
                    <a  href="/login.php" class="button" >Log In</a>
<?php
                }else{
?>
                    <h3 id="user_display_name"><?php echo $user['username']?></h3>
                    <a  href="/logout.php" class="button" >Log Out</a>
<?php
                }
?>
                    <a href="/stats.php" class="button" >Stats</a>
            </li>
        </nav>

        <div id="player_div_id"></div>

        <div id="chat">
            <ul id="messages"></ul>
            <form action="">
            <textarea placeholder="Send a message" id="m" autocomplete="off"></textarea>
            <button class="button">Send</button>
            </form>
        </div>

        <script src="https://cdn.socket.io/socket.io-1.2.0.js"></script>
        <script src="https://code.jquery.com/jquery-1.11.1.js"></script>
        <script>
            const nickname = "<?php echo empty($user['username']) ? '' : $user['username'];?>";
            const socket = io("<?php echo $socket_uri;?>");
            const login_uri = "<?php echo $login_uri;?>";
        </script>
        <script src="js/myjs.js"></script>
    </body>
</html>
