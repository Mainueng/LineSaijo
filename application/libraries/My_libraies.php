<?php 

defined('BASEPATH') OR exit('No direct script access allowed');


class My_libraies
{

    public function FCM($token,$job_id,$cancel=false,$count)
    {
        $url = "https://fcm.googleapis.com/fcm/send";
        $serverKey = 'AAAAzR3XYHg:APA91bFBshxZuOP2hX2bbM7Ar7V-CHfJepU6dv_q4_iyDm0E6f_04kOTVhsMqnfdSYB7A58RLanYaFR8NcZ6K4ryCMbb-IBZhimvbMDG0TfopgW4GY_drQBYRUcgzg0P6W28HiYNhaBs';
        $title = "Saijo Denki E-Service";

        if ($cancel) {
            $body = "ลูกค้ายกเลิกงาน. Job ID : " .$job_id;
        } else {
            $body = "ลูกค้าส่งคำขอรับบริการมาถึงคุณ.";
        }

        $notification = array('title' =>$title , 'body' => $body, 'sound' => 'default', 'badge' => $count);
        $data = array('job_id' => $job_id);
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

        if ($response === FALSE) {
            return false;
        }

        curl_close($ch);

        $result = json_decode($response, true);

        return $result['success'];


    }

    public function radius($lat1,$lon1,$lat2,$lon2)
    {

        if ($lat2 and $lon2) {
            $lat1 = deg2rad($lat1);
            $lon1 = deg2rad($lon1);
            $lat2 = deg2rad($lat2);
            $lon2 = deg2rad($lon2);
        } else {
            $lat1 = deg2rad($lat1);
            $lon1 = deg2rad($lon1);
            $lat2 = deg2rad(0);
            $lon2 = deg2rad(0);
        }

        $radius =  acos(sin($lat1) * sin($lat2) + cos($lat1) * cos($lat2) * cos($lon1 - $lon2)) * 6371;

        return ceil($radius * 10) / 10; 
    }

    public function send_push_noti($token,$message,$link)
    {
        $url = "https://fcm.googleapis.com/fcm/send";
        $serverKey = 'AAAAzR3XYHg:APA91bFBshxZuOP2hX2bbM7Ar7V-CHfJepU6dv_q4_iyDm0E6f_04kOTVhsMqnfdSYB7A58RLanYaFR8NcZ6K4ryCMbb-IBZhimvbMDG0TfopgW4GY_drQBYRUcgzg0P6W28HiYNhaBs';
        $title = "Saijo Denki E-Service";

        $notification = array('title' => $title , 'body' => $message , 'icon' => 'push_noti_logo.png', 'click_action' => $link);

        $arrayToSend = array('notification' => $notification,'to' => $token);
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

        if ($response === FALSE) {
            return false;
        }

        curl_close($ch);

        $result = json_decode($response, true);

        return $result['success'];
    }

    public function line_push_noti($line_id,$job_type,$technician,$officer,$note,$status) {
        $url = "https://api.line.me/v2/bot/message/push";
        $headers = array();
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Authorization: Bearer d7R1Ezxys5TlR85hRUpV2o/GBOIXlUD2KUtdHS6cSsUKJivDDy/EK1ghjzdH3HBaw5WUjpHSVh6nLEC/p1afJExgjPZ8FBjxKAZdpdRf9iC0B/qjj/F3iSqdIZh/15+hG8Ny25I1MmKapX3I6hUIzgdB04t89/1O/w1cDnyilFU=';

        $messages[] = array('type' => 'text' , 'text' => 'สถานะการแจ้ง'.$job_type.': '.$status."\r\n".'เจ้าหน้าที่รับเรื่อง: '.$officer."\r\n".'ช่างผู้รับผิดชอบ: '.$technician."\r\n".'หมายเหตุ: '.$note);

        $arrayToSend = array('to' => $line_id,'messages' => $messages);
        $json = json_encode($arrayToSend);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST,"POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER,$headers);

        $response = curl_exec($ch);

        curl_close($ch);
    }
}
?>