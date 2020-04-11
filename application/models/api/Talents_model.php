<?php
date_default_timezone_set("Asia/Manila");
include_once APPPATH . 'models/Tables.php';

class Talents_model extends CI_Model {
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
			$message .= "Password: HIRE_US@123\n\nYou can now login your account as a Talent/Model. Thank you & welcome to Hire Us PH.\n";
			
			$headers = "From:" . $from;
			mail($to, $subject, $message, $headers);
			$success  = 1;
		}catch (Exception $e){
			$msg = $e->getMessage();      
		}
	}

	public function detect_if_talent_profile_pic_exist($talent_id){
		$params = array($talent_id);

		$query = "
			SELECT 
				IF( ISNULL(talent_display_photo), 'NO IMAGE', CONCAT('" . base_url() . "uploads/talents_or_models/', talent_display_photo) ) as talent_display_photo,
				IF( ISNULL(talent_display_photo), 'NO IMAGE', talent_display_photo) as talent_display_photo_raw 
			FROM
				" . Tables::$TALENTS_RESOURCES . " 
			WHERE 
				talent_id = ?
		";

		$stmt = $this->db->query($query, $params);
		return $stmt->result();
	}

	public function add_talent(array $data) {
		try{
			//insert to talents table
			$talents_fields = array(
				'firstname' 			=> $data['firstname'],
				'middlename' 			=> $data['middlename'],
				'lastname' 				=> $data['lastname'],
				'screen_name'			=> $data['screen_name'],
				'email' 				=> $data['email'],
				'contact_number' 		=> $data['contact_number'],
				'gender' 				=> $data['gender'],
				'height' 				=> $data['height'],
				'birth_date' 			=> $data['birth_date'],
				'vital_stats'			=> $data['vital_stats'],
				'fb_followers'			=> $data['fb_followers'],
				'instagram_followers'	=> $data['instagram_followers'],
				'genre'					=> $data['genre'],
				'description'			=> $data['description'],
				'created_by' 			=> $data['created_by']
			);
			
			$this->db->insert(Tables::$TALENTS, $talents_fields);
			$lastInsertedId = $this->db->insert_id();

			//insert to talents_category table
			$talent_category = array();

			foreach($data['categories'] as $category){
				array_push($talent_category, $category);
			}

			$talents_category_fields = array(
				'talent_id'		=> $lastInsertedId,
				'category_id'	=> implode('*', $talent_category)
			);
			
			$this->db->insert(Tables::$TALENTS_CATEGORY, $talents_category_fields);
	
			//insert to talents_account table
			$generated_pin = 'HIRE_US@123';
	
			$talents_account_fields = array(
				'talent_id' => $lastInsertedId,
				'talent_password' => password_hash($generated_pin, PASSWORD_BCRYPT),
			);
			
			$this->db->insert(Tables::$TALENTS_ACCOUNT, $talents_account_fields);
			
			//insert to talents_exp_or_prev_clients table
			$talents_prev_clients_fields = array(
				'talent_id' 	=> $lastInsertedId,
				'details'		=> $data['prev_clients']
			);
	
			$this->db->insert(Tables::$TALENTS_EXP_OR_PREV_CLIENTS, $talents_prev_clients_fields);
			
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
	
			$this->db->insert(Tables::$TALENTS_ADDRESS, $talents_address_fields);

			//insert to talents_resources table
			$talent_resources_fields = array(
				'talent_id' 				=> $lastInsertedId,
				'talent_display_photo'		=> $data['talent_profile_img'],
				'created_by'				=> $data['created_by']
			);

			$this->db->insert(Tables::$TALENTS_RESOURCES, $talent_resources_fields);
			
			for($i = 0; $i < count($data['talent_gallery']); $i++){
				$data['talent_gallery'][$i]['talent_id'] = $lastInsertedId;
			}
			
			$this->db->insert_batch(Tables::$TALENTS_GALLERY, $data['talent_gallery']);
			//$this->_send_added_talent_email_notif($data);
		}catch(PDOException $e){
			$msg = $e->getMessage();
			$this->db->trans_rollback();
		}catch(Exception $e){
			$msg = $e->getMessage();
			$this->db->trans_rollback();
		}
	}

	public function modify_talent(array $data){
		try{
			$talent_id = $data['talent_id'];

			$talents_fields = array(
				'firstname' 			=> $data['firstname'],
				'middlename' 			=> $data['middlename'],
				'lastname' 				=> $data['lastname'],
				'screen_name'			=> $data['screen_name'],
				'email' 				=> $data['email'],
				'contact_number' 		=> $data['contact_number'],
				'gender' 				=> $data['gender'],
				'height' 				=> $data['height'],
				'birth_date' 			=> $data['birth_date'],
				'vital_stats'			=> $data['vital_stats'],
				'fb_followers'			=> $data['fb_followers'],
				'instagram_followers'	=> $data['instagram_followers'],
				'genre'					=> $data['genre'],
				'description'			=> $data['description'],
				'modified_date' 		=> $data['modified_date'],
				'modified_by'			=> $data['modified_by']
			);

			//update talents
			$this->db->where('talent_id', $talent_id);
			$this->db->update(Tables::$TALENTS, $talents_fields);

			$talents_address_fields = array(
				'region'			=> $data['address']['region'],
				'province' 			=> $data['address']['province'],
				'city_muni' 		=> $data['address']['city_muni'],
				'barangay' 			=> $data['address']['barangay'],
				'bldg_village' 		=> $data['address']['bldg_village'],
				'zip_code' 			=> $data['address']['zip_code']
			);

			//update talents_address
			$this->db->where('talent_id', $talent_id);
			$this->db->update(Tables::$TALENTS_ADDRESS, $talents_address_fields);

			$talents_prev_clients_fields = array(
				'talent_id' 	=> $talent_id,
				'details'		=> $data['prev_clients']
			);

			//update talents_exp_or_prev_clients
			$this->db->where('talent_id', $talent_id);
			$this->db->update(Tables::$TALENTS_EXP_OR_PREV_CLIENTS, $talents_prev_clients_fields);

			$talent_category = array();

			foreach($data['categories'] as $category){
				array_push($talent_category, $category);
			}

			$talent_category_fields = array(
				'category_id'	=> implode('*', $talent_category)
			);

			//update talents_category
			$this->db->where('talent_id', $talent_id);
			$this->db->update(Tables::$TALENTS_CATEGORY, $talent_category_fields);
			
			//for uploading talent profile picture
			$talent_resources_fields = array();
			$detect_if_talent_profile_pic_exist = $this->detect_if_talent_profile_pic_exist($talent_id);

			if(!EMPTY($detect_if_talent_profile_pic_exist)){
				if(!EMPTY($data['talent_profile_img'])){
					$talent_resources_fields = array(
						'talent_display_photo'		=> $data['talent_profile_img'],
						'modified_by'				=> $data['modified_by'],
						'modified_date'				=> $data['modified_date']
					);
					
					//update talents_resources
					$this->db->where('talent_id', $talent_id);
					$this->db->update(Tables::$TALENTS_RESOURCES, $talent_resources_fields);
				}
			}else{
				$talent_resources_fields = array(
					'talent_id' 				=> $talent_id,
					'talent_display_photo'		=> $data['talent_profile_img'],
					'created_by'				=> $data['created_by']
				);

				//insert to talents_resources
				$this->db->insert(Tables::$TALENTS_RESOURCES, $talent_resources_fields);
			}
		}catch(PDOException $e){
			$msg = $e->getMessage();
			$this->db->trans_rollback();
		}catch(Exception $e){
			$msg = $e->getMessage();
			$this->db->trans_rollback();
		}
	}

	public function get_all_talents($extra_filtering = NULL, $additional_filtering = NULL) {
		$params = array('Y');

		$where_selected_categories = '';
		$where_additional_filtering = '';
		$filtering_category_arr = array();
		
		if(!empty($extra_filtering['selected_categories'])){
			$selected_categories_arr = explode(',', $extra_filtering['selected_categories']);
			
			foreach($selected_categories_arr as $category){
				array_push($filtering_category_arr, "'" . $category . "'");
			}

			$where_selected_categories = " AND D.category_name IN (" . implode(",", $filtering_category_arr) . ")";
		}

		if(!empty($additional_filtering['height_from'])){
			if(!empty($additional_filtering['height_to'])){
				$where_additional_filtering .= ' AND A.height BETWEEN "' . $additional_filtering['height_from'] . '" AND "' . $additional_filtering['height_to'] . '"';
			}else{
				$where_additional_filtering .= ' AND A.height >= "' . $additional_filtering['height_from'] . '"';
			}
		}

		if(!empty($additional_filtering['age_from'])){
			if(!empty($additional_filtering['age_to'])){
				$where_additional_filtering .= ' AND YEAR(CURDATE()) - YEAR(A.birth_date) BETWEEN ' . $additional_filtering['age_from'] . ' AND ' . $additional_filtering['age_to'];
			}else{
				$where_additional_filtering .= ' AND YEAR(CURDATE()) - YEAR(A.birth_date) >= ' . $additional_filtering['age_from'];
			}
		}

		if(!empty($additional_filtering['gender'])){
			$where_additional_filtering .= ' AND A.gender = "' . $additional_filtering['gender'] . '"';
		}

		$query = "
				SELECT
					A.talent_id, CONCAT(A.firstname, ' ', A.lastname) as fullname, A.screen_name,
					A.height, IFNULL(A.description, '') as talent_description,
					YEAR(CURDATE()) - YEAR(A.birth_date) as age, A.gender,
					IF( ISNULL(B.talent_display_photo), '', CONCAT('" . base_url() . "uploads/talents_or_models/', B.talent_display_photo) ) as talent_display_photo,
					C.category_id as category_ids,
					MAX(J.regCode) as region_code, MAX(J.regDesc) as region,
					MAX(F.province) as province_code, MAX(G.provDesc) as province,
					MAX(F.city_muni) as city_muni_code, MAX(H.citymunDesc) as city_muni,
					MAX(I.brgyDesc) as barangay,
					F.bldg_village, F.zip_code
				FROM 
					" . Tables::$TALENTS . " A 
				LEFT JOIN 
					" . Tables::$TALENTS_RESOURCES . " B ON A.talent_id = B.talent_id 
				LEFT JOIN 
					" . Tables::$TALENTS_CATEGORY . " C ON A.talent_id = C.talent_id 
				LEFT JOIN 
					" . Tables::$PARAM_CATEGORIES . " D ON C.category_id = D.category_id 
				LEFT JOIN 
					" . Tables::$TALENTS_ADDRESS . " F ON A.talent_id = F.talent_id 
				LEFT JOIN 
					" . Tables::$PARAM_PROVINCE . " G ON F.province = G.provCode 
				LEFT JOIN
					" . Tables::$PARAM_CITY_MUNI . " H ON F.city_muni = H.citymunCode 
				LEFT JOIN 
					" . Tables::$PARAM_BARANGAY . " I ON F.barangay = I.id 
				LEFT JOIN 
					" . Tables::$PARAM_REGION . " J ON F.region = J.regCode 
				WHERE 
					A.active_flag = ? $where_selected_categories $where_additional_filtering 
				GROUP BY 
					A.talent_id 
				ORDER BY 
					A.talent_id DESC";
		
		$stmt = $this->db->query($query, $params);
		return $stmt->result();
	}

	public function get_talent_details($talent_id){
		$params = array($talent_id);

		$query = "
			SELECT
				A.talent_id,
				IFNULL(A.firstname, '') as firstname, IFNULL(A.middlename, '') as middlename, IFNULL(A.lastname, '') as lastname, 
				CONCAT(A.firstname, ' ', A.lastname) as fullname, A.screen_name,
				A.height, A.gender, A.contact_number, IFNULL(A.description, '') as talent_description,

				MAX(J.regCode) as region_code, MAX(J.regDesc) as region, 
				MAX(G.provCode) as province_code, MAX(G.provDesc) as province, 
				MAX(H.citymunCode) as city_muni_code, MAX(H.citymunDesc) as city_muni, 
				I.id as barangay_code, I.brgyDesc as barangay,
				F.bldg_village, F.zip_code, A.birth_date, 
				YEAR(CURDATE()) - YEAR(A.birth_date) as age, A.email,
				IF( ISNULL(B.talent_display_photo), '', CONCAT('" . base_url() . "uploads/talents_or_models/', B.talent_display_photo) ) as talent_display_photo,

				C.category_id as category_ids,
				IFNULL(E.details, 'N/A') as talent_experiences,

				IFNULL(A.vital_stats, 'N/A') as vital_stats,
				IFNULL(A.fb_followers, 0) as fb_followers,
				IFNULL(A.instagram_followers, 0) as instagram_followers,
				IFNULL(A.genre, 'N/A') as genre
			FROM 
				" . Tables::$TALENTS . " A 
			LEFT JOIN 
				" . Tables::$TALENTS_RESOURCES . " B ON A.talent_id = B.talent_id 
			LEFT JOIN 
				" . Tables::$TALENTS_CATEGORY . " C ON A.talent_id = C.talent_id 
			LEFT JOIN 
				" . Tables::$PARAM_CATEGORIES . " D ON C.category_id = D.category_id 
			LEFT JOIN 
				" . Tables::$TALENTS_EXP_OR_PREV_CLIENTS . " E ON A.talent_id = E.talent_id 
			LEFT JOIN 
				" . Tables::$TALENTS_ADDRESS . " F ON A.talent_id = F.talent_id 
			LEFT JOIN 
				" . Tables::$PARAM_PROVINCE . " G ON F.province = G.provCode 
			LEFT JOIN
				" . Tables::$PARAM_CITY_MUNI . " H ON F.city_muni = H.citymunCode 
			LEFT JOIN 
				" . Tables::$PARAM_BARANGAY . " I ON F.barangay = I.id 
			LEFT JOIN 
				" . Tables::$PARAM_REGION . " J ON F.region = J.regCode 
			WHERE 
				A.talent_id = ?
			GROUP BY 
				A.talent_id";
		
		$stmt = $this->db->query($query, $params);
		return $stmt->result();
	}

	public function get_all_talent_by_category($category_id){
		$params = array($category_id);

		$query = "
			SELECT
				A.tc_id, A.talent_id, A.category_id, B.firstname, B.lastname
			FROM 
				talents_category A 
			LEFT JOIN 
				talents B ON A.talent_id = B.talent_id
			WHERE 
				A.category_id = ?
		";
		
		$stmt = $this->db->query($query, $params);
		return $stmt->result();
	}

	public function get_all_client_booked($talent_id){
		$params = array($talent_id);

		$query = "
			SELECT 
				A.booking_id, A.client_id, A.talent_id, A.booking_generated_id, 
				A.booking_event_title, A.booking_talent_fee, A.booking_venue_location,
				IFNULL(A.booking_payment_option, 'N/A') as booking_payment_option,
				A.booking_date, A.booking_time, 
				IFNULL(A.booking_other_details, 'N/A') as booking_other_details,
				A.booking_offer_status, DATE_FORMAT(A.booking_created_date, '%M %d, %Y %r') as booking_created_date,
				IFNULL(A.booking_decline_reason, 'N/A') as booking_decline_reason,
				IF(ISNULL(A.booking_approved_or_declined_date), 'N/A', DATE_FORMAT(A.booking_approved_or_declined_date, '%M %d, %Y %r')) as booking_approved_or_declined_date,
				IF(ISNULL(A.booking_date_paid), 'PENDING', DATE_FORMAT(A.booking_date_paid, '%M %d, %Y %r')) as booking_date_paid,
                IF(ISNULL(A.booking_approved_or_declined_date), 'NOT YET APPROVED/DECLINED', DATE_FORMAT(DATE_ADD(A.booking_approved_or_declined_date, INTERVAL 24 hour), '%M %d, %Y %r') ) as booking_pay_on_or_before,
				IF(ISNULL(A.booking_approved_or_declined_date), 'NOT YET APPROVED/DECLINED', IF(NOW() > DATE_FORMAT(DATE_ADD(A.booking_approved_or_declined_date, INTERVAL 24 hour), '%Y-%m-%d %T'), 'EXPIRED', 'ACTIVE') ) as booking_payment_status
			FROM 
				" . Tables::$CLIENT_BOOKING_LIST . " A 
			WHERE 
				A.talent_id = ? 
			ORDER BY 
				A.booking_id DESC";

    	$stmt = $this->db->query($query, $params);
    	return $stmt->result();
	}

	public function get_all_bookings_ignored_for_days(){
		$query = "
			SELECT * FROM 
				" . Tables::$CLIENT_BOOKING_LIST . "  
			WHERE 
				booking_approved_or_declined_date IS NULL AND NOW() > DATE_FORMAT(DATE_ADD(booking_created_date, INTERVAL 24 hour), '%Y-%m-%d %T')";
		$stmt = $this->db->query($query);
    	return $stmt->result();
	}

	public function delete_all_bookings_ignored($booking_id){
        try {
			$this->db->delete(Tables::$CLIENT_BOOKING_LIST, array('booking_id' => $booking_id));
        }catch(PDOException $e){
			$msg = $e->getMessage();
			$this->db->trans_rollback();
		}
	}

	public function add_talent_reviews(array $data){
		try{
			$this->db->insert(Tables::$CLIENT_REVIEWS, $data);
			$lastInsertedId = $this->db->insert_id();
		}catch(PDOException $e){
			$msg = $e->getMessage();
			$this->db->trans_rollback();
		}
	}

	public function get_talent_reviews($talent_id){
		$params = array($talent_id);

		$query = "
			SELECT 
				review_id, review_feedback, 
				review_rating, review_to, review_from, 
				DATE_FORMAT(review_date, '%M %d, %Y %r') as review_date 
			FROM 
				" . Tables::$CLIENT_REVIEWS . "  
			WHERE 
				review_to = ?
			ORDER BY 
				review_id DESC";

		$stmt = $this->db->query($query, $params);
		return $stmt->result();
	}
}
