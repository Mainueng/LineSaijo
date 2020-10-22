<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Job_install extends MY_Controller {

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

				if (!is_blank($this->input->get('filter_order_id'))) {
					$filter_order_id = $this->input->get('filter_order_id');

					$url .= '&filter_order_id=' . $this->input->get('filter_order_id');
				} else {
					$filter_order_id = '';
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

				if (!is_blank($this->input->get('filter_appointment_date'))) {
					$filter_appointment_date = $this->input->get('filter_appointment_date');

					$url .= '&filter_appointment_date=' . $this->input->get('filter_appointment_date');
				} else {
					$filter_appointment_date = '';
				}

				if (!is_blank($this->input->get('filter_appointment_time'))) {
					$filter_appointment_time = $this->input->get('filter_appointment_time');

					$url .= '&filter_appointment_time=' . $this->input->get('filter_appointment_time');
				} else {
					$filter_appointment_time = '';
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
					'filter_order_id'        => $filter_order_id,
					'filter_customer'	     => $filter_customer,
					'filter_telephone'	     => $filter_telephone,
					'filter_appointment_date'    => $filter_appointment_date,
					'filter_appointment_time' => $filter_appointment_time,
					'filter_status'           => $filter_status,
					'start'                  => ($page - 1) * 20,
					'limit'                  => 20
				);

				$this->data['job_install'] = $this->jobs->getInstallList($filter_data);
				$install_list_total = $this->jobs->getInstallListTotal($filter_data);

				$config = $this->config->item('pagination_config');
				$config['base_url'] = site_url('job-install').'?'. $url .'&page=';
				$config['total_rows'] = (int)$install_list_total;
				$config['per_page'] = 20;
				$config['num_links'] = $config['total_rows']/$config['per_page'];
				$config['cur_tag_open'] = '<li class="page-item active"><a href="'. site_url('job-install').'?'. $url .'&page=' . $page .'" class="page-link">';
				$config["cur_page"] = $page;

				$start = ($page - 1) * $config['per_page'] + 1;

				if ($page + ($config['per_page'] * $page) - 1 > $config['total_rows']) {
					$end = $config['total_rows'];
				} else {
					$end = $page + ($config['per_page'] * $page) - 1;
				}

				$total_page = ceil((int)$install_list_total/$config['per_page']);

				$this->data['result_count'] = "Showing ".$start." to ".$end." of ".$config['total_rows']." ( ". $total_page ." Pages)";

				$this->pagination->initialize($config);

				$this->data['name'] = $this->session->userdata('userSession')['name'];
				$this->data['lastname'] = $this->session->userdata('userSession')['lastname'];

				$this->data['user-type'] = $this->getSessionUserRole();

				$this->load->view('tpl_job_install',$this->data);

			} else {
				redirect('financial-list');
			}

		} else {
			redirect('login');
		}
	}

	public function install_form()
	{

		if ($this->is_login()) {

			if ($this->getSessionUserRole() == 1 || $this->getSessionUserRole() == 9) {

				$order_id = $this->uri->segment('3');

				$this->jobs->setOrderID($order_id);

				$order = $this->jobs->getOrder();

				$this->data['order_id'] = $order_id;
				$this->data['customer'] = $order['name'].' '.$order['lastname'];
				$this->data['telephone'] = $order['telephone'];
				$this->data['appointment_date'] = $order['appointment_date'];
				$appointment_time = explode(" - ",$order['appointment_time']);
				$this->data['appointment_time'] = $appointment_time[0].' - '.$appointment_time[1];
				$this->data['product'] = $order['product'];
				$this->data['latitude'] = $order['latitude'];
				$this->data['longitude'] = $order['longitude'];
				$this->data['status'] = $order['status'];

				$this->data['appointment_datetime'] = $order['appointment_date'].' '.$appointment_time[0];
				$this->data['comment'] = '';
				$this->data['tech_id'] = '';
				$this->data['job_id'] = '';

				$this->jobs->setLatitude($order['latitude']);
				$this->jobs->setLongitude($order['longitude']);

				$this->data['technician_list'] = $this->jobs->getTechnicianList();
				$this->data['status_list'] = $this->jobs->getStatusList();
				$this->data['service_list'] = $this->jobs->getServiceList();
				$this->data['service_fee'] = $this->jobs->getServiceFee();

				$this->data['method'] = $this->input->get('method');

				if ($this->input->get('method') == 'add') {
					$this->data['disable'] = "disabled";
				} else {
					$this->data['disable'] = '';
				}

				$this->data['service_cost'] = $this->jobs->getServiceCost();
				$this->data['service_cost_total'] = $this->jobs->getInstallServiceCostTotal();

				$this->jobs->setTotal($this->data['service_cost_total']);

				if ($this->input->get('method') == 'edit') {

					$job = $this->jobs->getJobsFormOrder();

					$appointment_datetime = $job['appointment_datetime'];
					$date = str_replace('/', '-', $appointment_datetime );
					$newDate = date("d/m/Y H:i", strtotime($date));

					$this->data['appointment_datetime'] = $newDate;
					$this->data['comment'] = $job['problem'];
					$this->data['tech_id'] = $job['tech_id'];
					$this->data['job_id'] = $job['job_id'];
				}

				if ($this->input->post()) {

					$this->jobs->setTelephone($this->input->post('telephone'));

					$cus_data = $this->jobs->getCustomerFormTel();

					$this->jobs->setCusID($cus_data['cus_id']);
					$this->jobs->setName($cus_data['name']);
					$this->jobs->setLastName($cus_data['lastname']);

					$appointment_datetime = $this->input->post('appointment_datetime');
					$date = str_replace('/', '-', $appointment_datetime );
					$newDate = date("Y-m-d H:i", strtotime($date));

					$this->jobs->setAppointmentDatetime($newDate);
					$this->jobs->setComment($this->input->post('comment'));
					$this->jobs->setTypeCode(4);
					$this->jobs->setStatus($this->input->post('status'));
					$this->jobs->setServiceFee($this->input->post('service_fee'));
					
					foreach ($this->data['service_cost'] as $cost) {
						for ($i=0; $i < $cost['quantity']; $i++) { 
							$serial[] = $cost['name']." ". $cost['value'] ." - ". $cost['cost'];
						}

						if (!$this->input->post('service_fee')) {
							for ($i=0; $i < $cost['quantity']; $i++) { 
								$serial[] = "ส่วนลดค่าติดตั้ง ".$cost['name']." ". $cost['value'] ." - -". $cost['cost'];
							}
						}
					}

					$this->jobs->setInstallList(implode(",",$serial));

					if ($this->data['job_id']) {
						
						$job_id = $this->data['job_id'];

						$this->jobs->setJobID($job_id);
						
						$this->jobs->update_job();

					} else {

						$job_id = $this->jobs->check_order_id();

						if ($job_id) {
							$this->jobs->setJobID($job_id);

							$this->jobs->update_job();
						} else {

							$invoice_id = $this->jobs->getLastInvoice();
							$this->jobs->setInvoice($invoice_id);
							
							$job_id = $this->jobs->create_job();

							$this->jobs->setServiceHistory($job_id,$this->data['service_cost']);
						}
					}

					$this->jobs->setTechID($this->input->post('technician'));

					$fcm_tokens = $this->jobs->get_fcmToken();

					$count = 0;
					$error = 0;
					$notify = 0;

					if ($fcm_tokens !== false && !is_blank($fcm_tokens) && $job_id) {

						$this->jobs->set_notification($this->input->post('technician'),$job_id,3);

						foreach ($fcm_tokens as $row) {

							$count = $this->jobs->notification_count();

							if (!$this->my_libraies->FCM($row['device_id'],$job_id,false,$count)) {
								$error++;
							}

							$notify++;
						}
					}

					$notify = $notify - $error;

					if ($notify) {
						header( "location: ".site_url('job-install'));
					} else {
						echo '<script language="javascript">';
						echo 'alert("Failed to send notification message. Please choose another technician.")';
						echo '</script>';
					}

				}

				$this->data['name'] = $this->session->userdata('userSession')['name'];
				$this->data['lastname'] = $this->session->userdata('userSession')['lastname'];

				$this->data['user-type'] = $this->getSessionUserRole();

				$this->load->view('tpl_job_install_form',$this->data);

			} else {
				redirect('financial-list');
			}

		} else {
			redirect('login');
		}
	}
}
