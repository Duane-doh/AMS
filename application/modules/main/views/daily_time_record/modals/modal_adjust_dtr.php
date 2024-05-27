<form id="role_form">
	<div class="form-float-label">
		<div class="row">
		  <div class="col s6">
			<div class="input-field">
			  	<input class="timepicker" id="time_in" type="text"/>
			    <label for="time_in">Time in</label>
			</div>
		  </div>
		  <div class="col s6">
			<div class="input-field">
			  	<input class="timepicker" id="time_out" type="text"/>
			    <label for="time_out">Time out</label>
			</div>
		  </div>
		</div>
		<div class="row">
		  <div class="col s6">
			<div class="input-field">
			  	<input class="timepicker" id="break_in" type="text"/>
			    <label for="break_in">Break in</label>
			</div>
		  </div>
		  <div class="col s6">
			<div class="input-field">
			  	<input class="timepicker" id="break_out" type="text"/>
			    <label for="break_out">Break out</label>
			</div>
		  </div>
		</div>
	</div>
</form>
<div class="md-footer default">
	<a class="waves-effect waves-teal btn-flat cancel_modal" id="cancel_edit_dtr">Cancel</a>  <?php //if($this->permission->check_permission(MODULE_ROLE, ACTION_SAVE)):?>
    <button class="btn btn-success " id="save_attendance_correction" value="<?php echo BTN_SAVE ?>"><?php echo BTN_SAVE ?></button>
  <?php //endif; ?>
</div>