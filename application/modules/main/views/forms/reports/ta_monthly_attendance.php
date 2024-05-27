
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<html>
<head>
	<title>Monthly Report on Attendance</title>
	<link rel="stylesheet" href="<?php echo base_url().PATH_CSS ?>reports.css" type="text/css" />
</head>
<style>
 td{
	font-size: 13px;
 }
</style>
<body>
		
		<?php 
			
			foreach ($mra_array as $office_count => $offices):
				$footer_flag   = false;
				$header_flag = true;
				$counter     = 0;

				?>
				<table class="table-max cont-5">
					<thead>
						<tr class="f-size-12">
							<td colspan="13"><b>MONTHLY REPORT ON ATTENDANCE</b></td>
						</tr>
						<tr>
							<td colspan="13">For the month of</td>
						</tr>
						<tr>
							<td colspan="13">From : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span><b><?php echo isset($period_detail['date_from']) ? $period_detail['date_from'] : ""?></b></span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; To : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span> <b> <?php echo isset($period_detail['date_to']) ? $period_detail['date_to'] : ""?> </b></span> </td>
						</tr>
						<tr>
							<td colspan="13" height="50"><span>Name of Bureau : </span> <span class="pad-left-100"><b> <?php echo $offices['office']?></b></span></td>
						</tr>
						<tr>
							<td class="td-border-3 align-c valign-mid" rowspan="2" >NO</td>
							<td class="td-border-3 align-c valign-mid" rowspan="2" width="120px">NAME</td>
							<td class="td-border-3 align-c valign-mid f-size-12"  width="110px" colspan="2">ABSENCE/S <br>IN DAYS</td>
							<td class="td-border-3 align-c valign-mid f-size-11"  width="60px"  style="border-right: 2px solid black;" colspan="2"  rowspan="2">ABSENCE/S W/O OFFICIAL LEAVE</td>
							<td class="td-border-3 align-c valign-mid f-size-12" colspan="3">UNDERTIME</td>
							<td class="td-border-3 align-c valign-mid f-size-12 td-border-thick-right td-border-thick-left"  rowspan="2">DATE</td>
							<td class="td-border-3 align-c valign-mid f-size-12"  width="100px" colspan="2">ABSENCE/S</td>
							<td class="td-border-3 align-c valign-mid f-size-11"  width="60px"  style="border-right: 2px solid black;" colspan="2"  rowspan="2">ABSENCE/S W/O OFFICIAL LEAVE</td>
							<td class="td-border-3 align-c valign-mid f-size-12" colspan="2">UNDERTIME</td>
							<td class="td-border-4 align-c valign-mid f-size-12" rowspan="2" width="100px">REMARKS</td>
						</tr>
						
						<tr>
							<td class="td-border-3 align-c valign-mid f-size-12">SICK</td>
							<td class="td-border-3 align-c valign-mid f-size-12">VAC</td>
							<td class="td-border-3 align-c valign-mid f-size-12" >HOUR</td>
							<td class="td-border-3 align-c valign-mid f-size-12" >MIN</td>
							<td class="td-border-3 align-c valign-mid f-size-12" >DAY/<br>EQUIV</td>
							<td class="td-border-3 align-c valign-mid f-size-12" >SICK</td>
							<td class="td-border-3 align-c valign-mid f-size-12" >VAC</td>
							<td class="td-border-3 align-c valign-mid f-size-12" >HOUR</td>
							<td class="td-border-3 align-c valign-mid f-size-12" >MIN</td>
						</tr>
					</thead>
					<tbody>
				<?php 
				foreach ($offices['employee'] as $employee_count => $employee):
					$counter++;
					if(count($employee['mra_detail']) > 20)
					{
						$header_flag = true;
					}
					else
					{
						if($counter%2 == 1)
						{
							$header_flag = true;
						}
						else
						{
							$header_flag = false;
						}
					}
			?>
		
					<?php 
						$sick_total = 0;
						$vac_total = 0;
						$hour_total = 0;
						$min_total = 0;
						$total_vacation = 0;
						$print_total_vl = FALSE;

						foreach ($employee['mra_detail'] as  $k => $mra_detail):							
							$under_hour = floor($mra_detail['undertime_min']/60) + $mra_detail['undertime_hour'];
							if ( $under_hour == 8 )
							{
								$total_vacation += 1;
								$print_total_vl = TRUE;
							}
						endforeach;

						foreach ($employee['mra_detail'] as  $k => $mra_detail):
							// echo"<pre>";
							// print_r($employee['mra_detail']);
							// die();
						$print_num_name = ($k == 0) ? TRUE : FALSE;
							if(isset($mra_detail['sick_leave']))
							{
								$sick_total = $sick_total + $mra_detail['sick_leave'];
							}
							if(isset($mra_detail['vacation_leave']))
							{
								$vac_total = $vac_total + $mra_detail['vacation_leave'];
							}

							$sixty_minutes = 60;
							$under_hour    = 0;
							$underl_min    = 0;
							$under_hour    = floor($mra_detail['undertime_min']/$sixty_minutes) + $mra_detail['undertime_hour'];
							$under_min     = ($mra_detail['undertime_min']%$sixty_minutes);
							$mra_vacation_leave = (isset($mra_detail['vacation_leave']) AND $mra_detail['vacation_leave'] > 0) ? round($mra_detail['vacation_leave'],3) : "";

							// if ( $under_hour == 8 )
							// {
							// 	$vac_total += 1;
							// 	$mra_vacation_leave += 1;
							// 	$under_hour -= 8;
							// }
							
							$hour_total    = $hour_total + $under_hour;
							$min_total     = $min_total + $under_min;
							
					?>
						<!-- marvin -->
						<?php if(!empty($mra_detail) OR $print_num_name == TRUE): ?>
						<tr>
							<td class="td-border-light-left align-c"><?php echo ($print_num_name)? $employee_count:"";?></td>
							<td class="td-border-light-left"><b><?php echo ($print_num_name)? strtoupper($employee['employee_name']) : "";?></b></td>
							<td class="td-border-light-left align-c">
								<?php
								if($print_num_name == TRUE)
								{
									if($employee['sick_leave_wp'] > 0)
									{
										echo  round($employee['sick_leave_wp'],3) . " WP";
									}
									if($employee['sick_leave_wop'] > 0)
									{
										echo  " <br>" .  round($employee['sick_leave_wop'],3) . "<span style='color:red;'> WOP</span>";
									}
									if(
										$employee['sick_leave_wp'] + $employee['sick_leave_wop'] == 0 &&
										$employee['vacation_leave_wp'] + $employee['vacation_leave_wop'] == 0 &&
										$employee['absents_wo_official_leave_counter'] == 0 &&
										$sick_total == 0 &&
										$vac_total == 0 &&
										$employee['total_hour'] == 0 &&
										$employee['total_min'] == 0
										)
										{
											echo "<span style='color:red;'>FULL</span>";
										}
								}
								?>						
							</td>
							<td class="td-border-light-left align-c">
								<?php 
								if($print_num_name == TRUE )
								{
									if($employee['vacation_leave_wp'] > 0)
									{
										echo  round($employee['vacation_leave_wp'],3) . " WP";
									}
									if($employee['vacation_leave_wop'] > 0)
									{
										echo  " <br>" .  round($employee['vacation_leave_wop'],3) . "<span style='color:red;'> WOP</span>";
									}
									if(
										$employee['sick_leave_wp'] + $employee['sick_leave_wop'] == 0 &&
										$employee['vacation_leave_wp'] + $employee['vacation_leave_wop'] == 0 &&
										$employee['absents_wo_official_leave_counter'] == 0 &&
										$sick_total == 0 &&
										$vac_total == 0 &&
										$employee['total_hour'] == 0 &&
										$employee['total_min'] == 0
										)
										{
											echo "<span style='color:red;'>TIME</span>";
										}
								}

								?>
							</td>
							<td class="td-border-light-left align-c" colspan="2" style="border-right:2px solid black;">
							<?php 
								if($print_num_name == TRUE )
								{
									if( $employee['absents_wo_official_leave_counter'] == 0)
									{
										echo"";
									}else
									{
										echo  $employee['absents_wo_official_leave_counter'] . " <span style='color:red;'> WOP</span>";
									}

								}

								?>
							</td>
							<td class="td-border-light-left align-c"><?php echo ($print_num_name == TRUE AND $employee['total_hour'] > 0)? $employee['total_hour'] : "";?></td>
							<td class="td-border-light-left align-c"><?php echo ($print_num_name == TRUE AND $employee['total_min'] > 0)? $employee['total_min'] : "";?></td>
							<td class="td-border-light-left align-c"><?php echo ($print_num_name == TRUE AND $employee['equivalent'] > 0)? number_format($employee['equivalent'],3) : "";?></td>
							<td class="td-border-light-left align-c td-border-thick-right td-border-thick-left"><?php echo $mra_detail['attendance_date'];?></td>
							<td class="td-border-light-left align-c"><?php echo (isset($mra_detail['sick_leave']) AND $mra_detail['sick_leave'] > 0)? round($mra_detail['sick_leave'],3) : ""?></td>
							<td class="td-border-light-left align-c"><?php echo $mra_vacation_leave ?></td>
							<td class="td-border-light-left align-c" colspan="2" style="border-right: 2px solid black;">
								<?php
								if($mra_detail['attendance_status_id'] == ABSENT)
								
								{
									echo "1";
								}
								?>
							</td>
							<td class="td-border-light-left align-c"><?php echo ($under_hour > 0)? round($under_hour,3) : ""?></td>
							<td class="td-border-light-left align-c"><?php echo ($under_min > 0) ? round($under_min,3) : ""?></td>
							<td class="td-border-light-left td-border-light-right"><?php
							if($mra_detail['attendance_status_id'] == ABSENT)
								
							{
								echo "ABSENT";
							}
							else
							{
								echo $mra_detail['remarks'];
							}
								
							 ?></td>
						</tr>
						<!-- marvin -->
						<?php endif; ?>
					<?php endforeach;?>
						<tr>
							<td class="td-border-light-bottom td-border-light-top td-border-light-left"  colspan="2" style="border-bottom: 2px solid black;">Total<br></td>
							<td class="td-border-light-bottom td-border-light-top td-border-light-left align-c" style="border-bottom: 2px solid black;"><?php echo ($employee['sick_leave_wp'] + $employee['sick_leave_wop'] > 0 )? round($employee['sick_leave_wp'] + $employee['sick_leave_wop'],3)   : "0" ;?></td>
							<td class="td-border-light-bottom td-border-light-top td-border-light-left align-c" style="border-bottom: 2px solid black;"><?php echo ($employee['vacation_leave_wp'] + $employee['vacation_leave_wop'] > 0 )? round($employee['vacation_leave_wp'] + $employee['vacation_leave_wop'],3)   : "0" ;?></td>
							
							<td class="td-border-light-bottom td-border-light-top td-border-light-left align-c" colspan="2" style="border-right: 2px solid black; border-bottom: 2px solid black;">
							<?php echo $employee['absents_wo_official_leave_counter']; ?>
							</td>
							<td class="td-border-light-bottom td-border-light-top" style="color:red; border-bottom: 2px solid black;" colspan="4"><?php echo ($employee['additional_lwop_remarks'])? $employee['additional_lwop_remarks'] : "";?></td>
							<td class="td-border-light-bottom td-border-light-top td-border-light-left align-c" style="border-bottom: 2px solid black;"><?php echo $sick_total?></td>
							<td class="td-border-light-bottom td-border-light-top td-border-light-left align-c" style="border-bottom: 2px solid black;"><?php echo $vac_total?></td>
							<td class="td-border-light-bottom td-border-light-top td-border-light-left align-c" colspan="2" style="border-right: 2px solid black; border-bottom: 2px solid black;"><?php echo $employee['absents_wo_official_leave_counter']; ?></td>
							<td class="td-border-light-bottom td-border-light-top td-border-light-left align-c" style="border-bottom: 2px solid black;"><?php echo $hour_total?></td>
							<td class="td-border-light-bottom td-border-light-top td-border-light-left align-c"style="border-bottom: 2px solid black;"><?php echo $min_total?></td>
							<td class="td-border-light-bottom td-border-light-top td-border-light-left td-border-light-right" style="border-bottom: 2px solid black;"><br></td>
							
						</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
		<?php endforeach;?>
	
</body>
</html>
