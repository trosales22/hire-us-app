<?php
header('Access-Control-Allow-Origin: *');
require APPPATH . 'libraries/REST_Controller.php';

class Client extends REST_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->database();
		$this->load->model('api/Clients_model', 'clients_model');
		$this->load->model('Bookings_model', 'bookings_model');
		$this->load->model('Home_model', 'home_model');
		$this->load->model('api/Talents_model', 'talents_model');
	}

	public function get_client_requirements_get(){
		try{
			$success       	= 0;
			$client_id 	= $this->get('client_id');
			
			if(EMPTY($client_id))
				throw new Exception("Client ID is required.");
			
			$requirements = $this->home_model->get_requirements_of_client($client_id);
			
			$success  = 1;
		}catch (Exception $e){
			$msg = $e->getMessage();      
		}

		if($success == 1){
			$response = [
			  'requirements' => $requirements
			];
		}else{
			$response = [
				'msg'       => $msg,
				'flag'      => $success
			];
		}
		
		$this->response($response);
	}
	
	public function get_all_regions_get(){
		try{
			$success        = 0;
			$region_list = $this->clients_model->get_all_regions();
			
			$success  = 1;
		}catch (Exception $e){
			$msg = $e->getMessage();      
		}

		if($success == 1){
			$response = [
			  'region_list' => $region_list
			];
		}else{
			$response = [
				'msg'       => $msg,
				'flag'      => $success
			];
		}
		
		$this->response($response);
	}

	public function get_all_provinces_by_region_code_get(){
		try{
			$success       	= 0;
			$region_code 	= $this->get('region_code');
			
			if(EMPTY($region_code))
				throw new Exception("Region Code is required.");
				
			$provinces_list = $this->clients_model->get_all_provinces($region_code);
			
			$success  = 1;
		}catch (Exception $e){
			$msg = $e->getMessage();      
		}

		if($success == 1){
			$response = [
			  'provinces_list' => $provinces_list
			];
		}else{
			$response = [
				'msg'       => $msg,
				'flag'      => $success
			];
		}
	  
		$this->response($response);
	}

	public function get_city_muni_by_province_code_get(){
		try{
			$success        		= 0;
			$province_code 	= $this->get('province_code');
			
			if(EMPTY($province_code))
        		throw new Exception("Province Code is required.");

			$city_muni_list = $this->clients_model->get_city_muni_by_province_code($province_code);
			
			$success  = 1;
		}catch (Exception $e){
			$msg = $e->getMessage();      
		}

		if($success == 1){
			$response = [
			  'city_muni_list' => $city_muni_list
			];
		}else{
			$response = [
				'msg'       => $msg,
				'flag'      => $success
			];
		}
	  
		$this->response($response);
	}

	public function get_barangay_by_city_muni_code_get(){
		try{
			$success        		= 0;
			$city_muni_code 	= $this->get('city_muni_code');
			
			if(EMPTY($city_muni_code))
        		throw new Exception("City/Municipality Code is required.");

			$barangay_list = $this->clients_model->get_barangay_by_city_muni_code($city_muni_code);
			
			$success  = 1;
		}catch (Exception $e){
			$msg = $e->getMessage();      
		}

		if($success == 1){
			$response = [
			  'barangay_list' => $barangay_list
			];
		}else{
			$response = [
				'msg'       => $msg,
				'flag'      => $success
			];
		}
	  
		$this->response($response);
	}
	
	public function add_to_booking_list_post(){
		try{
			$success = 0;
			$client_booking_params = array(
				'booking_generated_id'		=> trim($this->post('booking_generated_id')),
				'talent_id' 				=> trim($this->post('talent_id')),
				'client_id' 				=> trim($this->post('client_id')),
				'booking_event_title' 		=> trim($this->post('booking_event_title')),
				'booking_talent_fee' 		=> trim($this->post('booking_talent_fee')),
				'booking_venue_location' 	=> trim($this->post('booking_venue_location')),
				'booking_date' 				=> trim($this->post('booking_date')),
				'booking_time' 				=> trim($this->post('booking_time')),
				'booking_other_details' 	=> trim($this->post('booking_other_details'))
			);
			
			if(EMPTY($client_booking_params['booking_generated_id']))
				throw new Exception("Booking Generated ID is required.");

			if(EMPTY($client_booking_params['client_id']))
				throw new Exception("Client ID is required.");
				
			if(EMPTY($client_booking_params['talent_id']))
				throw new Exception("Talent ID is required.");

			if(EMPTY($client_booking_params['booking_event_title']))
				throw new Exception("Booking event title is required.");

			if(EMPTY($client_booking_params['booking_talent_fee']))
				throw new Exception("Booking talent fee is required.");

			if(EMPTY($client_booking_params['booking_venue_location']))
				throw new Exception("Booking venue/location is required.");

			if(EMPTY($client_booking_params['booking_date']))
				throw new Exception("Booking date is required.");
			
			if(EMPTY($client_booking_params['booking_time']))
				throw new Exception("Booking time is required.");

			$email_params = array(
				'talent_details' 	=> $this->talents_model->getTalentDetails($client_booking_params['talent_id'])[0],
				'client_details' 	=> $this->home_model->getAllClients($client_booking_params['client_id'])[0]
			);

			// print "<pre>";
			// die(print_r($email_params));
			
			//will soon add validation if client_id & talent_id is existing
			$this->bookings_model->add_to_booking_list($client_booking_params, $email_params);
			$success  = 1;
		}catch (Exception $e){
			$msg = $e->getMessage();
		}

		if($success == 1){
			$response = array(
				'status' 	=> 'OK',
				'msg'		=> 'Your offer was successfully send to the chosen talent/s. Please check your email for more info. Thank you.'
			);
		}else{
			$response = [
				'msg'       => $msg,
				'flag'      => $success
			];
		}

		$this->response($response);
	}

	public function get_booking_list_by_client_id_get(){
		try{
			$success        		= 0;
			$client_id 	= $this->get('client_id');
			
			if(EMPTY($client_id))
        		throw new Exception("Client ID is required.");
			
			$booking_list = $this->clients_model->get_booking_list_by_client_id($client_id);
			foreach($booking_list as $booking){
				$talent_details 	= $this->talents_model->getTalentDetails($booking->talent_id);
				$booking->talent_id = $talent_details[0];
			}
			
			$success  = 1;
		}catch (Exception $e){
			$msg = $e->getMessage();      
		}

		if($success == 1){
			$response = [
			  'booking_list' => $booking_list
			];
		}else{
			$response = [
				'msg'       => $msg,
				'flag'      => $success
			];
		}
	  
		$this->response($response);
	}

	public function get_already_reserved_schedule_get(){
		try{
			$success    = 0;
			$talent_id 	= $this->get('talent_id');
			
			if(EMPTY($talent_id))
				throw new Exception("Talent ID is required.");
				
			$already_reserved_sched_list = $this->clients_model->get_already_reserved_schedule($talent_id);
			
			$success  = 1;
		}catch (Exception $e){
			$msg = $e->getMessage();      
		}

		if($success == 1){
			$response = [
			  'already_reserved_sched_list' => $already_reserved_sched_list
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
