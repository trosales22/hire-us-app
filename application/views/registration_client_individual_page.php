<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="icon" type="image/png" href="<?php echo base_url(); ?>static/images/logo.ico"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Hire Us: Client Registration (Individual)</title>

    <!-- Font Icon -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>static/colorlib-regform-16/fonts/material-icon/css/material-design-iconic-font.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>static/colorlib-regform-16/vendor/nouislider/nouislider.min.css">

    <!-- Main css -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>static/colorlib-regform-16/css/style.css">
</head>
<body>
    <div class="main">
        <div class="container">
            <div class="signup-content">
                <div class="signup-img">
                    <img src="<?php echo base_url(); ?>static/colorlib-regform-16/images/background_nature.jpg" alt="">
                    <div class="signup-img-content">
                        <h2>REGISTER NOW</h2>
                        <p>To start booking a talent or model!</p>
                    </div>
                </div>
                <div class="signup-form">			
                    <form method="POST" class="register-form" id="register-form" method="POST" action="<?php echo base_url(). 'login/insertClient'; ?>">
						<h2 style="color: black;">Client Registration <span style="color: #329e5e;">(INDIVIDUAL)</span></h2>
						
                        <div class="form-row">
                            <div class="form-group">
                                <div class="form-input">
                                    <label for="first_name" class="required">First name</label>
                                    <input type="text" name="first_name" id="first_name" />
                                </div>
                                <div class="form-input">
                                    <label for="last_name" class="required">Last name</label>
                                    <input type="text" name="last_name" id="last_name" />
                                </div>
                                <div class="form-input">
                                    <label for="individual_birthdate" class="required">Birth Date</label>
                                    <input type="text" name="individual_birthdate" id="individual_birthdate" />
                                </div>
                                <div class="form-input">
                                    <label for="email" class="required">Email</label>
                                    <input type="text" name="email" id="email" />
                                </div>
                            </div>
                            <div class="form-group">
								<div class="form-input">
                                    <label for="phone_number" class="required">Phone number</label>
                                    <input type="text" name="phone_number" id="phone_number" />
								</div>
								
                                <div class="form-radio">
                                    <div class="label-flex">
                                        <label for="gender" class="required">Gender</label>
                                    </div>
                                    <div class="form-radio-group">            
                                        <div class="form-radio-item">
                                            <input type="radio" name="gender" id="male" checked>
                                            <label for="male">Male</label>
                                            <span class="check"></span>
                                        </div>
                                        <div class="form-radio-item">
                                            <input type="radio" name="gender" id="female">
                                            <label for="female">Female</label>
                                            <span class="check"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-input">
                                    <label for="chequeno" class="required">Address</label>
									<textarea rows="7" id="frmClient_inputLocation" name="location" placeholder="Enter full address" style="resize: none; width: 100%;"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="form-submit">
							<input type="submit" value="Submit" class="submit" id="submit" name="submit" />
							<a href="<?php echo base_url(); ?>" style="text-decoration: none;">
								<input type="button" value="Back" class="submit" id="reset" name="reset" />
							</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>

    <!-- JS -->
    <script src="<?php echo base_url(); ?>static/colorlib-regform-16/vendor/jquery/jquery.min.js"></script>
    <script src="<?php echo base_url(); ?>static/colorlib-regform-16/vendor/nouislider/nouislider.min.js"></script>
    <script src="<?php echo base_url(); ?>static/colorlib-regform-16/vendor/wnumb/wNumb.js"></script>
    <script src="<?php echo base_url(); ?>static/colorlib-regform-16/vendor/jquery-validation/dist/jquery.validate.min.js"></script>
    <script src="<?php echo base_url(); ?>static/colorlib-regform-16/vendor/jquery-validation/dist/additional-methods.min.js"></script>
    <script src="<?php echo base_url(); ?>static/colorlib-regform-16/js/main.js"></script>
</body><!-- This templates was made by Colorlib (https://colorlib.com) -->
</html>
