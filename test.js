/*jslint node: true */
/*jslint browser: true*/
/*global $, jQuery, alert*/

"use strict";

function getDayName(dateString) {
    return ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"][new Date(dateString).getDay()];
}

function daysInMonth(month, year) {
    return new Date(year, month, 0).getDate();
}

function getStatus(code) {
	if (code === '1') {
		return "Approved";
	} else if (code === '2') {
		return "Rejected";
	} else if (code === '0') {
		return "Pending";
	} else if (code === '3') {
		return "Cancelled";
	} else {
		return "Incorrect Status";
	}
}

function isInPast(date) {
	var dateObj, curDate;
	dateObj = new Date(date);
	curDate = new Date();
	if (dateObj < curDate) {
		return 1;
	} else {
		return 0;
	}
}

function isInFuture(date) {
	var dateObj, curDate;
	dateObj = new Date(date);
	curDate = new Date();
	if (dateObj > curDate) {
		return 1;
	} else {
		return 0;
	}
}

function getMonthlyAbsentCount(attendance, month) {
	var count = 0, i = 0;
	for (i = 0; i < attendance.length; i += 1) {
        if (attendance[i].Month === month) {
            count += 1;
        }
    }
    return count;
}

function getMonthVal(month) {
	if (month === 5 || month === 6 || month === 7 || month === 8 || month === 9 || month === 10 || month === 11) {
		return 0;
	} else {
		return 1;
	}
}

function getYear(month) {
	var d, curMonthVal, monthVal, year;
	d = new Date();
	year = d.getFullYear();
	curMonthVal = getMonthVal(d.getMonth());
	monthVal = getMonthVal(month - 1);
	if (curMonthVal === monthVal) {
		return year;
	} else if (curMonthVal > monthVal) {
		return year - 1;
	} else {
		return year + 1;
	}
}

function getFormattedDate(date) {
	var year, month, day;
	year = date.getFullYear();
    month = (1 + date.getMonth()).toString();
    month = month.length > 1 ? month : '0' + month;
    day = date.getDate().toString();
    day = day.length > 1 ? day : '0' + day;
    return year + '-' + month + '-' + day;
}

function isHoliday(holiday, date) {
	var i;
	console.log(date);
	for (i = 0; i < holiday.length; i += 1) {
        if (holiday[i].Date === date) {
            return true;
        }
    }
	return false;
}

/*
//To match height to maximum div height
$(document).ready(function () {
	var maxheight = 0;
	$('.box-content-wrapper').each(function () {
		maxheight = ($(this).height() > maxheight ? $(this).height() : maxheight);
	});
	$('.box-content-wrapper').css('min-height', maxheight);
});
*/
//Function to take care of sidebar toggle
$(document).ready(function () {
    $('[data-toggle="offcanvas-left"]').click(function () {
        $('.row-offcanvas-left').toggleClass('active');
    });
});

//Function to take care of notification bar toggle
$(document).ready(function () {
    $('[data-toggle="offcanvas-right"]').click(function () {
        $('.row-offcanvas-right').toggleClass('active');
    });
});

//Highlight Selected sidebar-nav option
$(document).ready(function () {
	$('ul.sidebar-nav li a').click(function () {
		$(this).parent().parent().find('a.sidebar-active').removeClass('sidebar-active');
		$(this).addClass('sidebar-active');
	});
});

//Function to display grades on click
$(document).ready(function () {
    $("#nav-grade").click(function () {
        $(".toggle-nav").hide();
        $("#grade").show();
    });
});

//Function to display events on click
$(document).ready(function () {
    $("#nav-event").click(function () {
        $(".toggle-nav").hide();
        $("#event").show();
    });
});

//Function to display announcements on click
$(document).ready(function () {
    $("#nav-announcement").click(function () {
        $(".toggle-nav").hide();
        $("#announcement").show();
    });
});

//Function to display notices on click
$(document).ready(function () {
    $("#nav-notice").click(function () {
        $(".toggle-nav").hide();
        $("#notice").show();
    });
});

//Function to display homework on click
$(document).ready(function () {
    $("#nav-homework").click(function () {
        $(".toggle-nav").hide();
        $("#homework").show();
    });
});

//Function to display attendance on click
$(document).ready(function () {
    $("#nav-attendance").click(function () {
        $(".toggle-nav").hide();
        $("#attendance").show();
    });
});

//Function to display meeting on click
$(document).ready(function () {
    $("#nav-meeting").click(function () {
        $(".toggle-nav").hide();
        $("#meeting").show();
    });
});

//Function to display leave on click
$(document).ready(function () {
    $("#nav-leave").click(function () {
        $(".toggle-nav").hide();
        $("#leave").show();
    });
});

//Function to display need attention on click
$(document).ready(function () {
    $("#nav-attention").click(function () {
        $(".toggle-nav").hide();
        $("#attention").show();
    });
});

//Function to display tinfo on click
$(document).ready(function () {
    $("#nav-tinfo").click(function () {
        $(".toggle-nav").hide();
        $("#tinfo").show();
    });
});

//Function to handle meeting date picker
$(function () {
	$('#meeting-date').datepicker({
		autoclose: true,
		format: "yyyy-mm-dd",
		daysOfWeekDisabled: "0",
		orientation: "bottom"
	});
});

//Function to handle leave date picker
$(function () {
	$('#leave-date').datepicker({
		autoclose: true,
		format: "yyyy-mm-dd",
		daysOfWeekDisabled: "0",
		orientation: "bottom"
	});
});

//Initialise timepickers
$(function () {
	$('#meeting-start-time').timepicker();
	$('#meeting-end-time').timepicker();
});



/*************BACKEND CODE BELOW***************/
//To display jason object- var result = JSON.stringify(response);

//Session Check
$(document).ready(function () {
	$.ajax({
		type: "GET",
		url: "php/session.php",
		//async: false,
		cache: false,
		success: function (response) {
			response = $.trim(response);
			if (response !== 'parent') {
				window.location = "index.html";
			}
		}
	});
});

//Handle day-date display
$(document).ready(function () {
	var d = new Date();
	$("#day-date").css('display', 'inline', 'important');
	$("#day-date").html(d.toDateString());
});

//Handle Logout
$(document).ready(function () {
	$('#logout').click(function (e) {
		e.preventDefault();
		$.ajax({
			type: "POST",
			url: "php/logout.php",
			success: function (response) {
				window.location = "index.html";
			}
		});
	});
});

//Handle get Announcements
$(document).ready(function () {
	$('#nav-announcement').on('click', function (e) {
		e.preventDefault();
		$.ajax({
			type: "GET",
			url: "php/get_handle.php",
			data: "func=get_announcements",
			dataType: 'json',
			success: function (response) {
				var row, result, rowObj, ann = '', date, attachment;
				if (response.length === 0) {
					ann = ann +
						'<div class="container-fluid post-empty">' +
						'<p>' + 'No Announcement posted yet.' + '</p></div>';
				} else {
					for (row in response) {
						if (response.hasOwnProperty(row)) {
							rowObj = response[row];
							date = rowObj.CreatedOn.substr(0, 10);
							if (rowObj.FileName === null) {
								attachment = "No Attachment";
							} else {
								attachment = '<a href="files/announcement/' + rowObj.DbFileName + '" download="' + rowObj.FileName + '">' + rowObj.FileName + '</a>';
							}
							ann = ann +
								'<div class="container-fluid post-nobox">' +
								'<div class="row post-header">' +
								'<div class="col-sm-12"><p id="get-ann-subject">' + rowObj.Title + '</p></div></div>' +
								'<div class="row"><div class="col-sm-3"><p>Posted Date:</p></div>' +
								'<div class="col-sm-9"><p id="get-ann-date">' + date + '</p>' +
								'</div></div>' +
								'<div class="row"><div class="col-sm-3"><p>Posted by: </p></div>' +
								'<div class="col-sm-9"><p id="get-ann-postedby">' + rowObj.FirstName + " " + rowObj.LastName + '</p></div></div>' +
								'<div class="row"><div class="col-sm-3"><p>Description: </p></div>' +
								'<div class="col-sm-9"><p id="get-ann-description">' + rowObj.Description + '</p></div></div>' +
								'<div class="row"><div class="col-sm-3"><p>Attachment:</p></div>' +
								'<div class="col-xs-9"><p id="get-ann-attachment">' + attachment + '</p>' +
								'</div></div></div>' +
								'<hr style="width: 95%; color: darkgray; height: 1px; background-color: darkgray;"/>';
						}
					}
				}
				$("#display-announcements").css('display', 'inline', 'important');
				$("#display-announcements").html(ann);
			}
		});
	});
});

//Handle get Notices
$(document).ready(function () {
	$('#nav-notice').on('click', function (e) {
		e.preventDefault();
		$.ajax({
			type: "GET",
			url: "php/get_handle.php",
			data: "func=get_parent_notices",
			dataType: 'json',
			success: function (response) {
				var row, rowObj, ntc = '', date, attachment;
				if (response.length === 0) {
					ntc = ntc +
						'<div class="container-fluid post-empty">' +
						'<p>' + 'No Notices posted yet.' + '</p></div>';
					
				} else {
					for (row in response) {
						if (response.hasOwnProperty(row)) {
							rowObj = response[row];
							date = rowObj.CreatedOn.substr(0, 10);
							if (rowObj.FileName === null) {
								attachment = "No Attachment";
							} else {
								attachment = '<a href="files/notice/' + rowObj.DbFileName + '" download="' + rowObj.FileName + '">' + rowObj.FileName + '</a>';
							}
							ntc = ntc +
								'<div class="container-fluid post-nobox"><div class="row post-header"><div class="col-sm-12">' +
								'<p>' + rowObj.Title + '</p></div></div>' +
								'<div class="row"><div class="col-sm-3"><p>Posted Date:</p></div>' +
								'<div class="col-sm-9"><p>' + date + '</p></div></div>' +
								'<div class="row"><div class="col-sm-3"><p>Posted by: </p></div>' +
								'<div class="col-sm-9"><p>' + rowObj.TeacherName + '</p></div></div>' +
								'<div class="row"><div class="col-sm-3"><p>Student Name: </p></div>' +
								'<div class="col-sm-9"><p>' + rowObj.StudentName + '</p></div></div>' +
								'<div class="row"><div class="col-sm-3"><p>Description: </p></div>'  +
								'<div class="col-sm-9"><p>' + rowObj.Description + '</p></div></div>' +
								'<div class="row"><div class="col-sm-3"><p>Attachment: </p></div>' +
								'<div class="col-sm-9"><p>' + attachment + '</p></div></div></div>' +
								'<hr style="width: 95%; color: darkgray; height: 1px; background-color: darkgray;"/>';
						}
					}
				}
				$("#display-notices").css('display', 'inline', 'important');
				$("#display-notices").html(ntc);
			}
		});
	});
});

//Handle get Due Homework
$(document).ready(function () {
	$('#nav-homework, #get-due-homework').on('click', function (e) {
		e.preventDefault();
		$.ajax({
			type: "GET",
			url: "php/get_handle.php",
			data: "func=get_due_homeworks",
			dataType: 'json',
			success: function (response) {
				var row, rowObj, dhw = '', date, attachment;
				if (response.length === 0) {
					dhw = dhw +
						'<div class="container-fluid post-empty">' +
						'<p>' + 'No Due Homeworks.' + '</p></div>';
				} else {
					for (row in response) {
						if (response.hasOwnProperty(row)) {
							rowObj = response[row];
							date = rowObj.CreatedOn;
							if (rowObj.FileName === null) {
								attachment = "No Attachment";
							} else {
								attachment = '<a href="files/homework/' + rowObj.DbFileName + '" download="' + rowObj.FileName + '">' + rowObj.FileName + '</a>';
							}
							dhw = dhw +
								'<div class="container-fluid post-nobox"><div class="row post-header">' +
								'<div class="col-sm-12"><p>' + rowObj.Topic + '</p></div></div>' +
								'<div class="row"><div class="col-sm-3"><p>Due On:</p></div>' +
								'<div class="col-sm-9"><p>' + rowObj.DueDate + " at " + rowObj.DueTime + '</p></div></div>' +
								'<div class="row"><div class="col-sm-3"><p>Subject:</p></div>' +
								'<div class="col-sm-9"><p>' + rowObj.Subject + '</p></div></div>' +
								'<div class="row"><div class="col-sm-3"><p>Description:</p></div>' +
								'<div class="col-sm-9"><p>' + rowObj.Description + '</p></div></div>' +
								'<div class="row"><div class="col-sm-3"><p>Attachment:</p></div>' +
								'<div class="col-sm-9"><p>' + attachment + '</p></div></div></div>' +
								'<hr style="width: 100%; color: darkgray; height: 1px; background-color: darkgray;"/>';
						}
					}
				}
				$("#display-due-homework").css('display', 'inline', 'important');
				$("#display-due-homework").html(dhw);
			}
		});
	});
});

//Handle get Past Homework
$(document).ready(function () {
	$('#get-homework').on('shown.bs.tab', function (e) {
		e.preventDefault();
		$.ajax({
			type: "GET",
			url: "php/get_handle.php",
			data: "func=get_past_homeworks",
			dataType: 'json',
			success: function (response) {
				var row, rowObj, phw = '', date, attachment;
				if (response.length === 0) {
					phw = phw +
						'<div class="container-fluid post-empty">' +
						'<p>' + 'No past Homework yet.' + '</p></div>';
				} else {
					for (row in response) {
						if (response.hasOwnProperty(row)) {
							rowObj = response[row];
							date = rowObj.CreatedOn;
							if (rowObj.FileName === null) {
								attachment = "No Attachment";
							} else {
								attachment = '<a href="files/homework/' + rowObj.DbFileName + '" download="' + rowObj.FileName + '">' + rowObj.FileName + '</a>';
							}
							phw = phw +
								'<div class="container-fluid post-nobox"><div class="row post-header">' +
								'<div class="col-sm-12"><p>' + rowObj.Topic + '</p></div></div>' +
								'<div class="row"><div class="col-sm-3"><p>Due On:</p></div>' +
								'<div class="col-sm-9"><p>' + rowObj.DueDate + " at " + rowObj.DueTime + '</p></div></div>' +
								'<div class="row"><div class="col-sm-3"><p>Subject:</p></div>' +
								'<div class="col-sm-9"><p>' + rowObj.Subject + '</p></div></div>' +
								'<div class="row"><div class="col-sm-3"><p>Description:</p></div>' +
								'<div class="col-sm-9"><p>' + rowObj.Description + '</p></div></div>' +
								'<div class="row"><div class="col-sm-3"><p>Attachment:</p></div>' +
								'<div class="col-sm-9"><p>' + attachment + '</p></div></div></div>' +
								'<hr style="width: 100%; color: darkgray; height: 1px; background-color: darkgray;"/>';
						}
					}
				}
				$("#display-past-homework").css('display', 'inline', 'important');
				$("#display-past-homework").html(phw);
			}
		});
	});
});

//Reset operation on leave div
$(document).ready(function () {
	$('#nav-leave, #new-leave-tab').on('click', function (e) {
		e.preventDefault();
		$("form").trigger("reset");
		$("#post-leave-msg").css('display', 'inline', 'important');
		$("#post-leave-msg").html("");
	});
});

//Handle Post leave
$(document).ready(function () {
	$('#post-leave').submit(function (e) {
		e.preventDefault();
		var form_data = $(this).serialize();
		$.ajax({
			type: "POST",
			url: "php/post_leave.php",
			data: form_data,
			dataType: 'json',
			success: function (response) {
				//console.log(response);
				if (!response.success) {
					if (response.errors.db) {
						$("#post-leave-msg").html(response.errors.db);
					}
					if (response.errors.date) {
						$("#post-leave-msg").html(response.errors.date);
					}
					if (response.errors.reason) {
						$("#post-leave-msg").html(response.errors.reason);
					}
				} else {
					$("form").trigger("reset");
					$("#post-leave-msg").html(response.message);
					$("#post-leave-msg").delay(5000).fadeOut('slow');
				}
				/*
				response = $.trim(response);
				if (response === 'Ok') {
					$("form").trigger("reset");
					$("#post-leave-msg").css('display', 'inline', 'important');
					$("#post-leave-msg").html("Leave request posted successfully.");
				} else {
					$("#post-leave-msg").css('display', 'inline', 'important');
					$("#post-leave-msg").html("Could not post leave request. Please try again.");
				}
				*/
			}
		});
	});
});

//Handle get Leaves
$(document).ready(function () {
	$('#get-leaves').on('click', function (e) {
		e.preventDefault();
		$.ajax({
			type: "GET",
			url: "php/get_handle.php",
			data: "func=get_student_leaves",
			dataType: 'json',
			success: function (response) {
				var row, result, rowObj, leave = '', leave_date, date, status = '', isPast;
				if (response.length === 0) {
					leave = leave +
						'<div class="container-fluid post-empty">' +
						'<p>' + 'No leaves requested yet.' + '</p></div>';
					
				} else {
					for (row in response) {
						if (response.hasOwnProperty(row)) {
							rowObj = response[row];
							leave_date = rowObj.LeaveDate;
							date = rowObj.CreatedOn.substr(0, 10);
							status = getStatus(rowObj.Status);
							isPast = isInPast(leave_date);
							leave = leave +
								'<div class="container-fluid post-nobox"><div class="row post-header">';
							
							if (status !== "Cancelled" && !isPast) {
								leave += '<div class="col-sm-8"><p>' + getDayName(leave_date) + " " + leave_date + '</p></div>' +
									'<div class="col-sm-1">' +
									'<input id="' + rowObj.Id + '" name="lve-edit" type="submit" class="btn btn-default btn-lve-edit" value="Edit"/></div>' +
									'<div class="col-sm-1 col-sm-offset-1">' +
									'<input id="' + rowObj.Id + '" name="lve-cancel" type="submit" class="btn btn-default btn-lve-cancel" value="Cancel"/></div>';
							} else {
								leave += '<div class="col-sm-12"><p>' + getDayName(leave_date) + " " + leave_date + '</p></div>';
							}
							leave += '</div>' +
								'<div class="row"><div class="col-sm-3"><p>Requested On: </p></div>' +
								'<div class="col-sm-9"><p>' + date + '</p></div></div>' +
								'<div class="row"><div class="col-sm-3"><p>Status: </p></div>' +
								'<div class="col-sm-9"><p>' + status + '</p></div></div>' +
								'<div class="row"><div class="col-sm-3"><p>Reason: </p></div>' +
								'<div class="col-sm-9"><p>' + rowObj.Reason + '</p></div></div></div>' +
								'<hr style="width: 95%; color: darkgray; height: 1px; background-color: darkgray;"/>';
						}
					}
				}
				$("#display-leaves").css('display', 'inline', 'important');
				$("#display-leaves").html(leave);
			}
		});
	});
});

//Handle cancel Leave
$(document).ready(function () {
	$('#display-leaves').on('click', '.btn-lve-cancel', function (e) {
		e.preventDefault();
		if (confirm("Are you sure you want to cancel this Leave?")) {
			var rowId, value, form_data;
			rowId = $(this).attr('id');
			value = "lve";
			form_data = "val=" + rowId + "&type=" + value;
			console.log(form_data);
			$.ajax({
				type: "POST",
				url: "php/cancel_handle.php",
				data: form_data,
				dataType: 'json',
				success: function (response) {
					console.log(response);
					if (!response.success) {
						if (response.errors.db) {
							alert(response.errors.db);
						}
						if (response.errors.val) {
							alert(response.errors.val);
						}
						if (response.errors.type) {
							alert(response.errors.type);
						}
					} else {
						$('#get-leaves').click();
						alert(response.message);
					}
				}
			});
		}
	});
});

//Get Student's Report
$(document).ready(function () {
	$('#nav-grade').on('click', function (e) {
		e.preventDefault();
		var form_data, exam, subject, grade, thead = '', tbody = '', trow = '', tdata = '', table_result = '', is_added = 0;
		$.ajax({
			type: "GET",
			url: "php/get_handle.php",
			data: "func=get_student_report",
			dataType: 'json',
			success: function (response) {
				var row, rowObj, exm, sub;
				exam = response.exam;
				subject = response.subject;
				grade = response.grade;
				if (subject.length === 0) {
					thead = "None";
				} else {
					for (row in subject) {
						if (subject.hasOwnProperty(row)) {
							rowObj = subject[row];
							thead += '<th>' + rowObj.Name + '</th>';
						}
					}
					thead = '<thead><tr><th>Result</th>' + thead + '</tr></thead>';
				}
				for (exm = 0; exm < exam.length; exm += 1) {
					trow = '<td>' + exam[exm].Name + '</td>';
					for (sub = 0; sub < subject.length; sub += 1) {
						for (row in grade) {
							if (grade.hasOwnProperty(row)) {
								rowObj = grade[row];
								if (rowObj.Name === exam[exm].Name && rowObj.SubjectName === subject[sub].Name) {
									trow += '<td>' + rowObj.MarksObtained + "/" + rowObj.MarksTotal + '</td>';
									is_added = 1;
								}
							}
						}
						if (is_added === 0) {
							trow += '<td>-</td>';
						}
						is_added = 0;
					}
					tbody += '<tr>' + trow + '</tr>';
				}
				tdata = '<tbody>' + tbody + '</tbody>';
				table_result = '<table name="grade-marks" class="table" align="center" border="0">' + thead + tdata + '</table>';
				//$("#display-student-report").css('display', 'inline', 'important');
				$("#display-student-report").html(table_result);
			}
		});
	});
});

//Handle get Event
$(document).ready(function () {
	$('#nav-event').on('click', function (e) {
		e.preventDefault();
		$.ajax({
			type: "GET",
			url: "php/get_handle.php",
			data: "func=get_events",
			dataType: 'json',
			success: function (response) {
				var row, rowObj, cmtg = '', date;
				console.log(response);
				if (response.length === 0) {
					cmtg = cmtg +
						'<div class="container-fluid post-empty">' +
						'<p>' + 'No Meetings posted yet.' + '</p></div>';
				} else {
					for (row in response) {
						if (response.hasOwnProperty(row)) {
							rowObj = response[row];
							//date = rowObj.CreatedOn.substr(0, 10);
							date = rowObj.CreatedOn;
							cmtg = cmtg +
								'<div class="container-fluid post-nobox"><div class="row post-header">' +
								'<div class="col-sm-12"><p>' + rowObj.Subject + '</p></div></div>' +
								'<div class="row"><div class="col-sm-3"><p>When:</p></div>' +
								'<div class="col-sm-8"><p>' + rowObj.EventDate + '</p></div></div>' +
								'<div class="row"><div class="col-sm-3"><p>Time: </p></div>' +
								'<div class="col-sm-9"><p>' + rowObj.StartTime + " - " + rowObj.FinishTime + '</p></div></div>' +
								'<div class="row"><div class="col-sm-3"><p>Where:</p></div>' +
								'<div class="col-sm-9"><p>' + rowObj.Location + '</p></div></div>' +
								'<div class="row"><div class="col-sm-3"><p>Description:</p></div>' +
								'<div class="col-sm-9"><p>' + rowObj.Description + '</p></div></div></div>' +
								'<hr style="width: 100%; color: darkgray; height: 1px; background-color: darkgray;"/>';
						}
					}
				}
				$("#display-events").css('display', 'inline', 'important');
				$("#display-events").html(cmtg);
			}
		});
	});
});

//Reset operation on post meeting div
$(document).ready(function () {
	$('#nav-meeting, #new-meeting').on('click', function (e) {
		e.preventDefault();
		$("form").trigger("reset");
		$("#post-meeting-msg").css('display', 'inline', 'important');
		$("#post-meeting-msg").html("");
	});
});

//Handle post Meeting
$(document).ready(function () {
	$('#post-meeting').submit(function (e) {
		e.preventDefault();
		var form_data = $(this).serialize();
		$.ajax({
			type: "POST",
			url: "php/post_parent_meeting.php",
			data: form_data,
			dataType: 'json',
			success: function (response) {
				//console.log(response);
				if (!response.success) {
					if (response.errors.db) {
						$("#post-meeting-msg").html(response.errors.db);
					}
					if (response.errors.date) {
						$("#post-meeting-msg").html(response.errors.date);
					}
					if (response.errors.startTime) {
						$("#post-meeting-msg").html(response.errors.startTime);
					}
					if (response.errors.endTime) {
						$("#post-meeting-msg").html(response.errors.endTime);
					}
					if (response.errors.subject) {
						$("#post-meeting-msg").html(response.errors.subject);
					}
					if (response.errors.details) {
						$("#post-meeting-msg").html(response.errors.details);
					}
				} else {
					$("form").trigger("reset");
					$("#post-meeting-msg").html(response.message);
					$("#post-meeting-msg").delay(5000).fadeOut('slow');
				}
				
				/*
				response = $.trim(response);
				if (response === 'Ok') {
					$("form").trigger("reset");
					$("#post-meeting-msg").css('display', 'inline', 'important');
					$("#post-meeting-msg").html("Meeting requested successfully.");
				} else {
					$("#post-meeting-msg").css('display', 'inline', 'important');
					$("#post-meeting-msg").html("Could not request meeting. Please try again.");
				}
				*/
			}
		});
	});
});

//Handle get Parents Meeting
$(document).ready(function () {
	$('#imeeting-tab').on('click', function (e) {
		e.preventDefault();
		$.ajax({
			type: "GET",
			url: "php/get_handle.php",
			data: "func=get_parent_meetings",
			dataType: 'json',
			success: function (response) {
				var row, rowObj, imtg = '', date, status = '', isPast;
				if (response.length === 0) {
					imtg = imtg +
						'<div class="container-fluid post-empty">' +
						'<p>' + 'No Meetings posted yet.' + '</p></div>';
				} else {
					for (row in response) {
						if (response.hasOwnProperty(row)) {
							rowObj = response[row];
							//date = rowObj.CreatedOn.substr(0, 10);
							date = rowObj.CreatedOn;
							status = getStatus(rowObj.Status);
							isPast = isInPast(rowObj.MeetingDate);
							imtg = imtg +
								'<div class="container-fluid post-nobox"><div class="row post-header">';
							if (status === "Cancelled" || isPast) {
								imtg += '<div class="col-sm-12"><p>' + rowObj.Subject + '</p></div>';
							} else {
								imtg += '<div class="col-sm-8"><p>' + rowObj.Subject + '</p></div>' +
									'<div class="col-sm-1">' +
									'<input id="' + rowObj.Id + '" name="mtg-edit" type="submit" class="btn btn-default btn-mtg-edit" value="Edit"/></div>' +
									'<div class="col-sm-1 col-sm-offset-1">' +
									'<input id="' + rowObj.Id + '" name="mtg-cancel" type="submit" class="btn btn-default btn-mtg-cancel" value="Cancel"/></div>';
							}
							
							imtg += '</div>' +
								'<div class="row"><div class="col-sm-3"><p>Student Name:</p></div>' +
								'<div class="col-sm-9"><p>' + rowObj.FirstName + " " + rowObj.LastName + '</p></div></div>' +
								'<div class="row"><div class="col-sm-3"><p>Status:</p></div>' +
								'<div class="col-sm-8"><p>' + status + '</p></div></div>' +
								'<div class="row"><div class="col-sm-3"><p>When:</p></div>' +
								'<div class="col-sm-8"><p>' + rowObj.MeetingDate + '</p></div></div>' +
								'<div class="row"><div class="col-sm-3"><p>Time: </p></div>' +
								'<div class="col-sm-9"><p>' + rowObj.StartTime + " - " + rowObj.FinishTime + '</p></div></div>' +
								'<div class="row"><div class="col-sm-3"><p>Where:</p></div>' +
								'<div class="col-sm-9"><p>' + rowObj.Location + '</p></div></div>' +
								'<div class="row"><div class="col-sm-3"><p>Description:</p></div>' +
								'<div class="col-sm-9"><p>' + rowObj.Description + '</p></div></div></div>' +
								'<hr style="width: 100%; color: darkgray; height: 1px; background-color: darkgray;"/>';
						}
					}
				}
				$("#display-meeting").css('display', 'inline', 'important');
				$("#display-meeting").html(imtg);
			}
		});
	});
});

//Handle cancel meeting
$(document).ready(function () {
	$('#display-meeting').on('click', '.btn-mtg-cancel', function (e) {
		e.preventDefault();
		if (confirm("Are you sure you want to cancel this meeting?")) {
			var rowId, value, form_data;
			rowId = $(this).attr('id');
			value = "mtg";
			form_data = "val=" + rowId + "&type=" + value;
			console.log(form_data);
			$.ajax({
				type: "POST",
				url: "php/cancel_handle.php",
				data: form_data,
				dataType: 'json',
				success: function (response) {
					console.log(response);
					if (!response.success) {
						if (response.errors.db) {
							alert(response.errors.db);
						}
						if (response.errors.val) {
							alert(response.errors.val);
						}
						if (response.errors.type) {
							alert(response.errors.type);
						}
					} else {
						$('#imeeting-tab').click();
						alert(response.message);
					}
				}
			});
		}
	});
});

//Handle get Parents Need Attention Meeting
$(document).ready(function () {
	$('#nav-attention').on('click', function (e) {
		e.preventDefault();
		$("#mtg-na-response").html("");
		$.ajax({
			type: "GET",
			url: "php/get_handle.php",
			data: "func=get_parent_na_meetings",
			dataType: 'json',
			success: function (response) {
				var row, rowObj, namtg = '', date, na_count = 0;
				if (response.length === 0) {
					namtg = namtg +
						'<div class="container-fluid post-empty">' +
						'<p>' + 'No pending meeting requests.' + '</p></div>';
					na_count = 0;
					
				} else {
					na_count = response.length;
					for (row in response) {
						if (response.hasOwnProperty(row)) {
							rowObj = response[row];
							//date = rowObj.CreatedOn.substr(0, 10);
							date = rowObj.CreatedOn;
							namtg = namtg +
								'<div class="container-fluid post-nobox"><div class="row post-header">' +
								'<div class="col-sm-12"><p>' + rowObj.Subject + '</p></div></div>' +
								'<div class="row"><div class="col-sm-3"><p>When:</p></div>' +
								'<div class="col-sm-9"><p>' + rowObj.MeetingDate + '</p></div></div>' +
								'<div class="row"><div class="col-sm-3"><p>Time: </p></div>' +
								'<div class="col-sm-9"><p>' + rowObj.StartTime + " - " + rowObj.FinishTime + '</p></div></div>' +
								'<div class="row"><div class="col-sm-3"><p>Where:</p></div>' +
								'<div class="col-sm-9"><p>' + rowObj.Location + '</p></div></div>' +
								'<div class="row"><div class="col-sm-3"><p>Student Name:</p></div>' +
								'<div class="col-sm-9"><p>' + rowObj.FirstName + " " + rowObj.LastName + '</p></div></div>' +
								'<div class="row"><div class="col-sm-3"><p>Description:</p></div>' +
								'<div class="col-sm-9"><p>' + rowObj.Description + '</p></div></div>' +
								/*
								'<div class="row"><div class="col-sm-4 ">' +
								'<button id="' + rowObj.Id + '" type="submit" class="btn btn-default btn-mtg-accept">Accept</button></div>' +
								'<div class="col-sm-4">' +
								'<button id="' + rowObj.Id + '" type="submit" class="btn btn-default btn-mtg-reject">Reject</button>' +
								'</div></div>' +
								*/
								'<form id="na-mtg-response-form" class="form-box horizontal-form" role="form">' +
								'<div class="form-group row"><div class="col-sm-4 col-xs-5">' +
								'<input id="' + rowObj.Id + '" name="mtg-na-response" type="submit" class="btn btn-default btn-mtg-accept" value="Accept"/></div>' +
								'<div class="col-sm-4 col-xs-5">' +
								'<input id="' + rowObj.Id + '" name="mtg-na-response" type="submit" class="btn btn-default btn-mtg-reject" value="Reject"/>' +
								'</div></div>' +
								'</form>' +
								'<div id="mtg-na-response"></div>' +
								'</div>' +
								
								'<hr style="width: 95%; color: darkgray; height: 1px; background-color: darkgray; margin-top: 1em;"/>';
						}
					}
				}
				$("#display-na-meeting").css('display', 'inline', 'important');
				$("#display-na-meeting").html(namtg);
				$('#nav-attention').html('<img class="icon" src="images/icons/requestIcon.png" /> Need Response (' + na_count + ')');
			}
		});
	});
});

//Handle Accept meeting invite
$(document).ready(function () {
	$('#display-na-meeting').on('click', '.btn-mtg-accept', function (e) {
		e.preventDefault();
		$("#mtg-na-response").html("");
		var rowId, value, form_data;
		rowId = $(this).attr('id');
		value = $(this).attr('value');
		form_data = "mtg-na-response=" + rowId + "&value=" + value;
		
		$.ajax({
			type: "POST",
			url: "php/response_meeting.php",
			data: form_data,
			dataType: 'json',
			success: function (response) {
				//console.log(response);
				if (!response.success) {
					if (response.errors.db) {
						$("#mtg-na-response").html(response.errors.db);
					}
					if (response.errors.value) {
						$("#mtg-na-response").html(response.errors.value);
					}
					if (response.errors.mtg) {
						$("#mtg-na-response").html(response.errors.mtg);
					}
				} else {
					$("#nav-attention").click();
					$('#btn-nf-reload').click();
				}
				/*
				response = $.trim(response);
				if (response === 'Ok') {
					$("#nav-attention").click();
				} else {
					$('#mtg-na-response').css('display', 'inline', 'important');
					$('#mtg-na-response').html("Could not process Accepting the meeting request. Kindly refresh the page and Please try again.");
				}
				*/
			}
		});
	});
});

//Handle Reject Meeting invite
$(document).ready(function () {
	$('#display-na-meeting').on('click', '.btn-mtg-reject', function (e) {
		e.preventDefault();
		$("#mtg-na-response").html("");
		var rowId, value, form_data;
		rowId = $(this).attr('id');
		value = $(this).attr('value');
		form_data = "mtg-na-response=" + rowId + "&value=" + value;
		
		$.ajax({
			type: "POST",
			url: "php/response_meeting.php",
			data: form_data,
			dataType: 'json',
			success: function (response) {
				//console.log(response);
				if (!response.success) {
					if (response.errors.db) {
						$("#mtg-na-response").html(response.errors.db);
					}
					if (response.errors.value) {
						$("#mtg-na-response").html(response.errors.value);
					}
					if (response.errors.mtg) {
						$("#mtg-na-response").html(response.errors.mtg);
					}
				} else {
					$("#nav-attention").click();
					$('#btn-nf-reload').click();
				}
				/*
				response = $.trim(response);
				if (response === 'Ok') {
					$("#nav-attention").click();
				} else {
					$('#mtg-na-response').css('display', 'inline', 'important');
					$('#mtg-na-response').html("Could not process Rejecting the meeting request. Kindly refresh the page and Please try again.");
				}
				*/
			}
		});
	});
});

//Handle get Teacher Info
$(document).ready(function () {
	$('#nav-tinfo').on('click', function (e) {
		e.preventDefault();
		$.ajax({
			type: "GET",
			url: "php/get_handle.php",
			data: "func=get_teacher_info",
			dataType: 'json',
			success: function (response) {
				var row, rowObj, tinfo = '';
				if (response.length === 0) {
					tinfo = tinfo +
						'<div class="container-fluid post-empty">' +
						'<p>' + 'Teacher info not available.' + '</p></div>';
					
				} else {
					for (row in response) {
						if (response.hasOwnProperty(row)) {
							rowObj = response[row];
							
							tinfo = tinfo +
								'<div class="container-fluid post-nobox">' +
								'<div class="row"><div class="col-sm-3 col-sm-offset-1"><p><strong>Name:</strong></p></div>' +
								'<div class="col-sm-8"><p>' + rowObj.FirstName + " " + rowObj.MiddleName + " " + rowObj.LastName + '</p></div></div>' +
								'<div class="row"><div class="col-sm-3 col-sm-offset-1"><p><strong>Designation:</strong></p></div>' +
								'<div class="col-sm-8"><p>' + rowObj.Designation + '</p></div></div>' +
								'<div class="row"><div class="col-sm-3 col-sm-offset-1"><p><strong>Education:</strong></p></div>' +
								'<div class="col-sm-8"><p>' + rowObj.Education + '</p></div></div>' +
								'<div class="row"><div class="col-sm-3 col-sm-offset-1"><p><strong>Experience:</strong></p></div>' +
								'<div class="col-sm-8"><p>' + rowObj.Experience + '</p></div></div>' +
								'<div class="row"><div class="col-sm-3 col-sm-offset-1"><p><strong>Desk Location:</strong></p></div>' +
								'<div class="col-sm-8"><p>' + rowObj.DeskLocation + '</p></div></div>' +
								'<div class="row"><div class="col-sm-3 col-sm-offset-1"><p><strong>Date of Birth:</strong></p></div>' +
								'<div class="col-sm-8"><p>' + rowObj.DateOfBirth + '</p></div></div>' +
								'<div class="row"><div class="col-sm-3 col-sm-offset-1"><p><strong>Gender:</strong></p></div>' +
								'<div class="col-sm-8"><p>' + rowObj.Gender + '</p></div></div>' +
								'<div class="row"><div class="col-sm-3 col-sm-offset-1"><p><strong>Mobile#:</strong></p></div>' +
								'<div class="col-sm-8"><p>' + rowObj.MobileNumber + '</p></div></div>' +
								'<div class="row"><div class="col-sm-3 col-sm-offset-1"><p><strong>Email:</strong></p></div>' +
								'<div class="col-sm-8"><p>' + rowObj.Email + '</p></div></div>' +
								'<div class="row"><div class="col-sm-3 col-sm-offset-1"><p><strong>About:</strong></p></div>' +
								'<div class="col-sm-8"><p>' + rowObj.Description + '</p></div></div>' +
								'</div>' +
								'<hr style="width: 100%; color: darkgray; height: 1px; background-color: darkgray;"/>';
						}
					}
				}
				$("#display-teacher_info").css('display', 'inline', 'important');
				$("#display-teacher_info").html(tinfo);
			}
		});
	});
});

//Handle get home page Today's update
$(document).ready(function () {
	$.ajax({
		type: "GET",
		url: "php/get_handle.php",
		data: "func=get_parent_home",
		dataType: 'json',
		success: function (response) {
			var row, rowObj, bday, bdays = '', homework, homeworks = '', meeting, meetings = '', event, events = '', leave, leaves = '', count, na_count = 0, name, school, className, welcome = '', schoolName = '', classNames = '';
			bday = response.bday;
			homework = response.homework;
			event = response.event;
			meeting = response.meeting;
			leave = response.leave;
			count = response.count;
			
			name = response.name;
			school = response.school;
			className = response.className;
			
			//Handle Welcome name
			if (name.length === 0) {
				welcome = 'Welcome';
			} else {
				welcome = 'Hi ' + name.FirstName;
			}
			$("#welcome-msg").html(welcome);
			
			//Handle School Name
			if (school.length === 0) {
				schoolName = '';
			} else {
				schoolName = school.Name + ", " + school.City;
			}
			$("#school-name").html(schoolName);
			$("#school-name-xs").html(schoolName);
			
			//Handle Class name
			if (className.length === 0) {
				classNames = '';
			} else {
				classNames = 'Class: ' + className.Name;
			}
			$("#class-name").html(classNames);
			$("#class-name-xs").html(classNames);
			//Handle na count
			if (count.length === 0) {
				na_count = 0;
			} else {
				rowObj = count[0];
				na_count = rowObj.count;
			}
			$('#nav-attention').append(' (' + na_count + ')');
			
			//Handle Birthdays
			if (bday.length === 0) {
				bdays = bdays + '<p>' + 'No Birthdays Today.' + '</p>';

			} else {
				for (row in bday) {
					if (bday.hasOwnProperty(row)) {
						rowObj = bday[row];
						bdays = bdays +
							'<li><p>' + rowObj.FirstName + " " + rowObj.LastName + "has a birthday today. Wish " + rowObj.FirstName + " Happy Birthday!" + '</p></li>';
					}
				}
				bdays = '<ul>' + bdays + '</ul>';
			}
			$("#today").html(bdays);
			
			//Handle Homework
			if (homework.length === 0) {
				homeworks = homeworks + '<p>' + 'No Due Homework Today.' + '</p>';

			} else {
				for (row in homework) {
					if (homework.hasOwnProperty(row)) {
						rowObj = homework[row];
						homeworks = homeworks +
							'<li><p>' + rowObj.Subject + " : " + rowObj.Topic + '</br>' + "Description: " + rowObj.Description + '</p></li>';
					}
				}
				homeworks = '<ul>' + homeworks + '</ul>';
			}
			$("#today-homework").html(homeworks);
			
			//Handle Event
			if (event.length === 0) {
				events = events + '<p>' + 'No Events scheduled for Today.' + '</p>';

			} else {
				for (row in event) {
					if (event.hasOwnProperty(row)) {
						rowObj = event[row];
						events = events +
							'<li><p>' + rowObj.Subject + " " + '</br>' + "When: " + rowObj.EventDate + " at " + rowObj.StartTime + " - " + rowObj.FinishTime + '</br>' + "Location: " + rowObj.Location + '</p></li>';
					}
				}
				events = '<ul>' + events + '</ul>';
			}
			$("#today-event").html(events);
			
			//Handle meetings
			if (meeting.length === 0) {
				meetings = meetings + '<p>' + 'No Meetings scheduled for today.' + '</p>';
			} else {
				for (row in meeting) {
					if (meeting.hasOwnProperty(row)) {
						rowObj = meeting[row];
						meetings = meetings +
							'<li><p>' + "Meeting with Class Teacher " + '</br>' + "When: " + rowObj.StartTime + " - " + rowObj.FinishTime + '</br>' + "Location: " + rowObj.Location + '</br>' + "About: " + rowObj.Subject + '</p></li>';
					}
				}
				meetings = '<ul>' + meetings + '</ul>';
			}
			$("#today-meeting").html(meetings);
			
			//Handle Leaves
			if (leave.length === 0) {
				leaves = leaves + '<p>' + 'No one is on leave today.' + '</p>';

			} else {
				for (row in leave) {
					if (leave.hasOwnProperty(row)) {
						rowObj = leave[row];
						leaves = leaves +
							'<li><p>' + rowObj.FirstName + " " + rowObj.LastName + " is on leave today. " + '</p></li>';
					}
				}
				leaves = '<ul>' + leaves + '</ul>';
			}
			$("#today-leave").html(leaves);
		}
	});
});

//Handle get home page Notifications update
$(document).ready(function () {
	$('#btn-nf-reload').on('click', function (e) {
		e.preventDefault();
		$.ajax({
			type: "GET",
			url: "php/get_handle.php",
			data: "func=get_parent_notification",
			dataType: 'json',
			success: function (response) {
				var row, rowObj, meeting, meetings = '', event, events = '', leave, leaves = '', mattention, attention = '', recent, recents = '', rmeeting, rmeetings = '', revent, revents = '', rann, ranns = '', rnotice, rnotices = '', rhomework, rhomeworks = '', rleave, rleaves = '';
				event = response.event;
				meeting = response.meeting;
				leave = response.leave;
				mattention = response.mattention;
				recent = response.recent;
				rann = recent.ann;
				rnotice = recent.notice;
				rhomework = recent.homework;
				revent = recent.event;
				rmeeting = recent.meeting;
				rleave = recent.leave;
				console.log(recent);
				
				//Handle recent
				if (rann.length === 0 && rnotice.length === 0 && rhomework.length === 0 && revent.length === 0 && rmeeting.length === 0) {
					recents = recents + '<p>' + 'No recent activity.' + '</p>';
				} else {
					if (rann.length !== 0) {
						for (row in rann) {
							if (rann.hasOwnProperty(row)) {
								rowObj = rann[row];
								ranns = ranns +
									'<li><p>New announcement posted on ' + rowObj.CreatedOn.substr(0, 10) + "</br>" + 'Title: ' + rowObj.Title + '</p></li>';
							}
						}
					}
					if (rnotice.length !== 0) {
						for (row in rnotice) {
							if (rnotice.hasOwnProperty(row)) {
								rowObj = rnotice[row];
								rnotices = rnotices +
									'<li><p>New notice sent to you on ' + rowObj.CreatedOn.substr(0, 10) + "</br>" + 'Title: ' + rowObj.Title + '</p></li>';
							}
						}
					}
					if (rhomework.length !== 0) {
						for (row in rhomework) {
							if (rhomework.hasOwnProperty(row)) {
								rowObj = rhomework[row];
								rhomeworks = rhomeworks +
									'<li><p>New ' + rowObj.Subject + ' homework' + "</br>" + 'Topic: ' + rowObj.Topic + '</p></li>';
							}
						}
					}
					if (rmeeting.length !== 0) {
						for (row in rmeeting) {
							if (rmeeting.hasOwnProperty(row)) {
								rowObj = rmeeting[row];
								rmeetings = rmeetings +
									'<li><p>' + "New Meeting with class teacher" + '</br>' + "When: " + rowObj.MeetingDate + " at " + rowObj.StartTime + " - " + rowObj.FinishTime + '</br>' + "Location: " + rowObj.Location + '</br>' + '</p></li>';
							}
						}
					}
					
					if (revent.length !== 0) {
						for (row in revent) {
							if (revent.hasOwnProperty(row)) {
								rowObj = revent[row];
								revents = revents +
									'<li><p>' + rowObj.Subject + " " + '</br>' + "When: " + rowObj.EventDate + " at " + rowObj.StartTime + " - " + rowObj.FinishTime + '</br>' + "Location: " + rowObj.Location + '</p></li>';
							}
						}
					}
					if (rleave.length !== 0) {
						for (row in rleave) {
							if (rleave.hasOwnProperty(row)) {
								rowObj = rleave[row];
								rleaves = rleaves +
									'<li><p>You requested new leave.</br>' + "Leave date: " + rowObj.LeaveDate + '</p></li>';
							}
						}
					}
					recents = '<ul>' + ranns + rnotices + rhomeworks + rmeetings + revents + rleaves + '</ul>';
				}
				$("#nf-recents").html(recents);
				

				//Handle meetings
				if (meeting.length === 0) {
					meetings = meetings + '<p>' + 'No upcoming meetings.' + '</p>';
				} else {
					for (row in meeting) {
						if (meeting.hasOwnProperty(row)) {
							rowObj = meeting[row];
							meetings = meetings +
								'<li><p>' + "Meeting with Class teacher"  + '</br>' + "When: " + rowObj.MeetingDate + " at " + rowObj.StartTime + " - " + rowObj.FinishTime + '</br>' + "Location: " + rowObj.Location + '</br>' + '</p></li>';
						}
					}
					meetings = '<ul>' + meetings + '</ul>';
				}
				$("#nf-upcoming-meeting").html(meetings);

				//Handle Leaves
				if (leave.length === 0) {
					leaves = leaves + '<p>' + 'No upcoming leaves.' + '</p>';

				} else {
					for (row in leave) {
						if (leave.hasOwnProperty(row)) {
							rowObj = leave[row];
							leaves = leaves +
								'<li><p>' + rowObj.FirstName + " " + rowObj.LastName + " on " + rowObj.LeaveDate + '</p></li>';
						}
					}
					leaves = '<ul>' + leaves + '</ul>';
				}
				$("#nf-upcoming-leave").html(leaves);

				//Handle Event
				if (event.length === 0) {
					events = events + '<p>' + 'No upcoming events' + '</p>';

				} else {
					for (row in event) {
						if (event.hasOwnProperty(row)) {
							rowObj = event[row];
							events = events +
								'<li><p>' + rowObj.Subject + " " + '</br>' + "When: " + rowObj.EventDate + " at " + rowObj.StartTime + " - " + rowObj.FinishTime + '</br>' + "Location: " + rowObj.Location + '</p></li>';
						}
					}
					events = '<ul>' + events + '</ul>';
				}
				$("#nf-upcoming-event").html(events);

				//Handle Attention
				/*
				if (mattention.length === 0) {
					attention = attention + '<p>' + 'No new requests that need your attention.' + '</p>';
				} else {
					if (mattention.length !== 0) {
						for (row in mattention) {
							if (mattention.hasOwnProperty(row)) {
								rowObj = mattention[row];
								attention = attention +
									'<li><p>' + "Meeting with Class Teacher" + '</br>' + "When: " + rowObj.MeetingDate + " at " + rowObj.StartTime + " - " + rowObj.FinishTime + '</br>' + "Location: " + rowObj.Location + '</br>' + '</p></li>';
							}
						}
					}
					attention = '<ul>' + attention + '</ul>';
				}
				$("#nf-new-attention").html(attention);
				*/
			}
		});
	});
});

//Handle get student attendance report
$(document).ready(function () {
	$('#nav-attendance').on('click', function (e) {
		e.preventDefault();
		var form_data, student, attendance, holiday, thead = '', tbody = '', trow = '', tdata = '', table_result = '', is_added = 0, days = 0, day, month, year;
		$.ajax({
			type: "GET",
			url: "php/get_handle.php",
			data: "func=get_student_attendance",
			dataType: 'json',
			success: function (response) {
				var row, rowObj, std, d, dt, dt_str, yesHoliday = false, weekend, absent_count = 0, months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
				attendance = response.attendance;
				holiday = response.holiday;
				
				year = getYear(month);
				days = daysInMonth(month, year);
				
				for (day = 1; day <= 31; day += 1) {
					thead += '<th>' + day + '</th>';
				}
				thead = '<thead><tr><th>Result</th><th>Absent Days</th>' + thead + '</tr></thead>';
				
				for (month = 1; month <= months.length; month += 1) {
					absent_count = getMonthlyAbsentCount(attendance, month.toString());
					year = getYear(month);
					trow = '<td>' + months[month - 1] + '(' + year + ')' + '</td><td>' + absent_count + '</td>';
					days = daysInMonth(month, year);
					for (day = 1; day <= days; day += 1) {
						dt = new Date(year.toString() + '/' + month.toString() + '/' + day.toString());
						weekend = dt.getDay();

						dt_str = getFormattedDate(dt);
						yesHoliday = isHoliday(holiday, dt_str);
						
						if (weekend === 0 || yesHoliday === true) {
							trow += '<td>' + 'X' + '</td>';
							is_added = 1;
						}
						
						if (isInFuture(dt) === 1 && is_added === 0) {
							trow += '<td>' + '-' + '</td>';
							is_added = 1;
						}
						
						for (row in attendance) {
							if (attendance.hasOwnProperty(row)) {
								rowObj = attendance[row];
								d = new Date(rowObj.Date.replace(/-/g, '\/'));
								if (rowObj.Month === month.toString() &&  d.getDate() === day && is_added === 0) {
									trow += '<td>' + 'A' + '</td>';
									is_added = 1;
								}
							}
						}
						if (is_added === 0) {
							trow += '<td>P</td>';
						}
						is_added = 0;
						yesHoliday = false;
					}
					tbody += '<tr>' + trow + '</tr>';
				}
				tdata = '<tbody>' + tbody + '</tbody>';
				table_result = '<table name="student-attendance-table" class="table" align="center" border="0">' + thead + tdata + '</table>';
				//$("#display-class-report").css('display', 'inline', 'important');
				$("#display-student-attendance").html(table_result);
				
			}
		});
	});
});

//Notification button click
$(document).ready(function () {
	$('#btn-nf-reload').click();
});

//Get Need Response count
$(document).ready(function () {
	
});

function get_na_count() {
	
}