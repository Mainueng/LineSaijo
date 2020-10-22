<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';

class Jobs extends REST_Controller{
	function __construct()
	{
		parent::__construct();
		$this->load->library('Authorization_Token');
	}



	public function technician_post(){

	}

	public function accept_put(){
		if ($this->is_authen()) {

			if ($this->_user_role_id == 3) {
				$this->load->model($this->jobs_model, 'job');

				$status_job = 2

				$result = $this->job->update_job($status_job);
				if ($result) {
					$status = true;
				} else {
					$status = false;
				}

				$this->response(array('status' => $status, 'message' => 'Request successfully.'), REST_Controller::HTTP_NOT_FOUND);
			} else {
				$this->response(array('status' => FALSE, 'message' => 'You are not a technician'), REST_Controller::HTTP_NOT_FOUND);
			}

		} else {
			$this->response(array('status' => FALSE, 'message' => $this->_authen_message), REST_Controller::HTTP_NOT_FOUND);
		}
	}

	public function cancle_put(){
		if ($this->is_authen()) {

			if ($this->_user_role_id == 3) {

				$this->load->model($this->jobs_model, 'job');

				$status_job = 5

				$result = $this->job->update_job($status_job);

				if ($result) {
					$status = true;
				} else {
					$status = false;
				}

				$this->response(array('status' => $status, 'message' => 'Request successfully.'), REST_Controller::HTTP_NOT_FOUND);
			} else {
				$this->response(array('status' => FALSE, 'message' => 'You are not a technician'), REST_Controller::HTTP_NOT_FOUND);
			}

		} else {
			$this->response(array('status' => FALSE, 'message' => $this->_authen_message), REST_Controller::HTTP_NOT_FOUND);
		}
	}

	public function complete_put(){
		if ($this->is_authen()) {

			if ($this->_user_role_id == 3) {

				$this->load->model($this->jobs_model, 'job');

				$status_job = 4

				$pic_in = $this->post('pic_in_path');
				$pic_out = $this->post('pic_out_path');

				$this->job->setPicIn($pic_in);
				$this->job->setPicOut($pic_out);
				$this->job->setCheckSheet($check_sheet);

				$result = $this->job->update_job($status_job);

				if ($result) {
					$status = true;
				} else {
					$status = false;
				}

				$this->response(array('status' => $status, 'message' => 'Request successfully.'), REST_Controller::HTTP_NOT_FOUND);
			} else {
				$this->response(array('status' => FALSE, 'message' => 'You are not a technician'), REST_Controller::HTTP_NOT_FOUND);
			}

		} else {
			$this->response(array('status' => FALSE, 'message' => $this->_authen_message), REST_Controller::HTTP_NOT_FOUND);
		}
	}

} // End of class
