<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class User_dashboard_model extends MY_Model
{

    private $_user_id;
    private $_name;
    private $_lastname;
    private $_user_role;
    private $_status;
    private $_user_name;
    private $_password;
    private $_approval_date;
    private $_expire_date;
    private $_user_token;
    private $_dealer_store;
    private $_store_id;
    
    public function setUserID($user_id)
    {
        $this->_user_id = $user_id;
    }

    public function setName($name)
    {
        $this->_name = $name;
    }

    public function setLastname($lastname)
    {
        $this->_lastname = $lastname;
    }

    public function setTelephone($telephone)
    {
        $this->_telephone = $telephone;
    }

    public function setProfileImg($profile_img)
    {
        $this->_profile_img = $profile_img;
    }

    public function setAddress($address)
    {
        $this->_address = $address;
    }

    public function setDistrict($district)
    {
        $this->_district = $district;
    }

    public function setProvince($province)
    {
        $this->_province = $province;
    }

    public function setPostalCode($postal_code)
    {
        $this->_postal_code = $postal_code;
    }

    public function setLatitude($latitude)
    {
        $this->_latitude = $latitude;
    }

    public function setLongitude($longitude)
    {
        $this->_longitude = $longitude;
    }

    public function setUserRole($user_role)
    {
        $this->_user_role = $user_role;
    }

    public function setUserName($user_name)
    {
        $this->_user_name = $user_name;
    }

    public function setPassword($password)
    {
        $this->_password = $password;
    }

    public function setSaijoCertification($saijo_certification)
    {
        $this->_saijo_certification = $saijo_certification;
    }

    public function setStatus($status)
    {
        $this->_status = $status;
    }

    public function setDealerStore($dealer_store)
    {
        $this->_dealer_store = $dealer_store;
    }

    public function setApprovalDate($approval_date)
    {
        $this->_approval_date = $approval_date;
    }

    public function setExpireDate($expire_date)
    {
        $this->_expire_date = $expire_date;
    }

    public function setUserToken($user_token)
    {
        $this->_user_token = $user_token;
    }

    public function setStoreID($store_id)
    {
        $this->_store_id = $store_id;
    }

    public function getUserList($data = array())
    {

        $user_role_id = array(1,8,9);

        $this->db->select('cus_mstr.id as id,cus_mstr.name as name,lastname,user_role_id,user_role.name as user_role_name ,status');
        $this->db->from($this->tbl_cus_mstr);
        $this->db->join($this->tbl_user_role, 'cus_mstr.user_role_id = user_role.id', 'left');
        $this->db->where_in('user_role_id', $user_role_id);
        $this->db->where(array('cus_mstr.name !=' => ''));

        if (isset($data['filter_user_id']) && $data['filter_user_id'] !== '') {
            $this->db->where(array(
                'cus_mstr.id' => $data['filter_user_id']
            ));
        }

        if (isset($data['filter_name']) && $data['filter_name'] !== '') {
            $this->db->where(array(
                'cus_mstr.name' => $data['filter_name']
            ));
        }

        if (isset($data['filter_lastname']) && $data['filter_lastname'] !== '') {
            $this->db->where(array(
                'cus_mstr.lastname' => $data['filter_lastname']
            ));
        }

        if (isset($data['filter_user_role']) && $data['filter_user_role'] !== '') {
            $this->db->where(array(
                'cus_mstr.user_role_id' => $data['filter_user_role']
            ));
        }

        if (isset($data['filter_status']) && $data['filter_status'] !== '') {
            $this->db->where(array(
                'cus_mstr.status' => $data['filter_status']
            ));
        }


        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $this->db->limit((int)$data['limit'],(int)$data['start']);
        }
        
        $this->db->order_by('id', 'DESC');

        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $rows = array(
                    'id' => get_array_value($row, 'id', ''),
                    'name' => get_array_value($row, 'name', '-'),
                    'lastname' => get_array_value($row, 'lastname', ''),
                    'user_role' => get_array_value($row, 'user_role_id', ''),
                    'user_role_name' => get_array_value($row, 'user_role_name', ''),
                    'status' => get_array_value($row, 'status', ''),
                );

                $result[] = $rows;
            }

        } else {
            return false;
        }

        return $result;

    }

    public function getUserListTotal($data = array())
    {
        $user_role_id = array(1,8,9);

        $this->db->select('count(*) as total');
        $this->db->from($this->tbl_cus_mstr);
        $this->db->where_in('user_role_id', $user_role_id);

        if (isset($data['filter_user_id']) && $data['filter_user_id'] !== '') {
            $this->db->where(array(
                'id' => $data['filter_user_id']
            ));
        }

        if (isset($data['filter_name']) && $data['filter_name'] !== '') {
            $this->db->where(array(
                'name' => $data['filter_name']
            ));
        }

        if (isset($data['filter_lastname']) && $data['filter_lastname'] !== '') {
            $this->db->where(array(
                'lastname' => $data['filter_lastname']
            ));
        }

        if (isset($data['filter_user_role']) && $data['filter_user_role'] !== '') {
            $this->db->where(array(
                'user_role_id' => $data['filter_user_role']
            ));
        }

        if (isset($data['filter_status']) && $data['filter_status'] !== '') {
            $this->db->where(array(
                'status' => $data['filter_status']
            ));
        }
        
        $this->db->order_by('id', 'DESC');

        $query = $this->db->get();

        if ($query->num_rows() > 0) {

            $result = $query->row();

            $total = $result->total;

            return $total;

        } else {
            return 0;
        }

    }

    public function getUserInfo()
    {
        $this->db->select('email,name,lastname,user_role_id,status');
        $this->db->from($this->tbl_cus_mstr);
        $this->db->where('id', $this->_user_id);

        $query = $this->db->get();

        if ($query->num_rows() > 0) {

            $result = $query->row();

            $data['user_name'] = $result->email;
            $data['name'] = $result->name;
            $data['lastname'] = $result->lastname;
            $data['user_role'] = $result->user_role_id;
            $data['status'] = $result->status;

            return $data;

        } else {
            return false;
        }
    }

    public function getUserRole()
    {

        $user_role_id = array(1,8,9);

        $this->db->select('*');
        $this->db->from($this->tbl_user_role);
        $this->db->where_in('id', $user_role_id);

        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $rows = array(
                    'id' => get_array_value($row, 'id', ''),
                    'name' => get_array_value($row, 'name', '-')
                );

                $result[] = $rows;
            }

            return $result;

        } else {
            return false;
        }
    }

    public function updateUserAccount()
    {

        $data = array(
            'name' => $this->_name,
            'lastname' => $this->_lastname,
            'user_role_id' => $this->_user_role,
            'email' => $this->_user_name,
            'status' => $this->_status,
        );

        if (isset($this->_password)) {
            $data['password'] = $this->hash($this->_password);
        }

        $this->db->where(array('id' => $this->_user_id));
        $this->db->update($this->tbl_cus_mstr, $data);
    }

    public function addUserAccount()
    {

        $data = array(
            'name' => $this->_name,
            'lastname' => $this->_lastname,
            'user_role_id' => $this->_user_role,
            'email' => $this->_user_name,
            'status' => $this->_status,
            'password' => $this->hash($this->_password),
        );

        $this->db->select('*');
        $this->db->from($this->tbl_cus_mstr);
        $this->db->where(array('email' => $this->_user_name));

        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return false;
        } else {
            $this->db->insert($this->tbl_cus_mstr, $data);
            return true ;
        }
    }

    public function hash($password)
    {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        return $hash;
    }

    public function getTechnicianList($data = array())
    {

        $this->db->select('cus_mstr.id as id,cus_mstr.name as name,lastname,cus_mstr.telephone as telephone,cus_mstr.email,latitude,longitude,province,rating,technician_info.saijo_certification,cus_mstr.status,technician_info.dealer_store,technician_info.address,district,postal_code,profile_img');
        $this->db->from($this->tbl_cus_mstr);
        $this->db->join($this->tbl_technician_info, 'cus_mstr.id = technician_info.tech_id', 'left');
        $this->db->where(array('user_role_id' => 3));

        if (isset($data['filter_technician_id']) && $data['filter_technician_id'] !== '') {
            $this->db->where(array(
                'cus_mstr.id' => $data['filter_technician_id']
            ));
        }

        if (isset($data['filter_technician']) && $data['filter_technician'] !== '') {
            $str_cus = explode(" ",$data['filter_technician']);

            if (isset($str_cus[0])) {
                $this->db->where(array(
                    'cus_mstr.name' => $str_cus[0],
                ));
            }

            if (isset($str_cus[1])) {
                $this->db->where(array(
                    'cus_mstr.lastname' => $str_cus[1]
                ));
            }
        }

        if (isset($data['filter_telephone']) && $data['filter_telephone'] !== '') {
            $this->db->where(array(
                'cus_mstr.telephone' => $data['filter_telephone']
            ));
        }

        if (isset($data['filter_email']) && $data['filter_email'] !== '') {
            $this->db->where(array(
                'cus_mstr.email' => $data['filter_email']
            ));
        }

        if (isset($data['filter_province']) && $data['filter_province'] !== '') {
            $this->db->where(array(
                'province' => $data['filter_province']
            ));
        }

        if (isset($data['filter_dealer_store']) && $data['filter_dealer_store'] !== '') {
            $this->db->where(array(
                'technician_info.dealer_store' => $data['filter_dealer_store']
            ));
        }

        if (isset($data['filter_rating']) && $data['filter_rating'] !== '') {
            $this->db->where(array(
                'rating' => $data['filter_rating']
            ));
        }

        if (isset($data['filter_saijo_certification']) && $data['filter_saijo_certification'] !== '') {
            $this->db->where(array(
                'technician_info.saijo_certification' => $data['filter_saijo_certification']
            ));
        }

        if (isset($data['filter_status']) && $data['filter_status'] !== '') {
            $this->db->where(array(
                'cus_mstr.status' => $data['filter_status']
            ));
        }

        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $this->db->limit((int)$data['limit'],(int)$data['start']);
        }

        $this->db->order_by('id', 'DESC');

        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $rows = array(
                    'tech_id' => get_array_value($row, 'id', ''),
                    'name' => get_array_value($row, 'name', '-'),
                    'lastname' => get_array_value($row, 'lastname', ''),
                    'telephone' => get_array_value($row, 'telephone', '-'),
                    'latitude' => get_array_value($row, 'latitude', ''),
                    'longitude' => get_array_value($row, 'longitude', ''),
                    'province' => get_array_value($row, 'province', '-'),
                    'rating' => get_array_value($row, 'rating', '0'),
                    'saijo_certification' => get_array_value($row, 'saijo_certification', '-'),
                    'status' => get_array_value($row, 'status', ''),
                    'dealer_store' => get_array_value($row, 'dealer_store', '-'),
                    'district' => get_array_value($row, 'district', '-'),
                    'postal_code' => get_array_value($row, 'postal_code', '-'),
                    'profile_img' => get_array_value($row, 'profile_img', 'user.png'),
                    'email' => get_array_value($row, 'email', ''),
                    'address' => get_array_value($row, 'address', ''),
                );

                $result[] = $rows;
            }

        } else {
            return false;
        }

        return $result;
    }

    public function getTechnicianListTotal($data = array())
    {
        $this->db->select('cus_mstr.id as id, count(cus_mstr.name) as total,lastname,cus_mstr.telephone as telephone,cus_mstr.email,latitude,longitude,province,rating,saijo_certification,cus_mstr.status,technician_info.dealer_store');
        $this->db->from($this->tbl_cus_mstr);
        $this->db->join($this->tbl_technician_info, 'cus_mstr.id = technician_info.tech_id', 'left');
        $this->db->where(array('user_role_id' => 3));

        if (isset($data['filter_technician_id']) && $data['filter_technician_id'] !== '') {
            $this->db->where(array(
                'cus_mstr.id' => $data['filter_technician_id']
            ));
        }

        if (isset($data['filter_technician_id']) && $data['filter_technician_id'] !== '') {
            $this->db->where(array(
                'cus_mstr.id' => $data['filter_technician_id']
            ));
        }

        if (isset($data['filter_technician']) && $data['filter_technician'] !== '') {
            $str_cus = explode(" ",$data['filter_technician']);

            if (isset($str_cus[0])) {
                $this->db->where(array(
                    'cus_mstr.name' => $str_cus[0],
                ));
            }

            if (isset($str_cus[1])) {
                $this->db->where(array(
                    'cus_mstr.lastname' => $str_cus[1]
                ));
            }
        }

        if (isset($data['filter_telephone']) && $data['filter_telephone'] !== '') {
            $this->db->where(array(
                'cus_mstr.telephone' => $data['filter_telephone']
            ));
        }

        if (isset($data['filter_email']) && $data['filter_email'] !== '') {
            $this->db->where(array(
                'cus_mstr.email' => $data['filter_email']
            ));
        }

        if (isset($data['filter_province']) && $data['filter_province'] !== '') {
            $this->db->where(array(
                'province' => $data['filter_province']
            ));
        }

        if (isset($data['filter_rating']) && $data['filter_rating'] !== '') {
            $this->db->where(array(
                'rating' => $data['filter_rating']
            ));
        }

        if (isset($data['filter_dealer_store']) && $data['filter_dealer_store'] !== '') {
            $this->db->where(array(
                'technician_info.dealer_store' => $data['filter_dealer_store']
            ));
        }

        if (isset($data['filter_saijo_certification']) && $data['filter_saijo_certification'] !== '') {
            $this->db->where(array(
                'technician_info.saijo_certification' => $data['filter_saijo_certification']
            ));
        }

        if (isset($data['filter_status']) && $data['filter_status'] !== '') {
            $this->db->where(array(
                'cus_mstr.status' => $data['filter_status']
            ));
        }

        $this->db->order_by('id', 'DESC');

        $query = $this->db->get();

        if ($query->num_rows() > 0) {

            $result = $query->row();

            $total = $result->total;

            return $total;

        } else {
            return 0;
        }
    }

    public function getTechnicianInfo()
    {
        $this->db->select('name,lastname,cus_mstr.telephone as telephone,address,district,province,postal_code,latitude,longitude,rating,profile_img,saijo_certification,status,email,technician_info.dealer_store');
        $this->db->from($this->tbl_cus_mstr);
        $this->db->join($this->tbl_technician_info,'cus_mstr.id = technician_info.tech_id', 'left');
        $this->db->where(array('cus_mstr.id' => $this->_user_id));

        $query = $this->db->get();

        if ($query->num_rows() > 0) {

            $result = $query->row();

            $data['name'] = $result->name;
            $data['lastname'] = $result->lastname;
            $data['telephone'] = $result->telephone;
            $data['address'] = $result->address;
            $data['district'] = $result->district;
            $data['province'] = $result->province;
            $data['postal_code'] = $result->postal_code;
            $data['latitude'] = $result->latitude;
            $data['longitude'] = $result->longitude;
            $data['rating'] = $result->rating;
            $data['profile_img'] = $result->profile_img;
            $data['saijo_certification'] = $result->saijo_certification;
            $data['status'] = $result->status;
            $data['user_name'] = $result->email;
            $data['dealer_store'] = $result->dealer_store;

            if (!$data['profile_img']) {
                $data['profile_img'] = 'user.png';
            }

            return $data;

        } else {
            return false;
        }
    }

    public function updateTechnicianInfo()
    {

        $data = array(
            'name' => $this->_name,
            'lastname' => $this->_lastname,
            'telephone' => $this->_telephone,
            'latitude' => $this->_latitude,
            'longitude' => $this->_longitude,
            'email' => $this->_user_name,
            'status' => $this->_status,
        );

        $info = array(
            'telephone' => $this->_telephone,
            'address' => $this->_address,
            'district' => $this->_district,
            'province' => $this->_province,
            'postal_code' => $this->_postal_code,
            'saijo_certification' => $this->_saijo_certification,
            'dealer_store' => $this->_dealer_store
        );

        if (isset($this->_password)) {
            //$data['password'] = $this->hash($this->_password);
            $data['password'] = hash("sha256",$this->_password);
        }

        if (isset($this->_profile_img)) {
            $info['profile_img'] = $this->_profile_img;
        }

        $this->db->select('email');
        $this->db->from($this->tbl_cus_mstr);
        $this->db->where(array('id' => $this->_user_id, 'email !=' => $this->_user_name));

        $query = $this->db->get();

        if ($query->num_rows() > 0) {

            return false;

        } else {

            $this->db->where(array('id' => $this->_user_id));
            $this->db->update($this->tbl_cus_mstr, $data);

            $this->db->where(array('tech_id' => $this->_user_id));
            $this->db->update($this->tbl_technician_info, $info);

            return true;

        }
    }

    public function getCustomerList($data = array())
    {

        $this->db->select('cus_mstr.id as id,cus_mstr.name as name,lastname,cus_mstr.email,cus_mstr.telephone as telephone,latitude,longitude,province,status,cus_mstr.email');
        $this->db->from($this->tbl_cus_mstr);
        $this->db->join($this->tbl_cus_mstr_profile, 'cus_mstr.id = cus_mstr_profile.cus_id', 'left');
        $this->db->where_in('user_role_id',array(2,4));

        if (isset($data['filter_customer_id']) && $data['filter_customer_id'] !== '') {
            $this->db->where(array(
                'cus_mstr.id' => $data['filter_customer_id']
            ));
        }

        if (isset($data['filter_customer']) && $data['filter_customer'] !== '') {
            $str_cus = explode(" ",$data['filter_customer']);

            if (isset($str_cus[0])) {
                $this->db->where(array(
                    'cus_mstr.name' => $str_cus[0],
                ));
            }

            if (isset($str_cus[1])) {
                $this->db->where(array(
                    'cus_mstr.lastname' => $str_cus[1]
                ));
            }
        }

        if (isset($data['filter_email']) && $data['filter_email'] !== '') {
            $this->db->where(array(
                'cus_mstr.email' => $data['filter_email']
            ));
        }

        if (isset($data['filter_telephone']) && $data['filter_telephone'] !== '') {
            $this->db->where(array(
                'cus_mstr.telephone' => $data['filter_telephone']
            ));
        }

        if (isset($data['filter_province']) && $data['filter_province'] !== '') {
            $this->db->where(array(
                'province' => $data['filter_province']
            ));
        }

        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $this->db->limit((int)$data['limit'],(int)$data['start']);
        }

        $this->db->order_by('id', 'DESC');

        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $rows = array(
                    'cus_id' => get_array_value($row, 'id', ''),
                    'name' => get_array_value($row, 'name', '-'),
                    'lastname' => get_array_value($row, 'lastname', ''),
                    'telephone' => get_array_value($row, 'telephone', '-'),
                    'latitude' => get_array_value($row, 'latitude', ''),
                    'longitude' => get_array_value($row, 'longitude', ''),
                    'province' => get_array_value($row, 'province', '-'),
                    'status' => get_array_value($row, 'status', ''),
                    'email' => get_array_value($row, 'email', ''),
                    'profile_img' => get_array_value($row, 'pic0', 'user.png')
                );

                $result[] = $rows;
            }

        } else {
            return false;
        }

        return $result;
    }

    public function getCustomerListTotal($data = array())
    {
        $this->db->select('cus_mstr.id as id, count(cus_mstr.name) as total,lastname,cus_mstr.email,cus_mstr.telephone as telephone,latitude,longitude,province,status');
        $this->db->from($this->tbl_cus_mstr);
        $this->db->join($this->tbl_technician_info, 'cus_mstr.id = technician_info.tech_id', 'left');
        $this->db->where_in('user_role_id',array(2,4));

        if (isset($data['filter_customer_id']) && $data['filter_customer_id'] !== '') {
            $this->db->where(array(
                'cus_mstr.id' => $data['filter_customer_id']
            ));
        }

        if (isset($data['filter_customer']) && $data['filter_customer'] !== '') {
            $str_cus = explode(" ",$data['filter_customer']);

            if (isset($str_cus[0])) {
                $this->db->where(array(
                    'cus_mstr.name' => $str_cus[0],
                ));
            }

            if (isset($str_cus[1])) {
                $this->db->where(array(
                    'cus_mstr.lastname' => $str_cus[1]
                ));
            }
        }

        if (isset($data['filter_telephone']) && $data['filter_telephone'] !== '') {
            $this->db->where(array(
                'cus_mstr.telephone' => $data['filter_telephone']
            ));
        }

        if (isset($data['filter_email']) && $data['filter_email'] !== '') {
            $this->db->where(array(
                'cus_mstr.email' => $data['filter_email']
            ));
        }

        if (isset($data['filter_province']) && $data['filter_province'] !== '') {
            $this->db->where(array(
                'province' => $data['filter_province']
            ));
        }

        $this->db->order_by('id', 'DESC');

        $query = $this->db->get();

        if ($query->num_rows() > 0) {

            $result = $query->row();

            $total = $result->total;

            return $total;

        } else {
            return 0;
        }
    }

    public function getCustomerInfo()
    {
        $this->db->select('name,lastname,cus_mstr.telephone as telephone,cus_addr1,district,province,postal_code,latitude,longitude,pic0,status,cus_mstr.email');
        $this->db->from($this->tbl_cus_mstr);
        $this->db->join($this->tbl_cus_mstr_profile,'cus_mstr.id = cus_mstr_profile.cus_id', 'left');
        $this->db->where(array('cus_mstr.id' => $this->_user_id));

        $query = $this->db->get();

        if ($query->num_rows() > 0) {

            $result = $query->row();

            $data['name'] = $result->name;
            $data['lastname'] = $result->lastname;
            $data['telephone'] = $result->telephone;
            $data['address'] = $result->cus_addr1;
            $data['district'] = $result->district;
            $data['province'] = $result->province;
            $data['postal_code'] = $result->postal_code;
            $data['latitude'] = $result->latitude;
            $data['longitude'] = $result->longitude;
            $data['profile_img'] = $result->pic0;
            $data['status'] = $result->status;
            $data['user_name'] = $result->email;

            return $data;

        } else {
            return false;
        }
    }

    public function updateCustomerInfo()
    {

        $data = array(
            'name' => $this->_name,
            'lastname' => $this->_lastname,
            'telephone' => $this->_telephone,
            'latitude' => $this->_latitude,
            'longitude' => $this->_longitude,
            'email' => $this->_user_name,
            'status' => $this->_status,
        );

        $info = array(
            'cus_addr1' => $this->_address,
            'district' => $this->_district,
            'province' => $this->_province,
            'postal_code' => $this->_postal_code
        );

        if (isset($this->_password)) {
            //$data['password'] = $this->hash($this->_password);
            $data['password'] = hash("sha256",$this->_password);
        }

        if (isset($this->_profile_img)) {
            $info['pic0'] = $this->_profile_img;
        }

        $this->db->select('email');
        $this->db->from($this->tbl_cus_mstr);
        $this->db->where(array('id' => $this->_user_id, 'email !=' => $this->_user_name));

        $query = $this->db->get();

        if ($query->num_rows() > 0) {

            return false;

        } else {

            $this->db->where(array('id' => $this->_user_id));
            $this->db->update($this->tbl_cus_mstr, $data);

            $this->db->where(array('cus_id' => $this->_user_id));
            $this->db->update($this->tbl_cus_mstr_profile, $info);

            return true;

        }
    }

    public function getTechnicianVerifyList($data = array())
    {

        $this->db->select('cus_mstr.id as id,name,lastname,approval_date,expiry_date,verify_id,saijo_verify.status');
        $this->db->from($this->tbl_saijo_verify);
        $this->db->join($this->tbl_cus_mstr, 'cus_mstr.id = saijo_verify.tech_id', 'left');
        $this->db->where(array('user_role_id' => 3));

        if (isset($data['filter_technician_id']) && $data['filter_technician_id'] !== '') {
            $this->db->where(array(
                'cus_mstr.id' => $data['filter_technician_id']
            ));
        }

        if (isset($data['filter_technician']) && $data['filter_technician'] !== '') {
            $str_cus = explode(" ",$data['filter_technician']);

            if (isset($str_cus[0])) {
                $this->db->where(array(
                    'cus_mstr.name' => $str_cus[0],
                ));
            }

            if (isset($str_cus[1])) {
                $this->db->where(array(
                    'cus_mstr.lastname' => $str_cus[1]
                ));
            }
        }

        if (isset($data['filter_saijo_certification']) && $data['filter_saijo_certification'] !== '') {
            $this->db->where(array(
                'saijo_verify.verify_id' => $data['filter_saijo_certification']
            ));
        }

        if (isset($data['filter_approved_date']) && $data['filter_approved_date'] !== '') {

            $date = str_replace('/', '-', $data['filter_approved_date'] );
            $newDate = date("Y-m-d", strtotime($date));

            $this->db->where(array(
                'saijo_verify.approval_date' => $newDate
            ));
        }

        if (isset($data['filter_expire_date']) && $data['filter_expire_date'] !== '') {

            $date = str_replace('/', '-', $data['filter_expire_date'] );
            $newDate = date("Y-m-d", strtotime($date));

            $this->db->where(array(
                'saijo_verify.expiry_date' => $newDate
            ));
        }

        if (isset($data['filter_status']) && $data['filter_status'] !== '') {
            $this->db->where(array(
                'saijo_verify.status' => $data['filter_status']
            ));
        }

        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $this->db->limit((int)$data['limit'],(int)$data['start']);
        }

        $this->db->order_by('id', 'DESC');

        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $rows = array(
                    'tech_id' => get_array_value($row, 'id', ''),
                    'name' => get_array_value($row, 'name', '-'),
                    'lastname' => get_array_value($row, 'lastname', ''),
                    'saijo_certification' => get_array_value($row, 'verify_id', '-'),
                    'approved_date' => get_array_value($row, 'approval_date', ''),
                    'expiry_date' => get_array_value($row, 'expiry_date', ''),
                    'status' => get_array_value($row, 'status', '')
                );

                if ($rows['approved_date'] != '-') {
                    $date = date_create($rows['approved_date']);
                    $rows['approved_date'] = date_format($date,"d/m/Y");
                }

                if ($rows['expiry_date'] != '-') {
                    $date = date_create($rows['expiry_date']);
                    $rows['expiry_date'] = date_format($date,"d/m/Y");
                }

                $result[] = $rows;
            }

        } else {
            return false;
        }

        return $result;
    }

    public function getTechnicianVerifyListTotal($data = array())
    {
        $this->db->select('cus_mstr.id as id,count(name) as total,lastname,verify_id,saijo_verify.status');
        $this->db->from($this->tbl_saijo_verify);
        $this->db->join($this->tbl_cus_mstr, 'cus_mstr.id = saijo_verify.tech_id', 'left');
        $this->db->where(array('user_role_id' => 3));

        if (isset($data['filter_technician_id']) && $data['filter_technician_id'] !== '') {
            $this->db->where(array(
                'cus_mstr.id' => $data['filter_technician_id']
            ));
        }

        if (isset($data['filter_technician']) && $data['filter_technician'] !== '') {
            $str_cus = explode(" ",$data['filter_technician']);

            if (isset($str_cus[0])) {
                $this->db->where(array(
                    'cus_mstr.name' => $str_cus[0],
                ));
            }

            if (isset($str_cus[1])) {
                $this->db->where(array(
                    'cus_mstr.lastname' => $str_cus[1]
                ));
            }
        }

        if (isset($data['filter_saijo_certification']) && $data['filter_saijo_certification'] !== '') {
            $this->db->where(array(
                'saijo_verify.verify_id' => $data['filter_saijo_certification']
            ));
        }

        if (isset($data['filter_approved_date']) && $data['filter_approved_date'] !== '') {

            $date = str_replace('/', '-', $data['filter_approved_date'] );
            $newDate = date("Y-m-d", strtotime($date));

            $this->db->where(array(
                'saijo_verify.approval_date' => $newDate
            ));
        }

        if (isset($data['filter_expire_date']) && $data['filter_expire_date'] !== '') {

            $date = str_replace('/', '-', $data['filter_expire_date'] );
            $newDate = date("Y-m-d", strtotime($date));

            $this->db->where(array(
                'saijo_verify.expiry_date' => $newDate
            ));
        }

        if (isset($data['filter_status']) && $data['filter_status'] !== '') {
            $this->db->where(array(
                'saijo_verify.status' => $data['filter_status']
            ));
        }

        $this->db->order_by('id', 'DESC');

        $query = $this->db->get();

        if ($query->num_rows() > 0) {

            $result = $query->row();

            $total = $result->total;

            return $total;

        } else {
            return 0;
        }
    }

    public function getUnverified()
    {

        $data_where = array(
            'user_role_id' => 3,
            'cus_mstr.status' => 1,
            'name !=' => '',
            'saijo_certification' => ''
        );

        $this->db->select('cus_mstr.id as id,name,lastname,');
        $this->db->from($this->tbl_cus_mstr);
        $this->db->join($this->tbl_technician_info, 'cus_mstr.id = technician_info.tech_id', 'left');
        $this->db->where($data_where);

        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $rows = array(
                    'tech_id' => get_array_value($row, 'id', ''),
                    'name' => get_array_value($row, 'name', '-'),
                    'lastname' => get_array_value($row, 'lastname', ''),
                );

                $result[] = $rows;
            }

        } else {
            return false;
        }

        return $result;
    }

    public function addVerify()
    {

        $data = array(
            'tech_id' => $this->_user_id,
            'verify_id' => $this->_saijo_certification,
            'status' => $this->_status,
            'approval_date' => $this->_approval_date,
            'expiry_date' => $this->_expire_date,
        );

        $this->db->insert($this->tbl_saijo_verify,$data);

        $this->db->where(array('tech_id' => $this->_user_id));
        $this->db->update($this->tbl_technician_info,array('saijo_certification' => $this->_saijo_certification));
    }

    public function updateVerify()
    {

        $data = array(
            'verify_id' => $this->_saijo_certification,
            'status' => $this->_status,
            'approval_date' => $this->_approval_date,
            'expiry_date' => $this->_expire_date,
        );

        $this->db->where(array('tech_id' => $this->_user_id));
        $this->db->update($this->tbl_saijo_verify,$data);

        $this->db->where(array('tech_id' => $this->_user_id));
        $this->db->update($this->tbl_technician_info,array('saijo_certification' => $this->_saijo_certification));
    }

    public function getVerifyInfo()
    {
        $this->db->select('name,lastname,verify_id,approval_date,expiry_date,saijo_verify.status as status');
        $this->db->from($this->tbl_cus_mstr);
        $this->db->join($this->tbl_saijo_verify,'cus_mstr.id = saijo_verify.tech_id', 'left');
        $this->db->where(array('saijo_verify.tech_id' => $this->_user_id));

        $query = $this->db->get();

        if ($query->num_rows() > 0) {

            $result = $query->row();

            $data['name'] = $result->name;
            $data['lastname'] = $result->lastname;
            $data['saijo_certification'] = $result->verify_id;
            $data['status'] = $result->status;

            $app_date = str_replace('-', '/', $result->approval_date);
            $data['approved_date'] = date("d/m/Y", strtotime($app_date));

            $exp_date = str_replace('-', '/', $result->expiry_date);
            $data['expire_date'] = date("d/m/Y", strtotime($exp_date));

            return $data;

        } else {
            return false;
        }
    }

    public function updateToken()
    {
        $data = array(
            'user_id' => $this->_user_id,
            'user_token' => $this->_user_token,
            'update_datetime' => date('Y-m-d H:i:s')
        );

        $this->db->select('*');
        $this->db->from($this->tbl_user_token);
        $this->db->where(array('user_id' => $this->_user_id));

        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $this->db->where(array('user_id' => $this->_user_id));
            $this->db->update($this->tbl_user_token,$data);
        } else {
            $this->db->insert($this->tbl_user_token,$data);
        }
    }

    public function getDealerList($data = array())
    {

        $this->db->select('*');
        $this->db->from($this->tbl_dealer_store);

        if (isset($data['filter_dealer_id']) && $data['filter_dealer_id'] !== '') {
            $this->db->where(array(
                'id' => $data['filter_dealer_id']
            ));
        }

        if (isset($data['filter_dealer_store']) && $data['filter_dealer_store'] !== '') {
            $this->db->where(array(
                'store_name' => $data['filter_dealer_store']
            ));
        }

        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $rows = array(
                    'dealer_id'  => get_array_value($row, 'id', ''),
                    'dealer_store'  => get_array_value($row, 'store_name', '')
                );

                $result[] = $rows;
            }

            return $result;

        } else {

            return false;
        }
    }

    public function getDealerListTotal($data = array())
    {

        $this->db->select('count(id) as total');
        $this->db->from($this->tbl_dealer_store);

        if (isset($data['filter_dealer_id']) && $data['filter_dealer_id'] !== '') {
            $this->db->where(array(
                'id' => $data['filter_dealer_id']
            ));
        }

        if (isset($data['filter_dealer_store']) && $data['filter_dealer_store'] !== '') {
            $this->db->where(array(
                'store_name' => $data['filter_dealer_store']
            ));
        }

        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $result = $query->row();

            return $result->total;

        } else {

            return 0;
        }
    }

    public function getStoreInfo()
    {

        $this->db->select('*');
        $this->db->from($this->tbl_dealer_store);
        $this->db->where(array('id' => $this->_store_id));

        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $result = $query->row();

            $data['dealer_id'] = $result->id;
            $data['dealer_store'] = $result->store_name;

            return $data;

        } else {

            return 0;
        }

    }

    public function updateDealerStore()
    {

        $this->db->select('*');
        $this->db->from($this->tbl_dealer_store);
        $this->db->where(array('store_name' => $this->_dealer_store));

        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return false;
        } else {
            $this->db->where(array('id' => $this->_store_id));
            $this->db->update($this->tbl_dealer_store,array('store_name' => $this->_dealer_store));

            return true;
        }
    }

    public function addDealerStore()
    {

        $this->db->select('*');
        $this->db->from($this->tbl_dealer_store);
        $this->db->where(array('store_name' => $this->_dealer_store));

        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return false;
        } else {
            $this->db->insert($this->tbl_dealer_store,array('store_name' => $this->_dealer_store));
            return true;
        }
    }

    public function deleteDealerStore()
    {

        $this->db->where(array('id' => $this->_store_id));
        $msg = $this->db->delete($this->tbl_dealer_store);

        if ($msg == 1) {
            return true;
        } else {
            return false;
        }
    }

} //End of Class


?>
