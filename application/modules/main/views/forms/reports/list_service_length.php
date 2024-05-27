<?php 
	
	$service_length = '';
	if($to >= 12)
	{		
		$year_month = $to/12;
		$years = floor($year_month);
		$months = ($year_month - $years) * 12;
		$service_length = $years . ' year(s) and ' . $months . ' month(s)';
	}
	else
	{
		$service_length = $to . ' month(s)';
	}
				
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<html>
<head>
	
	<title>Employee List by Length of Service</title>
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
		<td class="td-center-bottom f-size-12" colspan=4 height="50" valign=bottom><b>LIST AND NUMBER OF EMPLOYEES BY LENGTH OF SERVICE<br>As of <?php echo strtoupper(date_format(date_create($date), 'F d, Y')) ?></b></td>
	</tr>
	<tr>
		<td colspan=2 height="20" align="left" valign=top></td>
	</tr>
	<tr>
		<td colspan=2 height="15" align="left" valign=bottom><b>Length of Service: <?php echo strtoupper($service_length) ?></b></td>
		<td colspan=2 align="right" valign=bottom><b>Count: <?php echo count($records)?></b></td>
	</tr>
</table>
<table class="table-max">
	<thead>
		<tr>
			<td class="td-border-light-top td-border-light-bottom td-border-light-left td-border-light-right td-center-middle" height="30" width="100"><b>Employee Number</b></td>
			<td class="td-border-light-top td-border-light-bottom td-border-light-right td-center-middle"><b>Employee Name</b></td>
			<td class="td-border-light-top td-border-light-bottom td-border-light-right td-center-middle"><b>Position</b></td>
			<td class="td-border-light-top td-border-light-bottom td-border-light-right td-center-middle"><b>Position Level</b></td>
		</tr>	
	</thead>
		<?php if($office): ?>
			<?php foreach ($office as $off): ?>		
					<tr>
						<td class="bold border-light pad-2" colspan=4 height="20" align="left" valign=middle><b>OFFICE: <?php echo strtoupper($off['office'])?></b></td>
					</tr>	
				<?php if($records): ?>
					<?php foreach ($records AS $record): ?>
						<?php if($record['office_id'] == $off['office_id']): ?>
							<tr>
								<td class="pad-2 td-border-light-bottom td-border-light-top td-border-light-left td-border-light-right" height="20" width="100"><?php echo $record['personnel_number']?></td>
								<td class="pad-2 td-border-light-bottom td-border-light-top td-border-light-right"><?php echo $record['employee_name']?></td>
								<td class="pad-2 td-border-light-bottom td-border-light-top td-border-light-right"><?php echo strtoupper($record['position_name'])?></td>
								<td class="pad-2 td-border-light-bottom td-border-light-top td-border-light-right" width="100"><?php echo strtoupper($record['position_level_name'])?></td>
							</tr>
						<?php endif;?>
					<?php endforeach;?>
				<?php else: ?>
					<tr>
						<td colspan="4" class="td-border-light-bottom td-border-light-left td-border-light-right td-center-middle" height="30"><b>No Records Found.</b></td>
					</tr>
				<?php endif;?>
			<?php endforeach;?>
		<?php else: ?>
			<?php if($agency): ?>
				<tr>
					<td class="bold border-light pad-2" colspan="4">OFFICE: <?php echo strtoupper($agency['name'])?></td>
				</tr>
			<?php endif;?>
			<tr>
				<td colspan="4" class="td-border-light-bottom td-border-light-left td-border-light-right td-center-middle" height="30"><b>No Records Found.</b></td>
			</tr>
		<?php endif;?>

</table>
<!-- ************************************************************************** -->
</body>

</html>