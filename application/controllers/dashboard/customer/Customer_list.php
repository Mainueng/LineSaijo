<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customer_list extends MY_Controller {

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

				if (!is_blank($this->input->get('filter_customer_id'))) {
					$filter_customer_id = $this->input->get('filter_customer_id');

					$url .= '&filter_customer_id=' . $this->input->get('filter_customer_id');
				} else {
					$filter_customer_id = '';
				}

				if (!is_blank($this->input->get('filter_customer'))) {
					$filter_customer = $this->input->get('filter_customer');

					$url .= '&filter_customer=' . $this->input->get('filter_customer');
				} else {
					$filter_customer = '';
				}

				if (!is_blank($this->input->get('filter_email'))) {
					$filter_email = $this->input->get('filter_email');

					$url .= '&filter_email=' . $this->input->get('filter_email');
				} else {
					$filter_email = '';
				}

				if (!is_blank($this->input->get('filter_telephone'))) {
					$filter_telephone = $this->input->get('filter_telephone');

					$url .= '&filter_telephone=' . $this->input->get('filter_telephone');
				} else {
					$filter_telephone = '';
				}

				if (!is_blank($this->input->get('filter_province'))) {
					$filter_province = $this->input->get('filter_province');

					$url .= '&filter_province=' . $this->input->get('filter_province');
				} else {
					$filter_province = '';
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
					'filter_customer_id'        => $filter_customer_id,
					'filter_customer'	     => $filter_customer,
					'filter_email'	     => $filter_email,
					'filter_telephone'	     => $filter_telephone,
					'filter_province'    => $filter_province,
					'filter_status'           => $filter_status,
					'start'                  => ($page - 1) * 20,
					'limit'                  => 20
				);

				$this->data['customer_list'] = $this->user->getCustomerList($filter_data);
				$customer_list_total = $this->user->getCustomerListTotal($filter_data);

				$config = $this->config->item('pagination_config');
				$config['base_url'] = site_url('customer-list').'?'. $url .'&page=';
				$config['total_rows'] = (int)$customer_list_total;
				$config['per_page'] = 20;
				$config['num_links'] = $config['total_rows']/$config['per_page'];
				$config['cur_tag_open'] = '<li class="page-item active"><a href="'. site_url('customer-list').'?'. $url .'&page=' . $page .'" class="page-link">';
				$config["cur_page"] = $page;

				$start = ($page - 1) * $config['per_page'] + 1;

				if ($page + ($config['per_page'] * $page) - 1 > $config['total_rows']) {
					$end = $config['total_rows'];
				} else {
					$end = $page + ($config['per_page'] * $page) - 1;
				}

				$total_page = ceil((int)$customer_list_total/$config['per_page']);

				$this->data['result_count'] = "Showing ".$start." to ".$end." of ".$config['total_rows']." ( ". $total_page ." Pages)";

				$this->pagination->initialize($config);

				$this->data['name'] = $this->session->userdata('userSession')['name'];
				$this->data['lastname'] = $this->session->userdata('userSession')['lastname'];

				$this->data['user-type'] = $this->getSessionUserRole();

				$this->load->view('tpl_customer_list',$this->data);

			} else {
				redirect('financial-list');
			}

		} else {
			redirect('login');
		}
	}

	public function customer_form()
	{

		if ($this->is_login()) {

			if ($this->getSessionUserRole() == 1) {

				$customer_id = $this->uri->segment('3');

				$this->user->setUserID($customer_id);

				$customer = $this->user->getCustomerInfo();

				$this->data['user_id'] = $customer_id;
				$this->data['name'] = $customer['name'];
				$this->data['lastname'] = $customer['lastname'];
				$this->data['telephone'] = $customer['telephone'];
				$this->data['address'] = $customer['address'];
				$this->data['district'] = $customer['district'];
				$this->data['province'] = $customer['province'];
				$this->data['postal_code'] = $customer['postal_code'];
				$this->data['latitude'] = $customer['latitude'];
				$this->data['longitude'] = $customer['longitude'];
				$this->data['profile_img'] = $customer['profile_img'];
				$this->data['status'] = $customer['status'];
				$this->data['user_name'] = $customer['user_name'];
				$this->data['latitude'] = $customer['latitude'];
				$this->data['longitude'] = $customer['longitude'];

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
					$user_name = $this->input->post('user_name');
					$status = $this->input->post('status');

					if (!is_blank($_FILES['profile_img']['name'])) {
                        $profile_img = $this->upload_img($customer_id,'profile_img');

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
					$this->user->setUserName($user_name);
					$this->user->setStatus($status);

					$update = $this->user->updateCustomerInfo();

					if (!$update) {
						echo '<script language="javascript">';
						echo 'alert("This account already exists. Please user anoter email.")';
						echo '</script>';
					} else {
						redirect('customer-list');
					}
				}

				$this->data['account_name'] = $this->session->userdata('userSession')['name'];
				$this->data['account_lastname'] = $this->session->userdata('userSession')['lastname'];

				$this->data['user-type'] = $this->getSessionUserRole();

				$this->load->view('tpl_customer_list_form',$this->data);

			} elseif ($this->getSessionUserRole() == 9) {
				redirect('job-list');
			} else {
				redirect('customer-list');
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

        imagepng($images_fin, "application/controllers/v1/core/upload/profile_img/".$id.".png");

        ImageDestroy($images_orig);
        ImageDestroy($images_fin);

        $name = $id.".png";

        return $name;
    }
}
