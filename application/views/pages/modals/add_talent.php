<div class="modal fade" id="addTalentOrModelModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <form id="frmAddTalentOrModel" method="POST" action="<?php echo base_url(). 'home/addTalentOrModel'; ?>">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Add Talent or Model</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>

          <div class="modal-body">
              <div class="row form-group">
                <div class="col-xs-4" style="margin-left: 10px; margin-right: 10px;">
                  <label for="inputFirstname">First Name</label>
                  <input type="text" class="form-control" id="inputFirstname" name="firstname" placeholder="Enter first name" required>
                </div>

                <div class="col-xs-4">
                  <label for="inputMiddlename">Middle Name</label>
                  <input type="text" class="form-control" id="inputMiddlename" name="middlename" placeholder="Enter middle name" required>
                </div>
								
                <div class="col-sm-4">
                  <label for="inputLastname">Last Name</label>
                  <input type="text" class="form-control" id="inputLastname" name="lastname" placeholder="Enter last name" required>
                </div>
              </div>

              <div class="row form-group">
                <div class="col-xs-4" style="margin-left: 10px; margin-right: 10px;">
                  <label for="inputEmail">Email</label>
                  <input type="email" class="form-control" id="inputEmail" name="email" placeholder="Enter email" required>
                </div>

                <div class="col-xs-4">
                  <label for="inputContactNumber">Contact Number</label>
                  <input type="text" class="form-control" id="inputContactNumber" name="contact_number" placeholder="Enter contact number" required>
                </div>

								<div class="col-sm-4">
                  <label for="cmbGender">Gender</label>
                  <select id="cmbGender" name="gender" class="form-control" required>
										<option disabled="disabled" selected="selected">Choose Gender</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                  </select>
                </div>
              </div>

              <div class="row form-group">
								<div class="col-xs-4" style="margin-left: 10px; margin-right: 10px;">
									<label for="inputHeight">Height <b>(in inches)</b></label>
                  <input type="text" class="form-control" id="inputHeight" name="height" placeholder="Enter height in inches" required>
                </div>

                <div class="col-xs-4">
									<label for="inputBirthdate">Birth Date</label><br>
                  <input type="text" class="form-control" id="inputBirthdate" name="birth_date" placeholder="Choose birthdate" required>
                </div>
								
                <div class="col-sm-4">
									<label for="inputHourlyRate">Rate per hour</label>
                  <input type="text" class="form-control" id="inputHourlyRate" name="hourly_rate" placeholder="Enter hourly rate" required>
                </div>
              </div>

							<div class="row form-group">
                <div class="col-xs-4" style="margin-left: 10px; margin-right: 10px;">
                  <label for="inputVitalStats">Vital Statistics</label>
                  <input type="text" class="form-control" id="inputVitalStats" name="vital_stats" placeholder="Enter vital statistics">
                </div>

                <div class="col-xs-4">
                  <label for="inputFbFollowers">Facebook Followers</label>
                  <input type="text" class="form-control" id="inputFbFollowers" name="fb_followers" placeholder="Enter FB Followers">
                </div>
								
								<div class="col-sm-4">
									<label for="inputInstagramFollowers">Instagram Followers</label>
                  <input type="text" class="form-control" id="inputInstagramFollowers" name="instagram_followers" placeholder="Enter Instagram Followers">
								</div>
							</div>

							<div class="row form-group">
								<div class="col-sm-5">
               		<label for="inputDescription">Description</label>
                	<textarea class="form-control" rows="5" id="inputDescription" name="description" placeholder="Enter talent's motto in life or any other things that describe him/her.." style="resize: none;" required></textarea>
								</div>
								
								<div class="col-sm-6">
               		<label for="inputPreviousClients">Previous Client(s)</label>
                	<textarea class="form-control" rows="5" id="inputPreviousClients" name="prev_clients" placeholder="Enter previous clients.." style="resize: none;" required></textarea>
								</div>
							</div>
							
							<hr width="100%" />
								<h5>Address</h5>
							<hr width="100%" />

							<div class="row form-group">
								<div class="col-sm-6">
									<label for="insertTalent_cmbRegion">Region</label>
									<select name="region" id="insertTalent_cmbRegion" class="form-control" required>
										<option disabled="disabled" selected="selected">Choose Region</option>
										<?php foreach($param_regions as $region){?>
											<option value="<?php echo $region->regCode;?>"><?php echo $region->region_name;?></option>
										<?php }?>
                  </select>
								</div>
										
								<div class="col-sm-6">
									<label for="insertTalent_cmbProvince">Province</label>
									<select name="province" id="insertTalent_cmbProvince" class="form-control" required>
										<option disabled="disabled" selected="selected">Choose Province</option>
                  </select>
								</div>
							</div>

							<div class="row form-group">
								<div class="col-sm-6">
									<label for="insertTalent_cmbCityMunicipality">City/Municipality</label>
									<select name="city_muni" id="insertTalent_cmbCityMunicipality" class="form-control" required>
										<option disabled="disabled" selected="selected">Choose City/Municipality</option>
									</select>
								</div>

								<div class="col-sm-6">
									<label for="insertTalent_cmbBarangay">Barangay</label>
									<select name="barangay" id="insertTalent_cmbBarangay" class="form-control" required>
										<option disabled="disabled" selected="selected">Choose Barangay</option>
									</select>
								</div>
							</div>

							<div class="row form-group">
								<div class="col-sm-6">
									<label for="streetUnit">Street/Unit/Bldg/Village</label>
                  <input class="form-control" id="streetUnit" type="text" name="bldg_village" placeholder="Enter Street/Unit/Bldg/Village" required>
								</div>

								<div class="col-sm-6">
									<label for="zipCode">ZIP Code / Postal Code</label>
                  <input class="form-control" id="zipCode" type="text" name="zip_code" maxlength="5" placeholder="Enter ZIP Code / Postal Code" required>
								</div>
							</div>

							<hr width="100%" />

              <div class="row form-group">
								<div class="col-sm-6">
									<label for="cmbCategory">Category</label>
                  <select id="cmbCategory" name="category[]" class="form-control" multiple required>
                    <?php foreach($categories as $category){?>
                      <option value="<?php echo $category->category_id;?>"><?php echo $category->category_name;?></option>   
                     <?php }?>
                  </select>
								</div>

								<div class="col-sm-6">
									<label for="inputGenre">Genre</label>
                  <input type="text" class="form-control" id="inputGenre" name="genre" placeholder="Enter Genre">
								</div>		
							</div>

							<div class="alert alert-info">
								<strong>Reminder!</strong> <br />Default password: <b>HIRE_US@123</b>
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
