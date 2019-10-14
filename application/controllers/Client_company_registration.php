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
		$msg = array();
		$company_id_image_output = array();
		$company_government_issued_id_image_output = array();
		$company_valid_id_beside_your_face_image_output = array();

		$config['upload_path'] = 'uploads/id_verification/';
		$config['allowed_types'] = 'jpg|jpeg|png';
		$config['max_size'] = 5000;
		$config['max_width'] = 1500;
		$config['max_height'] = 1500;
		$config['file_name'] = md5(time() . rand()) . '_' . mt_rand();

		$this->load->library('upload', $config);

		//validate company id
		if(!$this->upload->do_upload('company_id_image')) {
			$msg = array(
				'status'	=> 'FAILED',
				'error'	 	=> $this->upload->display_errors()
			);
		}else{
			$company_id_output = array(
				'image_metadata' => $this->upload->data()
			);

			$company_id_image_output = array(
				'company_id_image'	=> $company_id_output['image_metadata']['file_name']
			);
		}

		//validate government issued id
		if(!$this->upload->do_upload('company_government_issued_id_image')){
			$msg = array(
				'status'	=> 'FAILED',
				'error'	 	=> $this->upload->display_errors()
			);
		}else{
			$output = array(
				'image_metadata' => $this->upload->data()
			);
			
			$company_government_issued_id_image_output = array(
				'company_government_issued_id_image' 	=> $output['image_metadata']['file_name'],
				'company_government_issued_id'			=> trim($this->input->post('company_government_issued_id'))
			);
		}

		//validate id beside your face
		if(!empty($_FILES['valid_id_beside_your_face_image']['name'])){
			$valid_id_beside_your_face_image_count = count($_FILES['valid_id_beside_your_face_image']['name']);
			
			for ($i = 0; $i < $valid_id_beside_your_face_image_count; $i++) {
				$_FILES['file']['name']     = $_FILES['valid_id_beside_your_face_image']['name'][$i];
				$_FILES['file']['type']     = $_FILES['valid_id_beside_your_face_image']['type'][$i];
				$_FILES['file']['tmp_name'] = $_FILES['valid_id_beside_your_face_image']['tmp_name'][$i];
				$_FILES['file']['error']    = $_FILES['valid_id_beside_your_face_image']['error'][$i];
				$_FILES['file']['size']     = $_FILES['valid_id_beside_your_face_image']['size'][$i];
				
				// Upload file to server
				if($this->upload->do_upload('file')){
					// Uploaded file data
					$fileData = $this->upload->data();
					$company_valid_id_beside_your_face_image_output[$i]['id_type'] = 'OTHERS_ID_BESIDE_FACE';
					$company_valid_id_beside_your_face_image_output[$i]['file_name'] = $fileData['file_name'];
					
					$msg = array(
						'status'	=> 'SUCCESS',
						'image_metadata' => $this->upload->data()
					);
				}else{
					$msg = array(
						'status'	=> 'FAILED',
						'error'	 	=> $this->upload->display_errors()
					);

					print "<pre>";
					print_r($msg);
				}
			}

			if(!empty($company_valid_id_beside_your_face_image_output)){
				$company_client_fields =   array(
					'company_name'     					=> trim($this->input->post('company_name')),
					'company_contact_person'       		=> trim($this->input->post('company_contact_person')),
					'company_contact_person_position'   => trim($this->input->post('company_contact_person_position')),
					'company_length_of_service'       	=> trim($this->input->post('company_length_of_service')),
					'company_email'       				=> trim($this->input->post('company_email')),
					'company_contact_number'  			=> trim($this->input->post('company_contact_number')),
					'company_username'       			=> trim($this->input->post('company_username')),
					'company_password'       			=> trim($this->input->post('company_password')),
					'address'       	=> array(
						'region'		=> trim($this->input->post('region')),
						'province'		=> trim($this->input->post('province')),
						'city_muni' 	=> trim($this->input->post('city_muni')),
						'barangay' 		=> trim($this->input->post('barangay')),
						'bldg_village' 	=> trim($this->input->post('bldg_village')),
						'zip_code' 		=> trim($this->input->post('zip_code'))
					),
					'valid_ids'			=> array(
						'company_id_image'						=> $company_id_image_output['company_id_image'],
						'company_government_issued_id' 			=> $company_government_issued_id_image_output['company_government_issued_id'],
						'company_government_issued_id_image'	=> $company_government_issued_id_image_output['company_government_issued_id_image'],
						'valid_id_beside_your_face_image'		=> $company_valid_id_beside_your_face_image_output
					)
					
				);
				
				$this->client_company_model->add_company_client($company_client_fields);
			}
		}else{
			$msg = array(
				'status'	=> 'FAILED',
				'error'	 	=> 'Valid ID beside your face is required. Please try again!'
			);
		}
		
		return $msg;
	}
}
