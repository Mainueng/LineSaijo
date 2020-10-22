<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{
    public $_cus_token;
    public $_authen_token;

    /***** Model ****/
    public $auth_model;
    public $product_model;
    public $warranty_model;
    public $technician_model;
    public $service_model;
    public $jobs_model;
    public $job_history_model;
    public $notification_model;
    public $error_code_model;
    public $summary_model;
    public $device_model;
    public $manual_model;
    public $widget_model;


    public $_user_id;
    public $_user_email;
    public $_user_group_id;
    public $_user_role_id;
    public $_user_status;

    public $_authen_message;

    public $jobs_dashboard_model;
    public $auth_dashboard_model;
    public $financial_dashboard_model;

    public function __construct()
    {
        parent::__construct();
        /**** Model ****/
        $this->auth_model = "auth_model";
        $this->product_model = "product_model";
        $this->warranty_model = "warranty_model";
        $this->technician_model = "technician_model";
        $this->service_model = "service_model";
        $this->jobs_model = "jobs_model";
        $this->job_history_model = "job_history_model";
        $this->notification_model = "notification_model";
        $this->error_code_model = "error_code_model";
        $this->summary_model = "summary_model";
        $this->energy_model = "energy_model";
        $this->device_model = "device_model";
        $this->manual_model = "manual_model";
        $this->widget_model = "widget_model";
        $this->jobs_dashboard_model = "jobs_dashboard_model";
        $this->auth_dashboard_model = "auth_dashboard_model";
        $this->financial_dashboard_model = "financial_dashboard_model";
        $this->api_dashboard_model = "api_dashboard_model";


        // Library
        $this->load->library('Authorization_Token');
        $this->load->library('pagination');
        $this->load->library('session');
        $this->load->library('my_libraies');

        $this->load->config('pagination');

        $this->_authen_message ="";

        // Init User information
        $this->_user_id = "";
        $this->_user_email = "";
        $this->_user_role_id = "";
        $this->_user_group_id = "";
        $this->_user_status = "";


        $this->userInfo();

    }

    public function userInfo()
    {
        if ($this->authorization_token->userData()) {
            $user_info = $this->authorization_token->userData();
            if (is_object($user_info)) {
                $this->_user_id = $user_info->user_id;
                $this->_user_email = $user_info->email;
                $this->_user_group_id = $user_info->cus_group_id;
                $this->_user_role_id = $user_info->user_role_id;
                $this->_user_status = $user_info->status;
            }
        } else {
            return false;
        }
    }


    public function is_authen()
    {
        $is_valid_token =  $this->authorization_token->validateToken();
        if(isset($is_valid_token)&&!is_blank($is_valid_token)&& $is_valid_token['status']===true){
            return true;
        }else{
            $this->_authen_message = $is_valid_token['message'];
            return false;
        }

    }


    public function message_template($status=false,$message='',$data=array()){

        if($status===false){
            $message = array(
                'status' => $status,
                'error' => $message,
                'data'=>$data
            );
        }else{
            $message = array(
                'status' => $status,
                'message' => $message,
                'data'=>$data
            );
        }
        return $message;
    }

    public function getUserSession()
    {
        $userSession = $this->session->userdata('userSession');

        return $userSession;
    }

    public function getSessionUserAid()
    {
        $obj = $this->getUserSession();
        return get_array_value($obj, "user_id", "");
    }

    public function getSessionUserRole()
    {
        $obj = $this->getUserSession();
        return get_array_value($obj, "user_role_id", "");
    }
    
    public function is_login()
    {
        $user_aid = $this->getSessionUserAid();
        if ($user_aid != "") {
            return true;
        } else {
            return false;
        }

    }

}
