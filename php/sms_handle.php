<?php
require_once 'db_handle.php';

include 'way2sms-api.php';


function send_sms($send_to, $message) {
	$sms_user="9637365752";
	$sms_password="Amit1989";
	$result = sendWay2SMS($sms_user, $sms_password, $send_to, $message);
	//echo $result[0]['result']? 'true' : 'false';
}

function sms_announcement($class_id, $title) {
	$conn = new Dbase();
	$to = $conn->get_class_parent_mobile($class_id);
	$text = "Announcement Title- " . $title . " Visit noblepeer.com to find more details.";	
	if ( $to != '' ) {
		send_sms($to, $text);
	}
}

function sms_notice($students, $title) {
	$conn = new Dbase();
	$to = $conn->get_students_parent_mobile($students);
	$text = "Notice Title- " . $title . " Visit noblepeer.com to find more details.";
	if ( $to != '' ) {
		send_sms($to, $text);
	}
}

function sms_grade($class_id, $subject_id, $exam_id) {
	$conn = new Dbase();
	$to = $conn->get_class_parent_mobile($class_id);
	$examName = $conn->get_examName($exam_id);
	$subjectName = $conn->get_subjectName($subject_id);
	
	$text="Grades posted for Subject- " . $subjectName . " and Exam- " . $examName . ". Visit noblepeer.com to find more details.";
	if ( $to != '' ) {
		send_sms($to, $text);
	}
}

function sms_meeting($students, $meeting_date) {
	$conn = new Dbase();
	$text = "New meeting on ". $meeting_date . " is requested. Visit noblepeer.com to find more details.";
	$to = $conn->get_students_parent_mobile($students);
	if ( $to != '' ) {
		send_sms($to, $text);
	}
	
}

function sms_event($class_id, $event_date) {
	$conn = new Dbase();
	$to = $conn->get_class_parent_mobile($class_id);
	$text = "New event on '. $event_date . ' is posted. Visit noblepeer.com to find more details.";
	if ( $to != '' ) {
		send_sms($to, $text);
	}
}

function sms_leave($student_id, $class_id, $leave_date) {
	$conn = new Dbase();
	$to = $conn->get_teacher_mobile($class_id);
	
	$students = array($student_id);
	$studentName = $conn->get_student_names($students);
	$text = "New leave on " . $leave_date . " requested for ". $studentName;
	
	if ( $to != '' ) {
		send_sms($to, $text);
	}
	
}


/*
$sms_user="9637365752";
$sms_password="Amit1989";
$send_to="9689497053";
$message="Test SMS from Noblepeer. -Amit";
	
$result = sendWay2SMS($sms_user, $sms_password, $send_to, $message);
echo $result[0]['result']? 'true' : 'false';
*/
/*
include('way2sms-api.php');
$client = new WAY2SMSClient();
$client->login('9637365752', 'Amit1989');
$client->send('9145442544', 'Test message from noblepeer');
//Add sleep between requests to make this requests more human like! 
//A blast of request's may mark the ip as spammer and blocking further requests.
sleep(1);
#$client->send('987654321,9876501234', 'msg2');
#sleep(1);
$client->logout();
*/

?>