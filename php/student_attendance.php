<?php
require_once 'db_handle.php';

session_start();
$result = array();

if( isset($_POST['attendance-report-student']) && !empty($_POST['attendance-report-student']) ) {
	$student_id = $_POST['attendance-report-student'];
	
	$conn = new Dbase();
	$result =  $conn->get_student_attendance($student_id);
	//$result['attendance'] = $attendance;
	//$result['Id'] = $student_id;
	echo $result;
	
} else {
	echo "Failed";
}

?>