<?php

require_once(dirname(dirname(__FILE__)) . '/' . 'RestConfig.php');
require_once(SERVICES_PATH . 'PurchasesService.php');

$app->post("/", "authenticate", function() use($app) {

    //Get JSON body
    $bodyJson = json_decode($app->request->getBody());

    //Parse purchase
    $incomingPurchase = new Purchase();
    $incomingPurchase->fromJsonObject($bodyJson->purchase);

    //Assign UserId
    global $userId;
    $incomingPurchase->setUserId($userId);

    //Check for existing purchase
    $purchase = PurchasesServices::load($incomingPurchase->id);
    if(empty($purchase)) {
        PurchasesServices::insert($incomingPurchase);
    } else {
        //Check if update required
        $updateRequired = PurchasesServices::updateRequired($purchase, $incomingPurchase);
        if($updateRequired) {
            PurchasesServices::update($incomingPurchase);
        }
    }

	$response["errorMessage"] = "";
    echoResponse(200, $response);
});


$app->get('/(:id)', "authenticate", function($id = "") use($app) {

    $purchases = array();

    if(!empty($id)) {
        $purchase = PurchasesServices::load($id);
        if(!empty($purchase)) {
            array_push($purchases, $purchase);
        } else {
            $response["errorMessage"] = "Compra no encontrada.";
        }
    } else {
        $purchases = PurchasesServices::loadAll();
    }

    $response["purchases"] = $purchases;
    echoResponse(200, $response);

});

$app->run();
