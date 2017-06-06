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

$event_date = '';
$start_time = '';
$end_time = '';
$event_location = '';
$subject = '';
$description = '';
/*
if( !(isset($_POST['event-date']) && validateDate($_POST['event-date']))) {
	$errors['eventDate'] = "Incorrect Date format.";
}

if( !(isset($_POST['event-start-time']) && validateTime($_POST['event-start-time']))) {
	$errors['eventSTime'] = "Incorrect Start Time format.";
}

if( !(isset($_POST['event-end-time']) && validateTime($_POST['event-end-time']))) {
	$errors['eventETime'] = "Incorrect End Time format.";
}
*/

if( isset($_POST['event-date']) ) {
	if ( ! validateDate($_POST['event-date']) ) {
		$errors['date'] = "Incorrect Date format.";
	}
}
else {
	$errors['date'] = "Event Date is Required.";
}

if(isset($_POST['event-start-time']) ) {
	if ( ! validateTime($_POST['event-start-time']) ) {
		$errors['startTime'] = "Incorrect Start Time format.";
	}
} else {
	$errors['startTime'] = "Event Start Time is Required.";
}

if( isset($_POST['event-end-time']) ) {
	if ( ! validateTime($_POST['event-end-time']) ) {
		$errors['endTime'] = "Incorrect End Time format.";
	}
} else {
	$errors['endTime'] = "Event End Time is Required.";
}

if ( ! isset($_POST['event-location']) ) {
	$errors['location'] = "Event Location is Required";
}

if ( ! isset($_POST['event-subject']) ) {
	$errors['subject'] = "Event Subject is Required";
}

if ( ! isset($_POST['event-details']) ) {
	$errors['details'] = "Event Details is Required";
}

if ( empty($errors) ) {
	$event_date = $_POST['event-date'];
	$start_time = $_POST['event-start-time'];
	$end_time = $_POST['event-end-time'];
	
	$event_location = data_check($_POST['event-location']);
	$subject = data_check($_POST['event-subject']);
	$description = data_check($_POST['event-details']);
	
	$teacher_id = $_SESSION['id'];
	$class_id = $_SESSION['class_id'];
	
	$conn = new Dbase();
	$result =  $conn->post_event($class_id, $teacher_id, $event_date, $start_time, $end_time, $subject, $description, $event_location);
	
	if ( $result == 1 ) {
		$data['success'] = true;
		$data['message'] = 'Event posted Successfully!';
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

$email = email_event($class_id, $teacher_id, $event_date, $start_time, $end_time, $subject, $description, $event_location);
if ( $email['to'] != '' ) {
	send_email($email);
}

sms_event($class_id, $event_date);

?>