<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('linelogin');
	}

	public function index(){

		if ($this->session->userdata('token')) {

			$verify = $this->linelogin->verify($this->session->userdata('token'));

			$verify = json_decode($verify);

			if (intval($verify->expires_in) > 0) {
				redirect('claim');
			} else {
				$url = $this->linelogin->getLink(3);
				redirect($url);
			}
			
		} else {
			$url = $this->linelogin->getLink(3);
			redirect($url);
		}
	}

	public function line_login_callback(){
		$get = $this->input->get();
		$code = $get['code'];
		$state = $get['state'];
		$token = $this->linelogin->token($code,$state);
		$token = json_decode($token);

		$access_token = $token->access_token;

		$profile = $this->linelogin->profile($access_token);

		$profile = json_decode($profile);

		$this->session->set_userdata('token', $access_token);
		$this->session->set_userdata('userId', $profile->userId);

		redirect('line_login');
	}
}