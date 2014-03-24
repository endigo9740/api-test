<?php
define('API_VERSION', 1);
define('API_NAME', 'API Test');
define('DS', '/');
define('BASE_ROOT', __DIR__ . DS);

require_once(BASE_ROOT . 'config' . DS . 'bootstrap.php');
define ('ENVR', ENVR::setEnvironment());

header("Content-Type:application/json");
Controller::init();