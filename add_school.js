/*jslint node: true */
/*jslint browser: true*/
/*global $, jQuery, alert*/

"use strict";


//Handle Add School
$(document).ready(function () {
	$('#add-school-form').submit(function (e) {
		e.preventDefault();
		var form_data = $(this).serialize();
		$.ajax({
			type: "POST",
			url: "php/add_school.php",
			data: form_data,
			dataType: 'json',
			success: function (response) {
				console.log(response);
				if (!response.success) {
					if (response.errors.db) {
						$("#add-school-msg").html(response.errors.db);
					}
				} else {
					$("form").trigger("reset");
					//$("#add-school-msg").html(response.message);
					alert(response.message);
					$(location).attr('href', 'index.html');
				}
			}
		});
	});
});