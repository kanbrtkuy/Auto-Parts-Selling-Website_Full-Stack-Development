<?php

header("Content-Type: application/json");
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

function partsAll_011(){
    $conn = connect();
    if($conn->connect_error){
        die("Connection failed" . mysqli_connect_error);
    }
    http_response_code(200);
    $stmt = "SELECT partNo_011 AS PartNumber, partName_011 AS Name, partDescription_011 AS Description, partCurrentPrice_011 AS CurrentPrice, partQty_011 AS Qty FROM Parts_011;";
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
    $conn -> close();
    return $json;
}

function parts_011($partNo){
    $conn = connect();
    if($conn->connect_error){
        die("Connection failed" . mysqli_connect_error);
    }
    http_response_code(200);
    $stmt = "SELECT partNo_011 AS PartNumber, partName_011 AS Name, partDescription_011 AS Description, partCurrentPrice_011 AS CurrentPrice, partQty_011 AS Qty FROM Parts_011 WHERE partNo_011 = ?;";
    $handle=$conn->prepare($stmt);
    $handle -> bind_param('i',$partNo);
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
    $conn -> close();
    return $json;
}

function clientsAll_011(){
    $conn = connect();
    if($conn->connect_error){
        die("Connection failed" . mysqli_connect_error);
    }
    http_response_code(200);
    $stmt = "SELECT clientCompId_011 AS clientCompId, clientCompName_011 AS clientCompName, clientCity_011 AS clientCity, clientCompPswd_011 AS clientCompPswd, clientBalance_011 AS clientBalance FROM Client_User_011;";
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
    $conn -> close();
    return $json;
}

function clients_011($client_id){
    $conn = connect();
    if($conn->connect_error){
        die("Connection failed" . mysqli_connect_error);
    }
    http_response_code(200);
    $stmt = "SELECT clientCompId_011 AS clientCompId, clientCompName_011 AS 'clientCompName', clientCity_011 AS 'clientCity', clientCompPswd_011 AS 'clientCompPswd', clientBalance_011 AS 'clientBalance' FROM Client_User_011 WHERE clientCompId_011 = ?;";
    $handle=$conn->prepare($stmt);
    $handle -> bind_param('i',$client_id);
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
    $conn -> close();
    return $json;
}

function poAll_011(){
    $conn = connect();
    if($conn->connect_error){
        die("Connection failed" . mysqli_connect_error);
    }
    http_response_code(200);
    $stmt = "SELECT poNo_011 AS 'PO Number', clientCompId_011 AS 'Client Company ID', datePO_011 AS 'Date', status_011 AS 'Status' FROM PO_011;";
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
    $conn -> close();
    return $json;
}

function pof_011($po_id){
    $conn = connect();
    if($conn->connect_error){
        die("Connection failed" . mysqli_connect_error);
    }
    http_response_code(200);
    $stmt = "SELECT poNo_011 AS 'PO Number', clientCompId_011 AS 'Client Company ID', datePO_011 AS 'Date', status_011 AS 'Status' FROM PO_011 WHERE poNo_011 = ?;";
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
    $conn -> close();
    return $json;
}

function po_011($po_id){
    $conn = connect();
    if($conn->connect_error){
        die("Connection failed" . mysqli_connect_error);
    }
    http_response_code(200);
    $stmt = "SELECT LineNO_011 AS 'Line Number', partNo_011 AS 'Part Number', poNo_011 AS 'Purchase Order', qty_011 AS 'Quantity', linePrice_011 AS 'Unit Price' , partQty_011 AS 'Stock' FROM PO_Lines_011 NATURAL JOIN Parts_011 WHERE poNo_011 = ?;";
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
    $conn -> close();
    return $json;
}

function poSt_011($payload){
    $conn = connect();
    if($conn->connect_error){
        die("Connection failed" . mysqli_connect_error);
    }
    http_response_code(200);
    $po_id = $payload['id'];
    $po_st = $payload['status'];
    $stmt = "UPDATE PO_011 SET status_011 = ? WHERE poNo_011 = ?";
    $handle = $conn->prepare($stmt);
    $handle -> bind_param('si',$po_st,$po_id);
    $handle -> execute();

    $updater1 = "UPDATE PO_Lines_011 SET status_011 = ? WHERE poNo_011 = ?";
    $handle1 = $conn->prepare($updater1);
    $handle1 -> bind_param('si',$po_st,$po_id);
    $handle1 -> execute();

    $result = "{\"Rows\": " . $conn->affected_rows . "}";
    /*
    if($po_st == "confirmed"){
        $helper1 = "SELECT LineNO_011 FROM PO_Lines_011 WHERE poNo_011 = ?";
        $handle1 = $conn->prepare($helper1);
        $handle1 -> bind_param('i',$po_id);
        $handle1 -> execute();
        $payload1 = $handle1->get_result();
        if ($payload1->num_rows > 0){
            while($rows1 = $payload1->fetch_assoc()){
                $result .= $rows1['LineNO_011'];
                $updater1 = "UPDATE PO_Lines_011 SET status_011 = ? WHERE LineNO_011 = ?";
                $handle2 -> $conn->prepare($updater1);
                $handle2 -> bind_param('si',$po_st,$rows1['LineNO_011']);
                $handle2 -> execute();
            }           
        }
    }
    */
    $json = json_encode($result);
    $conn -> close();
    return $json;
}

function updateParts_011($payload){
    $conn = connect();
    if($conn->connect_error){
        die("Connection failed" . mysqli_connect_error);
    }
    http_response_code(200);
    $pts_id = $payload['id'];
    $stmt = "UPDATE Parts_011 SET ";

    $parameter = array();
    $param_stmt = array();
    $param_type = '';
    if(isset($payload['name'])){
        $param_type .= 's';
        array_push($parameter, $payload['name']);
        array_push($param_stmt,"partName_011= ?");
    }
    if(isset($payload['desc'])){
        $param_type .= 's';
        array_push($parameter, $payload['desc']);
        array_push($param_stmt,"partDescription_011 = ?");
    }
    if(isset($payload['price'])){
        $param_type .= 's';
        array_push($parameter, $payload['price']);
        array_push($param_stmt,"partCurrentPrice_011 = ?");
    }
    if(isset($payload['qty'])){
        $param_type .= 'i';
        array_push($parameter, $payload['qty']);
        array_push($param_stmt,"partQty_011 = ?");
    }

    $stmt = $stmt . $param_stmt[0];
    for($i = 1; $i < count($param_stmt); $i++){
        $stmt = $stmt . ", " . $param_stmt[$i];
    }

    $stmt = $stmt . " WHERE partNo_011 = ?";
    $param_type .= 'i';
    array_push($parameter, $pts_id);

    $handle=$conn->prepare($stmt);
    $handle -> bind_param($param_type, ...$parameter);
    $handle -> execute();
    $result = "{\"Rows\": " . $conn->affected_rows . "}";
    $json = json_encode($result);
    $conn -> close();
    return $json;
}

function updateClient_011($payload){
    $conn = connect();
    if($conn->connect_error){
        die("Connection failed" . mysqli_connect_error);
    }
    http_response_code(200);
    $pts_id = $payload['id'];
    $stmt = "UPDATE Client_User_011 SET ";

    $parameter = array();
    $param_stmt = array();
    $param_type = '';
    if(isset($payload['name'])){
        $param_type .= 's';
        array_push($parameter, $payload['name']);
        array_push($param_stmt, "clientCompName_011 = ?");
    }
    if(isset($payload['city'])){
        $param_type .= 's';
        array_push($parameter, $payload['city']);
        array_push($param_stmt, "clientCity_011 = ?");
    }
    if(isset($payload['pswd'])){
        $param_type .= 's';
        array_push($parameter, $payload['pswd']);
        array_push($param_stmt, "clientCompPswd_011 = ?");
    }
    if(isset($payload['blnc'])){
        $param_type .= 's';
        array_push($parameter, $payload['blnc']);
        array_push($param_stmt, "clientBalance_011 = ?");
    }

    $stmt = $stmt . $param_stmt[0];
    for($i = 1; $i < count($param_stmt); $i++){
        $stmt = $stmt . ", " . $param_stmt[$i];
    }

    $stmt = $stmt . " WHERE clientCompId_011 = ?";
    $param_type .= 'i';
    array_push($parameter, $payload['id']);

    $handle=$conn->prepare($stmt);
    $handle -> bind_param($param_type, ...$parameter);
    $handle -> execute();
    $result = "{\"Rows\": " . $conn->affected_rows . "}";
    $json = json_encode($result);
    $conn -> close();
    return $json;
}
?>