<html>
<head>

  <title>General Payroll Summary per Office</title>
  <link rel="stylesheet" type="text/css" href="<?php echo base_url().PATH_CSS ?>reports.css" type="text/css"/>
</head>
<style type="text/css">
  table 
  {
    font-family: "Arial", Times, serif;
  }
</style>
<body>
<table class="table-max f-size-12">
    <tbody>
        <tr>
            <td width="20%"><?php echo nbs(10) ?><img src="<?php echo base_url().PATH_IMG ?>doh_logo.png" width=90 height=90></img><br></td>
            <td align="center" width="60%">Republic of the Philippines<br>DEPARTMENT OF HEALTH</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
          <td colspan="3" class="bold align-c">GENERAL PAYROLL<br><?php echo $office_name; ?><br>Summary Sheet<br>For the month of <?php echo $payroll_date_text;?></td>
        </tr>
    </tbody>
</table>
<table class="f-size-12">
  <tr>
    <td colspan="4" height="10" class="td-right-bottom" style="padding-top:40px;"></td>
    <td colspan="4" width="10" class="td-left-bottom"></td>
    <td colspan="4" class="td-right-bottom" ></td>
   <!--  <td colspan="2" rowspan="17" align="right" valign="middle"><b>NET PAY FOR :</b></td> -->
    <td colspan="4" class="td-left-bottom"></td>
  </tr>
</table>
<table class="table-max f-size-13">
  <?php $total_compensation_amount = 0; ?>
  <?php foreach ($compensation_records as $key => $c_record): ?>
   <?php $total_compensation_amount += $c_record['amount'];?>
   <?php if($display_half):?>
  <tr>
    <td colspan="4" width="230" height="15" class="td-right-bottom"><?php echo $c_record['compensation_name']; ?> </td>
    <td colspan="1" height="15" class="td-left-bottom" width="40">: <?php echo ($key == 0) ? '&nbsp;&nbsp;&nbsp;&#8369;&nbsp;&nbsp;&nbsp;' : '' ?> </td>
    <td colspan="2" height="15" class="td-right-bottom"><?php echo number_format($c_record['amount'],2); ?></td>
    <td colspan="9" height="15" class="td-left-bottom">&nbsp;</td>
  </tr>
  <?php else:?>
    <tr>
      <td colspan="4" width="230" height="15" class="td-right-bottom"><?php echo $c_record['compensation_name']; ?> </td>
      <td colspan="1" height="15" class="td-left-bottom" width="40">: <?php echo ($key == 0) ? '&nbsp;&nbsp;&nbsp;&#8369;&nbsp;&nbsp;&nbsp;' : '' ?> </td>
      <td colspan="2" height="15" class="td-right-bottom"><?php echo ($c_record['less_absence_flag'] == YES) ? number_format($c_record['orig_amount'],2) : number_format($c_record['amount'],2); ?></td>
      <td colspan="9" height="15" class="td-left-bottom">&nbsp;</td>
    </tr>
    <?php if($c_record['less_absence_flag'] == YES):?>
        <tr>
          <td colspan="4" width="230" height="15" class="td-right-bottom">Less:(Absences/Tardiness)</td>
          <td colspan="1" height="15" class="td-left-bottom" width="40"><?php echo ($key == 0) ? '&nbsp;&nbsp;&nbsp;&#8369;&nbsp;&nbsp;&nbsp;' : '' ?> </td>
          <td colspan="2" height="15" class="td-right-bottom"><?php echo number_format($c_record['less_amount'],2); ?></td>
          <td colspan="9" height="15" class="td-left-bottom">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="4" height="10" class="td-right-bottom"></td>
          <td colspan="1" style="border-bottom: 2px solid #000000" class="td-left-bottom"></td>
          <td colspan="2" style="border-bottom: 2px solid #000000" class="td-left-bottom"></td>
          <td colspan="9" height="10" class="td-left-bottom">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="4" width="230" height="15" class="td-right-bottom">Gross Salary for the period</td>
          <td colspan="1" height="15" class="td-left-bottom" width="40">:<?php echo ($key == 0) ? '&nbsp;&nbsp;&nbsp;&#8369;&nbsp;&nbsp;&nbsp;' : '' ?> </td>
          <td colspan="2" height="15" class="td-right-bottom"><?php echo number_format($c_record['amount'],2); ?></td>
          <td colspan="9" height="15" class="td-left-bottom">&nbsp;</td>
        </tr>
    <?php endif;?>
  <?php endif;?>
  <?php  endforeach;?> 
  <tr>
    <td colspan="4" height="10" class="td-right-bottom"></td>
    <td colspan="1" style="border-bottom: 3px solid #000000" class="td-left-bottom"></td>
    <td colspan="2" style="border-bottom: 3px solid #000000" class="td-left-bottom"></td>
    <td colspan="9" height="15" class="td-left-bottom">&nbsp;</td>
  </tr>

  <tr>
    <td colspan="4" height="35" class="td-right-middle"><b><?php echo ($display_half) ? 'GROSS':'' ?></b></td>
    <td colspan="1" align="left" valign="middle"><b><?php echo ($display_half) ? ':':'' ?><?php echo nbs(3);?></b></td>
    <td colspan="2" align="right" valign="middle" width="150"><b>&#8369;</b><?php echo nbs(3).number_format($total_compensation_amount, 2);?></td>
    <td colspan="9" height="15" class="td-left-bottom">&nbsp;</td>
  </tr>

  <tr>
    <td colspan="4" height="28" class="td-right-middle"><b>( - ) DEDUCTIONS</b></td>
    <td colspan="1" class="td-center-middle">:</td>
    <td colspan="2" class="td-right-bottom" >&nbsp;</td>
    <td colspan="2" height="15" class="td-left-bottom">&nbsp;&nbsp;</td>
    <td colspan="1" height="15" class="td-left-bottom">&nbsp;</td>
    <td colspan="1" height="15" class="td-left-bottom">&nbsp;</td>
    <td colspan="1" height="15" class="td-left-bottom">&nbsp;</td>
    <td colspan="1" height="15" class="td-left-bottom">&nbsp;</td>
    <td colspan="1" height="15" class="td-left-bottom">&nbsp;</td>
    <td colspan="1" height="15" class="td-left-bottom">&nbsp;</td>
	<td colspan="1" height="15" class="td-left-bottom">&nbsp;</td>
  </tr>
  
   <?php 
   		$total_deduction_amount = 0;
		for($ctr=0;$ctr<count($deduction_records[2]);$ctr++):
		$ded_1 = ISSET($deduction_records[1][$ctr]['amount'])? $deduction_records[1][$ctr]['amount']:0;
		$ded_2 = ISSET($deduction_records[2][$ctr]['amount'])? $deduction_records[2][$ctr]['amount']:0;
		
   		$total_deduction_amount += $ded_1 + $ded_2;
   ?>
      <tr height="20">
	      <td colspan="4" height="10" class="td-right-bottom"><?php echo !EMPTY($deduction_records[1][$ctr]['deduction_name']) ? $deduction_records[1][$ctr]['deduction_name'] : "&nbsp;";?></td>
	      <td colspan="1" class="td-left-bottom"><?php echo !EMPTY($deduction_records[1][$ctr]['deduction_name']) ? ':' : '';?></td>
	      <td colspan="2" class="td-right-bottom"><?php echo !EMPTY($deduction_records[1][$ctr]['deduction_name']) ? number_format($deduction_records[1][$ctr]['amount'], 2) : "";?></td>
	      <td colspan="1" class="td-left-bottom" colspan="2">&nbsp;</td>
	      <td colspan="4" height="10" class="td-right-bottom" width="180px"><?php echo !EMPTY($deduction_records[2][$ctr]['deduction_name']) ? $deduction_records[2][$ctr]['deduction_name'] : '';?></td>
	      <td class="td-left-bottom"><?php echo  !EMPTY($deduction_records[2][$ctr]['deduction_name']) ? ':': '' ?></td>
	      <td colspan="2" class="td-right-bottom"><?php echo !EMPTY($deduction_records[2][$ctr]['deduction_name']) ?  number_format($deduction_records[2][$ctr]['amount'],2) : ''; ?></td>
      </tr>
 <?php endfor; ?>

  <tr>
    <td colspan="16" class="td-left-bottom">&nbsp;</td>
  </tr>
  
    
  <tr>
    <td colspan="8" height="35" class="td-right-bottom"></td>
    <td colspan="5" align="right" valign="middle" height="35"><b>Total Deductions(-):</b></td>
    <td colspan="3" align="right" valign="middle"><b>&#8369;</b><?php echo nbs(3) . number_format($total_deduction_amount, 2);?></td>
  </tr>
   <tr>
    <td colspan="8" height="25" class="td-right-bottom"></td>
    <td colspan="5" align="right" valign="middle"><b>Total NET:</b></td>
    <?php $total_net = $total_compensation_amount - $total_deduction_amount; ?>
    <td colspan="3" align="right" valign="middle" width="150"><b>&#8369;</b><?php echo nbs(3) . number_format($total_net, 2);?></td>
  </tr>
  
  <tr>
    <td colspan="16">&nbsp;</td>
  </tr>
  
  <tr>
  	<td colspan="16">&nbsp;</td>
  </tr>
  
   <?php 
   if($display_half)
   {
      foreach ($net_pay_records as $key => $np) {
        echo '<tr>
                <td colspan="8" class="td-right-bottom"></td>
                <td colspan="5" align="right" valign="middle"><b>'. ($key == 0 ? 'NET PAY FOR' . ':' : '') .'</b></td>
                <td colspan="3" align="right" valign="middle">Half '.($key+1).'&nbsp;:&nbsp;&nbsp;&#8369; ' . number_format($np['net_pays'], 2) . '</td>
              </tr>';
      }
  }
  ?>
 </table>
<!-- ************************************************************************** -->
</body>

</html>
