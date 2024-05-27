<form id="form_employee_daily_mra">
	<input type="hidden" name="id" value="<?php echo $id ?>"/>
	<input type="hidden" name="salt" value="<?php echo $salt ?>"/>
	<input type="hidden" name="token" value="<?php echo $token ?>"/>
	<input type="hidden" name="action" value="<?php echo $action ?>"/>
	<input type="hidden" name="module" value="<?php echo $module ?>"/>
	<input type="hidden" name="attendance_period_hdr_id" id="attendance_period_hdr_id" value="<?php echo $attendance_period_hdr_id ?>"/>
	<input type="hidden" name="employee_id" id="employee_id" value="<?php echo $employee_id ?>"/>
	<input type="hidden" name="basic_hours" id="basic_hours" value="<?php echo $mra_summary['basic_hours'] ?>"/>
	<div class="form-float-label">		

	  	<div class="row m-n">		  	
		    <div class="col s6">
			  	<div class="input-field">
				   	<input type="text" class="validate" name="working_hours" id="working_hours" value="<?php echo isset($mra_summary['working_hours'])? $mra_summary['working_hours'] : '0' ?>" readonly/>
				    <label for="working_hours">Total Hours Worked</label>
			  	</div>
		    </div>
		    <div class="col s6">
				<div class="input-field">
				  	<label for="frequency" class="active">Attendance Status</label>
				  	<select id="frequency" name="attendance_status_id" class="selectize" placeholder="Select Status">
					<option value="">Select Status</option>
					<?php if($status):?>
						<?php foreach($status as $value):?>
						<?php $selected = ($value['attendance_status_id'] == $mra_summary['attendance_status_id']) ? ' selected ':'';?>
						<option value="<?php echo $value['attendance_status_id']?>" <?php echo $selected;?> ><?php echo $value['attendance_status_name'];?></option>
						<?php endforeach;?>
					<?php endif;?>
				  	</select>
				</div>
		  	</div>
	  	</div>
	  	<div class="row m-n">		
		    <div class="col s6">
			  	<div class="input-field">
				   	<input type="text" class="validate" name="tardiness_hours" data-parsley-type="number" id="tardiness_hours" value="<?php echo isset($mra_summary['tardiness_hr'])? round($mra_summary['tardiness_hr'],3) : '0' ?>"/>
				    <label for="tardiness_hours">Late Hour</label>
			  	</div>
		    </div>
		    <div class="col s6">
			  	<div class="input-field">
				   	<input type="text" class="validate" name="tardiness_min" data-parsley-type="number" id="tardiness_hours" value="<?php echo isset($mra_summary['tardiness_min'])? round($mra_summary['tardiness_min'],3) : '0' ?>"/>
				    <label for="tardiness_hours">Late Min</label>
			  	</div>
		    </div>
	  	</div>
	  	<div class="row m-n">	
		    <div class="col s6">
			  	<div class="input-field">
				   	<input type="text" class="validate" name="undertime_hours" data-parsley-type="number" id="undertime_hours" value="<?php echo isset($mra_summary['undertime_hr'])? round($mra_summary['undertime_hr'],3) : '0' ?>"/>
				    <label for="undertime_hours">Undertime Hour</label>
			  	</div>
		    </div>
		    <div class="col s6">
			  	<div class="input-field">
				   	<input type="text" class="validate" name="undertime_min" data-parsley-type="number" id="undertime_hours" value="<?php echo isset($mra_summary['undertime_min'])? round($mra_summary['undertime_min'],3) : '0' ?>"/>
				    <label for="undertime_hours">Undertime Min</label>
			  	</div>
		    </div>
	  	</div>
	</div>
	<div class="md-footer default">
		<a class="waves-effect waves-teal btn-flat cancel_modal">Cancel</a>
	    <button class="btn btn-success " type="submit" id="save_employee_daily_mra" value="SAVING">SAVE</button>
	</div>
</form>
<script>

$(function (){

	
	$("label").addClass('active');

 	jQuery(document).off('submit', '#form_employee_daily_mra');
	jQuery(document).on('submit', '#form_employee_daily_mra', function(e){
	    e.preventDefault();
	    
		if ( $(this).parsley().isValid() ) {
			var data = $('#form_employee_daily_mra').serialize();
			
			var	process_url = $base_url + 'main/attendance_mra/process_employee_daily_mra';
			
		  	button_loader('save_employee_daily_mra', 1);
		  	var option = {
					url  : process_url,
					data : data,
					success : function(result){
						if(result.status)
						{
							notification_msg("<?php echo SUCCESS ?>", result.message);
							modal_update_employee_mra.closeModal();
							var post_data = {
											'attendance_period_hdr_id':$('#attendance_period_hdr_id').val(),
											'employee_id':$('#employee_id').val()
								};
							load_datatable('table_employee_daily_mra', '<?php echo PROJECT_MAIN ?>/attendance_mra/get_employee_daily_mra',false,0,0,true,post_data);
						}
						else
						{
							notification_msg("<?php echo ERROR ?>", result.message);
						}	
						
					},
					
					complete : function(jqXHR){
						button_loader('save_employee_daily_mra', 0);
					}
			};

			General.ajax(option);    
	    }
  	});
})
</script>