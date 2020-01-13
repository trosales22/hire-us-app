<?php
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
			$message .= "Rate per hour: PHP" . $data['hourly_rate'] . "\n";
			$message .= "Password: HIRE_US@123\n\nYou can now login your account as a Talent/Model. Thank you & welcome to Hire Us PH.\n";
			
			$headers = "From:" . $from;
			mail($to, $subject, $message, $headers);
			$success  = 1;
		}catch (Exception $e){
			$msg = $e->getMessage();      
		}
	}

	public function add_talent(array $data) {
		try{
			// print "<pre>";
			// die(print_r($data));

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
				'hourly_rate' 			=> $data['hourly_rate'],
				'vital_stats'			=> $data['vital_stats'],
				'fb_followers'			=> $data['fb_followers'],
				'instagram_followers'	=> $data['instagram_followers'],
				'genre'					=> $data['genre'],
				'description'			=> $data['description'],
				'created_by' 			=> $data['created_by']
			);
			
			$this->db->insert('talents', $talents_fields);
			$lastInsertedId = $this->db->insert_id();
				
			//insert to talents_category table
			foreach($data['categories'] as $category){
				$talents_category_fields = array(
					'talent_id' => $lastInsertedId,
					'category_id' => $category,
				);
				
				$this->db->insert('talents_category', $talents_category_fields);
			}
	
			//insert to talents_account table
			$generated_pin = 'HIRE_US@123';
	
			$talents_account_fields = array(
				'talent_id' => $lastInsertedId,
				'talent_password' => password_hash($generated_pin, PASSWORD_BCRYPT),
			);
			
			$this->db->insert('talents_account', $talents_account_fields);
			
			//insert to talents_exp_or_prev_clients table
			$talents_prev_clients_fields = array(
				'talent_id' 	=> $lastInsertedId,
				'details'		=> $data['prev_clients']
			);
	
			$this->db->insert('talents_exp_or_prev_clients', $talents_prev_clients_fields);
			
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
	
			$this->db->insert('talents_address', $talents_address_fields);

			//insert to event_images table
			$talent_resources_fields = array(
				'talent_id' 				=> $lastInsertedId,
				'talent_display_photo'		=> $data['talent_profile_img'],
				'created_by'				=> $data['created_by']
			);

			$this->db->insert('talents_resources', $talent_resources_fields);
			
			for($i = 0; $i < count($data['talent_gallery']); $i++){
				$data['talent_gallery'][$i]['talent_id'] = $lastInsertedId;
			}
			
			$this->db->insert_batch('talents_gallery', $data['talent_gallery']);
			//$this->_send_added_talent_email_notif($data);
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
		
		if(!empty($additional_filtering['rate_per_hour_from'])){
			if(!empty($additional_filtering['rate_per_hour_to'])){
				$where_additional_filtering .= ' AND A.hourly_rate BETWEEN "' . $additional_filtering['rate_per_hour_from'] . '" AND "' . $additional_filtering['rate_per_hour_to'] . '"';
			}else{
				$where_additional_filtering .= ' AND A.hourly_rate >= "' . $additional_filtering['rate_per_hour_from'] . '"';
			}
		}

		if(!empty($additional_filtering['province_code'])){
			$where_additional_filtering .= ' AND F.province = "' . $additional_filtering['province_code'] . '"';
		}

		if(!empty($additional_filtering['city_muni_code'])){
			$where_additional_filtering .= ' AND F.city_muni = "' . $additional_filtering['city_muni_code'] . '"';
		}

		if(!empty($additional_filtering['gender'])){
			$where_additional_filtering .= ' AND A.gender = "' . $additional_filtering['gender'] . '"';
		}

		$query = "
				SELECT
					A.talent_id, CONCAT(A.firstname, ' ', A.lastname) as fullname, A.screen_name,
					A.height, A.hourly_rate, IFNULL(A.description, '') as talent_description,
					YEAR(CURDATE()) - YEAR(A.birth_date) as age, A.gender,
					IF( ISNULL(B.talent_display_photo), '', CONCAT('" . base_url() . "uploads/talents_or_models/', B.talent_display_photo) ) as talent_display_photo,
					GROUP_CONCAT(D.category_name SEPARATOR '\n') as category_names,
					F.province as province_code, F.city_muni as city_muni_code,
					G.provDesc as province, H.citymunDesc as city_muni, I.brgyDesc as barangay,
					F.bldg_village, F.zip_code
				FROM 
					talents A 
				LEFT JOIN 
					talents_resources B ON A.talent_id = B.talent_id 
				LEFT JOIN 
					talents_category C ON A.talent_id = C.talent_id 
				LEFT JOIN 
					param_categories D ON C.category_id = D.category_id 
				LEFT JOIN 
					talents_address F ON A.talent_id = F.talent_id 
				LEFT JOIN 
					param_province G ON F.province = G.provCode 
				LEFT JOIN
					param_city_muni H ON F.city_muni = H.citymunCode 
				LEFT JOIN 
					param_barangay I ON F.barangay = I.id 
				WHERE 
					A.active_flag = ? $where_selected_categories $where_additional_filtering 
				GROUP BY A.talent_id 
				ORDER BY talent_id DESC
			";
		
		$stmt = $this->db->query($query, $params);
		return $stmt->result();
	}

	public function getTalentDetails($talent_id){
		$params = array($talent_id);

		$query = "
			SELECT
				A.talent_id,
				IFNULL(A.firstname, '') as firstname, IFNULL(A.middlename, '') as middlename, IFNULL(A.lastname, '') as lastname, 
				CONCAT(A.firstname, ' ', A.lastname) as fullname, A.screen_name,
				A.height, A.hourly_rate, A.gender, A.contact_number,
				IFNULL(A.description, '') as talent_description,

				J.regCode as region_code, J.regDesc as region, 
				G.provCode as province_code, G.provDesc as province, 
				H.citymunCode as city_muni_code, H.citymunDesc as city_muni, 
				I.id as barangay_code, I.brgyDesc as barangay,
				F.bldg_village, F.zip_code, A.birth_date, 
				YEAR(CURDATE()) - YEAR(A.birth_date) as age, A.email,
				IF( ISNULL(B.talent_display_photo), '', CONCAT('" . base_url() . "uploads/talents_or_models/', B.talent_display_photo) ) as talent_display_photo,

				GROUP_CONCAT(D.category_id SEPARATOR '|') as category_ids,
				GROUP_CONCAT(D.category_name SEPARATOR '\n') as category_names,
				IFNULL(E.details, 'N/A') as talent_experiences,

				IFNULL(A.vital_stats, 'N/A') as vital_stats,
				IFNULL(A.fb_followers, 0) as fb_followers,
				IFNULL(A.instagram_followers, 0) as instagram_followers,
				IFNULL(A.genre, 'N/A') as genre
			FROM 
				talents A 
			LEFT JOIN 
				talents_resources B ON A.talent_id = B.talent_id 
			LEFT JOIN 
				talents_category C ON A.talent_id = C.talent_id 
			LEFT JOIN 
				param_categories D ON C.category_id = D.category_id 
			LEFT JOIN 
				talents_exp_or_prev_clients E ON A.talent_id = E.talent_id 
			LEFT JOIN 
				talents_address F ON A.talent_id = F.talent_id 
			LEFT JOIN 
				param_province G ON F.province = G.provCode 
			LEFT JOIN
				param_city_muni H ON F.city_muni = H.citymunCode 
			LEFT JOIN 
				param_barangay I ON F.barangay = I.id 
			LEFT JOIN 
				param_region J ON F.region = J.regCode 
			WHERE 
				A.talent_id = ?
			GROUP BY A.talent_id
		";
		
		$stmt = $this->db->query($query, $params);
		return $stmt->result();
	}

	public function get_all_talent_by_category($category_id){
		$params = array($category_id);

		$query = "
			SELECT
				A.tc_id, A.talent_id, A.category_id,
				B.firstname, B.lastname
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
			B.booking_id, A.user_id as client_id, B.talent_id, 
			CONCAT(A.firstname, ' ', A.lastname) as fullname, 
			IFNULL(A.gender, '') as gender, D.role_id, D.role_name,
			B.preferred_date, B.preferred_time, 
			B.payment_option, B.total_amount, DATE_FORMAT(B.created_date, '%M %d, %Y %r') as date_paid
		FROM 
			users A 
		LEFT JOIN 
			client_booking_list B ON A.user_id = B.client_id 
		LEFT JOIN 
			user_role C ON A.user_id = C.user_id 
		LEFT JOIN 
			param_roles D ON C.role_code = D.role_id 
		WHERE 
			B.talent_id = ? 
		ORDER BY B.booking_id DESC
		";
		
		$stmt = $this->db->query($query, $params);
		return $stmt->result();
	}

	public function add_talent_reviews(array $data){
		try{
			$this->db->insert('client_reviews', $data);
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
				client_reviews 
			WHERE 
				review_to = ?
			ORDER BY review_id DESC";

		$stmt = $this->db->query($query, $params);
		return $stmt->result();
	}
}
