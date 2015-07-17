<?php
/*
	* This page is used search for a particular course
*/
require "config.php";
	//dept_name,course_num,restriction,term is retrieved from client
    if (isset($_POST['dept_name']) && isset($_POST['course_num']) && isset($_POST['restriction']) && isset($_POST['term'])){
        $response_array = array();
        $dept_name = $_POST['dept_name'];
        $course_num = $_POST['course_num'];
        $restriction = $_POST['restriction'];
        $term = $_POST['term'];
        //dept_no is retrieved
		$stmt = $conn->prepare("select dept_no from departments where dept_name = ?");
		$stmt->execute(array($dept_name));
		while ($row = $stmt->fetch()) {
		    $dept_no = $row['dept_no'];
        }
        $stmt = $conn->prepare("select term_id from terms where name = ?");
		$stmt->execute(array($term));
		while ($row = $stmt->fetch()) {
		    $term_id = $row['term_id'];
        }
        $stmt = $conn->prepare("select name,course_strength,instructor_id,course_time,room_no,start_date,end_date,course_code from courses where dept_no = ? and course_code " . $restriction . " ? and term_id = ?");
		$stmt->execute(array($dept_no,$course_num,$term_id));
		while ($row = $stmt->fetch()) {
			$response = array(
		    'couse_name' => $row['name'],
		    'course_strength' => $row['course_strength'],
		    'course_time' => $row['course_time'],
		    'room_no' => $row['room_no'],
		    'start_date' => $row['start_date'],
		    'end_date' => $row['end_date'],
		    'course_num' => $dept_name . " " . $row['course_code'],
		    'instructor_id' => $row['instructor_id'],
		    );
		    array_push($response_array,$response);
        }
        $response_array_new = array();
        foreach($response_array as $response_instance){
        	$stmt = $conn->prepare("select name from instructors where instructor_id = ?");
			$stmt->execute(array($response_instance['instructor_id']));
			while ($row = $stmt->fetch()) {
		    	$instructor_name = $row['name'];
		    }
		    $response_instance['instructor_name'] = $instructor_name;
		    array_push($response_array_new,$response_instance);
        }
        if(isset($response_array_new)){
            echo json_encode($response_array_new);
        }
        else{
            $response = array();
            $response['success'] = 0;
            $response['message'] = "No corses found";
            echo json_encode($response);
        }
    }
?>
