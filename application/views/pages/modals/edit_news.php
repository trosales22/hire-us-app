<div class="modal fade" id="modifyNewsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <form id="frmModifyNews" method="POST" action="<?php echo base_url(). 'news/modify_news'; ?>">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Edit News & Articles</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>

          <input type="hidden" name="news_id">

          <div class="modal-body">
            <div class="row form-group">
				<div class="col-sm-12">
					<label for="frmModifyNews_caption">Caption</label>
					<input type="text" class="form-control" id="frmModifyNews_caption" name="news_caption" placeholder="Enter caption.." required>
				</div>
			</div>
			
			<div class="row form-group">
				<div class="col-sm-12">
					<label for="frmModifyNews_details">Details</label>
					<textarea class="form-control" rows="5" id="frmModifyNews_details" name="news_details" placeholder="Write details.." style="resize: none;" required></textarea>
				</div>
			</div>

			<div class="row form-group">
				<div class="col-sm-6">
						<label for="frmModifyNews_photo">Display Photo</label>
						<input type="file" id="frmModifyNews_photo" name="news_display_pic" accept="image/png, image/jpeg, image/jpg"/>
				</div>

				<div class="col-sm-6">
					<label for="frmModifyNews_link">Link</label>
					<input type="text" class="form-control" id="frmModifyNews_link" name="news_url" placeholder="Enter URL/link..">
				</div>
			</div>

			<div class="row form-group">
				<div class="col-sm-12">
					<label for="frmModifyNews_author">Author</label>
					<input type="text" class="form-control" id="frmModifyNews_author" name="news_author" placeholder="Enter author.." required>
				</div>
			</div>

			<div class="alert alert-info">
				<strong>Reminder!</strong>
				<br />Maximum width: <b>1000px</b> | Maximum height: <b>1000px</b> | Maximum file size: <b>2MB</b>
			</div>
          </div>
          
          <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
            <button class="btn btn-primary" type="submit">Update</button>
          </div>
        </form>
      </div>
    </div>
</div>
