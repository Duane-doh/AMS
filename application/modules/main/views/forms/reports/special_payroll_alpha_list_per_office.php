<!DOCTYPE html>
<html>
<?php if(count($results)>0):?>
<head>
	<title>Special Payroll Alphalist Per Office</title>
	<link rel="stylesheet" href="<?php echo base_url().PATH_CSS ?>reports.css" type="text/css" />
</head>
<body>
<?php 
  $total_column = 8 + count($com_hdr) + count($ded_hdr) + $cna_count;
  $hdr_cnt      = 6 + count($com_hdr) + count($ded_hdr) + $cna_count;
?>
<br>
<div>
  <table class="center-85" border="1" width="100%">
  	<thead>
  	<tr>
  		<td class="border-hide align-c" colspan="<?php echo $total_column;?>">
          <table class="table-max f-size-16" border="1">
              <tbody>
                  <tr>
                      <td class="border-hide" width="150"><img src="<?php echo base_url().PATH_IMG ?>doh_logo.png" width=90 height=90></img><br></td>
                      <td class="border-hide" align="center" width="800" colspan="<?php echo $hdr_cnt;?>">Republic of the Philippines<br>DEPARTMENT OF HEALTH</td>
                      <td class="border-hide" width="150">&nbsp;</td>
                  </tr>
              </tbody>
          </table>
  		</td>
  	</tr>
  	<tr>
  		<td class="border-hide align-c" colspan="<?php echo $total_column;?>">&nbsp;</td>
  	</tr>
  	<tr>
  		<td class="border-hide align-c f-size-16" colspan="<?php echo $total_column;?>"><b><?php echo $office_name['name'];?></b></td>
  	</tr>
  	<tr>
  		<td class="border-hide align-c f-size-16" colspan="<?php echo $total_column;?>"><b><?php echo $compensation_name['compensation_name'];?> CY <?php echo $year;?></b></td>
  	</tr>
  	<tr>
		<td class="border-hide-l border-hide-r align-c" colspan="<?php echo $total_column;?>">&nbsp;</td>
	</tr>
    <tr>
      <th class="align-c f-size-12" width="20">No.</thth>
      <th class="align-c f-size-12" width="200">Name</th>
      <th class="align-c f-size-12" width="200">Designation</th>
      <th class="align-c f-size-12" width="20">SG</th>
      <?php foreach ($com_hdr as $c):?>
        
      <th class="align-c f-size-12" width="100"><?php echo $c['compensation_name']; ?></th>
      <?php if($c['compensation_code'] == $cna_code):?>
          <th class="align-c f-size-12" width="100">Agency Fee</th>
        <?php endif;?>
	  <?php endforeach;?>
	  
	  <?php foreach ($ded_hdr as $d):?>
      <th class="align-c f-size-12" width="100"><?php echo $d['deduction_name']; ?></th>
	  <?php endforeach;?>
	  
      <th class="align-c f-size-12" width="100">NET PAY</th>
      <th class="align-c f-size-12" width="100">SIGNATURE</th>
      <th class="align-c f-size-12" width="100">INITIALS</th>
      <th class="align-c f-size-12" width="100">REMARKS</th>
    </tr>
	</thead>
	<tbody>
    <?php 
    $ctr = 0;
    $com_grand = 0;
    $ded_grand = 0;
    $net_grand = 0;
    $page_row_count = 18;
    $total_per_com = array();
    $total_per_ded = array();
    $ototal_cna_less = 0;
    $cna_id = null;
	foreach ($results as $key => $result):
  	$ctr++;
  	$com_total = 0;
  	$ded_total = 0;
  	$net	   = 0;
    $total_cna_less = 0;
  	?>
	 <tr>
      <td class="align-c pad-2 f-size-12" height="20"><?php echo $ctr; ?></td>
      <td class="pad-2 f-size-12"><?php echo $result['employee_name']; ?></td>
      <td class="pad-2 f-size-12"><?php echo $result['position_name']; ?></td>
      <td class="align-c pad-2 f-size-12"><?php echo $result['employ_salary_grade']; ?></td>
      <?php
        foreach ($com_hdr as $cnt => $com) :
    		$com_total = $com_total + $result[$com['compensation_id']];
        $com_page_total[$cnt] += $result[$com['compensation_id']];
        $total_per_com[$cnt] += $result[$com['compensation_id']];
        $total_cna_less += $result['cna_less'];
        $ototal_cna_less += $result['cna_less'];
    	  ?> 
          <td class="align-r pad-2 f-size-12"><?php echo number_format($result[$com['compensation_id']],2); ?></td>
          <?php if($c['compensation_code'] == $cna_code):?>
            <?php $cna_id = $cnt;
            $ded_total += $result['cna_less'];
            ?>
            <td class="align-r f-size-12" width="100"><?php echo number_format($result['cna_less'],2); ?></td>
          <?php endif;?>
      <?php 
        $com_grand = $com_total + $com_grand;
      endforeach; ?>      

      <?php foreach ($deductions[$key] as $cnt => $ded):
      	$ded_total = $ded_total + $ded['amount'];
        $ded_page_total[$cnt] += $ded['amount'];
        $total_per_ded[$cnt] += $ded['amount'];
      ?>
         <td class="align-r pad-2 f-size-12"><?php echo ISSET($ded['amount']) ? number_format($ded['amount'],2) : "0.00"; ?></td>
      <?php         
        $ded_grand = $ded_grand + $ded_total;
      endforeach;
      	$net = $com_total - $ded_total;        
        $net_grand = $net_grand + $net;
		
		$net_page  = 0;
      ?>
      <td class="align-r pad-2 f-size-12"><?php echo number_format($net,2); ?></td>
      <td></td>
      <td></td>
      <td></td>
    </tr>
     <?php  if($ctr%$page_row_count == 0 OR count($results) == $ctr):?>
         <tr>
        <td class="f-size-16 align-r" colspan="4">Page Total<?php echo nbs(5) . ":" . nbs(2);?></td>
        <!-- COMPENSATION TOTALS -->
        <?php foreach ($com_page_total as $index => $tpc):
  			$net_page += $tpc;
  		?>
        <td class="align-r pad-2 f-size-16"><?php echo number_format($tpc,2); ?></td>
         <?php if($cna_count > 0 AND $index == $cna_id):?>
          <?php $net_page -= $ototal_cna_less;?>
          <td class="align-r pad-2 f-size-16"><?php echo number_format($ototal_cna_less,2); ?></td>
        <?php endif;?>
        <?php endforeach; ?>
        
        <!-- DEDUCTION TOTALS -->
        <?php foreach ($ded_page_total as $tpd):
			$net_page -= $tpd;
		?>
        <td class="align-r pad-2 f-size-16"><?php echo number_format($tpd,2); ?></td>
        <?php endforeach;?>
        
        <td class="align-r pad-2 f-size-16"><?php echo number_format($net_page,2); ?></td>
        <td class="align-c">---</td>
        <td class="align-c">---</td>
        <td class="align-c">---</td>
      </tr>
     <?php 
        $com_page_total = array();
        $ded_page_total = array();
     ?>
      <?php if(count($results) != $ctr):?>
         </tbody>
      </table>
      <pagebreak />
      <table class="center-85" border="1" width="100%">
        <thead>        
        <tr>
          <td class="border-hide align-c" colspan="<?php echo $total_column;?>">
              <table class="table-max f-size-16" border="1">
                  <tbody>
                      <tr>
                          <td class="border-hide" width="150"><img src="<?php echo base_url().PATH_IMG ?>doh_logo.png" width=90 height=90></img><br></td>
                          <td class="border-hide" align="center" width="800" colspan="<?php echo $hdr_cnt;?>">Republic of the Philippines<br>DEPARTMENT OF HEALTH</td>
                          <td class="border-hide" width="150">&nbsp;</td>
                      </tr>
                  </tbody>
              </table>
          </td>
        </tr>
        <tr>
          <td class="border-hide align-c" colspan="<?php echo $total_column;?>">&nbsp;</td>
        </tr>
        <tr>
          <td class="border-hide align-c f-size-16" colspan="<?php echo $total_column;?>"><b><?php echo $office_name['name'];?></b></td>
        </tr>
        <tr>
          <td class="border-hide align-c f-size-16" colspan="<?php echo $total_column;?>"><b><?php echo $compensation_name['compensation_name'];?> CY <?php echo $year;?></b></td>
        </tr>
        <tr>
        <td class="border-hide-l border-hide-r align-c" colspan="<?php echo $total_column;?>">&nbsp;</td>
      </tr>
        <tr>
          <th class="align-c f-size-12" width="20">No.</thth>
          <th class="align-c f-size-12" width="200">Name</th>
          <th class="align-c f-size-12" width="200">Designation</th>
          <th class="align-c f-size-12" width="20">SG</th>
          <?php foreach ($com_hdr as $c):?>
          <th class="align-c f-size-12" width="100"><?php echo $c['compensation_name']; ?></th>
            <?php if($c['compensation_code'] == $cna_code):?>
            <th class="align-c f-size-12" width="100">Agency Fee</th>
          <?php endif;?>
        <?php endforeach;?>
        
        <?php foreach ($ded_hdr as $d):?>
          <th class="align-c f-size-12" width="100"><?php echo $d['deduction_name']; ?></th>
        <?php endforeach;?>
        
          <th class="align-c f-size-12" width="100">NET PAY</th>
          <th class="align-c f-size-12" width="100">SIGNATURE</th>
          <th class="align-c f-size-12" width="100">INITIALS</th>
          <th class="align-c f-size-12" width="100">REMARKS</th>
        </tr>
      </thead>
      <tbody>
      <?php endif;?>
     <?php endif;?>
    <?php 
    endforeach;
    ?>
    <tr>
      <td class="bold f-size-16 align-r" colspan="4">Grand Total<?php echo nbs(5) . ":" . nbs(2);?></td>
      <!-- COMPENSATION TOTALS -->
     
      <?php foreach ($total_per_com as $index => $tpc):?>
      <td class="align-r pad-2 f-size-16"><?php echo number_format($tpc,2); ?></td>
       <?php if($cna_count > 0 AND $index == $cna_id):?>
          <td class="align-r pad-2 f-size-16"><?php echo number_format($ototal_cna_less,2); ?></td>
        <?php endif;?>
      <?php endforeach; ?>
      
      <!-- DEDUCTION TOTALS -->
      <?php foreach ($total_per_ded as $tpd):?>
      <td class="align-r pad-2 f-size-16"><?php echo number_format($tpd,2); ?></td>
      <?php endforeach;?>
      
      <td class="align-r pad-2 f-size-16"><?php echo number_format($net_grand,2); ?></td>
      <td class="align-c">---</td>
      <td class="align-c">---</td>
      <td class="align-c">---</td>
    </tr>
    </tbody>
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