<?php error_reporting(0); ini_set('display_errors', 0); // Error Reporting

define('API_VERSION', 1);
define('API_NAME', 'Jiffy Lube Marketing API');

define('DS', '/');
define('BASE_ROOT', __DIR__ . DS);

require_once(BASE_ROOT . 'config' . DS . 'bootstrap.php');

// ...
header("Content-Type:application/json");
Controller::init();