<!DOCTYPE html>
<html>
<head>
	<title>Certificate of Leave Without Pay</title>
	<link rel="stylesheet" href="<?php echo base_url().PATH_CSS ?>reports.css" type="text/css" />
</head>
<body>
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
	if($employee['gender_code'] == 'F'):
		$honor = "MS.";
	endif;
	
	$today = date("F j, Y");
?>
<br>
<?php if(!EMPTY($year)):?>
	<table style="font-size: 12pt">
		
		<tr>
			<td class="font-family-tnr td-right-middle"><?php echo $today;?></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td class="td-center-middle"><b><span>C E R T I F I C A T I O N</span></b></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
		</tr>
		<!-- <tr>
			<td class="font-family-tnr f-size-12">TO WHOM IT MAY CONCERN:</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
		</tr> -->
		<tr>
			<td class="font-family-tnr align-j"><?php echo nbs(8)?>This  is  to  certify  that  according  to  the   records  available   in  this  Office, <b><?php echo $honor. " " . $employee['full_name']; ?></b>, <?php echo convertTitleCase($details['employ_position_name'])?> in the <?php echo convertTitleCase($details['employ_office_name'])?>, this Department had incurred leave without pay on the dates indicated hereunder:</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td align="center">
				<table class="font-family-tnr" width="80%" style="font-size: 12pt">
					<tr>
						<th class="td-border-4 td-center-middle" width="15%">Year</th>	
						<th class="td-border-top td-border-bottom td-border-right td-center-middle" width="20%">Month</th>
						<th class="td-border-top td-border-bottom td-border-right td-center-middle" width="30%">Date</th>
						<th class="td-border-top td-border-bottom td-border-right td-center-middle" width="15%">No. of Days</th>
						<th class="td-border-top td-border-bottom td-border-right td-center-middle" width="20%">Type of Leave</th>
					</tr>
					<?php foreach($results as $result):?>
					<tr>
						<td class="td-border-bottom td-border-left td-border-right td-center-middle"><?php echo $result['leave_years'];?></td>
						<td class="td-border-bottom td-border-left td-border-right td-center-middle"><?php echo $result['display_month'];?></td>
						<td class="td-border-bottom td-border-right td-center-middle"><?php echo $result['leave_dates']?></td>
						<td class="td-border-bottom td-border-right td-center-middle"><?php echo round($result['lwop_days'],3);?></td>
						<td class="td-border-bottom td-border-right td-center-middle"><?php echo $result['leave_type_name'];?></td>
					</tr>
					<?php endforeach;?>
					<tr>
						<td class="td-border-3">&nbsp;</td>
						<td class="td-border-3">&nbsp;</td>
						<td class="td-border-3"><b>Total</b></td>
						<td class="td-border-3 td-center-middle"><b><?php echo $lwop_sum;?></b></td>
						<td class="td-border-4">&nbsp;</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td class="font-family-tnr align-j"><?php echo nbs(8)?>This certification is being issued upon the request of <b><?php echo $honor . " " . $employee['last_name']?></b> for whatever purpose it may serve.</td>
		</tr>
	</table>
<?php else :?>
	<table style="font-size: 12pt">
		<tr>
			<td class="font-family-tnr td-right-middle"><?php echo $today;?></td>
		</tr>
		
		<tr>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td class="td-center-middle"><b><span>C E R T I F I C A T I O N</span></b></td>
		</tr>
		
		<tr>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
		</tr>
		<!-- <tr>
			<td class="font-family-tnr">TO WHOM IT MAY CONCERN:</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
		</tr> -->
		<tr>
			<td class="font-family-tnr align-j"><?php echo nbs(8)?>This  is  to  certify  that  according  to  the   records  available   in  this  Office, <b><?php echo $honor. " " . $employee['full_name']; ?></b>, <?php echo convertTitleCase($details['employ_position_name'])?> in the <?php echo convertTitleCase($details['employ_office_name'])?>,  this Department has no leave without pay from <?php echo $date_from;?> up to <?php echo ($date_to === $today) ? "present" : $date_to?>.</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td class="font-family-tnr align-j"><?php echo nbs(8)?>This certification is being issued upon the request of <b><?php echo $honor . " " . $employee['last_name']?></b> for whatever purpose it may serve.</td>
		</tr>
	</table>
<?php endif;?>
	<table style="font-size: 12pt">
        <tr>
            <td height="50"></td>
        </tr>
        <tr>
            <td width="270"></td>
            <td width="400"><?php echo nbs(20)?>By Authority of the Secretary of Health:</td>
        </tr>
        <tr>
            <td height="40"></td>
        </tr>
        <tr>
            <td></td>
            <td>
                <table style="text-align: center; font-family: 'Times New Roman', Times, serif;font-size: 12pt;">
                    <tr>
                        <td class="bold">
                            <?php echo nbs(20)?><?php echo $certified_by['signatory_name']?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <?php echo nbs(20)?><?php echo $certified_by['position_name']?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <?php echo nbs(20)?><?php echo $certified_by['office_name']?>
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
        <tr>
            <td height="20"></td>
        </tr>  
    </table>
</body>


</html>