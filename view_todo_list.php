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
		$stmt = $conn->prepare("select type from students where netid = ?");
		$stmt->execute(array($netid));
		while ($row = $stmt->fetch()) {
		    $type = $row['type'];
        }
        if($type == "P"){
        	$stmt = $conn->prepare("select todo_no,description,due_date,name from todo_list where netid = ?");
			$stmt->execute(array($netid));
			while ($row = $stmt->fetch()) {
				$response = array();
		    	$response['todo_no'] = $row['todo_no'];
				$response['description'] = $row['description'];
				$response['due_date'] = $row['due_date'];
				$response['name'] = $row['name'];
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
