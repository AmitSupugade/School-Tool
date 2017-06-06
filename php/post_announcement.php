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

if ( ! isset($_POST['announcement-title']) ) {
	$errors['title'] = "Announcement Title is Required";
}

if ( ! isset($_POST['announcement-description']) ) {
	$errors['description'] = "Announcement Description is Required";
}

$has_file = $_FILES['file-announcement']['tmp_name'];

if ($has_file) {
	if (!isset($_FILES['file-announcement']['error']) || is_array($_FILES['file-announcement']['error'])) {
		$errors['fileError'] = "Invalid file parameters after uploading file. Please try again.";
	}

	if ($_FILES['file-announcement']['error'] !== 0) { 
		$errors['fileError'] = "File upload error. Please try again.";
	}

	$extensions = array("xls", "xlsx", "doc", "docx", "txt", "pdf", "jpeg", "png", "jpg");
	$file_ext = pathinfo($_FILES['file-announcement']['name'], PATHINFO_EXTENSION);
	if(in_array($file_ext, $extensions) === false) {
		$errors['wrongFormat'] = "Only xsl, xslx, doc, docx, txt, pdf, jpeg, jpg, png files are allowed. ";
	}
}

if ( empty($errors) ) {
	
	$title = data_check($_POST['announcement-title']);
	$description = data_check($_POST['announcement-description']);
	
	$teacher_id=$_SESSION['id'];
	$class_id = $_SESSION['class_id'];
	
	$conn = new Dbase();
	if ($has_file) {
		$result =  $conn->post_announcement_with_file($class_id, $teacher_id, $title, $description, $_FILES['file-announcement']);
	} else {
		$result =  $conn->post_announcement($class_id, $teacher_id, $title, $description);
	}
	

	if ( $result == 1 ) {
		$data['success'] = true;
		$data['message'] = 'Announcement posted Successfully!';
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

$email = email_announcement($class_id, $title, $description);
if ( $email['to'] != '' ) {
	send_email($email);
}
sms_announcement($class_id, $title);
	
?>