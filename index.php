<?php
define('API_VERSION', 1);
define('API_NAME', 'API Test');
define('DS', '/');
define('BASE_ROOT', __DIR__ . DS);
define('CURRENT_URL', preg_replace('{^/|\?.*}','',$_SERVER['REQUEST_URI']));

require_once(BASE_ROOT . 'config' . DS . 'bootstrap.php');

header("Content-Type:application/json");
// Controller::init();