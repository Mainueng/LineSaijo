<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';


class Jobs extends REST_Controller
{

	public function __construct()
	{
		parent::__construct();

		$this->load->library('Authorization_Token');

		$this->methods['job_create_post']['limit'] = 500;
		$this->methods['upload_img']['limit'] = 500;
		$this->methods['job_area_tech_post']['limit'] = 500;
		$this->methods['job_select_tech_post']['limit'] = 500;
		$this->methods['job_cancel_put']['limit'] = 500;
		$this->methods['cancel_create_job_put']['limit'] = 500;
		$this->methods['tech_around_get']['limit'] = 500;
		$this->methods['tech_province_post']['limit'] = 500;
		$this->methods['job_info_get']['limit'] = 500;
		$this->methods['job_list_get']['limit'] = 500;
		$this->methods['history_get']['limit'] = 500;
		$this->methods['symptoms_get']['limit'] = 500;
		$this->methods['service_list_get']['limit'] = 500;
		$this->methods['cleaning_cost_list_get']['limit'] = 500;
		$this->methods['service_cost_history_post']['limit'] = 500;
		$this->methods['service_cost_history_get']['limit'] = 500;
		$this->methods['notify_operator_post']['limit'] = 500;

		$this->load->model($this->jobs_model, 'jobs');
		$this->load->model($this->notification_model, 'notification');
		$this->load->model('User_model', 'user');
		$this->load->model('Auth_model', 'auth');

	}

	public function job_create_post()
	{
		if ($this->is_authen()) {

			$token_info = $this->authorization_token->userData();

			if ($token_info->user_role_id == 4 || $token_info->user_role_id == 1 || $token_info->user_role_id == 2) {

				$cus_id = $token_info->id;

				if (isset($cus_id) && !is_blank($cus_id)) {
					$this->jobs->setCustomerId($cus_id);
				}

				$data = $this->security->xss_clean($_POST);

				$this->form_validation->set_rules('tech_id', 'Tech ID', 'trim');
				$this->form_validation->set_rules('serial', 'Serial', 'trim|required');
				$this->form_validation->set_rules('symptom', 'Symptom', 'trim');
				$this->form_validation->set_rules('appointment_datetime', 'Appointment_datetime', 'required');
				$this->form_validation->set_rules('telephone', 'Telephone', 'required|min_length[9]|max_length[10]');
				$this->form_validation->set_rules('latitude', 'Latitude', 'trim|required');
				$this->form_validation->set_rules('longitude', 'Longitude', 'trim|required');
				$this->form_validation->set_rules('type', 'type', 'trim|required');

				if ($this->form_validation->run() == TRUE) {

					$tech_id = $this->post('tech_id');
					$serial = $this->post('serial');
					$symptom = $this->post('symptom');
					$appointment_datetime = $this->post('appointment_datetime');
					$telephone = $this->post('telephone');
					$latitude = $this->post('latitude');
					$longitude = $this->post('longitude');
					$type_code = $this->post('type');
					$id = 0;

                    /*$cus_technician = $this->jobs->get_cus_tech();

                    if ($cus_technician) {
                        $id = $cus_technician;
                    }*/

                    if (isset($tech_id)) {
                    	$id = $tech_id;
                    }

                    $this->jobs->setTechId($id);

                    $this->jobs->setSerial($serial);

                    if ($symptom !== false && !is_blank($symptom)) {
                    	$this->jobs->setSymptom($symptom);
                    }

                    $this->jobs->setAppointment($appointment_datetime);
                    $this->jobs->setTelNumber($telephone);
                    $this->jobs->setLatitude($latitude);
                    $this->jobs->setLongitude($longitude);
                    $this->jobs->setTypeCode($type_code);

                    $check_distant = false;

                    if ($id != 0) {
                    	$check_distant = $this->jobs->check_distant($id);

                    	if ($check_distant) {
                    		$data = $this->jobs->create_job();
                    	}

                    } else {
                    	$data = $this->jobs->create_job();
                    }

                    if ($data !== false && !is_blank($data)) {

                        $job_id = $data;
                        $this->jobs->setJobId($data);

                        if (isset($_FILES['img_1']['name'])) {
                            $img_1 = $this->upload_img($job_id."_1",'img_1');

                            $this->jobs->setImg1($img_1);
                        }

                        if (isset($_FILES['img_2']['name'])) {
                            $img_2 = $this->upload_img($job_id."_2",'img_2');

                            $this->jobs->setImg2($img_2);
                        }

                        if (isset($_FILES['img_3']['name'])) {
                            $img_3 = $this->upload_img($job_id."_3",'img_3');

                            $this->jobs->setImg3($img_3);
                        }

                        if (!is_blank($_FILES)) {
                            $this->jobs->addImage();
                        }

                        if ($id != 0) {

                            $fcm_tokens = $this->jobs->get_fcmToken($id);

                            $error = 0;
                            $notify = 0;

                            if ($fcm_tokens && $check_distant) {

                                foreach ($fcm_tokens as $row) {
                                    $fcm_token = $row['device_id'];

                                    $count = $this->jobs->notification_count();

                                    if (!$this->FCM($fcm_token,$data,false,$count)){
                                        $error++;
                                    }

                                    $notify++;
                                }

                                $total = $notify - $error;

                                $http_code = REST_Controller::HTTP_OK;
                                $message = array(
                                    'status' => true,
                                    'code' => $http_code,
                                    'message' => "Success",
                                    'data' => array(array(
                                      'notify' => $total,
                                      'job_id' => $data
                                  ))
                                );
                            } else {
                                $http_code = REST_Controller::HTTP_BAD_REQUEST;
                                $message = array(
                                  'status' => false,
                                  'code' => $http_code,
                                  'title' => 'Warning booking!',
                                  'message' => 'Technician out of area.',
                                  'data' => array()
                              );
                            }

                        } else {

                            $my_tech = $this->jobs->myTech();

                            if ($my_tech) {
                                $this->jobs->setTechId($my_tech);
                            } else {
                                $this->jobs->setTechId(0);
                            }

                            $tokens = $this->jobs->get_fcmTokens();
                            $techID = $this->jobs->tech_list();

                            $error = 0;
                            $notify = 0;

                            if ($tokens !== false && !is_blank($tokens)) {

                                foreach ($techID as $techs) {
                                    $this->notification->set_notification($techs['id'],$data,$type_code);
                                }

                                foreach ($tokens as $row) {

                                    $this->jobs->setTechId($row['id']);

                                    $count = $this->jobs->notification_count();

                                    if (!$this->FCM($row['device_id'],$data,false,$count)) {
                                        $error++;
                                    }

                                    $notify++;

                                }

                                $total = $notify - $error;

                                $http_code = REST_Controller::HTTP_OK;
                                $message = array(
                                    'status' => true,
                                    'code' => $http_code,
                                    'message' => 'Success',
                                    'data' => array(array(
                                        'notify' => $total,
                                        'job_id' => $data
                                    ))
                                );

                            } else {
                                $http_code = REST_Controller::HTTP_OK;
                                $message = array(
                                    'status' => true,
                                    'code' => $http_code,
                                    'message' => 'There are no technicians in your area.',
                                    'data' => array(array(
                                        'notify' => 0,
                                        'job_id' => $data
                                    ))
                                );
                            }
                        }

                    } else {
                        $http_code = REST_Controller::HTTP_BAD_REQUEST;
                        $message = array(
                            'status' => false,
                            'code' => $http_code,
                            'message' => 'Create job fail.',
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

    public function job_area_tech_post()
    {

       /* Send push message to technician in the area.*/
       if ($this->is_authen()) {

          $token_info = $this->authorization_token->userData();

          if ($token_info->user_role_id == 4 || $token_info->user_role_id == 1 || $token_info->user_role_id == 2) {

             $id = $token_info->id;

             if (isset($id) && !is_blank($id)) {
                $this->jobs->setCustomerId($id);
            } else {
                $this->jobs->setCustomerId(0);
            }

            $data = $this->jobs->get_last_job();

            $my_tech = $this->jobs->myTech();

            if ($my_tech) {
                $this->jobs->setTechId($my_tech);
            } else {
                $this->jobs->setTechId(0);
            }

            $this->jobs->setJobId($data['id']);

            $unaccepted = $this->jobs->unaccepted_job();

            $type_code = $this->jobs->job_type();

            $this->jobs->setLatitude($data['latitude']);
            $this->jobs->setLongitude($data['longitude']);

            if ($data !== false && !is_blank($data) && $unaccepted !== false && !is_blank($unaccepted)) {

                $tokens = $this->jobs->get_fcmTokens(30);
                        //$techID = $this->jobs->tech_list();

                $error = 0;
                $notify = 0;
                $tech_id = 0;

                if ($tokens) {
                            /*foreach ($techID as $techs) {

                                $check_notification = $this->notification->check_notification($techs['id'],$data['id']);

                                if ($check_notification) {
                                    $this->notification->set_notification($techs['id'],$data['id'],$type_code);
                                }
                            }*/

                            foreach ($tokens as $row) {

                            	$this->jobs->setTechId($row['id']);

                            	$check_notification = $this->notification->check_notification($row['id'],$data['id']);

                            	if ($check_notification) {

                            		$this->notification->set_notification($row['id'],$data['id'],$type_code);

                            		$tech_id = $row['id'];

                            	}

                            	$count = $this->jobs->notification_count();

                            	if ($row['id'] == $tech_id) {
                            		if (!$this->FCM($row['device_id'],$data['id'],false,$count)) {
                            			$error++;
                            		}

                            		$notify++;
                            	}
                            }

                            $total = $notify - $error;

                            $http_code = REST_Controller::HTTP_OK;
                            $message = array(
                            	'status' => true,
                            	'code' => $http_code,
                            	'message' => 'Success',
                            	'data' => array(array(
                            		'notify' => $total,
                            		'job_id' => intval($data['id'])
                            	))
                            );
                        } else {

                        	$http_code = REST_Controller::HTTP_OK;
                        	$message = array(
                        		'status' => true,
                        		'code' => $http_code,
                        		'message' => 'Success',
                        		'data' => array(array(
                        			'notify' => 0,
                        			'job_id' => intval($data['id'])
                        		))
                        	);
                        }

                    } else {
                    	$http_code = REST_Controller::HTTP_BAD_REQUEST;
                    	$message = array(
                    		'status' => false,
                    		'code' => $http_code,
                    		'message' => 'Job not found.',
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
            # code...
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

        public function job_select_tech_post()
        {
        	if ($this->is_authen()) {

        		$token_info = $this->authorization_token->userData();

        		if ($token_info->user_role_id == 4 || $token_info->user_role_id == 1 || $token_info->user_role_id == 2) {

        			$id = $token_info->id;

        			$tech_id = $this->get('tech_id');

        			if (isset($id) && !is_blank($id)) {
        				$this->jobs->setCustomerId($id);
        			} else {
        				$this->jobs->setCustomerId(0);
        			}

        			$data = $this->jobs->get_last_job();

        			$this->jobs->setJobId($data['id']);

        			$type_code = $this->jobs->job_type();

        			if ($data !== false && !is_blank($data)) {

        				$fcm_tokens = $this->jobs->get_fcmToken($tech_id);
        				$error = 0;
        				$notify = 0;

        				if ($fcm_tokens) {

        					foreach ($techID as $techs) {
        						$this->notification->set_notification($techs['id'],$data['id'],$type_code);
        					}

        					foreach ($fcm_tokens as $row) {
        						$fcm_token = $row['device_id'];

        						$this->jobs->setTechId($tech_id);

        						$count = $this->jobs->notification_count();

        						if (!$this->FCM($fcm_token,$data,false,$count)){
        							$error++;
        						}

        						$notify++;
        					}

        					$total = $notify - $error;

        					$techID = $this->jobs->tech_list();

        					/*if ($error == 0){*/
        						$http_code = REST_Controller::HTTP_OK;
        						$message = array(
        							'status' => true,
        							'code' => $http_code,
        							'message' => "Success",
        							'data' => array(array(
        								'notify' => $total,
        								'job_id' => intval($data['id'])
        							))
        						);

                        /*} else {

                            $http_code = REST_Controller::HTTP_OK;
                            $message = array(
                                'status' => false,
                                'code' => $http_code,
                                'message' => 'Unable to notify '. $error .' technician(s)',
                                'data' => array()
                            );
                        }*/
                    } else {
                    	$http_code = REST_Controller::HTTP_BAD_REQUEST;
                    	$message = array(
                    		'status' => false,
                    		'code' => $http_code,
                    		'message' => 'Technicians not found.',
                    		'data' => array()
                    	);
                    }

                } else {
                	$http_code = REST_Controller::HTTP_BAD_REQUEST;
                	$message = array(
                		'status' => false,
                		'code' => $http_code,
                		'message' => 'Job not found',
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
            # code...
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

    public function job_cancel_put()
    {
    	if ($this->is_authen()) {

    		$token_info = $this->authorization_token->userData();

    		if ($token_info->user_role_id == 4 || $token_info->user_role_id == 1 || $token_info->user_role_id == 2) {

    			$job_id = $this->get('id');

    			if (isset($job_id) && !is_blank($job_id)) {
    				$this->jobs->setJobId($job_id);
    			}

    			$techID = $this->jobs->getTechID();

    			$data = $this->jobs->cancel_job();

    			$tokens = $this->user->getOperatorToken();

    			if ($tokens) {

    				$message = "ยกเลิกการนัดหมาย  - Job ID ".$job_id;
    				$url = "job-list/form/".$job_id."?&method=edit";

    				foreach ($tokens as $row) {

    					$this->my_libraies->send_push_noti($row['token'],$message,$url);

    				}
    			}

    			if ($data !== false && !is_blank($data) && $techID !== false && !is_blank($techID)) {

    				$fcm_tokens = $this->jobs->get_fcmToken($techID);

    				$this->jobs->setTechId($techID);

    				if ($fcm_tokens) {

    					$error = 0;

    					foreach ($fcm_tokens as $row) {
    						$fcm_token = $row['device_id'];

    						$this->jobs->setTechId($techID);

    						$count = $this->jobs->notification_count();

    						if (!$this->FCM($fcm_token,$job_id,true,$count)){
    							$error++;
    						}
    					}

    					/*if ($error == 0){*/
    						$http_code = REST_Controller::HTTP_OK;
    						$message = array(
    							'status' => true,
    							'code' => $http_code,
    							'message' => "Success",
    							'data' => array()
    						);

                        /*} else {

                            $http_code = REST_Controller::HTTP_OK;
                            $message = array(
                                'status' => false,
                                'code' => $http_code,
                                'message' => 'Unable to notify '. $error .' technician(s)',
                                'data' => array()
                            );
                        }*/
                    } else {
                    	$http_code = REST_Controller::HTTP_BAD_REQUEST;
                    	$message = array(
                    		'status' => false,
                    		'code' => $http_code,
                    		'message' => 'Technicians not found.',
                    		'data' => array()
                    	);
                    }

                } else {

                	$this->jobs->uncancled_job();

                	$http_code = REST_Controller::HTTP_BAD_REQUEST;
                	$message = array(
                		'status' => false,
                		'code' => $http_code,
                		'message' => "Job cannot be canceled because the appointment time is less than 1 day.",
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
            # code...
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

    public function cancel_create_job_put()
    {
    	if ($this->is_authen()) {

    		$token_info = $this->authorization_token->userData();

    		if ($token_info->user_role_id == 4 || $token_info->user_role_id == 1 || $token_info->user_role_id == 2) {

    			$job_id = $this->get('id');

    			if (isset($job_id) && !is_blank($job_id)) {
    				$this->jobs->setJobId($job_id);
    			}

    			$data = $this->jobs->cancel_create_job();

    			if ($data !== false && !is_blank($data)) {

    				$http_code = REST_Controller::HTTP_OK;
    				$message = array(
    					'status' => true,
    					'code' => $http_code,
    					'message' => "Success",
    					'data' => array()
    				);

    			} else {

    				$this->jobs->uncancled_job();

    				$http_code = REST_Controller::HTTP_BAD_REQUEST;
    				$message = array(
    					'status' => false,
    					'code' => $http_code,
    					'message' => "Job cannot be canceled because the appointment time is less than 1 day.",
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
            # code...
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

    public function tech_around_get()
    {
    	if ($this->is_authen()) {

    		$token_info = $this->authorization_token->userData();

    		if ($token_info->user_role_id == 4 || $token_info->user_role_id == 1 || $token_info->user_role_id == 2) {

    			$id = $token_info->id;

    			if (isset($id) && !is_blank($id)) {
    				$this->jobs->setCustomerId($id);
    			} else {
    				$this->jobs->setCustomerId(0);
    			}

    			$data = $this->jobs->get_last_job();

    			if ($data !== false && !is_blank($data)) {

    				$this->jobs->setLatitude($data['latitude']);
    				$this->jobs->setLongitude($data['longitude']);

    				$tech_list = $this->jobs->get_tech_around();

    				$http_code = REST_Controller::HTTP_OK;
    				$message = array(
    					'status' => true,
    					'code' => $http_code,
    					'message' => "Success",
    					'data' => $tech_list
    				);

    			} else {
    				$http_code = REST_Controller::HTTP_BAD_REQUEST;
    				$message = array(
    					'status' => false,
    					'code' => $http_code,
    					'message' => 'Technician not found.',
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
            # code...
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

    public function tech_province_post()
    {
    	if ($this->is_authen()) {

    		$token_info = $this->authorization_token->userData();

    		if ($token_info->user_role_id == 4 || $token_info->user_role_id == 1 || $token_info->user_role_id == 2) {

    			$data = $this->security->xss_clean($_POST);

    			$this->form_validation->set_rules('province', 'Province', 'trim|required');

    			if ($this->form_validation->run() == TRUE) {

    				$province = $this->post('province');

    				$this->jobs->setProvince($province);

    				$data = $this->jobs->get_tech_province();

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
    						'message' => 'Technician not found.',
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
            # code...
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

    public function job_info_get()
    {
    	if ($this->is_authen()) {

    		$token_info = $this->authorization_token->userData();

    		if ($token_info->user_role_id == 4 || $token_info->user_role_id == 1 || $token_info->user_role_id == 2) {

    			$job_id = $this->get('id');

    			if (isset($job_id) && !is_blank($job_id)) {
    				$this->jobs->setJobId($job_id);
    			}

    			$id =  $token_info->id;

    			if (isset($id) && !is_blank($id)) {
    				$this->jobs->setCustomerId($id);
    				$this->user->getUserID($id);
    			}

    			$location = $this->user->location();

    			if (isset($location['latitude']) && !is_blank($location['latitude'])) {
    				$this->jobs->setLatitude($location['latitude']);
    			}

    			if (isset($location['longitude']) && !is_blank($location['longitude'])) {
    				$this->jobs->setLongitude($location['longitude']);
    			}


    			$data = $this->jobs->job_info_core();

    			if ($data !== false && !is_blank($data)) {

    				/*foreach ($data as $datas) {
    					$tech_id = $datas['tech_id'];
    				}*/

                    /*if ($tech_id == 0) {
                        $http_code = REST_Controller::HTTP_BAD_REQUEST;
                        $message = array(
                            'status' => false,
                            'code' => $http_code,
                            'message' => 'Job not found',
                            'data' => array()
                        );
                    } else {*/
                    	$http_code = REST_Controller::HTTP_OK;
                    	$message = array(
                    		'status' => true,
                    		'code' => $http_code,
                    		'message' => "Success",
                    		'data' => $data
                    	);
                    	/*}*/

                    } else {

                    	$http_code = REST_Controller::HTTP_BAD_REQUEST;
                    	$message = array(
                    		'status' => false,
                    		'code' => $http_code,
                    		'message' => 'Job no found',
                    		'data' => array()
                    	);
                    }

                } else {
            # code...
                	$http_code = REST_Controller::HTTP_METHOD_NOT_ALLOWED;
                	$message = array(
                		'status' => false,
                		'code' => $http_code,
                		'message' => "You do not have permission to access.",
                		'data' => array()
                	);
                }

                $this->response($message, $http_code);

            } else {
            # code...
            	$http_code = REST_Controller::HTTP_REQUEST_TIMEOUT;
            	$message = array(
            		'status' => false,
            		'code' => $http_code,
            		'message' => $this->_authen_message,
            		'data' => array()
            	);

            	$this->response($message, $http_code);
            }
        }

        public function job_list_get()
        {

        	if ($this->is_authen()) {

        		$token_info = $this->authorization_token->userData();

        		if ($token_info->user_role_id == 4 || $token_info->user_role_id == 1 || $token_info->user_role_id == 2) {

        			$id = $token_info->id;

        			if (isset($id) && !is_blank($id)) {
        				$this->jobs->setCustomerId($id);
        				$this->user->getUserID($id);
        			}

        			$location = $this->user->location();

        			if (isset($location['latitude']) && !is_blank($location['latitude'])) {
        				$this->jobs->setLatitude($location['latitude']);
        			}

        			if (isset($location['longitude']) && !is_blank($location['longitude'])) {
        				$this->jobs->setLongitude($location['longitude']);
        			}

        			$data = $this->jobs->jobs_list_core();

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
        					'message' => 'Job not found.',
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
            # code...
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

        public function history_get()
        {

        	if ($this->is_authen()) {

        		$token_info = $this->authorization_token->userData();

        		if ($token_info->user_role_id == 4 || $token_info->user_role_id == 1 || $token_info->user_role_id == 2) {

        			$id = $token_info->id;

        			if (isset($id) && !is_blank($id)) {
        				$this->jobs->setCustomerId($id);
        				$this->user->getUserID($id);
        			}

        			$location = $this->user->location();

        			if (isset($location['latitude']) && !is_blank($location['latitude'])) {
        				$this->jobs->setLatitude($location['latitude']);
        			}

        			if (isset($location['longitude']) && !is_blank($location['longitude'])) {
        				$this->jobs->setLongitude($location['longitude']);
        			}

        			$data = $this->jobs->jobs_history_list_core();

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
        					'message' => 'Job not found.',
        					'data' => array()
        				);
        			}

        		} else {
            # code...
        			$http_code = REST_Controller::HTTP_METHOD_NOT_ALLOWED;
        			$message = array(
        				'status' => false,
        				'code' => $http_code,
        				'message' => "You do not have permission to access.",
        				'data' => array()
        			);
        		}

        	} else {
            # code...
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

        public function symptoms_get()
        {

        	if ($this->is_authen()) {

        		$token_info = $this->authorization_token->userData();

        		if ($token_info->user_role_id == 4 || $token_info->user_role_id == 1 || $token_info->user_role_id == 2) {

        			$id = $token_info->id;

        			if (isset($id) && !is_blank($id)) {
        				$this->jobs->setCustomerId($id);
        			}

        			$data = $this->jobs->symptoms_list();

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
        					'message' => 'Symptoms list not found.',
        					'data' => array()
        				);
        			}

        		} else {
            # code...
        			$http_code = REST_Controller::HTTP_METHOD_NOT_ALLOWED;
        			$message = array(
        				'status' => false,
        				'code' => $http_code,
        				'message' => "You do not have permission to access.",
        				'data' => array()
        			);
        		}

        	} else {
            # code...
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

        public function service_list_get()
        {
        	if ($this->is_authen()) {

        		$token_info = $this->authorization_token->userData();

        		if ($token_info->user_role_id == 4 || $token_info->user_role_id == 1 || $token_info->user_role_id == 2) {

        			$data = $this->jobs->service_list();

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
        					'message' => 'Service not found.',
        					'data' => array()
        				);
        			}

        		} else {
            # code...
        			$http_code = REST_Controller::HTTP_METHOD_NOT_ALLOWED;
        			$message = array(
        				'status' => false,
        				'code' => $http_code,
        				'message' => "You do not have permission to access.",
        				'data' => array()
        			);
        		}

        	} else {
            # code...
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

        public function cleaning_cost_list_get()
        {
        	if ($this->is_authen()) {

        		$token_info = $this->authorization_token->userData();

        		if ($token_info->user_role_id == 4 || $token_info->user_role_id == 2 || $token_info->user_role_id == 1) {

        			$btu = $this->get('btu');

        			$this->jobs->setBTU($btu);

        			$data = $this->jobs->getCleanigCostList();

        			if ($data !== false && !is_blank($data)) {

        				$http_code = REST_Controller::HTTP_OK;
        				$message = array(
        					'status' => true,
        					'code' => $http_code,
        					'message' => "Success",
        					'data' =>  $data
        				);

        			} else {
        				$http_code = REST_Controller::HTTP_BAD_REQUEST;
        				$message = array(
        					'status' => false,
        					'code' => $http_code,
        					'message' => "ไม่พบรายการราคา",
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

        public function service_cost_history_post()
        {
        	if ($this->is_authen()) {

        		$token_info = $this->authorization_token->userData();

        		if ($token_info->user_role_id == 4 || $token_info->user_role_id == 2 || $token_info->user_role_id == 1) {

        			$jobs_id = $this->get('id');

        			$this->jobs->setJobId($jobs_id);

        			$data = $this->post('data');

        			$this->jobs->setServiceFee($data);

        			$data = $this->jobs->setServiceHistory();

        			if ($data !== false && !is_blank($data)) {

        				$http_code = REST_Controller::HTTP_OK;
        				$message = array(
        					'status' => true,
        					'code' => $http_code,
        					'message' => "Success",
        					'data' =>  array()
        				);

        			} else {
        				$http_code = REST_Controller::HTTP_BAD_REQUEST;
        				$message = array(
        					'status' => false,
        					'code' => $http_code,
        					'message' => "ไม่พบรายการราคา",
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

    /*public function service_cost_history_get()
    {
        if ($this->is_authen()) {

            $token_info = $this->authorization_token->userData();

            if ($token_info->user_role_id == 4 || $token_info->user_role_id == 2 || $token_info->user_role_id == 1) {

                $jobs_id = $this->get('id');

                $this->jobs->setJobId($jobs_id);

                $data = $this->jobs->getCleanigCostList();

                if ($data !== false && !is_blank($data)) {

                    $http_code = REST_Controller::HTTP_OK;
                    $message = array(
                        'status' => true,
                        'code' => $http_code,
                        'message' => "Success",
                        'data' =>  $data
                    );

                } else {
                    $http_code = REST_Controller::HTTP_BAD_REQUEST;
                    $message = array(
                        'status' => false,
                        'code' => $http_code,
                        'message' => "ไม่พบรายการราคา",
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
    }*/

    public function upload_img_post()
    {

    	if ($this->is_authen()) {

    		$token_info = $this->authorization_token->userData();

    		if ($token_info->user_role_id == 4 || $token_info->user_role_id == 2 || $token_info->user_role_id == 1) {

                $data = false;
                $x = false;

                $serial = $this->post('serial');

                if (!is_blank($serial)) {
                    $x = true;
                }

                if (isset($_FILES['img_1']['name'])) {
                    $img_1 = $this->upload_img("1_1",'img_1');

                    $this->jobs->setImg1($img_1);
                }

                if (isset($_FILES['img_2']['name'])) {
                    $img_2 = $this->upload_img("1_2",'img_2');

                    $this->jobs->setImg2($img_2);
                }

                if (isset($_FILES['img_3']['name'])) {
                    $img_3 = $this->upload_img("1_3",'img_3');

                    $this->jobs->setImg3($img_3);
                }

                if (!is_blank($_FILES)) {
                    $data = true;//$this->jobs->addImage();
                }

                if ($data !== false && !is_blank($data) && $x) {

                    $http_code = REST_Controller::HTTP_OK;
                    $message = array(
                     'status' => true,
                     'code' => $http_code,
                     'message' => "Success",
                     'data' => array()
                 );

                } elseif (!$data && $x) {
                    $http_code = REST_Controller::HTTP_BAD_REQUEST;
                    $message = array(
                     'status' => false,
                     'code' => $http_code,
                     'message' => "NO Image!",
                     'data' => array()
                 );

                } else {
                    $http_code = REST_Controller::HTTP_BAD_REQUEST;
                    $message = array(
                     'status' => false,
                     'code' => $http_code,
                     'message' => "NO Image! And Serail",
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

  imagepng($images_fin, "application/controllers/v1/club/upload/problem_img/".$id.".png");

  ImageDestroy($images_orig);
  ImageDestroy($images_fin);

  $name = $id.".png";

  return $name;
}

public function FCM($token,$job_id,$cancel=false,$count)
{
 $url = "https://fcm.googleapis.com/fcm/send";
 $serverKey = 'AAAAzR3XYHg:APA91bFBshxZuOP2hX2bbM7Ar7V-CHfJepU6dv_q4_iyDm0E6f_04kOTVhsMqnfdSYB7A58RLanYaFR8NcZ6K4ryCMbb-IBZhimvbMDG0TfopgW4GY_drQBYRUcgzg0P6W28HiYNhaBs';
 $title = "Saijo Denki E-Service";

 if ($cancel) {
  $body = "ลูกค้ายกเลิกงาน. Job ID : " .$job_id;
} else {
  $body = "ลูกค้าส่งคำขอรับบริการมาถึงคุณ.";
}

$notification = array('title' =>$title , 'body' => $body, 'sound' => 'default', 'badge' => $count);
$data = array('job_id' => $job_id);
$arrayToSend = array('to' => $token, 'notification' => $notification,'priority'=>'high', 'data' => $data);
$json = json_encode($arrayToSend);
$headers = array();
$headers[] = 'Content-Type: application/json';
$headers[] = 'Authorization: key='. $serverKey;
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST,"POST");
curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER,$headers);

/*        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_POST,true);
        curl_setopt($ch,CURLOPT_HTTPHEADER,$headers);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$json);
*/
        $response = curl_exec($ch);

        if ($response === FALSE) {
        	return false;
        }

        curl_close($ch);

        $result = json_decode($response, true);

        return $result['success'];


    }

    public function notify_operator_post()
    {
    	if ($this->is_authen()) {

    		$token_info = $this->authorization_token->userData();

    		if ($token_info->user_role_id == 4 || $token_info->user_role_id == 1) {

    			$tokens = $this->user->getOperatorToken();

    			$job_id = $this->get('id');

    			if ($tokens) {

    				$message = "Job ID ".$job_id." - ไม่มีช่างรับงาน!";
    				$url = "job-list/form/".$job_id."?&method=edit";

    				foreach ($tokens as $row) {

    					$this->my_libraies->send_push_noti($row['token'],$message,$url);

    				}

    				$http_code = REST_Controller::HTTP_OK;
    				$message = array(
    					'status' => true,
    					'code' => $http_code,
    					'message' => "Success",
    					'data' =>  array()
    				);

    			} else {

    				$http_code = REST_Controller::HTTP_BAD_REQUEST;
    				$message = array(
    					'status' => false,
    					'code' => $http_code,
    					'message' => "ไม่พบ Token",
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

} // End of class
