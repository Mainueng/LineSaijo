<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
    }

    public function index()
    {

        $device = new Mobile_Detect();

        $userAgents = array(
            'Mozilla/5.0 (Linux; Android 4.0.4; Desire HD Build/IMM76D) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.166 Mobile Safari/535.19',
            'BlackBerry7100i/4.1.0 Profile/MIDP-2.0 Configuration/CLDC-1.1 VendorID/103',
// [...]
        );
        foreach ($userAgents as $userAgent) {

            $device->setUserAgent($userAgent);
            $isMobile = $device->isMobile();
            $isTablet = $device->isTablet();
            $br = $device->is('Chrome');
            // Use the force however you want.

        }

        echo $isMobile;
        echo $br;


//        echo $device->isMobile();
//        echo $device->isTablet();
//        echo $device->isBot();
//        echo $device->isConsole();

    }

    public function login()
    {
            $this->load->view('login');


    }


    public function pushmsg(){
        $this->load->view('push_message');
    }

    public function product_warranty_info()
    {
        $this->load->model($this->warranty_model, 'warranty');
        $user_id = 164;
        $ac_no = "1801C00689000";
        $this->warranty->setUserId($user_id);
        $product_item = $this->warranty->product_warranty_by_user();
        $result = $this->warranty->product_warranty_by_serial_no($ac_no);


        echo "<pre>";
        print_r($product_item);
//        print_r($result);
//        echo ENVIRONMENT;

//        echo $this->db->last_query();
        echo "</pre>";

        echo "</pre>";


    }

    public function product_info()
    {
        $this->load->model($this->product_model, 'product');
        $serial = "1801C00689000";

        $this->product->setSerial($serial);
        $result = $this->product->product_info();


//       echo json_encode($result);

//        return  json_encode($result);
        return $result;


    }

    public function product_active()
    {
        $this->load->model($this->product_model, 'product');

        $serial = "1801C00689000";

        $this->product->setSerial($serial);
        $m = $this->product->product_activate();

        if ($m) {
//            echo "Update";
            $result = $this->product_info();
            echo "<pre>";
            print_r($result);
            echo "</pre>";

            foreach ($result as $key => $val) {
                $warranty_info[$key] = $val;
            }

            $warranty = $warranty_info['warranty_info'];

            foreach ($warranty as $row) {
                $rows[] = array(
                    'serial' => $warranty_info['serial'],
                    'warranty_id'=>get_array_value($row, 'warranty_id', ''),
                    'default_warranty' => get_array_value($row, 'default_warranty', ''),
                    'e_warranty' => get_array_value($row, 'e_warranty', ''),
                    'day' => get_array_value($row, 'all_warranty_days', ''),
                    'cus_mstr_id' => 1516
                );
            }


            echo "<pre>";
            print_r($warranty);
            print_r($rows);
            echo "</pre>";

            $this->load->model($this->warranty_model,'warranty');
            $result = $this->warranty->add_warranty_product($rows);


            var_dump($result);




        } else {
            echo "No Update";
        }


    }


    public function validate_product()
    {
        $serial = "8888F00000008";

        $this->load->model($this->product_model, 'product');

        $this->product->setSerial($serial);
        $result = $this->product->product_valid_warranty();

        if ($result) {
            echo "Is True".$this->product->getActiveStatus();
        } else {
            echo "Is False";
        }


    }

    public function push_msg_club_app(){

    }
    public function push_msg_eservice_app(){
//            $api_key = "AIzaSyAEJVlCoGWy2ReXayC-9RH8Eea9pMeAaDg";
            $api_key = "AIzaSyBuci4FSikEBqcF_GFNHFpPtyoHokVMpjk";

            $to = "eIJ6Z8Pv8pA:APA91bFx9FVBntuvIA77s_kgHZ5i2kmUEsYvFq4lp5Gzojcd2pfs80ljMEfj2Oq58atg5UUvgsc2-rwEZU_1a47hoUsKvkbLWTQzFdXe5MMQBfo6vmtJ9AP96fcCdKoCwQz2XGzF2r0h";


//            $data = array(
//                'body'=>"Msg from Web Service"
//            );


        $data = array(
            'title'=>"You have jobs",
            'text'=>"You have clean service 1 jobs detail other data",
            'sound'=>'default',
            'badge'=>1
        );






            $header = array('Authorization: key='.$api_key,'Content-Type: application/json');
            $fields = array(
                'to'=>$to,'notification'=>$data
            );
            $end_point = 'https://fcm.googleapis.com/fcm/send';

            $ch = curl_init();
            curl_setopt($ch,CURLOPT_URL,$end_point);
            curl_setopt($ch,CURLOPT_POST,true);
            curl_setopt($ch,CURLOPT_HTTPHEADER,$header);
            curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);

            curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
            curl_setopt($ch,CURLOPT_POSTFIELDS,json_encode($fields));

            $result = curl_exec($ch);
            curl_close($ch);

            return json_decode($result,true);

    }


    public function push_msg_helper(){


//        echo "JJO";
//        exit(0);
        $notification = array(
            'title'=>"You have jobs",
            'text'=>"You have clean service 1 jobs. Please Accept if you want this jobs",
            'sound'=>'default',
            'badge'=>1
        );
        $data = array(
            'title'=>"Mr. Saijo",
            'text'=>"You have clean service 1 jobs. Please Accept if you want this jobs",
            'sound'=>'default',
            'badge'=>1
        );

//        $multi_token[]['token'] = 'd6VUIUZweSM:APA91bECG3AVkza6K3JA89hyaC7-ixO8eF1tNzQooCNx01KdVEWHd4q7D2gToATtuoA4FoNcMvRM90rsZTwiImP-r2peTAWWat2v-SmW15Cb-Xpjblb0-fUG2YbqrlDf5i8lch6YVPHj';
//        $multi_token[]['token'] = 'eIJ6Z8Pv8pA:APA91bFx9FVBntuvIA77s_kgHZ5i2kmUEsYvFq4lp5Gzojcd2pfs80ljMEfj2Oq58atg5UUvgsc2-rwEZU_1a47hoUsKvkbLWTQzFdXe5MMQBfo6vmtJ9AP96fcCdKoCwQz2XGzF2r0h';
        $multi_token[]['token'] = 'dDuue5tUi3k:APA91bFadXXR6zNxZTUS-Ai5B2VPNZVur5vmUYSWQDkVdfBZv6q1bYaGRWF26o0MQLZZx7eU4NBlru4IEKJDkJpLeVLtVZUcqixtAqeoBqROxNn_R9ew8DtMRB3AqejHqYTI3LI_Ew0q';

        foreach ($multi_token as $row){
            $token =  $row['token'];
//                    send_noti($token,$notification,$data);
                    print_r(send_noti_technician($token,$notification,$data)) ;
        }


    }

    public function technician_online(){
        $this->load->model($this->technician_model,'technician');

        $tecnician_online = $this->technician->technician_online();

        $notification = array(
            'title'=>"You have a jobs",
            'text'=>"You have clean service 1 jobs. Please Accept if you want this jobs",
            'sound'=>'default',
            'badge'=>1
        );
        $data = array(
            'title'=>"Mr. Saijo",
            'text'=>"You have clean service 1 jobs. Please accept if you want this a job",
            'sound'=>'default',
            'badge'=>1
        );

        foreach ($tecnician_online as $row){
            $token = get_array_value($row,'device_token');

            send_noti_technician($token,$notification,$data);
        }


    }
    public function consumer_online(){
        $notification = array(
            'title'=>"You have a jobs",
            'text'=>"You have clean service 1 jobs. Please Accept if you want this jobs",
            'sound'=>'default',
            'badge'=>1
        );
        $data = array(
            'title'=>"Mr. SaiJO Denki Core App",
            'body'=>"You have clean service 1 jobs. Please accept if you want this a job",
            'sound'=>'default',
            'badge'=>1
        );
//        $token = "dYBeGM6F2PI:APA91bEeOsDUu8WLzU1bxjQeLVnikz3A7zeGAaf0dd8kTpME-2H8ZgAtG7g2Ni3jq2VuqJQHOwlTvZS4p8icxwzN0eKKe2fQSOq8SE8bvdPVyD8SpyJl-MxqiuxMRjV6L4h_IrAWdWQd";
        $token = "eOaJ3wS_pes:APA91bHwkai726ZdyD7qsY9Vdx716VuTwhdpqr6rN2th9-KrDaDtKUnkOa_u4e6Ycgkxjj_OpFZblveb1mmDccQGQi1fwKh2fvtVppgMNVOMqTwJn32y_8AQ9bdpYf4dzFy8sxqH35rr";



        print_r(core_send_notification($token,$data));


    }


    public function test_position(){
       $this->load->library('POI');

       $latitude = 13.6784847;
       $longitude = 100.9146399;

        $user = new POI($latitude, $longitude);
        $poi = new POI(13.674409,100.915436);
        echo $user->getDistanceInMetersTo($poi);





    }


    public function technician_nearse(){
            $this->load->model($this->technician_model,'technician');
            $jobId = 12;
            $distance = 10;

            $this->technician->setJobId($jobId);
            $this->technician->setDistance($distance);
            $res = $this->technician->find_technician_nearest();


//            var_dump($res);
    }

    public function jobs_history(){

    }

    public function facebook_login(){

    }






}
