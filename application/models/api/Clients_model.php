<?php
class Clients_model extends CI_Model {
	

	public function get_all_valid_ids(){
		$params = array('Y');
		$query = "
			SELECT 
				valid_id_code,valid_id_name,active_flag
			FROM 
				param_valid_ids 
			WHERE 
				active_flag = ?
			ORDER BY valid_id_name ASC";

    	$stmt = $this->db->query($query, $params);
    	return $stmt->result();
	}

	public function get_all_regions(){
		$query = "
			SELECT 
				id,regDesc as region_name,regCode
			FROM 
				param_region 
			ORDER BY regDesc ASC";

    	$stmt = $this->db->query($query);
    	return $stmt->result();
	}

  	public function get_all_provinces($region_code) {
		$params = array($region_code);

		$query = "
			SELECT 
				id,provDesc,provCode
			FROM 
				param_province 
			WHERE regCode = ?
			ORDER BY provDesc ASC";

    	$stmt = $this->db->query($query, $params);
    	return $stmt->result();
	}

	public function get_city_muni_by_province_code($province_code){
		$params = array($province_code);

		$query = "
			SELECT 
				id,citymunDesc,provCode,citymunCode
			FROM 
				param_city_muni 
			WHERE provCode = ? 
			ORDER BY citymunDesc ASC";

    	$stmt = $this->db->query($query, $params);
    	return $stmt->result();
	}

	public function get_barangay_by_city_muni_code($city_muni_code){
		$params = array($city_muni_code);

		$query = "
			SELECT 
				id,brgyDesc,provCode,citymunCode
			FROM 
				param_barangay 
			WHERE citymunCode = ? 
			ORDER BY brgyDesc ASC";

    	$stmt = $this->db->query($query, $params);
    	return $stmt->result();
	}
}
