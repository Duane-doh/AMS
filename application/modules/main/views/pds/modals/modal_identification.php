	<form id="form_identification">
	<input type="hidden" name="id" value="<?php echo $id ?>"/>
	<input type="hidden" name="salt" value="<?php echo $salt ?>"/>
	<input type="hidden" name="token" value="<?php echo $token ?>"/>
	<input type="hidden" name="action" value="<?php echo $action ?>"/>
	<input type="hidden" name="module" value="<?php echo $module ?>"/>

	<div class="form-float-label">
		<div class="row m-n">
			<div class="col s12">
				<div class="input-field">
					<label for="identification_type_id" class="active">Identification Type<span class="required">*</span></label>
					<select id="identification_type_id" required name="identification_type_id" class="selectize" placeholder="Select Identification Type">
						<option value="">Select Identification Type</option>
						<?php if (!EMPTY($identification_types)): ?>
							<?php foreach ($identification_types as $type): ?>
									<option value="<?php echo $type['identification_type_id'] . '|' . $type['format'] ?>"><?php echo strtoupper($type['identification_type_name']) ?></option>
							<?php endforeach;?>
						<?php endif;?>
					</select>
				</div>
			</div>
		</div>
		<div class="row m-n">
			<div class="col s12">
				<div class="input-field">
					<input id="identification_value" required name="identification_value" value="<?php echo isset($identification['identification_value'])? format_identifications($identification['identification_value'], $identification['format']) : '' ?>" type="text" class="validate" onkeypress="format_identifications('<?php echo $identification['format'] ?>',this.value,event,'identification_value')">
					<label class="<?php echo $action_id==ACTION_EDIT ? 'active' : 'active' ?>" for="identification_value">Identification Number<span class="required">*</span></label>
				</div>
			</div>
		</div>
	</div>
</form>
<?php if($action != ACTION_VIEW):?>
<div class="md-footer default">
	<a class="waves-effect waves-teal btn-flat cancel_modal">Cancel</a>
    	<button class="btn btn-success " type="button" id="save_identification" value="<?php echo BTN_SAVE ?>"><?php echo BTN_SAVE ?></button>
</div>
<?php endif; ?>
<script>
$(function (){
	/*
	 * THE PURPOSE OF THE 'FIRST_SEEN' IS TO PREVENT
	 * THE JQUERY TO CLEAR THE VALUE OF IDENTIFICATION ON EDIT PROCESS
	 */
	var first_seen = true;

	$('#identification_type_id').change(function(e) {
			if(!first_seen)
			$('#identification_value').val('');
			first_seen = false;
			var value = $(this).val();
			var val_arr = value.split('|');
			/*
			 * IF STATEMENT WILL JUST FIX THE FORMAT IF THE EXPECTED 
			 * SEPARATOR OF AN IDENTIFICATION IS THE SAME 
			 * AS WHAT WE USED IN OUR CODE DELIMITER '|'
			 */
			if(val_arr.length > 2) {
				val_arr[1] += '|';
			}
			$('#identification_value').attr('onkeypress','format_identifications("'+val_arr[1]+'",this.value,event,"identification_value")');
		});

	$('#form_identification').parsley();
	jQuery(document).off('click', '#save_identification');
	jQuery(document).on('click', '#save_identification', function(e){	
		$("#form_identification").trigger('submit');
	});

 	jQuery(document).off('submit', '#form_identification');
	jQuery(document).on('submit', '#form_identification', function(e){
	    e.preventDefault();
	    
		if ( $(this).parsley().isValid() ) {
			var data = $('#form_identification').serialize();
			var process_url = "";
			<?php if($module == MODULE_PERSONNEL_PORTAL):?>
				process_url = $base_url + 'main/pds_record_changes_requests/process_identification';
			<?php else: ?>
				process_url = $base_url + 'main/pds_identification_info/process';
			<?php endif; ?>
		  	button_loader('save_identification', 1);
		  	var option = {
					url  : process_url,
					data : data,
					success : function(result){
						if(result.status)
						{
							notification_msg("<?php echo SUCCESS ?>", result.message);
							modal_identification.closeModal();
							load_datatable('identification_table', '<?php echo PROJECT_MAIN ?>/pds_identification_info/get_identification_list',false,0,0,true);
						}
						else
						{
							notification_msg("<?php echo ERROR ?>", result.message);
						}	
						
					},
					
					complete : function(jqXHR){
						button_loader('save_identification', 0);
					}
			};

			General.ajax(option);    
	    }
  	});
})
</script>