<?php
header('Access-Control-Allow-Origin: *');
require APPPATH . 'libraries/REST_Controller.php';

class Hostinger_email extends REST_Controller {
	public function __construct() {
		parent::__construct();
	}
	
	public function send_email_get(){
		try{
			$success = 0;
			$from = "support@hireusph.com";
			$to = "tristanrosales0@gmail.com";
			$subject = "Checking PHP mail";
			$message = "PHP mail works just fine";
			$headers = "From:" . $from;
			mail($to, $subject, $message, $headers);

			$result_msg = "The email message was sent.";
			$success  = 1;
		}catch (Exception $e){
			$msg = $e->getMessage();      
		}

		if($success == 1){
			$response = [
				'msg'       => $result_msg,
				'flag'      => $success
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
