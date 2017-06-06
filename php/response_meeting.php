<?php
require_once 'db_handle.php';

session_start();

function validateResponse($response) {
	return $response == "Accept" or $response == "Reject";
}

$errors = array();
$data = array();

$meeting_id = '';
$action = '';

if ( ! isset($_POST['value']) ) {
	$errors['value'] = "Response is Missing. Please refresh the page and try again.";
} elseif ( ! validateResponse($_POST['value']) ) {
	$errors['value'] = "Response Unidentified. Please refresh the page and try again.";
}

if ( ! isset($_POST['mtg-na-response']) ) {
	$errors['mtg'] = "Could not find meeting. Please refresh the page and try again.";
}

if ( empty($errors) ) {
	$result = 0;
	$meeting_id = $_POST['mtg-na-response'];
	$action = $_POST['value'];
	
	$conn = new Dbase();
	if ($action == "Accept") {
		$result = $conn->na_meeting_accept($meeting_id);
	}
	else if ($action == "Reject") {
		$result = $conn->na_meeting_reject($meeting_id);
	}
	
	if ( $result == 1 ) {
		$data['success'] = true;
		$data['message'] = 'Grades posted Successfully!';
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