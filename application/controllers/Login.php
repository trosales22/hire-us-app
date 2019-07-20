<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {
	public function __construct() {
		parent::__construct();
		
		$this->load->helper('url', 'form');
		$this->load->library('session', 'form_validation');
		$this->load->model('Login_model', 'login_model');
	}

	public function index(){
		//use password_hash("password", PASSWORD_BCRYPT) for password encryption/hashing
		//use password_verify("password", $hash) for password verification
		
		$data['error_message'] = '';
		$this->load->view('login_page', $data);	
	}

	public function user_login_process() {
		$inputs = array(
			'username' => $this->input->post('username'),
			'password' => $this->input->post('password')
		);
		
		$result = $this->login_model->getUserInformation($inputs['username']);

		if(password_verify($inputs['password'], $result[0]->password)){
			$fields = array(
				'username' => $inputs['username'],
				'password' => $result[0]->password
			);

			$count = $this->login_model->loginUser($fields);

			if($count == 1){
				$session_data = array(
					'user_id'	=> $result[0]->user_id,
					'username' => $result[0]->username,
					'email' => $result[0]->email,
				);

				$this->session->set_userdata('logged_in', $session_data);
				redirect(base_url('home_page'));
			}else{
				$this->data['error_message'] = 'Invalid Username or Password!';
				$this->load->view('login_page', $this->data);
			}
		}else{
			$this->data['error_message'] = 'Invalid Username or Password!';
			$this->load->view('login_page', $this->data);
		}
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
}
