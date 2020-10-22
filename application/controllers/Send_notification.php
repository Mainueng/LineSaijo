<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Send_notification extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
    }


    public function index(){



        $this->load->view('gg_push');


    }


    public function send_notifi()
    {


        $device_id="efqBU8ziKI8:APA91bFL3r3FmZEtyr5Gjn6n6Z_oHRqyx5mzAbtxSaO8zmO3JLtTJh4DDlgjP2YSB_XOscCs09kMpRR8zjp3f5Xm0k2idk9OWJd3sdF6ehbnHN3S01ebuGvWgOr1vLWI0IDZ56eYewdA";
        $payload = array(
            'to'=>$device_id,
            'priority'=>'high',
            "mutable_content"=>true,
            "notification"=>array(
                "title"=> "MyApp",
                "body"=> "Test"
            ),
            'data'=>array(
                'action'=>'models',
                'model_id'=>'2701',
            )
        );
        $headers = array(
            'Authorization:key=AIzaSyCpP23DGGkE70Y-ILxLcOaMCfNEK3Y6K8M',
      'Content-Type: application/json'
    );
    $ch = curl_init();
//    curl_setopt( $ch,CURLOPT_URL, 'https://android.googleapis.com/gcm/send' );
    curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
    curl_setopt( $ch,CURLOPT_POST, true );
    curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
    curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
    curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
    curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $payload ) );
    $result = curl_exec($ch );
    curl_close( $ch );
    var_dump($result);exit;



    }


    public function sendPushNotification($to = '', $data = array())
    {

        $apiKey = 'AIzaSyCpP23DGGkE70Y-ILxLcOaMCfNEK3Y6K8M';
//        $apiKey = 'AAAADMb83aI:APA91bFE2w2H4o8tfmZc5Dk23XJzdu4pRAHNjrLpFwQJaCnrnZzKbOtCs9OwfO65c7V3UBVaH_6-VLpKkOvExgrX4oZF4i_ImkVlpDMNaJcA98pkapLFSYcr7LTGJK-N7mAx8ArSANy_';
        $fields = array('to' => $to, 'notification' => $data);

        $headers = array('Authorization: key=' . $apiKey, 'Content-Type : application/json');
        $url = 'https://fcm.googleapis.com/fcm/send';

        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_POST,true);
        curl_setopt($ch,CURLOPT_HEADER,$headers);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
        curl_setopt($ch,CURLOPT_POSTFIELDS,json_encode($fields));
        $result = curl_exec($ch);
        curl_close($ch);

        return json_encode($result,true);


    }



    public function pushNotification(){
        $device_id="efqBU8ziKI8:APA91bFL3r3FmZEtyr5Gjn6n6Z_oHRqyx5mzAbtxSaO8zmO3JLtTJh4DDlgjP2YSB_XOscCs09kMpRR8zjp3f5Xm0k2idk9OWJd3sdF6ehbnHN3S01ebuGvWgOr1vLWI0IDZ56eYewdA";
        $data = array(
            'body'=>'Test push msg form API'
        );


        print_r($this->sendPushNotification($device_id,$data));

    }




    public function sendPush3(){
        // API access key from Google API's Console
        define( 'API_ACCESS_KEY', 'YOUR-API-ACCESS-KEY-GOES-HERE' );


        $registrationIds = array( $_GET['id'] );

// prep the bundle
        $msg = array
        (
            'message' 	=> 'here is a message. message',
            'title'		=> 'This is a title. title',
            'subtitle'	=> 'This is a subtitle. subtitle',
            'tickerText'	=> 'Ticker text here...Ticker text here...Ticker text here',
            'vibrate'	=> 1,
            'sound'		=> 1,
            'largeIcon'	=> 'large_icon',
            'smallIcon'	=> 'small_icon'
        );

        $fields = array
        (
            'registration_ids' 	=> $registrationIds,
            'data'			=> $msg
        );

        $headers = array
        (
            'Authorization: key=' . API_ACCESS_KEY,
            'Content-Type: application/json'
        );

        $ch = curl_init();
        curl_setopt( $ch,CURLOPT_URL, 'https://android.googleapis.com/gcm/send' );
        curl_setopt( $ch,CURLOPT_POST, true );
        curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
        curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
        $result = curl_exec($ch );
        curl_close( $ch );

        echo $result;
    }

}
