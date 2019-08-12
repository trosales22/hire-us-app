<?php
class Talents_model extends CI_Model {
	public function getAllTalents($selected_categories = NULL, $additional_filtering = NULL) {
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

		if(!empty($additional_filtering['height'])){
			$where_additional_filtering .= ' AND A.height = "' . $additional_filtering['height'] . '"';
		}

		if(!empty($additional_filtering['age'])){
			$age_arr = explode ("-", $additional_filtering['age']);
			$where_additional_filtering .= ' AND YEAR(CURDATE()) - YEAR(A.birth_date) BETWEEN ' . $age_arr[0] . ' AND ' . $age_arr[1];
		}
		
		if(!empty($additional_filtering['talent_fee'])){
			$where_additional_filtering .= ' AND A.talent_fee <= ' . $additional_filtering['talent_fee'];
		}

		if(!empty($additional_filtering['location'])){
			$where_additional_filtering .= ' AND A.location LIKE "%' . $additional_filtering['location'] . '%"';
		}

		$query = "
				SELECT
					A.talent_id,CONCAT(A.firstname, ' ', A.lastname) as fullname,
					A.height,A.talent_fee,
					CASE A.talent_fee_type
						WHEN 'HOURLY_RATE' THEN 'PER HOUR'
						WHEN 'DAILY_RATE' THEN 'PER DAY'
					END as talent_fee_type,
					A.location,IFNULL(B.talent_description, '') as talent_description,
					YEAR(CURDATE()) - YEAR(A.birth_date) as age,
					A.gender,IFNULL(B.talent_display_photo, '') as talent_display_photo,
					GROUP_CONCAT(D.category_name SEPARATOR ', ') as category_names 
				FROM 
					talents A 
				LEFT JOIN 
					talents_resources B ON A.talent_id = B.talent_id 
				LEFT JOIN 
					talents_category C ON A.talent_id = C.talent_id 
				LEFT JOIN 
					param_categories D ON C.category_id = D.category_id 
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
					A.height,A.talent_fee,A.talent_fee_type,
					IFNULL(B.talent_description, '') as talent_description,
					A.location,YEAR(CURDATE()) - YEAR(A.birth_date) as age,
					A.email,IFNULL(B.talent_display_photo, '') as talent_display_photo,
					GROUP_CONCAT(D.category_name SEPARATOR ', ') as category_names,
					IFNULL(E.talent_experiences, 'N/A') as talent_experiences
				FROM 
					talents A 
				LEFT JOIN 
					talents_resources B ON A.talent_id = B.talent_id 
				LEFT JOIN 
					talents_category C ON A.talent_id = C.talent_id 
				LEFT JOIN 
					param_categories D ON C.category_id = D.category_id 
				LEFT JOIN 
					talents_experiences E ON A.talent_id = E.talent_id
				WHERE 
					A.talent_id = ?
				GROUP BY A.talent_id
		";
		
		$stmt = $this->db->query($query, $params);
		return $stmt->result();
	}
}
