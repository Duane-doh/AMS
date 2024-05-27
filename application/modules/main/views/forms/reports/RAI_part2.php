
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<html>
<head>
	
	<title>Report on Appointment Part II</title>
	<link rel="stylesheet" href="<?php echo base_url().PATH_CSS ?>reports.css" type="text/css" />
</head>
<style type="text/css">
	table{
		
		font-family: Times New Roman;
/*		page-break-inside:auto;*/
	}
	.tbl-padding {
		padding-left: 5px;
		padding-right: 5px;"
	}
	.tbl-page-break{
		page-break-after: always;
	}
	.pg-break{
		page-break-inside:avoid;
	}
	.ul-padding {
		padding-left: 30px;
		font-size: x-small;
	}
	tr {
	}
	</style>

<body>
<table class="f-size-12" >
	<tbody>
		<tr>
			<td class="bold" align="center" width="740px"><?php echo nbs(5)?><span style="font-size: 15;">APPOINTMENT PROCESSING CHECKLIST</span><br> [NGAs, Constitutional Commissions, SUCs/LCUs, GOCCs/GFIs]</td>
			<td>&nbsp;</td>
		</tr>
	</tbody>
</table>
<table class="table-max cont-5 f-size-12" border="0" >
	<tr>
		<td height="15"></td>
	</tr>
	<tr>
		<td class="td-border-top td-border-bottom td-border-right td-border-left bold" height="10" width="150">Name</td>
		<td class="td-border-top td-border-bottom td-border-right td-border-left bold" colspan="5"><?php echo $emp_record['employee_name'] ?></td>
	</tr>
	<tr>
		<td class="td-border-top td-border-bottom td-border-right td-border-left bold" height="10" width="150">Date of Birth</td>
		<td class="td-border-top td-border-bottom td-border-right td-border-left bold" colspan="5"><?php echo $record['birth_date'] ?></td>
	</tr>
	<tr>
		<td class="td-border-top td-border-bottom td-border-right td-border-left bold" height="10" width="150">Position Title</td>
		<td class="td-border-top td-border-bottom td-border-right td-border-left bold" colspan="3" width="350"><?php echo $emp_record['position_name'] ?></td>
		<td class="td-border-top td-border-bottom td-border-right td-border-left bold">SG/Step</td>
		<td class="td-border-top td-border-bottom td-border-right td-border-left bold"><?php echo $emp_record['employ_salary_grade'] ?>/<?php echo $record['employ_salary_step'] ?></td>
	</tr>
	<tr>
		<td class="td-border-top td-border-bottom td-border-right td-border-left bold" height="10" width="150">Agency</td>
		<td class="td-border-top td-border-bottom td-border-right td-border-left bold" colspan="5">DEPARTMENT OF HEALTH</td>
	</tr>
	<tr>
		<td class="td-border-top td-border-bottom td-border-right td-border-left bold" height="10" width="150">Annual Compensation</td>
		<td class="td-border-top td-border-bottom td-border-right td-border-left bold" colspan="5"> 
			<?php  
			$year = 12;
			$monthly_salary = $emp_record['employ_monthly_salary'];
			$annual_comp = $year * $monthly_salary;
			
			echo $annual_comp = "PHP " . number_format($annual_comp, 2, ".", ",");


		?></td>


	</tr>
	<tr>
		<td class="td-border-top td-border-bottom td-border-right td-border-left bold" height="10" width="150">Item Number</td>
		<td class="td-border-top td-border-bottom td-border-right td-border-left bold" colspan="5">
			<?php //echo $emp_record['plantilla_code'] ?>
			<?php 
				$emp_status = strtoupper($emp_record['employment_status_name']);
				if($emp_status == "CONTRACTUAL"){
					echo "N/A";}
				else{echo $emp_record['plantilla_code'];}
			?>
			</td>
	</tr>
	<tr>
		<td height="15"></td>
	</tr>

	<!-- <tr>
		<td colspan=4 height="20" align="left" valign=bottom><b>Office/Bureau:<u> <?php echo $office_name ?></b></u></td>
		<td colspan=4 align="left" valign=top><b>Address:<b>San Lazaro Compound, Sta. Cruz, Manila</b></td>
		<td colspan=3 align="right" valign=top>Sector : CSCFO In-Charge: ____________________</td>
	</tr>
	<tr>
		<td colspan=8 height="24" align="left" valign=top><b>PART II </b>- Pertinent data on appointees</td>
		<td colspan=3 align="left" valign=top>Important: Please accomplish this form completely</td>
	</tr> -->

</table>



<!-- first page -->

<table class="table-max f-size-12 tbl-page-break">
	<!-- <thead> -->
		<tr>
			<td class="bold" colspan="6"> Qualification Standards</td>
		</tr>
		<tr>
			<!-- <td class="td-border-top td-border-bottom td-border-right td-border-left bold" width="150" rowspan="2">Criteria</td> -->
			<td class="td-border-top td-border-bottom td-border-right td-border-left bold" rowspan=2 align="center" valign=middle width="15%">Criteria</td>
			<td class="td-border-top td-border-bottom td-border-right td-border-left bold" rowspan=2 align="center" valign=middle width="20%">Requirement</td>
			<td class="td-border-top td-border-bottom td-border-right td-border-left bold" rowspan=2 align="center" valign=middle width="30%">Appointee's Qualification<br> (Provide Specific<br> Details)</td>
			<td class="td-border-top td-border-bottom td-border-right td-border-left bold" colspan=2 align="center" valign=middle width="15%">QS MET</td>
			<td class="td-border-top td-border-bottom td-border-right td-border-left bold" align="center" valign=middle width="10%">Remarks	</td>
		</tr>
		<tr>
			<td class="td-border-top td-border-bottom td-border-right td-border-left bold" align="center" width="5%" valign=middle><b>Yes</b></td>
			<td class="td-border-top td-border-bottom td-border-right td-border-left bold" align="center" width="5%" valign=middle><b>No</b></td>
			<td class="td-border-top td-border-bottom td-border-right td-border-left bold" width="10%"></td>
		</tr>
		<tr>
	<!-- </thead> -->

	<tbody>
		<tr>
			<td class="td-border-top td-border-bottom td-border-right td-border-left" align="left" valign=top width="15%"><?php echo nbs(3)?>Education</td>
			<td class="td-border-top td-border-bottom td-border-right td-border-left tbl-padding" align="left" valign=top  width="20%">
				<?php
					$educ_req = strtolower($record['req_education']);
					if (empty($educ_req)) {
						echo NOT_APPLICABLE;
					}else{
						echo ucfirst($educ_req);
					}
				?>
			</td>
			<td class="td-border-top td-border-bottom td-border-right td-border-left" align="left" valign=top width="30%" ><?php echo (!EMPTY($record['degree_name'])) ? '* ' . strtoupper($record['degree_name']) : NOT_APPLICABLE; ?></td>
			<td class="td-border-top td-border-bottom td-border-right td-border-left" align="left" valign=middle width="5%" ></td>
			<td class="td-border-top td-border-bottom td-border-right td-border-left" align="left" valign=middle width="5%" ></td>
			<td class="td-border-top td-border-bottom td-border-right td-border-left" align="left" valign=middle width="10%" ></td>
		</tr>
	</tbody>
	<tbody>
		<tr>
			<td class="td-border-top  td-border-right td-border-left" align="left" valign=top><?php echo nbs(3)?>Experience</td>
			<td class="td-border-top  td-border-right td-border-left tbl-padding" align="left" valign=top>
				<?php
					$exp_req = strtolower($record['req_experience']);
					if (empty($exp_req)) {
						echo NOT_APPLICABLE;
					}else{
						echo ucfirst($exp_req);
					}
				?>
			</td>
			<td class="td-border-top  td-border-right td-border-left" align="left" valign=top >
				<?php if (empty($wexp_record)) echo NOT_APPLICABLE; 
				
				else { 
					$merged_wexp_record = array();
	                foreach ($wexp_record as $val) {
	                    $temp = array(
	                        'employ_position_name' => $val['employ_position_name'],
	                        'employment_status_name' => $val['employment_status_name'],
	                        'employ_office_name' => $val['employ_office_name'],
	                        'start_date' => $val['start_date'],
	                        'end_date' => $val['end_date']
	                    );
						$merge_key = $val['employ_position_name'] . $val['employment_status_name'] . $val['employ_office_name'];
						if (isset($merged_wexp_record[$merge_key])) {
							if (strtotime($merged_wexp_record[$merge_key]['start_date']) > strtotime($val['start_date'])) {
								$merged_wexp_record[$merge_key]['start_date'] = $val['start_date'];
							}
							
							if ($merged_wexp_record[$merge_key]['end_date'] === 'PRESENT') {
								$merged_wexp_record[$merge_key]['end_date'] = date("Y-m-d");
							}elseif (strtotime($merged_wexp_record[$merge_key]['end_date']) < strtotime($val['end_date'])) {
								$merged_wexp_record[$merge_key]['end_date'] = $val['end_date'];
							}
	                    } else {
	                        $merged_wexp_record[$merge_key] = $temp;
	                    }
	                }
					

					$first = current($merged_wexp_record);

					$start_date = date_create($first['start_date']);
					$end_date = date_create($first['end_date']);
		
					if (strtotime($first['end_date']) == strtotime(date('Y-m-d'))) {
						$end_date_formatted = 'PRESENT';
					} elseif (empty(strtotime($first['end_date']))) {
						$end_date_formatted = 'PRESENT';
					} else {
						$end_date_formatted = date_format(date_create($first['end_date']), 'M d, Y');
					}

					echo '* ' . $first['employ_position_name'] . '<br>'
						.'('. $first['employment_status_name'] .')' . '<br>'
						.strtoupper($first['employ_office_name']) . '<br>'
						.date_format($start_date, 'M d, Y') . ' - '
						.$end_date_formatted . '<br><br><br>';
					} 
					?>
				
			</td>
			<td class="td-border-top  td-border-right td-border-left" align="left" valign=middle ></td>
			<td class="td-border-top  td-border-right td-border-left" align="left" valign=middle ></td>	
			<td class="td-border-top  td-border-right td-border-left" align="left" valign=middle >
				<?php
						$merged_wexp_record = array();
						foreach ($wexp_record as $val) {
							$temp = array(
								'employ_position_name' => $val['employ_position_name'],
								'employment_status_name' => $val['employment_status_name'],
								'employ_office_name' => $val['employ_office_name'],
								'start_date' => $val['start_date'],
								'end_date' => $val['end_date']
							);
							$merge_key = $val['employ_position_name'] . $val['employment_status_name'] . $val['employ_office_name'];
							if (isset($merged_wexp_record[$merge_key])) {
								if (strtotime($merged_wexp_record[$merge_key]['start_date']) > strtotime($val['start_date'])) {
									$merged_wexp_record[$merge_key]['start_date'] = $val['start_date'];
								}
								if (strtotime($merged_wexp_record[$merge_key]['end_date']) < strtotime($val['end_date'])) {
									$merged_wexp_record[$merge_key]['end_date'] = $val['end_date'];
								}
							} else {
								$merged_wexp_record[$merge_key] = $temp;
							}
						}
						$borderClass = 'td-border-bottom td-border-right td-border-left';
						for ($i=1; $i < count($merged_wexp_record); $i++) { 
							$key = array_keys( $merged_wexp_record )[$i];
							$val = $merged_wexp_record[$key];

							$start_date = date_create($val['start_date']);
							$end_date = date_create($val['end_date']);
							$borderClass = ( $i == count($merged_wexp_record)-1 ) ? 'td-border-bottom td-border-right td-border-left' : 'td-border-right td-border-left';
							echo 
							'<tr>
							<td class="td- td-border-right td-border-left ' . $borderClass . '" align="left" valign=middle ></td>
							<td class="td- td-border-right td-border-left ' . $borderClass . '" align="left" valign=middle ></td>
							<td class="td- td-border-right td-border-left ' . $borderClass . '" align="left" valign="middle">
							
							* ' . $val['employ_position_name'] . '<br>'
							.'('. $val['employment_status_name'] .')' . '<br>'
							.strtoupper($val['employ_office_name']) . '<br>'
							.date_format($start_date, 'M d, Y') . ' - '
							.date_format($end_date, 'M d, Y') . '<br><br><br>

							</td>
							<td class="td- td-border-right td-border-left ' . $borderClass . '" align="left" valign=middle ></td>
							<td class="td- td-border-right td-border-left ' . $borderClass . '" align="left" valign=middle ></td>
							<td class="td-border-right td-border-left ' . $borderClass . '" align="left" valign=middle ></td>
							</tr>';
						}
			?>
			</td>
		</tr>
	</tbody>
	<tbody>
		<tr>
			<td class="td-border-top  td-border-right td-border-left" align="left" valign=top><?php echo nbs(3)?>Training</td>
			<td class="td-border-top  td-border-right td-border-left tbl-padding" align="left" valign=top>
				<?php
					$training_req = strtolower($record['req_training']);



					if (empty($training_req)) {
						echo NOT_APPLICABLE;
					}else{
						echo ucfirst($training_req);
					}
				?>
			</td>
			<td class="td-border-top  td-border-right td-border-left" align="left" valign=top >
				<?php
					if (!empty($record['rel_training'])) {
						$trainingArray = explode('* ', $record['rel_training']);
						echo '*'. strtoupper($trainingArray[0]) . '<br>';
					} else {
						echo NOT_APPLICABLE;
					}
				?>
			</td>
			<td class="td-border-top  td-border-right td-border-left" align="left" valign=middle ></td>
			<td class="td-border-top  td-border-right td-border-left" align="left" valign=middle ></td>	
			<td class="td-border-top  td-border-right td-border-left" align="left" valign=middle >
			<?php
				if (!empty($record['rel_training'])) {
					$trainingArray = explode('* ', $record['rel_training']);
					$lastIndex = count($trainingArray) - 1;
					foreach ($trainingArray as $index => $value) {
						if ($index === 0) continue; // Skip the first value
						$borderClass = $index === $lastIndex ? 'td-border-bottom' : '';
						echo 
						'<tr>
						<td class="td- td-border-right td-border-left ' . $borderClass . '" align="left" valign=middle ></td>
						<td class="td- td-border-right td-border-left ' . $borderClass . '" align="left" valign=middle ></td>
						<td class="td- td-border-right td-border-left ' . $borderClass . '" align="left" valign="middle">* ' . strtoupper($value) . ' <br></td>
						<td class="td- td-border-right td-border-left ' . $borderClass . '" align="left" valign=middle ></td>
						<td class="td- td-border-right td-border-left ' . $borderClass . '" align="left" valign=middle ></td>
						<td class="td-border-right td-border-left ' . $borderClass . '" align="left" valign=middle ></td>
						</tr>';
					}
				}
			?>
			</td>
		</tr>
	</tbody>
	<tbody>
		<tr>
			<td class="td-border-top td-border-bottom td-border-right td-border-left" align="left" valign=top width="120"><?php echo nbs(3)?>Eligibility</td>
			<td class="td-border-top td-border-bottom td-border-right td-border-left tbl-padding" align="left" valign=top>
				<?php
					$eligibility_req = strtolower($record['req_eligibility']);
					if (empty($eligibility_req)) {
						echo NOT_APPLICABLE;
					}else{
						echo ucfirst($eligibility_req);
					}
				?>
			</td>
				<td class="td-border-top td-border-bottom td-border-right td-border-left" align="left" valign=top ><?php echo (!EMPTY($record['eligibility_name'])) ? '* ' . strtoupper($record['eligibility_name']) : NOT_APPLICABLE; ?></td>



			
			<td class="td-border-top td-border-bottom td-border-right td-border-left" align="left" valign=middle ></td>
			<td class="td-border-top td-border-bottom td-border-right td-border-left" align="left" valign=middle ></td>
			<td class="td-border-top td-border-bottom td-border-right td-border-left" align="left" valign=middle ></td>
		</tr>
	</tbody>
	<tbody>
		<tr>
			<td class="td-border-top td-border-bottom td-border-right td-border-left" align="left" valign=middle width="120" ><?php echo nbs(3)?>Others, if <?php echo nbs(3)?>applicable (e.g. <?php echo nbs(3)?>Age, Term of <?php echo nbs(3)?>Office)</td>
			<td class="td-border-top td-border-bottom td-border-right td-border-left" align="left" valign=middle ></td>
			<td class="td-border-top td-border-bottom td-border-right td-border-left" align="left" valign=middle ></td>
			<td class="td-border-top td-border-bottom td-border-right td-border-left" align="left" valign=middle ></td>
			<td class="td-border-top td-border-bottom td-border-right td-border-left" align="left" valign=middle ></td>
			<td class="td-border-top td-border-bottom td-border-right td-border-left" align="left" valign=middle ></td>
		</tr>
	</tbody>
</table>
</div>
<!-- 2nd page -->
<table class="table-max f-size-12" style="table-layout:fixed;" >
	<thead>
		<tr>
			<td class="bold f-size-13" colspan="6"> Common Requirements for Regular Appointments</td>
		</tr>
		<tr>
			<td class="td-border-top td-border-bottom td-border-right td-border-left bold tbl-padding f-size-13" colspan="3" align="center" height="38" width="130"> Requirement</td>
			<td class="td-border-top td-border-bottom td-border-right td-border-left bold tbl-padding f-size-13" colspan="3" align="center"> Details/Compliance</td>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td class="td-border-top td-border-bottom td-border-right td-border-left tbl-padding" colspan="3" align="left" height="38">CS Form 33-A (revised 2018) in triplicate copies</td>
			<td class="td-border-top td-border-bottom td-border-right td-border-left tbl-padding bold f-size-20" colspan="3" align="center"></td>
		</tr>
		<tr>
			<td class="td-border-top td-border-bottom td-border-right td-border-left tbl-padding" colspan="3" align="left" height="38">Employment Status</td>
			<td class="td-border-top td-border-bottom td-border-right td-border-left tbl-padding" colspan="3" align="left"><?php echo $emp_record['employment_status_name'] ?></td>
		</tr>
		<tr>
			<td class="td-border-top td-border-bottom td-border-right td-border-left tbl-padding" colspan="3" align="left" height="38">Nature of Appointment</td>
			<td class="td-border-top td-border-bottom td-border-right td-border-left tbl-padding" colspan="3" align="left"><?php echo $emp_record['personnel_movement_name'] ?></td>
		</tr>
		<tr>
			<td class="td-border-top td-border-bottom td-border-right td-border-left tbl-padding" colspan="3" align="left" height="50">Appointing Authority</td>
			<td class="td-border-top td-border-bottom td-border-right td-border-left tbl-padding" colspan="3" align="left">
				<?php echo strtoupper($reviewed_by['signatory_name']) ; ?>
				<br><?php echo $reviewed_by['position_name'] ?>
				<br> <?php echo $reviewed_by['office_name']?>
			</td>
		</tr>
		<tr>
			<td class="td-border-top td-border-bottom td-border-right td-border-left tbl-padding" colspan="3" align="left" height="38">Date of Signing</td>
			<td class="td-border-top td-border-bottom td-border-right td-border-left tbl-padding" colspan="3" align="left"><?php echo !EMPTY($emp_record['signing_date']) ? strtoupper($emp_record['signing_date']) : N_A?></td>
		</tr>
		<tr>
			<td class="td-border-top td-border-bottom td-border-right td-border-left tbl-padding" colspan="3" align="left" height="38">Date of Publication/Posting of Vacant Position</td>
			<td class="td-border-top td-border-bottom td-border-right td-border-left tbl-padding" colspan="3" align="left">
				<?php echo !EMPTY($emp_record['publication_date']) ? strtoupper($emp_record['publication_date']) : N_A?> 
				to 
				<?php echo !EMPTY($emp_record['publication_date_to']) ? strtoupper($emp_record['publication_date_to']) : N_A?>
			</td>
		</tr>
		<tr>
			<td class="td-border-top td-border-bottom td-border-right td-border-left tbl-padding" colspan="3" align="left" height="38">Certification by PSB Chairman (at the back of appointment) or a copy of the proceedings of PSB's Deliberation</td>
			<td class="td-border-top td-border-bottom td-border-right td-border-left tbl-padding bold f-size-20" colspan="3" align="center"></td>
		</tr>
		<tr>
			<td class="td-border-top td-border-bottom td-border-right td-border-left tbl-padding" colspan="3" align="left" height="38">Photocopy of Notice of Vacancy</td>
			<td class="td-border-top td-border-bottom td-border-right td-border-left tbl-padding bold f-size-20" colspan="3" align="center"></td>
		</tr>
		<tr>
			<td class="td-border-top td-border-bottom td-border-right td-border-left tbl-padding" colspan="3" align="left" height="38">Personal Data Sheet with Work Experience Sheet (Revised 2017)</td>
			<td class="td-border-top td-border-bottom td-border-right td-border-left tbl-padding bold f-size-20" colspan="3" align="center"></td>
		</tr>
		<tr>
			<td class="td-border-top td-border-bottom td-border-right td-border-left tbl-padding" colspan="3" align="left" height="30">Position Description Form</td>
			<td class="td-border-top td-border-bottom td-border-right td-border-left tbl-padding bold f-size-20" colspan="3" align="center"></td>
		</tr>
		<tr>
			<td class="td-border-top td-border-bottom td-border-right td-border-left tbl-padding" colspan="3" align="left" height="38"><b>Certificate of Eligibility</b>/ RA 1080 eligibility with License <br>(Authenticated Copy)</td>
			<td class="td-border-top td-border-bottom td-border-right td-border-left tbl-padding bold f-size-20" colspan="3" align="center"></td>
		</tr>
		<tr>
			<td class="td-border-top td-border-bottom td-border-right td-border-left tbl-padding" colspan="3" align="left" height="38">Comparative Assessment Report and Resolution</td>
			<td class="td-border-top td-border-bottom td-border-right td-border-left tbl-padding bold f-size-20" colspan="3" align="center"></td>
		</tr>
	</tbody>
</table>


<table class="table-max f-size-12"  style="page-break-after: always;">
		<tr>
			<td height="40"></td>
		</tr>
		<tr>
			<td class="bold f-size-13" colspan="6">Additonal Requirements in Specific Cases</td>
		</tr>
	<thead>
		<tr>
			<td class="td-border-top td-border-bottom td-border-right td-border-left bold tbl-padding f-size-13" colspan="3" align="center" height="25" width="130"> Requirement</td>
			<td class="td-border-top td-border-bottom td-border-right td-border-left bold tbl-padding f-size-13" colspan="3" align="center"> Details/Compliance</td>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td class="td-border-top td-border-bottom td-border-right td-border-left tbl-padding" colspan="3" align="justify" height="80">Erasures/alterations on the appointment and other supporting <br>documents (Changes duly initialled by authorized officials and <br>accompanied by a communication authenticating changes made)</td>
			<td class="td-border-top td-border-bottom td-border-right td-border-left tbl-padding" colspan="3" align="center"></td>
		</tr>
		<tr>
			<td class="td-border-top td-border-bottom td-border-right td-border-left bold tbl-padding f-size-13" colspan="3" align="center" height="25" width="130"> Requirement</td>
			<td class="td-border-top td-border-bottom td-border-right td-border-left bold tbl-padding f-size-13" colspan="3" align="center"> Details/Compliance</td>
		</tr>
		<tr>
			<td class="td-border-top td-border-bottom td-border-right td-border-left tbl-padding" colspan="3" align="left" height="50">Appointee with decided admnistrative/criminal case <br>(certified true copy decision rendered submitted)</td>
			<td class="td-border-top td-border-bottom td-border-right td-border-left tbl-padding" colspan="3" align="center"></td>
		</tr>
		<tr>
			<td class="td-border-top td-border-bottom td-border-right td-border-left tbl-padding" colspan="3" align="left" height="50">Discrepancy in name/place of birth<span style="font-size:x-small;"> (Requirements and procedures as <br>amended by CSC Resolution No. 991907 dated August 27, 1999)</span></td>
			<td class="td-border-top td-border-bottom td-border-right td-border-left tbl-padding" colspan="3" align="center"></td>
		</tr>
		<tr>
			<td class="td-border-top td-border-bottom td-border-right td-border-left tbl-padding" colspan="3" align="left" height="50">COMELEC Ban <span style="font-size:small;">(Exemption from COMELEC)</span></td>
			<td class="td-border-top td-border-bottom td-border-right td-border-left tbl-padding" colspan="3" align="center"></td>
		</tr>
		<tr>
			<td class="td-border-top  td-border-right td-border-left tbl-padding" colspan="3" align="left" height="25">Non-Disciplinary Demotion </td>
			<td class="td-border-top td-border-right td-border-left tbl-padding" colspan="3" align="center"></td>
		</tr>
		<tr>
			<td class="td-border-bottom td-border-right td-border-left ul-padding" colspan="3" align="left" height="25">
				<ul style="list-style-type:disc;">
				  <li><span style="font-size:small;">Certification of the Agency Head that demotion is not a result of an<br> administrative case</span></li>
				  <li><span style="font-size:small;">Written consent by the employee interposing no objection to the <br>demotion</span></li>
				</ul>
			</td>
			<td class="td-border-bottom td-border-right td-border-left tbl-padding" colspan="3" align="center"></td>
		</tr>
	</tbody>
</table>

<!-- 3rd Page -->
<table class="table-max f-size-12 bold">
		<tr>
			<td class="bold f-size-13" colspan="6">FOR CSCFO ACTION:</td>
		</tr>
	<tbody>
		<tr>
			<td class="td-border-top td-border-right td-border-left bold tbl-padding" colspan="" align="center" height="10" width=""></td>
			<td class="td-border-top td-border-right td-border-left bold tbl-padding" colspan="" align="center"></td>
			<td class="td-border-top td-border-right td-border-left tbl-padding" colspan="4" align="left"></td>
		</tr>
		<tr>
			<td class="td-border-right td-border-left bold tbl-padding" colspan="" align="center" height="25" width=""> Agency</td>
			<td class="td-border-right td-border-left bold tbl-padding" colspan="" align="left">(<?php echo nbs(3)?>) Accredited</td>
			<td class="td-border-right td-border-left tbl-padding f-size-10" colspan="4" align="left">(<?php echo nbs(3)?>) Appointment submitted to CSCFO within 15 days of the<br> <?php echo nbs(6)?> succeeding month</td>
		</tr>
		<tr>
			<td class="td-border-right td-border-left bold tbl-padding" colspan="" align="center" height="25" width=""> </td>
			<td class="td-border-right td-border-left bold tbl-padding" colspan="" align="center"></td>
			<td class="td-border-right td-border-left tbl-padding f-size-10" colspan="4" align="left">(<?php echo nbs(3)?>) Appointment submitted to CSCFO within 15ᵗʰ day of the<br> <?php echo nbs(6)?> succeeding month</td>
		</tr>
		<tr>
			<td class="td-border-right td-border-left bold tbl-padding" colspan="" align="center" height="25" width=""> </td>
			<td class="td-border-right td-border-left bold tbl-padding" colspan="" align="center"></td>
			<td class="td-border-right td-border-left tbl-padding f-size-10" colspan="4" align="left"><?php echo nbs(6)?> Effective:<u><?php echo nbs(60)?></u></td>
		</tr>
		<tr>
			<td class="td-border-right td-border-left bold tbl-padding" colspan="" align="center" height="25" width=""> Action</td>
			<td class="td-border-right td-border-left bold tbl-padding" colspan="" align="left">(<?php echo nbs(3)?>) Validated</td>
			<td class="td-border-right td-border-left tbl-padding f-size-10" colspan="4" align="left"></td>
		</tr>
		<tr>
			<td class="td-border-right td-border-left bold tbl-padding" colspan="" align="center" height="25" width=""></td>
			<td class="td-border-right td-border-left bold tbl-padding" colspan="" align="left">(<?php echo nbs(3)?>) Invalidated</td>
			<td class="td-border-right td-border-left tbl-padding" colspan="4" align="left">Ground/s for Invalidation:</td>
		</tr>
		<tr>
			<td class="td-border-bottom td-border-right td-border-left bold tbl-padding" colspan="" align="center" height="10" width=""></td>
			<td class="td-border-bottom td-border-right td-border-left bold tbl-padding" colspan="" align="center"></td>
			<td class="td-border-bottom td-border-right td-border-left tbl-padding" colspan="4" align="left"></td>
		</tr>
		<tr>
			<td class="td-border-top td-border-right td-border-left bold tbl-padding" colspan="" align="center" height="10" width=""></td>
			<td class="td-border-top td-border-right td-border-left bold tbl-padding" colspan="" align="center"></td>
			<td class="td-border-top td-border-right td-border-left tbl-padding" colspan="4" align="left"></td>
		</tr>
		<tr>
			<td class="td-border-right td-border-left bold tbl-padding" colspan="" align="center" height="25" width=""> Agency</td>
			<td class="td-border-right td-border-left bold tbl-padding" colspan="" align="left">(<?php echo nbs(3)?>) Regulated</td>
			<td class="td-border-right td-border-left tbl-padding f-size-10" colspan="4" align="left">(<?php echo nbs(3)?>) Appointment submitted to CSCFO within 30 calendar days from<br> <?php echo nbs(6)?>date of issuance</td>
		</tr>
		<tr>
			<td class="td-border-right td-border-left bold tbl-padding" colspan="" align="center" height="25" width=""> </td>
			<td class="td-border-right td-border-left bold tbl-padding" colspan="" align="center"></td>
			<td class="td-border-right td-border-left tbl-padding f-size-10" colspan="4" align="left">(<?php echo nbs(3)?>) Appointment submitted to CSCFO beyond 30 calendar days from<br> <?php echo nbs(6)?>date of issuance</td>
		</tr>
		<tr>
			<td class="td-border-right td-border-left bold tbl-padding" colspan="" align="center" height="25" width=""> </td>
			<td class="td-border-right td-border-left bold tbl-padding" colspan="" align="center"></td>
			<td class="td-border-right td-border-left tbl-padding f-size-10" colspan="4" align="left"><?php echo nbs(6)?>Effective:<u><?php echo nbs(60)?></u></td>
		</tr>
		<tr>
			<td class="td-border-right td-border-left bold tbl-padding" colspan="" align="center" height="25" width=""> Action</td>
			<td class="td-border-right td-border-left bold tbl-padding" colspan="" align="left">(<?php echo nbs(3)?>) Approved</td>
			<td class="td-border-right td-border-left tbl-padding f-size-10" colspan="4" align="left"></td>
		</tr>
		<tr>
			<td class="td-border-right td-border-left bold tbl-padding" colspan="" align="center" height="25" width=""></td>
			<td class="td-border-right td-border-left bold tbl-padding" colspan="" align="left">(<?php echo nbs(3)?>) Disapproved</td>
			<td class="td-border-right td-border-left tbl-padding" colspan="4" align="left">Ground/s for Disapproval:</td>
		</tr>
		<tr>
			<td class="td-border-bottom td-border-right td-border-left bold tbl-padding" colspan="" align="center" height="10" width=""></td>
			<td class="td-border-bottom td-border-right td-border-left bold tbl-padding" colspan="" align="center"></td>
			<td class="td-border-bottom td-border-right td-border-left tbl-padding" colspan="4" align="left"></td>
		</tr>
		<tr>
			<td class="td-border-right td-border-left bold tbl-padding" colspan="2" align="left" height="10" width="">Evaluated By:</td>
			<td class="td-border-right td-border-left bold tbl-padding" colspan="2" align="left">Verified By:</td>
			<td class="td-border-right td-border-left tbl-padding" colspan="2" align="left">Final Action By:</td>
		</tr>
		<tr>
			<td class="td-border-bottom td-border-right td-border-left bold tbl-padding" colspan="2" align="center" height="30" width=""></td>
			<td class="td-border-bottom td-border-right td-border-left bold tbl-padding" colspan="2" align="center"></td>
			<td class="td-border-bottom td-border-right td-border-left tbl-padding" colspan="2" align="left"></td>
		</tr>
		<tr>
			<td class="td-border-bottom td-border-right td-border-left bold tbl-padding" colspan="2" align="left" height="20" width="">Date:</td>
			<td class="td-border-bottom td-border-right td-border-left bold tbl-padding" colspan="2" align="left">Date:</td>
			<td class="td-border-bottom td-border-right td-border-left tbl-padding" colspan="2" align="left">Date:</td>
		</tr>
		
	</tbody>

</table>
<!-- ************************************************************************** -->
</body>

</html>


