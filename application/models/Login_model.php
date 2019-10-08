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
					$data['username_or_email'],
					$data['username_or_email'],
					$data['password'], 
					'Y'
				);
		$query = "
			SELECT 
				username
			FROM 
				users
			WHERE 
				username = ? OR email = ? AND password = ? AND active_flag = ?";
				
		$stmt = $this->db->query($query, $params);
		return $stmt->num_rows();
	}

	public function login_talent(array $data){
		$params = array(
			$data['username_or_email'],
			$data['password'], 
			'Y'
		);

		$query = "
			SELECT 
				A.talent_id, A.email
			FROM 
				talents A 
			LEFT JOIN 
				talents_account B ON A.talent_id = B.talent_id 
			WHERE 
				A.email = ? AND B.talent_password = ? AND A.active_flag = ?
		";
				
		$stmt = $this->db->query($query, $params);
		return $stmt->num_rows();
	}

	public function get_talent_information($username_or_email){
		$params = array(
			$username_or_email,
			'Y'
		);

		$query = "
			SELECT 
				A.talent_id, A.firstname, A.lastname, A.email, 
				B.talent_password as password, 
				IF( ISNULL(C.talent_display_photo), '', CONCAT('" . base_url() . "uploads/talents_or_models/', C.talent_display_photo) ) as talent_display_photo, 
				GROUP_CONCAT(E.category_name SEPARATOR '\n') as role_name
			FROM 
				talents A 
			JOIN 
				talents_account B ON A.talent_id = B.talent_id 
			JOIN 
				talents_resources C ON A.talent_id = C.talent_id 
			LEFT JOIN 
				talents_category D ON A.talent_id = D.talent_id 
			LEFT JOIN 
				param_categories E ON D.category_id = E.category_id 
			WHERE 
				A.email = ? AND A.active_flag = ?
			";

		$stmt = $this->db->query($query, $params);
		return $stmt->result();
	}
	
	public function getUserInformation($username_or_email){
		$params = array($username_or_email, $username_or_email, 'Y');
		$query = "
			SELECT 
				A.user_id, A.username, A.firstname, A.lastname, 
				A.email,A.gender, A.password, B.role_code
			FROM 
				users A
			LEFT JOIN 
				user_role B ON A.user_id = B.user_id
			WHERE 
				A.username = ? OR A.email = ? AND A.active_flag = ?
			";

		$stmt = $this->db->query($query, $params);
		return $stmt->result();
	}

	public function getUserRole($user_id){
		$params = array($user_id);
		$query = "
			SELECT 
				user_id,role_code
			FROM 
				user_role
			WHERE 
				user_id = ?";

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
