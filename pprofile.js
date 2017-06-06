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

function isEmailNull(email) {
	if (email === null || email === '') {
		return "--";
	} else {
		return email;
	}
}

function isEditEmailNull(email) {
	if (email === null) {
		return "";
	} else {
		return email;
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
			if (response !== 'parent') {
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
			data: "func=get_parent_profile",
			dataType: 'json',
			success: function (response) {
				var row, rowObj, tinfo = '', alert = '', email1, email2;
				if (response.length === 0) {
					tinfo = tinfo +
						'<div class="container-fluid post-empty">' +
						'<p>' + 'Profile data is not available.' + '</p></div>';

				} else {
					for (row in response) {
						if (response.hasOwnProperty(row)) {
							rowObj = response[row];
							email1 = isEmailNull(rowObj.Email1);
							email2 = isEmailNull(rowObj.Email2);
							alert = getAlert(rowObj.EmailAlert);
							
							tinfo = tinfo +
								'<div class="container-fluid post-nobox">' +
								'<div class="row"><div class="col-md-4 col-md-offset-1"><p><strong>Student Name:</strong></p></div>' +
								'<div class="col-md-7"><p>' + rowObj.SFName + " " + rowObj.SLName + '</p></div></div>' +
								'<div class="row"><div class="col-md-4 col-md-offset-1"><p><strong>Roll Number:</strong></p></div>' +
								'<div class="col-md-7"><p>' + rowObj.RollNumber + '</p></div></div>' +
								'<div class="row"><div class="col-md-4 col-md-offset-1"><p><strong>First Name:</strong></p></div>' +
								'<div class="col-md-7"><p>' + rowObj.Firstname + '</p></div></div>' +
								'<div class="row"><div class="col-md-4 col-md-offset-1"><p><strong>Middle Name:</strong></p></div>' +
								'<div class="col-md-7"><p>' + rowObj.MiddleName + '</p></div></div>' +
								'<div class="row"><div class="col-md-4 col-md-offset-1"><p><strong>Last Name:</strong></p></div>' +
								'<div class="col-md-7"><p>' + rowObj.LastName + '</p></div></div>' +
								'<div class="row"><div class="col-md-4 col-md-offset-1"><p><strong>Mobile#:</strong></p></div>' +
								'<div class="col-md-7"><p>' + rowObj.MobileNumber + '</p></div></div>' +
								'<div class="row"><div class="col-md-4 col-md-offset-1"><p><strong>Email1:</strong></p></div>' +
								'<div class="col-md-7"><p>' + email1 + '</p></div></div>' +
								'<div class="row"><div class="col-md-4 col-md-offset-1"><p><strong>Email2:</strong></p></div>' +
								'<div class="col-md-7"><p>' + email2 + '</p></div></div>' +
								'<div class="row"><div class="col-md-4 col-md-offset-1"><p><strong>Address:</strong></p></div>' +
								'<div class="col-md-7"><p>' + rowObj.Address + '</p></div></div>' +
								'<div class="row"><div class="col-md-8 col-md-offset-1"><p><strong>Receive instant email updates:</strong></p></div>' +
								'<div class="col-md-2"><p>' + alert + '</p></div></div>' +
								'</div>';
						}
					}
				}
				$("#display-profile").css('display', 'inline', 'important');
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

//Handle View Parent Profile
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
			data: "func=get_parent_profile",
			dataType: 'json',
			success: function (response) {
				var row, rowObj, tinfo = '', alert = '', email1, email2;
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
								'<div class="form-group"><label class="col-md-3 control-label">First name:</label><div class="col-md-8">' +
								'<input class="form-control" type="text" name="f-name" value="' + rowObj.Firstname + '"></div></div>' +
								'<div class="form-group"><label class="col-md-3 control-label">Middle name:</label><div class="col-md-8">' +
								'<input class="form-control" type="text" name="m-name" value="' + rowObj.MiddleName + '"></div></div>' +
								'<div class="form-group"><label class="col-md-3 control-label">Last name:</label><div class="col-md-8">' +
								'<input class="form-control" type="text" name="l-name" value="' + rowObj.LastName + '"></div></div>' +
								'<div class="form-group"><label class="col-md-3 control-label">Mobile #:</label><div class="col-md-8">' +
								'<input class="form-control" type="text" name="mobile" value="' + rowObj.MobileNumber + '"></div></div>' +
								'<div class="form-group"><label class="col-md-3 control-label">Email:</label><div class="col-md-8">' +
								'<input class="form-control" type="text" name="email" value="' + rowObj.Email + '"></div></div>' +
								'<div class="form-group"><label class="col-md-3 control-label">Address:</label><div class="col-md-8">' +
								'<input class="form-control" type="text" name="address" value="' + rowObj.Address + '"></div></div>' +
								'<div class="form-group row"><div class="col-sm-4 col-md-offset-2">' +
								'<input type="submit" class="btn btn-default btn-update-profile" value="Update" style="margin-top:1.5em"/></div>' +
								'<div class="col-sm-4">' +
								'<a href="pprofile.html" class="btn btn-default" style="margin-top:1.5em">Cancel</a>' +
								'</div></div>' +
								'</form>';
*/
							email1 = isEditEmailNull(rowObj.Email1);
							email2 = isEditEmailNull(rowObj.Email2);
							alert = isAlert(rowObj.EmailAlert);
							$("#f-name").attr('value', rowObj.Firstname);
							$("#m-name").attr('value', rowObj.MiddleName);
							$("#l-name").attr('value', rowObj.LastName);
							$("#mobile").attr('value', rowObj.MobileNumber);
							$("#email1").attr('value', email1);
							$("#email2").attr('value', email2);
							$('#email-alert').prop('checked', alert);
							$('#address').val(rowObj.Address);
						}
					}
				}
				//$("#display-edit-profile").css('display', 'inline', 'important');
				//$("#display-edit-profile").html(tinfo);
			}
		});
    });
});

//Function handle update profile button
$(document).ready(function () {
    $('#form-edit-profile').on('submit', function (e) {
		e.preventDefault();
		var form_data = $(this).serialize();
		$.ajax({
			type: "POST",
			url: "php/update_pprofile.php",
			data: form_data,
			dataType: 'json',
			success: function (response) {
				if (!response.success) {
					if (response.errors.db) {
						$("#update-msg").html("Refresh the page and try again.  " + response.errors.db);
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
					if (response.errors.address) {
						//$("#update-msg").html(response.errors.address);
						alert(response.errors.address);
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
					$(".btn-view-profile").click();
					alert("Your Profile updated successfully.");
				} else {
					$("#update-msg").css('display', 'inline', 'important');
					$("#update-msg").html("Could not update profile. Please try again.");
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
