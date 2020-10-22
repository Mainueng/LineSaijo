<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';


class Summary extends REST_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->methods['index_get']['limit'] = 500;
        $this->methods['form_old_get']['limit'] = 500;
        $this->methods['index_post']['limit'] = 1500;
        $this->methods['upload_pic_post']['limit'] = 500;

        $this->load->model($this->summary_model, 'summary');
        $this->load->model($this->jobs_model, 'jobs');

        $this->load->library('Authorization_Token');
    }

    public function index_get()
    {
        /*** Get Summary ***/
        if ($this->is_authen()) {

            $token_info = $this->authorization_token->userData();

            if ($token_info->user_role_id == 3 || $token_info->user_role_id == 1) {

                $data = array();

                $job_id = $this->get('id');

                $this->summary->setJobID($job_id);

                $job_type = $this->summary->findJobType();
                $serial = $this->summary->getSerial();

                if (!$serial) {
                    $install_list = $this->summary->getInstallList();

                    if ($install_list) {
                        $install_list_arr = explode(',', $install_list);

                        $arr = array();

                        $count = 1;

                        for ($i=0; $i < count($install_list_arr); $i++) {
                            $install_data = explode(' - ', $install_list_arr[$i]);

                            if (strpos($install_data[0], 'ส่วนลดค่าติดตั้ง') === false) {
                                $arr[] = $count.'_'.$install_data[0];

                                $count++;
                            }

                            $serial_implode = implode(',',$arr);

                            $serial = str_replace(" ","_",$serial_implode);
                        }

                    } else {
                        $serial = '';
                    }
                }

                if ($job_type) {

                    $this->summary->setJobType($job_type);

                    $this->summary->setSerial($serial);

                    $check_sheet = $this->summary->check_sheet();

                    if ($check_sheet) {
                        $data = json_decode($check_sheet,true);
                    } else {
                        $data = $this->summary->check_sheet_form();
                    }

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
                            'message' => "ไม่พบแบบประเมิน",
                            'data' => array()
                        );
                    }

                } else {
                    $http_code = REST_Controller::HTTP_BAD_REQUEST;
                    $message = array(
                        'status' => false,
                        'code' => $http_code,
                        'message' => "ไม่พบงาน",
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

    public function form_old_get()
    {
        /*** Get Summary ***/
        if ($this->is_authen()) {

            $token_info = $this->authorization_token->userData();

            if ($token_info->user_role_id == 3 || $token_info->user_role_id == 1) {

                $data = array();

                $job_id = $this->get('id');

                $this->summary->setJobID($job_id);

                $job_type = $this->summary->findJobType($job_id);

                if ($job_type) {

                    $this->summary->setJobType($job_type);

                    $check_sheet = $this->summary->check_sheet();

                    if ($check_sheet) {
                        $data = json_decode($check_sheet,true);
                    } else {
                        $data = $this->summary->check_sheet_form_old();
                    }

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
                            'message' => "ไม่พบงาน",
                            'data' => array()
                        );
                    }

                } else {
                    $http_code = REST_Controller::HTTP_BAD_REQUEST;
                    $message = array(
                        'status' => false,
                        'code' => $http_code,
                        'message' => "Job not found.",
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
        /*** Post Summary ***/
        if ($this->is_authen()) {

            $token_info = $this->authorization_token->userData();

            if ($token_info->user_role_id == 3 || $token_info->user_role_id == 1) {

                $job_id = $this->get('id');

                $this->summary->setJobID($job_id);
                $this->jobs->setJobId($job_id);

                $this->summary->setCheckSheet(json_encode($this->post()));

                $json = json_encode($this->post());

                $data = $this->summary->save_check_sheet();

                $cus_id = $this->jobs->getCusID();

                $this->jobs->setTechId($token_info->id);

                $total = $this->jobs->getServiceTotal();
                $this->jobs->setTotal($total);

                $complete = $this->jobs->complete_job();

                $complete = $token_info->id;

                if ($data !== false && !is_blank($data) && $complete !== false && !is_blank($complete)) {

                    $fcm_tokens = $this->jobs->get_fcmToken($cus_id);

                    $error = 0;

                    if ($fcm_tokens) {

                        foreach ($fcm_tokens as $row) {
                            $fcm_token = $row['device_id'];

                            $count = $this->jobs->notification_count();

                            $count = $count+1;

                            if (!$this->FCM($fcm_token,$job_id,$count)){
                                $error++;   
                            }
                        }

                        if ($error == 0){

                            $invoice = $this->jobs->getLastInvoice();
                            $total = $this->jobs->getServiceTotal();
                            $job_type = $this->jobs->job_type();
                            $service_fee = $this->jobs->getServiceFee();

                            if (!$service_fee) {
                                $total = 0;
                            }

                            if ($total != 0) {
                                $this->jobs->setInvoice($invoice);
                                $this->jobs->setTotal($total);
                                $this->jobs->setTypeCode($job_type);

                                $this->jobs->addInvoice();
                            } else {
                                $this->jobs->complete_free_service();
                            }

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
                                'message' => 'ไม่สามารถแจ้งให้ลูกค้าทราบ',
                                'data' => array()
                            );
                        }

                    } else {
                        $http_code = REST_Controller::HTTP_BAD_REQUEST;
                        $message = array(
                            'status' => false,
                            'code' => $http_code,
                            'message' => 'ไม่สามารถแจ้งให้ลูกค้าทราบ',
                            'data' => array()
                        );
                    }

                } else {
                    $http_code = REST_Controller::HTTP_BAD_REQUEST;
                    $message = array(
                        'status' => false,
                        'code' => $http_code,
                        'message' => "ไม่สามารถเซฟได้.",
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

    public function upload_pic_post()
    {

        if ($this->is_authen()) {

            $token_info = $this->authorization_token->userData();

            if ($token_info->user_role_id == 3 || $token_info->user_role_id == 1) {

                $job_id = $this->get('id');
                $serial = $this->get('serial');

                $this->summary->setJobID($job_id);

                $before = '';

                if (isset($_FILES['before']['name'])) {
                    $before = $this->upload_img($job_id."_".$serial,'before');

                    $this->summary->setBefore($before);
                }

                if (isset($_FILES['after']['name'])) {
                    $after = $this->upload_img($job_id."_".$serial,'after');

                    $this->summary->setAfter($after);
                }

                if (isset($_FILES['signature']['name'])) {
                    $signature = $this->upload_img($job_id,'signature');

                    $this->summary->setSignature($signature);
                }

                $data = $this->summary->updateImage();

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
                        'message' => "การอัปเดตล้มเหลว",
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

    public function upload_pic_old_post()
    {

        if ($this->is_authen()) {

            $token_info = $this->authorization_token->userData();

            if ($token_info->user_role_id == 3 || $token_info->user_role_id == 1) {

                $job_id = $this->get('id');

                $this->summary->setJobID($job_id);

                $before = '';

                if (isset($_FILES['before']['name'])) {
                    $before = $this->upload_img($job_id,'before');

                    $this->summary->setBefore($before);
                }

                if (isset($_FILES['after']['name'])) {
                    $after = $this->upload_img($job_id,'after');

                    $this->summary->setAfter($after);
                }

                if (isset($_FILES['signature']['name'])) {
                    $signature = $this->upload_img($job_id,'signature');

                    $this->summary->setSignature($signature);
                }

                $data = $this->summary->updateImage();

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
                        'message' => "การอัปเดตล้มเหลว",
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

        imagepng($images_fin, "application/controllers/v1/club/upload/".$img."/".$id.".png");

        ImageDestroy($images_orig);
        ImageDestroy($images_fin);

        $name = $id.".png";

        return $name;
    }

    public function FCM($token,$job_id,$count) 
    {
        $url = "https://fcm.googleapis.com/fcm/send";
        $serverKey = 'AAAAH3G-tO4:APA91bHuxDL0IHvH-kafNS6U-BZF66Rauw3GvLNntngP6274FlGLyfn1juo6ElXOw5NMtAwpnmJbYq30cSG-l2jvhTMXarfWOyjbqDcXeqWunDv7os7dce62GQiHzgNV4vD2EXYO5cWB';
        $title = "Saijo Denki E-Service";

        $body = "ช่างเทคนิคทำงานเสร็จแล้ว";

        $notification = array('title' =>$title , 'body' => $body, 'sound' => 'default', 'badge' => $count);
        $data = array('job_id' => $job_id, 'status' => 'complete');
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

        return $result/*['success']*/;


    }

} // End of class
