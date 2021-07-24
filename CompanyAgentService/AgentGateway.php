<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require('CompanyAgentServiceAPI_011.php');

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
		if(count($uri) == 4){
			if($uri[3] == 'PartsG11'){
				$json = partsAll_011();
			}
			else if($uri[3] == 'ClientsG11'){
				$json = clientsAll_011();
			}
			else if($uri[3] == 'POG11'){
			$json = poAll_011();
			}
			else
				http_response_code(404);
		}
		else{
			if($uri[3] == 'PartsG11'){
				$json = parts_011($uri[4]);
			}
			else if($uri[3] == 'ClientsG11'){
				$json = clients_011($uri[4]);
			}
			else if($uri[3] == 'POG11'){
				$json = po_011($uri[4]);
			}
			else if($uri[3] == 'POFG11'){
				$json = pof_011($uri[4]);
			}
			else
				http_response_code(404);
		}
	}
	else if($request == "PUT"){
		$json = json_encode("Rows: 0");
		$json_str = file_get_contents('php://input');
		$payload = json_decode($json_str,TRUE);
		if($uri[3] == 'POStG11'){
			if(isset($payload['id']) and isset($payload['status'])){
				$json = poSt_011($payload);
			}
		}
		else if($uri[3] == 'PartsG11'){
			if(isset($payload['id']) and (isset($payload['name']) or isset($payload['desc']) or isset($payload['price']) or isset($payload['qty']))){
				$json = updateParts_011($payload);
			}
		}
		else if($uri[3] == 'ClientsG11'){
			if(isset($payload['id']) and (isset($payload['name']) or isset($payload['city']) or isset($payload['pswd']) or isset($payload['blnc']))){
				$json = updateClient_011($payload);
			}
		}
		else
			http_response_code(404);
	}
}
echo $json;
?>