
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<html>
<head>	
	<title>Application for Leave Form</title>
	<link rel="stylesheet" href="<?php echo base_url().PATH_CSS ?>reports.css" type="text/css" />
	<style type="text/css">
		table{
		
		font-size:9px;
		font-family: Arial, Times, serif;
	}
	</style>
</head>

<body>
<table class="table-max cont-5">
	<tbody>
		<tr>
			<td colspan="2" class="f-size f-size-8"><em><b>Civil Service Form No. 6<br>Revised 2020</b></em></td>
			<td colspan="2" class="f-size f-size-14" align="right"><b>ANNEX A</b></td>
		</tr>
	</tbody>
</table>
<table class="table-max cont-5">
	<tbody>
		<tr>
			<td align="right"><img src="<?php echo base_url() . PATH_IMAGES ?>doh_logo.png" width="100" height="100"></td>
			<td class="td-center-middle f-size-10" colspan="2"><center><b>Republic of the Philippines<br>Department of Health<br>San Lazaro Compound, Sta. Cruz, Manila</b></center></td>
			<td class="td-center-middle f-size-10"><center style="border: 1px solid #a9a9a9;">Stamp of Date of Receipt</center></td>
		</tr>
	</tbody>
</table>
<table class="table-max cont-5">
		<tr>
			<td class="td-center-middle f-size-14" colspan="4"><center><b>APPLICATION FOR LEAVE</b></center></td>
		</tr>
		<tr>
			<td class="td-border-3 valign-top" width="40%"><span>1. OFFICE/DEPARTMENT</span><br><?php echo isset($leave_detail['name']) ? $leave_detail['name']:""?></td>
			<td class="td-border-top td-border-bottom valign-top"><span>2. NAME (Last)</span><br><?php echo isset($leave_detail['last_name']) ? $leave_detail['last_name']:""?></td>
			<td class="td-border-top td-border-bottom valign-top"><span>(First)</span><br><?php echo isset($leave_detail['first_name']) ? $leave_detail['first_name']:""?><?php echo !EMPTY($leave_detail['ext_name']) ? ", ".$leave_detail['ext_name']:""?></td>
			<td class="td-border-right td-border-top td-border-bottom valign-top"><span>(Middle)</span><br><?php echo isset($leave_detail['middle_name']) ? $leave_detail['middle_name']:""?></td>
		</tr>
		<tr>
			<td class="td-border-1 td-border-left valign-middle" width="40%"><span>3. DATE OF FILING </span><span style="border-bottom: 1px solid;"><?php echo isset($leave_detail['date_of_filing']) ? $leave_detail['date_of_filing']:""?></span></td>
			<td class="td-border-top valign-middle" colspan="2"><span>4. POSITION </span><span style="border-bottom: 1px solid;"><?php echo isset($leave_detail['position_name']) ? $leave_detail['position_name']:""?></span></td>
			<td class="td-border-1 td-border-right valign-middle"><span>5. SALARY </span><span style="border-bottom: 1px solid;">Php <?php echo isset($leave_detail['amount']) ? $leave_detail['amount']:"0.00"?></span></td>
		</tr>
	</tbody>
</table>
<table class="table-max cont-5">
	<tbody>
		<tr>
			<td class="bold td-center-middle" colspan="2" style="border-top: 2px double; border-right: 1px solid; border-bottom: 2px double; border-left: 1px solid;">6. DETAILS OF APPLICATION</td>
		</tr>
		<tr>
			<td class="td-border-4" width="60%">6.A TYPE OF LEAVE TO BE AVAILED OF
				<table>
					<tr>
						<td></td>
						<td><?php echo (isset($leave_detail['leave_type_id']) and $leave_detail['leave_type_id'] == LEAVE_TYPE_VACATION) ? "&#9745;":"&#9633;"?> Vacation Leave <span style="font-size: 8px; color: #424242;">(Sec. 51, Rule XVI, Omnibus Rules Implementing E.O. No. 292)</span></td>
					</tr>
					<tr>
						<td></td>
						<td><?php echo (isset($leave_detail['leave_type_id']) and $leave_detail['leave_type_id'] == LEAVE_TYPE_FORCED) ? "&#9745;":"&#9633;"?> Mandatory/Forced Leave <span style="font-size: 8px; color: #424242;">(Sec. 25, Rule XVI, Omnibus Rules Implementing E.O. 292)</span></td>
					</tr>
					<tr>
						<td></td>
						<td><?php echo (isset($leave_detail['leave_type_id']) and $leave_detail['leave_type_id'] == LEAVE_TYPE_SICK) ? "&#9745;":"&#9633;"?> Sick Leave <span style="font-size: 8px; color: #424242;">(Sec. 43, Rule XVI, Omnibus Rules Implementing E.O. No. 292)</span></td>
					</tr>
					<tr>
						<td></td>
						<td><?php echo (isset($leave_detail['leave_type_id']) and $leave_detail['leave_type_id'] == LEAVE_TYPE_MATERNITY) ? "&#9745;":"&#9633;"?> Maternity Leave <span style="font-size: 8px; color: #424242;">(R.A. No. 11210 / IRR issued by CSC, DOLE and SSS)</span></td>
					</tr>
					<tr>
						<td></td>
						<td><?php echo (isset($leave_detail['leave_type_id']) and $leave_detail['leave_type_id'] == LEAVE_TYPE_PATERNITY) ? "&#9745;":"&#9633;"?> Paternity Leave <span style="font-size: 8px; color: #424242;">(R.A. No. 8187 / CSC MC No. 71, s. 1998, as amended)</span></td>
					</tr>
					<tr>
						<td></td>
						<td><?php echo (isset($leave_detail['leave_type_id']) and $leave_detail['leave_type_id'] == LEAVE_TYPE_SPECIAL_PRIVILEGE) ? "&#9745;":"&#9633;"?> Special Privilege Leave <span style="font-size: 8px; color: #424242;">(Sec. 21, Rule XVI, Omnibus Rule Implementing E.O. No. 292)</span></td>
					</tr>
					<tr>
						<td></td>
						<td><?php echo (isset($leave_detail['leave_type_id']) and $leave_detail['leave_type_id'] == LEAVE_TYPE_SINGLE_PARENT) ? "&#9745;":"&#9633;"?> Solo Parent Leave <span style="font-size: 8px; color: #424242;">(R.A. No. 8972 / CSC MC No. 8, s. 2004)</span></td>
					</tr>
					<tr>
						<td></td>
						<td><?php echo (isset($leave_detail['leave_type_id']) and $leave_detail['leave_type_id'] == LEAVE_TYPE_STUDY) ? "&#9745;":"&#9633;"?> Study Leave <span style="font-size: 8px; color: #424242;">(Sec. 68, Rules XVI, Omnibus Rules Implementing E.O. No. 292)></span></td>
					</tr>
					<tr>
						<td></td>
						<td><?php echo (isset($leave_detail['leave_type_id']) and $leave_detail['leave_type_id'] == LEAVE_TYPE_VAWC) ? "&#9745;":"&#9633;"?> 10-Day VAWC Leave <span style="font-size: 8px; color: #424242;">(R.A. No. 9262 / CSC MC No. 15, s. 2005)</span></td>
					</tr>
					<tr>
						<td></td>
						<td><?php echo (isset($leave_detail['leave_type_id']) and $leave_detail['leave_type_id'] == LEAVE_TYPE_REHABILITATION_PRIVILEGE) ? "&#9745;":"&#9633;"?> Rehabilitation Privilege <span style="font-size: 8px; color: #424242;">(Sec. 55, Rule XVI, Omnibus Rules Implementing E.O. No. 292)</span></td>
					</tr>
					<tr>
						<td></td>
						<td><?php echo (isset($leave_detail['leave_type_id']) and $leave_detail['leave_type_id'] == LEAVE_TYPE_SPECIAL_BENEFITS_WOMEN) ? "&#9745;":"&#9633;"?> Special Leave Benefits for Women <span style="font-size: 8px; color: #424242;">(R.A. No. 9710 / CSC MC No. 25, s. 2010)</span></td>
					</tr>
					<tr>
						<td></td>
						<td><?php echo (isset($leave_detail['leave_type_id']) and $leave_detail['leave_type_id'] == LEAVE_TYPE_SPECIAL_EMERGENCY_CALAMITY) ? "&#9745;":"&#9633;"?> Special Emergency (Calamity) Leave <span style="font-size: 8px; color: #424242;">(CSC MC No. 2, s. 2012, as amended)</span></td>
					</tr>
					<tr>
						<td></td>
						<td><?php echo (isset($leave_detail['leave_type_id']) and $leave_detail['leave_type_id'] == LEAVE_TYPE_ADOPTION) ? "&#9745;":"&#9633;"?> Adoption Leave <span style="font-size: 8px; color: #424242;">(R.A. No. 8552)</span></td>
					</tr>
					<tr>
						<td></td>
						<td height="50">Others:
							<br>
							<br>
							<u>
								<?php
								if(isset($leave_detail['leave_type_id']))
								{
									if
									(
										$leave_detail['leave_type_id'] != LEAVE_TYPE_SICK && 
										$leave_detail['leave_type_id'] != LEAVE_TYPE_VACATION && 
										$leave_detail['leave_type_id'] != LEAVE_TYPE_MATERNITY && 
										$leave_detail['leave_type_id'] != LEAVE_TYPE_PATERNITY && 
										$leave_detail['leave_type_id'] != LEAVE_TYPE_STUDY && 
										$leave_detail['leave_type_id'] != LEAVE_TYPE_SINGLE_PARENT && 
										$leave_detail['leave_type_id'] != LEAVE_TYPE_FORCED && 
										$leave_detail['leave_type_id'] != LEAVE_TYPE_SPECIAL_PRIVILEGE && 
										$leave_detail['leave_type_id'] != LEAVE_TYPE_SPECIAL_EMERGENCY_CALAMITY && 
										$leave_detail['leave_type_id'] != LEAVE_TYPE_SPECIAL_BENEFITS_WOMEN && 
										$leave_detail['leave_type_id'] != LEAVE_TYPE_VAWC && 
										$leave_detail['leave_type_id'] != LEAVE_TYPE_REHABILITATION_PRIVILEGE && 
										$leave_detail['leave_type_id'] != LEAVE_TYPE_ADOPTION 
									)
									{
										echo nbs(8); 
										echo $leave_detail['leave_type_name'];
										echo nbs(8); 
									}
									else{
										echo nbs(50); 
									}
								}

								?>
							</u>
						</td>
					</tr>
				</table>
			</td>
			<td class="td-border-2">6.B DETAILS OF LEAVE
				<table>
					<tr>
						<td></td>
						<td><em>In case of Vacation/Special Privilege Leave:</em></td>
					</tr>
					<tr>
						<td></td>
						<td><?php echo (isset($leave_detail['leave_location']) and $leave_detail['leave_location'] == 'P') ? "&#9745;":"&#9633;"?> Within the Philippines <u><?php echo (isset($leave_detail['location_text']) and $leave_detail['leave_location'] == 'P') ? $leave_detail['location_text']:""?><?php echo nbs((isset($leave_detail['location_text']) and $leave_detail['leave_location'] == 'P') ? (41-strlen($leave_detail['location_text'])*2) : 41) ?></u></td>
					</tr>
					<tr>
						<td></td>
						<td><?php echo (isset($leave_detail['leave_location']) and $leave_detail['leave_location'] == 'A') ? "&#9745;":"&#9633;"?> Abroad (Specify) <u><?php echo (isset($leave_detail['location_text']) and $leave_detail['leave_location'] == 'A') ? $leave_detail['location_text']:""?><?php echo nbs((isset($leave_detail['location_text']) and $leave_detail['leave_location'] == 'A') ? (49-strlen($leave_detail['location_text'])*2) : 49) ?></u></td>
					</tr>
					<tr>
						<td></td>
						<td><em>In case of Sick Leave:</em></td>
					</tr>
					<tr>
						<td></td>
						<td><?php echo (isset($leave_detail['leave_location']) and $leave_detail['leave_location'] == 'H') ? "&#9745;":"&#9633;"?> In Hospital (Specify Illness) <u><?php echo (isset($leave_detail['location_text']) and $leave_detail['leave_location'] == 'H') ? $leave_detail['location_text']:""?><?php echo nbs((isset($leave_detail['location_text']) and $leave_detail['leave_location'] == 'H') ? (32-strlen($leave_detail['location_text'])*2) : 32) ?></u></td>
					</tr>
					<tr>
						<td></td>
						<td><?php echo (isset($leave_detail['leave_location']) and $leave_detail['leave_location'] == 'O') ? "&#9745;":"&#9633;"?> Out Patient (Specify Illness) <u><?php echo (isset($leave_detail['location_text']) and $leave_detail['leave_location'] == 'O') ? $leave_detail['location_text']:""?><?php echo nbs((isset($leave_detail['location_text']) and $leave_detail['leave_location'] == 'O') ? (31-strlen($leave_detail['location_text'])*2) : 31) ?></u></td>
					</tr>
					<tr>
						<td></td>
						<td><u><?php echo nbs(80) ?></u></td>
					</tr>
					<tr>
						<td></td>
						<td><em>In case of Special Leave Benefits for Women:</em></td>
					</tr>
					<tr>
						<td></td>
						<td>(Specify Illness) <u><?php echo $leave_detail['leave_type_id'] == LEAVE_TYPE_SPECIAL_BENEFITS_WOMEN ? $leave_detail['location_text']:""?><?php echo nbs($leave_detail['leave_type_id'] == LEAVE_TYPE_SPECIAL_BENEFITS_WOMEN ? (55-strlen($leave_detail['location_text'])*2) : 55) ?></u></td>
					</tr>
					<tr>
						<td></td>
						<td><u><?php echo nbs(81) ?></u></td>
					</tr>
					<tr>
						<td></td>
						<td><em>In case of Study Leave:</em></td>
					</tr>
					<tr>
						<td></td>
						<td><?php echo ($leave_detail['leave_type_id'] == 5 AND $leave_detail['study_type_id'] == 'M') ? "&#9745;":"&#9633;"?> Completion of Master's Degree</td>
					</tr>
					<tr>
						<td></td>
						<td><?php echo ($leave_detail['leave_type_id'] == 5 AND $leave_detail['study_type_id'] == 'B') ? "&#9745;":"&#9633;"?> BAR/Board Examination Review</td>
					</tr>
					<tr>
						<td></td>
						<td><em>Other purpose:</em></td>
					</tr>
					<tr>
						<td></td>
						<td>&#9633; Monetization of Leave Credits</td>
					</tr>
					<tr>
						<td></td>
						<td>&#9633; Terminal Leave</td>
					</tr>
				</table>	
			</td>
		</tr>
		<tr>
			<td class="td-border-left">6.C NUMBER OF WORKING DAYS APPLIED FOR
				<table>
					<tr>
						<td></td>
						<td></td>
						<td></td>
						<td class="td-border-bottom">
							<?php echo isset($leave_detail['no_of_days']) ? $leave_detail['no_of_days'] + $leave_detail['no_of_days_wop'] :""?><?php echo nbs(56) ?>
						</td>
					</tr>
					<tr>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td></td>
						<td></td>
						<td></td>
						<td>INCLUSIVE DATES</td>
					</tr>
					<tr>
						<td></td>
						<td></td>
						<td></td>
						<td class="td-border-bottom">
							<?php 
							$date_leave = $leave_detail['inclusive_date_from'];
							$date_leave .= ($leave_detail['inclusive_date_from'] == $leave_detail['inclusive_date_to']) ? "" : " - ".$leave_detail['inclusive_date_to'];
							echo $date_leave;
							?>
							<?php echo nbs(10) ?>
						</td>
					</tr>
					<tr>
						<td></td>
						<td></td>
						<td></td>
						<td height="20"></td>
					</tr>
				</table>
			</td>
			<td class="td-border-left td-border-right">6.D COMMUTATION
				<table>
					<tr>
						<td></td>
						<td>
							<?php echo (isset($leave_detail['commutation_flag']) and $leave_detail['commutation_flag'] == 'Y') ? "&#9745;":"&#9633;"?> Requested
							<br>
							<?php echo (isset($leave_detail['commutation_flag']) and $leave_detail['commutation_flag'] == 'N') ? "&#9745;":"&#9633;"?> Not Requested
						</td>
					</tr>
					<tr>
						<td></td>
						<td colspan="2" class="td-center-bottom" height="50"><u><?php echo nbs(80) ?></u><br>(Signature of Applicant)</td>
					</tr>
				</table>
			</td>
		</tr>
	</tbody>
</table>
<table class="table-max cont-5">
	<tbody>
		<tr>
			<td class="bold td-center-middle" colspan="3" style="border-top: 2px double; border-right: 1px solid; border-bottom: 2px double; border-left: 1px solid;">7. DETAILS OF ACTION ON APPLICATION</td>
		</tr>
		<tr>
			<td class="td-border-left td-border-right" width="60%">7.A CERTIFICATION OF LEAVE CREDITS
				<table>
					<tr>
						<td></td>
						<td></td>
						<td class="td-center-middle" colspan="3">As of <u><?php echo nbs(30) ?></u></td>
					</tr>
					<tr>
						<td></td>
						<td></td>
						<td class="td-border-4 td-center-middle" width="115" height="30"></td>
						<td class="td-border-4 td-center-middle" width="115">Vacation Leave</td>
						<td class="td-border-4 td-center-middle" width="115">Sick Leave</td>
					</tr>
					<tr>
						<td></td>
						<td></td>
						<td class="td-border-4 td-center-middle"><em>Total Earned</em></td>
						<td class="td-border-4 td-center-middle"></td>
						<td class="td-border-4 td-center-middle"></td>
					</tr>
					<tr>
						<td></td>
						<td></td>
						<td class="td-border-4 td-center-middle"><em>Less this application</em></td>
						<td class="td-border-4 td-center-middle"></td>
						<td class="td-border-4 td-center-middle"></td>
					</tr>
					<tr>
						<td></td>
						<td></td>
						<td class="td-border-4 td-center-middle"><em>Balance</em></td>
						<td class="td-border-4 td-center-middle"><?php echo isset($leave_detail['vacation_balance']) ? round($leave_detail['vacation_balance'],3)." Days":""?> </td>
						<td class="td-border-4 td-center-middle"><?php echo isset($leave_detail['sick_balance']) ? round($leave_detail['sick_balance'],3)." Days":""?> </td>
					</tr>
					<tr>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td height="20"></td>
					</tr>
					<tr>
						<td></td>
						<td></td>
						<td class="td-border-top td-center-middle" colspan="3">(Authorized Officer)</td>
					</tr>
				</table>
			</td>
			<td colspan="2" class="td-border-right">7.B RECOMMENDATION
				<table>
					<tr>
						<td></td>
						<td><?php echo (isset($leave_detail['approved_flag']) and $leave_detail['approved_flag'] == "TRUE") ? "&#9745;":"&#9633;"?> Approval</td>
					</tr>
					<tr>
						<td></td>
						<td><?php echo (isset($leave_detail['approved_flag']) and $leave_detail['approved_flag'] == "FALSE") ? "&#9745;":"&#9633;"?> Disapproval due to <u><?php echo ($leave_detail['remarks']) ? $leave_detail['remarks']:nbs(45)?></u>
						</td>
					</tr>
					<tr>
						<td></td>
						<td>&nbsp;&nbsp;&nbsp;&nbsp;<u><?php echo nbs(80)?></u></td>
					</tr>
					<tr>
						<td></td>
						<td>&nbsp;&nbsp;&nbsp;&nbsp;<u><?php echo nbs(80)?></u></td>
					</tr>
					<tr>
						<td></td>
						<td>&nbsp;&nbsp;&nbsp;&nbsp;<u><?php echo nbs(80)?></u></td>
					</tr>
					<tr>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td></td>
						<td></td>
						<td height="15"></td>
					</tr>
					<tr>
						<td class="td-center-middle" colspan="3"><u><?php echo nbs(80) ?></u><br>(Authorized Officer)</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td class="td-border-left" style="border-top: 2px double;">7.C APPROVED FOR:
				<table>
					<tr>
						<td></td>
						<td></td>
						<td></td>
						<td class="td-border-bottom"><?php echo (!EMPTY($leave_detail['no_of_days']) AND $leave_detail['no_of_days'] > 0 AND $leave_detail['approved_flag'] == "TRUE") ? $leave_detail['no_of_days'] :nbs(5)?></td>
						<td>days with pay</td>
					</tr>
					<tr>
						<td></td>
						<td></td>
						<td></td>
						<td class="td-border-bottom"><?php echo (!EMPTY($leave_detail['no_of_days_wop']) AND $leave_detail['no_of_days_wop'] > 0  AND $leave_detail['approved_flag'] == "TRUE")  ? $leave_detail['no_of_days_wop'] :nbs(5)?></td>
						<td>days without pay</td>
					</tr>
					<tr>
						<td></td>
						<td></td>
						<td></td>
						<td class="td-border-bottom"></td>
						<td>Others (Specify)</td>
					</tr>
				</table>
			</td>
			<td colspan="2" class="td-border-right" style="border-top: 2px double;">7.D DISAPPROVED DUE TO:
				<table>
					<tr>
						<td></td>
						<td></td>
						<td><u><?php echo ($leave_detail['remarks']) ? $leave_detail['remarks']:''?><?php echo nbs(80) ?></u></td>
					</tr>
					<tr>
						<td></td>
						<td></td>
						<td><u><?php echo nbs(80) ?></u></td>
					</tr>
					<tr>
						<td></td>
						<td></td>
						<td><u><?php echo nbs(80) ?></u></td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td class="td-border-left td-border-right" colspan="3" height="30"></td>
		</tr>
		<tr>
			<td class="td-border-left td-border-right td-border-bottom td-center-middle" colspan="3"><u><?php echo nbs(80) ?></u><br>(Authorized Official)</td>
		</tr>
	</tbody>
</table>
			<br></br>
<table class="table-max cont-5">
	<tbody>
		<tr>
			<td class="bold td-center-middle" colspan="4" style="border-top: 2px double; border-right: 1px solid; border-bottom: 2px double; border-left: 1px solid;">INSTRUCTIONS AND REQUIREMENTS</td>
		</tr>
		<tr><td></td></tr>
		<tr>
			<td width="51%" style="vertical-align:top;text-align: justify;" >Application for any type of leave shall be made on this Form and <u><b>to be  accomplished at least in duplicate</b></u> with documentary requirements, as</td>
			<td width="4%"></td>
			<td width="45%" style="vertical-align:top;text-align: justify;">TPO or PPO has been filed with the said office shall be sufficient to support the application for the ten-day leave; or 
			</td>
		</tr>
		<tr>
			<td width="51%" style="padding-top: -2px;vertical-align:top;text-align: justify;">folows</td>
			<td width="4%" style="padding-top: -2px;vertical-align:top;text-align: right;">d.</td>
			<td width="45%" style="padding-top: -2px;vertical-align:top;text-align: justify;">In the absence of the BPO/TPO/PPO or the certification, a police report specifying the details of the occurrence of violence on the 
			</td>
		</tr>
		</tbody>
</table>
<table class="table-max cont-5">
	<tbody>
		<tr>
			<td width="2%" style="vertical-align:top;"><b>1.</b></td>
			<td width="48%" style="vertical-align:top;"><b>Vacation leave</b>
			<br>
			<span  style="">It shall be filed five (5) days in advance, whenever possible, of the effective date of such leave.  Vacation leave within in the Philippines or 
			</span>
		</td>
			<td width="5%" style="vertical-align:top;"></td>
			<td width="45%" style="padding-top: -2px; vertical-align:top; text-align: justify;">
			victim and a medical certificate may be considered, at the discretion of the immediate supervisor of the woman employee concerned.  
			</td>
		</tr>
		<tr>
			<td width="2%" style="padding-top: -2px; vertical-align:top;"></td>
			<td width="48%" style="padding-top: -2px; vertical-align:top;text-align: justify;">abroad shall be indicated in the form for purposes of securing travel</td>
			<td width="5%" style="padding-top: -2px; vertical-align:top;"></td>
			<td width="45%" style="padding-top: -2px; vertical-align:top; text-align: justify;"></td>
		</tr>

		<tr>
			<td width="2%" style="padding-top: -2px; vertical-align:top;"></td>
			<td width="48%" style="padding-top: -2px;vertical-align:top;text-align: justify;">authority and completing clearance from money and work accountabilities.</td>
			<td width="5%" style="padding-top: -2px;vertical-align:top; text-align: center;"><b>10.</b></td>
			<td width="45%" style="padding-top: -2px; vertical-align:top; text-align: justify;"><b>Rehabilitation leave* – up to 6 months</b><br>
			<span>
			•	Application shall be made within one (1) week from the time of the accident except when a longer period is warranted.  
			</span>
		</td>
		</tr>

		<tr>
			<td width="2%" style="padding-top: -2px; vertical-align:top;"><b>2.</b></td>
			<td width="48%" style="padding-top: -2px;vertical-align:top;"><b><span>Mandatory/Forced leave</span></b><br>
			<span  style="text-align: justify;">Annual five-day vacation leave shall be forfeited if not taken during the year.  In case the scheduled leave has been cancelled in the exigency of the service by the head of agency, it shall no longer be deducted from the accumulated vacation leave.  Availment of one (1) day or more Vacation Leave (VL) shall be considered for complying the mandatory/forced leave subject to the conditions under Section 25, Rule XVI of the Omnibus Rules Implementing E.O. No. 292.
			</span>
			</td>
			<td width="5%" style="padding-top: -2px;vertical-align:top;"></td>
			<td width="45%" style="padding-top: -2px; vertical-align:top; text-align: justify;">
			<span>
			•	Letter request supported by relevant reports such as the police report, if any,
			<br> 
			•	Medical certificate on the nature of the injuries, the course of treatment involved, and the need to undergo rest, recuperation, and rehabilitation, as the case may be. 
			<br>
			•	Written concurrence of a government physician should be obtained relative to the recommendation for rehabilitation if the attending physician is a private practitioner, particularly on the duration of the period of rehabilitation.
 
			</span>
			</td>
		</tr>
		<tr>
			<td width="2%" style="padding-top: -2px; vertical-align:top;"><b>3.</b></td>
			<td width="48%" style="padding-top: -2px;vertical-align:top;"><b>Sick leave*</b><br>
			<span  style="text-align: justify;">•	It shall be filed immediately upon employee's return from such leave.
			<br>
			•	If filed in advance or exceeding five (5) days, application shall be accompanied by a <u>medical certificate</u>.  In case medical consultation was not availed of, an <u>affidavit</u> should be executed by an applicant.
			</span>
			</td>
			<td width="5%" style="padding-top: -2px;vertical-align:top;"><br> <b>11.</b></td>
			<td width="45%" style="padding-top: -2px; vertical-align:top; text-align: justify;"><br><b>Special leave benefits for women* – up to 2 months</b>
			<br>
			<span>
			•	The application may be filed in advance, that is, at least five (5) days prior to the scheduled date of the gynecological surgery that will be undergone by the employee.  In case of emergency, the application for special leave shall be filed immediately upon employee’s return but during confinement the agency shall be notified of said surgery.
			</span>
			</td>
		</tr>
		<tr>
			<td width="2%" style="padding-top: -2px; vertical-align:top;"><b>4.</b></td>
			<td width="48%" style="padding-top: -2px;vertical-align:top;"><b>Maternity leave* – 105 days</b><br>
			<span  style="text-align: justify;">•	Proof of pregnancy e.g. ultrasound, doctor’s certificate on the expected date of delivery
			<br>
			•	Accomplished Notice of Allocation of Maternity Leave Credits (CS Form No. 6a), if needed
			<br>
			•	Seconded female employees shall enjoy maternity leave with full pay in the recipient agency.
			</span>
			</td>
			<td width="5%" style="padding-top: -2px;vertical-align:top;"></td>
			<td width="45%" style="padding-top: -2px; vertical-align:top; text-align: justify;">•	The application shall be accompanied by a medical certificate filled out by the proper medical authorities, e.g. the attending surgeon accompanied by a clinical summary reflecting the gynecological disorder which shall be addressed or was addressed by the said surgery; the histopathological report; the operative technique used for the surgery; the duration of the surgery including the perioperative period (period of confinement around surgery); as well as the employees estimated period of</td>
		</tr>
		<tr>
			<td width="2%" style="padding-top: -2px; vertical-align:top;"><b>5.</b></td>
			<td width="48%" style="padding-top: -2px;vertical-align:top;"><b>Paternity leave – 7 days</b><br>
			<span  style="text-align: justify;">Proof of child’s delivery e.g. birth certificate, medical certificate and marriage contract</span>
			</td>
			<td width="5%" style="padding-top: -2px;vertical-align:top;"><br><br><b>12.</b></td>
			<td width="45%" style="padding-top: -2px; vertical-align:top; text-align: justify;">recuperation for the same.<br><br><b>Special Emergency (Calamity) leave – up to 5 days</b><br>•	The special emergency leave can be applied for a maximum of 								
			</td>
		</tr>
		<tr>
			<td width="2%" style="padding-top: -2px; vertical-align:top;"><b>6.</b></td>
			<td width="48%" style="padding-top: -2px;vertical-align:top;"><b>Special Privilege leave – 3 days</b><br>
			<span  style="text-align: justify;">It shall be filed/approved for at least one (1) week prior to availment, except on emergency cases.  Special privilege leave within the Philippines or abroad shall be indicated in the form for purposes of securing travel authority and completing clearance from money and work accountabilities.</span>
			</td>
			<td width="5%" style="padding-top: -2px;vertical-align:top;"></td>
			<td width="45%" style="padding-top: -2px; vertical-align:top; text-align: justify;">five (5) straight working days or staggered basis within thirty (30) days from the actual occurrence of the natural calamity/disaster. Said privilege shall be enjoyed once a year, not in every instance of calamity or disaster. <br>
			•	The head of office shall take full responsibility for the grant of special emergency leave and verification of the employee’s eligibility to be granted thereof.  Said verification shall include: </td>
		</tr>
		<tr>
			<td width="2%" style="padding-top: -2px; vertical-align:top;"><b>7.</b></td>
			<td width="48%" style="padding-top: -2px;vertical-align:top;"><b>Solo Parent leave – 7 days</b><br>
			<span  style="text-align: justify;">It shall be filed in advance or whenever possible five (5) days before going on such leave with updated Solo Parent Identification Card.</span>
			</td>
			<td width="5%" style="padding-top: -2px;vertical-align:top;"></td>
			<td width="45%" style="padding-top: -2px; vertical-align:top; text-align: justify;">validation of place of validation of place of residence based on latest available records of the affected employee; verification that the place of residence is covered in the declaration of calamity area by the proper government agency; and such other proofs as may be necessary.</td>
		</tr>

		<tr>
			<td width="2%" style="vertical-align:top;"><b>8.</b></td>
			<td width="48%" style="vertical-align:top;"><b>Study leave* – up to 6 months</b><br>
			<span  style="text-align: justify;">•	Shall meet the agency’s internal requirements, if any;
			<br>
			•	Contract between the agency head or authorized representative and the employee concerned. 
			</span>
			</td>
			<td width="5%" style="padding-top: -2px;vertical-align:top;"><br><b>13.</b></td>
			<td width="45%" style="padding-top: -2px; vertical-align:top; text-align: justify;"><br><b>Monetization of leave credits</b> <br>
			<span style="text-align: justify;">
			Application for monetization of fifty percent (50%) or more of the accumulated leave credits shall be accompanied by letter request to the head of the agency stating the valid and justifiable reasons.
			</span>
		</td>
		</tr>

		<tr>
			<td width="2%" style="vertical-align:top;"><b>9.</b></td>
			<td width="48%" style="vertical-align:top;"><b>VAWC leave – 10 days</b><br>
			<span  style="text-align: justify;">•	It shall be filed in advance or immediately upon the woman employee’s return from such leave. 
			<br> 
			•	It shall be accompanied by any of the following supporting documents:
			<br>
			<?php echo nbs(3); ?>a.	Barangay Protection Order (BPO) obtained from the barangay;

			</span>
			</td>
			<td width="5%" style="padding-top: -2px;vertical-align:top;"><br><b>14.</b></td>
			<td width="45%" style="padding-top: -2px; vertical-align:top; text-align: justify;"><br><b>Terminal leave* </b> <br>
			<span style="text-align: justify;">
			Proof of employee’s resignation or retirement or separation from the service.  
			</span>
		</td>
		</tr>

		<tr>
			<td width="2%" style="vertical-align:top;"></td>
			<td width="48%" style="padding-top: -2px; vertical-align:top;"><?php echo nbs(3); ?>b.	Temporary/Permanent Protection Order (TPO/PPO) obtained from <?php echo nbs(6); ?> the court;<br>
			<?php echo nbs(3); ?>c.	If the protection order is not yet issued by the barangay or the <?php echo nbs(6); ?>court, a certification issued by the Punong Barangay/Kagawad or  <?php echo nbs(6); ?>Prosecutor or the Clerk of Court that the application for the BPO, 
			</td>
			<td width="5%" style="padding-top: -2px;vertical-align:top;"><b>15.</b></td>
			<td width="45%" style="padding-top: -2px; vertical-align:top; text-align: justify;"><b>Adoption Leave </b> <br>
			<span style="text-align: justify;">
			•	Application for adoption leave shall be filed with an authenticated copy of the Pre-Adoptive Placement Authority issued by the Department of Social Welfare and Development (DSWD).
			</span>
		</td>
		</tr>
		
	</tbody>
</table>
<table class="table-max cont-5">
	<tbody>
		<tr>
			<td>
				<br><br><br><br><br><br>
				<u>
				<?php echo nbs(70); ?>
				</u>
				<br><br>
			</td>
		</tr>
		<tr>
			<td>
			* For leave of absence for thirty (30) calendar days or more and terminal leave, application shall be accompanied by a clearance from money, property and
  work-related accountabilities (pursuant to CSC Memorandum Circular No. 2, s. 1985).

			</td>
		</tr>
	</tbody>
</table>
</body>
</html>