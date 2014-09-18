<?php

require_once(dirname(dirname(__FILE__)) . '/' . 'RestConfig.php');
require_once(SERVICES_PATH . 'SuppliersService.php');

//*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-
$app->post("/", "authenticate", function() use($app) {

    //Get JSON body
    $bodyJson = json_decode($app->request->getBody());

    //Parse supplier
    $incomingSupplier = new Supplier();
    $incomingSupplier->fromJsonObject($bodyJson->supplier);

    //Assign UserId
    global $userId;
    $incomingSupplier->setUserId($userId);

    //Check for existing Supplier
    $supplier = SuppliersService::load($incomingSupplier->id);
    if(empty($supplier)) {
        SuppliersService::insert($incomingSupplier);
    } else {

        //Check if update required
        if(SuppliersService::updateRequired($supplier, $incomingSupplier)) {
            SuppliersService::update($incomingSupplier);
        }

    }

	$response["errorMessage"] = "";
    echoResponse(200, $response);
});


//*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-
$app->get('/(:id)', "authenticate", function($id = "") use($app) {

    $suppliers = array();

    if(!empty($id)) {
        $supplier = SuppliersService::load($id);
        if(!empty($supplier)) {
            array_push($suppliers, $supplier);
        } else {
            $response["errorMessage"] = "Proveedor no encontrado.";
        }
    } else {
        $suppliers = SuppliersService::loadAll();
    }

    $response["suppliers"] = $suppliers;
    echoResponse(200, $response);

});


//*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-
$app->run();
