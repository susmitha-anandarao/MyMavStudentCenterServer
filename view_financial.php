<?php
/*
	* This page is used to view todo-list
*/
require "config.php";
	//netid is retrieved from client
    if (isset($_POST['netid'])){
        $response_array = array();
        $netid = $_POST['netid'];
        //dept_no is retrieved
		$stmt = $conn->prepare("select name,description,due_date,amount from financial_info where netid = ?");
		$stmt->execute(array($netid));
		while ($row = $stmt->fetch()) {
			$response = array();
		   	$response['name'] = $row['name'];
			$response['description'] = $row['description'];
			$response['due_date'] = $row['due_date'];
			$response['amount'] = $row['amount'];
			array_push($response_array,$response);
        }
     }
        
        if(isset($response_array)){
            echo json_encode($response_array);
        }
        else{
            echo "failed";
        }
    }
?>
