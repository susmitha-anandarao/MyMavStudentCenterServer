<?php
/*
	* This page is used to check application status
*/
require "config.php";
	//netid is retrieved from client
    if (isset($_POST['netid'])){
        $response_array = array();
        $netid = $_POST['netid'];
        //dept_no is retrieved
		$stmt = $conn->prepare("select type from students where netid = ?");
		$stmt->execute(array($netid));
		while ($row = $stmt->fetch()) {
		    $type = $row['type'];
        }
        if($type == "P"){
        	$stmt = $conn->prepare("select application_number,application_name,application_status,application_date from application_status where netid = ?");
			$stmt->execute(array($netid));
			while ($row = $stmt->fetch()) {
				$response = array();
		    	$response['application_number'] = $row['application_number'];
				$response['application_name'] = $row['application_name'];
				$response['application_status'] = $row['application_status'];
				$response['application_date'] = $row['application_date'];
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
