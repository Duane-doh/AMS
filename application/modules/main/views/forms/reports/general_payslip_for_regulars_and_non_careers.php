<!DOCTYPE html>
<html>
	<head>
		<title>General Payslip for regulars and Non Careers</title>
		<link rel="stylesheet" href="<?php echo base_url().PATH_CSS ?>reports.css" type="text/css" />
	    <style type="text/css">
	        td { 
	        	padding-left: 1px !important;
	        	padding-right: 1px !important;
	        }
	        .with_background_logo
	        {
	        	background-image: url(<?php echo base_url().PATH_IMG ?>doh_logo_bw.png); 
	        	background-position: center center;
    			background-image-resize: 2;
	        }
	    </style>
	</head>
	<body>
	<table class="table-max">
		<tbody>
		<?php 
			$display_cnt = 0;
			for($ctr=0;$ctr<count($results);$ctr++):
				$display_cnt++;
			?>
			<tr>
				<!-- START FIRST PAYSLIP BODY TABLE -->
				<td width="550" class="with_background_logo">
					<table class="table-max">
						<tr>
							<td colspan="3" class="td-border-light-top td-border-light-left f-size-9"><b>DEPARTMENT OF HEALTH</b></td>
							<td colspan="4" class="td-border-light-top f-size-9"><b><?php echo $results[$ctr]['header']['employee_name'];?></b></td>
							<td class="td-border-light-top td-border-light-right f-size-9 align-r"><?php echo $results[$ctr]['date'];?></td>
						</tr>
						<tr>
							<td colspan="3" class="td-border-light-bottom td-border-light-left f-size-8 bold"><i><?php echo ucwords(strtolower($results[$ctr]['header']['office_name']));?></i></td>
							<td colspan="6" class="td-border-light-bottom f-size-8 td-border-light-right"><?php echo $results[$ctr]['header']['position_name'];?>
								(<span class="f-size-7"><i> SG: <?php echo $results[$ctr]['header']['salary_grade'];?></i></span>
								<span class="f-size-7"><i> Step: <?php echo $results[$ctr]['header']['pay_step'];?></i></span>)
							</td>			
						</tr>
						<tr>
							<td colspan="2" class="td-border-light-bottom td-border-light-left f-size-9 align-c">COMPENSATIONS</td>
							<td colspan="4" class="td-border-light-left td-border-light-bottom f-size-9 align-c">DEDUCTIONS</td>
							<td colspan="2" class="td-border-light-left td-border-light-right f-size-9 align-c"> <br></td>
						</tr>
						<tr>
							<td colspan="2" rowspan="3" class="td-border-light-bottom td-border-light-left f-size-9">
								<table width="100%">
									<tbody>
										<?php 
										foreach ($results[$ctr]['compensations'] as $compensation):?>
										<tr>
											<td width="100%" class="f-size-9"><?php echo $compensation['compensation_code'];?></td>
											<td width="50%" class="align-r f-size-9"><?php echo ISSET($compensation['amount'])? number_format($compensation['amount'], 2) : "0.00";?></td>
										</tr>
										<?php 
											if($compensation['less_absence_flag'] == "Y"):?>
										<tr>
											<td width="100%" class="f-size-9">less</td>
											<td width="50%" class="align-r f-size-9"><?php echo ISSET($compensation['less_amount'])? number_format($compensation['less_amount'], 2) : "0.00";?></td>
										</tr>
										<?php 
											endif;
										endforeach;
										?>
									</tbody>
									
								</table>
							</td>
							<td colspan="2" rowspan="3" class="td-border-light-bottom td-border-light-left f-size-9" width="130">
								<table width="100%">
									<tbody>
										<?php foreach($results[$ctr]['deduction_1'] as $index => $deduct):?>
											<tr>
												<td width="65" class="f-size-9"><?php echo $deduct['deduction_code'];?> <?php echo ISSET($deduct['paid_count'])?$deduct['paid_count'] : "(0)" ?></td>
												<td width="65" class="align-r f-size-9"><?php echo ISSET($deduct['amount'])? number_format($deduct['amount'], 2) : "0.00";?></td>
											</tr>
										<?php endforeach;?>	
									</tbody>
									
								</table>
							</td>			
							<td colspan="2" rowspan="3" class="td-border-light-bottom td-border-light-left" width="130">
								<table width="100%">
									<tbody>
										<?php foreach($results[$ctr]['deduction_2'] as $index => $deduct):?>
											<tr>
												<td width="65" class="f-size-9"><?php echo $deduct['deduction_code'];?> <?php echo ISSET($deduct['paid_count'])?$deduct['paid_count']: "(0)" ?></td>
												<td width="65" class="align-r f-size-9"><?php echo ISSET($deduct['amount'])? number_format($deduct['amount'], 2) : "0.00";?></td>
											</tr>
										<?php endforeach;?>	
									</tbody>
									
								</table>
							</td>			
							<td colspan="2" class="td-border-light-bottom td-border-light-left td-border-light-right">
								<table width="100%">
									<tbody>
										<tr>
											<td width="50%" class="f-size-9">GROSS INCOME</td>
											<td width="50%" class="align-r f-size-9"><?php echo number_format($results[$ctr]['header']['total_income'], 2);?></td>
										</tr>
										<tr>
											<td width="50%" class="f-size-9">DEDUCTIONS (-)</td>
											<td width="50%" class="align-r f-size-9"><?php echo number_format($results[$ctr]['header']['total_deductions'], 2);?></td>
										</tr>
										<tr>
											<td width="50%" class="f-size-9">NET PAY</td>
											<td width="50%" class="align-r f-size-9"><?php echo number_format($results[$ctr]['header']['net_pay'], 2);?></td>
										</tr>
										<tr>
											<td colspan="2"><br></td>
										</tr>
										<?php foreach($results[$ctr]['payouts'] as $index => $amount):?>
										<tr>
											<td width="50%" class="f-size-9">HALF <?php echo $index + 1;?></td>
											<td width="50%" class="align-r f-size-9"><?php echo number_format($amount, 2);?></td>
										</tr>
										<?php endforeach;?>	
									</tbody>
									
								</table>
							</td>			
						</tr>
						<tr>
							<td colspan="2" class="td-border-light-bottom td-border-light-left td-border-light-right">
								<table width="100%">
									<tbody>
										<tr>
											<td width="50%" class="f-size-9">Rate/month</td>
											<td width="70%" class="align-r f-size-9"><?php echo number_format($results[$ctr]['header']['basic_amount'],2);?></td>
										</tr>
										<!-- <tr>
											<td width="50%">Basic Salary</td>
											<td width="70%" class="align-r f-size-9">
											<?php echo number_format($results[$ctr]['basic_total'],2);?>
											</td>
										</tr> -->
										<tr>
											<td width="50%" class="f-size-9">Other Compen</td>
											<td width="70%" class="align-r f-size-9">
											<?php echo number_format($results[$ctr]['other_comp_total'],2);?>
											</td>
										</tr>
										<tr>
											<td width="50%" class="f-size-9">Statutory Ded</td>
											<td width="70%" class="align-r f-size-9">
											<?php echo number_format($results[$ctr]['stat_ded_total'],2);?>
											</td>
										</tr>
										<tr>
											<td width="50%" class="f-size-9">Other Ded</td>
											<td width="70%" class="align-r f-size-9">
											<?php echo number_format($results[$ctr]['other_ded_total'],2);?>
											</td>
										</tr>

									</tbody>
									
								</table>
							</td>
						</tr>
						<tr>
							<td colspan="2" class="td-border-light-bottom td-border-light-left td-border-light-right">
								<table width="100%">
									<tbody>
										<tr>
											<td width="50%" class="f-size-9">AGENCY ID</td>
											<td width="50%" class="align-r f-size-9"><?php echo $results[$ctr]['header']['agency_employee_id']?></td>
										</tr>
										<tr>
											<td width="50%">TIN</td>
											<td width="50%" class="align-r f-size-9">
											<?php 
												$id     = $results[$ctr]['id'][TIN_TYPE_ID]['identification_value'];
												$format = $results[$ctr]['id'][TIN_TYPE_ID]['format'];
												echo !EMPTY($id)? format_identifications($id,$format): "N/A";
											?>
											</td>
										</tr>
										<tr>
											<td width="50%" class="f-size-9">GSIS</td>
											<td width="50%" class="align-r f-size-9">
											<?php 
												$id     = $results[$ctr]['id'][GSIS_TYPE_ID]['identification_value'];
												$format = $results[$ctr]['id'][GSIS_TYPE_ID]['format'];
												echo !EMPTY($id)? format_identifications($id,$format): "N/A";
											?>
											</td>
										</tr>
										<tr>
											<td width="50%" class="f-size-9">HMDF</td>
											<td width="50%" class="align-r f-size-9">
											<?php 
												$id     = $results[$ctr]['id'][PAGIBIG_TYPE_ID]['identification_value'];
												$format = $results[$ctr]['id'][PAGIBIG_TYPE_ID]['format'];
												echo !EMPTY($id)? format_identifications($id,$format): "N/A";
											?>
											</td>
										</tr>
										<tr>
											<td width="50%" class="f-size-9">PHIC</td>
											<td width="50%" class="align-r f-size-9">
											<?php 
												$id     = $results[$ctr]['id'][PHILHEALTH_TYPE_ID]['identification_value'];
												$format = $results[$ctr]['id'][PHILHEALTH_TYPE_ID]['format'];
												echo !EMPTY($id)? format_identifications($id,$format): "N/A";
											?>
											</td>
										</tr>

									</tbody>
									
								</table>
							</td>
						</tr>
					</table>
				</td>
				<td><br></td>
				<!-- END FIRST PAYSLIP BODY TABLE -->
				<?php $ctr++;?>
				<!-- START SECOND PAYSLIP BODY TABLE -->
				<td width="550" class="<?php echo (isset($results[$ctr]) ? 'with_background_logo':'')?>">
				<?php if(isset($results[$ctr])):?>
					<table class="table-max">
						<tr>
							<td colspan="3" class="td-border-light-top td-border-light-left f-size-9"><b>DEPARTMENT OF HEALTH</b></td>
							<td colspan="4" class="td-border-light-top f-size-9"><b><?php echo $results[$ctr]['header']['employee_name'];?></b></td>
							<td class="td-border-light-top td-border-light-right f-size-9 align-r"><?php echo $results[$ctr]['date'];?></td>
						</tr>
						<tr>
							<td colspan="3" class="td-border-light-bottom td-border-light-left f-size-8 bold"><i><?php echo ucwords(strtolower($results[$ctr]['header']['office_name']));?></i></td>
							<td colspan="6" class="td-border-light-bottom f-size-8 td-border-light-right"><?php echo $results[$ctr]['header']['position_name'];?>
								(<span class="f-size-7"><i> SG: <?php echo $results[$ctr]['header']['salary_grade'];?></i></span>
								<span class="f-size-7"><i> Step: <?php echo $results[$ctr]['header']['pay_step'];?></i></span>)
							</td>			
						</tr>
						<tr>
							<td colspan="2" class="td-border-light-bottom td-border-light-left f-size-9 align-c">COMPENSATIONS</td>
							<td colspan="4" class="td-border-light-left td-border-light-bottom f-size-9 align-c">DEDUCTIONS</td>
							<td colspan="2" class="td-border-light-left td-border-light-right f-size-9 align-c"> <br></td>
						</tr>
						<tr>
							<td colspan="2" rowspan="3" class="td-border-light-bottom td-border-light-left f-size-9">
								<table width="100%">
									<tbody>
										<?php 
										foreach ($results[$ctr]['compensations'] as $compensation):?>
										<tr>
											<td width="100%" class="f-size-9"><?php echo $compensation['compensation_code'];?></td>
											<td width="50%" class="align-r f-size-9"><?php echo ISSET($compensation['amount'])? number_format($compensation['amount'], 2) : "0.00";?></td>
										</tr>
										<?php 
											if($compensation['less_absence_flag'] == "Y"):?>
										<tr>
											<td width="100%" class="f-size-9">less</td>
											<td width="50%" class="align-r f-size-9"><?php echo ISSET($compensation['less_amount'])? number_format($compensation['less_amount'], 2) : "0.00";?></td>
										</tr>
										<?php 
											endif;
										endforeach;
										?>
									</tbody>
									
								</table>
							</td>
							<td colspan="2" rowspan="3" class="td-border-light-bottom td-border-light-left f-size-9" width="130">
								<table width="100%">
									<tbody>
										<?php foreach($results[$ctr]['deduction_1'] as $index => $deduct):?>
											<tr>
												<td width="65" class="f-size-9"><?php echo $deduct['deduction_code'];?> <?php echo ISSET($deduct['paid_count'])?$deduct['paid_count'] : "(0)" ?></td>
												<td width="65" class="align-r f-size-9"><?php echo ISSET($deduct['amount'])? number_format($deduct['amount'], 2) : "0.00";?></td>
											</tr>
										<?php endforeach;?>	
									</tbody>
									
								</table>
							</td>			
							<td colspan="2" rowspan="3" class="td-border-light-bottom td-border-light-left" width="130">
								<table width="100%">
									<tbody>
										<?php foreach($results[$ctr]['deduction_2'] as $index => $deduct):?>
											<tr>
												<td width="65" class="f-size-9"><?php echo $deduct['deduction_code'];?> <?php echo ISSET($deduct['paid_count'])?$deduct['paid_count']: "(0)" ?></td>
												<td width="65" class="align-r f-size-9"><?php echo ISSET($deduct['amount'])? number_format($deduct['amount'], 2) : "0.00";?></td>
											</tr>
										<?php endforeach;?>	
									</tbody>
									
								</table>
							</td>			
							<td colspan="2" class="td-border-light-bottom td-border-light-left td-border-light-right">
								<table width="100%">
									<tbody>
										<tr>
											<td width="50%" class="f-size-9">GROSS INCOME</td>
											<td width="50%" class="align-r f-size-9"><?php echo number_format($results[$ctr]['header']['total_income'], 2);?></td>
										</tr>
										<tr>
											<td width="50%" class="f-size-9">DEDUCTIONS (-)</td>
											<td width="50%" class="align-r f-size-9"><?php echo number_format($results[$ctr]['header']['total_deductions'], 2);?></td>
										</tr>
										<tr>
											<td width="50%" class="f-size-9">NET PAY</td>
											<td width="50%" class="align-r f-size-9"><?php echo number_format($results[$ctr]['header']['net_pay'], 2);?></td>
										</tr>
										<tr>
											<td colspan="2"><br></td>
										</tr>
										<?php foreach($results[$ctr]['payouts'] as $index => $amount):?>
										<tr>
											<td width="50%" class="f-size-9">HALF <?php echo $index + 1;?></td>
											<td width="50%" class="align-r f-size-9"><?php echo number_format($amount, 2);?></td>
										</tr>
										<?php endforeach;?>	
									</tbody>
									
								</table>
							</td>			
						</tr>
						<tr>
							<td colspan="2" class="td-border-light-bottom td-border-light-left td-border-light-right">
								<table width="100%">
									<tbody>
										<tr>
											<td width="50%" class="f-size-9">Rate/month</td>
											<td width="70%" class="align-r f-size-9"><?php echo number_format($results[$ctr]['header']['basic_amount'],2);?></td>
										</tr>
										<!-- <tr>
											<td width="50%">Basic Salary</td>
											<td width="70%" class="align-r f-size-9">
											<?php echo number_format($results[$ctr]['basic_total'],2);?>
											</td>
										</tr> -->
										<tr>
											<td width="50%" class="f-size-9">Other Compen</td>
											<td width="70%" class="align-r f-size-9">
											<?php echo number_format($results[$ctr]['other_comp_total'],2);?>
											</td>
										</tr>
										<tr>
											<td width="50%" class="f-size-9">Statutory Ded</td>
											<td width="70%" class="align-r f-size-9">
											<?php echo number_format($results[$ctr]['stat_ded_total'],2);?>
											</td>
										</tr>
										<tr>
											<td width="50%" class="f-size-9">Other Ded</td>
											<td width="70%" class="align-r f-size-9">
											<?php echo number_format($results[$ctr]['other_ded_total'],2);?>
											</td>
										</tr>

									</tbody>
									
								</table>
							</td>
						</tr>
						<tr>
							<td colspan="2" class="td-border-light-bottom td-border-light-left td-border-light-right">
								<table width="100%">
									<tbody>
										<tr>
											<td width="50%" class="f-size-9">AGENCY ID</td>
											<td width="50%" class="align-r f-size-9"><?php echo $results[$ctr]['header']['agency_employee_id']?></td>
										</tr>
										<tr>
											<td width="50%">TIN</td>
											<td width="50%" class="align-r f-size-9">
											<?php 
												$id     = $results[$ctr]['id'][TIN_TYPE_ID]['identification_value'];
												$format = $results[$ctr]['id'][TIN_TYPE_ID]['format'];
												echo !EMPTY($id)? format_identifications($id,$format): "N/A";
											?>
											</td>
										</tr>
										<tr>
											<td width="50%" class="f-size-9">GSIS</td>
											<td width="50%" class="align-r f-size-9">
											<?php 
												$id     = $results[$ctr]['id'][GSIS_TYPE_ID]['identification_value'];
												$format = $results[$ctr]['id'][GSIS_TYPE_ID]['format'];
												echo !EMPTY($id)? format_identifications($id,$format): "N/A";
											?>
											</td>
										</tr>
										<tr>
											<td width="50%" class="f-size-9">HMDF</td>
											<td width="50%" class="align-r f-size-9">
											<?php 
												$id     = $results[$ctr]['id'][PAGIBIG_TYPE_ID]['identification_value'];
												$format = $results[$ctr]['id'][PAGIBIG_TYPE_ID]['format'];
												echo !EMPTY($id)? format_identifications($id,$format): "N/A";
											?>
											</td>
										</tr>
										<tr>
											<td width="50%" class="f-size-9">PHIC</td>
											<td width="50%" class="align-r f-size-9">
											<?php 
												$id     = $results[$ctr]['id'][PHILHEALTH_TYPE_ID]['identification_value'];
												$format = $results[$ctr]['id'][PHILHEALTH_TYPE_ID]['format'];
												echo !EMPTY($id)? format_identifications($id,$format): "N/A";
											?>
											</td>
										</tr>

									</tbody>
									
								</table>
							</td>
						</tr>
					</table>
				<?php endif;?>
				</td>
				<!-- END SECOND PAYSLIP BODY TABLE -->
			</tr>
			<tr><td colspan="3" class="f-size-7">&nbsp;</td></tr>
		<?php endfor; ?>
		</tbody>
	</table>
		
	</body>
</html>
