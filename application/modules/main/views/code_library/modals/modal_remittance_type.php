<form id="remittance_type_form">
	<input type="hidden" name="id" id="id" value="<?php echo !EMPTY($id) ? $id : NULL?>">
	<input type="hidden" name="salt" id="salt" value="<?php echo !EMPTY($salt) ? $salt : NULL?>">
	<input type="hidden" name="token" id="token" value="<?php echo !EMPTY($token) ? $token : NULL?>">
	<input type="hidden" name="action" id="action" value="<?php echo !EMPTY($action) ? $action : NULL?>">
	<input type="hidden" name="module" id="module" value="<?php echo !EMPTY($module) ? $action : NULL?>">

	<div class="form-float-label">
		<div class="row">
		  <div class="col s12">
			<div class="input-field">
				<input type="text" class="validate" required name="remittance_type_name" id="remittance_type_name" value="<?php echo isset($remittance_type_info['remittance_type_name']) ? $remittance_type_info['remittance_type_name'] : NULL; ?>" <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>/>
		   		<label class="<?php echo $action == ACTION_EDIT ? 'active' :'' ?>" for="remittance_type_name">Remittance Type Name<span class="required">*</span></label>
			</div>
		  </div>
		</div>		
		<div class="row">
		 	<div class="col s12">
				<div class="input-field">
					<label for="remittance_payee" class="active">Remittance Payee</label>
					<select id="remittance_payee" name="remittance_payee" class="selectize" placeholder="Select Remittance Payee" <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>>
					 <option value="">Select Remittance Payee</option>
					 <?php if (!EMPTY($remittance_payee)): ?>
						<?php foreach ($remittance_payee as $payee): ?>
							<option value="<?php echo $payee['remittance_payee_id']?>"><?php echo strtoupper($payee['remittance_payee_name']) ?></option>
						<?php endforeach;?>
					<?php endif;?>
					</select>
				</div>
			</div>
		</div>
		<div class='row switch p-md b-b-n'>
		    <label>
		        Inactive
		        <input name='active_flag' type='checkbox'   value='Y' <?php echo ($remittance_type_info['active_flag'] == "Y") ? "checked" : "" ?> <?php echo $action == ACTION_ADD ? 'checked' :'' ?> <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>> 
		        <span class='lever'></span>Active
		    </label>
		</div>
	</div>
	<div class="md-footer default">
	  	<?php if($action != ACTION_VIEW):?>
	  		<a class="waves-effect waves-teal btn-flat cancel_modal">Cancel</a>
		    <button class="btn btn-success " id="save_remittance_type" value="<?php echo BTN_SAVE ?>"><?php echo BTN_SAVE ?></button>
	  	<?php endif; ?>
	</div>
</form>
<script>
$(function (){
	$('#remittance_type_form').parsley();
	$('#remittance_type_form').submit(function(e) {
	    e.preventDefault();
	    
		if ( $(this).parsley().isValid() ) {
			var data = $(this).serialize();
		  	button_loader('save_remittance_type', 1);
		  	var option = {
					url  : $base_url + 'main/code_library_payroll/remittance_type/process',
					data : data,
					success : function(result){
						if(result.status)
						{
							notification_msg("<?php echo SUCCESS ?>", result.msg);
							modal_remittance_type.closeModal();
							load_datatable('remittance_type_table', '<?php echo PROJECT_MAIN ?>/code_library_payroll/remittance_type/get_remittance_type_list',false,false,false,true);
						}
						else
						{
							notification_msg("<?php echo ERROR ?>", result.msg);
						}	
						
					},
					
					complete : function(jqXHR){
						button_loader('save_remittance_type', 0);
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