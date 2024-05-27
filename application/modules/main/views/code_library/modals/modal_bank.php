<form id="bank_form">
	<input type="hidden" name="id" id="id" value="<?php echo !EMPTY($id) ? $id : NULL?>">
	<input type="hidden" name="salt" id="salt" value="<?php echo !EMPTY($salt) ? $salt : NULL?>">
	<input type="hidden" name="token" id="token" value="<?php echo !EMPTY($token) ? $token : NULL?>">
	<input type="hidden" name="action" id="action" value="<?php echo !EMPTY($action) ? $action : NULL?>">
	<input type="hidden" name="module" id="module" value="<?php echo !EMPTY($module) ? $action : NULL?>">

	<div class="form-float-label">
		<div class="row">
		  <div class="col s12">
			<div class="input-field">
				<input type="text" class="validate" required name="bank_name" id="bank_name" value="<?php echo isset($bank_info['bank_name']) ? $bank_info['bank_name'] : NULL?>" <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>/>
		   		<label class="<?php echo $action == ACTION_EDIT ? 'active' :'' ?>" for="bank_name">Bank Name<span class="required">*</span></label>
			</div>
		  </div>
		</div>
		<div class="row">
		  <div class="col s12">
			<div class="input-field">
				<input type="text" class="validate" required name="branch_code" id="branch_code" value="<?php echo isset($bank_info['branch_code']) ? $bank_info['branch_code'] : NULL?>" <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>/>
		   		<label class="<?php echo $action == ACTION_EDIT ? 'active' :'' ?>" for="bank_name">Branch Code<span class="required">*</span></label>
			</div>
		  </div>
		</div>
		<div class="row">
		  <div class="col s12">
			<div class="input-field">
				<input type="text" class="validate" required name="account_no" id="account_no" value="<?php echo isset($bank_info['account_no']) ? $bank_info['account_no'] : NULL?>" <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>/>
		   		<label class="<?php echo $action == ACTION_EDIT ? 'active' :'' ?>" for="account_no">Account No.<span class="required">*</span></label>
			</div>
		  </div>
		</div>
		<div class="row">
		 	<div class="col s12">
				<div class="input-field">
					<label for="fund_source" class="active">Fund Source<span class="required">*</span></label>
					<select id="fund_source" name="fund_source" class="selectize" placeholder="Select Fund Source" required <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>>
					 <option value="">Select Fund Source</option>
					 <?php if (!EMPTY($fund_source_name)): ?>
						<?php foreach ($fund_source_name as $fsn): ?>
							<option value="<?php echo $fsn['fund_source_id']?>"><?php echo strtoupper($fsn['fund_source_name']) ?></option>
						<?php endforeach;?>
					<?php endif;?>
					</select>
				</div>
			</div>
			<!-- <div class="col s6">
				<div class="input-field">
					<input type="text" class="validate" required="" aria-required="true" name="account_number" id="account_number" value="<?php echo isset($bank_info['account_number']) ? $bank_info['account_number'] : NULL?>" <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>/>
			   		<label class="<?php echo $action == ACTION_EDIT ? 'active' :'' ?>" for="account_number">Account Number<span class="required">*</span></label>
				</div>
		  	</div> -->
		</div>
		<div class='row switch p-md b-b-n'>
		    <label>
		        Inactive
		        <input name='active_flag' type='checkbox'   value='Y' <?php echo ($bank_info['active_flag'] == "Y") ? "checked" : "" ?> <?php echo $action == ACTION_ADD ? 'checked' :'' ?> <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>> 
		        <span class='lever'></span>Active
		    </label>
		</div>
	</div>
	<div class="md-footer default">
	  	<?php if($action != ACTION_VIEW):?>
	  		<a class="waves-effect waves-teal btn-flat cancel_modal">Cancel</a>
		    <button class="btn btn-success " id="save_bank" value="<?php echo BTN_SAVE ?>"><?php echo BTN_SAVE ?></button>
	  	<?php endif; ?>
	</div>
</form>
<script>
$(function (){
	$('#bank_form').parsley();
	$('#bank_form').submit(function(e) {
	    e.preventDefault();
	    
		if ( $(this).parsley().isValid() ) {
			var data = $(this).serialize();
		  	button_loader('save_bank', 1);
		  	var option = {
					url  : $base_url + 'main/code_library_payroll/bank/process',
					data : data,
					success : function(result){
						if(result.status)
						{
							notification_msg("<?php echo SUCCESS ?>", result.msg);
							modal_bank.closeModal();
							load_datatable('bank_table', '<?php echo PROJECT_MAIN ?>/code_library_payroll/bank/get_bank_list',false,false,false,true);
						}
						else
						{
							notification_msg("<?php echo ERROR ?>", result.msg);
						}	
						
					},
					
					complete : function(jqXHR){
						button_loader('save_bank', 0);
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