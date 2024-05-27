
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
	$first_record = $records[0];
	$last_record = end($records);
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
	
	<title>Notice of Longevity Pay Increase</title>
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
		<td colspan=4 height="50" align="left" valign=bottom><b><u><center>Notice of Longevity Pay Increase</center></u></b><br><span style="float:right; padding-top:10px"></span></td>
	</tr>
	<tr>
		<td colspan=4 height="20" align="left"></td>
	</tr>
	<tr>
		<td colspan=4 align="right" valign="middle"><?php echo nbs(1) ?><?php echo date("F d, Y")?><?php echo nbs(1) ?></td>
	</tr>
	<tr>
		<td colspan=4 height="40"></td>
	</tr>
	<tr>
		<td class="bold" colspan=4 height="20" align="left" valign="bottom"><?php echo strtoupper($label)?><?php echo $first_record['employee_name']; ?></td>
	</tr>
	<tr>
		<td colspan=4 align="left"><?php echo convertTitleCase($first_record['employ_position_name']); ?><br><?php echo convertTitleCase($first_record['office_name']); ?></td>
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
		<td colspan=4 height="20" align="justify"><?php echo nbs(10) ?><?php echo $body['sys_param_value'] ?>, your <b><?php echo $ordinal_array[$first_record['lp_num']] ?></b> Longevity Pay as <b><?php echo !EMPTY($first_record['position_name']) ? convertTitleCase($first_record['position_name']) : convertTitleCase($first_record['employ_position_name']); ?>, SG-<?php echo $last_record['salary_grade']; ?>, Step <?php echo $last_record['salary_step']; ?></b>, is re-adjusted effective <b><?php echo $effective_date['effective_date']; ?></b>, shall be as follows:</td>
	</tr>
	<tr>
		<td colspan=4 height="20" align="left"></td>
	</tr>
	<tr>
		<td colspan=3 height="40" align="left" valign="middle">1. Actual monthly basic salary</td>
		<td colspan=1 align="right">&#8369; <?php echo number_format($last_record['basic_amount'], 2) ?></td>
	</tr>
	<tr>
		<td colspan=3 height="40" align="left" valign="middle" width="400">2. <b><?php echo $ordinal_array[$first_record['lp_num']] ?></b> Longevity Pay as of <b><?php echo $effective_date['effective_date']; ?></b><br><?php echo nbs(4) ?>due to salary increase pursuant to EO 201</td>
		<td colspan=1 align="right"><?php echo  number_format($last_record['pay_amount'], 2) ?></td>
	</tr>
	<tr>
	<?php 
		$third_val  = ($first_record['lp_num'] == 1) ? 0.00 : number_format($first_record['pay_amount'], 2);
		$fourth_val = ($first_record['lp_num'] == 1) ? $last_record['pay_amount'] : ($last_record['pay_amount'] - $first_record['pay_amount']);
	 ?>
		<td colspan=3 height="40" align="left" valign="middle">3. <b><?php echo $ordinal_array[$first_record['lp_num']] ?></b> Longevity Pay (granted prior to item 2)</td>
		<td colspan=1 align="right"><?php echo $third_val ?></td>
	</tr>
	<tr>
		<td colspan=3 height="40" align="left" valign="middle">4. <b><?php echo $ordinal_array[$first_record['lp_num']] ?></b> Longevity Pay Increase (item 2 less item 3)</td>
		<td colspan=1 align="right">&#8369; <?php echo number_format(($fourth_val), 2) ?></td>
	</tr>
	<tr>
		<td colspan=4 height="40" align="justify" valign="middle"><?php echo nbs(10) ?>This <b><?php echo $ordinal_array[$first_record['lp_num']] ?></b> Longevity Pay is subject to review and post-audit, and to appropriate re-adjustment and refund if found not in order.</td>
	</tr>
	<tr>
		<td colspan=4 height="20" align="left"></td>
	</tr>
	<tr>
		<td colspan=4 height="17" align="left" valign="middle">Item No./Unique Item No., FY <u><?php echo date_format(date_create($effective_date['effective_date']), 'Y')?></u> Personal Services Itemization</td>
	</tr>
	<tr>
		<td colspan=4 height="17" align="left" valign="middle">and/or Plantilla of Personnel:<u><?php echo nbs(5) ?><?php echo $first_record['plantilla_code'] ?><?php echo nbs(5) ?></u></td>
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
