<?php
require_once 'db_handle.php';

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

$holiday_date = '';

if( isset($_POST['holiday-date']) ) {
	if ( ! validateDate($_POST['holiday-date']) ) {
		$errors['date'] = "Incorrect Date format.";
	}
}
else {
	$errors['date'] = "Date is Required.";
}


if ( empty($errors) ) {
	
	$holiday_date = $_POST['holiday-date'];
	
	$school_id = $_SESSION['school_id'];
	
	$conn = new Dbase();
	$result =  $conn->post_holiday($holiday_date, $school_id);
	
	if ( $result == 1 ) {
		$data['success'] = true;
		$data['message'] = 'Holiday Posted Successfully!';
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