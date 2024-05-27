<form id="leave_card_form">	
	<input type="hidden" name="action" value="<?php echo $action ?>" />
	<input type="hidden" name="id" value="<?php echo $id ?>" />
	<input type="hidden" name="token" value="<?php echo $token ?>" />
	<input type="hidden" name="salt" value="<?php echo $salt ?>" />
	<input type="hidden" name="module" value="<?php echo $module ?>" />
	<input type="hidden" name="employee_id" id="employee_id" value="<?php echo $employee_id ?>" />
	<input type="hidden" name="leave_type_id" id="leave_type_id" value="<?php echo $leave_card['leave_type_id'] ?>" />
	<input type="hidden" name="leave_detail_id" id="leave_detail_id" value="<?php echo $leave_card['leave_detail_id'] ?>" />
	<input type="hidden" name="hash_leave_type_id" id="hash_leave_type_id" value="<?php echo $hash_leave_type_id ?>" />
	<input type="hidden" name="prev_leave_earned_used" id="prev_leave_earned_used" value="<?php echo $leave_card['leave_earned_used'] ?>" />
	<input type="hidden" name="prev_leave_transaction_type_id" id="prev_leave_transaction_type_id" value="<?php echo $leave_card['leave_transaction_type_id'] ?>" />

	<div class="form-float-label">
	
		<div class="row b-n p-t-lg p-l-md p-b-md">
			<span class="font-lg font-playfair-display font-spacing-15"><?php echo isset($leave_card['leave_type_name'])? $leave_card['leave_type_name'] : ""; ?></span>
		</div>
		
		<div class="row m-n b-t b-light-gray">
			<!--<div class="col s12">-->
			<!--marvin-->
			<div class="col s6">
			<!--marvin-->
			  <div class="input-field">
			  	<label for="transaction_type" class="active">Leave Transaction Type</label>
				<select id="transaction_type" name="transaction_type" class="selectize" placeholder="Select Leave Transaction Type">
					<option value="">Select Leave Transaction Type</option>
					<?php if(!EMPTY($transaction_types)): ?>
						<?php foreach ($transaction_types as $type): ?>
							<option value="<?php echo $type['leave_transaction_type_id']; ?>"><?php echo $type['leave_transaction_type_name']; ?></option>
						<?php endforeach;?>
					<?php endif;?>
				</select> 
		      </div>
		    </div>
			<!------------------------ MARVIN : START : ADD TRANSACTION DATE PICKER ------------------------>
			<div class="col s6">
				<div class="input-field">
					<label for="leave_transaction_date" class="<?php echo isset($leave_card['leave_transaction_date']) ? "active" : "" ?>">Transaction Date <span class="required"> * </span></label>
					<input type="text" id="leave_transaction_date" name="leave_transaction_date" class="datepicker" value="<?php echo isset($leave_card['leave_transaction_date']) ? format_date($leave_card['leave_transaction_date'], 'Y/m/d') : "" ?>" required />
				</div>
			<!------------------------ MARVIN : END : ADD TRANSACTION DATE PICKER ------------------------>
			</div>
		</div>
		
		<div class="row m-n">
		
			<div class="col s6">
				<div class="input-field">
					<input id="leave_earned_used" name="leave_earned_used" type="text" value="<?php echo isset($leave_card['leave_earned_used']) ? $leave_card['leave_earned_used'] : "" ?>" required />
					<label for="leave_earned_used" class="<?php echo isset($leave_card['leave_earned_used']) ? "active" : "" ?>">Number of Days <span class="required"> * </span></label>
				</div>
			</div>
			
			<div class="col s6">
				<div id="div_effective_date" class="input-field">
					<input id="effective_date" name="effective_date" type="text" value="<?php echo isset($leave_card['effective_date']) ? format_date($leave_card['effective_date'], 'Y/m/d') : "" ?>" class="datepicker" required />
					<label for="effective_date" class="<?php echo isset($leave_card['effective_date']) ? "active" : "" ?>">Effectivity Date <span class="required"> * </span></label>
				</div>
				
				<div id="div_no_of_days_wop" class="input-field none">
					<input id="no_of_days_wop" name="no_of_days_wop" type="text" value="0" required />
					<label for="no_of_days_wop" class="active">Number of Days w/o Pay <span class="required"> * </span></label>
				</div>
			</div>
			
		</div>
		
		<div class="none" id="date_range">
			<div class="form-float-label">
				<div class="row m-n  b-b-n b-t b-light-gray" >
					<div class="col s6">
						<div class="input-field">
							<input id="leave_start_date" name="leave_start_date" class="datepicker_start" type="text" value="<?php echo isset($leave_card['leave_start_date']) ? format_date($leave_card['leave_start_date'], 'Y/m/d') : "" ?>" required onkeypress="format_identifications('<?php echo DATE_FORMAT ?>',this.value,event,'leave_start_date')" />
							<label for="leave_start_date" class="<?php echo isset($leave_card['leave_start_date']) ? "active" : "" ?>">Leave Start Date <span class="required"> * </span></label>
						</div>
					</div>
					
					<div class="col s6">
						<div class="input-field">
							<input id="leave_end_date" name="leave_end_date" class="datepicker_end" type="text" value="<?php echo isset($leave_card['leave_end_date']) ? format_date($leave_card['leave_end_date'], 'Y/m/d') : "" ?>" required onkeypress="format_identifications('<?php echo DATE_FORMAT ?>',this.value,event,'leave_end_date')" />
							<label for="leave_end_date" class="<?php echo isset($leave_card['leave_end_date']) ? "active" : "" ?>">Leave End Date <span class="required"> * </span></label>
						</div>
					</div>
				</div>
			</div>		
		</div>
		
		<div class="row m-n">
			<div class="col s12">
				<div class="input-field">
				  	<label for="remarks" class="<?php echo isset($leave_card['remarks']) ? "active" : "" ?>">Remarks <span class="required"> * </span></label>
      				<textarea name="remarks" class="materialize-textarea" id="remarks" required><?php echo isset($leave_card['remarks']) ? $leave_card['remarks'] : "" ?></textarea>
				</div>
			 </div>	
		</div>
	</div>
<div class="md-footer default">
	<a class="waves-effect waves-teal btn-flat cancel_modal" id="cancel_service_record">Cancel</a>
    <button class="btn btn-success  green" id="save_adjustment" value="<?php echo BTN_SAVE ?>"><?php echo BTN_SAVE ?></button>
</div>
</form>
<script>
$(document).ready(function(){

	$('#transaction_type').off('change');
	$('#transaction_type').on('change', function(e){
		
		var type = $(this).val();

		if(type == '<?php echo LEAVE_INITIAL_BALANCE ?>' || type == '<?php echo LEAVE_CREDIT_LEAVE ?>')
		{
			$("#date_range").addClass('none');
			$('#leave_start_date').attr('required', false).val("");
			$('#leave_end_date').attr('required', false).val("");
		}
		else
		{			
			$("#date_range").removeClass('none');
			$('#leave_start_date').attr('required', true).val("<?php echo format_date($leave_card['leave_start_date'], 'Y/m/d'); ?>");
			$('#leave_end_date').attr('required', true).val("<?php echo format_date($leave_card['leave_end_date'], 'Y/m/d'); ?>");
		}
		if(type == '<?php echo LEAVE_FILE_LEAVE?>')
		{
			$('#div_no_of_days_wop').removeClass('none');
			$('#div_effective_date').addClass('none');

			$('#no_of_days_wop').attr('required', true);
			$('#effective_date').attr('required', false);
		}
		else
		{
			$('#div_effective_date').removeClass('none');
			$('#div_no_of_days_wop').addClass('none');
			
			$('#no_of_days_wop').attr('required', false);
			$('#effective_date').attr('required', true);
		}
	});

	$('#leave_card_form').parsley();
	
 	jQuery(document).off('submit', '#leave_card_form');
	jQuery(document).on('submit', '#leave_card_form', function(e){
		
	    e.preventDefault();
	    
		if($(this).parsley().isValid())
		{
			var data = $('#leave_card_form').serialize();
		  	button_loader('save_adjustment', 1);
		  	var option = {
				url  : $base_url + 'main/leaves/update_modal_leave_card',
				data : data,
				success : function(result){
					if(result.status)
					{
						notification_msg("<?php echo SUCCESS ?>", result.message);
						modal_leave_card.closeModal();
						var post_data = {
							'employee_id' : $("#employee_id").val(),
							'leave_type_id' : $("#hash_leave_type_id").val()
						};
						load_datatable('table_leave_history', '<?php echo PROJECT_MAIN ?>/leaves/get_employee_leave_history',false,0,0,true,post_data);
					}
					else
					{
						notification_msg("<?php echo ERROR ?>", result.message);
					}	
					
				},
				complete : function(jqXHR){
					button_loader('save_adjustment', 0);
				}
			};
			General.ajax(option);    
	    }
  	});
});
</script>