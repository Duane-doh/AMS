<form id="service_record_form">
	<input type="hidden" name="id" id="id" value="<?php echo !EMPTY($id) ? $id : NULL?>">
	<input type="hidden" name="salt" id="salt" value="<?php echo !EMPTY($salt) ? $salt : NULL?>">
	<input type="hidden" name="token" id="token" value="<?php echo !EMPTY($token) ? $token : NULL?>">
	<input type="hidden" name="action" id="action" value="<?php echo !EMPTY($action) ? $action : NULL?>">
	<input type="hidden" name="module" id="module" value="<?php echo !EMPTY($module) ? $module : NULL?>">
	<input type="hidden" name="employee_id" id="employee_id" value="<?php echo !EMPTY($employee_id) ? $employee_id : NULL?>">
	
	<div class="scroll-pane" style="height: 300px">
		<div class="form-float-label">
			<div class="row m-n">
				<div class="col s6">
					<div class="input-field">
						<input id="service_start" name="service_start" value="<?php echo !EMPTY($employee_service_record['service_start']) ?  $employee_service_record['service_start'] : '' ?>" <?php echo $action==ACTION_VIEW ? 'readonly' : '' ?> type="text" class="validate datepicker datepicker_start" >
						<label for="service_start">Service Start Date<span class="required">*</span></label>
					</div>
				</div>
				<div class="col s6">
					<div class="input-field">
						<input id="service_end" name="service_end" readonly value="<?php echo !EMPTY($employee_service_record['service_end']) ? $employee_service_record['service_end'] : '' ?>" <?php echo $action==ACTION_VIEW ? 'readonly' : '' ?> type="text" class="validate datepicker datepicker_end">
						<label for="service_end">Service End Date<span class="required">*</span></label>
					</div>
				</div>
			</div>
			<div class="row m-n">
				<div class="col s5">
					<div class="input-field">
						<label for="position_name" class="active">Position<span class="required">*</span></label>
						<select id="position_name" name="position_name" <?php echo $action==ACTION_VIEW ? 'readonly' : '' ?> class="selectize" placeholder="Select Position">
							<option value="">Select Position</option>	
							<?php foreach($param_position as $row):?>
									<option value="<?php echo $row['position_id']; ?>"><?php echo $row['position_name']; ?></option>
							<?php endforeach; ?>
						</select>
					</div>
				</div>
				<div class="col s4">
					<div class="input-field">
						<label for="employment_type_name" class="active">Employment Status<span class="required">*</span></label>
						<select id="employment_type_name" name="employment_type_name" <?php echo $action==ACTION_VIEW ? 'readonly' : '' ?> class="selectize" placeholder="Select Employment Status">
							<option value="">Select Employment Status</option>
							<?php foreach($param_employment_type as $row):?>
								<option value="<?php echo $row['employment_status_id']; ?>"><?php echo $row['employment_status_name']; ?></option>
							<?php endforeach;?>
						</select>
					</div>
				</div>
				<div class="col s3">
					<div class="input-field">
						<input id="annual_salary" name="annual_salary" value="<?php echo !EMPTY($employee_service_record['annual_salary']) ? $employee_service_record['annual_salary'] : '0' ?>" <?php echo $action==ACTION_VIEW ? 'readonly' : '' ?> type="text" class="number">
						<label for="annual_salary" class="active">Annual Salary<span class="required">*</span></label>
					</div>
				</div>
			</div>
			<div class="row m-n">
				<div class="col s5">
					<div class="input-field">
						<label for="office_name" class="active">Station/Place of Assignment<span class="required">*</span></label>
						<select id="office_name" name="office_name" <?php echo $action==ACTION_VIEW ? 'readonly' : '' ?> class="selectize" placeholder="Select Place of Assignment">
							<option value="">Select Status</option>
							<?php foreach($param_office as $row):?>
								<option value="<?php echo $row['office_id']; ?>"><?php echo $row['office_name']; ?></option>
							<?php endforeach;?>
						</select>
					</div>
				</div>
				<div class="col s3">
					<div class="input-field">
						<label for="branch_name" class="active">Branch<span class="required">*</span></label>
						<select id="branch_name" name="branch_name" <?php echo $action==ACTION_VIEW ? 'readonly' : '' ?> class="selectize" placeholder="Select Branch">
							<option value="">Select Branch</option>
							<?php foreach($param_branch as $row):?>
								<option value="<?php echo $row['branch_id']; ?>"><?php echo $row['branch_name']; ?></option>
							<?php endforeach;?>
						</select>
					</div>
				</div>
				<div class="col s4">
					<div class="input-field">
						<label for="leave_type_name" class="active">L/V ABS W/O PAY</label>
						<select id="leave_type_name" name="leave_type_name" <?php echo $action==ACTION_VIEW ? 'readonly' : '' ?> class="selectize" placeholder="Select Leaves">
							<option value="">Select Station</option>
							<?php foreach($param_leave_type as $row):?>
								<option value="<?php echo $row['leave_type_id']; ?>"><?php echo $row['leave_type_name']; ?></option>
							<?php endforeach;?>
						</select>
					</div>
				</div>
			</div>
			<div class="row m-n hide">
				<div class="col s5">
					<div class="input-field">
						<input id="service_date" name="service_date" value="<?php echo !EMPTY($employee_service_record['service_date']) ? $employee_service_record['service_date'] : '' ?>" <?php echo $action==ACTION_VIEW ? 'readonly' : '' ?> type="text" class="validate datepicker">
						<label for="service_date">Separation Date<span class="required">*</span></label>
					</div>
				</div>
				<div class="col s7">
					<div class="input-field">
						<input id="end_cause" name="end_cause" value="<?php echo !EMPTY($employee_service_record['end_cause']) ? $employee_service_record['end_cause'] : '' ?>" <?php echo $action==ACTION_VIEW ? 'readonly' : '' ?> type="text" class="validate">
						<label for="end_cause">Separation Cause<span class="required">*</span></label>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="md-footer default">
		<a class="waves-effect waves-teal btn-flat cancel_modal">Cancel</a>
		<button class="btn btn-success" id="save_service_record" value="<?php echo BTN_SAVE ?>"><?php echo BTN_SAVE ?></button>
	</div>
</form>

<script>
$(function (){
	<?php if($action != ACTION_ADD){ ?>
		$('.input-field label').addClass('active');
  	<?php } ?>

	$('#service_record_form').parsley();
	$('#service_record_form').submit(function(e) {
	    e.preventDefault();
	    
		if ( $(this).parsley().isValid() ) {
			var data = $(this).serialize();

		  	button_loader('save_service_record', 1);
		  	var option = {

		  		<?php if($module == MODULE_PERSONNEL_PORTAL):?>
					url  : $base_url + 'main/service_record_changes_requests/process_employee_service_record',
				<?php else: ?>
					url  : $base_url + 'main/service_record/process_employee_service_record',
				<?php endif; ?>

					data : data,
					success : function(result){
						if(result.status)
						{
							modal_service_record.closeModal();
							load_datatable('table_employee_service_record', '<?php echo PROJECT_MAIN ?>/service_record/get_employee_service_record/<?php echo $employee_id ?>',false,0,0,true);
						}
						else
						{
							notification_msg("<?php echo ERROR ?>", result.message);
						}	
					},
					complete : function(jqXHR){
						button_loader('save_service_record', 0);
					}
			};

			General.ajax(option);    
	    }
  	});
})
</script>
