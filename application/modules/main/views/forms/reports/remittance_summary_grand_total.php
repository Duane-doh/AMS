<!DOCTYPE html>
<html>
<?php if(count($deductions)>0):?>
<head>
	<title>Remittance Summary per Office</title>
	<link rel="stylesheet" href="<?php echo base_url().PATH_CSS ?>reports.css" type="text/css" />
	<style type="text/css">
		
		.light-border td, th { 
			border: .3px solid
		}
		
	</style>
</head>
<body>
<table class="table-max f-size-12">
    <tbody>
        <tr>
            <td width="20%"><?php echo nbs(10) ?><img src="<?php echo base_url().PATH_IMG ?>doh_logo.png" width=90 height=90></img><br></td>
            <td align="center" width="60%">Republic of the Philippines<br>DEPARTMENT OF HEALTH<br>San Lazaro Compound Sta. Cruz Manila</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
        	<td colspan="3" height="50" valign="middle" align="center"><b>
	        <?php echo $remittance_type['remittance_type_name']?><br><?php echo $report_month?></b></td>
        </tr>
    </tbody>
</table>
<div>
	<table class="center-85 light-border" width="100%">
		<thead>
		<tr>
			<th class="td-center-middle f-size-10">Office Name</th>
			<?php foreach($deductions as $header):?>
				<?php if($display_share_flag):?>
					<th class="td-center-middle f-size-10" width="70"><?php echo $display_label_flag ?  $header['deduction_name'].'<br>':''?>Employee Share</th>
					<?php if ( $header['employer_share_flag'] == 'Y' ): ?>
						<th class="td-center-middle f-size-10" width="70"><?php echo $display_label_flag ?  $header['deduction_name'].'<br>':''?>Employer Share</th>
					<?php endif;?>
				<?php else:?>
				<th class="td-center-middle f-size-10" width="70"><?php echo ($display_total_flag) ? $header['deduction_name']:'Amount'?></th>
				<?php endif;?>
			<?php endforeach;?>
			<?php if($display_total_flag):?>
			<th class="td-center-middle f-size-10" width="80">Total</th>
			<?php endif; ?>
		</tr>
		</thead>
		<tbody>
		<?php 
			$grand_total = 0;
			$share = 0;
			foreach ($offices as $office):
			$total = 0;
		?>
		<tr>
			  <td class="f-size-10 pad-2"><?php echo $office['office_name']?></td>
			  <?php 
			  	$ctr=0;
			  	foreach ($deductions as $deduct):
			  		$inputted = FALSE;
			  		foreach($results[$ctr] as $result):
			  			if($remit_type_flag == REMIT_ALL)
				        {
				            $share = $result['amount'] + $result['employer_amount'];
				        }
				        else if($remit_type_flag == REMIT_PERSONAL_SHARE)
				        {
				            $share = $result['amount'];
				        }
				        else
				        {
				            $share = $result['employer_amount'];
				        }
			  ?>
			  			<?php if(($office['office_id'] == $result['office_id']) AND ($deduct['deduction_id'] == $result['deduction_id'])):?>
			  				<?php if($display_share_flag):?>
			  				<td class="td-right-bottom f-size-10 pad-2" width="70"><?php echo ISSET($result['amount']) ? number_format($result['amount'], 2): "0.00";?></td>
			  				<?php if ( $deduct['employer_share_flag'] == 'Y' ): ?>
			  					<td class="td-right-bottom f-size-10 pad-2" width="70"><?php echo ISSET($result['employer_amount']) ? number_format($result['employer_amount'], 2): "0.00";?></td>
			  				<?php endif; ?>
			  				<?php else:?>
			  				<td class="td-right-bottom f-size-10 pad-2" width="70"><?php echo ISSET($share) ? number_format($share, 2): "0.00";?></td>
			  				<?php endif; ?>
			  			<?php 

			  					if($display_share_flag) {
			  						if ( $deduct['employer_share_flag'] == 'Y' )
			  						{
			  							$deduct_total[$ctr]['employer'] += $result['employer_amount'];
			  							$deduct_total[$ctr]['display_employer'] = TRUE;
			  						}
			  						else
			  						{			  							
			  							$deduct_total[$ctr]['display_employer'] = FALSE;
			  						}

			  						$deduct_total[$ctr]['employee'] += $result['amount'];
			  					}
			  					else
			  					{
			  						$deduct_total[$ctr] += $share;
			  					}
			  					
			  					$total = $total + $share;
				  				$inputted = TRUE;
				  			 endif;
			  			 ?>
			  <?php 
			  		endforeach;
			  		if($inputted==FALSE):
			  			if ( $deduct['employer_share_flag'] == 'Y' )
			  			{
	  						$deduct_total[$ctr]['employer'] += '0.00';
			  				$deduct_total[$ctr]['display_employer'] = TRUE;
  						}
  						else
  						{			  							
  							$deduct_total[$ctr]['display_employer'] = FALSE;
  						}

	  					$deduct_total[$ctr]['employee'] += '0.00';
			  ?>
			  		<?php if($display_share_flag):?>
			  			<td class="td-right-bottom f-size-10 pad-2" width="70">0.00</td>
			  			<?php if ( $deduct['employer_share_flag'] == 'Y' ): ?>
			  				<td class="td-right-bottom f-size-10 pad-2" width="70">0.00</td>
			  			<?php endif; ?>
			  		<?php else:?>
			  			<td class="td-right-bottom f-size-10 pad-2" width="70">0.00</td>
			  		<?php endif; ?>
			   <?php 
			   		endif;
			  		$ctr++;
			  	endforeach;
			  ?>
			  <?php if($display_total_flag):?>
			  <td class="td-right-bottom f-size-10 pad-2" width="80"><?php echo number_format($total, 2);?></td>
				<?php endif; ?>
		</tr>
		<?php 	
			$grand_total = $grand_total + $total;
			endforeach;
		?>
		<tr>
			<td class="td-center-middle f-size-10 pad-2"><b>Grand Total</b></td>

		 <?php foreach($deduct_total as $val):?>
		 	<?php if($display_share_flag):?>
				<td class="pad-2" align="right"><b><?php echo number_format($val['employee'], 2);?></b></td>
				<?php if( $val['display_employer'] ): ?>
					<td class="pad-2" align="right"><b><?php echo number_format($val['employer'], 2);?></b></td>
				<?php endif; ?>
			<?php else:?>
				<td class="pad-2" align="right"><b><?php echo number_format($val, 2);?></b></td>
			<?php endif; ?>
    	<?php endforeach;?>
    		<?php if($display_total_flag):?>
			<td class="td-right-bottom f-size-10 pad-2"><b><?php echo number_format($grand_total, 2);?></b></td>
			<?php endif; ?>
		</tr>

		</tbody>
	</table>
</div>
<table class="table-max">
	<tr>
		<td>
			<table>				
			    <tr>
			        <td colspan=2 height="20"></td>
			    </tr>
			    <tr>
			        <td height="20" width="500" align="left" valign="middle">Prepared by:</td>
			        <td height="20" width="50%" align="left" valign="middle">Certified correct:</td>
			    </tr>   
			    <tr>
			        <td colspan=2 height="20"></td>
			    </tr>
			    <tr>
			        <td height="20" align="left" valign="middle"><b><?php echo $prepared_by['signatory_name']; ?></b></td>
			        <td height="20" align="left" valign="middle"><b><?php echo $certified_by['signatory_name']; ?></b></td>
			    </tr>
			    <tr>  
			        <td height="20" align="left" valign="middle"><?php echo $prepared_by['position_name'] ?><br><?php echo $prepared_by['office_name']?></td>
			        <td height="20" align="left" valign="middle"><?php echo $certified_by['position_name'] ?><br><?php echo $certified_by['office_name'] ?></td>
			    </tr>
			</table>
		</td>
	</tr>
</table>
</body>
<?php else :?>

<div class="wrapper">
	<form id="test_report">
		<p style="text-align: center">No data available.</p>
	</form>
</div>

<?php endif;?>
</html>