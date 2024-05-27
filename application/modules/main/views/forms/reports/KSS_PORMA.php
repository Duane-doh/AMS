<?php 
	$month_tag = array(
		1 => 'Enero',
		2 => 'Pebrero',
		3 => 'Marso',
		4 => 'Abril',
		5 => 'Mayo',
		6 => 'Hunyo',
		7 => 'Hulyo',
		8 => 'Agosto',
		9 => 'Setyembre',
		10 => 'Oktubre',
		11 => 'Nobyembre',
		12 => 'Disyembre'
		);
	if($record['gender_code'] == 'F' && $record['civil_status_id'] == CIVIL_STATUS_MARRIED)
	{
		$label_eng = 'Mr./<b>Mrs.</b>/Ms.<b>:</b>';
	}
	elseif($record['gender_code'] == 'F' && $record['civil_status_id'] != CIVIL_STATUS_MARRIED)
	{
		$label_eng = 'Mr./Mrs./<b>Ms.</b><b>:</b>';
	}
	elseif($record['gender_code'] == 'M')
	{
		$label_eng = '<b>Mr.</b>/Mrs./Ms.<b>:</b>';
	}
	else
	{
		$label_eng = '<b>Mr.</b>/Mrs./Ms.<b>:</b>';
	}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<html>
<head>
	
	<title>Appointment Certificate</title>
	<link rel="stylesheet" href="<?php echo base_url().PATH_CSS ?>reports.css" type="text/css" />
</head>
 <style type="text/css">
	table{
		
/*		font-family: Tahoma, Geneva, sans-serif;*/
		font-family: Times New Roman;
	}
	.salary-container {
      max-width: 420px;
      overflow: hidden;
      text-overflow: ellipsis;
      white-space: nowrap;
      display: inline-block;
    }

    .font-size-9 {
      font-size: 9px;
    }
    .font-size-9 {
      font-size: 9px;
    }
	</style>
<body>
<p style="font-size: 9pt; text-align: right; font-family: times new roman; border: 1px solid; margin-left: 66%;" ><i><b>For Accredited/Deregulated Agencies</b></i><?php echo nbs(3)?></p>
<div class="border-solid" style="padding: 10px; background-color: gray;">
<div class="border-solid" style="padding: 10px; background-color: white;">
<table class="f-size-notice table-max" align="center">
    <tbody>
    	<tr>
			<td class="f-size-notice bold" colspan="3" style="font-size: 9pt;">CS Form No. 33-B<br>Revised 2018</td>
		</tr>
		<tr>
			<td height="" align="right" style="font-size: 9pt;"></td>
			<td height="" align="right" style="font-size: 9pt;"></td>
			<td height="" align="right" style="font-size: 9pt;"><i>(Stamp of Date of Receipt)</i></td>
		</tr>
		<br><br><br>
        <tr>
            <td align="right" width="30%"></td>
            <td class="f-size-notice" align="center" width=""><b>Republic of the Philippines</b><br><span class="" style="font-size:13pt;">DEPARTMENT OF HEALTH</span><br><span>Office of the Secretary</span><br>Manila</td>              
            <td width="30%"><br></td>
        </tr>
    </tbody>
</table>
<table class=" f-size-notice">
	<tr>
		<td height="30"></td>
	</tr>
	<tr>
		<td width="" height="20"><?php echo $label_tag; ?></td>
		<td></td>
	</tr>
	<tr>
		<td><?php echo $label_eng; ?></td>
		<td class="td-border-bottom" colspan=5 align="center" style="font-size: 11pt"><b>&emsp;<?php echo $record['employee_name']; ?>&emsp;</b></td>
	</tr>
	<tr>
		<td height="20"></td>
	</tr>
	<br><br><br>
</table>
<table class="bold" style="font-size: 10pt">
	<tr >
		<td width="40" height="20"></td>
		<td width="200">You are hereby appointed as </td>
		<td class="td-border-bottom" width="370" align="center"><b><?php echo strtoupper($record['position_name']);?></b></td>&nbsp; &nbsp;&nbsp;
		<td width="80"><b>&nbsp;&nbsp;(SG/JG/PG </b></td>
		<td class="td-border-bottom" width="30" align="center"><b><?php echo strtoupper($record['employ_salary_grade'] );?></b></td>
		<td width=""><b>) </b></td>
	</tr>
	<tr>
		<td></td>
		<td></td>
		<td style="font-size: 10px;"align="center"><b>(Position Title)</b></td>	
		<td></td>
	</tr>
	<tr>
		<td height="20"></td>
	</tr>
</table>
<table class="table-max  f-size-notice bold" style="font-size: 10pt">
	<tr>
		<td width="50" height="20" valign="bottom">under</td>
		<td class="td-border-bottom"  align="center" valign="bottom" style="font-size: 10pt;" width="150"><b>
		<?php
	      $emp_status = strtoupper($record['employment_status_name']);
	      
	      if (strlen($emp_status) > 45) {
	          echo '<div class="salary-container f-size-8">';
	      } elseif (strlen($emp_status) > 40) {
	      	  echo '<div class="salary-container f-size-9">';
	      } 
	      elseif (strlen($emp_status) > 35) {
	      	  echo '<div class="salary-container f-size-11">';
	      }  elseif (strlen($emp_status) > 30) {
	      	  echo '<div class="salary-container" style="font-size:11.5px;">';
	      } 
	      else {
	          echo '<div class="salary-container">';
	      }
	      ?>
	        <span><b><?php echo $emp_status; ?></b></span>
	        </div>
			<!-- <?php //echo strtoupper(convertTitleCase($record['employment_status_name'])); ?></b></td> -->
		<td width="100" align="center" valign="bottom">status at the &ensp;</td>
		<td class="td-border-bottom" width="" align="center" valign="bottom">
		<?php
	      $agency_name = strtoupper($record['agency_name']);
	      
	      if (strlen($agency_name) > 55) {
	          echo '<div class="salary-container" style="font-size:10.8px;">';
	      } 
	      elseif (strlen($agency_name) > 45) {
	      	  echo '<div class="salary-container" style="font-size:11.5px;">';
	      }
	      else {
	          echo '<div class="salary-container">';
	      }
	    ?>
	        <span><b><?php echo $agency_name; ?></b></span>
	        </div>
		</td>
	</tr>
	<tr>
		<td></td>
		<td style="font-size: 10px;" align="center"><b>(Permanent, Temporary, etc.)</b></td>
		<td style="font-size: 10px;"></td>
		<td style="font-size: 10px;" align="center"><b>(Office/Department/Unit)</b></td>
	</tr>
	<tr>
		<td height="30"></td>
	</tr>
</table>
<table class=" f-size-notice bold"  style="font-size: 10pt;border-collapse: collapse; width: 700px;">
	<tr>
		<td width= "200" height="20" valign="bottom">with a compensation rate of</td> 
		<td class="td-border-bottom" valign="bottom" width="420" align="center">
	      <?php
	      $salaryText = strtoupper($record['employ_monthly_salary_by_word']);
	      
	      if (strlen($salaryText) > 64) {
	          echo '<div class="salary-container font-size-9">';
	      } elseif (strlen($salaryText) > 60) {
	      	  echo '<div class="salary-container f-size-10">';
	      } 
	      elseif (strlen($salaryText) > 48) {
	      	  echo '<div class="salary-container f-size-11">';
	      }  elseif (strlen($salaryText) > 45) {
	      	  echo '<div class="salary-container f-size-12">';
	      } 
	      else {
	          echo '<div class="salary-container">';
	      }
	      ?>
	        <span><b><?php echo $salaryText; ?></b></span>
	      </div>
	    </td>
		<td width="40" valign="bottom"><b>&emsp;(P</b></td>
		<td class="td-border-bottom" min-width="100" align="center" valign="bottom"><b><?php echo number_format($record['employ_monthly_salary'],2) ;?></b></td>
		<td width="" valign="bottom"><b>) </b></td>
	</tr>
	<tr>
		<td height="20"></td>
	</tr>
</table>
<table class="table-max  f-size-notice bold"  style="font-size: 10pt">
	<tr> 
		<td width="" height="20" valign="bottom"> pesos per month.</td>
	</tr>
	<tr>
		<td height="30"></td>
	</tr>
</table>
<table class="table-max  f-size-notice bold"  style="font-size: 10pt">
	<tr>
		<td width="50" height="20"></td>
		<td width="230">The nature of this appointment is</td>
		<td class="td-border-bottom" width="" align="center" valign="bottom" style="font-size: 10pt"><b><?php echo $record['personnel_movement_name']; ?></b></td>
		<td width="20" align="right"> vice</td>
		<td class="td-border-bottom" width="" align="center"><b>
			<?php 
			$emp_status = strtoupper($record['employment_status_name']);
			if($emp_status == "CONTRACTUAL"){
				echo "N/A";
			}
			else{
				echo (!EMPTY($record['ex_employee_name']) ? $record['ex_employee_name'] : N_A);
			}?></b>
		</td>
	</tr>
	<tr>
		<td></td>
		<td style="font-size: 10px;"></td>
		<td style="font-size: 10px;" align="center">(Original, Promotion, etc.)</td>
		<td></td>
	</tr>
	<tr>
		<td height="30"></td>
	</tr>
</table>
<table class="table-max  f-size-notice bold"  style="font-size: 10pt">
	<tr>
		<td width="20" height="20">who</td>
		<td class="td-border-bottom" width="250" align="center"><b>
		<?php
			$emp_status = strtoupper($record['employment_status_name']);
			$separationText = (!EMPTY($record['separation_mode_name']) ? $record['separation_mode_name'] : N_A); 
	      if ($emp_status == "CONTRACTUAL") {
	      	echo "N/A";
	      }else{
			if($separationText == "PROMOTION" && $emp_status != "CONTRACTUAL"){
				echo"PROMOTED";
			}elseif ($separationText == "DEMOTION") {
				echo"DEMOTED";
			}elseif ($separationText == "REAPPOINTMENT") {
				echo "REAPPOINTED";
			}elseif ($separationText == "RECLASSIFICATION") {
				echo "RECLASSIFIED";
			}elseif ($separationText == "REEMPLOYMENT") {
				echo "REEMPLOYED";
			}elseif ($separationText == "REINSTATEMENT") {
				echo "REINSTATED";
			}elseif ($separationText == "TRANSFER") {
				echo "TRANSFERRED";
			}elseif ($separationText == "OPTIONAL RETIREMENT") {
				echo "RETIRED";
			}elseif ($separationText == "COMPULSORY RETIREMENT") {
				echo "RETIRED";
			}else{
				echo $separationText;
			}
		  }
	   	?>
	   	<?php 

		?>
		</b></td>
		<td width="" >Plantilla Item No.</td>
		<td class="td-border-bottom" width="300" align="center"><b>	
		<?php 
		$emp_status = strtoupper($record['employment_status_name']);
		if($emp_status == "CONTRACTUAL"){
			echo "N/A";
		}
		else{
			echo $record['plantilla_code'];
		}?>
	</b></td>
	</tr>
	<tr>
		<td style="font-size: 10px;"></td>
		<td style="font-size: 10px;" align="center">(Transferred, Retired, etc.)</td>
		<td style="font-size: 10px;"></td>
	</tr>
	<tr>
		<td height="30"></td>
	</tr>
</table>
<table class="table-max  f-size-notice bold"  style="font-size: 10pt">
	<tr>
		<td width="30">Page</td>
		<td class="td-border-bottom" width="120" align="center"><?php echo !EMPTY($record['plantilla_page']) ? strtolower($record['plantilla_page']) : N_A?></td>
		<td align="left">.</td>
	</tr>
	<tr>
		<td height="30"></td>
	</tr>
</table>
<table class="table-max  f-size-notice bold"  style="font-size: 10pt">
	<tr>
		<td width="30" height="20"></td>
		<td width = "50" style="font-size:10pt">This appointment shall take effect on the date of signing by the appointing officer/authority.</td>
	</tr>
	<tr>
		<td height="10"></td>
	</tr>
</table>
<?php if($probationary == "Y")
{
	echo"<table class='table-max  f-size-notice bold'  id='under_probationary' style='font-size: 10pt'>
	<tr>
		<td width='30' height='10'></td>
		<td width = '50' style='font-size:10pt;'><i>Under probationary for a period of six (6) months from _____________________ to _____________________.</i></td>
	</tr>
	<tr>
		<td height='30'></td>
	</tr>
</table>";
} ?>
<?php if($probationary == "N")
{
	echo"<table class='table-max  f-size-notice bold'  id='under_probationary' style='font-size: 10pt'>
	<tr>
		<td width='30' height='10'></td>
		<td width = '50' style='font-size:10pt;'></td>
	</tr>
	<tr>
		<td height='30'></td>
	</tr>
</table>";
} ?>

<!-- <table class="table-max f-size-notice bold" id="probationaryTable" style="font-size: 10pt">
    <tr id="probationaryTableRow">
        <td width="30" height="10"></td>
        <td width="50" style="font-size:10pt">
            <i>Under probationary for a period of six (6) months from
                <span id="start_date_placeholder">_____________________</span> 
                to <span id="end_date_placeholder">_____________________</span>.
            </i>
        </td>
    </tr>
    <tr>
        <td height="30"></td>
    </tr>
</table> -->
<br><br><br>
<!-- <table class="table-max  f-size-notice">
	<tr>
		<td width="40%"></td>
		<td class="f-size-notice"><b><?php //echo nbs(5)?> Very truly yours, </b></td>
	</tr>
	<tr>
		<td height="70"></td>
	</tr>
</table> -->

<table align="right">
	<tr>
		<td></td>
		<td align="center" class="f-size-notice" width = ""><b>Very truly yours, </b></td>
	</tr>
	<tr>
		<td height="70"></td>
	</tr>
	<tr>
		<td width=""></td>
		<td align="center" class=" f-size-notice bold"><?php echo strtoupper($certified_by['signatory_name']) ; ?></td>
	</tr>
	<tr>					
		<td></td>
		<td class="td-border-bottom  f-size-notice" align="center"><?php echo $certified_by['position_name'] ?><br> <?php echo $certified_by['office_name']?></td>
	</tr>
	<tr>					
		<td></td>
		<!-- <td class=" f-size-notice" align="center"><b>Appointing/Officer/Authority</b><br><br><u><?php echo nbs(30);?></u><br><b>Date of Signing</b><br></td> -->
		<td class=" f-size-notice" align="center"><b>Appointing/Officer/Authority</b></td>
	</tr>
	<tr>
		<td><br><br><br></td>
	</tr>
	<tr>
		<td></td>
		<td class=" f-size-notice" align="center">_____________________________________</td>
	</tr>
	<tr>
		<td></td>
		<td class=" f-size-notice" align="center"><b>Date of Signing</b><br></td>
	</tr>
</table>

<table class="table-max  f-size-notice bold"  style="font-size: 7pt">
	<tr>
		<td height="10">Accredited/Deregulated Pursuant to</td>
	</tr>
	<tr>
		<td height="10">CSC Resolution No.<u>1900961</u>,s.<u>2019</u> </td>
	</tr>
	<tr>
		<td height="10">dated <u>August 27, 2019</u></td>
	</tr>
</table>
<br><br><br><br><br>
<table class="table-max  f-size-notice"  style="font-size: 10pt">
	<tr>
		<td height="10" class="f-size-12"><i><?php echo nbs(30)?>DRY</i></td>
		<td height="10" align="right" class="f-size-12"><i>(Stamp of Date of Release)</i></td>
	</tr>
	<tr>
		<td height="30" class="f-size-12"><i><?php echo nbs(30)?>SEAL</i></td>
	</tr>
</table>
<br><br>
</div>
</div><!-- ncocampo -->
<!-- SECOND PAGE TABLE 1 -->
<div class="border-solid" style="padding: 10px; background-color: gray;">
<!-- <table>
	<tr>
		<td height="25"></td>
	</tr>
</table> -->
<table class="table-max f-size-notice" style="margin-bottom: 10px; background-color: white;">
	<br><br><br><br>
	<tr>
		<td class="border-solid f-size-notice" style="padding-left: 20px; padding-right: 20px;">
			<table class="table-max">
				<tr>
					<td class="bold  f-size-18" align="center" width="" height="50" valign=sub>Certification</td>
				</tr>
				<tr>
					<td class=" f-size-notice" height="50" align="justify"><?php echo nbs(10)?>This is to certify that all requirements and supporting papers pursuant to <b>CSC MC No. 24, s. 2017, as amended, </b>have been complied with, reviewed and found to be in order.</td>
				</tr>
				<tr>
					<td class=" f-size-notice" height="10"></td>
				</tr>
				<tr>
					<td class=" f-size-notice" style="text-align: justify; display: block; line-height: 100px;" ><?php echo nbs(10)?>The position was published at the<u><b> <?php echo !EMPTY($record['publication_place']) ? strtoupper($record['publication_place']) : N_A?></b></u> from <b><u><?php echo !EMPTY($record['publication_date']) ? strtoupper($record['publication_date']) : N_A?></u></b> to <b><u><?php echo !EMPTY($record['publication_date_to']) ? strtoupper($record['publication_date_to']) : N_A?></u></b><?php echo nbs(1)?> and posted in the <u><b><?php echo !EMPTY($record['posted_in']) ? strtoupper($record['posted_in']) : N_A?></b></u> from <b><u><?php echo !EMPTY($record['publication_date']) ? strtoupper($record['publication_date']) : N_A?></u></b> to <b><u><?php echo !EMPTY($record['publication_date_to']) ? strtoupper($record['publication_date_to']) : N_A?></u></b><?php echo nbs(2)?> in consonance with RA No.7041. The assessment by the Human Resource Merit Promotion and Selection Board (HRMPSB) started on  <b><u><?php echo !EMPTY($record['hrmpsb_date']) ? strtoupper($record['hrmpsb_date']) : N_A?></u></b>.<?php echo nbs(2)?></td>
				</tr>
				<tr>
					<td class=" f-size-notice" height="60"></td>
				</tr>
				<tr>
					<td>
						<table align="right">
							<tr>
								<td width=""></td>
								<td align="center" class=" f-size-notice bold"><?php echo strtoupper($prepared_by['signatory_name'])?></td>
							</tr>
							<tr>					
								<td></td>
								<td class="td-border-bottom  f-size-notice" align="center"><?php echo $prepared_by['position_name']?><br> <?php echo $prepared_by['office_name']?></td>
							</tr>
							<tr>					
								<td></td>
								<td class=" f-size-notice" align="center"><b>HRMO</b><br><br></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>

<!-- SECOND PAGE TABLE 2 -->
<table class="table-max f-size-notice" style="font-size: 10pt; background-color: white;">
	<tr>
		<td class="border-solid f-size-notice" style="padding-left: 20px; padding-right: 20px;">
			<table class="table-max">
				<tr>
					<td class="bold  f-size-18" align="center" width="" height="50" valign="sub">Certification</td>
				</tr>
				<tr>
					<td class=" f-size-notice" align="justify" style="line-height:200%;"><?php echo nbs(10)?>This is to certify that the appointee has been screened and found qualified by the majority of the HRMPSB/Placement Committee during the deliberation held on <b><u><?php echo !EMPTY($record['deliberation_date']) ? strtoupper($record['deliberation_date']) : N_A?></u></b>.</td>
				</tr>
				<tr>
					<td class=" f-size-notice" height="60"></td>
				</tr>
				<tr>
					<td>
						<table align="right">
							<tr>
								<td width=""></td>
								<td align="center" class="td-border-bottom f-size-notice">
									<b><?php echo !EMPTY($reviewed_by['signatory_name']) ? strtoupper($reviewed_by['signatory_name']) : N_A?></b><br>
									<?php echo $reviewed_by['position_name'] ?><br>
									<?php echo $reviewed_by['office_name'] ?></td>
							</tr>
							<tr>					
								<td></td>						
								<td class=" f-size-notice" align="center">
								<?php 
								if($signatory_title == VICE_CHAIRPERSON)
								{
									echo"<b>Vice-Chairperson, HRMPSB/Placement Committee</b>";
								}
								else{
									echo"<b>Chairperson, HRMPSB/Placement Committee</b>";
								}
								?>
									
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<!-- <tr>
					<td class=" f-size-notice" height="10"></td>
				</tr> -->
			</table>
		</td>
	</tr>
</table>
</div>

<!-- SECOND PAGE TABLE 3 -->
<!-- <table class="table-max f-size-notice" style="margin-bottom: 10px;">
	<tr>
		<td class=" f-size-notice" height="10"></td>
	</tr>
	<tr>
		<td class="border-kss f-size-notice" style="padding-left: 20px; padding-right: 20px;">
			<table class="table-max">
				<tr>
					<td width=""></td>
					<td class="bold f-size-18" align="center" width="800" height="50" valign=sub>CSC/HRMO Notation</td>
				</tr> -->
				<!-- <tr>
					<td class="td-border-bottom  f-size-notice" height="20"><?php //echo ($record['employ_personnel_movement_id'] == $movt_original['sys_param_value']) ? 'UNDER PROBATIONARY FOR A PERIOD OF (6) MONTHS.' : '' ?></td>
				</tr> -->	
			<!-- </table>		
		</td>
	</tr>
</table> -->



<table class="table-max  f-size-notice" style="margin-bottom: 10px;">
	<tr>
		<td class=" f-size-notice" height="20"></td>
	</tr>
	<tr>
		<td class="border-solid f-size-notice" style="padding-left: 20px; padding-right: 20px; background-color: gray;">
			<table class="table-max">
				<tr>
					<td><br>
						<table class="table-max" align="center">
							<tr>
								
								<td class="bold f-size-18" height="30" valign=sub>CSC/HRMO Notation</td>
							</tr>
						</table>	
						<table class="table-max  border-solid">
							<tr style="background-color: white;">
								<td>
									<table class="table-max f-size-notice">
										<tr>
											<td class="border-solid mid bold" colspan=4 width="600" height="50">ACTION ON APPOITMENTS</td>
											<td class="border-solid mid bold" colspan=2 width="200">Recorded by</td>
										</tr>
										<tr>
											<tr>
												<td class="border-solid bold" colspan=4 width="540" height="40"><?php echo nbs(3)?> &#9633;  Validated per RAI for the month of ___________________________________________ </td>
												<td class="border-solid mid bold" colspan=2 width="200"></td>
											</tr>
											<tr>
												<td class="border-solid bold" colspan=4 width="540" height="40"><?php echo nbs(3)?> &#9633;  Invalidated per CSCRO/FO letter dated _______________________________________</td>
												<td class="border-solid mid bold" colspan=2 width="200"></td>
											</tr>
										</tr>
										<tr>
											<td class="border-solid bold" width="270" height="40"><?php echo nbs(3)?> &#9633; Appeal</td>
											<td class="border-solid mid bold" width="135">DATE FILED</td>
											<td class="border-solid mid bold" width="135">STATUS</td>
											<td class="border-solid" colspan=4></td>

										</tr>
										<tr>
											<td class="border-solid " width="270" height="40"><?php echo nbs(9)?>  &#9633; CSCRO/CSC-Commission</td>
											<td class="border-solid mid bold" width="135"></td>
											<td class="border-solid mid bold" width="135"></td>
											<td class="border-solid" colspan=4></td>

										</tr>
										<tr>
											<td class="border-solid bold" width="270" height="40"><?php echo nbs(3)?> &#9633; Petition for Review</td>
											<td class="border-solid mid bold" width="135"></td>
											<td class="border-solid mid bold" width="135"></td>
											<td class="border-solid" colspan=4></td>

										</tr>
										<tr>
											<td class="border-solid " width="270" height="40"><?php echo nbs(9)?> &#9633; CSC-Commission</td>
											<td class="border-solid mid bold" width="135"></td>
											<td class="border-solid mid bold" width="135"></td>
											<td class="border-solid" colspan=4></td>

										</tr>
										<tr>
											<td class="border-solid " width="270" height="40"><?php echo nbs(9)?> &#9633; Court of Appeals</td>
											<td class="border-solid mid bold" width="135"></td>
											<td class="border-solid mid bold" width="135"></td>
											<td class="border-solid" colspan=4></td>

										</tr>
										<tr>
											<td class="border-solid " width="270" height="40"><?php echo nbs(9)?> &#9633; Supreme Court</td>
											<td class="border-solid mid bold" width="135"></td>
											<td class="border-solid mid bold" width="135"></td>
											<td class="border-solid" colspan=4></td>

										</tr>
									</table>
								</td>		
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
			
			


<!-- SECOND PAGE TABLE 4 -->

<!-- SECOND PAGE TABLE 6 -->
<table class="table-max  f-size-notice" style="margin-bottom: 20px;">
	<tr>
		<td class="border-solid f-size-notice" style="padding-left: 20px; padding-right: 20px; background-color: gray;">
			<table class="table-max">
				<tr>
					<td><br>
						<table class="border-solid">
							<tr  class="border-solid" style="background-color: white;">
										<td height="20" class="border-solid f-size-notice" width="500"><br><?php echo nbs(5)?>Original Copy - for the Appointee<br><?php echo nbs(5)?>Original Copy - for the Civil Service Commission<br><?php echo nbs(5)?>Original Copy - for the Agency<br><br></td>
										<td height="10" class="mid border-solid f-size-notice" width="500"><b>Acknowledgement</b><br><br><i><?php echo nbs(8)?>Received original/photocopy of appointment on _________________<br><br></i><u><b><?php echo nbs(9)?><?php echo $record['employee_name']; ?><?php echo nbs(9)?></b></u><br>Appointee</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
</body>
</html>
