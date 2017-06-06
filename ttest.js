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

function getEventStatus(status, date) {
	var dateObj, curDate;
	if (status === '3') {
		return "Cancelled";
	} else {
		dateObj = new Date(date);
		curDate = new Date();
		if (dateObj < curDate) {
			return "Completed";
		} else {
			return "Upcoming";
		}
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

function setHeight() {
	var maxHeight = 0;
	$('#middle, #right').each(function () {
		if ($(this).height() > maxHeight) {
			maxHeight = $(this).height();
		}
	});
	//$('.box-content-wrapper').css('min-height', maxheight);
	$('#middle').css('min-height', maxHeight);
	$('#right').css('min-height', maxHeight);
}

function getAbsentCount(attendance, studentId) {
    var count = 0, i = 0;
    for (i = 0; i < attendance.length; i += 1) {
        if (attendance[i].StudentId === studentId) {
            count += 1;
        }
    }
    return count;
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
	for (i = 0; i < holiday.length; i += 1) {
        if (holiday[i].Date === date) {
            return true;
        }
    }
	return false;
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
	console.log(year, curMonthVal, monthVal);
	if (curMonthVal === monthVal) {
		return year;
	} else if (curMonthVal > monthVal) {
		return year - 1;
	} else {
		return year + 1;
	}
}

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

//Function to display list of students on click
$(document).ready(function () {
    $("#nav-student").click(function () {
        $(".toggle-nav").hide();
        $("#student").show();
    });
});


//Function to display grades on click
$(document).ready(function () {
    $("#nav-grade").click(function () {
        $(".toggle-nav").hide();
        $("#grade").show();
    });
});

//Function to display Event on click
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


//Function to handle homework date picker
$(function () {
	$('#homework-due-date').datepicker({
		autoclose: true,
		format: "yyyy-mm-dd",
		daysOfWeekDisabled: "0",
		orientation: "bottom"
	});
});

//Function to handle meeting date picker
$(function () {
	$('#event-date').datepicker({
		autoclose: true,
		format: "yyyy-mm-dd",
		daysOfWeekDisabled: "0",
		orientation: "bottom"
	});
});

//Function to handle individual meeting date picker
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

//Function to handle Attendance date picker
$(function () {
	$('#attendance-date').datepicker({
		autoclose: true,
		format: "yyyy-mm-dd",
		daysOfWeekDisabled: "0",
		orientation: "bottom"
	});
});

//Function to initialise time pickers
$(function () {
	$('#event-start-time').timepicker();
	$('#event-end-time').timepicker();
	$('#meeting-start-time').timepicker();
	$('#meeting-end-time').timepicker();
	$('#homework-due-time').timepicker();
});

//Function to display leave/meeting details on click
$(document).ready(function () {
    $(function () {
		$(".toggler").on("click", function () {
			$(this)
				.next().slideToggle();
		});
	});
});

$(document).ready(function () {
    $(function () {
		$(".toggle-meeting-details").on("click", function () {
			$(this)
				.nextAll().slideToggle();
		});
	});
});


//Function to display schedule meeting on click
$(document).ready(function () {
    $("#nav-upcoming").click(function () {
        $(".toggle-nav").hide();
        $("#upcoming").show();
    });
});

//Generic multiselect handle function
jQuery.fn.extend({
	ismultiselect : function () {
		$(this).multiselect({
			maxHeight: 200,
			buttonWidth: '200px',
			enableFiltering: true,
			enableCaseInsensitiveFiltering: true,
			numberDisplayed: 1
		});
	}
});

//Initialise all multiselect elements
$(document).ready(function () {
	$('#attendance-student-select').ismultiselect();
	$('#attendance-month-select').ismultiselect();
	$('#attendance-report-student-select').ismultiselect();
	$('#notice-student-select').ismultiselect();
	$('#meeting-student-select').ismultiselect();
	$('#grade-student-select').ismultiselect();
	$('#grade-class-exam').ismultiselect();
	$('#homework-subject-select').ismultiselect();
	$('#grade-subject-select').ismultiselect();
	$('#grade-exam-select').ismultiselect();
});


/*************BACKEND CODE BELOW***************/
//To display jason object- var result = JSON.stringify(response);
//To declare a global variable- window.varName = 10;

//Session Check
$(document).ready(function () {
	$.ajax({
		type: "GET",
		url: "php/session.php",
		//async: false,
		cache: false,
		success: function (response) {
			response = $.trim(response);
			if (response !== 'teacher') {
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

//Handle Student
$(document).ready(function () {
	$('#nav-student').click(function (e) {
		e.preventDefault();
		$.ajax({
			type: "GET",
			url: "php/get_handle.php",
			data: "func=get_students",
			dataType: 'json',
			success: function (response) {
				var row, rowObj, tdata = '', tbody = '';
				if (response.length === 0) {
					tdata = "Students are not added to this class.";
				} else {
					for (row in response) {
						if (response.hasOwnProperty(row)) {
							rowObj = response[row];
							tbody += '<tr><td>' + rowObj.RollNumber + '</td><td>' + rowObj.FirstName + " " + rowObj.MiddleName +  " " + rowObj.LastName + '</td><td>' + rowObj.DateOfBirth + '</td><td>' + rowObj.Gender + '</td></tr>';
						}
					}
					tdata = '<table class="table" align="center" border="0"><thead><tr><th>Roll No.</th><th>Name</th><th>Date of Birth</th><th>Sex</th></tr></thead><tbody>' + tbody + '</tbody></table>';
				}
				//$("#table-student").css('display', 'inline', 'important');
				$("#table-student").html(tdata);
			}
		});
	});
});

//Reset operation on announcement div
$(document).ready(function () {
	$('#nav-announcement, #new-ann-tab').on('click', function (e) {
		e.preventDefault();
		$("form").trigger("reset");
		$("#post-announcement-msg").css('display', 'inline', 'important');
		$("#post-announcement-msg").html("");
	});
});

//Handle post Announcement
$(document).ready(function () {
	$('#post-announcement').submit(function (e) {
		e.preventDefault();
		//var form_data = $(this).serialize();
		var form_data = new FormData(this);
		
		$.ajax({
			type: "POST",
			url: "php/post_announcement.php",
			data: form_data,
			contentType: false,
			processData: false,
			dataType: 'json',
			success: function (response) {
				if (!response.success) {
					if (response.errors.db) {
						$("#post-announcement-msg").html(response.errors.db);
					}
					if (response.errors.title) {
						$("#post-announcement-msg").html(response.errors.title);
					}
					if (response.errors.description) {
						$("#post-announcement-msg").html(response.errors.description);
					}
					if (response.errors.noUpload) {
						$("#post-announcement-msg").html(response.errors.noUpload);
					}
					if (response.errors.fileError) {
						$("#post-announcement-msg").html(response.errors.fileError);
					}
					if (response.errors.empty) {
						$("#post-announcement-msg").html(response.errors.empty);
					}
					if (response.errors.wrongFormat) {
						$("#post-announcement-msg").html(response.errors.wrongFormat);
					}
				} else {
					$("form").trigger("reset");
					$("#post-announcement-msg").html(response.message);
					//$("#post-announcement-msg").delay(5000).fadeOut('slow');
				}
			}
		});
	});
});

//Handle get Announcements
$(document).ready(function () {
	$('#get-announcements').on('shown.bs.tab', function (e) {
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
								'<div class="col-xs-12"><p id="get-ann-subject">' + rowObj.Title + '</p></div></div>' +
								'<div class="row"><div class="col-sm-3"><p>Posted Date:</p></div>' +
								'<div class="col-xs-9"><p id="get-ann-date">' + date + '</p>' +
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

//Configure notice-student-select dropdown and reset Notice div
$(document).ready(function () {
	$('#nav-notice, #new-notice-tab').on('click', function (e) {
		e.preventDefault();
		$("form").trigger("reset");
		$("#post-notice-msg").css('display', 'inline', 'important');
		$("#post-notice-msg").html("");
		$.ajax({
			type: "GET",
			url: "php/get_handle.php",
			data: "func=get_students",
			dataType: 'json',
			success: function (response) {
				var row, rowObj, options = [], option;
				if (response.length === 0) {
					option = {label: 'No Students Available', title: 'No Students Available'};
					options.push(option);
				} else {
					for (row in response) {
						if (response.hasOwnProperty(row)) {
							rowObj = response[row];
							option = {label: rowObj.RollNumber + ': ' + rowObj.FirstName + ' ' + rowObj.LastName, title: rowObj.FirstName + rowObj.LastName, value: rowObj.Id};
							options.push(option);
						}
					}
				}
				$('#notice-student-select').multiselect('dataprovider', options);
			}
		});
	});
});

//Handle post Notice
$(document).ready(function () {
	$('#post-notice').submit(function (e) {
		e.preventDefault();
		//var form_data = $(this).serialize();
		var form_data = new FormData(this);
		$('#notice-student-select').multiselect('deselectAll', false);
		$('#notice-student-select').multiselect('updateButtonText');
		$.ajax({
			type: "POST",
			url: "php/post_notice.php",
			data: form_data,
			contentType: false,
			processData: false,
			dataType: 'json',
			success: function (response) {
				if (!response.success) {
					if (response.errors.db) {
						$("#post-notice-msg").html(response.errors.db);
					}
					if (response.errors.title) {
						$("#post-notice-msg").html(response.errors.title);
					}
					if (response.errors.description) {
						$("#post-notice-msg").html(response.errors.description);
					}
					if (response.errors.student) {
						$("#post-notice-msg").html(response.errors.student);
					}
					if (response.errors.fileError) {
						$("#post-notice-msg").html(response.errors.fileError);
					}
					if (response.errors.empty) {
						$("#post-notice-msg").html(response.errors.empty);
					}
					if (response.errors.wrongFormat) {
						$("#post-notice-msg").html(response.errors.wrongFormat);
					}
				} else {
					$("form").trigger("reset");
					$("#post-notice-msg").html(response.message);
					$("#post-notice-msg").delay(5000).fadeOut('slow');
				}
			}
		});
	});
});

//Handle get Notices
$(document).ready(function () {
	$('#get-notices').on('shown.bs.tab', function (e) {
		e.preventDefault();
		$.ajax({
			type: "GET",
			url: "php/get_handle.php",
			data: "func=get_teacher_notices",
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
								'<div class="container-fluid post-nobox"><div class="row post-header"><div class="col-xs-12">' +
								'<p>' + rowObj.Title + '</p></div></div>' +
								'<div class="row"><div class="col-sm-3"><p>Posted Date:</p></div>' +
								'<div class="col-sm-9"><p>' + date + '</p></div></div>' +
								'<div class="row"><div class="col-sm-3"><p>Posted by: </p></div>' +
								'<div class="col-sm-9"><p>' + rowObj.TeacherName + '</p></div></div>' +
								'<div class="row"><div class="col-sm-3"><p>Student Names: </p></div>' +
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

//Handle get Past Leaves
$(document).ready(function () {
	$('#get-past-leave').on('shown.bs.tab', function (e) {
		e.preventDefault();
		$.ajax({
			type: "GET",
			url: "php/get_handle.php",
			data: "func=get_teacher_past_leaves",
			dataType: 'json',
			success: function (response) {
				var row, result, rowObj, past_leave = '', leave_date, date, status = '';
				if (response.length === 0) {
					past_leave = past_leave +
						'<div class="container-fluid post-empty">' +
						'<p>' + 'No leaves requested yet.' + '</p></div>';
					
				} else {
					for (row in response) {
						if (response.hasOwnProperty(row)) {
							rowObj = response[row];
							leave_date = rowObj.LeaveDate;
							date = rowObj.CreatedOn.substr(0, 10);
							status = getStatus(rowObj.Status);
							past_leave = past_leave +
								'<div class="container-fluid post-nobox"><div class="row post-header">' +
								'<div class="col-sm-12"><p>' + getDayName(leave_date) + " " + leave_date + '</p></div></div>' +
								'<div class="row"><div class="col-sm-3"><p>Student Name: </p></div>' +
								'<div class="col-sm-9"><p>' + rowObj.FirstName + " " + rowObj.LastName + '</p></div></div>' +
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
				$("#display-past-leaves").css('display', 'inline', 'important');
				$("#display-past-leaves").html(past_leave);
			}
		});
	});
});

//Handle get Upcoming Leaves
$(document).ready(function () {
	$('#nav-leave, #get-upcoming-leave').on('click', function (e) {
		e.preventDefault();
		$.ajax({
			type: "GET",
			url: "php/get_handle.php",
			data: "func=get_teacher_upcoming_leaves",
			dataType: 'json',
			success: function (response) {
				var row, result, rowObj, upcoming_leave = '', leave_date, date, status = '';
				if (response.length === 0) {
					upcoming_leave = upcoming_leave +
						'<div class="container-fluid post-empty">' +
						'<p>' + 'No leaves requested yet.' + '</p></div>';
					
				} else {
					for (row in response) {
						if (response.hasOwnProperty(row)) {
							rowObj = response[row];
							leave_date = rowObj.LeaveDate;
							date = rowObj.CreatedOn.substr(0, 10);
							status = getStatus(rowObj.Status);
							
							upcoming_leave = upcoming_leave +
								'<div class="container-fluid post-nobox"><div class="row post-header">' +
								'<div class="col-sm-12"><p>' + getDayName(leave_date) + " " + leave_date + '</p></div></div>' +
								'<div class="row"><div class="col-sm-3"><p>Student Name: </p></div>' +
								'<div class="col-sm-9"><p>' + rowObj.FirstName + " " + rowObj.LastName + '</p></div></div>' +
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
				$("#display-upcoming-leaves").css('display', 'inline', 'important');
				$("#display-upcoming-leaves").html(upcoming_leave);
			}
		});
	});
});

//Configure meeting-student-select dropdown
$(document).ready(function () {
	$('#nav-meeting, #new-meeting-tab').on('click', function (e) {
		e.preventDefault();
		$("form").trigger("reset");
		$("#post-meeting-msg").css('display', 'inline', 'important');
		$("#post-meeting-msg").html("");
		$.ajax({
			type: "GET",
			url: "php/get_handle.php",
			data: "func=get_students",
			dataType: 'json',
			success: function (response) {
				var row, rowObj, options = [], option;
				if (response.length === 0) {
					option = {label: 'No Students Available', title: 'No Students Available'};
					options.push(option);
				} else {
					for (row in response) {
						if (response.hasOwnProperty(row)) {
							rowObj = response[row];
							option = {label: rowObj.RollNumber + ': ' + rowObj.FirstName + ' ' + rowObj.LastName, title: rowObj.FirstName + rowObj.LastName, value: rowObj.Id};
							options.push(option);
						}
					}
				}
				$('#meeting-student-select').multiselect('dataprovider', options);
			}
		});
	});
});

//Reset operation on Event div
$(document).ready(function () {
	$('#nav-event, #new-event-tab').on('click', function (e) {
		e.preventDefault();
		$("form").trigger("reset");
		$("#post-event-msg").css('display', 'inline', 'important');
		$("#post-event-msg").html("");
	});
});

//Handle post Event
$(document).ready(function () {
	$('#post-event').submit(function (e) {
		e.preventDefault();
		var form_data = $(this).serialize();
		console.log(form_data);
		$.ajax({
			type: "POST",
			url: "php/post_event.php",
			data: form_data,
			dataType: 'json',
			success: function (response) {
				if (!response.success) {
					if (response.errors.db) {
						$("#post-event-msg").html(response.errors.db);
					}
					if (response.errors.date) {
						$("#post-event-msg").html(response.errors.date);
					}
					if (response.errors.startTime) {
						$("#post-event-msg").html(response.errors.startTime);
					}
					if (response.errors.endTime) {
						$("#post-event-msg").html(response.errors.endTime);
					}
					if (response.errors.location) {
						$("#post-event-msg").html(response.errors.location);
					}
					if (response.errors.subject) {
						$("#post-event-msg").html(response.errors.subject);
					}
					if (response.errors.details) {
						$("#post-event-msg").html(response.errors.details);
					}
				} else {
					$("form").trigger("reset");
					$("#post-event-msg").html(response.message);
					$("#post-event-msg").delay(5000).fadeOut('slow');
				}
			}
		});
	});
});

//Handle get Events
$(document).ready(function () {
	//$('#get-events').on('shown.bs.tab', function (e) {
	$('#get-events').on('click', function (e) {
		e.preventDefault();
		$.ajax({
			type: "GET",
			url: "php/get_handle.php",
			data: "func=get_events",
			dataType: 'json',
			success: function (response) {
				var row, rowObj, event = '', date, status = '';
				if (response.length === 0) {
					event = event +
						'<div class="container-fluid post-empty">' +
						'<p>' + 'No Events posted yet.' + '</p></div>';
					
				} else {
					for (row in response) {
						if (response.hasOwnProperty(row)) {
							rowObj = response[row];
							//date = rowObj.CreatedOn.substr(0, 10);
							date = rowObj.CreatedOn;
							status = getEventStatus(rowObj.Status, rowObj.EventDate);
							console.log(status);
							event = event +
								'<div class="container-fluid post-nobox"><div class="row post-header">';
								/*'<div class="col-sm-12"><p>' + rowObj.Subject + '</p></div>*/
							
							if (status === "Upcoming") {
								event += '<div class="col-sm-8"><p>' + rowObj.Subject + '</p></div>' +
									'<div class="col-sm-1">' +
									'<input id="' + rowObj.Id + '" name="evt-edit" type="submit" class="btn btn-default btn-evt-edit" value="Edit"/></div>' +
									'<div class="col-sm-1 col-sm-offset-1">' +
									'<input id="' + rowObj.Id + '" name="evt-cancel" type="submit" class="btn btn-default btn-evt-cancel" value="Cancel"/></div>';
							} else {
								event += '<div class="col-sm-12"><p>' + rowObj.Subject + '</p></div>';
							}
								
							event += '</div>' +
								'<div class="row"><div class="col-sm-3"><p>When:</p></div>' +
								'<div class="col-sm-8"><p>' + rowObj.EventDate + '</p></div></div>' +
								'<div class="row"><div class="col-sm-3"><p>Time: </p></div>' +
								'<div class="col-sm-9"><p>' + rowObj.StartTime + " - " + rowObj.FinishTime + '</p></div></div>' +
								'<div class="row"><div class="col-sm-3"><p>Status:</p></div>' +
								'<div class="col-sm-9"><p>' + status + '</p></div></div>' +
								'<div class="row"><div class="col-sm-3"><p>Where:</p></div>' +
								'<div class="col-sm-9"><p>' + rowObj.Location + '</p></div></div>' +
								'<div class="row"><div class="col-sm-3"><p>Description:</p></div>' +
								'<div class="col-sm-9"><p>' + rowObj.Description + '</p></div></div></div>' +
								'<hr style="width: 100%; color: darkgray; height: 1px; background-color: darkgray;"/>';
						}
					}
				}
				$("#display-events").css('display', 'inline', 'important');
				$("#display-events").html(event);
			}
		});
	});
});

//Handle cancel Event
$(document).ready(function () {
	$('#display-events').on('click', '.btn-evt-cancel', function (e) {
		e.preventDefault();
		if (confirm("Are you sure you want to cancel this Event?")) {
			var rowId, value, form_data;
			rowId = $(this).attr('id');
			value = "evt";
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
						$('#get-events').click();
						alert(response.message);
					}
				}
			});
		}
	});
});

//Handle post Meeting
$(document).ready(function () {
	$('#post-meeting').submit(function (e) {
		e.preventDefault();
		var form_data = $(this).serialize();
		$.ajax({
			type: "POST",
			url: "php/post_meeting.php",
			data: form_data,
			dataType: 'json',
			success: function (response) {
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
					if (response.errors.location) {
						$("#post-meeting-msg").html(response.errors.location);
					}
					if (response.errors.subject) {
						$("#post-meeting-msg").html(response.errors.subject);
					}
					if (response.errors.details) {
						$("#post-meeting-msg").html(response.errors.details);
					}
					if (response.errors.student) {
						$("#post-meeting-msg").html(response.errors.student);
					}
				} else {
					$("form").trigger("reset");
					$("#post-meeting-msg").html(response.message);
					$("#post-meeting-msg").delay(5000).fadeOut('slow');
				}
			}
		});
	});
});

//Handle get Teachers Individual Meeting
$(document).ready(function () {
	$('#get-meeting').on('click', function (e) {
		e.preventDefault();
		$.ajax({
			type: "GET",
			url: "php/get_handle.php",
			data: "func=get_teacher_meetings",
			dataType: 'json',
			success: function (response) {
				var row, rowObj, mtg = '', date, status = '', isPast;
				if (response.length === 0) {
					mtg = mtg +
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
							mtg = mtg +
								'<div class="container-fluid post-nobox">' +
								'<div class="row post-header">';
							if (status === "Cancelled" || isPast) {
								mtg += '<div class="col-sm-12"><p>' + rowObj.Subject + '</p></div>';
							} else {
								mtg += '<div class="col-sm-8"><p>' + rowObj.Subject + '</p></div>' +
									'<div class="col-sm-1">' +
									'<input id="' + rowObj.Id + '" name="mtg-edit" type="submit" class="btn btn-default btn-mtg-edit" value="Edit"/></div>' +
									'<div class="col-sm-1 col-sm-offset-1">' +
									'<input id="' + rowObj.Id + '" name="mtg-cancel" type="submit" class="btn btn-default btn-mtg-cancel" value="Cancel"/></div>';
							}
							
							mtg += '</div>' +
								'<div class="row"><div class="col-sm-3"><p>When:</p></div>' +
								'<div class="col-sm-8"><p>' + rowObj.MeetingDate + '</p></div></div>' +
								'<div class="row"><div class="col-sm-3"><p>Time: </p></div>' +
								'<div class="col-sm-9"><p>' + rowObj.StartTime + " - " + rowObj.FinishTime + '</p></div></div>' +
								'<div class="row"><div class="col-sm-3"><p>Where:</p></div>' +
								'<div class="col-sm-9"><p>' + rowObj.Location + '</p></div></div>' +
								'<div class="row"><div class="col-sm-3"><p>Status:</p></div>' +
								'<div class="col-sm-8"><p>' + status + '</p></div></div>' +
								'<div class="row"><div class="col-sm-3"><p>Student Names:</p></div>' +
								'<div class="col-sm-9"><p>' + rowObj.StudentName + '</p></div></div>' +
								'<div class="row"><div class="col-sm-3"><p>Description:</p></div>' +
								'<div class="col-sm-9"><p>' + rowObj.Description + '</p></div></div>' +
								
								'</div>' +
								'<hr style="width: 100%; color: darkgray; height: 1px; background-color: darkgray;"/>';
						}
					}
				}
				$("#display-meetings").css('display', 'inline', 'important');
				$("#display-meetings").html(mtg);
			}
		});
	});
});

//Handle cancel meeting
$(document).ready(function () {
	$('#display-meetings').on('click', '.btn-mtg-cancel', function (e) {
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
						$('#get-meeting').click();
						alert(response.message);
					}
				}
			});
		}
	});
});

//Configure homework-subject-select dropdown
$(document).ready(function () {
	$('#nav-homework, #new-homework-tab').on('click', function (e) {
		e.preventDefault();
		$("form").trigger("reset");
		$("#post-homework-msg").css('display', 'inline', 'important');
		$("#post-homework-msg").html("");
		$.ajax({
			type: "GET",
			url: "php/get_handle.php",
			data: "func=get_class_subjects",
			dataType: 'json',
			success: function (response) {
				var row, rowObj, options = [], option;
				if (response.length === 0) {
					option = {label: 'No Subjects Available', title: 'No Subjects Available'};
					options.push(option);
				} else {
					for (row in response) {
						if (response.hasOwnProperty(row)) {
							rowObj = response[row];
							option = {label: rowObj.Name, title: rowObj.Name, value: rowObj.Id};
							options.push(option);
						}
					}
				}
				$('#homework-subject-select').multiselect('dataprovider', options);
			}
		});
	});
});

//Handle post Homework
$(document).ready(function () {
	$('#post-homework').submit(function (e) {
		e.preventDefault();
		//var form_data = $(this).serialize();
		var form_data = new FormData(this);
		$.ajax({
			type: "POST",
			url: "php/post_homework.php",
			data: form_data,
			contentType: false,
			processData: false,
			dataType: 'json',
			success: function (response) {
				if (!response.success) {
					if (response.errors.db) {
						$("#post-homework-msg").html(response.errors.db);
					}
					if (response.errors.dueDate) {
						$("#post-homework-msg").html(response.errors.dueDate);
					}
					if (response.errors.dueTime) {
						$("#post-homework-msg").html(response.errors.dueTime);
					}
					if (response.errors.topic) {
						$("#post-homework-msg").html(response.errors.topic);
					}
					if (response.errors.description) {
						$("#post-homework-msg").html(response.errors.description);
					}
					if (response.errors.subject) {
						$("#post-homework-msg").html(response.errors.subject);
					}
					if (response.errors.fileError) {
						$("#post-homework-msg").html(response.errors.fileError);
					}
					if (response.errors.empty) {
						$("#post-homework-msg").html(response.errors.empty);
					}
					if (response.errors.wrongFormat) {
						$("#post-homework-msg").html(response.errors.wrongFormat);
					}
				} else {
					$("form").trigger("reset");
					$("#post-homework-msg").html(response.message);
					$("#post-homework-msg").delay(5000).fadeOut('slow');
				}
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

//Handle get Due Homework
$(document).ready(function () {
	$('#get-due-homework').on('click', function (e) {
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
						'<p>' + 'No Due Homework.' + '</p></div>';
					
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

//Handle get Teachers Need Attention Meeting
$(document).ready(function () {
	$('#get-na-meeting, #nav-attention').on('click', function (e) {
		e.preventDefault();
		$.ajax({
			type: "GET",
			url: "php/get_handle.php",
			data: "func=get_teacher_na_meetings",
			dataType: 'json',
			success: function (response) {
				var row, rowObj, namtg = '', date, meeting, count, na_count = 0, mtg_count = 0, leave_count = 0;
				meeting = response.meeting;
				count = response.leave;
				
				if (meeting.length === 0) {
					namtg = namtg +
						'<div class="container-fluid post-empty">' +
						'<p>' + 'No pending meeting requests.' + '</p></div>';
					
				} else {
					mtg_count = meeting.length;
					for (row in meeting) {
						if (meeting.hasOwnProperty(row)) {
							rowObj = meeting[row];
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
				
				//Handle na count
				if (count.length === 0) {
					na_count = mtg_count;
				} else {
					rowObj = count[0];
					leave_count = rowObj.count;
					na_count = parseInt(mtg_count, 10) + parseInt(leave_count, 10);
				}
				
				$('#nav-attention').html('<img class="icon" src="images/icons/requestIcon.png" /> Need Response (' + na_count + ')');
				$('#get-na-meeting').html('Meeting Requests (' + mtg_count + ')');
				$('#get-na-leave').html('Leave Requests (' + leave_count + ')');
				$("#display-na-meeting").css('display', 'inline', 'important');
				$("#display-na-meeting").html(namtg);
			}
		});
	});
});

//Handle Accept meeting invite
$(document).ready(function () {
	$('#display-na-meeting').on('click', '.btn-mtg-accept', function (e) {
		e.preventDefault();
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
					$("#get-na-meeting").click();
					$('#btn-nf-reload').click();
				}
				/*
				response = $.trim(response);
				
				if (response === 'Ok') {
					$("#get-na-meeting").click();
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
					$("#get-na-meeting").click();
					$('#btn-nf-reload').click();
				}
				/*
				response = $.trim(response);
				if (response === 'Ok') {
					$("#get-na-meeting").click();
				} else {
					$('#mtg-na-response').css('display', 'inline', 'important');
					$('#mtg-na-response').html("Could not process Rejecting the meeting request. Kindly refresh the page and Please try again.");
				}
				*/
			}
		});
	});
});

//Handle get Need Attention Leaves
$(document).ready(function () {
	$('#get-na-leave').on('click', function (e) {
		e.preventDefault();
		$.ajax({
			type: "GET",
			url: "php/get_handle.php",
			data: "func=get_na_leaves",
			dataType: 'json',
			success: function (response) {
				var row, result, rowObj, na_leave = '', leave, leave_date, date, meeting, count, na_count = 0, leave_count = 0, mtg_count = 0;
				leave = response.leave;
				count = response.meeting;
				
				if (leave.length === 0) {
					na_leave = na_leave +
						'<div class="container-fluid post-empty">' +
						'<p>' + 'No pending leave requests.' + '</p></div>';
					
				} else {
					leave_count = leave.length;
					for (row in leave) {
						if (leave.hasOwnProperty(row)) {
							rowObj = leave[row];
							leave_date = rowObj.LeaveDate;
							date = rowObj.CreatedOn.substr(0, 10);
							 
							na_leave = na_leave +
								'<div class="container-fluid post-nobox"><div class="row post-header">' +
								'<div class="col-sm-12"><p>' + getDayName(leave_date) + " " + leave_date + '</p></div></div>' +
								'<div class="row"><div class="col-sm-3"><p>Student Name: </p></div>' +
								'<div class="col-sm-9"><p>' + rowObj.FirstName + " " + rowObj.LastName + '</p></div></div>' +
								'<div class="row"><div class="col-sm-3"><p>Requested On: </p></div>' +
								'<div class="col-sm-9"><p>' + date + '</p></div></div>' +
								'<div class="row"><div class="col-sm-3"><p>Reason: </p></div>' +
								'<div class="col-sm-9"><p>' + rowObj.Reason + '</p></div></div>' +
								'<form id="na-leave-response-form" class="form-box horizontal-form" role="form">' +
								'<div class="form-group row"><div class="col-sm-4 col-xs-5">' +
								'<input id="' + rowObj.Id + '" name="leave-na-response" type="submit" class="btn btn-default btn-leave-approve" value="Approve"/></div>' +
								'<div class="col-sm-4 col-xs-5">' +
								'<input id="' + rowObj.Id + '" name="leave-na-response" type="submit" class="btn btn-default btn-leave-deny" value="Deny"/>' +
								'</div></div>' +
								'</form>' +
								'<div id="leave-na-response"></div>' +
								'</div>' +
								'<hr style="width: 95%; color: darkgray; height: 1px; background-color: darkgray; margin-top: 1em;"/>';
						}
					}
				}
				
				//Handle na count
				if (count.length === 0) {
					na_count = leave_count;
				} else {
					rowObj = count[0];
					mtg_count = rowObj.count;
					na_count = parseInt(leave_count, 10) + parseInt(mtg_count, 10);
				}
				
				$('#nav-attention').html('<img class="icon" src="images/icons/requestIcon.png" /> Need Response (' + na_count + ')');
				$('#get-na-meeting').html('Meeting Requests (' + mtg_count + ')');
				$('#get-na-leave').html('Leave Requests (' + leave_count + ')');
				$("#display-na-leaves").css('display', 'inline', 'important');
				$("#display-na-leaves").html(na_leave);
			}
		});
	});
});

//Handle Approve leave request
$(document).ready(function () {
	$('#display-na-leaves').on('click', '.btn-leave-approve', function (e) {
		e.preventDefault();
		var rowId, value, form_data;
		rowId = $(this).attr('id');
		value = $(this).attr('value');
		form_data = "leave-na-response=" + rowId + "&value=" + value;
		
		$.ajax({
			type: "POST",
			url: "php/response_leave.php",
			data: form_data,
			dataType: 'json',
			success: function (response) {
				if (!response.success) {
					if (response.errors.db) {
						$("#leave-na-response").html(response.errors.db);
					}
					if (response.errors.value) {
						$("#leave-na-response").html(response.errors.value);
					}
					if (response.errors.leave) {
						$("#leave-na-response").html(response.errors.leave);
					}
				} else {
					$("#get-na-leave").click();
					$('#btn-nf-reload').click();
				}
				/*
				response = $.trim(response);
				if (response === 'Ok') {
					$("#get-na-leave").click();
				} else {
					$('#leave-na-response').css('display', 'inline', 'important');
					$('#leave-na-response').html("Could not process Approving leave request. Kindly refresh the page and Please try again.");
				}
				*/
			}
		});
	});
});

//Handle Deny leave request
$(document).ready(function () {
	$('#display-na-leaves').on('click', '.btn-leave-deny', function (e) {
		e.preventDefault();
		var rowId, value, form_data;
		rowId = $(this).attr('id');
		value = $(this).attr('value');
		form_data = "leave-na-response=" + rowId + "&value=" + value;
		
		$.ajax({
			type: "POST",
			url: "php/response_leave.php",
			data: form_data,
			dataType: 'json',
			success: function (response) {
				if (!response.success) {
					if (response.errors.db) {
						$("#leave-na-response").html(response.errors.db);
					}
					if (response.errors.value) {
						$("#leave-na-response").html(response.errors.value);
					}
					if (response.errors.leave) {
						$("#leave-na-response").html(response.errors.leave);
					}
				} else {
					$("#get-na-leave").click();
					$('#btn-nf-reload').click();
				}
				/*
				response = $.trim(response);
				if (response === 'Ok') {
					$("#get-na-leave").click();
				} else {
					$('#leave-na-response').css('display', 'inline', 'important');
					$('#leave-na-response').html("Could not process Denying leave request. Kindly refresh the page and Please try again.");
				}
				*/
			}
		});
	});
});

//Handle get home page Today's update
$(document).ready(function () {
	$.ajax({
		type: "GET",
		url: "php/get_handle.php",
		data: "func=get_home",
		dataType: 'json',
		success: function (response) {
			var row, rowObj, bday, bdays = '', homework, homeworks = '', meeting, meetings = '', leave, leaves = '', event, events = '', count, na_count = 0, name, school, className, welcome = '', schoolName = '', classNames = '';
			
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
				for (row in count) {
					if (count.hasOwnProperty(row)) {
						rowObj = count[row];
						na_count += parseInt(rowObj.count, 10);
					}
				}
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
				events = events + '<p>' + 'No Events scheduled Today.' + '</p>';

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
				meetings = meetings + '<p>' + 'You have no meetings scheduled for today.' + '</p>';
			} else {
				for (row in meeting) {
					if (meeting.hasOwnProperty(row)) {
						rowObj = meeting[row];
						meetings = meetings +
							'<li><p>' + "Meeting with Parents of " + rowObj.FirstName + " " + rowObj.LastName + " at " + rowObj.StartTime + " - " + rowObj.FinishTime + '</br>' + "Location: " + rowObj.Location + '</br>' + "About: " + rowObj.Subject + '</p></li>';
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
			data: "func=get_notification",
			dataType: 'json',
			success: function (response) {
				var row, rowObj, meeting, meetings = '', leave, leaves = '', event, events = '', mattention, lattention, attention = '', recent, recents = '', rmeeting, rleave, rmeetings = '', rleaves = '', revent, revents = '', rann, ranns = '', rnotice, rnotices = '', rhomework, rhomeworks = '';
				event = response.event;
				meeting = response.meeting;
				leave = response.leave;
				mattention = response.mattention;
				lattention = response.lattention;
				recent = response.recent;
				rann = recent.ann;
				rnotice = recent.notice;
				rhomework = recent.homework;
				revent = recent.event;
				rmeeting = recent.meeting;
				rleave = recent.leave;
				
				//Handle recent
				if (rann.length === 0 && rnotice.length === 0 && rhomework.length === 0 && revent.length === 0 && rmeeting.length === 0 && rleave.length === 0) {
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
									'<li><p>New notice posted on ' + rowObj.CreatedOn.substr(0, 10) + "</br>" + 'Title: ' + rowObj.Title + '</p></li>';
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
									'<li><p>' + "New Meeting with parents of " + rowObj.FirstName + " " + rowObj.LastName + '</br>' + "When: " + rowObj.MeetingDate + " at " + rowObj.StartTime + " - " + rowObj.FinishTime + '</br>' + "Location: " + rowObj.Location + '</br>' + '</p></li>';
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
									'<li><p>New leave requested for ' + rowObj.FirstName + " " + rowObj.LastName + '.</br>' + "Leave date: " + rowObj.LeaveDate + '</p></li>';
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
								'<li><p>' + "Meeting with Parents of " + rowObj.FirstName + " " + rowObj.LastName + '</br>' + "When: " + rowObj.MeetingDate + " at " + rowObj.StartTime + " - " + rowObj.FinishTime + '</br>' + "Location: " + rowObj.Location + '</br>' + '</p></li>';
						}
					}
					meetings = '<ul>' + meetings + '</ul>';
				}
				$("#nf-upcoming-meeting").html(meetings);

				//Handle Events
				if (event.length === 0) {
					events = events + '<p>' + 'No upcoming events.' + '</p>';

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

				//Handle leave
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

				//Handle Attention
				/*
				if (mattention.length === 0 && lattention.length === 0) {
					attention = attention + '<p>' + 'No new requests that need your attention.' + '</p>';
				} else {
					if (mattention.length !== 0) {
						for (row in mattention) {
							if (mattention.hasOwnProperty(row)) {
								rowObj = mattention[row];
								attention = attention +
									'<li><p>' + "Meeting with Parents of " + rowObj.FirstName + " " + rowObj.LastName + '</br>' + "When: " + rowObj.MeetingDate + " at " + rowObj.StartTime + " - " + rowObj.FinishTime + '</br>' + "Location: " + rowObj.Location + '</br>' + '</p></li>';
							}
						}
					}

					if (lattention.length !== 0) {
						for (row in lattention) {
							if (lattention.hasOwnProperty(row)) {
								rowObj = lattention[row];
								attention = attention +
									'<li><p>' + "Leave request for " + rowObj.FirstName + " " + rowObj.LastName + '</br>' + "When: " + rowObj.LeaveDate + '</p></li>';
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

//Notification button click
$(document).ready(function () {
	$('#btn-nf-reload').click();
});

//Reset grade forms and multiselects
$(document).ready(function () {
	$('#nav-grade').on('click', function (e) {
		$("#post-grade").trigger("reset");
		$("#student-report-form").trigger("reset");
		
		$('#grade-subject-select').multiselect('deselectAll', false);
		$('#grade-subject-select').multiselect('updateButtonText');
		$('#grade-exam-select').multiselect('deselectAll', false);
		$('#grade-exam-select').multiselect('updateButtonText');
		$('#grade-student-select').multiselect('deselectAll', false);
		$('#grade-student-select').multiselect('updateButtonText');
		
		$("#post-grade-msg").html("");
		$("#display-student-report").html("");
	});
});

//Configure grade-post div
$(document).ready(function () {
	$('#post-grade-tab').on('click', function (e) {
		e.preventDefault();
		$("form").trigger("reset");
		$("#post-grade-msg").css('display', 'inline', 'important');
		$("#post-grade-msg").html("");
		$.ajax({
			type: "GET",
			url: "php/get_handle.php",
			data: "func=get_grade_configs",
			dataType: 'json',
			success: function (response) {
				var row, rowObj, subject, exam, student, subs = [], sub, exm, exms = [], tdata = '', tbody = '';
				subject = response.subject;
				exam = response.exam;
				student = response.student;
				
				if (subject.length === 0) {
					sub = {label: 'No Subjects Available', title: 'No Subjects Available'};
					subs.push(sub);
				} else {
					for (row in subject) {
						if (subject.hasOwnProperty(row)) {
							rowObj = subject[row];
							sub = {label: rowObj.Name, title: rowObj.Name, value: rowObj.Id};
							subs.push(sub);
						}
					}
				}
				$('#grade-subject-select').multiselect('dataprovider', subs);
				
				if (exam.length === 0) {
					exm = {label: 'No Exams Available', title: 'No Exams Available'};
					exms.push(exm);
				} else {
					for (row in exam) {
						if (exam.hasOwnProperty(row)) {
							rowObj = exam[row];
							exm = {label: rowObj.Name, title: rowObj.Name, value: rowObj.Id};
							exms.push(exm);
						}
					}
				}
				$('#grade-exam-select').multiselect('dataprovider', exms);
				
				if (student.length === 0) {
					tdata = "Students are not added to this class.";
				} else {
					for (row in student) {
						if (student.hasOwnProperty(row)) {
							rowObj = student[row];
							tbody += '<tr><input type="hidden" name="id[]" value="' + rowObj.Id + '"><td>' + rowObj.RollNumber + '</td>' +
									'<td>' + rowObj.FirstName + " " + rowObj.LastName + '</td>' +
									'<td><input type="text" name="marks[]" id="row-grade" required></td>' +
									'<td><select name="remark[]" id="row-remark" required><option value="Pass" selected>Pass</option>' +
									'<option value="Fail">Fail</option></select></td></tr>';
						}
					}
					tdata = '<table name="grade-marks" class="table" align="center" border="0"><thead><tr>' +
							'<td><strong>Roll No.</strong></td><td><strong>Student Name</strong></td>' +
							'<td><strong>Grade</strong></td><td><strong>Remark</strong></td></tr>' +
							'</thead><tbody>' + tbody + '</tbody></table>';
				}
				$("#table-grade-students").css('display', 'inline', 'important');
				$("#table-grade-students").html(tdata);
			}
		});
	});
});

//Handle post Grade
$(document).ready(function () {
	$('#post-grade').submit(function (e) {
		e.preventDefault();
		var form_data = $(this).serialize();
		$('#grade-subject-select').multiselect('deselectAll', false);
		$('#grade-subject-select').multiselect('updateButtonText');
		$('#grade-exam-select').multiselect('deselectAll', false);
		$('#grade-exam-select').multiselect('updateButtonText');
		$.ajax({
			type: "POST",
			url: "php/post_grade.php",
			data: form_data,
			dataType: 'json',
			success: function (response) {
				if (!response.success) {
					if (response.errors.db) {
						$("#post-grade-msg").html(response.errors.db);
					}
					if (response.errors.tMarks) {
						$("#post-grade-msg").html(response.errors.tMarks);
					}
					if (response.errors.id) {
						$("#post-grade-msg").html(response.errors.id);
					}
					if (response.errors.marks) {
						$("#post-grade-msg").html(response.errors.marks);
					}
					if (response.errors.remark) {
						$("#post-grade-msg").html(response.errors.remark);
					}
					if (response.errors.subject) {
						$("#post-grade-msg").html(response.errors.subject);
					}
					if (response.errors.exam) {
						$("#post-grade-msg").html(response.errors.exam);
					}
				} else {
					$("form").trigger("reset");
					$("#post-grade-msg").html(response.message);
					$("#post-grade-msg").delay(5000).fadeOut('slow');
				}
				/*
				response = $.trim(response);
				if (response === 'Ok') {
					$("form").trigger("reset");
					$("#post-grade-msg").css('display', 'inline', 'important');
					$("#post-grade-msg").html("Grades posted successfully.");
				} else {
					$("#post-grade-msg").css('display', 'inline', 'important');
					$("#post-grade-msg").html("Could not post Grades. Please try again.");
				}
				*/
			}
			
		});
	});
});

//Configure grade-student-select
$(document).ready(function () {
	$('#student-grade-tab').on('click', function (e) {
		e.preventDefault();
		$("form").trigger("reset");
		$("#display-student-report").html("");
		$.ajax({
			type: "GET",
			url: "php/get_handle.php",
			data: "func=get_students",
			dataType: 'json',
			success: function (response) {
				var row, rowObj, options = [], option;
				if (response.length === 0) {
					option = {label: 'No Students Available', title: 'No Students Available'};
					options.push(option);
				} else {
					for (row in response) {
						if (response.hasOwnProperty(row)) {
							rowObj = response[row];
							option = {label: rowObj.RollNumber + ': ' + rowObj.FirstName + ' ' + rowObj.LastName, title: rowObj.FirstName + rowObj.LastName, value: rowObj.Id};
							options.push(option);
						}
					}
				}
				$('#grade-student-select').multiselect('dataprovider', options);
			}
		});
	});
});

//Get Student's Report
$(document).ready(function () {
	$('#student-report-form').submit(function (e) {
		e.preventDefault();
		var form_data, exam, subject, grade, thead = '', tbody = '', trow = '', tdata = '', table_result = '', is_added = 0;
		form_data = $(this).serialize();
		$.ajax({
			type: "POST",
			url: "php/student_report.php",
			data: form_data,
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

//Configure get class report
$(document).ready(function () {
	$('#nav-grade, #class-grade-tab').on('click', function (e) {
		e.preventDefault();
		$("form").trigger("reset");
		$("#display-class-report").html("");
		$.ajax({
			type: "GET",
			url: "php/get_handle.php",
			data: "func=get_exams",
			dataType: 'json',
			success: function (response) {
				var row, rowObj, exms = [], exm;
				if (response.length === 0) {
					exm = {label: 'No Exams Available', title: 'No Exams Available'};
					exms.push(exm);
				} else {
					for (row in response) {
						if (response.hasOwnProperty(row)) {
							rowObj = response[row];
							exm = {label: rowObj.Name, title: rowObj.Name, value: rowObj.Id};
							exms.push(exm);
						}
					}
				}
				$('#grade-class-exam').multiselect('dataprovider', exms);
			}
		});
	});
});

//Get Class Test Report
$(document).ready(function () {
	$('#class-report-form').submit(function (e) {
		e.preventDefault();
		var form_data, student, subject, grade, thead = '', tbody = '', trow = '', tdata = '', table_result = '', is_added = 0;
		form_data = $(this).serialize();
		$.ajax({
			type: "POST",
			url: "php/class_report.php",
			data: form_data,
			dataType: 'json',
			success: function (response) {
				subject = response.subject;
				grade = response.grade;
				student = response.student;
				var row, rowObj, std, sub;
				is_added = 0;
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
				for (std = 0; std < student.length; std += 1) {
					trow = '<td>' + student[std].FirstName + " " + student[std].LastName + '</td>';
					for (sub = 0; sub < subject.length; sub += 1) {
						for (row in grade) {
							if (grade.hasOwnProperty(row)) {
								rowObj = grade[row];
								if (rowObj.StudentId === student[std].Id && rowObj.SubjectName === subject[sub].Name) {
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
				//$("#display-class-report").css('display', 'inline', 'important');
				$("#display-class-report").html(table_result);
				
			}
		});
	});
});

//Configure attendance-student-select dropdown
$(document).ready(function () {
	$('#nav-attendance, #new-att-tab').on('click', function (e) {
		e.preventDefault();
		$("form").trigger("reset");
		$("#post-attendance-msg").css('display', 'inline', 'important');
		$("#post-attendance-msg").html("");
		$.ajax({
			type: "GET",
			url: "php/get_handle.php",
			data: "func=get_students",
			dataType: 'json',
			success: function (response) {
				var row, rowObj, options = [], option;
				if (response.length === 0) {
					option = {label: 'No Students Available', title: 'No Students Available'};
					options.push(option);
				} else {
					for (row in response) {
						if (response.hasOwnProperty(row)) {
							rowObj = response[row];
							option = {label: rowObj.RollNumber + ': ' + rowObj.FirstName + ' ' + rowObj.LastName, title: rowObj.FirstName + rowObj.LastName, value: rowObj.Id};
							options.push(option);
						}
					}
				}
				$('#attendance-student-select').multiselect('dataprovider', options);
			}
		});
	});
});

//post attendance
$(document).ready(function () {
	$('#post-attendance').submit(function (e) {
		e.preventDefault();
		var form_data = $(this).serialize();
		$.ajax({
			type: "POST",
			url: "php/post_attendance.php",
			data: form_data,
			dataType: 'json',
			success: function (response) {
				if (!response.success) {
					if (response.errors.db) {
						$("#post-attendance-msg").html(response.errors.db);
					}
					if (response.errors.date) {
						$("#post-attendance-msg").html(response.errors.date);
					}
					if (response.errors.student) {
						$("#post-attendance-msg").html(response.errors.student);
					}
				} else {
					$("form").trigger("reset");
					$("#post-attendance-msg").html(response.message);
					$("#post-attendance-msg").delay(5000).fadeOut('slow');
				}
			}
		});
	});
});

//Configure get class attendance report
$(document).ready(function () {
	$('#get-class-attendance-tab').on('click', function (e) {
		e.preventDefault();
		$("form").trigger("reset");
		$("#display-class-attendance").html("");
		var options = [], option;
		option = {label: 'January', value: 1};
		options.push(option);
		option = {label: 'February', value: 2};
		options.push(option);
		option = {label: 'March', value: 3};
		options.push(option);
		option = {label: 'April', value: 4};
		options.push(option);
		option = {label: 'May', value: 5};
		options.push(option);
		option = {label: 'June', value: 6};
		options.push(option);
		option = {label: 'July', value: 7};
		options.push(option);
		option = {label: 'August', value: 8};
		options.push(option);
		option = {label: 'September', value: 9};
		options.push(option);
		option = {label: 'October', value: 10};
		options.push(option);
		option = {label: 'November', value: 11};
		options.push(option);
		option = {label: 'December', value: 12};
		options.push(option);
		$('#attendance-month-select').multiselect('dataprovider', options);
	});
});

//Handle get Class Attendance Report
$(document).ready(function () {
	$('#class-attendance-form').submit(function (e) {
		e.preventDefault();
		var form_data, student, attendance, thead = '', tbody = '', trow = '', tdata = '', table_result = '', is_added = 0, days = 0, day, month, year, holiday;
		form_data = $(this).serialize();
		$.ajax({
			type: "POST",
			url: "php/class_attendance.php",
			data: form_data,
			dataType: 'json',
			success: function (response) {

				attendance = response.attendance;
				student = response.student;
				month = response.month;
				holiday = response.holiday;
				
				var row, rowObj, std, d, dt, dt_str, weekend, absent_count = 0, yesHoliday = false;
				
				//year = (new Date()).getFullYear();
				year = getYear(month);
				days = daysInMonth(month, year);
				
				for (day = 1; day <= days; day += 1) {
					thead += '<th>' + day + '</th>';
				}
				thead = '<thead><tr><th>Result</th><th>Absent Days</th>' + thead + '</tr></thead>';
				
				for (std = 0; std < student.length; std += 1) {
					absent_count = getAbsentCount(attendance, student[std].Id);
					trow = '<td>' + student[std].FirstName + " " + student[std].LastName + '</td><td>' + absent_count + '</td>';
					
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
								if (rowObj.StudentId === student[std].Id &&  d.getDate() === day && is_added === 0) {
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
				table_result = '<table name="class-attendance-table" class="table" align="center" border="0">' + thead + tdata + '</table>';
				//$("#display-class-report").css('display', 'inline', 'important');
				$("#display-class-attendance").html(table_result);
				
			}
		});
	});
});

//Configure get student attendance report
$(document).ready(function () {
	$('#get-student-attendance-tab').on('click', function (e) {
		e.preventDefault();
		$("form").trigger("reset");
		$("#display-student-attendance").html("");
		$.ajax({
			type: "GET",
			url: "php/get_handle.php",
			data: "func=get_students",
			dataType: 'json',
			success: function (response) {
				var row, rowObj, options = [], option;
				if (response.length === 0) {
					option = {label: 'No Students Available', title: 'No Students Available'};
					options.push(option);
				} else {
					for (row in response) {
						if (response.hasOwnProperty(row)) {
							rowObj = response[row];
							option = {label: rowObj.RollNumber + ': ' + rowObj.FirstName + ' ' + rowObj.LastName, title: rowObj.FirstName + rowObj.LastName, value: rowObj.Id};
							options.push(option);
						}
					}
				}
				$('#attendance-report-student-select').multiselect('dataprovider', options);
			}
		});
	});
});

//Handle get student attendance report
$(document).ready(function () {
	$('#student-attendance-form').submit(function (e) {
		e.preventDefault();
		var form_data, student, attendance, holiday, thead = '', tbody = '', trow = '', tdata = '', table_result = '', is_added = 0, days = 0, day, month, year;
		form_data = $(this).serialize();
		$.ajax({
			type: "POST",
			url: "php/student_attendance.php",
			data: form_data,
			dataType: 'json',
			success: function (response) {
				var row, rowObj, std, d, dt, dt_str, weekend, absent_count = 0, yesHoliday = false, months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
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

//Handle page height
$(document).ready(function () {
	setHeight();
	var highestCol = Math.max($('#middle').height(), $('#right').height());
	$('#middle').height(highestCol);
});
