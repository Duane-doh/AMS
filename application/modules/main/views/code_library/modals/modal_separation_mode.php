<form id="separation_mode_form">
	<input type="hidden" name="id" id="id" value="<?php echo !EMPTY($id) ? $id : NULL?>">
	<input type="hidden" name="salt" id="salt" value="<?php echo !EMPTY($salt) ? $salt : NULL?>">
	<input type="hidden" name="token" id="token" value="<?php echo !EMPTY($token) ? $token : NULL?>">
	<input type="hidden" name="action" id="action" value="<?php echo !EMPTY($action) ? $action : NULL?>">
	<input type="hidden" name="module" id="module" value="<?php echo !EMPTY($module) ? $action : NULL?>">

	<div class="form-float-label">
		<div class="row">
		  <div class="col s12">
			<div class="input-field">
				<input type="text" class="validate" required name="separation_mode_name" id="separation_mode_name" value="<?php echo isset($separation_mode_info['separation_mode_name']) ? $separation_mode_info['separation_mode_name'] : NULL?>"  <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>/>
		   		<label class="<?php echo $action == ACTION_EDIT ? 'active' :'' ?>" for="separation_mode_name">Mode of Separation Name<span class="required">*</span></label>
			</div>
		  </div>
		</div>
		<div class='row switch p-md b-b-n'>
		    <label>
		        Inactive
		        <input name='active_flag' type='checkbox'   value='Y' <?php echo ($separation_mode_info['active_flag'] == "Y") ? "checked" : "" ?> <?php echo $action == ACTION_ADD ? 'checked' :'' ?> <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>> 
		        <span class='lever'></span>Active
		    </label>
		</div>
	</div>
	<div class="md-footer default">
	  	<?php if($action != ACTION_VIEW):?>
	  		<a class="waves-effect waves-teal btn-flat cancel_modal">Cancel</a>
		    <button class="btn btn-success " id="save_separation_mode" value="<?php echo BTN_SAVE ?>"><?php echo BTN_SAVE ?></button>
	  	<?php endif; ?>
	</div>
</form>

<script>
$(function (){

	$('#separation_mode_form').parsley();
	$('#separation_mode_form').submit(function(e) {
	    e.preventDefault();

		if ( $(this).parsley().isValid() ) {
			var data = $(this).serialize();
		  	button_loader('save_separation_mode', 1);
		  	var option = {
					url  : $base_url + 'main/code_library_hr/separation_mode/process',
					data : data,
					success : function(result){
						if(result.status)
						{
							notification_msg("<?php echo SUCCESS ?>", result.msg);
							modal_separation_mode.closeModal();
							load_datatable('separation_mode_table', '<?php echo PROJECT_MAIN ?>/code_library_hr/separation_mode/get_separation_mode_list', false,0,0,true);
						}
						else
						{
							notification_msg("<?php echo ERROR ?>", result.msg);
						}	
						
					},
					
					complete : function(jqXHR){
						button_loader('save_separation_mode', 0);
					}
			};

			General.ajax(option);    
	    }
  	});

  	<?php if($action != ACTION_ADD){ ?>
		$('.input-field label').addClass('active');
  	<?php } ?>

	$('#separation_mode_name').on('keyup', function(){
  		this.value = this.value.toUpperCase();
  	});
})
</script>