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

$('#frmAddAnnouncement').parsley().on('field:validated', function() {
	var ok = $('.parsley-error').length === 0;
});

addAnnouncement();
