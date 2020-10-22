<?php

header("Content-Type: application/vnd.ms-excel");
header('Content-Disposition: attachment; filename="financial_report_'.date("d-m-Y").'.xls"');
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
      <th colspan="5">Financial Report</th>
    </tr>
    <?php if ($this->data['from'] && $this->data['to']) { ?>
      <tr>
        <th colspan="5">Statement Period <?php echo $this->data['from'].' - '.$this->data['to']; ?></th>
      </tr>
    <?php } ?>
    <tr>
      <th>#</th>
      <th>Report Date</th>
      <th>Invoice ID</th>
      <th>Service</th>
      <th>Service Fee</th>
    </tr>
    <?php
    $total = 0;
    foreach ($this->data['financial_report'] as $result) { ?>
      <tr>
        <th align="right"><?php echo $result['index']; ?></th>
        <td align="right"><?php echo $result['report_date']; ?></td>
        <td align="right"><?php echo $result['invoice_prefix'].'-'.$result['invoice_id']; ?></td>
        <td align="center"><?php echo ucfirst($result['service']); ?></td>
        <td align="right"><?php echo $result['service_fee']; ?></td>
      </tr>
      <?php $total = $total + $result['service_fee']; } ?>
      <tr>
        <td colspan="4" align="right">Total</td>
        <td align="right"><?php echo $total; ?></td>
      </tr>
    </table>
  </body>
  </html>