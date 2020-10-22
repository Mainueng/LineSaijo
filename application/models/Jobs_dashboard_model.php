<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Jobs_dashboard_model extends MY_Model
{

    private $_job_id;
    private $_cus_id;
    private $_tech_id;
    private $_latitude;
    private $_longitude;
    private $_status;
    private $_appointment_datetime;
    private $_telephone;
    private $_total;
    private $_service_fee;
    private $_invoice;
    private $_claim_id;
    private $_officer;
    private $_technician;
    private $_note;
    private $_serial_indoor;
    private $_serial_outdoor;
    private $_warranty;

    public function __construct()
    {
        parent::__construct();

        $this->load->library('my_libraies');

    }

    public function setJobID($job_id)
    {
        $this->_job_id = $job_id;
    }

    public function setOrderID($order_id)
    {
        $this->_order_id = $order_id;
    }

    public function setCusID($cus_id)
    {
        $this->_cus_id = $cus_id;
    }

    public function setTechID($tech_id)
    {
        $this->_tech_id = $tech_id;
    }

    public function setName($name)
    {
        $this->_name = $name;
    }

    public function setLastName($lastname)
    {
        $this->_lastname = $lastname;
    }

    public function setLatitude($latitude)
    {
        $this->_latitude = $latitude;
    }

    public function setLongitude($longitude)
    {
        $this->_longitude = $longitude;
    }

    public function setStatus($status)
    {
        $this->_status = $status;
    }

    public function setAppointmentDatetime($appointment_datetime)
    {
        $this->_appointment_datetime = $appointment_datetime;
    }

    public function setTelephone($telephone)
    {
        $this->_telephone = $telephone;
    }

    public function setTypeCode($type_code)
    {
        $this->_type_code = $type_code;
    }

    public function setSymptom($symptom)
    {
        $this->_symptom = $symptom;
    }

    public function setSerial($serial)
    {
        $this->_serial = $serial;
    }

    public function setComment($comment)
    {
        $this->_comment = $comment;
    }

    public function setInstallList($install_list)
    {
        $this->_install_list = $install_list;
    }

    public function setTotal($total)
    {
        $this->_total = $total;
    }

    public function setServiceFee($service_fee)
    {
        $this->_service_fee = $service_fee;
    }

    public function setInvoice($invoice)
    {
        $this->_invoice = $invoice;
    }

    public function setClaimID($claim_id)
    {
        $this->_claim_id = $claim_id;
    }

    public function setOfficer($officer)
    {
        $this->_officer = $officer;
    }

    public function setTechnician($technician)
    {
        $this->_technician = $technician;
    }

    public function setNote($note)
    {
        $this->_note = $note;
    }

    public function setPart($part)
    {
        $this->_part = $part;
    }

    public function setSerialIndoor($serial_indoor)
    {
        $this->_serial_indoor = $serial_indoor;
    }

    public function setSerialOutdoor($serial_outdoor)
    {
        $this->_serial_outdoor = $serial_outdoor;
    }

    public function setWarranty($warranty)
    {
        $this->_warranty = $warranty;
    }

    public function getList($data = array())
    {

        if (isset($data['filter_technician']) && $data['filter_technician'] !== '') {
            $str = explode(" ",$data['filter_technician']);

            $this->db->select('id');
            $this->db->from($this->tbl_cus_mstr);

            if (isset($str[0])) {
                $this->db->where(array(
                    'cus_mstr.name' => $str[0]
                ));
            }

            if (isset($str[1])) {
                $this->db->where(array(
                    'cus_mstr.lastname' => $str[1]
                ));
            }

            $query_tech = $this->db->get();

            if ($query_tech->num_rows() > 0) {
                $result_tech = $query_tech->row();

                $tech_id = $result_tech->id;

            } else {
                $tech_id = 0;
            }
        }

        $this->db->select('jobs.*, cus_mstr.name, cus_mstr.lastname, job_status.name_en as status, service.name_en as service');
        $this->db->from($this->tbl_jobs);
        $this->db->join($this->tbl_cus_mstr, 'jobs.cus_id = cus_mstr.id', 'left');
        $this->db->join($this->tbl_job_status, 'jobs.status_code = job_status.code', 'left');
        $this->db->join($this->tbl_service, 'jobs.type_code = service.id', 'left');

        if (isset($data['filter_job_id']) && $data['filter_job_id'] !== '') {
            $this->db->where(array(
                'jobs.id' => $data['filter_job_id']
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

        if (isset($data['filter_service']) && $data['filter_service'] !== '') {
            $this->db->where(array(
                'service.name_en' => $data['filter_service']
            ));
        }

        if (isset($data['filter_appointment_datetime']) && $data['filter_appointment_datetime'] !== '') {

            $date_time = explode(" ",$data['filter_appointment_datetime']);

            $origDate = $date_time[0];

            $date = str_replace('/', '-', $origDate );
            $newDate = date("Y-m-d", strtotime($date));

            $data['filter_appointment_datetime'] = $newDate.' '.$date_time[1];

            $this->db->where(array(
                'jobs.appointment_datetime' => $data['filter_appointment_datetime']
            ));
        }

        if (isset($data['filter_status']) && $data['filter_status'] !== '') {
            $this->db->where(array(
                'job_status.name_en' => $data['filter_status']
            ));
        }

        if (isset($data['filter_technician']) && $data['filter_technician'] !== '') {
            $this->db->where(array(
                'jobs.tech_id' => $tech_id
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
        
        $this->db->order_by('jobs.id', 'DESC');

        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $rows = array(
                    'job_id' => get_array_value($row, 'id', ''),
                    'name' => get_array_value($row, 'name', '-'),
                    'lastname' => get_array_value($row, 'lastname', ''),
                    'latitude' => get_array_value($row, 'latitude', ''),
                    'longitude' => get_array_value($row, 'longitude', ''),
                    'appointment_datetime' => get_array_value($row, 'appointment_datetime', '-'),
                    'service' => get_array_value($row, 'service', ''),
                    'status' => get_array_value($row, 'status', ''),
                    'technician' => get_array_value($row, 'tech_id', '-'),
                    'cus_id' => get_array_value($row, 'cus_id', '0'),
                );

                if ($rows['appointment_datetime'] != '-') {
                    $date = date_create($rows['appointment_datetime']);
                    $rows['appointment_datetime'] = date_format($date,"d/m/Y H:i");
                }

                if ($rows['status'] == 'notified') {
                    $rows['status'] = "created";
                }

                if ($rows['technician'] != "-") {

                    $this->db->select('name,lastname');
                    $this->db->from($this->tbl_cus_mstr);
                    $this->db->where(array('id' => $rows['technician']));

                    $query = $this->db->get();

                    if ($query->num_rows() > 0) {
                        $result_2 = $query->row();

                        $rows['technician'] = $result_2->name." ".$result_2->lastname;

                    } else {
                        $rows['technician'] = '-';
                    }
                }

                $result[] = $rows;
            }

        } else {
            return false;
        }

        return $result;

    }

    public function getListTotal($data = array())
    {

        $this->db->select('count(*) as total');
        $this->db->from($this->tbl_jobs);
        $this->db->join($this->tbl_cus_mstr, 'jobs.cus_id = cus_mstr.id', 'left');
        $this->db->join($this->tbl_job_status, 'jobs.status_code = job_status.code', 'left');
        $this->db->join($this->tbl_service, 'jobs.type_code = service.id', 'left');

        if (isset($data['filter_job_id']) && $data['filter_job_id'] !== '') {
            $this->db->where(array(
                'jobs.id' => $data['filter_job_id']
            ));
        }

        if (isset($data['filter_customer']) && $data['filter_customer'] !== '') {

            $str = explode(" ",$data['filter_customer']);

            if (isset($str[0])) {
                $this->db->where(array(
                    'cus_mstr.name' => $str[0],
                ));
            }

            if (isset($str[1])) {
                $this->db->where(array(
                    'cus_mstr.lastname' => $str[1]
                ));
            }
        }

        if (isset($data['filter_service']) && $data['filter_service'] !== '') {
            $this->db->where(array(
                'service.name_en' => $data['filter_service']
            ));
        }

        if (isset($data['filter_appointment_datetime']) && $data['filter_appointment_datetime'] !== '') {

            $date_time = explode(" ",$data['filter_appointment_datetime']);

            $origDate = $date_time[0];

            $date = str_replace('/', '-', $origDate );
            $newDate = date("Y-m-d", strtotime($date));

            $data['filter_appointment_datetime'] = $newDate.' '.$date_time[1];

            $this->db->where(array(
                'jobs.appointment_datetime' => $data['filter_appointment_datetime']
            ));
        }

        if (isset($data['filter_status']) && $data['filter_status'] !== '') {
            $this->db->where(array(
                'job_status.name_en' => $data['filter_status']
            ));
        }

        if (isset($data['filter_technician']) && $data['filter_technician'] !== '') {
            $str = explode(" ",$data['filter_technician']);

            if (isset($str[0])) {
                $this->db->where(array(
                    'cus_mstr.name' => 'Hello'
                ));
            }

            if (isset($str[1])) {
                $this->db->where(array(
                    'cus_mstr.lastname' => $str[1]
                ));
            }
        }

        $this->db->order_by('jobs.id', 'DESC');

        $query = $this->db->get();

        if ($query->num_rows() > 0) {

            $result = $query->row();

            $total = $result->total;

            return $total;
        } else {
            return false;
        }

    }

    public function getServiceList()
    {

        $this->db->select('*');
        $this->db->from($this->tbl_service);

        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $rows = array(
                    'service_name' => get_array_value($row, 'name_en', ''),
                    'id' => get_array_value($row, 'id', ''),
                );

                $result[] = $rows;
            }

        } else {
            return false;
        }

        return $result;

    }

    public function getStatusList()
    {

        $this->db->select('*');
        $this->db->from($this->tbl_job_status);

        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $rows = array(
                    'status_name' => get_array_value($row, 'name_en', ''),
                    'id' => get_array_value($row, 'id', ''),
                );

                $result[] = $rows;
            }

        } else {
            return false;
        }

        return $result;

    }

    public function getInstallList($data = array())
    {

        $data_where = array(
            'order_status_id' => 5
        );

        $air_list = array(3624,3625,3218,3626,3631,3940,3933,3934,3936,3937,3938,3939,3942,3930,3947,3944);

        $this->db->distinct();

        $this->db->select('oc_order.order_id,oc_order.firstname,oc_order.lastname,oc_order.shipping_tel,oc_order.shipping_date,oc_order.shipping_time,oc_order.job_status,oc_order.latitude,oc_order.longitude');
        $this->db->from($this->tbl_oc_order);
        $this->db->join($this->tbl_oc_order_product, 'oc_order.order_id = oc_order_product.order_id', 'left');
        $this->db->where_in('oc_order_product.product_id', $air_list);
        //$this->db->where($data_where);

        if (isset($data['filter_order_id']) && $data['filter_order_id'] !== '') {
            $this->db->where(array(
                'oc_order.order_id' => $data['filter_order_id']
            ));
        }

        if (isset($data['filter_customer']) && $data['filter_customer'] !== '') {

            $str_cus = explode(" ",$data['filter_customer']);

            if (isset($str_cus[0])) {
                $this->db->where(array(
                    'oc_order.firstname' => $str_cus[0],
                ));
            }

            if (isset($str_cus[1])) {
                $this->db->where(array(
                    'oc_order.lastname' => $str_cus[1]
                ));
            }
        }

        if (isset($data['filter_telephone']) && $data['filter_telephone'] !== '') {
            $this->db->where(array(
                'oc_order.shipping_tel' => $data['filter_telephone']
            ));
        }

        if (isset($data['filter_appointment_date']) && $data['filter_appointment_date'] !== '') {

            $origDate = $data['filter_appointment_date'];

            $date = str_replace('/', '-', $origDate );
            $newDate = date("Y-m-d", strtotime($date));

            $this->db->where(array(
                'oc_order.shipping_date' => $newDate
            ));
        }

        if (isset($data['filter_appointment_time']) && $data['filter_appointment_time'] !== '') {
            $this->db->where(array(
                'oc_order.shipping_time' => $data['filter_appointment_time']
            ));
        }

        if (isset($data['filter_status']) && $data['filter_status'] !== '') {
            $this->db->where(array(
                'job_status' => $data['filter_status']
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

        $this->db->order_by('order_id', 'DESC');

        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $rows = array(
                    'order_id' => get_array_value($row, 'order_id', ''),
                    'name' => get_array_value($row, 'firstname', ''),
                    'lastname' => get_array_value($row, 'lastname', ''),
                    'telephone' => get_array_value($row, 'shipping_tel', '-'),
                    'product' => get_array_value($row, 'product', '-'),
                    'latitude' => get_array_value($row, 'latitude', '0'),
                    'longitude' => get_array_value($row, 'longitude', '0'),
                    'date' => get_array_value($row, 'shipping_date', '-'),
                    'time' => get_array_value($row, 'shipping_time', '-'),
                    'status' => get_array_value($row, 'job_status', '0'),
                    'app' => false,
                );

                if ($rows['date'] != '-') {
                    $date = date_create($rows['date']);
                    $rows['date'] = date_format($date,"d/m/Y");
                }

                if ($rows['status'] == 1) {
                    $rows['status'] = 'Assigned';
                } else {
                    $rows['status'] = 'Wait';
                }

                $this->db->distinct();

                $this->db->select('name');
                $this->db->from($this->tbl_oc_order_product);
                $this->db->where(array('order_id' => $rows['order_id']));
                $this->db->where_in('product_id', $air_list);

                $query = $this->db->get();

                $product = array();

                if ($query->num_rows() > 0) {

                    foreach ($query->result_array() as $product_row) {
                        $product_rows = array(
                            'name' => get_array_value($product_row, 'name', ''),
                        );

                        $product[] = $product_rows['name'];
                    }

                    $rows['product'] = implode(", ",$product);
                }

                $this->db->select('id');
                $this->db->from($this->tbl_cus_mstr);
                $this->db->where(array('telephone' => $rows['telephone']));

                $query = $this->db->get();

                $product = array();

                if ($query->num_rows() > 0) {

                    $rows['app'] = true;
                }

                $result[] = $rows;
            }

            return $result;

        } else {
            return false;
        }

    }

    public function getInstallListTotal($data = array())
    {

        $data_where = array(
            'order_status_id' => 5
        );

        $air_list = array(3624,3625,3218,3626,3631,3940,3933,3934,3936,3937,3938,3939,3942,3930,3947,3944);

        $this->db->distinct();

        $this->db->select('oc_order.order_id,oc_order.firstname,oc_order.lastname,oc_order.shipping_tel,oc_order.shipping_date,oc_order.shipping_time,oc_order.job_status');
        $this->db->from($this->tbl_oc_order);
        $this->db->join($this->tbl_oc_order_product, 'oc_order.order_id = oc_order_product.order_id', 'left');
        $this->db->where_in('oc_order_product.product_id', $air_list);
        //$this->db->where($data_where);

        if (isset($data['filter_order_id']) && $data['filter_order_id'] !== '') {
            $this->db->where(array(
                'oc_order.order_id' => $data['filter_order_id']
            ));
        }

        if (isset($data['filter_customer']) && $data['filter_customer'] !== '') {

            $str_cus = explode(" ",$data['filter_customer']);

            if (isset($str_cus[0])) {
                $this->db->where(array(
                    'oc_order.firstname' => $str_cus[0],
                ));
            }

            if (isset($str_cus[1])) {
                $this->db->where(array(
                    'oc_order.lastname' => $str_cus[1]
                ));
            }
        }

        if (isset($data['filter_telephone']) && $data['filter_telephone'] !== '') {
            $this->db->where(array(
                'oc_order.shipping_tel' => $data['filter_telephone']
            ));
        }

        if (isset($data['filter_appointment_date']) && $data['filter_appointment_date'] !== '') {

            $origDate = $data['filter_appointment_date'];

            $date = str_replace('/', '-', $origDate );
            $newDate = date("Y-m-d", strtotime($date));

            $this->db->where(array(
                'oc_order.shipping_date' => $newDate
            ));
        }

        if (isset($data['filter_appointment_time']) && $data['filter_appointment_time'] !== '') {
            $this->db->where(array(
                'oc_order.shipping_time' => $data['filter_appointment_time']
            ));
        }

        if (isset($data['filter_status']) && $data['filter_status'] !== '') {
            $this->db->where(array(
                'job_status' => $data['filter_status']
            ));
        }

        $this->db->order_by('order_id', 'DESC');

        $query = $this->db->get();

        if ($query->num_rows() > 0) {

            $total = $query->num_rows();

            return $total;
        } else {
            return false;
        }
    }

    public function getJob()
    {

        $this->db->select('id,cus_id,tech_id,appointment_datetime,telephone,type_code,latitude,longitude,symptom,serial_indoor,serial_outdoor,status_code,comment,install_list,service_fee');
        $this->db->from($this->tbl_jobs);
        $this->db->where(array('id' => $this->_job_id));

        $query = $this->db->get();

        if ($query->num_rows() > 0) {

            $result = $query->row();

            $data['job_id'] = $result->id;
            $data['cus_id'] = $result->cus_id;
            $data['tech_id'] = $result->tech_id;
            $data['telephone'] = $result->telephone;
            $data['type_code'] = $result->type_code;
            $data['appointment_datetime'] = $result->appointment_datetime;
            $data['latitude'] = $result->latitude;
            $data['longitude'] = $result->longitude;
            $data['problem'] = $result->symptom;
            $data['serial_number_indoor'] = $result->serial_number_indoor;
            $data['serial_number_outdoor'] = $result->serial_number_outdoor;
            $data['status'] = $result->status_code;
            $data['comment'] = $result->comment;
            $data['install_list'] = $result->install_list;
            $data['service_fee'] = $result->service_fee;

            print_r($data);

            return $data;
        } else {
            return false;
        }
    }

    public function getTechnicianList()
    {
        $r = 30/6371;

        if (!$this->_latitude) {
            $this->_latitude = 1;
        }

        if (!$this->_longitude) {
            $this->_longitude = 1;
        }

        $lat_min = rad2deg(deg2rad($this->_latitude) - $r);
        $lat_max = rad2deg(deg2rad($this->_latitude) + $r);

        $delta_lon = asin(sin($r)/cos(deg2rad($this->_latitude)));

        $lon_min = rad2deg(deg2rad($this->_longitude) - $delta_lon);
        $lon_max = rad2deg(deg2rad($this->_longitude) + $delta_lon);

        $data_where = array(
            'cus_mstr.user_role_id' => 3,
        );
        
       /* $this->db->select('saijo_verify.tech_id, cus_mstr.name, cus_mstr.lastname');
        $this->db->from($this->tbl_saijo_verify);
        $this->db->join($this->tbl_cus_mstr, 'cus_mstr.id = saijo_verify.tech_id', 'left');
        $this->db->where('latitude BETWEEN '.$lat_min.' AND '.$lat_max);
        $this->db->where('longitude BETWEEN '.$lon_min.' AND '.$lon_max);*/

        $this->db->distinct();
        $this->db->select('user_log.cus_id as tech_id, cus_mstr.name, cus_mstr.lastname, cus_mstr.latitude, cus_mstr.longitude');
        $this->db->from($this->tbl_user_log);
        $this->db->join($this->tbl_cus_mstr, 'cus_mstr.id = user_log.cus_id', 'left');
        $this->db->where($data_where);
        $this->db->where('cus_mstr.latitude BETWEEN '.$lat_min.' AND '.$lat_max);
        $this->db->where('cus_mstr.longitude BETWEEN '.$lon_min.' AND '.$lon_max);


        $query = $this->db->get();

        if ($query->num_rows() > 0) {

            foreach ($query->result_array() as $row) {
                $rows = array(
                    'id' => get_array_value($row, 'tech_id', ''),
                    'name' => get_array_value($row, 'name', ''),
                    'lastname' => get_array_value($row, 'lastname', ''),
                    'radius' => $this->my_libraies->radius($this->_latitude,$this->_longitude,get_array_value($row, 'latitude', ''),get_array_value($row, 'longitude', ''))." km",
                );

                $result[] = $rows;
            }
            
            return $result;

        } else {

            return false;
        }
    }

    public function getCustomer()
    {
        $this->db->select('name,lastname');
        $this->db->from($this->tbl_cus_mstr);
        $this->db->where(array('id' => $this->_cus_id));

        $query = $this->db->get();

        if ($query->num_rows() > 0) {

            $result = $query->row();

            $data['name'] = $result->name;
            $data['lastname'] = $result->lastname;

            return $data['name']." ".$data['lastname'];

        } else {
            return false;
        }
    }

    public function getCustomerFormTel()
    {

        $data_where = array(
            'status' => 1,
            'telephone' => $this->_telephone
        );

        $user_role_id = array(1,4);

        $this->db->select('id,name,lastname');
        $this->db->from($this->tbl_cus_mstr);
        $this->db->where($data_where);
        $this->db->where_in('user_role_id', $user_role_id);

        $query = $this->db->get();

        if ($query->num_rows() > 0) {

            $result = $query->row();

            $data['cus_id'] = $result->id;
            $data['name'] = $result->name;
            $data['lastname'] = $result->lastname;

            return $data;

        } else {
            return false;
        }
    }

    public function get_fcmToken() 
    {
        $data_where = array(
            'cus_id' => $this->_tech_id
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

    public function editJob(){

        $data = array(
            'appointment_datetime' => $this->_appointment_datetime,
            'status_code' => $this->_status,
            'update_datetime' => date("Y-m-d H:i")
        );

        if ($this->_status == 1) {
            $data['tech_id'] = 0;
        }

        $this->db->where('id', $this->_job_id);
        $this->db->update($this->tbl_jobs, $data);

        if ($this->db->affected_rows() > 0) {

            $log['job_id'] = $this->_job_id;
            $log['status_code'] = $this->_status;
            $log['update_datetime'] = date('Y-m-d H:i:s');

            $this->db->insert($this->tbl_job_status_log, $log);
            
            return true;
        } else {
            return false;
        }
    }

    public function getOrder()
    {

        $data_where = array(
            'oc_order.order_id' => $this->_order_id
        );

        $air_list = array(3624,3625,3218,3626,3631,3940,3933,3934,3936,3937,3938,3937,3942,3930,3947,3944);

        $this->db->distinct();

        $this->db->select('oc_order.order_id,oc_order.firstname,oc_order.lastname,oc_order.shipping_tel,oc_order.shipping_date,oc_order.shipping_time,oc_order.job_status,oc_order.latitude,oc_order.longitude');
        $this->db->from($this->tbl_oc_order);
        $this->db->join($this->tbl_oc_order_product, 'oc_order.order_id = oc_order_product.order_id', 'left');
        $this->db->where_in('oc_order_product.product_id', $air_list);
        $this->db->where($data_where);

        $query = $this->db->get();

        if ($query->num_rows() > 0) {

            $result = $query->row();

            $data['order_id'] = $result->order_id;
            $data['name'] = $result->firstname;
            $data['lastname'] = $result->lastname;
            $data['telephone'] = $result->shipping_tel;
            $data['latitude'] =  $result->latitude; //13.8490449;
            $data['longitude'] = $result->longitude; //100.5411874;
            $data['appointment_time'] = $result->shipping_time;

            if (!$data['latitude']) {
                $data['latitude'] = 0;
            }

            if (!$data['longitude']) {
                $data['longitude'] = 0;
            }

            //if ($result->shipping_date != '-') {
            $date = date_create($result->shipping_date);
            $data['appointment_date'] = date_format($date,"d/m/Y");
            /*} else {
                $data['appointment_date'] = $result->shipping_date;
            }*/

            if ($result->job_status == 1) {
                $data['status'] = 'Assigned';
            } else {
                $data['status'] = 'Wait';
            }

            $this->db->distinct();

            $this->db->select('oc_order_product.name,oc_order_product.quantity,oc_order_option.value');
            $this->db->from($this->tbl_oc_order_product);
            $this->db->join($this->tbl_oc_order_option, 'oc_order_product.order_product_id = oc_order_option.order_product_id', 'left');
            $this->db->where(array('oc_order_product.order_id' => $data['order_id']));
            $this->db->where_in('oc_order_product.product_id', $air_list);

            $query = $this->db->get();

            $product = array();

            if ($query->num_rows() > 0) {

                foreach ($query->result_array() as $product_row) {
                    $product_rows = array(
                        'name' => get_array_value($product_row, 'name', '').' - '.get_array_value($product_row, 'value', ''),
                        'quantity' => get_array_value($product_row, 'quantity', ''),
                    );

                    $product[] = $product_rows['name'];
                }

                $data['product'] = implode(", ",$product);
            }

            return $data;

        } else {
            return false;
        }

    }

    public function getServiceCost()
    {

        $this->db->distinct();

        $air_list = array(3624,3625,3218,3626,3631,3940,3933,3934,3936,3937,3938,3937,3942,3930,3947,3944);

        $this->db->select('oc_order_product.name,oc_order_product.quantity,oc_order_option.value,service_cost.cost');
        $this->db->from($this->tbl_oc_order_product);
        $this->db->join($this->tbl_oc_order_option, 'oc_order_product.order_product_id = oc_order_option.order_product_id', 'left');
        $this->db->join($this->tbl_service_cost, 'service_cost.btu = oc_order_option.value', 'left');
        $this->db->where(array('oc_order_product.order_id' => $this->_order_id));
        $this->db->where(array('service_cost.status' => 1));
        $this->db->where(array('service_cost.service_type' => 4));
        $this->db->where_in('oc_order_product.product_id', $air_list);

        $query = $this->db->get();

        $product = array();

        if ($query->num_rows() > 0) {

            foreach ($query->result_array() as $row) {
                $rows = array(
                    'name' => get_array_value($row, 'name', ''),
                    'value' => get_array_value($row, 'value', ''),
                    'cost' => get_array_value($row, 'cost', '-'),
                    'quantity' => get_array_value($row, 'quantity', '1'),
                );

                $result[] = $rows;
            }

            return $result;

        } else {
            return false;
        }
    }

    public function getInstallServiceCostTotal()
    {

        $this->db->distinct();

        $air_list = array(3624,3625,3218,3626,3631,3940,3933,3934,3936,3937,3938,3937,3942,3930,3947,3944);

        $this->db->select('sum(service_cost.cost) as total');
        $this->db->from($this->tbl_oc_order_product);
        $this->db->join($this->tbl_oc_order_option, 'oc_order_product.order_product_id = oc_order_option.order_product_id', 'left');
        $this->db->join($this->tbl_service_cost, 'service_cost.btu = oc_order_option.value', 'left');
        $this->db->where(array('oc_order_product.order_id' => $this->_order_id));
        $this->db->where(array('service_cost.status' => 1));
        $this->db->where(array('service_cost.service_type' => 4));
        $this->db->where_in('oc_order_product.product_id', $air_list);

        $query = $this->db->get();

        $product = array();

        if ($query->num_rows() > 0) {

            $result = $query->row();

            return $result->total;

        } else {
            return false;
        }
    }

    public function getInstallServiceTotal()
    {

        $this->db->select('sum(cost) as total');
        $this->db->from($this->tbl_service_history);
        $this->db->where(array('job_id' => $this->_job_id));

        $query = $this->db->get();

        $product = array();

        if ($query->num_rows() > 0) {

            $result = $query->row();

            return $result->total;

        } else {
            return false;
        }
    }

    public function create_job()
    {

        $data = array();

        if (!is_blank($this->_cus_id)) {
            $data['cus_id'] = $this->_cus_id;
        }

        if (!is_blank($this->_install_list)) {
            $data['install_list'] = $this->_install_list;
        }

        if (!is_blank($this->_appointment_datetime)) {
            $data['appointment_datetime'] = $this->_appointment_datetime;
        }

        if (!is_blank($this->_telephone)) {
            $data['telephone'] = $this->_telephone;
        }

        if (!is_blank($this->_comment)) {
            $data['comment'] = $this->_comment;
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

        if (!is_blank($this->_order_id)) {
            $data['order_id'] = $this->_order_id;
        }

        if (!is_blank($this->_total)) {
            $total = $this->_total;
        }

        if (!is_blank($this->_service_fee)) {
            $data['service_fee'] = $this->_service_fee;

            if (!$data['service_fee']) {
                $total = 0;
            }
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

            if (!$this->_service_fee) {
                $invoice['invoice_id'] = $this->_invoice + 1;
                $invoice['invoice_prefix'] = 'INV-'.date("Y");
                $invoice['payment_status'] = 1;

                $invoice['job_id'] = $id;
                $invoice['total'] = $total;
                $invoice['update_datetime'] = date("Y-m-d H:i:s");

                $this->db->insert($this->tbl_invoice, $invoice);
            }

            return $id;
        } else {
            return false;
        }

    }

    public function update_job()
    {

        $data = array();

        if (!is_blank($this->_cus_id)) {
            $data['cus_id'] = $this->_cus_id;
        }

        if (!is_blank($this->_appointment_datetime)) {
            $data['appointment_datetime'] = $this->_appointment_datetime;
        }

        if (!is_blank($this->_telephone)) {
            $data['telephone'] = $this->_telephone;
        }

        if (!is_blank($this->_comment)) {
            $data['comment'] = $this->_comment;
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

        if (!is_blank($this->_order_id)) {
            $data['order_id'] = $this->_order_id;
        }

        if (!is_blank($this->_install_list)) {
            $data['install_list'] = $this->_install_list;
        }

        if (!is_blank($this->_total)) {
            $total = $this->_total;
        }

        if (!is_blank($this->_service_fee)) {
            $data['service_fee'] = $this->_service_fee;

            if (!$this->_service_fee) {
                $total = 0;
            }
            
        }

        $data['status_code'] = 1;
        $data['update_datetime'] = date('Y-m-d H:i:s');

        if ($this->_status) {
            $this->db->where('id', $this->_job_id);
            $msg = $this->db->update($this->tbl_jobs, $data);

            $appointment_date = explode(" ",$this->_appointment_datetime);

            $this->db->where('order_id', $this->_order_id);
            $this->db->update($this->tbl_oc_order, array('shipping_date' => $appointment_date[0]));

            $this->db->where('job_id', $this->_job_id);
            $this->db->update($this->tbl_invoice, array('total' => $total, 'update_datetime' => date('Y-m-d H:i:s')));
        } else {
            $this->db->where(array('order_id' => $this->_order_id));
            $this->db->update($this->tbl_oc_order, array('job_status' => 0));

            $data['status_code'] = 1;
            $data['tech_id'] = 0;

            $this->db->where('id', $this->_job_id);
            $msg = $this->db->update($this->tbl_jobs, $data);

            $this->db->where('job_id', $this->_job_id);
            $this->db->update($this->tbl_invoice, array('total' => $total, 'update_datetime' => date('Y-m-d H:i:s')));

            $appointment_date = explode(" ",$this->_appointment_datetime);

            $this->db->where('order_id', $this->_order_id);
            $this->db->update($this->tbl_oc_order, array('shipping_date' => $appointment_date[0]));
        }

        if ($msg) {
            return $this->_job_id;
        } else {
            return false;
        }
    }

    public function check_order_id()
    {
        $this->db->select('id');
        $this->db->from($this->tbl_jobs);
        $this->db->where(array('order_id' => $this->_order_id));

        $query = $this->db->get();

        if ($query->num_rows() > 0) {

            $result = $query->row();

            return $result->id;

        } else {
            return false;
        }
    }

    public function get_order_id()
    {
        $this->db->select('order_id');
        $this->db->from($this->tbl_jobs);
        $this->db->where(array('id' => $this->_job_id));

        $query = $this->db->get();

        if ($query->num_rows() > 0) {

            $result = $query->row();

            return $result->order_id;

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

    public function getJobsFormOrder()
    {
        $this->db->select('id,cus_id,tech_id,appointment_datetime,telephone,type_code,latitude,longitude,symptom,serial,status_code');
        $this->db->from($this->tbl_jobs);
        $this->db->where(array('order_id' => $this->_order_id));

        $query = $this->db->get();

        if ($query->num_rows() > 0) {

            $result = $query->row();

            $data['job_id'] = $result->id;
            $data['cus_id'] = $result->cus_id;
            $data['tech_id'] = $result->tech_id;
            $data['telephone'] = $result->telephone;
            $data['type_code'] = $result->type_code;
            $data['appointment_datetime'] = $result->appointment_datetime;
            $data['latitude'] = $result->latitude;
            $data['longitude'] = $result->longitude;
            $data['problem'] = $result->symptom;
            $data['serial'] = $result->serial;
            $data['status'] = $result->status_code;

            return $data;
        } else {
            return false;
        }
    }

    public function getServiceFee()
    {
        $this->db->select('service_fee');
        $this->db->from($this->tbl_jobs);
        $this->db->where(array('id' => $this->_job_id));

        $query = $this->db->get();

        if ($query->num_rows() > 0) {

            $result = $query->row();

            return $result->service_fee;

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

    public function setServiceHistory($job_id,$service_fee){

        foreach ($service_fee as $row) {
            $rows = array(
                'job_id' => $job_id,
                'service_id' => 4,
                'serial' => $row['name'].' '.$row['value'],
                'cost' => $row['cost'],
                'quantity' => $row['quantity']
            );

            for ($i=0; $i < $rows['quantity']; $i++) { 
                $this->db->insert($this->tbl_service_history, array(
                    'job_id' => $job_id,
                    'service_id' => 4,
                    'serial' => $row['name'].' '.$row['value'],
                    'cost' => $row['cost']
                ));
            }
        }

        if (!is_blank($this->db->insert_id()) && $this->db->insert_id() > 0) {

            return true;

        } else {
            return false;
        }
    }

    public function getClaimList($data = array())
    {

        $this->db->select('*');
        $this->db->from($this->tbl_claim);

        if (isset($data['filter_claim_id']) && $data['filter_claim_id'] !== '') {
            $this->db->where(array(
                'claim.id' => $data['filter_claim_id']
            ));
        }

        if (isset($data['filter_customer']) && $data['filter_customer'] !== '') {

            $str_cus = explode(" ",$data['filter_customer']);

            if (isset($str_cus[0])) {
                $this->db->where(array(
                    'claim.firstname' => $str_cus[0],
                ));
            }

            if (isset($str_cus[1])) {
                $this->db->where(array(
                    'claim.lastname' => $str_cus[1]
                ));
            }
        }

        if (isset($data['filter_serial_number']) && $data['filter_serial_number'] !== '') {
            $this->db->where(array(
                'claim.serial_number_indoor' => $data['filter_serial_number']
            ));
        }

        if (isset($data['filter_job_type']) && $data['filter_job_type'] !== '') {
            $this->db->where(array(
                'claim.job_type' => $data['filter_job_type']
            ));
        }

        if (isset($data['filter_date']) && $data['filter_date'] !== '') {

            $date = str_replace('/', '-', $data['filter_date'] );
            $newDate = date("Y-m-d", strtotime($date));

            $this->db->like('claim.date', $newDate, 'both');
        }

        if (isset($data['filter_status']) && $data['filter_status'] !== '') {
            $this->db->where(array(
                'claim.status' => $data['filter_status']
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
                    'claim_id' => get_array_value($row, 'id', ''),
                    'firstname' => get_array_value($row, 'firstname', '-'),
                    'lastname' => get_array_value($row, 'lastname', ''),
                    'serial_number_indoor' => get_array_value($row, 'serial_number_indoor', ''),
                    'serial_number_outdoor' => get_array_value($row, 'serial_number_outdoor', ''),
                    'job_type' => get_array_value($row, 'job_type', ''),
                    'problem' => get_array_value($row, 'problem', ''),
                    'type' => get_array_value($row, 'type', ''),
                    'date' => get_array_value($row, 'date', '-'),
                    'status' => get_array_value($row, 'status', '')
                );

                if ($rows['date'] != '-') {
                    $date = date_create($rows['date']);
                    $rows['date'] = date_format($date,"d/m/Y");
                }

                if ($rows['type'] == 'air_con') {
                    $rows['type'] = 'Air Conditioner';
                } else if ($rows['type'] == 'air_puri') {
                    $rows['type'] = 'Air Purifier';
                } else {
                    $rows['type'] = 'Part';
                }

                $result[] = $rows;
            }

        } else {
            return false;
        }

        return $result;

    }

    public function getClaimListTotal($data = array())
    {

        $this->db->select('count(*) as total    ');
        $this->db->from($this->tbl_claim);

        if (isset($data['filter_claim_id']) && $data['filter_claim_id'] !== '') {
            $this->db->where(array(
                'claim.id' => $data['filter_claim_id']
            ));
        }

        if (isset($data['filter_customer']) && $data['filter_customer'] !== '') {

            $str_cus = explode(" ",$data['filter_customer']);

            if (isset($str_cus[0])) {
                $this->db->where(array(
                    'claim.firstname' => $str_cus[0],
                ));
            }

            if (isset($str_cus[1])) {
                $this->db->where(array(
                    'claim.lastname' => $str_cus[1]
                ));
            }
        }

        if (isset($data['filter_serial_number']) && $data['filter_serial_number'] !== '') {
            $this->db->where(array(
                'claim.serial_number_indoor' => $data['filter_serial_number']
            ));
        }

        if (isset($data['filter_job_type']) && $data['filter_job_type'] !== '') {
            $this->db->where(array(
                'claim.job_type' => $data['filter_job_type']
            ));
        }

        if (isset($data['filter_date']) && $data['filter_date'] !== '') {

            $date = str_replace('/', '-', $data['filter_date'] );
            $newDate = date("Y-m-d", strtotime($date));

            $this->db->like('claim.date', $newDate, 'both');
        }

        if (isset($data['filter_status']) && $data['filter_status'] !== '') {
            $this->db->where(array(
                'claim.status' => $data['filter_status']
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

            $result = $query->row();

            $total = $result->total;

            return $total;
        } else {
            return false;
        }

    }

    public function getClaim()
    {

        $data_where = array(
            'id' => $this->_claim_id
        );

        $this->db->select('*');
        $this->db->from($this->tbl_claim);
        $this->db->where($data_where);

        $query = $this->db->get();

        if ($query->num_rows() > 0) {

            $result = $query->row();

            $data['claim_id'] = $result->id;
            $data['firstname'] = $result->firstname;
            $data['lastname'] = $result->lastname;
            $data['phone_number'] = $result->phone_number;
            $data['type'] = $result->type;
            $data['serial_number_indoor'] = $result->serial_number_indoor;
            $data['serial_number_outdoor'] = $result->serial_number_outdoor;
            $data['warranty'] = $result->warranty;
            $data['claim_date'] = $result->date;
            $data['status'] = $result->status;
            $data['problem'] = $result->problem;
            $data['update_date'] = $result->update_date;
            $data['note'] = $result->note;
            $data['officer'] = $result->officer;
            $data['technician'] = $result->technician;
            $data['job_type'] = $result->job_type;
            $data['address'] = $result->address;
            $data['part_number'] = $result->part_number;

            $i = 0;

            if ($result->image) {

                $image = explode(",",$result->image);

                for ($i=0; $i < count($image); $i++) { 
                    $data['img_'.$i] = $image[$i];
                }

            }

            $data['img_total'] = $i;

            return $data;

        } else {
            return false;
        }

    }

    public function updateClaim(){

        $data['officer'] = $this->_officer;
        $data['technician'] = $this->_technician;
        $data['status'] = $this->_status;
        $data['note'] = $this->_note;
        $data['update_date'] = date('Y-m-d H:i:s');
        $data['serial_number_indoor'] = $this->_serial_indoor;
        $data['serial_number_outdoor'] = $this->_serial_outdoor;
        $data['part_number'] = $this->_part;
        $data['warranty'] = $this->_warranty;
        
        $this->db->where(array('id' => $this->_claim_id));

        $this->db->update($this->tbl_claim, $data);

    }

    public function getLineID(){
        $data_where = array(
            'id' => $this->_claim_id
        );

        $this->db->select('line_id');
        $this->db->from($this->tbl_claim);
        $this->db->where($data_where);

        $query = $this->db->get();

        if ($query->num_rows() > 0) {

            $result = $query->row();

            return $result->line_id;

        } else {

            return false;
        }
    }

    public function getClaimReport($data = array())
    {
        $this->db->select('*');
        $this->db->from($this->tbl_claim);

        if (isset($data['filter_claim_id']) && $data['filter_claim_id'] !== '') {
            $this->db->where(array(
                'claim.id' => $data['filter_claim_id']
            ));
        }

        if (isset($data['filter_customer']) && $data['filter_customer'] !== '') {

            $str_cus = explode(" ",$data['filter_customer']);

            if (isset($str_cus[0])) {
                $this->db->where(array(
                    'claim.firstname' => $str_cus[0],
                ));
            }

            if (isset($str_cus[1])) {
                $this->db->where(array(
                    'claim.lastname' => $str_cus[1]
                ));
            }
        }

        if (isset($data['filter_serial_number']) && $data['filter_serial_number'] !== '') {
            $this->db->where(array(
                'claim.serial_number_indoor' => $data['filter_serial_number']
            ));
        }

        if (isset($data['filter_job_type']) && $data['filter_job_type'] !== '') {
            $this->db->where(array(
                'claim.job_type' => $data['filter_job_type']
            ));
        }

        if (isset($data['filter_date']) && $data['filter_date'] !== '') {

            $date = str_replace('/', '-', $data['filter_date'] );
            $newDate = date("Y-m-d", strtotime($date));

            $this->db->like('claim.date', $newDate, 'both');
        }

        if (isset($data['filter_status']) && $data['filter_status'] !== '') {
            $this->db->where(array(
                'claim.status' => $data['filter_status']
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
                    'claim_id' => get_array_value($row, 'id', ''),
                    'firstname' => get_array_value($row, 'firstname', '-'),
                    'lastname' => get_array_value($row, 'lastname', ''),
                    'phone_number' => get_array_value($row, 'phone_number', ''),
                    'type' => get_array_value($row, 'type', ''),
                    'serial_number_indoor' => get_array_value($row, 'serial_number_indoor', ''),
                    'serial_number_outdoor' => get_array_value($row, 'serial_number_outdoor', ''),
                    'warranty' => get_array_value($row, 'warranty', ''),
                    'claim_date' => get_array_value($row, 'date', '-'),
                    'status' => get_array_value($row, 'status', ''),
                    'problem' => get_array_value($row, 'problem', ''),
                    'update_date' => get_array_value($row, 'update_date', ''),
                    'note' => get_array_value($row, 'note', ''),
                    'officer' => get_array_value($row, 'officer', ''),
                    'technician' => get_array_value($row, 'technician', ''),
                    'job_type' => get_array_value($row, 'job_type', ''),
                    'address' => get_array_value($row, 'address', ''),
                    'part_number' => get_array_value($row, 'part_number', ''),
                );

                if ($rows['type'] == 'air_con') {
                    $rows['type'] = 'เครื่องปรับอากาศ';
                } else if ($rows['type'] == 'air_puri') {
                    $rows['type'] = 'เครื่องฟอกอากศ';
                } else {
                    $rows['type'] = 'อะไหล่';
                }

                if ($rows['warranty'] == 'yes') {
                    $rows['warranty'] = 'อยู่ในประกัน';
                } else {
                    $rows['warranty'] = 'หมดประกัน';
                }

                $result[] = $rows;
            }

        } else {
            return false;
        }

        return $result;

    }

    public function getClaimReportTotal($data = array())
    {

        $this->db->select('count(*) as total');
        $this->db->from($this->tbl_claim);

        if (isset($data['filter_claim_id']) && $data['filter_claim_id'] !== '') {
            $this->db->where(array(
                'claim.id' => $data['filter_claim_id']
            ));
        }

        if (isset($data['filter_customer']) && $data['filter_customer'] !== '') {

            $str_cus = explode(" ",$data['filter_customer']);

            if (isset($str_cus[0])) {
                $this->db->where(array(
                    'claim.firstname' => $str_cus[0],
                ));
            }

            if (isset($str_cus[1])) {
                $this->db->where(array(
                    'claim.lastname' => $str_cus[1]
                ));
            }
        }

        if (isset($data['filter_serial_number']) && $data['filter_serial_number'] !== '') {
            $this->db->where(array(
                'claim.serial_number_indoor' => $data['filter_serial_number']
            ));
        }

        if (isset($data['filter_job_type']) && $data['filter_job_type'] !== '') {
            $this->db->where(array(
                'claim.job_type' => $data['filter_job_type']
            ));
        }

        if (isset($data['filter_date']) && $data['filter_date'] !== '') {

            $date = str_replace('/', '-', $data['filter_date'] );
            $newDate = date("Y-m-d", strtotime($date));

            $this->db->like('claim.date', $newDate, 'both');
        }

        if (isset($data['filter_status']) && $data['filter_status'] !== '') {
            $this->db->where(array(
                'claim.status' => $data['filter_status']
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

            $result = $query->row();

            $total = $result->total;

            return $total;
        } else {
            return false;
        }

    }

} //End of Class


?>
