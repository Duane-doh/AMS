<!DOCTYPE html>
<html>
<head>
	<title>BIR Alphalist</title>
	<link rel="stylesheet" href="<?php echo base_url().PATH_CSS ?>reports.css" type="text/css" />
</head>
<body>
<table border="1">
<?php 
  $record = $records['D7.1'][0];
  if(!EMPTY($record)) {
    echo '<tr>
            <td class="bold">'.$record[1].'</td>
            <td class="bold">'.$record[2].'</td>
            <td class="bold">'.$record[3].'</td>
            <td class="bold">'.$record[4].'</td>
            <td class="bold" colspan=2>'.$record[count($record)-1].'</td>
            <td class="bold">0000</td>
            <td class="bold" colspan=2>'.$record[6].'</td>
          </tr>';

    $details1 = array();
    foreach ($records['D7.1'] as $key => $value) {

        echo '<tr>';
          foreach ($value as $key2 => $val) {
            if($key == 0 AND $key2 < 5) {
              $details1[$key2] = ($key2 == 0) ? 'C7.1' : $val;
            }
            if($key2 > 12 AND $key2 != 22 AND $key2 != 32) {
              $details1[$key2-8] += $val;
            }
            if($key2 == 5) {
              echo '<td>'.($key+1).'</td>';
            }
            echo '<td>'.($val=='-' ? '' : $val).'</td>';
          }

        echo '</tr>';

    } 
    echo '<tr>';
      foreach ($details1 as $value) {
        echo '<td class="bold">'.$value.'</td>';
      }
    echo '</tr>';
  } else {
    echo '<tr><td class="bold" colspan=10>No result for ALPHALIST OF EMPLOYEES TERMINATED BEFORE DECEMBER 31.</td></tr>';
  }
  

?>

</table>
<table border="1">
<?php 
  $record = $records['D7.2'][0];
  if(!EMPTY($record)) {
    echo '<tr>
            <td class="bold">'.$record[1].'</td>
            <td class="bold">'.$record[2].'</td>
            <td class="bold">'.$record[3].'</td>
            <td class="bold" colspan=2 >'.$record[4].'</td>
            <td class="bold">0000</td>
            <td class="bold" colspan=2>'.$record[6].'</td>
          </tr>';

    $details2 = array();
    foreach ($records['D7.2'] as $key => $value) {

        echo '<tr>';
          foreach ($value as $key2 => $val) {
            if($key == 0 AND $key2 < 5) {
              $details2[$key2] = ($key2 == 0) ? 'C7.2' : $val;
            }
            if($key2 > 10 AND $key2 != 19) {
              $details2[$key2-6] += $val;
            }
            if($key2 == 5) {
              echo '<td>'.($key+1).'</td>';
            }
            echo '<td>'.($val=='-' ? '' : $val).'</td>';
          }

        echo '</tr>';

    } 
    echo '<tr>';
      foreach ($details2 as $value) {
        echo '<td class="bold">'.$value.'</td>';
      }
    echo '</tr>';
  } else {
    echo '<tr><td class="bold" colspan=10>No result for ALPHALIST OF EMPLOYEES WHOSE COMPENSATION INCOME ARE EXEMPT FROM WITHHOLDING TAX BUT SUBJECT TO INCOME TAX.</td></tr>';
  }
  

?>
</table>
<table border="1">
<?php 
  $record = $records['D7.3'][0];
  if(!EMPTY($record)) {
    echo '<tr>
            <td class="bold">'.$record[1].'</td>
            <td class="bold">'.$record[2].'</td>
            <td class="bold">'.$record[3].'</td>
            <td class="bold" colspan=2>'.$record[4].'</td>
            <td class="bold">'.$record[count($record)-1].'</td>
            <td class="bold">0000</td>
            <td class="bold" colspan=2>'.$record[6].'</td>
          </tr>';
    
    $details3 = array();
    foreach ($records['D7.3'] as $key => $value) {

        echo '<tr>';
          foreach ($value as $key2 => $val) {
            if($key == 0 AND $key2 < 5) {
              $details3[$key2] = ($key2 == 0) ? 'C7.3' : $val;
            }
            if($key2 > 10 AND $key2 != 21 AND $key2 != 30) {
              $details3[$key2-6] += $val;
            }
            if($key2 == 5) {
              echo '<td>'.($key+1).'</td>';
            }
            echo '<td>'.($val=='-' ? '' : $val).'</td>';
          }

        echo '</tr>';

    } 
    echo '<tr>';
      foreach ($details3 as $value) {
        echo '<td class="bold">'.$value.'</td>';
      }
    echo '</tr>';
  } else {
    echo '<tr><td class="bold" colspan=10>No result for ALPHALIST OF EMPLOYEES AS OF DECEMBER 31 WITH NO PREVIOUS EMPLOYER WITHIN THE YEAR.</td></tr>';
  }
?>
</table>
<table border="1">
<?php 
  $record = $records['D7.4'][0];
  if(!EMPTY($record)) {
    echo '<tr>
            <td class="bold">'.$record[1].'</td>
            <td class="bold">'.$record[2].'</td>
            <td class="bold">'.$record[3].'</td>
            <td class="bold" colspan=2>'.$record[4].'</td>
            <td class="bold">'.$record[count($record)-1].'</td>
            <td class="bold">0000</td>
            <td class="bold" colspan=2>'.$record[6].'</td>
          </tr>';
    
    $details4 = array();
    foreach ($records['D7.4'] as $key => $value) {

        echo '<tr>';
          foreach ($value as $key2 => $val) {
            if($key == 0 AND $key2 < 5) {
              $details4[$key2] = ($key2 == 0) ? 'C7.4' : $val;
            }
            if($key2 > 10 AND $key2 != 21 AND $key2 != 30) {
              $details4[$key2-6] += $val;
            }
            if($key2 == 5) {
              echo '<td>'.($key+1).'</td>';
            }
            echo '<td>'.($val=='-' ? '' : $val).'</td>';
          }

        echo '</tr>';

    } 
    echo '<tr>';
      foreach ($details4  as $value) {
        echo '<td class="bold">'.$value.'</td>';
      }
    echo '</tr>';
  } else {
    echo '<tr><td class="bold" colspan=10>No result for ALPHALIST OF EMPLOYEES AS OF DECEMBER 31 WITH PREVIOUS EMPLOYER/S WITHIN THE YEAR.</td></tr>';
  }
?>
</table>
<br>
<div>


</div>
</body>
</html>