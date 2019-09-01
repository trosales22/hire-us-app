$("#inputBirthdate").flatpickr({
	dateFormat: "Y-m-d",
	static: true,
	allowInput: true
});

$('select#cmbCategory').selectize({
	placeholder: 'Choose category'
});

$('#inputHeight').maskMoney();
$('#inputHourlyRate').maskMoney();
$('#zipCode').maskMoney({precision: 0, thousands: ''});

$('#inputFbFollowers').maskMoney({precision: 0});
$('#inputInstagramFollowers').maskMoney({precision: 0});

$('#frmUpdateTalentProfilePic').submit(function(e){
	e.preventDefault(); 
	$.ajax({
		url:this.action,
		type:"POST",
		data:new FormData(this),
		processData:false,
		contentType:false,
		cache:false,
		async:false,
		success: function(data){
			alert("Upload Image Successful.");
		}
	});
});

function base_url() {
	var pathparts = location.pathname.split('/');
	if (location.host == 'localhost') {
		var url = location.origin+'/'+pathparts[1].trim('/')+'/'; // http://localhost/myproject/
	}else{
		var url = location.origin; // http://stackoverflow.com
	}
	return url;
}

function setupFilePond(){
	// Get a reference to the file input element
	var talentProfilePicElement = document.querySelector('input[id="profile_picture"]');
	var talentGalleryElement = document.querySelector('input[id="talent_gallery"]');

	FilePond.registerPlugin(
		FilePondPluginImagePreview,
		FilePondPluginImageExifOrientation,
		FilePondPluginFileValidateSize
	);

	// Create the FilePond instance
	FilePond.create(
		talentProfilePicElement,
		{
			labelIdle: `Drag & Drop Profile Picture or <span class="filepond--label-action">Browse</span>`,
			imagePreviewHeight: 170,
			imagePreviewWidth: 150,
			imageCropAspectRatio: '1:1',
			imageResizeTargetWidth: 200,
			imageResizeTargetHeight: 200,
			styleLoadIndicatorPosition: 'center bottom',
			styleProgressIndicatorPosition: 'right bottom',
			styleButtonRemoveItemPosition: 'left bottom',
			styleButtonProcessItemPosition: 'right bottom',
		}
	);

	FilePond.create(
		talentGalleryElement,
		{
			labelIdle: `Drag & Drop Images or <span class="filepond--label-action">Browse</span>`,
			imagePreviewHeight: 100,
		}
	);
}

function setupAddress(){
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
}

function addTalentOrModel(){
	$("#frmAddTalentOrModel").submit(function(e) {
		//prevent Default functionality
		e.preventDefault();
	
		//get the action-url of the form
		var actionUrl = e.currentTarget.action;
	
		$.confirm({
			title: 'Confirmation!',
			content: 'Are you sure you want to add this talent/model?',
			useBootstrap: false, 
			theme: 'supervan',
			buttons: {
				NO: function () {
					//do nothing
				},
				YES: function () {
					$.ajax({
						url: actionUrl,
						type: 'POST',
						data: $("#frmAddTalentOrModel").serialize(),
						success: function(data) {
							$.alert({
								title: 'Yehey!',
								content: 'Talent was successfully added!',
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
}

$('#frmAddTalentOrModel').parsley().on('field:validated', function() {
	var ok = $('.parsley-error').length === 0;

	if(ok){
		addTalentOrModel();
	}
});

setupFilePond();
setupAddress();
