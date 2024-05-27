<form id="salary_grade_steps_form">
	<input type="hidden" name="id" id="id" value="<?php echo !EMPTY($id) ? $id : NULL?>">
	<input type="hidden" name="salt" id="salt" value="<?php echo !EMPTY($salt) ? $salt : NULL?>">
	<input type="hidden" name="token" id="token" value="<?php echo !EMPTY($token) ? $token : NULL?>">
	<input type="hidden" name="action" id="action" value="<?php echo !EMPTY($action) ? $action : NULL?>">
	<input type="hidden" name="module" id="module" value="<?php echo !EMPTY($module) ? $module : NULL?>">
  <div class="scroll-pane">
	<div class="form-float-label">
	<div class='switch p-md'>
	    <label>
	        Use for another fund
	        <input name='active_fund' type='hidden'   value='N'>
	        <input name='active_fund' type='checkbox'   value='Y'  <?php echo ($salary_grade_steps['active_fund'] == "Y") ? "checked" : "" ?>> 
	        <span class='lever'></span>
	    </label>
	</div>
	  <div class="row m-n">
	    <div class="col s4">
		  <div class="input-field">
		  	<input id="effectivity_date" name="effectivity_date" value="<?php echo !EMPTY($salary_grade_steps['effectivity_date']) ?  $salary_grade_steps['effectivity_date'] : '' ?>" <?php echo $action_id==ACTION_VIEW ? 'disabled' : '' ?> type="text" class="validate datepicker" >
		    <label for="effectivity_date">Effectivity Date</label>
	      </div>
	    </div>
	    <div class="col s4">
		 <div class="input-field">
		  	<input id="grade_count" name="grade_count" value="<?php echo !EMPTY($salary_grade_steps['grade_count']) ? $salary_grade_steps['grade_count'] : '' ?>" <?php echo $action_id==ACTION_VIEW ? 'disabled' : '' ?> type="text" class="validate">
		    <label for="grade_count">Salary Grade Count</label>
		  </div>
	    </div>
	    <div class="col s4">
		  <div class="input-field">
		  	<input id="steps_count" name="steps_count" value="<?php echo !EMPTY($salary_grade_steps['steps_count']) ? $salary_grade_steps['steps_count'] : '' ?>" <?php echo $action_id==ACTION_VIEW ? 'disabled' : '' ?> type="text" class="validate">
		    <label for="steps_count">Steps Count</label>
		  </div>
	    </div>
	  </div>
	</div>
  </div>
	<div class="md-footer default">
		<a class="waves-effect waves-teal btn-flat cancel_modal">Cancel</a>
	  <?php //if($this->permission->check_permission(MODULE_ROLE, ACTION_SAVE)):?>
	    <button class="btn btn-success btn-success" id="save_salary_grade_steps" value="<?php echo BTN_SAVE ?>"><?php echo BTN_SAVE ?></button>
	  <?php //endif; ?>
	</div>
</form>
<script>
$(function (){
	<?php if($action != ACTION_ADD){ ?>
		$('.input-field label').addClass('active');
  	<?php } ?>


	$('#salary_grade_steps_form').parsley();
	$('#salary_grade_steps_form').submit(function(e) {
	    e.preventDefault();
	    
		if ( $(this).parsley().isValid() ) {
			var data = $(this).serialize();
		  	button_loader('save_salary_grade_steps', 1);
		  	var option = {
					url  : $base_url + 'main/code_library/process_salary_grade_steps',
					data : data,
					success : function(result){
						if(result.status)
						{
							notification_msg("<?php echo SUCCESS ?>", result.message);
							modal_salary_grade_steps.closeModal();
							load_datatable('salary_grade_steps_table', '<?php echo PROJECT_MAIN ?>/code_library/get_salary_grade_steps_list/',false,0,0,true);
						}
						else
						{
							notification_msg("<?php echo ERROR ?>", result.message);
						}	
					},
					complete : function(jqXHR){
						button_loader('save_salary_grade_steps', 0);
					}
			};

			General.ajax(option);    
	    }
  	});
})
</script>
