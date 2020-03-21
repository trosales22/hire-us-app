<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class News extends CI_Controller {
	public function __construct() {
		parent::__construct();
			
		$this->load->helper('url', 'form');
		$this->load->library('session');
		$this->load->database();
		$this->load->model('api/News_model', 'news_model');
	}
	
	public function index() {
		$this->_get_news_and_updates();
		$this->load->view('news_page', $this->data);
	}

	private function _get_news_and_updates(){
		try{
			$success        = 0;
			$news_id		= trim($this->input->get('news_id'));
			$news_caption 	= trim($this->input->get('news_caption'));

			$news_params = array(
				'news_id'		=> $news_id,
				'news_caption' 	=> $news_caption
			);
			
			$news_list = $this->news_model->get_news_and_updates($news_params);
			$success  = 1;
		}catch (Exception $e){
			$msg = $e->getMessage();      
		}

		if($success == 1){
			$response = [
			  'news_list' => $news_list
			];
		}else{
			$response = [
				'msg'       => $msg,
				'flag'      => $success
			];
		}
		
		$this->data['news_list'] = $response['news_list'];
	}

	public function add_news(){
		try{
			$success  		= 0;
			$msg 			= array();
			$session_data 	= $this->session->userdata('logged_in');
			
			$news_params = array(
				'news_caption'	=> trim($this->input->post('news_caption')),
				'news_details'	=> trim($this->input->post('news_details')),
				'news_link'		=> trim($this->input->post('news_link')),
				'news_author'	=> trim($this->input->post('news_author')),
				'created_by'    => $session_data['user_id']
			);
			
			if(EMPTY($news_params['news_caption']))
				throw new Exception("News Caption is required.");
			
			if(EMPTY($news_params['news_details']))
				throw new Exception("News Details is required.");

			if(EMPTY($news_params['news_author']))
				throw new Exception("News Author is required.");
			
			if(EMPTY($news_params['created_by']))
				throw new Exception("Creator is required.");

			$config['upload_path'] = 'uploads/news/';
			$config['allowed_types'] = 'jpg|jpeg|png';
			$config['max_size'] = 2000; //set the maximum file size in kilobytes (2MB)
			$config['max_width'] = 1000;
			$config['max_height'] = 1000;
			$config['file_name'] = time() . '_' . rand(1000,9999);
			$this->load->library('upload', $config);

			if (!EMPTY($_FILES['news_display_pic']['name'])){
				if(!$this->upload->do_upload('news_display_pic')) {
					$msg = $this->upload->display_errors();			
					throw new Exception($msg);
				}else{
					$upload_img_output = array(
						'image_metadata' => $this->upload->data()
					);

					$img_output = array(
						'news_display_pic'	=> $upload_img_output['image_metadata']['file_name']
					);

					$news_params['news_display_pic'] = $img_output['news_display_pic'];
				}
				
				if(EMPTY($news_params['news_display_pic']))
					throw new Exception("News display photo is required.");
			}else{
				$news_params['news_display_pic'] = NULL;
			}

			$this->news_model->add_news($news_params);
			$success  = 1;
		}catch (Exception $e){
			$msg = $e->getMessage();      
		}

		if($success == 1){
			$response = [
				'msg'       => 'News was successfully added.',
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

	public function modify_news(){
		try{
			$success  = 0;
			$msg = array();

			$news_params = array(
				'news_id'		=> trim($this->input->post('news_id')),
				'news_caption'	=> trim($this->input->post('news_caption')),
				'news_details'	=> trim($this->input->post('news_details')),
				'news_link'		=> trim($this->input->post('news_link')),
				'news_author'	=> trim($this->input->post('news_author'))
			);

			if(EMPTY($news_params['news_id']))
				throw new Exception("News ID is required.");

			if(EMPTY($news_params['news_caption']))
				throw new Exception("News Caption is required.");
			
			if(EMPTY($news_params['news_details']))
				throw new Exception("News Details is required.");
			
			if(EMPTY($news_params['news_author']))
				throw new Exception("News Author is required.");
			
			$detect_if_display_pic_exist = $this->news_model->detect_if_display_pic_exist($news_params['news_id']);
			//die(print_r($detect_if_display_pic_exist));

			$latest_news_display_photo = $detect_if_display_pic_exist[0]->news_display_photo == 'NO IMAGE' ? '' : $detect_if_display_pic_exist[0]->news_display_photo;
			$latest_news_display_photo_raw = $detect_if_display_pic_exist[0]->news_display_photo_raw == 'NO IMAGE' ? '' : $detect_if_display_pic_exist[0]->news_display_photo_raw;
			//die(print_r($latest_news_display_photo_raw));

			$config['upload_path'] = 'uploads/news/';
			$config['allowed_types'] = 'jpg|jpeg|png';
			$config['max_size'] = 2000; //set the maximum file size in kilobytes (2MB)
			$config['max_width'] = 1000; //1000px
			$config['max_height'] = 1000; //100px
			$config['file_name'] = time() . '_' . rand(1000,9999);
			$this->load->library('upload', $config);

			if (EMPTY($_FILES['news_display_pic']['name'])){
				if(EMPTY($latest_news_display_photo)){
					throw new Exception("News display photo is required.");
				}else{
					$news_params['news_display_pic'] = $latest_news_display_photo_raw;
				}
			}else{
				if(!$this->upload->do_upload('news_display_pic')) {
					$msg = $this->upload->display_errors();
					throw new Exception($msg);
				}else{
					if(!EMPTY($latest_news_display_photo_raw)){
						unlink("uploads/news/" . $latest_news_display_photo_raw);
					}
					
					$upload_img_output = array(
						'image_metadata' => $this->upload->data()
					);
	
					$img_output = array(
						'news_display_pic'	=> $upload_img_output['image_metadata']['file_name']
					);
					
					$news_params['news_display_pic'] = $img_output['news_display_pic'];
				}

				if(EMPTY($news_params['news_display_pic']))
					throw new Exception("News display photo is required.");
			}

			//die(print_r($news_params));
			
			$this->news_model->modify_news($news_params);
			$success  = 1;
		}catch (Exception $e){
			$msg = $e->getMessage();      
		}

		if($success == 1){
			$response = [
				'msg'       => 'News was successfully updated.',
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

	public function delete_news(){
		try{
			$success 	= 0;
			$news_id 	= trim($this->input->get('news_id'));
			
			if(EMPTY($news_id))
				throw new Exception("News ID is required.");

			$this->news_model->delete_news($news_id);
			$success  = 1;
		}catch (Exception $e){
			$msg = $e->getMessage();
		}

		if($success == 1){
			$response = [
				'msg'       => 'News was successfully deleted.',
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
