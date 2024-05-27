<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
	<title>Notice of Salary Adjustment</title>
	<link rel="stylesheet" href="<?php echo base_url().PATH_CSS ?>reports.css" type="text/css" />
</head>

<body>
<?php if($sec_record): ?>
	<?php if($param != 'A'): ?>
		<?php 
		$label = '';
		$address = '';
		// echo '<pre>';
		// print_r($sec_record);
		// die();
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
		<table class="center-85 f-size-notice">
			<tr>
				<td colspan="5">
					<table class="f-size-notice">
						<tr>
							<td><?php echo nbs(6) ?><img src="<?php echo base_url().PATH_IMG ?>doh_logo.png" width=90 height=90></img></td>
							<td class="align-c f-size-notice"><?php echo nbs(8) ?>Republic of the Philippines<br><?php echo nbs(8) ?><span class="f-size-notice">Department of Health</span><br><?php echo nbs(8) ?><span class="f-size-14-notice bold">OFFICE OF THE SECRETARY</span></td>   
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td colspan=5 height="50" align="left" valign=bottom><b><center>NOTICE OF SALARY ADJUSTMENT</center></b><br><span style="float:right; padding-top:10px"></span></td>
			</tr>
			<tr>
				<td colspan=5 height="20"></td>
			</tr>
			<tr>
				<td class="align-r" colspan=5 ><?php echo date("F d, Y")?></td>
			</tr>
			<tr>
				<td colspan=5 height="40"></td>
			</tr>
			<tr>
				<!-- marvin : fix position, office & salary : start -->
				<?php

					//fix office name with parenthesis
					// $sec_record['employ_office_name'] = str_replace('( ', '(', convertTitleCase(str_replace('(', '( ', str_replace(' - DOH', '', $sec_record['employ_office_name']))));

					if(strpos($sec_record['position_name'], "(PART-TIME)"))
					{
						//fix position name
						$partTime_position = str_replace('( ', '(', convertTitleCase(str_replace('(', '( ', $sec_record['position_name'])));
						$partTime_position = str_replace('-t', '-T', $partTime_position);

						//divided by 2
						$sec_record['sec_salary'] = $sec_record['sec_salary']/2;

						// echo '<pre>';
						// print_r($sec_record);
						// print_r($partTime_position);
						// die();
					}
				?>
				<!-- marvin : fix position, office & salary : end -->
				<!--<td colspan=5 height="20" align="left" valign=middle><b><?php //echo $label?><?php //echo $sec_record['employee_name']; ?></b><br><?php //echo convertTitleCase($sec_record['position_name']); ?><br><?php //echo convertTitleCase(str_replace(' - DOH', '', $sec_record['employ_office_name'])); ?><br>This Department</td>-->
				<td colspan=5 height="20" align="left" valign=middle><b><?php echo $label?><?php echo $sec_record['employee_name']; ?></b><br><?php echo !isset($partTime_position) ? convertTitleCase($sec_record['position_name']) : $partTime_position; ?><br><?php echo str_replace('( ', '(', convertTitleCase(str_replace('(', '( ', str_replace(' - DOH', '', $sec_record['employ_office_name'])))); ?><br>This Department</td>
			</tr>
			<tr>
				<td colspan=5 height="20"></td>
			</tr>
			<tr>
				<!--<td colspan=5 height="40" align="justify" valign=middle><b><?php //echo $address?>:</b><br><br><?php //echo nbs(10) ?>Pursuant to <?php //echo $sec_record['budget_circular_number']; ?> dated <?php //echo $sec_record['budget_circular_date']; ?> implementing <?php //echo $sec_record['executive_order_number']; ?>, your salary is hereby adjusted effective <b><?php //echo $sec_record['start_date']; ?></b>, as follows:</p></td>-->
				
				<td colspan=5 height="40" align="justify" valign=middle><b><?php echo $address?>:</b><br><br><?php echo nbs(10) ?>Pursuant to <?php echo $sec_record['budget_circular_number']; ?> dated <?php echo $sec_record['budget_circular_date']; ?> implementing <?php echo $sec_record['executive_order_number']; ?> dated <b><?php echo $sec_record['execute_order_date']; ?></b>, your salary is hereby adjusted effective <b><?php echo $sec_record['start_date']; ?></b>, as follows:</p></td>
			</tr>
			<tr>
				<td colspan=5 height="15"></td>
			</tr>
		</table>
		<table class="center-85 f-size-notice">
			<tr>
				<!--<td colspan=3 height="20" align="left" valign=middle><?php //echo nbs(10) ?>1. Adjusted monthly basic salary effective <?php //echo $sec_record['start_date']; ?> <br> <?php //echo nbs(14) ?>under the new Salary Schedule SG <b><?php //echo $sec_record['sec_grade']; ?></b>, Step <b><?php //echo $sec_record['sec_step']; ?></b></td>-->
				
				<td colspan=4 height="20" align="left" valign=middle><?php echo nbs(7) ?>1. Adjusted monthly basic salary effective <?php echo $sec_record['start_date'] . ','; ?> <br> <?php echo nbs(14) ?>under the new Salary Schedule; SG <b><?php echo $sec_record['sec_grade']; ?></b>, Step <b><?php echo $sec_record['sec_step']; ?></b><br><br><br></td>
				<td colspan=1 align="right" valign=middle width="100"><b>&#8369; <?php echo number_format($sec_record['sec_salary'], 2); ?></b></td>
			</tr>
			<tr>
				<!--<td colspan=3 height="20" align="left" valign=middle><?php //echo nbs(10) ?>2. Actual monthly basic salary as of <?php //echo $first_record['employ_end_date']; ?> <br> <?php //echo nbs(14) ?>SG <b><?php //echo $first_record['first_grade']; ?></b>, Step <b><?php //echo $first_record['first_step']; ?></b></td>-->
				
				<td colspan=4 height="20" align="left" valign=middle><?php echo nbs(7) ?>2. Actual monthly basic salary as of <?php echo $first_record['employ_end_date'] . ';'; ?> <br> <?php echo nbs(14) ?>SG <b><?php echo $first_record['first_grade']; ?></b>, Step <b><?php echo $first_record['first_step']; ?></b><br><br><br></td>
				<td colspan=1 align="right" valign=middle width="100"><u><?php echo nbs(5) ?><?php echo number_format($first_record['first_salary'], 2); ?></u></td>
			</tr>
			<tr>
				<!--<td colspan=3 height="20" align="left" valign=middle><?php //echo nbs(10) ?>3. Monthly salary adjustment effective <?php //echo $sec_record['start_date']; ?> (1-2)</td>-->
				
				<td colspan=4 height="20" align="left" valign=middle><?php echo nbs(7) ?>3. Monthly salary adjustment effective <?php echo $sec_record['start_date']; ?></td>
				<td colspan=1 align="right" valign=middle><b>&#8369; <?php echo number_format(($sec_record['sec_salary'] - $first_record['first_salary']), 2);?></b></td>
			</tr>
			<tr>
				<td colspan=5 height="20"></td>
			</tr>
			<tr>
				<!--<td colspan=4 height="20" align="justify" valign=middle><?php //echo nbs(10) ?>It is understood that this adjustment is subject to review and post-audit, and to appropriate re-adjustment and refund not in order.<br><br></td>-->
				
				<td colspan=5 height="20" align="justify" valign=middle><?php echo nbs(10) ?>It is understood that this salary adjustment is subject to review and post-audit, and to appropriate re-adjustment and refund not in order.<br><br></td>
			</tr>	
			<tr>
				<td colspan=5 height="20"></td>
			</tr>
			<tr>
				<!--<td colspan=2 height="20" align="left" valign=middle><br></td>
				<td colspan=2 align="center" valign=middle>Very truly yours,<br>By authority of the Secretary of Health:</td>-->
				
				<td colspan=2 height="20" align="left" valign=middle><br></td>
				<td colspan=3 align="center" valign=middle>Very truly yours,<br>By authority of the Secretary of Health:</td>
			</tr>
			<tr>
				<td colspan=5 height="40"></td>
			</tr>
			<tr>
				<!--<td colspan=2 height="20" align="left" valign=middle><br></td>
				<td colspan=2 align="center" valign=middle><b><?php //echo strtoupper($certified_by['signatory_name']); ?></b><br><?php //echo $certified_by['position_name'] ?><br><?php //echo $certified_by['office_name'] ?></td>-->
				
				<td colspan=2 height="20" align="left" valign=middle><br></td>
				<td colspan=3 align="center" valign=middle><b><?php echo strtoupper($certified_by['signatory_name']); ?></b><br><?php echo $certified_by['position_name'] ?><br><?php echo $certified_by['office_name'] ?></td>
			</tr>
			<tr>
				<td colspan=5 height="40"></td>
			</tr>
			<tr>
				<!--<td colspan=4 height="60" align="left" valign=middle>Position Title: <b><u><?php //echo $sec_record['position_name']; ?></u></b><br>Salary Grade: <b><u><?php //echo $sec_record['sec_grade']; ?></u></b><br>Item No.: <b><u><?php //echo $sec_record['plantilla_code']; ?></u></b>, <b>FY <u><?php //echo $date; ?></u></b></td>-->
				
				<td colspan=5 height="60" align="left" valign=middle>Position Title: <b><u><?php echo $sec_record['position_name']; ?></u></b><br>Salary Grade: <b><u><?php echo $sec_record['sec_grade']; ?></u></b><br>Item No./Unique Item No. <b>FY <u><?php echo $date; ?></u></b> Personnel Services Itemization and/or Plantilla of Personnel: <b><u><?php echo $sec_record['plantilla_code']; ?></u></b>, </td>
			</tr>
			<tr>
				<td colspan=5 height="20" class="font-size-sm" align="left" valign=middle><?php echo $cc['sys_param_value']; ?></td>
			</tr>
		</table>
	<?php endif;?>

	<?php if($param == 'A'): ?>
		<?php
			// echo '<pre>';
			// print_r($sec_record);
			// die();
		// ?>
		<?php foreach ($sec_record as $second): ?>
			<?php 
			$label = '';
			$address = '';
			if($second['gender_code'] == 'F')
			{
				// $label = 'MS. ';
				//===== marvin : start : position/eligibility ======
				$sec = array(16509);
				$asec = array(16368,16506);
				$usec = array(16512,16973);
				$director = array(16526,16527,16528,16531);
				
				if(in_array($second['position_id'], $sec))
				{
					$label = 'Sec. ';
				}
				elseif(in_array($second['position_id'], $asec))
				{
					$label = 'Asec. ';
				}
				elseif(in_array($second['position_id'], $usec))
				{
					$label = 'Usec. ';
				}
				elseif(in_array($second['position_id'], $director))
				{
					$label = 'Dir. ';
				}
				elseif(in_array(2161, $second['eligibility']) OR in_array(2169, $second['eligibility']))
				{
					$label = 'Dr. ';
				}
				elseif(in_array(2116, $second['eligibility']))
				{
					$label = 'Atty. ';
				}
				elseif(in_array(2117, $second['eligibility']))
				{
					$label = 'Engr. ';
				}
				elseif(in_array(2166, $second['eligibility']))
				{
					$label = 'Arch. ';
				}
				else
				{
					$label = 'MS. ';				
				}
				//===== marvin : end : position/eligibility ======
			}
			if($second['gender_code'] == 'M')
			{
				// $label = 'MR. ';
				//===== marvin : start : position/eligibility ======
				$sec = array(16509);
				$asec = array(16368,16506);
				$usec = array(16512,16973);
				$director = array(16526,16527,16528,16531);
				
				if(in_array($second['position_id'], $sec))
				{
					$label = 'Sec. ';
				}
				elseif(in_array($second['position_id'], $asec))
				{
					$label = 'Asec. ';
				}
				elseif(in_array($second['position_id'], $usec))
				{
					$label = 'Usec. ';
				}
				elseif(in_array($second['position_id'], $director))
				{
					$label = 'Dir. ';
				}
				elseif(in_array(2161, $second['eligibility']) OR in_array(2169, $second['eligibility']))
				{
					$label = 'Dr. ';
				}
				elseif(in_array(2116, $second['eligibility']))
				{
					$label = 'Atty. ';
				}
				elseif(in_array(2117, $second['eligibility']))
				{
					$label = 'Engr. ';
				}
				elseif(in_array(2166, $second['eligibility']))
				{
					$label = 'Arch. ';
				}
				else
				{
					$label = 'MR. ';				
				}
				//===== marvin : end : position/eligibility ======
			}
			if($second['gender_code'] == 'M')
			{
				$address = 'Sir';
			}
			if($second['gender_code'] == 'F')
			{
				$address = 'Madam';
			}
		?>
			<?php foreach ($first_record as $first): ?>
				<?php if($second['employee_id'] == $first['employee_id']): ?>
					<?php
						$grade 		= $first['first_grade'];
						$step 		= $first['first_step'];
						$salary 	= $first['first_salary'];
						$emp_date 	= $first['employ_end_date'];
					?>
				<?php endif;?>
			<?php endforeach;?>	
			
			<table class="center-85 f-size-notice">
				<tr>
					<td colspan="5">
						<table class="f-size-notice">
							<tr>
								<td><?php echo nbs(6) ?><img src="<?php echo base_url().PATH_IMG ?>doh_logo.png" width=90 height=90></img></td>
								<td class="align-c f-size-notice"><?php echo nbs(8) ?>Republic of the Philippines<br><?php echo nbs(8) ?><span class="f-size-notice">Department of Health</span><br><?php echo nbs(8) ?><span class="f-size-14-notice bold">OFFICE OF THE SECRETARY</span></td>   
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td colspan=5 height="50" align="left" valign=bottom><b><center>NOTICE OF SALARY ADJUSTMENT</center></b><br><span style="float:right; padding-top:10px"></span></td>
				</tr>
				<tr>
					<td colspan=5 height="20"></td>
				</tr>
				<tr>
					<td class="align-r" colspan=5 ><?php echo date("F d, Y")?></td>
				</tr>
				<tr>
					<td colspan=5 height="40"></td>
				</tr>
				<tr>
					<!-- marvin : fix position, office & salary : start -->
					<?php

					//fix office name with parenthesis
					// $sec_record['employ_office_name'] = str_replace('( ', '(', convertTitleCase(str_replace('(', '( ', str_replace(' - DOH', '', $sec_record['employ_office_name']))));

					if(strpos($second['position_name'], "(PART-TIME)"))
					{
						//fix position name
						$partTime_position = str_replace('( ', '(', convertTitleCase(str_replace('(', '( ', $second['position_name'])));
						$partTime_position = str_replace('-t', '-T', $partTime_position);

						//divided by 2
						$second['sec_salary'] = $second['sec_salary']/2;

						// echo '<pre>';
						// print_r($sec_record);
						// print_r($partTime_position);
						// die();
					}
					else
					{
						unset($partTime_position);
					}
					?>
					<!-- marvin : fix position, office & salary : end -->
					<!--<td colspan=5 height="20" align="left" valign=middle><b><?php //echo $label?><?php //echo $second['employee_name']; ?></b><br><?php //echo convertTitleCase($second['position_name']); ?><br><?php //echo convertTitleCase(str_replace(' - DOH', '', $second['employ_office_name'])); ?><br>This Department</td>-->
					<td colspan=5 height="20" align="left" valign=middle><b><?php echo $label?><?php echo $second['employee_name']; ?></b><br><?php echo !isset($partTime_position) ? convertTitleCase($second['position_name']) : $partTime_position; ?><br><?php echo str_replace('( ', '(', convertTitleCase(str_replace('(', '( ', str_replace(' - DOH', '', $second['employ_office_name'])))); ?><br>This Department</td>
				</tr>
				<tr>
					<td colspan=5 height="20"></td>
				</tr>
				<tr>
					<!--<td colspan=5 height="40" align="justify" valign=middle><b><?php //echo $address?>:</b><br><br><?php //echo nbs(10) ?>Pursuant to <?php //echo $second['budget_circular_number']; ?> dated <?php //echo $second['budget_circular_date']; ?> implementing <?php //echo $second['executive_order_number']; ?>, your salary is hereby adjusted effective <b><?php //echo $second['start_date']; ?></b>, as follows:</p></td>-->
					
					<td colspan=5 height="40" align="justify" valign=middle><b><?php echo $address?>:</b><br><br><?php echo nbs(10) ?>Pursuant to <?php echo $second['budget_circular_number']; ?> dated <?php echo $second['budget_circular_date']; ?> implementing <?php echo $second['executive_order_number']; ?> dated <b><?php echo $second['execute_order_date']; ?></b>, your salary is hereby adjusted effective <b><?php echo $second['start_date']; ?></b>, as follows:</p></td>
				</tr>
					<td colspan=5 height="15"></td>
				</tr>
			</table>
			<table class="center-85 f-size-notice">
				<tr>
					<!--<td colspan=4 height="20" align="left" valign=middle style="padding-top: 20px"><?php //echo nbs(10) ?>1. Adjusted monthly basic salary effective <?php //echo $second['start_date']; ?> <br> <?php //echo nbs(14) ?>under the New Salary Schedule SG-<b><?php //echo $second['sec_grade']; ?></b>, Step <b><?php //echo $second['sec_step']; ?></b></td>
					<td colspan=1 align="right" valign=middle width="100"><b>&#8369; <?php //echo number_format($second['sec_salary'], 2); ?></b></td>-->
					
					<td colspan=4 height="20" align="left" valign=middle style="padding-top: 20px"><?php echo nbs(7) ?>1. Adjusted monthly basic salary effective <?php echo $second['start_date'] . ','; ?> <br> <?php echo nbs(14) ?>under the new Salary Schedule; SG <b><?php echo $second['sec_grade']; ?></b>, Step <b><?php echo $second['sec_step']; ?></b><br><br><br></td>
					<td colspan=1 align="right" valign=middle width="100"><b>&#8369; <?php echo number_format($second['sec_salary'], 2); ?></b></td>
				</tr>
				<tr>
					<!--<td colspan=4 height="20" align="left" valign=middle><?php //echo nbs(10) ?>2. Actual monthly basic salary as of <?php //echo $emp_date ?> <br> <?php //echo nbs(13) ?> SG-<b><?php //echo $grade; ?></b>, Step <b><?php //echo $step; ?></b></td>
					<td colspan=1 align="right" valign=middle width="100"><u><?php //echo nbs(5) ?><?php //echo number_format($salary, 2); ?></u></td>-->
					
					<td colspan=4 height="20" align="left" valign=middle><?php echo nbs(7) ?>2. Actual monthly basic salary as of <?php echo $emp_date . ';'; ?> <br> <?php echo nbs(14) ?>SG <b><?php echo $grade; ?></b>, Step <b><?php echo $step; ?></b><br><br><br></td>
					<td colspan=1 align="right" valign=middle width="100"><u><?php echo nbs(5) ?><?php echo number_format($salary, 2); ?></u></td>
				</tr>
				<tr>
					<!--<td colspan=4 height="20" align="left" valign=middle><?php //echo nbs(10) ?>3. Monthly salary adjustment effective <?php //echo $second['start_date']; ?> (1-2)</td>-->
					
					<td colspan=4 height="20" align="left" valign=middle><?php echo nbs(7) ?>3. Monthly salary adjustment effective <?php echo $second['start_date']; ?></td>
					<td colspan=1 align="right" valign=middle><b>&#8369; <?php echo number_format(($second['sec_salary'] - $salary), 2);?></b></td>
				</tr>
				<tr>
					<td colspan=5 height="20"></td>
				</tr>
				<tr>
					<!--<td colspan=5 height="20" align="justify" valign=middle><?php echo nbs(10) ?>It is understood that this adjustment is subject to review and post-audit, and to appropriate re-adjustment and refund not in order.<br><br></td>-->
					
					<td colspan=5 height="20" align="justify" valign=middle><?php echo nbs(10) ?>It is understood that this salary adjustment is subject to review and post-audit, and to appropriate re-adjustment and refund not in order.<br><br></td>
				</tr>	
				<tr>
					<td colspan=5 height="20"></td>
				</tr>
				<tr>
					<td colspan=2 height="20" align="left" valign=middle><br></td>
					<td colspan=3 align="center" valign=middle>Very truly yours,<br>By authority of the Secretary of Health:</td>
				</tr>
				<tr>
					<td colspan=5 height="40"></td>
				</tr>
				<tr>
					<td colspan=2 height="20" align="left" valign=middle><br></td>
					<td colspan=3 align="center" valign=middle><b><?php echo strtoupper($certified_by['signatory_name']); ?></b><br><?php echo $certified_by['position_name'] ?><br><?php echo $certified_by['office_name'] ?></td>
				</tr>
				<tr>
					<td colspan=5 height="40"></td>
				</tr>
				<tr>
					<!--<td colspan=5 height="60" align="left" valign=middle>Posidtion Title: <b><u><?php //echo $second['position_name']; ?></u></b><br>Salary Grade: <b><u><?php //echo $second['sec_grade']; ?></u></b><br>Item No.: <b><u><?php //echo $second['plantilla_code']; ?></u></b>, <b>FY <u><?php //echo $date; ?></u></b></td>-->
					
					<td colspan=5 height="60" align="left" valign=middle>Position Title: <b><u><?php echo $second['position_name']; ?></u></b><br>Salary Grade: <b><u><?php echo $second['sec_grade']; ?></u></b><br>Item No./Unique Item No. <b>FY <u><?php echo $date; ?></u></b> Personnel Services Itemization and/or Plantilla of Personnel: <b><u><?php echo $second['plantilla_code']; ?></u></b>, </td>
				</tr>
				<tr>
					<td colspan=5 height="20" class="font-size-sm" align="left" valign=middle><?php echo $cc['sys_param_value']; ?></td>
				</tr>
			</table>
		<?php endforeach;?>	
	<?php endif;?>
<?php else: ?>
	<table class="center-85 f-size-notice">
		<tr>
			<td colspan="4">
				<table class="f-size-notice">
					<tr>
						<td><img src="<?php echo base_url().PATH_IMG ?>doh_logo.png" width=90 height=90></img></td>
						<td class="align-c f-size-notice"><?php echo nbs(28) ?>Republic of the Philippines<br><?php echo nbs(28) ?><span class="f-size-notice">Department of Health</span><br><?php echo nbs(28) ?><span class="f-size-14-notice bold">OFFICE OF THE SECRETARY</span></td>   
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td colspan=4 height="50" align="left" valign=bottom><b><u><center>NOTICE OF SALARY ADJUSTMENT</center></u></b><br><span style="float:right; padding-top:10px"></span></td>
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
			<td colspan=4 height="20" align="left" valign=middle><u><?php echo nbs(30); ?></u><br><u><?php echo nbs(30); ?></u><br><u><?php echo nbs(30); ?></u><br>This Department</td>
		</tr>
		<tr>
			<td colspan=4 height="20"></td>
		</tr>
		<tr>
			<td colspan=4 height="40" align="justify" valign=middle><u><?php echo nbs(30); ?>:</u><br><br><?php echo nbs(10) ?>Pursuant to <?php echo $sec_record['budget_circular_number']; ?> dated <?php echo $sec_record['budget_circular_date']; ?> implementing <?php echo $sec_record['executive_order_number']; ?>, your salary is hereby adjusted effective <?php echo $sec_record['start_date']; ?>, as follows:</p></td>
		</tr>
		<tr>
			<td colspan=4 height="15"></td>
		</tr>
	</table>
	<table class="center-85 f-size-notice">
		<tr>
			<td colspan=3 height="20" align="left" valign=middle><?php echo nbs(20) ?>1. Adjusted monthly basic salary effective <u><?php echo nbs(30); ?></u> <br> <?php echo nbs(25) ?>under the New Salary Schedule SG-<u><?php echo nbs(5); ?></u>, Step <u><?php echo nbs(5); ?></u></td>
			<td colspan=1 align="right" valign=middle width="100">&#8369;<u><?php echo nbs(20); ?></u></td>
		</tr>
		<tr>
			<td colspan=3 height="20" align="left" valign=middle><?php echo nbs(20) ?>2. Actual monthly basic salary as of <u><?php echo nbs(30); ?></u> <br> <?php echo nbs(25) ?>SG-<u><?php echo nbs(5); ?></u>, Step <u><?php echo nbs(5); ?></u></td>
			<td colspan=1 align="right" valign=middle width="100"><u><?php echo nbs(20); ?></u></td>
		</tr>
		<tr>
			<td colspan=3 height="20" align="left" valign=middle><?php echo nbs(20) ?>3. Monthly salary adjustment effective <u><?php echo nbs(30); ?></u> (1-2)</td>
			<td colspan=1 align="right" valign=middle><b>&#8369; </b><u><?php echo nbs(20); ?></u></td>
		</tr>
		<tr>
			<td colspan=4 height="20"></td>
		</tr>
		<tr>
			<td colspan=4 height="20" align="justify" valign=middle><?php echo nbs(10) ?>It is understood that this adjustment is subject to review and post-audit, and to appropriate re-adjustment and refund not in order.<br><br></td>
		</tr>	
		<tr>
			<td colspan=4 height="20"></td>
		</tr>
		<tr>
			<td colspan=2 height="20" align="left" valign=middle><br></td>
			<td colspan=2 align="center" valign=middle>Very truly yours,<br>By authority of the Secretary of Health:</td>
		</tr>
		<tr>
			<td colspan=4 height="40"></td>
		</tr>
		<tr>
			<td colspan=2 height="20" align="left" valign=middle><br></td>
			<td colspan=2 align="center" valign=middle><b><?php echo $certified_by['signatory_name']; ?></b><br><?php echo $certified_by['position_name'] ?><br><?php echo $certified_by['office_name'] ?></td>
		</tr>
		<tr>
			<td colspan=4 height="40"></td>
		</tr>
		<tr>
			<td colspan=4 height="60" align="left" valign=middle>Position Title: <b><u><?php echo $sec_record['position_name']; ?></u></b><br>Salary Grade: <b><u><?php echo $sec_record['sec_grade']; ?></u></b><br>Item No.: <b><u><?php echo $sec_record['plantilla_code']; ?></u></b>, <b>FY <u><?php echo $date; ?></u></b></td>
		</tr>
		<tr>
			<td colspan=4 height="20" class="font-size-sm" align="left" valign=middle><?php echo $cc['sys_param_value']; ?></td>
		</tr>
	</table>
<?php endif;?>
<!-- ************************************************************************** -->
</body>

</html>
