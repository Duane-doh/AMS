<html>
<head>
	<title>Employee List by Position Level</title>
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
		<td class="td-center-bottom f-size-12" colspan=6 height="50" valign=bottom><b>LIST AND NUMBER OF EMPLOYEES BY POSITION LEVEL<br><?php echo $date_hdr ?></b></td>
	</tr>
	<tr>
		<td colspan="6" height="20" align="left" valign=top></td>
	</tr>
	<tr>
		<td colspan="4" height="15" align="left" valign=bottom><b>Position Level: <?php echo strtoupper($records[0]['position_level_name'] ? $records[0]['position_level_name'] : $level['position_level_name'])?></b></td>
		<td colspan="2" align="right" valign=bottom><b>Count: <?php echo (!EMPTY($records)) ? count($records) : 0?></b></td>
	</tr>
</table>
<table class="table-max">
	<thead>
		<tr>
			<td colspan="1" class="td-border-3 td-center-middle" height="30"><b>Employee Number</b></td>
			<td colspan="2" class="td-border-3 td-center-middle"><b>Employee Name</b></td>
			<td colspan="2" class="td-border-3 td-center-middle"><b>Position</b></td>
			<td colspan="1" class="td-border-4 td-center-middle" width="150"><b>Employment Status</b></td>
		</tr>		
	</thead>
	<?php if($office): ?>
		<?php foreach ($office as $off): ?>
			<tr>
				<td class="bold border-light pad-2" colspan="6" height="20" align="left" valign=middle><b>OFFICE: <?php echo strtoupper($off['office'])?></b></td>
			</tr>
			<?php if($records): ?>
				<?php foreach ($records AS $record): ?>
					<?php if($record['office_id'] == $off['office_id']): ?>
						<tr>
							<td colspan="1" class="pad-2 td-border-3 td-border-light-top " height="20"><?php echo $record['personnel_number']?></td>
							<td colspan="2" class="pad-2 td-border-3 td-border-light-top "><?php echo $record['employee_name']?></td>
							<td colspan="2" class="pad-2 td-border-3 td-border-light-top "><?php echo strtoupper($record['position_name'])?></td>
							<td colspan="1" class="pad-2 td-border-4 td-border-light-top "><?php echo strtoupper($record['service_status'])?></td>
						</tr>
					<?php endif;?>
				<?php endforeach;?>
			<?php else: ?>
				<tr>
					<td colspan="6" class="td-border-light-bottom td-border-light-left td-border-light-right td-center-middle" height="30"><b>No Records Found.</b></td>
				</tr>
			<?php endif;?>
		<?php endforeach;?>	
	<?php else: ?>
		<?php if($agency): ?>
			<tr>
				<td class="bold border-light pad-2" colspan="6">OFFICE: <?php echo strtoupper($agency['name'])?></td>
			</tr>
		<?php endif;?>
		<tr>
			<td colspan="6" class="td-border-light-bottom td-border-light-left td-border-light-right td-center-middle" height="30"><b>No Records Found.</b></td>
		</tr>
	<?php endif;?>

</table>
<!-- ************************************************************************** -->
</body>

</html>
