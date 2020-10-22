<?php

defined('BASEPATH') OR exit('No direct script access allowed');

use Restserver\Libraries\REST_Controller;
require APPPATH . '/libraries/REST_Controller.php';


class Product extends REST_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->load->library('Authorization_Token');

        $this->load->model('Product_model', 'product');
        $this->load->model('User_model', 'user');

        $this->methods['device_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['device_post']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['device_delete']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['device_put']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['ac_management_put']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['air_type_get']['limit'] = 500; // 500 requests per hour per user/key
    }

    public function device_get()
    {
        if ($this->is_authen()) {

            $message = array();

            $token_info = $this->authorization_token->userData();

            if ($token_info->user_role_id == 4 || $token_info->user_role_id == 1 || $token_info->user_role_id == 2) {

                $id = $this->user->getIot_ID($token_info->id);

                if ($id !== false && !is_blank($id)) {
                    $this->product->setUserID($id);
                } else {
                    $this->product->setUserID(0);
                }

                $device = $this->product->device_list();

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

    public function device_post()
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
                        $this->product->setUserID($id);
                    } else {
                        $this->product->setUserID(0);
                    }

                    $serial = $this->post('serial');

                    if (isset($serial) && !is_blank($serial)) {
                        $this->product->setSerial($serial);
                    }

                    $device = $this->product->check_device();

                    if ($device !== false && !is_blank($device)) {

                        $count = $this->product->device_count();

                        if ($count !== false && !is_blank($count)) {

                            $data = $this->product->add_device();

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

    public function device_delete()
    {
        if ($this->is_authen()) {

            $message = array();

            $token_info = $this->authorization_token->userData();

            if ($token_info->user_role_id == 4 || $token_info->user_role_id == 1 || $token_info->user_role_id == 2) {

                $id = $token_info->id;

                $this->product->setUserID($id);

                $serial = $this->delete('serial');

                if (isset($serial) && !is_blank($serial)) {
                    $this->product->setSerial($serial);
                }

                $device = $this->product->delete_device();

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

    public function device_put()
    {
        if ($this->is_authen()) {

            $message = array();

            $token_info = $this->authorization_token->userData();

            if ($token_info->user_role_id == 4 || $token_info->user_role_id == 1 || $token_info->user_role_id == 2) {

                $id = $token_info->id;

                $this->product->setUserID($id);

                $serial = $this->put('serial');
                $command = $this->put('command');

                if (isset($serial) && !is_blank($serial)) {
                    $this->product->setSerial($serial);
                } else {
                    $this->product->setSerial(0);
                }

                if (isset($command) && !is_blank($command)) {
                    $this->product->setCommand($command);
                } else {
                    $this->product->setCommand(0);
                }

                if ($command !== false && !is_blank($command)) {

                    $device = $this->product->update_cmd();

                    if ($device !== false && !is_blank($device)) {

                        if ($device == 'Unable to get information.') {

                            $http_code = REST_Controller::HTTP_BAD_REQUEST;
                            $message = array(
                                'status' => false,
                                'code' => $http_code,
                                'message' => $device,
                                'data' => array()
                            );

                        } else {

                            $http_code = REST_Controller::HTTP_OK;
                            $message = array(
                                'status' => true,
                                'code' => $http_code,
                                'message' => "Success",
                                'data' => $device
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
                        'message' => "Please provide command.",
                        'data' => $this->put()
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

    public function ac_management_put()
    {
        if ($this->is_authen()) {

            $message = array();

            $token_info = $this->authorization_token->userData();

            if ($token_info->user_role_id == 4 || $token_info->user_role_id == 1 || $token_info->user_role_id == 2) {

                $id = $token_info->id;

                $this->product->setUserID($id);

                $serial = $this->put('serial');
                $timer = $this->put('timer');
                $latitude = $this->put('latitude');
                $longitude = $this->put('longitude');
                $power_event = $this->put('power');
                $energy_event = $this->put('energy');
                $set_temp_event = $this->put('set_temp');
                $group_event = $this->put('group');
                $enable_event = $this->put('enable');
                $utc_zone = $this->put('utc_zone');
                $mgr_id = $this->put('mgr_id');
                $mgr_delete = $this->put('mgr_delete');
                $mgr_wd = $this->put('mgr_wd');

                if (isset($serial) && !is_blank($serial)) {
                    $this->product->setSerial($serial);
                }

                if (isset($timer) && !is_blank($timer)) {
                    $this->product->setTimer($timer);
                }

                if (isset($latitude) && !is_blank($latitude)) {
                    $this->product->setLatitude($latitude);
                }

                if (isset($longitude) && !is_blank($longitude)) {
                    $this->product->setLongitude($longitude);
                }

                if (isset($power_event) && !is_blank($power_event)) {
                    $this->product->setPowerEvent($power_event);
                }

                if (isset($energy_event) && !is_blank($energy_event)) {
                    $this->product->setEnergyEvent($energy_event);
                }

                if (isset($set_temp_event) && !is_blank($set_temp_event)) {
                    $this->product->setTempEvent($set_temp_event);
                }

                if (isset($group_event) && !is_blank($group_event)) {
                    $this->product->setGroupEvent($group_event);
                }

                if (isset($enable_event) && !is_blank($enable_event)) {
                    $this->product->setEnableEvent($enable_event);
                }

                if (isset($utc_zone) && !is_blank($utc_zone)) {
                    $this->product->setUtcZone($utc_zone);
                }

                if (isset($mgr_id) && !is_blank($mgr_id)) {
                    $this->product->setMgrId($mgr_id);
                } else {
                    $this->product->setMgrId(0);
                }

                if (isset($mgr_delete) && !is_blank($mgr_delete)) {
                    $this->product->setMgrDelete($mgr_delete);
                }

                if (isset($mgr_wd) && !is_blank($mgr_wd)) {
                    $this->product->setMgrWd($mgr_wd);
                }

                $device = $this->product->check_device_account();

                if ($device !== false && !is_blank($device)) {

                    $delete = false;

                    if (($timer == '-' && $latitude != '-' && $longitude != '-') || ($timer != '-' && $latitude == '-' && $longitude == '-')) {
                        if( $mgr_delete == "0" ) {
                            if ($this->product->check_mgr_timer()) {
                                $update_mrg_timer = $this->product->update_mrg_timer();

                            } else {

                                $insert_to_mrg = true;

                                /*Check GPS cool*/
                                if ($timer == '-' && $latitude != '-' && $longitude != '-') {
                                    if ($this->product->check_mgr_timer()) {
                                        $insert_to_mrg = false;
                                    }
                                }

                                if ($insert_to_mrg == true) {
                                    $this->product->add_mrg_timer();
                                }

                            }

                        } else {
                            $delete = $this->product->delete_mrg_timer();
                        }
                    }

                    if ($delete !== false && !is_blank($delete)) {

                        $http_code = REST_Controller::HTTP_OK;
                        $message = array(
                            'status' => true,
                            'code' => $http_code,
                            'message' => "Event delete successfully.",
                            'data' => array()
                        );

                    } else {
                        /*event*/
                        $mgr_event = $this->product->check_mgr_timer();

                        if ($mgr_event !== false && !is_blank($mgr_event)) {
                            $http_code = REST_Controller::HTTP_OK;
                            $message = array(
                                'status' => true,
                                'code' => $http_code,
                                'message' => "Event updated successfully.",
                                'data' => $mgr_event
                            );

                        } else {
                            $http_code = REST_Controller::HTTP_BAD_REQUEST;
                            $message = array(
                                'status' => false,
                                'code' => $http_code,
                                'message' => "Event updated fail.",
                                'data' => array()
                            );
                        }
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

                $id = $token_info->id;

                $this->product->setUserID($id);
                
                $serial = $this->get('serial');

                if (isset($serial) && !is_blank($serial)) {
                    $this->product->setSerial($serial);
                }

                $device = $this->product->check_air_mn();

                if ($device !== false && !is_blank($device)) {

                    $air_info = $this->product->air_info();
                    
                    if ($air_info !== false && !is_blank($air_info)) {

                        $this->product->setAir_type_id($air_info);
                        $air_type = $this->product->air_type();

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
