<?php
require_once getenv('SITE_ROOT').'/include/api_init.php';

if(empty($_GET['username'])){
    echo json_encode( array('error'=>'no username provided') );
    exit;
}
$chatModel = Chat::getInstance();
$messages = $chatModel->get_msg_by_username($_GET['username']);
echo json_encode($messages);