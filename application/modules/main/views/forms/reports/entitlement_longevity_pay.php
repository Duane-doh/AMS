
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<html>
<head>
	
	<title>Entitlement of Longevity Pay</title>
	<link rel="stylesheet" href="<?php echo base_url().PATH_CSS ?>reports.css" type="text/css" />
</head>

<body>
<table class="f-size-12">
	<tbody>
		<tr>
			<td><?php echo nbs(10) ?><img src="<?php echo base_url().PATH_IMG ?>doh_logo.png" width=90 height=90></img></td>
			<td align="center" width="740"><?php echo nbs(5)?>Republic of the Philippines<br><?php echo nbs(5)?>Department of Health<br><?php echo nbs(5)?>Civil Service Commission</td>
			<td>&nbsp;</td>
		</tr>
	</tbody>
</table>
<table class="table-max">	
	<tr>
		<td height="40" align="left" valign=bottom><b><?php echo $agency['name']?></b></td>
	</tr>
</table>
<table class="table-max">
<thead>
	<tr>
		<td height='5'></td>
	</tr>
	<tr>
		<td class="td-border-light-top td-border-light-left td-border-light-right td-center-middle" height="17" align="left" valign=bottom><br></td>
		<td class="td-border-light-top td-border-light-right td-center-middle" align="left" valign=bottom><br></td>
	<?php
		for ($i=1; $i <= $max_lp_num; $i++) { 
	?>
		<td class="td-border-light-top td-border-light-bottom td-border-light-right td-center-middle" colspan=4 align="center" valign=bottom><b>LP<?php echo $i?></b></td>
	<?php } ?>

		<td class="td-border-light-top td-border-light-bottom td-border-light-left td-border-light-right td-center-middle" rowspan=2 align="center" valign=middle><b>TOTAL</b></td>
		<td class="td-border-light-top td-border-light-bottom td-border-light-right td-center-middle" rowspan=2 align="center" valign=middle><b>Remarks</b></td>
	</tr>
	<tr>
		<td class="td-border-light-bottom td-border-light-left td-border-light-right td-center-middle" height="55" align="center" valign=bottom><b>NAME</b></td>
		<td class="td-border-light-bottom td-border-light-right td-center-middle" align="center" valign=bottom width=30><b>ETD as<br>PWH</b></td>

	<?php
		for ($i=1; $i <= $max_lp_num; $i++) { 
	?>	

		<td class="td-border-light-bottom td-border-light-right td-center-middle" align="center" valign=bottom><b>DATE OF LP<?php echo $i?><br>(<?php echo $i?> year)</b></td>
		<td class="td-border-light-bottom td-border-light-right td-center-middle" align="center" valign=bottom><b>Anual<br>Salary<br>Before the<br>next LP</b></td>
		<td class="td-border-light-bottom td-border-light-right td-center-middle" align="center" valign=bottom><b>Monthly<br>Salary<br>Before the<br>next LP</b></td>
		<td class="td-border-light-bottom td-border-light-right td-center-middle" align="center" valign=bottom><b>5% of<br>Basic<br>salary</b></td>

	<?php } ?>

	</tr>
</thead>

	<?php 
	$grand_total = 0;
	foreach ($records AS $record): ?>
		<?php 
			$lp_num = explode(',', $record['lp_num']);
			$basic_amount = explode(',', $record['basic_amount']);
			$effective_date = explode(',', $record['effective_date']);
			$pay_amount = explode(',', $record['pay_amount']);
			$total_amount = end(explode(',', $record['total_amount']));
			$grand_total = $grand_total + $total_amount;
			
		?>
		<tr>
			<td class="td-border-light-bottom td-border-light-top td-border-light-left td-border-light-right td-center-middle" height="17" align="left" valign=bottom><?php echo $record['employee_name'] ?></td>
			<td class="td-border-light-bottom td-border-light-top td-border-light-right td-center-middle" align="right" valign=bottom></td>

			<?php
				$start = 0;
				for ($i=1; $i <= $max_lp_num; $i++) { 
			?>
				<td class="td-border-light-bottom td-border-light-top td-border-light-right td-center-middle" align="right" valign=bottom><?php echo isset($effective_date[$start]) ? $effective_date[$start]: '' ?></td>
				<td class="td-border-light-bottom td-border-light-top td-border-light-right td-center-middle" align="right" valign=bottom><?php echo isset($basic_amount[$start]) ? number_format(($basic_amount[$start] * 12), 2): '' ?></td>
				<td class="td-border-light-bottom td-border-light-top td-border-light-right td-center-middle" align="right" valign=bottom><?php echo isset($basic_amount[$start]) ? number_format($basic_amount[$start], 2): '0.00' ?></td>
				<td class="td-border-light-bottom td-border-light-top td-border-light-right td-center-middle" align="right" valign=bottom><?php echo isset($pay_amount[$start]) ? number_format($pay_amount[$start], 2): '0.00' ?></td>


			<?php $start++; } ?>

			<td class="td-border-light-bottom td-border-light-right td-border-light-top td-center-middle" align="right" valign=bottom><?php echo number_format($total_amount, 2)?></td>
			<td class="td-border-light-bottom td-border-light-right td-border-light-top td-center-middle" align="right" valign=bottom></td>
		</tr>
	<?php endforeach;?>

	<tr>
		<td class="td-border-light-bottom td-border-light-top td-border-light-left td-center-middle" height="17" align="center" valign=bottom><b>GRAND TOTAL</b></td>
		<td class="td-border-light-bottom td-border-light-top td-center-middle" align="right" valign=bottom></td>

		<?php
			for ($i=1; $i <= $max_lp_num; $i++) { 
			?>
					<td class="td-border-light-bottom td-border-light-top td-center-middle" align="right" valign=bottom></td>
					<td class="td-border-light-bottom td-border-light-top td-center-middle" align="right" valign=bottom></td>
					<td class="td-border-light-bottom td-border-light-top td-center-middle" align="right" valign=bottom></td>
					<td class="td-border-light-bottom td-border-light-top td-center-middle" align="right" valign=bottom></td>
			<?php } ?>

		<td class="td-border-light-bottom td-border-light-top td-border-light-right td-center-middle bold" align="right" valign=bottom><?php echo number_format($grand_total, 2)?></td>
		<td class="td-border-light-bottom td-border-light-top td-border-light-right td-center-middle" align="right" valign=bottom></td>
	</tr>

</table>
<!-- ************************************************************************** -->
</body>

</html>
