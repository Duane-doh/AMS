<?php 
	$month_to_word = array(
		1=>'January',
		2=>'February',
		3=>'March',
		4=>'April',
		5=>'May',
		6=>'June',
		7=>'July',
		8=>'August',
		9=>'September',
		10=>'October',
		11=>'November',
		12=>'December'
	);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<html>
<head>
	
	<title>Personnel Movement</title>
	<link rel="stylesheet" href="<?php echo base_url().PATH_CSS ?>reports.css" type="text/css" />
</head>

<body>
<!-- <table class="f-size-12">
	<tbody>
		<tr>
			<td width="390"></td>
			<td><img src="<?php echo base_url().PATH_IMG ?>doh_logo.png" width=61 height=61></img></td>
			<td align="center"><?php echo nbs(5)?>Republic of the Philippines<br><?php echo nbs(5)?>Department of Health</td>
			<td></td>
		</tr>
	</tbody>
</table> -->
<table class="table-max">
	<tr>
		<td class="bold f-size-24" align="center" colspan=12>LIST OF DOH-CO EMPLOYEES WHO WERE APPOINTED FOR THE PERIOD OF <br><?php echo strtoupper($date_hdr) ?></td>
	</tr>
	<tr>
		<td class="f-size-12 bold" align="center" height="30">per CSC MC # 03 S. 2001</td>
	</tr>
	<tr>
		<td class="f-size-12 bold" align="center" height="20">&nbsp;</td>
	</tr>

</table>

<table class="table-max">
	<thead>
		<tr>
			<td class="td-border-top td-border-left td-center-middle td-border-bottom bold f-size-12">No.</td>
			<td class="td-border-top td-border-left td-center-middle td-border-bottom f-size-12"><b>Name</b></td>
			<td class="td-border-top td-border-left td-center-middle td-border-bottom f-size-12"><b>Position</b></td>
			<td class="td-border-top td-border-left td-center-middle td-border-bottom f-size-12"><b>Office</b></td>
			<td class="td-border-top td-border-left td-center-middle td-border-bottom f-size-12"><b>Nature of<br>Appointment</b></td>
			<td class="td-border-top td-border-left td-border-right td-center-middle td-border-bottom f-size-12"><b>Date of Issuance of <br>Appointment</b></td>
		</tr>
	</thead>
	<tbody>
		<?php if($records): ?>
			<?php 
			$cnt = 1;
			foreach ($records AS $record): ?>
				<tr>
					<td class="td-border-left td-border-bottom td-border-right td-center-middle f-size-12"><?php echo $cnt ?></td>
					<td class="td-border-left td-border-bottom pad-2 f-size-12"><?php echo strtoupper($record['employee_name']) ?></td>
					<td class="td-border-left td-border-bottom td-center-middle f-size-12"><?php echo strtoupper($record['position_name']) ?></td>
					<td class="td-border-left td-border-bottom td-center-middle pad-2 f-size-12"><?php echo strtoupper($record['name']) ?></td>
					<td class="td-border-left td-border-bottom td-center-middle pad-2 f-size-12"><?php echo strtoupper($record['personnel_movement_name']) ?></td>
					<td class="td-border-left td-border-bottom td-border-right td-center-middle f-size-12"><?php echo strtoupper(date_format(date_create($record['employ_start_date']), 'F d, Y') )?></td>
				</tr>
			<?php 
			$cnt++;
			endforeach;?>
		<?php else: ?>
			<tr>
				<td colspan=6 class="td-border-bottom td-border-left td-border-right td-center-middle" height="30"><b>No Records Found.</b></td>
			</tr>
		<?php endif;?>
		<tr>
			<td colspan=6 height="5"></td>
		</tr>
		<tr>
			<td colspan=6 class="f-size-14 align-j">Note: Only qualified next-in-rank applicants shall have the right to appeal or file a protest initially to the appointing authority as per Civil Service Commission Memorandum Circular No. 4 date Feruary 2010.</td>
		</tr>
		<tr>
			<td colspan=6 height="40"></td>
		</tr>
	</tbody>
</table>
<table class="center-85">
	<tr>
		<td height="21" class="td-center-middle f-size-12" width="50%">Prepared by:</td>
		<td class="td-center-middle f-size-12" width="50%">Reviewed by:</td>
	</tr>
	<tr>	
		<td height="10"></td>
	</tr>
	<tr>
		<td class="td-center-middle f-size-12" height="81" ><b><?php echo $prepared_by['signatory_name']; ?></b><br><?php echo $prepared_by['position_name'] ?><br><?php echo $prepared_by['office_name']?></td>
		<td class="td-center-middle f-size-12"><b><?php echo $certified_by['signatory_name']; ?></b><br><?php echo $certified_by['position_name'] ?><br><?php echo $certified_by['office_name'] ?></td>
	</tr>
</table>
<!-- ************************************************************************** -->
</body>

</html>
