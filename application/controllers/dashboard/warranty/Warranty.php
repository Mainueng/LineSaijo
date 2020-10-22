<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Warranty extends MY_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('Warranty_model', 'warranty');
	}

	public function index()
	{
		$this->load->view('tpl_warranty');
	}

	public function warranty_info()
	{

		$serial_number = $this->input->post('serial_number');

		$this->warranty->setSerial($serial_number);

		$product_warranty_info = $this->warranty->product_warranty_info();
		$warranty_match = $this->warranty->get_warranty_match();

		$this->data['product_model'] = $product_warranty_info['product_model'];
		$this->data['active_date'] = $product_warranty_info['active_date'];

		if($product_warranty_info) {

			$str = substr($serial_number, 4, 1);

			if ($str == 'F' || $str == 'C') {
				$this->data['warranty_compressor'] = $product_warranty_info['warranty_info'][0]['e_warranty'];
				$this->data['warranty_part'] = $product_warranty_info['warranty_info'][1]['e_warranty'];
				$this->data['indoor'] = $warranty_match['indoor_serial'];
				$this->data['outdoor'] = $warranty_match['outdoor_serial'];

				$this->data['type'] = 'air_con';
			} else {
				$this->data['warranty_compressor'] = $product_warranty_info['warranty_info'][0]['e_warranty'];
				$this->data['serial'] = $product_warranty_info['serial'];

				$this->data['type'] = 'air_puri';
			}

			$this->load->view('tpl_warranty_info',$this->data);
		} else {
			echo '<div class="d-flex w-100">
				<div class="col-sm-6 col-12" style="padding-left:7.5px;">
					<div class="serial_not_found pt-2">หมายเลขเครื่องไม่ถูกต้อง</div>
				</div>
			</div>';
		}

	}

}
