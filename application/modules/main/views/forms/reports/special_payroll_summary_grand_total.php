<!DOCTYPE html>
<html>
<head>
	<title>Doh</title>
	<link rel="stylesheet" href="<?php echo base_url().PATH_CSS ?>reports.css" type="text/css" />
</head>
<body>
<table class="center-100 f-size-12">
  <tbody>
    <tr>
      <td width="150" height="10" align="left" valign="top"><img src="<?php echo base_url().PATH_IMG ?>doh_logo.png" width="80" height="78"></td>
      <td colspan="5" align="center" valign="center" style="padding-bottom:10px;">
          <b>Special Payroll Summary Grand Total<br>
          <span style="font-size:8px;">San Lazaro, Manila<br>
          </span>Administrative Service<br>
          <?php echo $compensation_details['compensation_name']; ?> CY 2016</b></td> 
    </tr>
  </tbody>
</table>
<br>


<div>
<table class="center-85" border="1">
  <tr>
    <td>No.</td>
    <td>Name</td>
    <td>Designation</td>
    <td>SG</td>
    <td><?php echo $compensation_details['compensation_name']; ?></td>

    <?php if($compensation_sub_details < 1 ) : ?>
        <?php foreach ($compensation_sub_details as $csd) { ?>
            <td><?php echo $csd['compensation_name']; ?></td>
        <?php  } ?>
    <?php endif; ?> 
    <td>NET PAY</td>
    <td>SIGNATURE</td>
    <td>INITIALS</td>
    <td>REMARKS</td>
  </tr>

  <?php 
  $n = 1;
  foreach ($records as $record) { 

  ?>  

  <tr>
    <td><?php echo $n++; ?></td>
    <td><?php echo $record['employee_name']; ?></td>
    <td><?php echo $record['position_name']; ?></td>
    <td><?php echo $record['employ_salary_grade']; ?></td>
    <td><?php echo $record['amount']; ?></td>
    <td><?php echo $record['other_amount']; ?></td>
    <td><?php echo $record['net_amount']; ?></td>
    <td></td>
    <td></td>
    <td></td>
    
  </tr>

  <?php } ?>

</table>

</div>
</body>
</html>