<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {
  function __Construct() {
    parent::__Construct();
    $this->load->database(); // load database
    $this->load->model('Home_model', 'home_model'); // load model
  }

  public function index() {
    $this->load->helper('url');
    $this->data['categories'] = $this->home_model->getAllCategories();
    $this->data['talents'] = $this->home_model->getAllTalents();

		//print_r($this->data['talents']);
		//die();

    // $talents_fields =   array(
    //             'firstname'     => 'Josh',
    //             'middlename'     => 'SAMP',
    //             'lastname'       => 'Saratan',
    //             'email'       => 'josh.saratan@gmail.com',
    //             'contact_number'  => '09758694478',
    //             'gender'       => 'Male',
    //             'height'       => '15',
    //             'location'       => 'Kawit, Cavite',
    //             'birth_date'    => '1999-02-22',
    //             'talent_fee'     => 500,
    //             'talent_fee_type'   => 'HOURLY_RATE',
    //             'category'      => 1
    //           );

    // $this->home_model->insertTalentOrModel($talents_fields);

    $this->load->view('home_page', $this->data);
  }
}
