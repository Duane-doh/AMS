<form id="mode_separation_form">
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
		        <input name='active_flag' type='checkbox'   value='Y' <?php echo $action == ACTION_ADD ? 'checked' :'' ?> <?php echo ($mode_separation_info['active_flag'] == "Y") ? "checked" : "" ?>> 
		        <span class='lever'></span>Active
		    </label>
		</div>
		<div class="row">
		  <div class="col s12">
			<div class="input-field">
				<input type="text" class="validate" required="" aria-required="true" name="mode_separation_name" id="mode_separation_name" value="<?php echo isset($mode_separation_info['mode_separation_name']) ? $mode_separation_info['mode_separation_name'] : NULL?>"/>
		   		<label class="<?php echo $action == ACTION_EDIT ? 'active' :'' ?>" for="mode_separation_name">Mode of Separation Name<span class="required">*</span></label>
			</div>
		  </div>
		</div>
	</div>
	<div class="md-footer default">
	<a class="waves-effect waves-teal btn-flat cancel_modal" id="cancel_mode_separation">Cancel</a>
  <?php //if($this->permission->check_permission(MODULE_ROLE, ACTION_SAVE)):?>
	    <button class="btn btn-success " type="submit" id="save_mode_separation" value="<?php echo BTN_SAVE ?>"><?php echo BTN_SAVE ?></button>
  <?php //endif; ?>
</div>
</form>

<script>
$(function (){

	$('#mode_separation_form').parsley();
	$('#mode_separation_form').submit(function(e) {
	    e.preventDefault();

		if ( $(this).parsley().isValid() ) {
			var data = $(this).serialize();
		  	button_loader('save_mode_separation', 1);
		  	var option = {
					url  : $base_url + 'main/code_library/process_mode_separation',
					data : data,
					success : function(result){
						if(result.status)
						{
							notification_msg("<?php echo SUCCESS ?>", result.msg);
							$("#cancel_mode_separation").trigger('click');
							load_datatable('mode_separation_table', '<?php echo PROJECT_MAIN ?>/code_library/get_mode_separation_list', false,0,0,true);
						}
						else
						{
							notification_msg("<?php echo ERROR ?>", result.msg);
						}	
						
					},
					
					complete : function(jqXHR){
						button_loader('save_mode_separation', 0);
					}
			};

			General.ajax(option);    
	    }
  	});
})
</script>