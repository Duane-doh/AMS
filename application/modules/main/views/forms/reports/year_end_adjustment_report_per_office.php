<!DOCTYPE html>
<html>
	<head>
		<title>Tax Report Adjustment <?php echo $year;?></title>
		<link rel="stylesheet" href="<?php echo base_url().PATH_CSS ?>reports.css" type="text/css" />
	</head>
	<style type="text/css">
	   	.light-border th{
			border: .5px solid #727171 !important; 
		}
		.light-border td{
			border: .5px solid #727171 !important; 
		}
	</style>
	<body>
		<table class="table-max f-size-12">
		    <tbody>
		        <tr>
		          <td colspan="3" valign="top" align="center"><b>Tax Report (Adjustment <?php echo $year;?>)</b><br>DEPARTMENT OF HEALTH<br>San Lazaro Cpd. Sta Cruz, Manila<br>YEAR-END ADJUSTMENT</td>
		        </tr>
		    </tbody>
		</table>  
		<table  class="light-border" style="width:100%; border-collapse: collapse;">
			<thead>
			<tr>
				<th class="td-center-middle" rowspan="2">NAME<br>(1)</th>
				<th class="td-center-middle" rowspan="2">TAXPAYER ACCOUNT NO.<br>(2)</th>
				<th class="td-center-middle" colspan="3">NON-TAXBLE</th>
				<th class="td-center-middle" colspan="3">TAXABLE</th>
				<th class="td-center-middle" rowspan="2">AMOUNT OF EXEMPTION<br>(4)</th>
				<th class="td-center-middle" rowspan="2">TAX DUE (JAN TO DEC)<br>(5)</th>
				<th class="td-center-middle" rowspan="2">TAX WITHHELD (JAN TO NOV)<br>(6)</th>
				<th class="td-center-middle" colspan="2">YEAR-END ADJUSTMENT<br>(7)</th>
				<th class="td-center-middle" rowspan="2">TOTAL TAXES WITHHELD (NET YEAR-END ADJ.)<br>(8)</th>
			</tr>
			<tr>
				<th class="td-center-middle">13th MONTH PAY<br>(3a)</th>
				<th class="td-center-middle">OTHER BENEFITS<br>(3b)</th>
				<th class="td-center-middle">SAL. & OTHER FORMS OF COMPENSATION<br>(3c)</th>
				<th class="td-center-middle">13th MONTH PAY<br>(3d)</th>
				<th class="td-center-middle">OTHER BENEFITS<br>(3e)</th>
				<th class="td-center-middle">SAL. & OTHER FORMS OF COMPENSATION<br>(3f)</th>
				<th class="td-center-middle">TAX DUE DEC<br>(7a)</th>
				<th class="td-center-middle">OVERWITHHELD<br>(7b)</th>
			</tr>
			</thead>
			<tbody>
				<?php
				$totals         = array();
				$page_totals    = array();
				$display_count  = 34;
				$count_per_page = 38;
				$footer_count   = 0;
				
				foreach ($records as $key => $rec):
					$record_cnt++;
					$footer_count++;
					$non_tax_3a    = 0;
					$non_tax_3b    = 0;
					$tax_3d        = 0;
					$tax_3e        = 0;
					$total_gross   = 0;
					$exempt_amount = 82000;
					if($rec['basic_salary'] > $exempt_amount)
					{
						$non_tax_3a  = $exempt_amount;
						$tax_3d      = $rec['basic_salary'] - $exempt_amount;
						$total_gross = $rec['month_pay_13'] + $rec['taxable_month_pay_13'];
						$tax_3e      = $total_gross - $rec['basic_salary'];
						$tax_3e      = ($tax_3e < 0) ? 0 : $tax_3e;
					}
					else
					{
						$non_tax_3a = $rec['basic_salary'];
						$non_tax_3b = $rec['month_pay_13'] - $rec['basic_salary'];
						$tax_3e = $rec['taxable_month_pay_13'];
					}
					$totals[0] += $non_tax_3a;
					$totals[1] += $non_tax_3b;
					$totals[2] += $rec['salary_other_compensation'] + $rec['statutory_employee_share'];
					$totals[3] += $tax_3d;
					$totals[4] += $tax_3e;
					$totals[5] += $rec['sal_comp'];
					$totals[6] += $rec['total_personal_exemption'];
					$totals[7] += $rec['tax_due'];
					$totals[8] += $rec['total_tax_withheld'];

					$tax_due_dec 		= $rec['tax_due'] - $rec['total_tax_withheld'];
					$tax_overwithheld	= 0;
					if ($tax_due_dec < 0)
					{
						$tax_overwithheld	= $tax_due_dec * -1;
						$tax_due_dec		= 0;
					}
					

					$totals[9]	+= $tax_due_dec;
					$totals[10] += $tax_overwithheld;
					$totals[11] += $rec['tax_due'];

					$page_totals[0] += $non_tax_3a;
					$page_totals[1] += $non_tax_3b;
					$page_totals[2] += $rec['salary_other_compensation'] + $rec['statutory_employee_share'];
					$page_totals[3] += $tax_3d;
					$page_totals[4] += $tax_3e;
					$page_totals[5] += $rec['sal_comp'];
					$page_totals[6] += $rec['total_personal_exemption'];
					$page_totals[7] += $rec['tax_due'];
					$page_totals[8] += $rec['total_tax_withheld'];

					$page_totals[9]	+= $tax_due_dec;
					$page_totals[10]+= $tax_overwithheld;
					$page_totals[11]+= $rec['tax_due'];


				echo '<tr>
							<td class="pad-2" width="200">' . ($key+1) .'. ' . $rec['employee_name'] . '</td>
							<td class="pad-2">' . format_identifications($rec['taxpayer_account'],$tin_format). '</td>
							<td class="pad-2" align="right">' . number_format($non_tax_3a,2) . '</td>
							<td class="pad-2" align="right">' . number_format($non_tax_3b,2) . '</td>
							<td class="pad-2" align="right">' . number_format(($rec['salary_other_compensation']  + $rec['statutory_employee_share']),2) . '</td>
							<td class="pad-2" align="right">' . number_format($tax_3d,2) . '</td>
							<td class="pad-2" align="right">' . number_format($tax_3e,2) . '</td>
							<td class="pad-2" align="right">' . number_format($rec['sal_comp'],2) . '</td>
							<td class="pad-2" align="right">' . number_format($rec['total_personal_exemption'],2) . '</td>
							<td class="pad-2" align="right">' . number_format($rec['tax_due'],2) . '</td>
							<td class="pad-2" align="right">' . number_format($rec['total_tax_withheld'],2) . '</td>
							<td class="pad-2" align="right">' . number_format($tax_due_dec,2) . '</td>
							<td class="pad-2" align="right">' . number_format($tax_overwithheld,2) . '</td>
							<td class="pad-2" align="right">' . number_format($rec['tax_due'],2) . '</td>
						</tr>';
				if($footer_count == $display_count OR count($records) == $record_cnt):
					echo '<tr>
						<td colspan="2" class="align-r"><b>Page Total:</b></td>';
						foreach ($page_totals as $key => $val) {
							echo '<td class="pad-2 align-r"><b>' . number_format($val,2) . '</b></td>';
						}
					$footer_count = 0;
					$display_count = $count_per_page;
					$page_totals = array();
					?>

				<?php if(count($records) != $record_cnt): ?>
						 </tbody>
	            	</table>
	             	<pagebreak />
	             	<table  class="light-border" style="width:100%; border-collapse: collapse;">
						<thead>
							<tr>
								<th class="td-center-middle" rowspan="2">NAME<br>(1)</th>
								<th class="td-center-middle" rowspan="2">TAXPAYER ACCOUNT NO.<br>(2)</th>
								<th class="td-center-middle" colspan="3">NON-TAXBLE</th>
								<th class="td-center-middle" colspan="3">TAXABLE</th>
								<th class="td-center-middle" rowspan="2">AMOUNT OF EXEMPTION<br>(4)</th>
								<th class="td-center-middle" rowspan="2">TAX DUE (JAN TO DEC)<br>(5)</th>
								<th class="td-center-middle" rowspan="2">TAX WITHHELD (JAN TO NOV)<br>(6)</th>
								<th class="td-center-middle" colspan="2">YEAR-END ADJUSTMENT<br>(7)</th>
								<th class="td-center-middle" rowspan="2">TOTAL TAXES WITHHELD (NET YEAR-END ADJ.)<br>(8)</th>
							</tr>
							<tr>
								<th class="td-center-middle">13th MONTH PAY<br>(3a)</th>
								<th class="td-center-middle">OTHER BENEFITS<br>(3b)</th>
								<th class="td-center-middle">SAL. & OTHER FORMS OF COMPENSATION<br>(3c)</th>
								<th class="td-center-middle">13th MONTH PAY<br>(3d)</th>
								<th class="td-center-middle">OTHER BENEFITS<br>(3e)</th>
								<th class="td-center-middle">SAL. & OTHER FORMS OF COMPENSATION<br>(3f)</th>
								<th class="td-center-middle">TAX DUE DEC<br>(7a)</th>
								<th class="td-center-middle">OVERWITHHELD<br>(7b)</th>
							</tr>
							</thead>
						<tbody>
        		<?php endif;?>  
        	<?php endif;?>  
		<?php endforeach;

			if(!EMPTY($totals)) 
			{

				echo '</tr>
				<tr>
					<td colspan="2" class="align-r"><b>Grand Total:</b></td>';
		
						foreach ($totals as $key => $val) {
							echo '<td class="pad-2 align-r"><b>' . number_format($val,2) . '</b></td>';
						}
				echo '</tr>';
			}
			else
			{
				echo '<tr><td colspan="14" class="align-c">No records found.</td></tr>';
			}
			?>	
			</tbody>
		</table>
			<table class="center-85">
    <tr>
        <td colspan=2 height="40"></td>
    </tr>
     <tr>
        <td colspan=2>
        	<table border="0" width="100%">
        		 <tr>
			        <td height="20" width="500" align="left" valign="middle"><?php echo nbs(25)?>Prepared by:</td>
			        <td height="20" align="left" valign="middle"><?php echo nbs(10)?>Certified correct:</td>
			    </tr>   
			    <tr>
			        <td colspan=2 height="40"></td>
			    </tr>
			    <tr>
			        <td height="20" align="center" valign="middle"><b><?php echo $prepared_by['signatory_name']; ?></b></td>
			        <td height="20" align="center" valign="middle"><?php echo nbs(35)?><b><?php echo $certified_by['signatory_name']; ?></b></td>
			    </tr>
			    <tr>  
			        <td height="20" align="center" valign="middle"><?php echo $prepared_by['position_name'] ?><br><?php echo $prepared_by['office_name'] ?></td>
			        <td height="20" align="center" valign="middle"><?php echo nbs(35)?><?php echo $certified_by['position_name'] ?><br>
			        <?php echo nbs(35)?><?php echo $certified_by['office_name']?></td>
			    </tr>
        	</table>
        </td>
    </tr>
   
</table>
	</body>
</html>