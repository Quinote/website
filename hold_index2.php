<?php

//libraries
require 'utils.php';
require 'credentials.php';
require 'lib/Slim/Slim.php';
require 'lib/apiclient/Google_Client.php';
require 'lib/apiclient/contrib/Google_DriveService.php';
require 'lib/apiclient/contrib/Google_Oauth2Service.php';

set_include_path(get_include_path() . PATH_SEPARATOR . 'lib/apiclient2/src');

require_once 'HTTP/Request2.php';

\Slim\Slim::registerAutoloader();
session_start();

// create a new Slim app.
$app = new \Slim\Slim(array(
  'templates.path' => './public',
));

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

echo 'reached1';

// if there is an existing session, set the access token
if ($user = get_user()) {
  $client->setAccessToken($user->tokens);
}

 if($client->isAccessTokenExpired()) {
     $client->authenticate();
     $NewAccessToken = json_decode($client->getAccessToken());
     $client->refreshToken($NewAccessToken->refresh_token);
}

$service = new Google_DriveService($client);



//state variable passed from google, json object -> php var
if (isset($_GET['state'])) {
        $state = json_decode(stripslashes($_GET['state']));
        $_SESSION['mode'] = $state->action;

        if (isset($state->ids)){
          $_SESSION['fileIds'] = $state->ids;
        } else {
          $_SESSION['fileIds'] = array();
        }
        if (isset($state->userId)) {
          $_SESSION['userId'] = $state->userId;
        } else {
          $_SESSION['userId'] = null;
        }
        if (isset($state->parentId)) { //the folder id
          $_SESSION['parentId'] = $state->parentId;
        } else {
          $_SESSION['parentId'] = null;
        }
      } else {
        $error = 'State is empty. You probably went directly to www.quinote.com instead of via open with or create.';
        throw new Exception($error);
      }     


?>