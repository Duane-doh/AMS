<!DOCTYPE html>
<html>
  <?php if (!EMPTY($header)): ?>
    <head>
      <title>General Payslip for Contracts of Service</title>
      <link rel="stylesheet" href="<?php echo base_url() . PATH_CSS ?>reports.css" type="text/css" />
    </head>
    <style type="text/css">
      .myTableBg4 {
        background-image:url('<?php echo base_url() . PATH_IMG ?>doh_logo.png');
        background-image-resolution: 400dpi;
        background-image-resize: 3;
        background-image-opacity: 0.3;
        background-repeat: no-repeat;
        background-position: center center;
        font-weight: bold;
      }

      img.checkmark:after {
        content: url('<?php echo base_url(); ?>static/images/check_black.png');
      }

    </style>
    <body>


      <?php
      $ctr     = 0;
      $total_1 = 0;
      $total_2 = 0;
      foreach ($header as $hdr):
        $net_bs_1 = $basic_salary[$hdr['employee_id']]['1st']['amount'];
        $net_bs_2 = $basic_salary[$hdr['employee_id']]['2nd']['amount'];

        $com_ded_hdr_count = count($deduction_hdr[$hdr['employee_id']]) + count($compensation_hdr[$hdr['employee_id']]);
        ?>
        <div class="pad-bot-10">  
          <table class="center-85 cont-5 f-size-7 myTableBg4">
            <tr>
              <td class="td-border-3 td-left-top" colspan=11 height="20" align="left" valign=top style="overflow:hidden;white-space: nowrap;">DEPARTMENT OF HEALTH | <?php echo $office['name'] ?><br>Resp. Desc : <?php echo $responsibility_center[$hdr['employee_id']] ?></td>
              <td class="td-border-top td-border-bottom td-border-right td-right-bottom f-size-7" colspan=3 align="right" valign=bottom style="overflow:hidden;white-space: nowrap;">Note: Please notify Personnel <br /> Administration Division for any correction. </td>
            </tr>
            <tr>
              <td class="td-border-top td-border-left td-border-right td-left-bottom" colspan=6 align="left" valign=bottom><?php echo $hdr['full_name']; ?></td>
              <td class="td-border-top td-border-left td-left-bottom" colspan=4 align="left" valign=bottom>(SG : <?php echo $hdr['salary_grade']; ?>)</td>
              <td class="td-border-top td-right-bottom">Page</td>
              <td class="td-border-top td-left-bottom">:</td>
              <td class="td-border-top td-left-bottom"></td>
              <td class="td-border-top td-border-right td-right-bottom">Rec No : <?php echo $ctr + 1; ?></td>
            </tr>
            <tr style="height:300px;">
              <td class="td-border-bottom td-border-left td-border-right td-left-bottom" colspan=6 align="left" valign=bottom><?php echo $hdr['position_name']; ?></td>
              <td class="td-border-bottom td-border-left td-left-bottom" colspan=4 align="left" valign=bottom>BIR No. <?php echo $hdr['tin']; ?></td>
              <td class="td-border-bottom td-right-bottom" align="right" valign=bottom>Status</td>
              <td class="td-border-bottom td-left-bottom" align="left" valign=bottom>:</td>
              <td class="td-border-bottom td-border-right td-left-bottom" colspan=2 align="left" valign=bottom><?php echo $hdr['status']; ?></td>
            </tr> 
            <tr>
              <td class="td-border-3 td-right-bottom" align="right" valign=bottom>For the Period</td>
              <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000" align="left" valign=bottom>:</td>
              <td class="td-border-top td-border-bottom td-border-right td-left-bottom" colspan=2 align="left" valign=bottom><?php echo $month_text . " 1 - 15, " . $year; ?></td>
              <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" colspan=2 align="left" valign=bottom>WD: <?php echo $results[$hdr['employee_id']]['1st']['working_days'] . '/' . ($results[$hdr['employee_id']]['1st']['working_days'] + $results[$hdr['employee_id']]['2nd']['working_days']); ?></td>
              <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="left" valign=bottom>WD : <?php echo $results[$hdr['employee_id']]['2nd']['working_days'] . '/' . ($results[$hdr['employee_id']]['1st']['working_days'] + $results[$hdr['employee_id']]['2nd']['working_days']); ?></td>
              <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-right: 1px solid #000000; border-left: 1px solid #000000" colspan=7 align="left" valign=bottom>For the Period : <?php echo $month_text . " 16 - " . cal_days_in_month(CAL_GREGORIAN, $month, $year) . ", " . $year; ?> </td>
            </tr>
            <tr>
              <?php
              $workingdays1      = $results[$hdr['employee_id']]['1st']['working_days'];
              $workingdays2      = $results[$hdr['employee_id']]['2nd']['working_days'];
              $workingdays3      = $workingdays1 + $workingdays2;

              $basic               = ISSET($results[$hdr['employee_id']]['1st']['basic_amount']) ? number_format(($results[$hdr['employee_id']]['1st']['basic_amount']), 2) : number_format($results[$hdr['employee_id']]['2nd']['basic_amount']);
              $basic_salary_parsed = floatval(preg_replace('/[^\d.]/', '', $basic));
              $premium_rate        = number_format(($premium['rate'] / 100) * $basic_salary_parsed, 2);
              $rate_month          = number_format($basic_salary_parsed + floatval(preg_replace('/[^\d.]/', '', $premium_rate)), 2);
              $rate_month_parsed   = floatval(preg_replace('/[^\d.]/', '', $rate_month));

              $gross_salary1         = number_format((($workingdays1 / $workingdays3) * $rate_month_parsed), 2);
              $gross_salary2         = number_format((($workingdays2 / $workingdays3) * $rate_month_parsed), 2);
              ?>
              x
              <td class="td-border-3 td-right-bottom" align="right" valign=bottom>Rate/Month </td>
              <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000" align="left" valign=bottom>:</td>
              <td class="td-border-top td-border-bottom td-border-right td-right-bottom" align="right" valign=bottom><?php echo $rate_month; ?></td>
              <td colspan="10" class="td-border-top td-border-bottom td-border-right td-right-bottom" align="left" valign=bottom>Amount Rate : <?php echo ISSET($results[$hdr['employee_id']]['1st']['basic_amount']) ? number_format(($results[$hdr['employee_id']]['1st']['basic_amount']), 2) : number_format($results[$hdr['employee_id']]['2nd']['basic_amount']); ?> + <?php echo $premium['rate'] ?>% Premium : <?php echo $premium_rate; ?></td>
              <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="left" valign=bottom>ID No / Picture</td>
            </tr>
            <tr>
              <td class="td-border-3 td-right-bottom" align="right" valign=bottom>Gross Salary </td>
              <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000" align="left" valign=bottom>:</td>
              <td class="td-border-top td-border-bottom td-border-right td-right-bottom" align="right" valign=bottom><?php echo$gross_salary1; ?></td>
              <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" colspan=3 align="left" valign=bottom>Less Abs/Undertime</td>
              <td colspan="2" class="td-border-3 td-right-bottom" align="right" valign=bottom>Gross Salary </td>
              <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000" align="left" valign=bottom>:</td>
              <td class="td-border-top td-border-bottom td-border-right td-right-bottom" align="right" valign=bottom><?php echo $gross_salary2; ?></td>
              <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" colspan=3 align="left" valign=bottom>Less Abs/Undertime</td>
              <td style="border-top: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="left" valign=bottom></td>
            </tr>
            <tr>
              <td class="td-border-top td-border-left td-left-bottom" align="right" valign=bottom>Rate/Day</td>
              <td class="td-border-top td-left-bottom">:</td>
              <td class="td-border-top td-border-right td-right-bottom"><?php echo number_format($rates[$hdr['employee_id']]['1st']['rate_day'], 2); ?></td>




                                                                                                    <!--              <td class="td-border-top td-border-left td-left-bottom" align="left" valign=bottom>Day</td>
                                                                                                    <td class="td-border-top td-left-bottom">:</td>
                                                                                                    <td style="border-top: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom><?php echo ($less_amounts[$hdr['employee_id']]['1st']['day'] > 0) ? $less_amounts[$hdr['employee_id']]['1st']['day'] : "0"; ?></td>-->


              <?php
              $rates_per_day_1st     = $rates[$hdr['employee_id']]['1st']['rate_day'];
              $deduction_per_day_1st = ($less_amounts[$hdr['employee_id']]['1st']['day'] > 0) ? $less_amounts[$hdr['employee_id']]['1st']['day'] : 0;

              $deduction_per_day_1st_total = $rates_per_day_1st * $deduction_per_day_1st;
              ?>                             
              <td style="overflow:hidden;white-space: nowrap;">
                &nbsp;&nbsp;<span>Day</span>
                <span> :</span>
              </td>
              <td style="overflow:hidden;white-space: nowrap;">
                <span> <?php echo ($less_amounts[$hdr['employee_id']]['1st']['day'] > 0) ? $less_amounts[$hdr['employee_id']]['1st']['day'] : "0"; ?></span>
              </td>
              <td style="overflow:hidden;white-space: nowrap;">
                <span><?php echo number_format($deduction_per_day_1st_total, 2); ?></span>
              </td>



              <td class="td-border-top td-border-left td-left-bottom" colspan=2 align="right" valign=bottom>Rate/Day</td>
              <td class="td-border-top td-left-bottom">:</td>
              <td class="td-border-top td-border-right td-right-bottom"><?php echo number_format($rates[$hdr['employee_id']]['2nd']['rate_day'], 2); ?></td>

                                                                                <!--              <td class="td-border-top td-border-left td-left-bottom" align="left" valign=bottom>Day</td>
                                                                                 <td class="td-border-top td-left-bottom">:</td>
                                                                                 <td style="border-top: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom><?php echo ($less_amounts[$hdr['employee_id']]['2nd']['day'] > 0) ? $less_amounts[$hdr['employee_id']]['2nd']['day'] : "0"; ?></td>-->


              <?php
              $rates_per_day_2nd           = $rates[$hdr['employee_id']]['2nd']['rate_day'];
              $deduction_per_day_2nd       = ($less_amounts[$hdr['employee_id']]['2nd']['day'] > 0) ? $less_amounts[$hdr['employee_id']]['2nd']['day'] : 0;

              $deduction_per_day_2nd_total = $rates_per_day_2nd * $deduction_per_day_2nd;
              ?>

              <td style="overflow:hidden;white-space: nowrap;">
                &nbsp;&nbsp;<span>Day</span>
                <span> :</span>
              </td>
              <td style="overflow:hidden;white-space: nowrap;">
                <span> <?php echo ($less_amounts[$hdr['employee_id']]['2nd']['day'] > 0) ? $less_amounts[$hdr['employee_id']]['2nd']['day'] : "0"; ?></span>
              </td>
              <td style="overflow:hidden;white-space: nowrap;">
                <span><?php echo number_format($deduction_per_day_2nd_total, 2); ?></span>
              </td>

              <td class=" td-border-left td-border-right td-left-bottom" align="left" valign=bottom>ID : <?php echo $hdr['agency_employee_id']; ?></td>
            </tr>
            <tr>
              <td style="border-left: 1px solid #000000" align="right" valign=bottom>Rate/Hour</td>
              <td align="left" valign=bottom>:</td>
              <td style="border-right: 1px solid #000000" align="right" valign=bottom><?php echo number_format($rates[$hdr['employee_id']]['1st']['rate_hour'], 4); ?></td>


                                                                                            <!--              <td style="border-left: 1px solid #000000" align="right" valign=bottom>Hour</td>
                                                                                            <td align="left" valign=bottom>:</td>
                                                                                            <td style="border-right: 1px solid #000000" align="center" valign=bottom><?php echo ($less_amounts[$hdr['employee_id']]['1st']['hour'] > 0) ? $less_amounts[$hdr['employee_id']]['1st']['hour'] : "0"; ?></td>-->

              <?php
              $rates_per_hour_1st          = $rates[$hdr['employee_id']]['1st']['rate_hour'];
              $deduction_per_hour_1st      = ($less_amounts[$hdr['employee_id']]['1st']['hour'] > 0) ? $less_amounts[$hdr['employee_id']]['1st']['hour'] : 0;

              $deduction_per_hour_1st_total = $rates_per_hour_1st * $deduction_per_hour_1st;
              ?> 
              <td style="overflow:hidden;white-space: nowrap;">
                <span>Hour</span>
                <span> :</span>
              </td>
              <td style="overflow:hidden;white-space: nowrap;">
                <span> <?php echo ($less_amounts[$hdr['employee_id']]['1st']['hour'] > 0) ? $less_amounts[$hdr['employee_id']]['1st']['hour'] : "0"; ?></span>
              </td>
              <td style="overflow:hidden;white-space: nowrap;">
                <span><?php echo number_format($deduction_per_hour_1st_total, 2); ?></span>
              </td>


              <td style="border-left: 1px solid #000000" colspan=2 align="right" valign=bottom>Rate/Hour</td>
              <td align="left" valign=bottom>:</td>
              <td style="border-right: 1px solid #000000" align="right" valign=bottom><?php echo number_format($rates[$hdr['employee_id']]['2nd']['rate_hour'], 4); ?></td>

                                                                                <!--              <td style="border-left: 1px solid #000000" align="right" valign=bottom>Hour</td>
                                                                                <td align="left" valign=bottom>:</td>
                                                                                <td style="border-right: 1px solid #000000" align="center" valign=bottom><?php echo ($less_amounts[$hdr['employee_id']]['2nd']['hour'] > 0) ? $less_amounts[$hdr['employee_id']]['2nd']['hour'] : "0"; ?></td>-->
              <?php
              $rates_per_hour_2nd           = $rates[$hdr['employee_id']]['2nd']['rate_hour'];
              $deduction_per_hour_2nd       = ($less_amounts[$hdr['employee_id']]['2nd']['hour'] > 0) ? $less_amounts[$hdr['employee_id']]['2nd']['hour'] : 0;

              $deduction_per_hour_2nd_total = $rates_per_hour_2nd * $deduction_per_hour_2nd;
              ?> 
              <td style="overflow:hidden;white-space: nowrap;">
                <span>Hour</span>
                <span> :</span>
              </td>
              <td style="overflow:hidden;white-space: nowrap;">
                <span> <?php echo ($less_amounts[$hdr['employee_id']]['2nd']['hour'] > 0) ? $less_amounts[$hdr['employee_id']]['2nd']['hour'] : "0"; ?></span>
              </td>
              <td style="overflow:hidden;white-space: nowrap;">
                <span><?php echo number_format($deduction_per_hour_2nd_total, 2); ?></span>
              </td>


              <td style="border-left: 1px solid #000000; border-right: 1px solid #000000" align="left" valign=bottom>PHIC# : </td>
            </tr>
            <tr>
              <td class="td-border-bottom td-border-left td-right-bottom" align="right" valign=bottom>Rate/Min</td>
              <td class="td-border-bottom td-left-bottom" align="left" valign=bottom>:</td>
              <td class="td-border-bottom td-border-right td-right-bottom" align="right" valign=bottom><?php echo number_format($rates[$hdr['employee_id']]['1st']['rate_minute'], 4); ?></td>

                                                                                        <!--              <td class="td-border-bottom td-border-left td-right-bottom" align="right" valign=bottom>Mins</td>
                                                                                        <td class="td-border-bottom td-left-bottom" align="left" valign=bottom>:</td>
                                                                                        <td class="td-border-bottom td-border-right td-center-bottom" align="center" valign=bottom><?php echo ($less_amounts[$hdr['employee_id']]['1st']['minute'] > 0) ? $less_amounts[$hdr['employee_id']]['1st']['minute'] : "0"; ?></td>-->
              <?php
              $rates_per_minute_1st         = $rates[$hdr['employee_id']]['1st']['rate_minute'];
              $deduction_per_minute_1st     = ($less_amounts[$hdr['employee_id']]['1st']['minute'] > 0) ? $less_amounts[$hdr['employee_id']]['1st']['minute'] : 0;

              $deduction_per_minute_1st_total = $rates_per_minute_1st * $deduction_per_minute_1st;
              ?> 

              <td style="overflow:hidden;white-space: nowrap;">
                <span>Mins</span>
                <span> :</span>
              </td>
              <td style="overflow:hidden;white-space: nowrap;">
                <span> <?php echo ($less_amounts[$hdr['employee_id']]['1st']['minute'] > 0) ? $less_amounts[$hdr['employee_id']]['1st']['minute'] : "0"; ?></span>
              </td>
              <td style="overflow:hidden;white-space: nowrap;">
                <span><?php echo number_format($deduction_per_minute_1st_total, 2); ?></span>
              </td>

              <td class="td-border-bottom td-border-left td-right-bottom" colspan=2 align="right" valign=bottom>Rate/Min</td>
              <td class="td-border-bottom td-left-bottom" align="left" valign=bottom>:</td>
              <td class="td-border-bottom td-border-right td-right-bottom" align="right" valign=bottom><?php echo number_format($rates[$hdr['employee_id']]['2nd']['rate_minute'], 4); ?></td>

                                                                                <!--              <td class="td-border-bottom td-border-left td-right-bottom" align="right" valign=bottom>Mins</td>
                                                                                <td class="td-border-bottom td-left-bottom" align="left" valign=bottom>:</td>
                                                                                <td class="td-border-bottom td-border-right td-center-bottom" align="center" valign=bottom><?php echo ($less_amounts[$hdr['employee_id']]['2nd']['minute'] > 0) ? $less_amounts[$hdr['employee_id']]['2nd']['minute'] : "0"; ?></td>-->
                                                                           <!--<td class="td-border-bottom td-border-right td-center-bottom" align="center" valign=bottom><?php echo ($less_amounts[$hdr['employee_id']]['1st']['minute'] > 0) ? $less_amounts[$hdr['employee_id']]['1st']['minute'] : "0"; ?></td>-->-->
              <?php
              $rates_per_minute_2nd           = $rates[$hdr['employee_id']]['2nd']['rate_minute'];
              $deduction_per_minute_2nd       = ($less_amounts[$hdr['employee_id']]['2nd']['minute'] > 0) ? $less_amounts[$hdr['employee_id']]['2nd']['minute'] : 0;

              $deduction_per_minute_2nd_total = $rates_per_minute_2nd * $deduction_per_minute_2nd;
              ?> 
              <td style="overflow:hidden;white-space: nowrap;">
                <span>Mins</span>
                <span> :</span>
              </td>
              <td style="overflow:hidden;white-space: nowrap;">
                <span> <?php echo ($less_amounts[$hdr['employee_id']]['2nd']['minute'] > 0) ? $less_amounts[$hdr['employee_id']]['2nd']['minute'] : "0"; ?></span>
              </td>
              <td style="overflow:hidden;white-space: nowrap;">
                <span> <span><?php echo number_format($deduction_per_minute_2nd_total, 2); ?></span></span>
              </td>

              <td style="border-left: 1px solid #000000; border-right: 1px solid #000000" align="left" valign=bottom width="85"><?php echo $hdr['phic']; ?></td>
            </tr>
            <tr>
              <td class="td-border-top td-border-left td-left-bottom" align="right" valign="top">Net B. Salary</td>
              <td class="td-border-top td-left-bottom" valign="top">:</td>
              <td class="td-border-top td-border-right td-right-bottom" valign="top"><?php echo number_format($net_bs_1, 2); ?></td>
              <td style="border-top: 1px solid #000000; border-right: 1px solid #000000" align="left" valign="top" width="90" colspan="3" rowspan=<?php echo 2 + $com_ded_hdr_count; ?> align="left"><?php echo "Remarks" . nbs(3) . ":<br>" ?><?php echo!EMPTY($less_amounts[$hdr['employee_id']]['1st']['remarks']) ? $less_amounts[$hdr['employee_id']]['1st']['remarks'] : 'FT' ?></td>
              <td class="td-border-top td-border-left td-left-bottom" colspan=2 align="right" valign="top" width="90">Net B. Salary</td>
              <td class="td-border-top td-left-bottom" valign="top">:</td>
              <td class="td-border-top td-border-right td-right-bottom" valign="top"><?php echo number_format($net_bs_2, 2); ?></td>
              <td style="border-top: 1px solid #000000; border-right: 1px solid #000000" align="left" valign="top" width="90" colspan="3" rowspan=<?php echo 2 + $com_ded_hdr_count; ?> align="left"><?php echo "Remarks" . nbs(3) . ":<br>" ?><?php echo!EMPTY($less_amounts[$hdr['employee_id']]['2nd']['remarks']) ? $less_amounts[$hdr['employee_id']]['2nd']['remarks'] : 'FT' ?></td>
              <td class="td-border-bottom td-border-left td-border-right td-left-bottom" rowspan=<?php echo 3 + $com_ded_hdr_count; ?> align="left" valign=bottom><br></td>
            </tr>

            <!-- COMPENSATIONS -->
            <?php
            $total_compensation_1           = 0;
            $total_compensation_2           = 0;
            foreach ($compensation_hdr[$hdr['employee_id']] as $com):
              ?>
              <tr >
                <?php
                switch ($compensations[$hdr['employee_id']]['1st'][$com]['compensation_name']) {
                  case 'Underpayment':
                    $compensation_name = '(+) Undpymnt';
                    break;
                  default:
                    $compensation_name;
                }
                IF (!EMPTY($compensations[$hdr['employee_id']]['1st'][$com]) && ISSET($compensations[$hdr['employee_id']]['1st'][$com])):
                  $total_compensation_1 += $compensations[$hdr['employee_id']]['1st'][$com]['amount'];
                  ?>
                  <td style="border-left: 1px solid #000000" align="right" valign="bottom"><?php echo $compensation_name; ?></td>
                  <td align="left" valign=bottom>:</td>
                  <td style="border-right: 1px solid #000000" align="right" valign="bottom"><?php echo empty($compensations[$hdr['employee_id']]['1st'][$com]['amount']) ? '0.00' : $compensations[$hdr['employee_id']]['1st'][$com]['amount']; ?></td>
                  <?php ELSE: ?>
                  <td style="border-left: 1px solid #000000; border-right: 1px solid #000000;" colspan="3"></td>
                <?php ENDIF; ?>



                <?php
                switch ($compensations[$hdr['employee_id']]['1st'][$com]['compensation_name']) {
                  case 'Underpayment':
                    $compensation_name = '(+) Undpymnt';
                    break;
                  default:
                    $compensation_name = $compensations[$hdr['employee_id']]['1st'][$com]['compensation_name'];
                }
                IF (!EMPTY($compensations[$hdr['employee_id']]['2nd'][$com]) && ISSET($compensations[$hdr['employee_id']]['2nd'][$com])):
                  $total_compensation_2 += $compensations[$hdr['employee_id']]['2nd'][$com]['amount'];
                  ?>
                  <td style="border-left: 1px solid #000000" align="right" colspan=2 valign="bottom"><?php echo $compensation_name; ?></td>
                  <td align="left" valign=bottom>:</td>
                  <td style="border-right: 1px solid #000000" align="right" valign="bottom"><?php echo empty($compensations[$hdr['employee_id']]['2nd'][$com]['amount']) ? '0.00' : $compensations[$hdr['employee_id']]['2nd'][$com]['amount']; ?></td>
                  <?php ELSE: ?>
                  <td style="border-left: 1px solid #000000; border-right: 1px solid #000000;" colspan="4"></td>
                <?php ENDIF; ?>
              </tr>
              <?php
            endforeach;
            ?>

            <!-- DEDUCTIONS -->
            <?php
            $total_deduction_1 = 0;
            $total_deduction_2 = 0;
            foreach ($deduction_hdr[$hdr['employee_id']] as $deduct):
              ?>
              <tr>
                <?php
                if (!EMPTY($deductions[$hdr['employee_id']]['1st'][$deduct]) && ISSET($deductions[$hdr['employee_id']]['1st'][$deduct])):
                  $total_deduction_1 += $deductions[$hdr['employee_id']]['1st'][$deduct]['amount'];

                  switch ($deductions[$hdr['employee_id']]['1st'][$deduct]['deduction_name']) {
                    case 'PAG-IBIG':
                      $deduction_name = 'Pag-IBIG';
                      break;
                    case 'EWT':
                      $deduction_name = 'EWTax';
                      break;
                    case 'GMP':
                      $deduction_name = 'GMP Tax';
                      break;
                    case 'Overpayment':
                      $deduction_name = 'Ovr Pymt';
                      break;
                    case 'PHIC':
                      $deduction_name = '(-) PhilHealth';
                      break;
                    case 'SSS':
                      $deduction_name = 'SSS Contr.';
                      break;
                    case 'MP2':
                      $deduction_name = 'MP-2';
                      break;
                    case '8% Income Tax Rate':
                      $deduction_name = '8% Inc. Tax';
                      break;
                    default:
                      $deduction_name = $deductions[$hdr['employee_id']]['1st'][$deduct]['deduction_name'];
                  }
                  ?>
                  <td style="border-left: 1px solid #000000" align="right" valign="bottom">
                    <?php
                    if ($deductions[$hdr['employee_id']]['1st'][$deduct]['deduction_name'] === 'EWT' && array_search($deductions[$hdr['employee_id']]['1st'][$deduct]['deduction_id'], $deduction_checks[$hdr['employee_id']])) {
                      ?>
                      <img src="<?php echo base_url(); ?>static/images/check_black.png" style="height:10px;padding:-3px;">
                      <?php
                    } else if ($deductions[$hdr['employee_id']]['1st'][$deduct]['deduction_name'] === 'GMP' && array_search($deductions[$hdr['employee_id']]['1st'][$deduct]['deduction_id'], $deduction_checks[$hdr['employee_id']])) {
                      ?>
                      <img src="<?php echo base_url(); ?>static/images/check_black.png" style="height:10px;padding-left:-3px;padding:-3px;">
                      <?php
                    } else if ($deductions[$hdr['employee_id']]['1st'][$deduct]['deduction_name'] === '8% Income Tax Rate' && array_search($deductions[$hdr['employee_id']]['1st'][$deduct]['deduction_id'], $deduction_checks[$hdr['employee_id']])) {
                      ?>
                      <img src="<?php echo base_url(); ?>static/images/check_black.png" style="height:10px;padding-left:-3px;padding:-3px;">
                      <?php
                    }
                    ?>

                    <?php echo $deduction_name; ?></td>
                  <td align="left" valign=bottom>:</td>
                  <td style="border-right: 1px solid #000000" align="right" valign="bottom"><?php echo EMPTY($deductions[$hdr['employee_id']]['1st'][$deduct]['amount']) ? '0.00' : number_format($deductions[$hdr['employee_id']]['1st'][$deduct]['amount'], 2); ?></td>
                <?php else: ?>
                  <td style="border-left: 1px solid #000000; border-right: 1px solid #000000;" colspan="3"></td>
                <?php endif; ?>


                <?php
                if (!EMPTY($deductions[$hdr['employee_id']]['2nd'][$deduct]) && ISSET($deductions[$hdr['employee_id']]['2nd'][$deduct])):
                  $total_deduction_2 += $deductions[$hdr['employee_id']]['2nd'][$deduct]['amount'];

                  switch ($deductions[$hdr['employee_id']]['2nd'][$deduct]['deduction_name']) {
                    case 'PAG-IBIG':
                      $deduction_name = 'Pag-IBIG';
                      break;
                    case 'EWT':
                      $deduction_name = 'EWTax';
                      break;
                    case 'GMP':
                      $deduction_name = 'GMP Tax';
                      break;
                    case 'Overpayment':
                      $deduction_name = 'Ovr Pymt';
                      break;
                    case 'PHIC':
                      $deduction_name = '(-) PhilHealth';
                      break;
                    case 'SSS':
                      $deduction_name = 'SSS Contr.';
                      break;
                    case 'MP2':
                      $deduction_name = 'MP-2';
                      break;
                    case '8% Income Tax Rate':
                      $deduction_name = '8% Inc. Tax';
                      break;
                    default:
                      $deduction_name = $deductions[$hdr['employee_id']]['2nd'][$deduct]['deduction_name'];
                  }
                  ?>

                    
                  <td style="border-left: 1px solid #000000;" align="right" colspan=2 valign="bottom">
                    <?php
                    if ($deductions[$hdr['employee_id']]['2nd'][$deduct]['deduction_name'] === 'EWT' && array_search($deductions[$hdr['employee_id']]['2nd'][$deduct]['deduction_id'], $deduction_checks[$hdr['employee_id']])) {
                      ?>
                      <img src="<?php echo base_url(); ?>static/images/check_black.png" style="height:10px;padding:-3px;">
                      <?php
                    } else if ($deductions[$hdr['employee_id']]['2nd'][$deduct]['deduction_name'] === 'GMP' && array_search($deductions[$hdr['employee_id']]['2nd'][$deduct]['deduction_id'], $deduction_checks[$hdr['employee_id']])) {
                      ?>
                      <img src="<?php echo base_url(); ?>static/images/check_black.png" style="height:10px;padding-left:-3px;padding:-3px;">
                      <?php
                    } else if ($deductions[$hdr['employee_id']]['2nd'][$deduct]['deduction_name'] === '8% Income Tax Rate' && array_search($deductions[$hdr['employee_id']]['2nd'][$deduct]['deduction_id'], $deduction_checks[$hdr['employee_id']])) {
                      ?>
                      <img src="<?php echo base_url(); ?>static/images/check_black.png" style="height:10px;padding-left:-3px;padding:-3px;">
                      <?php
                    }
                    ?>
                    
                    <?php echo $deduction_name; ?></td>
                  <td align="left" valign=bottom>:</td>
                  <td style="border-right: 1px solid #000000" align="right" valign="bottom"><?php echo EMPTY($deductions[$hdr['employee_id']]['2nd'][$deduct]['amount']) ? '0.00' : number_format($deductions[$hdr['employee_id']]['2nd'][$deduct]['amount'], 2); ?></td>
                <?php else: ?>
                  <td style="border-left: 1px solid #000000; border-right: 1px solid #000000;" colspan="4"></td>
                <?php endif; ?>
              </tr>
              <?php
            endforeach;
            $total_1 = 0;
            $total_2 = 0;
            if (ISSET($basic_salary[$hdr['employee_id']]['1st']['amount'])):
              $total_1 = ($net_bs_1 + $total_compensation_1) - $total_deduction_1;
            endif;

            if (ISSET($basic_salary[$hdr['employee_id']]['2nd']['amount'])):
              $total_2 = ($net_bs_2 + $total_compensation_2) - $total_deduction_2;
            endif;
            ?>
            <tr>
              <td class="td-border-3 td-right-bottom" align="right" valign=bottom>Total Net Pay</td>
              <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000" align="left" valign=bottom>:</td>
              <td class="td-border-top td-border-bottom td-border-right td-right-bottom" align="right" valign=bottom><?php echo ISSET($total_1) ? number_format($total_1, 2) : "0.00"; ?></td>
              <td class="td-border-3 td-right-bottom" colspan=2 align="right" valign=bottom>Total Net Pay</td>
              <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000" align="left" valign=bottom>:</td>
              <td class="td-border-top td-border-bottom td-border-right td-right-bottom" align="right" valign=bottom><?php echo ISSET($total_2) ? number_format($total_2, 2) : "0.00"; ?></td>
            </tr>
            <tr>
              <td class="td-border-3 td-left-top" colspan=5 align="left" valign=bottom>DOH - Employee under JOB ORDER -</td>
              <!--<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000" colspan=5 align="center" valign=bottom>CAFL : Coop AirFare Loan</td>-->
              <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000" colspan=5 align="center" valign=bottom>LEGEND: &nbsp;&nbsp; <img src='<?php echo base_url(); ?>static/images/check_black.png' style="height:10px;padding-left:-3px;padding-right:-3px;"> &nbsp;&nbsp; if applicable.</td>
              <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000" align="left" valign=bottom><br></td>
              <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000" align="left" valign=bottom><br></td>
              <td class="td-border-top td-border-bottom td-border-right td-right-bottom" colspan=2 align="right" valign=bottom>[FT] - Full Time</td>
            </tr>

          </table>
        </div>
        <?php
        if (((($ctr + 1) % 3) === 0) && ($ctr > 0)) :
          ?>
        <pagebreak />
        <?php
      endif;
      $ctr++;
    endforeach;
    ?>
  </body>
<?php else : ?>

  <div class="wrapper">
    <form id="test_report">
      <p style="text-align: center">No data available.</p>
    </form>
  </div>  
<?php endif; ?>
</html>