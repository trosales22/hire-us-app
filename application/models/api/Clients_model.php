<?php
date_default_timezone_set("Asia/Manila");
require APPPATH . 'models/Tables.php';

class Clients_model extends CI_Model {
	public function get_all_valid_ids(){
		$params = array('Y');
		$query = "
			SELECT 
				valid_id_code,valid_id_name,active_flag
			FROM 
				param_valid_ids 
			WHERE 
				active_flag = ?
			ORDER BY valid_id_name ASC";

    	$stmt = $this->db->query($query, $params);
    	return $stmt->result();
	}

	public function get_all_regions(){
		$query = "
			SELECT 
				id,regDesc as region_name,regCode
			FROM 
				param_region 
			ORDER BY regDesc ASC";

    	$stmt = $this->db->query($query);
    	return $stmt->result();
	}

  	public function get_all_provinces($region_code) {
		$params = array($region_code);

		$query = "
			SELECT 
				id,provDesc,provCode
			FROM 
				param_province 
			WHERE regCode = ?
			ORDER BY provDesc ASC";

    	$stmt = $this->db->query($query, $params);
    	return $stmt->result();
	}

	public function get_city_muni_by_province_code($province_code){
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

	public function get_barangay_by_city_muni_code($city_muni_code){
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

	public function add_individual_client(array $data){
		try{
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
		}catch(PDOException $e){
			$msg = $e->getMessage();
			$this->db->trans_rollback();
		}
	}

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
	
	public function get_booking_list_by_client_id($client_id){
		$params = array($client_id);

		$query = "
			SELECT 
				A.booking_id, A.client_id, A.talent_id, A.booking_generated_id, 
				A.booking_event_title, A.booking_talent_fee, A.booking_venue_location,
				IFNULL(A.booking_payment_option, 'N/A') as booking_payment_option,
				A.booking_date, A.booking_time, 
				IFNULL(A.booking_other_details, 'N/A') as booking_other_details,
				A.booking_offer_status, DATE_FORMAT(A.booking_created_date, '%M %d, %Y %r') as booking_created_date,
				IFNULL(A.booking_decline_reason, 'N/A') as booking_decline_reason,
				IF(A.booking_approved_or_declined_date = NULL, 'N/A', DATE_FORMAT(A.booking_approved_or_declined_date, '%M %d, %Y %r')) as booking_approved_or_declined_date,
				IF(A.booking_date_paid = NULL, 'PENDING', DATE_FORMAT(A.booking_date_paid, '%M %d, %Y %r')) as booking_date_paid,
                DATE_FORMAT(DATE_ADD(A.booking_approved_or_declined_date, INTERVAL 24 hour), '%M %d, %Y %r') as booking_pay_on_or_before,
                NOW() as datetime_today,
                IF(NOW() > DATE_FORMAT(DATE_ADD(A.booking_approved_or_declined_date, INTERVAL 24 hour), '%Y-%m-%d %T'), 'EXPIRED', 'ACTIVE') as booking_payment_status
			FROM 
				" . Tables::$CLIENT_BOOKING_LIST . " A 
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
				" . Tables::$CLIENT_BOOKING_LIST . " A 
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
			$message = '';
			$subject = "Hire Us | Congratulations for a successful booking!";
			
			$message = "Hi " . $email_params['client_details']->fullname . "!\n\n";
			$message .= "Below are your booking details:\n\n";
			$message .= "Schedule:\n" . $booking_params['preferred_date'] . '\n' . $booking_params['preferred_time']  . "\n";
			$message .= "Talent Fullname: " . $email_params['talent_details']->fullname . "\n";
			$message .= "Talent Category: " . $email_params['talent_details']->category_names . "\n";
			$message .= "Payment Method: " . $booking_params['payment_option'] . "\n";
			$message .= "Venue: " . $booking_params['preferred_venue'] . "\n";
			$message .= "Total Amount: ₱" . $booking_params['total_amount'] . "\n";
			$message .= "Thank you for supporting Hire Us PH.\n";
			
			$headers = "From:" . $from;
			mail($to, $subject, $message, $headers);
			$success  = 1;
		}catch (Exception $e){
			$msg = $e->getMessage();      
		}
	}

	private function _send_successful_booking_to_talent_email_notif(array $booking_params, array $email_params){
		try{
			$success = 0;
			$from = "support@hireusph.com";
			$to = $email_params['talent_details']->email;
			$honorific = '';
			$message = '';
			$subject = "Hire Us | Congratulations! You have a client!";

			switch($email_params['talent_details']->gender){
				case 'Male':
					$honorific = 'Mr. ';
					break;
				case 'Female':
					$honorific = 'Ms/Mrs. ';
					break;
			}
			
			$message = "Hi " . $honorific . $email_params['talent_details']->fullname . "!\n\n";
			$message .= "Below are your booking details:\n\n";
			$message .= "Schedule:\n" . $booking_params['preferred_date'] . '\n' . $booking_params['preferred_time']  . "\n";
			$message .= "Client Fullname: " . $email_params['client_details']->fullname . "\n";
			$message .= "Client Type: " . $email_params['client_details']->role_name . "\n";
			$message .= "Client Contact Number: " . $email_params['client_details']->contact_number . "\n";
			$message .= "Payment Method: " . $booking_params['payment_option'] . "\n";
			$message .= "Venue: " . $booking_params['preferred_venue'] . "\n";
			$message .= "Total Amount: ₱" . $booking_params['total_amount'] . "\n";
			$message .= "Congratulations from Hire Us PH.\n";
			
			$headers = "From:" . $from;
			mail($to, $subject, $message, $headers);
			$success  = 1;
		}catch (Exception $e){
			$msg = $e->getMessage();      
		}
	}

	private function _send_pending_booking_to_client_email_notif(array $booking_params, array $email_params){
		try{
			$success = 0;
			$from = "support@hireusph.com";
			$to = $email_params['client_details']->email;
			$message = '';
			$subject = "Hire Us | Congratulations for a successful booking!";

			$message = "Hi " . $email_params['client_details']->fullname . "!\n\n";
			$message .= "Below are your booking details:\n\n";
			$message .= "Schedule:\n" . $booking_params['temp_booking_date'] . '\n' . $booking_params['temp_booking_time']  . "\n";
			$message .= "Talent Fullname: " . $email_params['talent_details']->fullname . "\n";
			$message .= "Talent Category: " . $email_params['talent_details']->category_names . "\n";
			$message .= "Payment Method: " . $booking_params['temp_payment_option'] . "\n";
			$message .= "Venue: " . $booking_params['temp_booking_venue'] . "\n";
			$message .= "Total Amount: ₱" . $booking_params['temp_total_amount'] . "\n";
			$message .= "Status: PENDING" . "\n\n";
			$message .= "Note: You have 48hrs to pay your booked talent/model. Otherwise, your booking will be forfeited.\n";
			$message .= "Thank you for supporting Hire Us PH.\n";
			
			$headers = "From:" . $from;
			mail($to, $subject, $message, $headers);
			$success  = 1;
		}catch (Exception $e){
			$msg = $e->getMessage();
			$this->db->trans_rollback();
		}
	}
}
