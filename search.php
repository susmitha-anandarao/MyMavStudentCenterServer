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
        //echo "Department name = " . $dept_name . " Course num = " . $course_num . " Restriction = " . $restriction . " Term = " . $term;
        //dept_no is retrieved
		$stmt = $conn->prepare("select dept_no from departments where dept_name = ?");
		$stmt->execute(array($dept_name));
		while ($row = $stmt->fetch()) {
		    $dept_no = $row['dept_no'];
        }
        //echo "Department number = " . $dept_no;
        $stmt = $conn->prepare("select term_id from terms where name = ?");
		$stmt->execute(array($term));
		while ($row = $stmt->fetch()) {
		    $term_id = $row['term_id'];
        }
        //echo " Term id = " . $term_id;
        /*if ($restriction == "equal"){
        	$stmt = $conn->prepare("select name,course_strength,instructor_id,course_time,room_no,start_date,end_date,course_code from courses where dept_no = ? and course_code = ? and term_id = ?");
			//$stmt->execute(array($dept_no,$course_num,$term_id));
		}elseif($restriction == "greater"){
			$stmt = $conn->prepare("select name,course_strength,instructor_id,course_time,room_no,start_date,end_date,course_code from courses where dept_no = ? and course_code > ? and term_id = ?");
			//$stmt->execute(array($dept_no,$course_num,$term_id));
		}
		else{
			$stmt = $conn->prepare("select name,course_strength,instructor_id,course_time,room_no,start_date,end_date,course_code from courses where dept_no = ? and course_code < ? and term_id = ?");
			//$stmt->execute(array($dept_no,$course_num,$term_id));
		}*/
		$stmt = $conn->prepare("select name,course_strength,instructor_id,course_time,room_no,start_date,end_date,course_code from courses where dept_no = ? and course_code " . $restriction . " ? and term_id = ?");
		echo "select name,course_strength,instructor_id,course_time,room_no,start_date,end_date,course_code from courses where dept_no =" . $dept_no . " and course_code " . $restriction . " " . $course_num . " and term_id = " . $term_id;
		$stmt->execute(array($dept_no,$course_num,$term_id));
		while ($row = $stmt->fetch()) {
		    $response = array();
		    $response['couse_name'] = $row['name'];
		    $response['course_strength'] = $row['course_strength'];
		    $response['course_time'] = $row['course_time'];
		    $response['room_no'] = $row['room_no'];
		    $response['start_date'] = $row['start_date'];
		    $response['end_date'] = $row['end_date'];
		    $response['course_num'] = $dept_name . " " . $row['course_code'];
		    $stmt_instructor = $conn->prepare("select name from instructors where instructor_id = ?");
			$stmt_instructor->execute(array($row['instructor_id']));
			while ($row_instructor = $stmt_instructor->fetch()) {
		    	$instructor_name = $row_instructor['name'];
		    }
		    $response['instructor_name'] = $instructor_name;
		    array_push($response_array,$response);
        }
        if(isset($response_array)){
            echo json_encode($response_array);
        }
        else{
            $response = array();
            $response['success'] = 0;
            echo json_encode($response);
        }
    }
?>
