<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {
  public function __construct() {
	parent::__construct();
		
	$this->load->helper('url', 'form');
	$this->load->library('session');
    $this->load->database();
	$this->load->model('Home_model', 'home_model');
	$this->load->model('api/Clients_model', 'clients_model');
  }

  public function index() {
    $this->data['categories'] = $this->home_model->getAllCategories();
	$this->data['talents'] = $this->home_model->getAllTalents();
	$this->data['clients'] = $this->home_model->getAllClients();
	$this->data['applicants'] = $this->home_model->getAllApplicants();
	$this->data['param_regions'] = $this->clients_model->get_all_regions();
    $this->load->view('home_page', $this->data);
	}

	public function delete_applicant(){
		try{
			$success 	= 0;
			$user_id 	= trim($this->input->get('user_id'));
			
			if(EMPTY($user_id))
				throw new Exception("User ID is required.");

			$this->home_model->delete_applicant($user_id);
			$success  = 1;
		}catch (Exception $e){
			$msg = $e->getMessage();
		}

		if($success == 1){
			$response = [
				'msg'       => 'Applicant was successfully deleted.',
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

	public function uploadProfilePicOfTalent(){
		$talent_id = $this->input->post('talent_id');
		$res = $this->home_model->getTalentResourceCount($talent_id);
		$msg = array();
		
		if($res[0]->talent_res_count == 1){
			$msg = array(
				'status'	=> 'FAILED',
				'error_msg'	=> 'Already have an existing record.'
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
