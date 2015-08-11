<?php
/*
	* This page is used to view financial
*/
require "config.php";
	//netid is retrieved from client
    if (isset($_POST['netid']) && isset($_POST['term'])){
        $response_array = array();
        $netid = $_POST['netid'];
        $term = $_POST['term'];
        //dept_no is retrieved
        $stmt = $conn->prepare("select term_id from terms where name = ?");
		$stmt->execute(array($term));
		while ($row = $stmt->fetch()) {
		    $term_id = $row['term_id'];
        }
		$stmt = $conn->prepare("select name,description,due_date,amount from financial_info where netid = ? and term_id = ?");
		$stmt->execute(array($netid,$term_id));
		while ($row = $stmt->fetch()) {
			$response = array();
		   	$response['name'] = $row['name'];
			$response['description'] = $row['description'];
			$response['due_date'] = $row['due_date'];
			$response['amount'] = $row['amount'];
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
