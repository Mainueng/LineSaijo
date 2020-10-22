<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Summary_model extends MY_Model
{

    private $_tech_id;
    private $_job_type;
    private $_job_id;
    private $_check_sheet;
    private $_before;
    private $_after;
    private $_signature;
    private $_serial;

    public function setTechID($tech_id)
    {
        $this->_tech_id = $tech_id;
    }

    public function setJobType($job_type)
    {
        $this->_job_type = $job_type;
    }

    public function setJobID($job_id)
    {
        $this->_job_id = $job_id;
    }

    public function setCheckSheet($check_sheet)
    {
        $this->_check_sheet = $check_sheet;
    }

    public function setBefore($before)
    {
        $this->_before = $before;
    }

    public function setAfter($after)
    {
        $this->_after = $after;
    }

    public function setSignature($signature)
    {
        $this->_signature = $signature;
    }

    public function setSerial($serial)
    {
        $this->_serial = $serial;
    }

    public function check_sheet()
    {
        $data_where = array(
            'id' => $this->_job_id
        );

        $this->db->select('check_sheet');
        $this->db->where($data_where);
        $this->db->from($this->tbl_jobs);

        $query = $this->db->get();

        if ($query->num_rows() > 0) {

            $result = $query->row();

            return $result->check_sheet;
        }
    }

    public function findJobType()
    {
        $data_where = array(
            'id' => $this->_job_id
        );

        $this->db->select('type_code');
        $this->db->where($data_where);
        $this->db->from($this->tbl_jobs);

        $query = $this->db->get();

        if ($query->num_rows() > 0) {

            $result = $query->row();

            return $result->type_code;
        } else {
            return false;
        }
    }

    public function getSerial()
    {
        $data_where = array(
            'id' => $this->_job_id
        );

        $this->db->select('serial');
        $this->db->where($data_where);
        $this->db->from($this->tbl_jobs);

        $query = $this->db->get();

        if ($query->num_rows() > 0) {

            $result = $query->row();

            return $result->serial;
        } else {
            return false;
        }
    }

    public function getInstallList()
    {
        $data_where = array(
            'id' => $this->_job_id
        );

        $this->db->select('install_list');
        $this->db->where($data_where);
        $this->db->from($this->tbl_jobs);

        $query = $this->db->get();

        if ($query->num_rows() > 0) {

            $result = $query->row();

            return $result->install_list;
        } else {
            return false;
        }
    }

    public function check_sheet_form()
    {

        /*if ($this->_job_type <= 3) {
            $this->_job_type = 1;
        } else {
            $this->_job_type = 2;
        }*/

        $data_where = array(
            'form_type' => 1,
            'status' => 1,
        );

        $this->db->select('header_id,header_en,header_th');
        $this->db->where($data_where);
        $this->db->from($this->tbl_summary_header);
        $this->db->order_by("header_id", "asc");


        $query = $this->db->get();

        $count = 1;

        if ($query->num_rows() > 0) {

            foreach ($query->result_array() as $row) {

                $rows = array(
                    'header_th' => $count.".".get_array_value($row, 'header_th', ''),
                    'header_en' => $count.".".get_array_value($row, 'header_en', ''),
                    'body' => $this->summary->check_sheet_title(get_array_value($row, 'header_id', ''),/*$this->_job_type*/1,$count)
                );

                $result[] = $rows;

                $count++;
            }

            return $result;

        } else {

            return false;
        }

    }

    public function check_sheet_title($title_id,$title_form,$count)
    {
        $data_where = array(
            'title_id' => $title_id,
            'title_form' => $title_form,
            'status' => 1,
        );

        $sub_count = 1;

        $this->db->select('detail_en,detail_th,input_type,unit');
        $this->db->where($data_where);
        $this->db->from($this->tbl_summary_title);
        /* $this->db->order_by("title_id", "asc");*/

        $query = $this->db->get();

        $result = array();

        foreach ($query->result_array() as $row) {

            if ($title_form == 1) {
                $rows = array(
                    'title_th' => $count.". ".$sub_count.". ".get_array_value($row, 'detail_th', ''),
                    'title_en' => $count.". ".$sub_count.". ".get_array_value($row, 'detail_en', ''),
                    "value" => "",
                );

            } else {
                $rows = array(
                    'title_th' => $sub_count.". ".get_array_value($row, 'detail_th', ''),
                    'title_en' => $sub_count.". ".get_array_value($row, 'detail_en', ''),
                    "value" => "",
                );
            }

            $serials = explode(",", $this->_serial);

            $array_count = count($serials);

            for ($i=0; $i < $array_count; $i++) { 
                $data[$i] = $serials[$i];

                $value[$i] = array(
                    'sn' => $serials[$i],
                    'value' => '',
                    'input_type' => get_array_value($row, 'input_type', ''),
                    'unit' => get_array_value($row, 'unit', ''),
                );

                if ($value[$i]['unit'] == 'boolean' || $value[$i]['unit'] == 'sound' || $value[$i]['unit'] == 'sound_error' || $value[$i]['unit'] == 'touch') {
                    $value[$i]['value'] = 'false';
                }

                if ($value[$i]['unit'] == 'Volt' || $value[$i]['unit'] == 'Amp.' || $value[$i]['unit'] == 'PSI' || $value[$i]['unit'] == '°C' || $value[$i]['unit'] == 'pisg') {
                    $value[$i]['value'] = '0';
                }

                if ($value[$i]['unit'] == 'fan_speed' || $value[$i]['unit'] == 'speed') {
                    $value[$i]['value'] = '1';
                }

            }

            $rows['value'] = $value;

            $result[] = $rows;

            $sub_count++;
        }

        return $result;
    }

    public function check_sheet_form_old()
    {

        if ($this->_job_type <= 3) {
            $this->_job_type = 1;
        } else {
            $this->_job_type = 2;
        }

        $data_where = array(
            'form_type' => $this->_job_type,
            'status' => 1,
        );

        $this->db->select('header_id,header_en,header_th');
        $this->db->where($data_where);
        $this->db->from($this->tbl_summary_header);
        $this->db->order_by("header_id", "asc");


        $query = $this->db->get();

        $count = 1;

        if ($query->num_rows() > 0) {

            foreach ($query->result_array() as $row) {

                $rows = array(
                    'header_th' => $count.".".get_array_value($row, 'header_th', ''),
                    'header_en' => $count.".".get_array_value($row, 'header_en', ''),
                    'body' => $this->summary->check_sheet_title_old(get_array_value($row, 'header_id', ''),$this->_job_type,$count)
                );

                $result[] = $rows;

                $count++;
            }

            return $result;

        } else {

            return false;
        }

    }

    public function check_sheet_title_old($title_id,$title_form,$count)
    {
        $data_where = array(
            'title_id' => $title_id,
            'title_form' => $title_form,
            'status' => 1,
        );

        $sub_count = 1;

        $this->db->select('detail_en,detail_th,input_type,unit');
        $this->db->where($data_where);
        $this->db->from($this->tbl_summary_title);
        /*$this->db->order_by("title_id", "asc");*/

        $query = $this->db->get();

        $result = array();

        foreach ($query->result_array() as $row) {

            if ($title_form == 1) {
                $rows = array(
                    'title_th' => $count.". ".$sub_count.". ".get_array_value($row, 'detail_th', ''),
                    'title_en' => $count.". ".$sub_count.". ".get_array_value($row, 'detail_en', ''),
                    "value" => "",
                    'input_type' => get_array_value($row, 'input_type', ''),
                    'unit' => get_array_value($row, 'unit', ''),
                );

            } else {
                $rows = array(
                    'title_th' => $sub_count.". ".get_array_value($row, 'detail_th', ''),
                    'title_en' => $sub_count.". ".get_array_value($row, 'detail_en', ''),
                    "value" => "",
                    'input_type' => get_array_value($row, 'input_type', ''),
                    'unit' => get_array_value($row, 'unit', ''),
                );
            }

            if ($rows['unit'] == 'boolean' || $rows['unit'] == 'sound' || $rows['unit'] == 'sound_error' || $rows['unit'] == 'touch') {
                $rows['value'] = 'false';
            }

            if ($rows['unit'] == 'Volt' || $rows['unit'] == 'Amp.' || $rows['unit'] == 'PSI' || $rows['unit'] == '°C' || $rows['unit'] == 'pisg') {
                $rows['value'] = '0';
            }

            if ($rows['unit'] == 'fan_speed' || $rows['unit'] == 'speed') {
                $rows['value'] = '1';
            }

            $result[] = $rows;

            $sub_count++;
        }

        return $result;
    }

    public function save_check_sheet()
    {

        $data = array();

        if (!is_blank($this->_check_sheet)) {
            $data['check_sheet'] = $this->_check_sheet;
        }

        $this->db->where('id', $this->_job_id);
        $msg = $this->db->update($this->tbl_jobs, $data);

        $data_where = array(
            'job_id' => $this->_job_id,
        );

        $this->db->where('job_id', $this->_job_id);
        $this->db->delete($this->tbl_notification,$data_where);

        if ($msg == 1) {
            return true;
        } else {
            return false;
        }

    }

    public function updateImage()
    {

        $data = array();

        if (!is_blank($this->_before)) {

            $data_where = array(
                'id' => $this->_job_id,
                'before_img !=' => null,
            );

            $this->db->select('before_img');
            $this->db->where($data_where);
            $this->db->from($this->tbl_jobs);

            $query = $this->db->get();

            if ($query->num_rows() > 0) {

                $result = $query->row();

                if (strstr($result->before_img,$this->_before)) {
                    $data['before_img'] = $result->before_img;
                } else {
                    $data['before_img'] = $result->before_img.','.$this->_before;
                }

            } else {

                $data['before_img'] = $this->_before;
            }
        }

        if (!is_blank($this->_after)) {

            $data_where = array(
                'id' => $this->_job_id,
                'after_img !=' => null 
            );

            $this->db->select('after_img');
            $this->db->where($data_where);
            $this->db->from($this->tbl_jobs);

            $query = $this->db->get();

            if ($query->num_rows() > 0) {

                $result = $query->row();

                if (strstr($result->after_img,$this->_after)) {
                    $data['after_img'] = $result->after_img;
                } else {
                    $data['after_img'] = $result->after_img.','.$this->_after;
                }

            } else {

                $data['after_img'] = $this->_after;
            }
        }

        if (!is_blank($this->_signature)) {
            $data['signature'] = $this->_signature;
        }

        $data['update_datetime'] = date("Y-m-d H:i:s");

        $this->db->where('id', $this->_job_id);
        $msg = $this->db->update($this->tbl_jobs, $data);

        if ($msg == 1) {
            return true;
        } else {
            return false;
        }

    }

} // end of class
