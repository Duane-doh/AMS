<form id="form_experience_non">
	<input type="hidden" name="id" value="<?php echo $id ?>"/>
	<input type="hidden" name="salt" value="<?php echo $salt ?>"/>
	<input type="hidden" name="token" value="<?php echo $token ?>"/>
	<input type="hidden" name="action" value="<?php echo $action ?>"/>
	<input type="hidden" name="module" value="<?php echo $module ?>"/>
	<?php if( !EMPTY($experience['employ_type_flag'])) : ?>
	  	<input type="hidden" name="employ_type_flag" value="<?php echo $experience['employ_type_flag'] ?>"/>
	<?php endif; ?>	
	<div class="scroll-pane" style="height: 500px">
		<div class="form-float-label ">
			<div class="row">
				<div class='col s12 switch p-md'>
					<label>
						Private
						<input id="govt_service_flag" name='govt_service_flag' type='checkbox' value='0' <?php echo ($experience['employ_type_flag'] == NON_DOH_GOV) ? "checked":"" ?> <?php echo $action==ACTION_VIEW ? 'disabled' : '' ?>>
						<span class='lever'></span>
						Government Service
					</label>
				</div>
			</div>
			<div class="row m-n">
				<div class="col s6">
					<div class="input-field">
						<input type="text" class="validate datepicker" required placeholder="YYYY/MM/DD" name="employ_start_date_non_doh" id="employ_start_date_non_doh" 
							   onkeypress="format_identifications('<?php echo DATE_FORMAT ?>',this.value,event,'employ_start_date_non_doh')"
							   value="<?php echo !EMPTY($experience['employ_start_date'])? format_date($experience['employ_start_date']):"" ?>" <?php echo $action==ACTION_VIEW ? 'disabled' : '' ?>/>
						<label for="employ_start_date_non_doh" class="active">Start Date<span class="required">*</span></label>
					</div>
				</div>
				<div class="col s6">
					<div class="input-field">
						<input type="text" class="validate datepicker" required placeholder="YYYY/MM/DD" name="employ_end_date_non_doh" id="employ_end_date_non_doh" 
							   onkeypress="format_identifications('<?php echo DATE_FORMAT ?>',this.value,event,'employ_end_date_non_doh')"
							   value="<?php echo !EMPTY($experience['employ_end_date'])? format_date($experience['employ_end_date']):"" ?>" <?php echo $action==ACTION_VIEW ? 'disabled' : '' ?> />
						<label for="employ_end_date_non_doh" class="active">End Date<span class="required">*</span><label>
					</div>
				</div>
			</div>
			<div class="row m-n">
				<div class="col s12">
					<div class="input-field" id="div_employ_position">
						<label for="employ_position">Position<span class="required">*</span></label>
						<input type="text" class="validate" required name="employ_position" id="employ_position" value="<?php echo !EMPTY($experience['employ_position_name'])? $experience['employ_position_name']:"" ?>" <?php echo $action==ACTION_VIEW ? 'disabled' : '' ?>/>
					</div>
				</div>
			</div>
			<div class="row m-n">
				<div class="col s12">
					<div class="input-field">
						<input type="text" class="validate" required name="employ_company_name" id="employ_company_name" value="<?php echo !EMPTY($experience['employ_office_name'])? $experience['employ_office_name']:"" ?>" <?php echo $action==ACTION_VIEW ? 'disabled' : '' ?>/>
						<label for="employ_company_name">Department/Agency/Office/Company<span class="required">*</span></label>
					</div>
				</div>
			</div>
			<div class="row m-n b-b b-light-gray">
				<div class="col s6">
					<div class="input-field">
						<input type="text" class="validate number" required name="employ_monthly_salary_non_doh" id="employ_monthly_salary_non_doh" value="<?php echo !EMPTY($experience['employ_monthly_salary'])? $experience['employ_monthly_salary']:"" ?>" <?php echo $action==ACTION_VIEW ? 'disabled' : '' ?>/>
						<label for="employ_monthly_salary_non_doh">Monthly Salary<span class="required">*</span></label>
					</div>
				</div>
				<div class="col s6">
					<div class="input-field">
						<label for="employment_status_non_doh" class="active">Employment Status<span class="required">*</span></label>
						<select id="employment_status_non_doh" required name="employment_status_non_doh" <?php echo $action ==ACTION_VIEW ? 'disabled' : '' ?> class="selectize" placeholder="Select Employment Status">
						</select>
					</div>
				</div>
			</div>
			
			<div class="row m-n gov_service">
				<div class="col s6">
					<div class="input-field">
						<input type="text" class="validate" onkeypress="return isNumberKey(event)" name="employ_salary_grade_non_doh" id="employ_salary_grade_non_doh" value="<?php echo is_numeric($experience['employ_salary_grade']) && $experience['employ_salary_grade'] >= 0 ? $experience['employ_salary_grade'] : '' ?>" <?php echo $action==ACTION_VIEW ? 'disabled' : '' ?>/>
						<label for="employ_salary_grade_non_doh">Salary Grade<span class="required">*</span></label>
					</div>
				</div>
				<div class="col s6">
					<div class="input-field">
						<input type="text" class="validate" onkeypress="return isNumberKey(event)" name="employ_salary_step_non_doh" id="employ_salary_step_non_doh" value="<?php echo is_numeric($experience['employ_salary_step']) && $experience['employ_salary_step'] >= 0 ? $experience['employ_salary_step'] : '' ?>"<?php echo $action==ACTION_VIEW ? 'disabled' : '' ?>/>
						<label for="employ_salary_step_non_doh">Step Increment<span class="required">*</span></label>
					</div>
				</div>
			</div>
			<div class="row m-n gov_service">
				<div class="col s6">
					<div class="input-field">
						<label for="branch_name" class="active">Branch<span class="required">*</span></label>
						<select id="branch_name"  name="branch_name" <?php echo $action==ACTION_VIEW ? 'disabled' : '' ?> class="selectize" placeholder="Select Branch">
							<option value="">Select Branch</option>
							<?php foreach($branch as $row):?>
								<option value="<?php echo $row['branch_id']; ?>"><?php echo $row['branch_name']; ?></option>
							<?php endforeach;?>
						</select>
					</div>
				</div>
				<div class="col s6">
					<div class="input-field">
						<input type="text" class="validate" onkeypress="return isNumberKey(event)" name="leaves" id="leaves" value="<?php echo !EMPTY($experience['service_lwop'])? $experience['service_lwop']:"" ?>" <?php echo $action==ACTION_VIEW ? 'disabled' : '' ?>/>
						<label for="leaves">Leave/s without Pay</label>
					</div>
				</div>	
			</div>
			<div class="row m-n gov_service">
				<div class="col s6">
					<div class="input-field">
						<!--<label for="separation_mode_non_doh" class="active">Separation Cause<span class="required">*</span></label>-->
						<!-- marvin : start : remove required attr -->
						<label for="separation_mode_non_doh" class="active">Separation Cause</label>
						<!-- marvin : end : remove required attr -->
						<select id="separation_mode_non_doh"  name="separation_mode_non_doh" class="selectize" placeholder="Select Separation Cause" <?php echo $action==ACTION_VIEW ? 'disabled' : '' ?>>
							<option value="">Select Separation Cause</option>
							<?php foreach($separation_mode as $row):?>
								<option value="<?php echo $row['separation_mode_id']; ?>"><?php echo $row['separation_mode_name']; ?></option>
							<?php endforeach;?>
						</select>
					</div>
				</div>
				<div class='col s6 switch p-md b-b-n'>
					Relevant<br><br>
					<label>
						No
						<input name='relevance_flag' type='checkbox' value='Y' <?php echo ($experience['relevance_flag'] == "Y") ? "checked" : "" ?> <?php echo $action == ACTION_ADD ? 'checked' :'' ?> <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>> 
						<span class='lever'></span>Yes
					</label>
				</div>
			</div>	
			<div class="row gov_service">						
				<div class="col s12">
					<div class="input-field">
						<input type="text" name="remarks" id="remarks" value="<?php echo !EMPTY($experience['remarks'])? $experience['remarks'] : '' ?>" <?php echo $action==ACTION_VIEW ? 'disabled' : '' ?>>
						<label for="remarks" class="active">Remarks</label>
					</div>
				</div>
			</div>
			<!-- davcorrea: added note -->
			<!-- ========START======== -->
			<div class="row">
				<div class="col s12">
				
				<p><b class="red-text">Note:</b> You are not allowed to encode DOH and other government work experience. Please coordinate with the Recruitment Officer in the Personnel Administration Division (PAD) to update or correct your DOH employment history. To update or add your other government job history, please provide PAD an original copy of your service record from your previous employer.</p>
				</strong>
				</div>
			</div>
			<!-- =====END======= -->
			
			</div><br><br><br><br><br><br><br><br><br><br><br><br><br>
		</div>
	</div>	

	<?php if($action != ACTION_VIEW):?>
		<div class="md-footer default">
			<a class="waves-effect waves-teal btn-flat cancel_modal">Cancel</a>
			<button class="btn btn-success" type="button" id="save_experience" value="<?php echo BTN_SAVE ?>"><?php echo BTN_SAVE ?></button>
		</div>
	<?php endif;?>
</form>
<script>
// ALLOWS NUMERIC INPUT ONLY
function isNumberKey(evt)
{
    var charCode = (evt.which) ? evt.which : event.keyCode
    if (charCode != 43 && charCode != 48 && charCode > 31 && (charCode < 48 || charCode > 57))
        return false;
    return true;
}

$(function (){
	<?php if($action != ACTION_ADD){ ?>
		$('.input-field label').addClass('active');
  	<?php } ?>

  	//IF ACTION IS EDIT BUTTON ABOVE WILL DISABLED
  	<?php if(!EMPTY($experience)):?>
  		$('#govt_service_flag').attr('disabled', true);
  	<?php endif; ?>
  	//END OF ACTION EDIT

  	$( document ).ready(function() {
	// davcorrea : 09/27/2023 START disable button if non HR
		<?php if($module == MODULE_PERSONNEL_PORTAL) : ?>
			$('#govt_service_flag').attr('disabled', true);
	  	<?php endif; ?>	
	// ===========END ==========
  		<?php if ($experience['employ_type_flag']!= NON_DOH_GOV):?>
				var result = '<option value="">Select Employment Status</option>';
				var employment_status = <?php echo json_encode($employment_status) ?>;

				for(var i=0 ; i < employment_status.length; i++)
				{
						if(employment_status[i]['pr_flag'] == 'Y')
						{
							result += '<option value="' + employment_status[i]['employment_status_id'] + '">' + employment_status[i]['employment_status_name'] + '</option>';
						}
				}
				$('#employment_status_non_doh').html(result);				
	    <?php endif;?>

	    <?php if ($experience['employ_type_flag']== NON_DOH_GOV):?>
				var result = '<option value="">Select Employment Status</option>';
				var employment_status = <?php echo json_encode($employment_status) ?>;

				for(var i=0 ; i < employment_status.length; i++)
				{
						if(employment_status[i]['og_flag'] == 'Y')
						{
							result += '<option value="' + employment_status[i]['employment_status_id'] + '">' + employment_status[i]['employment_status_name'] + '</option>';
						}
				}
				$('#employment_status_non_doh').html(result);				
	    <?php endif;?>

  		<?php if($experience['employ_type_flag'] != NON_DOH_GOV) : ?>
	  			$('.gov_service').css("cssText", "display: none !important");
	  	<?php endif; ?>	

	  	

	});

   	$('#govt_service_flag').on('change', function(){
   	    govt_service_flag = $(this).val();
   	    new_val = (govt_service_flag == '0') ? '1' : '0';
		$(this).val(new_val);
		if(new_val == 0)
		{
			$('.gov_service').css("cssText", "display: none !important");

			$('#employment_status_non_doh')[0].selectize.destroy();
			$('#employ_salary_grade_non_doh').prop('required', false);
			$('#employ_salary_step_non_doh').prop('required', false);
			$('#branch_name').prop('required', false);
			$('#separation_mode_non_doh').prop('required', false);

			var result = '<option value="">Select Employment Status</option>';
			var employment_status = <?php echo json_encode($employment_status) ?>;

			for(var i=0 ; i < employment_status.length; i++)
			{
					if(employment_status[i]['pr_flag'] == 'Y')
					{
						result += '<option value="' + employment_status[i]['employment_status_id'] + '">' + employment_status[i]['employment_status_name'] + '</option>';
					}
				
			}
			$('#employment_status_non_doh').html(result).selectize();
			
			//marvin : restore orig position field : start
			$("#div_employ_position").empty();
			
			var html = "";
			
			html += '<label for="employ_position">Position<span class="required">*</span></label>';
			html += '<input type="text" class="validate" required name="employ_position" id="employ_position" value="<?php echo !EMPTY($experience["employ_position_name"])? $experience["employ_position_name"]:"" ?>" <?php echo $action==ACTION_VIEW ? "disabled" : "" ?>/>';
			$("#div_employ_position").html(html);
			
			//marvin : restore orig position field : end
		}
		else
		{
			$('.gov_service').css("cssText", "display: table !important");

			$('#employment_status_non_doh')[0].selectize.destroy();
			$('#employ_salary_grade_non_doh').prop('required', true);
			$('#employ_salary_step_non_doh').prop('required', true);
			$('#branch_name').prop('required', true);
			//marvin : start : remove required attr
			// $('#separation_mode_non_doh').prop('required', true);

			var result = '<option value="">Select Employment Status</option>';
			var employment_status = <?php echo json_encode($employment_status) ?>;

			for(var i=0 ; i < employment_status.length; i++)
			{
					if(employment_status[i]['og_flag'] == 'Y')
					{
						result += '<option value="' + employment_status[i]['employment_status_id'] + '">' + employment_status[i]['employment_status_name'] + '</option>';
					}
				
			}
			$('#employment_status_non_doh').html(result).selectize();
			
			//DISABLE 02-10-2022
			//marvin : include position list : start
			// $("#div_employ_position").empty();
			
			// var html = "";
			
			// html += '<label for="employ_position" class="active">Position<span class="required">*</span></label>';
			// html += '<select id="employ_position" name="employ_position" <?php echo $action==ACTION_VIEW ? "disabled" : "" ?> class="selectize" placeholder="Select Position">';
			// option = '<option value="">Select Position</option>';
			// <?php foreach($position as $row):?>
			// option += '<option value="<?php echo $row["position_id"]; ?>"><?php echo $row["position_name"]; ?></option>';
			// <?php endforeach; ?>
			// html += '</select>';
		
			// $("#div_employ_position").html(html);
			// $("#employ_position").html(option).selectize();
			
			//GET THE POSITION - AJAX
			// $("#employ_position").on("change", function(){

				// var val       = $(this).val();
				// var date 	  = $('#employ_start_date_non_doh').val();

				// var $params = [];
				// $params     = {select_id 		 : val,
							   // employ_start_date : date};
				
				// $.post($base_url+"<?php echo PROJECT_MAIN."/pds_work_experience_info/get_param_position"?>",$params, function(result) {
					
					// console.log(result);
					
					// if(result.flag == "1"){
						// $("#employ_salary_grade_non_doh").val(result.data.salary_grade).focus();
						// $("#employ_position").empty();
						// $("#employ_position").html('<option value="'+result.data.position_name+'"></option>');
						
					// } else {
						// notification_msg("<?php echo ERROR ?>", result.msg);
					// }
				// }, 'json');
			// });
			//marvin : include position list : end
		}

   	});

	$('#form_experience_non').parsley();
	jQuery(document).off('click', '#save_experience');
	jQuery(document).on('click', '#save_experience', function(e){	
		$("#form_experience_non").trigger('submit');
	});

 	jQuery(document).off('submit', '#form_experience_non');
	jQuery(document).on('submit', '#form_experience_non', function(e){
	    e.preventDefault();
	    
		if ( $(this).parsley().isValid() ) {
			var data = $('#form_experience_non').serialize();
			var process_url = "";
			<?php if($module == MODULE_PERSONNEL_PORTAL):?>
				process_url = $base_url + 'main/pds_record_changes_requests/process_work_experience';
			<?php else: ?>
				process_url = $base_url + 'main/pds_work_experience_info/process_work_experience_non_doh';
			<?php endif; ?>
		  	button_loader('save_experience', 1);
		  	var option = {
					url  : process_url,
					data : data,
					success : function(result){
						if(result.status)
						{
							notification_msg("<?php echo SUCCESS ?>", result.message);
							modal_work_experience_non_doh.closeModal();
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
})
</script>