<?php 

$date = date_create('Y-m-d'); 

foreach($record AS $key => $r)
{
	$arr = explode(' ', $r['employment_status_name']);
	$record[$key]['employment_status_name'] = '';
	foreach($arr AS $a)
	{
		$record[$key]['employment_status_name'] .= $a[0];
	}
}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<html>
<head>
	
	<title>Service Record</title>
	<link rel="stylesheet" href="<?php echo base_url().PATH_CSS ?>reports.css" type="text/css" />
	
</head>
<body>
<table class="table-max">
	<thead>

		<tr>
			<!--<td colspan=10 height="50"><?php //date_default_timezone_set('Asia/Manila'); nbs(5) ;echo date('F d, Y h:i:s A') ?></td>-->
			<td colspan=11 height="50"><?php date_default_timezone_set('Asia/Manila'); nbs(5) ;echo date('F d, Y h:i:s A') ?></td>
		</tr>
		<tr>
			<!--<td colspan=10><b>ID No. <?php //echo $record[0]['agency_employee_id']?></b></td>-->
			<td colspan=11><b>ID No. <?php echo $record[0]['agency_employee_id']?></b></td>
		</tr>
		<tr>
			<!--<td colspan=10 align="center"><b>SERVICE RECORD</b></td>-->
			<td colspan=11 align="center"><b>SERVICE RECORD</b></td>
		</tr>
		<tr>
			<!--<td colspan=10 align="center"><b>(To be accomplished by Employer)</b></td>-->
			<td colspan=11 align="center"><b>(To be accomplished by Employer)</b></td>
		</tr>
		<tr>
			<td colspan=1 height="20"></td>
			<!--<td colspan=2></td>
			<td colspan=2></td>
			<td colspan=2></td>
			<td colspan=3 valign=bottom>(if married woman, give</td>-->
			
			<td colspan=3></td>
			<td colspan=3></td>
			<td colspan=3></td>
			<td colspan=1 valign=bottom>(if married woman, give</td>
		</tr>
		<tr>
			<td colspan=1 width="5"><b>NAME:</b></td>
			<!--<td colspan=2 width="160" class="td-border-bottom" align="center" valign=bottom><b><?php //echo ucwords($record[0]['last_name']); ?></b></td>
			<td colspan=2 width="160" class="td-border-bottom" align="center" valign=bottom><b><?php //echo ucwords($record[0]['first_name']); ?><?php //echo !EMPTY($record[0]['ext_name'])? ', '.ucwords($record[0]['ext_name']) : ''; ?></b></td>
			<td colspan=2 width="160" class="td-border-bottom" align="center" valign=bottom><b><?php //echo ucwords($record[0]['middle_name']); ?></b></td>
			<td colspan=3 width="145">also full maiden name.)</td>-->
			
			<td colspan=3 width="160" class="td-border-bottom" align="center" valign=bottom><b><?php echo ucwords($record[0]['last_name']); ?></b></td>
			<td colspan=3 width="160" class="td-border-bottom" align="center" valign=bottom><b><?php echo ucwords($record[0]['first_name']); ?><?php echo !EMPTY($record[0]['ext_name'])? ', '.ucwords($record[0]['ext_name']) : ''; ?></b></td>
			<td colspan=3 width="160" class="td-border-bottom" align="center" valign=bottom><b><?php echo ucwords($record[0]['middle_name']); ?></b></td>
			<td colspan=1 width="145">also full maiden name.)</td>
		</tr>
		<tr>
			<!--<td colspan=10 height="5"></td>-->
			<td colspan=11 height="5"></td>
		</tr>
		<tr>
			<td colspan=1 height="17"></td>
			<!--<td colspan=2 align="center" valign=top><b>(Surname)</b></td>
			<td colspan=2 align="center" valign=top><b>(Given Name)</b></td>
			<td colspan=2 align="center" valign=top><b>(Middle Name)</b></td>
			<td colspan=3>(Data herein should be checked<br>from birth or baptismal or some</td>-->
			
			<td colspan=3 align="center" valign=top><b>(Surname)</b></td>
			<td colspan=3 align="center" valign=top><b>(Given Name)</b></td>
			<td colspan=3 align="center" valign=top><b>(Middle Name)</b></td>
			<td colspan=1>(Data herein should be checked<br>from birth or baptismal or some</td>
		</tr>
		<tr>
			<td colspan=1 width="5"><b>BIRTH:</b></td>
			<td colspan=4 width="165" class="td-border-bottom" align="center" valign=bottom><b><?php echo $record[0]['birth_date'] ?></b></td>
			<td colspan=5 width="165" class="td-border-bottom" align="center" valign=bottom><b><?php echo ucwords($record[0]['birth_place']); ?></b></td>
			<!--<td colspan=3 width="130">other reliable documents.)</td>-->
			<td colspan=1 width="130">other reliable documents.)</td>
		</tr>
		<tr>
			<!--<td colspan=10 height="5"></td>-->
			<td colspan=11 height="5"></td>
		</tr>
		<tr>
			<td colspan=1 height="17"></td>
			<td colspan=4 align="center" valign=top><b>(Date)</b></td>
			<td colspan=5 align="center" valign=top><b>(Place)</b></td>
			<!--<td colspan=2></td>-->
			<td colspan=1></td>
		</tr>
		<tr>
			<!--<td colspan=10 height="10"></td>-->
			<td colspan=11 height="10"></td>
		</tr>
		<tr>
			<!--<td colspan=10 align="justify"><?php //echo nbs(5)?>This is to certify that the employee named herein above actually rendered services in this Office as shown by the service record below, each<br> line of which is supported by the appointment and other papers actually issued by this Office and approved by the authorities concerned:</p></td>-->
			<td colspan=11 align="justify"><?php echo nbs(5)?>This is to certify that the employee named herein above actually rendered services in this Office as shown by the service record below, each<br> line of which is supported by the appointment and other papers actually issued by this Office and approved by the authorities concerned:</p></td>
		</tr>
		<tr>
			<!--<td colspan=10 height="5"></td>-->
			<td colspan=11 height="5"></td>
		</tr>
		<tr>
			<td class="td-border-top td-border-left" colspan=2 height="21" align="center"><b>SERVICE<br>(Inclusive Dates)</b></td>
			<td class="td-border-top td-border-left" colspan=3 align="center"><b>RECORDS OF APPOINTMENT</b></td>
			<td class="td-border-top td-border-left" colspan=3 align="center"><b>OFFICE ENTITY / DIVISION</b></td>
			<td class="td-border-top td-border-left td-border-right" colspan=2 align="center"><b>SEPARATION</b></td>
			<td class="td-border-top td-border-left td-border-right td-border-bottom" colspan=1 rowspan=2 align="center"><b>REMARKS</b></td>
		</tr>
		<tr>
			<td width="60" class="td-border-top td-border-left td-border-bottom" height="21" align="center"><b>From</b></td>
			<td width="60" class="td-border-top td-border-left td-border-bottom" align="center"><b>To</b></td>
			<td class="td-border-top td-border-left td-border-bottom" align="center"><b>Designation</b></td>
			<td width="40" class="td-border-top td-border-left td-border-bottom" align="center"><b>Status</b></td>
			<td width="50" class="td-border-top td-border-left td-border-bottom" align="center"><b>Annual Salary</b></td>
			<td class="td-border-top td-border-left td-border-bottom" align="center"><b>Station/Place of Assignment (per Plantilla)</b></td>
			<td class="td-border-top td-border-left td-border-bottom" align="center"><b>Branch</b></td>
			<td width="50" class="td-border-top td-border-left td-border-bottom" align="center"><b>L/V ABS<br>W/O PAY</b></td>
			<td width="50" class="td-border-top td-border-left td-border-bottom" align="center"><b>Date</b></td>
			<td width="50" class="td-border-top td-border-left td-border-right td-border-bottom" align="center"><b>Cause</b></td>
		</tr>
	</thead>
	<tbody>
		<?php
		if($record):
			foreach($record AS $r)
			{
				$separation_dtls = '';
				$lwop = ! empty($r['service_lwop']) ? $r['service_lwop'] : '';
				$remarks = ! empty($r['remarks']) ? $r['remarks'] : '';

				if ( (strpos($remarks, 'VL') !== false) OR (strpos($remarks, 'SL') !== false) OR (strpos($remarks, 'SLWOP') !== false) )
				{
					$with_lwop = TRUE;
					$lwop_dtls = $remarks;
				}
				else
				{
					$with_lwop = FALSE;
					$lwop_dtls = $lwop;
				}

				if ( ! empty($r['separation_mode_name']) AND ! empty($r['employ_end_date']) )
				{
					$separate_print = TRUE;
					$separation_dtls = date('m/d/Y', strtotime($r['employ_end_date'] . ' +1 day')) . '<br>' . $r['separation_mode_name'];
				}
				else
				{
					$separate_print = FALSE;
					if ( empty($with_lwop) )
						$separation_dtls = $remarks;
				}

				echo '<tr>';
					echo '<td class="td-border-top td-border-left td-border-bottom" height="21" align="center" style="padding: 2px;">' . $r['employ_start_date'] . '</td>';
					echo '<td class="td-border-top td-border-left td-border-bottom" align="center" style="padding: 2px;">' . ((EMPTY($r['employ_end_date'])) ? 'PRESENT' : $r['employ_end_date']) . '</td>';
					echo '<td class="td-border-top td-border-left td-border-bottom" style="padding: 2px;">' . strtoupper($r['position_name']) . '</td>';
					echo '<td class="td-border-top td-border-left td-border-bottom" align="center" style="padding: 2px;">' . $r['employment_status_code'] . '</td>';
					echo '<td class="td-border-top td-border-left td-border-bottom" align="right" align="center" style="padding: 2px;">' . number_format($r['annual_salary'],2) . '</td>';
					echo '<td class="td-border-top td-border-left td-border-bottom" style="padding: 2px;">' . strtoupper($r['office_name']) . '</td>';
					echo '<td class="td-border-top td-border-left td-border-bottom" align="center" style="padding: 2px;">' . strtoupper($r['branch_name']) . '</td>';
					// echo '<td class="td-border-top td-border-left td-border-bottom" align="center" style="padding: 2px;">' . $lwop_dtls . '</td>';
					
					//MARVIN
					echo '<td class="td-border-top td-border-left td-border-bottom" align="center" style="padding: 2px;">' . $r['leave_wop'] . '</td>';

					//echo '<td class="td-border-top td-border-left td-border-right td-border-bottom" colspan="2"><table>';

					if($separate_print)
					{
						// echo '<td class="td-border-top td-border-left td-border-bottom" align="left" style="padding: 2px;">' . strtoupper($r['separation_mode_name']) . '</td><td class="td-border-top td-border-left td-border-bottom td-border-right" align="left" style="padding: 2px;">' . strtoupper($r['employ_end_date']) . '</td>';
						
						//MARVIN
						echo '<td class="td-border-top td-border-left td-border-bottom" align="left" style="padding: 2px;">' . date('m/d/Y', strtotime(strtoupper($r['employ_end_date']) . ' +1 day')) . '</td>
						
						<td class="td-border-top td-border-left td-border-bottom" align="left" style="padding: 2px;">' . strtoupper($r['separation_mode_name']) . '</td>
						
						<td class="td-border-top td-border-left td-border-bottom td-border-right" align="left" style="padding: 2px;">' . strtoupper($r['remarks']) . '</td>';
					}
					else
					{
						// echo '<td class="td-border-top td-border-left td-border-bottom" align="left" style="padding: 2px;" align="left"></td><td class="td-border-top td-border-left td-border-bottom" align="left" style="padding: 2px;" align="left"></td><td class="td-border-top td-border-left td-border-bottom td-border-right">' . strtoupper($separation_dtls) . '</td>';

						//MARVIN
						echo '<td class="td-border-top td-border-left td-border-bottom" align="left" style="padding: 2px;" align="left"></td>
						
						<td class="td-border-top td-border-left td-border-bottom" align="left" style="padding: 2px;" align="left"></td>
						
						<td class="td-border-top td-border-left td-border-bottom td-border-right">' . strtoupper(empty($separation_dtls) ? $r['remarks'] : $separation_dtls) . '</td>';
					}
				//echo '</table></td>';
				echo '</tr>';
			}

		else:?>
			<tr>
				<!--<td colspan=10 class="td-border-light-top td-border-light-bottom td-border-light-left td-border-light-right td-center-middle" height="30">No Records Found.</td>-->
				<td colspan=11 class="td-border-light-top td-border-light-bottom td-border-light-left td-border-light-right td-center-middle" height="30">No Records Found.</td>
			</tr>
		<?php endif;?>
		<tr>
			<!--<td colspan=10 height="5"></td>-->
			<td colspan=11 height="5"></td>
		</tr>
		
	</tbody>
</table>
<table class="table-max">
	<tr>
		<!--<td colspan=10 class="td-border-top td-border-left td-border-bottom td-border-right" height="50" align="center">&#60; &#60; N O T H I N G<?php //echo nbs(5);?>F O L L O W S &#62; &#62;<br><br>Note: Not Valid without the official seal of the Department of Health</td>-->
		<td colspan=11 class="td-border-top td-border-left td-border-bottom td-border-right" height="50" align="center">&#60; &#60; N O T H I N G<?php echo nbs(5);?>F O L L O W S &#62; &#62;<br><br>Note: Not Valid without the official seal of the Department of Health</td>
	</tr>
</table>
<table class="table-max" style="line-height:14px">
	<tr>
		<td height="20"></td>
	</tr>
	<tr>
		<td>
			<table>				
				<tr >
					<td colspan="2" class="td-left-bottom">Legend: Status <?php foreach($legend_status as $status): ?><?php echo $status?><?php endforeach;?> LWOP: [VL]-Vacational Leave; [SL]-Sick Leave.</td>
				</tr>
				<tr>
					<td height="30" valign="middle"><?php echo nbs(10)?><i>Note: All Leave Without Pay are already deducted from the Payroll</i></td>
				</tr>
				<tr>
					<td height="30"></td>
				</tr>
				<tr>
					<td width="350"></td>
					<td align="center">CERTIFIED CORRECT:<br>By the Authority of the Secretary of Health</td>
				</tr>
				<tr>
					<td height="20"></td>
				</tr>
				<tr>
					<td width="350">
						<table>
							<tr>
								<td width="50"></td>
								<td class="td-border-light-bottom align-c" width="105"><?php echo date('F d, Y')?></td>
								<td></td>
							</tr>
							<tr>
								<td></td>
								<td class="align-c">(Date)</td>
								<td></td>
							</tr>
						</table>
					</td>
					<td align="center"><b><?php echo nbs(5) . strtoupper($certified_by['signatory_name']) . nbs(5); ?></b><br><?php echo $certified_by['position_name'] ?><br> <?php echo $certified_by['office_name'] ?></td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<!-- ************************************************************************** -->
</body>

</html>
