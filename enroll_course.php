<?php
	/*
	* This page is used for enrolling to a course
*/
	require "config.php";
	//meeeting details are provided by the client
    if (isset($_POST['netid']) && isset($_POST['unique_code']) && isset($_POST['term'])) {
    	$course_strength = 0;
    	$count_course = 0;
        $netid = $_POST['netid'];
        $course_no = $_POST['unique_code'];
        $term = $_POST['term'];
        /*$netid = 'sxa6933';
        $course_no = '10';
        $term = 'Fall 2015';*/
        $stmt = $conn->prepare("select term_id from terms where name = ?");
		$stmt->execute(array($term));
		while ($row = $stmt->fetch()) {
		    $term_id = $row['term_id'];
        }
        $stmt = $conn->prepare("select course_strength from courses where course_no = ?");
		$stmt->execute(array($course_no));
		while ($row = $stmt->fetch()) {
		    $course_strength = $row['course_strength'];
        }
        $stmt = $conn->prepare("select count(*) as counts from enrolled_courses where netid = ? and term_id =?");
		$stmt->execute(array($netid,$term_id));
		while ($row = $stmt->fetch()) {
		    $count_course = $row['counts'];
        }
        if($count_course < 5){
        	$stmt = $conn->prepare("select count(*) as counts from enrolled_courses where course_no = ?");
			$stmt->execute(array($unique_code,$term_id));
			while ($row = $stmt->fetch()) {
		    	$counts = $row['counts'];
        	}
        	if($counts < $course_strength){
        		$query = "INSERT INTO enrolled_courses (netid, course_no, term_id) VALUES (:netid,:course_no,:term_id)";
        		$stmt = $conn->prepare($query);
        		$result_course = $stmt->execute(array(':netid'=>$netid,':course_no'=>$course_no,':term_id'=>$term_id));
        		$query_del = "delete from desired_courses where netid = :netid and term_id = :term_id and course_no = :course_no";
        		$stmt_del = $conn->prepare($query_del);
				$stmt_del->bindParam(':netid', $netid, PDO::PARAM_STR); 
				$stmt_del->bindParam(':term_id', $term_id, PDO::PARAM_INT); 
        		$stmt_del->bindParam(':course_no', $course_no, PDO::PARAM_STR); 
        		$result_del = $stmt_del->execute();
        		$stmt = $conn->prepare("select count(*) as counts from enrolled_courses where netid = ? and term_id =?");
				$stmt->execute(array($netid,$term_id));
				while ($row = $stmt->fetch()) {
		    		$count_course = $row['counts'];
        		}
        		if($count_course == 1){
        			$query = "INSERT INTO financial_info (netid, name, description, amount, due_date) VALUES (:netid,:name,:description,:amount,:due_date)";
        			$stmt = $conn->prepare($query);
        			$description = 'Tution fee for ' . $term;
        			$result_course = $stmt->execute(array(':netid'=>$netid,':name'=>'Tuition',':description'=>$description,':due_date'=>'2015-09-05',':amount'=>'2500'));
        		}
        		else{
        			$amount = $count_course * 2500 . "";
        			$name = 'Tuition';
        			$query_fin = "update financial_info set amount = :amount where netid = :netid and name = :name";
        			$stmt_fin = $conn->prepare($query_fin);
        			$stmt_fin->bindParam(':amount', $amount, PDO::PARAM_STR); 
					$stmt_fin->bindParam(':netid', $netid, PDO::PARAM_STR); 
					$stmt_fin->bindParam(':name', $name, PDO::PARAM_STR);
					$result_fin = $stmt_fin->execute();
        			
        		}
        		if ($result_course == TRUE && isset($result_del)) {
           		// successfully inserted into database
           			$stmt = $conn->prepare("select count(*) as counts from enrolled_courses where netid = ? and term_id =?");
					$stmt->execute(array($netid,$term_id));
					while ($row = $stmt->fetch()) {
		    			$count_course = $row['counts'];
        			}
           			if($count_course > 3 && $count_course < 6){
           				$query_hold = "INSERT INTO holds (netid, name, description,term_id) VALUES (:netid,:name,:description,:term_id)";
        				$stmt_hold = $conn->prepare($query_hold);
        				$result_course_hold = $stmt_hold->execute(array(':netid'=>$netid,':name'=>'Enrollment to ' . $count_course . 'courses',':description'=>'An academic hold has been created. Meet your graduate advisor for removal of hold. Course no ' . $course_no,':term_id'=>$term_id));
        				
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
        		$response['message'] = "Failed. Course is full.";
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
