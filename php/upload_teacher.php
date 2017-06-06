<?php
require_once 'db_handle.php';
require_once 'npmail.php';

session_start();

$errors = array();
$data = array();

if (! isset($_FILES['file-teacher'])) {
	$errors['noUpload'] = "File not uploaded properly. Please Select a file and try again.";
}

if (!isset($_FILES['file-teacher']['error']) || is_array($_FILES['file-teacher']['error'])) {
	$errors['fileError'] = "Invalid file parameters after uploading file. Please try again.";
}


if ($_FILES['file-teacher']['error'] !== 0) { 
	$errors['fileError'] = "File upload error. Please try again.";
}

if(empty($_FILES['file-teacher']['name'])) {
	$errors['empty'] = "No file selected. Please select the file and try again.";
}

$correct_ext = "csv";
$extension = pathinfo($_FILES['file-teacher']['name'], PATHINFO_EXTENSION);
if($extension != $correct_ext) {
	$errors['wrongFormat'] = "Uploaded file is not a .csv file. Please use .csv file format provided.";
}

if ( empty($errors) ) {
	$file_data = fopen($_FILES['file-teacher']['tmp_name'], 'r');
	$school_id = $_SESSION['school_id'];

	$conn = new Dbase();
	$result =  $conn->upload_teacher($school_id, $file_data);

	if ( $result == 1 ) {
		$data['success'] = true;
		$data['message'] = 'Teachers information uploaded Successfully and accounts created.';
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