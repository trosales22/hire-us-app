<?php
require APPPATH . 'libraries/REST_Controller.php';
     
class Talents extends REST_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->database();
		$this->load->model('api/Talents_model', 'talents_model');
	}

	public function get_all_talents_get(){
		try{
			$success        = 0;	  
			$talents_list   = $this->talents_model->getAllTalents();
			
			$success  = 1;
		}catch (Exception $e){
			$msg = $e->getMessage();      
		}

		if($success == 1){
			$response = [
			  'talents_list' => $talents_list
			];
		}else{
			$response = [
				'msg'       => $msg,
				'flag'      => $success
			];
		}
	  
		$this->response($response);
	}
	
	public function get_talent_details_get(){
		try{
			$success 					= 0;
			$talent_id      			= $this->get('talent_id');

			if(EMPTY($talent_id))
        		throw new Exception("Talent ID is required.");
			
			$talent_details 	= $this->talents_model->getTalentDetails($talent_id);
			
			$success  = 1;
		}catch (Exception $e){
			$msg = $e->getMessage();      
		}

		if($success == 1){
			$response = [
				$talent_details
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