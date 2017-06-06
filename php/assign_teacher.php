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

if ( ! isset($_POST['ct-class']) ) {
	$errors['cls'] = "Please select class.";
}

if ( ! isset($_POST['teacher']) ) {
	$errors['teacher'] = "Please select teacher";
}

if ( empty($errors) ) {
	
	$class_id = data_check($_POST['ct-class']);
	$teacher_id = data_check($_POST['teacher']);
	
	$conn = new Dbase();
	$result =  $conn->assign_teacher($class_id, $teacher_id);

	if ( $result == 1 ) {
		$data['success'] = true;
		$data['message'] = 'Teacher Assigned Successfully!';
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