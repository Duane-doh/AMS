<!DOCTYPE html>
<html>
<?php if(count($results)>0):?>
<head>
	<title>Bank Payroll Register</title>	
	<link rel="stylesheet" href="<?php echo base_url().PATH_CSS ?>reports.css" type="text/css" />
</head>
<body>
<?php foreach ($effective_date as $date):?>
	<div>
		<!-- ===================== jendaigo : start : separate data extraction for pdf and excel ============= -->
		<?php if($format == 'excel'):?>
			<table class="center-30" border="0">
				<tbody>
					<?php foreach ($results[$date['effective_date']] as $result): ?>
					<?php if( $result['amount'] > 0):?>
					<tr>
						<td class="f-size-16"><?php echo ISSET($result['emp_acct_no'])?sprintf('%010d', $result['emp_acct_no']):"&nbsp;";?></td>
						<td class="f-size-16"><?php echo str_replace('Ñ', 'N', $result['emp_full_name']);?></td>
						<td class="f-size-16"><?php echo sprintf('%014d', $result['emp_amount']);?></td>
						
						<td class="f-size-16"><?php echo ISSET($result['emp_acct_no'])?$result['emp_acct_no']:"&nbsp;";?></td>
						<td class="f-size-16"><?php echo str_replace('Ñ', 'N', $result['emp_full_name']);?></td>
						<td class="f-size-16"><?php echo $result['emp_amount'];?></td>
					</tr>
					<?php endif;?>
					<?php endforeach;?>
				</tbody>
			</table>
		<?php else :?>
		<!-- ===================== jendaigo : end : separate data extraction for pdf and excel ============= -->
			<table class="table-max" border="0">
				<thead>
					<tr>
						<td width="20%"><?php echo nbs(10) ?><img src="<?php echo base_url().PATH_IMG ?>doh_logo.png" width=90 height=90></img><br></td>
						<td align="center" class="f-size-12" width="60%">Republic of the Philippines<br>DEPARTMENT OF HEALTH</td>
						<td width="20%">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="3" class="align-c f-size-12"><b>Payroll Register</b></td>
					</tr>	
					<tr>
						<td colspan="3" class="align-c"><b><?php echo date("F j, Y", strtotime($date['effective_date']));?></b></td>
					</tr>
					<tr>
						<td colspan="3" class="valign-bot align-c"><br><?php echo $bank['bank_name'];?><br>DATA BASE REPORT<br><?php echo $bank['bank_name'] . " - [CODE : " . $bank['branch_code'] . "]";?><br>BATCH <?php echo $batch_code;?></td>
					</tr>	
					<tr>
						<td class="bold align-l" height="30" width="150">ACCOUNT NO.</td>
						<td class="bold align-l" width="50">ACCOUNT NAME</td>
						<td class="bold align-r" width="50">CREDIT AMOUNT</td>
					</tr>
				</thead>
				<tbody>
					
					<?php
						$total        = 0;
						$cnt        = 0;
						$account_hash = 0;
						foreach ($results[$date['effective_date']] as $result):
						if($result['amount'] > 0)
						{
							$total += $result['amount'];
							$cnt ++;
							$account_hash += substr(preg_replace("/[^0-9.]/", "", $result['acct_no']),0,10);
						}
						
						
					?>
					<?php if( $result['amount'] > 0):?>
					<tr>
						<td><?php echo ISSET($result['acct_no'])?$result['acct_no']:"&nbsp;";?></td>
						<td><?php echo str_replace('Ñ', 'N', $result['full_name']);?></td>
						<td class="td-right-middle"><?php echo number_format($result['amount'], 2);?></td>
					</tr>
					<?php endif;?>
					<?php endforeach;?>
				</tbody>
			</table>
			<table class="center-50">
				<tbody>
					<tr>
						<td class="align-c" height="50" colspan="3">********* END OF REGISTER *********</td>
					</tr>
					<tr>
						<td width="150">TOTAL NO. OF RECORDS</td>
						<td width="2">:</td>
						<td class="align-r"><?php echo $cnt;?></td>
					</tr>
					<tr>
						<td>TOTAL CREDIT AMOUNT</td>
						<td>:</td>
						<td class="align-r"><?php echo number_format($total, 2);?></td>
					</tr>
					<tr>
						<td>TOTAL ACCOUNT NO. HASH</td>     
						<td>:</td>
						<td class="align-r"><?php echo number_format($account_hash, 2);?></td>
					</tr>
					<tr>
						<td>TOTAL CHECK FIELD HASH</td>   
						<td>:</td>
						<td class="align-r"><?php echo number_format(preg_replace("/[^0-9.]/", "",$check_hash), 2);?></td>
					</tr>   
				</tbody>
			</table>
			<br>
			<table class="table-max" border="0">
				<tbody>
					<tr>
						<td class="valign-top" height="50" width="30%">CERTIFIED CORRECT:</td>
						<td rowspan="3" width="2%"></td>
						<td class="valign-top">APPROVED:</td>
						<td rowspan="3" width="2%"></td>
						<td class="valign-top" width="30%">CERTIFIED CASH AVAILABLE:</td>
					</tr>
					<tr>
						<td class="bold align-c td-border-bottom f-size"><?php echo $certified_by['signatory_name'];?></td>
						<td class="bold align-c td-border-bottom f-size"><?php echo $approved_by['signatory_name'];?></td>
						<td class="bold align-c td-border-bottom f-size"><?php echo $certified_cash_by['signatory_name'];?></td>
					</tr>
					<tr>
						<td class="align-c f-size"><?php echo $certified_by['position_name'] . ", " . $certified_by['office_name'];?></td>
						<td class="align-c f-size"><?php echo $approved_by['position_name'] . ", " . $approved_by['office_name'];?></td>
						<td class="align-c f-size"><?php echo $certified_cash_by['position_name'] . ", " . $certified_cash_by['office_name'];?></td>
					</tr>
				</tbody>
			</table>
		<?php endif;?> <!-- jendaigo : separate data extraction for pdf and excel -->
	</div>
<?php endforeach;?>
</body>
<?php else :?>

<div class="wrapper">
	<form id="test_report">
		<p style="text-align: center">No data available.</p>
	</form>
</div>

<?php endif;?>
</html>

