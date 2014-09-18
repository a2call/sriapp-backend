<?php

class User {

    var $id;
    var $fullName;
    var $taxpayerId;
    var $login;
    var $password;
    var $apiKey;

    function User() {
        $this->id = 0;
        $this->fullName = "";
        $this->taxpayerId = "";
        $this->login = "";
        $this->password = "";
        $this->apiKey = "";
    }

    static function fromJson($jsonString) {
        $data = json_decode($jsonString);
        return User::fromJsonObject($data);
    }

    static function fromJsonObject($data) {
        $user = new User();

        if(!empty($data->id)) $user->id = $data->id;
        if(!empty($data->fullName)) $user->fullName = $data->fullName;
        if(!empty($data->taxpayerId)) $user->taxpayerId = $data->taxpayerId;
        if(!empty($data->login)) $user->login = $data->login;
        if(!empty($data->password)) $user->password = $data->password;
        if(!empty($data->apiKey)) $user->apiKey = $data->apiKey;

        return $user;
    }

    function toJson() {
        return json_encode($this);
    }

    static function fromCursor($cursor) {
        $user = new User();

        if(array_key_exists("id", $cursor)) $user->id = $cursor["id"];
        if(array_key_exists("full_name", $cursor)) $user->fullName = $cursor["full_name"];
        if(array_key_exists("taxpayer_id", $cursor)) $user->taxpayerId = $cursor["taxpayer_id"];
        if(array_key_exists("login", $cursor)) $user->login = $cursor["login"];
        if(array_key_exists("password", $cursor)) $user->password = $cursor["password"];
        if(array_key_exists("api_key", $cursor)) $user->apiKey = $cursor["api_key"];

        return $user;
    }
}