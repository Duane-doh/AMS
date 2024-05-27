<form id="remittance_type_deduction_form">
	<input type="hidden" name="id" id="id" value="<?php echo !EMPTY($id) ? $id : NULL?>">
	<input type="hidden" name="salt" id="salt" value="<?php echo !EMPTY($salt) ? $salt : NULL?>">
	<input type="hidden" name="token" id="token" value="<?php echo !EMPTY($token) ? $token : NULL?>">
	<input type="hidden" name="action" id="action" value="<?php echo !EMPTY($action) ? $action : NULL?>">
	<input type="hidden" name="module" id="module" value="<?php echo !EMPTY($module) ? $module : NULL?>">
	<div class="form-float-label">
		<div class="row">
			<div class="col s12">
				<div class="input-field">
					<input disabled type="text" class="validate"  name="remittance_type" id="remittance_type" value="<?php echo isset($remittance_type_info['remittance_type_name']) ? $remittance_type_info['remittance_type_name'] : NULL; ?>"/>
					<label class="active" for="remittance_type">Remittance Type</label>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col s12">
				<div class="input-field">
					<input disabled type="text" class="validate"  name="remittance_payee" id="remittance_payee" value="<?php echo isset($remittance_payee[0]['remittance_payee_name']) ? $remittance_payee[0]['remittance_payee_name'] : ""?>"/>
					<label class="active" for="remittance_payee">Remittance Payee</label>
				</div>
			</div>
		</div>
		
		<div class="row">
			<div class="col s12">
				<div class="input-field">
					<label for="remittance_file_name" class="active">Deduction Types<span class="required">*</span></label>
					<select id="deduction_type" name="deduction_type[]" class="selectize validate" multiple <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>>
						<option value="">Select Payout Count</option>
						<?php foreach($deduction_types as $deduction):?>
						<option value="<?php echo $deduction['deduction_id']?>" <?php echo in_array($deduction['deduction_id'], $remittance_deductions) ? 'selected':''?>><?php echo $deduction['deduction_name']?></option>
						<?php endforeach;?>
					</select>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col s12">
				<div class="input-field">
					<label for="remittance_file_name" class="active">Remittance File Name</label>
					<select id="remittance_file_name" name="remittance_file_name" class="selectize validate" <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>>
						<option value="">Select Payout Count</option>
						<?php foreach($remittance_file_upload as $rfu):?>
							<option value="<?php echo $rfu['sys_param_value']?>" <?php echo ($remittance_type_deduction_file === $rfu['sys_param_value']) ? 'selected' : ''?>><?php echo $rfu['sys_param_name'] ?></option>
						<?php endforeach;?>
					</select>
				</div>
			</div>
		</div>
	</div>

	<div class="md-footer default">
	  	<?php if($action != ACTION_VIEW):?>
	  		<a class="waves-effect waves-teal btn-flat cancel_modal">Cancel</a>
		    <button class="btn btn-success" id="save_remittance_type_deduction" value="<?php echo BTN_SAVE ?>"><?php echo BTN_SAVE ?></button>
	  	<?php endif; ?>
	</div>
</form>


<script>

$(function (){

	
	$('#remittance_type_deduction_form').parsley();
	$('#remittance_type_deduction_form').submit(function(e) {
	    e.preventDefault();
	    
		if ( $(this).parsley().isValid() ) {
			var data = $(this).serialize();
		  	button_loader('save_remittance_type_deduction', 1);
		  	var option = {
					url  : $base_url + 'main/code_library_payroll/remittance_type/process_remittance_type_deduction',
					data : data,
					success : function(result){
						if(result.status)
						{
							modal_remittance_type_deduction.closeModal();
							notification_msg("<?php echo SUCCESS ?>", result.msg);
						}
						else
						{
							notification_msg("<?php echo ERROR ?>", result.msg);
						}	
						
					},
					
					complete : function(jqXHR){
						button_loader('save_remittance_type_deduction', 0);
					}
			};

			General.ajax(option);    
	    }
  	});

	
});

</script>