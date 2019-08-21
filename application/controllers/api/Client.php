<?php
require APPPATH . 'libraries/REST_Controller.php';
     
class Client extends REST_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->database();
		$this->load->model('Client_individual_model', 'client_individual_model');
	}

	public function get_city_muni_by_province_code_get(){
		try{
			$success        		= 0;
			$province_code 	= $this->get('province_code');
			
			if(EMPTY($province_code))
        		throw new Exception("Province Code is required.");

			$city_muni_list = $this->client_individual_model->getCityMuniByProvinceCode($province_code);
			
			$success  = 1;
		}catch (Exception $e){
			$msg = $e->getMessage();      
		}

		if($success == 1){
			$response = [
			  'city_muni_list' => $city_muni_list
			];
		}else{
			$response = [
				'msg'       => $msg,
				'flag'      => $success
			];
		}
	  
		$this->response($response);
	}

	public function get_barangay_by_city_muni_code_get(){
		try{
			$success        		= 0;
			$city_muni_code 	= $this->get('city_muni_code');
			
			if(EMPTY($city_muni_code))
        		throw new Exception("City/Municipality Code is required.");

			$barangay_list = $this->client_individual_model->getBarangayByCityMuniCode($city_muni_code);
			
			$success  = 1;
		}catch (Exception $e){
			$msg = $e->getMessage();      
		}

		if($success == 1){
			$response = [
			  'barangay_list' => $barangay_list
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
