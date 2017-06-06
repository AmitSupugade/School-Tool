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

$errors = array();
$data = array();

$leave_date = '';
$reason = '';

if( isset($_POST['leave-date']) ) {
	if ( ! validateDate($_POST['leave-date']) ) {
		$errors['date'] = "Incorrect Date format.";
	}
}
else {
	$errors['date'] = "Date is Required.";
}

if ( ! isset($_POST['leave-reason']) ) {
	$errors['reason'] = "Reason is Required";
}

if ( empty($errors) ) {
	
	$leave_date = $_POST['leave-date'];
	$reason = data_check($_POST['leave-reason']);
	
	$student_id = $_SESSION['student_id'];
	$class_id = $_SESSION['class_id'];
	
	$conn = new Dbase();
	$result =  $conn->post_leave($student_id, $class_id, $leave_date, $reason);
	
	if ( $result == 1 ) {
		$data['success'] = true;
		$data['message'] = 'Leave requested Successfully!';
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

$email = email_leave($student_id, $class_id, $leave_date, $reason);
if ( $email['to'] != '' ) {
	send_email($email);
}

sms_leave($student_id, $class_id, $leave_date);

?>