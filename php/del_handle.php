<?php
include 'db_handle.php';

session_start();

function trimmer($data) {
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}

$errors = array();
$data = array();

$id = '';
$type = '';

if ( ! isset($_POST['val']) ) {
	$errors['val'] = "Could not Identify post. Please refresh the page and try again.";
}

if ( ! isset($_POST['type']) ) {
	$errors['type'] = "Could not Identify post. Please refresh the page and try again.";
}

if ( empty($errors) ) {
	
	$id = trimmer($_POST['val']);
	$list = explode(',', $id);
	$type = trimmer($_POST['type']);
	
	$conn = new Dbase();
	
	switch ($type) {
		case "mtg":
			$result = $conn->delete_meeting($list);
			break;
		case "evt":
			$result = $conn->delete_event($list);
			break;
		default:
			$result = 0;
	}
	
	if ( $result == 1 ) {
		$data['success'] = true;
		$data['message'] = 'Deleted Successfully!';
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