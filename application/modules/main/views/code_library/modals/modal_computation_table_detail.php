<form id="computation_table_detail_form">
	<input type="hidden" name="id" id="id" value="<?php echo !EMPTY($id) ? $id : NULL?>">
	<input type="hidden" name="salt" id="salt" value="<?php echo !EMPTY($salt) ? $salt : NULL?>">
	<input type="hidden" name="token" id="token" value="<?php echo !EMPTY($token) ? $token : NULL?>">
	<input type="hidden" name="action" id="action" value="<?php echo !EMPTY($action) ? $action : NULL?>">
	<input type="hidden" name="module" id="module" value="<?php echo !EMPTY($module) ? $module : NULL?>">
	<input type="hidden" name="computation_table_id" id="computation_table_id"  value="<?php echo !EMPTY($comp_table_info['computation_table_id']) ? $comp_table_info['computation_table_id'] : NULL?>">
	<input type="hidden" name="computation_table_type_id" id="computation_table_type_id">
	<div class="form-float-label">
	<div class="row">
			<div class="col s6">
				<div class="input-field">
					<label class="<?php echo $action == ACTION_EDIT ? 'active' :'' ?>" for="start_date">Start Date<span class="required">*</span></label>
					<input type="text" class="datepicker_start" required name="start_date" id="start_date" value="<?php echo isset($comp_table_info['start_date']) ? $comp_table_info['start_date'] : NULL?>" <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?> onkeypress="format_identifications('<?php echo DATE_FORMAT ?>',this.value,event,'fltr_dtr_start')"/>
					</div>
			</div>

			<div class="col s6">
				<div class="input-field">
					<label class="<?php echo $action == ACTION_EDIT ? 'active' :'' ?>" for="end_date">End Date<span class="required"></span></label>
					<input type="text" class="datepicker_start" name="end_date" id="end_date" value="<?php if(isset($comp_table_info['end_date'])){if($actions != ACTION_ADD){ echo $comp_table_info['end_date'];}}?>" <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?> onkeypress="format_identifications('<?php echo DATE_FORMAT ?>',this.value,event,'fltr_dtr_start')"/>
				</div>
			</div>
		</div>
				<?php
				if(isset($comp_table_type))
					{
						echo '
						<div class="row">
							<div class="col">
								<div class="input-field">
								<label class="active" for="table_type">Computation Table Type<span class="required">*</span></label>
						';
						echo '<select name="table_type" class="selectize form-filter" id="table_type" ">';
						echo '<option></option>';
						foreach($comp_table_type as $key => $value)
						{
							echo '<option value="' . $value['computation_table_type_id'] . ','.$value['num_of_details_fields'].'">' . $value['type_name'] . '</option>';
						}	
						echo '</select>
							</div>
							</div>
							</div>
						';					
					}
                ?>
			
	<div class="row m-n p-t-md">
	    <div class="col s12">
	    	<div class="pre-datatable filter-left"></div>
	    	<diV>
			  <table cellpadding="0" cellspacing="0" class="table-default striped" id="table Computation">
			  <thead class="teal white-text" >
				<tr>
				  <th class="white-text" width="30%"><?php echo isset($type_name) ? $type_name : NULL ?> Equivalent</th>
				  <th class="white-text" width="70%">Point Equivalent</th>
				</tr>
			  </thead>
			  <tbody id="table_content_read">	
			  <?php echo $data_row;?>		  	
			  </tbody>
		  </table>
	    </div>
	    </div>
	  </div>
	</div>

	<div class="md-footer default">
	  	<?php if($action != ACTION_VIEW):?>
	  		<a class="waves-effect waves-teal btn-flat cancel_modal">Cancel</a>
		    <button class="btn btn-success ">SAVE</button>
	  		
	  	<?php endif; ?>
	</div>
</form>

<script>

$(function (){
	$('#computation_table_detail_form').parsley();
	$('#computation_table_detail_form').submit(function(e) {
	    e.preventDefault();
	    
		if ( $(this).parsley().isValid() ) {
			var data = $(this).serialize();
		  	button_loader('save_day', 1);
		  	var option = {
					url  : $base_url + 'main/code_library_ta/computation_table/process_details',
					data : data,
					success : function(result){
						if(result.status)
						{
							notification_msg("<?php echo SUCCESS ?>", result.msg);
							modal_computation_table_detail.closeModal();
							load_datatable('computation_table', '<?php echo PROJECT_MAIN ?>/code_library_ta/computation_table/get_computation_table_list',false,0,0,true);
							function hidepanel() {     
    							$('#computation_table_processing').hide();
 							}
 							setTimeout(hidepanel, 1000)
							}
						else
						{
							notification_msg("<?php echo ERROR ?>", result.msg);
						}	 
						
					},
					
					complete : function(jqXHR){
						button_loader('save_day', 0);
					}
			};

			General.ajax(option);    
	    }
  	});

})
$('#table_type').on('change', function() {  
	let text = this.value;
	const arr = text.split(",");
	$("#table_content_read").empty();
	var table_content_html = '';
	var comp_table_id = arr[0];
	$('#computation_table_type_id').val(comp_table_id);
	// document.getElementById("computation_table_id").innerHTML = myArray[0];
	for (let i = 1; i <= arr[1] ; i++) {
		if(comp_table_id == '5')
		{
			var n = i/2;
		}
		else
		{
			var n = i;
		}
		table_content_html += '<tr><td> '+n+'</td>';
		table_content_html += '<td><input class="number" name="values['+i+']" type="number" step=".001" style="text-align:center;" required> </td> ';
	}

	$("#table_content_read").html(table_content_html);
 
});
</script>