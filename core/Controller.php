<?php // Controller

// ********************
// *
// THIS FILE IS DEPRECIATED (remove asap)
// *
// ********************

class Controller {

	public function init(){

		$name = $_GET['name'];
		$offerData = Controller::getOfferData($name);

		if(isset($name)){
			// Price Responce 
			!empty($offerData) ? Controller::responce(200, "Offer Found" , $offerData) : Controller::responce(200, "Offer Not Found" , null);
		} else {
			// Invalid Responce
			Controller::responce(400, "Invalid Responce" , null);
		}
		
	}

	public function getOfferData($find = null){
		
		// *** REMOVE ***
		$offer = array("dallas" => 299, "chicago" => 348, "blah" => array("dataPoint" => 'foo', "dataPoint2" => "bar"));
		// *** /REMOVE ***

		// Loop and Return Offer Price
		foreach ($offer as $offerTitle => $offerContent) {
			if($offerTitle == $find){ return $offerContent; }
		}

	}

	// NOTE: MOVE TO RESPONCE
	public function responce($status = '200', $status_message = 'Default Mesage', $data = null){

		header("HTTP/1.1 $status $status_message");

		$responce = array(
			'api_version' => API_VERSION,
			'status' => $status,
			'status_message' => $status_message,
			'data' => $data,
			'dev_only' => array(
				'environment' => ENVR,
				'DB_NAME' => DB_NAME,
				'DB_HOST' => DB_HOST,
				'DB_USER' => DB_USER,
				'DB_PASS' => DB_PASS
			)
		);
		echo json_encode($responce);

	}

}