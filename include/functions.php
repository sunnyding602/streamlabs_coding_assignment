<?php
$user = array();
function gen_uuid() {
    return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        // 32 bits for "time_low"
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),

        // 16 bits for "time_mid"
        mt_rand( 0, 0xffff ),

        // 16 bits for "time_hi_and_version",
        // four most significant bits holds version number 4
        mt_rand( 0, 0x0fff ) | 0x4000,

        // 16 bits, 8 bits for "clk_seq_hi_res",
        // 8 bits for "clk_seq_low",
        // two most significant bits holds zero and one for variant DCE1.1
        mt_rand( 0, 0x3fff ) | 0x8000,

        // 48 bits for "node"
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
    );
}

function login($username){
    $is_login= is_login();
    if(!$is_login){
        $userModel = User::getInstance();
        $session_id = $userModel->set_session_id($username);
        //save sessionid to cookie
        setcookie("session_id", $session_id, time()+86400);
    }
    return true;
}

function is_login(){
    
    global $user;
    $userModel = User::getInstance();
    
    if (empty($_COOKIE['session_id'])){
        return false;
    }
    //check sesion
    $_user = $userModel->get_user_by_session_id($_COOKIE['session_id']);
    if(empty($_user)){
        setcookie("session_id", "", time() - 3600);
        return false;
    }
    $user = $_user;
    return true;
}

function logout(){
    setcookie("session_id", "", time() - 3600);
    //session_unset();
}


function listChannelMine($service, $part, $params) {
    $params = array_filter($params);
    $response = $service->channels->listChannels(
        $part,
        $params
    );
    return array($response['modelData']['items'][0]['id'], $response['modelData']['items'][0]['snippet']['title']);
}


