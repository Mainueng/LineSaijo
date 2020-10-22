<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';

class Ewarranty extends REST_Controller
{

    function __construct()
    {
//        parent::__construct();
        parent::__construct();
    }

    public function index_get()
    {

    }

    public function index_post()
    {

    }

    public function valid_product_post()
    {
        $message = array();
        $data_info = array();
        $http_code = "";

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
                $this->load->model($this->product_model, 'product');
                $this->product->setSerial($serial);
                $result = $this->product->product_valid_warranty();

                if ($result) {
                    $is_active = $this->product->getActiveStatus();
                    if ($is_active == 1) {
                        $http_code = REST_Controller::HTTP_BAD_REQUEST;
                        $data_info = array(
                            'status' => false,
                            'code' => $http_code,
                            'message' => 'Serial นี้ทำการลงทะเบียน E-Warranty แล้ว'
                        );
                    } else {
                        $http_code = REST_Controller::HTTP_OK;
                        $product_info = $this->product->product_info();
                        $data_info = array(
                            'status' => true,
                            'code' => $http_code,
                            'message' => 'Validate product successfully',
                            'data' => $product_info

                        );
                    }

                } else {

                    //Wrong or is not serial product
                    $http_code = REST_Controller::HTTP_BAD_REQUEST;

                    $data_info = array(
                        'error' => true,
                        'status' => false,
                        'message' => 'ไม่มีรหัสสินค้านี้ในระบบ',
                        'code' => $http_code
                    );


                }


            }

        } else {
            $http_code = REST_Controller::HTTP_BAD_REQUEST;
            $data_info = array(
                'error' => true,
                'status' => false,
                'message' => 'Login Fail',
                'code' => $http_code
            );
        }


        $this->response($data_info, $http_code);


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

            $this->response($data_info, REST_Controller::HTTP_OK);

        } else {
            $this->response(array('status' => FALSE, 'message' => $this->_authen_message), REST_Controller::HTTP_NOT_FOUND);
        }

    }

    public function do_product_warranty($serial)
    {
        $this->load->model($this->product_model, 'product');
        if (!is_blank($serial)) {

            $product_active = $this->check_product_active($serial);

            if (!$product_active) {
                $this->product->setSerial($serial);
                $check_active = $this->product->product_activate();
                $result = array();
                if ($check_active) {
                    $this->product->setSerial($serial);
                    $result = $this->product->product_info();

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

            $this->load->model($this->product_model, 'product');
            $this->product->setSerial($serial);
            $result = $this->product->product_valid_warranty();
            $status = false;
            if ($result) {
                $is_active = $this->product->getActiveStatus();
                if ($is_active == 1) {
                    $status = true;
                } else {
                    $status = false;
                }
            }

            return $status;

        }
    }


} // end of class
