<?php
class Bookings_model extends CI_Model {

	public function get_pending_bookings(){
		$query = "
			SELECT 
				temp_booking_id, temp_talent_id,    
				temp_client_id, temp_booking_date, 
				temp_booking_time, temp_booking_venue, 
				temp_total_amount, temp_status, temp_payment_option,
				DATE_FORMAT(temp_created_date, '%M %d, %Y %r') as created_date
			FROM 
				temp_booking_list";

		$stmt = $this->db->query($query);
		return $stmt->result();
	}
	
	public function get_paid_bookings(){
		$query = "
			SELECT 
				booking_id, talent_id, client_id, 
				preferred_date, preferred_time, 
				preferred_venue, total_amount, payment_option,
				DATE_FORMAT(created_date, '%M %d, %Y %r') as created_date
			FROM 
				client_booking_list";

		$stmt = $this->db->query($query);
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
}
