<?php
// IDENTIFICATION INFORMATION
$tin_val = '';
$tin_format = '';
$sss_val = '';
$sss_format = '';
$gsis_val = '';
$gsis_format = '';
$pagibig_val = '';
$pagibig_format = '';
$philhealth_val = '';
$philhealth_format = '';
$permanent_no = '';
$email = '';
$residential_no = '';
$mobile_no = '';
// CONTACTS VALUE
foreach ($contact_info as $contact) {
	switch ($contact['contact_type_id']) {
		case PERMANENT_NUMBER:
			$permanent_no 	= $contact['contact_value'];	
		break;
		case EMAIL:
			$email 			= $contact['contact_value'];	
		break;
		case RESIDENTIAL_NUMBER:
			$residential_no = $contact['contact_value'];	
		break;
		case MOBILE_NUMBER:
			$mobile_no 		= $contact['contact_value'];	
		break;
	}
}
// IDENTIFICATION VALUE
foreach ($identification_info as $identification) {
	switch ($identification['identification_type_id']) {
		case TIN_TYPE_ID:
			$tin_val 		= $identification['identification_value'];	
		break;
		case SSS_TYPE_ID:
			$sss_val 		= $identification['identification_value'];	
		break;
		case GSIS_TYPE_ID:
			$gsis_val 		= $identification['identification_value'];	
		break;
		case PAGIBIG_TYPE_ID:
			$pagibig_val 	= $identification['identification_value'];	
		break;
		case PHILHEALTH_TYPE_ID:
			$philhealth_val = $identification['identification_value'];	
		break;
	}
}
// IDENTIFICATION FORMAT
foreach ($identification_format as $format) {
	switch ($format['identification_type_id']) {
		case TIN_TYPE_ID:		
			$tin_format 		= $format['format'];
		break;
		case SSS_TYPE_ID:	
			$sss_format 		= $format['format'];
		break;
		case GSIS_TYPE_ID:
			$gsis_format 		= $format['format'];
		break;
		case PAGIBIG_TYPE_ID:
			$pagibig_format 	= $format['format'];
		break;
		case PHILHEALTH_TYPE_ID:	
			$philhealth_format 	= $format['format'];
		break;
	}
}
?>
<form id="form_personal_info" class="m-b-md m-t-n-xxs" autocomplete="off">
	<input type="hidden" name="id" value="<?php echo $id ?>" /> 
	<input type="hidden" name="salt" value="<?php echo $salt ?>" /> 
	<input type="hidden" name="token" value="<?php echo $token ?>" /> 
	<input type="hidden" name="action" value="<?php echo $action ?>" /> 
	<input type="hidden" name="module" value="<?php echo $module ?>" /> 
	<input type="hidden" name="proceed_flag" id="proceed_flag" value="<?php echo ($action == ACTION_ADD) ? 'N':'Y'?>" />
	<input type="hidden" name="job_order_flag" id="jo_flag" value="<?php echo (!EMPTY($personal_info['job_order_flag']) AND $personal_info['job_order_flag'] == 'Y')? '1' : '' ?>" />

	<div class="card m-b-n">
		<div class="card-image p-r-md">
			<div class="">
				<div class="form-float-label">
				<?php if ($module == MODULE_PERSONNEL_PORTAL) { ?>
					<p>Note: The updating of <span style="color:red"><strong>restricted</strong></span> fields must be coordinated with the Personnel Administration Division.</p>
				<?php } ?>
					<div class="row b-t b-light-gray">
						<div class="col s12">
							<div class="input-field">
								<input tabindex="1" id="last_name" name="last_name" required
									value="<?php echo !EMPTY($personal_info['last_name']) ? ucwords($personal_info['last_name']) : '' ?>"
									type="text" class="validate"> <label for="last_name">Last Name
									<?php if($module == MODULE_PERSONNEL_PORTAL){
											echo '<span class="required">(Restricted)</span>';
										}else{
											echo '<span class="required">*</span>';
									}?>
								</label>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col 12">
							<div class="input-field">
								<input tabindex="2" id="first_name" name="first_name" required
									value="<?php echo !EMPTY($personal_info['first_name'])? ucwords($personal_info['first_name']) : '' ?>"
									type="text" class="validate"> <label for="first_name">First
									Name 
									<?php if($module == MODULE_PERSONNEL_PORTAL){
											echo '<span class="required">(Restricted)</span>';
										}else{
											echo '<span class="required">*</span>';
									}?>
								</label>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col s8">
							<div class="input-field">
								<input tabindex="3" id="middle_name" name="middle_name" required
									value="<?php echo !EMPTY($personal_info['middle_name']) ? ucwords($personal_info['middle_name']) : '' ?>"
									type="text" class="validate"> <label for="middle_name">Middle
									Name 
									<?php if($module == MODULE_PERSONNEL_PORTAL){
											echo '<span class="required">(Restricted)</span>';
										}else{
											echo '<span class="required">*</span>';
									}?>
								</label>
							</div>
						</div>
						<div class="col s4">
							<div class="input-field">
								<label for="ext_name" class="active">Extension Name</label> <select
									tabindex="4" name="ext_name" id="ext_name"
									class="selectize validate" placeholder="Select Extension Name">
									<option value="">Select Extension Name</option>
									<option value="JR.">JR.</option>
									<option value="II">II</option>
									<option value="III">III</option>
									<option value="IV">IV</option>
									<option value="V">V</option>
									<option value="VI">VI</option>
									<option value="VII">VII</option>
								</select>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col s3">
							<div class="input-field">
								<input tabindex="5" id="birth_date" name="birth_date" required
									placeholder="YYYY/MM/DD"
									value="<?php echo !EMPTY($personal_info['birthday']) ? format_date($personal_info['birthday']) : '' ?>"
									type="text" 
									<?php if($module == MODULE_PERSONNEL_PORTAL){
										echo 'readonly="readonly"';
									}?> 
									class="<?php if($module == MODULE_PERSONNEL_PORTAL){
										echo 'validate';
									}else{
										echo 'validate datepicker';
									}?>" 
									onkeypress="format_identifications('<?php echo DATE_FORMAT ?>',this.value,event,'birth_date')"> 
									<label for="birth_date" class="active">Date of Birth
										<?php if($module == MODULE_PERSONNEL_PORTAL){
											echo '<span class="required">(Restricted)</span>';
										}else{
											echo '<span class="required">* (YYYY/MM/DD)</span>';
										}?>
									</label>
							</div>
						</div>
						<div class="col s3">
							<div class="input-field">
								<input tabindex="6" id="birth_place" name="birth_place" required
									value="<?php echo !EMPTY($personal_info['birth_place']) ? ucwords($personal_info['birth_place']) : '' ?>"
									type="text" class="validate"> <label for="birth_place">Place of Birth <span class="required">*</span>
								</label>
							</div>
						</div>
						<div class="col s3">
							<div class="input-field">
								<label for="citizenships" class="active">Citizenship <span
									class="required">*</span></label> <select tabindex="7"
									id="citizenships" name="citizenships" required
									class="selectize validate" placeholder="Select Citizenship">
									<option value="">Select Citizenship</option>
									<?php if (!EMPTY($citizenships)): ?>
										<?php foreach ($citizenships as $type): ?>
												<option value="<?php echo $type['citizenship_id'] ?>"><?php echo $type['citizenship_name'] ?></option>
										<?php endforeach;?>
									<?php endif;?>
								</select>
							</div>
						</div>
						<div class="col s3">
							<div class="input-field">
								<label for="citizenship_basis" class="active">Basis of Citizenship<span
									id="required_citi" class="required"></span></label> <select tabindex="8"
									id="citizenship_basis" name="citizenship_basis"
									class="selectize validate" placeholder="Select Basis of Citizenship">
									<option value="">Select Basis of Citizenship</option>
									<?php if (!EMPTY($citizenship_basis)): ?>
										<?php foreach ($citizenship_basis as $basis): ?>
												<option value="<?php echo $basis['sys_param_value'] ?>"><?php echo strtoupper($basis['sys_param_name']) ?></option>
										<?php endforeach;?>
									<?php endif;?>
								</select>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col s12" id = civil_stat_div>
							<div class="input-field">
								<label for="civil_status" class="active">Civil Status <span
									class="required">*</span></label> <select tabindex="9"
									id="civil_status" name="civil_status" required
									class="selectize validate" placeholder="Select Civil Status">
									<option value="">Select Civil Status</option>
									<?php if (!EMPTY($civil_status)): ?>
										<?php foreach ($civil_status as $status): ?>
												<option value="<?php echo $status['civil_status_id'] ?>"><?php echo $status['civil_status_name'] ?></option>
										<?php endforeach;?>
									<?php endif;?>
								</select>
							</div>
						</div>
						<div class="col s12" id = cs_ro_div>
							<div class="input-field">
								<input tabindex="9" id="cs_readonly" name="cs_readonly"
									value="<?php echo !EMPTY($personal_info['civil_status_name'])? strtoupper($personal_info['civil_status_name']) : '' ?>"
									type="text" class="validate"> 
								<label for="cs_readonly">Civil Status <span class="required">(Restricted)</span></label>
							</div>
						</div>
						<div class="col s6 input-field">
							<label class="gender">Sex <span class="required">*</span></label>
							<div class="p-t-md">
								<div class="col s5 p-l-n input-field m-t-n">
									<input tabindex="10" id="male" name="gender" value="M"
										<?php echo $action == ACTION_VIEW ? 'disabled' : ''?>
										type="radio" class="validate with-gap"
										<?php echo $personal_info['gender_code']== 'M' ? 'checked' : '' ?>>
									<label for="male" class="gender">Male</label> 
								</div>
								<div class="col s5 p-l-n input-field m-t-n">
									<input tabindex="11" id="female" name="gender" value="F"
										<?php echo $action == ACTION_VIEW ? 'disabled' : ''?>
										type="radio" class="validate with-gap"
										<?php echo $personal_info['gender_code']== 'F' ? 'checked' : '' ?>>
									<label for="female" class="gender">Female</label>
								</div>
							</div>
						</div>						
					</div>

					<div class="row">
						<div class="col s6">
							<div class="input-field">
								<input tabindex="12" id="height" name="height" required 
									value="<?php echo !EMPTY($personal_info['height']) ? $personal_info['height'] : '' ?>"
									type="text" class="validate"> <label for="height">Height (m) <span
									class="required">*</span></label>
							</div>
						</div>
						<div class="col s6">
							<div class="input-field">
								<input tabindex="13" id="weight" name="weight" required 
									value="<?php echo !EMPTY($personal_info['weight'])? $personal_info['weight'] : '' ?>"
									type="text" class="validate"> <label for="weight">Weight (kg) <span
									class="required">*</span></label>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col s12" style="background-color:teal;">
							<h5 style="color:white; text-align: center; font-size: 1.4rem;">Residential Address</h5>
						</div>
					</div>
					<div class="row">
						<div class="col s6">
							<div class="input-field">
								<label for="region_residential" class="active">Region
									<span class="required">*</span>
								</label> 
								<select tabindex="14" id="region_residential"
									name="region_residential" <?php echo $action==ACTION_VIEW ? 'disabled' : '' ?> required
									class="selectize"
									placeholder="Select region residential">
									
								</select>
							</div>
						</div>
						<div class="col s6">
							<div class="input-field">
								<label for="province_residential" class="active">Province
									<span class="required">*</span>
								</label> 
								<select tabindex="15" id="province_residential"
									name="province_residential" <?php echo $action==ACTION_VIEW ? 'disabled' : '' ?> required
									class="selectize"
									placeholder="Select province residential">
									
								</select>
							</div>
						</div>
					</div>


					<div class="row">
						<div class="col s6">
							<div class="input-field">
								<label for="municipalities_residential" class="active">Municipality
									<span class="required">*</span>
								</label> 
								<select tabindex="16" id="municipalities_residential" name="municipalities_residential" <?php echo $action==ACTION_VIEW ? 'disabled' : '' ?> class="selectize" placeholder="Select Municipality">
									
								</select>
							</div>
						</div>

						
						<div class="col s6">
							<div class="input-field">
								<label for="barangay_residential" class="active">Barangay
									<span class="required">*</span>
								</label>
								 <select tabindex="17" id="barangay_residential"
									name="barangay_residential" <?php echo $action==ACTION_VIEW ? 'disabled' : '' ?> required
									class="selectize"
									placeholder="Select Barangay">

								</select>
							</div>
						</div>		
					</div>

					<div class="row">
						<div class="col s6">
							<div class="input-field">
								<label for="residential_house_num" class="active">House/ Block/ Lot No.<span class="required">*</span>
								</label> <input tabindex="18" type="text"
									name="residential_house_num" required
									id="residential_house_num" class="validate"
									value="<?php echo $residential_address_values[0] ?>"
									placeholder="House Number or Lot No.">
							</div>
						</div>

						<div class="col s6">
							<div class="input-field">
								<label for="residential_street_name" class="active">Street <span class="required">*</span>
								</label> <input tabindex="19" type="text" 
									name="residential_street_name"
									id="residential_street_name" class="validate"
									value="<?php echo $residential_address_values[1];?>">
							</div>
						</div>		
					</div>

					
					<div class="row">
						<div class="col s6">
							<div class="input-field">
								<label for="residential_subdivision_name" class="active">Subdivision/Village <span class="required">*</span>
								</label> <input tabindex="20" type="text"
									name="residential_subdivision_name"
									id="residential_subdivision_name" class="validate"
									value="<?php echo $residential_address_values[2];?>">
							</div>
						</div>
						<div class="col s6">
							<div class="input-field">
								<input tabindex="21" id="residential_zip_code" 
									name="residential_zip_code" required
									value="<?php echo !EMPTY($residential_address_info)? $residential_address_info['postal_number']:"" ?>" maxlength="6" type="text" class="validate"> <label for="residential_zip_code">Zip
									Code <span class="required">*</span></label>
							</div>
						</div>
						
					</div>
					<div class="row">
						<div class="col s12" style="background-color:teal;">
							<h5 style="color:white; text-align: center; font-size: 1.4rem;">Permanent Address</h5>
						</div>
					</div>

					<div class="row">
						<div class="col s6">
							<div class="input-field">
								<label for="region_permanent" class="active">Region
									<span class="required">*</span>
								</label> 
								<select tabindex="22" id="region_permanent"
									name="region_permanent" required
									class="selectize validate"
									placeholder="Select region residential">
									
								</select>
							</div>
						</div>
						<div class="col s6">
							<div class="input-field">
								<label for="province_permanent" class="active">Province
									<span class="required">*</span>
								</label> <select tabindex="23" id="province_permanent"
									name="province_permanent" required
									class="selectize validate"
									placeholder="Select Province Permanent">
									
								</select>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col s6">
							<div class="input-field">
								<label for="municipalities_permanent" class="active">Municipality
									<span class="required">*</span>
								</label> <select tabindex="24" id="municipalities_permanent"
									name="municipalities_permanent" required
									class="selectize validate"
									placeholder="Select Municipality Permanent">
									<option value="" selected></option>
								</select>
							</div>
						</div>
						<div class="col s6">
							<div class="input-field">
								<label for="barangay_permanent" class="active">Barangay
									<span class="required">*</span>
								</label>
								 <select tabindex="25" id="barangay_permanent"
									name="barangay_permanent" <?php echo $action==ACTION_VIEW ? 'disabled' : '' ?> required
									class="selectize"
									placeholder="Select Barangay">
								</select>
							</div>
						</div>						
					</div>
					<div class="row">
						<div class="col s6">
							<div class="input-field">
								<label for="permanent_house_num" class="active">House/ Block/ Lot No. <span class="required">*</span>
								</label> <input tabindex="26" type="text"
									name="permanent_house_num" required
									id="permanent_house_num" class="validate"
									value="<?php echo $permanent_address_values[0] ?>"
									placeholder="House Number or Lot/Blk/Phase, Streetname">
							</div>
						</div>
						<div class="col s6">
							<div class="input-field">
								<label for="permanent_street_name" class="active">Street <span class="required">*</span>
								</label> <input tabindex="27" type="text"
									name="permanent_street_name"
									id="permanent_street_name" class="validate"
									value="<?php echo $permanent_address_values[1];?>">
							</div>
						</div>			
					</div>
					<!-- ========================================================================================================= -->
					
					<div class="row">
						<div class="col s6">
							<div class="input-field">
								<label for="permanent_subdivision_name" class="active">Subdivision/Village <span class="required">*</span>
								</label> <input tabindex="28" type="text"
									name="permanent_subdivision_name"
									id="permanent_subdivision_name" class="validate"
									value="<?php echo $permanent_address_values[2]; ?>"
									>
							</div>
						</div>
						<div class="col s6">
							<div class="input-field">
								<input tabindex="29" id="permanent_zip_code" 
									name="permanent_zip_code" required
									value="<?php echo !EMPTY($permanent_address_info)? $permanent_address_info['postal_number']:"" ?>" maxlength="6" type="text" class="validate"> <label for="permanent_zip_code">Zip
									Code <span class="required">*</span></label>
							</div>
						</div>
					</div>
					<div class="row">
						<hr></hr>
					</div>
					<!-- ========================================================================================================= -->
					<div class="row">
						<div class="col s6">
							<div class="input-field">
								<label for="blood_type" class="active">Blood Type <span
									class="required">*</span></label> <select tabindex="30"
									id="blood_type" name="blood_type" required
									class="selectize validate" placeholder="Select Blood Type">
									<option value="">Select Blood Type</option>
									<?php if (!EMPTY($blood_types)): ?>
										<?php foreach ($blood_types as $type): ?>
												<option value="<?php echo $type['blood_type_id'] ?>"><?php echo $type['blood_type_name'] ?></option>
										<?php endforeach;?>
									<?php endif;?>
								</select>
							</div>
						</div>
						<div class="col s6">
							<div class="input-field">
								<input tabindex="31" id="email_value" name="email_value" required
									value="<?php echo !EMPTY($email) ? strtolower($email): "" ?>"
									type="email" class="validate"> <label for="email_value">E-mail
									Address <span class="required">*</span></label>
							</div>
						</div>
					</div>
					
					<div class="row">
						<div class="col s6">
							<div class="input-field">
								<input tabindex="32" id="telephone_permanent_value"
									name="telephone_permanent_value" 
									value="<?php echo !EMPTY($permanent_no)? format_identifications($permanent_no, TELEPHONE_FORMAT):"" ?>"
									type="text" class="validate"
									onkeypress="format_identifications('<?php echo TELEPHONE_FORMAT ?>',this.value,event,'telephone_permanent_value')"
									onkeydown="return (event.ctrlKey || event.altKey 
										                    || (47<event.keyCode 
									&& event.keyCode<58 && event.shiftKey==false) || (95
								<event.keyCode && event.keyCode<106) || (event.keyCode==8) ||
								(event.keyCode==9) || (event.keyCode>34 && event.keyCode<40) ||
								(event.keyCode==46) || (event.keyCode==173) || (event.keyCode==78) || (event.keyCode==65) || (event.charCode <
								44 && event.charCode > 39))"> <label
									for="telephone_permanent_value">Permanent Telephone Number</label>
							
							</div>
						</div>
						
						<div class="col s6">
							<div class="input-field"> 
								<input tabindex="33" id="cellphone_value" name="cellphone_value"
									value="<?php echo !EMPTY($mobile_no)? format_identifications($mobile_no, CELLPHONE_FORMAT):"" ?>"
									type="text" class="validate"
									onkeypress="format_identifications('<?php echo CELLPHONE_FORMAT ?>',this.value,event,'cellphone_value')"
									onkeydown="return (event.ctrlKey || event.altKey 
										                    || (47<event.keyCode 
									&& event.keyCode<58 && event.shiftKey==false) || (95
								<event.keyCode && event.keyCode<106) || (event.keyCode==8) ||
								(event.keyCode==9) || (event.keyCode>34 && event.keyCode<40) ||
								(event.keyCode==46) || (event.keyCode==173) || (event.keyCode==78) || (event.keyCode==65) || (event.charCode <
								44 && event.charCode > 39))"> 									
								<label class="<?php echo $action_id==ACTION_EDIT ? 'active' : '' ?>" for="cellphone_value">Cellphone Number<span class="required">*</span></label>							
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col s6">
							<div class="input-field">
								<input tabindex="34" id="gsis_value"
									name="gsis_value" 
									value="<?php echo !EMPTY($gsis_val)? (is_numeric($gsis_val)?format_identifications($gsis_val,$gsis_format) : $gsis_val) : '' ?>"
									type="text" class="validate"
									onkeypress="format_identifications('<?php echo $gsis_format ?>',this.value,event,'gsis_value')"
									onkeydown="return (event.ctrlKey || event.altKey 
										                    || (47<event.keyCode 
									&& event.keyCode<58 && event.shiftKey==false) || (95
								<event.keyCode && event.keyCode<106) || (event.keyCode==8) ||
								(event.keyCode==9) || (event.keyCode>34 && event.keyCode<40) ||
								(event.keyCode==46) || (event.keyCode==173) || (event.keyCode==78) || (event.keyCode==65) || (event.charCode <
								44 && event.charCode > 39))"> <label
									class="<?php echo $action_id==ACTION_EDIT ? 'active' : '' ?>"
									for="gsis_value">GSIS Number <span class="required">*</span></label>
							
							</div>
						</div>
						<div class="col s6">
							<div class="input-field">
								<input tabindex="35" id="pagibig_value" name="pagibig_value"
									value="<?php echo !EMPTY($pagibig_val)? (is_numeric($pagibig_val)?format_identifications($pagibig_val,$pagibig_format) : $pagibig_val) : '' ?>"
									type="text" class="validate"
									onkeypress="format_identifications('<?php echo $pagibig_format ?>',this.value,event,'pagibig_value')"
									onkeydown="return (event.ctrlKey || event.altKey 
										                    || (47<event.keyCode 
									&& event.keyCode<58 && event.shiftKey==false) || (95
								<event.keyCode && event.keyCode<106) || (event.keyCode==8) ||
								(event.keyCode==9) || (event.keyCode>34 && event.keyCode<40) ||
								(event.keyCode==46) || (event.keyCode==173) || (event.keyCode==78) || (event.keyCode==65) || (event.charCode <
								44 && event.charCode > 39))"> 
								<label class="<?php echo $action_id==ACTION_EDIT ? 'active' : '' ?>"
									for="pagibig_value">Pag-IBIG Number <span class="required">*</span></label>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col s6">
							<div class="input-field">
								<input tabindex="36" id="philhealth_value"
									name="philhealth_value" 
									value="<?php echo !EMPTY($philhealth_val)? (is_numeric($philhealth_val)?format_identifications($philhealth_val,$philhealth_format) : $philhealth_val) : '' ?>"
									type="text" class="validate"
									onkeypress="format_identifications('<?php echo $philhealth_format ?>',this.value,event,'philhealth_value')"
									onkeydown="return (event.ctrlKey || event.altKey 
										                    || (47<event.keyCode 
									&& event.keyCode<58 && event.shiftKey==false) || (95
								<event.keyCode && event.keyCode<106) || (event.keyCode==8) ||
								(event.keyCode==9) || (event.keyCode>34 && event.keyCode<40) ||
								(event.keyCode==46) || (event.keyCode==173) || (event.keyCode==78) || (event.keyCode==65) || (event.charCode <
								44 && event.charCode > 39))"> <label
									class="<?php echo $action_id==ACTION_EDIT ? 'active' : '' ?>"
									for="philhealth_value">Philhealth Number <span class="required">*</span></label>
							
							</div>
						</div>
						<div class="col s6">
							<div class="input-field">
								<input tabindex="37" id="sss_value" name="sss_value" 
									value="<?php echo !EMPTY($sss_val)? (is_numeric($sss_val)?format_identifications($sss_val,$sss_format) : $sss_val) : '' ?>"
									type="text" class="validate"
									onkeypress="format_identifications('<?php echo $sss_format ?>',this.value,event,'sss_value')"
									onkeydown="return (event.ctrlKey || event.altKey 
										                    || (47<event.keyCode 
									&& event.keyCode<58 && event.shiftKey==false) || (95
								<event.keyCode && event.keyCode<106) || (event.keyCode==8) ||
								(event.keyCode==9) || (event.keyCode>34 && event.keyCode<40) ||
								(event.keyCode==46) || (event.keyCode==173) || (event.keyCode==78) || (event.keyCode==65) || (event.charCode <
								44 && event.charCode > 39))"> <label
									class="<?php echo $action_id==ACTION_EDIT ? 'active' : '' ?>"
									for="sss_value">SSS Number <span class="required">*</span></label>
							
							</div>
						</div>
						
					</div>
					
					<div class="row">
						<div class="col s6">
							<div class="input-field">
								<input tabindex="38" id="tin_value" name="tin_value" required
									value="<?php echo !EMPTY($tin_val)? format_identifications($tin_val, $tin_format) : '' ?>"
									type="text" class="validate"
									onkeypress="format_identifications('<?php echo $tin_format ?>',this.value,event,'tin_value')"
									onkeydown="return ( event.shiftKey || event.ctrlKey || event.altKey 
										                    || (47<event.keyCode 
									&& event.keyCode<58 && event.shiftKey==false) || (95
								<event.keyCode && event.keyCode<106) || (event.keyCode==8) ||
								(event.keyCode==9) || (event.keyCode>34 && event.keyCode<40) ||
								(event.keyCode==46) || (event.keyCode==173) || (event.keyCode==78) || (event.keyCode==65) || (event.charCode <
								44 && event.charCode > 39))"> <label
									class="<?php echo $action_id==ACTION_EDIT ? 'active' : '' ?>"
									for="tin_value">TIN Number <span class="required">*</span></label>
							
							</div>
						</div>
						<div class="col s4 b-r-n">
							<div class="input-field">
								<input tabindex="39" id="agency_employee_id" <?php echo ($module == MODULE_PERSONNEL_PORTAL) ? 'readonly' : '' ?>
									data-parsley-length="[8, 8]" name="agency_employee_id" required
									value="<?php echo !EMPTY($personal_info['agency_employee_id']) ? $personal_info['agency_employee_id'] :  ''?>"
									type="text" class="validate"> <label for="agency_employee_id"
									class="active">Agency Employee Number <span class="required">*</span></label>
							</div>
						</div>
						<?php
							$style = ($action == ACTION_ADD) ? 'margin-top: -2px' : 'margin-top: 30px';							
						?>
						<?php if($action == ACTION_VIEW OR $module == MODULE_PERSONNEL_PORTAL) : ?>
							<div class="col s2 p-t-md p-l-lg">
							</div>
						<?php else: ?>
							<div class="col s2 p-t-md p-l-lg">
								<span class="font-md">Generate</span>
								<div class="input-field p-l-md" style="<?php echo $style?>"> 													
									<input name="generate_agency_number" id="generate_agency_number" class="filled-in" type="checkbox">
									<label for="generate_agency_number"></label>							  		
							  	</div>
							</div>
						<?php endif; ?>
					</div>
					<div class="row">
						<div class="col s6">
							<div class="input-field">
								<input disabled tabindex="30" id="biometric_pin"
									name="biometric_pin" required
									value="<?php echo !EMPTY($personal_info['biometric_pin'])  ? $personal_info['biometric_pin'] : '' ?>"
									type="text" class="validate"> <label for="biometric_pin"
									class="active">Biometric Pin</label>
							</div>
						</div>
						
					</div>
				</div>
			</div>
		</div>

		<!-- SHOW UNDEFINED FIELDS -->
		<div class="card-content p-t-n p-b-n">
			<span class="card-title activator  green-text font-md font-semibold">Show
				Undefined Fields<i class="flaticon-eye104 left"></i>
			</span>
		</div>
		<div class="card-reveal">
			<span class="card-title  green-text font-lg font-semibold">Personal
				Information Undefined Fields<i class="material-icons right">close</i>
			</span>
			<p>Here is the list of undefined information in this tab.</p>
			<table class="m-t-xl striped">
				<thead class="green white-text">
					<tr>
						<td class="font-semibold">Field Name</td>
						<td class="font-semibold">Sample Value</td>
					</tr>
				</thead>
				<tbody>
					<?php if(EMPTY($personal_info['last_name'])):?>
					<tr>
						<td>Last Name</td>
						<td>Dela Cruz</td>
					</tr>
					<?php endif;?>
					<?php if(EMPTY($personal_info['first_name'])):?>
					<tr>
						<td>First Name</td>
						<td>Juan</td>
					</tr>
					<?php endif;?>
					<?php if(EMPTY($personal_info['middle_name'])):?>
					<tr>
						<td>Middle Name</td>
						<td>Reyes</td>
					</tr>
					<?php endif;?>
					<?php if(EMPTY($personal_info['ext_name'])):?>
					<tr>
						<td>Extension Name</td>
						<td>Jr</td>
					</tr>
					<?php endif;?>
					<?php if(EMPTY($personal_info['birth_date'])):?>
					<tr>
						<td>Date of Birth</td>
						<td>1990/12/25</td>
					</tr>
					<?php endif;?>
					<?php if(EMPTY($personal_info['birth_place'])):?>
					<tr>
						<td>Place of Birth</td>
						<td>1990/12/25</td>
					</tr>
					<?php endif;?>
					<?php if(EMPTY($personal_info['gender_code'])):?>
					<tr>
						<td>Gender</td>
						<td>Male</td>
					</tr>
					<?php endif;?>
					<?php if(EMPTY($personal_info['civil_status_id'])):?>
					<tr>
						<td>Civil Status</td>
						<td>Single</td>
					</tr>
					<?php endif;?>
					<?php if(EMPTY($personal_info['citizenship_id'])):?>
					<tr>
						<td>Citizenship</td>
						<td>Filipino</td>
					</tr>
					<?php endif;?>
					<?php if(EMPTY($personal_info['height'])):?>
					<tr>
						<td>Height</td>
						<td>6.25</td>
					</tr>
					<?php endif;?>
					<?php if(EMPTY($personal_info['weight'])):?>
					<tr>
						<td>Weight</td>
						<td>66.32</td>
					</tr>
					<?php endif;?>
					<?php if(EMPTY($personal_info['blood_type_id'])):?>
					<tr>
						<td>Blood Type</td>
						<td>AB</td>
					</tr>
					<?php endif;?>
						<?php if(EMPTY($gsis_val)):?>
					<tr>
						<td>GSIS Number</td>
						<td>12345678</td>
					</tr>
					<?php endif;?>
						<?php if(EMPTY($pagibig_val)):?>
					<tr>
						<td>Pag-ibig Number</td>
						<td>12345678</td>
					</tr>
					<?php endif;?>
						<?php if(EMPTY($philhealth_val)):?>
					<tr>
						<td>Philhealth Number</td>
						<td>12345678</td>
					</tr>
					<?php endif;?>
						<?php if(EMPTY($sss_val)):?>
					<tr>
						<td>SSS Number</td>
						<td>12345678</td>
					</tr>
					<?php endif;?>
						<?php if(EMPTY($residential_address_info['address_value'])):?>
					<tr>
						<td>Residential Address</td>
						<td>096 Stuazon St.</td>
					</tr>
					<?php endif;?>
					<?php if(EMPTY($residential_address_info['codes'])):?>
					<tr>
						<td>Barangay/Municipality/Province/Region (Residential)</td>
						<td>Poblaction, Pateros, Manila, NCR</td>
					</tr>
					<?php endif;?>
					<?php if(EMPTY($residential_address_info['postal_number'])):?>
					<tr>
						<td>Zip Code (Residential)</td>
						<td>1620</td>
					</tr>
					<?php endif;?>
						<?php if(EMPTY($residential_no)):?>
					<tr>
						<td>Telephone Number (Residential)</td>
						<td>1234567</td>
					</tr>
					<?php endif;?>
						<?php if(EMPTY($permanent_address_info['address_value'])):?>
					<tr>
						<td>Permanent Address</td>
						<td>096 Stuazon St.</td>
					</tr>
					<?php endif;?>
					<?php if(EMPTY($permanent_address_info['codes'])):?>
					<tr>
						<td>Barangay/Municipality/Province/Region (Permanent)</td>
						<td>Poblaction, Pateros, Manila, NCR</td>
					</tr>
					<?php endif;?>
					<?php if(EMPTY($permanent_address_info['postal_number'])):?>
					<tr>
						<td>Zip Code (Permanent)</td>
						<td>1620</td>
					</tr>
					<?php endif;?>
						<?php if(EMPTY($permanent_no)):?>
					<tr>
						<td>Telephone Number (Permanent)</td>
						<td>1234567</td>
					</tr>
					<?php endif;?>
						<?php if(EMPTY($email)):?>
					<tr>
						<td>Email Addres</td>
						<td>sample@yahoo.com</td>
					</tr>
					<?php endif;?>
						<?php if(EMPTY($mobile_no)):?>
					<tr>
						<td>Mobile Number</td>
						<td>09361600304</td>
					</tr>
					<?php endif;?>
					<?php if(EMPTY($personal_info['agency_employee_id'])):?>
					<tr>
						<td>Employee Number</td>
						<td>12345678</td>
					</tr>
					<?php endif;?>
						<?php if(EMPTY($tin_val)):?>
					<tr>
						<td>TIN Number</td>
						<td>12345678</td>
					</tr>
					<?php endif;?>
					<?php if(EMPTY($personal_info['biometric_pin'])):?>
					<tr>
						<td>Biometric Pin</td>
						<td>12345678</td>
					</tr>
					<?php endif;?>
				</tbody>
			</table>
		</div>
	</div>
	<div class="right-align p-r-sm">
		<?php if ($action != ACTION_VIEW): ?>
				<button class="btn btn-success" id="save_personal_info"
			type="button" name="action" value="Save"  tabindex="40" >Save</button>
		<?php endif; ?>
	</div>
</form>
<script type="text/javascript">
$("#citizenships").change(function() {
    var citizenships = $(this).val();
    if (citizenships == <?php echo CITIZENSHIP_FILIPINO; ?>) 
    {
    	$('#required_citi').empty('');
       	$('#citizenship_basis').prop('required', false);
		$('#citizenship_basis').attr('disabled', true);
		$('#citizenship_basis')[0].selectize.destroy();
		$('#citizenship_basis').attr("disabled", true);
		$('#citizenship_basis').selectize();
		$("#citizenship_basis")[0].selectize.clear();
    } 
    else 
    {
    	$('#required_citi').html('*');
       	$('#citizenship_basis').prop('required', true);
		$('#citizenship_basis')[0].selectize.destroy();
		$('#citizenship_basis').attr("disabled", false);
		$('#citizenship_basis').selectize();
		$("#citizenship_basis")[0].selectize.clear();
    }
});


	<?php if($personal_info['height'] == 'Y'): ?>
		$(document).ready(function() {
			$('#jo_flag').val(1);
		});
	<?php endif; ?>
	<?php if($lacking_fields): ?>
		$(document).ready(function() {
			$(".activator").trigger("click");
		});
	<?php endif; ?>

	$(function(){
			
			var result = '<option value="">Select Region</option>';
			var regions_value = <?php echo json_encode($regions_values) ?>;
			
			var region = <?php echo json_encode($permanent_address_info['region_code']) ?>;
			for(var i=0 ; i < regions_value.length; i++)
			{
				if(regions_value[i]['region_code'] == region)
				{
					result += '<option value="' + regions_value[i]['region_code'] + '" selected>' + regions_value[i]['region_name'] + '</option>';
				}
				else
				{
					result += '<option value="' + regions_value[i]['region_code'] + '">' + regions_value[i]['region_name'] + '</option>';
				}				
			}
			$('#region_permanent').html(result);


			var result = '<option value="">Select Province</option>';
			var provinces_value = <?php echo json_encode($provinces_value) ?>;
			var province = <?php echo json_encode($permanent_address_info['province_code']) ?>;
			for(var i=0 ; i < provinces_value.length; i++)
			{
				if(provinces_value[i]['province_code'] == province)
				{
					if(region == '13')
					{
						result += '<option value="' + provinces_value[i]['province_code'] + '" selected> METRO MANILA - ' + provinces_value[i]['province_name'] + '</option>';
					}
					else
					{
						result += '<option value="' + provinces_value[i]['province_code'] + '" selected>' + provinces_value[i]['province_name'] + '</option>';
					}
				}
				else
				{
					if(region == '13')
					{
						result += '<option value="' + provinces_value[i]['province_code'] + '">METRO MANILA - ' + provinces_value[i]['province_name'] + '</option>';
					}
					else
					{
						result += '<option value="' + provinces_value[i]['province_code'] + '">' + provinces_value[i]['province_name'] + '</option>';
					}
				}				
			}
			$('#province_permanent').html(result);

			var result = '<option value="">Select Municipality</option>';
			
			var municipalities_value = <?php echo json_encode($municipalities_value) ?>;
			var municity = <?php echo json_encode($permanent_address_info['municity_code']) ?>;
			for(var i=0 ; i < municipalities_value.length; i++)
			{
				if(municipalities_value[i]['province_code'] == province)
				{
					if(province == '13806')
					{
						if(municipalities_value[i]['municity_code'] !== '1380600')
						{
							if(municipalities_value[i]['municity_code'] == municity)
							{
								result += '<option value="' + municipalities_value[i]['municity_code'] + '" selected> City Of Manila -' + municipalities_value[i]['municity_name'] + '</option>';
								
							}
							else
							{
								result += '<option value="' + municipalities_value[i]['municity_code'] + '"> City Of Manila -' + municipalities_value[i]['municity_name'] + '</option>';
							}	
						}
					}
					else
					{
						if(municipalities_value[i]['municity_code'] == municity)
						{
							result += '<option value="' + municipalities_value[i]['municity_code'] + '" selected>' + municipalities_value[i]['municity_name'] + '</option>';
							
						}
						else
						{
							result += '<option value="' + municipalities_value[i]['municity_code'] + '">' + municipalities_value[i]['municity_name'] + '</option>';
						}	
					}			
				}		
			}
			$('#municipalities_permanent').html(result);
			
			var result = '<option value="" selected>Select Barangay</option>';
			
			var barangays_value = <?php echo json_encode($barangays_value) ?>;
			var barangay = <?php echo json_encode($permanent_address_info['barangay_code']) ?>;
			for(var i=0 ; i < barangays_value.length; i++)
			{
				if(barangays_value[i]['municity_code'] == municity)
				{
					if(barangays_value[i]['barangay_code'] == barangay)
					{
						result += '<option value="' + barangays_value[i]['barangay_code'] + '" selected>' + barangays_value[i]['barangay_name'] + '</option>';
					}
					else
					{
						result += '<option value="' + barangays_value[i]['barangay_code'] + '">' + barangays_value[i]['barangay_name'] + '</option>';
					}	
				}			
			}
			$('#barangay_permanent').html(result);


			var result = '<option value="">Select Region</option>';
			var regions_value = <?php echo json_encode($regions_values) ?>;
			
			var region = <?php echo json_encode($residential_address_info['region_code']) ?>;
			for(var i=0 ; i < regions_value.length; i++)
			{
				if(regions_value[i]['region_code'] == region)
				{
					result += '<option value="' + regions_value[i]['region_code'] + '" selected>' + regions_value[i]['region_name'] + '</option>';
				}
				else
				{
					result += '<option value="' + regions_value[i]['region_code'] + '">' + regions_value[i]['region_name'] + '</option>';
				}				
			}
			$('#region_residential').html(result);

			var result = '<option value="">Select Province</option>';
			var provinces_value = <?php echo json_encode($provinces_value) ?>;
			var province = <?php echo json_encode($residential_address_info['province_code']) ?>;
			for(var i=0 ; i < provinces_value.length; i++)
			{
				if(provinces_value[i]['province_code'] == province)
				{
					if(region == '13')
					{
						result += '<option value="' + provinces_value[i]['province_code'] + '" selected> METRO MANILA - ' + provinces_value[i]['province_name'] + '</option>';
					}
					else
					{
						result += '<option value="' + provinces_value[i]['province_code'] + '" selected>' + provinces_value[i]['province_name'] + '</option>';
					}
				}
				else
				{
					if(region == '13')
					{
						result += '<option value="' + provinces_value[i]['province_code'] + '">METRO MANILA - ' + provinces_value[i]['province_name'] + '</option>';
					}
					else
					{
						result += '<option value="' + provinces_value[i]['province_code'] + '">' + provinces_value[i]['province_name'] + '</option>';
					}
				}				
			}
			$('#province_residential').html(result);

			var result = '<option value="">Select Municipality</option>';
			
			var municipalities_value = <?php echo json_encode($municipalities_value) ?>;
			var municity = <?php echo json_encode($residential_address_info['municity_code']) ?>;
			for(var i=0 ; i < municipalities_value.length; i++)
			{
				if(municipalities_value[i]['province_code'] == province)
				{
					if(province == '13806')
					{
						if(municipalities_value[i]['municity_code'] !== '1380600')
						{
							if(municipalities_value[i]['municity_code'] == municity)
							{
								result += '<option value="' + municipalities_value[i]['municity_code'] + '" selected> City Of Manila -' + municipalities_value[i]['municity_name'] + '</option>';
								
							}
							else
							{
								result += '<option value="' + municipalities_value[i]['municity_code'] + '"> City Of Manila -' + municipalities_value[i]['municity_name'] + '</option>';
							}	
						}
					}
					else
					{
						if(municipalities_value[i]['municity_code'] == municity)
						{
							result += '<option value="' + municipalities_value[i]['municity_code'] + '" selected>' + municipalities_value[i]['municity_name'] + '</option>';
							
						}
						else
						{
							result += '<option value="' + municipalities_value[i]['municity_code'] + '">' + municipalities_value[i]['municity_name'] + '</option>';
						}	
					}			
				}			
			}
			$('#municipalities_residential').html(result);
			
			var result = '<option value="" selected>Select Barangay</option>';
			
			var barangays_value = <?php echo json_encode($barangays_value) ?>;
			var barangay = <?php echo json_encode($residential_address_info['barangay_code']) ?>;
			for(var i=0 ; i < barangays_value.length; i++)
			{
				if(barangays_value[i]['municity_code'] == municity)
				{
					if(barangays_value[i]['barangay_code'] == barangay)
					{
						result += '<option value="' + barangays_value[i]['barangay_code'] + '" selected>' + barangays_value[i]['barangay_name'] + '</option>';
					}
					else
					{
						result += '<option value="' + barangays_value[i]['barangay_code'] + '">' + barangays_value[i]['barangay_name'] + '</option>';
					}	
				}			
			}
			$('#barangay_residential').html(result);

		
		// REPLACATION OF AGENCY EMPLOYEE ID VALUE TO BIOMETRIC PIN
		var $src = $('#agency_employee_id'),
	        $dst = $('#biometric_pin');
	    $src.on('input', function () {
	        $dst.val($src.val());
	    });

		<?php if($action != ACTION_ADD): ?>
			$("label:not(.gender)").addClass('active');
		
		<?php endif; ?>	

		jQuery(document).off('click', '#save_personal_info');
		jQuery(document).on('click', '#save_personal_info', function(e){	
			$("#form_personal_info").trigger('submit');
		});
		$('#form_personal_info').parsley();

		jQuery(document).off('submit', '#form_personal_info');
		jQuery(document).on('submit', '#form_personal_info', function(e){
			e.preventDefault();
			if ( $(this).parsley().isValid() ) {

				var data = $('#form_personal_info').serialize();
				data += '&biometric_pin=' + $('#agency_employee_id').val();
				var process_url = "";
				<?php if($module == MODULE_PERSONNEL_PORTAL):?>
				process_url = $base_url + 'main/pds_record_changes_requests/process_peronal_info';
				<?php else: ?>
				process_url = $base_url + 'main/pds_personal_info/process';
				<?php endif; ?>
				button_loader('save_personal_info', 1);
				var option = {
					url  : process_url,
					data : data,
					success : function(result){
						if(result.status === true)
						{
							<?php if($module != MODULE_PERSONNEL_PORTAL):?>
							if(result.proceed_flag == 'Y')
							{
								notification_msg("<?php echo SUCCESS ?>", result.message);
								//button_loader("save_personal_info",0);
								var reload_url = result.reload_url;
								if(reload_url != "")
								{
									setTimeout(function(){ window.location.href = "<?php echo base_url() . PROJECT_MAIN ?>/pds/display_pds_info/"+ reload_url; }, 2000);
								}
							}
							else
							{
								confirm_duplicate();
							}
							<?php else: ?>
								notification_msg("<?php echo SUCCESS ?>", result.message);
								//button_loader("save_personal_info",0);
								var reload_url = result.reload_url;
								if(reload_url != "")
								{
									setTimeout(function(){ window.location.href = "<?php echo base_url() . PROJECT_MAIN ?>/pds/display_pds_info/"+ reload_url; }, 2000);
								}
							<?php endif;?>
						}
						else
						{
							notification_msg("<?php echo ERROR ?>", result.message);
							button_loader('save_personal_info', 0);
						}	

					},

					complete : function(jqXHR){
						//button_loader('save_personal_info', 0);
					}
				};
				General.ajax(option);  		    
			}

		});

	});
	function confirm_duplicate(){
	
	$('#confirm_modal').confirmModal({
		topOffset : 0,
		onOkBut : function() {

			$('#proceed_flag').val('Y');
			$("#form_personal_info").trigger('submit');
		},
		onCancelBut : function() {
			button_loader('save_personal_info', 0);
		},
		onLoad : function() {
			$('.confirmModal_content h4').html('<span class="orange-text">Warning!</span>');	
			$('.confirmModal_content p').html('Employee information already exist. Continue anyway?');
		},
		onClose : function() {}
	});
}
	// GET GENERATED ID
	$("#generate_agency_number").on("change", function(){
		if($(this).is(":checked")) {
			$.post($base_url+"<?php echo PROJECT_MAIN.'/pds_personal_info/get_generated_id'?>", function(result) {		
				$("#agency_employee_id").val(result.generated_id);
				$("#biometric_pin").val(result.generated_id);
				$("#agency_employee_id").attr('readonly', true);
			}, 'json');
		}
		else
		{
			$("#agency_employee_id").val("<?php echo !EMPTY($personal_info['agency_employee_id'])  ? $personal_info['agency_employee_id'] : '' ?>");
			$("#biometric_pin").val("<?php echo !EMPTY($personal_info['biometric_pin'])  ? $personal_info['biometric_pin'] : '' ?>");
			$("#agency_employee_id").attr('readonly', false);
		}
	});

	$("#region_residential").on("change", function(){
		$('#province_residential')[0].selectize.destroy();
			
		var result = '<option value="">Select Province</option>';
		var provinces_value = <?php echo json_encode($provinces_value) ?>;
		var region_code = $(this).val();
		for(var i=0 ; i < provinces_value.length; i++)
		{
			if(provinces_value[i]['region_code'] == region_code)
			{
				if(region_code == '13')
				{
					result += '<option value="' + provinces_value[i]['province_code'] + '">METRO MANILA - ' + provinces_value[i]['province_name'] + '</option>';
				}
				else
				{
					result += '<option value="' + provinces_value[i]['province_code'] + '">' + provinces_value[i]['province_name'] + '</option>';
				}

			}
		}

			$('#province_residential').html(result);
			$('#province_residential').selectize();


			$('#municipalities_residential')[0].selectize.destroy();
			var result = '<option value="">Select Municipality</option>';
			$('#municipalities_residential').html(result);
			$('#municipalities_residential').selectize();

			$('#barangay_residential')[0].selectize.destroy();
			var result = '<option value="">Select Barangay</option>';
			$('#barangay_residential').html(result);
			$('#barangay_residential').selectize();
	});
	
	$("#province_residential").on("change", function(){
		$('#municipalities_residential')[0].selectize.destroy();
			
		var result = '<option value="">Select Municipality</option>';
		var municipalities_value = <?php echo json_encode($municipalities_value) ?>;
		var province_code = $(this).val();
		for(var i=0 ; i < municipalities_value.length; i++)
		{
			if(municipalities_value[i]['province_code'] == province_code)
			{
				if(province_code == '13806')
				{
					if(municipalities_value[i]['municity_code'] !== '1380600')
					{
						result += '<option value="' + municipalities_value[i]['municity_code'] + '"> City Of Manila - ' + municipalities_value[i]['municity_name'] + '</option>';
					}
				}
				else
				{
					result += '<option value="' + municipalities_value[i]['municity_code'] + '">' + municipalities_value[i]['municity_name'] + '</option>';
				}
			}
		}
		$('#municipalities_residential').html(result);
		$('#municipalities_residential').selectize();

		$('#barangay_residential')[0].selectize.destroy();
		var result = '<option value="">Select Barangay</option>';
		$('#barangay_residential').html(result);
		$('#barangay_residential').selectize();
	});

	$("#municipalities_residential").on("change", function(){
		$('#barangay_residential')[0].selectize.destroy();
			
		var result = '<option value="">Select Barangay</option>';
		var barangays_value = <?php echo json_encode($barangays_value) ?>;
		var municity_code = $(this).val();
		for(var i=0 ; i < barangays_value.length; i++)
		{
			if(barangays_value[i]['municity_code'] == municity_code)
			{
				result += '<option value="' + barangays_value[i]['barangay_code'] + '">' + barangays_value[i]['barangay_name'] + '</option>';
			}
		}
		$('#barangay_residential').html(result);
		$('#barangay_residential').selectize();
	});

	$("#region_permanent").on("change", function(){
		$('#province_permanent')[0].selectize.destroy();
			
		var result = '<option value="">Select Province</option>';
		var provinces_value = <?php echo json_encode($provinces_value) ?>;
		var region_code = $(this).val();
		for(var i=0 ; i < provinces_value.length; i++)
		{
			if(provinces_value[i]['region_code'] == region_code)
			{
				if(region_code == '13')
				{
					result += '<option value="' + provinces_value[i]['province_code'] + '">METRO MANILA - ' + provinces_value[i]['province_name'] + '</option>';
				}
				else
				{
					result += '<option value="' + provinces_value[i]['province_code'] + '">' + provinces_value[i]['province_name'] + '</option>';
				}

			}
		}

			$('#province_permanent').html(result);
			$('#province_permanent').selectize();


			$('#municipalities_permanent')[0].selectize.destroy();
			var result = '<option value="">Select Municipality</option>';
			$('#municipalities_permanent').html(result);
			$('#municipalities_permanent').selectize();

			$('#barangay_permanent')[0].selectize.destroy();
			var result = '<option value="">Select Barangay</option>';
			$('#barangay_permanent').html(result);
			$('#barangay_permanent').selectize();
	});
	

	$("#province_permanent").on("change", function(){
		$('#municipalities_permanent')[0].selectize.destroy();
			
		var result = '<option value="">Select Municipality</option>';
		var municipalities_value = <?php echo json_encode($municipalities_value) ?>;
		var province_code = $(this).val();
		for(var i=0 ; i < municipalities_value.length; i++)
		{
			if(municipalities_value[i]['province_code'] == province_code)
			{
				if(province_code == '13806')
				{
					if(municipalities_value[i]['municity_code'] !== '1380600')
					{
						result += '<option value="' + municipalities_value[i]['municity_code'] + '"> City Of Manila - ' + municipalities_value[i]['municity_name'] + '</option>';
					}
				}
				else
				{
					result += '<option value="' + municipalities_value[i]['municity_code'] + '">' + municipalities_value[i]['municity_name'] + '</option>';
				}
			}
		}
		$('#municipalities_permanent').html(result);
		$('#municipalities_permanent').selectize();

		$('#barangay_permanent')[0].selectize.destroy();
		var result = '<option value="">Select Barangay</option>';
		$('#barangay_permanent').html(result);
		$('#barangay_permanent').selectize();
	});

	$("#municipalities_permanent").on("change", function(){
		$('#barangay_permanent')[0].selectize.destroy();
			
		var result = '<option value="">Select Barangay</option>';
		var barangays_value = <?php echo json_encode($barangays_value) ?>;
		var municity_code = $(this).val();
		
		for(var i=0 ; i < barangays_value.length; i++)
		{
			if(barangays_value[i]['municity_code'] == municity_code)
			{
				result += '<option value="' + barangays_value[i]['barangay_code'] + '">' + barangays_value[i]['barangay_name'] + '</option>';
			}
		}
		$('#barangay_permanent').html(result);
		$('#barangay_permanent').selectize();
	});

	// TO ENTIRELY REMOVE THE ASTERISKS(*) AND DISABLE ALL FIELDS IN THIS VIEW
	<?php if($action == ACTION_VIEW) : ?>
		$('span.required').addClass('none');
		$('.validate').attr('disabled','');
	<?php endif; ?>

	<?php if($module == MODULE_PERSONNEL_PORTAL) : ?>
		$('#last_name').prop('readonly', true);
		$('#first_name').prop('readonly', true);
		$('#middle_name').prop('readonly', true);
		$('#cs_readonly').prop('readonly', true);

		
		
	<?php endif; ?>

	
	<?php if($module == MODULE_PERSONNEL_PORTAL) : ?>
		$('#cs_ro_div').css("cssText", "display: table !important");
		$('#civil_stat_div').css("cssText", "display: none !important"); 
	<?php else : ?>
		$('#cs_ro_div').css("cssText", "display: none !important"); 
		$('#civil_stat_div').css("cssText", "display: table !important"); 
	<?php endif; ?>



	$('input:not([type=email]):not([type=password])').keyup(function () {
       $(this).val($(this).val().toUpperCase());
   	});
</script>
