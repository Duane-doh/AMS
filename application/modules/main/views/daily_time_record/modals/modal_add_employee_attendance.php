<?php
	//marvin
	$min_date = format_date($fltr_dtr_start, 'Y/m/d');
	$max_date = format_date($fltr_dtr_end, 'Y/m/d');
	
	//disabled dates
	$disabledDates = array();
	foreach($dates as $dts)
	{
		$disabledDates[] = format_date($dts, 'Y/m/d');
	}
	
	$json_disabledDates = json_encode($disabledDates);
?>
<form id="add_attendance_form">
	<input type="hidden" name="id" value="<?php echo $id ?>"/>
	<input type="hidden" name="salt" value="<?php echo $salt ?>"/>
	<input type="hidden" name="token" value="<?php echo $token ?>"/>
	<input type="hidden" name="action" value="<?php echo $action ?>"/>
	<input type="hidden" name="module" value="<?php echo $module ?>"/>	
	<div class="form-float-label">
		<div class="row m-n">		
			<div class="col s6">
				<div class="input-field">
				  	<input class="datepicker" id="attendance_date" name="attendance_date" type="text" value="<?php echo date('Y/m/d');?>"required onkeypress="format_identifications('<?php echo DATE_FORMAT ?>',this.value,event,'attendance_date')"/>
				    <label for="attendance_date" class="active">Attendance Date <span class="required"> * </span></label>
				</div>
		  	</div>			
			<div class="col s6">
				<div class="input-field">
				    <label for="attendance_status_id" class="active">Type<span class="required">*</span></label>
					<select id="attendance_status_id" name="attendance_status_id"  required class="selectize" placeholder="Select Type">
						<option value="">Select Type</option>
						<?php if (!EMPTY($attendance_status)): ?>
							<?php foreach ($attendance_status as $type): ?>
								<option value="<?php echo $type['attendance_status_id'] ?>"><?php echo strtoupper($type['attendance_status_name']) ?></option>
							<?php endforeach;?>
						<?php endif;?>
					</select>
			    </div>
			</div>
		</div>
		<div class="row time_logs_div">
		  <div class="col s6">
			<div class="input-field time_logs_input_div">
			  	<input class="datetimepicker attendance_input" id="time_in" name="time_in" type="text" />
			    <label for="time_in">Time in </label>
			</div>
		  </div>
		  <div class="col s6">
			<div class="input-field time_logs_input_div">
			  	<input class="datetimepicker attendance_input" id="break_out" name="break_out" type="text" />
			    <label for="break_out">Break out</label>
			</div>
		  </div>
		</div>
		<div class="row time_logs_div">
		  <div class="col s6">
			<div class="input-field time_logs_input_div">
			  	<input class="datetimepicker attendance_input" id="break_in" name="break_in" type="text" />
			    <label for="break_in">Break in</label>
			</div>
		  </div>
		  <div class="col s6">
			<div class="input-field time_logs_input_div">
			  	<input class="datetimepicker attendance_input" id="time_out" name="time_out" type="text" />
			    <label for="time_out">Time out</label>
			</div>
		  </div>
		</div>
		<div class="row">
	      <div class="col s12">
	        <div class="input-field">
	          <label for="remarks">Remarks <span class="required"> * </span></label>
	          <textarea id="remarks" name="remarks" required class="materialize-textarea"></textarea>
	        </div>
	      </div>
    	</div>
	</div>
<div class="md-footer default">
	<a class="waves-effect waves-teal btn-flat cancel_modal">Cancel</a>
    <button type="button" class="btn-flat  green white-text" id="save_new_attendance" value="Save">Save and Add New</button>
    <button type="button" class="btn btn-success " id="save_attendance" value="Save">Save</button>
</div>
</form>

<script> 
$(document).ready(function(){

	//marvin
	$("#attendance_date").datetimepicker({
		
		value			: <?php echo '"'.$min_date.'"'; ?>,
		startDate		: <?php echo '"'.$min_date.'"'; ?>,
		minDate			: <?php echo '"'.$min_date.'"'; ?>,
		maxDate			: <?php echo '"'.$max_date.'"'; ?>,
		format_date		: 'Y/m/d',
		disabledDates	: <?php echo $json_disabledDates; ?>
	});
	
	var OB_flag = <?php echo OFFICIAL_BUSINESS?>;
	var close_modal = 'N';
	
	$('#attendance_status_id, #attendance_date').on('change', function(e){

		var attendance_date = $('#attendance_date').val();
		var attendance_status_id = $('#attendance_status_id').val();
		if(attendance_status_id == OB_flag)
		{
			$('#time_in').val(attendance_date + ' 08:00 AM');
			$('#break_out').val(attendance_date + ' 12:00 PM');
			$('#break_in').val(attendance_date + ' 01:00 PM');
			$('#time_out').val(attendance_date + ' 05:00 PM');
			$('.time_logs_div').css("visibility", "hidden");
			$('.time_logs_input_div').css("display", "none");
		}
		else{
			//marvin
			// $('.attendance_input').datetimepicker({
			// 	datepicker : false
			// }).val(attendance_date + ' 00:00 AM');
			
			
			// $('.attendance_input').val(attendance_date + ' 00:00 AM');

			// davcorrea : change default value : 11/06/2023: START
			$('#time_in').val(attendance_date + ' 00:00 AM');
			$('#break_out').val(attendance_date + ' 00:00 PM');
			$('#break_in').val(attendance_date + ' 00:00 PM');
			$('#time_out').val(attendance_date + ' 00:00 PM');
			$('.time_logs_div').css("visibility", "visible");
			$('.time_logs_input_div').css("display", "block");
			// davcorrea : END
		}
		$('.input-field label').addClass('active');
	});

	$('#save_attendance').on('click', function(e){
		close_modal = 'Y';
		$('#add_attendance_form').trigger('submit');
	});
	$('#save_new_attendance').on('click', function(e){
		close_modal = 'N';
		$('#add_attendance_form').trigger('submit');
	});
	$('#add_attendance_form').parsley();
	jQuery(document).off('submit', '#add_attendance_form');
	jQuery(document).on('submit', '#add_attendance_form', function(e){
	    e.preventDefault();
	    
		if ( $(this).parsley().isValid() ) {
			
			//marvin
			var attendanceDate = $("#attendance_date").val();
			var disabledDates = <?php echo $json_disabledDates; ?>;
			var checkInputDate = disabledDates.includes(attendanceDate);
			//marvin
			if(!checkInputDate)
			{
				var data = $('#add_attendance_form').serialize();
				var process_url = "";
				<?php if($module == MODULE_PORTAL_DAILY_TIME_RECORD):?>
					process_url = $base_url + 'main/employee_dtr/process_add_attendance';
				<?php else: ?>
					process_url = $base_url + 'main/employee_attendance/process_add_attendance';
				<?php endif; ?>
				button_loader('save_attendance', 1);
				button_loader('save_new_attendance', 1);
				var option = {
						url  : process_url,
						data : data,
						success : function(result){
							if(result.status)
							{
								notification_msg("<?php echo SUCCESS ?>", result.message);
								if(close_modal == 'Y')
								{
									modal_add_employee_attendance.closeModal();
								}
								
								var date_from = $('#fltr_dtr_start').val();
								var date_to   = $('#fltr_dtr_end').val();   
								var action    = $('#action').val();   
								var id        = $('#id').val();   
								var token     = $('#token').val();   
								var salt      = $('#salt').val();   
								var module    = $('#module').val(); 
								$('#tab_content').load('<?php echo base_url() . PROJECT_MAIN ."/employee_attendance/get_time_logs/"?>'+action+'/'+id+'/'+token+'/'+salt+'/'+module+'/'+ dateFormat(date_from, 'yyyy-m-d') + '/' + dateFormat(date_to, 'yyyy-m-d'))
							
							}
							else
							{
								notification_msg("<?php echo ERROR ?>", result.message);
							}	
							
						},
						
						complete : function(jqXHR){
							button_loader('save_attendance', 0);
							button_loader('save_new_attendance', 0);
						}
				};

				General.ajax(option);    
			}
			else
			{
				notification_msg("<?php echo ERROR ?>", '<b>Attendance Date:</b> ' + attendanceDate + ' already exist');
			}
			
	    }
  	});
});
</script>