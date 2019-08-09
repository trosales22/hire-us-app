<?php
class Talents_model extends CI_Model {
	public function getAllTalents() {
		$params = array('Y');

		$query = "
				SELECT
					A.talent_id,CONCAT(A.firstname, ' ', A.lastname) as fullname,
					A.height,A.talent_fee,
					CASE A.talent_fee_type
						WHEN 'HOURLY_RATE' THEN 'PER HOUR'
						WHEN 'DAILY_RATE' THEN 'PER DAY'
					END as talent_fee_type,
					IFNULL(B.talent_description, '') as talent_description,
					A.location,DATE_FORMAT(A.birth_date, '%M %d, %Y') as birth_date,
					YEAR(CURDATE()) - YEAR(A.birth_date) as age,
					A.email,A.contact_number,A.gender,IFNULL(B.talent_display_photo, '') as talent_display_photo,
					GROUP_CONCAT(C.category_id SEPARATOR ', ') as category_ids,
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
					A.active_flag = ? 
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
					A.height,A.talent_fee,
					CASE A.talent_fee_type
						WHEN 'HOURLY_RATE' THEN 'PER HOUR'
						WHEN 'DAILY_RATE' THEN 'PER DAY'
					END as talent_fee_type,
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
