<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bookings extends CI_Controller {
	public function __construct() {
		parent::__construct();

		$this->load->helper('url', 'form');
		$this->load->library('session');
		$this->load->database();
		$this->load->model('Home_model', 'home_model');
		$this->load->model('Client_individual_model', 'client_individual_model');
		$this->load->model('api/Clients_model', 'clients_model');
		$this->load->model('api/Talents_model', 'talents_model');
		$this->load->model('Bookings_model', 'bookings_model');
  	}
	
  	public function index() {
		$this->_get_pending_bookings();
		$this->load->view('bookings_page', $this->data);
	}
	
	private function _get_pending_bookings(){
		try{
			$success        		= 0;
			$pending_booking_list = $this->bookings_model->get_pending_bookings();

			foreach($pending_booking_list as $pending_booking){
				$talent_details 	= $this->talents_model->getTalentDetails($pending_booking->temp_talent_id);
				$client_details		= $this->home_model->getAllClients($pending_booking->temp_client_id);

				$pending_booking->temp_talent_id = $talent_details[0];
				$pending_booking->temp_client_id = $client_details[0];
			}
			
			$success  = 1;
		}catch (Exception $e){
			$msg = $e->getMessage();      
		}

		if($success == 1){
			$response = [
			  'pending_booking_list' => $pending_booking_list
			];
		}else{
			$response = [
				'msg'       => $msg,
				'flag'      => $success
			];
		}

		// print "<pre>";
		// die(print_r($response['pending_booking_list']));
		
		$this->data['pending_bookings'] = $response['pending_booking_list'];
	}

	public function update_booking_status(){
		try{
			$success       	= 0;
			$client_id 		= trim($this->input->post('checkReq_clientId'));
			$active_flag 	= trim($this->input->post('client_status'));
			
			if(EMPTY($client_id))
				throw new Exception("Client ID is required.");

			if(EMPTY($active_flag))
				throw new Exception("Status is required.");
			
			$client_fields =   array(
				'user_id'     	=> $client_id,
				'active_flag'	=> $active_flag
			);
		
			$this->home_model->update_client_status($client_fields);
			
			$success  = 1;
		}catch (Exception $e){
			$msg = $e->getMessage();      
		}
		
		if($success == 1){
			$response = [
				'msg'       => 'Successfully updated client status!',
				'flag'      => $success
			];
		}else{
			$response = [
				'msg'       => $msg,
				'flag'      => $success
			];
		}

		echo json_encode($response);
	}
}
