<form id="role_form">
		<div class="form-float-label">
			<div class="row">
				<div class="col s12">
					<div class="input-field select-icon">
					 <i class="material-icons prefix grey-text">supervisor_account</i>
					 <label for="employment_type" class="active">Employee</label>
					 <select id="employment_type" <?php echo $action_id==ACTION_VIEW ? 'disabled' : '' ?> class="selectize" placeholder="Select Leave">
						<option value="">Select Employee</option>
						<option value="contractual">Contractual</option>
						<option value="regular">Regular</option>					
				 	 </select>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col s6">
					<div class="input-field select-icon">
					 <i class="material-icons prefix grey-text">redeem</i>
					 <label for="voucher" class="active">Voucher Type</label>
					 <select id="voucher" <?php echo $action_id==ACTION_VIEW ? 'disabled' : '' ?> class="selectize" placeholder="Select Bank">
						<option value="">Select voucher</option>
						<option value="sample1">sample1</option>
						<option value="sample2">sample2</option>
						<option value="sample3" >sample3</option>					
				 	 </select>
					</div>
				</div>
				<div class="col s6">
					<div class="input-field">
						<label for="payout">Payout Date</label>
						<input id="payout" name="payout" type="text" class="validate datepicker">
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col s12">
					<div class="input-field select-icon">
					 <i class="material-icons prefix grey-text">account_balance</i>
					 <label for="bank_type" class="active">Bank</label>
					 <select id="bank_type" <?php echo $action_id==ACTION_VIEW ? 'disabled' : '' ?> class="selectize" placeholder="Select Bank">
						<option value="">Select Bank</option>
						<option value="bpi_bank">Bank of the Philippines</option>
						<option value="landbank_bank">Landbank</option>
						<option value="bdo_bank" >Banco de Oro</option>					
				 	 </select>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col s12">
					<div class="input-field select-icon">
					 <i class="material-icons prefix grey-text">supervisor_account</i>
					 <label for="certified" class="active">Certified By</label>
					 <select  multiple="multiple" id="certified" <?php echo $action_id==ACTION_VIEW ? 'disabled' : '' ?> class="selectize" placeholder="Select Certified">
						<option value="">Select Certified</option>
						<option value="juan">Juan Dela Cruz</option>
						<option value="gidget">Mary Gidget Dela Llana</option>
				 	 </select>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col s12">
					<div class="input-field select-icon">
					 <i class="material-icons prefix grey-text">supervisor_account</i>
					 <label for="approved" class="active">Approved By</label>
					 <select id="apporved" <?php echo $action_id==ACTION_VIEW ? 'disabled' : '' ?> class="selectize" placeholder="Select Approved">
						<option value="">Select Certified</option>
						<option value="juan">Juan Dela Cruz</option>
						<option value="gidget">Mary Gidget Dela Llana</option>
				 	 </select>
					</div>
				</div>
			</div>
		</div>
</form>
	<div class="md-footer default">
		<a class="waves-effect waves-teal btn-flat cancel_modal" id="modal_cancel">Cancel</a>
	  <?php //if($this->permission->check_permission(MODULE_ROLE, ACTION_SAVE)):?>
	    <button id="payroll_report" class="btn btn-success " id="save_service_record" value="<?php echo BTN_SAVE ?>"><?php echo BTN_SAVE ?></button>
	  <?php //endif; ?>
	</div>
<script>
$(function (){
	$('.selectize').selectize();
	$("#payroll_report").on('click', function (){
		window.location = $base_url + 'main/payroll/payroll_report/';
	});
})
</script>