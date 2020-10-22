<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Api_dashboard_model extends MY_Model
{

    private $_service_id;
    private $_service_type;
    private $_service_name_en;
    private $_service_name_th;
    private $_btu;
    private $_cost;
    private $_unit;
    private $_status;
    private $_service_cost_id;
    private $_service_code;
    private $_problem_id;
    private $_problem_name_en;
    private $_problem_name_th;
    
    public function setServiceID($service_id)
    {
        $this->_service_id = $service_id;
    }

    public function setServiceType($service_type)
    {
        $this->_service_type = $service_type;
    }

    public function setServiceNameEN($service_name_en)
    {
        $this->_service_name_en = $service_name_en;
    }

    public function setServiceNameTH($service_name_th)
    {
        $this->_service_name_th = $service_name_th;
    }

    public function setBTU($btu)
    {
        $this->_btu = $btu;
    }

    public function setCost($cost)
    {
        $this->_cost = $cost;
    }

    public function setUnit($unit)
    {
        $this->_unit = $unit;
    }

    public function setStatus($status)
    {
        $this->_status = $status;
    }

    public function setServiceCode($service_code)
    {
        $this->_service_code = $service_code;
    }

    public function setServiceCostID($service_cost_id)
    {
        $this->_service_cost_id = $service_cost_id;
    }

    public function setProblemID($problem_id)
    {
        $this->_problem_id = $problem_id;
    }

    public function setProblemNameEN($problem_name_en)
    {
        $this->_problem_name_en = $problem_name_en;
    }

    public function setProblemNameTH($problem_name_th)
    {
        $this->_problem_name_th = $problem_name_th;
    }

    public function getServiceCost($data = array())
    {

        $this->db->select('service_cost.id as id,name_en,service_name_en,service_name_th,btu,cost,unit,service_cost.status as status');
        $this->db->from($this->tbl_service_cost);
        $this->db->join($this->tbl_service, 'service_cost.service_type = service.id', 'left');

        if (isset($data['filter_service_id']) && $data['filter_service_id'] !== '') {
            $this->db->where(array(
                'service_cost.id' => $data['filter_service_id']
            ));
        }

        if (isset($data['filter_service_type']) && $data['filter_service_type'] !== '') {
            $this->db->where(array(
                'service_type' => $data['filter_service_type']
            ));
        }

        if (isset($data['filter_service_name']) && $data['filter_service_name'] !== '') {
            $this->db->where(array(
                'service_name_th' => $data['filter_service_name']
            ));
        }

        if (isset($data['filter_cost']) && $data['filter_cost'] !== '') {
            $this->db->where(array(
                'cost' => $data['filter_cost']
            ));
        }

        if (isset($data['filter_status']) && $data['filter_status'] !== '') {
            $this->db->where(array(
                'service_cost.status' => $data['filter_status']
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
        
        $this->db->order_by('service_cost.id', 'ASC');

        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $rows = array(
                    'id' => get_array_value($row, 'id', ''),
                    'service_type' => get_array_value($row, 'name_en', ''),
                    'service_name_en' => get_array_value($row, 'service_name_en', '-'),
                    'service_name_th' => get_array_value($row, 'service_name_th', ''),
                    'btu' => get_array_value($row, 'btu', ''),
                    'cost' => get_array_value($row, 'cost', ''),
                    'unit' => get_array_value($row, 'unit', ''),
                    'status' => get_array_value($row, 'status', '')
                );

                $result[] = $rows;
            }

            return $result;

        } else {
            return false;
        }

    }

    public function getServiceCostTotal($data = array())
    {

        $this->db->select('count(service_cost.id) as total');
        $this->db->from($this->tbl_service_cost);
        $this->db->join($this->tbl_service, 'service_cost.service_type = service.id', 'left');

        if (isset($data['filter_service_id']) && $data['filter_service_id'] !== '') {
            $this->db->where(array(
                'service_cost.id' => $data['filter_service_id']
            ));
        }

        if (isset($data['filter_service_type']) && $data['filter_service_type'] !== '') {
            $this->db->where(array(
                'service_type' => $data['filter_service_type']
            ));
        }

        if (isset($data['filter_service_name']) && $data['filter_service_name'] !== '') {
            $this->db->where(array(
                'service_name_th' => $data['filter_service_name']
            ));
        }

        if (isset($data['filter_cost']) && $data['filter_cost'] !== '') {
            $this->db->where(array(
                'cost' => $data['filter_cost']
            ));
        }

        if (isset($data['filter_status']) && $data['filter_status'] !== '') {
            $this->db->where(array(
                'service_cost.status' => $data['filter_status']
            ));
        }
        
        $this->db->order_by('service_cost.id', 'ASC');

        $query = $this->db->get();

        if ($query->num_rows() > 0) {

            $result = $query->row();

            return $result->total;

        } else {
            return 0;
        }

    }

    public function getServiceName()
    {

        $this->db->select('service_name_th,service_name_en,id');
        $this->db->from($this->tbl_service_cost);

        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $rows = array(
                    'service_name_th' => get_array_value($row, 'service_name_th', ''),
                    'service_name_en' => get_array_value($row, 'service_name_en', ''),
                    'id' => get_array_value($row, 'id', '')
                );

                $result[] = $rows;
            }

            return $result;

        } else {
            return false;
        }

    }

    public function getServiceCostInfo()
    {
        $this->db->select('*');
        $this->db->from($this->tbl_service_cost);
        $this->db->where(array('id' => $this->_service_cost_id));

        $query = $this->db->get();

        if ($query->num_rows() > 0) {

            $result = $query->row();

            $data['service_type'] = $result->service_type;
            $data['service_name_en'] = $result->service_name_en;
            $data['service_name_th'] = $result->service_name_th;
            $data['btu'] = $result->btu;
            $data['cost'] = $result->cost;
            $data['unit'] = $result->unit;
            $data['status'] = $result->status;

            return $data;

        } else {
            return false;
        }
    }

    public function updateServiceCost()
    {

        $data = array(
            'service_type' => $this->_service_type,
            'service_name_en' => $this->_service_name_en,
            'service_name_th' => $this->_service_name_th,
            'btu' => $this->_btu,
            'cost' => $this->_cost,
            'unit' => $this->_unit,
            'status' => $this->_status
        );

        $this->db->where(array('id' => $this->_service_cost_id));
        $msg = $this->db->update($this->tbl_service_cost, $data);

        if ($msg == 1) {
            return true;
        } else {
            return false;
        }
    }

    public function addServiceCost()
    {

        $data = array(
            'service_type' => $this->_service_type,
            'service_name_en' => $this->_service_name_en,
            'service_name_th' => $this->_service_name_th,
            'btu' => $this->_btu,
            'cost' => $this->_cost,
            'unit' => $this->_unit,
            'status' => $this->_status
        );

        $this->db->insert($this->tbl_service_cost, $data);

        if (!empty($this->db->insert_id()) && $this->db->insert_id() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function deleteServiceCost()
    {

        $this->db->where(array('id' => $this->_service_cost_id));
        $msg = $this->db->delete($this->tbl_service_cost);

        if ($msg == 1) {
            return true;
        } else {
            return false;
        }
    }

    public function getServiceType($data = array())
    {

        $this->db->select('*');
        $this->db->from($this->tbl_job_type);

        if (isset($data['filter_service_code']) && $data['filter_service_code'] !== '') {
            $this->db->where(array(
                'code' => $data['filter_service_code']
            ));
        }

        if (isset($data['filter_service_name_en']) && $data['filter_service_name_en'] !== '') {
            $this->db->where(array(
                'name_en' => $data['filter_service_name_en']
            ));
        }

        if (isset($data['filter_service_name_th']) && $data['filter_service_name_th'] !== '') {
            $this->db->where(array(
                'name_th' => $data['filter_service_name_th']
            ));
        }

        if (isset($data['filter_status']) && $data['filter_status'] !== '') {
            $this->db->where(array(
                'status' => $data['filter_status']
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
        
        $this->db->order_by('id', 'ASC');

        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $rows = array(
                    'code' => get_array_value($row, 'code', ''),
                    'name_en' => get_array_value($row, 'name_en', '-'),
                    'name_th' => get_array_value($row, 'name_th', '-'),
                    'status' => get_array_value($row, 'status', '')
                );

                $result[] = $rows;
            }

            return $result;

        } else {
            return false;
        }

    }

    public function getServiceTypeTotal($data = array())
    {
        $this->db->select('count(code) as total');
        $this->db->from($this->tbl_job_type);

        if (isset($data['filter_service_code']) && $data['filter_service_code'] !== '') {
            $this->db->where(array(
                'code' => $data['filter_service_code']
            ));
        }

        if (isset($data['filter_service_name_en']) && $data['filter_service_name_en'] !== '') {
            $this->db->where(array(
                'name_en' => $data['filter_service_name_en']
            ));
        }

        if (isset($data['filter_service_name_th']) && $data['filter_service_name_th'] !== '') {
            $this->db->where(array(
                'name_th' => $data['filter_service_name_th']
            ));
        }

        if (isset($data['filter_status']) && $data['filter_status'] !== '') {
            $this->db->where(array(
                'status' => $data['filter_status']
            ));
        }

        $query = $this->db->get();

        if ($query->num_rows() > 0) {

            $result = $query->row();

            return $result->total;

        } else {
            return 0;
        }
    }

    public function updateServiceInfo()
    {

        $data = array(
            'code' => $this->_service_code,
            'name_en' => $this->_service_name_en,
            'name_th' => $this->_service_name_th,
            'status' => $this->_status
        );

        $this->db->where(array('code' => $this->_service_code));
        $this->db->update($this->tbl_job_type, $data);

        return true;
    }

    public function addServiceInfo()
    {

        $data = array(
            'code' => $this->_service_code,
            'name_en' => $this->_service_name_en,
            'name_th' => $this->_service_name_th,
            'status' => $this->_status
        );

        $this->db->insert($this->tbl_job_type, $data);

        return true;
    }

    public function deleteServiceInfo()
    {
        $this->db->where(array('code' => $this->_service_code));
        $this->db->delete($this->tbl_job_type);

        return true;
    }

    public function getServiceInfo()
    {
        $this->db->select('*');
        $this->db->from($this->tbl_job_type);
        $this->db->where(array('code' => $this->_service_code));

        $query = $this->db->get();

        if ($query->num_rows() > 0) {

            $result = $query->row();

            $data['name_en'] = $result->name_en;
            $data['name_th'] = $result->name_th;
            $data['status'] = $result->status;

            return $data;

        } else {
            return false;
        }
    }

    public function getLastServiceCode()
    {

        $this->db->select('code');
        $this->db->from($this->tbl_job_type);
        $this->db->limit(1);
        $this->db->order_by('code', 'DESC');

        $query = $this->db->get();

        if ($query->num_rows() > 0) {

            $result = $query->row();

            return (int)$result->code+1;

        } else {
            return false;
        }
    }

    public function getProblems($data = array())
    {

        $this->db->select('*');
        $this->db->from($this->tbl_symptoms);

        if (isset($data['filter_problem_name_en']) && $data['filter_problem_name_en'] !== '') {
            $this->db->where(array(
                'name_en' => $data['filter_problem_name_en']
            ));
        }

        if (isset($data['filter_problem_name_th']) && $data['filter_problem_name_th'] !== '') {
            $this->db->where(array(
                'name_th' => $data['filter_problem_name_th']
            ));
        }

        if (isset($data['filter_status']) && $data['filter_status'] !== '') {
            $this->db->where(array(
                'status' => $data['filter_status']
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
        
        $this->db->order_by('id', 'ASC');

        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $rows = array(
                    'id' => get_array_value($row, 'id', '-'),
                    'name_en' => get_array_value($row, 'name_en', '-'),
                    'name_th' => get_array_value($row, 'name_th', '-'),
                    'status' => get_array_value($row, 'status', '')
                );

                $result[] = $rows;
            }

            return $result;

        } else {
            return false;
        }

    }

    public function getProblemsTotal($data = array())
    {
        $this->db->select('count(id) as total');
        $this->db->from($this->tbl_symptoms);

        if (isset($data['filter_problems_name_en']) && $data['filter_problems_name_en'] !== '') {
            $this->db->where(array(
                'name_en' => $data['filter_problems_name_en']
            ));
        }

        if (isset($data['filter_problems_name_th']) && $data['filter_problems_name_th'] !== '') {
            $this->db->where(array(
                'name_th' => $data['filter_problems_name_th']
            ));
        }

        if (isset($data['filter_status']) && $data['filter_status'] !== '') {
            $this->db->where(array(
                'status' => $data['filter_status']
            ));
        }
        
        $this->db->order_by('id', 'ASC');

        $query = $this->db->get();

        if ($query->num_rows() > 0) {

            $result = $query->row();

            return $result->total;

        } else {
            return 0;
        }
    }

    public function updateProblems()
    {

        $data = array(
            'name_en' => $this->_problem_name_en,
            'name_th' => $this->_problem_name_th,
            'status' => $this->_status
        );

        $this->db->where(array('id' => $this->_problem_id));
        $this->db->update($this->tbl_symptoms, $data);

        return true;
    }

    public function addProblems()
    {

        $data = array(
            'name_en' => $this->_problem_name_en,
            'name_th' => $this->_problem_name_th,
            'status' => $this->_status
        );

        $this->db->insert($this->tbl_symptoms, $data);

        return true;
    }

    public function deleteProblems()
    {
        $this->db->where(array('id' => $this->_problem_id));
        $this->db->delete($this->tbl_symptoms);

        return true;
    }

    public function getProblemsInfo()
    {
        $this->db->select('*');
        $this->db->from($this->tbl_symptoms);
        $this->db->where(array('id' => $this->_problem_id));

        $query = $this->db->get();

        if ($query->num_rows() > 0) {

            $result = $query->row();

            $data['name_en'] = $result->name_en;
            $data['name_th'] = $result->name_th;
            $data['status'] = $result->status;

            return $data;

        } else {
            return false;
        }
    }

} //End of Class


?>
