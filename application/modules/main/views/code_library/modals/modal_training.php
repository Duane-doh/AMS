<form id="training_form">
	<input type="hidden" name="id" id="id" value="<?php echo !EMPTY($id) ? $id : NULL?>">
	<input type="hidden" name="salt" id="salt" value="<?php echo !EMPTY($salt) ? $salt : NULL?>">
	<input type="hidden" name="token" id="token" value="<?php echo !EMPTY($token) ? $token : NULL?>">
	<input type="hidden" name="action" id="action" value="<?php echo !EMPTY($action) ? $action : NULL?>">
	<input type="hidden" name="module" id="module" value="<?php echo !EMPTY($module) ? $action : NULL?>">

	<div class="form-float-label">
		<div class="row">
		  <div class="col s12">
			<div class="input-field">
				<input type="text" class="validate" required="" aria-required="true" name="training_name" id="training_name" value="<?php echo isset($training_info['training_name']) ? $training_info['training_name'] : NULL?>"/>
		   		<label class="<?php echo $action == ACTION_EDIT ? 'active' :'' ?>" for="training_name">Training Name<span class="required">*</span></label>
			</div>
		  </div>
		</div>
		<div class='row switch p-md b-b-n'>
		    <label>
		        Inactive
		        <input name='active_flag' type='hidden'   value='N'>
		        <input name='active_flag' type='checkbox'   value='Y' <?php echo $action == ACTION_ADD ? 'checked' :'' ?> <?php echo ($training_info['active_flag'] == "Y") ? "checked" : "" ?>> 
		        <span class='lever'></span>Active
		    </label>
		</div>
	</div>
	<div class="md-footer default">
	<a class="waves-effect waves-teal btn-flat cancel_modal" id="cancel_training">Cancel</a>
  <?php //if($this->permission->check_permission(MODULE_ROLE, ACTION_SAVE)):?>
	    <button class="btn btn-success " type="submit" id="save_training" value="<?php echo BTN_SAVE ?>"><?php echo BTN_SAVE ?></button>
  <?php //endif; ?>
</div>
</form>

<script>
$(function (){

	$('#training_form').parsley();
	$('#training_form').submit(function(e) {
	    e.preventDefault();

		if ( $(this).parsley().isValid() ) {
			var data = $(this).serialize();
		  	button_loader('save_training', 1);
		  	var option = {
					url  : $base_url + 'main/code_library/process_training',
					data : data,
					success : function(result){
						if(result.status)
						{
							notification_msg("<?php echo SUCCESS ?>", result.msg);
							$("#cancel_training").trigger('click');
							load_datatable('training_table', '<?php echo PROJECT_MAIN ?>/code_library/get_training_list', false,0,0,true);
						}
						else
						{
							notification_msg("<?php echo ERROR ?>", result.msg);
						}	
						
					},
					
					complete : function(jqXHR){
						button_loader('save_training', 0);
					}
			};

			General.ajax(option);    
	    }
  	});
})
</script>