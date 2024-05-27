<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<html>
<head>
 
 <title>Certificate of Compensation Payment/Tax Withheld</title>
 <link rel="stylesheet" href="<?php echo base_url().PATH_CSS ?>reports.css" type="text/css" />
</head>

<body>
<table class="table-max">
  <tr>
    <td class="border-thick">    	
      <!-- TABLE 1 -->
      	<table>
				<tr>
					<td width="240" style="padding: 0px !important;">
						<table>
							<tr>
								<td width="60" align="center"><img src="<?php echo base_url().PATH_IMG ?>bir_logo.png" width=40 height=40></img></td>
								<td><span class="f-size-11">Republika ng Pilipinas<br>Kagawaran ng Pananalapi</span><span class="f-size-12"><br>Kawanihan ng Rentas Internas</span></td>
							</tr>
						</table>
					</td>
					<td class="f-size-22" width="280" align="center" valign="middle">Certificate of Final Tax<br>Withheld At Source</td>
					<td width="40"></td>
					<td class="f-size-11">BIR Form No.<br><span class="f-size-40"><b>2306</b></span></td>			
				</tr>
				<tr>
					<td colspan=3 class="f-size-11" valign=top><?php echo nbs(5)?>&nbsp;</td>
					<td class="f-size-11" valign=top>September 2005 (ENCS)</td>
				</tr>
			</table>		

   		<!-- TABLE 2 -->
			<table class="table-max" style="background-color: #BFBFBF">
				<tr>
					<td class="td-border-thick-top" width="350">
						<table>
							<tr>
								<td width="20" height="10" valign=top><?php echo nbs(1)?><b>1</b></td>
								<td width="80">For the Period<br><?php echo nbs(5)?>From</td>	
								<td width="20" align="center" valign=middle>&#9656;</td>
								<td>
									<table>
										<tr><td height="2"></td></tr>
										<tr>
											<td class="bold td-border-light-left td-border-light-top td-border-light-bottom td-center-middle bg-white" width="15" height="17"><?php echo $date_from[0];?></td>
											<td class="bold td-border-light-left td-border-light-top td-border-light-bottom td-center-middle bg-white" width="15"><?php echo $date_from[1];?></td>
											<td class="bold td-border-light-left td-border-light-top td-border-light-bottom td-center-middle bg-white" width="15"><?php echo $date_from[2];?></td>
											<td class="bold td-border-light-left td-border-light-top td-border-light-bottom td-center-middle bg-white" width="15"><?php echo $date_from[3];?></td>
											<td class="bold td-border-light-left td-border-light-top td-border-light-bottom td-center-middle bg-white" width="15"><?php echo $date_from[6];?></td>
											<td class="bold border-light td-center-middle bg-white" width="17"><?php echo $date_from[7];?></td>
										</tr>
										<tr><td height="2"></td></tr>
									</table>
								</td>
								<td width="20" align="center" valign=middle>(MM/DD/YY)</td>
							</tr>
						</table>
					</td>
					<td class="td-border-thick-top" width="350">
						<table>
							<tr>
								<td width="20" height="10" valign=top><?php echo nbs(4)?></td>
								<td width="80">&nbsp;<br><?php echo nbs(5)?>To</td>	
								<td width="20" align="center" valign=middle>&#9656;</td>
								<td>
									<table>
										<tr><td height="2"></td></tr>
										<tr>
											<td class="bold td-border-light-left td-border-light-top td-border-light-bottom td-center-middle bg-white" width="15" height="17"><?php echo $date_to[0];?></td>
											<td class="bold td-border-light-left td-border-light-top td-border-light-bottom td-center-middle bg-white" width="15"><?php echo $date_to[1];?></td>
											<td class="bold td-border-light-left td-border-light-top td-border-light-bottom td-center-middle bg-white" width="15"><?php echo $date_to[2];?></td>
											<td class="bold td-border-light-left td-border-light-top td-border-light-bottom td-center-middle bg-white" width="15"><?php echo $date_to[3];?></td>
											<td class="bold td-border-light-left td-border-light-top td-border-light-bottom td-center-middle bg-white" width="15"><?php echo $date_to[6];?></td>
											<td class="bold border-light td-center-middle bg-white" width="17"><?php echo $date_to[7];?></td>
										</tr>
										<tr><td height="2"></td></tr>
									</table>
								</td>
								<td width="20" align="center" valign=middle>(MM/DD/YY)</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>

			<!-- TABLE 3 -->
			<table class="table-max" style="background-color: #BFBFBF">
				<tr>
					<td class="td-border-thick-top td-border-thick-right td-border-thick-bottom" width="350" height="15"><b>Part I<?php echo nbs(20)?>Income Recipient/Payee Information</b></td>
					<td class="td-border-thick-top td-border-thick-bottom align-c bold" width="350" height="15">Withholding Agent/Payor Information</b></td>
				</tr>
				<tr>
					<td class="td-border-thick-right td-border-bottom">
						<table>
							<tr>
								<td width="20" height="15" valign=top><?php echo nbs(1)?><b>2</b></td>
								<td width="30">TIN<br><?php echo nbs(2)?>&#9656;</td>	
								<td>
									<table>
										<tr><td height="2"></td></tr>
										<tr>
											<td class="bold td-border-light-left td-border-light-top td-border-light-bottom td-center-middle bg-white" width="15" height="17"><?php echo $payer_info['tin'][0];?></td>
											<td class="bold td-border-light-left td-border-light-top td-border-light-bottom td-center-middle bg-white" width="15"><?php echo $payer_info['tin'][1];?></td>
											<td class="bold td-border-light-left td-border-light-top td-border-light-bottom td-center-middle bg-white" width="15"><?php echo $payer_info['tin'][2];?></td>
											<td class="bold td-border-light-left td-border-light-top td-border-light-bottom" width="15"></td>
											<td class="bold td-border-light-left td-border-light-top td-border-light-bottom td-center-middle bg-white" width="15"><?php echo $payer_info['tin'][3];?></td>
											<td class="bold td-border-light-left td-border-light-top td-border-light-bottom td-center-middle bg-white" width="15"><?php echo $payer_info['tin'][4];?></td>
											<td class="bold td-border-light-left td-border-light-top td-border-light-bottom td-center-middle bg-white" width="15"><?php echo $payer_info['tin'][5];?></td>
											<td class="bold td-border-light-left td-border-light-top td-border-light-bottom" width="15"></td>
											<td class="bold td-border-light-left td-border-light-top td-border-light-bottom td-center-middle bg-white" width="15"><?php echo $payer_info['tin'][6];?></td>
											<td class="bold td-border-light-left td-border-light-top td-border-light-bottom td-center-middle bg-white" width="15"><?php echo $payer_info['tin'][7];?></td>
											<td class="bold td-border-light-left td-border-light-top td-border-light-bottom td-center-middle bg-white" width="15"><?php echo $payer_info['tin'][8];?></td>
											<td class="bold td-border-light-left td-border-light-top td-border-light-bottom" width="15"></td>
											<td class="bold td-border-light-left td-border-light-top td-border-light-bottom td-center-middle bg-white" width="15"><?php echo $payer_info['tin'][9];?></td>
											<td class="bold td-border-light-left td-border-light-top td-border-light-bottom td-center-middle bg-white" width="15"><?php echo $payer_info['tin'][10];?></td>
											<td class="bold border-light td-center-middle bg-white" width="15"><?php echo $payer_info['tin'][11];?></td>
										</tr>
										<tr><td height="2"></td></tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
					<td class="td-border-bottom">
						<table>
							<tr>
								<td width="20" height="15" valign=top><?php echo nbs(1)?><b>3</b></td>
								<td width="30">TIN<br><?php echo nbs(2)?>&#9656;</td>	
								<td>
									<table>
										<tr><td height="2"></td></tr>
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
											<td class="bold border-light td-center-middle bg-white" width="15"><?php echo $doh_tin[11];?></td>
										</tr>
										<tr><td height="2"></td></tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td class="td-border-thick-right td-border-bottom">
						<table>
							<tr>
								<td width="15" valign=top><?php echo nbs(1)?><b>4</b></td>
								<td width="">Payee's Name (For Non-Individuals)</td>	
							</tr>
							<tr>
								<td><?php echo nbs(2)?>&#9656;</td>
								<td class="border-light bg-white" width="330" height="17"></td>
							</tr>
							<tr><td height="2"></td></tr>
						</table>
					</td>
					<td class="td-border-bottom">
						<table>
							<tr>
								<td width="15" valign=top><?php echo nbs(1)?><b>5</b></td>
								<td width="">Payor's Name (For Non-Individuals)</td>	
							</tr>
							<tr>
								<td><?php echo nbs(2)?>&#9656;</td>
								<td class="border-light bg-white align-c bold" width="330" height="17">DEPARTMENT OF HEALTH</td>
							</tr>
							<tr><td height="2"></td></tr>
						</table>
					</td>
				</tr>
				<tr>
					<td class="td-border-thick-right td-border-bottom">
						<table>
							<tr>
								<td width="15" valign=top><?php echo nbs(1)?><b>6</b></td>
								<td width="">Payee's Name (Last Name, First Name, Middle Name) For Individuals</td>	
							</tr>
							<tr>
								<td><?php echo nbs(2)?>&#9656;</td>
								<td class="bold border-light bg-white align-c" width="330" height="17"><?php echo $payer_info['last_name'] . ', ' . $payer_info['first_name'] . ' ' . $payer_info['middle_name'] . '.';?></td>
							</tr>
							<tr><td height="2"></td></tr>
						</table>
					</td>
					<td class="td-border-bottom">
						<table>
							<tr>
								<td width="15" valign=top><?php echo nbs(1)?><b>7</b></td>
								<td width="">Payor's Name (Last Name, First Name, Middle Name) For Individuals</td>	
							</tr>
							<tr>
								<td><?php echo nbs(2)?>&#9656;</td>
								<td class="bold border-light bg-white align-c" width="330" height="17"><?php echo $header['employee_name'];?></td>
							</tr>
							<tr><td height="2"></td></tr>
						</table>
					</td>
				</tr>
				<tr>
					<td class="td-border-thick-right">
						<table>
							<tr>
								<td width="15" valign=top><?php echo nbs(1)?><b>8</b></td>
								<td width="">Registered Address</td>	
							</tr>
							<tr>
								<td><?php echo nbs(2)?>&#9656;</td>
								<td class="bold border-light bg-white align-c" width="330" height="17"><?php echo $payer_info['local_address'];?></td>
							</tr>
							<tr><td height="2"></td></tr>
						</table>
					</td>
					<td>
						<table>
							<tr>
								<td width="15" valign=top><?php echo nbs(1)?><b>9</b></td>
								<td width="">Registered Address</td>	
							</tr>
							<tr>
								<td><?php echo nbs(2)?>&#9656;</td>
								<td class="bold border-light bg-white align-c" width="330" height="17"><?php echo $doh_add;?></td>
							</tr>
							<tr><td height="2"></td></tr>
						</table>
					</td>
				</tr>
				<tr>
					<td class="td-border-thick-right td-border-bottom">
						<table>
							<tr>
								<td width="15">&nbsp;</td>
								<td class="border-light bg-white" width="202" height="17"></td>
								<td><?php echo nbs(2);?><b>8A</b> Zip Code</td>
								<td class="f-size-8" width="4"></td>							
								<td class=" bold f-size-8 td-border-light-left td-border-light-top td-border-light-bottom td-center-middle bg-white" width="15" height="17"><?php echo $payer_info['loc_zip'][0];?></td>
								<td class=" bold f-size-8 td-border-light-left td-border-light-top td-border-light-bottom td-center-middle bg-white" width="15"><?php echo $payer_info['loc_zip'][1];?></td>
								<td class=" bold f-size-8 td-border-light-left td-border-light-top td-border-light-bottom td-center-middle bg-white" width="15"><?php echo $payer_info['loc_zip'][2];?></td>
								<td class=" bold f-size-8 border-light bg-white" width="15"><?php echo $payer_info['loc_zip'][3];?></td>
								<td class="f-size-8" width="4"></td>							
							</tr>
							<tr><td height="2"></td></tr>
						</table>
					</td>
					<td class="td-border-bottom">
						<table>
							<tr>
								<td width="15">&nbsp;</td>
								<td class="bold border-light bg-white" width="200" height="17"><?php echo $header['employee_name'];?></td>
								<td><?php echo nbs(2);?><b>9A</b> Zip Code</td>
								<td class="f-size-8" width="4"></td>							
								<td class="bold border-light td-center-middle bg-white" width="15" height="17"><?php echo $doh_zip_code[0];?></td>
								<td class="bold border-light td-center-middle bg-white" width="15"><?php echo $doh_zip_code[1];?></td>
								<td class="bold border-light td-center-middle bg-white" width="15"><?php echo $doh_zip_code[2];?></td>
								<td class="bold border-light td-center-middle bg-white" width="15"><?php echo $doh_zip_code[3];?></td>
								<td class="f-size-8" width="4"></td>							
							</tr>
							<tr><td height="2"></td></tr>
						</table>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<table>
							<tr>
								<td width="15" valign=top><b>10</b></td>
								<td>Foreign Address</td>
								<td colspan="5"><?php echo nbs(2)?><b>10A</b> Zip Code</td>
								<td width="270" colspan="2"><?php echo nbs(2)?><b>10B</b> ICR No. (For Alien Income Recipient Only)</td>
							</tr>
							<tr>
								<td><?php echo nbs(2)?>&#9656;</td>
								<td class="bold border-light bg-white" width="257" height="17"><?php echo $payer_info['foreign_address'];?></td>
								<td class="align-r" width="12">&nbsp;</td>
								<td class="bold f-size-8 td-border-light-left td-border-light-top td-border-light-bottom td-center-middle bg-white" width="15" height="17"><?php echo $payer_info['for_zip'][0];?></td>
								<td class="bold f-size-8 td-border-light-left td-border-light-top td-border-light-bottom td-center-middle bg-white" width="15"><?php echo $payer_info['for_zip'][1];?></td>
								<td class="bold f-size-8 td-border-light-left td-border-light-top td-border-light-bottom td-center-middle bg-white" width="15"><?php echo $payer_info['for_zip'][2];?></td>
								<td class="bold f-size-8 border-light bg-white" width="15"><?php echo $payer_info['for_zip'][3];?></td>
								<td class="align-r" width="90">&#9656;<?php echo nbs(5)?></td>
								<td class="bold border-light bg-white" width="260" height="17"><?php echo $header['employee_name'];?></td>                         
							</tr>
							<tr><td height="2"></td></tr>
						</table>
					</td>
				</tr>
			</table>
    </td>
  </tr>
  <tr style="background-color: #BFBFBF">
	<td class="border-thick" colspan="4"><b>Part II<?php echo nbs(35);?>Details of Income Payment and Tax Withheld (Attach additional sheet if necessary)</b></td>
  </tr>  
  <tr>
  	<td class="border-thick">
  		<table class="table-max">
  			<tr class="bg-dark-gray">
  				<td class="td-border-bottom td-border-right align-c bold" width="250">Nature of Income Payment</td>
  				<td class="td-border-bottom td-border-right align-c bold" width="100">A T C</td>
  				<td class="td-border-bottom td-border-right align-c bold" width="170">Amount of Payment</td>
  				<td class="td-border-bottom align-c bold" width="180">Tax Withheld</td>
  			</tr>

  			<?php 
				$total = count($details);
				$diff	= 20 - $total;
				// TOTAL FOR EVERY FIELD
				$payment_total	= 0;
  				$tax_total		= 0;
				if(!EMPTY($details)){ 

				foreach($details as $detail): 
					$payment_total 	= $payment_total + $detail['payment'];
  					$tax_total		= $tax_total + $detail['tax'];

			?>
			<tr>
  				<td class="bold td-border-bottom td-border-right"><?php echo $detail['nature'];?></td>
  				<td class="bold td-border-bottom td-border-right align-c"><?php echo $detail['atc'];?></td>
  				<td class="bold td-border-bottom td-border-right align-r"><?php echo number_format($detail['payment'], 2);?></td>
  				<td class="bold td-border-bottom align-r"><?php echo number_format($detail['tax'], 2);?></td>
  			</tr>
			<?php 
				endforeach; 
			}
				for($i = 0; $i < $diff; $i++)
				{
			?>
			<tr>
  				<td class="td-border-bottom td-border-right">&nbsp;</td>
  				<td class="td-border-bottom td-border-right align-r">&nbsp;</td>
  				<td class="td-border-bottom td-border-right align-r">&nbsp;</td>
  				<td class="td-border-bottom align-r">&nbsp;</td>
  			</tr>
			<?php } ?>

  			<!-- <?php 
  			$payment_total	= 0;
  			$tax_total		= 0;
  			foreach ($details as $detail):
  			$payment_total 	= $payment_total + $detail['payment'];
  			$tax_total		= $tax_total + $detail['tax'];
  			?>
  			<tr>
  				<td class="td-border-bottom td-border-right"><?php echo $detail['nature'];?></td>
  				<td class="td-border-bottom td-border-right align-r"><?php echo $detail['atc'];?></td>
  				<td class="td-border-bottom td-border-right align-r"><?php echo number_format($detail['payment'], 2);?></td>
  				<td class="td-border-bottom align-r"><?php echo number_format($detail['tax'], 2);?></td>
  			</tr>
  			<?php endforeach;?> -->
  			<tr>
  				<td class="bold td-border-bottom td-border-right bold bg-dark-gray align-c" colspan="2">Total</td>
  				<td class="bold td-border-bottom td-border-right align-r"><?php echo number_format($payment_total, 2);?></td>
  				<td class="bold td-border-bottom align-r"><?php echo number_format($tax_total, 2);?></td>
  			</tr>
  		</table>
  	</td>
  </tr>
  <tr>
  	<td class="border-thick">
  		<table class="table-max">
  			<tr>
  				<td width="15">&nbsp;</td>
  				<td class="align-j f-size-9" colspan="7">
  					<?php echo nbs(8);?>We declare, under the penalties of perjury, that this certificate has been made in good faith, verified by us, and to the best of our knowledge and belief, is true and correct pursuant to the provisions of the National Internal Revenue Code, as amended, and the regulations issued under authority thereof
  				</td>
  				<td width="15">&nbsp;</td>
  			</tr>
  			<tr>
  				<td colspan="9">&nbsp;</td>
  			</tr>
  			<tr>
  				<td width="15">&nbsp;</td>
  				<td class="td-border-top f-size-8 align-c">Payor/Payor's Authorized Representative/Accredited Tax Agent</td>
  				<td width="15">&nbsp;</td>
  				<td class="td-border-top f-size-8 align-c">TIN of Signatory</td>
  				<td width="15">&nbsp;</td>
  				<td class="td-border-top f-size-8 align-c">Title/Position of Signatory</td>
  				<td width="15">&nbsp;</td>
  				<td class="td-border-top f-size-8 align-c">Date Signed</td>
  				<td width="15">&nbsp;</td>
  			</tr>
  			<tr>
  				<td>&nbsp;</td>
  				<td class="align-c f-size-7">Signature Over Printed Name</td>
  				<td colspan="7"></td>
  			</tr>
  			<tr>
  				<td colspan="9">&nbsp;</td>
  			</tr>
  			<tr>
  				<td width="15">&nbsp;</td>
  				<td class="td-border-top f-size-8 align-c">Tax Agent Accreditation No./Attorney's Roll No. (if applicable)</td>
  				<td>&nbsp;</td>
  				<td class="td-border-top f-size-8 align-c">Date of Issuance</td>
  				<td>&nbsp;</td>
  				<td class="td-border-top f-size-8 align-c">Date of Expiry</td>
  				<td colspan="15">&nbsp;</td>
  			</tr>
  			<tr>
  				<td colspan="9">&nbsp;</td>
  			</tr>
  			<tr>
  				<td></td>
  				<td colspan="8">CONFORME:</td>
  			</tr>
  			<tr>
  				<td width="15">&nbsp;</td>
  				<td class="bold f-size-8 align-c" height="15" valign="bottom"><?php echo $payer_info['first_name'] . ' ' . $payer_info['middle_name'] . '. ' . $payer_info['last_name'];?></td>
  				<td width="15">&nbsp;</td>
  				<td class="bold f-size-8 align-c"><?php echo format_identifications($payer_info['tin'], $tin_format['format']) ?></td>
  				<td width="15">&nbsp;</td>
  				<td class="bold f-size-8 align-c"><?php echo $payer_info['employ_position_name']?></td>
  				<td width="15">&nbsp;</td>
  				<td class="f-size-8 align-c"></td>
  				<td width="15">&nbsp;</td>
  			</tr>
  			<tr>
  				<td width="15">&nbsp;</td>
  				<td class="td-border-top f-size-8 align-c">Payee/Payee's Authorized Representative/Accredited Tax Agent</td>
  				<td width="15">&nbsp;</td>
  				<td class="td-border-top f-size-8 align-c">TIN of Signatory</td>
  				<td width="15">&nbsp;</td>
  				<td class="td-border-top f-size-8 align-c">Title/Position of Signatory</td>
  				<td width="15">&nbsp;</td>
  				<td class="td-border-top f-size-8 align-c">Date Signed</td>
  				<td width="15">&nbsp;</td>
  			</tr>
  			<tr>
  				<td>&nbsp;</td>
  				<td class="align-c f-size-7">Signature Over Printed Name</td>
  				<td colspan="7"></td>
  			</tr>
  			<tr>
  				<td colspan="9">&nbsp;</td>
  			</tr>
  			<tr>
  				<td width="15">&nbsp;</td>
  				<td class="td-border-top f-size-8 align-c">Tax Agent Accreditation No./Attorney's Roll No. (if applicable)</td>
  				<td>&nbsp;</td>
  				<td class="td-border-top f-size-8 align-c">Date of Issuance</td>
  				<td>&nbsp;</td>
  				<td class="td-border-top f-size-8 align-c">Date of Expiry</td>
  				<td colspan="15">&nbsp;</td>
  			</tr>
  			<tr>
  				<td colspan="9">&nbsp;</td>
  			</tr>
  			
  		</table>
  	</td>
  </tr>
  <tr>
  	<td class="border-thick">
  		<table class="table-max">
  			<tr> 
  				<td width="15">&nbsp;</td>
  				<td class="f-size-9 bold" align="center" colspan="7">To be accomplished for Value-Added Tax/Percentage Tax Withholding (substituted filing)</td>
  				<td width="15">&nbsp;</td>
  			</tr>
  			<tr>
  				<td width="15">&nbsp;</td>
  				<td class="valign-top align-j f-size-8" colspan="3">
  					<?php echo nbs(8);?>I declare, under the penalties of perjury, that the information herein stated are reported under BIR Form No. 1600 which have been filed with the Bureau of Internal Revenue.
  				</td>
  				<td width="15">&nbsp;</td>
  				<td class="valign-top align-j f-size-8" colspan="3" rowspan="4">
  					<?php echo nbs(8);?>I declare under the penalties of perjury that I am qualified under substituted filing of Percentage
Tax/Value Added Tax Returns (BIR Form 2551M/2550M/Q), since I have only one payor from
whom I earn my income; that, in accordance with RR 14-2003, I have availed of the Optional
Registration under the 3% Final Percentage Tax Wthholding/10% Final VAT Withholding in lieu
of the 3% Percentage Tax/10% VAT in order to be entitled to the privileges accorded by the
Substituted Percentage Tax Return/Substituted VAT Return System prescribed in the aforesaid
Regulations; that, this Declaration is sufficient authority of the withholding agent to withhold 3%
Final Percentage Tax/10% Final VAT from my sale of goods and/or services.
  				</td>
  				<td width="15">&nbsp;</td>
  			</tr>
  			<tr>
  				<td colspan="5">&nbsp;</td>
  				<td>&nbsp;</td>
  			</tr>
  			<tr>
  				<td>&nbsp;</td>
  				<td class="td-border-top f-size-7 align-c" colspan="3">Payor/Payor's Authorized Representative/Accredited Tax Agent</td>
  				<td>&nbsp;</td>
  				<td>&nbsp;</td>
  			</tr>
  			<tr>
  				<td>&nbsp;</td>
  				<td class="f-size-8 align-c" colspan="3">Signature Over Printed Name</td>
  				<td>&nbsp;</td>
  				<td>&nbsp;</td>
  			</tr>
  			<tr>
  				<td width="15">&nbsp;</td>
  				<td class="f-size-8 align-c"></td>
  				<td width="15">&nbsp;</td>
  				<td class="f-size-8 align-c"></td>
  				<td width="15">&nbsp;</td>
  				<td class="bold f-size-8 align-c" height="15" valign="bottom"><?php echo $payer_info['first_name'] . ' ' . $payer_info['middle_name'] . '. ' . $payer_info['last_name'];?></td>
  				<td width="15">&nbsp;</td>
  				<td class="bold f-size-8 align-c"><?php echo $payer_info['employ_position_name']?></td>
  				<td width="15">&nbsp;</td>
  			</tr>
  			<tr>
  				<td width="15">&nbsp;</td>
  				<td class="td-border-top f-size-8 align-c">TIN of Signatory</td>
  				<td width="15">&nbsp;</td>
  				<td class="td-border-top f-size-8 align-c">Title/Position of Signatory</td>
  				<td width="15">&nbsp;</td>
  				<td class="td-border-top f-size-8 align-c">Payee/Payee's Authorized Representative/Accredited Tax Agent</td>
  				<td width="15">&nbsp;</td>
  				<td class="td-border-top f-size-8 align-c">Title/Position of Signatory</td>
  				<td width="15">&nbsp;</td>
  			</tr>
  			<tr>
  				<td colspan="5">&nbsp;</td>
  				<td class="align-c f-size-7">Signature Over Printed Name</td>
  				<td>&nbsp;</td>
  			</tr>
  			<tr>
  				<td width="15">&nbsp;</td>
  				<td colspan="3">&nbsp;</td>
  				<td width="15">&nbsp;</td>
  				<td>&nbsp;</td>
  				<td width="15">&nbsp;</td>
  				<td class="bold f-size-8 align-c"><?php echo format_identifications($payer_info['tin'],$tin_format['format']) ?></td>
  				<td width="15">&nbsp;</td>
  			</tr>
  			<tr>
  				<td width="15">&nbsp;</td>
  				<td class="td-border-top f-size-8 align-c" colspan="3">Tax Agent Accreditation No./Attorney's Roll No. (if applicable)</td>
  				<td width="15">&nbsp;</td>
  				<td class="td-border-top f-size-8 align-c">Tax Agent Accreditation No./Attorney's Roll No. (if applicable)</td>
  				<td width="15">&nbsp;</td>
  				<td class="td-border-top f-size-8 align-c">TIN of Signatory</td>
  				<td width="15">&nbsp;</td>
  			</tr>
  			<tr>
  				<td colspan="9">&nbsp;</td>
  			</tr>
  			<tr>
  				<td width="15">&nbsp;</td>
  				<td class="td-border-top f-size-8 align-c">Date of Issuance</td>
  				<td width="15">&nbsp;</td>
  				<td class="td-border-top f-size-8 align-c">Date of Expiry</td>
  				<td width="15">&nbsp;</td>
  				<td class="td-border-top f-size-8 align-c">Date of Issuance</td>
  				<td width="15">&nbsp;</td>
  				<td class="td-border-top f-size-8 align-c">Date of Expiry</td>
  				<td width="15">&nbsp;</td>
  			</tr>
  		</table>
  	</td>
  </tr>
</table>
</body>
</html>