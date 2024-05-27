<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<head>
 <title>Certificate of Creditable Tax Withheld At Source</title>
 <link rel="stylesheet" href="<?php echo base_url().PATH_CSS ?>reports.css" type="text/css" />
</head>

<body>
<table class="table-max f-size-12">
    <tbody>
        <tr>
            <td width="20%"><?php echo nbs(10) ?><img src="<?php echo base_url().PATH_IMG ?>doh_logo.png" width=90 height=90></img><br></td>
            <td align="center" width="60%">Republic of the Philippines<br>DEPARTMENT OF HEALTH</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
          <td colspan="3" height="50" valign="top" align="center"><b>EMPLOYEES PAID BY VOUCHER<br><?php echo $office_name;?><br>FOR THE MONTH OF <?php echo $month_text;?>, <?php echo $year;?></b></td>
        </tr>
    </tbody>
</table>
<table class="table-max" border="1">
	<tr>
		<th class="align-c" rowspan="2" width="150">PAYEE</th>
		<th class="align-c" rowspan="2" width="100">VOUCHER DESCRIPTION</th>
		<th class="align-c" rowspan="2" width="80">Check Date</th>
		<th class="align-c" rowspan="2" width="80">Check No.</th>
		<th class="align-c" colspan="<?php echo (count($com_header) + 1)?>">GROSS INCOME</th>
		<th class="align-c" colspan="<?php echo (count($ded_header) + 1)?>">DEDUCTIONS</th>
		<th class="align-c" rowspan="2" width="100">NET PAY</th>
	</tr>
	<tr>
		<?php foreach ($com_header as $com):?>
			<th class="align-c" width="100"><?php echo $com['compensation_code'] ?></th>
		<?php endforeach;?>
		<th class="align-c" width="100">TOTAL</th>
		<?php foreach ($ded_header as $ded):?>
			<th class="align-c" width="100"><?php echo $ded['deduction_code'] ?></th>
		<?php endforeach;?>
		<th class="align-c" width="100">TOTAL</th>
	</tr>
	<?php foreach($results as $result):?>
	<tr>
		<?php 
		foreach($result as $key => $res):
			if(is_numeric($res) and $key != 'check_no'):
		?>
			<td class="align-r f-size-12"><?php echo number_format($res, 2);?></td>
			<?php else:?>
			<td><?php echo $res;?></td>
		<?php 
			endif;
		endforeach;
		?>
	</tr>
	<?php endforeach;?>
	
	<tr>
		<td class="align-c bold" colspan="4">TOTALS</td>
		<?php foreach($vertical_totals as $ver_tot):?>
			<td class="align-r f-size-12"><?php echo number_format($ver_tot, 2);?></td>
		<?php endforeach;?>
	</tr>
</table>
<br><br>
<table class="f-size-10 center-85">
	<tr>
		<td height="20" align="left" valign="middle" width="50%">Prepared by:</td>
		<td height="20" align="left" valign="middle" width="50%">Certified correct:</td>
	</tr>		
	<tr>
		<td height="40"></td>
	</tr>
	<tr>
		<td height="20" align="center" valign="middle"><b><?php echo $prepared_by['signatory_name']; ?></b></td>
		<td height="20" align="center" valign="middle"><b><?php echo $certified_by['signatory_name']; ?></b></td>
	</tr>
	<tr>
		<td height="20" align="center" valign="middle"><?php echo $prepared_by['position_name'] ?><br><?php echo $prepared_by['office_name'] ?></td>
		<td height="20" align="center" valign="middle"><?php echo $certified_by['position_name'] ?><br><?php echo $certified_by['office_name'] ?></td>
	</tr>
</table>
</body>