<?php
require_once 'db_handle.php';
require_once 'npmail.php';

session_start();

function data_check($data) {
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}

$errors = array();
$data = array();

if ( ! isset($_POST['photo-album']) ) {
	$errors['album'] = "Album is Required";
}


if (isset($_FILES['file-photo'])) {
	foreach($_FILES['file-photo']['tmp_name'] as $key => $tmp_name) {
	
		if (!isset($_FILES['file-photo']['error'][$key]) || is_array($_FILES['file-photo']['error'][$key])) {
			$errors['fileError'] = "Invalid file parameters after uploading file. Please try again.";
		}

		if ($_FILES['file-photo']['error'][$key] !== 0) { 
			$errors['fileError'] = "File upload error. Please try again.";
		}

		$extensions = array("jpeg", "png", "jpg");
		$file_ext = pathinfo($_FILES['file-photo']['name'][$key], PATHINFO_EXTENSION);
		if(in_array($file_ext, $extensions) === false) {
			$errors['wrongFormat'] = "Only jpeg, jpg, png files are allowed. ";
		}
	}
}
else {
	$errors['noUpload'] = "Please select files to add to the album.";
}


if ( empty($errors) ) {
	
	$album_id = data_check($_POST['photo-album']);
	$files = $_FILES['file-photo'];
	
	$conn = new Dbase();
	$result =  $conn->add_photo($album_id, $_FILES['file-photo']);
	
	if ( $result == 1 ) {
		$data['success'] = true;
		$data['message'] = 'Photos added Successfully!';
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