<?php

function init(){

	if(!empty($_GET['name'])){
		$name = $_GET['name'];
		$price = getPrice($name);

		// Display Price Responce
		if(!empty($price)){
			responce(200, "Book Found" , $price);
		} else {
			responce(200, "Book Not Found" , null);
		}

	} else {
		// Invalid Responce
		responce(400, "invalid responce" , null);
	}
	
}

// Lookup Book Price
function getPrice($find){
	
	$books = array(
		"java" => 299,
		"c"    => 348,
		"php"  => array(
			"price" => 267,
			"foo" => "bar"
		)
	);

	// Loop and Return Book Price
	foreach ($books as $bookTitle => $bookPrice) {
		if($bookTitle == $find){
			return $bookPrice;
			break;
		}
	}

}

// Display View Responce (json)
function responce($status, $status_message, $data){

	header("HTTP/1.1 $status $status_message");

	$responce['version'] = "1";
	$responce['status'] = $status;
	$responce['status_message'] = $status_message;
	$responce['data'] = $data;

	echo json_encode($responce);

}