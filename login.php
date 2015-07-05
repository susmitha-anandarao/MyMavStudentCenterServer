<?php
	/*
	* This page is used for the user login
	*/
	require 'config.php';
	//get password and username from the client
    if (isset($_POST['password']) && isset($_POST['netid'])){
        $password = md5($_POST['password']);
        $netid = $_POST['netid'];
        $conn->beginTransaction();
        //check if the username and password combination is registered in the database
		$stmt = $conn->prepare("select name from students where netid = ? and password = ?");
		$stmt->execute(array($netid,$password));
		while ($row = $stmt->fetch()) {
            $home_location = $row['name'];
        }
        //if not registered, send an error message
		if(null == $name){
			$response["success"] = 0;
            $response["message"] = "Invalid netid or password";
 
            // echoing JSON response
            echo json_encode($response);
  		}
  		//if registered send the users home location to the client
		else{
		    $row = $stmt->fetch();
			$response["success"] = 1;
            $response["message"] = "User can login.";
            $response['name'] = $name;
            $_SESSION['username'] = $username;
            // echoing JSON response
            echo json_encode($response);
		}
	}
?>
