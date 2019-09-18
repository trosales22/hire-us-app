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
		$this->data['gallery'] = $this->home_model->getTalentGallery(1);
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
		$talent_id = $this->input->post('talent_id');
		$res = $this->home_model->getTalentResourceCount($talent_id);
		$msg = array();
		
		if($res[0]->talent_res_count == 1){
			$msg = array(
				'status'	=> 'FAILED',
				'error_msg'	 	=> 'Already have an existing record.'
			);
		}else{
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

				$uploadData['talent_display_photo'] = $msg['image_metadata']['file_name'];
				$uploadData['talent_id'] = $talent_id;
				
				// Insert files data into the database
				$this->home_model->uploadTalentProfilePic($uploadData);
			}
		}

		return $msg;
	}
	
	public function uploadTalentGallery(){
		$msg = array();

		if(!empty($_FILES['talent_gallery']['name'])){
			$filesCount = count($_FILES['talent_gallery']['name']);

			for($i = 0; $i < $filesCount; $i++){
					$_FILES['file']['name']     = $_FILES['talent_gallery']['name'][$i];
					$_FILES['file']['type']     = $_FILES['talent_gallery']['type'][$i];
					$_FILES['file']['tmp_name'] = $_FILES['talent_gallery']['tmp_name'][$i];
					$_FILES['file']['error']     = $_FILES['talent_gallery']['error'][$i];
					$_FILES['file']['size']     = $_FILES['talent_gallery']['size'][$i];
					
					// File upload configuration
					$config['upload_path'] = 'uploads/talents_or_models/';
					$config['allowed_types'] = 'jpg|png';
					$config['max_size'] = 5000;
					$config['max_width'] = 1500;
					$config['max_height'] = 1500;
					$config['file_name'] = md5(time() . rand());

					// Load and initialize upload library
					$this->load->library('upload', $config);
					$this->upload->initialize($config);
					
					// Upload file to server
					if($this->upload->do_upload('file')){
							// Uploaded file data
							$fileData = $this->upload->data();
							$uploadData[$i]['file_name'] = $fileData['file_name'];
							$uploadData[$i]['talent_id'] = $this->input->post('talent_id');

							$msg = array(
								'status'	=> 'SUCCESS',
								'image_metadata' => $this->upload->data()
							);

					}else{
						$msg = array(
							'status'	=> 'FAILED',
							'error'	 	=> $this->upload->display_errors()
						);
					}
			}
			
			if(!empty($uploadData)){
					// Insert files data into the database
					$this->home_model->uploadTalentGallery($uploadData);
			}
		}else{
			$msg = array(
				'status'	=> 'FAILED',
				'error'	 	=> 'Empty file upload. Please try again!'
			);
		}

		return $msg;
	}
}
