
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<html>
<head>
	
	<title>BIR 1601-c Form</title>
	<link rel="stylesheet" href="<?php echo base_url().PATH_CSS ?>reports.css" type="text/css" />
</head>

<body>
<table class="table-max">
	<tr>
		<td><span>(To be filled up by the BIR)</span><br><?php echo nbs(3)?><b>&#9656;<?php echo nbs(2)?>DLN:<?php echo nbs(130)?>&#9656;<?php echo nbs(2)?>PSOC:<?php echo nbs(15)?>&#9656;<?php echo nbs(2)?>PSIC:</b></td>
	</tr> 
</table>
<!-- MAIN TABLE -->
<table class="table-max">
	<tr>
		<td class="border-thick">
			<!-- TABLE 1 -->
			<table>
				<tr>
					<td width="240" height="70">
						<table><!-- LOGO TABLE -->
							<tr>
								<td width="60" align="center"><img src="<?php echo base_url().PATH_IMG ?>bir_logo.png" width=40 height=40></img></td>
								<td><span class="f-size-11">Republika ng Pilipinas<br>Kagawaran ng Pananalapi</span><span class="f-size-12"><br>Kawanihan ng Rentas Internas</span></td>
							</tr>
						</table>
					</td>
					<td class="f-size-20" width="330" align="center" valign="middle"><b>Monthly Remittance Return<br>of Income Taxes Withheld<br>on Compensation</b></td>
					<td width="40"></td>
					<td class="f-size-11">BIR Form No.<br><span class="f-size-40"><b>1601-C</b></span><br>July  2008 (ENCS)</td>			
				</tr>
			</table>
			<!-- TABLE 2 -->
			<table class="table-max" style="background-color: #BFBFBF">
				<tr>
					<td colspan=4 class="td-border-thick-bottom td-border-thick-top" height="15" width="100%" style="background-color: white"><?php echo nbs(3)?>Fill in all applicable spaces. Mark all appropriate boxes with an “X”.</td>
				</tr>
				<tr>
					<td class="td-border-right" width="280">
						<table>
							<tr>
								<td width="20" height="15"><?php echo nbs(1)?><b>1</b></td>
								<td colspan=3 width="260"><?php echo nbs(2)?>For the Month</td>								
							</tr>
							<tr>
								<td></td>
								<td width="55" valign=top><?php echo nbs(2)?>(MM/YYYY)</td>
								<td width="50" align="center" valign=middle>&#9656;</td>
								<td>
									<table>
										<tr>
											<td class="td-border-light-left td-border-light-top td-border-light-bottom td-center-middle" width="20" height="20" style="background-color: white"><?php echo $month_year[0];?></td>
											<td class="td-border-light-left td-border-light-top td-border-light-bottom td-center-middle" width="20" style="background-color: white"><?php echo $month_year[1];?></td>
											<td class="td-border-light-left td-border-light-top td-border-light-bottom td-center-middle" width="20" style="background-color: white"><?php echo $month_year[2];?></td>
											<td class="td-border-light-left td-border-light-top td-border-light-bottom td-center-middle" width="20" style="background-color: white"><?php echo $month_year[3];?></td>
											<td class="td-border-light-left td-border-light-top td-border-light-bottom td-center-middle" width="20" style="background-color: white"><?php echo $month_year[4];?></td>
											<td class="border-light td-center-middle" width="20" style="background-color: white"><?php echo $month_year[5];?></td>
										</tr>
										<tr>
											<td height="5"></td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
					<td class="td-border-right" width="160">
						<table>
							<tr>
								<td width="20" height="10" valign=top><?php echo nbs(1)?><b>2</b></td>
								<td width="140" valign=top>Amended Return?</td>
							</tr>
							<tr>
								<td colspan=2>
									<table>
										<tr>
											<td width="35" align="right" height="22">&#9656;<?php echo nbs(3)?></td>
											<td class="border-light" width="20" style="background-color: white"></td>
											<td width="40" valign=bottom><?php echo nbs(2)?>Yes</td>
											<td class="align-c border-light" width="20" style="background-color: white">X</td>
											<td width="40" valign=bottom><?php echo nbs(2)?>No</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
					<td class="td-border-right" width="160">
						<table>
							<tr>
								<td width="20" height="10" valign=top><?php echo nbs(1)?><b>3</b></td>
								<td width="140" valign=top>No. of Sheets Attached</td>
							</tr>
							<tr>
								<td colspan=2>
									<table>
										<tr>
											<td width="80"></td>
											<td class="td-border-light-left td-border-light-top td-border-light-bottom" width="27" height="20" style="background-color: white"></td>
											<td class="border-light" width="27" style="background-color: white"></td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
					<td width="160">
						<table>
							<tr>
								<td width="20" height="10" valign=top><?php echo nbs(1)?><b>4</b></td>
								<td width="140" valign=top>Any Taxes Withheld?</td>
							</tr>
							<tr>
								<td colspan=2>
									<table>
										<tr>
											<td width="35" align="right" height="22">&#9656;<?php echo nbs(3)?></td>
											<td class="align-c border-light" width="20" style="background-color: white">X</td>
											<td width="40" valign=bottom><?php echo nbs(2)?>Yes</td>
											<td class="border-light" width="20" style="background-color: white"></td>
											<td width="40" valign=bottom><?php echo nbs(2)?>No</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>

			<!-- TABLE 2 -->
			<table style="background-color: #BFBFBF">
				<tr>
					<td colspan=3 class="td-border-thick-bottom td-border-thick-top" width="100%" height="15"><?php echo nbs(1)?><b>Part I<?php echo nbs(80)?>B a c k g r o u n d<?php echo nbs(3)?>I n f o r m a t i o n</b></td>
				</tr>
				<tr>
					<td class="td-border-right" width="350" height="30">
						<table>
							<tr>
								<td width="20" valign=top><?php echo nbs(1)?><b>5</b></td>
								<td width="25"><?php echo nbs(2)?>TIN<br><?php echo nbs(2)?>&#9656;</td>
								<td class="td-border-light-left td-border-light-top td-border-light-bottom td-center-middle" width="20" height="20" style="background-color: white"><?php echo $doh_tin[0];?></td>
								<td class="td-border-light-left td-border-light-top td-border-light-bottom td-center-middle" width="17" style="background-color: white"><?php echo $doh_tin[1];?></td>
								<td class="td-border-light-left td-border-light-top td-border-light-bottom td-center-middle" width="17" style="background-color: white"><?php echo $doh_tin[2];?></td>
								<td class="td-border-light-left td-border-light-top td-border-light-bottom" width="20" style="background-color: #FFCC99"></td>
								<td class="td-border-light-left td-border-light-top td-border-light-bottom td-center-middle" width="20" style="background-color: white"><?php echo $doh_tin[3];?></td>
								<td class="td-border-light-left td-border-light-top td-border-light-bottom td-center-middle" width="20" style="background-color: white"><?php echo $doh_tin[4];?></td>
								<td class="td-border-light-left td-border-light-top td-border-light-bottom td-center-middle" width="17" style="background-color: white"><?php echo $doh_tin[5];?></td>
								<td class="td-border-light-left td-border-light-top td-border-light-bottom" width="20" style="background-color: #FFCC99"></td>
								<td class="td-border-light-left td-border-light-top td-border-light-bottom td-center-middle" width="20" style="background-color: white"><?php echo $doh_tin[6];?></td>
								<td class="td-border-light-left td-border-light-top td-border-light-bottom td-center-middle" width="20" style="background-color: white"><?php echo $doh_tin[7];?></td>
								<td class="td-border-light-left td-border-light-top td-border-light-bottom td-center-middle" width="15" style="background-color: white"><?php echo $doh_tin[8];?></td>
								<td class="td-border-light-left td-border-light-top td-border-light-bottom" width="20" style="background-color: #FFCC99"></td>
								<td class="td-border-light-left td-border-light-top td-border-light-bottom td-center-middle" width="17" style="background-color: white"><?php echo $doh_tin[9];?></td>
								<td class="td-border-light-left td-border-light-top td-border-light-bottom td-center-middle" width="17" style="background-color: white"><?php echo $doh_tin[10];?></td>
								<td class="td-border-light-left td-border-light-top td-border-light-bottom td-center-middle" width="17" style="background-color: white"><?php echo $doh_tin[11];?></td>
								<td class="border-light td-center-middle" width="17" style="background-color: white"><?php echo $doh_tin[12];?></td>
							</tr>
						</table>
					</td>
					<td class="td-border-right" width="135">
						<table>
							<tr>
								<td width="20" valign=top><?php echo nbs(1)?><b>6</b></td>
								<td width="25" align="right">RDO Code<br>&#9656;</td>
								<td width="10"></td>
								<td class="td-border-light-left td-border-light-top td-border-light-bottom td-center-middle" width="17" style="background-color: white"><?php echo $rdo_code[0];?></td>
								<td class="td-border-light-left td-border-light-top td-border-light-bottom td-center-middle" width="17" style="background-color: white"><?php echo $rdo_code[1];?></td>
								<td class="border-light td-center-middle" width="17" style="background-color: white"><?php echo $rdo_code[2];?></td>
							</tr>
						</table>
					</td>
					<td width="278">
						<table>
							<tr>
								<td width="20" valign=top><?php echo nbs(1)?><b>7</b></td>
								<td width="40">Line of Business/<br>Occupation<?php echo nbs(8)?>&#9656;</td>
								<td width="10"></td>
								<td class="border-light" width="165" style="background-color: white"></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
			<!-- TABLE 3 -->
			<table style="background-color: #BFBFBF">
				<tr>
					<td colspan=2 class="td-border-right td-border-thick-top" width="650" height="40">
						<table>
							<tr>
								<td width="20"><?php echo nbs(1)?><b>8</b></td>
								<td colspan=2><?php echo nbs(2)?>Withholding Agent's Name<?php echo nbs(3)?>(Last Name, First Name, Middle Name for Individuals)/(Registered Name for Non-Individuals)</td>
							</tr>
							<tr>
								<td></td>
								<td align="left" valign=middle width="20"><?php echo nbs(2)?>&#9656;</td>
								<td class="border-light" width="584" height="20" style="background-color: white">Department of Health</td>
							</tr>
						</table>
					</td>
					<td class="td-border-thick-top" width="133">
						<table>
							<tr>
								<td width="10"><?php echo nbs(1)?><b>9</b></td>
								<td>Telephone Number</td>
							</tr>
							<tr>
								<td colspan=2>
									<table>
										<tr>
											<td width="7"></td>
											<td class="align-c td-border-light-left td-border-light-top td-border-light-bottom" width="17" height="20" style="background-color: white">6</td>
											<td class="align-c td-border-light-left td-border-light-top td-border-light-bottom" width="17" style="background-color: white">5</td>
											<td class="align-c td-border-light-left td-border-light-top td-border-light-bottom" width="17" style="background-color: white">1</td>
											<td class="align-c td-border-light-left td-border-light-top td-border-light-bottom" width="17" style="background-color: white">7</td>
											<td class="align-c td-border-light-left td-border-light-top td-border-light-bottom" width="17" style="background-color: white">8</td>
											<td class="align-c td-border-light-left td-border-light-top td-border-light-bottom" width="17" style="background-color: white">0</td>
											<td class="align-c border-light" width="17" style="background-color: white">0</td>											
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td colspan=2 class="td-border-right td-border-thick-top" width="630" height="40">
						<table>
							<tr>
								<td width="20"><?php echo nbs(1)?><b>10</b></td>
								<td colspan=2><?php echo nbs(2)?>Registered Address</td>
							</tr>
							<tr>
								<td></td>
								<td align="left" valign=middle width="20"><?php echo nbs(2)?>&#9656;</td>
								<td class="border-light" width="584" height="20" style="background-color: white"><?php echo $doh_add;?></td>
							</tr>
						</table>
					</td>
					<td class="td-border-thick-top" width="133">
						<table>
							<tr>
								<td width="20"><?php echo nbs(1)?><b>11</b></td>
								<td colspan=2><?php echo nbs(2)?>Zip Code</td>
							</tr>
							<tr>
								<td></td>
								<td width="20" align="left"><?php echo nbs(2)?>&#9656;</td>
								<td>
									<table>
										<tr>
											<td class="td-border-light-left td-border-light-top td-border-light-bottom td-center-middle" width="22" height="20" style="background-color: white"><?php echo $doh_zip_code[0]?></td>
											<td class="td-border-light-left td-border-light-top td-border-light-bottom td-center-middle" width="22" style="background-color: white"><?php echo $doh_zip_code[1]?></td>
											<td class="td-border-light-left td-border-light-top td-border-light-bottom td-center-middle" width="22" style="background-color: white"><?php echo $doh_zip_code[2]?></td>
											<td class="border-light td-center-middle" width="21" style="background-color: white"><?php echo $doh_zip_code[3]?></td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td class="td-border-right td-border-thick-top" width="200" height="40">
						<table>
							<tr>
								<td width="20"><?php echo nbs(1)?><b>12</b></td>
								<td colspan=5><?php echo nbs(2)?>Category of Withholding Agent</td>
							</tr>
							<tr>
								<td width="20"></td>
								<td width="20" valign=middle height="22"><?php echo nbs(2)?>&#9656;</td>
								<td class="border-light" width="20" style="background-color: white"></td>
								<td width="40" valign=bottom><?php echo nbs(2)?>Private</td>
								<td class="border-light td-center-middle" width="20" style="background-color: white">X</td>
								<td width="40" valign=bottom><?php echo nbs(2)?>Government</td>
							</tr>
						</table>
					</td>
					<td class="td-border-right td-border-thick-top" width="430">
						<table>
							<tr>
								<td colspan=2 width="20"><?php echo nbs(1)?><b>13</b><?php echo nbs(3)?>Are there payees availing of tax relief under Special Law</td>
							</tr>
							<tr>
								<td>
									<table>
										<tr>
											<td></td>
											<td colspan=5><?php echo nbs(1)?>or International Tax Treaty?</td>
										</tr>
										<tr>
											<td width="24"></td>
											<td class="border-light" width="25" height="12" style="background-color: white"></td>
											<td width="40" valign=bottom><?php echo nbs(2)?>Yes</td>
											<td class="border-light" width="25" height="12" style="background-color: white"></td>
											<td width="40" valign=bottom><?php echo nbs(2)?>No</td>
											<td width="50" valign=bottom><?php echo nbs(2)?>If yes, specify<?php echo nbs(3)?></td>
										</tr>
									</table>
								</td>
								<td class="border-light" width="193" height="20" style="background-color: white"></td>
							</tr>
						</table>
					</td>
					<td class="td-border-thick-top" width="133">
						<table>
							<tr>
								<td width="20"><?php echo nbs(1)?><b>14</b></td>
								<td colspan=2><?php echo nbs(2)?>A T C</td>
							</tr>
							<tr>
								<td></td>
								<td width="20" align="left"><?php echo nbs(2)?>&#9656;</td>
								<td>
									<table>
										<tr>
											<td class="td-border-light-left td-border-light-top td-border-light-bottom" width="18" height="20" align="center" style="background-color: white">W</td>
											<td class="td-border-light-top td-border-light-bottom" width="18" align="center" style="background-color: white">W</td>
											<td class="td-border-light-top td-border-light-bottom" width="18" align="center" style="background-color: white">0</td>
											<td class="td-border-light-top td-border-light-bottom" width="17" align="center" style="background-color: white">1</td>
											<td class="td-border-light-right td-border-light-top td-border-light-bottom" width="17" align="center" style="background-color: white">0</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
			<!-- TABLE 4 -->
			<table style="background-color: #BFBFBF">
				<tr>
					<td colspan=3 class="td-border-thick-bottom td-border-thick-top" width="100%" height="15"><?php echo nbs(1)?><b>Part II<?php echo nbs(80)?>&#9656;<?php echo nbs(3)?>C o m p u t a t i o n<?php echo nbs(3)?>o f<?php echo nbs(3)?>T a x</b></td>
				</tr>
				<tr>
					<td width="270" align="center" height="15">Particulars</td>
					<td width="254" align="center">Amount of Compensation</td>
					<td width="254" align="center">Tax Due</td>
				</tr>
				<tr><td height="3"></td></tr><!-- separator -->
				<!-- ROW 15 -->
				<tr>
					<td><?php echo nbs(1)?><b>15</b><?php echo nbs(3)?>Total Amount of Compensation</td>
					<td>
						<table>
							<tr>
								<td width="30" align="center"><b>15</b></td>
								<td class="border-light td-right-middle" width="219" height="20" style="background-color: white"><?php echo number_format($part_2['col_15'], 2);?></td>
							</tr>
						</table>
					</td>
					<td></td>
				</tr>
				<tr><td height="3"></td></tr><!-- separator -->
				<!-- ROW 16A -->
				<tr>
					<td><?php echo nbs(1)?><b>16</b><?php echo nbs(7)?>Less: Non-Taxable Compensation<br><?php echo nbs(18)?><b>16A</b><?php echo nbs(3)?>Statutory Minimum Wage (MWEs)</td>
					<td>
						<table>
							<tr>
								<td width="30" align="center"><b>16A</b></td>
								<td class="border-light td-right-middle" width="219" height="20" style="background-color: white"></td>
							</tr>
						</table>
					</td>
					<td></td>
				</tr>
				<tr><td height="3"></td></tr><!-- separator -->
				<!-- ROW 16B -->
				<tr>
					<td><?php echo nbs(18)?><b>16B</b><?php echo nbs(3)?>Holiday Pay,Overtime Pay, Night Shift<br><?php echo nbs(28)?>Differential Pay, Hazard Pay<br><?php echo nbs(28)?>(Minimum Wage Earner)</td>
					<td>
						<table>
							<tr>
								<td width="30" align="center"><b>16B</b></td>
								<td class="border-light td-right-middle" width="219" height="20" style="background-color: white"></td>
							</tr>
						</table>
					</td>
					<td></td>
				</tr>
				<tr><td height="3"></td></tr><!-- separator -->
				<!-- ROW 16C -->
				<tr>
					<td><?php echo nbs(18)?><b>16C</b><?php echo nbs(3)?>Other Non-Taxable Compensation</td>
					<td>
						<table>
							<tr>
								<td width="30" align="center"><b>16C</b></td>
								<td class="border-light td-right-middle" width="219" height="20" style="background-color: white"><?php echo number_format($part_2['col_16c'], 2);?></td>
							</tr>
						</table>
					</td>
					<td></td>
				</tr>
				<tr><td height="3"></td></tr><!-- separator -->
				<!-- ROW 17 -->
				<tr>
					<td><?php echo nbs(1)?><b>17</b><?php echo nbs(3)?>Taxable Compensation</td>
					<td>
						<table>
							<tr>
								<td width="30" align="center"><b>17</b></td>
								<td class="border-light td-right-middle" width="219" height="20" style="background-color: white"><?php echo number_format($part_2['col_17'], 2);?></td>
							</tr>
						</table>
					</td>
					<td></td>
				</tr>
				<tr><td height="3"></td></tr><!-- separator -->
				<!-- ROW 18 -->
				<tr>
					<td><?php echo nbs(1)?><b>18</b><?php echo nbs(3)?>Tax Required to be Withheld</td>
					<td></td>
					<td>
						<table>
							<tr>
								<td width="30" align="center"><b>18</b></td>
								<td class="border-light td-right-middle" width="219" height="20" style="background-color: white"><?php echo number_format($part_2['col_20'], 2);?></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr><td height="3"></td></tr><!-- separator -->
				<!-- ROW 19 -->
				<tr>
					<td><?php echo nbs(1)?><b>19</b><?php echo nbs(3)?> Add/Less: Adjustment (from Item 26 of Section A)</td>
					<td></td>
					<td>
						<table>
							<tr>
								<td width="30" align="center"><b>19</b></td>
								<td class="border-light td-right-middle" width="219" height="20" style="background-color: white"></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr><td height="3"></td></tr><!-- separator -->
				<!-- ROW 20 -->
				<tr>
					<td><?php echo nbs(1)?><b>20</b><?php echo nbs(3)?>Tax Required to be Withheld for Remittance</td>
					<td></td>
					<td>
						<table>
							<tr>
								<td width="30" align="center"><b>20</b></td>
								<td class="border-light td-right-middle" width="219" height="20" style="background-color: white"><?php echo number_format($part_2['col_20'], 2);?></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr><td height="3"></td></tr><!-- separator -->
				<!-- ROW 21 -->
				<tr>
					<td><?php echo nbs(1)?><b>21</b><?php echo nbs(5)?>Less: Tax Remitted in Return Previously Filed,<br><?php echo nbs(20)?>if this is an amended return<br><?php echo nbs(15)?>Other Payments Made (please attach<br><?php echo nbs(20)?>proof of payment BIR Form No. 0605)</td>
					<td>
						<table>
							<tr>
								<td width="30" align="center"><b>21A</b></td>
								<td class="border-light td-right-middle" width="219" height="20" style="background-color: white"></td>
							</tr>
							<tr><td height="3"></td></tr><!-- separator -->
							<tr>
								<td width="30" align="center"><b>21B</b></td>
								<td class="border-light td-right-middle" width="219" height="20" style="background-color: white"><?php //echo number_format($part_2['col_21b'], 2);?></td>
							</tr>
						</table>
					</td>
					<td></td>
				</tr>
				<tr><td height="3"></td></tr><!-- separator -->
				<!-- ROW 22 -->
				<tr>
					<td colspan=2><?php echo nbs(1)?><b>22</b><?php echo nbs(3)?>Total Tax Payments Made (Sum of Item Nos. 21A & 21B)</td>
					<td>
						<table>
							<tr>
								<td width="30" align="center"><b>22</b></td>
								<td class="border-light td-right-middle" width="219" height="20" style="background-color: white"></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr><td height="3"></td></tr><!-- separator -->
				<!-- ROW 23 -->
				<tr>
					<td colspan=2><?php echo nbs(1)?><b>23</b><?php echo nbs(3)?>Tax Still Due/(Overremittance) (Item No. 20 less Item No. 22)	</td>
					<td>
						<table>
							<tr>
								<td width="30" align="center"><b>23</b></td>
								<td class="border-light td-right-middle" width="219" height="20" style="background-color: white"><?php echo number_format($part_2['col_20'], 2);?></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr><td height="3"></td></tr><!-- separator -->
				<!-- ROW 24 -->
				<tr>
					<td colspan=2><?php echo nbs(1)?><b>24</b><?php echo nbs(5)?> Add: Penalties</td>
				</tr>
				<tr>
					<td colspan=2>
						<table>
							<tr>
								<td colspan=2 align="center">Surcharge</td>
								<td colspan=2 align="center">Interest</td>
								<td colspan=2 align="right">Compromise</td>
							</tr>
							<tr>
								<td width="50" align="right"><b>24A</b><?php echo nbs(2)?></td>
								<td class="border-light td-right-middle" width="130" height="20" style="background-color: white"></td>
								<td width="30" align="right"><b>24B</b><?php echo nbs(2)?></td>
								<td class="border-light td-right-middle" width="130" style="background-color: white"></td>
								<td width="30" align="right"><b>24C</b><?php echo nbs(2)?></td>
								<td class="border-light td-right-middle" width="130" style="background-color: white"></td>
							</tr>
						</table>
					</td>
					<td>
						<table>
							<tr>
								<td width="30" align="center"><b>24D</b></td>
								<td class="border-light" width="219" height="20" style="background-color: white"></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr><td height="3"></td></tr><!-- separator -->
				<!-- ROW 25 -->
				<tr>
					<td colspan=2><?php echo nbs(1)?><b>25</b><?php echo nbs(3)?>Total Amount Still Due/(Overremittance)</td>
					<td>
						<table>
							<tr>
								<td width="30" align="center"><b>25</b></td>
								<td class="border-light td-right-middle" width="219" height="20" style="background-color: white"><?php echo number_format($part_2['col_20'], 2);?></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr><td height="3"></td></tr><!-- separator -->
			</table>
			<!-- TABLE 5 -->
			<table style="background-color: #BFBFBF">
				<tr>
					<td colspan=4 class="td-border-thick-bottom td-border-thick-top" width="100%" height="15"><?php echo nbs(1)?><b>Section A<?php echo nbs(50)?>Adjustment of Taxes Withheld on Compensation For Previous Months</b></td>
				</tr>
				<tr>
					<td class="td-border-right td-border-bottom" width="196" align="center">Previous Month(s)<br>(1)<br>(MM/YYYY)</td>
					<td class="td-border-right td-border-bottom" width="196" align="center">Date Paid<br>(2)<br>(MM/DD/YYYY)</td>
					<td class="td-border-right td-border-bottom" width="196" align="center">Bank Validation/<br>ROR No.<br>(3)</td>
					<td class="td-border-bottom" width="195" align="center">Bank Code(s)<br>(4)</td>
				</tr>
				<tr>
					<td class="td-border-right td-border-bottom" height="15" style="background-color: white">
						<table>
							<tr>
								<td class="td-border-right" width="70" height="15"></td>
								<td></td>
							</tr>
						</table>
					</td>
					<td class="td-border-right td-border-bottom" style="background-color: white">
						<table>
							<tr>
								<td class="td-border-right" width="50" height="15"></td>
								<td class="td-border-right" width="50" height="15"></td>
								<td></td>
							</tr>
						</table>
					</td>
					<td class="td-border-right td-border-bottom" style="background-color: white"></td>
					<td class="td-border-bottom" style="background-color: white"></td>					
				</tr>
				<tr>
					<td class="td-border-right td-border-bottom" height="15" style="background-color: white">
						<table>
							<tr>
								<td class="td-border-right" width="70" height="15"></td>
								<td></td>
							</tr>
						</table>
					</td>
					<td class="td-border-right td-border-bottom" style="background-color: white">
						<table>
							<tr>
								<td class="td-border-right" width="50" height="15"></td>
								<td class="td-border-right" width="50" height="15"></td>
								<td></td>
							</tr>
						</table>
					</td>
					<td class="td-border-right td-border-bottom" style="background-color: white"></td>
					<td class="td-border-bottom" style="background-color: white"></td>					
				</tr>
				<tr>
					<td class="td-border-right td-border-bottom" height="15" style="background-color: white">
						<table>
							<tr>
								<td class="td-border-right" width="70" height="15"></td>
								<td></td>
							</tr>
						</table>
					</td>
					<td class="td-border-right td-border-bottom" style="background-color: white">
						<table>
							<tr>
								<td class="td-border-right" width="50" height="15"></td>
								<td class="td-border-right" width="50" height="15"></td>
								<td></td>
							</tr>
						</table>
					</td>
					<td class="td-border-right td-border-bottom" style="background-color: white"></td>
					<td class="td-border-bottom" style="background-color: white"></td>					
				</tr>
			</table>
			<!-- TABLE 6 -->
			<table style="background-color: #BFBFBF">
				<tr>
					<td colspan=4 class="td-border-thick-bottom td-border-thick-top" width="100%" height="15"><?php echo nbs(1)?><b>Section A(continuation)</b></td>
				</tr>
				<tr>
					<td class="td-border-right" width="191" align="center">Tax Paid (Excluding Penalties)</td>
					<td class="td-border-right" width="191" align="center">Should Be Tax Due</td>
					<td colspan=2 class="td-border-bottom" align="center">Adjustment (7)</td>
				</tr>
				<tr>
					<td class="td-border-right td-border-bottom" width="196" align="center">for the Month<br>(5)</td>
					<td class="td-border-right td-border-bottom" width="196" align="center">for the Month<br>(6)</td>
					<td class="td-border-right td-border-bottom" width="196" align="center">From Current Year(7a)</td>
					<td class="td-border-bottom" width="195" align="center">From Year - End Adjustment of the <br>Immediately Preceeding Year (7b)</td>
				</tr>
				<tr>
					<td class="td-border-right td-border-bottom" height="15" style="background-color: white"></td>
					<td class="td-border-right td-border-bottom" style="background-color: white"></td>
					<td class="td-border-right td-border-bottom" style="background-color: white"></td>
					<td class="td-border-bottom" style="background-color: white"></td>
				</tr>
				<tr>
					<td class="td-border-right td-border-bottom" height="15" style="background-color: white"></td>
					<td class="td-border-right td-border-bottom" style="background-color: white"></td>
					<td class="td-border-right td-border-bottom" style="background-color: white"></td>
					<td class="td-border-bottom" style="background-color: white"></td>
				</tr>
				<tr>
					<td class="td-border-right td-border-bottom" height="15" style="background-color: white"></td>
					<td class="td-border-right td-border-bottom" style="background-color: white"></td>
					<td class="td-border-right td-border-bottom" style="background-color: white"></td>
					<td class="td-border-bottom" style="background-color: white"></td>
				</tr>
			</table>
			<!-- TABLE 7 -->
			<table>
				<tr>
					<td class="td-border-thick-bottom td-border-right" width="450" height="15" style="background-color: #BFBFBF"><?php echo nbs(1)?><b>26</b><?php echo nbs(3)?>Total (7a plus 7b) (To Item 19)</td>
					<td class="td-border-thick-bottom td-border-right" width="220"></td>
					<td class="td-border-thick-bottom" width="93" style="background-color: #BFBFBF"></td>
				</tr>
				<tr>
					<td colspan=3><?php echo nbs(5)?>We declare, under the penalties of perjury, that this return has been made in good faith, verified by us, and to the best of our knowledge and belief,<br>is true and correct, pursuant to the provisions of the National Internal Revenue Code, as amended, and the regulations issued under authority thereof.</td>
				</tr>
				<tr>
					<td>
						<table>
							<tr>
								<td width="60" align="right" height="17"><b>27<?php echo nbs(3)?></b></td>
								<td class="td-border-bottom" width="300"></td>
								<td></td>
							</tr>
							<tr>
								<td></td>
								<td class="f-size-9" align="center">President/Vice President/Principal Officer/Accredited Tax Agent/<br>Authorized Representative / Taxpayer<br>(Signature Over Printed Name)</td>
								<td></td>
							</tr>
							<tr>
								<td></td>
								<td>
									<table>
										<tr>
											<td class="td-border-bottom" width="120" align="center" height="17"></td>
											<td width="60"></td>
											<td class="td-border-bottom" width="120" align="center"></td>
										</tr>
										<tr>
											<td class="f-size-9" align="center">Title/Position of Signatory</td>
											<td></td>
											<td class="f-size-9" align="center">TIN of Signatory</td>
										</tr>
									</table>
								</td>
								<td></td>
							</tr>
							<tr>
								<td colspan=3>
									<table>
										<tr>
											<td width="30" height="17"></td>
											<td class="td-border-bottom" width="150" align="center"></td>
											<td width="30"></td>
											<td class="td-border-bottom" width="80" align="center"></td>
											<td width="30"></td>
											<td class="td-border-bottom" width="80" align="center"></td>
										</tr>
										<tr>
											<td></td>
											<td class="f-size" align="center">Tax Agent Acc. No./Atty's Roll No.(if applicable)</td>
											<td></td>
											<td class="f-size" align="center">Date of Issuance</td>
											<td></td>
											<td class="f-size" align="center">Date of Expiry</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
					<td>
						<table>
							<tr>
								<td width="20" height="17"><b>28</b><?php echo nbs(3)?></td>
								<td class="td-border-bottom" width="180" align="center"></td>
							</tr>
							<tr>
								<td></td>
								<td class="f-size-9" align="center">Treasurer/Assistant Treasurer<br>(Signature Over Printed Name)</td>
							</tr>
							<tr>
								<td width="20" height="17"></td>
								<td class="td-border-bottom" width="180" align="center"></td>
							</tr>
							<tr>
								<td></td>
								<td class="f-size-9" align="center">Title/Position of Signatory</td>
							</tr>
							<tr>
								<td width="20" height="17"></td>
								<td class="td-border-bottom" width="180" align="center"></td>
							</tr>
							<tr>
								<td></td>
								<td class="f-size-9" align="center">TIN of Signatory</td>
							</tr>
						</table>
					</td>
					<td></td>
				</tr>
			</table>
			<!-- TABLE 8 -->
			<table style="background-color: #BFBFBF">
				<tr>
					<td class="td-border-bottom td-border-thick-top td-border-thick-right" width="630" height="15"><?php echo nbs(1)?><b>Part III<?php echo nbs(70)?>D e t a i l s<?php echo nbs(3)?>o f<?php echo nbs(3)?>P a y m e n t</b></td>
					<td class="td-border-thick-top" width="133" align="center" style="background-color: white">Stamp of</td>
				</tr>
				<tr>
					<td>
						<table>
							<tr>
								<td class="td-border-right td-border-bottom" width="100" align="center" valign=bottom><b>Particulars</b></td>
								<td class="td-border-right td-border-bottom" width="100" align="center" valign=bottom><b> Drawee Bank/<br>Agency</b></td>
								<td class="td-border-right td-border-bottom" width="110" align="center" valign=bottom><b>Number</b></td>
								<td class="td-border-right td-border-bottom" width="130" align="center" valign=bottom><b>Date</b>
								<table>
									<tr>
										<td width="10"></td>
										<td class="td-border-right td-border-left td-border-top" width="30" height="15" align="center"><b>MM</b></td>
										<td class="td-border-right td-border-left td-border-top" width="30" align="center"><b>DD</b></td>
										<td class="td-border-left td-border-top" width="60" align="center"><b>YYYY</b></td>
									</tr>
								</table>
								</td>
								<td class="td-border-bottom" width="208" align="center" valign=bottom><b>Amount</b></td>
							</tr>
						</table>
					</td>
					<td class="td-border-thick-left" align="center" style="background-color: white">Receiving Office/AAB<br>and</td>
				</tr>
				<!-- ROW 29 -->
				<tr>
					<td height="17">
						<table>
							<tr><td height="3"></td></tr>
							<tr>
								<td width="90"><?php echo nbs(1)?><b>29</b><?php echo nbs(1)?>Cash/Bank<br><?php echo nbs(6)?>Debit Memo</td>
								<td align="center"><b>29A</b><br>&#9656;</td>
								<td class="td-border-right td-border-left td-border-top td-border-bottom" width="80" style="background-color: white"></td>
								<td align="center"><b>29B</b><br>&#9656;</td>
								<td class="td-border-right td-border-left td-border-top td-border-bottom" width="90" style="background-color: white"></td>
								<td align="center"><b>29C</b><br>&#9656;</td>
								<td class="td-border-left td-border-top td-border-bottom" width="14" style="background-color: white"></td>
								<td class="td-border-top td-border-bottom" width="14" style="background-color: white"></td>
								<td class="td-border-left td-border-top td-border-bottom" width="14" style="background-color: white"></td>
								<td class="td-border-top td-border-bottom" width="14" style="background-color: white"></td>
								<td class="td-border-left td-border-top td-border-bottom" width="14" style="background-color: white"></td>
								<td class="td-border-top td-border-bottom" width="14" style="background-color: white"></td>
								<td class="td-border-top td-border-bottom" width="14" style="background-color: white"></td>
								<td class="td-border-right td-border-top td-border-bottom" width="14" style="background-color: white"></td>
								<td align="center"><b>29D</b><br>&#9656;</td>
								<td class="td-border-right td-border-left td-border-top td-border-bottom" width="170" style="background-color: white"></td>
							</tr>
						</table>
					</td>
					<td class="td-border-thick-left" align="center" valign=top style="background-color: white">Date of Receipt<br>(RO's Signature/</td>
				</tr>
				<!-- ROW 30 -->
				<tr>
					<td>
						<table>
							<tr><td height="3"></td></tr>
							<tr>
								<td width="90"><?php echo nbs(1)?><b>30</b><?php echo nbs(1)?>Check</td>
								<td align="center"><b>30A</b><br>&#9656;</td>
								<td class="td-border-right td-border-left td-border-top td-border-bottom" width="80" style="background-color: white"></td>
								<td align="center"><b>30B</b><br>&#9656;</td>
								<td class="td-border-right td-border-left td-border-top td-border-bottom" width="90" style="background-color: white"></td>
								<td align="center"><b>30C</b><br>&#9656;</td>
								<td class="td-border-left td-border-top td-border-bottom" width="14" style="background-color: white"></td>
								<td class="td-border-top td-border-bottom" width="14" style="background-color: white"></td>
								<td class="td-border-left td-border-top td-border-bottom" width="14" style="background-color: white"></td>
								<td class="td-border-top td-border-bottom" width="14" style="background-color: white"></td>
								<td class="td-border-left td-border-top td-border-bottom" width="14" style="background-color: white"></td>
								<td class="td-border-top td-border-bottom" width="14" style="background-color: white"></td>
								<td class="td-border-top td-border-bottom" width="14" style="background-color: white"></td>
								<td class="td-border-right td-border-top td-border-bottom" width="14" style="background-color: white"></td>
								<td align="center"><b>30D</b><br>&#9656;</td>
								<td class="td-border-right td-border-left td-border-top td-border-bottom" width="170" style="background-color: white"></td>
							</tr>
						</table>
					</td>
					<td class="td-border-thick-left" align="center" valign=top style="background-color: white">Bank Teller's Initial)</td>
				</tr>
				<!-- ROW 31 -->
				<tr>
					<td>
						<table>
							<tr><td height="3"></td></tr>
							<tr>
								<td width="90"><?php echo nbs(1)?><b>31</b><?php echo nbs(1)?>Others</td>
								<td align="center"><b>31A</b><br>&#9656;</td>
								<td class="td-border-right td-border-left td-border-top td-border-bottom" width="80" style="background-color: white"></td>
								<td align="center"><b>31B</b><br>&#9656;</td>
								<td class="td-border-right td-border-left td-border-top td-border-bottom" width="90" style="background-color: white"></td>
								<td align="center"><b>31C</b><br>&#9656;</td>
								<td class="td-border-left td-border-top td-border-bottom" width="14" style="background-color: white"></td>
								<td class="td-border-top td-border-bottom" width="14" style="background-color: white"></td>
								<td class="td-border-left td-border-top td-border-bottom" width="14" style="background-color: white"></td>
								<td class="td-border-top td-border-bottom" width="14" style="background-color: white"></td>
								<td class="td-border-left td-border-top td-border-bottom" width="14" style="background-color: white"></td>
								<td class="td-border-top td-border-bottom" width="14" style="background-color: white"></td>
								<td class="td-border-top td-border-bottom" width="14" style="background-color: white"></td>
								<td class="td-border-right td-border-top td-border-bottom" width="14" style="background-color: white"></td>
								<td align="center"><b>31D</b><br>&#9656;</td>
								<td class="td-border-right td-border-left td-border-top td-border-bottom" width="170" style="background-color: white"></td>
							</tr>
							<tr><td height="3"></td></tr>
						</table>
					</td>
					<td class="td-border-thick-left" align="center" valign=top style="background-color: white"></td>
				</tr>
				<tr>
					<td class="td-border-thick-top" colspan=2 height="35" style="background-color: white" valign=top>Machine Validation/Revenue Official Receipt Details (If not filed with the bank)</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<!-- ************************************************************************** -->
</body>

</html>
