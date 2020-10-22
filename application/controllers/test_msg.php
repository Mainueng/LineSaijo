<?php

class test_msg extends MY_Controller {

	public function index(){

		$url = "https://fcm.googleapis.com/fcm/send";
		$serverKey = 'AAAAH3G-tO4:APA91bHuxDL0IHvH-kafNS6U-BZF66Rauw3GvLNntngP6274FlGLyfn1juo6ElXOw5NMtAwpnmJbYq30cSG-l2jvhTMXarfWOyjbqDcXeqWunDv7os7dce62GQiHzgNV4vD2EXYO5cWB';
		$title = "Saijo Denki E-Service";

		$body = "Test MSG.";


		$token = 'e01pDbCi3nY:APA91bGZkpS5_59nOvvxinHx3XyL5BA3IgbP6G22QiwG3IyYa-oQFZpmy-AjLohp0VL0bWsgrfnGBe1oyo6knYRMh8hERT6JRP6Vobd0n7gp0F0yjT2H-XerwkWLaCE38VEqfHQw3Esg';

		$notification = array('title' =>$title , 'body' => $body, 'sound' => 'default', 'badge' => 1);
		$data = array('job_id' => 1);
		$arrayToSend = array('to' => $token, 'notification' => $notification,'priority'=>'high', 'data' => $data);
		$json = json_encode($arrayToSend);
		$headers = array();
		$headers[] = 'Content-Type: application/json';
		$headers[] = 'Authorization: key='. $serverKey;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST,"POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER,$headers);

		$response = curl_exec($ch);

		curl_close($ch);

		$result = json_decode($response, true);

		print_r($result);

		//$this->load->view('test_msg');
	}

}
?>