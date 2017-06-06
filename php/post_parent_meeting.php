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

$meeting_date = '';
$start_time = '';
$end_time = '';
$subject = '';
$description = '';

if( isset($_POST['meeting-date']) ) {
	if ( ! validateDate($_POST['meeting-date']) ) {
		$errors['date'] = "Incorrect Date format.";
	}
}
else {
	$errors['date'] = "Date is Required.";
}

if(isset($_POST['meeting-start-time']) ) {
	if ( ! validateTime($_POST['meeting-start-time']) ) {
		$errors['startTime'] = "Incorrect Start Time format.";
	}
} else {
	$errors['startTime'] = "Start Time is Required.";
}

if( isset($_POST['meeting-end-time']) ) {
	if ( ! validateTime($_POST['meeting-end-time']) ) {
		$errors['endTime'] = "Incorrect End Time format.";
	}
} else {
	$errors['endTime'] = "End Time is Required.";
}

if ( ! isset($_POST['meeting-subject']) ) {
	$errors['subject'] = "Subject is Required";
}

if ( ! isset($_POST['meeting-details']) ) {
	$errors['details'] = "Meeting Details is Required";
}

if ( empty($errors) ) {
	
	$location = "Classroom";
	$meeting_date = $_POST['meeting-date'];
	$start_time = $_POST['meeting-start-time'];
	$end_time = $_POST['meeting-end-time'];
	
	$subject = data_check($_POST['meeting-subject']);
	$description = data_check($_POST['meeting-details']);
	
	$user_id = $_SESSION['user_id'];
	$class_id=$_SESSION['class_id'];
	$student_id=$_SESSION['student_id'];
	$parent_id = $_SESSION['id'];
	
	$conn = new Dbase();
	$result =  $conn->post_parent_meeting($user_id, $class_id, $student_id, $parent_id, $meeting_date, $start_time, $end_time, $subject, $description, $location);
	
	if ( $result == 1 ) {
		$data['success'] = true;
		$data['message'] = 'Meeting posted Successfully!';
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

$email = email_parent_meeting($class_id, $student_id, $parent_id, $meeting_date, $start_time, $end_time, $subject, $description, $location);
if ( $email['to'] != '' ) {
	send_email($email);
}

?>