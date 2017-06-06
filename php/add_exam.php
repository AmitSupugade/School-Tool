<?php
require_once 'db_handle.php';

session_start();

$errors = array();
$data = array();

if ( ! isset($_POST['exm-class']) || empty($_POST['exm-class']) ) {
	$errors['cls'] = "Please select class.";
}

if ( ! isset($_POST['exam']) || empty($_POST['exam']) ) {
	$errors['exam'] = "Select at least one exam from the list.";
}

if ( empty($errors) ) {

	$class_id = $_POST['exm-class'];
	$exams = $_POST['exam'];
	
	$conn = new Dbase();
	$result =  $conn->add_exams($class_id, $exams);
	
	if ( $result == 1 ) {
		$data['success'] = true;
		$data['message'] = 'Exams added successfully!';
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