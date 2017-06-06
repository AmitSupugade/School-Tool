<?php
require_once 'db_handle.php';

session_start();

function data_check($data) {
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}

function old_passwd_check($password) {
	$username= $_SESSION['username'];
	$conn = new Dbase();
	$stmt = $conn->get_user($username);
	$count= $stmt->rowCount();
	
	if ($count == 1) {
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		$hash = $row['Password'];
		
		if(password_verify($password, $hash)){	
			return true;		
		} else {
			return false;
		}
	} else {
		return false;
	}
	
}

$errors = array();
$data = array();

if ( ! isset($_POST['old-pwd']) ) {
	$errors['oldPwd'] = "Old Password is Required";
}

if ( ! isset($_POST['new-pwd']) ) {
	$errors['newPwd'] = "New Password is Required";
}

if ( ! isset($_POST['new-pwd-again']) ) {
	$errors['newPwdAgain'] = "Confirm New Password is Required";
}

if ( ! old_passwd_check($_POST['old-pwd']) ) {
	$errors['noOldPwd'] = "Old Password is not correct. Please try again.";
}

if ( $_POST['new-pwd'] != $_POST['new-pwd-again']) {
	$errors['noNewPwd'] = "Confirm New Password should match New Password.";
}

if ( empty($errors) ) {
	$new_pwd=$_POST['new-pwd'];
	$username= $_SESSION['username'];
	$conn = new Dbase();
	$result =  $conn->change_password($username, $new_pwd);
	if ( $result == 1 ) {
		$data['success'] = true;
		$data['message'] = 'Password changed Successfully!';
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