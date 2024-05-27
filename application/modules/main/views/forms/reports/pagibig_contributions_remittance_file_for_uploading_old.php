<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<html>
<head>
	
	<title></title>
	<link rel="stylesheet" href="<?php echo base_url().PATH_CSS ?>reports.css" type="text/css" />
</head>
<body>
	<table class="table-max">
		<tr>
			<td class="bold align-c valign-mid border-light" height="17">PAGIBIG ID NO</td>
			<td class="bold align-c valign-mid border-light-left">LNAME</td>
			<td class="bold align-c valign-mid border-light-left">FNAME</td>
			<td class="bold align-c valign-mid border-light-left">MID</td>
			<td class="bold align-c valign-mid border-light-left">TIN</td>
			<td class="bold align-c valign-mid border-light-left">MONTH_COV</td>
			<td class="bold align-c valign-mid border-light-left">EMPLOYEE</td>
			<td class="bold align-c valign-mid border-light-left">EMPLOYER</td>
		</tr>

		<?php if($pagibig_remittances): ?>
			<?php 
				$total_amt 	   = 0;
				$total_emp_amt = 0;
				foreach ($pagibig_remittances as $pagibig_remittance): ?>
				<?php 
					$amt 			= $pagibig_remittance['amount'];
					$emp_amt 		= $pagibig_remittance['employer_amount'];
					$total_amt 		= $total_amt + $amt;
					$total_emp_amt  = $total_emp_amt + $emp_amt;
				?>
				<tr>
					<td class="border-light-top" height="17" width="100"><?php echo (!EMPTY($pagibig_remittance['pagibig'])) ? $pagibig_remittance['pagibig'].'&nbsp;' : '' ?></td>
					<td class="border-light-top-left" width="100"><?php echo $pagibig_remittance['last_name']?></td>
					<td class="border-light-top-left" width="100"><?php echo $pagibig_remittance['first_name']?></td>
					<td class="border-light-top-left" width="100"><?php echo $pagibig_remittance['middle_name']?></td>
					<td class="border-light-top-left align-c" width="100"><?php echo (!EMPTY($pagibig_remittance['tin'])) ? $pagibig_remittance['tin'].'&nbsp;' : '' ?></td>
					<td class="border-light-top-left align-c" width="100"><?php echo $pagibig_remittance['date_processed']?></td>
					<td class="border-light-top-left align-r" width="100"><?php echo $pagibig_remittance['amount']?></td>
					<td class="border-light-top-left align-r" width="100"><?php echo $pagibig_remittance['employer_amount']?></td>
				</tr>
			<?php endforeach;?>

		<tr>
			<td colspan=6 height="17"></td>
			<td class="bold td-border-bottom align-r"><?php echo number_format((float)$total_amt, 2, '.', '');?></td>
			<td class="bold td-border-bottom align-r"><?php echo number_format((float)$total_emp_amt, 2, '.', '')?></td>
		</tr>

		<?php else: ?>
			<tr>
				<td colspan=8 class="border-light align-c" height="17"><b>No Records Found.</b></td>
			</tr>
		<?php endif;?>
		
	</table>
</body>
</html>