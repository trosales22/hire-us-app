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

	$('.btnModifyNews').click(function(){
		var newsId = $(this).data("id");
	
		var newsDetailsUrl = base_url() + 'api/news/get_news_by_id?news_id=' + newsId;
		$.getJSON(newsDetailsUrl, function(response) {
			//console.log(response);
			$("input[name='news_id']").val(response.news_id);
			$("input[id='frmModifyNews_caption']").val(response.news_caption);
			$("textarea[id='frmModifyNews_details']").val(response.news_details);
			$("input[id='frmModifyNews_link']").val(response.news_link);
			$("input[id='frmModifyNews_author']").val(response.news_author);
		});
	});

	$('#frmAddNews').parsley().on('field:validated', function() {
		var ok = $('.parsley-error').length === 0;

		if(ok){
			addNews();
		}
	});

	$('#frmModifyNews').parsley().on('field:validated', function() {
		var ok = $('.parsley-error').length === 0;
		
		if(ok){
			modifyNews();
		}
	});

	function addNews(){
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
												location.replace(base_url() + "news");
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

	function modifyNews(){
		$("#frmModifyNews").submit(function(e) {
			//prevent Default functionality
			e.preventDefault();
			var formAction = e.currentTarget.action;
			var formData = new FormData(this);
			var formType = "POST";
			
			//get the action-url of the form
			var actionUrl = e.currentTarget.action;
			
			$.confirm({
				title: 'Confirmation!',
				content: 'Are you sure you want to update this news & updates?',
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
												location.replace(base_url() + "news");
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

	function deleteNews(){
		$('.btnDeleteNews').click(function(){
			var newsId = $(this).data("id");
			console.log('newsId: ' + newsId);
	
			$.confirm({
				title: 'Confirmation!',
				content: 'Are you sure you want to delete this news?',
				useBootstrap: false, 
				theme: 'supervan',
				buttons: {
					NO: function () {
						//do nothing
					},
					YES: function () {
						$.ajax({
							url: base_url() + 'news/delete_news?news_id=' + newsId,
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
												location.replace(base_url() + "news");
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
	
	deleteNews();
});
