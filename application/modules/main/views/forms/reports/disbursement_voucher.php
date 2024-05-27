
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<html>
<head>
	
	<meta http-equiv="content-type" content="text/html; charset=iso-8859-1"/>
	<title>Disbursement Voucher</title>
	<link rel="stylesheet" href="<?php echo base_url().PATH_CSS ?>reports.css" type="text/css" />
</head>

<body>
<table class="table-max f-size-12" border="1">
<tr>
	<td colspan="2" class="align-l pad-2 pad-left-20" style="border-right-style: hidden;"><img src="<?php echo base_url().PATH_IMG ?>doh_logo.png" width=61 height=61></img></td>
	<td align="center" width="480px" class="td-border-top td-border-left" colspan="4" border="1">
		<table width="100%">
			<tbody>
				<tr>
					<td class="align-c f-size-10">Republic of the Philippines</td>
				</tr>
				<tr>
					<td class="align-c f-size-10">San Lazaro Compound, Sta. Cruz, Manila</td>
				</tr>
				<tr>
					<td class="align-c f-size-12"><b>DISBURSEMENT VOUCHER</b></td>
				</tr>
			</tbody>
		</table>
	</td>
	<td class="td-border-top td-border-left td-border-right f-size-9 td-left-top" colspan="3">
		<table width="100%">
			<tbody>
				<tr>
					<td class="bold f-size-9 td-left-top">Fund Cluster:</td>
				</tr>
				<tr>
					<td class="td-border-bottom align-c"><?php echo nbs(30);?></td>
				</tr>
				<tr>
					<td class="bold f-size-9">Date:</td>
				</tr>
				<tr>
					<td class="bold f-size-9">&nbsp;</td>
				</tr>
				<tr>
					<td class="bold f-size-9">DV No.:</td>
				</tr>
			</tbody>
		</table>
	</td>
</tr>
</table>
<table class="table-max" border="1">
	<tr>
		<td class="td-border-3 pad-2">Mode of<br>Payment</td>
		<td class="td-border-4 pad-2" colspan="8" valign="middle">
			<table class="table-max f-size-9">
				<tr>
					<td width="25%" class="pad-2"> &#9633; MDS Check</td>
					<td width="25%" class="pad-2"> &#9633; Commercial Check</td>
					<td width="25%" class="pad-2 pad-left-50"> &#9633; ADA</td>
					<td width="25%" class="pad-2 pad-left-50"> &#9633; Others</td>
				</tr>
			</table>
			
		</td>
	</tr>
	<tr>
		<td class="td-border-left td-border-bottom" rowspan="2" valign="middle">Payee</td>
		<td class="td-border-left td-border-bottom align-c f-size-9" rowspan="2" colspan="3"><b><?php echo isset($voucher_info['employee_name']) ? $voucher_info['employee_name']: ''?></b></td>
		<td class="td-border-left td-border-right pad-2 f-size-9" colspan="4">TIN/Employee No.</td>
		<td class="td-border-left td-border-right f-size-9">OR/BUR No.</td>
	</tr>
	<tr>
		<td class="td-border-left td-border-bottom td-border-right pad-2" colspan="4"><center><?php echo isset($voucher_info['agency_employee_id']) ? $voucher_info['agency_employee_id']: ''?></center></td>
		<td class="td-border-left td-border-bottom td-border-right"></td>
	</tr>
	<tr>
		<td class="td-border-left td-border-bottom pad-2"valign="middle">Address</td>
		<td class="td-border-left td-border-right td-border-bottom pad-2" colspan="8">San Lazaro Compound, Sta. Cruz, Manila</td>
	</tr>
</table>
<table class="table-max f-size-12" border="1">
	<tr>
		<td class="td-border-bottom td-border-left pad-2 align-c" width="400px" colspan="3">Particulars</td>
		<td class="td-border-bottom td-border-left pad-2 align-c" width="150px" colspan="2">Responsibility Center</td>
		<td class="td-border-bottom td-border-left pad-2 align-c" width="150px" colspan="2">MFO/PAP</td>
		<td class="td-border-bottom td-border-left td-border-right pad-2 align-c"  width="150px" colspan="1">Amount</td>
	</tr>
	<tr>
		<td class="td-border-left pad-2" colspan="3"><?php echo $voucher_info['voucher_description'];?></td>
		<td class="td-border-left pad-2 align-c" colspan="2"><?php echo $voucher_info['responsibility_center_code'];?></td>
		<td class="td-border-left pad-2 align-c" colspan="2">&nbsp;</td>
		<td class="td-border-left td-border-right pad-2 align-c" colspan="1">&nbsp;</td>
	</tr>
	<tr>
		<td class="td-border-left" colspan="3">
			<table class="table-max">
				<tr>
					<?php if(count($compensations) > 0):?>
					<td width="150px" class="pad-2">COMPENSATIONS</td>
					<td width="100px" class="pad-2">&nbsp;</td>
					<?php endif;?>
					<?php if(count($deductions) > 0):?>
					<td width="150px" class="pad-2" style="padding-left: 10px;">DEDUCTIONS</td>
					<td width="100px" class="pad-2 align-c">&nbsp;</td>
					<?php endif;?>
				</tr>
				
				<?php 
				for($ctr=0;$ctr<=$count;$ctr++):
					$com_isset = ISSET($compensations[$ctr]);
					$ded_isset = ISSET($deductions[$ctr]);
				?>
				<tr>
					<?php if(count($compensations) > 0):?>
						<?php 
							if(($ctr) != $com_count):
								if($com_isset):
						?>	
									<td class="pad-2"><?php echo $compensations[$ctr]['compensation_name']?></td>
									<td class="pad-2 align-r"><?php echo number_format($compensations[$ctr]['amount'],2);?></td>
						<?php 
								endif;
							else :
						?>
								<td class="pad-2">GROSS INCOME</td>
								<td class="td-border-top pad-2 align-r"><?php echo number_format($com_total,2);?></td>
						<?php endif;?>
					<?php endif;?>
					<?php if(count($deductions) > 0):?>
						<?php 
							if(($ctr) != $ded_count):
								if($ded_isset):
						?>	
									<td class="pad-2" style="padding-left: 10px;"><?php echo $deductions[$ctr]['deduction_name']?></td>
									<td class="pad-2 align-r"><?php echo number_format($deductions[$ctr]['amount'],2);?></td>
						<?php 
								endif;
							else :
						?>
								<td class="pad-2" style="padding-left: 10px;">TOTAL</td>
								<td class="td-border-tb pad-2 align-r"><?php echo number_format($ded_total,2);?></td>
						<?php endif;?>
					<?php endif;?>
				</tr>
				<?php endfor;?>
				<?php if(count($deductions) > 0 AND count($compensations) > 0):?>
				<tr>
					<td class="pad-2">DEDUCTIONS</td>
					<td class="td-border-bottom pad-2 align-r"><?php echo number_format($ded_total,2);?></td>
					<td class="pad-2" colspan="2">&nbsp;</td>
				</tr>
				<?php endif;?>
				<tr>
					<td class="pad-2">NET AMOUNT DUE</td>
					<td class="td-border-boto pad-2 align-r"><?php echo number_format($amount_due,2);?></td>
					<td class="pad-2" colspan="2">&nbsp;</td>
				</tr>
				
			</table>
		</td>
		<td class="td-border-left pad-2" colspan="2">&nbsp;</td>
		<td class="td-border-left pad-2" colspan="2">&nbsp;</td>
		<td class="td-border-left td-border-right pad-2" colspan="1">&nbsp;</td>
	</tr>
	<tr>
		<td class="td-border-left pad-2" colspan="3">&nbsp;</td>
		<td class="td-border-left pad-2" colspan="2">&nbsp;</td>
		<td class="td-border-left pad-2" colspan="2">&nbsp;</td>
		<td class="td-border-left td-border-right pad-2" colspan="1">&nbsp;</td>
	</tr>
	<tr>
		<td class="td-border-left pad-2" colspan="3"><?php echo str_replace('<P> </P>','<br>',$voucher_info['voucher_footer']);?></td>
		<td class="td-border-left pad-2" colspan="2">&nbsp;</td>
		<td class="td-border-left pad-2" colspan="2">&nbsp;</td>
		<td class="td-border-left td-border-right pad-2" colspan="1">&nbsp;</td>
	</tr>
	<tr>
		<td class="td-border-bottom td-border-left pad-2 align-r bold" colspan="3">Amount Due</td>
		<td class="td-border-bottom td-border-left pad-2" colspan="2">&nbsp;</td>
		<td class="td-border-bottom td-border-left pad-2" colspan="2">&nbsp;</td>
		<td class="td-border-4 pad-2 align-r bold" colspan="1"><?php echo number_format($amount_due,2);?></td>
	</tr>
</table>
<table class="table-max" border="1">
	<tr>
		<td class="td-border-left td-border-right f-size-9" colspan="9">A. Certified Expenses/Cash Advance necessary, lawful and incurred under my direct supervision.</td>
	</tr>
	<tr>
		<td class="td-border-left td-border-right" height="40" colspan="9">&nbsp;</td>
	</tr>
	<tr>
		<td class="td-border-left td-border-right align-c f-size-9" colspan="9">
		<b><?php echo $signatory_a['signatory_name'];?></b><br>
		<?php echo $signatory_a['position_name'];?><br>
		<?php echo $signatory_a['office_name'];?>
			
		</td>
	</tr>
</table>
	
<table class="table-max" border="1">
	<tr>
		<td colspan="9" class="td-border-4 pad-2 f-size-9" valign="top">B. Accounting Entry</td>
	</tr>
	<tr>
		<td class="td-border-left pad-2 align-c f-size-9" colspan="4">Account Title</td>
		<td class="td-border-left pad-2 align-c f-size-9" colspan="2">UACS Code</td>
		<td class="td-border-left pad-2 align-c f-size-9" colspan="2">Debit</td>
		<td class="td-border-left td-border-right pad-2 align-c f-size-9">Credit</td>
	</tr>
	<tr>
		<td class="td-border-3 align-l f-size-7 pad-2" colspan="4" height="50px">
		<?php 
		if($compensations)
		{
			foreach ($compensations as $comp) {
				echo $comp['compensation_name']."<br>";
			}
		}
		if($deductions)
		{
			foreach ($deductions as $ded) {
				echo $ded['deduction_name']."<br>";
			}
		}
		?>
		</td>
		<td class="td-border-3 pad-2 align-c" colspan="2">&nbsp;</td>
		<td class="td-border-3 pad-2 align-c" colspan="2">&nbsp;</td>
		<td class="td-border-4 pad-2 align-c">&nbsp;</td>
	</tr>
	<tr>
	<b>
		<td colspan="4" class="td-border-3 pad-2 f-size-9" valign="top">C. Certified</td>
		<td colspan="5" class="td-border-4 pad-2 f-size-9" valign="top">D. Approved for Payment</td>
	</b>
	</tr>
	<tr>
		<td colspan="4" class="td-border-left pad-2 f-size-9" height="30" valign="top">
		&#9633; Cash available<br>
		&#9633; Subject to Authority to Debit Account (when applicable)<br>
		&#9633; Supporting documents complete
		</td>
		<td colspan="5" class="td-border-left td-border-right pad-2" width="80" valign="top"></td>
	</tr>
	<tr>
		<td colspan="1" class="td-border-left td-border-bottom pad-2 f-size-9" valign="top" height="30">Signature</td>
		<td colspan="3" class="td-border-left td-border-bottom pad-2" valign="top"></td>
		<td colspan="1" class="td-border-left td-border-bottom pad-2 f-size-9" valign="top">Signature</td>
		<td colspan="4" class="td-border-left td-border-bottom td-border-right pad-2" valign="top"></td>
	</tr>
	<tr>
		<td colspan="1" class="td-border-left td-border-bottom pad-2 f-size-9" width="90" valign="top">Printed Name</td>
		<td colspan="3" class="td-border-left td-border-bottom align-c pad-2 f-size-9" valign="middle"><b><?php echo isset($signatory_b['signatory_name']) ? $signatory_b['signatory_name'] :''?></b></td>
		<td colspan="1" class="td-border-left td-border-bottom pad-2 f-size-9" valign="top">Printed Name</td>
		<td colspan="4" class="td-border-left td-border-bottom td-border-right align-c pad-2 f-size-9" valign="middle"><b><?php echo isset($signatory_c['signatory_name']) ? $signatory_c['signatory_name'] :''?></b></td>
	</tr>
	<tr>
		<td colspan="1" class="td-border-left td-border-bottom pad-2 f-size-9" valign="middle" rowspan="2">Position</td>
		<td colspan="3" class="td-border-left td-border-bottom align-c pad-2 f-size-9" valign="top"><?php echo isset($signatory_b['position_name']) ? $signatory_b['position_name'] :''?></td>
		<td colspan="1" class="td-border-left td-border-bottom pad-2 f-size-9" valign="middle" rowspan="2">Position</td>
		<td colspan="4" class="td-border-left td-border-bottom td-border-right align-c pad-2 f-size-9" valign="top">	<?php echo isset($signatory_c['position_name']) ? $signatory_c['position_name'] :''?></td>
	</tr>
	<tr>
		<td colspan="3" class="td-border-left td-border-bottom align-c pad-2 f-size-9" valign="top"><?php echo isset($signatory_b['office_name']) ? $signatory_b['office_name'] :'Head, Accounting Unit/Authorized Representative'?></td>
		<td colspan="4" class="td-border-left td-border-bottom td-border-right align-c pad-2 f-size-9" valign="top"><?php echo isset($signatory_c['office_name']) ? $signatory_c['office_name'] :''?></td>
	</tr>
	<tr>
		<td colspan="1" class="td-border-left td-border-bottom pad-2 f-size-9" valign="top">Date</td>
		<td colspan="3" class="td-border-left td-border-bottom pad-2 f-size-9" valign="top"></td>
		<td colspan="1" class="td-border-left td-border-bottom pad-2 f-size-9" valign="top">Date</td>
		<td colspan="4" class="td-border-left td-border-bottom td-border-right pad-2 f-size-9" valign="top"></td>
	</tr>
	<tr>
		<td colspan="8" class="td-border-3 pad-2 f-size-9">E. Received Payment</td>
		<td width="100" class="td-border-left td-border-bottom td-border-right pad-2 f-size-9" rowspan="2" valign="top">JEV No.</td>
	</tr>
	<tr>
		<td colspan="1" class="td-border-left td-border-bottom pad-2 f-size-9" valign="top">Check/ <br> MOA No.</td>
		<td colspan="1" class="td-border-left td-border-bottom pad-2 f-size-9" valign="top"></td>
		<td colspan="2" class="td-border-left td-border-bottom pad-2 f-size-9" valign="top">Date</td>
		<td colspan="4" class="td-border-left td-border-bottom pad-2 f-size-9" valign="top">Bank Name</td>
	</tr>
	<tr>
		<td colspan="1" class="td-border-left td-border-bottom pad-2 f-size-9" valign="top"></td>
		<td colspan="1" class="td-border-left td-border-bottom pad-2 f-size-9" valign="top"></td>
		<td colspan="2" class="td-border-left td-border-bottom pad-2 f-size-9" valign="top">Date</td>
		<td colspan="4" class="td-border-left td-border-bottom pad-2 f-size-9" valign="top">Printed Name</td>
		<td width="100" class="td-border-left td-border-bottom td-border-right pad-2 f-size-9" rowspan="2" valign="top">Date</td>
	</tr>
	<tr>
		<td colspan="8" class="td-border-3 pad-2 f-size-9">Official Receipt No. & Date/Other Documents</td>
	</tr>
</table>
<!-- ************************************************************************** -->
<div style="padding-top: 20px;" class="f-size-10"><b><?php echo 'ATM No. ' . $voucher_info['identification_value']?></b></div>
</body>

</html>