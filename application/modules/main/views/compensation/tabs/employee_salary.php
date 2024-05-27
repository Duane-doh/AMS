<form id="benefits_form">
	<input type="hidden" name="employee_id" id="employee_id" value="<?php echo !EMPTY($employee_id) ? $employee_id : NULL?>">
	<input type="hidden" name="salt" id="salt" value="<?php echo !EMPTY($salt) ? $salt : NULL?>">
	<input type="hidden" name="token" id="token" value="<?php echo !EMPTY($token) ? $token : NULL?>">
	<input type="hidden" name="action" id="action" value="<?php echo !EMPTY($action) ? $action : NULL?>">
	<input type="hidden" name="module" id="module" value="<?php echo !EMPTY($module) ? $action : NULL?>">


<div class="form-float-label p-r-md">
	<div class="row b-t b-light-gray">
	  <div class="col s12">
		<div class="input-field">
		  <label for="position_id" class="active">Position</label>
			<input id="position_id" name="position_id" type="text" class="" value="<?php echo isset($employee_salary_info['position_name']) ? $employee_salary_info['position_name'] : ''; ?>" disabled>
		</div>
	  </div>
	  
	</div>
	<div class="row m-n">
	  <div class="col s6">
		<div class="input-field">
		 	<label for="salary_grade" class="active">Salary Grade</label>
			<input id="salary_grade" name="salary_grade" type="text" class="" value="<?php echo isset($employee_salary_info['employ_salary_grade']) ? $employee_salary_info['employ_salary_grade'] : NULL; ?>" disabled>
		</div>
	  </div>
	  <div class="col s6">
		<div class="input-field">
		  <label for="step" class="active"> Pay Step</label>
			<input id="step" name="step" type="text" class="" value="<?php echo isset($employee_salary_info['employ_salary_step']) ? $employee_salary_info['employ_salary_step'] : NULL?>" disabled>
		</div>
	  </div>
	</div>
	<div class="row m-n">
		<div class="col s6">
			<div class="input-field">
				<label for="start_date" class="active">Start Date</label>
				<input id="start_date" name="start_date" type="text" class="datepicker" value="<?php echo isset($employee_salary_info['employ_start_date']) ? format_date($employee_salary_info['employ_start_date']) : NULL; ?>" disabled>
			</div>
		</div>
		<div class="col s6">
			<div class="input-field">
				<label for="end_date" class="active">End Date</label>
				<input id="end_date" name="end_date" type="text" class="datepicker" value="<?php echo isset($employee_salary_info['employ_end_date']) ? format_date($employee_salary_info['employ_end_date']) : NULL; ?>" disabled>
			</div>
		</div>
	</div>
	<div class="row m-n">
	  <div class="col s6">
		<div class="input-field">
		 	<label for="salary_grade" class="active">Monthly Salary</label>
			<input id="salary_grade" name="salary_grade" type="text" style="text-align:right" class="number pull-right" value="<?php echo isset($employee_salary_info['employ_monthly_salary']) ? '&#8369; ' . number_format($employee_salary_info['employ_monthly_salary'],2) : NULL; ?>" disabled>
		</div>
	  </div>
	  <div class="col s6">
		<div class="input-field">
		  <label for="step" class="active">Daily Salary</label>
			<input id="step" name="step" type="text" style="text-align:right" class="number pull-right" value="<?php echo isset($employee_salary_info['employ_monthly_salary']) ? '&#8369; ' . number_format($employee_salary_info['employ_monthly_salary'] / 22,2) : NULL; ?>" disabled>
		</div>
	  </div>
	</div>
</div>
