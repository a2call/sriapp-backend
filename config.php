<?php

define('LIBS_PATH', dirname(__FILE__) . '/libs/');

define('SLIM_PATH', LIBS_PATH . '/Slim/');
define('SLIM_FRAMEWORK', SLIM_PATH . 'Slim.php');

define('MEEKRO_PATH', LIBS_PATH . '/Meekro/');
define('MEEKRO_FRAMEWORK', MEEKRO_PATH . 'meekrodb.2.3.class.php');

define('MCRYPT_PATH', LIBS_PATH . '/MCrypt/');
define('MCRYPT_FRAMEWORK', MCRYPT_PATH . 'MCrypt.php');

define('MODEL_PATH', dirname(__FILE__) . '/model/');
define('SERVICES_PATH', dirname(__FILE__) . '/services/');
define('DATABASE_PATH', dirname(__FILE__) . '/database/');

define("MS_D", "sriapp");
define("MS_U", "root");
define("MS_P", "root");

define("CLIENT_ID", "5793321-qpoidmcgu895275iojefabeadjliuoimyu9480.sriapp.com");
define("API_KEY_SALT", "C0c0l0n!y4ndr3$#");

//Debug
error_reporting(E_ALL);
ini_set('display_errors', 'on');