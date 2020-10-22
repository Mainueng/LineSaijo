<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';


class Technician extends REST_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index_post()
    {

    }

    public function users_get()
    {
        if ($this->is_authen()) {

            $userId = $this->get('id');
            $this->load->model($this->technician_model, 'technician');
            $result = array();
            $data = array();

            if (isset($userId) && !is_blank($userId)) {
                $this->technician->setTechnicianId($userId);
            }

            $result = $this->technician->technicians_list();

            if (is_array($result) & !is_blank($result)) {
                $data['total'] = count($result);
                $data['technician_list'] = $result;

            }
            $this->response(array('status' => true, 'code' => REST_Controller::HTTP_OK, 'message' => "Request successfully", 'data' => $data), REST_Controller::HTTP_OK);

        } else {
            $this->response(array('status' => FALSE, 'message' => $this->_authen_message), REST_Controller::HTTP_NOT_FOUND);
        }

    }

    public function users_post()
    {
        if ($this->is_authen()) {
            echo "POST OK" . $this->get('id');

        } else {
            $this->response(array('status' => FALSE, 'message' => $this->_authen_message), REST_Controller::HTTP_NOT_FOUND);
        }
    }

    public function users_on_system_get()
    {
        if ($this->is_authen()) {

            $this->load->model($this->technician_model, 'technician');


        } else {
            $this->response(array('status' => FALSE, 'message' => $this->_authen_message), REST_Controller::HTTP_NOT_FOUND);
        }

    }

    public function acceptjob()
    {
        if ($this->is_authen()) {
        } else {
            $this->response(array('status' => FALSE, 'message' => $this->_authen_message), REST_Controller::HTTP_NOT_FOUND);
        }
    }

    public function online_get()
    {
        if ($this->is_authen()) {
        } else {
            $this->response(array('status' => FALSE, 'message' => $this->_authen_message), REST_Controller::HTTP_NOT_FOUND);
        }
    }

    public function all_get()
    {
        if ($this->is_authen()) {


        } else {
            $this->response(array('status' => FALSE, 'message' => $this->_authen_message), REST_Controller::HTTP_NOT_FOUND);
        }
    }

    #accept_job
    public function acceptjob_get()
    {
        if ($this->is_authen()) {
            echo "technician_id = ".$this->_user_id." Role =".$this->_user_role_id." ";
            echo "Job Id = ".$this->get('job_id').$this->get('action');


        } else {
            $this->response(array('status' => FALSE, 'message' => $this->_authen_message), REST_Controller::HTTP_NOT_FOUND);
        }
    }


} // End of class
