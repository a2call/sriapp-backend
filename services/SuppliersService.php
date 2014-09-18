<?php

require_once(dirname(dirname(__FILE__)) . '/config.php');
require_once(MODEL_PATH . 'Supplier.php');
require_once(DATABASE_PATH . 'SupplierTable.php');

class SuppliersService {

    static function save(Supplier $supplier) {
        if(!SuppliersService::exists($supplier->id)) {
            SuppliersService::insert($supplier);
        } else {
            SuppliersService::update($supplier);
        }
    }

    static function exists($id) {
        $count = DB::queryFirstField("SELECT COUNT(*) FROM " . SupplierTable::$TABLE_NAME . " WHERE id= %s", $id);
        return $count > 0;
    }

    static function load($id) {
        $record = DB::queryFirstRow("SELECT * FROM " . SupplierTable::$TABLE_NAME . " WHERE id=%s", $id);
        if(!empty($record)) {
            return SuppliersService::supplierfromCursor($record);
        }

        return null;
    }

    static function loadAll() {
        $suppliers = array();
        $results = DB::query("SELECT * FROM " . SupplierTable::$TABLE_NAME);
        foreach ($results as $record) {
            array_push($suppliers, SuppliersService::supplierfromCursor($record));
        }
        return $suppliers;
    }

    static function insert(Supplier $supplier) {
        DB::insert(SupplierTable::$TABLE_NAME, array(
            SupplierTable::$ID => $supplier->id,
            SupplierTable::$USER_ID => $supplier->getUserId(),
            SupplierTable::$CODE => $supplier->code,
            SupplierTable::$NAME => $supplier->name,
            SupplierTable::$HASH => $supplier->hash,
            SupplierTable::$HASH_ON => $supplier->hashOn
        ));
    }

    static function update(Supplier $supplier) {
        DB::update(SupplierTable::$TABLE_NAME, array(
            SupplierTable::$USER_ID => $supplier->getUserId(),
            SupplierTable::$CODE => $supplier->code,
            SupplierTable::$NAME => $supplier->name,
            SupplierTable::$HASH => $supplier->hash,
            SupplierTable::$HASH_ON => $supplier->hashOn
        ), "id=%s   ", $supplier->id);
    }

    static function supplierfromCursor($cursor) {
        $supplier = new Supplier();

        if(array_key_exists(SupplierTable::$ID, $cursor))
            $supplier->id = $cursor[SupplierTable::$ID];

        if(array_key_exists(SupplierTable::$CODE, $cursor))
            $supplier->code = $cursor[SupplierTable::$CODE];

        if(array_key_exists(SupplierTable::$NAME, $cursor))
            $supplier->name = $cursor[SupplierTable::$NAME];

        if(array_key_exists(SupplierTable::$HASH, $cursor))
            $supplier->hash = $cursor[SupplierTable::$HASH];

        if(array_key_exists(SupplierTable::$HASH_ON, $cursor))
            $supplier->hashOn = $cursor[SupplierTable::$HASH_ON];

        return $supplier;
    }

    static function updateRequired($from, $to) {
        $updateRequired = $from->hash != $to->hash;
        $newerData = $from->hashOn < $to->hashOn;
        return $updateRequired && $newerData;
    }
}