<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Client_individual_registration extends CI_Controller {

	public function __construct() {
		parent::__construct();
		
		$this->load->helper('url', 'form');
		$this->load->library('session');
	}

  	public function index() {
		$this->load->view('registration_client_individual_page');
	}
}
