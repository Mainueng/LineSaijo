<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends MY_Model {

    // Declaration of a variables
    private $_userID;
    private $_techID;
    private $_userName;
    private $_firstName;
    private $_lastName;
    private $_email;
    private $_tel;
    private $_latitude;
    private $_longitude;
    private $_address;
    private $_district;
    private $_province;
    private $_postal_code;
    private $_device_id;
    private $_rating;
    private $_serial;
    private $_cus_function;

    private $_fb_token;
    private $_token;

    function __construct() {
        parent::__construct();
        $this->cus_mstr = 'cus_mstr';
        $this->cus_mstr_profile = 'cus_mstr_profile';
        $this->primaryKey = 'id';
        $this->technician_info = "technician_info";
        $this->cus_tech = "cus_tech";
        $this->cus_function = "cus_function";
    }

    //Declaration of a methods
    public function getUserID($userID)
    {
        $this->_userID = $userID;
    }

    public function setTechID($techID)
    {
        $this->_techID = $techID;
    }

    public function setName($firstName)
    {
        $this->_firstName = $firstName;
    }

    public function setLastName($lastName)
    {
        $this->_lastName = $lastName;
    }

    public function setTel($tel)
    {
        $this->_tel = $tel;
    }

    public function setEmail($email)
    {
        $this->_email = $email;
    }

    public function setLatitude($latitude)
    {
        $this->_latitude = $latitude;
    }

    public function setLongitude($longitude)
    {
        $this->_longitude = $longitude;
    }

    public function setAddress($address)
    {
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

    public function setDeviceId($device_id)
    {
        $this->_device_id = $device_id;
    }

    public function setProfile($profile)
    {
        $this->_profile = $profile;
    }

    public function setRating($rating)
    {
        $this->_rating = $rating;
    }

    public function setSerial($serial)
    {
        $this->_serial = $serial;
    }

    public function setFbToken($fb_token)
    {
        $this->_fb_token = $fb_token;
    }

    public function setToken($token)
    {
        $this->_token = $token;
    }

    public function checkUser_core($userData = array()){
        if(!empty($userData)){
            //check whether user data already exists in database with same oauth info
            $this->db->select($this->primaryKey.',email,user_role_id,iot_id');
            $this->db->from($this->cus_mstr);
            $this->db->where(array('email'=> $userData['fb_email']));
            $this->db->where('status', 1);
            $prevQuery = $this->db->get();
            $prevCheck = $prevQuery->num_rows();

            if($prevCheck > 0 ){
                //$prevResult = $prevQuery->row_array();

                $result = $prevQuery->row();

                if ($result->user_role_id == 1 || $result->user_role_id == 4 || $result->user_role_id == 2) {
                    //update user data
                    $userData['updated_at'] = date("Y-m-d H:i:s");
                    $userData['device_id'] = $this->_device_id;

                    if ($result->iot_id == 0) {
                        $userData['iot_id'] = $result->id;
                    }

                    $userData['user_role_id'] = $result->user_role_id;

                    $update = $this->db->update($this->cus_mstr, $userData, array('id' => $result->id));

                    //get user ID
                    $userID = $result->id;

                } else {
                    $userID = FALSE;
                }

            }else{
                //insert user data

                $userData['created_at']  = date("Y-m-d H:i:s");
                $userData['updated_at'] = date("Y-m-d H:i:s");
                $userData['device_id'] = $this->_device_id;
                $insert = $this->db->insert($this->cus_mstr, $userData);

                //get user ID
                $userID = $this->db->insert_id();

                $this->db->update($this->cus_mstr, array('iot_id' => $userID), array('id' => $userID));

                if ($userData['user_role_id'] == 3) {

                    $user_info['tech_id'] = $userID;

                    $this->db->insert($this->technician_info, $user_info);
                } else {

                    $user_info['cus_id'] = $userID;

                    $this->db->insert($this->cus_mstr_profile, $user_info);
                }

                
            }
        }

        //return user ID
        return $userID?$userID:FALSE;
    }

    public function checkUser_club($userData = array()){
        if(!empty($userData)){
            //check whether user data already exists in database with same oauth info
            $this->db->select($this->primaryKey.',email,user_role_id');
            $this->db->from($this->cus_mstr);
            $this->db->where(array('email'=> $userData['fb_email']));
            $this->db->where('status', 1);
            $prevQuery = $this->db->get();
            $prevCheck = $prevQuery->num_rows();

            if($prevCheck > 0 ){
                //$prevResult = $prevQuery->row_array();

                $result = $prevQuery->row();

                if ($result->user_role_id == 1 || $result->user_role_id == 3) {

                    $userData['user_role_id'] = $result->user_role_id;
                    //update user data
                    $userData['updated_at'] = date("Y-m-d H:i:s");
                    $userData['device_id'] = $this->_device_id;
                    $update = $this->db->update($this->cus_mstr, $userData, array('id' => $result->id));

                    //get user ID
                    $userID = $result->id;

                } else {
                    $userID = FALSE;
                }

            }else{
                //insert user data

                $userData['created_at']  = date("Y-m-d H:i:s");
                $userData['updated_at'] = date("Y-m-d H:i:s");
                $userData['device_id'] = $this->_device_id;
                $insert = $this->db->insert($this->cus_mstr, $userData);

                //get user ID
                $userID = $this->db->insert_id();


                if ($userData['user_role_id'] == 3) {

                    $user_info['tech_id'] = $userID;

                    $this->db->insert($this->technician_info, $user_info);
                } else {

                    $user_info['cus_id'] = $userID;

                    $this->db->insert($this->cus_mstr_profile, $user_info);
                }

                
            }
        }

        //return user ID
        return $userID?$userID:FALSE;
    }

    public function user_role_id($userData = array()){
        if(!empty($userData)){
            //check whether user data already exists in database with same oauth info
            $this->db->select($this->primaryKey.',email,user_role_id');
            $this->db->from($this->cus_mstr);
            $this->db->where(array('email'=> $userData['email']));
            $prevQuery = $this->db->get();
            $prevCheck = $prevQuery->num_rows();

            if($prevCheck > 0 ){

                $result = $prevQuery->row();

                $user_role_id = $result->user_role_id;

            } else {
                $user_role_id = 0;
            }
        }

        //return user ID
        return $user_role_id?$user_role_id:0;
    }

    /*public function checkUserEapp($userEapp = array()){

        if(!empty($userEapp)){

            $this->db2->select('id');
            $this->db2->from($this->cus_mstr);
            $this->db2->where(array('fb_email'=> $userEapp['fb_email'], 'email'=> $userEapp['fb_email'], 'user_role_id'=>$userEapp['user_role_id']));
            $prevQuery = $this->db2->get();
            $prevCheck = $prevQuery->num_rows();

            $userEapp['fb_token'] = $this->_fb_token;
            $userEapp['updated_at'] = date("Y-m-d H:i:s");


            if($prevCheck > 0){
                $prevResult = $prevQuery->row_array();

                $update = $this->db2->update($this->cus_mstr, $userEapp, array('id' => $prevResult['id']));

                //get user ID
                $userEapp = $prevResult['id'];
            }else{

                $userEapp['created_at'] = date("Y-m-d H:i:s");
                $insert = $this->db2->insert($this->cus_mstr, $userEapp);

                //get user ID
                $userEapp = $this->db2->insert_id();

                $data['iot_id'] = $userEapp;

                $update = $this->db2->update($this->cus_mstr, $data, array('id' => $userEapp));

            }
        }

        //return user ID
        return $userEapp?$userEapp:FALSE;
    }*/

    public function getInfo()
    {
        $this->db->select('cus_mstr_profile.*,cus_mstr.name, cus_mstr.lastname, cus_mstr.telephone, cus_mstr.email, cus_mstr.latitude, cus_mstr.longitude');
        $this->db->from($this->cus_mstr_profile);
        $this->db->join($this->cus_mstr, 'cus_mstr_profile.cus_id = cus_mstr.id', 'left');
        $this->db->where('cus_mstr_profile.cus_id', $this->_userID);

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
                    'address' => get_array_value($row, 'cus_addr1', ''),
                    'district' => get_array_value($row, 'district', ''),
                    'province' => get_array_value($row, 'province', ''),
                    'postal_code' => get_array_value($row, 'postal_code', ''),
                    'profile' => get_array_value($row, 'pic0', ''),
                    'latitude' => get_array_value($row, 'latitude', ''),
                    'longitude' => get_array_value($row, 'longitude', '')
                );

                $result[] = $rows;
            }

            return $result;

        } else {

            return false;
        }
    }

    public function updateInfo()
    {

        $data = array();
        $address = array();

        if (!is_blank($this->_firstName)) {
            $data['name'] = $this->_firstName;
        }
        if (!is_blank($this->_lastName)) {
            $data['lastname'] = $this->_lastName;
        }
        if(!is_blank($this->_tel)){
            $data['telephone'] = $this->_tel;
        }
        if(!is_blank($this->_latitude)){
            $data['latitude'] = $this->_latitude;
        }
        if(!is_blank($this->_longitude)){
            $data['longitude'] = $this->_longitude;
        }
        if(!is_blank($this->_address)){
            $address['cus_addr1'] = $this->_address;
        }
        if(!is_blank($this->_district )){
            $address['district'] = $this->_district;
        }
        if(!is_blank($this->_province)){
            $address['province'] = $this->_province;
        }
        if(!is_blank($this->_postal_code)){
            $address['postal_code'] = $this->_postal_code;
        }
        if(!is_blank($this->_profile)){

            if (strpos($this->_profile,".png")) {
                $address['pic0'] = $this->_profile;
            } else {
                $address['pic0'] = $this->_profile.".png";
            }
        }

        $data_where = array(
            'id' => $this->_userID
        );

        if ($data) {
            $this->db->where($data_where);
            $msg = $this->db->update($this->cus_mstr, $data);
        } else {
            $msg = 1;
        }

        $data_where2 = array(
            'cus_id' => $this->_userID
        );

        if ($address) {
            $this->db->where($data_where2);
            $msg2 = $this->db->update($this->cus_mstr_profile, $address); 
        } else {
            $msg2 = 1;
        }

        if ($msg = 1 && $msg2 = 1) {

            return true;

        } else {

            return false;
        }
    }

    public function location()
    {
        $this->db->select('latitude,longitude');
        $this->db->from($this->cus_mstr);
        $this->db->where('id', $this->_userID);

        $query = $this->db->get();
        $result = array();
        $rows = array();
        if ($query->num_rows() > 0) {

            $result = $query->row();

            $data['latitude'] = $result->latitude;
            $data['longitude'] = $result->longitude;

            return $data;

        } else {

            return false;
        }
    }

    public function update_rating()
    {

        $this->db->select('rating,rating_count');
        $this->db->from($this->technician_info);
        $this->db->where('tech_id', $this->_techID);

        $query = $this->db->get();

        if ($query->num_rows() > 0) {

            $result = $query->row();

            $rating = $result->rating;
            $rating_count = $result->rating_count;

            if ($rating == 0 && $rating_count == 0) {
                $data['rating'] = $this->_rating;
                $data['rating_count'] = 1;
            } else {
                $data['rating'] = $this->round_up((($rating * $rating_count) + $this->_rating)/($rating_count+1),1);
                $data['rating_count'] = $rating_count+1;
            }

            $this->db->where('tech_id', $this->_techID);
            $msg = $this->db->update($this->technician_info, $data);

            if ($msg == 1) {
                return true;
            } else {
                return false;
            }

        } else {

            return false;
        }
    }

    public function update_cus_tech()
    {

        $this->db->select('tech_id');
        $this->db->from($this->cus_tech);
        $this->db->where('cus_id', $this->_userID);

        $query = $this->db->get();

        if ($query->num_rows() > 0) {

            $data['tech_id'] = $this->_techID;

            $this->db->where('cus_id', $this->_userID);

            $msg = $this->db->update($this->cus_tech, $data);

        } else {
            $msg = $this->db->insert($this->cus_tech, array('cus_id' => $this->_userID ,'tech_id' => $this->_techID));
        }

        if ($msg == 1) {
            return true;
        } else {
            return false;
        }

    }

    public function get_cus_tech()
    {

        $this->db->select('tech_id');
        $this->db->from($this->cus_tech);
        $this->db->where('cus_id', $this->_userID);

        $query = $this->db->get();

        if ($query->num_rows() > 0) {

            $result = $query->row();

            $tech_id = $result->tech_id;

            return $tech_id;

        } else {

            return false;
        }

    }

    public function round_up($value, $places=0) {
        if ($places < 0) { 
            $places = 0; 
        }
        $mult = pow(10, $places);

        return ceil($value * $mult) / $mult;
    }

    public function getIot_ID($id) {
        $this->db->select('iot_id');
        $this->db->from($this->tbl_cus_mstr);
        $this->db->where('id',$id);

        $query = $this->db->get();

        if ($query->num_rows() > 0) {

            $result = $query->row();

            return $result->iot_id;

        } else {

            return false;
        }

    }

    public function check_club_account_validate() {

        $data_where = array(
            'technician_info.tech_id' => $this->_userID,
            'technician_info.address !=' => '',
            'technician_info.district !=' => '',
            'technician_info.province !=' => '',
            'technician_info.postal_code !=' => '',
            'technician_info.profile_img !=' => '',
            'technician_info.telephone !=' => '',
            'cus_mstr.latitude !=' => '',
            'cus_mstr.longitude !=' => ''
        );

        $this->db->select('*');
        $this->db->from($this->tbl_technician_info);
        $this->db->join($this->cus_mstr, 'technician_info.tech_id = cus_mstr.id', 'left');
        $this->db->where($data_where);

        $query = $this->db->get();

        $data =  array();

        $data['validate'] = false;

        if ($query->num_rows() > 0) {

            $data['validate'] = true;

            return $data;

        } else {

            return $data;
        }
    }

    public function check_core_account_validate() {

        $data_where = array(
            'cus_mstr_profile.cus_id' => $this->_userID,
            'cus_mstr_profile.cus_addr1 !=' => '',
            'cus_mstr_profile.district !=' => '',
            'cus_mstr_profile.province !=' => '',
            'cus_mstr_profile.postal_code !=' => '',
            'cus_mstr_profile.pic0 !=' => '',
            'cus_mstr.telephone !=' => '',
            'cus_mstr.latitude !=' => '',
            'cus_mstr.longitude !=' => ''
        );

        $this->db->select('*');
        $this->db->from($this->tbl_cus_mstr_profile);
        $this->db->join($this->cus_mstr, 'cus_mstr_profile.cus_id = cus_mstr.id', 'left');
        $this->db->where($data_where);

        $query = $this->db->get();

        $data =  array();

        $data['validate'] = false;

        if ($query->num_rows() > 0) {

            $data['validate'] = true;

            return $data;

        } else {

            $data['validate'] = true;

            return $data;
        }
    }

    public function check_saijo_certification() {

        $data_where = array(
            'tech_id' => $this->_userID,
            'verify_id !=' => '',
        );

        $this->db->select('*');
        $this->db->from($this->tbl_saijo_verify);
        $this->db->where($data_where);

        $query = $this->db->get();

        $data =  array();

        $data['verified'] = false;

        if ($query->num_rows() > 0) {

            $result = $query->row();

            $expiry_date = $result->expiry_date;

            if ($expiry_date < date("Y-m-d") || $result->status == 0) {

                $this->db->where(array('tech_id' => $this->_userID));
                $this->db->update($this->tbl_saijo_verify, array('status' => 0));

                $data['verified'] = false;
            } else {
                $data['verified'] = true;
            }

            return $data;

        } else {

            return $data;
        }
    }

    public function saijo_certification() {

        $data_where = array(
            'tech_id' => $this->_userID,
        );

        $this->db->select('*');
        $this->db->from($this->tbl_saijo_verify);
        $this->db->where($data_where);

        $query = $this->db->get();

        $data =  array();

        if ($query->num_rows() > 0) {

            foreach ($query->result_array() as $row) {
                $rows = array(
                    'verify_id' => get_array_value($row, 'verify_id', ''),
                    'tech_id' => get_array_value($row, 'tech_id', ''),
                    'verified' => get_array_value($row, 'status', ''),
                    'approval_date' => get_array_value($row, 'approval_date', ''),
                    'expiry_date' => get_array_value($row, 'expiry_date', ''),
                );

                if ($rows['verified'] == 1) {
                    $rows['verified'] = true;
                } else {
                    $rows['verified'] = false;
                }

                if ($rows['expiry_date'] < date("Y-m-d")) {

                    $this->db->where(array('tech_id' => $this->_userID));
                    $this->db->update($this->tbl_saijo_verify, array('verified' => 0));

                    $rows['verified'] = 0;
                }

                $result[] = $rows;
            }

            return $result;

        } else {

            return false;
        }
    }

    public function check_info_tech(){
        $this->db->select('*');
        $this->db->from($this->technician_info);
        $this->db->where('tech_id', $this->_userID);

        $query = $this->db->get();

        if ($query->num_rows() > 0) {

            return true;

        } else {

            $this->db->insert($this->technician_info, array('tech_id' => $this->_userID));

            return true;
        }
    }  

    public function check_info_cus(){

        $this->db->select('*');
        $this->db->from($this->cus_mstr_profile);
        $this->db->where('cus_id', $this->_userID);

        $query = $this->db->get();
        $result = array();

        if ($query->num_rows() > 0) {

            return true;

        } else {

            $this->db->insert($this->cus_mstr_profile, array('cus_id' => $this->_userID));

            return true;
        }
    }

    public function update_token(){

        $this->db->where('id', $this->_userID);
        $this->db->update($this->tbl_cus_mstr, array('token' => $this->_token));
    }

    public function get_token(){

        $this->db->select('token');
        $this->db->from($this->tbl_cus_mstr);
        $this->db->where('id', $this->_userID);

        $query = $this->db->get();

        if ($query->num_rows() > 0) {

            $result = $query->row();

            $token = $result->token;

            return $token;

        } else {

            return false;
        }

    }

    public function getOperatorToken()
    {
        $this->db->select('*');
        $this->db->from($this->tbl_user_token);

        $query = $this->db->get();

        if ($query->num_rows() > 0) {

            foreach ($query->result_array() as $row) {
                $rows = array(
                    'token' => get_array_value($row, 'user_token', '')
                );

                $result[] = $rows;
            }

            return $result;

        } else {

            return false;
        }
    }

    public function check_apple_core($userData = array()){
        if(!empty($userData)){
            //check whether user data already exists in database with same oauth info
            $this->db->select($this->primaryKey.',email,user_role_id,iot_id');
            $this->db->from($this->cus_mstr);
            $this->db->where(array('email'=> $userData['email']));
            $this->db->where('status', 1);
            $prevQuery = $this->db->get();
            $prevCheck = $prevQuery->num_rows();

            if($prevCheck > 0 ){

                $result = $prevQuery->row();

                if ($result->user_role_id == 1 || $result->user_role_id == 4 || $result->user_role_id == 2) {
                    //update user data
                    $userData['updated_at'] = date("Y-m-d H:i:s");
                    $userData['device_id'] = $userData['device_id'];

                    if ($result->iot_id == 0) {
                        $userData['iot_id'] = $result->id;
                    }

                    $update = $this->db->update($this->cus_mstr, $userData, array('id' => $result->id));

                    //get user ID
                    $userID = $result->id;

                } else {
                    $userID = FALSE;
                }

            }else{
                //insert user data

                $userData['created_at']  = date("Y-m-d H:i:s");
                $userData['updated_at'] = date("Y-m-d H:i:s");
                $userData['device_id'] = $userData['device_id'];
                $insert = $this->db->insert($this->cus_mstr, $userData);

                //get user ID
                $userID = $this->db->insert_id();

                $this->db->update($this->cus_mstr, array('iot_id' => $userID), array('id' => $userID));

                $user_info['cus_id'] = $userID;

                $this->db->insert($this->cus_mstr_profile, $user_info);
                
            }
        }

        //return user ID
        return $userID?$userID:FALSE;
    }
}
