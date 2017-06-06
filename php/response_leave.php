<?php
require_once 'db_handle.php';

session_start();

function validateResponse($response) {
	return $response == "Approve" or $response == "Deny";
}

$errors = array();
$data = array();

if ( ! isset($_POST['value']) ) {
	$errors['value'] = "Response is Missing. Please refresh the page and try again.";
} elseif ( ! validateResponse($_POST['value']) ) {
	$errors['value'] = "Response Unidentified. Please refresh the page and try again.";
}

if ( ! isset($_POST['leave-na-response']) ) {
	$errors['leave'] = "Could not find leave. Please refresh the page and try again.";
}

if ( empty($errors) ) {
	$result = 0;
	$leave_id = $_POST['leave-na-response'];
	$action = $_POST['value'];
	
	$conn = new Dbase();
	
	if ($action == "Approve") {
		$result = $conn->na_leave_approve($leave_id);
	}
	else if ($action == "Deny") {
		$result = $conn->na_leave_deny($leave_id);
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