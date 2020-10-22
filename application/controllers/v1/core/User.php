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

        $this->load->model('Auth_model', 'auth');
        $this->load->model('User_model', 'user');
        $this->load->model('Jobs_model', 'jobs');
        $this->load->model('Technician_model', 'technician');

        $this->load->library('email');

        // Configure limits on our controller methods
        // Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
        $this->methods['users_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['users_post']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['rating_put']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['cus_tech_put']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['cus_tech_get']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['technician_online_get']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['technician_info_get']['limit'] = 100; // 100 requests per hour per user/key
    }

    public function forgot_password_post()
    {

        $data = $this->security->xss_clean($_POST);
        $this->form_validation->set_rules('email', 'Email', 'trim|required');

        if ($this->form_validation->run() == TRUE) {

            $to = $this->post('email');

            $this->auth->setEmail($to);

            $config = array();
            $config['useragent'] = 'saijo-denki.co.th';
            $config['protocol'] = 'smtp';
            $config['smtp_host'] = 'mail.saijo-denki.co.th';
            $config['smtp_user'] = 'saijoapp@saijo-denki.co.th';
            $config['smtp_pass'] = 'WjiHk2Jz';
            $config['smtp_port'] = '465';
            $config['smtp_crypto'] = 'ssl';
            $config['charset'] = 'utf-8';
            $this->email->initialize($config);

            $password = $this->random_password();

            $this->email->from('saijoapp@saijo-denki.co.th', 'Saijo Denki');
            $this->email->to($to);
            $this->email->subject('Password reset request');
            $this->email->message('A new password was requested for Saijo Denki customer account. New password : '.$password);

            $chk = $this->auth->checkEmail_core();

            if ($chk !== false && !is_blank($chk)) {

                $this->auth->setPassword($password);

                $data = $this->auth->forgotPassword_core();

                if ($data !== false && !is_blank($data)) {

                    $this->email->send();

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
                        'message' => "Password change unsuccessful.",
                        'data' => array()
                    );
                }

            } else {

                $http_code = REST_Controller::HTTP_BAD_REQUEST;
                $message = array(
                    'status' => false,
                    'code' => $http_code,
                    'message' => "Email not found.",
                    'data' => array()
                );
            }

        } else {
            $http_code = REST_Controller::HTTP_REQUEST_TIMEOUT;
            $message = array(
                'status' => false,
                'code' => $http_code,
                'message' => trim(strip_tags(validation_errors(),'\n')),
                'data' => array()
            );
        }

        $this->response($message, $http_code);
    }

    public function users_get()
    {
        if ($this->is_authen()) {

            $message = array();

            $token_info = $this->authorization_token->userData();

            if ($token_info->user_role_id == 4 || $token_info->user_role_id == 1 || $token_info->user_role_id == 2) {

                $id = $token_info->id;
                $this->user->getUserID($id);

                $check_info = $this->user->check_info_cus();

                $info = $this->user->getInfo();

                if ($info !== false && !is_blank($info)) {

                    $http_code = REST_Controller::HTTP_OK;
                    $message = array(
                        'status' => true,
                        'code' => $http_code,
                        'message' => "Success",
                        'data' => $info
                    );
                } else {

                    $http_code = REST_Controller::HTTP_BAD_REQUEST;
                    $message = array(
                        'status' => false,
                        'code' => $http_code,
                        'message' => "No users were found.",
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

            $this->response($message, $http_code);

        } else {

            $this->response(array('status' => FALSE, 'message' => $this->_authen_message), REST_Controller::HTTP_REQUEST_TIMEOUT);
        }
    }

    public function users_post()
    {

        if ($this->is_authen()) {

            $token_info = $this->authorization_token->userData();

            if ($token_info->user_role_id == 4 || $token_info->user_role_id == 1 || $token_info->user_role_id == 2) {

                $data = $this->security->xss_clean($_POST);
                $this->form_validation->set_rules('name', 'Name', 'trim');
                $this->form_validation->set_rules('lastname', 'Lastname', 'trim');
                $this->form_validation->set_rules('tel', 'Tel', 'trim|min_length[9]|max_length[10]');
                $this->form_validation->set_rules('latitude', 'Latitude', 'trim');
                $this->form_validation->set_rules('longitude', 'Longitude', 'trim');
                $this->form_validation->set_rules('address', 'Address', 'trim');
                $this->form_validation->set_rules('district', 'District', 'trim');
                $this->form_validation->set_rules('province', 'Province', 'trim');
                $this->form_validation->set_rules('postal_code', 'Postal Code', 'trim');
                $this->form_validation->set_rules('profile_img', 'Profile Image');

                if ($this->form_validation->run() == TRUE) {

                    $message = array();

                    $id = $token_info->id;
                    $name = $this->post('name');
                    $lastname = $this->post('lastname');
                    $tel = $this->post('tel');
                    $latitude = $this->post('latitude');
                    $longitude = $this->post('longitude');
                    $address = $this->post('address');
                    $district = $this->post('district');
                    $province = $this->post('province');
                    $postal_code = $this->post('postal_code');
                    $profile = "";

                    if (isset($_FILES['profile_img']['name'])) {
                        $profile = $this->upload_img($id,'profile_img');
                    }

                    /*** Set Properties ***/
                    if (isset($name)) {
                        $this->user->setName($name);
                    }
                    if (isset($lastname)) {
                        $this->user->setLastname($lastname);
                    }
                    if (isset($tel)) {
                        $this->user->setTel($tel);
                    }
                    if (isset($latitude)) {
                        $this->user->setLatitude($latitude);
                    }
                    if (isset($longitude)) {
                        $this->user->setLongitude($longitude);
                    }
                    if (isset($address)) {
                        $this->user->setAddress($address);
                    }
                    if (isset($district)) {
                        $this->user->setDistrict($district);
                    }
                    if (isset($province)) {
                        $this->user->setProvice($province);
                    }
                    if (isset($postal_code)) {
                        $this->user->setPostalCode($postal_code);
                    }
                    if (isset($profile)) {
                        $this->user->setProfile($profile);
                    }            

                    $this->user->getUserID($id);

                    $check_info = $this->user->check_info_cus();

                    $info = $this->user->getInfo();

                    if ($info !== false && !is_blank($info)) {

                        $result = array();

                        $result = $this->user->updateInfo();

                        $info = $this->user->getInfo();

                        if ($result) {

                            $http_code = REST_Controller::HTTP_OK;
                            $message = array(
                                'status' => TRUE,
                                'code' => $http_code,
                                'message' => "Update information successful.",
                                'data' => array()
                            );

                        } else {
                            $http_code = REST_Controller::HTTP_BAD_REQUEST;
                            $message = array(
                                'status' => FALSE,
                                'code' => $http_code,
                                'message' => "Update information fail.",
                                'data' =>  array()
                            );
                        }

                    } else {

                        $http_code = REST_Controller::HTTP_BAD_REQUEST;
                        $message = array(
                            'status' => FALSE,
                            'code' => $http_code,
                            'message' => "Update information fail.",
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

            $this->response($message, $http_code);

        } else {

            $this->response(array('status' => FALSE, 'message' => $this->_authen_message), REST_Controller::HTTP_REQUEST_TIMEOUT);
        }
    }

    public function rating_put()
    {
        if ($this->is_authen()) {

            $token_info = $this->authorization_token->userData();

            if ($token_info->user_role_id == 4 || $token_info->user_role_id == 1 || $token_info->user_role_id == 2) {

                $cus_id = $token_info->id;

                if (isset($cus_id)) {
                    $this->jobs->setCustomerId($cus_id);
                }

                $info = $this->jobs->get_last_job();

                if ($info['tech_id']) {
                    $this->user->setTechID($info['tech_id']);
                }

                $rating = $this->put('rating');

                if (isset($rating)) {
                    $this->user->setRating($rating);
                }

                $data = $this->user->update_rating();

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
                        'message' => 'Unsuccessful rating.',
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

            $this->response($message, $http_code);

        } else {

            $this->response(array('status' => FALSE, 'message' => $this->_authen_message), REST_Controller::HTTP_REQUEST_TIMEOUT);
        }
    }

    public function cus_tech_get()
    {
        if ($this->is_authen()) {

            $token_info = $this->authorization_token->userData();

            if ($token_info->user_role_id == 4 || $token_info->user_role_id == 1 || $token_info->user_role_id == 2) {

                $cus_id = $token_info->id;

                if (isset($cus_id)) {
                    $this->jobs->setCustomerId($cus_id);
                    $this->user->getUserID($cus_id);
                }

                $info = $this->jobs->get_last_job();

                if ($info['tech_id']) {
                    $this->user->setTechID($info['tech_id']);
                }

                $data = $this->user->get_cus_tech();

                if ($data !== false && !is_blank($data)) {

                    $http_code = REST_Controller::HTTP_OK;
                    $message = array(
                        'status' => true,
                        'code' => $http_code,
                        'message' => "Success",
                        'data' => array(array(
                            'tech_id' => $data
                        ))
                    );

                } else {
                    $http_code = REST_Controller::HTTP_ACCEPTED;
                    $message = array(
                        'status' => false,
                        'code' => $http_code,
                        'message' => 'Personal technician not found.',
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

            $this->response($message, $http_code);

        } else {

            $this->response(array('status' => FALSE, 'message' => $this->_authen_message), REST_Controller::HTTP_REQUEST_TIMEOUT);
        }
    }

    public function cus_tech_put()
    {
        if ($this->is_authen()) {

            $token_info = $this->authorization_token->userData();

            if ($token_info->user_role_id == 4 || $token_info->user_role_id == 1 || $token_info->user_role_id == 2) {

                $cus_id = $token_info->id;

                if (isset($cus_id)) {
                    $this->jobs->setCustomerId($cus_id);
                    $this->user->getUserID($cus_id);
                }

                //$info = $this->jobs->get_last_job();

                $tech_id = $this->put('tech_id');

                if ($tech_id) {
                    $this->user->setTechID($tech_id);
                }

                $data = $this->user->update_cus_tech();

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
                        'message' => 'Update personal technician fail.',
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

            $this->response($message, $http_code);

        } else {

            $this->response(array('status' => FALSE, 'message' => $this->_authen_message), REST_Controller::HTTP_REQUEST_TIMEOUT);
        }
    }

    public function technician_online_get()
    {
        if ($this->is_authen()) {

            $token_info = $this->authorization_token->userData();

            if ($token_info->user_role_id == 4 || $token_info->user_role_id == 1 || $token_info->user_role_id == 2) {

                $online = $this->technician->technician_online();
                $total = $this->technician->technician_total();

                if ($online !== false && !is_blank($online) && $total !== false && !is_blank($total)) {

                    $http_code = REST_Controller::HTTP_OK;
                    $message = array(
                        'status' => true,
                        'code' => $http_code,
                        'message' => "Success",
                        'data' => array(array(
                            'online' => strval($online),
                            'total' => $total
                        ))
                    );

                } else {
                    $http_code = REST_Controller::HTTP_BAD_REQUEST;
                    $message = array(
                        'status' => false,
                        'code' => $http_code,
                        'message' => 'Information not found.',
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

    public function technician_info_get()
    {
        /*** Technician Information ***/

        if ($this->is_authen()) {

            $token_info = $this->authorization_token->userData();

            $id = $this->get('id');

            if ($token_info->user_role_id == 4 || $token_info->user_role_id == 1 || $token_info->user_role_id == 2) {

                if (isset($id) && !is_blank($id)) {
                    $this->technician->setTechnicianId($id);
                }

                $data = $this->technician->technicianInfo();

                if ($data !== false && !is_blank($data)) {

                    $http_code = REST_Controller::HTTP_OK;
                    $message = array(
                        'status' => true,
                        'code' => $http_code,
                        'message' => 'Success',
                        'data' => $data
                    );
                } else {

                    $http_code = REST_Controller::HTTP_OK;
                    $message = array(
                        'status' => false,
                        'code' => $http_code,
                        'message' => "Technician infotmation not found.",
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

    public function certification_info_get()
    {
        if ($this->is_authen()) {

            $token_info = $this->authorization_token->userData();

            $id = $this->get('id');

            if ($token_info->user_role_id == 4 || $token_info->user_role_id == 1 || $token_info->user_role_id == 2) {

                if (isset($id) && !is_blank($id)) {
                    $this->user->getUserID($id);
                }

                $data = $this->user->saijo_certification();

                if ($data !== false && !is_blank($data)) {

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
                        'message' => "Infotmation not found.",
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

    public function check_account_validate_get()
    {
        if ($this->is_authen()) {

            $token_info = $this->authorization_token->userData();

            $id = $token_info->id;

            if ($token_info->user_role_id == 4 || $token_info->user_role_id == 1 || $token_info->user_role_id == 2) {

                if (isset($id) && !is_blank($id)) {
                    $this->user->getUserID($id);
                }

                $data =  $this->user->check_core_account_validate();

                if ($data !== false && !is_blank($data)) {

                    $data['validate'] = true;

                    $http_code = REST_Controller::HTTP_OK;
                    $message = array(
                        'status' => true,
                        'code' => $http_code,
                        'message' => 'Success',
                        'data' => array($data)
                    );
                } else {

                    $http_code = REST_Controller::HTTP_BAD_REQUEST;
                    $message = array(
                        'status' => false,
                        'code' => $http_code,
                        'message' => "Infotmation not found.",
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

    public function upload_img($id,$img) {
        $images = $_FILES[$img]['tmp_name'];
        $check_type = $_FILES[$img]['name'];
        $check_type_explode = explode('.', $check_type);

        $size = GetimageSize($images);

        if ($size[0] > 720) {
            $width = 720;
        } else {
            $width = $size[0];
        }

        $height = round($width*$size[1]/$size[0]);

        if ($check_type_explode[1] == 'PNG' || $check_type_explode[1] == 'png') {
            $images_orig = ImageCreateFromPNG($images);
        }
        else if ($check_type_explode[1] == 'GIF' || $check_type_explode[1] == 'gif') {
            $images_orig = ImageCreateFromGIF($images);
        }
        else{
            $images_orig = ImageCreateFromJPEG($images);
        }

        $photoX = ImagesX($images_orig);
        $photoY = ImagesY($images_orig);
        $images_fin = ImageCreateTrueColor($width, $height);
        ImageCopyResampled($images_fin, $images_orig, 0, 0, 0, 0, $width+1, $height+1, $photoX, $photoY);

        imagepng($images_fin, "application/controllers/v1/core/upload/profile_img/".$id.".png");

        ImageDestroy($images_orig);
        ImageDestroy($images_fin);

        $name = $id.".png";

        return $name;
    }

    public function random_password()
    {
        $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
        $ret_str = "";
        $num = strlen($chars);
        for($i = 0; $i < 9; $i++)
        {
            $ret_str.= $chars[rand()%$num];
            $ret_str.=""; 
        }
        return $ret_str; 
    }

}
