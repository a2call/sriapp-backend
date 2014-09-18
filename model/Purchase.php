<?php

class Purchase {
    var $id;
    var $userId;
    var $supplierId;
    var $date;
    var $establishmentCode;
    var $pointOfSaleCode;
    var $sequentialCode;
    var $subtotal12;
    var $subtotal0;
    var $tax;
    var $total;
    var $feeding;
    var $health;
    var $education;
    var $clothing;
    var $dwelling;

    var $hash;
    var $hashOn;

    function Purchase() {
        $this->id = "";
        $this->userId = 0;
        $this->supplierId = "";
        $this->date = 0;
        $this->establishmentCode = 0;
        $this->pointOfSaleCode = 0;
        $this->sequentialCode = 0;
        $this->subtotal12 = 0;
        $this->subtotal0 = 0;
        $this->tax = 0;
        $this->total = 0;
        $this->feeding = 0;
        $this->health = 0;
        $this->education = 0;
        $this->clothing = 0;
        $this->dwelling = 0;

        $this->hash = "";
        $this->hashOn = 0;
    }

    function setUserId($userId) {
        $this->userId = $userId;
    }

    function getUserId() {
        return $this->userId;
    }

    function fromJson($jsonString) {
        $jsonObject = json_decode($jsonString);
        $this->fromJsonObject($jsonObject);
    }

    function fromJsonObject($jsonObject) {
        $this->Purchase();

        $classVars = get_class_vars(get_class($this));
        foreach ($classVars as $name => $value) {
            if(isset($jsonObject->$name)) {
                $this->$name = $jsonObject->$name;
            }
        }
    }

    function toJson() {
        return json_encode($this);
    }

} 