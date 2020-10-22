<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Financial_list extends MY_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('financial_dashboard_model', 'financial');
		$this->load->model('jobs_dashboard_model', 'jobs');
	}

	public function index()
	{
		if ($this->is_login()) {

			if ($this->getSessionUserRole() == 1 || $this->getSessionUserRole() == 8) {

				$url = '';

				if (!is_blank($this->input->get('filter_job_id'))) {
					$filter_job_id = $this->input->get('filter_job_id');

					$url .= '&filter_job_id=' . $this->input->get('filter_job_id');
				} else {
					$filter_job_id = '';
				}

				if (!is_blank($this->input->get('filter_invoice_id'))) {
					$filter_invoice_id = $this->input->get('filter_invoice_id');

					$url .= '&filter_invoice_id=' . $this->input->get('filter_invoice_id');
				} else {
					$filter_invoice_id = '';
				}

				if (!is_blank($this->input->get('filter_customer'))) {
					$filter_customer = $this->input->get('filter_customer');

					$url .= '&filter_customer=' . $this->input->get('filter_customer');
				} else {
					$filter_customer = '';
				}

				if (!is_blank($this->input->get('filter_telephone'))) {
					$filter_telephone = $this->input->get('filter_telephone');

					$url .= '&filter_telephone=' . $this->input->get('filter_telephone');
				} else {
					$filter_telephone = '';
				}

				if (!is_blank($this->input->get('filter_service_type'))) {
					$filter_service_type = $this->input->get('filter_service_type');

					$url .= '&filter_service_type=' . $this->input->get('filter_service_type');
				} else {
					$filter_service_type = '';
				}

				if (!is_blank($this->input->get('filter_total'))) {
					$filter_total = $this->input->get('filter_total');

					$url .= '&filter_total=' . $this->input->get('filter_total');
				} else {
					$filter_total = '';
				}

				if (!is_blank($this->input->get('filter_payment_status'))) {
					$filter_payment_status = $this->input->get('filter_payment_status');

					$url .= '&filter_payment_status=' . $this->input->get('filter_payment_status');
				} else {
					$filter_payment_status = '';
				}

				if (!is_blank($this->input->get('filter_update_datetime'))) {
					$filter_update_datetime = $this->input->get('filter_update_datetime');

					$url .= '&filter_update_datetime=' . $this->input->get('filter_update_datetime');
				} else {
					$filter_update_datetime = '';
				}

				if (!is_blank($this->input->get('page'))) {
					$page = $this->input->get('page');
				} else {
					$page = 1;
				}

				$filter_data = array(
					'filter_job_id'        => $filter_job_id,
					'filter_invoice_id'	     => $filter_invoice_id,
					'filter_customer'	     => $filter_customer,
					'filter_telephone'    => $filter_telephone,
					'filter_service_type' => $filter_service_type,
					'filter_total'           => $filter_total,
					'filter_payment_status'           => $filter_payment_status,
					'filter_update_datetime'           => $filter_update_datetime,
					'start'                  => ($page - 1) * 20,
					'limit'                  => 20
				);

				$this->data['financial_list'] = $this->financial->getFinancialList($filter_data);
				$financial_list_total = $this->financial->getFinancialListTotal($filter_data);

				$config = $this->config->item('pagination_config');
				$config['base_url'] = site_url('job-list').'?'. $url .'&page=';
				$config['total_rows'] = (int)$financial_list_total;
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

				$total_page = ceil((int)$financial_list_total/$config['per_page']);

				$this->data['result_count'] = "Showing ".$start." to ".$end." of ".$config['total_rows']." ( ". $total_page ." Pages)";

				$this->pagination->initialize($config);

				$this->data['service_list'] = $this->jobs->getServiceList();

				$this->data['name'] = $this->session->userdata('userSession')['name'];
				$this->data['lastname'] = $this->session->userdata('userSession')['lastname'];

				$this->data['user-type'] = $this->getSessionUserRole();

				$this->load->view('tpl_financial_list',$this->data);

			} else {
				redirect('job-claim');
			}

		} else {
			redirect('login');
		}
	}

	public function invoice_form()
	{
		if ($this->is_login()) {

			if ($this->getSessionUserRole() == 1 || $this->getSessionUserRole() == 8) {

				$job_id = $this->uri->segment(2);

				$this->financial->setJobID($job_id);

				$this->data['service_fee'] = $this->financial->getServiceFeeStatus();

				$this->data['invoice_form'] = $this->financial->getInvoiceInfo();
				$this->data['invoice_service'] = $this->financial->getInvoiceService();
				$this->data['invoice_id'] = $this->financial->getInvoiceID();
				$total = $this->financial->getInvoiceTotal();

				$vat = $total['total']*(7/107);

				$fee_non_vat = $total['total'] - $vat;
				
				$this->data['sub-total'] = round($fee_non_vat,2);
				$this->data['vat'] = round($vat,2);
				$this->data['total'] = round($fee_non_vat,2) + round($vat,2);
				$this->data['unit'] = $total['unit'];

				$this->load->view('tpl_invoice_form',$this->data);
			} else {
				redirect('job-list');
			}

		} else {
			redirect('login');
		}
	}

	public function financial_report()
	{
		if ($this->is_login()) {

			if ($this->getSessionUserRole() == 1 || $this->getSessionUserRole() == 8) {

				$url = '';

				if (!is_blank($this->input->get('filter_from'))) {
					$filter_from = $this->input->get('filter_from');

					$url .= '&filter_from=' . $this->input->get('filter_from');
				} else {
					$filter_from = '';
				}

				if (!is_blank($this->input->get('filter_to'))) {
					$filter_to = $this->input->get('filter_to');

					$url .= '&filter_to=' . $this->input->get('filter_to');
				} else {
					$filter_to = '';
				}

				if (!is_blank($this->input->get('page'))) {
					$page = $this->input->get('page');
				} else {
					$page = 1;
				}

				$filter_data = array(
					'filter_from'        => $filter_from,
					'filter_to'	     			=> $filter_to,
					'start'                  => ($page - 1) * 20,
					'limit'                  => 20
				);

				$this->data['financial_report'] = $this->financial->getFinancialReport($filter_data);
				$financial_report_total = $this->financial->getFinancialReportTotal($filter_data);

				$config = $this->config->item('pagination_config');
				$config['base_url'] = site_url('financial-report').'?'. $url .'&page=';
				$config['total_rows'] = (int)$financial_report_total;
				$config['per_page'] = 20;
				$config['num_links'] = $config['total_rows']/$config['per_page'];
				$config['cur_tag_open'] = '<li class="page-item active"><a href="'. site_url('financial-report').'?'. $url .'&page=' . $page .'" class="page-link">';
				$config["cur_page"] = $page;

				$start = ($page - 1) * $config['per_page'] + 1;

				if ($page + ($config['per_page'] * $page) - 1 > $config['total_rows']) {
					$end = $config['total_rows'];
				} else {
					$end = $page + ($config['per_page'] * $page) - 1;
				}

				$total_page = ceil((int)$financial_report_total/$config['per_page']);

				$this->data['result_count'] = "Showing ".$start." to ".$end." of ".$config['total_rows']." ( ". $total_page ." Pages)";

				$this->pagination->initialize($config);

				$this->data['from'] = '';
				$this->data['to'] = '';

				if ($this->input->get()) {
					$this->data['from'] = $this->input->get('filter_from');
					$this->data['to'] = $this->input->get('filter_to');
				}

				$this->data['account_name'] = $this->session->userdata('userSession')['name'];
				$this->data['account_lastname'] = $this->session->userdata('userSession')['lastname'];

				$this->data['user-type'] = $this->getSessionUserRole();

				$this->load->view('tpl_financial_report',$this->data);

			} else {
				redirect('job-list');
			}

		} else {
			redirect('login');
		}
	}

	public function financial_export()
	{
		if ($this->is_login()) {

			if ($this->getSessionUserRole() == 1 || $this->getSessionUserRole() == 8) {

				$url = '';

				if (!is_blank($this->input->get('filter_from'))) {
					$filter_from = $this->input->get('filter_from');

					$url .= '&filter_from=' . $this->input->get('filter_from');
				} else {
					$filter_from = '';
				}

				if (!is_blank($this->input->get('filter_to'))) {
					$filter_to = $this->input->get('filter_to');

					$url .= '&filter_to=' . $this->input->get('filter_to');
				} else {
					$filter_to = '';
				}

				if (!is_blank($this->input->get('page'))) {
					$page = $this->input->get('page');
				} else {
					$page = 1;
				}

				$filter_data = array(
					'filter_from'        => $filter_from,
					'filter_to'	     			=> $filter_to,
					'start'                  => ($page - 1) * 20,
					'limit'                  => 20
				);

				$this->data['from'] = '';
				$this->data['to'] = '';

				if ($this->input->get()) {
					$this->data['from'] = $this->input->get('filter_from');
					$this->data['to'] = $this->input->get('filter_to');
				}

				$this->data['financial_report'] = $this->financial->getFinancialReport($filter_data);

				$this->load->view('tpl_financial_export',$this->data);

			} else {
				redirect('job-list');
			}

		} else {
			redirect('login');
		}
	}
}
