<?php
header('Access-Control-Allow-Origin: *');
require APPPATH . 'libraries/REST_Controller.php';

class Bookings extends REST_Controller {
	public function __construct() {
		parent::__construct();
        $this->load->database();
        $this->load->model('Home_model', 'home_model');
		$this->load->model('api/Talents_model', 'talents_model');
		$this->load->model('Bookings_model', 'bookings_model');
    }

    public function get_booking_by_booking_generated_id_get(){
		try{
			$success                = 0;
            $booking_generated_id	= trim($this->get('booking_generated_id'));
            
            if(EMPTY($booking_generated_id))
				throw new Exception("Booking ID is required.");
			
            $booking_list = $this->bookings_model->get_booking_by_booking_generated_id($booking_generated_id);
            
            foreach($booking_list as $booking){
				$talent_details 	= $this->talents_model->get_talent_details($booking->talent_id);
				$client_details		= $this->home_model->getAllClients($booking->client_id);
				
				$booking->talent_id = $talent_details[0];
				$booking->client_id = $client_details[0];
            }
            
			$success  = 1;
		}catch (Exception $e){
			$msg = $e->getMessage();      
		}

		if($success == 1){
			$response = $booking_list[0];
		}else{
			$response = [
				'msg'       => $msg,
				'flag'      => $success
			];
		}
	  
		$this->response($response);
	}

	public function verify_payment_post(){
		try{
			$success  = 0;
			$msg = array();

			$booking_params = array(
				'booking_generated_id'		=> trim($this->input->post('booking_generated_id')),
				'booking_payment_option'	=> 'BANK TRANSFER/DEPOSIT'
			);

			if(EMPTY($booking_params['booking_generated_id']))
				throw new Exception("Booking ID is required.");
			
			if(EMPTY($booking_params['booking_payment_option']))
				throw new Exception("Booking Payment Option is required.");

			$this->bookings_model->update_booking_payment_status($booking_params);
			$success  = 1;
		}catch (Exception $e){
			$msg = $e->getMessage();      
		}

		if($success == 1){
			$response = [
				'msg'       => 'Payment was successfully verified!',
				'flag'      => $success
			];
		}else{
			$response = [
				'msg'       => $msg,
				'flag'      => $success
			];
		}

		echo json_encode($response);
	}
}