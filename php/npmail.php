<?php
require_once 'db_handle.php';

//Send Email
function send_email($data) {
	$to="eschooladm@gmail.com";
	$emailList = $data['to'];
	
	$header = "MIME-Version: 1.0\r\n";
	$header .= "Content-type: text/html; charset=iso-8859-1\r\n";
	$header .= "Bcc: $emailList \r\n";
	
	return mail($to, $data['subject'], $data['message'], $header);
}

//ANNOUNCEMENT
function email_announcement($class_id, $title, $description) {
	$result = array();
	$conn = new Dbase();
	$teacherName = $conn->get_teachername($class_id);
	//$classname = $conn->get_classname($class_id);
	
	$message = '
	<html>
		<head>
			<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
			<meta name="format-detection" content="telephone=no"> 
			<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=no;">
			<meta http-equiv="X-UA-Compatible" content="IE=9; IE=8; IE=7; IE=EDGE" />
		</head>
		<body>
			<div>
				<table cellpadding="0" cellspacing="0" border="0" align="center" valign="top" style="font-size: 1.5em; margin-top: 1em; margin-bottom: 2em; width: 100%; max-width: 640px;">
					<tr>
						<table>
							<tr align="center" bgcolor="#E9E9E9">
								<img src="http://demo.noblepeer.com/images/Noblepeer1.png" alt="Noblepeer" style="width: 100%; max-width: 400px;"/>
							</tr>
							<tr>
								<h3>New announcement posted by ' . $teacherName . '. Below are the details- </h3>
							</tr>
						</table>
					</tr>
					<tr>
						<table border="1">
							<tr>
								<td align="left" width="270px" valign="top">
									<p style="font-weight: bold;"> Posted on: </p>
								</td>
								<td align="left" width="370px" valign="top">
									<p> ' . date("Y-m-d") . '</p>
								</td>
							</tr>
							<tr>
								<td align="left" width="270px" valign="top">
									<p style="font-weight: bold;"> Title: </p>
								</td>
								<td align="left" width="370px" valign="top">
									<p> ' . $title . ' </p>
								</td>
							</tr>
							<tr>
								<td align="left" width="270px" valign="top">
									<p style="font-weight: bold;"> Description: </p>
								</td>
								<td align="left" width="370px" valign="top">
									<p> ' . $description . ' </p>
								</td>
							</tr>
						</table>
					</tr>
					<tr>
						<table>
							<tr>
								<p style="color:gray">NOTE: This is an auto-generated email. Please do not reply to this email. If you have any questions, contact class teacher or school for details.</p>
							</tr>
							<tr>
								<p style="color:gray">If you do not wish to receive such emails in future, Please update Instant Email preference in your Noblepeer profile to unsubscribe.</p>
							</tr>
						</table>
					</tr>
				</table>
			</div>
		</body>
	</html>';
	
	$subject = "New Announcement posted for your class on ". date("Y-m-d");
	$to = $conn->get_class_parent_email($class_id);
	
	$result['message'] = $message;
	$result['subject'] = $subject;
	$result['to'] = $to;
	
	//echo $message, $subject, $to;
	return $result;
}

//NOTICE
function email_notice($students, $teacher_id, $title, $description) {
	$result = array();
	$conn = new Dbase();
	
	$to = $conn->get_students_parent_email($students);
	$subject = "New Notice posted for your child on ". date("Y-m-d");
	
	$teacherName = $conn->get_Teachername($teacher_id);
	$studentName = $conn->get_student_names($students);
	
	$message = '
	<html>
		<head>
			<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
			<meta name="format-detection" content="telephone=no"> 
			<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=no;">
			<meta http-equiv="X-UA-Compatible" content="IE=9; IE=8; IE=7; IE=EDGE" />
		</head>
		<body>
			<div>
				<table cellpadding="0" cellspacing="0" border="0" align="center" valign="top" style="font-size: 1.5em; margin-top: 1em; margin-bottom: 2em; width: 100%; max-width: 640px;">
					<tr>
						<table>
							<tr align="center" bgcolor="#E9E9E9">
								<img src="http://demo.noblepeer.com/images/Noblepeer1.png" alt="Noblepeer" style="width: 100%; max-width: 400px;"/>
							</tr>
							<tr>
								<h3>New Notice posted by ' . $teacherName . '. Below are the details- </h3>
							</tr>
						</table>
					</tr>
					<tr>
						<table border="1">
							<tr>
								<td align="left" width="270px" valign="top">
									<p style="font-weight: bold;"> Posted on: </p>
								</td>
								<td align="left" width="370px" valign="top">
									<p> ' . date("Y-m-d") . '</p>
								</td>
							</tr>
							<tr>
								<td align="left" width="270px" valign="top">
									<p style="font-weight: bold;"> Posted for: </p>
								</td>
								<td align="left" width="370px" valign="top">
									<p> ' . $studentName . ' </p>
								</td>
							</tr>
							<tr>
								<td align="left" width="270px" valign="top">
									<p style="font-weight: bold;"> Title: </p>
								</td>
								<td align="left" width="370px" valign="top">
									<p> ' . $title . ' </p>
								</td>
							</tr>
							<tr>
								<td align="left" width="270px" valign="top">
									<p style="font-weight: bold;"> Description: </p>
								</td>
								<td align="left" width="370px" valign="top">
									<p> ' . $description . ' </p>
								</td>
							</tr>
						</table>
					</tr>
					<tr>
						<table>
							<tr>
								<p style="color:gray">NOTE: This is an auto-generated email. Please do not reply to this email. If you have any questions, contact class teacher or school for details.</p>
							</tr>
							<tr>
								<p style="color:gray">If you do not wish to receive such emails in future, Please update Instant Email preference in your Noblepeer profile to unsubscribe.</p>
							</tr>
						</table>
					</tr>
				</table>
			</div>
		</body>
	</html>';
	
	$result['message'] = $message;
	$result['subject'] = $subject;
	$result['to'] = $to;
	
	//echo $message;
	return $result;
}

//HOMEWORK
function email_homework($class_id, $teacher_id, $subject_id, $due_date, $due_time, $topic, $description) {
	$result = array();
	$conn = new Dbase();
	
	$to = $conn->get_class_parent_email($class_id);
	$subject = "New homework posted for your class on ". date("Y-m-d");
	
	$teacherName = $conn->get_Teachername($teacher_id);
	$subjectName = $conn->get_subjectName($subject_id);
	
	$message = '
	<html>
		<head>
			<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
			<meta name="format-detection" content="telephone=no"> 
			<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=no;">
			<meta http-equiv="X-UA-Compatible" content="IE=9; IE=8; IE=7; IE=EDGE" />
		</head>
		<body>
			<div>
				<table cellpadding="0" cellspacing="0" border="0" align="center" valign="top" style="font-size: 1.5em; margin-top: 1em; margin-bottom: 2em; width: 100%; max-width: 640px;">
					<tr>
						<table>
							<tr align="center" bgcolor="#E9E9E9">
								<img src="http://demo.noblepeer.com/images/Noblepeer1.png" alt="Noblepeer" style="width: 100%; max-width: 400px;"/>
							</tr>
							<tr>
								<h3>New Homework posted by ' . $teacherName . '. Below are the details- </h3>
							</tr>
						</table>
					</tr>
					<tr>
						<table border="1">
							<tr>
								<td align="left" width="270px" valign="top">
									<p style="font-weight: bold;"> Posted on: </p>
								</td>
								<td align="left" width="370px" valign="top">
									<p> ' . date("Y-m-d") . '</p>
								</td>
							</tr>
							<tr>
								<td align="left" width="270px" valign="top">
									<p style="font-weight: bold;"> Subject: </p>
								</td>
								<td align="left" width="370px" valign="top">
									<p> ' . $subjectName . ' </p>
								</td>
							</tr>
							<tr>
								<td align="left" width="270px" valign="top">
									<p style="font-weight: bold;"> Due on: </p>
								</td>
								<td align="left" width="370px" valign="top">
									<p> ' . $due_date . ' at ' . $due_time . ' </p>
								</td>
							</tr>
							<tr>
								<td align="left" width="270px" valign="top">
									<p style="font-weight: bold;"> Topic: </p>
								</td>
								<td align="left" width="370px" valign="top">
									<p> ' . $topic . ' </p>
								</td>
							</tr>
							<tr>
								<td align="left" width="270px" valign="top">
									<p style="font-weight: bold;"> Description: </p>
								</td>
								<td align="left" width="370px" valign="top">
									<p> ' . $description . ' </p>
								</td>
							</tr>
						</table>
					</tr>
					<tr>
						<table>
							<tr>
								<p style="color:gray">NOTE: This is an auto-generated email. Please do not reply to this email. If you have any questions, contact class teacher or school for details.</p>
							</tr>
							<tr>
								<p style="color:gray">If you do not wish to receive such emails in future, Please update Instant Email preference in your Noblepeer profile to unsubscribe.</p>
							</tr>
						</table>
					</tr>
				</table>
			</div>
		</body>
	</html>';
	
	$result['message'] = $message;
	$result['subject'] = $subject;
	$result['to'] = $to;
	
	//echo $message, $subject, $to;
	return $result;
}

//EVENT
function email_event($class_id, $teacher_id, $event_date, $start_time, $end_time, $event_subject, $description, $event_location) {
	$result = array();
	$conn = new Dbase();
	
	$to = $conn->get_class_parent_email($class_id);
	$subject = "New event posted for your class on ". date("Y-m-d");
	
	$teacherName = $conn->get_Teachername($teacher_id);
	
	$message = '
	<html>
		<head>
			<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
			<meta name="format-detection" content="telephone=no"> 
			<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=no;">
			<meta http-equiv="X-UA-Compatible" content="IE=9; IE=8; IE=7; IE=EDGE" />
		</head>
		<body>
			<div>
				<table cellpadding="0" cellspacing="0" border="0" align="center" valign="top" style="font-size: 1.5em; margin-top: 1em; margin-bottom: 2em; width: 100%; max-width: 640px;">
					<tr>
						<table>
							<tr align="center" bgcolor="#E9E9E9">
								<img src="http://demo.noblepeer.com/images/Noblepeer1.png" alt="Noblepeer" style="width: 100%; max-width: 400px;"/>
							</tr>
							<tr>
								<h3>New Event posted by ' . $teacherName . '. Below are the details- </h3>
							</tr>
						</table>
					</tr>
					<tr>
						<table border="1">
							<tr>
								<td align="left" width="270px" valign="top">
									<p style="font-weight: bold;"> Posted on: </p>
								</td>
								<td align="left" width="370px" valign="top">
									<p> ' . date("Y-m-d") . '</p>
								</td>
							</tr>
							<tr>
								<td align="left" width="270px" valign="top">
									<p style="font-weight: bold;"> Event Date: </p>
								</td>
								<td align="left" width="370px" valign="top">
									<p> ' . $event_date . ' </p>
								</td>
							</tr>
							<tr>
								<td align="left" width="270px" valign="top">
									<p style="font-weight: bold;"> Event Time: </p>
								</td>
								<td align="left" width="370px" valign="top">
									<p> ' . $start_time . ' - ' . $end_time . ' </p>
								</td>
							</tr>
							<tr>
								<td align="left" width="270px" valign="top">
									<p style="font-weight: bold;"> Location: </p>
								</td>
								<td align="left" width="370px" valign="top">
									<p> ' . $event_location . ' </p>
								</td>
							</tr>
							<tr>
								<td align="left" width="270px" valign="top">
									<p style="font-weight: bold;"> Subject: </p>
								</td>
								<td align="left" width="370px" valign="top">
									<p> ' . $event_subject . ' </p>
								</td>
							</tr>
							<tr>
								<td align="left" width="270px" valign="top">
									<p style="font-weight: bold;"> Description: </p>
								</td>
								<td align="left" width="370px" valign="top">
									<p> ' . $description . ' </p>
								</td>
							</tr>
						</table>
					</tr>
					<tr>
						<table>
							<tr>
								<p style="color:gray">NOTE: This is an auto-generated email. Please do not reply to this email. If you have any questions, contact class teacher or school for details.</p>
							</tr>
							<tr>
								<p style="color:gray">If you do not wish to receive such emails in future, Please update Instant Email preference in your Noblepeer profile to unsubscribe.</p>
							</tr>
						</table>
					</tr>
				</table>
			</div>
		</body>
	</html>';
	
	$result['message'] = $message;
	$result['subject'] = $subject;
	$result['to'] = $to;
	
	//echo $message, $subject, $to;
	return $result;
}

//MEETING
function email_meeting($students, $teacher_id, $meeting_date, $start_time, $end_time, $meeting_subject, $description, $meeting_location) {
	$result = array();
	$conn = new Dbase();
	
	$to = $conn->get_students_parent_email($students);
	$subject = "New meeting requested with you on ". date("Y-m-d");
	
	$teacherName = $conn->get_Teachername($teacher_id);
	$studentName = $conn->get_student_names($students);
	
	$message = '
	<html>
		<head>
			<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
			<meta name="format-detection" content="telephone=no"> 
			<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=no;">
			<meta http-equiv="X-UA-Compatible" content="IE=9; IE=8; IE=7; IE=EDGE" />
		</head>
		<body>
			<div>
				<table cellpadding="0" cellspacing="0" border="0" align="center" valign="top" style="font-size: 1.5em; margin-top: 1em; margin-bottom: 2em; width: 100%; max-width: 640px;">
					<tr>
						<table>
							<tr align="center" bgcolor="#E9E9E9">
								<img src="http://demo.noblepeer.com/images/Noblepeer1.png" alt="Noblepeer" style="width: 100%; max-width: 400px;"/>
							</tr>
							<tr>
								<h3>New Meeting posted by ' . $teacherName . '. Below are the details- </h3>
							</tr>
						</table>
					</tr>
					<tr>
						<table border="1">
							<tr>
								<td align="left" width="270px" valign="top">
									<p style="font-weight: bold;"> Posted on: </p>
								</td>
								<td align="left" width="370px" valign="top">
									<p> ' . date("Y-m-d") . '</p>
								</td>
							</tr>
							<tr>
								<td align="left" width="270px" valign="top">
									<p style="font-weight: bold;"> Meeting Date: </p>
								</td>
								<td align="left" width="370px" valign="top">
									<p> ' . $meeting_date . ' </p>
								</td>
							</tr>
							<tr>
								<td align="left" width="270px" valign="top">
									<p style="font-weight: bold;"> Meeting Time: </p>
								</td>
								<td align="left" width="370px" valign="top">
									<p> ' . $start_time . ' - ' . $end_time . ' </p>
								</td>
							</tr>
							<tr>
								<td align="left" width="270px" valign="top">
									<p style="font-weight: bold;"> Attendees: </p>
								</td>
								<td align="left" width="370px" valign="top">
									<p> ' . $teacherName . ' and parents of ' . $studentName . ' </p>
								</td>
							</tr>
							<tr>
								<td align="left" width="270px" valign="top">
									<p style="font-weight: bold;"> Location: </p>
								</td>
								<td align="left" width="370px" valign="top">
									<p> ' . $meeting_location . ' </p>
								</td>
							</tr>
							<tr>
								<td align="left" width="270px" valign="top">
									<p style="font-weight: bold;"> Subject: </p>
								</td>
								<td align="left" width="370px" valign="top">
									<p> ' . $meeting_subject . ' </p>
								</td>
							</tr>
							<tr>
								<td align="left" width="270px" valign="top">
									<p style="font-weight: bold;"> Description: </p>
								</td>
								<td align="left" width="370px" valign="top">
									<p> ' . $description . ' </p>
								</td>
							</tr>
						</table>
					</tr>
					<tr>
						<table>
							<tr>
								<p style="color:gray">NOTE: This is an auto-generated email. Please do not reply to this email. If you have any questions, contact class teacher or school for details.</p>
							</tr>
							<tr>
								<p style="color:gray">If you do not wish to receive such emails in future, Please update Instant Email preference in your Noblepeer profile to unsubscribe.</p>
							</tr>
						</table>
					</tr>
				</table>
			</div>
		</body>
	</html>';
	
	$result['message'] = $message;
	$result['subject'] = $subject;
	$result['to'] = $to;
	
	//echo $message, $subject, $to;
	return $result;
}

function email_parent_meeting($class_id, $student_id, $parent_id, $meeting_date, $start_time, $end_time, $meeting_subject, $description, $location) {
	$result = array();
	$conn = new Dbase();
	
	$to = $conn->get_teacher_email($class_id);
	
	$teacherName = $conn->get_teachername($class_id);
	$students = array($student_id);
	$studentName = $conn->get_student_names($students);
	$subject = "New meeting requested by parents of ". $studentName;

	$message = '
	<html>
		<head>
			<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
			<meta name="format-detection" content="telephone=no"> 
			<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=no;">
			<meta http-equiv="X-UA-Compatible" content="IE=9; IE=8; IE=7; IE=EDGE" />
		</head>
		<body>
			<div>
				<table cellpadding="0" cellspacing="0" border="0" align="center" valign="top" style="font-size: 1.5em; margin-top: 1em; margin-bottom: 2em; width: 100%; max-width: 640px;">
					<tr>
						<table>
							<tr align="center" bgcolor="#E9E9E9">
								<img src="http://demo.noblepeer.com/images/Noblepeer1.png" alt="Noblepeer" style="width: 100%; max-width: 400px;"/>
							</tr>
							<tr>
								<h3>New Meeting requested by parents of ' . $studentName . '. Below are the details- </h3>
							</tr>
						</table>
					</tr>
					<tr>
						<table border="1">
							<tr>
								<td align="left" width="270px" valign="top">
									<p style="font-weight: bold;"> Requested on: </p>
								</td>
								<td align="left" width="370px" valign="top">
									<p> ' . date("Y-m-d") . '</p>
								</td>
							</tr>
							<tr>
								<td align="left" width="270px" valign="top">
									<p style="font-weight: bold;"> Meeting Date: </p>
								</td>
								<td align="left" width="370px" valign="top">
									<p> ' . $meeting_date . ' </p>
								</td>
							</tr>
							<tr>
								<td align="left" width="270px" valign="top">
									<p style="font-weight: bold;"> Meeting Time: </p>
								</td>
								<td align="left" width="370px" valign="top">
									<p> ' . $start_time . ' - ' . $end_time . ' </p>
								</td>
							</tr>
							<tr>
								<td align="left" width="270px" valign="top">
									<p style="font-weight: bold;"> Attendees: </p>
								</td>
								<td align="left" width="370px" valign="top">
									<p> ' . $teacherName . ' and parents of ' . $studentName . ' </p>
								</td>
							</tr>
							<tr>
								<td align="left" width="270px" valign="top">
									<p style="font-weight: bold;"> Location: </p>
								</td>
								<td align="left" width="370px" valign="top">
									<p> ' . $location . ' </p>
								</td>
							</tr>
							<tr>
								<td align="left" width="270px" valign="top">
									<p style="font-weight: bold;"> Subject: </p>
								</td>
								<td align="left" width="370px" valign="top">
									<p> ' . $meeting_subject . ' </p>
								</td>
							</tr>
							<tr>
								<td align="left" width="270px" valign="top">
									<p style="font-weight: bold;"> Description: </p>
								</td>
								<td align="left" width="370px" valign="top">
									<p> ' . $description . ' </p>
								</td>
							</tr>
						</table>
					</tr>
					<tr>
						<table>
							<tr>
								<p style="color:gray">NOTE: This is an auto-generated email. Please do not reply to this email. If you have any questions, contact class teacher or school for details.</p>
							</tr>
							<tr>
								<p style="color:gray">If you do not wish to receive such emails in future, Please update Instant Email preference in your Noblepeer profile to unsubscribe.</p>
							</tr>
						</table>
					</tr>
				</table>
			</div>
		</body>
	</html>';
	
	$result['message'] = $message;
	$result['subject'] = $subject;
	$result['to'] = $to;
	
	//echo $message, $subject, $to;
	return $result;
}

//LEAVE
function email_leave($student_id, $class_id, $leave_date, $reason) {
	$result = array();
	$conn = new Dbase();
	
	$to = $conn->get_teacher_email($class_id);
	
	$teacherName = $conn->get_teachername($class_id);
	$students = array($student_id);
	$studentName = $conn->get_student_names($students);
	$subject = "New leave requested by parents of ". $studentName;
	
	$message = '
	<html>
		<head>
			<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
			<meta name="format-detection" content="telephone=no"> 
			<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=no;">
			<meta http-equiv="X-UA-Compatible" content="IE=9; IE=8; IE=7; IE=EDGE" />
		</head>
		<body>
			<div>
				<table cellpadding="0" cellspacing="0" border="0" align="center" valign="top" style="font-size: 1.5em; margin-top: 1em; margin-bottom: 2em; width: 100%; max-width: 640px;">
					<tr>
						<table>
							<tr align="center" bgcolor="#E9E9E9">
								<img src="http://demo.noblepeer.com/images/Noblepeer1.png" alt="Noblepeer" style="width: 100%; max-width: 400px;"/>
							</tr>
							<tr>
								<h3>New Leave requested by parents of ' . $studentName . '. Below are the details- </h3>
							</tr>
						</table>
					</tr>
					<tr>
						<table border="1">
							<tr>
								<td align="left" width="270px" valign="top">
									<p style="font-weight: bold;"> Requested on: </p>
								</td>
								<td align="left" width="370px" valign="top">
									<p> ' . date("Y-m-d") . '</p>
								</td>
							</tr>
							<tr>
								<td align="left" width="270px" valign="top">
									<p style="font-weight: bold;"> Leave Date: </p>
								</td>
								<td align="left" width="370px" valign="top">
									<p> ' . $leave_date . ' </p>
								</td>
							</tr>
							<tr>
								<td align="left" width="270px" valign="top">
									<p style="font-weight: bold;"> Reason: </p>
								</td>
								<td align="left" width="370px" valign="top">
									<p> ' . $reason . ' </p>
								</td>
							</tr>
						</table>
					</tr>
					<tr>
						<table>
							<tr>
								<p style="color:gray">NOTE: This is an auto-generated email. Please do not reply to this email. If you have any questions, contact class teacher or school for details.</p>
							</tr>
							<tr>
								<p style="color:gray">If you do not wish to receive such emails in future, Please update Instant Email preference in your Noblepeer profile to unsubscribe.</p>
							</tr>
						</table>
					</tr>
				</table>
			</div>
		</body>
	</html>';
	
	$result['message'] = $message;
	$result['subject'] = $subject;
	$result['to'] = $to;
	
	//echo $message, $subject, $to;
	return $result;
}

//GRADE
function email_grade($class_id, $subject_id, $exam_id) {
	$result = array();
	$conn = new Dbase();
	
	$subject = "New Grade posted";
	$to = $conn->get_class_parent_email($class_id);
	
	$teacherName = $conn->get_teachername($class_id);
	$examName = $conn->get_examName($exam_id);
	$subjectName = $conn->get_subjectName($subject_id);
	
	$message = '
	<html>
		<head>
			<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
			<meta name="format-detection" content="telephone=no"> 
			<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=no;">
			<meta http-equiv="X-UA-Compatible" content="IE=9; IE=8; IE=7; IE=EDGE" />
		</head>
		<body>
			<div>
				<table cellpadding="0" cellspacing="0" border="0" align="center" valign="top" style="font-size: 1.5em; margin-top: 1em; margin-bottom: 2em; width: 100%; max-width: 640px;">
					<tr>
						<table>
							<tr align="center" bgcolor="#E9E9E9">
								<img src="http://demo.noblepeer.com/images/Noblepeer1.png" alt="Noblepeer" style="width: 100%; max-width: 400px;"/>
							</tr>
							<tr>
								<h3>New grades are posted by ' . $teacherName . '. Visit www.noblepeer.com to check grades. Below are the details of grades- </h3>
							</tr>
						</table>
					</tr>
					<tr>
						<table border="1">
							<tr>
								<td align="left" width="270px" valign="top">
									<p style="font-weight: bold;"> Posted on: </p>
								</td>
								<td align="left" width="370px" valign="top">
									<p> ' . date("Y-m-d") . '</p>
								</td>
							</tr>
							<tr>
								<td align="left" width="270px" valign="top">
									<p style="font-weight: bold;"> Exam: </p>
								</td>
								<td align="left" width="370px" valign="top">
									<p> ' . $examName . ' </p>
								</td>
							</tr>
							<tr>
								<td align="left" width="270px" valign="top">
									<p style="font-weight: bold;"> Subject: </p>
								</td>
								<td align="left" width="370px" valign="top">
									<p> ' . $subjectName . ' </p>
								</td>
							</tr>
						</table>
					</tr>
					<tr>
						<table>
							<tr>
								<p style="color:gray">NOTE: This is an auto-generated email. Please do not reply to this email. If you have any questions, contact class teacher or school for details.</p>
							</tr>
							<tr>
								<p style="color:gray">If you do not wish to receive such emails in future, Please update Instant Email preference in your Noblepeer profile to unsubscribe.</p>
							</tr>
						</table>
					</tr>
				</table>
			</div>
		</body>
	</html>';
	
	$result['message'] = $message;
	$result['subject'] = $subject;
	$result['to'] = $to;
	
	//echo $message . $subject . $to;
	return $result;
}

function email_reset_passwd_link($id, $link) {
	$result = array();
	$conn = new Dbase();
	
	$to = $conn->get_userid_email($id);
	$subject = "Noblepeer: Reset Password link";
	$message = '
	<html>
		<head>
			<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
			<meta name="format-detection" content="telephone=no"> 
			<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=no;">
			<meta http-equiv="X-UA-Compatible" content="IE=9; IE=8; IE=7; IE=EDGE" />
		</head>
		<body>
			<div>
				<table cellpadding="0" cellspacing="0" border="0" align="center" valign="top" style="font-size: 1.5em; margin-top: 1em; margin-bottom: 2em; width: 100%; max-width: 640px;">
					<tr>
						<table>
							<tr align="center" bgcolor="#E9E9E9">
								<img src="http://demo.noblepeer.com/images/Noblepeer1.png" alt="Noblepeer" style="width: 100%; max-width: 400px;"/>
							</tr>
						</table>
					</tr>
					
					<tr>
						<p>Use this link to reset your password- ' . $link . ' </p>
					</tr>
					
					<tr>
						<table>
							<tr>
								<p style="color:gray">NOTE: This is an auto-generated email. Please do not reply to this email. If you have any questions, contact class teacher or school for details.</p>
							</tr>
							<tr>
								<p style="color:gray">If you do not wish to receive such emails in future, Please update Instant Email preference in your Noblepeer profile to unsubscribe.</p>
							</tr>
						</table>
					</tr>
				</table>
			</div>
		</body>
	</html>';
	
	$result['message'] = $message;
	$result['subject'] = $subject;
	$result['to'] = $to;
	
	//echo $message, $subject, $to;
	return $result;
	
	
}

/*
$class_id=1;
$title="Title";
$description="Description";
$email = email_announcement($class_id, $title, $description);
echo $email['message'];

$title="Title";
$description="Description";
$teacher_id = 1;
$students = array(1,2,3,4,5);
$email = email_notice($students, $teacher_id, $title, $description);
echo $email['message'];

$class_id = 1;
$teacher_id = 1;
$subject_id = 1;
$due_date="2016-10-20";
$due_time="11:00 AM";
$topic="Topic";
$description="Description";
$email = email_homework($class_id, $teacher_id, $subject_id, $due_date, $due_time, $topic, $description);
echo $email['message'];

$class_id = 1;
$teacher_id = 1;
$event_date="2016-10-20";
$start_time="12:00 PM";
$end_time="2:00 PM";
$subject = "Subject";
$description= "Description";
$event_location="Ground";
$email = email_event($class_id, $teacher_id, $event_date, $start_time, $end_time, $subject, $description, $event_location);
echo $email['message'];

$students = array(1,2,3,4,5);
$teacher_id = 1;
$meeting_date="2016-10-20";
$start_time="12:00 PM";
$end_time="2:00 PM";
$meeting_subject = "Subject";
$description= "Description";
$meeting_location="Ground";
$email = email_meeting($students, $teacher_id, $meeting_date, $start_time, $end_time, $meeting_subject, $description, $meeting_location);
echo $email['message'];

$class_id = 1;
$subject_id = 4;
$exam_id = 2;
$email = email_grade($class_id, $subject_id, $exam_id);
echo $email['message'];

$class_id= 1;
$student_id = 1;
$parent_id =1;
$meeting_date="2016-10-20";
$start_time="12:00 PM";
$end_time="2:00 PM";
$meeting_subject = "Subject";
$description= "Description";
$location="Ground";
$email = email_parent_meeting($class_id, $student_id, $parent_id, $meeting_date, $start_time, $end_time, $meeting_subject, $description, $location);
echo $email['message'];

$class_id= 1;
$student_id = 1;
$leave_date = "2016-10-20";
$reason = "Not feeeling well.";
$email = email_leave($student_id, $class_id, $leave_date, $reason);
echo $email['message'];
*/
?>