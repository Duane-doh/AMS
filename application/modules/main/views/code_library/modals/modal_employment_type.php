<form id="employment_type_form">
	<input type="hidden" name="id" id="id" value="<?php echo !EMPTY($id) ? $id : NULL?>">
	<input type="hidden" name="salt" id="salt" value="<?php echo !EMPTY($salt) ? $salt : NULL?>">
	<input type="hidden" name="token" id="token" value="<?php echo !EMPTY($token) ? $token : NULL?>">
	<input type="hidden" name="action" id="action" value="<?php echo !EMPTY($action) ? $action : NULL?>">
	<input type="hidden" name="module" id="module" value="<?php echo !EMPTY($module) ? $action : NULL?>">

	<div class="form-float-label">
		<div class='switch p-md'>
		    <label>
		        Inactive
		        <input name='active_flag' type='hidden'   value='N'>
		        <input name='active_flag' type='checkbox'   value='Y' <?php echo $action == ACTION_ADD ? 'checked' :'' ?> <?php echo ($active_flag['active_flag'] == "Y") ? "checked" : "" ?>> 
		        <span class='lever'></span>Active
		    </label>
		</div>
		<div class="row">
		  <div class="col s12">
			<div class="input-field">
				<input type="text" class="validate" required="" aria-required="true" name="employment_type_name" id="employment_type_name" value="<?php echo isset($employment_type_info['employment_type_name']) ? $employment_type_info['employment_type_name'] : NULL?>"/>
		   		<label class="<?php echo $action == ACTION_EDIT ? 'active' :'' ?>" for="employment_type_name">Employment Type Name<span class="required">*</span></label>
			</div>
		  </div>
		</div>
	</div>
</form>
<div class="md-footer default">
	<a class="waves-effect waves-teal btn-flat cancel_modal" id="cancel_employment_type">Cancel</a>
  <?php //if($this->permission->check_permission(MODULE_ROLE, ACTION_SAVE)):?>
	    <button class="btn btn-success " type="button" id="save_employment_type" value="<?php echo BTN_SAVE ?>"><?php echo BTN_SAVE ?></button>
  <?php //endif; ?>
</div>
<script>
$(function (){
	$('#save_employment_type').on('click', function(){
		$('#employment_type_form').trigger('submit');
	});

	$('#employment_type_form').parsley();
	$('#employment_type_form').submit(function(e) {
	    e.preventDefault();
	    
		if ( $(this).parsley().isValid() ) {
			var data = $(this).serialize();
		  	button_loader('save_employment_type', 1);
		  	var option = {
					url  : $base_url + 'main/code_library/process_employment_type',
					data : data,
					success : function(result){
						if(result.status)
						{
							notification_msg("<?php echo SUCCESS ?>", result.msg);
							$("#cancel_employment_type").trigger('click');
							load_datatable('employment_type_table', '<?php echo PROJECT_MAIN ?>/code_library/get_employment_type_list');
						}
						else
						{
							notification_msg("<?php echo ERROR ?>", result.msg);
						}	
						
					},
					
					complete : function(jqXHR){
						button_loader('save_employment_type', 0);
					}
			};

			General.ajax(option);    
	    }
  	});
})
</script>