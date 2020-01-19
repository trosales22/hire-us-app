<?php
date_default_timezone_set("Asia/Manila");

class Bookings_model extends CI_Model {
	public function add_to_booking_list(array $booking_params, array $email_params){
		try{	
			$this->db->insert('client_booking_list', $booking_params);
			$lastInsertedId = $this->db->insert_id();
			
			//$this->_send_successful_booking_to_client_email_notif($booking_params, $email_params);
			//$this->_send_successful_booking_to_talent_email_notif($booking_params, $email_params);
		}catch(PDOException $e){
			$msg = $e->getMessage();
			$this->db->trans_rollback();
		}
	}

	public function get_booking_by_booking_generated_id($booking_generated_id){
		$params = array($booking_generated_id);
		
		$query = "
			SELECT 
				booking_id, booking_generated_id, client_id, talent_id,
				booking_event_title, booking_talent_fee, booking_venue_location,
				IFNULL(booking_payment_option, 'N/A') as booking_payment_option,
				booking_date, booking_time, 
				IFNULL(booking_other_details, 'N/A') as booking_other_details,
				booking_offer_status, DATE_FORMAT(booking_created_date, '%M %d, %Y %r') as booking_created_date,
				IFNULL(booking_decline_reason, 'N/A') as booking_decline_reason,
				IFNULL(booking_approved_or_declined_date, 'N/A') as booking_approved_or_declined_date
			FROM 
				client_booking_list 
			WHERE 
				booking_generated_id = ?";
		
		$stmt = $this->db->query($query, $params);
		return $stmt->result();
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

	private function _send_successful_booking_to_client_email_notif(array $booking_params, array $email_params){
		try{
			$success = 0;
			$from = "support@hireusph.com";
			$to = $email_params['client_details']->email;
			$message = '';
			$subject = "Hire Us | Congratulations for a successful booking!";
			
			$message = "Hi " . $email_params['client_details']->fullname . "!\n\n";
			$message .= "Below are your booking details:\n\n";
			
			$message .= "Booking ID: " . $booking_params['booking_generated_id'] . "\n";
			$message .= "Talent Fullname: " . $email_params['talent_details']->fullname . "\n";
			$message .= "Talent Category: " . $email_params['talent_details']->category_names . "\n";
			$message .= "Schedule:\n" . $booking_params['booking_date'] . '\n' . $booking_params['booking_date']  . "\n";
			$message .= "Event Title: " . $booking_params['booking_event_title'] . "\n";
			$message .= "Venue: " . $booking_params['booking_venue_location'] . "\n";
			$message .= "Talent Fee: ₱" . $booking_params['booking_talent_fee'] . "\n";
			$message .= "Other Details: " . $booking_params['booking_other_details'] . "\n\n";
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

			$message .= "Booking ID: " . $booking_params['booking_generated_id'] . "\n";
			$message .= "Client Fullname: " . $email_params['client_details']->fullname . "\n";
			$message .= "Client Type: " . $email_params['client_details']->role_name . "\n";
			$message .= "Client Contact Number: " . $email_params['client_details']->contact_number . "\n";
			$message .= "Schedule:\n" . $booking_params['booking_date'] . '\n' . $booking_params['booking_time']  . "\n";
			$message .= "Event Title: " . $booking_params['booking_event_title'] . "\n";
			$message .= "Venue: " . $booking_params['booking_venue_location'] . "\n";
			$message .= "Talent Fee: ₱" . $booking_params['booking_talent_fee'] . "\n";
			$message .= "Other Details: " . $booking_params['booking_other_details'] . "\n\n";
			$message .= "Congratulations from Hire Us PH.\n";
			
			$headers = "From:" . $from;
			mail($to, $subject, $message, $headers);
			$success  = 1;
		}catch (Exception $e){
			$msg = $e->getMessage();      
		}
	}

	public function approve_booking($booking_generated_id, $client_booking_list_params, $email_params){
		$booking_params = array(
			'booking_offer_status' => 'APPROVED',
			'booking_approved_or_declined_date' => date("Y-m-d H:i:s")
		);

		$this->db->where('booking_generated_id', $booking_generated_id);
		$this->db->update('client_booking_list', $booking_params);
		
		//$this->_send_successful_booking_to_client_email_notif($client_booking_list_params, $email_params);
		//$this->_send_successful_booking_to_talent_email_notif($client_booking_list_params, $email_params);
	}

	public function decline_booking($booking_generated_id, $booking_decline_reason){
		$booking_params = array(
			'booking_offer_status' => 'DECLINED',
			'booking_decline_reason' => $booking_decline_reason,
			'booking_approved_or_declined_date' => date("Y-m-d H:i:s")
		);

		$this->db->where('booking_generated_id', $booking_generated_id);
		$this->db->update('client_booking_list', $booking_params);
	}
}
