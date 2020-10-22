<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';

class Books extends REST_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('Authorization_Token');
        $this->load->model('Jobs_model', 'job');

         $this->methods['upcoming_get']['limit'] = 500;
    }

    public function upcoming_get(){
    	if ($this->is_authen()) {

    		$message = array();
            $id = $this->get('id');

    		$this->job->setCustomerId($id);

    		$list = $this->job->jobs_list();

    		if ($list !== false && !is_blank($list)) {

                $http_code = REST_Controller::HTTP_OK;
                $message = array(
                    'status' => TRUE,
                    'data' => $list
                );
            } else {

            	$http_code = REST_Controller::HTTP_BAD_REQUEST;
                $message = array(
                    'status' => FALSE,
                    'message' => "Job not found."
                );
            }

            $this->response($message, $http_code);

    	} else {
    		$this->response(array('status' => FALSE, 'message' => $this->_authen_message), REST_Controller::HTTP_NOT_FOUND);
    	}
	}

	public function upcoming_info_get(){
    	if ($this->is_authen()) {

    		$message = array();
            $job_id = $this->get('id');

    		$this->job->setJobId($job_id);

    		$info = $this->job->job_info();

    		if ($info !== false && !is_blank($info)) {

                $http_code = REST_Controller::HTTP_OK;
                $message = array(
                    'status' => TRUE,
                    'data' => $info
                );
            } else {

            	$http_code = REST_Controller::HTTP_BAD_REQUEST;
                $message = array(
                    'status' => FALSE,
                    'message' => "Job not found."
                );
            }

            $this->response($message, $http_code);

    	} else {
    		$this->response(array('status' => FALSE, 'message' => $this->_authen_message), REST_Controller::HTTP_NOT_FOUND);
    	}
	}

	public function call_get(){

		if ($this->is_authen()) {

			$message = array();

			$tech_id = $this->get('tech_id');

			$this->job->setTechId($tech_id);

			$tel = $this->job->telephone_number();

			if ($tel !== false && !is_blank($tel)) {

                $http_code = REST_Controller::HTTP_OK;
                $message = array(
                    'status' => TRUE,
                    'data' => $tel
                );
            } else {

            	$http_code = REST_Controller::HTTP_BAD_REQUEST;
                $message = array(
                    'status' => FALSE,
                    'message' => "Telephone number not found."
                );
            }

            $this->response($message, $http_code);

    	} else {
    		$this->response(array('status' => FALSE, 'message' => $this->_authen_message), REST_Controller::HTTP_NOT_FOUND);
    	}
	}

	public function history_get(){
    	if ($this->is_authen()) {

    		$message = array();
            $tech_id = $this->get('tech_id');

    		$this->job->setTechId($tech_id);

    		$list = $this->job->history_list();

    		if ($list !== false && !is_blank($list)) {

                $http_code = REST_Controller::HTTP_OK;
                $message = array(
                    'status' => TRUE,
                    'data' => $list
                );
            } else {

            	$http_code = REST_Controller::HTTP_BAD_REQUEST;
                $message = array(
                    'status' => FALSE,
                    'message' => "Job not found."
                );
            }

            $this->response($message, $http_code);

    	} else {
    		$this->response(array('status' => FALSE, 'message' => $this->_authen_message), REST_Controller::HTTP_NOT_FOUND);
    	}
	}

}
