<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Auth_model extends MY_Model
{
    // Declaration of a variables
    private $_userID;
    private $_userName;
    private $_firstName;
    private $_lastName;
    private $_email;
    private $_password;
    private $_contactNo;
    private $_companyName;
    private $_address;
    private $_systemID;
    private $_dob;
    private $_verificationCode;
    private $_timeStamp;
    private $_status;
    private $_token;
    private $_user_role_id;
    private $_cus_group_id;
    private $_device_id;
    private $_version;
    private $_device;
    private $_old_cus;


    public function __construct()
    {
        parent::__construct();
    }


    //Declaration of a methods
    public function setUserID($userID)
    {
        $this->_userID = $userID;
    }

    public function getUserId(){
        return $this->_userID;
    }

    public function setUserName($userName)
    {
        $this->_userName = $userName;
    }

    public function setFirstname($firstName)
    {
        $this->_firstName = $firstName;
    }

    public function setLastName($lastName)
    {
        $this->_lastName = $lastName;
    }

    public function setEmail($email)
    {
        $this->_email = $email;
    }

    public function setContactNo($contactNo)
    {
        $this->_contactNo = $contactNo;
    }

    public function setPassword($password)
    {
        $this->_password = $password;
    }

    public function setAddress($address){
        $this->_address = $address;
    }

    public function setDOB($dob)
    {
        $this->_dob = $dob;
    }

    public function setSystemId($system_id)
    {
        $this->_systemID = $system_id;
    }

    public function setVerificationCode($verificationCode)
    {
        $this->_verificationCode = $verificationCode;
    }

    public function setTimeStamp($timeStamp)
    {
        $this->_timeStamp = $timeStamp;
    }

    public function setStatus($status)
    {
        $this->_status = $status;
    }

    public function setToken($token)
    {
        $this->_token = $token;
    }

    public function setRole($roleId)
    {
        $this->_user_role_id = $roleId;
    }

    public function setGroup($groupId)
    {
        $this->_cus_group_id = $groupId;
    }

    public function setCompanyName($companyName)
    {
        $this->_companyName = $companyName;
    }

    public function setDeviceId($deviceId)
    {
        $this->_deviceId = $deviceId;
    }

    public function setTel($telephone)
    {
        $this->_telephone = $telephone;
    }

    public function setDevice($device)
    {
        $this->_device = $device;
    }

    public function setVersion($version)
    {
        $this->_version = $version;
    }

    public function setApp($app)
    {
        $this->_app = $app;
    }

    //create new user
    public function create()
    {
        //$hash = $this->hash($this->_password);

        if ($this->_user_role_id == 3) {
            $hash = $this->hash($this->_password);
        } else {
            $hash = hash("sha256",$this->_password);
        }

        $data = array();
        //$data_eapp = array();

        $address = array();

        if (!is_blank($this->_email)) {
            $data['email'] = $this->_email;
            //$data_eapp['email'] = $this->_email;
        }
        if (!is_blank($this->_userName)) {
            $data['user_name'] = $this->_userName;
        }
        if(!is_blank($this->_password)){
            $data['password'] = $hash;
            //$data_eapp['password'] = hash("sha256",$this->_password);
        }
        if(!is_blank($this->_firstName)){
            $data['name'] = $this->_firstName;
            //$data_eapp['name'] = $this->_firstName;
        }
        if(!is_blank($this->_lastName)){
            $data['lastname'] = $this->_lastName;
            //$data_eapp['lastname'] = $this->_lastName;
        }
        if(!is_blank($this->_telephone)){
            $data['telephone'] = $this->_telephone;
            //$data_eapp['telephone'] = $this->_telephone;
        }
        if(!is_blank($this->_user_role_id)){
            $data['user_role_id'] = $this->_user_role_id;
            //$data_eapp['user_role_id'] = $this->_user_role_id;
        }
        if(!is_blank($this->_cus_group_id)){
            $data['cus_group_id'] = 0;
        }
        if(!is_blank($this->_verificationCode)){
            $data['verification_code'] = $this->_verificationCode;
        }
        if(!is_blank($this->_status)){
            $data['status'] = $this->_status;
        }
        if(!is_blank($this->_timeStamp)){
            $data['created_at'] = $this->_timeStamp;
            $data['updated_at'] = $this->_timeStamp;

            //$data_eapp['created_at'] = $this->_timeStamp;
            //$data_eapp['updated_at'] = $this->_timeStamp;
        }

        if(!is_blank($this->_deviceId)){
            $log['device_id'] = $this->_deviceId;
            $data['device_id'] = $this->_deviceId;
        }

        if ($data['user_role_id'] == 3) {
            $this->db->insert($this->tbl_cus_mstr, $data);

            $id = $this->db->insert_id();

            if (!empty($this->db->insert_id()) && $this->db->insert_id() > 0) {
                $address['tech_id'] = $this->db->insert_id();
                $address['telephone'] = $this->_telephone;
                $this->db->insert($this->tbl_technician_info, $address);

                $log['cus_id'] = $id;
                $log['update_date'] = date('Y-m-d H:i:s');
                $log['create_date'] = date('Y-m-d H:i:s');

                $this->db->insert($this->tbl_user_log, $log);
            }

            return $id;

        } elseif ($this->_user_role_id == 4) {
            $this->db->insert($this->tbl_cus_mstr, $data);
            $id = $this->db->insert_id();

            if (!empty($this->db->insert_id()) && $this->db->insert_id() > 0) {
                $address['cus_id'] = $this->db->insert_id();
                $this->db->insert($this->tbl_cus_mstr_profile, $address);

                $log['cus_id'] = $id;
                $log['update_date'] = date('Y-m-d H:i:s');
                $log['create_date'] = date('Y-m-d H:i:s');

                $this->db->insert($this->tbl_user_log, $log);

                $this->db->where('id',$id);
                $this->db->update($this->tbl_cus_mstr,array('iot_id'=> $id));
            }

            return $id;

        } else {
            return false;
        }
    }

    //update user
    public function update()
    {

        $data = array();
        if (!is_blank($this->_password)) {
            $hash = $this->hash($this->_password);
            $data['password'] = $hash;
        }
        $data['name'] = $this->_firstName;
        $data['lastname'] = $this->_lastName;
        $data['user_role_id'] = $this->_user_role_id;
        $data['cus_group_id'] = $this->_customer_group_id;
        $data['updated_at'] = $this->_timeStamp;

        $this->db->where('id', $this->_userID);
        $msg = $this->db->update($this->tbl_users, $data);

        if ($msg == 1) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function user_count()
    {
        return $this->db->count_all($this->tbl_users);
    }

    public function delete_user($data = '')
    {
        if (is_array($data)) {
            $user_id = get_array_value($data, 'id', '');
            $this->db->delete($this->tbl_cus_mstr, array('id' => $user_id));
            if ($this->db->affected_rows() > 0) {
                return true;
            }
        }
        return false;
    }

    function group_list()
    {
        $db_where = array('status' => 1);

        if (getUserRoleId() > 1) {
            $this->db->where($db_where);
        }
        $query = $this->db->get($this->tbl_cus_group);
        $result = array();
        $rows = array();
        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $rows = array(
                    'id' => get_array_value($row, 'id', ''),
                    'name' => get_array_value($row, 'name', ''),
                    'status' => get_array_value($row, 'status', '')
                );

                $result[] = $rows;
            }
            return $result;
        }

        return false;

    }

    function user_list($limit = 20, $start = 0)
    {
        $result = array();
        $rows = array();
        $this->db->reset_query();

        $this->db->select('cus_mstr.id,cus_mstr.email,cus_mstr.`password`,cus_mstr.`name`,cus_mstr.lastname,cus_mstr.telephone,cus_mstr.user_role_id,user_role.`name` AS role_name,cus_group.`name` AS group_name,cus_mstr.cus_group_id,cus_mstr.`status` AS user_status');
        $this->db->join($this->tbl_cus_group, 'cus_mstr.cus_group_id = cus_group.id', 'left');
        $this->db->join($this->tbl_user_role, 'cus_mstr.user_role_id = user_role.id ', 'left');


        switch (getUserRoleId()) {
            case 5:
            $group_id = getUserGroupId();
            $db_where = array('cus_mstr.cus_group_id' => $group_id, 'cus_mstr.status' => '1');
            $this->db->where($db_where);
            break;
            case 4:
            $group_id = getUserGroupId();
            $db_where = array('cus_mstr.cus_group_id' => $group_id);
            $this->db->where($db_where);
            break;
            case 3:
            $group_id = getUserGroupId();
            $db_where = array('cus_mstr.cus_group_id' => $group_id);
            $this->db->where($db_where);
            break;
            case 2:
            $group_id = getUserGroupId();
            $db_where = array('cus_mstr.cus_group_id' => $group_id);
            $this->db->where($db_where);

            break;
            case 1:
            $group_id = getUserGroupId();
            $db_where = array('cus_mstr.status' => '1');
            $this->db->where($db_where);
            break;
        }


        $query = $this->db->order_by('id', 'desc')->limit($limit, $start)->get($this->tbl_cus_mstr);


        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $rows = array(
                    'id' => get_array_value($row, 'id', ''),
                    'email' => get_array_value($row, 'email', ''),
                    'name' => get_array_value($row, 'name', ''),
                    'last_name' => get_array_value($row, 'lastname', ''),
                    'user_role_id' => get_array_value($row, 'user_role_id', ''),
                    'role_name' => get_array_value($row, 'role_name', ''),
                    'customer_group_id' => get_array_value($row, 'cus_group_id'),
                    'group_name' => get_array_value($row, 'group_name', ''),
                    'status' => get_array_value($row, 'user_status', '')
                );
                $result[] = $rows;
            }
        }

        return $result;

    }


    // login method and password verify
    function login_core()
    {
        $this->db->select('*,id as user_id');
        $this->db->from($this->tbl_cus_mstr);
        $this->db->where('email', $this->_userName);
        $this->db->where('password !=', '');
        $this->db->where('status', 1);
        /*$this->db->where('user_role_id',4);*/
        $user_role_id = array(1,2, 4);
        $this->db->where_in('user_role_id',$user_role_id);
        $this->db->limit(1);
        $query = $this->db->get();

        if ($query->num_rows() == 1) {
            $result = $query->result();

            foreach ($result as $row) {
                if ($this->verifyHash($this->_password, $row->password) == TRUE) {
                    return $result;
                } elseif ($this->verifyHash256($this->_password, $row->password) == TRUE) {
                    return $result;
                } else {
                    return FALSE;
                }
            }

        } else {
            return FALSE;
        }


    } //end of function login

    function login_club()
    {
        $this->db->select('*,id as user_id');
        $this->db->from($this->tbl_cus_mstr);
        $this->db->where('email', $this->_userName);
        $this->db->where('password !=', '');
        $this->db->where('status', 1);
        $user_role_id = array(1,3);
        $this->db->where_in('user_role_id',$user_role_id);
        $this->db->limit(1);
        $query = $this->db->get();

        if ($query->num_rows() == 1) {
            $result = $query->result();

            foreach ($result as $row) {
                if ($this->verifyHash($this->_password, $row->password) == TRUE) {
                    return $result;
                } elseif ($this->verifyHash256($this->_password, $row->password) == TRUE) {
                    return $result;
                } else {
                    return FALSE;
                }
            }
        } else {
            return FALSE;
        }


    } //end of function login


    //change password
    public function changePassword()
    {
        $hash = $this->hash($this->_password);
        //$sha256 = hash("sha256",$this->_password);
        $data = array(
            'password' => $hash,
        );

        /*$data_iot = array(
            'password' => $sha256,
        );*/

        $this->db->where('id', $this->_userID);
        $this->db->update($this->tbl_cus_mstr, $data);


        /*$this->db2->where('email',$this->_email);
        $this->db2->update($this->tbl_cus_mstr, $data_iot);*/

        

        if ($this->db->affected_rows() > 0) {
            return TRUE;
        } else {
            return false;
        }
    }

    public function forgotPassword_club()
    {
        $hash = $this->hash($this->_password);
        $data = array(
            'password' => $hash
        );

        $data_where = array(
            'email' => $this->_email
        );

        $where_in = array(1,3);

        $this->db->where($data_where);
        $this->db->where_in('user_role_id', $where_in);
        $this->db->update($this->tbl_cus_mstr, $data);

        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function forgotPassword_core()
    {
        //$hash = $this->hash($this->_password);
        $sha256 = hash("sha256",$this->_password);
        $data = array(
            'password' => $sha256
        );

        $data_where = array(
            'email' => $this->_email
        );

        $where_in = array(1,2,4);

        $this->db->where($data_where);
        $this->db->where_in('user_role_id', $where_in);
        $this->db->update($this->tbl_cus_mstr, $data);

        /*$this->db2->where($data_where);
        $this->db2->update($this->tbl_cus_mstr, $data);*/

        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    // get User Detail
    public function getUserDetails()
    {
        $this->db->select(array('m.id as user_id','cus_group_id','m.status','user_role_id', 'CONCAT(m.name, " ", m.lastname) as full_name', 'm.name', 'm.lastname', 'm.email', 'm.token', 'm.telephone', 'm.latitude', 'm.longitude', 'm.device_id'));
        $this->db->from('cus_mstr as m');
        $this->db->where('m.id', $this->_userID);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return FALSE;
        }
    }

    // update Forgot Password
    public function updateForgotPassword()
    {
        $hash = $this->hash($this->_password);
        $data = array(
            'password' => $hash,
        );
        $this->db->where('email', $this->_email);
        $msg = $this->db->update('users', $data);
        if ($msg > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    // get Email Address
    public function activate()
    {
        $data = array(
            'status' => 1,
            'verification_code' => 1,
        );
        $this->db->where('verification_code', $this->_verificationCode);
        $msg = $this->db->update('users', $data);
        if ($msg == 1) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    // password hash
    public function hash($password)
    {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        return $hash;
    }

    // password verify
    public function verifyHash($password, $vpassword)
    {
        if (password_verify($password, $vpassword)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function hash256($password)
    {
        $hash = hash("sha256", $password);
        return $hash;
    }

    public function verifyHash256($password, $vpassword)
    {
        $ch_pass = $this->hash256($password);
        if ($ch_pass == $vpassword) {
            return TRUE;
        } else {
            return FALSE;
        }
    }


    public function check_user($email = "")
    {

        if (!is_blank($email)) {
            $query = $this->db->where(array('email' => $email))->get($this->tbl_users);
            if ($query->num_rows() != 0) {
                return true;
            }
        }

        return false;
    }

    public function updateToken()
    {

        $data = array();
        $data['device_id'] = $this->_deviceId;
        
        $this->db->where('id', $this->_userID);
        $this->db->update($this->tbl_cus_mstr, $data);

        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }

    }

    public function validate()
    {
        $data_where = array(
            'email' => $this->_email,
            'user_role_id' => $this->_user_role_id,
            'fb_email' => '' 
        );

        $this->db->select('*');
        $this->db->from($this->tbl_cus_mstr);
        $this->db->where($data_where);

        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
        
    }

    public function validate_old()
    {
        $data_where = array(
            'email' => $this->_email,
            'user_role_id' => $this->_user_role_id
        );

        /*$data_where2 = array(
            'name' => $this->_firstName,
            'lastname' => $this->_lastName,
            'user_role_id' => $this->_user_role_id
        );*/

        $this->db2->select('name, lastname, email');
        $this->db2->from($this->tbl_cus_mstr);
        $this->db2->where($data_where);

        $query = $this->db2->get();

        if ($query->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
        
    }

    public function update_fcm_token()
    {
        $data = array();

        $data_where = array(
            'device_id' => $this->_deviceId,
            'cus_id' => $this->_userID
        );

        $data['device_id'] = $this->_deviceId;
        
        $this->db->select('device_id');
        $this->db->from($this->tbl_user_log);
        $this->db->where($data_where);

        $query = $this->db->get();

        if ($query->num_rows() > 0) {

            $data = array(
                'update_date' => date('Y-m-d H:i:s')
            );

            $this->db->where('device_id', $this->_deviceId);
            $msg = $this->db->update($this->tbl_user_log, $data);

            if ($msg == 1) {
                return true;
            } else {
                return false;
            }

        } else {

            $data = array(
                'cus_id' => $this->_userID,
                'device_id' => $this->_deviceId,
                'create_date' => date('Y-m-d H:i:s'),
                'update_date' => date('Y-m-d H:i:s')
            );

            $this->db->where('id', $this->_userID);
            $msg = $this->db->update($this->tbl_cus_mstr, array('device_id' => $this->_deviceId));

            $this->db->insert($this->tbl_user_log, $data);

            if (!empty($this->db->insert_id()) && $this->db->insert_id() > 0) {
                return true;
            } else {
                return false;
            }
        }
    }

    public function checkEmail()
    {
        $data_where = array(
            'email' => $this->_email,
        );

        $this->db->select('email');
        $this->db->from($this->tbl_cus_mstr);
        $this->db->where($data_where);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {

            return true;

        } else {
            return false;
        }

    }

    public function checkEmail_core()
    {
        $data_where = array(
            'email' => $this->_email,
        );

        $this->db->select('email');
        $this->db->from($this->tbl_cus_mstr);
        $this->db->where($data_where);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {

            return true;

        } else {
            return false;
        }

    }

    public function logout()
    {

        $data_where = array(
            'device_id' => $this->_deviceId
        );

        //$this->db->delete($this->tbl_user_log, $data_where);

        $this->db->select('device_id');
        $this->db->from($this->tbl_user_log);
        $this->db->where($data_where);

        $query = $this->db->get();

        if ($query->num_rows() > 0) {

            $result = $query->row();

            $this->db->delete($this->tbl_user_log, array('device_id' => $result->device_id));

            if ($this->db->affected_rows() > 0) {
                return true;
            } else {
                return false;
            }

        } else {

            return false;
        }

    }

    public function check_version()
    {

        $data_where = array(
            'device' => $this->_device,
            'app' => $this->_app
        );

        $this->db->select('*');
        $this->db->from($this->tbl_version);
        $this->db->where($data_where);

        $query = $this->db->get();

        if ($query->num_rows() > 0) {

            $result = $query->row();

            return $result->version;

        } else {

            return false;
        }
    }

    public function login()
    {

        $user_role_id = array(1,8,9);

        $this->db->select('*');
        $this->db->from($this->tbl_cus_mstr);
        $this->db->where('email', $this->_userName);
        $this->db->where_in('user_role_id', $user_role_id);
        $this->db->where('status', 1);
        $this->db->limit(1);

        $query = $this->db->get();

        if ($query->num_rows() == 1) {
            $result = $query->row();

            if ($this->verifyHash($this->_password, $result->password) == TRUE) {
                return $result;
            } elseif ($this->verifyHash256($this->_password, $result->password) == TRUE) {
                return $result;
            } else {
                return FALSE;
            }

        } else {
            return false;
        }
    }

    public function delete_user_log()
    {

        $data_where = array(
            'cus_id' => $this->_userID,
            'update_date <' => date("Y-m-d H:i:s", strtotime("-30 days"))
        );

        $this->db->delete($this->tbl_user_log, $data_where);

        return $data_where;
    }

}

?>