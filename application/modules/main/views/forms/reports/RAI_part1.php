
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<html>
<head>
	
	<title>Report on Appointment Part I</title>
	<link rel="stylesheet" href="<?php echo base_url().PATH_CSS ?>reports.css" type="text/css" />	
</head>
<style type="text/css">
	table{
		
		font-family: Calibri;
	}
	.line {

		padding-bottom: 10px;
		border-bottom-style: solid;
		border-bottom-width: 1.5px;
		width: fit-content;
	}
	td.line-space{
		line-height: 100px;
	}
</style>

<body>
	<?php
	$employeesPerPage = 5;
	$totalEmployees = count($records);
	$pageCount = ceil($totalEmployees / $employeesPerPage);
	$cnt = 1;


	if ($totalEmployees > 0) {
		for ($page = 1; $page <= $pageCount; $page++) { ?> 
			<!-- HEADER TABLE -->
			<table class="f-size-8">
				<tbody>
					<tr>
						<td align="left" width=""><b><i>CS Form No. 2</i></b></td>
					</tr>
					<tr>
						<td align="left" width="300" colspan="5"><b><i>Revised 2018</i></b></td>
						<td align="left" width="300" colspan="5"></td>
						<td align="left" width="200" colspan="5"></td>
						<td class="td-border-top td-border-bottom td-border-left td-border-right td-center-middle"  width="500" colspan="5"><i>For Use of Accredited Agencies Only</i></td>

					</tr>
				</tbody>
			</table>
			<table class="table-max">
				<tr>
					<td height="25"></td>
				</tr>
				<tr>
					<td class="td-center-middle f-size-12" colspan=10 height="15"><?php ///echo nbs(15)?>REPORT ON APPOINTMENT ISSUED (RAI)</td>
				</tr>
				<tr>
					<td class="td-center-middle" colspan=10 height="15"> For the month of <span class="line bold" >&emsp;<b><?php echo $date_hdr?></b>&emsp;</span></td>
				</tr>
				<tr>
					<td height="10"></td>
				</tr>
				<tr>
					<td colspan=4 height="15" align="left" width="" valign=middle></td>
					<td colspan=4 align="left" valign=middle><?php echo nbs(5)?></td>
					<td colspan=4 align="right" >Date received by the CSC FO: <u>&emsp;&emsp;&emsp; &emsp; &emsp; &emsp; &emsp; &emsp; &emsp; &emsp; &emsp;  </u></td>
				</tr>
				<tr>
					<td height="10"></td>
				</tr>
				<tr>
					<td colspan=4 height="15" align="left">AGENCY:<u> <b><?php echo $office_name ?></b></u></td>
					<td colspan=4 align="left" valign=middle>CSC Resolution No: <span class="line bold" >&emsp;<b>1900961 s. 2019&emsp;</b></span></td>
					<td colspan=4 align="right" valign=middle>CSC FO In-Charge: <u>&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp; &emsp; &emsp; &emsp; &emsp; &emsp; &emsp; &emsp; &emsp; &emsp; &emsp; &emsp; &emsp; &emsp; &emsp; &emsp; </u></td>
				</tr>
				<tr>
					<td height="10"></td>
				</tr>
				<tr>
					<td colspan=8 height="15" align="left" valign=middle><b>INSTRUCTIONS: </b><?php echo nbs(3)?> (1) Fill-out the data needed in the form completely and accurately.</td>
				</tr>
				<tr>
					<td colspan=8 height="15" align="left" valign=middle><?php echo nbs(32)?> (2) Do not abbreviate entries in the form.</td>
				</tr>
				<tr>
					<td colspan=8 height="15" align="left" valign=middle><?php echo nbs(32)?> (3) Accomplish the Checklist of Common Requirements and sign the certification.</td>
				</tr>
				<tr>
					<td colspan=8 height="15" align="left" valign=middle><?php echo nbs(32)?> (4) Submit the duly accomplished form in electronic and printed copy (2 copies) to the CSC Field Office-in-Charge<br><?php echo nbs(39)?>together with the original CSC copy of appointments and supporting documents within the 30th day of the succeeding month.</td>
				</tr>
				<tr>
					<td height="10"></td>
				</tr>
				<tr>
					<td colspan=8 height="15" align="left" valign=middle><b>Pertinent data on appointment issued</b></td>
				</tr>
			</table>
			<!-- HEADER TABLE -->

			<!-- DATA TABLE AND INFORMATION -->
			<table class="table-max">
				<thead>
					<tr>
						<td class="td-border-thick-bottom td-border-thick-top td-border-thick-left td-center-middle f-size-11" rowspan=2></td>
						<td class="td-border-thick-bottom td-border-thick-right td-border-thick-top td-border-thick-left td-center-middle f-size-11" rowspan=2>Date Issued/<br>Effectivity<br>(mm/dd/yyyy)</td>
						<td class="td-border-thick-top td-border-thick-bottom td-border-thick-left td-center-middle f-size-11" colspan=4 height="10"><b>NAME OF APPOINTEE/S</td>
							<td class="td-border-thick-bottom td-border-thick-top td-border-thick-left td-center-middle f-size-11" rowspan=2>POSITION TITLE</b><br>(Indicate parenthetical<br>title if applicable)</td>
							<td class="td-border-thick-bottom td-border-thick-top td-border-thick-left td-center-middle f-size-11" rowspan=2>ITEM NO.</td>
							<td class="td-border-thick-bottom td-border-thick-top td-border-thick-left td-center-middle f-size-11" rowspan=2>SALARY/<br>JOB/<br>PAY<br>PAY<br>GRADE</td>
							<td class="td-border-thick-bottom td-border-thick-top td-border-thick-left td-center-middle f-size-11" rowspan=2>SALARY RATE<br><b>(Monthly)</b></td>
							<td class="td-border-thick-bottom td-border-thick-top td-border-thick-left td-center-middle f-size-11" rowspan=2>EMPLOYMENT<br>STATUS</td>
							<td class="td-border-thick-bottom td-border-thick-top td-border-thick-left td-center-middle f-size-11" rowspan=2>PERIOD OF <br>EMPLOYMENT (for<br>Temporary, Casual/<br>Contractual<br>Appointments)<br>(mm/dd/yyy to<br>mm/dd/yyyy)</td>
							<td class="td-border-thick-bottom td-border-thick-top td-border-thick-left td-center-middle f-size-11" rowspan=2>NATURE OF <br> APPOINTMENT</td>
							<td class="border-thick td-center-middle f-size-11" colspan=2 valign=bottom>PUBLICATION</td>
							<td class="border-thick td-center-middle f-size-11" colspan=3 valign=bottom>CSC ACTION</td>
							<td class="td-border-thick-bottom td-border-thick-top td-border-thick-right td-center-middle" rowspan=2>Agency<br>Receiving<br>Officer</td>
						</tr>
						<tr>
							<td class="td-border-thick-bottom td-border-thick-right td-center-middle f-size-11" height="31">Last Name</td>
							<td class="td-border-thick-bottom td-border-thick-right td-center-middle f-size-11">First Name</td>
							<td class="td-border-thick-bottom td-border-thick-right td-center-middle f-size-11">Name<br>Extension<br>(Jr./III)</td>
							<td class="td-border-thick-bottom td-border-thick-right td-center-middle f-size-11">Middle Name</td>
							<td class="td-border-thick-bottom td-border-thick-left td-border-thick-right td-center-middle f-size-11">DATE<br>indicate period of<br>publication<br>(mm/dd/yyyy to <br>mm/dd/yyyy)</td>
							<td class="td-border-thick-bottom td-border-thick-right td-center-middle f-size-11">MODE<br>(CSC Bulletin of Vacant<br>Positions, <b>Agency <br>Website, Newspaper,<br>etc)</b></td>
							<td class="td-border-thick-bottom td-border-thick-right td-center-middle f-size-11">V-Validated<br>INV-<br>Invalidated<br><b>N-Noted</b></td>
							<td class="td-border-thick-bottom td-border-thick-right td-center-middle f-size-11">Date<br>of<br>Action<br>(mm/dd/yyyy)</td>
							<td class="td-border-thick-bottom td-border-thick-right td-center-middle f-size-11">Date of<br>Release<br>(mm/dd/yyyy)</td>
						</tr>
						<tr>
							<td class="td-border-thick-bottom td-border-thick-right td-border-thick-left td-center-middle"><b></b></td>
							<td class="td-border-thick-bottom td-border-thick-right	 td-center-middle">(1)</td>
							<td class="td-border-thick-bottom td-border-thick-right td-center-middle" colspan=4>(2)</td>
							<td class="td-border-thick-bottom td-border-thick-right td-center-middle">(3)</td>
							<td class="td-border-thick-bottom td-border-thick-right td-center-middle">(4)</td>
							<td class="td-border-thick-bottom td-border-thick-right td-center-middle">(5)</td>
							<td class="td-border-thick-bottom td-border-thick-right td-center-middle">(6)</td>
							<td class="td-border-thick-bottom td-border-thick-right td-center-middle">(7)</td>
							<td class="td-border-thick-bottom td-border-thick-right td-center-middle">(8)</td>
							<td class="td-border-thick-bottom td-border-thick-right td-center-middle">(9)</td>
							<td class="td-border-thick-bottom td-border-thick-right td-center-middle">(10)</td>
							<td class="td-border-thick-bottom td-border-thick-right td-center-middle">(11)</td>
							<td class="td-border-thick-right td-border-thick-bottom td-center-middle">(12)</td>
							<td class="td-border-thick-right td-border-thick-bottom td-center-middle">(13)</td>
							<td class="td-border-thick-right td-border-thick-bottom td-center-middle">(14)</td>
							<td class="td-border-thick-right td-border-thick-bottom td-center-middle">(15)</td>
						</tr>	
					</thead>
					<tbody>
						<?php
						$startIndex = ($page - 1) * $employeesPerPage;
						$endIndex = min($startIndex + $employeesPerPage, $totalEmployees);


						for ($i = $startIndex; $i < $endIndex; $i++) {
							$record = $records[$i];
							?>
							<?php if($records): ?>
								<?php 
			//foreach ($records //AS $record): ?>
			<tr class=" f-size-notice">
				<td class="td-border-thick-bottom td-border-thick-left td-border-right td-center-middle pad-2" height="20" width="30"><?php echo $cnt ?></td>
				<td class="td-border-thick-bottom td-border-left td-border-right td-center-middle" width="70"><?php echo date_format(date_create($record['employ_start_date']), 'm/d/Y') ?></td>
				<td class="td-border-thick-bottom td-border-left td-border-right td-center-middle" width="100"><?php echo strtoupper($record['last_name']) ?></td>
				<td class="td-border-thick-bottom td-border-left td-border-right td-center-middle" width="100"><?php echo strtoupper($record['first_name']) ?></td>
				<td class="td-border-thick-bottom td-border-left td-border-right td-center-middle" width="20"><?php echo (!EMPTY($record['ext_name']) ? $record['ext_name'] : N_A); ?></td>
				<td class="td-border-thick-bottom td-border-left td-border-right td-center-middle" width="100">
					<?php
					$middle_name = strtoupper($record['middle_name']);
					if ($middle_name == '-') {
						echo "N/A";
					}elseif ($middle_name == '/') {
						echo "N/A";
					}elseif ($middle_name == 'NA') {
						echo "N/A";
					}else{
						echo $middle_name;
					}
					?>
				<td class="td-border-thick-bottom td-border-left td-border-right td-center-middle" width="120"><?php echo strtoupper($record['position_name']) ?></td>
				<td class="td-border-thick-bottom td-border-left td-border-right td-center-middle pad-2" width="80">
					<?php //echo $record['plantilla_code'] ?>
					<?php 
					$emp_status = strtoupper($record['employment_status_name']);
					if($emp_status == "CONTRACTUAL"){
						echo "N/A";
					}
					else{
						echo $record['plantilla_code'];
					}?>
					</td>
				<td class="td-border-thick-bottom td-border-left td-border-right td-center-middle" width="70"><?php echo $record['salary_grade'] ?></td>
				<td class="td-border-thick-bottom td-border-left td-border-right td-center-middle" width="70">P <?php echo number_format($record['employ_monthly_salary'],2)?></td>
				<td class="td-border-thick-bottom td-border-left td-border-right td-center-middle" width="100"><?php echo strtoupper($record['employment_status_name']) ?></td>
				<td class="td-border-thick-bottom td-border-left td-border-right td-center-middle pad-2" width="100">
					<?php
					$emp_from = $record['employ_period_from'];
					$emp_to = $record['employ_period_to'];
					$employ_period_from = date_format(date_create($emp_from), 'm/d/Y');
					$employ_period_to = date_format(date_create($emp_to), 'm/d/Y');

					if (!EMPTY($emp_from) && !EMPTY($emp_to)){
						echo $employ_period_from. ' <br>to<br>' .$employ_period_to ;
					}
					elseif (!EMPTY($emp_from))  {
						echo $employ_period_from.' <br> to <br> N/A';
					}elseif (!EMPTY($emp_to)){
						echo 'N/A <br> to <br>'. $employ_period_to;
					}elseif (EMPTY($emp_from) && EMPTY($emp_to)) {
						echo 'N/A';
					}
					?>
				</td>
				<td class="td-border-thick-bottom td-border-left td-border-right td-center-middle pad-2" width="100"><?php echo strtoupper($record['personnel_movement_name']) ?></td>
				<td class="td-border-thick-bottom td-border-left td-border-right td-center-middle" width="100">
					<?php
					$pub_from = $record['publication_date'];
					$pub_to = $record['publication_date_to'];
					$publication_date_from = date_format(date_create($pub_from), 'm/d/Y');
					$publication_date_to = date_format(date_create($pub_to), 'm/d/Y');

					if (!EMPTY($pub_from) && !EMPTY($pub_to)){
						echo $publication_date_from. ' <br>to<br>' .$publication_date_to ;
					}
					elseif (!EMPTY($pub_from))  {
						echo $publication_date_from.' <br> to <br> N/A';
					}elseif (!EMPTY($pub_to)){
						echo 'N/A <br> to <br>'. $publication_date_to;
					}elseif (EMPTY($pub_from) && EMPTY($pub_to)) {
						echo 'N/A';
					}

					?>
				</td>
				<td class="td-border-thick-bottom td-border-left td-border-right td-center-middle" width="70"><?php echo (!EMPTY($record['publication_place'])) ? strtoupper($record['publication_place']) : NOT_APPLICABLE; ?></td>
				<td class="td-border-thick-bottom td-border-left td-border-thick-right td-center-middle" width=""></td>
				<td class="td-border-thick-bottom td-border-left td-border-thick-right td-center-middle" width=""></td>
				<td class="td-border-thick-bottom td-border-left td-border-thick-right td-center-middle" width=""></td>
				<td class="td-border-thick-bottom td-border-left td-border-thick-right td-center-middle" width="50"></td>
			</tr>
			<?php 
			$cnt++; ?>
		<?php else: ?>
			<tr>
				<td colspan=19 class="td-border-bottom td-border-thick-left td-border-right td-center-middle" height="20"><b>No Records Found.</b></td>
			</tr>
		<?php endif;?>
	<?php } ?>
</tbody>
</table>
<!-- DATA TABLE AND INFORMATION -->

<!-- CERTIFICATION TABLE AND SIGNATORIES-->
<br>
<table>
	<tr>
		<td height="10" align="left" valign=bottom width="270" class="f-size-9">CERTIFICATION:</td>
		<td height="10" align="left" valign=bottom width="150"></td>
		<td align="left" valign=bottom width="270" class="f-size-9">CERTIFICATION:</td>
		<td height="10" align="left" valign=bottom width="150"></td>
		<td align="left" valign=bottom width="270" class="f-size-9">Post-Audited by:</td>
	</tr>
	<tr>
		<td height="30"></td>
	</tr>
	<tr>
		<td height="10" align="left" valign=bottom width="" class="f-size-9"><?php echo nbs(9)?>This is to certify that the information contained in this<br>report are true, correct and complete based on the Plantilla<br>of Personnel and appointment/s issued.</td>
		<td align="left" valign=bottom width=""><?php echo nbs(9)?></td>

		<td align="left" valign=bottom width="" class="f-size-9"><?php echo nbs(8)?>This is to certify that the appointment/s issued<br>is/are in accordance with existing Civil Service Law,<br>rules and regulations.</td>
		<td align="left" valign=bottom width=""><?php echo nbs(9)?></td>
	</tr>
	<tr>
		<td height="30"></td>
	</tr>
	<tr>
		<td class="td-border-bottom" align="center" valign=bottom width="200" >
			<b><?php echo strtoupper($reviewed_by['signatory_name']);?></b><br>
			&emsp;<?php echo $reviewed_by['position_name'] ?>&emsp;
		</td>

		<td align="left" valign=bottom width=""><?php echo nbs(9)?></td>
		<td class="td-border-bottom" align="center" valign=bottom width="200" >
			<b><?php echo $certified_by['signatory_name']; ?></b><br>
			&emsp;<?php echo $certified_by['position_name'] ?>, <?php echo $certified_by['office_name']?>&emsp;
		</td>
		<td align="left" valign=bottom width=""><?php echo nbs(9)?></td>
		<td class="td-border-bottom" align="center" valign=bottom width="200" ></td>
	</tr>

	<tr>
		<td align="center" valign=bottom width="200" >
			<b>HRMO</b>
		</td>

		<td align="left" valign=bottom width=""><?php echo nbs(9)?></td>
		<td align="center" valign=bottom width="200" >
			Agency Head or Authorized Official	
		</td>
		<td align="left" valign=bottom width=""><?php echo nbs(9)?></td>
		<td align="center" valign=bottom width="200" >CSC Official</td>
	</tr>
	<tr>
		<td height="30"></td>
	</tr>	
</table>
<!-- CERTIFICATION TABLE AND SIGNATORIES-->

<!-- REMARKS TABLE-->
<table class="table-max" style="page-break-after: always;">	
	<!-- <table class="table-max" style="<?php //echo ($page < $pageCount) ? 'page-break-after: always;' : ''; ?>"> -->

		<tr>
			<td><i>For CSC Use Only:</i></td>
		</tr>
		<tr>
			<td class="td-border-top td-border-bottom td-border-left td-border-right" height="10">REMARKS/COMMENTS/RECOMMENDATIONS (e.g. Reasons for Invalidation):</td>
		</tr>
		<tr>
			<td class="td-border-top td-border-bottom td-border-left td-border-right td-center-middle f-size-11" height="10"></td>
		</tr>
		<tr>
			<td class="td-border-top td-border-bottom td-border-left td-border-right td-center-middle f-size-11" height="10"></td>
		</tr>
		<tr>
			<td class="td-border-top td-border-bottom td-border-left td-border-right td-center-middle f-size-11" height="10" class="page-break-after"></td>
		</tr>
	</table>
	<!-- REMARKS TABLE-->
<?php } // end of for condition
} // end of if condition

else { ?> 
	<table class="f-size-8">
		<tbody>
			<tr>
				<td align="left" width=""><b><i>CS Form No. 2</i></b></td>
			</tr>
			<tr>
				<td align="left" width="300" colspan="5"><b><i>Revised 2018</i></b></td>
				<td align="left" width="300" colspan="5"></td>
				<td align="left" width="200" colspan="5"></td>
				<td class="td-border-top td-border-bottom td-border-left td-border-right td-center-middle"  width="500" colspan="5"><i>For Use of Accredited Agencies Only</i></td>

			</tr>
		</tbody>
	</table>
	<table class="table-max">
		<tr>
			<td height="25"></td>
		</tr>
		<tr>
			<td class="td-center-middle f-size-12" colspan=10 height="15"><?php ///echo nbs(15)?>REPORT ON APPOINTMENT ISSUED (RAI)</td>
		</tr>
		<tr>
			<td class="td-center-middle" colspan=10 height="15"> For the month of <span class="line bold" >&emsp;<b><?php echo $date_hdr?></b>&emsp;</span></td>
		</tr>
		<tr>
			<td height="10"></td>
		</tr>
		<tr>
			<td colspan=4 height="15" align="left" width="" valign=middle></td>
			<td colspan=4 align="left" valign=middle><?php echo nbs(5)?></td>
			<td colspan=4 align="right" >Date received by the CSC FO: <u>&emsp;&emsp;&emsp; &emsp; &emsp; &emsp; &emsp; &emsp; &emsp; &emsp; &emsp;  </u></td>
		</tr>
		<tr>
			<td height="10"></td>
		</tr>
		<tr>
			<td colspan=4 height="15" align="left">AGENCY:<u> <b><?php echo $office_name ?></b></u></td>
			<td colspan=4 align="left" valign=middle>CSC Resolution No: <span class="line bold" >&emsp;<b>1900961 s. 2019&emsp;</b></span></td>
			<td colspan=4 align="right" valign=middle>CSC FO In-Charge: <u>&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp; &emsp; &emsp; &emsp; &emsp; &emsp; &emsp; &emsp; &emsp; &emsp; &emsp; &emsp; &emsp; &emsp; &emsp; &emsp; </u></td>
		</tr>
		<tr>
			<td height="10"></td>
		</tr>
		<tr>
			<td colspan=8 height="15" align="left" valign=middle><b>INSTRUCTIONS: </b><?php echo nbs(3)?> (1) Fill-out the data needed in the form completely and accurately.</td>
		</tr>
		<tr>
			<td colspan=8 height="15" align="left" valign=middle><?php echo nbs(32)?> (2) Do not abbreviate entries in the form.</td>
		</tr>
		<tr>
			<td colspan=8 height="15" align="left" valign=middle><?php echo nbs(32)?> (3) Accomplish the Checklist of Common Requirements and sign the certification.</td>
		</tr>
		<tr>
			<td colspan=8 height="15" align="left" valign=middle><?php echo nbs(32)?> (4) Submit the duly accomplished form in electronic and printed copy (2 copies) to the CSC Field Office-in-Charge<br><?php echo nbs(39)?>together with the original CSC copy of appointments and supporting documents within the 30th day of the succeeding month.</td>
		</tr>
		<tr>
			<td height="10"></td>
		</tr>
		<tr>
			<td colspan=8 height="15" align="left" valign=middle><b>Pertinent data on appointment issued</b></td>
		</tr>
	</table>
	<table class="table-max">
		<thead>
			<tr>
				<td class="td-border-thick-bottom td-border-thick-top td-border-thick-left td-center-middle f-size-11" rowspan=2></td>
				<td class="td-border-thick-bottom td-border-thick-right td-border-thick-top td-border-thick-left td-center-middle f-size-11" rowspan=2>Date Issued/<br>Effectivity<br>(mm/dd/yyyy)</td>
				<td class="td-border-thick-top td-border-thick-bottom td-border-thick-left td-center-middle f-size-11" colspan=4 height="10"><b>NAME OF APPOINTEE/S</td>
					<td class="td-border-thick-bottom td-border-thick-top td-border-thick-left td-center-middle f-size-11" rowspan=2>POSITION TITLE</b><br>(Indicate parenthetical<br>title if applicable)</td>
					<td class="td-border-thick-bottom td-border-thick-top td-border-thick-left td-center-middle f-size-11" rowspan=2>ITEM NO.</td>
					<td class="td-border-thick-bottom td-border-thick-top td-border-thick-left td-center-middle f-size-11" rowspan=2>SALARY/<br>JOB/<br>PAY<br>PAY<br>GRADE</td>
					<td class="td-border-thick-bottom td-border-thick-top td-border-thick-left td-center-middle f-size-11" rowspan=2>SALARY RATE<br><b>(Monthly)</b></td>
					<td class="td-border-thick-bottom td-border-thick-top td-border-thick-left td-center-middle f-size-11" rowspan=2>EMPLOYMENT<br>STATUS</td>
					<td class="td-border-thick-bottom td-border-thick-top td-border-thick-left td-center-middle f-size-11" rowspan=2>PERIOD OF <br>EMPLOYMENT (for<br>Temporary, Casual/<br>Contractual<br>Appointments)<br>(mm/dd/yyy to<br>mm/dd/yyyy)</td>
					<td class="td-border-thick-bottom td-border-thick-top td-border-thick-left td-center-middle f-size-11" rowspan=2>NATURE OF <br> APPOINTMENT</td>
					<td class="border-thick td-center-middle f-size-11" colspan=2 valign=bottom>PUBLICATION</td>
					<td class="border-thick td-center-middle f-size-11" colspan=3 valign=bottom>CSC ACTION</td>
					<td class="td-border-thick-bottom td-border-thick-top td-border-thick-right td-center-middle" rowspan=2>Agency<br>Receiving<br>Officer</td>
				</tr>
				<tr>
					<td class="td-border-thick-bottom td-border-thick-right td-center-middle f-size-11" height="31">Last Name</td>
					<td class="td-border-thick-bottom td-border-thick-right td-center-middle f-size-11">First Name</td>
					<td class="td-border-thick-bottom td-border-thick-right td-center-middle f-size-11">Name<br>Extension<br>(Jr./III)</td>
					<td class="td-border-thick-bottom td-border-thick-right td-center-middle f-size-11">Middle Name</td>
					<td class="td-border-thick-bottom td-border-thick-left td-border-thick-right td-center-middle f-size-11">DATE<br>indicate period of<br>publication<br>(mm/dd/yyyy to <br>mm/dd/yyyy)</td>
					<td class="td-border-thick-bottom td-border-thick-right td-center-middle f-size-11">MODE<br>(CSC Bulletin of Vacant<br>Positions, <b>Agency <br>Website, Newspaper,<br>etc)</b></td>
					<td class="td-border-thick-bottom td-border-thick-right td-center-middle f-size-11">V-Validated<br>INV-<br>Invalidated<br><b>N-Noted</b></td>
					<td class="td-border-thick-bottom td-border-thick-right td-center-middle f-size-11">Date<br>of<br>Action<br>(mm/dd/yyyy)</td>
					<td class="td-border-thick-bottom td-border-thick-right td-center-middle f-size-11">Date of<br>Release<br>(mm/dd/yyyy)</td>
				</tr>
				<tr>
					<td class="td-border-thick-bottom td-border-thick-right td-border-thick-left td-center-middle"><b></b></td>
					<td class="td-border-thick-bottom td-border-thick-right	 td-center-middle">(1)</td>
					<td class="td-border-thick-bottom td-border-thick-right td-center-middle" colspan=4>(2)</td>
					<td class="td-border-thick-bottom td-border-thick-right td-center-middle">(3)</td>
					<td class="td-border-thick-bottom td-border-thick-right td-center-middle">(4)</td>
					<td class="td-border-thick-bottom td-border-thick-right td-center-middle">(5)</td>
					<td class="td-border-thick-bottom td-border-thick-right td-center-middle">(6)</td>
					<td class="td-border-thick-bottom td-border-thick-right td-center-middle">(7)</td>
					<td class="td-border-thick-bottom td-border-thick-right td-center-middle">(8)</td>
					<td class="td-border-thick-bottom td-border-thick-right td-center-middle">(9)</td>
					<td class="td-border-thick-bottom td-border-thick-right td-center-middle">(10)</td>
					<td class="td-border-thick-bottom td-border-thick-right td-center-middle">(11)</td>
					<td class="td-border-thick-right td-border-thick-bottom td-center-middle">(12)</td>
					<td class="td-border-thick-right td-border-thick-bottom td-center-middle">(13)</td>
					<td class="td-border-thick-right td-border-thick-bottom td-center-middle">(14)</td>
					<td class="td-border-thick-right td-border-thick-bottom td-center-middle">(15)</td>
				</tr>	
		</thead>
		<tbody>
				<tr>
					<td colspan=19 class="td-border-thick-bottom td-border-thick-left td-border-thick-right td-center-middle" height="20"><b>No Records Found.</b></td>
				</tr>
				<tr>
					<td colspan=19 class="" height="130"></td>
				</tr>
		</tbody>
	</table>
	<table>
		<tr>
					<td height="10" align="left" valign=bottom width="270" class="f-size-9">CERTIFICATION:</td>
					<td height="10" align="left" valign=bottom width="150"></td>
					<td align="left" valign=bottom width="270" class="f-size-9">CERTIFICATION:</td>
					<td height="10" align="left" valign=bottom width="150"></td>
					<td align="left" valign=bottom width="270" class="f-size-9">Post-Audited by:</td>
				</tr>
				<tr>
					<td height="30"></td>
				</tr>
				<tr>
					<td height="10" align="left" valign=bottom width="" class="f-size-9"><?php echo nbs(9)?>This is to certify that the information contained in this<br>report are true, correct and complete based on the Plantilla<br>of Personnel and appointment/s issued.</td>
					<td align="left" valign=bottom width=""><?php echo nbs(9)?></td>

					<td align="left" valign=bottom width="" class="f-size-9"><?php echo nbs(8)?>This is to certify that the appointment/s issued<br>is/are in accordance with existing Civil Service Law,<br>rules and regulations.</td>
					<td align="left" valign=bottom width=""><?php echo nbs(9)?></td>
				</tr>
				<tr>
					<td height="30"></td>
				</tr>
				<tr>
					<td class="td-border-bottom" align="center" valign=bottom width="200" >
						<b><?php echo strtoupper($reviewed_by['signatory_name']);?></b><br>
						&emsp;<?php echo $reviewed_by['position_name'] ?>&emsp;
					</td>

					<td align="left" valign=bottom width=""><?php echo nbs(9)?></td>
					<td class="td-border-bottom" align="center" valign=bottom width="200" >
						<b><?php echo $certified_by['signatory_name']; ?></b><br>
						&emsp;<?php echo $certified_by['position_name'] ?>, <?php echo $certified_by['office_name']?>&emsp;
					</td>
					<td align="left" valign=bottom width=""><?php echo nbs(9)?></td>
					<td class="td-border-bottom" align="center" valign=bottom width="200" ></td>
				</tr>

		<tr>
			<td align="center" valign=bottom width="200" >
				<b>HRMO</b>
			</td>

			<td align="left" valign=bottom width=""><?php echo nbs(9)?></td>
			<td align="center" valign=bottom width="200" >
						Agency Head or Authorized Official	
					</td>
					<td align="left" valign=bottom width=""><?php echo nbs(9)?></td>
					<td align="center" valign=bottom width="200" >CSC Official</td>
				</tr>
		<tr>
					<td height="30"></td>
				</tr>	
		</table>
			<!-- CERTIFICATION TABLE AND SIGNATORIES-->

			<!-- REMARKS TABLE-->
		<table class="table-max" style="page-break-after: always;">	
			<tr>
				<td><i>For CSC Use Only:</i></td>
			</tr>
			<tr>
				<td class="td-border-top td-border-bottom td-border-left td-border-right" height="10">REMARKS/COMMENTS/RECOMMENDATIONS (e.g. Reasons for Invalidation):</td>
			</tr>
			<tr>
				<td class="td-border-top td-border-bottom td-border-left td-border-right td-center-middle f-size-11" height="10"></td>
			</tr>
			<tr>
				<td class="td-border-top td-border-bottom td-border-left td-border-right td-center-middle f-size-11" height="10"></td>
			</tr>
			<tr>
				<td class="td-border-top td-border-bottom td-border-left td-border-right td-center-middle f-size-11" height="10" class="page-break-after"></td>
			</tr>
		</table>
				<!-- REMARKS TABLE-->

<!-- end of else -->
<?php } ?>

<!-- LAST PAGE-->
				<table class="table-max">	
					<tr>
						<td class="td-border-thick-top td-border-thick-bottom td-border-thick-left td-border-thick-right td-center-middle f-size-14" height="30" width="40%" colspan="2">CHECKLIST FOR REQUIREMENTS</td>
						<td class="td-border-thick-top td-border-thick-bottom td-border-thick-left td-border-thick-right td-center-middle f-size-14" width="30%">HRMO</td>
						<td class="td-border-thick-top td-border-thick-bottom td-border-thick-left td-border-thick-right td-center-middle f-size-14" width="30%">CSC FO</td>
					</tr>
					<tr>
						<td class="td-border-thick-top td-border-thick-bottom td-border-thick-left td-border-thick-right td-center-middle f-size-12" colspan="4">Instructions: Put a check if the requirements are complete. If incomplete, use the space provided to indicate the name of appointee and the lacking requirement/s.</td>
					</tr>
					<tr>
						<td class="td-border-thick-top td-border-thick-bottom td-border-thick-left td-border-thick-right td-center-middle f-size-12" width="2%" height="70">1</td>
						<td class="td-border-thick-top td-border-thick-bottom td-border-thick-left td-border-thick-right td-center-left f-size-12" width="40%"><i>APPOINTMENT FORMS (CS FORM No. 33-B, Revised 2017) - Original CSC copy of appointment form</i></td>
						<td class="td-border-thick-top td-border-thick-bottom td-border-thick-left td-border-thick-right td-center-middle f-size-12" ></td>
						<td class="td-border-thick-top td-border-thick-bottom td-border-thick-left td-border-thick-right td-center-middle f-size-12" ></td>
					</tr>
					<tr>
						<td class="td-border-thick-top td-border-thick-bottom td-border-thick-left td-border-thick-right td-center-middle f-size-12" width="2%" height="70">2</td>
						<td class="td-border-thick-top td-border-thick-bottom td-border-thick-left td-border-thick-right td-center-left f-size-12" width="40%"><i>PLANTILLA OF CASUAL APPOINTMENT (CSC Form No. 34-B, D, <b>E or F</b>) - Original CSC copy</i></td>
						<td class="td-border-thick-top td-border-thick-bottom td-border-thick-left td-border-thick-right td-center-middle f-size-12" ></td>
						<td class="td-border-thick-top td-border-thick-bottom td-border-thick-left td-border-thick-right td-center-middle f-size-12" ></td>
					</tr>
					<tr>
						<td class="td-border-thick-top td-border-thick-bottom td-border-thick-left td-border-thick-right td-center-middle f-size-12" width="2%" height="70">3</td>
						<td class="td-border-thick-top td-border-thick-bottom td-border-thick-left td-border-thick-right td-center-left f-size-12" width="40%"><i>PERSONAL DATA SHEET (CS Form No. 212, Revised 2017)</i></td>
						<td class="td-border-thick-top td-border-thick-bottom td-border-thick-left td-border-thick-right td-center-middle f-size-12" ></td>
						<td class="td-border-thick-top td-border-thick-bottom td-border-thick-left td-border-thick-right td-center-middle f-size-12" ></td>
					</tr>
					<tr>
						<td class="td-border-thick-top td-border-thick-bottom td-border-thick-left td-border-thick-right td-center-middle f-size-12" width="2%" height="70">4</td>
						<td class="td-border-thick-top td-border-thick-bottom td-border-thick-left td-border-thick-right td-center-left f-size-12" width="40%"><i>ORIGINAL COPY OF AUTHENTICATED CERTIFICATE OF ELIGIBILITY/ RATING/ LICENSE - Except if the eligibility has been previously authenticated in 2004 or onward and recorded by the CSC </i></td>
						<td class="td-border-thick-top td-border-thick-bottom td-border-thick-left td-border-thick-right td-center-middle f-size-12" ></td>
						<td class="td-border-thick-top td-border-thick-bottom td-border-thick-left td-border-thick-right td-center-middle f-size-12" ></td>
					</tr>
					<tr>
						<td class="td-border-thick-top td-border-thick-bottom td-border-thick-left td-border-thick-right td-center-middle f-size-12" width="2%" height="70">5</td>
						<td class="td-border-thick-top td-border-thick-bottom td-border-thick-left td-border-thick-right td-center-left f-size-12" width="40%"><i>POSITION DESCRIPTION FORM (DBM-CSC Form No. 1, Revised 2017)</i></td>
						<td class="td-border-thick-top td-border-thick-bottom td-border-thick-left td-border-thick-right td-center-middle f-size-12" ></td>
						<td class="td-border-thick-top td-border-thick-bottom td-border-thick-left td-border-thick-right td-center-middle f-size-12" ></td>
					</tr>
					<tr>
						<td class="td-border-thick-top td-border-thick-bottom td-border-thick-left td-border-thick-right td-center-middle f-size-12" width="2%" height="70">6</td>
						<td class="td-border-thick-top td-border-thick-bottom td-border-thick-left td-border-thick-right td-center-left f-size-12" width="40%"><i>OATH OF OFFICE (CS Form No. 32, Revised 2017)</i></td>
						<td class="td-border-thick-top td-border-thick-bottom td-border-thick-left td-border-thick-right td-center-middle f-size-12" ></td>
						<td class="td-border-thick-top td-border-thick-bottom td-border-thick-left td-border-thick-right td-center-middle f-size-12" ></td>
					</tr>
					<tr>
						<td class="td-border-thick-top td-border-thick-bottom td-border-thick-left td-border-thick-right td-center-middle f-size-12" width="2%" height="70">7</td>
						<td class="td-border-thick-top td-border-thick-bottom td-border-thick-left td-border-thick-right td-center-left f-size-12" width="40%"><i>CERTIFICATE OF ASSUMPTION TO DUTY (CS Form No. 4) </i></td>
						<td class="td-border-thick-top td-border-thick-bottom td-border-thick-left td-border-thick-right td-center-middle f-size-12" ></td>
						<td class="td-border-thick-top td-border-thick-bottom td-border-thick-left td-border-thick-right td-center-middle f-size-12" ></td>
					</tr>
					<tr>
						<td width="5%" height="50"></td>
						<td width="40%"></td>
						<td class="td-border-thick-top td-border-thick-bottom td-border-thick-left td-border-thick-right f-size-12" style="text-align: justify;"ss>&emsp;&emsp;This is to certify that i have checked the veracity, authenticity and completeness of all the requirements in support of the appointments attached herein.</td>
						<td class="td-border-thick-top td-border-thick-bottom td-border-thick-left td-border-thick-right f-size-12" style="text-align: justify; margin-left:10px;margin-right:10%">&emsp;&emsp;This is to certify that I have checked all the requirements in support of the appointments attachec herein and found this to be [ ] complete / [ ] lacking.</td>
					</tr>
					<tr>
						<td width="5%" height="50"></td>
						<td width="40%"></td>
						<td class=" td-border-thick-top td-border-thick-bottom td-border-thick-left td-border-thick-right td-center-middle f-size-12" ><br><br><br><br><?php echo strtoupper($reviewed_by['signatory_name']);?></b><br>
							&emsp;<i><?php echo $reviewed_by['position_name'] ?></i>&emsp;<br> <b>HRMO</b></td>
							<td class=" td-border-thick-top td-border-thick-bottom td-border-thick-left td-border-thick-right td-center-middle  f-size-12" ><br><br><br><br><br><br>CSC FO Receiving Officer</td>
						</tr>
					</table>
					<!-- LAST PAGE-->
					<!-- ************************************************************************** -->
				</body>
				</html>

<!-- PROBLEMS:

NO RECORD FOUND
1,2,3,4,5,6 -->
