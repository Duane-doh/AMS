<?php 
    if($record['gender_code'] == 'F' && $record['civil_status_id'] == CIVIL_STATUS_MARRIED)
    {
        $label_eng = 'Mrs.';
    }
    elseif($record['gender_code'] == 'F' && $record['civil_status_id'] != CIVIL_STATUS_MARRIED)
    {
        $label_eng = 'Ms.';
    }
    elseif($record['gender_code'] == 'M')
    {
        $label_eng = 'Mr.';
    }
    else
    {
        $label_eng = 'Mr.';
    }
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<html>
    <head>
        
        <title>Certification of Assumption to Duty</title>
        <link rel="stylesheet" href="<?php echo base_url().PATH_CSS ?>reports.css" type="text/css" />
    </head>
 <style type="text/css">
    table{
        
        font-family: Geneva, sans-serif;
    }
    .salary-container {
      max-width: 420px;
      overflow: hidden;
      text-overflow: ellipsis;
      white-space: nowrap;
      display: inline-block;
    }
    .f-size-notice{
      font-size: 12pt !important;
    }
    .line {
             
            padding-bottom: 10px;
            border-bottom-style: solid;
            border-bottom-width: 1.5px;
            width: fit-content;
        }
    td.line-space{
        line-height: 100px;
    }
    </style>
    <body>
        <div>
            <div style="padding: 20px;">
                <table class="f-size-notice table-max" align="center">
                    <tbody>
                        <tr>
                            <td colspan="3" style="font-size: 9pt;"><b><i>CS Form No. 4</i></b><br><i>Revised 2018</i></td>
                        </tr>
                        <tr>
                            <td height="40"></td>
                        </tr>
                        <tr>
                            <td align="right" width="30%"></td>
                            <td align="center" width=""><span style="font-size:12pt;"><b>Republic of the Philippines</b></span><br><span class="" style="font-size:13pt;"><b>DEPARTMENT OF HEALTH</b></span><br><span><b>Office of the Secretary</b></span><br>Manila</td>              
                            <td width="30%"><br></td>
                        </tr>
                        <tr>
                            <td height="30"></td>
                        </tr>
                    </tbody>
                </table>
                <table class="f-size-notice table-max" align="center">
                    <tbody>
                        <tr>
                            <td class="f-size-notice bold" align="center"><span style="font-size:13pt;">CERTIFICATION OF ASSUMPTION TO DUTY</span></td>
                        </tr>
                    </tbody>
                </table>
                <table class=" f-size-notice">
                    <tr>
                        <td height="40"></td>
                    </tr>
                </table>
                <p class=" f-size-notice" style="text-align: justify; display: block; line-height: 2; color: black;" ><?php echo nbs(9)?>This is to certify that <?php echo $label_eng; ?> <span class="line bold" >&emsp;<?php echo strtoupper($record['employee_name']);?>&emsp;</b></span> has assumed the duties and responsibilities as <span class="line bold">&emsp;<?php echo strtoupper($record['position_name']);?>&emsp;</span> of the <span class="line bold">&emsp;<?php echo strtoupper($record['agency_name']);?>&emsp;</span> effective <span class="line bold">&emsp;<?php echo strtoupper($record['start_date']);?>&emsp;</span>. <br> <br>
                    <?php echo nbs(10)?>This certification is issued in connection with the issuance of the appointment of <?php echo strtoupper($label_eng); ?><?php echo strtoupper($record['last_name']);?> as <?php echo ($record['position_name']);?>. <br> <br>

                    <?php echo nbs(12)?>Done this <span class="line bold">&emsp;<?php echo strtoupper($record['start_date']);?>&emsp;</span> in Manila, Philippines.
                </p>
                <table align="right">
                    <tr>
                        <td height="50"></td>
                    </tr>
                    <tr>                    
                        <td></td>
                        <td class="td-border-top  f-size-notice" align="center"><b><?php echo strtoupper($certified_by['signatory_name']) ; ?></b><br><?php echo $certified_by['position_name'] ?><br> <?php echo $certified_by['office_name']?></td>
                    </tr>
                </table>

                <table align="left">
                    <tr>
                        <td height="10"></td>
                    </tr>
                    <tr>
                        <td class=" f-size-notice" > Date:  <span class="line bold">&emsp;<?php echo strtoupper($record['start_date']);?>&emsp;</span><br><br>Attested by:</td>

                    </tr>
                    <tr>
                        <td height="55"></td>
                    </tr>
                    <tr>                    
                        <td class="td-border-top  f-size-notice" align="center"><b><?php echo strtoupper($prepared_by['signatory_name']) ; ?></b><br><?php echo $prepared_by['position_name'] ?><br> <?php echo $prepared_by['office_name']?></td>
                    </tr>
                    <tr>
                        <td height="30"></td>
                    </tr>
                </table>
            <!-- <br><br><br><br> -->
                <table class="table-max" style="page-break-after: always;"> 
                        <tr>
                            <td height="" class="f-size-notice" width="350"><br><?php echo nbs(5)?>201 file<br><?php echo nbs(5)?>Admin<br><?php echo nbs(5)?>COA<br><?php echo nbs(5)?>CSC<br><br><br><br></td>
                            <td height="" class="f-size-notice" width="200"></td>
                            <td align="center" height="10" class="f-size-20" width="300" style="border: 1px solid; font-family: Times New Roman;"><b><i>For submission to CSCFO <br>within 30 days from the <br> date of assumption of the <br>appointee</i></b></td>
                        </tr>
                </table>
            </div>
        </div>

    <!-- PAGE 2 -->
        <div>
            <div style="padding: 20px;">
                <table class="f-size-notice table-max" align="center">
                    <tbody>
                        <tr>
                            <td class="f-size-notice" colspan="3" style="font-size: 9pt;"><b><i>CS Form No. 32</i></b><br><i>Revised 2018</i></td>
                        </tr>
                        <tr>
                            <td height="30"></td>
                        </tr>
                        <tr>
                            <td align="right" width="30%"></td>
                            <td class="f-size-notice" align="center" width=""><span style="font-size:12pt;"><b>Republic of the Philippines</b></span><br><span class="" style="font-size:13pt;"><b>DEPARTMENT OF HEALTH</b></span><br><span><b>Office of the Secretary</b></span><br>Manila</td>              
                            <td width="30%"><br></td>
                        </tr>
                        <tr>
                            <td height="30"></td>
                        </tr>
                    </tbody>
                </table>
                <table class="f-size-notice table-max" align="center">
                    <tbody>
                        <tr>
                            <td class="f-size-notice bold" align="center"><span style="font-size:13pt;">OATH OF OFFICE</span></td>
                        </tr>
                    </tbody>
                </table>
                <table class=" f-size-notice">
                    <tr>
                        <td height="30"></td>
                    </tr>
                </table>

                <p class=" f-size-notice" style="text-align: justify; display: block; line-height: 2; color: black;" ><?php echo nbs(9)?>I <span class="line bold" >&emsp;<?php echo strtoupper($record['employee_name']);?>&emsp;</b></span> of <span class="line bold" >&emsp;
                    <?php
                        $strt_no       = $record['strt_no'];
                        if ($strt_no == 'N/A' || $strt_no == '-') {
                            $strt_no = "";
                        }else{
                            $strt_no = $strt_no.', ';
                        }


                        $street_name    = $record['street_name'];
                        if ($street_name == 'N/A' || $street_name == '-') {
                            $street_name = "";
                        }else{
                            $street_name = $street_name.', ';
                        }


                        $subdivision_name       = $record['subdivision_name'];
                        if ($subdivision_name == 'N/A' || $subdivision_name == '-') {
                            $subdivision_name = "";
                        }else{
                            $subdivision_name = $subdivision_name.', ';
                        }
                        
                        $province   = $record['province_name'];
                        $region     = $record['region_code'];
                        if($region == '13')
                        {
                            $province = 'Metro Manila';
                        }else{
                            $province = $province;
                        }

                        $brgy_muni                  = $record['employee_address'];
                        $employee_addresses         = $strt_no.$street_name.$subdivision_name.$brgy_muni.', '.$province;
                        $employee_addresses = str_replace('Ã‘', 'Ñ', $employee_addresses);
                        echo strtoupper($employee_addresses);

                     ?>
                &emsp;</b></span>, having been appointed to the position of <span class="line bold">&emsp;<?php echo strtoupper($record['position_name']);?>&emsp;</span>, hereby solemnly swear, that I
                will faithfully discharge to the best of my ability, the duties of my present position and of all others that I may hereafter hold under the Republic of the Philippines; that I will bear true faith and allegiance to the same; that I will obey the laws, legal orders, and decrees promulgated by the duly constituted authorities of the Republic of the Philippines; and that I impose this obligation upon myself voluntarily, without mental reservation or purpose of evasion.
                <br><br>
                <?php echo nbs(9)?> SO HELP ME GOD.
                </p>
                <table align="right">
                    <tr>
                        <td height="30"></td>
                    </tr>
                    <tr>                    
                        <td></td>
                        <td class="td-border-bottom  f-size-notice" align="center"><b><?php echo strtoupper($record['employee_name']) ; ?></td>
                    </tr>
                    <tr>                    
                        <td></td>
                        <td class=" f-size-notice" align="center">(Signature over Printed Name<br>of the Appointee)</td>
                    </tr>
                </table>

                <table align="left">
                    <tr>
                        <td height="25"></td>
                    </tr>
                    <tr>
                        <td class=" f-size-notice" >Government ID:<?php echo nbs(9)?>_________________________________<br>
                                                    ID Number:<?php echo nbs(16)?>_________________________________<br>
                                                    Date Issued:<?php echo nbs(14)?>_________________________________</td>

                    </tr>
                </table>
                <hr style="color:black; height: 2px; line-height: 1;">
                <p class=" f-size-notice" style="text-align: justify; display: block; line-height: 1.5; color: black;" ><?php echo nbs(9)?>Subscribed and sworn to before me this <u>&emsp; &emsp; &emsp; &emsp; &emsp; &emsp; &emsp; &emsp; &emsp; &emsp; &emsp;</u> in Manila, Philippines.<br>
                </p><br>
                <table align="right">
                    <tr>
                        <td height=""><br><br><br><br></td>
                    </tr>
                    <tr>                    
                        <td></td>
                        <td class="td-border-top  f-size-notice" align="center"><b>&emsp;<?php echo strtoupper($certified_by['signatory_name']) ; ?>&emsp;</b><br><?php echo $certified_by['position_name'] ?><br> <?php echo $certified_by['office_name']?></td>
                    </tr>
                </table>
            </div>
        </div>  
    </body>
</html>
