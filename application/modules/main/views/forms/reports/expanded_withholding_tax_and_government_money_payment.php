<!DOCTYPE html>
<html>
<head>
	<title>Expanded Withholding Tax and Government Money Payment</title>
	<link rel="stylesheet" href="<?php echo base_url().PATH_CSS ?>reports.css" type="text/css" />
</head>
<body>
	<?php $total_th = 10?>
	<table class="table-max">
		<tr>
			<td class="align-c" colspan="<?php echo $total_th;?>">
			    <table class="table-max f-size-12">
				    <tr>
						<td width="150"><img src="<?php echo base_url().PATH_IMG ?>doh_logo.png" width=90 height=90></img></td>
						<td align="center" width="600"><?php echo nbs(5)?>Republic of the Philippines<br><?php echo nbs(5)?>DEPARTMENT OF HEALTH</td>
						<td width="150">&nbsp;</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td colspan="<?php echo $total_th;?>">&nbsp;</td>
		</tr>
		<tr>
			<td class="align-c f-size-12" colspan="<?php echo $total_th;?>"><?php echo !EMPTY($office_name['name']) ? $office_name['name'] : '';?></td>
		</tr>
		<tr>
			<td class="align-c f-size-12" colspan="<?php echo $total_th;?>">FOR THE PERIOD OF <?php echo $payroll_period_text;?></td>
		</tr>
		<tr>
			<td height="10"></td>
		</tr>
	</table>
	<table class="table-max">
	<thead>
		<tr>
		<th class="td-border-4 align-c f-size-12" width="30">No.</th>
		<th class="td-border-4 align-c f-size-12" width="">ID Number</th>
		<th class="td-border-2 align-c f-size-12" width="">TIN</th>
		<th class="td-border-2 align-c f-size-12" width="">LASTNAME</th>
		<th class="td-border-2 align-c f-size-12" width="">FIRSTNAME</th>
		<th class="td-border-2 align-c f-size-12" width="">NET SALARY</th>
		<th class="td-border-2 align-c f-size-12" width="">GMP</th>
		<th class="td-border-2 align-c f-size-12" width="">EWT</th>
		<th class="td-border-2 align-c f-size-12" width="">OFFICE</th>
		<th class="td-border-2 align-c f-size-12" width="">PRC NUMBER</th>
		
		</tr>
	</thead>
	<tbody>
	<?php 
		$page_net_salary   	= 0;
		$page_gmp			= 0;
		$page_ewt		   	= 0;
		
		$total_net_salary   = 0;
		$total_gmp			= 0;
		$total_ewt		   	= 0;
	?>
		<?php foreach ($header as $hdr):?>
		
		<tr>
			<td class="td-border-4 f-size-12"><?php echo ++$ctr;?></td>
			<td class="td-border-bottom td-border-right align-c f-size-12">
				<?php echo $hdr['agency_employee_id']?>
			</td>
			<td class="td-border-bottom td-border-right align-c f-size-12">
				<?php echo (empty($hdr['identification_value'])) ? N_A : format_identifications($hdr['identification_value'], $hdr['format'])?>
			</td>
			<td class="td-border-bottom td-border-right f-size-12"><?php echo $hdr['last_name']?></td>
			<td class="td-border-bottom td-border-right f-size-12"><?php echo $hdr['first_name']?></td>
			<td class="td-border-bottom td-border-right align-r f-size-12">
			<?php
				$page_net_salary   	+= $hdr['net_pay'];
				$total_net_salary	+= $hdr['net_pay'];
				echo number_format($hdr['net_pay'],2)?>
			</td>
			<td class="td-border-bottom td-border-right align-r f-size-12">
				<?php 
					$page_gmp	+= $hdr['gmp_amount'];
					$total_gmp 	+= $hdr['gmp_amount'];
					echo number_format($hdr['gmp_amount'],2)?>
			</td>
			<td class="td-border-bottom td-border-right align-r f-size-12">
				<?php 
					$page_ewt	+= $hdr['ewt_amount'];
					$total_ewt	+= $hdr['ewt_amount'];
					echo number_format($hdr['ewt_amount'],2)?>
			</td>
			<td class="td-border-bottom td-border-right f-size-12">
				<?php echo $hdr['office_name']?>
			</td>
			<td class="td-border-bottom td-border-right align-c f-size-12">
				<?php echo (empty($hdr['license_no'])) ? N_A : strtoupper($hdr['license_no'])?>
			</td>
		</tr>
			<?php if($ctr%15 == 0 OR count($header) == $ctr):?>
			
			<tr>
				<th class="td-border-4 align-c f-size-12"><?php echo ($ctr%15 == 0) ? 15 :  $ctr%15?></th>
				<th class="td-border-2 align-c f-size-12">PAGE TOTAL</th>
				<th class="td-border-2 align-c f-size-12">---</th>
				<th class="td-border-2 align-c f-size-12">---</th>
				<th class="td-border-2 align-c f-size-12">---</th>
				<th class="td-border-2 align-c f-size-12"><?php echo number_format($page_net_salary,2);?></th>
				<th class="td-border-2 align-c f-size-12"><?php echo number_format($page_gmp,2);?></th>
				<th class="td-border-2 align-c f-size-12"><?php echo number_format($page_ewt,2);?></th>
				
				<th class="td-border-2 align-c f-size-12">---</th>
				<th class="td-border-2 align-c f-size-12">---</th>
			</tr>
			<?php 
				$page_net_salary   	= 0;
				$page_gmp      		= 0;
				$page_ewt		 	= 0;
			?>
			<?php if(count($header) != $ctr):?>
			</tbody>
			</table>
			
			<pagebreak />
			<table class="table-max">
				<tr>
					<td class="align-c" colspan="<?php echo $total_th;?>">
					    <table class="table-max f-size-12">
						    <tr>
								<td width="150"><img src="<?php echo base_url().PATH_IMG ?>doh_logo.png" width=90 height=90></img></td>
								<td align="center" width="600"><?php echo nbs(5)?>Republic of the Philippines<br><?php echo nbs(5)?>DEPARTMENT OF HEALTH</td>
								<td width="150">&nbsp;</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td colspan="<?php echo $total_th;?>">&nbsp;</td>
				</tr>
				<tr>
					<td class="align-c f-size-12" colspan="<?php echo $total_th;?>"><?php echo !EMPTY($office_name['name']) ? $office_name['name'] : '';?></td>
				</tr>
				<tr>
					<td class="align-c f-size-12" colspan="<?php echo $total_th;?>">FOR THE PERIOD OF <?php echo $payroll_period_text;?></td>
				</tr>
				<tr>
					<td height="10"></td>
				</tr>
			</table>
				<table>
				<thead>
		<tr>
		<th class="td-border-4 align-c f-size-12" width="30">No.</th>
		<th class="td-border-4 align-c f-size-12" width="">ID Number</th>
		<th class="td-border-2 align-c f-size-12" width="">TIN</th>
		<th class="td-border-2 align-c f-size-12">LASTNAME</th>
		<th class="td-border-2 align-c f-size-12">FIRSTNAME</th>
		<th class="td-border-2 align-c f-size-12" width="">NET SALARY</th>
		<th class="td-border-2 align-c f-size-12" width="">GMP</th>
		<th class="td-border-2 align-c f-size-12" width="">EWT</th>
		<th class="td-border-2 align-c f-size-12">OFFICE</th>
		<th class="td-border-2 align-c f-size-12" width="">PRC NUMBER</th>
		
		</tr>
				</thead>
				<tbody>
        		<?php endif;?>  
			<?php endif;?>
		<?php endforeach;?>
		<tr>
			<th class="td-border-4 align-c f-size-12"><?php echo $ctr;?></th>
			<th class="td-border-2 align-c f-size-12">GRAND TOTAL</th>
			<th class="td-border-2 align-c f-size-12">---</th>
			<th class="td-border-2 align-c f-size-12">---</th>
			<th class="td-border-2 align-c f-size-12">---</th>
			<th class="td-border-2 align-c f-size-12"><?php echo number_format($total_net_salary,2)?></th>
			<th class="td-border-2 align-c f-size-12"><?php echo number_format($total_gmp,2);?></th>
			<th class="td-border-2 align-c f-size-12"><?php echo number_format($total_ewt,2);?></th>
			<th class="td-border-2 align-c f-size-12">---</th>
			<th class="td-border-2 align-c f-size-12">---</th>
		</tr>
	</tbody>
	</table>
</body>
</html>