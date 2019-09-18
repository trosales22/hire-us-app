<?php
class Home_model extends CI_Model {

  public function getAllCategories() {
		$params = array('Y');

		$query = "
						SELECT 
							category_id,category_name
						FROM 
							param_categories 
						WHERE 
							active_flag = ?
						";

    $stmt = $this->db->query($query, $params);
    return $stmt->result();
	}

  public function getAllTalents() {
    $query = "
  				SELECT
						talent_id,CONCAT(firstname, ' ', lastname) as fullname,
						hourly_rate,gender,
						DATE_FORMAT(birth_date, '%M %d, %Y') as birth_date
  				FROM
  					talents
  				ORDER BY talent_id DESC
  			";

    $stmt = $this->db->query($query);
    return $stmt->result();
	}
	
	public function getTalentResourceCount($talent_id){
		$params = array($talent_id);

		$query = "
						SELECT 
							count(*) as talent_res_count
						FROM 
							talents_resources 
						WHERE talent_id = ?";

		$stmt = $this->db->query($query, $params);
    return $stmt->result();
	}

  private function _generatePIN($digits = 4) {
    $i = 0; //counter
    $pin = ""; //our default pin is blank.
    while ($i < $digits) {
      //generate a random number between 0 and 9.
      $pin .= mt_rand(0, 9);
      $i++;
    }
    return $pin;
  }

  public function insertTalentOrModel(array $data) {
		//insert to talents table
    $talents_fields = array(
      'firstname' => $data['firstname'],
      'middlename' => $data['middlename'],
      'lastname' => $data['lastname'],
      'email' => $data['email'],
      'contact_number' => $data['contact_number'],
      'gender' => $data['gender'],
      'height' => $data['height'],
      'birth_date' => $data['birth_date'],
			'hourly_rate' => $data['hourly_rate'],
			'vital_stats'	=> $data['vital_stats'],
			'fb_followers'	=> $data['fb_followers'],
			'instagram_followers'	=> $data['instagram_followers'],
			'genre'	=> $data['genre'],
			'description'	=> $data['description'],
      'created_by' => 1,
    );

    $this->db->insert('talents', $talents_fields);
    $lastInsertedId = $this->db->insert_id();
		
		//insert to talents_category table
		foreach($data['categories'] as $category){
			$talents_category_fields = array(
				'talent_id' => $lastInsertedId,
				'category_id' => $category,
			);

			$this->db->insert('talents_category', $talents_category_fields);
		}

		//insert to talents_account table
    $generated_pin = 'HIREUS_' . $this->_generatePIN();

		print_r('PIN: ' . $generated_pin);
		
    $talents_account_fields = array(
      'talent_id' => $lastInsertedId,
      'talent_password' => password_hash($generated_pin, PASSWORD_BCRYPT),
		);
		
		$this->db->insert('talents_account', $talents_account_fields);
		
		//insert to talents_exp_or_prev_clients table
		$talents_prev_clients_fields = array(
			'talent_id' => $lastInsertedId,
			'details'		=> $data['prev_clients']
		);

		$this->db->insert('talents_exp_or_prev_clients', $talents_prev_clients_fields);
		
		//insert to talent_address table
		$talents_address_fields = array(
			'talent_id' 		=> $lastInsertedId,
			'province' 			=> $data['address']['province'],
			'city_muni' 		=> $data['address']['city_muni'],
			'barangay' 			=> $data['address']['barangay'],
			'bldg_village' 	=> $data['address']['bldg_village'],
			'zip_code' 			=> $data['address']['zip_code']
		);

		$this->db->insert('talents_address', $talents_address_fields);
	}

	public function uploadTalentProfilePic(array $fields){
		//insert to talents_resources table
		$this->db->insert('talents_resources', $fields);
	}

	public function uploadTalentGallery(array $data){
		//insert to talents_gallery table
		$insert = $this->db->insert_batch('talents_gallery',$data);
	}

	public function getTalentGallery($talent_id = ''){
		$params = array($talent_id);
		$query = "
  				SELECT
						img_id,talent_id,file_name,uploaded_on
  				FROM
						talents_gallery
					ORDER BY 
						uploaded_on DESC
  			";

    $stmt = $this->db->query($query, $params);
		return $stmt->result();
	}
}
