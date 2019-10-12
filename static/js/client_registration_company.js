(function ($) {
    var region_dropdown = $("select[name='region']");
	var province_dropdown = $("select[name='province']");
	var city_muni_dropdown = $("select[name='city_muni']");
	var barangay_dropdown = $("select[name='barangay']");
	
	function base_url() {
		var pathparts = location.pathname.split('/');
		if (location.host == 'localhost') {
			var url = location.origin+'/'+pathparts[1].trim('/')+'/'; // http://localhost/myproject/
		}else{
			var url = location.origin; // http://stackoverflow.com
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

	$('#frmRegisterIndividualClient').parsley().on('field:validated', function() {
		var ok = $('.parsley-error').length === 0;
		console.log(ok);
	});

	$("#frmRegisterIndividualClient").submit(function(e) {
    //prevent Default functionality
    e.preventDefault();

    //get the action-url of the form
		var actionurl = e.currentTarget.action;

		$.confirm({
			title: 'Confirmation!',
			content: 'Are you sure you want to register as an individual client?',
			useBootstrap: false, 
			theme: 'supervan',
			buttons: {
				NO: function () {
					//do nothing
				},
				YES: function () {
					$.ajax({
						url: actionurl,
						type: 'POST',
						data: $("#frmRegisterIndividualClient").serialize(),
						success: function(data) {
							$.alert({
								title: 'Client successfully registered!',
								content: 'Please check your email for verification.',
								useBootstrap: false,
								theme: 'supervan',
								buttons: {
									'Ok, Got It!': function () {
										location.replace(base_url());
									}
								}
							});
						},
						error: function(err){
							console.log(err);
						}
					});
					
				}
			}
		});
    });


})(jQuery);
