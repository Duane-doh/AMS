<?php 
	$colspan = 7;
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<html>
<head>
	
	<title>Transferees</title>
	<link rel="stylesheet" href="<?php echo base_url().PATH_CSS ?>reports.css" type="text/css" />
</head>

<body>
<table class="table-max">
    <tbody>
        <tr>
            <td align="right" width="25%"><?php echo nbs(10) ?><img src="<?php echo base_url().PATH_IMG ?>doh_logo.png" width=90 height=90></img></td>
            <td class="align-c f-size-notice" width="50%">Republic of the Philippines<br><span class="f-size-notice">Department of Health</span><br><span class="f-size-14-notice bold">OFFICE OF THE SECRETARY</span></td>                
            <td width="25%"><br></td>
        </tr>
    </tbody>
</table>
<table class="table-max">
	<tr>
		<td class="td-center-bottom f-size-12" colspan=<?php echo $colspan?> height="50" valign=bottom><b>TRANSFEREE/S <?php echo ($header != MOVT_TRANSFER_PROMOTION) ? $header : 'WITH PROMOTION' ?><br><?php echo $date_hdr?></b></td>
	</tr>	
	<tr>
		<td colspan=2 height="20" align="left" valign=top></td>
	</tr>
	<tr>
		<td colspan=<?php echo $colspan?> align="left"><b>Count: <?php echo (!EMPTY($records)) ? count($records) : 0?></b></td>
	</tr>
</table>
<table class="table-max">
	<thead>		
		<tr>
			<td class="td-border-light-top td-border-light-bottom td-border-light-left td-border-light-right td-center-middle" height="30" width="60"><b>Employee Number</b></td>
			<td class="td-border-light-top td-border-light-bottom td-border-light-right td-center-middle" width="120"><b>Employee Name</b></td>
			<?php if($header != MOVT_TRANSFER_PROMOTION): ?>
				<td class="td-border-light-top td-border-light-bottom td-border-light-right td-center-middle" width="130"><b>From</b></td>
				<td class="td-border-light-top td-border-light-bottom td-border-light-right td-center-middle" width="130"><b>To</b></td>
				<td class="td-border-light-top td-border-light-bottom td-border-light-right td-center-middle"><b><?php echo ($header == TRANSFER_IN) ? 'Date of Transfer' : 'Last Day of Service'?></b></td>
			<?php else: ?>
				<td class="td-border-light-top td-border-light-bottom td-border-light-right td-center-middle" width="130"><b>From</b></td>
				<td class="td-border-light-top td-border-light-bottom td-border-light-right td-center-middle" width="130"><b>To</b></td>		
				<td class="td-border-light-top td-border-light-bottom td-border-light-right td-center-middle"><b>Effectivity Date</b></td>
			<?php endif;?>
			<td class="td-border-light-top td-border-light-bottom td-border-light-right td-center-middle"><b>Position</b></td>
			<td class="td-border-light-top td-border-light-bottom td-border-light-right td-center-middle"><b>Employment Status</b></td>
		</tr>
	</thead>
	<?php if($office): ?>
		<?php foreach ($office as $off): ?>
			<tr>
				<td class="border-light pad-2" colspan='<?php echo $colspan?>' height="20" align="left" valign=middle><b>OFFICE: <?php echo strtoupper($off['office'])?></b></td>
			</tr>
			<?php if($records): ?>
				<?php foreach ($records AS $record): ?>
					<?php if(strtolower($record['office']) == strtolower($off['office'])): ?>
						<tr>
							<td class="pad-2 td-border-light-bottom td-border-light-left td-border-light-right" height="20"><?php echo $record['personnel_number']?></td>
							<td class="pad-2 td-border-light-bottom td-border-light-right"><?php echo $record['employee_name']?></td>					
							<td class="pad-2 td-border-light-bottom td-border-light-right"><?php echo ($record['transfer_flag'] == TRANSFER_IN) ? strtoupper($record['transfer_to']) : strtoupper($record['office'])?></td>
							<td class="pad-2 td-border-light-bottom td-border-light-right"><?php echo ($record['transfer_flag'] == TRANSFER_OUT) ? strtoupper($record['transfer_to']) : strtoupper($record['office'])?></td>
							<td class="pad-2 td-border-light-bottom td-border-light-right td-center-middle" width="60"><?php echo ($record['transfer_flag'] == TRANSFER_IN) ? date_format(date_create($record['employ_start_date']), 'm/d/Y') : date_format(date_create($record['employ_end_date']), 'm/d/Y')?></td>
							<td class="pad-2 td-border-light-bottom td-border-light-right"><?php echo strtoupper($record['position_name'])?></td>
							<td class="pad-2 td-border-light-bottom td-border-light-right"><?php echo $record['service_status']?></td>
						</tr>
					<?php endif;?>
				<?php endforeach;?>
			<?php else: ?>
				<tr>
					<td colspan='<?php echo $colspan?>' class="td-border-light-bottom td-border-light-left td-border-light-right td-center-middle" height="30"><b>No Records Found.</b></td>
				</tr>
			<?php endif;?>
		<?php endforeach;?>	
	<?php else: ?>
		<?php if($agency): ?>
			<tr>
				<td class="bold border-light pad-2" colspan='<?php echo $colspan?>'>OFFICE: <?php echo strtoupper($agency['name'])?></td>
			</tr>
		<?php endif;?>
		<tr>
			<td colspan='<?php echo $colspan?>' class="td-border-light-bottom td-border-light-left td-border-light-right td-center-middle" height="30"><b>No Records Found.</b></td>
		</tr>
	<?php endif;?>

</table>
<!-- ************************************************************************** -->
</body>

</html>
