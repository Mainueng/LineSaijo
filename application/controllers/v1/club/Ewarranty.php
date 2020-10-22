<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';

class Ewarranty extends REST_Controller
{

    function __construct()
    {
        parent::__construct();

        $this->methods['valid_product_post']['limit'] = 500;
        $this->methods['products_warranty_post']['limit'] = 500;

        $this->load->library('Authorization_Token');

        $this->load->model($this->warranty_model, 'warranty');
        $this->load->model('Device_model', 'device');
        $this->load->model('User_model', 'user');
    }

    public function valid_product_post()
    {

        if ($this->is_authen()) {

            $data = $this->security->xss_clean($_POST);
            $serial = "";
            if (!is_blank($data) && is_array($data)) {
                if (isset($data['serial'])) {
                    $serial = $data['serial'];
                }
            }

            // Check Product and active
            if (!is_blank($serial)) {

                $this->warranty->setSerial($serial);
                $result = $this->warranty->product_valid_warranty();

                if ($result) {
                    $is_active = $this->warranty->getActiveStatus();
                    if ($is_active == 1 || $serial != '8888A00000008') {
                        $http_code = REST_Controller::HTTP_BAD_REQUEST;
                        $message = array(
                            'status' => false,
                            'code' => $http_code,
                            'message' => 'Serial นี้ทำการลงทะเบียน E-Warranty แล้ว',
                            'data' => array()
                        );
                    } else {
                        $http_code = REST_Controller::HTTP_OK;
                        $product_warranty_info = $this->warranty->product_warranty_info();
                        $message = array(
                            'status' => true,
                            'code' => $http_code,
                            'message' => 'Validate product successfully',
                            'data' => array($product_warranty_info)

                        );
                    }

                } else {

                    //Wrong or is not serial product
                    $http_code = REST_Controller::HTTP_BAD_REQUEST;

                    $message = array(
                        'status' => false,
                        'code' => $http_code,
                        'message' => 'ไม่มีรหัสสินค้านี้ในระบบ',
                        'data' => array()
                    );


                }


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

    public function products_warranty_post()
    {
        if ($this->is_authen()) {

            $data = $this->security->xss_clean($_POST);
            $serial = "";
            if (!is_blank($data) && is_array($data)) {
                if (isset($data['serial'])) {
                    $serial = $data['serial'];
                }
            }

            if (!is_blank($serial)) {
                $serial = explode('|', $serial);
            }

            $data_info = array();
            foreach ($serial as $key) {

                $data_info[] = array(
                    'serail' => $key,
                    'status' => $this->do_product_warranty($key)
                );

            }

            $http_code = REST_Controller::HTTP_OK;
            $message = array(
                'status' => true,
                'code' => $http_code,
                'message' => "Success",
                'data' => $data_info
            );

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

    public function do_product_warranty($serial)
    {

        if (!is_blank($serial)) {

            $product_active = $this->check_product_active($serial);

            if (!$product_active) {
                $this->warranty->setSerial($serial);
                $check_active = $this->warranty->product_activate();
                $result = array();
                if ($check_active) {
                    $this->warranty->setSerial($serial);
                    $result = $this->warranty->product_warranty_info();

                    $warranty_info = array();
                    if (is_array($result)) {
                        foreach ($result as $key => $val) {
                            $warranty_info[$key] = $val;
                        }
                    }
                    $warranty = $warranty_info['warranty_info'];

                    foreach ($warranty as $row) {
                        $rows[] = array(
                            'serial' => $warranty_info['serial'],
                            'warranty_id' => get_array_value($row, 'warranty_id', ''),
                            'default_warranty' => get_array_value($row, 'default_warranty', ''),
                            'e_warranty' => get_array_value($row, 'e_warranty', ''),
                            'day' => get_array_value($row, 'all_warranty_days', ''),
                            'cus_mstr_id' => $this->_user_id
                        );
                    }

                    $this->load->model($this->warranty_model, 'warranty');


                    $check_add = 0;
                    $check_add = $this->warranty->add_warranty_product($rows);


                    if ($check_add > 0) {
                        return true;
                    } else {
                        return false;
                    }


                }

            } else {
                return false;
            }
        }
        return false;

    }

    public function check_product_active($serial)
    {
        if (!is_blank($serial)) {

            $this->warranty->setSerial($serial);
            $result = $this->warranty->product_valid_warranty();
            $status = false;
            if ($result) {
                $is_active = $this->warranty->getActiveStatus();
                if ($is_active == 1) {
                    $status = true;
                } else {
                    $status = false;
                }
            }

            return $status;

        }
    }

    public function tech_valid_product_post()
    {
        if ($this->is_authen()) {

            $token_info = $this->authorization_token->userData();

            if ($token_info->user_role_id == 3 || $token_info->user_role_id == 4 || $token_info->user_role_id == 1 || $token_info->user_role_id == 2) {

                $data = $this->security->xss_clean($_POST);

                $this->form_validation->set_rules('serial', 'Serial', 'trim|required');

                if ($this->form_validation->run() == TRUE) {

                    $serial = trim($this->post('serial'));

                    // Check Product and active
                    if (!is_blank($serial)) {

                        $this->warranty->setSerial($serial);
                        $result = $this->warranty->product_valid_warranty();

                        if ($result) {
                            $is_active = $this->warranty->getActiveStatus();

                            $str = substr($serial, 4, 1);

                            if ($str == "A") {

                                if ($is_active && $serial != '8888A00000008') {
                                    $http_code = REST_Controller::HTTP_ACCEPTED;
                                    $message = array(
                                        'status' => true,
                                        'code' => $http_code,
                                        'message' => 'Serial นี้ทำการลงทะเบียน E-Warranty แล้ว',
                                        'data' => array(
                                            array(
                                                'indoor_serial' => $serial,
                                                'outdoor_serial' => $serial,
                                            )
                                        )
                                    );
                                } else {

                                    $http_code = REST_Controller::HTTP_OK;
                                    $product_activate = $this->warranty->product_activate();
                                    $message = array(
                                        'status' => true,
                                        'code' => $http_code,
                                        'message' => 'Validate product successfully.',
                                        'data' => array(
                                            array(
                                                'serial' => $serial,
                                                'type' => productType($serial)
                                            )
                                        )

                                    );
                                }

                            } else {

                                if ($token_info->user_role_id != 3) {
                                    $id = $this->user->getIot_ID($token_info->id); 
                                    $this->device->setUserID($id);
                                    $this->device->setSerial($serial);
                                    $device = $this->device->check_iot();

                                    if ($device == 0 && $device !== false) {

                                        $count = $this->device->device_count();

                                        if ($count !== false && !is_blank($count)) {

                                            $add = $this->device->add_device();

                                        }

                                    }
                                }

                                $warranty_match = $this->warranty->get_warranty_match();

                                if ($is_active && $warranty_match) {
                                    $http_code = REST_Controller::HTTP_ACCEPTED;
                                    $message = array(
                                        'status' => true,
                                        'code' => $http_code,
                                        'message' => 'Serial นี้ทำการลงทะเบียน E-Warranty แล้ว',
                                        'data' => array($warranty_match)
                                    );
                                } else {

                                    $this->warranty->setSerial($serial);
                                    $info = $this->warranty->product_warranty_info();

                                    $http_code = REST_Controller::HTTP_OK;

                                    if (!$is_active) {
                                        $this->warranty->product_activate();
                                    }

                                    $message = array(
                                        'status' => true,
                                        'code' => $http_code,
                                        'message' => 'Validate product successfully.',
                                        'data' => array(
                                            array(
                                                'serial' => $serial,
                                                'type' => productType($serial),
                                                // 'warranty_info' => $info
                                            )
                                        )

                                    );
                                }
                            }

                        } else {

                        //Wrong or is not serial product
                            $http_code = REST_Controller::HTTP_BAD_REQUEST;

                            $message = array(
                                'status' => false,
                                'code' => $http_code,
                                'message' => 'ไม่มีรหัสสินค้านี้ในระบบ',
                                'data' => array()
                            );


                        }


                    } else {

                        //Wrong or is not serial product
                        $http_code = REST_Controller::HTTP_BAD_REQUEST;

                        $message = array(
                            'status' => false,
                            'code' => $http_code,
                            'message' => 'ไม่มีรหัสสินค้านี้ในระบบ',
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
            # code...
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

    public function tech_warranty_info_post()
    {
        if ($this->is_authen()) {

            $token_info = $this->authorization_token->userData();

            if ($token_info->user_role_id == 3 || $token_info->user_role_id == 4 || $token_info->user_role_id == 1 || $token_info->user_role_id == 2) {

                $this->warranty->setID($token_info->id);

                $data = $this->security->xss_clean($_POST);
                $indoor_serial = "";
                $outdoor_serial = "";

                $this->form_validation->set_rules('indoor_serial', 'Indoor Serial', 'trim');
                $this->form_validation->set_rules('outdoor_serial', 'Outdoor Serial', 'trim');

                $indoor_serial = $this->post('indoor_serial');
                $outdoor_serial = $this->post('outdoor_serial');

                $data = array();

                $indoor = '';
                $outdoor = '';
                $air_puri = '';

                $str = substr($indoor_serial, 4, 1);

                //$this->warranty->setTech(true);

                // Check Product and active
                if (!is_blank($indoor_serial)) {

                    $this->warranty->setSerial($indoor_serial);

                    if($str != "A") {
                        $indoor = $this->warranty->product_warranty_info();

                        $data[0] = $indoor;
                    }
                }

                if (!is_blank($outdoor_serial)) {

                    $this->warranty->setSerial($outdoor_serial);
                    $outdoor = $this->warranty->product_warranty_info();

                    $data[1] = $outdoor;
                }

                if ($str != "A") {

                    $warranty_match = $this->warranty->get_warranty_match();
                }

                if ($indoor && $outdoor) {

                    if ($warranty_match) {

                        if ($token_info->user_role_id == 2 || $token_info->user_role_id == 4) {

                            $this->warranty->setID($token_info->id);

                            $add_warranty_to_cus = $this->warranty->add_warranty_to_cus();
                        }

                        $http_code = REST_Controller::HTTP_OK;
                        $message = array(
                            'status' => true,
                            'code' => $http_code,
                            'message' => 'Serial นี้ทำการลงทะเบียน E-Warranty แล้ว',
                            'data' => $data
                        );

                    } else {
                        $this->warranty->setIndoor($indoor_serial);
                        $this->warranty->setOutdoor($outdoor_serial);

                        if ($indoor_serial != '8888F00000008' || $outdoor_serial != '8888C00000008') {
                            $this->warranty->warranty_match();

                            if ($token_info->user_role_id == 4 || $token_info->user_role_id == 2) {

                                $this->warranty->setID($token_info->id);

                                $add_warranty_to_cus = $this->warranty->add_warranty_to_cus();
                            }
                        }

                        $http_code = REST_Controller::HTTP_OK;
                        $message = array(
                            'status' => true,
                            'code' => $http_code,
                            'message' => 'Success',
                            'data' => $data

                        );
                    }

                } elseif ($str == "A") {

                    $air_puri_active = false;

                    if ($indoor_serial != '8888A00000008') {
                        $air_puri_active = $this->warranty->getActiveStatus();
                    }

                    $air_puri = $this->warranty->product_warranty_info();

                    $data[0] = $air_puri;

                    if ($air_puri_active) {
                        $http_code = REST_Controller::HTTP_OK;
                        $message = array(
                            'status' => true,
                            'code' => $http_code,
                            'message' => 'Serial นี้ทำการลงทะเบียน E-Warranty แล้ว',
                            'data' => $data
                        );

                    } else {

                        if ($air_puri) {

                            $http_code = REST_Controller::HTTP_OK;
                            $message = array(
                                'status' => true,
                                'code' => $http_code,
                                'message' => 'Success',
                                'data' => $data

                            );

                        } else {

                            $http_code = REST_Controller::HTTP_BAD_REQUEST;

                            $message = array(
                                'status' => false,
                                'code' => $http_code,
                                'message' => 'ไม่มีรหัสสินค้านี้ในระบบ',
                                'data' => array()
                            );

                        }
                    }

                } else {

                    //Wrong or is not serial product
                    $http_code = REST_Controller::HTTP_BAD_REQUEST;

                    $message = array(
                        'status' => false,
                        'code' => $http_code,
                        'message' => 'ไม่มีรหัสสินค้านี้ในระบบ',
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
            # code...
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



} // end of class
