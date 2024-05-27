<?php 
	$contact = array_combine(range(1, count($contact_info)), array_values($contact_info));
	$address = array_combine(range(1, count($address_info)), array_values($address_info));
	$work_exp_counter = 0;
	$govt_exam_counter = 0;
	$vol_details_counter = 0;
	$train_details_counter = 0;
	$skills_list_counter = 0;
	$member_list_counter = 0;
	$recog_list_counter = 0;
	$educ_details_counter = 0;
?>

<html>
<head>
	<title>Personal Data Sheet</title>
	<link rel="stylesheet" href="<?php echo base_url().PATH_CSS ?>reports.css" type="text/css"/>
</head>
 	
<body>
<!-- **********************HEADER********************** -->
<table width="100%">
	<tr>
		<td colspan="14" class="td-border-thick-top td-border-thick-left td-border-thick-right" height="2.5"></td>
	</tr>
	<tr>
		<td colspan="14" class="td-border-thick-top td-border-thick-left td-border-thick-right"><span style="font-size: 8px;">CS FORM 212 (Revised 2005)</span></td>
	</tr>
	<tr>
		<td colspan="14" class="td-border-thick-right td-border-thick-left" height="60" align="center"><span style="font-size: 23px;"><b>PERSONAL DATA SHEET</b></span></td>
	</tr>
	<tr>
		<td colspan="14" class="td-border-thick-bottom td-border-thick-left td-border-thick-right">
			<table>
				<tr>
					<td colspan="8" width="430px"><span style="font-size: 8px;">Print Legibly. Mark appropriate boxes <span style="font-size: 13px;">&#9633;</span> with <span style="font-size: 15px;">&#10003;</span> and use separate sheet if necessary.</span></td>
					<td colspan="3" class="td-border-top td-border-left td-border-right" width="60px" align="center" style="background-color: #7e7e7e; color: white;"><span style="font-size: 8px;">1.CS ID No.</span></td>
					<td colspan="3" class="td-border-top" width="220px" align="right"><span style="font-size: 8px;">(to be filled up by CSC)</span></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="14" class="td-border-thick-left td-border-thick-right" height="1.5"></td>
	</tr>
</table>

<!-- PERSONAL INFO -->
<table width="100%" style="font-family: Arial Black, Gadget, sans-serif; calibri; width: 100%; border: 1px solid #000000; border-collapse: collapse; page-break-after:always;">
	<tbody>
		<tr>
			<td colspan="14" class="td-border-thick-top td-border-thick-left td-border-thick-right td-border-thick-bottom" style="background-color: #7e7e7e; color: white; font-size: 11px;"><i>I. PERSONAL INFORMATION</i></td>
		</tr>
		<tr style="page-break-after:always;">
			<td colspan="1" rowspan="3" class="td-border-thick-left" valign="top" style="padding: 3px;font-size: 10px;background:#e6e6e6;">2. &nbsp;</td>
			<td colspan="1" style="padding: 3px; text-indent: 1px; height: 20px;border-right:  1px solid #000000 ; width: 20%; font-size: 10px;background:#e6e6e6;" >SURNAME</td>
			<td colspan="12" class="td-border-thick-right" style="width:230px; padding: 3px;  height: 20px;border-bottom: 1px solid #000000; font-size: 12px; font-weight: lighter;"><?php echo !EMPTY($personal_info['last_name']) ? $personal_info['last_name']:NOT_APPLICABLE?></td>
		</tr>
		<tr>
			<td colspan="1" style="padding: 3px; text-indent: 1px; height: 20px;border-right:  1px solid #000000; font-size: 10px;background:#e6e6e6;">FIRSTNAME</td>
			<td colspan="12" class="td-border-thick-right" style="width:230px; padding: 3px; text-indent: 1px; height: 20px;border-bottom: 1px solid #000000; font-size: 12px; font-weight: lighter;"><?php echo !EMPTY($personal_info['first_name']) ? $personal_info['first_name']:NOT_APPLICABLE?></td>
		</tr>
		<tr>
			<td colspan="1" style="padding: 3px; text-indent: 1px; height: 20px;border-right:  1px solid #000000;  font-size: 10px;background:#e6e6e6;  border-bottom: 1px solid #000000;">MIDDLE NAME</td>
			<td colspan="7" style="width:230px; padding: 3px; text-indent: 1px; height: 20px;border-right:  1px solid #000000;   border-bottom: 1px solid #000000; font-size: 12px; font-weight: lighter;"><?php echo !EMPTY($personal_info['middle_name']) ? $personal_info['middle_name']:NOT_APPLICABLE?></td>
			<td colspan="4" style="width: 20%; padding: 3px; text-indent: 1px; height: 20px;border-right:  1px solid #000000; font-size: 10px; background:#e6e6e6; border-bottom: 1px solid #000000;">3. NAME EXTENSION <span style="font-size: 8px;"> (e.g. Jr., Sr)</span></td>			
			<td colspan="1" class="td-border-thick-right" style=" padding: 3px; text-indent: 1px; height: 20px;border-bottom: 1px solid #000000; font-size: 12px; font-weight: lighter;"><?php echo !EMPTY($personal_info['ext_name']) ? $personal_info['ext_name']:NOT_APPLICABLE?></td>
		</tr>
		<tr>
			<td colspan="1" class="td-border-thick-left" style="background:#e6e6e6;  border-bottom: 1px solid #000000;">4. &nbsp;</td>
			<td colspan="3" style="padding: 3px; text-indent: 1px; height: 20px;border-right:  1px solid #000000; font-size: 10px;background:#e6e6e6;  border-bottom: 1px solid #000000;">DATE OF BIRTH<span style="font-size: 8px;"> (mm/dd/yyyy)</span></td>
			<td colspan="2" style=" padding: 3px; text-indent: 1px; height: 20px;border-right:  1px solid #000000;   border-bottom: 1px solid #000000; font-size: 12px; font-weight: lighter;"><?php echo !EMPTY($personal_info['birth_date']) ? ($personal_info['birth_date']):NOT_APPLICABLE?></td>
			<td colspan="2" rowspan="3" style="padding: 3px; text-indent: 1px; height: 20px;border-right:  1px solid #000000; font-size: 10px; background:#e6e6e6; vertical-align: top;">16. RESIDENTIAL ADDRESS</td>
					<div style="text-align:left; font-size: 12px; font-weight: lighter;"></div>
			<td colspan="6" class="td-border-thick-right" rowspan="3" style="padding: 3px; text-indent: 1px; height: 20px;border-bottom: 1px solid #000000; font-size: 12px; font-weight: lighter;">
				<?php echo !EMPTY($address[RESIDENTIAL_ADDRESS]['address'])? $address[RESIDENTIAL_ADDRESS]['address']:'N/A' ?>
			</td>
		</tr>
		<tr>
			<td colspan="1" class="td-border-thick-left" style="background:#e6e6e6;  border-bottom: 1px solid #000000;">5. &nbsp;</td>
			<td colspan="1" style="padding: 3px; text-indent: 1px; height: 20px;border-right:  1px solid #000000; font-size: 10px;background:#e6e6e6;  border-bottom: 1px solid #000000;">PLACE OF BIRTH</td>
			<td colspan="4" style=" padding: 3px; text-indent: 1px; height: 20px;border-right:  1px solid #000000; border-bottom: 1px solid #000000; font-size: 12px; font-weight: lighter; "><?php echo !EMPTY($personal_info['birth_place']) ? $personal_info['birth_place']:NOT_APPLICABLE?></td>
		</tr>
		<tr> 
			<td colspan="1" class="td-border-thick-left" style="background:#e6e6e6;  border-bottom: 1px solid #000000;">6. &nbsp;</td>
			<td colspan="1" style="padding: 3px; text-indent: 1px; height: 20px;border-right:  1px solid #000000; font-size: 10px;background:#e6e6e6;  border-bottom: 1px solid #000000;">SEX</td>
			<td colspan="4" style=" padding: 3px; text-indent: 1px; height: 20px;border-right:  1px solid #000000; border-bottom: 1px solid #000000; font-size: 12px; font-weight: lighter;">
				<table>
					<tr><td></td>
						<?php 
						if(!EMPTY($per_params['gender'])){
							foreach($per_params['gender'] as $gen):
								$box = ($personal_info['gender_code'] == $gen['gender_code'])?"&#9745;":"&#9633;";
						?>
							<td style="font-size: 12px; font-weight: lighter;padding: 3px;">
							<?php echo $box ?>&nbsp;<?php echo $gen['gender'] ?>
							</td>
						<?php
								endforeach; 
							}
						?>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td colspan="1" class="td-border-thick-left" style="background:#e6e6e6;">7. &nbsp;</td>
			<td colspan="1" style="padding: 3px; text-indent: 1px; height: 20px;border-right:  1px solid #000000; font-size: 10px;background:#e6e6e6;">CIVIL STATUS</td>
			<td colspan="4" rowspan="3" style="padding: 3px; text-indent: 1px; height: 20px;border-right:  1px solid #000000;   border-bottom: 1px solid #000000; font-size: 12px; font-weight: lighter;">
				
					<table>

				<?php // CIVIL STATUS
					if(!EMPTY($per_params['civil_status'])){
						// $counter = 1;
						// $count_per_params = count($per_params);
						// foreach($per_params['civil_status'] as $civ):
						for($i=0; $i < count($per_params['civil_status']); $i+=2) :
						$line1 = '';
						$line2 = '';
						echo '<tr><td></td>';
						$box = ($personal_info['civil_status_id'] == $per_params['civil_status'][$i]['civil_status_id']) ? "&#9745;" : "&#9633;";
						if (strpos(strtolower($per_params['civil_status'][$i]['civil_status_name']), 'others') !== false)
						$line1 = ' &#95;&#95;&#95;&#95;&#95;&#95;&#95;&#95;&#95;&#95;&#95;&#95;&#95;&#95;';
						if (strpos(strtolower($per_params['civil_status'][$i+1]['civil_status_name']), 'others') !== false)
						$line2 = ' &#95;&#95;&#95;&#95;&#95;&#95;&#95;&#95;&#95;&#95;&#95;&#95;&#95;&#95;';
						echo !empty($per_params['civil_status'][$i]['civil_status_name']) ? ('<td style="font-size: 12px; font-weight: lighter;padding: 3px;">' . $box." ".$per_params['civil_status'][$i]['civil_status_name'] . $line1 . '</td>') : '';
						$box = ($personal_info['civil_status_id'] == $per_params['civil_status'][$i+1]['civil_status_id']) ? "&#9745;" : "&#9633;";
						echo !empty($per_params['civil_status'][$i+1]['civil_status_name']) ? ('<td style="font-size: 12px; font-weight: lighter;padding: 3px;">' . $box." ".$per_params['civil_status'][$i+1]['civil_status_name'] . $line2 .'</td>') : '';
						echo '</tr>';
						endfor;
					}
				?>
				</table>
			</td>
			<td colspan="2" style="padding: 3px; text-indent: 1px; height: 20px;border-right:  1px solid #000000; font-size: 10px; background:#e6e6e6;">&nbsp;&nbsp;ZIP CODE</td>
					<div style="text-align:left; font-size: 12px; font-weight: lighter;"></div>
			<td colspan="6" class="td-border-thick-right" style="padding: 3px; text-indent: 1px; height: 20px;border-bottom: 1px solid #000000; font-size: 12px; font-weight: lighter;">
				<?php echo !EMPTY($address[RESIDENTIAL_ADDRESS]['postal_number'])? $address[RESIDENTIAL_ADDRESS]['postal_number']:'N/A' ?>
			</td>
		</tr>
		<tr>
			<td colspan="6" class="td-border-thick-left" style="padding: 3px; text-indent: 1px; height: 20px;border-right:  1px solid #000000; font-size: 10px;background:#e6e6e6;"></td>
			<td colspan="2" style="padding: 3px; text-indent: 1px; height: 20px;border-right:  1px solid #000000; font-size: 10px; background:#e6e6e6; border-bottom: 1px solid #000000;">17. TELEPHONE NO.</td>
					<div style="text-align:left; font-size: 12px; font-weight: lighter;"></div>
			<td colspan="6" class="td-border-thick-right" style="padding: 3px; text-indent: 1px; height: 20px;border-bottom: 1px solid #000000; font-size: 12px; font-weight: lighter;">
					<?php echo !EMPTY($contact[RESIDENTIAL_NUMBER]['contact_value'])? $contact[RESIDENTIAL_NUMBER]['contact_value']:NOT_APPLICABLE ?>
			</td>
		</tr>
		<tr>
			<td colspan="6" class="td-border-thick-left" style="padding: 3px; text-indent: 1px; height: 20px;border-right:  1px solid #000000; font-size: 10px;background:#e6e6e6;  border-bottom: 1px solid #000000;"></td>
			<td colspan="2" rowspan="3" style="padding: 3px; text-indent: 1px; height: 20px;border-right:  1px solid #000000; font-size: 10px; background:#e6e6e6; vertical-align: top;">18. PERMANENT ADDRESS</td>
			<td colspan="6" class="td-border-thick-right" rowspan="3" style="padding: 3px; text-indent: 1px; height: 20px;border-bottom: 1px solid #000000; font-size: 12px; font-weight: lighter;">
				<?php echo !EMPTY($address[PERMANENT_ADDRESS]['address'])? $address[PERMANENT_ADDRESS]['address']:'N/A' ?>
			</td>
		</tr>
		<tr>
			<td colspan="1" class="td-border-thick-left" style="background:#e6e6e6;  border-bottom: 1px solid #000000;">8. &nbsp;</td>
			<td colspan="1" style="padding: 3px; text-indent: 1px; height: 20px;border-right:  1px solid #000000; font-size: 10px;background:#e6e6e6;  border-bottom: 1px solid #000000;">CITIZENSHIP</td>
			<td colspan="4" style=" padding: 3px; text-indent: 1px; height: 20px;border-right:  1px solid #000000; border-bottom: 1px solid #000000; font-size: 12px; font-weight: lighter;"><?php echo !EMPTY($personal_info['citizenship_name']) ? $personal_info['citizenship_name']:""?></td>
		</tr>
		<tr>
			<td colspan="1" class="td-border-thick-left" style="background:#e6e6e6;  border-bottom: 1px solid #000000;">9. &nbsp;</td>
			<td colspan="1" style="padding: 3px; text-indent: 1px; height: 20px;border-right:  1px solid #000000; font-size: 10px;background:#e6e6e6;  border-bottom: 1px solid #000000;">HEIGHT<span style="font-size: 8px;">(m)</span></td>
			<td colspan="4" style=" padding: 3px; text-indent: 1px; height: 20px;border-right:  1px solid #000000; border-bottom: 1px solid #000000; font-size: 12px; font-weight: lighter;"><?php echo !EMPTY($personal_info['height']) ? $personal_info['height']:""?></td>
		</tr>
		<tr>
			<td colspan="1" class="td-border-thick-left" style="background:#e6e6e6;  border-bottom: 1px solid #000000;">10. &nbsp;</td>
			<td colspan="1" style="padding: 3px; text-indent: 1px; height: 20px;border-right:  1px solid #000000; font-size: 10px;background:#e6e6e6;  border-bottom: 1px solid #000000;">10. WEIGHT<span style="font-size: 8px;">(kg)</span></td>
			<td colspan="4" style=" padding: 3px; text-indent: 1px; height: 20px;border-right:  1px solid #000000;   border-bottom: 1px solid #000000; font-size: 12px; font-weight: lighter;"><?php echo !EMPTY($personal_info['weight']) ? $personal_info['weight']:""?></td>
			<td colspan="2" style="padding: 3px; text-indent: 1px; height: 20px;border-right:  1px solid #000000; font-size: 10px; background:#e6e6e6;">&nbsp;&nbsp;ZIP CODE</td>
			<td colspan="6" class="td-border-thick-right" style="padding: 3px; text-indent: 1px; height: 25px;border-bottom: 1px solid #000000; font-size: 12px; font-weight: lighter;">
				<?php echo !EMPTY($address[PERMANENT_ADDRESS]['postal_number'])? $address[PERMANENT_ADDRESS]['postal_number']:'N/A' ?>
			</td>
		</tr>
		<tr>
			<td colspan="1" class="td-border-thick-left" style="background:#e6e6e6;  border-bottom: 1px solid #000000;">11. &nbsp;</td>
			<td colspan="1" style="padding: 3px; text-indent: 1px; height: 20px;border-right:  1px solid #000000; font-size: 10px;background:#e6e6e6;  border-bottom: 1px solid #000000;">11. BLOOD TYPE</td>
			<td colspan="4" style=" padding: 3px; text-indent: 1px; height: 20px;border-right:  1px solid #000000;   border-bottom: 1px solid #000000; font-size: 12px; font-weight: lighter;"><?php echo !EMPTY($personal_info['blood_type_name']) ? $personal_info['blood_type_name']:""?></td>
			<td colspan="2" style="padding: 3px; text-indent: 1px; height: 20px;border-left:  1px solid #000000;  border-right:  1px solid #000000; font-size: 10px; background:#e6e6e6; border-bottom: 1px solid #000000;">19. TELEPHONE NO.</td>
			<td colspan="6" class="td-border-thick-right" style="padding: 3px; text-indent: 1px; height: 20px;border-bottom: 1px solid #000000; font-size: 12px; font-weight: lighter;">
				<?php echo !EMPTY($contact[PERMANENT_NUMBER]['contact_value'])? $contact[PERMANENT_NUMBER]['contact_value']:NOT_APPLICABLE ?>
			</td>
		</tr>
		<tr>
			<td colspan="1" class="td-border-thick-left" style="background:#e6e6e6;  border-bottom: 1px solid #000000;">12. &nbsp;</td>
			<td colspan="1" style="padding: 3px; text-indent: 1px; height: 20px;border-right:  1px solid #000000; font-size: 10px;background:#e6e6e6;  border-bottom: 1px solid #000000;">12. GSIS ID NO.</td>
			<td colspan="4" style=" padding: 3px; text-indent: 1px; height: 20px;border-right:  1px solid #000000;   border-bottom: 1px solid #000000; font-size: 12px; font-weight: lighter;"><?php echo !EMPTY($identification_info[GSIS_TYPE_ID-1]['identification_value']) ? format_identifications($identification_info[GSIS_TYPE_ID-1]['identification_value'], $identification_info[GSIS_TYPE_ID-1]['format']) :NOT_APPLICABLE?></td>
			<td colspan="2" style="padding: 3px; text-indent: 1px; height: 20px;background:#e6e6e6; border: 1px solid #000000;">20. E-MAIL ADDRESS <span style="font-size: 8px;">(if any)</span></td>
			<td colspan="6" class="td-border-thick-right" style=" padding: 3px; text-indent: 1px; height: 20px;border: 1px; font-size: 12px; font-weight: lighter;">
				<?php echo !EMPTY($contact[EMAIL]['contact_value'])? $contact[EMAIL]['contact_value']:NOT_APPLICABLE ?>
			</td>
		</tr>
		<tr>
			<td colspan="1" class="td-border-thick-left" style="background:#e6e6e6;  border-bottom: 1px solid #000000;">13. &nbsp;</td>
			<td colspan="1" style="padding: 3px; text-indent: 1px; height: 20px;border-right:  1px solid #000000; font-size: 10px;background:#e6e6e6;  border-bottom: 1px solid #000000;">13. PAG-IBIG NO.</td>
			<td colspan="4" style=" padding: 3px; text-indent: 1px; height: 20px;border-right:  1px solid #000000;   border-bottom: 1px solid #000000; font-size: 12px; font-weight: lighter;"><?php echo !EMPTY($identification_info[PAGIBIG_TYPE_ID-1]['identification_value']) ? format_identifications($identification_info[PAGIBIG_TYPE_ID-1]['identification_value'], $identification_info[PAGIBIG_TYPE_ID-1]['format']) :NOT_APPLICABLE?></td>
			<td colspan="2" style="padding: 3px; text-indent: 1px; height: 20px;border-right:  1px solid #000000; font-size: 10px; background:#e6e6e6; border-bottom: 1px solid #000000;">21. CELLPHONE NO.<span style="font-size: 8px;"> (if any)</span></td>
			<td colspan="6" class="td-border-thick-right" style="padding: 3px; text-indent: 1px; height: 20px;border-bottom: 1px solid #000000; font-size: 12px; font-weight: lighter;">
				<?php echo !EMPTY($contact[MOBILE_NUMBER]['contact_value'])? $contact[MOBILE_NUMBER]['contact_value']:NOT_APPLICABLE ?>
			</td>
		</tr>
		<tr>
			<td colspan="1" class="td-border-thick-left" style="background:#e6e6e6;  border-bottom: 1px solid #000000;">14. &nbsp;</td>
			<td colspan="1" style="padding: 3px; text-indent: 1px; height: 20px;border-right:  1px solid #000000; font-size: 10px;background:#e6e6e6;  border-bottom: 1px solid #000000;">14. PHILHEALTH NO.</td>
			<td colspan="4" style=" padding: 3px; text-indent: 1px; height: 20px;border-right:  1px solid #000000;   border-bottom: 1px solid #000000; font-size: 12px; font-weight: lighter;"><?php echo !EMPTY($identification_info[PHILHEALTH_TYPE_ID-1]['identification_value']) ? format_identifications($identification_info[PHILHEALTH_TYPE_ID-1]['identification_value'], $identification_info[PHILHEALTH_TYPE_ID-1]['format']) :NOT_APPLICABLE?></td>
			<td colspan="2" style="padding: 3px; text-indent: 1px; height: 20px;border-right:  1px solid #000000; font-size: 10px; background:#e6e6e6; border-bottom: 1px solid #000000;">22. AGENCY EMPLOYEE NO.</td>
			<td colspan="6" class="td-border-thick-right" style="padding: 3px; text-indent: 1px; height: 20px;border-bottom: 1px solid #000000; font-size: 12px; font-weight: lighter;"><?php echo !EMPTY($personal_info['agency_employee_id']) ? $personal_info['agency_employee_id']:NOT_APPLICABLE?></td>
		</tr>
		<tr>
			<td colspan="1" class="td-border-thick-left" style="background:#e6e6e6;">15. &nbsp;</td>
			<td colspan="1" style="padding: 3px; text-indent: 1px; height: 20px;border-right:  1px solid #000000; font-size: 10px;background:#e6e6e6;">15. SSS NO.</td>
			<td colspan="4" style=" padding: 3px; text-indent: 1px; height: 20px;border-right:  1px solid #000000; font-size: 12px; font-weight: lighter;"><?php echo !EMPTY($identification_info[SSS_TYPE_ID-1]['identification_value']) ? format_identifications($identification_info[SSS_TYPE_ID-1]['identification_value'], $identification_info[SSS_TYPE_ID-1]['format']):NOT_APPLICABLE?></td>
			<td colspan="2" style="padding: 3px; text-indent: 1px; height: 20px;border-right:  1px solid #000000; font-size: 10px; background:#e6e6e6;">23.TIN</td>
			<td colspan="6" class="td-border-thick-right" style="padding: 3px; text-indent: 1px; height: 20px;font-size: 12px; font-weight: lighter;">
				<?php echo !EMPTY($identification_info[TIN_TYPE_ID-1]['identification_value']) ? format_identifications($identification_info[TIN_TYPE_ID-1]['identification_value'],$identification_info[TIN_TYPE_ID-1]['format']) :NOT_APPLICABLE?>
			</td>
		</tr>
		<tr>
			<td colspan="14" class="td-border-thick-top td-border-thick-left td-border-thick-right td-border-thick-bottom" style="background-color: #7e7e7e; color: white; font-size: 11px;"><i>II. FAMILY BACKGROUND</i></td>
		</tr>
		<tr>
			<td colspan="7" class="td-border-thick-top td-border-thick-left td-border-thick-right td-border-thick-bottom">
				<table>

					<?php 
						if(!EMPTY($spouse)){
						foreach($spouse as $sp): 
							$spouse_first	= (!EMPTY($sp['relation_first_name'])) ? $sp['relation_first_name'] 		  : NOT_APPLICABLE;
							$spouse_last	= (!EMPTY($sp['relation_last_name'])) ? $sp['relation_last_name']			  : NOT_APPLICABLE;
							$spouse_mid		= (!EMPTY($sp['relation_middle_name'])) ? $sp['relation_middle_name'] 		  : NOT_APPLICABLE;
							$spouse_occ		= (!EMPTY($sp['relation_occupation'])) ? $sp['relation_occupation']			  : NOT_APPLICABLE;
							$spouse_emp		= (!EMPTY($sp['relation_company'])) ? $sp['relation_company']				  : NOT_APPLICABLE;
							$spouse_addr	= (!EMPTY($sp['relation_company_address'])) ? $sp['relation_company_address'] : NOT_APPLICABLE;
							$spouse_con		= (!EMPTY($sp['relation_contact_num'])) ? $sp['relation_contact_num']		  : NOT_APPLICABLE;
						endforeach; }
					?>
					<tr>
						<td colspan="1" rowspan="7" class="td-border-thick-left" valign="top" style="padding: 3px;font-size: 10px;background:#e6e6e6; border-bottom: 1px solid #000000;">24.&nbsp;</td>
						<td colspan="1" style="padding: 3px; text-indent: 1px; height: 20px;border-right:  1px solid #000000; width: 40%; font-size: 10px;background:#e6e6e6;">SPOUSE'S SURNAME</td>
						<td colspan="5" style="padding: 3px; text-indent: 1px; height: 20px;border-right:  1px solid #000000; border-bottom: 1px solid #000000; font-size: 10px; font-weight: lighter;"><?php echo $spouse_last ?>
						</td>
					</tr>
					<tr>
						<td colspan="1" style="padding: 3px; text-indent: 1px; height: 20px;border-right:  1px solid #000000; width: 20%; font-size: 10px;background:#e6e6e6;">FIRST NAME</td>
						<td colspan="5" style="padding: 3px; text-indent: 1px; height: 20px;border-right:  1px solid #000000; border-bottom: 1px solid #000000; font-size: 10px; font-weight: lighter;"><?php echo $spouse_first ?></td>
					</tr>
					<tr>
						<td colspan="1" style="padding: 3px; text-indent: 1px; height: 20px;border-right:  1px solid #000000; width: 20%; font-size: 10px;background:#e6e6e6;">MIDDLE NAME</td>
						<td colspan="5" style="padding: 3px; text-indent: 1px; height: 20px;border-right:  1px solid #000000; border-bottom: 1px solid #000000; font-size: 10px; font-weight: lighter;"><?php echo $spouse_mid ?></td>
					</tr>
					<tr>
						<td colspan="1" style="padding: 3px; text-indent: 1px; height: 20px;border-right:  1px solid #000000; width: 20%; font-size: 10px;background:#e6e6e6;">OCCUPATION</td>
						<td colspan="5" style="padding: 3px; text-indent: 1px; height: 20px;border-right:  1px solid #000000; border-bottom: 1px solid #000000; font-size: 10px; font-weight: lighter;"><?php echo $spouse_occ ?></td>
					</tr>
					<tr>
						<td colspan="1" style="padding: 3px; text-indent: 1px; height: 20px;border-right:  1px solid #000000; width: 20%; font-size: 10px;background:#e6e6e6;">EMPLOYER/BUS. NAME</td>
						<td colspan="5" style="padding: 3px; text-indent: 1px; height: 20px;border-right:  1px solid #000000; border-bottom: 1px solid #000000; font-size: 10px; font-weight: lighter;"><?php echo $spouse_emp ?></td>
					</tr>
					<tr>
						<td colspan="1" style="padding: 3px; text-indent: 1px; height: 20px;border-right:  1px solid #000000; width: 20%; font-size: 10px;background:#e6e6e6;">BUSINESS ADDRESS</td>
						<td colspan="5" style="padding: 3px; text-indent: 1px; height: 20px;border-right:  1px solid #000000; border-bottom: 1px solid #000000; font-size: 10px; font-weight: lighter;"><?php echo $spouse_addr ?></td>
					</tr>
					<tr>
						<td colspan="1" style="padding: 3px; text-indent: 1px; height: 20px;border-right:  1px solid #000000; width: 20%; font-size: 10px;background:#e6e6e6; border-bottom: 1px solid #000000;">TELEPHONE NO.</td>
						<td colspan="5" style="padding: 3px; text-indent: 1px; height: 20px;border-right:  1px solid #000000; border-bottom: 1px solid #000000; font-size: 10px; font-weight: lighter;"><?php echo $spouse_con ?></td>
					</tr>
					<tr>
						<td colspan="7" style="padding: 3px; text-indent: 1px; height: 20px;border-bottom: 1px solid #000000; border-right: 1px solid #000000; font-size: 9px; font-weight: lighter;background:#e6e6e6; " align="center"><i>(Continue on separate sheet if necessary)</i></td>
					</tr>	

					<?php if(!EMPTY($father)){ 
							foreach($father as $ft):
								$father_first	= (!EMPTY($ft['relation_first_name'])) ? $ft['relation_first_name']   : NOT_APPLICABLE;
								$father_last	= (!EMPTY($ft['relation_last_name'])) ? $ft['relation_last_name']	  : NOT_APPLICABLE;
								$father_mid		= (!EMPTY($ft['relation_middle_name'])) ? $ft['relation_middle_name'] : NOT_APPLICABLE;
							endforeach; }
					?>
					<tr>
						<td colspan="1" rowspan="3" class="td-border-thick-left" valign="top" style="padding: 3px;font-size: 10px;background:#e6e6e6; border-bottom: 1px solid #000000;">26.&nbsp;</td>
						<td colspan="1" style="padding: 3px; text-indent: 1px; height: 20px;border-right:  1px solid #000000; width: 20%; font-size: 10px;background:#e6e6e6;">FATHER'S NAME</td>
						<td colspan="5" style="padding: 3px; text-indent: 1px; height: 20px;border-right:  1px solid #000000; border-bottom: 1px solid #000000; font-size: 10px; font-weight: lighter;"><?php echo $father_last ?></td>
					</tr>
					<tr>
						<td colspan="1" style="padding: 3px; text-indent: 1px; height: 20px;border-right:  1px solid #000000; width: 20%; font-size: 10px;background:#e6e6e6;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;FIRST NAME</td>
						<td colspan="5" style="padding: 3px; text-indent: 1px; height: 20px;border-right:  1px solid #000000; border-bottom: 1px solid #000000; font-size: 10px; font-weight: lighter;"><?php echo $father_first ?></td>
					</tr>
					<tr>
						<td colspan="1" style="padding: 3px; text-indent: 1px; height: 20px;border-right:  1px solid #000000; width: 20%; font-size: 10px;background:#e6e6e6; border-bottom: 1px solid #000000;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;MIDDLE NAME</td>
						<td colspan="5" style="padding: 3px; text-indent: 1px; height: 20px;border-right:  1px solid #000000; border-bottom: 1px solid #000000; font-size: 10px; font-weight: lighter;"><?php echo $father_mid ?></td>
					</tr>
					<?php if(!EMPTY($mother)){ 
							foreach($mother as $mt):
								$mother_first	= (!EMPTY($mt['relation_first_name'])) ? $mt['relation_first_name']   : NOT_APPLICABLE;
								$mother_last	= (!EMPTY($mt['relation_last_name'])) ? $mt['relation_last_name']	  : NOT_APPLICABLE;
								$mother_mid		= (!EMPTY($mt['relation_middle_name'])) ? $mt['relation_middle_name'] : NOT_APPLICABLE;
					?>
					<?php endforeach; }?>
					<tr>
						<td colspan="1" rowspan="4" class="td-border-thick-left" valign="top" style="padding: 3px;font-size: 10px;background:#e6e6e6;">27.&nbsp;</td>
						<td colspan="6" style="padding: 3px; text-indent: 1px; height: 20px;width: 20%; font-size: 10px;background:#e6e6e6;border-right:  1px solid #000000;">MOTHER'S MAIDEN NAME</td>
					</tr>
					<tr>
						<td colspan="1" style="padding: 3px; text-indent: 1px; height: 20px;border-right:  1px solid #000000;  width: 20%; font-size: 10px;background:#e6e6e6;">MOTHER'S SURNAME</td>
						<td colspan="5" style="padding: 3px; text-indent: 1px; height: 20px;border-right:  1px solid #000000; border-bottom: 1px solid #000000; border-top:  1px solid #000000; font-size: 10px; font-weight: lighter;"><?php echo $mother_last ?></td>
					</tr>
					<tr>
						<td colspan="1" style="padding: 3px; text-indent: 1px; height: 20px;border-right:  1px solid #000000; width: 20%; font-size: 10px;background:#e6e6e6;">FIRST NAME</td>
						<td colspan="5" style="padding: 3px; text-indent: 1px; height: 20px;border-right:  1px solid #000000; border-bottom: 1px solid #000000; font-size: 10px; font-weight: lighter;"><?php echo $mother_first ?></td>
					</tr>
					<tr>
						<td colspan="1" style="padding: 3px; text-indent: 1px; height: 20px;border-right:  1px solid #000000; width: 20%; font-size: 10px;background:#e6e6e6;">MIDDLE NAME</td>
						<td colspan="5" style="padding: 3px; text-indent: 1px; height: 20px;border-right:  1px solid #000000; border-bottom: 1px solid #000000; font-size: 10px; font-weight: lighter;"><?php echo $mother_mid ?></td>
					</tr>
				</table>	
			</td>
			<td colspan="7" class="td-border-thick-top td-border-thick-left td-border-thick-right td-border-thick-bottom">
				<table>
					<tr>
						<td colspan="5" style="padding: 3px; text-indent: 1px; height: 20px;border-right:  1px solid #000000; font-size: 10px;background:#e6e6e6;border-bottom: 1px solid #000000;">25. NAME OF CHILD <span style="font-size: 6px;">(Write full name and all)</span></td>
						<td colspan="2" class="td-border-thick-right" style="padding: 3px; text-indent: 1px; height: 20px;font-size: 10px;background:#e6e6e6;border-bottom: 1px solid #000000;">DATE OF BIRTH <span style="font-size: 6px;">(mm/dd/yy)</td>
					</tr>
					<?php 
						$total = count($child);
						$diff	= 13 - $total;

						if(!EMPTY($child)){ 

						foreach($child as $cd): 
							$child_name		= (!EMPTY($cd['name'])) ? $cd['name']								  : NOT_APPLICABLE;
							$birth_date		= (!EMPTY($cd['relation_birth_date'])) ? ($cd['relation_birth_date']) : NOT_APPLICABLE;

					?>
					<tr>
						<td colspan="5" style="padding: 3px; text-indent: 1px; height: 20px;border-right:  1px solid #000000; border-bottom: 1px solid #000000; font-size: 10px; font-weight: lighter;"><?php echo $child_name ?></td>
						<td colspan="2" class="td-border-thick-right" style="padding: 3px; text-indent: 1px; height: 20px;border-bottom: 1px solid #000000; font-size: 10px; font-weight: lighter;" align="center"><?php echo $birth_date ?></td>
					</tr>
					<?php 
						endforeach; 
					}
						for($i = 0; $i < $diff; $i++)
						{
					?>
					<tr>
						<td colspan="5" style="padding: 3px; text-indent: 1px; height: 20px;border-right:  1px solid #000000; border-bottom: 1px solid #000000; font-size: 10px; font-weight: lighter;">&nbsp;</td>
						<td colspan="2" class="td-border-thick-right" style="padding: 3px; text-indent: 1px; height: 20px;border-bottom: 1px solid #000000; font-size: 10px; font-weight: lighter;">&nbsp;</td>
					</tr>
					<?php } ?>
					<tr>
						<td colspan="7" class="td-border-thick-right" style="padding: 3px; text-indent: 1px; height: 20px;border-bottom: 1px solid #000000; font-size: 9px; font-weight: lighter;" align="center"><i>(Continue on separate sheet if necessary)</i></td>
					</tr>		
				</table>
			</td>
		</tr>
		<tr>
			<td colspan="14" class="td-border-thick-top td-border-thick-left td-border-thick-right td-border-thick-bottom" style="background-color: #7e7e7e; color: white; font-size: 11px; padding: 1px"><i>III. EDUCATIONAL BACKGROUND</i></td>
		</tr>
		<tr>
			<td colspan="1" rowspan="2" class="td-border-thick-left" valign="top" style="padding: 3px;font-size: 10px;background:#e6e6e6; border-bottom: 1px solid #000000;">28.&nbsp;</td>
			<td colspan="1" rowspan="2" style="padding: 3px; text-indent: 1px; height: 20px;width: 12%; font-size:10px;background:#e6e6e6;" align="center">LEVEL</td>
			<td colspan="3" rowspan="2" width="70" style="padding: 3px; text-indent: 1px; height: 27px;border:  1px solid #000000; font-size: 10px;background:#e6e6e6;" align="center">NAME OF SCHOOL <br><span style="font-size:9px;">(Write in full)</span></td>
			<td colspan="2" rowspan="2" width="" style="padding: 3px; text-indent: 1px; height: 27px;border:  1px solid #000000; font-size: 10px;background:#e6e6e6;" align="center">DEGREE COURSE <br><span style="font-size:9px;">(if graduated)</td></td>
			<td colspan="1" rowspan="2" width="" style="padding: 3px; text-indent: 1px; height: 27px;border:  1px solid #000000; font-size: 10px;background:#e6e6e6;" align="center">YEAR <br>GRADUATED <br><span style="font-size:9px;">(if graduated)</td></td>
			<td colspan="3" rowspan="2" width="" style="padding: 3px; text-indent: 1px; height: 27px;border:  1px solid #000000; font-size: 10px;background:#e6e6e6;" align="center">HIGHEST GRADE/<br>LEVEL/<br>UNITS EARNED <br><span style="font-size:9px;">(if not graduated)</td></td>
			<td colspan="2" style="padding: 3px; text-indent: 1px; height: 27px;border:  1px solid #000000; font-size: 10px;background:#e6e6e6;" align="center">INCLUSIVE DATES OF <br> ATTENDANCE</td>
			<td colspan="1" rowspan="2" class="td-border-thick-right" width="" style="border-bottom:  1px solid #000000; padding: 3px; text-indent: 1px; height: 27px;font-size: 10px;background:#e6e6e6;" align="center">SCHOLARSHIP/<br>ACADEMIC HONORS <br>RECEIVED</td>
		</tr>
		<tr>
			<td colspan="1" style="padding: 3px; text-indent: 1px; height: 20px;border:  1px solid #000000; font-size: 10px;background:#e6e6e6;" align="center">From</td>
			<td colspan="1" style="padding: 3px; text-indent: 1px; height: 20px;border:  1px solid #000000; font-size: 10px;background:#e6e6e6;" align="center">To</td>
		</tr>
			<?php 
			foreach($educ_list as $educ):

				$count = 6;
					foreach($educ_details as $key => $ed):
						$educ_details_counter++;
						$same = FALSE;
						if($educ['educ_level_id'] == $ed['educational_level_id']) :
							$school_name = (!EMPTY($ed['school_name'])) ? $ed['school_name']						:NOT_APPLICABLE;
							$deg_course  = (!EMPTY($ed['degree_name'])) ? $ed['degree_name']						:NOT_APPLICABLE;	
							$year_grad   = ($ed['year_graduated_flag'] == "Y") ? $ed['end_year']					:NOT_APPLICABLE;
							$high_level  = (!EMPTY($ed['highest_level'])) ? $ed['highest_level']				 	:NOT_APPLICABLE;
							$start_date  = (!EMPTY($ed['start_year'])) ? $ed['start_year']	    					:NOT_APPLICABLE;
							$end_date    = (!EMPTY($ed['end_year'])) ? $ed['end_year']								:NOT_APPLICABLE;
							$honors      = (!EMPTY($ed['academic_honor'])) ? $ed['academic_honor']					:NOT_APPLICABLE;
							if($key > $count) {
								$educ_details_exceeds = TRUE;
								break;	
							} 
							$same = $educ_details[$key-1]['educational_level_id']  == $ed['educational_level_id'] ? TRUE : FALSE;
						?>
						<tr>
							<td colspan="2" class="td-border-thick-left" style="<?php echo ($same) ? '' : 'border-top: 1px solid #000000;'; ?> width: 12%; font-size:10px;background:#e6e6e6;">&nbsp;&nbsp;<?php if ($same): ?>&nbsp;<?php else: ?><?php echo $educ['educ_level_name']; ?><?php endif;?></td>
							<td colspan="3" height="50" style="font-size:10px; padding: 3px; text-indent: 1px; border:  1px solid #000000; width: 50px !important" align="center"><?php echo $school_name; ?></td>
							<td colspan="2" style="font-size:10px; padding: 3px; text-indent: 1px; border:  1px solid #000000;" align="center"><?php echo $deg_course; ?></td>
							<td colspan="1" style="font-size:10px; padding: 3px; text-indent: 1px; border:  1px solid #000000;" align="center"><?php echo $year_grad; ?></td>
							<td colspan="3" style="font-size:10px; padding: 3px; text-indent: 1px; border:  1px solid #000000;" align="center"><?php echo $high_level; ?></td>
							<td colspan="1" style="font-size:10px; padding: 3px; text-indent: 1px; border:  1px solid #000000;" align="center"><?php echo $start_date; ?></td>
							<td colspan="1" style="font-size:10px; padding: 3px; text-indent: 1px; border:  1px solid #000000;" align="center"><?php echo $end_date; ?></td>
							<td colspan="1" class="td-border-thick-right" style="font-size:10px; padding: 3px; text-indent: 1px; height: 27px;border-bottom:  1px solid #000000;" align="center"><?php echo $honors; ?></td>
						</tr>
					<?php endif; 
					endforeach;
				if(!$found AND $i < 3) {
					echo '<tr>
							<td class="td-border-thick-left" style="width: 12%; font-size:10px;background:#e6e6e6;border:  1px solid #000000;">&nbsp;&nbsp;'. $educ['educ_level_name'] .'</td>
							<td style="font-size:10px; padding: 3px; text-indent: 1px; height: 27px;border:  1px solid #000000;" align="center">&nbsp;</td>
							<td style="font-size:10px; padding: 3px; text-indent: 1px; height: 27px;border:  1px solid #000000;" align="center">&nbsp;</td>
							<td style="font-size:10px; padding: 3px; text-indent: 1px; height: 27px;border:  1px solid #000000;" align="center">&nbsp;</td>
							<td style="font-size:10px; padding: 3px; text-indent: 1px; height: 27px;border:  1px solid #000000;" align="center">&nbsp;</td>
							<td style="font-size:10px; padding: 3px; text-indent: 1px; height: 27px;border:  1px solid #000000;" align="center">&nbsp;</td>
							<td style="font-size:10px; padding: 3px; text-indent: 1px; height: 27px;border:  1px solid #000000;" align="center">&nbsp;</td>
							<td class="td-border-thick-right" style="font-size:10px; padding: 3px; text-indent: 1px; height: 27px;border-bottom:  1px solid #000000;" align="center">&nbsp;</td>
						</tr>';
				}
				endforeach; ?>
		<tr>
			<td colspan="14" height="20" style="border: 2px solid #000000; width: 12%; font-size:9px;background:#e6e6e6; padding: 0px;" align="center"><i>(Continue on separate sheet if necessary)</i></td>
		</tr>
		<tr>
			<td colspan="14" height="20" style="border: 2px solid #000000; width: 12%; font-size:9px;padding: 0px;" align="right">Page 1 of {nb}</td>
		</tr>
		<tr>
			<td colspan="14" height="10" style="border: 2px solid #000000; width: 12%; font-size:9px;background:#e6e6e6; padding: 0px;" align="right"></td>
		</tr>
	</tbody>
</table>
<table style="font-family: Arial Black, Gadget, sans-serif; calibri; width: 100%; border: 1px solid #000000; border-collapse: collapse;border: 1px solid #000000; border-collapse: collapse;">
	<tbody>			
		<tr>
			<td colspan="14" class="td-border-thick-top td-border-thick-right td-border-thick-left" height="2.5"></td>
		</tr>
		<tr>
			<td colspan="14" class="td-border-thick-top td-border-thick-left td-border-thick-right td-border-thick-bottom" style="background-color: #7e7e7e; color: white; font-size: 11px; padding: 1px"><i>IV. CIVIL SERVICE ELIGIBILITY</i></td>
		</tr>
		<tr>
			<td colspan="1" rowspan="2" class="td-border-thick-left" valign="top" style="border-bottom: 1px solid #000000;padding: 3px;font-size: 10px;background:#e6e6e6; border-bottom: 1px solid #000000;">29.&nbsp;</td>
			<td colspan="4" rowspan="2" valign="top" style="border-bottom: 1px solid #000000;padding: 3px; text-indent: 1px; height: 27px;border-right:  1px solid #000000; width: 12%; font-size: 10;background:#e6e6e6;" align="center">CAREER SERVICE/RA 1080 (BOARD / BAR) <br>UNDER SPECIAL LAWS/CES/CSEE</td>
			<td colspan="1" rowspan="2" valign="top" style="border-bottom: 1px solid #000000;padding: 3px; text-indent: 1px; height: 27px;border-right:  1px solid #000000; width: 12%; font-size: 10;background:#e6e6e6;" align="center">RATING</td>
			<td colspan="2" rowspan="2" valign="top" style="border-bottom: 1px solid #000000;padding: 3px; text-indent: 1px; height: 27px;border-right:  1px solid #000000; width: 12%; font-size: 10;background:#e6e6e6;" align="center">DATE OF <br>EXAMINATION/<br>CONFERMENT</td>
			<td colspan="4" rowspan="2" valign="top" style="border-bottom: 1px solid #000000;padding: 3px; text-indent: 1px; height: 27px;border-right:  1px solid #000000; width: 12%; font-size: 10;background:#e6e6e6;" align="center">PLACE OF EXAMINATION / CONFERMENT</td>
			<td colspan="2" class="td-border-thick-right" valign="top" style="border-bottom: 1px solid #000000;padding: 3px; text-indent: 1px; height: 27px;border-left:  1px solid #000000; width: 12%; font-size: 10;background:#e6e6e6;" align="center">LICENSE <span style="font-size: 8px;">(if applicable)</span></td>
		</tr>
		<tr>
			<td colspan="1" valign="top" style="border-bottom: 1px solid #000000; padding: 3px; text-indent: 1px; height: 27px;border-right:  1px solid #000000; width: 12%; font-size: 10;background:#e6e6e6;" align="center">NUMBER</td>
			<td colspan="1" valign="top" class="td-border-thick-right" style="border-bottom: 1px solid #000000; padding: 3px; text-indent: 1px; height: 27px;border-left:  1px solid #000000; width: 12%; font-size: 10;background:#e6e6e6;" align="center">DATE OF <br>RELEASE</td>
		</tr>
		<?php 
			$count = 7;
			if(!EMPTY($govt_exam)){ 
			foreach($govt_exam as $key => $ge):
				if($key > $count) {
					$govt_exam_exceeds = TRUE;
					break;	
				} 
				$govt_exam_counter++;
		?>
		<tr>
			<td colspan="5" class="td-border-thick-left" style="padding: 3px; text-indent: 1px; border-bottom: 1px solid #000000; border-top: 1px solid #000000; border-right: 1px solid #000000; width: 12%; font-size: 10px; font-weight: lighter;" align="center"><?php echo $ge['eligibility_type_name'] ?></td>
			<td colspan="1" style="padding: 3px; text-indent: 1px; border: 1px solid #000000; width: 12%; font-size: 10px; font-weight: lighter;" align="center"><?php echo $ge['rating'] ?></td>
			<td colspan="2" style="padding: 3px; text-indent: 1px; border: 1px solid #000000; width: 12%; font-size: 10px; font-weight: lighter;" align="center"><?php echo $ge['exam_date'] ?></td>
			<td colspan="4" style="padding: 3px; text-indent: 1px; border: 1px solid #000000; width: 12%; font-size: 10px; font-weight: lighter;" align="center"><?php echo $ge['exam_place'] ?></td>
			<td colspan="1" style="padding: 3px; text-indent: 1px; border: 1px solid #000000; width: 12%; font-size: 10px; font-weight: lighter;" align="center"><?php echo $ge['license_no'] ?></td>
			<td colspan="1" class="td-border-thick-right" style="height: 27px;padding: 3px; text-indent: 1px; border-bottom: 1px solid #000000; border-top: 1px solid #000000; border-left: 1px solid #000000; width: 12%; font-size: 10px; font-weight: lighter;" align="center"><?php echo $ge['release_date'] ?></td>
		</tr>
		<?php
			endforeach; 
			if(($key+1) < $count) {
				while (++$key < $count) {
					echo '<tr><td colspan="5" class="td-border-thick-left" style="height: 27px;padding: 3px; text-indent: 1px; border-bottom: 1px solid #000000; border-top: 1px solid #000000; border-right: 1px solid #000000; width: 12%; font-size: 12px; font-weight: lighter;" align="center"></td>
							<td colspan="1" style="height: 27px;padding: 3px; text-indent: 1px; border: 1px solid #000000; width: 12%; font-size: 12px; font-weight: lighter;" align="center"></td>
							<td colspan="2" style="height: 27px;padding: 3px; text-indent: 1px; border: 1px solid #000000; width: 12%; font-size: 12px; font-weight: lighter;" align="center"></td>
							<td colspan="4" style="height: 27px;padding: 3px; text-indent: 1px; border: 1px solid #000000; width: 12%; font-size: 12px; font-weight: lighter;" align="center"></td>
							<td colspan="1" style="height: 27px;padding: 3px; text-indent: 1px; border: 1px solid #000000; width: 12%; font-size: 12px; font-weight: lighter;" align="center"></td>
							<td colspan="1" class="td-border-thick-right" style="height: 27px;padding: 3px; text-indent: 1px; border-bottom: 1px solid #000000; border-top: 1px solid #000000; border-left: 1px solid #000000; width: 12%; font-size: 12px; font-weight: lighter;" align="center"></td></tr>';
				}
			}
		}else{  
			$key = 0;
			while ($key++ < $count) {
			echo '<tr><td colspan="5" class="td-border-thick-left" style="height: 27px;padding: 3px; text-indent: 1px; border-bottom: 1px solid #000000; border-top: 1px solid #000000; border-right: 1px solid #000000; width: 12%; font-size: 12px; font-weight: lighter;" align="center"></td>
					<td colspan="1" style="height: 27px;padding: 3px; text-indent: 1px; border: 1px solid #000000; width: 12%; font-size: 12px; font-weight: lighter;" align="center"></td>
					<td colspan="2" style="height: 27px;padding: 3px; text-indent: 1px; border: 1px solid #000000; width: 12%; font-size: 12px; font-weight: lighter;" align="center"></td>
					<td colspan="4" style="height: 27px;padding: 3px; text-indent: 1px; border: 1px solid #000000; width: 12%; font-size: 12px; font-weight: lighter;" align="center"></td>
					<td colspan="1" style="height: 27px;padding: 3px; text-indent: 1px; border: 1px solid #000000; width: 12%; font-size: 12px; font-weight: lighter;" align="center"></td>
					<td colspan="1" class="td-border-thick-right" style="height: 27px;padding: 3px; text-indent: 1px; border-bottom: 1px solid #000000; border-top: 1px solid #000000; border-left: 1px solid #000000; width: 12%; font-size: 12px; font-weight: lighter;" align="center"></td></tr>';
			}

		} ?>	
		<tr>
			<td colspan="14" height="15" style="border: 2px solid #000000; width: 12%; font-size:9px;background:#e6e6e6;" align="center"><i>(Continue on separate sheet if necessary)</i></td>
		</tr>
		<tr>
			<td colspan="14" class="td-border-thick-top td-border-thick-left td-border-thick-right td-border-thick-bottom" style="background-color: #7e7e7e; color: white; font-size: 11px; padding: 1px"><i>V. WORK EXPERIENCE (Include private employment. Start from your current work)</i></td>
		</tr>
		<tr>
			<td colspan="2" class="td-border-thick-left" style="padding: 3px; text-indent: 1px; height: 27px;border-bottom:  1px solid #000000; border-right:  1px solid #000000; width: 12%; font-size: 10px;background:#e6e6e6;">30. &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;INCLUSIVE DATES<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="font-size: 9px;">(mm/dd/yyyy)</span></td>
			<td rowspan="2" style="padding: 3px; text-indent: 1px; height: 27px;border:  1px solid #000000; width: 12%; font-size: 10px;background:#e6e6e6;" align="center">POSITION TITLE <span style="font-size: 9px;"><br>(Write in full)</span></td>
			<td rowspan="2" style="padding: 3px; text-indent: 1px; height: 27px;border:  1px solid #000000; width: 12%; font-size: 10px;background:#e6e6e6;" align="center">DEPARTMENT/AGENCY/OFFICE/COMPANY <span style="font-size: 9px;">(Write in full)</span></td>
			<td rowspan="2" style="padding: 3px; text-indent: 1px; height: 27px;border:  1px solid #000000; width: 12%; font-size: 10px;background:#e6e6e6;" align="center">MONTHLY SALARY</td>
			<td rowspan="2" style="padding: 3px; text-indent: 1px; height: 27px;border:  1px solid #000000; width: 12%; font-size: 10px;background:#e6e6e6;" align="center">SALARY GRADE <br>&amp; STEP INCREMENT<br> <span style="font-size: 9px;">(Format "00-0")</span></td>
			<td rowspan="2" style="padding: 3px; text-indent: 1px; height: 27px;border:  1px solid #000000; width: 12%; font-size: 10px;background:#e6e6e6;" align="center">STATUS OF<br> APPOINTMENT</td>
			<td class="td-border-thick-right" rowspan="2" style="padding: 3px; text-indent: 1px; height: 27px;border-bottom:  1px solid #000000; width: 8%; font-size: 10px;background:#e6e6e6;" align="center">GOV'T<br> SERVICE <br><span style="font-size: 9px;">(Yes/No)</span></td>
		</tr>
		<tr>
			<td class="td-border-thick-left" style="padding: 3px; text-indent: 1px; height: 27px;border-bottom:  1px solid #000000; border-right:  1px solid #000000; width: 12%; font-size: 10px;background:#e6e6e6;" align="center">From</td>
			<td style="padding: 3px; text-indent: 1px; height: 27px;border:  1px solid #000000; width: 12%; font-size: 10px;background:#e6e6e6;" align="center">To</td>
		</tr>
		<?php $count = 20; 
		if(!EMPTY($work_exp)) { 
			foreach($work_exp AS $key => $we):
				if($key > $count) {
					$work_exp_exceeds = TRUE;
					break;	
				} 
				$work_exp_counter++;
				$govt_service = ($we['govt_service_flag'] == 'Y') ? "YES" : "NO";
		?>
		<tr>
			<td class="td-border-thick-left" style="padding: 3px; text-indent: 1px; height: 27px;border-right:  1px solid #000000;  border-bottom: 1px solid #000000; font-size: 8px;" align="center"><?php echo $we['employ_start_date']; ?></td>
			<td style="padding: 3px; text-indent: 1px; height: 27px;border-right:  1px solid #000000;  border-bottom: 1px solid #000000; font-size: 8px;" align="center"><?php echo (!EMPTY($we['employ_end_date']) ? $we['employ_end_date'] : 'PRESENT') ; ?></td>
			<td style="padding: 3px; text-indent: 1px; height: 27px;border-right:  1px solid #000000;  border-bottom: 1px solid #000000; font-size: 8px;" align="center"><?php echo $we['position_name']; ?></td>
			<td style="padding: 3px; text-indent: 1px; height: 27px;border-right:  1px solid #000000;  border-bottom: 1px solid #000000; font-size: 8px;" align="center"><?php echo $we['name']; ?></td>
			<td style="padding: 3px; text-indent: 1px; height: 27px;border-right:  1px solid #000000;  border-bottom: 1px solid #000000; font-size: 8px;" align="center"><?php echo number_format($we['employ_monthly_salary'],2); ?></td>
			<td style="padding: 3px; text-indent: 1px; height: 27px;border-right:  1px solid #000000;  border-bottom: 1px solid #000000; font-size: 8px;" align="center"><?php echo $we['employ_salary_grade'].' - '.$we['employ_salary_step']; ?></td>
			<td style="padding: 3px; text-indent: 1px; height: 27px;border-right:  1px solid #000000;  border-bottom: 1px solid #000000; font-size: 8px;" align="center"><?php echo $we['employment_status_name']; ?></td>
			<td class="td-border-thick-right" style="padding: 3px; text-indent: 1px; height: 27px;border-bottom: 1px solid #000000; font-size: 8px;" align="center"><?php echo $govt_service; ?></td>
		</tr>
		<?php	
			endforeach; 
			if(($key+1) < $count) {
				while (++$key < $count) {
				echo   '<tr>
						<td class="td-border-thick-left" style="height:25px; padding: 3px; text-indent: 1px; height: 27px;border-right:  1px solid #000000;  border-bottom: 1px solid #000000; font-size: 12px;"></td>
						<td style="height:25px; padding: 3px; text-indent: 1px; height: 27px;border-right:  1px solid #000000;  border-bottom: 1px solid #000000; font-size: 12px;"></td>
						<td style="height:25px; padding: 3px; text-indent: 1px; height: 27px;border-right:  1px solid #000000;  border-bottom: 1px solid #000000; font-size: 12px;"></td>
						<td style="height:25px; padding: 3px; text-indent: 1px; height: 27px;border-right:  1px solid #000000;  border-bottom: 1px solid #000000; font-size: 12px;"></td>
						<td style="height:25px; padding: 3px; text-indent: 1px; height: 27px;border-right:  1px solid #000000;  border-bottom: 1px solid #000000; font-size: 12px;"></td>
						<td style="height:25px; padding: 3px; text-indent: 1px; height: 27px;border-right:  1px solid #000000;  border-bottom: 1px solid #000000; font-size: 12px;"></td>
						<td style="height:25px; padding: 3px; text-indent: 1px; height: 27px;border-right:  1px solid #000000;  border-bottom: 1px solid #000000; font-size: 12px;"></td>
						<td class="td-border-thick-right" style="height:25px; padding: 3px; text-indent: 1px; height: 27px;border-bottom: 1px solid #000000; font-size: 12px;"></td>
						</tr>';
				}
			}
		}else{ $key = 0;

			while ($key++ < $count) {
				echo '<tr>
						<td class="td-border-thick-left" style="height:25px; padding: 3px; text-indent: 1px; height: 27px;border-right:  1px solid #000000;  border-bottom: 1px solid #000000; font-size: 12px;"></td>
						<td style="height:25px; padding: 3px; text-indent: 1px; height: 27px;border-right:  1px solid #000000;  border-bottom: 1px solid #000000; font-size: 12px;"></td>
						<td style="height:25px; padding: 3px; text-indent: 1px; height: 27px;border-right:  1px solid #000000;  border-bottom: 1px solid #000000; font-size: 12px;"></td>
						<td style="height:25px; padding: 3px; text-indent: 1px; height: 27px;border-right:  1px solid #000000;  border-bottom: 1px solid #000000; font-size: 12px;"></td>
						<td style="height:25px; padding: 3px; text-indent: 1px; height: 27px;border-right:  1px solid #000000;  border-bottom: 1px solid #000000; font-size: 12px;"></td>
						<td style="height:25px; padding: 3px; text-indent: 1px; height: 27px;border-right:  1px solid #000000;  border-bottom: 1px solid #000000; font-size: 12px;"></td>
						<td style="height:25px; padding: 3px; text-indent: 1px; height: 27px;border-right:  1px solid #000000;  border-bottom: 1px solid #000000; font-size: 12px;"></td>
						<td class="td-border-thick-right" style="height:25px; padding: 3px; text-indent: 1px; height: 27px;border-bottom: 1px solid #000000; font-size: 12px;"></td>
					</tr>';
			}

		}?>
		<tr>
			<td colspan="8" height="10" style="border: 2px solid #000000; width: 12%; font-size:9px;background:#e6e6e6; padding: 0px;" align="center"><i>(Continue on separate sheet if necessary)</i></td>
		</tr>
		<tr>
			<td colspan=8 class="td-border-thick-bottom td-border-thick-right td-border-thick-left f-size-9" height="10" align="right">CS FORM 212 (Revised 2005), Page 2 of {nb}</td>
		</tr>
		<tr>
			<td colspan=8 class="td-border-thick-bottom td-border-thick-right td-border-thick-left" height="2.5"></td>
		</tr>
	</tbody>
</table>