<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Client_individual_registration extends CI_Controller {

	public function __construct() {
		parent::__construct();
		
		$this->load->helper('url', 'form');
		$this->load->library('session');
		$this->load->database();
   		$this->load->model('Client_individual_model', 'client_individual_model');
	}

  	public function index() {
		$this->data['param_provinces'] = $this->client_individual_model->getAllProvinces();
		$this->load->view('registration_client_individual_page', $this->data);
	}
}
