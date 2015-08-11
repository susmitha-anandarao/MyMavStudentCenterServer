<?php
/*
	* This page is used to view holds
*/
require "config.php";
	//netid is retrieved from client
    //if (isset($_POST['netid'])){
        $response_array = array();
        //$netid = $_POST['netid'];
        $netid = 'sxa6933';
        //dept_no is retrieved
		$stmt = $conn->prepare("select email,mailing_address,home_address,contact_no from students where netid = ?");
		$stmt->execute(array($netid));
		while ($row = $stmt->fetch()) {
			$response = array();
		   	$response['email'] = $row['email'];
			$response['mailing_address'] = $row['mailing_address'];
			$response['home_address'] = $row['home_address'];
			$response['contact_no'] = $row['contact_no'];
			array_push($response_array,$response);
        }
     	 if(isset($response_array)){
            echo json_encode($response_array);
        }
        else{
            echo "failed";
        }
    //}
?>
