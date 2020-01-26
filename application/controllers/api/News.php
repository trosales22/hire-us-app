<?php
header('Access-Control-Allow-Origin: *');
require APPPATH . 'libraries/REST_Controller.php';

class News extends REST_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->database();
		$this->load->model('api/News_model', 'news_model');
	}
	
	public function get_all_announcements_get(){
		try{
			$success        = 0;
			$announcement_id		= trim($this->get('announcement_id'));
			$announcement_caption 	= trim($this->get('announcement_caption'));

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
	  
		$this->response($response);
	}
}
