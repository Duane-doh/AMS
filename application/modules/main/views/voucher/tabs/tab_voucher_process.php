<div class="p-t-lg">
	<form id="process_voucher_form">
	<input type="hidden" name="id"  id="id" value="<?php echo $id ?>"/>
	<input type="hidden" name="salt" value="<?php echo $salt ?>"/>
	<input type="hidden" name="token" value="<?php echo $token ?>"/>
	<input type="hidden" name="action" value="<?php echo $action ?>"/>
	<input type="hidden" name="module" value="<?php echo $module ?>"/>	
	<div class="row">
		<div class="col s8">
			<div class="form-float-label m-b-lg p-b-md">
				<div class="row b-light-gray b-t">
					<div class="col s7">
						<div class="input-field">
						 	<input type="text" name="voucher_description" disabled value="<?php echo isset($voucher_info['voucher_description'])? $voucher_info['voucher_description']:''?>" />
				   			<label for="voucher_description" class="active">Voucher Description</label>
						</div>
					</div>

					<div class="col s5">
						<div class="input-field">
						 	<input type="text" id="voucher_date" name="voucher_date" disabled value="<?php echo isset($voucher_info['employee_name'])? $voucher_info['employee_name']:''?>"/>
				   			<label for="voucher_date" class="active">Employee</label>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col s4">
						<div class="input-field">
						 	<input type="text" name="voucher_description" disabled value="<?php echo isset($voucher_info['bank_account'])? $voucher_info['bank_account']:''?>" />
				   			<label for="voucher_description" class="active">Bank Account</label>
						</div>
					</div>

					<div class="col s4">
						<div class="input-field">
						 	<input type="text" name="voucher_description" disabled value="<?php echo isset($voucher_info['voucher_date'])? format_date($voucher_info['voucher_date']):''?>" />
				   			<label for="voucher_description" class="active">Voucher Date</label>
						</div>
					</div>
					<div class="col s4">
						<div class="input-field">
						 	<input type="text" name="voucher_description" disabled value="<?php echo isset($voucher_info['net_pay'])? number_format($voucher_info['net_pay'],2):''?>" />
				   			<label for="voucher_description" class="active">Net Amount</label>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col s4">
			<div class="form-float-label">
				
				<div class="row b-light-gray b-t">
					<div class="col s12">
						<div class="input-field">
						  	<label for="payout_status" class="active">Payout Status <span class="required"> * </span></label>
							 <select id="payout_status"  name="payout_status" class="selectize" placeholder="Select Payout Status" required>
								<option value="">Select Payout Status </option>	
								 <?php if (!EMPTY($payout_status)) : 
										 foreach ($payout_status as $status): 
										 	if($status['approved_flag'] OR $status['return_flag']) {
										 		if($this->permission->check_permission($module, $status['action_id']))
												echo '<option value="' . $status['payout_status_id'] . '">' . $status['payout_status_name'] . '</option>';
										 	}
											else
												echo '<option value="' . $status['payout_status_id'] . '">' . $status['payout_status_name'] . '</option>';
									 	endforeach;
									endif;?>		
						 	 </select>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col s12">
						<div class="input-field">
						 	<textarea name="remarks" class="materialize-textarea" id="remarks" <?php echo (isset($voucher_info['voucher_status_id']) AND $voucher_info['voucher_status_id'] == PAYOUT_STATUS_PAID)? 'disabled':''?>></textarea>
				   			<label for="remarks">Remarks</label>
						</div>
					</div>
				</div>
			</div>
			<?php if ($voucher_info['voucher_status_id'] != PAYOUT_STATUS_PAID AND $has_permission):?>
			<div class="col s12 p-r-sm p-t-md">
			    <button id="save_voucher" class="btn btn-success pull-right">Save</button>
			</div>
		<?php endif;?>
		</div>
	</div>
</form>
</div>
<script>
$(document).ready(function(){
	$('#process_voucher_form').parsley();
	jQuery(document).off('submit', '#process_voucher_form');
	jQuery(document).on('submit', '#process_voucher_form', function(e){
	    e.preventDefault();
	    
		if ( $(this).parsley().isValid() ) {
			var data = $('#process_voucher_form').serialize();
		  	button_loader('save_voucher', 1);
		  	var option = {
					url  : $base_url + 'main/voucher_process/process_voucher',
					data : data,
					success : function(result){
						if(result.status)
						{
							notification_msg("<?php echo SUCCESS ?>", result.message);
							$('#link_voucher_process').trigger('click');
						}
						else
						{
							notification_msg("<?php echo ERROR ?>", result.message);
						}	
						
					},
					
					complete : function(jqXHR){
						button_loader('save_voucher', 0);
					}
			};

			General.ajax(option);    
	    }
  	});
  	<?php if(isset($voucher_info['voucher_status_id']) AND $voucher_info['voucher_status_id'] == PAYOUT_STATUS_PAID OR !$has_permission) : ?>
  		$('span.required').addClass('hide');
 		$('#payout_status').attr('disabled','');
  	<?php endif; ?>
});
</script>
	