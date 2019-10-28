<?php
class Client_individual_model extends CI_Model {
	public function add_individual_client(array $data){
		//insert to talents table
		$client_fields = array(
			'firstname' 		=> $data['firstname'],
			'lastname' 			=> $data['lastname'],
			'email' 			=> $data['email'],
			'contact_number' 	=> $data['contact_number'],
			'username' 			=> $data['username'],
			'password' 			=> password_hash($data['password'], PASSWORD_BCRYPT),
			'gender' 			=> $data['gender'],
			'active_flag'		=> 'N'
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
			'region'			=> $data['address']['region'],
			'province' 			=> $data['address']['province'],
			'city_muni' 		=> $data['address']['city_muni'],
			'barangay' 			=> $data['address']['barangay'],
			'bldg_village' 		=> $data['address']['bldg_village'],
			'zip_code' 			=> $data['address']['zip_code']
		);

		$this->db->insert('user_address', $client_address_fields);

		//insert to user_valid_id table
		$individual_government_issued_id_fields = array(
			'user_id' 		=> $lastInsertedId,
			'id_type'		=> $data['individual_government_issued_id'],
			'file_name'		=> $data['individual_government_issued_id_image']
		);

		$this->db->insert('user_valid_id', $individual_government_issued_id_fields);
		
		for($i = 0; $i < count($data['valid_id_beside_your_face_image']); $i++){
			$data['valid_id_beside_your_face_image'][$i]['user_id'] = $lastInsertedId;
		}
		
		$this->db->insert_batch('user_valid_id', $data['valid_id_beside_your_face_image']);
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

	public function add_to_client_booking_list(array $booking_params, array $email_params){
		$client_booking_params = array(
			'talent_id' 		=> $booking_params['talent_id'],
			'client_id' 		=> $booking_params['client_id'],
			'preferred_date' 	=> $booking_params['preferred_date'],
			'preferred_time' 	=> $booking_params['preferred_time'],
			'payment_option' 	=> $booking_params['payment_option'],
			'total_amount' 		=> $booking_params['total_amount']
		);

		$this->db->insert('client_booking_list', $client_booking_params);
		$lastInsertedId = $this->db->insert_id();
		
		$this->_send_successful_booking_to_client_email_notif($booking_params, $email_params);
	}
	
	public function get_booking_list_by_client_id($client_id){
		$params = array($client_id);

		$query = "
			SELECT 
				A.booking_id, A.client_id, A.talent_id, A.total_amount,
				A.preferred_date, A.preferred_time, A.payment_option,
				DATE_FORMAT(A.created_date, '%M %d, %Y %r') as date_paid
			FROM 
				client_booking_list A 
			WHERE 
				A.client_id = ? 
			ORDER BY 
				A.booking_id DESC";

    	$stmt = $this->db->query($query, $params);
    	return $stmt->result();
	}

	public function get_already_reserved_schedule($talent_id){
		$params = array($talent_id);
		$query = "
			SELECT 
				A.booking_id, A.talent_id, A.preferred_date, A.preferred_time, A.created_date
			FROM 
				client_booking_list A 
			WHERE 
				A.talent_id = ? AND A.created_date >= CURDATE()";
		
    	$stmt = $this->db->query($query, $params);
    	return $stmt->result();
	}

	private function _send_successful_booking_to_client_email_notif(array $booking_params, array $email_params){
		try{
			$success = 0;
			$from = "support@hireusph.com";
			$to = $email_params['client_details']->email;
			$honorific = '';
			$message = '';
			$subject = "Hire Us | Congratulations for a successful booking!";
			
			$message = "Hi " . $email_params['client_details']->fullname . "!\n\n";
			$message .= "Below are your booking details:\n\n";
			$message .= "Schedule:\n" . $booking_params['preferred_date'] . '\n' . $booking_params['preferred_time']  . "\n";
			$message .= "Talent Fullname: " . $email_params['talent_details']->fullname . "\n";
			$message .= "Talent Category: " . $email_params['talent_details']->category_names . "\n";
			$message .= "Rate per hour: ₱" . $email_params['talent_details']->hourly_rate . "\n";
			$message .= "Payment Method: " . $booking_params['payment_option'] . "\n";
			$message .= "Total Amount: ₱" . $booking_params['total_amount'] . "\n";
			$message .= "Thank you for supporting Hire Us PH.\n";
			
			$headers = "From:" . $from;
			mail($to, $subject, $message, $headers);
			$success  = 1;
		}catch (Exception $e){
			$msg = $e->getMessage();      
		}
	}
}
