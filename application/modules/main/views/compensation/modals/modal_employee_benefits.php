<form id="employee_benefit_form">
	<input type="hidden" name="id" id="id" value="<?php echo !EMPTY($id) ? $id : NULL?>">
	<input type="hidden" name="employee_id" id="employee_id" value="<?php echo !EMPTY($employee_id) ? $employee_id : NULL?>">
	<input type="hidden" name="salt" id="salt" value="<?php echo !EMPTY($salt) ? $salt : NULL?>">
	<input type="hidden" name="token" id="token" value="<?php echo !EMPTY($token) ? $token : NULL?>">
	<input type="hidden" name="action" id="action" value="<?php echo !EMPTY($action) ? $action : NULL?>">
	<input type="hidden" name="module" id="module" value="<?php echo !EMPTY($module) ? $module : NULL?>">

	<div class="form-float-label">
		<div class="row">
			<div class="col s12">
				<div class="input-field">
					<label class="active" for="compensation_id">Benefit Name<span class="required">*</span></label>
					<select id="compensation_id" name="compensation_id" class="selectize" placeholder="Select Benefits" <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>>
					 <option value="">Select Benefit</option>
					 <?php if (!EMPTY($benefit_types)): ?>
						<?php foreach ($benefit_types as $benefit): ?>
							<option value="<?php echo $benefit['compensation_id']?>"><?php echo $benefit['compensation_name'] ?></option>
						<?php endforeach;?>
					<?php endif;?>
					</select>
				</div>
			</div>
		</div>

		<div class="row">
			
			<div class="col s6">
				<div class="input-field">
					<input type="text" class="validate" required="" aria-required="true" name="compensation_code" id="compensation_code" value="<?php echo isset($benefit_info['compensation_code']) ? $benefit_info['compensation_code'] : NULL; ?>" <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>/>
					<label class="active" for="compensation_code">Benefit Code</label>
				</div>
			</div>
		
			<div class="col s6">
				<div class="input-field">
					<input type="text" class="validate" required="" aria-required="true" name="compensation_code" id="compensation_type" value="<?php echo isset($benefit_info['compensation_code']) ? $benefit_info['compensation_code'] : NULL; ?>" <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>/>
					<label class="active" for="compensation_type">Benefit Type</label>
				</div>
			</div>
		
		</div>

		<div class="row">
			<div class="col s6">
				<div class="input-field">
					<label for="start_date" class="<?php echo $action != ACTION_ADD ? 'active' :'' ?>" >Start Date<span class="required">*</span></label>
					<input id="start_date" name="start_date" type="text" class="datepicker" value="<?php echo isset($benefit_info['start_date']) ? $benefit_info['start_date'] : NULL; ?>">
				</div>
			</div>
			<div class="col s6">
				<div class="input-field">
					<label for="end_date" class="<?php echo $action != ACTION_ADD ? 'active' :'' ?>" >End Date</label>
					<input id="end_date" name="end_date" type="text" class="datepicker" value="<?php echo isset($benefit_info['end_date']) ? $benefit_info['end_date'] : NULL; ?>">
				</div>
			</div>
		</div>
		<div class="none" id="fix_group" class="row m-t-md">
			<div class="form-float-label" >
				<div class="row row b-t b-b-n b-light-gray m-n">
				 	<div class="col s12">
						<div class="input-field">
						 	<input type="text" aria-required="true" name="amount" id="amount" value="<?php echo isset($benefit_info['amount']) ? $benefit_info['amount'] : NULL; ?>" <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>/>
							<label  class="active" for="amount">Amount</label>
						</div>
					</div>
				 </div>
			</div>
		</div>

		<div class="none" id="variable_group" class="row m-t-md">
			<div class="form-float-label">
				<div class="row row b-t b-b-n b-light-gray m-n">
					<div class="col s6">
						<div class="input-field">
							<label for="multiplier" class="active">Base Multiplier</label>
							<select id="multiplier" name="multiplier" class="selectize" placeholder="Select Base Multiplier" disabled ?>>
							 <option value="">Select Base Multiplier</option>
							 <?php if (!EMPTY($multipliers)): ?>
								<?php foreach ($multipliers as $multiplier): ?>
									<option value="<?php echo $multiplier['multiplier_id']?>"><?php echo $multiplier['multiplier_name'] ?></option>
								<?php endforeach;?>
							<?php endif;?>
							</select>
						</div>
				 	</div>
					<div class="col s6">
						<div class="input-field">
							<input type="text" aria-required="true" name="rate" id="rate" value="<?php echo isset($compensation_info['rate']) ? $compensation_info['rate'] : NULL; ?>" <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>/>
							<label class="active" for="rate">Rate</label>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			
			<div class="col 12">
				<div class="input-field">
					<label for="frequency" class="active">Frequency</label>
					<select  disabled id="frequency" name="frequency" class="selectize" placeholder="Select Frequency">
					 <option value="">Select Frequency</option>
					 <?php if (!EMPTY($frequency)): ?>
						<?php foreach ($frequency as $dt): ?>
							<option value="<?php echo $dt['frequency_id']?>"><?php echo $dt['frequency_name'] ?></option>
						<?php endforeach;?>
					<?php endif;?>
					</select>
				</div>
			</div>
			
		</div>
		
	</div>
	<div class="md-footer default">
		<a class="waves-effect waves-teal btn-flat cancel_modal" id="cancel_benefit">Cancel</a>
	  <?php //if($this->permission->check_permission(MODULE_ROLE, ACTION_SAVE)):?>
		    <button class="btn btn-success " id="save_benefit" value="<?php echo BTN_SAVE ?>"><?php echo BTN_SAVE ?></button>
	  <?php //endif; ?>
	</div>
</form>
<script>
$(function (){
<?php if($action != ACTION_ADD) :?>

		$('#compensation_id').trigger('change');
<?php endif; ?>
	$('#employee_benefit_form').parsley();
	$('#employee_benefit_form').submit(function(e) {
	    e.preventDefault();
	    
		if ( $(this).parsley().isValid() ) {
			var data = $(this).serialize();
		  	button_loader('save_benefit', 1);
		  	var option = {
					url  : $base_url + 'main/compensation/process_employee_benefit',
					data : data,
					success : function(result){
						if(result.status)
						{
							notification_msg("<?php echo SUCCESS ?>", result.msg);
							$("#cancel_benefit").trigger('click');
							var employee_id = $('#employee_id').val();
							var post_data = {'employee_id' : employee_id};
						
							load_datatable('table_employe_benefit_list', '<?php echo PROJECT_MAIN ?>/compensation/get_employee_compensation_list/',false,0,0,true,post_data);
						}
						else                                        
						{
							notification_msg("<?php echo ERROR ?>", result.msg);
						}	
						
					},
					
					complete : function(jqXHR){
						button_loader('save_benefit', 0);
					}
			};

			General.ajax(option);    
	    }
  	});

	var benefit_name = $('#compensation_id').selectize();
	var frequency = $('#frequency').selectize();
	frequency[0].selectize.disable();

	benefit_name.selectize().on('change', function() {
		var value = benefit_name[0].selectize.getValue();
		// $('#compensation_code').trigger('click');

		$.ajax({
			url : $base_url + '<?php echo PROJECT_MAIN ?>/compensation/get_benefits_data/' + value,
			method: 'GET',
			dataType: 'json',
			success: function(r) { 
				$('#compensation_code').val(r.compensation_code);

				$('#rate').val(r.rate);
				$('#base_multiplier').val(r.base_multiplier);
				if(r.compensation_type_flag == 'F'){
					$('#compensation_type').val('Fixed');
					$('#amount').val(r.amount);
					$('#variable_group').addClass('none');
	  				$('#fix_group').removeClass('none');
				} 
				if(r.compensation_type_flag == 'V'){
					$('#compensation_type').val('Variable');
					$('#variable_group').removeClass('none');
	  				$('#fix_group').addClass('none');
				}
 
				
				frequency[0].selectize.setValue(r.frequency_id);
			}
		})
		
	});

})
</script>