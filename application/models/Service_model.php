<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Service_model extends MY_Model
{

    private $_id;
    private $_name;
    private $_name_en;
    private $_description;
    private $_description_en;
    private $_instruction;
    private $_saijo_prices;
    private $_market_prices;


    public function setServiceId($id)
    {
        $this->_id = $id;
    }

    public function getServiceId()
    {
        return $this->_id;
    }

    public function setServiceName($name)
    {
        $this->_name = $name;
    }

    public function setServiceNameEn($name_En)
    {
        $this->_name_en = $name_En;
    }

    public function setServiceDescription($description)
    {
        $this->_description = $description;
    }

    public function setServiceDescriptionEn($description_en)
    {
        $this->_description_en = $description_en;
    }

    public function setInstruction($instruction)
    {
        $this->_instruction = $instruction;
    }

    public function setServicePrice($price)
    {
        $this->_saijo_prices = $price;
    }

    public function setServiceMarketPrice($market_price)
    {
        $this->_market_prices = $market_price;
    }

    public function __construct()
    {
        parent::__construct();
    }


    public function service_list()
    {

//       $query = $this->db->get_where($this->tbl_service,array($this->tbl_service.'.`status`'=>1));


        if (!is_blank($this->_id)) {
            $this->db->where('id', $this->_id);
        }

        $this->db->where('status', '1');
        $query = $this->db->get($this->tbl_service);


        $result = array();
        if ($query->num_rows()) {
            foreach ($query->result_array() as $row) {
                $result[] = $row;
            }
        }


        return $result;


    }


} // end of class
