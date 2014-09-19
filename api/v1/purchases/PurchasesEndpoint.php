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


$app->get('/getAll', "authenticate", function() use($app) {

    $since = $app->request->get("since");
    $purchases = PurchasesServices::getAllPurchasesSince($since);

    //Get last records hashOn for newSince
    $newSince = $since;
    if(count($purchases) > 0) {
        $purchase = $purchases[count($purchases) - 1];
        $newSince = $purchase->hashOn + 1;
    }

    $response["purchases"] = $purchases;
    $response["newSince"] = $newSince;
    echoResponse(200, $response);

});

$app->run();
