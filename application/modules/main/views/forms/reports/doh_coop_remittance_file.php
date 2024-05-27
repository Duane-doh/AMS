<!DOCTYPE html>
<html>
<head>
	<title>DOH</title>
	<link rel="stylesheet" href="<?php echo base_url().PATH_CSS ?>reports.css" type="text/css" />
</head>
<body>
<table>
	<tr>
	    <td width="100" height="10" align="right" valign="top"><img src="<?php echo base_url().PATH_IMG ?>doh_logo.png" width="80" height="78"></td>
	    <td width="550" align="center" valign="top" style="padding-bottom:10px;">
	    	<b>
	    		Summary of Deductions (DOH-COOP)<br>
	    		<?php echo "April, 2016"?> 
	    	</b> 
	    </td>
    </tr>
</table>
<table border="1"  style="width:100%; border-collapse: collapse;">
			<tr>
				<th class="td-center-middle">&nbsp;</th>
				<th class="td-center-middle">SHARE</th>
				<th class="td-center-middle">SALARY</th>
				<th class="td-center-middle">CREDIT CARD</th>
				<th class="td-center-middle">GOODS</th>
				<th class="td-center-middle">RICE</th>
				<th class="td-center-middle">GOOD</th>
				<th class="td-center-middle">EDUC LOAN</th>
				<th class="td-center-middle">COOP CONSO</th>
				<th class="td-center-middle">CALAMITY/LOAN</th>
				<th class="td-center-middle">GOODS 2</th>
				<th class="td-center-middle">TOTAL</th>
			</tr>
			<tr>
				<td>AS</td>
				<td class="td-right-bottom">500.00</td>
				
				<td class="td-right-bottom">500.00</td>
				<td class="td-right-bottom">500.00</td>
				<td class="td-right-bottom">500.00</td>
				<td class="td-right-bottom">500.00</td>
				<td class="td-right-bottom">500.00</td>
				<td class="td-right-bottom">500.00</td>
				<td class="td-right-bottom">500.00</td>
				<td class="td-right-bottom">500.00</td>
				<td class="td-right-bottom">500.00</td>
				<td class="td-right-bottom">500.00</td>
			</tr>
			<tr>
				<td>KMITS</td>
				<td class="td-right-bottom">500.00</td>
				<td class="td-right-bottom">500.00</td>
				<td class="td-right-bottom">500.00</td>
				<td class="td-right-bottom">500.00</td>
				<td class="td-right-bottom">500.00</td>
				<td class="td-right-bottom">500.00</td>
				<td class="td-right-bottom">500.00</td>
				<td class="td-right-bottom">500.00</td>
				<td class="td-right-bottom">500.00</td>
				<td class="td-right-bottom">500.00</td>
				<td class="td-right-bottom">500.00</td>
			</tr>
			<tr>
				<td><b></b>Grand Total</b></td>
				<td class="td-right-bottom">1000.00</td>
				<td class="td-right-bottom">1000.00</td>
				<td class="td-right-bottom">1000.00</td>
				<td class="td-right-bottom">1000.00</td>
				<td class="td-right-bottom">1000.00</td>
				<td class="td-right-bottom">1000.00</td>
				<td class="td-right-bottom">1000.00</td>
				<td class="td-right-bottom">1000.00</td>
				<td class="td-right-bottom">1000.00</td>
				<td class="td-right-bottom">1000.00</td>
				<td class="td-right-bottom">1000.00</td>
			</tr>
</table>
	<div style="height:30px"></div>
	
	<table style="width:100%;">
			<tr>
				<td>
					<b>Prepared by:</b>
				</td>
				<td>
					<b>Certified Corect by:</b>
				</td>	
			</tr>
			<tr><td>&nbsp;</td><td></td></tr>
			<tr><td>&nbsp;</td><td></td></tr>
			<tr>
				 <td class="align-c bold">
			        <u><?php echo nbs(3).$prepared_by['full_name'].nbs(3)?></u>
			      </td>
			      <td class="align-c bold">
			        <u><?php echo nbs(3).$certified_by['full_name'].nbs(3)?></u>
			      </td>
			</tr>
			<tr>
				<td class="align-c">Signature Over Printed Name</td>
				<td class="align-c">Signature Over Printed Name</td>
			</tr>
			<tr><td>&nbsp;</td><td></td></tr>
			<tr><td>&nbsp;</td><td></td></tr>
			<tr>
			    <td class="align-c bold">
			        <?php echo nbs(3).$prepared_by['position'].nbs(3)?>
			    </td>
			    <td class="align-c bold">
			        <?php echo nbs(3).$certified_by['position'].nbs(3)?>
			    </td>
			 </tr>
			 <tr>
			     <td class="align-c bold">
			        <u><?php echo nbs(3).$prepared_by['office'].nbs(3)?></u>
			     </td>
			     <td class="align-c bold">
			        <u><?php echo nbs(3).$certified_by['office'].nbs(3)?></u>
			     </td>
			 </tr>
			<tr>
				<td class="align-c">Designation</td>
				<td class="align-c">Designation</td>
			</tr>
		</table>	
</body>
</html>