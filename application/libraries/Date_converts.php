<?php 

defined('BASEPATH') OR exit('No direct script access allowed');


class Date_converts
{

  public function DateThai($strDate)
  {

    $strDate = strtotime($strDate);

    $strYear = date("Y",$strDate)+543;
    $strMonth= date("n",$strDate);
    $strDay= date("j",$strDate);
    $strMonthCut = Array("","ม.ค.","ก.พ.","มี.ค.","เม.ย.","พ.ค.","มิ.ย.","ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย.","ธ.ค.");
    $strMonthThai=$strMonthCut[$strMonth];

    return "$strDay $strMonthThai $strYear";
  }

  public function DateUS($strDate)
  {

    $strDate = strtotime($strDate);

    $strYear = date("Y",$strDate);
    $strMonth= date("n",$strDate);
    $strDay= date("j",$strDate);
    $strMonthCut = Array("","Jan.","Feb.","Mar.","Apr.","May.","Jun.","Jul.","Aug.","Sep.","Oct.","Nov.","Dec.");
    $strMonthUS=$strMonthCut[$strMonth];

    return "$strDay $strMonthUS $strYear";
  }

  public function DateTimeThai($strDate)
  {

    $strDate = strtotime($strDate);

    $strYear = date("Y",$strDate)+543;
    $strMonth= date("n",$strDate);
    $strDay= date("j",$strDate);
    $strTime = date("H:i",$strDate);
    $strMonthCut = Array("","ม.ค.","ก.พ.","มี.ค.","เม.ย.","พ.ค.","มิ.ย.","ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย.","ธ.ค.");
    $strMonthThai=$strMonthCut[$strMonth];

    return "$strDay $strMonthThai $strYear $strTime";
  }

  public function DateTimeUS($strDate)
  {

    $strDate = strtotime($strDate);

    $strYear = date("Y",$strDate);
    $strMonth = date("n",$strDate);
    $strDay = date("j",$strDate);
    $strTime = date("H:i",$strDate);
    $strMonthCut = Array("","Jan.","Feb.","Mar.","Apr.","May.","Jun.","Jul.","Aug.","Sep.","Oct.","Nov.","Dec.");
    $strMonthUS=$strMonthCut[$strMonth];

    return "$strDay $strMonthUS $strYear $strTime";
  }
}
?>