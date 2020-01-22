<div class="modal fade" id="viewOrEditTalentOrModelModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form id="frmViewOrEditTalentOrModel" method="POST" action="<?php echo base_url(). 'home/update_talent'; ?>">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit/View Talent or Model</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="row form-group">
                        <img src="<?php echo base_url() . '/static/images/no_profile_pic.png'; ?>" id="viewTalentDisplayPhoto" width="150" height="150" style="margin-left: auto; margin-right: auto; border:1px solid black;" />
                    </div>

                    <div class="row form-group">
                        <div class="col-xs-4" style="margin-left: 10px; margin-right: 10px;">
                            <label for="inputFirstname">First Name</label>
                            <input type="text" class="form-control" id="inputFirstname" name="talent_firstname" placeholder="Enter first name" required>
                        </div>

                        <div class="col-xs-4">
                            <label for="inputMiddlename">Middle Name</label>
                            <input type="text" class="form-control" id="inputMiddlename" name="talent_middlename" placeholder="Enter middle name" required>
                        </div>

                        <div class="col-sm-4">
                            <label for="inputLastname">Last Name</label>
                            <input type="text" class="form-control" id="inputLastname" name="talent_lastname" placeholder="Enter last name" required>
                        </div>
                    </div>

                    <div class="row form-group">
                        <div class="col-xs-4" style="margin-left: 10px; margin-right: 10px;">
                            <label for="inputEmail">Email</label>
                            <input type="email" class="form-control" id="inputEmail" name="talent_email" placeholder="Enter email" required>
                        </div>

                        <div class="col-xs-4">
                            <label for="inputContactNumber">Contact Number</label>
                            <input type="text" class="form-control" id="inputContactNumber" name="talent_contact_number" placeholder="Enter contact number" required>
                        </div>

                        <div class="col-sm-4">
                            <label for="cmbGender">Gender</label>
                            <select id="cmbGender" name="talent_gender" class="form-control" required>
                                <option disabled="disabled" selected="selected">Choose Gender</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                        </div>
                    </div>

                    <div class="row form-group">
                        <div class="col-sm-4">
                            <label for="inputHeight">Height <b>(in inches)</b></label>
                            <input type="text" class="form-control" id="inputHeight" name="talent_height" placeholder="Enter height in inches" required>
                        </div>

                        <div class="col-sm-4">
                            <label for="inputBirthdate">Birth Date</label>
                            <br>
                            <input type="text" class="form-control" id="inputBirthdate" name="talent_birth_date" placeholder="Choose birthdate" required>
                        </div>
                    </div>

                    <div class="row form-group">
                        <div class="col-xs-4" style="margin-left: 10px; margin-right: 10px;">
                            <label for="inputVitalStats">Vital Statistics</label>
                            <input type="text" class="form-control" id="inputVitalStats" name="talent_vital_stats" placeholder="Enter vital statistics">
                        </div>

                        <div class="col-xs-4">
                            <label for="inputFbFollowers">Facebook Followers</label>
                            <input type="text" class="form-control" id="inputFbFollowers" name="talent_fb_followers" placeholder="Enter FB Followers">
                        </div>

                        <div class="col-sm-4">
                            <label for="inputInstagramFollowers">Instagram Followers</label>
                            <input type="text" class="form-control" id="inputInstagramFollowers" name="talent_instagram_followers" placeholder="Enter Instagram Followers">
                        </div>
                    </div>

                    <div class="row form-group">
                        <div class="col-sm-5">
                            <label for="inputDescription">Description</label>
                            <textarea class="form-control" rows="5" id="inputDescription" name="talent_description" placeholder="Enter talent's motto in life or any other things that describe him/her.." style="resize: none;" required></textarea>
                        </div>

                        <div class="col-sm-6">
                            <label for="inputPreviousClients">Previous Client(s)</label>
                            <textarea class="form-control" rows="5" id="inputPreviousClients" name="talent_prev_clients" placeholder="Enter previous clients.." style="resize: none;" required></textarea>
                        </div>
                    </div>

                    <hr width="100%" />
                    <h5>Address</h5>
                    <hr width="100%" />

                    <div class="row form-group">
                        <div class="col-sm-6">
                            <label for="cmbRegion">Region</label>
                            <select name="region" id="cmbRegion" class="form-control" required>
                                <option disabled="disabled" selected="selected">Choose Region</option>
                                <?php foreach($param_regions as $region){?>
                                    <option value="<?php echo $region->regCode;?>">
                                        <?php echo $region->region_name;?>
                                    </option>
                                    <?php }?>
                            </select>
                        </div>

                        <div class="col-sm-6">
                            <label for="cmbProvince">Province</label>
                            <select name="province" id="cmbProvince" class="form-control" required>
                                <option disabled="disabled" selected="selected">Choose Province</option>
                            </select>
                        </div>
                    </div>

                    <div class="row form-group">
                        <div class="col-sm-6">
                            <label for="cmbCityMunicipality">City/Municipality</label>
                            <select name="city_muni" id="cmbCityMunicipality" class="form-control" required>
                                <option disabled="disabled" selected="selected">Choose City/Municipality</option>
                            </select>
                        </div>

                        <div class="col-sm-6">
                            <label for="cmbBarangay">Barangay</label>
                            <select name="barangay" id="cmbBarangay" class="form-control" required>
                                <option disabled="disabled" selected="selected">Choose Barangay</option>
                            </select>
                        </div>
                    </div>

                    <div class="row form-group">
                        <div class="col-sm-6">
                            <label for="streetUnit">Street/Unit/Bldg/Village</label>
                            <input class="form-control" id="streetUnit" type="text" name="talent_bldg_village" placeholder="Enter Street/Unit/Bldg/Village" required>
                        </div>

                        <div class="col-sm-6">
                            <label for="zipCode">ZIP Code / Postal Code</label>
                            <input class="form-control" id="zipCode" type="text" name="talent_zip_code" maxlength="5" placeholder="Enter ZIP Code / Postal Code" required>
                        </div>
                    </div>

                    <hr width="100%" />

                    <div class="row form-group">
                        <div class="col-sm-6">
                            <label for="talent_cmbCategory">Category</label>
                            <select id="talent_cmbCategory" name="category[]" class="form-control" multiple required>
                                <?php foreach($categories as $category){?>
                                    <option value="<?php echo $category->category_id;?>">
                                        <?php echo $category->category_name;?>
                                    </option>
                                    <?php }?>
                            </select>
                        </div>

                        <div class="col-sm-6">
                            <label for="inputGenre">Genre</label>
                            <input type="text" class="form-control" id="inputGenre" name="talent_genre" placeholder="Enter Genre">
                        </div>
                    </div>

                    <hr width="100%" />
                    <h5>Gallery</h5>
                    <hr width="100%" />

                    <div class="row form-group">
                        <div class="talent_gallery" style="display: grid; grid-template-columns: auto auto auto auto; margin-bottom: 10px;"></div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Close</button>
                    <button class="btn btn-primary" type="submit">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
