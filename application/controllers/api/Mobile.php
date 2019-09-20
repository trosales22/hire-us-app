<?php
require APPPATH . 'libraries/REST_Controller.php';
     
class Mobile extends REST_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->database();
		$this->load->model('Login_model', 'login_model');
	}

	public function user_login_post() {
		$inputs = array(
			'username_or_email' => $this->post('username_or_email'),
			'password' 			=> $this->post('password')
		);

		$res = array();
		$result = $this->login_model->getUserInformation($inputs['username_or_email']);

		if(empty($result)){
			$res = array(
				'status' 	=> 'UNKNOWN_USER',
				'msg'		=> 'User not found!'
			);
		}else{
			if(password_verify($inputs['password'], $result[0]->password)){
				$fields = array(
					'username_or_email' => $inputs['username_or_email'],
					'password' => $result[0]->password
				);
				
				$count = $this->login_model->loginUser($fields);
	
				if($count == 1){
					$user_role_res = $this->login_model->getUserRole($result[0]->user_id);
	
					if($user_role_res[0]->role_code == 'SUPER_ADMIN'){
						$res = array(
							'status' => 'INVALID_ROLE', 
							'msg' => 'Super Admin is not allowed to login!'
						);
					}else{
						$session_data = array(
							'status'		=> 'OK',
							'user_id'		=> $result[0]->user_id,
							'username' 		=> $result[0]->username,
							'email' 		=> $result[0]->email,
							'role_code'		=> $result[0]->role_code
						);
						
						$res = $session_data;
					}
				}else{
					$res = array(
						'status' 	=> 'INVALID_LOGIN',
						'msg'		=> 'Invalid username or password!'
					);
				}
			}else{
				$res = array(
					'status' 	=> 'PASSWORD_MISMATCH',
					'msg'		=> 'Password does not match!'
				);
			}
		}

		echo json_encode($res);
	}
}
