<!DOCTYPE html>
<html>
<?php if(count($results)>0):?>
<head>
	<title>General Payslip for regulars and Non Careers</title>
	<link rel="stylesheet" href="<?php echo base_url().PATH_CSS ?>reports.css" type="text/css" />
    <style type="text/css">
        td { padding: 2px !important }
    </style>
</head>

<body>
<?php 
	$display_cnt = 0;
	for($ctr=0;$ctr<count($results);$ctr++):
		$display_cnt++;
		if(ISSET($results[$ctr])):
?>

<div style="overflow: hidden;">
<div style="float: left; width: 49.5%;">
	<div style="overflow: hidden;">
		<table class="table-max">
			<tr>
				<td class="td-border-4 td-border-left" height="30px" colspan="2"><b><?php echo $results[$ctr]['header']['office_name'];?></b></td>
			</tr>
			<tr>
				<td class="td-border-left td-border-right" colspan="2">Note: Please submit any corrections in your payslip before the 16th of the month.</td>
			</tr>
			<tr>
				<td class="td-border-top td-border-bottom td-border-left"><?php echo $results[$ctr]['header']['employee_name'];?></td>
				<td class="td-border-top td-border-bottom td-border-right td-right-bottom"><?php echo $results[$ctr]['date']['payroll_period'];?></td>
			</tr>
		</table>
		<div style="float: left; width: 50%;">
			<table class="table-max">
				<?php 
				foreach ($results[$ctr]['compensations'] as $compensation):?>
				<tr>
					<td class="td-border-left td-left-bottom"><?php echo $compensation['compensation_code'];?></td>
					<td class="td-left-bottom align-r">:</td>
					<td class="td-border-right td-right-bottom"><?php echo ISSET($compensation['amount'])? number_format($compensation['amount'], 2) : "0.00";?></td>
				</tr>
				<?php 
					if($compensation['less_absence_flag'] == "Y"):?>
				<tr>
					<td class="td-border-left td-left-bottom">Less(Abs)</td>
					<td class="td-left-bottom align-r">:</td>
					<td class="td-border-right td-right-bottom"><?php echo ISSET($compensation['less_amount'])? number_format($compensation['less_amount'], 2) : "0.00";?></td>
				</tr>
				<?php 
					endif;
				endforeach;
				?>
				<tr>
					<td class="td-border-left td-border-right td-center-middle" colspan="3"> -----------------------------</td>
				</tr>
				<tr>
					<td class="td-border-left td-left-bottom">GROSS INCOME</td>
					<td class="td-left-bottom align-r">:</td>
					<td class="td-border-right td-right-bottom"><?php echo number_format($results[$ctr]['header']['total_income'], 2);?></td>
				</tr>
				<tr>
					<td class="td-border-left td-left-bottom">DEDUCTIONS</td>
					<td class="td-left-bottom align-r">:</td>
					<td class="td-border-bottom td-border-right td-right-bottom">(-)<?php echo number_format($results[$ctr]['header']['total_deductions'], 2);?></td>
				</tr>
				<tr>
					<td class="td-border-bottom td-border-left td-left-bottom">NET PAY</td>
					<td class="td-border-bottom td-left-bottom align-r">:</td>
					<td class="td-border-bottom td-border-right td-right-bottom"><?php echo number_format($results[$ctr]['header']['net_pay'], 2);?></td>
				</tr>
				<tr>
					<td class="td-border-right td-border-left td-center-bottom" colspan="3"><b>&gt;DEDUCTIONS&lt;</b></td>
				</tr>
				<?php 
				$count = count($results[$ctr]['deduction_1']);
				foreach($results[$ctr]['deduction_1'] as $index => $deduct_1):
					if($count-1 == $index):
				?>
				<tr>
					<td class="td-border-bottom td-border-left td-left-bottom"><?php echo $deduct_1['deduction_code'];?></td>
					<td class="td-border-bottom td-left-bottom align-r"><?php echo ISSET($deduct_1['paid_count'])?$deduct_1['paid_count'] : "0" ?>:</td>
					<td class="td-border-bottom td-border-right td-right-bottom"><?php echo ISSET($deduct_1['amount'])? number_format($deduct_1['amount'], 2) : "0.00";?></td>
				</tr>
				<?php 
					else:?>
				<tr>
					<td class="td-border-left td-left-bottom"><?php echo $deduct_1['deduction_code'];?></td>
					<td class="td-left-bottom align-r"><?php echo ISSET($deduct_1['paid_count'])?$deduct_1['paid_count'] : "0" ?>:</td>
					<td class="td-border-right td-right-bottom"><?php echo ISSET($deduct_1['amount'])? number_format($deduct_1['amount'], 2): "0.00";?></td>
				</tr>	
				<?php 
					endif;
				endforeach;
				?>	
			</table>
		</div>
		<div style="float: right; width: 50%;">
			<table  width="100%">
				<?php 
				$count = count($results[$ctr]['deduction_2']);
				foreach($results[$ctr]['deduction_2'] as $index => $deduct_2):
					if($count-1 == $index):
				?>
				<tr>
					<td class="td-border-bottom td-left-bottom"><?php echo $deduct_2['deduction_code'];?></td>
					<td class="td-border-bottom td-left-bottom align-r"><?php echo ISSET($deduct_2['paid_count'])?$deduct_2['paid_count'] : "0" ?>:</td>
					<td class="td-border-bottom td-border-right td-right-bottom"><?php echo ISSET($deduct_2['amount'])? number_format($deduct_2['amount'], 2) : "0.00";?></td>
				</tr>
				<?php 
					else:?>
				<tr>
					<td class="td-left-bottom"><?php echo $deduct_2['deduction_code'];?></td>
					<td class="align-r"><?php echo ISSET($deduct_2['paid_count'])?$deduct_2['paid_count']: "0" ?>:</td>
					<td class="td-border-right td-right-bottom"><?php echo ISSET($deduct_2['amount'])? number_format($deduct_2['amount'], 2) : "0.00";?></td>
				</tr>	
				<?php 
					endif;
				endforeach;
				?>	
				<tr>
					<td class="td-left-bottom">REM:GPN</td>
					<td class="align-r">:</td>
					<td class="td-border-right">&nbsp;</td>
				</tr>	
				<tr>
					<td class="td-left-bottom">PHIC ID#</td>
					<td class="align-r">:</td>
					<td class="td-border-right"><?php echo ISSET($results[$ctr]['id']['identification_value'])? $results[$ctr]['id']['identification_value']: "N/A";?></td>
				</tr>
				<tr>
					<td class="td-border-bottom td-left-bottom">ID NO.</td>
					<td class="td-border-bottom align-r">:</td>
					<td class="td-border-bottom td-border-right"><?php echo $results[$ctr]['header']['agency_employee_id']?></td>
				</tr>
				<tr>
					<td class="td-left-bottom">NET :</td>
					<td class="td-border-right td-right-bottom" colspan="2">&nbsp;</td>
				</tr>
				<?php if($results[$ctr]['header']['payout_count'] > 1) { ?>
				<tr>
					<td class="td-left-bottom">01-15</td>
					<td class="align-r">:</td>
					<td class="td-border-right td-right-bottom"><?php echo number_format($results[$ctr]['half_1'], 2);?></td>
				</tr>
				<tr>
					<td class="td-left-bottom">16-<?php echo date('t', strtotime($results[$ctr]['date_2']))?></td>
					<td class="align-r">:</td>
					<td class="td-border-right td-right-bottom"><?php echo number_format($results[$ctr]['half_2'], 2);?></td>
				</tr>
				<?php } else { ?>
				<tr>
					<td class="td-left-bottom">01-<?php echo date('t', strtotime($results[$ctr]['date_2']))?></td>
					<td class="align-r">:</td>
					<td class="td-border-right td-right-bottom"><?php echo number_format($results[$ctr]['half_1'], 2);?></td>
				</tr>
				<tr>
					<td colspan="3" class="td-left-bottom td-border-right">&nbsp;</td>
				</tr>
				<?php } ?>
				<tr>
					<td class="td-border-right td-center-bottom" colspan="3"><b>----- Nothing Follows -----</td>
				</tr>
				<?php 
				$total_cnt = count($results[$ctr]['compensations']) + count($results[$ctr]['deduction_1']) + 1;
				if($total_cnt < 14)
				{
					$count += 14 - $total_cnt;
				}
				$end = $total_cnt - $count;

				for($end_ctr=$end;$end_ctr>=0;$end_ctr--):
				?>
				<tr>
					<td class="td-border-right" colspan="3">&nbsp;</td>
				</tr>				
				<?php 
				endfor;
				?>
				<tr>
					<td class="td-border-bottom td-border-right" colspan="3">&nbsp;</td>
				</tr>
			</table>
		</div>
	</div>
</div>
<?php
	$ctr++;	
	if(ISSET($results[$ctr])):
?>
<div style="float: right; width: 49.5%;">
	<div style="overflow: hidden;">
		<table  width="100%">
			<tr>
				<td class="td-border-4 td-border-left" height="30px" colspan="2"><b><?php echo $results[$ctr]['header']['office_name'];?></b></td>
			</tr>
			<tr>
				<td class="td-border-left td-border-right" colspan="2">Note: Please submit any corrections in your payslip before the 16th of the month.</td>
			</tr>
			<tr>
				<td class="td-border-top td-border-bottom td-border-left"><?php echo $results[$ctr]['header']['employee_name'];?></td>
				<td class="td-border-top td-border-bottom td-border-right td-right-bottom"><?php echo $results[$ctr]['date']['payroll_period'];?></td>
			</tr>
		</table>
		<div style="float: left; width: 50%;">
			<table width="100%">
				<?php 
				foreach ($results[$ctr]['compensations'] as $compensation):?>
				<tr>
					<td class="td-border-left td-left-bottom"><?php echo $compensation['compensation_code'];?></td>
					<td class="td-left-bottomc align-r">:</td>
					<td class="td-border-right td-right-bottom"><?php echo ISSET($compensation['amount'])? number_format($compensation['amount'],2): "0.00";?></td>
				</tr>
				<?php 
					if($compensation['less_absence_flag'] == "Y"):?>
				<tr>
					<td class="td-border-left td-left-bottom">Less(Abs)</td>
					<td class="td-left-bottom align-r">:</td>
					<td class="td-border-right td-right-bottom"><?php echo ISSET($compensation['less_amount'])? number_format($compensation['less_amount'],2): "0.00";?></td>
				</tr>
				<?php 
					endif;
				endforeach;
				?>
				<tr>
					<td class="td-border-left td-border-right td-center-middle" colspan="3"> -----------------------------</td>
				</tr>
				<tr>
					<td class="td-border-left td-left-bottom">GROSS INCOME</td>
					<td class="td-left-bottom align-r">:</td>
					<td class="td-border-right td-right-bottom"><?php echo number_format($results[$ctr]['header']['total_income'],2);?></td>
				</tr>
				<tr>
					<td class="td-border-left td-left-bottom">DEDUCTIONS</td>
					<td class="td-left-bottom align-r">:</td>
					<td class="td-border-bottom td-border-right td-right-bottom">(-)<?php echo number_format($results[$ctr]['header']['total_deductions'],2);?></td>
				</tr>
				<tr>
					<td class="td-border-bottom td-border-left td-left-bottom">NET PAY</td>
					<td class="td-border-bottom td-left-bottom align-r">:</td>
					<td class="td-border-bottom td-border-right td-right-bottom"><?php echo number_format($results[$ctr]['header']['net_pay'],2);?></td>
				</tr>
				<tr>
					<td class="td-border-right td-border-left td-center-bottom" colspan="3"><b>&gt;DEDUCTIONS&lt;</b></td>
				</tr>
				<?php 
				$count = count($results[$ctr]['deduction_1']);
				foreach($results[$ctr]['deduction_1'] as $index => $deduct_1):
					if($count-1 == $index):
				?>
				<tr>
					<td class="td-border-bottom td-border-left td-left-bottom"><?php echo $deduct_1['deduction_code'];?></td>
					<td class="td-border-bottom td-left-bottom align-r"><?php echo ISSET($deduct_1['paid_count'])?$deduct_1['paid_count'] : "0" ?>:</td>
					<td class="td-border-bottom td-border-right td-right-bottom"><?php echo ISSET($deduct_1['amount'])? number_format($deduct_1['amount'],2): "0.00";?></td>
				</tr>
				<?php 
					else:?>
				<tr>
					<td class="td-border-left td-left-bottom"><?php echo $deduct_1['deduction_code'];?></td>
					<td class="td-left-bottom align-r"><?php echo ISSET($deduct_1['paid_count'])?$deduct_1['paid_count'] : "0" ?>:</td>
					<td class="td-border-right td-right-bottom"><?php echo ISSET($deduct_1['amount'])? number_format($deduct_1['amount'],2): "0.00";?></td>
				</tr>	
				<?php 
					endif;
				endforeach;
				?>	
			</table>
		</div>
		<div style="float: right; width: 50%;">
			<table  width="100%">
				<?php 
				$count = count($results[$ctr]['deduction_2']);
				foreach($results[$ctr]['deduction_2'] as $index => $deduct_2):
					if($count-1 == $index):
				?>
				<tr>
					<td class="td-border-bottom td-left-bottom"><?php echo $deduct_2['deduction_code'];?></td>
					<td class="td-border-bottom td-left-bottom align-r"><?php echo ISSET($deduct_2['paid_count'])?$deduct_2['paid_count'] : "0" ?>:</td>
					<td class="td-border-bottom td-border-right td-right-bottom"><?php echo ISSET($deduct_2['amount'])? number_format($deduct_2['amount'],2): "0.00";?></td>
				</tr>
				<?php 
					else:?>
				<tr>
					<td class="td-left-bottom"><?php echo $deduct_2['deduction_code'];?></td>
					<td class="align-r"><?php echo ISSET($deduct_2['paid_count'])?$deduct_2['paid_count'] : "0" ?>:</td>
					<td class="td-border-right td-right-bottom"><?php echo ISSET($deduct_2['amount'])? number_format($deduct_2['amount'],2): "0.00";?></td>
				</tr>	
				<?php 
					endif;
				endforeach;
				?>
				<tr>
					<td class="td-left-bottom">REM:GPN</td>
					<td class="align-r">:</td>
					<td class="td-border-right">&nbsp;</td>
				</tr>	
				<tr>
					<td class="td-left-bottom">PHIC ID#</td>
					<td class="align-r">:</td>
					<td class="td-border-right"><?php echo ISSET($results[$ctr]['id']['identification_value'])? $results[$ctr]['id']['identification_value']: "N/A";?></td>
				</tr>
				<tr>
					<td class="td-border-bottom td-left-bottom">ID NO.</td>
					<td class="td-border-bottom align-r">:</td>
					<td class="td-border-bottom td-border-right"><?php echo $results[$ctr]['header']['agency_employee_id']?></td>
				</tr>
				<tr>
					<td class="td-left-bottom">NET:</td>
					<td class="td-border-right td-right-bottom" colspan="2">&nbsp;</td>
				</tr>
				<?php if($results[$ctr]['header']['payout_count'] > 1) { ?>
				<tr>
					<td class="td-left-bottom">01-15</td>
					<td class="align-r">:</td>
					<td class="td-border-right td-right-bottom"><?php echo number_format($results[$ctr]['half_1'], 2);?></td>
				</tr>
				<tr>
					<td class="td-left-bottom">16-<?php echo date('t', strtotime($results[$ctr]['date_2']))?></td>
					<td class="align-r">:</td>
					<td class="td-border-right td-right-bottom"><?php echo number_format($results[$ctr]['half_2'], 2);?></td>
				</tr>
				<?php } else { ?>
				<tr>
					<td class="td-left-bottom">01-<?php echo date('t', strtotime($results[$ctr]['date_2']))?></td>
					<td class="align-r">:</td>
					<td class="td-border-right td-right-bottom"><?php echo number_format($results[$ctr]['half_1'], 2);?></td>
				</tr>
				<tr>
					<td colspan="3" class="td-left-bottom td-border-right">&nbsp;</td>
				</tr>
				<?php } ?>
				<tr>
					<td class="td-border-right td-center-bottom" colspan="3"><b>----- Nothing Follows -----</td>
				</tr>
				<?php 
				$total_cnt = count($results[$ctr]['compensations']) + count($results[$ctr]['deduction_1']) + 1;
				if($total_cnt < 14)
				{
					$count += 14 - $total_cnt;
				}
				$end = $total_cnt - $count;
				for($end_ctr=$end;$end_ctr>=0;$end_ctr--):
				?>
				<tr>
					<td class="td-border-right" colspan="3">&nbsp;</td>
				</tr>				
				<?php 
				endfor;
				?>
				<tr>
					<td class="td-border-bottom td-border-right" colspan="3">&nbsp;</td>
				</tr>
			</table>
		</div>
	</div>
</div>
<?php 
	$total_cnt1 = count($results[$ctr - 1]['compensations']) + count($results[$ctr - 1]['deduction_1']);
	$total_cnt2 = count($results[$ctr]['compensations']) + count($results[$ctr]['deduction_1']);
	if($total_cnt1 <= 13 AND $total_cnt2 <= 13)
	{
		if($display_cnt%2 == 0 )
		{
			echo "<pagebreak />";
		}
		else
		{
			echo "<br><br>";
		}
	}
	else
	{
		echo "<pagebreak />";
	}
?>

</div>
	<?php else:?>
	<?php endif;?>
	<?php
		endif;
	endfor;
?>
</body>

<?php else :?>

<div class="wrapper">
	<form id="test_report">
		<p style="text-align: center">No data available.</p>
	</form>
</div>

<?php endif;?>

</html>
