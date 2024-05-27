<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<html>
<?php if(ISSET($header['employee_name'])):?>
<head>
  
  <title>Certificate of Compensation Payment/Tax Withheld</title>
  <link rel="stylesheet" href="<?php echo base_url().PATH_CSS ?>reports.css" type="text/css" />
</head>

<body>
<table class="table-max">
    <tr>
        <td class="f-size-8 border-thick">        	
            <!-- TABLE 1 -->
           	<table>
				<tr>
					<td class="f-size-8" width="220" style="padding: 0px !important;">
						<table>
							<tr>
								<td class="f-size-8" width="60" align="center"><img src="<?php echo base_url().PATH_IMG ?>bir_logo.png" width=40 height=40></img></td>
								<td class="f-size-8">Republika ng Pilipinas<br>Kagawaran ng Pananalapi<span class="f-size-9"><br>Kawanihan ng Rentas Internas</span></td>
							</tr>
						</table>
					</td>
					<td class="f-size-8 f-size-16" width="330" align="center" valign="middle">Certificate of Compensation<br>Payment/Tax Withheld</td>
					<td class="f-size-8" width="50"></td>
					<td class="f-size-8">BIR Form No.<br><span class="f-size-24"><b>2316</b></span></td>			
				</tr>
				<tr>
					<td colspan=3 class="f-size-8" valign=top><?php echo nbs(5)?>For Compensation Payment With or Without Tax Withheld</td>
					<td class="f-size-8" valign=top>July  2008 (ENCS)</td>
				</tr>
			</table>		

      		<!-- TABLE 2 -->
			<table class="table-max" style="background-color: #BFBFBF">
				<tr>
					<td colspan=2 class="f-size-8 td-border-thick-bottom td-border-thick-top bg-white" height="15"><?php echo nbs(3)?>Fill in all applicable spaces. Mark all appropriate boxes with an “X”.</td>
				</tr>
				<tr>
					<td class="f-size-8 td-border-thick-right" width="350">
						<table>
							<tr>
								<td class="f-size-8" width="20" height="10" valign=top><?php echo nbs(1)?><b>1</b></td>
								<td class="f-size-8" width="90">For the Year<br><?php echo nbs(5)?>(YYYY)</td>	
								<td class="f-size-8" width="20" align="center" valign=middle>&#9656;</td>
								<td class="f-size-8">
									<table>
										<tr><td height="1"></td></tr>
										<tr>
											<td class="f-size-8 td-border-light-left td-border-light-top td-border-light-bottom td-center-middle bg-white" width="15" height="15"><?php echo $header['year'][0];?></td>
											<td class="f-size-8 td-border-light-left td-border-light-top td-border-light-bottom td-center-middle bg-white" width="15"><?php echo $header['year'][1];?></td>
											<td class="f-size-8 td-border-light-left td-border-light-top td-border-light-bottom td-center-middle bg-white" width="15"><?php echo $header['year'][2];?></td>
											<td class="f-size-8 border-light td-center-middle bg-white" width="15"><?php echo$header['year'][3];?></td>
										</tr>
										<tr><td height="1"></td></tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
					<td class="f-size-8" width="350">
						<table>
							<tr>
								<td class="f-size-8" width="20" valign=top><?php echo nbs(1)?><b>2</b></td>
								<td class="f-size-8" width="105">For the Period<br>&#9656;<?php echo nbs(3)?>From<?php echo nbs(5)?>(MM/DD)</td>
								<td class="f-size-8">
									<table>
										<tr>
											<td class="f-size-8 td-border-light-left td-border-light-top td-border-light-bottom td-center-middle bg-white" width="15" height="15"><?php echo $period_from[0];?></td>
											<td class="f-size-8 td-border-light-left td-border-light-top td-border-light-bottom td-center-middle bg-white" width="15"><?php echo $period_from[1];?></td>
											<td class="f-size-8 td-border-light-left td-border-light-top td-border-light-bottom td-center-middle bg-white" width="15"><?php echo $period_from[2];?></td>
											<td class="f-size-8 border-light td-center-middle bg-white" width="17"><?php echo $period_from[3];?></td>
										</tr>
									</table>
								</td>
								<td class="f-size-8" width="90" align="center" valign=bottom>To (MM/DD)</td>
								<td class="f-size-8">
									<table>
										<tr>
											<td class="f-size-8 td-border-light-left td-border-light-top td-border-light-bottom td-center-middle bg-white" width="15" height="15"><?php echo $period_to[0];?></td>
											<td class="f-size-8 td-border-light-left td-border-light-top td-border-light-bottom td-center-middle bg-white" width="15"><?php echo $period_to[1];?></td>
											<td class="f-size-8 td-border-light-left td-border-light-top td-border-light-bottom td-center-middle bg-white" width="15"><?php echo $period_to[2];?></td>
											<td class="f-size-8 border-light td-center-middle bg-white" width="15"><?php echo $period_to[3];?></td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>

			<!-- TABLE 3 -->
			<table class="table-max" style="background-color: #BFBFBF">
				<tr>
					<td class="f-size-8 td-border-thick-top td-border-thick-right td-border-thick-bottom bg-white" width="350" height="14"><b>Part I<?php echo nbs(20)?>Employee Information</b></td>
					<td class="f-size-8 td-border-thick-top td-border-thick-bottom bg-white" width="350" height="15"><b>Part IV-B<?php echo nbs(5)?><span class="f-size-7">Details of Compensation Income and Tax Withheld from Present Employer</span></b></td>
				</tr>
				<tr>
					<td class="f-size-8 td-border-thick-right">
						<table>
							<tr>
								<td class="f-size-8" width="20" height="15" valign=top><?php echo nbs(1)?><b>3</b></td>
								<td class="f-size-8" width="90">Taxpayer<br>Identification No.</td>	
								<td class="f-size-8" width="20" align="center" valign=middle>&#9656;</td>
								<td class="f-size-8">
									<table>
										<tr><td height="1"></td></tr>
										<tr>
											<td class="f-size-8 td-border-light-left td-border-light-top td-border-light-bottom td-center-middle bg-white" width="13" height="15"><?php echo $tin[0]?></td>
											<td class="f-size-8 td-border-light-left td-border-light-top td-border-light-bottom td-center-middle bg-white" width="13"><?php echo $tin[1]?></td>
											<td class="f-size-8 td-border-light-left td-border-light-top td-border-light-bottom td-center-middle bg-white" width="13"><?php echo $tin[2]?></td>
											<td class="f-size-8 td-border-light-left td-border-light-top td-border-light-bottom" width="15"></td>
											<td class="f-size-8 td-border-light-left td-border-light-top td-border-light-bottom td-center-middle bg-white" width="13"><?php echo $tin[3]?></td>
											<td class="f-size-8 td-border-light-left td-border-light-top td-border-light-bottom td-center-middle bg-white" width="13"><?php echo $tin[4]?></td>
											<td class="f-size-8 td-border-light-left td-border-light-top td-border-light-bottom td-center-middle bg-white" width="13"><?php echo $tin[5]?></td>
											<td class="f-size-8 td-border-light-left td-border-light-top td-border-light-bottom" width="15"></td>
											<td class="f-size-8 td-border-light-left td-border-light-top td-border-light-bottom td-center-middle bg-white" width="13"><?php echo $tin[6]?></td>
											<td class="f-size-8 td-border-light-left td-border-light-top td-border-light-bottom td-center-middle bg-white" width="13"><?php echo $tin[7]?></td>
											<td class="f-size-8 td-border-light-left td-border-light-top td-border-light-bottom td-center-middle bg-white" width="13"><?php echo $tin[8]?></td>
											<td class="f-size-8 td-border-light-left td-border-light-top td-border-light-bottom" width="15"></td>
											<td class="f-size-8 td-border-light-left td-border-light-top td-border-light-bottom td-center-middle bg-white" width="13"><?php echo $tin[9]?></td>
											<td class="f-size-8 td-border-light-left td-border-light-top td-border-light-bottom td-center-middle bg-white" width="13"><?php echo $tin[10]?></td>
											<td class="f-size-8 td-border-light-left td-border-light-top td-border-light-bottom td-center-middle bg-white" width="13"><?php echo $tin[11]?></td>
											<td class="f-size-8 border-light bg-white" width="13"></td>
										</tr>
										<tr><td height="1"></td></tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
					<td class="f-size-8"><?php echo nbs(75)?>Amount<br><?php echo nbs(1)?><b>A.<?php echo nbs(3)?>NON-TAXABLE/EXEMPT COMPENSATION INCOME</b></td>
				</tr>
			</table>

			<!-- TABLE 4 -->
			<table class="table-max" style="background-color: #BFBFBF">
				<tr>
					<!-- MAIN COLUMN 1 -->
					<td  class="td-border-thick-right" width="350">
						<table>
							<!-- ROW 4 -->
							<tr>
								<td class="f-size-8">
									<table>
										<tr>
											<td class="f-size-8 td-border-top" width="15" valign=top><?php echo nbs(1)?><b>4</b></td>
											<td class="f-size-8 td-border-top" width="">Employee's Name (Last Name, First Name, Middle Name)</td>	
											<td class="f-size-8 td-border-top" width=""><b>5</b><?php echo nbs(2)?>RDO Code</td>
										</tr>
										<tr>
											<td class="f-size-8"><?php echo nbs(2)?>&#9656;</td>
											<td class="f-size-8 border-light bg-white" width="270" height="15"><?php echo $header['employee_name'];?></td>
											<td class="f-size-8">
												<table>
													<tr>	
														<td class="f-size-8" width="8"></td>							
														<td class="f-size-8 td-border-light-left td-border-light-top td-border-light-bottom td-center-middle bg-white" width="17" height="15"><?php echo$header['rdo_code'][0];?></td>
														<td class="f-size-8 td-border-light-left td-border-light-top td-border-light-bottom td-center-middle bg-white" width="17"><?php echo$header['rdo_code'][1];?></td>
														<td class="f-size-8 border-light td-center-middle bg-white" width="17"><?php echo $header['rdo_code'][2];?></td>
														<td class="f-size-8" width="4"></td>
													</tr>
												</table>
											</td>
										</tr>
										<tr><td height="1"></td></tr>
									</table>
								</td>
							</tr>
							<!-- ROW 6 -->
							<tr>
								<td class="f-size-8">
									<table>
										<tr>
											<td class="f-size-8 td-border-top" width="15" valign=top><?php echo nbs(1)?><b>6</b></td>
											<td class="f-size-8 td-border-top" width=""> Registered Address</td>	
											<td class="f-size-8 td-border-top" width=""><b>6A</b><?php echo nbs(2)?>Zip Code</td>
										</tr>
										<tr>
											<td class="f-size-8"><?php echo nbs(2)?>&#9656;</td>
											<td class="f-size-8 border-light bg-white" width="270" height="15"><?php echo $header['registered_address'];?></td>
											<td class="f-size-8">
												<table>
													<tr>	
														<td class="f-size-8" width="8"></td>							
														<td class="f-size-8 td-border-light-left td-border-light-top td-border-light-bottom td-center-middle bg-white" width="13" height="15"><?php echo $header['registered_addr_zip_code'][0];?></td>
														<td class="f-size-8 td-border-light-left td-border-light-top td-border-light-bottom td-center-middle bg-white" width="13"><?php echo $header['registered_addr_zip_code'][1];?></td>
														<td class="f-size-8 td-border-light-left td-border-light-top td-border-light-bottom td-center-middle bg-white" width="13"><?php echo $header['registered_addr_zip_code'][2];?></td>
														<td class="f-size-8 border-light bg-white" width="13"><?php echo $header['registered_addr_zip_code'][3];?></td>
														<td class="f-size-8" width="4"></td>
													</tr>
												</table>
											</td>
										</tr>
									</table>
								</td>
							</tr>
							<!-- ROW 6B -->
							<tr>
								<td class="f-size-8">
									<table>
										<tr>
											<td class="f-size-8" colspan=2 width="15" height="15" valign=top><?php echo nbs(1)?><b>6B</b><?php echo nbs(1)?>Local Home Address</td>
											<td class="f-size-8" width=""><b>6C</b><?php echo nbs(2)?>Zip Code</td>
										</tr>
										<tr>
											<td class="f-size-8"><?php echo nbs(2)?>&#9656;<?php echo nbs(1)?></td>
											<td class="f-size-8 border-light bg-white" width="270" height="15"><?php echo $header['local_address'];?></td>
											<td class="f-size-8">
												<table>
													<tr>	
														<td class="f-size-8" width="8"></td>							
														<td class="f-size-8 td-border-light-left td-border-light-top td-border-light-bottom td-center-middle bg-white" width="13" height="15"><?php echo $header['local_addr_zip_code'][0];?></td>
														<td class="f-size-8 td-border-light-left td-border-light-top td-border-light-bottom td-center-middle bg-white" width="13"><?php echo $header['local_addr_zip_code'][1];?></td>
														<td class="f-size-8 td-border-light-left td-border-light-top td-border-light-bottom td-center-middle bg-white" width="13"><?php echo $header['local_addr_zip_code'][2];?></td>
														<td class="f-size-8 border-light td-center-middle bg-white" width="13"><?php echo $header['local_addr_zip_code'][3];?></td>
														<td class="f-size-8" width="4"></td>
													</tr>
												</table>
											</td>
										</tr>
									</table>
								</td>
							</tr>
							<!-- ROW 6D -->
							<tr>
								<td class="f-size-8">
									<table>
										<tr>
											<td class="f-size-8" colspan=2 width="15" height="15" valign=top><?php echo nbs(1)?><b>6D</b><?php echo nbs(1)?>Foreign Home Address</td>
											<td class="f-size-8" width=""><b>6E</b><?php echo nbs(2)?>Zip Code</td>
										</tr>
										<tr>
											<td class="f-size-8"><?php echo nbs(2)?>&#9656;<?php echo nbs(1)?></td>
											<td class="f-size-8 border-light bg-white" width="270" height="15"><?php echo $header['foreign_address'];?></td>
											<td class="f-size-8">
												<table>
													<tr>	
														<td class="f-size-8" width="8"></td>							
														<td class="f-size-8 td-border-light-left td-border-light-top td-border-light-bottom td-center-middle bg-white" width="13" height="15"><?php echo $header['foreign_addr_zip_code'][0];?></td>
														<td class="f-size-8 td-border-light-left td-border-light-top td-border-light-bottom td-center-middle bg-white" width="13"><?php echo $header['foreign_addr_zip_code'][1];?></td>
														<td class="f-size-8 td-border-light-left td-border-light-top td-border-light-bottom td-center-middle bg-white" width="13"><?php echo $header['foreign_addr_zip_code'][2];?></td>
														<td class="f-size-8 border-light bg-white" width="13"><?php echo $header['foreign_addr_zip_code'][3];?></td>
														<td class="f-size-8" width="4"></td>
													</tr>
												</table>
											</td>
										</tr>
										<tr><td height="1"></td></tr>
									</table>
								</td>
							</tr>	
							<!-- ROW 7 -->
							<tr>
								<td class="f-size-8">
									<table>
										<tr>
											<td class="f-size-8 td-border-right td-border-top" width="175">
												<table>
													<tr>		
														<td class="f-size-8" width="15" valign=top><?php echo nbs(1)?><b>7</b></td>
														<td class="f-size-8">Date of Birth (MM/DD/YYYY)</td>	
													</tr>
													<tr>
														<td class="f-size-8" width="15"></td>
														<td class="f-size-8">
															<table>
																<tr>
																	<td class="f-size-8 td-border-light-left td-border-light-top td-border-light-bottom td-center-middle bg-white" width="14" height="13"><?php echo $header['birth_date'][0];?></td>
																	<td class="f-size-8 td-border-light-top td-border-light-bottom td-center-middle bg-white" width="14"><?php echo $header['birth_date'][1];?></td>
																	<td class="f-size-8 td-border-light-left td-border-light-top td-border-light-bottom td-center-middle bg-white" width="14"><?php echo $header['birth_date'][2];?></td>
																	<td class="f-size-8 td-border-light-top td-border-light-bottom td-center-middle bg-white" width="14"><?php echo $header['birth_date'][3];?></td>
																	<td class="f-size-8 td-border-light-left td-border-light-top td-border-light-bottom td-center-middle bg-white" width="14"><?php echo $header['birth_date'][4];?></td>
																	<td class="f-size-8 td-border-light-top td-border-light-bottom td-center-middle bg-white" width="14"><?php echo $header['birth_date'][5];?></td>
																	<td class="f-size-8 td-border-light-top td-border-light-bottom td-center-middle bg-white" width="14"><?php echo $header['birth_date'][6];?></td>
																	<td class="f-size-8 td-border-light-right td-border-light-top td-center-middle td-border-light-bottom bg-white" width="14"><?php echo $header['birth_date'][7];?></td>
																</tr>
															</table>
														</td>
													</tr>
												</table>
											</td>
											<td class="f-size-8 td-border-top" width="174">
												<table>
													<tr>		
														<td class="f-size-8" width="15" valign=top><?php echo nbs(1)?><b>8</b></td>
														<td class="f-size-8">Telephone Number</td>	
													</tr>
													<tr>
														<td class="f-size-8" width="15"></td>
														<td class="f-size-8 border-light bg-white" width="153" height="13"><?php echo $header['telephone_num'];?></td>
													</tr>
												</table>
											</td>
										</tr>
										<tr><td class="f-size-8 td-border-right td-border-bottom" height="1"></td><td class="f-size-8 td-border-bottom" height="1"></td></tr>
									</table>
								</td>
							</tr>
							<!-- ROW 9 -->
							<tr>
								<td class="f-size-8" valign=top width="348"><?php echo nbs(1)?><b>9</b><?php echo nbs(3)?>Exemption Status</td>
							</tr>
							<tr>
								<td class="f-size-8">
									<table>
										<tr>
										<?php 
											if($header['exemption_status'] == 'Y'):
												$ex_y = 'X';
											else:
												$ex_n = 'X';
											endif;
										?>
											<td class="f-size-8" width="75"></td>
											<td class="f-size-8 border-light td-center-middle bg-white" width="25" height="12"><?php echo $ex_y;?></td>
											<td class="f-size-8" width="60"><?php echo nbs(3)?>Yes</td>
											<td class="f-size-8 border-light td-center-middle bg-white" width="25" height="12"><?php echo $ex_n;?></td>
											<td class="f-size-8" width="50"><?php echo nbs(3)?>No</td>
										</tr>
									</table>
								</td>
							</tr>
							<!-- ROW 9A -->
							<tr>
								<td class="f-size-8" valign=top width="348"><?php echo nbs(1)?><b>9A</b><?php echo nbs(2)?><span>Is the wife claiming the additional exemption for qualified dependent children?</span></td>
							</tr>
							<tr>
								<td class="f-size-8">
									<table>
										<tr>
										<?php 
											if($header['wife_claim_exemption'] == 'Y'):
												$wce_y = 'X';
											else:
												$wce_n = 'X';
											endif;
										?>
											<td class="f-size-8" width="75"></td>
											<td class="f-size-8 border-light td-center-middle bg-white" width="25" height="12"><?php echo $wce_y;?></td>
											<td class="f-size-8" width="60"><?php echo nbs(3)?>Yes</td>
											<td class="f-size-8 border-light td-center-middle bg-white" width="25" height="12"><?php echo $wce_n;?></td>
											<td class="f-size-8" width="50"><?php echo nbs(3)?>No</td>
										</tr>
										<tr><td height="1"></td></tr>
									</table>
								</td>
							</tr>
							<!-- ROW 10 -->
							<tr>
								<td class="f-size-8 td-border-top" height="15"><?php echo nbs(1)?><b>10</b><?php echo nbs(2)?>Name of Qualified Dependent Children<?php echo nbs(3)?><b>11</b><?php echo nbs(2)?>Date of Birth (MM/DD/YYYY)</td>
							</tr>
							<tr>
								<td class="f-size-8">
									<table>
										<tr>
											<td class="f-size-8" width="15"></td>
											<td class="f-size-8 border-light bg-white" width="193"><?php echo $dependents[0]['dependent_name'];?></td>
											<td class="f-size-8" width="15"></td>
											<td class="f-size-8 td-border-light-left td-border-light-top td-border-light-bottom td-center-middle bg-white" width="15" height="13"><?php echo $dependents[0]['birth_date'][0];?></td>
											<td class="f-size-8 td-border-light-top td-border-light-bottom td-center-middle bg-white" width="15"><?php echo $dependents[0]['birth_date'][1];?></td>
											<td class="f-size-8 td-border-light-left td-border-light-top td-border-light-bottom td-center-middle bg-white" width="15"><?php echo $dependents[0]['birth_date'][2];?></td>
											<td class="f-size-8 td-border-light-top td-border-light-bottom td-center-middle bg-white" width="15"><?php echo $dependents[0]['birth_date'][3];?></td>
											<td class="f-size-8 td-border-light-left td-border-light-top td-border-light-bottom td-center-middle bg-white" width="15"><?php echo $dependents[0]['birth_date'][4];?></td>
											<td class="f-size-8 td-border-light-top td-border-light-bottom td-center-middle bg-white" width="15"><?php echo $dependents[0]['birth_date'][5];?></td>
											<td class="f-size-8 td-border-light-top td-border-light-bottom td-center-middle bg-white" width="15"><?php echo $dependents[0]['birth_date'][6];?></td>
											<td class="f-size-8 td-border-light-right td-border-light-top td-border-light-bottom td-center-middle bg-white" width="15"><?php echo $dependents[0]['birth_date'][7];?></td>
										</tr>
										<tr>
											<td class="f-size-8" width="15"></td>
											<td class="f-size-8 border-light bg-white" width="193"><?php echo $dependents[1]['dependent_name'];?></td>
											<td class="f-size-8" width="15"></td>
											<td class="f-size-8 td-border-light-left td-border-light-top td-border-light-bottom td-center-middle bg-white" width="15" height="13"><?php echo $dependents[1]['birth_date'][0];?></td>
											<td class="f-size-8 td-border-light-top td-border-light-bottom td-center-middle bg-white" width="15"><?php echo $dependents[1]['birth_date'][1];?></td>
											<td class="f-size-8 td-border-light-left td-border-light-top td-border-light-bottom td-center-middle bg-white" width="15"><?php echo $dependents[1]['birth_date'][2];?></td>
											<td class="f-size-8 td-border-light-top td-border-light-bottom td-center-middle bg-white" width="15"><?php echo $dependents[1]['birth_date'][3];?></td>
											<td class="f-size-8 td-border-light-left td-border-light-top td-border-light-bottom td-center-middle bg-white" width="15"><?php echo $dependents[1]['birth_date'][4];?></td>
											<td class="f-size-8 td-border-light-top td-border-light-bottom td-center-middle bg-white" width="15"><?php echo $dependents[1]['birth_date'][5];?></td>
											<td class="f-size-8 td-border-light-top td-border-light-bottom td-center-middle bg-white" width="15"><?php echo $dependents[1]['birth_date'][6];?></td>
											<td class="f-size-8 td-border-light-right td-border-light-top td-border-light-bottom td-center-middle bg-white" width="15"><?php echo $dependents[1]['birth_date'][7];?></td>
										</tr>
										<tr>
											<td class="f-size-8" width="15"></td>
											<td class="f-size-8 border-light bg-white" width="193"><?php echo $dependents[2]['dependent_name'];?></td>
											<td class="f-size-8" width="15"></td>
											<td class="f-size-8 td-border-light-left td-border-light-top td-border-light-bottom td-center-middle bg-white" width="15" height="13"><?php echo $dependents[2]['birth_date'][0];?></td>
											<td class="f-size-8 td-border-light-top td-border-light-bottom td-center-middle bg-white" width="15"><?php echo $dependents[2]['birth_date'][1];?></td>
											<td class="f-size-8 td-border-light-left td-border-light-top td-border-light-bottom td-center-middle bg-white" width="15"><?php echo $dependents[2]['birth_date'][2];?></td>
											<td class="f-size-8 td-border-light-top td-border-light-bottom td-center-middle bg-white" width="15"><?php echo $dependents[2]['birth_date'][3];?></td>
											<td class="f-size-8 td-border-light-left td-border-light-top td-border-light-bottom td-center-middle bg-white" width="15"><?php echo $dependents[2]['birth_date'][4];?></td>
											<td class="f-size-8 td-border-light-top td-border-light-bottom td-center-middle bg-white" width="15"><?php echo $dependents[2]['birth_date'][5];?></td>
											<td class="f-size-8 td-border-light-top td-border-light-bottom td-center-middle bg-white" width="15"><?php echo $dependents[2]['birth_date'][6];?></td>
											<td class="f-size-8 td-border-light-right td-border-light-top td-border-light-bottom td-center-middle bg-white" width="15"><?php echo $dependents[2]['birth_date'][7];?></td>
										</tr>
										<tr>
											<td class="f-size-8" width="15"></td>
											<td class="f-size-8 border-light bg-white" width="193"><?php echo $dependents[3]['dependent_name'];?></td>
											<td class="f-size-8" width="15"></td>
											<td class="f-size-8 td-border-light-left td-border-light-top td-border-light-bottom td-center-middle bg-white" width="15" height="13"><?php echo $dependents[3]['birth_date'][0];?></td>
											<td class="f-size-8 td-border-light-top td-border-light-bottom td-center-middle bg-white" width="15"><?php echo $dependents[3]['birth_date'][1];?></td>
											<td class="f-size-8 td-border-light-left td-border-light-top td-border-light-bottom td-center-middle bg-white" width="15"><?php echo $dependents[3]['birth_date'][2];?></td>
											<td class="f-size-8 td-border-light-top td-border-light-bottom td-center-middle bg-white" width="15"><?php echo $dependents[3]['birth_date'][3];?></td>
											<td class="f-size-8 td-border-light-left td-border-light-top td-border-light-bottom td-center-middle bg-white" width="15"><?php echo $dependents[3]['birth_date'][4];?></td>
											<td class="f-size-8 td-border-light-top td-border-light-bottom td-center-middle bg-white" width="15"><?php echo $dependents[3]['birth_date'][5];?></td>
											<td class="f-size-8 td-border-light-top td-border-light-bottom td-center-middle bg-white" width="15"><?php echo $dependents[3]['birth_date'][6];?></td>
											<td class="f-size-8 td-border-light-right td-border-light-top td-border-light-bottom td-center-middle bg-white" width="15"><?php echo $dependents[3]['birth_date'][7];?></td>
										</tr>
									</table>
								</td>
							</tr>
							<tr><td height="1"></td></tr>
							<!-- ROW 12 -->
							<tr>
								<td class="f-size-8 td-border-top">
									<table>
										<tr><td height="1"></td></tr>
										<tr>
											<td class="f-size-8" width="200" valign=top><?php echo nbs(1)?><b>12</b><?php echo nbs(2)?>Statutory Minimum Wage rate per day</td>
											<td class="f-size-8" width="23" align="right" valign=top><b>12</b><?php echo nbs(1)?></td>
											<td class="f-size-8 border-light bg-white" width="120" height="13"></td>
										</tr>
									</table>
								</td>
							</tr>
							<!-- ROW 13 -->
							<tr>
								<td class="f-size-8">
									<table>
										<tr><td height="1"></td></tr>
										<tr>
											<td class="f-size-8" width="200" valign=top><?php echo nbs(1)?><b>13</b><?php echo nbs(2)?>Statutory Minimum Wage rate per day</td>
											<td class="f-size-8" width="23" align="right" valign=top><b>13</b><?php echo nbs(1)?></td>
											<td class="f-size-8 border-light bg-white" width="120" height="13"></td>
										</tr>
									</table>
								</td>
							</tr>
							<!-- ROW 14 -->
							<tr>
								<td class="f-size-8">
									<table>
										<tr>
											<td class="f-size-8" width="22"><?php echo nbs(1)?><b>14</b></td>
											<td class="f-size-8 border-light bg-white" width="25" height="13"></td>
											<td class="f-size-8"><?php echo nbs(2)?>Minimum Wage Earner whose compensation is exempt from </td>											
										</tr>
										<tr>
											<td class="f-size-8"></td>
											<td class="f-size-8"></td>
											<td class="f-size-8"><?php echo nbs(2)?>withholding tax and not subject to income tax</td>
										</tr>
									</table>
								</td>
							</tr>
							<!-- PART II -->
							<tr>
								<td class="f-size-8 td-border-thick-top td-border-thick-bottom bg-white" height="14"><b>Part II<?php echo nbs(17)?>Employer Information (Present)</b></td>
							</tr>
							<!-- ROW 15 -->
							<tr>
								<td class="f-size-8">
									<table>
										<tr>
											<td class="f-size-8" width="20" height="15" valign=top><?php echo nbs(1)?><b>15</b></td>
											<td class="f-size-8" width="90"><?php echo nbs(1)?>Taxpayer<br><?php echo nbs(1)?>Identification No.</td>	
											<td class="f-size-8" width="20" align="center" valign=middle>&#9656;</td>
											<td class="f-size-8">
												<table>
													<tr><td height="1"></td></tr>
													<tr>
														<td class="f-size-8 td-border-light-left td-border-light-top td-border-light-bottom td-center-middle bg-white" width="13" height="15"><?php echo $doh_tin[0];?></td>
														<td class="f-size-8 td-border-light-left td-border-light-top td-border-light-bottom td-center-middle bg-white" width="13"><?php echo $doh_tin[1];?></td>
														<td class="f-size-8 td-border-light-left td-border-light-top td-border-light-bottom td-center-middle bg-white" width="13"><?php echo $doh_tin[2];?></td>
														<td class="f-size-8 td-border-light-left td-border-light-top td-border-light-bottom" width="15"></td>
														<td class="f-size-8 td-border-light-left td-border-light-top td-border-light-bottom td-center-middle bg-white" width="13"><?php echo $doh_tin[3];?></td>
														<td class="f-size-8 td-border-light-left td-border-light-top td-border-light-bottom td-center-middle bg-white" width="13"><?php echo $doh_tin[4];?></td>
														<td class="f-size-8 td-border-light-left td-border-light-top td-border-light-bottom td-center-middle bg-white" width="13"><?php echo $doh_tin[5];?></td>
														<td class="f-size-8 td-border-light-left td-border-light-top td-border-light-bottom" width="15"></td>
														<td class="f-size-8 td-border-light-left td-border-light-top td-border-light-bottom td-center-middle bg-white" width="13"><?php echo $doh_tin[6];?></td>
														<td class="f-size-8 td-border-light-left td-border-light-top td-border-light-bottom td-center-middle bg-white" width="13"><?php echo $doh_tin[7];?></td>
														<td class="f-size-8 td-border-light-left td-border-light-top td-border-light-bottom td-center-middle bg-white" width="13"><?php echo $doh_tin[8];?></td>
														<td class="f-size-8 td-border-light-left td-border-light-top td-border-light-bottom" width="15"></td>
														<td class="f-size-8 td-border-light-left td-border-light-top td-border-light-bottom td-center-middle bg-white" width="13"><?php echo $doh_tin[9];?></td>
														<td class="f-size-8 td-border-light-left td-border-light-top td-border-light-bottom td-center-middle bg-white" width="13"><?php echo $doh_tin[10];?></td>
														<td class="f-size-8 td-border-light-left td-border-light-top td-border-light-bottom td-center-middle bg-white" width="13"><?php echo $doh_tin[11];?></td>
														<td class="f-size-8 border-light bg-white" width="13"><?php echo $doh_tin[12];?></td>
													</tr>
													<tr><td height="1"></td></tr>
												</table>
											</td>
										</tr>
									</table>
								</td>
							</tr>
							<!-- ROW 16 -->
							<tr>
								<td class="f-size-8 td-border-top">
									<table>
										<tr>
											<td class="f-size-8" width="20" valign=top><?php echo nbs(1)?><b>16</b></td>
											<td class="f-size-8"><?php echo nbs(1)?>Employer's Name </td>	
										</tr>
										<tr>
											<td class="f-size-8"><?php echo nbs(2)?>&#9656;</td>
											<td class="f-size-8 border-light bg-white" width="324" height="15">Department of Health</td>
										</tr>
										<tr><td height="1"></td></tr>
									</table>
								</td>
							</tr>
							<!-- ROW 17 -->
							<tr>
								<td class="f-size-8 td-border-top">
									<table>
										<tr>
											<td class="f-size-8" width="20" valign=top><?php echo nbs(1)?><b>17</b></td>
											<td class="f-size-8">Registered Address</td>	
											<td class="f-size-8"><b>17A</b><?php echo nbs(2)?>Zip Code</td>
										</tr>
										<tr>
											<td class="f-size-8"><?php echo nbs(2)?>&#9656;</td>
											<td class="f-size-8 border-light bg-white" width="260" height="15"><?php echo $doh_add;?></td>
											<td class="f-size-8">
												<table>
													<tr>	
														<td class="f-size-8" width="12"></td>							
														<td class="f-size-8 td-border-light-left td-border-light-top td-border-light-bottom bg-white" width="13" height="15"><?php echo $doh_zip_code[0];?></td>
														<td class="f-size-8 td-border-light-left td-border-light-top td-border-light-bottom bg-white" width="13"><?php echo $doh_zip_code[1];?></td>
														<td class="f-size-8 td-border-light-left td-border-light-top td-border-light-bottom bg-white" width="13"><?php echo $doh_zip_code[2];?></td>
														<td class="f-size-8 border-light bg-white" width="13"><?php echo $doh_zip_code[3];?></td>
													</tr>
												</table>
											</td>
										</tr>
										<tr><td height="1"></td></tr>
									</table>
								</td>
							</tr>
							<!-- CHECKBOX -->
							<tr>
								<td class="f-size-8 td-border-top" height="15">
									<table>
										<tr>
											<td class="f-size-8" width="50"></td>
											<td class="f-size-8 border-light bg-white" width="20" height="13"></td>
											<td class="f-size-8" width="100"><?php echo nbs(3)?>Main Employer</td>
											<td class="f-size-8 border-light bg-white" width="20" height="13"></td>
											<td class="f-size-8" width="50"><?php echo nbs(3)?>Secondary Employer</td>
										</tr>
									</table>
								</td>
							</tr>
							<!-- PART III -->
							<tr>
								<td class="f-size-8 td-border-thick-top td-border-thick-bottom bg-white" height="15"><b>Part III<?php echo nbs(17)?>Employer Information (Previous)</b></td>
							</tr>
							<!-- ROW 18 -->
							<tr>
								<td class="f-size-8">
									<table>
										<tr>
											<td class="f-size-8" width="20" height="15" valign=top><?php echo nbs(1)?><b>18</b></td>
											<td class="f-size-8" width="90"><?php echo nbs(1)?>Taxpayer<br><?php echo nbs(1)?>Identification No.</td>	
											<td class="f-size-8" width="20" align="center" valign=middle>&#9656;</td>
											<td class="f-size-8">
												<table>
													<tr><td height="1"></td></tr>
													<tr>
														<td class="f-size-8 td-border-light-left td-border-light-top td-border-light-bottom bg-white" width="13" height="15"><?php echo $prev_employer_tin['other_deduction_detail_value'][0]?></td>
														<td class="f-size-8 td-border-light-left td-border-light-top td-border-light-bottom bg-white" width="13"><?php echo $prev_employer_tin['other_deduction_detail_value'][1]?></td>
														<td class="f-size-8 td-border-light-left td-border-light-top td-border-light-bottom bg-white" width="13"><?php echo $prev_employer_tin['other_deduction_detail_value'][2]?></td>
														<td class="f-size-8 td-border-light-left td-border-light-top td-border-light-bottom" width="15"></td>
														<td class="f-size-8 td-border-light-left td-border-light-top td-border-light-bottom bg-white" width="13"><?php echo $prev_employer_tin['other_deduction_detail_value'][3]?></td>
														<td class="f-size-8 td-border-light-left td-border-light-top td-border-light-bottom bg-white" width="13"><?php echo $prev_employer_tin['other_deduction_detail_value'][4]?></td>
														<td class="f-size-8 td-border-light-left td-border-light-top td-border-light-bottom bg-white" width="13"><?php echo $prev_employer_tin['other_deduction_detail_value'][5]?></td>
														<td class="f-size-8 td-border-light-left td-border-light-top td-border-light-bottom" width="15"></td>
														<td class="f-size-8 td-border-light-left td-border-light-top td-border-light-bottom bg-white" width="13"><?php echo $prev_employer_tin['other_deduction_detail_value'][6]?></td>
														<td class="f-size-8 td-border-light-left td-border-light-top td-border-light-bottom bg-white" width="13"><?php echo $prev_employer_tin['other_deduction_detail_value'][7]?></td>
														<td class="f-size-8 td-border-light-left td-border-light-top td-border-light-bottom bg-white" width="13"><?php echo $prev_employer_tin['other_deduction_detail_value'][8]?></td>
														<td class="f-size-8 td-border-light-left td-border-light-top td-border-light-bottom" width="15"></td>
														<td class="f-size-8 td-border-light-left td-border-light-top td-border-light-bottom bg-white" width="13"><?php echo $prev_employer_tin['other_deduction_detail_value'][9]?></td>
														<td class="f-size-8 td-border-light-left td-border-light-top td-border-light-bottom bg-white" width="13"><?php echo $prev_employer_tin['other_deduction_detail_value'][10]?></td>
														<td class="f-size-8 td-border-light-left td-border-light-top td-border-light-bottom bg-white" width="13"><?php echo $prev_employer_tin['other_deduction_detail_value'][11]?></td>
														<td class="f-size-8 border-light bg-white" width="13"><?php echo $prev_employer_tin['other_deduction_detail_value'][12]?></td>
													</tr>
													<tr><td height="1"></td></tr>
												</table>
											</td>
										</tr>
									</table>
								</td>
							</tr>
							<!-- ROW 19 -->
							<tr>
								<td class="f-size-8 td-border-top">
									<table>
										<tr>
											<td class="f-size-8" width="20" valign=top><?php echo nbs(1)?><b>19</b></td>
											<td class="f-size-8"><?php echo nbs(1)?>Employer's Name </td>	
										</tr>
										<tr>
											<td class="f-size-8"><?php echo nbs(2)?>&#9656;</td>
											<td class="f-size-8 border-light bg-white" width="324" height="15"><?php echo $prev_employer_name['other_deduction_detail_value']?></td>
										</tr>
										<tr><td height="1"></td></tr>
									</table>
								</td>
							</tr>
							<!-- ROW 20 -->
							<tr>
								<td class="f-size-8 td-border-top">
									<table>
										<tr>
											<td class="f-size-8" width="20" valign=top><?php echo nbs(1)?><b>20</b></td>
											<td class="f-size-8">Registered Address</td>	
											<td class="f-size-8"><b>20A</b><?php echo nbs(2)?>Zip Code</td>
										</tr>
										<tr>
											<td class="f-size-8"><?php echo nbs(2)?>&#9656;</td>
											<td class="f-size-8 border-light bg-white" width="260" height="15"><?php echo $prev_employer_address['other_deduction_detail_value']?></td>
											<td class="f-size-8">
												<table>
													<tr>	
														<td class="f-size-8" width="12"></td>							
														<td class="f-size-8 td-border-light-left td-border-light-top td-border-light-bottom bg-white" width="13" height="15"></td>
														<td class="f-size-8 td-border-light-left td-border-light-top td-border-light-bottom bg-white" width="13"></td>
														<td class="f-size-8 td-border-light-left td-border-light-top td-border-light-bottom bg-white" width="13"></td>
														<td class="f-size-8 border-light bg-white" width="13"></td>
													</tr>
												</table>
											</td>
										</tr>
										<tr><td height="1"></td></tr>
									</table>
								</td>
							</tr>
							<!-- PART IV-A -->
							<tr>
								<td class="f-size-8 td-border-thick-top td-border-thick-bottom bg-white" height="14"><b>Part IV-A<?php echo nbs(30)?>Summary</b></td>
							</tr>
							<tr><td height="1"></td></tr>
							<!-- ROW 21 -->
							<tr>
								<td class="f-size-8">
									<table>
										<tr>
											<td class="f-size-8" width="185"><span><?php echo nbs(1)?><b>21</b><?php echo nbs(2)?>Gross Compensation Income from<br><?php echo nbs(7)?>Present Employer (Item 41 plus Item 55)</span></td>
											<td class="f-size-8" width="20" align=right valign=top><b>21</b><?php echo nbs(1)?></td>
											<td class="f-size-8 border-light td-right-middle bg-white" width="140"><?php echo number_format($details['gross_income_present_employer'], 2);?></td>
										</tr>
									</table>
								</td>
							</tr>
							<tr><td height="1"></td></tr>
							<!-- ROW 22 -->
							<tr>
								<td class="f-size-8">
									<table>
										<tr>
											<td class="f-size-8" width="185"><span><?php echo nbs(1)?><b>22</b><?php echo nbs(2)?>Less: Total Non-Taxable/<br><?php echo nbs(7)?>Exempt (Item 41)</span></td>
											<td class="f-size-8" width="20" align=right valign=top><b>22</b><?php echo nbs(1)?></td>
											<td class="f-size-8 border-light td-right-middle bg-white" width="140"><?php echo number_format($details['total_non_tax_exemption'], 2);?></td>
										</tr>
									</table>
								</td>
							</tr>
							<tr><td height="1"></td></tr>
							<!-- ROW 23 -->
							<tr>
								<td class="f-size-8">
									<table>
										<tr>
											<td class="f-size-8" width="185"><span><?php echo nbs(1)?><b>23</b><?php echo nbs(2)?>Taxable Compensation Income<br><?php echo nbs(7)?>from Present Employer (Item 55)</span></td>
											<td class="f-size-8" width="20" align=right valign=top><b>23</b><?php echo nbs(1)?></td>
											<td class="f-size-8 border-light td-right-middle bg-white" width="140"><?php echo number_format($details['taxable_income_present_employer'], 2);?></td>
										</tr>
									</table>
								</td>
							</tr>
							<tr><td height="1"></td></tr>
							<!-- ROW 24 -->
							<tr>
								<td class="f-size-8">
									<table>
										<tr>
											<td class="f-size-8" width="185"><span><?php echo nbs(1)?><b>24</b><?php echo nbs(2)?>Add: Taxable Compensation<br><?php echo nbs(7)?>Income from Previous Employer</span></td>
											<td class="f-size-8" width="20" align=right valign=top><b>24</b><?php echo nbs(1)?></td>
											<td class="f-size-8 border-light td-right-middle bg-white" width="140"><?php echo number_format($details['taxable_income_previous_employer'], 2);?></td>
										</tr>
									</table>
								</td>
							</tr>	
							<tr><td height="1"></td></tr>
							<!-- ROW 25 -->
							<tr>
								<td class="f-size-8">
									<table>
										<tr>
											<td class="f-size-8" width="185"><span><?php echo nbs(1)?><b>25</b><?php echo nbs(2)?>Gross Taxable<br><?php echo nbs(7)?>Compensation Income</span></td>
											<td class="f-size-8" width="20" align=right valign=top><b>25</b><?php echo nbs(1)?></td>
											<td class="f-size-8 border-light td-right-middle bg-white" width="140"><?php echo number_format($details['gross_taxable_income'], 2);?></td>
										</tr>
									</table>
								</td>
							</tr>	
							<tr><td height="1"></td></tr>
							<!-- ROW 26 -->
							<tr>
								<td class="f-size-8">
									<table>
										<tr>
											<td class="f-size-8" width="185"><span><?php echo nbs(1)?><b>26</b><?php echo nbs(2)?>Less: Total Exemptions</span></td>
											<td class="f-size-8" width="20" align=right valign=top><b>26</b><?php echo nbs(1)?></td>
											<td class="f-size-8 border-light td-right-middle bg-white" width="140" height="15"><?php echo number_format($details['total_personal_exemption'], 2);?></td>
										</tr>
									</table>
								</td>
							</tr>	
							<tr><td height="1"></td></tr>
							<!-- ROW 27 -->
							<tr>
								<td class="f-size-8">
									<table>
										<tr>
											<td class="f-size-8" width="185"><span><?php echo nbs(1)?><b>27</b><?php echo nbs(2)?>Less: Premium Paid on Health/<br><?php echo nbs(7)?>and/or Hospital Insurance (If applicable)</span></td>
											<td class="f-size-8" width="20" align=right valign=top><b>27</b><?php echo nbs(1)?></td>
											<td class="f-size-8 border-light td-right-middle bg-white" width="140"><?php echo number_format($details['health_insurance_paid'], 2);?></td>
										</tr>
									</table>
								</td>
							</tr>
							<tr><td height="1"></td></tr>
							<!-- ROW 28-->
							<tr>
								<td class="f-size-8">
									<table>
										<tr>
											<td class="f-size-8" width="185"><span><?php echo nbs(1)?><b>28</b><?php echo nbs(2)?>Net Taxable <br><?php echo nbs(7)?>Compensation Income</span></td>
											<td class="f-size-8" width="20" align=right valign=top><b>28</b><?php echo nbs(1)?></td>
											<td class="f-size-8 border-light td-right-middle bg-white" width="140"><?php echo number_format($details['net_taxable_income'], 2);?></td>
										</tr>
									</table>
								</td>
							</tr>	
							<tr><td height="1"></td></tr>
							<!-- ROW 29-->
							<tr>
								<td class="f-size-8">
									<table>
										<tr>
											<td class="f-size-8" width="185"><span><?php echo nbs(1)?><b>29</b><?php echo nbs(2)?>Tax Due</span></td>
											<td class="f-size-8" width="20" align=right valign=top><b>29</b><?php echo nbs(1)?></td>
											<td class="f-size-8 border-light td-right-middle bg-white" width="140" height="15"><?php echo number_format($details['tax_due'], 2);?></td>
										</tr>
									</table>
								</td>
							</tr>	
							<tr><td height="1"></td></tr>
							<!-- ROW 30A-->
							<tr>
								<td class="f-size-8">
									<table>
										<tr>
											<td class="f-size-8" width="175"><span><?php echo nbs(1)?><b>30</b><?php echo nbs(2)?>Amount of Taxes Withheld<br><?php echo nbs(7)?><b>30A</b><?php echo nbs(1)?>Present Employer</span></td>
											<td class="f-size-8" width="30" align=right valign=top><b>30A</b><?php echo nbs(1)?></td>
											<td class="f-size-8 border-light td-right-middle bg-white" width="140"><?php echo number_format($details['tax_withheld_present_employer'], 2);?></td>
										</tr>
									</table>
								</td>
							</tr>	
							<tr><td height="1"></td></tr>
							<!-- ROW 30B-->
							<tr>
								<td class="f-size-8">
									<table>
										<tr>
											<td class="f-size-8" width="175"><span><?php echo nbs(7)?><b>30B</b><?php echo nbs(1)?>Previous Employer</span></td>
											<td class="f-size-8" width="30" align=right valign=top><b>30B</b><?php echo nbs(1)?></td>
											<td class="f-size-8 border-light td-right-middle bg-white" width="140" height="15"><?php echo number_format($details['tax_withheld_previous_employer'], 2);?></td>
										</tr>
									</table>
								</td>
							</tr>	
							<tr><td height="1"></td></tr>
							<!-- ROW 31-->
							<tr>
								<td class="f-size-8">
									<table>
										<tr>
											<td class="f-size-8" width="175"><span><?php echo nbs(1)?><b>31</b><?php echo nbs(2)?>Total Amount of Taxes Withheld<br><?php echo nbs(7)?>As adjusted</span></td>
											<td class="f-size-8" width="30" align=right valign=top><b>31</b><?php echo nbs(1)?></td>
											<td class="f-size-8 border-light td-right-middle bg-white" width="140"><?php echo number_format($details['total_tax_withheld'], 2);?></td>
										</tr>
									</table>
								</td>
							</tr>	
							<tr><td height="1"></td></tr>
						</table>
					</td>
					<!-- END MAIN COLUMN 1 -->

					<!-- MAIN COLUMN 2 -->
					<td class="f-size-8" width="350" valign=top>
						<table>
							<!-- ROW 32  -->
							<tr><td height="7"></td></tr>
							<tr>
								<td class="f-size-8" width="23" valign=top height="17"><?php echo nbs(1)?><b>32</b></td>
								<td class="f-size-8" width="140">Basic Salary/<br>Statutory Minimum Wage<br></td>
								<td class="f-size-8" width="20" valign=top><b>32</b></td>
								<td class="f-size-8 border-light td-right-middle bg-white" width="163"><?php echo number_format($details['mwe_basic_salary'], 2);?></td>
							</tr>
							<tr>
								<td class="f-size-8" colspan=4><?php echo nbs(8)?>Minimum Wage Earner (MWE)</td>
							</tr>
							<!-- ROW 33 -->
							<tr><td height="5"></td></tr>
							<tr>
								<td class="f-size-8" width="23" valign=top height="17"><?php echo nbs(1)?><b>33</b></td>
								<td class="f-size-8" width="140" valign=top>Holiday Pay (MWE)</td>
								<td class="f-size-8" width="20" valign=top><b>33</b></td>
								<td class="f-size-8 border-light td-right-middle bg-white" width="163"><?php echo number_format($details['mwe_holiday_pay'], 2);?></td>
							</tr>
							<!-- ROW 34 -->
							<tr><td height="4"></td></tr>
							<tr>
								<td class="f-size-8" width="23" valign=top height="17"><?php echo nbs(1)?><b>34</b></td>
								<td class="f-size-8" width="140" valign=top>Overtime Pay (MWE)</td>
								<td class="f-size-8" width="20" valign=top><b>34</b></td>
								<td class="f-size-8 border-light td-right-middle bg-white" width="163"><?php echo number_format($details['mwe_overtime_pay'], 2);?></td>
							</tr>
							<!-- ROW 35 -->
							<tr><td height="4"></td></tr>
							<tr>
								<td class="f-size-8" width="23" valign=top height="17"><?php echo nbs(1)?><b>35</b></td>
								<td class="f-size-8" width="140" valign=top>Night Shift Differential (MWE)</td>
								<td class="f-size-8" width="20" valign=top><b>35</b></td>
								<td class="f-size-8 border-light td-right-middle bg-white" width="163"><?php echo number_format($details['mwe_night_diff_pay'], 2);?></td>
							</tr>
							<!-- ROW 36 -->
							<tr><td height="4"></td></tr>
							<tr>
								<td class="f-size-8" width="23" valign=top height="17"><?php echo nbs(1)?><b>36</b></td>
								<td class="f-size-8" width="140" valign=top>Hazard Pay (MWE)</td>
								<td class="f-size-8" width="20" valign=top><b>36</b></td>
								<td class="f-size-8 border-light td-right-middle bg-white" width="163"><?php echo number_format($details['mwe_hazard_pay'], 2);?></td>
							</tr>
							<!-- ROW 37 -->
							<tr><td height="4"></td></tr>
							<tr>
								<td class="f-size-8" width="23" valign=top height="17"><?php echo nbs(1)?><b>37</b></td>
								<td class="f-size-8" width="140">13th Month Pay<br>and Other Benefits</td>
								<td class="f-size-8" width="20" valign=top><b>37</b></td>
								<td class="f-size-8 border-light td-right-middle bg-white" width="163"><?php echo number_format($details['month_pay_13'], 2);?></td>
							</tr>
							<!-- ROW 38 -->
							<tr><td height="7"></td></tr>
							<tr>
								<td class="f-size-8" width="23" valign=top height="17"><?php echo nbs(1)?><b>38</b></td>
								<td class="f-size-8" width="140" valign=top>De Minimis Benefits</td>
								<td class="f-size-8" width="20" valign=top><b>38</b></td>
								<td class="f-size-8 border-light td-right-middle bg-white" width="163"><?php echo number_format($details['deminimis'], 2);?></td>
							</tr>
							<!-- ROW 39 -->
							<tr><td height="7"></td></tr>
							<tr>
								<td class="f-size-8" width="23" valign=top height="17"><?php echo nbs(1)?><b>39</b></td>
								<td class="f-size-8" width="140">SSS, GSIS, PHIC & Pag-ibig<br>Contributions, & Union Duess</td>
								<td class="f-size-8" width="20" valign=top><b>39</b></td>
								<td class="f-size-8 border-light td-right-middle bg-white" width="163"><?php echo number_format($details['statutory_employee_share'], 2);?></td>
							</tr>
							<tr>
								<td colspan=4 height="17"><?php echo nbs(8)?>(Employee share only)</td>
							</tr>
							<!-- ROW 40 -->
							<tr><td height="7"></td></tr>
							<tr>
								<td class="f-size-8" width="23" valign=top height="17"><?php echo nbs(1)?><b>40</b></td>
								<td class="f-size-8" width="140">Salaries & Other Forms of<br>Compensation</td>
								<td class="f-size-8" width="20" valign=top><b>40</b></td>
								<td class="f-size-8 border-light td-right-middle bg-white" width="163"><?php echo number_format($details['salary_other_compensation'], 2);?></td>
							</tr>
							<!-- ROW 41 -->
							<tr><td height="7"></td></tr>
							<tr>
								<td class="f-size-8" width="23" valign=top height="17"><?php echo nbs(1)?><b>41</b></td>
								<td class="f-size-8" width="140">Total Non-Taxable/Exempt<br>Compensation Income</td>
								<td class="f-size-8" width="20" valign=top><b>41</b></td>
								<td class="f-size-8 border-light td-right-middle bg-white" width="163"><?php echo number_format($details['total_exempt_income'], 2);?></td>
							</tr>
							<!-- ROW B -->
							<tr><td height="5"></td></tr>
							<tr>
								<td class="f-size-8" colspan=4 width="23" valign=top><?php echo nbs(1)?><b>B.<?php echo nbs(3)?>TAXABLE COMPENSATION INCOME</b></td>
							</tr>
							<tr>
								<td class="f-size-8" colspan=4 width="23" valign=top><?php echo nbs(8)?><b>REGULAR</b></td>
							</tr>
							<!-- ROW 42 -->
							<tr><td height="7"></td></tr>
							<tr>
								<td class="f-size-8" width="23" valign=top height="17"><?php echo nbs(1)?><b>42</b></td>
								<td class="f-size-8" width="140" valign=top>Basic Salary</td>
								<td class="f-size-8" width="20" valign=top><b>42</b></td>
								<td class="f-size-8 border-light td-right-middle bg-white" width="163"><?php echo number_format($details['basic_salary'], 2);?></td>
							</tr>
							<!-- ROW 43 -->
							<tr><td height="5"></td></tr>
							<tr>
								<td class="f-size-8" width="23" valign=top height="17"><?php echo nbs(1)?><b>43</b></td>
								<td class="f-size-8" width="140" valign=top>Representation</td>
								<td class="f-size-8" width="20" valign=top><b>43</b></td>
								<td class="f-size-8 border-light td-right-middle bg-white" width="163"><?php echo number_format($details['representation'], 2);?></td>
							</tr>
							<!-- ROW 44 -->
							<tr><td height="5"></td></tr>
							<tr>
								<td class="f-size-8" width="23" valign=top height="17"><?php echo nbs(1)?><b>44</b></td>
								<td class="f-size-8" width="140" valign=top>Transportation</td>
								<td class="f-size-8" width="20" valign=top><b>44</b></td>
								<td class="f-size-8 border-light td-right-middle bg-white" width="163"><?php echo number_format($details['transportation'], 2);?></td>
							</tr>
							<!-- ROW 45 -->
							<tr><td height="5"></td></tr>
							<tr>
								<td class="f-size-8" width="23" valign=top height="17"><?php echo nbs(1)?><b>45</b></td>
								<td class="f-size-8" width="140" valign=top>Cost of Living Allowance</td>
								<td class="f-size-8" width="20" valign=top><b>45</b></td>
								<td class="f-size-8 border-light td-right-middle bg-white" width="163"><?php echo number_format($details['cost_of_living_allowance'], 2);?></td>
							</tr>
							<!-- ROW 46 -->
							<tr><td height="5"></td></tr>
							<tr>
								<td class="f-size-8" width="23" valign=top height="17"><?php echo nbs(1)?><b>46</b></td>
								<td class="f-size-8" width="140" valign=top>Fixed Housing Allowance</td>
								<td class="f-size-8" width="20" valign=top><b>46</b></td>
								<td class="f-size-8 border-light td-right-middle bg-white" width="163"><?php echo number_format($details['fixed_housing_allowance'], 2);?></td>
							</tr>
							<!-- ROW 47 -->
							<tr><td height="5"></td></tr>
							<tr>
								<td class="f-size-8" width="23" valign=top height="17"><?php echo nbs(1)?><b>47</b></td>
								<td class="f-size-8" width="140" valign=top>Others (Specify)</td>
							</tr>
							<!-- ROW 47A -->
							<tr><td height="5"></td></tr>
							<tr>
								<td class="f-size-8" width="23" valign=top height="17"><?php echo nbs(1)?><b>47A</b></td>
								<td class="f-size-8">
									<table>
										<tr>
											<td class="f-size-8 border-light bg-white" width="130" height="15"><?php echo $details['others_taxable_name_47a'];?></td>
											<td class="f-size-8" width="10"></td>
										</tr>
									</table>
								</td>
								<td class="f-size-8" width="20" valign=top><b>47A</b></td>
								<td class="f-size-8 border-light td-right-middle bg-white" width="163"><?php echo $details['others_taxable_47a'];?></td>
							</tr>
							<!-- ROW 47B -->
							<tr><td height="5"></td></tr>
							<tr>
								<td class="f-size-8" width="23" valign=top height="17"><?php echo nbs(1)?><b>47B</b></td>
								<td class="f-size-8">
									<table>
										<tr>
											<td class="f-size-8 border-light bg-white" width="130" height="15"><?php echo $details['others_taxable_name_47b'];?></td>
											<td class="f-size-8" width="10"></td>
										</tr>
									</table>
								</td>
								<td class="f-size-8" width="20" valign=top><b>47B</b></td>
								<td class="f-size-8 border-light td-right-middle bg-white" width="163"><?php echo $details['others_taxable_47b'];?></td>
							</tr>
							<tr>
								<td class="f-size-8" colspan=4 width="23"><?php echo nbs(8)?><b>SUPPLEMENTARY</b></td>
							</tr>
							<!-- ROW 48 -->
							<tr><td height="5"></td></tr>
							<tr>
								<td class="f-size-8" width="23" valign=top height="17"><?php echo nbs(1)?><b>48</b></td>
								<td class="f-size-8" width="140" valign=top>Commission</td>
								<td class="f-size-8" width="20" valign=top><b>48</b></td>
								<td class="f-size-8 border-light td-right-middle bg-white" width="163"><?php echo number_format($details['commission'], 2);?></td>
							</tr>
							<!-- ROW 49 -->
							<tr><td height="7"></td></tr>
							<tr>
								<td class="f-size-8" width="23" valign=top height="17"><?php echo nbs(1)?><b>49</b></td>
								<td class="f-size-8" width="140" valign=top>Profit Sharing</td>
								<td class="f-size-8" width="20" valign=top><b>49</b></td>
								<td class="f-size-8 border-light td-right-middle bg-white" width="163"><?php echo number_format($details['profit_sharing'], 2);?></td>
							</tr>
							<!-- ROW 50 -->
							<tr><td height="7"></td></tr>
							<tr>
								<td class="f-size-8" width="23" valign=top height="17"><?php echo nbs(1)?><b>50</b></td>
								<td class="f-size-8" width="140">Fees Including Director's<br>Fees</td>
								<td class="f-size-8" width="20" valign=top><b>50</b></td>
								<td class="f-size-8 border-light td-right-middle bg-white" width="163"><?php echo number_format($details['fees'], 2);?></td>
							</tr>
							<!-- ROW 51 -->
							<tr><td height="5"></td></tr>
							<tr>
								<td class="f-size-8" width="23" valign=top height="17"><?php echo nbs(1)?><b>51</b></td>
								<td class="f-size-8" width="140">Taxable 13th Month Pay<br>and Other Benefits</td>
								<td class="f-size-8" width="20" valign=top><b>51</b></td>
								<td class="f-size-8 border-light td-right-middle bg-white" width="163"><?php echo number_format($details['taxable_month_pay_13'], 2);?></td>
							</tr>
							<!-- ROW 52 -->
							<tr><td height="5"></td></tr>
							<tr>
								<td class="f-size-8" width="23" valign=top height="17"><?php echo nbs(1)?><b>52</b></td>
								<td class="f-size-8" width="140" valign=top>Hazard Pay</td>
								<td class="f-size-8" width="20" valign=top><b>52</b></td>
								<td class="f-size-8 border-light td-right-middle bg-white" width="163"><?php echo number_format($details['hazard_pay'], 2);?></td>
							</tr>
							<!-- ROW 53 -->
							<tr><td height="5"></td></tr>
							<tr>
								<td class="f-size-8" width="23" valign=top height="17"><?php echo nbs(1)?><b>53</b></td>
								<td class="f-size-8" width="140" valign=top>Overtime Pay</td>
								<td class="f-size-8" width="20" valign=top><b>53</b></td>
								<td class="f-size-8 border-light td-right-middle bg-white" width="163"><?php echo number_format($details['overtime_pay'], 2);?></td>
							</tr>
							<!-- ROW 54 -->
							<tr><td height="5"></td></tr>
							<tr>
								<td class="f-size-8" width="23" valign=top height="17"><?php echo nbs(1)?><b>54</b></td>
								<td class="f-size-8" width="140" valign=top>Others (Specify)</td>
							</tr>
							<!-- ROW 54A -->
							<tr><td height="5"></td></tr>
							<tr>
								<td class="f-size-8" width="23" valign=top height="17"><?php echo nbs(1)?><b>54A</b></td>
								<td class="f-size-8">
									<table>
										<tr>
											<td class="f-size-8 border-light bg-white" width="130" height="15"><?php echo $details['others_taxable_name_54a'];?></td>
											<td class="f-size-8" width="10"></td>
										</tr>
									</table>
								</td>
								<td class="f-size-8" width="20" valign=top><b>54A</b></td>
								<td class="f-size-8 border-light td-right-middle bg-white" width="163"><?php echo $details['others_taxable_54a'];?></td>
							</tr>
							<!-- ROW 54B -->
							<tr><td height="5"></td></tr>
							<tr>
								<td class="f-size-8" width="23" valign=top height="17"><?php echo nbs(1)?><b>54B</b></td>
								<td class="f-size-8">
									<table>
										<tr>
											<td class="f-size-8 border-light bg-white" width="130" height="15"><?php echo $details['others_taxable_name_54b'];?></td>
											<td class="f-size-8" width="10"></td>
										</tr>
									</table>
								</td>
								<td class="f-size-8" width="20" valign=top><b>54B</b></td>
								<td class="f-size-8 border-light td-right-middle bg-white" width="163"><?php echo $details['others_taxable_54b'];?></td>
							</tr>
							<!-- ROW 55 -->
							<tr><td height="5"></td></tr>
							<tr>
								<td class="f-size-8" width="23" valign=top height="17"><?php echo nbs(1)?><b>55</b></td>
								<td class="f-size-8" width="140" valign=top>Total Taxable Compensation<br>Income</td>
								<td class="f-size-8" width="20" valign=top><b>55</b></td>
								<td class="f-size-8 border-light td-right-middle bg-white" width="163"><?php echo number_format($details['total_taxable_income'], 2);?></td>
							</tr>

						</table>
					</td>
				</tr>
			</table>
			
			<!-- TABLE 5 -->
			<table class="table-max">
				<tr>
					<td class="f-size-8 td-border-top f-size-8" width="700"><?php echo nbs(20)?>We declare, under the penalties of perjury, that this certificate has been made in good faith, verified by us, and to the best of our knowledge and belief, is true and correct<br><?php echo nbs(10)?>pursuant to the provisions of the National Internal Revenue Code, as amended, and the regulations issued under authority thereof.</td>
				</tr>
				<tr>
					<td class="f-size-8">
						<table>
							<tr>
								<td class="f-size-8" width="50" align="right"><b>56</b></td>
								<td class="f-size-8 td-border-bottom align-c" width="270"><?php echo $signatory_a['signatory_name']?></td>
								<td class="f-size-8" width="100" align="right">Date Signed<?php echo nbs(3)?></td>
								<td class="f-size-8 td-border-light-left td-border-light-top td-border-light-bottom" width="15" height="13"></td>
								<td class="f-size-8 td-border-light-top td-border-light-bottom" width="15"></td>
								<td class="f-size-8 td-border-light-left td-border-light-top td-border-light-bottom" width="15"></td>
								<td class="f-size-8 td-border-light-top td-border-light-bottom" width="15"></td>
								<td class="f-size-8 td-border-light-left td-border-light-top td-border-light-bottom " width="15"></td>
								<td class="f-size-8 td-border-light-top td-border-light-bottom" width="15"></td>
								<td class="f-size-8 td-border-light-top td-border-light-bottom" width="15"></td>
								<td class="f-size-8 td-border-light-right td-border-light-top td-border-light-bottom" width="15"></td>
							</tr>
							<tr>
								<td align="right"></td>
								<td class="f-size-8" align="center" valign=top>Present Employer/ Authorized Agent Signature Over Printed Name</td>
							</tr>
							<tr>
								<td class="f-size-8" colspan=2><?php echo nbs(8)?>CONFORME:</td>
							</tr>
							<tr>
								<td class="f-size-8" width="50" align="right"><b>57</b></td>
								<td class="f-size-8 td-border-bottom align-c" width="270"><?php echo $employee['name']?></td>
								<td class="f-size-8" width="100" align="right">Date Signed<?php echo nbs(3)?></td>
								<td class="f-size-8 td-border-light-left td-border-light-top td-border-light-bottom" width="15" height="13"></td>
								<td class="f-size-8 td-border-light-top td-border-light-bottom" width="15"></td>
								<td class="f-size-8 td-border-light-left td-border-light-top td-border-light-bottom" width="15"></td>
								<td class="f-size-8 td-border-light-top td-border-light-bottom" width="15"></td>
								<td class="f-size-8 td-border-light-left td-border-light-top td-border-light-bottom" width="15"></td>
								<td class="f-size-8 td-border-light-top td-border-light-bottom" width="15"></td>
								<td class="f-size-8 td-border-light-top td-border-light-bottom" width="15"></td>
								<td class="f-size-8 td-border-light-right td-border-light-top td-border-light-bottom" width="15"></td>
							</tr>
							<tr>
								<td class="f-size-8" align="right">CTC No</td>
								<td class="f-size-8" align="center" valign=top>Employee Signature Over Printed Name</td>
								<td colspan=10></td>
								<td class="f-size-8" align="center">Amount Paid</td>
							</tr>
							<tr>
								<td colspan=3>
									<table>
										<tr>
											<td class="f-size-8" align="right" height="13"><?php echo nbs(10)?>of Employee<?php echo nbs(2)?></td>
											<td class="f-size-8 border-light" width="100"></td>
											<td class="f-size-8" width="65" align="center">Place of Issue</td>
											<td class="f-size-8 border-light" width="100"></td>
											<td class="f-size-8" width="100" align="right">Date Signed<?php echo nbs(3)?></td>
										</tr>
									</table>
								</td>
								<td class="f-size-8 td-border-light-left td-border-light-top td-border-light-bottom" width="15" height="13"></td>
								<td class="f-size-8 td-border-light-top td-border-light-bottom" width="15"></td>
								<td class="f-size-8 td-border-light-left td-border-light-top td-border-light-bottom" width="15"></td>
								<td class="f-size-8 td-border-light-top td-border-light-bottom" width="15"></td>
								<td class="f-size-8 td-border-light-left td-border-light-top td-border-light-bottom" width="15"></td>
								<td class="f-size-8 td-border-light-top td-border-light-bottom" width="15"></td>
								<td class="f-size-8 td-border-light-top td-border-light-bottom" width="15"></td>
								<td class="f-size-8 td-border-light-right td-border-light-top td-border-light-bottom" width="15"></td>
								<td class="f-size-8" width="30"></td>
								<td class="f-size-8 border-light" width="100"></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
			
			<table class="table-max">
				<tr><td height="1"></td></tr>
				<tr>
					<td class="f-size-8 td-border-thick-top" width="700" align="center"><b>To be accomplished under substituted filing</b></td>
				</tr>
			</table>
        </td>
    </tr>
    <tr>
		<td class="f-size-8 td-border-thick-bottom td-border-thick-left td-border-thick-right">  
			<table>
				<tr>
					<td class="f-size-8 td-border-thick-right">
						<table>
							<tr>
								<td colspan=3 class="f-size-8" width="350"><?php echo nbs(10)?>I declare, under the penalties of perjury, that the information herein stated are reported<br><?php echo nbs(3)?> under BIR Form No. 1604CF which has been filed with the Bureau of Internal Revenue.</td>
							</tr>
							<tr>
								<td class="f-size-8" width="10" align="right" height="30" valign=bottom><b>58</b></td>
								<td width="50" class="td-border-bottom f-size-8 align-c" valign="bottom"><?php echo $signatory_b['signatory_name']?></td>
								<td class="f-size-8" width="10"></td>
							</tr>
							<tr>
								<td align="right"></td>
								<td class="f-size-8 align-c">Present Employer/ Authorized Agent Signature Over Printed Name<br>(Head of Accounting/ Human Resource or Authorized Representative)<br><br><br><br><br></td>
								<td class="f-size-8"></td>
							</tr>
						</table>
					</td>
					<td class="f-size-8">
						<table>
							<tr>
								<td class="f-size-8" width="10"></td>
								<td colspan="2" class="f-size-8">
									<?php echo nbs(10)?>I declare,under the penalties of perjury that I am qualified 
									under substituted filing of under BIR Form No. 1604CF which has been filed with 
									the Bureau of Internal Revenue. Income Tax Returns(BIR Form No. 1700), since I 
									received purely compensation incom from  only  one  employer  in  the  Phils. for 
									the calendar  year; that  taxes have been correctly withheld by my employer (tax due 
									equals tax withheld);  that the  BIR Form No. 1604CF filed by  my  employer to the 
									BIR shall constitute as my income tax return; and that BIR Form No. 2316 shall serve 
									the same purpose as if BIR Form No. 1700 had been filed pursuant to the provisions of 
									RR No. 3-2002, as amended.
								</td>
							</tr>
							<tr>
								<td class="f-size-8" width="10"></td>
								<td class="f-size-8 align-c" colspan="2" width="30"><b>59</b><u><?php echo nbs(30)?><?php echo $employee['name']?><?php echo nbs(30)?></u></td>
							</tr>
							<tr>
								<td align="right"></td>
								<td class="f-size-8" colspan="2" align="center">Employee Signature Over Printed Name</td>
							</tr>

						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
    
</table>
</body>
<?php else :?>

<div class="wrapper">
	<form id="test_report">
		<p style="text-align: center">No data available.</p>
	</form>
</div>

<?php endif;?>
</html>