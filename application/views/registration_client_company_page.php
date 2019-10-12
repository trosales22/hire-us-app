<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags-->
    <meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="icon" type="image/png" href="<?php echo base_url(); ?>static/images/logo.ico"/>
    <meta name="description" content="Colorlib Templates">
    <meta name="author" content="Colorlib">
    <meta name="keywords" content="Colorlib Templates">

    <!-- Title Page-->
    <title>Hire Us | Client Registration (Company)</title>

    <!-- Icons font CSS-->
    <link href="<?php echo base_url(); ?>static/colorlib-regform-4/vendor/mdi-font/css/material-design-iconic-font.min.css" rel="stylesheet" media="all">
    <link href="<?php echo base_url(); ?>static/colorlib-regform-4/vendor/font-awesome-4.7/css/font-awesome.min.css" rel="stylesheet" media="all">
    <!-- Font special for pages-->
    <link href="https://fonts.googleapis.com/css?family=Poppins:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Vendor CSS-->
    <link href="<?php echo base_url(); ?>static/colorlib-regform-4/vendor/select2/select2.min.css" rel="stylesheet" media="all">
    <link href="<?php echo base_url(); ?>static/colorlib-regform-4/vendor/datepicker/daterangepicker.css" rel="stylesheet" media="all">

    <!-- Main CSS-->
    <link href="<?php echo base_url(); ?>static/colorlib-regform-4/css/main.css" rel="stylesheet">
	<link href="<?php echo base_url(); ?>static/js/libraries/jquery-confirm-v3.3.4/dist/jquery-confirm.min.css" rel="stylesheet">
	<link href="<?php echo base_url(); ?>static/css/parsley.css" rel="stylesheet">
</head>

<body>
    <div class="page-wrapper bg-gra-02 p-t-130 p-b-100 font-poppins">
        <div class="wrapper wrapper--w680">
            <div class="card card-4">
                <div class="card-body">
					<img src="<?php echo base_url(); ?>static/images/logo.png" style="display: block; margin-left: auto; margin-right: auto; width: 100px; height: 100px;">
                    <h2 class="title" style="text-align: center;">Client Registration (Company / Corporate)</h2>
					
                    <form id="frmRegisterIndividualClient" data-parsley-validate="" method="POST" action="<?php echo base_url(). 'client_company_registration/add_company_client'; ?>">
                        <div class="row row-space">
                            <div class="col-2">
                                <div class="input-group">
                                    <label class="label">company name</label>
                                    <input class="input--style-4" type="text" name="company_name" required>
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="input-group">
                                    <label class="label">name of contact person</label>
                                    <input class="input--style-4" type="text" name="company_contact_person" required>
                                </div>
                            </div>
						</div>
						
                        <div class="row row-space">
							<div class="input-group">
								<label class="label">position of contact person</label>
								<input class="input--style-4" type="text" name="company_contact_person_position" required>
							</div>
							
                            <div class="col-2">
                                <div class="input-group">
                                    <label class="label">Length of Service (Start date)</label>
                                    <div class="input-group-icon">
                                        <input class="input--style-4 js-datepicker" type="text" name="company_length_of_service" required>
                                        <i class="zmdi zmdi-calendar-note input-icon js-btn-calendar"></i>
                                    </div>
                                </div>
                            </div>
						</div>
						
                        <div class="row row-space">
                            <div class="col-2">
                                <div class="input-group">
                                    <label class="label">Email</label>
                                    <input class="input--style-4" type="email" name="company_email" data-parsley-trigger="change" required>
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="input-group">
                                    <label class="label">Contact Number</label>
                                    <input class="input--style-4" type="text" name="company_contact_number" required>
                                </div>
                            </div>
						</div>

						<div class="row row-space">
                            <div class="col-2">
                                <div class="input-group">
                                    <label class="label">Username</label>
                                    <input class="input--style-4" type="text" name="username" data-parsley-trigger="change" required>
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="input-group">
                                    <label class="label">Password</label>
                                    <input class="input--style-4" type="password" name="password" required maxlength="16">
                                </div>
                            </div>
						</div>
						
                        <div class="input-group">
							<label class="label">Company Address</label>
							
							<!-- add select--no-search in class if doesn't want to have a search filter -->
                            <div class="rs-select2 js-select-simple">
                                <select name="region" required>
									<option disabled="disabled" selected="selected">Choose Region</option>
									<?php foreach($param_regions as $region){?>
										<option value="<?php echo $region->regCode;?>"><?php echo $region->region_name;?></option>
									<?php }?>
                                </select>
                                <div class="select-dropdown"></div>
							</div>

							<br/>

							<!-- add select--no-search in class if doesn't want to have a search filter -->
                            <div class="rs-select2 js-select-simple">
                                <select name="province" required>
									<option disabled="disabled" selected="selected">Choose Province</option>
                                </select>
                                <div class="select-dropdown"></div>
							</div>
							
							<br/>

							<div class="rs-select2 js-select-simple">
                                <select name="city_muni" required>
                                    <option disabled="disabled" selected="selected">Choose City/Municipality</option>
                                </select>
                                <div class="select-dropdown"></div>
							</div>

							<br/>
							
							<div class="rs-select2 js-select-simple">
                                <select name="barangay" required>
                                    <option disabled="disabled" selected="selected">Choose Barangay</option>
                                </select>
                                <div class="select-dropdown"></div>
							</div>
						</div>

						<div class="row row-space">
                            <div class="col-2">
                                <div class="input-group">
                                    <label class="label">Street/Unit/Bldg/Village</label>
                                    <input class="input--style-4" type="text" name="bldg_village" required>
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="input-group">
									<label class="label">ZIP Code / Postal Code</label>
									<input class="input--style-4" type="text" name="zip_code" required>
                                </div>
                            </div>
						</div>

						<hr width="100%" />
						<h3 class="title">Valid ID's</h3>

						<div class="col-4">
							<div class="input-group">
								<label class="label">Company ID Image</label>
								<input class="input--style-4" type="file" name="company_id_image" required>
							</div>
						</div>
						
						<div class="row row-space">
                            <div class="col-2">
                                <!-- add select--no-search in class if doesn't want to have a search filter -->
								<div class="rs-select2 js-select-simple">
									<select name="company_government_issued_id" required>
										<option disabled="disabled" selected="selected">Choose Any Government Issued ID</option>
										<?php foreach($param_valid_ids as $valid_id){?>
											<option value="<?php echo $valid_id->valid_id_code;?>"><?php echo $valid_id->valid_id_name;?></option>
										<?php }?>
									</select>

									<div class="select-dropdown"></div>
								</div>
                            </div>

                            <div class="col-2">
                                <div class="input-group">
                                    <label class="label">Any Government Issued ID Image</label>
                                    <input class="input--style-4" type="file" name="company_government_issued_id_image" required>
                                </div>
                            </div>
						</div>
						
						<div class="col-4">
							<div class="input-group">
								<label class="label">2 Photos holding your Valid ID beside your face</label>
								<input class="input--style-4" type="file" name="valid_id_beside_your_face_image[]" multiple accept="image/png, image/jpeg, image/jpg" required />
							</div>
						</div>
						
                        <div class="p-t-15">
							<button class="btn btn--radius-2 btn--blue" type="button" onclick="window.location.href='<?php echo base_url(); ?>'">Back</button>
                            <button class="btn btn--radius-2 btn--green" type="submit">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Jquery JS-->
    <script src="<?php echo base_url(); ?>static/colorlib-regform-4/vendor/jquery/jquery.min.js"></script>
    <!-- Vendor JS-->
    <script src="<?php echo base_url(); ?>static/colorlib-regform-4/vendor/select2/select2.min.js"></script>
    <script src="<?php echo base_url(); ?>static/colorlib-regform-4/vendor/datepicker/moment.min.js"></script>
    <script src="<?php echo base_url(); ?>static/colorlib-regform-4/vendor/datepicker/daterangepicker.js"></script>

    <!-- Main JS-->
	<script src="<?php echo base_url(); ?>static/colorlib-regform-4/js/global.js"></script>
	
	<script src="<?php echo base_url(); ?>static/js/libraries/jquery-confirm-v3.3.4/dist/jquery-confirm.min.js"></script>
	<script src="https://parsleyjs.org/dist/parsley.min.js"></script>
	<script src="<?php echo base_url(); ?>static/js/client_registration_company.js"></script>

</body><!-- This templates was made by Colorlib (https://colorlib.com) -->

</html>
<!-- end document-->
