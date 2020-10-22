<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';


class Notification extends REST_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->methods['index_get']['limit'] = 500;
        $this->methods['all_put']['limit'] = 100;
        $this->methods['all_delete']['limit'] = 100;
        $this->methods['remove_delete']['limit'] = 100;
        $this->methods['read_put']['limit'] = 100;

        $this->load->model($this->notification_model, 'notification');

        $this->load->library('Authorization_Token');
    }

    public function index_get()
    {
        /*** Get notification ***/
        if ($this->is_authen()) {

            $token_info = $this->authorization_token->userData();

            if ($token_info->user_role_id == 3 || $token_info->user_role_id == 1) {

                $id = $token_info->id;

                if (isset($id) && !is_blank($id)) {
                    $this->notification->setCustomerId($id);
                } else {
                    $this->notification->setCustomerId(0);
                }

                $location = $this->notification->get_location();

                if (isset($location) && !is_blank($location)) {
                    $this->notification->setLatitude($location['latitude']);
                    $this->notification->setLongitude($location['longitude']);
                } else {
                    $this->notification->setLatitude(0);
                    $this->notification->setLongitude(0);
                }

                $data = $this->notification->get_notification();

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
                        'message' => 'ไม่พบการแจ้งเตือน',
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

    public function all_put()
    {
        /*** Read all ***/
        if ($this->is_authen()) {

            $token_info = $this->authorization_token->userData();

            if ($token_info->user_role_id == 3 || $token_info->user_role_id == 1) {

                $id = $token_info->id;

                if (isset($id) && !is_blank($id)) {
                    $this->notification->setCustomerId($id);
                } else {
                    $this->notification->setCustomerId(0);
                }

                $job_type = $this->get('id');

                $this->notification->setJobType($job_type);

                $data = $this->notification->read_all();

                if ($data !== false && !is_blank($data)) {

                    $http_code = REST_Controller::HTTP_OK;
                    $message = array(
                        'status' => true,
                        'code' => $http_code,
                        'message' => "Success",
                        'data' => array()
                    );

                } else {
                    $http_code = REST_Controller::HTTP_BAD_REQUEST;
                    $message = array(
                        'status' => false,
                        'code' => $http_code,
                        'message' => "ไม่สามารถปรับสถานะการทำงาน.",
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

    public function all_delete()
    {
        /*** Remove all ***/
        if ($this->is_authen()) {

            $token_info = $this->authorization_token->userData();

            if ($token_info->user_role_id == 3 || $token_info->user_role_id == 1) {

                $id = $token_info->id;

                if (isset($id) && !is_blank($id)) {
                    $this->notification->setCustomerId($id);
                } else {
                    $this->notification->setCustomerId(0);
                }

                $job_type = $this->get('id');

                $this->notification->setJobType($job_type);

                $data = $this->notification->remove_all();

                if ($data !== false && !is_blank($data)) {

                    $http_code = REST_Controller::HTTP_OK;
                    $message = array(
                        'status' => true,
                        'code' => $http_code,
                        'message' => "Success",
                        'data' => array()
                    );

                } else {
                    $http_code = REST_Controller::HTTP_BAD_REQUEST;
                    $message = array(
                        'status' => false,
                        'code' => $http_code,
                        'message' => "ไม่สามารถลบการแจ้งเตือน.",
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

    public function remove_delete()
    {
        /*** Remove notification ***/
        if ($this->is_authen()) {

            $token_info = $this->authorization_token->userData();

            if ($token_info->user_role_id == 3 || $token_info->user_role_id == 1) {

                $id = $token_info->id;

                if (isset($id) && !is_blank($id)) {
                    $this->notification->setCustomerId($id);
                } else {
                    $this->notification->setCustomerId(0);
                }

                $job_id = $this->get('id');

                $this->notification->setJobId($job_id);

                $data = $this->notification->remove();

                if ($data !== false && !is_blank($data)) {

                    $http_code = REST_Controller::HTTP_OK;
                    $message = array(
                        'status' => true,
                        'code' => $http_code,
                        'message' => "Success",
                        'data' => array()
                    );

                } else {
                    $http_code = REST_Controller::HTTP_BAD_REQUEST;
                    $message = array(
                        'status' => false,
                        'code' => $http_code,
                        'message' => "ไม่สามารถลบการแจ้งเตือน",
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

    public function read_put()
    {
        if ($this->is_authen()) {

            $token_info = $this->authorization_token->userData();

            if ($token_info->user_role_id == 3 || $token_info->user_role_id == 1) {

                $id = $token_info->id;

                if (isset($id) && !is_blank($id)) {
                    $this->notification->setCustomerId($id);
                } else {
                    $this->notification->setCustomerId(0);
                }

                $job_id = $this->get('id');

                if (isset($job_id) && !is_blank($job_id)) {
                    $this->notification->setJobId($job_id);
                } else {
                    $this->notification->setJobId(0);
                }

                $data = $this->notification->read();

                if ($data !== false && !is_blank($data)) {

                    $http_code = REST_Controller::HTTP_OK;
                    $message = array(
                        'status' => true,
                        'code' => $http_code,
                        'message' => "Success",
                        'data' => array()
                    );

                } else {
                    $http_code = REST_Controller::HTTP_BAD_REQUEST;
                    $message = array(
                        'status' => false,
                        'code' => $http_code,
                        'message' => "ไม่สามารถปรับสถานะการทำงาน",
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

} // End of class
