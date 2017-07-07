<?php
date_default_timezone_set('America/Los_Angeles');
require_once __DIR__.'/../vendor/autoload.php';

session_start();

$client = new Google_Client();
$client->setAuthConfig('../client_secrets.json');
$client->addScope(Google_Service_YouTube::YOUTUBE_FORCE_SSL);

if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
  $client->setAccessToken($_SESSION['access_token']);
  $youtube = new Google_Service_YouTube($client);

  channelsListMine($youtube,
	  'id', 
	  array('mine' => true));
} else {
  $redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] . '/oauth2callback.php';
  header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
}


function channelsListMine($service, $part, $params) {
    $params = array_filter($params);
    $response = $service->channels->listChannels(
        $part,
        $params
    );

    print_r($response['modelData']['items'][0]['id']);
}

