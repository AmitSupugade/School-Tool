<?php

require_once 'db_handle.php';

$errors = array();
$data = array();

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

if ( ! isset($_POST['school-name']) ) {
	$errors['school-name'] = "School Name is Required";
}

if ( ! isset($_POST['reg-number']) ) {
	$errors['reg-number'] = "School Registratio Number is Required";
}

if ( ! isset($_POST['address']) || empty($_POST['address']) ) {
	$errors['address'] = "Address is Required";
}

if ( ! isset($_POST['city']) ) {
	$errors['city'] = "City is Required";
}

if ( ! isset($_POST['pin']) ) {
	$errors['pin'] = "Pin is Required";
}

if( isset($_POST['phone-number']) && !empty($_POST['phone-number']) ) {
	if ( ! validateMobile($_POST['phone-number']) ) {
		$errors['phone-number'] = "Invalid Phone Number. Please enter numbers only";
	}
}
else {
	$errors['phone-number'] = "Phone Number is Required.";
}

if( isset($_POST['email']) && !empty($_POST['email']) ) {
	if ( ! validateEmail($_POST['email']) ) {
		$errors['email'] = "Invalid Email.";
	}
}
else {
	$errors['email'] = "Email is Required.";
}

if ( ! isset($_POST['username']) || empty($_POST['username']) ) {
	$errors['username'] = "Username is Required";
}

if ( ! isset($_POST['password']) ) {
	$errors['password'] = "Password is Required";
}

if ( ! isset($_POST['password-check']) ) {
	$errors['password-check'] = "Password is Required";
}

if ( empty($errors) ) {
	$name = $_POST['school-name'];
	$reg_number = $_POST['reg-number'];
	$year = $_POST['year']; 
	$website = $_POST['website'];
	$address = $_POST['address'];
	$city = $_POST['city'];
	$pin = $_POST['pin'];
	$phone = $_POST['phone-number'];
	$email = $_POST['email'];
	$username = $_POST['username'];
	$password = $_POST['password'];
	
	$conn = new Dbase();
	$result = $conn->add_school($name, $reg_number, $year, $website, $address, $city, $pin, $phone, $email, $username, $password);
	
	if ( $result == 1 ) {
		$data['success'] = true;
		$data['message'] = 'School Added Successfully! Our team will contact your school to validate your account and you can start using noblepeer after successful validation of your account. Thank you!!';
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