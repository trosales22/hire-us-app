<?php
class Talents_model extends CI_Model {
	public function get_all_talents($selected_categories = NULL, $additional_filtering = NULL) {
		$params = array('Y');

		$where_selected_categories = '';
		$where_additional_filtering = '';
		$filtering_category_arr = array();
		
		if($selected_categories != NULL){
			$selected_categories_arr = explode(',', $selected_categories);

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
					A.talent_id, CONCAT(A.firstname, ' ', A.lastname) as fullname,
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
				A.talent_id,CONCAT(A.firstname, ' ', A.lastname) as fullname,
				A.height,A.hourly_rate,A.gender,
				IFNULL(A.description, '') as talent_description,
				
				G.provDesc as province, H.citymunDesc as city_muni, I.brgyDesc as barangay,
				F.bldg_village, F.zip_code,

				YEAR(CURDATE()) - YEAR(A.birth_date) as age, A.email,
				IF( ISNULL(B.talent_display_photo), '', CONCAT('" . base_url() . "uploads/talents_or_models/', B.talent_display_photo) ) as talent_display_photo,

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
			WHERE 
				A.talent_id = ?
			GROUP BY A.talent_id
		";
		
		$stmt = $this->db->query($query, $params);
		return $stmt->result();
	}

	public function getTalentUnavailableDates($talent_id){
		$params = array($talent_id);

		$query = "
			SELECT
				ud_talent_id,ud_sched,ud_month_year,ud_created_date
			FROM 
				talents_unavailable_dates 
			WHERE 
				ud_talent_id = ?
			ORDER BY ud_id DESC
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
}
