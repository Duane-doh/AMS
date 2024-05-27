<form id="replacement_reason_form">
	<input type="hidden" name="id" id="id" value="<?php echo !EMPTY($id) ? $id : NULL?>">
	<input type="hidden" name="salt" id="salt" value="<?php echo !EMPTY($salt) ? $salt : NULL?>">
	<input type="hidden" name="token" id="token" value="<?php echo !EMPTY($token) ? $token : NULL?>">
	<input type="hidden" name="action" id="action" value="<?php echo !EMPTY($action) ? $action : NULL?>">
	<input type="hidden" name="module" id="module" value="<?php echo !EMPTY($module) ? $action : NULL?>">

	<div class="form-float-label">
		<div class='switch p-md'>
		    <label>
		        Activate
		        <input name='active_flag' type='hidden'   value='N'>
		        <input name='active_flag' type='checkbox'   value='Y' <?php echo ($active_flag['active_flag'] == "Y") ? "checked" : "" ?>> 
		        <span class='lever'></span>
		    </label>
		</div>
		<div class="row">
		  <div class="col s12">
			<div class="input-field">
				<input type="text" class="validate" required="" aria-required="true" name="replacement_reason_name" id="replacement_reason_name" value="<?php echo isset($replacement_reason_info['replacement_reason_name']) ? $replacement_reason_info['replacement_reason_name'] : NULL; ?>"/>
		   		<label class="<?php echo $action == ACTION_EDIT ? 'active' :'' ?>" for="replacement_reason_name">Reason of Replacement</label>
			</div>
		  </div>
		</div>
	</div>
	<div class="md-footer default">
		<a class="waves-effect waves-teal btn-flat cancel_modal" id="cancel_replacement_reason">Cancel</a>
	  <?php //if($this->permission->check_permission(MODULE_ROLE, ACTION_SAVE)):?>
		    <button class="btn btn-success " id="save_replacement_reason" value="<?php echo BTN_SAVE ?>"><?php echo BTN_SAVE ?></button>
	  <?php //endif; ?>
	</div>
</form>
<script>
$(function (){
	$('#replacement_reason_form').parsley();
	$('#replacement_reason_form').submit(function(e) {
	    e.preventDefault();
	    
		if ( $(this).parsley().isValid() ) {
			var data = $(this).serialize();
		  	button_loader('save_replacement_reason', 1);
		  	var option = {
					url  : $base_url + 'main/code_library/process_replacement_reason',
					data : data,
					success : function(result){
						if(result.status)
						{
							notification_msg("<?php echo SUCCESS ?>", result.msg);
							$("#cancel_replacement_reason").trigger('click');
							load_datatable('replacement_reason_table', '<?php echo PROJECT_MAIN ?>/code_library/get_replacement_reason_list');
						}
						else
						{
							notification_msg("<?php echo ERROR ?>", result.msg);
						}	
						
					},
					
					complete : function(jqXHR){
						button_loader('save_replacement_reason', 0);
					}
			};

			General.ajax(option);    
	    }
  	});
})
</script>