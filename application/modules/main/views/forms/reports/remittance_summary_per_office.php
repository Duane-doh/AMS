<!DOCTYPE html>
<html>
<?php if(!EMPTY($records)): ?>
<head>
  <title>Remittance Summary Grand Total</title>
  <link rel="stylesheet" href="<?php echo base_url().PATH_CSS ?>reports.css" type="text/css" />
</head>
<style type="text/css">
  table 
  {
    font-family: "Arial", Times, serif;
    font-size: 12px
  }
</style>
<body>
<table class="table-max f-size-12">
    <tbody>
        <tr>
            <td width="20%"><?php echo nbs(10) ?><img src="<?php echo base_url().PATH_IMG ?>doh_logo.png" width=90 height=90></img><br></td>
            <td align="center" width="60%">Republic of the Philippines<br>DEPARTMENT OF HEALTH<br>OFFICE OF THE SECRETARY</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
          <td colspan="3" height="50" valign="middle" align="center"><?php echo ($display_office_flag) ? $records[0]['office_name'] . '<br>' : ''; ?>
          <?php echo strtoupper($records[0]['remittance_period']); ?></td>
        </tr>
    </tbody>
</table>
<br>
<div>
<table class="table-max">
    <?php 
    if(!EMPTY($records)) {
      $total_remittance = 0;
      
      foreach($records AS $key => $r) {
        $share = 0;
        switch ($r['remit_type_flag']) {
            case REMIT_ALL:
                $share = $r['amount'] + $r['employer_amount'];
                 if($share > 0)
                  {
                     echo '<tr>'
                    . '<td height="10" width="300" class="td-left-bottom">' . strtoupper($r['deduction_name']) . ' (Employee Share)</td>'
                    . '<td class="td-center-bottom">--------------------------------------</td>'
                    // . '<td class="td-center-bottom">.............................................</td>'
                    . '<td class="td-right-bottom"> ' . number_format($r['amount'],2) . '</td>' 
                    . '</tr>';

                     echo '<tr>'
                    . '<td height="10" width="300" class="td-left-bottom">' . strtoupper($r['deduction_name']) . ' (Employer Share)</td>'
                    . '<td class="td-center-bottom">--------------------------------------</td>'
                    // . '<td class="td-center-bottom">.............................................</td>'
                    . '<td class="td-right-bottom"> ' . number_format($r['employer_amount'],2) . '</td>' 
                    . '</tr>';
                  }
                break;
             case REMIT_PERSONAL_SHARE:
               $share = $r['amount'];
                if($share > 0)
                {
                  echo '<tr>'
                  . '<td height="10" width="300" class="td-left-bottom">' . strtoupper($r['deduction_name']) . '</td>'
                  . '<td class="td-center-bottom">--------------------------------------</td>'
                  // . '<td class="td-center-bottom">.............................................</td>'
                  . '<td class="td-right-bottom"> ' . number_format($share,2) . '</td>' 
                  . '</tr>';
                }
                break;
            default:
                 $share = $r['employer_amount'];
                 if($share > 0)
                  {
                    echo '<tr>'
                    . '<td height="10" width="300" class="td-left-bottom">' . strtoupper($r['deduction_name']) . '</td>'
                    . '<td class="td-center-bottom">--------------------------------------</td>'
                    // . '<td class="td-center-bottom">.............................................</td>'
                    . '<td class="td-right-bottom"> ' . number_format($share,2) . '</td>' 
                    . '</tr>';
                  }
                break;
        }
        $total_remittance += $share;
      }
    }
    ?>
    <tr>
    	<td></td>
    	<td></td>
    	<td class="pad-top-10 td-border-thick-bottom td-border-thick-top"></td>
    </tr>
    <tr>
    	<td></td>
    	<td></td>
    	<td class="pad-top-10 td-border-thick-bottom"></td>
    </tr>
    <tr>
    	<td height="10" class="td-center-bottom"><b>GRAND TOTAL</b></td>
        <td class="td-center-bottom">--------------------------------------</td>
        <td class="td-right-bottom td-border-thick-bottom"><b>&#8369;  <?php echo number_format($total_remittance,2);?> </b></td>
    </tr>
</table>

</div>

<table class="table-max">
    <tr>
        <td colspan=2 height="100"></td>
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

<?php else :?>

<div class="wrapper">
	<form id="test_report">
		<p style="text-align: center">No data available.</p>
	</form>
</div>

<?php endif;?>
</html>

