<?php
/* The landing page for the app. */

//Libraries:
require 'utils.php';
require 'credentials.php';
require 'lib/apiclient2/autoload.php';

//Begin session
session_start();

//Initialize Client
$client = new Google_Client();
$client->setClientId($client_id);
$client->setClientSecret($client_secret);
$client->setRedirectUri($redirect_uri);
$client->addScope("https://www.googleapis.com/auth/drive");
$service = new Google_Service_Drive($client);

//Google passes in a state variable accessed by GET
function getStateVars() {
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
      }
      
var_dump($_SESSION['fileIds']);

?>