<?php 
	$ordinal_array = array(
		1 => '1st',
		2 => '2nd',
		3 => '3rd',
		4 => '4th',
		5 => '5th',
		6 => '6th',
		7 => '7th',
		8 => '8th',
		9 => '9th',
		10 => '10th'
		);
	$label = '';
	$address = '';
	if($sec_record['gender_code'] == 'F')
	{
		$label = 'MS. ';
	}
	if($sec_record['gender_code'] == 'M')
	{
		$label = 'MR. ';
	}
	if($sec_record['gender_code'] == 'M')
	{
		$address = 'Sir';
	}
	if($sec_record['gender_code'] == 'F')
	{
		$address = 'Madam';
	}
	if($sec_record['gender_code'] == '')
	{
		$address = 'Sir';
		$label = 'MR. ';
	}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<html>
<head>
	
	<title>Notice of Step Increment</title>
	<link rel="stylesheet" href="<?php echo base_url().PATH_CSS ?>reports.css" type="text/css" />
</head>

<body>
<table class="f-size-notice table-max">
    <tbody>
        <tr>
            <td align="right" width="25"><?php echo nbs(10) ?><img src="<?php echo base_url().PATH_IMG ?>doh_logo.png" width=90 height=90></img></td>
            <td class="align-c f-size-notice" width="50%">Republic of the Philippines<br><span class="f-size-notice">Department of Health</span><br><span class="f-size-14-notice bold">OFFICE OF THE SECRETARY</span></td>                
            <td width="25%"><br></td>
        </tr>
    </tbody>
</table>
	<table class="center-85 f-size-notice">
		<tr>
			<td colspan=4 height="50" align="left" valign=bottom><b><u><center>NOTICE OF STEP INCREMENTS</center></u></b><br><span style="float:right; padding-top:10px"></span></td>
		</tr>
		<tr>
			<td colspan=4 height="20"></td>
		</tr>
		<tr>
			<td class="align-r" colspan=4 ><?php echo date("F d, Y")?></td>
		</tr>
		<tr>
			<td colspan=4 height="40"></td>
		</tr>
		<tr>
			<td colspan=4 height="40" align="left" valign=middle><b><?php echo $label?><?php echo $sec_record['employee_name']; ?></b><br><?php echo !EMPTY($sec_record['employ_office_name']) ? convertTitleCase($sec_record['employ_office_name']) : convertTitleCase($sec_record['office_name']); ?><br>This Department</td>
		</tr>
		<tr>
			<td colspan=4 height="20"></td>
		</tr>
		<tr>
			<td colspan=4 height="40" align="justify" valign=middle><b><?php echo $address?>:</b><br><br><?php echo nbs(10) ?><?php echo $body['sys_param_value'] ?>, your salary as <b><?php echo convertTitleCase($sec_record['position_name']) ; ?> (SG-<?php echo $sec_record['sec_grade']; ?>)</b> is hereby adjusted effective <b><?php echo $sec_record['start_date']; ?></b> as shown below:</td>
		</tr>
		<tr>
			<td colspan=4 height="15"></td>
		</tr>
		<tr>
			<td colspan=3 height="40" align="left" valign=middle><?php echo nbs(10) ?>Basic Monthly Salary <br><?php echo nbs(9) ?> As of <b><?php echo $first_record['employ_end_date']; ?></b></td>
			<td colspan=1 align="right" valign=middle width="80"><b>&#8369; <?php echo number_format($first_record['first_salary'], 2); ?></b></td>
		</tr>
		<tr>
			<td colspan=3 height="40" align="left" valign=middle><?php echo nbs(10) ?>Salary Adjustment</td>
			<td colspan=1 align="right" valign=middle width="80"></td>
		</tr>
		<tr>
			<td colspan=3 height="20" align="left" valign=middle><?php echo nbs(10) ?>a) Merit</td>
			<td colspan=1 align="right" valign=middle width="80"></td>
		</tr>
		<tr>
			<td colspan=3 height="20" align="left" valign=middle><?php echo nbs(10) ?>b) Length of service . . . Difference . . . (<?php echo $ordinal_array[$sec_record['sec_step']]; ?> step)</td>
			<td colspan=1 align="right" valign=middle width="80"><b><u><?php echo number_format(($sec_record['sec_salary'] - $first_record['first_salary']), 2);?></u></b></td>
		</tr>
		<tr>
			<td colspan=3 height="40" align="right" valign=middle><b>TOTAL:</b></td>
			<td colspan=1 align="right" valign=middle width="80"><b><u>&#8369; <?php echo number_format($sec_record['sec_salary'], 2); ?></u></b></td>
		</tr>
		<tr>
			<td colspan=4 height="40" align="justify" valign=middle><?php echo nbs(10) ?>The step increment/s is/are subject to review and post-audit by the Department of Budget and Management and subject to readjustment and refund if found not in order.<br><br></td>
		</tr>
		<tr>
			<td colspan=4 height="20"></td>
		</tr>
	</table>
	<table class="center-85 f-size-notice">
		<tr>
			<td colspan=2 height="20" align="left" valign=middle><br></td>
			<td colspan=2 align="center" valign=middle>Very truly yours,<br>By authority of the Secretary of Health:</td>
		</tr>
		<tr>
			<td colspan=4 height="40"></td>
		</tr>
		<tr>
			<td colspan=2 height="20" align="left" valign=middle><br></td>
			<td colspan=2 align="center" valign=middle><b><?php echo strtoupper($certified_by['signatory_name']); ?></b><br><?php echo $certified_by['position_name'] ?><br><?php echo $certified_by['office_name'] ?></td>
		</tr>
		<tr>
			<td colspan=4 height="40"></td>
		</tr>
		<tr>
			<td colspan=4 height="20" class="font-size-sm" align="left" valign=middle><?php echo $cc['sys_param_value'] ?></td>
		</tr>
	</table>
<!-- ************************************************************************** -->
</body>

</html>
