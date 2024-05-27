<html>
<head>
  <title>COE - without Compensations</title>
  <link rel="stylesheet" href="<?php echo base_url().PATH_CSS ?>reports.css" type="text/css" />
</head>
<body>
    <table style="font-size: 12pt">
        <tbody>
            <tr>
                <td><?php echo nbs(5) ?><img src="<?php echo base_url().PATH_IMG ?>doh_logo.png" width=100 height=100></img></td>
                <td class="align-c" style="font-size: 11pt" width="380">Republic of the Philippines<br><span class="bold" style="font-size: 11pt">DEPARTMENT OF HEALTH</span><br><span style="font-size: 12pt; font-style: italic;">Office of the Secretary </span></td>                
                <td><img src="<?php echo base_url().PATH_IMG ?>bagong_pilipinas_logo.png" width=110 height=110></img></td>
            </tr>
        </tbody>
    </table>
    <?php 
    if($employee_info['gender_code'] == 'F')
    {
        // $label = 'MS. ';
        
        //===== davcorrea : start : position/eligibility ======
        $sec = array(16509);
        $asec = array(16368,16506);
        $usec = array(16512,16973);
        $director = array(16526,16527,16528,16531);
     
        if(in_array($position['employ_position_id'], $sec))
        {
            
            $honor = 'Sec. ';
        }
        elseif(in_array($position['employ_position_id'], $asec))
        {
            $honor = 'Asec. ';
        }
        elseif(in_array($position['employ_position_id'], $usec))
        {
            $honor = 'Usec. ';
        }
        elseif(in_array($position['employ_position_id'], $director))
        {
            $honor = 'Dir. ';
        }
        elseif(in_array(2161, $position['eligibility']) OR in_array(2169, $position['eligibility']))
        {
            $honor = 'Dr. ';
        }
        elseif(in_array(2116, $position['eligibility']))
        {
            $honor = 'Atty. ';
        }
        elseif(in_array(2117, $position['eligibility']))
        {
            $honor = 'Engr. ';
        }
        elseif(in_array(2166, $position['eligibility']))
        {
            $honor = 'Arch. ';
        }
        else
        {
            $honor = 'MS. ';				
        }
        // echo"<pre>";
        // print_r($position['eligibility']);
        // die();
        //===== davcorrea : end : position/eligibility ======
    }
    if($employee_info['gender_code'] == 'M')
    {
        // $label = 'MR. ';
        
        //===== davcorrea : start : position/eligibility ======
        $sec = array(16509);
        $asec = array(16368,16506);
        $usec = array(16512,16973);
        $director = array(16526,16527,16528,16531);
        
        if(in_array($position['employ_position_id'], $sec))
        {
            $honor = 'Sec. ';
        }
        elseif(in_array($position['employ_position_id'], $asec))
        {
            $honor = 'Asec. ';
        }
        elseif(in_array($position['employ_position_id'], $usec))
        {
            $honor = 'Usec. ';
        }
        elseif(in_array($position['employ_position_id'], $director))
        {
            $honor = 'Dir. ';
        }
        elseif(in_array(2161, $position['eligibility']) OR in_array(2169, $position['eligibility']))
        {
            $honor = 'Dr. ';
        }
        elseif(in_array(2116, $position['eligibility']))
        {
            $honor = 'Atty. ';
        }
        elseif(in_array(2117, $position['eligibility']))
        {
            $honor = 'Engr. ';
        }
        elseif(in_array(2166, $position['eligibility']))
        {
            $honor = 'Arch. ';
        }
        else
        {
            $honor = 'MR. ';				
        }
        //===== davcorrea : end : position/eligibility ======
    }
        // $honor = "MR.";
        // if($employee_info['gender_code'] == 'F'):
        //     $honor = "MS.";
        // endif;
        
    ?>
    <table class="td-center-middle" style="font-family: 'Times New Roman', Times, serif; width: 100%; font-size: 12pt">       
        <!--===== jen : start : add space before date =====-->
        <tr>
            <td colspan="10" class="td-center-middle pad-top-30"></td>
        </tr>
        <!--===== jen : end : add space before date =====-->
        <tr>
            <td colspan="10" class="td-right-center pad-bot-20 pad-top-20">
                <?php echo format_date(date('Y-m-d'), 'F d, Y'); ?>
            </td>
        </tr>
        <tr>
            <td colspan="10" class="td-center-middle pad-top-30 bold">
                C E R T I F I C A T I O N
            </td>
        </tr>
        <!-- <tr>
            <td colspan="10" class=" td-left-middle pad-top-50">
                TO WHOM IT MAY CONCERN:
            </td>
        </tr> -->
        <tr>
            <!--<td colspan="10" class="pad-top-50 pad-bot-10" style="text-align: justify;">
                <?php //echo nbs(10)?>This is to certify that <b><?php //echo $honor; ?> <?php //echo strtoupper($employee_info['employee_name']); ?></b> <?php //echo ($separated_flag) ? 'was employed':'has been employed'; ?> in the Department <?php //echo ($separated_flag) ? 'from':'since'; ?> <?php //echo format_date($employment_date['employ_start_date'], 'F d, Y') ?> <?php //echo ($separated_flag) ? 'to ':'and is presently'; ?><?php //echo ($separated_flag) ? format_date($employment_date['employ_end_date'], 'F d, Y'):''; ?> holding the position of <?php //echo convertTitleCase($employee_info['employ_position_name']) ?> on a <?php //echo convertTitleCase($employee_info['employment_status_name']) ?> status in the <?php //echo convertTitleCase($employee_info['employ_office_name']); ?>.
            </td>-->
			<!--===== jen : start : modify text =====-->
            <!-- <td colspan="10" class="pad-top-50 pad-bot-10" style="text-align: justify;">
                <?php //echo nbs(10)?>This is to certify that per existing records on file, <b><?php //echo $honor; ?> <?php //echo strtoupper($employee_info['employee_name']); ?></b> <?php //echo ($separated_flag) ? 'was employed':'has been employed'; ?> in this Department <?php //echo ($separated_flag) ? 'from':'since'; ?> <?php// echo format_date($employment_date['employ_start_date'], 'F d, Y') ?> <?php// echo ($separated_flag) ? 'to ':'and is presently'; ?><?php// echo ($separated_flag) ? format_date($employment_date['employ_end_date'], 'F d, Y'):''; ?> holding the position of <?php// echo convertTitleCase($employee_info['employ_position_name']) ?> <?php //echo convertTitleCase($employee_info['employment_status_name']) == 'Job Order' ? 'under Contract of Service' : 'on a '. convertTitleCase($employee_info['employment_status_name']).' status' ?> in the <?php //echo strpos(convertTitleCase($employee_info['employ_office_name']), " - DOH") ? substr_replace(convertTitleCase($employee_info['employ_office_name']),'',-6) : convertTitleCase($employee_info['employ_office_name']); ?>.
            </td> -->
            <!--===== jen : end : modify text =====-->
            
    <td colspan="10" class="pad-top-50 pad-bot-10" style="text-align: justify;">
                <?php echo nbs(10)?>This is to certify that per existing records on file, <b><?php echo $honor; ?> <?php echo strtoupper($employee_info['employee_name']); ?></b>
                <?php
                    if($separated_flag)
                    {
                        $text = 'former ' . convertTitleCase($employee_info['employ_position_name']). ' ';
                        $text .= convertTitleCase($employee_info['employment_status_name']) == 'Job Order' ? 'under Contract of Service in the ' : 'on a '. convertTitleCase($employee_info['employment_status_name']).' status in the ';
                        $text .= strpos(convertTitleCase($employee_info['employ_office_name']), " - DOH") ? substr_replace(convertTitleCase($employee_info['employ_office_name']),'',-6) : convertTitleCase($employee_info['employ_office_name']);
                        $text .= ' was employed from '. format_date($employment_date['employ_start_date'], 'F d, Y'). ' to ' . format_date($employment_date['employ_end_date'], 'F d, Y'). '.';

                    }
                    else
                    {
                        $text = 'has been employed in this Department since ' . format_date($employment_date['employ_start_date'], 'F d, Y') . ' and is presently holding the position of '. convertTitleCase($employee_info['employ_position_name']). ' ';
                        $text .= convertTitleCase($employee_info['employment_status_name']) == 'Job Order' ? 'under Contract of Service in the ' : 'on a '. convertTitleCase($employee_info['employment_status_name']). ' status in the ';
                        $text .= strpos(convertTitleCase($employee_info['employ_office_name']), " - DOH") ? substr_replace(convertTitleCase($employee_info['employ_office_name']),'',-6).'.' : convertTitleCase($employee_info['employ_office_name']). '.';
                        // $text .= 'was employed from '. format_date($employment_date['employ_start_date'], 'F d, Y'). ' to ' . format_date($employment_date['employ_end_date'], 'F d, Y'). '.';
                    }
                    echo $text;
                ?> 
            </td>
</tr>
        <tr>
            <!--<td colspan="10" class="pad-top-10 pad-bot-30" style="text-align: justify;">
                <?php //echo nbs(10)?>This certification is being issued upon the request of <b><?php //echo $honor; ?> <?php //echo $employee_info['last_name']; ?></b> for whatever legal purpose it may serve.
            </td>-->
			<!--===== jen : start : modify text =====-->
			<td colspan="10" class="pad-top-10 pad-bot-30" style="text-align: justify;">
                <?php echo nbs(10)?>This certification is being issued for whatever legal purpose it may serve.
            </td>
			<!--===== jen : end : modify text =====-->
        </tr>
    </table>
    <table>
        <tr>
            <td height="30"></td>
        </tr>
        <tr>
            <td width="270"></td>
            <td style="font-size: 12pt" width="400">
			<?php //echo nbs(20) //jendaigo: change spacing size ?>
			<?php echo nbs(9)?>
			By Authority of the Secretary of Health:</td>
        </tr>
        <tr>
            <td height="50"></td>
        </tr>
        <tr>
            <td width="45%"></td>
            <td>
                <table style="text-align: center; font-family: 'Times New Roman', Times, serif;font-size: 12pt">
                    <tr>
                        <td class="bold">
                            <?php if(strlen($certified_by['signatory_name']) < 30) echo nbs(14)?>
							<?php echo $certified_by['signatory_name']?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                             <?php if(strlen($certified_by['signatory_name']) < 30) echo nbs(14)?>
							<?php echo $certified_by['position_name']?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                         <?php if(strlen($certified_by['signatory_name']) < 30) echo nbs(14)?>
							<?php echo $certified_by['office_name']?>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td height="50"></td>
        </tr>
        <tr>
            <td colspan="2" class="align-c" style="font-size: 8pt;font-family: Arial;">Note: Not valid without the official seal of the Department of Health</td>
        </tr>    
    </table>
</body>
</html>

