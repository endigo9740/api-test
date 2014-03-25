<?php // Database Configuration

class DB_Config {
	public static $environment, $db_name, $db_user, $db_pass, $db_host;

	function __construct($environment){
		isset($environment) ? self::$environment = $environment : self::$environment = 'DEVELOPMENT';
	}

	public static function setConfiguration() {
		if(self::$environment == 'LOCAL') {
			self::$db_name = strtolower(str_replace(' ',API_NAME,'_')) . '_local'; // _main
			self::$db_user = 'root';
			self::$db_pass = 'root';
			self::$db_host = '127.0.0.1';
		} else if(self::$environment == 'DEVELOPMENT') {
			self::$db_name = strtolower(str_replace(' ',API_NAME,'_')) . '_dev';
			self::$db_user = 'root';
			self::$db_pass = 'root';
			self::$db_host = '127.0.0.1';
		} else if(self::$environment == 'STAGING') {
			self::$db_name = strtolower(str_replace(' ',API_NAME,'_')) . '_stage';
			self::$db_user = 'root';
			self::$db_pass = 'root';
			self::$db_host = '127.0.0.1';
		} else if(self::$environment == 'PRODUCTION') {
			self::$db_name = strtolower(str_replace(' ',API_NAME,'_')) . '_production';
			self::$db_user = 'root';
			self::$db_pass = 'root';
			self::$db_host = '127.0.0.1';
		}
	}

	public static function getConfiguration() {
		return array(
			'DB_NAME' => self::$db_name, 
			'DB_HOST' => self::$db_host,
			'DB_USER' => self::$db_user,
			'DB_PASS' => self::$db_pass
		);
	}
}

# Initialize
$dbConfig = new DB_Config(ENVR);
	$dbConfig->setConfiguration();
	$database = $dbConfig->getConfiguration();

# Define
define('DB_NAME', $database['DB_NAME']);
define('DB_HOST', $database['DB_HOST']);
define('DB_USER', $database['DB_USER']);
define('DB_PASS', $database['DB_PASS']);
