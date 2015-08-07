<?php
	/*
	* This page is used for adding course to cart
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
        $stmt = $conn->prepare("select count(*) as counts from enrolled_courses where netid = ? and term_id = ? and course_no = ?");
		$stmt->execute(array($netid,$term_id,$course_no));
		while ($row = $stmt->fetch()) {
		    $counts = $row['counts'];
        }
        if($counts == 0){
        	$query = "INSERT INTO desired_courses (netid, course_no, term_id) VALUES (:netid,:course_no,:term_id)";
        	$stmt = $conn->prepare($query);
        	$result_course = $stmt->execute(array(':netid'=>$netid,':course_no'=>$course_no,':term_id'=>$term_id));
        
        	if ($result_course == TRUE) {
            	// successfully inserted into database
            	echo "success";
        	} else {
            	echo "failed";
        	}
        }
        else{
        	echo "failed";
        }
    
    }
        

?>
