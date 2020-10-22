<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Device_model extends MY_Model
{

    private $_id;
    private $_serial;

    public function setUserID($id)
    {
        $this->_id = $id;
    }

    public function setSerial($serial)
    {
        $this->_serial = $serial;
    }

    public function setCommand($command)
    {
        $this->_command = $command;
    }

    public function setTimer($timer)
    {
        $this->_timer = $timer;
    }

    public function setLatitude($latitude)
    {
        $this->_latitude = $latitude;
    }

    public function setLongitude($longitude)
    {
        $this->_longitude = $longitude;
    }

    public function setPowerEvent($power_event)
    {
        $this->_power_event = $power_event;
    }

    public function setEnergyEvent($energy_event)
    {
        $this->_energy_event = $energy_event;
    }

    public function setTempEvent($set_temp_event)
    {
        $this->_set_temp_event = $set_temp_event;
    }

    public function setGroupEvent($group_event)
    {
        $this->_group_event = $group_event;
    }

    public function setEnableEvent($enable_event)
    {
        $this->_enable_event = $enable_event;
    }

    public function setUtcZone($utc_zone)
    {
        $this->_utc_zone = $utc_zone;
    }

    public function setMgrId($mgr_id)
    {
        $this->_mgr_id = $mgr_id;
    }

    public function setMgrDelete($mgr_delete)
    {
        $this->_mgr_delete = $mgr_delete;
    }

    public function setMgrWd($mgr_wd)
    {
        $this->_mgr_wd = $mgr_wd;
    }

    public function setAir_type_id($air_type_id)
    {
        $this->_air_type_id = $air_type_id;
    }

    public function setCusFunction($cus_function)
    {
        $this->_cus_function = $cus_function;
    }

    public function setName($name)
    {
        $this->_name = $name;
    }

    public function device_list()
    {

        $query = $this->db3->query("select cus_air_mn.*, " .
           "air_info.serial as serial,air_info.energy as air_energy,air_info.indoor as indoor,air_info.outdoor as outdoor,air_info.datetime as air_datetime,air_info.iot,air_info.display_name, ".
           "TA.* ".
           "from cus_air_mn ".  
           "LEFT JOIN air_info on cus_air_mn.air_info_serial=air_info.serial ".
           "LEFT JOIN (select * from air_mgr_timer where latitude <> '-') as TA ".
           "on cus_air_mn.air_info_serial=TA.air_info_serial  ".
           "and cus_air_mn.cus_mstr_id=TA.cus_mstr_id ".
           "where cus_air_mn.cus_mstr_id=".$this->_id);

        if ($query->num_rows() > 0) {

            foreach ($query->result_array() as $row) {
                $rows = array(
                    'device_serial' => get_array_value($row, 'serial', ''),
                    'device_name' => trim(subairname(get_array_value($row, 'indoor', ''))),
                    'display_name' => get_array_value($row, 'display_name', ''),
                    'device_ind_conf' => sub_indoor_conf(get_array_value($row, 'indoor', '')),
                    'device_ip' => trim(subip(get_array_value($row, 'air_energy', ''))),
                    'device_ssid' => trim(subssid(get_array_value($row, 'air_energy', ''))),
                    'device_wifi' => substr(bin2hex(get_array_value($row, 'air_energy', '')),30,2),
                    'device_mgr_latitude' => get_array_value($row, 'latitude', ''),
                    'device_mgr_longitude' => get_array_value($row, 'longitude', ''),
                    'device_mgr_group' => get_array_value($row, 'group', ''),
                    'device_mgr_enable' => get_array_value($row, 'enable', ''),
                    'password' => trim(subairpass(get_array_value($row, 'indoor', ''))),
                    'time_on' => subtimeon(get_array_value($row, 'indoor', '')),
                    'time_off' => subtimeoff(get_array_value($row, 'indoor', '')),
                    'iot_support' => get_array_value($row, 'iot', ''),
                    'device_datetime' => get_array_value($row, 'air_datetime', ''),
                );

                $data = array(
                    'name' => $rows['device_name'],
                    'password' => $rows['password'],
                    'time_on' => $rows['time_on'],
                    'time_off' => $rows['time_off'],
                    'ip' => $rows['device_ip'],
                    'ssid' => $rows['device_ssid']
                );

                $this->db3->where('serial', $rows['device_serial']);
                $msg = $this->db3->update($this->tbl_air_info, $data);

                if ($msg == 1) {

                    $online_status = "Online";
                    $today = date('Y-m-d H:i:s');
                    $duration = 30;
                    $dateinsec = strtotime($rows['device_datetime']) + $duration;
                    $newdate = date('Y-m-d H:i:s',$dateinsec);

                    if($newdate < $today) {
                        $online_status = "Offline";
                    }

                    $airinfo = array(
                        'ip' => $rows['device_ip'],
                        'name' => $rows['display_name'],
                        'serial' => $rows['device_serial'],
                        "ssid" => $rows['device_ssid'],
                        "status" => $online_status,
                        "latitude" => $rows['device_mgr_latitude'],
                        "longitude" => $rows['device_mgr_longitude'],
                        "group" => $rows['device_mgr_group'],
                        "enable" => $rows['device_mgr_enable'],
                        "ind_conf" => $rows['device_ind_conf'],
                        "ver" => $rows['device_wifi'],
                        "iot" => $rows['iot_support'],
                    );

                    if (!$airinfo['ind_conf']) {
                        $airinfo['ind_conf'] = '0000000000000000';
                    }

                    if (empty($airinfo['name'])) {
                        $airinfo['name'] = $rows['device_name'];
                    }

                    if (!$airinfo['ver']) {
                        $airinfo['ver'] = "00";
                    }

                    if ($airinfo['iot'] == "0") {
                        $airinfo['status'] = "Online";
                    }

                    if (!$airinfo['name']) {
                        $airinfo['name'] = "Indoor";
                    }

                    $result[] = $airinfo;

                } else {
                    $result = false;   
                }
            }

            return $result;

        } else {
            return false;
        }
    }

    public function check_device()
    {
        $this->db3->select('*');
        $this->db3->from($this->tbl_air_info);
        $this->db3->where('serial',$this->_serial);
        $query = $this->db3->get();

        if ($query->num_rows() > 0) {

            return true;

        } else {
            return false;
        }
    }

    public function device_count()
    {

        $data_where = array(
            'cus_mstr_id' => $this->_id
        );

        $this->db3->select('cus_mstr_id,air_info_serial');
        $this->db3->from($this->tbl_cus_air_mn);
        $this->db3->where($data_where);
        $query = $this->db3->get();

        if ($query->num_rows() <= 16) {

            return true;
        } else {

            return false;
        }
    }

    public function add_device()
    {

        $data_where = array(
            'cus_mstr_id' => $this->_id,
            'air_info_serial' => $this->_serial
        );

        $this->db3->select('cus_mstr_id,air_info_serial');
        $this->db3->from($this->tbl_cus_air_mn);
        $this->db3->where($data_where);
        $query = $this->db3->get();

        if ($query->num_rows() > 0) {

            return false;
        } else {

            $data['cus_mstr_id'] = $this->_id;
            $data['air_info_serial'] = $this->_serial;
            $data['datetime'] = date("Y-m-d H:i:s");   

            $this->db3->insert($this->tbl_cus_air_mn, $data);

            return true;
        }
    }

    public function delete_device()
    {

        $data_where = array(
            'cus_mstr_id' => $this->_id,
            'air_info_serial' => $this->_serial
        );

        $this->db3->select('cus_mstr_id,air_info_serial');
        $this->db3->from($this->tbl_cus_air_mn);
        $this->db3->where($data_where);
        $query = $this->db3->get();

        if ($query->num_rows() > 0) {

            $this->db3->where($data_where);
            $msg = $this->db3->delete($this->tbl_cus_air_mn,$data_where);

            $this->db3->where($data_where);
            $msg2 = $this->db3->delete($this->tbl_air_mgr_timer,$data_where);

            if ($msg == 1 && $msg2 == 1) {
                return true;
            } else {
                return false;
            }

        } else {

            return false;
        }
    }

    public function update_cmd()
    {
        $data_where = array(
            'air_info_serial' => $this->_serial
        );

        $this->db3->select('air_info_serial');
        $this->db3->from($this->tbl_cus_air_mn);
        $this->db3->where($data_where);
        $query = $this->db3->get();

        if ($query->num_rows() > 0) {

            $this->_command = trim($this->_command);

            $cmd_req =  substr($this->_command ,0,2);

            if ($cmd_req == '43') {

                /*Insert into cmd_air_data*/
                $today = date("Y-m-d H:i:s");

                $data = array(
                    'cus_mstr_id' => $this->_id,
                    'air_info_serial' => $this->_serial,
                    'cmd' => 'X'.$this->_command,
                    'datetime' => $today
                );

                $this->db3->insert($this->tbl_cus_air_cmd, $data);

                /*Update command info*/
                $data_where = array(
                    'cus_mstr_id' => $this->_id,
                    'air_info_serial' => $this->_serial
                );

                $data_update = array(
                    'cmd' => 'X'.$this->_command,
                    'datetime' => $today,
                );

                $this->db3->update($this->tbl_cus_air_mn, $data_where);

            } else {

                /*Update status info*/
                $data_where = array(
                    'cus_mstr_id' => $this->_id,
                    'air_info_serial' => $this->_serial
                );

                $data_update = array(
                    'cmd' => 'X'.$this->_command
                );

                $this->db3->update($this->tbl_cus_air_mn, $data_where);
            }

            if ($this->db3->affected_rows() > 0) {

                $this->db3->select('*');
                $this->db3->from($this->tbl_air_info);
                $this->db3->where('serial',$this->_serial);
                $query = $this->db3->get();

                if ($query->num_rows() > 0) {

                    $result = $query->row();

                    $bin2dec_en = bin2hex($result->energy);
                    $bin2dec_in = bin2hex($result->indoor);
                    $bin2dec_ou = bin2hex($result->outdoor);

                    $online_status = "Online";
                    $duration = 30; //in second
                    $dateinsec = strtotime($result->datetime) + $duration;
                    $newdate = date('Y-m-d H:i:s',$dateinsec);

                    if( $newdate < date('Y-m-d H:i:s'))
                    {
                        $online_status = "Offline";
                    }

                    $return_data = array(
                        'air_serial' => $this->_serial,
                        'energy' => $bin2dec_en,
                        'indoor' => $bin2dec_in,
                        'outdoor' => $bin2dec_ou,
                        'status' => $online_status
                    );

                    return $return_data; 

                } else {
                    return false;
                }

            } else {
                return false;
            }

        } else {

            return false;
        }
    }

    public function check_device_account()
    {
        $data_where = array(
            'air_info_serial' => $this->_serial,
            'cus_mstr_id' => $this->_id
        );

        $this->db3->select('*');
        $this->db3->from($this->tbl_cus_air_mn);
        $this->db3->where('air_info_serial',$this->_serial);
        $query = $this->db3->get();

        if ($query->num_rows() > 0) {

            return true;

        } else {
            return false;
        }
    }

    public function check_mgr_timer()
    {
        $data_where = array(
            'air_info_serial' => $this->_serial,
            'cus_mstr_id' => $this->_id
        );

        $this->db3->select('*');
        $this->db3->from($this->tbl_air_mgr_timer);
        $this->db3->where($data_where);

        if ($this->_mgr_id != 0) {
            $this->db3->where('mid',$this->_mgr_id);
        }

        $query = $this->db3->get();

        if ($query->num_rows() > 0) {

            foreach ($query->result_array() as $row) {
                $rows = array(
                    'mgr_id' => get_array_value($row, 'mid', ''),
                    'time' => get_array_value($row, 'time', ''),
                    'utc' => get_array_value($row, 'utc', ''),
                    'latitude' => get_array_value($row, 'latitude', ''),
                    'longitude' => get_array_value($row, 'longitude', ''),
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

    public function update_mrg_timer()
    {
        $data_where = array(
            'air_info_serial' => $this->_serial,
            'cus_mstr_id' => $this->_id,
            'mid' => $this->_mgr_id
        );

        $data['time'] = $this->_timer;
        $data['utc'] = $this->_utc_zone;
        $data['latitude'] = $this->_latitude;
        $data['longitude'] = $this->_longitude;
        $data['power'] = $this->_power_event;
        $data['energy'] = $this->_energy_event;
        $data['set_temp'] = $this->_set_temp_event;
        $data['group'] = $this->_group_event;
        $data['enable'] = $this->_enable_event;
        $data['weekday'] = $this->_mgr_wd;

        $this->db3->where($data_where);
        $this->db3->update($this->tbl_air_mgr_timer, $data);

        if ($this->db3->affected_rows() > 0) {
            return true;
        }

        return false;
    }

    public function add_mrg_timer()
    {

        $data['cus_mstr_id'] = $this->_id;
        $data['air_info_serial'] = $this->_serial;
        $data['time'] = $this->_timer;
        $data['utc'] = $this->_utc_zone;
        $data['latitude'] = $this->_latitude;
        $data['longitude'] = $this->_longitude;
        $data['power'] = $this->_power_event;
        $data['energy'] = $this->_energy_event;
        $data['set_temp'] = $this->_set_temp_event;
        $data['group'] = $this->_group_event;
        $data['enable'] = $this->_enable_event;
        $data['weekday'] = $this->_mgr_wd;

        $this->db3->insert($this->tbl_air_mgr_timer, $data);

        return true;
    }

    public function delete_mrg_timer()
    {

        $data_where = array(
            'air_info_serial' => $this->_serial,
            'cus_mstr_id' => $this->_id,
            'mid' => $this->_mgr_id,
        );

        $this->db3->delete($this->tbl_air_mgr_timer, $data_where);

        if ($this->db3->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function check_air_mn()
    {

        $data_where = array(
            'cus_mstr_id' => $this->_id,
            'air_info_serial' => $this->_serial
        );

        $this->db3->select('*');
        $this->db3->from($this->tbl_cus_air_mn);
        $this->db3->where($data_where);

        $query = $this->db3->get();

        if ($query->num_rows() > 0) {

            return true;
        } else {

            return false;
        }
    }

    public function air_info()
    {
        $this->db3->select('*');
        $this->db3->from($this->tbl_air_info);
        $this->db3->where('serial',$this->_serial);

        $query = $this->db3->get();

        if ($query->num_rows() > 0) {

            $result = $query->row();

            return $result->air_type_id;
        } else {

            return false;
        }
    }

    public function air_type()
    {
        $this->db3->select('*');
        $this->db3->from($this->tbl_air_type);
        $this->db3->where('id',$this->_air_type_id);

        $query = $this->db3->get();

        if ($query->num_rows() > 0) {

            foreach ($query->result_array() as $row) {
                $rows = array(
                    'name' => get_array_value($row, 'name', ''),
                    'range' => get_array_value($row, 'range', ''),
                    'model_name' => get_array_value($row, 'model_name', ''),
                    'indoor_fan_speed' => get_array_value($row, 'indoor_fan_speed', ''),
                    'timer' => get_array_value($row, 'timer', ''),
                    'turbo_aps' => get_array_value($row, 'turbo_aps', ''),
                    'double_aps' => get_array_value($row, 'double_aps', ''),
                    'swing' => get_array_value($row, 'swing', ''),
                    'mode' => get_array_value($row, 'mode', ''),
                    'sensor' => get_array_value($row, 'sensor', ''),
                    'engineering_display' => get_array_value($row, 'engineering_display', ''),
                );

                $result[] = $rows;

            }

            return $result;

        } else {

            return false;
        }
    }

    public function get_function_list()
    {

        $data_where = array(
            'cus_mstr_id' => $this->_id,
            'serial' => $this->_serial
        );

        $this->db3->select('serial,function');
        $this->db3->from($this->tbl_cus_function);
        $this->db3->where($data_where);

        $query = $this->db3->get(); 

        if ($query->num_rows() > 0) {

            foreach ($query->result_array() as $row) {
                $rows = array(
                    'serial' => get_array_value($row, 'serial', ''),
                    'function' => get_array_value($row, 'function', '')
                );
            }

            $result[] = $rows;

            return $result;

        } else {

            return false;
        }

    }

    public function add_function_list()
    {

        $data = array(
            'cus_mstr_id' => $this->_id,
            'serial' => $this->_serial,
            'function' => $this->_cus_function
        );

        $this->db3->insert($this->tbl_cus_function, $data);

        return true;

    }

    public function update_function_list()
    {

        $data_where = array(
            'cus_mstr_id' => $this->_id,
            'serial' => $this->_serial
        );

        $this->db3->where($data_where);
        $msg = $this->db3->update($this->tbl_cus_function, array('function' => $this->_cus_function));

        if ($msg == 1) {
            return true;
        } else {
            return false;
        }

    }

    public function update_device_name()
    {

        $data_where = array(
            'serial' => $this->_serial
        );

        $this->db3->where($data_where);
        $msg = $this->db3->update($this->tbl_air_info, array('display_name' => $this->_name));

        if ($msg == 1) {
            return true;
        } else {
            return false;
        }

    }

    public function check_iot()
    {
        $data_where = array(
            'serial' => $this->_serial
        );

        $this->db3->select('*');
        $this->db3->from($this->tbl_air_info);
        $this->db3->where('serial',$this->_serial);
        $query = $this->db3->get();

        if ($query->num_rows() > 0) {

            $result = $query->row();

            return $result->iot;

        } else {
            return false;
        }
    }

    public function family_list()
    {

        $data_where = array(
            'air_info_serial' => $this->_serial
        );

        $this->db3->select('cus_mstr_id');
        $this->db3->from($this->tbl_cus_air_mn);
        $this->db3->where($data_where);
        $this->db3->order_by('cus_mstr_id', 'RANDOM');
        $this->db3->limit(4);

        $query = $this->db3->get();

        if ($query->num_rows() > 0) {

            foreach ($query->result_array() as $row) {
                $rows = array(
                    'user_image' => get_array_value($row, 'cus_mstr_id', '').'.png'

                );

                $result[] = $rows;

            } 

            return $result;

        } else {
            return false;   
        }
    }

    public function getAirInfoTest($serial)
    {
        $data_where = array(
            'serial' => $serial
        );

        $this->db3->select('*');
        $this->db3->from($this->tbl_air_info);
        $this->db3->where($data_where);

        $query = $this->db3->get();

        if ($query->num_rows() > 0) {

            foreach ($query->result_array() as $row) {
                $rows = array(
                    'serial' => get_array_value($row, 'serial', ''),
                    'name' => get_array_value($row, 'name', ''),
                    'ip' => get_array_value($row, 'ip', ''),
                    'ssid' => get_array_value($row, 'ssid', ''),
                    'datetime' => get_array_value($row, 'datetime', ''),
                    'indoor' => get_array_value($row, 'indoor', ""),
                );

                $result[] = $rows;

            }

            return $result;

        } else {

            return false;
        }
    }

} // end of clss
