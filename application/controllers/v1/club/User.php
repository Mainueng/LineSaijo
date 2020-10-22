<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';

class User extends REST_Controller
{

	public function __construct()
	{

		parent::__construct();

		$this->methods['index_get']['limit'] = 500;
		$this->methods['index_post']['limit'] = 100;
		$this->methods['technician_list_get']['limit'] = 100;
		$this->methods['password_post']['limit'] = 100;
		$this->methods['password_put']['limit'] = 100;
		$this->methods['check_account_validate_get']['limit'] = 500;
		$this->methods['check_saijo_certification_get']['limit'] = 500;

		$this->load->model('Auth_model', 'auth');
		$this->load->model('User_model','user');
		$this->load->model($this->technician_model, 'technician');

		$this->load->library('email');

		$this->load->library('Authorization_Token');

	}

	public function index_get()
	{
		/*** Technician Information ***/

		if ($this->is_authen()) {

			$token_info = $this->authorization_token->userData();

			$id = $token_info->id;

			if ($token_info->user_role_id == 3 || $token_info->user_role_id == 1) {

				if (isset($id) && !is_blank($id)) {
					$this->technician->setTechnicianId($id);
					$this->user->getUserID($id);
				}

				$check_info = $this->user->check_info_tech();

				$data = $this->technician->technicianInfo();

				if ($data !== false && !is_blank($data)) {

					$http_code = REST_Controller::HTTP_OK;
					$message = array(
						'status' => true,
						'code' => $http_code,
						'message' => 'Success',
						'data' => $data
					);
				} else {

					$http_code = REST_Controller::HTTP_BAD_REQUEST;
					$message = array(
						'status' => false,
						'code' => $http_code,
						'message' => "ไม่พบข้อมูลช่างเทคนิค",
						'data' => array()
					);
				}
			} else {
				$http_code = REST_Controller::HTTP_METHOD_NOT_ALLOWED;
				$message = array(
					'status' => false,
					'code' => $http_code,
					'message' => "You do not have permission to access.",
					'data' => array()
				);
			}

		} else {

			$http_code = REST_Controller::HTTP_REQUEST_TIMEOUT;
			$message = array(
				'status' => false,
				'code' => $http_code,
				'message' => $this->_authen_message,
				'data' => array()
			);
		}

		$this->response($message, $http_code);
	}

	public function index_post()
	{
		/*** Update Technician Information ***/

		if ($this->is_authen()) {

			$token_info = $this->authorization_token->userData();

			if ($token_info->user_role_id == 3 || $token_info->user_role_id == 1) {

				$id = $token_info->id;

				if (isset($id) && !is_blank($id)) {
					$this->technician->setTechnicianId($id);
					$this->user->getUserID($id);
				}

				$data = $this->security->xss_clean($_POST);

				$this->form_validation->set_rules('profile_img', 'Profile Image');
				$this->form_validation->set_rules('name', 'Name', 'trim');
				$this->form_validation->set_rules('lastname', 'Lastname', 'trim');
				$this->form_validation->set_rules('tel', 'Telephone', 'trim|min_length[9]|max_length[10]');
				$this->form_validation->set_rules('address', 'Address', 'trim');
				$this->form_validation->set_rules('district', 'District', 'trim');
				$this->form_validation->set_rules('province', 'Province', 'trim');
				$this->form_validation->set_rules('postal_code', 'Postal Code', 'trim');
				$this->form_validation->set_rules('latitude', 'Latiude', 'trim');
				$this->form_validation->set_rules('longitude', 'Longitude', 'trim');
				$this->form_validation->set_rules('saijo_certification', 'Saijo-Denki Certification', 'trim');

				if ($this->form_validation->run() == TRUE) {

					$name = $this->post('name');
					$lastname = $this->post('lastname');
					$tel = $this->post('tel');
					$address = $this->post('address');
					$district = $this->post('district');
					$province = $this->post('province');
					$postal_code = $this->post('postal_code');
					$latitude = $this->post('latitude');
					$longitude = $this->post('longitude');
					$saijo_certification = $this->post('saijo_certification');

					$profile = '';

					if (isset($_FILES['profile_img']['name'])) {
						$profile = $this->upload_img($id,'profile_img');
					}

					/*** Set Properties ***/
					if (isset($name)) {
						$this->technician->setFirstname($name);
					}
					if (isset($lastname)) {
						$this->technician->setLastname($lastname);
					}
					if (isset($tel)) {
						$this->technician->setTel($tel);
					}
					if (isset($latitude)) {
						$this->technician->setLatitude($latitude);
					}
					if (isset($longitude)) {
						$this->technician->setLongitude($longitude);
					}
					if (isset($address)) {
						$this->technician->setAddress($address);
					}
					if (isset($district)) {
						$this->technician->setDistrict($district);
					}
					if (isset($province)) {
						$this->technician->setProvice($province);
					}
					if (isset($postal_code)) {
						$this->technician->setPostalCode($postal_code);
					}
					if (isset($saijo_certification)) {
						$this->technician->setSaijoCertification($saijo_certification);
					}
					if (isset($profile)) {
						$this->technician->setProfile($profile);
					}            

					$check_info = $this->user->check_info_tech();

					$chk = $this->technician->technicianInfo();

					if ($this->form_validation->run() == TRUE) {

						if ($chk) {
							$data = $this->technician->updateTechnicianInfo();

							if (!empty($data) && $data !== false) {
								$http_code = REST_Controller::HTTP_OK;
								$message = array(
									'status' => true,
									'code' => $http_code,
									'message' => "Update technician information successful.",
									'data' => array()
								);

							} else {
								$http_code = REST_Controller::HTTP_BAD_REQUEST;
								$message = array(
									'status' => false,
									'code' => $http_code,
									'message' => "อัพเดทข้อมูลช่างเทคนิคล้มเหลว",
									'data' => array()
								);

							}

						} else {
							$http_code = REST_Controller::HTTP_BAD_REQUEST;
							$message = array(
								'status' => false,
								'code' => $http_code,
								'message' => "ไม่พบข้อมูลช่างเทคนิค",
								'data' => array()
							);

						}

					} else {
						$http_code = REST_Controller::HTTP_BAD_REQUEST;
						$message = array(
							'status' => false,
							'code' => $http_code,
							'message' => trim(strip_tags(validation_errors(),'\n')),
							'data' => array()
						);
					}

				} else {

					$http_code = REST_Controller::HTTP_BAD_REQUEST;
					$message = array(
						'status' => false,
						'code' => $http_code,
						'message' => trim(strip_tags(validation_errors(),'\n')),
						'data' => array()
					);
				}

			} else {
				$http_code = REST_Controller::HTTP_METHOD_NOT_ALLOWED;
				$message = array(
					'status' => false,
					'code' => $http_code,
					'message' => "You do not have permission to access.",
					'data' => array()
				);
			} 

		} else {

			$http_code = REST_Controller::HTTP_REQUEST_TIMEOUT;
			$message = array(
				'status' => false,
				'code' => $http_code,
				'message' => $this->_authen_message,
				'data' => array()
			);
		}

		$this->response($message, $http_code);
	}

	public function technician_list_get()
	{

		if ($this->is_authen()) {

			$token_info = $this->authorization_token->userData();

			if ($token_info->user_role_id == 3 || $token_info->user_role_id == 1) {

				$data = $this->technician->technicianList();

				if ($data !== false && !is_blank($data)) {

					$http_code = REST_Controller::HTTP_OK;
					$message = array(
						'status' => true,
						'code' => $http_code,
						'message' => "Success",
						'data' => $data
					);
				} else {

					$http_code = REST_Controller::HTTP_BAD_REQUEST;
					$message = array(
						'status' => false,
						'code' => $http_code,
						'message' => "ไม่พบข้อมูลช่างเทคนิค",
						'data' => array()
					);
				}

			} else {
				$http_code = REST_Controller::HTTP_METHOD_NOT_ALLOWED;
				$message = array(
					'status' => false,
					'code' => $http_code,
					'message' => "You do not have permission to access.",
					'data' => array()
				);
			} 

		} else {

			$http_code = REST_Controller::HTTP_REQUEST_TIMEOUT;
			$message = array(
				'status' => false,
				'code' => $http_code,
				'message' => $this->_authen_message,
				'data' => array()
			);
		}

		$this->response($message, $http_code);
	}

	public function forgot_password_post()
	{

		$data = $this->security->xss_clean($_POST);
		$this->form_validation->set_rules('email', 'Email', 'trim|required');

		if ($this->form_validation->run() == TRUE) {

			$to = $this->post('email');

			$this->auth->setEmail($to);

			$config = array();
			$config['useragent'] = 'saijo-denki.co.th';
			$config['protocol'] = 'smtp';
			$config['smtp_host'] = 'mail.saijo-denki.co.th';
			$config['smtp_user'] = 'saijoapp@saijo-denki.co.th';
			$config['smtp_pass'] = 'WjiHk2Jz';
			$config['smtp_port'] = '465';
			$config['smtp_crypto'] = 'ssl';
			$config['charset'] = 'utf-8';
			$this->email->initialize($config);

			$password = $this->random_password();

			$this->email->from('saijoapp@saijo-denki.co.th', 'Saijo Denki');
			$this->email->to($to);
			$this->email->subject('Password reset request');
			$this->email->message('A new password was requested for Saijo Denki customer account. New password : '.$password);

			$chk = $this->auth->checkEmail();

			if ($chk !== false && !is_blank($chk)) {

				$this->auth->setPassword($password);

				$data = $this->auth->forgotPassword_club();

				if ($data !== false && !is_blank($data)) {

					$this->email->send();

					$http_code = REST_Controller::HTTP_OK;
					$message = array(
						'status' => true,
						'code' => $http_code,
						'message' => "Success",
						'data' => array()
					);

				} else {

					$http_code = REST_Controller::HTTP_BAD_REQUEST;
					$message = array(
						'status' => false,
						'code' => $http_code,
						'message' => "เปลี่ยนรหัสผ่านไม่สำเร็จ",
						'data' => array()
					);
				}

			} else {

				$http_code = REST_Controller::HTTP_BAD_REQUEST;
				$message = array(
					'status' => false,
					'code' => $http_code,
					'message' => "ไม่พบอีเมล",
					'data' => array()
				);
			}

		} else {
			$http_code = REST_Controller::HTTP_REQUEST_TIMEOUT;
			$message = array(
				'status' => false,
				'code' => $http_code,
				'message' => trim(strip_tags(validation_errors(),'\n')),
				'data' => array()
			);
		}

		$this->response($message, $http_code);
	}

	public function password_put()
	{
		/**** Change password ****/
		if ($this->is_authen()) {

			$token_info = $this->authorization_token->userData();

			$id = $token_info->id;
			$email = $token_info->email;

			if (isset($id) && !is_blank($id)) {
				$this->auth->setUserID($id);
			}

			if (isset($email) && !is_blank($email)) {
				$this->auth->setEmail($email);
			}

			$password = $this->put('password');

			$this->auth->setPassword($password);

			$data = $this->auth->changePassword();

			if ($data !== false && !is_blank($data)) {

				$http_code = REST_Controller::HTTP_OK;
				$message = array(
					'status' => true,
					'code' => $http_code,
					'message' => "Success",
					'data' => array()
				);
			} else {

				$http_code = REST_Controller::HTTP_BAD_REQUEST;
				$message = array(
					'status' => false,
					'code' => $http_code,
					'message' => "อัปเดตรหัสผ่านล้มเหลว",
					'data' => array()
				);
			}

		} else {

			$http_code = REST_Controller::HTTP_REQUEST_TIMEOUT;
			$message = array(
				'status' => false,
				'code' => $http_code,
				'message' => $this->_authen_message,
				'data' => array()
			);
		}

		$this->response($message, $http_code);
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

	public function random_password()
	{
		$chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
		$ret_str = "";
		$num = strlen($chars);
		for($i = 0; $i < 9; $i++)
		{
			$ret_str.= $chars[rand()%$num];
			$ret_str.=""; 
		}
		return $ret_str; 
	}

	public function check_account_validate_get()
	{
		if ($this->is_authen()) {

			$token_info = $this->authorization_token->userData();

			$id = $token_info->id;

			if ($token_info->user_role_id == 3 || $token_info->user_role_id == 1) {

				if (isset($id) && !is_blank($id)) {
					$this->user->getUserID($id);
				}

				$data = $this->user->check_club_account_validate();

				if ($data !== false && !is_blank($data)) {

					$http_code = REST_Controller::HTTP_OK;
					$message = array(
						'status' => true,
						'code' => $http_code,
						'message' => 'Success',
						'data' => array($data)
					);
				} else {

					$http_code = REST_Controller::HTTP_BAD_REQUEST;
					$message = array(
						'status' => false,
						'code' => $http_code,
						'message' => "ไม่พบข้อมูล",
						'data' => array()
					);
				}

			} else {
				$http_code = REST_Controller::HTTP_METHOD_NOT_ALLOWED;
				$message = array(
					'status' => false,
					'code' => $http_code,
					'message' => "You do not have permission to access.",
					'data' => array()
				);
			}

		} else {

			$http_code = REST_Controller::HTTP_REQUEST_TIMEOUT;
			$message = array(
				'status' => false,
				'code' => $http_code,
				'message' => $this->_authen_message,
				'data' => array()
			);
		}

		$this->response($message, $http_code);
	}

	public function check_saijo_certification_get()
	{
		if ($this->is_authen()) {

			$token_info = $this->authorization_token->userData();

			$id = $token_info->id;

			if ($token_info->user_role_id == 3 || $token_info->user_role_id == 1) {

				if (isset($id) && !is_blank($id)) {
					$this->user->getUserID($id);
				}

				$data = $this->user->check_saijo_certification();

				if ($data !== false && !is_blank($data)) {

					$http_code = REST_Controller::HTTP_OK;
					$message = array(
						'status' => true,
						'code' => $http_code,
						'message' => 'Success',
						'data' => array(array(
							'verified' => $data['verified']
						))
					);
				} else {

					$http_code = REST_Controller::HTTP_BAD_REQUEST;
					$message = array(
						'status' => false,
						'code' => $http_code,
						'message' => "ไม่พบข้อมูล",
						'data' => array()
					);
				}

			} else {
				$http_code = REST_Controller::HTTP_METHOD_NOT_ALLOWED;
				$message = array(
					'status' => false,
					'code' => $http_code,
					'message' => "You do not have permission to access.",
					'data' => array()
				);
			}

		} else {

			$http_code = REST_Controller::HTTP_REQUEST_TIMEOUT;
			$message = array(
				'status' => false,
				'code' => $http_code,
				'message' => $this->_authen_message,
				'data' => array()
			);
		}

		$this->response($message, $http_code);
	}

	public function certification_info_get()
	{
		if ($this->is_authen()) {

			$token_info = $this->authorization_token->userData();

			$id = $token_info->id;

			if ($token_info->user_role_id == 3 || $token_info->user_role_id == 1 || $token_info->user_role_id == 2) {

				if (isset($id) && !is_blank($id)) {
					$this->user->getUserID($id);
				}

				$data = $this->user->saijo_certification();

				if ($data !== false && !is_blank($data)) {

					$http_code = REST_Controller::HTTP_OK;
					$message = array(
						'status' => true,
						'code' => $http_code,
						'message' => 'Success',
						'data' => $data
					);
				} else {

					$http_code = REST_Controller::HTTP_BAD_REQUEST;
					$message = array(
						'status' => false,
						'code' => $http_code,
						'message' => "ไม่พบข้อมูล",
						'data' => array()
					);
				}

			} else {
				$http_code = REST_Controller::HTTP_METHOD_NOT_ALLOWED;
				$message = array(
					'status' => false,
					'code' => $http_code,
					'message' => "You do not have permission to access.",
					'data' => array()
				);
			}

		} else {

			$http_code = REST_Controller::HTTP_REQUEST_TIMEOUT;
			$message = array(
				'status' => false,
				'code' => $http_code,
				'message' => $this->_authen_message,
				'data' => array()
			);
		}

		$this->response($message, $http_code);
	}

}