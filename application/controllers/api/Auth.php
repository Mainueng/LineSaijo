<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';

class Auth extends REST_Controller
{
    function __construct()
    {
        parent::__construct();


        $this->methods['register_post']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['login_post']['limit'] = 100; // 100 requests per hour per user/key
        $this->load->model('Auth_model', 'auth');
        $this->load->model('User_model','user');

        $this->load->library('facebook');

    }

    public function login_post()
    {
        $data = $this->security->xss_clean($_POST);

        $this->form_validation->set_rules('cus_email', 'Email', 'trim|required|min_length[5]|max_length[80]', array('required' => 'You must provide a %s.', 'is_unique' => 'This %s already exists.'));
        $this->form_validation->set_rules('cus_password', 'Password', 'trim|required|min_length[5]|max_length[12]');

        $message = array();
        if ($this->form_validation->run() == TRUE) {
            # code...
            $data_user = array(
                'email' => $this->post('cus_email'),
                'password' => $this->post('cus_password')
            );

            $email_user = $this->post('cus_email');
            $pass_user = $this->post('cus_password');

            $this->auth->setUserName($email_user);
            $this->auth->setPassword($pass_user);

            $result = array();
            $authArray = array();
            $token_data = array();
            $result = $this->auth->login();

            /*** App Info **/
            $app_name = "EAPI";
            $app_version = "";
            if (!is_blank($this->post('app_name'))) {
                $app_name = $this->post('app_name');
            }

            $login_status = false;
            $http_code = REST_Controller::HTTP_BAD_REQUEST;
            $msg = "";
            $user_token = "";

            $data = array();

            if (!empty($result) && $result !== false) {
                foreach ($result as $row) {
                    if ($row->status == 1) {
                        $user_status = true;
                        $login_status = true;
                    } else {
                        $user_status = false;
                    }

                    $authArray = array(
                        'id' => $row->user_id,
                        'user_id' => $row->user_id,
                        'user_image' => null,
                        'name' => $row->name,
                        'lastname' => $row->lastname,
                        'email' => $row->email,
                        'telephone' => $row->telephone,
                        'latitude' => $row->latitude,
                        'longitude' => $row->longitude,
                        'rating'   => 5,
                        'user_role_id' => $row->user_role_id,
                        'cus_group_id' => $row->cus_group_id,
                        'status' => $row->status,
                        'time' => time()
                    );

                    $token_data['user_id'] = $row->user_id;
                    $token_data['email'] = $row->email;
                    $token_data['name'] = $row->name;
                    $token_data['lastname'] = $row->lastname;


                }
                #Load Libraries JWT
                $this->load->library('Authorization_Token');
                $user_token = $this->authorization_token->generateToken($authArray);

                if ($this->authorization_token->validateToken()) {


                    $this->auth->setUserID($token_data['user_id']);
                    $this->auth->setToken($user_token);
                    $device_token = $this->auth->updateToken();

                    $http_code = REST_Controller::HTTP_OK;

                    $msg = "Success";
                    $data = array(
                        'auth_token' => $user_token
                    );
                } else {
                    $http_code = REST_Controller::HTTP_BAD_REQUEST;

                    $msg = "Login Fail";
                }

            } else {
                $login_status = false;
                $msg = "Login Fail";
                $http_code = REST_Controller::HTTP_BAD_REQUEST;
            }

            $message = array(
                'status' => $login_status,
                'code' => $http_code,
                'message' => $msg,
                'data' => array($data)
            );


        } else {
            # code...
            $http_code = REST_Controller::HTTP_BAD_REQUEST;
            $message = array(
                'status' => false,
                'code' => $http_code,
                'message' => trim(strip_tags(validation_errors(),'\n')),
                'data' => array(
                    /*'error' => $this->form_validation->error_array()*/
                )
            );
        }

        $this->response($message, $http_code);

    }

    public function register_post()
    {
        $data = $this->security->xss_clean($_POST);
        $this->form_validation->set_rules('cus_email', 'Email', 'trim|required|is_unique[cus_mstr.email]|min_length[5]|max_length[80]', array('required' => 'You must provide a %s.', 'is_unique' => 'This %s already exists.'));
        $this->form_validation->set_rules('cus_password', 'Password', 'trim|required|min_length[5]|max_length[12]');

        $message = array();
        if ($this->form_validation->run() == TRUE) {
            $this->load->model($this->auth_model, 'auth');
            $email = $this->post('cus_email');
            $password = $this->post('cus_password');
            $status = 1;
            $setRole = 4;
/*            $app_name = $this->post('app_name');
            if (isset($app_name) && $app_name == "CLUB") {
                $setRole = 3;
            }*/
            $groupId = 1;
            $timeStamp = date("Y-m-d H:i:s");

            /*** Set Properties ***/
            $this->auth->setEmail($email);
            $this->auth->setPassword($password);
            $this->auth->setStatus($status);
            $this->auth->setRole($setRole);
            $this->auth->setGroup($groupId);
            $this->auth->setTimeStamp($timeStamp);
            $this->auth->setRole($setRole);
            $chk = $this->auth->create();

            if ($chk) {
                $result = array();

                $result = $this->auth->getUserDetails();

                $authArray = array();

                if ($result !== false && !is_blank($result)) {
                    foreach ($result as $row) {
                        if ($row->status == 1) {
                            $user_status = true;
                            $login_status = true;
                        } else {
                            $user_status = false;
                        }

                        $authArray = array(
                            'id' => $row->user_id,
                            'user_id' => $row->user_id,
                            'user_image' => null,
                            'name' => $row->name,
                            'lastname' => $row->lastname,
                            'email' => $row->email,
                            'telephone' => $row->telephone,
                            'latitude' => $row->latitude,
                            'longitude' => $row->longitude,
                            'rating'   => 5,
                            'user_role_id' => $row->user_role_id,
                            'cus_group_id' => $row->cus_group_id,
                            'status' => $row->status,
                            'time' => time()
                        );

                        $token_data['user_id'] = $row->user_id;
                        $token_data['email'] = $row->email;
                        $token_data['name'] = $row->name;
                        $token_data['lastname'] = $row->lastname;


                    }
                }

                $this->load->library('Authorization_Token');
                $user_token = $this->authorization_token->generateToken($authArray);

                if ($this->authorization_token->validateToken()) {

                    $this->auth->setToken($user_token);

                    $device_token = $this->auth->updateToken();

                    $http_code = REST_Controller::HTTP_OK;
                    $message = array(
                        'status' => true,
                        'code' => $http_code,
                        'message' => "User successfully registered.",
                        'data' => array(array(
                            /*'user_id' => $this->auth->getUserId(),*/
                            'auth_token' => $user_token
                        ))
                    );

                } else {
                    $http_code = REST_Controller::HTTP_BAD_REQUEST;
                    $message = array(
                        'status' => true,
                        'code' => $http_code,
                        'message' => "Register Fail",
                        'data' => array()
                    );
                }

            } else {
                $http_code = REST_Controller::HTTP_BAD_REQUEST;
                $message = array(
                    'status' => false,
                    'code' => $http_code,
                    'message' => "INVALID ACCOUNT REPEAT EMAIL AND FACEBOOK ID",
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
        $this->response($message, $http_code);

    }

    public function facebook_login_post(){

        if ($this->facebook->is_authenticated()){
            // Get user facebook profile details
            $fbUser = $this->facebook->request('get', '/me?fields=id,first_name,last_name,email');

            // Preparing data for database insertion
            $userData['fb_id']    = !empty($fbUser['id'])?$fbUser['id']:'';
            //$userData['device_token'] = $this->facebook->is_authenticated();
            $userData['name']    = !empty($fbUser['first_name'])?$fbUser['first_name']:'';
            $userData['fb_name']    = !empty($fbUser['first_name'])?$fbUser['first_name']:'';
            $userData['lastname']    = !empty($fbUser['last_name'])?$fbUser['last_name']:'';
            $userData['fb_lname']    = !empty($fbUser['last_name'])?$fbUser['last_name']:'';
            $userData['email']    = !empty($fbUser['email'])?$fbUser['email']:'';
            $userData['fb_email']    = !empty($fbUser['email'])?$fbUser['email']:'';
            $userData['cus_group_id']    = 1;
            $userData['status']    = 1;
            $userData['user_role_id'] = 3;

            $userData['telephone'] = null;
            $userData['latitude'] = 0;
            $userData['longitude'] = 0;

            // Insert or update user data
            $userID = $this->user->checkUser($userData);

            // Check user data insert or update status
            if(!empty($userID)){
                $data['userData'] = $userData;
                $this->session->set_userdata('userData', $userData);
            } else {
                $data['userData'] = array();
            }

            $authArray = array(
                'id' => $userID,
                'user_id' => $userID,
                'user_image' => null,
                'name' => $userData['name'],
                'lastname' => $userData['lastname'],
                'email' => $userData['email'],
                'telephone' => $userData['telephone'],
                'latitude' => $userData['latitude'],
                'longitude' => $userData['longitude'],
                'rating'   => 5,
                'user_role_id' => $userData['user_role_id'],
                'cus_group_id' =>  $userData['cus_group_id'],
                'status' =>  $userData['status'],
                'time' => time()
            );

            $this->load->library('Authorization_Token');
            $user_token = $this->authorization_token->generateToken($authArray);

            if ($this->authorization_token->validateToken()) {

                $this->auth->setToken($user_token);

                $device_token = $this->auth->updateToken();

                $http_code = REST_Controller::HTTP_OK;
                $message = array(
                    'status' => true,
                    'code' => $http_code,
                    'message' => "Facebook login successfully.",
                    'data' => array(array(
                        'auth_token' => $user_token
                    ))
                );

            } else {
                $http_code = REST_Controller::HTTP_BAD_REQUEST;
                $message = array(
                    'status' => false,
                    'code' => $http_code,
                    'message' => "Facebook login fail.",
                    'data' => array()
                );
            }


        } else {
            $token = $this->facebook->is_authenticated();
            $http_code = REST_Controller::HTTP_BAD_REQUEST;
            $message = array(
                'status' => false,
                'code' => $http_code,
                'message' => 'No authenticate.',
                'data' => array()
            );
        }

        $this->response($message, $http_code);
    }


}// End of class
