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

function validateDate($date, $format = 'Y-m-d') {
	$d = DateTime::createFromFormat($format, $date);
	return $d && $d->format($format) == $date;
}

function validateTime($time) {
	return preg_match('/^(1[0-2]|0?[1-9]):[0-5][0-9] (AM|PM)$/i', $time);
}

$errors = array();
$data = array();

$subject_id = '';
$due_date = '';
$due_time = '';
$topic = '';
$description = '';

if( isset($_POST['homework-due-date']) ) {
	if ( ! validateDate($_POST['homework-due-date']) ) {
		$errors['dueDate'] = "Incorrect Date format.";
	}
}
else {
	$errors['dueDate'] = "Homework Due Date is Required.";
}

if( isset($_POST['homework-due-time']) ) {
	if ( ! validateTime($_POST['homework-due-time']) ) {
		$errors['dueTime'] = "Incorrect Start Time format.";
	}
} else {
	$errors['dueTime'] = "Homework Due Time is Required.";
}

if ( ! isset($_POST['homework-subject']) ) {
	$errors['subject'] = "Subject is Required";
}

if ( ! isset($_POST['homework-topic']) ) {
	$errors['topic'] = "Topic is Required";
}

if ( ! isset($_POST['homework-description']) ) {
	$errors['description'] = "Homework Description is Required";
}

$has_file = $_FILES['file-homework']['tmp_name'];

if ($has_file) {
	if (!isset($_FILES['file-homework']['error']) || is_array($_FILES['file-homework']['error'])) {
		$errors['fileError'] = "Invalid file parameters after uploading file. Please try again.";
	}

	if ($_FILES['file-homework']['error'] !== 0) { 
		$errors['fileError'] = "File upload error. Please try again.";
	}

	$extensions = array("xls", "xlsx", "doc", "docx", "txt", "pdf", "jpeg", "png", "jpg");
	$file_ext = pathinfo($_FILES['file-homework']['name'], PATHINFO_EXTENSION);
	if(in_array($file_ext, $extensions) === false) {
		$errors['wrongFormat'] = "Only xsl, xslx, doc, docx, txt, pdf, jpeg, jpg, png files are allowed. ";
	}
}

if ( empty($errors) ) {

	$due_date = $_POST['homework-due-date'];
	$due_time = $_POST['homework-due-time'];
	
	$subject_id = data_check($_POST['homework-subject']);
	$topic = data_check($_POST['homework-topic']);
	$description = data_check($_POST['homework-description']);
	
	$teacher_id = $_SESSION['id'];
	$class_id = $_SESSION['class_id'];
	
	$conn = new Dbase();
	if ($has_file) {
		$result =  $conn->post_homework_with_file($class_id, $teacher_id, $subject_id, $due_date, $due_time, $topic, $description, $_FILES['file-homework']);
	} else {
		$result =  $conn->post_homework($class_id, $teacher_id, $subject_id, $due_date, $due_time, $topic, $description);
	}
	
	if ( $result == 1 ) {
		$data['success'] = true;
		$data['message'] = 'Homework posted Successfully!';
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

$email = email_homework($class_id, $teacher_id, $subject_id, $due_date, $due_time, $topic, $description);
if ( $email['to'] != '' ) {
	send_email($email);
}

?>