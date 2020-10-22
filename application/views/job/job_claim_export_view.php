<?php

header("Content-Type: application/vnd.ms-excel");
header('Content-Disposition: attachment; filename="claim_and_repair_report_'.date("d-m-Y").'.xls"');
header("Content-Type: application/force-download");
header("Content-Type: application/octet-stream"); 
header("Content-Type: application/download");
header("Content-Transfer-Encoding: binary"); 

@readfile($filename); 

?>

<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <style type="text/css">
    table, td {border:thin solid black}
    table {border-collapse:collapse}
  </style>
</head>
<body>
  <table>
    <tr>
      <th>หมายเลขงาน</th>
      <th>วันที่แจ้ง</th>
      <th>ชื่อ-นามสกุล</th>
      <th>เบอร์โทร</th>
      <th>ที่อยู่</th>
      <th>หมายเลขเครื่องตัวเย็น</th>
      <th>หมายเลขเครื่องตัวร้อน</th>
      <th>หมายเลขอะไหล่</th>
      <th>ประเภท</th>
      <th>การรับประกัน</th>
      <th>ประเภทงาน</th>
      <th>ปัญหา</th>
      <th>เจ้าหน้าที่รับเรื่อง</th>
      <th>ช่างผู้รับผิดชอบ</th>
      <th>สถานะงาน</th>
      <th>วันที่อัพเดทสถานะงาน</th>
      <th>หมายเหตุ</th>
    </tr>
    <?php
    foreach ($this->data['report'] as $result) { ?>
      <tr>
        <td align="right"><?php echo strval($result['claim_id']); ?></td>
        <td align="right"><?php echo strval($result['claim_date']); ?></td>
        <td align="right"><?php echo strval($result['firstname'].' '.$result['lastname']); ?></td>
        <td align="right"><?php echo strval($result['phone_number']); ?></td>
        <td align="right"><?php echo strval($result['address']); ?></td>
        <td align="right"><?php echo strval($result['serial_number_indoor']); ?></td>
        <td align="right"><?php echo strval($result['serial_number_outdoor']); ?></td>
        <td align="right"><?php echo strval($result['part_number']); ?></td>
        <td align="right"><?php echo strval($result['type']); ?></td>
        <td align="right"><?php echo strval($result['warranty']); ?></td>
        <td align="right"><?php echo strval($result['job_type']); ?></td>
        <td align="right"><?php echo strval($result['problem']); ?></td>
        <td align="right"><?php echo strval($result['officer']); ?></td>
        <td align="right"><?php echo strval($result['technician']); ?></td>
        <td align="right"><?php echo strval($result['status']); ?></td>
        <td align="right"><?php echo strval($result['update_date']); ?></td>
        <td align="right"><?php echo strval($result['note']); ?></td>
      </tr>
    <?php } ?>
  </table>
</body>
</html>