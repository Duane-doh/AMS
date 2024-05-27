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
							<br><br><span class="f-size-12"><?php echo !EMPTY($office_name['name']) ? $office_name['name'] : '';?></span>
							<br><span class="f-size-12">FOR THE PERIOD OF <?php echo $payroll_period_text;?></span>
							</td>
							<td width="150">&nbsp;</td>
						</tr>
					</tbody>
				</table>
			</td>
		</tr>
		<tr>
			<td height="10"></td>
		</tr>
	</thead>
	</table>
	<table>
	<thead>
		<tr>
		<th class="td-border-4 align-c f-size-12">NO.</th>
		<th class="td-border-2 align-c f-size-12">NAME/TIN/ATM NO</th>
		<th class="td-border-2 align-c f-size-12">RATE/MONTH</th>
		<th class="td-border-2 align-c f-size-12">NO. OF<br>WORKING<br>DAYS(in<br>a mon)</th>
		<th class="td-border-2 align-c f-size-12">RATE/DAY</th>
		<th class="td-border-2 align-c f-size-12">GROSS SALARY<br>FOR THE PERIOD</th>
		<th class="td-border-2 align-c f-size-12">NO. OF<br>DAY/HOUR<br>/MIN</th>
		<th class="td-border-2 align-c f-size-12">RATE</th>
		<th class="td-border-2 align-c f-size-12">SUB-TOTAL</th>
		<!-- COMPENSATION HEADERS -->
		<?php foreach ($compensation_hdr as $com_hdr):?>
		<th class="td-border-2 align-c f-size-12"><?php echo $com_hdr['report_short_code']?></th>
		<?php endforeach;?>
		<th class="td-border-2 align-c f-size-12">NET SALARY</th>
		<!-- REGULAR DEDUCTIONS HEADERS -->
		<?php foreach ($regular_deduction_hdr as $ded_hdr):?>
		<th class="td-border-2 align-c f-size-12"><?php echo $ded_hdr['report_short_code']?></th>
		<?php endforeach;?>		
		
		<!-- DEDUCTIONS IN GROUP HEADERS -->
		<?php foreach ($deduction_groups as $ded_group):?>
		<th class="td-border-2 align-c f-size-12">
			<table>
				<?php 
				end($ded_group);
				$last = key($ded_group);
				$class = " td-border-bottom";
				foreach($ded_group as $key=> $ded):
					if($key === $last)
					{
						$class="";
					}
				?>
				<tr><th class="align-c f-size-12<?php echo $class;?>" width="100%"><?php echo $ded['report_short_code'];?></th></tr>
				<?php endforeach;?>
			</table>			
		</th>
		<?php endforeach;?>
		<th class="td-border-2 align-c f-size-12">OTHER<br>DEDUCTION</th>
		<th class="td-border-2 align-c f-size-12">NET PAY</th>
		<th class="td-border-2 align-c f-size-12" width="100px">REMARKS</th>
		
		</tr>
	</thead>
	<tbody>
	<?php 
		$page_month_rate   = 0;
		$page_gross        = 0;
		$page_sub          = 0;
		$page_ded_other    = 0;
		$page_net_salary   = 0;
		$page_net_pay      = 0;
		$page_compensation = array();
		$page_ded_regular  = array();
		$page_ded_group    = array();

		$total_month_rate   = 0;
		$total_gross        = 0;
		$total_sub          = 0;
		$total_ded_other    = 0;
		$total_net_salary   = 0;
		$total_net_pay      = 0;
		$total_compensation = array();
		$total_ded_regular  = array();
		$total_ded_group    = array();
	?>
		<?php foreach ($header as $hdr):?>
		<tr>
			<td class="td-border-4 f-size-12"><?php echo ++$ctr;?></td>
			<td class="td-border-bottom td-border-right f-size-12"  width="200px">
				<table class="table-max">
					<tr>
						<td class="f-size-12" height="20px" colspan="2"><?php echo $hdr['full_name'];?></td>
					</tr>
					<tr>
						<td class="td-border-top td-border-right f-size-12" width="120px" height="20px">TIN No.:<br><?php echo $hdr['tin'];?></td>
						<td class="td-border-top f-size-12" width="120px">ATM No.:<br><?php echo $hdr['atm_no'];?></td>						
					</tr>
				</table>
			</td>
			<td class="td-border-bottom td-border-right align-r f-size-12">
			<?php
				$page_month_rate += $records[$hdr['employee_id']]['basic_amount'];
				$total_month_rate += $records[$hdr['employee_id']]['basic_amount'];
			 	echo number_format($records[$hdr['employee_id']]['basic_amount'], 2);
			 ?>
			 	
			 </td>
			<td class="td-border-bottom td-border-right align-c f-size-12"><?php echo $working_days_in_period . '/' . $working_days_in_month;?></td>
			<td class="td-border-bottom td-border-right align-r f-size-12"><?php echo number_format($rates[$hdr['employee_id']]['rate_day'], 4);?></td>
			<td class="td-border-bottom td-border-right align-r f-size-12">
			<?php 
			$orig_amount = round($working_days_in_period * $rates[$hdr['employee_id']]['rate_day'],2);
			$page_gross += $orig_amount;
			$total_gross += $orig_amount;
			echo number_format($orig_amount, 2);
			?>
				
			</td>
			<td class="td-border-bottom td-border-right align-c f-size-12">
				<table class="table-max">
					<tr>
						<td class="align-c f-size-12"><?php echo $less_amounts[$hdr['employee_id']]['day'];?></td>					
					</tr>
					<tr>
						<td class="align-c f-size-12"><?php echo $less_amounts[$hdr['employee_id']]['hour'];?></td>
					</tr>
					<tr>
						<td class="align-c f-size-12"><?php echo $less_amounts[$hdr['employee_id']]['minute'];?></td>
					</tr>
				</table>
			</td>
			<td class="td-border-bottom td-border-right align-r f-size-12">
				<table class="table-max">
					<tr>
						<td class="align-r f-size-12"><?php echo number_format($rates[$hdr['employee_id']]['rate_day'], 4);?></td>					
					</tr>
					<tr>
						<td class="align-r f-size-12"><?php echo number_format($rates[$hdr['employee_id']]['rate_hour'], 4);?></td>
					</tr>
					<tr>
						<td class="align-r f-size-12"><?php echo number_format($rates[$hdr['employee_id']]['rate_minute'], 4);?></td>
					</tr>
				</table>
			</td>
			<td class="td-border-bottom td-border-right align-r f-size-12">
				<table class="table-max">
					<tr>
						<td class="align-r f-size-12">
						<?php 
							$page_sub += round($less_amounts[$hdr['employee_id']]['rate_day'],4); 
							$total_sub += round($less_amounts[$hdr['employee_id']]['rate_day'],4); 
							echo number_format($less_amounts[$hdr['employee_id']]['rate_day'], 4);?>
							
						</td>					
					</tr>
					<tr>
						<td class="align-r f-size-12">
						<?php 
							$page_sub += round($less_amounts[$hdr['employee_id']]['rate_hour'],4); 
							$total_sub += round($less_amounts[$hdr['employee_id']]['rate_hour'],4); 
							echo number_format($less_amounts[$hdr['employee_id']]['rate_hour'], 4);?>
							
						</td>
					</tr>
					<tr>
						<td class="align-r f-size-12">
						<?php 
							$page_sub += round($less_amounts[$hdr['employee_id']]['rate_minute'],4); 
							$total_sub += round($less_amounts[$hdr['employee_id']]['rate_minute'],4); 
							echo number_format($less_amounts[$hdr['employee_id']]['rate_minute'], 4);?>
							
						</td>
					</tr>
				</table>
			</td>
			<?php foreach ($compensation_hdr as $com):?>
				<td class="td-border-bottom td-border-right align-r f-size-12">
				<?php 
				$page_compensation[$com['compensation_id']] += $compensation_amounts[$hdr['employee_id']][$com['compensation_id']]['amount'];
				$total_compensation[$com['compensation_id']] += $compensation_amounts[$hdr['employee_id']][$com['compensation_id']]['amount'];
				echo ISSET($compensation_amounts[$hdr['employee_id']][$com['compensation_id']]['amount']) ? $compensation_amounts[$hdr['employee_id']][$com['compensation_id']]['amount'] : "0.00";?>
					
				</td>
			<?php endforeach;?>
			<td class="td-border-bottom td-border-right align-r f-size-12">
			<?php 
				$page_net_salary += $bs[$hdr['employee_id']]['amount'];
				$total_net_salary += $bs[$hdr['employee_id']]['amount'];
				echo number_format($bs[$hdr['employee_id']]['amount'], 2);?>
				
			</td>
			<?php foreach ($regular_deduction_hdr as $ded):?>
				<td class="td-border-bottom td-border-right align-r f-size-12">
				<?php 
				$page_ded_regular[$ded['deduction_id']] += $regular_deduction_amt[$hdr['employee_id']][$ded['deduction_id']]['amount'];
				$total_ded_regular[$ded['deduction_id']] += $regular_deduction_amt[$hdr['employee_id']][$ded['deduction_id']]['amount'];
				echo ISSET($regular_deduction_amt[$hdr['employee_id']][$ded['deduction_id']]['amount'])? $regular_deduction_amt[$hdr['employee_id']][$ded['deduction_id']]['amount']: "0.00";?>
					
				</td>
			<?php endforeach;?>
			
			<?php foreach ($deduction_groups as $group_key=>$ded_group):?>
			<td class="td-border-2 align-r f-size-12">
				<table width="60px">
					<?php 
					end($ded_group);
					$last = key($ded_group);
					$class = " td-border-bottom";
					foreach($ded_group as $ded_key=> $ded):
						$page_ded_group[$group_key] += $ded_amt_in_group[$hdr['employee_id']][$group_key][$ded['deduction_id']]['amount'];
						$total_ded_group[$group_key] += $ded_amt_in_group[$hdr['employee_id']][$group_key][$ded['deduction_id']]['amount'];
						if($ded_key === $last)
						{
							$class="";
						}
					?>
					<tr><td class="align-r f-size-12<?php echo $class;?>"><?php echo ISSET($ded_amt_in_group[$hdr['employee_id']][$group_key][$ded['deduction_id']]['amount']) ? number_format($ded_amt_in_group[$hdr['employee_id']][$group_key][$ded['deduction_id']]['amount'], 2) : "0.00";?></td></tr>
					<?php endforeach;?>
				</table>			
			</td>
			<?php endforeach;?>
			<td class="td-border-bottom td-border-right align-r f-size-12">
			<?php 
			$page_ded_other += $other_deduction_amt[$hdr['employee_id']];
			$total_ded_other += $other_deduction_amt[$hdr['employee_id']];
			echo ISSET($other_deduction_amt[$hdr['employee_id']])? number_format($other_deduction_amt[$hdr['employee_id']], 2) : "0.00";?>
				
			</td>
			<td class="td-border-bottom td-border-right align-r f-size-12 pad-2">
			<?php echo number_format($records[$hdr['employee_id']]['net_pay'], 2);
					$total_net_pay += $records[$hdr['employee_id']]['net_pay'];
					$page_net_pay  += $records[$hdr['employee_id']]['net_pay'];
			?>
				
			</td>
			<td class="td-border-bottom td-border-right f-size-12 pad-2"><?php echo (empty($less_amounts[$hdr['employee_id']]['remarks']) ? "&nbsp;FULL TIME" : $less_amounts[$hdr['employee_id']]['remarks']) ?></td>
		</tr>
		<?php if($ctr%12 == 0 OR count($header) == $ctr):?>
			
			<tr>
				<th class="td-border-4 align-c f-size-12"><?php echo ($ctr%12 == 0) ? 12 :  $ctr%12?></th>
				<th class="td-border-2 align-c f-size-12">PAGE TOTAL</th>
				<th class="td-border-2 align-c f-size-12"><?php echo number_format($page_month_rate,2);?></th>
				<th class="td-border-2 align-c f-size-12" colspan="2">---</th>
				<th class="td-border-2 align-c f-size-12"><?php echo number_format($page_gross,2);?></th>
				<th class="td-border-2 align-c f-size-12" colspan="2">---</th>
				<th class="td-border-2 align-c f-size-12"><?php echo number_format($page_sub,2);?></th>
				<!-- COMPENSATION HEADERS -->
				<?php foreach ($page_compensation as $amount):?>
				<th class="td-border-2 align-c f-size-12"><?php echo number_format($amount,2);?></th>
				<?php endforeach;?>
				<th class="td-border-2 align-c f-size-12"><?php echo number_format($page_net_salary,2);?></th>
				
				<!-- REGULAR DEDUCTIONS HEADERS -->
				<?php foreach ($page_ded_regular as $amount):?>
				<th class="td-border-2 align-c f-size-12"><?php echo number_format($amount,2);?></th>
				<?php endforeach;?>		
				
				<!-- DEDUCTIONS IN GROUP HEADERS -->
				<?php foreach ($page_ded_group as $amount):?>
				<th class="td-border-2 align-c f-size-12"><?php echo number_format($amount,2);?></th>
				<?php endforeach;?>
				<th class="td-border-2 align-c f-size-12"><?php echo number_format($page_ded_other,2);?></th>
				<th class="td-border-2 align-c f-size-12"><?php echo number_format($page_net_pay,2);?></th>
				<th class="td-border-2 align-c f-size-12">---</th>
			</tr>
			<?php 
				$page_month_rate   = 0;
				$page_gross        = 0;
				$page_sub          = 0;
				$page_ded_other    = 0;
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
										<br><br><span class="f-size-12"><?php echo !EMPTY($office_name['name']) ? $office_name['name'] : '';?></span>
										<br><span class="f-size-12">FOR THE PERIOD OF <?php echo $payroll_period_text;?></span>
										</td>
										<td width="150">&nbsp;</td>
									</tr>
								</tbody>
							</table>
						</td>
					</tr>
					<tr>
						<td height="10"></td>
					</tr>
				</thead>
			</table>
			<table>
				<thead>
					<tr>
					<th class="td-border-4 align-c f-size-12">NO.</th>
					<th class="td-border-2 align-c f-size-12">NAME/TIN/ATM NO</th>
					<th class="td-border-2 align-c f-size-12">RATE/MONTH</th>
					<th class="td-border-2 align-c f-size-12">NO. OF<br>WORKING<br>DAYS(in<br>a mon)</th>
					<th class="td-border-2 align-c f-size-12">RATE/DAY</th>
					<th class="td-border-2 align-c f-size-12">GROSS SALARY<br>FOR THE PERIOD</th>
					<th class="td-border-2 align-c f-size-12">NO. OF<br>DAY/HOUR<br>/MIN</th>
					<th class="td-border-2 align-c f-size-12">RATE</th>
					<th class="td-border-2 align-c f-size-12">SUB-TOTAL</th>
					<!-- COMPENSATION HEADERS -->
					<?php foreach ($compensation_hdr as $com_hdr):?>
					<th class="td-border-2 align-c f-size-12"><?php echo $com_hdr['report_short_code']?></th>
					<?php endforeach;?>
					<th class="td-border-2 align-c f-size-12">NET SALARY</th>
					<!-- REGULAR DEDUCTIONS HEADERS -->
					<?php foreach ($regular_deduction_hdr as $ded_hdr):?>
					<th class="td-border-2 align-c f-size-12"><?php echo $ded_hdr['report_short_code']?></th>
					<?php endforeach;?>		
					
					<!-- DEDUCTIONS IN GROUP HEADERS -->
					<?php foreach ($deduction_groups as $ded_group):?>
					<th class="td-border-2 align-c f-size-12">
						<table>
							<?php 
							end($ded_group);
							$last = key($ded_group);
							$class = " td-border-bottom";
							foreach($ded_group as $key=> $ded):
								if($key === $last)
								{
									$class="";
								}
							?>
							<tr><th class="align-c f-size-12<?php echo $class;?>" width="100%"><?php echo $ded['report_short_code'];?></th></tr>
							<?php endforeach;?>
						</table>			
					</th>
					<?php endforeach;?>
					<th class="td-border-2 align-c f-size-12">OTHER<br>DEDUCTION</th>
					<th class="td-border-2 align-c f-size-12">NET PAY</th>
					<th class="td-border-2 align-c f-size-12" width="100px">REMARKS</th>
					
					</tr>
				</thead>
				<tbody>
        		<?php endif;?>  
			<?php endif;?>
		<?php endforeach;?>
		<tr>
			<th class="td-border-4 align-c f-size-12"><?php echo $ctr;?></th>
			<th class="td-border-2 align-c f-size-12">GRAND TOTAL</th>
			<th class="td-border-2 align-c f-size-12"><?php echo number_format($total_month_rate,2);?></th>
			<th class="td-border-2 align-c f-size-12" colspan="2">---</th>
			<th class="td-border-2 align-c f-size-12"><?php echo number_format($total_gross,2);?></th>
			<th class="td-border-2 align-c f-size-12" colspan="2">---</th>
			<th class="td-border-2 align-c f-size-12"><?php echo number_format($total_sub,2);?></th>
			<!-- COMPENSATION HEADERS -->
			<?php foreach ($total_compensation as $amount):?>
			<th class="td-border-2 align-c f-size-12"><?php echo number_format($amount,2);?></th>
			<?php endforeach;?>
			<th class="td-border-2 align-c f-size-12"><?php echo number_format($total_net_salary,2);?></th>
			
			<!-- REGULAR DEDUCTIONS HEADERS -->
			<?php foreach ($total_ded_regular as $amount):?>
			<th class="td-border-2 align-c f-size-12"><?php echo number_format($amount,2);?></th>
			<?php endforeach;?>		
			
			<!-- DEDUCTIONS IN GROUP HEADERS -->
			<?php foreach ($total_ded_group as $amount):?>
			<th class="td-border-2 align-c f-size-12"><?php echo number_format($amount,2);?></th>
			<?php endforeach;?>
			<th class="td-border-2 align-c f-size-12"><?php echo number_format($total_ded_other,2);?></th>
			<th class="td-border-2 align-c f-size-12"><?php echo number_format($total_net_pay,2);?></th>
			<th class="td-border-2 align-c f-size-12">---</th>
		</tr>
	</tbody>
	</table>
</body>
</html>