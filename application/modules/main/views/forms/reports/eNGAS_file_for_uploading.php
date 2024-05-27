<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
	<title>eNGAS File for Uploading</title>
	<link rel="stylesheet" href="<?php echo base_url().PATH_CSS ?>reports.css" type="text/css" />
</head>
<body>
	<table class="table-max f-size-12">
	    <tbody>
	        <tr>
	            <td width="20%"><?php echo nbs(10) ?><img src="<?php echo base_url().PATH_IMG ?>doh_logo.png" width=90 height=90></img><br></td>
	            <td align="center" width="60%">Republic of the Philippines<br>DEPARTMENT OF HEALTH
					<br>FOR THE PERIOD OF <?php echo $period_detail['payroll_period'];?>
				</td>
	            <td>&nbsp;</td>
	        </tr>
			<tr>
				<td height="20"></td>
			</tr>
	    </tbody>
	</table>
	<table class="center-100 f-size-12 table-max" border="1">
		<thead>
			<tr>
				<th class="pad-2 align-c" colspan="11">Employee Details</th>
				<th class="pad-2 align-c" colspan="5">Accounts</th>
				<th class="pad-2 align-c" rowspan="2">Gross Salary</th>
				<th class="pad-2 align-c" rowspan="2">Net Salary</th>
				<th class="pad-2 align-c" colspan="7">Deductions</th>
				<th class="pad-2 align-c" rowspan="2">Net Pay</th>
			</tr>	
			<tr>
				<th class="pad-2 align-c">Employee ID</th>
				<th class="pad-2 align-c">Last Name</th>
				<th class="pad-2 align-c">First Name</th>
				<th class="pad-2 align-c">Middle Name</th>
				<th class="pad-2 align-c">Ext Name</th>
				<th class="pad-2 align-c">Office Name</th>
				<th class="pad-2 align-c">Salary Grade</th>
				<th class="pad-2 align-c">ATM No.</th>
				<th class="pad-2 align-c">Responsibility Code</th>
				<th class="pad-2 align-c">Aptitude</th>
				<th class="pad-2 align-c">PRC No.</th>
				<th class="pad-2 align-c">TIN No.</th>
				<th class="pad-2 align-c">SSS No.</th>
				<th class="pad-2 align-c">Pag-ibig No.</th>
				<th class="pad-2 align-c">Philhealth No.</th>
				<th class="pad-2 align-c">MP2 No.</th>
				<th class="pad-2 align-c">EWT</th>
				<th class="pad-2 align-c">GMP</th>
				<th class="pad-2 align-c">SSS</th>
				<th class="pad-2 align-c">Pag-ibig</th>
				<th class="pad-2 align-c">Philhealth</th>
				<th class="pad-2 align-c">MP2</th>
				<th class="pad-2 align-c">Overpayment</th>
			</tr>			
		</thead>
		<tbody>
			<?php foreach($header as $key => $value): ?>
			<tr>
				<td class="pad-2 align-c"><?php echo $value['biometric_pin']?></td>
				<td class="pad-2 align-l"><?php echo $value['last_name']?></td>
				<td class="pad-2 align-l"><?php echo $value['first_name']?></td>
				<td class="pad-2 align-l"><?php echo $value['middle_name']?></td>
				<td class="pad-2 align-l"><?php echo $value['ext_name']?></td>
				<td class="pad-2 align-l"><?php echo $value['office_name']?></td>
				<td class="pad-2 align-c"><?php echo $value['salary_grade']?></td>
				<td class="pad-2 align-l"><?php echo $identifications[$value['employee_id']]['atm']?></td>
				<td class="pad-2 align-l"><?php echo $value['respcode']?></td>
				<td class="pad-2 align-l"><?php echo $identifications[$value['employee_id']]['license_no']?></td>
				<td class="pad-2 align-l"><?php echo $value['']?></td>
				
				<td class="pad-2 align-l"><?php echo $identifications[$value['employee_id']]['tin']?></td>
				<td class="pad-2 align-l"><?php echo $identifications[$value['employee_id']]['sss']?></td>
				<td class="pad-2 align-l"><?php echo $identifications[$value['employee_id']]['hmdf']?></td>
				<td class="pad-2 align-l"><?php echo $identifications[$value['employee_id']]['phic']?></td>
				<td class="pad-2 align-l"><?php echo $identifications[$value['employee_id']]['hmdf2']?></td>
				
				<td class="pad-2 align-r"><?php echo number_format($value['gross_salary'], 2)?></td>
				<td class="pad-2 align-r"><?php echo number_format($value['net_salary'], 2)?></td>
				
				<td class="pad-2 align-r"><?php echo number_format($deductions[$value['employee_id']]['ewt'], 2)?></td>
				<td class="pad-2 align-r"><?php echo number_format($deductions[$value['employee_id']]['gmp'], 2)?></td>
				<td class="pad-2 align-r"><?php echo number_format($deductions[$value['employee_id']]['sss'], 2)?></td>
				<td class="pad-2 align-r"><?php echo number_format($deductions[$value['employee_id']]['hmdf'], 2)?></td>

				<td class="pad-2 align-r"><?php echo number_format($deductions[$value['employee_id']]['phic'], 2)?></td>
				
				
				<td class="pad-2 align-r"><?php echo number_format($deductions[$value['employee_id']]['hmdf2'], 2)?></td>

				<td class="pad-2 align-r"><?php echo number_format($deductions[$value['employee_id']]['overpay'], 2)?></td>
				<td class="pad-2 align-r"><?php echo number_format($value['net_pay'], 2)?></td>
				
			</tr>
			<?php endforeach;?>
		</tbody>
	</table>
</body>
</html>