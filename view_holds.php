<?php
/*
	* This page is used to view holds
*/
require "config.php";
	//netid is retrieved from client
    if (isset($_POST['netid'])){
        $response_array = array();
        //$netid = $_POST['netid'];
        $netid = 'sxa6933';
        //dept_no is retrieved
		$stmt = $conn->prepare("select name,description from holds where netid = ?");
		$stmt->execute(array($netid));
		while ($row = $stmt->fetch()) {
			$response = array();
		   	$response['name'] = $row['name'];
			$response['description'] = $row['description'];
			array_push($response_array,$response);
        }
     	 if(isset($response_array)){
            echo json_encode($response_array);
        }
        else{
            echo "failed";
        }
    }
?>
