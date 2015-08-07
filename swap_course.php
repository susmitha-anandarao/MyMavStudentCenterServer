<?php
/*
	* This page is used to drop a particular course
*/
require "config.php";

	//dept_name,course_num,restriction,term is retrieved from client
    if (isset($_POST['netid'])  && isset($_POST['term']) && isset($_POST['unique_code_drop']) && isset($_POST['unique_code_add'])) {
        $netid = $_POST['netid'];
        $term = $_POST['term'];
        $course_no_drop = $_POST['unique_code_drop'];
        $course_no_add = $_POST['unique_code_add'];
        $stmt = $conn->prepare("select term_id from terms where name = ?");
		$stmt->execute(array($term));
		while ($row = $stmt->fetch()) {
		    $term_id = $row['term_id'];
        }
        $stmt = $conn->prepare("select course_strength from courses where course_no = ?");
		$stmt->execute(array($course_no_add));
		while ($row = $stmt->fetch()) {
		    $course_strength = $row['course_strength'];
        }
        $stmt = $conn->prepare("select count(*) as counts from enrolled_courses where course_no = ?");
		$stmt->execute(array($course_no_add));
		while ($row = $stmt->fetch()) {
		   	$counts = $row['counts'];
        }
        echo "Counts = " . $counts;
        if($counts < $course_strength){
       		$query = "delete from enrolled_courses where netid = :netid and term_id = :term_id and course_no = :course_no";
       		$stmt = $conn->prepare($query);
			$stmt->bindParam(':netid', $netid, PDO::PARAM_STR); 
			$stmt->bindParam(':term_id', $term_id, PDO::PARAM_INT); 
        	$stmt->bindParam(':course_no', $course_no_drop, PDO::PARAM_STR); 
        	$result = $stmt->execute();
        	if(isset($result)){
        		$query = "INSERT INTO enrolled_courses (netid, course_no, term_id) VALUES (:netid,:course_no,:term_id)";
        		$stmt = $conn->prepare($query);
        		$result_course = $stmt->execute(array(':netid'=>$netid,':course_no'=>$course_no_add,':term_id'=>$term_id));
        		if ($result_course == TRUE) {
        			$query_del = "delete from desired_courses where netid = :netid and term_id = :term_id and course_no = :course_no";
        			$stmt_del = $conn->prepare($query_del);
					$stmt_del->bindParam(':netid', $netid, PDO::PARAM_STR); 
					$stmt_del->bindParam(':term_id', $term_id, PDO::PARAM_INT); 
        			$stmt_del->bindParam(':course_no', $course_no_add, PDO::PARAM_STR); 
        			$result_del = $stmt_del->execute();
        			$response = array();
        			$response['success'] = 1;
           			$response['message'] = "Swap successful";
           			echo json_encode($response);
           		}
           		else{
        			echo "failed";
        		}
        
        	}
        }
        
        else{
        	echo "failed";
        }
        
    }
?>
