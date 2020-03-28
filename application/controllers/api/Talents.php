<?php
header('Access-Control-Allow-Origin: *');
require APPPATH . 'libraries/REST_Controller.php';
     
class Talents extends REST_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->helper('url', 'form');
		$this->load->library('session');
		$this->load->database();
		$this->load->model('Home_model', 'home_model');
		$this->load->model('Bookings_model', 'bookings_model');
		$this->load->model('api/Talents_model', 'talents_model');
	}
	
	public function add_talent_post(){
		try{
			$success  = 0;
			$msg = array();
			$talent_profile_image_output = array();
			$talent_gallery_output = array();
			$session_data = $this->session->userdata('logged_in');
			
			$talents_fields =   array(
				'firstname'     		=> trim($this->input->post('firstname')),
				'middlename'     		=> trim($this->input->post('middlename')),
				'lastname'       		=> trim($this->input->post('lastname')),
				'screen_name'       	=> trim($this->input->post('screen_name')),
				'email'       			=> trim($this->input->post('email')),
				'contact_number'  		=> trim($this->input->post('contact_number')),
				'gender'       			=> trim($this->input->post('gender')),
				'height'       			=> trim($this->input->post('height')),
				'birth_date'    		=> trim($this->input->post('birth_date')),
				'vital_stats'    		=> trim($this->input->post('vital_stats')),
				'fb_followers'    		=> trim($this->input->post('fb_followers')),
				'instagram_followers' 	=> trim($this->input->post('instagram_followers')),
				'description'    		=> trim($this->input->post('description')),
				'prev_clients'			=> trim($this->input->post('prev_clients')),
				'address'       		=> array(
					'region'		=> trim($this->input->post('region')),
					'province' 		=> trim($this->input->post('province')),
					'city_muni' 	=> trim($this->input->post('city_muni')),
					'barangay' 		=> trim($this->input->post('barangay')),
					'bldg_village' 	=> trim($this->input->post('bldg_village')),
					'zip_code' 		=> trim($this->input->post('zip_code'))
				),
				'categories'      		=> $this->input->post('category'),
				'genre'      			=> trim($this->input->post('genre')),
				'created_by'       		=> $session_data['user_id']
			);

			if(EMPTY($talents_fields['firstname']))
				throw new Exception("Firstname is required.");

			if(EMPTY($talents_fields['middlename']))
				throw new Exception("Middle name is required.");

			if(EMPTY($talents_fields['lastname']))
				throw new Exception("Lastname is required.");

			if(EMPTY($talents_fields['email']))
				throw new Exception("Email address is required.");
			
			if(EMPTY($talents_fields['contact_number']))
				throw new Exception("Contact number is required.");

			if(EMPTY($talents_fields['gender']))
				throw new Exception("Gender is required.");

			if(EMPTY($talents_fields['height']))
				throw new Exception("Height is required.");

			if(EMPTY($talents_fields['birth_date']))
				throw new Exception("Birthdate is required.");
			
			if(EMPTY($talents_fields['prev_clients']))
				throw new Exception("Previous clients/experience is required.");

			//address
			if(EMPTY($talents_fields['address']['region']))
				throw new Exception("Region is required.");

			if(EMPTY($talents_fields['address']['province']))
				throw new Exception("Province is required.");

			if(EMPTY($talents_fields['address']['city_muni']))
				throw new Exception("City/Muni is required.");

			if(EMPTY($talents_fields['address']['barangay']))
				throw new Exception("Barangay is required.");

			if(EMPTY($talents_fields['address']['bldg_village']))
				throw new Exception("Bldg/Village is required.");

			if(EMPTY($talents_fields['address']['zip_code']))
				throw new Exception("ZIP Code is required.");

			$config['upload_path'] = 'uploads/talents_or_models/';
			$config['allowed_types'] = 'jpg|jpeg|png';
			$config['max_size'] = 5000; //set the maximum file size in kilobytes (5MB)
			$config['max_width'] = 1000;
			$config['max_height'] = 1000;
			$config['file_name'] = time() . '_' . rand(1000,9999);

			$this->load->library('upload', $config);


			//upload talent profile picture
			if(!$this->upload->do_upload('talent_profile_img')) {
				$msg = $this->upload->display_errors();			
				throw new Exception($msg);
			}else{
				$upload_img_output = array(
					'image_metadata' => $this->upload->data()
				);

				$talent_profile_image_output = array(
					'talent_profile_img'	=> $upload_img_output['image_metadata']['file_name']
				);

				$talents_fields['talent_profile_img'] = $talent_profile_image_output['talent_profile_img'];
			}

			if(EMPTY($talents_fields['talent_profile_img']))
				throw new Exception("Talent profile image is required.");
			
			
			//upload talent gallery
			if(!EMPTY($_FILES['talent_gallery']['name'])){
				$filesCount = count($_FILES['talent_gallery']['name']);
				
				for($i = 0; $i < $filesCount; $i++){
					$_FILES['file']['name']     = $_FILES['talent_gallery']['name'][$i];
					$_FILES['file']['type']     = $_FILES['talent_gallery']['type'][$i];
					$_FILES['file']['tmp_name'] = $_FILES['talent_gallery']['tmp_name'][$i];
					$_FILES['file']['error']     = $_FILES['talent_gallery']['error'][$i];
					$_FILES['file']['size']     = $_FILES['talent_gallery']['size'][$i];
					
					// File upload configuration
					$config['upload_path'] = 'uploads/talents_or_models/';
					$config['allowed_types'] = 'jpg|png';
					$config['max_size'] = 5000;
					$config['max_width'] = 1500;
					$config['max_height'] = 1500;
					$config['file_name'] = md5(time() . rand());
					
					// Load and initialize upload library
					$this->load->library('upload', $config);
					$this->upload->initialize($config);
					
					// Upload file to server
					if($this->upload->do_upload('file')){
						// Uploaded file data
						$fileData = $this->upload->data();
						$talent_gallery_output[$i]['file_name'] = $fileData['file_name'];
						
						$talents_fields['talent_gallery'] = $talent_gallery_output;
					}else{
						$msg = $this->upload->display_errors();			
						throw new Exception($msg);
					}
				}
			}

			$this->talents_model->add_talent($talents_fields);
			$success  = 1;
		}catch (Exception $e){
			$msg = $e->getMessage();      
		}

		if($success == 1){
			$response = [
				'msg'       => 'Talent was successfully added!',
				'flag'      => $success
			];
		}else{
			$response = [
				'msg'       => $msg,
				'flag'      => $success
			];
		}

		$this->response($response);
	}

	public function modify_talent_post(){
		try{
			$success  = 0;
			$msg = array();
			$talent_profile_image_output = array();
			$talent_gallery_output = array();
			$session_data = $this->session->userdata('logged_in');
			$does_error_occur = FALSE;
			$validation_error_msg = '';
			
			$talents_fields =   array(
				'talent_id'				=> trim($this->input->post('talent_id')),
				'firstname'     		=> trim($this->input->post('talent_firstname')),
				'middlename'     		=> trim($this->input->post('talent_middlename')),
				'lastname'       		=> trim($this->input->post('talent_lastname')),
				'screen_name'       	=> trim($this->input->post('talent_screen_name')),
				'email'       			=> trim($this->input->post('talent_email')),
				'contact_number'  		=> trim($this->input->post('talent_contact_number')),
				'gender'       			=> trim($this->input->post('talent_gender')),
				'height'       			=> trim($this->input->post('talent_height')),
				'birth_date'    		=> trim($this->input->post('talent_birth_date')),
				'vital_stats'    		=> trim($this->input->post('talent_vital_stats')),
				'fb_followers'    		=> trim($this->input->post('talent_fb_followers')),
				'instagram_followers' 	=> trim($this->input->post('talent_instagram_followers')),
				'description'    		=> trim($this->input->post('talent_description')),
				'prev_clients'			=> trim($this->input->post('talent_prev_clients')),
				'address'       		=> array(
					'region'		=> trim($this->input->post('region')),
					'province' 		=> trim($this->input->post('province')),
					'city_muni' 	=> trim($this->input->post('city_muni')),
					'barangay' 		=> trim($this->input->post('barangay')),
					'bldg_village' 	=> trim($this->input->post('talent_bldg_village')),
					'zip_code' 		=> trim($this->input->post('talent_zip_code'))
				),
				'categories'      		=> $this->input->post('category'),
				'genre'      			=> trim($this->input->post('talent_genre')),
				'created_by'       		=> $session_data['user_id'],
				'modified_date'			=> date("Y-m-d H:i:s"),
				'modified_by'       	=> $session_data['user_id']
			);
			
			//start validation
			if(EMPTY($talents_fields['talent_id'])){
				$does_error_occur = TRUE;
				$validation_error_msg .= 'Talent ID is required.<br/>';
			}

			if(EMPTY($talents_fields['firstname'])){
				$does_error_occur = TRUE;
				$validation_error_msg .= 'First name is required.<br/>';
			}

			if(EMPTY($talents_fields['middlename'])){
				$does_error_occur = TRUE;
				$validation_error_msg .= 'Middlename is required.<br/>';
			}
			
			if(EMPTY($talents_fields['lastname'])){
				$does_error_occur = TRUE;
				$validation_error_msg .= 'Lastname is required.<br/>';
			}

			if(EMPTY($talents_fields['email'])){
				$does_error_occur = TRUE;
				$validation_error_msg .= 'Email address is required.<br/>';
			}
			
			if(EMPTY($talents_fields['contact_number'])){
				$does_error_occur = TRUE;
				$validation_error_msg .= 'Contact number is required.<br/>';
			}

			if(EMPTY($talents_fields['gender'])){
				$does_error_occur = TRUE;
				$validation_error_msg .= 'Gender is required.<br/>';
			}

			if(EMPTY($talents_fields['height'])){
				$does_error_occur = TRUE;
				$validation_error_msg .= 'Height is required.<br/>';
			}

			if(EMPTY($talents_fields['birth_date'])){
				$does_error_occur = TRUE;
				$validation_error_msg .= 'Birthdate is required.<br/>';
			}
			
			if(EMPTY($talents_fields['prev_clients'])){
				$does_error_occur = TRUE;
				$validation_error_msg .= 'Previous clients/experience is required.<br/>';
			}

			//address
			if(EMPTY($talents_fields['address']['region'])){
				$does_error_occur = TRUE;
				$validation_error_msg .= 'Region is required.<br/>';
			}

			if(EMPTY($talents_fields['address']['province'])){
				$does_error_occur = TRUE;
				$validation_error_msg .= 'Province is required.<br/>';
			}

			if(EMPTY($talents_fields['address']['city_muni'])){
				$does_error_occur = TRUE;
				$validation_error_msg .= 'City/Municipality is required.<br/>';
			}

			if(EMPTY($talents_fields['address']['barangay'])){
				$does_error_occur = TRUE;
				$validation_error_msg .= 'Barangay is required.<br/>';
			}

			if(EMPTY($talents_fields['address']['bldg_village'])){
				$does_error_occur = TRUE;
				$validation_error_msg .= 'Bldg/Village is required.<br/>';
			}

			if(EMPTY($talents_fields['address']['zip_code'])){
				$does_error_occur = TRUE;
				$validation_error_msg .= 'ZIP/Postal Code is required.<br/>';
			}

			$detect_if_talent_profile_pic_exist = $this->talents_model->detect_if_talent_profile_pic_exist($talents_fields['talent_id']);
			//die(print_r(EMPTY($detect_if_talent_profile_pic_exist)));
			
			$latest_talent_profile_pic = '';
			$latest_talent_profile_pic_raw = '';

			if(!EMPTY($detect_if_talent_profile_pic_exist)){
				$latest_talent_profile_pic = $detect_if_talent_profile_pic_exist[0]->talent_display_photo == 'NO IMAGE' ? '' : $detect_if_talent_profile_pic_exist[0]->talent_display_photo;
				$latest_talent_profile_pic_raw = $detect_if_talent_profile_pic_exist[0]->talent_display_photo_raw == 'NO IMAGE' ? '' : $detect_if_talent_profile_pic_exist[0]->talent_display_photo_raw;
				//die(print_r($latest_talent_profile_pic_raw));
			}
			
			//validate file upload
			if (EMPTY($_FILES['talent_profile_img']['name'])){
				if(EMPTY($latest_talent_profile_pic)){
					$does_error_occur = TRUE;
					$validation_error_msg .= 'Talent profile picture is required.<br/>';
				}else{
					$talents_fields['talent_profile_img'] = $latest_talent_profile_pic_raw;
				}
			}else{
				$config['upload_path'] = 'uploads/talents_or_models/';
				$config['allowed_types'] = 'jpg|jpeg|png';
				$config['max_size'] = 5000; //set the maximum file size in kilobytes (5MB)
				$config['max_width'] = 1000;
				$config['max_height'] = 1000;
				$config['file_name'] = time() . '_' . rand(1000,9999);
				$this->load->library('upload', $config);

				if(!$this->upload->do_upload('talent_profile_img')) {
					$msg = $this->upload->display_errors();
					$does_error_occur = TRUE;
					$validation_error_msg .= $msg;
				}else{
					if(!EMPTY($latest_news_display_photo_raw)){
						unlink("uploads/talents_or_models/" . $latest_talent_profile_pic_raw);
					}
					
					$upload_img_output = array(
						'image_metadata' => $this->upload->data()
					);
					
					$img_output = array(
						'talent_profile_img'	=> $upload_img_output['image_metadata']['file_name']
					);
					
					$talents_fields['talent_profile_img'] = $img_output['talent_profile_img'];

					if(EMPTY($talents_fields['talent_profile_img'])){
						$does_error_occur = TRUE;
						$validation_error_msg .= 'Talent profile picture is required.<br/>';
					}
				}
			}

			if($does_error_occur){
				throw new Exception($validation_error_msg);
			}
			
			// print "<pre>";
			// die(print_r($talents_fields));
			
			$this->talents_model->modify_talent($talents_fields);
			$success  = 1;
		}catch (Exception $e){
			$msg = $e->getMessage();      
		}

		if($success == 1){
			$response = [
				'msg'       => 'Talent was successfully modified!',
				'flag'      => $success
			];
		}else{
			$response = [
				'msg'       => $msg,
				'flag'      => $success
			];
		}

		$this->response($response);
	}

	public function get_talent_categories_get(){
		try{
			$success       	= 0;			
			$talent_categories = $this->home_model->getAllCategories();
			$success  = 1;
		}catch (Exception $e){
			$msg = $e->getMessage();      
		}

		if($success == 1){
			$response = [
			  'talent_categories' => $talent_categories
			];
		}else{
			$response = [
				'msg'       => $msg,
				'flag'      => $success
			];
		}
		
		$this->response($response);	
	}

	public function get_talent_gallery_get(){
		try{
			$success       	= 0;
			$talent_id 	= $this->get('talent_id');
			
			if(EMPTY($talent_id))
				throw new Exception("Talent ID is required.");
			
			$talent_gallery = $this->home_model->getTalentGallery($talent_id);
			$success  = 1;
		}catch (Exception $e){
			$msg = $e->getMessage();      
		}

		if($success == 1){
			$response = [
			  'talent_gallery' => $talent_gallery
			];
		}else{
			$response = [
				'msg'       => $msg,
				'flag'      => $success
			];
		}
		
		$this->response($response);
	}
	
	public function get_all_talents_get(){
		try{
			$success        		= 0;

			$extra_filtering		= [
				'selected_categories'	=> $this->get('selected_categories')
			];
			
			$additional_filtering   = [
				'height_from'			=> $this->get('height_from'),
				'height_to'				=> $this->get('height_to'),
				'age_from'				=> $this->get('age_from'),
				'age_to'				=> $this->get('age_to'),
				'rate_per_hour_from'	=> $this->get('rate_per_hour_from'),
				'rate_per_hour_to'		=> $this->get('rate_per_hour_to'),
				'province_code'			=> $this->get('province_code'),
				'city_muni_code'		=> $this->get('city_muni_code'),
				'gender'				=> $this->get('gender'),
			];
			
			$talents_list   		= $this->talents_model->get_all_talents($extra_filtering, $additional_filtering);
			
			$success  = 1;
		}catch (Exception $e){
			$msg = $e->getMessage();      
		}

		if($success == 1){
			$response = [
			  'talents_list' => $talents_list
			];
		}else{
			$response = [
				'msg'       => $msg,
				'flag'      => $success
			];
		}
	  
		$this->response($response);
	}

	public function get_talent_details_get(){
		try{
			$success 					= 0;
			$talent_id      			= $this->get('talent_id');

			if(EMPTY($talent_id))
        		throw new Exception("Talent ID is required.");
			
			$talent_details 	= $this->talents_model->get_talent_details($talent_id);
			
			$success  = 1;
		}catch (Exception $e){
			$msg = $e->getMessage();      
		}

		if($success == 1){
			$response = $talent_details[0];
		}else{
			$response = [
				'msg'       => $msg,
				'flag'      => $success
			];
		}
	  
		$this->response($response);
	}

	public function get_all_talent_by_category_get(){
		try{
			$success 					= 0;
			$category_id      			= $this->get('category_id');

			if(EMPTY($category_id))
        		throw new Exception("Category ID is required.");
			
			$talents_per_category_list 	= $this->talents_model->get_all_talent_by_category($category_id);
			
			$success  = 1;
		}catch (Exception $e){
			$msg = $e->getMessage();      
		}

		if($success == 1){
			$response = [
				'talents_per_category_list' => $talents_per_category_list
			];
		}else{
			$response = [
				'msg'       => $msg,
				'flag'      => $success
			];
		}
	  
		$this->response($response);
	}

	public function get_all_client_booked_get(){
		try{
			$success 		= 0;
			$talent_id      = $this->get('talent_id');
			
			if(EMPTY($talent_id))
        		throw new Exception("Talent ID is required.");
			
			$clients_booked_list = $this->talents_model->get_all_client_booked($talent_id);
			foreach($clients_booked_list as $booking){
				$client_details 	= $this->home_model->getAllClients($booking->client_id);
				$booking->client_id = $client_details[0];
				
				$talent_details 	= $this->talents_model->getTalentDetails($booking->talent_id);
				$booking->talent_id = $talent_details[0];
			}
			
			$success  = 1;
		}catch (Exception $e){
			$msg = $e->getMessage();      
		}

		if($success == 1){
			$response = [
			'clients_booked_list' => $clients_booked_list
			];
		}else{
			$response = [
				'msg'       => $msg,
				'flag'      => $success
			];
		}
	  
		$this->response($response);
	}

	public function add_talent_reviews_post(){
		try{
			$success  = 0;
			$msg = array();

			$client_reviews_params = array(
				'review_feedback'	=> trim($this->input->post('review_feedback')),
				'review_rating'		=> trim($this->input->post('review_rating')),
				'review_to'			=> trim($this->input->post('review_to')),
				'review_from'		=> trim($this->input->post('review_from'))
			);

			if(EMPTY($client_reviews_params['review_feedback']))
				throw new Exception("Review Feedback is required.");
			
			if(EMPTY($client_reviews_params['review_rating']))
				throw new Exception("Review Rating is required.");

			if(EMPTY($client_reviews_params['review_to']))
				throw new Exception("Review To is required.");
			
			if(EMPTY($client_reviews_params['review_from']))
				throw new Exception("Review From is required.");

			$this->talents_model->add_talent_reviews($client_reviews_params);
			$success  = 1;
		}catch (Exception $e){
			$msg = $e->getMessage();      
		}

		if($success == 1){
			$response = [
				'msg'       => 'Client reviews was successfully submitted!',
				'flag'      => $success
			];
		}else{
			$response = [
				'msg'       => $msg,
				'flag'      => $success
			];
		}

		$this->response($response);
	}

	public function get_talent_reviews_get(){
		try{
			$success = 0;
			$talent_id = $this->get('talent_id');

			if(EMPTY($talent_id))
				throw new Exception("Talent ID is required.");
				
			$client_reviews_list = $this->talents_model->get_talent_reviews($talent_id);
			
			foreach($client_reviews_list as $client_review){
				$client_details 			= $this->home_model->getAllClients($client_review->review_from);
				$client_review->review_from = $client_details[0];
			}
			
			$success  = 1;
		}catch (Exception $e){
			$msg = $e->getMessage();      
		}

		if($success == 1){
			$response = [
				'client_reviews_list' => $client_reviews_list
			];
		}else{
			$response = [
				'msg'       => $msg,
				'flag'      => $success
			];
		}
	  
		$this->response($response);
	}

	public function approve_booking_post(){
		try{
			$success       			= 0;
			$booking_generated_id 	= trim($this->input->post('booking_generated_id'));
			
			if(EMPTY($booking_generated_id))
				throw new Exception("Booking ID is required.");

			$booking_details = $this->bookings_model->get_booking_by_booking_generated_id($booking_generated_id);

			if(empty($booking_details)){
				throw new Exception("Booking not found. Please try again.");
			}
			
			$client_booking_list_params = array(
				'client_id'					=> $booking_details[0]->client_id,
				'talent_id'					=> $booking_details[0]->talent_id,
				'working_dates'				=> $booking_details[0]->booking_date,
				'working_hours'				=> $booking_details[0]->booking_time,
				'booking_venue_location'	=> $booking_details[0]->booking_venue_location,
				'booking_payment_option'	=> $booking_details[0]->booking_payment_option,
				'booking_talent_fee'		=> $booking_details[0]->booking_talent_fee,
			);

			$email_params = array(
				'talent_details' 	=> $this->talents_model->getTalentDetails($client_booking_list_params['talent_id'])[0],
				'client_details' 	=> $this->home_model->getAllClients($client_booking_list_params['client_id'])[0]
			);
			
			$this->bookings_model->approve_booking($booking_generated_id, $client_booking_list_params, $email_params);

			$success  = 1;
		}catch (Exception $e){
			$msg = $e->getMessage();      
		}

		if($success == 1){
			$response = [
				'msg'       => 'Booking has been approved!',
				'flag'      => $success
			];
		}else{
			$response = [
				'msg'       => $msg,
				'flag'      => $success
			];
		}

		$this->response($response);
	}

	public function decline_booking_post(){
		try{
			$success       				= 0;
			$booking_generated_id 		= trim($this->input->post('booking_generated_id'));
			$booking_decline_reason 	= trim($this->input->post('booking_decline_reason'));
			
			if(EMPTY($booking_generated_id))
				throw new Exception("Booking ID is required.");

			if(EMPTY($booking_decline_reason))
				throw new Exception("Booking Decline Reason is required.");

			$booking_details = $this->bookings_model->get_booking_by_booking_generated_id($booking_generated_id);
			
			if(empty($booking_details)){
				throw new Exception("Booking not found. Please try again.");
			}
			
			$this->bookings_model->decline_booking($booking_generated_id, $booking_decline_reason);

			$success  = 1;
		}catch (Exception $e){
			$msg = $e->getMessage();      
		}

		if($success == 1){
			$response = [
				'msg'       => 'Booking has been declined!',
				'flag'      => $success
			];
		}else{
			$response = [
				'msg'       => $msg,
				'flag'      => $success
			];
		}

		$this->response($response);
	}
}
