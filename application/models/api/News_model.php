<?php
class News_model extends CI_Model {
	public function detect_if_display_pic_exist($news_id){
		$params = array($news_id);

		$query = "
			SELECT 
				IF( ISNULL(news_display_pic), 'NO IMAGE', CONCAT('" . base_url() . "uploads/news/', news_display_pic) ) as news_display_photo,
				IF( ISNULL(news_display_pic), 'NO IMAGE', news_display_pic) as news_display_photo_raw 
			FROM
				news_and_updates 
			WHERE 
				news_id = ?
		";

		$stmt = $this->db->query($query, $params);
		return $stmt->result();
	}

	public function get_news_and_updates(array $news_params = NULL){
		$params = array('Y');
		$where_condition = '';

		if(!empty($news_params['news_id'])){
			$where_condition .= "AND A.news_id = " . $news_params['news_id'] . "";
		}
		
		if(!empty($news_params['news_caption'])){
			$where_condition .= "AND A.news_caption LIKE '%" . $news_params['news_caption'] . "%'";
		}
		
		$query = "
			SELECT 
				A.news_id, A.news_caption, A.news_details, IFNULL(A.news_link, '') as news_link, 
				A.news_display_pic as news_display_pic_raw,
				IF( ISNULL(A.news_display_pic), '', CONCAT('" . base_url() . "uploads/news/', A.news_display_pic) ) as news_display_photo,
				B.user_id, CONCAT(B.firstname, ' ', B.lastname) as news_creator, A.news_author,
				DATE_FORMAT(A.created_date, '%M %d, %Y %r') as news_created_date, A.active_flag
			FROM 
				news_and_updates A
			LEFT JOIN 
				users B ON A.created_by = B.user_id 
			WHERE 
				A.active_flag = ? $where_condition 
			ORDER BY A.news_id DESC
			";
		
		$stmt = $this->db->query($query, $params);
		return $stmt->result();
	}
	
	public function add_news(array $data){
		try{
			$news_fields = array(
				'news_display_pic' 	=> $data['news_display_pic'],
				'news_caption' 		=> $data['news_caption'],
				'news_details' 		=> $data['news_details'],
				'news_link' 		=> $data['news_link'],
				'news_author'		=> $data['news_author'],
				'created_by' 		=> $data['created_by']
			);
			
			$this->db->insert('news_and_updates', $news_fields);
			$lastInsertedId = $this->db->insert_id();
		}catch(PDOException $e){
			$msg = $e->getMessage();
			$this->db->trans_rollback();
		}
	}

	public function modify_news(array $data){
		try{
			$news_params = array();

			if(EMPTY($data['news_display_pic'])){
				$news_params = array(
					'news_caption' 		=> $data['news_caption'],
					'news_details' 		=> $data['news_details'],
					'news_link' 		=> $data['news_link'],
					'news_author' 		=> $data['news_author']
				);
			}else{
				$news_params = array(
					'news_caption' 		=> $data['news_caption'],
					'news_details' 		=> $data['news_details'],
					'news_link' 		=> $data['news_link'],
					'news_author' 		=> $data['news_author'],
					'news_display_pic' 	=> $data['news_display_pic']
				);
			}
			
			
			$this->db->where('news_id', $data['news_id']);
			$this->db->update('news_and_updates', $news_params);
		}catch(PDOException $e){
			$msg = $e->getMessage();
			$this->db->trans_rollback();
		}
	}
	
	public function delete_news($news_id){
        try {
			$this->db->delete('news_and_updates', array('news_id' => $news_id));
        }catch(PDOException $e){
			$msg = $e->getMessage();
			$this->db->trans_rollback();
		}
	}
}
