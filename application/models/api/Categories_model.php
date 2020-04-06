<?php
date_default_timezone_set("Asia/Manila");
require APPPATH . 'models/Tables.php';

class Categories_model extends CI_Model {
	public function getAllCategories() {
		$params = array('Y');

		$query = "
			SELECT 
				category_id, category_name 
			FROM 
				" . Tables::$PARAM_CATEGORIES . "  
			WHERE 
				active_flag = ? 
			ORDER BY 
				category_id ASC";
		
		$stmt = $this->db->query($query, $params);
		return $stmt->result();
	}

	public function getTalentCategories($talent_id){
		$params = array($talent_id);

		$query = "
			SELECT 
				A.talent_id, B.category_id, B.category_name 
			FROM 
				" . Tables::$TALENTS_CATEGORY . " A 
			LEFT JOIN 
				" . Tables::$PARAM_CATEGORIES . " B ON A.category_id = B.category_id 
			WHERE 
				A.talent_id = ?";
		
		$stmt = $this->db->query($query, $params);
		return $stmt->result();
	}
}
