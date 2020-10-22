<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_list extends MY_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('user_dashboard_model', 'user');
	}

	public function index()
	{
		if ($this->is_login()) {

			if ($this->getSessionUserRole() == 1) {

				$url = '';

				if (!is_blank($this->input->get('filter_user_id'))) {
					$filter_user_id = $this->input->get('filter_user_id');

					$url .= '&filter_user_id=' . $this->input->get('filter_user_id');
				} else {
					$filter_user_id = '';
				}

				if (!is_blank($this->input->get('filter_name'))) {
					$filter_name = $this->input->get('filter_name');

					$url .= '&filter_name=' . $this->input->get('filter_name');
				} else {
					$filter_name = '';
				}

				if (!is_blank($this->input->get('filter_lastname'))) {
					$filter_lastname = $this->input->get('filter_lastname');

					$url .= '&filter_lastname=' . $this->input->get('filter_lastname');
				} else {
					$filter_lastname = '';
				}

				if (!is_blank($this->input->get('filter_user_role'))) {
					$filter_user_role = $this->input->get('filter_user_role');

					$url .= '&filter_user_role=' . $this->input->get('filter_user_role');
				} else {
					$filter_user_role = '';
				}

				if (!is_blank($this->input->get('filter_status'))) {
					$filter_status = $this->input->get('filter_status');

					$url .= '&filter_status=' . $this->input->get('filter_status');
				} else {
					$filter_status = '';
				}

				if (!is_blank($this->input->get('page'))) {
					$page = $this->input->get('page');
				} else {
					$page = 1;
				}

				$filter_data = array(
					'filter_user_id'        => $filter_user_id,
					'filter_name'	     => $filter_name,
					'filter_lastname'	     => $filter_lastname,
					'filter_user_role'    => $filter_user_role,
					'filter_status' 		=> $filter_status,
					'start'                  => ($page - 1) * 20,
					'limit'                  => 20
				);

				$this->data['user_list'] = $this->user->getUserList($filter_data);
				$user_list_total = $this->user->getUserListTotal($filter_data);

				$config = $this->config->item('pagination_config');
				$config['base_url'] = site_url('user-list').'?'. $url .'&page=';
				$config['total_rows'] = (int)$user_list_total;
				$config['per_page'] = 20;
				$config['num_links'] = $config['total_rows']/$config['per_page'];
				$config['cur_tag_open'] = '<li class="page-item active"><a href="'. site_url('user-list').'?'. $url .'&page=' . $page .'" class="page-link">';
				$config["cur_page"] = $page;

				$start = ($page - 1) * $config['per_page'] + 1;

				if ($page + ($config['per_page'] * $page) - 1 > $config['total_rows']) {
					$end = $config['total_rows'];
				} else {
					$end = $page + ($config['per_page'] * $page) - 1;
				}

				$total_page = ceil((int)$user_list_total/$config['per_page']);

				$this->data['result_count'] = "Showing ".$start." to ".$end." of ".$config['total_rows']." ( ". $total_page ." Pages)";

				$this->pagination->initialize($config);

				$this->data['user_role_list'] = $this->user->getUserRole();

				$this->data['name'] = $this->session->userdata('userSession')['name'];
				$this->data['lastname'] = $this->session->userdata('userSession')['lastname'];

				$this->data['user-type'] = $this->getSessionUserRole();

				$this->load->view('tpl_user_list',$this->data);

			} elseif ($this->getSessionUserRole() == 9) {
				redirect('job-list');
			} else {
				redirect('financial-list');
			}

		} else {
			redirect('login');
		}
	}

	public function user_form()
	{

		if ($this->is_login()) {

			if ($this->getSessionUserRole() == 1) {

				$user_id = $this->uri->segment('3');

				$this->user->setUserID($user_id);

				$user = $this->user->getUserInfo();

				$this->data['user_id'] = $user_id;

				$this->data['user_name'] = $user['user_name'];
				$this->data['name'] = $user['name'];
				$this->data['lastname'] = $user['lastname'];
				$this->data['user_role'] = $user['user_role'];
				$this->data['status'] = $user['status'];

				$this->data['method'] = $this->input->get('method');

				$this->data['user_role_list'] = $this->user->getUserRole();

				if ($this->input->post() && !is_blank($this->input->post())) {

					$name = $this->input->post('name');
					$lastname = $this->input->post('lastname');
					$user_role = $this->input->post('user_role');
					$user_name = $this->input->post('user_name');

					if (!is_blank($this->input->post('password')) && $this->input->post('password') != '') {
						$password = $this->input->post('password');

						$this->user->setPassword($password);
					}

					$status = $this->input->post('status');

					$this->user->setName($name);
					$this->user->setLastname($lastname);
					$this->user->setUserRole($user_role);
					$this->user->setStatus($status);
					$this->user->setUserName($user_name);

					if ($this->input->get('method') == 'edit') {
						$this->user->updateUserAccount();

						redirect('user-list');
					} else {
						
						if (!$this->user->addUserAccount()) {
							echo '<script language="javascript">';
							echo 'alert("This account already exists. Please user anoter email.")';
							echo '</script>';
						} else {
							redirect('user-list');
						}
					}
				}

				$this->data['account_name'] = $this->session->userdata('userSession')['name'];
				$this->data['account_lastname'] = $this->session->userdata('userSession')['lastname'];

				$this->data['user-type'] = $this->getSessionUserRole();

				$this->load->view('tpl_user_list_form',$this->data);

			} elseif ($this->getSessionUserRole() == 9) {
				redirect('job-list');
			} else {
				redirect('financial-list');
			}

		} else {
			redirect('login');
		}
	}
}
