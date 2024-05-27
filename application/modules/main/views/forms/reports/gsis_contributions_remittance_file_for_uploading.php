
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<html>
<?php if(count($gsis_remittances)>0):?>
<head>
	<title>GSIS Contributions Remittance File for Uploading</title>
	<link rel="stylesheet" href="<?php echo base_url().PATH_CSS ?>reports.css" type="text/css" />
</head>
<body>
	<table class="table-max f-size">
		<tr>
			<td width="70">Remitting Agency</td>
			<td><?php echo $agency['sys_param_value']?></td>
		</tr>
		<tr>
			<td>Office Code</td>
			<td><?php echo $code['sys_param_value']?></td>
		</tr>
		<tr>
			<td>Due Month</td>
			<td><?php echo $gsis_remittances[0]['date_processed']?></td>			
		</tr>
		<tr><!-- 
			<td class="bold border-light align-c" height="15" width="100">PolicyNo</td> -->
			<td class="bold border-light-left align-c" width="100">BPNO</td>
			<td class="bold border-light-left align-c" width="300">LastName</td>
			<td class="bold border-light-left align-c" width="300">FirstName</td>
			<td class="bold border-light-left align-c" width="100">MI</td>
			<td class="bold border-light-left align-c" width="100">PREFIX</td>
			<td class="bold border-light-left align-c" width="100">APPELLATION</td>
			<td class="bold border-light-left align-c" width="100">BIRTHDATE</td>
			<td class="bold border-light-left align-c" width="100">CRN</td>
			<td class="bold border-light-left align-c" width="100">BASIC</td>
			<td class="bold border-light-left align-c" width="100">EFFECTIVITY</td>
			<td class="bold border-light-left align-c" width="100">PS</td>
			<td class="bold border-light-left align-c" width="100">GS</td>
			<td class="bold border-light-left align-c" width="100">EC</td>
			<td class="bold border-light-left align-c" width="100">CONSOLOAN</td>
			<td class="bold border-light-left align-c" width="100">ECARDPLUS</td>
			<td class="bold border-light-left align-c" width="100">SALARY_LOAN</td>
			<td class="bold border-light-left align-c" width="100">CASH_ADV</td>
			<td class="bold border-light-left align-c" width="100">EMRGYLN</td>
			<td class="bold border-light-left align-c" width="100">EDUC_ASST</td>
			<td class="bold border-light-left align-c" width="100">ELA</td>
			<td class="bold border-light-left align-c" width="100">SOS</td>
			<td class="bold border-light-left align-c" width="100">PLREG</td>
			<td class="bold border-light-left align-c" width="100">PLOPT</td>
			<td class="bold border-light-left align-c" width="100">REL</td>
			<td class="bold border-light-left align-c" width="100">LCH_DCS</td>
			<td class="bold border-light-left align-c" width="100">STOCK_PURCHASE</td>
			<td class="bold border-light-left align-c" width="100">OPT_LIFE</td>
			<td class="bold border-light-left align-c" width="100">CEAP</td>
			<td class="bold border-light-left align-c" width="100">EDU_CHILD</td>
			<td class="bold border-light-left align-c" width="100">GENESIS</td>
			<td class="bold border-light-left align-c" width="100">GENPLUS</td>
			<td class="bold border-light-left align-c" width="100">GENFLEXI</td>
			<td class="bold border-light-left align-c" width="100">GENSPCL</td>
			<td class="bold border-light-left align-c" width="100">HELP</td>
		</tr>

		<?php foreach ($gsis_remittances as $gsis_remittance): ?>
			<tr>
				<td class="border-light-top-left"><?php echo $gsis_remittance['pin']?></td>
				<td class="border-light-top-left"><?php echo str_replace('Ñ', 'N', $gsis_remittance['last_name'])?></td>
				<td class="border-light-top-left"><?php echo str_replace('Ñ', 'N', $gsis_remittance['first_name'])?></td>
				<td class="border-light-top-left"><?php echo $gsis_remittance['middle_init']?></td>
				<td class="border-light-top-left"><?php echo !EMPTY($gsis_remittance['ext_name']) ? $gsis_remittance['ext_name'] : '' ?></td>
				<td class="border-light-top-left"></td>
				<td class="border-light-top-left"><?php echo $gsis_remittance['birth_date']?></td>
				<td class="border-light-top-left"><?php echo $gsis_remittance['crn']?></td>
				<td class="border-light-top-left"><?php echo $gsis_remittance['basic_salary']?></td>
				<td class="border-light-top-left"><?php echo $gsis_remittance['effectivity_date']?></td>
				<td class="border-light-top-left align-r"><?php echo number_format($gsis_remittance['ps'], 2)?></td>
				<td class="border-light-top-left align-r"><?php echo number_format($gsis_remittance['gs'], 2)?></td>
				<td class="border-light-top-left align-r"><?php echo number_format($gsis_remittance['ec'], 2)?></td>
				<td class="border-light-top-left align-r"><?php echo number_format($gsis_remittance['consoloan'], 2)?></td>
				<td class="border-light-top-left align-r"><?php echo number_format($gsis_remittance['ecardplus'], 2)?></td>
				<td class="border-light-top-left align-r"><?php echo number_format($gsis_remittance['salary_loan'], 2)?></td>
				<td class="border-light-top-left align-r"><?php echo number_format($gsis_remittance['cash_adv'], 2)?></td>
				<td class="border-light-top-left align-r"><?php echo number_format($gsis_remittance['emrgyln'], 2)?></td>
				<td class="border-light-top-left align-r"><?php echo number_format($gsis_remittance['educ_asst'], 2)?></td>
				<td class="border-light-top-left align-r"><?php echo number_format($gsis_remittance['ela'], 2)?></td>
				<td class="border-light-top-left align-r"><?php echo number_format($gsis_remittance['sos'], 2)?></td>
				<td class="border-light-top-left align-r"><?php echo number_format($gsis_remittance['plreg'], 2)?></td>
				<td class="border-light-top-left align-r"><?php echo number_format($gsis_remittance['plopt'], 2)?></td>
				<td class="border-light-top-left align-r"><?php echo number_format($gsis_remittance['rel'], 2)?></td>
				<td class="border-light-top-left align-r"><?php echo number_format($gsis_remittance['lch_dcs'], 2)?></td>
				<td class="border-light-top-left align-r"><?php echo number_format($gsis_remittance['stock_purchase'], 2)?></td>
				<td class="border-light-top-left align-r"><?php echo number_format($gsis_remittance['opt_life'], 2)?></td>
				<td class="border-light-top-left align-r"><?php echo number_format($gsis_remittance['ceap'], 2)?></td>
				<td class="border-light-top-left align-r"><?php echo number_format($gsis_remittance['edu_child'], 2)?></td>
				<td class="border-light-top-left align-r"><?php echo number_format($gsis_remittance['genesis'], 2)?></td>
				<td class="border-light-top-left align-r"><?php echo number_format($gsis_remittance['genplus'], 2)?></td>
				<td class="border-light-top-left align-r"><?php echo number_format($gsis_remittance['genflexi'], 2)?></td>
				<td class="border-light-top-left align-r"><?php echo number_format($gsis_remittance['genspcl'], 2)?></td>
				<td class="border-light-top-left align-r"><?php echo number_format($gsis_remittance['helps'], 2)?></td>
			</tr>
		<?php endforeach;?>
		
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