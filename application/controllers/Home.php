<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {
  public function __construct() {
		parent::__construct();
		
		$this->load->helper('url', 'form');
		$this->load->library('session');
    $this->load->database();
		$this->load->model('Home_model', 'home_model');
		$this->load->model('Client_individual_model', 'client_individual_model');
  }

  public function index() {
    $this->data['categories'] = $this->home_model->getAllCategories();
		$this->data['talents'] = $this->home_model->getAllTalents();
		$this->data['param_provinces'] = $this->client_individual_model->getAllProvinces();

    $this->load->view('home_page', $this->data);
	}
	
	public function addTalentOrModel(){
    $talents_fields =   array(
                'firstname'     	=> trim($this->input->post('firstname')),
                'middlename'     	=> trim($this->input->post('middlename')),
                'lastname'       	=> trim($this->input->post('lastname')),
                'email'       		=> trim($this->input->post('email')),
                'contact_number'  => trim($this->input->post('contact_number')),
                'gender'       		=> trim($this->input->post('gender')),
								'height'       		=> trim($this->input->post('height')),
								'birth_date'    	=> trim($this->input->post('birth_date')),
								'hourly_rate'    	=> trim($this->input->post('hourly_rate')),
								'vital_stats'    	=> trim($this->input->post('vital_stats')),
								'fb_followers'    => trim($this->input->post('fb_followers')),
								'instagram_followers'	=> trim($this->input->post('instagram_followers')),
								'description'    	=> trim($this->input->post('description')),
								'prev_clients'		=> trim($this->input->post('prev_clients')),
								'address'       	=> 
																		array(
																			'province' => trim($this->input->post('province')),
																			'city_muni' => trim($this->input->post('city_muni')),
																			'barangay' => trim($this->input->post('barangay')),
																			'bldg_village' => trim($this->input->post('bldg_village')),
																			'zip_code' => trim($this->input->post('zip_code'))
																		),
								'categories'      => $this->input->post('category'),
								'genre'      			=> trim($this->input->post('genre'))
              );
	
    $this->home_model->insertTalentOrModel($talents_fields);
	}

	public function uploadProfilePicOfTalent(){
		$msg = array();

		$config['upload_path'] = 'uploads/talents_or_models/';
		$config['allowed_types'] = 'jpg|png';
		$config['max_size'] = 5000;
		$config['max_width'] = 1500;
		$config['max_height'] = 1500;
		$config['file_name'] = md5(time() . rand());

		$this->load->library('upload', $config);

		if(!$this->upload->do_upload('profile_image')) {
			$msg = array(
								'status'	=> 'FAILED',
								'error'	 	=> $this->upload->display_errors()
							);
		}else{
			$msg = array(
								'status'	=> 'SUCCESS',
								'image_metadata' => $this->upload->data()
							);
		}

		print_r($msg['image_metadata']['file_name']);
		
		return $msg;
	}
}
