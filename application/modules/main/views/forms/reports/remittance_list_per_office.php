<!DOCTYPE html>
<html>
<head>
    <title>Remittance List per Office</title>
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
            <td align="center" width="60%">Republic of the Philippines<br>DEPARTMENT OF HEALTH</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
          <td colspan="3" height="40" valign="middle" align="center"><b><?php 
          $hdr = ($title_hdr[1]) ? $header : $title_hdr[0];
            echo 'REMITTANCE LIST OF ' .  strtoupper($hdr); ?><br>For the month of <?php echo $month_text . ", " . $year ;?></b></td>
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
    <td class="">:&nbsp;<b><?php echo strtoupper($records[0]['office_name']); ?></b></td>
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
<table cellspacing="0" class="table-max light-border" >
  <thead>
    <tr>
      <th class="align-c" width="25">No.</th>
      <th class="align-c" width="150">EMPLOYEE NAME</th>
      <th class="align-c" width="100">
      <?php
      $other_dtl_hdr = !EMPTY($other_ded_dtl_val[0]['other_detail_name']) ? ($other_ded_dtl_val[0]['other_detail_name']) : 'Policy Number'; 
      if($ecip)
      {
        $other_dtl_hdr = 'BP NO.';
        echo $other_dtl_hdr;
      }
      else
      {
        echo ($primary) ? strtoupper($other_dtl_hdr) : 'Position';
      }
       ?>
      </th>      
      <?php if($statutory OR $ecip): ?>
        <th class="align-c" width="70">Basic Salary</th>
          <th class="align-c" width="150">Position</th>
      <?php endif;?>
      <?php if(!$ecip): ?>
      <th class="align-c" width="70"><?php echo ($show_employer_share) ? wordwrap('Employee Contribution',10,'<br/>') : wordwrap('Amount Remitted',10,'<br/>')?></th>
       <?php endif;?>
      <?php if($show_employer_share): ?>
      <th class="align-c" width="70"><?php echo wordwrap('Government Contribution',10,'<br/>')?></th>
            <?php if(!$ecip): ?>
                <th class="align-c" width="70"><?php echo wordwrap('TOTAL',10,'<br/>')?></th>
            <?php endif;?>      
      <?php endif;?>      
       <?php if($show_installment_flag): ?>
      <th class="align-c" width="70"><?php echo wordwrap('Number of Payment',10,'<br/>')?></th>
      <th class="align-c" width="70"><?php echo wordwrap('Installment Number',10,'<br/>')?></th>
      <?php endif;?>
    </tr>
  </thead>
<?php if($records): ?>
    <?php 
    $total_basic_salary        = 0;
    $total_employee_share      = 0;
    $total_employer_share      = 0;
    $page_total_basic_salary   = 0;
    $page_total_employee_share = 0;
    $page_total_employer_share = 0;
    $sub_total                 = 0;
    $total                     = 0;
    $page_total                = 0;

    $count                     = 1;
    $display_per_page          = 28;
    $display_second_page       = 38;
    $display_count             = 0;
    foreach ($records as $record): ?>
        <?php     
            $display_count ++;
            $total_basic_salary        += $record['employ_monthly_salary'];
            $total_employee_share      += $record['amount'];
            $total_employer_share      += $record['employer_amount'];
            $page_total_basic_salary   += $record['employ_monthly_salary'];
            $page_total_employee_share += $record['amount'];
            $page_total_employer_share += $record['employer_amount'];

            $sub_total = $record['employer_amount'] + $record['amount'];
            $page_total += $sub_total;
            $total += $sub_total;
        ?>  
        <?php foreach ($other_ded_dtl_val AS $other_dtl): ?>
            <?php if($record['employee_id'] == $other_dtl['employee_id']): ?>
                <?php $other_detail_val = !EMPTY($other_dtl['other_deduction_detail_value']) ? $other_dtl['other_deduction_detail_value'] : '-'; ?>
            <?php endif;?>
        <?php endforeach;?>
            <tr>
                <td class="pad-2 align-r"><?php echo $count ?></td>
                <td class="pad-2"><?php echo $record['employee_name'] ?></td>
                <td class="pad-2"><?php 
                    $dtl = !EMPTY($other_detail_val) ? $other_detail_val : $record['reference_text'];
                    if($ecip)
                      {
                        echo $dtl;
                      }
                      else
                      {
                        echo ($primary) ? $dtl : $record['position_name'];
                      }
               
                 ?></td>
                <?php if($statutory OR $ecip): ?>
                    <td class="pad-2 align-r">
                    <?php echo number_format($record['employ_monthly_salary'],2) ?>
                   </td> 
                    <td class="pad-2">
                    <?php echo $record['position_name'] ?>
                    </td>
                <?php endif;?>
                <?php if(!$ecip): ?>
                    <td class="pad-2 align-r"><?php echo number_format($record['amount'],2) ?></td>
                <?php endif;?>
                <?php if($show_employer_share): ?>
                    <td class="pad-2 align-r">
                    <?php echo number_format($record['employer_amount'],2) ?>
                    </td>
                    <?php if(!$ecip): ?>
                        <td class="pad-2 align-r">
                        <?php echo number_format($sub_total,2) ?>
                        </td>
                    <?php endif;?>
                <?php endif;?>

                <?php if($show_installment_flag): ?>
                <td class="pad-2 align-c"><?php echo $record['payment_count'] ?></td>
                <td class="pad-2 align-c"><?php echo $record['paid_count'] ?></td>
                <?php endif; ?>
            </tr> 

        <?php 
            
            if($display_count == $display_per_page AND $format === 'pdf'): ?>
            <tr>
                <td width="20" class="td-border-thick-bottom"></td>
                <td class="align-r fl-right pad-left-50 td-border-thick-bottom" width="150"><b>Page Total :</b></td>
                <td width="100" class="td-border-thick-bottom">----------------->  </td>
                <?php if($statutory OR $ecip): ?>
                    <td class="td-border-thick-bottom align-r bold" width="70"><?php echo number_format($page_total_basic_salary,2) ?></td>
                    <td class="td-border-thick-bottom"></td>
                <?php endif;?>
                <?php if(!$ecip): ?>
                    <td class="pad-2 align-r td-border-thick-bottom" width="70"><b><?php echo number_format($page_total_employee_share,2) ?></b></td>
                <?php endif;?>
                <?php if($show_employer_share): ?>
                    <td class="pad-2 align-r td-border-thick-bottom" width="70"><b>
                    <?php echo number_format($page_total_employer_share,2) ?>
                    </b></td>
                    <?php if(!$ecip): ?>
                        <td class="pad-2 align-r td-border-thick-bottom" width="70"><b>
                            <?php echo number_format($page_total,2) ?>
                            </b></td>
                    <?php endif;?>
                <?php endif;?>
                <?php if($show_installment_flag): ?>
                <td class="td-border-thick-bottom"></td>
                <td class="td-border-thick-bottom"></td>
                <?php endif;?>
            </tr>
            <?php 
                $display_count             = 0;
                $display_per_page          = $display_second_page;
                $page_total_basic_salary   = 0;
                $page_total_employee_share = 0;
                $page_total_employer_share = 0;
                $page_total                = 0;
            ?>
            </table>
            <pagebreak />
            <table cellspacing="0" class="table-max light-border">
                <thead>
                    <tr>
                        <th class="align-c" width="25">No.</th>
                        <th class="align-c" width="150">Employee Name</th>
                        <th class="align-c" width="100">
                        <?php echo ($primary) ? strtoupper($other_ded_dtl_val[0]['other_detail_name']) : 'Position' ?>
                        </th>
                          <?php if($statutory OR $ecip): ?>
                            <th class="align-c" width="70">Basic Salary</th>
                            <th class="align-c" width="150">Position</th>
                          <?php endif;?>
                          <?php if(!$ecip): ?>
                                <th class="align-c" width="70"><?php echo ($show_employer_share) ? wordwrap('Employee Contribution',10,'<br/>') : wordwrap('Amount Remitted',10,'<br/>')?></th>
                            <?php endif;?>
                          <?php if($show_employer_share): ?>
                            <th class="align-c" width="70"><?php echo wordwrap('Government Contribution',10,'<br/>')?></th>
                            <?php if(!$ecip): ?>
                                <th class="align-c" width="70"><?php echo wordwrap('TOTAL',10,'<br/>')?></th>
                            <?php endif;?>
                          <?php endif;?>                          
                        <?php if($show_installment_flag): ?>
                        <th class="align-c" width="70"><?php echo wordwrap('Number of Payment',10,'<br/>')?></th>
                        <th class="align-c" width="70"><?php echo wordwrap('Installment Number',10,'<br/>')?></th>
                        <?php endif;?>
                        
                    </tr>
                </thead>
        <?php endif;?>

    <?php 
        $count++;
        endforeach;?>
<?php endif;?>
<?php if($format === 'pdf') : ?>
    <tr>
        <td width="20" class="td-border-thick-bottom"></td>
        <td class="align-r fl-right pad-left-50 td-border-thick-bottom" width="150"><b>Page Total :</b></td>
        <td width="100" class="td-border-thick-bottom">----------------->  </td>
        <?php if($statutory OR $ecip): ?>
            <td width="150" class="td-border-thick-bottom align-r bold" width="70"><?php echo number_format($page_total_basic_salary,2) ?></td>
            <td width="150" class="td-border-thick-bottom"></td>
        <?php endif;?>
        <?php if(!$ecip): ?>
            <td class="pad-2 align-r td-border-thick-bottom" width="70"><b><?php echo number_format($page_total_employee_share,2) ?></b></td>
        <?php endif;?>
        <?php if($show_employer_share): ?>
            <td class="pad-2 align-r td-border-thick-bottom" width="70"><b>
            <?php echo number_format($page_total_employer_share,2) ?>
            </b></td>
            <?php if(!$ecip): ?>
                 <td class="pad-2 align-r td-border-thick-bottom" width="70"><b>
                    <?php echo number_format($page_total,2) ?>
                    </b></td>
            <?php endif;?>
        <?php endif;?>

        <?php if($show_installment_flag): ?>
        <td class="td-border-thick-bottom"></td>
        <td class="td-border-thick-bottom"></td>
        <?php endif;?>
    </tr>
<?php endif; ?>
    <tr>
        <td width="20" class="td-border-thick-bottom"></td>
        <td class="align-r fl-right pad-left-50 td-border-thick-bottom" width="150"><b>Grand Total :</b></td>
        <td width="100" class="td-border-thick-bottom">----------------->  </td>
        <?php if($statutory OR $ecip): ?>
            <td width="150" class="td-border-thick-bottom align-r bold" width="70"><?php echo number_format($total_basic_salary,2) ?></td>
            <td width="150" class="td-border-thick-bottom"></td>
        <?php endif;?>
        <?php if(!$ecip): ?>
            <td class="pad-2 align-r td-border-thick-bottom" width="70"><b><?php echo number_format($total_employee_share,2) ?></b></td>
        <?php endif;?>
        <?php if($show_employer_share): ?>
            <td class="pad-2 align-r td-border-thick-bottom" width="70"><b><?php echo number_format($total_employer_share,2) ?></b></td>
            <?php if(!$ecip): ?>
                <td class="pad-2 align-r td-border-thick-bottom" width="70"><b><?php echo number_format($total,2) ?></b></td>
            <?php endif;?>
        <?php endif;?>
        <?php if($show_installment_flag): ?>
        <td class="td-border-thick-bottom"></td>
        <td class="td-border-thick-bottom"></td>
        <?php endif;?>
    </tr>
</table>

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
        <td height="20" align="center" valign="middle"><?php echo $prepared_by['position_name'] ?><br><?php echo $prepared_by['office_name'] ?></td>
        <td height="20" align="center" valign="middle"><?php echo $certified_by['position_name'] ?><br><?php echo $certified_by['office_name']?></td>
    </tr>
</table>

</body>
</html>
