<!-- <form id="form_family"> NCOCAMPO  10/31/2023--> 
	<form id="form_family" autocomplete="off">
		<input type="hidden" name="id" value="<?php echo $id ?>"/>
		<input type="hidden" name="salt" value="<?php echo $salt ?>"/>
		<input type="hidden" name="token" value="<?php echo $token ?>"/>
		<input type="hidden" name="action" value="<?php echo $action ?>"/>
		<input type="hidden" name="module" value="<?php echo $module ?>"/>
		<div class="form-float-label">
			<div class="row">
				<!-- MARVIN : REMOVE : START -->
			<!--<div class='col s4 switch p-md new_record'>
				Deceased?<br><br>
				<label>
					No
					<input name='deceased' type='hidden' value='N'>
					<input id="deceased" name='deceased' type='checkbox' value='Y' <?php //echo $action == ACTION_VIEW ? 'disabled' : '' ?>>
					<span class='lever'></span>
					Yes
				</label>
			</div>-->
			<!-- MARVIN : REMOVE : END -->
			<div class="col s4">
				<div class="input-field">
					<label for="relation_type" class="active">Relationship<span class="required">*</span></label>
					<select id="relation_type" name="relation_type" <?php echo $action==ACTION_VIEW ? 'disabled' : '' ?> required class="selectize" placeholder="Select Relationship">
						<option value="">Select Relationship</option>
						<?php if (!EMPTY($relation_types)): ?>
							<?php foreach ($relation_types as $type): ?>
								<!-- MARVIN : EXCLUDE BROTHER, SISTER AND LEGAL GUARDIAN : START -->
								<?php if(!in_array($type['relation_type_id'], array(3,4,5))): ?>
									<option value="<?php echo $type['relation_type_id'] ?>"><?php echo strtoupper($type['relation_type_name']) ?></option>
								<?php endif; ?>
								<!-- MARVIN : EXCLUDE BROTHER, SISTER AND LEGAL GUARDIAN : END -->
							<?php endforeach;?>
						<?php endif;?>
					</select>
				</div>
			</div>
			<div class="col s12" id="deceased_date">
				<div class="input-field">
					<input type="text" class="validate datepicker" placeholder="YYYY/MM/DD" name="death_date" id="death_date" 
					onkeypress="format_identifications('<?php echo DATE_FORMAT ?>',this.value,event,'death_date')"
					value="<?php echo isset($family['death_date'])? format_date($family['death_date']):"" ?>" <?php echo $action==ACTION_VIEW ? 'disabled' : '' ?>/>
					<label for="death_date" class="active">Date of Death<span class="required">*</span></label>
				</div>
			</div>
		</div>

		<div class="row m-n">
			<div class="col s3">
				<div class="input-field">
					<input type="text" class="validate" required name="relation_last_name" id="relation_last_name" value="<?php echo isset($family['relation_last_name'])? $family['relation_last_name'] : '' ?>" <?php echo $action==ACTION_VIEW ? 'disabled' : '' ?>/>
					<label for="relation_last_name"><span id="last_name">Last Name</span><span class="required">*</span></label>
				</div>
			</div>
			<div class="col s3">
				<div class="input-field">
					<input type="text" class="validate" required name="relation_first_name" id="relation_first_name" value="<?php echo isset($family['relation_first_name'])? $family['relation_first_name'] : '' ?>" <?php echo $action==ACTION_VIEW ? 'disabled' : '' ?>/>
					<label for="relation_first_name">First Name<span class="required">*</span></label>
				</div>
			</div>
			<div class="col s3">
				<div class="input-field">
					<input type="text" class="validate" required name="relation_middle_name" id="relation_middle_name" value="<?php echo isset($family['relation_middle_name'])? $family['relation_middle_name'] : '' ?>" <?php echo $action==ACTION_VIEW ? 'disabled' : '' ?>/>
					<label for="relation_middle_name">Middle Name<span class="required">*</span></label>
				</div>
			</div>
			<div class="col s3">
				<div class="input-field">
					<label for="ext_name" class="active">Extension Name</label>
					<select name="ext_name" id="ext_name" <?php echo $action==ACTION_VIEW ? 'disabled' : '' ?> class="selectize" placeholder="Select Extension Name">
						<option value="">Select Extension Name</option>
						<option value="Jr.">JR.</option>
						<option value="II">II</option>
						<option value="III">III</option>
						<option value="IV">IV</option>
						<option value="V">V</option>
					</select>
				</div>
			</div>
		</div>
		<div class="row m-n">
			<!-- <div class="col s4"> -->
				<div class="col s6"> <!-- ncocampo: change col s4 to col s6 11/13/2023-->
					<!-- marvin -->
					<div id="div_relation_birth_date" class="input-field">
						<input type="text" class="validate datepicker" required placeholder="YYYY/MM/DD" name="relation_birth_date" id="relation_birth_date" 
						onkeypress="format_identifications('<?php echo DATE_FORMAT ?>',this.value,event,'relation_birth_date')"
						value="<?php echo isset($family['relation_birth_date'])? format_date($family['relation_birth_date']) : '' ?>" <?php echo $action==ACTION_VIEW ? 'disabled' : '' ?>/>
						<label for="relation_birth_date" class="active">Date of Birth<span class="required">*</span></label>
						<!-- <label for="relation_birth_date" class="active">Date of Birth</label> -->
					</div>

					<!-- asiagate -->
			  	<!--<div class="input-field">
				   	<input type="text" class="validate datepicker" required placeholder="YYYY/MM/DD" name="relation_birth_date" id="relation_birth_date" 
						   onkeypress="format_identifications('<?php //echo DATE_FORMAT ?>',this.value,event,'relation_birth_date')"
				   		   value="<?php //echo isset($family['relation_birth_date'])? format_date($family['relation_birth_date']) : '' ?>" <?php //echo $action==ACTION_VIEW ? 'disabled' : '' ?>/>
				    <label for="relation_birth_date" class="active">Date of Birth<span class="required">*</span></label>
				</div>-->
			</div>
		</div><!-- //ncocampo: add </div> 11/10/2023-->
		<!-- =============ncocampo: change occupation's place: START 11/10/2023============= -->
		<div class = "row m-n">
			<div class="col s6">
				<div id="div_relation_occupation" class="input-field">
					<input type="text" class="validate" name="relation_occupation" id="relation_occupation" value="<?php echo isset($family['relation_occupation'])? $family['relation_occupation'] : '' ?>"<?php echo ($family['deceased_flag'] == "Y")? 'disabled':'' ?> <?php echo $action==ACTION_VIEW ? 'disabled' : '' ?>/>
					<label for="relation_occupation">Occupation</label>
				</div>
			</div>
		</div>
		<!-- =============ncocampo: change occupation's place: END 11/10/2023=============-->
		<div class="row m-n"><!--  ncocampo -->
			<div class="col s4 input-field">

				<!-- marvin -->
				<div id="div_gender" class="p-t-md">
					<label class="gender">Gender</label>
					<div class="col s4 p-l-n input-field m-t-n">
						<input tabindex="7" id="male" name="gender" disabled value = "M" checked	<?php echo $action == ACTION_VIEW ? 'disabled' : '' ?> type="radio" class="validate with-gap" <?php echo $family['relation_gender_code']== 'M' ? 'checked' : '' ?>>
						<label for="male" class="gender">Male</label>
					</div>
					<div class="col s8 p-l-n input-field m-t-n">
						<input tabindex="8" id="female" name="gender" disabled value = "F" <?php echo $action == ACTION_VIEW ? 'disabled' : '' ?> type="radio" class="validate with-gap" <?php echo $family['relation_gender_code']== 'F' ? 'checked' : '' ?>>
						<label for="female" class="gender">Female</label>
					</div>
				</div>

				<!-- asiagate -->
				<!--<div class="p-t-md">
					<div class="col s4 p-l-n input-field m-t-n">
						<input tabindex="7" id="male" name="gender" disabled value = "M" checked	<?php //echo $action == ACTION_VIEW ? 'disabled' : '' ?> type="radio" class="validate with-gap" <?php //echo $family['relation_gender_code']== 'M' ? 'checked' : '' ?>>
						<label for="male" class="gender">Male</label>
					</div>
					<div class="col s8 p-l-n input-field m-t-n">
						<input tabindex="8" id="female" name="gender" disabled value = "F" <?php //echo $action == ACTION_VIEW ? 'disabled' : '' ?> type="radio" class="validate with-gap" <?php //echo $family['relation_gender_code']== 'F' ? 'checked' : '' ?>>
						<label for="female" class="gender">Female</label>
					</div>
				</div>-->
			</div>
			<div class="col s4">

				<!-- marvin -->
				<div id="div_civil_status" class="input-field">
					<label for="civil_status" class="active">Civil Status<span class="required">*</span></label>
					<select id="civil_status" name="civil_status" <?php echo $action == ACTION_VIEW ? 'disabled' : '' ?><?php echo ($family['deceased_flag'] == "Y")? 'disabled':'' ?> class="selectize" placeholder="Select Civil Status">
						<option value="">Select Civil Status</option>
						<?php if (!EMPTY($civil_status)): ?>
							<?php foreach ($civil_status as $status): ?>
								<option value="<?php echo $status['civil_status_id'] ?>"><?php echo strtoupper($status['civil_status_name']) ?></option>
							<?php endforeach;?>
						<?php endif;?>
					</select>
				</div>

				<!-- asiagate -->
				<!--<div class="input-field">
				 	<label for="civil_status" class="active">Civil Status<span class="required">*</span></label>
				  	<select id="civil_status" name="civil_status" <?php //echo $action == ACTION_VIEW ? 'disabled' : '' ?><?php //echo ($family['deceased_flag'] == "Y")? 'disabled':'' ?> class="selectize" placeholder="Select Civil Status">
						<option value="">Select Civil Status</option>
						<?php //if (!EMPTY($civil_status)): ?>
							<?php //foreach ($civil_status as $status): ?>
								<option value="<?php //echo $status['civil_status_id'] ?>"><?php //echo strtoupper($status['civil_status_name']) ?></option>
							<?php //endforeach;?>
						<?php //endif;?>
				  	</select>
				  </div>-->
				</div>		  
			</div>

			<!-- <div class="row m-n"> -->
			<div class="row m-n" id="div_emp_disable"> <!-- ncocampo: add id="div_emp_disable 11/13/2023 -->
				<div class="col s4">

					<!-- marvin -->
					<div id="div_employment_status" class="input-field">
						<label for="employment_status" class="active">Employment Status<span class="required">*</span></label>
						<select id="employment_status" name="employment_status" <?php echo ($family['deceased_flag'] == "Y")? 'disabled':'' ?> <?php echo $action == ACTION_VIEW ? 'disabled' : '' ?> class="selectize" placeholder="Select Employment Status">
							<option value="">Select Employment Status</option>
							<?php if (!EMPTY($employment_status)): ?>
								<?php foreach ($employment_status as $status): ?>
									<option value="<?php echo $status['relation_employment_status_id'] ?>"><?php echo strtoupper($status['relation_employment_status_name']) ?></option>
								<?php endforeach;?>
							<?php endif;?>
						</select>
					</div>

					<!-- asiagate-->
				<!--<div class="input-field">
				 	<label for="employment_status" class="active">Employment Status<span class="required">*</span></label>
				 	<select id="employment_status" name="employment_status" <?php //echo ($family['deceased_flag'] == "Y")? 'disabled':'' ?> <?php //echo $action == ACTION_VIEW ? 'disabled' : '' ?> class="selectize" placeholder="Select Employment Status">
						<option value="">Select Employment Status</option>
						<?php //if (!EMPTY($employment_status)): ?>
							<?php //foreach ($employment_status as $status): ?>
								<option value="<?php //echo $status['relation_employment_status_id'] ?>"><?php //echo strtoupper($status['relation_employment_status_name']) ?></option>
							<?php //endforeach;?>
						<?php //endif;?>
				  	</select>
				  </div>-->
				</div>
				<div class="col s4">

					<!-- marvin -->
					<div id="div_disable_flag" class="input-field">
						<input type="checkbox" class="labelauty" name="disable_flag" id="disable_flag" value="<?php echo ACTIVE ?>" data-labelauty="Tag as PWD|PWD"<?php echo ($family['deceased_flag'] == "Y")? 'disabled':'' ?> <?php echo ($family['pwd_flag'] == "Y")? " checked ":"" ?><?php echo ($action == ACTION_VIEW) ? ' disabled ' : '' ?>/>			
					</div>

					<!-- asiagate -->
				<!--<div class="input-field">
			   		<input type="checkbox" class="labelauty" name="disable_flag" id="disable_flag" value="<?php //echo ACTIVE ?>" data-labelauty="Tag as PWD|PWD"<?php// echo ($family['deceased_flag'] == "Y")? 'disabled':'' ?> <?php //echo ($family['pwd_flag'] == "Y")? " checked ":"" ?><?php //echo ($action == ACTION_VIEW) ? ' disabled ' : '' ?>/>			
			   	</div>-->
			   </div>
			   <div class="col s4">

			   	<!--marvin-->
			   	<!-- =============ncocampo: I uncomment this to change its location: START 11/10/2023=============-->
			   	<!-- <div id="div_relation_occupation" class="input-field">
			   		<input type="text" class="validate" name="relation_occupation" id="relation_occupation" value="<?php //echo isset($family['relation_occupation'])? $family['relation_occupation'] : '' ?>"<?php //echo ($family['deceased_flag'] == "Y")? 'disabled':'' ?> <?php //echo $action==ACTION_VIEW ? 'disabled' : '' ?>/>
			   		<label for="relation_occupation">Occupation</label>
			   	</div> -->
			   	<!-- =============ncocampo: I uncomment this to change location: END 11/10/2023=============-->
			   	<!-- asiagate -->
				<!--<div class="input-field">
				   	<input type="text" class="validate" name="relation_occupation" id="relation_occupation" value="<?php //echo isset($family['relation_occupation'])? $family['relation_occupation'] : '' ?>"<?php //echo ($family['deceased_flag'] == "Y")? 'disabled':'' ?> <?php // echo $action==ACTION_VIEW ? 'disabled' : '' ?>/>
				    <label for="relation_occupation">Occupation</label>
				</div>-->
			</div>
		</div>  

		<div class="row m-n">
			<div class="col s4">

				<!-- marvin -->
				<div id="div_relation_contact_num" class="input-field">
					<input type="text" class="validate" name="relation_contact_num" id="relation_contact_num" 
					value="<?php echo !EMPTY($family['relation_contact_num']) ? format_identifications($family['relation_contact_num'], CELLPHONE_FORMAT):"" ?>"
					type="text" class="validate"
					onkeypress="format_identifications('<?php echo CELLPHONE_FORMAT ?>',this.value,event,'relation_contact_num')"
					onkeydown="return ( event.shiftKey || event.ctrlKey || event.altKey 
						|| (47<event.keyCode 
							&& event.keyCode<58 && event.shiftKey==false) || (95
							<event.keyCode && event.keyCode<106) || (event.keyCode==8) ||
							(event.keyCode==9) || (event.keyCode>34 && event.keyCode<40) ||
							(event.keyCode==46) || (event.keyCode==173) || (event.charCode <
							44 && event.charCode > 39))"
							<?php echo ($family['deceased_flag'] == "Y")? 'disabled':'' ?><?php echo $action == ACTION_VIEW ? 'disabled' : '' ?>/>
							<label for="relation_contact_num">Contact No.</label>
						</div>

						<!-- asiagate -->
				<!--<div class="input-field">
				   	<input type="text" class="validate" name="relation_contact_num" id="relation_contact_num" 
				   			value="<?php echo !EMPTY($family['relation_contact_num']) ? format_identifications($family['relation_contact_num'], CELLPHONE_FORMAT):"" ?>"
									type="text" class="validate"
									onkeypress="format_identifications('<?php echo CELLPHONE_FORMAT ?>',this.value,event,'relation_contact_num')"
									onkeydown="return ( event.shiftKey || event.ctrlKey || event.altKey 
										                    || (47<event.keyCode 
									&& event.keyCode<58 && event.shiftKey==false) || (95
								<event.keyCode && event.keyCode<106) || (event.keyCode==8) ||
								(event.keyCode==9) || (event.keyCode>34 && event.keyCode<40) ||
								(event.keyCode==46) || (event.keyCode==173) || (event.charCode <
								44 && event.charCode > 39))"
								<?php echo ($family['deceased_flag'] == "Y")? 'disabled':'' ?><?php echo $action == ACTION_VIEW ? 'disabled' : '' ?>/>
				    <label for="relation_contact_num">Contact No.</label>
				</div>-->
			</div>
			<div class="col s4">

				<!--marvin-->
				<div id="div_relation_company" class="input-field">
					<input type="text" class="validate" name="relation_company" id="relation_company" value="<?php echo isset($family['relation_company'])? $family['relation_company'] : '' ?>" <?php echo ($family['deceased_flag'] == "Y")? 'disabled':'' ?><?php echo $action==ACTION_VIEW ? 'disabled' : '' ?>/>
					<label for="relation_company">Company Name</label>
				</div>
				
				<!-- asiagate-->
				<!--<div class="input-field">
				   	<input type="text" class="validate" name="relation_company" id="relation_company" value="<?php echo isset($family['relation_company'])? $family['relation_company'] : '' ?>" <?php echo ($family['deceased_flag'] == "Y")? 'disabled':'' ?><?php echo $action==ACTION_VIEW ? 'disabled' : '' ?>/>
				    <label for="relation_company">Company Name</label>
				</div>-->
			</div>
			<div class="col s4">

				<!--marvin-->
				<div id="div_relation_company_address" class="input-field">
					<input type="text" class="validate" name="relation_company_address" id="relation_company_address" value="<?php echo isset($family['relation_company_address'])? $family['relation_company_address'] : '' ?>"<?php echo ($family['deceased_flag'] == "Y")? 'disabled':'' ?> <?php echo $action==ACTION_VIEW ? 'disabled' : '' ?>/>
					<label for="relation_company_address">Company Address</label>
				</div>
				
				<!--asiagate-->
				<!--<div class="input-field">
				   	<input type="text" class="validate" name="relation_company_address" id="relation_company_address" value="<?php echo isset($family['relation_company_address'])? $family['relation_company_address'] : '' ?>"<?php echo ($family['deceased_flag'] == "Y")? 'disabled':'' ?> <?php echo $action==ACTION_VIEW ? 'disabled' : '' ?>/>
				    <label for="relation_company_address">Company Address</label>
				</div>-->
			</div>
		</div>
	</div><br><br><br><br>
	<?php if ($action != ACTION_VIEW): ?>
		<div class="md-footer default">
			<a class="waves-effect waves-teal btn-flat cancel_modal">Cancel</a>
			<?php //if($this->permission->check_permission(MODULE_ROLE, ACTION_SAVE)):?>
			<button class="btn btn-success " type="button" id="save_family" value="<?php echo BTN_SAVE ?>"><?php echo BTN_SAVE ?></button>
			<?php //endif; ?>
		</div>
	<?php endif; ?>
</form>
<script>
	$( document ).ready(function() {
		//=============ncocampo: hides deleted fields: START 11/13/2023//=============
		$("#div_relation_birth_date").hide();
		$("#div_gender").hide();
		$("#div_civil_status").hide();
		$("#div_relation_occupation").hide();
		$("#div_relation_contact_num").hide();
		$("#div_relation_company").hide();
		$("#div_relation_company_address").hide();
		$("#div_emp_disable").css("cssText", "display: none !important");
		//=============ncocampo hides deleted fields:END 11/13/2023//=============
		<?php if( $family["deceased_flag"] =="Y" ):?>
			$('#deceased').prop('checked', true);
			$("#deceased_date").css("cssText", "display: table !important");
			$('#civil_status').prop('required', false);
			$('#employment_status').prop('required', false);
		<?php else: ?>
			$("#deceased_date").css("cssText", "display: none !important");
			$('#civil_status').prop('required', true);
			$('#employment_status').prop('required', true);
		<?php endif; ?>
		
	});

	$(function (){

		var edit_cnt = 0;
	//MARVIN
		$('#relation_type').on( "change", function() {
			var selected = $(this).val();
			$('#female').prop('disabled', true);
			$('#male').prop('disabled', true);

			edit_cnt ++;
			switch(selected)
			{
			case '<?php echo FAMILY_FATHER?>':
				var last_name = 'Last Name';
				$('#last_name').empty().append(last_name);
				$('#male').prop('checked', true);		
				$("#relation_birth_date").prop("required", false);
				$("#civil_status").prop("required", false);
				$("#employment_status").prop("required", false);

				
				$("#div_relation_birth_date").hide(); 
				$("#div_gender").hide();
				$("#div_civil_status").hide();
				$("#div_employment_status").hide();
				$("#div_disable_flag").hide();
				$("#div_relation_occupation").hide();
				$("#div_relation_contact_num").hide();
				$("#div_relation_company").hide();
				$("#div_relation_company_address").hide();
				break;
			case '<?php echo FAMILY_MOTHER?>':
				var maiden_name = 'Maiden Name';
				$('#last_name').empty().append(maiden_name);
				$('#female').prop('checked', true);
				
				$("#relation_birth_date").prop("required", false);
				$("#civil_status").prop("required", false);
				$("#employment_status").prop("required", false);
				
				$("#div_relation_birth_date").hide();
				$("#div_gender").hide();
				$("#div_civil_status").hide();
				$("#div_employment_status").hide();
				$("#div_disable_flag").hide();
				$("#div_relation_occupation").hide();
				$("#div_relation_contact_num").hide();
				$("#div_relation_company").hide();
				$("#div_relation_company_address").hide();
				break;
			case '<?php echo FAMILY_BROTHER?>':
				var last_name = 'Last Name';
				$('#last_name').empty().append(last_name);
				$('#male').prop('checked', true);
				break;
			case '<?php echo FAMILY_SISTER?>':
				var last_name = 'Last Name';
				$('#last_name').empty().append(last_name);
				$('#female').prop('checked', true);
				break;
			case '<?php echo FAMILY_GUARDIAN?>':
				var last_name = 'Last Name';
				$('#last_name').empty().append(last_name);
				$('#female').prop('checked', false);
				$('#female').prop('disabled', false);
				$('#male').prop('checked', false);
				$('#male').prop('disabled', false);
				<?php if($action != ACTION_ADD): ?>
					if(edit_cnt < 2)
					{						
						<?php if($family['relation_gender_code']== 'M'): ?>			
							$('#male').prop('checked', true);
						<?php elseif($family['relation_gender_code']== 'F'):?>						
							$('#female').prop('checked', true);				
						<?php endif; ?>	
					}
				<?php endif; ?>	
				break;
			case '<?php echo FAMILY_SPOUSE?>':
				var last_name = 'Last Name';
				$('#last_name').empty().append(last_name);
				$('#female').prop('checked', false);
				$('#female').prop('disabled', false);
				$('#male').prop('checked', false);
				$('#male').prop('disabled', false);
				<?php if($action != ACTION_ADD): ?>
					if(edit_cnt < 2)
					{						
						<?php if($family['relation_gender_code']== 'M'): ?>			
							$('#male').prop('checked', true);
						<?php elseif($family['relation_gender_code']== 'F'):?>						
							$('#female').prop('checked', true);				
						<?php endif; ?>	
					}
				<?php endif; ?>	
				
				$("#relation_birth_date").prop("required", false);
				$("#civil_status").prop("required", false);
				$("#employment_status").prop("required", false);
				
				$("#div_relation_birth_date").hide();
				$("#div_gender").hide();
				$("#div_civil_status").hide();
				$("#div_employment_status").hide();
				$("#div_disable_flag").hide();
				$("#div_relation_occupation").show();
				$("#div_relation_contact_num").show();
				$("#div_relation_company").show();
				$("#div_relation_company_address").show();
				break;
			case '<?php echo FAMILY_CHILD?>':
				var last_name = 'Last Name';
				$('#last_name').empty().append(last_name);
				$('#female').prop('checked', false);
				$('#female').prop('disabled', false);
				$('#male').prop('checked', false);
				$('#male').prop('disabled', false);
				<?php if($action != ACTION_ADD): ?>
					if(edit_cnt < 2)
					{						
						<?php if($family['relation_gender_code']== 'M'): ?>			
							$('#male').prop('checked', true);
						<?php elseif($family['relation_gender_code']== 'F'):?>						
							$('#female').prop('checked', true);				
						<?php endif; ?>	
					}
				<?php endif; ?>	
				
				$("#relation_birth_date").prop("required", true);
				$("#civil_status").prop("required", false);
				$("#employment_status").prop("required", false);
				
				$("#div_relation_birth_date").show();
				$("#div_gender").hide();
				$("#div_civil_status").hide();
				$("#div_employment_status").hide();
				$("#div_disable_flag").hide();
				$("#div_relation_occupation").hide();
				$("#div_relation_contact_num").hide();
				$("#div_relation_company").hide();
				$("#div_relation_company_address").hide();
				break;
			}
		});

	//ASIAGATE
	// $('#relation_type').on( "change", function() {
  		// var selected = $(this).val();
		// $('#female').prop('disabled', true);
		// $('#male').prop('disabled', true);

  		// edit_cnt ++;
		// switch(selected)
		// {
			// case '<?php echo FAMILY_FATHER?>':
				// var last_name = 'Last Name';
				// $('#last_name').empty().append(last_name);
				// $('#male').prop('checked', true);
			// break;
			// case '<?php echo FAMILY_MOTHER?>':
				// var maiden_name = 'Maiden Name';
				// $('#last_name').empty().append(maiden_name);
				// $('#female').prop('checked', true);
			// break;
			// case '<?php echo FAMILY_BROTHER?>':
				// var last_name = 'Last Name';
				// $('#last_name').empty().append(last_name);
				// $('#male').prop('checked', true);
			// break;
			// case '<?php echo FAMILY_SISTER?>':
				// var last_name = 'Last Name';
				// $('#last_name').empty().append(last_name);
				// $('#female').prop('checked', true);
			// break;
			// case '<?php echo FAMILY_GUARDIAN?>':
				// var last_name = 'Last Name';
				// $('#last_name').empty().append(last_name);
				// $('#female').prop('checked', false);
				// $('#female').prop('disabled', false);
				// $('#male').prop('checked', false);
				// $('#male').prop('disabled', false);
				// <?php if($action != ACTION_ADD): ?>
					// if(edit_cnt < 2)
					// {						
						// <?php if($family['relation_gender_code']== 'M'): ?>			
							// $('#male').prop('checked', true);
						// <?php elseif($family['relation_gender_code']== 'F'):?>						
							// $('#female').prop('checked', true);				
						// <?php endif; ?>	
					// }
				// <?php endif; ?>	
			// break;
			// case '<?php echo FAMILY_SPOUSE?>':
				// var last_name = 'Last Name';
				// $('#last_name').empty().append(last_name);
				// $('#female').prop('checked', false);
				// $('#female').prop('disabled', false);
				// $('#male').prop('checked', false);
				// $('#male').prop('disabled', false);
				// <?php if($action != ACTION_ADD): ?>
					// if(edit_cnt < 2)
					// {						
						// <?php if($family['relation_gender_code']== 'M'): ?>			
							// $('#male').prop('checked', true);
						// <?php elseif($family['relation_gender_code']== 'F'):?>						
							// $('#female').prop('checked', true);				
						// <?php endif; ?>	
					// }
				// <?php endif; ?>	
			// break;
			// case '<?php echo FAMILY_CHILD?>':
				// var last_name = 'Last Name';
				// $('#last_name').empty().append(last_name);
				// $('#female').prop('checked', false);
				// $('#female').prop('disabled', false);
				// $('#male').prop('checked', false);
				// $('#male').prop('disabled', false);
				// <?php if($action != ACTION_ADD): ?>
					// if(edit_cnt < 2)
					// {						
						// <?php if($family['relation_gender_code']== 'M'): ?>			
							// $('#male').prop('checked', true);
						// <?php elseif($family['relation_gender_code']== 'F'):?>						
							// $('#female').prop('checked', true);				
						// <?php endif; ?>	
					// }
				// <?php endif; ?>	
			// break;
		// }
	// });

$("input[name=deceased]").change(function(){
	if ($('#deceased').is(":checked"))
	{   			
		$('#deceased_date').prop('required', true);
		$("#deceased_date").css("cssText", "display: table !important");
		$("#deceased").val("Y");

		$('#civil_status')[0].selectize.destroy();
		$('#civil_status').attr("disabled", true);
		$('#civil_status').selectize();
		$("#civil_status")[0].selectize.clear();
		$('#civil_status').prop('required', false);

		$('#employment_status')[0].selectize.destroy();
		$('#employment_status').attr("disabled", true);
		$('#employment_status').selectize();
		$("#employment_status")[0].selectize.clear();
		$('#employment_status').prop('required', false);

		$('#relation_contact_num').attr("disabled", true);
		$('#disable_flag').attr("disabled", true);
		$('#relation_occupation').attr("disabled", true);
		$('#relation_company').attr("disabled", true);
		$('#relation_company_address').attr("disabled", true);

	}
	else
	{
		$("#deceased_date").css("cssText", "display: none !important");
		$('#deceased_date').prop('required', false);
		$("#deceased").val("");

		$('#civil_status')[0].selectize.destroy();
		$('#civil_status').attr("disabled", false);
		$('#civil_status').selectize();
		$("#civil_status")[0].selectize.clear();
		$('#civil_status').prop('required', true);

		$('#employment_status')[0].selectize.destroy();
		$('#employment_status').attr("disabled", false);
		$('#employment_status').selectize();
		$("#employment_status")[0].selectize.clear();
		$('#employment_status').prop('required', true);

		$('#relation_contact_num').attr("disabled", false);
		$('#disable_flag').attr("disabled", false);
		$('#relation_occupation').attr("disabled", false);
		$('#relation_company').attr("disabled", false);
		$('#relation_company_address').attr("disabled", false);
	}
});

<?php if($action != ACTION_ADD): ?>
	$("label:not(.gender)").addClass('active');
<?php endif; ?>	

$('#form_family').parsley();
jQuery(document).off('click', '#save_family');
jQuery(document).on('click', '#save_family', function(e){	
	$("#form_family").trigger('submit');
});

jQuery(document).off('submit', '#form_family');
jQuery(document).on('submit', '#form_family', function(e){
	e.preventDefault();

	if ( $(this).parsley().isValid() ) {
		$('#male').prop('disabled', false);
		$('#female').prop('disabled', false);
		var data = $('#form_family').serialize();
		var process_url = "";
		<?php if($module == MODULE_PERSONNEL_PORTAL):?>
			process_url = $base_url + 'main/pds_record_changes_requests/process_family';
		<?php else: ?>
			process_url = $base_url + 'main/pds_family_info/process';
		<?php endif; ?>
		button_loader('save_family', 1);
		var option = {
			url  : process_url,
			data : data,
			success : function(result){
				if(result.status)
				{
					notification_msg("<?php echo SUCCESS ?>", result.message);
					modal_family.closeModal();
					load_datatable('pds_family_table', '<?php echo PROJECT_MAIN ?>/pds_family_info/get_family_list',false,0,0,true);
				}
				else
				{
					notification_msg("<?php echo ERROR ?>", result.message);
				}	

			},

			complete : function(jqXHR){
				button_loader('save_family', 0);
			}
		};

		General.ajax(option);    
	}
});
})
</script>