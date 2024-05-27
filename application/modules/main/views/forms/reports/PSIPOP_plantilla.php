<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<html>
<head>
	
	<title>PSIPOP</title>
	<link rel="stylesheet" href="<?php echo base_url().PATH_CSS ?>reports.css" type="text/css" />
</head>
<style>
	td.font-10{
		font-size: 10px;
	}
	td{
		font-family: Arial;
	}
</style>


<body>
	<?php if($division): ?>
		<?php 
		$grand_total_filled = 0;
		$grand_total_unfilled = 0;
		$grand_cnt_filled = 0;
		$grand_cnt_unfilled = 0;
		foreach ($division AS $off): ?>
			<table style="page-break-after: always; font-size: 10px;" class="table-max cont-5">
			<!-- <table style="font-size: 10px;" class="table-max cont-5"> -->
				<thead>
					<tr>
						<td class="td-center-middle" colspan=16 height="76"><b>Republic of the Philippines<br>DEPARTMENT OF BUDGET AND MANAGEMENT<br><span style="font-size: 10pt;">PERSONAL SERVICES ITEMIZATION AND PLANTILLA OF PERSONNEL (PSIPOP)</span><br>for the Fiscal Year : <?php echo $date?></b></td>
					</tr>
					<tr>
						<td class="td-border-4 td-left-top" height="54" colspan=8 width="100"><b>Department: Department of Health</b></td>
						<td class="td-border-4 td-left-top" colspan=8><b>Bureau / Agency: <?php echo $agency['name']?></b></td>
					</tr>
					<tr>
						<td class="td-border-top td-border-left td-border-right td-center-middle" colspan=2 rowspan=2 height="42"><b>ITEM NUMBER<br></b></td>
						<td class="td-border-top td-border-left td-border-right td-center-middle" colspan=2 rowspan=2 width="100"><b>POSITION TITLE and<br>SALARY GRADE<br></b></td>
						<td class="td-border-4 td-center-middle" colspan=2><b>ANNUAL SALARY</b></td>
						<td class="td-border-top td-border-left td-border-right td-center-middle" rowspan=2><b>S<br>T<br>E<br>P<br></b></td>
						<td class="td-border-top td-border-left td-border-right td-center-middle" rowspan=2><b>L<br>E<br>V<br>E<br>L<br></b></td>
						<td class="td-border-top td-border-left td-border-right td-center-middle" rowspan=2 width="100"><b>NAME OF INCUMBENT</b></td>
						<td class="td-border-top td-border-left td-border-right td-center-middle" rowspan=2><b>S<br>E<br>X</b></td>
						<td class="td-border-top td-border-left td-border-right td-center-middle" rowspan=2><b>DATE<br>OF<br>BIRTH</b></td>
						<td class="td-border-top td-border-left td-border-right td-center-middle" rowspan=2><b>TIN</b></td>
						<td class="td-border-top td-border-left td-border-right td-center-middle" rowspan=2><b>DATE OF<br>ORIGINAL<br>APPOINTMENT</b></td>
						<td class="td-border-top td-border-left td-border-right td-center-middle" rowspan=2><b>DATE OF<br>LAST<br>PROMOTION</b></td>
						<td class="td-border-top td-border-left td-border-right td-center-middle" rowspan=2><b>S<br>T<br>A<br>T<br>U<br>S</b></td>
						<td class="td-border-top td-border-left td-border-right td-center-middle" rowspan=2><b>CIVIL<br>SERVICE<br>ELIGIBILITY</b></td>
					</tr>
					<tr>
						<td class="td-border-top td-border-left td-border-right td-center-middle"><b>AUTHORIZED</b></td>
						<td class="td-border-top td-border-left td-border-right td-center-middle"><b>ACTUAL</b></td>
					</tr>
					<tr>
						<td class="td-border-bottom td-border-left td-border-right td-center-bottom" colspan=2 height="21"><b>(1)</b></td>
						<td class="td-border-bottom td-border-left td-border-right td-center-bottom" colspan=2><b>(2)</b></td>
						<td class="td-border-bottom td-border-left td-border-right td-center-bottom"><b>(3)</b></td>
						<td class="td-border-bottom td-border-left td-border-right td-center-bottom"><b>(4)</b></td>
						<td class="td-border-bottom td-border-left td-border-right td-center-bottom"><b>(5)</b></td>
						<td class="td-border-bottom td-border-left td-border-right td-center-bottom"><b>(8)</b></td>
						<td class="td-border-bottom td-border-left td-border-right td-center-bottom"><b>(10)</b></td>
						<td class="td-border-bottom td-border-left td-border-right td-center-bottom"><b>(11)</b></td>
						<td class="td-border-bottom td-border-left td-border-right td-center-bottom"><b>(12)</b></td>
						<td class="td-border-bottom td-border-left td-border-right td-center-bottom"><b>(13)</b></td>
						<td class="td-border-bottom td-border-left td-border-right td-center-bottom"><b>(14)</b></td>
						<td class="td-border-bottom td-border-left td-border-right td-center-bottom"><b>(15)</b></td>
						<td class="td-border-bottom td-border-left td-border-right td-center-bottom"><b>(16)</b></td>
						<td class="td-border-bottom td-border-left td-border-right td-center-bottom"><b>(17)</b></td>
					</tr>	
					<tr>
						<td height="10"></td>
					</tr>
				</thead>
						<tbody>
							<tr><td colspan=16 class="td-left-bottom bold" valign="middle"><br><?php echo !empty($off['name']) ? strtoupper($off['name']." (". strtoupper($agency['org_code']). ")") : 'OFFICE OF THE DIRECTOR'." (". strtoupper($agency['org_code']). ")" ?><br></td></tr>
							<?php if($records): ?>

								<?php 
								$subtotal_filled = 0;
								$subtotal_filled_auth = 0;
								$subtotal_unfilled = 0;
								$subtotal_unfilled_auth = 0;
								$sub_cnt_filled = 0;
								$sub_cnt_unfilled = 0;
								foreach ($records AS $record): ?>

									<?php if($record['division_id'] == $off['office_id']): ?>

										<?php 
										$last_promotion_date = '';
										foreach ($prev_start_date as $prev): ?>

											<?php if($record['employee_id'] == $prev['employee_id']): ?>
												<?php $last_promotion_date = $prev['last_promotion_date'];
												$original_appt_date  = $prev['original_appt_date']?>
											<?php endif;?>

										<?php endforeach;?>
										
										<?php 
										$type_flag ='';
										foreach ($eligibility_type_flag as $type): ?>
											<?php if($record['eligibility_type_flag'] == $type['sys_param_value']): ?>
												<?php $type_flag = $type['sys_param_value']?>
											<?php endif;?>
										<?php endforeach;?>

											<tr>
												<td colspan=2 height="21" valign="middle"><?php echo $record['plantilla_code']?></td>
												<td colspan="2" valign="middle"><?php echo strtoupper($record['position']) . ' SG-' . $record['sg']?></td>
												<td valign="middle"><?php echo number_format($record['authorized_amount'], 2)?></td>
												<td valign="middle"><?php echo number_format($record['actual_amount'], 2)?></td>
												<td valign="middle"><?php echo $record['step']?></td>
												<td valign="middle"><?php echo str_replace('Level', '', $record['plantilla_level_code'])?></td>
												<td valign="middle"><?php echo !EMPTY($record['employee_name']) ? $record['employee_name'] : ''?>
												<?php
												if(!EMPTY($record['employee_name'])){
													$sub_cnt_filled ++;
													$subtotal_filled = $subtotal_filled + $record['actual_amount'];
													$subtotal_filled_auth = $subtotal_filled_auth + $record['authorized_amount'];
												}
												else
												{
													$sub_cnt_unfilled ++;
													$subtotal_unfilled = $subtotal_unfilled + $record['actual_amount'];
													$subtotal_unfilled_auth = $subtotal_unfilled_auth + $record['authorized_amount'];								
												}
												?>
												</td>
												<td align="center" valign="middle"><?php echo !EMPTY($record['gender_code']) ? $record['gender_code'] : ''?></td>
												<td align="center" valign="middle"><?php echo !EMPTY($record['birth_date']) ? $record['birth_date'] : '' ?></td>
												<td align="center" valign="middle"><?php echo !EMPTY($record['tin']) ? format_identifications($record['tin'], $record['format']) : '' ?></td>
												<td align="center" valign="middle"><?php echo !EMPTY($record['employee_name']) ? $original_appt_date : ''?></td>
												<td align="center" valign="middle"><?php echo !EMPTY($last_promotion_date) ? $last_promotion_date : ''?></td>
												<td align="center" valign="middle"><?php echo !EMPTY($record['status_code']) ? $record['status_code'] : ''?></td>
												<td align="center" valign="middle">
													<?php
														if ($type_flag == "RA" ) {
															echo "RA1080";
														}else{
															echo strtoupper($type_flag);
														}
													 ?>
												</td>
											</tr>
											
									<?php endif;?>
								<?php endforeach;?>			
							<tr>
								<td colspan=8>
									<table>
										<tr>

											<td class="td-border-top td-left-bottom" width="100">Subtotal</td>
											<td class="td-border-top td-left-sbottom" colspan=2>No. of Filled Positions:</td>
											<td class="td-border-top td-right-bottom"><?php echo number_format($sub_cnt_filled)?></td>
											<td class="td-border-top td-right-bottom"><?php echo number_format($subtotal_filled_auth, 2)?></td>
											<td class="td-border-top td-right-bottom"><?php echo number_format($subtotal_filled, 2)?></td>
										</tr>
										<tr>
											<td class="td-left-bottom"><br></td>
											<td class="td-left-bottom" colspan=2>No. of Unfilled Positions:</td>
											<td class="td-right-bottom" ><?php echo number_format($sub_cnt_unfilled)?></td>
											<td class="td-right-bottom" ><?php echo number_format($subtotal_unfilled_auth, 2)?></td>
											<td class="td-right-bottom" ><?php echo number_format($subtotal_unfilled, 2)?></td>		
										</tr>
										<tr>
											<td height="10"></td>
										</tr>
										<tr>
											<td class="td-left-bottom bold" width="100">Grand Total</td>
											<td class="td-left-bottom bold" colspan=2>No. of Filled Positions:</td>
											<td class="td-right-bottom bold">
												<?php 
												$grand_cnt_filled = 0;
												$grand_cnt_filled = $grand_cnt_filled + $sub_cnt_filled;
												echo number_format($grand_cnt_filled)?>
											</td>
											<td class="td-right-bottom bold" width="100">
												<?php 
												$grand_total_filled_auth = 0;
												$grand_total_filled_auth = $grand_total_filled_auth + $subtotal_filled_auth;
												echo number_format($grand_total_filled_auth, 2)?>
											</td>
											<td class="td-right-bottom bold" width="100">
												<?php 
												$grand_total_filled = 0;
												$grand_total_filled = $grand_total_filled + $subtotal_filled;
												echo number_format($grand_total_filled, 2)?>
											</td>
										</tr>
										<tr>
											<td class="td-left-bottom bold"><br></td>
											<td class="td-left-bottom bold" colspan=2>No. of Unfilled Positions:</td>
											<td class="td-right-bottom bold">
												<?php 
												$grand_cnt_unfilled = 0;
												$grand_cnt_unfilled = $grand_cnt_unfilled + $sub_cnt_unfilled;
												echo number_format($grand_cnt_unfilled)?>
											</td>
											<td class="td-right-bottom bold">
												<?php 
												$grand_total_unfilled_auth = 0;
												$grand_total_unfilled_auth = $grand_total_unfilled_auth + $subtotal_unfilled_auth;
												echo number_format($grand_total_unfilled_auth, 2)?>
											</td>
											<td class="td-right-bottom bold">
												<?php 
												$grand_total_unfilled = 0;
												$grand_total_unfilled = $grand_total_unfilled + $subtotal_unfilled;
												echo number_format($grand_total_unfilled, 2)?>
											</td>
										</tr>
										<tr>
											<td class="td-left-bottom bold"><br></td>
											<td class="td-left-bottom bold" colspan=2>No. of Itemized Positions:</td>
											<td class="td-right-bottom bold"><?php echo number_format(($grand_cnt_filled + $grand_cnt_unfilled))?></td>
											<td class="td-right-bottom bold"><?php echo number_format(($grand_total_filled_auth + $grand_total_unfilled_auth), 2)?></td>
											<td class="td-right-bottom bold"><?php echo number_format(($grand_total_filled + $grand_total_unfilled), 2)?></td>	
										</tr>
										<tr>
											<td height="20"></td>
										</tr>
									</table>
								</td>
							</tr>
							<!-- <tr><td align="center" colspan=16><b>***** NOTHING FOLLOWS *****</b></td></tr>
							<tr><td align="center" colspan=16 height=20></td></tr> -->
							<!-- <tr>
								<td align="center" colspan=16 style="position:absolute; bottom:0; left:0;">
									<table width="100%" style="position:absolute; bottom:0; left:0;">
										<tr>
											<td class="border-thick2" width="280" valign="top">
												<table>
													<tr>
														<td colspan="3" valign=top>Department of Budget and Management</td>
													</tr>
													<tr>
														<td width="20"></td>
														<td class="td-border-bottom" height="30" align="center"><?php echo nbs(80)?></td>
														<td width="10"></td>
													</tr>
												</table>
											</td>
											<td class="td-border-3" width="200">
												<table>
													<tr>
														<td colspan=3 valign=top>I certify to the correctness of the entries from columns 4 to 17 and that employees whose names appear on the above PSIPOP are the incumbents of the positions.</td>
													</tr>
													<tr>	
														<td width="20"></td>
														<td class="td-border-bottom" height="30" align="center" valign=bottom><?php echo $certified_by['signatory_name']?></td>
														<td width="20"></td>
													</tr>10
													<tr>
														<td colspan=3 align="center"><?php echo $certified_by['position_name'] ?></td>
													</tr>
												</table>
											</td>
											<td class="border-thick2" width="250">
												<table>
													<tr>
														<td colspan=3 align="center" valign=top width="250">APPROVED BY:</td>
													</tr>
													<tr>
														<td width="20"></td>
														<td class="td-border-bottom" height="30" align="center" valign=bottom><?php echo $prepared_by['signatory_name']?></td>
														<td width="20"></td>
													</tr>
													<tr>
														<td colspan=3 align="center"><?php echo $prepared_by['position_name']?></td>
													</tr>
												</table>
											</td>
										</tr>
									</table>
								</td>
							</tr> -->

						<?php endif;?>
					</tbody>
					
				</table>
			<?php 
			$grand_total_filled = $grand_total_filled + $subtotal_filled;
			$grand_total_unfilled = $grand_total_unfilled + $subtotal_unfilled;
			$grand_total_filled_auth = $grand_total_filled_auth + $subtotal_filled_auth;
			$grand_total_unfilled_auth = $grand_total_unfilled_auth + $subtotal_unfilled_auth;
			$grand_cnt_filled = $grand_cnt_filled + $sub_cnt_filled;
			$grand_cnt_unfilled = $grand_cnt_unfilled + $sub_cnt_unfilled;
			endforeach;?>	
		<?php endif;?>




		


			<table class="table-max cont-5">
				<tr>
					<td class="td-center-middle" colspan=2 height="76"><b>Republic of the Philippines<br>DEPARTMENT OF BUDGET AND MANAGEMENT<br><span style="font-size: 10pt;">PERSONAL SERVICES ITEMIZATION AND PLANTILLA OF PERSONNEL (PSIPOP)</span><br>for the Fiscal Year : <?php echo $date?></b></td>
					
				</tr>
				<tr>
					<td class="td-border-4 td-left-top" height="54" width="50%"><b>Department: Department of Health</b></td>
					<td class="td-border-4 td-left-top"><b>Bureau / Agency: <?php echo $agency['name']?></b></td>
				</tr>
			</table>
	<table class="table-max cont-5">
		<thead>
			<tr>
				<td class="td-border-top td-border-left td-border-right td-center-middle" colspan=2 rowspan=2 height="42"><b>ITEM NUMBER<br></b></td>
				<td class="td-border-top td-border-left td-border-right td-center-middle" colspan=2 rowspan=2 width="100"><b>POSITION TITLE &amp;<br>SALARY GRADE<br></b></td>
				<td class="td-border-4 td-center-middle" colspan=2><b>ANNUAL SALARY</b></td>
				<td class="td-border-top td-border-left td-border-right td-center-middle" rowspan=2><b>S<br>T<br>E<br>P<br></b></td>
				<td class="td-border-top td-border-left td-border-right td-center-middle" rowspan=2><b>L<br>E<br>V<br>E<br>L<br></b></td>
				<td class="td-border-top td-border-left td-border-right td-center-middle" rowspan=2 width="100"><b>NAME OF INCUMBENT</b></td>
				<td class="td-border-top td-border-left td-border-right td-center-middle" rowspan=2><b>S<br>E<br>X</b></td>
				<td class="td-border-top td-border-left td-border-right td-center-middle" rowspan=2><b>DATE<br>OF<br>BIRTH</b></td>
				<td class="td-border-top td-border-left td-border-right td-center-middle" rowspan=2><b>TIN</b></td>
				<td class="td-border-top td-border-left td-border-right td-center-middle" rowspan=2><b>DATE OF<br>ORIGINAL<br>APPOINTMENT</b></td>
				<td class="td-border-top td-border-left td-border-right td-center-middle" rowspan=2><b>DATE OF<br>LAST<br>PROMOTION</b></td>
				<td class="td-border-top td-border-left td-border-right td-center-middle" rowspan=2><b>S<br>T<br>A<br>T<br>U<br>S</b></td>
				<td class="td-border-top td-border-left td-border-right td-center-middle" rowspan=2><b>CIVIL<br>SERVICE<br>ELIGIBILITY</b></td>
			</tr>
			<tr>
				<td class="td-border-top td-border-left td-border-right td-center-middle"><b>AUTHORIZED</b></td>
				<td class="td-border-top td-border-left td-border-right td-center-middle"><b>ACTUAL</b></td>
				</tr>
			<tr>
				<td class="td-border-bottom td-border-left td-border-right td-center-bottom" colspan=2 height="21"><b>(1)</b></td>
				<td class="td-border-bottom td-border-left td-border-right td-center-bottom" colspan=2><b>(2)</b></td>
				<td class="td-border-bottom td-border-left td-border-right td-center-bottom"><b>(3)</b></td>
				<td class="td-border-bottom td-border-left td-border-right td-center-bottom"><b>(4)</b></td>
				<td class="td-border-bottom td-border-left td-border-right td-center-bottom"><b>(5)</b></td>
				<td class="td-border-bottom td-border-left td-border-right td-center-bottom"><b>(8)</b></td>
				<td class="td-border-bottom td-border-left td-border-right td-center-bottom"><b>(10)</b></td>
				<td class="td-border-bottom td-border-left td-border-right td-center-bottom"><b>(11)</b></td>
				<td class="td-border-bottom td-border-left td-border-right td-center-bottom"><b>(12)</b></td>
				<td class="td-border-bottom td-border-left td-border-right td-center-bottom"><b>(13)</b></td>
				<td class="td-border-bottom td-border-left td-border-right td-center-bottom"><b>(14)</b></td>
				<td class="td-border-bottom td-border-left td-border-right td-center-bottom"><b>(15)</b></td>
				<td class="td-border-bottom td-border-left td-border-right td-center-bottom"><b>(16)</b></td>
				<td class="td-border-bottom td-border-left td-border-right td-center-bottom"><b>(17)</b></td>
			</tr>
		</thead>
		<?php if($office): ?>
			<?php 
			$grand_total_filled = 0;
			$grand_total_unfilled = 0;
			$grand_cnt_filled = 0;
			$grand_cnt_unfilled = 0;
			foreach ($office AS $off): ?>
				<tr><td height="30"></td></tr>
				<tr>
					<td colspan=9 class="td-left-bottom bold" valign="middle"><?php echo strtoupper($off['employ_office_name']) ?></td>			
					<td></td>	
				</tr>
				<tr><td height="30"></td></tr>

				<?php if($records): ?>
					<?php 
					$subtotal_filled = 0;
					$subtotal_filled_auth = 0;
					$subtotal_unfilled = 0;
					$subtotal_unfilled_auth = 0;
					$sub_cnt_filled = 0;
					$sub_cnt_unfilled = 0;
					foreach ($records AS $record): ?>
						<?php if($record['office_id'] == $off['office_id']): ?>
							<tr>
								<?php
									if(!EMPTY($record['employee_name'])){
										$sub_cnt_filled ++;
										$subtotal_filled = $subtotal_filled + $record['actual_amount'];
										$subtotal_filled_auth = $subtotal_filled_auth + $record['authorized_amount'];
									}
									else
									{
										$sub_cnt_unfilled ++;
										$subtotal_unfilled = $subtotal_unfilled + $record['actual_amount'];
										$subtotal_unfilled_auth = $subtotal_unfilled_auth + $record['authorized_amount'];								
									}
								?>
							</tr>
						<?php endif;?>
					<?php 
					endforeach;?>			
				<?php endif;?>
			<?php 
			$grand_total_filled = $grand_total_filled + $subtotal_filled;
			$grand_total_unfilled = $grand_total_unfilled + $subtotal_unfilled;
			$grand_total_filled_auth = $grand_total_filled_auth + $subtotal_filled_auth;
			$grand_total_unfilled_auth = $grand_total_unfilled_auth + $subtotal_unfilled_auth;
			$grand_cnt_filled = $grand_cnt_filled + $sub_cnt_filled;
			$grand_cnt_unfilled = $grand_cnt_unfilled + $sub_cnt_unfilled;
			endforeach;?>		
		<?php endif;?>
	</table>
	<table>
		<tr>
			<td>
				<table>
					<tr>
						<td class="td-left-bottom" width="80">Subtotal</td>
						<td class="td-left-bottom" width="150">No. of Filled Positions:</td>
						<td class="td-right-bottom" width="20"><?php echo number_format($sub_cnt_filled)?></td>
						<td class="td-right-bottom" width="100"><?php echo number_format($subtotal_filled_auth, 2)?></td>
						<td class="td-right-bottom" width="100"><?php echo number_format($subtotal_filled, 2)?></td>
					</tr>
					<tr>
						<td class="td-left-bottom"><br></td>
						<td class="td-left-bottom">No. of Unfilled Positions:</td>
						<td class="td-right-bottom"><?php echo number_format($sub_cnt_unfilled)?></td>
						<td class="td-right-bottom"><?php echo number_format($subtotal_unfilled_auth, 2)?></td>
						<td class="td-right-bottom"><?php echo number_format($subtotal_unfilled, 2)?></td>		
					</tr>
					<tr>
						<td height="20"></td>
					</tr>
					<tr>
						<td class="td-left-bottom bold">Grand Total</td>
						<td class="td-left-bottom bold">No. of Filled Positions:</td>
						<td class="td-right-bottom bold">
							<?php 
							$grand_cnt_filled = 0;
							$grand_cnt_filled = $grand_cnt_filled + $sub_cnt_filled;
							echo number_format($grand_cnt_filled)?>
						</td>
						<td class="td-right-bottom bold" width="100">
							<?php 
							$grand_total_filled_auth = 0;
							$grand_total_filled_auth = $grand_total_filled_auth + $subtotal_filled_auth;
							echo number_format($grand_total_filled_auth, 2)?>
						</td>
						<td class="td-right-bottom bold" width="100">
							<?php 
							$grand_total_filled = 0;
							$grand_total_filled = $grand_total_filled + $subtotal_filled;
							echo number_format($grand_total_filled, 2)?>
						</td>
						
					</tr>
					<tr>
						<td class="td-left-bottom bold"><br></td>
						<td class="td-left-bottom bold">No. of Unfilled Positions:</td>
						<td class="td-right-bottom bold">
							<?php 
							$grand_cnt_unfilled = 0;
							$grand_cnt_unfilled = $grand_cnt_unfilled + $sub_cnt_unfilled;
							echo number_format($grand_cnt_unfilled)?>
						</td>
						<td class="td-right-bottom bold">
							<?php 
							$grand_total_unfilled_auth = 0;
							$grand_total_unfilled_auth = $grand_total_unfilled_auth + $subtotal_unfilled_auth;
							echo number_format($grand_total_unfilled_auth, 2)?>
						</td>
						<td class="td-right-bottom bold">
							<?php 
							$grand_total_unfilled = 0;
							$grand_total_unfilled = $grand_total_unfilled + $subtotal_unfilled;
							echo number_format($grand_total_unfilled, 2)?>
						</td>
						
					</tr>
					<tr>
						<td class="td-left-bottom bold"><br></td>
						<td class="td-left-bottom bold">No. of Itemized Positions:</td>
						<td class="td-right-bottom bold"><?php echo number_format(($grand_cnt_filled + $grand_cnt_unfilled))?></td>
						<td class="td-right-bottom bold"><?php echo number_format(($grand_total_filled_auth + $grand_total_unfilled_auth), 2)?></td>
						<td class="td-right-bottom bold"><?php echo number_format(($grand_total_filled + $grand_total_unfilled), 2)?></td>			
					</tr>				
				</table>
			</td>
		</tr>
	</table>










		
	<!-- <table>
		<tr>
			<td class="td-center-middle" colspan=3 height="80"><b>***** NOTHING FOLLOWS *****</b></td>
		</tr>
		<tr>
			<td class="border-thick2" width="280" valign="top" height="60">
				<table>
					<tr>
						<td colspan="3" valign=top>Department of Budget and Management</td>
					</tr>
					<tr>
						<td width="70"></td>
						<td class="td-border-bottom" height="30" align="center"><?php echo nbs(50)?></td>
						<td width="20"></td>
					</tr>
				</table>
			</td>
			<td class="td-border-3" width="400">
				<table>
					<tr>
						<td colspan=3 valign=top>I certify to the correctness of the entries from columns 4 to 17 and that employees<br>whose names appear on the above PSIPOP are the incumbents of the positions.</td>
					</tr>
					<tr>	
						<td width="50"></td>
						<td class="td-border-bottom" height="30" align="center" valign=bottom><?php echo $certified_by['signatory_name']?></td>
						<td width="20"></td>
					</tr>
					<tr>
						<td colspan=3 align="center"><?php echo $certified_by['position_name'] ?></td>
					</tr>
				</table>
			</td>
			<td class="border-thick2" width="400">
				<table>
					<tr>
						<td colspan=3 align="center" valign=top width="400">APPROVED BY:</td>
					</tr>
					<tr>
						<td width="20"></td>
						<td class="td-border-bottom" height="30" align="center" valign=bottom><?php echo $prepared_by['signatory_name']?></td>
						<td width="20"></td>
					</tr>
					<tr>
						<td colspan=3 align="center"><?php echo $prepared_by['position_name']?></td>
					</tr>
				</table>
			</td>
		</tr>
	</table> -->

			<!-- ************************************************************************** -->
			
		</body>

		</html>
