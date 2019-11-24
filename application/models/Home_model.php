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
				talent_id, CONCAT(firstname, ' ', lastname) as fullname,
				screen_name, hourly_rate, gender,
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

	public function update_client_status(array $data){
		try{
			$client_params = array('active_flag' => $data['active_flag']);
			$this->db->where('user_id', $data['user_id']);
			$this->db->update('users', $client_params);
			
			$client_details = $this->_get_email_of_client($data['user_id']);		
			$this->_send_client_status_email_notif($client_details->email, $data['active_flag']);
		}catch(PDOException $e){
            $msg = $e->getMessage();
            $this->db->trans_rollback();
        }	
	}

	public function uploadTalentProfilePic(array $fields){
		try{
			//insert to talents_resources table
			$this->db->insert('talents_resources', $fields);
		}catch(PDOException $e){
			$msg = $e->getMessage();
			$this->db->trans_rollback();
		}
	}

	public function uploadTalentGallery(array $data){
		try{
			//insert to talents_gallery table
			$insert = $this->db->insert_batch('talents_gallery',$data);
		}catch(PDOException $e){
			$msg = $e->getMessage();
			$this->db->trans_rollback();
		}
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
