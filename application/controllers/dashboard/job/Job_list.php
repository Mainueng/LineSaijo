<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Job_list extends MY_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('jobs_dashboard_model', 'jobs');
	}

	public function index()
	{
		if ($this->is_login()) {

			if ($this->getSessionUserRole() == 1 || $this->getSessionUserRole() == 9) {

				$url = '';

				if (!is_blank($this->input->get('filter_job_id'))) {
					$filter_job_id = $this->input->get('filter_job_id');

					$url .= '&filter_job_id=' . $this->input->get('filter_job_id');
				} else {
					$filter_job_id = '';
				}

				if (!is_blank($this->input->get('filter_customer'))) {
					$filter_customer = $this->input->get('filter_customer');

					$url .= '&filter_customer=' . $this->input->get('filter_customer');
				} else {
					$filter_customer = '';
				}

				if (!is_blank($this->input->get('filter_service'))) {
					$filter_service = $this->input->get('filter_service');

					$url .= '&filter_service=' . $this->input->get('filter_service');
				} else {
					$filter_service = '';
				}

				if (!is_blank($this->input->get('filter_appointment_datetime'))) {
					$filter_appointment_datetime = $this->input->get('filter_appointment_datetime');

					$url .= '&filter_appointment_datetime=' . $this->input->get('filter_appointment_datetime');
				} else {
					$filter_appointment_datetime = '';
				}

				if (!is_blank($this->input->get('filter_status'))) {
					$filter_status = $this->input->get('filter_status');

					$url .= '&filter_status=' . $this->input->get('filter_status');
				} else {
					$filter_status = '';
				}

				if (!is_blank($this->input->get('filter_technician'))) {
					$filter_technician = $this->input->get('filter_technician');

					$url .= '&filter_technician=' . $this->input->get('filter_technician');
				} else {
					$filter_technician = '';
				}

				if (!is_blank($this->input->get('page'))) {
					$page = $this->input->get('page');
				} else {
					$page = 1;
				}

				$filter_data = array(
					'filter_job_id'        => $filter_job_id,
					'filter_customer'	     => $filter_customer,
					'filter_service'	     => $filter_service,
					'filter_appointment_datetime'    => $filter_appointment_datetime,
					'filter_status' => $filter_status,
					'filter_technician'           => $filter_technician,
					'start'                  => ($page - 1) * 20,
					'limit'                  => 20
				);

				$this->data['job_list'] = $this->jobs->getList($filter_data);
				$job_list_total = $this->jobs->getListTotal($filter_data);

				$config = $this->config->item('pagination_config');
				$config['base_url'] = site_url('job-list').'?'. $url .'&page=';
				$config['total_rows'] = (int)$job_list_total;
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

				$total_page = ceil((int)$job_list_total/$config['per_page']);

				$this->data['result_count'] = "Showing ".$start." to ".$end." of ".$config['total_rows']." ( ". $total_page ." Pages)";

				$this->pagination->initialize($config);

				$this->data['service_list'] = $this->jobs->getServiceList();
				$this->data['status_list'] = $this->jobs->getStatusList();

				$this->data['name'] = $this->session->userdata('userSession')['name'];
				$this->data['lastname'] = $this->session->userdata('userSession')['lastname'];

				$this->data['user-type'] = $this->getSessionUserRole();

				$this->load->view('tpl_job_list',$this->data);

			} else {
				redirect('financial-list');
			}

		} else {
			redirect('login');
		}
	}

	public function job_form()
	{

		if ($this->is_login()) {

			if ($this->getSessionUserRole() == 1 || $this->getSessionUserRole() == 9) {

				$job_id = $this->uri->segment('3');

				$this->jobs->setJobID($job_id);

				$job = $this->jobs->getJob();

				$this->data['telephone'] = $job['telephone'];

				$origDate = $job['appointment_datetime'];

				$date = str_replace('/', '-', $origDate );
				$newDate = date("d/m/Y H:i", strtotime($date));

				$this->data['appointment_datetime'] = $newDate;
				$this->data['service'] = $job['type_code'];

				$this->jobs->setLatitude($job['latitude']);
				$this->jobs->setLongitude($job['longitude']);
				$this->jobs->setCusID($job['cus_id']);

				$this->data['technician_list'] = $this->jobs->getTechnicianList();
				$this->data['status_list'] = $this->jobs->getStatusList();
				$this->data['service_list'] = $this->jobs->getServiceList();
				$this->data['customer'] = $this->jobs->getCustomer();

				$this->data['latitude'] = $job['latitude'];
				$this->data['longitude'] = $job['longitude'];
				$this->data['job_id'] = $job['job_id'];
				$this->data['problem'] = $job['problem'];
				$this->data['serial_indoor'] = $job['serial_indoor'];
				$this->data['serial_outdoor'] = $job['serial_outdoor'];
				$this->data['tech_id'] = $job['tech_id'];
				$this->data['status'] = $job['status'];
				$this->data['comment'] = $job['comment'];
				$this->data['install_list'] = explode(',',$job['install_list']);
				$this->data['service_fee'] = $job['service_fee'];
				$this->data['service_cost_total'] = $this->jobs->getInstallServiceTotal();

				if (!$job['service_fee']) {
					$this->data['service_cost_total'] = 0;
				}

				$this->data['method'] = $this->input->get('method');

				if ($this->input->get('method') == 'add') {
					$this->data['disable'] = "disabled";
				} else {
					$this->data['disable'] = '';
				}

				if ($this->input->post()) {

					$status = $this->input->post('status');

					if ($this->input->get('method') == 'add') {
						$status = 1;
					}

					$appointment_datetime = $this->input->post('appointment_datetime');
					$technician = $this->input->post('technician');

					$date = str_replace('/', '-', $appointment_datetime );
					$newDate = date("Y-m-d H:i", strtotime($date));

					$this->jobs->setStatus($status);
					$this->jobs->setAppointmentDatetime($newDate);

					$this->jobs->editJob();

					if ($status == 4) {
						$invoice = $this->jobs->getLastInvoice();
						$total = $this->jobs->getServiceTotal();
						$job_type = $this->jobs->job_type();
						$service_fee = $this->jobs->getServiceFee();

						if (!$service_fee) {
							$total = 0;
						}

						if ($job_type != 4 || ($job_type == 4 && $total != 0)) {
							$this->jobs->setInvoice($invoice);
							$this->jobs->setTotal($total);
							$this->jobs->setTypeCode($job_type);

							$this->jobs->addInvoice();
						}
					}

					$this->jobs->setTechID($this->input->post('technician'));

					$fcm_tokens = $this->jobs->get_fcmToken();

					$count = 0;
					$error = 0;
					$notify = 0;

					if ($status == 1 || $status == 5) {
						if ($fcm_tokens !== false && !is_blank($fcm_tokens)) {

							if ($status == 3) {
								$this->jobs->set_notification($this->input->post('technician'),$job_id,3);
								$cancel = false;
							} else {
								$cancel = true;
							}

							foreach ($fcm_tokens as $row) {

								$count = $this->jobs->notification_count();

								if (!$this->my_libraies->FCM($row['device_id'],$job['job_id'],$cancel,$count)) {
									$error++;
								}

								$notify++;
							}
						}

						$notify = $notify - $error;

						if ($notify) {
							header( "location: ".site_url('job-list'));
						} else {
							echo '<script language="javascript">';
							echo 'alert("Failed to send notification message. Please choose another technician.")';
							echo '</script>';
						}
					} else {
						header( "location: ".site_url('job-list'));
					}

				}

				$this->data['name'] = $this->session->userdata('userSession')['name'];
				$this->data['lastname'] = $this->session->userdata('userSession')['lastname'];

				$this->data['user-type'] = $this->getSessionUserRole();

				$this->load->view('tpl_job_list_form',$this->data);

			} else {
				redirect('financial-list');
			}

		} else {
			redirect('login');
		}
	}
}
