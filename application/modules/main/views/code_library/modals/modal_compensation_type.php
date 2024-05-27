<form id="compensation_type_form">
	<input type="hidden" name="id" id="id" value="<?php echo !EMPTY($id) ? $id : NULL?>">
	<input type="hidden" name="salt" id="salt" value="<?php echo !EMPTY($salt) ? $salt : NULL?>">
	<input type="hidden" name="token" id="token" value="<?php echo !EMPTY($token) ? $token : NULL?>">
	<input type="hidden" name="action" id="action" value="<?php echo !EMPTY($action) ? $action : NULL?>">
	<input type="hidden" name="module" id="module" value="<?php echo !EMPTY($module) ? $action : NULL?>">

	<div class="form-float-label">
		<div class="row">
			<div class="col s6">
				<div class="input-field">
					<input type="text" class="validate" required="" aria-required="true" name="compensation_name" id="compensation_name" value="<?php echo isset($compensation_info['compensation_name']) ? $compensation_info['compensation_name'] : NULL; ?>"/>
					<label class="" for="compensation_name">Compensation Name<span class="required">*</span></label>
				</div>
			</div>
			<div class="col s6">
				<div class="input-field">
					<input type="text" class="validate" required="" aria-required="true" name="compensation_code" id="compensation_code" value="<?php echo isset($compensation_info['compensation_code']) ? $compensation_info['compensation_code'] : NULL; ?>"/>
					<label class="" for="compensation_code">Compensation Code<span class="required">*</span></label>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col s4">
				<div class='switch p-md'>
					Compensation Type<br><br>
				    <label>
				    	Fixed
				        <input class="validate" name='compensation_type_flag' type='checkbox' value='<?php echo COMPENSATION_TYPE_FLAG_VARIABLE ?>' <?php echo ($compensation_info['compensation_type_flag'] == "V") ? "checked" : "" ?>> 
				        <span class='lever'></span>Variable
				    </label>
				</div>
			</div>
			<div class="col s4">
				<div class="input-field">
					<label for="parent_compensation" class="active">Parent Compensation</label>
					<select id="parent_compensation" name="parent_compensation" class="selectize validate" placeholder="Select Parent Compensation">
					 <option value="">Select Parent Compensation</option>
					 <?php if (!EMPTY($parent_compensation)): ?>
						<?php foreach ($parent_compensation as $parent): ?>
							<option value="<?php echo $parent['compensation_id']?>"><?php echo strtoupper($parent['compensation_name']) ?></option>
						<?php endforeach;?>
					<?php endif;?>
					</select>
				</div>
			</div>
			<div class="col s4">
				<div class="input-field">
					<label for="inherit_parent_id_flag" class="active">Inherit Parent Code<span id="inherit_req" class="required">*</span></label>
					<select id="inherit_parent_id_flag" name="inherit_parent_id_flag" class="selectize validate" placeholder="Select Inherit Parent Code Flag" disabled>
					 	<option value="NA" <?php echo ($compensation_info['inherit_parent_id_flag'] == NOT_APPLICABLE) ? "selected" : "" ?>>NOT APPLICABLE</option>
					 	<option value="Y" <?php echo ($compensation_info['inherit_parent_id_flag'] == YES) ? "selected" : "" ?>>YES</option>
					 	<option value="N" <?php echo ($compensation_info['inherit_parent_id_flag'] == NO) ? "selected" : "" ?>>NO</option>
					 </select>
				</div>
		 	</div>
		</div>
		<div class="none" id="amount_div">
			<div class="form-float-label" >
				<div class="row b-t b-b-n b-light-gray m-n">
				 	<div class="col s6">
						<div class="input-field">
						 	<input class="number validate right-align" type="text" name="amount" id="amount" value="<?php echo isset($compensation_info['amount']) ? $compensation_info['amount'] : NULL; ?>"/>
							<label class="" for="amount">Amount<span class="required">*</span></label>
						</div>
					</div>
					<div class="col s6">
						<div class="input-field">
							<label for="frequency" class="active">Frequency<span class="required">*</span></label>
							<select id="frequency" name="frequency" class="selectize validate" placeholder="Select Frequency">
							 <option value="">Select Frequency</option>
							 <?php if (!EMPTY($frequency_name)): ?>
								<?php foreach ($frequency_name as $frequency): ?>
									<option value="<?php echo $frequency['frequency_id']?>"><?php echo strtoupper($frequency['frequency_name']) ?></option>
								<?php endforeach;?>
							<?php endif;?>
							</select>
						</div>
				 	</div>
				 </div>
			</div>
			
		</div>
		<div class="none" id="variable_group">
			<div class="form-float-label">
				<div class="row b-t b-b-n b-light-gray">
					<div class="col s6">
						<div class="input-field">
							<label for="multiplier" class="active">Base Multiplier<span class="required">*</span></label>
							<select id="multiplier" name="multiplier" class="selectize validate" placeholder="Select Base Multiplier">
							 <option value="">Select Base Multiplier</option>
							 <?php if (!EMPTY($multiplier_name)): ?>
								<?php foreach ($multiplier_name as $multiplier): ?>
									<option value="<?php echo $multiplier['multiplier_id']?>"><?php echo strtoupper($multiplier['multiplier_name']) ?></option>
								<?php endforeach;?>
							<?php endif;?>
							</select>
						</div>
				 	</div>
					<div class="col s6">
						<div class="input-field">
							<input class="number validate" type="text" aria-required="true" name="rate" id="rate" value="<?php echo isset($compensation_info['rate']) ? $compensation_info['rate'] : NULL; ?>"/>
							<label class="" for="rate">Rate<span class="required">*</span></label>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="form-float-label">
			<div class="row b-b-n b-t b-light-gray">
			 	<div class="col s4">
					<div class="input-field">
						<label for="tenure_rqmt_flag" class="active">Tenure Requirement</label>
						<select id="tenure_rqmt_flag" name="tenure_rqmt_flag" class="selectize validate" placeholder="Deduction Type">
						 	<option value="NA" <?php echo ($compensation_info['tenure_rqmt_flag'] == TENURE_RQMT_NA) ? "selected" : "" ?>>NOT APPLICABLE</option>
						 	<option value="T" <?php echo ($compensation_info['tenure_rqmt_flag'] == TENURE_RQMT_TENURE) ? "selected" : "" ?>>TENURE</option>
						 	<option value="DP" <?php echo ($compensation_info['tenure_rqmt_flag'] == TENURE_RQMT_DAYS_PRESENT) ? "selected" : "" ?>>DAYS PRESENT</option>
						 </select>
					</div>
				</div>
				<div class="col s4">
					<div class="input-field">
						<input class="validate number" type="text" aria-required="true" name="tenure_rqmt_val" disabled placeholder="Number of Months" id="tenure_rqmt_val" value="<?php echo isset($compensation_info['tenure_rqmt_val']) ? $compensation_info['tenure_rqmt_val'] : NULL; ?>"/>
						<label class="active" for="tenure_rqmt_val">Tenure Requirement Value<span id="tenure_req" class="required">*</span></label>
					</div>
				</div>
				<div class="col s4">
					<div class="input-field">
						<label for="pro_rated_flag" class="active">Pro Rated Flag</label>
						<select id="pro_rated_flag" name="pro_rated_flag" class="selectize validate" placeholder="Select Pro Rated Flag">
						 	<option value="NA" <?php echo ($compensation_info['pro_rated_flag'] == PRO_RATED_FLAG_NA) ? "selected" : "" ?>>NOT APPLICABLE</option>
						 	<option value="T" <?php echo ($compensation_info['pro_rated_flag'] == PRO_RATED_FLAG_TENURE) ? "selected" : "" ?>>TENURE</option>
						 	<option value="SG" <?php echo ($compensation_info['pro_rated_flag'] == PRO_RATED_FLAG_SALARY_GRADE) ? "selected" : "" ?>>SALARY GRADE</option>
						 	<option value="DP" <?php echo ($compensation_info['pro_rated_flag'] == PRO_RATED_FLAG_DAYS_PRESENT) ? "selected" : "" ?>>DAYS PRESENT</option>
						 </select>
					</div>
			 	</div>
			</div>
		</div>
		<div class="row">		
			<div class="col s4">	
				<div class='switch p-md'>
					Deminimis<br><br>
				    <label>
				    	No
				        <input class="validate" name='deminimis_flag' type='checkbox' value='Y' <?php echo ($compensation_info['deminimis_flag'] == "Y") ? "checked" : "" ?>> 
				        <span class='lever'></span>Yes
				    </label>
				</div>
			</div>			
			<div class="col s4">
				<div class='switch p-md'>
					General Payroll<br><br>
				    <label>
				    	No
				        <input name='general_payroll_flag' type='hidden' value='N'>
				        <input class="validate" name='general_payroll_flag' type='checkbox' value='Y' <?php echo ($compensation_info['general_payroll_flag'] == "Y") ? "checked" : "" ?>> 
				        <span class='lever'></span>Yes
				    </label>
				</div> 
			</div>
			<div class="col s4">
				<div class='switch p-md'>
					Special Payroll<br><br>
				    <label>
				    	No
				        <input name='special_payroll_flag' type='hidden' value='N'>
				        <input class="validate" name='special_payroll_flag' type='checkbox' value='Y' <?php echo ($compensation_info['special_payroll_flag'] == "Y") ? "checked" : "" ?>> 
				        <span class='lever'></span>Yes
				    </label>
				</div> 
			</div>			
		</div>

		<div class="row">
			<div class="col s3">
				<div class='switch p-md'>
					Less Absence<br><br>
				    <label>
				        No
				        <input class="validate" name='less_absence_flag' type='checkbox' value='Y' <?php echo ($compensation_info['less_absence_flag'] == "Y") ? "checked" : "" ?> > 
				        <span class='lever'></span>Yes
				    </label>
				</div>
			</div>
			<div class="col s3">
				<div class='switch p-md'>
					Basic Salary<br><br>
				    <label>
				        No
				        <input class="validate" name='basic_salary_flag' type='checkbox' value='Y' <?php echo ($compensation_info['basic_salary_flag'] == "Y") ? "checked" : "" ?> > 
				        <span class='lever'></span>Yes
				    </label>
				</div>
			</div>
			<div class="col s3">
				<div class='switch p-md'>
					Monetization<br><br>
				    <label>
				    	No
				        <input class="validate" name='monetization_flag' type='checkbox' value='Y' <?php echo ($compensation_info['monetization_flag'] == "Y") ? "checked" : "" ?>> 
				        <span class='lever'></span>Yes
				    </label>
				</div> 
			</div>
			<div class="col s3">
				<div class="input-field">
					<input type="text" class="validate" required="" aria-required="true" name="report_short_code" id="report_short_code" value="<?php echo isset($compensation_info['report_short_code']) ? $compensation_info['report_short_code'] : NULL; ?>"/>
					<label class="" for="report_short_code">Report Short Code<span class="required">*</span></label>
				</div>
			</div>
			<!-- <div class="col s4">
				<div class='switch p-md'>
					Other Salary<br><br>
				    <label>
				    	No
				        <input class="validate" name='other_salary_flag' type='checkbox' value='Y' <?php echo ($compensation_info['other_salary_flag'] == "Y") ? "checked" : "" ?> > 
				        <span class='lever'></span>Yes
				    </label>
				</div>
			</div> -->
		</div> 

		<div class="row">
			<div class="col s3">
				<div class='switch p-md'>
					Grant to Employee<span class="required">*</span><br><br>
				    <label>
				        No
				        <input name='employee_flag' id="employee_flag" type='checkbox'   value='Y' <?php echo ($compensation_info['employee_flag'] == "Y") ? "checked" : "" ?> <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>> 
				        <span class='lever'></span>Yes
				    </label>
				</div>
			</div>
			<div class="col s3">
				<div class="input-field">
					<label for="employ_type_flag" class="active">Payroll Type<span class="required">*</span></label>
					<select id="employ_type_flag" name="employ_type_flag" class="selectize" placeholder="Payroll Type" <?php echo (($action == ACTION_VIEW) || ($action == ACTION_ADD) || ($compensation_info['employee_flag'] == NO)) ? 'disabled' :'' ?> required="required">
					 	<option value="ALL" <?php echo ($compensation_info['employ_type_flag'] == PAYROLL_TYPE_FLAG_ALL) ? "selected" : "" ?>>All</option>
					 	<option value="REG" <?php echo ($compensation_info['employ_type_flag'] == PAYROLL_TYPE_FLAG_REG) ? "selected" : "" ?>>Regular</option>
					 	<option value="JO" <?php echo ($compensation_info['employ_type_flag'] == PAYROLL_TYPE_FLAG_JO) ? "selected" : "" ?>>Contract of Sevice</option>
					 	<option value="GIP" <?php echo ($compensation_info['employ_type_flag'] == PAYROLL_TYPE_FLAG_GIP) ? "selected" : "" ?>>Gov't Intern Program</option>
					 </select>
				</div>
		 	</div>
			<!-- <div class="col s4">
				<div class='switch p-md'>
					Grant to Employee<br><br>
				    <label>
				        No
				        <input class="validate" name='employee_flag' type='checkbox' value='Y' <?php echo ($compensation_info['employee_flag'] == "Y") ? "checked" : "" ?>> 
				        <span class='lever'></span>Yes
				    </label>
				</div>
			</div> -->
			<div class="col s3">
				<div class='switch p-md'>
					Taxable<br><br>
				    <label>
				    	No
				        <input class="validate" name='taxable_flag' type='checkbox' value='Y' <?php echo ($compensation_info['taxable_flag'] == "Y") ? "checked" : "" ?>> 
				        <span class='lever'></span>Yes
				    </label>
				</div>
			</div>
			<div class="col s3">
				<div class="input-field">
					<input class="number validate right-align" type="text" aria-required="true" name="taxable_amount" disabled id="taxable_amount" value="<?php echo isset($compensation_info['taxable_amount']) ? $compensation_info['taxable_amount'] : "0.00"; ?>"/>
					<label class="active" for="taxable_amount">Taxable Amount</label>
				</div>
			</div>
		</div>

		<div class='row switch p-md b-b-n'>
		    <label>
		        Inactive
		        <input class="validate" name='active_flag' type='checkbox' value='Y' <?php echo ($compensation_info['active_flag'] == "Y") ? "checked" : "" ?> <?php echo $action == ACTION_ADD ? 'checked' :'' ?> > 
		        <span class='lever'></span>Active
		    </label>
		</div>
	</div><br><br><br><br><br>
	<div class="md-footer default">
	  	<?php if($action != ACTION_VIEW):?>
	  		<a class="waves-effect waves-teal btn-flat cancel_modal" id="cancel_compensation_type">Cancel</a>
		    <button class="btn btn-success " id="save_compensation_type" value="<?php echo BTN_SAVE ?>"><?php echo BTN_SAVE ?></button>
	  	<?php endif; ?>
	</div>
</form>
<script>
$(function (){
	$('#inherit_req').hide();
	$('#tenure_req').hide();
	
	$('#amount_div').removeClass('none');
   	$('#amount').attr('required', true);
   	$('#frequency').attr('required', true);

	$( 'input[name="compensation_type_flag"]').on( "click", function() {
  		var selected = $('input[name="compensation_type_flag"]:checked').val();

  			if (selected == '<?php echo COMPENSATION_TYPE_FLAG_VARIABLE ?>') 
  			{
  				$('#variable_group').removeClass('none');
  				$('#amount_div').addClass('none');
		       	$('#multiplier').attr('required', true);
		       	$('#rate').attr('required', true);
		       	$('#amount').attr('required', false);
		       	$('#frequency').attr('required', false);
  			}

  			else 
  			{
  				$('#variable_group').addClass('none');
  				$('#amount_div').removeClass('none');
		       	$('#multiplier').attr('required', false);
		       	$('#rate').attr('required', false);
		       	$('#amount').attr('required', true);
		       	$('#frequency').attr('required', true);
  			}
	});

	$('#tenure_rqmt_flag').on( "change", function() {
  		var selected = $(this).val();

  			if (selected == '<?php echo TENURE_RQMT_NA ?>') 
  			{
  				$('#tenure_rqmt_val').attr("disabled", true);
  				$('#tenure_rqmt_val').attr("placeholder", "Not applicable");
  				$('#tenure_rqmt_val').removeAttr("required");
  				$('#tenure_req').hide();
  			}
  			if (selected == '<?php echo TENURE_RQMT_TENURE ?>')
  			{
  				$('#tenure_rqmt_val').attr("disabled", false);
  				$('#tenure_rqmt_val').attr("placeholder", "Number of Months");
  				$('#tenure_rqmt_val').attr("required", "required");
  				$('#tenure_req').show();
  			}
  			if (selected == '<?php echo TENURE_RQMT_DAYS_PRESENT ?>')
  			{
  				$('#tenure_rqmt_val').attr("disabled", false);
  				$('#tenure_rqmt_val').attr("placeholder", "Number of Days");
  				$('#tenure_rqmt_val').attr("required", "required");
  				$('#tenure_req').show();
  			}
	});

	$('#parent_compensation').on( "change", function() {
  		var selected = $(this).val();

  			if (selected) 
  			{
  				$('#inherit_parent_id_flag')[0].selectize.destroy();
				$('#inherit_parent_id_flag').attr("disabled", false);
				$('#inherit_parent_id_flag').selectize();
				$("#inherit_parent_id_flag")[0].selectize.clear();
				$('#inherit_parent_id_flag').attr("required", "required");
  				$('#inherit_req').show();
  			}
  			else
  			{
  				$('#inherit_parent_id_flag')[0].selectize.destroy();
				$('#inherit_parent_id_flag').attr("disabled", true);
				$('#inherit_parent_id_flag').selectize();
				$("#inherit_parent_id_flag")[0].selectize.clear();
				$('#inherit_parent_id_flag').removeAttr("required");
  				$('#inherit_req').hide();
  			}
	});

	$( 'input[name="taxable_flag"]').on( "click", function() {
  		var selected = $('input[name="taxable_flag"]:checked').val();

  			if (selected == 'Y') 
  			{  				
  				$('#taxable_amount').attr("disabled", false);
  			}
  			else 
  			{
  				$('#taxable_amount').attr("disabled", true);
  				$('#taxable_amount').val('');
  			}
	});

	$('#compensation_type_form').parsley();
	$('#compensation_type_form').submit(function(e) {
	    e.preventDefault();
	    
		if ( $(this).parsley().isValid() ) {
			var data = $(this).serialize();
		  	button_loader('save_compensation_type', 1);
		  	var option = {
					url  : $base_url + 'main/code_library_payroll/compensation_type/process',
					data : data,
					success : function(result){
						if(result.status)
						{
							notification_msg("<?php echo SUCCESS ?>", result.msg);
							modal_compensation_type.closeModal();
							load_datatable('compensation_type_table', '<?php echo PROJECT_MAIN ?>/code_library_payroll/compensation_type/get_compensation_type_list',false,0,0,true);
						}
						else
						{
							notification_msg("<?php echo ERROR ?>", result.msg);
						}	
						
					},
					
					complete : function(jqXHR){
						button_loader('save_compensation_type', 0);
					}
			};

			General.ajax(option);    
	    }
  	});

  	$('#employee_flag').on('change', function(){
		var selectize = $('#employ_type_flag')[0].selectize;
		if($(this).is(':checked')){
			selectize.enable();
		}else{
			selectize.setValue('<?php echo PAYROLL_TYPE_FLAG_ALL; ?>');
			selectize.disable();
		}
	});

	<?php if($action != ACTION_ADD){ ?>
		$('.input-field label').addClass('active');
		<?php if ($compensation_info['tenure_rqmt_flag'] != "NA"):?>
			$('#tenure_rqmt_val').attr("disabled", false);
		<?php endif;?>
		
		<?php if ($compensation_info['compensation_type_flag'] == COMPENSATION_TYPE_FLAG_VARIABLE):?>
			$('#variable_group').removeClass('none');
			$('#amount_div').addClass('none');
	       	$('#multiplier').attr('required', true);
	       	$('#rate').attr('required', true);
	       	$('#amount').attr('required', false);
	       	$('#frequency').attr('required', false);
		<?php endif;?>
  	<?php } ?>

	// TO ENTIRELY REMOVE THE ASTERISKS(*) AND DISABLE ALL FIELDS IN THIS VIEW
  	<?php if($action == ACTION_VIEW) : ?>
		$('span.required').addClass('none');
		$('.validate').attr('disabled','');
	<?php endif; ?>
})
</script>