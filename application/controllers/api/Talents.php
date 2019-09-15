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
			$success        		= 0;
			$selected_categories 	= $this->get('selected_categories');
			$additional_filtering   = [
				'height'		=> $this->get('height'),
				'age'			=> $this->get('age'),
				'talent_fee'	=> $this->get('talent_fee'),
				'location'		=> $this->get('location')
			];
			
			$talents_list   		= $this->talents_model->getAllTalents($selected_categories, $additional_filtering);
			
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
			$response = $talent_details[0];
		}else{
			$response = [
				'msg'       => $msg,
				'flag'      => $success
			];
		}
	  
		$this->response($response);
	}

	public function get_talent_unavailable_dates_get(){
		try{
			$success 					= 0;
			$talent_id      			= $this->get('talent_id');

			if(EMPTY($talent_id))
        		throw new Exception("Talent ID is required.");
			
			$talent_unavailable_dates = $this->talents_model->getTalentUnavailableDates($talent_id);
			
			$success  = 1;
		}catch (Exception $e){
			$msg = $e->getMessage();      
		}

		if($success == 1){
			$response = [
			'talent_unavailable_dates' => $talent_unavailable_dates
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
