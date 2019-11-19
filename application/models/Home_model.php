<?php
class Home_model extends CI_Model {

	public function getPersonalInfo($username_or_email){
		$params = array($username_or_email, $username_or_email, 'Y');
		$query = "
			SELECT 
				A.user_id, A.username, 
				A.firstname, A.lastname, A.email, 
				B.role_code, C.role_name
			FROM 
				users A
			LEFT JOIN 
				user_role B ON A.user_id = B.user_id 
			LEFT JOIN 
				param_roles C ON B.role_code = C.role_id 
			WHERE 
				A.username = ? OR A.email = ? AND A.active_flag = ?";

		$stmt = $this->db->query($query, $params);
		return $stmt->result();
	}

	private function _get_email_of_client($user_id){
		$params = array($user_id);
		$query = "
			SELECT 
				user_id, email 
			FROM 
				users 
			WHERE 
				user_id = ?";

		$stmt = $this->db->query($query, $params);
		$return_val = $stmt->result();
		return $return_val[0];
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

  	public function getAllTalents() {
		$query = "
			SELECT
				talent_id,CONCAT(firstname, ' ', lastname) as fullname,
				hourly_rate,gender,
				DATE_FORMAT(birth_date, '%M %d, %Y') as birth_date
			FROM
				talents
			ORDER BY talent_id DESC
		";

		$stmt = $this->db->query($query);
		return $stmt->result();
	}

	public function getAllClients($user_id = NULL){
		$where_condition = '';
		if(!empty($user_id)){
			$where_condition .= 'AND A.user_id = ' . $user_id;
		}

		$query = "
			SELECT 
				A.user_id, A.username, A.email, A.contact_number,
				IFNULL(CONCAT(A.firstname, ' ', A.lastname), D.company_name) as fullname,
				IFNULL(A.gender, '') as gender,
				B.role_code, IF(C.role_name = 'Client (Individual)', 'INDIVIDUAL', 'COMPANY / CORPORATE') as role_name,  IF(A.active_flag = 'Y', 'Active', 'Inactive') as status_flag
			FROM 
				users A
			LEFT JOIN 
				user_role B ON A.user_id = B.user_id 
			LEFT JOIN 
				param_roles C ON B.role_code = C.role_id 
			LEFT JOIN 
				client_details D ON A.user_id = D.user_id
			WHERE 
				B.role_code IN ('CLIENT_COMPANY', 'CLIENT_INDIVIDUAL') $where_condition
			ORDER BY 
				A.user_id DESC";

		$stmt = $this->db->query($query);
		return $stmt->result();
	}

	public function getAllApplicants(){
		$query = "
			SELECT 
				A.user_id, A.email, A.contact_number,
				CONCAT(A.firstname, ' ', A.lastname) as fullname,
				B.role_code, C.role_name,  IF(A.active_flag = 'Y', 'Active', 'Inactive') as status_flag
			FROM 
				users A
			LEFT JOIN 
				user_role B ON A.user_id = B.user_id 
			LEFT JOIN 
				param_roles C ON B.role_code = C.role_id 
			WHERE 
				B.role_code IN ('APPLICANT')
			ORDER BY 
				A.user_id DESC";

		$stmt = $this->db->query($query);
		return $stmt->result();
	}
	
	public function getTalentResourceCount($talent_id){
		$params = array($talent_id);

		$query = "
			SELECT 
				count(*) as talent_res_count
			FROM 
				talents_resources 
			WHERE talent_id = ?";

		$stmt = $this->db->query($query, $params);
    	return $stmt->result();
	}

	public function get_requirements_of_client($client_id){
		$params = array($client_id);
		
		$query = "
			SELECT 
				A.id, A.user_id as client_id, B.valid_id_code, B.valid_id_name,
				IF( ISNULL(A.file_name), '', CONCAT('" . base_url() . "uploads/id_verification/', A.file_name) ) as file_name,
				DATE_FORMAT(A.created_date, '%M %d, %Y %r') as created_date
			FROM 
				user_valid_id A 
			LEFT JOIN 
				param_valid_ids B ON A.id_type = B.valid_id_code 
			WHERE user_id = ?;
		";
		
		$stmt = $this->db->query($query, $params);
		return $stmt->result();
	}

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

  	public function insertTalentOrModel(array $data) {
		//insert to talents table
		$talents_fields = array(
		'firstname' => $data['firstname'],
		'middlename' => $data['middlename'],
		'lastname' => $data['lastname'],
		'email' => $data['email'],
		'contact_number' => $data['contact_number'],
		'gender' => $data['gender'],
		'height' => $data['height'],
		'birth_date' => $data['birth_date'],
		'hourly_rate' => $data['hourly_rate'],
		'vital_stats'	=> $data['vital_stats'],
		'fb_followers'	=> $data['fb_followers'],
		'instagram_followers'	=> $data['instagram_followers'],
		'genre'	=> $data['genre'],
		'description'	=> $data['description'],
		'created_by' => 1,
		);

		$this->db->insert('talents', $talents_fields);
		$lastInsertedId = $this->db->insert_id();
			
		//insert to talents_category table
		foreach($data['categories'] as $category){
			$talents_category_fields = array(
				'talent_id' => $lastInsertedId,
				'category_id' => $category,
			);

			$this->db->insert('talents_category', $talents_category_fields);
		}

		//insert to talents_account table
		$generated_pin = 'HIRE_US@123';

		$talents_account_fields = array(
			'talent_id' => $lastInsertedId,
			'talent_password' => password_hash($generated_pin, PASSWORD_BCRYPT),
		);
		
		$this->db->insert('talents_account', $talents_account_fields);
		
		//insert to talents_exp_or_prev_clients table
		$talents_prev_clients_fields = array(
			'talent_id' => $lastInsertedId,
			'details'		=> $data['prev_clients']
		);

		$this->db->insert('talents_exp_or_prev_clients', $talents_prev_clients_fields);
		
		//insert to talent_address table
		$talents_address_fields = array(
			'talent_id' 		=> $lastInsertedId,
			'region'			=> $data['address']['region'],
			'province' 			=> $data['address']['province'],
			'city_muni' 		=> $data['address']['city_muni'],
			'barangay' 			=> $data['address']['barangay'],
			'bldg_village' 		=> $data['address']['bldg_village'],
			'zip_code' 			=> $data['address']['zip_code']
		);

		$this->db->insert('talents_address', $talents_address_fields);

		$this->_send_added_talent_email_notif($data);
	}

	private function _send_client_status_email_notif($email, $status){
		try{
			$success = 0;
			$from = "support@hireusph.com";
			$to = $email;
			
			if($status == 'Y'){
				$subject = "Account Activated!";
				$message = "Congratulations! Your account has been activated. You can now login your account. Thank you.";
			}else{
				$subject = "Account Deactivated!";
				$message = "Whoop. We're sorry! Your account has been deactivated!";
			}

			$headers = "From:" . $from;
			mail($to, $subject, $message, $headers);
			$success  = 1;
		}catch (Exception $e){
			$msg = $e->getMessage();      
		}
	}

	private function _send_added_talent_email_notif(array $data){
		try{
			$success = 0;
			$from = "support@hireusph.com";
			$to = $data['email'];
			$honorific = '';
			$message = '';
			$subject = "Welcome to Hire Us PH!";

			if($data['gender'] == 'Male'){
				$honorific = 'Mr. ';
			}else if($data['gender'] == 'Female'){
				$honorific = 'Ms/Mrs. ';
			}

			$message = "Hi " . $honorific . $data['firstname'] . ' ' . $data['lastname'] . "!\n\n";
			$message .= "Below are your account details:\n\n";
			$message .= "Email: " . $data['email'] . "\n";
			$message .= "Contact Number: " . $data['contact_number'] . "\n";
			$message .= "Rate per hour: PHP" . $data['hourly_rate'] . "\n";
			$message .= "Password: HIRE_US@123\n\nYou can now login your account as a Talent/Model. Thank you & welcome to Hire Us PH.\n";
			
			$headers = "From:" . $from;
			mail($to, $subject, $message, $headers);
			$success  = 1;
		}catch (Exception $e){
			$msg = $e->getMessage();      
		}
	}

	public function update_client_status(array $data){
		$client_params = array('active_flag' => $data['active_flag']);
		$this->db->where('user_id', $data['user_id']);
		$this->db->update('users', $client_params);
		
		$client_details = $this->_get_email_of_client($data['user_id']);		
		$this->_send_client_status_email_notif($client_details->email, $data['active_flag']);
	}

	public function uploadTalentProfilePic(array $fields){
		//insert to talents_resources table
		$this->db->insert('talents_resources', $fields);
	}

	public function uploadTalentGallery(array $data){
		//insert to talents_gallery table
		$insert = $this->db->insert_batch('talents_gallery',$data);
	}

	public function getTalentGallery($talent_id = ''){
		$params = array($talent_id);
		$query = "
			SELECT
				img_id, talent_id,
				IF( ISNULL(file_name), '', CONCAT('" . base_url() . "uploads/talents_or_models/', file_name) ) as file_name,
				DATE_FORMAT(uploaded_on, '%M %d, %Y %r') as created_date
			FROM
				talents_gallery
			ORDER BY 
				uploaded_on DESC
  			";

    	$stmt = $this->db->query($query, $params);
		return $stmt->result();
	}
}
