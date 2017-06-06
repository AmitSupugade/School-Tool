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

$title = '';
$description = '';

if ( ! isset($_POST['notice-title']) ) {
	$errors['title'] = "Notice Title is Required";
}

if ( ! isset($_POST['notice-description']) ) {
	$errors['description'] = "Notice Description is Required";
}

if ( ! isset($_POST['notice-student']) || empty($_POST['notice-student']) ) {
	$errors['student'] = "Select at least one student from the list.";
}

$has_file = $_FILES['file-notice']['tmp_name'];

if ($has_file) {
	if (!isset($_FILES['file-notice']['error']) || is_array($_FILES['file-notice']['error'])) {
		$errors['fileError'] = "Invalid file parameters after uploading file. Please try again.";
	}

	if ($_FILES['file-notice']['error'] !== 0) { 
		$errors['fileError'] = "File upload error. Please try again.";
	}

	$extensions = array("xls", "xlsx", "doc", "docx", "txt", "pdf", "jpeg", "png", "jpg");
	$file_ext = pathinfo($_FILES['file-notice']['name'], PATHINFO_EXTENSION);
	if(in_array($file_ext, $extensions) === false) {
		$errors['wrongFormat'] = "Only xsl, xslx, doc, docx, txt, pdf, jpeg, jpg, png files are allowed. ";
	}
}

if ( empty($errors) ) {
	
	$students = $_POST['notice-student'];
	$title = data_check($_POST['notice-title']);
	$description = data_check($_POST['notice-description']);
	
	$teacher_id = $_SESSION['id'];

	$conn = new Dbase();
	if ($has_file) {
		$result =  $conn->post_notice_with_file($students, $teacher_id, $title, $description, $_FILES['file-notice']);
	} else {
		$result =  $conn->post_notice($students, $teacher_id, $title, $description);
	}
	
	if ( $result == 1 ) {
		$data['success'] = true;
		$data['message'] = 'Notice posted Successfully!';
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

$email = email_notice($students, $teacher_id, $title, $description);
if ( $email['to'] != '' ) {
	send_email($email);
}

sms_notice($students, $title);

?>