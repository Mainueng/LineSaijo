<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';


class Error_code extends REST_Controller
{
	public function __construct()
	{
		parent::__construct();

		$this->methods['index_get']['limit'] = 500;
		$this->methods['error_code_get']['limit'] = 500;

		$this->load->model($this->error_code_model, 'error_code');

		$this->load->library('Authorization_Token');
	}


	public function errors_list_get()
	{
		if ($this->is_authen()) {

			$token_info = $this->authorization_token->userData();

			if ($token_info->user_role_id == 3 || $token_info->user_role_id == 1) {

				$data = $this->error_code->get_error_code_list();

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
                        'message' => 'ไม่พบรหัสข้อผิดพลาด',
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

	public function index_get()
	{
		if ($this->is_authen()) {

			$token_info = $this->authorization_token->userData();

			if ($token_info->user_role_id == 3 || $token_info->user_role_id == 1) {

				$data = $this->error_code->get_error_code_list();

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
                        'message' => 'ไม่พบรหัสข้อผิดพลาด',
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

	public function error_code_get()
	{
		if ($this->is_authen()) {

			$token_info = $this->authorization_token->userData();

			if ($token_info->user_role_id == 3 || $token_info->user_role_id == 1) {

				$code_id = $this->get('id');

                if (isset($code_id) && !is_blank($code_id)) {
                    $this->error_code->setErrorCodeId($code_id);
                } else {
                    $this->error_code->setErrorCodeId(0);
                }

				$data = $this->error_code->get_error_code();

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
                        'message' => 'ไม่พบรหัสข้อผิดพลาด',
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
