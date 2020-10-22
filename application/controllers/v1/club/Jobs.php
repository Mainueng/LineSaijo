<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';


class Jobs extends REST_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->methods['index_get']['limit'] = 500;
        $this->methods['recommend_get']['limit'] = 500;
        $this->methods['history_get']['limit'] = 500;
        $this->methods['job_info_get']['limit'] = 500;
        $this->methods['job_accept_put']['limit'] = 500;
        $this->methods['job_cancel_put']['limit'] = 500;
        $this->methods['job_complete_put']['limit'] = 500;
        $this->methods['notification_get']['limit'] = 500;
        $this->methods['deny_job_delete']['limit'] = 500;
        $this->methods['status_log']['limit'] = 500;

        $this->load->model($this->jobs_model, 'jobs');
        $this->load->model('Auth_model', 'auth');
        $this->load->model('User_model', 'user');
        $this->load->model('Notification_model', 'notification');

        $this->load->library('Authorization_Token');

    }

    public function index_get()
    {

        if ($this->is_authen()) {

            $token_info = $this->authorization_token->userData();

            if ($token_info->user_role_id == 3 || $token_info->user_role_id == 1) {

                $id = $token_info->id;

                if (isset($id) && !is_blank($id)) {
                    $this->jobs->setTechId($id);
                    $this->user->getUserID($id);
                }

                $location = $this->user->location();

                if (isset($location['latitude']) && !is_blank($location['latitude'])) {
                    $this->jobs->setLatitude($location['latitude']);
                }

                if (isset($location['longitude']) && !is_blank($location['longitude'])) {
                    $this->jobs->setLongitude($location['longitude']);
                }

                $data = $this->jobs->jobs_list_club();

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

    public function recommend_get()
    {
        if ($this->is_authen()) {

            $token_info = $this->authorization_token->userData();

            if ($token_info->user_role_id == 3 || $token_info->user_role_id == 1) {

                $id = $token_info->id;

                if (isset($id) && !is_blank($id)) {
                    $this->user->getUserID($id);
                    $this->jobs->setTechId($id);
                }

                $location = $this->user->location();

                if (isset($location['latitude']) && !is_blank($location['latitude'])) {
                    $this->jobs->setLatitude($location['latitude']);
                }

                if (isset($location['longitude']) && !is_blank($location['longitude'])) {
                    $this->jobs->setLongitude($location['longitude']);
                }

                $data = $this->jobs->recommend_jobs_list();

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
                        'message' => 'ไม่พบงาน.',
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

            if ($token_info->user_role_id == 3 || $token_info->user_role_id == 1) {

                $id = $token_info->id;

                if (isset($id) && !is_blank($id)) {
                    $this->jobs->setTechId($id);
                    $this->user->getUserID($id);
                }

                $location = $this->user->location();

                if (isset($location['latitude']) && !is_blank($location['latitude'])) {
                    $this->jobs->setLatitude($location['latitude']);
                }

                if (isset($location['longitude']) && !is_blank($location['longitude'])) {
                    $this->jobs->setLongitude($location['longitude']);
                }

                $data = $this->jobs->jobs_history_list_club();

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
                        'message' => 'ไม่พบงาน',
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

    public function job_info_get()
    {
        if ($this->is_authen()) {

            $token_info = $this->authorization_token->userData();

            if ($token_info->user_role_id == 3 || $token_info->user_role_id == 1) {

                $id = $this->get('id');

                if (isset($id) && !is_blank($id)) {
                    $this->jobs->setJobId($id);
                }

                $tech_id =  $token_info->id;

                if (isset($tech_id) && !is_blank($tech_id)) {
                    $this->jobs->setTechId($tech_id);
                    $this->user->getUserID($tech_id);
                }

                $location = $this->user->location();

                if (isset($location['latitude']) && !is_blank($location['latitude'])) {
                    $this->jobs->setLatitude($location['latitude']);
                }

                if (isset($location['longitude']) && !is_blank($location['longitude'])) {
                    $this->jobs->setLongitude($location['longitude']);
                }


                $data = $this->jobs->job_info_club();

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
                        'message' => 'ไม่พบงาน',
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

    public function job_accept_put()
    {
        if ($this->is_authen()) {

            $token_info = $this->authorization_token->userData();

            if ($token_info->user_role_id == 3 || $token_info->user_role_id == 1) {

                $job_id = $this->get('id');

                $id = $token_info->id;

                if (isset($job_id) && !is_blank($job_id)) {
                    $this->jobs->setJobId($job_id);
                    $this->user->getUserID($id);
                }

                $this->jobs->setTechId($token_info->id);

                $cus_id = $this->jobs->getCusID();

                $check = $this->user->check_club_account_validate();

                if ($check['validate']) {

                    $data = $this->jobs->accept_job();

                    $status = 'accept';

                    if ($data !== false && !is_blank($data)) {

                        $fcm_tokens = $this->jobs->get_fcmToken($cus_id);

                        if ($fcm_tokens) {

                            $error = 0;

                            foreach ($fcm_tokens as $row) {
                                $fcm_token = $row['device_id'];

                                $count = $this->jobs->notification_count();

                                $count = $count+1;

                                if (!$this->FCM($fcm_token,$job_id,$status,$count)){
                                    $error++;   
                                }
                            }

                            $tokens = $this->user->getOperatorToken();
                            $job_type = $this->jobs->job_type();

                            if ($tokens && $job_type == 3) {

                                $message = "ช่างรับงานติดตั้ง - Job ID ".$job_id;
                                $url = "job-list/form/".$job_id."?&method=edit";

                                foreach ($tokens as $row) {

                                    $this->my_libraies->send_push_noti($row['token'],$message,$url);

                                }
                            } elseif ($tokens && $job_type == 1) {
                                $message = "ช่างรับงานล้างแอร์ - Job ID ".$job_id;
                                $url = "job-list/form/".$job_id."?&method=edit";

                                foreach ($tokens as $row) {

                                    $this->my_libraies->send_push_noti($row['token'],$message,$url);

                                }
                            } elseif ($tokens && $job_type == 2) {
                                $message = "ช่างรับงานซ่อมแอร์ - Job ID ".$job_id;
                                $url = "job-list/form/".$job_id."?&method=edit";

                                foreach ($tokens as $row) {

                                    $this->my_libraies->send_push_noti($row['token'],$message,$url);

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
                                'message' => 'ไม่พบลูกค้า',
                                'data' => array()
                            );
                        }

                    } else {
                        $http_code = REST_Controller::HTTP_FORBIDDEN;
                        $message = array(
                            'status' => false,
                            'code' => $http_code,
                            'message' => 'มีช่างเทคนิครับงานแล้ว',
                            'data' => array()
                        );
                    }

                } else {
                    $http_code = REST_Controller::HTTP_FORBIDDEN;
                    $message = array(
                        'status' => false,
                        'code' => $http_code,
                        'title' => "ไม่สามารถรับงานได้",
                        'message' => 'กรุณาอัพเดตข้อมูลส่วนตัว.',
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

    public function job_complete_put()
    {
        if ($this->is_authen()) {

            $token_info = $this->authorization_token->userData();

            if ($token_info->user_role_id == 3 || $token_info->user_role_id == 1) {

                $job_id = $this->get('id');

                if (isset($job_id) && !is_blank($job_id)) {
                    $this->jobs->setJobId($job_id);
                }

                $cus_id = $this->jobs->getCusID();

                $this->jobs->setTechId($token_info->id);

                $data = $this->jobs->complete_job();

                if ($data !== false && !is_blank($data)) {

                    $status = 'complete';

                    $fcm_tokens = $this->jobs->get_fcmToken($cus_id);

                    if ($fcm_tokens) {

                        $error = 0;

                        foreach ($fcm_tokens as $row) {
                            $fcm_token = $row['device_id'];
                            
                            $count = $this->jobs->notification_count();

                            $count = $count+1;

                            if (!$this->FCM($fcm_token,$job_id,$status,$count)){
                                $error++;   
                            }
                        }

                        /*$job_type = $this->jobs->job_type();

                        if ($job_type == 4) {
                           $invoiceid = $this->jobs->getLastInvoice();

                           $this->jobs->setInvoice($invoiceid);
                       }*/

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
                                'message' => 'Unable to notify customer.',
                                'data' => array()
                            );
                        }*/

                    } else {
                        $http_code = REST_Controller::HTTP_BAD_REQUEST;
                        $message = array(
                            'status' => false,
                            'code' => $http_code,
                            'message' => 'ไม่พบลูกค้า',
                            'data' => array()
                        );
                    }


                } else {
                    $http_code = REST_Controller::HTTP_BAD_REQUEST;
                    $message = array(
                        'status' => false,
                        'code' => $http_code,
                        'message' => 'ไม่พบงาน',
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

    public function notification_get()
    {
        if ($this->is_authen()) {

            $token_info = $this->authorization_token->userData();

            if ($token_info->user_role_id == 3 || $token_info->user_role_id == 1) {

                $id = $token_info->id;

                if (isset($id) && !is_blank($id)) {
                    $this->jobs->setCustomerId($id);
                } else {
                    $this->jobs->setCustomerId(0);
                }

                $data = $this->jobs->get_notification();

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
                        'message' => 'ไม่พบการแจ้งเตือน',
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

    public function deny_job_delete()
    {
        /*** Deny Job ***/
        if ($this->is_authen()) {

            $token_info = $this->authorization_token->userData();

            if ($token_info->user_role_id == 3 || $token_info->user_role_id == 1) {

                $job_id = $this->get('id');

                if (isset($job_id) && !is_blank($job_id)) {
                    $this->notification->setJobId($job_id);
                    $this->jobs->setJobId($job_id);
                }

                $tech_id =  $token_info->id;

                if (isset($tech_id) && !is_blank($tech_id)) {
                    $this->notification->setCustomerId($tech_id);
                } else {
                    $this->notification->setCustomerId(0);
                }

                $data = $this->notification->remove();

                $cus_id = $this->jobs->getCusID();

                if ($data !== false && !is_blank($data)) {

                    /*$status = 'cancel';

                    $fcm_tokens = $this->jobs->get_fcmToken($cus_id);

                    if ($fcm_tokens) {*/

                        /*$error = 0;

                        foreach ($fcm_tokens as $row) {
                            $fcm_token = $row['device_id'];
                            
                            $count = $this->jobs->notification_count();

                            $count = $count+1;

                            if (!$this->FCM($fcm_token,$job_id,$status,$count)){
                                $error++;   
                            }
                        }

                        if ($error == 0){*/
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

                    /*} else {
                        $http_code = REST_Controller::HTTP_BAD_REQUEST;
                        $message = array(
                            'status' => false,
                            'code' => $http_code,
                            'message' => 'Customer not found.',
                            'data' => array()
                        );
                    }*/

                } else {
                    $http_code = REST_Controller::HTTP_BAD_REQUEST;
                    $message = array(
                        'status' => false,
                        'code' => $http_code,
                        'message' => "ไม่สามารถลบการแจ้งเตือน",
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

    public function status_log_get()
    {
        if ($this->is_authen()) {

            $token_info = $this->authorization_token->userData();

            if ($token_info->user_role_id == 3 || $token_info->user_role_id == 1 || $token_info->user_role_id == 2 || $token_info->user_role_id == 4) {

                $job_id = $this->get('id');

                $this->jobs->setJobId($job_id);

                $data = $this->jobs->getStatusCodeLog();

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
                        'message' => 'ไม่พบสถานะ',
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

            $http_code = REST_Controller::HTTP_METHOD_NOT_ALLOWED;
            $message = array(
                'status' => false,
                'code' => $http_code,
                'message' => "You do not have permission to access.",
                'data' => array()
            );
        }

        $this->response($message, $http_code);  
    }

    public function FCM($token,$job_id,$status) 
    {
        $url = "https://fcm.googleapis.com/fcm/send";
        $serverKey = 'AAAAH3G-tO4:APA91bHuxDL0IHvH-kafNS6U-BZF66Rauw3GvLNntngP6274FlGLyfn1juo6ElXOw5NMtAwpnmJbYq30cSG-l2jvhTMXarfWOyjbqDcXeqWunDv7os7dce62GQiHzgNV4vD2EXYO5cWB';
        $title = "Saijo Denki E-Service";

        if ($status == 'complete') {
            $body = "ช่างเทคนิคทำงานเสร็จแล้ว";
        } elseif ($status == 'accept') {
            $body = "ช่างเทคนิคได้ตอบรับคำขอรับบริการของคุณแล้ว";
        } else {
            $body = "ช่างเทคนิคได้ยกเลิกคำขอของคุณ";
        }

        $notification = array('title' =>$title , 'body' => $body, 'sound' => 'default', 'badge' => '1');
        $data = array('job_id' => $job_id, 'status' => $status);
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

        $response = curl_exec($ch);

        if ($response === FALSE) {
            return false;
        }

        curl_close($ch);

        $result = json_decode($response, true);

        return $result['success'];


    }

} // End of class
