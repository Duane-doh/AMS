<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<html>
<head>
	
	<title>Monthly Report on Separation</title>
	<link rel="stylesheet" href="<?php echo base_url().PATH_CSS ?>reports.css" type="text/css" />
	
</head>

<body>
<!-- <table class="f-size-12 table-max">
	<tbody>
		<tr>
			<td width="20%"><?php echo nbs(10) ?><img src="<?php echo base_url().PATH_IMG ?>doh_logo.png" width=90 height=90></img></td>
			<td align="center" width="60%">Republic of the Philippines<br>Department of Health<br>Civil Service Commission</td>
			<td width="20%">&nbsp;</td>
		</tr>
	</tbody>
</table> -->

<table class="table-max">
	<thead>
			<tr>		
				<td colspan=3 height="20" align="right" valign=top colspan=8>OPMIS Separation Form 4</td>
			</tr>
			<tr>
				<td width="20%">Title of Report:</td>
				<td width="60%" height="13" align="center" valign=bottom colspan=6><b>REPORT ON SEPARATION</b></td>	
				<td width="20%"></td>
			</tr>
			<tr>
				<td>Period Covered:</td>
				<td height="13" align="center" valign=middle colspan=6><b><?php echo strtoupper($date_hdr) ?></b></td>
				<td></td>	
			</tr>
			<tr>
				<td>Office:</td>
				<td height="13" align="center" valign=bottom colspan=6><b><?php echo $office_name ?></b></td>
				<td></td>
			</tr>
			<tr>		
				<td class="bold" colspan=3 height="30" align="left" valign=bottom>Agency:<u> <?php echo $office_name ?></td>
			</tr>
	<!-- </table> -->

	<!-- <table class="table-max"> -->
		
			<tr>
				<td class="td-border-top td-border-left td-center-middle td-border-bottom" width="230" height="54" align="center" valign=middle><b>OFFICE</b></td>
				<td class="td-border-top td-border-left td-center-middle td-border-bottom" width="230" height="54" align="center" valign=middle><b>NAME</b></td>
				<td class="td-border-top td-border-left td-center-middle td-border-bottom" width="180" align="center" valign=middle><b>POSITION TITLE</b></td>
				<td class="td-border-top td-border-left td-center-middle td-border-bottom" width="70" align="center" valign=middle><b>SG</b></td>
				<td class="td-border-top td-border-bottom td-border-left td-center-middle" width="50" align="center" valign=middle><b>POSITION<br>LEVEL</b></td>
				<td class="td-border-top td-border-left td-center-middle td-border-bottom" width="130" align="center" valign=middle><b>STATUS OF<br>APPOINTMENT</b></td>
				<td class="td-border-top td-border-left td-center-middle td-border-bottom" width="130" align="center" valign=middle><b>EFFECTIVITY<br>DATE OF<br>SEPARATION</b></td>
				<td class="td-border-top td-border-left td-border-right td-center-middle td-border-bottom" width="130" align="center" valign=middle><b>MODE OF<br>SEPARATION</b></td>
			</tr>
	</thead>
		<tbody>
			<?php if($office): ?>
				<?php foreach ($office as $off): ?>
					<?php if($agency['office_id'] == $off['office_id']): ?>
						<!-- PRINT WITHOUT OFFICE INSIDE TABLE -->
						<?php if($records): ?>
							<?php foreach ($records AS $record): ?>
								<?php if($record['office_id'] == $off['office_id']): ?>
									<tr>
										<td class="td-border-bottom td-border-top td-border-left" height="30"><?php echo $off['offices'] ?></td>
										<td class="td-border-bottom td-border-top td-border-left"><?php echo $record['employee_name'] ?></td>
										<td class="td-border-bottom td-border-top td-border-left"><?php echo strtoupper($record['position_name']) ?></td>
										<td class="td-border-bottom td-border-top td-border-left" align="center" valign=middle><?php echo $record['salary_grade'] ?></td>
										<td class="td-border-bottom td-border-left" align="center" valign=middle><?php echo strtoupper($record['position_level_name'])?></td>
										<td class="td-border-bottom td-border-top td-border-left"  valign=middle><?php echo $record['employment_status_name'] ?></td>
										<td class="td-border-bottom td-border-top td-border-left td-center-middle" align="center"><?php echo $record['employ_end_date'] ?></td>
										<td class="td-border-bottom td-border-top td-border-left td-border-right" align="center" valign=middle><?php echo $record['separation_mode_name'] ?></td>
									</tr>
								<?php endif;?>
							<?php endforeach;?>
						<?php else: ?>
							<tr>
								<td colspan=6 class="td-border-bottom td-border-left td-border-right td-center-middle" height="30"><b>No Records Found.</b></td>
							</tr>
						<?php endif;?>
					<!-- PRINT WITH OFFICE INSIDE TABLE -->						
					<?php else: ?>							
					<?php if($records): ?>
							<?php foreach ($records AS $record): ?>
								<?php if($record['office_id'] == $off['office_id']): ?>
									<tr>
										<td class="td-border-bottom td-border-top td-border-left" height="30"><?php echo strtoupper($off['office'])?></td>
										<td class="td-border-bottom td-border-top td-border-left"><?php echo $record['employee_name'] ?></td>
										<td class="td-border-bottom td-border-top td-border-left"><?php echo strtoupper($record['position_name']) ?></td>
										<td class="td-border-bottom td-border-top td-border-left" align="center" valign=middle><?php echo $record['salary_grade'] ?></td>
										<td class="td-border-bottom td-border-left" align="center" valign=middle><?php echo strtoupper($record['position_level_name'])?></td>
										<td class="td-border-bottom td-border-top td-border-left" align="center" valign=middle><?php echo $record['employment_status_name'] ?></td>
										<td class="td-border-bottom td-border-top td-border-left td-center-middle" align="center"><?php echo $record['employ_end_date'] ?></td>
										<td class="td-border-bottom td-border-top td-border-left td-border-right" align="center" valign=middle><?php echo $record['separation_mode_name'] ?></td>
									</tr>
								<?php endif;?>
							<?php endforeach;?>
						<?php else: ?>
							<tr>
								<td colspan=6 class="td-border-bottom td-border-left td-border-right td-center-middle" height="30"><b>No Records Found.</b></td>
							</tr>
						<?php endif;?>
					<?php endif;?>
				<?php endforeach;?>
			<?php endif;?>
			<?php if (!$records): ?> 							
				<tr>
					<td colspan="8" class="td-border-bottom td-border-left td-border-right td-center-middle" height="30"><b>No Records Found.</b></td>
				</tr>
			<?php endif; ?>							
		</tbody>
</table>
<table class="table-max">
	<tr>
		<td colspan=2 align="left" valign=top><br>We hereby certify that the above information is true based on our official records.</td>
	</tr>
		<tr>
			<td height="40"></td>
		</tr>
		<tr>
			<td height="20" align="center" valign="middle" width="50%"><b><?php echo strtoupper($approved_by['signatory_name']); ?></b></td>
			<td height="20" align="center" valign="middle" width="50%"><b><?php echo strtoupper($certified_by['signatory_name']); ?></b></td>
		</tr>
		<tr>
			<td height="20" align="center" valign="top"><?php echo $approved_by['position_name'] ?><br><?php echo $approved_by['office_name'] ?></td>
			<td height="20" align="center" valign="top"><?php echo $certified_by['position_name'] ?><br><?php echo $certified_by['office_name']?></td>
		</tr>
</table>
<!-- ************************************************************************** -->
</body>

</html>
v