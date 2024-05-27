<html>
<head>
  
  <title>General Payroll Alphalist per Office</title>
   <link rel="stylesheet" href="<?php echo base_url().PATH_CSS ?>reports.css" type="text/css" />
</head>

<body>
<table class="center-50 f-size-12">
	<tbody>
		<tr>
			<td class="align-r" width="70"><?php echo nbs(50) ?><img src="<?php echo base_url().PATH_IMG ?>doh_logo.png" width=61 height=61></img></td>
			<td class="align-c" style="padding-bottom:10px; padding-left: -100px;"><b>GENERAL PAYROLL<br><span style="font-size:14px;">DEPARTMENT OF HEALTH<br><?php echo $period_detail['office_name'] ?><br></span>AlphaList</td>
		</tr>
	</tbody>
</table>
<table class="cont-2 table-max">
  <tbody>
  <tr>
    <td colspan="14" height="21" align="left" valign="bottom"><b>Entity Name:<u><?php echo nbs(5) . $period_detail['office_name'] . nbs(20);?></u></b></td>
    <td colspan="3" align="left" valign="bottom">Payroll No._______________</td>
  </tr>
  <tr>
    <td colspan="14" height="21" align="left" valign="bottom"><b><b>Fund Cluster:________________________</b></td>
    <td colspan="3" align="left" valign="bottom">Sheet __ of {nbpg} Sheets</td>
  </tr>
  <tr>
    <td colspan="14" height="21" align="left" valign="bottom">We acknowledge receipt of cash shown opposite our name as full compensation of services rendered for the period covered</td>
    <td colspan="3" align="left" valign="bottom"></td>
 </tr>
</tbody>
</table>

<table border="1" class="table-max">
    <tr>
        <th class="pad-2" rowspan="2" colspan="1" align="center">Serial No.</th>
        <th class="pad-2" rowspan="2" colspan="1" align="center">Name</th>
        <th class="pad-2" rowspan="2" colspan="1" align="center">Position</th>
        <th class="pad-2" rowspan="2" colspan="1" align="center">Employee<br>No.</th>
        <th class="pad-2" colspan="5" align="center">COMPENSATIONS</th>
        <th class="pad-2" colspan="6" align="center">DEDUCTIONS</th>
        <th class="pad-2" rowspan="2" colspan="1" align="center">Net Amount<br> Due</th>
        <th class="pad-2" rowspan="2" colspan="1" align="center" width="150">Signature of Recipient</th>
    </tr>
    <tr>
        <th class="pad-2" colspan="1" align="center">Salaries and<br> Wages-<br>Regular</th>
        <th class="pad-2" colspan="1" align="center">Allowances</th>
        <th class="pad-2" colspan="1" align="center">RATA</th>
        <th class="pad-2" colspan="1" align="center">Other<br>Allowances</th>
        <th class="pad-2" colspan="1" align="center">Gross Amount<br>Earned</th>
        <?php
            foreach ($deduction_headers as $key => $d) {
                echo '<th class="pad-2" colspan="1" align="center">' . $d['remittance_payee_name'] . '</th>';
            }
        ?>
        <th class="pad-2" colspan="1">Total<br>Deductions</th>
    </tr>
    <?php
        $key = 0;
        foreach ($compensation_records as $c) {
            
            $employee_id = $c['employee_id'];
            $amounts = $c['amount'];
            echo '<tr>
                    <td class="pad-2" colspan="1" align="right">' . (++$key) . '</td>
                    <td class="pad-2" colspan="1">' . $c['employee_name'] . '</td>
                    <td class="pad-2" colspan="1">' . $c['position_name'] . '</td>
                    <td class="pad-2" colspan="1" align="center">' . $c['agency_employee_id'] . '</td>';
            $gross_earned = 0;
            foreach ($amounts as $amount) {
                $gross_earned += $amount;
                echo '<td class="pad-2" colspan="1" align="right">' . number_format($amount,2) . '</td>';
            }
            echo '<td class="pad-2" colspan="1" align="right">' . number_format($gross_earned,2) . '</td>';
            $total_deductions = 0;
            
                   $total_deductions += $deduction_records[$employee_id]['bir'] + $deduction_records[$employee_id]['gsis'] + $deduction_records[$employee_id]['pagibig'] + $deduction_records[$employee_id]['philhealth'] + $deduction_records[$employee_id]['others'];

                ?>
                    <td class="pad-2" colspan="1" align="right"><?php echo ISSET($deduction_records[$employee_id]['bir'])?number_format($deduction_records[$employee_id]['bir'], 2):"0.00"?></td>
                    <td class="pad-2" colspan="1" align="right"><?php echo ISSET($deduction_records[$employee_id]['gsis'])?number_format($deduction_records[$employee_id]['gsis'], 2):"0.00"?></td>
                    <td class="pad-2" colspan="1" align="right"><?php echo ISSET($deduction_records[$employee_id]['pagibig'])?number_format($deduction_records[$employee_id]['pagibig'], 2):"0.00"?></td>
                    <td class="pad-2" colspan="1" align="right"><?php echo ISSET($deduction_records[$employee_id]['philhealth'])?number_format($deduction_records[$employee_id]['philhealth'], 2):"0.00"?></td>
                    <td class="pad-2" colspan="1" align="right"><?php echo ISSET($deduction_records[$employee_id]['others'])?number_format($deduction_records[$employee_id]['others'], 2):"0.00"?></td>
                <?php 

            echo '<td class="pad-2" colspan="1" align="right">' . number_format($total_deductions,2) . '</td>';
            echo '<td class="pad-2" colspan="1" align="right">' . number_format($gross_earned - $total_deductions,2) . '</td>';
            echo '<td class="pad-2" colspan="1" align="right">&nbsp;</td></tr>';
        }
    ?>
    <tr>
        <td colspan="1" align="center" valign="top" width="50" class="pad-top-10" style="border: 0px">A</td>
        <td colspan="6" style="border: 0px">
            <table class="table-max">
                <tr>
                    <td colspan="6" class="pad-bot-50 pad-top-10" width="600"><b>CERTIFIED: </b>Services duly rendered as stated.</td>
                </tr>
                <tr>
                    <td >&nbsp;</td>
                    <td align="center">________________________________________________________________</td>
                    <td >&nbsp;</td>
                    <td align="center">__________________________</td>
                    <td >&nbsp;</td>
                </tr>
                <tr>
                    <td >&nbsp;</td>
                    <td align="center" class="pad-bot-10">Signature over Printed Name of Authorized Officer</td>
                    <td >&nbsp;</td>
                    <td align="center" class="pad-bot-10">Date</td>
                    <td >&nbsp;</td>
                </tr>
            </table>
        </td>
        <td colspan="1" align="center" valign="top" width="50" class="pad-top-10">C</td>
        <td colspan="9">
            <table class="table-max">
                <tr>
                    <td colspan="8" class="pad-bot-50 pad-top-10"><b>APPROVED FOR PAYMENT: ______________________________________________________________________________</b></td>
                </tr>
                <tr>
                    <td >&nbsp;</td>
                    <td align="center">________________________________________________________________</td>
                    <td >&nbsp;</td>
                    <td align="center">__________________________</td>
                    <td >&nbsp;</td>
                </tr>
                <tr>
                    <td >&nbsp;</td>
                    <td align="center" class="pad-bot-10">(Signature over Printed Name)<br>Head of Agency/Authorized Representative</td>
                    <td >&nbsp;</td>
                    <td align="center" class="pad-bot-10">Date</td>
                    <td >&nbsp;</td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td colspan="1" align="center" valign="top" width="50" class="pad-top-10">B</td>
        <td colspan="6">
            <table class="table-max">
                <tr>
                    <td colspan="6" class="pad-bot-50 pad-top-10" width="600"><b>CERTIFIED: </b>Supporting documents complete and proper, and cash available in the amount of P ____________________</td>
                </tr>
                <tr>
                    <td >&nbsp;</td>
                    <td align="center">________________________________________________________________</td>
                    <td >&nbsp;</td>
                    <td align="center">__________________________</td>
                    <td >&nbsp;</td>
                </tr>
                <tr>
                    <td >&nbsp;</td>
                    <td align="center" class="pad-bot-10">(Signature over Printed Name)<br>Head of Accounting Division/Unit</td>
                    <td >&nbsp;</td>
                    <td align="center" class="pad-bot-10">Date</td>
                    <td >&nbsp;</td>
                </tr>
            </table>
        </td>
        <td colspan="1" align="center" valign="top" width="50" class="pad-top-10">D</td>
        <td colspan="5">
            <table class="table-max">
                <tr>
                    <td colspan="5" class="pad-bot-50 pad-top-10"><b>CERTIFIED: </b>Supporting documents complete and proper, and cash available in the amount of P ____________________</td>
                </tr>
                <tr>
                    <td >&nbsp;</td>
                    <td align="center">_______________________________________________________</td>
                    <td >&nbsp;</td>
                    <td align="center">__________________________</td>
                    <td >&nbsp;</td>
                </tr>
                <tr>
                    <td >&nbsp;</td>
                    <td align="center" class="pad-bot-10">(Signature over Printed Name)<br>Head of Accounting Division/Unit</td>
                    <td >&nbsp;</td>
                    <td align="center" class="pad-bot-10">Date</td>
                    <td >&nbsp;</td>
                </tr>
            </table>
        </td>
        <td colspan="1" align="center" valign="top" width="50" class="pad-top-10">E</td>
        <td colspan="3" >
            <table class="table-max">
                <tr>
                    <td colspan="3" class="pad-bot-50"></td>
                </tr>
                <tr>
                    <td colspan="3">ORS/BURS No. _______________</td>
                </tr>
                <tr>
                    <td colspan="3">Date: ________________________</td>
                </tr>
                <tr>
                    <td colspan="3">JEV No. ______________________</td>
                </tr>
                <tr>
                    <td colspan="3">Date: ________________________</td>
                </tr>
            </table>
        </td>
    </tr>
</table>
<!-- ************************************************************************** -->
</body>

</html>