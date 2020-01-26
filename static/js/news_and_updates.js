$(function() {
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

	$('#tbl_news_and_updates').DataTable();

	function addNewsAndUpdates(){
		$("#frmAddNews").submit(function(e) {
			//prevent Default functionality
			e.preventDefault();
			var formAction = e.currentTarget.action;
			var formData = new FormData(this);
			var formType = "POST";
			
			//get the action-url of the form
			var actionUrl = e.currentTarget.action;
			
			$.confirm({
				title: 'Confirmation!',
				content: 'Are you sure you want to add this news & updates?',
				useBootstrap: false, 
				theme: 'supervan',
				buttons: {
					NO: function () {
						//do nothing
					},
					YES: function () {
						$.ajax({
							url: actionUrl,
							type: formType,
							data: formData,
							processData: false,
							contentType: false,
							cache: false,
							async: false,
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
												location.replace(base_url() + "/news");
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

	$('#frmAddNews').parsley().on('field:validated', function() {
		var ok = $('.parsley-error').length === 0;
	});

	addNewsAndUpdates();
});
