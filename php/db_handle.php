<?php
class Dbase {
	private $host = "localhost";
	private $user = "root";
	private $pass = "";
	private $dbname = "noblepeer";
	private $error;
	
	//Connect to database
	private function connect(){
		$dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname;
		
		$options = array(
            PDO::ATTR_PERSISTENT    => false,
            PDO::ATTR_ERRMODE       => PDO::ERRMODE_EXCEPTION,
			PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"		//Important to get data in proper format with fetchAll
        );
		
		$db = new PDO($dsn, $this->user, $this->pass, $options);
		return $db;
		
		/*
		try{
            $db = new PDO($dsn, $this->user, $this->pass, $options);
			return $db
        } catch(PDOException $e){
            $this->error = $e->getMessage();
        }
		*/
	}
	
	//Disconnect from database
	private function disconnect($db){
		$db = NULL;
	}
	
	public function get_user($username) {
		$query = "SELECT * FROM user WHERE Username = :username";
		$db = $this->connect();
		$stmt = $db->prepare($query);
		$stmt->bindValue(':username', $username, PDO::PARAM_STR);
		$stmt->execute();
		$this->disconnect($db);
		return $stmt;
	}
	
	public function get_username_id($username) {
		$query = "SELECT Id FROM user WHERE Username = :username";
		$db = $this->connect();
		$stmt = $db->prepare($query);
		$stmt->bindValue(':username', $username, PDO::PARAM_STR);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		$this->disconnect($db);
		return $row['Id'];
	}
	
	public function get_userid_email($id) {
		$query = "SELECT Email FROM user WHERE Id = :Id";
		$db = $this->connect();
		$stmt = $db->prepare($query);
		$stmt->bindValue(':Id', $id, PDO::PARAM_INT);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		$this->disconnect($db);
		return $row['Email'];
	}
	
	//Get student_id from parent_id
	public function get_parent_student($parent_id) {
		$query = "SELECT StudentId FROM parent WHERE Id = :ParentId";
		$db = $this->connect();
		$stmt = $db->prepare($query);
		$stmt->bindValue(':ParentId', $parent_id, PDO::PARAM_INT);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		$this->disconnect($db);
		return $row['StudentId'];
	}
	
	//Get class_id from student_id
	public function get_student_class($student_id) {
		$query = "SELECT ClassId FROM student WHERE Id = :StudentId";
		$db = $this->connect();
		$stmt = $db->prepare($query);
		$stmt->bindValue(':StudentId', $student_id, PDO::PARAM_INT);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		$this->disconnect($db);
		return $row['ClassId'];
	}
	
	//Get school_id from admin_id
	public function get_admin_school($admin_id) {
		$query = "SELECT SchoolId FROM admin WHERE Id = :AdminId";
		$db = $this->connect();
		$stmt = $db->prepare($query);
		$stmt->bindValue(':AdminId', $admin_id, PDO::PARAM_INT);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		$this->disconnect($db);
		return $row['SchoolId'];
	}
	
	//Get class_id from teacher_id
	public function get_teacher_class($teacher_id) {
		$query = "SELECT ClassId FROM teacherclassmap WHERE TeacherId = :TeacherId";
		$db = $this->connect();
		$stmt = $db->prepare($query);
		$stmt->bindValue(':TeacherId', $teacher_id, PDO::PARAM_INT);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		$this->disconnect($db);
		return $row['ClassId'];
	}
	
	//Get teacher_id from class_id
	public function get_class_teacher($class_id) {
		$query = "SELECT TeacherId FROM teacherclassmap WHERE ClassId = :ClassId";
		$db = $this->connect();
		$stmt = $db->prepare($query);
		$stmt->bindValue(':ClassId', $class_id, PDO::PARAM_INT);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		$this->disconnect($db);
		return $row['TeacherId'];
	}
	
	//Get classname from class_id
	public function get_classname($class_id) {
		$query = "SELECT Name FROM classroom INNER JOIN class ON class.ClassroomId = classroom.Id WHERE class.Id = :ClassId";
		$db = $this->connect();
		$stmt = $db->prepare($query);
		$stmt->bindValue(':ClassId', $class_id, PDO::PARAM_INT);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		$this->disconnect($db);
		return $row['Name'];
	}
	
	//Get teacherName from class_id
	public function get_teachername($class_id){
		$query = "SELECT CONCAT(FirstName, ' ', LastName) AS TeacherName FROM teacher RIGHT JOIN teacherclassmap ON teacherclassmap.TeacherId = teacher.Id WHERE ClassId = :ClassId";
		$db = $this->connect();
		$stmt = $db->prepare($query);
		$stmt->bindValue(':ClassId', $class_id, PDO::PARAM_INT);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		$this->disconnect($db);
		return $row['TeacherName'];
	}
	
	public function get_class_subject($class_id) {
		$query = "SELECT subject.Id, subject.Name FROM (SELECT SubjectId FROM class_subteach_map WHERE ClassId = :ClassId)csm  LEFT JOIN subject ON csm.SubjectId = subject.Id ORDER BY subject.Name";
		$db = $this->connect();
		$stmt = $db->prepare($query);
		$stmt->bindValue(':ClassId', $class_id, PDO::PARAM_INT);
		$stmt->execute();
		$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$json = json_encode( $rows );
		$this->disconnect($db);
		return $json;
	}
	
	public function get_student($class_id) {
		$query = "SELECT Id, CAST(RollNumber AS UNSIGNED) AS RollNumber, FirstName, MiddleName, LastName, Address, DateOfBirth, Gender FROM student WHERE ClassId=:ClassId ORDER BY RollNumber ASC";
		$db = $this->connect();
		$stmt = $db->prepare($query);
		$stmt->bindValue(':ClassId', $class_id, PDO::PARAM_INT);
		$stmt->execute();
		$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$json = json_encode( $rows );
		$this->disconnect($db);
		return $json;
	}
	
	public function get_name($id, $table) {
		$query = "SELECT FirstName, MiddleName, LastName FROM $table WHERE Id=:Id";
		$db = $this->connect();
		$stmt = $db->prepare($query);
		$stmt->bindValue(':Id', $id, PDO::PARAM_INT);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		$this->disconnect($db);
		return $row;
	}
	
	public function get_exam($class_id) {
		$query = "SELECT Id, Name FROM exam INNER JOIN (SELECT DISTINCT ExamId FROM examsubjectmap INNER JOIN (SELECT Id FROM class_subteach_map WHERE ClassId = :ClassId) csmap ON examsubjectmap.ClassSubTeachMapId = csmap.Id) exm ON exm.ExamId = exam.Id ORDER BY Name";
		$db = $this->connect();
		$stmt = $db->prepare($query);
		$stmt->bindValue(':ClassId', $class_id, PDO::PARAM_INT);
		$stmt->execute();
		$row = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$json = json_encode( $row );
		$this->disconnect($db);
		return $json;
	}
	
	private function get_examsubjectmapId($dbh, $class_id, $subject_id, $exam_id) {
		$db = $dbh;
		$query = "SELECT examsubjectmap.Id FROM examsubjectmap RIGHT JOIN  (SELECT Id FROM class_subteach_map WHERE ClassId = :ClassId AND SubjectId = :SubjectId) csmap ON examsubjectmap.ClassSubTeachMapId = csmap.Id AND examsubjectmap.ExamId = :ExamId";
		$stmt = $db->prepare($query);
		$stmt->bindValue(':ClassId', $class_id, PDO::PARAM_INT);
		$stmt->bindValue(':ExamId', $exam_id, PDO::PARAM_INT);
		$stmt->bindValue(':SubjectId', $subject_id, PDO::PARAM_INT);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		return $row['Id'];
	}
	
	private function get_ClassSubTeachMapId($dbh, $class_id, $teacher_id, $subject_id) {
		$db = $dbh;
		$query = "SELECT Id FROM class_subteach_map WHERE ClassId = :ClassId AND TeacherId = :TeacherId AND SubjectId = :SubjectId";
		$stmt = $db->prepare($query);
		$stmt->bindValue(':ClassId', $class_id, PDO::PARAM_INT);
		$stmt->bindValue(':TeacherId', $teacher_id, PDO::PARAM_INT);
		$stmt->bindValue(':SubjectId', $subject_id, PDO::PARAM_INT);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		return $row['Id'];
	}
	
	public function get_class_school($class_id) {
		$query = "SELECT SchoolId FROM class WHERE Id = :Id";
		$db = $this->connect();
		$stmt = $db->prepare($query);
		$stmt->bindValue(':Id', $class_id, PDO::PARAM_INT);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		$this->disconnect($db);
		return $row['SchoolId'];
	}
	
	private function get_student_school($student_id) {
		$query = "SELECT SchoolId FROM class RIGHT JOIN student on student.ClassId = class.Id WHERE student.Id = :Id";
		$db = $this->connect();
		$stmt = $db->prepare($query);
		$stmt->bindValue(':Id', $student_id, PDO::PARAM_INT);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		$this->disconnect($db);
		return $row['SchoolId'];
	}
	
	//ANNOUNCEMENT
	public function post_announcement($class_id, $teacher_id, $title, $description) {
		$query = "INSERT INTO announcement (ClassId, TeacherId, Title, Description, CreatedOn) VALUES (:ClassId, :TeacherId, :Title, :Description, :CreatedOn)";
		$date=date('Y-m-d H:i:s');
		
		try {
			$db = $this->connect();
			$stmt = $db->prepare($query);
			$stmt->bindValue(':ClassId', $class_id, PDO::PARAM_INT);
			$stmt->bindValue(':TeacherId', $teacher_id, PDO::PARAM_INT);
			$stmt->bindValue(':Title', $title, PDO::PARAM_STR);
			$stmt->bindValue(':Description', $description, PDO::PARAM_STR);
			$stmt->bindValue(':CreatedOn', $date, PDO::PARAM_STR);
			$result = $stmt->execute();
			$this->disconnect($db);
			return $result;
		} catch (PDOEXCEPTION $e) {
			$this->disconnect($db);
			return  $e->getMessage();
		}
	}
	
	public function post_announcement_with_file($class_id, $teacher_id, $title, $description, $file) {
		$query = "INSERT INTO announcement (ClassId, TeacherId, Title, Description, CreatedOn, FileName, DbFileName) VALUES (:ClassId, :TeacherId, :Title, :Description, :CreatedOn, :FileName, :DbFileName)";
		$date=date('Y-m-d H:i:s');
		$file_ext = pathinfo($file['name'], PATHINFO_EXTENSION);
		$micro_time = round(microtime(true));
		$random_number = rand(0, 9999);
		$new_name = $micro_time . $random_number . '.' . $file_ext;
		
		try {
			$db = $this->connect();
			$stmt = $db->prepare($query);
			$stmt->bindValue(':ClassId', $class_id, PDO::PARAM_INT);
			$stmt->bindValue(':TeacherId', $teacher_id, PDO::PARAM_INT);
			$stmt->bindValue(':Title', $title, PDO::PARAM_STR);
			$stmt->bindValue(':Description', $description, PDO::PARAM_STR);
			$stmt->bindValue(':CreatedOn', $date, PDO::PARAM_STR);
			$stmt->bindValue(':FileName', $file['name'], PDO::PARAM_STR);
			$stmt->bindValue(':DbFileName', $new_name, PDO::PARAM_STR);
			$result = $stmt->execute();
			move_uploaded_file($file["tmp_name"], "../files/announcement/" . $new_name);
			$this->disconnect($db);
			return $result;
		} catch (PDOEXCEPTION $e) {
			$this->disconnect($db);
			return  $e->getMessage();
		}
	}
	
	public function get_announcement($class_id) {
		$query = "SELECT ann.Title, ann.Description, ann.CreatedOn, ann.FileName, ann.DbFileName, teacher.FirstName, teacher.LastName FROM (SELECT TeacherId, Title, Description, CreatedOn, FileName, DbFileName FROM announcement WHERE ClassId = :ClassId)ann LEFT JOIN teacher ON ann.TeacherId = teacher.Id ORDER BY CreatedOn DESC";
		//$query = "SELECT * FROM announcement WHERE ClassId=:ClassId order by CreatedOn desc";
		$db = $this->connect();
		$stmt = $db->prepare($query);
		$stmt->bindValue(':ClassId', $class_id, PDO::PARAM_INT);
		$stmt->execute();
		$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$json = json_encode( $rows );
		$this->disconnect($db);
		return $json;
	}
	
	//NOTICE
	public function post_notice($students, $teacher_id, $title, $description) {
		$query = "INSERT INTO notice (StudentId, TeacherId, Title, Description, CreatedOn) VALUES (:StudentId, :TeacherId, :Title, :Description, :CreatedOn)";
		$date=date('Y-m-d H:i:s');
		
		try{
			$db = $this->connect();
			$db->beginTransaction(); 
			$stmt = $db->prepare($query);

			foreach ($students as $student_id) {
				$stmt->bindValue(':StudentId', $student_id, PDO::PARAM_INT);
				$stmt->bindValue(':TeacherId', $teacher_id, PDO::PARAM_INT);
				$stmt->bindValue(':Title', $title, PDO::PARAM_STR);
				$stmt->bindValue(':Description', $description, PDO::PARAM_STR);
				$stmt->bindValue(':CreatedOn', $date, PDO::PARAM_STR);
				$stmt->execute();
			}
			$result = $db->commit();
			$this->disconnect($db);
			return $result;
		}
		catch(PDOException $e) {
			$db->rollback();
			$this->disconnect($db);
			return  $e->getMessage();
		}
	}
	
	public function post_notice_with_file($students, $teacher_id, $title, $description, $file) {
		$query = "INSERT INTO notice (StudentId, TeacherId, Title, Description, CreatedOn, FileName, DbFileName) VALUES (:StudentId, :TeacherId, :Title, :Description, :CreatedOn, :FileName, :DbFileName)";
		$date=date('Y-m-d H:i:s');
		
		$file_name = $file['name'];
		$file_ext = pathinfo($file['name'], PATHINFO_EXTENSION);
		$micro_time = round(microtime(true));
		$random_number = rand(0, 9999);
		$new_name = $micro_time . $random_number . '.' . $file_ext;
		
		try{
			$db = $this->connect();
			$db->beginTransaction(); 
			$stmt = $db->prepare($query);

			foreach ($students as $student_id) {
				$stmt->bindValue(':StudentId', $student_id, PDO::PARAM_INT);
				$stmt->bindValue(':TeacherId', $teacher_id, PDO::PARAM_INT);
				$stmt->bindValue(':Title', $title, PDO::PARAM_STR);
				$stmt->bindValue(':Description', $description, PDO::PARAM_STR);
				$stmt->bindValue(':CreatedOn', $date, PDO::PARAM_STR);
				$stmt->bindValue(':FileName', $file_name, PDO::PARAM_STR);
				$stmt->bindValue(':DbFileName', $new_name, PDO::PARAM_STR);
				$stmt->execute();
			}
			$result = $db->commit();
			move_uploaded_file($file["tmp_name"], "../files/notice/" . $new_name);
			$this->disconnect($db);
			return $result;
		}
		catch(PDOException $e) {
			$db->rollback();
			$this->disconnect($db);
			return  $e->getMessage();
		}
	}

	public function get_teacher_notice($teacher_id) {
		$query = "SELECT Title, Description, CreatedOn, FileName, DbFileName, CONCAT(teacher.FirstName, ' ', teacher.LastName) AS TeacherName, GROUP_CONCAT(DISTINCT CONCAT(' ', student.FirstName, ' ', student.LastName)) AS StudentName FROM notice LEFT JOIN teacher ON notice.TeacherId = teacher.Id LEFT JOIN student on notice.StudentId = student.Id WHERE TeacherId = :TeacherId  GROUP BY CreatedOn ORDER BY CreatedOn DESC";
		$db = $this->connect();
		$stmt = $db->prepare($query);
		$stmt->bindValue(':TeacherId', $teacher_id, PDO::PARAM_INT);
		$stmt->execute();
		$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$json = json_encode( $rows );
		$this->disconnect($db);
		return $json;
	}
	
	public function get_parent_notice($student_id) {
		$query = "SELECT Title, Description, CreatedOn, FileName, DbFileName, CONCAT(teacher.FirstName, ' ', teacher.LastName) AS TeacherName, CONCAT(student.FirstName, ' ', student.LastName) AS StudentName FROM notice LEFT JOIN teacher ON notice.TeacherId = teacher.Id LEFT JOIN student on notice.StudentId = student.Id WHERE StudentId = :StudentId  GROUP BY CreatedOn ORDER BY CreatedOn DESC";
		$db = $this->connect();
		$stmt = $db->prepare($query);
		$stmt->bindValue(':StudentId', $student_id, PDO::PARAM_INT);
		$stmt->execute();
		$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$json = json_encode( $rows );
		$this->disconnect($db);
		return $json;
	}
	
	//LEAVE
	public function post_leave($student_id, $class_id, $leave_date, $reason) {
		$query = "INSERT INTO leaverequest (StudentId, ClassId, LeaveDate, Reason, CreatedOn) VALUES (:StudentId, :ClassId, :LeaveDate, :Reason, :CreatedOn)";
		$date=date('Y-m-d H:i:s');
		
		try {
			$db = $this->connect();
			$stmt = $db->prepare($query);
			$stmt->bindValue(':StudentId', $student_id, PDO::PARAM_INT);
			$stmt->bindValue(':ClassId', $class_id, PDO::PARAM_INT);
			$stmt->bindValue(':LeaveDate', $leave_date, PDO::PARAM_STR);
			$stmt->bindValue(':Reason', $reason, PDO::PARAM_STR);
			$stmt->bindValue(':CreatedOn', $date, PDO::PARAM_STR);
			$result = $stmt->execute();
			$this->disconnect($db);
			return $result;
		} catch (PDOEXCEPTION $e) {
			$this->disconnect($db);
			return  $e->getMessage();
		}
	}
	
	public function get_student_leave($student_id) {
		$query = "SELECT Id, LeaveDate, LeaveType, Reason, Status, CreatedOn FROM leaverequest WHERE StudentId= :StudentId ORDER BY LeaveDate DESC";
		$db = $this->connect();
		$stmt = $db->prepare($query);
		$stmt->bindValue(':StudentId', $student_id, PDO::PARAM_INT);
		$stmt->execute();
		$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$json = json_encode( $rows );
		$this->disconnect($db);
		return $json;
	}
	
	public function get_teacher_past_leave($class_id) {
		$query = "SELECT lve.LeaveDate, lve.LeaveType, lve.Reason, lve.Status, lve.CreatedOn, student.FirstName, student.LastName FROM (SELECT StudentId, LeaveDate, LeaveType, Reason, Status, CreatedOn FROM leaverequest WHERE ClassId = :ClassId AND LeaveDate<CURDATE())lve LEFT JOIN student ON StudentId = student.Id ORDER BY LeaveDate DESC, LastName DESC";
		$db = $this->connect();
		$stmt = $db->prepare($query);
		$stmt->bindValue(':ClassId', $class_id, PDO::PARAM_INT);
		$stmt->execute();
		$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$json = json_encode( $rows );
		$this->disconnect($db);
		return $json;
	}
	
	public function get_teacher_upcoming_leave($class_id) {
		$query = "SELECT lve.LeaveDate, lve.LeaveType, lve.Reason, lve.Status, lve.CreatedOn, student.FirstName, student.LastName FROM (SELECT StudentId, LeaveDate, LeaveType, Reason, Status, CreatedOn FROM leaverequest WHERE ClassId = :ClassId AND LeaveDate>=CURDATE())lve LEFT JOIN student ON StudentId = student.Id ORDER BY LeaveDate ASC, LastName DESC";
		$db = $this->connect();
		$stmt = $db->prepare($query);
		$stmt->bindValue(':ClassId', $class_id, PDO::PARAM_INT);
		$stmt->execute();
		$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$json = json_encode( $rows );
		$this->disconnect($db);
		return $json;
	}
	
	//HOMEWORK
	public function post_homework($class_id, $teacher_id, $subject_id, $due_date, $due_time, $topic, $description) {
		$query = "INSERT INTO homework ( ClassId, ClassSubTeachMapId, DueDate, DueTime, Topic, Description, CreatedOn ) VALUES ( :ClassId, :ClassSubTeachMapId, :DueDate, :DueTime, :Topic, :Description, :CreatedOn )";
		$date=date('Y-m-d H:i:s');
		
		try {
			$db = $this->connect();
			$class_subteach_map_id = $this->get_ClassSubTeachMapId($db, $class_id, $teacher_id, $subject_id);
			$stmt = $db->prepare($query);
			$stmt->bindValue(':ClassId', $class_id, PDO::PARAM_INT);
			$stmt->bindValue(':ClassSubTeachMapId', $class_subteach_map_id, PDO::PARAM_INT);
			$stmt->bindValue(':DueDate', $due_date, PDO::PARAM_STR);
			$stmt->bindValue(':DueTime', $due_time, PDO::PARAM_STR);
			$stmt->bindValue(':Topic', $topic, PDO::PARAM_STR);
			$stmt->bindValue(':Description', $description, PDO::PARAM_STR);
			$stmt->bindValue(':CreatedOn', $date, PDO::PARAM_STR);
			$result = $stmt->execute();
			$this->disconnect($db);
			return $result;
		} catch (PDOEXCEPTION $e) {
			$this->disconnect($db);
			return  $e->getMessage();
		}
	}
	
	public function post_homework_with_file($class_id, $teacher_id, $subject_id, $due_date, $due_time, $topic, $description, $file) {
		$query = "INSERT INTO homework ( ClassId, ClassSubTeachMapId, DueDate, DueTime, Topic, Description, CreatedOn, FileName, DbFileName) VALUES ( :ClassId, :ClassSubTeachMapId, :DueDate, :DueTime, :Topic, :Description, :CreatedOn, :FileName, :DbFileName)";
		$date=date('Y-m-d H:i:s');
		
		$file_name = $file['name'];
		$file_ext = pathinfo($file['name'], PATHINFO_EXTENSION);
		$micro_time = round(microtime(true));
		$random_number = rand(0, 9999);
		$new_name = $micro_time . $random_number . '.' . $file_ext;
		
		try {
			$db = $this->connect();
			$class_subteach_map_id = $this->get_ClassSubTeachMapId($db, $class_id, $teacher_id, $subject_id);
			$stmt = $db->prepare($query);
			$stmt->bindValue(':ClassId', $class_id, PDO::PARAM_INT);
			$stmt->bindValue(':ClassSubTeachMapId', $class_subteach_map_id, PDO::PARAM_INT);
			$stmt->bindValue(':DueDate', $due_date, PDO::PARAM_STR);
			$stmt->bindValue(':DueTime', $due_time, PDO::PARAM_STR);
			$stmt->bindValue(':Topic', $topic, PDO::PARAM_STR);
			$stmt->bindValue(':Description', $description, PDO::PARAM_STR);
			$stmt->bindValue(':CreatedOn', $date, PDO::PARAM_STR);
			$stmt->bindValue(':FileName', $file_name, PDO::PARAM_STR);
			$stmt->bindValue(':DbFileName', $new_name, PDO::PARAM_STR);
			$result = $stmt->execute();
			move_uploaded_file($file["tmp_name"], "../files/homework/" . $new_name);
			$this->disconnect($db);
			return $result;
		} catch (PDOEXCEPTION $e) {
			$this->disconnect($db);
			return  $e->getMessage();
		}
	}
	
	public function get_past_homework($class_id) {
		$query = "SELECT hws.DueDate, hws.DueTime, hws.Topic, hws.Description, hws.CreatedOn, hws.FileName, hws.DbFileName, subject.Name AS Subject FROM (SELECT hw.DueDate, hw.DueTime, hw.Topic, hw.Description, hw.CreatedOn, hw.FileName, hw.DbFileName, class_subteach_map.SubjectId FROM (SELECT ClassSubTeachMapId, DueDate, DueTime, Topic, Description, CreatedOn, FileName, DbFileName FROM `homework` WHERE ClassId = :ClassId AND DueDate<CURDATE())hw LEFT JOIN class_subteach_map ON hw.ClassSubTeachMapId = class_subteach_map.Id)hws LEFT JOIN subject ON hws.SubjectId = subject.Id ORDER BY DueDate DESC";
		$db = $this->connect();
		$stmt = $db->prepare($query);
		$stmt->bindValue(':ClassId', $class_id, PDO::PARAM_INT);
		$stmt->execute();
		$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$json = json_encode( $rows );
		$this->disconnect($db);
		return $json;
	}
	
	public function get_due_homework($class_id) {
		$query = "SELECT hws.DueDate, hws.DueTime, hws.Topic, hws.Description, hws.CreatedOn, hws.FileName, hws.DbFileName, subject.Name AS Subject FROM (SELECT hw.DueDate, hw.DueTime, hw.Topic, hw.Description, hw.CreatedOn, hw.FileName, hw.DbFileName, class_subteach_map.SubjectId FROM (SELECT ClassSubTeachMapId, DueDate, DueTime, Topic, Description, CreatedOn, FileName, DbFileName FROM `homework` WHERE ClassId = :ClassId AND DueDate>=	CURDATE())hw LEFT JOIN class_subteach_map ON hw.ClassSubTeachMapId = class_subteach_map.Id)hws LEFT JOIN subject ON hws.SubjectId = subject.Id ORDER BY DueDate ASC";
		$db = $this->connect();
		$stmt = $db->prepare($query);
		$stmt->bindValue(':ClassId', $class_id, PDO::PARAM_INT);
		$stmt->execute();
		$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$json = json_encode( $rows );
		$this->disconnect($db);
		return $json;
	}
	
	//EVENT
	public function post_event($class_id, $teacher_id, $event_date, $start_time, $end_time, $subject, $description, $location) {
		$query = "INSERT INTO event (ClassId, TeacherId, EventDate, StartTime, FinishTime, Subject, Description, Location, CreatedOn) VALUES (:ClassId, :TeacherId, :EventDate, :StartTime, :FinishTime, :Subject, :Description, :Location, :CreatedOn)";
		$date=date('Y-m-d H:i:s');
		
		try {
			$db = $this->connect();
			$stmt = $db->prepare($query);
			$stmt->bindValue(':ClassId', $class_id, PDO::PARAM_INT);
			$stmt->bindValue(':TeacherId', $teacher_id, PDO::PARAM_INT);
			$stmt->bindValue(':EventDate', $event_date, PDO::PARAM_STR);
			$stmt->bindValue(':StartTime', $start_time, PDO::PARAM_STR);
			$stmt->bindValue(':FinishTime', $end_time, PDO::PARAM_STR);
			$stmt->bindValue(':Subject', $subject, PDO::PARAM_STR);
			$stmt->bindValue(':Description', $description, PDO::PARAM_STR);
			$stmt->bindValue(':Location', $location, PDO::PARAM_STR);
			$stmt->bindValue(':CreatedOn', $date, PDO::PARAM_STR);
			$result = $stmt->execute();
			$this->disconnect($db);
			return $result;
		} catch (PDOEXCEPTION $e) {
			return  $e->getMessage();
		}
	}
	
	public function get_event($class_id) {
		$query = "SELECT Id, EventDate, StartTime, FinishTime, Subject, Description, CreatedOn, Location, Status FROM event WHERE ClassId = :ClassId ORDER BY EventDate DESC";
		$db = $this->connect();
		$stmt = $db->prepare($query);
		$stmt->bindValue(':ClassId', $class_id, PDO::PARAM_INT);
		$stmt->execute();
		$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$json = json_encode( $rows );
		$this->disconnect($db);
		return $json;
	}
	
	//MEETING
	public function post_meeting($user_id, $students, $teacher_id, $meeting_date, $start_time, $end_time, $subject, $description, $location) {
		$query = "INSERT INTO individualmeeting (StudentId, TeacherId, PostedBy, MeetingDate, StartTime, FinishTime, Subject, Description, Location, CreatedOn) VALUES (:StudentId, :TeacherId, :PostedBy, :MeetingDate, :StartTime, :FinishTime, :Subject, :Description, :Location, :CreatedOn)";
		$date=date('Y-m-d H:i:s');
		
		try {
			$db = $this->connect();
			$db->beginTransaction(); 
			$stmt = $db->prepare($query);
			
			foreach ($students as $student_id) {
				$stmt->bindValue(':StudentId', $student_id, PDO::PARAM_INT);
				$stmt->bindValue(':TeacherId', $teacher_id, PDO::PARAM_INT);
				$stmt->bindValue(':PostedBy', $user_id, PDO::PARAM_INT);
				$stmt->bindValue(':MeetingDate', $meeting_date, PDO::PARAM_STR);
				$stmt->bindValue(':StartTime', $start_time, PDO::PARAM_STR);
				$stmt->bindValue(':FinishTime', $end_time, PDO::PARAM_STR);
				$stmt->bindValue(':Subject', $subject, PDO::PARAM_STR);
				$stmt->bindValue(':Description', $description, PDO::PARAM_STR);
				$stmt->bindValue(':Location', $location, PDO::PARAM_STR);
				$stmt->bindValue(':CreatedOn', $date, PDO::PARAM_STR);
				
				$stmt->execute();
			}
			$result = $db->commit();
			$this->disconnect($db);
			return $result;
		}
		catch(PDOException $e) {
			$db->rollback();
			$this->disconnect($db);
			return  $e->getMessage();
		}

		/*
		$is_success = 1;
		$db = $this->connect();
		$stmt = $db->prepare($query);
		
		foreach ($students as $student_id) {
			$stmt->bindValue(':StudentId', $student_id, PDO::PARAM_INT);
			$stmt->bindValue(':TeacherId', $teacher_id, PDO::PARAM_INT);
			$stmt->bindValue(':PostedBy', $user_id, PDO::PARAM_INT);
			$stmt->bindValue(':MeetingDate', $meeting_date, PDO::PARAM_STR);
			$stmt->bindValue(':StartTime', $start_time, PDO::PARAM_STR);
			$stmt->bindValue(':FinishTime', $end_time, PDO::PARAM_STR);
			$stmt->bindValue(':Subject', $subject, PDO::PARAM_STR);
			$stmt->bindValue(':Description', $description, PDO::PARAM_STR);
			$stmt->bindValue(':Location', $location, PDO::PARAM_STR);
			$stmt->bindValue(':CreatedOn', $date, PDO::PARAM_STR);
			if( $stmt->execute() ) {
				continue;
			} else {
				$is_success = 0;
				break;
			}
		}
		$this->disconnect($db);
		return $is_success;
		*/
	}
	
	public function post_parent_meeting($user_id, $class_id, $student_id, $parent_id, $meeting_date, $start_time, $end_time, $subject, $description, $location) {
		$query = "INSERT INTO individualmeeting (StudentId, TeacherId, PostedBy, MeetingDate, StartTime, FinishTime, Subject, Description, Location, CreatedOn) VALUES (:StudentId, :TeacherId, :PostedBy, :MeetingDate, :StartTime, :FinishTime, :Subject, :Description, :Location, :CreatedOn)";
		$date=date('Y-m-d H:i:s');
		
		try {
			$db = $this->connect();
			$teacher_id = $this->get_class_teacher($class_id);
			$stmt = $db->prepare($query);
			$stmt->bindValue(':StudentId', $student_id, PDO::PARAM_INT);
			$stmt->bindValue(':TeacherId', $teacher_id, PDO::PARAM_INT);
			$stmt->bindValue(':PostedBy', $user_id, PDO::PARAM_INT);
			$stmt->bindValue(':MeetingDate', $meeting_date, PDO::PARAM_STR);
			$stmt->bindValue(':StartTime', $start_time, PDO::PARAM_STR);
			$stmt->bindValue(':FinishTime', $end_time, PDO::PARAM_STR);
			$stmt->bindValue(':Subject', $subject, PDO::PARAM_STR);
			$stmt->bindValue(':Description', $description, PDO::PARAM_STR);
			$stmt->bindValue(':Location', $location, PDO::PARAM_STR);
			$stmt->bindValue(':CreatedOn', $date, PDO::PARAM_STR);
			$result = $stmt->execute();
			$this->disconnect($db);
			return $result;
		} catch (PDOEXCEPTION $e) {
			return  $e->getMessage();
		}
	}

	public function get_teacher_meeting($teacher_id) {
		$query = "SELECT GROUP_CONCAT(mtg.Id) AS Id, mtg.MeetingDate, StartTime, FinishTime, mtg.Subject, mtg.Description, mtg.Status, mtg.CreatedOn, mtg.Location, GROUP_CONCAT(DISTINCT CONCAT(' ', student.FirstName, ' ', student.LastName)) AS StudentName FROM (SELECT Id, StudentId, MeetingDate, StartTime, FinishTime, Subject, Description, Status, CreatedOn, Location FROM individualmeeting WHERE TeacherId = :TeacherId) mtg LEFT JOIN student on mtg.StudentId = student.Id GROUP BY CreatedOn, Status ORDER BY MeetingDate DESC";
		//$query = "SELECT MeetingDate, StartTime, FinishTime, Subject, Description, Status, CreatedOn, Location, GROUP_CONCAT(DISTINCT CONCAT(' ', student.FirstName, ' ', student.LastName)) AS StudentName FROM individualmeeting LEFT JOIN student on individualmeeting.StudentId = student.Id WHERE TeacherId = :TeacherId GROUP BY CreatedOn ORDER BY MeetingDate DESC";
		$db = $this->connect();
		$stmt = $db->prepare($query);
		$stmt->bindValue(':TeacherId', $teacher_id, PDO::PARAM_INT);
		$stmt->execute();
		$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$json = json_encode( $rows );
		$this->disconnect($db);
		return $json;
	}
	
	public function get_parent_meeting($student_id) {
		$query = "SELECT mtg.Id, mtg.MeetingDate, StartTime, FinishTime, mtg.Subject, mtg.Description, mtg.Status, mtg.CreatedOn, mtg.Location, student.FirstName, student.LastName FROM (SELECT Id, StudentId, MeetingDate, StartTime, FinishTime, Subject, Description, Status, CreatedOn, Location FROM individualmeeting WHERE StudentId = :StudentId) mtg LEFT JOIN student on mtg.StudentId = student.Id ORDER BY MeetingDate DESC";
		$db = $this->connect();
		$stmt = $db->prepare($query);
		$stmt->bindValue(':StudentId', $student_id, PDO::PARAM_INT);
		$stmt->execute();
		$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$json = json_encode( $rows );
		$this->disconnect($db);
		return $json;
	}
	
	//GRADE
	public function post_grade($class_id, $subject_id, $exam_id, $total_marks, $students, $marks, $remarks) {
		$query= "INSERT INTO grade (StudentId, ExamSubjectMapId, MarksObtained, MarksTotal, PassFailRemark) VALUES (:StudentId, :ExamSubjectMapId, :MarksObtained, :MarksTotal, :PassFailRemark);";
		$len = count($students);
		
		try{
			$db = $this->connect();
			$examsubjectmap_id = $this->get_examsubjectmapId($db, $class_id, $subject_id, $exam_id);
			$db->beginTransaction(); 
			$stmt = $db->prepare($query);
			
			for ($x = 0; $x < $len; $x++) {
				$student_id = $students[$x];
				$marks_obtained = $marks[$x];
				$remark = $remarks[$x];
				
				$stmt->bindValue(':StudentId', $student_id, PDO::PARAM_INT);
				$stmt->bindValue(':ExamSubjectMapId', $examsubjectmap_id, PDO::PARAM_INT);
				$stmt->bindValue(':MarksObtained', $marks_obtained, PDO::PARAM_STR);
				$stmt->bindValue(':MarksTotal', $total_marks, PDO::PARAM_STR);
				$stmt->bindValue(':PassFailRemark', $remark, PDO::PARAM_STR);
				$stmt->execute();
			}
			$result = $db->commit();
			$this->disconnect($db);
			return $result;
		}
		catch(PDOException $e) {
			$db->rollback();
			$this->disconnect($db);
			return  $e->getMessage();
		}
	}
	
	public function get_grade_config($class_id) {
		$result = array();
		
		$query_subject = "SELECT Id, Name FROM subject RIGHT JOIN (SELECT SubjectId FROM class_subteach_map WHERE ClassId = :ClassId) csub ON csub.SubjectId = subject.Id ORDER BY Name ASC";
		
		$query_exam = "SELECT Id, Name FROM exam INNER JOIN (SELECT DISTINCT ExamId FROM examsubjectmap INNER JOIN (SELECT Id FROM class_subteach_map WHERE ClassId = :ClassId) csmap ON examsubjectmap.ClassSubTeachMapId = csmap.Id) exm ON exm.ExamId = exam.Id";
		
		$query_student = "SELECT Id, FirstName, MiddleName, LastName, CAST(RollNumber AS UNSIGNED) AS RollNumber FROM student WHERE ClassId = :ClassId ORDER BY RollNumber ASC";
		
		$db = $this->connect();
		
		$stmt_subject = $db->prepare($query_subject);
		$stmt_subject->bindValue(':ClassId', $class_id, PDO::PARAM_INT);
		
		$stmt_exam = $db->prepare($query_exam);
		$stmt_exam->bindValue(':ClassId', $class_id, PDO::PARAM_INT);
		
		$stmt_student = $db->prepare($query_student);
		$stmt_student->bindValue(':ClassId', $class_id, PDO::PARAM_INT);
		
		$stmt_subject->execute();
		$stmt_exam->execute();
		$stmt_student->execute();
		
		$result['subject'] = $stmt_subject->fetchAll(PDO::FETCH_ASSOC);
		$result['exam'] = $stmt_exam->fetchAll(PDO::FETCH_ASSOC);
		$result['student'] = $stmt_student->fetchAll(PDO::FETCH_ASSOC);
		
		$json = json_encode( $result );
		$this->disconnect($db);
		return $json;
	}
	
	public function get_student_grade($student_id, $class_id) {
		$result = array();
		
		$query = "SELECT exam.Name, subject.Name AS SubjectName, grade.MarksObtained, grade.MarksTotal, grade.StudentId FROM class_subteach_map INNER JOIN examsubjectmap ON examsubjectmap.ClassSubTeachMapId= class_subteach_map.Id INNER JOIN exam ON exam.Id=examsubjectmap.ExamId INNER JOIN subject ON subject.Id=class_subteach_map.SubjectId INNER JOIN grade ON grade.StudentId = :StudentId AND grade.ExamSubjectMapId=examsubjectmap.Id WHERE class_subteach_map.ClassId = :ClassId ORDER BY Name, SubjectName";
		
		$query_subject = "SELECT Name FROM subject RIGHT JOIN (SELECT SubjectId FROM class_subteach_map WHERE ClassId = :ClassId) csub ON csub.SubjectId = subject.Id ORDER BY Name";
		
		$query_exam = "SELECT Name FROM exam INNER JOIN (SELECT DISTINCT ExamId FROM examsubjectmap INNER JOIN (SELECT Id FROM class_subteach_map WHERE ClassId = :ClassId) csmap ON examsubjectmap.ClassSubTeachMapId = csmap.Id) exm ON exm.ExamId = exam.Id ORDER BY Name";
		
		$db = $this->connect();
		$stmt = $db->prepare($query);
		$stmt->bindValue(':ClassId', $class_id, PDO::PARAM_INT);
		$stmt->bindValue(':StudentId', $student_id, PDO::PARAM_INT);
		
		$stmt_subject = $db->prepare($query_subject);
		$stmt_subject->bindValue(':ClassId', $class_id, PDO::PARAM_INT);
		
		$stmt_exam = $db->prepare($query_exam);
		$stmt_exam->bindValue(':ClassId', $class_id, PDO::PARAM_INT);
		
		$stmt->execute();
		$stmt_subject->execute();
		$stmt_exam->execute();
		
		$result['grade'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$result['subject'] = $stmt_subject->fetchAll(PDO::FETCH_ASSOC);
		$result['exam'] = $stmt_exam->fetchAll(PDO::FETCH_ASSOC);
		
		$json = json_encode( $result );
		$this->disconnect($db);
		return $json;
	}
	
	public function get_class_report($exam_id, $class_id) {
		$result = array();
		
		$query = "SELECT subject.Name AS SubjectName, grade.MarksObtained, grade.MarksTotal, student.Id AS StudentId, student.FirstName, student.LastName FROM class_subteach_map INNER JOIN examsubjectmap ON examsubjectmap.ClassSubTeachMapId= class_subteach_map.Id AND examsubjectmap.ExamId = :ExamId INNER JOIN exam ON exam.Id=examsubjectmap.ExamId INNER JOIN subject ON subject.Id=class_subteach_map.SubjectId INNER JOIN grade ON grade.ExamSubjectMapId=examsubjectmap.Id INNER JOIN student ON grade.StudentId = student.Id WHERE class_subteach_map.ClassId = :ClassId ORDER BY StudentId, SubjectName";
		
		$query_subject = "SELECT Name FROM subject RIGHT JOIN (SELECT SubjectId FROM class_subteach_map WHERE ClassId = :ClassId) csub ON csub.SubjectId = subject.Id ORDER BY Name";
		
		$query_student = "SELECT Id, CAST(RollNumber AS UNSIGNED) AS RollNumber, FirstName, MiddleName, LastName FROM student WHERE ClassId=:ClassId ORDER BY RollNumber ASC";
		
		$db = $this->connect();
		$stmt = $db->prepare($query);
		$stmt->bindValue(':ClassId', $class_id, PDO::PARAM_INT);
		$stmt->bindValue(':ExamId', $exam_id, PDO::PARAM_INT);
		
		$stmt_subject = $db->prepare($query_subject);
		$stmt_subject->bindValue(':ClassId', $class_id, PDO::PARAM_INT);
		
		$stmt_student = $db->prepare($query_student);
		$stmt_student->bindValue(':ClassId', $class_id, PDO::PARAM_INT);
		
		$stmt->execute();
		$stmt_subject->execute();
		$stmt_student->execute();
		
		$result['grade'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$result['subject'] = $stmt_subject->fetchAll(PDO::FETCH_ASSOC);
		$result['student'] = $stmt_student->fetchAll(PDO::FETCH_ASSOC);
		
		$json = json_encode( $result );
		$this->disconnect($db);
		return $json;
	}
	
	//ATTENTION
	public function get_teacher_na_meeting($teacher_id, $user_id, $class_id) {
		$result = array();
		$query = "SELECT mtg.Id, mtg.MeetingDate, mtg.StartTime, mtg.FinishTime, mtg.Subject, mtg.Description, mtg.CreatedOn, mtg.Location, student.FirstName, student.LastName FROM (SELECT Id, StudentId, MeetingDate, StartTime, FinishTime, Subject, Description, CreatedOn, Location FROM individualmeeting WHERE TeacherId = :TeacherId AND Status = 0 AND PostedBy != :UserId AND Status != 3) mtg LEFT JOIN student on mtg.StudentId = student.Id ORDER BY MeetingDate DESC";
		
		$query_count = "SELECT COUNT(Id) AS count FROM leaverequest WHERE ClassId = :ClassId AND Status = 0";
		
		$db = $this->connect();
		
		$stmt = $db->prepare($query);
		$stmt->bindValue(':TeacherId', $teacher_id, PDO::PARAM_INT);
		$stmt->bindValue(':UserId', $user_id, PDO::PARAM_INT);
		
		$stmt_count = $db->prepare($query_count);
		$stmt_count->bindValue(':ClassId', $class_id, PDO::PARAM_INT);
		
		$stmt->execute();
		$stmt_count->execute();
		
		$result['meeting'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$result['leave'] = $stmt_count->fetchAll(PDO::FETCH_ASSOC);
		
		$json = json_encode( $result );
		$this->disconnect($db);
		return $json;
	}
	
	public function get_parent_na_meeting($student_id, $user_id) {
		$query = "SELECT mtg.Id, mtg.MeetingDate, mtg.StartTime, mtg.FinishTime, mtg.Subject, mtg.Description, mtg.CreatedOn, mtg.Location, student.FirstName, student.LastName FROM (SELECT Id, StudentId, MeetingDate, StartTime, FinishTime, Subject, Description, CreatedOn, Location FROM individualmeeting WHERE StudentId = :StudentId AND Status = 0 AND PostedBy != :UserId AND Status != 3) mtg LEFT JOIN student on mtg.StudentId = student.Id ORDER BY MeetingDate DESC";
		$db = $this->connect();
		$stmt = $db->prepare($query);
		$stmt->bindValue(':StudentId', $student_id, PDO::PARAM_INT);
		$stmt->bindValue(':UserId', $user_id, PDO::PARAM_INT);
		$stmt->execute();
		$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$json = json_encode( $rows );
		$this->disconnect($db);
		return $json;
	}
	
	public function get_na_leave($teacher_id, $user_id, $class_id) {
		$result = array();
		
		$query = "SELECT lve.Id, lve.LeaveDate, lve.LeaveType, lve.Reason, lve.CreatedOn, student.FirstName, student.LastName FROM (SELECT Id, StudentId, LeaveDate, LeaveType, Reason, CreatedOn FROM leaverequest WHERE ClassId = :ClassId AND Status = 0 AND Status != 3)lve LEFT JOIN student ON StudentId = student.Id ORDER BY LeaveDate ASC, LastName DESC";
		
		$query_count = "SELECT COUNT(Id) AS count FROM individualmeeting WHERE TeacherId = :TeacherId AND Status = 0 AND PostedBy != :UserId";
		
		$db = $this->connect();
		
		$stmt = $db->prepare($query);
		$stmt->bindValue(':ClassId', $class_id, PDO::PARAM_INT);
		
		$stmt_count = $db->prepare($query_count);
		$stmt_count->bindValue(':TeacherId', $teacher_id, PDO::PARAM_INT);
		$stmt_count->bindValue(':UserId', $user_id, PDO::PARAM_INT);
		
		$stmt->execute();
		$stmt_count->execute();
		
		$result['leave'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$result['meeting'] = $stmt_count->fetchAll(PDO::FETCH_ASSOC);
		
		$json = json_encode( $result );
		$this->disconnect($db);
		return $json;
	}
	
	//ATTENTION RESPONSE
	public function na_meeting_accept($meeting_id) {
		$query = "UPDATE individualmeeting SET Status = 1 WHERE Id = :Id";
		try {
			$db = $this->connect();
			$stmt = $db->prepare($query);
			$stmt->bindValue(':Id', $meeting_id, PDO::PARAM_INT);
			$result = $stmt->execute();
			$this->disconnect($db);
			return $result;
		} catch (PDOEXCEPTION $e) {
			$this->disconnect($db);
			return  $e->getMessage();
		}
	}
	
	public function na_meeting_reject($meeting_id) {
		$query = "UPDATE individualmeeting SET Status = 2 WHERE Id = :Id";
		
		try {
			$db = $this->connect();
			$stmt = $db->prepare($query);
			$stmt->bindValue(':Id', $meeting_id, PDO::PARAM_INT);
			$result = $stmt->execute();
			$this->disconnect($db);
			return $result;
		} catch (PDOEXCEPTION $e) {
			$this->disconnect($db);
			return  $e->getMessage();
		}
	}
	
	public function na_leave_approve($leave_id) {
		$query = "UPDATE leaverequest SET Status = 1 WHERE Id = :Id";
		
		try {
			$db = $this->connect();
			$stmt = $db->prepare($query);
			$stmt->bindValue(':Id', $leave_id, PDO::PARAM_INT);
			$result = $stmt->execute();
			$this->disconnect($db);
			return $result;
		} catch (PDOEXCEPTION $e) {
			$this->disconnect($db);
			return  $e->getMessage();
		}
	}
	
	public function na_leave_deny($leave_id) {
		$query = "UPDATE leaverequest SET Status = 2 WHERE Id = :Id";
		
		try {
			$db = $this->connect();
			$stmt = $db->prepare($query);
			$stmt->bindValue(':Id', $leave_id, PDO::PARAM_INT);
			$result = $stmt->execute();
			$this->disconnect($db);
			return $result;
		} catch (PDOEXCEPTION $e) {
			$this->disconnect($db);
			return  $e->getMessage();
		}
	}
	
	//GET TEACHER INFO
	public function get_teachers_info($student_id, $class_id) {
		$teacher_id = $this->get_class_teacher($class_id);
		return $this->get_teachers_profile($teacher_id);
	}
	
	//ATTENDANCE
	public function post_attendance($students, $class_id, $attendance_date, $month) {
		$query = "INSERT INTO attendance (StudentId, ClassId, Date, Month, CreatedOn) VALUES (:StudentId, :ClassId, :Date, :Month, :CreatedOn)";
		$date=date('Y-m-d H:i:s');
		
		try {
			$db = $this->connect();
			$db->beginTransaction(); 
			$stmt = $db->prepare($query);
			
			foreach ($students as $student_id) {
				$stmt->bindValue(':StudentId', $student_id, PDO::PARAM_INT);
				$stmt->bindValue(':ClassId', $class_id, PDO::PARAM_INT);
				$stmt->bindValue(':Date', $attendance_date, PDO::PARAM_STR);
				$stmt->bindValue(':Month', $month, PDO::PARAM_INT);
				$stmt->bindValue(':CreatedOn', $date, PDO::PARAM_STR);
				
				$stmt->execute();
			}
			$result = $db->commit();
			$this->disconnect($db);
			return $result;
		}
		catch(PDOException $e) {
			$db->rollback();
			$this->disconnect($db);
			return  $e->getMessage();
		}
	}
	
	public function get_class_attendance($class_id, $month) {
		$result = array();
		
		$query = "SELECT StudentId, Date FROM attendance WHERE ClassId=:ClassId AND Month=:Month ORDER BY StudentId, Date";
		
		$query_student = "SELECT Id, CAST(RollNumber AS UNSIGNED) AS RollNumber, FirstName, MiddleName, LastName FROM student WHERE ClassId=:ClassId ORDER BY RollNumber ASC";
		
		$query_holiday = "SELECT Date FROM holiday WHERE SchoolId = :SchoolId";
		
		$school_id = $this->get_class_school($class_id);
		
		$db = $this->connect();
		$stmt = $db->prepare($query);
		$stmt->bindValue(':ClassId', $class_id, PDO::PARAM_INT);
		$stmt->bindValue(':Month', $month, PDO::PARAM_INT);
		
		$stmt_student = $db->prepare($query_student);
		$stmt_student->bindValue(':ClassId', $class_id, PDO::PARAM_INT);
		
		$stmt_holiday = $db->prepare($query_holiday);
		$stmt_holiday->bindValue(':SchoolId', $school_id, PDO::PARAM_INT);
		
		$stmt->execute();
		$stmt_student->execute();
		$stmt_holiday->execute();
		
		$result['attendance'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$result['student'] = $stmt_student->fetchAll(PDO::FETCH_ASSOC);
		$result['month'] = $month;
		$result['holiday'] = $stmt_holiday->fetchAll(PDO::FETCH_ASSOC);
		
		$json = json_encode( $result );
		$this->disconnect($db);
		return $json;
	}
	
	public function get_student_attendance($student_id) {
		$result = array();
		$query = "SELECT Date, Month FROM attendance WHERE StudentId=:StudentId ORDER BY Date";
		
		$query_holiday = "SELECT Date FROM holiday WHERE SchoolId = :SchoolId";
		
		$school_id = $this->get_student_school($student_id);
		
		$db = $this->connect();
		
		$stmt = $db->prepare($query);
		$stmt->bindValue(':StudentId', $student_id, PDO::PARAM_INT);
		
		$stmt_holiday = $db->prepare($query_holiday);
		$stmt_holiday->bindValue(':SchoolId', $school_id, PDO::PARAM_INT);
		
		$stmt->execute();
		$stmt_holiday->execute();
		
		$result['attendance'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$result['holiday'] = $stmt_holiday->fetchAll(PDO::FETCH_ASSOC);
		
		$json = json_encode( $result );
		$this->disconnect($db);
		return $json;
	}
	
	//GET RECENT
	private function get_recent($db, $teacher_id, $class_id, $user_id) {
		$result = array();
		
		$query_ann = "SELECT ann.Title, ann.Description, ann.CreatedOn, teacher.FirstName, teacher.LastName FROM (SELECT TeacherId, Title, description, CreatedOn FROM announcement WHERE ClassId = :ClassId AND CreatedOn>=CURDATE()-1)ann LEFT JOIN teacher ON ann.TeacherId = teacher.Id ORDER BY CreatedOn DESC LIMIT 5";
		
		$query_notice = "SELECT Title, Description, CreatedOn, CONCAT(teacher.FirstName, ' ', teacher.LastName) AS TeacherName, GROUP_CONCAT(DISTINCT CONCAT(' ', student.FirstName, ' ', student.LastName)) AS StudentName FROM notice LEFT JOIN teacher ON notice.TeacherId = teacher.Id LEFT JOIN student on notice.StudentId = student.Id WHERE TeacherId = :TeacherId AND CreatedOn>=CURDATE()-1 GROUP BY CreatedOn ORDER BY CreatedOn DESC LIMIT 5";
		
		$query_homework = "SELECT hws.DueDate, hws.DueTime, hws.Topic, hws.Description, hws.CreatedOn, subject.Name AS Subject FROM (SELECT hw.DueDate, hw.DueTime, hw.Topic, hw.Description, hw.CreatedOn, class_subteach_map.SubjectId FROM (SELECT ClassSubTeachMapId, DueDate, DueTime, Topic, Description, CreatedOn FROM `homework` WHERE ClassId = :ClassId AND CreatedOn >=	CURDATE()-1)hw LEFT JOIN class_subteach_map ON hw.ClassSubTeachMapId = class_subteach_map.Id)hws LEFT JOIN subject ON hws.SubjectId = subject.Id ORDER BY DueDate ASC LIMIT 5";
		
		$query_event = "SELECT EventDate, StartTime, FinishTime, Subject, Description, CreatedOn, Location FROM event WHERE ClassId = :ClassId AND CreatedOn >=	CURDATE()-1 AND Status != 3 ORDER BY EventDate DESC LIMIT 5";
		
		$query_meeting = "SELECT mtg.MeetingDate, StartTime, FinishTime, mtg.Subject, mtg.Description, mtg.CreatedOn, mtg.Location, student.FirstName, student.LastName FROM (SELECT StudentId, MeetingDate, StartTime, FinishTime, Subject, Description, CreatedOn, Location FROM individualmeeting WHERE TeacherId = :TeacherId AND CreatedOn>=CURDATE()-1 AND Status != 3) mtg LEFT JOIN student on mtg.StudentId = student.Id ORDER BY MeetingDate DESC LIMIT 5";
		
		$query_leave = "SELECT lve.LeaveDate, lve.LeaveType, lve.Reason, lve.CreatedOn, student.FirstName, student.LastName FROM (SELECT StudentId, LeaveDate, LeaveType, Reason, CreatedOn FROM leaverequest WHERE ClassId = :ClassId AND CreatedOn>=CURDATE()-1 AND Status != 3)lve LEFT JOIN student ON StudentId = student.Id ORDER BY LeaveDate DESC, LastName DESC LIMIT 5";
		
		$stmt_ann = $db->prepare($query_ann);
		$stmt_ann->bindValue(':ClassId', $class_id, PDO::PARAM_INT);
		
		$stmt_notice = $db->prepare($query_notice);
		$stmt_notice->bindValue(':TeacherId', $teacher_id, PDO::PARAM_INT);
		
		$stmt_homework = $db->prepare($query_homework);
		$stmt_homework->bindValue(':ClassId', $class_id, PDO::PARAM_INT);
		
		$stmt_event = $db->prepare($query_event);
		$stmt_event->bindValue(':ClassId', $class_id, PDO::PARAM_INT);
		
		$stmt_meeting = $db->prepare($query_meeting);
		$stmt_meeting->bindValue(':TeacherId', $teacher_id, PDO::PARAM_INT);
		//$stmt_meeting->bindValue(':PostedBy', $user_id, PDO::PARAM_INT);
		
		$stmt_leave = $db->prepare($query_leave);
		$stmt_leave->bindValue(':ClassId', $class_id, PDO::PARAM_INT);
		
		$stmt_ann->execute();
		$stmt_notice->execute();
		$stmt_homework->execute();
		$stmt_event->execute();
		$stmt_meeting->execute();
		$stmt_leave->execute();
		
		$result['ann'] = $stmt_ann->fetchAll(PDO::FETCH_ASSOC);
		$result['notice'] = $stmt_notice->fetchAll(PDO::FETCH_ASSOC);
		$result['homework'] = $stmt_homework->fetchAll(PDO::FETCH_ASSOC);
		$result['event'] = $stmt_event->fetchAll(PDO::FETCH_ASSOC);
		$result['meeting'] = $stmt_meeting->fetchAll(PDO::FETCH_ASSOC);
		$result['leave'] = $stmt_leave->fetchAll(PDO::FETCH_ASSOC);
		
		return $result;
	}
	
	private function get_parent_recent($db, $student_id, $class_id, $user_id) {
		$result = array();
		$query_ann = "SELECT ann.Title, ann.Description, ann.CreatedOn, teacher.FirstName, teacher.LastName FROM (SELECT TeacherId, Title, description, CreatedOn FROM announcement WHERE ClassId = :ClassId AND CreatedOn>=CURDATE()-1)ann LEFT JOIN teacher ON ann.TeacherId = teacher.Id ORDER BY CreatedOn DESC LIMIT 5";
		
		$query_notice = "SELECT ntcs.Title, ntcs.Description, ntcs.CreatedOn, ntcs.TeacherFN, ntcs.TeacherLN, student.FirstName AS StudentFN, student.LastName AS StudentLN FROM (SELECT ntc.StudentId, ntc.Title, ntc.Description, ntc.CreatedOn, teacher.FirstName AS TeacherFN, teacher.LastName AS TeacherLN FROM (SELECT StudentId, TeacherId, Title, description, CreatedOn FROM notice WHERE StudentId = :StudentId AND CreatedOn>=CURDATE())ntc LEFT JOIN teacher ON ntc.TeacherId = teacher.Id) ntcs LEFT JOIN student on ntcs.StudentId = student.Id ORDER BY CreatedOn DESC LIMIT 5";
		
		$query_homework = "SELECT hws.DueDate, hws.DueTime, hws.Topic, hws.Description, hws.CreatedOn, subject.Name AS Subject FROM (SELECT hw.DueDate, hw.DueTime, hw.Topic, hw.Description, hw.CreatedOn, class_subteach_map.SubjectId FROM (SELECT ClassSubTeachMapId, DueDate, DueTime, Topic, Description, CreatedOn FROM `homework` WHERE ClassId = :ClassId AND CreatedOn >=	CURDATE()-1)hw LEFT JOIN class_subteach_map ON hw.ClassSubTeachMapId = class_subteach_map.Id)hws LEFT JOIN subject ON hws.SubjectId = subject.Id ORDER BY DueDate ASC LIMIT 5";
		
		$query_event = "SELECT EventDate, StartTime, FinishTime, Subject, Description, CreatedOn, Location FROM event WHERE ClassId = :ClassId AND CreatedOn >=	CURDATE()-1 AND Status != 3 ORDER BY EventDate DESC LIMIT 5";
		
		$query_meeting = "SELECT mtg.Id, mtg.MeetingDate, mtg.StartTime, mtg.FinishTime, mtg.Subject, mtg.Description, mtg.CreatedOn, mtg.Location, student.FirstName, student.LastName FROM (SELECT Id, StudentId, MeetingDate, StartTime, FinishTime, Subject, Description, CreatedOn, Location FROM individualmeeting WHERE StudentId = :StudentId AND CreatedOn >= CURDATE()-1 AND Status != 3) mtg LEFT JOIN student on mtg.StudentId = student.Id ORDER BY MeetingDate DESC LIMIT 5";
		
		$query_leave = "SELECT LeaveDate, LeaveType, Reason, Status, CreatedOn FROM leaverequest WHERE StudentId= :StudentId  AND CreatedOn>=CURDATE()-1 AND Status != 3 ORDER BY LeaveDate DESC LIMIT 5";
		
		$stmt_ann = $db->prepare($query_ann);
		$stmt_ann->bindValue(':ClassId', $class_id, PDO::PARAM_INT);
		
		$stmt_notice = $db->prepare($query_notice);
		$stmt_notice->bindValue(':StudentId', $student_id, PDO::PARAM_INT);
		
		$stmt_homework = $db->prepare($query_homework);
		$stmt_homework->bindValue(':ClassId', $class_id, PDO::PARAM_INT);
		
		$stmt_event = $db->prepare($query_event);
		$stmt_event->bindValue(':ClassId', $class_id, PDO::PARAM_INT);
		
		$stmt_meeting = $db->prepare($query_meeting);
		$stmt_meeting->bindValue(':StudentId', $student_id, PDO::PARAM_INT);
		
		$stmt_leave = $db->prepare($query_leave);
		$stmt_leave->bindValue(':StudentId', $student_id, PDO::PARAM_INT);
		
		$stmt_ann->execute();
		$stmt_notice->execute();
		$stmt_homework->execute();
		$stmt_event->execute();
		$stmt_meeting->execute();
		$stmt_leave->execute();
		
		$result['ann'] = $stmt_ann->fetchAll(PDO::FETCH_ASSOC);
		$result['notice'] = $stmt_notice->fetchAll(PDO::FETCH_ASSOC);
		$result['homework'] = $stmt_homework->fetchAll(PDO::FETCH_ASSOC);
		$result['event'] = $stmt_event->fetchAll(PDO::FETCH_ASSOC);
		$result['meeting'] = $stmt_meeting->fetchAll(PDO::FETCH_ASSOC);
		$result['leave'] = $stmt_leave->fetchAll(PDO::FETCH_ASSOC);
		
		return $result;
	}
	
	//HOME
	public function get_home($class_id, $teacher_id, $user_id) {
		$result = array();
		$query_bday = "SELECT FirstName, MiddleName, LastName, CAST(RollNumber AS UNSIGNED) AS RollNumber FROM student WHERE ClassId = :ClassId AND DateOfBirth=CURDATE()";
		
		$query_homework = "SELECT hws.DueDate, hws.DueTime, hws.Topic, hws.Description, hws.CreatedOn, subject.Name AS Subject FROM (SELECT hw.DueDate, hw.DueTime, hw.Topic, hw.Description, hw.CreatedOn, class_subteach_map.SubjectId FROM (SELECT ClassSubTeachMapId, DueDate, DueTime, Topic, Description, CreatedOn FROM `homework` WHERE ClassId = :ClassId AND DueDate=CURDATE())hw LEFT JOIN class_subteach_map ON hw.ClassSubTeachMapId = class_subteach_map.Id)hws LEFT JOIN subject ON hws.SubjectId = subject.Id ORDER BY DueDate DESC";
		
		$query_meeting = "SELECT EventDate, StartTime, FinishTime, Subject, Description, CreatedOn, Location FROM event WHERE ClassId = :ClassId AND EventDate=CURDATE() AND Status=0 AND Status != 3";
		
		$query_imeeting = "SELECT mtg.MeetingDate, StartTime, FinishTime, mtg.Subject, mtg.Description, mtg.CreatedOn, mtg.Location, student.FirstName, student.LastName FROM (SELECT StudentId, MeetingDate, StartTime, FinishTime, Subject, Description, CreatedOn, Location FROM individualmeeting WHERE TeacherId = :TeacherId AND Status = 1 AND MeetingDate=CURDATE()) mtg LEFT JOIN student on mtg.StudentId = student.Id ORDER BY MeetingDate DESC";
		
		$query_leave = "SELECT lve.LeaveDate, lve.LeaveType, lve.Reason, lve.CreatedOn, student.FirstName, student.LastName FROM (SELECT StudentId, LeaveDate, LeaveType, Reason, CreatedOn FROM leaverequest WHERE ClassId = :ClassId AND Status = 1 AND LeaveDate=CURDATE())lve LEFT JOIN student ON StudentId = student.Id ORDER BY LeaveDate DESC, LastName DESC";
		
		$query_count = "SELECT COUNT(Id) AS count FROM individualmeeting WHERE TeacherId = :TeacherId AND Status = 0 AND PostedBy != :UserId UNION ALL SELECT COUNT(Id) AS count FROM leaverequest WHERE ClassId = :ClassId AND Status = 0";
		
		$query_name = "SELECT FirstName FROM teacher WHERE Id = :TeacherId";
		
		$query_school = "SELECT Name, City FROM school INNER JOIN teacher ON school.Id = teacher.SchoolId WHERE teacher.Id = :TeacherId";
		
		$query_className = "SELECT Name FROM classroom INNER JOIN class ON classroom.Id = class.ClassroomId INNER JOIN teacherclassmap ON class.Id = teacherclassmap.ClassId WHERE teacherclassmap.ClassId = :ClassId";
		
		$db = $this->connect();
		
		$stmt_bday = $db->prepare($query_bday);
		$stmt_bday->bindValue(':ClassId', $class_id, PDO::PARAM_INT);
		
		$stmt_homework = $db->prepare($query_homework);
		$stmt_homework->bindValue(':ClassId', $class_id, PDO::PARAM_INT);
		
		$stmt_meeting = $db->prepare($query_meeting);
		$stmt_meeting->bindValue(':ClassId', $class_id, PDO::PARAM_INT);
		
		$stmt_imeeting = $db->prepare($query_imeeting);
		$stmt_imeeting->bindValue(':TeacherId', $teacher_id, PDO::PARAM_INT);
		
		$stmt_leave = $db->prepare($query_leave);
		$stmt_leave->bindValue(':ClassId', $class_id, PDO::PARAM_INT);
		
		$stmt_count = $db->prepare($query_count);
		$stmt_count->bindValue(':ClassId', $class_id, PDO::PARAM_INT);
		$stmt_count->bindValue(':TeacherId', $teacher_id, PDO::PARAM_INT);
		$stmt_count->bindValue(':UserId', $user_id, PDO::PARAM_INT);
		
		$stmt_name = $db->prepare($query_name);
		$stmt_name->bindValue(':TeacherId', $teacher_id, PDO::PARAM_INT);
		
		$stmt_school = $db->prepare($query_school);
		$stmt_school->bindValue(':TeacherId', $teacher_id, PDO::PARAM_INT);
		
		$stmt_className = $db->prepare($query_className);
		$stmt_className->bindValue(':ClassId', $class_id, PDO::PARAM_INT);
		
		$stmt_bday->execute();
		$stmt_homework->execute();
		$stmt_meeting->execute();
		$stmt_imeeting->execute();
		$stmt_leave->execute();
		$stmt_count->execute();
		$stmt_name->execute();
		$stmt_school->execute();
		$stmt_className->execute();
		
		$result['bday'] = $stmt_bday->fetchAll(PDO::FETCH_ASSOC);
		$result['homework'] = $stmt_homework->fetchAll(PDO::FETCH_ASSOC);
		$result['event'] = $stmt_meeting->fetchAll(PDO::FETCH_ASSOC);
		$result['meeting'] = $stmt_imeeting->fetchAll(PDO::FETCH_ASSOC);
		$result['leave'] = $stmt_leave->fetchAll(PDO::FETCH_ASSOC);
		$result['count'] = $stmt_count->fetchAll(PDO::FETCH_ASSOC);
		$result['name'] = $stmt_name->fetch(PDO::FETCH_ASSOC);
		$result['school'] = $stmt_school->fetch(PDO::FETCH_ASSOC);
		$result['className'] = $stmt_className->fetch(PDO::FETCH_ASSOC);
		
		$json = json_encode( $result );
		$this->disconnect($db);
		return $json;
	}
	
	public function get_parent_home($class_id, $student_id, $user_id) {
		$result = array();
		$query_bday = "SELECT FirstName, MiddleName, LastName, CAST(RollNumber AS UNSIGNED) AS RollNumber FROM student WHERE ClassId = :ClassId AND DateOfBirth=CURDATE()";
		
		$query_homework = "SELECT hws.DueDate, hws.DueTime, hws.Topic, hws.Description, hws.CreatedOn, subject.Name AS Subject FROM (SELECT hw.DueDate, hw.DueTime, hw.Topic, hw.Description, hw.CreatedOn, class_subteach_map.SubjectId FROM (SELECT ClassSubTeachMapId, DueDate, DueTime, Topic, Description, CreatedOn FROM `homework` WHERE ClassId = :ClassId AND DueDate=CURDATE())hw LEFT JOIN class_subteach_map ON hw.ClassSubTeachMapId = class_subteach_map.Id)hws LEFT JOIN subject ON hws.SubjectId = subject.Id ORDER BY DueDate DESC";
		
		$query_meeting = "SELECT EventDate, StartTime, FinishTime, Subject, Description, CreatedOn, Location FROM event WHERE ClassId = :ClassId AND EventDate=CURDATE() AND Status=0";
		
		$query_imeeting = "SELECT mtg.MeetingDate, StartTime, FinishTime, mtg.Subject, mtg.Description, mtg.CreatedOn, mtg.Location, student.FirstName, student.LastName FROM (SELECT StudentId, MeetingDate, StartTime, FinishTime, Subject, Description, CreatedOn, Location FROM individualmeeting WHERE StudentId = :StudentId AND Status = 1 AND MeetingDate=CURDATE()) mtg LEFT JOIN student on mtg.StudentId = student.Id ORDER BY MeetingDate DESC";
		
		$query_leave = "SELECT lve.LeaveDate, lve.LeaveType, lve.Reason, lve.CreatedOn, student.FirstName, student.LastName FROM (SELECT StudentId, LeaveDate, LeaveType, Reason, CreatedOn FROM leaverequest WHERE StudentId = :StudentId AND Status = 1 AND LeaveDate=CURDATE())lve LEFT JOIN student ON StudentId = student.Id ORDER BY LeaveDate DESC, LastName DESC";
		
		$query_count = "SELECT count(Id) AS count FROM individualmeeting WHERE StudentId = :StudentId AND Status = 0 AND PostedBy != :UserId";
		
		$query_name = "SELECT parent.FirstName FROM parent INNER JOIN student ON parent.StudentId = student.Id WHERE student.Id = :StudentId";
		
		$query_school = "SELECT Name, City FROM school INNER JOIN class ON class.SchoolId = school.Id INNER JOIN student on class.Id = student.ClassId WHERE student.Id = :StudentId";
		
		$query_className = "SELECT Name FROM classroom INNER JOIN class ON class.ClassroomId = classroom.Id INNER JOIN student on class.Id = student.ClassId WHERE student.Id = :StudentId";
			
		$db = $this->connect();
		
		$stmt_bday = $db->prepare($query_bday);
		$stmt_bday->bindValue(':ClassId', $class_id, PDO::PARAM_INT);
		
		$stmt_homework = $db->prepare($query_homework);
		$stmt_homework->bindValue(':ClassId', $class_id, PDO::PARAM_INT);
		
		$stmt_meeting = $db->prepare($query_meeting);
		$stmt_meeting->bindValue(':ClassId', $class_id, PDO::PARAM_INT);
		
		$stmt_imeeting = $db->prepare($query_imeeting);
		$stmt_imeeting->bindValue(':StudentId', $student_id, PDO::PARAM_INT);
		
		$stmt_leave = $db->prepare($query_leave);
		$stmt_leave->bindValue(':StudentId', $student_id, PDO::PARAM_INT);
		
		$stmt_count = $db->prepare($query_count);
		$stmt_count->bindValue(':StudentId', $student_id, PDO::PARAM_INT);
		$stmt_count->bindValue(':UserId', $user_id, PDO::PARAM_INT);
		
		$stmt_name = $db->prepare($query_name);
		$stmt_name->bindValue(':StudentId', $student_id, PDO::PARAM_INT);
		
		$stmt_school = $db->prepare($query_school);
		$stmt_school->bindValue(':StudentId', $student_id, PDO::PARAM_INT);
		
		$stmt_className = $db->prepare($query_className);
		$stmt_className->bindValue(':StudentId', $student_id, PDO::PARAM_INT);
		
		$stmt_bday->execute();
		$stmt_homework->execute();
		$stmt_meeting->execute();
		$stmt_imeeting->execute();
		$stmt_leave->execute();
		$stmt_count->execute();
		$stmt_name->execute();
		$stmt_school->execute();
		$stmt_className->execute();
		
		$result['bday'] = $stmt_bday->fetchAll(PDO::FETCH_ASSOC);
		$result['homework'] = $stmt_homework->fetchAll(PDO::FETCH_ASSOC);
		$result['event'] = $stmt_meeting->fetchAll(PDO::FETCH_ASSOC);
		$result['meeting'] = $stmt_imeeting->fetchAll(PDO::FETCH_ASSOC);
		$result['leave'] = $stmt_leave->fetchAll(PDO::FETCH_ASSOC);
		$result['count'] = $stmt_count->fetchAll(PDO::FETCH_ASSOC);
		$result['name'] = $stmt_name->fetch(PDO::FETCH_ASSOC);
		$result['school'] = $stmt_school->fetch(PDO::FETCH_ASSOC);
		$result['className'] = $stmt_className->fetch(PDO::FETCH_ASSOC);
		
		$json = json_encode( $result );
		$this->disconnect($db);
		return $json;
	}
	
	public function get_admin_home($school_id) {
		$query = "SELECT Name, EstablishmentYear, Address, City, PinCode, PhoneNumber, Email, Website FROM school WHERE Id = :SchoolId";
		$db = $this->connect();
		$stmt = $db->prepare($query);
		$stmt->bindValue(':SchoolId', $school_id, PDO::PARAM_INT);
		$stmt->execute();
		$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$json = json_encode( $rows );
		$this->disconnect($db);
		return $json;
	}
	
	//NOTIFICATION
	public function get_notifications($class_id, $teacher_id, $user_id) {
		$result = array();
		
		$query_meeting = "SELECT EventDate, StartTime, FinishTime, Subject, Description, CreatedOn, Location FROM event WHERE ClassId = :ClassId AND EventDate>CURDATE() AND Status=0 ORDER BY EventDate ASC LIMIT 2";
		
		$query_imeeting = "SELECT mtg.MeetingDate, StartTime, FinishTime, mtg.Subject, mtg.Description, mtg.CreatedOn, mtg.Location, student.FirstName, student.LastName FROM (SELECT StudentId, MeetingDate, StartTime, FinishTime, Subject, Description, CreatedOn, Location FROM individualmeeting WHERE TeacherId = :TeacherId AND MeetingDate>CURDATE() AND Status = 1) mtg LEFT JOIN student on mtg.StudentId = student.Id ORDER BY MeetingDate ASC LIMIT 5";
		
		$query_leave = "SELECT lve.LeaveDate, lve.LeaveType, lve.Reason, lve.CreatedOn, student.FirstName, student.LastName FROM (SELECT StudentId, LeaveDate, LeaveType, Reason, CreatedOn FROM leaverequest WHERE ClassId = :ClassId AND Status=1 AND LeaveDate>CURDATE())lve LEFT JOIN student ON StudentId = student.Id ORDER BY LeaveDate DESC, LastName ASC LIMIT 5";
		
		$query_na_meeting = "SELECT mtg.Id, mtg.MeetingDate, mtg.StartTime, mtg.FinishTime, mtg.Subject, mtg.Description, mtg.CreatedOn, mtg.Location, student.FirstName, student.LastName FROM (SELECT Id, StudentId, MeetingDate, StartTime, FinishTime, Subject, Description, CreatedOn, Location FROM individualmeeting WHERE TeacherId = :TeacherId AND Status = 0 AND PostedBy != :UserId AND Status != 3) mtg LEFT JOIN student on mtg.StudentId = student.Id ORDER BY MeetingDate ASC LIMIT 2";
		
		$query_na_leave = "SELECT lve.Id, lve.LeaveDate, lve.LeaveType, lve.Reason, lve.CreatedOn, student.FirstName, student.LastName FROM (SELECT Id, StudentId, LeaveDate, LeaveType, Reason, CreatedOn FROM leaverequest WHERE ClassId = :ClassId AND Status = 0 AND Status != 3)lve LEFT JOIN student ON StudentId = student.Id ORDER BY LeaveDate ASC, LastName DESC LIMIT 3";
		
		$db = $this->connect();
		
		$stmt_meeting = $db->prepare($query_meeting);
		$stmt_meeting->bindValue(':ClassId', $class_id, PDO::PARAM_INT);
		
		$stmt_imeeting = $db->prepare($query_imeeting);
		$stmt_imeeting->bindValue(':TeacherId', $teacher_id, PDO::PARAM_INT);
		
		$stmt_leave = $db->prepare($query_leave);
		$stmt_leave->bindValue(':ClassId', $class_id, PDO::PARAM_INT);
		
		$stmt_na_meeting = $db->prepare($query_na_meeting);
		$stmt_na_meeting->bindValue(':TeacherId', $teacher_id, PDO::PARAM_INT);
		$stmt_na_meeting->bindValue(':UserId', $user_id, PDO::PARAM_INT);
		
		$stmt_na_leave = $db->prepare($query_na_leave);
		$stmt_na_leave->bindValue(':ClassId', $class_id, PDO::PARAM_INT);
		
		$stmt_meeting->execute();
		$stmt_imeeting->execute();
		$stmt_leave->execute();
		$stmt_na_meeting->execute();
		$stmt_na_leave->execute();
		
		$result['event'] = $stmt_meeting->fetchAll(PDO::FETCH_ASSOC);
		$result['meeting'] = $stmt_imeeting->fetchAll(PDO::FETCH_ASSOC);
		$result['leave'] = $stmt_leave->fetchAll(PDO::FETCH_ASSOC);
		$result['mattention'] = $stmt_na_meeting->fetchAll(PDO::FETCH_ASSOC);
		$result['lattention'] = $stmt_na_leave->fetchAll(PDO::FETCH_ASSOC);
		$result['recent'] = $this->get_recent($db, $teacher_id, $class_id, $user_id);
		
		$json = json_encode( $result );
		$this->disconnect($db);
		return $json;
	}
	
	public function get_parent_notifications($class_id, $student_id, $user_id) {
		$result = array();
		
		$query_meeting = "SELECT EventDate, StartTime, FinishTime, Subject, Description, CreatedOn, Location FROM event WHERE ClassId = :ClassId AND EventDate>CURDATE() AND Status=0 AND Status != 3 ORDER BY EventDate ASC LIMIT 2";
		
		$query_imeeting = "SELECT mtg.MeetingDate, StartTime, FinishTime, mtg.Subject, mtg.Description, mtg.CreatedOn, mtg.Location, student.FirstName, student.LastName FROM (SELECT StudentId, MeetingDate, StartTime, FinishTime, Subject, Description, CreatedOn, Location FROM individualmeeting WHERE StudentId = :StudentId AND MeetingDate>CURDATE() AND Status = 1) mtg LEFT JOIN student on mtg.StudentId = student.Id ORDER BY MeetingDate ASC LIMIT 5";
		
		$query_leave = "SELECT lve.LeaveDate, lve.LeaveType, lve.Reason, lve.CreatedOn, student.FirstName, student.LastName FROM (SELECT StudentId, LeaveDate, LeaveType, Reason, CreatedOn FROM leaverequest WHERE StudentId = :StudentId AND Status=1 AND LeaveDate>CURDATE())lve LEFT JOIN student ON StudentId = student.Id ORDER BY LeaveDate DESC, LastName ASC LIMIT 5";
		
		$query_na_meeting = "SELECT mtg.Id, mtg.MeetingDate, mtg.StartTime, mtg.FinishTime, mtg.Subject, mtg.Description, mtg.CreatedOn, mtg.Location, student.FirstName, student.LastName FROM (SELECT Id, StudentId, MeetingDate, StartTime, FinishTime, Subject, Description, CreatedOn, Location FROM individualmeeting WHERE StudentId = :StudentId AND Status = 0 AND PostedBy != :UserId AND Status != 3) mtg LEFT JOIN student on mtg.StudentId = student.Id ORDER BY MeetingDate ASC LIMIT 2";
		
		$db = $this->connect();
		
		$stmt_meeting = $db->prepare($query_meeting);
		$stmt_meeting->bindValue(':ClassId', $class_id, PDO::PARAM_INT);
		
		$stmt_imeeting = $db->prepare($query_imeeting);
		$stmt_imeeting->bindValue(':StudentId', $student_id, PDO::PARAM_INT);
		
		$stmt_leave = $db->prepare($query_leave);
		$stmt_leave->bindValue(':StudentId', $student_id, PDO::PARAM_INT);
		
		$stmt_na_meeting = $db->prepare($query_na_meeting);
		$stmt_na_meeting->bindValue(':StudentId', $student_id, PDO::PARAM_INT);
		$stmt_na_meeting->bindValue(':UserId', $user_id, PDO::PARAM_INT);
		
		$stmt_meeting->execute();
		$stmt_imeeting->execute();
		$stmt_leave->execute();
		$stmt_na_meeting->execute();
		
		$result['event'] = $stmt_meeting->fetchAll(PDO::FETCH_ASSOC);
		$result['meeting'] = $stmt_imeeting->fetchAll(PDO::FETCH_ASSOC);
		$result['leave'] = $stmt_leave->fetchAll(PDO::FETCH_ASSOC);
		$result['mattention'] = $stmt_na_meeting->fetchAll(PDO::FETCH_ASSOC);
		$result['recent'] = $this->get_parent_recent($db, $student_id, $class_id, $user_id);
		$json = json_encode( $result );
		$this->disconnect($db);
		return $json;
	}
	
	//PROFILE
	public function get_teachers_profile($teacher_id) {
		$query = "SELECT teacher.FirstName, teacher.MiddleName, teacher.LastName, teacher.DateOfBirth, teacher.Gender, teacher.Email, teacher.EmailAlert, teacher.MobileNumber, teacher.DeskLocation, teacherprofile.Designation, teacherprofile.Education, teacherprofile.Experience, teacherprofile.Description  FROM `teacher` LEFT JOIN teacherprofile ON teacher.Id = teacherprofile.TeacherId WHERE teacher.Id = :TeacherId";
		
		$db = $this->connect();
		$stmt = $db->prepare($query);
		$stmt->bindValue(':TeacherId', $teacher_id, PDO::PARAM_INT);
		$stmt->execute();
		$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$json = json_encode( $rows );
		$this->disconnect($db);
		return $json;
	}
	
	public function get_parents_profile($parent_id) {
		$query = "SELECT parent.Firstname, parent.MiddleName, parent.LastName, parent.Address, parent.Email1, parent.Email2, parent.EmailAlert, parent.MobileNumber, student.Firstname AS SFName, student.LastName AS SLName, student.RollNumber FROM parent INNER JOIN student ON student.Id = parent.StudentId WHERE parent.Id = :ParentId";
		
		$db = $this->connect();
		$stmt = $db->prepare($query);
		$stmt->bindValue(':ParentId', $parent_id, PDO::PARAM_INT);
		$stmt->execute();
		$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$json = json_encode( $rows );
		$this->disconnect($db);
		return $json;
	}
	
	public function update_tprofile($teacher_id, $fName, $mName, $lName, $mobile, $email, $alert, $desk, $education, $designation, $experience, $dateOfBirth, $gender, $description) {
		$query = "UPDATE teacher a JOIN teacherprofile b ON a.Id = b.TeacherId SET a.FirstName = :FirstName, a.MiddleName = :MiddleName, a.LastName = :LastName, a.DateOfBirth = :DateOfBirth, a.Gender = :Gender, a.Email = :Email, a.EmailAlert = :EmailAlert, a.MobileNumber = :MobileNumber, a.DeskLocation = :DeskLocation, b.Designation = :Designation, b.Education = :Education, b.Experience = :Experience, b.Description = :Description WHERE a.Id = :TeacherId";
		
		try {
			$db = $this->connect();
			$stmt = $db->prepare($query);
			$stmt->bindValue(':TeacherId', $teacher_id, PDO::PARAM_INT);
			$stmt->bindValue(':FirstName', $fName, PDO::PARAM_STR);
			$stmt->bindValue(':MiddleName', $mName, PDO::PARAM_STR);
			$stmt->bindValue(':LastName', $lName, PDO::PARAM_STR);
			$stmt->bindValue(':DateOfBirth', $dateOfBirth, PDO::PARAM_STR);
			$stmt->bindValue(':Gender', $gender, PDO::PARAM_STR);
			$stmt->bindValue(':Email', $email, PDO::PARAM_STR);
			$stmt->bindValue(':MobileNumber', $mobile, PDO::PARAM_STR);
			$stmt->bindValue(':DeskLocation', $desk, PDO::PARAM_STR);
			$stmt->bindValue(':Designation', $designation, PDO::PARAM_STR);
			$stmt->bindValue(':Education', $education, PDO::PARAM_STR);
			$stmt->bindValue(':Experience', $experience, PDO::PARAM_STR);
			$stmt->bindValue(':Description', $description, PDO::PARAM_STR);
			$stmt->bindValue(':EmailAlert', $alert, PDO::PARAM_INT);
			$result = $stmt->execute();
			$this->disconnect($db);
			return $result;
		} catch (PDOEXCEPTION $e) {
			$this->disconnect($db);
			return  $e->getMessage();
		}
	}
	
	public function update_pprofile($parent_id, $fName, $mName, $lName, $mobile, $email1, $email2, $alert, $address) {
		$query = "UPDATE parent SET FirstName = :FirstName, MiddleName = :MiddleName, LastName = :LastName, Email1 = :Email1, Email2 = :Email2, MobileNumber = :MobileNumber, Address = :Address, EmailAlert = :EmailAlert WHERE Id = :ParentId";
		
		try{
			$db = $this->connect();
			$stmt = $db->prepare($query);
			$stmt->bindValue(':ParentId', $parent_id, PDO::PARAM_INT);
			$stmt->bindValue(':FirstName', $fName, PDO::PARAM_STR);
			$stmt->bindValue(':MiddleName', $mName, PDO::PARAM_STR);
			$stmt->bindValue(':LastName', $lName, PDO::PARAM_STR);
			$stmt->bindValue(':Email1', $email1, PDO::PARAM_STR);
			$stmt->bindValue(':Email2', $email2, PDO::PARAM_STR);
			$stmt->bindValue(':MobileNumber', $mobile, PDO::PARAM_STR);
			$stmt->bindValue(':Address', $address, PDO::PARAM_STR);
			$stmt->bindValue(':EmailAlert', $alert, PDO::PARAM_INT);
			$result = $stmt->execute();
			$this->disconnect($db);
			return $result;
		} catch (PDOEXCEPTION $e) {
			$this->disconnect($db);
			return  $e->getMessage();
		}
	}
		
	//SEND EMAIL
	public function get_teacher_email($class_id) {
		$query = "SELECT Email FROM teacher INNER JOIN teacherclassmap ON teacherclassmap.TeacherId = teacher.Id WHERE ClassId = :ClassId AND EmailAlert = 1";
		$db = $this->connect();
		$stmt = $db->prepare($query);
		$stmt->bindValue(':ClassId', $class_id, PDO::PARAM_INT);
		$stmt->execute();
		$rows = $stmt->fetch(PDO::FETCH_ASSOC);
		$this->disconnect($db);
		return $rows['Email'];
	}
	
	public function get_class_parent_email($class_id) {
		$query = "SELECT GROUP_CONCAT(DISTINCT CONCAT_WS(',' , Email1, Email2)) AS Email FROM parent INNER JOIN student ON student.Id = parent.StudentId WHERE ClassId = :ClassId AND EmailAlert = 1";
		$db = $this->connect();
		$stmt = $db->prepare($query);
		$stmt->bindValue(':ClassId', $class_id, PDO::PARAM_INT);
		$stmt->execute();
		$rows = $stmt->fetch(PDO::FETCH_ASSOC);
		$this->disconnect($db);
		return $rows['Email'];
	}
	
	public function get_student_parent_email($student_id) {
		$query = "SELECT GROUP_CONCAT(CONCAT_WS(',' , Email1, Email2)) AS Email FROM parent WHERE StudentId = :StudentId AND EmailAlert = 1";
		$db = $this->connect();
		$stmt = $db->prepare($query);
		$stmt->bindValue(':StudentId', $student_id, PDO::PARAM_INT);
		$stmt->execute();
		$rows = $stmt->fetch(PDO::FETCH_ASSOC);
		$this->disconnect($db);
		return $rows['Email'];
	}
	
	public function get_students_parent_email($students) {
		$ids = implode(',', array_fill(0, count($students), '?'));
		$query = "SELECT GROUP_CONCAT( DISTINCT CONCAT_WS(',' , Email1, Email2)) AS Email FROM parent WHERE StudentId IN (".$ids.") AND EmailAlert = 1";
		$db = $this->connect();
		$stmt = $db->prepare($query);
		$stmt->execute($students);
		$rows = $stmt->fetch(PDO::FETCH_ASSOC);
		$this->disconnect($db);
		return $rows['Email'];
	}
	
	public function get_student_names($students) {
		$ids = implode(',', array_fill(0, count($students), '?'));
		$query = "SELECT GROUP_CONCAT(DISTINCT CONCAT(' ', FirstName, ' ', LastName)) AS StudentName FROM `student` WHERE Id IN (".$ids.")";
		$db = $this->connect();
		$stmt = $db->prepare($query);
		$stmt->execute($students);
		$rows = $stmt->fetch(PDO::FETCH_ASSOC);
		$this->disconnect($db);
		return $rows['StudentName'];
	}

	public function get_subjectName($subject_id) {
		$query = "SELECT Name FROM subject WHERE Id = :SubjectId";
		$db = $this->connect();
		$stmt = $db->prepare($query);
		$stmt->bindValue(':SubjectId', $subject_id, PDO::PARAM_INT);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		$this->disconnect($db);
		return $row['Name'];
	}
	
	public function get_examName($exam_id) {
		$query = "SELECT Name FROM exam WHERE Id = :ExamId";
		$db = $this->connect();
		$stmt = $db->prepare($query);
		$stmt->bindValue(':ExamId', $exam_id, PDO::PARAM_INT);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		$this->disconnect($db);
		return $row['Name'];
	}
	
	//HOLIDAY
	public function post_holiday($holiday_date, $school_id) {
		$query = "INSERT INTO holiday (Date, SchoolId) VALUES (:Date, :SchoolId)";
		
		try {
			$db = $this->connect();
			$stmt = $db->prepare($query);
			$stmt->bindValue(':SchoolId', $school_id, PDO::PARAM_INT);
			$stmt->bindValue(':Date', $holiday_date, PDO::PARAM_STR);
			$result = $stmt->execute();
			$this->disconnect($db);
			return $result;
		} catch (PDOEXCEPTION $e) {
			$this->disconnect($db);
			return  $e->getMessage();
		}
	}
	
	public function get_holiday($school_id) {
		$query = "SELECT Date FROM holiday WHERE SchoolId = :SchoolId";
		$db = $this->connect();
		$stmt = $db->prepare($query);
		$stmt->bindValue(':SchoolId', $school_id, PDO::PARAM_INT);
		$stmt->execute();
		$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$json = json_encode( $rows );
		$this->disconnect($db);
		return $json;
	}
	
	//Create Hash
	public function create_hash($pword){
		$password = $pword;
		$salt= mcrypt_create_iv(32, MCRYPT_DEV_URANDOM);

		//Below hash is crypt hash created using CRYPT_BLOWFISH - $2y$.
		$options = [
			'salt' => $salt,
		];
		return  password_hash($password, PASSWORD_BCRYPT, $options);
	}
	
	//Get password from lastname and dateOfBirth
	public function get_password($dob, $last_name) {
		$last_name = strtolower($last_name);
		$date = str_replace('/', '-', $dob);
		$year = date("Y", strtotime($date));
		$password = $last_name . "@" . $year;
		return $password;
	}
	
	//Get Username from firstName and dateOfBirth
	public function get_username($dob, $first_name) {
		$first_name = strtolower($first_name);
		$date = str_replace('/', '-', $dob);
		$d = date("d", strtotime($date));
		$m = date("m", strtotime($date));
		$username = $first_name . $d . $m;
		return $username;
	}
	
	public function data_check($data) {
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);
		return $data;
	}
	
	public function format_date($d) {
		$d = str_replace('/', '-', $d);
		$time = strtotime($d);
		return date('Y-m-d', $time);
	}
	
	//UPLOAD TEACHERS FROM .CSV FILE
	public function upload_teacher($school_id, $file_data) {
		$query_teacher = "INSERT INTO teacher (FirstName, MiddleName, LastName, DateOfBirth, Gender, Email, EmailAlert, MobileNumber, DeskLocation, SchoolId) VALUES (:FirstName, :MiddleName, :LastName, :DateOfBirth, :Gender, :Email, :EmailAlert, :MobileNumber, :DeskLocation, :SchoolId)";
		$query_user = "INSERT INTO user (UserType, TeacherId, AuthorizationCode, Username, Password, PasswordTemp, Email, IsVerified) VALUES (:UserType, :TeacherId, :AuthorizationCode, :Username, :Password, :PasswordTemp, :Email, :IsVerified)";
		
		$date=date('Y-m-d H:i:s');
		$email_alert = 1;
		$user_type = "teacher";
		$code = "98765";
		$is_verified = 0;

		try{
			$db = $this->connect();
			$db->beginTransaction(); 
			$stmt_teacher = $db->prepare($query_teacher);
			$stmt_user = $db->prepare($query_user);
			
			fgetcsv($file_data);  
			while($row = fgetcsv($file_data)) {
				$first_name = $this->data_check($row[0]);
				$middle_name = $this->data_check($row[1]);
				$last_name = $this->data_check($row[2]);
				$dob = $this->data_check($row[3]);
				$gender = $this->data_check($row[4]);
				$email = $this->data_check($row[5]);
				$mobile_number = $this->data_check($row[6]);
				$desk_location = $this->data_check($row[7]);
				
				$dob = $this->format_date($dob);
				
				$stmt_teacher->bindValue(':FirstName', $first_name, PDO::PARAM_STR);
				$stmt_teacher->bindValue(':MiddleName', $middle_name, PDO::PARAM_STR);
				$stmt_teacher->bindValue(':LastName', $last_name, PDO::PARAM_STR);
				$stmt_teacher->bindValue(':DateOfBirth', $dob, PDO::PARAM_STR);
				$stmt_teacher->bindValue(':Gender', $gender, PDO::PARAM_STR);
				$stmt_teacher->bindValue(':Email', $email, PDO::PARAM_STR);
				$stmt_teacher->bindValue(':EmailAlert', $email_alert, PDO::PARAM_INT);
				$stmt_teacher->bindValue(':MobileNumber', $mobile_number, PDO::PARAM_STR);
				$stmt_teacher->bindValue(':DeskLocation', $desk_location, PDO::PARAM_STR);
				$stmt_teacher->bindValue(':SchoolId', $school_id, PDO::PARAM_INT);
				
				$stmt_teacher->execute();
				$teacher_id = $db->lastInsertId();
				$username = $this->get_username($dob, $first_name);
				$password_temp = $this->get_password($dob, $last_name);
				$password = $this->create_hash($password_temp);
				
				$stmt_user->bindValue(':UserType', $user_type, PDO::PARAM_STR);
				$stmt_user->bindValue(':TeacherId', $teacher_id, PDO::PARAM_INT);
				$stmt_user->bindValue(':AuthorizationCode', $code, PDO::PARAM_STR);
				$stmt_user->bindValue(':Username', $username, PDO::PARAM_STR);
				$stmt_user->bindValue(':Password', $password, PDO::PARAM_STR);
				$stmt_user->bindValue(':PasswordTemp', $password_temp, PDO::PARAM_STR);
				$stmt_user->bindValue(':Email', $email, PDO::PARAM_STR);
				$stmt_user->bindValue(':IsVerified', $is_verified, PDO::PARAM_INT);
				
				$stmt_user->execute();
				
			}
			
			$result = $db->commit();
			$this->disconnect($db);
			return $result;
		}
		catch(PDOException $e) {
			$db->rollback();
			$this->disconnect($db);
			return  $e->getMessage();
		}
	}
	
	//UPLOAD STUDENTS AND PARENTS FROM .CSV FILE
	public function upload_student($class_id, $file_data) {
		$query_student = "INSERT INTO student (FirstName, MiddleName, LastName, RollNumber, ClassId, Address, DateOfBirth, Gender) VALUES (:FirstName, :MiddleName, :LastName, :RollNumber, :ClassId, :Address, :DateOfBirth, :Gender);";
		$query_parent = "INSERT INTO parent (FirstName, MiddleName, LastName, Address, Email1, EmailAlert, MobileNumber, StudentId) VALUES (:FirstName, :MiddleName, :LastName, :Address, :Email1, :EmailAlert, :MobileNumber, :StudentId)";
		$query_user = "INSERT INTO user (UserType, ParentId, AuthorizationCode, Username, Password, PasswordTemp, Email, IsVerified) VALUES (:UserType, :ParentId, :AuthorizationCode, :Username, :Password, :PasswordTemp, :Email, :IsVerified)";
		
		$email_alert = 1;
		$user_type = "parent";
		$code = "98765";
		$is_verified = 0;
		
		try{
			$db = $this->connect();
			$db->beginTransaction(); 
			$stmt_student = $db->prepare($query_student);
			$stmt_parent = $db->prepare($query_parent);
			$stmt_user = $db->prepare($query_user);
			
			fgetcsv($file_data);  
			while($row = fgetcsv($file_data)) {
				$roll_number = $this->data_check($row[0]);
				$student_fname = $this->data_check($row[1]);
				$student_mname = $this->data_check($row[2]);
				$student_lname = $this->data_check($row[3]);
				$dob = $this->data_check($row[4]);
				$gender = $this->data_check($row[5]);
				$address = $this->data_check($row[6]);
				$parent_fname = $this->data_check($row[7]);
				$parent_mname = $this->data_check($row[8]);
				$parent_lname = $this->data_check($row[9]);
				$email = $this->data_check($row[10]);
				$mobile_number = $this->data_check($row[11]);
				
				$dob = $this->format_date($dob);
				
				$stmt_student->bindValue(':FirstName', $student_fname, PDO::PARAM_STR);
				$stmt_student->bindValue(':MiddleName', $student_mname, PDO::PARAM_STR);
				$stmt_student->bindValue(':LastName', $student_lname, PDO::PARAM_STR);
				$stmt_student->bindValue(':RollNumber', $roll_number, PDO::PARAM_STR);
				$stmt_student->bindValue(':ClassId', $class_id, PDO::PARAM_INT);
				$stmt_student->bindValue(':Address', $address, PDO::PARAM_STR);
				$stmt_student->bindValue(':DateOfBirth', $dob, PDO::PARAM_STR);
				$stmt_student->bindValue(':Gender', $gender, PDO::PARAM_STR);
				
				$stmt_student->execute();
				$student_id = $db->lastInsertId();
				
				$stmt_parent->bindValue(':FirstName', $parent_fname, PDO::PARAM_STR);
				$stmt_parent->bindValue(':MiddleName', $parent_mname, PDO::PARAM_STR);
				$stmt_parent->bindValue(':LastName', $parent_lname, PDO::PARAM_STR);
				$stmt_parent->bindValue(':Address', $address, PDO::PARAM_STR);
				$stmt_parent->bindValue(':Email1', $email, PDO::PARAM_STR);
				$stmt_parent->bindValue(':EmailAlert', $email_alert, PDO::PARAM_INT);
				$stmt_parent->bindValue(':MobileNumber', $mobile_number, PDO::PARAM_STR);
				$stmt_parent->bindValue(':StudentId', $student_id, PDO::PARAM_INT);
				
				$stmt_parent->execute();
				$parent_id = $db->lastInsertId();

				$username = $this->get_username($dob, $parent_fname);
				$password_temp = $this->get_password($dob, $parent_lname);
				$password = $this->create_hash($password_temp);
				
				$stmt_user->bindValue(':UserType', $user_type, PDO::PARAM_STR);
				$stmt_user->bindValue(':ParentId', $parent_id, PDO::PARAM_INT);
				$stmt_user->bindValue(':AuthorizationCode', $code, PDO::PARAM_STR);
				$stmt_user->bindValue(':Username', $username, PDO::PARAM_STR);
				$stmt_user->bindValue(':Password', $password, PDO::PARAM_STR);
				$stmt_user->bindValue(':PasswordTemp', $password_temp, PDO::PARAM_STR);
				$stmt_user->bindValue(':Email', $email, PDO::PARAM_STR);
				$stmt_user->bindValue(':IsVerified', $is_verified, PDO::PARAM_INT);
				
				$stmt_user->execute();		
			}
			
			$result = $db->commit();
			$this->disconnect($db);
			return $result;
		}
		catch(PDOException $e) {
			$db->rollback();
			$this->disconnect($db);
			return  $e->getMessage();
		}
	}
	
	public function add_class($classes, $school_id) {
		$query = "INSERT INTO class (SchoolId, ClassroomId, Location, AcademicYear) VALUES (:SchoolId, :ClassroomId, :Location, :AcademicYear)";
		$date=date('Y-m-d H:i:s');
		$location = "NA";
		$academic_year = date('Y');
		
		try{
			$db = $this->connect();
			$db->beginTransaction(); 
			$stmt = $db->prepare($query);

			foreach ($classes as $classroom_id) {
				$stmt->bindValue(':SchoolId', $school_id, PDO::PARAM_INT);
				$stmt->bindValue(':ClassroomId', $classroom_id, PDO::PARAM_INT);
				$stmt->bindValue(':Location', $location, PDO::PARAM_STR);
				$stmt->bindValue(':AcademicYear', $academic_year, PDO::PARAM_STR);

				$stmt->execute();
			}
			$result = $db->commit();
			$this->disconnect($db);
			return $result;
		}
		catch(PDOException $e) {
			$db->rollback();
			$this->disconnect($db);
			return  $e->getMessage();
		}
	}
	
	//GET list of classrooms
	public function get_classroom(){
		$query = "SELECT Id, Name FROM classroom WHERE 1 ORDER BY Name";
		$db = $this->connect();
		$stmt = $db->prepare($query);
		$stmt->execute();
		$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$json = json_encode( $rows );
		$this->disconnect($db);
		return $json;
	}
	
	//GET list of classes in school
	public function get_school_class($school_id) {
		$query = "SELECT class.Id, classroom.Name FROM classroom INNER JOIN class ON class.ClassroomId = classroom.Id WHERE class.SchoolId = :SchoolId ORDER BY Name";
		$db = $this->connect();
		$stmt = $db->prepare($query);
		$stmt->bindValue(':SchoolId', $school_id, PDO::PARAM_INT);
		$stmt->execute();
		$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$json = json_encode( $rows );
		$this->disconnect($db);
		return $json;
	}
	
	//GET list of teachers in school
	public function get_school_teacher($school_id) {
		$query = "SELECT Id, CONCAT(FirstName, ' ', LastName) AS TeacherName FROM teacher WHERE SchoolId = :SchoolId ORDER BY LastName";
		$db = $this->connect();
		$stmt = $db->prepare($query);
		$stmt->bindValue(':SchoolId', $school_id, PDO::PARAM_INT);
		$stmt->execute();
		$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$json = json_encode( $rows );
		$this->disconnect($db);
		return $json;
	}
	
	//GET details of all teachers in school
	public function get_school_teachers($school_id) {
		$query = "SELECT teacher.FirstName, teacher.MiddleName, teacher.LastName, teacher.DateOfBirth, teacher.Gender, teacher.Email, teacher.MobileNumber, classroom.Name  FROM teacher LEFT JOIN teacherclassmap on teacher.Id = teacherclassmap.TeacherId LEFT JOIN class ON class.Id = teacherclassmap.ClassId LEFT JOIN classroom ON classroom.Id = class.ClassroomId WHERE teacher.SchoolId = :SchoolId ORDER BY teacher.LastName";
		$db = $this->connect();
		$stmt = $db->prepare($query);
		$stmt->bindValue(':SchoolId', $school_id, PDO::PARAM_INT);
		$stmt->execute();
		$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$json = json_encode( $rows );
		$this->disconnect($db);
		return $json;
	}
	
	//GET lists of classes and teachers in schools
	public function get_class_and_teacher($school_id) {
		$result = array();
		
		$query_class = "SELECT class.Id, classroom.Name FROM classroom INNER JOIN class ON class.ClassroomId = classroom.Id WHERE class.SchoolId = :SchoolId ORDER BY Name";
		$query_teacher = "SELECT Id, CONCAT(FirstName, ' ', LastName) AS TeacherName FROM teacher WHERE SchoolId = :SchoolId ORDER BY LastName";
		
		$db = $this->connect();
		
		$stmt_class = $db->prepare($query_class);
		$stmt_class->bindValue(':SchoolId', $school_id, PDO::PARAM_INT);
		
		$stmt_teacher = $db->prepare($query_teacher);
		$stmt_teacher->bindValue(':SchoolId', $school_id, PDO::PARAM_INT);
		
		$stmt_class->execute();
		$stmt_teacher->execute();
		
		$result['cls'] = $stmt_class->fetchAll(PDO::FETCH_ASSOC);
		$result['teacher'] = $stmt_teacher->fetchAll(PDO::FETCH_ASSOC);
		$json = json_encode( $result );
		$this->disconnect($db);
		return $json;
	}
	
	//ASSIGN teacher to a class
	public function assign_teacher($class_id, $teacher_id) {
		$query = "INSERT INTO teacherclassmap (TeacherId, ClassId) VALUES (:TeacherId, :ClassId) ON DUPLICATE KEY UPDATE ClassId = :ClassId1 ";
		
		try {
			$db = $this->connect();
			$stmt = $db->prepare($query);
			$stmt->bindValue(':ClassId', $class_id, PDO::PARAM_INT);
			$stmt->bindValue(':TeacherId', $teacher_id, PDO::PARAM_INT);
			$stmt->bindValue(':ClassId1', $class_id, PDO::PARAM_INT);
			$result = $stmt->execute();
			$this->disconnect($db);
			return $result;
		} catch (PDOEXCEPTION $e) {
			return  $e->getMessage();
		}
	}
	
	//ADD SCHOOL
	public function add_school($name, $reg_number, $year, $website, $address, $city, $pin, $phone, $email, $username, $passwd) {
		$query_school = "INSERT INTO school (Name, EstablishmentYear, RegistrationNumber, Address, City, PinCode, PhoneNumber, Email, Website) VALUES (:Name, :EstablishmentYear, :RegistrationNumber, :Address, :City, :PinCode, :PhoneNumber, :Email, :Website);";
		
		$query_admin = "INSERT INTO admin (Email, MobileNumber, SchoolId) VALUES (:Email, :MobileNumber, :SchoolId);";
		
		$query_user = "INSERT INTO user (UserType, AdminId, AuthorizationCode, Username, Password, PasswordTemp, Email, IsVerified) VALUES (:UserType, :AdminId, :AuthorizationCode, :Username, :Password, :PasswordTemp, :Email, :IsVerified)";
		
		$email_alert = 1;
		$user_type = "admin";
		$code = "98765";
		$is_verified = 0;
		
		try{
			$db = $this->connect();
			$db->beginTransaction(); 
			$stmt_school = $db->prepare($query_school);
			$stmt_admin = $db->prepare($query_admin);
			$stmt_user = $db->prepare($query_user);
			 
			$stmt_school->bindValue(':Name', $name, PDO::PARAM_STR);
			$stmt_school->bindValue(':EstablishmentYear', $year, PDO::PARAM_STR);
			$stmt_school->bindValue(':RegistrationNumber', $reg_number, PDO::PARAM_STR);
			$stmt_school->bindValue(':Address', $address, PDO::PARAM_STR);
			$stmt_school->bindValue(':City', $city, PDO::PARAM_STR);
			$stmt_school->bindValue(':PinCode', $pin, PDO::PARAM_STR);
			$stmt_school->bindValue(':PhoneNumber', $phone, PDO::PARAM_STR);
			$stmt_school->bindValue(':Email', $email, PDO::PARAM_STR);
			$stmt_school->bindValue(':Website', $website, PDO::PARAM_STR);

			$stmt_school->execute();
			$school_id = $db->lastInsertId();

			$stmt_admin->bindValue(':Email', $email, PDO::PARAM_STR);
			$stmt_admin->bindValue(':MobileNumber', $phone, PDO::PARAM_STR);
			$stmt_admin->bindValue(':SchoolId', $school_id, PDO::PARAM_INT);

			$stmt_admin->execute();
			$admin_id = $db->lastInsertId();

			$password_temp = $passwd;
			$password = $this->create_hash($password_temp);

			$stmt_user->bindValue(':UserType', $user_type, PDO::PARAM_STR);
			$stmt_user->bindValue(':AdminId', $admin_id, PDO::PARAM_INT);
			$stmt_user->bindValue(':AuthorizationCode', $code, PDO::PARAM_STR);
			$stmt_user->bindValue(':Username', $username, PDO::PARAM_STR);
			$stmt_user->bindValue(':Password', $password, PDO::PARAM_STR);
			$stmt_user->bindValue(':PasswordTemp', $password_temp, PDO::PARAM_STR);
			$stmt_user->bindValue(':Email', $email, PDO::PARAM_STR);
			$stmt_user->bindValue(':IsVerified', $is_verified, PDO::PARAM_INT);

			$stmt_user->execute();		
			
			
			$result = $db->commit();
			$this->disconnect($db);
			return $result;
		}
		catch(PDOException $e) {
			$db->rollback();
			$this->disconnect($db);
			return  $e->getMessage();
		}
	}
	
	//GALLARY
	public function get_album($school_id) {
		$query = "SELECT Id, Name, Thumbnail FROM album WHERE SchoolId = :SchoolId";
		$db = $this->connect();
		$stmt = $db->prepare($query);
		$stmt->bindValue(':SchoolId', $school_id, PDO::PARAM_INT);
		$stmt->execute();
		$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$json = json_encode( $rows );
		$this->disconnect($db);
		return $json;
	}
	
	public function add_album($name, $school_id) {
		$query = "INSERT INTO album (Name, SchoolId, CreatedOn) VALUES (:Name, :SchoolId, :CreatedOn)";
		$date=date('Y-m-d H:i:s');
		
		try {
			$db = $this->connect();
			$stmt = $db->prepare($query);
			$stmt->bindValue(':Name', $name, PDO::PARAM_STR);
			$stmt->bindValue(':SchoolId', $school_id, PDO::PARAM_INT);
			$stmt->bindValue(':CreatedOn', $date, PDO::PARAM_STR);
			$result = $stmt->execute();
			$this->disconnect($db);
			return $result;
		} catch (PDOEXCEPTION $e) {
			return  $e->getMessage();
		}
	}

	public function get_gallery($album_id) {
		$query = "SELECT FileName, DbFileName FROM photo WHERE AlbumId = :AlbumId";
		$db = $this->connect();
		$stmt = $db->prepare($query);
		$stmt->bindValue(':AlbumId', $album_id, PDO::PARAM_INT);
		$stmt->execute();
		$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$json = json_encode( $rows );
		$this->disconnect($db);
		return $json;
	}
	
	public function add_photo($album_id, $files) {
		$query = "INSERT INTO photo (AlbumId, FileName, DbFileName) VALUES (:AlbumId, :FileName, :DbFileName)";
		
		$query_thumbnail = "UPDATE album SET Thumbnail = :Thumbnail WHERE Id = :Id ";
		
		$is_set_thumbnail = false;
		
		try{
			$db = $this->connect();
			$db->beginTransaction(); 
			$stmt = $db->prepare($query);
			$stmt_thumbnail = $db->prepare($query_thumbnail);
			
			foreach($files['tmp_name'] as $key => $tmp_name) {
				
				$file_ext = pathinfo($files['name'][$key], PATHINFO_EXTENSION);
				$micro_time = round(microtime(true));
				$random_number = rand(0, 9999);
				$new_name = $micro_time . $random_number . '.' . $file_ext;
				
				$stmt->bindValue(':AlbumId', $album_id, PDO::PARAM_INT);
				$stmt->bindValue(':FileName', $files['name'][$key], PDO::PARAM_STR);
				$stmt->bindValue(':DbFileName', $new_name, PDO::PARAM_STR);
				
				$stmt->execute();
				
				if (! $is_set_thumbnail) {
					$stmt_thumbnail->bindValue(':Thumbnail', $new_name, PDO::PARAM_STR);
					$stmt_thumbnail->bindValue(':Id', $album_id, PDO::PARAM_INT);
					$stmt_thumbnail->execute();
					$is_set_thumbnail = true;
				}
				move_uploaded_file($files["tmp_name"][$key], "../photos/" . $new_name);
				
			}
			$result = $db->commit();
			$this->disconnect($db);
			return $result;
		}
		catch(PDOException $e) {
			$db->rollback();
			$this->disconnect($db);
			return  $e->getMessage();
		}
	}
	
	//GET lists of classes and subjects in schools
	public function get_class_and_subject($school_id) {
		$result = array();
		
		$query_class = "SELECT class.Id, classroom.Name FROM classroom INNER JOIN class ON class.ClassroomId = classroom.Id WHERE class.SchoolId = :SchoolId ORDER BY Name";
		$query_subject = "SELECT Id, Name FROM subject WHERE 1";
		
		$db = $this->connect();
		
		$stmt_class = $db->prepare($query_class);
		$stmt_class->bindValue(':SchoolId', $school_id, PDO::PARAM_INT);
		
		$stmt_subject = $db->prepare($query_subject);
		
		$stmt_class->execute();
		$stmt_subject->execute();
		
		$result['cls'] = $stmt_class->fetchAll(PDO::FETCH_ASSOC);
		$result['subject'] = $stmt_subject->fetchAll(PDO::FETCH_ASSOC);
		$json = json_encode( $result );
		$this->disconnect($db);
		return $json;
	}
	
	//ADD subjects
	public function add_subjects($class_id, $subjects) {
		$query = "INSERT INTO class_subteach_map (ClassId, SubjectId, TeacherId, TeacherIdNA) VALUES (:ClassId, :SubjectId, :TeacherId, :TeacherIdNA)";
		
		$teacher_id = $this->get_class_teacher($class_id);
		
		try{
			$db = $this->connect();
			$db->beginTransaction(); 
			$stmt = $db->prepare($query);

			foreach ($subjects as $subject_id) {
				$stmt->bindValue(':ClassId', $class_id, PDO::PARAM_INT);
				$stmt->bindValue(':SubjectId', $subject_id, PDO::PARAM_INT);
				$stmt->bindValue(':TeacherId', $teacher_id, PDO::PARAM_INT);
				$stmt->bindValue(':TeacherIdNA', $teacher_id, PDO::PARAM_INT);
				$stmt->execute();
			}
			$result = $db->commit();
			$this->disconnect($db);
			return $result;
		}
		catch(PDOException $e) {
			$db->rollback();
			$this->disconnect($db);
			return  $e->getMessage();
		}
	}
	
	
	//GET lists of classes and Exams in schools
	public function get_class_and_exam($school_id) {
		$result = array();
		
		$query_class = "SELECT class.Id, classroom.Name FROM classroom INNER JOIN class ON class.ClassroomId = classroom.Id WHERE class.SchoolId = :SchoolId ORDER BY Name";
		$query_exam = "SELECT Id, Name FROM exam WHERE 1";
		
		$db = $this->connect();
		
		$stmt_class = $db->prepare($query_class);
		$stmt_class->bindValue(':SchoolId', $school_id, PDO::PARAM_INT);
		
		$stmt_exam = $db->prepare($query_exam);
		
		$stmt_class->execute();
		$stmt_exam->execute();
		
		$result['cls'] = $stmt_class->fetchAll(PDO::FETCH_ASSOC);
		$result['exam'] = $stmt_exam->fetchAll(PDO::FETCH_ASSOC);
		
		$json = json_encode( $result );
		$this->disconnect($db);
		return $json;
	}
	
	private function get_class_ClassSubTeachMapId($dbh, $class_id) {
		$db = $dbh;
		$query = "SELECT Id FROM class_subteach_map WHERE ClassId = :ClassId";
		$stmt = $db->prepare($query);
		$stmt->bindValue(':ClassId', $class_id, PDO::PARAM_INT);
		$stmt->execute();
		$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
		return $rows;
	}
	
	public function add_exams($class_id, $exams) {
		$query = "INSERT INTO examsubjectmap (ExamId, ClassSubTeachMapId) VALUES (:ExamId, :ClassSubTeachMapId)";
		
		try{
			$db = $this->connect();
			$db->beginTransaction(); 
			$stmt = $db->prepare($query);
			$class_sub_maps = $this->get_class_ClassSubTeachMapId($db, $class_id);

			foreach ($exams as $exam_id) {
				foreach ($class_sub_maps as $class_sub_map_id) {
					$stmt->bindValue(':ExamId', $exam_id, PDO::PARAM_INT);
					$stmt->bindValue(':ClassSubTeachMapId', $class_sub_map_id['Id'], PDO::PARAM_INT);
					$stmt->execute();
				}
			}
			$result = $db->commit();
			$this->disconnect($db);
			return $result;
		}
		catch(PDOException $e) {
			$db->rollback();
			$this->disconnect($db);
			return  $e->getMessage();
		}
	}
	
	
	public function cancel_meeting($list) {
		$query = "UPDATE individualmeeting SET Status = 3 WHERE Id = :Id";
		
		try{
			$db = $this->connect();
			$db->beginTransaction(); 
			$stmt = $db->prepare($query);

			foreach ($list as $id) {
				$stmt->bindValue(':Id', $id, PDO::PARAM_INT);
				$stmt->execute();
			}
			$result = $db->commit();
			$this->disconnect($db);
			return $result;
		}
		catch(PDOException $e) {
			$db->rollback();
			$this->disconnect($db);
			return  $e->getMessage();
		}
	}
	
	public function cancel_event($list) {
		$query = "UPDATE event SET Status = 3 WHERE Id = :Id";
		
		try{
			$db = $this->connect();
			$db->beginTransaction(); 
			$stmt = $db->prepare($query);

			foreach ($list as $id) {
				$stmt->bindValue(':Id', $id, PDO::PARAM_INT);
				$stmt->execute();
			}
			$result = $db->commit();
			$this->disconnect($db);
			return $result;
		}
		catch(PDOException $e) {
			$db->rollback();
			$this->disconnect($db);
			return  $e->getMessage();
		}
	}
	
	public function cancel_leave($list) {
		$query = "UPDATE leaverequest SET Status = 3 WHERE Id = :Id";
		
		try{
			$db = $this->connect();
			$db->beginTransaction(); 
			$stmt = $db->prepare($query);

			foreach ($list as $id) {
				$stmt->bindValue(':Id', $id, PDO::PARAM_INT);
				$stmt->execute();
			}
			$result = $db->commit();
			$this->disconnect($db);
			return $result;
		}
		catch(PDOException $e) {
			$db->rollback();
			$this->disconnect($db);
			return  $e->getMessage();
		}
	}
	
	//SEND SMS
	public function get_class_parent_mobile($class_id) {
		$query = "SELECT GROUP_CONCAT(MobileNumber SEPARATOR ', ') AS MobileNumber FROM parent INNER JOIN student ON student.Id = parent.StudentId WHERE ClassId = :ClassId AND EmailAlert = 1";
		$db = $this->connect();
		$stmt = $db->prepare($query);
		$stmt->bindValue(':ClassId', $class_id, PDO::PARAM_INT);
		$stmt->execute();
		$rows = $stmt->fetch(PDO::FETCH_ASSOC);
		$this->disconnect($db);
		return $rows['MobileNumber'];
	}
	
	public function get_student_parent_mobile($student_id) {
		$query = "SELECT GROUP_CONCAT(MobileNumber SEPARATOR ', ') AS MobileNumber FROM parent WHERE StudentId = :StudentId AND EmailAlert = 1";
		$db = $this->connect();
		$stmt = $db->prepare($query);
		$stmt->bindValue(':StudentId', $student_id, PDO::PARAM_INT);
		$stmt->execute();
		$rows = $stmt->fetch(PDO::FETCH_ASSOC);
		$this->disconnect($db);
		return $rows['MobileNumber'];
	}
	
	public function get_students_parent_mobile($students) {
		$ids = implode(',', array_fill(0, count($students), '?'));
		$query = "SELECT GROUP_CONCAT(MobileNumber SEPARATOR ', ') AS MobileNumber FROM parent WHERE StudentId IN (".$ids.") AND EmailAlert = 1";
		$db = $this->connect();
		$stmt = $db->prepare($query);
		$stmt->execute($students);
		$rows = $stmt->fetch(PDO::FETCH_ASSOC);
		$this->disconnect($db);
		return $rows['MobileNumber'];
	}
	
	public function get_teacher_mobile($class_id) {
		$query = "SELECT MobileNumber FROM teacher INNER JOIN teacherclassmap ON teacherclassmap.TeacherId = teacher.Id WHERE ClassId = :ClassId AND EmailAlert = 1";
		$db = $this->connect();
		$stmt = $db->prepare($query);
		$stmt->bindValue(':ClassId', $class_id, PDO::PARAM_INT);
		$stmt->execute();
		$rows = $stmt->fetch(PDO::FETCH_ASSOC);
		$this->disconnect($db);
		return $rows['MobileNumber'];
	}
	
	public function change_password($username, $passwd) {
		$query="UPDATE user SET Password = :Password, PasswordTemp = :PasswordTemp WHERE Username = :Username";
		
		try {
			$db = $this->connect();
			$stmt = $db->prepare($query);
			$hash = $this->create_hash($passwd);
			$stmt->bindValue(':Password', $hash, PDO::PARAM_STR);
			$stmt->bindValue(':PasswordTemp', $passwd, PDO::PARAM_STR);
			$stmt->bindValue(':Username', $username, PDO::PARAM_STR);
			$result = $stmt->execute();
			$this->disconnect($db);
			return $result;
		} catch (PDOEXCEPTION $e) {
			return  $e->getMessage();
		}
	}
	
	public function reset_password($id, $passwd) {
		$query="UPDATE user SET Password = :Password, PasswordTemp = :PasswordTemp WHERE Id = :Id";
		
		try {
			$db = $this->connect();
			$stmt = $db->prepare($query);
			$hash = $this->create_hash($passwd);
			$stmt->bindValue(':Password', $hash, PDO::PARAM_STR);
			$stmt->bindValue(':PasswordTemp', $passwd, PDO::PARAM_STR);
			$stmt->bindValue(':Id', $id, PDO::PARAM_INT);
			$result = $stmt->execute();
			$this->disconnect($db);
			return $result;
		} catch (PDOEXCEPTION $e) {
			return  $e->getMessage();
		}
	}
	
	
	
}
?>

