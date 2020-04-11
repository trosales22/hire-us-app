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

$('#tbl_bookings').DataTable();

$('.btnViewBooking').click(function(){
	var bookingGeneratedId = $(this).data("id");

	var bookingDetailsUrl = base_url() + 'api/bookings/get_booking_by_booking_generated_id?booking_generated_id=' + bookingGeneratedId;
	$.getJSON(bookingDetailsUrl, function(response) {
		console.log(response);
		
		$('.booking_generated_id').text(response.booking_generated_id);
		$("input[name='booking_generated_id']").val(response.booking_generated_id);
		$(".booking_event_title").text(response.booking_event_title);
		$(".booking_event_venue").text(response.booking_venue_location);
		$(".booking_talent_fee").text(response.booking_talent_fee);
		$(".booking_payment_option").text(response.booking_payment_option);
		$(".booking_working_dates").text(response.booking_date);
		$(".booking_working_hours").text(response.booking_time);
		$(".booking_other_details").text(response.booking_other_details);
		$(".booking_offer_status").text(response.booking_offer_status);
		$(".booking_created_date").text(response.booking_created_date);
		$(".booking_decline_reason").text(response.booking_decline_reason);
		$(".booking_approved_or_declined_date").text(response.booking_approved_or_declined_date);
		$(".booking_date_paid").text(response.booking_date_paid);
		$(".booking_pay_on_or_before").text(response.booking_pay_on_or_before);
		$(".booking_payment_status").text(response.booking_payment_status);

		if(response.booking_payment_status == "ACTIVE" && response.booking_payment_option == "N/A" && response.booking_approved_or_declined_date != "N/A"){
			$('button#btnApprovePayment').css('display', 'block');
		}else{
			$('button#btnApprovePayment').css('display', 'none');
		}
	});
});

$("#frmVerifyPayment").submit(function(e) {
	e.preventDefault();
	var formAction = e.currentTarget.action;
	var formData = new FormData(this);
	var formType = "POST";

	Swal.fire({
		title: 'Confirmation',
		text: "Are you sure you want to approve this payment?",
		icon: 'warning',
		showCancelButton: true,
		reverseButtons: true,
		confirmButtonColor: '#3085d6',
		cancelButtonColor: '#d33',
		confirmButtonText: 'Yes!'
	}).then((result) => {
		if (result.value) {
			$.ajax({
				url: formAction,
				type: formType,
				data: formData,
				processData: false,
				contentType: false,
				cache: false,
				async: false,
				success: function(data) {
					var obj = $.parseJSON(data);
						
					if(obj.flag === 0){
						Swal.fire(
							'Error!',
							obj.msg,
							'error'
						);
					}else{
						Swal.fire({
							title: 'Success!',
							text: obj.msg,
							icon: 'success',
							allowOutsideClick: false,
							allowEscapeKey: false,
							showCancelButton: false,
							confirmButtonText: 'Ok, Got It!'
						}).then((result) => {
							if (result.value) {
								location.replace(base_url() + "/bookings");
							}
						});
					}
				},
				error: function(xhr, status, error){
					var errorMessage = xhr.status + ': ' + xhr.statusText;
					Swal.fire(
						'Error!',
						errorMessage,
						'error'
					);
				 }
			});	
		}
	});
});