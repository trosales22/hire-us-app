<?php
header('Access-Control-Allow-Origin: *');
require APPPATH . 'libraries/REST_Controller.php';
     
class Talents extends REST_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->database();
		$this->load->model('Home_model', 'home_model');
		$this->load->model('api/Talents_model', 'talents_model');
	}
	
	public function get_talent_categories_get(){
		try{
			$success       	= 0;			
			$talent_categories = $this->home_model->getAllCategories();
			$success  = 1;
		}catch (Exception $e){
			$msg = $e->getMessage();      
		}

		if($success == 1){
			$response = [
			  'talent_categories' => $talent_categories
			];
		}else{
			$response = [
				'msg'       => $msg,
				'flag'      => $success
			];
		}
		
		$this->response($response);	
	}

	public function get_talent_gallery_get(){
		try{
			$success       	= 0;
			$talent_id 	= $this->get('talent_id');
			
			if(EMPTY($talent_id))
				throw new Exception("Talent ID is required.");
			
			$talent_gallery = $this->home_model->getTalentGallery($talent_id);
			$success  = 1;
		}catch (Exception $e){
			$msg = $e->getMessage();      
		}

		if($success == 1){
			$response = [
			  'talent_gallery' => $talent_gallery
			];
		}else{
			$response = [
				'msg'       => $msg,
				'flag'      => $success
			];
		}
		
		$this->response($response);
	}
	
	public function get_all_talents_get(){
		try{
			$success        		= 0;

			$extra_filtering		= [
				'selected_categories'	=> $this->get('selected_categories')
			];
			
			$additional_filtering   = [
				'height_from'			=> $this->get('height_from'),
				'height_to'				=> $this->get('height_to'),
				'age_from'				=> $this->get('age_from'),
				'age_to'				=> $this->get('age_to'),
				'rate_per_hour_from'	=> $this->get('rate_per_hour_from'),
				'rate_per_hour_to'		=> $this->get('rate_per_hour_to'),
				'province_code'			=> $this->get('province_code'),
				'city_muni_code'		=> $this->get('city_muni_code'),
				'gender'				=> $this->get('gender'),
			];
			
			$talents_list   		= $this->talents_model->get_all_talents($extra_filtering, $additional_filtering);
			
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

	public function get_reserved_schedule_talent_get(){
		try{
			$success 					= 0;
			$talent_id      			= $this->get('talent_id');
			
			if(EMPTY($talent_id))
        		throw new Exception("Talent ID is required.");
			
			$reserved_schedule_list = $this->talents_model->get_reserved_schedule_talent($talent_id);
			
			$success  = 1;
		}catch (Exception $e){
			$msg = $e->getMessage();      
		}

		if($success == 1){
			$response = [
				'reserved_schedule_list' => $reserved_schedule_list
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

	public function get_all_talent_by_category_get(){
		try{
			$success 					= 0;
			$category_id      			= $this->get('category_id');

			if(EMPTY($category_id))
        		throw new Exception("Category ID is required.");
			
			$talents_per_category_list 	= $this->talents_model->get_all_talent_by_category($category_id);
			
			$success  = 1;
		}catch (Exception $e){
			$msg = $e->getMessage();      
		}

		if($success == 1){
			$response = [
				'talents_per_category_list' => $talents_per_category_list
			];
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

	public function get_all_client_booked_get(){
		try{
			$success 					= 0;
			$talent_id      			= $this->get('talent_id');

			if(EMPTY($talent_id))
        		throw new Exception("Talent ID is required.");
			
			$clients_booked_list = $this->talents_model->get_all_client_booked($talent_id);
			
			$success  = 1;
		}catch (Exception $e){
			$msg = $e->getMessage();      
		}

		if($success == 1){
			$response = [
			'clients_booked_list' => $clients_booked_list
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
