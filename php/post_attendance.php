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

function getMonth($date) {
	return date('n',strtotime($date));
	
}

$errors = array();
$data = array();

$attendance_date = '';

if( isset($_POST['attendance-date']) ) {
	if ( ! validateDate($_POST['attendance-date']) ) {
		$errors['date'] = "Incorrect Date format.";
	}
}
else {
	$errors['date'] = "Date is Required.";
}

if ( ! isset($_POST['attendance-student']) || empty($_POST['attendance-student']) ) {
	$errors['student'] = "Select at least one student from the list.";
}

if ( empty($errors) ) {
	
	$students = $_POST['attendance-student'];
	$attendance_date = $_POST['attendance-date'];
	$month = getMonth($attendance_date);
	$class_id = $_SESSION['class_id'];
	
	$conn = new Dbase();
	$result =  $conn->post_attendance($students, $class_id, $attendance_date, $month);
	
	if ( $result == 1 ) {
		$data['success'] = true;
		$data['message'] = 'Attendance posted Successfully!';
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
/*
$email = email_meeting($students, $teacher_id, $meeting_date, $start_time, $end_time, $subject, $description, $meeting_location);
if ( $email['to'] != '' ) {
	send_email($email);
}
*/
?>