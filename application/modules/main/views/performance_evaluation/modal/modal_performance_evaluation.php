<form id="performance_evaluation_form">
	<input type="hidden" name="id" id="id" value="<?php echo !EMPTY($id) ? $id : NULL?>">
	<input type="hidden" name="salt" id="salt" value="<?php echo !EMPTY($salt) ? $salt : NULL?>">
	<input type="hidden" name="token" id="token" value="<?php echo !EMPTY($token) ? $token : NULL?>">
	<input type="hidden" name="action" id="action" value="<?php echo !EMPTY($action) ? $action : NULL?>">
	<input type="hidden" name="module" id="module" value="<?php echo !EMPTY($module) ? $module : NULL?>">
	<input type="hidden" name="employee_id" id="employee_id" value="<?php echo !EMPTY($employee_id) ? $employee_id : NULL?>">
	<input type="hidden" name="description" id="description_hidden" value="<?php echo !EMPTY($employee_performance_evaluation['rating_description']) ? $employee_performance_evaluation['rating_description'] : '' ?>"/>

	<div class="form-float-label">
		<div class="row">
			<div class="col s6">
				<div class="input-field">
				  	<input id="evaluation_start_date" name="evaluation_start_date" placeholder="YYYY/MM/DD" required 
					  	   onkeypress="format_identifications('<?php echo DATE_FORMAT ?>',this.value,event,'evaluation_start_date')"
					  	   value="<?php echo !EMPTY($employee_performance_evaluation['evaluation_start_date']) ?  format_date($employee_performance_evaluation['evaluation_start_date']) : '' ?>" type="text" class="validate datepicker datepicker_start" >
				    <label class="active" for="evaluation_start_date">Start Date <span class="required">*</span></label>
				</div>
			</div>
			<div class="col s6">
				<div class="input-field">
				  	<input id="evaluation_end_date" name="evaluation_end_date" placeholder="YYYY/MM/DD" required 
					  	   onkeypress="format_identifications('<?php echo DATE_FORMAT ?>',this.value,event,'evaluation_end_date')"
					  	   value="<?php echo !EMPTY($employee_performance_evaluation['evaluation_end_date']) ? format_date($employee_performance_evaluation['evaluation_end_date']) : '' ?>" type="text" class="validate datepicker datepicker_end">
					<label class="active" for="evaluation_end_date">End Date <span class="required">*</span></label>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col s6">
				<div class="input-field">
				  	<input id="rating" name="rating" required  type="text" class="validate"  value="<?php echo !EMPTY($employee_performance_evaluation['rating']) ? $employee_performance_evaluation['rating'] : '' ?>">
				    <label for="rating">Numerical Rating <span class="required">*</span></label>
				</div>
			</div>
			<div class="col s6">
				<div class="input-field">
				  	<input id="description" name="description"  type="text" class="validate" disabled value="<?php echo !EMPTY($employee_performance_evaluation['rating_description']) ? $employee_performance_evaluation['rating_description'] : '' ?>"/>
				    <label for="description" class="active">Adjectival Rating</label>
				</div>
			</div>
		</div>
		<div class="row m-n">
		    <div class="col s6">
				<div class="input-field">
				  	<label for="remarks">Remarks</label>
					<input type="text" name="remarks" id="remarks" class="validate" value="<?php echo !EMPTY($employee_performance_evaluation['remarks']) ? $employee_performance_evaluation['remarks'] : '' ?>">			
			    </div>
		    </div>
			<div class="col s6">
				<div class="input-field">
					<label for="classification_field_id" class="active">Classification<span class="required">*</span></label>
					<select tabindex="19" id="classification_field_id" name="classification_field_id" required class="selectize" placeholder="Select Classification" <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>>
						<option value="">Select Classification</option>
						<?php if (!EMPTY($classifications)): ?>
							<?php foreach ($classifications as $classification): ?>
									<option value="<?php echo $classification['classification_field_id'] ?>"><?php echo $classification['classification_field_name'] ?></option>
							<?php endforeach;?>
						<?php endif;?>
					</select>
				</div>
			</div>
		</div>
	</div>
	<div class="md-footer default">
		<?php if($action != ACTION_VIEW) : ?>
		<a class="waves-effect waves-teal btn-flat cancel_modal">Cancel</a>
	    <button class="btn btn-success" id="modal_save" value="<?php echo BTN_SAVE ?>"><?php echo BTN_SAVE ?></button>
	  <?php endif; ?>
	</div>
</form>

<script>
$(function (){
	<?php if($action != ACTION_ADD){ ?>
		$('.input-field label').addClass('active');
  	<?php } ?>
	// evaluation_start_date
	$('#evaluation_start_date').change(function(){
		$('#evaluation_end_date').val('');
	});

	$('#rating').on('input', function(){
		
		var rating = $(this).val();
	  	$(this).val(rating.replace(/[^0-9\.]+/,''));
  		if(rating > 100)
  		{
  			rating = 100.00;
  			$('#rating').val(rating);
  		}

  	
  		var rating_description = <?php echo json_encode($rating_description) ?>;

  		if(rating == '')
  		{
  			$('#description').val('');
  		}
  		else
  		{
  			var option = {
				url  : $base_url + 'main/performance_evaluation/get_rating_description',
				data : 'rating='+rating,
				success : function(result){
					if(result.status) {
						$('#description').val(result.description);
						$('#description_hidden').val(result.description);
					}
				}
			};

			General.ajax(option);   
		}
	});

	$('#performance_evaluation_form').parsley();
	$('#performance_evaluation_form').submit(function(e) {
	    e.preventDefault();
	    
		if ( $(this).parsley().isValid() ) {
			var data = $(this).serialize();

		  	button_loader('modal_save', 1);
		  	var option = {

					url  : $base_url + 'main/performance_evaluation/process_employee_performance_evaluation',
				
					data : data,
					success : function(result){
						if(result.status)
						{
							notification_msg("<?php echo SUCCESS ?>", result.message);
							modal_performance_evaluation.closeModal();
							load_datatable('table_performance_evaluation', '<?php echo PROJECT_MAIN ?>/performance_evaluation/get_employee_performance_evaluation_list/<?php echo $employee_id ?>/<?php echo $module ?>',false,0,0,true);
						}
						else
						{
							notification_msg("<?php echo ERROR ?>", result.message);
						}	
					},
					complete : function(jqXHR){
						button_loader('modal_save', 0);
					}
			};

			General.ajax(option);    
	    }
  	});
})
// TO ENTIRELY REMOVE THE ASTERISKS(*) AND DISABLE ALL FIELDS IN THIS VIEW
	<?php if($action == ACTION_VIEW) : ?>
		$('span.required').addClass('none');
		$('.validate').attr('disabled','');
	<?php endif; ?>
</script>
