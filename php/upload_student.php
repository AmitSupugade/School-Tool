<?php
require_once 'db_handle.php';
require_once 'npmail.php';

session_start();

$errors = array();
$data = array();

if (! isset($_FILES['file-student'])) {
	$errors['noUpload'] = "File not uploaded properly. Please Select a file and try again.";
}

if (!isset($_FILES['file-student']['error']) || is_array($_FILES['file-student']['error'])) {
	$errors['fileError'] = "Invalid file parameters after uploading file. Please try again.";
}


if ($_FILES['file-student']['error'] !== 0) { 
	$errors['fileError'] = "File upload error. Please try again.";
}

$correct_ext = "csv";
$extension = pathinfo($_FILES['file-student']['name'], PATHINFO_EXTENSION);
if($extension != $correct_ext) {
	$errors['wrongFormat'] = "Uploaded file is not a .csv file. Please use .csv file format provided.";
}

if(empty($_FILES['file-student']['name'])) {
	$errors['empty'] = "No file selected. Please select the file and try again.";
}

if (! isset($_POST['student-class'])) {
	$data['selectCls'] = "Please select class.";
}

if ( empty($errors) ) {
	$file_data = fopen($_FILES['file-student']['tmp_name'], 'r');
	$class_id = $_POST['student-class'];

	$conn = new Dbase();
	$result =  $conn->upload_student($class_id, $file_data);

	if ( $result == 1 ) {
		$data['success'] = true;
		$data['message'] = 'Students information uploaded Successfully and parent accounts created.';
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