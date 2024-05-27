<form id="manual_adjustment_form">
	<input type="hidden" name="id" value="<?php echo $id ?>"/>
	<input type="hidden" name="salt" value="<?php echo $salt ?>"/>
	<input type="hidden" name="token" value="<?php echo $token ?>"/>
	<input type="hidden" name="action" value="<?php echo $action ?>"/>
	<input type="hidden" name="module" value="<?php echo $module ?>"/>
	<input type="hidden" name="attendance_date"  value="<?php echo isset($attendance['time_in']) ? date('Y-m-d',strtotime($attendance['time_in'])) : "" ?>"/>
	<div class="row m-t-sm m-b-n">
		<div class="row p-l-sm p-md"><label class="font-semibold font-lg"><?php echo isset($attendance['time_in']) ? date('l, F d, Y ',strtotime($attendance['time_in'])) : "" ?></label></div>	
	</div>
	<div class="form-float-label">
		<div class="row b-light-gray b-t">
			<div class="col s6">
				<div class="input-field">
				  <input id="time_in" name="time_in" <?php echo $action ==ACTION_VIEW ? 'disabled' : '' ?> type="text" class="validate timepicker" value="<?php echo isset($attendance['time_in']) ? date('h:i A',strtotime($attendance['time_in'])) : "" ?>">
				  <label for="time_in">Time In <span class="required"> * </span></label>
				</div>
			 </div>	
			 <div class="col s6">
				<div class="input-field">
				  <input id="break_out" name="break_out" <?php echo $action ==ACTION_VIEW ? 'disabled' : '' ?> type="text" class="validate timepicker" value="<?php echo isset($attendance['break_out']) ? date('h:i A',strtotime($attendance['break_out'])) : "" ?>">
				  <label for="break_out">Break Out <span class="required"> * </span></label>
				</div>
			 </div>	
		</div>
		<div class="row">
			<div class="col s6">
				<div class="input-field">
				  <input id="break_in" name="break_in"  <?php echo $action ==ACTION_VIEW ? 'disabled' : '' ?> type="text" class="validate timepicker" value="<?php echo isset($attendance['break_in']) ? date('h:i A',strtotime($attendance['break_in'])) : "" ?>">
				  <label for="break_in">Break In <span class="required"> * </span></label>
				</div>
			 </div>	
			 <div class="col s6">
				<div class="input-field">
				  <input id="time_out" name="time_out" <?php echo $action ==ACTION_VIEW ? 'disabled' : '' ?> type="text" class="validate timepicker" value="<?php echo isset($attendance['time_out']) ? date('h:i A',strtotime($attendance['time_out'])) : "" ?>">
				  <label for="time_out">Time Out <span class="required"> * </span></label>
				</div>
			 </div>	
		</div>
		<?php if($action == ACTION_EDIT):?>
		<div class="row">
			<div class="col s12">
				<div class="input-field">
				  	<label for="specific_details">Specific Details <span class="required"> * </span></label>
      				<textarea name="specific_details" class="materialize-textarea" id="specific_details" value=""></textarea>
				</div>
			 </div>	
		</div>
		<?php endif;?>
	</div>	
	<?php if($action != ACTION_VIEW):?>
	<div class="md-footer default">
		<a class="waves-effect waves-teal btn-flat cancel_modal">Cancel</a>
	    <button class="btn"  id="save_adjustment">Submit</button>
	 </div>
	 <?php endif;?>
</form>
<script>
$(document).ready(function(){
	$('.input-field label').addClass('active');
	$('#manual_adjustment_form').parsley();
	jQuery(document).off('submit', '#manual_adjustment_form');
	jQuery(document).on('submit', '#manual_adjustment_form', function(e){
	    e.preventDefault();
	    
		if ( $(this).parsley().isValid() ) {
			var data = $('#manual_adjustment_form').serialize();
		  	button_loader('save_adjustment', 1);
		  	var option = {
					url  : $base_url + 'main/employee_dtr/process_manual_adjustment',
					data : data,
					success : function(result){
						if(result.status)
						{
							notification_msg("<?php echo SUCCESS ?>", result.message);
							modal_employee_dtr.closeModal();
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