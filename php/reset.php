<?php
require_once 'db_handle.php';
require_once 'npmail.php';

function data_check($data) {
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}

function decrypt_data($encrypt) {
	$key = 2017;

	$date=date('d') + 10;
	$month=date('m') + 10;
	$year=date('Y');
	$iv=$date.$month.$year;
	$iv=$iv.$iv;
	
	$decrypt = openssl_decrypt(base64_decode($encrypt), 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $iv);
	$decrypt = (int) $decrypt;
	$id = ($decrypt / 1947) - 24;
	return $id;
}

$errors = array();
$data = array();

if ( ! isset($_POST['en']) ) {
	$errors['en'] = "Unable to Reset Password. Please try again.";
}

if ( ! isset($_POST['new-pwd']) ) {
	$errors['newPwd'] = "New Password is Required";
}

if ( ! isset($_POST['con-new-pwd']) ) {
	$errors['newPwdAgain'] = "Confirm New Password is Required";
}

if ( $_POST['new-pwd'] != $_POST['con-new-pwd']) {
	$errors['noNewPwd'] = "Confirm New Password should match New Password.";
}

if ( empty($errors) ) {
	
	$id = decrypt_data($_POST['en']);
	$passwd = $_POST['new-pwd'];
	
	$conn = new Dbase();
	$result =  $conn->reset_password($id, $passwd);

	if ( $result == 1 ) {
		$data['success'] = true;
		$data['message'] = "Success";
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
$email = email_announcement($class_id, $title, $description);
if ( $email['to'] != '' ) {
	send_email($email);
}
sms_announcement($class_id, $title);
*/
?>