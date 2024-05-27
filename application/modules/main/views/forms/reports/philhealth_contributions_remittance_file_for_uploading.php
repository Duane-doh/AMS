
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<html>
<head>
  <title>Philhealth Contributions Remittance File for Uploading</title>
  <link rel="stylesheet" href="<?php echo base_url().PATH_CSS ?>reports.css" type="text/css" />
</head>
<body>
    <?php if(in_array(DEFAULT_PHILHEALTH_REMITTANCE_TEMPLATE, $payout_type_flag)):?>
	<table class="center-85" width="100%">
        <thead>
            <tr>
                <td class="border-hide align-c">
                      <div>
                        <table width="100%" border="1" style="text-align: center;">
                          <tbody>
                            <tr>
                              <td rowspan="3" class="border-hide"><img src="<?php echo base_url().PATH_IMG ?>doh_logo.png" width="60" height="60" style="float: left"></td>
                            </tr>
                            <tr>
                              <td class="border-hide"><span class="center-85 f-size-12">Republic of the Philippines</span></td>
                            </tr>
                            <tr>
                              <td class="border-hide"><span class="f-size-12">DEPARTMENT OF HEALTH</span></td>
                            </tr>
                          </tbody>
                        </table>
                        <br><br><br>
                    </div>
                </td>
            </tr>
        </thead>
	</table>
    <table class="table-max">
        <tr>
            <td class="border-light bold">EMP NAME</td>
            <td colspan=3 class="border-light-left">Department of Health</td>
            <td class="border-light-left bold bg-gray align-c">EMPLOYEE COUNT</td>
            <td class="border-light-left bold align-r">SSS NUMBER</td>
            <td colspan=2 class="border-light-left"></td>
            <td colspan=2 class="border-light-left bold bg-yellow align-r">ME-5 # / OR #</td>
            <td colspan=2 class="border-light-left bg-yellow align-c"><?php echo $phil_remittances[0]['or_no'];?></td>
            <td class="border-light-left bold">TOTAL PS</td>
            <td colspan=2 class="border-light-left bg-yellow align-c"><?php echo $total_ps;?></td>
        </tr>
        <tr>
            <td class="border-light-top bold">ADDRESS</td>
            <td colspan=3 class="border-light-top-left"><?php echo $doh_address;?></td>
            <td class="border-light-top-left td-center-middle bold bg-gray"><?php echo $remittance_count;?></td>
            <td class="border-light-top-left bold align-r">TIN</td>
            <td colspan=2 class="border-light-top-left "><?php echo $doh_tin;?></td>
            <td colspan=2 class="border-light-top-left bold bg-yellow align-r">AMOUNT PAID</td>
            <td colspan=2 class="border-light-top-left bg-yellow align-c"><?php echo  $total_ps_gs;?></td>
            <td class="border-light-top-left bold">TOTAL GS</td>           
            <td colspan=2 class="border-light-top-left bg-yellow align-c"><?php echo $total_gs;?></td>
        </tr>
        <tr>
            <td class="border-light-top bold">PEN</td>
            <td colspan=2 class="border-light-top-left">140039100036</td>
            <td class="border-light-top-left bold align-r">EMPLOYER TYPE</td>
            <td class="border-light-top-left bold align-c">GOVERNMENT</td>
            <td class="border-light-top-left bold align-r">ALLOTED GS</td>
            <td colspan=2 class="border-light-top-left "></td>
            <td colspan=2 class="border-light-top-left bold bg-yellow align-r">DATE PAID</td>
            <td colspan=2 class="border-light-top-left bg-yellow align-c"><?php echo $phil_remittances[0]['or_date'];?></td>
            <td class="border-light-top-left bold bg-lightgreen">NO ALLOTED GS</td>           
            <td colspan=2 class="border-light-top-left bg-lightgreen align-c"></td>
        </tr>
        <tr>
            <td class="border-light-top bold">TEL #</td>
            <td colspan=2 class="border-light-top-left"></td>
            <td class="border-light-top-left bold align-r">YEAR</td>
            <td class="border-light-top-left bold bg-yellow align-c"><?php echo $year;?></td>
            <td class="border-light-top-left bold align-r">PREPARED BY</td>
            <td colspan=2 class="border-light-top-left"><?php echo $prepared_by['signatory_name'];?>aa</td>
            <td colspan=2 class="border-light-top-left bold bg-yellow align-r">APPLICABLE MONTH</td>
            <td colspan=2 class="border-light-top-left bold align-c"><?php echo $month_text;?></td>
            <td class="border-light-top-left bold bg-lightgreen">TOTAL ARREARS</td>           
            <td colspan=2 class="border-light-top-left bold bg-lightgreen align-c"></td>
        </tr>
        <tr>
            <td class="border-light-top bold">INCHARGE</td>
            <td colspan=2 class="border-light-top-left "></td>
            <td class="border-light-top-left bold align-r">MONTH</td>
            <td class="border-light-top-left bold bg-yellow align-c"><?php echo $month;?></td>
            <td class="border-light-top-left bold align-r">DESIGNATION</td>
            <td colspan=2 class="border-light-top-left "><?php echo $prepared_by['position_name'] ?>, <?php echo $prepared_by['office_name'] ?></td>
            <td colspan=2 class="border-light-top-left bold bg-yellow align-r">TOTAL RF-1</td>
            <td colspan=2 class="border-light-top-left bold align-c"><?php echo $total_ps_gs;?></td>
            <td class="border-light-top-left bold">TOTAL PS & TOTAL GS</td>           
            <td colspan=2 class="border-light-top-left bold bg-yellow align-c"><?php echo $total_ps_gs;?></td>
        </tr>
        <tr>
            <td class="border-light-top bold">POSITION</td>
            <td colspan=2 class="border-light-top-left"></td>
            <td class="border-light-top-left bold align-r">TYPE OF REPORT</td>
            <td class="border-light-top-left bold bg-yellow align-c"></td>
            <td class="border-light-top-left bold align-r"></td>
            <td colspan=2 class="border-light-top-left"></td>
            <td colspan=2 class="border-light-top-left bold bg-yellow align-r">OVER/UNDER</td>
            <td colspan=2 class="border-light-top-left bold align-c"></td>
            <td class="border-light-top-left bold bg-lightgreen">TOTAL PS + NO ALLO</td>           
            <td colspan=2 class="border-light-top-left bold bg-lightgreen align-c"></td>
        </tr>
        <tr>
            <td class="border-light-top bold">EMAIL</td>
            <td colspan=2 class="border-light-top-left "></td>
            <td class="border-light-top-left bold align-r">DATE PREPARED</td>
            <td class="border-light-top-left bold bg-yellow align-c"><?php echo date("m/d/Y g:i A");?></td>
            <td rowspan=2 class="border-light-top-left bold align-c">PHILHEALTH NO</td>
            <td rowspan=2 class="border-light-top-left bold align-c">DATE OF BIRTH</td>
            <td rowspan=2 class="border-light-top-left bold align-c">SEX</td>
            <td rowspan=2 class="border-light-top-left bold bg-yellow align-c">SALARY</td>
            <td rowspan=2 class="border-light-top-left bold align-c">SB</td>
            <td rowspan=2 class="border-light-top-left bold align-c">PS</td>
            <td rowspan=2 class="border-light-top-left bold align-c">GS</td>
            <td rowspan=2 class="border-light-top-left bold align-c">ALLOTED</td>   
            <td rowspan=2 class="border-light-top-left bold align-c">REMARKS</td>
            <td rowspan=2 class="border-light-top-left bold align-c">DATE</td>           
        </tr>
        <tr>
            <td class="border-light-top bold"># OF EE'S</td>
            <td class="border-light-top-left bold align-c">LAST NAME</td>
            <td class="border-light-top-left bold align-c">SUFFIX</td>
            <td class="border-light-top-left bold align-c">FIRST NAME</td>
            <td class="border-light-top-left bold align-c">MIDDLE NAME</td>
        </tr>

        <?php if($phil_remittances): ?>
            <?php 
                $cnt 		= 0;
                foreach ($phil_remittances as $phil_remittance): 
           			 $cnt ++;
             ?>
                <tr>
                    <td class="border-light-top align-c" height="15" width="60"><?php echo $cnt?></td>
                    <td class="border-light-top-left " width="150"><?php echo $phil_remittance['last_name']?></td>
                    <td class="border-light-top-left " width="50"><?php echo $phil_remittance['ext_name']?></td>
                    <td class="border-light-top-left " width="150"><?php echo $phil_remittance['first_name']?></td>
                    <td class="border-light-top-left " width="150"><?php echo $phil_remittance['middle_name']?></td>
                    <td class="border-light-top-left align-r" width="100"><?php echo $phil_remittance['ph_id'].'&nbsp;'?></td>
                    <td class="border-light-top-left align-c" width="70"><?php echo $phil_remittance['birth_date']?></td>
                    <td class="border-light-top-left align-c" width="50"><?php echo $phil_remittance['gender_code']?></td>
                    <td class="border-light-top-left bg-yellow align-r" width="100"><?php echo number_format($phil_remittance['basic_salary'], 2)?></td>
                    <td class="border-light-top-left align-c" width="30"></td>
                    <td class="border-light-top-left align-r" width="50"><?php echo number_format($phil_remittance['ps'], 2);?></td>
                    <td class="border-light-top-left align-r" width="50"><?php echo number_format($phil_remittance['gs'], 2);?></td>
                    <td class="border-light-top-left align-r" width="120"></td>
                    <td class="border-light-top-left bg-yellow" width="50"></td>
                    <td class="border-light-top-left bg-yellow" width="70"></td>
                </tr>
           <?php 
            endforeach;
           
            else: 
           ?>
            <tr>
                <td colspan=15 class="border-light align-c" height="15"><b>No Records Found.</b></td>
            </tr>
        <?php endif;?>
        
    </table>
    <?php else:?>
    <table class="table-max" align="center">
        <thead>
            <tr>
                <td class="border-hide pad-2" width="50">&nbsp;</td>
                <td class="border-hide pad-2">For the Month of</td>
                <td class="border-hide pad-2">&nbsp;</td>
                <td class="border-hide pad-2"><?php echo $month_text;?></td>
                <td class="border-hide pad-2" width="300">&nbsp;</td>
            </tr>
            <tr>
                <td class="border-hide pad-2" width="50">&nbsp;</td>
                <td class="border-hide pad-2">Amount</td>
                <td class="border-hide pad-2">PS</td>
                <td class="border-hide pad-2"><?php echo number_format($total_ps,2);?></td>
                <td class="border-hide pad-2" width="300">&nbsp;</td>
            </tr>
            <tr>
                <td class="border-hide pad-2" width="50">&nbsp;</td>
                <td class="border-hide pad-2">&nbsp;</td>
                <td class="border-hide pad-2">GS</td>
                <td class="border-hide pad-2"><?php echo number_format($total_gs,2);?></td>
                <td class="border-hide pad-2" width="300">&nbsp;</td>
            </tr>
            <tr>
                <td class="border-hide pad-2" width="50">&nbsp;</td>
                <td class="border-hide pad-2">Total</td>
                <td class="border-hide pad-2">&nbsp;</td>
                <td class="border-hide pad-2"><?php echo number_format($total_ps + $total_gs,2);?></td>
                <td class="border-hide pad-2" width="300">&nbsp;</td>
            </tr>
            <tr>
                <td class="border-hide pad-2" width="50">&nbsp;</td>
                <td class="border-hide pad-2">O.R. Number</td>
                <td class="border-hide pad-2">&nbsp;</td>
                <td class="border-hide pad-2"><?php echo $or_no?></td>
                <td class="border-hide pad-2" width="300">&nbsp;</td>
            </tr>
            <tr>
                <td class="border-hide pad-2" width="50">&nbsp;</td>
                <td class="border-hide pad-2">O.R. Date</td>
                <td class="border-hide pad-2">&nbsp;</td>
                <td class="border-hide pad-2"><?php echo $or_date?></td>
                <td class="border-hide pad-2" width="300">&nbsp;</td>
            </tr>
            <tr><td colspan="5">&nbsp;</td></tr>
        </thead>
    </table>
    <table class="table-max" align="center">
        <thead>
            <tr>
                <td class="bold pad-2 td-border-light-left td-border-light-top td-border-light-bottom">Office</td>
                <td class="bold pad-2 td-border-light-left td-border-light-top td-border-light-bottom">Last Name</td>
                <td class="bold pad-2 td-border-light-left td-border-light-top td-border-light-bottom">Suf</td>
                <td class="bold pad-2 td-border-light-left td-border-light-top td-border-light-bottom">First Name</td>
                <td class="bold pad-2 td-border-light-left td-border-light-top td-border-light-bottom">Middle Name</td>
                <td class="bold pad-2 td-border-light-left td-border-light-top td-border-light-bottom">BDATE</td>
                <td class="bold pad-2 td-border-light-left td-border-light-top td-border-light-bottom">Remarks1</td>
                <td class="bold pad-2 td-border-light-left td-border-light-top td-border-light-bottom">Remarks2</td>
                <td class="bold pad-2 td-border-light-left td-border-light-top td-border-light-bottom">Basic</td>
                <td class="bold pad-2 td-border-light-left td-border-light-top td-border-light-bottom td-border-light-right">PIN</td>
            </tr>
        </thead>
        <tbody>
         <?php if($phil_remittances): ?>
            <?php 
                $cnt        = 0;
                foreach ($phil_remittances as $phil_remittance): 
                     $cnt ++;
             ?>
            <tr>
                <td class="pad-2 td-border-light-left td-border-light-bottom"><?php echo $phil_remittance['office_short_name']?></td>
                <td class="pad-2 td-border-light-left td-border-light-bottom"><?php echo str_replace('Ñ', 'N', $phil_remittance['last_name'])?></td>
                <td class="pad-2 td-border-light-left td-border-light-bottom"><?php echo str_replace('Ñ', 'N', $phil_remittance['ext_name'])?></td>
                <td class="pad-2 td-border-light-left td-border-light-bottom"><?php echo str_replace('Ñ', 'N', $phil_remittance['first_name'])?></td>
                <td class="pad-2 td-border-light-left td-border-light-bottom"><?php echo str_replace('Ñ', 'N', $phil_remittance['middle_name'])?></td>
                <td class="pad-2 td-border-light-left td-border-light-bottom"><?php echo $phil_remittance['birth_date']?></td>
                <td class="pad-2 td-border-light-left td-border-light-bottom align-c"><?php echo $phil_remittance['remarks1']?></td>
                <td class="pad-2 td-border-light-left td-border-light-bottom align-c"><?php echo $phil_remittance['remarks2']?></td>
                <td class="pad-2 td-border-light-left td-border-light-bottom"><?php echo (in_array($phil_remittance['remarks1'], array('S','NE'))) ? '0':number_format($phil_remittance['basic_amount'],2)?></td>
                <td class="pad-2 td-border-light-left td-border-light-bottom td-border-light-right"><?php echo $phil_remittance['ph_id'].'&nbsp;'?></td>
            </tr>
             <?php 
                endforeach;
               
            else: 
               ?>
                <tr>
                    <td colspan=15 class="border-light align-c" height="15"><b>No Records Found.</b></td>
                </tr>
        <?php endif;?>
        </tbody>
    </table>
    <?php endif;?>
</body>
</html>
