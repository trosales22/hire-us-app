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
					A.location,DATE_FORMAT(A.birth_date, '%M %d, %Y') as birth_date,
					A.email,A.contact_number,A.gender,IFNULL(B.talent_display_photo, '') as talent_display_photo
				FROM 
					talents A 
				LEFT JOIN 
					talents_resources B ON A.talent_id = B.talent_id 
				WHERE 
					A.active_flag = ? 
				ORDER BY talent_id DESC
			";
		
		$stmt = $this->db->query($query, $params);
		return $stmt->result();
	}

	public function getTalentCategories($talent_id){
		$params = array($talent_id);

		$query = "
			SELECT 
				A.talent_id,B.category_id,B.category_name 
			FROM 
				talents_category A 
			LEFT JOIN 
				param_categories B ON A.category_id = B.category_id 
			WHERE A.talent_id = ?
		";
		
		$stmt = $this->db->query($query, $params);
		return $stmt->result();
	}
}
