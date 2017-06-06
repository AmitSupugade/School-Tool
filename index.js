/*jslint node: true */
/*jslint browser: true*/
/*global $, jQuery, alert*/

"use strict";

$(document).ready(function () {
	$(".navbar-nav li a.collapsable").click(function (event) {
		$(".navbar-collapse").collapse('hide');
	});
});

//Increases height of navbar-collapse on small screens so login form does not hide
$(document).ready(function () {
	$(".navbar-collapse").css({ maxHeight: $(window).height() - $(".navbar-header").height() + "2px" });
});


//Handle Session
$(document).ready(function () {
	$.ajax({
		type: "GET",
		url: "php/session.php",
		success: function (response) {
			response = $.trim(response);
			if (response === 'parent') {
				window.location = "test.html";
			} else if (response === 'teacher') {
				window.location = "ttest.html";
			} else if (response === 'admin') {
				window.location = "admin.html";
			}
		}
	});
});

//Submit login-form
$(document).ready(function () {
	$('#login-form').submit(function (e) {
		e.preventDefault();
		var form_data = $(this).serialize();
		console.log(form_data);
		$.ajax({
			type: "POST",
			url: "php/login_process.php",
			data: form_data,
			success: function (response) {
				response = $.trim(response);
				console.log(response);
				if (response === 'parent') {
					window.location = "test.html";
				} else if (response === 'teacher') {
					window.location = "ttest.html";
				} else if (response === 'admin') {
					window.location = "admin.html";
				} else if (response === 'index') {
					window.location = "index.html";
				} else if (response === 'username') {
					$("#add_error").css('display', 'inline', 'important');
					$("#add_error").html("Incorrect Username");
				} else if (response === 'password') {
					$("#add_error").css('display', 'inline', 'important');
					$("#add_error").html("Incorrect Password");
				} else {
					$("#add_error").css('display', 'inline', 'important');
					$("#add_error").html("System Error. Please Contact admin.");
				}
			}
		});
		return false;
	});
});
