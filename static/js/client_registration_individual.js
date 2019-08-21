(function ($) {
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

	function clearBarangay(){
		barangay_dropdown.empty();
		barangay_dropdown.append('<option disabled="disabled" selected="selected">Choose Barangay</option>');
	}

	function clearCityMuni(){
		city_muni_dropdown.empty();
		city_muni_dropdown.append('<option disabled="disabled" selected="selected">Choose City/Municipality</option>');
	}

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

})(jQuery);
