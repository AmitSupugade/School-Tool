<?php
require_once 'db_handle.php';

session_start();

if( isset($_POST['exam-class-record']) && !empty($_POST['exam-class-record']) ) {
	$exam_id = $_POST['exam-class-record'];
	$class_id = $_SESSION['class_id'];
	
	$conn = new Dbase();
	$result =  $conn->get_class_report($exam_id, $class_id);
	echo $result;
} else {
	echo "Failed";
}

?>