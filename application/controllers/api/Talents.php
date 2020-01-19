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
				'hourly_rate'    		=> trim($this->input->post('hourly_rate')),
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
			$config['max_size'] = 5000;
			$config['max_width'] = 1500;
			$config['max_height'] = 1500;
			$config['file_name'] = md5(time() . rand()) . '_' . mt_rand();

			$this->load->library('upload', $config);


			//upload talent profile picture
			if(!$this->upload->do_upload('talent_profile_img')) {
				// $msg = array(
				// 	'status'	=> 'FAILED',
				// 	'error'	 	=> $this->upload->display_errors()
				// );
				
				throw new Exception("There's an unexpected error in uploading talent gallery. Please contact administrator/developer.");
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
			if(!empty($_FILES['talent_gallery']['name'])){
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
						// $msg = array(
						// 	'status'	=> 'FAILED',
						// 	'error'	 	=> $this->upload->display_errors()
						// );
						
						throw new Exception("There's an unexpected error in uploading talent gallery. Please contact administrator/developer.");
					}
				}
			}else{
				throw new Exception("Talent gallery is required.");
			}
			
			//print "<pre>";
			//die(print_r($talents_fields));

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
			
			$talent_details 	= $this->talents_model->getTalentDetails($talent_id);
			
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
}
