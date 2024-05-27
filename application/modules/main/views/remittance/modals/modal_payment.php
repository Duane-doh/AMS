<form id="remittance_form">
	<input type="hidden" name="id" id="id" value="<?php echo !EMPTY($id) ? $id : NULL?>">
	<input type="hidden" name="salt" id="salt" value="<?php echo !EMPTY($salt) ? $salt : NULL?>">
	<input type="hidden" name="token" id="token" value="<?php echo !EMPTY($token) ? $token : NULL?>">
	<input type="hidden" name="action" id="action" value="<?php echo !EMPTY($action) ? $action : NULL?>">
	<input type="hidden" name="module" id="module" value="<?php echo !EMPTY($module) ? $module : NULL?>">
	<div class="form-float-label">
		<div class="row">
			<div class="col s12">
				<div class="input-field">
				 	<input required class="datepicker" name="payment_date" id="payment_date" type="text" value="<?php echo (!EMPTY($remittance_info['payment_date']) ? format_date($remittance_info['payment_date']) : '') ?>" />
		   			<label for="or_date" class="active">Payment Date <span class="required">*</span></label>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col s12">
				<div class="input-field">
				 	<input name="payment_details" id="payment_details" type="text" value="<?php echo (!EMPTY($remittance_info['payment_details']) ? $remittance_info['payment_details'] : '') ?>" />
		   			<label for="payment_details" class="active">Payment Details</label>
				</div>
			</div>
		</div>
	</div>	
	<div class="md-footer default">
		<a class="waves-effect waves-teal btn-flat cancel_modal" id="modal_cancel">Cancel</a>
	    <button id="save_remittance" class="btn btn-success " id="save_remittance" value="<?php echo BTN_SAVE ?>"><?php echo BTN_SAVE ?></button>
	</div>
</form>

<script>

	$(function (){
	$('#remittance_form').parsley();
	$('#remittance_form').submit(function(e) {
	    e.preventDefault();
	    
		if ( $(this).parsley().isValid() ) {
			var data = $(this).serialize();
		  	button_loader('save_remittance', 1);
		  	var option = {
					url  : $base_url + '<?php echo PROJECT_MAIN ?>/payroll_remittance/process_remittance/1',
					data : data,
					success : function(result){
						if(result.status)
						{
							notification_msg("<?php echo SUCCESS ?>", result.msg);
							modal_payment.closeModal();
							load_datatable('payroll_remittance_list_tbl', '<?php echo PROJECT_MAIN ?>/payroll_remittance/get_payroll_list',false,0,0,true);
						}
						else
						{
							notification_msg("<?php echo ERROR ?>", result.msg);
						}	
						
					},
					
					complete : function(jqXHR){
						button_loader('save_remittance', 0);
					}
			};

			General.ajax(option);    
	    }
  	});
})

</script>