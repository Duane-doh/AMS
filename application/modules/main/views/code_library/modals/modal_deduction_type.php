<form id="deduction_type_form">
	<input type="hidden" name="id" id="id" value="<?php echo !EMPTY($id) ? $id : NULL?>">
	<input type="hidden" name="salt" id="salt" value="<?php echo !EMPTY($salt) ? $salt : NULL?>">
	<input type="hidden" name="token" id="token" value="<?php echo !EMPTY($token) ? $token : NULL?>">
	<input type="hidden" name="action" id="action" value="<?php echo !EMPTY($action) ? $action : NULL?>">
	<input type="hidden" name="module" id="module" value="<?php echo !EMPTY($module) ? $module : NULL?>">
	<input type="hidden" name="has_ded_mon" id="has_ded_mon" value="1">

	<div class="form-float-label">
		<div class="row">
			<div class="col s4">
				<div class="input-field">
					<input type="text" class="validate" required="" aria-required="true" name="deduction_code" id="deduction_code" value="<?php echo isset($deduction_info['deduction_code']) ? $deduction_info['deduction_code'] : NULL; ?>" <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>/>
					<label class="<?php echo $action == ACTION_EDIT ? 'active' :'' ?>" for="deduction_code">Deduction Type Code<span class="required">*</span></label>
				</div>
			</div>
			<div class="col s4">
				<div class="input-field">
					<input type="text" class="validate" required="" aria-required="true" name="deduction_name" id="deduction_name" value="<?php echo isset($deduction_info['deduction_name']) ? $deduction_info['deduction_name'] : NULL; ?>" <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>/>
					<label class="<?php echo $action == ACTION_EDIT ? 'active' :'' ?>" for="deduction_name">Deduction Type Name<span class="required">*</span></label>
				</div>
			</div>
			<div class="col s4">
				<div class="input-field">
					<label for="deduction_type_flag" class="active">Deduction Type<span class="required">*</span></label>
					<select id="deduction_type_flag" name="deduction_type_flag" class="selectize" placeholder="Deduction Type" <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?> required="required">
					 	<option value="ST" <?php echo ($deduction_info['deduction_type_flag'] == DEDUCTION_TYPE_FLAG_SYSTEM) ? "selected" : "" ?>>SYSTEM</option>
					 	<option value="F" <?php echo ($deduction_info['deduction_type_flag'] == DEDUCTION_TYPE_FLAG_FIXED) ? "selected" : "" ?>>FIXED</option>
					 	<option value="V" <?php echo ($deduction_info['deduction_type_flag'] == DEDUCTION_TYPE_FLAG_VARIABLE) ? "selected" : "" ?>>VARIABLE</option>
					 	<option value="S" <?php echo ($deduction_info['deduction_type_flag'] == DEDUCTION_TYPE_FLAG_SCHEDULED) ? "selected" : "" ?>>SCHEDULE</option>
					 </select>
				</div>
		 	</div>
		</div>
		<div class="row">			
		 	<div class="col s4">
				<div class="input-field">
					<label for="parent_deduction" class="active">Parent Deduction</label>
					<select id="parent_deduction" name="parent_deduction" class="selectize validate" placeholder="Select Parent Deduction">
					 <option value="">Select Parent Deduction</option>
					 <?php if (!EMPTY($parent_deduction)): ?>
						<?php foreach ($parent_deduction as $parent): ?>
							<option value="<?php echo $parent['deduction_id']?>"><?php echo strtoupper($parent['deduction_name']) ?></option>
						<?php endforeach;?>
					<?php endif;?>
					</select>
				</div>
			</div>
			<div class="col s4">
				<div class="input-field">
					<label for="inherit_parent_id_flag" class="active">Inherit Parent Code<span id="inherit_req" class="required">*</span></label>
					<select id="inherit_parent_id_flag" name="inherit_parent_id_flag" class="selectize validate" placeholder="Select Inherit Parent Code Flag" disabled>
					 	<option value="NA" <?php echo ($deduction_info['inherit_parent_id_flag'] == NOT_APPLICABLE) ? "selected" : "" ?>>NOT APPLICABLE</option>
					 	<option value="Y" <?php echo ($deduction_info['inherit_parent_id_flag'] == YES) ? "selected" : "" ?>>YES</option>
					 	<option value="N" <?php echo ($deduction_info['inherit_parent_id_flag'] == NO) ? "selected" : "" ?>>NO</option>
					 </select>
				</div>
		 	</div>
		 	<div class="col s4">
				<div class="input-field">
					<input type="text" class="validate" required="" aria-required="true" name="report_short_code" id="report_short_code" value="<?php echo isset($deduction_info['report_short_code']) ? $deduction_info['report_short_code'] : NULL; ?>"/>
					<label class="" for="report_short_code">Report Short Code<span class="required">*</span></label>
				</div>
			</div>
		</div>
		<div class="none" id="amount" class="row m-t-md">
			<div class="form-float-label" >
				<div class="row b-t b-b-n b-light-gray m-n">
				 	<div class="col s12">
						<div class="input-field">
						 	<input class="number validate right-align" type="text" aria-required="true" name="amount" id="amount" value="<?php echo isset($deduction_info['amount']) ? $deduction_info['amount'] : NULL; ?>" <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>/>
							<label class="<?php echo $action == ACTION_EDIT ? 'active' :'' ?>" for="amount">Amount<span class="required">*</span></label>
						</div>
					</div>
				 </div>
			</div>
		</div>
		<div class="none" id="variable_group" class="row m-t-md">
			<div class="form-float-label">
				<div class="row b-t b-b-n b-light-gray">
					<div class="col s6">
						<div class="input-field">
							<label for="multiplier" class="active">Deduction Multiplier<span class="required">*</span></label>
							<select id="multiplier" name="multiplier" class="selectize" placeholder="Select Deduction Multiplier" <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>>
							 <option value="">Select Deduction Multiplier</option>
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
							<input class="number validate" type="text" aria-required="true" name="rate" id="rate" value="<?php echo isset($deduction_info['rate']) ? $deduction_info['rate'] : NULL; ?>" <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>/>
							<label class="<?php echo $action == ACTION_EDIT ? 'active' :'' ?>" for="rate">Rate<span class="required">*</span></label>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col s3">
				<div class="input-field">
					<label for="frequency" class="active">Frequency<span class="required">*</span></label>
					<select id="frequency" name="frequency" class="selectize" placeholder="Select Frequency" <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?> required="required">
					 <option value="">Select Frequency</option>
					 <?php if (!EMPTY($frequency_name)): ?>
						<?php foreach ($frequency_name as $frequency): ?>
							<option value="<?php echo $frequency['frequency_id']?>"><?php echo strtoupper($frequency['frequency_name']) ?></option>
						<?php endforeach;?>
					<?php endif;?>
					</select>
				</div>
			</div>
			<div class="col s3">
				<div class="input-field">
					<label for="deduction_month" class="active">Deduction Month<span class="required" id="ded_mon_req">*</span></label>
					<select name="deduction_month" id="deduction_month" class='selectize' required="required">
						<option value="">Select Month</option>
					</select>
				</div>
			</div>
			<div class="col s3">
				<div class="input-field">
					<label for="remittance_type" class="active">Remittance Type<span class="required">*</span></label>
					<select id="remittance_type" name="remittance_type" class="selectize" placeholder="Select Remittance Type" <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?> required="required">
					 <option value="">Select Remittance Type</option>
					 <?php if (!EMPTY($remittance_type_name)): ?>
						<?php foreach ($remittance_type_name as $remittance): ?>
							<option value="<?php echo $remittance['remittance_type_id']?>"><?php echo strtoupper($remittance['remittance_type_name']) ?></option>
						<?php endforeach;?>
					<?php endif;?>
					</select>
				</div>
			</div>
			<div class="col s3">
				<div class="input-field">
					<input class="validate number" type="text" name="priority_num" id="priority_num" value="<?php echo isset($deduction_info['priority_num']) ? $deduction_info['priority_num'] : NULL; ?>" <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>/>
					<label class="<?php echo $action == ACTION_EDIT ? 'active' :'' ?>" for="priority_num">Priority Number</label>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col s3">
				<div class='switch p-md'>
					Statutory<span class="required">*</span><br><br>
				    <label>
				        No
				        <input name='statutory_flag' type='checkbox'   value='Y' <?php echo ($deduction_info['statutory_flag'] == "Y") ? "checked" : "" ?> <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>> 
				        <span class='lever'></span>Yes
				    </label>
				</div>
			</div>
			<div class="col s3">
				<div class='switch p-md'>
					Grant to Employee<span class="required">*</span><br><br>
				    <label>
				        No
				        <input name='employee_flag' id="employee_flag" type='checkbox'   value='Y' <?php echo ($deduction_info['employee_flag'] == "Y") ? "checked" : "" ?> <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>> 
				        <span class='lever'></span>Yes
				    </label>
				</div>
			</div>
			<div class="col s3">
				<div class="input-field">
					<label for="employ_type_flag" class="active">Payroll Type<span class="required">*</span></label>
					<select id="employ_type_flag" name="employ_type_flag" class="selectize" placeholder="Payroll Type" <?php echo (($action == ACTION_VIEW) || ($action == ACTION_ADD) || ($deduction_info['employee_flag'] == NO)) ? 'disabled' :'' ?> required="required">
					 	<option value="ALL" <?php echo ($deduction_info['employ_type_flag'] == PAYROLL_TYPE_FLAG_ALL) ? "selected" : "" ?>>All</option>
					 	<option value="REG" <?php echo ($deduction_info['employ_type_flag'] == PAYROLL_TYPE_FLAG_REG) ? "selected" : "" ?>>Regular</option>
					 	<option value="JO" <?php echo ($deduction_info['employ_type_flag'] == PAYROLL_TYPE_FLAG_JO) ? "selected" : "" ?>>Contract of Sevice</option>
					 </select>
				</div>
		 	</div>
			<div class="col s3">
				<div class='switch p-md'>
					Employer Share<span class="required">*</span><br><br>
				    <label>
				        No
				        <input name='employer_share_flag' type='checkbox'   value='Y' <?php echo ($deduction_info['employer_share_flag'] == "Y") ? "checked" : "" ?> <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>> 
				        <span class='lever'></span>Yes
				    </label>
				</div>
			</div>
		</div>	

		<div class='row switch p-md b-b-n'>
		    <label>
	        Inactive
		        <input name='active_flag' type='checkbox'   value='Y' <?php echo ($deduction_info['active_flag'] == "Y") ? "checked" : "" ?>  <?php echo $action == ACTION_ADD ? 'checked' :'' ?> <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>> 
		        <span class='lever'></span>Active
		    </label>
		</div>		
		
		<div class="row p-b-sm p-t-sm b-t-n">
			<div class="col s3 pull-right">
				<?php if($action != ACTION_VIEW):?>
		  			<button type="button" class="btn" id="add_other_details"><i class="flaticon-add176"></i>Add Details</button>
		  		<?php endif; ?>
			</div>
		</div>
		<!-- EDIT/VIEW OTHER DEDUCTION DETAILS -->
		<div class="row b-t-n">
			<div class="form-basic">
				<table class="table-default striped">
					<thead class="teal white-text">
						<tr>
							<th width="20%" class="white-text">Name</th>
							<th width="15%" class="white-text">Data type</th>
							<th width="15%" class="white-text">Dropdown</th>
							<th width="20%" class="white-text">Primary?</th>
							<th width="20%" class="white-text">Required?</th>
							<th width="10%" class="white-text">Action</th>
						</tr>
					</thead>
					<tbody id="table_body">
						<?php if ($action != ACTION_ADD):
							foreach ($other_deduction_details as $others):
						?>
							<tr>
								<input type="hidden" name="other_deduction_detail_id[]" id="other_deduction_detail_id" value="<?php echo !EMPTY($others['other_deduction_detail_id']) ? $others['other_deduction_detail_id']: NULL?>">
								<input type="hidden" name="employee_other_ded_dtl_id[]" id="employee_other_ded_dtl_id" value="<?php echo !EMPTY($others['employee_other_ded_dtl_id']) ? $others['employee_other_ded_dtl_id']: NULL?>">
							
								<td>
									<div class="row">
										<div class="col s12">
											<div class="input-field">
												<input type="text" aria-required="true" name="other_detail_name[]" id="other_detail_name" value="<?php echo isset($others['other_detail_name']) ? $others['other_detail_name'] : ''; ?>" <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>>
											</div>
										</div>
									</div>
								</td>
								<td>
									<div class="row">
										<div class="col s12">
											<div class="input-field">
												<select name="other_detail_type[]" class="selectize" required placeholder="Select Type" <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>>
													<option value="">Select Type</option>
													<?php 
														$selected = '';
														$selected =(isset($others['other_detail_type']) && $others['other_detail_type'] == DEDUCTION_DETAIL_CHAR) ? "selected": ''; 
													?>
													<option value="C" <?php echo $selected;?>>CHARACTER</option>
													<?php 
														$selected = '';
														$selected =(isset($others['other_detail_type']) && $others['other_detail_type'] == DEDUCTION_DETAIL_NUMBER) ? "selected": ''; 
													?>
													<option value="N" <?php echo $selected;?>>NUMBER</option>
													<?php 
														$selected = '';
														$selected =(isset($others['other_detail_type']) && $others['other_detail_type'] == DEDUCTION_DETAIL_DATE) ? "selected": ''; 
													?>
													<option value="D" <?php echo $selected;?>>DATE</option>
													<?php 
														$selected = '';
														$selected =(isset($others['other_detail_type']) && $others['other_detail_type'] == DEDUCTION_DETAIL_DROPDOWN) ? "selected": ''; 
													?>
													<option value="DR" <?php echo $selected;?>>DROPDOWN</option>
													<?php 
														$selected = '';
														$selected =(isset($others['other_detail_type']) && $others['other_detail_type'] == DEDUCTION_DETAIL_YES_NO) ? "selected": ''; 
													?>
													<option value="YN" <?php echo $selected;?>>YES/NO</option>
												</select>
											</div>
										</div>
									</div>
								</td>
								<td>
									<div class="row">
										<div class="col s12">
											<div class="input-field">
												<select id="dropdown_flag" name="dropdown_flag[]" class="selectize" placeholder="Select Dropdown" <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>>
													<option value="">Select Dropdown</option>
													<?php 
														$selected = '';
														$selected =(isset($others['dropdown_flag']) && $others['dropdown_flag'] =="ADDR") ? "selected": ''; 
													?>
													<option value="ADDR" <?php echo $selected;?>>LIST OF EMPLOYEE ADDRESS</option>
												</select>
											</div>
										</div>
									</div>
								</td>
								<td>
									<div class="switch p-md">
										<label>
											No
											<input name="pk_flag[]" type="checkbox"  value="Y" <?php echo ($others['pk_flag'] == "Y") ? "checked" : "" ?> <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>>
											<span class="lever"></span>Yes
										</label>
									</div>
								</td>
								<td>
									<div class="switch p-md">
										<label>
											No
											<input name="required_flag[]" type="checkbox"  value="Y" <?php echo ($others['required_flag'] == "Y") ? "checked" : "" ?> <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>>
											<span class="lever"></span>Yes
										</label>
									</div>
								</td>
								<?php if($action != ACTION_VIEW && (  EMPTY($others['employee_other_ded_dtl_id']) || ! ISSET($others['employee_other_ded_dtl_id']))):?>
									<td class="table-actions">
										<a href="javascript:;" class="delete delete_row" onclick="delete_row(this)"></a>
									</td>
								<?php endif; ?>
							</tr>
						<?php
						endforeach;
						 endif;?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<div class="md-footer default">
		<?php if($action != ACTION_VIEW):?>
	  		<a class="waves-effect waves-teal btn-flat cancel_modal" id="cancel_deduction_type">Cancel</a>
		    <button class="btn btn-success " id="save_deduction_type" value="<?php echo BTN_SAVE ?>"><?php echo BTN_SAVE ?></button>
	  	<?php endif; ?>
	</div>
</form>
<script>
$(function (){
	$('#inherit_req').hide();
	
	$('#deduction_type_flag').on( "change", function() {
  		var selected = $(this).val();

  			if (selected === 'V') {
  				$('#variable_group').removeClass('none');
  				$('#amount').addClass('none');
  			}
  			if (selected === 'F') {
  				$('#variable_group').addClass('none');
  				$('#amount').removeClass('none');
  			}
  			if (selected === 'S') {
  				$('#variable_group').addClass('none');
  				$('#amount').addClass('none');
  			}
  			if (selected === 'ST') {
  				$('#variable_group').addClass('none');
  				$('#amount').addClass('none');
  			}
	});

	$('#parent_deduction').on( "change", function() {
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

	//ADD OTHER DEDUCTION DETAILS
	$('#add_other_details').on( "click", function() {
		var add_other_details =	'<tr>' +
									'<input type="hidden" name="other_deduction_detail_id[]" id="other_deduction_detail_id" value="">' +
									'<input type="hidden" name="employee_other_ded_dtl_id[]" id="employee_other_ded_dtl_id" value="">' +
									'<td>' +
										'<div class="row">' +
											'<div class="col s12">' +
												'<div class="input-field">' +
													'<input type="text" aria-required="true" name="other_detail_name[]" id="other_detail_name" value=""/>' +
												'</div>' +
											'</div>' +
										'</div>' +
									'</td>' +
									'<td>' +
										'<div class="row">' +
											'<div class="col s12">' +
												'<div class="input-field">' +
													'<select name="other_detail_type[]" class="selectize" required placeholder="Select Type">' +
														'<option value="">Select Type</option>' +
													 	'<option value="C">CHARACTER</option>' +
													 	'<option value="N">NUMBER</option>' +
													 	'<option value="D">DATE</option>' +
													 	'<option value="DR">DROPDOWN</option>' +
													 	'<option value="YN">YES/NO</option>' +
													'</select>' +
												'</div>' +
											'</div>' +
										'</div>' +
									'</td>' +
									'<td>' +
										'<div class="row">' +
											'<div class="col s12">' +
												'<div class="input-field">' +
													'<select id="dropdown_flag" name="dropdown_flag[]" class="selectize" placeholder="Select Dropdown">' +
														'<option value="">Select Dropdown</option>' +
													 	'<option value="ADDR">LIST OF EMPLOYEE ADDRESS</option>' +
													'</select>' +
												'</div>' +
											'</div>' +
										'</div>' +
									'</td>' +
									'<td>' +
										'<div class="switch p-md">' +
											'<label>' +
												'No' +
												'<input name="pk_flag[]" type="hidden" value="N">' +
												'<input name="pk_flag[]" type="checkbox"  value="Y">' +
												'<span class="lever"></span>Yes' +
											'</label>' +
										'</div>' +
									'</td>' +
									'<td>' +
										'<div class="switch p-md">' +
											'<label>' +
												'No' +
												'<input name="required_flag[]" type="hidden" value="N">' +
												'<input name="required_flag[]" type="checkbox"  value="Y">' +
												'<span class="lever"></span>Yes' +
											'</label>' +
										'</div>' +
									'</td>' +
									'<td class="table-actions">' +
										'<a href="javascript:;" class="delete delete_row" onclick="delete_row(this)"></a>' +
									'</td>' +
								'</tr>';
		$('#table_body').append(add_other_details);
		selectize_init();
  	});
	
	$('#deduction_type_form').parsley();
	$('#deduction_type_form').submit(function(e) {
	    e.preventDefault();
	    
		if ( $(this).parsley().isValid() ) {
			var data = $(this).serialize();
		  	button_loader('save_deduction_type', 1);
		  	var option = {
					url  : $base_url + 'main/code_library_payroll/deduction_type/process',
					data : data,
					success : function(result){
						if(result.status)
						{
							notification_msg("<?php echo SUCCESS ?>", result.msg);
							modal_deduction_type.closeModal();
							load_datatable('deduction_type_table', '<?php echo PROJECT_MAIN ?>/code_library_payroll/deduction_type/get_deduction_type_list',false,0,0,true);
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

  	$('#frequency').on('change', function(){
  		var frequency_id  = $(this).val();
  		var deduction_month = '<?php echo $deduction_info['month_pay_num'];?>';

        load_selectize({
        	url: $base_url+'main/code_library_payroll/deduction_type/get_months_limit/',
        	data: {frequency_id: frequency_id},
			target: 'deduction_month',
			selected_val: deduction_month,
			async: false
		});

       check_val();
       
  	});

  	<?php if($action != ACTION_ADD){ ?>
		$('.input-field label').addClass('active');
		<?php if ($deduction_info['deduction_type_flag'] == 'V'):?>
				$('#variable_group').removeClass('none');
  				$('#amount').addClass('none');
		<?php endif;?>
		<?php if ($deduction_info['deduction_type_flag'] == 'F'):?>
				$('#variable_group').addClass('none');
  				$('#amount').removeClass('none');
		<?php endif;?>
		<?php if ($deduction_info['deduction_type_flag'] == 'S'):?>
				$('#variable_group').addClass('none');
  				$('#amount').addClass('none');
		<?php endif;?>
		<?php if ($deduction_info['deduction_type_flag'] == 'ST'):?>
				$('#variable_group').addClass('none');
  				$('#amount').addClass('none');
		<?php endif;?>
  	<?php } ?>
});

function delete_row(delete_row){
	delete_row.closest('tr').remove();
}

function check_val(){
	var selectize = $('#deduction_month')[0].selectize;
	var options = selectize.options;

	var ctr = 0;
    for(key in options){
          ctr++;
    }

    if(ctr == 0){
    	selectize.disable();
    	$('#deduction_month').removeAttr("required");
    	$('#ded_mon_req').hide();
    	$('#has_ded_mon').val(0);
    }else{
    	selectize.enable();
    	$('#deduction_month').attr("required", "required");
    	$('#ded_mon_req').show();
    	$('#has_ded_mon').val(1);
    }   
}

$('#employee_flag').on('change', function(){
	var selectize = $('#employ_type_flag')[0].selectize;
	if($(this).is(':checked')){
		selectize.enable();
	}else{
		selectize.setValue('<?php echo PAYROLL_TYPE_FLAG_ALL; ?>');
		selectize.disable();
	}
});

</script>