<?php
include 'db_handle.php';

session_start();

function trimmer($data) {
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}

if(isset($_GET['func']))
{
	$func = trimmer($_GET['func']);

    if($func == "get_announcements") {
        get_announcements();
	} else if($func == "get_grade_configs") {
        get_grade_configs();
    } else if($func == "get_my_name") {
        get_my_name();
	} else if($func == "get_students") {
        get_students();
	} else if($func == "get_class_subjects") {
		get_class_subjects();
	} else if($func == "get_events") {
		get_events();
	//} else if($func == "get_parent_class_meetings") {
		//get_parent_class_meetings();
	} else if($func == "get_teacher_meetings") {
		get_teacher_meetings();
	} else if($func == "get_parent_meetings") {
		get_parent_meetings();
	} else if($func == "get_teacher_notices") {
        get_teacher_notices();
	} else if($func == "get_parent_notices") {
        get_parent_notices();
	} else if($func == "get_student_leaves") {
        get_student_leaves();
	} else if($func == "get_teacher_past_leaves") {
        get_teacher_past_leaves();
	} else if($func == "get_teacher_upcoming_leaves") {
        get_teacher_upcoming_leaves();
	} else if($func == "get_teacher_na_meetings") {
        get_teacher_na_meetings();
	} else if($func == "get_parent_na_meetings") {
        get_parent_na_meetings();
	} else if($func == "get_na_leaves") {
        get_na_leaves();
	} else if($func == "get_past_homeworks") {
        get_past_homeworks();
	} else if($func == "get_due_homeworks") {
        get_due_homeworks();
	} else if($func == "get_home") {
        get_home();
	} else if($func == "get_parent_home") {
        get_parent_home();
	} else if($func == "get_notification") {
        get_notification();
	} else if($func == "get_parent_notification") {
        get_parent_notification();
	} else if($func == "get_exams") {
        get_exams();
	} else if($func == "get_student_report") {
        get_student_report();
	} else if($func == "get_teacher_info") {
        get_teacher_info();
	} else if($func == "get_teacher_profile") {
        get_teacher_profile();
	} else if($func == "get_parent_profile") {
        get_parent_profile();
	} else if($func == "get_student_attendance") {
        get_student_attendance();
	} else if($func == "get_admin_home") {
		get_admin_home();
	} else if($func == "get_school_class") {
		get_school_class();
	} else if($func == "get_classroom") {
		get_classroom();
	} else if($func == "get_class_and_teacher") {
		get_class_and_teacher();
	} else if($func == "get_school_teachers") {
		get_school_teachers();
	} else if($func == "get_holidays") {
		get_holidays();
	} else if($func == "get_albums") {
		get_albums();
	} else if($func == "get_class_and_subject") {
		get_class_and_subject();
	} else if($func == "get_class_and_exam") {
		get_class_and_exam();
    } else {
        wrong_func();
    }
}

function get_class_and_exam() {
	$school_id = $_SESSION['school_id'];
	$conn = new Dbase();
	$json = $conn->get_class_and_exam($school_id);
	echo $json;
}

function get_class_and_subject() {
	$school_id = $_SESSION['school_id'];
	$conn = new Dbase();
	$json = $conn->get_class_and_subject($school_id);
	echo $json;
}

function get_albums() {
	$school_id = $_SESSION['school_id'];
	$conn = new Dbase();
	$json = $conn->get_album($school_id);
	echo $json;
}

function get_holidays() {
	$school_id = $_SESSION['school_id'];
	$conn = new Dbase();
	$json = $conn->get_holiday($school_id);
	echo $json;
}

function get_school_teachers() {
	$school_id = $_SESSION['school_id'];
	$conn = new Dbase();
	$json = $conn->get_school_teachers($school_id);
	echo $json;
}

function get_class_and_teacher() {
	$school_id = $_SESSION['school_id'];
	$conn = new Dbase();
	$json = $conn->get_class_and_teacher($school_id);
	echo $json;
}

function get_classroom() {
	$conn = new Dbase();
	$json = $conn->get_classroom();
	echo $json;
}

function get_school_class() {
	$school_id=$_SESSION['school_id'];
	$conn = new Dbase();
	$json = $conn->get_school_class($school_id);
	echo $json;
}

function get_student_report() {
	$student_id=$_SESSION['student_id'];
	$class_id=$_SESSION['class_id'];
	$conn = new Dbase();
	$json = $conn->get_student_grade($student_id, $class_id);
	echo $json;
}

function get_student_attendance() {
	$student_id=$_SESSION['student_id'];
	$conn = new Dbase();
	$json = $conn->get_student_attendance($student_id);
	echo $json;
}

function get_teacher_info() {
	$class_id=$_SESSION['class_id'];
	$student_id=$_SESSION['student_id'];
	$conn = new Dbase();
	$json = $conn->get_teachers_info($student_id, $class_id);
	echo $json;
}

function get_teacher_profile() {
	$teacher_id=$_SESSION['id'];
	$conn = new Dbase();
	$json = $conn->get_teachers_profile($teacher_id);
	echo $json;
}

function get_parent_profile() {
	$parent_id=$_SESSION['id'];
	$conn = new Dbase();
	$json = $conn->get_parents_profile($parent_id);
	echo $json;
}

function get_exams() {
	$class_id=$_SESSION['class_id'];
	$conn = new Dbase();
	$json = $conn->get_exam($class_id);
	echo $json;
}

function get_my_name() {
	$json = json_encode($_SESSION['name']);
	echo $json;
}

function get_home() {
	$class_id=$_SESSION['class_id'];
	$teacher_id=$_SESSION['id'];
	$user_id = $_SESSION['user_id'];
	$conn = new Dbase();
	$json = $conn->get_home($class_id, $teacher_id, $user_id);
	echo $json;
}

function get_parent_home() {
	$class_id = $_SESSION['class_id'];
	$student_id = $_SESSION['student_id'];
	$user_id = $_SESSION['user_id'];
	$conn = new Dbase();
	$json = $conn->get_parent_home($class_id, $student_id, $user_id);
	echo $json;
}

function get_admin_home() {
	$school_id = $_SESSION['school_id'];
	$conn = new Dbase();
	$json = $conn->get_admin_home($school_id);
	echo $json;
}

function get_notification() {
	$class_id=$_SESSION['class_id'];
	$teacher_id=$_SESSION['id'];
	$user_id = $_SESSION['user_id'];
	$conn = new Dbase();
	$json = $conn->get_notifications($class_id, $teacher_id, $user_id);
	echo $json;
}

function get_parent_notification() {
	$class_id=$_SESSION['class_id'];
	$student_id=$_SESSION['student_id'];
	$user_id = $_SESSION['user_id'];
	$conn = new Dbase();
	$json = $conn->get_parent_notifications($class_id, $student_id, $user_id);
	echo $json;
}

function get_grade_configs() {
	$class_id=$_SESSION['class_id'];
	$conn = new Dbase();
	$json = $conn->get_grade_config($class_id);
	echo $json;
}

function get_past_homeworks() {
	$class_id=$_SESSION['class_id'];
	$conn = new Dbase();
	$json = $conn->get_past_homework($class_id);
	echo $json;
}

function get_due_homeworks() {
	$class_id=$_SESSION['class_id'];
	$conn = new Dbase();
	$json = $conn->get_due_homework($class_id);
	echo $json;
}

function get_teacher_na_meetings() {
	$teacher_id=$_SESSION['id'];
	$user_id = $_SESSION['user_id'];
	$class_id=$_SESSION['class_id'];
	$conn = new Dbase();
	$json = $conn->get_teacher_na_meeting($teacher_id, $user_id, $class_id);
	echo $json;
}

function get_parent_na_meetings() {
	$student_id=$_SESSION['student_id'];
	$user_id = $_SESSION['user_id'];
	$conn = new Dbase();
	$json = $conn->get_parent_na_meeting($student_id, $user_id);
	echo $json;
}

function get_na_leaves() {
	$teacher_id=$_SESSION['id'];
	$user_id = $_SESSION['user_id'];
	$class_id=$_SESSION['class_id'];
	$conn = new Dbase();
	$json = $conn->get_na_leave($teacher_id, $user_id, $class_id);
	echo $json;
}

function get_teacher_upcoming_leaves() {
	$class_id=$_SESSION['class_id'];
	$conn = new Dbase();
	$json = $conn->get_teacher_upcoming_leave($class_id);
	echo $json;
}

function get_teacher_past_leaves() {
	$class_id=$_SESSION['class_id'];
	$conn = new Dbase();
	$json = $conn->get_teacher_past_leave($class_id);
	echo $json;
}

function get_student_leaves() {
	$student_id=$_SESSION['student_id'];
	$conn = new Dbase();
	$json = $conn->get_student_leave($student_id);
	echo $json;
}


function get_students() {
    $class_id=$_SESSION['class_id'];
	$conn = new Dbase();
	$json = $conn->get_student($class_id);
	echo $json;
}

function get_class_subjects() {
    $class_id=$_SESSION['class_id'];
	$conn = new Dbase();
	$json = $conn->get_class_subject($class_id);
	echo $json;
}

function get_events() {
	$class_id=$_SESSION['class_id'];
	$conn = new Dbase();
	$json = $conn->get_event($class_id);
	echo $json;
}

function get_parent_class_meetings() {
	$student_id=$_SESSION['student_id'];
	$conn = new Dbase();
	$json = $conn->get_parent_class_meeting($student_id);
	echo $json;
}

function get_teacher_meetings() {
	$teacher_id=$_SESSION['id'];
	$conn = new Dbase();
	$json = $conn->get_teacher_meeting($teacher_id);
	echo $json;
}

function get_parent_meetings() {
	$student_id=$_SESSION['student_id'];
	$conn = new Dbase();
	$json = $conn->get_parent_meeting($student_id);
	echo $json;
}

function get_announcements() {
    $class_id=$_SESSION['class_id'];
	$conn = new Dbase();
	$json = $conn->get_announcement($class_id);
	echo $json;
}

function get_teacher_notices() {
    $teacher_id=$_SESSION['id'];
	$conn = new Dbase();
	$json = $conn->get_teacher_notice($teacher_id);
	echo $json;
}

function get_parent_notices() {
    $student_id=$_SESSION['student_id'];
	$conn = new Dbase();
	$json = $conn->get_parent_notice($student_id);
	echo $json;
}

function wrong_func()
{
    echo "Incorrect function";
}

?>