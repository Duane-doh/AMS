	<form id="form_employee_bank">
	<input type="hidden" name="id" value="<?php echo $id ?>"/>
	<input type="hidden" name="salt" value="<?php echo $salt ?>"/>
	<input type="hidden" name="token" value="<?php echo $token ?>"/>
	<input type="hidden" name="action" value="<?php echo $action ?>"/>
	<input type="hidden" id="module" name="module" value="<?php echo $module ?>"/>
	<input type="hidden" id="employee_id" name="employee_id" value="<?php echo $employee_id ?>"/>
	<input type="hidden" name="identification_type_id" value="<?php echo $identification_type['identification_type_id'] . '|' . $identification_type['format'] ?>"/>

	<div class="form-float-label">
		<div class="row m-n">
			<div class="col s6">
				<div class="input-field">
					<input id="identification_value" required name="identification_value" value="<?php echo isset($identification['identification_value'])? format_identifications($identification['identification_value'], $identification_type['format']) : '' ?>" type="text" class="validate" onkeypress="format_identifications('<?php echo $identification_type['format'] ?>',this.value,event,'identification_value')">
					<label for="identification_value" class="<?php echo $action==ACTION_EDIT ? 'active' : 'active' ?>">Account Number<span class="required">*</span></label>
				</div>
			</div>
			<div class="col s6">
				<div class="input-field">
					<label for="effective_date" class="<?php echo $action==ACTION_EDIT ? 'active' : 'active' ?>">Effectivity Date<span class="required">*</span></label>
					<input type="text" class="validate datepicker" required name="effective_date" id="effective_date" value="<?php echo isset($identification['start_date']) ? format_date($identification['start_date']) : NULL?>" <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?> onkeypress="format_identifications('<?php echo DATE_FORMAT ?>',this.value,event,'effective_date')"/>
				</div>
			</div>
		</div>
		<div class="row m-n">
			<div class="col s12">
				<div class="input-field" class="active">
					<textarea type="text" name="remarks" id="remarks" class="validate materialize-textarea" maxlength="255"><?php echo $identification['remarks']; ?></textarea>				
					<label for="remarks" class="<?php echo $action==ACTION_EDIT ? 'active' : 'active' ?>">Remarks<span class="required">*</span></label>
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
	
	<?php if($action == ACTION_ADD){ ?>
		$('#effective_date').datepicker('destroy');
		$('#effective_date').attr("autocomplete", "off");
	<?php } ?>
	
	<?php //if($action != ACTION_VIEW){ ?>
	// $('#identification_type_id').change(function(e) {
			// if(!first_seen)
			// $('#identification_value').val('');
			// first_seen = false;
			// var value = $(this).val();
			// var val_arr = value.split('|');
			/*
			 * IF STATEMENT WILL JUST FIX THE FORMAT IF THE EXPECTED 
			 * SEPARATOR OF AN IDENTIFICATION IS THE SAME 
			 * AS WHAT WE USED IN OUR CODE DELIMITER '|'
			 */
			// if(val_arr.length > 2) {
				// val_arr[1] += '|';
			// }
			// $('#identification_value').attr('onkeypress','format_identifications("'+val_arr[1]+'",this.value,event,"identification_value")');
		// });
	<?php //} ?>

	$('#form_employee_bank').parsley();
	jQuery(document).off('click', '#save_identification');
	jQuery(document).on('click', '#save_identification', function(e){	
		$("#form_employee_bank").trigger('submit');
	});

 	jQuery(document).off('submit', '#form_employee_bank');
	jQuery(document).on('submit', '#form_employee_bank', function(e){
	    e.preventDefault();
	    
		if ( $(this).parsley().isValid() ) {
			var data = $('#form_employee_bank').serialize();
			var process_url = "";
			<?php if($module == MODULE_HR_COMPENSATION):?>
				process_url = $base_url + 'main/Compensation/process_employee_bank_acc';
			// <?php else: ?>
				// process_url = $base_url + 'main/pds_identification_info/process';
			<?php endif; ?>
		  	button_loader('save_identification', 1);
		  	var option = {
					url  : process_url,
					data : data,
					success : function(result){
						
						if(result.status)
						{
							notification_msg("<?php echo SUCCESS ?>", result.msg);
							modal_employee_bank_acc.closeModal();
							var post_data = {
											'employee_id':$('#employee_id').val(),
											'module':$('#module').val()
								};
							load_datatable('table_employee_bank', '<?php echo PROJECT_MAIN ?>/compensation/get_employee_bank_acc',false,0,0,true,post_data)
						}
						else
						{
							notification_msg("<?php echo ERROR ?>", result.msg);
						}	
						
					},
					
					complete : function(jqXHR){
						button_loader('save_identification', 0);
					}
			};

			General.ajax(option);    
	    }
  	});
	
<?php if($action == ACTION_VIEW){ ?>
		$('label .required').addClass('none');
		$('.validate').attr('disabled','');
<?php } ?>

})
</script>