<?php // Set Environment

class ENVR {
	public static $envr;

	public static function setEnvironment($environment = NULL) {

		$url = $_SERVER['HTTP_HOST'];

		if($environment != NULL) {
			ENVR::$envr = $environment;
			return null;
		}

		if(strpos($url, '.local') !== FALSE) {
			$envr = 'LOCAL';
			error_reporting(E_ALL);
			ini_set('display_errors', 'On');
		} else if(strpos($url, 'dev.') !== FALSE) {
			$envr = 'DEVELOPMENT';
		} else if(strpos($url, 'staging.') !== FALSE) {
			$envr = 'STAGING';
		} else {
			$envr = 'PRODUCTION';
			error_reporting(0);
			ini_set('display_errors', 0);
		}

		return $envr;
	}
}

define ('ENVR', ENVR::setEnvironment()); # Initialize + Define