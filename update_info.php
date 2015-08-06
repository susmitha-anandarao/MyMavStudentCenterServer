<?php
/*
	* This page is used to remove a particular course from the cart
*/
require "config.php";
	//dept_name,course_num,restriction,term is retrieved from client
    if ((isset($_POST['netid'])  && (isset($_POST['contact_no']) || isset($_POST['mailing_address']) || isset($_POST['home_address']) || isset($_POST['email']))) {
        $response_array = array();
        $netid = $_POST['netid'];
        $contact_no = $_POST['contact_no'];
        $mailing_address = $_POST['mailing_address'];
        $home_address = $_POST['home_address'];
        $email = $_POST['email'];
        $query = "update students set mailing_address = :mailing_address and home_address = :home_address and contact_no = :contact_no and email = :email where netid = :netid";
        $stmt = $conn->prepare($query);
		$stmt->bindParam(':netid', $netid, PDO::PARAM_STR); 
		$stmt->bindParam(':mailing_address', $mailing_address, PDO::PARAM_STR); 
        $stmt->bindParam(':home_address', $home_address, PDO::PARAM_STR);
        $stmt->bindParam(':contact_no', $contact_no, PDO::PARAM_STR); 
        $stmt->bindParam(':email', $email, PDO::PARAM_STR); 
        $result = $stmt->execute();
        if(isset($result)){
        	$response = array();
        	$response['success'] = 1;
            $response['message'] = "Personal information updated";
            echo json_encode($response);
        	
        }
        else{
        	echo "failed";
        }
        
    }
?>
