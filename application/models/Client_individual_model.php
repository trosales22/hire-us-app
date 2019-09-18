<?php
class Client_individual_model extends CI_Model {

  	public function getAllProvinces() {
		$query = "
			SELECT 
				id,provDesc,provCode
			FROM 
				param_province 
			ORDER BY provDesc ASC";

    	$stmt = $this->db->query($query);
    	return $stmt->result();
	}

	public function getCityMuniByProvinceCode($province_code){
		$params = array($province_code);

		$query = "
			SELECT 
				id,citymunDesc,provCode,citymunCode
			FROM 
				param_city_muni 
			WHERE provCode = ? 
			ORDER BY citymunDesc ASC";

    	$stmt = $this->db->query($query, $params);
    	return $stmt->result();
	}

	public function getBarangayByCityMuniCode($city_muni_code){
		$params = array($city_muni_code);

		$query = "
			SELECT 
				id,brgyDesc,provCode,citymunCode
			FROM 
				param_barangay 
			WHERE citymunCode = ? 
			ORDER BY brgyDesc ASC";

    	$stmt = $this->db->query($query, $params);
    	return $stmt->result();
	}

	public function addIndividualClient(array $data){
		//insert to talents table
		$client_fields = array(
			'firstname' => $data['firstname'],
			'lastname' => $data['lastname'],
			'email' => $data['email'],
			'contact_number' => $data['contact_number'],
			'username' => $data['username'],
			'password' => password_hash($data['password'], PASSWORD_BCRYPT),
			'gender' => $data['gender']
		);
	
		//insert to users table
		$this->db->insert('users', $client_fields);
		$lastInsertedId = $this->db->insert_id();

		//insert to user_birth_date table
		$user_birthdate_fields = array(
			'user_id'		=> $lastInsertedId,
			'birthdate'		=> $data['birth_date']
		);

		$this->db->insert('user_birth_date', $user_birthdate_fields);

		//insert to role table
		$user_role_fields = array(
			'user_id' 		=> $lastInsertedId,
			'role_code'		=> 'CLIENT_INDIVIDUAL'
		);

		$this->db->insert('user_role', $user_role_fields);

		//insert to user_address table
		$client_address_fields = array(
			'user_id' 			=> $lastInsertedId,
			'province' 			=> $data['address']['province'],
			'city_muni' 		=> $data['address']['city_muni'],
			'barangay' 			=> $data['address']['barangay'],
			'bldg_village' 		=> $data['address']['bldg_village'],
			'zip_code' 			=> $data['address']['zip_code']
		);

		$this->db->insert('user_address', $client_address_fields);
	}
}
