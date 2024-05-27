<form id="form_experience">
	<input type="hidden" name="id" value="<?php echo $id ?>"/>
	<input type="hidden" name="salt" value="<?php echo $salt ?>"/>
	<input type="hidden" name="token" value="<?php echo $token ?>"/>
	<input type="hidden" name="action" value="<?php echo $action ?>"/>
	<input type="hidden" name="module" value="<?php echo $module ?>"/>
	<input type="hidden" name="employee_id" id="employee_id" value="<?php echo $employee_id ?>"/>
	<input type="hidden" name="prev_record" value="<?php echo (!EMPTY($active_plantilla)) ? '1' : '' ?>"/>

	<input type="hidden" name="appointment_flag" value="" id="appointment_flag_hidden"/>
	<input type="hidden" name="position" value="" id="position"/>
	<input type="hidden" name="office" value="" id="office"/>

	<input type="hidden" name="office_id" value="" id="office_id_hidden"/>
	<input type="hidden" name="division_id" value="<?php echo (!EMPTY($experience['employ_division_id'])) ? $experience['employ_division_id'] : '' ?>" id="division_id_hidden"/>
	<input type="hidden" name="position_id" value="" id="position_id_hidden"/>
	<input type="hidden" name="employment_status_id" value="" id="employment_status_id_hidden"/>
	<input type="hidden" name="employ_salary_grade" value="" id="employ_salary_grade_hidden"/>
	<input type="hidden" name="plantilla_id" value="" id="plantilla_id_hidden"/>
	<input type="hidden" name="employ_monthly_salary_non_hidden" value="" id="employ_monthly_salary_non_hidden"/>
	<input type="hidden" name="salary_step" value="" id="salary_step_hidden"/>

	<?php if(!EMPTY($experience)):?>
		<input type="hidden" name="employ_type_flag" value="<?php echo $experience['employ_type_flag'] ?>" />
	<?php endif; ?>

	<div class="form-float-label">
		<div class="row">
			<div class='col s6 switch p-md new_record p-r-n'>
				<label>
					Contract of Service
					<input id="plantilla_flag" name='plantilla_flag' type='checkbox' value=' '>
					<span class='lever active' ></span>
					Appointment
				</label>
			</div>
			<div class='col s6 switch p-md ' id="new_record">
				<label>
					Salary Increase? <span class="p-l-xs">Yes</span>
					<input id="appointment_flag" name='appointment_flag' type='checkbox' value=' '>
					<span class='lever'></span>
					No
				</label>
			</div>
		</div>

		<div class="row m-n w-appointment">
			<div class="col s6 p_movement">
				<div class=" input-field">
					<label for="personnel_movement" class="active" id="label">Personnel Movement<span class="required">*</span></label>
					<select id="personnel_movement" name="personnel_movement" <?php echo $action==ACTION_VIEW ? 'disabled' : '' ?> class="selectize" placeholder="">
					</select>
				</div>
			</div>
			<div class="col s12 step_incr_reason">
				<div class=" input-field">
					<label for="step_incr_reason_code" class="active">Step Increment Reason<!-- <span class="required">*</span> --></label>
					<select id="step_incr_reason_code" name="step_incr_reason_code" <?php echo $action==ACTION_VIEW ? 'disabled' : '' ?> class="selectize" placeholder="Select Reason" disabled>
						<option value="">Select Reason</option>
						<?php foreach($incr_reason as $incr):?>
							<option value="<?php echo $incr['sys_param_value']; ?>"><?php echo strtoupper($incr['sys_param_name']); ?></option>
						<?php endforeach;?>
					</select>
				</div>
			</div>
		</div>

		<!-- START DOH NON APPOINTMENT -->
		<div class="row m-n non-appointment">
			<div class="col s6">
				<div class="input-field">
					<input id="service_start_step" name="service_start_step" autocomplete="off"
					onkeypress="format_identifications('<?php echo DATE_FORMAT ?>',this.value,event,'service_start_step')"
					value="<?php echo !EMPTY($experience['employ_start_date']) ?  format_date($experience['employ_start_date']) : '' ?>" <?php echo $action==ACTION_VIEW ? 'disabled' : '' ?> type="text" class="validate datepicker" >
					<label id="service_start_step_label" for="service_start_step">Service Start<span class="required">*</span></label>
				</div>
			</div>
			<div class="col s6">
				<div class="input-field">
					<input id="salary_step" name="salary_step" type="text" class="validate" readonly value="<?php echo !EMPTY($experience['employ_salary_step']) ?  $experience['employ_salary_step'] : '' ?>" <?php echo $action!=ACTION_ADD ? 'readonly' : '' ?>>
					<label for="salary_step" class="active">Step Increment<span class="required">*</span></label>
				</div>
			</div>
		</div>	

		<!-- END DOH NON APPOINTMENT -->

		<div class="row m-n sd-appointment samples">
			<div class="col s6 sample1">
				<div class="input-field">
					<input type="text" class="validate datepicker" placeholder="YYYY/MM/DD" name="employ_start_date" id="employ_start_date"  autocomplete="off"
					onkeypress="format_identifications('<?php echo DATE_FORMAT ?>',this.value,event,'employ_start_date')"
					value="<?php echo isset($experience['employ_start_date'])? format_date($experience['employ_start_date']):"" ?>" <?php echo $action==ACTION_VIEW ? 'disabled' : '' ?>/>
					<label for="employ_start_date" class="active">Start Date<span class="required">*</span></label>
				</div>
			</div>
			<div class="col s6 sample">
				<div class="input-field">
					<input type="text" class="validate datepicker" placeholder="YYYY/MM/DD" name="employ_end_date" id="employ_end_date" 
					onkeypress="format_identifications('<?php echo DATE_FORMAT ?>',this.value,event,'employ_end_date')"
					value="<?php echo isset($experience['employ_end_date'])? format_date($experience['employ_end_date']):"" ?>" <?php echo $action==ACTION_VIEW ? 'disabled' : '' ?> autocomplete="off"/>
						<label for="employ_end_date" class="active">End Date (Last Day in Service)<label>
						</div>
					</div>
				</div>


				<div class="row m-n doh_jo">
					<div class="col s6">
						<div class="input-field">
							<label for="employment_status_id" class="active">Employment Status<span class="required">*</span></label>
							<select id="employment_status_id" name="employment_status_id" <?php echo $action ==ACTION_VIEW ? 'disabled' : '' ?> class="selectize" placeholder="Select Employment Status">
								<option></option>
								<?php
								foreach($employment_status AS $employ) {
									if($employ['jo_flag'] == 'Y') {
										echo '<option value="' . $employ['employment_status_id'] . '">' . $employ['employment_status_name'] . '</option>';
									}
								}

								?>
							</select>
						</div>
					</div>
					<div class="col s6">
						<div class="input-field">
							<input type="checkbox" class="labelauty" name="govt_service_flag" id="govt_service_flag"  disabled value="<?php echo ACTIVE ?>" data-labelauty="Tag as Government Service|Government Service" <?php echo isset($experience['govt_service_flag'])? "checked":"" ?>/>
						</div>
					</div>
				</div>

				<div class="row m-n plantilla">
					<div class="col s12">
						<div class=" input-field">
							<label for="plantilla_id" class="active">Plantilla Item Number<span class="required">*</span></label>
							<select id="plantilla_id" name="plantilla_id" <?php echo $action==ACTION_VIEW ? 'disabled' : '' ?> class="selectize" placeholder="Select Item Number">
								<option value="">Select Item Number</option>
								<?php foreach($plantilla as $row):?>
									<option value="<?php echo $row['plantilla_id']; ?>"><?php echo $row['plantilla_code']; ?></option>
								<?php endforeach;?>
							</select>
						</div>
					</div>
				</div>		
				<div class="row m-n doh_jo">
					<div class="col s4"  id="office_div">
						<div class="input-field" id="office_select">
							<label for="office_id" class="active">Department/Agency/Office/Company<span class="required">*</span></label>
							<select id="office_id" name="office_id" <?php echo $action==ACTION_VIEW ? 'disabled' : '' ?> class="selectize" placeholder="Select Place of Assignment">
								<option value="">Select Office</option>
								<?php foreach($office as $row):?>
									<option value="<?php echo $row['office_id']; ?>"><?php echo $row['name']; ?></option>
								<?php endforeach;?>
							</select>
						</div>
						<div class="input-field" id="office_read">
							<label for="office_id_read" class="active">Office<span class="required">*</span></label>
							<input id="office_id_read" name="office_id_read" value="test" disabled type="text" class="validate"/>
						</div>				
					</div>

					<div class="col s4" id="division_div">
					<div class="input-field" id="division_select">
							<label for="division_id" class="active">Division<span class="required">*</span></label>
							<select id="division_id" name="division_id" <?php echo $action==ACTION_VIEW ? 'disabled' : '' ?> class="selectize" placeholder="Select Place of Assignment">
								<option value="">Select Office</option>
								<?php foreach($office as $row):?>
									<option value="<?php echo $row['office_id']; ?>"><?php echo $row['name']; ?></option>
								<?php endforeach;?>
							</select>
						</div>
						<div class="input-field" id="division_read">
							<label for="division_id_read" class="active">Division<span class="required">*</span></label>
							<input id="division_id_read" name="division_id_read" value="" disabled type="text" class="validate"/>
						</div>				
					</div>

					<div class="col s4"  id="position_div">
						<div class="input-field" id="position_select">
							<label for="position_id" class="active">Position<span class="required">*</span></label>
							<select id="position_id" name="position_id" <?php echo $action==ACTION_VIEW ? 'disabled' : '' ?> class="selectize" placeholder="Select Position">
								<option value="">Select Position</option>	
								<?php foreach($position as $row):?>
									<option value="<?php echo $row['position_id']; ?>"><?php echo $row['position_name']; ?></option>
								<?php endforeach; ?>
							</select>
						</div>
						<div class="input-field" id="position_read">
							<label for="position_id_read" class="active">Position<span class="required">*</span></label>
							<input id="position_id_read" name="position_id_read" value="test" disabled type="text" class="validate"/>
						</div>
					</div>
				</div>

				<div class="row m-n" id="admin_office">
					<div class="col s12">
						<div class=" input-field">
							<label for="admin_office_id" class="active">Administrative Office<span class="required">*</span></label>
							<select id="admin_office_id" name="admin_office_id" <?php echo $action==ACTION_VIEW ? 'disabled' : '' ?> class="selectize" placeholder="Select Office">
								<option value="">Select Office</option>
								<?php foreach($office as $row):?>
									<option value="<?php echo $row['office_id']; ?>"><?php echo $row['name']; ?></option>
								<?php endforeach;?>
							</select>
						</div>
					</div>
				</div>		

				<!-- START DOH NON APPOINTMENT -->
				<div class="row m-n non-appointment b-b b-light-gray">
					<div class="col s6">
						<div class="input-field">
							<input type="text" class="validate" name="employ_salary_grade" id="employ_salary_grade" disabled value="" <?php echo $action==ACTION_VIEW ? 'disabled' : '' ?>/>
							<label for="employ_salary_grade" class="active">Salary Grade</label>
						</div>
					</div>
					<div class="col s6">
						<div class="input-field">
							<input type="text" class="validate" name="employ_monthly_salary_non" id="employ_monthly_salary_non" value="" readonly <?php echo $action==ACTION_VIEW ? 'disabled' : '' ?>/>
							<label for="employ_monthly_salary_non" class="active">Monthly Salary</label>
						</div>
					</div>
				</div>	
				<!-- END DOH NON APPOINTMENT -->

				<div class="row m-n sd-appointment">
					<div class="col s4">
						<div class="input-field">
					<!--marvin
					<input id="salary_grade" name="salary_grade" value=""  readonly type="text" class="validate" <?php //echo $action==ACTION_VIEW ? 'disabled' : '' ?>>
				-->
				<input id="salary_grade" name="salary_grade" value="" type="text" class="validate" <?php echo $action==ACTION_VIEW ? 'disabled' : '' ?>>
				<label for="salary_grade" class="active">Salary Grade</label>
			</div>
		</div>
		<div class="col s4">
			<div class="input-field">
					<!--marvin
					<input type="text" name="salary_step_info" id="salary_step_info" readonly value="" <?php //echo $action!=ACTION_ADD ? 'readonly' : '' ?>>
				-->
				<input type="text" name="salary_step_info" id="salary_step_info" value="" <?php echo $action!=ACTION_ADD ? 'readonly' : '' ?>>
				<label for="salary_step_info" class="active">Step Increment<span class="required">*</span></label>
			</div>
		</div>
		<div class="col s4">
			<div class="input-field">
				<input type="text" class="validate" readonly name="employ_monthly_salary" id="employ_monthly_salary" value="" <?php echo $action==ACTION_VIEW ? 'disabled' : '' ?>>
				<label for="employ_monthly_salary" class="active">Monthly Salary</label>
			</div>
		</div>
	</div>		

	<div class="row m-n b-b b-light-gray  sd-appointment" id="publication_range">
	<!-- <div class="row m-n b-b b-light-gray" id="publication_range"> -->
		<div class="col s6">
			<div class="input-field">
				<input type="text" class="validate datepicker" placeholder="YYYY/MM/DD" name="publication_date" id="publication_date" 
				onkeypress="format_identifications('<?php echo DATE_FORMAT ?>',this.value,event,'publication_date')"
				value="<?php echo !EMPTY($experience['publication_date'])? format_date($experience['publication_date']):"" ?>" <?php echo $action==ACTION_VIEW ? 'disabled' : '' ?> />
				<label for="publication_date" class="active">Publication Date From<label>
				</div>
			</div>
			<div class="col s6">
			<div class="input-field">
				<input type="text" class="validate datepicker" placeholder="YYYY/MM/DD" name="publication_date_to" id="publication_date_to" 
				onkeypress="format_identifications('<?php echo DATE_FORMAT ?>',this.value,event,'publication_date_to')"
				value="<?php echo !EMPTY($experience['publication_date_to'])? format_date($experience['publication_date_to']):"" ?>" <?php echo $action==ACTION_VIEW ? 'disabled' : '' ?> />
				<label for="publication_date_to" class="active">Publication Date To<label>
				</div>
			</div>

			
		</div>
		<div class="row m-n b-b b-light-gray sd-appointment" id="place_branch_div" >
		<!-- <div class="row m-n b-b b-light-gray" id="place_branch_div"> -->
			<div class="col s6">
				<div class="input-field">
					<input type="text" name="publication_place" id="publication_place" value=" <?php echo !EMPTY($experience['publication_place'])? $experience['publication_place'] : '' ?>" <?php echo $action==ACTION_VIEW ? 'disabled' : '' ?>>
					<label for="publication_place" class="active">Publication Place<span id="publication_place_label" class="required">*</span></label>
				</div>
			</div>
			<div class="col s6">
				<div class="input-field">
					<label for="branch_id" class="active">Branch<span class="required">*</span></label>
					<select id="branch_id"  name="branch_id" <?php echo $action==ACTION_VIEW ? 'disabled' : '' ?> class="selectize" placeholder="Select Branch" required>
						 <option value="">Select Branch</option>
						<?php foreach($branch as $row):?>
							<option value="<?php echo $row['branch_id']; ?>"><?php echo $row['branch_name']; ?> </option>
						<?php endforeach;?>							
					</select>
				</div>
			</div>
		</div>
		<div class="row m-n b-b b-light-gray sd-appointment w-appointment" id="posted_deliberation_div">
		<!-- <div class="row m-n b-b b-light-gray" id="posted_deliberation_div">  -->
			<div class="col s6">
				<div class="input-field">
					<input type="text" name="posted_in" id="posted_in" value=" <?php echo !EMPTY($experience['posted_in'])? $experience['posted_in'] : '' ?>" <?php echo $action==ACTION_VIEW ? 'disabled' : '' ?>>
					<label for="posted_in" class="active">Posted In<span id="posted_in_label" class="required">*</span></label>
				</div>
			</div>
			<div class="col s6">
			<div class="input-field">
				<input type="text" class="validate datepicker" placeholder="YYYY/MM/DD" name="deliberation_date" id="deliberation_date" 
				onkeypress="format_identifications('<?php echo DATE_FORMAT ?>',this.value,event,'deliberation_date')"
				value="<?php echo !EMPTY($experience['deliberation_date'])? format_date($experience['deliberation_date']):"" ?>" <?php echo $action==ACTION_VIEW ? 'disabled' : '' ?> />
				<!-- <label for="deliberation_date" class="active">Deliberation Date<span id="deliberation_date_label" class="required">*</span><label> -->
				<label for="deliberation_date" class="active">Deliberation Date<label>
				</div>
			</div>
		</div>
		<div class="row m-n b-b b-light-gray sd-appointment w-appointment" id="posted_deliberation_div">
			<div class="col s6">
				<div class="input-field">
					<input type="text" class="validate datepicker" placeholder="YYYY/MM/DD" name="hrmpsb_date" id="hrmpsb_date" 
					onkeypress="format_identifications('<?php echo DATE_FORMAT ?>',this.value,event,'hrmpsb_date')"
					value="<?php echo !EMPTY($experience['hrmpsb_date'])? format_date($experience['hrmpsb_date']):"" ?>" <?php echo $action==ACTION_VIEW ? 'disabled' : '' ?> />
					<label for="hrmpsb_date" class="active">HRMPSB started on<label>
				</div>
			</div>
			<div class="col s6">
				<div class="input-field">
					<input type="text" name="plantilla_page" id="plantilla_page" placeholder="e.g. 1 of 3"
					value="<?php echo !EMPTY($experience['plantilla_page'])? $experience['plantilla_page'] : '' ?>" <?php echo $action==ACTION_VIEW ? 'disabled' : '' ?>>
					<label for="plantilla_page" class="active">Plantilla Page</label>
				</div>
			</div>
		</div>
		<div class="row m-n b-b b-light-gray signing_div" id="signing_div">
			<div class="col s12">
				<div class="input-field">
					<input type="text" class="validate datepicker" placeholder="YYYY/MM/DD" name="signing_date" id="signing_date" 
					onkeypress="format_identifications('<?php echo DATE_FORMAT ?>',this.value,event,'signing_date')"
					value="<?php echo !EMPTY($experience['signing_date'])? format_date($experience['signing_date']):"" ?>" <?php echo $action==ACTION_VIEW ? 'disabled' : '' ?> />
					<label for="signing_date" class="active">Date of Signing<label>
				</div>
			</div>
		</div>
		<div class="row m-n" id="separation_div"  >  			
			<div class="col s12"> 
				<div class="input-field">
					<label for="separation_mode" class="active">Separation Cause</label>
					<select id="separation_mode"  name="separation_mode" class="selectize" placeholder="Select Separation Cause" <?php echo $action==ACTION_VIEW ? 'disabled' : '' ?>>
						<option value="">Select Separation Cause</option>
						<?php foreach($separation_mode as $row):?>
							<option value="<?php echo $row['separation_mode_id']; ?>"><?php echo $row['separation_mode_name']; ?></option>
						<?php endforeach;?>
					</select>
				</div>
			</div>
		</div>
		<div class="row s_end_date" >
			<div class="col s12"> 
				<div class="input-field">
					<input type="text" class="validate datepicker" placeholder="YYYY/MM/DD" name="end_date" id="end_date" 
					onkeypress="format_identifications('<?php echo DATE_FORMAT ?>',this.value,event,'end_date')"
					value="<?php echo !EMPTY($experience['employ_end_date'])? format_date($experience['employ_end_date']):"" ?>" <?php echo $action==ACTION_VIEW ? 'disabled' : '' ?> />
					<label for="end_date" class="active">Service End Date (Last day in Service)<label>
					</div>
				</div>
			</div>
		<div class="row m-n b-b b-light-gray w-appointment" id="period_of_emp_div">
				<div class="col s6">
					<div class="input-field">
						<input type="text" class="validate datepicker" placeholder="YYYY/MM/DD" name="employ_period_from" id="employ_period_from" 
								onkeypress="format_identifications('<?php echo DATE_FORMAT ?>',this.value,event,'employ_period_from')"
								value="<?php echo !EMPTY($experience['employ_period_from'])? format_date($experience['employ_period_from']):"" ?>" <?php echo $action==ACTION_VIEW ? 'disabled' : '' ?> />
						<label for="employ_period_from" class="active">Period of Employment From<label>
					</div>
				</div>
				<div class="col s6">
					<div class="input-field">
						<input type="text" class="validate datepicker" placeholder="YYYY/MM/DD" name="employ_period_to" id="employ_period_to" 
							onkeypress="format_identifications('<?php echo DATE_FORMAT ?>',this.value,event,'employ_period_to')"
							value="<?php echo !EMPTY($experience['employ_period_to'])? format_date($experience['employ_period_to']):"" ?>" <?php echo $action==ACTION_VIEW ? 'disabled' : '' ?> />
						<label for="employ_period_to" class="active">Period of Employment To<label>
					</div>
				</div>
			</div>

			<div class="row">			
				<div class="col s12 b-t-n b-b b-light-gray">
					<div class="input-field">
						<input type="text" name="remarks" id="remarks" value="<?php echo !EMPTY($experience['remarks'])? $experience['remarks'] : '' ?>" <?php echo $action==ACTION_VIEW ? 'disabled' : '' ?>>
						<label for="remarks" class="active">Remarks</label>
					</div>
				</div>
			</div>
			<div class='col s6 switch p-md b-b-n'>
					Relevant<br><br>
				<label>
					No
					<input name='relevance_flag' type='checkbox' value='Y' <?php echo ($experience['relevance_flag'] == "Y") ? "checked" : "" ?>  <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>> 
					<span class='lever'></span>Yes
				</label>
			</div>
			<div class="row" id="transfer_div">			
				<div class='col s6 switch p-md'>
				Transfer Movement</label><br><br>
				<label>
					Out
					<input id="transfer_flag" name='transfer_flag' type='checkbox' value='IN' <?php echo ($experience['transfer_flag'] == "IN") ? "checked" : "" ?> <?php echo $action == ACTION_ADD ? 'checked' :'' ?> <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>> 
					<span class='lever'></span>In
				</label>
			</div>
			<div class="col s6">
				<div class="input-field">
					<input type="text" name="transfer_to" id="transfer_to" value="<?php echo !EMPTY($experience['transfer_to'])? $experience['transfer_to'] : '' ?>" <?php echo $action==ACTION_VIEW ? 'disabled' : '' ?>>
					<label for="transfer_to" class="active"><span id="transfer_text">Transfer From/To</label></label>
					</div>
				</div>
			</div>
		</div>	<br><br><br><br><br><br><br><br><br><br>
	</form>

	<?php if($action != ACTION_VIEW): ?>

		<div class="md-footer default">
			<a class="waves-effect waves-teal btn-flat cancel_modal">Cancel</a>
			<button class="btn btn-success " id="save_experience" value="<?php echo BTN_SAVE ?>"><?php echo BTN_SAVE ?></button>
		</div>
	<?php endif;?>

	<script>


		$(function (){

			$('#admin_office').css("cssText", "display: none !important");

			<?php if($action != ACTION_ADD){ ?>
				$('.input-field label').addClass('active');
				$('#salary_grade').addClass('active'); 
				$('#salary_step_info').addClass('active');
				$('#employ_monthly_salary').addClass('active');
			<?php } ?>

			$('#plantilla_flag').on("click", function() {
				plantilla_flag = $('#plantilla_flag:checked').val();
				new_val = (plantilla_flag == ' ') ? '1' : ' ';
				$(this).val(new_val);
				if(new_val == 1)
				{
					<?php if(EMPTY($active_plantilla)):?>
						$('#appointment_flag').attr('disabled', true); 
					<?php else: ?> 
						$('#appointment_flag').attr('disabled', false);
					<?php endif; ?>



				}
				else
				{
					$('#appointment_flag').prop('checked', false);
					$('#appointment_flag').attr('disabled', true);
				}
			});

			$('#appointment_flag').on('change', function(){
				appointment_flag = $(this).val();
				new_val = (appointment_flag == ' ') ? '1' : ' ';
				$(this).val(new_val);
			});

  	//IF ACTION IS EDIT BUTTON ABOVE WILL DISABLED
			<?php if(!EMPTY($experience)){?>
				<?php if($experience['employ_type_flag'] == DOH_JO) { ?>
					$('#plantilla_flag').attr('disabled', true);
					$('#govt_service_flag').removeAttr('checked');
					// $("#salary_grade").val(result.data.salary_grade);
					// $("#salary_step_info").val(result.data.salary_step);	
				<?php } ?>

				<?php if($experience['employ_type_flag'] == DOH_GOV_NON_APPT) { ?>
					$('#plantilla_flag').attr('disabled', true);
					$('#plantilla_flag').attr('checked', true);
					$('#appointment_flag').attr('disabled', true);
					$('#govt_service_flag').attr('checked', true);
					$('#employ_monthly_salary_non').val('<?php echo $experience['employ_monthly_salary']?>');
				<?php } ?>

				<?php if($experience['employ_type_flag'] == DOH_GOV_APPT) { ?>
					$('#plantilla_flag').attr('disabled', true);
					$('#plantilla_flag').attr('checked', true);
					$('#appointment_flag').attr('disabled', true);
					$('#appointment_flag').attr('checked', true);
					$('#govt_service_flag').attr('checked', true);
					$("#posted_in").prop("required", true);
					$('#posted_in_label').html('*');
					$('#office_select').addClass('hide');
					$('#position_select').addClass('hide');
					$('#division_select').addClass('hide');
				<?php } ?>
			<?php }?>
  	//END OF ACTION EDIT

  	//DOCUMENT READY FUNCTION - AUTOMATIC RUN WHEN THE PAGE IS LOAD
			$( document ).ready(function() {
				<?php if ($experience['employ_type_flag'] == DOH_JO):?>
					var result = '<option value="">Select Employment Status</option>';
					var employment_status = <?php echo json_encode($employment_status) ?>;

					for(var i=0 ; i < employment_status.length; i++)
					{
						if(employment_status[i]['jo_flag'] == 'Y')
						{
							result += '<option value="' + employment_status[i]['employment_status_id'] + '">' + employment_status[i]['employment_status_name'] + '</option>';
						}
					}
					$('#employment_status_id').html(result);				
				<?php endif;?>

				<?php if ($experience['employ_type_flag'] != DOH_GOV_NON_APPT AND $experience['employ_type_flag'] != DOH_GOV_APPT):?>
					$('.appointment_flag').css("cssText", "display: none !important");
					$('.w-appointment').css("cssText", "display: none !important");
					$('.s_end_date').css("cssText", "display: none !important");
	    			$('#separation_div').css("cssText", "display: none !important"); 
	    			$('.plantilla').css("cssText", "display: none !important");

	    			var result = '<option value="">Select Employment Status</option>';
	    			var employment_status = <?php echo json_encode($employment_status) ?>;
	    		<?php endif;?>
	    		<?php if ($experience['employ_type_flag'] != DOH_GOV_APPT):?>
	    			$('#publication_place').val("N/A");
	    			$('.signing_div').css("cssText", "display: none !important");

					$('#branch_id').selectize();
					$('#branch_id')[0].selectize.destroy();
					$('#branch_id').removeAttr('disabled');
					$('#branch_id').val('<?php echo $active_plantilla['government_branch_id']?>');
					$('#branch_id').selectize();
	    			var result = '<option value="">Select Employment Status</option>';
	    			var employment_status = <?php echo json_encode($employment_status) ?>;
	    		<?php endif;?>

	    		<?php if($action != ACTION_ADD):?>
	    			$('#separation_div').css("cssText", "display: important"); 
	    		<?php endif;?>

	    		<?php if ($experience['employ_type_flag'] != DOH_GOV_APPT):?>
	    			$('#appointment_flag').prop('checked', false);
					$("#posted_in").prop("required", false);
					$('#posted_in_label').html('');

	    		<?php endif;?>

	    		<?php if ($experience['employ_type_flag'] == DOH_GOV_APPT):?>
	    			$('.sample').attr('style','display:none !important');
	    			$('#employ_end_date').attr('type','hidden');
	    			$('.sample1').switchClass('s6','s12');

	    			var result = '<option value="">Select Personel Movement</option>';
	    			var personnel_movement = <?php echo json_encode($personnel_movement) ?>;
	    			for(var i=0 ; i < personnel_movement.length; i++)
	    			{
	    				if(personnel_movement[i]['needs_appointment'] == 'Y')
	    				{
	    					result += '<option value="' + personnel_movement[i]['personnel_movement_id'] + '">' + personnel_movement[i]['personnel_movement_name'] + '</option>';
	    				}
	    			}
	    			$('#personnel_movement').html(result);
	    			$('#label').html("Nature of Appointment");

	    			var result = '<option value="">Select Employment Status</option>';
	    			var employment_status = <?php echo json_encode($employment_status) ?>;

	    			for(var i=0 ; i < employment_status.length; i++)
	    			{
	    				if(employment_status[i]['ap_flag'] == 'Y')
	    				{
	    					result += '<option value="' + employment_status[i]['employment_status_id'] + '">' + employment_status[i]['employment_status_name'] + '</option>';
	    				}
	    			}
	    			$('#employment_status_id').html(result);

	    		<?php endif;?>  		

	    		<?php if ($experience['employ_type_flag'] != DOH_GOV_NON_APPT):?>	     	
	    			$('.non-appointment').css("cssText", "display: none !important");
	    		<?php endif;?>

	    		<?php if ($experience['employ_type_flag'] == DOH_GOV_NON_APPT):?>
	    			$('.sd-appointment').css("cssText", "display: none !important");

	    			$('#plantilla_id').attr('disabled','true');
	    			$('#plantilla_id').val('<?php echo $experience['employ_plantilla_id']?>');

	    			$('#office_id').attr('disabled','true');
	    			$('#office_id').val('<?php echo $experience['employ_office_id']?>');

	    			$('#division_id').attr('disabled','true');
	    			$('#division_id').val('<?php echo $experience['employ_division_id']?>');

	    			$('#employment_status_id').attr('disabled','true');
	    			$('#employment_status_id').val('<?php echo $experience['employment_status_id']?>');

	    			$('#position_id').attr('disabled','true');
	    			$('#position_id').val('<?php echo $experience['employ_position_id']?>');

	    			$('#employ_salary_grade').val('<?php echo $experience['employ_salary_grade']?>');
	    			$('#employ_monthly_salary_non').val('<?php echo $experience['employ_monthly_salary']?>');
	    			$('#salary_step').val('<?php echo $experience['employ_salary_step']?>');

	    			$('#plantilla_id_hidden').val('<?php echo $experience['employ_plantilla_id']?>');
	    			$('#office_id_hidden').val('<?php echo $experience['employ_office_id']?>');
					$('#division_id_hidden').val('<?php echo $experience['employ_division_id']?>');
	    			$('#employment_status_id_hidden').val('<?php echo $experience['employment_status_id']?>');
	    			$('#position_id_hidden').val('<?php echo $experience['employ_position_id']?>');
	    			$('#employ_salary_grade_hidden').val('<?php echo $experience['employ_salary_grade']?>');
	    			$('#employ_monthly_salary_non_hidden').val('<?php echo $experience['employ_monthly_salary']?>');
	    			$('#salary_step_hidden').val('<?php echo $experience['employ_salary_step']?>');

	    			var result = '<option value="">Select Personel Movement</option>';
	    			var personnel_movement = <?php echo json_encode($personnel_movement) ?>;
	    			for(var i=0 ; i < personnel_movement.length; i++)
	    			{
	    				$('.sample1').switchClass('s12','s6');
	    				if(personnel_movement[i]['needs_office_order'] == 'Y')
	    				{
	    					result += '<option value="' + personnel_movement[i]['personnel_movement_id'] + '">' + personnel_movement[i]['personnel_movement_name'] + '</option>';
	    				}
	    			}
	    			$('#personnel_movement').html(result);

	    			var result = '<option value="">Select Employment Status</option>';
	    			var employment_status = <?php echo json_encode($employment_status) ?>;

	    			for(var i=0 ; i < employment_status.length; i++)
	    			{
	    				if(employment_status[i]['ap_flag'] == 'Y')
	    				{
	    					result += '<option value="' + employment_status[i]['employment_status_id'] + '">' + employment_status[i]['employment_status_name'] + '</option>';
	    				}

	    			}
	    			$('#employment_status_id').html(result);

	    		<?php endif;?>

	    		<?php if(EMPTY($active_plantilla)):?>
	    			$('#appointment_flag').attr('disabled', true);
	    			$('#new_record').css("cssText", "display: none !important");
	    			$('.new_record').switchClass('s6','s12');
	    		<?php endif;?>

	    		$('#appointment_flag').attr('disabled', true);
	    		$('#position_read').addClass("hide");
	    		$('#office_read').addClass("hide");
	    		$('#division_read').addClass("hide");

				<?php if($experience['employ_type_flag'] == DOH_GOV_APPT) { ?>
					$('#office_read').removeClass('hide');
					$('#position_read').removeClass('hide');
					$('#division_read').removeClass('hide');
				<?php } ?>

		// marvin : disable sg, step and salary by default : start
	    		if($("#plantilla_flag").val() != "1")
	    		{
	    			$("#salary_grade").prop("readonly", true);
	    			// $("#salary_step_info").prop("readonly", true); davcorrea 10/09/23 enabled editting
	    		}
		// marvin : disable sg, step and salary by default : end
	    	});

  	//TAGGING DISPLAYS : JO AND NO APPOINTMENT
$("input[name=plantilla_flag]").change(function(){
	if (!$('#plantilla_flag').is(":checked"))
	{
		$('.appointment_flag').css("cssText", "display: table !important");
		$('.plantilla').css("cssText", "display: none !important");

		$("#posted_in").prop("required", false);
		$('#posted_in_label').html('');
		$('#publication_place').val("N/A");
		$('#period_of_emp_div').css("cssText", "display: none !important");


		$('.non-appointment').css("cssText", "display: none !important");
		$('.sd-appointment').css("cssText", "display: table !important");

		$('.w-appointment').css("cssText", "display: none !important");
		$('#separation_div').css("cssText", "display: none !important");


		$('#position_select').removeClass("hide");
		$('#position_read').addClass("hide");
		$('#office_select').removeClass("hide");
		$('#division_select').removeClass("hide");
		$('#office_read').addClass("hide");
		$('#division_read').addClass("hide");

		$('#appointment_flag').prop('checked', false);
		$('#govt_service_flag').prop('checked', false);

			// CLONE DIV
		$('.sample').removeAttr('style');
		$('#employ_end_date').attr('type','text');
		$('.sample1').switchClass('s12','s6');

		$('#plantilla_id')[0].selectize.destroy();
		$('#plantilla_id').removeAttr('disabled');
		$('#plantilla_id').val('');
		$('#plantilla_id').selectize();

		$('#office_id')[0].selectize.destroy();
		$('#office_id').removeAttr('disabled');
		$('#office_id').val('');
		$('#office_id').selectize();

		$('#division_id')[0].selectize.destroy();
		$('#division_id').removeAttr('disabled');
		$('#division_id').val('');
		$('#division_id').selectize();

		$('#employment_status_id')[0].selectize.destroy();
		$('#employment_status_id').removeAttr('disabled');
		$('#employment_status_id').val('');
		$('#employment_status_id').selectize();

		$('#position_id')[0].selectize.destroy();
		$('#position_id').removeAttr('disabled');
		$('#position_id').val('');
		$('#position_id').selectize();

		$('#employ_salary_grade').val('');
		$('#employ_monthly_salary_non').val('');
		$('#salary_step').val('');

		$('#employment_status_id')[0].selectize.destroy();
		var result = '<option value="">Select Employment Status</option>';
		var employment_status = <?php echo json_encode($employment_status) ?>;

		for(var i=0 ; i < employment_status.length; i++)
		{
			if(employment_status[i]['jo_flag'] == 'Y')
			{
				result += '<option value="' + employment_status[i]['employment_status_id'] + '">' + employment_status[i]['employment_status_name'] + '</option>';
			}

		}
		$('#employment_status_id').html(result).selectize();

	}
	else
	{
		$('#branch_id')[0].selectize.destroy();
		$('#branch_id').removeAttr('disabled');
		$('#branch_id').val('<?php echo $active_plantilla['government_branch_id']?>');
		$('#branch_id').selectize();

		$('.appointment_flag').css("cssText", "display: table !important");
		$('.plantilla').css("cssText", "display: table !important");
		$('.w-appointment').css("cssText", "display: table !important");
		$('#period_of_emp_div').css("cssText", "display: none !important");

		$('#separation_div').css("cssText", "display: none !important");
		$('#govt_service_flag').prop('checked', true);

		$('#plantilla_id').css("cssText", "display: none !important");

		

		<?php if(!EMPTY($active_plantilla)):?>

			$('#position_select').removeClass("hide");
			$('#position_read').addClass("hide");
			$('#office_select').removeClass("hide");
			$('#division_select').removeClass("hide");
			$('#office_read').addClass("hide");
			$('#division_read').addClass("hide");

			$("#posted_in").prop("required", true);
			$('#posted_in_label').html('*'); //ncocampo end
			$('.non-appointment').css("cssText", "display: table !important");
			$('.sd-appointment').css("cssText", "display: none !important");
			$('#appointment_flag').prop('checked', false);
			$('#appointment_flag').prop('disabled', false);

			$('#plantilla_id')[0].selectize.destroy();
			$('#plantilla_id').attr('disabled','true');
			$('#plantilla_id').val('<?php echo $active_plantilla['employ_plantilla_id']?>');
			$('#plantilla_id').selectize();

			$('#office_id')[0].selectize.destroy();
			$('#office_id').attr('disabled','true');
			$('#office_id').val('<?php echo $active_plantilla['employ_office_id']?>');
			$('#office_id').selectize();

			$('#division_id')[0].selectize.destroy();
			$('#division_id').attr('disabled','true');
			$('#division_id').val('<?php echo $active_plantilla['employ_division_id']?>');
			$('#division_id').selectize();

			$('#position_id')[0].selectize.destroy();
			$('#position_id').attr('disabled','true');
			$('#position_id').val('<?php echo $active_plantilla['employ_position_id']?>');
			$('#position_id').selectize();

			$('#employ_salary_grade').val('<?php echo $active_plantilla['employ_salary_grade']?>');
			$('#employ_monthly_salary_non').val('<?php echo $active_plantilla['employ_monthly_salary']?>');
			$('#salary_step').val('<?php echo $active_plantilla['employ_salary_step']?>');

			$('#plantilla_id_hidden').val('<?php echo $active_plantilla['employ_plantilla_id']?>');
			$('#office_id_hidden').val('<?php echo $active_plantilla['employ_office_id']?>');
			$('#division_id_hidden').val('<?php echo $active_plantilla['employ_division_id']?>');
			$('#employment_status_id_hidden').val('<?php echo $active_plantilla['employment_status_id']?>');
			$('#position_id_hidden').val('<?php echo $active_plantilla['employ_position_id']?>');
			$('#employ_salary_grade_hidden').val('<?php echo $active_plantilla['employ_salary_grade']?>');
			$('#employ_monthly_salary_non_hidden').val('<?php echo $active_plantilla['employ_monthly_salary']?>');
			$('#salary_step_hidden').val('<?php echo $active_plantilla['employ_salary_step']?>');

	    	$('#publication_place').val("N/A");

			$('#branch_id')[0].selectize.destroy();
			$('#branch_id').removeAttr('disabled');
			$('#branch_id').val('<?php echo $active_plantilla['government_branch_id']?>');
			$('#branch_id').selectize();

		<?php else: ?>
			$('.sample').attr('style','display:none !important');
			$('#employ_end_date').attr('type','hidden');
			$('.sample1').switchClass('s6','s12');

			$("#posted_in").prop("required", true);
			$('#posted_in_label').html('*'); //ncocampo end
			$('#position_select').addClass("hide");
			$('#position_read').removeClass("hide");
			$('#office_select').addClass("hide");
			$('#division_select').addClass("hide");
			$('#office_read').removeClass("hide");
			$('#division_read').removeClass("hide");

			$('#appointment_flag_hidden').val('1');

			$('.non-appointment').css("cssText", "display: none !important");
			$('.sd-appointment').css("cssText", "display: table !important");
			$('#appointment_flag').prop('checked', true);
			$('#appointment_flag').prop('disabled', true);

	    	$('#publication_place').val("");

		<?php endif;?>


		$('#personnel_movement')[0].selectize.destroy();
		var result = '<option value="">Select Personel Movement</option>';
		var personnel_movement = <?php echo json_encode($personnel_movement) ?>;

		for(var i=0 ; i < personnel_movement.length; i++)
		{
			<?php if(EMPTY($active_plantilla)):?>
				if(personnel_movement[i]['needs_appointment'] == 'Y')
				{
					result += '<option value="' + personnel_movement[i]['personnel_movement_id'] + '">' + personnel_movement[i]['personnel_movement_name'] + '</option>';
				}
			<?php else: ?>
				if(personnel_movement[i]['needs_office_order'] == 'Y')
				{
					result += '<option value="' + personnel_movement[i]['personnel_movement_id'] + '">' + personnel_movement[i]['personnel_movement_name'] + '</option>';
				}
			<?php endif; ?>

		}
		$('#personnel_movement').html(result).selectize();

		$('#label').html("Personnel Movement");

		$('#employment_status_id')[0].selectize.destroy();
		var result = '<option value="">Select Employment Status</option>';
		var employment_status = <?php echo json_encode($employment_status) ?>;

		for(var i=0 ; i < employment_status.length; i++)
		{
			if(employment_status[i]['ap_flag'] == 'Y')
			{
				result += '<option value="' + employment_status[i]['employment_status_id'] + '">' + employment_status[i]['employment_status_name'] + '</option>';
			}
		}

		$('#employment_status_id').html(result).selectize();

		<?php if(!EMPTY($active_plantilla)):?>
			$('#employment_status_id').html(result).selectize();
			$('#employment_status_id')[0].selectize.destroy();
			$('#employment_status_id').attr('disabled','true');
			$('#employment_status_id').val('<?php echo $active_plantilla['employment_status_id']?>');
			$('#employment_status_id').selectize();
		<?php endif; ?>

	}  
});

	//TAGGING DISPLAYS : APPOINTMENT 
$("input[name=appointment_flag]").change(function(){
	if ($('#appointment_flag').is(":checked"))
	{
		
		$('.sd-appointment').css("cssText", "display: table !important");
		$('.w-appointment').css("cssText", "display: table !important");
		$('#separation_div').css("cssText", "display: none !important");
		$('.non-appointment').css("cssText", "display: none !important");
		$('#period_of_emp_div').css("cssText", "display: none !important");


		$('#position_select').addClass("hide");
		$('#position_read').removeClass("hide");
		$('#office_select').addClass("hide");
		$('#division_select').addClass("hide");
		$('#office_read').removeClass("hide");
		$('#division_read').removeClass("hide");
		$("#posted_in").prop("required", true);
		$('#posted_in_label').html('*'); //ncocampo end
		$('#publication_place').val("");

		$('#personnel_movement')[0].selectize.destroy();
		var result = '<option value="">Select Nature Of Appointment</option>';
		var personnel_movement = <?php echo json_encode($personnel_movement) ?>;

		for(var i=0 ; i < personnel_movement.length; i++)
		{
			if(personnel_movement[i]['needs_appointment'] == 'Y')
			{
				result += '<option value="' + personnel_movement[i]['personnel_movement_id'] + '">' + personnel_movement[i]['personnel_movement_name'] + '</option>';
			}
		}

		$('#personnel_movement').html(result).selectize();

		$('#step_incr_reason_code')[0].selectize.destroy();
		$('#step_incr_reason_code').attr("disabled", true);
		$('#step_incr_reason_code').selectize();
		$("#step_incr_reason_code")[0].selectize.clear();
		$('#step_incr_reason_code').prop('required', false);

		$('.sample').attr('style','display:none !important');
		$('#employ_end_date').attr('type','hidden');
		$('.sample1').switchClass('s6','s12');

		$('#plantilla_id')[0].selectize.destroy();
		$('#plantilla_id').removeAttr('disabled');
		$('#plantilla_id').val('');
		$('#plantilla_id').selectize();

		$('#office_id')[0].selectize.destroy();
		$('#office_id').removeAttr('disabled');
		$('#office_id').val('');
		$('#office_id').selectize();
		
		$('#division_id')[0].selectize.destroy();
		$('#division_id').removeAttr('disabled');
		$('#division_id').val('');
		$('#division_id').selectize();

		$('#employment_status_id')[0].selectize.destroy();
		$('#employment_status_id').removeAttr('disabled');
		$('#employment_status_id').val('');
		$('#employment_status_id').selectize();

		$('#position_id')[0].selectize.destroy();
		$('#position_id').removeAttr('disabled');
		$('#position_id').val('');
		$('#position_id').selectize();

		$('#branch_id')[0].selectize.destroy();
		$('#branch_id').removeAttr('disabled');
		$('#branch_id').val('<?php echo $active_plantilla['government_branch_id']?>');
		$('#branch_id').selectize();

		$('#employ_salary_grade').val('');
		$('#employ_monthly_salary_non').val('');
		$('#salary_step').val('');

		$('#plantilla_id')[0].selectize.destroy();

		<?php if(!EMPTY($active_plantilla)): ?>
			var result = '<option value="">Select Item Number</option>';
			var plantilla = <?php echo json_encode($plantilla) ?>;
			var active_plantilla = <?php echo !EMPTY($active_plantilla['employ_plantilla_id']) ? $active_plantilla['employ_plantilla_id'] : 0; 	?>;

			for(var i=0 ; i < plantilla.length; i++)
			{
				if(plantilla[i]['plantilla_id'] != active_plantilla)
				{
					result += '<option value="' + plantilla[i]['plantilla_id'] + '">' + plantilla[i]['plantilla_code'] + '</option>';
				}
			}
			$('#plantilla_id').html(result).selectize();
		<?php else: ?>
			('#plantilla_id').removeAttr('disabled');
			$('#plantilla_id').val('');
			$('#plantilla_id').selectize();	
		<?php endif; ?>

		$('#label').html("Nature of Appointment");
		$('#personnel_movement').attr("placeholder","Select Nature of Appointment");

		var result = '<option value="">Select Employment Status</option>';
		var employment_status = <?php echo json_encode($employment_status) ?>;
		for(var i=0 ; i < employment_status.length; i++)
		{
			if(employment_status[i]['ap_flag'] == 'Y')
			{
				result += '<option value="' + employment_status[i]['employment_status_id'] + '">' + employment_status[i]['employment_status_name'] + '</option>';
			}
		}
		$('#employment_status_id').html(result);

	}
	else
	{
		$('.appointment_flag').css("cssText", "display: table !important");
		$('.plantilla').css("cssText", "display: table !important");
		$('.w-appointment').css("cssText", "display: table !important");
		$('#separation_div').css("cssText", "display: none !important");
		$('#period_of_emp_div').css("cssText", "display: none !important");

		// $('#posted_deliberation_div').css("cssText", "display: none !important"); //12/14/2023/
		$('#govt_service_flag').prop('checked', true);

		$('#plantilla_id').css("cssText", "display: none !important");
		$("#posted_in").prop("required", false);
		$('#posted_in_label').html(''); //ncocampo end
		$('#publication_place').val("N/A");

		<?php if(!EMPTY($active_plantilla)):?>

			$('#position_select').removeClass("hide");
			$('#position_read').addClass("hide");
			$('#office_select').removeClass("hide");
			$('#division_select').removeClass("hide");
			$('#office_read').addClass("hide");
			$('#division_read').addClass("hide");


			$('.non-appointment').css("cssText", "display: table !important");
			$('.sd-appointment').css("cssText", "display: none !important");
			$('#appointment_flag').prop('checked', false);
			$('#appointment_flag').prop('disabled', false);

			$('#plantilla_id')[0].selectize.destroy();
			$('#plantilla_id').attr('disabled','true');
			$('#plantilla_id').val('<?php echo $active_plantilla['employ_plantilla_id']?>');
			$('#plantilla_id').selectize();

			$('#office_id')[0].selectize.destroy();
			$('#office_id').attr('disabled','true');
			$('#office_id').val('<?php echo $active_plantilla['employ_office_id']?>');
			$('#office_id').selectize();

			$('#division_id')[0].selectize.destroy();
			$('#division_id').attr('disabled','true');
			$('#division_id').val('<?php echo $active_plantilla['employ_division_id']?>');
			$('#division_id').selectize();

			$('#employment_status_id')[0].selectize.destroy();
			$('#employment_status_id').attr('disabled','true');
			$('#employment_status_id').val('<?php echo $active_plantilla['employment_status_id']?>');
			$('#employment_status_id').selectize();

			$('#position_id')[0].selectize.destroy();
			$('#position_id').attr('disabled','true');
			$('#position_id').val('<?php echo $active_plantilla['employ_position_id']?>');
			$('#position_id').selectize();

			$('#branch_id')[0].selectize.destroy();
			$('#branch_id').removeAttr('disabled');
			$('#branch_id').val('<?php echo $active_plantilla['government_branch_id']?>');
			$('#branch_id').selectize();

			$('#employ_salary_grade').val('<?php echo $active_plantilla['employ_salary_grade']?>');
			$('#employ_monthly_salary_non').val('<?php echo $active_plantilla['employ_monthly_salary']?>');
			$('#salary_step').val('<?php echo $active_plantilla['employ_salary_step']?>');

			$('#plantilla_id_hidden').val('<?php echo $active_plantilla['employ_plantilla_id']?>');
			$('#office_id_hidden').val('<?php echo $active_plantilla['employ_office_id']?>');
			$('#division_id_hidden').val('<?php echo $active_plantilla['employ_division_id']?>');
			$('#employment_status_id_hidden').val('<?php echo $active_plantilla['employment_status_id']?>');
			$('#position_id_hidden').val('<?php echo $active_plantilla['employ_position_id']?>');
			$('#employ_salary_grade_hidden').val('<?php echo $active_plantilla['employ_salary_grade']?>');
			$('#employ_monthly_salary_non_hidden').val('<?php echo $active_plantilla['employ_monthly_salary']?>');
			$('#salary_step_hidden').val('<?php echo $active_plantilla['employ_salary_step']?>');

		<?php else: ?>
			$('.sample').attr('style','display:none !important');
			$('#employ_end_date').attr('type','hidden');
			$('.sample1').switchClass('s6','s12');

			// $('#posted_deliberation_div').css("cssText", "display: none !important"); //12/14/2023

			$('#position_select').addClass("hide");
			$('#position_read').removeClass("hide");
			$('#office_select').addClass("hide");
			$('#division_select').addClass("hide");
			$('#office_read').removeClass("hide");
			$('#division_read').removeClass("hide");

			$('#appointment_flag_hidden').val('1');

			$('.non-appointment').css("cssText", "display: none !important");
			$('.sd-appointment').css("cssText", "display: table !important");
			$('#appointment_flag').prop('checked', true);
			$('#appointment_flag').prop('disabled', true);
		<?php endif;?>


		$('#personnel_movement')[0].selectize.destroy();
		var result = '<option value="">Select Personel Movement</option>';
		var personnel_movement = <?php echo json_encode($personnel_movement) ?>;

		for(var i=0 ; i < personnel_movement.length; i++)
		{
			if(personnel_movement[i]['needs_office_order'] == 'Y')
			{
				result += '<option value="' + personnel_movement[i]['personnel_movement_id'] + '">' + personnel_movement[i]['personnel_movement_name'] + '</option>';
			}
		}

		$('#personnel_movement').html(result).selectize();

		$('#label').html("Personnel Movement");


	}
});

	//GET THE POSITION - AJAX
$("#position_id").on("change", function(){
	var val       = $(this).val();
	var date 	  = $('#employ_start_date').val();
		//===== marvin : start : include employment_status =====//
	var emp_stats = $("#employment_status_id").val();
		//===== marvin : end : include employment_status =====//
	var $params = [];
		// $params = {
		// select_id 		 : val,
		// employ_start_date : date};
		//===== marvin : start : include employment_status =====//
	$params     = {select_id 		 : val,
	employ_start_date : date,
	employment_status : emp_stats};
		//===== marvin : end : include employment_status =====//
	$.post($base_url+"<?php echo PROJECT_MAIN."/pds_work_experience_info/get_param_position"?>",$params, function(result) {
		if(result.flag == "1"){
			// $("#salary_grade").val(result.data.salary_grade);
			// $("#salary_step_info").val(result.data.salary_step);		
			//$("#employ_monthly_salary").val( result.data.amount ); 
			//marvin : divided by 2 if part-time : start
			var position_name = result.data.position_name;
			if(position_name.includes("PART-TIME"))
			{var amount_parttime = parseFloat(result.data.amount.replace(/,/g, ''))/2;
			$("#salary_grade").val(result.data.salary_grade);
			$("#salary_step_info").val(result.data.salary_step);	
			$("#employ_monthly_salary").val(amount_parttime.toFixed(2));
			}
			else
			{
				$("#salary_grade").val(result.data.salary_grade);
				$("#salary_step_info").val(result.data.salary_step);	
				$("#employ_monthly_salary").val( result.data.amount );
			}
				//marvin : divided by 2 if part-time : end
	} else {
		notification_msg("<?php echo ERROR ?>", result.msg);
	}
}, 'json');
});


// $("#position_id").on("change", function(){
// 	var val = $(this).val();
// 	var date = $('#employ_start_date').val();
// 	var emp_stats = $("#employment_status_id").val();
// 	var $params = [];

// 	$params = {select_id: val,
// 	employ_start_date: date,
// 	employment_status: emp_stats,
// };

// $.post($base_url + "<?php //echo PROJECT_MAIN.'/pds_work_experience_info/get_param_position' ?>",$params,function(result) {
// 	if(result.flag == "1"){
// 		$("#salary_grade").val(result.data.salary_grade);
// 		$("#salary_step_info").val(result.data.salary_step);

// 		var position_name = result.data.position_name;
// 		if (position_name.includes("PART-TIME")) {
// 			var amount_parttime = parseFloat(result.data.amount.replace(/,/g, '')) / 2;
// 			$("#employ_monthly_salary").val(amount_parttime.toFixed(2));
// 		} else {
// 			$("#employ_monthly_salary").val(result.data.amount);
// 		}
// 	} else {
// 		notification_msg("<?php// echo ERROR ?>", result.msg);
// 	}
// },'json');
// });

	//CHECK PERSONNEL MOVEMENT
$('#transfer_div').css("cssText", "display: none !important");
var edit_cnt1 = 0;
$("#personnel_movement").on("change", function(){
	$("#employ_monthly_salary_non").attr("readonly", true); 
	var $val    = $(this).val();
	var $params = [];
	$params     = {select_id : $val};
	$.post($base_url+"<?php echo PROJECT_MAIN."/pds_work_experience_info/check_personnel_movement"?>",$params, function(result) {
		if(result.movt_type == 'MOVT_SALARY_INCR') {
			transferSeparation(result.movt_type);
			<?php if($action != ACTION_VIEW): ?>
				$('#step_incr_reason_code')[0].selectize.destroy();
				$('#step_incr_reason_code').attr("disabled", false);
				$('#step_incr_reason_code').selectize();
				$("#step_incr_reason_code")[0].selectize.clear();
				$('#step_incr_reason_code').prop('required', true);
				$("#salary_step").attr("readonly", false); 
			<?php endif?>
			<?php if($action == ACTION_EDIT): ?>
				$('#step_incr_reason_code')[0].selectize.setValue('<?php echo $experience["step_incr_reason_code"]?>');
			<?php endif?>				
		} else if (result.movt_type == 'MOVT_SALARY_ADJUSTMENT') {
			adminOffice(false);
			salaryAdjustment();
			transferSeparation(result.movt_type);
			//ncocampo
			<?php if($action == ACTION_EDIT OR $action == ACTION_VIEW): ?>
				$('#employ_monthly_salary_non').val('<?php echo $experience['employ_monthly_salary']?>');
				$('#employ_salary_grade').val('<?php echo $experience['employ_salary_grade']?>');
				$('#salary_step').val('<?php echo $experience['employ_salary_step']?>');
				<?php endif?>
	
	    	//01/11/2024

		} else if(result.movt_type == 'MOVT_DETAIL') {
			adminOffice(true);
			transferSeparation(result.movt_type);
		} else if(result.movt_type == 'MOVT_TRANSFER_IN') {	
			transferSeparation(result.movt_type);			
			$('#admin_office').css("cssText", "display: none !important");
			$('#admin_office_id').attr('required', false);
			$('#transfer_div').css("cssText", "display: table !important");
		} else {
			$('#step_incr_reason_code')[0].selectize.destroy();
			$('#step_incr_reason_code').attr("disabled", true);
			$('#step_incr_reason_code').selectize();
			$("#step_incr_reason_code")[0].selectize.clear();
			$('#step_incr_reason_code').prop('required', false);
			$("#salary_step").attr("readonly", true);
			$('#admin_office').css("cssText", "display: none !important");
			$('#admin_office_id').attr('required', false);
			$('#transfer_div').css("cssText", "display: none !important");
			transferSeparation(result.movt_type);
		}
	}, 'json');
});

function adminOffice(required_flag){
	$('#admin_office').css("cssText", "display: table !important");
	if(required_flag == true)
	{
		$('#admin_office_id').attr('required', true);
		$('#admin_office span').removeClass('none');
	}
	else
	{
		$('#admin_office_id').attr('required', false);
		$('#admin_office span').addClass('none');
	}
	<?php if($action == ACTION_EDIT): ?>
		if(edit_cnt1 == 0) {
			$('#admin_office_id')[0].selectize.setValue('<?php echo $experience["admin_office_id"]?>');
			edit_cnt1++;
		}
	<?php endif?>
}

   	// SEPARATION TRANFERRED
function transferSeparation(movt_type){
	<?php if($action != ACTION_ADD): ?>
		var separation_mode  		= $('#separation_mode').val();
		$params = {select_id : separation_mode};
		$.post($base_url+"<?php echo PROJECT_MAIN."/pds_work_experience_info/get_separation_mode"?>",$params, function(result) {
			if(result.mode == "MOVT_TRANSFER_OUT") {
				$('#transfer_div').css("cssText", "display: table !important");
			}								
		}, 'json');
	<?php endif?>	
	$('#separation_mode').on('change', function() {
		var separation_mode = $(this).val();

		$params = {select_id : separation_mode};
		$.post($base_url+"<?php echo PROJECT_MAIN."/pds_work_experience_info/get_separation_mode"?>",$params, function(result) {
			if(result.mode == "MOVT_TRANSFER_OUT" || movt_type == "MOVT_TRANSFER_IN") {
				$('#transfer_div').css("cssText", "display: table !important");
			}
			if(result.mode != "MOVT_TRANSFER_OUT" && movt_type != "MOVT_TRANSFER_IN") {
				$('#transfer_div').css("cssText", "display: none !important");
			}								
		}, 'json');
	});
}

function salaryAdjustment(){
	$('#service_start_step').on('change', function() {
		var serv_start_date = $(this).val();
		var emp_id  		= $('#employee_id').val();
		var val 			= $('#salary_step').val();
		var personnel_movement_comp_ret =  $('#personnel_movement').val();
		if(personnel_movement_comp_ret != 675)
		{
			$params = {select_id : val, 
			id 		 : emp_id,
			date      : serv_start_date};
			$.post($base_url+"<?php echo PROJECT_MAIN."/pds_work_experience_info/get_monthly_salary"?>",$params, function(result) {
				if(result.flag == "1"){				
					$("#employ_monthly_salary_non").val(result.amount);
				} else {
					$("#employ_monthly_salary_non").val('');
					notification_msg("<?php echo ERROR ?>", result.msg);	
				}					
			}, 'json');
		}

	});
}


$('#service_start_step').on('change', function() {
	var serv_start_date = $(this).val();
	var emp_id  		= $('#employee_id').val();
	var val 			= $('#salary_step').val();
	var personnel_movement_comp_ret =  $('#personnel_movement').val();
	
	if(personnel_movement_comp_ret != 675)
	{
		$params = {select_id : val, 
		id 		 : emp_id,
		date      : serv_start_date};
		$.post($base_url+"<?php echo PROJECT_MAIN."/pds_work_experience_info/get_monthly_salary"?>",$params, function(result) {
			if(result.flag == "1"){				
					// $("#employ_monthly_salary_non").val(result.amount);
	
					//marvin : divided by 2 if part-time : start
				var position_name = $("#position_id").text();
				if(position_name.includes("PART-TIME"))
				{
					var amount_parttime = parseFloat(result.amount.replace(/,/g, ''))/2;
	
					$("#employ_monthly_salary_non").val(amount_parttime.toFixed(2));
				}
				else
				{
					$("#employ_monthly_salary_non").val( result.amount );
				}
					//marvin : divided by 2 if part-time : end
			} else {
				$("#employ_monthly_salary_non").val('');
				notification_msg("<?php echo ERROR ?>", result.msg);	
			}					
		}, 'json');
	}



});

var edit_cnt = 0;
$('#employ_start_date').on('change', function() {	
	var date      		= $(this).val();
	var val 	  		= $('#position_id').val();
	var plantilla_id 	= $('#plantilla_id').val();

	if($("#plantilla_flag").val() == 1)
	{
			//marvin : include "-" in plantilla : start
		var plantilla = <?php echo json_encode($plantilla); ?>;

		$("#plantilla_id")[0].selectize.destroy();

		var option_html = '';
		if(date < '2014/02/01')
		{
			$("#employ_monthly_salary").prop("readonly", false);
			option_html += '<option value="0">Select Plantilla</option>';		
			option_html += '<option value="00000">-</option>';			
		}
		for(i=0; i<plantilla.length; i++)
		{
			option_html += '<option value="'+plantilla[i].plantilla_id+'">'+plantilla[i].plantilla_code+'</option>';
		}

		$("#plantilla_id").html(option_html).selectize();
			//marvin : include "-" in plantilla : end
	}

	if(plantilla_id == "")
	{
			// param position trigger
		$("#position_id").change();
			// var $params = [];
			// $params     = {select_id 		 : val,
			// 			   employ_start_date : date};

			// $.post($base_url+"<?php //echo PROJECT_MAIN."/pds_work_experience_info/get_param_position"?>",$params, function(result) {
			// 	if(result.flag == "1"){
			// 		$("#salary_grade").val(result.data.salary_grade);
			// 		$("#salary_step_info").val(result.data.salary_step);
			// 		$("#employ_monthly_salary").val( result.data.amount );
			// 	} else {
			// 		notification_msg("<?php //echo ERROR ?>", result.msg);
			// 	}
			// }, 'json');
	}
	else
	{
		var $val    		= $(this).val();		
		var serv_start_date = $('#service_start_step').val();
		var start_date 		= $('#employ_start_date').val();

		var $params = [];
		$params     = {select_id 	      : plantilla_id,
		service_start_date : date};
		$.post($base_url+"<?php echo PROJECT_MAIN."/pds_work_experience_info/get_param_plantilla"?>",$params, function(result) {
			if(result.flag == "1"){
				$("#employ_monthly_salary").val(result.data.amount);
				<?php if($action == ACTION_EDIT): ?>
					if(edit_cnt == 0)
					{
						$('#employ_monthly_salary').val('<?php echo $experience["employ_monthly_salary"]?>');
						edit_cnt++;
					}
				<?php endif?>
			} else {
				notification_msg("<?php echo ERROR ?>", result.msg);
			}
		}, 'json');
	}

		// param position trigger
		// $("#position_id").change();
		// $("#position_id").change();
		// $("#position_id").change();
});

	// GET APPOINTMENT - SALARY INCREASE MONTHLY SALARY
$('#salary_step').on('keyup', function() {
	var $val   			= $(this).val();
	var emp_id  		= $('#employee_id').val();
	var serv_start_date = $('#service_start_step').val();

	var $params = [];
	$params     = {select_id : $val, 
	id 		 : emp_id,
	date      : serv_start_date};
	$.post($base_url+"<?php echo PROJECT_MAIN."/pds_work_experience_info/get_monthly_salary"?>",$params, function(result) {
		if(result.flag == "1"){				
				// $("#employ_monthly_salary_non").val(result.amount);

				//marvin : divided by 2 if part-time : start
			var position_name = $("#position_id").text();
			if(position_name.includes("PART-TIME"))
			{
				var amount_parttime = parseFloat(result.amount.replace(/,/g, ''))/2;

				$("#employ_monthly_salary_non").val(amount_parttime.toFixed(2));
			}
			else
			{
				$("#employ_monthly_salary_non").val( result.amount );
			}
				//marvin : divided by 2 if part-time : end

		} else {
			$("#employ_monthly_salary_non").val('');
			notification_msg("<?php echo ERROR ?>", result.msg);	
		}
	}, 'json');
});

	//GET THE PLANTILLA - AJAX
var edit_cnt = 0;

	/*=====================================MARVIN :: START :: FIXED VIEWING OF SG, STEP AND MONTHLY SALARY=====================================*/
$("#plantilla_id").on("change", function(){
	var $val    		= $(this).val();		
	var serv_start_date = $('#service_start_step').val();
	var start_date 		= $('#employ_start_date').val();

	if($val != 00000)
	{
			//disable office read
		var office_id_read_html = '';
		office_id_read_html += '<label for="office_id_read" class="active">Office<span class="required">*</span></label>';
		office_id_read_html += '<input id="office_id_read" name="office_id_read" value="" disabled type="text" class="validate"/>';
		$("#office_read").html(office_id_read_html);

			//disable position read
			// $("#position_id_read")[0].selectize.destroy();
		var position_id_read_html = '';
		position_id_read_html += '<label for="position_id_read" class="active">Position<span class="required">*</span></label>';
		position_id_read_html += '<input id="position_id_read" name="position_id_read" value="" disabled type="text" class="validate"/>';
		$("#position_read").html(position_id_read_html);

		var $params = [];
		$params     = {select_id 	      : $val,
		service_start_date : serv_start_date,
		employ_start_date  : start_date};
		$.post($base_url+"<?php echo PROJECT_MAIN."/pds_work_experience_info/get_param_plantilla"?>",$params, function(result) {
			if(result.flag == "1")
			{
				
				$("#position").val(result.data.position_id);
				$("#position_id_read").val(result.data.position_name);
				$("#salary_grade").val(result.data.salary_grade);
				$("#salary_step_info").val(result.data.salary_step);
					// $("#employ_monthly_salary").val(result.data.amount);

					//marvin : divided by 2 if part-time : start
				var position_name = result.data.position_name;
				if(position_name.includes("PART-TIME"))
				{
					var amount_parttime = parseFloat(result.data.amount.replace(/,/g, ''))/2;

					$("#employ_monthly_salary").val(amount_parttime.toFixed(2));
				}
				else
				{
					$("#employ_monthly_salary").val( result.data.amount );
				}
					//marvin : divided by 2 if part-time : end
				$("#office").val(result.data.office_id);
				$("#office_id_read").val(result.data.name);
				$("#division_id_hidden").val(result.data.division_id);
				$("#division_id_read").val(result.data.division_name);

					/*===== marvin : start : enable =====*/
				<?php if($action == ACTION_EDIT): ?>
					if(edit_cnt == 0)
					{
						$('#salary_step_info').val('<?php echo $experience["employ_salary_step"]?>');
						$('#employ_monthly_salary').val('<?php echo $experience["employ_monthly_salary"]?>');
						edit_cnt++;
					}
				<?php endif?>
					/*===== marvin : end : enable =====*/
			}
			else
			{
				notification_msg("<?php echo ERROR ?>", result.msg);
			}
		}, 'json');	
	}
	else
	{	
		$("#office_id_read").val("");
		$("#position_id_read").val("");
		// $("#salary_grade").val("");
		// $("#salary_step_info").val("");
		$("#employ_monthly_salary").val("");

		var office = <?php echo json_encode($office); ?>;
		var position = <?php echo json_encode($position); ?>;

			//create office selectize
			// $("#office_id_read").prop("disabled", false);
		$("#office_read").empty();
		var office_id_read_html = '';
		office_id_read_html += '<label for="office_id_read" class="active">Office<span class="required">*</span></label>';
		office_id_read_html += '<select id="office_id_read" name="office_id_read" class="selectize"></select>';

		var office_id_read_option = '';
		for(i=0;i<office.length;i++)
		{
			office_id_read_option += '<option value="'+office[i].office_id+'">'+office[i].name+'</option>';
		}

		$("#office_read").html(office_id_read_html);
		$("#office_id_read").html(office_id_read_option).selectize();

			//create position selectize
		$("#position_read").empty();
		var position_id_read_html = '';
		position_id_read_html += '<label for="position_id_read" class="active">Position<span class="required">*</span></label>';
		position_id_read_html += '<select id="position_id_read" name="position_id_read" class="selectize"></select>';

		var position_id_read_option = '';
		for(i=0;i<position.length;i++)
		{
			position_id_read_option += '<option value="'+position[i].position_id+'">'+position[i].position_name+'</option>';
		}

		$("#position_read").html(position_id_read_html);
		$("#position_id_read").html(position_id_read_option).selectize();

			//get param position

		$("#position_id_read").on("change", function(){

			var val       = $(this).val();
			var date 	  = $('#employ_start_date').val();

			var $params = [];
			$params     = {select_id 		 : val,
			employ_start_date : date};

			$.post($base_url+"<?php echo PROJECT_MAIN."/pds_work_experience_info/get_param_position"?>",$params, function(result) {
					$("#salary_grade").val(result.data.salary_grade);
					$("#salary_step_info").val(result.data.salary_step);					
					$("#employ_monthly_salary").val(result.data.amount);					

			}, 'json');
		})
	}
});
	/*=====================================MARVIN :: END :: FIXED VIEWING OF SG, STEP AND MONTHLY SALARY=====================================*/

   // GET APPOINTMENT MONTHLY SALARY
$('#salary_step_info').on('keyup', function() {
	// davcorrea 10/09/2023 added if statement to bypass error when editting salary step increment
	var salary_input = $('#salary_step_info').val();
		if(salary_input.length != 0)
		{
			var $val    	  = $(this).val();
			var plantilla_id  = $('#plantilla_id').val();
			var start_date 	  = $('#employ_start_date').val();

				//marvin : include sg : start
			var salary_grade = $("#salary_grade").val();
				//marvin : include sg : end

			var $params = [];
				//marvin : include sg parameter : start
				// $params     = {select_id : $val, 
							// id 		 : plantilla_id,
							// date      : start_date,
							// sg : salary_grade};
				//marvin : include sg parameter : end
			$params     = {select_id : $val, 
			id 		 : plantilla_id,
			date      : start_date};
			$.post($base_url+"<?php echo PROJECT_MAIN."/pds_work_experience_info/get_appt_monthly_salary"?>",$params, function(result) {
				if(result.flag == "1"){				
					$("#employ_monthly_salary").val(result.amount);
				} else {
					$("#employ_monthly_salary").val('');
					notification_msg("<?php echo ERROR ?>", result.msg);
				}
			}, 'json');
		}
});


	//SAVING & UPDATING - PROCESS WORK EXPERIENCE
$('#form_experience').parsley();
jQuery(document).off('click', '#save_experience');
jQuery(document).on('click', '#save_experience', function(e){	
	$("#form_experience").trigger('submit');
});

jQuery(document).off('submit', '#form_experience');
jQuery(document).on('submit', '#form_experience', function(e){
	e.preventDefault();

	if ( $(this).parsley().isValid() ) {
		var data = $('#form_experience').serialize();
		var process_url = "";
		<?php if($module == MODULE_PERSONNEL_PORTAL):?>
			process_url = $base_url + 'main/pds_record_changes_requests/process_work_experience_doh';
		<?php else: ?>
			process_url = $base_url + 'main/pds_work_experience_info/process_work_experience_doh';
		<?php endif; ?>
		button_loader('save_experience', 1);
		var option = {
			url  : process_url,
			data : data,
			success : function(result){
				if(result.status)
				{
					notification_msg("<?php echo SUCCESS ?>", result.message);
					modal_work_experience_doh.closeModal();
					$('#work_experience_tab').trigger('click');
				}
				else
				{
					notification_msg("<?php echo ERROR ?>", result.message);
				}	

			},

			complete : function(jqXHR){
				button_loader('save_experience', 0);
			}
		};

		General.ajax(option);    
	}
});

$('.cancel_modal').click(function() {
	$('#work_experience_tab').trigger('click');

});


	/*===================================================== MARVIN : START : AJAX PLANTILLA FOR SUBS =====================================================*/
$("#employment_status_id").on("change", function(){

	if($(this).val()== 88)
	{
		$('#plantilla_id')[0].selectize.destroy();
		var result = '<option value="">Select Item Number</option>';

		$.post($base_url + 'main/pds_work_experience_info/get_plantilla_subs',
		{
			"employment_status_id" : $(this).val()
		},
		function(data)
		{
			for(i=0; i<data.length; i++)
			{
				result += '<option value="'+data[i].plantilla_id+'">'+data[i].plantilla_code+'</option>';					
			}
			$('#plantilla_id').html(result).selectize();
		},"json");
	}
	else if($(this).val() == 118)
	{

			// param position trigger
		$("#position_id").change();
		$("#position_id").change();
		$("#position_id").change();	
	}



});
	/*===================================================== MARVIN : END : AJAX PLANTILLA FOR SUBS =====================================================*/

	/*============================================ MARVIN : START : AJAX PERSONNEL MOVEMENT FOR COMP. RET. =============================================*/
$("#personnel_movement").on("change", function(){

	if($(this).val() == 675)
	{	
		var employ_salary_grade 		= '<?php echo $active_plantilla['employ_salary_grade']; ?>';
		var salary_step 				= '<?php echo $active_plantilla['employ_salary_step']; ?>';
		var service_start				= $("#service_start_step").val();
		var bday 						= '<?php echo date_format(date_create($personal_info['birth_date']), "Y-m-d");?>';
		var service_start_step 			= moment(bday).add(777, 'months').format('YYYY/MM/DD');
		
		if(service_start === "")
		{
			$("#service_start_step").val(service_start_step);
			$("#service_start_step_label").addClass("active");
			// alert(service_start_step);
		}
		
		$.post($base_url + 'main/pds_work_experience_info/get_salary_sched',
		{
			"employ_salary_grade" 	: employ_salary_grade,
			"salary_step" 			: salary_step,
			"service_start_step" 	: service_start_step,
			"action"				: <?php echo $action; ?>
		},
		function(result)
		{
			if(result.status)
			{
				$("#employ_salary_grade").removeAttr("disabled").val(result.message[0].salary_grade);
				$("#salary_step").val(result.message[0].salary_step);
				$("#employ_monthly_salary_non").val(result.message[0].amount);
			}
			else
			{
				notification_msg("<?php echo ERROR ?>", err_message);
			}
		},"json");
	}
	else
	{
		$("#employ_salary_grade").val('<?php echo $active_plantilla['employ_salary_grade']; ?>');
		$("#salary_step").val('<?php echo $active_plantilla['employ_salary_step']; ?>');
		$("#employ_monthly_salary_non").val('<?php echo $active_plantilla['employ_monthly_salary']; ?>');
	}
});
	/*============================================ MARVIN : END : AJAX PERSONNEL MOVEMENT FOR COMP. RET. ===============================================*/

	/*============================================ MARVIN : START : AJAX STEP INCREMENT REASON. =============================================*/
$("#personnel_movement").on('change', function(){

	if($(this).val() == 661)
	{
		var service_start_step = $("#service_start_step").val();
		var action = <?php echo $action; ?>;
		$.post($base_url + 'main/pds_work_experience_info/get_step_increment_reason',
		{
			"service_start_step" : service_start_step
		},
		function(result)
		{
			if(action != 3)
			{
				
			
			if(result.status)
			{	
				$("#step_incr_reason_code")[0].selectize.destroy();
				var custom_html = '<option value="">Select Item Number</option>';

				for(i=0; i<result.message.length; i++)
				{
					custom_html += '<option value="'+result.message[i].sys_param_value+'">'+result.message[i].sys_param_name+'</option>';
				}

				$("#step_incr_reason_code").html(custom_html).selectize();
			}
			}
			 // else
			 // {
			 // 	notification_msg("<?php //echo ERROR ?>", result.message);
			 // }
		},"json");
	}
});
	/*============================================ MARVIN : END : AJAX STEP INCREMENT REASON. =============================================*/
/*============================================ davcorrea : START : SALARY STEP EDITTING. =============================================*/
$("#step_incr_reason_code").on('change', function(){
		var test = $('#employment_status_id').val();
		if($(this).val() != "MI" && $("#personnel_movement").val() == '661')
		{
			$('#employment_status_id')[0].selectize.destroy();
			$('#employment_status_id').attr("disabled", true);
			$("#employment_status_id").val(test);
			$('#employment_status_id').selectize();
			$('#employment_status_id').prop('required', true);
		}
		else
		{
			$('#employment_status_id')[0].selectize.destroy();
			$('#employment_status_id').attr("disabled", false);
			$("#employment_status_id").val(test);
			$('#employment_status_id').selectize();
			$('#employment_status_id').prop('required', false);
		}
	});
$("#personnel_movement").on('change', function(){
		
		if($(this).val() == 640 || $(this).val() == 653)
		{
			$("#salary_step_info").prop("readonly", true);
		}
		else
		{
			$("#salary_step_info").prop("readonly", false);
		}
	});
	/*============================================ davcorrea : END :  =============================================*/
$("#personnel_movement").on('change', function(){
		
		if($(this).val() == 659 || $(this).val() == 675 || $(this).val() == 661)
		{
			$('#posted_in').prop("required", false);
			$('#posted_in_label').html('');
			$('#period_of_emp_div').css("cssText", "display: none !important");
		}
		else{
			// $('#posted_in').prop("required", true);
			// $('#posted_in_label').html('*');
		}
	});
$("#employment_status_id").on("change", function(){
	var app_flag = $('#appointment_flag').val();
	if($(this).val()== 78)
	{
		$('#period_of_emp_div').css("cssText", "display: table !important");
	}else if ($(this).val()== 80){
		$('#period_of_emp_div').css("cssText", "display: table !important");
	}
	else if ($(this).val()== 89){
		$('#period_of_emp_div').css("cssText", "display: table !important");
	}else{
		$('#period_of_emp_div').css("cssText", "display: none !important");
	}
	if(app_flag == 1)
	{
		var reqs = [78, 81, 118, 119, 121];
		var isRequired = !reqs.includes(parseInt($(this).val()));
		$('#publication_place, #posted_in').prop("required", isRequired);
		$('#publication_place_label, #posted_in_label').html(isRequired ? '*' : '');
	}
	


});
$('#office_id').on("change", function() {
	var office_id = $(this).val();
	var division_id = $('#division_id_hidden').val();
	$.post($base_url + 'main/pds_work_experience_info/get_divisions',
		{
			"office_id" : office_id
		},
		function(result)
		{
			if(action != 3)
			{
				
			
			if(result.status)
			{	
				$("#division_id")[0].selectize.destroy();
				var custom_html = '<option value="0" selected>N/A</option>';

				for(i=0; i<result.message.length; i++)
				{
					if(result.message[i].office_id == division_id)
					{
						custom_html += '<option value="'+result.message[i].office_id+'" selected >'+result.message[i].name+'</option>';
					}
					else
					{
						custom_html += '<option value="'+result.message[i].office_id+'">'+result.message[i].name+'</option>';
					}
						
				}

				$("#division_id").html(custom_html).selectize();
			}
			}
			 // else
			 // {
			 // 	notification_msg("<?php //echo ERROR ?>", result.message);
			 // }
		},"json");
});

$("#service_start_step").on("change", function(){

	if($("#personnel_movement").val() == 675)
	{	
		var bday 	= '<?php echo date_format(date_create($personal_info['birth_date']), "Y-m-d");?>';
		var sg1 = moment(bday).add(777, 'months').format('YYYY/MM/DD');
		var mess = "Invalid Service Start Date. Please enter <b>" + sg1 + "</b>";
		
		if($("#service_start_step").val() != sg1)
		{
			notification_msg("<?php echo ERROR ?>", mess, true);
			$("#service_start_step").val(sg1);
			$("#service_start_step_label").addClass("active");
		}
	}
});


})
</script>