<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Job_claim extends MY_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('jobs_dashboard_model', 'jobs');

		$this->load->library('my_libraies');
	}

	public function index()
	{
		if ($this->is_login()) {

			if ($this->getSessionUserRole() == 1 || $this->getSessionUserRole() == 9) {

				$url = '';

				if (!is_blank($this->input->get('filter_claim_id'))) {
					$filter_claim_id = $this->input->get('filter_claim_id');

					$url .= '&filter_claim_id=' . $this->input->get('filter_claim_id');
				} else {
					$filter_claim_id = '';
				}

				if (!is_blank($this->input->get('filter_customer'))) {
					$filter_customer = $this->input->get('filter_customer');

					$url .= '&filter_customer=' . $this->input->get('filter_customer');
				} else {
					$filter_customer = '';
				}

				if (!is_blank($this->input->get('filter_job_type'))) {
					$filter_job_type = $this->input->get('filter_job_type');

					$url .= '&filter_job_type=' . $this->input->get('filter_job_type');
				} else {
					$filter_job_type = '';
				}

				if (!is_blank($this->input->get('filter_serial_number'))) {
					$filter_serial_number = $this->input->get('filter_serial_number');

					$url .= '&filter_serial_number=' . $this->input->get('filter_serial_number');
				} else {
					$filter_serial_number = '';
				}

				if (!is_blank($this->input->get('filter_date'))) {
					$filter_date = $this->input->get('filter_date');

					$url .= '&filter_date=' . $this->input->get('filter_date');
				} else {
					$filter_date = '';
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
					'filter_claim_id'        => $filter_claim_id,
					'filter_customer'	     => $filter_customer,
					'filter_serial_number'	 => $filter_serial_number,
					'filter_job_type'	 	 => $filter_job_type,
					'filter_date'    		 => $filter_date,
					'filter_status' 		 => $filter_status,
					'start'                  => ($page - 1) * 20,
					'limit'                  => 20
				);

				$this->data['claim_list'] = $this->jobs->getClaimList($filter_data);
				$claim_list_total = $this->jobs->getClaimListTotal($filter_data);

				$config = $this->config->item('pagination_config');
				$config['base_url'] = site_url('job-list').'?'. $url .'&page=';
				$config['total_rows'] = (int)$claim_list_total;
				$config['per_page'] = 20;
				$config['num_links'] = $config['total_rows']/$config['per_page'];
				$config['cur_tag_open'] = '<li class="page-item active"><a href="'. site_url('job-list').'?'. $url .'&page=' . $page .'" class="page-link">';
				$config["cur_page"] = $page;

				$start = ($page - 1) * $config['per_page'] + 1;

				if ($page + ($config['per_page'] * $page) - 1 > $config['total_rows']) {
					$end = $config['total_rows'];
				} else {
					$end = $page + ($config['per_page'] * $page) - 1;
				}

				$total_page = ceil((int)$claim_list_total/$config['per_page']);

				$this->data['result_count'] = "Showing ".$start." to ".$end." of ".$config['total_rows']." ( ". $total_page ." Pages)";

				$this->pagination->initialize($config);

				$this->data['name'] = $this->session->userdata('userSession')['name'];
				$this->data['lastname'] = $this->session->userdata('userSession')['lastname'];

				$this->data['user-type'] = $this->getSessionUserRole();

				$this->load->view('tpl_claim_list',$this->data);

			} else {
				redirect('financial-list');
			}

		} else {
			redirect('login');
		}
	}

    public function claim_form()
	{

		if ($this->is_login()) {

			if ($this->getSessionUserRole() == 1 || $this->getSessionUserRole() == 9) {

				$claim_id = $this->uri->segment('3');

				$this->jobs->setClaimID($claim_id);

				$claim = $this->jobs->getClaim();

				$this->data['claim_id'] = $claim['claim_id'];
				$this->data['customer'] = $claim['firstname'].' '.$claim['lastname'];
				$this->data['phone_number'] = $claim['phone_number'];
				$this->data['type'] = $claim['type'];
				$this->data['serial_number_indoor'] = $claim['serial_number_indoor'];
				$this->data['serial_number_outdoor'] = $claim['serial_number_outdoor'];
				$this->data['claim_date'] = $claim['claim_date'];
				$this->data['status'] = $claim['status'];
				$this->data['problem'] = $claim['problem'];
				$this->data['img_total'] = $claim['img_total'];
				$this->data['officer'] = $claim['officer'];
				$this->data['technician'] = $claim['technician'];
				$this->data['update_date'] = $claim['update_date'];
				$this->data['note'] = $claim['note'];
				$this->data['job_type'] = $claim['job_type'];
				$this->data['address'] = $claim['address'];
				$this->data['part_number'] = $claim['part_number'];

				$this->data['method'] = $this->input->get('method');

				if ($claim['type'] == 'air_con') {
					$this->data['type'] = 'เครื่องปรับอากาศ';
				} else if ($claim['type'] == 'air_puri') {
					$this->data['type'] = 'เครื่องฟอก';
				} else {
					$this->data['type'] = 'อะไหล่';
				}

				if ($claim['warranty'] == 'yes') {
					$this->data['warranty'] = "อยู่ในประกัน";
				} else {
					$this->data['warranty'] = "หมดประกัน";
				}

				if ($this->input->post()) {

					$this->jobs->setClaimID($claim['claim_id']);
					$this->jobs->setOfficer($this->input->post('officer'));
					$this->jobs->setTechnician($this->input->post('technician'));
					$this->jobs->setStatus($this->input->post('status'));
					$this->jobs->setNote($this->input->post('note'));
					$this->jobs->setSerialIndoor($this->input->post('serial_number_indoor'));
					$this->jobs->setSerialOutdoor($this->input->post('serial_number_outdoor'));
					$this->jobs->setPart($this->input->post('part_number'));
					$this->jobs->setWarranty($this->input->post('warranty'));

					$technician = $this->input->post('technician');
					$officer = $this->input->post('officer');
					$note = $this->input->post('note');
					$status = $this->input->post('status');
					$job_type = $claim['job_type'];
					$warranty = $this->input->post('warranty');

					if ($status == 'Processing') {
						$status = 'อยู่ระหว่างดำเนินการ';
					} else if ($status == "Complete") {
						$status = 'เสร็จสิ้น';
					}

					$this->jobs->updateClaim();

					$line_id = $this->jobs->getLineID();

					if (!is_blank($line_id) && $this->input->post('status') != 'Pending') {
						$this->my_libraies->line_push_noti($line_id,$job_type,$technician,$officer,$note,$status);
					}

					header( "location: ".site_url('job-claim'));

				}

				$this->data['name'] = $this->session->userdata('userSession')['name'];
				$this->data['lastname'] = $this->session->userdata('userSession')['lastname'];

				$this->data['user-type'] = $this->getSessionUserRole();

				$this->load->view('tpl_claim_form',$this->data);

			} else {
				redirect('financial-list');
			}

		} else {
			redirect('login');
		}
	}

	public function export()
	{
		if ($this->is_login()) {

			if ($this->getSessionUserRole() == 1 || $this->getSessionUserRole() == 8) {

				$url = '';

				if (!is_blank($this->input->get('filter_claim_id'))) {
					$filter_claim_id = $this->input->get('filter_claim_id');

					$url .= '&filter_claim_id=' . $this->input->get('filter_claim_id');
				} else {
					$filter_claim_id = '';
				}

				if (!is_blank($this->input->get('filter_customer'))) {
					$filter_customer = $this->input->get('filter_customer');

					$url .= '&filter_customer=' . $this->input->get('filter_customer');
				} else {
					$filter_customer = '';
				}

				if (!is_blank($this->input->get('filter_job_type'))) {
					$filter_job_type = $this->input->get('filter_job_type');

					$url .= '&filter_job_type=' . $this->input->get('filter_job_type');
				} else {
					$filter_job_type = '';
				}

				if (!is_blank($this->input->get('filter_serial_number'))) {
					$filter_serial_number = $this->input->get('filter_serial_number');

					$url .= '&filter_serial_number=' . $this->input->get('filter_serial_number');
				} else {
					$filter_serial_number = '';
				}

				if (!is_blank($this->input->get('filter_date'))) {
					$filter_date = $this->input->get('filter_date');

					$url .= '&filter_date=' . $this->input->get('filter_date');
				} else {
					$filter_date = '';
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
					'filter_claim_id'        => $filter_claim_id,
					'filter_customer'	     => $filter_customer,
					'filter_serial_number'	 => $filter_serial_number,
					'filter_job_type'	 	 => $filter_job_type,
					'filter_date'    		 => $filter_date,
					'filter_status' 		 => $filter_status,
					'start'                  => ($page - 1) * 20,
					'limit'                  => 20
				);

				$this->data['report'] = $this->jobs->getClaimReport($filter_data);

				$this->load->view('tpl_job_claim_export',$this->data);

			} else {
				redirect('job-claim');
			}

		} else {
			redirect('login');
		}
	}

	public function claim_form_print()
	{

		if ($this->is_login()) {

			if ($this->getSessionUserRole() == 1 || $this->getSessionUserRole() == 9) {

				$claim_id = $this->uri->segment('2');

				$this->jobs->setClaimID($claim_id);

				$claim = $this->jobs->getClaim();

				$this->data['claim_id'] = $claim['claim_id'];
				$this->data['customer'] = $claim['firstname'].' '.$claim['lastname'];
				$this->data['phone_number'] = $claim['phone_number'];
				$this->data['type'] = $claim['type'];
				$this->data['serial_number_indoor'] = $claim['serial_number_indoor'];
				$this->data['serial_number_outdoor'] = $claim['serial_number_outdoor'];
				$this->data['claim_date'] = $claim['claim_date'];
				$this->data['status'] = $claim['status'];
				$this->data['problem'] = $claim['problem'];
				$this->data['img_total'] = $claim['img_total'];
				$this->data['officer'] = $claim['officer'];
				$this->data['technician'] = $claim['technician'];
				$this->data['update_date'] = $claim['update_date'];
				$this->data['note'] = $claim['note'];
				$this->data['job_type'] = $claim['job_type'];
				$this->data['address'] = $claim['address'];
				$this->data['part_number'] = $claim['part_number'];

				$this->data['method'] = $this->input->get('method');

				if ($claim['type'] == 'air_con') {
					$this->data['type'] = 'เครื่องปรับอากาศ';
				} else if ($claim['type'] == 'air_puri') {
					$this->data['type'] = 'เครื่องฟอก';
				} else {
					$this->data['type'] = 'อะไหล่';
				}

				if ($claim['warranty'] == 'yes') {
					$this->data['warranty'] = "อยู่ในประกัน";
				} else {
					$this->data['warranty'] = "หมดประกัน";
				}

				if ($claim['status'] == 'Pending') {
					$this->data['status'] = "รอดำเนินการติดต่อกับ";
				} else if ($claim['status'] == 'Processing') {
					$this->data['status'] = "อยู่ระหว่างดำเนินการ";
				} else {
					$this->data['status'] = "เสร็จสิ้น";
				}

				$this->data['name'] = $this->session->userdata('userSession')['name'];
				$this->data['lastname'] = $this->session->userdata('userSession')['lastname'];

				$this->data['user-type'] = $this->getSessionUserRole();

				$this->load->view('tpl_claim_form_print',$this->data);

			} else {
				redirect('financial-list');
			}

		} else {
			redirect('login');
		}
	}

	public function manual() {

		$file_url = base_url()."manual/คู่มือการใช้งาน API Dashboard.pdf";
		header('Content-Type: application/pdf');
		header("Content-disposition: inline; filename=\"" . basename($file_url) . "\""); 
		readfile($file_url);
	}
}
