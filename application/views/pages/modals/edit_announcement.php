<div class="modal fade" id="modifyAnnouncementModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form id="frmModifyAnnouncement" method="POST" action="<?php echo base_url(). 'announcements/modify_announcement'; ?>">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Announcement</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>

				<input type="hidden" name="announcement_id">

                <div class="modal-body">
                    <div class="row form-group">
                        <div class="col-sm-12">
                            <label for="inputAnnouncementCaption">Caption</label>
                            <input type="text" class="form-control" id="inputAnnouncementCaption" name="announcement_caption" placeholder="Enter announcement caption.." required>
                        </div>
                    </div>

                    <div class="row form-group">
                        <div class="col-sm-12">
                            <label for="inputAnnouncementDetails">Details</label>
                            <textarea class="form-control" rows="5" id="inputAnnouncementDetails" name="announcement_details" placeholder="Write announcement details.." style="resize: none;" required></textarea>
                        </div>
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
