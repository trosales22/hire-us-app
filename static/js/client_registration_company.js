(function ($) {
    var region_dropdown = $("select[name='region']");
	var province_dropdown = $("select[name='province']");
	var city_muni_dropdown = $("select[name='city_muni']");
	var barangay_dropdown = $("select[name='barangay']");
	
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

	function clearProvince(){
		province_dropdown.empty();
		province_dropdown.append('<option disabled="disabled" selected="selected">Choose Province</option>');
	}

	function clearBarangay(){
		barangay_dropdown.empty();
		barangay_dropdown.append('<option disabled="disabled" selected="selected">Choose Barangay</option>');
	}

	function clearCityMuni(){
		city_muni_dropdown.empty();
		city_muni_dropdown.append('<option disabled="disabled" selected="selected">Choose City/Municipality</option>');
	}

	region_dropdown.change(
		function() {
			clearProvince();
			clearCityMuni();
			clearBarangay();

			var region_val = region_dropdown.val();
			
			var url = base_url() + 'api/client/get_all_provinces_by_region_code?region_code=' + region_val;
			$.getJSON(url, function(response) {
				clearProvince();
				
				$.each(response['provinces_list'], function() {
					province_dropdown.append($("<option />").val(this.provCode).text(this.provDesc));
				});
			});
		}
	);
	
	province_dropdown.change(
		function() {
			clearCityMuni();
			clearBarangay();

			var province_val = province_dropdown.val();
			
			var url = base_url() + 'api/client/get_city_muni_by_province_code?province_code=' + province_val;
			$.getJSON(url, function(response) {
				clearCityMuni();

				$.each(response['city_muni_list'], function() {
					city_muni_dropdown.append($("<option />").val(this.citymunCode).text(this.citymunDesc));
				});
			});
		}
	);

	city_muni_dropdown.change(
		function() {
			var city_muni_code_val = city_muni_dropdown.val();
			
			var url = base_url() + 'api/client/get_barangay_by_city_muni_code?city_muni_code=' + city_muni_code_val;
			$.getJSON(url, function(response) {
				clearBarangay();
				$.each(response['barangay_list'], function() {
					barangay_dropdown.append($("<option />").val(this.id).text(this.brgyDesc));
				});
			});
		}
	);

	$('#frmRegisterCompanyClient').parsley().on('field:validated', function() {
		var ok = $('.parsley-error').length === 0;
		console.log(ok);
	});

	$("#frmRegisterCompanyClient").submit(function(e) {
		e.preventDefault();
		var formAction = e.currentTarget.action;
		var formData = new FormData(this);
		var formType = "POST";

		Swal.fire({
			title: 'Confirmation',
			text: "Are you sure you want to register as a company/corporate client?",
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
						console.log('data:' + data);
						$.alert({
							title: 'Company client was successfully registered!',
							content: 'Please wait for admin approval. Please check your email from time to time. Thank you.',
							useBootstrap: false,
							theme: 'supervan',
							buttons: {
								'Ok, Got It!': function () {
									location.replace(base_url());
								}
							}
						});
					},
					error: function(xhr, status, error){
						var errorMessage = xhr.status + ': ' + xhr.statusText
						alert('Error - ' + errorMessage);
						Swal.fire(
							'Error!',
							errorMessage,
							'danger'
						);
					 }
				});	
			}
		});
    });
})(jQuery);
