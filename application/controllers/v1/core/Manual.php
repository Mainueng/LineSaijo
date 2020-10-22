<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';


class Manual extends REST_Controller
{
	public function __construct()
	{
		parent::__construct();

		$this->methods['manual_list_get']['limit'] = 500;
		$this->methods['manual_info_get']['limit'] = 500;

		$this->load->model($this->manual_model, 'manual');

		$this->load->library('Authorization_Token');
	}


	public function manual_list_get()
	{
		if ($this->is_authen()) {

			$token_info = $this->authorization_token->userData();

			if ($token_info->user_role_id == 4 || $token_info->user_role_id == 2 || $token_info->user_role_id == 1) {

				$data = $this->manual->get_manual_list();

                if ($data !== false && !is_blank($data)) {

                    $http_code = REST_Controller::HTTP_OK;
                    $message = array(
                        'status' => true,
                        'code' => $http_code,
                        'message' => "Success",
                        'data' => $data
                    );

                } else {
                    $http_code = REST_Controller::HTTP_BAD_REQUEST;
                    $message = array(
                        'status' => false,
                        'code' => $http_code,
                        'message' => 'Manual List not found.',
                        'data' => array()
                    );
                }

			} else {
				$http_code = REST_Controller::HTTP_METHOD_NOT_ALLOWED;
				$message = array(
					'status' => false,
					'code' => $http_code,
					'message' => "You do not have permission to access.",
					'data' => array()
				);
			}

		} else {

			$http_code = REST_Controller::HTTP_REQUEST_TIMEOUT;
			$message = array(
				'status' => false,
				'code' => $http_code,
				'message' => $this->_authen_message,
				'data' => array()
			);

		}

		$this->response($message, $http_code);
	}

	public function manual_info_get()
	{
		if ($this->is_authen()) {

			$token_info = $this->authorization_token->userData();

			if ($token_info->user_role_id == 4 || $token_info->user_role_id == 2 || $token_info->user_role_id == 1) {

				$manual_id = $this->get('id');

                if (isset($manual_id) && !is_blank($manual_id)) {
                    $this->manual->setManualId($manual_id);
                } else {
                    $this->manual->setManualId(0);
                }

				$data = $this->manual->get_manual_info();

                if ($data !== false && !is_blank($data)) {

                    $http_code = REST_Controller::HTTP_OK;
                    $message = array(
                        'status' => true,
                        'code' => $http_code,
                        'message' => "Success",
                        'data' => $data
                    );

                } else {
                    $http_code = REST_Controller::HTTP_BAD_REQUEST;
                    $message = array(
                        'status' => false,
                        'code' => $http_code,
                        'message' => 'Manual not found.',
                        'data' => array()
                    );
                }

			} else {
				$http_code = REST_Controller::HTTP_METHOD_NOT_ALLOWED;
				$message = array(
					'status' => false,
					'code' => $http_code,
					'message' => "You do not have permission to access.",
					'data' => array()
				);
			}

		} else {

			$http_code = REST_Controller::HTTP_REQUEST_TIMEOUT;
			$message = array(
				'status' => false,
				'code' => $http_code,
				'message' => $this->_authen_message,
				'data' => array()
			);

		}

		$this->response($message, $http_code);
	}

}
