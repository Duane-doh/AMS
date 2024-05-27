
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<html>
<head>
	<title>Employee Leave Card</title>
	 <link rel="stylesheet" href="<?php echo base_url().PATH_CSS ?>reports.css" type="text/css" />
</head>

<body>
<table class="f-size-12">
    <tbody>
        <tr>
            <td><?php echo nbs(10) ?><img src="<?php echo base_url().PATH_IMG ?>doh_logo.png" width=90 height=90></img></td>
            <td class="align-c f-size-12" width="410">Republic of the Philippines<br><span class="f-size-12">Department of Health</span><br><span class="f-size-10">San Lazaro Compound Sta. Cruz, Manila</span></td>                
            <td><br></td>
        </tr>
    </tbody>
</table>
</table>
<table class="center-85 f-size-12">
	<tbody>
		<tr>
			<td class="align-c"><b>EMPLOYEE'S LEAVE CARD</b></td>
		</tr>
	</tbody>
</table>
<br>
<table class="table-max" cellpadding="5">
	<tbody>
		<tr>
			<td class="td-left-bottom" width="100"><b>Name </b></td>
			<td class="td-left-bottom"> <?php echo isset($employee_info['fullname']) ? $employee_info['fullname']:""?></td>
			<td class="td-left-bottom" width="100"></td>
			<td class="td-left-bottom"><b>Office </b></td>
			<td class="td-left-bottom" colspan = "3"><?php echo isset($employee_info['office_name']) ? $employee_info['office_name']:""?></td>
		</tr>
		<tr>
			<td class="td-left-bottom"><b>Position </b></td>
			<td class="td-left-bottom"><?php echo isset($employee_info['position_name']) ? $employee_info['position_name']:""?></td>
			<td class="td-left-bottom" width="100"></td>
			<td class="td-left-bottom" width="50"><b>ETD</b> </td>
			<td class="td-left-bottom"><?php echo format_date($employment_date['employ_start_date'], 'm/d/Y') ?></td>
			<td class="td-left-bottom" width="50"><b>Status</b></td>
			<td class="td-left-bottom"><?php echo isset($employee_info['employment_status_name']) ? $employee_info['employment_status_name']:""?></td>
		</tr>
	</tbody>
</table>
<br>
<table class="table-max cont-5">
	<thead>
		<tr>
				<td class="td-border-3 align-c valign-mid" rowspan="2">PERIOD</td>
				<td class="td-border-3 align-c valign-mid" rowspan="2">PARTICULARS</td>
				<td class="td-border-3 align-c valign-mid" colspan="4">VACATION LEAVE</td>
				<td class="td-border-3 align-c valign-mid" colspan="4">SICK LEAVE</td>
				<td class="td-border-4 align-c valign-mid" rowspan="2">REMARKS</td>
			</tr>

			<tr>
				<td class="td-border-3 align-c valign-mid" >EARNED</td>
				<td class="td-border-3 align-c valign-mid" >ABS.<br>UND.<br>W/P<br></td>
				<td class="td-border-3 align-c valign-mid" >BALANCE</td>
				<td class="td-border-3 align-c valign-mid" >ABS.<br>UND.<br>WOP<br></td>
				<td class="td-border-3 align-c valign-mid" >EARNED</td>
				<td class="td-border-3 align-c valign-mid" >ABS.<br>UND.<br>W/P<br></td>
				<td class="td-border-3 align-c valign-mid" >BALANCE</td>
				<td class="td-border-3 align-c valign-mid" >ABS.<br>UND.<br>WOP<br></td>
			</tr>
			<tr>
				<td class="td-border-light-bottom td-border-light-left"><br></td>
				<td class="td-border-light-bottom td-border-light-left" colspan="3">BAL. BROUGHT FORWARD</td>
				<td class="td-border-light-bottom td-border-light-left"><br></td>
				<td class="td-border-light-bottom td-border-light-left"><br></td>
				<td class="td-border-light-bottom td-border-light-left"><br></td>
				<td class="td-border-light-bottom td-border-light-left"><br></td>
				<td class="td-border-light-bottom td-border-light-left"><br></td>
				<td class="td-border-light-bottom td-border-light-left"><br></td>
				<td class="td-border-light-bottom td-border-light-left td-border-light-right"><br></td>
			</tr>
	</thead>
	<tbody>
		
		<?php 
			$previous_year = "";
			$year_label    = "";
			foreach($leave_detail as $leave):	
				if(EMPTY($previous_year) OR $previous_year != $leave['period'])
				{
					$previous_year = $leave['period'];
					$year_label    = $leave['period'];
				}
				else
				{
					$year_label    = '';
				}
				?>
				<tr>
					
					<td class="td-border-light-bottom td-border-light-left"><?php echo !EMPTY($year_label) ? $year_label : '';?></td>
					<td class="td-border-light-bottom td-border-light-left"><?php echo !EMPTY($leave['particulars']) ? $leave['particulars']:$leave['particulars']?></td>
					<!--<td class="td-border-light-bottom td-border-light-left align-r"><?php //echo (isset($leave['vl_earned']) AND $leave['vl_earned'] > 0) ? $leave['vl_earned']:""?></td>-->
					
					<!---------------- MARVIN : START : REMOVE VL EARNED IF TRANSACTION TYPE IS INITIAL ---------------->
					<td class="td-border-light-bottom td-border-light-left align-r">
					<?php
						if($leave['leave_transaction_type_id'] != 1)
						{
							echo (isset($leave['vl_earned']) AND $leave['vl_earned'] > 0) ? $leave['vl_earned'] : "";						
						}
						else
						{
							echo "";
						}
					?>
					</td>
					<!---------------- MARVIN : END : REMOVE VL EARNED IF TRANSACTION TYPE IS INITIAL ---------------->
					
					<td class="td-border-light-bottom td-border-light-left align-r"><?php echo (isset($leave['vl_used']) AND $leave['vl_used'] > 0) ? $leave['vl_used']:""?></td>
					<td class="td-border-light-bottom td-border-light-left align-r"><?php echo (isset($leave['vl_balance']) AND ($leave['vl_earned'] > 0 OR $leave['vl_used'] > 0)) ? $leave['vl_balance']:""?></td>
					<td class="td-border-light-bottom td-border-light-left align-r"><?php echo (isset($leave['vl_undertime_wop']) AND $leave['vl_undertime_wop'] > 0) ? $leave['vl_undertime_wop']:""?></td>
					<!--<td class="td-border-light-bottom td-border-light-left align-r"><?php //echo (isset($leave['sl_earned']) AND $leave['sl_earned'] > 0) ? $leave['sl_earned']:""?></td>-->
					
					<!---------------- MARVIN : START : REMOVE SL EARNED IF TRANSACTION TYPE IS INITIAL ---------------->
					<td class="td-border-light-bottom td-border-light-left align-r">
					<?php
						if($leave['leave_transaction_type_id'] != 1)
						{
							echo (isset($leave['sl_earned']) AND $leave['sl_earned'] > 0) ? $leave['sl_earned'] : "";							
						}
						else
						{
							echo "";
						}
					?>
					</td>
					<!---------------- MARVIN : END : REMOVE SL EARNED IF TRANSACTION TYPE IS INITIAL ---------------->
					
					<td class="td-border-light-bottom td-border-light-left align-r"><?php echo (isset($leave['sl_used']) AND $leave['sl_used'] > 0) ? $leave['sl_used']:""?></td>
					<td class="td-border-light-bottom td-border-light-left align-r"><?php echo (isset($leave['sl_balance']) AND ($leave['sl_earned'] > 0 OR $leave['sl_used'] > 0)) ? $leave['sl_balance']:""?></td>
					<td class="td-border-light-bottom td-border-light-left align-r"><?php echo (isset($leave['sl_undertime_wop']) AND $leave['sl_undertime_wop'] > 0) ? $leave['sl_undertime_wop']:""?></td>
					<!--<td class="td-border-light-bottom td-border-light-left td-border-light-right"><?php //echo !EMPTY($leave['leave_type_name']) ? $leave['leave_type_name']:""?></td>-->
					
					<!----------------------------- MARVIN : START : SHOW REMARKS IF THERE IS NO LEAVE TYPE NAME ----------------------------->
										
					<td class="td-border-light-bottom td-border-light-left td-border-light-right">
					<?php
						echo !EMPTY($leave['leave_type_name']) ? $leave['leave_type_name'] : $leave['remarks'];
					?>
					</td>
				</tr>
					<!----------------------------- MARVIN : END : SHOW REMARKS IF THERE IS NO LEAVE TYPE NAME ----------------------------->
			

		<?php endforeach;?>
			<?php 
				$count = count($leave_detail);
				for($x= $count; $x <= 33;$x++):
			?>
				<tr>
					<td class="td-border-light-bottom td-border-light-left"><br></td>
					<td class="td-border-light-bottom td-border-light-left"><br></td>
					<td class="td-border-light-bottom td-border-light-left"><br></td>
					<td class="td-border-light-bottom td-border-light-left"><br></td>
					<td class="td-border-light-bottom td-border-light-left"><br></td>
					<td class="td-border-light-bottom td-border-light-left"><br></td>
					<td class="td-border-light-bottom td-border-light-left"><br></td>
					<td class="td-border-light-bottom td-border-light-left"><br></td>
					<td class="td-border-light-bottom td-border-light-left"><br></td>
					<td class="td-border-light-bottom td-border-light-left"><br></td>
					<td class="td-border-light-bottom td-border-light-left td-border-light-right"><br></td>
				</tr>
			<?php endfor;?>
	</tbody>
</table>
</body>
</html>