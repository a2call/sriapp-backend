<?php

class Supplier {

    var $id;
    protected $userId;
    var $code;
    var $name;

    var $hash;
    var $hashOn;

    function Supplier() {
        $this->id = "";
        $this->userId = 0;
        $this->code = "";
        $this->name = "";

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
        $data = json_decode($jsonString);
        $this->fromJsonObject($data);
    }

    function fromJsonObject($data) {
        $this->Supplier();

        if(!empty($data->id)) $this->id = $data->id;
        if(!empty($data->userId)) $this->userId = $data->userId;
        if(!empty($data->code)) $this->code = $data->code;
        if(!empty($data->name)) $this->name = $data->name;
        if(!empty($data->hash)) $this->hash = $data->hash;
        if(!empty($data->hashOn)) $this->hashOn = $data->hashOn;

    }

    function toJson() {
        return json_encode($this);
    }

}