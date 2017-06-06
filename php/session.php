<?php 
include 'db_handle.php';
session_start();

//Redirects to 'index.php' if session is not started- Meaning if user is not logged in.
if ( session_id() == '' || !isset($_SESSION['user_type'])) {
	session_destroy();
	echo "index";
} else {
	switch($_SESSION['user_type']) {
		case "parent":
			echo "parent";
			break;
		case "teacher":
			echo "teacher";
			break;
		case "admin":
			echo "admin";
			break;
		default:
			echo "index";
	}
}

?>