<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Token extends MY_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('user_dashboard_model', 'user');
	}

	public function index()
	{
		if ($this->is_login()) {

			if ($this->getSessionUserRole() == 1 || $this->getSessionUserRole() == 9) {

				$this->user->setUserID($this->getSessionUserAid());
				$this->user->setUserToken($this->input->post('token'));

				$this->user->updateToken();

			}

		}
	}

}
