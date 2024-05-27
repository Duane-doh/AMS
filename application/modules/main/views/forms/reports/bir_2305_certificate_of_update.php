<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<head>
 <title>Certificate of Update of Exemption and of Employer’s&nbsp;Employee’s Information</title>
 <link rel="stylesheet" href="<?php echo base_url().PATH_CSS ?>reports.css" type="text/css" />
 <style type="text/css">
		table{
		
		font-size:10px;
		font-family: Arial, Times, serif;
	}
	</style>
</head>
<body>
<table width="100%" style="padding: 0px !important;" cellpadding="0">
	<tr>
		<td colspan="4" class="f-size-8">To be filled-up by BIR  &#9656; DLN:</td>
	</tr>
	<tr class="td-border-thick-top td-border-thick-left td-border-thick-right td-border-thick-bottom">
		<td width="240" style="padding: 0px !important;">
			<table>
				<tr>
					<td width="50" align="center"><img src="<?php echo base_url().PATH_IMG ?>bir_logo.png" width=35 height=35></img></td>
					<td><span class="f-size-10">Republika ng Pilipinas<br>Kagawaran ng Pananalapi</span><span class="f-size-11"><br>Kawanihan ng Rentas Internas</span></td>
				</tr>
			</table>
		</td>
 		<td class="f-size-16" width="280" align="center" valign="middle" height="3">Certificate of Update of<br>Exemption and of Employer’s<br>and Employee’s Information</td>
		<td width="40"></td>
		<td class="f-size-10">BIR Form No.<br><span class="f-size-30">2305</span><br><span class="f-size-9">July 2008 (ENCS)</span></td>
	</tr>
	<tr class="td-border-thick-bottom td-border-thick-left td-border-thick-right">
		<td colspan="4">Fill in all applicable spaces. Mark all appropriate boxes with an “X”.</td>
	</tr>
</table>
<table width="100%" style="background-color: #BFBFBF">
	<tr class="td-border-thick-bottom td-border-thick-left td-border-thick-right">
		<td class="valign-top" width="10%"><b>1</b>&nbsp;Type of Filer<?php echo nbs(3)?>&#9656;<br></td>
		<td class="td-border-right" width="60%">
			<table>
				<tr><td height="3"></td></tr>
				<tr>
					<td class="td-border-light-top td-border-light-bottom td-border-light-left td-border-light-right td-center-middle bg-white" width="15" height="15">X</td>
					<td>Employee (for update of "Exemption" and other employer's and employee's information)</td>
				</tr>
				<tr><td height="3"></td></tr>
				<tr>
					<td class="td-border-light-top td-border-light-bottom td-border-light-left td-border-light-righ td-center-middle bg-white" width="15" height="15"></td>
					<td>Self-employed (for update of "Exemption")</td>
				</tr>
				<tr><td height="3"></td></tr>
			</table>
		</td>
		<td class="valign-top"><b>2</b>&nbsp;Effective Date<?php echo nbs(3)?>&#9656;<br></td>
		<td class="td-border-right">
			<table>
				<tr>
					<?php $date_today = date('mdY');?>
					<td class="td-border-light-top td-border-light-bottom td-border-light-left td-center-middle bg-white" width="17"><?php echo $date_today[0]?></td>
					<td class="td-border-light-top td-border-light-bottom td-center-middle bg-white" width="15" height="17"><?php echo $date_today[1]?></td>
					<td class="td-border-light-left td-border-light-top td-border-light-bottom td-center-middle bg-white" width="15"><?php echo $date_today[2]?></td>
					<td class="td-border-light-top td-border-light-bottom td-center-middle bg-white" width="15"><?php echo $date_today[3]?></td>
					<td class="td-border-light-left td-border-light-top td-border-light-bottom td-center-middle bg-white" width="15"><?php echo $date_today[4]?></td>
					<td class="td-border-light-top td-border-light-bottom td-center-middle bg-white" width="15"><?php echo $date_today[5]?></td>
					<td class="td-border-light-top td-border-light-bottom td-center-middle bg-white" width="15"><?php echo $date_today[6]?></td>
					<td class="td-border-light-top td-border-light-bottom td-border-light-right td-center-middle bg-white" width="15"><?php echo $date_today[7]?></td>
				</tr>
				<tr>
					<td class="align-c" colspan="8">(MM/DD/YYYY)</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<table width="100%" style="background-color: #BFBFBF">
	<tr>
		<td class="td-border-bottom td-border-thick-left"><b>Part I</b></td>
		<td class="td-border-bottom td-border-thick-right" colspan="2"><b>Taxpayer/Employee Information</b></td>
	</tr>
	<tr class="td-border-thick-bottom td-border-thick-left td-border-thick-right">
		<td class="td-border-right" width="50%">
			<b>3</b>&nbsp;TIN
			<table align="center">
				<tr>
					<td>&#9656;<?php echo nbs(5);?></td>
					<td class="border-light td-center-middle bg-white" width="15" height="17"><?php echo $header['employee_tin'][0];?></td>
					<td class="border-light td-center-middle bg-white" width="15"><?php echo $header['employee_tin'][1];?></td>
					<td class="border-light td-center-middle bg-white" width="15"><?php echo $header['employee_tin'][2];?></td>
					<td class="border-light td-center-middle bg-gray" width="15"></td>
					<td class="border-light td-center-middle bg-white" width="15"><?php echo $header['employee_tin'][3];?></td>
					<td class="border-light td-center-middle bg-white" width="15"><?php echo $header['employee_tin'][4];?></td>
					<td class="border-light td-center-middle bg-white" width="15"><?php echo $header['employee_tin'][5];?></td>
					<td class="border-light td-center-middle bg-gray" width="15"></td>
					<td class="border-light td-center-middle bg-white" width="15"><?php echo $header['employee_tin'][6];?></td>
					<td class="border-light td-center-middle bg-white" width="15"><?php echo $header['employee_tin'][7];?></td>
					<td class="border-light td-center-middle bg-white" width="15"><?php echo $header['employee_tin'][8];?></td>
					<td class="border-light td-center-middle bg-gray" width="15"></td>
					<td class="border-light td-center-middle bg-white" width="15"><?php echo $header['employee_tin'][9];?></td>
					<td class="border-light td-center-middle bg-white" width="15"><?php echo $header['employee_tin'][10];?></td>
					<td class="border-light td-center-middle bg-white" width="15"><?php echo $header['employee_tin'][11];?></td>
					<td class="border-light td-center-middle bg-white" width="15"><?php echo $header['employee_tin'][12];?></td>
				</tr>
				<tr><td height="3"></td></tr>
			</table>
		</td>
		<td class="td-border-right" width="20%">
			<b>4</b>&nbsp;RDO Code
			<table align="center">
				<tr>
					<td>&#9656;<?php echo nbs(5)?></td>
					<td class="border-light td-center-middle bg-white" width="15" height="17"><?php echo $header['rdo_code'][0];?></td>
					<td class="border-light td-center-middle bg-white" width="15"><?php echo $header['rdo_code'][1];?></td>
					<td class="border-light td-center-middle bg-white" width="15"><?php echo $header['rdo_code'][2];?></td>
				</tr>
				<tr><td height="3"></td></tr>
			</table>
		</td>
		<td width="35%" class="td-border-thick-right">
			<b>5</b>&nbsp;Sex
			<table align="center">
				<tr>
					<td>&#9656;<?php echo nbs(5)?></td>
					<td class="border-light td-center-middle bg-white" width="15" height="17"><?php echo $personal_info['gender_code']=="M" ? "X":""; ?></td>
					<td>&nbsp;&nbsp;Male</td>
					<td><?php echo nbs(10)?>&#9656;<?php echo nbs(5)?></td>
					<td class="border-light td-center-middle bg-white" width="15" height="17"><?php echo $personal_info['gender_code']=="F" ? "X":""; ?></td>
					<td>&nbsp;&nbsp;Female</td>
				</tr>
				<tr><td height="3"></td></tr>
			</table>
		</td>		
	</tr>
	<tr class="td-border-thick-bottom td-border-thick-left td-border-thick-right">
		<td class="td-border-bottom" colspan="2">
			<b>6</b> Payor's Name (Last Name, First Name, Middle Name) For Individuals
			<table>
				<tr>
					<td><?php echo nbs(20)?>&#9656;</td>
					<td class="border-light bg-white" width="420" height="22"><?php echo $header['employee_name'];?></td>
				</tr>
			</table>
		</td>
		<td class="td-border-bottom" colspan="3">
			<b>6A</b> Date of Birth
			<table align="center">
				<tr>
					<td class="td-border-light-top td-border-light-bottom td-border-light-left td-center-middle bg-white" width="15" height="17"><?php echo $header['birth_date'][0];?></td>
					<td class="td-border-light-top td-border-light-bottom td-center-middle bg-white" width="15"><?php echo $header['birth_date'][1];?></td>
					<td class="td-border-light-left td-border-light-top td-border-light-bottom td-center-middle bg-white" width="15"><?php echo $header['birth_date'][2];?></td>
					<td class="td-border-light-top td-border-light-bottom td-center-middle bg-white" width="15"><?php echo $header['birth_date'][3];?></td>
					<td class="td-border-light-left td-border-light-top td-border-light-bottom td-center-middle bg-white" width="15"><?php echo $header['birth_date'][4];?></td>
					<td class="td-border-light-top td-border-light-bottom td-center-middle bg-white" width="15"><?php echo $header['birth_date'][5];?></td>
					<td class="td-border-light-top td-border-light-bottom td-center-middle bg-white" width="15"><?php echo $header['birth_date'][6];?></td>
					<td class="td-border-light-top td-border-light-bottom td-border-light-right td-center-middle bg-white" width="15"><?php echo $header['birth_date'][7];?></td>
				</tr>
				<tr><td colspan="7" class="align-r">(MM/DD/YYYY)</td></tr>
			</table>
		</td>
	</tr>
	<tr class="td-border-thick-left td-border-thick-right">
		<td class="td-border-bottom" colspan="2">
			<b>7</b> Residence Address
			<table>
				<tr>
					<td><?php echo nbs(17)?><b>7A</b></td>
					<td class="border-light bg-white" width="420" height="17" rowspan="2"><?php echo $header['registered_address'];?></td>
				</tr>
				<tr>
					<td><?php echo nbs(20)?>&#9656;</td>
				</tr>
			</table>
		</td>
		<td class="td-border-bottom" colspan="3">
			<b>7B</b> Zip Code
			<table align="center">
				<tr>
					<td><?php echo nbs(20)?>&#9656;<?php echo nbs(5)?></td>
					<td class="border-light td-center-middle bg-white" width="15" height="17"><?php echo $header['registered_addr_zip_code'][0];?></td>
					<td class="border-light td-center-middle bg-white" width="15"><?php echo $header['registered_addr_zip_code'][1];?></td>
					<td class="border-light td-center-middle bg-white" width="15"><?php echo $header['registered_addr_zip_code'][2];?></td>
					<td class="border-light td-center-middle bg-white" width="15"><?php echo $header['registered_addr_zip_code'][3];?></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr class="td-border-thick-bottom td-border-thick-left td-border-thick-right">
		<td class="td-border-bottom" colspan="2">
			<?php echo nbs(4);?>Business Address (for Self-Employed)
			<table>
				<tr>
					<td><?php echo nbs(17)?><b>7C</b></td>
					<td class="border-light bg-white" width="420" height="17" rowspan="2"></td>
				</tr>
				<tr>
					<td><?php echo nbs(20)?>&#9656;</td>
				</tr>
			</table>
		</td>
		<td class="td-border-bottom" colspan="3">
			<b>7D</b> Zip Code
			<table align="center">
				<tr>
					<td><?php echo nbs(20)?>&#9656;<?php echo nbs(5)?></td>
					<td class="border-light td-center-middle bg-white" width="15" height="17"></td>
					<td class="border-light td-center-middle bg-white" width="15"></td>
					<td class="border-light td-center-middle bg-white" width="15"></td>
					<td class="border-light td-center-middle bg-white" width="15"></td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<table width="100%">
	<tr class="td-border-thick-left td-border-thick-right">
		<td class="align-c" colspan="3">
			 I declare, under the penalties of perjury, that this certificate has been made in good faith, verified by me, and to the best of my knowledge and belief, is true and correct, pursuant to the National Internal Revenue Code, as amended, and the regulations issued under authority thereof.
		</td>
	</tr>
	<tr class="td-border-thick-left td-border-thick-right">
		<td>&nbsp;</td>
	</tr>
	<tr class="td-border-thick-left td-border-thick-right">
		<td class="td-border-bottom align-c"><b>8<u><?php echo nbs(5) . $personal_info['employee_name'] . nbs(5); ?></u></b></td>
	</tr>
	<tr class="td-border-thick-bottom td-border-thick-left td-border-thick-right">
		<td class="align-c">Taxpayer/Authorized Agent Signature over Printed Name</td>
	</tr>
</table>
<table width="100%" style="background-color: #BFBFBF">
	<tr>
		<td class="td-border-bottom td-border-thick-left"><b>Part II</b></td>
		<td class="td-border-bottom td-border-thick-right" colspan="2"><b><?php echo nbs(15);?>Personal Exemptions</b></td>
	</tr>
	<tr>
		<td class="td-border-thick-left" width="225">
			<b>9 &#9656;</b> Civil Status
			<table>
				<tr>
					<td rowspan="5"><?php echo nbs(3);?></td>
					<td class="border-light bg-white align-c" width="15" height="10"><?php echo $personal_info['civil_status_id']==CIVIL_STATUS_SINGLE ? "X":""; ?></td>
					<td colspan="2"><?php echo nbs(3)?>Single</td>
				</tr>
				<tr>
					<td height="2" colspan="3"></td>
				</tr>
				<tr>
					<td class="border-light bg-white align-c" width="15" height="10"><?php echo $personal_info['civil_status_id']==CIVIL_STATUS_LEGALLY_SEPERATED ? "X":""; ?></td>
					<td colspan="2"><?php echo nbs(3)?>Legally Seperated</td>
				</tr>
				<tr>
					<td colspan="3">&nbsp;</td>
				</tr>
				<tr>
					<td><?php echo nbs(3);?></td>
					<td class="border-light bg-white align-c" width="15" height="10"><?php echo count($child_dependents)>0? "X":""?></td>
					<td><?php echo nbs(3)?>with qualified dependent child/ren</td>
				</tr>
			</table>
		</td>
		<td>
			&nbsp;
			<table>
				<tr>
					<td class="border-light bg-white align-c" width="15" height="10"><?php echo $personal_info['civil_status_id']==CIVIL_STATUS_WIDOW_ER ? "X":""; ?></td>
					<td rowspan="4">&nbsp;</td>
					<td><?php echo nbs(3)?>Widow/Widower</td>
				</tr>
				<tr>
					<td height="2" colspan="2"></td>
				</tr>
				<tr>
					<td class="border-light bg-white align-c" width="15" height="10"><?php echo $personal_info['civil_status_id']==CIVIL_STATUS_MARRIED ? "X":""; ?></td>
					<td><?php echo nbs(3)?>Married</td>
				</tr>
				<tr>
					<td colspan="2">&nbsp;</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td class="border-light bg-white align-c" width="15" height="10"><?php echo count($child_dependents)>0? "":"X"?></td>
					<td colspan="2"><?php echo nbs(3)?>without qualified dependent child/ren</td>
				</tr>
			</table>
		</td>
		<td class="td-border-thick-right">
			<b>10 &#9656;</b> Employment Status of Spouse:
			<table>
				<tr>
					<td rowspan="7"><?php echo nbs(5);?></td>
					<td class="border-light bg-white align-c" width="15" height="10"><?php echo $spouse_info['rel_employment_stat']==EMPLOYMENT_STATUS_UNEMPLOYED ? "X":""; ?></td>
					<td>Unemployed</td>
				</tr>
				<tr>
					<td height="3" colspan="2"></td>
				</tr>
				<tr>
					<td class="border-light bg-white align-c" width="15" height="10"><?php echo $spouse_info['rel_employment_stat']==EMPLOYMENT_STATUS_EMPLOYED_LOCALLY ? "X":""; ?></td>
					<td>Employed Locally</td>
				</tr>
				<tr>
					<td height="3" colspan="2"></td>
				</tr>
				<tr>
					<td class="border-light bg-white align-c" width="15" height="10"><?php echo $spouse_info['rel_employment_stat']==EMPLOYMENT_STATUS_EMPLOYED_ABROAD ? "X":""; ?></td>
					<td>Employed Abroad</td>
				</tr>
				<tr>
					<td height="3" colspan="2"></td>
				</tr>
				<tr>
					<td class="border-light bg-white align-c" width="15" height="10"><?php echo $spouse_info['rel_employment_stat']==EMPLOYMENT_STATUS_ENGAGE_IN_BUSINESS ? "X":""; ?></td>
					<td>Engaged in Business/Practice of Profession</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td class="td-border-thick-left td-border-thick-right" colspan="3">&nbsp;</td>
	</tr>
</table>
<table width="100%" style="background-color: #BFBFBF">
	<tr class="td-border-thick-left td-border-thick-right">
		<td class="font-size-sm" colspan="2"><b>11 &#9656;</b> Claims for Additional Exemptions / Premium Deductions for husband and wife whose aggregate family income does not exceed P250,000.00 per annum</td>
	</tr>
	<tr class="td-border-thick-left td-border-thick-right">
		<td align="center" width="50%">
			<table>
				<tr>
					<td rowspan="7"><?php echo nbs(5);?></td>
					<td class="border-light bg-white align-c" width="15" height="10"><?php echo !EMPTY($husband_exception)? "X":"";?></td>
					<td>Husband claims additional exemption and premium deductions</td>
				</tr>
			</table>
		</td>
		<td align="center">
			<table>
				<tr>
					<td rowspan="7"><?php echo nbs(5);?></td>
					<td class="border-light bg-white align-c" width="15" height="10"><?php echo !EMPTY($wife_exception)? "X":"";?></td>
					<td>Wife claims additional exemption  and  premium deductions</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr class="td-border-thick-left td-border-thick-right">
		<td>&nbsp;</td>
		<td class="align-c">(Attach Waiver of the Husband)</td>
	</tr>
	<tr class="td-border-thick-left td-border-thick-right">
		<td colspan="2"><b>12</b> Spouse Information</td>
	</tr>
	<tr>
		<td class="td-border-thick-left td-border-thick-right" width="50%" colspan="2">
			<?php echo nbs(5);?><b>12A</b>&nbsp;Spouse Taxpayer Identification Number
			<table>
				<tr>
					<td><?php echo nbs(10);?>&#9656;<?php echo nbs(5);?></td>
					<td class="border-light td-center-middle bg-white" width="15" height="17"><?php echo $spouse_info['tin'][0];?></td>
					<td class="border-light td-center-middle bg-white" width="15"><?php echo $spouse_info['tin'][1];?></td>
					<td class="border-light td-center-middle bg-white" width="15"><?php echo $spouse_info['tin'][2];?></td>
					<td class="border-light td-center-middle bg-gray" width="15"></td>
					<td class="border-light td-center-middle bg-white" width="15"><?php echo $spouse_info['tin'][3];?></td>
					<td class="border-light td-center-middle bg-white" width="15"><?php echo $spouse_info['tin'][4];?></td>
					<td class="border-light td-center-middle bg-white" width="15"><?php echo $spouse_info['tin'][5];?></td>
					<td class="border-light td-center-middle bg-gray" width="15"></td>
					<td class="border-light td-center-middle bg-white" width="15"><?php echo $spouse_info['tin'][6];?></td>
					<td class="border-light td-center-middle bg-white" width="15"><?php echo $spouse_info['tin'][7];?></td>
					<td class="border-light td-center-middle bg-white" width="15"><?php echo $spouse_info['tin'][8];?></td>
					<td class="border-light td-center-middle bg-gray" width="15"></td>
					<td class="border-light td-center-middle bg-white" width="15"><?php echo $spouse_info['tin'][9];?></td>
					<td class="border-light td-center-middle bg-white" width="15"><?php echo $spouse_info['tin'][10];?></td>
					<td class="border-light td-center-middle bg-white" width="15"><?php echo $spouse_info['tin'][11];?></td>
					<td class="border-light td-center-middle bg-white" width="15"><?php echo $spouse_info['tin'][12];?></td>
				</tr>
				<tr><td height="3"></td></tr>
			</table>
		</td>
	</tr>
	<tr>
		<td class="td-border-thick-left td-border-thick-right" colspan="2">
			<?php echo nbs(5);?><b>12B</b> Spouse Name (if wife, indicate maiden name)
			<table>
				<tr>
					<td><?php echo nbs(20)?>&#9656;</td>
					<td class="td-border-3 bg-white align-c" width="200" height="17"><?php echo ($spouse_info['gender']=='M'||$spouse_info['gender']="")? $spouse_info['ln']:$spouse_info['mn'];?></td>
					<td class="td-border-tb bg-white align-c" width="200"><?php echo $spouse_info['fn'];?></td>
					<td class="td-border-tb td-border-right bg-white align-c" width="200"><?php echo ($spouse_info['gender']=='M'||$spouse_info['gender']="")? $spouse_info['mn']:"";?></td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td class="align-c">Last Name</td>
					<td class="align-c">First Name</td>
					<td class="align-c">Middle Name</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td class="td-border-thick-bottom td-border-thick-left" width="40%">
			<?php echo nbs(5);?><b>12C</b>&nbsp;Spouse Employer's Taxpayer Identification Number
			<table>
				<tr>
					<td><?php echo nbs(10);?>&#9656;<?php echo nbs(5);?></td>
					<td class="border-light td-center-middle bg-white" width="15" height="17"><?php echo $payer_info['tin'][0];?></td>
					<td class="border-light td-center-middle bg-white" width="15"><?php echo $payer_info['tin'][1];?></td>
					<td class="border-light td-center-middle bg-white" width="15"><?php echo $payer_info['tin'][2];?></td>
					<td class="border-light td-center-middle bg-gray" width="15"></td>
					<td class="border-light td-center-middle bg-white" width="15"><?php echo $payer_info['tin'][3];?></td>
					<td class="border-light td-center-middle bg-white" width="15"><?php echo $payer_info['tin'][4];?></td>
					<td class="border-light td-center-middle bg-white" width="15"><?php echo $payer_info['tin'][5];?></td>
					<td class="border-light td-center-middle bg-gray" width="15"></td>
					<td class="border-light td-center-middle bg-white" width="15"><?php echo $payer_info['tin'][6];?></td>
					<td class="border-light td-center-middle bg-white" width="15"><?php echo $payer_info['tin'][7];?></td>
					<td class="border-light td-center-middle bg-white" width="15"><?php echo $payer_info['tin'][8];?></td>
					<td class="border-light td-center-middle bg-gray" width="15"></td>
					<td class="border-light td-center-middle bg-white" width="15"><?php echo $payer_info['tin'][9];?></td>
					<td class="border-light td-center-middle bg-white" width="15"><?php echo $payer_info['tin'][10];?></td>
					<td class="border-light td-center-middle bg-white" width="15"><?php echo $period_to[11];?></td>
					<td class="border-light td-center-middle bg-white" width="15"><?php echo $period_to[11];?></td>
				</tr>
				<tr><td height="3"></td></tr>
			</table>
		</td>
		<td class="td-border-thick-bottom td-border-thick-right align-c" width="60%">
			Spouse Employer's Name
			<table>
				<tr>
					<td><?php echo nbs(10);?>&#9656;<?php echo nbs(5);?></td>
					<td class="border-light td-center-middle bg-white align-c" width="340" height="17"><?php echo $spouse_info['relation_company'];?></td>
				</tr>
				<tr><td height="3"></td></tr>
			</table>
		</td>
	</tr>
</table>
<table width="100%" style="background-color: #BFBFBF">
	<tr>
		<td class="td-border-bottom td-border-thick-left" colspan="2"><b>Part III</b></td>
		<td class="td-border-bottom td-border-thick-right align-c" colspan="3"><b>Additional Exemptions</b></td>
	</tr>
	<tr class="td-border-thick-left td-border-thick-right">
		<td class="font-size-sm align-c" colspan="5" height="30"><b>13</b> Names of Qualified Dependent Child/ren (refers to a legitimate, illegitimate, or legally adopted child chiefly dependent upon & living with the taxpayer; not more than 21 years of age, unmarried, and not gainfully employed; or regardless of age, is incapable of self-support due to mental or physical defect)</td>
	</tr>
	<tr>
		<td class="td-border-tb td-border-thick-left align-c" height="30" width="20%">Last Name</td>
		<td class="td-border-tb td-border-left align-c" width="20%">First Name</td>
		<td class="td-border-tb td-border-left align-c" width="20%">Middle Name</td>
		<td class="td-border-tb td-border-left align-c" width="25%">Date of Birth (MM/DD/YYYY)</td>
		<td class="td-border-tb td-border-left td-border-thick-right align-c" width="15%">Mark if Mentally/Physically Incapacitated</td>
	</tr>
	<tr>
		<td class="td-border-thick-left">
			<table class="align-c" width="100%">
				<tr><td height="3"></td></tr>
				<tr>
					<td><b>13A</b> &#9656;<?php echo nbs(3);?></td>
					<td class="border-light td-center-middle bg-white" width="100" height="17"><?php echo $dependents[0]['ln'];?></td>
				</tr>
				<tr><td height="3"></td></tr>
			</table>
		</td>
		<td>
			<table class="align-c" width="100%">
				<tr><td height="3"></td></tr>
				<tr>
					<td><b>13B</b> &#9656;<?php echo nbs(3);?></td>
					<td class="border-light td-center-middle bg-white" width="100" height="17"><?php echo $dependents[0]['fn'];?></td>
				</tr>
				<tr><td height="3"></td></tr>
			</table>
		</td>
		<td>
			<table class="align-c" width="100%">
				<tr><td height="3"></td></tr>
				<tr>
					<td><b>13C</b> &#9656;<?php echo nbs(3);?></td>
					<td class="border-light td-center-middle bg-white" width="100" height="17"><?php echo $dependents[0]['mn'];?></td>
				</tr>
				<tr><td height="3"></td></tr>
			</table>
		</td>
		<td align="center">
			<table class="align-c">
				<tr><td height="3"></td></tr>
				<tr>
					<td><b>13D</b> &#9656;<?php echo nbs(3);?></td>
					<td class="td-border-light-top td-border-light-bottom td-border-light-left td-center-middle bg-white" width="15" height="17"><?php echo $dependents[0]['birth_date'][0];?></td>
					<td class="td-border-light-top td-border-light-bottom td-center-middle bg-white" width="15"><?php echo $dependents[0]['birth_date'][1];?></td>
					<td class="td-border-light-left td-border-light-top td-border-light-bottom td-center-middle bg-white" width="15"><?php echo $dependents[0]['birth_date'][2];?></td>
					<td class="td-border-light-top td-border-light-bottom td-center-middle bg-white" width="15"><?php echo $dependents[0]['birth_date'][3];?></td>
					<td class="td-border-light-left td-border-light-top td-border-light-bottom td-center-middle bg-white" width="15"><?php echo $dependents[0]['birth_date'][4];?></td>
					<td class="td-border-light-top td-border-light-bottom td-center-middle bg-white" width="15"><?php echo $dependents[0]['birth_date'][5];?></td>
					<td class="td-border-light-top td-border-light-bottom td-center-middle bg-white" width="15"><?php echo $dependents[0]['birth_date'][6];?></td>
					<td class="td-border-light-top td-border-light-bottom td-border-light-right td-center-middle bg-white" width="15"><?php echo $dependents[0]['birth_date'][7];?></td>
				</tr>
			</table>
		</td>
		<td class="td-border-thick-right align-c">
			<table class="align-c" width="100%">
				<tr><td height="3"></td></tr>
				<tr>
					<td><b>13E</b> &#9656;<?php echo nbs(3);?></td>
					<td class="border-light td-center-middle bg-white" width="15" height="17"><?php echo $dependents[0]['pwd_flag']=="Y"?"X":""; ?></td>
				</tr>
				<tr><td height="3"></td></tr>
			</table>
		</td>
	</tr>
	<tr>
		<td class="td-border-thick-left">
			<table class="align-c" width="100%">
				<tr><td height="3"></td></tr>
				<tr>
					<td><b>14A</b> &#9656;<?php echo nbs(3);?></td>
					<td class="border-light td-center-middle bg-white" width="100" height="17"><?php echo $dependents[1]['ln'];?></td>
				</tr>
				<tr><td height="3"></td></tr>
			</table>
		</td>
		<td>
			<table class="align-c" width="100%">
				<tr><td height="3"></td></tr>
				<tr>
					<td><b>14B</b> &#9656;<?php echo nbs(3);?></td>
					<td class="border-light td-center-middle bg-white" width="100" height="17"><?php echo $dependents[1]['fn'];?></td>
				</tr>
				<tr><td height="3"></td></tr>
			</table>
		</td>
		<td>
			<table class="align-c" width="100%">
				<tr><td height="3"></td></tr>
				<tr>
					<td><b>14C</b> &#9656;<?php echo nbs(3);?></td>
					<td class="border-light td-center-middle bg-white" width="100" height="17"><?php echo $dependents[1]['mn'];?></td>
				</tr>
				<tr><td height="3"></td></tr>
			</table>
		</td>
		<td align="center">
			<table class="align-c">
				<tr><td height="3"></td></tr>
				<tr>
					<td><b>14D</b> &#9656;<?php echo nbs(3);?></td>
					<td class="td-border-light-top td-border-light-bottom td-border-light-left td-center-middle bg-white" width="15" height="17"><?php echo $dependents[1]['birth_date'][0];?></td>
					<td class="td-border-light-top td-border-light-bottom td-center-middle bg-white" width="15"><?php echo $dependents[1]['birth_date'][1];?></td>
					<td class="td-border-light-left td-border-light-top td-border-light-bottom td-center-middle bg-white" width="15"><?php echo $dependents[1]['birth_date'][2];?></td>
					<td class="td-border-light-top td-border-light-bottom td-center-middle bg-white" width="15"><?php echo $dependents[1]['birth_date'][3];?></td>
					<td class="td-border-light-left td-border-light-top td-border-light-bottom td-center-middle bg-white" width="15"><?php echo $dependents[1]['birth_date'][4];?></td>
					<td class="td-border-light-top td-border-light-bottom td-center-middle bg-white" width="15"><?php echo $dependents[1]['birth_date'][5];?></td>
					<td class="td-border-light-top td-border-light-bottom td-center-middle bg-white" width="15"><?php echo $dependents[1]['birth_date'][6];?></td>
					<td class="td-border-light-top td-border-light-bottom td-border-light-right td-center-middle bg-white" width="15"><?php echo $dependents[1]['birth_date'][7];?></td>
				</tr>
			</table>
		</td>
		<td class="td-border-thick-right align-c">
			<table class="align-c" width="100%">
				<tr><td height="3"></td></tr>
				<tr>
					<td><b>14E</b> &#9656;<?php echo nbs(3);?></td>
					<td class="border-light td-center-middle bg-white" width="15" height="17"><?php echo $dependents[1]['pwd_flag']=="Y"?"X":"";?></td>
				</tr>
				<tr><td height="3"></td></tr>
			</table>
		</td>
	</tr>
	<tr>
		<td class="td-border-thick-left">
			<table class="align-c" width="100%">
				<tr><td height="3"></td></tr>
				<tr>
					<td><b>15A</b> &#9656;<?php echo nbs(3);?></td>
					<td class="border-light td-center-middle bg-white" width="100" height="17"><?php echo $dependents[2]['ln'];?></td>
				</tr>
				<tr><td height="3"></td></tr>
			</table>
		</td>
		<td>
			<table class="align-c" width="100%">
				<tr><td height="3"></td></tr>
				<tr>
					<td><b>15B</b> &#9656;<?php echo nbs(3);?></td>
					<td class="border-light td-center-middle bg-white" width="100" height="17"><?php echo $dependents[2]['fn'];?></td>
				</tr>
				<tr><td height="3"></td></tr>
			</table>
		</td>
		<td>
			<table class="align-c" width="100%">
				<tr><td height="3"></td></tr>
				<tr>
					<td><b>15C</b> &#9656;<?php echo nbs(3);?></td>
					<td class="border-light td-center-middle bg-white" width="100" height="17"><?php echo $dependents[2]['mn'];?></td>
				</tr>
				<tr><td height="3"></td></tr>
			</table>
		</td>
		<td align="center">
			<table class="align-c">
				<tr><td height="3"></td></tr>
				<tr>
					<td><b>15D</b> &#9656;<?php echo nbs(3);?></td>
					<td class="td-border-light-top td-border-light-bottom td-border-light-left td-center-middle bg-white" width="15" height="17"><?php echo $dependents[2]['birth_date'][0];?></td>
					<td class="td-border-light-top td-border-light-bottom td-center-middle bg-white" width="15"><?php echo $dependents[2]['birth_date'][1];?></td>
					<td class="td-border-light-left td-border-light-top td-border-light-bottom td-center-middle bg-white" width="15"><?php echo $dependents[2]['birth_date'][2];?></td>
					<td class="td-border-light-top td-border-light-bottom td-center-middle bg-white" width="15"><?php echo $dependents[2]['birth_date'][3];?></td>
					<td class="td-border-light-left td-border-light-top td-border-light-bottom td-center-middle bg-white" width="15"><?php echo $dependents[2]['birth_date'][4];?></td>
					<td class="td-border-light-top td-border-light-bottom td-center-middle bg-white" width="15"><?php echo $dependents[2]['birth_date'][5];?></td>
					<td class="td-border-light-top td-border-light-bottom td-center-middle bg-white" width="15"><?php echo $dependents[2]['birth_date'][6];?></td>
					<td class="td-border-light-top td-border-light-bottom td-border-light-right td-center-middle bg-white" width="15"><?php echo $dependents[2]['birth_date'][7];?></td>
				</tr>
			</table>
		</td>
		<td class="td-border-thick-right align-c">
			<table class="align-c" width="100%">
				<tr><td height="3"></td></tr>
				<tr>
					<td><b>15E</b> &#9656;<?php echo nbs(3);?></td>
					<td class="border-light td-center-middle bg-white" width="15" height="17"><?php echo $dependents[2]['pwd_flag']=="Y"?"X":"";?></td>
				</tr>
				<tr><td height="3"></td></tr>
			</table>
		</td>
	</tr>
	<tr>
		<td class="td-border-thick-left">
			<table class="align-c" width="100%">
				<tr><td height="3"></td></tr>
				<tr>
					<td><b>16A</b> &#9656;<?php echo nbs(3);?></td>
					<td class="border-light td-center-middle bg-white" width="100" height="17"><?php echo $dependents[3]['ln'];?></td>
				</tr>
				<tr><td height="3"></td></tr>
			</table>
		</td>
		<td>
			<table class="align-c" width="100%">
				<tr><td height="3"></td></tr>
				<tr>
					<td><b>16B</b> &#9656;<?php echo nbs(3);?></td>
					<td class="border-light td-center-middle bg-white" width="100" height="17"><?php echo $dependents[3]['fn'];?></td>
				</tr>
				<tr><td height="3"></td></tr>
			</table>
		</td>
		<td>
			<table class="align-c" width="100%">
				<tr><td height="3"></td></tr>
				<tr>
					<td><b>16C</b> &#9656;<?php echo nbs(3);?></td>
					<td class="border-light td-center-middle bg-white" width="100" height="17"><?php echo $dependents[3]['mn'];?></td>
				</tr>
				<tr><td height="3"></td></tr>
			</table>
		</td>
		<td align="center">
			<table class="align-c">
				<tr><td height="3"></td></tr>
				<tr>
					<td><b>16D</b> &#9656;<?php echo nbs(3);?></td>
					<td class="td-border-light-top td-border-light-bottom td-border-light-left td-center-middle bg-white" width="15" height="17"><?php echo $dependents[3]['birth_date'][0];?></td>
					<td class="td-border-light-top td-border-light-bottom td-center-middle bg-white" width="15"><?php echo $dependents[3]['birth_date'][1];?></td>
					<td class="td-border-light-left td-border-light-top td-border-light-bottom td-center-middle bg-white" width="15"><?php echo $dependents[3]['birth_date'][2];?></td>
					<td class="td-border-light-top td-border-light-bottom td-center-middle bg-white" width="15"><?php echo $dependents[3]['birth_date'][3];?></td>
					<td class="td-border-light-left td-border-light-top td-border-light-bottom td-center-middle bg-white" width="15"><?php echo $dependents[3]['birth_date'][4];?></td>
					<td class="td-border-light-top td-border-light-bottom td-center-middle bg-white" width="15"><?php echo $dependents[3]['birth_date'][5];?></td>
					<td class="td-border-light-top td-border-light-bottom td-center-middle bg-white" width="15"><?php echo $dependents[3]['birth_date'][6];?></td>
					<td class="td-border-light-top td-border-light-bottom td-border-light-right td-center-middle bg-white" width="15"><?php echo $dependents[3]['birth_date'][7];?></td>
				</tr>
			</table>
		</td>
		<td class="td-border-thick-right align-c">
			<table class="align-c" width="100%">
				<tr><td height="3"></td></tr>
				<tr>
					<td><b>16E</b> &#9656;<?php echo nbs(3);?></td>
					<td class="border-light td-center-middle bg-white" width="15" height="17"><?php echo $dependents[3]['pwd_flag']=="Y"?"X":"";?></td>
				</tr>
				<tr><td height="3"></td></tr>
			</table>
		</td>
	</tr>
</table>
<table width="100%" style="background-color: #BFBFBF">
	<tr>
		<td class="td-border-thick-top td-border-bottom td-border-thick-left" width="25%"><b>Part IV</b></td>
		<td class="td-border-thick-top td-border-bottom td-border-thick-right" width="75%"><b>For Employee With Two or More Employers (Multiple Employments) Within the Calendar Year</b></td>
	</tr>
	<tr>
		<td class="td-border-thick-left td-border-thick-right" colspan="2">
			<b>17 &#9656;</b> Type of multiple employments
			<table>
				<tr>
					<td rowspan="3"><?php echo nbs(10);?></td>
					<td class="border-light bg-white align-r" width="15" height="10"></td>
					<td><?php echo nbs(3)?>Successive employments</td>
				</tr>
				<tr>
					<td class="border-light bg-white align-r" width="15" height="10"></td>
					<td><?php echo nbs(3)?>Concurrent employments</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td class="td-border-thick-left td-border-thick-right" colspan="2"><?php echo nbs(5);?>(If successive, enter previous employer(s); if concurrent, enter main employer)</td>
	</tr>
	<tr>
		<td class="td-border-thick-left td-border-thick-right align-c" colspan="2">Previous and Concurrent Employments During the Calendar Year</td>
	</tr>
	<tr>
		<td class="td-border-thick-left align-c">TIN</td>
		<td class="td-border-thick-right align-c">Name of Employers</td>
	</tr>
	<tr>
		<td class="td-border-thick-left">
			<table>
				<tr>
					<td><?php echo nbs(10);?>&#9656;<?php echo nbs(5);?></td>
					<td class="f-size-8 td-border-light-left td-border-light-top td-border-light-bottom bg-white align-c" width="15" height="17"><?php echo $prev_employer_tin['other_deduction_detail_value'][0]?></td>
					<td class="f-size-8 td-border-light-left td-border-light-top td-border-light-bottom bg-white align-c" width="15"><?php echo $prev_employer_tin['other_deduction_detail_value'][1]?></td>
					<td class="f-size-8 td-border-light-left td-border-light-top td-border-light-bottom bg-white align-c" width="15"><?php echo $prev_employer_tin['other_deduction_detail_value'][2]?></td>
					<td class="f-size-8 td-border-light-left td-border-light-top td-border-light-bottom" width="15"></td>
					<td class="f-size-8 td-border-light-left td-border-light-top td-border-light-bottom bg-white align-c" width="15"><?php echo $prev_employer_tin['other_deduction_detail_value'][3]?></td>
					<td class="f-size-8 td-border-light-left td-border-light-top td-border-light-bottom bg-white align-c" width="15"><?php echo $prev_employer_tin['other_deduction_detail_value'][4]?></td>
					<td class="f-size-8 td-border-light-left td-border-light-top td-border-light-bottom bg-white align-c" width="15"><?php echo $prev_employer_tin['other_deduction_detail_value'][5]?></td>
					<td class="f-size-8 td-border-light-left td-border-light-top td-border-light-bottom" width="15"></td>
					<td class="f-size-8 td-border-light-left td-border-light-top td-border-light-bottom bg-white align-c" width="15"><?php echo $prev_employer_tin['other_deduction_detail_value'][6]?></td>
					<td class="f-size-8 td-border-light-left td-border-light-top td-border-light-bottom bg-white align-c" width="15"><?php echo $prev_employer_tin['other_deduction_detail_value'][7]?></td>
					<td class="f-size-8 td-border-light-left td-border-light-top td-border-light-bottom bg-white align-c" width="15"><?php echo $prev_employer_tin['other_deduction_detail_value'][8]?></td>
					<td class="f-size-8 td-border-light-left td-border-light-top td-border-light-bottom" width="15"></td>
					<td class="f-size-8 td-border-light-left td-border-light-top td-border-light-bottom bg-white align-c" width="15"><?php echo $prev_employer_tin['other_deduction_detail_value'][9]?></td>
					<td class="f-size-8 td-border-light-left td-border-light-top td-border-light-bottom bg-white align-c" width="15"><?php echo $prev_employer_tin['other_deduction_detail_value'][10]?></td>
					<td class="f-size-8 td-border-light-left td-border-light-top td-border-light-bottom bg-white align-c" width="15"><?php echo $prev_employer_tin['other_deduction_detail_value'][11]?></td>
					<td class="f-size-8 border-light td-border-light-bottom bg-white align-c" width="15"><?php echo $prev_employer_tin['other_deduction_detail_value'][12]?></td>
				</tr>
				<tr><td height="3"></td></tr>
			</table>
		</td>
		<td class="td-border-thick-right align-c">
			<table>
				<tr>
					<td><?php echo nbs(10);?>&#9656;<?php echo nbs(5);?></td>
					<td class="border-light td-center-middle bg-white" width="340" height="17"><?php echo $prev_employer_name['other_deduction_detail_value'];?></td>
				</tr>
				<tr><td height="3"></td></tr>
			</table>
		</td>
	</tr>
	<tr>
		<td class="td-border-thick-left">
			<table>
				<tr>
					<td><?php echo nbs(10);?>&#9656;<?php echo nbs(5);?></td>
					<td class="border-light td-center-middle bg-white" width="15" height="17"></td>
					<td class="border-light td-center-middle bg-white" width="15"></td>
					<td class="border-light td-center-middle bg-white" width="15"></td>
					<td class="border-light td-center-middle bg-gray" width="15"></td>
					<td class="border-light td-center-middle bg-white" width="15"></td>
					<td class="border-light td-center-middle bg-white" width="15"></td>
					<td class="border-light td-center-middle bg-white" width="15"></td>
					<td class="border-light td-center-middle bg-gray" width="15"></td>
					<td class="border-light td-center-middle bg-white" width="15"></td>
					<td class="border-light td-center-middle bg-white" width="15"></td>
					<td class="border-light td-center-middle bg-white" width="15"></td>
					<td class="border-light td-center-middle bg-gray" width="15"></td>
					<td class="border-light td-center-middle bg-white" width="15"></td>
					<td class="border-light td-center-middle bg-white" width="15"></td>
					<td class="border-light td-center-middle bg-white" width="15"></td>
					<td class="border-light td-center-middle bg-white" width="15"></td>
				</tr>
				<tr><td height="3"></td></tr>
			</table>
		</td>
		<td class="td-border-thick-right align-c">
			<table>
				<tr>
					<td><?php echo nbs(10);?>&#9656;<?php echo nbs(5);?></td>
					<td class="border-light td-center-middle bg-white" width="340" height="17"></td>
				</tr>
				<tr><td height="3"></td></tr>
			</table>
		</td>
	</tr>
	<tr>
		<td class="td-border-thick-bottom td-border-thick-left">
			<table>
				<tr>
					<td><?php echo nbs(10);?>&#9656;<?php echo nbs(5);?></td>
					<td class="border-light td-center-middle bg-white" width="15" height="17"></td>
					<td class="border-light td-center-middle bg-white" width="15"></td>
					<td class="border-light td-center-middle bg-white" width="15"></td>
					<td class="border-light td-center-middle bg-gray" width="15"></td>
					<td class="border-light td-center-middle bg-white" width="15"></td>
					<td class="border-light td-center-middle bg-white" width="15"></td>
					<td class="border-light td-center-middle bg-white" width="15"></td>
					<td class="border-light td-center-middle bg-gray" width="15"></td>
					<td class="border-light td-center-middle bg-white" width="15"></td>
					<td class="border-light td-center-middle bg-white" width="15"></td>
					<td class="border-light td-center-middle bg-white" width="15"></td>
					<td class="border-light td-center-middle bg-gray" width="15"></td>
					<td class="border-light td-center-middle bg-white" width="15"></td>
					<td class="border-light td-center-middle bg-white" width="15"></td>
					<td class="border-light td-center-middle bg-white" width="15"></td>
					<td class="border-light td-center-middle bg-white" width="15"></td>
				</tr>
				<tr><td height="3"></td></tr>
			</table>
		</td>
		<td class="td-border-thick-bottom td-border-thick-right align-c">
			<table>
				<tr>
					<td><?php echo nbs(10);?>&#9656;<?php echo nbs(5);?></td>
					<td class="border-light td-center-middle bg-white" width="340" height="17"></td>
				</tr>
				<tr><td height="3"></td></tr>
			</table>
		</td>
	</tr>
</table>
<table width="100%" style="background-color: #BFBFBF">
	<tr>
		<td class="td-border-thick-left td-border-thick-right" colspan="2"><b>Part V</b><?php echo nbs(100)?><b>Employer Information</b></td>
	</tr>
	<tr>
		<td class="td-border-bottom td-border-thick-left td-border-thick-right align-c" colspan="2"><?php echo nbs(15)?>(If self-employed, please do not accomplish this part)</td>
	</tr>
	<tr>
		<td class="td-border-bottom td-border-thick-left td-border-right" width="60%">
			<b>18</b> TIN
			<table>
				<tr><td height="3"></td></tr>
				<tr>
					<td><?php echo nbs(20);?>&#9656;<?php echo nbs(5);?></td>
					<td class="border-light td-center-middle bg-white" width="15" height="17"><?php echo $doh_tin[0];?></td>
					<td class="border-light td-center-middle bg-white" width="15"><?php echo $doh_tin[1];?></td>
					<td class="border-light td-center-middle bg-white" width="15"><?php echo $doh_tin[2];?></td>
					<td class="border-light td-center-middle bg-gray" width="15"></td>
					<td class="border-light td-center-middle bg-white" width="15"><?php echo $doh_tin[3];?></td>
					<td class="border-light td-center-middle bg-white" width="15"><?php echo $doh_tin[4];?></td>
					<td class="border-light td-center-middle bg-white" width="15"><?php echo $doh_tin[5];?></td>
					<td class="border-light td-center-middle bg-gray" width="15"></td>
					<td class="border-light td-center-middle bg-white" width="15"><?php echo $doh_tin[6];?></td>
					<td class="border-light td-center-middle bg-white" width="15"><?php echo $doh_tin[7];?></td>
					<td class="border-light td-center-middle bg-white" width="15"><?php echo $doh_tin[8];?></td>
					<td class="border-light td-center-middle bg-gray" width="15"></td>
					<td class="border-light td-center-middle bg-white" width="15"><?php echo $doh_tin[9];?></td>
					<td class="border-light td-center-middle bg-white" width="15"><?php echo $doh_tin[10];?></td>
					<td class="border-light td-center-middle bg-white" width="15"><?php echo $doh_tin[11];?></td>
					<td class="border-light td-center-middle bg-white" width="15"><?php echo $doh_tin[12];?></td>
				</tr>
				<tr><td height="3"></td></tr>
			</table>
		</td>
		<td class="td-border-bottom td-border-thick-right">
			<b>19</b>&nbsp;RDO Code
			<table align="center">
				<tr>
					<td>&#9656;<?php echo nbs(5)?></td>
					<td class="border-light td-center-middle bg-white" width="15" height="17"><?php echo $doh_rdo_code[0];?></td>
					<td class="border-light td-center-middle bg-white" width="15"><?php echo $doh_rdo_code[1];?></td>
					<td class="border-light td-center-middle bg-white" width="15"><?php echo $doh_rdo_code[2];?></td>
				</tr>
				<tr><td height="3"></td></tr>
			</table>
		</td>
	</tr>
	<tr>
		<td class="td-border-bottom td-border-thick-left td-border-thick-right" colspan="2">
			<b>20</b> Employer's Name ( For Non-Individuals)
			<table>
				<tr>
					<td><?php echo nbs(20)?>&#9656;</td>
					<td class="td-border-3 bg-white" width="600" height="17">Department of Health</td>
				</tr>
				<tr><td height="3"></td></tr>
			</table>
		</td>
	</tr>
	<tr>
		<td class="td-border-bottom td-border-thick-left td-border-thick-right" colspan="2">
			<b>21</b> Employer's Name (For-Individuals) (Last Name, First Name, Middle Name)
			<table>
				<tr>
					<td><?php echo nbs(20)?>&#9656;</td>
					<td class="td-border-3 bg-white" width="200" height="17"></td>
					<td class="td-border-tb bg-white" width="200"></td>
					<td class="td-border-tb td-border-right bg-white" width="200"></td>
				</tr>
				<tr><td height="3"></td></tr>
			</table>
		</td>
	</tr>
	<tr>
		<td class="td-border-thick-left td-border-thick-right" colspan="2">
			<b>22</b> Registered Address
			<table>
				<tr>
					<td><?php echo nbs(20)?>&#9656;</td>
					<td class="td-border-3 bg-white align-c" width="200" height="17"><?php echo $doh_building;?></td>
					<td class="td-border-tb bg-white align-c" width="200"><?php echo nbs(10) . $doh_street . nbs(15) . $doh_subdivision;?></td>
					<td class="td-border-tb td-border-right bg-white align-c" width="200"><?php echo $doh_barangay;?></td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td class="align-c">No. (Include  Building Name)</td>
					<td class="align-c">Street <?php echo nbs(25)?>Subdivision</td>
					<td class="align-c">Barangay</td>
				</tr>
				<tr><td height="5" colspan="4"></td></tr>
			</table>
		</td>
	</tr>
	<tr>
		<td class="td-border-bottom td-border-thick-left td-border-thick-right" colspan="2">
			<table>
				<tr>
					<td><?php echo nbs(20)?>&#9656;</td>
					<td class="td-border-3 bg-white align-c" width="200" height="17"><?php echo $doh_municity;?></td>
					<td class="td-border-tb bg-white align-c" width="200"></td>
					<td class="td-border-tb td-border-right bg-white align-c" width="200"><?php echo $doh_zip_code;?></td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td class="align-c">District/Municipality</td>
					<td class="align-c">City/Province</td>
					<td class="align-c">Zip Code</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<table width="100%">
	<tr>
		<td class="td-border-thick-bottom td-border-thick-left td-border-thick-right" style="background-color: #BFBFBF" colspan="2" width="75%">
			<b>23</b> Date of Certification
			<table align="center">
				<tr>
					<td><?php echo nbs(20)?>&#9656;<?php echo nbs(5)?></td>
					<td class="border-light td-center-middle bg-white" width="15" height="17"><?php echo $payer_info['tin'][0];?></td>
					<td class="border-light td-center-middle bg-white" width="15"><?php echo $payer_info['tin'][1];?></td>
					<td class="border-light td-center-middle bg-white" width="15"><?php echo $payer_info['tin'][2];?></td>
					<td class="border-light td-center-middle bg-white" width="15"><?php echo $payer_info['tin'][1];?></td>
					<td class="border-light td-center-middle bg-white" width="15"><?php echo $payer_info['tin'][2];?></td>
					<td class="border-light td-center-middle bg-white" width="15"><?php echo $payer_info['tin'][2];?></td>
					<td class="border-light td-center-middle bg-white" width="15"><?php echo $payer_info['tin'][2];?></td>
					<td class="border-light td-center-middle bg-white" width="15"><?php echo $payer_info['tin'][2];?></td>
				</tr>
				<tr><td colspan="7" class="align-r">(MM/DD/YYYY)</td></tr>
			</table>
		</td>
		<td class="td-border-thick-right align-c" width="25%">
			Stamp of Receiving Office and Date of Receipt
		</td>
	</tr>
	<tr>
		<td class="td-border-thick-left td-border-thick-right align-j" colspan="2">
		<?php echo nbs(8);?>I declare, under the penalties of perjury, that this certificate has been made in good faith, verified by me andto the best of my knowledge and belief, is true and correct, pursuant to the provisions of the National Internal Revenue Code, as amended, and the regulations issued under authority thereof.
		</td>
		<td class="td-border-thick-right align-c"></td>
	</tr>
	<tr>
		<td class="td-border-thick-left align-c">24 ________________________________________</td>
		<td class="td-border-thick-right align-c">25 ________________________________________</td>
		<td class="td-border-thick-right align-c"></td>
	</tr>
	<tr>
		<td class="td-border-thick-bottom td-border-thick-left align-c">Employer/Authorized Agent Signature</td>
		<td class="td-border-thick-bottom td-border-thick-right align-c">Title/Position of Signatory</td>
		<td class="td-border-thick-bottom td-border-thick-right align-c"></td>
	</tr>
</table>
</body>