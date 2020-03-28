<div class="modal fade" id="addTalentOrModelModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form id="frmAddTalentOrModel" method="POST" action="<?php echo base_url(). 'api/talents/add_talent'; ?>">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Talent or Model</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="row form-group">
                        <div class="col-xs-4" style="margin-left: 10px; margin-right: 10px;">
                            <label>First Name</label>
                            <input type="text" class="form-control" name="firstname" placeholder="Enter first name" required>
                        </div>

                        <div class="col-xs-4">
                            <label>Middle Name</label>
                            <input type="text" class="form-control" name="middlename" placeholder="Enter middle name" required>
                        </div>

                        <div class="col-sm-4">
                            <label>Last Name</label>
                            <input type="text" class="form-control" name="lastname" placeholder="Enter last name" required>
                        </div>
                    </div>

                    <div class="row form-group">
                        <div class="col-xs-4" style="margin-left: 10px; margin-right: 10px;">
                            <label>Email</label>
                            <input type="email" class="form-control" name="email" placeholder="Enter email" required>
                        </div>

                        <div class="col-xs-4">
                            <label>Contact Number</label>
                            <input type="text" class="form-control" name="contact_number" placeholder="Enter contact number" required>
                        </div>

                        <div class="col-sm-4">
                            <label>Gender</label>
                            <select name="gender" class="form-control" required>
                                <option disabled="disabled" selected="selected">Choose Gender</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                        </div>
                    </div>

                    <div class="row form-group">
                        <div class="col-sm-4">
                            <label>Height <b>(in inches)</b></label>
                            <input type="text" class="form-control" name="height" placeholder="Enter height in inches" required>
                        </div>

                        <div class="col-sm-4">
                            <label>Birth Date</label>
                            <input type="text" class="form-control" name="birth_date" placeholder="Choose birthdate" required>
                        </div>
                    </div>

                    <div class="row form-group">
                        <div class="col-xs-4" style="margin-left: 10px; margin-right: 10px;">
                            <label>Vital Statistics</label>
                            <input type="text" class="form-control" name="vital_stats" placeholder="Enter vital statistics">
                        </div>

                        <div class="col-xs-4">
                            <label>Facebook Followers</label>
                            <input type="text" class="form-control" name="fb_followers" placeholder="Enter FB Followers">
                        </div>

                        <div class="col-sm-4">
                            <label>Instagram Followers</label>
                            <input type="text" class="form-control" name="instagram_followers" placeholder="Enter Instagram Followers">
                        </div>
                    </div>

                    <div class="row form-group">
                        <div class="col-sm-5">
                            <label>Description</label>
                            <textarea class="form-control" rows="5" name="description" placeholder="Enter talent's motto in life or any other things that describe him/her.." style="resize: none;"></textarea>
                        </div>

                        <div class="col-sm-6">
                            <label>Previous Client(s)</label>
                            <textarea class="form-control" rows="5" name="prev_clients" placeholder="Enter previous clients.." style="resize: none;" required></textarea>
                        </div>
                    </div>

                    <hr width="100%" />
                    <h5>Address</h5>
                    <hr width="100%" />

                    <div class="row form-group">
                        <div class="col-sm-6">
                            <label>Region</label>
                            <select name="region" id="region" class="form-control" required>
                                <option disabled="disabled" selected="selected">Choose Region</option>
                                <?php foreach($param_regions as $region){?>
                                    <option value="<?php echo $region->regCode;?>">
                                        <?php echo $region->region_name;?>
                                    </option>
                                    <?php }?>
                            </select>
                        </div>

                        <div class="col-sm-6">
                            <label>Province</label>
                            <select name="province" id="province" class="form-control" required>
                                <option disabled="disabled" selected="selected">Choose Province</option>
                            </select>
                        </div>
                    </div>

                    <div class="row form-group">
                        <div class="col-sm-6">
                            <label>City/Municipality</label>
                            <select name="city_muni" id="city_muni" class="form-control" required>
                                <option disabled="disabled" selected="selected">Choose City/Municipality</option>
                            </select>
                        </div>

                        <div class="col-sm-6">
                            <label>Barangay</label>
                            <select name="barangay" id="barangay" class="form-control" required>
                                <option disabled="disabled" selected="selected">Choose Barangay</option>
                            </select>
                        </div>
                    </div>

                    <div class="row form-group">
                        <div class="col-sm-6">
                            <label>Street/Unit/Bldg/Village</label>
                            <input class="form-control" type="text" name="bldg_village" placeholder="Enter Street/Unit/Bldg/Village" required>
                        </div>

                        <div class="col-sm-6">
                            <label>ZIP Code / Postal Code</label>
                            <input class="form-control" type="text" name="zip_code" maxlength="5" placeholder="Enter ZIP Code / Postal Code" required>
                        </div>
                    </div>

                    <hr width="100%" />

                    <div class="row form-group">
                        <div class="col-sm-6">
                            <label>Category</label>
                            <select name="category[]" class="form-control" multiple required>
                                <?php foreach($categories as $category){?>
                                    <option value="<?php echo $category->category_id;?>">
                                        <?php echo $category->category_name;?>
                                    </option>
                                    <?php }?>
                            </select>
                        </div>

                        <div class="col-sm-6">
                            <label>Genre</label>
                            <input type="text" class="form-control" name="genre" placeholder="Enter Genre">
                        </div>
                    </div>

                    <div class="row form-group">
                        <div class="col-sm-6">
                            <label>Profile Picture</label>
                            <input type="file" name="talent_profile_img" accept="image/png, image/jpg, image/jpeg" />
                        </div>

                        <div class="col-sm-6">
                            <label>Gallery (Min/Max: 8-10)</label>
                            <input type="file" name="talent_gallery[]" multiple accept="image/png, image/jpg, image/jpeg" />
                        </div>
                    </div>

                    <div class="row form-group">
                        <div class="col-sm-12">
                            <label>Screen Name</label>
                            <input type="text" class="form-control" name="screen_name" placeholder="Enter screen name" required>
                        </div>
                    </div>

                    <div class="alert alert-info">
                        <strong>Reminder!</strong>
                        <br />Default password: <b>HIRE_US@123</b>
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
