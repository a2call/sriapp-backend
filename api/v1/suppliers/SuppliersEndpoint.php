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
$app->get('/getAll', "authenticate", function() use($app) {

    $since = $app->request->get("since");
    $suppliers = SuppliersService::getAllSuppliersSince($since);

    //Get last records hashOn for newSince
    $newSince = $since;
    if(count($suppliers) > 0) {
        $supplier = $suppliers[count($suppliers) - 1];
        $newSince = $supplier->hashOn + 1;
    }

    $response["suppliers"] = $suppliers;
    $response["newSince"] = $newSince;
    echoResponse(200, $response);

});


//*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-
$app->run();
