
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<?php 
	$ordinal_array = array(
		1 => 'First',
		2 => 'Second',
		3 => 'Third',
		4 => 'Fourth',
		5 => 'Fifth',
		6 => 'Sixth',
		7 => 'Seventh',
		8 => 'Eight',
		9 => 'Ninth',
		10 => 'Tenth'
		);
	$last_record  = $records[0];
	$first_record = end($records);
	$record_cnt  = count($records) - 2;
	if($record_cnt > -1)
	{
		$data_raw    = $records[$record_cnt]['effective_date'];
		$first_date  = strtotime($data_raw);
		$prev_date   = strtotime('-1 day', $first_date);
		$actual_date = 'as of <b>' . date('F d, Y', $prev_date) . '</b>';	
	} 
	else 
	{
		$actual_date = 'as of <b>' . date('F d, Y', strtotime('-1 day', strtotime($records[0]['effective_date'])));
	}

	$label = '';
	if($records[0]['gender_code'] == 'F')
	{
		$label = 'Ms. ';
	}
	if($records[0]['gender_code'] == 'M')
	{
		$label = 'Mr. ';
	}
	if($records[0]['gender_code'] == 'M')
	{
		$address = 'Sir';
	}
	if($records[0]['gender_code'] == 'F')
	{
		$address = 'Madam';
	}

?>

<html>
<head>
	
	<title>Notice of Longevity Pay</title>
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
		<td colspan=4 height="50" align="left" valign=middle><b><center>Notice of Longevity Pay</center></b><br><span style="float:right; padding-top:10px"></span></td>
	</tr>
	<tr>
		<td colspan=4 height="20" align="right" valign="middle"><?php echo date("F d, Y")?></td>
	</tr>
	<tr>
		<td colspan=4 height="40"></td>
	</tr>
	<tr>
		<td colspan=4 align="left"><b><?php echo strtoupper($label)?><?php echo strtoupper($last_record['employee_name']); ?></b></td>
	</tr>
	<tr>
		<td colspan=4 align="left"><?php echo convertTitleCase($last_record['position_name']); ?></td>
	</tr>
	<tr>
		<td colspan=4 align="left"><?php echo convertTitleCase($last_record['name']); ?></td>
	</tr>
	<tr>
		<td colspan=4 align="left">This Department</td>
	</tr>
	<tr>
		<td colspan=4 height="20" align="left"></td>
	</tr>
	<tr>
		<td colspan=4 height="20" align="left">Dear <?php echo $address?>:</td>
	</tr>
	<tr>
		<td colspan=4 height="20" align="left"></td>
	</tr>
	<tr>
		<td colspan=4 height="20" align="justify"><?php echo nbs(10) ?><?php echo $body['sys_param_value'] ?>, your <b><?php echo $ordinal_array[$last_record['lp_num']] ?></b> Longevity Pay as <b><?php echo convertTitleCase($last_record['position_name']); ?>, SG <?php echo $last_record['salary_grade']; ?>, Step <?php echo $last_record['salary_step']; ?></b>, effective <b><?php echo $last_record['effective_date']; ?></b>, shall be as follows:</td>
	</tr>
	<tr>
		<td colspan=4 height="20" align="left"></td>
	</tr>
	<tr>
		<td colspan=3 height="20" align="left" valign="middle">1. Actual monthly basic salary <?php echo $actual_date ?></td>
		<td colspan=1 align="right">&#8369;<?php echo nbs(5) ?><?php echo number_format($first_record['basic_amount'], 2); ?></td>
	</tr>
	<tr>
		<td colspan=3 height="20" align="left" valign="middle">2. <?php echo $ordinal_array[$first_record['lp_num']]?> Longevity Pay (5% of item)</td>
		<td colspan=1 align="right"><?php echo number_format($first_record['pay_amount'], 2) ?></td>
	</tr>
	<tr>
		<td colspan=4 height="40" align="left" valign="middle"><?php echo nbs(10) ?>Your Total Longevity Pay as of <b><?php echo $last_record['effective_date']; ?></b>, follows:</td>
	</tr>
	<?php 
		$records = array_reverse($records, TRUE);
		$cnt = 1;
	foreach ($records as $record) { ?>
		<tr>
			<td colspan=3 height="20" align="left" valign="middle"><?php echo $ordinal_array[$record['lp_num']]?> Longevity Pay as of <u><?php echo nbs(1) ?><?php echo $record['effective_date']; ?><?php echo nbs(1) ?></u></td>
			<td colspan=1 align="right"><?php echo ($cnt == 1) ? '&#8369;' : ''?><?php echo nbs(5) ?><?php echo number_format($record['pay_amount'], 2); ?></td>
		</tr>
	<?php $cnt++; }  ?>
	<tr>
		<td colspan=3 height="30" class="bold" align="left" valign="top">Total Longevity Pay</td>	
		<td colspan=1 align="right" class="td-border-light-top" width="50">&#8369;<?php echo nbs(5) ?><?php echo number_format($last_record['total_amount'], 2) ?></td>
	</tr>
	<tr>
		<td colspan=4 height="40" align="justify" valign="middle"><?php echo nbs(10) ?>This <b><?php echo $ordinal_array[$last_record['lp_num']] ?></b> Longevity Pay is subject to review and post audit, and to appropriate re-adjustment and refund if found not in order.</td>
	</tr>	
	<tr>
		<td colspan=4 height="10" align="left"></td>
	</tr>
	<tr>
		<td colspan=4 height="17" align="left" valign="middle">Item No./Unique Item No. FY <?php echo $fy; ?> Personal Services Itemization</td>
	</tr>
	<tr>
		<td colspan=4 height="17" align="left" valign="middle">and/or Plantilla of Personnel:<b><u><?php echo nbs(1) ?><?php echo $last_record['plantilla_code'] ?><?php echo nbs(1) ?></u></b></td>
	</tr>
	<tr>
		<td colspan=4 height="40" align="left"></td>
	</tr>
</table>
<table class="table-max f-size-notice">
	<tr>
		<td width="300" height="20" align="left" valign="middle"></td>
		<td align="center" valign="middle">Very truly yours,</td>
	</tr>
	<tr>
		<td height="30" align="left"></td>
	</tr>
	<tr>
		<td height="20" align="left" valign="middle"></td>
		<td align="center" valign="middle"><?php echo nbs(5) ?><b><?php echo strtoupper($certified_by['signatory_name']); ?></b><?php echo nbs(5) ?></td>
	</tr>
	<tr>
		<td height="20" align="left" valign="middle"></td>
		<td align="center" valign="middle"><?php echo $certified_by['position_name'] ?><br><?php echo $certified_by['office_name'] ?></td>
	</tr>
</table>
<!-- ************************************************************************** -->
</body>

</html>
