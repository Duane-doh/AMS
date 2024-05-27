<!DOCTYPE html>
<html>
<head>
	<title>General Payroll Alphalist for JO</title>
	<link rel="stylesheet" href="<?php echo base_url().PATH_CSS ?>reports.css" type="text/css" />
</head>
<body>
	<?php $total_th = 13 + count($compensation_hdr) + count($regular_deduction_hdr) + count($deduction_groups)?>
	<table class="table-max">
	<thead>
		<tr>
			<td class="align-c" colspan="<?php echo $total_th;?>">
				<table class="f-size-14">
					<tbody>
						<tr>
							<td width="150"><img src="<?php echo base_url().PATH_IMG ?>doh_logo.png" width=90 height=90></img></td>
							<td class="align-c" width="600"><?php echo nbs(5)?>Republic of the Philippines<br><?php echo nbs(5)?>DEPARTMENT OF HEALTH
							<br><span class="f-size-12"><?php echo !EMPTY($office_name['name']) ? $office_name['name'] : '';?></span>
							<br><span class="f-size-12">FOR THE PERIOD OF <?php echo $payroll_period_text;?></span>
							</td>
							<td width="150">&nbsp;</td>
						</tr>
					</tbody>
				</table>
			</td>
		</tr>
		<tr>
			<td height="5"></td>
		</tr>
	</thead>
	</table>
	<table>
	<thead>
		<tr class="cont-5">
			<th class="td-border-4 align-c f-size-12">NO.</th>
			<th class="td-border-2 align-c f-size-12">
				<table>
					<tr><th class="align-c f-size-12 td-border-bottom" width="100%" colspan="2">NAME</th></tr>
					<tr><th class="align-c f-size-12 td-border-bottom" width="100%" colspan="2">POSITION / SG</th></tr>
					<tr>
						<th class="td-border-right f-size-12 align-c" width="120px">TIN No.</th>
						<th class="f-size-12 align-c " width="120px">ATM No.</th>
					</tr>
				</table>			
			</th>

			<th class="td-border-2 align-c f-size-12">
				<table>
					<tr><th class="align-c f-size-12 td-border-bottom" width="100%">RATE/MONTH</th></tr>
					<tr><th class="align-c f-size-12 td-border-bottom" width="100%">Basic Rate</th></tr>
					<tr>
						<th class="align-c f-size-12" width="100px">
						<?php echo !isset($comp_prem['generated_rate']) ? round($comp_prem['active_rate'],0) : round($comp_prem['generated_rate'],0); ?>% Premium
						</th>
					</tr>
				</table>			
			</th>
			
			<th class="td-border-2 align-c f-size-12">NO. OF<br>WORKING<br>DAYS (in<br>a month)</th>
			<th class="td-border-2 align-c f-size-12">RATE/DAY</th>
			<th class="td-border-2 align-c f-size-12">GROSS SALARY<br>FOR THE PERIOD</th>
			<!--
			<th class="td-border-2 align-c f-size-12">
				<table>
					<tr><th class="align-c f-size-12 td-border-bottom" width="100%" height="30px">Absences/Tardiness/Undertime</th></tr>
					<tr><th class="align-c f-size-12" width="100%">Day/Hr./Min.</th></tr>
				</table>			
			</th>
			
			<th class="td-border-2 align-c f-size-12">
				<table>
					<tr><th class="align-c f-size-12 td-border-bottom" width="100%" height="30px">RATE</th></tr>
					<tr><th class="align-c f-size-12" width="100%">Day/Hr./Min.</th></tr>
				</table>			
			</th>
			
			<th class="td-border-2 align-c f-size-12">SUB-TOTAL</th>
			-->
			<th class="td-border-2 align-c f-size-12" colspan="3">
				<table class="table-max">
					<tr><th class="align-c f-size-12 td-border-bottom" width="100%" colspan="3">ABSENCES / TARDINESS / UNDERTIME</th></tr>
					<tr>
						<th class="td-border-right align-c f-size-12">
							<table>
								<tr><th class="align-c f-size-12 td-border-bottom" width="100%" height="23px">NUMBER</th></tr>
								<tr><th class="align-c f-size-12" width="100%" height="23px">Day/Hr./Min.</th></tr>
							</table>
						</th>
						<th class="td-border-right align-c f-size-12">
							<table>
								<tr><th class="align-c f-size-12 td-border-bottom" width="100%" height="23px">RATE</th></tr>
								<tr><th class="align-c f-size-12" width="100%" height="23px">Day/Hr./Min.</th></tr>
							</table>
						</th>
						<th class="align-c f-size-12">
							<table width="85px">
								<tr><th class="align-c f-size-12" width="100%" height="23px">SUB-TOTAL</th></tr>
							</table>
						</th>
					</tr>
				</table>
			</th>
			
			<!-- COMPENSATION HEADERS -->
			<?php 
				foreach ($compensation_hdr as $com_hdr):
					if($com_hdr['report_short_code'] != $prem_code['sys_param_value'])
					{
			?>
			<th class="td-border-2 align-c f-size-12"><?php echo $com_hdr['report_short_code']?></th>
			<?php 	}
				endforeach; 
			?>
			
			<th class="td-border-2 align-c f-size-12">Underpayment</th>
			<th class="td-border-2 align-c f-size-12">NET SALARY</th>
			
			<!-- DEDUCTIONS -->
			<?php $ded_width = ((count($deduction_groups))+(count($regular_deduction_hdr)/2)-1); ?>
			<th class="td-border-2 align-c f-size-12" colspan="<?php echo $ded_width; ?>">
				<table class="table-max">
					<tr><th class="align-c f-size-12 td-border-bottom" width="100%" colspan="<?php echo $ded_width; ?>">DEDUCTIONS</th></tr>
					<tr>
						<th class="td-border-right align-c f-size-12">
							<table width="85px">
								<!-- TAX REGULAR DEDUCTIONS HEADERS -->
								<?php 
									end($regular_deduction_hdr);
									foreach ($regular_deduction_hdr as $key => $ded_hdr): 
										if ($ded_hdr['deduction_id'] != DEDUC_BIR_EIGHT)
										{
										$key === key($regular_deduction_hdr) ? $class="" : $class=" td-border-bottom";
								?>
										<tr><th class="align-c f-size-12<?php echo $class;?>" width="100%" height="23px"><?php echo $ded_hdr['deduction_name']; ?></th></tr>
								<?php 
										}
									endforeach;
								?>
							</table>
						</th>
						
						<!-- DEDUCTIONS IN GROUP HEADERS -->
						<?php 
						foreach ($deduction_groups as $ded_group): 
							$ded_group === end($deduction_groups) ? $class="" : $class="td-border-right ";
						?>
							<th class="<?php echo $class;?>align-c f-size-12">
								<table width="86px">
									<?php
									end($ded_group);
									foreach($ded_group as $key=> $ded):
										$key === key($ded_group) ? $class="" : $class=" td-border-bottom";
										$key === 'OTHER DEDUCTION' ? $deduction_name = "Other Deduction" : $deduction_name = $ded['deduction_name'];
									?>
									<tr><th class="align-c f-size-12<?php echo $class;?>" width="100%" height="23px"><?php echo $deduction_name; ?></th></tr>
									
									<?php endforeach; ?>
								</table>			
							</th>
						<?php endforeach;?>
					</tr>
				</table>
			</th>
			
			<!--
			<th class="td-border-2 align-c f-size-12" colspan="4">
				<table class="table-max">
					<tr><th class="align-c f-size-12 td-border-bottom" width="100%" colspan="4">DEDUCTIONS</th></tr>
					<tr>
						<th class="align-c f-size-12 td-border-bottom td-border-right">EWT</th>
						<th class="align-c f-size-12 td-border-bottom td-border-right">PAG-IBIG</th>
						<th class="align-c f-size-12 td-border-bottom td-border-right">PHIC</th>
						<th class="align-c f-size-12 td-border-bottom">Overpayment</th>
					</tr>
					<tr>
						<th class="align-c f-size-12 td-border-right">GMP</th>
						<th class="align-c f-size-12 td-border-right">MP2</th>
						<th class="align-c f-size-12 td-border-right">SSS</th>
						<th class="align-c f-size-12">Other Deduction</th>
					</tr>
				</table>
			</th>
			-->
			
			<th class="td-border-2 align-c f-size-12">NET PAY</th>
			<th class="td-border-2 align-c f-size-12">REMARKS</th>
		</tr>
	</thead>
	<tbody>
	<?php 
		$page_month_rate   = 0;
		$page_gross        = 0;
		$page_sub          = 0;
		$page_ded_other    = 0;
		$page_underpay     = 0;
		$page_net_salary   = 0;
		$page_net_pay      = 0;
		$page_compensation = array();
		$page_ded_regular  = array();
		$page_ded_group    = array();

		$total_month_rate   = 0;
		$total_gross        = 0;
		$total_sub          = 0;
		$total_ded_other    = 0;
		$total_underpay	    = 0;
		$total_net_salary   = 0;
		$total_net_pay      = 0;
		$total_compensation = array();
		$total_ded_regular  = array();
		$total_ded_group    = array();
	?>
		<?php foreach ($header as $hdr):?>
		<tr class="cont-5">
			<!-- NO. -->
			<td class="td-border-4 align-c f-size-12"><?php echo ++$ctr;?></td>
			<td class="td-border-bottom td-border-right f-size-12" width="200px">
				<table class="table-max">
					<tr><td class="f-size-12" height="30px" colspan="2"><strong><?php echo $hdr['full_name'].'</strong><br>'.$hdr['position_name'];?> / SG<?php echo $hdr['salary_grade'];?></td></tr>
					<tr>
						<td class="td-border-top td-border-right f-size-12" width="120px" height="30px">TIN No.<br><?php echo $hdr['tin'];?></td>
						<td class="td-border-top f-size-12" width="120px">ATM No.<br><?php echo $hdr['atm_no'];?></td>
					</tr>
				</table>
			</td>
			<!-- Rate/Month -->
			<td class="td-border-bottom td-border-right align-r f-size-12">
				<?php
					$basic_amount = $records[$hdr['employee_id']]['basic_amount'];
					$premium = $basic_amount*($records[$hdr['employee_id']]['premium']/100);
					$basic_wprem = $basic_amount+$premium;

					$page_month_rate += $basic_wprem;
					$total_month_rate += $basic_wprem;
				?>
				<table class="table-max">
					<tr><td class="align-r f-size-12" height="15px"><strong><?php echo number_format($basic_wprem, 2); ?></strong></td></tr>
					<tr><td class="align-r f-size-12" height="15px"><?php echo number_format($basic_amount, 2); ?></td></tr>
					<tr><td class="align-r f-size-12" height="15px"><?php echo number_format($premium, 2); ?></td></tr>
				</table>
			</td>
			<!-- NO. OF WORKING DAYS (in a month) -->
			<td class="td-border-bottom td-border-right align-c f-size-12" width="75px"><?php echo $working_days_in_period . '/' . $working_days_in_month;?></td>
			<!-- RATE/DAY -->
			<td class="td-border-bottom td-border-right align-r f-size-12" width="70px">
				<?php 
					$rate_day_wprem = $basic_wprem/$working_days_in_month;
					echo number_format($rate_day_wprem, 2);
				?>
			</td>
			<!-- GROSS SALARY FOR THE PERIOD -->
			<td class="td-border-bottom td-border-right align-r f-size-12">
				<?php 
					$orig_amount = round($working_days_in_period * $rate_day_wprem,2);
					$page_gross += $orig_amount;
					$total_gross += $orig_amount;
					echo number_format($orig_amount, 2);
				?>
			</td>
			<!-- Absences/ Undertime -->
			<td class="td-border-bottom td-border-right align-c f-size-12" width="90px">
				<table class="table-max">
					<tr>
						<td class="align-c f-size-12">D</td>
						<td class="align-r f-size-12" width="100%"><?php echo $less_amounts[$hdr['employee_id']]['day'];?></td>
					</tr>
					<tr>
						<td class="align-c f-size-12">H</td>
						<td class="align-r f-size-12" width="100%"><?php echo $less_amounts[$hdr['employee_id']]['hour'];?></td>
					</tr>
					<tr>
						<td class="align-c f-size-12">M</td>
						<td class="align-r f-size-12" width="100%"><?php echo $less_amounts[$hdr['employee_id']]['minute'];?></td>
					</tr>
				</table>
			</td>
			<!-- RATE -->
			<td class="td-border-bottom td-border-right align-r f-size-12">
				<table class="table-max">
					<tr>
						<td class="align-c f-size-12">D</td>
						<td class="align-r f-size-12" width="100%"><?php echo number_format($rates[$hdr['employee_id']]['rate_day'], 4);?></td>
					</tr>
					<tr>
						<td class="align-c f-size-12">H</td>
						<td class="align-r f-size-12" width="100%"><?php echo number_format($rates[$hdr['employee_id']]['rate_hour'], 4);?></td>
					</tr>
					<tr>
						<td class="align-c f-size-12">M</td>
						<td class="align-r f-size-12" width="100%"><?php echo number_format($rates[$hdr['employee_id']]['rate_minute'], 4);?></td>
					</tr>
				</table>
			</td>
			<!-- SUB-TOTAL -->
			<td class="td-border-bottom td-border-right align-r f-size-12" width="90px">
				<table class="table-max">
					<tr>
						<td class="align-r f-size-12">
						<?php 
							$page_sub += round($less_amounts[$hdr['employee_id']]['rate_day'],2); 
							$total_sub += round($less_amounts[$hdr['employee_id']]['rate_day'],2); 
							echo number_format($less_amounts[$hdr['employee_id']]['rate_day'], 2);
						?>
						</td>					
					</tr>
					<tr>
						<td class="align-r f-size-12">
						<?php 
							$page_sub += round($less_amounts[$hdr['employee_id']]['rate_hour'],2); 
							$total_sub += round($less_amounts[$hdr['employee_id']]['rate_hour'],2); 
							echo number_format($less_amounts[$hdr['employee_id']]['rate_hour'], 2);
						?>
						</td>
					</tr>
					<tr>
						<td class="align-r f-size-12">
						<?php 
							$page_sub += round($less_amounts[$hdr['employee_id']]['rate_minute'], 2); 
							$total_sub += round($less_amounts[$hdr['employee_id']]['rate_minute'], 2); 
							echo number_format($less_amounts[$hdr['employee_id']]['rate_minute'], 2);
						?>
						</td>
					</tr>
				</table>
			</td>
			<!-- COMPENSATION -->
			<?php
				foreach ($compensation_hdr as $com):
				if(strtoupper($com_hdr['report_short_code']) != 'PREM')
				{
			?>
				<td class="td-border-bottom td-border-right align-r f-size-12">
					<?php 
					$page_compensation[$com['compensation_id']] += $compensation_amounts[$hdr['employee_id']][$com['compensation_id']]['amount'];
					$total_compensation[$com['compensation_id']] += $compensation_amounts[$hdr['employee_id']][$com['compensation_id']]['amount'];
					echo ISSET($compensation_amounts[$hdr['employee_id']][$com['compensation_id']]['amount']) ? $compensation_amounts[$hdr['employee_id']][$com['compensation_id']]['amount'] : "0.00";
					?>
					<br><?php echo $compensation_amounts[$hdr['employee_id']][$com['compensation_id']]['rate']?>
				</td>
			<?php 
				}
				endforeach;
			?>
			<!-- UNDERPAYMENT -->
			<td class="td-border-bottom td-border-right align-r f-size-12 ">
				<?php
					$underpay 		  = 0;
					$page_underpay   += 0;
					$total_underpay  += 0;
					echo number_format($underpay, 2);
				?>
			</td>
			<!-- NET SALARY -->
			<td class="td-border-bottom td-border-right align-r f-size-12" width="90px">
				<?php
					
					$net_salary 		= $bs[$hdr['employee_id']]['amount'];
					$page_net_salary   += $net_salary;
					$total_net_salary  += $net_salary;
					echo number_format($net_salary, 2);
				?>
			</td>
			<!-- TAX REGULAR DEDUCTIONS HEADERS -->
			<td class="td-border-2 align-r f-size-12" width="85px">
				<table class="table-max">
					<?php
						$tax_remarks = '';
						foreach ($regular_deduction_hdr as $ded):
							$page_ded_regular[$ded['deduction_id']]  += $regular_deduction_amt[$hdr['employee_id']][$ded['deduction_id']]['amount'];
							$total_ded_regular[$ded['deduction_id']] += $regular_deduction_amt[$hdr['employee_id']][$ded['deduction_id']]['amount'];

							$tax_deduc 		= ISSET($regular_deduction_amt[$hdr['employee_id']][$ded['deduction_id']]['amount'])? $regular_deduction_amt[$hdr['employee_id']][$ded['deduction_id']]['amount']: "0.00";
							$percent_deduc  = number_format((($tax_deduc/$net_salary)*100), 0);
							
							
								switch($ded['deduction_id'])
								{	
									case DEDUC_BIR_EIGHT:
										if(ISSET($regular_deduction_amt[$hdr['employee_id']][$ded['deduction_id']]['amount']))
											$tax_remarks .= ($tax_remarks == '') ? '8% Income Tax' : ', 8% Income Tax';
											
										break;
									case DEDUC_BIR_EWT:
										?><tr><td class="align-r f-size-12"><?php echo number_format($tax_deduc, 2); ?></td></tr><?php
										
										if(ISSET($regular_deduction_amt[$hdr['employee_id']][$ded['deduction_id']]['amount']))
											$tax_remarks .= ($tax_remarks == '') ? 'EWT '.$percent_deduc.'%' : ', EWT '.$percent_deduc.'%';
										break;
									case DEDUC_BIR_VAT:
										?><tr><td class="align-r f-size-12"><?php echo number_format($tax_deduc, 2); ?></td></tr><?php
										
										if(ISSET($regular_deduction_amt[$hdr['employee_id']][$ded['deduction_id']]['amount']))
											$tax_remarks .= ($tax_remarks == '') ? 'GMP '.$percent_deduc.'%' : ', GMP '.$percent_deduc.'%';
										break;
								
							}
						endforeach; 
					?>
				</table>			
			</td>
			<!-- Regular Deductions -->
			<?php
			foreach ($deduction_groups as $group_key=>$ded_group):?>
			<td class="td-border-2 align-r f-size-12" width="85px">
				<table class="table-max">
					<?php 
					$breakdown_remarks = '';
					foreach($ded_group as $ded_key=> $ded):
						$page_ded_group[$group_key] += $ded_amt_in_group[$hdr['employee_id']][$group_key][$ded['deduction_id']]['amount'];
						$total_ded_group[$group_key] += $ded_amt_in_group[$hdr['employee_id']][$group_key][$ded['deduction_id']]['amount'];


						if($ded['deduction_type_flag'] == DEDUCTION_TYPE_FLAG_SCHEDULED AND $ded['deduction_id'] == DEDUC_OVERPAY_JO)
						{
							$breakdown_remarks_dtl = $ded['report_short_code'].'(';

							foreach($ded_amt_dtl_sched[$ded['deduction_id']][$hdr['employee_id']] as $ded_dtl_key=> $ded_dtl):

								$breakdown_remarks_dtl .= ($ded_dtl_key != 0) ? ', ' : '';
								if($ded_dtl['deduction_detail_type_code'])
								$breakdown_remarks_dtl .= $ded_dtl['deduction_detail_type_code'].':'.$ded_dtl['amount'];
								else
								$breakdown_remarks_dtl .= $ded_dtl['amount'];
								
							endforeach;
							$breakdown_remarks_dtl .= ')';
						}
						if(!ISSET($ded_amt_dtl_sched[$ded['deduction_id']][$hdr['employee_id']][0]))
							$breakdown_remarks_dtl = '';

						if($breakdown_remarks_dtl)
						$breakdown_remarks .= $breakdown_remarks_dtl.'<br>';
					?>
						<tr><td class="align-r f-size-12"><?php echo ISSET($ded_amt_in_group[$hdr['employee_id']][$group_key][$ded['deduction_id']]['amount']) ? number_format($ded_amt_in_group[$hdr['employee_id']][$group_key][$ded['deduction_id']]['amount'], 2) : "0.00";?></td></tr>
					<?php endforeach;

					?>
				</table>			
			</td>
			<?php endforeach;?>
			<!-- Other Deduction -->
		<!-- 
			<td class="td-border-bottom td-border-right align-r f-size-12">
			<?php 
		//	$page_ded_other += $other_deduction_amt[$hdr['employee_id']];
		//	$total_ded_other += $other_deduction_amt[$hdr['employee_id']];
		//	echo ISSET($other_deduction_amt[$hdr['employee_id']])? number_format($other_deduction_amt[$hdr['employee_id']], 2) : "0.00";?>
			</td>
		-->
			<!-- Net Pay -->
			<td class="td-border-bottom td-border-right align-r f-size-12 pad-2" width="100px">
			<?php echo number_format($records[$hdr['employee_id']]['net_pay'], 2);
					$total_net_pay += $records[$hdr['employee_id']]['net_pay'];
					$page_net_pay  += $records[$hdr['employee_id']]['net_pay'];
			?>
			</td>
			<!-- Remarks -->
			<td class="td-border-bottom td-border-right f-size-12 pad-2" width="120px">
			<?php 
				$remarks =(empty($less_amounts[$hdr['employee_id']]['remarks']) ? "FULL TIME" : $less_amounts[$hdr['employee_id']]['remarks']);
				echo $remarks;
				echo '<br/>'.$tax_remarks;
				echo '<br/>'.$breakdown_remarks;
				?>
			</td>
		</tr>
		<?php if($ctr%8 == 0 OR count($header) == $ctr):?>
			
			<tr class="cont-5">
				<th class="td-border-4 align-c f-size-12"><?php echo ($ctr%8 == 0) ? 8 :  $ctr%8?></th>
				<th class="td-border-2 align-c f-size-12">PAGE TOTAL</th>
				<th class="td-border-2 align-r f-size-12"><?php echo number_format($page_month_rate,2);?></th>
				<th class="td-border-2 align-c f-size-12" colspan="2">---</th>
				<th class="td-border-2 align-r f-size-12"><?php echo number_format($page_gross,2);?></th>
				<th class="td-border-2 align-c f-size-12" colspan="2">---</th>
				<th class="td-border-2 align-r f-size-12"><?php echo number_format($page_sub,2);?></th>
				<!-- COMPENSATION HEADERS -->
				<?php foreach ($page_compensation as $amount):?>
				<th class="td-border-2 align-r f-size-12"><?php echo number_format($amount,2);?></th>
				<?php endforeach;?>
				<th class="td-border-2 align-r f-size-12"><?php echo number_format($page_underpay,2);?></th>
				<th class="td-border-2 align-r f-size-12"><?php echo number_format($page_net_salary,2);?></th>
				
				<!-- TAX REGULAR DEDUCTIONS HEADERS -->
				<?php foreach ($page_ded_regular as $amount):
				$page_tax_deduction += $amount;
				endforeach;?>		
				<th class="td-border-2 align-r f-size-12"><?php echo number_format($page_tax_deduction,2);?></th>
				
				<!-- DEDUCTIONS IN GROUP HEADERS -->
				<?php foreach ($page_ded_group as $amount):?>
				<th class="td-border-2 align-r f-size-12"><?php echo number_format($amount,2);?></th>
				<?php endforeach;?>
				<!--
				<th class="td-border-2 align-r f-size-12"><?php //echo number_format($page_ded_other,2);?></th>
				-->
				<th class="td-border-2 align-r f-size-12"><?php echo number_format($page_net_pay,2);?></th>
				<th class="td-border-2 align-c f-size-12">---</th>
			</tr>
			<?php 
				$page_month_rate   = 0;
				$page_gross        = 0;
				$page_sub          = 0;
				$page_ded_other    = 0;
				$page_underpay	   = 0;
				$page_net_salary   = 0;
				$page_net_pay      = 0;
				$page_compensation = array();
				$page_ded_regular  = array();
				$page_ded_group    = array();
			?>
			<?php if(count($header) != $ctr):?>
			</tbody>
			</table>
			
			<pagebreak />
			<table class="table-max">
				<thead>
					<tr>
						<td class="align-c" colspan="<?php echo $total_th;?>">
							<table class="f-size-14">
								<tbody>
									<tr>
										<td width="150"><img src="<?php echo base_url().PATH_IMG ?>doh_logo.png" width=90 height=90></img></td>
										<td class="align-c" width="600"><?php echo nbs(5)?>Republic of the Philippines<br><?php echo nbs(5)?>DEPARTMENT OF HEALTH
										<br><span class="f-size-12"><?php echo !EMPTY($office_name['name']) ? $office_name['name'] : '';?></span>
										<br><span class="f-size-12">FOR THE PERIOD OF <?php echo $payroll_period_text;?></span>
										</td>
										<td width="150">&nbsp;</td>
									</tr>
								</tbody>
							</table>
						</td>
					</tr>
					<tr>
						<td height="5"></td>
					</tr>
				</thead>
			</table>
			<table>
				<thead>
					<tr class="cont-5">
						<th class="td-border-4 align-c f-size-12">NO.</th>
						<th class="td-border-2 align-c f-size-12">
							<table>
								<tr><th class="align-c f-size-12 td-border-bottom" width="100%" colspan="2">NAME</th></tr>
								<tr><th class="align-c f-size-12 td-border-bottom" width="100%" colspan="2">POSITION / SG</th></tr>
								<tr>
									<th class="td-border-right f-size-12 align-c" width="120px">TIN No.</th>
									<th class="f-size-12 align-c " width="120px">ATM No.</th>
								</tr>
							</table>			
						</th>

						<th class="td-border-2 align-c f-size-12">
							<table>
								<tr><th class="align-c f-size-12 td-border-bottom" width="100%">RATE/MONTH</th></tr>
								<tr><th class="align-c f-size-12 td-border-bottom" width="100%">Basic Rate</th></tr>
								<tr>
									<th class="align-c f-size-12" width="100px">
									<?php echo !isset($comp_prem['generated_rate']) ? round($comp_prem['active_rate'],0) : round($comp_prem['generated_rate'],0); ?>% Premium
									</th>
								</tr>
							</table>			
						</th>
						
						<th class="td-border-2 align-c f-size-12">NO. OF<br>WORKING<br>DAYS (in<br>a month)</th>
						<th class="td-border-2 align-c f-size-12">RATE/DAY</th>
						<th class="td-border-2 align-c f-size-12">GROSS SALARY<br>FOR THE PERIOD</th>
						<!--
						<th class="td-border-2 align-c f-size-12">
							<table>
								<tr><th class="align-c f-size-12 td-border-bottom" width="100%" height="30px">Absences/Tardiness/Undertime</th></tr>
								<tr><th class="align-c f-size-12" width="100%">Day/Hr./Min.</th></tr>
							</table>			
						</th>
						
						
						<th class="td-border-2 align-c f-size-12">
							<table>
								<tr><th class="align-c f-size-12 td-border-bottom" width="100%" height="30px">RATE</th></tr>
								<tr><th class="align-c f-size-12" width="100%">Day/Hr./Min.</th></tr>
							</table>			
						</th>
						
						<th class="td-border-2 align-c f-size-12">SUB-TOTAL</th>
						-->
						<th class="td-border-2 align-c f-size-12" colspan="3">
							<table class="table-max">
								<tr><th class="align-c f-size-12 td-border-bottom" width="100%" colspan="3">ABSENCES / TARDINESS / UNDERTIME</th></tr>
								<tr>
									<th class="td-border-right align-c f-size-12">
										<table>
											<tr><th class="align-c f-size-12 td-border-bottom" width="100%" height="23px">NUMBER</th></tr>
											<tr><th class="align-c f-size-12" width="100%" height="23px">Day/Hr./Min.</th></tr>
										</table>
									</th>
									<th class="td-border-right align-c f-size-12">
										<table>
											<tr><th class="align-c f-size-12 td-border-bottom" width="100%" height="23px">RATE</th></tr>
											<tr><th class="align-c f-size-12" width="100%" height="23px">Day/Hr./Min.</th></tr>
										</table>
									</th>
									<th class="align-c f-size-12">
										<table width="85px">
											<tr><th class="align-c f-size-12" width="100%" height="23px">SUB-TOTAL</th></tr>
										</table>
									</th>
								</tr>
							</table>
						</th>
						
						<!-- COMPENSATION HEADERS -->
						<?php 
							foreach ($compensation_hdr as $com_hdr):
							if(strtoupper($com_hdr['report_short_code']) != 'PREM'){
						?>
						<th class="td-border-2 align-c f-size-12"><?php echo $com_hdr['report_short_code']?></th>
						<?php 
							} 
							endforeach;
						?>
						
						<th class="td-border-2 align-c f-size-12">Underpayment</th>
						<th class="td-border-2 align-c f-size-12">NET SALARY</th>
						
						<!-- DEDUCTIONS -->
						<?php $ded_width = ((count($deduction_groups))+(count($regular_deduction_hdr)/2)-1); ?>
						<th class="td-border-2 align-c f-size-12" colspan="<?php echo $ded_width; ?>">
							<table class="table-max">
								<tr><th class="align-c f-size-12 td-border-bottom" width="100%" colspan="<?php echo $ded_width; ?>">DEDUCTIONS</th></tr>
								<tr>
									<th class="td-border-right align-c f-size-12">
										<table width="85px">
											<!-- TAX REGULAR DEDUCTIONS HEADERS -->
											<?php 
												end($regular_deduction_hdr);
												foreach ($regular_deduction_hdr as $key => $ded_hdr): 
													if ($ded_hdr['deduction_id'] != DEDUC_BIR_EIGHT)
													{
													$key === key($regular_deduction_hdr) ? $class="" : $class=" td-border-bottom";
											?>
													<tr><th class="align-c f-size-12<?php echo $class;?>" width="100%" height="23px"><?php echo $ded_hdr['deduction_name']; ?></th></tr>
											<?php 
													}
												endforeach;
											?>
										</table>
									</th>
									
									<!-- DEDUCTIONS IN GROUP HEADERS -->
									<?php 
									foreach ($deduction_groups as $ded_group): 
										$ded_group === end($deduction_groups) ? $class="" : $class="td-border-right ";
									?>
										<th class="<?php echo $class;?>align-c f-size-12">
											<table width="86px">
												<?php
												end($ded_group);
												foreach($ded_group as $key=> $ded):
													$key === key($ded_group) ? $class="" : $class=" td-border-bottom";
													$key === 'OTHER DEDUCTION' ? $deduction_name = "Other Deduction" : $deduction_name = $ded['deduction_name'];
												?>
												<tr><th class="align-c f-size-12<?php echo $class;?>" width="100%" height="23px"><?php echo $deduction_name; ?></th></tr>
												
												<?php endforeach; ?>
											</table>			
										</th>
									<?php endforeach;?>
								</tr>
							</table>
						</th>
						
						<th class="td-border-2 align-c f-size-12">NET PAY</th>
						<th class="td-border-2 align-c f-size-12">REMARKS</th>
					</tr>
				</thead>
				<tbody>
        		<?php endif;?>  
			<?php endif;?>
		<?php endforeach;?>
		<tr class="cont-5">
			<th class="td-border-4 align-c f-size-12"><?php echo $ctr;?></th>
			<th class="td-border-2 align-c f-size-12">GRAND TOTAL</th>
			<th class="td-border-2 align-r f-size-12"><?php echo number_format($total_month_rate,2);?></th>
			<th class="td-border-2 align-c f-size-12" colspan="2">---</th>
			<th class="td-border-2 align-r f-size-12"><?php echo number_format($total_gross,2);?></th>
			<th class="td-border-2 align-c f-size-12" colspan="2">---</th>
			<th class="td-border-2 align-r f-size-12"><?php echo number_format($total_sub,2);?></th>
			<!-- COMPENSATION HEADERS -->
			<?php foreach ($total_compensation as $amount):?>
			<th class="td-border-2 align-r f-size-12"><?php echo number_format($amount,2);?></th>
			<?php endforeach;?>
			<th class="td-border-2 align-r f-size-12"><?php echo number_format($total_underpay,2);?></th>
			<th class="td-border-2 align-r f-size-12"><?php echo number_format($total_net_salary,2);?></th>
			
			<!-- TAX REGULAR DEDUCTIONS HEADERS -->
			<?php foreach ($total_ded_regular as $amount):
			$total_tax_deduction += $amount;
			endforeach;?>
			<th class="td-border-2 align-r f-size-12"><?php echo number_format($total_tax_deduction,2);?></th>
			
			<!-- DEDUCTIONS IN GROUP HEADERS -->
			<?php foreach ($total_ded_group as $amount):?>
			<th class="td-border-2 align-r f-size-12"><?php echo number_format($amount,2);?></th>
			<?php endforeach;?>
			<!--
			<th class="td-border-2 align-r f-size-12"><?php //echo number_format($total_ded_other,2);?></th>
			-->
			<th class="td-border-2 align-r f-size-12"><?php echo number_format($total_net_pay,2);?></th>
			<th class="td-border-2 align-c f-size-12">---</th>
		</tr>
	</tbody>
	</table>
</body>
</html>