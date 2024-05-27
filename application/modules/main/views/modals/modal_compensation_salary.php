<form>
<div class="form-float-label">
	<div class="row">
	  <div class="col s6">
		<div class="input-field">
		  <label for="status" class="active">Status</label>
			<select id="status" name="status" <?php echo $action_id==ACTION_VIEW ? 'disabled' : '' ?> class="selectize" placeholder="Select Status">
				<option value="">Select Status</option>
				<option value="1">Step 1</option>
				<option value="2">Step 2</option>
				<option value="4">Step 3</option>
			</select>
		</div>
	  </div>
	  <div class="col s6">
		<div class="input-field">
		  <label for="position" class="active">Position</label>
			 <select id="position" name="position" <?php echo $action_id==ACTION_VIEW ? 'disabled' : '' ?> class="selectize" placeholder="Select Position">
				<option value="">Select Position</option>
				<option value="1">Utility Worker I</option>
				<option value="2">Floor Manager I</option>
				<option value="4">Software Engineer</option>
			 </select>
		</div>
	  </div>
	</div>
	<div class="row">
	  <div class="col s6">
		<div class="input-field">
		  <label for="salary_grade" class="active">Salary Grade</label>
			<select id="salary_grade" name="salary_grade" <?php echo $action_id==ACTION_VIEW ? 'disabled' : '' ?> class="selectize" placeholder="Select Salary Grade">
				<option value="">	/option>
				<option value="1">Grade 1</option>
				<option value="2">Grade 2</option>
				<option value="4">Grade 3</option>
			</select>
		</div>
	  </div>
	  <div class="col 6">
		<div class="input-field">
		  <label for="salary_grade" class="active">Pay Step</label>
		  <select id="salary_grade" name="salary_grade" <?php echo $action_id==ACTION_VIEW ? 'disabled' : '' ?> class="selectize" placeholder="Select Pay Step">
			<option value="">Select Salary Grade</option>
			<option value="1">Step 1</option>
			<option value="2">Step 2</option>
			<option value="4">Step 3</option>
		  </select>
		</div>
	  </div>
	</div>
	<div class="row">
	  <div class="col s6">
		<div class="input-field">
		  <input id="monthly_salary" name="monthly_salary" <?php echo $action_id==ACTION_VIEW ? 'disabled' : '' ?> type="text" class="validate">
		  <label for="monthly_salary">Monthly Salary</label>
		</div>
	  </div>
	  <div class="col s6">
		<div class="input-field">
		  <input id="annual_salary" name="annual_salary" <?php echo $action_id==ACTION_VIEW ? 'disabled' : '' ?> type="text" class="validate">
		  <label for="annual_salary">Annual Salary</label>
		</div>
	  </div>
	</div>
	<div class="row">
	  <div class="col s6">
		<div class="input-field">
		  <label for="number_hours" class="active">Total Number of Hours</label>
		  <input id="number_hours" name="number_hours" value="24" <?php //echo $action_id==ACTION_VIEW ? 'disabled' : '' ?> disabled type="text" class="validate">
		</div>
	  </div>
	  <div class="col s6">
		<div class="input-field">
		   <label for="number_days" class="active">Total Number of Days</label>
		  <input id="number_days" name="number_days" value="360" <?php //echo $action_id==ACTION_VIEW ? 'disabled' : '' ?> disabled type="text" class="validate">
		 
		</div>
	  </div>
	</div>
	<div class="row">
	  <div class="col s6">
		<div class="input-field">
		  <label for="appointment_type" class="active">Appointment Type</label>
			<select id="appointment_type" name="appointment_type" <?php echo $action_id==ACTION_VIEW ? 'disabled' : '' ?> class="selectize" placeholder="Select Appointment Type">
				<option value="">Select Appointment</option>
				<option value="1">Grade 1</option>
				<option value="2">Grade 2</option>
				<option value="4">Grade 3</option>
			</select>
		</div>
	  </div>
	  <div class="col s6">
		<div class="input-field">
		  <label for="plantilla" class="active">Plantilla</label>
			<select id="plantilla" name="plantilla" <?php echo $action_id==ACTION_VIEW ? 'disabled' : '' ?> class="selectize" placeholder="Select Plantilla">
				<option value="">Select Plantilla</option>
				<option value="1">Step 1</option>
				<option value="2">Step 2</option>
				<option value="4">Step 3</option>
			</select>
		</div>
	  </div>
	</div>
	<div class="row">
	  <div class="col s6">
		<div class="input-field">
		  <label for="station" class="active">Station</label>
			<select id="station" name="appointment_type" <?php echo $action_id==ACTION_VIEW ? 'disabled' : '' ?> class="selectize" placeholder="Select Station">
				<option value="">Select Station</option>
				<option value="1">Grade 1</option>
				<option value="2">Grade 2</option>
				<option value="4">Grade 3</option>
			</select>
		</div>
	  </div>
	  <div class="col s6">
		<div class="input-field">
		  <label for="place_assign" class="active">Place of Assignment</label>
			<select id="place_assign" name="place_assign" <?php echo $action_id==ACTION_VIEW ? 'disabled' : '' ?> class="selectize" placeholder="Select Place of Assignment">
				<option value="">Select Place of Assignment</option>
				<option value="1">Step 1</option>
				<option value="2">Step 2</option>
				<option value="4">Step 3</option>
			</select>
		</div>
	  </div>
	</div>
</div>
</form>
<div class="right-align m-b-sm p-r-sm">
		<a class="waves-effect waves-teal btn-flat">Cancel</a>
	<?php IF ($action_id != ACTION_VIEW): ?>
		<button class="btn " type="submit" name="action">Save</button>
	<?php ENDIF; ?>
</div>