/*jslint node: true */
/*jslint browser: true*/
/*global $, jQuery, alert*/

"use strict";

function getAlert(val) {
	if (val === '1') {
		return "Yes";
	} else if (val === '0') {
		return "No";
	} else {
		return "Unknown. Please update.";
	}
}

function isAlert(alert) {
	if (alert === '1') {
		return true;
	} else {
		return false;
	}
}

//Session Check
$(document).ready(function () {
	$.ajax({
		type: "GET",
		url: "php/session.php",
		cache: false,
		success: function (response) {
			response = $.trim(response);
			if (response !== 'teacher') {
				window.location = "index.html";
			}
		}
	});
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

//Handle get Name
$(document).ready(function () {
	$.ajax({
		type: "GET",
		url: "php/get_handle.php",
		data: "func=get_my_name",
		dataType: 'json',
		success: function (response) {
			var welcome, row, rowObj;
			if (response.length === 0) {
				welcome = 'Welcome';
			} else {
				welcome = 'Welcome ' + response.FirstName;
			}
			$("#welcome-msg").css('display', 'inline', 'important');
			$("#welcome-msg").html(welcome);
		}
	});
});

//Function to handle View profile button
$(document).ready(function () {
    $(".btn-view-profile").click(function () {
        $("#edit").hide();
        $("#view").show();
		$("#change").hide();
		$(".btn-view-profile").hide();
		$(".btn-edit-profile").show();
		$(".btn-change-password").show();
		$.ajax({
			type: "GET",
			url: "php/get_handle.php",
			data: "func=get_teacher_profile",
			dataType: 'json',
			success: function (response) {
				var row, rowObj, tinfo = '', alert = '';
				if (response.length === 0) {
					tinfo = tinfo +
						'<div class="container-fluid post-empty">' +
						'<p>' + 'Profile data is not available.' + '</p></div>';

				} else {
					for (row in response) {
						if (response.hasOwnProperty(row)) {
							rowObj = response[row];
							alert = getAlert(rowObj.EmailAlert);
							tinfo = tinfo +
								'<div class="container-fluid post-nobox">' +
								'<div class="row"><div class="col-md-4 col-md-offset-1"><p class="p-label">First Name:</p></div>' +
								'<div class="col-md-7"><p>' + rowObj.FirstName + '</p></div></div>' +
								'<div class="row"><div class="col-md-4 col-md-offset-1"><p class="p-label">Middle Name:</p></div>' +
								'<div class="col-md-7"><p>' + rowObj.MiddleName + '</p></div></div>' +
								'<div class="row"><div class="col-md-4 col-md-offset-1"><p class="p-label">Last Name:</p></div>' +
								'<div class="col-md-7"><p>' + rowObj.LastName + '</p></div></div>' +
								'<div class="row"><div class="col-md-4 col-md-offset-1"><p class="p-label">Mobile#:</p></div>' +
								'<div class="col-md-7"><p>' + rowObj.MobileNumber + '</p></div></div>' +
								'<div class="row"><div class="col-md-4 col-md-offset-1"><p class="p-label">Email:</p></div>' +
								'<div class="col-md-7"><p>' + rowObj.Email + '</p></div></div>' +
								'<div class="row"><div class="col-md-4 col-md-offset-1"><p class="p-label">Desk Location:</p></div>' +
								'<div class="col-md-7"><p>' + rowObj.DeskLocation + '</p></div></div>' +
								'<div class="row"><div class="col-md-4 col-md-offset-1"><p class="p-label">Education:</p></div>' +
								'<div class="col-md-7"><p>' + rowObj.Education + '</p></div></div>' +
								'<div class="row"><div class="col-md-4 col-md-offset-1"><p class="p-label">Designation:</p></div>' +
								'<div class="col-md-7"><p>' + rowObj.Designation + '</p></div></div>' +
								'<div class="row"><div class="col-md-4 col-md-offset-1"><p class="p-label">Experience:</p></div>' +
								'<div class="col-md-7"><p>' + rowObj.Experience + '</p></div></div>' +
								'<div class="row"><div class="col-md-4 col-md-offset-1"><p class="p-label">Date of Birth:</p></div>' +
								'<div class="col-md-7"><p>' + rowObj.DateOfBirth + '</p></div></div>' +
								'<div class="row"><div class="col-md-4 col-md-offset-1"><p class="p-label">Gender:</p></div>' +
								'<div class="col-md-7"><p>' + rowObj.Gender + '</p></div></div>' +
								'<div class="row"><div class="col-md-4 col-md-offset-1"><p class="p-label">About me:</p></div>' +
								'<div class="col-md-7"><p>' + rowObj.Description + '</p></div></div>' +
								'<div class="row"><div class="col-md-8 col-md-offset-1"><p><strong>Receive instant email updates:</strong></p></div>' +
								'<div class="col-md-2"><p>' + alert + '</p></div></div>' +
								'</div>';
						}
					}
				}
				//$("#display-profile").css('display', 'inline', 'important');
				$("#display-profile").html(tinfo);
			}
		});
    });
});

//Function to handle cancel button on edit profile page
$(document).ready(function () {
    $("#btn-edit-profile-cancel").click(function (e) {
		e.preventDefault();
        $("#edit").hide();
        $("#view").show();
		$("#change").hide();
		$(".btn-view-profile").hide();
		$(".btn-edit-profile").show();
		$(".btn-change-password").show();
    });
});

//Handle View Teacher Profile
$(document).ready(function () {
	$(".btn-view-profile").click();
});

//Function to handle edit profile button
$(document).ready(function () {
    $(".btn-edit-profile").click(function () {
        $("#view").hide();
        $("#edit").show();
		$("#change").hide();
		$(".btn-edit-profile").hide();
		$(".btn-view-profile").show();
		$(".btn-change-password").hide();
		$.ajax({
			type: "GET",
			url: "php/get_handle.php",
			data: "func=get_teacher_profile",
			dataType: 'json',
			success: function (response) {
				var row, rowObj, tinfo = '', tinfo_html, alert;
				if (response.length === 0) {
					tinfo = tinfo +
						'<div class="container-fluid post-empty">' +
						'<p>' + 'Profile data is not available.' + '</p></div>';
				} else {
					for (row in response) {
						if (response.hasOwnProperty(row)) {
							rowObj = response[row];
							/*
							tinfo = tinfo +
								'<form id="form-edit-profile" class="form-box horizontal-form" role="form">' +
								'<div class="form-group"><label class="col-md-4 control-label">First name:</label><div class="col-md-8">' +
								'<input class="form-control" type="text" name="f-name" value="' + rowObj.FirstName + '"></div></div>' +
								'<div class="form-group"><label class="col-md-4 control-label">Middle name:</label><div class="col-md-8">' +
								'<input class="form-control" type="text" name="m-name" value="' + rowObj.MiddleName + '"></div></div>' +
								'<div class="form-group"><label class="col-md-4 control-label">Last name:</label><div class="col-md-8">' +
								'<input class="form-control" type="text" name="l-name" value="' + rowObj.LastName + '"></div></div>' +
								'<div class="form-group"><label class="col-md-4 control-label">Mobile #:</label><div class="col-md-8">' +
								'<input class="form-control" type="number" min="1000000000" name="mobile" value="' + rowObj.MobileNumber + '"></div></div>' +
								'<div class="form-group"><label class="col-md-4 control-label">Email:</label><div class="col-md-8">' +
								'<input class="form-control" type="email" name="email" value="' + rowObj.Email + '"></div></div>' +
								'<div class="form-group"><label class="col-md-4 control-label">Location:</label><div class="col-md-8">' +
								'<input class="form-control" type="text" name="desk" value="' + rowObj.DeskLocation + '"></div></div>' +
								'<div class="form-group"><label class="col-md-4 control-label">Education:</label><div class="col-md-8">' +
								'<input class="form-control" type="text" name="education" value="' + rowObj.Education + '"></div></div>' +
								'<div class="form-group"><label class="col-md-4 control-label">Designation:</label><div class="col-md-8">' +
								'<input class="form-control" type="text" name="designation" value="' + rowObj.Designation + '"></div></div>' +
								'<div class="form-group"><label class="col-md-4 control-label">Experience:</label><div class="col-md-8">' +
								'<input class="form-control" type="text" name="experience" value="' + rowObj.Experience + '"></div></div>' +
								'<div class="form-group"><label class="col-md-4 control-label" for="inputType">Date of Birth:</label><div class="col-md-8"><div class="input-group">' +
								'<input type="text" id="birth-date" name="birth-date" class="form-control" value="' + rowObj.DateOfBirth + '"  required><label class="input-group-addon btn-datetime" for="birth-date"><span class="glyphicon glyphicon-calendar"></span></label></div></div></div>' +
								'<div class="form-group"><label class="col-md-4 control-label">Gender:</label><div class="col-md-8">' +
								'<input class="form-control" type="text" name="gender" value="' + rowObj.Gender + '"></div></div>' +
								'<div class="form-group"><label class="col-md-4 control-label">Description:</label><div class="col-md-8">' +
								'<textarea class="form-control" type="text" name="description" rows="4">' + rowObj.Description + '</textarea></div></div>' +
								'<div class="form-group row"><div class="col-sm-4 col-md-offset-2">' +
								'<input type="submit" class="btn btn-default btn-update-profile" value="Update"/></div>' +
								'<div class="col-sm-4">' +
								'<a href="tprofile.html" class="btn btn-default">Cancel</a>' +
								'</div></div>' +
								'</form>';
								*/
							alert = isAlert(rowObj.EmailAlert);
							$("#f-name").attr('value', rowObj.FirstName);
							$("#m-name").attr('value', rowObj.MiddleName);
							$("#l-name").attr('value', rowObj.LastName);
							$("#mobile").attr('value', rowObj.MobileNumber);
							$("#email").attr('value', rowObj.Email);
							$("#desk").attr('value', rowObj.DeskLocation);
							$("#education").attr('value', rowObj.Education);
							$("#designation").attr('value', rowObj.Designation);
							$("#experience").attr('value', rowObj.Experience);
							$("#birth-date").attr('value', rowObj.DateOfBirth);
							$("#gender").attr('value', rowObj.Gender);
							$('#description').val(rowObj.Description);
							$('#email-alert').prop('checked', alert);
						}
					}
				}
				//$("#display-edit-profile").css('display', 'inline', 'important');
				$("#display-edit-profile").html(tinfo);
				
				$('#birth-date').datepicker({
					autoclose: true,
					format: "yyyy-mm-dd"
				});
			}
		});
    });
});

//Function handle update profile button
$(document).ready(function () {
    $(document).on('submit', '#form-edit-profile', function (e) {
		e.preventDefault();
		var form_data = $(this).serialize();
		$.ajax({
			type: "POST",
			url: "php/update_tprofile.php",
			data: form_data,
			dataType: 'json',
			success: function (response) {
				//console.log(response);
				if (!response.success) {
					if (response.errors.db) {
						alert("Refresh the page and try again.  " + response.errors.db);
					}
					if (response.errors.name) {
						//$("#update-msg").html(response.errors.name);
						alert(response.errors.name);
					}
					if (response.errors.mobile) {
						//$("#update-msg").html(response.errors.mobile);
						alert(response.errors.mobile);
					}
					if (response.errors.email) {
						//$("#update-msg").html(response.errors.email);
						alert(response.errors.email);
					}
					if (response.errors.gender) {
						//$("#update-msg").html(response.errors.gender);
						alert(response.errors.gender);
					}
					if (response.errors.date) {
						//$("#update-msg").html(response.errors.date);
						alert(response.errors.date);
					}
					if (response.errors.desk) {
						//$("#update-msg").html(response.errors.desk);
						alert(response.errors.desk);
					}
				} else {
					$(".btn-view-profile").click();
					alert(response.message);
				}
				/*
				response = $.trim(response);
				if (response === 'Ok') {
					//$("#update-msg").css('display', 'inline', 'important');
					//$("#update-msg").html("Profile updated successfully.");
					$("#btn-view-profile").click();
					alert("Your Profile updated successfully.");
				} else {
					alert("Could not update profile. Please try again.");
					//$("#update-msg").css('display', 'inline', 'important');
					//$("#update-msg").html("Could not update profile. Please try again.");
				}
				*/
			}
		});
	});
});
				
//Function to handle Change Password button
$(document).ready(function () {
    $(".btn-change-password").click(function () {
        $("#edit").hide();
        $("#view").hide();
		$("#change").show();
		$(".btn-edit-profile").hide();
		$(".btn-change-password").hide();
		$(".btn-view-profile").show();
    });
});

//Function to handle cancel button on change password page
$(document).ready(function () {
    $("#btn-change-password-cancel").click(function (e) {
		e.preventDefault();
        $("#edit").hide();
        $("#view").show();
		$("#change").hide();
		$(".btn-view-profile").hide();
		$(".btn-edit-profile").show();
		$(".btn-change-password").show();
    });
});

//Function to handle submit change password
$(document).ready(function () {
    $(document).on('submit', '#form-change-passwd', function (e) {
		e.preventDefault();
		//var form_data = new FormData(this);
		var form_data = $(this).serialize();
		console.log(form_data);
		$.ajax({
			type: "POST",
			url: "php/change_passwd.php",
			data: form_data,
			//contentType: false,
			//processData: false,
			dataType: 'json',
			success: function (response) {
				console.log(response);
				if (!response.success) {
					if (response.errors.db) {
						alert("Refresh the page and try again.  " + response.errors.db);
					}
					if (response.errors.oldPwd) {
						//$("#update-msg").html(response.errors.name);
						alert(response.errors.oldPwd);
					}
					if (response.errors.newPwd) {
						//$("#update-msg").html(response.errors.mobile);
						alert(response.errors.newPwd);
					}
					if (response.errors.newPwdAgain) {
						//$("#update-msg").html(response.errors.email);
						alert(response.errors.newPwdAgain);
					}
					if (response.errors.noOldPwd) {
						//$("#update-msg").html(response.errors.gender);
						alert(response.errors.noOldPwd);
					}
					if (response.errors.noNewPwd) {
						//$("#update-msg").html(response.errors.date);
						alert(response.errors.noNewPwd);
					}
				} else {
					$(".btn-view-profile").click();
					alert(response.message);
				}
			}
		});
    });
});