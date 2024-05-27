<?php 	
	$num_array = array(
		1 => 'One',
		2 => 'Two',
		3 => 'Three',
		4 => 'Four',
		5 => 'Five',
		6 => 'Six',
		7 => 'Seven',
		8 => 'Eight',
		9 => 'Nine',
		10 => 'Ten'
		);
	$label = '';
	$address = '';
	// if($sec_record['gender_code'] == 'F')
	// {
	// 	$label = 'MS. ';
	// }
	// if($sec_record['gender_code'] == 'M')
	// {
	// 	$label = 'MR. ';
	// }
	// if($sec_record['gender_code'] == 'M')
	// {
	// 	$address = 'Sir';
	// }
	// if($sec_record['gender_code'] == 'F')
	// {
	// 	$address = 'Madam';
	// }
	// if($sec_record['gender_code'] == '')
	// {
	// 	$address = 'Sir';
	// 	$label = 'MR. ';
	// }
	if($sec_record['gender_code'] == 'F')
	{
		// $label = 'MS. ';
		
		//===== marvin : start : position/eligibility ======
		$sec = array(16509);
		$asec = array(16368,16506);
		$usec = array(16512,16973);
		$director = array(16526,16527,16528,16531);
		
		if(in_array($sec_record['position_id'], $sec))
		{
			$label = 'Sec. ';
		}
		elseif(in_array($sec_record['position_id'], $asec))
		{
			$label = 'Asec. ';
		}
		elseif(in_array($sec_record['position_id'], $usec))
		{
			$label = 'Usec. ';
		}
		elseif(in_array($sec_record['position_id'], $director))
		{
			$label = 'Dir. ';
		}
		elseif(in_array(2161, $sec_record['eligibility']) OR in_array(2169, $sec_record['eligibility']))
		{
			$label = 'Dr. ';
		}
		elseif(in_array(2116, $sec_record['eligibility']))
		{
			$label = 'Atty. ';
		}
		elseif(in_array(2117, $sec_record['eligibility']))
		{
			$label = 'Engr. ';
		}
		elseif(in_array(2166, $sec_record['eligibility']))
		{
			$label = 'Arch. ';
		}
		else
		{
			$label = 'MS. ';				
		}
		//===== marvin : end : position/eligibility ======
	}
	if($sec_record['gender_code'] == 'M')
	{
		// $label = 'MR. ';
		
		//===== marvin : start : position/eligibility ======
		$sec = array(16509);
		$asec = array(16368,16506);
		$usec = array(16512,16973);
		$director = array(16526,16527,16528,16531);
		
		if(in_array($sec_record['position_id'], $sec))
		{
			$label = 'Sec. ';
		}
		elseif(in_array($sec_record['position_id'], $asec))
		{
			$label = 'Asec. ';
		}
		elseif(in_array($sec_record['position_id'], $usec))
		{
			$label = 'Usec. ';
		}
		elseif(in_array($sec_record['position_id'], $director))
		{
			$label = 'Dir. ';
		}
		elseif(in_array(2161, $sec_record['eligibility']) OR in_array(2169, $sec_record['eligibility']))
		{
			$label = 'Dr. ';
		}
		elseif(in_array(2116, $sec_record['eligibility']))
		{
			$label = 'Atty. ';
		}
		elseif(in_array(2117, $sec_record['eligibility']))
		{
			$label = 'Engr. ';
		}
		elseif(in_array(2166, $sec_record['eligibility']))
		{
			$label = 'Arch. ';
		}
		else
		{
			$label = 'MR. ';				
		}
		//===== marvin : end : position/eligibility ======
	}
	if($sec_record['gender_code'] == 'M')
	{
		$address = 'Sir';
	}
	if($sec_record['gender_code'] == 'F')
	{
		$address = 'Madam';
	}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
	<title>Notice of Salary Step Increment</title>
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
		<td colspan=4 height="50" align="left" valign=bottom><b><u><center>NOTICE OF SALARY STEP INCREMENT</center></u></b><br><span style="float:right; padding-top:10px"></span></td>
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
		<td colspan=4 height="20" align="left" valign=middle><b><?php echo $label?><?php echo $sec_record['employee_name']; ?></b><br><?php echo convertTitleCase($sec_record['employ_position_name']); ?><br><?php echo !EMPTY(convertTitleCase($sec_record['employ_office_name'])) ? convertTitleCase(str_replace('- DOH', '', $sec_record['employ_office_name'])) : convertTitleCase(str_replace('- DOH', '', $sec_record['office_name'])); ?><br>This Department</td>
	</tr>
	<tr>
		<td colspan=4 height="20"></td>
	</tr>
	<tr>
		<td colspan=4 height="40" align="justify" valign=middle><b><?php echo $address?>:</b><br><br><?php echo nbs(10) ?><?php echo $body['sys_param_value'] ?>, your salary as <b><?php echo convertTitleCase($sec_record['employ_position_name']); ?>, SG-<?php echo $sec_record['sec_grade']; ?></b>, is hereby adjusted effective <b><?php echo $sec_record['start_date']; ?></b> as follows:</p></td>
	</tr>
	<tr>
		<td colspan=4 height="15"></td>
	</tr>
	<tr>
		<td colspan=3 height="40" align="left" valign=middle><?php echo nbs(10) ?>1.<?php echo nbs(5) ?>Actual monthly basic salary at SG-<?php echo $first_record['first_grade']; ?>, Step <?php echo $first_record['first_step']; ?></td>
		<td colspan=1 align="right" valign=middle width="80"><u>&#8369; <?php echo number_format($first_record['first_salary'], 2); ?></u></td>
	</tr>
	<tr>
		<td colspan=3 height="50" align="left" valign=middle><?php echo nbs(10) ?>2.<?php echo nbs(5) ?>Add: One (1) salary step increment for <?php echo nbs(18) ?><?php echo $step_incr_reason; ?></td>
		<td colspan=1 align="right" valign=middle width="80"><u><?php echo number_format(($sec_record['sec_salary'] - $first_record['first_salary']), 2);?></u></td>
	</tr>
	<tr>
		<!--<td colspan=3 height="40" align="left" valign=middle><?php //echo nbs(10) ?>3.<?php //echo nbs(5) ?>Adjusted monthly basic salary effective <?php //echo $sec_record['start_date']; ?></td>-->
		<!-- marvin : include adjusted step : start -->
		<td colspan=3 height="50" align="left" valign=middle><?php echo nbs(10) ?>3.<?php echo nbs(5) ?>Adjusted monthly basic salary effective <?php echo $sec_record['start_date']; ?><br><?php echo nbs(18) ?>at SG-<?php echo $sec_record['sec_grade'] . ' Step ' . $sec_record['sec_step']; ?></td>
		<!-- marvin : include adjusted step : end -->
		<td colspan=1 align="right" valign=middle><u>&#8369; <?php echo number_format($sec_record['sec_salary'], 2); ?></td>
	</tr>
	<tr>
		<td colspan=4 height="60" align="justify" valign=middle><?php echo nbs(10) ?>This salary increase is subject to review and post-audit, and to appropriate re-adjustment and refund if found not in order. <br><br></td>
	</tr>	
	<tr>
		<td colspan=4 height="10"></td>
	</tr>
	<tr>
		<!--<td colspan=4 height="" align="left" valign=middle>Item No./Unique Item No. FY, <u><?php //echo date_format(date_create($sec_record['start_date']), 'Y')?></u></td>-->
		<td colspan=4 height="" align="left" valign=middle>Item No./Unique Item No. <b>FY <u><?php echo date_format(date_create($sec_record['start_date']), 'Y')?></u></b> Personnel Services Itemization and/or Plantilla of Personnel: <u><b><?php echo $sec_record['plantilla_code']; ?></u></b></td>
	</tr>
	<!--<tr>
		<td colspan=4 height="" align="left" valign=middle>and/or Plantilla of Personnel: <u><?php //echo $sec_record['plantilla_code']; ?></u></td>
	</tr>-->
	<tr>
		<td colspan=4 height="20"></td>
	</tr>
</table>
<table class="center-85 f-size-notice">
	<tr>
		<td height="20" align="left" valign=middle width="50%"><br></td>
		<td align="center" valign=middle width="50%">Very truly yours,</td>
	</tr>
	<tr>
		<td height="40"></td>
	</tr>
	<tr>
		<td height="20" align="left" valign=middle><br></td>
		<td align="center" valign=middle><b><?php echo strtoupper($certified_by['signatory_name']); ?></b><br><?php echo $certified_by['position_name'] ?><br><?php echo $certified_by['office_name'] ?></td>
	</tr>
	<tr>
		<td height="20"></td>
	</tr>
	<tr>
		<td height="20" class="font-size-sm" align="left" valign=middle><?php echo $cc['sys_param_value']; ?></td>
	</tr>
</table>
<!-- ************************************************************************** -->
</body>

</html>
