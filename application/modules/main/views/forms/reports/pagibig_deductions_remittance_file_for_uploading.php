<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<html>
<head>
	
	<title></title>
	<link rel="stylesheet" href="<?php echo base_url().PATH_CSS ?>reports.css" type="text/css" />
</head>
<body>
	<table class="table-max">
		<tr>
			<td class="bold align-c valign-mid border-light" height="17">APPLNO</td>
			<td class="bold align-c valign-mid border-light-left">LNAME</td>
			<td class="bold align-c valign-mid border-light-left">FNAME</td>
			<td class="bold align-c valign-mid border-light-left">MID</td>
			<td class="bold align-c valign-mid border-light-left">TIN</td>
			<td class="bold align-c valign-mid border-light-left">MONTH_COV</td>
			<td class="bold align-c valign-mid border-light-left">AMOUNT</td>
		</tr>


		<?php if($pagibig_deduc_remittances): ?>
			<?php 
				$total_amt 	   = 0;
				foreach ($pagibig_deduc_remittances as $pagibig_deduc_remittance): ?>
				<?php 
				if($pagibig_deduc_remittance['amount'] > 0):
					$amt 			= $pagibig_deduc_remittance['amount'];
					$total_amt 		= $total_amt + $amt;
				?>
					<tr>
						<td class="border-light-top" height="17"><?php echo $pagibig_deduc_remittance['applno']?></td>
						<td class="border-light-top-left" width="100"><?php echo $pagibig_deduc_remittance['last_name']?></td>
						<td class="border-light-top-left" width="100"><?php echo $pagibig_deduc_remittance['first_name']?></td>
						<td class="border-light-top-left" width="100"><?php echo $pagibig_deduc_remittance['middle_name']?></td>
						<td class="border-light-top-left align-c"><?php echo (!EMPTY($pagibig_deduc_remittance['tin'])) ? $pagibig_deduc_remittance['tin'] : '' ?></td>
						<td class="border-light-top-left align-c"><?php echo $pagibig_deduc_remittance['month_covered']?></td>
						<td class="border-light-top-left align-r"><?php echo $pagibig_deduc_remittance['amount']?></td>
					</tr>
				<?php endif;?>
				<?php endforeach;?>

			<tr>
				<td colspan=6 height="17"></td>
				<td class="bold td-border-bottom align-r"><?php echo number_format((float)$total_amt, 2, '.', '');?></td>
			</tr>

		<?php else: ?>
			<tr>
				<td colspan=7 class="border-light align-c" height="17"><b>No Records Found.</b></td>
			</tr>
		<?php endif;?>

	</table>
</body>
</html>