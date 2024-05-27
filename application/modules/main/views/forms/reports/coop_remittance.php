<!DOCTYPE html>
<html>
<?php if(count($results)>0):?>
<head>
	<title>Coop Remittance</title>
	<link rel="stylesheet" href="<?php echo base_url().PATH_CSS ?>reports.css" type="text/css" />
</head>
<body>
<table class="center-85 f-size-12">
	<tbody>
		<tr>
			<td rowspan="5"><img src="<?php echo base_url().PATH_IMG ?>doh_logo.png" width=61 height=61></img></td>
		</tr>
		<tr>
			<td align="center">Republic of the Philippines</td>
		</tr>
		<tr>
			<td align="center">DOH COOPERATIVE SHARE</td>
		</tr>
		<tr>
	    	<td class="align-c">Remittance List of <?php echo strtoupper($results[0]['remittance_type_name']); ?></td>
	    </tr>
		<tr>
			<td align="center">For the month of <?php echo $results[0]['remittance_period'];?></td>
		</tr>
	</tbody>
</table>
<table class="center-85 f-size-12">
		<tr>
			<td align="center" colspan="2"><b><?php echo $deduction['deduction_name']?></b></td>
		</tr>
</table>
<br>
<div>
<table cellspacing="0">
  <tr>
    <td class="align-r fl-right pad-left-20">Agency Name :</td>
    <td class="align-l pad-left-20"><b>DEPARTMENT OF HEALTH</b></td>
  </tr>
  <tr>
    <td class="align-r fl-right pad-left-20">Office Name :</td>
    <td class="align-l pad-left-20"><b><?php echo strtoupper($results[0]['office_name']); ?></b></td>
  </tr>
  <tr>
    <td class="align-r fl-right pad-left-20">Office Address :</td>
    <td class="align-l pad-left-20">SAN LAZARO COMP. MANILA</td>
  </tr>
  <tr>
    <td class="align-r fl-right pad-left-20">Office Tel. No. :</td>
    <td class="align-l pad-left-20"></td>
  </tr>
</table>
<table cellspacing="0" class="table-max" border="1">
<tr>
  <th class="align-c" width="20">No.</th>
  <th class="align-c" width="150">Employee Name</th>
  <th class="align-c" width="100">Employee ID#</th>
  <th class="align-c" width="100">Amount Remitted</th>
  <th class="align-c" width="100">Number of Payment</th>
  <th class="align-c" width="100">Installment Number</th>
</tr>
<?php 
$total =0;
foreach ($results as $key => $value):
$total = $total + $value['amount'];
?>
<tr>
	<td class="pad-2 align-r"><?php echo  ++$key;?></td>
	<td class="pad-2"><?php echo  $value['employee_name'];?></td>
	<td class="pad-2"><?php echo  $value['agency_employee_id'];?></td>
	<td class="pad-2 align-r"><?php echo  number_format($value['amount'], 2);?></td>
	<td class="pad-2 align-c"><?php echo  $value['pay_num'];?></td>
	<td class="pad-2 align-c"><?php echo  $value['ins_num'];?></td>
</tr>
<?php endforeach;?>
<tr>
	<td class="pad-2 align-r" colspan="3">Grand Total : -----------------> </td>
	<td class="pad-2 align-r"><?php echo  number_format($total, 2);?></td>
	<td></td>
	<td></td>
</tr>
</table>
<div height="30px"></div>
  <table style="width:100%;">
    <tr>
      <td>
        <b>Prepared by:</b>
      </td>
      <td>
        <b>Certified by:</b>
      </td> 
    </tr>
    <tr><td>&nbsp;</td><td></td></tr>
    <tr><td>&nbsp;</td><td></td></tr>
    <tr>
      <td class="align-c bold">
        <u><?php echo nbs(3).$prepared_by['full_name'].nbs(3)?></u>
      </td>
      <td class="align-c bold">
        <u><?php echo nbs(3).$certified_by['full_name'].nbs(3)?></u>
      </td>
    </tr>
    <tr>
      <td class="align-c">Signature Over Printed Name</td>
      <td class="align-c">Signature Over Printed Name</td>
    </tr>
    <tr><td>&nbsp;</td><td></td></tr>
    <tr><td>&nbsp;</td><td></td></tr>
    <tr>
      <td class="align-c bold">
        <?php echo nbs(3).$prepared_by['position'].nbs(3)?>
      </td>
      <td class="align-c bold">
        <?php echo nbs(3).$certified_by['position'].nbs(3)?>
      </td>
    </tr>
    <tr>
      <td class="align-c bold">
        <u><?php echo nbs(3).$prepared_by['office'].nbs(3)?></u>
      </td>
      <td class="align-c bold">
        <u><?php echo nbs(3).$certified_by['office'].nbs(3)?></u>
      </td>
    </tr>
    <tr>
      <td class="align-c">Designation</td>
      <td class="align-c">Designation</td>
    </tr>
  </table>    
</div>

</body>
<?php else :?>

<div class="wrapper">
	<form id="test_report">
		<p style="text-align: center">No data available.</p>
	</form>
</div>

<?php endif;?>
</html>
