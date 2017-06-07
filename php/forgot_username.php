<?php
require_once 'db_handle.php';
require_once 'npmail.php';

function data_check($data) {
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}

$errors = array();
$data = array();


if ( ! isset($_POST['email']) ) {
	$errors['email'] = "Please Enter Email.";
}

if ( empty($errors) ) {
	
	$emailID = data_check($_POST['email']);
	
	$conn = new Dbase();
	$result =  $conn->get_email_username($emailID);
	$username= $result;
	
	if ($username == "") {
		$data['success'] = false;
		$errors['noEmail'] = "Could not find Username associated with the Email provided.";
		$data['errors']  = $errors;
	} else {
		$data['success'] = true;
		$data['message'] = "Sent";
	}
	
} else {
	$data['success'] = false;
	$data['errors']  = $errors;
}

echo json_encode($data);

if ($username != "") {
	$email = email_forgot_username($emailID, $username);
	if ( $email['to'] != '') {
		send_email($email);
	}
}

?>