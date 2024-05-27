<form id="form_generate_attendance" class="p-b-md">
	<div class="form-float-label" >
		<div class="row m-n">				
			<div class="col s6">
				<div class="input-field">
					<input type="text" class="datepicker_start" id="date_range_from" name="date_range_from" autocomplete="off"/>
					<label for="date_range_from" class="active">Start Date <span class="required"> * </span></label>
				</div>
			</div>
			<div class="col s6">
				<div class="input-field">
					<input type="text" class="datepicker_end" id="date_range_to" name="date_range_to" autocomplete="off"/>
					<label for="date_range_to" class="active">End Date <span class="required"> * </span></label>
				</div>
			</div>
		</div>
	</div>
	<div class="form-float-label">
		<div class="row">
		<div class="col">
			<div class="input-field">
				<label for="employee" class="active">Employee<span class="required">*</span></label>
				<select id="employee" name="employee" class="selectize" placeholder="Select employee" required <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>>
					<option value="">Select Employee</option>
					 <?php if (!EMPTY($employee_list)): ?>
						<?php foreach ($employee_list as $employee): ?>
							<option value="<?php echo $employee['employee_id']?>"><?php echo strtoupper($employee['fullname']) ?></option>
						<?php endforeach;?>
					<?php endif;?>
					</select>
				</div>
		 	</div>
		</div>
	</div>		
	<div class="md-footer default">
		<a class=" btn-flat cancel_modal">Cancel</a>
		<button class="btn"  id="Generate">Generate</button>
	 </div>
</form>
<script> 
$(document).ready(function(){
	$('.input-field label').addClass('active');
	$('#form_generate_attendance').parsley();
	jQuery(document).off('submit', '#form_generate_attendance');
	jQuery(document).on('submit', '#form_generate_attendance', function(e){
	    e.preventDefault();
	    
		if ( $(this).parsley().isValid() ) {
			var data = $('#form_generate_attendance').serialize();
		  	button_loader('Generate', 1);
		  	var option = {
					url  : "<?php echo base_url() . PROJECT_MAIN ?>/biometric_logs/process_generate_employee_attendance_period_dtl",
					data : data,
					success : function(result){
						if(result.status)
						{
							notification_msg("<?php echo SUCCESS ?>", result.message);
							modal_generate_attendance.closeModal();
						}
						else
						{
							notification_msg("<?php echo ERROR ?>", result.message);
						}	
						
					},
					
					complete : function(jqXHR){
						button_loader('Generate', 0);
					}
			};

			General.ajax(option);    
	    }
  	});
});
</script>