<?php
	if ($this->session->userdata('logged_in')) {
		redirect(base_url('home_page'));
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>Welcome to Hire Us</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" type="image/png" href="<?php echo base_url(); ?>static/images/logo.ico"/>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>static/Login_v18/vendor/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>static/Login_v18/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>static/Login_v18/fonts/Linearicons-Free-v1.0.0/icon-font.min.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>static/Login_v18/vendor/animate/animate.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>static/Login_v18/vendor/css-hamburgers/hamburgers.min.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>static/Login_v18/vendor/animsition/css/animsition.min.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>static/Login_v18/vendor/select2/select2.min.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>static/Login_v18/vendor/daterangepicker/daterangepicker.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>static/Login_v18/css/util.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>static/Login_v18/css/main.css">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
</head>
<body style="background-color: #666666;">
	
	<div class="limiter">
		<div class="container-login100">
			<div class="wrap-login100">
				<form class="login100-form validate-form" method="POST" action="<?php echo base_url(). 'login/user_login_process'; ?>">
					<span class="login100-form-title p-b-43">
						<img src="<?php echo base_url(); ?>static/images/logo.png" style="height: 80px; width: 80px;">
						Hire Us<br />Please login to continue
					</span>
					
					<div class="wrap-input100 validate-input" data-validate = "Username is required">
						<input class="input100" type="text" name="username">
						<span class="focus-input100"></span>
						<span class="label-input100">Username</span>
					</div>
					
					
					<div class="wrap-input100 validate-input" data-validate="Password is required">
						<input class="input100" type="password" name="password">
						<span class="focus-input100"></span>
						<span class="label-input100">Password</span>
					</div>

					<div class="flex-sb-m w-full p-t-3 p-b-32">
						<div class="contact100-form-checkbox">
							<input class="input-checkbox100" id="ckb1" type="checkbox" name="remember-me">
							<label class="label-checkbox100" for="ckb1">
								Remember me
							</label>
						</div>

						<div>
							<a href="#" class="txt1">
								Forgot Password?
							</a>
						</div>
					</div>
			

					<div class="container-login100-form-btn">
						<button class="login100-form-btn" type="submit" style="margin-bottom: 10px;">
							<i class="fa fa-sign-in fa-lg"></i> Login
						</button>

						<button class="login100-form-btn" type="button" style="margin-bottom: 10px; background-color: orange;" data-toggle="modal" data-target="#registrationPortalApplicantModal">
							<i class="fa fa-briefcase fa-lg"></i> Register as an Applicant
						</button>

						<a href="<?php echo base_url(); ?>client_individual_registration" style="text-decoration: none; width: 100%;">
							<button class="login100-form-btn" type="button" style="margin-bottom: 10px; background-color: red;">
								<i class="fa fa-handshake-o fa-lg"></i> Register as a Client
							</button>
						</a>
					</div>

					<span style="font-size: 20px; margin: auto; display:table; margin-top: 10px;">
						<?php echo $error_message; ?>
					</span>
				</form>

				<div class="login100-more" style="background-image: url('<?php echo base_url(); ?>static/Login_v18/images/background_image.jpg');">
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="registrationPortalApplicantModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<form id="frmInsertApplicant" method="POST" action="<?php echo base_url(). 'login/insertApplicant'; ?>">
					<div class="modal-header">
						<h5 class="modal-title" id="exampleModalLabel">Registration Portal (Applicant)</h5>
						<button class="close" type="button" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">×</span>
						</button>
					</div>
					
					<div class="modal-body">
						<div class="row form-group">
							<div class="col-sm-4">
								<label for="inputFirstname">First Name</label>
								<input type="text" class="form-control" id="inputFirstname" name="firstname" placeholder="Enter first name" required>
							</div>

							<div class="col-sm-4">
								<label for="inputMiddlename">Middle Name</label>
								<input type="text" class="form-control" id="inputMiddlename" name="middlename" placeholder="Enter middle name" required>
							</div>
											
							<div class="col-sm-4">
								<label for="inputLastname">Last Name</label>
								<input type="text" class="form-control" id="inputLastname" name="lastname" placeholder="Enter last name" required>
							</div>
						</div>

						<div class="row form-group">
							<div class="col-sm-4">
								<label for="inputEmail">Email</label>
								<input type="email" class="form-control" id="inputEmail" name="email" placeholder="Enter email" required>
							</div>

							<div class="col-sm-4">
								<label for="inputContactNumber">Contact Number</label>
								<input type="text" class="form-control" id="inputContactNumber" name="contact_number" placeholder="Enter contact number" required>
							</div>

							<div class="col-sm-4">
								<label for="cmbGender">Gender</label>
								<select id="cmbGender" name="gender" class="form-control" required>
									<option value="Male">Male</option>
									<option value="Female">Female</option>
								</select>
							</div>
						</div>

						<div class="row form-group">
							<div class="col-sm-6">
								<label for="inputPassword">Password</label>
								<input type="password" class="form-control" id="inputPassword" name="password" placeholder="Enter your password" maxlength="16" required>
							</div>
						</div>
					</div>

					<div class="modal-footer">
						<button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
						<button class="btn btn-primary" id="btnUpdateTalentProfilePic" type="submit">Register</button>
					</div>
				</form>
			</div>
		</div>
	</div>

	<script src="<?php echo base_url(); ?>static/Login_v18/vendor/jquery/jquery-3.2.1.min.js"></script>
	<script src="<?php echo base_url(); ?>static/Login_v18/vendor/animsition/js/animsition.min.js"></script>
	<script src="<?php echo base_url(); ?>static/Login_v18/vendor/bootstrap/js/popper.js"></script>
	<script src="<?php echo base_url(); ?>static/Login_v18/vendor/bootstrap/js/bootstrap.min.js"></script>
	<script src="<?php echo base_url(); ?>static/Login_v18/vendor/select2/select2.min.js"></script>
	<script src="<?php echo base_url(); ?>static/Login_v18/vendor/daterangepicker/moment.min.js"></script>
	<script src="<?php echo base_url(); ?>static/Login_v18/vendor/daterangepicker/daterangepicker.js"></script>
	<script src="<?php echo base_url(); ?>static/Login_v18/vendor/countdowntime/countdowntime.js"></script>
	<script src="<?php echo base_url(); ?>static/Login_v18/js/main.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
	<script src="<?php echo base_url(); ?>static/js/login.js"></script>
</body>
</html>
