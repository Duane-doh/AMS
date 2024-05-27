<form id="uacs_code_form">
	<input type="hidden" name="id" id="id" value="<?php echo !EMPTY($id) ? $id : NULL?>">
	<input type="hidden" name="salt" id="salt" value="<?php echo !EMPTY($salt) ? $salt : NULL?>">
	<input type="hidden" name="token" id="token" value="<?php echo !EMPTY($token) ? $token : NULL?>">
	<input type="hidden" name="action" id="action" value="<?php echo !EMPTY($action) ? $action : NULL?>">
	<input type="hidden" name="module" id="module" value="<?php echo !EMPTY($module) ? $action : NULL?>">

	<div class="form-float-label">
		<div class="row">
		  <div class="col s12">
			<div class="input-field">
				<input type="text" class="validate" required name="account_title" id="account_title" value="<?php echo isset($uacs_code_info['account_title']) ? $uacs_code_info['account_title'] : NULL?>" <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>/>
		   		<label class="<?php echo $action == ACTION_EDIT ? 'active' :'' ?>" for="account_title">Account Title<span class="required">*</span></label>
			</div>
		  </div>
		</div>
		<div class="row">
		  <div class="col s12">
			<div class="input-field">
				<input type="text" class="validate" required name="uacs_object_code" id="uacs_object_code" value="<?php echo isset($uacs_code_info['uacs_object_code']) ? $uacs_code_info['uacs_object_code'] : NULL?>" <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>/>
		   		<label class="<?php echo $action == ACTION_EDIT ? 'active' :'' ?>" for="uacs_object_code">UACS Object Code<span class="required">*</span></label>
			</div>
		  </div>
		</div>
		<div class="row">
		 	<div class="col s12">
				<div class="input-field">
					<label for="uacs_object_type" class="active">Type<span class="required">*</span></label>
					<select id="uacs_object_type" name="uacs_object_type" class="selectize" placeholder="Select Type" required <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>>
					 <option value="">Select Type</option>
					 <?php if (!EMPTY($uacs_object_type_info)): ?>
						<?php foreach ($uacs_object_type_info as $uacstype): ?>
							<option value="<?php echo $uacstype['uacs_object_type_id']?>" <?php echo ($uacs_code_info['uacs_object_type_id'] == $uacstype['uacs_object_type_id'] ? ' selected' : '')?> ><?php echo strtoupper($uacstype['uacs_object_type']) ?></option>
						<?php endforeach;?>
					<?php endif;?>
					</select>
				</div>
			</div>
		</div>
		<div class='row switch p-md b-b-n'>
		    <label>
		        Inactive
		        <input name='active_flag' type='checkbox' value='Y' <?php echo ($uacs_code_info['active_flag'] == "Y") ? "checked" : "" ?> <?php echo $action == ACTION_ADD ? 'checked' :'' ?> <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>> 
		        <span class='lever'></span>Active
		    </label>
		</div>
	</div>
	<div class="md-footer default">
	  	<?php if($action != ACTION_VIEW):?>
	  		<a class="waves-effect waves-teal btn-flat cancel_modal">Cancel</a>
		    <button class="btn btn-success " id="save_uacs_code" value="<?php echo BTN_SAVE ?>"><?php echo BTN_SAVE ?></button>
	  	<?php endif; ?>
	</div>
</form>

<script>
$(function (){

	$('#uacs_code_form').parsley();
	$('#uacs_code_form').submit(function(e) {
	    e.preventDefault();

		if ( $(this).parsley().isValid() ) {
			var data = $(this).serialize();
		  	button_loader('save_uacs_code', 1);
		  	var option = {
					url  : $base_url + 'main/code_library_payroll/uacs_object/process',
					data : data,
					success : function(result){
						if(result.status)
						{
							notification_msg("<?php echo SUCCESS ?>", result.msg);
							modal_uacs_code.closeModal();
							load_datatable('uacs_code_table', '<?php echo PROJECT_MAIN ?>/code_library_payroll/uacs_object/get_uacs_code_list', false,0,0,true);
						}
						else
						{
							notification_msg("<?php echo ERROR ?>", result.msg);
						}	
						
					},
					
					complete : function(jqXHR){
						button_loader('save_uacs_code', 0);
					}
			};

			General.ajax(option);    
	    }
  	});
  	
  	<?php if($action != ACTION_ADD){ ?>
		$('.input-field label').addClass('active');
  	<?php } ?>
})
</script>