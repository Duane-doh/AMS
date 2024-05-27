<html>
  <head>
    <title>General Payroll Summary Grand Total</title>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url() . PATH_CSS ?>reports.css" type="text/css"/>
  </head>
  <style type="text/css">
    table
    {
      font-family: "Arial", Times, serif;
      font-size: 13px;
    }
    /*    table tr td {
          border : 1px solid gray;
        }*/
  </style>
  <body>
    <div style="width: 100%; text-align: center;">
      <div>
        <img src="<?php echo base_url() . PATH_IMG ?>doh_logo.png" width="90" height="90" style="float:left;margin-left:30px;margin-right:-250px;" />
      </div>
      <div style="margin-top:-95px;">
        <span class="bold" style="font-size: 18px;">General Payroll</span><br/>
        <span class="bold" style="font-size: 15px;">DEPARTMENT OF HEALTH JOB ORDER</span><br/>
        <span class="bold" style="font-size: 18px;">SUMMARY SHEET</span><br/>
        <span class="bold" style="font-size: 18px;"><?php echo $payroll_date_text; ?></span>
      </div>
    </div>

    <table class="table-max" style="margin-top:80px;">
      <?php $total_compensation_amount = 0; ?>
      <?php foreach ($compensation_records as $key => $c_record) { ?>
        <?php $total_compensation_amount += $c_record['amount']; ?>
        <?php if ($display_half): ?>
          <tr>
            <td colspan="4" width="150" height="15" class="td-right-bottom"><?php echo $c_record['compensation_name']; ?> </td>
            <td colspan="1" height="15" class="td-left-bottom" width="40">&nbsp;: <?php echo ($key == 0) ? '&nbsp;&nbsp;&nbsp;&#8369;&nbsp;&nbsp;&nbsp;' : '' ?> </td>
            <td colspan="2" height="15" class="td-right-bottom"><?php echo number_format($c_record['amount'], 2); ?></td>
            <td colspan="9" height="15" class="td-left-bottom">&nbsp;</td>
          </tr>
        <?php else: ?>
          <tr>
            <!--modesto--> 
            <?php
            if ($c_record['compensation_name'] == 'Basic Salary') {
              $c_record['compensation_name'] = 'Salary for the period ';
            } else if ($c_record['compensation_name'] == 'Underpayment') {
              $c_record['compensation_name'] = 'Add : Underpayment for the period ';
            }
            ?>
            <td colspan="4" width="150" height="15" class="td-right-bottom"><span style="font-weight:bold;"><?php echo $c_record['compensation_name']; ?></span></td>
            <td colspan="1" height="15" class="td-left-bottom" width="40">&nbsp;: <?php echo ($key == 0) ? '&nbsp;&nbsp;&nbsp;&#8369;&nbsp;&nbsp;&nbsp;' : '' ?> </td>
            <td colspan="2" height="15" class="td-right-bottom"><?php echo ($c_record['less_absence_flag'] == YES) ? number_format($c_record['orig_amount'], 2) : number_format($c_record['amount'], 2); ?></td>
            <td colspan="9" height="15" class="td-left-bottom">&nbsp;</td> 
          </tr>
          <?php if (($c_record['less_absence_flag'] == YES) && ($c_record['compensation_name'] == 'Salary for the period ')): ?>
            <tr>
              <td colspan="4" width="150" height="15" class="td-right-bottom"><span style="font-weight:bold;">Less : (Absences/Tardiness)</span></td>
              <td colspan="1" height="15" class="td-left-bottom" width="40">&nbsp;:<?php echo ($key == 0) ? '&nbsp;&nbsp;&nbsp;&#8369;&nbsp;&nbsp;&nbsp;' : '' ?> </td>
              <td colspan="2" height="15" class="td-right-bottom"><?php echo number_format($c_record['less_amount'], 2); ?></td>
              <td colspan="9" height="15" class="td-left-bottom">&nbsp;</td>
            </tr>
            <tr>
              <td colspan="4" height="10" class="td-right-bottom"></td>
              <td colspan="1" style="border-bottom: 2px solid #000000" class="td-left-bottom"></td>
              <td colspan="2" style="border-bottom: 2px solid #000000" class="td-left-bottom"></td>
              <td colspan="9" height="10" class="td-left-bottom">&nbsp;</td>
            </tr>
            <tr>
              <td colspan="4" width="150" height="15" class="td-right-bottom"><span style="font-weight:bold;">Gross Salary for the period</span></td>
              <td colspan="1" height="15" class="td-left-bottom" width="40">:<?php echo ($key == 0) ? '&nbsp;&nbsp;&nbsp;&#8369;&nbsp;&nbsp;&nbsp;' : '' ?> </td>
              <td colspan="2" height="15" class="td-right-bottom"><?php echo number_format($c_record['amount'], 2); ?></td>
              <td colspan="9" height="15" class="td-left-bottom">&nbsp;</td>
            </tr>
          <?php endif; ?>
        <?php endif; ?>
      <?php } ?> 
      <tr>
        <td colspan="4" height="10" class="td-right-bottom"></td>
        <td colspan="1" style="border-bottom: 3px solid #000000" class="td-left-bottom"></td>
        <td colspan="2" style="border-bottom: 3px solid #000000" class="td-left-bottom"></td>
        <td colspan="9" height="15" class="td-left-bottom">&nbsp;</td>
      </tr>

      <tr>
        <td colspan="4" height="35" class="td-right-middle"><b><?php echo ($display_half) ? 'GROSS' : '' ?></b></td>
        <td colspan="1" align="left" valign="middle"><b><?php echo ($display_half) ? ':' : '' ?><?php echo nbs(3); ?></b></td>
        <td colspan="2" align="right" valign="middle" width="150"><b>&#8369;</b><?php echo nbs(3) . number_format($total_compensation_amount, 2); ?></td>
        <td colspan="9" height="15" class="td-left-bottom">&nbsp;</td>
      </tr>

      <tr>
        <td colspan="4" height="28" class="td-right-middle"><b>( - ) DEDUCTIONS</b></td>
      </tr>
      <tr>
        <td colspan="16" class="td-left-bottom">&nbsp;</td>
      </tr>
      <?php
      $total_deduction_amount = 0;
      $merge_deduction_records = array_merge($deduction_records[1], $deduction_records[2]);
      $sort_deduction_records  = array();
      foreach ($merge_deduction_records as $key => $value) {
        $total_deduction_amount += isset($value['amount']) ? $value['amount'] : '';

        switch ($value['deduction_name']) {
          case 'EWT' :
            $new_deduction_name = 'Expanded Withholding Tax (EWT)';
            break;
          case 'GMP' :
            $new_deduction_name = 'Government Money Payments (GMP)';
            break;
          case 'SSS' :
            $new_deduction_name = 'SSS Contribution';
            break;
          case 'MP2' :
            $new_deduction_name = 'Modified Pag-IBIG II (MP2)';
            break;
          case 'PAG-IBIG' :
            $new_deduction_name = 'Pag-IBIG';
            break;
          case 'Overpayment' :
            $new_deduction_name = 'OverPayment';
            break;
          case 'PHIC' :
            $new_deduction_name = 'PhilHealth';
            break;
          default:
            $new_deduction_name = $value['deduction_name'];
        }
        ?>
        <tr>
          <td colspan="4" height="10" class="td-right-bottom">
            <span style="font-weight:bold;"><?php echo (!empty($value['deduction_name'])) ? $new_deduction_name : "&nbsp;"; ?></span>
          </td>
          <td colspan="1" class="td-left-bottom">
            <span><?php echo (!empty($value['deduction_name'])) ? '&nbsp;:' : ''; ?></span>
          </td>
          <td colspan="2" class="td-right-bottom">
            <span><?php echo (!EMPTY($value['deduction_name'])) ? number_format($value['amount'], 2) : ""; ?></span>
          </td>
        </tr>
        <?php
      }
      ?>
      <tr>
        <!--<td colspan="8" height="35" class="td-right-bottom"></td>-->
        <td colspan="4" align="right" valign="middle" height="10"><b>Total Deductions</b></td>
        <td colspan="1" class="td-left-bottom"><span>&nbsp;:</span></td>
        <td colspan="2" align="right" valign="middle"><b>&#8369;</b><?php echo nbs(3) . number_format($total_deduction_amount, 2); ?></td>
      </tr>
      <tr>
       <!--<td colspan="8" height="35" class="td-right-bottom"></td>-->
        <td colspan="4" align="right" valign="middle" height="10"></td>
        <td colspan="1" class="td-left-bottom"></td>
        <td colspan="2" align="right" valign="middle"></td>
      </tr>
      <tr>
        <td colspan="4" align="right" valign="middle"><b>Total Net Salary</b></td>
        <td colspan="1" class="td-left-bottom"><span>&nbsp;:</span></td>
        <?php $total_net = $total_compensation_amount - $total_deduction_amount; ?>
        <td colspan="2" align="right" valign="middle" width="150" style="border-bottom: 3px solid gray;"><b>&#8369;</b><?php echo nbs(3) . number_format($total_net, 2); ?></td>
      </tr>

      <tr>
        <td colspan="16">&nbsp;</td>
      </tr>

      <tr>
        <td colspan="16">&nbsp;</td>
      </tr>
      <?php
      if ($display_half) {
        foreach ($net_pay_records as $key => $np) {
          echo '<tr>
                <td colspan="8" class="td-right-bottom"></td>
                <td colspan="5" align="right" valign="middle"><b>' . ($key == 0 ? 'NET PAY FOR' . ':' : '') . '</b></td>
                <td colspan="3" align="right" valign="middle">Half ' . ($key + 1) . '&nbsp;:&nbsp;&nbsp;&#8369; ' . number_format($np['net_pays'], 2) . '</td>
              </tr>';
        }
      }
      ?>
    </table>
    <!-- ************************************************************************** -->
  </body>

</html>
