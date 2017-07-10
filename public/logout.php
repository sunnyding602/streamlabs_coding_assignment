<?php
require_once getenv('SITE_ROOT').'/include/init.php';
require_once getenv('SITE_ROOT').'/vendor/autoload.php';
logout();
$redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] . '/';
header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));