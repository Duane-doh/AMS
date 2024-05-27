<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<html>
<head>
	
	<title></title>
	<link rel="stylesheet" href="<?php echo base_url().PATH_CSS ?>reports.css" type="text/css"/>
</head>
<body>
	<table class="table-max">
		<tbody>
			<tr>
				<td class="bold align-c valign-mid" height="17" colspan="9"></td>
				<td class="bold align-r valign-mid" height="17">FPF060</td>
			</tr>
			<tr>
				<td class="align-l valign-mid" rowspan="2" width="80px"><img src="<?php echo base_url().PATH_IMG ?>Pag-IBIG-logo.jpg" width=50 height=50></img></td>
				<td class="bold align-l f-size-12" height="17" colspan="9">MEMBERSHIP CONTRIBUTIONS REMITTANCE FORM (MCRF)</td>
			</tr>
			<tr>
				<td class="bold align-l valign-mid" height="17" colspan="9">
					<span class="f-size-9">PAGIBIG FUND CONTRIBUTION</span><br>
					<span class="f-size-8"><?php echo convertTitleCase($office_name); ?></span>
				</td>
			</tr>
		</tbody>
	</table>
	<table class="table-max">
		<tbody>
			
			<tr>
				<td class="align-l valign-mid f-size-8" height="17"><i>PERIOD COVERED</i></td>
				<td class="align-l valign-mid" height="17" colspan="9">&nbsp;</td>
			</tr>
			<tr>
				<td class="align-l valign-mid f-size-8" height="17"><i>(<?php echo $month_year?>)</i></td>
				<td class="bold align-c valign-mid" height="17" colspan="6">&nbsp;</td>
				<td class="align-r valign-mid f-size-8" height="17" colspan="3">Employer's PAG-IBIG ID No.</td>
			</tr>
			<tr>
				<td class="align-l valign-mid" height="17" colspan="8">&nbsp;</td>
				<td class="bold align-r valign-mid f-size-8" colspan="2"><?php echo $doh_hmdf;?></td>
			</tr>
			<tr>
				<td class="align-l valign-mid f-size-8 td-border-light-left td-border-light-top" height="17" colspan="4" width="410"><i>EMPLOYER/BUSINESS NAME</i></td>
				<td class="align-l valign-mid f-size-8 td-border-light-left td-border-light-top" height="17" colspan="2"><i>EMPLOYER SSS NO.<br><span class="f-size-7">(for private Employers only)</span></i></td>
				<td class="align-l valign-mid f-size-8 td-border-light-left td-border-light-top td-border-light-right" height="17" colspan="4"><i>AGENCY/BRANCH/DIVISION CODE<br><span class="f-size-7">(for private Government only)</span></i></td>
			</tr>
			<tr>
				<td class="bold align-l valign-mid f-size-8 td-border-light-left" height="17" colspan="4" width="280">DEPARTMENT OF HEALTH</td>
				<td class="bold align-l valign-mid f-size-8 td-border-light-left" height="17" colspan="2"></td>
				<td class="bold align-l valign-mid f-size-8 td-border-light-left td-border-light-right" height="17" colspan="4"></td>
			</tr>
			<tr>
				<td class="align-l valign-mid f-size-8 td-border-light-left td-border-light-top" height="17" colspan="4"><i>BUSINESS ADDRESS<span class="f-size-7">(Unit Room/Floor/Building/Street)</span></i></td>
				<td class="align-l valign-mid f-size-8 td-border-light-left td-border-light-top" height="17" colspan="2"><i>ZIP CODE</i></td>
				<td class="align-l valign-mid f-size-8 td-border-light-left td-border-light-top" height="17" colspan="2" width="80"><i>TIN</i></td>
				<td class="align-l valign-mid f-size-8 td-border-light-left td-border-light-top td-border-light-right" height="17" colspan="2"><i>CONTACT NO./S</i></td>
			</tr>
			<tr>
				<td class="bold align-l valign-mid f-size-8 td-border-light-left" height="17" colspan="4"><?php echo strtoupper($doh_address);?></td>
				<td class="bold align-l valign-mid f-size-8 td-border-light-left align-c" height="17" colspan="2"><?php echo $zip_code;?></td>
				<td class="bold align-l valign-mid f-size-8 td-border-light-left align-c" height="17" colspan="2"><?php echo $doh_tin;?></td>
				<td class="bold align-l valign-mid f-size-8 td-border-light-left td-border-light-right align-c" height="17" colspan="2">651-7800</td>
			</tr>
		</tbody>
	</table>
	<table class="table-max">
		<thead>
			<tr>
				<th class="bold align-c valign-mid border-light" height="17"></th>
				<th class="bold align-c valign-mid border-light-left f-size-10" colspan="5">NAME OF EMPLOYEES</th>
				<th class="bold align-c valign-mid border-light-left f-size-10" colspan="3">C O N T R I B U T I O N S</th>
				<th class="bold align-c valign-mid border-light-left">REMARKS</th>
			</tr>
			<tr>
				<th class="bold align-c valign-mid border-light" height="17">PAG-IBIG ID No.</th>
				<th class="bold align-c valign-mid border-light-left">Last Name</th>
				<th class="bold align-c valign-mid border-light-left">First Name</th>
				<th class="bold align-c valign-mid border-light-left f-size-7">Name Extension <br><span class="f-size-7">(Jr., III, etc.)</span></th>
				<th class="bold align-c valign-mid border-light-left">Middle Name</th>
				<th class="bold align-c valign-mid border-light-left">Month_Cov</th>
				<th class="bold align-c valign-mid border-light-left">EMPLOYEE</th>
				<th class="bold align-c valign-mid border-light-left">EMPLOYER</th>
				<th class="bold align-c valign-mid border-light-left">TOTAL</th>
				<th class="bold align-c valign-mid border-light-left"></th>
			</tr>
		</thead>
		<tbody>
		<?php if($pagibig_remittances): ?>
			<?php 
				$total_amt 	   = 0;
				$total_emp_amt = 0;
				$page_amt 	   = 0;
				$page_emp_amt = 0;
				$display_count     = 42;
            	$count_per_page    = 53;
            	$footer_count = 0;
				foreach ($pagibig_remittances as $pagibig_remittance): ?>
				<?php 
					$footer_count++;
					$record_cnt++;

					$total_amt     += $pagibig_remittance['amount'];
					$total_emp_amt += $pagibig_remittance['employer_amount'];
					$page_amt      += $pagibig_remittance['amount'];
					$page_emp_amt  += $pagibig_remittance['employer_amount'];
				?>
				<tr>
					<td class="border-light-top align-c" height="17" width="100"><?php echo (!EMPTY($pagibig_remittance['pagibig'])) ? $pagibig_remittance['pagibig'].'&nbsp;' : '' ?></td>
					<td class="border-light-top-left" width="100"><?php echo $pagibig_remittance['last_name']?></td>
					<td class="border-light-top-left" width="100"><?php echo $pagibig_remittance['first_name']?></td>
					<td class="border-light-top-left"><?php echo $pagibig_remittance['ext_name']?></td>
					<td class="border-light-top-left" width="100"><?php echo $pagibig_remittance['middle_name']?></td>
					<td class="border-light-top-left align-c" width="100"><?php echo $pagibig_remittance['effective_date']?></td>
					<td class="border-light-top-left align-r" width="100"><?php echo number_format($pagibig_remittance['amount'],2)?></td>
					<td class="border-light-top-left align-r" width="100"><?php echo number_format($pagibig_remittance['employer_amount'],2)?></td>
					<td class="border-light-top-left align-r" width="100"><?php echo number_format($pagibig_remittance['employer_amount'] + $pagibig_remittance['amount'],2);?></td>
					<td class="border-light-top-left align-r" width="100"></td>
				</tr>
				<?php if(($footer_count == $display_count OR count($pagibig_remittances) == $record_cnt) AND $format == 'pdf'):?>
					<tr>
						<td class="bold td-border-bottom align-c td-border-light-left" colspan=2 height="17">No. of Employees on this page</td>
						<td class="bold td-border-bottom align-l td-border-light-left pad-2" colspan=2 height="17"><?php echo $footer_count;?></td>
						<td class="bold td-border-bottom align-c td-border-light-left" colspan=2 height="17">TOTAL FOR THIS PAGE</td>
						<td class="bold td-border-bottom align-r td-border-light-left"><?php echo number_format($page_amt, 2);?></td>
						<td class="bold td-border-bottom align-r td-border-light-left"><?php echo number_format($page_emp_amt, 2)?></td>
						<td class="bold td-border-bottom align-r td-border-light-left"><?php echo number_format($page_amt + $page_emp_amt, 2)?></td>
							<td class="bold align-c valign-mid td-border-light-left td-border-light-bottom td-border-light-right">----</td>
					</tr>
					<?php if(count($pagibig_remittances) != $record_cnt):?>
					</tbody>
					</table>
					<pagebreak />
					<table>
						<thead>
							<tr>
								<th class="bold align-c valign-mid border-light" height="17"></th>
								<th class="bold align-c valign-mid border-light-left f-size-10" colspan="5">NAME OF EMPLOYEES</th>
								<th class="bold align-c valign-mid border-light-left f-size-10" colspan="3">C O N T R I B U T I O N S</th>
								<th class="bold align-c valign-mid border-light-left">REMARKS</th>
							</tr>
							<tr>
								<th class="bold align-c valign-mid border-light" height="17">PAG-IBIG ID No.</th>
								<th class="bold align-c valign-mid border-light-left">Last Name</th>
								<th class="bold align-c valign-mid border-light-left">First Name</th>
								<th class="bold align-c valign-mid border-light-left f-size-7">Name Extension <br><span class="f-size-7">(Jr., III, etc.)</span></th>
								<th class="bold align-c valign-mid border-light-left">Middle Name</th>
								<th class="bold align-c valign-mid border-light-left"></th>
								<th class="bold align-c valign-mid border-light-left">EMPLOYEE</th>
								<th class="bold align-c valign-mid border-light-left">EMPLOYER</th>
								<th class="bold align-c valign-mid border-light-left">TOTAL</th>
								<th class="bold align-c valign-mid border-light-left"></th>
							</tr>
						</thead>
					<tbody>
					<?php endif;?>
					<?php 
						$footer_count  = 0;
						$display_count = $count_per_page;
						$page_amt      = 0;
						$page_emp_amt  = 0;
					?>

				<?php endif;?>
			<?php endforeach;?>

		<tr>
			<td class="bold td-border-bottom align-c td-border-light-left" colspan=2 height="17">Total No. of Employees</td>
			<td class="bold td-border-bottom align-l td-border-light-left pad-2" colspan=2 height="17"><?php echo $record_cnt;?></td>
			<td class="bold td-border-bottom align-c td-border-light-left" colspan=2 height="17">GRAND TOTAL</td>
			<td class="bold td-border-bottom align-r td-border-light-left"><?php echo number_format($total_amt, 2);?></td>
			<td class="bold td-border-bottom align-r td-border-light-left"><?php echo number_format($total_emp_amt, 2)?></td>
			<td class="bold td-border-bottom align-r td-border-light-left"><?php echo number_format($total_amt + $total_emp_amt, 2)?></td>
			<td class="bold align-c valign-mid td-border-light-left td-border-light-bottom td-border-light-right">----</td>
		</tr>

		<?php else: ?>
			<tr>
				<td colspan=10 class="border-light align-c" height="17"><b>No Records Found.</b></td>
			</tr>
		<?php endif;?>
		<tr>
			<td colspan=10 height="17">&nbsp;</td>
		</tr>
		<tr>
			<td colspan=10 height="17">&nbsp;</td>
		</tr>
		<tr>
			<td colspan=5 class="align-l" height="17"><b>FOR Pag-IBIG USE ONLY</b></td>
			<td colspan=5 class="align-l" height="17"><b>CERTIFIED CORRECT BY:</b></td>
		</tr>
		<tr>
			<td colspan=3 class="align-l" height="17">POSTED BY:   _______________</td>
			<td colspan=2 class="align-l" height="17">DATE: _____________</td>
			<td colspan=3 class="align-l" height="17">&nbsp;</td>
			<td class="align-l" height="17">DATE: _____________</td>
			<td class="align-l" height="17">&nbsp;</td>
		</tr>
		<tr>
			<td colspan=5 class="align-l" height="17">&nbsp;</td>
			<td colspan=3 class="align-l valign-bot f-size-12 bold" height="17"><u><?php echo $certified_by['signatory_name']?></u></td>
			<td colspan=2 class="align-l" height="17">&nbsp;</td>
		</tr>
		<tr>
			<td colspan=3 class="align-l" height="17">APPROVED BY: ______________</td>
			<td colspan=2 class="align-l" height="17">DATE: _____________</td>
			<td colspan=3 class="align-l valign-top f-size-12" height="17"><?php echo $certified_by['position_name'] ?></td>
			<td class="align-l" height="17">&nbsp;</td>
			<td class="align-l" height="17">&nbsp;</td>
		</tr>
		</tbody>
	</table>
</body>
</html>