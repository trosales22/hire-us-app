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
  }

  public function index() {
    $this->data['categories'] = $this->home_model->getAllCategories();
	$this->data['talents'] = $this->home_model->getAllTalents();
	$this->data['clients'] = $this->home_model->getAllClients();
	$this->data['applicants'] = $this->home_model->getAllApplicants();
	$this->data['param_regions'] = $this->clients_model->get_all_regions();
    $this->load->view('bookings_page', $this->data);
	}
	
	public function update_client_status(){
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
