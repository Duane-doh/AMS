<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<html>
<head>
	<title></title>
	 <link rel="stylesheet" href="<?php echo base_url().PATH_CSS ?>reports.css" type="text/css" />
</head>

<body>
<table class="center-85 f-size-12">
		<tbody>
			<tr>
				<td><img src="<?php echo base_url().PATH_IMG ?>doh_logo.png" width=61 height=61></img> <?php echo nbs(15) ?></td>
				
				<td class="align-c">Republic of the Philippines<br>Department of Health<br><b>OFFICE OF THE SECRETARY</b><br>Manila</td>
				<td></td>
			</tr>
			<tr>
				<td></td>
				<td class="align-c"><br><br><b>LIST OF EMPLOYEES WITH NO WORK SCHEDULE</b></td>
				<td></td>
			</tr>
		</tbody>
</table>
<br>
<br>
<table class="table-max cont-5">
<tr>
	<td class='td-border-light-bottom td-border-light-top td-border-light-left td-border-light-right'><b>Employee Name</b></td>
	<td class='td-border-light-bottom td-border-light-top td-border-light-left td-border-light-right'><b>Office Name</b></td>
	<td class='td-border-light-bottom td-border-light-top td-border-light-left td-border-light-right'><b>Employee Number</b></td>
 </tr>
		<?php
	foreach($employees_list as $row)
	{
		echo "<tr>";
		echo "<td class='td-border-light-bottom td-border-light-top td-border-light-left td-border-light-right'>".$row['last_name']. ", ". $row['first_name']." ". $row['middle_name'] ."</td>";
		echo "<td class='td-border-light-bottom td-border-light-top td-border-light-left td-border-light-right'>".$row['employ_office_name']. "</td>";
		echo "<td class='td-border-light-bottom td-border-light-top td-border-light-left td-border-light-right'>".$row['biometric_pin']. "</td>";
		echo"</tr>";
	}
	?>
</table>
</body>
</html>
