<!DOCTYPE html>
<html>
<?php if(count($compensations)>0):?>
<head>
  <title>Special Payroll Summary per Office</title>
  <link rel="stylesheet" href="<?php echo base_url().PATH_CSS ?>reports.css" type="text/css" />
</head>
<body>
<?php 
	$total_column = 2 + count($compensations) + count($deductions);
	$hdr_td_cnt = count($compensations) + count($deductions);
?>
<div>
<table class="center-85" border="1" width="100%">
	<thead>
    <tr>
   		<td colspan="<?php echo $total_column;?>" class="border-hide" >
	    	<table border="1" class="table-max">
	    		<tr>
			        <td class="border-hide" width="20%"><?php echo nbs(10) ?><img src="<?php echo base_url().PATH_IMG ?>doh_logo.png" width=90 height=90></img></td>
			        <td class="border-hide f-size-14" align="center" width="60%" style="padding-left: -50px;">Republic of the Philippines<br>DEPARTMENT OF HEALTH</td>
			        <td class="border-hide" width="20%">&nbsp;</td>	    			
	    		</tr>
	    	</table>
    	</td>
    </tr>
	<tr>
		<td class="border-hide align-c" colspan="<?php echo $total_column;?>">&nbsp;</td>
	</tr>
	<tr>
		<td class="border-hide align-c f-size-12" colspan="<?php echo $total_column;?>"><b><?php echo $office_name['name'];?></b></td>
	</tr>
	<tr>
		<td class="border-hide align-c f-size-12" colspan="<?php echo $total_column;?>"><b><?php echo $compensation_type['compensation_name'];?> CY <?php echo $year?></b></td>
	</tr>
  	<tr>
		<td class="border-hide-l border-hide-r align-c" colspan="<?php echo $total_column;?>">&nbsp;</td>
	</tr>
	<tr>
		<th class="td-center-middle f-size-12">Office Name</th>
		<!-- COMPENSATION HEADER -->
	    <?php
	    	foreach($compensations as $compensation):
	    ?>
	    	<th class="td-center-middle f-size-12" width="100"><?php echo $compensation['compensation_name']?></th>
	    <?php endforeach;?>
		<!-- DEDUCTION HEADER -->
	    <?php 
	    	foreach($deductions as $deduction):
	    ?>
	    	<th class="td-center-middle f-size-12" width="100"><?php echo $deduction['deduction_name'];?></th>
	    <?php endforeach;?>
	    <th class="td-center-middle f-size-12">Total</th>
	</tr>
	</thead>
	<!-- MULTIPLE CHILD -->
	<tbody>
	<?php 
	if($multi_child):
		$ctr=0;
		$grand_total = 0;
		foreach ($com_amt_w_child as $key=>$values):
			$compen_sum = 0;
			$deduct_sum = 0;
			$total = 0;
	?>	
		<tr>
		
		<!-- COMPENSATIONS -->
		<?php
			foreach($values as $val):
			$class ="";
			if(is_numeric($val))
			{
				$compen_sum = $compen_sum + $val;
				$val = number_format($val, 2);
				$class = "td-right-bottom";
			}
		?>
			<td class="<?php echo $class . " f-size-12";?>"><?php echo $val;?></td>
		<?php 
			endforeach;
		?>
		
		<!-- DEDUCTIONS -->
		<?php 
			$ded_ctr = 0;
			foreach($deductions as $deduction):
				$inputted = FALSE;
				foreach($deduct_amounts[$ded_ctr] as $ded_am):
		?>
				<?php 
					if(($key == $ded_am['office_id']) AND ($deduction['deduction_id'] == $ded_am['deduction_id'])):
					$deduct_sum = $deduct_sum + $ded_am['amount'];
				?>
	    			<!-- IF THERES A MATCH -->
	    			<td class="td-right-bottom f-size-12"><?php echo ISSET($ded_am['amount']) ? number_format($ded_am['amount'], 2): "0.00";?></td>
	  			<?php 
	  				$inputted = TRUE;
	  				endif;
	  			?>
	    <?php 
	    		endforeach;
	    		// IF NOTHING  MATCHES
	    		if($inputted==FALSE):
	    ?>
	    		<td class="td-right-bottom f-size-12">0.00</td>
	    <?php 
	    		endif;
	    	$ded_ctr++;
	    	endforeach;
	    ?>
	    	<td class="td-right-bottom f-size-12">
	    		<?php
	    			$total = $compen_sum - $deduct_sum;
	    			$grand_total = $grand_total + $total; 
	    			echo  number_format($total, 2);
	    		?>
	    	</td>
		</tr>
	<?php
		$ctr++;
		endforeach;
	?>
	
	
	<!-- SINGLE CHILD -->
	<?php 
	else:
		$grand_total = 0;
	    $com_grand = 0;
	    $ded_grand = 0;
		foreach ($comp_amounts as $datas):
			$compen_sum = 0;
			$total = 0;
			foreach ($datas as $data):
				$compen_sum = $data['amount'];
				$deduct_sum = 0;

	?>
		<tr>
			<td class="f-size-12"><?php echo strtoupper($data['office_name']);?></td>
			<td class="td-right-bottom f-size-12"><?php echo number_format($data['amount'], 2);?></td>
		<!-- DEDUCTIONS -->
		<?php 
			$ded_ctr = 0;
			foreach($deductions as $deduction):
				$inputted = FALSE;
				foreach($deduct_amounts[$ded_ctr] as $ded_am):
		?>
				<?php 
					if(($data['office_id'] == $ded_am['office_id']) AND ($deduction['deduction_id'] == $ded_am['deduction_id'])):
					$deduct_sum = $deduct_sum + $ded_am['amount'];
				?>
	    			<!-- IF THERES A MATCH -->
	    			<td class="td-right-bottom f-size-12"><?php echo ISSET($ded_am['amount']) ? number_format($ded_am['amount'], 2): "0.00";?></td>
	  			<?php 
	  				$inputted = TRUE;
	  				endif;
	  			?>
	    <?php 

	    		endforeach;
	    		// IF NOTHING  MATCHES
	    		if($inputted==FALSE):
	    ?>
	    		<td class="td-right-bottom f-size-12">0.00</td>
	    <?php 
	    		endif;
	    	$ded_ctr++;
	    	endforeach;
	    ?>
		    <td class="td-right-bottom f-size-12">
		    		<?php
		    			$total = $compen_sum - $deduct_sum;
		    			$grand_total = $grand_total + $total; 
		    			echo number_format($total, 2);
		    		?>
		    </td>
		</tr>
	<?php

	    	$com_grand = $compen_sum + $com_grand;
        	$ded_grand = $ded_grand + $deduct_sum;
			endforeach;
		endforeach;
	endif;
	?>
	
	<tr>
		<td class="td-right-bottom f-size-12"><b>Grand Total<?php echo nbs(2) . ":" . nbs(2);?></b></td>
		<!-- COMPENSATION TOTALS THIS FROM CONTROLLER-->
		<?php foreach($total_per_com as $tpc): ?>
	    	<td class="td-right-bottom f-size-12"><b><?php echo number_format($tpc, 2);?></b></td>
	    <?php endforeach;?>
	    
		<!-- DEDUCTION TOTALS THIS IS FROM THIS VIEW-->
	    <?php foreach($total_per_ded as $tpd): ?>
	    	<td class="td-right-bottom f-size-12"><b><?php echo number_format($tpd, 2);?></b></td>
	    <?php endforeach;?>
	    
		<td class="td-right-bottom f-size-12"><b><?php echo number_format($grand_total, 2);?></b></td>
	</tr>
	</tbody>
</table>
<table class="table-max f-size-12">
<tr>
	<td>
		<table class="table-max f-size-12">
			<tr>
				<td width=350 height="40" valign="bottom">Prepared by:</td>
				<td width=""></td>
			</tr>	
			<tr>
				<td height="20" align="center" height="60" valign="bottom"><b><?php echo $prepared_by['signatory_name']; ?></b></td>
				<td height="20" align="left" valign="middle"></td>
			</tr>
			<tr>
				<td height="20" align="center" valign="top"><?php echo $prepared_by['position_name'] ?><br><?php echo $prepared_by['office_name'] ?></td>
				<td height="20" align="left" valign="top"></td>
			</tr>			
		</table>
	</td>
</tr>
</table>

</div> 
</body>
<?php else :?>

<div class="wrapper">
	<form id="test_report">
		<p style="text-align: center">No data available.</p>
	</form>
</div>

<?php endif;?>
</html>