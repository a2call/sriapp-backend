<?php

require_once(dirname(dirname(__FILE__)) . '/config.php');
require_once(MODEL_PATH . 'Purchase.php');
require_once(DATABASE_PATH . 'PurchaseTable.php');

class PurchasesServices {

    static function save(Purchase $incomingPurchase) {
        $purchase = PurchasesServices::load($incomingPurchase->id);
        if(empty($purchase)) {
            PurchasesServices::insert($incomingPurchase);
        } else {
            PurchasesServices::update($incomingPurchase);
        }
    }

    static function load($id) {
        $record = DB::queryFirstRow("SELECT * FROM " . PurchaseTable::$TABLE_NAME . " WHERE id=%s", $id);
        if(!empty($record)) {
            $purchase = PurchasesServices::purchaseFromCursor($record);
            return $purchase;
        }

        return null;
    }

    static function loadAll() {
        $purchases = array();
        $results = DB::query("SELECT * FROM " . PurchaseTable::$TABLE_NAME);
        foreach ($results as $record) {
            array_push($purchases, PurchasesServices::purchasefromCursor($record));
        }
        return $purchases;
    }

    static function getAllPurchasesSince($since) {
        global $userId;

        $purchases = array();
        $results = DB::query(
            "SELECT * FROM "
            . PurchaseTable::$TABLE_NAME
            . " WHERE "
            . PurchaseTable::$HASH_ON . " > " . $since
            . " AND " . PurchaseTable::$USER_ID . " = " . $userId
            . " ORDER BY "
            . PurchaseTable::$HASH_ON . " ASC"
        );
        foreach ($results as $record) {
            array_push($purchases, PurchasesServices::purchaseFromCursor($record));
        }
        return $purchases;
    }

    static function purchaseFromCursor($cursor) {
        $purchase = new Purchase();

        if(array_key_exists(PurchaseTable::$ID, $cursor))
            $purchase->id = $cursor[PurchaseTable::$ID];

        if(array_key_exists(PurchaseTable::$USER_ID, $cursor))
            $purchase->userId = $cursor[PurchaseTable::$USER_ID];

        if(array_key_exists(PurchaseTable::$SUPPLIER_ID, $cursor))
            $purchase->supplierId = $cursor[PurchaseTable::$SUPPLIER_ID];

        if(array_key_exists(PurchaseTable::$DATE, $cursor))
            $purchase->date = $cursor[PurchaseTable::$DATE];

        if(array_key_exists(PurchaseTable::$ESTABLISHMENT_CODE, $cursor))
            $purchase->establishmentCode = $cursor[PurchaseTable::$ESTABLISHMENT_CODE];

        if(array_key_exists(PurchaseTable::$POINT_OF_SALE_CODE, $cursor))
            $purchase->pointOfSaleCode = $cursor[PurchaseTable::$POINT_OF_SALE_CODE];

        if(array_key_exists(PurchaseTable::$SEQUENTIAL_CODE, $cursor))
            $purchase->sequentialCode = $cursor[PurchaseTable::$SEQUENTIAL_CODE];

        if(array_key_exists(PurchaseTable::$SUBTOTAL_12, $cursor))
            $purchase->subtotal12 = $cursor[PurchaseTable::$SUBTOTAL_12];

        if(array_key_exists(PurchaseTable::$SUBTOTAL_0, $cursor))
            $purchase->subtotal0 = $cursor[PurchaseTable::$SUBTOTAL_0];

        if(array_key_exists(PurchaseTable::$TAX, $cursor))
            $purchase->tax = $cursor[PurchaseTable::$TAX];

        if(array_key_exists(PurchaseTable::$TOTAL, $cursor))
            $purchase->total = $cursor[PurchaseTable::$TOTAL];

        if(array_key_exists(PurchaseTable::$FEEDING, $cursor))
            $purchase->feeding = $cursor[PurchaseTable::$FEEDING];

        if(array_key_exists(PurchaseTable::$HEALTH, $cursor))
            $purchase->health = $cursor[PurchaseTable::$HEALTH];

        if(array_key_exists(PurchaseTable::$CLOTHING, $cursor))
            $purchase->clothing = $cursor[PurchaseTable::$CLOTHING];

        if(array_key_exists(PurchaseTable::$EDUCATION, $cursor))
            $purchase->education = $cursor[PurchaseTable::$EDUCATION];

        if(array_key_exists(PurchaseTable::$DWELLING, $cursor))
            $purchase->dwelling = $cursor[PurchaseTable::$DWELLING];

        if(array_key_exists(PurchaseTable::$HASH, $cursor))
            $purchase->hash = $cursor[PurchaseTable::$HASH];

        if(array_key_exists(PurchaseTable::$HASH_ON, $cursor))
            $purchase->hashOn = $cursor[PurchaseTable::$HASH_ON];

        return $purchase;
    }

    static function insert(Purchase $purchase) {
        DB::insert(PurchaseTable::$TABLE_NAME, array(
            PurchaseTable::$ID => $purchase->id,
            PurchaseTable::$USER_ID => $purchase->getUserId(),
            PurchaseTable::$SUPPLIER_ID => $purchase->supplierId,
            PurchaseTable::$DATE => $purchase->date,
            PurchaseTable::$ESTABLISHMENT_CODE => $purchase->establishmentCode,
            PurchaseTable::$POINT_OF_SALE_CODE => $purchase->pointOfSaleCode,
            PurchaseTable::$SEQUENTIAL_CODE => $purchase->sequentialCode,
            PurchaseTable::$SUBTOTAL_12 => $purchase->subtotal12,
            PurchaseTable::$SUBTOTAL_0 => $purchase->subtotal0,
            PurchaseTable::$TAX => $purchase->tax,
            PurchaseTable::$TOTAL => $purchase->total,
            PurchaseTable::$FEEDING => $purchase->feeding,
            PurchaseTable::$HEALTH => $purchase->health,
            PurchaseTable::$EDUCATION => $purchase->education,
            PurchaseTable::$CLOTHING => $purchase->clothing,
            PurchaseTable::$DWELLING => $purchase->dwelling,
            PurchaseTable::$HASH => $purchase->hash,
            PurchaseTable::$HASH_ON => $purchase->hashOn
        ));
    }

    static function update(Purchase $purchase) {
        DB::update(PurchaseTable::$TABLE_NAME, array(
            PurchaseTable::$USER_ID => $purchase->getUserId(),
            PurchaseTable::$SUPPLIER_ID => $purchase->supplierId,
            PurchaseTable::$DATE => $purchase->date,
            PurchaseTable::$ESTABLISHMENT_CODE => $purchase->establishmentCode,
            PurchaseTable::$POINT_OF_SALE_CODE => $purchase->pointOfSaleCode,
            PurchaseTable::$SEQUENTIAL_CODE => $purchase->sequentialCode,
            PurchaseTable::$SUBTOTAL_12 => $purchase->subtotal12,
            PurchaseTable::$SUBTOTAL_0 => $purchase->subtotal0,
            PurchaseTable::$TAX => $purchase->tax,
            PurchaseTable::$TOTAL => $purchase->total,
            PurchaseTable::$FEEDING => $purchase->feeding,
            PurchaseTable::$HEALTH => $purchase->health,
            PurchaseTable::$EDUCATION => $purchase->education,
            PurchaseTable::$CLOTHING => $purchase->clothing,
            PurchaseTable::$DWELLING => $purchase->dwelling,
            PurchaseTable::$HASH => $purchase->hash,
            PurchaseTable::$HASH_ON => $purchase->hashOn
        ), "id=%s", $purchase->id);
    }

    static function updateRequired($from, $to) {
        $updateRequired = $from->hash != $to->hash;
        $newerData = $from->hashOn < $to->hashOn;
        return $updateRequired && $newerData;
    }
}