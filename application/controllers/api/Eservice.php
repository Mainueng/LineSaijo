<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';

class Eservice extends REST_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->library('Authorization_Token');
    }

    public function tech_get()
    {
        //echo "Method get";
        print_r($this->authorization_token->userData());
        echo " userId" . $this->_user_id . " User Email" . $this->_user_email;
        exit();
    }

    public function tech_post()
    {
        echo "Method Post";
    }

    public function technician_post()
    {


    }

    public function technicians_get()
    {
        if ($this->is_authen()) {


            echo "Joe" . $this->_user_id;


        } else {
            $this->response(array('status' => FALSE, 'message' => $this->_authen_message), REST_Controller::HTTP_NOT_FOUND);
        }


    }

    public function service_get()
    {
        if ($this->is_authen()) {

            $service_id = "";
            if (!is_blank($this->get('id'))) {
                $service_id = $this->get('id');
            }

            $result = array();
            $this->load->model($this->service_model, 'service');
            if (!is_blank($service_id)) {
                /*** Select ***/
                $this->service->setServiceId($service_id);
                $result = $this->service->service_list();

            } else {
                /** All  */
                $result = $this->service->service_list();
            }


            $this->response(array(
                'status' => true,
                'code' => REST_Controller::HTTP_OK,
                'message' => "Load Service list Success",
                'data' => $result

            ), REST_Controller::HTTP_OK);


        } else {
            $this->response(array('status' => FALSE, 'message' => $this->_authen_message), REST_Controller::HTTP_NOT_FOUND);
        }

    }

    public function job_post()
    {
        if ($this->is_authen()) {

            $data = $this->security->xss_clean($_POST);
            $this->form_validation->set_rules('type', 'type', 'trim|required');
            $this->form_validation->set_rules('serial', 'serial', 'trim|required');
            $this->form_validation->set_rules('appoint_datetime', 'appointment', 'trim|required');
            $this->form_validation->set_rules('latitude', 'latitude', 'trim|required|numeric');
            $this->form_validation->set_rules('longitude', 'longitude', 'trim|required|numeric');
            $this->form_validation->set_rules('telephone', 'telephone number', 'trim|required|is_natural');
            $this->form_validation->set_rules('problems', 'problem', 'trim|required');
            $this->form_validation->set_rules('service_id', 'service_id', 'trim|required');


            $message = array();

            if ($this->form_validation->run() == TRUE) {
                $http_code = REST_Controller::HTTP_OK;

                /***** Check product Information ****/
                $create_date = now();
                $serial_product = $this->post('serial');
                $service_id = $this->post('service_id');
                $appointment_datetime = $this->post('appoint_datetime');
                $latitude = $this->post('latitude');
                $longitude = $this->post('longitude');
                $telephone = $this->post('telephone');
                $problems = $this->post('problems');

                $serial_product = json_decode($serial_product);
                $serial_product_sl = serialize($serial_product);
                $serial_product_un = unserialize($serial_product_sl);

                $create_date = date("Y-m-d H:i:s");
                $job_status = 1;

                #step-1 Add Job
                $this->load->model($this->jobs_model, 'job');
                $this->job->setCustomerId($this->_user_id);
                $this->job->setSerial($serial_product_sl);
                $this->job->setTelNumber($telephone);
                $this->job->setServiceId($service_id);
                $this->job->setLatitude($latitude);
                $this->job->setLongitude($longitude);
                $this->job->setStatusCode($job_status);
                $this->job->setCreateDateTime($create_date);
                $this->job->setUpdateDateTime($create_date);
                $this->job->setAppointment($appointment_datetime);

                $result = $this->job->create();
                $job_id = $this->job->getJobId();

                #step-2 Add Job history
                if (is_array($serial_product) && !is_blank($serial_product)) {
                    foreach ($serial_product as $row) {
                        $data_db[] = array(
                            'job_id' => $job_id,
                            'serial' => $row,
                            'job_status_id' => 1
                        );
                    }
                }

                $this->load->model($this->job_history_model, 'job_history');

                $this->job_history->setDataArray($data_db);
                $aff = $this->job_history->create();

                if (is_array($data_db)) {
                    foreach ($data_db as $row) {
                        $job_id = get_array_value($row, 'job_id','');
                        $job_status = get_array_value($row, 'job_status', '');
                    }
                }

//                echo "Job History" . $aff;
//                echo "Job ID".$job_id;
//
//                exit(0);


                #step-3 Sen notification to technician
                #3.1 Send Job ID to technician

                $notification = array(
                    'title'=>"You have a jobs",
                    'text'=>"You have clean service 1 jobs. Please Accept if you want this jobs",
                    'sound'=>'default',
                    'badge'=>1
                );
                $data = array(
                    'job_id'=>$job_id,
                );
                $this->load->model($this->technician_model,'technician');

                $technician_online = $this->technician->technician_online();

                foreach ($technician_online as $row){
                    $token = get_array_value($row,'device_token');
                    send_noti_technician($token,$notification,$data);
                }





                #step-4 Send notification to customer
                $data_db = array();
                $data_job = array(
                    'cus_id' => $this->_user_id,
                    'serial' => $serial_product_sl,
                    'unserial' => $serial_product_un
                );


                print_r($data_job);
                exit(0);


//                $this->load->model($this->product_model,'product');
//                $this->product->setSerial($serial_product);
//                $product_info = array();
//                $product_info = $this->product->product_info();


                $message = array(
                    'status' => true,
                    'message' => "Create job Success",
                    'data' => $data_db
                );


            } else {
                $http_code = REST_Controller::HTTP_BAD_REQUEST;
                $message = array(
                    'status' => false,
                    'error' => $this->form_validation->error_array(),
                    'message' => validation_errors()
                );
            }


            $this->response($message, $http_code);

        } else {
            $this->response(array('status' => FALSE, 'message' => $this->_authen_message), REST_Controller::HTTP_NOT_FOUND);
        }
    }

    public function job_get()
    {
        if ($this->is_authen()) {


        } else {
            $this->response(array('status' => FALSE, 'message' => $this->_authen_message), REST_Controller::HTTP_NOT_FOUND);
        }
    }

    public function job_put()
    {
        if ($this->is_authen()) {

            // Customer Update Job
            if ($this->_user_role_id == 4) {
                echo "Customer OK";
            }

            if ($this->_user_role_id == 3) {
                echo "Technician OK";

            }

            //        echo $this->get('id');
//        echo $this->get('action');
//        echo $this->get('f');

//        echo "JobID".$this->put('job_id');
//        echo "JobStatus".$this->put('job_status');

        } else {
            $this->response(array('status' => FALSE, 'message' => $this->_authen_message), REST_Controller::HTTP_NOT_FOUND);
        }


    }

    public function jobpublicnotice_post()
    {

    }


}// end of class
