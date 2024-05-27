<?php
	/*$first_record = $records[0];
	$last_record = end($records);*/
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<html>
<head>
	
	<title>GSIS Membership Form</title>
	<link rel="stylesheet" href="<?php echo base_url().PATH_CSS ?>reports.css" type="text/css" />
</head>
<style type="text/css">
	table 
	{
		font-family: "Times New Roman", Times, serif;
	}
</style>
<body>
<table>
	<tr>
		<td width="90" height="100" style="font-size: 8px" valign=top><b>Form No. MIS-05-02</b><br><br><br><?php echo nbs(5)?><img src="<?php echo base_url().PATH_IMG ?>gsis_logo.jpg" width=60 height=55></img></td>
		<td valign=top><br><br>
			<span style="font-size: 15px"><b>PASEGURUHAN NG MGA NAGLILINGKOD SA PAMAHALAAN</b></span><br>
			<span style="font-size: 14px"><b>(Government Service Insurance System)</b></span><br>
			<span style="font-size: 12px">Financial Center, Roxas Boulevard, Pasay City</span><br><br><br>
			<span style="font-size: 20px"><b>MEMBERSHIP INFORMATION SHEET</b></span>
		</td>
		<td><br><br><br><img src="<?php echo base_url().PATH_IMG ?>id_picture.png" width=145 height=130></img></td>
	</tr>
	<tr>
		<td class="td-border-light-bottom" colspan=3 height="5"></td>
	</tr>
</table>
<!-- ********** TABLE 1 ********** -->
<table style="font-size: 13px">
	<tr>
		<td colspan=4 height="3"></td>
	</tr>
	<tr>
		<td class="td-border-light-top" colspan=4 height="10"></td>
	</tr>
	<tr>
		<td colspan=4 height="30" style="font-size: 13px" valign=top><b>PERSONAL DATA:</b></td>
	</tr>
	<tr>
		<td width="40">Name:</td>
		<td width="220" class="td-border-light-bottom" align="center"><?php echo $personal_info['last_name'] ?></td>
		<td width="220" class="td-border-light-bottom" align="center"><?php echo $personal_info['first_name'] ?><?php echo nbs(5) ?><?php echo $personal_info['ext_name'] ?></td>
		<td width="220" class="td-border-light-bottom" align="center"><?php echo $personal_info['middle_name'] ?></td>
	</tr>
	<tr>
		<td></td>
		<td align="center" height="20"><i>Last Name</i></td>
		<td align="center"><i>First Name</i></td>
		<td align="center"><i>Middle Name</i></td>
	</tr>	
	<tr>
		<td colspan=4 height="10"></td>
	</tr>
</table>

<!-- ********** TABLE 2 ********** -->
<table style="font-size: 13px">
	<tr>
		<td width="30">Sex:</td>
		<td width="130" class="td-border-light-bottom" align="center"><?php echo $personal_info['gender'] ?></td>
		<td width="90" align="right">Civil Status:</td>
		<td width="190" class="td-border-light-bottom" align="center"><?php echo $personal_info['civil_status_name'] ?></td>
		<td width="40" align="right">TIN:</td>
		<td width="220" class="td-border-light-bottom" align="center"><?php echo format_identifications($personal_info['identification_value'], $personal_info['format']) ?></td>
	</tr>
	<tr>
		<td colspan=6 height="10"></td>
	</tr>
</table>

<!-- ********** TABLE 3 ********** -->
<table style="font-size: 13px">
	<tr>
		<td width="90">Date of Birth:</td>
		<td width="140" class="td-border-light-bottom" align="center"><?php echo $personal_info['birth_date'] ?></td>
		<td width="100" align="right">Place of Birth:</td>
		<td width="185" class="td-border-light-bottom" align="center"><?php echo $personal_info['birth_place'] ?></td>
		<td width="185" class="td-border-light-bottom"></td>
	</tr>
	<tr>
		<td></td>
		<td style="font-size: 10px" height="17" align="center" valign=middle><i>(Month/Day/Year)</i></td>
		<td align="right"></td>
		<td style="font-size: 10px" align="center" valign=middle><i>Town/District</i></td>
		<td style="font-size: 10px" align="center" valign=middle><i>City/Province</i></td>
	</tr>
	<tr>
		<td colspan=5 height="10"></td>
	</tr>
</table>

<!-- ********** TABLE 4 ********** -->
<table style="font-size: 13px">
	<tr>
		<td colspan="5">Residence/Mailing Address:</td>
	</tr>
	<tr>
		<td width="160" height="20" class="td-border-light-bottom"><?php echo $residential_address['address_value'] ?></td>
		<td width="150" class="td-border-light-bottom" align="center"><?php echo $residential_address['barangay_name'] ?></td>
		<td width="130" class="td-border-light-bottom" align="center"><?php echo $residential_address['municity_name'] ?></td>
		<td width="130" class="td-border-light-bottom" align="center"><?php echo $residential_address['province_name'] ?></td>
		<td width="130" class="td-border-light-bottom" align="center"><?php echo $residential_address['postal_number'] ?></td>
	</tr>
	<tr>
		<td style="font-size: 10px" height="17" align="center" valign=middle><i>House, Apt. or Bldg No./St. Name</i></td>
		<td style="font-size: 10px" align="center" valign=middle><i>Barangay or Barrio</i></td>
		<td style="font-size: 10px" align="center" valign=middle><i>Town/City</i></td>
		<td style="font-size: 10px" align="center" valign=middle><i>Province</i></td>
		<td style="font-size: 10px" align="center" valign=middle><i>Zip Code</i></td>
	</tr>
	<tr>
		<td class="td-border-light-bottom" colspan=5 height="10"></td>
	</tr>
</table>

<!-- ********** TABLE 5 ********** -->
<table style="font-size: 13px">
	<tr>
		<td colspan=4 height="3"></td>
	</tr>
	<tr>
		<td class="td-border-light-top" colspan=4 height="10"></td>
	</tr>
	<tr>
		<td colspan=4 height="30" style="font-size: 13px" valign=top><b>EMPLOYMENT DATA:</b></td>
	</tr>
	<tr>
		<td width="40">Office:</td>
		<td width="310" class="td-border-light-bottom" align="center"><?php echo $employment_info['name'] ?></td>
		<td width="200" align="right">Date of Original Appointment:</td>
		<td width="150" class="td-border-light-bottom" align="center"><?php echo $start_date['start_date'] ?></td>
	</tr>
	<tr>
		<td></td>
		<td></td>
		<td></td>
		<td style="font-size: 10px" height="17" align="center" valign=middle><i>(Month/Day/Year)</i></td>
	</tr>
</table>

<!-- ********** TABLE 6 ********** -->
<table style="font-size: 13px">
	<tr>
		<td colspan=4>Office Address:</td>
	</tr>
	<tr>
		<td width="100" height="30" class="td-border-light-bottom" align="center"><?php echo $doh_building['sys_param_value'] ?></td>
		<td width="150" class="td-border-light-bottom" align="center"><?php echo $doh_street['sys_param_value'] ?></td>
		<td width="225" class="td-border-light-bottom" align="center"><?php echo $doh_subdivision['sys_param_value'] ?> <?php echo $doh_barangay['sys_param_value'] ?></td>
		<td width="225" class="td-border-light-bottom" align="center"><?php echo $doh_municity['sys_param_value'] ?></td>
	</tr>
	<tr>
		<td style="font-size: 10px" height="17" align="center" valign=middle><i>No.</i></td>
		<td style="font-size: 10px" align="center" valign=middle><i>Street</i></td>
		<td style="font-size: 10px" align="center" valign=middle><i>Town/City</i></td>
		<td style="font-size: 10px" align="center" valign=middle><i>Province</i></td>
	</tr>
</table>

<!-- ********** TABLE 7 ********** -->
<table style="font-size: 13px">
	<tr>
		<td height="10"></td>
	</tr>
	<tr>
		<td width="90">Position Title:</td>
		<td width="290" class="td-border-light-bottom" align="center"><?php echo $employment_info['position_name'] ?></td>
		<td width="145" align="right">Status of Appointment:</td>
		<td width="185" class="td-border-light-bottom" align="center"><?php echo $employment_info['employment_status_name'] ?></td>
	</tr>
</table>

<!-- ********** TABLE 8 ********** -->
<table style="font-size: 13px">
	<tr>
		<td height="10"></td>
	</tr>
	<tr>
		<td width="100">Present Salary:</td>
		<td width="220" class="td-border-light-bottom" align="center"><?php echo $employment_info['employ_monthly_salary'] ?></td>
		<td width="230" align="right">Date of Effectivity of Present Salary:</td>
		<td width="155" class="td-border-light-bottom" align="center"><?php echo $employment_info['employ_start_date'] ?></td>
	</tr>
	<tr>
		<td></td>
		<td></td>
		<td></td>
		<td style="font-size: 10px" height="17" align="center" valign=middle><i>(Month/Day/Year)</i></td>
	</tr>
</table>

<!-- ********** TABLE 9 ********** -->
<table style="font-size: 13px">
	<tr>
		<td height="10"></td>
	</tr>
	<tr>
		<td width="170"><i>For DEPED Employees only:</i></td>
		<td width="90" align="right">Division No.:</td>
		<td class="td-border-light-bottom" width="90"></td>
		<td width="80" align="right">Station No.:</td>
		<td class="td-border-light-bottom" width="90"></td>
		<td width="100" align="right">Employee No.:</td>
		<td class="td-border-light-bottom" width="90"></td>
	</tr>
	<tr>
		<td class="td-border-light-bottom" colspan=7 height="10"></td>
	</tr>
</table>

<!-- ********** TABLE 10 ********** -->
<table style="font-size: 13px">
	<tr>
		<td colspan=4 height="3"></td>
	</tr>
	<tr>
		<td class="td-border-light-top" colspan=4 height="10"></td>
	</tr>
	<tr>
		<td width="100">Home Tel. No.:</td>
		<td width="273" class="td-border-light-bottom"><?php echo format_identifications($contacts_info['hometel'], TELEPHONE_FORMAT) ?></td>
		<td width="100" align="right">Celphone No.:</td>
		<td width="230" class="td-border-light-bottom"><?php echo nbs(2)?><?php echo format_identifications($contacts_info['mobile'], CELLPHONE_FORMAT) ?></td>
	</tr>
	<tr>
		<td height="10"></td>
	</tr>
	<tr>
		<td>Office Tel. No.:</td>
		<td class="td-border-light-bottom"><?php echo format_identifications($contacts_info['landline'], TELEPHONE_FORMAT) ?></td>
		<td align="right">eMail Address:</td>
		<td class="td-border-light-bottom"><?php echo nbs(2)?><?php echo $contacts_info['email'] ?></td>
	</tr>
	<tr>
		<td class="td-border-light-bottom" colspan=4 height="10"></td>
	</tr>
</table>

<!-- ********** TABLE 110 ********** -->
<table style="font-size: 13px">
	<tr>
		<td colspan=2 height="3"></td>
	</tr>
	<tr>
		<td class="td-border-light-top" colspan=2 height="10"></td>
	</tr>	
	<tr>
		<td class="td-border-light-bottom" width="230" height="40"></td>
		<td width="500"></td>
	</tr>
	<tr>
		<td align="center">Signature of Member</td>
		<td></td>
	</tr>
	<tr>
		<td colspan=2 height="40" align=left>Attested:</td>
	</tr>
	<tr>
		<td class="td-border-light-bottom" height="50"></td>
		<td></td>
	</tr>
	<tr>
		<td align="center">Signature over Printed Name of<br>Personnel/Administrative Officer</td>
		<td></td>
	</tr>
</table>

<!-- ************************************************************************** -->
</body>

</html>
