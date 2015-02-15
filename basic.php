<?php

require 'utils.php';
require 'credentials.php';
require 'lib/apiclient/Google_Client.php';
require 'lib/apiclient/contrib/Google_DriveService.php';
require 'lib/apiclient/contrib/Google_Oauth2Service.php';

$client = new Google_Client();
$client->setClientId(CLIENT_ID);
$client->setClientSecret(CLIENT_SECRET);
$client->setRedirectUri(REDIRECT_URI);
$client->setScopes(array(  'https://www.googleapis.com/auth/drive',
  'https://www.googleapis.com/auth/userinfo.email',
  'https://www.googleapis.com/auth/userinfo.profile',
  'https://www.googleapis.com/auth/drive.install'
));
$client->setUseObjects(true);

var_dump($_GET['state']);

?>