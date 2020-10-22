<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class job_history_model extends MY_Model
{
    private $_job_history_id;
    private $_job_id;
    private $_serial;
    private $_tech_id;
    private $_job_status_id;
    private $_data_array;

    public function __construct()
    {
        parent::__construct();
    }

    public function setJobHistoryId($job_history_id)
    {
        $this->_job_history_id = $job_history_id;
    }

    public function getJobHistoryId()
    {
        return $this->_job_history_id;
    }

    public function setTechId($tech_id)
    {
        $this->_tech_id = $tech_id;
    }
    public function setJobId($job_id){
        $this->_job_id = $job_id;
    }
    public function setJobStatus($status_id){
        $this->_job_status_id = $status_id;
    }
    public function setSerial($serial){
        $this->_serial = $serial;
    }



    public function setDataArray($data_array)
    {
        $this->_data_array = $data_array;
    }


    public function create()
    {
        if (is_array($this->_data_array) && !is_blank($this->_data_array)) {

            $this->db->insert_batch($this->tbl_job_history, $this->_data_array);

            if ($this->db->affected_rows() > 0) {
                return true;
            } else {
                return false;
            }

        } else {
            return false;
        }


    }

    public function update()
    {

    }


} //end of class
