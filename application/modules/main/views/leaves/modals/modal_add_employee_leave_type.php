<form id="employee_leave_type_form">	
	<input type="hidden" name="id" id = "id" value="<?php echo $id ?>"/>
	<input type="hidden" name="salt" value="<?php echo $salt ?>"/>
	<input type="hidden" name="token" value="<?php echo $token ?>"/>
	<input type="hidden" name="action" value="<?php echo $action ?>"/>
	<input type="hidden" name="module" value="<?php echo $module ?>"/>	
	<div class="form-float-label">
		<div class="row m-n">
			<div class="col s12">
			  <div class="input-field">
			  	<label for="leave_type" class="active">Leave Type <span class="required"> * </span></label>
				<select id="leave_type" name="leave_type" class="selectize" placeholder="Select Leave Type">
					<option value="">Select Leave Type</option>
					<?php if (!EMPTY($leave_types)): ?>
						<?php foreach ($leave_types as $type): ?>
							<option value="<?php echo $type['leave_type_id'] ?>"><?php echo $type['leave_type_name'] ?></option>
						<?php endforeach;?>
					<?php endif;?>
				</select>
		      </div>
		    </div>
		</div>	
		<div class="row m-n">
			<div class="col s6">
				<div class="input-field">
					<input id="leave_earned_used" name="leave_earned_used" type="text" required>
					<label for="leave_earned_used" >Number of Days <span class="required"> * </span></label>
				</div>
			</div>
			<div class="col s6">
				<div class="input-field">
					<input id="effective_date" name="effective_date" class="datepicker" type="text" required onkeypress="format_identifications('<?php echo DATE_FORMAT ?>',this.value,event,'effective_date')">
					<label for="effective_date" >Effective Date <span class="required"> * </span></label>
				</div>
			</div>
		</div>	
		<div class="row m-n">
			<div class="col s12">
				<div class="input-field">
				  	<label for="remarks">Remarks <span class="required"> * </span></label>
      				<textarea name="remarks" class="materialize-textarea" id="remarks" required></textarea>
				</div>
			 </div>	
		</div>
	</div>	
<div class="md-footer default">
	<a class="waves-effect waves-teal btn-flat cancel_modal" id="cancel_service_record">Cancel</a>
    <button class="btn btn-success  green" id="save_employee_leave" value="<?php echo BTN_SAVE ?>"><?php echo BTN_SAVE ?></button>
</div>
</form>
<script>
$(document).ready(function(){

	$('#employee_leave_type_form').parsley();
	
 	jQuery(document).off('submit', '#employee_leave_type_form');
	jQuery(document).on('submit', '#employee_leave_type_form', function(e){
	    e.preventDefault();
	    
		if ( $(this).parsley().isValid() ) {
			var data = $('#employee_leave_type_form').serialize();
		  	button_loader('save_employee_leave', 1);
		  	var option = {
					url  : $base_url + 'main/leaves/process_add_employee_leave',
					data : data,
					success : function(result){
						if(result.status)
						{
							notification_msg("<?php echo SUCCESS ?>", result.message);
							modal_add_employee_leave_type.closeModal();
							var post_data = {
											'employee_id':$('#id').val()
								};
							load_datatable('table_employee_leave_list', '<?php echo PROJECT_MAIN ?>/leaves/get_employee_leave_list',false,0,0,true,post_data);
						}
						else
						{
							notification_msg("<?php echo ERROR ?>", result.message);
						}	
						
					},
					
					complete : function(jqXHR){
						button_loader('save_employee_leave', 0);
					}
			};

			General.ajax(option);    
	    }
  	});
});
</script>