<form id="leave_adjustment_form">	
	<input type="hidden" name="id" id = "leave_type_id" value="<?php echo $id ?>"/>
	<input type="hidden" name="salt" value="<?php echo $salt ?>"/>
	<input type="hidden" name="token" value="<?php echo $token ?>"/>
	<input type="hidden" name="action" value="<?php echo $action ?>"/>
	<input type="hidden" name="module" value="<?php echo $module ?>"/>
	<input type="hidden" name="employee_id" id = "employee_id" value="<?php echo $employee_id ?>"/>	

	<div class="form-float-label">
		<div class="row b-n p-t-lg p-l-md p-b-md">
			<span class="font-lg font-playfair-display font-spacing-15"><?php echo isset($leave_type)? $leave_type:""; ?></span>
		</div>
		<div class="row m-n b-t b-light-gray">
			<div class="col s12">
			  <div class="input-field">
			  	<label for="transaction_type" class="active">Leave Transaction Type</label>
				<select id="transaction_type" name="transaction_type" class="selectize" placeholder="Select Leave Transaction Type">
					<option value="">Select Leave Transaction Type</option>
					<?php if (!EMPTY($transaction_types)): ?>
						<?php foreach ($transaction_types as $type): ?>
							<option value="<?php echo $type['leave_transaction_type_id'] ?>"><?php echo $type['leave_transaction_type_name'] ?></option>
						<?php endforeach;?>
					<?php endif;?>
				</select> 
		      </div>
		    </div>
		</div>
		<div class="none" id="num_of_days_row">
			<div class="form-float-label">	
				<div class="row m-n  b-b-n b-t b-light-gray" >
					<div class="col s6">
						<div class="input-field">
							<input id="leave_earned_used" name="leave_earned_used" type="text" required>
							<label for="leave_earned_used" >Number of Days <span class="required"> * </span></label>
						</div>
					</div>
					<div class="col s6">
						<div id="div_effective_date" class="input-field">
							<input id="effective_date" name="effective_date" type="text" required class="datepicker">
							<label for="effective_date" >Effectivity Date <span class="required"> * </span></label>
						</div>
						<div id="div_no_of_days_wop" class="input-field none">
							<input id="no_of_days_wop" name="no_of_days_wop" type="text" required value="0">
							<label for="no_of_days_wop" class="active">Number of Days w/o Pay <span class="required"> * </span></label>
						</div>
					</div>
				</div>
			</div>
		</div>		
		<div class="none" id="date_range">
			<div class="form-float-label">
				<div class="row m-n  b-b-n b-t b-light-gray" >
					<div class="col s6">
						<div class="input-field">
							<input id="leave_start_date" name="leave_start_date" class="datepicker_start" type="text" required onkeypress="format_identifications('<?php echo DATE_FORMAT ?>',this.value,event,'leave_start_date')">
							<label for="leave_start_date" >Leave Start Date <span class="required"> * </span></label>
						</div>
					</div>
					<div class="col s6">
						<div class="input-field">
							<input id="leave_end_date" name="leave_end_date" class="datepicker_end" type="text" required onkeypress="format_identifications('<?php echo DATE_FORMAT ?>',this.value,event,'leave_end_date')">
							<label for="leave_end_date" >Leave End Date <span class="required"> * </span></label>
						</div>
					</div>
				</div>
			</div>		
		</div>
		<div class="none" id="approved_leaves_list">
			<div class="form-float-label">
				<div class="row m-n  b-b-n b-t b-light-gray" >
					<div class="col s12">
			  			<div class="input-field">
			  				<label for="approved_leaves" class="active">Date of Approved Leave</label>
							<select id="approved_leaves" name="approved_leaves" class="selectize" placeholder="Select Approved Leave">
								<option value="">Select Approved Leave</option>
								<?php if (!EMPTY($transaction_types))
								{
									foreach ($approved_leaves as $approved_leave)
									{
										echo "<option value='".$approved_leave['leave_detail_id']."'>". $approved_leave['leave_start_date_str'] . " - " . $approved_leave['leave_end_date_str'] ."</option>";
									}
								} 
									
									
								?>
							</select> 
		     			 </div>
		    		</div>
				</div>
			</div>		
		</div>
		<div class="row m-n">
			<div class="col s12">
				<div class="input-field">
				  	<label for="remarks">Remarks <span class="required"> * </span></label>
      				<textarea name="remarks" class="materialize-textarea" id="remarks" required></textarea>
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

		if(type == '<?php echo LEAVE_INITIAL_BALANCE?>' || type == '<?php echo LEAVE_CREDIT_LEAVE?>')
		{
			$("#num_of_days_row").removeClass('none');
			$("#date_range").addClass('none');
			$('#leave_start_date').attr('required',false);
			$('#leave_end_date').attr('required',false);
		}
		else
		{			
			$("#date_range").removeClass('none');
			$('#leave_start_date').attr('required',true);
			$('#leave_end_date').attr('required',true);
			$("#num_of_days_row").removeClass('none');
		}
		if(type == '<?php echo LEAVE_FILE_LEAVE?>')
		{
			$("#num_of_days_row").removeClass('none');
			$('#div_no_of_days_wop').removeClass('none');
			$('#div_effective_date').addClass('none');

			$('#no_of_days_wop').attr('required',true);
			$('#effective_date').attr('required',false);
		}
		else
		{
			$("#num_of_days_row").removeClass('none');
			$('#div_effective_date').removeClass('none');
			$('#div_no_of_days_wop').addClass('none');
			
			$('#no_of_days_wop').attr('required',false);
			$('#effective_date').attr('required',true);
		}
		if(type == '<?php echo LEAVE_REVERSE_LEAVE?>')
			{
				$("#approved_leaves_list").removeClass('none');
				$("#num_of_days_row").addClass('none');
				$('#effective_date').attr('required',false);
				$('#leave_earned_used').attr('required',false);
				$("#date_range").addClass('none');
				$('#leave_start_date').attr('required',false);
				$('#leave_end_date').attr('required',false);
			}
			else
			{
				$("#approved_leaves_list").addClass('none');
				$("#num_of_days_row").removeClass('none');
			}
		
	});
	$('#leave_adjustment_form').parsley();
	
 	jQuery(document).off('submit', '#leave_adjustment_form');
	jQuery(document).on('submit', '#leave_adjustment_form', function(e){
	    e.preventDefault();
	    
		if ( $(this).parsley().isValid() ) {
			var data = $('#leave_adjustment_form').serialize();
		  	button_loader('save_adjustment', 1);
		  	var option = {
					url  : $base_url + 'main/leaves/process_employee_leave_adjustment',
					data : data,
					success : function(result){
						if(result.status)
						{
							notification_msg("<?php echo SUCCESS ?>", result.message);
							modal_employee_leave_adjustment.closeModal();
							var post_data = {
											'employee_id':$('#employee_id').val()
								};
							load_datatable('table_employee_leave_list', '<?php echo PROJECT_MAIN ?>/leaves/get_employee_leave_list',false,0,0,true,post_data);
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