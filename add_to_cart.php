<?php
	/*
	* This page is used for adding course to cart
*/
	require "config.php";
	//meeeting details are provided by the client
    if (isset($_POST['netid']) && isset($_POST['course_name']) && isset($_POST['term'])) {
        $netid = $_POST['netid'];
        $course_name = $_POST['course_name'];
        $term = $_POST['term'];
        $stmt = $conn->prepare("select term_id from terms where name = ?");
		$stmt->execute(array($term));
		while ($row = $stmt->fetch()) {
		    $term_id = $row['term_id'];
        }
        $stmt = $conn->prepare("select course_no from courses where name = ? and term_id = ?");
		$stmt->execute(array($course_name,$term_id));
		while ($row = $stmt->fetch()) {
		    $course_no = $row['course_no'];
        }
        //the meeting details are insterted into the database
        $query = "INSERT INTO desired_courses (net_id, course_no, term_id) VALUES (:netid,:course_no,:term_id)";
        $stmt = $conn->prepare($query);
        
        $result_course = $stmt->execute(array(':netid'=>$netid,':max_ppl'=>$course_no,':term_id'=>$term_id));
        
        if (isset($result_course)) {
            // successfully inserted into database
        
            $response["success"] = 1;
            $response["message"] = "Course has been added to your cart";
 
            echo json_encode($response);
        } else {
            echo "failed";
        }
    
    }
        

?>
