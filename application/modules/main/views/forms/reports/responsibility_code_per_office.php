<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<head>
 	<title>Responsibility Code per Office</title>
 	<link rel="stylesheet" href="<?php echo base_url().PATH_CSS ?>reports.css" type="text/css" />
 	<style type="text/css">
        
        .light-border td, th { 
            border: .3px solid;
			padding: 2px 2px;
        }
        
    </style>
</head>
<body>
	<?php
		$logo_is_set = FALSE;
		foreach ($organizations as $org):
		if(ISSET($records[$org['org_id']])):
			$total_gross = 0;
	?>

	<!-- SETTING LOGO -->
	<?php if($logo_is_set == FALSE):?>
	<table class="table-max f-size-12">
	    <tbody>
	        <tr>
	            <td width="20%"><?php echo nbs(10) ?><img src="<?php echo base_url().PATH_IMG ?>doh_logo.png" width=90 height=90></img><br></td>
	            <td align="center" width="60%">Republic of the Philippines<br>DEPARTMENT OF HEALTH<br><?php echo $org['name'];?><br>FOR THE PERIOD OF <?php echo $period_detail['payroll_period'];?></td>
	            <td>&nbsp;</td>
	        </tr>
	    </tbody>
	</table>
	<?php 
		//SETTING $logo BOOLEAN TO FALSE SO THAT LOGO AND HEADER IS NOT REPEATED
		$logo_is_set = TRUE;
		endif;
	?>
	
	<table>
	<tr>
		<td height="20"></td>
	</tr>
	</table>
	<table class="table-max light-border">
		<thead>
			<tr>
				<th class="td-border-3 align-c" rowspan="2">No.</th>
				<th class="td-border-3 align-c" rowspan="2">Office Assignment </th>
				<th class="td-border-3 align-c" colspan="2">Responsibility</th>
				<th class="td-border-3 align-c" colspan="2">Account</th>
				<th class="td-border-3 align-c" rowspan="2">Employee Name</th>
				<th class="td-border-3 align-c" rowspan="2">Position Title</th>
				<th class="td-border-3 align-c" rowspan="2">Salary Grade</th>
				<th class="td-border-3 align-c" rowspan="2">Basic Salary</th>
				<th class="td-border-3 align-c" rowspan="2">20% Premium</th>
				<th class="td-border-3 align-c" rowspan="2">Total amount of Absences/ Undertime/ Tardiness</th>
				<th class="td-border-3 align-c" rowspan="2">Net salary</th>
				<th class="td-border-3 align-c" colspan="3">BIR</th>
				<th class="td-border-3 align-c" colspan="3">Contributions</th>
				<th class="td-border-3 align-c" rowspan="2">MP2</th>
				<th class="td-border-3 align-c" rowspan="2">Underpayment</th>
				<th class="td-border-3 align-c" rowspan="2">Overpayment</th>
				<th class="td-border-4 align-c" rowspan="2">Remarks</th>
			</tr>
			<tr>
				<th class="td-border-3 align-c">Description</th>
				<th class="td-border-3 align-c">Code</th>
				<th class="td-border-3 align-c">Title</th>
				<th class="td-border-3 align-c">Code</th>
				<th class="td-border-3 align-c">10% EWT</th>
				<th class="td-border-3 align-c">5% EWT</th>
				<th class="td-border-3 align-c">1% GMP</th>
				<th class="td-border-3 align-c">SSS</th>
				<th class="td-border-3 align-c">PAG-IBIG</th>
				<th class="td-border-3 align-c">PHIC</th>
			</tr>
		</thead>
		<?php
			$ctr=0;
			foreach($records[$org['org_id']] as $record) :

			$record['rc_code'] 		= ISSET($respcodes) ? $respcodes[$record['emp_id']]['responsibility_center_code'] : $record['rc_code'];
			$record['rc_desc'] 		= ISSET($respcodes) ? $respcodes[$record['emp_id']]['responsibility_center_desc'] : $org['responsibility_center_desc'];
			$record['uacs_code'] 	= ISSET($uacscodes) ? $uacscodes['uacs_object_code'] : '0.00';
			$record['uacs_desc'] 	= ISSET($uacscodes) ? $uacscodes['account_title'] : '0.00';
			$record['emp_prem'] 	= ISSET($comp_prem) ? $record['emp_mosal']*($comp_prem/100) : '0.00';
			$record['less_attend'] 	= ISSET($less_attendance) ? $less_attendance[$record['emp_id']]['less_amount']*-1 : '0.00';
			
			$remarks =(empty($less_amounts[$record['emp_id']]['remarks']) ? "FT" : $less_amounts[$record['emp_id']]['remarks']);
			$record['remarks'] 		= ISSET($less_amounts) ? $remarks : '-';

			$record['ewt_5']	= 0.00;
			$record['ewt_10']	= 0.00;
			$record['gmp_1']	= 0.00;
			$record['sss'] 		= 0.00;
			$record['hmdf'] 	= 0.00;
			$record['phic'] 	= 0.00;
			$record['hmdf2'] 	= 0.00;
			$record['underpay'] = 0.00;
			$record['overpay'] 	= 0.00;

			$page_emp_mosal		+= $record['emp_mosal'];
			$total_emp_mosal	+= $record['emp_mosal'];
			$page_emp_prem		+= $record['emp_prem'];
			$total_emp_prem		+= $record['emp_prem'];
			$page_less_attend	+= $record['less_attend'];
			$total_less_attend	+= $record['less_attend'];
			$page_net_salary	+= $record['net_salary'];
			$total_net_salary	+= $record['net_salary'];
			
			$breakdown_remarks_hdr = null;
			$breakdown_remarks_dtl = null;
			$dtl_amount = 0.00;
			
			foreach($deductions[$record['emp_id']] as $deduction) :
			
				switch($deduction['deduction_id']) {
					case DEDUC_BIR_EWT:
						switch($deduction['other_deduction_detail_id']) {
							case DEDUC_OTHER_TIN:
								$record['ewt_10'] = $deduction['amount'];
								$page_ewt_10 	 += $record['ewt_10'];
								$total_ewt_10 	 += $record['ewt_10'];
								break;
							case DEDUC_OTHER_TAX_CODE:
								$record['ewt_10'] = 0.00;
								$record['ewt_5']  = $deduction['amount'];
								$page_ewt_10 	 -= $record['ewt_5'];
								$total_ewt_10 	 -= $record['ewt_5'];
								$page_ewt_5 	 += $record['ewt_5'];
								$total_ewt_5 	 += $record['ewt_5'];
								break;
						}
						break;
						
					case DEDUC_BIR_VAT:
						$record['gmp_1'] = $deduction['amount'];
						$page_gmp_1 	+= $record['gmp_1'];
						$total_gmp_1 	+= $record['gmp_1'];
						break;
					case DEDUC_SSS:
						$record['sss'] 	 = $deduction['amount'];
						$page_sss 		+= $record['sss'];
						$total_sss 		+= $record['sss'];
						break;
					case DEDUC_HMDF1_JO:
						$record['hmdf']  = $deduction['amount'];
						$dtl_amount  	 = $deduction['dtl_amount'];
						$page_hmdf 		+= $dtl_amount;
						$total_hmdf 	+= $dtl_amount;
						break;
					case DEDUC_PHILHEALTH:
						$record['phic']  = $deduction['amount'];
						$page_phic 		+= $record['phic'];
						$total_phic 	+= $record['phic'];
						break;
					case DEDUC_HMDF2_JO:
						$record['hmdf2'] = $deduction['amount'];
						$dtl_amount  	 = $deduction['dtl_amount'];
						$page_hmdf2 	+= $dtl_amount;
						$total_hmdf2 	+= $dtl_amount;
						break;
					case DEDUC_UNDERPAY_JO:
						$record['underpay']  = $deduction['amount'];
						$dtl_amount  	 	 = $deduction['dtl_amount'];
						$page_underpay 	    += $dtl_amount;
						$total_underpay 	+= $dtl_amount;
						break;
					case DEDUC_OVERPAY_JO:
						$record['overpay'] 	 = $deduction['amount'];
						$dtl_amount  	 	 = $deduction['dtl_amount'];
						$page_overpay 		+= $dtl_amount;
						$total_overpay 		+= $dtl_amount;
						
						if($breakdown_remarks_hdr == null)
							$breakdown_remarks_hdr = '<br>OVERPAY(';
						if($breakdown_remarks_dtl != null)
							$breakdown_remarks_dtl = ',<br>';
						
						$breakdown_remarks_dtl .= $deduction['deduction_detail_type_code'].':'.$dtl_amount;
						$breakdown_remarks_hdr .= $breakdown_remarks_dtl;
						break;
				}
			endforeach;
		?>
		<tbody>
			<tr>
				<td class="td-border-3 align-c"><?php echo ++$ctr;?></td>
				<td class="td-border-3 align-c"><?php echo $record['org_code'];?></td>
				<td class="td-border-3 align-l"><?php echo ISSET($record['rc_desc']) ? $record['rc_desc'] : '-';?></td>
				<td class="td-border-3 align-l"><?php echo ISSET($record['rc_code']) ? $record['rc_code'] : '-';?></td>
				<td class="td-border-3 align-l"><?php echo $record['uacs_desc'];?></td>
				<td class="td-border-3 align-l"><?php echo $record['uacs_code'];?></td>
				<td class="td-border-3 align-l"><?php echo $record['emp_name'];?></td>
				<td class="td-border-3 align-l"><?php echo strtoupper($record['job_desc']);?></td>
				<td class="td-border-3 align-c"><?php echo $record['emp_sg'];?></td>
				<td class="td-border-3 align-r"><?php echo number_format($record['emp_mosal'], 2);?></td>
				<td class="td-border-3 align-r"><?php echo ($record['emp_prem'] != 0.00) ? number_format($record['emp_prem'], 2) : '-';?></td>
				<td class="td-border-3 align-r"><?php echo ($record['less_attend'] != 0.00) ? number_format($record['less_attend'], 2) : '-';?></td>
				<td class="td-border-4 align-r"><?php echo number_format($record['net_salary'], 2);?></td>
				
				<td class="td-border-4 align-r"><?php echo ($record['ewt_10'] != 0.00) ? number_format($record['ewt_10'], 2) : '-';?></td>
				<td class="td-border-4 align-r"><?php echo ($record['ewt_5'] != 0.00) ? number_format($record['ewt_5'], 2) : '-';?></td>
				<td class="td-border-4 align-r"><?php echo ($record['gmp_1'] != 0.00) ? number_format($record['gmp_1'], 2) : '-';?></td>
				<td class="td-border-4 align-r"><?php echo ($record['sss'] != 0.00) ? number_format($record['sss'], 2) : '-';?></td>
				<td class="td-border-4 align-r"><?php echo ($record['hmdf'] != 0.00) ? number_format($record['hmdf'], 2) : '-';?></td>
				<td class="td-border-4 align-r"><?php echo ($record['phic'] != 0.00) ? number_format($record['phic'], 2) : '-';?></td>
				<td class="td-border-4 align-r"><?php echo ($record['hmdf2'] != 0.00) ? number_format($record['hmdf2'], 2) : '-';?></td>
				<td class="td-border-4 align-r"><?php echo ($record['underpay'] != 0.00) ? number_format($record['underpay'], 2) : '-';?></td>
				<td class="td-border-4 align-r"><?php echo ($record['overpay'] != 0.00) ? number_format($record['overpay'], 2) : '-';?></td>
				<td class="td-border-4 align-l"><?php echo ISSET($breakdown_remarks_hdr) ? $record['remarks'].$breakdown_remarks_hdr.")" : $record['remarks'];?></td>
			</tr>
			<?php if($ctr%12 == 0 OR count($records[$org['org_id']]) == $ctr):?>
			<tr>
				<td class="align-r bold align-c"><?php echo ($ctr%12 == 0) ? 12 :  $ctr%12?></td>
				<td class="align-r bold align-c" colspan="8">PAGE TOTAL</td>
				<td class="align-r bold align-r"><?php echo number_format($page_emp_mosal, 2);?></td>
				<td class="align-r bold align-r"><?php echo number_format($page_emp_prem, 2);?></td>
				<td class="align-r bold align-r"><?php echo number_format($page_less_attend, 2);?></td>
				<td class="align-r bold align-r"><?php echo number_format($page_net_salary, 2);?></td>
				<td class="align-r bold align-r"><?php echo number_format($page_ewt_10, 2);?></td>
				<td class="align-r bold align-r"><?php echo number_format($page_ewt_5, 2);?></td>
				<td class="align-r bold align-r"><?php echo number_format($page_gmp_1, 2);?></td>
				<td class="align-r bold align-r"><?php echo number_format($page_sss, 2);?></td>
				<td class="align-r bold align-r"><?php echo number_format($page_hmdf, 2);?></td>
				<td class="align-r bold align-r"><?php echo number_format($page_phic, 2);?></td>
				<td class="align-r bold align-r"><?php echo number_format($page_hmdf2, 2);?></td>
				<td class="align-r bold align-r"><?php echo number_format($page_underpay, 2);?></td>
				<td class="align-r bold align-r"><?php echo number_format($page_overpay, 2);?></td>
				<td class="align-r bold align-c">---</td>
			</tr>
			<?php
				$page_emp_mosal		= 0.00;
				$page_emp_prem		= 0.00;
				$page_less_attend	= 0.00;
				$page_net_salary	= 0.00;
				$page_ewt_10		= 0.00;
				$page_ewt_5			= 0.00;
				$page_gmp_1			= 0.00;
				$page_sss			= 0.00;
				$page_hmdf			= 0.00;
				$page_phic			= 0.00;
				$page_hmdf2			= 0.00;
				$page_underpay		= 0.00;
				$page_overpay		= 0.00;

				if(count($records[$org['org_id']]) != $ctr):
				
			?>
		</tbody>
	</table>
			<pagebreak />
				<table class="table-max f-size-12">
					<tbody>
						<tr>
							<td width="20%"><?php echo nbs(10) ?><img src="<?php echo base_url().PATH_IMG ?>doh_logo.png" width=90 height=90></img><br></td>
							<td align="center" width="60%">Republic of the Philippines<br>DEPARTMENT OF HEALTH<br><?php echo $org['name'];?><br>FOR THE PERIOD OF <?php echo $period_detail['payroll_period'];?></td>
							<td>&nbsp;</td>
						</tr>
					</tbody>
				</table>
			
				<table>
				<tr>
					<td height="20"></td>
				</tr>
				</table>
			
			<table class="table-max light-border">
					<thead>
						<tr>
							<th class="td-border-3 align-c" rowspan="2">No.</th>
							<th class="td-border-3 align-c" rowspan="2">Office Assignment </th>
							<th class="td-border-3 align-c" colspan="2">Responsibility</th>
							<th class="td-border-3 align-c" colspan="2">Account</th>
							<th class="td-border-3 align-c" rowspan="2">Employee Name</th>
							<th class="td-border-3 align-c" rowspan="2">Position Title</th>
							<th class="td-border-3 align-c" rowspan="2">Salary Grade</th>
							<th class="td-border-3 align-c" rowspan="2">Basic Salary</th>
							<th class="td-border-3 align-c" rowspan="2">20% Premium</th>
							<th class="td-border-3 align-c" rowspan="2">Total amount of Absences/ Undertime/ Tardiness</th>
							<th class="td-border-3 align-c" rowspan="2">Net salary</th>
							<th class="td-border-3 align-c" colspan="3">BIR</th>
							<th class="td-border-3 align-c" colspan="3">Contributions</th>
							<th class="td-border-3 align-c" rowspan="2">MP2</th>
							<th class="td-border-3 align-c" rowspan="2">Underpayment</th>
							<th class="td-border-3 align-c" rowspan="2">Overpayment</th>
							<th class="td-border-4 align-c" rowspan="2">Remarks</th>
						</tr>
						<tr>
							<th class="td-border-3 align-c">Description</th>
							<th class="td-border-3 align-c">Code</th>
							<th class="td-border-3 align-c">Title</th>
							<th class="td-border-3 align-c">Code</th>
							<th class="td-border-3 align-c">10% EWT</th>
							<th class="td-border-3 align-c">5% EWT</th>
							<th class="td-border-3 align-c">1% GMP</th>
							<th class="td-border-3 align-c">SSS</th>
							<th class="td-border-3 align-c">PAG-IBIG</th>
							<th class="td-border-3 align-c">PHIC</th>
						</tr>
					</thead>
				<?php endif;?>  
			<?php endif;?>
		<?php endforeach;?>
			<tr>
				<td class="align-r bold align-c"><?php echo $ctr;?></td>
				<td class="align-r bold align-c" colspan="8">GRAND TOTAL</td>
				<td class="align-r bold align-r"><?php echo number_format($total_emp_mosal, 2);?></td>
				<td class="align-r bold align-r"><?php echo number_format($total_emp_prem, 2);?></td>
				<td class="align-r bold align-r"><?php echo number_format($total_less_attend, 2);?></td>
				<td class="align-r bold align-r"><?php echo number_format($total_net_salary, 2);?></td>
				<td class="align-r bold align-r"><?php echo number_format($total_ewt_10, 2);?></td>
				<td class="align-r bold align-r"><?php echo number_format($total_ewt_5, 2);?></td>
				<td class="align-r bold align-r"><?php echo number_format($total_gmp_1, 2);?></td>
				<td class="align-r bold align-r"><?php echo number_format($total_sss, 2);?></td>
				<td class="align-r bold align-r"><?php echo number_format($total_hmdf, 2);?></td>
				<td class="align-r bold align-r"><?php echo number_format($total_phic, 2);?></td>
				<td class="align-r bold align-r"><?php echo number_format($total_hmdf2, 2);?></td>
				<td class="align-r bold align-r"><?php echo number_format($total_underpay, 2);?></td>
				<td class="align-r bold align-r"><?php echo number_format($total_overpay, 2);?></td>
				<td class="align-r bold align-c">---</td>
			</tr>
		
		</table>
	<br>
	<br>
	<?php 
		endif;
		endforeach;
	?>
	
	<!-- IF NO RECORD -->
	<?php if($logo_is_set == FALSE):?>
	<div class="wrapper">
		<form id="test_report">
			<p style="text-align: center">No data available.</p>
		</form>
	</div>
	<?php endif;?>
	
</body>