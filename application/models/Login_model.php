<?php
class Login_model extends CI_Model {

	private function _generatePIN($digits = 4) {
		$i = 0; //counter
		$pin = ""; //our default pin is blank.
		while ($i < $digits) {
		  //generate a random number between 0 and 9.
		  $pin .= mt_rand(0, 9);
		  $i++;
		}
		return $pin;
	}

	public function loginUser(array $data){
		$params = array(
					$data['username'], 
					$data['password'], 
					'Y'
				);
		$query = "
			SELECT 
				username
			FROM 
				users
			WHERE 
				username = ? AND password = ? AND active_flag = ?
			";
				
		$stmt = $this->db->query($query, $params);
		return $stmt->num_rows();
	}
	
	public function getUserInformation($username){
		$params = array($username, 'Y');
		$query = "
			SELECT 
				user_id,username,firstname,lastname,email,gender,password
			FROM 
				users
			WHERE 
				username = ? AND active_flag = ?
			";

		$stmt = $this->db->query($query, $params);
		return $stmt->result();
	}

	public function getAllCategories() {
		$params = array('Y');
		$query = "
			SELECT 
				category_id,category_name
			FROM 
				param_categories 
			WHERE 
				active_flag = ?
			";

		$stmt = $this->db->query($query, $params);
		return $stmt->result();
	}

	public function getAllRoleForClient() {
		$params = array('Y');
		
		$query = "
			SELECT 
				role_id,role_name
			FROM 
				param_roles 
			WHERE 
				active_flag = ? AND role_id IN ('CLIENT_INDIVIDUAL', 'CLIENT_COMPANY')
			";

    	$stmt = $this->db->query($query, $params);
    	return $stmt->result();
	 }
	 
	 public function insertApplicant(array $data) {
		$users_fields = array(
		  'firstname' => $data['firstname'],
		  'middlename' => $data['middlename'],
		  'lastname' => $data['lastname'],
		  'email' => $data['email'],
		  'contact_number' => $data['contact_number'],
		  'gender' => $data['gender'],
		  'password'	=> password_hash($data['password'], PASSWORD_BCRYPT)
		);

		$this->db->insert('users', $users_fields);
		$lastInsertedId = $this->db->insert_id();

		$user_role_fields = array(
			'user_id' => $lastInsertedId,
			'role_code' => 'APPLICANT',
		);
		
		$this->db->insert('user_role', $user_role_fields);
	}

	public function insertClient(array $data){
		$generated_pin = 'HIREUS_' . $this->_generatePIN();
		print_r('PIN: ' . $generated_pin);

		$users_fields = array(
			'email' => $data['email'],
			'contact_number' => $data['contact_number'],
			'password'	=> password_hash($generated_pin, PASSWORD_BCRYPT)
		);

		$this->db->insert('users', $users_fields);
		$lastInsertedId = $this->db->insert_id();

		$user_role_fields = array(
			'user_id' => $lastInsertedId,
			'role_code' => $data['account_type'],
		);
		
		$this->db->insert('user_role', $user_role_fields);
		
	}
}
