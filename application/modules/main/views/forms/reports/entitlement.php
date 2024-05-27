
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<html>
<head>
	
	<title></title>
	<link rel="stylesheet" href="<?php echo base_url().PATH_CSS ?>reports.css" type="text/css" />
</head>

<body>
<table class="table-max">
	<tr>
		<td class="td-center-bottom" colspan=4 height="40" valign=middle><b>ENTITLEMENT OF <?php echo $records[0]['compensation_code']; ?> PAY<br>For the month of <?php echo strtoupper(date_format(date_create($date), 'F  Y')) ?></b></td>
	</tr>
	<tr>
		<td colspan=2 height="20" align="left" valign=top></td>
	</tr>
	<tr>
		<td colspan=2 height="20" align="left" valign=top><b>Office: <?php echo $records[0]['name']; ?></b></td>
		<td colspan=2 class="td-left-top"><b>Address: San Lazaro Compound, Sta. Cruz, Manila</b></td>
	</tr>
	<tr>
		<td colspan=4 height="20" align="left" valign=top><b>Count: <?php echo (!EMPTY($records)) ? count($records) : 0 ?></b></td>
	</tr>
	<tr>
		<td class="td-border-top td-border-bottom td-border-left td-border-right td-center-middle" height="30"><b>Employee Number</b></td>
		<td class="td-border-top td-border-bottom td-border-right td-center-middle"><b>Employee Name</b></td>
		<td class="td-border-top td-border-bottom td-border-right td-center-middle"><b>Office</b></td>
		<td class="td-border-top td-border-bottom td-border-right td-center-middle"><b>Position</b></td>
	</tr>

	<?php

		foreach($records AS $r)
		{
			echo '<tr>'
				.	'<td class="td-border-bottom td-border-left td-border-right td-center-middle" align="right" height="20">' . $r['personnel_number'] . '&nbsp;</td>'
				. 	'<td class="td-border-bottom td-border-right td-center-middle">' . $r['employee_name'] . '</td>'
				. 	'<td class="td-border-bottom td-border-right td-center-middle">' . $r['name'] . '</td>'
				. 	'<td class="td-border-bottom td-border-right td-center-middle">' . $r['position_name'] . '</td>'
			. 	 '</tr>';
		}

	?>

</table>
<!-- ************************************************************************** -->
</body>

</html>
