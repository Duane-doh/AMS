<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<html>
<head>
	
	<title>PHILHEALTH Membership Form</title>
	<link rel="stylesheet" href="<?php echo base_url().PATH_CSS ?>reports.css" type="text/css" />
</head>
<body>
<div style="margin: -10mm; ">
<table class="table-max" style="font-size: 10px;">
	<tr>
		<td>
			<table>
				<tr>
					<td>
						<table>
							<tr>
								<td><img src="<?php echo base_url().PATH_IMG ?>philhealth_logo.jpg" width=40 height=70></img></td>
								<td>
									<br><span style="font-size: 0.80em;"><i>Republic of the Philippines</i></span><br>
									<span style="font-size: 11px;"><b>PHILIPPINE HEALTH INSURANCE CORPORATION</b></span><br>
									<span style="font-size: 0.80em;">Citystate Centre Building, 709 Shaw Boulevard, Pasig City<br>Healthline 441-7444  www.philhealth.gov.ph</span>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td>
						<span style="font-size: 0.90em;"><?php echo nbs(3) ?><b><u>IMPORTANT REMINDERS:</u></b><br>
						<?php echo nbs(3) ?>1. Your PhilHealth Identification Number (PIN) is your unique and permanent number.<br>
						<?php echo nbs(3) ?>2. The issuance of the PIN does not automatically qualify you or your dependents to be entitled to NHIP benefits.<br>
						<?php echo nbs(3) ?>3. Always use your PIN in all transactions with PhilHealth.</span><br>
						<span style="font-size: 11px;"><?php echo nbs(5) ?><b>Please carefully read instructions at the back before accomplishing this form.</b></span>
					</td>
				</tr>
			</table>
		</td>
		<td>
			<span style="font-size: 23px;"><?php echo nbs(12) ?><b>PMRF<br></b></span>
			<span style="font-size: 10px;"><b>PHILHEALTH MEMBER REGISTRATION FORM</b><br></span>
			<span style="font-size: 9px;"><?php echo nbs(30) ?>(October 2013)<br>
			<?php echo nbs(10) ?><b>PhilHealth Identification Number (PIN)</b></span>
			<table>
				<tr>
					<td class="td-border-light-right td-border-light-left td-border-light-bottom td-border-light-top" width="18" height="20" align="center"><?php echo substr($personal_info['pin'], 0, 1)?></td>
					<td class="td-border-light-right td-border-light-bottom td-border-light-top" width="18" height="20" align="center"><?php echo substr($personal_info['pin'], 1, 1)?></td>
					<td class="td-border-light-right td-border-light-bottom td-border-light-top" width="18" height="20" align="center"><?php echo substr($personal_info['pin'], 2, 1)?></td>
					<td class="td-border-light-right td-border-light-bottom td-border-light-top" width="18" height="20" align="center"><?php echo substr($personal_info['pin'], 3, 1)?></td>
					<td width="5" height="20"></td>
					<td class="td-border-light-right td-border-light-left td-border-light-bottom td-border-light-top" width="18" height="20" align="center"><?php echo substr($personal_info['pin'], 4, 1)?></td>
					<td class="td-border-light-right td-border-light-bottom td-border-light-top" width="18" height="20" align="center"><?php echo substr($personal_info['pin'], 4, 1)?></td>
					<td class="td-border-light-right td-border-light-bottom td-border-light-top" width="18" height="20" align="center"><?php echo substr($personal_info['pin'], 5, 1)?></td>
					<td class="td-border-light-right td-border-light-bottom td-border-light-top" width="18" height="20" align="center"><?php echo substr($personal_info['pin'], 6, 1)?></td>
					<td width="5" height="20"></td>
					<td class="td-border-light-right td-border-light-left td-border-light-bottom td-border-light-top" width="18" height="20" align="center"><?php echo substr($personal_info['pin'], 7, 1)?></td>
					<td class="td-border-light-right td-border-light-bottom td-border-light-top" width="18" height="20" align="center"><?php echo substr($personal_info['pin'], 8, 1)?></td>
					<td class="td-border-light-right td-border-light-bottom td-border-light-top" width="18" height="20" align="center"><?php echo substr($personal_info['pin'], 9, 1)?></td>
					<td class="td-border-light-right td-border-light-bottom td-border-light-top" width="18" height="20" align="center"><?php echo substr($personal_info['pin'], 10, 1)?></td>
				</tr>
			</table>
			<span style="font-size: 11px;"><?php echo nbs(2) ?><b>PURPOSE:</b></span><br>
			<span style="font-size: 11px;"><span class="f-size-12" style="font-family: Arial, Helvetica, sans-serif"><?php echo nbs(5); echo ISSET($personal_info['pin'])?"&#9633;":"&#9745;";?></span>FOR ENROLLMENT <span class="f-size-12" style="font-family: Arial, Helvetica, sans-serif"><?php echo ISSET($personal_info['pin'])?"&#9745;":"&#9633;";?></span>FOR UPDATING</span>
		</td>
	</tr>
</table>


<table class="table-max" style="font-size: 10px;">
	<tr>
		<td class="td-border-light-top td-border-light-bottom td-border-light-left td-border-light-right td-center-middle" colspan=11 align="left" valign=middle style="background-color: #d3d3d3;"><?php echo nbs(2) ?><b>1. MEMBERSHIP INFORMATION</b></td>
	</tr>
	<tr>
		<td class="td-border-light-left" colspan=3 height="17" align="center" valign=middle><b>Last Name</b></td>
		<td colspan=3 align="center" valign=middle><b>First Name</b></td>
		<td colspan=2 width="70" align="center" valign=middle><b>Name Extension (JR/SR/II)</b></td>
		<td class="td-border-light-right" colspan=3 align="center" valign=middle><b>Middle Name</b></td>
	</tr>
	<tr>
		<td class="td-border-light-left" colspan=3 height="17" align="center" valign=middle><?php echo $personal_info['last_name'];?></td>
		<td colspan=3 align="center" valign=middle><?php echo $personal_info['first_name'];?></td>
		<td colspan=2 align="center" valign=middle><?php echo $personal_info['ext_name'];?></td>
		<td class="td-border-light-right" colspan=3 align="center" valign=middle><?php echo $personal_info['middle_name'];?></td>
	</tr>
	<tr>
		<td class="td-border-light-top td-border-light-bottom td-border-light-left td-border-light-right td-center-middle" colspan=11  align="left" valign=middle style="background-color: #deeff5;"><?php echo nbs(2) ?><b>If Married Female, please write FULL MAIDEN NAME:</b></td>
	</tr>
	<tr>
		<td class="td-border-light-left" colspan=3 height="17" align="center" valign=middle><b>Last Name</b></td>
		<td colspan=3 align="center" valign=middle><b>First Name</b></td>
		<td colspan=2 width="70" align="center" valign=middle><b>Name Extension (JR/SR/II)</b></td>
		<td class="td-border-light-right" colspan=3 align="center" valign=middle><b>Middle Name</b></td>
	</tr>
	<?php if(($personal_info['gender_code'] == "F") AND (($personal_info['civil_status_id'] == CIVIL_STATUS_MARRIED) OR ($personal_info['civil_status_id'] == CIVIL_STATUS_WIDOW_ER))){?>
	<tr>
		<td class="td-border-light-left" colspan=3 height="17" align="center" valign=middle><?php echo $personal_info['middle_name'];?></td>
		<td colspan=3 align="center" valign=middle><?php echo $personal_info['first_name'];?></td>
		<td colspan=2 align="center" valign=middle></td>
		<td class="td-border-light-right" colspan=3 align="center" valign=middle></td>
	</tr>
	<?php }else{?>
	<tr>
		<td class="td-border-light-left" colspan=3 height="17" align="center" valign=middle></td>
		<td colspan=3 align="center" valign=middle></td>
		<td colspan=2 align="center" valign=middle></td>
		<td class="td-border-light-right" colspan=3 align="center" valign=middle></td>
	</tr>
	<?php }?>
</table>

<table class="table-max" style="font-size: 10px;">
	<tr>
		<td class="td-border-light-top td-border-light-left td-border-light-right td-center-middle" colspan=2 width="80" height="17" align="center" valign=middle>Date of Birth<span style="font-size: 8px;">(mm-dd-yyyy)</span></td>
		<td class="td-border-light-top td-border-light-right td-center-middle" colspan=3 width="170" align="center" valign=middle>Place of Birth<span style="font-size: 8px;">(City/Municipality, Province)</span></td>
		<td class="td-border-light-top td-border-light-right td-center-middle" width="50" align="center" valign=middle>Sex</td>
		<td class="td-border-light-top td-border-light-right td-center-middle" width="160" align="center" valign=middle>Civil Status</td>
		<td class="td-border-light-top td-border-light-right td-center-middle" colspan=2 width="70" align="center" valign=middle>Nationality</td>
		<td class="td-border-light-top td-border-light-right td-center-middle" colspan=2 align="center" valign=middle>Tax Identification No.(TIN)</td>
	</tr>
	<tr>
		<td class="td-border-light-left td-border-light-right td-center-middle" colspan=2 width="70" height="20" align="center" valign=middle><?php echo $personal_info['birth_date'];?></td>
		<td class="td-border-light-right td-center-middle" colspan=3 width="170" align="center" valign=middle><?php echo $personal_info['birth_place'];?></td>
		<td class="td-border-light-right td-center-middle" width="50" align="left" valign=middle><span style="font-family: Arial, Helvetica, sans-serif"><?php echo nbs(2); echo($personal_info['gender_code'] == "M")?"&#9745;":"&#9633;";?></span>Male<br><span style="font-family: Arial, Helvetica, sans-serif"><?php echo nbs(2); echo($personal_info['gender_code'] == "F")?"&#9745;":"&#9633;";?></span>Female </td>
		<td class="td-border-light-right td-center-middle" align="left" valign=middle>
			<table style="font-size: 10px">
			
				<tr>
					<td><?php echo nbs(2) ?></td>
					<td><span style="font-family: Arial, Helvetica, sans-serif"><?php echo ($personal_info['civil_status_id'] == CIVIL_STATUS_SINGLE)?"&#9745;":"&#9633;";?></span>Single</td>
					<td><?php echo nbs(2) ?></td>
					<td><span style="font-family: Arial, Helvetica, sans-serif"><?php echo ($personal_info['civil_status_id'] == CIVIL_STATUS_MARRIED)?"&#9745;":"&#9633;";?></span>Married</td>
				</tr>
				<tr>
					<td><?php echo nbs(2) ?></td>
					<td><span style="font-family: Arial, Helvetica, sans-serif"><?php echo ($personal_info['civil_status_id'] == CIVIL_STATUS_WIDOW_ER)?"&#9745;":"&#9633;";?></span>Widow(er)</td>
					<td><?php echo nbs(2) ?></td>
					<td><span style="font-family: Arial, Helvetica, sans-serif"><?php echo ($personal_info['civil_status_id'] == CIVIL_STATUS_LEGALLY_SEPERATED)?"&#9745;":"&#9633;";?></span>Legally Separated</td>
				</tr>
			</table>
		</td>
		<td class="td-border-light-right td-center-middle" colspan=2 width="70" align="center" valign=middle><?php echo $personal_info['citizenship_name']?></td>
		<td class="td-border-light-right td-center-middle" colspan=2 align="center" valign=middle><?php echo $personal_info['tin'];?></td>
	</tr>
	<tr>
		<td class="td-border-light-top td-border-light-left td-border-light-right" colspan=11 align="left" valign=middle style="background-color: #d3d3d3;"><?php echo nbs(2) ?><b>Permanent Address</b></td>
	</tr>
	<tr>
		<td class="td-border-light-top td-border-light-left" colspan=2 height="17" align="center" valign=middle>Unit/Room No., Floor</td>
		<td class="td-border-light-top" colspan=2 align="center" valign=middle>Building Name</td>
		<td class="td-border-light-top" colspan=2 align="center" valign=middle>House/Building No.</td>
		<td class="td-border-light-top" colspan=3 align="center" valign=middle>Street</td>
		<td class="td-border-light-top td-border-light-right" colspan=2 align="center" valign=middle>Subdivision/Village</td>
	</tr>
	<tr>
		<td class="td-border-light-left" colspan=2 height="17" align="center" valign=middle></td>
		<td colspan=2 align="center" valign=middle></td>
		<td colspan=2 align="center" valign=middle></td>
		<td colspan=3 align="center" valign=middle><?php echo $permanent_address['address_value'];?></td>
		<td class="td-border-light-right" colspan=2 align="center" valign=middle></td>
	</tr>
	<tr>
		<td class="td-border-light-top td-border-light-left" colspan=3 height="17" align="center" valign=middle>Barangay</td>
		<td class="td-border-light-top" colspan=3 align="center" valign=middle>City/Municipality</td>
		<td class="td-border-light-top" colspan=3 align="center" valign=middle>Province</td>
		<td class="td-border-light-top td-border-light-right" colspan=2 align="center" valign=middle>Zipcode</td>
	</tr>
	<tr>
		<td class="td-border-light-left" colspan=2 height="17" align="center" valign=middle><?php echo $permanent_address['barangay_name']?></td>
		<td colspan=2 align="center" valign=middle></td>
		<td colspan=2 align="center" valign=middle><?php echo $permanent_address['municity_name']?></td>
		<td colspan=3 align="center" valign=middle><?php echo $permanent_address['province_name']?></td>
		<td class="td-border-light-right" colspan=2 align="center" valign=middle><?php echo $permanent_address['postal_number']?></td>
	</tr>
	<tr>
		<td class="td-border-light-top td-border-light-left td-border-light-right td-center-middle" colspan=11 align="left" valign=middle style="background-color: #d3d3d3;"><?php echo nbs(2) ?><b>Contact Information</b></td>
		</tr>
	<tr>
		<td class="td-border-light-top td-border-light-left td-border-light-right td-center-middle" colspan=4 height="17" align="center" valign=middle>Landline Number (Area Code + Tel. No.)</td>
		<td class="td-border-light-top td-border-light-right td-center-middle" colspan=2 align="center" valign=middle>Mobile Number</td>
		<td class="td-border-light-top td-border-light-right td-center-middle" colspan=5 align="center" valign=middle>E-mail Address</td>
		</tr>
	<tr>
		<td class="td-border-light-left td-border-light-right td-border-light-bottom" colspan=4 height="17" align="center" valign=middle><?php echo $contacts_info['landline'];?></td>
		<td class="td-border-light-right td-border-light-bottom" colspan=2 align="center" valign=middle><?php echo $contacts_info['mobile']; ?></td>
		<td class="td-border-light-right td-border-light-bottom" colspan=5 align="center" valign=middle><?php echo $contacts_info['email']; ?></td>
	</tr>
	<tr>
		<td class="td-border-light-left td-border-light-right td-border-light-bottom" colspan=11 align="left" valign=middle style="background-color: #d3d3d3;"><?php echo nbs(2) ?><b>2. DECLARATION OF DEPENDENTS </b>(Use separate sheet if necessary)</td>
	</tr>
</table>

<table class="table-max" style="font-size: 9px;">
	<tr>
		<td class="td-border-light-left td-border-light-right td-border-light-bottom" colspan=12 align="left" valign=middle style="background-color: #d3d3d3;"><?php echo nbs(2) ?>2.1 Legal Spouse</td>
		</tr>
	<tr>
		<td class="td-border-light-left td-border-light-right td-border-light-bottom" colspan=2 align="center" valign=middle>PhilHealth Identification<br>Number (PIN)</td>
		<td class="td-border-light-right td-border-light-bottom" colspan=2 width="120" align="center" valign=middle>Last Name</td>
		<td class="td-border-light-right td-border-light-bottom" colspan=2 width="120" align="center" valign=middle>First Name</td>
		<td class="td-border-light-right td-border-light-bottom" width="80" align="center" valign=middle>Name Extension<br>(JR/SR/III)</td>
		<td class="td-border-light-right td-border-light-bottom" colspan=3 align="center" valign=middle>Middle Name</td>
		<td class="td-border-light-right td-border-light-bottom" align="center" width="70" valign=middle>Date of Birth<br>mm-dd-yyyy</td>
		<td class="td-border-light-right td-border-light-bottom" align="center" valign=middle>Sex<br>M/F</td>
	</tr>
	<tr>
		<td class="td-border-light-left td-border-light-right td-border-light-bottom align-c" colspan=2 height="17" align="center" valign=middle><?php echo $spouse['philhealth_number'];?></td>
		<td class="td-border-light-right td-border-light-bottom" colspan=2 align="center" valign=middle><?php echo $spouse['relation_last_name'];?></td>
		<td class="td-border-light-right td-border-light-bottom" colspan=2 align="center" valign=middle><?php echo $spouse['relation_first_name'];?></td>
		<td class="td-border-light-right td-border-light-bottom" align="center" valign=middle><?php echo $spouse['relation_ext_name'];?></td>
		<td class="td-border-light-right td-border-light-bottom" colspan=3 align="center" valign=middle><?php echo $spouse['relation_middle_name'];?></td>
		<td class="td-border-light-right td-border-light-bottom" align="center" valign=middle><?php echo $spouse['relation_birth_date'];?></td>
		<td class="td-border-light-right td-border-light-bottom" align="center" valign=middle><?php echo $spouse['relation_gender_code'];?></td>
	</tr>
	<tr>
		<td class="td-border-light-left td-border-light-right td-border-light-bottom" colspan=12 align="left" valign=middle style="background-color: #d3d3d3;"><?php echo nbs(2) ?>2.2 Children below 21 years old (unmarried &amp; unemployed) and/or Children 21 years old and above with permanent disability</td>
		</tr>
	<tr>
		<td class="td-border-light-left td-border-light-right td-border-light-bottom" colspan=2 align="center" valign=middle>PhilHealth Identification<br>Number (PIN)</td>
		<td class="td-border-light-right td-border-light-bottom" colspan=2 align="center" valign=middle>Last Name</td>
		<td class="td-border-light-right td-border-light-bottom" colspan=2 align="center" valign=middle>First Name</td>
		<td class="td-border-light-right td-border-light-bottom" align="center" valign=middle>Name Extension<br>(JR/SR/III)</td>
		<td class="td-border-light-right td-border-light-bottom" colspan=2 width="100" align="center" valign=middle>Middle Name</td>
		<td class="td-border-light-right td-border-light-bottom" align="center" valign=middle><span class="f-size">Mark <span style="font-family: Arial, Helvetica, sans-serif">&#10003;</span> if with<br>Disability</span></td>
		<td class="td-border-light-right td-border-light-bottom" align="center" valign=middle>Date of Birth<br>mm-dd-yyyy</td>
		<td class="td-border-light-right td-border-light-bottom" align="center" valign=middle>Sex<br>M/F</td>
	</tr>
	<tr>
		<td class="td-border-light-left td-border-light-right td-border-light-bottom" colspan=2 height="17" align="center"><?php echo $child[0]['philhealth_number'];?></td>
		<td class="td-border-light-right td-border-light-bottom" colspan=2 align="center"><?php echo $child[0]['relation_last_name'];?></td>
		<td class="td-border-light-right td-border-light-bottom" colspan=2 align="center"><?php echo $child[0]['relation_first_name'];?></td>
		<td class="td-border-light-right td-border-light-bottom" align="left"><?php echo $child[0]['relation_ext_name'];?></td>
		<td class="td-border-light-right td-border-light-bottom" colspan=2 align="center" valign=middle><?php echo $child[0]['relation_middle_name'];?></td>
		<td class="td-border-light-right td-border-light-bottom" align="center" ><span style="font-family: Arial, Helvetica, sans-serif" class="f-size-16"><?php echo ($child[0]['pwd_flag'] == 'Y')?"&#9745;":"&#9633;"; ?></span></td>
		<td class="td-border-light-right td-border-light-bottom valign-mid" align="center"><?php echo $child[0]['relation_birth_date'];?></td>
		<td class="td-border-light-right td-border-light-bottom" align="center"><?php echo $child[0]['relation_gender_code'];?></td>
	</tr>
	<tr>
		<td class="td-border-light-left td-border-light-right td-border-light-bottom" colspan=2 height="17" align="center"><?php echo $child[1]['philhealth_number'];?></td>
		<td class="td-border-light-right td-border-light-bottom" colspan=2 align="center"><?php echo $child[1]['relation_last_name'];?></td>
		<td class="td-border-light-right td-border-light-bottom" colspan=2 align="center"><?php echo $child[1]['relation_first_name'];?></td>
		<td class="td-border-light-right td-border-light-bottom" align="left"><?php echo $child[1]['relation_ext_name'];?></td>
		<td class="td-border-light-right td-border-light-bottom" colspan=2 align="center" valign=middle><?php echo $child[1]['relation_middle_name'];?></td>
		<td class="td-border-light-right td-border-light-bottom" align="center" ><span style="font-family: Arial, Helvetica, sans-serif" class="f-size-16"><?php echo ($child[1]['pwd_flag'] == 'Y')?"&#9745;":"&#9633;"; ?></span></td>
		<td class="td-border-light-right td-border-light-bottom valign-mid" align="center"><?php echo $child[1]['relation_birth_date'];?></td>
		<td class="td-border-light-right td-border-light-bottom" align="center"><?php echo $child[1]['relation_gender_code'];?></td>
	</tr>
	<tr>
		<td class="td-border-light-left td-border-light-right td-border-light-bottom" colspan=2 height="17" align="center"><?php echo $child[2]['philhealth_number'];?></td>
		<td class="td-border-light-right td-border-light-bottom" colspan=2 align="center"><?php echo $child[2]['relation_last_name'];?></td>
		<td class="td-border-light-right td-border-light-bottom" colspan=2 align="center"><?php echo $child[2]['relation_first_name'];?></td>
		<td class="td-border-light-right td-border-light-bottom" align="left"><?php echo $child[2]['relation_ext_name'];?></td>
		<td class="td-border-light-right td-border-light-bottom" colspan=2 align="center" valign=middle><?php echo $child[2]['relation_middle_name'];?></td>
		<td class="td-border-light-right td-border-light-bottom" align="center" ><span style="font-family: Arial, Helvetica, sans-serif"class="f-size-16"><?php echo ($child[2]['pwd_flag'] == 'Y')?"&#9745;":"&#9633;"; ?></span></td>
		<td class="td-border-light-right td-border-light-bottom valign-mid" align="center"><?php echo $child[2]['relation_birth_date'];?></td>
		<td class="td-border-light-right td-border-light-bottom" align="center"><?php echo $child[2]['relation_gender_code'];?></td>
	</tr>
	<tr>
		<td class="td-border-light-left td-border-light-right td-border-light-bottom" colspan=12 align="left" valign=middle style="background-color: #d3d3d3;"><?php echo nbs(2) ?>2.3 Parents&rsquo; Details</td>
		</tr>
	<tr>
		<td class="td-border-light-left td-border-light-right td-border-light-bottom" colspan=2 align="center" valign=middle>PhilHealth Identification<br>Number (PIN)</td>
		<td class="td-border-light-right td-border-light-bottom" colspan=2 align="center" valign=middle>Father's Last Name</td>
		<td class="td-border-light-right td-border-light-bottom" colspan=2 align="center" valign=middle>Father's First Name</td>
		<td class="td-border-light-right td-border-light-bottom" align="center" valign=middle>Name Extension<br>(JR/SR/III)</td>
		<td class="td-border-light-right td-border-light-bottom" colspan=2 align="center" valign=middle>Father's Middle Name</td>
		<td class="td-border-light-right td-border-light-bottom" align="center" valign=middle>Mark <span style="font-family: Arial, Helvetica, sans-serif">&#10003;</span> if with<br>Permanent<br>Disability</td>
		<td class="td-border-light-right td-border-light-bottom" align="center" valign=middle>Date of Birth<br>mm-dd-yyyy</td>
		<td class="td-border-light-right td-border-light-bottom" align="center" valign=middle>Sex<br>M/F</td>
	</tr>
	<tr>
		<td class="td-border-light-left td-border-light-right td-border-light-bottom" colspan=2 height="17" align="center"><?php echo $father['philhealth_number'];?></td>
		<td class="td-border-light-right td-border-light-bottom" colspan=2 align="center"><?php echo $father['relation_last_name'];?></td>
		<td class="td-border-light-right td-border-light-bottom" colspan=2 align="center"><?php echo $father['relation_first_name'];?></td>
		<td class="td-border-light-right td-border-light-bottom" align="left"><?php echo $father['relation_ext_name'];?></td>
		<td class="td-border-light-right td-border-light-bottom" colspan=2 align="center" valign=middle><?php echo $father['relation_middle_name'];?></td>
		<td class="td-border-light-right td-border-light-bottom" align="center" ><span style="font-family: Arial, Helvetica, sans-serif" class="f-size-16"><?php echo ($father['pwd_flag'] == 'Y')?"&#9745;":"&#9633;"; ?></span></td>
		<td class="td-border-light-right td-border-light-bottom valign-mid" align="center"><?php echo $father['relation_birth_date'];?></td>
		<td class="td-border-light-right td-border-light-bottom" align="center"><?php echo $father['relation_gender_code'];?></td>
	</tr>
	<tr>
		<td class="td-border-light-left td-border-light-right td-border-light-bottom" colspan=2 align="center" valign=middle>PhilHealth Identification<br>Number (PIN)</td>
		<td class="td-border-light-right td-border-light-bottom" colspan=2 align="center" valign=middle>Mother's Last Name</td>
		<td class="td-border-light-right td-border-light-bottom" colspan=2 align="center" valign=middle>Mother's First Name</td>
		<td class="td-border-light-right td-border-light-bottom" align="center" valign=middle>Name Extension<br>(JR/SR/III)</td>
		<td class="td-border-light-right td-border-light-bottom" colspan=2 align="center" valign=middle>Mother's Middle Name</td>
		<td class="td-border-light-right td-border-light-bottom" align="center" valign=middle><span class="f-size">Mark <span style="font-family: Arial, Helvetica, sans-serif">&#10003;</span> if with<br>Permanent<br>Disability</span></td>
		<td class="td-border-light-right td-border-light-bottom" align="center" valign=middle>Date of Birth<br>mm-dd-yyyy</td>
		<td class="td-border-light-right td-border-light-bottom" align="center" valign=middle>Sex<br>M/F</td>
	</tr>
	<tr>
		<td class="td-border-light-left td-border-light-right td-border-light-bottom" colspan=2 height="17" align="center"><?php echo $mother['philhealth_number'];?></td>
		<td class="td-border-light-right td-border-light-bottom" colspan=2 align="center"><?php echo $mother['relation_last_name'];?></td>
		<td class="td-border-light-right td-border-light-bottom" colspan=2 align="center"><?php echo $mother['relation_first_name'];?></td>
		<td class="td-border-light-right td-border-light-bottom" align="left"><?php echo $father['relation_ext_name'];?></td>
		<td class="td-border-light-right td-border-light-bottom" colspan=2 align="center" valign=middle><?php echo $mother['relation_middle_name'];?></td>
		<td class="td-border-light-right td-border-light-bottom" align="center" ><span style="font-family: Arial, Helvetica, sans-serif" class="f-size-16"><?php echo ($mother['pwd_flag'] == 'Y')?"&#9745;":"&#9633;"; ?></span></td>
		<td class="td-border-light-right td-border-light-bottom valign-mid" align="center"><?php echo $mother['relation_birth_date'];?></td>
		<td class="td-border-light-right td-border-light-bottom" align="center"><?php echo $mother['relation_gender_code'];?></td>
	</tr>
</table>

<table class="table-max" style="font-size: 10px;page-break-after:always">
	<tr>
		<td class="td-border-light-left td-border-light-right td-border-light-bottom" colspan=2 align="left" valign=middle style="background-color: #d3d3d3;"><?php echo nbs(2) ?><b>MEMBERSHIP CATEGORY</b></td>
	</tr>
	<tr>
		<td class="td-border-light-right td-border-light-left td-border-light-bottom" width="50%" align="left" valign=middle>
			<span><?php echo nbs(2) ?><b>3.1 Formal Economy</b><br>
			<?php echo nbs(8) ?>&#9633; Private <?php echo nbs(5) ?>&#9745; Government<br>
			<span style="font-family: Arial, Helvetica, sans-serif"><?php echo nbs(15); echo(($formal_economy['employment_status_name'] == EMPLOYMENT_STATUS_NAME_PERMANENT) OR ($formal_economy['employment_status_name'] == EMPLOYMENT_STATUS_NAME_REGULAR))? "&#9745;":"&#9633;"?></span> Permanent/Regular <span style="font-family: Arial, Helvetica, sans-serif"><?php echo nbs(2); echo($formal_economy['employment_status_name'] == EMPLOYMENT_STATUS_NAME_CASUAL)? "&#9745;":"&#9633;";?></span> Casual <span style="font-family: Arial, Helvetica, sans-serif"><?php echo nbs(2); echo($formal_economy['employment_status_name'] == EMPLOYMENT_STATUS_NAME_CONTRACTUAL)? "&#9745;":"&#9633;"?></span> Contractor/Project-Based<br>
			<?php echo nbs(8) ?>&#9633; Enterprise Owner<br>
			<?php echo nbs(8) ?>&#9633; Household Help / Kasambahay<br>
			<?php echo nbs(8) ?>&#9633; Family Driver
			</span>
		</td>
		<td class="td-border-light-right td-border-light-bottom" width="50%" align="left" valign=top>
			<span><br><?php echo nbs(2) ?><b>3.3 Indigent</b><br>
			<?php echo nbs(8) ?>&#9633; NHTS-PR
			</span>
		</td>
	</tr>
	<tr>
		<td class="td-border-light-right td-border-light-left td-border-light-bottom" width="50%" align="left" valign=middle>
			<span><?php echo nbs(2) ?><b>3.2 Informal Economy</b><br>
			<?php echo nbs(8) ?>&#9633; Migrant Worker<br>
			<?php echo nbs(15) ?>&#9633; Land Based &#9633; Sea Based<br>
			<?php echo nbs(8) ?>&#9633; Informal Sector <span class="f-size-8">(e.g. Market Vendor, Street Hawker, Pedicab/Tricycle Driver, etc.)<br><?php echo nbs(15) ?>(Please specify):</span><u><?php echo nbs(70) ?></u><br>
			<?php echo nbs(15) ?>Estimated Monthly Income: Php <u><?php echo nbs(50) ?></u><br>
			<?php echo nbs(16) ?>&#9633; No Income<br>
			<?php echo nbs(8) ?>&#9633; Self-Earning Individual <span class="f-size-8">(e.g. Doctors, Lawyers, Engineers, Artists, etc.)<br><?php echo nbs(15) ?>(Please specify):</span><u><?php echo nbs(70) ?></u><br>
			<?php echo nbs(15) ?>Estimated Monthly Income: Php <u><?php echo nbs(50) ?></u><br>
			<?php echo nbs(8) ?>&#9633; Filipino with Dual Citizenship<br>
			<?php echo nbs(8) ?>&#9633; Naturalized Filipino Citizen<br>
			<?php echo nbs(8) ?>&#9633; Citizen of other countries working/residing/studying in the Philippines<br>
			<?php echo nbs(8) ?>&#9633; Organized Group (Please specify): <u><?php echo nbs(50) ?></u><br>
			</span>
		</td>
		<td class="td-border-light-right td-border-light-bottom" width="50%" align="left" valign=top>
			<span><?php echo nbs(2) ?><b>3.4 Sponsored</b><br>
			<?php echo nbs(8) ?>&#9633; Local Government Unit <span class="f-size-8">(Please specify): <u><?php echo nbs(63) ?></u></span><br>
			<?php echo nbs(8) ?>&#9633; National Government Agency <span class="f-size-8">(Please specify): <u><?php echo nbs(50) ?></u></span><br>
			<?php echo nbs(8) ?>&#9633; Others <span class="f-size">(Please specify): <u><?php echo nbs(95) ?></u></span><br>
			</span>
			<table>
				<tr>
					<td class="td-border-light-top" width="180">
						<span><br><?php echo nbs(2) ?><b>3.5 Liftime Member</b><br>
						<?php echo nbs(8) ?>&#9633; Retiree / Pensioner<br>
						<?php echo nbs(8) ?>&#9633; With 120 months contribution<br><?php echo nbs(12) ?>and has reached retirement age
						</span>
					</td>
					<td class="td-border-light-top" width="165">
						<b><i>Date/Effectivity of Retirement:</i></b>
						<table>
							<tr>
								<td class="td-border-light-right td-border-light-left td-border-light-bottom td-border-light-top" width="18" height="20"></td>
								<td class="td-border-light-right td-border-light-bottom td-border-light-top" width="18" height="20"></td>
								<td class="td-border-light-right td-border-light-bottom td-border-light-top" width="18" height="20"></td>
								<td class="td-border-light-right td-border-light-bottom td-border-light-top" width="18" height="20"></td>
								<td class="td-border-light-right td-border-light-bottom td-border-light-top" width="18" height="20"></td>
								<td class="td-border-light-right td-border-light-bottom td-border-light-top" width="18" height="20"></td>
								<td class="td-border-light-right td-border-light-bottom td-border-light-top" width="18" height="20"></td>
								<td class="td-border-light-right td-border-light-bottom td-border-light-top" width="18" height="20"></td>
							</tr>
							<tr>
								<td align="right" valign=top>m</td>
								<td align="left" valign=top>m</td>
								<td align="right" valign=top>d</td>
								<td align="left" valign=top>d</td>
								<td align="left" valign=top></td>
								<td align="right" valign=top>yy</td>
								<td align="left" valign=top>yy</td>
								<td align="left" valign=top></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td class="td-border-light-right td-border-light-left td-border-light-bottom" valign=top>
			<table>
				<tr>
					<td width="260">
						<span style="font-size: 11px;"><br><?php echo nbs(10) ?>Under the penalty of law, I attest that the<br><?php echo nbs(9) ?>information I provide in this Form are true<br><?php echo nbs(9) ?>and accurate to the best of my knowledge.<br><br>
						<?php echo nbs(2) ?><u><?php echo nbs(55) ?></u><?php echo nbs(5) ?><u><?php echo nbs(20) ?></u><br><?php echo nbs(5) ?>Signature over Printed Name <?php echo nbs(15) ?>Date
						</span>
					</td>
					<td>
						<table>
							<tr>
								<td><img src="<?php echo base_url().PATH_IMG ?>thumbmark.jpg" width=90 height=72></img></td>
							</tr>
							<tr>
								<td style="font-size: 6.5px;"><span>Please affix right thumbmark <br><?php echo nbs(12) ?>if unable to write.</span></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
		<td class="td-border-light-right td-border-light-bottom" >
			<span><br><?php echo nbs(2) ?><b>Please do not write on this portion. For filling-out by PhilHealth <?php echo nbs(2) ?>Officer:</b><br><br><br>
			<?php echo nbs(2) ?>Received by: <u><?php echo nbs(46) ?></u><?php echo nbs(3) ?>Date: <u><?php echo nbs(25) ?></u><br>
			<?php echo nbs(2) ?>Evaluated by: <u><?php echo nbs(45) ?></u><?php echo nbs(3) ?>Date: <u><?php echo nbs(25) ?></u><br>
			</span>
		</td>
	</tr>
</table>
<table style="margin-left: 20px;">
	<tr>
		<td class="td-border-light-right td-border-light-bottom td-border-light-left td-border-light-top" width="625" style="padding: 20px;">
			<table style="font-size: 11px;">
				<tr>
					<td height="40" align="center" valign="middle" ><b>INSTRUCTIONS</b></td>
				</tr>
				<tr>
					<td height="15"><div class="align-j">1.<?php echo nbs(5) ?>For PURPOSE, put a mark <span style="font-size: 20px">&#x2611;</span> FOR ENROLLMENT if you have never been issued a PhilHealth Identification</div></td>
				</tr>
				<tr>
					<td height="15"><?php echo nbs(8) ?>Number (PIN) or Family Health Card. Mark <span style="font-size: 20px">&#x2611;</span> FOR UPDATING if you want to update or make corrections to</td>
				</tr>
				<tr>
					<td height="15"><?php echo nbs(8) ?>certain information previously submitted when you enrolled. Fill-out the appropriate portions of the form.</td>
				</tr>
				<tr>
					<td height="15">2.<?php echo nbs(5) ?>Please write in CAPITAL LETTERS.</td>
				</tr>
				<tr>
					<td height="15">3.<?php echo nbs(5) ?>ALL FIELDS in item 1 for Member Information ARE MANDATORY. The Member should fill-out all required</td>
				</tr>
				<tr>
					<td height="15"><?php echo nbs(8) ?>information.</td>
				</tr>
				<tr>
					<td height="15">4.<?php echo nbs(5) ?>Write N.A. if the information is not applicable.</td>
				</tr>
				<tr>
					<td height="15">5.<?php echo nbs(5) ?>All name entries should be in the following format:</td>
				</tr>
				<tr>
					<td height="15"></td>
				</tr>
				<tr>
					<td height="15"><?php echo nbs(17) ?>Example: JUAN ANDRES DELA CRUZ SANTOS III will be entered as:</td>
				</tr>
				<tr>
					<td height="15">
						<table style="font-size: 11px; margin-left: 50px;">
							<tr>
								<td width="100" height="17" align="center" valign=bottom><u>Last Name</u></td>
								<td width="100" align="center" valign=bottom><u>First Name</u></td>
								<td width="100" align="center" valign=bottom><u>Name Extension</u></td>
								<td width="100" align="center" valign=bottom><u>Middle Name</u></td>
							</tr>
							<tr>
								<td width="100" align="center" valign=top>SANTOS</td>
								<td width="100" align="center" valign=top>JUAN ANDRES</td>
								<td width="100" align="center" valign=top>III</td>
								<td width="100" align="center" valign=top>DELA CRUZ</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td height="15"></td>
				</tr>
				<tr>
					<td height="15">6.<?php echo nbs(5) ?>For the Declaration of Dependents, fill-out the names of the living spouse, children and parents in items 2.1, 2.2</td>
				</tr>
				<tr>
					<td height="15"><?php echo nbs(8) ?>and 2.3 following the same format above.</td>
				</tr>
				<tr>
					<td height="15"></td>
				</tr>
				<tr>
					<td height="15"><?php echo nbs(17) ?>Put a mark <span style="font-size: 20px">&#x2611;</span> in the box for item 2.2 if child has disability.</td>
				</tr>
				<tr>
					<td height="15"><?php echo nbs(17) ?>Put a mark <span style="font-size: 20px">&#x2611;</span> in the box for item 2.3 if parent has disability.</td>
				</tr>
				<tr>
					<td height="15"><?php echo nbs(17) ?>Please indicate FULL MOTHER’S NAME for item 2.3.</td>
				</tr>
				<tr>
					<td height="15"></td>
				</tr>
				<tr>
					<td height="15">7.<?php echo nbs(5) ?>For declared dependents with disability, please submit a Medical Certificate indicating the details and extent</td>
				</tr>
				<tr>
					<td height="15"><?php echo nbs(8) ?>of disability. As defined in the Implementing Rules and Regulations of the National Health Insurance Act of</td>
				</tr>
				<tr>
					<td height="15"><?php echo nbs(8) ?>2013, the following are included as qualified dependents:</td>
				</tr>
				<tr>
					<td height="15"></td>
				</tr>
				<tr>
					<td height="15"><?php echo nbs(8) ?>a. Children who are twenty-one (21) years old or above but suffering from congenital disability, either</td>
				</tr>
				<tr>
					<td height="15"><?php echo nbs(8) ?>physical or mental, or any disability acquired that renders them totally dependent on the member for support.</td>
				</tr>
				<tr>
					<td height="15"></td>
				</tr>
				<tr>
					<td height="15"><?php echo nbs(8) ?>b. Parents with permanent disability regardless of age that renders them totally dependent on the member</td>
				</tr>
				<tr>
					<td height="15"><?php echo nbs(8) ?>for subsistence.</td>
				</tr>
				<tr>
					<td height="15"></td>
				</tr>
				<tr>
					<td height="15">8.<?php echo nbs(5) ?>For MEMBERSHIP CATEGORY, put a mark <span style="font-size: 20px">&#x2611;</span> in the appropriate box and specify details as necessary.</td>
				</tr>
				<tr>
					<td height="15">9.<?php echo nbs(5) ?>The member or guardian (if member is a minor) should certify that the information provided are true and</td>
				</tr>
				<tr>
					<td height="15"><?php echo nbs(8) ?>correct by affixing his/her signature over the printed name in the space provided for. If unable to write,</td>
				</tr>
				<tr>
					<td height="15"><?php echo nbs(8) ?>please affix the right thumbmark in the space provided.</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
</div>
<!-- ************************************************************************** -->
</body>

</html>
