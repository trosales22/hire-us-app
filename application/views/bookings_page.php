<?php
	$session_data = $this->session->userdata('logged_in');
	$session_username = $session_data['username'];

	if (!$session_data) {
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

  <title>Hire Us - Bookings</title>

  <!-- Custom fonts for this template -->
  <link href="<?php echo base_url(); ?>static/SBAdmin/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
  <link href="<?php echo base_url(); ?>static/SBAdmin/css/sb-admin-2.min.css" rel="stylesheet">
	<link href="<?php echo base_url(); ?>static/SBAdmin/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
	
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.bootstrap3.css">
	<link href="<?php echo base_url(); ?>static/css/parsley.css" rel="stylesheet">
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

		<?php include 'pages/sidebar.php';?>

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

				<?php include 'pages/topbar.php';?>

				<!-- Bookings (Paid/Refunded) -->
        <div class="container-fluid">
					<h1 class="h3 mb-2 text-gray-800">Bookings</h1>
					<p class="mb-4">Bookings with <b>Paid / Refunded</b> status</p>
					
          <div class="card shadow mb-4">
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-bordered" id="tbl_paid_refunded_bookings" width="100%" cellspacing="0">
                  <thead>
										<tr>
                      <th>Date</th>
											<th>Talent Fullname</th>
											<th>Talent Category</th>
											<th>Client</th>
											<th>Payment Option</th>
											<th>Schedule</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  
                  <tbody>
										
                  </tbody>

                  <tfoot>
										<tr>
                      <th>Date</th>
											<th>Talent Fullname</th>
											<th>Talent Category</th>
											<th>Client</th>
											<th>Payment Option</th>
											<th>Schedule</th>
                      <th>Action</th>
                    </tr>
                  </tfoot>
                </table>
              </div>
            </div>
          </div>
				</div>
				
				<!-- Bookings (Pending) -->
        <div class="container-fluid">
					<h1 class="h3 mb-2 text-gray-800">Bookings</h1>
					<p class="mb-4">Bookings with <b>Pending</b> status</p>
					
          <div class="card shadow mb-4">
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-bordered" id="tbl_pending_bookings" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>Date</th>
											<th>Talent Fullname</th>
											<th>Talent Category</th>
											<th>Client</th>
											<th>Payment Option</th>
											<th>Schedule</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  
                  <tbody>
										<?php foreach($pending_bookings as $pending_booking){?>
											<tr>
												<td><?php echo $pending_booking->created_date;?></td>
												<td><?php echo $pending_booking->temp_talent_id->firstname . ' ' . $pending_booking->temp_talent_id->lastname; ?></td>
												<td><?php echo $pending_booking->temp_talent_id->category_names;?></td>
												<td><?php echo $pending_booking->temp_client_id->fullname . ' (' . $pending_booking->temp_client_id->role_name . ')';?></td>
												<td><?php echo $pending_booking->temp_payment_option;?></td>
												<td><?php echo $pending_booking->temp_booking_date . ' ' . $pending_booking->temp_booking_time;?></td>
												<td>
													<a href="#" class="btn btn-success btn-icon-split">
														<span class="icon text-white-50">
															<i class="fas fa-check"></i>
														</span>
														<span class="text">Update Status</span>
													</a>
												</td>
											</tr> 
                     <?php }?>
                  </tbody>

                  <tfoot>
										<tr>
                      <th>Date</th>
											<th>Talent Fullname</th>
											<th>Talent Category</th>
											<th>Client</th>
											<th>Payment Option</th>
											<th>Schedule</th>
                      <th>Action</th>
                    </tr>
                  </tfoot>
                </table>
              </div>
            </div>
          </div>
        </div>

      </div>
      <!-- End of Main Content -->

      <?php include 'pages/footer.php';?>

    </div>
    <!-- End of Content Wrapper -->

  </div>
  <!-- End of Page Wrapper -->

  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

  <?php include 'pages/modals/logout.php';?>
	
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
	<script src="<?php echo base_url(); ?>static/js/libraries/jquery-confirm-v3.3.4/dist/jquery-confirm.min.js"></script>
	<script src="<?php echo base_url(); ?>static/js/bookings.js"></script>

</body>

</html>
