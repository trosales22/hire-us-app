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
    <title>Hire Us | Client Registration (Individual)</title>

    <!-- Icons font CSS-->
    <link href="<?php echo base_url(); ?>static/colorlib-regform-4/vendor/mdi-font/css/material-design-iconic-font.min.css" rel="stylesheet" media="all">
    <link href="<?php echo base_url(); ?>static/colorlib-regform-4/vendor/font-awesome-4.7/css/font-awesome.min.css" rel="stylesheet" media="all">
    <!-- Font special for pages-->
    <link href="https://fonts.googleapis.com/css?family=Poppins:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Vendor CSS-->
    <link href="<?php echo base_url(); ?>static/colorlib-regform-4/vendor/select2/select2.min.css" rel="stylesheet" media="all">
    <link href="<?php echo base_url(); ?>static/colorlib-regform-4/vendor/datepicker/daterangepicker.css" rel="stylesheet" media="all">

    <!-- Main CSS-->
    <link href="<?php echo base_url(); ?>static/colorlib-regform-4/css/main.css" rel="stylesheet" media="all">
</head>

<body>
    <div class="page-wrapper bg-gra-02 p-t-130 p-b-100 font-poppins">
        <div class="wrapper wrapper--w680">
            <div class="card card-4">
                <div class="card-body">
                    <h2 class="title">Client Registration (Individual)</h2>
                    <form method="POST">
                        <div class="row row-space">
                            <div class="col-2">
                                <div class="input-group">
                                    <label class="label">first name</label>
                                    <input class="input--style-4" type="text" name="first_name">
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="input-group">
                                    <label class="label">last name</label>
                                    <input class="input--style-4" type="text" name="last_name">
                                </div>
                            </div>
						</div>
						
                        <div class="row row-space">
                            <div class="col-2">
                                <div class="input-group">
                                    <label class="label">Birthday</label>
                                    <div class="input-group-icon">
                                        <input class="input--style-4 js-datepicker" type="text" name="birthday">
                                        <i class="zmdi zmdi-calendar-note input-icon js-btn-calendar"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="input-group">
                                    <label class="label">Gender</label>
                                    <div class="p-t-10">
                                        <label class="radio-container m-r-45">Male
                                            <input type="radio" checked="checked" name="gender">
                                            <span class="checkmark"></span>
                                        </label>
                                        <label class="radio-container">Female
                                            <input type="radio" name="gender">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
						</div>
						
                        <div class="row row-space">
                            <div class="col-2">
                                <div class="input-group">
                                    <label class="label">Email</label>
                                    <input class="input--style-4" type="email" name="email">
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="input-group">
                                    <label class="label">Phone Number</label>
                                    <input class="input--style-4" type="text" name="phone">
                                </div>
                            </div>
						</div>
						
                        <div class="input-group">
							<label class="label">Address</label>
							
							<!-- add select--no-search in class if doesn't want to have a search filter -->
                            <div class="rs-select2 js-select-simple">
                                <select name="province">
									<option disabled="disabled" selected="selected">Choose Province</option>
									<?php foreach($param_provinces as $province){?>
										<option value="<?php echo $province->provCode;?>"><?php echo $province->provDesc;?></option>
									<?php }?>
                                </select>
                                <div class="select-dropdown"></div>
							</div>
							
							<br/>

							<div class="rs-select2 js-select-simple">
                                <select name="city_muni">
                                    <option disabled="disabled" selected="selected">Choose City/Municipality</option>
                                </select>
                                <div class="select-dropdown"></div>
							</div>

							<br/>
							
							<div class="rs-select2 js-select-simple">
                                <select name="barangay">
                                    <option disabled="disabled" selected="selected">Choose Barangay</option>
                                </select>
                                <div class="select-dropdown"></div>
							</div>
						</div>

						<div class="row row-space">
                            <div class="col-2">
                                <div class="input-group">
                                    <label class="label">Street/Unit/Bldg/Village</label>
                                    <input class="input--style-4" type="text" name="bldg_village">
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="input-group">
									<label class="label">ZIP Code / Postal Code</label>
									<input class="input--style-4" type="text" name="zip_code">
                                </div>
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
	<script src="<?php echo base_url(); ?>static/js/client_registration_individual.js"></script>

</body><!-- This templates was made by Colorlib (https://colorlib.com) -->

</html>
<!-- end document-->
