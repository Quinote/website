<?php

require 'utils.php';
require 'credentials.php';
require 'lib/apiclient/Google_Client.php';
require 'lib/apiclient/contrib/Google_DriveService.php';
require 'lib/apiclient/contrib/Google_Oauth2Service.php';
require_once 'HTTP/Request2.php';
//require '../php/.registry/http_request2.reg';

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

/*state variable passed from google, json object -> php var*/
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
        if (isset($state->parentId)) { /*the folder id*/
          $_SESSION['parentId'] = $state->parentId;
        } else {
          $_SESSION['parentId'] = null;
        }
      } else {
        $error = 'State is empty. You probably went directly to www.quinote.com instead of via open with or create.';
        throw new Exception($error);
      }
    
    
    //$response = http_get('https://www.googleapis.com/drive/v2/files/'.$_SESSION['fileIds'], array("key"=>CLIENT_ID), $info );
    
   /* $request = new HTTP_Request2('http://pear.php.net/bugs/search.php',
                             HTTP_Request2::METHOD_GET, array('use_brackets' => true));*/
    $request = new HTTP_Request2('https://www.googleapis.com/drive/v2/files/'.$_SESSION['fileIds'], array("key"=>CLIENT_ID));
    
    var_dump($request)
    
    

?>