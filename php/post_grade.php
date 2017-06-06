<?php
require_once 'db_handle.php';
require_once 'npmail.php';

session_start();

function validateNum($num) {
	return preg_match('/^\d{1,3}(\.\d{1,2})?$/i', $num);
}

function validateMarks($total, $obtained) {
	return $total >= $obtained;
}

$errors = array();
$data = array();

$subject_id = '';
$exam_id = '';
$total_marks = '';
$is_tMarks_valid = false;
	
if( isset($_POST['total-marks']) ) {
	if ( ! validateNum($_POST['total-marks']) ) {
		$errors['tMarks'] = "Invalid 'Total Marks' value. Please use format similar to '100 or 100.00'.";
	}
	else {
		$is_tMarks_valid = true;
	}
}
else {
	$errors['tMarks'] = "Total Marks is Required.";
}

if ( ! isset($_POST['id']) ) {
	$errors['id'] = "Student count mismatch. Please try again.";
}

if ($is_tMarks_valid) {
	if ( ! isset($_POST['marks']) || empty($_POST['marks']) ) {
		$errors['marks'] = "Please Enter Marks obtained for all students.";
	} elseif ( count($_POST['id']) != count($_POST['marks']) ) {
		$errors['marks'] = "Please Enter Marks obtained for all students.";
	} else {
		foreach($_POST['marks'] as $num) {
			if ( ! validateMarks($_POST['total-marks'], $num)) {
				$errors['marks'] = "All Marks Obtained must be less than Total Marks.";
			}
			if ( ! validateNum($num) ) {
				$errors['marks'] = "Invalid 'Marks obtained' value. Please use format similar to '100 or 100.00'.";
			}
		}
	} 
}

if ( ! isset($_POST['remark']) ) {
	$errors['remark'] = "Student count mismatch. Please try again.";
}

if ( ! isset($_POST['grade-subject']) ) {
	$errors['subject'] = "Subject is Required";
}

if ( ! isset($_POST['grade-exam']) ) {
	$errors['exam'] = "Exam is Required";
}

if ( empty($errors) ) {
	
	$subject_id=$_POST['grade-subject'];
	$exam_id=$_POST['grade-exam'];
	$students=$_POST['id'];
	$marks=$_POST['marks'];
	$remarks=$_POST['remark'];
	
	$class_id=$_SESSION['class_id'];
	$total_marks = $_POST['total-marks'];
	
	$conn = new Dbase();
	$result =  $conn->post_grade($class_id, $subject_id, $exam_id, $total_marks, $students, $marks, $remarks);
	
	if ( $result == 1 ) {
		$data['success'] = true;
		$data['message'] = 'Grades posted Successfully!';
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

$email = email_grade($class_id, $subject_id, $exam_id);
if ( $email['to'] != '' ) {
	send_email($email);
}

sms_grade($class_id, $subject_id, $exam_id);

?>