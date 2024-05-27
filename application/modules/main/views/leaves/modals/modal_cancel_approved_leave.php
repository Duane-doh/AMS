<form id="leave_adjustment_form">	
	<input type="hidden" name="id" id = "leave_type_id" value="<?php echo $id ?>"/>
	<input type="hidden" name="salt" value="<?php echo $salt ?>"/>
	<input type="hidden" name="token" value="<?php echo $token ?>"/>
	<input type="hidden" name="action" value="<?php echo $action ?>"/>
	<input type="hidden" name="module" value="<?php echo $module ?>"/>
	<input type="hidden" name="employee_id" id = "employee_id" value="<?php echo $employee_id ?>"/>	
	<input type="hidden" name="effective_date" id = "effective_date" value=""/>	
	<input type="hidden" name="transaction_type" id = "transaction_type" value="<?php echo LEAVE_REVERSE_LEAVE ?>"/>	

	<div class="form-float-label">
		<div class="row b-n p-t-lg p-l-md p-b-md">
			<span class="font-lg font-playfair-display font-spacing-15"><?php echo isset($leave_type)? $leave_type:""; ?></span>
		</div>			
		<div id="approved_leaves_list">
			<div class="form-float-label">
				<div class="row m-n  b-b-n b-t b-light-gray" >
					<div class="col s12">
			  			<div class="input-field">
			  				<label for="approved_leaves" class="active">Date of Approved Leave</label>
							<select id="approved_leaves" name="approved_leaves" class="selectize" placeholder="Select Approved Leave">
								<option value="">Select Approved Leave</option>
								<?php 
									foreach ($approved_leaves as $approved_leave)
									{
										echo "<option value='".$approved_leave['leave_detail_id']."'>". $approved_leave['leave_start_date_str'] . " - " . $approved_leave['leave_end_date_str'] ."</option>";
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
							modal_cancel_approved_leave.closeModal();
							var post_data = {
											'leave_type_id':$('#leave_type_id').val()
								};
							load_datatable('table_leave_type_adjustment', '<?php echo PROJECT_MAIN ?>/leaves/get_leave_type_employee_list',false,0,0,true,post_data);
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