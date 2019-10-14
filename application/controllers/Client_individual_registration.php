<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Client_individual_registration extends CI_Controller {

	public function __construct() {
		parent::__construct();
		
		$this->load->helper('url', 'form');
		$this->load->library('session');
		$this->load->database();
		$this->load->model('api/Clients_model', 'clients_model');
   		$this->load->model('Client_individual_model', 'client_individual_model');
	}

  	public function index() {
		$this->data['param_valid_ids'] = $this->clients_model->get_all_valid_ids();
		$this->data['param_regions'] = $this->clients_model->get_all_regions();
		$this->load->view('registration_client_individual_page', $this->data);
	}
	
	public function add_individual_client(){
		$msg = array();
		$individual_government_issued_id_image_output = array();

		$config['upload_path'] = 'uploads/id_verification/';
		$config['allowed_types'] = 'jpg|jpeg|png';
		$config['max_size'] = 5000;
		$config['max_width'] = 1500;
		$config['max_height'] = 1500;
		$config['file_name'] = md5(time() . rand()) . '_' . mt_rand();

		$this->load->library('upload', $config);
		
		if(!$this->upload->do_upload('individual_government_issued_id_image')) {
			$msg = array(
				'status'	=> 'FAILED',
				'error'	 	=> $this->upload->display_errors()
			);
		}else{
			$output = array(
				'image_metadata' => $this->upload->data()
			);
			
			$individual_government_issued_id_image_output = array(
				'individual_government_issued_id_image' => $output['image_metadata']['file_name'],
				'individual_government_issued_id'		=> trim($this->input->post('individual_government_issued_id'))
			);

			if(!empty($_FILES['valid_id_beside_your_face_image']['name'])){
				$valid_id_beside_your_face_image_count = count($_FILES['valid_id_beside_your_face_image']['name']);
	
				for($i = 0; $i < $valid_id_beside_your_face_image_count; $i++){
						$_FILES['file']['name']     = $_FILES['valid_id_beside_your_face_image']['name'][$i];
						$_FILES['file']['type']     = $_FILES['valid_id_beside_your_face_image']['type'][$i];
						$_FILES['file']['tmp_name'] = $_FILES['valid_id_beside_your_face_image']['tmp_name'][$i];
						$_FILES['file']['error']    = $_FILES['valid_id_beside_your_face_image']['error'][$i];
						$_FILES['file']['size']     = $_FILES['valid_id_beside_your_face_image']['size'][$i];
						
						// Upload file to server
						if($this->upload->do_upload('file')){
								// Uploaded file data
								$fileData = $this->upload->data();
								$uploadData[$i]['id_type'] = 'OTHERS_ID_BESIDE_FACE';
								$uploadData[$i]['file_name'] = $fileData['file_name'];
								
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
							'region'		=> trim($this->input->post('region')),
							'province' 		=> trim($this->input->post('province')),
							'city_muni' 	=> trim($this->input->post('city_muni')),
							'barangay' 		=> trim($this->input->post('barangay')),
							'bldg_village' 	=> trim($this->input->post('bldg_village')),
							'zip_code' 		=> trim($this->input->post('zip_code'))
						),
						'individual_government_issued_id' 			=> $individual_government_issued_id_image_output['individual_government_issued_id'],
						'individual_government_issued_id_image'		=> $individual_government_issued_id_image_output['individual_government_issued_id_image'],
						'valid_id_beside_your_face_image'			=> $uploadData
					);
										
					$this->client_individual_model->add_individual_client($client_fields);
				}
			}else{
				$msg = array(
					'status'	=> 'FAILED',
					'error'	 	=> 'Valid ID beside your face is required. Please try again!'
				);
			}
		}
		
		return $msg;
	}
}
