<?php

require_once(dirname(dirname(__FILE__)) . '/config.php');
require_once(MODEL_PATH . 'User.php');

class UsersService {

    public static $TABLE_NAME = "user";

    //Status Codes
    public static $USER_SUCCESSFULLY_REGISTERED = 1;
    public static $USER_ALREADY_REGISTERED = 2;
    public static $USER_REGISTRATION_ERROR = 3;

    static function checkLogin($login, $password) {
        $count = DB::queryFirstField(
            "SELECT COUNT(*) FROM " . UsersService::$TABLE_NAME . " WHERE login= %s AND password = %s",
            $login,
            $password
        );

        return $count > 0;
    }

    static public function generateApiKey() {
        return md5(API_KEY_SALT . uniqid(rand(), true));
    }

    static function newUser(User $user) {
        try {
            DB::insert(UsersService::$TABLE_NAME, array(
                "full_name" => $user->fullName,
                "taxpayer_id" => $user->taxpayerId,
                "login" => $user->login,
                "password" => $user->password,
                "api_key" => $user->apiKey
            ));
            return $user;

        } catch (MeekroDBException $e) {
            return null;
        }
    }

    static function loadUserByLogin($login) {
        $record = DB::queryFirstRow("SELECT * FROM " . UsersService::$TABLE_NAME . " WHERE login= %s ", $login);
        $user = User::fromCursor($record);
        return $user;
    }

    static function loadUserByApiKey($apiKey) {
        $record = DB::queryFirstRow("SELECT * FROM " . UsersService::$TABLE_NAME . " WHERE api_key= %s ", $apiKey);
        if(!empty($record)) {
            $user = User::fromCursor($record);
            return $user;
        } else {
            return null;
        }
    }

}