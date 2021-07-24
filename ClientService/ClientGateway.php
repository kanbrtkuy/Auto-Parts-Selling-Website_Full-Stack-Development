<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require('ClientServiceAPI.php');

$json = json_encode("Error: Not Found");

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode( '/', $uri );
//print_r($uri);

if(count($uri)<4 or count($uri)>5){
	http_response_code(404);
}
else{
	$request = $_SERVER['REQUEST_METHOD'];
	if($request == "GET"){
		if($uri[3] == 'PartsG11'){
			$json = parts_011();
		}
		else if($uri[3] == 'AllPOG11'){
			$json = poAll_011($uri[4]);
		}
		else if($uri[3] == 'POG11'){
			$json = po_011($uri[4]);
		}
		else
			http_response_code(404);
	}
	else if($request == "PUT"){
		$json = json_encode("Rows: 0");
		$json_str = file_get_contents('php://input');
		$payload = json_decode($json_str,TRUE);
		if($uri[3] == 'NewOrderG11'){
			if(isset($payload['id']) and isset($payload['lines'])){
				$json = newOrder_011($payload);
			}
		}
		else if($uri[3] == 'CancelG11'){
			if(isset($payload['cus_id']) and isset($payload['id'])){
				$json = cancelOrder_011($payload);

			}
		}
		else
			http_response_code(404);
	}
}
echo $json;
?>