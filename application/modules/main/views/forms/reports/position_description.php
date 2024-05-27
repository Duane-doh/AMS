<html>
<head>
	<title>Position Description</title>
	<link rel="stylesheet" href="<?php echo base_url().PATH_CSS ?>reports.css" type="text/css" />
</head>
<body>
<table class="table-max f-size-12">
	<tr>
		<td class="td-border-top td-border-right td-border-left padl-5" width="50%">REPUBLIC OF THE PHILIPPINES</td>
		<td class="td-border-top td-border-right padl-5" width="50%">1. NAME OF EMPLOYEE</td>
	</tr>	
	<tr>
		<td class="td-border-left padl-5">DC - CSC Form No. 1</td>
		<td class="td-border-right td-border-left">
			<table class="f-size-12">
				<tr>
					<td class="align-c bold" width="150"><?php echo $info['ln']?></td>
					<td class="align-c bold" width="150"><?php echo $info['fn']?></td>
					<td class="align-c bold" width="20"><?php echo $info['mi']."."?></td>
				</tr>
			</table>
		</td>
	</tr>	
	<tr>
		<td class="td-border-left padl-5">[POSITION DESCRIPTION FORM]</td>
		<td class="td-border-right td-border-left">
			<table class="f-size-12">
				<tr>
					<td class="align-c" width="150">(Family Name)</td>
					<td class="align-c" width="150">(Given Name)</td>
					<td class="align-c" width="20">(M.I.)</td>
				</tr>
			</table>
		</td>
	</tr>	
	<tr>
		<td class="td-border-bottom td-border-right td-border-left" height="10">&nbsp;</td>
		<td class="td-border-bottom td-border-right" height="10">&nbsp;</td>
	</tr>
	<tr>
		<td class="td-border-right td-border-left padl-5">2. DEPARTMENT, CORPORATION OR AGENCY/LOCAL</td>
		<td class="td-border-right padl-5">3. BUREAU OR OFFICE</td>
	</tr>
	<tr>
		<td class="td-border-right td-border-left padl-5" valign="top"><?php echo nbs(5)?>GOVT.</td>
		<td class="td-border-right td-border-bottom  bold padl-5" valign="middle" rowspan="2"><?php echo nbs(5)?><?php if(EMPTY($parent_office)){ echo strtoupper($info['office']);} else { echo strtoupper($parent_office['name']);}?></td>
	</tr>
	<tr>
		<td class="td-border-bottom td-border-right td-border-left bold  padl-30" height="25">DEPARTMENT OF HEALTH</td>
	</tr>
	<tr>
		<td class="td-border-right td-border-left padl-5">4. DEPARTMENT/BRANCH/DIVISION</td>
		<td class="td-border-right padl-5">5. WORK STATION/PLACE OF WORK</td>
	</tr>
	<tr>		
		<td class="td-border-bottom td-border-right td-border-left bold padl-30" valign="middle" height="40"><?php echo ( ! EMPTY($parent_office)) ? $info['office'] : ''?></td>
		<td class="td-border-bottom td-border-right bold  padl-30">DOH CENTRAL OFFICE</td>
	</tr>
	<tr>
		<td class="td-border-bottom td-border-right td-border-left padl-5" valign="top" height="100" colspan="2">
			<table class="f-size-12 table-max">
				<tr>
					<td class="td-border-right" width="166" valign="top" height="100">6. PRES. APPROP. ACT/<br>BOARD<br>RESOLUTION/ORD. NO.<br><br>ITEM NO. <?php echo $info['plantilla_code']; ?></td>
					<td class="td-border-right padl-5" width="166" valign="top">PREV. APPROP. ACT/<br>BOARD<br>RESOLUTION/ORD. NO.<br><br>ITEM NO.</td>
					<td class="td-border-right padl-5" width="167" valign="top">7a. SALARY<br>AUTHORIZED<br>ACTUAL Php <?php echo number_format($info['salary'], 2)?> <br>plus other mo/.annual<?php echo nbs(5)?><br>benefits</td>
					<td class="td-border-right" width="167" class="padl-5" valign="top">7B. OTHER COMPENSATION<br><?php echo $other_compensations['compensations']?></td>		
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td class="td-border-right td-border-left padl-5">8. OFFICIAL, DESIGNATION OF POSITION</td>
		<td class="td-border-right padl-5">9. WORKING OR PROPOSED TITLE</td>
	</tr>
	<tr>
		<td class="td-border-bottom td-border-right td-border-left padl-30 bold" height="40" valign="middle"><?php echo strtoupper($position_description['position_designation'])?></td>
		<td class="td-border-bottom td-border-right padl-30 bold" valign="middle"><?php echo strtoupper($position_description['proposed_title']) ?></td>
	</tr>
	<tr>
		<td class="td-border-right td-border-left padl-5">10. OCPC CLASSIFICATION OF POSITION</td>
		<td class="td-border-right padl-5">11. OCCUPATIONAL GROUP TITLE (Leave Blank)</td>
	</tr>
	<tr>
		<td class="td-border-right td-border-left  padl-30 bold" height="40"><?php echo strtoupper($position_description['position_classification'])?></td>
		<td class="td-border-right padl-5">&nbsp;</td>
	</tr>
	<tr>
		<td class="border-thick2 padl-5" colspan="2">12. FOR LOCAL GOVERNMENT POSITION, CHECK GOVERNMENT UNIT AND UNIT CLASS
		<table class="f-size-12">
			<tr>
				<td colspan="4" height="10">&nbsp;</td>
			</tr>
			<tr>
				<td width="150"><?php echo nbs(5)?>[ ] MUNICIPALITY</td>
				<td width="150">[ ] 1st</td>
				<td width="150">[ ] 4th</td>
				<td width="150">[ ] 7th</td>
			</tr>
			<tr>
				<td><?php echo nbs(5)?>[ ] CITY</td>
				<td>[ ] 2nd</td>
				<td>[ ] 5th</td>
				<td></td>
			</tr>
			<tr>
				<td><?php echo nbs(5)?>[ ] PROVINCE</td>
				<td>[ ] 3rd</td>
				<td>[ ] 6th</td>
				<td></td>
			</tr>
		</table>
		</td>
	</tr>
	<tr>
		<td colspan="2" class="td-border-right td-border-left padl-5">13. STATEMENT OF DUTIES AND RESPONSIBILITIES. If more space is needed, please attach additional sheets.</td>
	</tr>
	<tr>
		<td colspan="2" class="td-border-right td-border-left padl-5" height="10">&nbsp;</td>
	</tr>
</table>
<div class="font-size-14-notice"><?php echo $info['duties']?></div>
<table class="table-max f-size-12" style="page-break-after:always">
	<tr>
		<td class=""></td>
	</tr>
</table>
	
<table class="table-max f-size-12">
	<tr>
		<td class="td-border-top td-border-right td-border-left padl-5" width="45%">14. POSITION TITLE OF IMMEDIATE SUPERVISOR</td>
		<td class="td-border-top td-border-right padl-5" width="55%">15. POSITION TITLE OF NEXT HIGHER SUPERVISOR</td>
	</tr>
	<tr>
		<td class="td-border-bottom td-border-right td-border-left bold  padl-30" height="40"><?php echo strtoupper($position_description['immediate_position'])?></td>
		<td class="td-border-bottom td-border-right bold padl-30"><?php echo strtoupper($position_description['next_higher_position'])?></td>
	</tr>
	<tr>
		<td colspan="2" class="td-border-right td-border-left padl-5">16. NAMES, TITLES AND ITEM NUMBERS OF THOSE WHO YOU DIRECTLY SUPERVISE. (If more than seven, list</td>
	</tr>
	<tr>		
		<td colspan="2" class="td-border-right td-border-left padl-5"><?php echo nbs(5)?>only by their item number and titles)</td>
	</tr>
	<tr>		
		<td colspan="2" class="td-border-bottom td-border-right td-border-left padl-30" height="30"><?php echo strtoupper($position_description['directly_supervised'])?></td>
	</tr>
	<tr>
		<td colspan="2" class="td-border-right td-border-left padl-5">17. MACHINES, EQUIPMENTS, TOOLS, ETC. USED REGULARLY IN PERFORMANCE OF WORK.</td>
	</tr>
	<tr>		
		<td colspan="2" class="td-border-bottom td-border-right td-border-left padl-30" height="40"><?php echo strtoupper($position_description['work_tools_used'])?></td>
	</tr>
	<tr>
		<td class="td-border-bottom td-border-right td-border-left padl-5">18. C O N T A C T S
		<table class="f-size-12">
			<tr>
				<td colspan="3">&nbsp;</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td class="align-c"><?php echo nbs(3)?>OCCASIONAL</td>
				<td class="align-c"><?php echo nbs(3)?>FREQUENT</td>
			</tr>
			<?php $check = '<span style="font-family: Arial, Helvetica, sans-serif">&#x2714;</span>' ;?>
			<tr>
				<td><?php echo nbs(3)?>GENERAL PUBLIC</td>
				<td class="align-c">[<?php echo (in_array("GP-O", $contacts)) ? $check : '&nbsp;&nbsp;&nbsp;' ;?>]</td>
				<td class="align-c">[<?php echo (in_array("GP-F", $contacts)) ? $check : '&nbsp;&nbsp;&nbsp;' ;?>]</td>
			</tr>
			<tr>
				<td><?php echo nbs(3)?>OTHER AGENCY</td>
				<td class="align-c">[<?php echo (in_array("OA-O", $contacts)) ? $check : '&nbsp;&nbsp;&nbsp;' ;?>]</td>
				<td class="align-c">[<?php echo (in_array("OA-F", $contacts)) ? $check : '&nbsp;&nbsp;&nbsp;' ;?>]</td>
			</tr>
			<tr>
				<td><?php echo nbs(3)?>SUPERVISORS</td>
				<td class="align-c">[<?php echo (in_array("S-O", $contacts)) ? $check : '&nbsp;&nbsp;&nbsp;' ;?>]</td>
				<td class="align-c">[<?php echo (in_array("S-F", $contacts)) ? $check : '&nbsp;&nbsp;&nbsp;' ;?>]</td>
			</tr>
			<tr>
				<td><?php echo nbs(3)?>MANAGEMENT</td>
				<td class="align-c">[<?php echo (in_array("M-O", $contacts)) ? $check : '&nbsp;&nbsp;&nbsp;' ;?>]</td>
				<td class="align-c">[<?php echo (in_array("M-F", $contacts)) ? $check : '&nbsp;&nbsp;&nbsp;' ;?>]</td>
			</tr>
			<tr>
				<td><?php echo nbs(3)?>OTHERS (Specify)</td>
				<td class="align-c">[<?php echo (in_array("O-O", $contacts)) ? $check : '&nbsp;&nbsp;&nbsp;' ;?>]</td>
				<td class="align-c">[<?php echo (in_array("O-F", $contacts)) ? $check : '&nbsp;&nbsp;&nbsp;' ;?>]</td>
			</tr>
		</table>
		</td>

		<td class="td-border-bottom td-border-right padl-5">19. WORKING CONDITION
			<table class="f-size-12">
			<tr>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td colspan="2">&nbsp;</td>
			</tr>
			<tr>
				<td><?php echo nbs(8)?>[<?php echo (in_array("NWC", $working_condition)) ? $check : '&nbsp;&nbsp;&nbsp;' ;?>]</td>
				<td><?php echo nbs(3)?>NORMAL WORKING CONDITION</td>
			</tr>
			<tr>
				<td><?php echo nbs(8)?>[<?php echo (in_array("FW", $working_condition)) ? $check : '&nbsp;&nbsp;&nbsp;' ;?>]</td>
				<td><?php echo nbs(3)?>FIELD WORK</td>
			</tr>
			<tr>
				<td><?php echo nbs(8)?>[<?php echo (in_array("FT", $working_condition)) ? $check : '&nbsp;&nbsp;&nbsp;' ;?>]</td>
				<td><?php echo nbs(3)?>FIELD TRIPS</td>
			</tr>
			<tr>
				<td><?php echo nbs(8)?>[<?php echo (in_array("EVWC", $working_condition)) ? $check : '&nbsp;&nbsp;&nbsp;' ;?>]</td>
				<td><?php echo nbs(3)?>EXPOSED TO VARIED WEATHER</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td><?php echo nbs(3)?>CONDITION</td>
			</tr>
			<tr>
				<td><?php echo nbs(8)?>[<?php echo (in_array("O", $working_condition)) ? $check : '&nbsp;&nbsp;&nbsp;' ;?>]</td>
				<td><?php echo nbs(3)?>OTHERS (Specify)</td>
			</tr>
		</table>
		</td>
		</td>
	</tr>
	<tr>
		<td class="td-border-right td-border-left padl-5" colspan="2">20. I CERTIFY THAT THE ABOVE ANSWERS ARE ADEQUATE AND COMPLETE</td>	
	</tr>
	<tr>
		<td colspan="2" class="td-border-right td-border-left padl-5">
			<table class="f-size-12">
				<tr>
					<td height="50" width="80"></td>
					<td class="td-border-bottom" width="170"></td>
					<td width="150"></td>
					<td class="td-border-bottom align-c bold" valign="bottom" width="170"><?php echo $info['fn'] . ' ' . $info['mi'] . '. ' . $info['ln']; ?></td>
				</tr>
				<tr>
					<td></td>
					<td width="170" class="align-c">( DATE )</td>
					<td width="150"></td>
					<td width="170" class="align-c">( SIGNATURE OF EMPLOYEE )</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td class="td-border-bottom td-border-left td-border-right td-left-bottom align-c"  height="20" colspan="2"><b><i> TO BE FILLED OUT BY IMMEDIATE SUPERVISOR.</i></b></td>
	</tr>
	<tr>
		<td class="td-border-right td-border-left padl-5" colspan="2">21. Describe briefly the general function of the Unit or Section.</td>
	</tr>
	<tr>
		<td class="td-border-right td-border-left bold padl-30" colspan="2" height="40" valign="middle"><?php echo nbs(15);?><?php echo $position_description['unit_general_function']?></td>
	</tr>

	<tr>
		<td class="td-border-top td-border-right td-border-left padl-5" colspan="2">22. DESCIBE BRIEFLY THE GENERAL FUNCTION OF THE POSITION.</i></b></td>
	</tr>
	<tr>
		<td class="td-border-right td-border-left bold padl-30" colspan="2"  height="40"><?php echo $info['plantilla_gen_function']?></td>
	</tr>
	<tr>
		<td class="td-border-top td-border-right td-border-left padl-5" colspan="2">23a. INDICATE THE REQUIRED QUALIFICATION BY YEARS AND KIND OF EDUCATION CONSIDERED IN </i></b></td>
	</tr>
	<tr>
		<td class="td-border-left td-border-right td-left-top" colspan="2"><?php echo nbs(13);?>FILLING UP A VACANCY TO THIS POINT. (KEEP THE POSITION IN MIND RATHER THAN THE </i></b></td>
	</tr>
	<tr>
		<td class="td-border-left td-border-right td-left-top" colspan="2"><?php echo nbs(13);?>QUALIFICATIONS OF THE PRESENT INCUMBENT. THIS ITEM SHOULD BE FILLED FOR ALL<br><?php echo nbs(13);?>POSITIONS OTHER THAN TEACHING)
		<table class="f-size-12">
			<tr>
				<td colspan="6" height="10">&nbsp;</td>
			</tr>
			<tr>
				<td><?php echo nbs(10)?>EDUCATION</td>
				<td>:</td>
				<td><?php echo !EMPTY($info['education']) ? $info['education'] : N_A;?></td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td width="110"><?php echo nbs(10)?>EXPERIENCE</td>
				<td width="20" align="left">:</td>
				<td><?php echo !EMPTY($info['experience']) ? $info['experience'] : N_A;?></td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
		</table>
		</td>
	</tr>
	<tr>
		<td class="td-border-top td-border-right td-border-left padl-5" colspan="2">23b. LICENCE OR CERTIFICATES REQUIRED TO DO THIS WORK, IF ANY.</i></b></td>
	</tr>
	<tr>		
		<td colspan="2" class="td-border-bottom td-border-right td-border-left padl-5" height="40"><?php echo nbs(10)?><?php echo $info['eligibility']?></td>
	</tr>
	<tr>
		<td class="td-border-right td-border-left padl-5" colspan="2">24. I HEREBY CERTIFY THAT ABOVE ANSWERS ARE ACCURATE AND COMPLETE.

		</td>
	</tr>
	<tr>
		<td class="td-border-left padl-5">&nbsp;</td>
		<td class="td-border-right padl-5">&nbsp;</td>
	</tr>
	<tr>
		<td class="td-border-left">&nbsp;</td>
		<td class="td-border-right align-c">&nbsp;</td>
	</tr>
	<tr>
		<td colspan="2" class="td-border-right td-border-left padl-5">
			<table class="f-size-12">
				<tr>
					<td height="30" width="80"></td>
					<td class="td-border-thick-bottom" width="170"></td>
					<td width="100"></td>
					<td class="td-border-thick-bottom align-c" valign="bottom" width="170"><b><?php echo $signatory_2['signatory_name']?></b><br><?php echo $signatory_2['position_name']?>, <?php echo $signatory_3['office_name']?></td>
				</tr>
				<tr>
					<td></td>
					<td width="170" class="align-c">( DATE )</td>
					<td width="100"></td>
					<td width="170" class="align-c" valign="bottom">(SIGNATURE AND TITLE OF IMMEDIATE SUPERVISOR)</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td class="td-border-top td-border-right td-border-left padl-5" colspan="2">25. A P P R O V E D:</td>
	</tr>
	<tr>
		<td class="td-border-left padl-5">&nbsp;</td>
		<td class="td-border-right align-c">&nbsp;</td>
	</tr>
	<tr>
		<td class="td-border-left padl-5">&nbsp;</td>
		<td class="td-border-right align-c">&nbsp;</td>
	</tr>
	<tr>
		<td colspan="2" class="td-border-right td-border-left td-border-bottom padl-5">
			<table class="f-size-12"> 
				<tr>
					<td height="20" width="80"></td>
					<td class="td-border-thick-bottom align-c" width="170"></td>
					<td width="100"></td>
					<td class="td-border-thick-bottom align-c" valign="bottom" width="170"><b><?php echo $signatory_3['signatory_name']?></b><br><?php echo $signatory_3['position_name']?>, <?php echo $signatory_3['office_name']?></td>
				</tr>
				<tr>
					<td></td>
					<td width="170" class="align-c">( DATE )</td>
					<td width="130"></td>
					<td width="170" class="align-c" valign="bottom">Head of Agency</td>
				</tr>
			</table>
		</td>
	</tr>
	
</table>

</body>
</html>
