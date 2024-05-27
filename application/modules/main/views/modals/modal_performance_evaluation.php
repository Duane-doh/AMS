<form id="performance_evaluation_form">
	<div class="form-float-label">
		<div class="row">
			<div class="col s6">
				<div class="input-field select-icon">
				<!-- <i class="material-icons prefix grey-text">supervisor_account</i> -->
			  	<input id="start_date" name="start_date" value="" type="text" class="validate datepicker" placeholder="YYYY/MM/DD">
			    <label for="start_date">Start Date</label>
				</div>
			</div>
			<div class="col s6">
				<div class="input-field select-icon">
				<!-- <i class="material-icons prefix grey-text">supervisor_account</i> -->
			  	<input id="end_date" name="end_date" value="" type="text" class="validate datepicker" placeholder="YYYY/MM/DD">
			    <label for="end_date">End Date</label>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col s12">
				<div class="input-field select-icon">
				<!-- <i class="material-icons prefix grey-text">supervisor_account</i> -->
			  	<input id="rating" name="rating" value="" type="text" class="validate" >
			    <label for="rating">Rating</label>
				</div>
			</div>
		</div>
	</div>
	<div class="md-footer default">
		<a class="waves-effect waves-teal btn-flat cancel_modal">Cancel</a>
	  <?php //if($this->permission->check_permission(MODULE_ROLE, ACTION_SAVE)):?>
	    <button class="btn btn-success" id="save_salary_grade_steps" value="<?php echo BTN_SAVE ?>"><?php echo BTN_SAVE ?></button>
	  <?php //endif; ?>
	</div>
</form>