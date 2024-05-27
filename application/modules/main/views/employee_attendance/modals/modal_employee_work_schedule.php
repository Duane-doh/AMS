
<form id="work_schedule_form">
	<input type="hidden" name="id" id="id" value="<?php echo !EMPTY($id) ? $id : NULL?>">
	<input type="hidden" name="salt" id="salt" value="<?php echo !EMPTY($salt) ? $salt : NULL?>">
	<input type="hidden" name="token" id="token" value="<?php echo !EMPTY($token) ? $token : NULL?>">
	<input type="hidden" name="action" id="action" value="<?php echo !EMPTY($action) ? $action : NULL?>">
	<input type="hidden" name="module" id="module" value="<?php echo !EMPTY($module) ? $module : NULL?>">
	<input type="hidden" name="employee_id" id="employee_id" value="<?php echo !EMPTY($employee_id) ? $employee_id : NULL?>">

	<div class="form-float-label">
		<div class="row">
			<div class="col s6">
				<div class="input-field">
					<label for="schedule_type" class="active">Work Schedule<span class="required">*</span></label>
					<select id="schedule_type" name="schedule_type" class="selectize" placeholder="Select Work Schedule" required <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>>
						<option value="">Select Work Schedule</option>
						<?php if (!EMPTY($work_schedules)): ?>
							<?php foreach ($work_schedules as $type): ?>
									<option value="<?php echo $type['work_schedule_id'] ?>"><?php echo $type['work_schedule_name'] ?></option>
							<?php endforeach;?>
						<?php endif;?>
					</select>
				</div>
			</div>
			<div class="col s6">
				<div class="input-field">
					<label for="effective_date">Effectivity Date<span class="required">*</span></label>
					<input type="text" class="validate datepicker" required name="effective_date" id="effective_date" value="<?php echo isset($sched_info['start_date']) ? format_date($sched_info['start_date']) : NULL?>" <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?> onkeypress="format_identifications('<?php echo DATE_FORMAT ?>',this.value,event,'effective_date')"/>
				</div>
			</div>
		</div>
	</div>
	<div class="md-footer default">
	  	<?php if($action != ACTION_VIEW):?>
	  		<a class="waves-effect waves-teal btn-flat cancel_modal">Cancel</a>
		    <button class="btn btn-success " id="save_work_schedule" value="<?php echo BTN_SAVE ?>"><?php echo BTN_SAVE ?></button>
	  	<?php endif; ?>
	</div>
</form>

<script>
$(function (){
	$('#work_schedule_form').parsley();
	$('#work_schedule_form').submit(function(e) {
	    e.preventDefault();
	    
		if ( $(this).parsley().isValid() ) {
			var data = $(this).serialize();
		  	button_loader('save_work_schedule', 1);
		  	var option = {
					url  : $base_url + 'main/employee_work_schedule/process_work_schedule',
					data : data,
					success : function(result){
						if(result.status)
						{
							notification_msg("<?php echo SUCCESS ?>", result.msg);
							modal_employee_work_schedule.closeModal();
							var post_data = {
											'employee_id':$('#employee_id').val()
								};
							load_datatable('employee_work_schedule_table', '<?php echo PROJECT_MAIN ?>/employee_work_schedule/get_work_schedule/',false,0,0,true,post_data);
						}
						else
						{
							notification_msg("<?php echo ERROR ?>", result.msg);
						}	
						
					},
					
					complete : function(jqXHR){
						button_loader('save_work_schedule', 0);
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