<?php
class Client_individual_model extends CI_Model {

  	public function getAllProvinces() {
		$query = "
			SELECT 
				id,provDesc,provCode
			FROM 
				param_province 
			ORDER BY provDesc ASC";

    	$stmt = $this->db->query($query);
    	return $stmt->result();
	}

	public function getCityMuniByProvinceCode($province_code){
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

	public function getBarangayByCityMuniCode($city_muni_code){
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
