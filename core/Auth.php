<?php // Authentication

class Auth {

	private static $serverPublicKey = "123456";
	private static $serverPrivateKey = "7c3ab1490896995ea69705f5e83651129e752671f78199d294241328a4f078e4";
	private static $postData, $clientHash, $clientPublicKey, $clientDataRequest, $clientTimestamp;

	function __construct($postData){
		self::$clientHash = $postData['hash'];
			self::$clientPublicKey = $postData['publicKey'];
			self::$clientDataRequest = $postData['dataRequest'];
			self::$clientTimestamp = $postData['timestamp'];		
	}

	private static function serverTimestamp(){
		// month.date.year // -hours:minutes | ex: 12.31.2014 // -15:56
		return gmdate('m.d.Y'); // -H:i
	}

	private static function generateServerHash(){
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
				} else { return "Error: Hash"; }
			} else { return "Error: Timestamp"; }
		} else { return "Error: Public Key"; }
	}

}

$postData = array(
	'publicKey' => '123456',
	'hash' => 'f9c088a9491157c5ecc521ce0456995b1452f390e0f51a3bdeaa4d5821b56e9b',
	'dataRequest' => '?foo=bar&blah=stuff',
	'timestamp' => '03.24.2014'
);
$auth = new Auth($postData);
$validator = $auth->validator();
if($validator != 'valid'){ echo $validator; break; } else { echo "Data will display..."; break; }
