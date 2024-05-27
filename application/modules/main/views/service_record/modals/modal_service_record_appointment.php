<form id="service_record_appointment_form">
	<input type="hidden" name="id" id="id" value="<?php echo !EMPTY($id) ? $id : NULL?>">
	<input type="hidden" name="salt" id="salt" value="<?php echo !EMPTY($salt) ? $salt : NULL?>">
	<input type="hidden" name="token" id="token" value="<?php echo !EMPTY($token) ? $token : NULL?>">
	<input type="hidden" name="action" id="action" value="<?php echo !EMPTY($action) ? $action : NULL?>">
	<input type="hidden" name="module" id="module" value="<?php echo !EMPTY($module) ? $module : NULL?>">
	<input type="hidden" name="employee_id" id="employee_id" value="<?php echo !EMPTY($employee_id) ? $employee_id : NULL?>">

	<div class="scroll-pane">
		<div class="form-float-label">
			<?php if(!EMPTY($employee_plantilla)):?>
				<div class="row">
					<div class='switch p-md'>
						<label>
							Salary Step Change
							<input class='active_flag' name='active_flag' type='checkbox' value='0'>
							<span class='lever'></span>
						</label>
					</div>
				</div>

				<div class="row m-n ss_ps_div">
					<div class="col s6">
						<div class="input-field">
							<input id="service_start_step" name="service_start_step" value="<?php echo !EMPTY($employee_service_record['service_start']) ?  $employee_service_record['service_start'] : '' ?>" <?php echo $action==ACTION_VIEW ? 'disabled' : '' ?> type="text" class="validate datepicker datepicker_start" >
							<label for="service_start_step">Service Start<span class="required">*</span></label>
						</div>
					</div>
					<div class="col s6">
						<div class="input-field">
							<input id="salary_step" name="salary_step" value="" type="text" class="validate">
							<label for="salary_step">Pay Step<span class="required">*</span></label>
						</div>
					</div>
				</div>
				<div class="row m-n ss_ps_div">
				</div>
			<?php endif; ?>
			<div class="row second_div">
				<div class="col s1 b-r-n">
					<input type="radio" name="fix_var_type" value="appointment" id="appointment_1" checked>
					<label for="appointment_1">Appointment</label>
				</div>
				<div class="col s6 b-r-n">
					<input type="radio" name="fix_var_type" value="personnal_movement" id="personnal_movement_1">
					<label for="personnal_movement_1">Adjustment</label>
				</div>
			</div>
			<div class="row m-n second_div">
				<div class="col s12">
					<div class=" input-field">
						<label for="personnel_movement" class="active">Personnel Movement<span class="required">*</span></label>
							<select id="personnel_movement" name="personnel_movement" <?php echo $action==ACTION_VIEW ? 'disabled' : '' ?> class="selectize" placeholder="Select Personnel Movement">
								<option value="">Select Personel Movement</option>
								<option value="1">Personel Movement 1</option>
								<option value="2">Personel Movement 2</option>
								<option value="3">Personel Movement 3</option>
							<!-- <?php foreach($param_nature_appointment as $row):?>
									<option value="<?php echo $row['nature_appointment_id']; ?>"><?php echo $row['nature_appointment_name']; ?></option>
								<?php endforeach;?> -->
							</select>
					</div>
				</div>
			</div>
			<div class="row m-n second_div">
				<div class="col s6">
					<div class=" input-field">
						<input id="service_start" name="service_start" value="<?php echo !EMPTY($employee_service_record['service_start']) ?  $employee_service_record['service_start'] : '' ?>" <?php echo $action==ACTION_VIEW ? 'disabled' : '' ?> type="text" class="validate datepicker datepicker_start" >
						<label for="service_start">Service Start Date<span class="required">*</span></label>
					</div>
				</div>
				<div class="col s6">
					<div class="input-field">
						<input id="service_end" name="service_end" readonly value="<?php echo !EMPTY($employee_service_record['service_end']) ? $employee_service_record['service_end'] : '' ?>" <?php echo $action==ACTION_VIEW ? 'readonly' : '' ?> type="text" class="validate datepicker datepicker_end">
						<label for="service_end">Service End Date</label>
					</div>
				</div>
			</div> 

			<div class="row m-n second_div">
				<div class="col s12">
					<div class=" input-field">
						<label for="plantilla" class="active">Plantilla Item Number<span class="required">*</span></label>
							<select id="plantilla" name="plantilla" <?php echo $action==ACTION_VIEW ? 'disabled' : '' ?> class="selectize" placeholder="Select Item Number">
								<option value="">Select Item Number</option>
								<?php foreach($param_plantilla as $row):?>
										<option value="<?php echo $row['plantilla_id']; ?>"><?php echo $row['item_number']; ?></option>
								<?php endforeach;?>
							</select>
					</div>
				</div>
			</div>

			<div class="row m-n second_div">
				<div class="col s6">
					<div class=" input-field">
						<input id="designation" name="designation" value="" readonly type="text" class="validate">
						<label for="designation" class="active">Position</label>
					</div>
				</div>

				<div class="col s6">
					<div class=" input-field">
						<input id="salary_grade" name="salary_grade" value=""  readonly type="text" class="validate">
						<label for="salary_grade" class="active">Salary Grade<span class="required">*</span></label>
					</div>
				</div>
			</div>
			<div class="row m-n second_div">
				<div class="col s6">
					<div class=" input-field">
						<input type="text" readonly="" 	name="salary_step_info" id="salary_step_info" value="">
						<label for="salary_step_info" class="active">Salary Step<span class="required">*</span></label>
					</div>
				</div>
				<div class="col s6">
					<div class=" input-field">
						<input id="annual_salary" name="annual_salary" value="0"  readonly type="text" class="number">
						<label for="annual_salary" class="active">Annual Salary</label>
					</div>
				</div>
			</div>
			<div class="row m-n second_div">
				<div class="col s6">
					<div class=" input-field">
						<input id="branch" name="branch" value="National" readonly type="text" class="validate">
						<label for="branch" class="active">Branch</label>
					</div>
				</div>
				<div class="col s6">
					<div class="input-field">
						<label for="office_name" class="active">Station/Place of Assignment<span class="required">*</span></label>
							<select id="office_name" name="office_name" <?php echo $action==ACTION_VIEW ? 'disabled' : '' ?> class="selectize" placeholder="Select Place of Assignment">
								<option value="">Select Status</option>
								<?php foreach($param_office as $row):?>
										<option value="<?php echo $row['office_id']; ?>"><?php echo $row['office_name']; ?></option>
								<?php endforeach;?>
							</select>
					</div>
				</div>
			</div>
			<div class="row m-n second_div">
				<div class="col s12">
					<div class=" input-field">
						<label for="employment_type" class="active">Employment Status<span class="required">*</span></label>
							<select id="employment_type" name="employment_type" <?php echo $action==ACTION_VIEW ? 'disabled' : '' ?> class="selectize" placeholder="Select Employment Status">
								<option value="">Select  Employment Status</option>
								<?php foreach($param_employment_type as $row):?>
										<option value="<?php echo $row['employment_status_id']; ?>"><?php echo $row['employment_status_name']; ?></option>
								<?php endforeach;?>
							</select>
					</div>
				</div>
			</div>
			<div class="row m-n second_div appointment_1">
				<div class="col s12">
					<div class=" input-field">
						<label for="nature_appointment" class="active">Nature of Employment<span class="required">*</span></label>
							<select id="nature_appointment" name="nature_appointment" <?php echo $action==ACTION_VIEW ? 'disabled' : '' ?> class="selectize" placeholder="Select Nature of Employment">
								<option value="">Select Nature of Employment</option>
								<?php foreach($param_nature_appointment as $row):?>
										<option value="<?php echo $row['nature_appointment_id']; ?>"><?php echo $row['nature_appointment_name']; ?></option>
								<?php endforeach;?>
							</select>
					</div>
				</div>
			</div>
			<div class="row m-n second_div appointment_1">
				<div class="col s6">
					<div class=" input-field">
						<input id="previous_appointee" name="previous_appointee" value="" type="text" class="validate">
						<label for="previous_appointee">Previous Appointee </label>
					</div>
				</div>
				<div class="col s6">
					<div class=" input-field">
						<input id="previous_cause" name="previous_cause" value="" type="text" class="validate">
						<label for="previous_cause">Previous Appointee Cause</label>
					</div>
				</div>
			</div>
			<div class="row m-n second_div">
				<div class="col s6">
					<div class=" input-field">
						<input id="publication_date" name="publication_date" value="<?php echo !EMPTY($employee_service_record['service_start']) ?  $employee_service_record['service_start'] : '' ?>" <?php echo $action==ACTION_VIEW ? 'disabled' : '' ?> type="text" class="validate datepicker datepicker_start" >
						<label for="publication_date">Publication Date<span class="required">*</span></label>
					</div>
				</div>
				<div class="col s6">
					<div class=" input-field">
						<input id="publication_place" name="publication_place" value="" type="text" class="validate">
						<label for="publication_place">Publication Place<span class="required">*</span></label>
					</div>
				</div>
			</div>
			
			<div class="row m-n second_div">
				<div class="col s6">
					<div class="input-field">
						<label for="leave_type_name" class="active">L/V ABS W/O PAY</label>
						<select id="leave_type_name" name="leave_type_name" <?php echo $action==ACTION_VIEW ? 'readonly' : '' ?> class="selectize" placeholder="Select Leaves">
							<option value="">Select Station</option>
							<?php foreach($param_leave_type as $row):?>
								<option value="<?php echo $row['leave_type_id']; ?>"><?php echo $row['leave_type_name']; ?></option>
							<?php endforeach;?>
						</select>
					</div>
				</div>
				<div class="col s6">
					<div class=" input-field">
						<input id="separation_cause" name="separation_cause" value=""  type="text" class="validate">
						<label for="separation_cause">Separation Cause</label>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="md-footer default">
		<a class="waves-effect waves-teal btn-flat cancel_modal">Cancel</a>
		<button class="btn " id="save_appointment_status" value="<?php echo BTN_SAVE ?>"><?php echo BTN_SAVE ?></button>
	</div>
</form>
<script>
$(function (){

	<?php if($action != ACTION_ADD){ ?>
		$('.input-field label').addClass('active');
  	<?php } ?>

  	$( 'input[name="fix_var_type"]').on( "change", function() {
  		var selected = $('input[name="fix_var_type"]:checked').val();

  			if (selected === 'personnal_movement') {
	  			$('.appointment_1').css("cssText", "display: none !important");
				$('.personnal_movement_1').css("cssText", "display: table !important");
  			}
  			else if(selected === 'appointment'){
  				$('.personnal_movement_1').css("cssText", "display: none !important");
				$('.appointment_1').css("cssText", "display: table !important");
  			}else{
  				$('.personnal_movement_1').css("cssText", "display: none !important");
				$('.appointment_1').css("cssText", "display: none !important");
  			}
	});

	$val_active_flag = $('.active_flag').val();
	
	($val_active_flag == '0') ?  $('.ss_ps_div').css("cssText", "display: none !important") : '';

	$('.active_flag').on('change', function(){

		$val_active_flag_ch = $(this).val();

		console.log($val_active_flag_ch);

		if($val_active_flag_ch == '1') {

			$('.ss_ps_div').css("cssText", "display: none !important");
			$('.second_div').css("cssText", "display: table !important");

		} else {

			$('.second_div').css("cssText", "display: none !important");
			$('.ss_ps_div').css("cssText", "display: table !important");

		}
		$new_val = ($val_active_flag_ch == '1') ? '0' : '1';
		$(this).val($new_val);

	});
	$('#service_record_appointment_form').parsley();

	$('#service_record_appointment_form').submit(function(e) {
	    e.preventDefault();
	    
		if ( $(this).parsley().isValid() ) {
			var data = $(this).serialize();
		  	button_loader('save_appointment_status', 1);
		  	var option = {

		  		<?php if($module == MODULE_PERSONNEL_PORTAL):?>
					url  : $base_url + 'main/service_record_changes_requests/process_education',
				<?php else: ?>
					url  : $base_url + 'main/service_record/process_service_record_appointment',
				<?php endif; ?>

					data : data,
					success : function(result){
						if(result.status)
						{
							notification_msg("<?php echo SUCCESS ?>", result.message);
							modal_service_record_appointment.closeModal();
							load_datatable('table_employee_service_record', '<?php echo PROJECT_MAIN ?>/service_record/get_employee_service_record/<?php echo $employee_id ?>',false,0,0,true);
						}
						else
						{
							notification_msg("<?php echo ERROR ?>", result.message);
						}	
					},
					complete : function(jqXHR){
						button_loader('save_appointment_status', 0);
					}
			};

			General.ajax(option);    
	    }
  	});

	$("#plantilla").on("change", function(){
		var $val    = $(this).val();
		var $params = [];
		$params     = {select_id : $val};
		$.post($base_url+"<?php echo PROJECT_MAIN."/service_record/get_param_plantilla"?>",$params, function(result) {
			if(result.flag == "1"){
				$("#designation").val(result.data.position_name);
				$("#salary_grade").val(result.data.salary_grade);
				$("#salary_step_info").val(result.data.salary_step);
				$("#annual_salary").val( parseFloat(result.data.amount) * parseFloat(12) );
			} else {
				notification_msg("<?php echo ERROR ?>", result.msg);
			}
		}, 'json');
	});

})
</script>


