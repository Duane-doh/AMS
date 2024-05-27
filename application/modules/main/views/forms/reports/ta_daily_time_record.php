
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<html>
<head>
	<title>Daily Time Record</title>
	 <link rel="stylesheet" href="<?php echo base_url().PATH_CSS ?>reports.css" type="text/css" />
</head>

<body>
	<?php if($dtrs):?>
	<?php foreach ($dtrs as $key => $dtr):?>
<table class="table-max">
	<tbody>
		<tr>
			<td style="padding-right:30px">
				<table class="table-max cont-5">
					<tbody>
						<!--add work schedule in dtr-->
						<tr>
							<td colspan="7" class="td-left-top f-size-7"><?php echo $work_schedule[$key]['work_schedule_name']; ?></td>
						</tr>
						<!--end-->
						<tr>
							<td colspan="7" class="td-left-top f-size-7">Civil Service Form No. 48</td>
						</tr>
						<tr>
							<td colspan="7" class="td-center-bottom f-size-14"><br><center><b>DAILY TIME RECORD</b></center></td>
						</tr>
						<tr>
							<td colspan="7" class="td-center-top f-size-7"><center>-----o0o-----</center></td>
						</tr>
						<tr>
							<td colspan="7" class="td-center-bottom td-border-bottom f-size-12"><b><br><center><?php echo isset($employee_name[$key]) ? $employee_name[$key]:""?></center></b></td>
						</tr>
						<tr>
							<td colspan="7" class="td-center-top"><center>(Name)</center><br><br></td>
						</tr>
						<tr>
							<td class="td-left-top f-size-12">From: </td>
							<td colspan="3" class="td-center-top f-size-12"><?php echo isset($date_from) ? $date_from:""?></td>
							<td class="td-left-top f-size-12">To: </td>
							<td colspan="2" class="td-center-top f-size-12"><?php echo isset($date_to) ? $date_to:""?></td>
						</tr>

						<tr>
							<td colspan="7" class="td-center-top"><br></td>
						</tr>
						<tr>
							<td rowspan="2" class="td-border-3 align-c valign-mid">Day</td>
							<td colspan="2" class="td-border-3 align-c valign-bot" >A.M</td>
							<td colspan="2" class="td-border-3 align-c valign-bot" >P.M.</td>
							<td colspan="2" class="td-border-4 align-c valign-bot" >Undertime / Tardy</td>
						</tr>
						<tr>
							<td class="td-border-3 align-c valign-bot">Arrival</td>
							<td class="td-border-3 align-c valign-bot">Depar-<br>ture</td>
							<td class="td-border-3 align-c valign-bot">Arrival</td>
							<td class="td-border-3 align-c valign-bot">Depar-<br>ture</td>
							<td class="td-border-3 align-c valign-bot">Hours</td>
							<td class="td-border-4 align-c valign-bot">Min-<br>utes</td>
						</tr>
						<?php 
							$total_absent_hrs = 0;
							for($x = $date_display_start; $x <=$date_display_end; $x++) :?>
						<tr>
							<td class="td-border-light-left td-border-light-bottom align-c valign-bot" align="center"><?php echo $x?></td>
							<td class="f-size-8 td-border-light-left td-border-light-bottom align-c valign-bot"><?php echo isset($dtr[$x]['time_in']) ? $dtr[$x]['time_in']:""?></td>
							<td class="f-size-8 td-border-light-left td-border-light-bottom align-c valign-bot"><?php echo isset($dtr[$x]['break_out']) ? $dtr[$x]['break_out']:""?></td>
							<td class="f-size-8 td-border-light-left td-border-light-bottom align-c valign-bot"><?php echo isset($dtr[$x]['break_in']) ? $dtr[$x]['break_in']:""?></td>
							<td class="f-size-8 td-border-light-left td-border-light-bottom align-c valign-bot"><?php echo isset($dtr[$x]['time_out']) ? $dtr[$x]['time_out']:""?></td>

							<?php								
								$undertime_hour = isset($dtr[$x]['undertime_hour']) ? $dtr[$x]['undertime_hour']:"";
								if ( $undertime_hour == 8 AND empty($dtr[$x]['attendance_status_name']) AND (empty($dtr[$x]['time_in']) OR empty($dtr[$x]['time_out'])))
								{
									$total_absent_hrs += 8;
									$dtr[$x]['attendance_status_name'] = 'ABSENT';
								}
								//davcorrea added 4 hours
								
								if ( $undertime_hour == 4 AND empty($dtr[$x]['attendance_status_name']) AND (empty($dtr[$x]['time_in']) AND empty($dtr[$x]['time_out'])))
								{
									$total_absent_hrs += 4;
									$dtr[$x]['attendance_status_name'] = 'ABSENT';
								}
								// marvin : add 10,12,16,24 working hours : start
								if ( $undertime_hour == 10 AND empty($dtr[$x]['attendance_status_name']) AND empty($dtr[$x]['time_in']))
								{
									$total_absent_hrs += 10;
									$dtr[$x]['attendance_status_name'] = 'ABSENT';
								}

								if ( $undertime_hour == 12 AND empty($dtr[$x]['attendance_status_name']) AND empty($dtr[$x]['time_in']))
								{
									$total_absent_hrs += 12;
									$dtr[$x]['attendance_status_name'] = 'ABSENT';
								}

								if ( $undertime_hour == 16 AND empty($dtr[$x]['attendance_status_name']) AND empty($dtr[$x]['time_in']))
								{
									$total_absent_hrs += 16;
									$dtr[$x]['attendance_status_name'] = 'ABSENT';
								}

								if ( $undertime_hour == 24 AND empty($dtr[$x]['attendance_status_name']) AND empty($dtr[$x]['time_in']))
								{
									$total_absent_hrs += 24;
									$dtr[$x]['attendance_status_name'] = 'ABSENT';
								}
								// marvin : add 10,12,16,24 working hours : end

								if ( ! empty($dtr[$x]['holiday']) )
									$dtr[$x]['attendance_status_name'] = $dtr[$x]['holiday'];
							?>

							<?php if(EMPTY($dtr[$x]['attendance_status_name'])):?>
							<td class="f-size-8 td-border-light-left td-border-light-bottom align-c valign-bot"><?php echo $undertime_hour ?></td>
							<td class="f-size-8 td-border-light-left td-border-light-right td-border-light-bottom align-c valign-bot"><?php echo isset($dtr[$x]['undertime_min']) ? $dtr[$x]['undertime_min']:""?></td>
							<?php else:?>
								<td colspan="2" class="f-size-8 td-border-light-left td-border-light-right td-border-light-bottom align-c valign-bot"><?php echo isset($dtr[$x]['attendance_status_name']) ? $dtr[$x]['attendance_status_name']:""?></td>
							<?php endif;?>
						</tr>
						<?php 	endfor;?>
						<tr>
							<td colspan="5" class="td-border-light-left td-border-light-bottom align-r valign-bot">Total</td>
							<td class="td-border-light-left td-border-light-bottom align-c valign-bot"><?php echo $total_undertime_hour[$key] - $total_absent_hrs;?></td>
							<td class="td-border-light-left td-border-light-right td-border-light-bottom align-c valign-bot"><?php echo $total_undertime_min[$key];?></td>
						</tr>
						<tr>
							<td colspan="7" class="td-center-top f-size-10"><br><i>I certify on my honor that the above is a true and correct report of the hours of work performed, record of which was made daily at the time of arrival and departure from office.</i><br></td>
						</tr>
						<tr>
							<td colspan="7"><br><br><br></td>
						</tr>
						<tr>
							<td colspan="7" class="td-border-bottom"><br></td>
						</tr>
						<tr>
							<td colspan="7"><i>VERIFIED as to the prescribed office hours:</i><br></td>
						</tr>
						<tr>
							<td colspan="7" class="td-center-bottom td-border-bottom"><br><br><br></td>
						</tr>
						<tr>
							<td colspan="7" class="td-center-top"><center><i>In Charge</i></center></td>
						</tr>
					</tbody>
				</table>
			</td>
			<td><br></td>
			<td style="padding-left:30px">
				<table class="table-max cont-5">
					<tbody>
						<!--add work schedule in dtr-->
						<tr>
							<td colspan="7" class="td-left-top f-size-7"><?php echo $work_schedule[$key]['work_schedule_name']; ?></td>
						</tr>
						<!--end-->
						<tr>
							<td colspan="7" class="td-left-top f-size-7">Civil Service Form No. 48</td>
						</tr>
						<tr>
							<td colspan="7" class="td-center-bottom f-size-14"><br><center><b>DAILY TIME RECORD</b></center></td>
						</tr>
						<tr>
							<td colspan="7" class="td-center-top f-size-7"><center>-----o0o-----</center></td>
						</tr>
						<tr>
							<td colspan="7" class="td-center-bottom td-border-bottom f-size-12"><b><br><center><?php echo isset($employee_name[$key]) ? $employee_name[$key]:""?></center></b></td>
						</tr>
						<tr>
							<td colspan="7" class="td-center-top"><center>(Name)</center><br><br></td>
						</tr>
						<tr>
							<td class="td-left-top f-size-12">From: </td>
							<td colspan="3" class="td-center-top f-size-12"><?php echo isset($date_from) ? $date_from:""?></td>
							<td class="td-left-top f-size-12">To: </td>
							<td colspan="2" class="td-center-top f-size-12"><?php echo isset($date_to) ? $date_to:""?></td>
						</tr>

						<tr>
							<td colspan="7" class="td-center-top"><br></td>
						</tr>
						<tr>
							<td rowspan="2" class="td-border-3 align-c valign-mid">Day</td>
							<td colspan="2" class="td-border-3 align-c valign-bot" >A.M</td>
							<td colspan="2" class="td-border-3 align-c valign-bot" >P.M.</td>
							<td colspan="2" class="td-border-4 align-c valign-bot" >Undertime / Tardy</td>
						</tr>
						<tr>
							<td class="td-border-3 align-c valign-bot">Arrival</td>
							<td class="td-border-3 align-c valign-bot">Depar-<br>ture</td>
							<td class="td-border-3 align-c valign-bot">Arrival</td>
							<td class="td-border-3 align-c valign-bot">Depar-<br>ture</td>
							<td class="td-border-3 align-c valign-bot">Hours</td>
							<td class="td-border-4 align-c valign-bot">Min-<br>utes</td>
						</tr>
						<?php for($x = $date_display_start; $x <=$date_display_end; $x++) :?>
						<tr>
							<td class="td-border-light-left td-border-light-bottom align-c valign-bot" align="center"><?php echo $x?></td>
							<td class="f-size-8 td-border-light-left td-border-light-bottom align-c valign-bot"><?php echo isset($dtr[$x]['time_in']) ? $dtr[$x]['time_in']:""?></td>
							<td class="f-size-8 td-border-light-left td-border-light-bottom align-c valign-bot"><?php echo isset($dtr[$x]['break_out']) ? $dtr[$x]['break_out']:""?></td>
							<td class="f-size-8 td-border-light-left td-border-light-bottom align-c valign-bot"><?php echo isset($dtr[$x]['break_in']) ? $dtr[$x]['break_in']:""?></td>
							<td class="f-size-8 td-border-light-left td-border-light-bottom align-c valign-bot"><?php echo isset($dtr[$x]['time_out']) ? $dtr[$x]['time_out']:""?></td>
							<?php if(EMPTY($dtr[$x]['attendance_status_name'])):?>
							<td class="f-size-8 td-border-light-left td-border-light-bottom align-c valign-bot"><?php echo isset($dtr[$x]['undertime_hour']) ? $dtr[$x]['undertime_hour']:""?></td>
							<td class="f-size-8 td-border-light-left td-border-light-right td-border-light-bottom align-c valign-bot"><?php echo isset($dtr[$x]['undertime_min']) ? $dtr[$x]['undertime_min']:""?></td>
							<?php else:?>
								<td colspan="2" class="f-size-8 td-border-light-left td-border-light-right td-border-light-bottom align-c valign-bot"><?php echo isset($dtr[$x]['attendance_status_name']) ? $dtr[$x]['attendance_status_name']:""?></td>
							<?php endif;?>
						</tr>
						<?php 	endfor;?>
						<tr>
							<td colspan="5" class="td-border-light-left td-border-light-bottom align-r valign-bot">Total</td>
							<td class="td-border-light-left td-border-light-bottom align-c valign-bot"><?php echo $total_undertime_hour[$key] - $total_absent_hrs;?></td>
							<td class="td-border-light-left td-border-light-right td-border-light-bottom align-c valign-bot"><?php echo $total_undertime_min[$key];?></td>
						</tr>
						<tr>
							<td colspan="7" class="td-center-top f-size-10"><br><i>I certify on my honor that the above is a true and correct report of the hours of work performed, record of which was made daily at the time of arrival and departure from office.</i><br></td>
						</tr>
						<tr>
							<td colspan="7"><br><br><br></td>
						</tr>
						<tr>
							<td colspan="7" class="td-border-bottom"><br></td>
						</tr>
						<tr>
							<td colspan="7"><i>VERIFIED as to the prescribed office hours:</i><br></td>
						</tr>
						<tr>
							<td colspan="7" class="td-center-bottom td-border-bottom"><br><br><br></td>
						</tr>
						<tr>
							<td colspan="7" class="td-center-top"><center><i>In Charge</i></center></td>
						</tr>
					</tbody>
				</table>
			</td>
		</tr>
	</tbody>
</table>
<?php endforeach;?>
<?php endif;?>
</body>
</html>