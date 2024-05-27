<form id="signatories_form">
	<input type="hidden" name="id" id="id" value="<?php echo !EMPTY($id) ? $id : NULL?>">
	<input type="hidden" name="salt" id="salt" value="<?php echo !EMPTY($salt) ? $salt : NULL?>">
	<input type="hidden" name="token" id="token" value="<?php echo !EMPTY($token) ? $token : NULL?>">
	<input type="hidden" name="action" id="action" value="<?php echo !EMPTY($action) ? $action : NULL?>">
	<input type="hidden" name="module" id="module" value="<?php echo !EMPTY($module) ? $action : NULL?>">

	<div class="form-float-label">
		<div class="row">
		  <div class="col s12">
			<div class="input-field">
				<input type="text" class="validate strict-case-input" required name="signatory_name" id="signatory_name" value="<?php echo isset($signatories_info['signatory_name']) ? $signatories_info['signatory_name'] : NULL?>" <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>/>
		   		<label class="<?php echo $action == ACTION_EDIT ? 'active' :'' ?>" for="signatory_name">Fullname<span class="required">*</span></label>
			</div>
		  </div>
		</div>
		<div class="row">
		  <div class="col s12">
			<div class="input-field">
				<input type="text" class="validate strict-case-input" required name="position_name" id="position_name" value="<?php echo isset($signatories_info['position_name']) ? $signatories_info['position_name'] : NULL?>" <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>/>
		   		<label class="<?php echo $action == ACTION_EDIT ? 'active' :'' ?>" for="position_name">Position
		   		<span class="required">*</span></label>
			</div>
		  </div>
		</div>
		<div class="row">
		  <div class="col s12">
			<div class="input-field">
				<input type="text" class="validate strict-case-input" required name="office_name" id="office_name" value="<?php echo isset($signatories_info['office_name']) ? $signatories_info['office_name'] : NULL?>" <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>/>
		   		<label class="<?php echo $action == ACTION_EDIT ? 'active' :'' ?>" for="office_name">Office
		   		<span class="required">*</span></label>
			</div>
		  </div>
		</div>
		
		<div class="row">
		  <div class="col s12">
			<div class="input-field">
				<label for="signatory_type_flags" class="active">Signatory Types<span class="required">*</span></label>
				<select id="signatory_type_flags" required name="signatory_type_flags[]" class="selectize" placeholder="Select Signatory Type" multiple <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>>
					<option value="">Select Signatory Types</option>
					<option value="<?php echo PREPARED_BY ?>"><?php echo PREPARED_BY ?></option>
					<option value="<?php echo CERTIFIED_BY ?>"><?php echo CERTIFIED_BY ?></option>
					<option value="<?php echo APPROVED_BY ?>"><?php echo APPROVED_BY ?></option>
					<option value="<?php echo CASH_AVAILABLE_BY ?>"><?php echo CASH_AVAILABLE_BY ?></option>
				</select>
			</div>
		  </div>
		</div>
		<div class="row">
		  <div class="col s12">
			<div class="input-field">
				<label for="sys_code_flags" class="active">System Types<span class="required">*</span></label>
				<select id="sys_code_flags" required name="sys_code_flags[]" class="selectize" placeholder="Select System Type" multiple <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>>
					<option value="">Select System Types</option>
					<option value="<?php echo CODE_HR ?>"><?php echo CODE_HR ?></option>
					<option value="<?php echo CODE_TA ?>"><?php echo CODE_TA ?></option>
					<option value="<?php echo CODE_PAYROLL ?>"><?php echo CODE_PAYROLL ?></option>
				</select>
			</div>
			
		  </div>
		</div>
	</div>
	<div class="md-footer default">
	  	<?php if($action != ACTION_VIEW):?>
	  		<a class="waves-effect waves-teal btn-flat cancel_modal">Cancel</a>
		    <button class="btn btn-success " id="save_signatories" value="<?php echo BTN_SAVE ?>"><?php echo BTN_SAVE ?></button>
	  	<?php endif; ?>
	</div>
</form>

<script>
$(function (){

	$('#signatories_form').parsley();
	$('#signatories_form').submit(function(e) {
	    e.preventDefault();

		if ( $(this).parsley().isValid() ) {
			var data = $(this).serialize();
		  	button_loader('save_signatories', 1);
		  	var option = {
					url  : $base_url + 'main/code_library_system/signatories/process',
					data : data,
					success : function(result){
						if(result.status)
						{
							notification_msg("<?php echo SUCCESS ?>", result.msg);
							modal_signatories.closeModal();
							load_datatable('signatories_table', '<?php echo PROJECT_MAIN ?>/code_library_system/signatories/get_signatories_list', false,0,0,true);
						}
						else
						{
							notification_msg("<?php echo ERROR ?>", result.msg);
						}	
						
					},
					
					complete : function(jqXHR){
						button_loader('save_signatories', 0);
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