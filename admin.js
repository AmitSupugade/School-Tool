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
	} else {
		return "Incorrect Status";
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

//Function to display Add Teacher on click
$(document).ready(function () {
    $("#nav-teacher").click(function () {
        $(".toggle-nav").hide();
        $("#teacher").show();
    });
});

//Function to display Add Class on click
$(document).ready(function () {
    $("#nav-class").click(function () {
        $(".toggle-nav").hide();
        $("#class").show();
    });
});

//Function to display Add Class on click
$(document).ready(function () {
    $("#nav-class-teacher").click(function () {
        $(".toggle-nav").hide();
        $("#class-teacher").show();
    });
});

//Function to display Subject on click
$(document).ready(function () {
    $("#nav-subject").click(function () {
        $(".toggle-nav").hide();
        $("#subject").show();
    });
});

//Function to display Exam on click
$(document).ready(function () {
    $("#nav-exam").click(function () {
        $(".toggle-nav").hide();
        $("#exam").show();
    });
});

//Function to display Add Student on click
$(document).ready(function () {
    $("#nav-student").click(function () {
        $(".toggle-nav").hide();
        $("#student").show();
    });
});

//Function to display holiday on click
$(document).ready(function () {
    $("#nav-holiday").click(function () {
        $(".toggle-nav").hide();
        $("#holiday").show();
    });
});

//Function to display photo on click
$(document).ready(function () {
    $("#nav-photo").click(function () {
        $(".toggle-nav").hide();
        $("#photo").show();
    });
});


//Function to handle Holiday date picker
$(function () {
	$('#holiday-date').datepicker({
		autoclose: true,
		format: "yyyy-mm-dd",
		daysOfWeekDisabled: "0",
		orientation: "bottom"
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
	$('#class-select').ismultiselect();
	$('#ct-class-select').ismultiselect();
	$('#teacher-select').ismultiselect();
	$('#student-class-select').ismultiselect();
	$('#album-select').ismultiselect();
	$('#sub-class-select').ismultiselect();
	$('#subject-select').ismultiselect();
	$('#exm-class-select').ismultiselect();
	$('#exam-select').ismultiselect();
});

//Get Photos
function get_photo(album_id) {
	$.ajax({
		type: "POST",
		url: "php/get_gallary.php",
		data: {albumId : album_id},
		dataType: 'json',
		success: function (response) {
			var row, rowObj, data, final_result = '', result = '', count = 0;

			data = response;
			console.log(data.length);
			if (data.length === 0) {
				final_result = "No photos added to this Album";
			} else {
				for (row in data) {
					if (data.hasOwnProperty(row)) {
						rowObj = data[row];
						console.log(rowObj.DbFileName);
						result += '<a href="photos/' + rowObj.DbFileName + '" data-toggle="lightbox" data-gallery="gallery" class="col-sm-3">' +
							'<img src="photos/' + rowObj.DbFileName + '" class="img-fluid thumbnail img-responsive photo"></a>';
						count += 1;
						if (count === 4) {
							final_result += '<div class="row">' + result + '</div>';
							result = '';
							count = 0;
						}
					}
				}
				if (count !== 4) {
					final_result += '<div class="row">' + result + '</div>';
				}
			}
			$("#display-gallary").html(final_result);
		}
	});
}

$(document).on('click', '[data-toggle="lightbox"]', function (event) {
    event.preventDefault();
    $(this).ekkoLightbox({
		alwaysShowClose: true,
		scale_height: true
	});
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
			if (response !== 'admin') {
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

//Handle get home page
$(document).ready(function () {
	$.ajax({
		type: "GET",
		url: "php/get_handle.php",
		data: "func=get_admin_home",
		dataType: 'json',
		success: function (response) {
			var row, rowObj, school = '', schoolName = '';
			
			if (response.length === 0) {
				school = school +
						'<div class="container-fluid post-empty">' +
						'<p>' + 'No School Info Available.' + '</p></div>';
			} else {
				for (row in response) {
					if (response.hasOwnProperty(row)) {
						rowObj = response[row];
						schoolName = rowObj.Name + ", " + rowObj.City;
						school = school +
							'<div class="container-fluid post-nobox">' +
							'<div class="row"><div class="col-sm-4 col-sm-offset-1"><p><strong>Name:</strong></p></div>' +
							'<div class="col-sm-7"><p>' + rowObj.Name + '</p></div></div>' +
							'<div class="row"><div class="col-sm-4 col-sm-offset-1"><p><strong>Established Year:</strong></p></div>' +
							'<div class="col-sm-7"><p>' + rowObj.EstablishmentYear + '</p></div></div>' +
							'<div class="row"><div class="col-sm-4 col-sm-offset-1"><p><strong>Address:</strong></p></div>' +
							'<div class="col-sm-7"><p>' + rowObj.Address + '</p></div></div>' +
							'<div class="row"><div class="col-sm-4 col-sm-offset-1"><p><strong>City:</strong></p></div>' +
							'<div class="col-sm-7"><p>' + rowObj.City + '</p></div></div>' +
							'<div class="row"><div class="col-sm-4 col-sm-offset-1"><p><strong>PinCode:</strong></p></div>' +
							'<div class="col-sm-7"><p>' + rowObj.PinCode + '</p></div></div>' +
							'<div class="row"><div class="col-sm-4 col-sm-offset-1"><p><strong>Phone Number:</strong></p></div>' +
							'<div class="col-sm-7"><p>' + rowObj.PhoneNumber + '</p></div></div>' +
							'<div class="row"><div class="col-sm-4 col-sm-offset-1"><p><strong>Email:</strong></p></div>' +
							'<div class="col-sm-7"><p>' + rowObj.Email + '</p></div></div>' +
							'<div class="row"><div class="col-sm-4 col-sm-offset-1"><p><strong>Website:</strong></p></div>' +
							'<div class="col-sm-7"><p>' + rowObj.Website + '</p></div></div>' +
							'</div>';
					}
				}
			}
			$("#school-name").html(schoolName);
			$("#school-name-xs").html(schoolName);
			$("#display-school-info").css('display', 'inline', 'important');
			$("#display-school-info").html(school);
		}
	});
});

//Handle post Holiday Date
$(document).ready(function () {
	$('#post-holiday').submit(function (e) {
		e.preventDefault();
		var form_data = $(this).serialize();
		$.ajax({
			type: "POST",
			url: "php/post_holiday.php",
			data: form_data,
			dataType: 'json',
			success: function (response) {
				if (!response.success) {
					if (response.errors.db) {
						$("#post-holiday-msg").html(response.errors.db);
					}
					if (response.errors.date) {
						$("#post-holiday-msg").html(response.errors.date);
					}
				} else {
					$("form").trigger("reset");
					$("#post-holiday-msg").html(response.message);
					$("#post-holiday-msg").delay(5000).fadeOut('slow');
				}
			}
		});
		$('#nav-holiday').click();
	});
});

//Reset teachers tab
$(document).ready(function () {
	$('#nav-teacher, #add-teacher-tab').on('click', function (e) {
		e.preventDefault();
		$("form").trigger("reset");
		$("#teacher-upload-msg").css('display', 'inline', 'important');
		$("#teacher-upload-msg").html("");
	});
});

//Handle Upload teachers from file
$(document).ready(function () {
	$('#export_teacher').submit(function (e) {
		e.preventDefault();
		var form_data = new FormData(this);
		console.log(form_data);
		$.ajax({
			type: "POST",
			url: "php/upload_teacher.php",
			data: form_data,
			contentType: false,
			processData: false,
			dataType: 'json',
			success: function (response) {
				console.log(response);
				if (!response.success) {
					if (response.errors.db) {
						$("#teacher-upload-msg").html(response.errors.db);
					}
					if (response.errors.noUpload) {
						$("#teacher-upload-msg").html(response.errors.noUpload);
					}
					if (response.errors.fileError) {
						$("#teacher-upload-msg").html(response.errors.fileError);
					}
					if (response.errors.empty) {
						$("#teacher-upload-msg").html(response.errors.empty);
					}
					if (response.errors.wrongFormat) {
						$("#teacher-upload-msg").html(response.errors.wrongFormat);
					}
				} else {
					$("form").trigger("reset");
					$("#teacher-upload-msg").html(response.message);
					//$("#teacher-upload-msg").delay(5000).fadeOut('slow');
				}
			}
		});
	});
});

//Handle teachers and classes
$(document).ready(function () {
	$('#get-teacher-tab').on('click', function (e) {
		e.preventDefault();
		$.ajax({
			type: "GET",
			url: "php/get_handle.php",
			data: "func=get_school_teachers",
			dataType: 'json',
			success: function (response) {
				var row, rowObj, thead = '', trow = '', tbody = '', tdata = '', table_result = '';
				if (response.length === 0) {
					table_result = "<thead><tr><th></th><th>No teachers added.</th></tr></thead>";
				} else {
					thead = '<thead><tr><th>FirstName</th><th>MiddleName</th><th>LastName</th><th>ClassName</th><th>Contact#</th><th>Email</th><th>DateOfBirth</th><th>Gender</th></tr></thead>';
				
					for (row in response) {
						if (response.hasOwnProperty(row)) {
							rowObj = response[row];
							trow = '<td>' + rowObj.FirstName + '</td>' + '<td>' + rowObj.MiddleName + '</td>' + '<td>' + rowObj.LastName +  '</td>' + '<td>' + rowObj.Name + '</td>' + '<td>' + rowObj.MobileNumber + '</td>' + '<td>' + rowObj.Email + '</td>' + '<td>' + rowObj.DateOfBirth + '</td>' + '<td>' + rowObj.Gender + '</td>';
							tbody += '<tr>' + trow + '</tr>';
						}
					}
					tdata = '<tbody>' + tbody + '</tbody>';
				}
				table_result = '<table name="teacher-list" class="table" align="center" border="0">' + thead + tdata + '</table>';
				$("#display-teachers").html(table_result);
			}
		});
	});
});

//Handle Get classrooms
$(document).ready(function () {
	$('#nav-class').on('click', function (e) {
		e.preventDefault();
		$("form").trigger("reset");
		$("#add-class-msg").css('display', 'inline', 'important');
		$("#add-class-msg").html("");
		$.ajax({
			type: "GET",
			url: "php/get_handle.php",
			data: "func=get_classroom",
			dataType: 'json',
			success: function (response) {
				var row, rowObj, options = [], option;
				if (response.length === 0) {
					option = {label: 'No Classes Available', title: 'No Classes Available'};
					options.push(option);
				} else {
					for (row in response) {
						if (response.hasOwnProperty(row)) {
							rowObj = response[row];
							option = {label: rowObj.Name, value: rowObj.Id};
							options.push(option);
						}
					}
				}
				$('#class-select').multiselect('dataprovider', options);
			}
		});
	});
});

//Handle Add Class form submit
$(document).ready(function () {
	$('#add-class').submit(function (e) {
		e.preventDefault();
		var form_data = $(this).serialize();
		console.log(form_data);
		$.ajax({
			type: "POST",
			url: "php/add_class.php",
			data: form_data,
			dataType: 'json',
			success: function (response) {
				if (!response.success) {
					if (response.errors.db) {
						$("#add-class-msg").html(response.errors.db);
					}
					if (response.errors.cls) {
						$("#add-class-msg").html(response.errors.cls);
					}
				} else {
					$("form").trigger("reset");
					$("#add-class-msg").html(response.message);
					$("#add-class-msg").delay(5000).fadeOut('slow');
				}
			}
		});
	});
});

//Configure Assign Teacher
$(document).ready(function () {
	$('#nav-class-teacher').on('click', function (e) {
		e.preventDefault();
		$("form").trigger("reset");
		$("#class-teacher-msg").css('display', 'inline', 'important');
		$("#class-teacher-msg").html("");
		$.ajax({
			type: "GET",
			url: "php/get_handle.php",
			data: "func=get_class_and_teacher",
			dataType: 'json',
			success: function (response) {
				console.log(response);
				var row, rowObj, cls, cls_option, cls_options = [], teacher, teacher_option, teacher_options = [];
				cls = response.cls;
				teacher = response.teacher;
				
				console.log(response);
				console.log(cls);
				console.log(teacher);
				
				if (cls.length === 0) {
					cls_option = {label: 'No Classes Available', title: 'No Classes Available'};
					cls_options.push(cls_option);
				} else {
					for (row in cls) {
						if (cls.hasOwnProperty(row)) {
							rowObj = cls[row];
							cls_option = {label: rowObj.Name, value: rowObj.Id};
							cls_options.push(cls_option);
						}
					}
				}
				$('#ct-class-select').multiselect('dataprovider', cls_options);
				
				if (teacher.length === 0) {
					teacher_option = {label: 'No Teachers Available', title: 'No Teachers Available'};
					teacher_options.push(teacher_option);
				} else {
					for (row in teacher) {
						if (teacher.hasOwnProperty(row)) {
							rowObj = teacher[row];
							teacher_option = {label: rowObj.TeacherName, value: rowObj.Id};
							teacher_options.push(teacher_option);
						}
					}
				}
				$('#teacher-select').multiselect('dataprovider', teacher_options);
			}
		});
	});
});

//Handle Assign Teacher Submit
$(document).ready(function () {
	$('#assign-class-teacher').submit(function (e) {
		e.preventDefault();
		var form_data = $(this).serialize();
		console.log(form_data);
		$.ajax({
			type: "POST",
			url: "php/assign_teacher.php",
			data: form_data,
			dataType: 'json',
			success: function (response) {
				if (!response.success) {
					if (response.errors.db) {
						$("#class-teacher-msg").html(response.errors.db);
					}
					if (response.errors.cls) {
						$("#class-teacher-msg").html(response.errors.cls);
					}
					if (response.errors.teacher) {
						$("#class-teacher-msg").html(response.errors.teacher);
					}
				} else {
					$("form").trigger("reset");
					$("#class-teacher-msg").html(response.message);
					$("#class-teacher-msg").delay(5000).fadeOut('slow');
				}
			}
		});
	});
});

//Configure upload students
$(document).ready(function () {
	$('#nav-student').on('click', function (e) {
		e.preventDefault();
		$("form").trigger("reset");
		$("#student_upload-msg").css('display', 'inline', 'important');
		$("#student_upload-msg").html("");
		$.ajax({
			type: "GET",
			url: "php/get_handle.php",
			data: "func=get_school_class",
			dataType: 'json',
			success: function (response) {
				var row, rowObj, options = [], option;
				if (response.length === 0) {
					option = {label: 'No Classes Available', title: 'No Classes Available'};
					options.push(option);
				} else {
					for (row in response) {
						if (response.hasOwnProperty(row)) {
							rowObj = response[row];
							option = {label: rowObj.Name, value: rowObj.Id};
							options.push(option);
						}
					}
				}
				$('#student-class-select').multiselect('dataprovider', options);
			}
		});
	});
});

//Handle Upload students from file
$(document).ready(function () {
	$('#export_student').submit(function (e) {
		e.preventDefault();
		var form_data = new FormData(this);
		$.ajax({
			type: "POST",
			url: "php/upload_student.php",
			data: form_data,
			contentType: false,
			processData: false,
			dataType: 'json',
			success: function (response) {
				console.log(response);
				if (!response.success) {
					if (response.errors.db) {
						$("#student-upload-msg").html(response.errors.db);
					}
					if (response.errors.noUpload) {
						$("#student-upload-msg").html(response.errors.noUpload);
					}
					if (response.errors.fileError) {
						$("#student-upload-msg").html(response.errors.fileError);
					}
					if (response.errors.wrongFormat) {
						$("#student-upload-msg").html(response.errors.wrongFormat);
					}
					if (response.errors.empty) {
						$("#student-upload-msg").html(response.errors.empty);
					}
					if (response.errors.selectCls) {
						$("#student-upload-msg").html(response.errors.selectCls);
					}
				} else {
					$("form").trigger("reset");
					$("#student-upload-msg").html(response.message);
					$("#student-upload-msg").delay(5000).fadeOut('slow');
				}
			}
		});
	});
});

//See holidays
$(document).ready(function () {
	$('#nav-holiday').on('click', function (e) {
		e.preventDefault();
		$("form").trigger("reset");
		$.ajax({
			type: "GET",
			url: "php/get_handle.php",
			data: "func=get_holidays",
			dataType: 'json',
			success: function (response) {
				var row, rowObj, tdata = '', thead = '', tbody = '', table_result = '', count = 1;
				if (response.length === 0) {
					table_result = "No holiday added yet";
				} else {
					thead = '<thead><tr><th>Sr.No.</th><th> Date </th></tr></thead>';
					for (row in response) {
						if (response.hasOwnProperty(row)) {
							rowObj = response[row];
							tbody += '<tr><td>' + count + '</td><td>' + rowObj.Date + '</td></tr>';
						}
						count += 1;
					}
					tdata = '<tbody>' + tbody + '</tbody>';
					table_result = '<table name="holiday-table" class="table" align="center" border="0">' + thead + tdata + '</table>';
				}
				$("#display-holiday").html(table_result);
			}
		});
	});
});

//See classes 
$(document).ready(function () {
	$('#nav-class').on('click', function (e) {
		e.preventDefault();
		$("form").trigger("reset");
		$.ajax({
			type: "GET",
			url: "php/get_handle.php",
			data: "func=get_school_class",
			dataType: 'json',
			success: function (response) {
				var row, rowObj, tdata = '', thead = '', tbody = '', table_result = '', count = 1;
				if (response.length === 0) {
					table_result = "No classes added for this school.";
				} else {
					thead = '<thead><tr><th>Sr.No.</th><th> ClassName </th></tr></thead>';
					for (row in response) {
						if (response.hasOwnProperty(row)) {
							rowObj = response[row];
							tbody += '<tr><td>' + count + '</td><td>' + rowObj.Name + '</td></tr>';
						}
						count += 1;
					}
					tdata = '<tbody>' + tbody + '</tbody>';
					table_result = '<table name="holiday-table" class="table" align="center" border="0">' + thead + tdata + '</table>';
				}
				$("#display-class").html(table_result);
			}
		});
	});
});

//Configure post photos
$(document).ready(function () {
	$('#nav-photo, #add-photo-tab').on('click', function (e) {
		e.preventDefault();
		$.ajax({
			type: "GET",
			url: "php/get_handle.php",
			data: "func=get_albums",
			dataType: 'json',
			success: function (response) {
				var row, rowObj, album, albums = [];
				console.log(response);
				if (response.length === 0) {
					album = {label: 'No Albums Available', title: 'No Albums Available'};
					albums.push(album);
				} else {
					for (row in response) {
						if (response.hasOwnProperty(row)) {
							rowObj = response[row];
							album = {label: rowObj.Name, title: rowObj.Name, value: rowObj.Id};
							albums.push(album);
						}
					}
				}
				$('#album-select').multiselect('dataprovider', albums);
			}
		});
	});
});

//Handle Add Album
$(document).ready(function () {
	$('#add-album').submit(function (e) {
		e.preventDefault();
		var form_data = $(this).serialize();
		$.ajax({
			type: "POST",
			url: "php/add_album.php",
			data: form_data,
			dataType: 'json',
			success: function (response) {
				if (!response.success) {
					if (response.errors.db) {
						$("#add-album-msg").html(response.errors.db);
					}
					if (response.errors.title) {
						$("#add-album-msg").html(response.errors.title);
					}
				} else {
					$("form").trigger("reset");
					$("#add-album-msg").html(response.message);
					//$("#add-album-msg").delay(5000).fadeOut('slow');
				}
			}
		});
		$('#nav-photo').click();
	});
});

//View Gallery
$(document).ready(function () {
	$('#get-gallery-tab').on('click', function (e) {
		e.preventDefault();
		$.ajax({
			type: "GET",
			url: "php/get_handle.php",
			data: "func=get_albums",
			dataType: 'json',
			success: function (response) {
				var row, rowObj, result = '', final_result = '', count = 0;
				if (response.length === 0) {
					result = result +
						'<div class="container-fluid post-empty">' +
						'<p>' + 'No Albums/Photos posted yet.' + '</p></div>';
				} else {
					for (row in response) {
						if (response.hasOwnProperty(row)) {
							rowObj = response[row];
							result += '<div class="col-sm-6 col-md-3">' +
								'<div class="thumbnail"><a class="a-thumbnail" onclick=get_photo(' + rowObj.Id + ')><img src="photos/' + rowObj.Thumbnail + '" alt="' + rowObj.Name + '" class="img-thumbnail" style="width: 200px; height: 200px">' +
								'<div class="caption"><h3>' + rowObj.Name + '</h3></div></a>' +
								'</div></div>';
							count += 1;
							if (count === 4) {
								final_result += '<div class="row">' + result + '</div>';
								result = '';
								count = 0;
							}
						}
					}
					if (count !== 4) {
						final_result += '<div class="row">' + result + '</div>';
					}
				}
			
				$("#display-gallary").html(final_result);
			}
		});
	});
});

//Handle add photos
$(document).ready(function () {
	$('#add-photo-form').submit(function (e) {
		e.preventDefault();
		var form_data = new FormData(this);
		console.log(form_data);
		$.ajax({
			type: "POST",
			url: "php/add_photo.php",
			data: form_data,
			contentType: false,
			processData: false,
			dataType: 'json',
			success: function (response) {
				console.log(response);
				if (!response.success) {
					if (response.errors.db) {
						$("#add-photo-msg").html(response.errors.db);
					}
					if (response.errors.album) {
						$("#add-photo-msg").html(response.errors.album);
					}
					if (response.errors.noUpload) {
						$("#add-photo-msg").html(response.errors.noUpload);
					}
					if (response.errors.fileError) {
						$("#add-photo-msg").html(response.errors.fileError);
					}
					if (response.errors.wrongFormat) {
						$("#add-photo-msg").html(response.errors.wrongFormat);
					}
				} else {
					$("form").trigger("reset");
					$("#add-photo-msg").html(response.message);
					//$("#add-photo-msg").delay(5000).fadeOut('slow');
				}
			}
		});
	});
});

//Configure Add Subjects
$(document).ready(function () {
	$('#nav-subject').on('click', function (e) {
		e.preventDefault();
		$("form").trigger("reset");
		$("#add-subject-msg").css('display', 'inline', 'important');
		$("#add-subject-msg").html("");
		$.ajax({
			type: "GET",
			url: "php/get_handle.php",
			data: "func=get_class_and_subject",
			dataType: 'json',
			success: function (response) {
				console.log(response);
				var row, rowObj, cls, cls_option, cls_options = [], subject, subject_option, subject_options = [];
				cls = response.cls;
				subject = response.subject;
				
				if (cls.length === 0) {
					cls_option = {label: 'No Classes Available', title: 'No Classes Available'};
					cls_options.push(cls_option);
				} else {
					for (row in cls) {
						if (cls.hasOwnProperty(row)) {
							rowObj = cls[row];
							cls_option = {label: rowObj.Name, value: rowObj.Id};
							cls_options.push(cls_option);
						}
					}
				}
				$('#sub-class-select').multiselect('dataprovider', cls_options);
				
				if (subject.length === 0) {
					subject_option = {label: 'No Subjects Available', title: 'No Subjects Available'};
					subject_options.push(subject_option);
				} else {
					for (row in subject) {
						if (subject.hasOwnProperty(row)) {
							rowObj = subject[row];
							subject_option = {label: rowObj.Name, value: rowObj.Id};
							subject_options.push(subject_option);
						}
					}
				}
				$('#subject-select').multiselect('dataprovider', subject_options);
			}
		});
	});
});

//Handle Add Subjects
$(document).ready(function () {
	$('#add-subject').submit(function (e) {
		e.preventDefault();
		var form_data = $(this).serialize();
		$.ajax({
			type: "POST",
			url: "php/add_subject.php",
			data: form_data,
			dataType: 'json',
			success: function (response) {
				if (!response.success) {
					if (response.errors.db) {
						$("#add-subject-msg").html(response.errors.db);
					}
					if (response.errors.cls) {
						$("#add-subject-msg").html(response.errors.cls);
					}
					if (response.errors.subject) {
						$("#add-subject-msg").html(response.errors.subject);
					}
				} else {
					$("form").trigger("reset");
					$("#add-subject-msg").html(response.message);
					//$("#add-album-msg").delay(5000).fadeOut('slow');
				}
			}
		});
	});
});

//Configure Add Exams
$(document).ready(function () {
	$('#nav-exam').on('click', function (e) {
		e.preventDefault();
		$("form").trigger("reset");
		$("#add-exam-msg").css('display', 'inline', 'important');
		$("#add-exam-msg").html("");
		$.ajax({
			type: "GET",
			url: "php/get_handle.php",
			data: "func=get_class_and_exam",
			dataType: 'json',
			success: function (response) {
				var row, rowObj, cls, cls_option, cls_options = [], exam, exam_option, exam_options = [];
				cls = response.cls;
				exam = response.exam;

				if (cls.length === 0) {
					cls_option = {label: 'No Classes Available', title: 'No Classes Available'};
					cls_options.push(cls_option);
				} else {
					for (row in cls) {
						if (cls.hasOwnProperty(row)) {
							rowObj = cls[row];
							cls_option = {label: rowObj.Name, value: rowObj.Id};
							cls_options.push(cls_option);
						}
					}
				}
				$('#exm-class-select').multiselect('dataprovider', cls_options);
				
				if (exam.length === 0) {
					exam_option = {label: 'No exams Available', title: 'No exams Available'};
					exam_options.push(exam_option);
				} else {
					for (row in exam) {
						if (exam.hasOwnProperty(row)) {
							rowObj = exam[row];
							exam_option = {label: rowObj.Name, value: rowObj.Id};
							exam_options.push(exam_option);
						}
					}
				}
				$('#exam-select').multiselect('dataprovider', exam_options);
			}
		});
	});
});

//Handle Add exams
$(document).ready(function () {
	$('#add-exam').submit(function (e) {
		e.preventDefault();
		var form_data = $(this).serialize();
		console.log(form_data);
		$.ajax({
			type: "POST",
			url: "php/add_exam.php",
			data: form_data,
			dataType: 'json',
			success: function (response) {
				console.log(response);
				if (!response.success) {
					if (response.errors.db) {
						$("#add-exam-msg").html(response.errors.db);
					}
					if (response.errors.cls) {
						$("#add-exam-msg").html(response.errors.cls);
					}
					if (response.errors.exam) {
						$("#add-exam-msg").html(response.errors.exam);
					}
				} else {
					$("form").trigger("reset");
					$("#add-exam-msg").html(response.message);
					//$("#add-album-msg").delay(5000).fadeOut('slow');
				}
			}
		});
	});
});



