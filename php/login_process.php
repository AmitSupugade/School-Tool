<?php
require_once 'db_handle.php';

session_start();

function trimmer($data) {
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}

if( isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['password']) && !empty($_POST['password']) ) {
	
	$username=trimmer($_POST['username']);
	$password=trimmer($_POST['password']);

	$conn = new Dbase();
	$stmt = $conn->get_user($username);
	$count= $stmt->rowCount();
	
	if ($count == 1) {
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		$hash = $row['Password'];
		
		if(password_verify($password, $hash)){				
			$_SESSION['username']=$username;
			$_SESSION['user_type']=$row['UserType'];

			switch($row['UserType']){
				case "parent":
					$_SESSION['user_id'] = $row['Id'];
					$_SESSION['id'] = $row['ParentId'];
					$_SESSION['student_id'] = $conn->get_parent_student($_SESSION['id']);
					$_SESSION['class_id'] = $conn->get_student_class($_SESSION['student_id']);
					$_SESSION['school_id'] = $conn->get_class_school($_SESSION['class_id']);
					$_SESSION['name'] = $conn->get_name($_SESSION['id'], $_SESSION['user_type']);
					echo "parent";
					break;
				case "teacher":
					$_SESSION['user_id'] = $row['Id'];
					$_SESSION['id'] = $row['TeacherId'];
					$_SESSION['class_id'] = $conn->get_teacher_class($_SESSION['id']);
					$_SESSION['school_id'] = $conn->get_class_school($_SESSION['class_id']);
					$_SESSION['name'] = $conn->get_name($_SESSION['id'], $_SESSION['user_type']);
					echo "teacher";
					break;
				case "admin":
					$_SESSION['user_id'] = $row['Id'];
					$_SESSION['id'] = $row['AdminId'];
					$_SESSION['school_id'] = $conn->get_admin_school($_SESSION['id']);
					echo "admin";
					break;
				default:
					echo "index";
			}
		}
		else{
			echo "password";
		}
	}
	else {
		echo "username";
	}
}
else {
	echo "Please try again.";
}


/*
if( isset($_POST['username']) && isset($_POST['password']) ) {
	
	$username=trimmer($_POST['username']);
	$password=trimmer($_POST['password']);

	$conn = new Dbase();
	$stmt = $conn->get_user($username);
	$count= $stmt->rowCount();
	
	if ($count == 1) {
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		$hash = $row['PasswordTemp'];

		if($password == $hash){				
			$_SESSION['username']=$username;
			$_SESSION['user_type']=$row['UserType'];

			//switch($_SESSION['user_type']){
			switch($row['UserType']){
				case "parent":
					$_SESSION['id'] = $row['ParentId'];
					$_SESSION['student_id'] = $conn->get_parent_student($_SESSION['id']);
					$_SESSION['class_id'] = $conn->get_student_class($_SESSION['student_id']);
					$_SESSION['name'] = $conn->get_name($_SESSION['id'], $_SESSION['user_type']);
					echo "parent";
					break;
				case "teacher":
					$_SESSION['id'] = $row['TeacherId'];
					$_SESSION['class_id'] = $conn->get_teacher_class($_SESSION['id']);
					$_SESSION['name'] = $conn->get_name($_SESSION['id'], $_SESSION['user_type']);
					echo "teacher";
					break;
				default:
					echo "index";
			}
		}
		else{
			echo "password";
		}
	}
	else {
		echo "username";
	}
}
else {
	echo "Please try again.";
}
*/
?>