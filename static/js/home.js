$("#inputBirthdate").flatpickr({
	dateFormat: "Y-m-d",
	static: true,
	allowInput: true
});

$('select#cmbCategory').selectize({
	placeholder: 'Choose category'
});

$('#inputHeight').maskMoney();
$('#inputTalentFee').maskMoney();

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
