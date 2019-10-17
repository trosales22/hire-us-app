<?php
	if (!$this->session->userdata('logged_in')) {
		redirect(base_url('login_page'));
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="icon" type="image/png" href="<?php echo base_url(); ?>static/images/logo.ico"/>
  <meta name="description" content="Your Long-Term RAKET Partner">
  <meta name="author" content="Tristan Rosales">

  <title>Hire Us - Dashboard</title>

  <!-- Custom fonts for this template -->
  <link href="<?php echo base_url(); ?>static/SBAdmin/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
  <link href="<?php echo base_url(); ?>static/SBAdmin/css/sb-admin-2.min.css" rel="stylesheet">
	<link href="<?php echo base_url(); ?>static/SBAdmin/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
	
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.bootstrap3.css">
	<link href="<?php echo base_url(); ?>static/css/parsley.css" rel="stylesheet">
	<link href="<?php echo base_url(); ?>static/js/libraries/filepond/filepond.css" rel="stylesheet">
	<link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css" rel="stylesheet">
	<link href="<?php echo base_url(); ?>static/js/libraries/jquery-confirm-v3.3.4/dist/jquery-confirm.min.css" rel="stylesheet">

  <style>
  div.gallery {
    margin: 5px;
    border: 1px solid #ccc;
    float: left;
    width: 180px;
  }

  div.gallery:hover {
    border: 1px solid #777;
  }

  div.gallery img {
    width: 100%;
    height: 200px;
  }

  div.desc {
    padding: 15px;
    text-align: center;
  }
  </style>
</head>

<body id="page-top">

  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

      <!-- Sidebar - Brand -->
      <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?php echo base_url(); ?>">
        <div class="sidebar-brand-icon rotate-n-15">
          <img src="<?php echo base_url(); ?>static/images/logo.png" style="height: 50px; width: 50px;">
        </div>
        <div class="sidebar-brand-text mx-3">Hire Us</div>
      </a>

      <!-- Divider -->
      <hr class="sidebar-divider my-0">

      <!-- Nav Item - Dashboard -->
      <li class="nav-item">
        <a class="nav-link" href="<?php echo base_url(); ?>">
          <i class="fas fa-fw fa-home"></i>
          <span>Home</span></a>
      </li>
			
      <!-- Divider -->
      <hr class="sidebar-divider d-none d-md-block">

      <!-- Sidebar Toggler (Sidebar) -->
      <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
      </div>

    </ul>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        <!-- Topbar -->
        <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

          <!-- Sidebar Toggle (Topbar) -->
          <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
            <i class="fa fa-bars"></i>
          </button>

          <!-- Topbar Search -->
          <!-- <form class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
            <div class="input-group">
              <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2">
              <div class="input-group-append">
                <button class="btn btn-primary" type="button">
                  <i class="fas fa-search fa-sm"></i>
                </button>
              </div>
            </div>
          </form> -->

          <!-- Topbar Navbar -->
          <ul class="navbar-nav ml-auto">

            <!-- Nav Item - Search Dropdown (Visible Only XS) -->
            <li class="nav-item dropdown no-arrow d-sm-none">
              <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-search fa-fw"></i>
              </a>
              <!-- Dropdown - Messages -->
              <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in" aria-labelledby="searchDropdown">
                <form class="form-inline mr-auto w-100 navbar-search">
                  <div class="input-group">
                    <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2">
                    <div class="input-group-append">
                      <button class="btn btn-primary" type="button">
                        <i class="fas fa-search fa-sm"></i>
                      </button>
                    </div>
                  </div>
                </form>
              </div>
            </li>
						
            <!-- Nav Item - Alerts -->
            <li class="nav-item dropdown no-arrow mx-1">
              <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-bell fa-fw"></i>
                <!-- Counter - Alerts -->
                <span class="badge badge-danger badge-counter">3+</span>
              </a>
              <!-- Dropdown - Alerts -->
              <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="alertsDropdown">
                <h6 class="dropdown-header">
                  Alerts Center
                </h6>
                <a class="dropdown-item d-flex align-items-center" href="#">
                  <div class="mr-3">
                    <div class="icon-circle bg-primary">
                      <i class="fas fa-file-alt text-white"></i>
                    </div>
                  </div>
                  <div>
                    <div class="small text-gray-500">December 12, 2019</div>
                    <span class="font-weight-bold">A new monthly report is ready to download!</span>
                  </div>
                </a>
                <a class="dropdown-item d-flex align-items-center" href="#">
                  <div class="mr-3">
                    <div class="icon-circle bg-success">
                      <i class="fas fa-donate text-white"></i>
                    </div>
                  </div>
                  <div>
                    <div class="small text-gray-500">December 7, 2019</div>
                    $290.29 has been deposited into your account!
                  </div>
                </a>
                <a class="dropdown-item d-flex align-items-center" href="#">
                  <div class="mr-3">
                    <div class="icon-circle bg-warning">
                      <i class="fas fa-exclamation-triangle text-white"></i>
                    </div>
                  </div>
                  <div>
                    <div class="small text-gray-500">December 2, 2019</div>
                    Spending Alert: We've noticed unusually high spending for your account.
                  </div>
                </a>
                <a class="dropdown-item text-center small text-gray-500" href="#">Show All Alerts</a>
              </div>
            </li>

            <!-- Nav Item - Messages -->
            <li class="nav-item dropdown no-arrow mx-1">
              <a class="nav-link dropdown-toggle" href="#" id="messagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-envelope fa-fw"></i>
                <!-- Counter - Messages -->
                <span class="badge badge-danger badge-counter">7</span>
              </a>
              <!-- Dropdown - Messages -->
              <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="messagesDropdown">
                <h6 class="dropdown-header">
                  Message Center
                </h6>
                <a class="dropdown-item d-flex align-items-center" href="#">
                  <div class="dropdown-list-image mr-3">
                    <img class="rounded-circle" src="https://source.unsplash.com/fn_BT9fwg_E/60x60" alt="">
                    <div class="status-indicator bg-success"></div>
                  </div>
                  <div class="font-weight-bold">
                    <div class="text-truncate">Hi there! I am wondering if you can help me with a problem I've been having.</div>
                    <div class="small text-gray-500">Emily Fowler · 58m</div>
                  </div>
                </a>
                <a class="dropdown-item d-flex align-items-center" href="#">
                  <div class="dropdown-list-image mr-3">
                    <img class="rounded-circle" src="https://source.unsplash.com/AU4VPcFN4LE/60x60" alt="">
                    <div class="status-indicator"></div>
                  </div>
                  <div>
                    <div class="text-truncate">I have the photos that you ordered last month, how would you like them sent to you?</div>
                    <div class="small text-gray-500">Jae Chun · 1d</div>
                  </div>
                </a>
                <a class="dropdown-item d-flex align-items-center" href="#">
                  <div class="dropdown-list-image mr-3">
                    <img class="rounded-circle" src="https://source.unsplash.com/CS2uCrpNzJY/60x60" alt="">
                    <div class="status-indicator bg-warning"></div>
                  </div>
                  <div>
                    <div class="text-truncate">Last month's report looks great, I am very happy with the progress so far, keep up the good work!</div>
                    <div class="small text-gray-500">Morgan Alvarez · 2d</div>
                  </div>
                </a>
                <a class="dropdown-item d-flex align-items-center" href="#">
                  <div class="dropdown-list-image mr-3">
                    <img class="rounded-circle" src="https://source.unsplash.com/Mv9hjnEUHR4/60x60" alt="">
                    <div class="status-indicator bg-success"></div>
                  </div>
                  <div>
                    <div class="text-truncate">Am I a good boy? The reason I ask is because someone told me that people say this to all dogs, even if they aren't good...</div>
                    <div class="small text-gray-500">Chicken the Dog · 2w</div>
                  </div>
                </a>
                <a class="dropdown-item text-center small text-gray-500" href="#">Read More Messages</a>
              </div>
            </li>

            <div class="topbar-divider d-none d-sm-block"></div>

            <!-- Nav Item - User Information -->
            <li class="nav-item dropdown no-arrow">
              <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="mr-2 d-none d-lg-inline text-gray-600 small">Josh Saratan</span>
                <img class="img-profile rounded-circle" src="<?php echo base_url(); ?>static/SBAdmin/img/no-profile-picture.jpg">
              </a>
              <!-- Dropdown - User Information -->
              <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                <a class="dropdown-item" href="#">
                  <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                  Profile
                </a>
                <a class="dropdown-item" href="#">
                  <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                  Settings
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                  <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                  Logout
                </a>
              </div>
            </li>

          </ul>

        </nav>
        <!-- End of Topbar -->

        <!-- Begin Talents -->
        <div class="container-fluid">
          <!-- Page Heading -->
          <h1 class="h3 mb-2 text-gray-800">Roster of Talents</h1>
          <p class="mb-4">"Your Long-Term RAKET Partner"</p>

          <div class="card shadow mb-4">
            <div class="card-header py-3">
              <a class="btn btn-primary btn-icon-split" href="#" data-toggle="modal" data-target="#addTalentOrModelModal">
                <span class="icon text-white-50">
                  <i class="fas fa-plus-circle"></i>
                </span>
                <span class="text">Add Talent/Model</span>
              </a>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-bordered" id="tbl_talents" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>Name</th>
                      <th>Birth Date</th>
											<th>Hourly Rate</th>
											<th>Gender</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  
                  <tbody>
										<?php foreach($talents as $talent){?>
											<tr>
												<td><?php echo $talent->fullname;?></td>
												<td><?php echo $talent->birth_date;?></td>
												<td><?php echo $talent->hourly_rate;?></td>
												<td><?php echo $talent->gender;?></td>
												<td>
													<a href="#" data-toggle="modal" data-id="<?php echo $talent->talent_id;?>" data-target="#viewOrEditTalentOrModelModal" class="btnViewOrEditTalent btn btn-success btn-icon-split">
														<span class="icon text-white-50">
															<i class="fas fa-edit"></i>
														</span>
														<span class="text">View or Edit</span>
													</a>

													<a id="btnAddTalentResources" href="#" data-toggle="modal" data-id="<?php echo $talent->talent_id;?>" data-target="#updateTalentResourcesModal" class="btn btn-info btn-icon-split">
														<span class="icon text-white-50">
															<i class="fas fa-plus-circle"></i>
														</span>
														<span class="text">Add Resources</span>
													</a>
												</td>
											</tr> 
                     <?php }?>
                  </tbody>

                  <tfoot>
                    <tr>
                      <th>Name</th>
                      <th>Birth Date</th>
											<th>Hourly Rate</th>
											<th>Gender</th>
                      <th>Actions</th>
                    </tr>
                  </tfoot>
                </table>
              </div>
            </div>
          </div>
        </div>
        <!-- End Talents -->

				<!-- Begin Clients -->
        <div class="container-fluid">
          <h1 class="h3 mb-2 text-gray-800">Clients</h1>
					
          <div class="card shadow mb-4">
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-bordered" id="tbl_clients" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>Name</th>
											<th>Username</th>
                      <th>Email</th>
											<th>Type</th>
											<th>Status</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  
                  <tbody>
										<?php foreach($clients as $client){?>
											<tr>
												<td><?php echo $client->fullname;?></td>
												<td><?php echo $client->username;?></td>
												<td><?php echo $client->email;?></td>
												<td><?php echo $client->role_name;?></td>
												<td><?php echo $client->status_flag;?></td>
												<td>
													<a href="#" data-toggle="modal" data-id="<?php echo $client->user_id;?>" data-target="#checkRequirementsModal" class="btnCheckRequirements btn btn-success btn-icon-split">
														<span class="icon text-white-50">
															<i class="fas fa-edit"></i>
														</span>
														<span class="text">Check Requirements</span>
													</a>
												</td>
											</tr> 
                     <?php }?>
                  </tbody>

                  <tfoot>
                    <tr>
											<th>Name</th>
											<th>Username</th>
                      <th>Email</th>
											<th>Type</th>
											<th>Status</th>
                      <th>Actions</th>
                    </tr>
                  </tfoot>
                </table>
              </div>
            </div>
          </div>
        </div>
        <!-- End Clients -->

				<!-- Begin Applicants -->
        <div class="container-fluid">
          <h1 class="h3 mb-2 text-gray-800">Applicants</h1>
					
          <div class="card shadow mb-4">
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-bordered" id="tbl_applicants" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>Name</th>
                      <th>Email</th>
											<th>Contact Number</th>
											<th>Status</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  
                  <tbody>
										<?php foreach($applicants as $applicant){?>
											<tr>
												<td><?php echo $applicant->fullname;?></td>
												<td><?php echo $applicant->email;?></td>
												<td><?php echo $applicant->contact_number;?></td>
												<td><?php echo $applicant->status_flag;?></td>
												<td>
													<a href="#" class="btn btn-success btn-icon-split">
														<span class="icon text-white-50">
															<i class="fas fa-edit"></i>
														</span>
														<span class="text">Check Requirements</span>
													</a>
												</td>
											</tr> 
                     <?php }?>
                  </tbody>

                  <tfoot>
                    <tr>
											<th>Name</th>
                      <th>Email</th>
											<th>Contact Number</th>
											<th>Status</th>
                      <th>Actions</th>
                    </tr>
                  </tfoot>
                </table>
              </div>
            </div>
          </div>
        </div>
        <!-- End Applicants -->

      </div>
      <!-- End of Main Content -->

      <!-- Footer -->
      <footer class="sticky-footer bg-white">
        <div class="container my-auto">
          <div class="copyright text-center my-auto">
            <span>Copyright &copy; Hire Us <?php echo date("Y"); ?></span>
          </div>
        </div>
      </footer>
      <!-- End of Footer -->

    </div>
    <!-- End of Content Wrapper -->

  </div>
  <!-- End of Page Wrapper -->

  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

  <!-- Logout Modal-->
  <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
        <div class="modal-footer">
          <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
          <a class="btn btn-primary" href="<?php echo base_url(). 'login/user_logout'; ?>">Logout</a>
        </div>
      </div>
    </div>
  </div>

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
									<label for="cmbProvince">Region</label>
									<select name="region" id="cmbRegion" class="form-control" required>
										<option disabled="disabled" selected="selected">Choose Region</option>
										<?php foreach($param_regions as $region){?>
											<option value="<?php echo $region->regCode;?>"><?php echo $region->region_name;?></option>
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
          </div>
          
          <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
            <button class="btn btn-primary" type="submit">Submit</button>
          </div>
        </form>
      </div>
    </div>
  </div>

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
								<div class="col-xs-4" style="margin-left: 10px; margin-right: 10px;">
									<label for="inputHeight">Height <b>(in inches)</b></label>
                  <input type="text" class="form-control" id="inputHeight" name="talent_height" placeholder="Enter height in inches" required>
                </div>

                <div class="col-xs-4">
									<label for="inputBirthdate">Birth Date</label><br>
                  <input type="text" class="form-control" id="inputBirthdate" name="talent_birth_date" placeholder="Choose birthdate" required>
                </div>
								
                <div class="col-sm-4">
									<label for="inputHourlyRate">Rate per hour</label>
                  <input type="text" class="form-control" id="inputHourlyRate" name="talent_hourly_rate" placeholder="Enter hourly rate" required>
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
									<label for="cmbProvince">Region</label>
									<select name="region" id="cmbRegion" class="form-control" required>
										<option disabled="disabled" selected="selected">Choose Region</option>
										<?php foreach($param_regions as $region){?>
											<option value="<?php echo $region->regCode;?>"><?php echo $region->region_name;?></option>
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
                      <option value="<?php echo $category->category_id;?>"><?php echo $category->category_name;?></option>   
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
	
  <!-- Bootstrap core JavaScript-->
  <script src="<?php echo base_url(); ?>static/SBAdmin/vendor/jquery/jquery.min.js"></script>
	<script src="<?php echo base_url(); ?>static/SBAdmin/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/js/standalone/selectize.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-maskmoney/3.0.2/jquery.maskMoney.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="<?php echo base_url(); ?>static/SBAdmin/vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="<?php echo base_url(); ?>static/SBAdmin/js/sb-admin-2.min.js"></script>

  <!-- Page level plugins -->
  <script src="<?php echo base_url(); ?>static/SBAdmin/vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="<?php echo base_url(); ?>static/SBAdmin/vendor/datatables/dataTables.bootstrap4.min.js"></script>

  <!-- Page level custom scripts -->
	<script src="<?php echo base_url(); ?>static/SBAdmin/js/demo/datatables-demo.js"></script>
	<script src="https://parsleyjs.org/dist/parsley.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/promise-polyfill@8/dist/polyfill.min.js"></script>
	<script src="https://unpkg.com/filepond-polyfill"></script>
	<script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.js"></script>
	<script src="https://unpkg.com/filepond-plugin-image-exif-orientation/dist/filepond-plugin-image-exif-orientation.js"></script>
	<script src="https://unpkg.com/filepond-plugin-image-validate-size/dist/filepond-plugin-image-validate-size.js"></script>
	<script src="https://unpkg.com/filepond-plugin-file-validate-size/dist/filepond-plugin-file-validate-size.js"></script>
	<script src="https://unpkg.com/filepond-plugin-file-encode/dist/filepond-plugin-file-encode.js"></script>
	<script src="https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.js"></script>
	<script src="<?php echo base_url(); ?>static/js/libraries/filepond/filepond.js"></script>
	<script src="<?php echo base_url(); ?>static/js/libraries/jquery-confirm-v3.3.4/dist/jquery-confirm.min.js"></script>
	<script src="<?php echo base_url(); ?>static/js/home.js"></script>

</body>

</html>
