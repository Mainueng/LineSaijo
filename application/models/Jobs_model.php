<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Jobs_model extends MY_Model
{
    private $_job_id;
    private $_tech_id;
    private $_cus_id;
    private $_serial;
    private $_appointment_datetime;
    private $_telephone;
    private $_symptom;
    private $_latitude;
    private $_longitude;
    private $_radius;
    private $_service_id;
    private $_status_code;
    private $_type_code;
    private $_check_sheet;
    private $_rating;
    private $_customer_message;
    private $_pic_in;
    private $_pic_out;
    private $_created_datetime;
    private $_update_datetime;
    private $_province;
    private $_problem_img;
    private $_sequence;
    private $_btu;
    private $_cost;
    private $_invoice;
    private $_total;
    private $_service_fee;
    private $_img_1;
    private $_img_2;
    private $_img_3;


    public function __construct()
    {
        parent::__construct();

        $this->load->library('date_converts');

    }

    public function setJobId($id)
    {
        $this->_job_id = $id;
    }

    public function getJobId()
    {
        return $this->_job_id;
    }

    public function setTechId($tech_id)
    {
        $this->_tech_id = $tech_id;
    }

    public function setCustomerId($cus_id)
    {
        $this->_cus_id = $cus_id;
    }

    public function setAppointmentDatetime($appoint_datetime)
    {
        $this->_appointment_datetime = $appoint_datetime;
    }

    public function setTelNumber($tel_number)
    {
        $this->_telephone = $tel_number;
    }

    public function setSymptom($symptom)
    {
        $this->_symptom = $symptom;
    }

    public function setLatitude($latitude)
    {
        $this->_latitude = $latitude;
    }

    public function setLongitude($longitude)
    {
        $this->_longitude = $longitude;
    }

    public function setRadius($radius)
    {
        $this->_radius = $radius;
    }

    public function setStatusCode($status_code)
    {
        $this->_status_code = $status_code;
    }

    public function setTypeCode($type_code)
    {
        $this->_type_code = $type_code;
    }

    public function setCheckSheet($check_cheet)
    {
        $this->_check_sheet = $check_cheet;
    }

    public function setRating($rating)
    {
        $this->_rating = $rating;
    }

    public function setCustomerMessage($message)
    {
        $this->_customer_message = $message;
    }

    public function setPicIn($pic_in)
    {
        $this->_pic_in = $pic_in;
    }

    public function setPicOut($pic_out)
    {
        $this->_pic_out = $pic_out;
    }

    public function setCreateDateTime($create_date)
    {
        $this->_created_datetime = $create_date;
    }

    public function setUpdateDateTime($update_date)
    {
        $this->_update_datetime = $update_date;
    }

    public function setSerial($serial)
    {
        $this->_serial = $serial;
    }

    public function setServiceId($service_id)
    {
        $this->_service_id = $service_id;
    }

    public function setAppointment($appointment_datetime)
    {
        $this->_appointment_datetime = $appointment_datetime;
    }

    public function setProvince($province)
    {
        $this->_province = $province;
    }

    public function setProblemImg($problem_img)
    {
        $this->_problem_img = $problem_img;
    }

    public function setBTU($btu)
    {
        $this->_btu = $btu;
    }

    public function setCost($cost)
    {
        $this->_cost = $cost;
    }

    public function setInvoice($invoice)
    {
        $this->_invoice = $invoice;
    }

    public function setTotal($total)
    {
        $this->_total = $total;
    }

    public function setServiceFee($service_fee)
    {
        $this->_service_fee = $service_fee;
    }

    public function setImg1($img_1)
    {
        $this->_img_1 = $img_1;
    }

    public function setImg2($img_2)
    {
        $this->_img_2 = $img_2;
    }

    public function setImg3($img_3)
    {
        $this->_img_3 = $img_3;
    }


    public function create()
    {
        $data = array();
        if (!is_blank($this->_tech_id)) {
            $data['tech_id'] = $this->_tech_id;
        }
        if (!is_blank($this->_cus_id)) {
            $data['cus_id'] = $this->_cus_id;
        }
        if (!is_blank($this->_service_id)) {
            $data['service_id'] = $this->_service_id;
        }

        if (!is_blank($this->_serial)) {
            $data['serial'] = $this->_serial;
        }
        if (!is_blank($this->_appointment_datetime)) {
            $data['appointment_datetime'] = $this->_appointment_datetime;
        }
        if (!is_blank($this->_telephone)) {
            $data['telephone'] = $this->_telephone;
        }
        if (!is_blank($this->_symptom)) {
            $data['symptom'] = $this->_symptom;
        }
        if (!is_blank($this->_latitude)) {
            $data['latitude'] = $this->_latitude;
        }
        if (!is_blank($this->_longitude)) {
            $data['longitude'] = $this->_longitude;
        }
        if (!is_blank($this->_radius)) {
            $data['radius'] = $this->_radius;
        }
        if (!is_blank($this->_status_code)) {
            $data['status_code'] = $this->_status_code;
        }
        if (!is_blank($this->_type_code)) {
            $data['type_code'] = $this->_type_code;
        }
        if (!is_blank($this->_rating)) {
            $data['rating'] = $this->_rating;
        }
        if (!is_blank($this->_customer_message)) {
            $data['customer_message'] = $this->_customer_message;
        }
        if (!is_blank($this->_pic_in)) {
            $data['pic_in'] = $this->_pic_in;
        }
        if (!is_blank($this->_pic_out)) {
            $data['pic_out'] = $this->_pic_out;
        }
        if (!is_blank($this->_created_datetime)) {
            $data['create_datetime'] = $this->_created_datetime;
        }
        if (!is_blank($this->_update_datetime)) {
            $data['update_datetime'] = $this->_update_datetime;
        }

        $this->db->insert($this->tbl_jobs, $data);
        if (!is_blank($this->db->insert_id()) && $this->db->insert_id() > 0) {

            $this->setJobId($this->db->insert_id());

            return true;

        } else {
            return false;
        }


    }

    public function update()
    {
        $data = array();
        if (!is_blank($this->_tech_id)) {
            $data['tech_id'] = $this->_tech_id;
        }
        if (!is_blank($this->_cus_id)) {
            $data['cus_id'] = $this->_cus_id;
        }
        if (!is_blank($this->_service_id)) {
            $data['service_id'] = $this->_service_id;
        }

        if (!is_blank($this->_serial)) {
            $data['serial'] = $this->_serial;
        }
        if (!is_blank($this->_appointment_datetime)) {
            $data['appointment_datetime'] = $this->_appointment_datetime;
        }
        if (!is_blank($this->_telephone)) {
            $data['telephone'] = $this->_telephone;
        }
        if (!is_blank($this->_symptom)) {
            $data['symptom'] = $this->_symptom;
        }
        if (!is_blank($this->_latitude)) {
            $data['latitude'] = $this->_latitude;
        }
        if (!is_blank($this->_longitude)) {
            $data['longitude'] = $this->_longitude;
        }
        if (!is_blank($this->_radius)) {
            $data['radius'] = $this->_radius;
        }
        if (!is_blank($this->_status_code)) {
            $data['status_code'] = $this->_status_code;
        }
        if (!is_blank($this->_type_code)) {
            $data['type_code'] = $this->_type_code;
        }
        if (!is_blank($this->_rating)) {
            $data['rating'] = $this->_rating;
        }
        if (!is_blank($this->_customer_message)) {
            $data['customer_message'] = $this->_customer_message;
        }
        if (!is_blank($this->_pic_in)) {
            $data['pic_in'] = $this->_pic_in;
        }
        if (!is_blank($this->_pic_out)) {
            $data['pic_out'] = $this->_pic_out;
        }
        if (!is_blank($this->_created_datetime)) {
            $data['create_datetime'] = $this->_created_datetime;
        }
        if (!is_blank($this->_update_datetime)) {
            $data['update_datetime'] = $this->_update_datetime;
        }
        if (!is_blank($this->_check_sheet)) {
            $data['check_sheet'] = $this->_check_sheet;
        }
        $this->db->where('id', $this->_job_id);
        $this->db->update($this->tbl_jobs, $data);

        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }


    }

    public function update_job($status){
        $data = array();

        if (!is_blank($this->_tech_id)) {
            $data['tech_id'] = $this->_tech_id;
        }
        if (!is_blank($this->_pic_in)) {
            $data['pic_in'] = $this->_pic_in;
        }
        if (!is_blank($this->_pic_out)) {
            $data['pic_out'] = $this->_pic_out;
        }
        if (!is_blank($this->_check_sheet)) {
            $data['check_sheet'] = $this->_check_sheet;
        }

        $data['status_code'] = $status;
        $data['update_datetime'] = now();

        if ($status == 5) {
            $array = array('id' => $this->_job_id);
        } elseif ($status == 4) {
            $array = array('id' => $this->_job_id, 'status_code' => 3);
        } else {
            $array = array('id' => $this->_job_id, 'status_code' => 2); 
        }

        $this->db->where($array);
        $this->db->update($this->tbl_jobs, $data);

        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function delete()
    {

    }

    public function jobs_list()
    {
        $data_where = array(
            'jobs.cus_id' => $this->_cus_id,
            'jobs.status_code' => 3
        );

        $this->db->select('jobs.id,jobs.tech_id,jobs.appointment_datetime,jobs.type_code,technician.name,technician.lastname');
        $this->db->from($this->tbl_jobs);
        $this->db->join($this->tbl_technician, 'jobs.tech_id = technician.id', 'left');
        $this->db->where($data_where);

        $query = $this->db->get();

        $result = array();
        $rows = array();
        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $rows = array(
                    'job_id' => get_array_value($row, 'id', ''),
                    'tech_id' => get_array_value($row, 'tech_id', ''),
                    'appointment_datetime' => get_array_value($row, 'appointment_datetime', ''),
                    'type_code' => get_array_value($row, 'type_code', ''),
                    'technician_name' => get_array_value($row, 'name', ''),
                    'technician_last' => get_array_value($row, 'lastname', ''),

                );

                $result[] = $rows;
            }

            return $result;

        } else {

            return false;
        }

    }

    public function job_info()
    {
        $data_where = array(
            'jobs.id' => $this->_job_id,
        );

        $this->db->select('jobs.id,job_type.name as type,jobs.appointment_datetime,jobs.symptom,jobs.latitude,jobs.longitude,jobs.serial,cus_mstr.name,cus_mstr.lastname,cus_mstr_profile.cus_addr1');
        $this->db->from($this->tbl_jobs);
        $this->db->join($this->tbl_technician, 'jobs.tech_id = technician.id', 'left');
        $this->db->join($this->tbl_cus_mstr, 'jobs.cus_id = cus_mstr.id', 'left');
        $this->db->join($this->tbl_cus_mstr_profile, 'cus_mstr.id = cus_mstr_profile.cus_id', 'left');
        $this->db->join($this->tbl_job_type, 'jobs.type_code = job_type.code', 'left');
        $this->db->where($data_where);

        $query = $this->db->get();

        $result = array();
        $rows = array();
        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $rows = array(
                    'job_id' => get_array_value($row, 'id', ''),
                    'type_code' => get_array_value($row, 'type', ''),
                    'appointment_date' => date_format(date_create(get_array_value($row, 'appointment_datetime', '')), 'd-m-Y'),
                    'appointment_time' => date_format(date_create(get_array_value($row, 'appointment_datetime', '')), 'H:i:s'),
                    'serial' => get_array_value($row, 'serial', ''),
                    'symptom' => get_array_value($row, 'symptom', ''),
                    'latitude' => get_array_value($row, 'latitude', ''),
                    'longitude' => get_array_value($row, 'longitude', ''),
                    'name' => get_array_value($row, 'name', ''),
                    'lastname' => get_array_value($row, 'lastname', ''),
                    'address' => get_array_value($row, 'cus_addr1', ''),
                );

                $result[] = $rows;
            }

            return $result;

        } else {

            return false;
        }

    }

    public function job_info_core()
    {
        $data_where = array(
            'jobs.id' => $this->_job_id,
        );

        $lat1 =  $this->_latitude;
        $lon1 =  $this->_longitude;

        $this->db->select('jobs.id,jobs.cus_id,jobs.tech_id,jobs.status_code,job_type.name_en as type_en,job_type.name_th as type_th,jobs.appointment_datetime,jobs.symptom,cus_mstr.latitude,cus_mstr.longitude,cus_mstr.name,cus_mstr.lastname,technician_info.telephone,technician_info.profile_img,jobs.serial,sum(service_history.cost) as cost,unit,jobs.problem_img,technician_info.rating,technician_info.rating_count,saijo_verify.status,job_status.name_en as status_en,job_status.name_th as status_th,jobs.comment,jobs.install_list,payment_status');
        $this->db->from($this->tbl_jobs);
        $this->db->join($this->tbl_cus_mstr, 'jobs.tech_id = cus_mstr.id', 'left');
        $this->db->join($this->tbl_job_type, 'jobs.type_code = job_type.code', 'left');
        $this->db->join($this->tbl_job_status, 'jobs.status_code = job_status.code', 'left');
        $this->db->join($this->tbl_technician_info, 'jobs.tech_id = technician_info.tech_id', 'left');
        $this->db->join($this->tbl_service_history, 'jobs.id = service_history.job_id', 'left');
        $this->db->join($this->tbl_service_cost, 'service_cost.id = service_history.service_id', 'left');
        $this->db->join($this->tbl_saijo_verify, 'technician_info.tech_id = saijo_verify.tech_id', 'left');
        $this->db->join($this->tbl_invoice, 'jobs.id = invoice.job_id', 'left');
        $this->db->where($data_where);

        $query = $this->db->get();

        $result = array();
        $rows = array();
        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $rows = array(
                    'job_id' => get_array_value($row, 'id', ''),
                    'cus_id' => get_array_value($row, 'cus_id', ''),
                    'tech_id' => get_array_value($row, 'tech_id', ''),
                    'name' => get_array_value($row, 'name', ''),
                    'lastname' => get_array_value($row, 'lastname', ''),
                    'status_code' => get_array_value($row, 'status_code', ''),
                    'job_type_en' => get_array_value($row, 'type_en', ''),
                    'job_type_th' => get_array_value($row, 'type_th', ''),
                    'job_status_en' => get_array_value($row, 'status_en', ''),
                    'job_status_th' => get_array_value($row, 'status_th', ''),
                    'appointment_date' => date_format(date_create(get_array_value($row, 'appointment_datetime', '')), 'd-m-Y'),
                    'appointment_date_en' => $this->date_converts->DateUS(date_format(date_create(get_array_value($row, 'appointment_datetime', '')), 'd-m-Y')),
                    'appointment_date_th' => $this->date_converts->DateThai(date_format(date_create(get_array_value($row, 'appointment_datetime', '')), 'd-m-Y')),
                    'appointment_time' => date_format(date_create(get_array_value($row, 'appointment_datetime', '')), 'H:i'),
                    'serial' => get_array_value($row, 'serial', ''),
                    'symptom' => get_array_value($row, 'symptom', ''),
                    'latitude' => get_array_value($row, 'latitude', ''),
                    'longitude' => get_array_value($row, 'longitude', ''),                   
                    'telephone' => get_array_value($row, 'telephone', ''),
                    'profile_picture' => get_array_value($row, 'profile_img', ''),
                    'radius' => $this->radius($lat1,$lon1,get_array_value($row, 'latitude', ''),get_array_value($row, 'longitude', ''))." km",
                    'problem_img' => get_array_value($row, 'problem_img', ''),
                    'service_cost' => get_array_value($row, 'cost', ''),
                    'service_unit' => get_array_value($row, 'unit', ''),
                    'rating' => get_array_value($row, 'rating', ''),
                    'rating_count' => get_array_value($row, 'rating_count', ''),
                    'verify' => get_array_value($row, 'status', ''),
                    'comment' => get_array_value($row, 'comment', ''),
                    'install_list' => get_array_value($row, 'install_list', ''),
                    'payment_status' => get_array_value($row, 'payment_status', ''),
                );

                if ($rows['install_list']) {

                    $install_list = explode(',', $rows['install_list']);

                    $total = 0;

                    $arr = array();

                    for ($i=0; $i < count($install_list); $i++) { 
                        $install_data = explode(' - ', $install_list[$i]);

                        $data[] = array('item' => $install_data[0], 'cost' => $install_data[1], 'unit' => '฿') ;

                        $total = $total + $install_data[1];

                        if (strpos($install_data[0], 'ส่วนลดค่าติดตั้ง') === false) {
                            $arr[] = $install_data[0];
                        }
                    }

                    $rows['serial'] = implode(',',$arr);

                    $rows['install_list'] = $data;
                    $rows['install_total'] = strval($total);
                    $rows['install_total_unit'] = '฿';

                } else {
                    $rows['install_list'] = array();
                    $rows['install_total'] = '';
                    $rows['install_total_unit'] = '';
                }

                if ($rows['verify'] == 1) {
                    $rows['verify'] = true; 
                } else {
                    $rows['verify'] = false; 
                }

                $this->db->select('*');
                $this->db->from($this->tbl_cus_mstr_profile);
                $this->db->where(array('cus_id' => $rows['cus_id']));

                $query_address = $this->db->get();

                if ($query_address->num_rows() > 0) {
                    foreach ($query_address->result_array() as $address) {
                        $addresses = array(
                            'address' => get_array_value($address, 'cus_addr1', ''),
                            'district' => get_array_value($address, 'district', ''),
                            'province' => get_array_value($address, 'province', ''),
                            'postal_code' => get_array_value($address, 'postal_code', '')
                        );
                    }

                    $rows['address'] = $addresses['address'];
                    $rows['district'] = $addresses['district'];
                    $rows['province'] = $addresses['province'];
                    $rows['postal_code'] = $addresses['postal_code'];
                }

                $result[] = $rows;
            }

            return $result;

        } else {

            return false;
        }

    }

    public function telephone_number()
    {
        $data_where = array(
            'jobs.tech_id' => $this->_tech_id
        );

        $this->db->select('cus_mstr.telephone');
        $this->db->from($this->tbl_cus_mstr);
        $this->db->join($this->tbl_jobs, 'jobs.cus_id = cus_mstr.id', 'left');
        $this->db->where($data_where);

        $query = $this->db->get();

        $result = array();
        $rows = array();
        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $rows = array(
                    'telephone' => get_array_value($row, 'telephone', '')
                );

                $result[] = $rows;
            }

            return $result;

        } else {

            return false;
        }
    }

    public function jobs_list_club()
    {
        $data_where = array(
            'jobs.tech_id' => $this->_tech_id,
            'jobs.appointment_datetime >' => date("Y-m-d H:i:s", strtotime("-30 minutes"))
        );

        $status_code = array(1,2,3,8);

        $lat1 = $this->_latitude;
        $lon1 = $this->_longitude;

        $this->db->select('jobs.id,jobs.appointment_datetime,jobs.status_code,job_type.name_en as type_en,job_type.name_th as type_th,cus_mstr.name,cus_mstr.lastname,job_status.name_en,job_status.name_th,jobs.latitude,jobs.longitude');
        $this->db->from($this->tbl_jobs);
        $this->db->join($this->tbl_cus_mstr, 'jobs.cus_id = cus_mstr.id', 'left');
        $this->db->join($this->tbl_job_type, 'jobs.type_code = job_type.code', 'left');
        $this->db->join($this->tbl_job_status, 'jobs.status_code = job_status.code', 'left');
        $this->db->where($data_where);
        $this->db->where_in('jobs.status_code', $status_code);

        $this->db->order_by("jobs.appointment_datetime", "asc");

        $query = $this->db->get();

        $result = array();
        $rows = array();

        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $rows = array(
                    'job_id' => get_array_value($row, 'id', ''),
                    'appointment_date' => date_format(date_create(get_array_value($row, 'appointment_datetime', '')), 'd-m-Y'),
                    'appointment_date_en' => $this->date_converts->DateUS(date_format(date_create(get_array_value($row, 'appointment_datetime', '')), 'd-m-Y')),
                    'appointment_date_th' => $this->date_converts->DateThai(date_format(date_create(get_array_value($row, 'appointment_datetime', '')), 'd-m-Y')),
                    'appointment_time' => date_format(date_create(get_array_value($row, 'appointment_datetime', '')), 'H:i:s'),
                    'status_code' => get_array_value($row, 'status_code', ''),
                    'job_type_en' => get_array_value($row, 'type_en', ''),
                    'job_type_th' => get_array_value($row, 'type_th', ''),
                    'job_status_en' => get_array_value($row, 'name_en', ''),
                    'job_status_th' => get_array_value($row, 'name_th', ''),
                    'name' => get_array_value($row, 'name', ''),
                    'lastname' => get_array_value($row, 'lastname', ''),
                    'radius' => $this->radius($lat1,$lon1,get_array_value($row, 'latitude', ''),get_array_value($row, 'longitude', ''))." km",
                    'latitude' => get_array_value($row, 'latitude', ''),
                    'longitude' => get_array_value($row, 'longitude', ''),

                );

                if (get_array_value($row, 'appointment_datetime', '') <= date("Y-m-d H:i:s") && get_array_value($row, 'status_code', '') == 3) {
                    $data['status_code'] = 8;

                    $update_where = array(
                        'jobs.id' => $rows['job_id']
                    );

                    $this->db->where($update_where);
                    $this->db->update($this->tbl_jobs, $data);

                    $log['job_id'] = $rows['job_id'];
                    $log['status_code'] = 8;
                    $log['update_datetime'] = date('Y-m-d H:i:s');

                    $this->db->insert($this->tbl_job_status_log, $log);

                    $rows['status_code'] = 8;
                }

                $result[] = $rows;
            }

            return $result;

        } else {

            return false;
        }

    }

    public function jobs_list_core()
    {
        $data_where = array(
            'jobs.cus_id' => $this->_cus_id,
            'jobs.appointment_datetime >=' => date("Y-m-d H:i:s", strtotime("-30 minutes"))
        );

        $status_code = array(1,2,3);

        $lat1 = $this->_latitude;
        $lon1 = $this->_longitude;

        $this->db->select('jobs.id,jobs.tech_id,jobs.appointment_datetime,jobs.status_code,job_type.name_en as type_en,job_type.name_th as type_th,cus_mstr.name,cus_mstr.lastname,job_status.name_en,job_status.name_th,cus_mstr.telephone,jobs.serial,jobs.symptom, cus_tech.tech_id as my_tech, saijo_verify.status,jobs.latitude,jobs.longitude,payment_status');
        //$this->db->select('jobs.id,jobs.tech_id,jobs.appointment_datetime,jobs.status_code,jobs.serial,jobs.symptom,cus_mstr.name,cus_mstr.lastname');
        $this->db->from($this->tbl_jobs);
        $this->db->join($this->tbl_cus_mstr, 'jobs.tech_id = cus_mstr.id', 'left');
        $this->db->join($this->tbl_job_type, 'jobs.type_code = job_type.code', 'left');
        $this->db->join($this->tbl_job_status, 'jobs.status_code = job_status.code', 'left');
        $this->db->join($this->tbl_cus_tech, 'jobs.tech_id = cus_tech.cus_id', 'left');
        $this->db->join($this->tbl_saijo_verify, 'jobs.tech_id = saijo_verify.tech_id', 'left');
        $this->db->join($this->tbl_invoice, 'jobs.id = invoice.job_id', 'left');
        $this->db->where($data_where);
        $this->db->where_in('jobs.status_code', $status_code);

        $this->db->order_by("jobs.appointment_datetime", "asc");

        $query = $this->db->get();

        $result = array();
        $rows = array();

        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $rows = array(
                    'job_id' => get_array_value($row, 'id', ''),
                    'tech_id' => get_array_value($row, 'tech_id', ''),
                    'appointment_date' => date_format(date_create(get_array_value($row, 'appointment_datetime', '')), 'd-m-Y'),
                    'appointment_date_en' => $this->date_converts->DateUS(date_format(date_create(get_array_value($row, 'appointment_datetime', '')), 'd-m-Y')),
                    'appointment_date_th' => $this->date_converts->DateThai(date_format(date_create(get_array_value($row, 'appointment_datetime', '')), 'd-m-Y')),
                    'appointment_time' => date_format(date_create(get_array_value($row, 'appointment_datetime', '')), 'H:i'),
                    'status_code' => get_array_value($row, 'status_code', ''),
                    'job_type_en' => get_array_value($row, 'type_en', ''),
                    'job_type_th' => get_array_value($row, 'type_th', ''),
                    'job_status_en' => get_array_value($row, 'name_en', ''),
                    'job_status_th' => get_array_value($row, 'name_th', ''),
                    'name' => get_array_value($row, 'name', ''),
                    'lastname' => get_array_value($row, 'lastname', ''),
                    'radius' => $this->radius($lat1,$lon1,get_array_value($row, 'latitude', ''),get_array_value($row, 'longitude', ''))." km",
                    'latitude' => get_array_value($row, 'latitude', ''),
                    'longitude' => get_array_value($row, 'longitude', ''),
                    'image' => get_array_value($row, 'tech_id', ''),
                    'telephone' => get_array_value($row, 'telephone', ''),
                    'serial' => get_array_value($row, 'serial', ''),
                    'symptom' => get_array_value($row, 'symptom', ''),
                    'my_tech' => get_array_value($row, 'my_tech', ''),
                    'verify' => get_array_value($row, 'status', ''),
                    'payment_status' => get_array_value($row, 'payment_status', '')
                );

                if ($rows['my_tech'] == $rows['image']) {
                    $rows['my_tech'] = true;
                } else {
                    $rows['my_tech'] = false;
                }

                if ($rows['verify'] == 1) {
                    $rows['verify'] = true; 
                } else {
                    $rows['verify'] = false; 
                }

                if (get_array_value($row, 'appointment_datetime', '') <= date("Y-m-d H:i:s") && get_array_value($row, 'status_code', '') == 3) {
                    $data['status_code'] = 8;

                    $update_where = array(
                        'jobs.id' => $rows['job_id']
                    );

                    $this->db->where($update_where);
                    $this->db->update($this->tbl_jobs, $data);

                    $log['job_id'] = $rows['job_id'];
                    $log['status_code'] = 8;
                    $log['update_datetime'] = date('Y-m-d H:i:s');

                    $this->db->insert($this->tbl_job_status_log, $log);

                    $rows['status_code'] = 8;
                }

                $result[] = $rows;
            }

            return $result;

        } else {

            return false;
        }

    }

    public function recommend_jobs_list()
    {
        $data_where = array(
            'jobs.status_code <' => 3,
            'jobs.appointment_datetime >=' => date("Y-m-d H:i:s"),
            'notification.tech_id=' => $this->_tech_id
        );

        $r = 30/6371;

        $lat1 = $this->_latitude;
        $lon1 = $this->_longitude;

        $lat_min = rad2deg(deg2rad($this->_latitude) - $r);
        $lat_max = rad2deg(deg2rad($this->_latitude) + $r);

        $delta_lon = asin(sin($r)/cos(deg2rad($this->_latitude)));

        $lon_min = rad2deg(deg2rad($this->_longitude) - $delta_lon);
        $lon_max = rad2deg(deg2rad($this->_longitude) + $delta_lon);

        //$this->db->distinct();
        $this->db->select('jobs.id,jobs.appointment_datetime,job_type.name_en as type_en,job_type.name_th as type_th,cus_mstr.name,cus_mstr.lastname,jobs.latitude,jobs.longitude,job_status.code,job_status.name_en,job_status.name_th');
        $this->db->from($this->tbl_jobs);
        $this->db->join($this->tbl_cus_mstr, 'jobs.cus_id = cus_mstr.id', 'left');
        $this->db->join($this->tbl_job_type, 'jobs.type_code = job_type.code', 'left');
        $this->db->join($this->tbl_job_status, 'jobs.status_code = job_status.code', 'left');
        $this->db->join($this->tbl_notification, 'jobs.id = notification.job_id', 'left');
        $this->db->where($data_where);
        $this->db->where('jobs.latitude BETWEEN '.$lat_min.' AND '.$lat_max);
        $this->db->where('jobs.longitude BETWEEN '.$lon_min.' AND '.$lon_max);
        $this->db->order_by("jobs.appointment_datetime", "desc");

        $query = $this->db->get();

        $result = array();
        $rows = array();

        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $rows = array(
                    'job_id' => get_array_value($row, 'id', ''),
                    'appointment_date' => date_format(date_create(get_array_value($row, 'appointment_datetime', '')), 'd-m-Y'),
                    'appointment_date_en' => $this->date_converts->DateUS(date_format(date_create(get_array_value($row, 'appointment_datetime', '')), 'd-m-Y')),
                    'appointment_date_th' => $this->date_converts->DateThai(date_format(date_create(get_array_value($row, 'appointment_datetime', '')), 'd-m-Y')),
                    'appointment_time' => date_format(date_create(get_array_value($row, 'appointment_datetime', '')), 'H:i'),
                    'status_code' => get_array_value($row, 'code', ''),
                    'job_type_en' => get_array_value($row, 'type_en', ''),
                    'job_type_th' => get_array_value($row, 'type_th', ''),
                    'job_status_en' => get_array_value($row, 'name_en', ''),
                    'job_status_th' => get_array_value($row, 'name_th', ''),
                    'name' => get_array_value($row, 'name', ''),
                    'lastname' => get_array_value($row, 'lastname', ''),
                    'radius' => $this->radius($lat1,$lon1,get_array_value($row, 'latitude', ''),get_array_value($row, 'longitude', ''))." km",
                    'latitude' => get_array_value($row, 'latitude', ''),
                    'longitude' => get_array_value($row, 'longitude', ''),
                );

                $result[] = $rows;
            }

            return $result;

        } else {

            return false;
        }

    }

    public function jobs_history_list_club()
    {
        $data_where = array(
            'jobs.tech_id' => $this->_tech_id,
            'jobs.appointment_datetime <' => date("Y-m-d H:i:s", strtotime("-30 minutes"))
        );

        $lat1 = $this->_latitude;
        $lon1 = $this->_longitude;

        $status_code = array(3,4,5,6,7,8,9);

        $this->db->select('jobs.id,jobs.appointment_datetime,jobs.status_code,job_type.name_en as type_en,job_type.name_th as type_th,cus_mstr.name,cus_mstr.lastname,job_status.name_en as status_en,job_status.name_th as status_th,jobs.status_code,jobs.latitude,jobs.longitude');
        $this->db->from($this->tbl_jobs);
        $this->db->join($this->tbl_cus_mstr, 'jobs.cus_id = cus_mstr.id', 'left');
        $this->db->join($this->tbl_job_type, 'jobs.type_code = job_type.code', 'left');
        $this->db->join($this->tbl_job_status, 'jobs.status_code = job_status.code', 'left');
        $this->db->where($data_where);
        $this->db->where_in('jobs.status_code', $status_code);
        $this->db->or_where("(jobs.status_code = 5 AND jobs.tech_id = ". $this->_tech_id .")", NULL, FALSE);
        $this->db->or_where("(jobs.status_code = 4 AND jobs.tech_id = ". $this->_tech_id .")", NULL, FALSE);
        $this->db->or_where("(jobs.status_code = 9 AND jobs.tech_id = ". $this->_tech_id .")", NULL, FALSE);
        $this->db->order_by("jobs.appointment_datetime", "desc");

        $query = $this->db->get();

        $result = array();
        $rows = array();

        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $rows = array(
                    'job_id' => get_array_value($row, 'id', ''),
                    'appointment_date' => date_format(date_create(get_array_value($row, 'appointment_datetime', '')), 'd-m-Y'),
                    'appointment_date_en' => $this->date_converts->DateUS(date_format(date_create(get_array_value($row, 'appointment_datetime', '')), 'd-m-Y')),
                    'appointment_date_th' => $this->date_converts->DateThai(date_format(date_create(get_array_value($row, 'appointment_datetime', '')), 'd-m-Y')),
                    'appointment_time' => date_format(date_create(get_array_value($row, 'appointment_datetime', '')), 'H:i'),
                    'status_code' => get_array_value($row, 'status_code', ''),
                    'job_type_en' => get_array_value($row, 'type_en', ''),
                    'job_type_th' => get_array_value($row, 'type_th', ''),
                    'job_status_en' => get_array_value($row, 'status_en', ''),
                    'job_status_th' => get_array_value($row, 'status_th', ''),
                    'name' => get_array_value($row, 'name', ''),
                    'lastname' => get_array_value($row, 'lastname', ''),
                    'radius' => $this->radius($lat1,$lon1,get_array_value($row, 'latitude', ''),get_array_value($row, 'longitude', ''))." km",
                    'latitude' => get_array_value($row, 'latitude', ''),
                    'longitude' => get_array_value($row, 'longitude', ''),
                );

                if (get_array_value($row, 'appointment_datetime', '') <= date("Y-m-d H:i:s", strtotime("-1 days")) && (get_array_value($row, 'status_code', '') == 8)) {

                    $data['status_code'] = 6;
                    $data['update_datetime'] = date("Y-m-d H:i:s");

                    $update_where = array(
                        'id' => get_array_value($row, 'id', ''),
                    );

                    $this->db->where($update_where);
                    $this->db->update($this->tbl_jobs, $data);

                    $log['job_id'] = $rows['job_id'];
                    $log['status_code'] = 6;
                    $log['update_datetime'] = date('Y-m-d H:i:s');

                    $this->db->insert($this->tbl_job_status_log, $log);

                    $rows['status_code'] = 6;

                    $this->db->select('*');
                    $this->db->from($this->tbl_user_token);

                    $query = $this->db->get();

                    if ($query->num_rows() > 0) {

                        foreach ($query->result_array() as $tokens) {
                            $token = array(
                                'token' => get_array_value($tokens, 'user_token', '')
                            );

                            $message = "Job ID ".$rows['job_id']." - ไม่มีการเคลื่อนไหว!";
                            $url = "job-list/form/".$rows['job_id']."?&method=edit";

                            $this->my_libraies->send_push_noti($token['token'],$message,$url);
                        }

                    }
                }

                $result[] = $rows;
            }

            return $result;

        } else {

            return false;
        }

    }

    public function jobs_history_list_core()
    {
        $data_where = array(
            'jobs.cus_id' => $this->_cus_id,
            'jobs.appointment_datetime <' => date("Y-m-d H:i:s", strtotime("+30 minutes"))
        );

        $lat1 = $this->_latitude;
        $lon1 = $this->_longitude;

        $status_code = array(3,4,5,6,7,8,9);

        $this->db->select('jobs.id,jobs.tech_id,jobs.appointment_datetime,jobs.status_code,job_type.name_en as type_en,job_type.name_th as type_th,cus_mstr.name,cus_mstr.lastname,job_status.name_en as status_en,job_status.name_th as status_th,jobs.status_code,jobs.latitude,jobs.longitude,cus_mstr.telephone,jobs.serial,jobs.symptom,cus_tech.tech_id as my_tech, saijo_verify.status,payment_status,sum(service_history.cost) as cost,unit');
        $this->db->from($this->tbl_jobs);
        $this->db->join($this->tbl_cus_mstr, 'jobs.tech_id = cus_mstr.id', 'left');
        $this->db->join($this->tbl_job_type, 'jobs.type_code = job_type.code', 'left');
        $this->db->join($this->tbl_job_status, 'jobs.status_code = job_status.code', 'left');
        $this->db->join($this->tbl_cus_tech, 'jobs.cus_id = cus_tech.cus_id', 'left');
        $this->db->join($this->tbl_saijo_verify, 'jobs.tech_id = saijo_verify.tech_id', 'left');
        $this->db->join($this->tbl_invoice, 'jobs.id = invoice.job_id', 'left');
        $this->db->join($this->tbl_service_history, 'jobs.id = service_history.job_id', 'left');
        $this->db->join($this->tbl_service_cost, 'service_cost.id = service_history.service_id', 'left');
        $this->db->where($data_where);
        $this->db->where_in('jobs.status_code', $status_code);
        $this->db->or_where("(jobs.status_code = 5 AND jobs.cus_id = ". $this->_cus_id .")", NULL, FALSE);
        $this->db->group_by("jobs.id");
        $this->db->order_by("jobs.appointment_datetime", "desc");

        $query = $this->db->get();

        $result = array();
        $rows = array();

        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $rows = array(
                    'job_id' => get_array_value($row, 'id', ''),
                    'tech_id' => get_array_value($row, 'tech_id', ''),
                    'appointment_date' => date_format(date_create(get_array_value($row, 'appointment_datetime', '')), 'd-m-Y'),
                    'appointment_date_en' => $this->date_converts->DateUS(date_format(date_create(get_array_value($row, 'appointment_datetime', '')), 'd-m-Y')),
                    'appointment_date_th' => $this->date_converts->DateThai(date_format(date_create(get_array_value($row, 'appointment_datetime', '')), 'd-m-Y')),
                    'appointment_time' => date_format(date_create(get_array_value($row, 'appointment_datetime', '')), 'H:i'),
                    'status_code' => get_array_value($row, 'status_code', ''),
                    'job_type_en' => get_array_value($row, 'type_en', ''),
                    'job_type_th' => get_array_value($row, 'type_th', ''),
                    'job_status_en' => get_array_value($row, 'status_en', ''),
                    'job_status_th' => get_array_value($row, 'status_th', ''),
                    'name' => get_array_value($row, 'name', ''),
                    'lastname' => get_array_value($row, 'lastname', ''),
                    'radius' => $this->radius($lat1,$lon1,get_array_value($row, 'latitude', ''),get_array_value($row, 'longitude', ''))." km",
                    'latitude' => get_array_value($row, 'latitude', ''),
                    'longitude' => get_array_value($row, 'longitude', ''),
                    'image' => get_array_value($row, 'tech_id', ''),
                    'telephone' => get_array_value($row, 'telephone', ''),
                    'serial' => get_array_value($row, 'serial', ''),
                    'symptom' => get_array_value($row, 'symptom', ''),
                    'my_tech' => get_array_value($row, 'my_tech', ''),
                    'verify' => get_array_value($row, 'status', ''),
                    'payment_status' => get_array_value($row, 'payment_status', ''),
                    'service_cost' => get_array_value($row, 'cost', ''),
                    'service_unit' => get_array_value($row, 'unit', ''),
                );

                if ($rows['my_tech'] == $rows['image']) {
                    $rows['my_tech'] = true;
                } else {
                    $rows['my_tech'] = false;
                }

                if ($rows['verify'] == 1) {
                    $rows['verify'] = true; 
                } else {
                    $rows['verify'] = false; 
                }

                if (get_array_value($row, 'appointment_datetime', '') <= date("Y-m-d H:i:s", strtotime("-1 days")) && (get_array_value($row, 'status_code', '') == 8 || get_array_value($row, 'status_code', '') == 3)) {
                    $data['status_code'] = 6;
                    $data['update_datetime'] = date("Y-m-d H:i:s");

                    $update_where = array(
                        'id' => get_array_value($row, 'id', ''),
                    );

                    $this->db->where($update_where);
                    $this->db->update($this->tbl_jobs, $data);

                    $log['job_id'] = $rows['job_id'];
                    $log['status_code'] = 6;
                    $log['update_datetime'] = date('Y-m-d H:i:s');

                    $this->db->insert($this->tbl_job_status_log, $log);

                    $rows['status_code'] = 6;

                    $this->db->select('*');
                    $this->db->from($this->tbl_user_token);

                    $query = $this->db->get();

                    if ($query->num_rows() > 0) {

                        foreach ($query->result_array() as $tokens) {
                            $token = array(
                                'token' => get_array_value($tokens, 'user_token', '')
                            );

                            $message = "Job ID ".$rows['job_id']." - ไม่มีการเคลื่อนไหว!";
                            $url = "job-list/form/".$rows['job_id']."?&method=edit";

                            $this->my_libraies->send_push_noti($token['token'],$message,$url);
                        }

                    }
                }

                $result[] = $rows;
            }

            return $result;

        } else {

            return false;
        }

    }

    public function job_info_club()
    {
        $data_where = array(
            'jobs.id' => $this->_job_id,
        );

        $lat1 =  $this->_latitude;
        $lon1 =  $this->_longitude;

        $this->db->select('jobs.id,jobs.cus_id,jobs.tech_id,jobs.status_code,jobs.problem_img,job_type.name_en as type_en,job_type.name_th as type_th,jobs.appointment_datetime,jobs.symptom,jobs.latitude,jobs.longitude,cus_mstr.name,cus_mstr.lastname,jobs.telephone,cus_mstr_profile.cus_addr1 as address,job_status.name_en as status_en,job_status.name_th as status_th,cus_mstr_profile.pic0,jobs.serial,cus_mstr_profile.district,cus_mstr_profile.province,cus_mstr_profile.postal_code,sum(service_history.cost) as cost,unit,comment,install_list,payment_status');
        $this->db->from($this->tbl_jobs);
        $this->db->join($this->tbl_cus_mstr, 'jobs.cus_id = cus_mstr.id', 'left');
        $this->db->join($this->tbl_job_type, 'jobs.type_code = job_type.code', 'left');
        $this->db->join($this->tbl_job_status, 'jobs.status_code = job_status.code', 'left');
        $this->db->join($this->tbl_cus_mstr_profile, 'jobs.cus_id = cus_mstr_profile.cus_id', 'left');
        $this->db->join($this->tbl_service_history, 'jobs.id = service_history.job_id', 'left');
        $this->db->join($this->tbl_service_cost, 'service_cost.id = service_history.service_id', 'left');
        $this->db->join($this->tbl_invoice, 'jobs.id = invoice.job_id', 'left');
        $this->db->where($data_where);

        $query = $this->db->get();

        $result = array();
        $rows = array();
        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $rows = array(
                    'job_id' => get_array_value($row, 'id', ''),
                    'cus_id' => get_array_value($row, 'cus_id', ''),
                    'tech_id' => get_array_value($row, 'tech_id', ''),
                    'name' => get_array_value($row, 'name', ''),
                    'lastname' => get_array_value($row, 'lastname', ''),
                    'status_code' => get_array_value($row, 'status_code', ''),
                    'job_type_en' => get_array_value($row, 'type_en', ''),
                    'job_type_th' => get_array_value($row, 'type_th', ''),
                    'job_status_en' => get_array_value($row, 'status_en', ''),
                    'job_status_th' => get_array_value($row, 'status_th', ''),
                    'appointment_date' => date_format(date_create(get_array_value($row, 'appointment_datetime', '')), 'd-m-Y'),
                    'appointment_date_en' => $this->date_converts->DateUS(date_format(date_create(get_array_value($row, 'appointment_datetime', '')), 'd-m-Y')),
                    'appointment_date_th' => $this->date_converts->DateThai(date_format(date_create(get_array_value($row, 'appointment_datetime', '')), 'd-m-Y')),
                    'appointment_date' => date_format(date_create(get_array_value($row, 'appointment_datetime', '')), 'd-m-Y'),
                    'appointment_time' => date_format(date_create(get_array_value($row, 'appointment_datetime', '')), 'H:i'),
                    'serial' => get_array_value($row, 'serial', ''),
                    'symptom' => get_array_value($row, 'symptom', ''),
                    'address' => get_array_value($row, 'address', ''),
                    'district' => get_array_value($row, 'district', ''),
                    'province' => get_array_value($row, 'province', ''),
                    'postal_code' => get_array_value($row, 'postal_code', ''),
                    'latitude' => get_array_value($row, 'latitude', ''),
                    'longitude' => get_array_value($row, 'longitude', ''),                   
                    'telephone' => get_array_value($row, 'telephone', ''),
                    'profile_picture' => get_array_value($row, 'pic0', ''),
                    'radius' => $this->radius($lat1,$lon1,get_array_value($row, 'latitude', ''),get_array_value($row, 'longitude', ''))." km",
                    'problem_img' => get_array_value($row, 'problem_img', ''),
                    'service_cost' => get_array_value($row, 'cost', ''),
                    'service_unit' => get_array_value($row, 'unit', ''),
                    'comment' => get_array_value($row, 'comment', ''),
                    'install_list' => get_array_value($row, 'install_list', ''),
                    'payment_status' => get_array_value($row, 'payment_status', '0'),
                );

                $install_list = explode(',', $rows['install_list']);

                $total = 0;

                if ($rows['install_list']) {

                    $install_list = explode(',', $rows['install_list']);

                    $arr = array();

                    for ($i=0; $i < count($install_list); $i++) { 
                        $install_data = explode(' - ', $install_list[$i]);

                        $data[] = array('item' => $install_data[0], 'cost' => $install_data[1], 'unit' => '฿') ;

                        $total = $total + $install_data[1];

                        if (strpos($install_data[0], 'ส่วนลดค่าติดตั้ง') === false) {
                            $arr[] = $install_data[0];
                        }
                    }

                    $rows['serial'] = implode(",",$arr);

                    $rows['install_list'] = $data;
                    $rows['install_total'] = strval($total);
                    $rows['install_total_unit'] = '฿';

                } else {
                    $rows['install_list'] = array();
                    $rows['install_total'] = '';
                    $rows['install_total_unit'] = '';
                }

                $result[] = $rows;
            }

            return $result;

        } else {

            return false;
        }

    }

    public function getCusID()
    {
        $data_where = array(
            'id' => $this->_job_id,
        );

        $this->db->select('cus_id');
        $this->db->from($this->tbl_jobs);
        $this->db->where($data_where);
        $query = $this->db->get();

        $result = array();
        $rows = array();
        if ($query->num_rows() > 0) {

            $result = $query->row();

            return $result->cus_id;

        } else {

            return false;
        }

    }

    public function getTechID()
    {
        $data_where = array(
            'id' => $this->_job_id,
        );

        $this->db->select('tech_id');
        $this->db->from($this->tbl_jobs);
        $this->db->where($data_where);
        $query = $this->db->get();

        $result = array();
        $rows = array();
        if ($query->num_rows() > 0) {

            $result = $query->row();

            return $result->tech_id;

        } else {

            return false;
        }

    }

    public function create_job()
    {

        $data = array();

        $data_where = array(
            'cus_tech.cus_id' => $this->_cus_id,
        );

        if (!is_blank($this->_cus_id)) {
            $data['cus_id'] = $this->_cus_id;
        }

        if (!is_blank($this->_serial)) {
            $data['serial'] = $this->_serial;
        }

        if (!is_blank($this->_appointment_datetime)) {
            $data['appointment_datetime'] = $this->_appointment_datetime;
        }

        if (!is_blank($this->_telephone)) {
            $data['telephone'] = $this->_telephone;
        }

        if (!is_blank($this->_symptom)) {
            $data['symptom'] = $this->_symptom;
        }

        if (!is_blank($this->_latitude)) {
            $data['latitude'] = $this->_latitude;
        }

        if (!is_blank($this->_longitude)) {
            $data['longitude'] = $this->_longitude;
        }

        if (!is_blank($this->_type_code)) {
            $data['type_code'] = $this->_type_code;
        }

        $data['status_code'] = 1;
        $data['update_datetime'] = date('Y-m-d H:i:s');
        $data['create_datetime'] = date('Y-m-d H:i:s');

        $this->db->insert($this->tbl_jobs, $data);

        $id = $this->db->insert_id();

        if (!empty($this->db->insert_id()) && $this->db->insert_id() > 0) {

            $log['job_id'] = $id;
            $log['status_code'] = 1;
            $log['update_datetime'] = date('Y-m-d H:i:s');

            $this->db->insert($this->tbl_job_status_log, $log);

            if ($this->_tech_id != 0) {

                $this->db->select('cus_id');
                $this->db->where($data_where);
                $this->db->from($this->tbl_cus_tech);

                $query = $this->db->get();

                $result = $query->row();

                if ($result) {
                    $this->db->where($data_where);
                    $msg = $this->db->update($this->tbl_cus_tech, array('tech_id' => $this->_tech_id));

                    $this->db->where('jobs.id' , $id);
                    $msg2 = $this->db->update($this->tbl_jobs, array('status_code' => 2));

                    $this->db->insert($this->tbl_notification, array('tech_id' => $this->_tech_id, 'job_id' => $id, 'job_type' => $this->_type_code, 'status' => 1, 'datetime' => date("Y-m-d H:i:s")));

                    if ($msg = 1 && $msg2 && !empty($this->db->insert_id()) && $this->db->insert_id() > 0) {
                        return $id;
                    } else {
                        return false;
                    }
                } else {
                    $this->db->insert($this->tbl_cus_tech, array('cus_id' => $this->_cus_id, 'tech_id' => $this->_tech_id));

                    $this->db->where('jobs.id' , $id);
                    $msg2 = $this->db->update($this->tbl_jobs, array('status_code' => 2));

                    if ($msg2 == 1 && !empty($this->db->insert_id()) && $this->db->insert_id() > 0) {
                        return $id;
                    } else {
                        return false;
                    }
                }
            } else {

                return $id;
            }

        } else {
            return false;
        }

    }

    public function accept_job()
    {
        $data_where = array(
            'jobs.id' => $this->_job_id,
            'status_code <' => 3,
        );

        $this->db->select('order_id');
        $this->db->from($this->tbl_jobs);
        $this->db->where($data_where);

        $query = $this->db->get();

        if ($query->num_rows() > 0) {

            $result = $query->row();

            $this->db->where(array('order_id' => $result->order_id));
            $this->db->update($this->tbl_oc_order, array('job_status' => 1));
        }

        $data['status_code'] = 3;
        $data['update_datetime'] = date("Y-m-d H:i:s");
        $data['tech_id'] = $this->_tech_id;

        $this->db->where($data_where);
        $this->db->update($this->tbl_jobs, $data);

        if ($this->db->affected_rows() > 0) {

            $data_where = array(
                'job_id' => $this->_job_id,
            );

            $this->db->delete($this->tbl_notification,$data_where);

            $log['job_id'] = $this->_job_id;
            $log['status_code'] = 3;
            $log['update_datetime'] = date('Y-m-d H:i:s');

            $this->db->insert($this->tbl_job_status_log, $log);

            return true;
        } else {
            return false;
        }
    }

    public function cancel_job()
    {

        $data_where = array(
            'id' => $this->_job_id,
            'status_code' => 3,
            'appointment_datetime >=' => date("Y-m-d H:i:s", strtotime("+1 days"))
        );

        $data['status_code'] = 5;
        $data['update_datetime'] = date("Y-m-d H:i:s");

        $this->db->where($data_where);
        $this->db->update($this->tbl_jobs, $data);

        if ($this->db->affected_rows() > 0) {

            $data_where = array(
                'job_id' => $this->_job_id,
            );

            $this->db->delete($this->tbl_notification,$data_where);

            $log['job_id'] = $this->_job_id;
            $log['status_code'] = 5;
            $log['update_datetime'] = date('Y-m-d H:i:s');

            $this->db->insert($this->tbl_job_status_log, $log);

            return true;
        } else {
            return false;
        }
    }

    public function cancel_create_job()
    {

        $data_where = array(
            'jobs.id' => $this->_job_id
        );

        $data['status_code'] = 5;
        $data['update_datetime'] = date("Y-m-d H:i:s");

        $this->db->where($data_where);
        $this->db->update($this->tbl_jobs, $data);

        if ($this->db->affected_rows() > 0) {

            $data_where = array(
                'job_id' => $this->_job_id,
            );

            $this->db->delete($this->tbl_notification,$data_where);

            $log['job_id'] = $this->_job_id;
            $log['status_code'] = 5;
            $log['update_datetime'] = date('Y-m-d H:i:s');

            $this->db->insert($this->tbl_job_status_log, $log);

            return true;
        } else {
            return false;
        }
    }

    public function tech_cancel_job()
    {

        $data_where = array(
            'jobs.id' => $this->_job_id,
            'status_code' => 2,
        );

        $data['status_code'] = 1;
        $data['update_datetime'] = date("Y-m-d H:i:s");

        $this->db->where($data_where);
        $this->db->update($this->tbl_jobs, $data);

        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function complete_job()
    {
        $data_where = array(
            'jobs.id' => $this->_job_id,
            'jobs.tech_id' => $this->_tech_id,
            'status_code >=' => 6,
            'status_code !=' => 7
        );

        $data['status_code'] = 9;
        //$data['status_code'] = 4;

        $data['update_datetime'] = date("Y-m-d H:i:s");

        $this->db->where($data_where);
        $this->db->update($this->tbl_jobs, $data);

        if ($this->db->affected_rows() > 0) {

            $log['job_id'] = $this->_job_id;
            //$log['status_code'] = 4;
            $log['status_code'] = 9;
            $log['update_datetime'] = date('Y-m-d H:i:s');

            $this->db->insert($this->tbl_job_status_log, $log);
            
            return true;
        } else {
            return false;
        }
    }

    public function complete_free_service()
    {
        $data_where = array(
            'jobs.id' => $this->_job_id,
            'jobs.tech_id' => $this->_tech_id,
            'status_code =' => 9
        );

        $data['status_code'] = 4;
        $data['update_datetime'] = date("Y-m-d H:i:s");

        $this->db->where($data_where);
        $this->db->update($this->tbl_jobs, $data);

        if ($this->db->affected_rows() > 0) {

            $log['job_id'] = $this->_job_id;
            $log['status_code'] = 4;
            $log['update_datetime'] = date('Y-m-d H:i:s');

            $this->db->insert($this->tbl_job_status_log, $log);
            
            return true;
        } else {
            return false;
        }
    }

    public function unaccepted_job()
    {

        $data_where = array(
            'jobs.id' => $this->_job_id,
            'status_code <' => 3,
        );

        $data['status_code'] = 1;
        $data['update_datetime'] = date("Y-m-d H:i:s");
        $data['tech_id'] = '';

        $this->db->where($data_where);
        $this->db->where($data_where);
        $this->db->update($this->tbl_jobs, $data);

        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return $this->_job_id;
        }
    }

    public function uncancled_job()
    {

        $data_where = array(
            'jobs.id' => $this->_job_id,
            'status_code ' => 5,
        );

        $data['status_code'] = 3;
        $data['update_datetime'] = date("Y-m-d H:i:s");

        $this->db->where($data_where);
        $this->db->update($this->tbl_jobs, $data);

        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function tech_uncancled_job()
    {

        $data_where = array(
            'jobs.id' => $this->_job_id,
            'status_code ' => 5,
        );

        $data['status_code'] = 1;
        $data['update_datetime'] = date("Y-m-d H:i:s");

        $this->db->where($data_where);
        $this->db->update($this->tbl_jobs, $data);

        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function get_last_job()
    {

        $data_where = array(
            'cus_id' => $this->_cus_id,
        );

        $this->db->select('id,tech_id,latitude,longitude,type_code');
        $this->db->where($data_where);
        $this->db->from($this->tbl_jobs);
        $this->db->limit(1);
        $this->db->order_by('id', 'DESC');

        $query = $this->db->get();

        if ($query->num_rows() > 0) {

            $result = $query->row();

            $data['id'] = $result->id;
            $data['tech_id'] = $result->tech_id;
            $data['latitude'] = $result->latitude;
            $data['longitude'] = $result->longitude;
            $data['type_code'] = $result->type_code;

            return $data;

        } else {

            return false;
        }
    }

    public function get_fcmToken($id) 
    {
        $data_where = array(
            'cus_id' => $id,
        );

        $this->db->select('device_id');
        $this->db->where($data_where);
        $this->db->from($this->tbl_user_log);

        $query = $this->db->get();

        if ($query->num_rows() > 0) {

            foreach ($query->result_array() as $row) {
                $rows = array(
                    'device_id' => get_array_value($row, 'device_id', ''),
                );

                $result[] = $rows;
            }

            return $result;

        } else {
            return false;
        }
    }

    public function check_distant($id) 
    {

        $r = 30/6371;

        $lat_min = rad2deg(deg2rad($this->_latitude) - $r);
        $lat_max = rad2deg(deg2rad($this->_latitude) + $r);

        $delta_lon = asin(sin($r)/cos(deg2rad($this->_latitude)));

        $lon_min = rad2deg(deg2rad($this->_longitude) - $delta_lon);
        $lon_max = rad2deg(deg2rad($this->_longitude) + $delta_lon);

        $data_where = array(
            'cus_mstr.id' => $id,
        );

        $this->db->select('id');
        $this->db->from($this->tbl_cus_mstr);
        $this->db->where($data_where);
        $this->db->where('cus_mstr.latitude BETWEEN '.$lat_min.' AND '.$lat_max);
        $this->db->where('cus_mstr.longitude BETWEEN '.$lon_min.' AND '.$lon_max);

        $query = $this->db->get();

        if ($query->num_rows() > 0) {

            return true;

        } else {

            return false;
        }
    }

    public function get_fcmTokens($d=20) 
    {

        $r = $d/6371;

        $lat_min = rad2deg(deg2rad($this->_latitude) - $r);
        $lat_max = rad2deg(deg2rad($this->_latitude) + $r);

        $delta_lon = asin(sin($r)/cos(deg2rad($this->_latitude)));

        $lon_min = rad2deg(deg2rad($this->_longitude) - $delta_lon);
        $lon_max = rad2deg(deg2rad($this->_longitude) + $delta_lon);

        $data_where = array(
            'cus_mstr.user_role_id' => 3,
        );

        $this->db->select('user_log.cus_id as cus_id , user_log.device_id as device_id');
        $this->db->from($this->tbl_user_log);
        $this->db->join($this->tbl_cus_mstr, 'cus_mstr.id = user_log.cus_id', 'left');
        $this->db->where($data_where);
        $this->db->where('cus_mstr.latitude BETWEEN '.$lat_min.' AND '.$lat_max);
        $this->db->where('cus_mstr.longitude BETWEEN '.$lon_min.' AND '.$lon_max);

        if ($this->_tech_id != 0) {
            $this->db->where('user_log.cus_id != '.$this->_tech_id);
        }

        $query = $this->db->get();

        if ($query->num_rows() > 0) {

            foreach ($query->result_array() as $row) {
                $rows = array(
                    'id' => get_array_value($row, 'cus_id', ''),
                    'device_id' => get_array_value($row, 'device_id', ''),
                );

                $result[] = $rows;
            }

            return $result;

        } else {

            return false;
        }
    }

    public function tech_list($d=20) 
    {

        $r = $d/6371;

        $lat_min = rad2deg(deg2rad($this->_latitude) - $r);
        $lat_max = rad2deg(deg2rad($this->_latitude) + $r);

        $delta_lon = asin(sin($r)/cos(deg2rad($this->_latitude)));

        $lon_min = rad2deg(deg2rad($this->_longitude) - $delta_lon);
        $lon_max = rad2deg(deg2rad($this->_longitude) + $delta_lon);

        $data_where = array(
            'cus_mstr.user_role_id' => 3
        );

        //$this->db->distinct();
        $this->db->select('user_log.cus_id');
        $this->db->from($this->tbl_user_log);
        $this->db->join($this->tbl_cus_mstr, 'cus_mstr.id = user_log.cus_id', 'left');
        $this->db->where($data_where);
        $this->db->where('cus_mstr.latitude BETWEEN '.$lat_min.' AND '.$lat_max);
        $this->db->where('cus_mstr.longitude BETWEEN '.$lon_min.' AND '.$lon_max);

        if ($this->_tech_id != 0) {
            $this->db->where('cus_mstr.id != '.$this->_tech_id);
        }

        $query = $this->db->get();

        if ($query->num_rows() > 0) {

            foreach ($query->result_array() as $row) {
                $rows = array(
                    'id' => get_array_value($row, 'cus_id', '')
                );

                $result[] = $rows;
            }

            return $result;

        } else {

            return false;
        }
    }

    public function get_cus_tech() 
    {

        $data_where = array(
            'cus_tech.cus_id' => $this->_cus_id,
        );

        $this->db->select('tech_id');
        $this->db->where($data_where);
        $this->db->from($this->tbl_cus_tech);

        $query = $this->db->get();

        $rows = array();

        if ($query->num_rows() > 0) {

            $result = $query->row();

            return $result->tech_id;

        } else {

            return false;
        }
    }

    public function get_tech_around()
    {

        $r = 20/6371;

        $lat_min = rad2deg(deg2rad($this->_latitude) - $r);
        $lat_max = rad2deg(deg2rad($this->_latitude) + $r);

        $delta_lon = asin(sin($r)/cos(deg2rad($this->_latitude)));

        $lon_min = rad2deg(deg2rad($this->_longitude) - $delta_lon);
        $lon_max = rad2deg(deg2rad($this->_longitude) + $delta_lon);

        $data_where = array(
            'cus_mstr.user_role_id' => 3,
        );

        $this->db->select('cus_mstr.id, cus_mstr.name, cus_mstr.lastname, technician_info.rating');
        $this->db->where('latitude BETWEEN '.$lat_min.' AND '.$lat_max);
        $this->db->where('longitude BETWEEN '.$lon_min.' AND '.$lon_max);
        $this->db->from($this->tbl_cus_mstr);
        $this->db->join($this->tbl_technician_info, 'cus_mstr.id = technician_info.tech_id', 'left');

        $query = $this->db->get();

        if ($query->num_rows() > 0) {

            foreach ($query->result_array() as $row) {
                $rows = array(
                    'id' => get_array_value($row, 'id', ''),
                    'name' => get_array_value($row, 'name', ''),
                    'lastname' => get_array_value($row, 'lastname', ''),
                    'rating' => get_array_value($row, 'rating', ''),

                );

                $result[] = $rows;
            }

            return $result;

        } else {

            return false;
        }
    }

    public function get_tech_province()
    {

        $data_where = array(
            'technician_info.province' => $this->_province,
        );

        $this->db->select('cus_mstr.id, cus_mstr.name, cus_mstr.lastname, technician_info.rating');
        $this->db->where($data_where);
        $this->db->from($this->tbl_cus_mstr);
        $this->db->join($this->tbl_technician_info, 'cus_mstr.id = technician_info.tech_id', 'left');

        $query = $this->db->get();

        if ($query->num_rows() > 0) {

            foreach ($query->result_array() as $row) {
                $rows = array(
                    'id' => get_array_value($row, 'id', ''),
                    'name' => get_array_value($row, 'name', ''),
                    'lastname' => get_array_value($row, 'lastname', ''),
                    'rating' => get_array_value($row, 'rating', ''),

                );

                $result[] = $rows;
            }

            return $result;

        } else {

            return false;
        }
    }

    public function myTech()
    {

        $data_where = array(
            'cus_id' => $this->_cus_id,
        );

        $this->db->select('tech_id');
        $this->db->where($data_where);
        $this->db->from($this->tbl_cus_tech);

        $query = $this->db->get();

        if ($query->num_rows() > 0) {

            $result = $query->row();

            return $result->tech_id;

        } else {

            return false;
        }
    }

    public function job_type()
    {
        $data_where = array(
            'id' => $this->_job_id,
        );

        $this->db->select('type_code');
        $this->db->from($this->tbl_jobs);
        $this->db->where($data_where);

        $query = $this->db->get();

        $result = array();
        $rows = array();
        if ($query->num_rows() > 0) {

            $result = $query->row();

            return $result->type_code;

        } else {

            return false;
        }

    }

    public function getServiceFee()
    {
        $data_where = array(
            'id' => $this->_job_id,
        );

        $this->db->select('service_fee');
        $this->db->from($this->tbl_jobs);
        $this->db->where($data_where);

        $query = $this->db->get();

        $result = array();
        $rows = array();
        if ($query->num_rows() > 0) {

            $result = $query->row();

            return $result->service_fee;

        } else {

            return false;
        }

    }

    public function service_list()
    {
        $this->db->select('*');
        $this->db->from($this->tbl_job_type);
        $this->db->where('status',1);

        $query = $this->db->get();

        if ($query->num_rows() > 0) {

            foreach ($query->result_array() as $row) {
                $rows = array(
                    'name_en' => get_array_value($row, 'name_en', ''),
                    'name_th' => get_array_value($row, 'name_th', ''),
                    'type_code' => get_array_value($row, 'code', ''),
                );

                $result[] = $rows;
            }

            return $result;
        } else {
            return false;
        }
    }

    public function radius($lat1,$lon1,$lat2,$lon2)
    {

        if ($lat2 and $lon2) {
            $lat1 = deg2rad($lat1);
            $lon1 = deg2rad($lon1);
            $lat2 = deg2rad($lat2);
            $lon2 = deg2rad($lon2);
        } else {
            $lat1 = deg2rad($lat1);
            $lon1 = deg2rad($lon1);
            $lat2 = deg2rad(0);
            $lon2 = deg2rad(0);
        }

        $radius =  acos(sin($lat1) * sin($lat2) + cos($lat1) * cos($lat2) * cos($lon1 - $lon2)) * 6371;

        return ceil($radius * 10) / 10; 
    }

    public function notification_count(){

        $data_where = array(
            'notification.tech_id' => $this->_tech_id,
            'notification.status' => 1,
            'jobs.appointment_datetime >=' => date("Y-m-d H:i:s")
        );

        $this->db->select('count(status) as count');
        $this->db->from($this->tbl_notification);
        $this->db->join($this->tbl_jobs, 'jobs.id = notification.job_id', 'left');
        $this->db->where($data_where);

        $query = $this->db->get();

        $result = array();
        $rows = array();
        if ($query->num_rows() > 0) {

            $result = $query->row();

            return $result->count;

        } else {

            return false;
        }
    }

    public function symptoms_list(){

        $this->db->select('name_en,name_th');
        $this->db->from($this->tbl_symptoms);
        $this->db->where('status',1);

        $query = $this->db->get();

        $result = array();

        if ($query->num_rows() > 0) {

            foreach ($query->result_array() as $row) {
                $rows = array(
                    'name_en' => get_array_value($row, 'name_en', ''),
                    'name_th' => get_array_value($row, 'name_th', ''),
                );

                $result[] = $rows;
            }

            return $result;

        } else {

            return false;
        }
    }

    public function addImage(){

        /*$data_where = array(
            'id' => $this->_job_id,
            'problem_img !=' => null,
        );

        $this->db->select('problem_img');
        $this->db->where($data_where);
        $this->db->from($this->tbl_jobs);

        $query = $this->db->get();

        if ($query->num_rows() > 0) {

            $result = $query->row();

            if (strstr($result->problem_img,$this->_problem_img)) {
                $data['problem_img'] = $result->problem_img;
            } else {
                $data['problem_img'] = $result->problem_img.','.$this->_problem_img;
            }

        } else {
            $data['problem_img'] = $this->_problem_img;
        }*/

        $data['problem_img'] = '';

        if (isset($this->_img_1)) {
            $data['problem_img'] = $this->_img_1;
        }

        if (isset($this->_img_2)) {
            $data['problem_img'] .= ','.$this->_img_2;
        }

        if (isset($this->_img_3)) {
            $data['problem_img'] .= ','.$this->_img_3;
        }

        $this->db->where(array('id' => $this->_job_id));
        $msg = $this->db->update($this->tbl_jobs, $data);

        if ($msg) {
            return true;
        } else {
            return false;
        }
    }

    public function getCleanigCostList(){

        $this->db->select('*');
        $this->db->from($this->tbl_service_cost);

        if ($this->_btu != 0 && $this->_btu <= 36000) {

            $btu = $this->_btu.' BTU';

            $this->db->where(array('btu' => $btu));
        } elseif ($this->_btu > 36000) {
            $this->db->where(array('btu' => 'over 36,000 BTU')); 
        }

        $this->db->where(array('service_type' => 1, 'status' => 1));

        $query = $this->db->get();

        $result = array();

        if ($query->num_rows() > 0) {

            foreach ($query->result_array() as $row) {
                $rows = array(
                    'service_id' => get_array_value($row, 'id', ''),
                    'service_name_en' => get_array_value($row, 'service_name_en', ''),
                    'service_name_th' => get_array_value($row, 'service_name_th', ''),
                    'cost' => get_array_value($row, 'cost', 0),
                    'unit' => get_array_value($row, 'unit', ''),
                );

                $result[] = $rows;
            }

            return $result;

        } else {

            return false;
        }
    }

    public function setServiceHistory(){

        foreach ($this->_service_fee as $row) {
            $rows = array(
                'job_id' => $this->_job_id,
                'service_id' => $row['service_id'],
                'serial' => $row['serial'],
                'cost' => $row['cost'],
            );

            $this->db->insert($this->tbl_service_history, $rows);
        }

        if (!is_blank($this->db->insert_id()) && $this->db->insert_id() > 0) {

            return true;

        } else {
            return false;
        }
    }

    public function getLastInvoice(){
        $this->db->select('invoice_id');
        $this->db->from($this->tbl_invoice);
        $this->db->order_by('invoice_id', 'DESC');
        $this->db->limit(1);

        $query = $this->db->get();

        if ($query->num_rows() > 0) {

            $result = $query->row();

            return $result->invoice_id+1;

        } else {

            return 1;
        }
    }

    public function getServiceTotal(){
        $this->db->select('sum(cost) as total');
        $this->db->from($this->tbl_service_history);
        $this->db->where(array('job_id' => $this->_job_id));

        $query = $this->db->get();

        if ($query->num_rows() > 0) {

            $result = $query->row();

            if ($result->total) {
                return $result->total;
            } else {
                return 0;
            }

        } else {

            return 0;
        }
    }

    public function getStatusCodeLog(){
        $this->db->select('name_en,name_th,update_datetime');
        $this->db->from($this->tbl_job_status_log);
        $this->db->join($this->tbl_job_status, 'job_status.code = job_status_log.status_code', 'left');
        $this->db->where(array('job_id' => $this->_job_id));

        $query = $this->db->get();

        $result = array();

        if ($query->num_rows() > 0) {

            foreach ($query->result_array() as $row) {
                $rows = array(
                    'name_en' => get_array_value($row, 'name_en', ''),
                    'name_th' => get_array_value($row, 'name_th', ''),
                    'update_datetime_en' => $this->date_converts->DateTimeUS(get_array_value($row, 'update_datetime', '')),
                    'update_datetime_th' => $this->date_converts->DateTimeThai(get_array_value($row, 'update_datetime', ''))
                );

                $result[] = $rows;
            }

            return $result;

        } else {

            return false;
        }
    }

    public function addInvoice(){

        /*if ($this->_type_code == 4) {
            $invoice['invoice_id'] = $this->_invoice + 1;
            $invoice['invoice_prefix'] = 'INV-'.date("Y");
            $invoice['update_datetime'] = date("Y-m-d H:i:s");
        }*/

        $invoice['total'] = $this->_total;
        $invoice['job_id'] = $this->_job_id;
        $invoice['payment_status'] = 0;

        $this->db->select('*');
        $this->db->from($this->tbl_invoice);
        $this->db->where(array('job_id' => $this->_job_id));

        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $this->db->where(array('job_id' => $this->_job_id));

            $this->db->update($this->tbl_invoice, $invoice);
        } else {
            $this->db->insert($this->tbl_invoice, $invoice);
        }

    }

    public function updateInvoice(){

        $invoice['invoice_id'] = $this->_invoice;
        $invoice['invoice_prefix'] = 'INV-'.date("Y");
        $invoice['payment_status'] = 1;
        $invoice['update_datetime'] = date("Y-m-d H:i:s");

        $this->db->where(array('job_id' => $this->_job_id));
        $this->db->update($this->tbl_invoice, $invoice);

        return true;

    }

    public function payment_success()
    {

        $data_where = array(
            'id' => $this->_job_id,
            'status_code' => 9
        );

        $this->db->where($data_where);
        $this->db->update($this->tbl_jobs, array('status_code' => 4));

        $data_log = array(
            'job_id' => $this->_job_id,
            'status_code' => 4,
            'update_datetime' => date("Y-m-d H:i:s"),
        );

        $this->db->where(array('job_id' => $this->_job_id));
        $this->db->insert($this->tbl_job_status_log, $data_log);
    }

    public function job_des()
    {
        $data_where = array(
            'jobs.id' => $this->_job_id,
        );

        $this->db->select('name_en');
        $this->db->from($this->tbl_jobs);
        $this->db->join($this->tbl_job_type, 'jobs.type_code = job_type.code', 'left');
        $this->db->where($data_where);

        $query = $this->db->get();

        $result = array();
        $rows = array();

        if ($query->num_rows() > 0) {

            $result = $query->row();

            return $result->name_en;

        } else {

            return false;
        }

    }

    public function addClaim($data)
    {

        $this->db->insert($this->tbl_claim, $data);

        if (!empty($this->db->insert_id()) && $this->db->insert_id() > 0) {
            return $this->db->insert_id();
        } else {
            return false;
        }

    }

    public function addClaimImg($id,$imgage){

        $this->db->where(array('id' => $id));
        $this->db->update($this->tbl_claim, array('image' => $imgage));

        return true;

    }

} // end of class
