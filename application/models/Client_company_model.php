<?php
class Client_company_model extends CI_Model {
	public function add_company_client(array $data){
		try{
			//insert to users table
			$users_fields = array(
				'username'			=> $data['company_username'],
				'email'				=> $data['company_email'],
				'contact_number'	=> $data['company_contact_number'],
				'password'			=> password_hash($data['company_password'], PASSWORD_BCRYPT),
				'active_flag'		=> 'N'
			);
			
			$this->db->insert('users', $users_fields);
			$lastInsertedId = $this->db->insert_id();

			//insert to client_details table
			$client_details_fields = array(
				'user_id'					=> $lastInsertedId,
				'company_name' 				=> $data['company_name'],
				'contact_person' 			=> $data['company_contact_person'],
				'contact_person_position' 	=> $data['company_contact_person_position'],
				'length_of_service' 		=> $data['company_length_of_service']
			);
			
			$this->db->insert('client_details', $client_details_fields);
			
			//insert to role table
			$user_role_fields = array(
				'user_id' 		=> $lastInsertedId,
				'role_code'		=> 'CLIENT_COMPANY'
			);

			$this->db->insert('user_role', $user_role_fields);

			//insert to user_address table
			$client_address_fields = array(
				'user_id' 			=> $lastInsertedId,
				'region'			=> $data['address']['region'],
				'province' 			=> $data['address']['province'],
				'city_muni' 		=> $data['address']['city_muni'],
				'barangay' 			=> $data['address']['barangay'],
				'bldg_village' 		=> $data['address']['bldg_village'],
				'zip_code' 			=> $data['address']['zip_code']
			);

			$this->db->insert('user_address', $client_address_fields);

			//insert to user_valid_id table

			//insert company_id
			$company_id_fields = array(
				'user_id' 		=> $lastInsertedId,
				'id_type'		=> 'COMPANY_ID',
				'file_name'		=> $data['valid_ids']['company_id_image']
			);

			$this->db->insert('user_valid_id', $company_id_fields);

			//insert company_government_issued_id
			$company_government_issued_id_fields = array(
				'user_id' 		=> $lastInsertedId,
				'id_type'		=> $data['valid_ids']['company_government_issued_id'],
				'file_name'		=> $data['valid_ids']['company_government_issued_id_image']
			);

			$this->db->insert('user_valid_id', $company_government_issued_id_fields);
			
			//insert valid_id_beside_your_face
			for($i = 0; $i < count($data['valid_ids']['valid_id_beside_your_face_image']); $i++){
				$data['valid_ids']['valid_id_beside_your_face_image'][$i]['user_id'] = $lastInsertedId;
			}
			
			$this->db->insert_batch('user_valid_id', $data['valid_ids']['valid_id_beside_your_face_image']);
		}catch(PDOException $e){
			$msg = $e->getMessage();
			$this->db->trans_rollback();
		}
	}
}
