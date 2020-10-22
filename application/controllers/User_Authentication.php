<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_Authentication extends MY_Controller {
    function __construct() {
        parent::__construct();

        // Load facebook library
        $this->load->library('facebook');

        //Load user model
        $this->load->model('User_model','user');
    }

    public function index(){

        $_SESSION['app'] = 'core';

        // Check if user is logged in
        if($this->facebook->is_authenticated()){

            // Get logout URL
            $data['logoutURL'] = $this->facebook->logout_url();

        }else{
            // Get login URL
            $data['authURL'] =  $this->facebook->login_url();
        }

        $data['app'] = $_SESSION['app'];

        // Load login & profile view
        $this->load->view('user_authentication/index',$data);
    }

    public function logout() {
        // Remove local Facebook session
        $this->facebook->destroy_session();
        // Remove user data from session
        $this->session->unset_userdata('userData');
        // Redirect to login page
        redirect('user_authentication');
    }
}
