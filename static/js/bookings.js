function base_url() {
	var pathparts = location.pathname.split('/');
	var url;

	if (location.host == 'localhost') {
		url = location.origin+'/'+pathparts[1].trim('/')+'/'; // http://localhost/myproject/
	}else{
		url = location.origin + "/"; // http://stackoverflow.com/
	}
	return url;
}

$('#tbl_pending_bookings').DataTable();
$('#tbl_paid_bookings').DataTable();

$('.btnApproveBooking').click(function(){
	var bookingId = $(this).data("id");
	$.confirm({
		title: 'Confirmation!',
		content: 'Are you sure you want to approve this pending booking?',
		useBootstrap: false, 
		theme: 'supervan',
		buttons: {
			NO: function () {
				//do nothing
			},
			YES: function () {
				$.ajax({
					url: base_url() + 'bookings/approve_booking?booking_id=' + bookingId,
					type: "POST",
					processData: false,
					contentType: false,
					cache: false,
					async: false,
					success: function(data) {
						var obj = JSON.parse(data);

						if(obj.flag === 0){
							$.alert({
								title: "Oops! We're sorry!",
								content: obj.msg,
								useBootstrap: false,
								theme: 'supervan',
								buttons: {
									'Ok, Got It!': function () {
										//do nothing
									}
								}
							});
						}else{
							$.alert({
								title: 'Success!',
								content: obj.msg,
								useBootstrap: false,
								theme: 'supervan',
								buttons: {
									'Ok, Got It!': function () {
										location.replace(base_url() + 'bookings');
									}
								}
							});
						}
					},
					error: function(xhr, status, error){
						var errorMessage = xhr.status + ': ' + xhr.statusText;
						$.alert({
							title: "Oops! We're sorry!",
							content: errorMessage,
							useBootstrap: false,
							theme: 'supervan',
							buttons: {
								'Ok, Got It!': function () {
									//do nothing
								}
							}
						});
					 }
				});
				
			}
		}
	});
});

$('.btnDeclineBooking').click(function(){
	var bookingId = $(this).data("id");
	$.confirm({
		title: 'Confirmation!',
		content: 'Are you sure you want to decline this pending booking?',
		useBootstrap: false, 
		theme: 'supervan',
		buttons: {
			NO: function () {
				//do nothing
			},
			YES: function () {
				$.ajax({
					url: base_url() + 'bookings/decline_booking?booking_id=' + bookingId,
					type: "POST",
					processData: false,
					contentType: false,
					cache: false,
					async: false,
					success: function(data) {
						var obj = JSON.parse(data);

						if(obj.flag === 0){
							$.alert({
								title: "Oops! We're sorry!",
								content: obj.msg,
								useBootstrap: false,
								theme: 'supervan',
								buttons: {
									'Ok, Got It!': function () {
										//do nothing
									}
								}
							});
						}else{
							$.alert({
								title: 'Success!',
								content: obj.msg,
								useBootstrap: false,
								theme: 'supervan',
								buttons: {
									'Ok, Got It!': function () {
										location.replace(base_url() + 'bookings');
									}
								}
							});
						}
					},
					error: function(xhr, status, error){
						var errorMessage = xhr.status + ': ' + xhr.statusText;
						$.alert({
							title: "Oops! We're sorry!",
							content: errorMessage,
							useBootstrap: false,
							theme: 'supervan',
							buttons: {
								'Ok, Got It!': function () {
									//do nothing
								}
							}
						});
					 }
				});
				
			}
		}
	});
});
