<?php
require_once 'db_handle.php';

session_start();

$errors = array();
$data = array();

if ( ! isset($_POST['sub-class']) || empty($_POST['sub-class']) ) {
	$errors['cls'] = "Please select class.";
}

if ( ! isset($_POST['subject']) || empty($_POST['subject']) ) {
	$errors['subject'] = "Select at least one subject from the list.";
}

if ( empty($errors) ) {

	$class_id = $_POST['sub-class'];
	$subjects = $_POST['subject'];
	
	$conn = new Dbase();
	$result =  $conn->add_subjects($class_id, $subjects);
	
	if ( $result == 1 ) {
		$data['success'] = true;
		$data['message'] = 'Subjects added successfully!';
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