<!DOCTYPE html>
<html>
<?php if(count($results)>0):?>
<head>
	<title>ATM Alpha List</title>
	<link rel="stylesheet" href="<?php echo base_url().PATH_CSS ?>reports.css" type="text/css" />
</head>
<body>
<?php foreach ($effective_date as $date):?>
<div>
<table class="table-max" border="0" class="f-size-12pt">
	<thead><!-- 
	 	<tr>
            <td width="20%"><?php echo nbs(10) ?><img src="<?php echo base_url().PATH_IMG ?>doh_logo.png" width=90 height=90></img><br></td>
            <td colspan="3" class="f-size-12pt" align="center" width="60%">Republic of the Philippines<br>DEPARTMENT OF HEALTH</td>
            <td width="20%">&nbsp;</td>
        </tr> -->
		<tr>
			<td colspan="5" class="align-c f-size-12pt"><b>ATM Alpha List</b></td>
		</tr>	
		<tr>
			<td colspan="5" class="align-c"><b><?php echo date("F j, Y", strtotime($date['effective_date']));?></b></td>
		</tr>
		<tr>
			<td colspan="5" class="valign-bot align-c"><br><?php echo $bank['bank_name'];?><br>DATA BASE REPORT<br><?php echo $bank['bank_name'] . " - [CODE : " . $bank['branch_code'] . "]";?><br>BATCH <?php echo $batch_code;?></td>
		</tr>	
		<tr>
			<th class="align-l" height="30" width="200">ACCOUNT NO.</th>
			<th class="align-l" width="">ACCOUNT NAME</th>
			<th class="align-c" width="150">CREDIT AMOUNT</th>
			<th class="align-c" width="200">BRANCH</th>
			<th class="align-c" width="130">BATCH</th>
		</tr>
	</thead>
	<tbody>
	
		<?php
			$total = 0;
			$cnt        = 0;
			foreach ($results[$date['effective_date']] as $result):
			if($result['amount'] > 0)
			{
				$total += $result['amount'];
				$cnt ++;
			}
		?>
		<?php if( $result['amount'] > 0):?>
		<tr>
			<td class="pad-2"><?php echo ISSET($result['acct_no'])?$result['acct_no']:"&nbsp;";?></td>
			<td class="pad-2"><?php echo str_replace('Ñ', 'N', $result['full_name']);?></td>
			<td class="td-right-middle pad-2"><?php echo number_format($result['amount'], 2);?></td>
			<td class="td-center-middle pad-2"><?php echo ISSET($bank['branch_code'])?$bank['branch_code']:"&nbsp;";?></td>
			<td class="td-center-middle pad-2"><?php echo ISSET($bank['branch_detail_code']) ? $bank['branch_detail_code']:"&nbsp;";?></td>
		</tr>
		<?php endif;?>
		<?php endforeach;?>
	</tbody>
</table>

<table class="center-85  f-size-12pt">
	<tbody>
		<tr>
			<td class="align-c" height="60" colspan="4">********* END OF DATA BASE REPORT *********</td>
		</tr>
		<tr>
			<td width="350" class="align-r">TOTAL NO. OF RECORDS</td>
			<td width="80"  class="align-r">:</td>
			<td class="align-r" width="350"><?php echo $cnt;?></td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td class="align-r">TOTAL CREDIT AMOUNT</td>
			<td class="align-r">:</td>
			<td class="align-r"><?php echo number_format($total, 2);?></td>
			<td>&nbsp;</td>
		</tr>
	</tbody>
</table>
<br>
<table class="table-max f-size-12pt" border="0" >
	<tbody>
		<tr>
			<td class="valign-top" height="50"  >CERTIFIED CORRECT:</td>
			<td rowspan="3" width="3%"></td>
			<td class="valign-top">APPROVED:</td>
			<td rowspan="3" width="3%"></td>
			<td class="valign-top">CERTIFIED CASH AVAILABLE:</td>
		</tr>
		<tr>
			<td class="bold align-c td-border-bottom"><?php echo $certified_by['signatory_name'];?></td>
			<td class="bold align-c td-border-bottom"><?php echo $approved_by['signatory_name'];?></td>
			<td class="bold align-c td-border-bottom"><?php echo $certified_cash_by['signatory_name'];?></td>
		</tr>
		<tr>
			<td class="align-c"><?php echo $certified_by['position_name'] . ", " . $certified_by['office_name'];?></td>
			<td class="align-c"><?php echo $approved_by['position_name'] . ", " . $approved_by['office_name'];?></td>
			<td class="align-c"><?php echo $certified_cash_by['position_name'] . ", " . $certified_cash_by['office_name'];?></td>
		</tr>
	</tbody>
</table>
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