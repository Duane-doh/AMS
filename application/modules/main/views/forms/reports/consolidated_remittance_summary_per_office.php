<!DOCTYPE html>
<html>
<?php if(count($results)>0):?>
<head>
  <title>Consolidated Remittance Summary per Office</title>
  <link rel="stylesheet" href="<?php echo base_url().PATH_CSS ?>reports.css" type="text/css" />
</head>
<body>
<table class="table-max f-size-12">
    <tbody>
        <tr>
            <td width="20%"><?php echo nbs(10) ?><img src="<?php echo base_url().PATH_IMG ?>doh_logo.png" width=90 height=90></img><br></td>
            <td align="center" width="60%">Republic of the Philippines<br>DEPARTMENT OF HEALTH</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
          <td colspan="3" height="40" valign="top" align="center"><b><?php echo strtoupper($remittance_type_name); ?>  <br>FOR THE MONTH OF <?php echo strtoupper($month_text . ", " . $year) ;?></b></td>
        </tr>
    </tbody>
</table>
<div>
<table cellspacing="0">
<tr>
    <td height="10"></td>
</tr>
  <?php if($office_list):?>
  <tr>
    <td class="">Office Name</td>
    <td class="">:&nbsp;<b><?php echo strtoupper($office_name); ?></b></td>
  </tr>
<?php endif;?>
  <tr>
    <td class="">Office Address</td>
    <td class="">:&nbsp;SAN LAZARO COMP. MANILA</td>
  </tr>
  <tr>
    <td class="">Office Tel. No.</td>
    <td class="">:</td>
  </tr>
</table>
<table class="table-max" border="1">
  <tbody>
    <tr>
      <th class="align-c" scope="col" height="15" width="60">Employee Number</th>
      <th class="align-c" scope="col" >Employee Name</th>
      <th class="align-c" scope="col" width="70"><?php echo $other_dtl_hdr = !EMPTY($other_ded_dtl_val[0]['other_detail_name']) ? ($other_ded_dtl_val[0]['other_detail_name']) : 'Policy Number';?></th>
      <?php 
      if(!EMPTY($columns)) :
    foreach($columns AS $hdr)
        {
      ?>
          <th class="align-c" scope="col" width="70"><?php echo $hdr['report_short_code'];?></th>
      <?php
        }
      endif;
      ?>      
      <?php if(count($columns) > 1): ?>
        <th class="align-c" width="70" scope="col">Sub Total</th>
      <?php endif;?>
        <th class="align-c" width="50" scope="col">Remarks</th>
    </tr>
    <?php 
    $display_per_page = 29;
    $display_count = 1;
    foreach ($results as $res):
      $sub_total = 0;
      ?>
    <tr>
      <?php 
        $ctr=0;
        foreach ($res as $key => $r):
          if($ctr<3):
      ?>
          <td><?php echo $r;?></td>
      <?php 
          else:
            $amount[$key] += $r;
            $amount_per_page[$key] += $r;
            $sub_amount_per_page += $r;
            $sub_total += $r;
            $sub_grand_total += $r;
      ?>
          <td class="td-right-bottom" height="15"><?php echo number_format($r, 2)?></td>
         
      <?php 
          endif;
          $ctr++;
        endforeach;
      ?>
      <?php if(count($columns) > 1): ?>
        <td class=" align-r"><?php echo number_format($sub_total, 2)?></td>
      <?php endif;?>      
        <td class=" align-r">&nbsp;</td>
       <?php 

            $display_count ++;
            if($display_count == $display_per_page): ?>

              </tr>
              <tr>
                <td class="bold pad-2" colspan="3" height="15">Page Total</td>
                <?php foreach($amount_per_page as $val):?>
                    <td class="bold pad-2 align-r" ><?php echo number_format($val, 2);?></td>
                <?php endforeach;?>
                <?php if(count($columns) > 1): ?>
                  <td class="bold pad-2 align-r" ><?php echo number_format($sub_amount_per_page, 2);?></td>
                <?php endif;?>
                 <td class=" align-r">&nbsp;</td>
              </tr>
            <?php $display_count = 1;
                  $amount_per_page = array();
                  $sub_amount_per_page = 0; ?>
            </table>
            <pagebreak />
            <table cellspacing="0" class="table-max" border="1">
                <thead>
                   <tr>
                      <th class="align-c" scope="col" height="15" width="60">Employee Number</th>
                      <th class="align-c" scope="col">Employee Name</th>
                      <th class="align-c" scope="col" width="70"><?php echo $other_dtl_hdr = !EMPTY($other_ded_dtl_val[0]['other_detail_name']) ? ($other_ded_dtl_val[0]['other_detail_name']) : 'Policy Number';?></th>
                      <?php 
                      if(!EMPTY($columns)) :
                    foreach($columns AS $hdr)
                        {
                      ?>
                          <th class="align-c" scope="col" height="15" width="70"><?php echo $hdr['report_short_code'];?></th>
                      <?php
                        }
                      endif;
                      ?>
                      <?php if(count($columns) > 1): ?>
                        <th class="align-c" scope="col" width="70">Sub Total</th>
                      <?php endif;?>
                      <th class="align-c" width="50" scope="col">Remarks</th>
                    </tr>
                </thead>
        <?php endif;?>
    <?php endforeach;?>
    <tr>
      <td class="bold pad-2" colspan="3" height="15">Page Total</td>
      <?php foreach($amount_per_page as $val):?>
          <td class="bold pad-2 align-r" ><?php echo number_format($val, 2);?></td>
      <?php endforeach;?>
      <?php if(count($columns) > 1): ?>
        <td class="bold pad-2 align-r" ><?php echo number_format($sub_amount_per_page, 2);?></td>
      <?php endif;?>
       <td class=" align-r">&nbsp;</td>
    </tr>
    <tr>
      <td class="bold pad-2" colspan="3" height="15">Grand Total</td>
      <?php foreach($amount as $val):?>
      <td class="bold pad-2 align-r" ><?php echo number_format($val, 2);?></td>
    <?php endforeach;?>
    <?php if(count($columns) > 1): ?>
      <td class="bold pad-2 align-r" ><?php echo number_format($sub_grand_total, 2);?></td>
      <?php endif;?>
     <td class=" align-r">&nbsp;</td>
    </tr>
  </tbody>
</table>

</div>
<table class="table-max">
    <tr>
        <td colspan=2 height="40"></td>
    </tr>
    <tr>
        <td height="20" width="50%" align="left" valign="middle"><?php echo nbs(15)?>Prepared by:</td>
        <td height="20" width="50%" align="left" valign="middle"><?php echo nbs(15)?>Certified correct:</td>
    </tr>   
    <tr>
        <td colspan=2 height="40"></td>
    </tr>
    <tr>
        <td height="20" align="center" valign="middle"><b><?php echo $prepared_by['signatory_name']; ?></b></td>
        <td height="20" align="center" valign="middle"><b><?php echo $certified_by['signatory_name']; ?></b></td>
    </tr>
    <tr>  
        <td height="20" align="center" valign="middle"><?php echo $prepared_by['position_name'] ?><br><?php echo $prepared_by['office_name']?></td>
        <td height="20" align="center" valign="middle"><?php echo $certified_by['position_name'] ?><br><?php echo $certified_by['office_name']?></td>
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