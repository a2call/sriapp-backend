<?php

//Config
require_once(dirname(dirname(dirname(__FILE__))). '/config.php');

//*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-
//Encryption/Decryption
require_once(MCRYPT_FRAMEWORK);


//*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-
//Database
require(MEEKRO_FRAMEWORK);
\DB::$user = MS_U;
\DB::$password = MS_P;
\DB::$dbName = MS_D;
//\DB::debugMode(true);

//*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-
//Slim for REST interface
require(SLIM_FRAMEWORK);
\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();


//*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-
//validateAuthorization
function authenticate() {

    $headers = apache_request_headers();
    if(isset($headers["X-Authorization"])) {

        $apiKey = $headers["X-Authorization"];

        require_once(SERVICES_PATH . "UsersService.php");
        $user = UsersService::loadUserByApiKey($apiKey);

        if(empty($user)) {
            echoAccessDeniedResponse("Invalid key");
        } else {
            global $userId;
            $userId = $user->id;
        }

    } else {
        echoAccessDeniedResponse("No key");
    }
}



//*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-
//Response methods
function echoResponse($status_code, $response) {
    $app = \Slim\Slim::getInstance();
    $app->status($status_code);
    $app->contentType('application/json');
    echo json_encode($response);
}
function echoAccessDeniedResponse($message) {
    $app = \Slim\Slim::getInstance();
    $app->status(401);
    $response["errorMessage"] = "Access denied: " . $message;
    echo json_encode($response);
    $app->stop();
}