<?php
require_once 'db_handle.php';

session_start();

function data_check($data) {
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}

function validateName($name) {
	return preg_match('/^[a-zA-Z\\s]*$/i', $name);
}

function validateEmail($email){
	return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function validateMobile($mobile) {
	return preg_match('/^((\+*)((0[ -]+)*|(91 )*)(\d{12}+|\d{10}+))|\d{5}([- ]*)\d{6}$/i', $mobile);
}

$errors = array();
$data = array();

$fName = '';
$mName = '';
$lName = '';
$mobile = '';
$email1 = '';
$email2 = '';
$alert = 0;
$address = '';

if( isset($_POST['f-name']) && !empty($_POST['f-name']) ) {
	if ( ! validateName($_POST['f-name']) ) {
		$errors['name'] = "Name should have characters only.";
	}
}
else {
	$errors['name'] = "Full Name is Required.";
}

if( isset($_POST['m-name']) && !empty($_POST['m-name']) ) {
	if ( ! validateName($_POST['m-name']) ) {
		$errors['name'] = "Name should have characters only.";
	}
}
else {
	$errors['name'] = "Full Name is Required.";
}

if( isset($_POST['l-name']) && !empty($_POST['l-name']) ) {
	if ( ! validateName($_POST['l-name']) ) {
		$errors['name'] = "Name should have characters only.";
	}
}
else {
	$errors['name'] = "Full Name is Required.";
}

if( isset($_POST['mobile']) && !empty($_POST['mobile']) ) {
	if ( ! validateMobile($_POST['mobile']) ) {
		$errors['mobile'] = "Invalid Mobile Number. Please enter numbers only";
	}
}
else {
	$errors['mobile'] = "Mobile Number is Required.";
}

if( isset($_POST['email1']) && !empty($_POST['email1']) ) {
	if ( ! validateEmail($_POST['email1']) ) {
		$errors['email1'] = "Invalid Email1.";
	}
}
else {
	$errors['email1'] = "Email1 is Required.";
}

if( isset($_POST['email2']) && !empty($_POST['email2']) ) {
	if ( ! validateEmail($_POST['email2']) ) {
		$errors['email2'] = "Invalid Email2.";
	}
}

if ( ! isset($_POST['address']) || empty($_POST['address']) ) {
	$errors['address'] = "Address is Required";
}

if ( isset($_POST['email-alert'])) {
	$alert = 1;
}

if ( empty($errors) ) {
	
	$fName = $_POST['f-name'];
	$mName = $_POST['m-name'];
	$lName = $_POST['l-name'];
	$mobile = $_POST['mobile'];
	$email1 = $_POST['email1'];
	$email2 = $_POST['email2'];
	$address = data_check($_POST['address']);
	$parent_id = $_SESSION['id'];
	
	$conn = new Dbase();
	$result =  $conn->update_pprofile($parent_id, $fName, $mName, $lName, $mobile, $email1, $email2, $alert, $address);
	
	if ( $result == 1 ) {
		$data['success'] = true;
		$data['message'] = 'Profile updated Successfully!';
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