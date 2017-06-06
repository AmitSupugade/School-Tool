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

function validateGender($gender) {
	//return preg_match('/^M(ale)?$|^F(emale)?$/i', $gender);
	return preg_match('/^(M|F|m|f|Male|Female|MALE|FEMALE){1}$/i', $gender);
}

function validateEmail($email){
	return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function validateMobile($mobile) {
	return preg_match('/^((\+*)((0[ -]+)*|(91 )*)(\d{12}+|\d{10}+))|\d{5}([- ]*)\d{6}$/i', $mobile);
}

function validateDate($date, $format = 'Y-m-d') {
	$d = DateTime::createFromFormat($format, $date);
	return $d && $d->format($format) == $date;
}

$errors = array();
$data = array();

$fName = '';
$mName = '';
$lName = '';
$mobile = '';
$email = '';
$desk = '';
$education = '';
$designation = '';
$experience = '';
$dateOfBirth = '';
$gender = '';
$description = '';
$alert = 0;

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

if( isset($_POST['gender']) && !empty($_POST['gender']) ) {
	if ( ! validateGender($_POST['gender']) ) {
		$errors['gender'] = "Gender format do not match. Choose from- (Male/Female/M/F).";
	}
}
else {
	$errors['gender'] = "Gender is Required.";
}

if( isset($_POST['mobile']) && !empty($_POST['mobile']) ) {
	if ( ! validateMobile($_POST['mobile']) ) {
		$errors['mobile'] = "Invalid Mobile Number.";
	}
}
else {
	$errors['mobile'] = "Mobile Number is Required.";
}

if( isset($_POST['email']) && !empty($_POST['email']) ) {
	if ( ! validateEmail($_POST['email']) ) {
		$errors['email'] = "Invalid Email.";
	}
}
else {
	$errors['email'] = "Email is Required.";
}

if ( ! isset($_POST['desk']) || empty($_POST['desk']) ) {
	$errors['desk'] = "Desk Location is Required";
}

if( isset($_POST['birth-date']) && !empty($_POST['birth-date']) ) {
	if ( ! validateDate($_POST['birth-date']) ) {
		$errors['date'] = "Incorrect Date format.";
	}
}
else {
	$errors['date'] = "Event Date is Required.";
}

if ( isset($_POST['email-alert'])) {
	$alert = 1;
}

if ( empty($errors) ) {
	
	$fName = data_check($_POST['f-name']);
	$mName = data_check($_POST['m-name']);
	$lName = data_check($_POST['l-name']);
	$mobile = data_check($_POST['mobile']);
	$email = data_check($_POST['email']);
	$desk = data_check($_POST['desk']);
	$education = data_check($_POST['education']);
	$designation = data_check($_POST['designation']);
	$experience = data_check($_POST['experience']);
	$dateOfBirth = data_check($_POST['birth-date']);
	$gender = data_check($_POST['gender']);
	$description = data_check($_POST['description']);
	$teacher_id = $_SESSION['id'];
	
	$conn = new Dbase();
	$result =  $conn->update_tprofile($teacher_id, $fName, $mName, $lName, $mobile, $email, $alert, $desk, $education, $designation, $experience, $dateOfBirth, $gender, $description);
	
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