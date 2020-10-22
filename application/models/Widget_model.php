<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Widget_model extends MY_Model
{

    private $_id;
    private $_serial;
    private $_mid;
    private $_time;
    private $_utc;
    private $_power;
    private $_energy;
    private $_set_temp;
    private $_group;
    private $_enable;
    private $_weekday;
    private $_latitude;
    private $_longitude;

    public function setUserID($id)
    {
        $this->_id = $id;
    }

    public function setSerial($serial)
    {
        $this->_serial = $serial;
    }

    public function setMid($mid)
    {
        $this->_mid = $mid;
    }

    public function setTime($time)
    {
        $this->_time = $time;
    }

    public function setUtc($utc)
    {
        $this->_utc = $utc;
    }

    public function setPower($power)
    {
        $this->_power = $power;
    }

    public function setEnergy($energy)
    {
        $this->_energy = $energy;
    }

    public function setTemp($set_temp)
    {
        $this->_set_temp = $set_temp;
    }

    public function setGroup($group)
    {
        $this->_group = $group;
    }

    public function setEnable($enable)
    {
        $this->_enable = $enable;
    }

    public function setWeekday($weekday)
    {
        $this->_weekday = $weekday;
    }

    public function setLatitude($latitude)
    {
        $this->_latitude = $latitude;
    }

    public function setLongitude($longitude)
    {
        $this->_longitude = $longitude;
    }

    public function setErrorCode($error_code)
    {
        $this->_error_code = $error_code;
    }

    public function energy_day()
    {

        $KWHOUR = 4.0;
        /*Get current time*/
        $today = date("Y-m-d");
        $date = new DateTime($today);
        $date->sub(new DateInterval('P30D'));
        $last_date = $date->format('Y-m-d');

        $year  = Intval( substr( $last_date , 0 , 4 ) );
        $month = Intval( substr( $last_date , 5 , 2 ) );
        $day   = Intval( substr( $last_date , 8 , 2 ) );

        $data_where = array(
            'serial' => $this->_serial,
            'year' => $year
        );

        $this->db3->select('*');
        $this->db3->from($this->tbl_energy_hour);
        $this->db3->where($data_where);
        $this->db3->where('((month > '.$month.') OR (month='. $month .' and day >= '. $day .'))');
        $this->db3->order_by('month','asc');
        $this->db3->order_by('day','asc');

        $query = $this->db3->get();

        if ($query->num_rows() > 0) {

            $energy_info = array();

            foreach ($query->result_array() as $row) {
                $rows = array(
                    'hour' => get_array_value($row, 'hour', ''),
                    'day' => get_array_value($row, 'day', ''),
                    'month' => get_array_value($row, 'month', ''),
                    'year' => get_array_value($row, 'year', '')
                );

                $bill_value = floatval($rows['hour']) * $KWHOUR/1000;
                $bill_format = number_format($bill_value, 2, '.', ',');

                $key = sprintf('%04d-%02d-%02d', Intval( $rows['year'] ), Intval( $rows['month'] ), Intval( $rows['day'] ));

                if (empty($energy_info[$key])) {

                    $energy_info[$key] = array(
                        'bill' => $bill_format,
                        'hour' => 0
                    );
                }

            }
        }

        $this->db3->select('*');
        $this->db3->from($this->tbl_power_hour);
        $this->db3->where($data_where);
        $this->db3->where('((month > '.$month.') OR (month='. $month .' and day >= '. $day .'))');
        $this->db3->order_by('month','asc');
        $this->db3->order_by('day','asc');

        $query = $this->db3->get();

        if ($query->num_rows() > 0) {

            foreach ($query->result_array() as $row) {
                $rows = array(
                    'hour' => get_array_value($row, 'hour', ''),
                    'day' => get_array_value($row, 'day', ''),
                    'month' => get_array_value($row, 'month', ''),
                    'year' => get_array_value($row, 'year', '')
                );

                $key = sprintf('%04d-%02d-%02d', Intval( $rows['year'] ), Intval( $rows['month'] ), Intval( $rows['day'] ));

                $on_value = floatval($rows['hour']/3) / 3600;
                $on_format = number_format($on_value, 2, '.', ',');

                $time_format = explode('.',$on_format);

                $hour = $time_format[0];
                $min = round(floatval('0.'.$time_format[1])*60);

                $energy_info[$key]['hour'] = $hour.":".str_pad($min,2,'0',STR_PAD_LEFT);

            }

            $result[] = $energy_info;

            //return $result;
            return $energy_info;
        }

        return false;
    }

    public function energy_month()
    {

        $KWHOUR = 4.0;
        /*Get current time*/
        $today = date("Y-m-d");
        $date = new DateTime($today);
        $date->sub(new DateInterval('P30D'));
        $last_date = $date->format('Y-m-d');

        $year  = Intval( substr( $last_date , 0 , 4 ) );
        $month = Intval( substr( $last_date , 5 , 2 ) );
        $day   = Intval( substr( $last_date , 8 , 2 ) );

        $month = ($month + 13) % 12;

        if ($month < 12) {
            $year  = $year  - 1;                
        }

        $data_where = array(
            'serial' => $this->_serial
        );

        $this->db3->select('*');
        $this->db3->from($this->tbl_energy_hour);
        $this->db3->where($data_where);
        $this->db3->where('((year > '.$year.') OR (year='. $year .' and month >= '. $month .'))');
        $this->db3->order_by('year','asc');
        $this->db3->order_by('month','asc');
        $this->db3->order_by('day','asc');

        $query = $this->db3->get();

        if ($query->num_rows() > 0) {

            $energy_info = array();

            foreach ($query->result_array() as $row) {
                $rows = array(
                    'hour' => get_array_value($row, 'hour', ''),
                    'month' => get_array_value($row, 'month', ''),
                    'year' => get_array_value($row, 'year', '')
                );

                $bill_value = floatval($rows['hour']) * $KWHOUR/1000;

                $key = sprintf('%04d-%02d', Intval($rows['year']), Intval($rows['month']));

                if (empty($energy_info[$key])) {

                    $bill_format = number_format($bill_value, 2, '.', ',');

                    $energy_info[$key] = array(
                        'bill' => ''.$bill_format,
                        'hour' => 0,
                    );

                } else {
                    $bill_value  = $bill_value + floatval( $energy_info[$key]['bill'] );
                    $bill_format = number_format($bill_value, 2, '.', ','); 
                    $energy_info[$key]['bill'] = $bill_format;
                }

            }
        }

        $this->db3->select('*');
        $this->db3->from($this->tbl_power_hour);
        $this->db3->where($data_where);
        $this->db3->where('((year > '.$year.') OR (year='. $year .' and month >= '. $month .'))');
        $this->db3->order_by('year','asc');
        $this->db3->order_by('month','asc');
        $this->db3->order_by('day','asc');

        $query = $this->db3->get();

        if ($query->num_rows() > 0) {

            foreach ($query->result_array() as $row) {
                $rows = array(
                    'hour' => get_array_value($row, 'hour', ''),
                    'month' => get_array_value($row, 'month', ''),
                    'year' => get_array_value($row, 'year', '')
                );

                $key = sprintf('%04d-%02d', Intval($rows['year']), Intval($rows['month']));

                if (!empty($energy_info[$key])) {

                    $on_value = floatval($rows['hour']/3) / 3600;
                    $on_value = $on_value + floatval( $energy_info[$key]['hour'] );

                    $on_format = number_format($on_value, 2, '.', ',');

                    $time_format = explode('.',$on_format);

                    $hour = $time_format[0];
                    $min = round(floatval('0.'.$time_format[1])*60);

                    $energy_info[$key]['hour'] = $hour.":".str_pad($min,2,'0',STR_PAD_LEFT);
                }

            }

            $result[] = $energy_info;

            //return $result;
            return $energy_info;
        }

        return false;
    }

    public function schedule_list()
    {
        $data_where = array(
            'cus_mstr_id' => $this->_id,
            'air_info_serial' => $this->_serial
        );

        $this->db3->select('*');
        $this->db3->from($this->tbl_air_mgr_timer);
        $this->db3->where($data_where);

        $query = $this->db3->get();

        if ($query->num_rows() > 0) {

            foreach ($query->result_array() as $row) {
                $rows = array(
                    'mgr_id' => get_array_value($row, 'mid', ''),
                    'time' => get_array_value($row, 'time', ''),
                    'utc' => get_array_value($row, 'utc', ''),
                    'power' => get_array_value($row, 'power', ''),
                    'energy' => get_array_value($row, 'energy', ''),
                    'set_temp' => get_array_value($row, 'set_temp', ''),
                    'group' => get_array_value($row, 'group', ''),
                    'enable' => get_array_value($row, 'enable', ''),
                    'weekday' => get_array_value($row, 'weekday', ''),
                );

                $result[] = $rows;

            }

            return $result;
        }

        return false;
    }

    public function schedule_info()
    {
        $data_where = array(
            'cus_mstr_id' => $this->_id,
            'air_info_serial' => $this->_serial,
            'mid' => $this->_mid
        );

        $this->db3->select('*');
        $this->db3->from($this->tbl_air_mgr_timer);
        $this->db3->where($data_where);

        $query = $this->db3->get();

        if ($query->num_rows() > 0) {

            foreach ($query->result_array() as $row) {
                $rows = array(
                    'mgr_id' => get_array_value($row, 'mid', ''),
                    'time' => get_array_value($row, 'time', ''),
                    'utc' => get_array_value($row, 'utc', ''),
                    'power' => get_array_value($row, 'power', ''),
                    'energy' => get_array_value($row, 'energy', ''),
                    'set_temp' => get_array_value($row, 'set_temp', ''),
                    'group' => get_array_value($row, 'group', ''),
                    'enable' => get_array_value($row, 'enable', ''),
                    'weekday' => get_array_value($row, 'weekday', ''),
                );

                $result[] = $rows;

            }

            return $result;
        }

        return false;
    }

    public function get_schedule()
    {
        $data_where = array(
            'cus_mstr_id' => $this->_id,
            'air_info_serial' => $this->_serial,
            'time' => $this->_time,
            'utc' => $this->_utc,
            'power' => $this->_power,
            'energy' => $this->_energy,
            'set_temp' => $this->_set_temp,
            'group' => $this->_group,
            'enable' => $this->_enable,
            'weekday' => $this->_weekday,
            'latitude' => '-',
            'longitude' => '-'
        );

        $this->db3->select('*');
        $this->db3->from($this->tbl_air_mgr_timer);
        $this->db3->where($data_where);

        $query = $this->db3->get();

        if ($query->num_rows() > 0) {

            return true;
        }

        return false;
    }

    public function add_schedule()
    {
        $data = array(
            'cus_mstr_id' => $this->_id,
            'air_info_serial' => $this->_serial,
            'time' => $this->_time,
            'utc' => $this->_utc,
            'power' => $this->_power,
            'energy' => $this->_energy,
            'set_temp' => $this->_set_temp,
            'group' => $this->_group,
            'enable' => $this->_enable,
            'weekday' => $this->_weekday,
            'latitude' => '-',
            'longitude' => '-'
        );

        $this->db3->insert($this->tbl_air_mgr_timer, $data);

        if (!empty($this->db3->insert_id()) && $this->db3->insert_id() > 0) {
            return true;
        }

        return false;
    }

    public function check_schedule()
    {
        $data_where = array(
            'cus_mstr_id' => $this->_id,
            'air_info_serial' => $this->_serial,
            'mid' => $this->_mid,
        );

        $this->db3->select('*');
        $this->db3->from($this->tbl_air_mgr_timer);
        $this->db3->where($data_where);

        $query = $this->db3->get();

        if ($query->num_rows() > 0) {

            return true;
        }

        return false;
    }

    public function update_schedule()
    {
        $data = array(
            'time' => $this->_time,
            'utc' => $this->_utc,
            'power' => $this->_power,
            'energy' => $this->_energy,
            'set_temp' => $this->_set_temp,
            'group' => $this->_group,
            'enable' => $this->_enable,
            'weekday' => $this->_weekday,
            'latitude' => '-',
            'longitude' => '-'
        );

        $data_where = array(
            'cus_mstr_id' => $this->_id,
            'air_info_serial' => $this->_serial,
            'mid' => $this->_mid
        );

        $this->db3->where($data_where);
        $msg = $this->db3->update($this->tbl_air_mgr_timer, $data);

        if ($msg == 1) {
            return true;
        }

        return false;
    }

    public function delete_schedule()
    {

        $data_where = array(
            'cus_mstr_id' => $this->_id,
            'air_info_serial' => $this->_serial,
            'mid' => $this->_mid
        );

        $this->db3->where($data_where);
        $this->db3->delete($this->tbl_air_mgr_timer);

        if ($this->db3->affected_rows() > 0) {
            return true;
        }

        return false;
    }

    public function device_location()
    {
        $data_where = array(
            'cus_mstr_id' => $this->_id,
            'air_info_serial' => $this->_serial,
            'latitude !=' => '-',
            'longitude !=' => '-',
        );

        $this->db3->select('*');
        $this->db3->from($this->tbl_air_mgr_timer);
        $this->db3->where($data_where);

        $query = $this->db3->get();

        if ($query->num_rows() > 0) {

            foreach ($query->result_array() as $row) {
                $rows = array(
                    'latitude' => get_array_value($row, 'latitude', ''),
                    'longitude' => get_array_value($row, 'longitude', '')
                );

                $result[] = $rows;

            }

            return $result;
        }

        return false;
    }

    public function update_location()
    {
        $data_where = array(
            'cus_mstr_id' => $this->_id,
            'air_info_serial' => $this->_serial,
            'latitude !=' => '-',
            'longitude !=' => '-',
        );

        $data = array(
            'cus_mstr_id' => $this->_id,
            'air_info_serial' => $this->_serial,
            'power' => '-',
            'energy' => '-',
            'set_temp' => '-',
            'group' => 0,
            'enable' => 1,
            'weekday' => 0,
            'latitude' => $this->_latitude,
            'longitude' => $this->_longitude
        );

        $this->db3->select('*');
        $this->db3->from($this->tbl_air_mgr_timer);
        $this->db3->where($data_where);

        $query = $this->db3->get();

        if ($query->num_rows() > 0) {

            $this->db3->where($data_where);
            $msg = $this->db3->update($this->tbl_air_mgr_timer, $data);

            if ($msg == 1) {
                return true;
            }

            return false;

        } else {

            $this->db3->insert($this->tbl_air_mgr_timer, $data);

            if (!empty($this->db3->insert_id()) && $this->db3->insert_id() > 0) {
                return true;
            }

            return false;
        }
    }

    public function all_location()
    {
        $data_where = array(
            'cus_mstr_id' => $this->_id,
            'latitude !=' => '-',
            'longitude !=' => '-',
        );

        $this->db3->select('*');
        $this->db3->from($this->tbl_air_mgr_timer);
        $this->db3->where($data_where);

        $query = $this->db3->get();

        if ($query->num_rows() > 0) {

            foreach ($query->result_array() as $row) {
                $rows = array(
                    'serial' => get_array_value($row, 'air_info_serial', ''),
                    'latitude' => get_array_value($row, 'latitude', ''),
                    'longitude' => get_array_value($row, 'longitude', '')
                );

                $result[] = $rows;

            }

            return $result;
        }

        return false;
    }

    public function error_code_title()
    {
        $data_where = array(
            'code_id' => $this->_error_code
        );

        $this->db->select('*');
        $this->db->from($this->tbl_error_code);
        $this->db->where($data_where);

        $query = $this->db->get();

        if ($query->num_rows() > 0) {

            foreach ($query->result_array() as $row) {
                $rows = array(
                    'unit_en' => get_array_value($row, 'unit_en', ''),
                    'unit_th' => get_array_value($row, 'unit_th', ''),
                    'title_en' => get_array_value($row, 'title_en', ''),
                    'title_th' => get_array_value($row, 'title_th', '')
                );

                $result[] = $rows;

            }

            return $result;
        }

        return false;
    }

} // end of clss
