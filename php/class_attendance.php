<?php
require_once 'db_handle.php';

session_start();

if( isset($_POST['attendance-month']) && !empty($_POST['attendance-month']) ) {
	$month = $_POST['attendance-month'];
	$class_id = $_SESSION['class_id'];
	
	$conn = new Dbase();
	$result =  $conn->get_class_attendance($class_id, $month);
	echo $result;
} else {
	echo "Failed";
}

?>