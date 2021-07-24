<?php 

#header("Content-Type: application/json");
date_default_timezone_set('America/Halifax');

$json = "Error: Function Not Found";

function connect(){
	$db_hostname = 'db.cs.dal.ca';  
	$db_username = 'zhaohe'; 
	$db_password = '3RY2nybdcyxhzxHHiQ8UUpDFQ'; 
	$db_database = 'zhaohe'; 
	$conn = new mysqli($db_hostname,$db_username,$db_password,$db_database);
	if($conn->connect_error){
    	die("Connection failed" . mysqli_connect_error);
	}
	return $conn;
}

function parts_011(){
	$conn = connect();
	if($conn->connect_error){
    	die("Connection failed" . mysqli_connect_error);
	}
	http_response_code(200);
	$stmt = "SELECT partName_011 AS Name, partDescription_011 AS Description, partQty_011 AS Qty FROM Parts_011;";
	$payload = $conn->query($stmt);
	if ($payload->num_rows > 0){
		$i=0;
		while($rows = $payload->fetch_assoc()){
			$result[$i]=$rows;
			$i++;
		}
	}
	if(isset($result)){
		$json = json_encode($result);
	}
	return $json;
}

function poAll_011($cus_id){
	$conn = connect();
	if($conn->connect_error){
    	die("Connection failed" . mysqli_connect_error);
	}
	http_response_code(200);
	$stmt = "SELECT poNo_011 AS 'PO Number', datePO_011 AS 'Date', status_011 AS 'Status' FROM PO_011 WHERE clientCompId_011 = ?;";
	$handle=$conn->prepare($stmt);
	$handle -> bind_param('i',$cus_id);
	$handle -> execute();
	$payload=$handle->get_result();
	if ($payload->num_rows > 0){
		$i=0;
		while($rows = $payload->fetch_assoc()){
			$result[$i]=$rows;
			$i++;
		}			
	}
	if(isset($result)){
		$json = json_encode($result);
	}
	return $json;
}

function po_011($po_id){
	$conn = connect();
	if($conn->connect_error){
    	die("Connection failed" . mysqli_connect_error);
	}
	http_response_code(200);
	$stmt = "SELECT LineNo_011 AS 'Line Number', partNo_011 AS 'Part Number', qty_011 AS 'Quantity', linePrice_011 AS 'Subtotal' FROM PO_Lines_011 WHERE poNo_011 = ?;";
	$handle=$conn->prepare($stmt);
	$handle -> bind_param('i',$po_id);
	$handle -> execute();
	$payload=$handle->get_result();
	if ($payload->num_rows > 0){
		$i=0;
		while($rows = $payload->fetch_assoc()){
			$result[$i]=$rows;
			$i++;
		}			
	}
	if(isset($result)){
		$json = json_encode($result);
	}
	return $json;
}

function newOrder_011($payload){
	$conn = connect();
	if($conn->connect_error){
    	die("Connection failed" . mysqli_connect_error);
	}
	http_response_code(200);
	$client_id = $payload['id'];
	$po_id = -1;
	$ins_rows = 0;
	$po_stmt = "INSERT INTO PO_011(clientCompId_011,status_011) VALUES(?,'processing')";
	$handle = $conn->prepare($po_stmt);
	$handle -> bind_param('i',$payload['id']);
	$handle -> execute();
	#insert here
	if($conn->affected_rows != 0){
		$recent_po_stmt = "SELECT MAX(poNo_011) AS p FROM PO_011 WHERE clientCompId_011 = ?";
		$handle=$conn->prepare($recent_po_stmt);
		$handle -> bind_param('i',$client_id);
		$handle -> execute();
		$result = $handle -> get_result();
		if($result->num_rows > 0){
			$rows = $result->fetch_assoc();
			$po_id = $rows['p'];
		}

		$lines = $payload['lines'];
		$insert_stmt = "INSERT INTO PO_Lines_011(partNo_011, poNo_011,qty_011) VALUES";
		$parameter = array();
    	$param_stmt = array();
    	$param_type = '';
		foreach ($lines as $k => $v) {
			$param_type .= 'iii';
			array_push($parameter,$v['part'],$po_id,$v['qty']);
			array_push($param_stmt,"(?,?,?)");
			
		}

		$insert_stmt = $insert_stmt . $param_stmt[0];
    	for($i = 1; $i < count($param_stmt); $i++){
        	$insert_stmt = $insert_stmt . ", " . $param_stmt[$i];
    	}

    	$handle=$conn->prepare($insert_stmt);
    	$handle -> bind_param($param_type, ...$parameter);
    	$handle -> execute();
    	$result = "{\"Rows\": " . $conn->affected_rows . "}";
    	$json = json_encode($result);
    	return $json;
	}
	$result = "{Rows: 0}";
    $json = json_encode($result);
    return $json;

}

function cancelOrder_011($payload){
	$conn = connect();
	if($conn->connect_error){
    	die("Connection failed" . mysqli_connect_error);
	}
	http_response_code(200);
	$client_id = $payload['cus_id'];
	$po_id = $payload['id'];
	$stmt = "UPDATE PO_011 SET status_011 = 'canceled' WHERE poNo_011 = ? and clientCompId_011 = ?";
	$handle=$conn->prepare($stmt);
	$handle -> bind_param('ii',$po_id,$client_id);
	$handle -> execute();
	$result = "{\"Rows\": " . $conn->affected_rows . "}";
    $json = json_encode($result);
    return $json;
}


//print_r(count($uri));
//if($uri[3] == "list")
//$json = parts_011();
//echo $json;