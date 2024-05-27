<form id="role_form">
	<div class="scroll-pane" style="height: 400px">
		<div class="form-float-label">
			<div class="row">
			  <div class="col s12">
				<div class="input-field">
				  <label for="leave_type" class="active">Type of leave</label>
				  <select id="leave_type" <?php echo $action_id==ACTION_VIEW ? 'disabled' : '' ?> class="selectize" placeholder="Select Leave">
					<option value="">Select Leave</option>
					<option value="vacation_leave">Vacation Leave</option>
					<option value="sick_leave">Sick Leave</option>
					<option value="maternity_leave">Maternity Leave</option>
					<option value="others">Others</option>
				  </select>
				</div>
			  </div>
			</div>
			<div class="row">
				<div id="div_vacation_type" class="hide">
				  <div class="col s12">
				  	<div class="input-field m-t-n p-b-md">
					  	<label>&nbsp;</label>
					  	<div>
							<input type="radio" name="vacation_type" checked value="to_seek_employment" id="check_1" >
							<label for="check_1">to seek employment</label> <br>
							<input type="radio" name="vacation_type" value="others" id="check_2">
							<label for="check_2">others</label>
							<div class="input-field hide" id="others_vacation_div">
								<input id="others_vacation" name="others_vacation" type="text" class="validate">
							    <label for="others_vacation" >Specify</label>
						    </div>
					    </div>
				    </div>
				  </div>
				</div>
			</div>
			<div class="row">
				<div id="div_present_leave_div" class="hide">
				  <div class="col s12 p-b-sm">
					<div class="input-field">
						<label class="active">Where leave will be present</label>	
						<div id="vacation_leave_present_div" class="hide">
						  	<input type="radio" name="vacation_leave_present" checked value="Within the philippines" id="within_philippines" >
							<label for="within_philippines">Within the philippines</label> <br>
							<input type="radio" name="vacation_leave_present" value="Abroad" id="abroad">
							<label for="abroad" class="m-b-md">Abroad</label>
							<div class="input-field hide" id="abroad_vacation_div">
								<input id="abroad_vacation" name="abroad_vacation" type="text" class="validate">
						    	<label for="abroad_vacation" >Specify</label>
						    </div>
						</div>
						<div id="sick_leave_present_div" class="hide">
							<div class="row m-n p-b-md">
								<div class="col s4">
							  		<input type="radio" name="sick_leave_present" checked value="to_seek_employment" id="in_hospital" >
									<label for="in_hospital">In Hospital</label> <br>
								</div>
								<div class="col s4">
									<input type="radio" name="sick_leave_present" value="others" id="out_hospital">
									<label for="out_hospital">Out Hospital</label>
								</div>
							</div>
							<div class="row m-n">
								<div class="col s12">
									<div class="input-field">
										<input id="in_hospital_specify" name="in_hospital_specify" type="text" class="validate">
					    				<label for="in_hospital_specify" >Specify</label>
					    			</div>
					    		</div>
							</div>
						</div>
					</div>
				  </div>
				</div>
			</div>
			<div class="row p-b-md">
				<div class="col s12">
					<div class="input-field">
						<label for="number_working_days" class="active">Number of Working Days Applied</label>
					</div>	
				</div>
			</div>
			<div class="row">
				<div class="col s6">
					<div class="input-field">
						<input id="for" name="in_hospital_specify" type="text" class="validate">
						<label for="for" >For:</label>
					</div>
				</div>
				<div class="col s6">
				<div class="input-field">
					<input id="inclusive_dates" name="in_hospital_specify" type="text" class="validate datepicker">
					<label for="inclusive_dates">Inclusive Dates</label>
				</div>
				</div>
			</div>
			<div class="row p-b-md">
				<div class="col s12">
					<div class="input-field">
						<label for="number_working_days" class="active">Commutation</label>
					</div>	
				</div>
			</div>
			<div class="row">
				<div class="row m-n p-b-md p-t-md">
					<div class="col s4">
				  		<input type="radio" name="commutation" checked value="requested" id="requested" >
						<label for="requested">Requested</label> <br>
					</div>
					<div class="col s4">
						<input type="radio" name="commutation" value="not_requested" id="not_requested">
						<label for="not_requested">Not Requested</label>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col s6">
					<div class="row">
						<div class="col s12">
							<div class="input-field">
								<input id="certification_leave" name="certification_leave" type="text" class="validate">
								<label for="certification_leave">Cretification of Leave Credits as of</label>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col s4">
							<div class="input-field">
								<input id="vacation" name="vacation" type="text" class="validate">
								<label for="vacation">Vacation</label>
							</div>
						</div>
						<div class="col s4">
							<div class="input-field">
								<input id="sick" name="sick" type="text" class="validate">
								<label for="sick">Sick</label>
							</div>
						</div>
						<div class="col s4">
							<div class="input-field">
								<input id="total" name="total" type="text" class="validate">
								<label for="total">Total</label>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col s12">
							<div class="input-field">
								<input id="personnel_officer" name="personnel_officer" type="text" class="validate">
								<label for="personnel_officer">Personnel Officer</label>
							</div>
						</div>
					</div>
				</div>
				<div class="col s6">
					<div class="row p-b-md">
						<div class="col s12">
							<div class="input-field">
								<label for="recommendation">Recommendation</label>
							</div>	
						</div>
					</div>
					<div class="row m-n  p-t-md">
						<div class="col s12">
					  		<input type="radio" name="recommendation" checked value="approval" id="approval" >
							<label for="approval">Approval</label> <br>
						</div>
					</div>
					<div class="row m-n p-t-md">
						<div class="col s8">
					  		<input type="radio" name="recommendation" value="disapproval" id="disapproval" >
							<label for="disapproval">Disapproval due to</label> <br>
						</div>
						<div class="col s12 m-t-n-lg">
							<div class="input-field">
								<input id="due_to" name="due_to" type="text" class="validate">
							</div>
						</div>
					</div>
					<div class="row p-t-md">
						<div class="col s12">
							<div class="input-field">
								<input id="authorized_official" name="authorized_official" type="text" class="validate">
								<label for="authorized_official">Authorized Official</label>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="row p-b-md">
				<div class="col s12">
					<div class="input-field">
						<label for="approved_for" class="active">Approved For:</label>
					</div>	
				</div>
			</div>
			<div class="row">
				<div class="col s4">
					<div class="input-field">
						<input id="days_with_pay" name="days_with_pay" type="text" class="validate">
						<label for="days_with_pay" >Days with pay</label>
					</div>
				</div>
				<div class="col s4">
					<div class="input-field">
						<input id="days_without_pay" name="days_without_pay" type="text" class="validate">
						<label for="days_without_pay">Days without pay</label>
					</div>
				</div>
				<div class="col s4">
					<div class="input-field">
						<input id="others_specify" name="others_specify" type="text" class="validate">
						<label for="others_specify">Others (specify)</label>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col s6">
					<div class="input-field">
						<input id="date" name="date" type="text" class="datepicker">
						<label for="date" >Date</label>
					</div>
				</div>
				<div class="col s6">
					<div class="input-field">
						<input id="authorized_official_approved" name="authorized_official_approved" type="text" class="validate">
						<label for="authorized_official_approved">Authorized Official</label>
					</div>
				</div>
			</div>		
		</div>
	</div>
</form>
	<div class="md-footer default">
		<a class="waves-effect waves-teal btn-flat cancel_modal" id="cancel_service_record">Cancel</a>
	  <?php //if($this->permission->check_permission(MODULE_ROLE, ACTION_SAVE)):?>
	    <button class="btn btn-success  green" id="save_service_record" value="<?php echo BTN_SAVE ?>"><?php echo BTN_SAVE ?></button>
	  <?php //endif; ?>
	</div>
<script>
$(function (){
	$('#leave_type').on('change',function(){
		if($(this).val() == 'vacation_leave')
		{
			$('#div_vacation_type').removeClass('hide');
			$('#div_present_leave_div').removeClass('hide');
			$('#vacation_leave_present_div').removeClass('hide');
			$('#sick_leave_present_div').addClass('hide');

		}
		else if ($(this).val() == 'sick_leave')
		{
			$('#div_leave_type').removeClass('hide');
			$('#div_vacation_type').addClass('hide');
			$('#div_present_leave_div').removeClass('hide');
			$('#sick_leave_present_div').removeClass('hide');
			$('#vacation_leave_present_div').addClass('hide');
		}
		else
		{
			$('#div_vacation_type').addClass('hide');
			$('#div_leave_type').addClass('hide');
			$('#div_present_leave_div').addClass('hide');
			$('#vacation_leave_present_div').addClass('hide');
			$('#sick_leave_present_div').addClass('hide');
		}
	});

	$('input[type=radio][name=vacation_type]').on('change',function(){
		$('#others_vacation').val('');
		if($(this).val() == 'others')
			$('#others_vacation_div').removeClass('hide');
		else
			$('#others_vacation_div').addClass('hide');
	}); 

	$('input[type=radio][name=vacation_leave_present]').on('change',function(){
		$('#abroad_vacation').val('');
		if($(this).val() == 'Abroad')
			$('#abroad_vacation_div').removeClass('hide');
		
		else
			$('#abroad_vacation_div').addClass('hide');
	}); 

	$('.selectize').selectize();
})
</script>