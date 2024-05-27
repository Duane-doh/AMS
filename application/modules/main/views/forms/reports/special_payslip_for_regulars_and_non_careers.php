<!DOCTYPE html>
<html>
<?php if(count($results)>0):?>
<head>
	<title>Special Payslip for regulars and non Careers</title>
	<link rel="stylesheet" href="<?php echo base_url().PATH_CSS ?>reports.css" type="text/css" />
</head>
<body>
<?php 
	$results_ctr = 0;
	$i=0;
	while ($results_ctr<count($results)):
	if(ISSET($results[$results_ctr]['details'])):
?>

<div style="overflow: hidden;margin-bottom: 10px;">
	<table width="100%">
		<tr>
			<?php 
			for($ctr_1=0;$ctr_1<3;$ctr_1++):
				if(ISSET($results[$results_ctr]['details'])):		
				$detail_ctr = count($results[$results_ctr]['details']);
			?>
			<td class="f-size-14">
				<table width="100%">
					<tr class="td-border-4">
						<td class="td-left-bottom pad-2" height="50" width="90" style="vertical-align:top">
							<br><img src="<?php echo base_url().PATH_IMG ?>doh_logo.png" width=80 height=70>
						</td>
					    <td class="td-left-bottom f-size-12" colspan="4">
							<b>
								<br><?php echo $office['name']?><br><br><?php echo $results[$results_ctr]['header']['employee_name'];?>
								<br><?php echo $results[$results_ctr]['header']['position_name'];?>
								<br><?php echo $compensation_name['compensation_name'] . ", CY " . $year;?>
							</b>
						</td>
					</tr>
					<tr>
						<td class="td-border-left td-border-right f-size-14" height="3" colspan="4">&nbsp;</td>
						<td class="td-border-bottom td-border-right td-left-bottom f-size-14 pad-2" rowspan="<?php echo ($detail_ctr + 2);?>">ID No. : <?php echo $results[$results_ctr]['header']['agency_employee_id']?></td>
					</tr>
					<?php 
						$count = count($results[$results_ctr]['details']);
						$compensations = 0;
						$deductions = 0;
						foreach($results[$results_ctr]['details'] as $index => $details):
					?>
					<tr>
						<?php 
							if(ISSET($details['compensation_id'])):
							$compensations = $compensations + $details['amount'];
						?>
							<td class="td-border-left td-left-bottom f-size-14" height="15"><br></td>
					  		<td class="td-left-bottom f-size-14"><b><?php echo $details['compensation_code'];?></b></td>
					  	<?php 
					  		else:
					  		$deductions = $deductions + $details['amount'];
					  	?>
					  		<td class="td-border-left td-center-bottom f-size-14  pad-2" height="15">Less :</td>
					  		<td class="td-left-bottom f-size-14  pad-2"><b><?php echo $details['deduction_code'];?></b></td>
					    <?php endif;?>
					    <td class="td-left-bottom f-size-14"><b>:</b></td>
					    <?php if($count -1 == $index):?>
					    	<td class="td-border-bottom td-border-right td-right-bottom f-size-14 pad-2"><?php echo number_format($details['amount'], 2);?></td>
						<?php else:?>
							<td class="td-border-right td-right-bottom f-size-14  pad-2"><?php echo number_format($details['amount'], 2);?></td>
						<?php endif;?>
					</tr>
					<?php 
						endforeach;
					?>
					<tr>
						<td class="td-border-bottom td-border-left td-left-bottom f-size-14" height="15"><br></td>
						<td class="td-border-bottom td-left-bottom f-size-14  pad-2"><b>Total Amount</b></td>
						<td class="td-border-bottom td-left-bottom f-size-14  pad-2"><b>:</b></td>
						<td class="td-border-bottom td-border-right td-right-bottom f-size-14  pad-2"><?php $net_dif = $compensations -$deductions; echo number_format($net_dif, 2);?></td>
					</tr>
					<tr>
				    	<td class="td-border-4 td-left-bottom f-size-10  pad-2" colspan="5" height="15"><i>**For Inquiry pls. call DOH Personnel Payroll Unit (651-7800) loc. 4205/4206</i></td>
				    </tr>
				</table>
			</td>
			<td width="10">
				
			</td>
			<?php else:?>
			<td style="color: white">
				----------------------------------------------------------------------------------------------------------------------------------
			</td>
			<td width="10">
				
			</td>
			<?php 
				endif;
			$results_ctr++; 
			endfor;
			?>
		</tr>
	</table>
</div>
<?php
		endif;
	endwhile;
?>
</div>

</body>
<?php else :?>

<div class="wrapper">
	<form id="test_report">
		<p style="text-align: center">No data available.</p>
	</form>
</div>

<?php endif;?>
</html>