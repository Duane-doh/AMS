<form id="update_attendance_form">
	<input type="hidden" name="id" id="id" value="<?php echo $id ?>"/>
	<input type="hidden" name="salt" value="<?php echo $salt ?>"/>
	<input type="hidden" name="token" value="<?php echo $token ?>"/>
	<input type="hidden" name="action" value="<?php echo $action ?>"/>
	<input type="hidden" name="module" value="<?php echo $module ?>"/>	
	<div class="form-float-label">		
		<div class="row">
		  <div class="col s6">
			<div class="input-field">
			  	<input class="timepicker" id="time_in" name="time_in" type="text" value="<?php echo isset($attendance['time_in']) ? $attendance['time_in']:''?>"/>
			    <label for="time_in">Time in</label>
			</div>
		  </div>
		  <div class="col s6">
			<div class="input-field">
			  	<input class="timepicker" id="break_out" name="break_out" type="text" value="<?php echo isset($attendance['break_out']) ? $attendance['break_out']:''?>"/>
			    <label for="break_out">Break out</label>
			</div>
		  </div>
		</div>
		<div class="row">
		  <div class="col s6">
			<div class="input-field">
			  	<input class="timepicker" id="break_in" name="break_in" type="text" value="<?php echo isset($attendance['break_in']) ? $attendance['break_in']:''?>"/>
			    <label for="break_in">Break in</label>
			</div>
		  </div>
		  <div class="col s6">
			<div class="input-field">
			  	<input class="timepicker" id="time_out" name="time_out" type="text" value="<?php echo isset($attendance['time_out']) ? $attendance['time_out']:''?>"/>
			    <label for="time_out">Time out</label>
			</div>
		  </div>
		</div>
	</div>
<div class="md-footer default">
	<a class="waves-effect waves-teal btn-flat cancel_modal" id="cancel_edit_dtr">Cancel</a>
    <button class="btn btn-success " id="save_attendance" value="Save">Save</button>
</div>
</form>

<script> 
$(document).ready(function(){
	$('.input-field label').addClass('active');
	$('#update_attendance_form').parsley();
	jQuery(document).off('submit', '#update_attendance_form');
	jQuery(document).on('submit', '#update_attendance_form', function(e){
	    e.preventDefault();
	    
		if ( $(this).parsley().isValid() ) {
			var data = $('#update_attendance_form').serialize();
		  	button_loader('save_attendance', 1);
		  	var option = {
					url  : $base_url + 'main/daily_time_record/process_update_attendance',
					data : data,
					success : function(result){
						if(result.status)
						{
							notification_msg("<?php echo SUCCESS ?>", result.message);
							modal_update_attendance_breakdown.closeModal();
							var post_data = {
											'employee_attendance_id':$('#id').val()
								};
							load_datatable('table_attendance_breakdown', '<?php echo PROJECT_MAIN ?>/daily_time_record/get_employee_attendance_breakdown',false,0,0,true,post_data);
						}
						else
						{
							notification_msg("<?php echo ERROR ?>", result.message);
						}	
						
					},
					
					complete : function(jqXHR){
						button_loader('save_attendance', 0);
					}
			};

			General.ajax(option);    
	    }
  	});
});
</script>