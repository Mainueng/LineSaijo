<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Error_code_model extends MY_Model
{
    private $_error_code_id;

    public function setErrorCodeId($error_code_id)
    {
        $this->_error_code_id = $error_code_id;
    }

    public function get_error_code()
    {

        $data_where = array(
            'code_id' => $this->_error_code_id,
        );

        $this->db->select('*');
        $this->db->where($data_where);
        $this->db->from($this->tbl_error_code);

        $query = $this->db->get();

        if ($query->num_rows() > 0) {


            foreach ($query->result_array() as $row) {
                $rows = array(
                        'title_en' => get_array_value($row, 'title_en', ''),
                        'title_th' => get_array_value($row, 'title_th', ''),
                        'detail_en' => get_array_value($row, 'detail_en', ''),
                        'detail_th' => get_array_value($row, 'detail_th', ''),
                        'url_video' => get_array_value($row, 'url_video', '')
                    );

                /*$rows[0] = array(
                    'header' => "Problem",
                    'body' => array(
                        'title_en' => get_array_value($row, 'title_en', ''),
                        'title_th' => get_array_value($row, 'title_th', ''),
                    ),
                );

                $rows[1] = array(
                    'header' => "solution",
                    'body' => array(
                        'detail_en' => get_array_value($row, 'detail_en', ''),
                        'detail_th' => get_array_value($row, 'detail_th', ''),
                    ),
                );

                $rows[2] = array(
                    'header' => "vdo",
                    'body' => array(
                        'url_video' => get_array_value($row, 'url_video', ''),
                    ),
                );*/

                $result[] = $rows;
            }

            return $result;

        } else {

            return false;
        }
    }

    public function get_error_code_list()
    {

        $this->db->distinct();
        $this->db->select('unit_en');
        $this->db->from($this->tbl_error_code);

        $query = $this->db->get();

        if ($query->num_rows() > 0) {

            foreach ($query->result_array() as $row) {
                $header = get_array_value($row, 'unit_en', '');

                if ($header == 'Fan Coil Unit') {
                    $unit = "Indoor Unit";
                } elseif ($header == 'Condensing Unit') {
                    $unit = "Outdoor Unit";
                }
                else {
                    $unit = "System";
                }

                $rows = array(
                    'header' => $unit,
                    'body' => $this->error_title(get_array_value($row, 'unit_en', ''))
                );

                $result[] = $rows;
            }

            return $result;

        } else {

            return false;
        }
    }

    public function error_title($title)
    {

        $this->db->select('code_id,title_en,title_th');
        $this->db->from($this->tbl_error_code);
        $this->db->where('unit_en',$title);

        $query = $this->db->get();

        if ($query->num_rows() > 0) {

            foreach ($query->result_array() as $row) {
                $rows = array(
                    'code_id' => get_array_value($row, 'code_id', ''),
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

}
