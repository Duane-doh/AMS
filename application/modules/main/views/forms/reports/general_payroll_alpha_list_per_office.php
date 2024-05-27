<html>
<?php if(!EMPTY($records)): ?>
<head>
  <title>General Payroll Alphalist per Office</title>
   <link rel="stylesheet" href="<?php echo base_url().PATH_CSS ?>reports.css" type="text/css" />
</head>
<style type="text/css">
    table.light-border th, td {
        border-style: solid;
        padding-left: 10px !important;
        padding-right: 10px !important;
        padding-top: 10px !important;
        padding-bottom: 5px !important;
    }
</style>
<body>
<?php 
    $total_column = 10 + count($com_hdr) + count($remittance_payee);
?>
<table class="table-max f-size-14">
    <tbody>
        <tr>
            <td width="20%"><?php echo nbs(10) ?><img src="<?php echo base_url().PATH_IMG ?>doh_logo.png" width=90 height=90></img><br></td>
            <td align="center" width="60%"><b>GENERAL PAYROLL<br><span style="font-size:20px;">DEPARTMENT OF HEALTH<br></span></td>
            <td>&nbsp;</td>
        </tr>
    </tbody>
</table>
<table class="cont-2 table-max">
  <tbody>
  <tr>
      <td height="15">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="20" height="15" align="left" valign="bottom"><b>Entity Name:<span class="f-size-12"><u style="padding-bottom: 2px;"><?php echo ($display_office) ? nbs(5) . $period_detail['office_name'] . nbs(5) : nbs(5) . 'DEPARTMENT OF HEALTH' . nbs(5);?></u></span></b></td>
    <td colspan="3" align="left" valign="bottom">Payroll No._______________</td>
  </tr>
  <tr>
    <td colspan="20" height="15" align="left" valign="bottom"><b>Fund Cluster:________________________</b></td>
    <td colspan="3" align="left" valign="bottom">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="20" height="15" align="left" valign="bottom">We acknowledge receipt of cash shown opposite our name as full compensation of services rendered for the period <b><?php echo $payroll_date_text?></b>.</td>
    <td colspan="3" align="left" valign="bottom"></td>
 </tr>
</tbody>
</table>
<table width="100%" class="light-border">
    <thead>
    <tr>
        <th class="pad-2 f-size-20 valign-bot td-border-4" rowspan="2" colspan="1" align="center" height="30"><span>No.</th>
        <th class="pad-2 f-size-20 valign-bot td-border-4" rowspan="2" colspan="1" align="center">Name</th>
        <th class="pad-2 f-size-20 valign-bot td-border-4" rowspan="2" colspan="1" align="center">Position/ Salary</th>
       
        <th class="pad-2 f-size-20 td-border-4" colspan="4" align="center">C O M P E N S A T I O N S</th>
        <th class="pad-2 f-size-20 td-border-4" colspan="<?php echo (count($remittance_payee) + 1);?>" align="center">D E D U C T I O N S</th>
        <th class="pad-2 f-size-20 valign-bot td-border-4" rowspan="2" colspan="1" align="center">Net Amount Due</th>
        <th class="pad-2 f-size-20 valign-bot td-border-4" rowspan="2" colspan="1" align="center" width="120">Signature of Recipient</th>
        <th class="pad-2 f-size-20 valign-bot td-border-4" rowspan="2" align="center" width="80">Remarks</th>
    </tr>
    <tr>
        <th class="pad-2 f-size-20 valign-bot td-border-4" align="center">Basic</th>
        <th class="pad-2 f-size-20 valign-bot td-border-4" colspan="2" align="center">Allowances</th>
        <th class="pad-2 f-size-20 valign-bot td-border-4" align="center">GrossAmount Earned</th>
        <?php foreach ($remittance_payee as $rem):?>
            <th class="pad-2 f-size-20 valign-bot td-border-4" align="center"><?php echo $rem['remittance_payee_name'];?></th>
        <?php endforeach;?>
        <th class="pad-2 f-size-20 valign-bot td-border-4" align="center">Total Deductions</th>
        
    </tr>
    </thead>
    <tbody >
    <?php
        $gt_com     = 0;
        $gt_ded     = 0;
        $gt_net     = 0;
        $record_cnt = 0;
       
        /*
        | START: Varibales for page total
        */
            $display_count     = $first_page_cnt;
            $count_per_page    = $per_page_cnt;
            $page_total_salary = 0;
            $page_total_basic  = 0;
            $page_total_allow  = 0;
            $page_total_gross  = 0;
            $page_total_deduct = 0;
            $page_total_net    = 0;
            foreach ($remittance_payee as $rem)
            {
                $page_total_deduct_type[$rem['remittance_payee_id']] = 0;
            }
            
        /*
        | END : Varibales for page total
        */
        foreach ($records as $record):
        $record_cnt++;
        $footer_count++;    
        $com_total  = 0;
        $ded_total  = 0;
        $net_amt    = 0;
    ?>
        <tr>
            <td class="td-border-light-left td-border-light-bottom f-size-24"><?php echo ++$ctr;?></td>
            <td class="td-border-light-left td-border-light-bottom f-size-24"><?php echo $record['employee_name'];?><br><span class="f-size-20"><?php echo $record['agency_employee_id'];?></span></td>
            <td class="td-border-light-left td-border-light-bottom f-size-24">
             <table style="width: 100%;">
                <tr>
                    <td class="f-size-20 align-l" width="100%">
                    <?php echo $record['position'];?><br>
                    SG: <?php echo $record['grade'];?>-<?php echo $record['step'];?>
                    </td>
                </tr>
                <tr>
                     <td class="f-size-24 align-r" width="100%">
                    <!-- <hr> -->
                    <?php 
                        $salary = 0;
                        $cnt = 0;
                       foreach ($record['amounts']['basic_comp'] as $com):
                        if($com['exclude'] == false)
                        {
                            $salary += $com['amount'];
                            echo number_format($com['amount'], 2);
                            $cnt++;
                            if(count($record['amounts']['basic_comp']) != $cnt)
                            echo "<br>";
                        }                  
                        endforeach;
                    ?>
                    <hr>
                    <?php 
                        echo number_format($salary, 2);
                         $page_total_salary += $salary;
                    ?>
                        
                    </span>
                    </td>
                </tr>
                </table> 
            </td>
            <td class="td-border-light-left td-border-light-bottom align-r f-size-24" align="justify">
                <table style="width: 100%;">

                <?php
                $basic_total = 0;
                 foreach ($record['amounts']['basic_comp'] as $com):
                    $com_total += $com['amount'];
                    $com_total += $com['less_amount'];
                     if($com['exclude'] == false)
                    {
                        $basic_total += $com['amount'];
                        $basic_total += $com['less_amount'];
                    }
                ?>
                
                <tr>
                    <td class="f-size-24" width="100%"><?php echo $com['code']?></td>
                    <td class="f-size-24" align="right"><?php  echo number_format($com['amount'], 2);?></td>
                </tr>
                <tr>
                    <td class="f-size-24" width="100%">less</td>
                    <td class="f-size-24" align="right"><?php  echo number_format($com['less_amount'], 2);?></td>
                </tr>
                <?php 
                endforeach;
               // $basic_total =  $com_total;
                $gt_com += $com_total;

               ?>
                </table>            
            </td>
            <td class="td-border-light-left td-border-light-bottom  f-size-24">
            <?php 
                foreach ($record['amounts']['compensation'] as $com):
                    echo $com['code'];
                    echo "<br>";
                endforeach;
            ?>
            </td>
            <td class="td-border-light-bottom align-r f-size-24">
            <?php 
                foreach ($record['amounts']['compensation'] as $com):
                    $com_total += $com['amount'];
                    echo number_format($com['amount'], 2);
                    echo "<br>";
                endforeach;
                $gt_com += $com_total;
            ?>
            </td>
            <td class="td-border-light-left td-border-light-bottom align-r f-size-24">
            <?php echo number_format($basic_total, 2);?><br>
            <?php echo number_format($com_total - $basic_total, 2);?><hr>
            <?php echo number_format($com_total, 2);?>
            <?php 

                $page_total_basic  += $basic_total;
                $page_total_allow  += $com_total - $basic_total;
                $page_total_gross  += $com_total;
               
            ?>
            </td>
            
            <?php 
            
                foreach ($record['amounts']['deduction'] as $rem_key=>$deductions):

            ?>
            <td class="td-border-light-left td-border-light-bottom" align="justify" >
                <table style="width: 100%;">
                <?php
                if(!EMPTY($deductions)):
                    $len_ctr = 0;
                    foreach ($deductions as $ded):
                    $ded_total += $ded['amount'];
                    
                    $space_count = ($longest_code[$rem_key] - strlen($ded['code'])) + ($longest_amt[$rem_key] - strlen((string)$ded['amount'])) + 2;
                    $page_total_deduct_type[$rem_key] += $ded['amount'];
                ?>
                <?php if($ded['amount'] > 0):?>
                <tr>
                    <td class="f-size-24" width="100%"><?php echo !EMPTY($ded['code'])? $ded['code']:''?></td>
                    <td class="f-size-24" align="right"><?php echo !EMPTY($ded['amount'])? nbs(3) . number_format($ded['amount'], 2) : '';?></td>
                </tr>
                <?php endif;?>
                <?php 
                    $len_ctr++;
                    endforeach;
                else :
                ?>
                <tr>
                    <td colspan="2" align="center">---</td>
                </tr>
                <?php 
                endif;
                ?>
                </table>
            </td>
            <?php 
                endforeach; 
                $gt_ded += $ded_total;
                $net_amt = $com_total - $ded_total;
                
                $gt_net  += $net_amt;

                $page_total_deduct += $ded_total;
                $page_total_net    += $net_amt;
            ?>
            <td class="td-border-light-left td-border-light-bottom align-r f-size-24"><?php echo number_format($ded_total, 2);?></td>
            <td class="td-border-light-left td-border-light-bottom align-l f-size-24">
            <!-- <?php //echo number_format($com_total, 2);?><br> -->
            <!-- <?php //echo number_format($ded_total, 2);?><hr> -->
            <!-- <b><?php //echo number_format($net_amt, 2);?></b> -->
            <?php 
                   foreach ($record['amounts']['pay_outs_comp'] as $key => $pay_out):
                    $pay_out_deduction = $record['amounts']['pay_outs_dec'][$key];

                    $cnt = $key+1;

                    echo number_format($pay_out - $pay_out_deduction, 2);

                    if($key + 1 != count($record['amounts']['pay_outs_comp']))
                    echo "<hr>";
            
                    endforeach;
            ?>
            </td>
            <td class="td-border-light-left td-border-light-bottom"></td>
            <td class="td-border-light-left td-border-light-right td-border-light-bottom f-size-24" width="200"><?php echo $record['amounts']['ded_remarks']?> <?php echo $record['amounts']['com_remarks']?></td>
        </tr>
        <?php if($footer_count == $display_count OR count($records) == $record_cnt): ?>
        <tr>
            <td class="td-border-4 align-r f-size-24 bold" colspan="2">Page Total :</td>
            <td class="td-border-4 align-r f-size-24 bold" ><?php echo number_format($page_total_salary, 2);?></td>
            <td class="td-border-4 align-r f-size-24"><?php echo number_format($page_total_basic, 2);?></td>
            <td class="td-border-4 align-r f-size-24" colspan="2"><?php echo number_format($page_total_allow, 2);?></td>
            <td class="td-border-4 align-r f-size-24"><?php echo number_format($page_total_gross, 2);?></td>
            <?php foreach ($page_total_deduct_type as $rem):?>
            <td class="td-border-4 align-r f-size-24"><?php echo number_format($rem, 2);?></td>
            <?php endforeach;?>
            <td class="td-border-4 align-r f-size-24"><?php echo number_format($page_total_deduct, 2);?></td>
            <td class="td-border-4 align-r f-size-24"><?php echo number_format($page_total_net, 2);?></td>
            <td class="td-border-4 align-c f-size-24"> --- </td>
            <td class="td-border-4 align-c f-size-24"> --- </td>
        </tr>

        <?php if(count($records) != $record_cnt): ?>
                </tbody>
            </table>
             <pagebreak />
            <table class="table-max f-size-14">
                <tbody>
                    <tr>
                        <td width="20%"><?php echo nbs(10) ?><img src="<?php echo base_url().PATH_IMG ?>doh_logo.png" width=90 height=90></img><br></td>
                        <td align="center" width="60%"><b>GENERAL PAYROLL<br><span style="font-size:20px;">DEPARTMENT OF HEALTH<br></span></td>
                        <td>&nbsp;</td>
                    </tr>
                </tbody>
            </table>
            <table class="cont-2 table-max">
              <tbody>
              <tr>
                  <td height="20">&nbsp;</td>
              </tr>
              <?php if($display_office):?>
              <tr>
                <td colspan="20" height="15" align="left" valign="bottom"><b>Entity Name:<span class="f-size-12"><u style="padding-bottom: 2px;"><?php echo nbs(5) . $period_detail['office_name'] . nbs(5);?></u></span></b></td>
                <td colspan="3" align="left" valign="bottom">Payroll No._______________</td>
              </tr>
            <?php endif; ?>
              <tr>
                <td colspan="20" height="15" align="left" valign="bottom"><b><b>Fund Cluster:________________________</b></td>
                <td colspan="3" align="left" valign="bottom">&nbsp;</td>
              </tr>
              <tr>
                <td colspan="20" height="15" align="left" valign="bottom">We acknowledge receipt of cash shown opposite our name as full compensation of services rendered for the period <b><?php echo $payroll_date_text?></b>.</td>
                <td colspan="3" align="left" valign="bottom"></td>
             </tr>
            </tbody>
            </table>
            <table width="100%" class="light-border">
                <thead>
                    <tr>
                        <th class="pad-2 f-size-20 valign-bot td-border-4" rowspan="2" colspan="1" align="center" height="30"><span>No.</th>
                        <th class="pad-2 f-size-20 valign-bot td-border-4" rowspan="2" colspan="1" align="center">Name</th>
                        <th class="pad-2 f-size-20 valign-bot td-border-4" rowspan="2" colspan="1" align="center">Position/ Salary</th>
                       
                        <th class="pad-2 f-size-20 td-border-4" colspan="4" align="center">C O M P E N S A T I O N S</th>
                        <th class="pad-2 f-size-20 td-border-4" colspan="<?php echo (count($remittance_payee) + 1);?>" align="center">D E D U C T I O N S</th>
                        <th class="pad-2 f-size-20 valign-bot td-border-4" rowspan="2" colspan="1" align="center">Net Amount Due</th>
                        <th class="pad-2 f-size-20 valign-bot td-border-4" rowspan="2" colspan="1" align="center" width="120">Signature of Recipient</th>
                        <th class="pad-2 f-size-20 valign-bot td-border-4" rowspan="2" align="center" width="80">Remarks</th>
                    </tr>
                    <tr>
                        <th class="pad-2 f-size-20 valign-bot td-border-4" align="center">Basic</th>
                        <th class="pad-2 f-size-20 valign-bot td-border-4" colspan="2" align="center">Allowances</th>
                        <th class="pad-2 f-size-20 valign-bot td-border-4" align="center">GrossAmount Earned</th>
                        <?php foreach ($remittance_payee as $rem):?>
                            <th class="pad-2 f-size-20 valign-bot td-border-4" align="center"><?php echo $rem['remittance_payee_name'];?></th>
                        <?php endforeach;?>
                        <th class="pad-2 f-size-20 valign-bot td-border-4" align="center">Total Deductions</th>
                        
                    </tr>
                </thead>
                <tbody >
        <?php endif;?>  

        <?php 
            $grand_total_salary += $page_total_salary;
            $grand_total_basic  += $page_total_basic;
            $grand_total_allow  += $page_total_allow;
            $grand_total_gross  += $page_total_gross;
            $grand_total_deduct += $page_total_deduct;
            $grand_total_net    += $page_total_net;

            $footer_count  = 0;
            $display_count = $count_per_page;
            $page_total_salary = 0;
            $page_total_basic  = 0;
            $page_total_allow  = 0;
            $page_total_gross  = 0;
            $page_total_deduct = 0;
            $page_total_net    = 0;
            foreach ($remittance_payee as $rem)
            {
                $grand_total_deduct_type[$rem['remittance_payee_id']] += $page_total_deduct_type[$rem['remittance_payee_id']];
                $page_total_deduct_type[$rem['remittance_payee_id']] = 0;
            }
        endif;?>  
    <?php endforeach;?>  
         <tr>
            <td class="td-border-4 align-r f-size-24 bold" colspan="2">Grand Total :</td>
            <td class="td-border-4 align-r f-size-24 bold" ><?php echo number_format($grand_total_salary, 2);?></td>
            <td class="td-border-4 align-r f-size-24"><?php echo number_format($grand_total_basic, 2);?></td>
            <td class="td-border-4 align-r f-size-24" colspan="2"><?php echo number_format($grand_total_allow, 2);?></td>
            <td class="td-border-4 align-r f-size-24"><?php echo number_format($grand_total_gross, 2);?></td>
            <?php foreach ($grand_total_deduct_type as $rem):?>
            <td class="td-border-4 align-r f-size-24"><?php echo number_format($rem, 2);?></td>
            <?php endforeach;?>
            <td class="td-border-4 align-r f-size-24"><?php echo number_format($grand_total_deduct, 2);?></td>
            <td class="td-border-4 align-r f-size-24"><?php echo number_format($grand_total_net, 2);?></td>
            <td class="td-border-4 align-c f-size-24"> --- </td>
            <td class="td-border-4 align-c f-size-24"> --- </td>
        </tr>
    </tbody>
</table>
<?php if($display_signatories):?>
<!-- <pagebreak />
<table class="table-max">
    <tr>
        <td colspan="15" class=" align-c" style="font-size: 70px"><b>GENERAL PAYROLL</b></td>
    </tr>
    <tr>
        <td colspan="6" class="td-border-thick-top td-border-thick-left pad-left-100 pad-top-50">
            <table class="table-max f-size-30">
                <tr>
                    <td colspan="5" class="pad-bot-250 f-size-40" width="600"><b>[ A ] CERTIFIED: Services duly rendered as stated.</b></td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td align="center">________________________________________________________________</td>
                    <td>&nbsp;</td>
                    <td align="center">__________________________</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td align="center" class="pad-bot-10">Signature over Printed Name of Authorized Officer</td>
                    <td>&nbsp;</td>
                    <td align="center" class="pad-bot-10">Date</td>
                    <td>&nbsp;</td>
                </tr>
            </table>
        </td>
        <td colspan="9" class="td-border-thick-top td-border-thick-right pad-top-50">
            <table class="table-max f-size-30">
                <tr>
                    <td colspan="7" class="pad-bot-250 pad-top-30 f-size-40"><b>[ C ] APPROVED FOR PAYMENT: _________________________</b></td>
                </tr>
                <tr>
                    <td height="30px">&nbsp;</td>
                    <td align="center" valign="bottom">________________________________________________________________</td>
                    <td>&nbsp;</td>
                    <td align="center" valign="bottom">__________________________</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td >&nbsp;</td>
                    <td align="center" valign="top" class="">(Signature over Printed Name)<br>Head of Agency/Authorized Representative</td>
                    <td >&nbsp;</td>
                    <td align="center" class="pad-bot-10">Date</td>
                    <td >&nbsp;</td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td colspan="6"  class="td-border-thick-bottom td-border-thick-left pad-left-100 pad-top-50">
            <table class="table-max f-size-30">
                <tr>
                    <td colspan="5" class="pad-bot-250 pad-top-10 f-size-40" width="600"><b>[ B ] CERTIFIED: Supporting documents complete and proper, and cash available in the amount of ____________________</b></td>
                </tr>
                <tr>
                    <td height="30px">&nbsp;</td>
                    <td align="center" valign="bottom">________________________________________________________________</td>
                    <td>&nbsp;</td>
                    <td align="center" valign="bottom">__________________________</td>
                    <td>&nbsp;</td>
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
        <td colspan="9" class="td-border-thick-bottom td-border-thick-right">
            <table class="table-max f-size-30">
                <tr>
                    <td colspan="4" class="pad-bot-250 pad-top-10 f-size-40"><b>[ D ] CERTIFIED: Each employee whose name appears on the payroll has been paid the amount as indicated opposite his/her name.</b></td>
                </tr>
                <tr>
                    <td height="30px">&nbsp;</td>
                    <td align="center" valign="bottom">_______________________________________________________</td>
                    <td width="50px"></td>
                    <td align="center" valign="bottom">__________________________</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td align="center" class="pad-bot-10">(Signature over Printed Name)<br>Disbursing Officer</td>
                    <td>&nbsp;</td>
                    <td align="center" class="pad-bot-10">Date</td>
                    <td>&nbsp;</td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td colspan="7" class="f-size-30 align-l pad-left-50"><b>Number of Pages : {nb}</b></td>
        <td colspan="8" class="f-size-30 align-r pad-right-100 pad-bot-100"><b>Number of Employee(s): 20</b></td>
    </tr>

    <tr>
        <td colspan="9"><br></td>
        <td colspan="6" class="align-l td-border-light-right td-border-light-left td-border-light-bottom td-border-light-top">
            <table class="table-max f-size-40">
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
</table> -->
<?php endif;?>
<!-- ************************************************************************** -->
</body>
<?php else :?>

<div class="wrapper">
    <form id="test_report">
        <p style="text-align: center">No data available.</p>
    </form>
</div>

<?php endif;?>
</html>