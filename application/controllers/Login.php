<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends MY_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('auth_model', 'auth');

		$this->load->library('form_validation');

	}

	public function index()
	{
		if ($this->session->userdata('ci_session_key_generate') == FALSE) {

			if ($this->input->post() && $this->validation()) {
				if ($this->getSessionUserRole() == 1 || $this->getSessionUserRole() == 9) {
					redirect('job-claim');
				} else {
					redirect('financial-list');
				}

			} else if ($this->input->post() && !$this->validation()) {
				redirect('login?&error=1');
			} else {
				$this->load->view('tpl_login');
			}

		} else {

			if ($this->session->userdata('userSession')['user_role_id'] == 1 || $this->getSessionUserRole() == 9) {
				redirect('job-claim');
			} else {
				redirect('job-claim');
			}
		}
		
	}

	public function validation()
	{
		$this->form_validation->set_rules('user', 'User', 'trim|required');
		$this->form_validation->set_rules('password', 'Password', 'trim|required');

		if ($this->form_validation->run() == FALSE) {
			$this->index();
		} else {
			$sessArray = array();
			$username = $this->input->post('user');
			$password = $this->input->post('password');

			$this->auth->setUserName($username);
			$this->auth->setPassword($password);

			$result = $this->auth->login();

			if ($result) {
				$authArray = array(
					'user_id' => $result->id,
					'email' => $result->email,
					'name' => $result->name,
					'lastname' => $result->lastname,
					'user_role_id' => $result->user_role_id,
					'cus_group_id' => $result->cus_group_id,
					'status' => $result->status
				);

				$this->session->set_userdata('ci_session_key_generate', TRUE);
				$this->session->set_userdata('ci_seesion_key', $authArray);
				$this->session->set_userdata('userSession', $authArray);

				return true;
			}

			return false;

		}

		return false;
	}

	public function logout() {
        $this->session->unset_userdata('ci_seesion_key');
        $this->session->unset_userdata('ci_session_key_generate');
        $this->session->sess_destroy();
        $this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
        $this->output->set_header("Pragma: no-cache");

        redirect('login');
    }
}
