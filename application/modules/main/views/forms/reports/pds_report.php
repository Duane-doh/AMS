<?php
// IDENTIFICATION INFORMATION
$tin_val = '';
$tin_format = '';
$sss_val = '';
$sss_format = '';
$gsis_val = '';
$gsis_format = '';
$pagibig_val = '';
$pagibig_format = '';
$philhealth_val = '';
$philhealth_format = '';
$permanent_no = '';
$email = '';
$residential_no = '';
$mobile_no = '';
// CONTACTS VALUE
foreach ( $contact_info as $contact ) {
	switch ($contact ['contact_type_id']) {
		case PERMANENT_NUMBER :
			$permanent_no = $contact ['contact_value'];
			break;
		case EMAIL :
			$email = $contact ['contact_value'];
			break;
		case RESIDENTIAL_NUMBER :
			$residential_no = $contact ['contact_value'];
			break;
		case MOBILE_NUMBER :
			$mobile_no = $contact ['contact_value'];
			break;
	}
}
// IDENTIFICATION VALUE
foreach ( $identification_info as $identification ) {
	switch ($identification ['identification_type_id']) {
		case TIN_TYPE_ID :
			$tin_val = $identification ['identification_value'];
			break;
		case SSS_TYPE_ID :
			$sss_val = $identification ['identification_value'];
			break;
		case GSIS_TYPE_ID :
			$gsis_val = $identification ['identification_value'];
			break;
		case PAGIBIG_TYPE_ID :
			$pagibig_val = $identification ['identification_value'];
			break;
		case PHILHEALTH_TYPE_ID :
			$philhealth_val = $identification ['identification_value'];
			break;
	}
}
// IDENTIFICATION FORMAT
foreach ( $identification_format as $format ) {
	switch ($format ['identification_type_id']) {
		case TIN_TYPE_ID :
			$tin_format = $format ['format'];
			break;
		case SSS_TYPE_ID :
			$sss_format = $format ['format'];
			break;
		case GSIS_TYPE_ID :
			$gsis_format = $format ['format'];
			break;
		case PAGIBIG_TYPE_ID :
			$pagibig_format = $format ['format'];
			break;
		case PHILHEALTH_TYPE_ID :
			$philhealth_format = $format ['format'];
			break;
	}
}
$work_exp_counter = 0;
$govt_exam_counter = 0;
$vol_details_counter = 0;
$train_details_counter = 0;
$skills_list_counter = 0;
$member_list_counter = 0;
$recog_list_counter = 0;
$educ_details_counter = 0;
$space = '&nbsp;';
?>
<html>
<head>

<title>Personal Data Sheet</title>
<link rel="stylesheet"
	href="<?php echo base_url().PATH_CSS ?>reports.css" type="text/css" />
</head>
<style>
table{
	font-size: 9px;
	font-family: Arial Narrow,Arial,sans-serif; 
}
</style>
<body>
<!-- ********************** PAGE 1 ********************** -->

	<!-- ********************** HEADER ********************** -->
	<table class="table-max"> 
		<tr>
			<td class="border-thick" height="2.5" colspan=3></td>
		</tr>
		<tr>
			<td class="b-t-n-b f-size-11 bold italic" colspan=3>CS Form No. 212</td>
		</tr>
		<tr>
			<td class="b-t-lr f-size-9 bold italic" colspan=3>Revised 2017</td>
		</tr>
		<tr>
			<td class="b-t-lr mid f-size-22pt bold" colspan=3>PERSONAL DATA SHEET</td>
		</tr>
		<tr>
			<td class="b-t-lr bold italic" colspan=3>WARNING: Any misrepresentation made in the Personal Data Sheet and the Work Experience Sheet shall cause the filing of administrative/criminal case/s against the person concerned.</td>
		</tr>
		<tr>
			<td class="b-t-lr bold italic" colspan=3>READ THE ATTACHED GUIDE TO FILLING OUT THE PERSONAL DATA SHEET (PDS) BEFORE ACCOMPLISHING THE PDS FORM.</td>
		</tr>
		<tr>
			<td class="b-t-lr" colspan=3>
				<table class="table-max">
					<tr>
						<td class="td-border-thick-bottom" width="555">Print legibly. Tick appropriate boxes (&#9633;) and use separate sheet if necessary. Indicate N/A if not applicable. <b>DO NOT ABBREVIATE.</b></td>
						<td class="border-solid bg-header" height="15">1. CS ID No.</td>
						<td class="td-border-top td-border-bottom align-r" width="154">(Do not fill up. For CSC use only)</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td class="b-t-lr" height="1.5" colspan=3></td>
		</tr>
	</table>

	<!-- ********************** PERSONAL INFO START ********************** -->
	<table class="table-max">
		<tr>
			<td colspan=4 class="border-thick bg-header text-white f-size-11 bold italic" height="20">I. PERSONAL INFORMATION</td>
		</tr>
		<tr>
			<td class="b-t-l bg-sub" width="125" height="20">&nbsp;2. SURNAME</td>
			<td class="border-solid b-t-r f-size-10" colspan=3>&nbsp;<?php echo !EMPTY($personal_info['last_name']) ? strtoupper($personal_info['last_name']):N_A?></td>
		</tr>
		<tr>
			<td class="b-t-l bg-sub" height="20">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;FIRSTNAME</td>
			<td class="b-s-l b-s-r f-size-10" width="430">&nbsp;<?php echo !EMPTY($personal_info['first_name']) ? strtoupper($personal_info['first_name']):N_A?></td>
			<td class="f-size-7 align-l v-top bg-sub">NAME EXTENSION (JR., SR)</td>
			<td class="b-t-r f-size-10 bg-sub"><?php echo !EMPTY($personal_info['ext_name']) ? strtoupper($personal_info['ext_name']):N_A?></td> 
		<tr>
			<td class="b-t-l b-t-b bg-sub" height="20">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;MIDDLE NAME</td>
			<td class="border-solid b-t-r b-t-b f-size-10" colspan=3>&nbsp;<?php echo !EMPTY($personal_info['middle_name']) ? strtoupper($personal_info['middle_name']):N_A?></td>
		</tr>		
	</table>
	<table class="table-max">
		<tr>
			<td class="b-t-l bg-sub" width="125">&nbsp;3. DATE OF BIRTH</td>
			<td class="b-s-l b-s-b f-size-10" rowspan=2 width="172.5">&nbsp;<?php echo !EMPTY($personal_info['birth_date']) ? ($personal_info['birth_date']):N_A?></td>
			<td class="b-t-l b-s-r v-top bg-sub" rowspan=2 width="160">16. CITIZENSHIP</td>
			<td class="b-t-r f-size-10">&nbsp;<?php echo ! empty($personal_info['citizenship_name']) ? 'x' : '&#9633;' ?>&nbsp;Filipino&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo (strtolower($personal_info['citizenship_name']) == "filipino") ? '&#9633;' : 'x' ?>&nbsp;Dual Citizenship</td>
		</tr>	
		<tr>
			<td class="b-s-b b-t-l v-top bg-sub" height="40">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(mm/dd/yyyy)</td>
			<td class="b-t-r">
				<table>
					<tr>
						<td width="80">&nbsp;</td>
						<?php
						if (! EMPTY ( $citizenship_basis )) {
							foreach ( $citizenship_basis as $basis ) :
								$box = ($personal_info ['citizenship_basis_id'] == $basis['sys_param_value']) ? "x " : "&#9633;";
								?>
							<td class="f-size-10">&nbsp;<?php echo $box ?>&nbsp;<?php echo $basis['sys_param_name'] ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
						<?php
							endforeach;
						}
						?>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td class="b-t-l bg-sub" height="20">&nbsp;4. PLACE OF BIRTH</td>
			<td class="b-s-l b-s-b f-size-10">&nbsp;<?php echo !EMPTY($personal_info['birth_place']) ? ($personal_info['birth_place']):N_A?></td>
			<td class="b-t-l b-s-r mid bg-sub">If holder of  dual citizenship,</td>
			<td class="b-t-r align-r f-size-10">Pls. indicate country:<?php echo str_repeat($space, 30)?></td>
		</tr>
		<tr>
			<td class="b-t-l b-s-b b-s-t bg-sub" height="20">&nbsp;5. SEX</td>
			<td class="b-s-l b-s-b f-size-10">&nbsp;
				<table>
					<tr>
						<?php
						if (! EMPTY ( $per_params ['gender'] )) {
							foreach ( $per_params ['gender'] as $gen ) :
								$box = ($personal_info ['gender_code'] == $gen ['gender_code']) ? "x " : "&#9633;";
								?>
							<td class="f-size-10">&nbsp;<?php echo $box ?>&nbsp;<?php echo $gen['gender'] ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
						<?php
							endforeach
							;
						}
						?>
					</tr>
				</table>
			</td>
			<td class="b-t-l b-s-r b-s-b v-top align-c bg-sub">please indicate the details.</td>
			<td class="b-t-r b-s-b f-size-10 b-s-t">&nbsp;<?php echo (strtolower($personal_info['citizenship_name']) != 'filipino') ? strtoupper($personal_info['country_name']) : ''?></td>
		</tr>
	</table>
	<table class="table-max">		
		<tr>
			<td class="b-t-l b-s-b bg-sub" rowspan=4  width="125">&nbsp;6. CIVIL STATUS</td>
			<td class="b-s-l b-s-b f-size-10" rowspan=4 width="172.5">
				<table class="table-max f-size-10">
					<tr>
						<td width="80">&nbsp;<?php echo ($civil_status[0]['civil_status_id'] == $personal_info['civil_status_id']) ? 'x' : '&#9633;' ?>&nbsp;Single</td>
						<td>&nbsp;<?php echo ($civil_status[1]['civil_status_id'] == $personal_info['civil_status_id']) ? 'x' : '&#9633;' ?>&nbsp;Married</td>
					</tr>
					<tr>
						<td>&nbsp;<?php echo ($civil_status[2]['civil_status_id'] == $personal_info['civil_status_id']) ? 'x' : '&#9633;' ?>&nbsp;Widowed</td>
						<td>&nbsp;<?php echo ($civil_status[3]['civil_status_id'] == $personal_info['civil_status_id']) ? 'x' : '&#9633;' ?>&nbsp;Separated</td>
					</tr>
					<tr>
						<td colspan="2">&nbsp;<?php echo ($civil_status[4]['civil_status_id'] == $personal_info['civil_status_id']) ? 'x' : '&#9633;' ?>&nbsp;Other/s: &nbsp;<u><?php echo $personal_info['other_civil_status'] ?></u></td>
					</tr>
					
				</table>
			</td>
			<td class="b-t-l b-s-r v-top bg-sub" rowspan=4 width="118.5">17. RESIDENTIAL ADDRESS</td>
			<td class="mid f-size-10" height="20" width="172"><?php echo !EMPTY($residential_house_no) ? ($residential_house_no):N_A?></td>
			<td class="b-t-r mid f-size-10" width="173"><?php echo !EMPTY($residential_street) ? ($residential_street):N_A?></td>
		</tr>
		<tr>
			<td class="b-s-b b-s-t mid italic" width="10">House/Block/Lot No.</td>
			<td class="b-s-b b-s-t b-t-r mid italic" width="10">Street</td>
		</tr>
		<tr>
			<td class="b-s-b f-size-10 mid italic" height="20"><?php echo !EMPTY($residential_subdivision) ? ($residential_subdivision):N_A?></td>
			<td class="b-s-b b-t-r f-size-10 mid italic"><?php echo !EMPTY($residential_address_info['barangay_name']) ? ($residential_address_info['barangay_name']):N_A?></td>
		</tr>
		<tr>
			<td class="b-s-b mid italic">Subdivision/Village</td>
			<td class="b-s-b b-t-r mid italic">Barangay</td>
		</tr>
		<tr>
			<td class="b-t-l b-s-b b-s-t bg-sub" rowspan=2 height="20">&nbsp;7. HEIGHT (m)</td>
			<td class="b-s-l b-s-b f-size-10" rowspan=2>&nbsp;<?php echo !EMPTY($personal_info['height']) ? ($personal_info['height']):N_A?></td>
			<td class="b-t-l b-s-r v-top align-c bg-sub" rowspan=2></td>
			<td class="mid f-size-10" height="20">

				<?php 
				if($residential_address_info['province_code'] =='13806'){
					echo $residential_address_info['municity_name']. ", CITY OF MANILA";
				}else{
					echo $residential_address_info['municity_name'];
				}
				?>
			
			</td>
			<td class="b-t-r mid f-size-10">
				<?php 
				if($residential_address_info['region_code'] =='13'){
					echo "METRO MANILA";
				}else{
					echo $residential_address_info['province_name'];
				}
				?></td>
		</tr>
		<tr>
			<td class="b-s-b b-s-t mid italic">City/Municipality</td>
			<td class="b-s-b b-s-t b-t-r mid italic">Province</td>
		</tr>
		<tr>
			<td class="b-t-l b-s-b bg-sub" height="20">&nbsp;8. WEIGHT (kg)</td>
			<td class="b-s-l b-s-b f-size-10">&nbsp;<?php echo !EMPTY($personal_info['weight']) ? ($personal_info['weight']):N_A?></td>
			<td class="b-t-l b-s-b b-s-r mid bg-sub">ZIP CODE</td>
			<td class="b-t-r b-s-b f-size-10" colspan=2>&nbsp;<?php echo !EMPTY($residential_address_info['postal_number'])? strtoupper($residential_address_info['postal_number']):N_A ?></td>
		</tr>
		<tr>
			<td class="b-t-l b-s-b bg-sub" rowspan=2 height="20">&nbsp;9. BLOOD TYPE</td>
			<td class="b-s-l b-s-b f-size-10" rowspan=2>&nbsp;<?php echo !EMPTY($personal_info['blood_type_name']) ? ($personal_info['blood_type_name']):N_A?></td>
			<td class="b-t-l b-s-r v-top bg-sub" rowspan=2>&nbsp;18. PERMANENT ADDRESS</td>
			<td class="mid f-size-10" height="20"><?php echo !EMPTY($permanent_house_no) ? ($permanent_house_no):N_A?></td>
			<td class="b-t-r mid f-size-10"><?php echo !EMPTY($permanent_street) ? ($permanent_street):N_A?></td>
		</tr>
		<tr>
			<td class="b-s-b b-s-t mid italic">House/Block/Lot No.</td>
			<td class="b-s-b b-s-t b-t-r mid italic">Street</td>
		</tr>
		<tr>
			<td class="b-t-l b-s-b bg-sub" rowspan=2 height="20">&nbsp;10. GSIS ID NO.</td>
			<td class="b-s-l b-s-b f-size-10" rowspan=2>&nbsp;<?php echo !EMPTY($gsis_val)? (is_numeric($gsis_val)?format_identifications($gsis_val,$gsis_format) : $gsis_val) : N_A ?></td>
			<td class="b-t-l b-s-r v-top bg-sub" rowspan=2></td>
			<td class="mid f-size-10" height="20"><?php echo !EMPTY($permanent_subdivision) ? ($permanent_subdivision):N_A?></td>
			<td class="b-t-r mid f-size-10"><?php echo !EMPTY($permanent_address_info['barangay_name']) ? ($permanent_address_info['barangay_name']):N_A?></td>
		</tr>
		<tr>
			<td class="b-s-b b-s-t mid italic">Subdivision/Village</td>
			<td class="b-s-b b-t-r b-s-t mid italic">Barangay</td>
		</tr>
		<tr>
			<td class="b-t-l b-s-b b-s-t bg-sub" rowspan=2 height="20">&nbsp;11. PAG-IBIG ID NO.</td>
			<td class="b-s-l b-s-b f-size-10" rowspan=2>&nbsp;<?php echo !EMPTY($pagibig_val)? (is_numeric($pagibig_val)? format_identifications($pagibig_val,$pagibig_format) : $pagibig_val) : N_A ?></td>
			<td class="b-t-l b-s-r v-top align-c bg-sub" rowspan=2></td>
			<td class="mid f-size-10" height="20">
				<?php 
				if($permanent_address_info['province_code'] =='13806'){
					echo $permanent_address_info['municity_name']. ", CITY OF MANILA";
				}else{
					echo $permanent_address_info['municity_name'];
				}
				?></td>
			<td class="b-t-r mid f-size-10">
				<?php 
				if($permanent_address_info['region_code'] =='13'){
					echo "METRO MANILA";
				}else{
					echo $permanent_address_info['province_name'];
				}
				?></td>
		</tr>
		<tr>
			<td class="b-s-b b-s-t mid italic">City/Municipality</td>
			<td class="b-s-b b-s-t b-t-r mid italic">Province</td>
		</tr>
		<tr>
			<td class="b-t-l b-s-b bg-sub" height="20">&nbsp;12. PHILHEALTH NO.</td>
			<td class="b-s-l b-s-b f-size-10">&nbsp;<?php echo !EMPTY($philhealth_val)? (is_numeric($philhealth_val)?format_identifications($philhealth_val,$philhealth_format) : $philhealth_val) : N_A ?></td>
			<td class="b-t-l b-s-b b-s-r mid bg-sub">ZIP CODE</td>
			<td class="b-t-r b-s-b f-size-10" colspan=2>&nbsp;<?php echo !EMPTY($permanent_address_info['postal_number'])? strtoupper($permanent_address_info['postal_number']):N_A ?></td>
		</tr>
	</table>
	<table class="table-max">
		<tr>
			<td class="b-t-l b-s-b bg-sub" width="125" height="20">&nbsp;13. SSS NO.</td>
			<td class="b-s-l b-s-b f-size-10" width="172.5">&nbsp;<?php echo !EMPTY($sss_val)? (is_numeric($sss_val)?format_identifications($sss_val,$sss_format) : $sss_val) : N_A ?></td>
			<td class="b-t-l b-s-b b-s-r bg-sub" width="118.5">&nbsp;19. TELEPHONE NO.</td>
			<td class="b-t-r b-s-b f-size-10">&nbsp;<?php echo !EMPTY($permanent_no)? format_identifications($permanent_no, TELEPHONE_FORMAT): N_A ?></td>
		</tr>	
		<tr>
			<td class="b-t-l b-s-b bg-sub" height="20">&nbsp;14. TIN NO.</td>
			<td class="b-s-l b-s-b f-size-10">&nbsp;<?php echo !EMPTY($tin_val)? format_identifications($tin_val, $tin_format) : N_A ?></td>
			<td class="b-t-l b-s-b b-s-r bg-sub">&nbsp;20. MOBILE NO.</td>
			<td class="b-t-r b-s-b f-size-10">&nbsp;<?php echo !EMPTY($mobile_no)? format_identifications($mobile_no, CELLPHONE_FORMAT): N_A ?></td>
		</tr>	
		<tr>
			<td class="b-t-l bg-sub" height="20">&nbsp;15. AGENCY EMPLOYEE NO.</td>
			<td class="b-s-l f-size-10">&nbsp;<?php echo !EMPTY($personal_info['agency_employee_id']) ? $personal_info['agency_employee_id']:N_A?></td>
			<td class="b-t-l b-s-r bg-sub">&nbsp;21. E-MAIL ADDRESS<span class="f-size-8"> (if any)</span></td>
			<td class="b-t-r f-size-10">&nbsp;<?php echo !EMPTY($email)? strtolower($email):N_A ?></td>
		</tr>	
	</table>
	<!-- ********************** PERSONAL INFO END ********************** -->

	<!-- ********************** FAMILY INFO START ********************** -->
	<table class="table-max">
		<tr>
			<td class="border-thick bg-header text-white f-size-11 bold italic" height="20">II. FAMILY BACKGROUND</td>
		</tr>
	</table>
	<div style="width: 100%;">
		<div style="width: 55%; border-style: solid; border-width: 0; margin: 0px; float: left;">
			<table class="table-max">
			<?php
			if (! EMPTY ( $spouse )) {
				foreach ( $spouse as $sp ) :
					$spouse_first 	= strtoupper ( $sp ['relation_first_name'] );
					$spouse_last 	= strtoupper ( $sp ['relation_last_name'] );
					$spouse_mid 	= strtoupper ( $sp ['relation_middle_name'] );
					$spouse_ext 	= strtoupper ( $sp ['relation_ext_name'] );
					$spouse_occ 	= strtoupper ( $sp ['relation_occupation'] );
					$spouse_emp 	= strtoupper ( $sp ['relation_company'] );
					$spouse_addr 	= strtoupper ( $sp ['relation_company_address'] );
					$spouse_con 	= strtoupper ( $sp ['relation_contact_num'] );
				endforeach;
			}
			?>
			<tr>
				<td class="b-t-l b-s-r bg-sub" height="20" width="29.5%">&nbsp;22. SPOUSE'S SURNAME</td>
				<td class="b-s-b b-s-r f-size-10" colspan=2>&nbsp;<?php echo !EMPTY($spouse_last) ? $spouse_last : N_A ?></td>
			</tr>
			<tr>
				<td class="b-t-l b-s-r bg-sub" height="20">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;FIRST NAME</td>
				<td class="b-s-b b-s-r f-size-10">&nbsp;<?php echo !EMPTY($spouse_first) ? $spouse_first : N_A ?></td>
				<td class="b-s-b b-s-r f-size-7 align-l v-top bg-sub" width="120">&nbsp;NAME EXTENSION (JR., SR)&nbsp;&nbsp;<span class="f-size-10"><?php echo !EMPTY($spouse_ext) ? $spouse_ext : N_A ?></span></td>
			</tr>
			<tr>
				<td class="b-t-l b-s-r b-s-b bg-sub" height="20">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;MIDDLE NAME</td>
				<td class="b-s-b b-s-r f-size-10" colspan=2>&nbsp;<?php echo !EMPTY($spouse_mid) ? $spouse_mid : N_A ?></td>
			</tr>
			<tr>
				<td class="b-t-l b-s-r b-s-b bg-sub" height="20">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;OCCUPATION</td>
				<td class="b-s-b b-s-r f-size-10" colspan=2>&nbsp;<?php echo !EMPTY($spouse_occ) ? $spouse_occ : N_A ?></td>
			</tr>
			<tr>
				<td class="b-t-l b-s-r b-s-b bg-sub" height="20">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;EMPLOYER/BUS. NAME</td>
				<td class="b-s-b b-s-r f-size-10" colspan=2>&nbsp;<?php echo !EMPTY($spouse_emp) ? $spouse_emp : N_A ?></td>
			</tr>
			<tr>
				<td class="b-t-l b-s-r b-s-b bg-sub" height="20">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;BUSINESS ADDRESS</td>
				<td class="b-s-b b-s-r f-size-10" colspan=2>&nbsp;<?php echo !EMPTY($spouse_addr) ? $spouse_addr : N_A ?></td>
			</tr>
			<tr>
				<td class="b-t-l b-s-r b-s-b bg-sub" height="20">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;TELEPHONE NO.</td>
				<td class="b-s-b b-s-r f-size-10" colspan=2>&nbsp;<?php echo !EMPTY($spouse_con) ? $spouse_con : N_A ?></td>
			</tr>

			<?php			
				if (! EMPTY ( $father )) {
				foreach ( $father as $ft ) :
					$father_first 	= strtoupper ( $ft ['relation_first_name'] );
					$father_last 	= strtoupper ( $ft ['relation_last_name'] );
					$father_mid 	= strtoupper ( $ft ['relation_middle_name'] );
					$father_ext 	= strtoupper ( $ft ['relation_ext_name'] );
				endforeach;
				}
			?>
			<tr>
				<td class="b-t-l b-s-r bg-sub" height="20">&nbsp;24. FATHER'S NAME</td>
				<td class="b-s-b b-s-r f-size-10" colspan=2>&nbsp;<?php echo !EMPTY($father_last) ? $father_last : N_A ?></td>
			</tr>
			<tr>
				<td class="b-t-l b-s-r bg-sub" height="20">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;FIRST NAME</td>
				<td class="b-s-b b-s-r f-size-10" >&nbsp;<?php echo !EMPTY($father_first) ? $father_first : N_A  ?></td>
				<td class="b-s-b b-s-r f-size-7 align-l v-top bg-sub" width="120">&nbsp;NAME EXTENSION (JR., SR)&nbsp;&nbsp;<span class="f-size-10"><?php echo !EMPTY($father_ext) ? $father_ext : N_A ?></span></td>
			</tr>
			<tr>
				<td class="b-t-l b-s-b b-s-r bg-sub" height="20">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;MIDDLE	NAME</td>
				<td class="b-s-b b-s-r f-size-10" colspan=2>&nbsp;<?php echo !EMPTY($father_mid) ? $father_mid : N_A ?></td>
			</tr>

			<?php			
				if (! EMPTY ( $mother )) {
				foreach ( $mother as $mt ) :
					$mother_first 	= strtoupper ( $mt ['relation_first_name'] );
					$mother_last 	= strtoupper ( $mt ['relation_last_name'] );
					$mother_mid 	= strtoupper ( $mt ['relation_middle_name'] );
					?>
				<?php endforeach; 
			}?>
			<tr>
				<td class="b-t-l b-s-r bg-sub" height="20">&nbsp;25. MOTHER'S MAIDEN NAME</td>
				<td class="b-s-b b-s-r f-size-10" colspan=2></td>
			</tr>
			<tr>
				<td class="b-t-l b-s-r bg-sub" height="20">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;MOTHER'S SURNAME</td>
				<td class="b-s-b b-s-r f-size-10" colspan=2>&nbsp;<?php echo !EMPTY($mother_last) ? $mother_last : N_A ?></td>
			</tr>
			<tr>
				<td class="b-t-l b-s-r bg-sub" height="20">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;FIRST NAME</td>
				<td class="b-s-b b-s-r f-size-10" colspan=2>&nbsp;<?php echo !EMPTY($mother_first) ? $mother_first : N_A ?></td>
			</tr>
			<tr>
				<td class="b-t-l b-s-r b-s-b bg-sub" height="20">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;MIDDLE NAME</td>
				<td class="b-s-r b-s-b f-size-10" colspan=2>&nbsp;<?php echo !EMPTY($mother_mid) ? $mother_mid : N_A ?></td>
			</tr>
		</table>

		</div>
		<div
			style="width: 44.99%; border-style: solid; border-width: 0; float: left;">
			<table class="table-max">
				<tr>
					<td class="b-s-b b-s-r bg-sub" height="20">&nbsp;23. NAME OF CHILD (Write full name and all)</span></td>
					<td class="b-t-r b-s-b bg-sub">&nbsp;DATE OF BIRTH (mm/dd/yy)&nbsp;</td>
				</tr>

				<?php
				$total = count ( $child );
				$diff  = 12 - $total;
				
					if (! EMPTY ( $child )) {						
						foreach ( $child as $cd ) :
							$child_name = (! EMPTY ( $cd ['name'] )) ? strtoupper ( $cd ['name'] ) : N_A;
							$birth_date = (! EMPTY ( $cd ['relation_birth_date'] )) ? ($cd ['relation_birth_date']) : N_A;							
							?>

							<tr>
								<td class="b-s-r b-s-b f-size-10" height="20">&nbsp;<?php echo $child_name ?></td>
								<td class="b-t-r b-s-b f-size-10">&nbsp;<?php echo $birth_date ?></td>
							</tr>
					<?php
						endforeach
						;
					}
					for($i = 0; $i < $diff; $i ++) {
						?>
						<tr>
							<td class="b-s-r b-s-b f-size-10" height="20">&nbsp;<?php echo ($i == '0' AND EMPTY($child[0]['name'])) ? N_A : '' ;?></td>
							<td class="b-t-r b-s-b f-size-10">&nbsp;<?php echo ($i == '0' AND EMPTY($child[0]['name'])) ? N_A : '' ;?></td>
						</tr>
					<?php } ?>
				<tr>
					<td class="b-t-r b-s-b f-size-8 align-c bold italic bg-sub" colspan=2 style="color: red;" height="20">(Continue on separate sheet if necessary)</td>
				</tr>
			</table>
		</div>
	</div>
	<!-- ********************** FAMILY INFO END ********************** -->
	
	<!-- ********************** EDUCATIONAL INFO START ********************** -->
	<table class="table-max" style="page-break-after: always">
		<tr>	
			<td class="border-thick bg-header text-white f-size-11 bold italic" height="20" colspan=8>III. EDUCATIONAL BACKGROUND</td>
		</tr>
		<tr>
			<td rowspan=2 class="b-t-l b-s-b align-l v-top bg-sub" width="125">26.<br><br><?php echo str_repeat($space, 20)?>LEVEL</td>
			<td rowspan=2 class="b-s-l b-s-b mid bg-sub" width="180">NAME OF SCHOOL<br>(Write in full)</td>
			<td rowspan=2 class="b-s-l b-s-b mid bg-sub" width="180">BASIC EDUCATION/DEGREE/COURSE<br>(Write in full)</td>
			<td colspan=2 class="b-s-l b-s-b mid bg-sub" width="80" height="30">PERIOD OF ATTENDANCE</td>
			<td rowspan=2 class="b-s-l b-s-b mid bg-sub" width="70">HIGHEST LEVEL/<br>UNITS EARNED<br>(if not graduated)</td>
			<td rowspan=2 class="b-s-l b-s-b mid bg-sub" width="40">YEAR<br>GRADUATED</td>
			<td rowspan=2 class="b-s-l b-s-b b-t-r mid bg-sub" width="60">SCHOLARSHIP/<br>ACADEMIC HONORS<br>RECEIVED</td>
		</tr>
		<tr>
			<td class="b-s-r b-s-b b-s-l mid bg-sub" height="15" width="40">From</td>
			<td class="b-s-r b-s-b mid bg-sub" width="40">To</td>
		</tr>
		<?php foreach($educ_list as $i => $educ):
				$count = 6;
				$found = FALSE;
			?>
				<?php foreach($educ_details as $key => $ed):

						$educ_details_counter++;
						$same = FALSE;
				?>		
					<?php if($educ['educ_level_id'] == $ed['educational_level_id']) :
							$found = TRUE;
							// $school_name = (!EMPTY($ed['school_name'])) ? $ed['school_name']						:N_A;
							$school_name = (!EMPTY($ed['school_name'])) ? strtoupper($ed['school_name'])			:N_A; //jendaigo : change school_name format
							$deg_course  = (!EMPTY($ed['degree_name'])) ? $ed['degree_name']						:N_A;	
							$year_grad   = ($ed['year_graduated_flag'] == "Y") ? $ed['end_year']					:N_A;
							$high_level  = (!EMPTY($ed['highest_level'])) ? strtoupper($ed['highest_level'])		:N_A;
							$start_date  = (!EMPTY($ed['start_year'])) ? $ed['start_year']	    					:N_A;
							$end_date    = (!EMPTY($ed['end_year'])) ? $ed['end_year']								:N_A;
							$honors      = (!EMPTY($ed['academic_honor'])) ? strtoupper($ed['academic_honor'])		:N_A;
							if($key > $count) {
								$educ_details_exceeds = TRUE;
								break;	
							} 
							$same = $educ_details[$key-1]['educational_level_id']  == $ed['educational_level_id'] ? TRUE : FALSE;
						?>
						<tr>
							<td class="b-t-l mid bg-sub" height="20" style="<?php echo ($same) ? '' : 'border-top: 1px solid #000000;'; ?>">&nbsp;&nbsp;<?php if ($same): ?>&nbsp;<?php else: ?><?php echo $educ['educ_level_name']; ?><?php endif;?></td>
							<td class="b-s-l b-s-b mid f-size-10"><?php echo $school_name; ?></td>
							<td class="b-s-l b-s-b mid f-size-10"><?php echo $deg_course; ?></td>
							<td class="b-s-l b-s-b mid f-size-10"><?php echo $start_date; ?></td>
							<td class="b-s-l b-s-b mid f-size-10"><?php echo $end_date; ?></td>
							<td class="b-s-l b-s-b mid f-size-10"><?php echo $high_level; ?></td>
							<td class="b-s-l b-s-b mid f-size-10"><?php echo $year_grad; ?></td>
							<td class="b-s-b b-s-l b-t-r mid" align="center"><?php echo $honors; ?></td>
						</tr>
					<?php endif; 

					endforeach;
				if(!$found AND $i < 5) {
					echo '<tr>
							<td class="b-t-l b-s-b b-s-t mid bg-sub" height="20">&nbsp;&nbsp;'. $educ['educ_level_name'] .'</td>
							<td class="b-s-l b-s-b f-size-10 mid">'. N_A .'</td>
							<td class="b-s-l b-s-b f-size-10 mid">&nbsp;</td>
							<td class="b-s-l b-s-b f-size-10 mid">&nbsp;</td>
							<td class="b-s-l b-s-b f-size-10 mid">&nbsp;</td>
							<td class="b-s-l b-s-b f-size-10 mid">&nbsp;</td>
							<td class="b-s-l b-s-b f-size-10 mid">&nbsp;</td>
							<td class="b-s-b b-s-l b-t-r f-size-10 mid">&nbsp;</td>
						</tr>';
				}
				endforeach;
			?>

		<tr>
		<tr>
			<td class="border-thick italic bold align-c bg-sub" colspan=8 height="10" style="color: red;">(Continue on separate sheet if necessary)</td>
		</tr>
		<tr>
			<td class="b-t-l b-t-r b-t-b italic bold f-size-11 align-c bg-sub" height="30">SIGNATURE</td>
			<td class="b-t-r b-t-b" colspan=2></td>
			<td class="b-t-r b-t-b italic bold f-size-11 align-c bg-sub" colspan=2>DATE</td>
			<td class="b-t-r b-t-b italic bold f-size-11 mid" colspan=3><?php echo !EMPTY($personal_info['date_accomplished']) ? $personal_info['date_accomplished'] : N_A ?></td>
		</tr>
		<tr>
			<td class="align-r f-size-7 italic" colspan=8>CS FORM 212 (Revised 2017), Page 1 of {nb}</td>
		</tr>
	</table>	
	<!-- ********************** EDUCATIONAL INFO END ********************** -->
	
	<!-- ********************** PAGE 2 ********************** -->
	
	<!-- ********************** ELIGIBILITY INFO START ********************** -->
	<table class="table-max">
		<tr>
			<td colspan=6 class="b-t-t b-t-r b-t-l" height="2.5"></td>
		</tr>
		<tr>
			<td class="border-thick bg-header text-white f-size-11 bold italic" height="20" colspan=6>IV. CIVIL SERVICE ELIGIBILITY</td>
		</tr>
		<tr>
			<td class="b-t-l b-s-b b-s-r bg-sub" rowspan=2 width="240">27. CAREER SERVICE/ RA 1080 (BOARD/ BAR) UNDER SPECIAL LAWS/ CES/ CSEE BARANGAY ELIGIBILITY / DRIVER'S LICENSE</td>
			<td class="b-s-b b-s-r bg-sub mid" rowspan=2 width="80">RATING <br>(if applicable)</td>
			<td class="b-s-b b-s-r bg-sub mid" rowspan=2 width="80">DATE OF <br>EXAMINATION/<br>CONFERMENT</td>
			<td class="b-s-b b-s-r bg-sub mid" rowspan=2 width="240">PLACE OF EXAMINATION / CONFERMENT</td>
			<td class="b-t-r b-s-b b-s-l bg-sub mid" colspan=2 width="" height="20">LICENSE (if applicable)</td>
		</tr>
		<tr>
			<td class="b-s-b b-s-r bg-sub mid" width="73">NUMBER</td>
			<td class="b-t-r b-s-b bg-sub mid">Date of<br>Validity</td>
		</tr>

		<?php
		$count = 7;
		if (! EMPTY ( $govt_exam )) {
			foreach ( $govt_exam as $key => $ge ) :
				if ($key > $count) {
					$govt_exam_exceeds = TRUE;
					break;
				}
				$govt_exam_counter ++;
				?>
		<tr>
			<td class="b-t-l b-s-b b-s-r mid f-size-10" height="27"><?php echo !EMPTY($ge['eligibility_type_name']) ? strtoupper($ge['eligibility_type_name']) : N_A ?></td>
			<td class="b-s-b b-s-r mid f-size-10" ><?php echo !EMPTY($ge['rating']) ? $ge['rating'] : N_A ?></td>
			<td class="b-s-b b-s-r mid f-size-10" ><?php echo !EMPTY($ge['exam_date']) ? $ge['exam_date'] : N_A ?></td>
			<td class="b-s-b b-s-r mid f-size-10" ><?php echo !EMPTY($ge['exam_place']) ? strtoupper($ge['exam_place']) : N_A ?></td>
			<td class="b-s-b b-s-r mid f-size-10" ><?php echo !EMPTY($ge['license_no']) ? $ge['license_no'] : N_A ?></td>
			<td class="b-t-r b-s-b b-s-l mid f-size-10" ><?php echo !EMPTY($ge['release_date']) ? $ge['release_date'] : N_A ?></td>
		</tr>
		<?php
			endforeach
			;
			if (($key + 1) < $count) {
				while ( ++ $key < $count ) {
					echo '<tr>
							<td class="border-solid b-t-l mid f-size-10" height="27"></td>
							<td class="border-solid f-size-10" ></td>
							<td class="border-solid f-size-10" ></td>
							<td class="border-solid f-size-10" ></td>
							<td class="border-solid f-size-10" ></td>
							<td class="border-solid b-t-r f-size-10"></td>
						</tr>';
				}
			}
		} else {
			$key = 0;
			while ( $key < $count ) {
					$key ++;
					$condition =  ($key == "1") ? N_A : "" ;
				echo '<tr>
						<td class="border-solid b-t-l mid f-size-10" height="27">'.$condition.'</td>
						<td class="border-solid mid f-size-10" >' . $condition.'</td>
						<td class="border-solid mid f-size-10" >' . $condition . '</td>
						<td class="border-solid mid f-size-10" >' .$condition . '</td>
						<td class="border-solid mid f-size-10" >' . $condition . '</td>
						<td class="border-solid mid b-t-r f-size-10">' . $condition . '</td>
					</tr>';
			}
		}
		?>	

		<tr>
			<td class="border-thick italic bold align-c bg-sub" colspan=6 height="10" style="color: red;">(Continue on separate sheet if necessary)</td>
		</tr>
	</table>	
	<!-- ********************** ELIGIBILITY INFO END ********************** -->
	
	<!-- ********************** WORK EXPERIENCE INFO START ********************** -->
	<table class="table-max" style="page-break-after: always">
		<tr>
			<td class="border-thick bg-header text-white f-size-11 bold italic" height="30" colspan=9>V. WORK EXPERIENCE <br><span class="f-size-9">(Include private employment.  Start from your recent work) Description of duties should be indicated in the attached Work Experience sheet.</span></td>
		</tr>
		<tr>
			<td class="border-solid b-t-l bg-sub" colspan=2 height="27" width="50">28.
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;INCLUSIVE DATES<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(mm/dd/yyyy)</td>
			<td rowspan=2 class="border-solid mid bg-sub" width="210">POSITION TITLE <br>((Write in full/Do not abbreviate)</td>
			<td rowspan=2 class="border-solid mid bg-sub" width="" colspan=2>DEPARTMENT/AGENCY/OFFICE/COMPANY <br>(Write in full/Do not abbreviate)</td>
			<td rowspan=2 class="border-solid mid bg-sub" width="">MONTHLY SALARY</td>
			<td rowspan=2 class="border-solid mid bg-sub" width="">SALARY/ JOB/ PAY GRADE (if applicable)&amp; STEP  (Format "00-0")/ INCREMENT</td>
			<td rowspan=2 class="border-solid mid bg-sub" width="73">STATUS OF<br> APPOINTMENT</td>
			<td rowspan=2 class="border-solid b-t-r mid bg-sub" width="52">GOV'T<br> SERVICE <br>(Yes/No)</td>
		</tr>
		<tr>
			<td class="border-solid b-t-l mid bg-sub">From</td>
			<td class="border-solid b-t-l mid bg-sub">To</td>
		</tr>
		<?php			
		$count = 26;
		if (! EMPTY ( $work_exp )) {
			foreach ( $work_exp as $key => $we ) :
				if ($key > $count) {
					$work_exp_exceeds = TRUE;
					break;
				}
				$work_exp_counter ++;
				$govt_service = ($we ['govt_service_flag'] == 'Y') ? "YES" : "NO";
		?>
		<tr>
			<td class="border-solid b-t-l mid f-size-9" height="27"><?php echo !EMPTY($we['employ_start_date']) ? $we['employ_start_date'] : N_A; ?></td>
			<td class="border-solid mid f-size-9"><?php echo (!EMPTY($we['employ_end_date']) ? $we['employ_end_date'] : 'PRESENT') ; ?></td>
			<td class="border-solid mid f-size-9"><?php echo !EMPTY($we['employ_position_name']) ? strtoupper($we['employ_position_name']) : N_A ?></td>
			<td class="border-solid mid f-size-9" style="padding-left: 5px; padding-right: 5px;" colspan=2>
				<?php
				$officeText = strtoupper($we['employ_office_name']);
				
				if (strlen($officeText) > 55) {
					echo '<div class=" f-size-8">';
				} 
				else {
					echo '<div class="f-size-9">';
				}
				?>
					<span> <?php echo $officeText; ?></span>
				</div>
				<?php //echo !EMPTY($we['employ_office_name']) ? strtoupper($we['employ_office_name']) : N_A ?>
			</td>
			<td class="border-solid mid f-size-9"><?php echo !EMPTY($we['employ_monthly_salary']) ? number_format($we['employ_monthly_salary'],2) : N_A; ?></td>
			<!-- <td class="border-solid mid f-size-10"><?php //echo ($we['govt_service_flag'] == 'Y') ? str_pad($we['employ_salary_grade'], 2, "0", STR_PAD_LEFT) .' - '.$we['employ_salary_step'] : N_A; ?></td> -->
			<!-- ncocampo 10/19/2023  DISPLAY SG AND SI IN PRINTING PDS IN JO: START -->
			<td class="border-solid mid f-size-9"><?php echo ($we['employ_type_flag'] !== 'PR') ? str_pad($we['employ_salary_grade'], 2, "0", STR_PAD_LEFT) .' - '.$we['employ_salary_step'] : N_A; ?></td>
			<!-- ncocampo 10/19/2023  DISPLAY SG AND SI IN PRINTING PDS IN JO: END -->
			<td class="border-solid mid f-size-9"><?php echo !EMPTY($we['employment_status_name']) ? strtoupper($we['employment_status_name']) : N_A; ?></td>
			<td class="border-solid b-t-r mid f-size-9"><?php echo !EMPTY($govt_service) ? $govt_service : N_A; ?></td>
		</tr>
			<?php
				endforeach
				;
				if (($key + 1) < $count) {
					while ( ++ $key < $count ) {
						echo '<tr>
								<td class="border-solid b-t-l" height="27"></td>
								<td class="border-solid"></td>
								<td class="border-solid"></td>
								<td class="border-solid" colspan=2></td>
								<td class="border-solid"></td>
								<td class="border-solid"></td>
								<td class="border-solid"></td>
								<td class="border-solid b-t-r"></td>
							</tr>';
					}
				}
			} else {
				$key = 0;
				
				while ( $key < $count ) {
					$key ++;
					$condition =  ($key == "1") ? N_A : "" ;
					echo '<tr>
							<td class="border-solid b-t-l mid f-size-10" height="27">' . $condition  . '</td>
							<td class="border-solid mid f-size-10">' . $condition . '</td>
							<td class="border-solid mid f-size-10">' . $condition . '</td>
							<td class="border-solid mid f-size-10" colspan=2>' . $condition . '</td>
							<td class="border-solid mid f-size-10">' .$condition  . '</td>
							<td class="border-solid mid f-size-10">' . $condition . '</td>
							<td class="border-solid mid f-size-10">' . $condition . '</td>
							<td class="border-solid mid b-t-r f-size-10">' . $condition . '</td>
						</tr>';
				}
			}
			?>
		<tr>
			<td class="border-thick italic bold align-c bg-sub" colspan=9 height="10" style="color: red;">(Continue on separate sheet if necessary)</td>
		</tr>
		<tr>
			<td class="b-t-l b-t-r b-t-b italic bold f-size-11 align-c bg-sub" height="30" colspan=2>SIGNATURE</td>
			<td class="b-t-r b-t-b" colspan=2></td>
			<td class="b-t-r b-t-b italic bold f-size-11 align-c bg-sub" colspan=2>DATE</td>
			<td class="b-t-r b-t-b italic bold f-size-11 mid" colspan=3><?php echo !EMPTY($personal_info['date_accomplished']) ? $personal_info['date_accomplished'] : N_A ?></td>
		</tr>
		<tr>
			<td class="align-r f-size-7 italic" colspan=9>CS FORM 212 (Revised 2017), Page 2 of {nb}</td>
		</tr>
	</table>	
	<!-- ********************** WORK EXPERIENCE INFO END ********************** -->

<!-- ********************** PAGE 2.5********************** -->
	
	
	<!-- ********************** WORK EXPERIENCE INFO START ********************** -->
	<?php if ($work_exp_counter > 26) : ?>
		<table class="table-max">
		<tr>
			<td colspan=6 class="b-t-t b-t-r b-t-l" height="2.5"></td>
		</tr>
		<tr>
			<td class="border-thick bg-header text-white f-size-11 bold italic" height="20" colspan=6>IV. CIVIL SERVICE ELIGIBILITY</td>
		</tr>
		<tr>
			<td class="b-t-l b-s-b b-s-r bg-sub" rowspan=2 width="240">27. CAREER SERVICE/ RA 1080 (BOARD/ BAR) UNDER SPECIAL LAWS/ CES/ CSEE BARANGAY ELIGIBILITY / DRIVER'S LICENSE</td>
			<td class="b-s-b b-s-r bg-sub mid" rowspan=2 width="80">RATING <br>(if applicable)</td>
			<td class="b-s-b b-s-r bg-sub mid" rowspan=2 width="80">DATE OF <br>EXAMINATION/<br>CONFERMENT</td>
			<td class="b-s-b b-s-r bg-sub mid" rowspan=2 width="240">PLACE OF EXAMINATION / CONFERMENT</td>
			<td class="b-t-r b-s-b b-s-l bg-sub mid" colspan=2 width="" height="20">LICENSE (if applicable)</td>
		</tr>
		<tr>
			<td class="b-s-b b-s-r bg-sub mid" width="73">NUMBER</td>
			<td class="b-t-r b-s-b bg-sub mid">Date of<br>Validity</td>
		</tr>
		<tr>
			<td class="border-solid b-t-l b-t-r italic bold align-c bg-sub" colspan=6 height="10" style="color: red;">(Aditional sheet)</td>
		</tr>
		<?php
		$govt_exam = array($govt_exam[0]);	
		$count = 7;
		if (! EMPTY ( $govt_exam )) {
			foreach ( $govt_exam as $key => $ge ) :
				if ($key > $count) {
					$govt_exam_exceeds = TRUE;
					break;
				}
				$govt_exam_counter ++;
				?>
		<tr>
			<td class="b-t-l b-s-b b-s-r mid f-size-10" height="27"><?php echo N_A ?></td>
			<td class="b-s-b b-s-r mid f-size-10" ><?php echo N_A ?></td>
			<td class="b-s-b b-s-r mid f-size-10" ><?php echo N_A ?></td>
			<td class="b-s-b b-s-r mid f-size-10" ><?php echo N_A ?></td>
			<td class="b-s-b b-s-r mid f-size-10" ><?php echo N_A ?></td>
			<td class="b-t-r b-s-b b-s-l mid f-size-10" ><?php echo N_A ?></td>
		</tr>
		<?php
			endforeach
			;
			if (($key + 1) < $count) {
				while ( ++ $key < $count ) {
					echo '<tr>
							<td class="border-solid b-t-l mid f-size-10" height="27"></td>
							<td class="border-solid f-size-10" ></td>
							<td class="border-solid f-size-10" ></td>
							<td class="border-solid f-size-10" ></td>
							<td class="border-solid f-size-10" ></td>
							<td class="border-solid b-t-r f-size-10"></td>
						</tr>';
				}
			}
		} else {
			$key = 0;
			while ( $key < $count ) {
					$key ++;
					$condition =  ($key == "1") ? N_A : "" ;
				echo '<tr>
						<td class="border-solid b-t-l mid f-size-10" height="27">'.$condition.'</td>
						<td class="border-solid mid f-size-10" >' . $condition.'</td>
						<td class="border-solid mid f-size-10" >' . $condition . '</td>
						<td class="border-solid mid f-size-10" >' .$condition . '</td>
						<td class="border-solid mid f-size-10" >' . $condition . '</td>
						<td class="border-solid mid b-t-r f-size-10">' . $condition . '</td>
					</tr>';
			}
		}
		?>	
	</table>	
	<!-- ********************** ELIGIBILITY INFO END ********************** -->
		<table class="table-max" style="page-break-after: always">
		<tr>
			<td class="border-thick bg-header text-white f-size-11 bold italic" height="30" colspan=9>V. WORK EXPERIENCE <br><span class="f-size-9">(Include private employment.  Start from your recent work) Description of duties should be indicated in the attached Work Experience sheet.</span></td>
		</tr>
		<tr>
			<td class="border-solid b-t-l bg-sub" colspan=2 height="27" width="50">28.
				INCLUSIVE DATES<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(mm/dd/yyyy)</td>
			<td rowspan=2 class="border-solid mid bg-sub" width="210">POSITION TITLE <br>((Write in full/Do not abbreviate)</td>
			<td rowspan=2 class="border-solid mid bg-sub" width="" colspan=2>DEPARTMENT/AGENCY/OFFICE/COMPANY <br>(Write in full/Do not abbreviate)</td>
			<td rowspan=2 class="border-solid mid bg-sub" width="">MONTHLY SALARY</td>
			<td rowspan=2 class="border-solid mid bg-sub" width="">SALARY/ JOB/ PAY GRADE (if applicable)&amp; STEP  (Format "00-0")/ INCREMENT</td>
			<td rowspan=2 class="border-solid mid bg-sub" width="73">STATUS OF<br> APPOINTMENT</td>
			<td rowspan=2 class="border-solid b-t-r mid bg-sub" width="52">GOV'T<br> SERVICE <br>(Yes/No)</td>
		</tr>
		<tr>
			<td class="border-solid b-t-l mid bg-sub">From</td>
			<td class="border-solid  mid bg-sub">To</td>
		</tr>
		<tr>
			<td class="border-solid b-t-l b-t-r italic bold align-c bg-sub" colspan=9 height="10" style="color: red;">(Aditional sheet)</td>
		</tr>
		<?php
		$count = 0;
		if (! empty ( $work_exp )) {
			foreach ( $work_exp as $key => $we ) :
				if ($key <= 26) {
					continue;
				}
				$count++;
				if ($count >= 28) {
					break;
				}
				$govt_service = ($we ['govt_service_flag'] == 'Y') ? "YES" : "NO";
		?>
			<tr>
			<td class="border-solid b-t-l mid f-size-9" height="27"><?php echo !EMPTY($we['employ_start_date']) ? $we['employ_start_date'] : N_A; ?></td>
			<td class="border-solid mid f-size-9"><?php echo (!EMPTY($we['employ_end_date']) ? $we['employ_end_date'] : 'PRESENT') ; ?></td>
			<td class="border-solid mid f-size-9"><?php echo !EMPTY($we['employ_position_name']) ? strtoupper($we['employ_position_name']) : N_A ?></td>
			<td class="border-solid mid f-size-9" style="padding-left: 5px; padding-right: 5px;" colspan=2> <?php $officeText = strtoupper($we['employ_office_name']); if (strlen($officeText) > 55) { echo '<div class=" f-size-8">'; } else { echo '<div class="f-size-9">';} ?><span> <?php echo $officeText; ?></span></div></td>
			<td class="border-solid mid f-size-9"><?php echo !EMPTY($we['employ_monthly_salary']) ? number_format($we['employ_monthly_salary'],2) : N_A; ?></td>
			<td class="border-solid mid f-size-9"><?php echo ($we['employ_type_flag'] !== 'PR') ? str_pad($we['employ_salary_grade'], 2, "0", STR_PAD_LEFT) .' - '.$we['employ_salary_step'] : N_A; ?></td>
			<td class="border-solid mid f-size-9"><?php echo !EMPTY($we['employment_status_name']) ? strtoupper($we['employment_status_name']) : N_A; ?></td>
			<td class="border-solid b-t-r mid f-size-9"><?php echo !EMPTY($govt_service) ? $govt_service : N_A; ?></td>
			</tr>
			<?php
				endforeach
				;
			}
			if ($count < 28) {
				$empty_rows = 28 - $count;
				while ( $empty_rows > 0 ) {
					echo '<tr>
					<td class="border-solid b-t-l mid f-size-10" height="27">' . $condition  . '</td>
					<td class="border-solid mid f-size-10">' . $condition . '</td>
					<td class="border-solid mid f-size-10">' . $condition . '</td>
					<td class="border-solid mid f-size-10" colspan=2>' . $condition . '</td>
					<td class="border-solid mid f-size-10">' .$condition  . '</td>
					<td class="border-solid mid f-size-10">' . $condition . '</td>
					<td class="border-solid mid f-size-10">' . $condition . '</td>
					<td class="border-solid mid b-t-r f-size-10">' . $condition . '</td>
					</tr>';
					$empty_rows--;
				}
			}
			?>
		
		<tr>
			<td class="b-t-l b-t-r b-t-b italic bold f-size-11 align-c bg-sub" height="30" colspan=2>SIGNATURE</td>
			<td class="b-t-r b-t-b" colspan=2></td>
			<td class="b-t-r b-t-b italic bold f-size-11 align-c bg-sub" colspan=2>DATE</td>
			<td class="b-t-r b-t-b italic bold f-size-11 mid" colspan=3><?php echo !EMPTY($personal_info['date_accomplished']) ? $personal_info['date_accomplished'] : N_A ?></td>
		</tr>
		
		<tr>
			<td class="align-r f-size-7 italic" colspan=9>CS FORM 212 (Revised 2017), Page 3 of {nb}</td>
		</tr>
		</table>
	<?php endif; ?>
	<!-- ********************** WORK EXPERIENCE INFO END ********************** -->


	<!-- ********************** PAGE 3 ********************** -->

	<!-- **********************  VOLUNTARY WORK INFO START ********************** -->
	<table class="table-max">
		<tr>
			<td colspan=5 class="b-t-t b-t-l b-t-r"	height="2.5"></td>
		</tr>
		<tr>
			<td colspan=5 class="border-thick bg-header text-white f-size-11 bold italic" height="20">VI. VOLUNTARY WORK OR INVOLVEMENT IN CIVIC / NON-GOVERNMENT / PEOPLE / VOLUNTARY ORGANIZATION/S</td>
		</tr>
		<tr>
			<td class="border-solid b-t-l bg-sub" rowspan=2 width="285">
				<table class="table-max">
					<tr>
						<td class="mid v-top" width="20">29.</td>
						<td class="mid" width="265">NAME &amp; ADDRESS OF ORGANIZATION <br>(Write in full)</td>
					</tr>
				</table>
			</td>
			<td class="border-solid mid bg-sub" colspan=2 height="30" width="120">INCLUSIVE DATES <br>(mm/dd/yyyy)</td>
			<td class="border-solid mid bg-sub" rowspan=2 width="60">NUMBER OF<br> HOURS</td>
			<td class="border-solid b-t-r mid bg-sub" rowspan=2 width="210">POSITION / NATURE OF WORK</td>
		</tr>
		<tr>
			<td class="border-solid mid bg-sub" height="15" width="60">From</td>
			<td class="border-solid mid bg-sub" width="60">To</td>
		</tr>
			<?php			
			$count = 5;
			if (! EMPTY ( $vol_details )) {
				foreach ( $vol_details as $key => $vd ) :
					if ($key > $count) {
						$vol_details_exceeds = TRUE;
						break;
					}
					$vol_details_counter ++;
			?>
		<tr>
			<td class="border-solid b-t-l mid f-size-10" height="27"><?php echo (!EMPTY($vd['volunteer_org_name']) AND !EMPTY($vd['volunteer_org_address'])) ? strtoupper($vd['volunteer_org_name'].' ('.$vd['volunteer_org_address'].')') : N_A; ?></td>
			<td class="border-solid mid f-size-10"><?php echo !EMPTY($vd['volunteer_start_date']) ? $vd['volunteer_start_date'] : N_A; ?></td>
			<td class="border-solid mid f-size-10"><?php echo !EMPTY($vd['volunteer_end_date']) ? $vd['volunteer_end_date'] : N_A; ?></td>
			<td class="border-solid mid f-size-10"><?php echo !EMPTY($vd['volunteer_hour_count']) ? number_format($vd['volunteer_hour_count'],2) : N_A; ?></td>
			<td class="border-solid b-t-r mid f-size-10"><?php echo !EMPTY($vd['volunteer_position']) ? strtoupper($vd['volunteer_position']) : N_A; ?></td>
		</tr>
			<?php
				endforeach
				;
				if (($key + 1) < $count) {
					while ( ++ $key < $count ) {
						echo '<tr>
								<td class="border-solid b-t-l mid" height="27"></td>
								<td class="border-solid mid"></td>
								<td class="border-solid mid"></td>
								<td class="border-solid mid"></td>
								<td class="border-solid b-t-r mid"></td>
							</tr>';
					}
				}
			} else {
				$key = 0;
				while ( $key < $count ) {
					$key ++;
					$condition =  ($key == "1") ? N_A : "" ;
					echo '<tr>
							<td class="border-solid b-t-l mid f-size-10" height="27">'. $condition .'</td>
							<td class="border-solid mid f-size-10">'. $condition .'</td>
							<td class="border-solid mid f-size-10">'. $condition .'</td>
							<td class="border-solid mid f-size-10">'. $condition .'</td>
							<td class="border-solid b-t-r mid f-size-10">'. $condition .'</td>
						</tr>';
				}
			}
			?>
		<tr>
			<td class="border-thick italic bold align-c bg-sub" colspan=5 height="10" style="color: red;">(Continue on separate sheet if necessary)</td>
		</tr>
	</table>
	<!-- **********************  VOLUNTARY WORK INFO END ********************** -->

	<!-- **********************  TRAINING INFO START ********************** -->
	<table class="table-max">
		<tr>
			<td class="border-thick bg-header text-white f-size-11 bold italic" height="20" colspan=6>VII.  LEARNING AND DEVELOPMENT (L&D) INTERVENTIONS/TRAINING PROGRAMS ATTENDED<br><span class="f-size-7">(Start from the most recent L&D/training program and include only the relevant L&D/training taken for the last five (5) years for Division Chief/Executive/Managerial positions)</span></td>
		</tr>
		<tr>
			<td class="border-solid b-t-l mid bg-sub" rowspan=2 width="310">30. &nbsp; &nbsp; &nbsp;TITLE OF SEMINAR/CONFERENCE/WORKSHOP/SHORT COURSES &nbsp;&nbsp; &nbsp; &nbsp;<br>(Write in full)</td>
			<td class="border-solid mid bg-sub" colspan=2 width="130">INCLUSIVE DATES OF ATTENDANCE<br>(mm/dd/yyyy)</td>
			<td class="border-solid mid bg-sub" rowspan=2 width="75">NUMBER OF<br> HOURS</td>
			<td class="border-solid mid bg-sub" rowspan=2 width="60">Type of LD<br>(Managerial/<br>Supervisory/<br>Technical/etc) </td>
			<td class="border-solid b-t-r mid bg-sub" rowspan=2 width="150">CONDUCTED/SPONSORED BY <br>(Write in full)</td>
		</tr>
		<tr>350
			<td class="border-solid mid bg-sub" width="65" height="15">From</td>
			<td class="border-solid mid bg-sub" width="65">To</td>
		</tr>
	<?php	
	$count = 21;
	if (! EMPTY ( $train_details )) {
		foreach ( $train_details as $key => $td ) :
			if ($key > $count) {
				$train_details_exceeds = TRUE;
				break;
			}
			$train_details_counter ++;
	?>
	<tr>
		<td class="border-solid b-t-l" style="padding-left: 5px; padding-right: 5px; text-align: justify;" height="25">
		<?php
	      $trainingText = strtoupper($td['training_name']);
			if (strlen($trainingText) > 90) { echo '<div class=" f-size-8">';}
			elseif (strlen($trainingText) > 50) { echo '<div class=" f-size-8">';}
			else {echo '<div class="f-size-9">';}
	      ?>
	    	<span> <?php echo $trainingText; ?></span></div>
		</td>
		<td class="border-solid mid f-size-9"><?php echo !EMPTY($td['training_start_date']) ? $td['training_start_date'] : N_A; ?></td>
		<td class="border-solid mid f-size-9"><?php echo !EMPTY($td['training_end_date']) ? $td['training_end_date'] : N_A; ?></td>
		<td class="border-solid mid f-size-9"><?php echo !EMPTY($td['training_hour_count']) ? number_format($td['training_hour_count'],2) : N_A; ?></td>
		<td class="border-solid mid f-size-9" style="padding-left: 5px; padding-right: 5px;">
			<?php 
			$trainingtypeText = strtoupper($td['training_type']);
				if (strlen($trainingtypeText) > 15) {echo '<div class=" f-size-7">';}
				else {echo '<div class="f-size-9">';}
			?>
			<span> <?php echo $trainingtypeText; ?></span></div>
		</td>
		<td class="border-solid b-t-r mid f-size-9">
			<?php 
				$training_conducted_Text = strtoupper($td['training_conducted_by']);
					if (strlen($training_conducted_Text) > 50) {echo '<div class=" f-size-7">';}
					elseif (strlen($training_conducted_Text) > 40) {echo '<div class=" f-size-8">';}
					else {echo '<div class="f-size-9">';}
			?>
			<span> <?php echo $training_conducted_Text; ?></span></div>
		</td>
	</tr>
	<?php
		endforeach
		;
		if (($key + 1) < $count) {
			while ( ++ $key < $count ) {
				echo '<tr>
						<td class="border-solid b-t-l mid" height="27"></td>
						<td class="border-solid mid"></td>
						<td class="border-solid mid"></td>
						<td class="border-solid mid"></td>
						<td class="border-solid mid"></td>
						<td class="border-solid b-t-r mid"></td>
					</tr>';
			}
		}
	} else {
		$key = 0;
		while ( $key < $count ) {
				$key ++;
				$condition =  ($key == "1") ? N_A : "" ;
			echo '<tr>					
					<td class="border-solid b-t-l mid f-size-10" height="27">'. $condition .'</td>
					<td class="border-solid mid f-size-10">'. $condition .'</td>
					<td class="border-solid mid f-size-10">'. $condition .'</td>
					<td class="border-solid mid f-size-10">'. $condition .'</td>
					<td class="border-solid mid f-size-10">'. $condition .'</td>
					<td class="border-solid b-t-r mid f-size-10">'. $condition .'</td>
				</tr>';
		}
	}
	?>
	<tr>
			<td class="border-thick italic bold align-c bg-sub" colspan=6 height="10" style="color: red;">(Continue on separate sheet if necessary)</td>
		</tr>
	</table>
	<!-- **********************  TRAINING INFO END ********************** -->
	
	<!-- **********************  OTHER INFO START ********************** -->
	<table class="table-max" style="page-break-after: always">
		<tr>
			<td class="border-thick bg-header text-white f-size-11 bold italic" height="20" colspan=10>VIII. OTHER INFORMATION</td>
		</tr>
		<tr>
			<td class="border-solid b-t-l mid bg-sub" colspan=3 width="168">&nbsp;31.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;SPECIAL SKILLS / HOBBIES</td>
			<td class="border-solid mid bg-sub" colspan=4 width="">32. NON-ACADEMIC DISTINCTIONS / RECOGNITION (Write in full)</td>
			<td class="border-solid b-t-r mid bg-sub" colspan=3 width="168">33. MEMBERSHIP IN<br> ASSOCIATION / ORGANIZATION<br>(Write in full)</td>
		</tr>
		<tr>
			<td class="border-solid b-t-l mid" colspan=3>
				<table class="table-max">
					<?php
					$count = 7;
					if (! EMPTY ( $other_params ['skills_list'] )) {
						?>
						<?php
						foreach ( $other_params ['skills_list'] as $key => $sl ) :
							if ($key > $count) {
								$skills_list_exceeds = TRUE;
								break;
							}
							$skills_list_counter ++;
							?>
							<tr>
								<td class="f-size-10 mid b-s-b" width="168" height="27">
									<?php 
										$skills_hobbies = strtoupper($sl['others_value']);
											if (strlen($skills_hobbies) > 50) {echo '<div class=" f-size-7">';}
											else {echo '<div class="f-size-9">';}
									?>
									<span> <?php echo $skills_hobbies; ?></span></div>
								</td>
							</tr>
						<?php
						endforeach;
						if (($key + 1) < $count) {
							while ( ++ $key < $count ) {
								echo '<tr>
										<td class="b-s-b" width="168" height="27">&nbsp;</td>
									</tr>';
							}
						}
						?>
						<?php			
					} 
					else 
					{
						$key = 1;
						while ( $key <= $count ) {
							$key ++;
							$condition =  ($key == "2") ? N_A : "" ;			
							echo '<tr>
									<td class="b-s-b mid f-size-10" width="168" height="27">'. $condition .'</td>
								</tr>';
						}
					}
					?>
				</table>
			</td>
			<td class="border-solid" colspan=4>
				<table>
					<?php
					if (! EMPTY ( $other_params ['recog_list'] )) {
						?>
						<?php
						foreach ( $other_params ['recog_list'] as $key => $rl ) :
							if ($key > $count) {
								$recog_list_exceeds = TRUE;
								break;
							}
							$recog_list_counter ++;
							?>
							<tr>
								<td class="f-size-10 mid b-s-b" width="372" height="27">
								<?php 
										$recog_list = strtoupper($rl['others_value']);
											if (strlen($recog_list) > 110) {echo '<div class=" f-size-9">';}
											else {echo '<div class="f-size-10">';}
									?>
									<span> <?php echo $recog_list; ?></span></div>
							</tr>
						<?php
						endforeach;
						if (($key + 1) < $count) {
							while ( ++ $key < $count ) {
								echo '<tr>
										<td class="b-s-b" width="372" height="27">&nbsp;</td>
									</tr>';
							}
						}
						?>
					<?php			
					} 
					else 
					{
						$key = 1;
						while ( $key <= $count ) {		
							$key ++;
							$condition =  ($key == "2") ? N_A : "" ;						
							echo '<tr>
									<td class="b-s-b mid f-size-10" width="372" height="27">'. $condition .'</td>
								</tr>';
						}
					}
					?>
				</table>
			</td>
			<td class="border-solid b-t-r mid" colspan=3>
				<table>
					<?php if(!EMPTY($other_params['member_list'])){ ?>
						<?php
						foreach ( $other_params ['member_list'] as $key => $ml ) :
							if ($key > $count) {
								$member_list_exceeds = TRUE;
								break;
							}
							$member_list_counter ++;
							?>
							<tr>
								<td class="f-size-10 mid b-s-b" width="168" height="27">
									<?php 
										$member_list = strtoupper($ml['others_value']);
											if (strlen($member_list) > 50) {echo '<div class=" f-size-7">';}
											else {echo '<div class="f-size-9">';}
									?>
									<span> <?php echo $member_list; ?></span></div>
							</tr>
						<?php
						endforeach;
						if (($key + 1) < $count) {
							while ( ++ $key < $count ) {								
								echo '<tr>
										<td class="b-s-b" width="168" height="27">&nbsp;</td>
									</tr>';
							}
						}
						?>
						<?php					
						} 
						else 
						{
							$key = 1;
							while ( $key <= $count ) {
								$key ++;
								$condition =  ($key == "2") ? N_A : "" ;			
								echo '<tr>
										<td class="b-s-b mid f-size-10" width="168" height="27">'. $condition .'</td>
									</tr>';
							}
						}
					?>
				</table>
			</td>
		</tr>
		<tr>
			<td class="border-thick italic bold align-c bg-sub" colspan=10 height="10" style="color: red;">(Continue on separate sheet if necessary)</td>
		</tr>
		<tr>
			<td class="b-t-l b-t-r b-t-b italic bold f-size-11 align-c bg-sub" height="30" colspan=3>SIGNATURE</td>
			<td class="b-t-r b-t-b" colspan=2></td>
			<td class="b-t-r b-t-b italic bold f-size-11 align-c bg-sub" colspan=2>DATE</td>
			<td class="b-t-r b-t-b italic bold f-size-11 mid" colspan=3><?php echo !EMPTY($personal_info['date_accomplished']) ? $personal_info['date_accomplished'] : N_A ?></td>
		</tr>
		<?php if ($work_exp_counter > 26) : ?>
			<tr>
				<td class="align-r f-size-7 italic" colspan=10>CS FORM 212 (Revised 2017), Page 4 of {nb}</td>
			</tr>
		<?php else: ?>
			<tr>
				<td class="align-r f-size-7 italic" colspan=10>CS FORM 212 (Revised 2017), Page 3 of {nb}</td>
			</tr>
		<?php endif; ?>
		<!-- <tr>
			<td class="align-r f-size-7 italic" colspan=10>CS FORM 212 (Revised 2017), Page 3 of {nb}</td>
		</tr> -->
	</table>
	<!-- **********************  OTHER INFO END ********************** -->	



<!-- **********************  PAGE 3.5 ********************** -->	
<?php if ($train_details_counter > 21) : ?>
<!-- **********************  VOLUNTARY WORK INFO START ********************** -->
<table class="table-max">
		<tr>
			<td colspan=5 class="b-t-t b-t-l b-t-r"	height="2.5"></td>
		</tr>
		<tr>
			<td colspan=5 class="border-thick bg-header text-white f-size-11 bold italic" height="20">VI. VOLUNTARY WORK OR INVOLVEMENT IN CIVIC / NON-GOVERNMENT / PEOPLE / VOLUNTARY ORGANIZATION/S</td>
		</tr>
		<tr>
			<td class="border-solid b-t-l bg-sub" rowspan=2 width="285">
				<table class="table-max">
					<tr>
						<td class="mid v-top" width="20">29.</td>
						<td class="mid" width="265">NAME &amp; ADDRESS OF ORGANIZATION <br>(Write in full)</td>
					</tr>
				</table>
			</td>
			<td class="border-solid mid bg-sub" colspan=2 height="30" width="120">INCLUSIVE DATES <br>(mm/dd/yyyy)</td>
			<td class="border-solid mid bg-sub" rowspan=2 width="60">NUMBER OF<br> HOURS</td>
			<td class="border-solid b-t-r mid bg-sub" rowspan=2 width="210">POSITION / NATURE OF WORK</td>
		</tr>
		<tr>
			<td class="border-solid mid bg-sub" height="15" width="60">From</td>
			<td class="border-solid mid bg-sub" width="60">To</td>
		</tr>
		<tr>
			<td class="border-thick italic bold align-c bg-sub" colspan=5 height="10" style="color: red;">Additional Sheet</td>
		</tr>
			<?php	
			$vol_details = array($vol_details[0]);		
			$count = 5;
			if (! EMPTY ( $vol_details )) {
				foreach ( $vol_details as $key => $vd ) :
					if ($key > $count) {
						$vol_details_exceeds = TRUE;
						break;
					}
					$vol_details_counter ++;
			?>
		<tr>
			<td class="border-solid b-t-l mid f-size-10" height="27"><?php echo N_A ?></td>
			<td class="border-solid mid f-size-10"><?php echo N_A ?></td>
			<td class="border-solid mid f-size-10"><?php echo N_A ?></td>
			<td class="border-solid mid f-size-10"><?php echo N_A ?></td>
			<td class="border-solid b-t-r mid f-size-10"><?php echo N_A ?></td>
		</tr>
			<?php
				endforeach
				;
				if (($key + 1) < $count) {
					while ( ++ $key < $count ) {
						echo '<tr>
								<td class="border-solid b-t-l mid" height="27"></td>
								<td class="border-solid mid"></td>
								<td class="border-solid mid"></td>
								<td class="border-solid mid"></td>
								<td class="border-solid b-t-r mid"></td>
							</tr>';
					}
				}
			} else {
				$key = 0;
				while ( $key < $count ) {
					$key ++;
					$condition =  ($key == "1") ? "" : "" ;
					echo '<tr>
							<td class="border-solid b-t-l mid f-size-10" height="27">'. $condition .'</td>
							<td class="border-solid mid f-size-10">'. $condition .'</td>
							<td class="border-solid mid f-size-10">'. $condition .'</td>
							<td class="border-solid mid f-size-10">'. $condition .'</td>
							<td class="border-solid b-t-r mid f-size-10">'. $condition .'</td>
						</tr>';
				}
			}
			?>
		
	</table>
	<!-- **********************  VOLUNTARY WORK INFO END ********************** -->
	<!-- **********************  TRAINING INFO START ********************** -->
	
	<table class="table-max">
		<tr>
			<td class="border-thick bg-header text-white f-size-11 bold italic" height="20" colspan=6>VII.  LEARNING AND DEVELOPMENT (L&D) INTERVENTIONS/TRAINING PROGRAMS ATTENDED<br><span class="f-size-7">(Start from the most recent L&D/training program and include only the relevant L&D/training taken for the last five (5) years for Division Chief/Executive/Managerial positions)</span></td>
		</tr>
		<tr>
			<td class="border-solid b-t-l mid bg-sub" rowspan=2 width="310">30. &nbsp; &nbsp; &nbsp;TITLE OF SEMINAR/CONFERENCE/WORKSHOP/SHORT COURSES &nbsp;&nbsp; &nbsp; &nbsp;<br>(Write in full)</td>
			<td class="border-solid mid bg-sub" colspan=2 width="130">INCLUSIVE DATES OF ATTENDANCE<br>(mm/dd/yyyy)</td>
			<td class="border-solid mid bg-sub" rowspan=2 width="75">NUMBER OF<br> HOURS</td>
			<td class="border-solid mid bg-sub" rowspan=2 width="60">Type of LD<br>(Managerial/<br>Supervisory/<br>Technical/etc) </td>
			<td class="border-solid b-t-r mid bg-sub" rowspan=2 width="150">CONDUCTED/SPONSORED BY <br>(Write in full)</td>
		</tr>
		<tr>350
			<td class="border-solid mid bg-sub" width="65" height="15">From</td>
			<td class="border-solid mid bg-sub" width="65">To</td>
		</tr>
		<tr>
			<td class="border-thick italic bold align-c bg-sub" colspan=6 height="10" style="color: red;">Additional Sheet</td>
		</tr>
		<?php
		$count = 0;
		if (! empty ( $train_details )) {
			foreach ( $train_details as $key => $td ) :
				if ($key <= 21) {
					continue;
				}
				$count++;
				if ($count >= 22) {
					break;
				}
		?>
	<tr>
		<td class="border-solid b-t-l" style="padding-left: 5px; padding-right: 5px; text-align: justify;" height="25">
		<?php
	      $trainingText = strtoupper($td['training_name']);
			if (strlen($trainingText) > 90) { echo '<div class=" f-size-8">';}
			elseif (strlen($trainingText) > 50) { echo '<div class=" f-size-8">';}
			else {echo '<div class="f-size-9">';}
	      ?>
	    	<span> <?php echo $trainingText; ?></span></div>
		</td>
		<td class="border-solid mid f-size-9"><?php echo !EMPTY($td['training_start_date']) ? $td['training_start_date'] : N_A; ?></td>
		<td class="border-solid mid f-size-9"><?php echo !EMPTY($td['training_end_date']) ? $td['training_end_date'] : N_A; ?></td>
		<td class="border-solid mid f-size-9"><?php echo !EMPTY($td['training_hour_count']) ? number_format($td['training_hour_count'],2) : N_A; ?></td>
		<td class="border-solid mid f-size-9" style="padding-left: 5px; padding-right: 5px;">
			<?php 
			$trainingtypeText = strtoupper($td['training_type']);
				if (strlen($trainingtypeText) > 15) {echo '<div class=" f-size-7">';}
				else {echo '<div class="f-size-9">';}
			?>
			<span> <?php echo $trainingtypeText; ?></span></div>
		</td>
		<td class="border-solid b-t-r mid f-size-9">
			<?php 
				$training_conducted_Text = strtoupper($td['training_conducted_by']);
					if (strlen($training_conducted_Text) > 50) {echo '<div class=" f-size-7">';}
					elseif (strlen($training_conducted_Text) > 40) {echo '<div class=" f-size-8">';}
					else {echo '<div class="f-size-9">';}
			?>
			<span> <?php echo $training_conducted_Text; ?></span></div>
		</td>
	</tr>
	<?php
				endforeach
				;
			}
			if ($count < 22) {
				$empty_rows = 22 - $count;
				while ( $empty_rows > 0 ) {
					echo '<tr>
					<td class="border-solid b-t-l mid f-size-10" height="27">' . $condition  . '</td>
					<td class="border-solid mid f-size-10">' . $condition . '</td>
					<td class="border-solid mid f-size-10">' . $condition . '</td>
					<td class="border-solid mid f-size-10" >' . $condition . '</td>
					<td class="border-solid mid f-size-10" >' . $condition . '</td>
					<td class="border-solid mid b-t-r f-size-10">' . $condition . '</td>
					</tr>';
					$empty_rows--;
				}
			}
			?>
		</table>

	<!-- ********************** TRAINING INFO END ********************** -->
	<!-- **********************  OTHER INFO START ********************** -->
	<table class="table-max" style="page-break-after: always">
		<tr>
			<td class="border-thick bg-header text-white f-size-11 bold italic" height="20" colspan=10>VIII. OTHER INFORMATION</td>
		</tr>
		<tr>
			<td class="border-solid b-t-l mid bg-sub" colspan=3 width="168">&nbsp;31.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;SPECIAL SKILLS / HOBBIES</td>
			<td class="border-solid mid bg-sub" colspan=4 width="">32. NON-ACADEMIC DISTINCTIONS / RECOGNITION (Write in full)</td>
			<td class="border-solid b-t-r mid bg-sub" colspan=3 width="168">33. MEMBERSHIP IN<br> ASSOCIATION / ORGANIZATION<br>(Write in full)</td>
		</tr>
		<tr>
			<td class="border-thick italic bold align-c bg-sub" colspan=10 height="10" style="color: red;">Additional Sheet</td>
		</tr>


	<?php	
	$other_params = array($other_params['skills_list'][0]);	
	$count = 7;
	if (! EMPTY ( $other_params)) {
		foreach ( $other_params as $key => $sl ) :
			if ($key > $count) {
				$skills_list_exceeds = TRUE;
				break;
			}
			$skills_list_counter ++;
	?>
	<tr>
		<td class="f-size-10 mid b-s-b border-solid b-t-l mid" width="168" height="27" colspan=3><?php echo N_A ?></td>
		<td class="f-size-10 mid b-s-b border-solid" colspan=4 width="168" height="27"><?php echo N_A ?></td>
		<td class="f-size-10 mid b-s-b border-solid b-t-r mid" colspan=3 width="168" height="27"><?php echo N_A ?></td>
		
	</tr>
	<?php
	endforeach
	;
		if (($key + 1) < $count) {
			while ( ++ $key < $count ) {
				echo '<tr>
						<td class="f-size-10 mid b-s-b border-solid b-t-l mid" width="168" height="27" colspan=3></td>
						<td class="f-size-10 mid b-s-b border-solid" colspan=4 width="168" height="27"></td>
						<td class="f-size-10 mid b-s-b border-solid b-t-r mid" colspan=3 width="168" height="27"></td>
					</tr>';
			}
		}
	} else {
		$key = 0;
		while ( $key < $count ) {
				$key ++;
				$condition =  ($key == "1") ? "" : "" ;
			echo '<tr>					
					<td class="border-solid b-t-l mid f-size-10" height="27" colspan=3>'. $condition .'</td>
					<td class="border-solid mid f-size-10" colspan=4>'. $condition .'</td>
					<td class="border-solid mid f-size-10" colspan=3>'. $condition .'</td>
				</tr>';
		}
	}
	?>
		<?php if ($train_details_counter > 21 && $work_exp_counter > 26) : ?>
			<tr>
				<td class="align-r f-size-7 italic" colspan=10>CS FORM 212 (Revised 2017), Page 5 of {nb}</td>
			</tr>
		<?php else: ?>
			<tr>
				<td class="align-r f-size-7 italic" colspan=10>CS FORM 212 (Revised 2017), Page 4 of {nb}</td>
			</tr>
		<?php endif; ?>
	</table>
	<?php endif; ?>

	<!-- **********************  PAGE 4 ********************** -->	
	<!-- **********************  QUESTIONNAIRE START ********************** -->	
	<table class="table-max">
		<tr>
			<td colspan=3 class="border-thick"	height="2.5"></td>
		</tr>
		<?php		
		$i = 34;
		$parent_question_num = '';
		if ($parent_questions) {
			foreach ( $parent_questions as $key => $p_question ) {
				if ($p_question ['parent_question_flag'] == "Y") {
					if (! EMPTY ( $p_question ['question_txt'] )) {
						echo '
							<tr>
								<td class="pad-t-5 f-size-10 v-top b-t-l b-s-t bg-sub" width="10">'. $i .'.</td>
								<td class="b-s-t b-s-r bg-sub f-size-10 pad-5" width="450">'. $p_question ['question_txt'] . '</td>
								<td class="b-s-t b-s-l b-t-r f-size-10 pad-5"></td>
							</tr>
							';
					} else {
						$parent_question_num = $i;
					}
					
					$letter = 'a';
					foreach ( $child_questions as $c_question ) {
						$question_answer_txt = "";
						$checked_no = "&#9633;";
						$checked_yes = "&#9633;";
						if ($c_question ['parent_question_id'] == $p_question ['question_id']) {
							if ($answers) {
								foreach ( $answers as $answer ) {
									if ($c_question ['question_id'] == $answer ['question_id']) {
										switch ($answer ['question_answer_flag']) {
											case 'Y' :
												$checked_yes = "x ";
												break;
											case 'N' :
												$checked_no = "x ";
												break;
										}
										$question_answer_txt = ! EMPTY ( $answer ['question_answer_txt'] ) ? $answer ['question_answer_txt'] : ' ';
									}
								}
							}
							
							echo '<tr>
									<td class="pad-t-5 bg-sub v-top f-size-10 b-t-l'.(! empty ( $parent_question_num ) ? " b-s-t" : "").'" width="10">'.(! empty ( $parent_question_num ) ? $parent_question_num.'.' : "") .'</td>
									<td rowspan=3 class="b-s-r bg-sub f-size-10 pad-5"  style="border-top: 1px solid #000000; ' . (empty ( $parent_question_num ) ? 'border-top:0px;' : '') . ' border-bottom:0px;">
										<p style="margin-left:25px;text-align:justify;">' . $letter . '.&nbsp;' . $c_question ['question_txt'] . '</p>
									</td>
									<td class="b-t-r b-s-l f-size-10 pad-l-10" style="border-top: 1px solid #000000;' . (empty ( $parent_question_num ) ? 'border-top:0px;' : '') . '">&nbsp;&nbsp;&nbsp;
										' . $checked_yes . ' YES &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $checked_no . ' NO
									</td>
									</tr>
									<tr>
										<td class="b-t-l bg-sub" width="10"></td>
										<td class="b-t-r b-s-l f-size-10 pad-l-10" style="' . (empty ( $parent_question_num ) ? 'border-top:0px;' : '') . ' font-size: 10px !important;">
											<p>&nbsp;&nbsp;&nbsp;&nbsp;If YES, give details: </p>
										</td>
									</tr>
									<tr>
										<td class="b-t-l bg-sub" width="10"></td>
										<td class="b-t-r b-s-l f-size-10 pad-b-5">
											<table>
												<tr>
													<td width="10">&nbsp;</td>
													<td class="b-s-b f-size-10" height="15" width="210">' . $question_answer_txt . '</td>
												</tr>
												<tr>
													<td height="2">&nbsp;</td>
												</tr>
											</table>	
										</td>
									</tr>';
							$letter ++;
							$parent_question_num = '';
						}
					}
				} else {
					$question_answer_txt = "";
					$checked_no = "&#9633;";
					$checked_yes = "&#9633;";
					if ($answers) {
						foreach ( $answers as $answer ) {
							if ($p_question ['question_id'] == $answer ['question_id']) {
								switch ($answer ['question_answer_flag']) {
									case 'Y' :
										$checked_yes = "x ";
										break;
									case 'N' :
										$checked_no = "x ";
										break;
								}
								$question_answer_txt = ! EMPTY ( $answer ['question_answer_txt'] ) ? $answer ['question_answer_txt'] : '';
							}
						}
					}
					echo '
							<tr>
								<td class="pad-t-5 bg-sub v-top b-t-l b-s-t f-size-10 bg-sub" width="10">'. $i .'.</td>
								<td rowspan=3 class="b-s-r b-s-t bg-sub f-size-10 pad-5">
									<p style="margin-left:25px;text-align:justify;">' . $p_question ['question_txt'] . '</p>
								</td>
								<td class="b-t-r b-s-l b-s-t f-size-10 pad-l-10">&nbsp;&nbsp;&nbsp;
									' . $checked_yes . ' YES &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $checked_no . ' NO
								</td>
							</tr>
							<tr>
								<td class="b-t-l bg-sub" width="10"></td>
								<td class="b-t-r b-s-l f-size-10 pad-l-10" style="' . (empty ( $parent_question_num ) ? 'border-top:0px;' : '') . ' border-bottom:0px; height: 0px !important">
									<p>&nbsp;&nbsp;&nbsp;&nbsp;If YES, give details: </p>
								</td>
							</tr>
							<tr>
								<td class="b-t-l bg-sub" width="10"></td>
								<td class="b-t-r b-s-l b-s-b f-size-10 pad-b-5">
									<table>
										<tr>
											<td width="10">&nbsp;</td>
											<td class="b-s-b f-size-10" height="15" width="210">' . $question_answer_txt . '</td>
										</tr>
										<tr>
											<td height="2">&nbsp;</td>
										</tr>
									</table>									
								</td>
							</tr>';
					$letter ++;
				}
				$parent_question_num = 0;
				$i ++;
			}
		}
		?>
	</table>
	<!-- **********************  QUESTIONNAIRE END ********************** -->	

	<div style="width: 100%;">
		<div style="width: 77%; border-style: solid; border-width: 0; margin: 0px; float: left;">
			<table class="table-max">
				<tr>
					<td class="border-thick bg-sub" height="20" colspan=3>&nbsp;41. REFERENCES <span style="color: red">(Person not related by consanguinity or affinity to applicant /appointee)</span></td>
				</tr>
				<tr>
					<td class="border-solid b-t-l bg-sub mid" width="250" height="27">NAME</td>
					<td class="border-solid bg-sub mid" width="200">ADDRESS</td>
					<td class="border-solid b-t-r bg-sub mid">TEL NO.</td>
				</tr>
				<tr>
					<td class="border-solid b-t-l mid f-size-10" height="27"><?php echo !EMPTY($refn_details) ? $refn_details[0]['reference_full_name'] : N_A ?></td>
					<td class="border-solid mid f-size-10"><?php echo !EMPTY($refn_details) ? $refn_details[0]['reference_address'] : N_A ?></td>
					<td class="border-solid b-t-r mid f-size-10"><?php echo !EMPTY($refn_details) ? $refn_details[0]['reference_contact_info'] : N_A ?></td>
				</tr>
				<tr>
					<td class="border-solid b-t-l mid f-size-10" height="27"><?php echo !EMPTY($refn_details) ? $refn_details[1]['reference_full_name'] : N_A ?></td>
					<td class="border-solid mid f-size-10"><?php echo !EMPTY($refn_details) ? $refn_details[1]['reference_address'] : N_A ?></td>
					<td class="border-solid b-t-r mid f-size-10"><?php echo !EMPTY($refn_details) ? $refn_details[1]['reference_contact_info'] : N_A ?></td>
				</tr>
				<tr>
					<td class="border-solid b-t-l mid f-size-10" height="27"><?php echo !EMPTY($refn_details) ? $refn_details[2]['reference_full_name'] : N_A ?></td>
					<td class="border-solid mid f-size-10"><?php echo !EMPTY($refn_details) ? $refn_details[2]['reference_address'] : N_A ?></td>
					<td class="border-solid b-t-r mid f-size-10"><?php echo !EMPTY($refn_details) ? $refn_details[2]['reference_contact_info'] : N_A ?></td>
				</tr>
				<tr>
					<td class="border-thick bg-sub f-size-10 v-top pad-l-10" align="justify" height="75" colspan=3 style="padding: 5px;">
						42. I declare under oath that I have personally accomplished this Personal Data Sheet which is a true, correct and complete statement pursuant to the provisions of pertinent laws, rules and regulations of the Republic of the philippines. I authorize the agency head/authorized representative to verify/validate the contents stated herein. I filing of administrative/criminal case/s against me.
					</td>
				</tr>
				<tr>
					<td class="b-t-l" height="10" colspan3></td>
				</tr>
				<tr>
					<td class="b-t-l" colspan=3>
						<table class="table-max">
							<tr>
								<td width="10"></td>
								<td class="border-thick bg-sub" height="30" width="">Government Issued ID (i.e.Passport, GSIS, SSS, PRC, Driver's License, <br>etc.) PLEASE INDICATE ID Number and Date of<br>Issuance</td>
								<td width="15"></td>
								<td class="b-t-l b-t-r b-t-t" width="255"></td>
							</tr>
							<tr>
								<td></td>
								<td class="border-solid b-t-l b-t-r" height="25">Government Issued ID: <?php echo !EMPTY($declaration['govt_issued_id']) ? strtoupper($declaration['govt_issued_id']) : N_A?></td>
								<td></td>
								<td class="b-t-l b-t-r b-t-b" rowspan=3>
									<table class="table-max">
										<tr>
											<td height="40" width="253"></td>
										</tr>
										<tr>
											<td class="b-s-t bg-sub mid" height="13">Signature (Sign inside the box)</td>
										</tr>
										<tr>
											<td class="b-s-t mid f-size-10" height="13"> <?php echo !EMPTY($personal_info['date_accomplished']) ? $personal_info['date_accomplished'] : N_A ?></td>
										</tr>
										<tr>
											<td class="b-s-t bg-sub mid" height="13">Date Accomplished</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td></td>
								<td class="border-solid b-t-l b-t-r" height="25">ID/License/Passport No.: <?php echo !EMPTY($declaration['ctc_no']) ? strtoupper($declaration['ctc_no']) : N_A?></td>
								<td></td>
							</tr>
							<tr>
								<td></td>
								<td class="border-thick b-s-t" height="25">Date/Place of Issuance: 
								<?php echo !EMPTY($declaration['issued_date']) ? $declaration['issued_date'] : N_A?>
								<?php echo !EMPTY($declaration['issued_place']) ? ' / ' . strtoupper($declaration['issued_place']) : ''?></td>
								<td></td>
							</tr>
						</table>
					</td>
				</tr>		
			</table>
			<table class="table-max">		
				<tr>
					<td class="b-t-b b-t-l" height="10"></td>
				</tr>
			</table>
		</div>
		<div style="width: 23%; border-style: solid; border-width: 0; float: left;">
			<table class="table-max">
				<tr>
					<td class="b-t-t b-t-r b-t-b" height="339.5">
						<table class="table-max">
							<tr>
								<td class="mid" height="20" colspan=3></td>
							</tr>
							<tr>
								<td width="25"></td>
								<td class="border-solid mid" width="110" height="140">
									ID picture taken within<br>
									the last  6 months<br>
									3.5 cm. X 4.5 cm<br>
									(passport size)<br>
									<br>
									With full and handwritten<br>
									name tag and signature over<br>
									printed name<br>
									<br>
									Computer generated<br>
									or photocopied picture<br>
									is not acceptable
								</td>
								<td width="25"></td>
							</tr>
							<tr>
								<td class="mid" height="30" colspan=3>PHOTO</td>
							</tr>
						</table>
						<table class="table-max">
							<tr>
								<td width="10"></td>
								<td class="b-t-l b-t-r b-t-t b-s-b" width="139" height="110"></td>
								<td width="10"></td>
							</tr>
							<tr>
								<td width="10"></td>
								<td class="border-thick b-s-t bg-sub mid" width="139" height="15">Right Thumbmark</td>
								<td width="10"></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</div>
	</div>
	<table class="table-max">
		<tr>
			<td class="b-t-l b-t-r b-t-b" height="100">
				<table>					
					<tr>
						<td class="f-size-9 mid" colspan=3 height="30"><?php echo str_repeat($space, 10)?>SUBSCRIBED AND SWORN to before me this ,<?php echo str_repeat($space, 5)?><u><?php echo str_repeat($space, 50)?></u><?php echo str_repeat($space, 5)?>affiant exhibiting his/her validly issued government ID as indicated above.</td>
					</tr>			
					<tr>
						<td width="250"></td>
						<td class="b-t-l b-t-r b-t-t" height="60"></td>
						<td></td>
					</tr>
					<tr>
						<td></td>
						<td class="b-t-l b-t-r b-t-b b-s-t bg-sub mid f-size-10" width="250" height="20">Person Administering Oath</td>
						<td></td>
					</tr>
					<tr>
						<td height="10"></td>
					</tr>
				</table>
			</td>
		</tr>
		<?php if ($train_details_counter > 21 AND $work_exp_counter > 26) : ?>
			<tr>
				<td class="align-r f-size-7 italic" >CS FORM 212 (Revised 2017), Page 6 of {nb}</td>
			</tr>
		<?php elseif ($train_details_counter > 21 OR $work_exp_counter > 26) : ?>
			<tr>
				<td class="align-r f-size-7 italic" >CS FORM 212 (Revised 2017), Page 5 of {nb}</td>
			</tr>
		<?php else: ?>
			<tr>
				<td class="align-r f-size-7 italic" >CS FORM 212 (Revised 2017), Page 4 of {nb}</td>
			</tr>
		<?php endif; ?>
	</table>
		

<!-- ************************************************************************** -->
</body>

</html>
