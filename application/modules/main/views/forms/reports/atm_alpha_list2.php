<!DOCTYPE html>
<html>
<?php if(count($results)>0):?>
<head>
	<title>ATM Alpha List</title>
	<link rel="stylesheet" href="<?php echo base_url().PATH_CSS ?>reports.css" type="text/css" />
</head>
<body>
<?php foreach ($effective_date as $date):?>
<div style="text-align: center;">
    <img src="<?php echo base_url().PATH_IMG ?>doh_logo.png" width="80" height="78">
	<span class="center-85 f-size-10">Republic of the Philippines</span><br>DEPARTMENT OF HEALTH
</div>
<table class="table-max" border="0">
<tbody>
	 <tr>
		<td class="align-c f-size-12"><br><b>ATM Alpha List</b></td>
    </tr>
    <tr>
		<td class="align-c"><br><b><?php echo date("F j, Y", strtotime($date['effective_date']));?></b></td>
	</tr>
	<tr>
		<td class="valign-bot align-c"><br><?php echo $bank['bank_name'];?><br>DATA BASE REPORT<br><?php echo $bank['bank_name'] . " - [CODE : " . $bank['branch_code'] . "]";?><br>BATCH <?php echo $details['batch_code'];?></td>
	</tr>
</tbody>
</table>
<div>
<table class="table-max" border="0">
	<tbody>
		<tr>
			<th class="align-c" width="100">ACCOUNT NAME</th>
			<th class="align-c" height="30" width="100">ACCOUNT NO.</th>
			<th class="align-c" width="100">CREDIT AMOUNT</th>
			<th class="align-c" width="100">EMPLOYEE NAME</th>
			<th class="align-c" width="100">EMPLOYEE NO.</th>
		</tr>
		<?php
			$total = 0;
			foreach ($results[$date['effective_date']] as $result):
			$total = $total + $result['amount'];
		?>
		<tr>
			<td><?php echo $result['full_name'];?></td>
			<td><?php echo ISSET($result['acct_no'])?$result['acct_no']:"&nbsp;";?></td>
			<td class="td-right-middle"><?php echo number_format($result['amount'], 2);?></td>
			<td>&nbsp;</td>
			<td><?php echo ISSET($result['emp_no'])?$result['emp_no']:"&nbsp;";?></td>
		</tr>
		<?php endforeach;?>
	</tbody>
</table>

<table class="center-50">
	<tbody>
		<tr>
			<td class="align-c" height="50" colspan="3">********* END OF DATA BASE REPORT *********</td>
		</tr>
		<tr>
			<td width="150">TOTAL NO. OF RECORDS</td>
			<td width="2">:</td>
			<td class="align-r"><?php echo count($results[$date['effective_date']]);?></td>
		</tr>
		<tr>
			<td>TOTAL CREDIT AMOUNT</td>
			<td>:</td>
			<td class="align-r"><?php echo number_format($total, 2);?></td>
		</tr>
	</tbody>
</table>
<br>
<table class="table-max" border="0">
	<tbody>
		<tr>
			<td class="valign-top" height="50" >CERTIFIED CORRECT:</td>
			<td rowspan="3" width="10%"></td>
			<td class="valign-top">APPROVED:</td>
			<td rowspan="3" width="10%"></td>
			<td class="valign-top">CERTIFIED CASH AVAILABLE:</td>
		</tr>
		<tr>
			<td class="bold align-c td-border-top f-size"><?php echo $certified_by['full_name'];?></td>
			<td class="bold align-c td-border-top f-size"><?php echo $approved_by['full_name'];?></td>
			<td class="bold align-c td-border-top f-size"><?php echo $certified_cash_by['full_name'];?></td>
		</tr>
		<tr>
			<td class="bold align-c f-size"><?php echo $certified_by['position'] . ", " . $certified_by['office'];?></td>
			<td class="bold align-c f-size"><?php echo $approved_by['position'] . ", " . $approved_by['office'];?></td>
			<td class="bold align-c f-size"><?php echo $certified_cash_by['position'] . ", " . $certified_cash_by['office'];?></td>
		</tr>
	</tbody>
</table>
</div>
<pagebreak>
<?php endforeach;?>
</body>

<?php else :?>

<div class="wrapper">
	<form id="test_report">
		<p style="text-align: center">No data available.</p>
	</form>
</div>

<?php endif;?>
</html>