<!DOCTYPE html>
<html>
<?php if(count($results)>0):?>
<head>
	<title>Consolidated Remittance List per Office </title>
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
<table cellspacing="0">
<tr>
    <td height="10"></td>
</tr>
<!--   <tr>
    <td class="" width="70">Agency Name</td>
    <td class="">:&nbsp;<b>DEPARTMENT OF HEALTH</b></td>
  </tr> -->
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
  <thead>
    <tr>
      <th class="align-c" scope="col" width="50">Employee Number</th>
      <th class="align-c" scope="col" width="150">Employee Name</th>
      <th class="align-c" scope="col" width="150">Primary (Loan details, Policy Number)</th>
      <th class="align-c" scope="col" width="50">Deducted Amount</th>
      <th class="align-c" scope="col" width="50">Number of Payment</th>
      <th class="align-c" scope="col" width="50">Installment Number</th>
    </tr>
    <?php     
    $page_total = 0;
    $grand_total = 0;
    $display_per_page = 24;
    $display_count = 1;
    $share = 0;
    foreach ($results as $result) {
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
        $page_total   += $share;
        $grand_total  += $share;
    	?>
        <?php foreach ($other_ded_dtl_val AS $other_dtl): ?>
            <?php if($result['employee_id'] == $other_dtl['employee_id']): ?>
                <?php $other_detail_val = !EMPTY($other_dtl['other_deduction_detail_value']) ? $other_dtl['other_deduction_detail_value'] : ' '; ?>
            <?php endif;?>
        <?php endforeach;?>
		<tr>
			<td class="pad-2" width="15%"><?php echo $result['agency_employee_id']; ?></td>
			<td class="pad-2" width="30%"><?php echo $result['employee_name']; ?></td>
			<td class="pad-2" width="20%"><?php echo $other_detail_val; ?></td>
			<td class="pad-2 align-r" width="20%"><?php echo number_format($share,2); ?></td>
            <td class="pad-2 align-r" width=""><?php echo $result['payment_count']; ?></td>
            <td class="pad-2 align-r" width=""><?php echo $result['paid_count']; ?></td>
		</tr>
		<?php 
            $display_count ++;
            if($display_count == $display_per_page): ?>            
			<tr>
				<td class="pad-2 bold" colspan="3">Page Total</td>
				<td class="pad-2 align-r bold"><?php echo number_format($page_total,2); ?></td>
                <td class="pad-2 align-r"></td>
                <td class="pad-2 align-r"></td>
			</tr>
            <?php $display_count = 1;
            $page_total   = 0;?>
            </table>
            <pagebreak />
            <table cellspacing="0" class="table-max" border="1">
                <thead>
                    <tr>
                        <th class="align-c" scope="col" width="50">Employee Number</th>
					    <th class="align-c" scope="col" width="180">Employee Name</th>
					    <th class="align-c" scope="col" width="180">Primary (Loan details, Policy Number)</th>
					    <th class="align-c" scope="col" width="50">Deducted Amount</th>
					    <th class="align-c" scope="col" width="50">Number of Payment</th>
                        <th class="align-c" scope="col" width="50">Installment Number</th>
                    </tr>
                </thead>
        <?php endif;?>
	<?php } ?>
    <tr>
        <td class="pad-2 bold" colspan="3">Page Total</td>
        <td class="pad-2 align-r bold"><?php echo number_format($page_total,2); ?></td>
        <td class="pad-2 align-r"></td>
        <td class="pad-2 align-r"></td>
    </tr>
	<tr>
		<td class="pad-2 bold" colspan="3">Grand Total</td>
		<td class="pad-2 align-r bold"><?php echo number_format($grand_total,2); ?></td>
		<td class="pad-2 align-r"></td>
        <td class="pad-2 align-r"></td>
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