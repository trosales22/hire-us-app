<div class="modal fade" id="addNewsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <form id="frmAddNews" method="POST" action="<?php echo base_url(). 'news/add_news'; ?>">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Add News & Articles</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>

          <div class="modal-body">
            <div class="row form-group">
				<div class="col-sm-12">
					<label for="inputNewsCaption">Caption</label>
					<input type="text" class="form-control" id="inputNewsCaption" name="news_caption" placeholder="Enter caption.." required>
				</div>
			</div>
			
			<div class="row form-group">
				<div class="col-sm-12">
					<label for="inputNewsDetails">Details</label>
					<textarea class="form-control" rows="5" id="inputNewsDetails" name="news_details" placeholder="Write details.." style="resize: none;" required></textarea>
				</div>
			</div>

			<div class="row form-group">
				<div class="col-sm-6">
						<label for="imgNewsDisplayPhoto">Display Photo</label>
						<input type="file" id="imgNewsDisplayPhoto" name="news_display_pic" accept="image/png, image/jpeg" />
				</div>

				<div class="col-sm-6">
					<label for="inputNewsLink">Link</label>
					<input type="text" class="form-control" id="inputNewsLink" name="news_url" placeholder="Enter URL/link..">
				</div>
			</div>

			<div class="row form-group">
				<div class="col-sm-12">
					<label for="inputNewsAuthor">Author</label>
					<input type="text" class="form-control" id="inputNewsAuthor" name="news_author" placeholder="Enter author.." required>
				</div>
			</div>

			<div class="alert alert-info">
				<strong>Reminder!</strong>
				<br />Maximum width: <b>1000px</b> | Maximum height: <b>1000px</b> | Maximum file size: <b>2MB</b>
			</div>
          </div>
          
          <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
            <button class="btn btn-primary" type="submit">Submit</button>
          </div>
        </form>
      </div>
    </div>
</div>
