<!DOCTYPE html>
<html>
	<?php if(count($records)>0):?>
	<head>
		<title>DOH</title>
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
		          <td colspan="3" height="40" valign="top" align="center"><b>REGULAR WITHHOLDING TAX<br>Summary Sheet<br>
				    	For the month of <?php echo $month_year_text; ?></b></td>
		        </tr>
		    </tbody>
		</table>
		<table>
			<tr>
			    <td height="20"></td>
			</tr>
			<!-- <tr>
			    <td class="bold" width="80">Agency Name</td>
			    <td class="">:&nbsp;DEPARTMENT OF HEALTH</td>
			</tr> -->
			<tr>
			    <td class="">Office Name</td>
			    <td class="bold">:&nbsp;<?php echo strtoupper($header['office_name']); ?></td>
			</tr>
			<tr>
			    <td class="">Office Address</td>
			    <td class="">:&nbsp;SAN LAZARO COMP. MANILA</td>
			</tr>
			<tr>
			    <td class="">Office Tel. No.</td>
			    <td class="">:</td>
			</tr>
		</table>
		<table border="1"  style="width:100%; border-collapse: collapse;">
			<thead>
				<tr>
					<th width="20" class="td-center-middle">No.</th>
					<th class="td-center-middle">Employee Name</th>
					<th class="td-center-middle">Position</th>
					<th class="td-center-middle">Basic salary</th>
					<th class="td-center-middle">Amount Remitted</th>
				</tr>
			</thead>
			<?php 
			$sum_salary = 0;
			$sum_amount = 0;
			foreach($records as $ctr => $data){
			?>
			<tr>
				<td height="20" class="align-r pad-2"><?php echo ++$ctr ?></td>
				<td class="pad-2"><?php echo $data['employee_name']?></td>
				<td class="pad-2"><?php echo $data['position_name']?></td>
				<td class="align-r pad-2"><?php echo number_format($data['basic_salary'], 2)?></td>
				<td class="align-r pad-2"><?php echo number_format($data['remitted'], 2)?></td>
			</tr>
			<?php 
			$sum_salary += $data['basic_salary'];
			$sum_amount += $data['remitted'];
			}
			?>
			<tr>
				<td colspan="3" class="align-c"><b>GRAND TOTAL ----------></b></td>
				<td class="align-r pad-2"><b><?php echo number_format($sum_salary, 2);?></b></td>
				<td class="align-r pad-2"><b><?php echo number_format($sum_amount, 2);?></b></td>
			</tr>	
		</table>
		
	<table class="table-max">
    <tr>
        <td colspan=2 height="40"></td>
    </tr>
    <tr>
        <td height="20" width="50%" align="left" valign="middle"><?php echo nbs(15)?>Prepared by:</td>
        <td height="20" width="50%" align="left" valign="middle"><?php echo nbs(15)?>Certified correct:</td>
    </tr>   
    <tr>
        <td colspan=2 height="40"></td>
    </tr>
    <tr>
        <td height="20" align="center" valign="middle"><b><?php echo $prepared_by['signatory_name']; ?></b></td>
        <td height="20" align="center" valign="middle"><b><?php echo $certified_by['signatory_name']; ?></b></td>
    </tr>
    <tr>  
        <td height="20" align="center" valign="middle"><?php echo $prepared_by['position_name'] ?><br><?php echo $prepared_by['office_name'] ?></td>
        <td height="20" align="center" valign="middle"><?php echo $certified_by['position_name'] ?><br><?php echo $certified_by['office_name']?></td>
    </tr>
</table>	
	</body>
	<?php else :?>
	
	<div class="wrapper">
		<form id="test_report">
			<p style="text-align: center">No data available.</p>
		</form>
	</div>
	
	<?php endif;?>
</html>