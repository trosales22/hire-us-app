<div class="modal fade" id="checkRequirementsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<form id="frmUpdateClientStatus" data-parsley-validate="" method="POST" action="<?php echo base_url(). 'home/update_client_status'; ?>">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Check Requirements</h5>
					<button class="close" type="button" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">×</span>
					</button>
				</div>
					
				<div class="modal-body">
					<div class="client_requirements"></div><br>
					
					<div class="row form-group">
						<div class="col-sm-8">
							<input type="hidden" name="checkReq_clientId" />
							
							<label for="cmbClientStatus">Status</label>
							<select id="cmbClientStatus" name="client_status" class="form-control" required>
								<option disabled="disabled" selected="selected">Choose Status</option>
								<option value="Y">Active</option>
								<option value="N">Inactive</option>
							</select>
						</div>
					</div>
				</div>
					
				<div class="modal-footer">
					<button class="btn btn-primary" type="submit">Update</button>	
					<button class="btn btn-secondary" type="button" data-dismiss="modal">Close</button>
				</div>
			</form>
		</div>
	</div>
</div>
