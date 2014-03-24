<?php // Authentication - Base on: http://www.thebuzzmedia.com/designing-a-secure-rest-api-without-oauth-authentication/

class Auth {

	private static $serverPublicKey = "123456";
	private static $serverPrivateKey = "098765";
	private static $postData, $clientHash, $clientPublicKey, $clientDataRequest, $clientTimestamp;

	function __construct($postData){
		self::$clientPublicKey = $postData['publicKey'];
		self::$clientHash = $postData['hash'];
		self::$clientDataRequest = $postData['dataRequest'];
		self::$clientTimestamp = $postData['timestamp'];		
	}

	private static function serverTimestamp(){
		// month.date.year // -hours:minutes | ex: 12.31.2014 // -15:56
		return gmdate('m.d.Y'); // -H:i
	}

	public static function generateServerHash(){
		return hash_hmac('sha256', self::$serverPrivateKey, self::$serverPublicKey . self::$clientDataRequest . self::serverTimestamp() );
	}

	private static function validatePublicKey(){
		if(self::$serverPublicKey == self::$clientPublicKey){ return true; } else { return false; }
	}

	private static function validateTimestamp(){
		// Note: Expand to ensure client and server timestamps match within 5-15 mins
		if(self::serverTimestamp() == self::$clientTimestamp){ return true; } else { return false; };
	}

	private static function validateHash(){
		if(self::generateServerHash() == self::$clientHash){ return true; } else { return false; }
	}

	public static function validator(){
		if(self::validatePublicKey() == true){
			if(self::validateTimestamp() == true){
				if(self::validateHash() == true){
					return 'valid';
				} else { return "Error: Hash Invalid"; }
			} else { return "Error: Timestamp Invalid"; }
		} else { return "Error: Public Key Invalid"; }
	}

}

$postData = array(
	'publicKey' => '123456',
	'hash' => 'a3021efe69bd527e284be4273938af0077e56e2b3da103b87b80c0ddadcf3ee5',
	'dataRequest' => '?foo=bar&blah=stuff',
	'timestamp' => '03.24.2014'
);
$auth = new Auth($postData);
$validator = $auth->validator();
if($validator != 'valid'){ echo $validator; break; } else { echo "Data will display..."; break; }
