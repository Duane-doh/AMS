<form id="request_status_form">
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
				<label class="<?php echo $action == ACTION_EDIT ? 'active' :'' ?>" for="request_status">Request Status Name</label>
				<input type="text" class="validate" required="" aria-required="true" name="request_status_name" id="request_status_name" value="<?php echo isset($request_status_info['request_status_name']) ? $request_status_info['request_status_name'] : NULL?>"/>
			</div>
		  </div>
		</div>
			<!-- Switch -->
 
	</div>
	<div class="md-footer default">
		<a class="waves-effect waves-teal btn-flat cancel_modal">Cancel</a>
	  <?php //if($this->permission->check_permission(MODULE_ROLE, ACTION_SAVE)):?>
		    <button class="btn btn-success " id="save_request_status" value="<?php echo BTN_SAVE ?>"><?php echo BTN_SAVE ?></button>
	  <?php //endif; ?>
	</div>
</form>
<script>
$(function (){
	$('#request_status_form').parsley();
	$('#request_status_form').submit(function(e) {
	    e.preventDefault();
	    
		if ( $(this).parsley().isValid() ) {
			var data = $(this).serialize();
		  	button_loader('save_request_status', 1);
		  	var option = {
					url  : $base_url + 'main/code_library/process_request_status',
					data : data,
					success : function(result){
						if(result.status)
						{
							modal_request_status.closeModal();
							notification_msg("<?php echo SUCCESS ?>", result.msg);
							load_datatable('request_status_table', '<?php echo PROJECT_MAIN ?>/code_library/get_request_status_list',false,0,0,true);
						}
						else
						{
							notification_msg("<?php echo ERROR ?>", result.msg);
						}	
						
					},
					
					complete : function(jqXHR){
						button_loader('save_request_status', 0);
					}
			};

			General.ajax(option);    
	    }
  	});
})
</script>