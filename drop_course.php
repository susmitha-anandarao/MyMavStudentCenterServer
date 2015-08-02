<?php
/*
	* This page is used to drop a particular course
*/
require "config.php";
	//dept_name,course_num,restriction,term is retrieved from client
    if (isset($_POST['netid'])  && isset($_POST['term']) && isset($_POST['unique_code'])) {
        $response_array = array();
        $netid = $_POST['netid'];
        $term = $_POST['term'];
        $course_no = $_POST['unique_code'];
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
       if($counts > 3){
       		$stmt = $conn->prepare("select description from holds where netid = ? and term_id =?");
			$stmt->execute(array($netid,$term_id));
			while ($row = $stmt->fetch()) {
				if(strpos($row['description'],$unique_code) != FALSE){
		    		$description = $row['description'];
		    	}
        	}
        	$query = "delete from holds where netid = :netid and term_id = :term_id and description = :description";
        	$stmt = $conn->prepare($query);
			$stmt->bindParam(':netid', $netid, PDO::PARAM_STR); 
			$stmt->bindParam(':term_id', $term_id, PDO::PARAM_INT); 
        	$stmt->bindParam(':description', $description, PDO::PARAM_STR); 
        	$result = $stmt->execute();
       }
       $query = "delete from enrolled_courses where netid = :netid and term_id = :term_id and course_no = :course_no";
       $stmt = $conn->prepare($query);
		$stmt->bindParam(':netid', $netid, PDO::PARAM_STR); 
		$stmt->bindParam(':term_id', $term_id, PDO::PARAM_INT); 
        $stmt->bindParam(':course_no', $course_no, PDO::PARAM_STR); 
        $result = $stmt->execute();
        if(isset($result)){
        	$response['success'] = 1;
           	$response['message'] = "Course deleted.";
           	echo json_encode($response)
        
        }
        
        else{
        	echo "failed";
        }
        
    }
?>
