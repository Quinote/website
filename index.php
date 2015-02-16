<?php
/* The landing page for the app. */

//
//Libraries:
//
require 'utils.php';
require 'credentials.php';
require 'lib/apiclient2/autoload.php';
require 'lib/apiclient2/src/Google/Http/Request.php';
//require 'lib/apiclient2/src/Google/Client.php';
/*require 'lib/apiclient/contrib/Google_DriveService.php';
require 'lib/apiclient/contrib/Google_Oauth2Service.php';*/

//Begin session
session_start();

//
//Initialize Client
//
$client_id = CLIENT_ID;
$client_secret = CLIENT_SECRET;
$redirect_uri = REDIRECT_URI.'/lib/apiclient2/examples/fileupload.php';


//Here is the client and service as initialized by the apiclient2 libraries
$client = new Google_Client();
$client->setClientId($client_id);
$client->setClientSecret($client_secret);
$client->setRedirectUri($redirect_uri);
$client->addScope("https://www.googleapis.com/auth/drive");
$service = new Google_Service_Drive($client);


/*  //here is the client and service as initialized by the apiclient libraries
$client = new Google_Client();
$client->setClientId(CLIENT_ID);
$client->setClientSecret(CLIENT_SECRET);
$client->setRedirectUri(REDIRECT_URI);
$client->setScopes(array(
  'https://www.googleapis.com/auth/drive',
  'https://www.googleapis.com/auth/userinfo.email',
  'https://www.googleapis.com/auth/userinfo.profile',
  'https://www.googleapis.com/auth/drive.install'
));
$service = new Google_DriveService($client);*/

////
// Authentication Business
////
if ($user = get_user()) {
  $client->setAccessToken($user->tokens);
}


echo 'reached';

////
//Google passes in a state variable accessed by GET
////
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
$fileId = '0BzyCBwx5XmTBYUxodWE4YkNobFk'; //hanging onto this instead of accessing dynamically, for debugging purposes


function printFile($service, $fileId) {
  try {
    $file = $service->files->get($fileId);

    print "Title: " . $file->getTitle();
    print "Description: " . $file->getDescription();
    print "MIME type: " . $file->getMimeType();
  } catch (Exception $e) {
    print "An error occurred: " . $e->getMessage();
  }
}

printFile($service,$fileId);

/*
 * Download a file's content.
 *
 * @param Google_DriveService $service Drive API service instance.
 * @param File $file Drive File instance.
 * @return String The file's content if successful, null otherwise.
 */
function downloadFile($service, $client, $file) {
  $downloadUrl = $file->getDownloadUrl();
  if ($downloadUrl) {
    $request = new Google_Http_Request($downloadUrl, 'GET', null, null);
    //$httpRequest = $client->getAuth()->sign($request);
    //$httpRequest = Google_Client::$io->authenticatedRequest($request);
    //$httpRequest = $client->$io->authenticatedRequest($request);
    $SignhttpRequest = $client->getAuth()->sign($request);
    $httpRequest = $client->getIo()->makeRequest($SignhttpRequest);
    
    if($httpRequest->getResponseHttpCode() == 200) {
      return $request->getResponseBody();
      } else { return $httpRequest->getResponseHttpCode(); }
      }
}

echo downloadFile($service, $client, $service->files->get($fileId));

?>