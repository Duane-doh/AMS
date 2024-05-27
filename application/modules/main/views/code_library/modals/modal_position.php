<form id="position_form">
	<input type="hidden" name="id" id="id" value="<?php echo !EMPTY($id) ? $id : NULL?>">
	<input type="hidden" name="salt" id="salt" value="<?php echo !EMPTY($salt) ? $salt : NULL?>">
	<input type="hidden" name="token" id="token" value="<?php echo !EMPTY($token) ? $token : NULL?>">
	<input type="hidden" name="action" id="action" value="<?php echo !EMPTY($action) ? $action : NULL?>">
	<input type="hidden" name="module" id="module" value="<?php echo !EMPTY($module) ? $action : NULL?>">
	<div class="form-float-label">
		<div class="row">
			<div class="col s12">
				<div class="input-field">
					<label class="<?php echo $action == ACTION_EDIT ? 'active' :'' ?>" for="position_name">Position Name<span class="required">*</span></label>
					<input type="text" class="validate" required name="position_name" id="position_name" value="<?php echo !EMPTY($position_info['position_name']) ? $position_info['position_name'] : NULL?>" <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>/>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col s6">
				<div class="input-field">
					<label for="position_level" class="active">Level<span class="required">*</span></label>
					<select id="position_level" name="position_level" class="selectize" placeholder="Select Level" required <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>>
					 <option value="">Select Level</option>
					 <?php if (!EMPTY($position_level_name)): ?>
						<?php foreach ($position_level_name as $level): ?>
							<option value="<?php echo $level['position_level_id']?>"><?php echo strtoupper($level['position_level_name']) ?></option>
						<?php endforeach;?>
					<?php endif;?>
					</select>
				</div>
		 	</div>
			<div class="col s6">
				<div class="input-field">
					<label for="position_class" class="active">Class<span class="required">*</span></label>
					<select id="position_class" name="position_class" class="selectize" placeholder="Select Class" required <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>>
					 <option value="">Select Class</option>
					 <?php if (!EMPTY($position_class_level_name)): ?>
						<?php foreach ($position_class_level_name as $class): ?>
							<option value="<?php echo $class['position_class_level_id']?>"><?php echo strtoupper($class['position_class_level_name']) ?></option>
						<?php endforeach;?>
					<?php endif;?>
					</select>
				</div>
		 	</div>
		</div>
		<div class="row">
			<div class="col s6">
				<div class="input-field">
					<label for="salary_grade" class="active">Salary Grade<span class="required">*</span></label>
					<select id="salary_grade" name="salary_grade" class="selectize" placeholder="Select Grade" required <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>>
					 <option value="">Select Grade</option>
					 <?php if (!EMPTY($salary_grade)): ?>
						<?php foreach ($salary_grade as $grade): ?>
							<option value="<?php echo $grade['salary_grade']?>"><?php echo $grade['salary_grade'] ?></option>
						<?php endforeach;?>
					<?php endif;?>
					</select>
				</div>
		 	</div>
			<div class="col s6">
				<div class="input-field">
					<label for="salary_step" class="active">Step Increment<span class="required">*</span></label>
					<select id="salary_step" name="salary_step" class="selectize" placeholder="Select Step" required <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>>
					 <option value="">Step Increment</option>
					 <?php if (!EMPTY($salary_step)): ?>
						<?php foreach ($salary_step as $step): ?>
							<option value="<?php echo $step['salary_step']?>"><?php echo $step['salary_step'] ?></option>
						<?php endforeach;?>
					<?php endif;?>
					</select>
				</div>
		 	</div>
		</div>
		<div class="row">
		    <div class="col s3">
			  	<div class="input-field">
			  		<label class="<?php echo $action == ACTION_EDIT ? 'active' :'' ?>" for="education">Education<span class="required">*</span></label>
					<input type="text" class="validate" required name="education" id="education" value="<?php echo !EMPTY($position_info['education']) ? $position_info['education'] : NULL?>" <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>/>				
		      	</div>
		    </div>
		    <div class="col s3">
		    	<div class="input-field">
			  		<label class="<?php echo $action == ACTION_EDIT ? 'active' :'' ?>" for="experience">Experience<span class="required">*</span></label>
					<input type="text" class="validate" required name="experience" id="experience" value="<?php echo !EMPTY($position_info['experience']) ? $position_info['experience'] : NULL?>" <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>/>				
		      	</div>
		    </div>
		     <div class="col s3">
		    	<div class="input-field">
			  		<label class="<?php echo $action == ACTION_EDIT ? 'active' :'' ?>" for="training">Training<span class="required">*</span></label>
					<input type="text" class="validate" required name="training" id="training" value="<?php echo !EMPTY($position_info['training']) ? $position_info['training'] : NULL?>" <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>/>				
		      	</div>
		    </div>
		    <div class="col s3">
		    	<div class="input-field">
			  		<label class="<?php echo $action == ACTION_EDIT ? 'active' :'' ?>" for="eligibility">Eligibility<span class="required">*</span></label>
					<input type="text" class="validate" required name="eligibility" id="eligibility" value="<?php echo !EMPTY($position_info['eligibility']) ? $position_info['eligibility'] : NULL?>" <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>/>				
		      	</div>
		    </div>
	  	</div>
		<div class="row m-n">
		    <div class="col s12">
			  <div class="input-field" class="active">
			  	<label for="general_function">General Function</label>
				<textarea type="text" name="general_function" id="general_function" class="materialize-textarea"><?php echo !EMPTY($position_info['general_function']) ? $position_info['general_function'] : NULL?></textarea>				
		      </div>
		    </div>
	  	</div>
	  	<div class="row m-n">
		    <div class="col s12">
			  <div class="input-field">
			  	<input type="hidden" name="position_duty_id" id="position_duty_id" value="<?php echo !EMPTY($position_info['position_duty_id']) ? $position_info['position_duty_id'] : NULL?>">
			  	<label for="duties">Duties</label>
			  	<br><br>
				<textarea type="text" name="duties" id="duties" class="materialize-textarea"><?php echo !EMPTY($position_info['duties']) ? $position_info['duties'] : NULL?></textarea>				
		      </div>
		    </div>
	  	</div>
		<div class='row switch p-md b-b-n'>
			<label>
				Inactive
				<input name='active_flag' type='checkbox' value='Y' <?php echo ($position_info['active_flag'] == "Y") ? "checked" : "" ?> <?php echo $action == ACTION_ADD ? 'checked' :'' ?> <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>> 
				<span class='lever'></span>Active
			</label>
		</div>
	</div>
	<div class="md-footer default">
	  	<?php if($action != ACTION_VIEW):?>
	  		<a class="waves-effect waves-teal btn-flat cancel_modal">Cancel</a>
		    <button class="btn btn-success " id="save_position" value="<?php echo BTN_SAVE ?>"><?php echo BTN_SAVE ?></button>
	  	<?php endif; ?>
	</div>
</form>

<script>
$(function (){
	CKEDITOR.replace('duties');
	$('#position_form').parsley();
	$('#position_form').submit(function(e) {
	    e.preventDefault();

	    $('#duties').val(CKEDITOR.instances['duties'].getData());

		if ( $(this).parsley().isValid() ) {
			var data = $(this).serialize();
		  	button_loader('save_position', 1);
		  	var option = {
					url  : $base_url + 'main/code_library_hr/position/process',
					data : data,
					success : function(result){
						if(result.status)
						{
							notification_msg("<?php echo SUCCESS ?>", result.msg);
							modal_position.closeModal();
							load_datatable('position_table', '<?php echo PROJECT_MAIN ?>/code_library_hr/position/get_position_list',false,0,0,true);
						}
						else
						{
							notification_msg("<?php echo ERROR ?>", result.msg);
						}	
						
					},
					
					complete : function(jqXHR){
						button_loader('save_position', 0);
					}
			};

			General.ajax(option);    
	    }
  	});

  	<?php if($action != ACTION_ADD){ ?>
		$('.input-field label').addClass('active');
  	<?php } ?>
})
</script>