<?php
class Home_model extends CI_Model {

  public function getAllCategories() {
		$query = "
						SELECT 
							category_id,category_name
						FROM 
							param_categories 
						WHERE 
							active_flag = 'Y'
						";

    $stmt = $this->db->query($query);
    return $stmt->result();
  }

  public function getAllTalents() {
    $query = "
  				SELECT
  					talent_id,CONCAT(firstname, ' ', lastname) as fullname,
						height,talent_fee,
						CASE talent_fee_type
							WHEN 'HOURLY_RATE' THEN 'PER HOUR'
							WHEN 'DAILY_RATE' THEN 'PER DAY'
						END as talent_fee_type,
						location,DATE_FORMAT(birth_date, '%M %d, %Y') as birth_date,
  					email,contact_number,gender
  				FROM
  					talents
  				ORDER BY talent_id DESC
  			";

    $stmt = $this->db->query($query);
    return $stmt->result();
  }

  //Our custom function.
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
    $talents_fields = array(
      'firstname' => $data['firstname'],
      'middlename' => $data['middlename'],
      'lastname' => $data['lastname'],
      'email' => $data['email'],
      'contact_number' => $data['contact_number'],
      'gender' => $data['gender'],
      'height' => $data['height'],
      'location' => $data['location'],
      'birth_date' => $data['birth_date'],
      'talent_fee' => $data['talent_fee'],
      'talent_fee_type' => $data['talent_fee_type'],
      'created_by' => 1,
    );

    $this->db->insert('talents', $talents_fields);
    $lastInsertedId = $this->db->insert_id();

    $talents_category_fields = array(
      'talent_id' => $lastInsertedId,
      'category_id' => $data['category'],
    );

    $generated_pin = 'HIREUS_' . $this->_generatePIN();

    print_r('PIN: ' . $generated_pin);
    $talents_account_fields = array(
      'talent_id' => $lastInsertedId,
      'talent_password' => password_hash($generated_pin, PASSWORD_BCRYPT),
    );

    $this->db->insert('talents_category', $talents_category_fields);
    $this->db->insert('talents_account', $talents_account_fields);
  }
}
