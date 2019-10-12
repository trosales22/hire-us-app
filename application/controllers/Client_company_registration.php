<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Client_company_registration extends CI_Controller {

	public function __construct() {
		parent::__construct();
		
		$this->load->helper('url', 'form');
		$this->load->library('session');
		$this->load->database();
		$this->load->model('api/Clients_model', 'clients_model');
		$this->load->model('Client_company_model', 'client_company_model');
	}

  	public function index() {
		$this->data['param_valid_ids'] = $this->clients_model->get_all_valid_ids();
		$this->data['param_regions'] = $this->clients_model->get_all_regions();
		$this->load->view('registration_client_company_page', $this->data);
	}

	public function add_company_client(){
		$client_fields =   array(
			'firstname'     	=> trim($this->input->post('firstname')),
			'lastname'       	=> trim($this->input->post('lastname')),
			'email'       		=> trim($this->input->post('email')),
			'contact_number'  	=> trim($this->input->post('phone')),
			'username'       	=> trim($this->input->post('username')),
			'password'       	=> trim($this->input->post('password')),
			'gender'       		=> trim($this->input->post('gender')),
			'birth_date'    	=> trim($this->input->post('birth_date')),
			'address'       	=> array(
										'province' => trim($this->input->post('province')),
										'city_muni' => trim($this->input->post('city_muni')),
										'barangay' => trim($this->input->post('barangay')),
										'bldg_village' => trim($this->input->post('bldg_village')),
										'zip_code' => trim($this->input->post('zip_code'))
									)
		);

		$this->client_company_model->add_company_client($client_fields);
	}
}
