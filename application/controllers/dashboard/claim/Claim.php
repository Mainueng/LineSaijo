<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Claim extends MY_Controller {

	function __construct()
	{
		parent::__construct();

        $this->load->model('Warranty_model', 'warranty');
        $this->load->model('Jobs_model', 'jobs');
        $this->load->model('User_model', 'user');
    }

    public function index()
    {

        if ($this->session->userdata('token')) {
            $this->load->view('tpl_claim');
        } else {
           redirect('line_login');
       }
   }

   public function check()
   {

    $serial_number = strtoupper($this->input->post('serial_number'));

    $this->warranty->setSerial($serial_number);

    $product_warranty_info = $this->warranty->product_warranty_info_line();

    $str = substr($serial_number, 4, 1);

    if ($str == 'F' && $str == 'A') {

        if($product_warranty_info) {

            $origDate = $product_warranty_info['warranty_info'][1]['e_warranty'];

            $date = str_replace('/', '-', $origDate );
            $exp_date = date("Y-m-d", strtotime($date));

            echo $exp_date;
        } else {
            echo 'not_found';
        }
    } else {
        if($product_warranty_info) {

            $origDate = $product_warranty_info['warranty_info'][0]['e_warranty'];

            $date = str_replace('/', '-', $origDate );
            $exp_date = date("Y-m-d", strtotime($date));

            echo $exp_date;
        } else {
            echo 'not_found';
        }
    }

}

public function add_form()
{

    if ($this->input->post()) {
        $this->data['firstname'] = $this->input->post('firstname');
        $this->data['lastname'] = $this->input->post('lastname');
        $this->data['phone_number'] = $this->input->post('phone_number');
        $this->data['serial_number_indoor'] = $this->input->post('serial_number_indoor');
        $this->data['serial_number_outdoor'] = $this->input->post('serial_number_outdoor');
        $this->data['problem'] = $this->input->post('problem');
        $this->data['type'] = $this->input->post('type');
        $this->data['line_id'] = $this->session->userdata('userId');
        $this->data['warranty'] = $this->input->post('warranty');
        $this->data['status'] = 'Pending';
        $this->data['date'] = $this->input->post('date');
        $this->data['update_date'] = $this->input->post('date');
        $this->data['job_type'] = $this->input->post('job_type');
        $this->data['address'] = $this->input->post('address');
        $this->data['part_number'] = $this->input->post('part_number');

        $profile_img = '';
        
        $id = $this->jobs->addClaim($this->data);

            /*if (!is_blank($_FILES['file-1']['name'])) {
                $profile_img_1 = $this->upload_img($id.'_1','file-1');
            }

            if (!is_blank($_FILES['file-2']['name'])) {
                $profile_img_2 = $this->upload_img($id.'_2','file-2');
            }

            if (!is_blank($_FILES['file-3']['name'])) {
                $profile_img_3 = $this->upload_img($id.'_3','file-3');
            }

            if (!is_blank($profile_img_1)) {
                $profile_img = $profile_img_1;
            }

            if (!is_blank($profile_img_2)) {
                $profile_img = $profile_img.','.$profile_img_2;
            }

            if (!is_blank($profile_img_3)) {
                $profile_img = $profile_img.','.$profile_img_3;
            }*/

            if (!is_blank($this->input->post('hidden-img-1'))) {
                $img = str_replace('data:image/png;base64,', '', $this->input->post('hidden-img-1'));
                $img = str_replace(' ', '+', $img);
                $data = base64_decode($img);
                file_put_contents('application/controllers/dashboard/claim/upload/'.$id.'_1.png', $data);

                $profile_img = $profile_img.$id.'_1.png';
            }

            if (!is_blank($this->input->post('hidden-img-2'))) {
                $img = str_replace('data:image/png;base64,', '', $this->input->post('hidden-img-2'));
                $img = str_replace(' ', '+', $img);
                $data = base64_decode($img);
                file_put_contents('application/controllers/dashboard/claim/upload/'.$id.'_2.png', $data);

                $profile_img = $profile_img.','.$id.'_2.png';
            }

            if (!is_blank($profile_img)) {
                $this->jobs->addClaimImg($id,$profile_img);
            }

            $tokens = $this->user->getOperatorToken();

            if ($tokens) {

                $message = "Claim ID ".$id." - ลูกค้าแจ้ง".$this->data['job_type']."!";
                $url = "job-claim/form/".$id."?&method=edit";

                foreach ($tokens as $row) {

                    $this->my_libraies->send_push_noti($row['token'],$message,$url);

                }
            }


            header( "location: ".site_url('claim/summary'));
        }
    }

    public function summary()
    {
        $this->load->view('tpl_claim_summary');
    }

    public function upload_img($id,$img) {

        $images = $_FILES[$img]['tmp_name'];
        $check_type = $_FILES[$img]['name'];
        $check_type_explode = explode('.', $check_type);

        $size = GetimageSize($images);

        if ($size[0] > 720) {
            $width = 720;
        } else {
            $width = $size[0];
        }

        $height = round($width*$size[1]/$size[0]);

        if ($check_type_explode[1] == 'PNG' || $check_type_explode[1] == 'png') {
            $images_orig = ImageCreateFromPNG($images);
        }
        else if ($check_type_explode[1] == 'GIF' || $check_type_explode[1] == 'gif') {
            $images_orig = ImageCreateFromGIF($images);
        }
        else{
            $images_orig = ImageCreateFromJPEG($images);
        }

        $photoX = ImagesX($images_orig);
        $photoY = ImagesY($images_orig);
        $images_fin = ImageCreateTrueColor($width, $height);
        ImageCopyResampled($images_fin, $images_orig, 0, 0, 0, 0, $width, $height, $photoX, $photoY);

        //imagepng($images_fin, "application/controllers/dashboard/claim/upload/".$id.".png");
        imagepng($images_orig, "application/controllers/dashboard/claim/upload/".$id.".png");

        ImageDestroy($images_orig);
        //ImageDestroy($images_fin);

        $name = $id.".png";

        return $name;
    }
}
