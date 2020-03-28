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

function isEmpty(value){
	return (
	  // null or undefined
	  (value == null) ||
  
	  // has length and it's zero
	  (value.hasOwnProperty('length') && value.length === 0) ||
  
	  // is an Object and has no keys
	  (value.constructor === Object && Object.keys(value).length === 0)
	);
}

var region_dropdown = $("select#region");
var province_dropdown = $("select#province");
var city_muni_dropdown = $("select#city_muni");
var barangay_dropdown = $("select#barangay");

var talent_region_dropdown = $("select#talent_region");
var talent_province_dropdown = $("select#talent_province");
var talent_city_muni_dropdown = $("select#talent_city_muni");
var talent_barangay_dropdown = $("select#talent_barangay");

function clearRegion(){
	region_dropdown.empty();
	region_dropdown.append('<option disabled="disabled" selected="selected">Choose Region</option>');

	talent_region_dropdown.empty();
	talent_region_dropdown.append('<option disabled="disabled" selected="selected">Choose Region</option>');
}

function clearProvince(){
	province_dropdown.empty();
	province_dropdown.append('<option disabled="disabled" selected="selected">Choose Province</option>');

	talent_province_dropdown.empty();
	talent_province_dropdown.append('<option disabled="disabled" selected="selected">Choose Province</option>');
}

function clearCityMuni(){
	city_muni_dropdown.empty();
	city_muni_dropdown.append('<option disabled="disabled" selected="selected">Choose City/Municipality</option>');

	talent_city_muni_dropdown.empty();
	talent_city_muni_dropdown.append('<option disabled="disabled" selected="selected">Choose City/Municipality</option>');
}

function clearBarangay(){
	barangay_dropdown.empty();
	barangay_dropdown.append('<option disabled="disabled" selected="selected">Choose Barangay</option>');

	talent_barangay_dropdown.empty();
	talent_barangay_dropdown.append('<option disabled="disabled" selected="selected">Choose Barangay</option>');
}

$("input[name='birth_date']").flatpickr({
	dateFormat: "Y-m-d",
	static: true,
	allowInput: true
});

$("select[name='category[]']").selectize({
	placeholder: 'Choose category'
});

$("select#talent_category").selectize({
	placeholder: 'Choose category'
});

//initialize datatable
$('#tbl_talents').DataTable();
$('#tbl_clients').DataTable();
$('#tbl_applicants').DataTable();

$('#inputHeight').maskMoney();
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
	$("input[name='talent_vital_stats']").val("");
	$("input[name='talent_fb_followers']").val("");
	$("input[name='talent_instagram_followers']").val("");
	$("textarea[name='talent_description']").val("");
	$("textarea[name='talent_prev_clients']").val("");
	$("input[name='talent_screen_name']").val("");
	$("input[name='talent_id']").val("");

	setupAddress(region_dropdown, province_dropdown, city_muni_dropdown, barangay_dropdown);
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

$('.btnViewOrModifyTalent').click(function(){
	var talentId = $(this).data("id");
	$('.talent_gallery').empty();

	var talentDetailsUrl = base_url() + 'api/talents/get_talent_details?talent_id=' + talentId;
	$.getJSON(talentDetailsUrl, function(response) {
		console.log(response);
		$("input[name='talent_id']").val(talentId);
		$("input[name='talent_firstname']").val(response.firstname);
		$("input[name='talent_middlename']").val(response.middlename);
		$("input[name='talent_lastname']").val(response.lastname);
		$("input[name='talent_email']").val(response.email);
		$("input[name='talent_contact_number']").val(response.contact_number);
		$("select[name='talent_gender']").val(response.gender);
		$("input[name='talent_height']").val(response.height);
		$("input[name='talent_birth_date']").val(response.birth_date);
		$("input[name='talent_vital_stats']").val(response.vital_stats);
		$("input[name='talent_fb_followers']").val(response.fb_followers);
		$("input[name='talent_instagram_followers']").val(response.instagram_followers);
		$("textarea[name='talent_description']").val(response.talent_description);
		$("textarea[name='talent_prev_clients']").val(response.talent_experiences);
		$("input[name='talent_screen_name']").val(response.screen_name);
		
		talent_region_dropdown.val(response.region_code);
		
		var getAllProvinceUrl = base_url() + 'api/client/get_all_provinces_by_region_code?region_code=' + response.region_code;
		$.getJSON(getAllProvinceUrl, function(provinceResponse) {
			clearProvince();

			$.each(provinceResponse['provinces_list'], function() {
				talent_province_dropdown.append($("<option />").val(this.provCode).text(this.provDesc));
				
				if(this.provCode === response.province_code){
					talent_province_dropdown.val(response.province_code);
				}
			});
		});

		var getAllCityMuniUrl = base_url() + 'api/client/get_city_muni_by_province_code?province_code=' + response.province_code;
		$.getJSON(getAllCityMuniUrl, function(cityMuniResponse) {
			clearCityMuni();
			
			$.each(cityMuniResponse['city_muni_list'], function() {
				talent_city_muni_dropdown.append($("<option />").val(this.citymunCode).text(this.citymunDesc));
				
				if(this.citymunCode == response.city_muni_code){
					talent_city_muni_dropdown.val(response.city_muni_code);
				}
			});
		});

		var getAllBarangayUrl = base_url() + 'api/client/get_barangay_by_city_muni_code?city_muni_code=' + response.city_muni_code;
		$.getJSON(getAllBarangayUrl, function(barangayResponse) {
			clearBarangay();

			$.each(barangayResponse['barangay_list'], function() {
				talent_barangay_dropdown.append($("<option />").val(this.id).text(this.brgyDesc));
				
				if(this.id === response.barangay_code){
					talent_barangay_dropdown.val(response.barangay_code);
				}
			});
		});

		$("input[name='talent_bldg_village']").val(response.bldg_village);
		$("input[name='talent_zip_code']").val(response.zip_code);
		$("input[name='talent_genre']").val(response.genre);

		var selected_category_ids_arr = [];
		var category_ids = response.category_ids.split("*");
		
		$("select#talent_category")[0].selectize.setValue(category_ids);
	});

	var talentGalleryUrl = base_url() + 'api/talents/get_talent_gallery?talent_id=' + talentId;
	$.getJSON(talentGalleryUrl, function(response) {
		if(response['talent_gallery'].length === 0){
			$('.talent_gallery').append(
				'<div class="alert alert-danger">' +
					'<span class="icon text-red-50" style="margin-right: auto;">' +
			  		'<i class="fas fa-exclamation-triangle"></i>' +
					'</span>' + 
					'<b>NO IMAGE GALLERY AVAILABLE!</b>' +
				  '</div>'
			);
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

function setupAddress(region_dropdown, province_dropdown, city_muni_dropdown, barangay_dropdown){
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
					region_dropdown.append($("<option />").val(this.provCode).text(this.provDesc));
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
					muni_dropdown.append($("<option />").val(this.citymunCode).text(this.citymunDesc));
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

function addTalent(){
	$("#frmAddTalentOrModel").submit(function(e) {
		e.preventDefault();
		var formAction = e.currentTarget.action;
		var formData = new FormData(this);
		var formType = "POST";
		
		Swal.fire({
			title: 'Confirmation',
			text: "Are you sure you want to add this talent/model?",
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
						var obj = data;
							
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
									location.replace(base_url());
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
}

$('#frmAddTalentOrModel').parsley().on('field:validated', function() {
	var ok = $('.parsley-error').length === 0;
	
	if(ok){
		addTalent();
	}
});

function modifyTalent(){
	$("#frmModifyTalent").submit(function(e) {
		e.preventDefault();
		var formAction = e.currentTarget.action;
		var formData = new FormData(this);
		var formType = "POST";

		Swal.fire({
			title: 'Confirmation',
			text: "Are you sure you want to modify this talent/model?",
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
						var obj = data;
							
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
									location.replace(base_url());
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
}

$('#frmModifyTalent').parsley().on('field:validated', function() {
	var ok = $('.parsley-error').length === 0;
});

modifyTalent();

function deleteApplicant(){
	$('.btnDeleteApplicant').click(function(){
		var userId = $(this).data("id");
		console.log('userId: ' + userId);

		$.confirm({
			title: 'Confirmation!',
			content: 'Are you sure you want to delete this applicant?',
			useBootstrap: false, 
			theme: 'supervan',
			buttons: {
				NO: function () {
					//do nothing
				},
				YES: function () {
					$.ajax({
						url: base_url() + 'home/delete_applicant?user_id=' + userId,
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
}

deleteApplicant();
