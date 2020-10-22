<?php

defined('BASEPATH') OR exit('No direct script access allowed');

use Restserver\Libraries\REST_Controller;
require APPPATH . '/libraries/REST_Controller.php';


class Widget extends REST_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->load->library('Authorization_Token');

        $this->load->model('Device_model', 'device');
        $this->load->model('Widget_model', 'widget');
        $this->load->model('User_model', 'user');

        // Configure limits on our controller methods
        // Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
        $this->methods['widget_list_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['widget_list_put']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['schedule_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['schedule_post']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['schedule_put']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['schedule_delete']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['schedule_info_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['energy_day_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['energy_month_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['location_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['location_put']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['location_all_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['error_code_get']['limit'] = 500; // 500 requests per hour per user/key
    }

    public function widget_list_get()
    {
        if ($this->is_authen()) {

            $token_info = $this->authorization_token->userData();

            if ($token_info->user_role_id == 4 || $token_info->user_role_id == 1 || $token_info->user_role_id == 2) {

                $serial = $this->get('serial');

                $id = $token_info->id;

                if (isset($id) && !is_blank($id)) {
                    $this->device->setUserID($id);
                }

                if (isset($serial) && !is_blank($serial)) {
                    $this->device->setSerial($serial);
                }

                $data = $this->device->get_function_list();

                if ($data !== false && !is_blank($data)) {

                    $http_code = REST_Controller::HTTP_OK;
                    $message = array(
                        'status' => true,
                        'code' => $http_code,
                        'message' => 'Success.',
                        'data' => $data
                    );
                } else {

                    $http_code = REST_Controller::HTTP_OK;
                    $message = array(
                        'status' => false,
                        'code' => $http_code,
                        'message' => "Function list not found.",
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

    public function widget_list_put()
    {
        if ($this->is_authen()) {

            $token_info = $this->authorization_token->userData();

            if ($token_info->user_role_id == 4 || $token_info->user_role_id == 1 || $token_info->user_role_id == 2) {

                $this->form_validation->set_rules('serial', 'Serial', 'trim|require');

                $serial = $this->get('serial');

                $function = $this->put('function');

                $id = $token_info->id;

                if (isset($id) && !is_blank($id)) {
                    $this->device->setUserID($id);
                }

                if (isset($serial) && !is_blank($serial)) {
                    $this->device->setSerial($serial);
                }

                if (isset($function) && !is_blank($function)) {
                    $this->device->setCusFunction($function);
                }

                if ($function != '' && strlen($function) >= 1 && strlen($function) <= 256) {

                    $data = $this->device->get_function_list();

                    if ($data !== false && !is_blank($data)) {

                        $update_function = $this->device->update_function_list();

                        if ($update_function) {

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
                                'message' => 'Update function list fail.',
                                'data' => array()
                            );
                        }

                    } else {

                        $add_function = $this->device->add_function_list();

                        $http_code = REST_Controller::HTTP_OK;
                        $message = array(
                            'status' => true,
                            'code' => $http_code,
                            'message' => 'Success.',
                            'data' => array()
                        );
                    }

                } else {

                    $http_code = REST_Controller::HTTP_BAD_REQUEST;
                    $message = array(
                        'status' => false,
                        'code' => $http_code,
                        'message' => "Invalid function list.",
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

    public function schedule_info_get()
    {
        if ($this->is_authen()) {

            $message = array();

            $token_info = $this->authorization_token->userData();

            if ($token_info->user_role_id == 4 || $token_info->user_role_id == 1 || $token_info->user_role_id == 2) {

                $id = $token_info->id;

                if ($id !== false && !is_blank($id)) {

                    $this->widget->setUserID($id);

                    $serial = strtoupper($this->get('serial'));
                    $mid = $this->get('mid');

                    if (isset($serial) && !is_blank($serial)) {
                        $this->widget->setSerial($serial);
                    } else {
                        $this->widget->setSerial(0);
                    }

                    if (isset($mid) && !is_blank($mid)) {
                        $this->widget->setMid($mid);
                    } else {
                        $this->widget->setMid(0);
                    }

                    $data = $this->widget->schedule_info();

                    if ($data !== false && !is_blank($data)) {

                        $http_code = REST_Controller::HTTP_OK;
                        $message = array(
                            'status' => true,
                            'code' => $http_code,
                            'message' => "Success.",
                            'data' => $data
                        );

                    } else {

                        $http_code = REST_Controller::HTTP_OK;
                        $message = array(
                            'status' => false,
                            'code' => $http_code,
                            'message' => "Schedule not found.",
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

    public function schedule_get()
    {
        if ($this->is_authen()) {

            $message = array();

            $token_info = $this->authorization_token->userData();

            if ($token_info->user_role_id == 4 || $token_info->user_role_id == 1 || $token_info->user_role_id == 2) {

                $id = $token_info->id;

                if ($id !== false && !is_blank($id)) {

                    $this->widget->setUserID($id);

                    $serial = strtoupper($this->get('serial'));

                    if (isset($serial) && !is_blank($serial)) {
                        $this->widget->setSerial($serial);
                    } else {
                        $this->widget->setSerial(0);
                    }

                    $data = $this->widget->schedule_list();

                    if ($data !== false && !is_blank($data)) {

                        $http_code = REST_Controller::HTTP_OK;
                        $message = array(
                            'status' => true,
                            'code' => $http_code,
                            'message' => "Success.",
                            'data' => $data
                        );

                    } else {

                        $http_code = REST_Controller::HTTP_OK;
                        $message = array(
                            'status' => false,
                            'code' => $http_code,
                            'message' => "Schedule not found.",
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

    public function schedule_post()
    {
        if ($this->is_authen()) {

            $message = array();

            $this->form_validation->set_rules('device_id', 'Device ID', 'trim|required');

            $token_info = $this->authorization_token->userData();

            if ($token_info->user_role_id == 4 || $token_info->user_role_id == 1 || $token_info->user_role_id == 2) {

                $id = $token_info->id;

                if ($id !== false && !is_blank($id) && $id != 0) {

                    $serial = strtoupper($this->get('serial'));

                    if (isset($serial) && !is_blank($serial)) {

                        $this->widget->setUserID($id);
                        $this->widget->setSerial($serial);

                        $timer = $this->post('timer');
                        $utc = $this->post('utc');
                        $power = $this->post('power');
                        $energy = $this->post('energy');
                        $set_temp = $this->post('set_temp');
                        $group = $this->post('group');
                        $enable = $this->post('enable');
                        $weekday = $this->post('weekday');

                        if (isset($timer) && !is_blank($timer)) {
                            $this->widget->setTime($timer);
                        } else {
                            $this->widget->setTime('-');
                        }

                        if (isset($utc) && !is_blank($utc)) {
                            $this->widget->setUtc($utc);
                        } else {
                            $this->widget->setUtc('-');
                        }

                        if (isset($power) && !is_blank($power)) {
                            $this->widget->setPower($power);
                        } else {
                            $this->widget->setPower('-');
                        }

                        if (isset($energy) && !is_blank($energy)) {
                            $this->widget->setEnergy($energy);
                        } else {
                            $this->widget->setEnergy('-');
                        }

                        if (isset($set_temp) && !is_blank($set_temp)) {
                            $this->widget->setTemp($set_temp);
                        } else {
                            $this->widget->setTemp('-');
                        }

                        if (isset($group) && !is_blank($group)) {
                            $this->widget->setGroup($group);
                        } else {
                            $this->widget->setGroup('-');
                        }

                        if (isset($enable) && !is_blank($enable)) {
                            $this->widget->setEnable($enable);
                        } else {
                            $this->widget->setEnable(0);
                        }

                        if (isset($weekday) && !is_blank($weekday)) {
                            $this->widget->setWeekday($weekday);
                        } else {
                            $this->widget->setWeekday(0);
                        }

                        $is_duplicate = $this->widget->get_schedule();

                        if ($is_duplicate && !is_blank($is_duplicate)) {

                            $http_code = REST_Controller::HTTP_BAD_REQUEST;
                            $message = array(
                                'status' => false,
                                'code' => $http_code,
                                'message' => "This schedule already exist.",
                                'data' => array()
                            );

                        } else {

                            $add_schedule = $this->widget->add_schedule();

                            if ($add_schedule !== false && !is_blank($add_schedule)) {

                                $http_code = REST_Controller::HTTP_OK;
                                $message = array(
                                    'status' => true,
                                    'code' => $http_code,
                                    'message' => "Success.",
                                    'data' => array()
                                );
                            } else {

                                $http_code = REST_Controller::HTTP_BAD_REQUEST;
                                $message = array(
                                    'status' => false,
                                    'code' => $http_code,
                                    'message' => "Add schedule fail.",
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

    public function schedule_put()
    {
        if ($this->is_authen()) {

            $message = array();

            $this->form_validation->set_rules('device_id', 'Device ID', 'trim|required');

            $token_info = $this->authorization_token->userData();

            if ($token_info->user_role_id == 4 || $token_info->user_role_id == 1 || $token_info->user_role_id == 2) {

                $id = $token_info->id;

                if ($id !== false && !is_blank($id) && $id != 0) {

                    $serial = strtoupper($this->get('serial'));

                    if (isset($serial) && !is_blank($serial)) {

                        $this->widget->setUserID($id);
                        $this->widget->setSerial($serial);

                        $mid = $this->put('mid');
                        $timer = $this->put('timer');
                        $utc = $this->put('utc');
                        $power = $this->put('power');
                        $energy = $this->put('energy');
                        $set_temp = $this->put('set_temp');
                        $group = $this->put('group');
                        $enable = $this->put('enable');
                        $weekday = $this->put('weekday');

                        if (isset($mid) && !is_blank($mid)) {
                            $this->widget->setMid($mid);
                        } else {
                            $this->widget->setMid(0);
                        }

                        if (isset($timer) && !is_blank($timer)) {
                            $this->widget->setTime($timer);
                        } else {
                            $this->widget->setTime('-');
                        }

                        if (isset($utc) && !is_blank($utc)) {
                            $this->widget->setUtc($utc);
                        } else {
                            $this->widget->setUtc('-');
                        }

                        if (isset($power) && !is_blank($power)) {
                            $this->widget->setPower($power);
                        } else {
                            $this->widget->setPower('-');
                        }

                        if (isset($energy) && !is_blank($energy)) {
                            $this->widget->setEnergy($energy);
                        } else {
                            $this->widget->setEnergy('-');
                        }

                        if (isset($set_temp) && !is_blank($set_temp)) {
                            $this->widget->setTemp($set_temp);
                        } else {
                            $this->widget->setTemp('-');
                        }

                        if (isset($group) && !is_blank($group)) {
                            $this->widget->setGroup($group);
                        } else {
                            $this->widget->setGroup('-');
                        }

                        if (isset($enable) && !is_blank($enable)) {
                            $this->widget->setEnable($enable);
                        } else {
                            $this->widget->setEnable(0);
                        }

                        if (isset($weekday) && !is_blank($weekday)) {
                            $this->widget->setWeekday($weekday);
                        } else {
                            $this->widget->setWeekday(0);
                        }

                        $check_schedule = $this->widget->check_schedule();

                        if ($check_schedule && !is_blank($check_schedule)) {

                            $update_schedule = $this->widget->update_schedule();

                            if ($update_schedule !== false && !is_blank($update_schedule)) {

                                $http_code = REST_Controller::HTTP_OK;
                                $message = array(
                                    'status' => true,
                                    'code' => $http_code,
                                    'message' => "Success.",
                                    'data' => array()
                                );
                            } else {

                                $http_code = REST_Controller::HTTP_BAD_REQUEST;
                                $message = array(
                                    'status' => false,
                                    'code' => $http_code,
                                    'message' => "Update schedule fail.",
                                    'data' => array()
                                );
                            }

                        } else {

                            $http_code = REST_Controller::HTTP_BAD_REQUEST;
                            $message = array(
                                'status' => false,
                                'code' => $http_code,
                                'message' => "Schedule not found.",
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

    public function schedule_delete()
    {
        if ($this->is_authen()) {

            $message = array();

            $this->form_validation->set_rules('device_id', 'Device ID', 'trim|required');

            $token_info = $this->authorization_token->userData();

            if ($token_info->user_role_id == 4 || $token_info->user_role_id == 1 || $token_info->user_role_id == 2) {

                $id = $token_info->id;

                if ($id !== false && !is_blank($id) && $id != 0) {

                    $serial = strtoupper($this->get('serial'));

                    if (isset($serial) && !is_blank($serial)) {

                        $this->widget->setUserID($id);
                        $this->widget->setSerial($serial);

                        $mid = $this->delete('mid');

                        if (isset($mid) && !is_blank($mid)) {
                            $this->widget->setMid($mid);
                        } else {
                            $this->widget->setMid(0);
                        }

                        $check_schedule = $this->widget->check_schedule();

                        if ($check_schedule && !is_blank($check_schedule)) {

                            $delete_schedule = $this->widget->delete_schedule();

                            if ($delete_schedule !== false && !is_blank($delete_schedule)) {

                                $http_code = REST_Controller::HTTP_OK;
                                $message = array(
                                    'status' => true,
                                    'code' => $http_code,
                                    'message' => "Success.",
                                    'data' => array()
                                );
                            } else {

                                $http_code = REST_Controller::HTTP_BAD_REQUEST;
                                $message = array(
                                    'status' => false,
                                    'code' => $http_code,
                                    'message' => "Delete schedule fail.",
                                    'data' => array()
                                );
                            }

                        } else {

                            $http_code = REST_Controller::HTTP_BAD_REQUEST;
                            $message = array(
                                'status' => false,
                                'code' => $http_code,
                                'message' => "Schedule not found.",
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

    public function energy_day_get()
    {
        if ($this->is_authen()) {

            $message = array();

            $token_info = $this->authorization_token->userData();

            if ($token_info->user_role_id == 4 || $token_info->user_role_id == 1 || $token_info->user_role_id == 2) {

                $id = $this->user->getIot_ID($token_info->id);

                if ($id !== false && !is_blank($id)) {
                    $this->device->setUserID($id);
                    $this->widget->setUserID($id);

                    $serial = $this->get('serial');
                    $this->device->setSerial($serial);
                    $this->widget->setSerial($serial);

                    $chk = $this->device->check_air_mn();

                    if ($chk !== false && !is_blank($chk)) {

                        $info = $this->widget->energy_day();

                        if ($info !== false && !is_blank($info)) {

                            $http_code = REST_Controller::HTTP_OK;
                            $message = array(
                                'status' => true,
                                'code' => $http_code,
                                'message' => "Energy information.",
                                'data' => array(array(
                                    "serial" => $serial,
                                    "unit" => number_format(4.0, 2, '.', ','),
                                    "currency" => "THB",
                                    "energy" => $info
                                ))
                            );
                        } else {

                            $http_code = REST_Controller::HTTP_BAD_REQUEST;
                            $message = array(
                                'status' => false,
                                'code' => $http_code,
                                'message' => "Energy information not found.",
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

    public function energy_month_get()
    {
        if ($this->is_authen()) {

            $message = array();

            $token_info = $this->authorization_token->userData();

            if ($token_info->user_role_id == 4 || $token_info->user_role_id == 1 || $token_info->user_role_id == 2) {

                $id = $this->user->getIot_ID($token_info->id);

                if ($id !== false && !is_blank($id)) {
                    $this->device->setUserID($id);
                    $this->widget->setUserID($id);

                    $serial = $this->get('serial');

                    if (isset($serial) && !is_blank($serial)) {
                        $this->device->setSerial($serial);
                        $this->widget->setSerial($serial);
                    }

                    $chk = $this->device->check_air_mn();

                    if ($chk !== false && !is_blank($chk)) {

                        $info = $this->widget->energy_month();

                        if ($info !== false && !is_blank($info)) {

                            $http_code = REST_Controller::HTTP_OK;
                            $message = array(
                                'status' => true,
                                'code' => $http_code,
                                'message' => "Energy information.",
                                'data' => array(array(
                                    "serial" => $serial,
                                    "unit" => number_format(4.0, 2, '.', ','),
                                    "currency" => "THB",
                                    "energy" => $info
                                ))
                            );
                        } else {

                            $http_code = REST_Controller::HTTP_BAD_REQUEST;
                            $message = array(
                                'status' => false,
                                'code' => $http_code,
                                'message' => "Energy information not found.",
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

    public function location_get()
    {
        if ($this->is_authen()) {

            $message = array();

            $token_info = $this->authorization_token->userData();

            if ($token_info->user_role_id == 4 || $token_info->user_role_id == 1 || $token_info->user_role_id == 2) {

                $id = $token_info->id;

                if ($id !== false && !is_blank($id)) {

                    $this->widget->setUserID($id);

                    $serial = strtoupper($this->get('serial'));

                    if (isset($serial) && !is_blank($serial)) {
                        $this->widget->setSerial($serial);
                        $this->device->setSerial($serial);
                    } else {
                        $this->widget->setSerial(0);
                        $this->device->setSerial(0);
                    }

                    $check_device = $this->device->check_device();

                    if ($check_device !== false && !is_blank($check_device)) {

                        $data = $this->widget->device_location();

                        if ($data !== false && !is_blank($data)) {

                            $http_code = REST_Controller::HTTP_OK;
                            $message = array(
                                'status' => true,
                                'code' => $http_code,
                                'message' => "Success.",
                                'data' => $data
                            );

                        } else {

                            $http_code = REST_Controller::HTTP_OK
                            ;
                            $message = array(
                                'status' => false,
                                'code' => $http_code,
                                'message' => "Location not found.",
                                'data' => array(array(
                                    'mid' => '0',
                                    'latitude' => '',
                                    'longitude' => '',
                                ))
                            );
                        }
                    } else {
                        $http_code = REST_Controller::HTTP_BAD_REQUEST;
                        $message = array(
                            'status' => false,
                            'code' => $http_code,
                            'message' => "Device not found.",
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

    public function location_put()
    {
        if ($this->is_authen()) {

            $message = array();

            $token_info = $this->authorization_token->userData();

            if ($token_info->user_role_id == 4 || $token_info->user_role_id == 1 || $token_info->user_role_id == 2) {

                $id = $token_info->id;

                if ($id !== false && !is_blank($id)) {

                    $this->widget->setUserID($id);

                    $serial = strtoupper($this->get('serial'));
                    $mid = $this->put('mid');
                    $latitude = $this->put('latitude');
                    $longitude = $this->put('longitude');

                    if (isset($serial) && !is_blank($serial)) {
                        $this->widget->setSerial($serial);
                        $this->device->setSerial($serial);
                    } else {
                        $this->widget->setSerial(0);
                        $this->device->setSerial(0);
                    }

                    if (isset($latitude) && !is_blank($latitude)) {
                        $this->widget->setLatitude($latitude);
                    } else {
                        $this->widget->setLatitude(0);
                    }

                    if (isset($longitude) && !is_blank($longitude)) {
                        $this->widget->setLongitude($longitude);
                    } else {
                        $this->widget->setLongitude(0);
                    }

                    $check_device = $this->device->check_device();

                    if ($check_device !== false && !is_blank($check_device)) {

                        $data = $this->widget->update_location();

                        if ($data !== false && !is_blank($data)) {

                            $http_code = REST_Controller::HTTP_OK;
                            $message = array(
                                'status' => true,
                                'code' => $http_code,
                                'message' => "Success.",
                                'data' => array()
                            );

                        } else {

                            $http_code = REST_Controller::HTTP_BAD_REQUEST;
                            $message = array(
                                'status' => false,
                                'code' => $http_code,
                                'message' => "Update location fail.",
                                'data' => array()
                            );
                        }
                    } else {
                        $http_code = REST_Controller::HTTP_BAD_REQUEST;
                        $message = array(
                            'status' => false,
                            'code' => $http_code,
                            'message' => "Device not found.",
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

    public function all_location_get()
    {
        if ($this->is_authen()) {

            $message = array();

            $token_info = $this->authorization_token->userData();

            if ($token_info->user_role_id == 4 || $token_info->user_role_id == 1 || $token_info->user_role_id == 2) {

                $id = $token_info->id;

                if ($id !== false && !is_blank($id)) {

                    $this->widget->setUserID($id);

                    $data = $this->widget->all_location();

                    if ($data !== false && !is_blank($data)) {

                        $http_code = REST_Controller::HTTP_OK;
                        $message = array(
                            'status' => true,
                            'code' => $http_code,
                            'message' => "Success.",
                            'data' => $data
                        );

                    } else {

                        $http_code = REST_Controller::HTTP_OK
                        ;
                        $message = array(
                            'status' => false,
                            'code' => $http_code,
                            'message' => "Location not found.",
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

    public function error_code_get()
    {
        if ($this->is_authen()) {

            $message = array();

            $token_info = $this->authorization_token->userData();

            if ($token_info->user_role_id == 4 || $token_info->user_role_id == 1 || $token_info->user_role_id == 2) {

                $id = $token_info->id;

                if ($id !== false && !is_blank($id)) {

                    $this->widget->setUserID($id);

                    $error_code = $this->get('id');

                    if (isset($error_code) && !is_blank($error_code)) {
                        $this->widget->setErrorCode($error_code);
                    } else {
                        $this->widget->setErrorCode(0);
                    }

                    $data = $this->widget->error_code_title();

                    if ($data !== false && !is_blank($data)) {

                        $http_code = REST_Controller::HTTP_OK;
                        $message = array(
                            'status' => true,
                            'code' => $http_code,
                            'message' => "Success.",
                            'data' => $data
                        );

                    } else {

                        $http_code = REST_Controller::HTTP_BAD_REQUEST;
                        $message = array(
                            'status' => false,
                            'code' => $http_code,
                            'message' => "Error code not found.",
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
}
