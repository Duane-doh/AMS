<form id="work_calendar_form">
	<input type="hidden" name="id" id="id" value="<?php echo !EMPTY($id) ? $id : NULL?>">
	<input type="hidden" name="salt" id="salt" value="<?php echo !EMPTY($salt) ? $salt : NULL?>">
	<input type="hidden" name="token" id="token" value="<?php echo !EMPTY($token) ? $token : NULL?>">
	<input type="hidden" name="action" id="action" value="<?php echo !EMPTY($action) ? $action : NULL?>">
	<input type="hidden" name="module" id="module" value="<?php echo !EMPTY($module) ? $action : NULL?>">
	<div class="form-float-label">
		<div class="row">
		  	<div class="col s6">
				<div class="input-field">
					<label for="holiday_type" class="active">Holiday Type<span class="required">*</span></label>
			   		<select id="holiday_type" name="holiday_type" class="selectize" placeholder="Select Holiday Type" required <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>>
						<option value="">Select Holiday Type</option>
						<?php if (!EMPTY($holiday_type_name)): ?>
							<?php foreach ($holiday_type_name as $holiday): ?>
								<option value="<?php echo $holiday['holiday_type_id'] ?>"><?php echo strtoupper($holiday['holiday_type_name']) ?></option>
							<?php endforeach;?>
						<?php endif;?>
					</select>
				</div>
		  	</div>
			<div class="col s6">
				<div class="input-field">
					<input type="text" class="validate" required name="title" id="title" value="<?php echo isset($work_calendar_info['title']) ? $work_calendar_info['title'] : NULL; ?>" <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>/>
			   		<label class="<?php echo $action == ACTION_EDIT ? 'active' :'' ?>" for="title">Event Title<span class="required">*</span></label>
				</div>
		  	</div>
		</div>
		<div class="row">
		  	<div class="col s6">
				<div class="input-field">
					<input type="text" class="validate" required name="description" id="description" value="<?php echo isset($work_calendar_info['description']) ? $work_calendar_info['description'] : NULL; ?>" <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>/>
			   		<label class="<?php echo $action == ACTION_EDIT ? 'active' :'' ?>" for="description">Description<span class="required">*</span></label>
				</div>
		  	</div>
		 	<div class="col s6">
				<div class="input-field">
					<input type="text" class="validate datepicker" required name="holiday_date" id="holiday_date" placeholder="YYYY/MM/DD"
				   		   onkeypress="format_identifications('<?php echo DATE_FORMAT ?>',this.value,event,'holiday_date')"
						   value="<?php echo isset($work_calendar_info['holiday_date']) ? format_date($work_calendar_info['holiday_date']) : NULL; ?>" <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>/>
			   		<label class="<?php echo $action == ACTION_EDIT ? 'active' :'' ?> active" for="holiday_date">Date<span class="required">*</span></label>
				</div>
		  	</div>
		</div>
		<div class="row">
		  <div class="col s6">
			<div class="input-field">
			  	<input class="timepicker" id="start_time" name="start_time" type="text"
			  	value="<?php echo isset($work_calendar_info['start_time']) ? $work_calendar_info['start_time'] : NULL; ?>" <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>/>
			    <label for="start_time">From</label>
			</div>
		  </div>
		  <div class="col s6">
			<div class="input-field">
			  	<input class="timepicker" id="end_time" name="end_time" type="text"
			  	value="<?php echo isset($work_calendar_info['end_time']) ? $work_calendar_info['end_time'] : NULL; ?>" <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>/>
			    <label for="end_time">To</label>
			</div>
		  </div>
		</div>
	</div>
	<div class="md-footer default">
	  	<?php if($action != ACTION_VIEW):?>
	  		<a class="waves-effect waves-teal btn-flat cancel_modal" id="cancel_work_calendar">Cancel</a>
		    <button class="btn btn-success " id="save_work_calendar" value="<?php echo BTN_SAVE ?>"><?php echo BTN_SAVE ?></button>
	  	<?php endif; ?>
	</div>
</form>
<script>
$(function (){
	$('#work_calendar_form').parsley();
	$('#work_calendar_form').submit(function(e) {
	    e.preventDefault();
	    
		if ( $(this).parsley().isValid() ) {
			var data = $(this).serialize();
		  	button_loader('save_work_calendar', 1);
		  	var option = {
					url  : $base_url + 'main/code_library_ta/work_calendar/process',
					data : data,
					success : function(result){
						if(result.status)
						{
							notification_msg("<?php echo SUCCESS ?>", result.msg);
							modal_work_calendar.closeModal();
							load_datatable('work_calendar_table', '<?php echo PROJECT_MAIN ?>/code_library_ta/work_calendar/get_work_calendar_list');
							$('#calendar').fullCalendar('refetchEvents');
						}
						else
						{
							notification_msg("<?php echo ERROR ?>", result.msg);
						}	
						
					},
					
					complete : function(jqXHR){
						button_loader('save_work_calendar', 0);
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