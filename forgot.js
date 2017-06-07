/*jslint node: true */
/*jslint browser: true*/
/*global $, jQuery, alert*/

"use strict";

function getEncrypt() {
	var query, vars, i = 0, pair;
	query = window.location.search.substring(1);
    vars = query.split("&");
	pair = vars[0].split("en=");
	return pair[1];
}

//Function to handle forgot password submit button
$(document).ready(function () {
    $(document).on('submit', '#forgot-pwd', function (e) {
		e.preventDefault();
		var form_data = $(this).serialize();
		//console.log(form_data);
		$.ajax({
			type: "POST",
			url: "php/forgot_password.php",
			data: form_data,
			dataType: 'json',
			success: function (response) {
				//console.log(response);
				if (!response.success) {
					if (response.errors.db) {
						alert("Refresh the page and try again.  " + response.errors.db);
					}
					if (response.errors.uname) {
						alert(response.errors.uname);
					}
				} else {
					alert("Link to reset Password has been sent to your email. Please use the link in email to reset your password. Thank you!");
					window.location = "index.html";
				}
			}
		});
    });
});

//Function to handle forgot username submit button
$(document).ready(function () {
    $(document).on('submit', '#forgot-uname', function (e) {
		e.preventDefault();
		var form_data = $(this).serialize();
		//console.log(form_data);
		$.ajax({
			type: "POST",
			url: "php/forgot_username.php",
			data: form_data,
			dataType: 'json',
			success: function (response) {
				//console.log(response);
				if (!response.success) {
					if (response.errors.db) {
						alert("Refresh the page and try again.");
					}
					if (response.errors.email) {
						alert(response.errors.email);
					}
					if (response.errors.noEmail) {
						alert(response.errors.noEmail);
						window.location = "forgot_username.html";
					}
				} else {
					alert("We have sent your username to your email. Thank you!");
					window.location = "index.html";
				}
			}
		});
    });
});

//Function to handle reset password submit button
$(document).ready(function () {
    $(document).on('submit', '#reset-pwd', function (e) {
		e.preventDefault();
		var encrypt, data, form_data;
		encrypt = getEncrypt();
		data = $(this).serialize();
		form_data = data + "&en=" + encrypt;
		$.ajax({
			type: "POST",
			url: "php/reset.php",
			data: form_data,
			dataType: 'json',
			success: function (response) {
				//console.log(response);
				if (!response.success) {
					if (response.errors.db) {
						alert("Refresh the page and try again.  " + response.errors.db);
					}
					if (response.errors.en) {
						//$("#update-msg").html(response.errors.name);
						alert(response.errors.en);
					}
					if (response.errors.newPwd) {
						//$("#update-msg").html(response.errors.mobile);
						alert(response.errors.newPwd);
					}
					if (response.errors.newPwdAgain) {
						//$("#update-msg").html(response.errors.email);
						alert(response.errors.newPwdAgain);
					}
					if (response.errors.noNewPwd) {
						//$("#update-msg").html(response.errors.date);
						alert(response.errors.noNewPwd);
					}
				} else {
					alert("Password Updated Successfully");
					window.location = "index.html";
				}
			}
		});
    });
});