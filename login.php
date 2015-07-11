<?php
	/*
	* This page is used for the user login
	*/
	require 'config.php';
	//get password and netid from the client
    if (isset($_POST['password']) && isset($_POST['netid'])){
        $password = md5($_POST['password']);
        $netid = $_POST['netid'];
        $conn->beginTransaction();
        //check if the netid and password combination is registered in the database
		$stmt = $conn->prepare("select name,type from students where netid = ? and password = ?");
		$stmt->execute(array($netid,$password));
		while ($row = $stmt->fetch()) {
            $name = $row['name'];
            $type = $row['type'];
        }
        //if not registered, send an error message
		if(null == $name){
			$response["success"] = 0;
            $response["message"] = "Invalid netid or password";
 
            // echoing JSON response
            echo json_encode($response);
  		}
  		//if registered send the students type and name to the client
		else{
		    $row = $stmt->fetch();
			$response["success"] = 1;
            $response["message"] = "User can login.";
            $response['name'] = $name;
            $response['type'] = $type;
            $_SESSION['username'] = $username;
            // echoing JSON response
            echo json_encode($response);
		}
	}
?>
