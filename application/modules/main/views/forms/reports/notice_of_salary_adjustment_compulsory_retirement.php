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
	<title>Notice of Salary Adjustment (Compulsory Retirement)</title>
	<link rel="stylesheet" href="<?php echo base_url().PATH_CSS ?>reports.css" type="text/css" />
</head>

<body>
<table class="f-size-notice table-max">
    <tbody>
        <tr>
            <td align="right" width="25"><?php echo nbs(10) ?><img src="<?php echo base_url().PATH_IMG ?>doh_logo.png" width=90 height=90></img></td>
            <td class="align-c f-size-notice" width="50%">Republic of the Philippines<br><span class="f-size-notice">Department of Health</span><br><span class="f-size-14-notice bold">OFFICE OF THE SECRETARY</span></td>                
            <td align="left" width="25%"><img src="<?php echo base_url().PATH_IMG ?>bagong_pilipinas_logo.png" width=100 height=90></img></td>
        </tr>
    </tbody>
</table>
<table class="center-85 f-size-notice">
	<tr>
		<td colspan=5 height="50" align="left" valign=bottom><b><center>NOTICE OF SALARY ADJUSTMENT</center></b><br><span style="float:right; padding-top:10px"></span></td>
	</tr>
	<tr>
		<td colspan=4 height="20"></td>
	</tr>
	<tr>
		<td class="align-r" colspan=5 ><?php echo date("F d, Y")?></td>
	</tr>
	<tr>
		<td colspan=4 height="40"></td>
	</tr>
	<tr>
		<td colspan=4 height="20" align="left" valign=middle><b><?php echo $label.$sec_record['employee_name']; ?></b><br><?php echo convertTitleCase($sec_record['employ_position_name']); ?><br><?php echo !EMPTY(convertTitleCase($sec_record['employ_office_name'])) ? convertTitleCase(str_replace('- DOH', '', $sec_record['employ_office_name'])) : convertTitleCase(str_replace('- DOH', '', $sec_record['office_name'])); ?><br>This Department</td>
	</tr>
	<tr>
		<td colspan=4 height="20"></td>
	</tr>
	<tr>
		<td colspan=5 height="40" align="justify" valign=middle><b><?php echo $address?>:</b><br><br><?php echo nbs(10) ?>Pursuant to Sub Item 12.4 of the Department of Budget and Management and Department of Health Joint Circular No. 1, s. 2012, dated November 29, 2012 implementing item (6) of the Senate and House of Representatives Joint Resolution No. 4, s.2009, approved on June 17, 2009, and Section 30 of Republic Act No. 7305, your salary as <b><?php echo convertTitleCase($sec_record['employ_position_name']); ?>, SG-<?php echo $sec_record['salary_grade']; ?>, Step-<?php echo $sec_record['sec_step']; ?></b> is hereby adjusted effective  <b><?php echo $sec_record['employ_start_date']."*"; ?></b>, as follows:</p></td>
	</tr>
	<tr>
		<td colspan=1 height="15" max-width="10"></td>
		<td colspan=3 height="15" width="300"></td>
		<td colspan=1 height="15" width="100"></td>
	</tr>
	<tr>
		<td colspan=1></td>
		<td colspan=3 height="40" align="left" valign=middle>1.<?php echo nbs(3) ?>Adjusted monthly basic salary at <b>SG-<?php echo $sec_record['salary_grade']; ?>, Step-<?php echo $sec_record['sec_step']; ?></b></td>
		<td colspan=1 align="right" valign=middle width="80"><u>&#8369; <?php echo number_format($sec_record['amount'], 2); ?></u></td>
	</tr>
	<tr>
		<td colspan=1></td>
		<td colspan=3 height="50" align="left" valign=middle>2.<?php echo nbs(3) ?>Add: One (1) salary grade increase 3 months prior to compulsory retirement as <?php echo convertTitleCase($sec_record['employ_position_name']); ?>,<b> SG-<?php echo $sec_record['sec_grade']; ?>, Step-<?php echo $sec_record['sec_step']; ?></b> </td>
		<td colspan=1 align="right" valign=middle width="80"><u><?php echo number_format(($sec_record['sec_salary'] - $sec_record['amount']), 2);?></u></td>
	</tr>
	<tr>
		<td colspan=1></td>
		<td colspan=3 height="50" align="left" valign=middle>3.<?php echo nbs(3) ?>Adjusted monthly basic salary effective <b><?php echo $sec_record['employ_start_date']; ?></b></td>
		<td colspan=1 align="right" valign=middle><u>&#8369; <?php echo number_format($sec_record['sec_salary'], 2); ?></td>
	</tr>
	<tr>
		<td colspan=5 height="60" align="justify" valign=middle><?php echo nbs(10) ?>This salary increase is subject to review and post-audit, and to appropriate re-adjustment and refund if found not in order. <br><br></td>
	</tr>	
	<tr>
		<td colspan=4 height="10"></td>
	</tr>
</table>
<table class="center-85 f-size-notice">
	<tr>
		<td height="20" align="left" valign=middle width="50%"><br></td>
		<td align="center" valign=middle width="50%">Very truly yours,<br>By Authority of the Secretary of Health:</td>
	</tr>
	<tr>
		<td height="40"></td>
	</tr>
	<tr>
		<td height="20" align="left" valign=middle><br></td>
		<td align="center" valign=middle><b><?php echo strtoupper($certified_by['signatory_name']); ?></b><br><?php echo $certified_by['position_name'] ?><br><?php echo $certified_by['office_name'] ?></td>
	</tr>
	<tr>
		<td colspan=5 height="40"></td>
	</tr>
	<tr>
		<td colspan=5 height="60" align="left" valign=middle>Position Title: <b><u><?php echo convertTitleCase($sec_record['employ_position_name']); ?></u></b><br>Salary Grade: <b><u>SG-<?php echo $sec_record['salary_grade']; ?>, Step-<?php echo $sec_record['sec_step']; ?></u></b><br>Item No./Unique Item No. <b><u><?php echo $sec_record['plantilla_code']; ?></u></b></td>
	</tr>
	<tr>
		<td colspan=5 height="60" align="justify" valign=middle><i>* Three (3) months prior to Compulsory Retirement as <?php echo convertTitleCase($sec_record['employ_position_name']); ?>, effective <b><?php echo $sec_record['employ_end_date']?></b>; Date of Birth is on <b><?php echo $sec_record['birth_date']; ?></b></i></td>
	</tr>
	<tr>
		<td height="20"></td>
	</tr>
	<tr>
		<td height="20" class="font-size-sm" align="left" valign=middle>cc:<?php echo nbs(10) ?> GSIS, CSC, DBM, Cashier's Office, Acctg. Div.<br>fn:<?php echo nbs(10) ?> RA 7305</td>
	</tr>
	
</table>
<!-- ************************************************************************** -->
</body>

</html>
