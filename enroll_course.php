<?php
	/*
	* This page is used for enrolling to a course
*/
	require "config.php";
	//meeeting details are provided by the client
    if (isset($_POST['netid']) && isset($_POST['unique_code']) && isset($_POST['term'])) {
        $netid = $_POST['netid'];
        $course_no = $_POST['unique_code'];
        $term = $_POST['term'];
        $stmt = $conn->prepare("select term_id from terms where name = ?");
		$stmt->execute(array($term));
		while ($row = $stmt->fetch()) {
		    $term_id = $row['term_id'];
        }
        $stmt = $conn->prepare("select count(*) as counts from enrolled_courses where netid = ? and term_id =?");
		$stmt->execute(array($netid,$term_id));
		while ($row = $stmt->fetch()) {
		    $count = $row['counts'];
        }
        if($counts < 5){
        	$query = "INSERT INTO enrolled_courses (netid, course_no, term_id) VALUES (:netid,:course_no,:term_id)";
        	$stmt = $conn->prepare($query);
        	$result_course = $stmt->execute(array(':netid'=>$netid,':course_no'=>$course_no,':term_id'=>$term_id));
        
        	if ($result_course == TRUE) {
           		// successfully inserted into database
           		if($counts > 3 and $counts < 6){
           			$query_hold = "INSERT INTO holds (netid, name, description,term_id) VALUES (:netid,:name,:description,:due_date,:term_id)";
        			$stmt_hold = $conn->prepare($query_hold);
        			$result_course_hold = $stmt_hold->execute(array(':netid'=>$netid,':name'=>'Enrollment to ' . $counts . 'courses',':description'=>'An academic hold has been created. Meet your graduate advisor for removal of hold.',':term_id'=>$term_id));
        			if($result_course_hold == TRUE){
        				$response = array();
        				$response['success'] = 1;
        				$response['message'] = "Success, but a hold has been created";
            			echo json_encode($response);
        			}
           		}
           		else{
           			$response = array();
        			$response['success'] = 1;
        			$response['message'] = "Success. You have been enrolled to course.";
            		echo json_encode($response);
           		}
            	
        	} else {
        		$response = array();
        		$response['success'] = 0;
        		$response['message'] = "Failed. You have not been enrolled to the course.";
            	echo json_encode($response);
       		}
       	}
       	else{
       		$response = array();
        	$response['success'] = 0;
        	$response['message'] = "Failed. You have exceeded the course credit for your term.";
            echo json_encode($response);
       	}
       	 
    
    }
        

?>
