
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<html>
<head>
	<title></title>
	 <link rel="stylesheet" href="<?php echo base_url().PATH_CSS ?>reports.css" type="text/css" />
</head>

<body>
<!-- <table class="center-85 f-size-12">
	<tbody>
		<tr>
			<td class="align-c"><span class="f-size-10">Republic of the Philippines</span><br>DEPARTMENT OF HEALTH<br><span class="f-size-10">San Lazaro Compound,<br>Sta. Cruz, Manila</span></td>
		</tr>
		<tr>
			<td class="align-c"><br><b>EMPLOYEE'S LEAVE CARD </b></td>
		</tr>
	</tbody>
</table> -->
<table class="center-85 f-size-12">
		<tbody>
			<tr>
				<td><?php echo nbs(50) ?><img src="<?php echo base_url().PATH_IMG ?>doh_logo.png" width=61 height=61></img></td>
				<td class="align-c">Republic of the Philippines<br>Department of Health<br><b>OFFICE OF THE SECRETARY</b><br>Manila</td>
				<td></td>
			</tr>
			<tr>
				<td></td>
				<td class="align-c"><br><br><b>RAW BIOMETRIC LOGS</b></td>
				<td></td>
			</tr>
		</tbody>
</table>
<br>
<table class="table-max" cellpadding="5">
	<tbody>
		<tr>
			<td class="td-left-bottom"> 
				<?php //echo isset($emp_info['first_name']) ? $emp_info['first_name']:""?> <?php //echo isset($emp_info['last_name']) ? $emp_info['last_name']:""?>
				
				<?php 
				// ===================== jendaigo : start : change name format ============= //
					echo $emp_info['first_name'];
					echo ($emp_info['middle_name'] == 'NA' OR $emp_info['middle_name'] == 'N/A' OR $emp_info['middle_name'] == '/' OR $emp_info['middle_name'] == '-') ? "" : " ".substr($emp_info['middle_name'],0,1).".";
					echo " ".$emp_info['last_name'];
					echo isset($emp_info['ext_name']) ? " ".$emp_info['ext_name'] : "";
				// ===================== jendaigo : start : change name format ============= //
				?>
				
				<br><b><?php echo isset($emp_info['agency_employee_id']) ? $emp_info['agency_employee_id']:""?>
				<br><?php echo isset($date_from) ? $date_from:""?> - <?php echo isset($date_to) ? $date_to : ""?> </b>
			</td>
		</tr>
	</tbody>
</table>
<br>
<table class="table-max cont-5">
	<tbody>		
		<tr>
			<td class="td-border-light-bottom td-border-light-top td-border-light-left" width="25%"><b>Attendance Date</b></td>
			<td class="td-border-light-bottom td-border-light-top td-border-light-left td-border-light-right"><b>Time Logs</b></td>
		</tr>
		<?php if($time_logs):?>
			<?php foreach ($time_logs as  $value):?>
				<tr>
					<td class="td-border-light-bottom td-border-light-left"><?php echo isset($value['date']) ? format_date($value['date']):""?></td>
					<td class="td-border-light-bottom td-border-light-left td-border-light-right"><?php echo isset($value['time_log']) ? $value['time_log']:""?></td>
				</tr>
			<?php endforeach;?>
		<?php else:?>
			<tr>
				<td colspan='2' class="td-border-light-bottom td-border-light-left td-border-light-right">No Logs Found.</td>
			</tr>
		<?php endif;?>
	</tbody>
</table>
</body>
</html>