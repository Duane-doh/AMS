<?php 
$label = ($employee_info['gender_code'] == 'F') ? 'MS. ' : 'MR. ';
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<html>
<head>
	
	<title>Philhealth Certificate of Contribution</title>
	<link rel="stylesheet" href="<?php echo base_url().PATH_CSS ?>reports.css" type="text/css" />
</head>
<body>
<div  style="margin: 5mm">
	<table class="table-max f-size-12">
		<tbody>
			<tr>
				<td width="20%"><?php echo nbs(10) ?><img src="<?php echo base_url().PATH_IMG ?>doh_logo.png" width=90 height=90></img></td>
				<td class="align-c" width="60%">Republic of the Philippines<br><span style="font-size: 12pt">Department of Health</span><br><span class="bold" style="font-size: 14pt">OFFICE OF THE SECRETARY</span></td>				
				<td width="20%"><!-- <img src="<?php echo base_url().PATH_IMG ?>health for all logo.jpg" width=90 height=90></img> --></td>
			</tr>
		</tbody>
	</table>

	<table class="f-size-12">
		<tr>
			<td colspan=5 height="40"></td>
		</tr>
		<tr>
			<td class="align-r" colspan=5 ><?php echo date('F d, Y'); ?></td>
		</tr>
		<tr>
			<td colspan=5 height="10"></td>
		</tr>
		<tr>
			<td colspan=5 height="21" class="bold" align="center" valign=middle style="font-size: 16pt">C E R T I F I C A T I O N</td>
		</tr>
		<tr>
			<td colspan=5 height="30"></td>
		</tr>
		<tr>
			<td colspan=5 height="40" align="justify" valign=middle><p><?php echo nbs(10) ?>This is to certify that according to the records of this Office the PHILHEALTH CONTRIBUTION deducted from the salaries of <b> <?php echo $label?> <?php echo $employee_info['employee_name']?> (<?php echo $employee_info['other_deduction_detail_value']?>)</b> 
			of the <?php echo $office_name ?>, this Department were remitted to Philippine Health Insurance Corporation are as follows:
			</p></td>
		</tr>
		<tr>
			<td colspan=5 height="20"></td>
		</tr>
		<tr>
			<td colspan=1 height="20" class="td-border-top td-border-bottom td-border-left td-border-right" align="center" valign=middle>PERIOD</td>
			<td colspan=1 height="20" class="td-border-top td-border-bottom td-border-right" align="center" valign=middle>PERSONAL SHARE</td>
			<td colspan=1 height="20" class="td-border-top td-border-bottom td-border-right" align="center" valign=middle>EMPLOYEE SHARE</td>
			<td colspan=1 height="20" class="td-border-top td-border-bottom td-border-right" align="center" valign=middle>O.R. #</td>
			<td colspan=1 height="20" class="td-border-top td-border-bottom td-border-right" align="center" valign=middle>DATE</td>
		</tr>
		
		<?php if($records): ?>
			<?php foreach ($records as $record): ?>
				<tr>
					<td colspan=1 height="20" class="td-border-top td-border-bottom td-border-left td-border-right" align="center" valign=middle><?php echo $record['effective_date']; ?></td>
					<td colspan=1 height="20" class="td-border-top td-border-bottom td-border-right" align="center" valign=middle><?php echo $record['amount']; ?></td>
					<td colspan=1 height="20" class="td-border-top td-border-bottom td-border-right" align="center" valign=middle><?php echo $record['employer_amount']; ?></td>
					<td colspan=1 height="20" class="td-border-top td-border-bottom td-border-right" align="center" valign=middle><?php echo $record['or_no']; ?></td>
					<td colspan=1 height="20" class="td-border-top td-border-bottom td-border-right" align="center" valign=middle><?php echo $record['or_date']; ?></td>
				</tr>
			<?php endforeach;?>
		<?php else: ?>
		<tr>
			<td colspan=5 class="td-border-light-top td-border-light-bottom td-border-light-left td-border-light-right td-center-middle" height="30">No Records Found.</td>
		</tr>
		<?php endif;?>

		<tr>
			<td colspan=5 height="20" align="center" valign="middle"></td>
		</tr>
		<tr>
			<td colspan=5 height="20" align="justify" valign="middle"><p><?php echo nbs(10) ?>This certification is being issued upon the request of <?php echo $label?><?php echo $employee_info['last_name']; ?> for whatever purpose it may serve.</p></td>
		</tr>	
		<tr>
			<td colspan=5 height="40"></td>
		</tr>
	</table>
	<table class="table-max f-size-12">
		<tr>
			<td height="20" align="left" valign="middle" width="50%">Prepared by:</td>
			<td height="20" align="left" valign="middle" width="50%">Certified correct:</td>
		</tr>		
		<tr>
			<td height="40"></td>
		</tr>
		<tr>
			<td height="20" align="left" valign="middle"><b><?php echo $prepared_by['signatory_name']; ?></b></td>
			<td height="20" align="left" valign="middle"><b><?php echo $certified_by['signatory_name']; ?></b></td>
		</tr>
		<tr>
			<td height="20" align="left" valign="top"><?php echo $prepared_by['position_name'] ?><br><?php echo $prepared_by['office_name']?></td>
			<td height="20" align="left" valign="top"><?php echo $certified_by['position_name'] ?><br><?php echo $certified_by['office_name'] ?></td>
		</tr>
	</table>
	
	</div>
<!-- ************************************************************************** -->
</body>
</html>