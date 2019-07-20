<?php
class Login_model extends CI_Model {

	public function loginUser(array $data){
		$params = array($data['username'], $data['password'], 'Y');
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
			$query = "
							SELECT 
								category_id,category_name
							FROM 
								param_categories 
							WHERE 
								active_flag = 'Y'
							";

		$stmt = $this->db->query($query);
		return $stmt->result();
	}
}
