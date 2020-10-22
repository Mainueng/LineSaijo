<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Technician_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    private $_limit;
    private $_offset;
    private $_id;
    private $_online_status;

    private $_distance;
    private $_job_id;

    private $_firstname;
    private $_lastname;
    private $_id_number;
    private $_tel;
    private $_latitude;
    private $_longitude;
    private $_address;
    private $_district;
    private $_province;
    private $_postal_code;
    private $_rating;
    private $_saijo_certification;
    private $_profile;

    public function setLimit($limit)
    {
        $this->_limit = $limit;
    }

    public function setOffset($offset)
    {
        $this->_offset = $offset;
    }

    public function setTechnicianId($id)
    {
        $this->_id = $id;
    }

    public function getTechnicianId()
    {
        return $this->_id;
    }

    public function setOnlineStatus($online_status)
    {
        $this->_online_status = $online_status;
    }

    public function setDistance($distance){
        $this->_distance = $distance;
    }

    public function setJobId($jobId){
        $this->_job_id = $jobId;
    }

    public function setFirstname($firstname){
        $this->_firstname = $firstname;
    }

    public function setLastname($lastname){
        $this->_lastname = $lastname;
    }

    public function setTel($tel){
        $this->_tel = $tel;
    }

    public function setLatitude($latitude){
        $this->_latitude = $latitude;
    }

    public function setLongitude($longitude){
        $this->_longitude = $longitude;
    }

    public function setAddress($address){
        $this->_address = $address;
    }

    public function setDistrict($district){
        $this->_district = $district;
    }

    public function setProvice($province){
        $this->_province = $province;
    }

    public function setPostalCode($postal_code){
        $this->_postal_code = $postal_code;
    }

    public function setSaijoCertification($saijo_certification){
        $this->_saijo_certification = $saijo_certification;
    }

    public function setProfile($profile){
        $this->_profile = $profile;
    }
/*
    public function technicians_list()
    {

        $result = array();

        $data_where = array(
            'status' => 1,
            'user_role_id' => 3,
            'cus_group_id' => 1
        );
        $this->db->select('	technicians.*,
         user_role.`name` AS role_title');
        $this->db->join($this->tbl_user_role, 'technicians.user_role_id = user_role.id', 'left');
        $this->db->where($data_where);
        if (!is_blank($this->_id)) {
            $this->db->where($this->tbl_technician . '.id', $this->_id);
        }
        if (!is_blank($this->_limit) && !is_blank($this->_offset)) {
            $this->db->limit($this->_limit, $this->_offset);
        }
        $query = $this->db->get($this->tbl_technician);

        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {

                if (!is_blank($row->name)) {
                    $first_name = $row->name;
                } else {
                    $first_name = $row->fb_name;
                }
                if (!is_blank($row->lastname)) {
                    $last_name = $row->lastname;
                } else {
                    $last_name = $row->fb_lname;
                }
                $full_name = $first_name . " " . $last_name;

                $result[] = array(
                    'id' => $row->id,
                    'email' => $row->email,
                    'name' => $first_name,
                    'last_name' => $last_name,
                    'full_name' => $full_name,
                    'telephone' => $row->telephone,
                    'token' => $row->token,
                    'total_score' => $row->total_score,
                    'latitude' => $row->latitude,
                    'longitude' => $row->longitude,
                    'user_role_id' => $row->user_role_id,
                    'iot_id' => $row->iot_id,
                    'online_status' => $row->online_status,
                    'language_id' => $row->language_id,
                    'status' => $row->status,
                    'role_title' => $row->role_title
                );
            }


        }

        return $result;
    }

    public function technician_online()
    {
        $data_where = array(
            'user_role_id' => 3,
            'online_status' => 1
        );
        $this->db->where('device_token IS NOT NULL', NULL, FALSE);
        $this->db->where('device_token !=""');
        $query = $this->db->get_where($this->tbl_cus_mstr, $data_where);
        $result = array();
        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $result[] = $row;
            }
        }


        return $result;
    }

    public function find_technician_nearest()
    {

        $query = "SELECT
        a.id,
        a.email,
        a.`name` AS first_name,
        a.lastname,
        a.telephone,
        a.device_token,
        (
        111.111 * DEGREES(
        ACOS(
        LEAST(
        COS( RADIANS( a.latitude ) ) * COS( RADIANS( b.latitude ) ) * COS( RADIANS( a.longitude - b.longitude ) ) + SIN( RADIANS( a.latitude ) ) * SIN( RADIANS( b.latitude ) ),
        1.0 
        ) 
        ) 
        ) 
        ) AS distance_in_km 
        FROM
        cus_mstr AS a
        JOIN jobs AS b 
        WHERE
        b.id ='".$this->_job_id."' AND a.user_role_id = 3 
        AND a.device_token IS NOT NULL 
        AND device_token != '' HAVING
        distance_in_km < $this->_distance";


        $rs = $this->db->query($query);
        $rows = array();
        if ($rs->num_rows() > 0) {
            foreach ($rs->result_array() as $row) {
                $rows[] = $row;
            }
        }


        return $rows;
    }*/

    public function technicianInfo()
    {

        $data_where = array(
            'technician_info.tech_id' => $this->_id,
        );

        $this->db->select('technician_info.*, cus_mstr.name, cus_mstr.lastname, cus_mstr.email, cus_mstr.latitude, cus_mstr.longitude, saijo_verify.verify_id, saijo_verify.approval_date, saijo_verify.expiry_date , saijo_verify.status');
        $this->db->from($this->tbl_cus_mstr);
        $this->db->join($this->tbl_technician_info, 'technician_info.tech_id = cus_mstr.id', 'left');
        $this->db->join($this->tbl_saijo_verify, 'saijo_verify.tech_id = technician_info.tech_id', 'left');
        $this->db->where($data_where);

        $query = $this->db->get();
        $result = array();
        $rows = array();
        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $rows = array(
                    'name'  => get_array_value($row, 'name', ''),
                    'lastname'  => get_array_value($row, 'lastname', ''),
                    'telephone' => get_array_value($row, 'telephone', ''),
                    'email' => get_array_value($row, 'email', ''),
                    'profile_img' => get_array_value($row, 'profile_img', ''),
                    'address' => get_array_value($row, 'address', ''),
                    'district' => get_array_value($row, 'district', ''),
                    'province' => get_array_value($row, 'province', ''),
                    'postal_code' => get_array_value($row, 'postal_code', ''),
                    'latitude' => get_array_value($row, 'latitude', ''),
                    'longitude' => get_array_value($row, 'longitude', ''),
                    'saijo_certification' => get_array_value($row, 'verify_id', ''),
                    'approval_date' => get_array_value($row, 'approval_date', ''),
                    'expiry_date' => get_array_value($row, 'expiry_date', ''),
                    'verify' => get_array_value($row, 'status', ''),
                    'rating' => get_array_value($row, 'rating', ''),
                    'rating_count' => get_array_value($row, 'rating_count', '')
                );

                if ($rows['rating_count'] > 999) {
                    $rows['rating_count'] = substr($rows['rating_count'], 0, -3).'K';
                }

                if ($rows['verify'] == 1) {
                    $rows['verify'] = true;
                } else {
                    $rows['verify'] = false;
                }

                if ($rows['expiry_date'] < date("Y-m-d")) {
                    $rows['verify'] = false;
                    $rows['approval_date'] = '-';
                    $rows['expiry_date'] = '-';
                    $rows['saijo_certification'] = '-';

                    $this->db->where(array('tech_id' => $this->_id));
                    $this->db->update($this->tbl_saijo_verify, array('status' => 0));
                }

                $result[] = $rows;
            }

            return $result;

        } else {

            return false;
        }
    }

    public function updateTechnicianInfo()
    {

        $data = array();
        $address = array();

        if (!is_blank($this->_firstname)) {
            $data['name'] = $this->_firstname;
        }
        if (!is_blank($this->_lastname)) {
            $data['lastname'] = $this->_lastname;
        }
        if(!is_blank($this->_tel)){
            $data['telephone'] = $this->_tel;
            $address['telephone'] = $this->_tel;
        }
        if(!is_blank($this->_latitude)){
            $data['latitude'] = $this->_latitude;
        }
        if(!is_blank($this->_longitude)){
            $data['longitude'] = $this->_longitude;
        }
        if(!is_blank($this->_address)){
            $address['address'] = $this->_address;
        }
        if(!is_blank($this->_district)){
            $address['district'] = $this->_district;
        }
        if(!is_blank($this->_province)){
            $address['province'] = $this->_province;
        }
        if(!is_blank($this->_postal_code)){
            $address['postal_code'] = $this->_postal_code;
        }
        if(!is_blank($this->_saijo_certification)){
            $address['saijo_certification'] = $this->_saijo_certification;
        }
        if(!is_blank($this->_profile)){

            if (strpos($this->_profile,".png")) {
                $address['profile_img'] = $this->_profile;
            } else {
                $address['profile_img'] = $this->_profile.".png";
            }
        }

        $data_where = array(
            'id' => $this->_id
        );

        if ($data) {
            $this->db->where($data_where);
            $msg = $this->db->update($this->tbl_cus_mstr, $data);
        } else {
            $msg = 1;
        }

        $data_where = array(
            'tech_id' => $this->_id
        );

        if ($address) {
            $this->db->where($data_where);
            $msg2 = $this->db->update($this->tbl_technician_info, $address); 
        } else {
            $msg2 = 1;
        }

        if ($msg == 1 && $msg2 == 1) {

            return true;

        } else {

            return false;
        }
    }

    public function technicianList()
    {
        $data_where = array(
            'status' => 1,
            'user_role_id' => 3,
            'cus_group_id' => 1
        );

        $this->db->select('name, lastname, telephone, email, fb_name, fb_lname, rating');
        $this->db->from($this->tbl_cus_mstr);
        $this->db->join($this->tbl_technician_info, 'technician_info.tech_id = cus_mstr.id', 'left');
        $this->db->where($data_where);
        $query = $this->db->get();

        $result = array();
        $rows = array();
        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {

                $name = get_array_value($row, 'name', '');
                $lastname = get_array_value($row, 'lastname', '');

                if (!is_blank($name)) {
                    $first_name = $name;
                } else {
                    $first_name = get_array_value($row, 'fb_name', '');
                }
                if (!is_blank($lastname)) {
                    $last_name = $lastname;
                } else {
                    $last_name = get_array_value($row, 'fb_lname', '');
                }

                $rows = array(
                    'name' => $first_name,
                    'lastname' => $last_name,
                    'tel' => get_array_value($row, 'telephone', ''),
                    'email' => get_array_value($row, 'email', ''),
                    'rating' => get_array_value($row, 'rating', ''),
                );

                $result[] = $rows;
            }

            return $result;

        } else {

            return false;
        }   
    }

    public function technician_online()
    {
        $data_where = array(
            'user_role_id' => 3
        );

        $this->db->distinct();
        $this->db->select('user_log.cus_id');
        $this->db->from($this->tbl_user_log);
        $this->db->join($this->tbl_cus_mstr, 'cus_mstr.id = user_log.cus_id', 'left');
        $this->db->where($data_where);
        $this->db->where('user_log.device_id IS NOT NULL', NULL, FALSE);
        $this->db->where('user_log.device_id !=""');

        $query = $this->db->get();
        
        $count = 0;

        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $count++;
            }
        }

        return $count;
    }

    public function technician_total()
    {
        $data_where = array(
            'user_role_id' => 3
        );

        $this->db->select('count(id) as total');
        $this->db->from($this->tbl_cus_mstr);
        $this->db->where($data_where);
        
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            
            $result = $query->row();

            $total = $result->total;

        } else {
            $total = 0;
        }

        return strval($total);
    }

} // End of Class
