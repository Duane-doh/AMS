<!DOCTYPE html>
<html>
<head>
	<title>Certificate of Leave Credits</title>
	<link rel="stylesheet" href="<?php echo base_url().PATH_CSS ?>reports.css" type="text/css" />
</head>
<body>
	<table style="font-size: 12pt">
	    <tbody>
	        <tr>
	            <td><?php echo nbs(10) ?><img src="<?php echo base_url().PATH_IMG ?>doh_logo.png" width=90 height=90></img></td>
	            <td class="align-c" style="font-size: 10pt" width="410">Republic of the Philippines<br><span style="font-size: 12pt">Department of Health</span><br><span class="bold" style="font-size: 14pt">OFFICE OF THE SECRETARY</span></td>                
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
	$as_of_date = date("F j, Y",strtotime($leave_last_date));
?>
<br>
	<table width="100%" style="font-size: 12pt">
		
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
		</tr><!-- 
		<tr>
			<td class="font-family-tnr">TO WHOM IT MAY CONCERN:</td>
		</tr> -->
		<tr>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td class="font-family-tnr align-j"><?php echo nbs(8)?>This  is  to  certify  that  according  to  the   records  available   in  this  Office, <b><?php echo $honor. " " . $employee['full_name']; ?></b>, <?php echo convertTitleCase($details['employ_position_name'])?> in the <?php echo convertTitleCase($details['employ_office_name'])?>, this Department, has the following leave credit balances as of <?php echo $as_of_date;?>:</td>
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
		<tr>
			<td align="center">
				<table class="font-family-tnr" width="80%" style="font-size: 12pt">
					<?php foreach ($leave_regular as $regular):?>
					<tr>
						<td><b><?php echo $regular['leave_type_name'];?></b></td>
						<td><b>&nbsp;&nbsp;-&nbsp;&nbsp;</b></td>
						<td><b><?php echo ($regular['leave_balance'] == "0.000") ? 0 : $regular['leave_balance'];?></b></td>
					</tr>
					<?php endforeach;?>
				</table>
			</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
		</tr>
		<?php if($leave_special):?>
		<tr>
			<td class="font-family-tnr align-j"><?php echo nbs(8)?>Further, <b><?php echo $honor . " " . $employee['last_name']?></b> availed of the following:</td>
		</tr>
		<?php endif?>
		<tr>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td align="center">
				<table class="font-family-tnr" width="80%" style="font-size: 12pt">
					<?php foreach ($leave_special as $special):?>
					<tr>
						<td><b><?php echo $special['leave_type_name'];?></b></td>
						<td><b>&nbsp;&nbsp;-&nbsp;&nbsp;</b></td>
						<td><b><?php echo ($special['leave_earned'] == "0.000") ? 0 : $special['leave_earned'];?></b></td>
					</tr>
					<?php endforeach;?>
				</table>
			</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td class="font-family-tnr align-j" style="text-align: justify;"><?php echo nbs(8)?>This certification is being issued upon the request of  <b><?php echo $honor . " " . $employee['last_name']?></b> for whatever legal purpose it may serve.</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
		</tr>
	</table>
	 <table style="font-size: 12pt">
        <tr>
            <td height="30"></td>
        </tr>
        <tr>
            <td width="270"></td>
            <td width="400"><?php echo nbs(20)?>By Authority of the Secretary of Health:</td>
        </tr>
        <tr>
            <td height="50"></td>
        </tr>
        <tr>
            <td></td>
            <td>
                <table style="text-align: center; font-family: 'Times New Roman', Times, serif;font-size: 12pt;">
                    <tr>
                        <td class=" bold">
                            <?php echo nbs(20)?><?php echo $certified_by['signatory_name']?>
                        </td>
                    </tr>
                    <tr>
                        <td >
                            <?php echo nbs(20)?><?php echo $certified_by['position_name']?>
                        </td>
                    </tr>
                    <tr>
                        <td >
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