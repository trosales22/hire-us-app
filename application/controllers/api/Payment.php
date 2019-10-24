<?php
header('Access-Control-Allow-Origin: *');
require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/stripe-php-7.7.0/init.php';

class Payment extends REST_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->database();
		$this->load->model('Login_model', 'login_model');
		$this->load->model('Home_model', 'home_model');
	}

	public function start_payment_post(){
		try {
			// Use Stripe's library to make requests...
			$success 		= 0;
			$stripe_token 	= $this->post('stripe_token');
			$amount 		= $this->post('amount');
			$description 	= $this->post('description');
			$currency 		= 'PHP';
			
			if(EMPTY($stripe_token))
				throw new Exception("Stripe token is required.");

			if(EMPTY($amount))
				throw new Exception("Amount is required.");
			
			if(EMPTY($description))
				throw new Exception("Description is required.");
			
			\Stripe\Stripe::setApiKey($this->config->item('stripe_secret'));
			
			\Stripe\Charge::create ([
					"amount" 		=> $amount * 100,
					"currency" 		=> $currency,
					"source" 		=> $stripe_token,
					"description" 	=> $description 
			]);
			
			$success  = 1;
		} catch(\Stripe\Exception\CardException $e) {
			// Since it's a decline, \Stripe\Exception\CardException will be caught
			// echo 'Status is:' . $e->getHttpStatus() . '\n';
			// echo 'Type is:' . $e->getError()->type . '\n';
			// echo 'Code is:' . $e->getError()->code . '\n';
			// // param is '' in this case
			// echo 'Param is:' . $e->getError()->param . '\n';
			// echo 'Message is:' . $e->getError()->message . '\n';
			$msg = $e->getError()->message;
		} catch (\Stripe\Exception\RateLimitException $e) {
			// Too many requests made to the API too quickly
			$msg = $e->getError()->message;
		} catch (\Stripe\Exception\InvalidRequestException $e) {
			// Invalid parameters were supplied to Stripe's API
			$msg = $e->getError()->message;
		} catch (\Stripe\Exception\AuthenticationException $e) {
			// Authentication with Stripe's API failed
			// (maybe you changed API keys recently)
			$msg = $e->getError()->message;
		} catch (\Stripe\Exception\ApiConnectionException $e) {
			// Network communication with Stripe failed
			$msg = $e->getError()->message;
		} catch (\Stripe\Exception\ApiErrorException $e) {
			// Display a very generic error to the user, and maybe send
			// yourself an email
			$msg = $e->getError()->message;
		} catch (Exception $e) {
			// Something else happened, completely unrelated to Stripe
			$msg = $e->getMessage();
		}

		if($success == 1){
			$response = [
				'msg'       => 'Payment successful. Thank you.',
				'flag'		=> $success
			];
		}else{
			$response = [
				'msg'       => $msg,
				'flag'      => $success
			];
		}
		
		$this->response($response);
	}

	public function get_personal_info_get(){
		try{
			$success        		= 0;
			$username_email 	= $this->get('username_email');
			
			if(EMPTY($username_email))
        		throw new Exception("Username/email is required.");

			$personal_info = $this->home_model->getPersonalInfo($username_email);
			$success  = 1;
		}catch (Exception $e){
			$msg = $e->getMessage();      
		}

		if($success == 1){
			if(empty($personal_info)){
				$response = [
					'msg'       => 'Unidentified user. Please try again.',
					'flag'		=> 0
				];
			}else{
				$response = $personal_info[0];
			}
		}else{
			$response = [
				'msg'       => $msg,
				'flag'      => $success
			];
		}
	  
		$this->response($response);
	}

	public function get_talent_personal_info_get(){
		try{
			$success        		= 0;
			$username_email 	= $this->get('username_email');
			
			if(EMPTY($username_email))
        		throw new Exception("Username/email is required.");

			$personal_info = $this->login_model->get_talent_information($username_email);
			$success  = 1;
		}catch (Exception $e){
			$msg = $e->getMessage();      
		}

		if($success == 1){
			if(empty($personal_info)){
				$response = [
					'msg'       => 'Unidentified user. Please try again.',
					'flag'		=> 0
				];
			}else{
				$response = $personal_info[0];
			}
		}else{
			$response = [
				'msg'       => $msg,
				'flag'      => $success
			];
		}
	  
		$this->response($response);
	}
}
