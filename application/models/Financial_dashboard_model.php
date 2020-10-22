<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Financial_dashboard_model extends MY_Model
{

    private $_job_id;
    
    public function setJobID($job_id)
    {
        $this->_job_id = $job_id;
    }

    public function getFinancialList($data = array())
    {

        $this->db->select('job_id,invoice_prefix,invoice_id,name,lastname,jobs.telephone,total,name_en,payment_status,invoice.update_datetime as update_datetime');
        $this->db->from($this->tbl_invoice);
        $this->db->join($this->tbl_jobs, 'jobs.id = invoice.job_id', 'left');
        $this->db->join($this->tbl_cus_mstr, 'cus_mstr.id = jobs.cus_id', 'left');
        $this->db->join($this->tbl_service, 'service.id = jobs.type_code', 'left');

        if (isset($data['filter_job_id']) && $data['filter_job_id'] !== '') {
            $this->db->where(array(
                'job_id' => $data['filter_job_id']
            ));
        }

        if (isset($data['filter_invoice_id']) && $data['filter_invoice_id'] !== '') {

            $str_cus = explode("-",$data['filter_invoice_id']);

            if (isset($str_inv[0]) && isset($str_inv[1])) {
                $this->db->where(array(
                    'invoice_prefix' => $str_inv[0].'-'.$str_inv[1],
                ));
            }

            if (isset($str_cus[2])) {
                $this->db->where(array(
                    'invoice_id' => $str_cus[2]
                ));
            }
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
                'jobs.telephone' => $data['filter_telephone']
            ));
        }

        if (isset($data['filter_service_type']) && $data['filter_service_type'] !== '') {
            $this->db->where(array(
                'service.id' => $data['filter_service_type']
            ));
        }

        if (isset($data['filter_total']) && $data['filter_total'] !== '') {
            $this->db->where(array(
                'total' => $data['filter_total']
            ));
        }

        if (isset($data['filter_payment_status']) && $data['filter_payment_status'] !== '') {
            $this->db->where(array(
                'payment_status' => $data['filter_payment_status']
            ));
        }

        if (isset($data['filter_update_datetime']) && $data['filter_update_datetime'] !== '') {

            $date_time = explode(" ",$data['filter_update_datetime']);

            $origDate = $date_time[0];

            $date = str_replace('/', '-', $origDate);
            $newDate = date("Y-m-d", strtotime($date));

            $data['filter_update_datetime'] = $newDate.' '.$date_time[1];

            $this->db->like('invoice.update_datetime',$data['filter_update_datetime'],'both');
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
        
        $this->db->order_by('job_id', 'DESC');

        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $rows = array(
                    'job_id' => get_array_value($row, 'job_id', ''),
                    'invoice_prefix' => get_array_value($row, 'invoice_prefix', '-'),
                    'invoice_id' => get_array_value($row, 'invoice_id', ''),
                    'cus_name' => get_array_value($row, 'name', ''),
                    'cus_lastname' => get_array_value($row, 'lastname', ''),
                    'telephone' => get_array_value($row, 'telephone', ''),
                    'total' => get_array_value($row, 'total', ''),
                    'type_code' => get_array_value($row, 'name_en', ''),
                    'payment_status' => get_array_value($row, 'payment_status', ''),
                    'update_datetime' => get_array_value($row, 'update_datetime', '')
                );

                if ($rows['payment_status']) {
                    $date = str_replace('-', '/', $rows['update_datetime']);
                    $rows['update_datetime'] = date("d/m/Y H:i", strtotime($date));
                } else {
                    $rows['update_datetime'] = '-';
                }

                $result[] = $rows;
            }

        } else {
            return false;
        }

        return $result;

    }

    public function getFinancialListTotal($data = array())
    {

        $this->db->select('count(job_id) as total');
        $this->db->from($this->tbl_invoice);
        $this->db->join($this->tbl_jobs, 'jobs.id = invoice.job_id', 'left');
        $this->db->join($this->tbl_cus_mstr, 'cus_mstr.id = jobs.cus_id', 'left');
        $this->db->join($this->tbl_service, 'service.id = jobs.type_code', 'left');

        if (isset($data['filter_job_id']) && $data['filter_job_id'] !== '') {
            $this->db->where(array(
                'job_id' => $data['filter_job_id']
            ));
        }

        if (isset($data['filter_invoice_id']) && $data['filter_invoice_id'] !== '') {

            $str_cus = explode("-",$data['filter_invoice_id']);

            if (isset($str_inv[0]) && isset($str_inv[1])) {
                $this->db->where(array(
                    'invoice_prefix' => $str_inv[0].'-'.$str_inv[1],
                ));
            }

            if (isset($str_cus[2])) {
                $this->db->where(array(
                    'invoice_id' => $str_cus[2]
                ));
            }
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
                'jobs.telephone' => $data['filter_telephone']
            ));
        }

        if (isset($data['filter_service_type']) && $data['filter_service_type'] !== '') {
            $this->db->where(array(
                'service.id' => $data['filter_service_type']
            ));
        }

        if (isset($data['filter_total']) && $data['filter_total'] !== '') {
            $this->db->where(array(
                'total' => $data['filter_total']
            ));
        }

        if (isset($data['filter_payment_status']) && $data['filter_payment_status'] !== '') {
            $this->db->where(array(
                'payment_status' => $data['filter_payment_status']
            ));
        }

        if (isset($data['filter_update_datetime']) && $data['filter_update_datetime'] !== '') {

            $date_time = explode(" ",$data['filter_update_datetime']);

            $origDate = $date_time[0];

            $date = str_replace('/', '-', $origDate);
            $newDate = date("Y-m-d", strtotime($date));

            $data['filter_update_datetime'] = $newDate.' '.$date_time[1];

            $this->db->like('invoice.update_datetime',$data['filter_update_datetime'],'both');
        }

        $this->db->order_by('job_id', 'DESC');

        $query = $this->db->get();

        if ($query->num_rows() > 0) {

            $result = $query->row();

            $total = $result->total;

            return $total;

        } else {
            return 0;
        }

    }

    public function getInvoiceInfo()
    {
        $this->db->select('name,lastname,jobs.telephone,cus_addr1 as address,district,province,postal_code,jobs.latitude,jobs.longitude,jobs.create_datetime,name_en,jobs.id as job_id');
        $this->db->from($this->tbl_jobs);
        $this->db->join($this->tbl_cus_mstr, 'jobs.cus_id = cus_mstr.id', 'left');
        $this->db->join($this->tbl_cus_mstr_profile, 'cus_mstr.id = cus_mstr_profile.cus_id', 'left');
        $this->db->join($this->tbl_service, 'service.id = jobs.type_code', 'left');
        $this->db->where(array('jobs.id' => $this->_job_id));

        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $rows = array(
                    'name' => get_array_value($row, 'name', ''),
                    'lastname' => get_array_value($row, 'lastname', '-'),
                    'telephone' => get_array_value($row, 'telephone', ''),
                    'address' => get_array_value($row, 'address', ''),
                    'district' => get_array_value($row, 'district', ''),
                    'province' => get_array_value($row, 'province', ''),
                    'postal_code' => get_array_value($row, 'postal_code', ''),
                    'latitude' => get_array_value($row, 'latitude', ''),
                    'longitude' => get_array_value($row, 'longitude', ''),
                    'date_added' => get_array_value($row, 'create_datetime', ''),
                    'service_type' => get_array_value($row, 'name_en', ''),
                    'job_id' => get_array_value($row, 'job_id', '')
                );

                $date_time = explode(" ",$rows['date_added']);

                $origDate = $date_time[0];

                $date = str_replace('-', '/', $origDate);
                $newDate = date("d/m/Y", strtotime($date));

                $newtime = date("H:i", strtotime($date_time[1]));

                $rows['date_added'] = $newDate.' '.$newtime;

                $result[] = $rows;
            }

            return $result;

        } else {
            return false;
        }
    }

    public function getInvoiceService()
    {
        $this->db->select('serial,service_cost.service_name_th,service_history.cost,unit,name_th');
        $this->db->from($this->tbl_service_history);
        $this->db->join($this->tbl_service_cost, 'service_history.service_id = service_cost.id', 'left');
        $this->db->join($this->tbl_service, 'service_cost.service_type = service.id', 'left');
        $this->db->where(array('service_history.job_id' => $this->_job_id));

        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $rows = array(
                    'serial' => get_array_value($row, 'serial'),
                    'service_type' => get_array_value($row, 'name_th', '-'),
                    'service_name' => get_array_value($row, 'service_name_th', '-'),
                    'cost' => get_array_value($row, 'cost', '-'),
                    'unit' => get_array_value($row, 'unit', '')
                );

                $result[] = $rows;
            }

            return $result;

        } else {
            return false;
        }
    }

    public function getInvoiceTotal()
    {
        $this->db->select('invoice.total as total, unit');
        $this->db->from($this->tbl_service_history);
        $this->db->join($this->tbl_service_cost, 'service_history.service_id = service_cost.id', 'left');
        $this->db->join($this->tbl_service, 'service_cost.service_type = service.id', 'left');
        $this->db->join($this->tbl_invoice, 'invoice.job_id = service_history.job_id', 'left');
        $this->db->where(array('service_history.job_id' => $this->_job_id));

        $query = $this->db->get();

        if ($query->num_rows() > 0) {

            $result = $query->row();

            $data['total'] = $result->total;
            $data['unit'] = $result->unit;

            return $data;

        } else {
            return 0;
        }
    }

    public function getInvoiceID()
    {
        $this->db->select('invoice_id');
        $this->db->from($this->tbl_invoice);
        $this->db->where(array('job_id' => $this->_job_id));

        $query = $this->db->get();

        if ($query->num_rows() > 0) {

            $result = $query->row();

            $invoice = $result->invoice_id;

            return $invoice;

        } else {
            return false;
        }
    }

    public function getFinancialReport($data = array())
    {

        $this->db->select('invoice.*,service.name_en');
        $this->db->from($this->tbl_invoice);
        $this->db->join($this->tbl_jobs, 'jobs.id = invoice.job_id', 'left');
        $this->db->join($this->tbl_service, 'service.id = jobs.type_code', 'left');
        $this->db->where(array('payment_status' => 1));


        if (isset($data['filter_from']) && $data['filter_from'] !== '') {

            $date = str_replace('/', '-', $data['filter_from']);
            $newDate = date("Y-m-d", strtotime($date));

            $data['filter_from'] = $newDate.' 00:00:00';

            $this->db->where('invoice.update_datetime >=',$data['filter_from']);
        }

        if (isset($data['filter_to']) && $data['filter_to'] !== '') {

            $date = str_replace('/', '-', $data['filter_to']);
            $newDate = date("Y-m-d", strtotime($date));

            $data['filter_to'] = $newDate.' 00:00:00';

            $this->db->where('invoice.update_datetime <=',$data['filter_to']);
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
        
        $this->db->order_by('job_id', 'DESC');

        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $index = 1;
            foreach ($query->result_array() as $row) {
                $rows = array(
                    'index' => $index,
                    'invoice_prefix' => get_array_value($row, 'invoice_prefix', '-'),
                    'invoice_id' => get_array_value($row, 'invoice_id', ''),
                    'service_fee' => get_array_value($row, 'total', ''),
                    'service' => get_array_value($row, 'name_en', ''),
                    'report_date' => get_array_value($row, 'update_datetime', '')
                );

                if ($rows['report_date']) {
                    $date = str_replace('-', '/', $rows['report_date']);
                    $rows['report_date'] = date("d/m/Y H:i", strtotime($date));
                } else {
                    $rows['report_date'] = '-';
                }

                $index++;

                $result[] = $rows;
            }

        } else {
            return false;
        }

        return $result;

    }

    public function getFinancialReportTotal($data = array())
    {

        $this->db->select('count(invoice.id) as total');
        $this->db->from($this->tbl_invoice);
        $this->db->join($this->tbl_jobs, 'jobs.id = invoice.job_id', 'left');
        $this->db->join($this->tbl_service, 'service.id = jobs.type_code', 'left');
        $this->db->where(array('payment_status' => 1));


        if (isset($data['filter_from']) && $data['filter_from'] !== '') {

            $date = str_replace('/', '-', $data['filter_from']);
            $newDate = date("Y-m-d", strtotime($date));

            $data['filter_from'] = $newDate.' 00:00:00';

            $this->db->where('invoice.update_datetime >=',$data['filter_from']);
        }

        if (isset($data['filter_to']) && $data['filter_to'] !== '') {

            $date = str_replace('/', '-', $data['filter_to']);
            $newDate = date("Y-m-d", strtotime($date));

            $data['filter_to'] = $newDate.' 00:00:00';

            $this->db->where('invoice.update_datetime <=',$data['filter_to']);
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
        
        $this->db->order_by('job_id', 'DESC');

        $query = $this->db->get();

        if ($query->num_rows() > 0) {

            $result = $query->row();

            $total = $result->total;

            return $total;

        } else {
            return 0;
        }

    }

    public function getServiceFeeStatus()
    {
        $this->db->select('service_fee');
        $this->db->from($this->tbl_jobs);
        $this->db->where(array('id' => $this->_job_id));

        $query = $this->db->get();

        if ($query->num_rows() > 0) {

            $result = $query->row();

            return $result->service_fee;

        } else {
            return 0;
        }
    }

} //End of Class


?>
