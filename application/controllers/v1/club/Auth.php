<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';


class Auth extends REST_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->methods['login_post']['limit'] = 100;
        $this->methods['register_post']['limit'] = 500;
        $this->methods['login_facebook_get']['limit'] = 100;
        $this->methods['login_facebook_post']['limit'] = 100;

        $_SESSION['app'] = 'club';

        $this->load->library('facebook');

        $this->load->model('Auth_model', 'auth');
        $this->load->model('User_model','user');
        $this->load->model($this->technician_model, 'technician');
    }

    public function login_post()
    {
        $data = $this->security->xss_clean($_POST);

        $this->form_validation->set_rules('email', 'Email', 'trim|required|min_length[5]|max_length[80]');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[5]|max_length[20]');
        $this->form_validation->set_rules('device_id', 'Device ID', 'trim|required');

        $message = array();
        if ($this->form_validation->run() == TRUE) {
            # code...
            $data_user = array(
                'email' => $this->post('email'),
                'password' => $this->post('password')
            );

            $email_user = $this->post('email');
            $pass_user = $this->post('password');
            $device_id = $this->post('device_id');

            $this->auth->setUserName($email_user);
            $this->auth->setPassword($pass_user);
            $this->auth->setDeviceId($device_id);

            $result = array();
            $authArray = array();
            $token_data = array();
            $result = $this->auth->login_club();

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
                        'time' => time() + (30 * 24 * 60 * 60),
                        'device_id' => $row->device_id,
                        'login_facebook' => false
                    );

                    $token_data['user_id'] = $row->user_id;
                    $token_data['email'] = $row->email;
                    $token_data['name'] = $row->name;
                    $token_data['lastname'] = $row->lastname;

                    $this->auth->setUserID($token_data['user_id']);

                    $this->auth->delete_user_log();

                }

                $this->user->getUserID($token_data['user_id']);
                $this->user->check_info_tech();

                #Load Libraries JWT
                $this->load->library('Authorization_Token');
                $user_token = $this->authorization_token->generateToken($authArray);

                if ($this->authorization_token->validateToken()) {

                    if ($this->auth->update_fcm_token()) {

                        $this->auth->setUserID($token_data['user_id']);
                        //$this->auth->setToken($device_id);
                        $device_token = $this->auth->updateToken();

                        $http_code = REST_Controller::HTTP_OK;

                        $msg = "Success";
                        $data = array(array(
                            'auth_token' => $user_token
                        ));

                    } else {
                        $login_status = false;
                        $msg = "Can't update FCM Token.";
                        $http_code = REST_Controller::HTTP_BAD_REQUEST;
                    }

                } else {
                    $http_code = REST_Controller::HTTP_BAD_REQUEST;

                    $msg = "รหัสผ่านไม่ถูกต้อง";
                }

            } else {
                $login_status = false;
                $msg = "รหัสผ่านไม่ถูกต้อง";
                $http_code = REST_Controller::HTTP_BAD_REQUEST;
            }

            $message = array(
                'status' => $login_status,
                'code' => $http_code,
                'message' => $msg,
                'data' => $data
            );


        } else {
            # code...
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

    public function register_post()
    {

        $data = $this->security->xss_clean($_POST);
        $this->form_validation->set_rules('email', 'Email', 'trim|required|is_unique[cus_mstr.email]|min_length[5]|max_length[80]', array('required' => 'You must provide a %s.', 'is_unique' => 'This %s already exists.'));
        $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[5]|max_length[12]');
        $this->form_validation->set_rules('name', 'Name', 'trim|required|max_length[80]');
        $this->form_validation->set_rules('lastname', 'Lastname', 'trim|required|max_length[80]');
        $this->form_validation->set_rules('device_id', 'Device ID', 'trim|required');
        $this->form_validation->set_rules('telephone', 'Telephone', 'trim|required|min_length[9]|max_length[10]');

        $message = array();

        if ($this->form_validation->run() == TRUE) {
            $this->load->model($this->auth_model, 'auth');
            $email = $this->post('email');
            $password = $this->post('password');
            $name = $this->post('name');
            $lastname = $this->post('lastname');
            $device_id = $this->post('device_id');
            $status = 1;
            $setRole = 3;
            $groupId = 0;
            $timeStamp = date("Y-m-d H:i:s");
            $telephone = $this->post('telephone');

            /*** Set Properties ***/
            $this->auth->setEmail($email);
            $this->auth->setPassword($password);
            $this->auth->setFirstname($name);
            $this->auth->setLastname($lastname);
            $this->auth->setStatus($status);
            $this->auth->setRole($setRole);
            $this->auth->setGroup($groupId);
            $this->auth->setTimeStamp($timeStamp);
            $this->auth->setRole($setRole);
            $this->auth->setDeviceId($device_id);
            $this->auth->setTel($telephone);

            $validate = $this->auth->validate();

            if (!$validate) {

                $chk = $this->auth->create();

                $this->auth->setUserID($chk);

                if ($chk && $this->auth->update_fcm_token()) {
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
                                'time' => time() + (30 * 24 * 60 * 60),
                                'device_id' => $row->device_id,
                                'login_facebook' => false
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

                        $this->auth->setDeviceId($device_id);

                        $device_token = $this->auth->updateToken();

                        $http_code = REST_Controller::HTTP_OK;
                        $message = array(
                            'status' => true,
                            'code' => $http_code,
                            'message' => "User successfully registered.",
                            'data' => array(array(
                                'auth_token' => $user_token,
                            ))
                        );

                    } else {
                        $http_code = REST_Controller::HTTP_BAD_REQUEST;
                        $message = array(
                            'status' => false,
                            'code' => $http_code,
                            'message' => "การลงทะเบียนล้มเหลว",
                            'data' => array()
                        );
                    }

                } else {
                    $http_code = REST_Controller::HTTP_BAD_REQUEST;
                    $message = array(
                        'status' => false,
                        'code' => $http_code,
                        'message' => "INVALID ACCOUNT REPEAT EMAIL AND FACEBOOK ID.",
                        'data' => array()
                    );
                }

            } else {
                $http_code = REST_Controller::HTTP_BAD_REQUEST;
                $message = array(
                    'status' => false,
                    'code' => $http_code,
                    'message' => "การลงทะเบียนล้มเหลว, อีเมลนี้ถูกใช้งานแล้ว.",
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

    public function login_facebook_get()
    {
        header( "location: https://api.saijo-denki.com/user_authentication" );
    }

    public function login_facebook_post()
    {

        $fb_token = $this->post('fb_token');

        if (!is_blank($fb_token)) {
            $_SESSION['fb_access_token'] = $this->post('fb_token');
        } else {
            $_SESSION['fb_access_token'] = '';
        }

     if ($this->facebook->is_authenticated()){
            // Get user facebook profile details
        $fbUser = $this->facebook->request('get', '/me?fields=id,first_name,last_name,email,picture.width(400).height(400)');

            // Preparing data for database insertion
        $userData['fb_id']    = !empty($fbUser['id'])?$fbUser['id']:'';
        $userData['name']    = !empty($fbUser['first_name'])?$fbUser['first_name']:'';
        $userData['fb_name']    = !empty($fbUser['first_name'])?$fbUser['first_name']:'';
        $userData['lastname']    = !empty($fbUser['last_name'])?$fbUser['last_name']:'';
        $userData['fb_lname']    = !empty($fbUser['last_name'])?$fbUser['last_name']:'';
        $userData['email']    = !empty($fbUser['email'])?$fbUser['email']:'';
        $userData['fb_email']    = !empty($fbUser['email'])?$fbUser['email']:'';
        $userData['cus_group_id']    = 0;
        $userData['status']    = 1;
        $userData['user_role_id'] = 3;
        $userData['fb_token'] = $fb_token;

            /*$userData['telephone'] = null;
            $userData['latitude'] = 0;
            $userData['longitude'] = 0;*/

            $device_id = $this->post('device_id');
            //$userData['device_id'] = $device_id;

            $this->auth->setDeviceId($device_id);
            $this->user->setDeviceId($device_id);

            if (!is_blank($userData['email'])) {

            // Insert or update user data
                $userID = $this->user->checkUser_club($userData);

                $this->auth->setUserID($userID);
                $this->user->getUserID($userID);

                $this->auth->update_fcm_token();

            // Check user data insert or update status
                if(!empty($userID) || $userID != FALSE){
                    $data['userData'] = $userData;
                    $this->session->set_userdata('userData', $userData);

                    $authArray = array(
                        'id' => $userID,
                        'user_id' => $userID,
                        'user_image' => null,
                        'name' => $userData['name'],
                        'lastname' => $userData['lastname'],
                        'email' => $userData['email'],
                        'telephone' => null,
                        'latitude' => 0,
                        'longitude' => 0,
                        'rating'   => 5,
                        'user_role_id' => $userData['user_role_id'],
                        'cus_group_id' =>  $userData['cus_group_id'],
                        'status' =>  $userData['status'],
                        'time' => time() + (30 * 24 * 60 * 60),
                        'device_id' => $device_id,
                        'login_facebook' => true
                    );

                    $this->load->library('Authorization_Token');
                    $user_token = $this->authorization_token->generateToken($authArray);

                    $this->technician->setTechnicianId($userID);

                    if (isset($userData['name'])) {
                        $this->technician->setFirstname($userData['name']);
                    }
                    if (isset($userData['lastname'])) {
                        $this->technician->setLastname($userData['lastname']);
                    }

                    $this->technician->setProfile($userID);

                    if (isset($fbUser['picture'])) {
                        foreach ($fbUser['picture'] as $row) {
                            $url = $row['url'];
                        }

                        file_put_contents("application/controllers/v1/club/upload/profile_img/".$userID.".png", fopen($url, 'r'));

                        $this->technician->setProfile($userID);
                    }

                    $this->user->getUserID($userID);
                    $this->user->check_info_tech();

                    $info = $this->technician->updateTechnicianInfo();

                    if ($this->authorization_token->validateToken() && $info) {

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
                            'message' => "การเข้าสู่ระบบด้วย Facebook ล้มเหลว.",
                            'data' => array()
                        );
                    }

                } else {
                    $http_code = REST_Controller::HTTP_BAD_REQUEST;
                    $message = array(
                        'status' => false,
                        'code' => $http_code,
                        'message' => "บัญชี Facebook นี้ได้รับการลงทะเบียนในแอปพลิเคชันอื่นแล้ว.",
                        'data' => array()
                    );
                }
            } else {
                $http_code = REST_Controller::HTTP_BAD_REQUEST;
                $message = array(
                    'status' => false,
                    'code' => $http_code,
                    'message' => "การเข้าสู่ระบบด้วย Facebook ล้มเหลว",
                    'data' => array()
                );
            }

        } else {
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

    public function logout_post()
    {
        if ($this->is_authen()) {

            $token_info = $this->authorization_token->userData();

            $cus_id = $token_info->id;

            $device_id = $this->post('device_id');

            if (isset($cus_id) && !is_blank($cus_id)) {
                $this->auth->setUserID($cus_id);
            }

            if (isset($device_id) && !is_blank($device_id)) {
                $this->auth->setDeviceId($device_id);
            } else {
                $this->auth->setDeviceId(0);
            }

            $logout = $this->auth->logout();

            if ($logout !== false && !is_blank($logout)) {

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
                    'message' => "การออกจากระบบล้มเหลว",
                    'data' => array()
                );
            }

        } else {
            # code...
            $http_code = REST_Controller::HTTP_BAD_REQUEST;
            $message = array(
                'status' => false,
                'code' => $http_code,
                'message' => $this->_authen_message,
                'data' => array()
            );

        }

        $this->response($message, $http_code);
    }

    public function check_version_post()
    {
        $this->form_validation->set_rules('app', 'App', 'trim|required');
        $this->form_validation->set_rules('device', 'Device', 'trim|required');
        $this->form_validation->set_rules('version', 'Version', 'trim|required');

        if ($this->form_validation->run() == TRUE) {

            $app = $this->post('app');
            $device = $this->post('device');
            $version = $this->post('version');

            $this->auth->setApp($app);
            $this->auth->setDevice($device);
            $this->auth->setVersion($version);

            $data = $this->auth->check_version();

            if ($data !== false && !is_blank($data)) {

                if ($data == $version) {
                    $http_code = REST_Controller::HTTP_OK;
                    $message = array(
                        'status' => true,
                        'code' => $http_code,
                        'message' => 'Success',
                        'data' => array()
                    );
                } else {
                    $http_code = REST_Controller::HTTP_ACCEPTED;
                    $message = array(
                        'status' => false,
                        'code' => $http_code,
                        'title' => 'เวอร์ชั่นใหม่พร้อมใช้งาน',
                        'message' => 'คุณสามารถอัพเดตแอพนี้จากเวอร์ชั่น '.$version.' เป็นเวอร์ชั่น '.$data,
                        'data' => array()
                    );
                }

            } else {
                $http_code = REST_Controller::HTTP_BAD_REQUEST;
                $message = array(
                    'status' => false,
                    'code' => $http_code,
                    'message' => 'ตรวจสอบเวอร์ชั่นล้มเหลว',
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

} // End of class
