<form id="monthly_leave_credit_form">	
	<input type="hidden" name="id" id = "leave_type_id" value="<?php echo $id ?>"/>
	<input type="hidden" name="salt" value="<?php echo $salt ?>"/>
	<input type="hidden" name="token" value="<?php echo $token ?>"/>
	<input type="hidden" name="action" value="<?php echo $action ?>"/>
	<input type="hidden" name="module" value="<?php echo $module ?>"/>
	<div class="row b-n p-t-lg p-l-md"><span class="font-lg font-spacing-15"><?php echo isset($leave_type)? $leave_type:""; ?></span></div>
	  
	<div class="form-float-label">
		<div class="row m-n b-t b-light-gray">
			<div class="col s12">
			  <div class="input-field">
			  	<label for="month_year" class="active">Select Month</label>
				<select id="month_year" name="month_year" class="selectize" placeholder="Select Month">
					<option value="">Select Month</option>
					<?php if (!EMPTY($credits_dropdown)): ?>
						<?php foreach ($credits_dropdown as $type): ?>
							<option value="<?php echo $type['month_id'] ?>"><?php echo $type['label'] ?></option>
						<?php endforeach;?>
					<?php endif;?>
				</select> 
		      </div>
		    </div>
		</div>	
	</div>
<div class="md-footer default">
	<a class="waves-effect waves-teal btn-flat cancel_modal">Cancel</a>
    <button class="btn btn-success  green" id="save_leave_credit" value="<?php echo BTN_SAVE ?>"><?php echo BTN_SAVE ?></button>
</div>
</form>
<script>
$(document).ready(function(){

	
	$('#monthly_leave_credit_form').parsley();
	
 	jQuery(document).off('submit', '#monthly_leave_credit_form');
	jQuery(document).on('submit', '#monthly_leave_credit_form', function(e){
	    e.preventDefault();
	    
		if ( $(this).parsley().isValid() ) {
			var data = $('#monthly_leave_credit_form').serialize();
		  	button_loader('save_leave_credit', 1);
		  	var option = {
					url  : $base_url + 'main/leaves/process_monthly_leave_credit',
					data : data,
					success : function(result){
						if(result.status)
						{
							notification_msg("<?php echo SUCCESS ?>", result.message);
							modal_add_monthly_leave_credit.closeModal();
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
						button_loader('save_leave_credit', 0);
					}
			};

			General.ajax(option);    
	    }
  	});
});
</script>