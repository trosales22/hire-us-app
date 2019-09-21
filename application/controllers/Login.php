<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {
	public function __construct() {
		parent::__construct();
		
		$this->load->helper('url', 'form');
		$this->load->library('session');
		$this->load->model('Login_model', 'login_model');
	}

	public function index(){
		//use password_hash("password", PASSWORD_BCRYPT) for password encryption/hashing
		//use password_verify("password", $hash) for password verification

		$data['error_message'] = '';
		$data['role_clients'] = $this->login_model->getAllRoleForClient();

		$this->load->view('login_page', $data);	
	}

	public function user_login_process() {
		$inputs = array(
			'username_or_email' => $this->input->post('username_or_email'),
			'password' 			=> $this->input->post('password')
		);
		
		$res = array();
		$result = $this->login_model->getUserInformation($inputs['username_or_email']);

		if(empty($result)){
			$res = array(
				'status' 	=> 'UNKNOWN_USER',
				'msg'		=> 'Unknown user. Please try again!'
			);
		}else{
			if(password_verify($inputs['password'], $result[0]->password)){
				$fields = array(
					'username_or_email' 	=> $inputs['username_or_email'],
					'password' 				=> $result[0]->password
				);
				
				$count = $this->login_model->loginUser($fields);
				
				if($count == 1){
					$user_role_res = $this->login_model->getUserRole($result[0]->user_id);
	
					if($user_role_res[0]->role_code == 'SUPER_ADMIN'){
						$session_data = array(
							'status'		=> 'OK',
							'user_id'		=> $result[0]->user_id,
							'username' 		=> $result[0]->username,
							'email' 		=> $result[0]->email,
							'role_code'		=> $result[0]->role_code
						);
						
						$res = $session_data;
						$this->session->set_userdata('logged_in', $session_data);
						//redirect(base_url('home_page'));
					}else{
						$res = array(
							'status' => 'INVALID_ROLE', 
							'msg' => 'Only Super Admin have access to the system!'
						);
					}
				}else{
					$res = array(
						'status' 	=> 'INVALID_LOGIN',
						'msg'		=> 'Invalid email/username or password!'
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

	public function user_logout(){    
        if ($this->session->userdata('logged_in')) {
			$this->session->unset_userdata('user_id');
			$this->session->unset_userdata('username');
			$this->session->unset_userdata('email');
            $this->session->unset_userdata('logged_in');       
		}
		
        redirect('login_page');
	}

	public function insertApplicant(){
		$applicant_fields =   array(
			'firstname'     	=> $this->input->post('firstname'),
			'middlename'     	=> $this->input->post('middlename'),
			'lastname'       	=> $this->input->post('lastname'),
			'email'       		=> $this->input->post('email'),
			'contact_number'  	=> $this->input->post('contact_number'),
			'gender'       		=> $this->input->post('gender'),
			'password'       	=> $this->input->post('password')
		);
		  
		$this->login_model->insertApplicant($applicant_fields);

		redirect('login_page');
	}

	public function insertClient(){
		$client_fields =   array(
			'email'       		=> $this->input->post('email'),
			'contact_number'  	=> $this->input->post('contact_number'),
			'account_type'      => $this->input->post('account_type')
		);
		  
		$this->login_model->insertClient($client_fields);
		
		redirect('login_page');
	}
}
