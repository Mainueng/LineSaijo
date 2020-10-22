<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Warranty_model extends MY_Model
{

    private $_serial_product;

    private $_active_status;
    private $_indoor;
    private $_outdoor;
    private $_tech;
    private $_id;


    public function __construct()
    {
        parent::__construct();
    }


    public function setActiveStatus($active_status){
        $this->_active_status = $active_status;
    }

/*    public function getActiveStatus(){
        return $this->_active_status;
    }*/

    public function setSerial($serial)
    {
        $this->_serial_product = $serial;
    }

    public function setIndoor($indoor)
    {
        $this->_indoor = $indoor;
    }

    public function setOutdoor($outdoor)
    {
        $this->_outdoor = $outdoor;
    }

    public function setTech($tech)
    {
        $this->_tech = $tech;
    }

    public function setID($id)
    {
        $this->_id = $id;
    }

    /*public function product_warranty_info()
    {
        $this->db->select('product.*,
         model_mstr.item as item,
         model_mstr.`name` AS model_name,
         warranty.id as warranty_id,
         warranty.`day` as warranty_days,
         warranty.e_warranty_day as e_warranty_days,
         warranty.`name` as warranty_title,
         warranty.category as warranty_category');
        $this->db->join($this->tbl_model_mstr, 'product.model_mstr_id = model_mstr.id', 'left');
        $this->db->join($this->tbl_model_warranty, 'model_mstr.model_warranty_id = model_warranty.model_warranty_id', 'left');
        $this->db->join($this->tbl_warranty, 'model_warranty.warranty_id = warranty.id', 'left');
        if (!is_blank($this->_serial_product)) {
            $this->db->where('product.serial', $this->_serial_product);
        }
        $query = $this->db->get($this->tbl_product);

        $result = array();
        
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $default_warranty_date ="";
                $e_warraty_date ="";
                $e_warranty_days =0;
                $all_days =0;

                $e_warranty_days = $row->warranty_days+$row->e_warranty_days;

                if($row->active==1){
                    $all_days =  $e_warranty_days;
                    $year =  floor($e_warranty_days / 365);
                    $e_warraty_date = date('Y-m-d',strtotime($row->active_date."+ $year years"));
                    $e_warraty_date = date('Y-m-d',strtotime($e_warraty_date."+ $row->e_warranty_days days"));
                    $default_warranty_date = date('Y-m-d',strtotime($row->active_date."+ $row->warranty_days days"));

                }else{
                    $all_days = $row->warranty_days;
                    $year =  floor($row->warranty_days / 365);
                    $default_warranty_date = date('Y-m-d',strtotime($row->production."+ $year years"));
                }

                $warranty_info[] = array(
                    'warranty_id' => $row->warranty_id,
                    'warranty_title' => $row->warranty_title,
                    'warranty_days' => $row->warranty_days,
                    'e_warranty_days' => $row->e_warranty_days,
                    'default_warranty'=> date_format(date_create($default_warranty_date),"d/m/Y"),
                    'e_warranty'=> date_format(date_create($e_warraty_date),"d/m/Y"),
                    'all_warranty_days'=>$all_days,
                    'warranty_category'=>$row->warranty_category
                );
                $result = array(
                    'serial' => $row->serial,
                    'unit'=>productType($row->serial),
                    'type'=>modelType($row->item),
                    'production_day' => $row->production,
                    'product_model'=>$row->item,
                    'product_name'=>$row->model_name,
                    'active_status'=>$row->active,
                    'active_date'=> date_format(date_create($row->active_date),"d/m/Y"),
                    'warranty_info' => $warranty_info,

                );

            }

            if ($row->active == 0) {
                $data_where = array(
                    'e_warranty'=> $e_warraty_date,
                    'status'=>1,
                    'active'=>1,
                    'active_date'=>date("Y-m-d H:i:s")
                );

                $this->db->where('serial',$this->_serial_product);
                $this->db->update($this->tbl_product,$data_where);
            }

            return $result;
        } else {
            return false;
        }
    }*/

    public function product_warranty_info()
    {
        $this->db->distinct();
        $this->db->select('product.*,
         product_sale.model as item,
         warranty.id as warranty_id,
         warranty.`day` as warranty_days,
         warranty.e_warranty_day as e_warranty_days,
         warranty.`name` as warranty_title,
         warranty.category as warranty_category');
        $this->db->join($this->tbl_product_sale, 'product.serial = product_sale.serial', 'left');
        $this->db->join($this->tbl_model_warranty, 'product_sale.model_warranty_id = model_warranty.model_warranty_id', 'left');
        $this->db->join($this->tbl_warranty, 'model_warranty.warranty_id = warranty.id', 'left');
        if (!is_blank($this->_serial_product)) {
            $this->db->where('product.serial', $this->_serial_product);
        }
        $query = $this->db->get($this->tbl_product);

        $result = array();
        
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $default_warranty_date ="";
                $e_warraty_date ="";
                $e_warranty_days =0;
                $all_days =0;

                $e_warranty_days = $row->warranty_days+$row->e_warranty_days;

                if($row->active==1){
                    $all_days =  $e_warranty_days;
                    $year =  floor($e_warranty_days / 365);
                    $e_warraty_date = date('Y-m-d',strtotime($row->active_date."+ $year years"));
                    $e_warraty_date = date('Y-m-d',strtotime($e_warraty_date."+ $row->e_warranty_days days"));
                    $default_warranty_date = date('Y-m-d',strtotime($row->active_date."+ $row->warranty_days days"));

                }else{
                    $all_days = $row->warranty_days;
                    $year =  floor($row->warranty_days / 365);
                    $default_warranty_date = date('Y-m-d',strtotime($row->production."+ $year years"));
                }

                $warranty_info[] = array(
                    'warranty_id' => $row->warranty_id,
                    'warranty_title' => $row->warranty_title,
                    'warranty_days' => $row->warranty_days,
                    'e_warranty_days' => $row->e_warranty_days,
                    'default_warranty'=> date_format(date_create($default_warranty_date),"d/m/Y"),
                    'e_warranty'=> date_format(date_create($e_warraty_date),"d/m/Y"),
                    'all_warranty_days'=>$all_days,
                    'warranty_category'=>$row->warranty_category
                );
                $result = array(
                    'serial' => $row->serial,
                    'unit'=>productType($row->serial),
                    'type'=>modelType($row->item),
                    'production_day' => $row->production,
                    'product_model'=>$row->item,
                    'product_name'=>"",
                    'active_status'=>$row->active,
                    'active_date'=> date_format(date_create($row->active_date),"d/m/Y"),
                    'warranty_info' => $warranty_info,

                );

            }

            return $result;
        } else {
            return false;
        }
    }

    public function product_warranty_info_line()
    {
        $this->db->distinct();
        $this->db->select('product.*,
         product_sale.model as item,
         warranty.id as warranty_id,
         warranty.`day` as warranty_days,
         warranty.e_warranty_day as e_warranty_days,
         warranty.`name` as warranty_title,
         warranty.category as warranty_category');
        $this->db->join($this->tbl_product_sale, 'product.serial = product_sale.serial', 'left');
        $this->db->join($this->tbl_model_warranty, 'product_sale.model_warranty_id = model_warranty.model_warranty_id', 'left');
        $this->db->join($this->tbl_warranty, 'model_warranty.warranty_id = warranty.id', 'left');
        if (!is_blank($this->_serial_product)) {
            $this->db->where('product.serial', $this->_serial_product);
        }
        $query = $this->db->get($this->tbl_product);

        $result = array();
        
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $default_warranty_date ="";
                $e_warraty_date ="";
                $e_warranty_days =0;
                $all_days =0;

                $e_warranty_days = $row->warranty_days+$row->e_warranty_days;

                if($row->active==1){
                    $all_days =  $e_warranty_days;
                    $year =  floor($e_warranty_days / 365);
                    $e_warraty_date = date('Y-m-d',strtotime($row->active_date."+ $year years"));
                    $e_warraty_date = date('Y-m-d',strtotime($e_warraty_date."+ $row->e_warranty_days days"));
                    $default_warranty_date = date('Y-m-d',strtotime($row->active_date."+ $row->warranty_days days"));

                }else{
                    $all_days = $row->warranty_days;
                    $year =  floor($row->warranty_days / 365);
                    $default_warranty_date = date('Y-m-d',strtotime($row->production."+ $year years"));
                    $e_warraty_date = $default_warranty_date;
                }

                $warranty_info[] = array(
                    'warranty_id' => $row->warranty_id,
                    'warranty_title' => $row->warranty_title,
                    'warranty_days' => $row->warranty_days,
                    'e_warranty_days' => $row->e_warranty_days,
                    'default_warranty'=> date_format(date_create($default_warranty_date),"d/m/Y"),
                    'e_warranty'=> date_format(date_create($e_warraty_date),"d/m/Y"),
                    'all_warranty_days'=>$all_days,
                    'warranty_category'=>$row->warranty_category
                );
                $result = array(
                    'serial' => $row->serial,
                    'unit'=>productType($row->serial),
                    'type'=>modelType($row->item),
                    'production_day' => $row->production,
                    'product_model'=>$row->item,
                    'product_name'=>"",
                    'active_status'=>$row->active,
                    'active_date'=> date_format(date_create($row->active_date),"d/m/Y"),
                    'warranty_info' => $warranty_info,

                );

            }

            return $result;
        } else {
            return false;
        }
    }

    public function product_activate()
    {

        $data = array(
            'active'=>1,
            'status'=>1,
            'active_date'=>date("Y-m-d H:i:s"),
            'e_warranty' => date('Y-m-d  H:i:s',strtotime(date("Y-m-d H:i:s")."+ 90 days"))
        );

        $this->db->where('serial',$this->_serial_product);
        $this->db->update($this->tbl_product,$data);
        if($this->db->affected_rows() >0){
            return true;
        }else{
            return false;
        }
    }

    public function product_valid_warranty()
    {
        $query = $this->db->get_where($this->tbl_product, array('serial' => $this->_serial_product));

        if ($query->num_rows() > 0) {
            $active = 0;
            foreach ($query->result() as $row){
                $active = $row->active;
            }
            $this->setActiveStatus($active);
            return true;
        } else {
            return false;
        }
    }

    public function check_date()
    {
        $query = $this->db->get_where($this->tbl_product, array('serial' => $this->_serial_product, 'active' => 1, 'status !=' => 1));

        if ($query->num_rows() > 0) {

            $result = $query->row();

            if (date($result->active_date, strtotime("+15 minutes")) <= date("Y/m/d H:i:s")) {
                $this->db->where('serial',$this->_serial_product);
                $this->db->update($this->tbl_product,array('active'=>0));

                if($this->db->affected_rows() >0){
                    return true;
                }else{
                    return false;
                }
            } else {
                return false;
            }

        } else {
            return false;
        }
    }

    public function warranty_match()
    {

        $data = array(
            'indoor_serial' => $this->_indoor,
            'outdoor_serial' => $this->_outdoor,
            'update_datetime' => date("Y/m/d H:i:s")
        );

        $this->db->insert($this->tbl_warranty_match, $data);
    }

    public function get_warranty_match()
    {

        $data = array();

        $this->db->select('indoor_serial,outdoor_serial');
        $this->db->from($this->tbl_warranty_match);
        $this->db->or_where(array('indoor_serial' => $this->_serial_product));
        $this->db->or_where(array('outdoor_serial' => $this->_serial_product));

        $query = $this->db->get();

        if ($query->num_rows() > 0) {

            foreach ($query->result() as $row){
                $data['indoor_serial'] = $row->indoor_serial;
                $data['outdoor_serial'] = $row->outdoor_serial;
            }

            return $data;

        } else {

            return false;
        }
    }

    public function get_warranty_air_puri()
    {

        $data = array();

        $this->db->select('indoor_serial,outdoor_serial');
        $this->db->from($this->tbl_warranty_match);
        $this->db->where(array('indoor_serial' => $this->_serial_product));

        $query = $this->db->get();

        if ($query->num_rows() > 0) {

            foreach ($query->result() as $row){
                $data['air_purifier_serial'] = $row->indoor_serial;
            }

            return $data;

        } else {

            return false;
        }
    }

    public function getActiveStatus()
    {
        $this->db->select('active');
        $this->db->from($this->tbl_product);
        $this->db->where(array('serial' => $this->_serial_product));

        $query = $this->db->get();

        if ($query->num_rows() > 0) {

            $result = $query->row();

            if ($result->active == 1) {
                return true;
            }
            return false;
        } else {
            return false;
        }
    }

    public function add_warranty_to_cus()
    {
        $this->db->select('cus_id');
        $this->db->from($this->tbl_warranty_match);
        $this->db->or_where(array('indoor_serial' => $this->_serial_product));
        $this->db->or_where(array('outdoor_serial' => $this->_serial_product));

        $query = $this->db->get();

        if ($query->num_rows() > 0) {

            $result = $query->row();

            if ($result->cus_id == 0) {

                $this->db->or_where(array('indoor_serial' => $this->_serial_product));
                $this->db->or_where(array('outdoor_serial' => $this->_serial_product));
                $this->db->update($this->tbl_warranty_match,array('cus_id'=>$this->_id));

            }
        }
    }
}// end of class
