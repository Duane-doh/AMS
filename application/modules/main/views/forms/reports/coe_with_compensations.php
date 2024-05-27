<html>
<head>
  <title>COE - with Compensations</title>
  <link rel="stylesheet" href="<?php echo base_url().PATH_CSS ?>reports.css" type="text/css" />
</head>
<body>
    <table>
    <tr>
        <td>
            
       
    <table style="font-size: 12pt">
        <tbody>
            <tr>
                <td><?php echo nbs(10) ?><img src="<?php echo base_url().PATH_IMG ?>doh_logo.png" width=90 height=90></img></td>
                <td class="align-c" width="410" style="font-size: 10pt">Republic of the Philippines<br><span style="font-size: 12pt">Department of Health</span><br><span class="bold" style="font-size: 14pt">OFFICE OF THE SECRETARY</span></td>                
                <td><br></td>
            </tr>
        </tbody>
    </table>
    <?php 
        $honor = "MR.";
        $his_her = "his";
        $he_she = "he";
        if($employee_info['gender_code'] == 'F'):
            $his_her = "her";
            $honor = "Ms.";
            $he_she = "she";
        endif;
        
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
       <!--  <tr>
            <td colspan="10" class="td-left-middle pad-top-50">
                TO WHOM IT MAY CONCERN:
            </td>
        </tr> -->
        <tr>
            <!-- <td colspan="10" class="pad-top-50 pad-bot-30" style="text-align: justify;">
                <?php //echo nbs(10)?>This is to certify that <b><?php //echo $honor; ?> <?php //echo strtoupper($employee_info['employee_name']); ?></b> has been employed in the Department since <?php //echo format_date($employment_date['employ_start_date'], 'F d, Y') ?> and is presently holding the position of <?php //echo convertTitleCase($employee_info['employ_position_name']) ?> on a <?php //echo convertTitleCase($employee_info['employment_status_name']) ?> status in the <?php //echo convertTitleCase($employee_info['employ_office_name']); ?>, with the following annual compensation/allowances per <?php //echo $his_her;?> latest record on file in this Office:
            </td> -->
            <!--===== jen : start : add space before date =====-->
            <td colspan="10" class="pad-top-50 pad-bot-30" style="text-align: justify;">
                <?php echo nbs(10)?>This is to certify that per existing records on file, <b><?php echo $honor; ?> <?php echo strtoupper($employee_info['employee_name']); ?></b> has been employed in this Department since <?php echo format_date($employment_date['employ_start_date'], 'F d, Y') ?> and is presently holding the position of <?php echo convertTitleCase($employee_info['employ_position_name']) ?> <?php echo convertTitleCase($employee_info['employment_status_name']) == 'Job Order' ? 'under Contract of Service' : 'on a '.convertTitleCase($employee_info['employment_status_name']).' status' ?> in the <?php echo strpos(convertTitleCase($employee_info['employ_office_name']), " - DOH") ? substr_replace(convertTitleCase($employee_info['employ_office_name']),'',-6) : convertTitleCase($employee_info['employ_office_name']); ?>, with the following annual compensation/allowances per <?php echo $his_her;?> latest record on file in this Office:
            </td>
            <!--===== jen : end : add space before date =====-->
        </tr>
        
    </table>
    <table class="td-center-middle" style="font-family: 'Times New Roman', Times, serif; width: 100%; font-size: 12pt">
    <?php
        $total_amount = 0;
        foreach($general_payroll_list AS $key => $gp)
        {
            echo '<tr>
                    <td colspan="6" class="pad-left-100 td-left-middle">
                        ' . $gp['compensation_name'] . '
                    </td>
                    <td colspan="2" class="td-left-middle">
                        ...................
                    </td>
                    <td colspan="1" class="td-right-middle">
                        '. ($key==0 ? '&#8369;' : '') .'
                    </td>
                    <td colspan="1" width="100" align="right" class="td-right-middle ' . (++$key == count($general_payroll_list) ? 'td-border-bottom' : '') . '">
                        ' . number_format($gp['ret_amount'][$gp['compensation_id']]['amount'],2) . '
                    </td>
                    <td colspan="1" class="td-right-middle" width="50"></td>
                </tr>';
                +$total_amount += +$gp['ret_amount'][$gp['compensation_id']]['amount'];
        }
        echo    '<tr>
                    <td colspan="6" class="pad-left-100 td-left-middle">
                    &nbsp;
                    </td>
                    <td colspan="2" class="td-right-middle">
                        TOTAL
                    </td>
                    <td colspan="1" class="td-right-middle">
                        &#8369;
                    </td>
                    <td colspan="1" width="100" align="right" class="td-right-middle td-border-thick-bottom pad-bot-10">
                        ' . number_format($total_amount,2) . '
                    </td>
                    <td colspan="1" class="td-right-middle" width="50"></td>
                </tr>';

    ?>
    <?php if($special_ben):?>
        <tr>
            <td colspan="10" class="pad-left-50 pad-top-30 pad-bot-30" style="text-align: justify">
                <?php echo nbs(10)?>Further, <?php echo $he_she; ?> received the following additional remuneration other than the above during the period of <?php echo $comp_year; ?>. 
            </td>
        </tr>

        <?php
            $total_amount = 0;
            foreach($special_ben AS $key => $sp)
            {
                echo '<tr>
                        <td colspan="6" class="pad-left-100 td-left-middle">
                            ' . $sp['compensation_name'] . '
                        </td>
                        <td colspan="2" class="td-left-middle">
                            ...................
                        </td>
                        <td colspan="1" class="td-right-middle">
                            '. ($key==0 ? '&#8369;' : '') .'
                        </td>
                        <td colspan="1" width="100" class="td-right-middle ' . (count($special_ben) == $key + 1 ? 'td-border-bottom' : '') . '">
                            ' . number_format($sp['compensation_amount'],2) . '
                        </td>
                        <td colspan="1" width="50" class="td-right-middle" width="50"></td>
                    </tr>';
                    $total_amount += $sp['compensation_amount'];
            }
            echo    '<tr>
                        <td colspan="6" class="pad-left-100 td-left-middle">
                        &nbsp;
                        </td>
                        <td colspan="2" class="td-right-middle">
                            TOTAL
                        </td>
                        <td colspan="1" class="td-right-middle">
                            &#8369;
                        </td>
                        <td colspan="1" width="100" align="right" class="td-right-middle td-border-thick-bottom pad-bot-10">
                            ' . number_format($total_amount,2) . '
                        </td>
                        <td colspan="1" class="td-right-middle" width="50"></td>
                    </tr>';

        ?>
    <?php endif;?>

    </table>
    <table>
        <!-- <tr>
            <td colspan="2" class="pad-top-30 pad-bot-30 align-j" style="font-family: 'Times New Roman', Times, serif;font-size: 12pt;">
                <?php //echo nbs(10)?>This certification is being issued upon the request of <b><?php //echo $honor; ?> <?php //echo $employee_info['last_name']; ?></b> for whatever legal purpose it may serve.
            </td>
        </tr> -->
        <!--===== jen : start : add space before date =====-->
        <tr>
            <td colspan="2" class="pad-top-30 pad-bot-30 align-j" style="font-family: 'Times New Roman', Times, serif;font-size: 12pt;">
                <?php echo nbs(10)?>This certification is being issued for whatever legal purpose it may serve.
            </td>
        </tr>
        <!--===== jen : end : add space before date =====-->
        <tr>
            <td height="30" colspan="2"></td>
        </tr>
        <tr>
            <td width="270"></td>
            <td style="font-size: 12pt" width="400">
			<?php //echo nbs(20) //jendaigo: change spacing size ?>
			<?php echo nbs(11)?>
			By Authority of the Secretary of Health:</td>
        </tr>
        <tr>
            <td height="30"></td>
        </tr>
		<!-- marvin -->
		<tr>
			<td></td>
			<td class="bold" style="text-align: center; font-family: 'Times New Roman', Times, serif;font-size: 12pt;">
				<?php //echo nbs(14) //jendaigo: change spacing size ?>
				<?php echo nbs(5)?>
				<?php echo $certified_by['signatory_name']?>
			</td>
		</tr>
		<tr>
			<td></td>
			<td style="text-align: center; font-family: 'Times New Roman', Times, serif;font-size: 12pt;">
				<?php //echo nbs(14) //jendaigo: change spacing size ?>
				<?php echo nbs(5)?>
				<?php echo $certified_by['position_name']?>
			</td>
		</tr>
		<tr>
			<td></td>
			<td style="text-align: center; font-family: 'Times New Roman', Times, serif;font-size: 12pt;">
				<?php //echo nbs(14) //jendaigo: change spacing size ?>
				<?php echo nbs(4)?>
				<?php echo $certified_by['office_name']?>
			</td>
		</tr>
		<!-- marvin -->
        <!--<tr>
            <td></td>
            <td>
                <table style="text-align: center; font-family: 'Times New Roman', Times, serif;font-size: 12pt;">
                    <tr>
                        <td class="bold">
                            <?php //echo nbs(20)?><?php //echo $certified_by['signatory_name']?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <?php //echo nbs(20)?><?php //echo $certified_by['position_name']?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <?php //echo nbs(20)?><?php //echo $certified_by['office_name']?>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>-->
        <tr>
            <td height="40"></td>
        </tr>
        <tr>
            <td colspan="2" class="align-c" style="font-size: 8pt;font-family: Arial;">Note: Not valid without the official seal of the Department of Health</td>
        </tr>
        <tr>
            <td height="20"></td>
        </tr>      
    </table>
     </td>
    </tr>
    </table>
</body>
</html>

