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

	public function add_to_temp_booking_list(array $data){
		$temp_booking_params = array(
			'temp_client_id' 			=> $data['temp_client_id'],
			'temp_talent_id' 			=> $data['temp_talent_id'],
			'temp_booking_date' 		=> $data['temp_booking_date'],
			'temp_booking_time' 		=> $data['temp_booking_time'],
			'temp_total_amount' 		=> $data['temp_total_amount'],
			'temp_status' 				=> $data['temp_status'],
			'temp_payment_option' 		=> $data['temp_payment_option']
		);

		$this->db->insert('temp_booking_list', $temp_booking_params);
		$lastInsertedId = $this->db->insert_id();
	}

	public function get_booking_list_by_client_id($client_id){
		$params = array($client_id);

		$query = "
			SELECT 
				A.booking_id,A.talent_id, FORMAT(A.total_amount, 2) as total_amount,
				A.preferred_date_from,A.preferred_date_to,
				A.preferred_time_from, A.preferred_time_to, 
				B.status, DATE_FORMAT(B.date_paid, '%M %d, %Y %r') as date_paid
			FROM 
				client_booking_list A 
			LEFT JOIN 
				client_booking_status B ON A.booking_id = B.booking_id 
			WHERE 
				A.client_id = ? 
			ORDER BY 
				booking_id DESC";

    	$stmt = $this->db->query($query, $params);
    	return $stmt->result();
	}
}
