<?php
/*
	* This page is used search for a particular course
*/
require "config.php";
	//dept_name,course_num,restriction,term is retrieved from client
    if (isset($_POST['netid'])) {
        $response_array = array();
        $netid = $_POST['netid'];
        $stmt = $conn->prepare("select email, mailing_address, home_address, contact_no from students where netid = ?");
		$stmt->execute(array($netid));
		$response_array = array();
		while ($row = $stmt->fetch()) {
			$response = array(
		    	'email' => $row['email'],
		    	'mailing_address' => $row['mailing_address'],
		    	'home_address' => $row['home_address'],
		    	'conatct_no' => $row['contact_no'],
		    
		    );
		    $response_array = $response;
		    
        }
        
        if(empty($response_array)){
        	echo "failed";
        	
        }
        else{
        	echo json_encode($response_array);
        }
        
    }
?>
