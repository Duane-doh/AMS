
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<html>
<head>
	<title>Statement of Leave Balances</title>
	 <link rel="stylesheet" href="<?php echo base_url().PATH_CSS ?>reports.css" type="text/css" />
</head>

<body>
<table class="table-max f-size-12">
		<tbody>
			<tr>
				<td class="align-c" colspan="6"><b>STATEMENT OF LEAVE BALANCES</b></td>
			</tr>
			<tr>
				<td class="align-c" colspan="6"><b><?php echo $agency['name']?></b></td>
			</tr>
			<tr>
				<td class="align-c" colspan="6">As of<?php echo nbs(2) ?><?php echo date('F d, Y')?></td>
			</tr>
		</tbody>
	</table>
	<br>
<table class="table-max cont-5 f-size-12">
	<thead>
		<tr>
			<td class="td-border-3 align-c valign-top" width= "5"><br></td>
			<td class="td-border-3 align-c valign-top" ><b>NAME</b></td>
			<td class="td-border-3 align-c valign-top" width= "70"><b>Vacation Leave</b></td>
			<td class="td-border-3 align-c valign-top" width= "70"><b>Sick Leave</b></td>
			<td class="td-border-3 align-c valign-top" width= "70"><b>Availed SPL</b></td>
			<td class="td-border-4 align-c valign-top" width= "70"><b>Availed Forced Leave</b></td>
		</tr>
	</thead>
	<tbody>
		
		<?php if($employee_dtl):
				foreach ($employee_dtl as $key => $dtl) :

		?>
		<tr>
			<td class="td-border-light-left td-border-light-bottom align-c valign-bot" align="center"><?php echo $key + 1;?></td>
			<td class="td-border-light-left td-border-light-bottom valign-bot"><?php echo isset($dtl['fullname']) ? $dtl['fullname']:"0"?></td>
			<td class="td-border-light-left td-border-light-bottom align-r valign-bot"><?php echo isset($dtl['total_vl']) ? $dtl['total_vl']:"0"?></td>
			<td class="td-border-light-left td-border-light-bottom align-r valign-bot"><?php echo isset($dtl['total_sl']) ? $dtl['total_sl']:"0"?></td>
			<td class="td-border-light-left td-border-light-bottom align-c valign-bot"><?php echo isset($dtl['availed_spl']) ? round($dtl['availed_spl'],3):"0"?></td>
			<td class="td-border-light-left td-border-light-right td-border-light-bottom align-c valign-bot"><?php echo isset($dtl['availed_fl']) ? round($dtl['availed_fl'],3):"0"?></td>
		</tr>
		<?php 	endforeach;?>

		<?php 	else:?>

		<tr>
			<td colspan="6" class="td-border-light-left td-border-light-right td-border-right td-border-light-bottom align-c valign-bot"><center>No Records Found.</center></td>
		</tr>
		<?php 	endif;

		?>
	</tbody>
</table>
<table class="table-max f-size-10">
		<tbody>
			<tr>
				<td class="align-c" colspan="6"><b>Note:</b> Only personnel with ten (10) days or more vacation leave credits shall be </td>
			</tr>
			<tr>
				<td class="align-c" colspan="6">required for five (5) days forced leave/mandatory leave.</td>
			</tr>
		</tbody>
	</table>
<table class="table-max">
	<tbody>
		<tr>
			<td class="td-left-bottom" colspan="6"><br></td>
		</tr>
		<tr>
			<td class="td-left-bottom" colspan="6"><br></td>
		</tr>
		<tr>
			<td class="td-left-bottom"><br></td>
			<td class="td-left-bottom" colspan="2">Prepared by:</td>
			<td class="td-left-bottom" width="100"><br></td>
			<td class="td-left-bottom" colspan="2">Verified by:</td>
		</tr>	
		<tr>
			<td class="td-left-bottom" colspan="6"><br></td>
		</tr>
		<tr>
			<td class="td-left-bottom"><br></td>
			<td class="td-left-bottom" colspan="2"><b><?php echo isset($prepared_by['signatory_name']) ? $prepared_by['signatory_name']:''; ?></b></td>
			<td class="td-left-bottom" width="100"><br></td>
			<td class="td-left-bottom" colspan="2"><b><?php echo isset($approved_by['signatory_name']) ? $approved_by['signatory_name']:''; ?></b></td>
		</tr>	
		<tr>
			<td class="td-left-bottom"><br></td>
			<td class="td-left-bottom" colspan="2"><?php echo isset($prepared_by['position_name']) ? $prepared_by['position_name']:''; ?></td>
			<td class="td-left-bottom" width="100"><br></td>
			<td class="td-left-bottom" colspan="2"><?php echo isset($approved_by['position_name']) ? $approved_by['position_name']:''; ?></td>
		</tr>
		<tr>
			<td class="td-left-bottom"><br></td>
			<td class="td-left-bottom" colspan="2"><?php echo isset($prepared_by['office_name']) ? $prepared_by['office_name']:''; ?></td>
			<td class="td-left-bottom" width="100"><br></td>
			<td class="td-left-bottom" colspan="2"><?php echo isset($approved_by['office_name']) ? $approved_by['office_name']:''; ?></td>
		</tr>
	</tbody>
</table>
</body>
</html>