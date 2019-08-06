<?php
class Categories_model extends CI_Model {
	public function getAllCategories() {
		$params = array('Y');

		$query = "
			SELECT 
				category_id,category_name 
			FROM 
				param_categories 
			WHERE 
				active_flag = ? 
			ORDER BY category_id ASC
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
