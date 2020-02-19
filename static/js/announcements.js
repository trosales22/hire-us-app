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

$('#tbl_announcements').DataTable();

function addAnnouncement(){
	$("#frmAddAnnouncement").submit(function(e) {
		//prevent Default functionality
		e.preventDefault();
		
		//get the action-url of the form
		var actionUrl = e.currentTarget.action;
		
		$.confirm({
			title: 'Confirmation!',
			content: 'Are you sure you want to add this announcement?',
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
						data: $("#frmAddAnnouncement").serialize(),
						success: function(data) {
							var res = $.parseJSON(data);
							
							if(res.flag === 0){
								$.alert({
									title: "Oops! We're sorry!",
									content: res.msg,
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
									content: res.msg,
									useBootstrap: false,
									theme: 'supervan',
									buttons: {
										'Ok, Got It!': function () {
											location.replace(base_url() + "/announcements");
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

$('.btnModifyAnnouncement').click(function(){
	var announcementId = $(this).data("id");

	var announcementDetailsUrl = base_url() + 'api/announcements/get_announcement_by_id?announcement_id=' + announcementId;
	$.getJSON(announcementDetailsUrl, function(response) {
		//console.log(response);
		$("input[name='announcement_id']").val(response.announcement_id);
		$("input[name='announcement_caption']").val(response.announcement_caption);
		$("textarea[name='announcement_details']").val(response.announcement_details);
	});
});

function modifyAnnouncement(){
	$("#frmModifyAnnouncement").submit(function(e) {
		e.preventDefault();
		var formAction = e.currentTarget.action;
		var formType = "POST";
	
		$.confirm({
			title: 'Confirmation!',
			content: 'Are you sure you want update this announcement?',
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
						data: $("#frmModifyAnnouncement").serialize(),
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
											location.replace(base_url() + "/announcements");
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

function deleteAnnouncement(){
	$('.btnDeleteAnnouncement').click(function(){
		var announcementId = $(this).data("id");
		console.log('announcementId: ' + announcementId);

		$.confirm({
			title: 'Confirmation!',
			content: 'Are you sure you want to delete this announcement?',
			useBootstrap: false, 
			theme: 'supervan',
			buttons: {
				NO: function () {
					//do nothing
				},
				YES: function () {
					$.ajax({
						url: base_url() + 'announcements/delete_announcement?announcement_id=' + announcementId,
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
											location.replace(base_url() + "/announcements");
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

$('#frmAddAnnouncement').parsley().on('field:validated', function() {
	var ok = $('.parsley-error').length === 0;
});

addAnnouncement();
modifyAnnouncement();
deleteAnnouncement();
