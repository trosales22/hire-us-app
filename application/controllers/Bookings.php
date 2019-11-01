<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bookings extends CI_Controller {
	public function __construct() {
		parent::__construct();

		$this->load->helper('url', 'form');
		$this->load->library('session');
		$this->load->database();
		$this->load->model('Home_model', 'home_model');
		$this->load->model('api/Talents_model', 'talents_model');
		$this->load->model('Bookings_model', 'bookings_model');
  	}
	
  	public function index() {
		$this->_get_pending_bookings();
		$this->_get_paid_bookings();
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

	private function _get_paid_bookings(){
		try{
			$success        		= 0;
			$paid_booking_list = $this->bookings_model->get_paid_bookings();

			foreach($paid_booking_list as $paid_booking){
				$talent_details 	= $this->talents_model->getTalentDetails($paid_booking->talent_id);
				$client_details		= $this->home_model->getAllClients($paid_booking->client_id);

				$paid_booking->talent_id = $talent_details[0];
				$paid_booking->client_id = $client_details[0];
			}
			
			$success  = 1;
		}catch (Exception $e){
			$msg = $e->getMessage();      
		}

		if($success == 1){
			$response = [
			  'paid_booking_list' => $paid_booking_list
			];
		}else{
			$response = [
				'msg'       => $msg,
				'flag'      => $success
			];
		}

		// print "<pre>";
		// die(print_r($response['paid_booking_list']));
		
		$this->data['paid_bookings'] = $response['paid_booking_list'];
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

	public function approve_booking(){
		try{
			$success       	= 0;
			$booking_id 		= trim($this->input->get('booking_id'));
			
			if(EMPTY($booking_id))
				throw new Exception("Booking ID is required.");

			$booking_details = $this->bookings_model->get_pending_bookings($booking_id);
		
			$client_booking_list_params = array(
				'client_id'			=> $booking_details[0]->temp_client_id,
				'talent_id'			=> $booking_details[0]->temp_talent_id,
				'preferred_date'	=> $booking_details[0]->temp_booking_date,
				'preferred_time'	=> $booking_details[0]->temp_booking_time,
				'preferred_venue'	=> $booking_details[0]->temp_booking_venue,
				'payment_option'	=> $booking_details[0]->temp_payment_option,
				'total_amount'		=> $booking_details[0]->temp_total_amount,
			);

			$email_params = array(
				'talent_details' 	=> $this->talents_model->getTalentDetails($client_booking_params['talent_id'])[0],
				'client_details' 	=> $this->home_model->getAllClients($client_booking_params['client_id'])[0]
			);
			
			$this->bookings_model->approve_booking($booking_id, $client_booking_list_params, $email_params);

			$success  = 1;
		}catch (Exception $e){
			$msg = $e->getMessage();      
		}

		if($success == 1){
			$response = [
				'msg'       => 'Booking successfully approved!',
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

	public function decline_booking(){
		try{
			$success       	= 0;
			$booking_id 		= trim($this->input->get('booking_id'));
			
			if(EMPTY($booking_id))
				throw new Exception("Booking ID is required.");
			
			$this->bookings_model->decline_booking($booking_id);

			$success  = 1;
		}catch (Exception $e){
			$msg = $e->getMessage();      
		}

		if($success == 1){
			$response = [
				'msg'       => 'Booking successfully declined!',
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
