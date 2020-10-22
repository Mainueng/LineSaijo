<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Manual_model extends MY_Model
{

    private $_id;

    public function setManualId($id)
    {
        $this->_id = $id;
    }

    public function __construct()
    {
        parent::__construct();
    }


    public function get_manual_list()
    {

        $this->db->select('*');
        $this->db->from($this->tbl_manual_title);

        $query = $this->db->get();

        if ($query->num_rows() > 0) {


            foreach ($query->result_array() as $row) {
                $rows = array(
                    'id' => get_array_value($row, 'id', ''),
                    'title_en' => get_array_value($row, 'title_en', ''),
                    'title_th' => get_array_value($row, 'title_th', ''),
                );

                $result[] = $rows;
            }

            return $result;

        } else {

            return false;
        }

    }

    public function get_manual_info()
    {

        $data_where = array(
            "title_id" => $this->_id
        );

        $this->db->select('*');
        $this->db->from($this->tbl_manual_information);
        $this->db->where($data_where);

        $query = $this->db->get();

        if ($query->num_rows() > 0) {


            foreach ($query->result_array() as $row) {
                $rows = array(
                    'step' => get_array_value($row, 'step', ''),
                    'description_en' => get_array_value($row, 'description_en', ''),
                    'description_th' => get_array_value($row, 'description_th', ''),
                    'image' => get_array_value($row, 'image', ''),
                );

                $result[] = $rows;
            }

            return $result;

        } else {

            return false;
        }

    }


} // end of class
