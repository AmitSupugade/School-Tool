<?php
require_once 'db_handle.php';
require_once 'npmail.php';

session_start();

function data_check($data) {
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}

$errors = array();
$data = array();


if ( ! isset($_POST['class']) || empty($_POST['class']) ) {
	$errors['cls'] = "Select at least one class from the list.";
}

if ( empty($errors) ) {
	
	$classes = $_POST['class'];
	$school_id = $_SESSION['school_id'];
		
	$conn = new Dbase();
	$result =  $conn->add_class($classes, $school_id);
	
	if ( $result == 1 ) {
		$data['success'] = true;
		$data['message'] = 'Classes Added Successfully!';
	} else {
		$data['success'] = false;
		$errors['db'] = "Databse Error. ". $result;
		$data['errors']  = $errors;
	}
} else {
	$data['success'] = false;
	$data['errors']  = $errors;
}

echo json_encode($data);

?>