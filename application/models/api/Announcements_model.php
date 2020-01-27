<?php
class Announcements_model extends CI_Model {
	public function get_announcements(array $announcement_params = NULL){
		$where_condition = '';

		if(!empty($announcement_params['announcement_id'])){
			$where_condition .= "AND A.announcement_id = " . $announcement_params['announcement_id'] . "";
		}
		
		if(!empty($announcement_params['announcement_caption'])){
			$where_condition .= "AND A.announcement_caption LIKE '%" . $announcement_params['announcement_caption'] . "%'";
		}
		
		$query = "
			SELECT 
				A.announcement_id, A.announcement_caption, A.announcement_details,
				B.user_id, CONCAT(B.firstname, ' ', B.lastname) as announcement_creator, 
				DATE_FORMAT(A.created_date, '%M %d, %Y %r') as announcement_created_date
			FROM 
				announcements A
			LEFT JOIN 
				users B ON A.created_by = B.user_id 
			WHERE 
				A.active_flag = 'Y' $where_condition 
			ORDER BY A.announcement_id DESC
			";
		
		$stmt = $this->db->query($query);
		return $stmt->result();
	}
	
	public function add_announcement(array $data){
		try{
			$announcement_fields = array(
				'announcement_caption' 	=> $data['announcement_caption'],
				'announcement_details' 	=> $data['announcement_details'],
				'created_by' 			=> $data['created_by']
			);
			
			$this->db->insert('announcements', $announcement_fields);
			$lastInsertedId = $this->db->insert_id();
		}catch(PDOException $e){
			$msg = $e->getMessage();
			$this->db->trans_rollback();
		}
	}
}
