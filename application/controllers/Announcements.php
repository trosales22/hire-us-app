<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Announcements extends CI_Controller {
	public function __construct() {
		parent::__construct();
			
		$this->load->helper('url', 'form');
		$this->load->library('session');
		$this->load->database();
		$this->load->model('api/Announcements_model', 'announcements_model');
	}
	
	public function index() {
		$this->_get_announcements();
		$this->load->view('announcements_page', $this->data);
	}

	private function _get_announcements(){
		try{
			$success        = 0;
			$announcement_id		= trim($this->input->get('announcement_id'));
			$announcement_caption 	= trim($this->input->get('announcement_caption'));

			$announcement_params = array(
				'announcement_id'		=> $announcement_id,
				'announcement_caption' 	=> $announcement_caption
			);
			
			$announcements_list = $this->announcements_model->get_announcements($announcement_params);
			$success  = 1;
		}catch (Exception $e){
			$msg = $e->getMessage();      
		}

		if($success == 1){
			$response = [
			  'announcements_list' => $announcements_list
			];
		}else{
			$response = [
				'msg'       => $msg,
				'flag'      => $success
			];
		}
		
		$this->data['announcements'] = $response['announcements_list'];
	}

	public function add_announcement(){
		try{
			$success  = 0;
			$msg = array();
			$session_data = $this->session->userdata('logged_in');

			$announcement_params = array(
				'announcement_caption'	=> trim($this->input->post('announcement_caption')),
				'announcement_details'	=> trim($this->input->post('announcement_details')),
				'announcement_link'		=> trim($this->input->post('announcement_link')),
				'created_by'       		=> $session_data['user_id']
			);

			if(EMPTY($announcement_params['announcement_caption']))
				throw new Exception("Announcement Caption is required.");
			
			if(EMPTY($announcement_params['announcement_details']))
				throw new Exception("Announcement Details is required.");

			if(EMPTY($announcement_params['created_by']))
				throw new Exception("Creator is required.");

			$this->announcements_model->add_announcement($announcement_params);
			$success  = 1;
		}catch (Exception $e){
			$msg = $e->getMessage();      
		}

		if($success == 1){
			$response = [
				'msg'       => 'Announcement was successfully added!',
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
