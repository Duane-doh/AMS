<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
	
	<title>List of Employees Not Included in Payroll</title>
	<link rel="stylesheet" href="<?php echo base_url().PATH_CSS ?>reports.css" type="text/css" />
</head>
<body>
	<table class="table-max f-size-12">
	    <tbody>
	        <tr>
	            <td width="20%"><?php echo nbs(10) ?><img src="<?php echo base_url().PATH_IMG ?>doh_logo.png" width=90 height=90></img><br></td>
	            <td align="center" width="60%">Republic of the Philippines<br>DEPARTMENT OF HEALTH</td>
	            <td>&nbsp;</td>
	        </tr>
	        <tr>
	          <td colspan="3" height="50" valign="top" align="center"><b>List of Employees Not Included in Payroll<br>For the period <?php echo ( ! empty ($records) ? $records[0]['period_date'] : '');?></b></td>
	        </tr>
	    </tbody>
	</table>
	<table class="center-85 f-size-12">
		<tr>
			<td height="20" colspan="6" align="left" valign=middle>
				Office Name : <?php echo !EMPTY($office) ? $office : ALL; ?>
			</td>
		</tr>
		<tr>
			<th height="25" width="50" class="td-border-top td-border-bottom td-border-left td-border-right" align="center" valign=middle>Employee ID</th>
			<th colspan="2" class="td-border-top td-border-bottom td-border-right" align="center" valign=middle>Employee Name</th>
			<th colspan="2" class="td-border-top td-border-bottom td-border-right" align="center" valign=middle>Position</th>
			<?php if(EMPTY($office)): ?>	
				<th colspan="2" class="td-border-top td-border-bottom td-border-right" align="center" valign=middle>Office</th>
			<?php endif; ?>
		</tr>

		<?php if($records): ?>
			<?php foreach ($records as $record): ?>
				<tr>
					<td height="20" class="td-border-top td-border-bottom td-border-left td-border-right" align="center" valign=middle><?php echo $record['agency_employee_id']?></td>
					<td colspan="2" class="td-border-top td-border-bottom td-border-right" style="padding:3px" align="left" valign=middle><?php echo $record['employee_name']?></td>
					<td colspan="2" height="20" class="td-border-top td-border-bottom td-border-right" style="padding:3px" align="left" valign=middle><?php echo $record['employ_position_name']?></td>
					<?php if(EMPTY($office)): ?>
						<td colspan="2" height="20" class="td-border-top td-border-bottom td-border-right" style="padding:3px" align="left" valign=middle><?php echo $record['employ_office_name']?></td>
					<?php endif; ?>
				</tr>
			<?php endforeach;?>
		<?php else: ?>
		<tr>
			<td colspan="7" align="center" valign="middle" class="td-border-light-top td-border-light-bottom td-border-light-left td-border-light-right td-center-middle" height="30">No Records Found.</td>
			
		</tr>
		<?php endif;?>

		<tr>
			<td colspan="7" height="40" align="center" valign="middle">*********************** Nothing follows **********************</td>
		</tr>
		
	</table>
	
<!-- ************************************************************************** -->
</body>
</html>