<?php
header('Access-Control-Allow-Origin: *');
require APPPATH . 'libraries/REST_Controller.php';

class Client extends REST_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->database();
		$this->load->model('Client_individual_model', 'client_individual_model');
		$this->load->model('api/Talents_model', 'talents_model');
	}

	public function get_city_muni_by_province_code_get(){
		try{
			$success        		= 0;
			$province_code 	= $this->get('province_code');
			
			if(EMPTY($province_code))
        		throw new Exception("Province Code is required.");

			$city_muni_list = $this->client_individual_model->getCityMuniByProvinceCode($province_code);
			
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

			$barangay_list = $this->client_individual_model->getBarangayByCityMuniCode($city_muni_code);
			
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

	public function add_to_temp_booking_list_post(){
		try{
			$success        = 0;
			$booking_params = array(
				'temp_talent_id' 			=> trim($this->post('temp_talent_id')),
				'temp_client_id' 			=> trim($this->post('temp_client_id')),
				'temp_booking_date' 		=> trim($this->post('temp_booking_date')),
				'temp_booking_time' 		=> trim($this->post('temp_booking_time')),
				'temp_total_amount' 		=> trim($this->post('temp_total_amount')),
				'temp_status' 				=> trim($this->post('temp_status')),
				'temp_payment_option' 		=> trim($this->post('temp_payment_option'))
			);

			if(EMPTY($booking_params['temp_client_id']))
				throw new Exception("Client ID is required.");
				
			if(EMPTY($booking_params['temp_talent_id']))
				throw new Exception("Talent ID is required.");

			if(EMPTY($booking_params['temp_booking_date']))
				throw new Exception("Preferred Date is required.");
			
			if(EMPTY($booking_params['temp_booking_time']))
				throw new Exception("Preferred Time is required.");
				
			if(EMPTY($booking_params['temp_total_amount']))
				throw new Exception("Total Amount is required.");
				
			//will soon add validation if client_id & talent_id is existing
			$this->client_individual_model->add_to_temp_booking_list($booking_params);
			$success  = 1;
		}catch (Exception $e){
			$msg = $e->getMessage();
		}

		if($success == 1){
			$response = array(
				'status' 	=> 'OK',
				'msg'		=> 'Added to temporary booking list!'
			);
		}else{
			$response = [
				'msg'       => $msg,
				'flag'      => $success
			];
		}

		$this->response($response);
	}

	public function add_to_client_booking_list_post(){
		try{
			$success        = 0;
			$client_booking_params = array(
				'talent_id' 		=> trim($this->post('talent_id')),
				'client_id' 		=> trim($this->post('client_id')),
				'preferred_date' 	=> trim($this->post('preferred_date')),
				'preferred_time' 	=> trim($this->post('preferred_time')),
				'payment_option' 	=> trim($this->post('payment_option')),
				'total_amount' 		=> trim($this->post('total_amount'))
				
			);

			if(EMPTY($client_booking_params['client_id']))
				throw new Exception("Client ID is required.");
				
			if(EMPTY($client_booking_params['talent_id']))
				throw new Exception("Talent ID is required.");

			if(EMPTY($client_booking_params['preferred_date']))
				throw new Exception("Preferred Date is required.");
			
			if(EMPTY($client_booking_params['preferred_time']))
				throw new Exception("Preferred Time is required.");
				
			if(EMPTY($client_booking_params['total_amount']))
				throw new Exception("Total Amount is required.");
			
			//will soon add validation if client_id & talent_id is existing
			$this->client_individual_model->add_to_client_booking_list($client_booking_params);
			$success  = 1;
		}catch (Exception $e){
			$msg = $e->getMessage();
		}

		if($success == 1){
			$response = array(
				'status' 	=> 'OK',
				'msg'		=> 'Added to booking list!'
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

			$booking_list = $this->client_individual_model->get_booking_list_by_client_id($client_id);
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
				
			$already_reserved_sched_list = $this->client_individual_model->get_already_reserved_schedule($talent_id);
			
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
