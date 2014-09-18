<?php

require_once(dirname(dirname(__FILE__)) . '/' . 'RestConfig.php');
require_once(SERVICES_PATH . 'UsersService.php');


//*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-
//Register
$app->post('/register', function() use ($app) {

    //Get Json body
    $bodyJson = json_decode($app->request->getBody());

    //Parse user
    $user = User::fromJsonObject($bodyJson->user);

    //Check for already registered user
    if(UsersService::checkLogin($user->login, $user->password)) {
        $response["status"] = UsersService::$USER_ALREADY_REGISTERED;
        $response["errorMessage"] = "Usuario ya registrado.";
        echoResponse(200, $response);
        return;
    }

    //Generate Api Key for User
    $user->apiKey = UsersService::generateApiKey();

    //New user
    $user = UsersService::newUser($user);

    //Check for errors
    if(!empty($user)) {
        $response["status"] = UsersService::$USER_SUCCESSFULLY_REGISTERED;
        $response["user"] = $user;
        $response["errorMessage"] = "";
    } else {
        $response["status"] = UsersService::$USER_REGISTRATION_ERROR;
        $response["errorMessage"] = "Error al registrar Usuario.";
    }
    echoResponse(200, $response);

});


//*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-
//Login
$app->post('/login', function() use ($app) {

    //Get Json body
    $bodyJson = json_decode($app->request->getBody());

    //Parse User
    $user = User::fromJsonObject($bodyJson->user);

    //Check Login
    if(!UsersService::checkLogin($user->login, $user->password)) {
        $response["errorMessage"] = "Usuario y/o contraseña incorrecta.";
        echoResponse(200, $response);
        return;
    }

    //Return Api Key
    $user = UsersService::loadUserByLogin($user->login);
    $response["user"] = $user;
    $response["errorMessage"] = "";
    echoResponse(201, $response);

});


//*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-
//Run
$app->run();