<?php
require_once 'db_handle.php';

session_start();

if( isset($_POST['grade-student']) && !empty($_POST['grade-student']) ) {
	$student_id = $_POST['grade-student'];
	$class_id = $_SESSION['class_id'];
	
	$conn = new Dbase();
	$result =  $conn->get_student_grade($student_id, $class_id);
	echo $result;
	
} else {
	echo "Failed";
}

?>