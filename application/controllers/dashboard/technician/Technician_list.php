<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Technician_list extends MY_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('user_dashboard_model', 'user');
	}

	public function index()
	{
		if ($this->is_login()) {

			if ($this->getSessionUserRole() == 1 || $this->getSessionUserRole() == 9) {

				$url = '';

				if (!is_blank($this->input->get('filter_technician_id'))) {
					$filter_technician_id = $this->input->get('filter_technician_id');

					$url .= '&filter_technician_id=' . $this->input->get('filter_technician_id');
				} else {
					$filter_technician_id = '';
				}

				if (!is_blank($this->input->get('filter_technician'))) {
					$filter_technician = $this->input->get('filter_technician');

					$url .= '&filter_technician=' . $this->input->get('filter_technician');
				} else {
					$filter_technician = '';
				}

				if (!is_blank($this->input->get('filter_telephone'))) {
					$filter_telephone = $this->input->get('filter_telephone');

					$url .= '&filter_telephone=' . $this->input->get('filter_telephone');
				} else {
					$filter_telephone = '';
				}

				if (!is_blank($this->input->get('filter_email'))) {
					$filter_email = $this->input->get('filter_email');

					$url .= '&filter_email=' . $this->input->get('filter_email');
				} else {
					$filter_email = '';
				}

				if (!is_blank($this->input->get('filter_province'))) {
					$filter_province = $this->input->get('filter_province');

					$url .= '&filter_province=' . $this->input->get('filter_province');
				} else {
					$filter_province = '';
				}

				if (!is_blank($this->input->get('filter_rating'))) {
					$filter_rating = $this->input->get('filter_rating');

					$url .= '&filter_rating=' . $this->input->get('filter_rating');
				} else {
					$filter_rating = '';
				}

				if (!is_blank($this->input->get('filter_dealer_store'))) {
					$filter_dealer_store = $this->input->get('filter_dealer_store');

					$url .= '&filter_dealer_store=' . $this->input->get('filter_dealer_store');
				} else {
					$filter_dealer_store = '';
				}

				if (!is_blank($this->input->get('filter_saijo_certification'))) {
					$filter_saijo_certification = $this->input->get('filter_saijo_certification');

					$url .= '&filter_saijo_certification=' . $this->input->get('filter_saijo_certification');
				} else {
					$filter_saijo_certification = '';
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
					'filter_technician_id'        => $filter_technician_id,
					'filter_technician'	     => $filter_technician,
					'filter_telephone'	     => $filter_telephone,
					'filter_email'	     => $filter_email,
					'filter_province'    => $filter_province,
					'filter_rating'    => $filter_rating,
					'filter_dealer_store'    => $filter_dealer_store,
					'filter_saijo_certification' => $filter_saijo_certification,
					'filter_status'           => $filter_status,
					'start'                  => ($page - 1) * 20,
					'limit'                  => 20
				);

				$this->data['technician_list'] = $this->user->getTechnicianList($filter_data);
				$technician_list_total = $this->user->getTechnicianListTotal($filter_data);

				$config = $this->config->item('pagination_config');
				$config['base_url'] = site_url('technician-list').'?'. $url .'&page=';
				$config['total_rows'] = (int)$technician_list_total;
				$config['per_page'] = 20;
				$config['num_links'] = $config['total_rows']/$config['per_page'];
				$config['cur_tag_open'] = '<li class="page-item active"><a href="'. site_url('technician-list').'?'. $url .'&page=' . $page .'" class="page-link">';
				$config["cur_page"] = $page;

				$start = ($page - 1) * $config['per_page'] + 1;

				if ($page + ($config['per_page'] * $page) - 1 > $config['total_rows']) {
					$end = $config['total_rows'];
				} else {
					$end = $page + ($config['per_page'] * $page) - 1;
				}

				$total_page = ceil((int)$technician_list_total/$config['per_page']);

				$this->data['result_count'] = "Showing ".$start." to ".$end." of ".$config['total_rows']." ( ". $total_page ." Pages)";

				$this->pagination->initialize($config);

				/*$this->data['service_list'] = $this->jobs->getServiceList();
				$this->data['status_list'] = $this->jobs->getStatusList();*/

				$this->data['dealer_list'] = $this->user->getDealerList();

				$this->data['name'] = $this->session->userdata('userSession')['name'];
				$this->data['lastname'] = $this->session->userdata('userSession')['lastname'];

				$this->data['user-type'] = $this->getSessionUserRole();

				$this->load->view('tpl_technician_list',$this->data);

			} else {
				redirect('financial-list');
			}

		} else {
			redirect('login');
		}
	}

	public function technician_form()
	{

		if ($this->is_login()) {

			if ($this->getSessionUserRole() == 1) {

				$technician_id = $this->uri->segment('3');

				$this->user->setUserID($technician_id);

				$technician = $this->user->getTechnicianInfo();

				$this->data['user_id'] = $technician_id;
				$this->data['name'] = $technician['name'];
				$this->data['lastname'] = $technician['lastname'];
				$this->data['telephone'] = $technician['telephone'];
				$this->data['address'] = $technician['address'];
				$this->data['district'] = $technician['district'];
				$this->data['province'] = $technician['province'];
				$this->data['postal_code'] = $technician['postal_code'];
				$this->data['latitude'] = $technician['latitude'];
				$this->data['longitude'] = $technician['longitude'];
				$this->data['rating'] = $technician['rating'];
				$this->data['profile_img'] = $technician['profile_img'];
				$this->data['saijo_certification'] = $technician['saijo_certification'];
				$this->data['status'] = $technician['status'];
				$this->data['user_name'] = $technician['user_name'];
				$this->data['latitude'] = $technician['latitude'];
				$this->data['longitude'] = $technician['longitude'];
				$this->data['dealer_store'] = $technician['dealer_store'];

				if (!$this->data['profile_img']) {
					$this->data['profile_img'] = 'user.png';
				}

				if (!$this->data['latitude']) {
					$this->data['latitude'] = 0;
				}

				if (!$this->data['longitude']) {
					$this->data['longitude'] = 0;
				}

				$this->data['method'] = $this->input->get('method');

				$this->data['user_role_list'] = $this->user->getUserRole();

				if ($this->input->post() && !is_blank($this->input->post())) {

					$name = $this->input->post('name');
					$lastname = $this->input->post('lastname');
					$telephone = $this->input->post('telephone');
					$address = $this->input->post('address');
					$district = $this->input->post('district');
					$province = $this->input->post('province');
					$postal_code = $this->input->post('postal_code');
					$latitude = $this->input->post('latitude');
					$longitude = $this->input->post('longitude');
					$saijo_certification = $this->input->post('saijo_certification');
					$user_name = $this->input->post('user_name');
					$status = $this->input->post('status');
					$dealer_store = $this->input->post('dealer_store');

					if (!is_blank($_FILES['profile_img']['name'])) {
						$profile_img = $this->upload_img($technician_id,'profile_img');

						$this->user->setProfileImg($profile_img);
					}

					if (!is_blank($this->input->post('password')) && $this->input->post('password') != '') {
						$password = $this->input->post('password');

						$this->user->setPassword($password);
					}

					$this->user->setName($name);
					$this->user->setLastname($lastname);
					$this->user->setTelephone($telephone);
					$this->user->setAddress($address);
					$this->user->setDistrict($district);
					$this->user->setProvince($province);
					$this->user->setPostalCode($postal_code);
					$this->user->setLatitude($latitude);
					$this->user->setLongitude($longitude);
					$this->user->setSaijoCertification($saijo_certification);
					$this->user->setUserName($user_name);
					$this->user->setStatus($status);
					$this->user->setDealerStore($dealer_store);

					$update = $this->user->updateTechnicianInfo();

					if (!$update) {
						echo '<script language="javascript">';
						echo 'alert("This account already exists. Please user anoter email.")';
						echo '</script>';
					} else {
						redirect('technician-list');
					}
				}

				$this->data['dealer_list'] = $this->user->getDealerList();

				$this->data['account_name'] = $this->session->userdata('userSession')['name'];
				$this->data['account_lastname'] = $this->session->userdata('userSession')['lastname'];

				$this->data['user-type'] = $this->getSessionUserRole();

				$this->load->view('tpl_technician_list_form',$this->data);

			} elseif ($this->getSessionUserRole() == 9) {
				redirect('job-list');
			} else {
				redirect('financial-list');
			}

		} else {
			redirect('login');
		}
	}

	public function technician_verify()
	{
		if ($this->is_login()) {

			if ($this->getSessionUserRole() == 1 || $this->getSessionUserRole() == 9) {

				$url = '';

				if (!is_blank($this->input->get('filter_technician_id'))) {
					$filter_technician_id = $this->input->get('filter_technician_id');

					$url .= '&filter_technician_id=' . $this->input->get('filter_technician_id');
				} else {
					$filter_technician_id = '';
				}

				if (!is_blank($this->input->get('filter_technician'))) {
					$filter_technician = $this->input->get('filter_technician');

					$url .= '&filter_technician=' . $this->input->get('filter_technician');
				} else {
					$filter_technician = '';
				}

				if (!is_blank($this->input->get('filter_saijo_certification'))) {
					$filter_saijo_certification = $this->input->get('filter_saijo_certification');

					$url .= '&filter_saijo_certification=' . $this->input->get('filter_saijo_certification');
				} else {
					$filter_saijo_certification = '';
				}

				if (!is_blank($this->input->get('filter_approved_date'))) {
					$filter_approved_date = $this->input->get('filter_approved_date');

					$url .= '&filter_approved_date=' . $this->input->get('filter_approved_date');
				} else {
					$filter_approved_date = '';
				}

				if (!is_blank($this->input->get('filter_expire_date'))) {
					$filter_expire_date = $this->input->get('filter_expire_date');

					$url .= '&filter_expire_date=' . $this->input->get('filter_expire_date');
				} else {
					$filter_expire_date = '';
				}

				if (!is_blank($this->input->get('filter_verify_status'))) {
					$filter_verify_status = $this->input->get('filter_verify_status');

					$url .= '&filter_verify_status=' . $this->input->get('filter_verify_status');
				} else {
					$filter_verify_status = '';
				}

				if (!is_blank($this->input->get('page'))) {
					$page = $this->input->get('page');
				} else {
					$page = 1;
				}

				$filter_data = array(
					'filter_technician_id'        => $filter_technician_id,
					'filter_technician'	     => $filter_technician,
					'filter_saijo_certification' => $filter_saijo_certification,
					'filter_approved_date' => $filter_approved_date,
					'filter_expire_date' => $filter_expire_date,
					'filter_verify_status'    => $filter_verify_status,
					'start'                  => ($page - 1) * 20,
					'limit'                  => 20
				);

				$this->data['technician_verify_list'] = $this->user->getTechnicianVerifyList($filter_data);
				$technician_verify_list_total = $this->user->getTechnicianVerifyListTotal($filter_data);

				$config = $this->config->item('pagination_config');
				$config['base_url'] = site_url('technician-verify').'?'. $url .'&page=';
				$config['total_rows'] = (int)$technician_verify_list_total;
				$config['per_page'] = 20;
				$config['num_links'] = $config['total_rows']/$config['per_page'];
				$config['cur_tag_open'] = '<li class="page-item active"><a href="'. site_url('technician-verify').'?'. $url .'&page=' . $page .'" class="page-link">';
				$config["cur_page"] = $page;

				$start = ($page - 1) * $config['per_page'] + 1;

				if ($page + ($config['per_page'] * $page) - 1 > $config['total_rows']) {
					$end = $config['total_rows'];
				} else {
					$end = $page + ($config['per_page'] * $page) - 1;
				}

				$total_page = ceil((int)$technician_verify_list_total/$config['per_page']);

				$this->data['result_count'] = "Showing ".$start." to ".$end." of ".$config['total_rows']." ( ". $total_page ." Pages)";

				$this->pagination->initialize($config);

				$this->data['name'] = $this->session->userdata('userSession')['name'];
				$this->data['lastname'] = $this->session->userdata('userSession')['lastname'];

				$this->data['user-type'] = $this->getSessionUserRole();

				$this->load->view('tpl_technician_verify',$this->data);

			} else {
				redirect('financial-list');
			}

		} else {
			redirect('login');
		}
	}

	public function technician_verify_form()
	{
		if ($this->is_login()) {

			if ($this->getSessionUserRole() == 1 || $this->getSessionUserRole() == 9) {

				$this->data['technician_list'] = $this->user->getUnverified();

				$method = $this->input->get('method');

				if ($method == 'edit') {

					$technician_id = $this->uri->segment('3');

					$this->user->setUserID($technician_id);

					$technician = $this->user->getVerifyInfo();

					$this->data['id'] = $technician_id;

					$this->data['tech_name'] = $technician['name'];
					$this->data['tech_lastname'] = $technician['lastname'];
					$this->data['saijo_certification'] = $technician['saijo_certification'];
					$this->data['approved_date'] = $technician['approved_date'];
					$this->data['expire_date'] = $technician['expire_date'];
					$this->data['status'] = $technician['status'];

				} else {

					$this->data['id'] = '';

					$this->data['tech_name'] = '';
					$this->data['tech_lastname'] = '';
					$this->data['saijo_certification'] = '';
					$this->data['approved_date'] = '';
					$this->data['expire_date'] = '';
					$this->data['status'] = 0;
				}

				if ($this->input->post()) {

					if ($method == 'edit') {
						$tech_id = $this->input->get('id');
					} else {
						$tech_id = $this->input->post('technician');
					}

					$saijo_certification = $this->input->post('saijo_certification');
					$status = $this->input->post('status');

					$this->user->setUserID($tech_id);
					$this->user->setSaijoCertification($saijo_certification);
					$this->user->setStatus($status);

					$approval_date = $this->input->post('approval_date');
					$app_date = str_replace('/', '-', $approval_date );
					$this->user->setApprovalDate(date("Y-m-d H:i", strtotime($app_date)));

					$expire_date = $this->input->post('expire_date');
					$exp_date = str_replace('/', '-', $expire_date );
					$this->user->setExpireDate(date("Y-m-d H:i", strtotime($exp_date)));

					if ($method == 'add') {
						$this->user->addVerify();

						redirect('technician-verify');
					} else {

						$this->user->updateVerify();

						redirect('technician-verify');
					}

				}

				$this->data['method'] = $method;

				$this->data['name'] = $this->session->userdata('userSession')['name'];
				$this->data['lastname'] = $this->session->userdata('userSession')['lastname'];

				$this->data['user-type'] = $this->getSessionUserRole();

				$this->load->view('tpl_technician_verify_form',$this->data);

			} else {
				redirect('financial-list');
			}

		} else {
			redirect('login');
		}
	}

	public function dealer_list()
	{
		if ($this->is_login()) {

			if ($this->getSessionUserRole() == 1 || $this->getSessionUserRole() == 9) {

				$url = '';

				if (!is_blank($this->input->get('filter_dealer_id'))) {
					$filter_dealer_id = $this->input->get('filter_dealer_id');

					$url .= '&filter_dealer_id=' . $this->input->get('filter_dealer_id');
				} else {
					$filter_dealer_id = '';
				}

				if (!is_blank($this->input->get('filter_dealer_store'))) {
					$filter_dealer_store = $this->input->get('filter_dealer_store');

					$url .= '&filter_dealer_store=' . $this->input->get('filter_dealer_store');
				} else {
					$filter_dealer_store = '';
				}

				if (!is_blank($this->input->get('page'))) {
					$page = $this->input->get('page');
				} else {
					$page = 1;
				}

				$filter_data = array(
					'filter_dealer_id'        => $filter_dealer_id,
					'filter_dealer_store'	     => $filter_dealer_store,
					'start'                  => ($page - 1) * 20,
					'limit'                  => 20
				);

				$this->data['dealer_list'] = $this->user->getDealerList($filter_data);
				$dealer_list_total = $this->user->getDealerListTotal($filter_data);

				$config = $this->config->item('pagination_config');
				$config['base_url'] = site_url('dealer-list').'?'. $url .'&page=';
				$config['total_rows'] = (int)$dealer_list_total;
				$config['per_page'] = 20;
				$config['num_links'] = $config['total_rows']/$config['per_page'];
				$config['cur_tag_open'] = '<li class="page-item active"><a href="'. site_url('dealer-list').'?'. $url .'&page=' . $page .'" class="page-link">';
				$config["cur_page"] = $page;

				$start = ($page - 1) * $config['per_page'] + 1;

				if ($page + ($config['per_page'] * $page) - 1 > $config['total_rows']) {
					$end = $config['total_rows'];
				} else {
					$end = $page + ($config['per_page'] * $page) - 1;
				}

				$total_page = ceil((int)$dealer_list_total/$config['per_page']);

				$this->data['result_count'] = "Showing ".$start." to ".$end." of ".$config['total_rows']." ( ". $total_page ." Pages)";

				$this->pagination->initialize($config);

				$this->data['account_name'] = $this->session->userdata('userSession')['name'];
				$this->data['account_lastname'] = $this->session->userdata('userSession')['lastname'];

				$this->data['user-type'] = $this->getSessionUserRole();

				$this->load->view('tpl_dealer_list',$this->data);

			} else {
				redirect('financial-list');
			}

		} else {
			redirect('login');
		}
	}

	public function dealer_form()
	{

		if ($this->is_login()) {

			if ($this->getSessionUserRole() == 1) {

				$store_id = $this->uri->segment('3');

				$this->user->setStoreID($store_id);

				$method	= $this->input->get('method');

				$this->data['dealer_id'] = $store_id;
				$this->data['method'] = $method;

				if ($method == 'delete') {
					$this->user->deleteDealerStore();

					redirect('dealer-list');
				} elseif ($method == 'edit') {
					$dealer = $this->user->getStoreInfo();

					$this->data['dealer_store'] = $dealer['dealer_store'];
				} else {
					$this->data['dealer_store'] = '';
				}

				if ($this->input->post() && !is_blank($this->input->post())) {

					$dealer_store = $this->input->post('dealer_store');

					$this->user->setDealerStore($dealer_store);

					if ($method == 'edit') {
						$msg = $this->user->updateDealerStore();

						if (!$msg) {
							echo '<script language="javascript">';
							echo 'alert("This dealer store already exists.")';
							echo '</script>';
						} else {
							redirect('dealer-list');
						}
					} elseif ($method == 'add') {
						$msg = $this->user->addDealerStore();

						if (!$msg) {
							echo '<script language="javascript">';
							echo 'alert("This dealer store already exists.")';
							echo '</script>';
						} else {
							redirect('dealer-list');
						}
						
					} else {
						redirect('dealer-list');
					}

					if ($msg) {
						redirect('dealer-list');
					}
					
				}

				$this->data['account_name'] = $this->session->userdata('userSession')['name'];
				$this->data['account_lastname'] = $this->session->userdata('userSession')['lastname'];

				$this->data['user-type'] = $this->getSessionUserRole();

				$this->load->view('tpl_dealer_form',$this->data);

			} else {
				redirect('financial-list');
			}

		} else {
			redirect('login');
		}
	}

	public function upload_img($id,$img) {

		$images = $_FILES[$img]['tmp_name'];
		$check_type = $_FILES[$img]['name'];
		$check_type_explode = explode('.', $check_type);

		$size = GetimageSize($images);

		if ($size[0] > 720) {
			$width = 720;
		} else {
			$width = $size[0];
		}

		$height = round($width*$size[1]/$size[0]);

		if ($check_type_explode[1] == 'PNG' || $check_type_explode[1] == 'png') {
			$images_orig = ImageCreateFromPNG($images);
		}
		else if ($check_type_explode[1] == 'GIF' || $check_type_explode[1] == 'gif') {
			$images_orig = ImageCreateFromGIF($images);
		}
		else{
			$images_orig = ImageCreateFromJPEG($images);
		}

		$photoX = ImagesX($images_orig);
		$photoY = ImagesY($images_orig);
		$images_fin = ImageCreateTrueColor($width, $height);
		ImageCopyResampled($images_fin, $images_orig, 0, 0, 0, 0, $width+1, $height+1, $photoX, $photoY);

		imagepng($images_fin, "application/controllers/v1/club/upload/profile_img/".$id.".png");

		ImageDestroy($images_orig);
		ImageDestroy($images_fin);

		$name = $id.".png";

		return $name;
	}
}
