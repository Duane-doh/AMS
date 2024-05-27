<form id="deduction_type_form">
	<input type="hidden" name="id" id="id" value="<?php echo !EMPTY($id) ? $id : NULL?>">
	<input type="hidden" name="salt" id="salt" value="<?php echo !EMPTY($salt) ? $salt : NULL?>">
	<input type="hidden" name="token" id="token" value="<?php echo !EMPTY($token) ? $token : NULL?>">
	<input type="hidden" name="action" id="action" value="<?php echo !EMPTY($action) ? $action : NULL?>">
	<input type="hidden" name="module" id="module" value="<?php echo !EMPTY($module) ? $action : NULL?>">

	<div class="form-float-label">
		<div class="row m-n">
			<div class="col s12">
					<div class="input-field">
						<label for="start_date">Start Date</label>
						<input id="start_date" name="start_date" type="text" class="datetimepicker">
					</div>
				</div>
		</div>
	</div>				
	
	<div class="form-float-label">	
		<div class="row m-n">
			<div class="col s6">
				<div class="input-field">
					<label for="Office" class="active">Office</label>
			   			<select id="Office" name="Office" class="selectize" placeholder="Select Office">
							<option value="">Select Office</option>
							<option value="1">Office 1</option>
							<option value="2">Office 2</option>
						</select>
				</div>
			</div>
			<div class="col s6">
				<div class="input-field">
					<label for="Level" class="active">Level</label>
			   			<select id="Level" name="level" class="selectize" placeholder="Select Level">
							<option value="">Select Level</option>
							<option value="">Level Level1</option>
							<option value="">Level 2</option>
						</select>
				</div>
			</div>
		</div>
		<div class="row m-n">
			<div class="col s6">
				<div class="input-field">
					<label for="employment_type_id" class="active">Employment Type</label>
			   			<select id="employment_type_id" name="employment_type_id" class="selectize" placeholder="Select Employment Type">
							<option value="">Select Employment Type</option>
							<option value="">Employment Type 1</option>
							<option value="">Employment Type 2</option>
						</select>
				</div>
			</div>
			<div class="col s6">
				<div class="input-field">
					<div class="input-field">
					<label for="salary_grade_id" class="active">Salary Grade</label>
			   			<select id="salary_grade_id" name="salary_grade_id" class="selectize" placeholder="Select Salary Gradee">
							<option value="">Select Salary Grade</option>
							<option value="1">Salary Grade 1</option>
							<option value="2">Salary Grade 2</option>
						</select>
				</div>
				</div>
			</div>
		</div>
	</div>
			<!-- Switch -->
	</div>
	<div class="md-footer default">
		<a class="waves-effect waves-teal btn-flat cancel_modal" id="cancel_deduction_type">Cancel</a>
		<button class="btn btn-success " id="save_deduction_type" value="<?php echo BTN_SAVE ?>"><?php echo BTN_SAVE ?></button>
	</div>
</form>
<script>
$(function (){
	$('#deduction_type_form').parsley();
	$('#deduction_type_form').submit(function(e) {
	    e.preventDefault();
	    
		if ( $(this).parsley().isValid() ) {
			var data = $(this).serialize();
		  	button_loader('save_deduction_type', 1);
		  	var option = {
					url  : $base_url + 'main/code_library/process_deduction_type',
					data : data,
					success : function(result){
						if(result.status)
						{
							notification_msg("<?php echo SUCCESS ?>", result.msg);
							$("#cancel_deduction_type").trigger('click');
							load_datatable('deduction_type_table', '<?php echo PROJECT_MAIN ?>/code_library/get_deduction_type_list',false,0,0,true);
						}
						else
						{
							notification_msg("<?php echo ERROR ?>", result.msg);
						}	
						
					},
					
					complete : function(jqXHR){
						button_loader('save_deduction_type', 0);
					}
			};

			General.ajax(option);    
	    }
  	});
})
</script>