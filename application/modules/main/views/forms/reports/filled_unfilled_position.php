
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<html>
<head>
	
	<title>Filled And Unfilled Positions</title>
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
		<td class="td-center-bottom f-size-12" colspan=2 height="40" valign=bottom><b>REPORT ON FILLED AND UNFILLED POSITION</b></td>
	</tr>
	<tr>
		<td colspan=2 height="20" align="left" valign=middle></td>
	</tr>
	<tr>
		<td class="bold" colspan=2 align="left" valign=middle>Filled Positions Count: <?php echo (!EMPTY($filled_positions)) ? count($filled_positions) : 0?></td>
	</tr>
	<tr>
		<td class="bold" colspan=2 align="left" valign=middle>Unfilled Positions Count: <?php echo (!EMPTY($unfilled_positions)) ? count($unfilled_positions) : 0?></td>
	<tr>
		<td align="left" valign=middle><b>Office/Bureau:<u> <?php echo $office_name ?></b></u></td>
		<td class="bold" align="right" valign=middle>Total Count: <?php echo (count($filled_positions) + count($unfilled_positions))?></td>
	</tr>
</table>
<table class="table-max">
	<thead>
		<tr>
			<td class="td-border-light-top td-border-light-bottom td-border-light-left td-border-light-right td-center-middle" height="30" width="450"><b>Position Name</b></td>
			<td class="td-border-light-top td-border-light-bottom td-border-light-right td-center-middle"><b>Plantilla Item</b></td>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td class="td-border-light-left td-border-light-bottom td-border-light-right td-center-middle" height="20" align="left" valign=middle><b><u>FILLED POSITIONS</u></b></td>
			<td class="td-border-light-right td-border-light-bottom td-center-middle" height="20" align="left" valign=top></td>
		</tr>

		<?php

			foreach($filled_positions AS $filled)
			{
				echo '<tr>'
					.	'<td class="td-border-light-left td-border-light-bottom td-border-light-top td-border-light-right" height="20">' . strtoupper($filled['position_name']) . '&nbsp;</td>'
					. 	'<td class="td-border-light-right td-border-light-bottom td-border-light-top">' . $filled['plantilla_code'] . '</td>'
				. 	 '</tr>';
			}

		?>

		<tr>
			<td class="td-border-light-left td-border-light-bottom td-border-light-right td-center-middle" height="20" align="left" valign=middle><b><u>UNFILLED POSITIONS</u></b></td>
			<td class="td-border-light-right  td-border-light-bottom  td-center-middle" height="20" align="left" valign=top></td>
		</tr>

		<?php

			foreach($unfilled_positions AS $unfilled)
			{
				echo '<tr>'
					.	'<td class="td-border-light-left td-border-light-bottom td-border-light-top td-border-light-right" height="20">' . strtoupper($unfilled['position_name']) . '&nbsp;</td>'
					. 	'<td class="td-border-light-right td-border-light-bottom td-border-light-top">' . $unfilled['plantilla_code'] . '</td>'
				. 	 '</tr>';
			}

		?>
	</tbody>
</table>
<!-- ************************************************************************** -->
</body>

</html>
