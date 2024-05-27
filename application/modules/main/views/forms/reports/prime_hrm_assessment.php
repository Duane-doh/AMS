
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<html>
<head>
	
	<title>Prime HRM Assessment</title>
	<link rel="stylesheet" href="<?php echo base_url().PATH_CSS ?>reports.css" type="text/css" />
</head>

<body>
<table class="table-max f-size-12">
	<tr>
		<td colspan="3" class="f-size-notice" align="center" valign=middle><b>PRIME &ndash; HRM ASSESSMENT<br><br>AGENCY PROFILE</b></td>
	</tr>
	<tr>
		<td height="17" align="center" valign=middle></td>
	</tr>
	<tr>
		<td class="td-border-light-top td-border-light-left td-border-light-right td-center-middle" width="290" height="17" align="left" valign=middle>AGENCY(Please do not abbreviate)</td>
		<td class="td-border-light-top td-border-light-right td-center-middle" align="left" width="140" valign=middle>REGION</td>
		<td class="td-border-light-top td-border-light-right td-center-middle" align="left" valign=middle>DATE OF ASSESSMENT</td>
		</tr>
	<tr>
		<td class="td-border-light-left td-border-light-right td-center-middle" height="30" align="center" valign=middle><br></td>
		<td class="td-border-light-right td-center-middle" align="center" valign=middle><br></td>
		<td class="td-border-light-right td-center-middle" align="center" valign=middle><br></td>
		</tr>
	</table>

	<table class="table-max f-size-12">
	<tr>
		<td class="td-border-light-top td-border-light-left td-border-light-right td-center-middle" width="85" height="17" align="left" valign=middle>SECTOR</td>
		<td class="td-border-light-top td-border-light-right td-center-middle" align="left" width="170" valign=middle>CURRENT AGENCY STATUS</td>
		<td class="td-border-light-top td-border-light-right td-center-middle" align="left" width="175" valign=middle>CSC RESOLUTION NO.</td>
		<td class="td-border-light-top td-border-light-right td-center-middle" align="left" valign=middle>RESOLUTION DATE</td>
		</tr>
	<tr>
		<td class="top td-border-light-left td-border-light-right td-center-middle" height="30" align="left" valign=middle><br></td>
		<td class="td-border-light-right td-center-middle" align="left" valign=middle><br></td>
		<td class="td-border-light-right td-center-middle" align="left" valign=middle><br></td>
		<td class="td-border-light-right td-center-middle" align="center" valign=middle><br></td>
		</tr>
	</table>
	<table class="table-max f-size-12">
	<tr>
		<td class="td-border-light-top td-border-light-left td-border-light-right td-center-middle" width="430" height="17" align="left" valign=middle>AGENCY HEAD</td>
		<td class="td-border-light-top td-border-light-right td-center-middle" align="left" valign=middle>POSITION TITLE</td>
		</tr>
	<tr>
		<td class="td-border-light-left td-border-light-right td-center-middle" height="30" align="center" valign=middle><?php echo $agency_head['employee_name']?></td>
		<td class="td-border-light-right td-center-middle" align="center" valign=middle><?php echo $agency_head['position_name']?></td>
		</tr>
	</table>
	<table class="table-max f-size-12">
	<tr>
		<td class="td-border-light-top td-border-light-left td-border-light-right td-center-middle" width="150" height="17" align="left" valign=middle>HRM OFFICERS</td>
		<td class="td-border-light-top td-border-light-right td-center-middle" align="left" width="140" valign=middle>POSITION TITLE</td>
		<td class="td-border-light-top td-border-light-right td-center-middle" align="left" width="140" valign=middle>EMPLOYMENT STATUS</td>
		<td class="td-border-light-top td-border-light-right td-center-middle" align="left" valign=middle>NO. OF EMPLOYEES IN HRM OFFICE</td>
		</tr>
	<tr>
		<td class="td-border-light-left td-border-light-right td-center-middle" height="17" align="center" valign=bottom><?php echo $hrm_officer['employee_name']?></td>
		<td class="td-border-light-right td-center-middle" align="center" valign=middle><?php echo $hrm_officer['position_name']?></td>
		<td class="td-border-light-right td-center-middle" align="center" valign=middle><?php echo $hrm_officer['employment_status_name']?></td>
		<td class="td-border-light-right td-center-middle" align="left" valign=middle>Permanent</td>
		</tr>
	<tr>
		<td class="td-border-light-left td-border-light-right td-center-middle" height="17" align="center" valign=middle><br></td>
		<td class="td-border-light-right td-center-middle" align="center" valign=middle><br></td>
		<td class="td-border-light-right td-center-middle" align="center" valign=middle><br></td>
		<td class="td-border-light-right td-center-middle" align="left" valign=middle>Temporary, Contractual, Casual</td>
		</tr>
	<tr>
		<td class="td-border-light-left td-border-light-right td-center-middle" height="17" align="center" valign=middle><br></td>
		<td class="td-border-light-right td-center-middle" align="center" valign=middle><br></td>
		<td class="td-border-light-right td-center-middle" align="center" valign=middle><br></td>
		<td class="td-border-light-right td-center-middle" align="left" valign=middle>Co-Terminous, Others</td>
		</tr>
	<tr>
		<td class="td-border-light-top td-center-middle" colspan="4" height="17" align="center" valign=middle></td>
	</tr>
</table>

<table class="table-max f-size-12">
	<tr>
		<td class="td-border-light-top td-border-light-left td-border-light-right td-center-middle" colspan=17 height="25" align="center" valign=middle><b>Agency Personnel Complement (Based on Updated Plantilla of Personnel)</b></td>
	</tr>
	<tr>
		<td class="td-border-light-top td-border-light-left td-border-light-right td-center-middle" colspan=2 rowspan=3 height="120" align="center" valign=middle style="font-size: 11px;"><b>AUTHORIZED<br>PERSONS</b></td>
		<td class="td-border-light-top td-border-light-right td-center-middle" colspan=2 rowspan=2 align="center" valign=middle><b>1st Level</b></td>
		<td class="td-border-light-top td-border-light-right td-center-middle" colspan=4 align="center" height="25" valign=middle><b>2nd Level</b></td>
		<td class="td-border-light-top td-border-light-right td-center-middle" colspan=2 align="center" valign=middle><b>3rd Level</b></td>
		<td class="td-border-light-top td-border-light-right td-center-middle" colspan=3 rowspan=2 align="center" valign=middle><b>Elective Officials</b></td>
		<td class="td-border-light-top td-border-light-right td-center-middle" colspan=4 rowspan=2 align="center" valign=middle><b>TOTAL</b></td>
	</tr>
	<tr>
		<td class="td-border-light-top td-border-light-left td-border-light-right td-center-middle" height="30" colspan=2 align="center" valign=middle><b>Professional/<br>Technical</b></td>
		<td class="td-border-light-top td-border-light-right td-center-middle" colspan=2 align="center" valign=middle><b>Executive/<br>Managerial</b></td>
		<td class="td-border-light-top td-border-light-right td-center-middle" colspan=2 align="center" valign=middle><b>Presidential<br>Appointees</b></td>
	</tr>
	<tr>
		<td class="td-border-light-top td-border-light-left td-border-light-right td-center-middle" colspan=2 align="center" valign=middle></td>
		<td class="td-border-light-top td-border-light-right td-center-middle" colspan=2 align="center" valign=middle></td>
		<td class="td-border-light-top td-border-light-right td-center-middle" colspan=2 align="center" valign=middle></td>
		<td class="td-border-light-top td-border-light-right td-center-middle" colspan=2 align="center" valign=middle></td>
		<td class="td-border-light-top td-border-light-right td-center-middle" colspan=3 align="center" valign=middle></td>
		<td class="td-border-light-top td-border-light-right td-center-middle" colspan=4 align="center" valign=middle></td>
	</tr>
	<tr>
		<td class="td-border-light-top td-border-light-left td-border-light-right td-center-middle" colspan=2 rowspan=3 height="51" align="center" valign=middle style="font-size: 10px;"><b>FILLED POSITIONS<br>as of</b></td>
		<td class="td-border-light-top td-border-light-right td-center-middle" colspan=15 align="center" height="20" valign=middle><b>Status of Employment</b></td>
	</tr>
	<tr>
		<td class="td-border-light-top td-border-light-left td-border-light-right td-center-middle" colspan=2 align="center"  height="20"  valign=middle><b>Permanent</b></td>
		<td class="td-border-light-top td-border-light-right td-center-middle" colspan=2 align="center" valign=middle><b>Temporary</b></td>
		<td class="td-border-light-top td-border-light-right td-center-middle" colspan=2 align="center" valign=middle><b>Coterminous</b></td>
		<td class="td-border-light-top td-border-light-right td-center-middle" colspan=2 align="center" valign=middle><b>Casuals</b></td>
		<td class="td-border-light-top td-border-light-right td-center-middle" colspan=2 align="center" valign=middle><b>Contractuals</b></td>
		<td class="td-border-light-top td-border-light-right td-center-middle" colspan=2 align="center" valign=middle><b>Elective</b></td>
		<td class="td-border-light-top td-border-light-right td-cente5r-middle" colspan=2 align="center" valign=middle><b>Job Order</b></td>
		<td class="td-border-light-top td-border-light-right td-center-middle" rowspan=2 align="center" valign=middle><b>Totals</b></td>
	</tr>
	<tr>
		<td class="td-border-light-top td-border-light-left td-border-light-right td-center-middle" colspan=1 align="center"  height="20"  valign=middle><b>Male</b></td>
		<td class="td-border-light-top td-border-light-right td-center-middle" colspan=1 align="center" valign=middle><b>Female</b></td>
		<td class="td-border-light-top td-border-light-right td-center-middle" colspan=1 align="center" valign=middle><b>Male</b></td>
		<td class="td-border-light-top td-border-light-right td-center-middle" colspan=1 align="center" valign=middle><b>Female</b></td>
		<td class="td-border-light-top td-border-light-right td-center-middle" colspan=1 align="center" valign=middle><b>Male</b></td>
		<td class="td-border-light-top td-border-light-right td-center-middle" colspan=1 align="center" valign=middle><b>Female</b></td>
		<td class="td-border-light-top td-border-light-right td-center-middle" colspan=1 align="center" valign=middle><b>Male</b></td>
		<td class="td-border-light-top td-border-light-right td-center-middle" colspan=1 align="center" valign=middle><b>Female</b></td>
		<td class="td-border-light-top td-border-light-right td-center-middle" colspan=1 align="center" valign=middle><b>Male</b></td>
		<td class="td-border-light-top td-border-light-right td-center-middle" colspan=1 align="center" valign=middle><b>Female</b></td>
		<td class="td-border-light-top td-border-light-right td-center-middle" colspan=1 align="center" valign=middle><b>Male</b></td>
		<td class="td-border-light-top td-border-light-right td-center-middle" colspan=1 align="center" valign=middle><b>Female</b></td>
		<td class="td-border-light-top td-border-light-right td-center-middle" colspan=1 align="center" valign=middle><b>Male</b></td>
		<td class="td-border-light-top td-border-light-right td-center-middle" colspan=1 align="center" valign=middle><b>Female</b></td>
	</tr>
	<tr>
		<td class="td-border-light-top td-border-light-left td-border-light-right td-center-middle" colspan=2 height="29" align="left" valign=middle style="padding-left: 15px;"><b>1st Level</b></td>
		<td class="td-border-light-top td-border-light-right td-center-middle" colspan=1 align="center" valign=middle height="40"><?php echo $records['permanent_male_level1'] ?></td>
		<td class="td-border-light-top td-border-light-right td-center-middle" colspan=1 align="center" valign=middle><?php echo $records['permanent_female_level1'] ?></td>
		<td class="td-border-light-top td-border-light-right td-center-middle" colspan=1 align="center" valign=middle><?php echo $records['temporary_male_level1'] ?></td>
		<td class="td-border-light-top td-border-light-right td-center-middle" colspan=1 align="center" valign=middle><?php echo $records['temporary_female_level1'] ?></td>
		<td class="td-border-light-top td-border-light-right td-center-middle" colspan=1 align="center" valign=middle><?php echo $records['coterminous_male_level1'] ?></td>
		<td class="td-border-light-top td-border-light-right td-center-middle" colspan=1 align="center" valign=middle><?php echo $records['coterminous_female_level1'] ?></td>
		<td class="td-border-light-top td-border-light-right td-center-middle" colspan=1 align="center" valign=middle><?php echo $records['casual_male_level1'] ?></td>
		<td class="td-border-light-top td-border-light-right td-center-middle" colspan=1 align="center" valign=middle><?php echo $records['casual_female_level1'] ?></td>
		<td class="td-border-light-top td-border-light-right td-center-middle" colspan=1 align="center" valign=middle><?php echo $records['contractual_male_level1'] ?></td>
		<td class="td-border-light-top td-border-light-right td-center-middle" colspan=1 align="center" valign=middle><?php echo $records['contractual_female_level1'] ?></td>
		<td class="td-border-light-top td-border-light-right td-center-middle" colspan=1 align="center" valign=middle>0</td>
		<td class="td-border-light-top td-border-light-right td-center-middle" colspan=1 align="center" valign=middle>0</td>
		<td class="td-border-light-top td-border-light-right td-center-middle" colspan=1 align="center" valign=middle><?php echo $records['jo_male_level1'] ?></td>
		<td class="td-border-light-top td-border-light-right td-center-middle" colspan=1 align="center" valign=middle><?php echo $records['jo_female_level1'] ?></td>
		<td class="td-border-light-top td-border-light-right td-center-middle" colspan=1 align="center" valign=middle></td>
	</tr>
	<tr>
		<td class="td-border-light-top td-border-light-left td-border-light-right td-center-middle" colspan=2 height="29" align="left" valign=middle style="padding-left: 15px;"><b>2nd Level<br>Professional/Technical</b></td>
		<td class="td-border-light-top td-border-light-right td-center-middle" colspan=1 align="center" valign=middle height="40"><?php echo $records['permanent_male_level2'] ?></td>
		<td class="td-border-light-top td-border-light-right td-center-middle" colspan=1 align="center" valign=middle><?php echo $records['permanent_female_level2'] ?></td>
		<td class="td-border-light-top td-border-light-right td-center-middle" colspan=1 align="center" valign=middle><?php echo $records['temporary_male_level2'] ?></td>
		<td class="td-border-light-top td-border-light-right td-center-middle" colspan=1 align="center" valign=middle><?php echo $records['temporary_female_level2'] ?></td>
		<td class="td-border-light-top td-border-light-right td-center-middle" colspan=1 align="center" valign=middle><?php echo $records['coterminous_male_level2'] ?></td>
		<td class="td-border-light-top td-border-light-right td-center-middle" colspan=1 align="center" valign=middle><?php echo $records['coterminous_female_level2'] ?></td>
		<td class="td-border-light-top td-border-light-right td-center-middle" colspan=1 align="center" valign=middle><?php echo $records['casual_male_level2'] ?></td>
		<td class="td-border-light-top td-border-light-right td-center-middle" colspan=1 align="center" valign=middle><?php echo $records['casual_female_level2'] ?></td>
		<td class="td-border-light-top td-border-light-right td-center-middle" colspan=1 align="center" valign=middle><?php echo $records['contractual_male_level2'] ?></td>
		<td class="td-border-light-top td-border-light-right td-center-middle" colspan=1 align="center" valign=middle><?php echo $records['contractual_female_level2'] ?></td>
		<td class="td-border-light-top td-border-light-right td-center-middle" colspan=1 align="center" valign=middle>0</td>
		<td class="td-border-light-top td-border-light-right td-center-middle" colspan=1 align="center" valign=middle>0</td>
		<td class="td-border-light-top td-border-light-right td-center-middle" colspan=1 align="center" valign=middle><?php echo $records['jo_male_level2'] ?></td>
		<td class="td-border-light-top td-border-light-right td-center-middle" colspan=1 align="center" valign=middle><?php echo $records['jo_female_level2'] ?></td>
		<td class="td-border-light-top td-border-light-right td-center-middle" colspan=1 align="center" valign=middle></td>
	</tr>
	<tr>
		<td class="td-border-light-top td-border-light-left td-border-light-right td-center-middle" colspan=2 height="29" align="left" valign=middle style="padding-left: 15px;"><b>2nd Level<br>Executive/Managerial</b></td>
		<td class="td-border-light-top td-border-light-right td-center-middle" colspan=1 align="center" valign=middle height="40"><?php echo $records['permanent_male_level2_executive'] ?></td>
		<td class="td-border-light-top td-border-light-right td-center-middle" colspan=1 align="center" valign=middle><?php echo $records['permanent_female_level2_executive'] ?></td>
		<td class="td-border-light-top td-border-light-right td-center-middle" colspan=1 align="center" valign=middle><?php echo $records['temporary_male_level2_executive'] ?></td>
		<td class="td-border-light-top td-border-light-right td-center-middle" colspan=1 align="center" valign=middle><?php echo $records['temporary_female_level2_executive'] ?></td>
		<td class="td-border-light-top td-border-light-right td-center-middle" colspan=1 align="center" valign=middle><?php echo $records['coterminous_male_level2_executive'] ?></td>
		<td class="td-border-light-top td-border-light-right td-center-middle" colspan=1 align="center" valign=middle><?php echo $records['coterminous_female_level2_executive'] ?></td>
		<td class="td-border-light-top td-border-light-right td-center-middle" colspan=1 align="center" valign=middle><?php echo $records['casual_male_level2_executive'] ?></td>
		<td class="td-border-light-top td-border-light-right td-center-middle" colspan=1 align="center" valign=middle><?php echo $records['casual_female_level2_executive'] ?></td>
		<td class="td-border-light-top td-border-light-right td-center-middle" colspan=1 align="center" valign=middle><?php echo $records['contractual_male_level2_executive'] ?></td>
		<td class="td-border-light-top td-border-light-right td-center-middle" colspan=1 align="center" valign=middle><?php echo $records['contractual_female_level2_executive'] ?></td>
		<td class="td-border-light-top td-border-light-right td-center-middle" colspan=1 align="center" valign=middle>0</td>
		<td class="td-border-light-top td-border-light-right td-center-middle" colspan=1 align="center" valign=middle>0</td>
		<td class="td-border-light-top td-border-light-right td-center-middle" colspan=1 align="center" valign=middle><?php echo $records['jo_male_level2_executive'] ?></td>
		<td class="td-border-light-top td-border-light-right td-center-middle" colspan=1 align="center" valign=middle><?php echo $records['jo_female_level2_executive'] ?></td>
		<td class="td-border-light-top td-border-light-right td-center-middle" colspan=1 align="center" valign=middle></td>
	</tr>
	<tr>
		<td class="td-border-light-top td-border-light-left td-border-light-right td-center-middle" colspan=2 height="29" align="left" valign=middle style="padding-left: 15px;"><b>3rd Level<br>(Presidential Appointees)</b></td>
		<td class="td-border-light-top td-border-light-right td-center-middle" colspan=1 align="center" valign=middle height="40"><?php echo $records['permanent_male_level3'] ?></td>
		<td class="td-border-light-top td-border-light-right td-center-middle" colspan=1 align="center" valign=middle><?php echo $records['permanent_female_level3'] ?></td>
		<td class="td-border-light-top td-border-light-right td-center-middle" colspan=1 align="center" valign=middle><?php echo $records['temporary_male_level3'] ?></td>
		<td class="td-border-light-top td-border-light-right td-center-middle" colspan=1 align="center" valign=middle><?php echo $records['temporary_female_level3'] ?></td>
		<td class="td-border-light-top td-border-light-right td-center-middle" colspan=1 align="center" valign=middle><?php echo $records['coterminous_male_level3'] ?></td>
		<td class="td-border-light-top td-border-light-right td-center-middle" colspan=1 align="center" valign=middle><?php echo $records['coterminous_female_level3'] ?></td>
		<td class="td-border-light-top td-border-light-right td-center-middle" colspan=1 align="center" valign=middle><?php echo $records['casual_male_level3'] ?></td>
		<td class="td-border-light-top td-border-light-right td-center-middle" colspan=1 align="center" valign=middle><?php echo $records['casual_female_level3'] ?></td>
		<td class="td-border-light-top td-border-light-right td-center-middle" colspan=1 align="center" valign=middle><?php echo $records['contractual_male_level3'] ?></td>
		<td class="td-border-light-top td-border-light-right td-center-middle" colspan=1 align="center" valign=middle><?php echo $records['contractual_female_level3'] ?></td>
		<td class="td-border-light-top td-border-light-right td-center-middle" colspan=1 align="center" valign=middle>0</td>
		<td class="td-border-light-top td-border-light-right td-center-middle" colspan=1 align="center" valign=middle>0</td>
		<td class="td-border-light-top td-border-light-right td-center-middle" colspan=1 align="center" valign=middle><?php echo $records['jo_male_level3'] ?></td>
		<td class="td-border-light-top td-border-light-right td-center-middle" colspan=1 align="center" valign=middle><?php echo $records['jo_female_level3'] ?></td>
		<td class="td-border-light-top td-border-light-right td-center-middle" colspan=1 align="center" valign=middle></td>
	</tr>
	<tr>
		<td class="td-border-light-top td-border-light-left td-border-light-right td-center-middle" colspan=2 height="40" align="left" valign=middle style="padding-left: 15px;"><b>Elective</b></td>
		<td class="td-border-light-top td-border-light-right td-center-middle" colspan=1 align="center" valign=middle height="40">0</td>
		<td class="td-border-light-top td-border-light-right td-center-middle" colspan=1 align="center" valign=middle>0</td>
		<td class="td-border-light-top td-border-light-right td-center-middle" colspan=1 align="center" valign=middle>0</td>
		<td class="td-border-light-top td-border-light-right td-center-middle" colspan=1 align="center" valign=middle>0</td>
		<td class="td-border-light-top td-border-light-right td-center-middle" colspan=1 align="center" valign=middle>0</td>
		<td class="td-border-light-top td-border-light-right td-center-middle" colspan=1 align="center" valign=middle>0</td>
		<td class="td-border-light-top td-border-light-right td-center-middle" colspan=1 align="center" valign=middle>0</td>
		<td class="td-border-light-top td-border-light-right td-center-middle" colspan=1 align="center" valign=middle>0</td>
		<td class="td-border-light-top td-border-light-right td-center-middle" colspan=1 align="center" valign=middle>0</td>
		<td class="td-border-light-top td-border-light-right td-center-middle" colspan=1 align="center" valign=middle>0</td>
		<td class="td-border-light-top td-border-light-right td-center-middle" colspan=1 align="center" valign=middle>0</td>
		<td class="td-border-light-top td-border-light-right td-center-middle" colspan=1 align="center" valign=middle>0</td>
		<td class="td-border-light-top td-border-light-right td-center-middle" colspan=1 align="center" valign=middle>0</td>
		<td class="td-border-light-top td-border-light-right td-center-middle" colspan=1 align="center" valign=middle>0</td>
		<td class="td-border-light-top td-border-light-right td-center-middle" colspan=1 align="center" valign=middle></td>
	</tr>
	<tr>
		<td class="td-border-light-top td-border-light-bottom td-border-light-left td-border-light-right td-center-middle" colspan=2 height="40" align="center" valign=middle style="font-size: 11px;"><b>TOTAL</b></td>
		<td class="td-border-light-top td-border-light-bottom td-border-light-right td-center-middle" colspan=15 align="center" valign=bottom></td>
	</tr>
</table>
<!-- ************************************************************************** -->
</body>

</html>
