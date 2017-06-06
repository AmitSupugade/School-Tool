/*jslint node: true */
/*jslint browser: true*/
/*global $, jQuery, alert*/

"use strict";

//Function to take care of sidebar toggle
$(document).ready(function () {
    $('[data-toggle="offcanvas-left"]').click(function () {
        $('.row-offcanvas-left').toggleClass('active');
    });
});


//Highlight Selected sidebar-nav option
$(document).ready(function () {
	$('ul.sidebar-nav li a').click(function () {
		$(this).parent().parent().find('a.sidebar-active').removeClass('sidebar-active');
		$(this).addClass('sidebar-active');
	});
});


//Function to display holiday on click
$(document).ready(function () {
    $("#nav-holiday").click(function () {
        $(".toggle-nav").hide();
        $("#holiday").show();
    });
});

//Function to display gallary on click
$(document).ready(function () {
    $("#nav-gallary").click(function () {
        $(".toggle-nav").hide();
        $("#gallary").show();
    });
});


$(document).on('click', '[data-toggle="lightbox"]', function (event) {
    event.preventDefault();
    $(this).ekkoLightbox({
		alwaysShowClose: true,
		scale_height: true
	});
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
			console.log(response);
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
			$("#display-school").css('display', 'inline', 'important');
			$("#display-school").html(school);
		}
	});
});

//See holidays
$(document).ready(function () {
	$('#nav-holiday').on('click', function (e) {
		e.preventDefault();
		$.ajax({
			type: "GET",
			url: "php/get_handle.php",
			data: "func=get_holidays",
			dataType: 'json',
			success: function (response) {
				console.log(response);
				var row, rowObj, tdata = '', thead = '', tbody = '', table_result = '', count = 1;
				if (response.length === 0) {
					table_result = "  No holiday added yet";
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

//Get Albums
$(document).ready(function () {
	$('#nav-gallary').on('click', function (e) {
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
								'<div class="thumbnail"><a class="a-thumbnail" onclick=get_photo(' + rowObj.Id + ')><img src="photos/' + rowObj.Thumbnail + '" alt="' + rowObj.Name + '" class="img-thumbnail" style="width: 200px; height: 200px;">' +
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

