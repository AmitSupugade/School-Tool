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

function encrypt_data($id) {
	$data=($id + 24) * 1947;
	$key = 2017;

	$date=date('d') + 10;
	$month=date('m') + 10;
	$year=date('Y');
	$iv=$date.$month.$year;
	$iv=$iv.$iv;

	$encrypt = base64_encode(openssl_encrypt($data, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $iv));
	return $encrypt;
}

if ( ! isset($_POST['username']) ) {
	$errors['uname'] = "Please Enter Username.";
}

if ( empty($errors) ) {
	
	$username = data_check($_POST['username']);
	
	$conn = new Dbase();
	$result =  $conn->get_username_id($username);
	$id= $result;
	
	$encrypt = encrypt_data($id);
	$link="http://localhost:8080/server/reset.html?en=" . $encrypt;
	
	$data['success'] = true;
	$data['message'] = "Sent";

	/*
	$result = 1;
	if ( $result == 1 ) {
		$data['success'] = true;
		$data['message'] = 'Sent';
	} else {
		$data['success'] = false;
		$errors['db'] = "Databse Error. ". $result;
		$data['errors']  = $errors;
	}
	*/
} else {
	$data['success'] = false;
	$data['errors']  = $errors;
}

echo json_encode($data);
/*
$email = email_reset_passwd_link($id, $link);
if ( $email['to'] != '' ) {
	send_email($email);
}
*/
	
?>