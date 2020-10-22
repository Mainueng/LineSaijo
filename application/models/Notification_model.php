<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Notification_model extends MY_Model
{
    private $_cus_id;
	private $_job_id;
    private $_latitude;
    private $_longitude;
    private $_type_code;

    public function setCustomerId($cus_id)
    {
        $this->_cus_id = $cus_id;
    }

    public function setJobId($job_id)
    {
        $this->_job_id = $job_id;
    }

    public function setLatitude($latitude)
    {
        $this->_latitude = $latitude;
    }

    public function setLongitude($longitude)
    {
        $this->_longitude = $longitude;
    }

    public function setJobType($job_type)
    {
        $this->_job_type = $job_type;
    }

    public function get_location()
    {
        $data_where = array(
            'id' => $this->_cus_id,
        );

        $this->db->select('latitude, longitude');
        $this->db->where($data_where);
        $this->db->from($this->tbl_cus_mstr);

        $query = $this->db->get();

        if ($query->num_rows() > 0) {

           $result = $query->row();

           $data['latitude'] = $result->latitude;
           $data['longitude'] = $result->longitude;

           return $data;
        
        } else {
            return false;
        }
    }

    public function set_notification($id,$job_id,$type_code)
    {

        $data_where = array(
            'tech_id' => $id,
            'job_id' => $job_id
        );

        $this->db->select('tech_id, job_id');
        $this->db->where($data_where);
        $this->db->from($this->tbl_notification);

        $query = $this->db->get();

        if ($query->num_rows() == 0) {

            $this->db->insert($this->tbl_notification, array('tech_id' => $id,'job_id' => $job_id, 'job_type' => $type_code, 'status' => 1, 'datetime' => date("Y-m-d H:i:s")));
        }
    }

    public function check_notification($id,$job_id)
    {

        $data_where = array(
            'tech_id' => $id,
            'job_id' => $job_id
        );

        $this->db->select('tech_id, job_id');
        $this->db->where($data_where);
        $this->db->from($this->tbl_notification);

        $query = $this->db->get();

        if ($query->num_rows() == 0) {

            return true;
        } else {
            return false;
        }
    }

    public function get_notification()
    {
        $data_where = array(
            'notification.tech_id' => $this->_cus_id,
            'jobs.appointment_datetime >=' => date("Y-m-d H:i:s"),
            'jobs.status_code <' => 3
        );

        $this->db->select('notification.job_id, notification.job_type, notification.status, cus_mstr.name, cus_mstr.lastname, cus_mstr_profile.cus_addr1, cus_mstr_profile.district, cus_mstr_profile.province, cus_mstr_profile.postal_code, jobs.appointment_datetime, jobs.latitude as lat1, jobs.longitude as lon1');
        $this->db->where($data_where);
        $this->db->from($this->tbl_notification);
         $this->db->join($this->tbl_jobs, 'jobs.id = notification.job_id', 'left');
        $this->db->join($this->tbl_cus_mstr, 'jobs.cus_id = cus_mstr.id', 'left');
        $this->db->join($this->tbl_cus_mstr_profile, ' cus_mstr_profile.cus_id = jobs.cus_id', 'left');

        $this->db->order_by('notification.datetime', 'desc');

        $query = $this->db->get();

        if ($query->num_rows() > 0) {

            foreach ($query->result_array() as $row) {
                $rows = array(
                    'job_id' => get_array_value($row, 'job_id', ''),
                    'job_type' => get_array_value($row, 'job_type', ''),
                    'status' => get_array_value($row, 'status', ''),
                    'name' => get_array_value($row, 'name', ''),
                    'lastname' => get_array_value($row, 'lastname', ''),
                    /*'appointment_datetime' => get_array_value($row, 'appointment_datetime', ''),*/
                    'appointment_datetime' => date_format(date_create(get_array_value($row, 'appointment_datetime', '')), 'd-m-Y H:i'),
                    'address' => get_array_value($row, 'cus_addr1', ''),
                    'district' => get_array_value($row, 'district', ''),
                    'province' => get_array_value($row, 'province', ''),
                    'postal_code' => get_array_value($row, 'postal_code', ''),
                    'radius' => $this->radius(get_array_value($row, 'lat1', ''),get_array_value($row, 'lon1', ''),$this->_latitude,$this->_longitude)." km",
                );

                $result[] = $rows;
            }



            return $result;

        } else {

            return false;
        }
    }

    public function read()
    {
        $data_where = array(
            'tech_id' => $this->_cus_id,
            'job_id' => $this->_job_id,
            'status' => 1
        );

        $data = array(
            'status'=> 0
        );

        $this->db->where($data_where);
        $msg = $this->db->update($this->tbl_notification,$data);

        if ($msg == 1){

            return true;

        } else {

            return false;
        }
    }

    public function read_all()
    {
        $data_where = array(
            'tech_id' => $this->_cus_id,
            'job_type' => $this->_job_type,
            'status' => 1
        );

        $data = array(
            'status'=> 0
        );

        $this->db->where($data_where);
        $msg = $this->db->update($this->tbl_notification,$data);

        if ($msg == 1){

            return true;

        } else {

            return false;
        }
    }

    public function remove_all()
    {
        $data_where = array(
            'tech_id' => $this->_cus_id,
            'job_type' => $this->_job_type
        );

        $this->db->where($data_where);
        $msg = $this->db->delete($this->tbl_notification,$data_where);

        if ($msg == 1){

            return true;

        } else {

            return false;
        }
    }

    public function remove()
    {
        $data_where = array(
            'tech_id' => $this->_cus_id,
            'job_id' => $this->_job_id
        );

        $this->db->where($data_where);
        $msg = $this->db->delete($this->tbl_notification,$data_where);

        if ($msg == 1){

            return true;

        } else {

            return false;
        }
    }

    public function radius($lat1,$lon1,$lat2,$lon2)
    {

        $lat1 = deg2rad($lat1);
        $lon1 = deg2rad($lon1);
        $lat2 = deg2rad($lat2);
        $lon2 = deg2rad($lon2);

        $radius =  acos(sin($lat1) * sin($lat2) + cos($lat1) * cos($lat2) * cos($lon1 - $lon2)) * 6371;

        return ceil($radius * 10) / 10; 
    }
}