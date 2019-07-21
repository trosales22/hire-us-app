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

$("#frmUpdateTalentProfilePic").submit(function (event) {
    event.preventDefault();

	var data = $('#frmUpdateTalentProfilePic').serialize();
	$.ajax({
		url: this.action,
		type: 'POST',
		contentType: 'multipart/form-data',
    	processData: false,
		data: data,
		success:function(data){
			console.log(data);
		}
	});
});
