<?php
/*
	* This page is used search for a particular course
*/
require "config.php";
	//dept_name,course_num,restriction,term is retrieved from client
    if (isset($_POST['netid'])  && isset($_POST['term'])) {
        $response_array = array();
        $netid = $_POST['netid'];
        $term = $_POST['term'];
        $stmt = $conn->prepare("select term_id from terms where name = ?");
		$stmt->execute(array($term));
		while ($row = $stmt->fetch()) {
		    $term_id = $row['term_id'];
        }
        $stmt = $conn->prepare("select c.course_no,c.name,c.course_strength,c.instructor_id,c.course_time,c.room_no,c.start_date,c.end_date,c.course_code from courses c, desired_courses d where d.course_no = c.course_no and d.term_id = ? and d.netid = ?");
		$stmt->execute(array($term_id,$netid));
		while ($row = $stmt->fetch()) {
			$response = array(
		    'course_name' => $row['name'],
		    'course_strength' => $row['course_strength'],
		    'course_time' => $row['course_time'],
		    'room_no' => $row['room_no'],
		    'start_date' => $row['start_date'],
		    'end_date' => $row['end_date'],
		    'course_num' => $dept_name . " " . $row['course_code'],
		    'instructor_id' => $row['instructor_id'],
		    'unique_code' => $row['course_no'],
		    );
		    array_push($response_array,$response);
        }
        $response_array_new = array();
        if(isset($response_array)){
        	
        	foreach($response_array as $response_instance){
        		$stmt = $conn->prepare("select name from instructors where instructor_id = ?");
				$stmt->execute(array($response_instance['instructor_id']));
				while ($row = $stmt->fetch()) {
		    		$instructor_name = $row['name'];
		    	}
		    	$response_instance['instructor_name'] = $instructor_name;
		    	array_push($response_array_new,$response_instance);
        	}
        }
        if(empty($response_array_new)){
        	if(empty($row)){
            	/*$response = array();
            	$response['success'] = 0;
            	$response['message'] = "No corses found";
            	echo json_encode($response);*/
            	echo "failed";
        	}	
        }
        else{
        	echo json_encode($response_array_new);
        }
        /*if(isset($response_array_new)){
            echo json_encode($response_array_new);
        }
        if(empty($row)){
            $response = array();
            $response['success'] = 0;
            $response['message'] = "No corses found";
            echo json_encode($response);
        }*/
    }
?>
