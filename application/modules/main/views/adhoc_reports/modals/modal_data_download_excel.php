
<table width="100%" cellpadding="5" cellspacing="5">
	<thead>
		<tr>
			<th colspan="<?php echo count($fields); ?>"><?php echo $remarks ?></th>
		</tr>
	</thead>
</table>

<table width="100%" cellpadding="5" cellspacing="5" border="1">
	<thead style="background-color:#FFFFE0;">
		<tr>
			<?php
				foreach($fields AS $r)
				{
					echo '<th>' . $r . '</th>';
				}
			
			?>
		</tr>		
	</thead>
	
	<tbody>
	
	<?php
		if (! EMPTY ( $info )) {
			
			foreach ( $info as $r ) 
			{
				$row .= '<tr>';
				foreach ( $fields as $field ) {

					$x = (!EMPTY($r[$field])) ? strip_tags($r[$field]) : "";
					$row .= '<td>' .$x. '</td>';
					
				}
				$row .= '</tr>';
			}
		} 
		else 
		{
			$col = count ( $fields );
			$row .= '<tr><td colspan="' . $col . '">' . $this->lang->line ( 'no_records_found' ) . '</td></tr>';
		}
		print_r($row);

	
	?>
	
	</tbody>
</table>