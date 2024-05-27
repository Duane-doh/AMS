<form id="change_details">
	<input type="hidden" id="id" value="<?php echo $id ?>"/>
	<input type="hidden" id="salt" value="<?php echo $salt ?>"/>
	<input type="hidden" id="token" value="<?php echo $token ?>"/>
	<input type="hidden" id="action" value="<?php echo $action ?>"/>
	<input type="hidden" id="module" value="<?php echo $module ?>"/>
	<input type="hidden" id="request_id" value="<?php echo $request_id ?>"/>
	<div class="panel row">
		<div class="row m-n m-t-md">
			<div class="col s12">
				<?php if($pds_flag):?>
				<table class="striped bordered table-default">
					<thead>
						<tr>
							<th width="40%">Field Name</th>
							<th width="30%">New Value</th>
							<th width="30%">Old Value</th>
						</tr>
					</thead>
					<tbody>
					<?php 						
						foreach($change_details as $value)
						{
							if($value)
							{
								echo "<tr>";
								echo "<td>".ucwords(str_replace("_"," ",$value['field_name']))."</td>";
								echo "<td>".$value['new_value']."</td>";
								echo "<td>".$value['old_value']."</td>";
								echo "</tr>";
							}
						}
					 ?>
					</tbody>
				</table>
				<?php else:?>
				<table class="striped bordered table-default">
					<thead>
						<tr>
							<th width="35%">Field Name</th>
							<th width="35%">Value</th>
						</tr>
					</thead>
					<tbody>
					<?php 
						
						foreach($change_details as $key => $value)
						{
							if(!EMPTY($value))
							{
								echo "<tr>";
								echo "<td>".ucwords(str_replace("_"," ",$key))."</td>";
								// echo "<td>".$value."</td>";
								
								//marvin
								echo "<td>".($value == 'TI' ? 'Time In' : ($value == 'BO' ? 'Break Out' : ($value == 'BI' ? 'Break In' : ($value == 'TO' ? 'Time Out' : $value))))."</td>";
								echo "</tr>";
							}
						}
					 ?>
					</tbody>
				</table>
				<?php endif;?>
			</div>
		</div>
	</div>
</form>