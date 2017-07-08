<?php
require_once getenv('SITE_ROOT').'/include/init.php';
require_once getenv('SITE_ROOT').'/vendor/autoload.php';

$client = new Google_Client();
$client->setAuthConfigFile(getenv('SITE_ROOT').'/config/client_secrets.json');
$client->setRedirectUri('http://' . $_SERVER['HTTP_HOST'] . '/oauth2callback.php');
$client->addScope(Google_Service_YouTube::YOUTUBE_FORCE_SSL);

if (! isset($_GET['code'])) {
  $auth_url = $client->createAuthUrl();
  header('Location: ' . filter_var($auth_url, FILTER_SANITIZE_URL));
} else {
  $client->authenticate($_GET['code']);
  $_SESSION['access_token'] = $client->getAccessToken();
  $redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] . '/';
  header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
}
