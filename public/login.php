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