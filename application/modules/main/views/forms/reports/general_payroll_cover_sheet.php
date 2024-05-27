<!DOCTYPE html>
<html>
  <head>
    <title>General Payroll Cover Sheet</title>
    <link rel="stylesheet" href="<?php echo base_url() . PATH_CSS ?>reports.css" type="text/css" />
  </head>
  <body>
    <div style="width: 100%; height: 500px;  text-align: center; font-family: 'Times New Roman', Times, serif; ">
      <div>
        <img src="<?php echo base_url() . PATH_IMG ?>doh_logo.png" width="150" height="150" style="float:left;margin-top:20px;margin-left:100px;margin-right:-250;">
        <!--<h1> GENERAL PAYROLL</h1>-->
        <br/><br/>
        <span class="bold" style="font-size: 30px;">GENERAL PAYROLL</span>
      </div>
      <!--<br><br><br><br>-->
      <br /><br />
      <div>
        <div class="center-85 bold align-c" style="font-size: 30px;padding-bottom: 180px;">DEPARTMENT OF HEALTH</div>
      </div>


      <div class="center-85 align-c bold" style="font-size:15px;">
        TOTAL NO. OF EMPLOYEES:&nbsp;&nbsp;&nbsp; <?php echo $results['emp_count'] . nbs(20); ?> &nbsp;&nbsp;&nbsp;
        CURRENT DATE:&nbsp;&nbsp;&nbsp;  <?php echo $payroll_date_text ?>
      </div>
    </div>
    <?php if ($job_order_flag == false): ?>
      <!-- 
      /**********************************/     START    /**********************************/ 
      /**********************************/ REGULAR page /**********************************/ 
      -->
      <div style="page-break-before: always; width: 100%; height: 500px; text-align: center">
        <table class="table-max">
          <tr>
            <td colspan="14" class=" align-c" style="font-size: 30px"><b>GENERAL PAYROLL</b></td>
          </tr>
          <tr>
            <td colspan="7" class="td-border-thick-top td-border-thick-left pad-left-20 pad-top-10">
              <table class="table-max f-size-18">
                <tr>
                  <td colspan="5" class="pad-bot-50 f-size-20 bold" width="300">[ A ] CERTIFIED: Services duly rendered as stated.</td>
                </tr>
                <tr>
                  <td>&nbsp;</td>
                  <td align="center" class="td-border-thick-bottom bold"><?php echo isset($signatory_a['signatory_name']) ? $signatory_a['signatory_name'] : '' ?></td>
                  <td>&nbsp;</td>
                  <td align="center" class="td-border-thick-bottom" width="80"></td>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <td>&nbsp;</td>
                  <td align="center" class="pad-bot-10 f-size-16">
                    <?php echo isset($signatory_a['position_name']) ? $signatory_a['position_name'] : '' ?>
                    <br>
                    <?php echo isset($signatory_a['office_name']) ? $signatory_a['office_name'] : '' ?>
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
                  <td align="center" class="td-border-thick-bottom bold"><?php echo isset($signatory_c['signatory_name']) ? $signatory_c['signatory_name'] : '' ?></td>
                  <td>&nbsp;</td>
                  <td align="center" class="td-border-thick-bottom" width="80"></td>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <td >&nbsp;</td>
                  <td align="center" class="pad-bot-10 f-size-16">
                    <?php echo isset($signatory_c['position_name']) ? $signatory_c['position_name'] : '' ?>
                    <br>
                    <?php echo isset($signatory_c['office_name']) ? $signatory_c['office_name'] : '' ?>
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
                  <td align="center" class="td-border-thick-bottom bold"><?php echo isset($signatory_b['signatory_name']) ? $signatory_b['signatory_name'] : '' ?></td>
                  <td>&nbsp;</td>
                  <td align="center" class="td-border-thick-bottom" width="80"></td>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <td >&nbsp;</td>
                  <td align="center" class="pad-bot-10 f-size-16">
                    <?php echo isset($signatory_b['position_name']) ? $signatory_b['position_name'] : '' ?>
                    <br>
                    <?php echo isset($signatory_b['office_name']) ? $signatory_b['office_name'] : '' ?>
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
                  <td align="center" class="td-border-thick-bottom bold"><?php echo isset($signatory_d['signatory_name']) ? $signatory_d['signatory_name'] : '' ?></td>
                  <td>&nbsp;</td>
                  <td align="center" class="td-border-thick-bottom" width="80"></td>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <td>&nbsp;</td>
                  <td align="center" class="pad-bot-10 f-size-16">
                    <?php echo isset($signatory_d['position_name']) ? $signatory_d['position_name'] : '' ?>
                    <br>
                    <?php echo isset($signatory_d['office_name']) ? $signatory_d['office_name'] : '' ?>
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
          <?php
          foreach ($deductions as $deduction):
            echo '[' . $deduction['deduction_code'] . '] - ' . $deduction['deduction_name'] . '; ';
          endforeach;
          ?>
        </span>

      </div>
    <?php else: ?>
      <!-- 
      /**********************************/     START    /**********************************/ 
      /**********************************/ JOB ORDER page /**********************************/ 
      -->

      <div style="page-break-before: always; width: 100%; height: 500px; text-align: center">
        <div>
          <p class="center-85 align-c f-size-30 bold font-family-tnr"><b>GENERAL PAYROLL</b></p>
        </div>
        <div id="main-general-payroll" style="border: 2px solid gray;">
          <div style="overflow: hidden;">
            <div style="float: left; width: 50%;">
              <table  width="90%" height="100%">
                <tr>
                  <td class="td-left-top f-size-12 bold" style="height: 100;text-align: justify;">(1)  I CERTIFY on my official oath that this payroll of DEPARTMENT OF HEALTH<?php echo $payroll_type_display; ?>for the period of <?php echo $payroll_date_text; ?> is correct and that the basis of this payroll are the services rendered for the period <?php echo $payroll_period['payroll_period'] ?> as evidence by the employees respective DTRs</td>
                </tr>
                <tr>
                  <td class="td-left-bottom f-size-12 align-c bold" style="height: 25;border-bottom: 1px solid black;width: 100%; display: block;"><?php echo nbs(5) . $signatory_a['signatory_name'] . nbs(5); ?></td>
                </tr>
                <tr>
                  <td class="td-left-top f-size-12 align-c" style="height: 50;"><?php echo $signatory_a['position_name'] . '<br>' . $signatory_a['office_name']; ?></td>
                </tr>
                <tr>
                  <?php
                  $time      = strtotime($payroll_date_text);
                  $newformat = date('Y', $time);
                  ?>
                  <td class="td-left-top f-size-12 bold" style="height: 80;text-align: justify;">(2) APPROVED, payable from appropriation for <?php echo $newformat; ?></td>
                </tr>
                <tr>
                  <td class="td-left-bottom f-size-12 align-c bold" style="height: 25;border-bottom: 1px solid black;width: 100%; display: block;"><?php echo nbs(5) . $signatory_b['signatory_name'] . nbs(5); ?></td>
                </tr>
                <tr>
                  <td class="td-left-top f-size-12 align-c" style="height: 40;"><?php echo $signatory_b['position_name'] . '<br>' . $signatory_b['office_name']; ?></td>
                </tr>
              </table>
            </div>
            <div style="float: right; width: 49%;">
              <table   width="90%" >
                <tr>
                  <td class="td-left-top f-size-12 bold" style="height: 100;text-align: justify;">(3) I CERTIFY on my official oath I have paid to each employee whose name appears in the roll the amount opposite his name, he having presented his Residence Certificate</td>
                </tr>
                <tr>
                  <td class="td-left-bottom f-size-12 align-c bold" style="height: 25;border-bottom: 1px solid black;width: 100%; display: block;"><?php echo nbs(5) . $signatory_c['signatory_name'] . nbs(5); ?></td>
                </tr>
                <tr>
                  <td class="td-left-top f-size-12 align-c" style="height: 50;"><?php echo $signatory_c['position_name'] . '<br>' . $signatory_c['office_name']; ?></td>
                </tr>
                <tr>
                  <td class="td-left-top f-size-12 bold" style="height: 80;text-align: justify;">(3) I CERTIFY on my official oath that I have witness payments to each person, whose name appears, hereon, of the amount set opposite his name and my initials</td>
                </tr>
                <tr>
                  <td class="td-left-bottom f-size-12 align-c bold" style="height: 25;border-bottom: 1px solid black;width: 100%; display: block;"><?php echo nbs(5) . $signatory_c['signatory_name'] . nbs(5); ?></td>
                </tr>
               <tr>
                  <td class="td-left-top f-size-12 align-c" style="height: 50;"><?php echo $signatory_c['position_name'] . '<br>' . $signatory_c['office_name']; ?></td>
                </tr>
              </table>
            </div>
          </div>
        </div>
        <div style="overflow: hidden;">
          <div style="float: left; width: 50%;padding-left:20px;">
            <table  width="100%">
              <tr>
                <td colspan="2" class="td-left-top f-size-12 bold" style="height: 40;padding-top:5px;">Number of pages: </td>
              </tr>
            </table>
          </div>
          <div style="float: right; width: 40%;">
            <table  width="100%">
              <tr>
                <td class="td-left-top f-size-12 bold" style="height: 40;padding-top:5px;">Number of Employee(s):&nbsp;&nbsp;&nbsp;<?php echo $results['emp_count']; ?></td>
              </tr>
              <tr>
                <td>
                  <table width="100%" class="f-size-12" style="font-size:10px;">
                    <tr>
                      <td class="f-size-12 bold" colspan="2" style="padding-left:-70px;">CERTIFIED :</td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                      <td>1.ADEQUATE AVAILABLE FUNDS IN THE AMOUNT OF _______</td>	
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                      <td>2.EXPENDITURE PROPERLY CERTIFIED.</td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                      <td>3.SUPPORTED BY DOCUMENTS APPEARING LEGAL AND PROPER.</td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                      <td>4.ACCOUNT CODES PROPER.</td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>
          </div>
        </div>
        <div style="overflow: hidden;">
          <div style="float: left; width: 40%;padding-left:30px;padding-top:10px;">
            <table  width="100%">
              <tr>
                <td class="td-center-bottom f-size-12 align-c bold" style="height: 45;border-bottom: 1px solid black;width: 100%; display: block;"><?php echo nbs(5) . $signatory_d['signatory_name'] . nbs(5); ?></td>
              </tr>
              <tr>
                <td class="td-center-top f-size-12 align-c" style="height: 75;"><?php echo $signatory_d['position_name'] . '<br>' . $signatory_d['office_name']; ?></td>
              </tr>
            </table>
          </div>
          <div style="float: right; width: 40%;padding-right:30px;padding-top:10px;">
            <table  width="100%">
              <tr>
                <td class="td-center-bottom f-size-12 align-c bold" style="height: 45;border-bottom: 1px solid black;width: 100%; display: block;"></td>
              </tr>
              <tr>
                <td class="td-center-top" style="height: 75">&nbsp;</td>
              </tr>
            </table>
          </div>
        </div>
      </div>
    <?php endif; ?>
  </body>
</html>