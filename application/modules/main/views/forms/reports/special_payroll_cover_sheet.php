<!DOCTYPE html>
<html>
<?php if(!EMPTY($results['emp_count']>0)): ?>
<head>
	<title>Special Payroll Cover Sheet</title>
	<link rel="stylesheet" href="<?php echo base_url().PATH_CSS ?>reports.css" type="text/css" />
</head>
<body style="width: 1010px;  margin: 5px">
    <div style="width: 100%; height: 500px;  text-align: center; font-family: 'Times New Roman', Times, serif; ">
        <br/><br/>
        <div>
        	<img src="<?php echo base_url().PATH_IMG ?>doh_logo.png" width="90" height="88">
			<span class="center-85 bold f-size-40" style="font-size: 30px"><?php echo nbs(1)?>DEPARTMENT OF HEALTH</span>
		</div>
		<br><br><br><br>
        <br>
        <div class="center-85 align-c f-size-30 bold align-c">
        <?php echo $compensation_name['compensation_name'];?>
        </div>
        <div class="center-85 align-c f-size-30 bold align-c pad-bot-100">CY 
        <?php echo $year;?>
        </div>
       
        <div class="center-85 align-c f-size-18 bold">

        TOTAL NO. OF EMPLOYEES:  <?php echo $results['emp_count']?>
        </div>
    </div>
   
     <div style="page-break-before: always; width: 100%; height: 500px; text-align: center">
        <table class="table-max">
		    <tr>
		        <td colspan="14" class=" align-c" style="font-size: 30px"><b>SPECIAL PAYROLL</b></td>
		    </tr>
		    <tr>
		        <td colspan="7" class="td-border-thick-top td-border-thick-left pad-left-20 pad-top-10">
		            <table class="table-max f-size-18">
		                <tr>
		                    <td colspan="5" class="pad-bot-50 f-size-20 bold" width="300">[ A ] CERTIFIED: Services duly rendered as stated.</td>
		                </tr>
		                <tr>
		                    <td>&nbsp;</td>
		                    <td align="center" class="td-border-thick-bottom bold"><?php echo isset($signatory_a['signatory_name']) ? $signatory_a['signatory_name'] : ''?></td>
		                    <td>&nbsp;</td>
		                    <td align="center" class="td-border-thick-bottom" width="80"></td>
		                    <td>&nbsp;</td>
		                </tr>
		                <tr>
		                    <td>&nbsp;</td>
		                    <td align="center" class="pad-bot-10 f-size-16">
		                    	<?php echo isset($signatory_a['position_name']) ? $signatory_a['position_name'] : ''?>
		                    <br>
		                    	<?php echo isset($signatory_a['office_name']) ? $signatory_a['office_name'] : ''?>
		                    </td>
		                    <td>&nbsp;</td>
		                    <td align="center" class="pad-bot-10">Date</td>
		                    <td>&nbsp;</td>
		                </tr>
		            </table>
		        </td>
		        <td colspan="7" class="td-border-thick-top td-border-thick-right pad-top-10 pad-left-20">
		            <table class="table-max f-size-18">
		                <tr>
		                    <td colspan="4" class="pad-bot-50 f-size-20 bold">[ C ] APPROVED FOR PAYMENT: _________________________</td>
		                </tr>
		                <tr>
		                    <td>&nbsp;</td>
		                    <td align="center" class="td-border-thick-bottom bold"><?php echo isset($signatory_c['signatory_name']) ? $signatory_c['signatory_name'] : ''?></td>
		                    <td>&nbsp;</td>
		                    <td align="center" class="td-border-thick-bottom" width="80"></td>
		                    <td>&nbsp;</td>
		                </tr>
		                <tr>
		                    <td >&nbsp;</td>
		                     <td align="center" class="pad-bot-10 f-size-16">
		                    	<?php echo isset($signatory_c['position_name']) ? $signatory_c['position_name'] : ''?>
		                    <br>
		                    	<?php echo isset($signatory_c['office_name']) ? $signatory_c['office_name'] : ''?>
		                    </td>
		                    <td >&nbsp;</td>
		                    <td align="center" class="pad-bot-10">Date</td>
		                    <td >&nbsp;</td>
		                </tr>
		            </table>
		        </td>
		    </tr>
		    <tr>
		        <td colspan="7"  class="td-border-thick-bottom td-border-thick-left pad-left-20 pad-top-10">
		            <table class="table-max f-size-18">
		                <tr>
		                    <td colspan="5" class="pad-bot-50 pad-top-10 f-size-20 bold" width="300">[ B ] CERTIFIED: Supporting documents complete and proper, and cash available in the amount of ____________________</td>
		                </tr>
		                <tr>
		                    <td height="30px">&nbsp;</td>
		                    <td align="center" class="td-border-thick-bottom bold"><?php echo isset($signatory_b['signatory_name']) ? $signatory_b['signatory_name'] : ''?></td>
		                    <td>&nbsp;</td>
		                     <td align="center" class="td-border-thick-bottom" width="80"></td>
		                    <td>&nbsp;</td>
		                </tr>
		                <tr>
		                    <td >&nbsp;</td>
		                     <td align="center" class="pad-bot-10 f-size-16">
		                    	<?php echo isset($signatory_b['position_name']) ? $signatory_b['position_name'] : ''?>
		                    <br>
		                    	<?php echo isset($signatory_b['office_name']) ? $signatory_b['office_name'] : ''?>
		                    </td>
		                    <td >&nbsp;</td>
		                    <td align="center" class="pad-bot-10">Date</td>
		                    <td >&nbsp;</td>
		                </tr>
		            </table>
		        </td>
		        <td colspan="7" class="td-border-thick-bottom td-border-thick-right pad-left-20">
		            <table class="table-max f-size-18">
		                <tr>
		                    <td colspan="4" class="pad-bot-50 pad-top-10 f-size-20 bold">[ D ] CERTIFIED: Each employee whose name appears on the payroll has been paid the amount as indicated opposite his/her name.</td>
		                </tr>
		                <tr>
		                    <td>&nbsp;</td>
		                    <td align="center" class="td-border-thick-bottom bold"><?php echo isset($signatory_d['signatory_name']) ? $signatory_d['signatory_name'] : ''?></td>
		                    <td>&nbsp;</td>
		                    <td align="center" class="td-border-thick-bottom" width="80"></td>
		                    <td>&nbsp;</td>
		                </tr>
		                <tr>
		                    <td>&nbsp;</td>
		                    <td align="center" class="pad-bot-10 f-size-16">
		                    	<?php echo isset($signatory_d['position_name']) ? $signatory_d['position_name'] : ''?>
		                    <br>
		                    	<?php echo isset($signatory_d['office_name']) ? $signatory_d['office_name'] : ''?>
		                    </td>
		                    <td>&nbsp;</td>
		                    <td align="center" class="pad-bot-10">Date</td>
		                    <td>&nbsp;</td>
		                </tr>
		            </table>
		        </td>
		    </tr>
		    <tr>
		        <td colspan="7" class="f-size-20 align-l pad-left-50">Number of Pages : ______</td>
		        <td colspan="7" class="f-size-20 align-r pad-right-100 pad-bot-10">Number of Employee(s): <b><?php echo $results['emp_count']; ?></b></td>
		    </tr>

		    <tr>
		        <td colspan="11"><br></td>
		        <td colspan="3" class="align-l td-border-light-right td-border-light-left td-border-light-bottom td-border-light-top">
		            <table class="table-max f-size-16">
		                <tr>
		                    <td colspan="3"><b>[ E ]</b></td>
		                </tr>
		                <tr>
		                    <td colspan="3">ORS/BURS No. _______________</td>
		                </tr>
		                <tr>
		                    <td colspan="3" height="30px">Date: ________________________</td>
		                </tr>
		                <tr>
		                    <td colspan="3" height="30px">JEV No. ______________________</td>
		                </tr>
		                <tr>
		                    <td colspan="3" height="30px">Date: ________________________</td>
		                </tr>
		            </table>
		        </td>
		    </tr>
		</table>
       	<br>
        <span class="f-size-7">
        	LEGENDS:
        	<?php foreach ($deductions as $deduction):
        		echo '['. $deduction['deduction_code'] .'] - ' . $deduction['deduction_name'] . '; ';
        	endforeach;?>
        </span>
        
    </div>
</body>
<?php else :?>

<div class="wrapper">
	<form id="test_report">
		<p style="text-align: center">No data available.</p>
	</form>
</div>

<?php endif;?>
</html>