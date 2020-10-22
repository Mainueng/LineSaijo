<?php

defined('BASEPATH') OR exit('No direct script access allowed');

use Restserver\Libraries\REST_Controller;
require APPPATH . '/libraries/REST_Controller.php';


class Device extends REST_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->load->library('Authorization_Token');

        $this->load->model('Device_model', 'device');
        $this->load->model('User_model', 'user');
        $this->load->model('Warranty_model', 'warranty');

        $this->methods['index_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['index_post']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['index_delete']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['index_put']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['air_type_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['rename_device_put']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['family_list_get']['limit'] = 500; // 500 requests per hour per user/key
    }

    public function index_get()
    {
        if ($this->is_authen()) {

            $message = array();

            $token_info = $this->authorization_token->userData();

            if ($token_info->user_role_id == 4 || $token_info->user_role_id == 1 || $token_info->user_role_id == 2) {

                $id = $this->user->getIot_ID($token_info->id);

                if ($id !== false && !is_blank($id)) {
                    $this->device->setUserID($id);

                    $device = $this->device->device_list();

                    if ($device !== false && !is_blank($device)) {

                        $http_code = REST_Controller::HTTP_OK;
                        $message = array(
                            'status' => true,
                            'code' => $http_code,
                            'message' => "Success",
                            'data' => $device
                        );

                    } else {

                        $http_code = REST_Controller::HTTP_OK;
                        $message = array(
                            'status' => false,
                            'code' => $http_code,
                            'message' => "Device not found.",
                            'data' => array()
                        );
                    }
                } else {

                    $http_code = REST_Controller::HTTP_OK;
                    $message = array(
                        'status' => false,
                        'code' => $http_code,
                        'message' => "User not found.",
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

    public function index_post()
    {
        if ($this->is_authen()) {

            $message = array();

            $token_info = $this->authorization_token->userData();

            if ($token_info->user_role_id == 4 || $token_info->user_role_id == 1 || $token_info->user_role_id == 2) {

                $data = $this->security->xss_clean($_POST);

                $this->form_validation->set_rules('serial', 'Serial', 'trim|required|exact_length[13]');

                if ($this->form_validation->run() == TRUE) {

                    $id = $this->user->getIot_ID($token_info->id);

                    if ($id !== false && !is_blank($id)) {
                        $this->device->setUserID($id);

                        $serial = strtoupper($this->post('serial'));

                        if (isset($serial) && !is_blank($serial)) {
                            $this->device->setSerial($serial);
                        }

                        $device = $this->device->check_device();

                        if ($device !== false && !is_blank($device)) {

                            $this->warranty->setSerial($serial);

                            $is_warranty = $this->warranty->getActiveStatus();

                            if (!$is_warranty) {
                                $this->warranty->product_activate();
                            }

                            $count = $this->device->device_count();

                            if ($count !== false && !is_blank($count)) {

                                $data = $this->device->add_device();

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
                                        'message' => "This device already added.",
                                        'data' => array()
                                    );
                                }

                            } else {

                                $http_code = REST_Controller::HTTP_BAD_REQUEST;
                                $message = array(
                                    'status' => false,
                                    'code' => $http_code,
                                    'message' => "You can't add more than 16 devices.",
                                    'data' => array()
                                );
                            }

                        } else {

                            $http_code = REST_Controller::HTTP_BAD_REQUEST;
                            $message = array(
                                'status' => false,
                                'code' => $http_code,
                                'message' => "Serial Invalid.",
                                'data' => array()
                            );
                        }
                    } else {

                        $http_code = REST_Controller::HTTP_BAD_REQUEST;
                        $message = array(
                            'status' => false,
                            'code' => $http_code,
                            'message' => "User not found.",
                            'data' => array()
                        );
                    }

                } else {

                    $http_code = REST_Controller::HTTP_BAD_REQUEST;
                    $message = array(
                        'status' => false,
                        'code' => $http_code,
                        'message' => trim(strip_tags(validation_errors(),'\n')),
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

    public function index_delete()
    {
        if ($this->is_authen()) {

            $message = array();

            $token_info = $this->authorization_token->userData();

            if ($token_info->user_role_id == 4 || $token_info->user_role_id == 1 || $token_info->user_role_id == 2) {

                $id = $this->user->getIot_ID($token_info->id);

                if ($id !== false && !is_blank($id)) {

                    $this->device->setUserID($id);

                    $serial = $this->delete('serial');

                    if (isset($serial) && !is_blank($serial)) {
                        $this->device->setSerial($serial);
                    }

                    $device = $this->device->delete_device();

                    if ($device !== false && !is_blank($device)) {

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
                            'message' => "Serial Invalid.",
                            'data' => array()
                        );
                    }
                } else {

                    $http_code = REST_Controller::HTTP_BAD_REQUEST;
                    $message = array(
                        'status' => false,
                        'code' => $http_code,
                        'message' => "User not found.",
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

    public function index_put()
    {
        if ($this->is_authen()) {

            $message = array();

            $token_info = $this->authorization_token->userData();

            if ($token_info->user_role_id == 4 || $token_info->user_role_id == 1 || $token_info->user_role_id == 2) {

                $id = $this->user->getIot_ID($token_info->id);

                if ($id !== false && !is_blank($id)) {

                    $this->device->setUserID($id);

                    $serial = $this->put('serial');
                    $command = $this->put('command');

                    if (isset($serial) && !is_blank($serial)) {
                        $this->device->setSerial($serial);
                    } else {
                        $this->device->setSerial(0);
                    }

                    if (isset($command) && !is_blank($command)) {
                        $this->device->setCommand($command);
                    } else {
                        $this->device->setCommand(0);
                    }

                    if ($command !== false && !is_blank($command)) {

                        $device = $this->device->update_cmd();

                        if ($device !== false && !is_blank($device)) {

                            $http_code = REST_Controller::HTTP_OK;
                            $message = array(
                                'status' => true,
                                'code' => $http_code,
                                'message' => "Success",
                                'data' => $device
                            );

                        } else {

                            $http_code = REST_Controller::HTTP_BAD_REQUEST;
                            $message = array(
                                'status' => false,
                                'code' => $http_code,
                                'message' => 'Unable to get information.',
                                'data' => array()
                            );

                        }

                    } else {

                        $http_code = REST_Controller::HTTP_BAD_REQUEST;
                        $message = array(
                            'status' => false,
                            'code' => $http_code,
                            'message' => "Please provide command.",
                            'data' => $this->put()
                        );
                    }

                } else {

                    $http_code = REST_Controller::HTTP_BAD_REQUEST;
                    $message = array(
                        'status' => false,
                        'code' => $http_code,
                        'message' => "User not found.",
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

    public function air_type_get()
    {
        if ($this->is_authen()) {

            $message = array();

            $token_info = $this->authorization_token->userData();

            if ($token_info->user_role_id == 4 || $token_info->user_role_id == 1 || $token_info->user_role_id == 2) {

                $id = $this->user->getIot_ID($token_info->id);

                if ($id !== false && !is_blank($id)) {

                    $this->device->setUserID($id);

                    $serial = $this->get('serial');

                    if (isset($serial) && !is_blank($serial)) {
                        $this->device->setSerial($serial);
                    }

                    $device = $this->device->check_air_mn();

                    if ($device !== false && !is_blank($device)) {

                        $air_info = $this->device->air_info();

                        if ($air_info !== false && !is_blank($air_info)) {

                            $this->device->setAir_type_id($air_info);
                            $air_type = $this->device->air_type();

                            if ($air_type !== false && !is_blank($air_type)) {

                                $http_code = REST_Controller::HTTP_OK;
                                $message = array(
                                    'status' => true,
                                    'code' => $http_code,
                                    'message' => "Air type is sent successfully.",
                                    'data' => $air_type
                                );
                            } else {

                                $http_code = REST_Controller::HTTP_BAD_REQUEST;
                                $message = array(
                                    'status' => false,
                                    'code' => $http_code,
                                    'message' => "No Information type air.",
                                    'data' => array()
                                );
                            }

                        } else {

                            $http_code = REST_Controller::HTTP_BAD_REQUEST;
                            $message = array(
                                'status' => false,
                                'code' => $http_code,
                                'message' => "No Information serial.",
                                'data' => array()
                            );
                        }

                    } else {

                        $http_code = REST_Controller::HTTP_BAD_REQUEST;
                        $message = array(
                            'status' => false,
                            'code' => $http_code,
                            'message' => "No Information serial.",
                            'data' => array()
                        );
                    }
                } else {

                    $http_code = REST_Controller::HTTP_BAD_REQUEST;
                    $message = array(
                        'status' => false,
                        'code' => $http_code,
                        'message' => "User not found.",
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

    public function rename_device_put()
    {
        if ($this->is_authen()) {

            $token_info = $this->authorization_token->userData();

            if ($token_info->user_role_id == 4 || $token_info->user_role_id == 1 || $token_info->user_role_id == 2) {

                $this->form_validation->set_rules('serial', 'Serial', 'trim|require');

                $serial = $this->get('serial');

                $name = $this->put('name');

                if (isset($serial) && !is_blank($serial)) {
                    $this->device->setSerial($serial);
                }

                if (isset($name) && !is_blank($name)) {
                    $this->device->setName($name);
                }

                $data = $this->device->update_device_name();

                if ($data !== false && !is_blank($data)) {

                    $http_code = REST_Controller::HTTP_OK;
                    $message = array(
                        'status' => true,
                        'code' => $http_code,
                        'message' => 'Success.',
                        'data' => array()
                    );

                } else {

                    $http_code = REST_Controller::HTTP_BAD_REQUEST;
                    $message = array(
                        'status' => false,
                        'code' => $http_code,
                        'message' => "Update name fail.",
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

    public function family_list_get()
    {
        if ($this->is_authen()) {

            $message = array();

            $token_info = $this->authorization_token->userData();

            if ($token_info->user_role_id == 4 || $token_info->user_role_id == 1 || $token_info->user_role_id == 2) {

                $serial = $this->get('serial');

                if ($serial !== false && !is_blank($serial)) {

                    $this->device->setSerial($serial);

                    $family = $this->device->family_list();

                    if ($family !== false && !is_blank($family)) {

                        $http_code = REST_Controller::HTTP_OK;
                        $message = array(
                            'status' => true,
                            'code' => $http_code,
                            'message' => "successfully.",
                            'data' => $family
                        );

                    } else {

                        $http_code = REST_Controller::HTTP_BAD_REQUEST;
                        $message = array(
                            'status' => false,
                            'code' => $http_code,
                            'message' => "Family not found.",
                            'data' => array()
                        );
                    }
                } else {

                    $http_code = REST_Controller::HTTP_BAD_REQUEST;
                    $message = array(
                        'status' => false,
                        'code' => $http_code,
                        'message' => "User not found.",
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

    public function air_info_test_get()
    {
        $serial = $this->get('serial');

        if ($serial !== false && !is_blank($serial)) {

            $air_info = $this->device->getAirInfoTest($serial);

            foreach ($air_info as $info) {
                /*$indoor = $info['indoor'];
                $outdoor = $info['outdoor'];
                $energy = $info['energy'];*/

                /* $data['name'] = $info['name'];*/
                $data['serial'] = $info['serial'];
                $data['ip'] = $info['ip'];
                /*$data['ssid'] = $info['ssid'];*/
                $data['online'] = $info['datetime'];
                $data['datetime'] = date("d/m/Y H:i:s");

                $data['indoor'] = bin2hex($info["indoor"]);

            }

            if ($info !== false && !is_blank($info)) {

                //print_r($data);

                $http_code = REST_Controller::HTTP_OK;
                /*$message = array(
                    'status' => true,
                    'code' => $http_code,
                    'message' => "successfully.",
                    'data' =>  array($data)
                );*/
                $message = array(
                    /*'status' => true,
                    'code' => $http_code,
                    'message' => "successfully.",*/
                    'data' =>  $data
                );

            } else {

                $http_code = REST_Controller::HTTP_BAD_REQUEST;
                $message = array(
                    'status' => false,
                    'code' => $http_code,
                    'message' => "Serial not found.",
                    'data' => array()
                );
            }
        } else {

            $http_code = REST_Controller::HTTP_BAD_REQUEST;
            $message = array(
                'status' => false,
                'code' => $http_code,
                'message' => "Serial not found.",
                'data' => array()
            );
        }

        $this->response($message, $http_code);
    }
}
