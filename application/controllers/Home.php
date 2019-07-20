<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {
  public function __construct() {
		parent::__construct();
		
		$this->load->helper('url', 'form');
		$this->load->library('session');
    $this->load->database();
    $this->load->model('Home_model', 'home_model');
  }

  public function index() {
    $this->data['categories'] = $this->home_model->getAllCategories();
    $this->data['talents'] = $this->home_model->getAllTalents();

    $this->load->view('home_page', $this->data);
	}
	
	public function addTalentOrModel(){
    $talents_fields =   array(
                'firstname'     	=> $this->input->post('firstname'),
                'middlename'     	=> $this->input->post('middlename'),
                'lastname'       	=> $this->input->post('lastname'),
                'email'       		=> $this->input->post('email'),
                'contact_number'  => $this->input->post('contact_number'),
                'gender'       		=> $this->input->post('gender'),
                'height'       		=> $this->input->post('height'),
                'location'       	=> $this->input->post('location'),
                'birth_date'    	=> $this->input->post('birth_date'),
                'talent_fee'     	=> $this->input->post('talent_fee'),
                'talent_fee_type' => $this->input->post('talent_fee_type'),
                'category'      	=> $this->input->post('category') //change this into array
              );
		
    $this->home_model->insertTalentOrModel($talents_fields);
	}
}
