<form id="attendance_period_form">
	<input type="hidden" name="id" value="<?php echo $id ?>"/>
	<input type="hidden" name="salt" value="<?php echo $salt ?>"/>
	<input type="hidden" name="token" value="<?php echo $token ?>"/>
	<input type="hidden" name="action" value="<?php echo $action ?>"/>
	<input type="hidden" name="module" value="<?php echo $module ?>"/>
	<div class="form-float-label">
		<div class="row m-n">
			<div class="col s12">
				<div class="input-field">
					 <label for="payroll_type" class="active">Payroll Type</label>
					 <select id="payroll_type"  name="payroll_type" <?php echo $action==ACTION_VIEW ? 'disabled' : '' ?> class="selectize" placeholder="Select Payroll Type" required>
						<option value="">Select Payroll type</option>	
						 <?php if (!EMPTY($payroll_types)): ?>
							<?php foreach ($payroll_types as $type): ?>
								<option value="<?php echo $type['payroll_type_id'] ?>"><?php echo $type['payroll_type_name'] ?></option>
							<?php endforeach;?>
						<?php endif;?>		
				 	 </select>
				</div>
			</div>
		</div>
		<div class="row m-n">
			<div class="col s6">
				<div class="input-field">
					<label for="period_from" class="active">Period From</label>
					<input id="period_from" name="period_from" type="text" class="validate datepicker_start" required value="<?php echo isset($period_detail['date_from']) ? format_date($period_detail['date_from']) : ''?>">
				</div>
			</div>
			<div class="col s6">
				<div class="input-field">
					<label for="period_to" class="active">Period To</label>
					<input id="period_to" name="period_to" type="text" class="validate datepicker_end" required value="<?php echo isset($period_detail['date_to']) ? format_date($period_detail['date_to']) : ''?>">
				</div>
			</div>
		</div>
		<!-- marvin : include employee selection : start -->
		<!-- disabled
		<div class="row m-n">
			<div class="col s12">
				<div class="input-field">
					<label for="employee_list" class="active">Excluded Employee(s)</label>
					<select id="employee_list" name="employee_list[]" class="selectize" multiple>
						<option value="">Select Employee</option>
						<?php //if(!empty($users)): ?>
							<?php //$ex_emp = explode(',', $period_detail['excluded_employee']); ?>
							<?php //foreach($users as $emp): ?>
								<option value="<?php //echo $emp['employee_id']; ?>" <?php //echo in_array($emp['employee_id'], $ex_emp) ? 'selected' : ''; ?>><?php //echo $emp['username']; ?></option>
							<?php //endforeach; ?>
						<?php //endif; ?>
					</select>
				</div>
			</div>
		</div>
		-->
		<!-- marvin : include employee selection : end -->
	</div>		
	<div class="md-footer default">
		<a class="waves-effect waves-teal btn-flat cancel_modal">Cancel</a>
	    <button id="save_attendance_period" class="btn btn-success">Save</button>
	</div>
</form>
<script>
$(document).ready(function(){
	$('#attendance_period_form').parsley();
	jQuery(document).off('submit', '#attendance_period_form');
	jQuery(document).on('submit', '#attendance_period_form', function(e){
	    e.preventDefault();
	    
		if ( $(this).parsley().isValid() ) {
			var data = $('#attendance_period_form').serialize();
		  	button_loader('save_attendance_period', 1);
		  	var option = {
					url  : $base_url + 'main/attendance_period/process_attendance_period',
					data : data,
					success : function(result){
						if(result.status)
						{
							notification_msg("<?php echo SUCCESS ?>", result.message);
							modal_attendance_period.closeModal();
							load_datatable('table_attendance_periods', '<?php echo PROJECT_MAIN ?>/attendance_period/get_attendance_period_list',false,0,0,true);
						}
						else
						{
							notification_msg("<?php echo ERROR ?>", result.message);
						}	
						
					},
					
					complete : function(jqXHR){
						button_loader('save_attendance_period', 0);
					}
			};

			General.ajax(option);    
	    }
  	});
});
</script>