function base_url() {
	var pathparts = location.pathname.split('/');
	if (location.host == 'localhost') {
		var url = location.origin+'/'+pathparts[1].trim('/')+'/'; // http://localhost/myproject/
	}else{
		var url = location.origin; // http://stackoverflow.com
	}
	return url;
}

var region_dropdown = $("select[name='region']");
var province_dropdown = $("select[name='province']");
var city_muni_dropdown = $("select[name='city_muni']");
var barangay_dropdown = $("select[name='barangay']");

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

$("#inputBirthdate").flatpickr({
	dateFormat: "Y-m-d",
	static: true,
	allowInput: true
});

$('select#cmbCategory').selectize({
	placeholder: 'Choose category'
});

$('select#talent_cmbCategory').selectize({
	placeholder: 'Choose category'
});

//initialize datatable
$('#tbl_talents').DataTable();
$('#tbl_clients').DataTable();
$('#tbl_applicants').DataTable();

$('#inputHeight').maskMoney();
$('#inputHourlyRate').maskMoney();
$('#zipCode').maskMoney({precision: 0, thousands: ''});

$('#inputFbFollowers').maskMoney({precision: 0});
$('#inputInstagramFollowers').maskMoney({precision: 0});

$('#talent_id_upload_gallery').val($(this).data('id'));

$('#btnAddTalentResources').click(function(){
	var talentId = $(this).data("id");
	$('input[name=talent_id').val(talentId);
});

$('.btnAddTalentOrModel').click(function(){
	//clear all fields
	$("input[name='talent_firstname']").val("");
	$("input[name='talent_middlename']").val("");
	$("input[name='talent_lastname']").val("");
	$("input[name='talent_email']").val("");
	$("input[name='talent_contact_number']").val("");
	$("select[name='talent_gender']").val("");
	$("input[name='talent_height']").val("");
	$("input[name='talent_birth_date']").val("");
	$("input[name='talent_hourly_rate']").val("");
	$("input[name='talent_vital_stats']").val("");
	$("input[name='talent_fb_followers']").val("");
	$("input[name='talent_instagram_followers']").val("");
	$("textarea[name='talent_description']").val("");
	$("textarea[name='talent_prev_clients']").val("");

	var $select = $('#cmbRegion').selectize();
 	var control = $select[0].selectize;
	control.clear();

	var $select = $('#cmbProvince').selectize();
 	var control = $select[0].selectize;
	control.clear();

	var $select = $('#cmbCityMunicipality').selectize();
 	var control = $select[0].selectize;
	control.clear();
	
	var $select = $('#cmbBarangay').selectize();
 	var control = $select[0].selectize;
	control.clear();
});

$('.btnCheckRequirements').click(function(){
	var clientId = $(this).data("id");
	$('input[name=checkReq_clientId').val(clientId);
	$('.client_requirements').empty();

	var url = base_url() + 'api/client/get_client_requirements?client_id=' + clientId;
	$.getJSON(url, function(response) {
		if(response['requirements'].length === 0){
			$('.client_requirements').append('No requirements as of the moment.');
		}else{
			$.each(response['requirements'], function() {
				$('.client_requirements').append(
					'<div style="display: grid; grid-template-columns: auto auto; margin-bottom: 10px;">' +
						'<a target="_blank" href="' + this.file_name + '">' +
							  '<img src="' + this.file_name + '" width="400" height="250">' +
						'</a>' +
						'<div>' +
							'<br /><b>Type:</b> ' + this.valid_id_name + '<br />' +
							'<b>Date Uploaded:</b> ' + this.created_date + 
						'</div>' + 
					'</div>'
				);
			});
		}	
	});
});

$('.btnViewOrEditTalent').click(function(){
	var talentId = $(this).data("id");
	$('.talent_gallery').empty();

	var talentDetailsUrl = base_url() + 'api/talents/get_talent_details?talent_id=' + talentId;
	$.getJSON(talentDetailsUrl, function(response) {
		$("input[name='talent_firstname']").val(response.firstname);
		$("input[name='talent_middlename']").val(response.middlename);
		$("input[name='talent_lastname']").val(response.lastname);
		$("input[name='talent_email']").val(response.email);
		$("input[name='talent_contact_number']").val(response.contact_number);
		$("select[name='talent_gender']").val(response.gender);
		$("input[name='talent_height']").val(response.height);
		$("input[name='talent_birth_date']").val(response.birth_date);
		$("input[name='talent_hourly_rate']").val(response.hourly_rate);
		$("input[name='talent_vital_stats']").val(response.vital_stats);
		$("input[name='talent_fb_followers']").val(response.fb_followers);
		$("input[name='talent_instagram_followers']").val(response.instagram_followers);
		$("textarea[name='talent_description']").val(response.talent_description);
		$("textarea[name='talent_prev_clients']").val(response.talent_experiences);

		region_dropdown.val(response.region_code);
		
		var getAllProvinceUrl = base_url() + 'api/client/get_all_provinces_by_region_code?region_code=' + response.region_code;
		$.getJSON(getAllProvinceUrl, function(provinceResponse) {
			clearProvince();
			
			$.each(provinceResponse['provinces_list'], function() {
				$("select[name='province']").append($("<option />").val(this.provCode).text(this.provDesc));
				
				if(this.provCode === response.province_code){
					$("select[name='province']").val(response.province_code);
				}
			});
		});

		var getAlLCityMuniUrl = base_url() + 'api/client/get_city_muni_by_province_code?province_code=' + response.province_code;
		$.getJSON(getAlLCityMuniUrl, function(cityMuniResponse) {
			clearCityMuni();
			
			$.each(cityMuniResponse['city_muni_list'], function() {
				city_muni_dropdown.append($("<option />").val(this.citymunCode).text(this.citymunDesc));
				
				if(this.citymunCode == response.city_muni_code){
					city_muni_dropdown.val(response.city_muni_code);
				}
			});
		});

		var getAllBarangayUrl = base_url() + 'api/client/get_barangay_by_city_muni_code?city_muni_code=' + response.city_muni_code;
		$.getJSON(getAllBarangayUrl, function(barangayResponse) {
			clearBarangay();

			$.each(barangayResponse['barangay_list'], function() {
				barangay_dropdown.append($("<option />").val(this.id).text(this.brgyDesc));
				
				if(this.id === response.barangay_code){
					barangay_dropdown.val(response.barangay_code);
				}
			});
		});

		$("input[name='talent_bldg_village']").val(response.bldg_village);
		$("input[name='talent_zip_code']").val(response.zip_code);
		$("input[name='talent_genre']").val(response.genre);

		$("select#talent_cmbCategory")[0].selectize.setValue([response.category_ids]);
	});

	var talentGalleryUrl = base_url() + 'api/talents/get_talent_gallery?talent_id=' + talentId;
	$.getJSON(talentGalleryUrl, function(response) {
		if(response['talent_gallery'].length === 0){
			$('.talent_gallery').append('No images as of the moment.');
		}else{
			$.each(response['talent_gallery'], function() {
				$('.talent_gallery').append(
					'<a target="_blank" href="' + this.file_name + '" style="padding: 5px;">' +
						'<img src="' + this.file_name + '" width="150" height="150">' +
					'</a>'
				);
			});
		}	
	});
});

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
			alert("Image(s) uploaded successfully.");
			location.reload(true);
		},
		error: function(xhr, status, error){
        	var errorMessage = xhr.status + ': ' + xhr.statusText
         	alert('Error - ' + errorMessage);
			location.reload(true);
     	}
	});
});

$('#frmUploadTalentGallery').submit(function(e){
	e.preventDefault(); 
	$.ajax({
		url: this.action,
		type: "POST",
		data: new FormData(this),
		processData: false,
		contentType: false,
		cache: false,
		async: false,
		success: function(data){
			alert("Image(s) uploaded successfully.");
			location.reload(true);
		},
		error: function(xhr, status, error){
        	var errorMessage = xhr.status + ': ' + xhr.statusText;
         	//alert('Error - ' + errorMessage);
			location.reload(true);
     	}
	});
});

$('#frmUpdateClientStatus').parsley().on('field:validated', function() {
	var ok = $('.parsley-error').length === 0;
});

$("#frmUpdateClientStatus").submit(function(e) {
	e.preventDefault();
	var formAction = e.currentTarget.action;
	var formType = "POST";

	$.confirm({
		title: 'Confirmation!',
		content: 'Are you sure you want update status of this client?',
		useBootstrap: false, 
		theme: 'supervan',
		buttons: {
			NO: function () {
				//do nothing
			},
			YES: function () {
				$.ajax({
					url: formAction,
					type: formType,
					data: $("#frmUpdateClientStatus").serialize(),
					success: function(data) {
						var obj = JSON.parse(data);

						if(obj.flag === 0){
							$.alert({
								title: "Oops! We're sorry!",
								content: 'Failed to update client status. Please try again later.',
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
										location.replace(base_url());
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
}

$('#frmAddTalentOrModel').parsley().on('field:validated', function() {
	var ok = $('.parsley-error').length === 0;
	
	if(ok){
		addTalentOrModel();
	}
});

//setupFilePond();
setupAddress();
