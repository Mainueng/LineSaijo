<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends MY_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('api_dashboard_model', 'api');
		$this->load->model('jobs_dashboard_model', 'jobs');
	}

	public function service_cost()
	{

		if ($this->is_login()) {
			if ($this->getSessionUserRole() == 1 || $this->getSessionUserRole() == 9) {

				$url = '';

				if (!is_blank($this->input->get('filter_service_id'))) {
					$filter_service_id = $this->input->get('filter_service_id');

					$url .= '&filter_service_id=' . $this->input->get('filter_service_id');
				} else {
					$filter_service_id = '';
				}

				if (!is_blank($this->input->get('filter_service_type'))) {
					$filter_service_type = $this->input->get('filter_service_type');

					$url .= '&filter_service_type=' . $this->input->get('filter_service_type');
				} else {
					$filter_service_type = '';
				}

				if (!is_blank($this->input->get('filter_service_name'))) {
					$filter_service_name = $this->input->get('filter_service_name');

					$url .= '&filter_service=' . $this->input->get('filter_service');
				} else {
					$filter_service_name = '';
				}

				if (!is_blank($this->input->get('filter_cost'))) {
					$filter_cost = $this->input->get('filter_cost');

					$url .= '&filter_cost=' . $this->input->get('filter_cost');
				} else {
					$filter_cost = '';
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
					'filter_service_id'     => $filter_service_id,
					'filter_service_type'	=> $filter_service_type,
					'filter_service_name'	=> $filter_service_name,
					'filter_cost'    		=> $filter_cost,
					'filter_status' 		=> $filter_status,
					'start'                  => ($page - 1) * 20,
					'limit'                  => 20
				);

				$this->data['service_cost_list'] = $this->api->getServiceCost($filter_data);
				$service_cost_list_total = $this->api->getServiceCostTotal($filter_data);

				$config = $this->config->item('pagination_config');
				$config['base_url'] = site_url('api/service-cost').'?'. $url .'&page=';
				$config['total_rows'] = (int)$service_cost_list_total;
				$config['per_page'] = 20;
				$config['num_links'] = $config['total_rows']/$config['per_page'];
				$config['cur_tag_open'] = '<li class="page-item active"><a href="'. site_url('api/service-cost').'?'. $url .'&page=' . $page .'" class="page-link">';
				$config["cur_page"] = $page;

				$start = ($page - 1) * $config['per_page'] + 1;

				if ($page + ($config['per_page'] * $page) - 1 > $config['total_rows']) {
					$end = $config['total_rows'];
				} else {
					$end = $page + ($config['per_page'] * $page) - 1;
				}

				$total_page = ceil((int)$service_cost_list_total/$config['per_page']);

				$this->data['result_count'] = "Showing ".$start." to ".$end." of ".$config['total_rows']." ( ". $total_page ." Pages)";

				$this->pagination->initialize($config);

				$this->data['service_list'] = $this->jobs->getServiceList();
				$this->data['service_name'] = $this->api->getServiceName();

				$this->data['account_name'] = $this->session->userdata('userSession')['name'];
				$this->data['account_lastname'] = $this->session->userdata('userSession')['lastname'];

				$this->data['user-type'] = $this->getSessionUserRole();

				$this->load->view('tpl_service_cost_list',$this->data);

			} else {
				redirect('financial-list');
			}

		} else {
			redirect('login');
		}
	}

	public function service_cost_form()
	{
		if ($this->is_login()) {
			if ($this->getSessionUserRole() == 1 || $this->getSessionUserRole() == 9) {

				$service_id = $this->uri->segment('4');

				$this->api->setServiceCostID($service_id);

				$method	= $this->input->get('method');

				$this->data['id'] = $service_id;
				$this->data['method'] = $method;

				if ($method == 'delete') {
					$this->api->deleteServiceCost();

					redirect('api/service-cost');
				} elseif ($method == 'edit') {
					$info = $this->api->getServiceCostInfo();

					$this->data['service_type'] = $info['service_type'];
					$this->data['service_name_en'] = $info['service_name_en'];
					$this->data['service_name_th'] = $info['service_name_th'];
					$this->data['btu'] = $info['btu'];
					$this->data['cost'] = $info['cost'];
					$this->data['unit'] = $info['unit'];
					$this->data['status'] = $info['status'];

					
					$this->data['service_name'] = $this->api->getServiceName();

				} else {
					$this->data['service_type'] = '';
					$this->data['service_name_en'] = '';
					$this->data['service_name_th'] = '';
					$this->data['btu'] = '';
					$this->data['cost'] = '';
					$this->data['unit'] = '';
					$this->data['status'] = '';

					$this->data['service_name'] = '';
				}
				
				$this->data['service_list'] = $this->jobs->getServiceList();
				$this->data['btu_list'] = array('9000 BTU','12000 BTU','18000 BTU','25000 BTU','30000 BTU','36000 BTU','Over 36,000 BTU');

				if ($this->input->post() && !is_blank($this->input->post())) {

					$service_type = $this->input->post('service_type');
					$service_name_en = $this->input->post('service_name_en');
					$service_name_th = $this->input->post('service_name_th');
					$btu = $this->input->post('btu');
					$cost = $this->input->post('cost');
					$unit = $this->input->post('unit');
					$status = $this->input->post('status');

					$this->api->setServiceType($service_type);
					$this->api->setServiceNameEN($service_name_en);
					$this->api->setServiceNameTH($service_name_th);
					$this->api->setBTU($btu);
					$this->api->setCost($cost);
					$this->api->setUnit($unit);
					$this->api->setStatus($status);

					if ($method == 'edit') {
						$msg = $this->api->updateServiceCost();
					} elseif ($method == 'add') {
						$msg = $this->api->addServiceCost();
					} else {
						redirect('api/service-cost');
					}

					if ($msg) {
						redirect('api/service-cost');
					}
				}

				$this->data['account_name'] = $this->session->userdata('userSession')['name'];
				$this->data['account_lastname'] = $this->session->userdata('userSession')['lastname'];

				$this->data['user-type'] = $this->getSessionUserRole();

				$this->load->view('tpl_service_cost_form',$this->data);
			} else {
				redirect('financial-list');
			}

		} else {
			redirect('login');
		}
	}

	public function service_type()
	{
		if ($this->is_login()) {

			if ($this->getSessionUserRole() == 1) {

				$url = '';

				if (!is_blank($this->input->get('filter_service_code'))) {
					$filter_service_code = $this->input->get('filter_service_code');

					$url .= '&filter_service_code=' . $this->input->get('filter_service_code');
				} else {
					$filter_service_code = '';
				}

				if (!is_blank($this->input->get('filter_service_name_en'))) {
					$filter_service_name_en = $this->input->get('filter_service_name_en');

					$url .= '&filter_service_name_en=' . $this->input->get('filter_service_name_en');
				} else {
					$filter_service_name_en = '';
				}

				if (!is_blank($this->input->get('filter_service_name_th'))) {
					$filter_service_name_th = $this->input->get('filter_service_name_th');

					$url .= '&filter_service_name_th=' . $this->input->get('filter_service_name_th');
				} else {
					$filter_service_name_th = '';
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
					'filter_service_code'        => $filter_service_code,
					'filter_service_name_en'	     => $filter_service_name_en,
					'filter_service_name_th'    => $filter_service_name_th,
					'filter_status' 		=> $filter_status,
					'start'                  => ($page - 1) * 20,
					'limit'                  => 20
				);

				$this->data['service_type'] = $this->api->getServiceType($filter_data);
				$service_type_total = $this->api->getServiceTypeTotal($filter_data);

				$config = $this->config->item('pagination_config');
				$config['base_url'] = site_url('api/service-type').'?'. $url .'&page=';
				$config['total_rows'] = (int)$service_type_total;
				$config['per_page'] = 20;
				$config['num_links'] = $config['total_rows']/$config['per_page'];
				$config['cur_tag_open'] = '<li class="page-item active"><a href="'. site_url('api/service-type').'?'. $url .'&page=' . $page .'" class="page-link">';
				$config["cur_page"] = $page;

				$start = ($page - 1) * $config['per_page'] + 1;

				if ($page + ($config['per_page'] * $page) - 1 > $config['total_rows']) {
					$end = $config['total_rows'];
				} else {
					$end = $page + ($config['per_page'] * $page) - 1;
				}

				$total_page = ceil((int)$service_type_total/$config['per_page']);

				$this->data['result_count'] = "Showing ".$start." to ".$end." of ".$config['total_rows']." ( ". $total_page ." Pages)";

				$this->pagination->initialize($config);

				$this->data['account_name'] = $this->session->userdata('userSession')['name'];
				$this->data['account_lastname'] = $this->session->userdata('userSession')['lastname'];

				$this->data['user-type'] = $this->getSessionUserRole();

				$this->load->view('tpl_service_type',$this->data);

			} elseif ($this->getSessionUserRole() == 9) {
				redirect('job-list');
			} else {
				redirect('financial-list');
			}

		} else {
			redirect('login');
		}
	}

	public function service_type_form()
	{

		if ($this->is_login()) {

			if ($this->getSessionUserRole() == 1) {

				$service_code = $this->uri->segment('4');

				$this->api->setServiceCode($service_code);

				$info = $this->api->getServiceInfo();

				$this->data['service_code'] = $service_code;
				$this->data['service_name_en'] = $info['name_en'];
				$this->data['service_name_th'] = $info['name_th'];
				$this->data['status'] = $info['status'];

				$this->data['method'] = $this->input->get('method');

				if ($this->input->get('method') == 'delete') {
					$this->api->deleteServiceInfo();
					redirect('api/service-type');
				}

				if ($this->input->post() && !is_blank($this->input->post())) {

					$service_code = $this->input->post('service_code');
					$name_en = $this->input->post('service_name_en');
					$name_th = $this->input->post('service_name_th');
					$status = $this->input->post('status');

					$this->api->setServiceNameEN($name_en);
					$this->api->setServiceNameTh($name_th);
					$this->api->setStatus($status);

					if ($this->input->get('method') == 'edit') {
						$this->api->updateServiceInfo();
						redirect('api/service-type');
					} else {

						$last_code = $this->api->getLastServiceCode();

						$this->api->setServiceCode($last_code);

						$this->api->addServiceInfo();

						redirect('api/service-type');

					}
				}

				$this->data['account_name'] = $this->session->userdata('userSession')['name'];
				$this->data['account_lastname'] = $this->session->userdata('userSession')['lastname'];

				$this->data['user-type'] = $this->getSessionUserRole();

				$this->load->view('tpl_service_type_form',$this->data);

			} elseif ($this->getSessionUserRole() == 9) {
				redirect('job-list');
			} else {
				redirect('financial-list');
			}

		} else {
			redirect('login');
		}
	}

	public function problems()
	{
		if ($this->is_login()) {

			if ($this->getSessionUserRole() == 1) {

				$url = '';

				if (!is_blank($this->input->get('filter_problem_name_en'))) {
					$filter_problem_name_en = $this->input->get('filter_problem_name_en');

					$url .= '&filter_problem_name_en=' . $this->input->get('filter_problem_name_en');
				} else {
					$filter_problem_name_en = '';
				}

				if (!is_blank($this->input->get('filter_problem_name_th'))) {
					$filter_problem_name_th = $this->input->get('filter_problem_name_th');

					$url .= '&filter_problem_name_th=' . $this->input->get('filter_problem_name_th');
				} else {
					$filter_problem_name_th = '';
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
					'filter_problem_name_en'	     => $filter_problem_name_en,
					'filter_problem_name_th'    => $filter_problem_name_th,
					'filter_status' 		=> $filter_status,
					'start'                  => ($page - 1) * 20,
					'limit'                  => 20
				);

				$this->data['problems'] = $this->api->getProblems($filter_data);
				$problems_total = $this->api->getProblemsTotal($filter_data);

				$config = $this->config->item('pagination_config');
				$config['base_url'] = site_url('api/problems').'?'. $url .'&page=';
				$config['total_rows'] = (int)$problems_total;
				$config['per_page'] = 20;
				$config['num_links'] = $config['total_rows']/$config['per_page'];
				$config['cur_tag_open'] = '<li class="page-item active"><a href="'. site_url('api/problems').'?'. $url .'&page=' . $page .'" class="page-link">';
				$config["cur_page"] = $page;

				$start = ($page - 1) * $config['per_page'] + 1;

				if ($page + ($config['per_page'] * $page) - 1 > $config['total_rows']) {
					$end = $config['total_rows'];
				} else {
					$end = $page + ($config['per_page'] * $page) - 1;
				}

				$total_page = ceil((int)$problems_total/$config['per_page']);

				$this->data['result_count'] = "Showing ".$start." to ".$end." of ".$config['total_rows']." ( ". $total_page ." Pages)";

				$this->pagination->initialize($config);

				$this->data['account_name'] = $this->session->userdata('userSession')['name'];
				$this->data['account_lastname'] = $this->session->userdata('userSession')['lastname'];

				$this->data['user-type'] = $this->getSessionUserRole();

				$this->load->view('tpl_problems',$this->data);

			} elseif ($this->getSessionUserRole() == 9) {
				redirect('job-list');
			} else {
				redirect('financial-list');
			}

		} else {
			redirect('login');
		}
	}

	public function problems_form()
	{

		if ($this->is_login()) {

			if ($this->getSessionUserRole() == 1) {

				$problem_id = $this->uri->segment('4');

				$this->api->setProblemID($problem_id);

				$info = $this->api->getProblemsInfo();

				$this->data['id'] = $problem_id;
				$this->data['problem_name_en'] = $info['name_en'];
				$this->data['problem_name_th'] = $info['name_th'];
				$this->data['status'] = $info['status'];

				$this->data['method'] = $this->input->get('method');

				if ($this->input->get('method') == 'delete') {
					$this->api->deleteProblems();
					redirect('api/problems');
				}

				if ($this->input->post() && !is_blank($this->input->post())) {

					$name_en = $this->input->post('problem_name_en');
					$name_th = $this->input->post('problem_name_th');
					$status = $this->input->post('status');

					$this->api->setProblemNameEN($name_en);
					$this->api->setProblemNameTH($name_th);
					$this->api->setStatus($status);

					if ($this->input->get('method') == 'edit') {
						$this->api->updateProblems();
						redirect('api/problems');
					} else {
						$this->api->addProblems();
						redirect('api/problems');

					}
				}

				$this->data['account_name'] = $this->session->userdata('userSession')['name'];
				$this->data['account_lastname'] = $this->session->userdata('userSession')['lastname'];

				$this->data['user-type'] = $this->getSessionUserRole();

				$this->load->view('tpl_problems_form',$this->data);

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
