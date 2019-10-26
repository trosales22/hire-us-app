<div class="modal fade" id="updateTalentResourcesModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
      	<div class="modal-content">
         	<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Add Talent or Model Resources</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
					</div>

					<div class="modal-body">
            <form id="frmUpdateTalentProfilePic" method="POST" action="<?php echo base_url(). 'home/uploadProfilePicOfTalent'; ?>" enctype="multipart/form-data">
              <div class="form-group">
                <label for="profile_picture">Profile Picture</label>
								
								<input type="hidden" name="talent_id" />
                <input type="file" id="profile_picture"  name="profile_image" accept="image/png, image/jpeg" />                
                <button class="btn btn-primary" id="btnUpdateTalentProfilePic" type="submit" style="display: block; margin: 0 auto;">Upload Profile Picture</button>  
              </div>
            </form>
						
            <form id="frmUploadTalentGallery" method="POST" action="<?php echo base_url(). 'home/uploadTalentGallery'; ?>" enctype="multipart/form-data">
              <div class="form-group">
                <label for="talent_gallery">Gallery (Min/Max: 8-10)</label>
                
                <input type="hidden" name="talent_id" />
                <input type="file" name="talent_gallery[]" multiple accept="image/png, image/jpeg" />

                <!-- <input 
                  type="file" 
                  id="talent_gallery" 
                  class="filepond" 
                  name="talent_gallery[]" 
                  accept="image/png, image/jpeg"
                  multiple 
                  data-max-file-size="5MB"
                  data-min-files="8"
                  data-max-files="10" /> -->
                  
                <button class="btn btn-primary" id="btnUploadPictures" type="submit" style="display: block; margin: 0 auto;">Upload Pictures</button>
              </div>
            </form>
					</div>

					<div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Close</button>
          </div>
			</div>
		</div>
	</div>
