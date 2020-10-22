<?php

defined('BASEPATH') OR exit('No direct script access allowed');

use Restserver\Libraries\REST_Controller;
require APPPATH . '/libraries/REST_Controller.php';


class User extends REST_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->load->library('Authorization_Token');

        $this->load->model('User_model', 'user');

        // Configure limits on our controller methods
        // Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
        $this->methods['users_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['users_post']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['users_delete']['limit'] = 50; // 50 requests per hour per user/key
        $this->methods['users_put']['limit'] = 50; // 50 requests per hour per user/key
    }

    public function users_get()
    {
        if ($this->is_authen()) {

            $message = array();
            $id = $this->get('id');
            $this->user->getUserID($id);
            $info = $this->user->getInfo();

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
                    'message' => "No users were found."
                );
            }

            $this->response($message, $http_code);

        } else {

            $this->response(array('status' => FALSE, 'message' => $this->_authen_message), REST_Controller::HTTP_NOT_FOUND);
        }
    }


    public function users_post()
    {

        if ($this->is_authen()) {

            $data = $this->security->xss_clean($_POST);
            $this->form_validation->set_rules('cus_name', 'Name', 'trim|required');
            $this->form_validation->set_rules('cus_lastname', 'Lastname', 'required');
            $this->form_validation->set_rules('cus_tel', 'Tel', 'required|regex_match[/^[0-9]{10}$/]');

            $message = array();

            $id = $this->get('id');
            $name = $this->post('cus_name');
            $lastname = $this->post('cus_lastname');
            $tel = $this->post('cus_tel');
            $latitude = $this->post('latitude');
            $longitude = $this->post('longitude');
            $address = $this->post('address');

            /*** Set Properties ***/
            $this->user->getUserID($id);
            $this->user->setName($name);
            $this->user->setLastname($lastname);
            $this->user->setTel($tel);
            $this->user->setLatitude($latitude);
            $this->user->setLongitude($longitude);
            $this->user->setAddress($address);

            $info = $this->user->getInfo();

            if ($info !== false && !is_blank($info)) {

                $result = array();

                $result = $this->user->updateInfo();

                $info = $this->user->getInfo();

                if ($result) {

                    $http_code = REST_Controller::HTTP_OK;
                    $message = array(
                        'status' => TRUE,
                        'message' => "Update information successful.",
                        'data' => $info
                    );

                } else {
                    $http_code = REST_Controller::HTTP_BAD_REQUEST;
                    $message = array(
                        'status' => FALSE,
                        'message' => "Update information fail."
                    );
                }

            } else {

                $http_code = REST_Controller::HTTP_BAD_REQUEST;
                $message = array(
                    'status' => FALSE,
                    'message' => "Update information fail.",
                );
            }

            $this->response($message, $http_code);

        } else {

            $this->response(array('status' => FALSE, 'message' => $this->_authen_message), REST_Controller::HTTP_NOT_FOUND);
        }

    }

}
