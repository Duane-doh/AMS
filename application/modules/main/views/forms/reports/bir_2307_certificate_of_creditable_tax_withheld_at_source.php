<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<head>
 <title>Certificate of Creditable Tax Withheld At Source</title>
 <link rel="stylesheet" href="<?php echo base_url().PATH_CSS ?>reports.css" type="text/css" />
</head>

<body>
<table width="100%">
	<tr class="td-border-thick-top td-border-thick-left td-border-thick-right">
		<td width="230" style="padding: 0px !important;">
			<table>
				<tr>
					<td width="50" align="center"><img src="<?php echo base_url().PATH_IMG ?>bir_logo.png" width=35 height=35></img></td>
					<td><span class="f-size-11">Republika ng Pilipinas<br>Kagawaran ng Pananalapi</span><span class="f-size-11"><br>Kawanihan ng Rentas Internas</span></td>
				</tr>
			</table>
		</td>
		<td class="f-size-20" width="270" align="center" valign="middle">Certificate of Creditable Tax<br>Withheld At Source</td>
		<td width="40"></td>
		<td class="f-size-11">BIR Form No.<br><span class="f-size-30"><b>2307</b></span></td>			
	</tr>
	<tr class="td-border-thick-bottom td-border-thick-left td-border-thick-right">
		<td colspan=3 class="f-size-11" valign=top><?php echo nbs(5)?>&nbsp;</td>
		<td class="f-size-11" valign=top>September 2005 (ENCS)</td>
	</tr>
</table>
<table width="100%" style="background-color: #BFBFBF">
	<tr class="td-border-thick-bottom td-border-thick-left td-border-thick-right">
		<td><b>1</b>&nbsp;For the Period<br><?php echo nbs(3)?>From<?php echo nbs(20)?>&#9656;</td>
		<td>
			<table>
				<tr>
					<td class="bold td-border-light-left td-border-light-top td-border-light-bottom td-center-middle bg-white" width="15" height="17"><?php echo $period_from[0];?></td>
					<td class="bold td-border-light-left td-border-light-top td-border-light-bottom td-center-middle bg-white" width="15"><?php echo $period_from[1];?></td>
					<td class="bold td-border-light-left td-border-light-top td-border-light-bottom td-center-middle bg-white" width="15"><?php echo $period_from[2];?></td>
					<td class="bold td-border-light-left td-border-light-top td-border-light-bottom td-center-middle bg-white" width="15"><?php echo $period_from[3];?></td>
					<td class="bold td-border-light-left td-border-light-top td-border-light-bottom td-center-middle bg-white" width="15"><?php echo $period_from[4];?></td>
					<td class="bold border-light td-center-middle bg-white" width="17"><?php echo $period_from[5];?></td>
				</tr>
			</table>
		</td>
		<td>(MM/DD/YY)</td>
		<td width="90" align="center" valign=bottom>To<?php echo nbs(15)?>&#9656;</td>
		<td>
			<table>
				<tr>
					<td class="bold td-border-light-left td-border-light-top td-border-light-bottom td-center-middle bg-white" width="15" height="17"><?php echo $period_to[0];?></td>
					<td class="bold td-border-light-left td-border-light-top td-border-light-bottom td-center-middle bg-white" width="15"><?php echo $period_to[1];?></td>
					<td class="bold td-border-light-left td-border-light-top td-border-light-bottom td-center-middle bg-white" width="15"><?php echo $period_to[2];?></td>
					<td class="bold td-border-light-left td-border-light-top td-border-light-bottom td-center-middle bg-white" width="15"><?php echo $period_to[3];?></td>
					<td class="bold td-border-light-left td-border-light-top td-border-light-bottom td-center-middle bg-white" width="15"><?php echo $period_to[4];?></td>
					<td class="bold border-light td-center-middle bg-white" width="17"><?php echo $period_to[5];?></td>
				</tr>
			</table>
		</td>
		<td>(MM/DD/YY)</td>
		<td width="125" align="center" valign=bottom>&nbsp;<?php echo nbs(5)?></td>
	</tr>
</table>
<table width="100%" style="background-color: #BFBFBF">
	<tr>
		<td class="td-border-thick-bottom td-border-thick-left" width="43%"><b>Part I</b></td>
		<td class="td-border-thick-bottom td-border-thick-right">&nbsp;<b>Payee Information</b></td>
	</tr>
</table>
<table width="100%" style="background-color: #BFBFBF">
	<tr class="td-border-thick-left td-border-thick-right">
		<td width="25%"><b>2</b>&nbsp;Taxpayer<br><?php echo nbs(3)?>Identification Number<?php echo nbs(15)?>&#9656;</td>
		<td colspan="3">
			<table>
				<tr>
					<td class="bold border-light td-center-middle bg-white" width="15" height="17"><?php echo $payer_info['tin'][0];?></td>
					<td class="bold border-light td-center-middle bg-white" width="15"><?php echo $payer_info['tin'][1];?></td>
					<td class="bold border-light td-center-middle bg-white" width="15"><?php echo $payer_info['tin'][2];?></td>
					<td class="bold border-light td-center-middle bg-gray" width="15"></td>
					<td class="bold border-light td-center-middle bg-white" width="15"><?php echo $payer_info['tin'][3];?></td>
					<td class="bold border-light td-center-middle bg-white" width="15"><?php echo $payer_info['tin'][4];?></td>
					<td class="bold border-light td-center-middle bg-white" width="15"><?php echo $payer_info['tin'][5];?></td>
					<td class="bold border-light td-center-middle bg-gray" width="15"></td>
					<td class="bold border-light td-center-middle bg-white" width="15"><?php echo $payer_info['tin'][6];?></td>
					<td class="bold border-light td-center-middle bg-white" width="15"><?php echo $payer_info['tin'][7];?></td>
					<td class="bold border-light td-center-middle bg-white" width="15"><?php echo $payer_info['tin'][8];?></td>
					<td class="bold border-light td-center-middle bg-gray" width="15"></td>
					<td class="bold border-light td-center-middle bg-white" width="15"><?php echo $payer_info['tin'][9];?></td>
					<td class="bold border-light td-center-middle bg-white" width="15"><?php echo $payer_info['tin'][10];?></td>
					<td class="bold border-light td-center-middle bg-white" width="15"><?php echo $payer_info['tin'][11];?></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr class="td-border-thick-left td-border-thick-right">
		<td width="25%"><b>3</b>&nbsp;Payees Name<br><?php echo nbs(54)?>&#9656;</td>
		<td colspan="3">
			<table>
				<tr>
					<td class="bold border-light bg-white" width="520" height="17"><?php echo $payer_info['last_name'] . ', ' . $payer_info['first_name'] . ' ' . $payer_info['middle_name'] . '.';?></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr class="td-border-thick-left td-border-thick-right">
		<td width="25%">&nbsp;</td>
		<td colspan="3">(Last Name, First Name, Middle Name for Individuals) (Registered Name for Non-Individuals)</td>
	</tr>
	<tr class="td-border-thick-left td-border-thick-right">
		<td width="25%"><b>4</b>&nbsp;Registered Address<br><?php echo nbs(54)?>&#9656;</td>
		<td>
			<table>
				<tr>
					<td class="bold border-light bg-white" width="350" height="17"><?php echo $payer_info['local_address'];?></td>
				</tr>
			</table>
		</td>
		<td><b>4A</b>&nbsp;Zip Code<br><?php echo nbs(30)?>&#9656;</td>
		<td>
			<table>
				<tr>
					<td class="bold td-border-light-left td-border-light-top td-border-light-bottom td-center-middle bg-white" width="15" height="17"><?php echo $payer_info['loc_zip'][0];?></td>
					<td class="bold td-border-light-left td-border-light-top td-border-light-bottom td-center-middle bg-white" width="15"><?php echo $payer_info['loc_zip'][1];?></td>
					<td class="bold td-border-light-left td-border-light-top td-border-light-bottom td-center-middle bg-white" width="15"><?php echo $payer_info['loc_zip'][2];?></td>
					<td class="bold border-light td-center-middle bg-white" width="15"><?php echo $payer_info['loc_zip'][3];?></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr class="td-border-thick-left td-border-thick-right">
		<td width="25%"><b>5</b>&nbsp;Foreign Address<br><?php echo nbs(54)?>&#9656;</td>
		<td>
			<table>
				<tr>
					<td class="bold border-light bg-white" width="350" height="17"><?php echo $payer_info['foreign_address'];?></td>
				</tr>
			</table>
		</td>
		<td><b>5A</b>&nbsp;Zip Code<br><?php echo nbs(30)?>&#9656;</td>
		<td>
			<table>
				<tr>
					<td class="bold td-border-light-left td-border-light-top td-border-light-bottom td-center-middle bg-white" width="15" height="17"><?php echo $payer_info['for_zip'][0];?></td>
					<td class="bold td-border-light-left td-border-light-top td-border-light-bottom td-center-middle bg-white" width="15"><?php echo $payer_info['for_zip'][1];?></td>
					<td class="bold td-border-light-left td-border-light-top td-border-light-bottom td-center-middle bg-white" width="15"><?php echo $payer_info['for_zip'][2];?></td>
					<td class="bold border-light td-center-middle bg-white" width="15"><?php echo $payer_info['for_zip'][3];?></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr class="border-thick">
		<td class="align-c" colspan="4"><b>Payor Information</b></td>
	</tr>
	<tr class="td-border-thick-left td-border-thick-right">
		<td width="25%"><b>6</b>&nbsp;Taxpayer<br><?php echo nbs(3)?>Identification Number<?php echo nbs(15)?>&#9656;</td>
		<td colspan="3">
			<table>
				<tr>
					<td class="bold td-border-light-left td-border-light-top td-border-light-bottom td-center-middle bg-white" width="15" height="17"><?php echo $doh_tin[0];?></td>
					<td class="bold td-border-light-left td-border-light-top td-border-light-bottom td-center-middle bg-white" width="15"><?php echo $doh_tin[1];?></td>
					<td class="bold td-border-light-left td-border-light-top td-border-light-bottom td-center-middle bg-white" width="15"><?php echo $doh_tin[2];?></td>
					<td class="bold td-border-light-left td-border-light-top td-border-light-bottom" width="15"></td>
					<td class="bold td-border-light-left td-border-light-top td-border-light-bottom td-center-middle bg-white" width="15"><?php echo $doh_tin[3];?></td>
					<td class="bold td-border-light-left td-border-light-top td-border-light-bottom td-center-middle bg-white" width="15"><?php echo $doh_tin[4];?></td>
					<td class="bold td-border-light-left td-border-light-top td-border-light-bottom td-center-middle bg-white" width="15"><?php echo $doh_tin[5];?></td>
					<td class="bold td-border-light-left td-border-light-top td-border-light-bottom" width="15"></td>
					<td class="bold td-border-light-left td-border-light-top td-border-light-bottom td-center-middle bg-white" width="15"><?php echo $doh_tin[6];?></td>
					<td class="bold td-border-light-left td-border-light-top td-border-light-bottom td-center-middle bg-white" width="15"><?php echo $doh_tin[7];?></td>
					<td class="bold td-border-light-left td-border-light-top td-border-light-bottom td-center-middle bg-white" width="15"><?php echo $doh_tin[8];?></td>
					<td class="bold td-border-light-left td-border-light-top td-border-light-bottom" width="15"></td>
					<td class="bold td-border-light-left td-border-light-top td-border-light-bottom td-center-middle bg-white" width="15"><?php echo $doh_tin[9];?></td>
					<td class="bold td-border-light-left td-border-light-top td-border-light-bottom td-center-middle bg-white" width="15"><?php echo $doh_tin[10];?></td>
					<td class="bold td-border-light-left td-border-light-right td-border-light-top td-border-light-bottom td-center-middle bg-white" width="15"><?php echo $doh_tin[11];?></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr class="td-border-thick-left td-border-thick-right">
		<td width="25%"><b>7</b>&nbsp;Payor's Name<br><?php echo nbs(54)?>&#9656;</td>
		<td colspan="3">
			<table>
				<tr>
					<td class="bold border-light bg-white" width="520" height="17">DEPARTMENT OF HEALTH</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr class="td-border-thick-left td-border-thick-right">
		<td width="25%">&nbsp;</td>
		<td colspan="3">(Last Name, First Name, Middle Name for Individuals) (Registered Name for Non-Individuals)</td>
	</tr>
	<tr class="td-border-thick-left td-border-thick-right">
		<td width="25%"><b>8</b>&nbsp;Registered Address<br><?php echo nbs(54)?>&#9656;</td>
		<td>
			<table>
				<tr>
					<td class="bold border-light bg-white" width="350" height="17"><?php echo $doh_add;?></td>
				</tr>
			</table>
		</td>
		<td><b>8A</b>&nbsp;Zip Code<br><?php echo nbs(30)?>&#9656;</td>
		<td>
			<table>
				<tr>
					<td class="bold border-light td-center-middle bg-white" width="15" height="17"><?php echo $doh_zip_code[0];?></td>
					<td class="bold border-light td-center-middle bg-white" width="15"><?php echo $doh_zip_code[1];?></td>
					<td class="bold border-light td-center-middle bg-white" width="15"><?php echo $doh_zip_code[2];?></td>
					<td class="bold border-light td-center-middle bg-white" width="15"><?php echo $doh_zip_code[3];?></td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<table width="100%" style="background-color: #BFBFBF">
	<tr class="border-thick">
		<td width="30%"><b>Part II</b></td>
		<td>&nbsp;<b>Details of Monthly Income Payments and Tax Withheld for the Quarter</b></td>
	</tr>
</table>
<table width="100%">
	<tr style="background-color: #BFBFBF">
		<td class="td-border-thick-left td-border-bottom bold align-c" rowspan="2" width="25%">Income Payments Subject to Expanded Withholding Tax</td>
		<td class="td-border-left td-border-bottom bold align-c" rowspan="2" width="10%">ATC</td>
		<td class="td-border-left td-border-bottom bold align-c" colspan="4">AMOUNT OF INCOME PAYMENTS</td>
		<td class="td-border-left td-border-bottom td-border-thick-right bold align-c" rowspan="2">Tax Withheld <br>For the Quarter</td>
	</tr>
	<tr style="background-color: #BFBFBF">
		<td class="td-border-thick-left td-border-4 bold align-c" width="100">1st Month of the Quarter</td>
		<td class="td-border-4 bold align-c" width="100">2nd Month of the Quarter</td>
		<td class="td-border-4 bold align-c" width="100">3rd Month of the Quarter</td>
		<td class="td-border-thick-right td-border-4 bold align-c" width="100">Total</td>
	</tr>
	<?php 
				$total = count($details);
				$diff	= 10 - $total;
				// TOTAL FOR EVERY FIELD
				$payment_1 = 0;
				$payment_2 = 0;
				$payment_3 = 0;
				$payment_total = 0;
				if(!EMPTY($details)){ 

				foreach($details as $detail): 
					$payment_1		= $payment_1 + $detail['pay_1'];
					$payment_2 		= $payment_2 + $detail['pay_2'];
					$payment_3 		= $payment_3 + $detail['pay_3'];
					$payment_total	= $payment_total + $detail['payment_total'];
					$tax_withheld	= $tax_withheld + $detail['tax_withheld'];

			?>
			<tr>
				<td class="bold td-border-thick-left td-border-bottom td-border-right align-c"><?php echo $detail['ewt'];?></td>
				<td class="bold td-border-4 align-c"><?php echo $detail['atc'];?></td>
				<td class="bold td-border-4 align-r"><?php echo number_format($detail['pay_1'], 2);?></td>
				<td class="bold td-border-4 align-r"><?php echo number_format($detail['pay_2'], 2);?></td>
				<td class="bold td-border-4 align-r"><?php echo number_format($detail['pay_3'], 2);?></td>
				<td class="bold td-border-4 align-r"><?php echo number_format($detail['payment_total'], 2);?></td>
				<td class="bold td-border-thick-right td-border-bottom align-r"><?php echo number_format($detail['tax_withheld'], 2);?></td>
			</tr>
			<?php 
				endforeach; 
			}
				for($i = 0; $i < $diff; $i++)
				{
			?>
			<tr>
				<td class="td-border-thick-left td-border-bottom td-border-right align-c">&nbsp;</td>
				<td class="td-border-4 align-c">&nbsp;</td>
				<td class="td-border-4 align-r">&nbsp;</td>
				<td class="td-border-4 align-r">&nbsp;</td>
				<td class="td-border-4 align-r">&nbsp;</td>
				<td class="td-border-4 align-r">&nbsp;</td>
				<td class="td-border-thick-right td-border-bottom align-r">&nbsp;</td>
			</tr>
			<?php } ?>

<!-- 	<?php 
	if(count($details)>0) :
	// TOTAL FOR EVERY FIELD
	$payment_1 = 0;
	$payment_2 = 0;
	$payment_3 = 0;
	$payment_total = 0;
	
		foreach ($details as $detail):
		$payment_1		= $payment_1 + $detail['pay_1'];
		$payment_2 		= $payment_2 + $detail['pay_2'];
		$payment_3 		= $payment_3 + $detail['pay_3'];
		$payment_total	= $payment_total + $detail['payment_total'];
		//$tax_withheld	= $tax_withheld + $detail['tax_withheld'];
	?>
	<tr>
		<td class="td-border-thick-left td-border-bottom td-border-right align-c"><?php echo $detail['ewt'];?></td>
		<td class="td-border-4 align-c"><?php echo $detail['atc'];?></td>
		<td class="td-border-4 align-r"><?php echo number_format($detail['pay_1'], 2);?></td>
		<td class="td-border-4 align-r"><?php echo number_format($detail['pay_2'], 2);?></td>
		<td class="td-border-4 align-r"><?php echo number_format($detail['pay_3'], 2);?></td>
		<td class="td-border-4 align-r"><?php echo number_format($detail['payment_total'], 2);?></td>
		<td class="td-border-thick-right td-border-bottom align-r"><?php echo number_format($detail['tax_withheld'], 2);?></td>
	</tr>
	<?php 
	endforeach;
	else:
	?>
	<tr>
		<td class="td-border-thick-left td-border-bottom td-border-right align-c"><?php echo $detail['ewt'];?></td>
		<td class="td-border-4 align-c"><?php echo $detail['atc'];?></td>
		<td class="td-border-4 align-r"><?php echo "0.00";?></td>
		<td class="td-border-4 align-r"><?php echo "0.00";?></td>
		<td class="td-border-4 align-r"><?php echo "0.00";?></td>
		<td class="td-border-4 align-r"><?php echo "0.00";?></td>
		<td class="td-border-thick-right td-border-bottom align-r"><?php echo number_format($detail['tax_withheld'], 2);?></td>
	</tr>
	<?php endif?> -->
	<tr>
		<td class="td-border-thick-left td-border-bottom td-border-right bold" style="background-color: #BFBFBF">Total</td>
		<td class="bold td-border-4 align-c">&nbsp;</td>
		<td class="bold td-border-4 align-r"><?php echo number_format($payment_1, 2);?></td>
		<td class="bold td-border-4 align-r"><?php echo number_format($payment_2, 2);?></td>
		<td class="bold td-border-4 align-r"><?php echo number_format($payment_3, 2);?></td>
		<td class="bold td-border-4 align-r"><?php echo number_format($payment_total, 2);?></td>
		<td class="bold td-border-thick-right td-border-bottom align-r"><?php echo number_format($tax_withheld, 2);?></td>
	</tr>
	<tr style="background-color: #BFBFBF">
		<td class="td-border-thick-left td-border-bottom td-border-right align-c bold">Money Payments Subject to Withholding of Business Tax (Government & Private)</td>
		<td class="td-border-4 align-c">&nbsp;</td>
		<td class="td-border-4 align-c">&nbsp;</td>
		<td class="td-border-4 align-c">&nbsp;</td>
		<td class="td-border-4 align-c">&nbsp;</td>
		<td class="td-border-4 align-c">&nbsp;</td>
		<td class="td-border-thick-right td-border-bottom align-c">&nbsp;</td>
	</tr>
	<?php 
	$count = 10;
	for($i = 0; $i < $count; $i++) { ?>
		<tr>
			<td class="td-border-thick-left td-border-bottom td-border-right align-c">&nbsp;</td>
			<td class="td-border-4 align-c">&nbsp;</td>
			<td class="td-border-4 align-r">&nbsp;</td>
			<td class="td-border-4 align-r">&nbsp;</td>
			<td class="td-border-4 align-r">&nbsp;</td>
			<td class="td-border-4 align-r">&nbsp;</td>
			<td class="td-border-thick-right td-border-bottom align-r">&nbsp;</td>
		</tr>
	<?php } ?>
	
	<tr>
		<td class="td-border-thick-left td-border-bottom td-border-right bold" style="background-color: #BFBFBF">Total</td>
		<td class="td-border-4 align-c">&nbsp;</td>
		<td class="td-border-4 align-c">&nbsp;</td>
		<td class="td-border-4 align-c">&nbsp;</td>
		<td class="td-border-4 align-c">&nbsp;</td>
		<td class="td-border-4 align-c">&nbsp;</td>
		<td class="td-border-thick-right td-border-bottom align-c">&nbsp;</td>
	</tr>
</table>
<table>
	<tr class="td-border-thick-top td-border-thick-left td-border-thick-right">
		<td colspan="7"><p align="justify"><?php echo nbs(8)?>We declare, under the penalties of perjury, that this certificate has been made in good faith, verified by me, and to the best of my knowledge and belief, is true and correct,pursuant to the provisions of the National Internal Revenue Code, as amended, and the regulations issued under authority thereof</p></td>
	</tr><!-- 
	<tr class="td-border-thick-left td-border-thick-right">
		<td colspan="7">&nbsp;</td>
	</tr> -->
	<tr class="td-border-thick-left td-border-thick-right">
		<td height="15">&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td class="td-border-thick-left" width="15">&nbsp;</td>
		<td class="td-border-top align-c"><span style="text-decoration: overline">Payor/Payor's Authorized Representative/Accredited Tax Agent</span></td>
		<td width="15">&nbsp;</td>
		<td class="td-border-top align-c">TIN of Signatory</td>
		<td width="15">&nbsp;</td>
		<td class="td-border-top align-c">Title/Position of Signatory</td>
		<td class="td-border-thick-right" width="15">&nbsp;</td>
	</tr>
	<tr class="td-border-thick-left td-border-thick-right">
		<td>&nbsp;</td>
		<td class="align-c">(Signature Over Printed Name)</td>
		<td colspan="5">&nbsp;</td>
	</tr>	
	<tr class="td-border-thick-left td-border-thick-right">
		<td height="15">&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td class="td-border-thick-left td-border-bottom height-20" width="15">&nbsp;</td>
		<td class="td-border-top  td-border-bottom align-c"><span style="text-decoration: overline">Tax Agent Accreditation No./Attorney's Roll No. (if applicable)</span></td>
		<td class="td-border-bottom" width="15">&nbsp;</td>
		<td class="td-border-top  td-border-bottom align-c">Date of Issuance</td>
		<td class="td-border-bottom" width="15">&nbsp;</td>
		<td class="td-border-top td-border-bottom align-c">Date of Expiry</td>
		<td class="td-border-thick-right td-border-bottom" width="15">&nbsp;</td>
	</tr>
</table>
<table class="table-max">
	<tr class="td-border-thick-left td-border-thick-right">
		<td colspan="9">Conforme:</td>
	</tr>
	<tr class="td-border-thick-left td-border-thick-right">
		<td height="15">&nbsp;</td>
		<td class="bold align-c"><?php echo $payer_info['first_name'] . ' ' . $payer_info['middle_name'] . '. ' . $payer_info['last_name'];?></td>
		<td>&nbsp;</td>
		<td class="bold align-c"><?php echo format_identifications($payer_info['tin'], $tin_format['format']) ?></td>
		<td>&nbsp;</td>
		<td class="bold align-c"><?php echo $payer_info['employ_position_name']?></td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td class="td-border-thick-left" width="15">&nbsp;</td>
		<td class="td-border-top align-c"><span style="text-decoration: overline">Payee/Payee's Authorized Representative/Accredited Tax Agent</span></td>
		<td width="15">&nbsp;</td>
		<td class="td-border-top align-c">TIN of Signatory</td>
		<td width="15">&nbsp;</td>
		<td class="td-border-top align-c">Title/Position of Signatory</td>
		<td width="15">&nbsp;</td>
		<td class="td-border-top align-c">Date Signed</td>
		<td class="td-border-thick-right" width="15">&nbsp;</td>
	</tr>
	<tr class="td-border-thick-left td-border-thick-right">
		<td>&nbsp;</td>
		<td class="align-c">(Signature Over Printed Name)</td>
		<td colspan="7">&nbsp;</td>
	</tr>
</table>
<table class="table-max">
	<tr class="td-border-thick-left td-border-thick-right">
		<td height="15">&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td class="td-border-thick-left td-border-thick-bottom height-20" width="15">&nbsp;</td>
		<td class="td-border-top  td-border-thick-bottom align-c"><span style="text-decoration: overline">Tax Agent Accreditation No./Attorney's Roll No. (if applicable)</span></td>
		<td class="td-border-thick-bottom" width="15">&nbsp;</td>
		<td class="td-border-top  td-border-thick-bottom align-c">Date of Issuance</td>
		<td class="td-border-thick-bottom" width="15">&nbsp;</td>
		<td class="td-border-top td-border-thick-bottom align-c">Date of Expiry</td>
		<td class="td-border-thick-right td-border-thick-bottom" width="15">&nbsp;</td>
	</tr>
</table>
<pagebreak />
<table class="table-max f-size-7-5">
	<tr class="border-thick">
		<td class="td-border-thick-left bold align-c" colspan="4">SCHEDULES OF ALPHANUMERIC TAX CODES</td>
	</tr>
	<tr>
		<td class="td-border-bottom td-border-thick-left td-border-right bg-dark-gray bold" width="550" colspan="2" rowspan="2">A Income Payments subject to Expanded Withholding Tax</td>
		<td class="td-border-thick-bottom td-border-thick-right bold align-c" colspan="2">ATC</td>
	</tr>
	<tr>
		<td class="td-border-4 bold align-c" width="65">IND</td>
		<td class="td-border-bottom td-border-thick-right bold align-c" width="65">CORP</td>
	</tr>
	<tr>
		<td class="td-border-thick-left td-border-right" colspan="2"><b>1</b><?php echo nbs(5);?>Professional/talent fees paid to juridical persons/individuals (lawyers, CPAs, etc.)</td>
		<td class="td-border-3 align-c" rowspan="2">WI 010</td>
		<td class="td-border-3 td-border-thick-right align-c" rowspan="2">WC 010</td>
	</tr>
	<tr>
		<td class="td-border-thick-left">&nbsp;</td>
		<td>-if current year's gross income does not exceed P720,000.00</td>
	</tr>
	<tr>
		<td class="td-border-thick-left">&nbsp;</td>
		<td class="td-border-top">-if current year's gross income exceed P720,000.00</td>
		<td class="td-border-3 align-c">WI 011</td>
		<td class="td-border-3 td-border-thick-right align-c">WC 011</td>
	</tr>
	<tr>
		<td class="td-border-thick-left td-border-top td-border-right" colspan="2"><b>2</b><?php echo nbs(5);?>Professional entertainers-</td>
		<td class="td-border-3 align-c" rowspan="2">WI 020</td>
		<td class="td-border-3 td-border-thick-right bg-dark-gray" rowspan="2">&nbsp;</td>
	</tr>
	<tr>
		<td class="td-border-thick-left">&nbsp;</td>
		<td>-if current year's gross income does not exceed P720,000.00</td>
	</tr>
	<tr>
		<td class="td-border-thick-left">&nbsp;</td>
		<td class="td-border-top">-if current year's gross income exceed P720,000.00</td>
		<td class="td-border-3 align-c">WI 021</td>
		<td class="td-border-3 td-border-thick-right bg-dark-gray">&nbsp;</td>
	</tr>
	<tr>
		<td class="td-border-thick-left td-border-top td-border-right" colspan="2"><b>3</b><?php echo nbs(5);?>Professional athletes-</td>
		<td class="td-border-3 align-c" rowspan="2">WI 030</td>
		<td class="td-border-3 td-border-thick-right bg-dark-gray" rowspan="2">&nbsp;</td>
	</tr>
	<tr>
		<td class="td-border-thick-left">&nbsp;</td>
		<td>-if current year's gross income does not exceed P720,000.00</td>
	</tr>
	<tr>
		<td class="td-border-thick-left">&nbsp;</td>
		<td class="td-border-top">-if current year's gross income exceed P720,000.00</td>
		<td class="td-border-3 align-c">WI 031</td>
		<td class="td-border-3 td-border-thick-right bg-dark-gray">&nbsp;</td>
	</tr>
	<tr>
		<td class="td-border-thick-left td-border-top td-border-right" colspan="2"><b>4</b><?php echo nbs(5);?>Movie, stage, radio, television and musical directors-</td>
		<td class="td-border-3 align-c" rowspan="2">WI 040</td>
		<td class="td-border-3 td-border-thick-right bg-dark-gray" rowspan="2">&nbsp;</td>
	</tr>
	<tr>
		<td class="td-border-thick-left">&nbsp;</td>
		<td>-if current year's gross income does not exceed P720,000.00</td>
	</tr>
	<tr>
		<td class="td-border-thick-left">&nbsp;</td>
		<td class="td-border-top">-if current year's gross income exceed P720,000.00</td>
		<td class="td-border-3 align-c">WI 041</td>
		<td class="td-border-3 td-border-thick-right bg-dark-gray">&nbsp;</td>
	</tr>
	<tr>
		<td class="td-border-thick-left td-border-top td-border-right" colspan="2"><b>5</b><?php echo nbs(5);?>Management & technical consultants</td>
		<td class="td-border-3 align-c" rowspan="2">WI 050</td>
		<td class="td-border-3 td-border-thick-right bg-dark-gray" rowspan="2">&nbsp;</td>
	</tr>
	<tr>
		<td class="td-border-thick-left">&nbsp;</td>
		<td>-if current year's gross income does not exceed P720,000.00</td>
	</tr>
	<tr>
		<td class="td-border-thick-left">&nbsp;</td>
		<td class="td-border-top">-if current year's gross income exceed P720,000.00</td>
		<td class="td-border-3 align-c">WI 051</td>
		<td class="td-border-3 td-border-thick-right bg-dark-gray">&nbsp;</td>
	</tr>
	<tr>
		<td class="td-border-thick-left td-border-top td-border-right" colspan="2"><b>6</b><?php echo nbs(5);?>Bookkeeping agents and agencies</td>
		<td class="td-border-3 align-c" rowspan="2">WI 060</td>
		<td class="td-border-3 td-border-thick-right bg-dark-gray" rowspan="2">&nbsp;</td>
	</tr>
	<tr>
		<td class="td-border-thick-left">&nbsp;</td>
		<td>-if current year's gross income does not exceed P720,000.00</td>
	</tr>
	<tr>
		<td class="td-border-thick-left">&nbsp;</td>
		<td class="td-border-top">-if current year's gross income exceed P720,000.00</td>
		<td class="td-border-3 align-c">WI 061</td>
		<td class="td-border-3 td-border-thick-right bg-dark-gray">&nbsp;</td>
	</tr>
	<tr>
		<td class="td-border-thick-left td-border-top td-border-right" colspan="2"><b>7</b><?php echo nbs(5);?>Insurance agents & insurance adjusters</td>
		<td class="td-border-3 align-c" rowspan="2">WI 070</td>
		<td class="td-border-3 td-border-thick-right bg-dark-gray" rowspan="2">&nbsp;</td>
	</tr>
	<tr>
		<td class="td-border-thick-left">&nbsp;</td>
		<td>-if current year's gross income does not exceed P720,000.00</td>
	</tr>
	<tr>
		<td class="td-border-thick-left">&nbsp;</td>
		<td class="td-border-top">-if current year's gross income exceed P720,000.00</td>
		<td class="td-border-3 align-c">WI 071</td>
		<td class="td-border-3 td-border-thick-right bg-dark-gray">&nbsp;</td>
	</tr>
	<tr>
		<td class="td-border-thick-left td-border-top td-border-right" colspan="2"><b>8</b><?php echo nbs(5);?>Other recipient of talents fees-</td>
		<td class="td-border-3 align-c" rowspan="2">WI 080</td>
		<td class="td-border-3 td-border-thick-right bg-dark-gray" rowspan="2">&nbsp;</td>
	</tr>
	<tr>
		<td class="td-border-thick-left">&nbsp;</td>
		<td>-if current year's gross income does not exceed P720,000.00</td>
	</tr>
	<tr>
		<td class="td-border-thick-left">&nbsp;</td>
		<td class="td-border-top">-if current year's gross income exceed P720,000.00</td>
		<td class="td-border-3 align-c">WI 081</td>
		<td class="td-border-3 td-border-thick-right bg-dark-gray">&nbsp;</td>
	</tr>
	<tr>
		<td class="td-border-thick-left td-border-top td-border-right" colspan="2"><b>9</b><?php echo nbs(5);?>Fees of directors who are not employee of the company</td>
		<td class="td-border-3 align-c" rowspan="2">WI 090</td>
		<td class="td-border-3 td-border-thick-right bg-dark-gray" rowspan="2">&nbsp;</td>
	</tr>
	<tr>
		<td class="td-border-thick-left">&nbsp;</td>
		<td>-if current year's gross income does not exceed P720,000.00</td>
	</tr>
	<tr>
		<td class="td-border-thick-left">&nbsp;</td>
		<td class="td-border-top">-if current year's gross income exceed P720,000.00</td>
		<td class="td-border-3 align-c">WI 091</td>
		<td class="td-border-3 td-border-thick-right bg-dark-gray">&nbsp;</td>
	</tr>
	<tr>
		<td class="td-border-thick-left td-border-top td-border-right" colspan="2"><b>10</b><?php echo nbs(4);?>Fees of directors who are not employee of the company</td>
		<td class="td-border-3 align-c">WI 100</td>
		<td class="td-border-3 td-border-thick-right align-c">WC 100</td>
	</tr>
	<tr>
		<td class="td-border-thick-left td-border-top td-border-right" colspan="2"><b>11</b><?php echo nbs(4);?>Cinematographic film rentals</td>
		<td class="td-border-3 align-c">WI 110</td>
		<td class="td-border-3 td-border-thick-right align-c">WC 110</td>
	</tr>
	<tr>
		<td class="td-border-thick-left td-border-top td-border-right" colspan="2"><b>12</b><?php echo nbs(4);?>Prime contractors/Sub-contractors</td>
		<td class="td-border-3 align-c">WI 120</td>
		<td class="td-border-3 td-border-thick-right align-c">WC 120</td>
	</tr>
	<tr>
		<td class="td-border-thick-left td-border-top td-border-right" colspan="2"><b>13</b><?php echo nbs(4);?>Income distribution to beneficiaries of estates & trusts</td>
		<td class="td-border-3 align-c">WI 130</td>
		<td class="td-border-3 td-border-thick-right bg-dark-gray align-c">&nbsp;</td>
	</tr>
	<tr>
		<td class="td-border-thick-left td-border-top td-border-right" colspan="2"><b>14</b><?php echo nbs(4);?>Gross commissions or service fees of customs, insurance, stock, real estate, immigration & commercial brokers & fees <br><?php echo nbs(10);?>of agents of professional entertainers</td>
		<td class="td-border-3 align-c">WI 140</td>
		<td class="td-border-3 td-border-thick-right align-c">WC 140</td>
	</tr>
	<tr>
		<td class="td-border-thick-left td-border-top td-border-right" colspan="2"><b>15</b><?php echo nbs(4);?>Fees of directors who are not employee of the company</td>
		<td class="td-border-3 align-c">WI 141</td>
		<td class="td-border-3 td-border-thick-right align-c">&nbsp;</td>
	</tr>
	<tr>
		<td class="td-border-thick-left td-border-top td-border-right" colspan="2"><b>16</b><?php echo nbs(4);?>Payments for medical/dental veterinary services thru hospitals/clinics/health maintenance organizations including <br><?php echo nbs(10);?>direct payments to service provider</td>
		<td class="td-border-3 align-c" rowspan="2">WI 151</td>
		<td class="td-border-3 td-border-thick-right bg-dark-gray" rowspan="2">&nbsp;</td>
	</tr>
	<tr>
		<td class="td-border-thick-left">&nbsp;</td>
		<td>-if current year's gross income does not exceed P720,000.00</td>
	</tr>
	<tr>
		<td class="td-border-thick-left">&nbsp;</td>
		<td class="td-border-top">-if current year's gross income exceed P720,000.00</td>
		<td class="td-border-3 align-c">WI 150</td>
		<td class="td-border-3 td-border-thick-right bg-dark-gray">&nbsp;</td>
	</tr>
	<tr>
		<td class="td-border-thick-left td-border-top td-border-right" colspan="2" width="550"><b>17</b><?php echo nbs(4);?>Payment to partners in general professional partnership</td>
		<td class="td-border-3 align-c" rowspan="2">WI 152</td>
		<td class="td-border-3 td-border-thick-right bg-dark-gray" rowspan="2">&nbsp;</td>
	</tr>
	<tr>
		<td class="td-border-thick-left" width="80">&nbsp;</td>
		<td width="470">-if current year's gross income does not exceed P720,000.00</td>
	</tr>
	<tr>
		<td class="td-border-thick-left">&nbsp;</td>
		<td class="td-border-top">-if current year's gross income exceed P720,000.00</td>
		<td class="td-border-3 align-c">WI 150</td>
		<td class="td-border-3 td-border-thick-right bg-dark-gray">&nbsp;</td>
	</tr>
	<tr>
		<td class="td-border-thick-left td-border-top td-border-right" colspan="2"><b>18</b><?php echo nbs(4);?>Income payments made by credit card companies to any business entity</td>
		<td class="td-border-3 align-c">WI 156</td>
		<td class="td-border-3 td-border-thick-right align-c">WC 156</td>
	</tr>
	<tr>
		<td class="td-border-thick-left td-border-top td-border-right" colspan="2"><b>19</b><?php echo nbs(4);?>Income payments made by the government to its local/resident suppliers of goods</td>
		<td class="td-border-3 align-c">WI 640</td>
		<td class="td-border-3 td-border-thick-right align-c">WC 640</td>
	</tr>
	<tr>
		<td class="td-border-thick-left td-border-top td-border-right" colspan="2"><b>20</b><?php echo nbs(4);?>Payments made by government offices on their purchases of goods and services from local/resident suppliers</td>
		<td class="td-border-3 align-c">WI 157</td>
		<td class="td-border-3 td-border-thick-right align-c">WC 157</td>
	</tr>
	<tr>
		<td class="td-border-thick-left td-border-top td-border-right" colspan="2"><b>21</b><?php echo nbs(4);?>Payments made by top 10,000 private corporations to their local/resident suppliers of goods</td>
		<td class="td-border-3 align-c">WI 158</td>
		<td class="td-border-3 td-border-thick-right align-c">WC 158</td>
	</tr>
	<tr>
		<td class="td-border-thick-left td-border-top td-border-right" colspan="2"><b>22</b><?php echo nbs(4);?>Payments made by top 10,000 private corporations to their local/resident suppliers of services</td>
		<td class="td-border-3 align-c">WI 160</td>
		<td class="td-border-3 td-border-thick-right align-c">WC 160</td>
	</tr>
	<tr>
		<td class="td-border-thick-left td-border-top td-border-right" colspan="2"><b>23</b><?php echo nbs(4);?>Additional payments to gov't. personnel from importers , shipping and airline companies or their agents</td>
		<td class="td-border-3 align-c">WI 159</td>
		<td class="td-border-3 td-border-thick-right bg-dark-gray">&nbsp;</td>
	</tr>
	<tr>
		<td class="td-border-thick-left td-border-top td-border-right" colspan="2"><b>24</b><?php echo nbs(4);?>Commissions, rebates, discounts and other similar considerations paid/granted to independent and exclusive <br><?php echo nbs(9);?>distributors, medical/technical and sales reperesentatives and marketing agents and sub-agents of <br><?php echo nbs(9);?>multi-level marketing companie</td>
		<td class="td-border-3 align-c">WI 515</td>
		<td class="td-border-3 td-border-thick-right align-c">WC 515</td>
	</tr>
	<tr>
		<td class="td-border-thick-left td-border-top td-border-right" colspan="2"><b>25</b><?php echo nbs(4);?>Fees of directors who are not employee of the company</td>
		<td class="td-border-3 align-c">WI 530</td>
		<td class="td-border-3 td-border-thick-right bg-dark-gray">&nbsp;</td>
	</tr>
	<tr>
		<td class="td-border-thick-left td-border-top td-border-right" colspan="2"><b>26</b><?php echo nbs(4);?>Fees of directors who are not employee of the company</td>
		<td class="td-border-3 align-c">WI 535</td>
		<td class="td-border-3 td-border-thick-right align-c">WC 535</td>
	</tr>
	<tr>
		<td class="td-border-thick-left td-border-tb td-border-right" colspan="2"><b>27</b><?php echo nbs(4);?>Tolling fee paid to refineries</td>
		<td class="td-border-3 align-c">WI 540</td>
		<td class="td-border-3 td-border-thick-right align-c">WC 540</td>
	</tr>
	<tr>
		<td class="td-border-thick-left td-border-thick-right" colspan="4">
			<table class="table-max f-size-7-5">
				<tr>
					<td width="285"><b>28</b><?php echo nbs(4)?>Sale of Real Property (Ordinary Asset)</td>
					<td class="td-border-bottom" width="285">1.5%</td>
					<td width="67" class="td-border-3 align-c">WI 555</td>
					<td width="67" class="td-border-3 align-c">WC 555</td>
				</tr>
				<tr>
					<td width="285">&nbsp;</td>
					<td class="td-border-bottom" width="285">3%</td>
					<td class="td-border-3 align-c" width="67">WI 556</td>
					<td width="66" class="td-border-3 align-c">WC 556</td>
				</tr>
				<tr>
					<td width="285">&nbsp;</td>
					<td class="td-border-bottom" width="285">5%</td>
					<td class="td-border-3 align-c" width="67">WI 557</td>
					<td width="66" class="td-border-3 align-c">WC 557</td>
				</tr>
				<tr>
					<td class="td-border-bottom" width="285">&nbsp;</td>
					<td class="td-border-bottom" width="285">6%</td>
					<td class="td-border-3 align-c" width="67">WI 558</td>
					<td width="66" class="td-border-3 align-c">WC 558</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td class="td-border-thick-left td-border-tb td-border-right" colspan="2"><b>29</b><?php echo nbs(4);?>Income payments made to suppliers of agricultural products</td>
		<td class="td-border-3 align-c">WI 610</td>
		<td class="td-border-3 td-border-thick-right align-c">WC 610</td>
	</tr>
	<tr>
		<td class="td-border-thick-left td-border-tb td-border-right" colspan="2"><b>30</b><?php echo nbs(4);?>Interest payments by any person other than those subject to final tax</td>
		<td class="td-border-3 align-c">WI 620</td>
		<td class="td-border-3 td-border-thick-right align-c">WC 620</td>
	</tr>
	<tr>
		<td class="td-border-thick-left td-border-tb td-border-right" colspan="2"><b>31</b><?php echo nbs(4);?>Income payments on purchases of minerals, mineral products & quarry resources</td>
		<td class="td-border-3 align-c">WI 630</td>
		<td class="td-border-3 td-border-thick-right align-c">WC 630</td>
	</tr>
	<tr>
		<td class="border-thick bg-dark-gray bold" width="550" colspan="4">B Money Payments Subject to Withholding of Business Tax by Government Payor only</td>
	</tr>
	<tr>
		<td class="td-border-thick-left td-border-tb td-border-right" colspan="2"><b>32</b><?php echo nbs(4);?>Tax on carriers and keepers of garages</td>
		<td class="td-border-3 td-border-thick-right align-c" colspan="2">WB 030</td>
	</tr>
	<tr>
		<td class="td-border-thick-left td-border-tb td-border-right" colspan="2"><b>33</b><?php echo nbs(4);?>Franchise Tax on Gas and Water Utilities</td>
		<td class="td-border-3 td-border-thick-right align-c" colspan="2">WB 040</td>
	</tr>
	<tr>
		<td class="td-border-thick-left td-border-tb td-border-right" colspan="2"><b>34</b><?php echo nbs(4);?>Franchise Tax on radio & TV broadcasting companies whose annual gross receipts does not exceed P10M <br><?php echo nbs(9);?>and who are not Value-Added Tax registered taxpayer</td>
		<td class="td-border-3 td-border-thick-right align-c" colspan="2">WB 050</td>
	</tr>
	<tr>
		<td class="td-border-thick-left td-border-tb td-border-right" colspan="2"><b>35</b><?php echo nbs(4);?>Tax on life insurance premiums</td>
		<td class="td-border-3 td-border-thick-right align-c" colspan="2">WB 070</td>
	</tr>
	<tr>
		<td class="td-border-thick-left td-border-tb td-border-right" colspan="2"><b>36</b><?php echo nbs(4);?>Tax on Overseas Dispatch, Message or Conversation originating from the Phils.</td>
		<td class="td-border-3 td-border-thick-right align-c" colspan="2">WB 090</td>
	</tr>
	<tr>
		<td class="td-border-thick-left td-border-right" colspan="2">Tax on Banks and Non-Bank Financial Intermediaries Performing Quasi-Banking Functions</td>
		<td class="td-border-thick-right align-c" colspan="2">&nbsp;</td>
	</tr>
	<tr>
		<td class="td-border-thick-left td-border-right" colspan="2"><b>37</b><?php echo nbs(4);?>A. On interest, commissions and discounts from lending activities as well as income from financial leasing, on the <br><?php echo nbs(9);?>basis of the remaining maturities of instrument from which such receipts are derived</td>
		<td class="td-border-thick-right align-c" colspan="2">&nbsp;</td>
	</tr>
	<tr>
		<td class="td-border-thick-left td-border-thick-right" colspan="4">
			<table class="f-size-9">
				<tr>
					<td width="80">&nbsp;</td>
					<td width="250"><?php echo nbs(20);?>- Maturity period is five years or less</td>
					<td class="td-border-right align-c" width="240">5%</td>	
					<td width="133" class="align-c">WB 301</td>			
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td><?php echo nbs(20);?>- Maturity period is more than five years</td>
					<td class="td-border-right align-c" width="240">1%</td>	
					<td class="align-c">WB 303</td>			
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td class="td-border-thick-left" colspan="2"><b>38</b><?php echo nbs(4);?>Tax on royalties, rentals of property, real or personal, profits from exchange & all other items treated as gross  <br><?php echo nbs(9);?>income under Section 32 of the Code<?php echo nbs(87);?>7%</td>
		<td class="td-border-3 td-border-thick-right align-c" colspan="2">WB 103</td>
	</tr>
	<tr>
		<td class="td-border-bottom td-border-thick-left" colspan="2"><b>39</b><?php echo nbs(4);?>On net trading gains within the taxable year on foreign currency,debt securities, derivatives, and other financial  <br><?php echo nbs(9);?>instruments<?php echo nbs(127);?>7%</td>
		<td class="td-border-3 td-border-thick-right align-c" colspan="2">WB 104</td>
	</tr>
	<tr>
		<td class="td-border-thick-left td-border-right" colspan="2">Tax on Other Non-Banks Financial Intermediaries Not Performing Quasi-Banking Functions</td>
		<td class="td-border-thick-right align-c" colspan="2">&nbsp;</td>
	</tr>
	<tr>
		<td class="td-border-thick-left td-border-right" colspan="2"><?php echo nbs(6);?>A. On interest, commissions and discounts from lending activities as well as income from financial leasing, on the <br><?php echo nbs(9);?>basis of the remaining maturities of instrument from which such receipts are derived</td>
		<td class="td-border-thick-right align-c" colspan="2">&nbsp;</td>
	</tr>
	<tr>
		<td class="td-border-thick-left td-border-thick-right" colspan="4">
			<table class="f-size-9">
				<tr>
					<td width="80"><b>40</b></td>
					<td width="250"><?php echo nbs(20);?>- Maturity period is five years or less</td>
					<td class="td-border-right align-c" width="240">5%</td>	
					<td class="td-border-top align-c" width="133" >WB 108</td>			
				</tr>
				<tr>
					<td><b>41</b></td>
					<td><?php echo nbs(20);?>- Maturity period is more than five years</td>
					<td class="td-border-right align-c" width="240">1%</td>	
					<td class="td-border-tb align-c">WB 109</td>			
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td class="td-border-bottom td-border-thick-left" colspan="2"><b>42</b><?php echo nbs(2);?>B. On all other items treated as gross income under the code<?php echo nbs(51);?>5%</td>
		<td class="td-border-3 td-border-thick-right align-c" colspan="2">WB 110</td>
	</tr>
	<tr>
		<td class="td-border-tb td-border-thick-left" colspan="2"><b>43</b><?php echo nbs(2);?>Business Tax on Agents of foreign insurance co.- insurance agents<?php echo nbs(42);?>10%</td>
		<td class="td-border-3 td-border-thick-right align-c" colspan="2">WB 120</td>
	</tr>
	<tr>
		<td class="td-border-tb td-border-thick-left" colspan="2"><b>44</b><?php echo nbs(2);?>Business Tax on Agents of foreign insurance co.-owner of the property<?php echo nbs(36);?>5%</td>
		<td class="td-border-3 td-border-thick-right align-c" colspan="2">WB 121</td>
	</tr>
	<tr>
		<td class="td-border-tb td-border-thick-left" colspan="2"><b>45</b><?php echo nbs(2);?>Tax on International Carriers</td>
		<td class="td-border-3 td-border-thick-right align-c" colspan="2">WB 130</td>
	</tr>
	<tr>
		<td class="td-border-tb td-border-thick-left" colspan="2"><b>46</b><?php echo nbs(2);?>Tax on Cockpits</td>
		<td class="td-border-3 td-border-thick-right align-c" colspan="2">WB 140</td>
	</tr>
	<tr>
		<td class="td-border-tb td-border-thick-left" colspan="2"><b>47</b><?php echo nbs(2);?>Tax on Cabaret, night and day club</td>
		<td class="td-border-3 td-border-thick-right align-c" colspan="2">WB 150</td>
	</tr>
	<tr>
		<td class="td-border-tb td-border-thick-left" colspan="2"><b>48</b><?php echo nbs(2);?>Tax on Boxing exhibitions</td>
		<td class="td-border-3 td-border-thick-right align-c" colspan="2">WB 160</td>
	</tr>
	<tr>
		<td class="td-border-tb td-border-thick-left" colspan="2"><b>49</b><?php echo nbs(2);?>Tax on Professional basketball games</td>
		<td class="td-border-3 td-border-thick-right align-c" colspan="2">WB 170</td>
	</tr>
	<tr>
		<td class="td-border-tb td-border-thick-left" colspan="2"><b>50</b><?php echo nbs(2);?>Tax on jai-alai and race tracks</td>
		<td class="td-border-3 td-border-thick-right align-c" colspan="2">WB 180</td>
	</tr>
	<tr>
		<td class="td-border-tb td-border-thick-left" colspan="2"><b>51</b><?php echo nbs(2);?>Tax on sale, barter or exchange of stocks listed & traded through Local Stock Exchange</td>
		<td class="td-border-3 td-border-thick-right align-c" colspan="2">WB 200</td>
	</tr>
	<tr>
		<td class="td-border-thick-left td-border-right" colspan="2"><b>52</b><?php echo nbs(2);?>Tax on shares of stock sold or exchanged through initial and secondary public offering</td>
		<td class="td-border-thick-right align-c" colspan="2">&nbsp;</td>
	</tr>
	<tr>
		<td class="td-border-thick-left td-border-thick-right" colspan="4">
			<table class="f-size-9">
				<tr>
					<td width="80">&nbsp;</td>
					<td class="td-border-bottom" width="250">- Not over 25%</td>
					<td class="td-border-bottom td-border-right align-c" width="240">4%</td>	
					<td class="td-border-top align-c" width="133" >WB 201</td>			
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td class="td-border-bottom">- Over 25% but not exceeding 33 1/3 %</td>
					<td class="td-border-bottom td-border-right align-c" width="240">2%</td>	
					<td class="td-border-tb align-c">WB 202</td>			
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td>- Over 33 1/3%</td>
					<td class="td-border-right align-c" width="240">1%</td>	
					<td class="align-c">WB 203</td>			
				</tr>
			</table>
		</td>
	</tr>
</table>
<table class="table-max f-size-7-5">
	<tr>
		<td class="border-thick bg-dark-gray bold" width="550" colspan="3">C Money Payments Subject to Withholding of Business Tax by Government or Private Payors (Individual & Corporate)</td>
	</tr>
	<tr>
		<td class="td-border-bottom td-border-thick-left" width="340"><b>53</b><?php echo nbs(2);?>Person exempt from VAT under Sec. 109 (v) (Government withholding agent)</td>
		<td class="td-border-bottom td-border-right" width="110">3%</td>
		<td class="td-border-3 td-border-thick-right align-c" width="115">WB 080</td>
	</tr>
	<tr>
		<td class="td-border-bottom td-border-thick-left" width="350"><b>54</b><?php echo nbs(2);?>Person exempt from VAT under Sec. 109 (v) (Private withholding agent)</td>
		<td class="td-border-bottom td-border-right">3%</td>
		<td class="td-border-3 td-border-thick-right align-c">WB 082</td>
	</tr>
	<tr>
		<td class="td-border-bottom td-border-thick-left" width="350"><b>55</b><?php echo nbs(2);?>Vat Withholding on Purchase of Goods (with waiver of privilege to claim input tax credits)</td>
		<td class="td-border-bottom td-border-right">10%</td>
		<td class="td-border-3 td-border-thick-right align-c">WB 012</td>
	</tr>
	<tr>
		<td class="td-border-bottom td-border-thick-left" width="350"><b>56</b><?php echo nbs(2);?>Vat Withholding on Purchase of Services (with waiver of privilege to claim input tax credits)</td>
		<td class="td-border-bottom td-border-right">10%</td>
		<td class="td-border-3 td-border-thick-right align-c">WB 022</td>
	</tr>
</table>

</body>