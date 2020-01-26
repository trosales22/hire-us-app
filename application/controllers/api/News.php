<?php
header('Access-Control-Allow-Origin: *');
require APPPATH . 'libraries/REST_Controller.php';

class News extends REST_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->database();
		$this->load->model('api/News_model', 'news_model');
	}
	
	public function get_all_news_get(){
		try{
			$success        = 0;
			$news_id		= trim($this->input->get('news_id'));
			$news_caption 	= trim($this->input->get('news_caption'));

			$news_params = array(
				'news_id'		=> $news_id,
				'news_caption' 	=> $news_caption
			);
			
			$news_list = $this->news_model->get_news_and_updates($news_params);
			$success  = 1;
		}catch (Exception $e){
			$msg = $e->getMessage();      
		}

		if($success == 1){
			$response = [
			  'news_list' => $news_list
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
