<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<html>
<head>
	
	<title></title>
	<link rel="stylesheet" href="<?php echo base_url().PATH_CSS ?>reports.css" type="text/css" />
	
</head>

<body>


<table class="table-max" align="center">
	<tr class="align-c">
		
		<td  class="align-c" valign=middle><b>First Salary Tranche Monthly Salary Schedule For</b></td>
	</tr>
	<tr class="align-c">
		
		<td  align="center" valign=middle><b>Civilian Personnel of the National Government</b></td>
		
	</tr>
	<tr class="align-c">
		
		<td align="center" valign=middle><b>Effective <?php echo date_format(date_create($records[0]['effectivity_date']), 'F d, Y'); ?>  </b></td>
		
	</tr>
	<tr class="align-c">

		<td align="center" valign=middle>(in Pesos)</td>

	</tr>
	<tr class="align-c">
		<td height="30" align="left" valign=bottom><b></b><br></td>
	</tr>


</table>

<table class="table-max">
	<?php 


		$salary_grade = 0;
			for ($i=0; $i <= count($records); $i++) 
			{
				if($salary_grade == $records[$i]['salary_grade'])
				{
					echo '<td class="td-border-top td-border-bottom td-border-right" colspan="1"><center><b>Step ' . $records[$i-1]['salary_step'] . '</b></center></td>';
					if($salary_grade != $records[$i+1]['salary_grade'])
					{
						echo '<td class="td-border-top td-border-bottom td-border-right" colspan="1"><center><b>Step ' . $records[$i]['salary_step'] . '</b></center></td></tr>';
						$i = count($records);
					}
				}	
				else
				{
					echo '<tr><td class="td-border-left td-border-top td-border-bottom td-border-right" colspan="1"><center><b>Salary Grade</b></center></td>';	
					$salary_grade = $records[$i]['salary_grade'];
				}
			} 

			$salary_grade = 0;
			for ($i=0; $i < count($records); $i++) 
			{ 
				if($salary_grade == $records[$i]['salary_grade'])
				{
					echo '<td class="td-border-top td-border-bottom td-border-right" colspan="1" align="right">' . $records[$i]['amount'] . '</td>';
					if($salary_grade != $records[$i+1]['salary_grade'])
						echo '</tr>';
				}
				else
				{
					echo '<tr><td class="td-border-left td-border-top td-border-bottom td-border-right" colspan="1"><center>' . $records[$i]['salary_grade'] .'</center></td>';	
				 	echo '<td class="td-border-top td-border-bottom td-border-right" colspan="1" align="right">' . $records[$i]['amount'] .'</td>';
					$salary_grade = $records[$i]['salary_grade'];
				}

			}


	?>
	
	
</table>

<!-- ************************************************************************** -->
</body>

</html>
