<?php
require_once getenv('SITE_ROOT').'/include/init.php';
require_once getenv('SITE_ROOT').'/vendor/autoload.php';
$socket_uri = 'http://' . $_SERVER['HTTP_HOST'].':3000';
?>
<html>
    <head>
        <title>Streams Lab Coding Assignment</title>
    </head>
    <body>
        <h2><span id="msgCntPerSec">0</span> messages per second</h2>
        <script src="https://cdn.socket.io/socket.io-1.2.0.js"></script>
        <script src="https://code.jquery.com/jquery-1.11.1.js"></script>
        <script>
            const socket = io('<?php echo $socket_uri;?>');
            const msgCntPerSec = document.getElementById("msgCntPerSec");
            $(function () {
                socket.on("stats", (cnt) => {
                    msgCntPerSec.innerText = cnt;
                })
            });
        </script>
    </body>
</html>


