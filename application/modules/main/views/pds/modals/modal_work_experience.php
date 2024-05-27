<form id="form_experience">
	<input type="hidden" name="id" value="<?php echo $id ?>"/>
	<input type="hidden" name="salt" value="<?php echo $salt ?>"/>
	<input type="hidden" name="token" value="<?php echo $token ?>"/>
	<input type="hidden" name="action" value="<?php echo $action ?>"/>
	<input type="hidden" name="module" value="<?php echo $module ?>"/>

	<div class="form-float-label">
		<div class="row m-n">
			<div class="col s6">
				<div class="input-field">
					<input type="text" class="validate datepicker_start " required="" aria-required="true" name="employ_start_date" id="employ_start_date" value="<?php echo isset($experience['employ_start_date'])? $experience['employ_start_date']:"" ?>" <?php echo $action==ACTION_VIEW ? 'disabled' : '' ?>/>
					<label for="employ_start_date">Date Started</label>
				</div>
			</div>
			<div class="col s6">
				<div class="input-field">
					<input type="text" class="validate datepicker_end" required="" aria-required="true" name="employ_end_date" id="employ_end_date" value="<?php echo isset($experience['employ_end_date'])? $experience['employ_end_date']:"" ?>" <?php echo $action==ACTION_VIEW ? 'disabled' : '' ?> />
					<label for="employ_end_date">Date Ended</label>
				</div>
			</div>
		</div>
		<div class="row m-n">
			<div class="col s12">
				<div class="input-field">
					<label for="employ_position">Position</label>
					<input type="text" class="validate" required="" aria-required="true" name="employ_position" id="employ_position" value="<?php echo isset($experience['employ_position'])? $experience['employ_position']:"" ?>" <?php echo $action==ACTION_VIEW ? 'disabled' : '' ?>/>
				</div>
			</div>
		</div>
		<div class="row m-n">
			<div class="col s12">
				<div class="input-field">
					<input type="text" class="validate" required="" aria-required="true" name="employ_company_name" id="employ_company_name" value="<?php echo isset($experience['employ_company_name'])? $experience['employ_company_name']:"" ?>" <?php echo $action==ACTION_VIEW ? 'disabled' : '' ?>/>
					<label for="employ_company_name">Department/Agency/Office/Company</label>
				</div>
			</div>
		</div>
		<div class="row m-n">
			<div class="col s6">
				<div class="input-field">
					<input type="text" class="validate" required="" aria-required="true" name="employ_monthly_salary" id="employ_monthly_salary" value="<?php echo isset($experience['employ_monthly_salary'])? $experience['employ_monthly_salary']:"" ?>" <?php echo $action==ACTION_VIEW ? 'disabled' : '' ?>/>
					<label for="employ_monthly_salary">Monthly Salary</label>
				</div>
			</div>
			<div class="col s6">
				<div class="input-field">
					<label for="appointment_status_id" class="active">Status of Appointment</label>
					<select id="appointment_status_id" name="appointment_status_id" <?php echo $action ==ACTION_VIEW ? 'disabled' : '' ?> class="selectize" placeholder="Select Status of Appointment">
						<option value="">Select Status of Appointment</option>
						<?php if (!EMPTY($appointment_status)): ?>
						<?php foreach ($appointment_status as $type): ?>
								<option value="<?php echo $type['appointment_status_id'] ?>"><?php echo $type['appointment_status_name'] ?></option>
						<?php endforeach;?>
						<?php endif;?>
					</select>
				</div>
			</div>
		</div>
		<div class="row m-n">
			<div class="col s4">
				<div class="input-field">
					<input type="text" class="validate" required="" aria-required="true" name="employ_salary_grade" id="employ_salary_grade" value="<?php echo isset($experience['employ_salary_grade'])? $experience['employ_salary_grade']:"" ?>" <?php echo $action==ACTION_VIEW ? 'disabled' : '' ?>/>
					<label for="employ_salary_grade">Salary Grade</label>
				</div>
			</div>
			<div class="col s3">
				<div class="input-field">
					<input type="text" class="validate" required="" aria-required="true" name="employ_salary_step" id="employ_salary_step" value="<?php echo isset($experience['employ_salary_step'])? $experience['employ_salary_step']:"" ?>" <?php echo $action==ACTION_VIEW ? 'disabled' : '' ?>/>
					<label for="employ_salary_step">Pay Step</label>
				</div>
			</div>
			<div class="col s5">
				<div class="input-field">
					<input type="checkbox" class="labelauty" name="govt_service_flag" id="govt_service_flag" value="<?php echo ACTIVE ?>" data-labelauty="Tag as Gov't Service|Gov't Service" <?php echo isset($experience['govt_service_flag'])? "checked":"" ?> <?php echo $action==ACTION_VIEW ? 'disabled' : '' ?>/>
				</div>
			</div>
		</div>
	</div>	
</form>

<?php if($action != ACTION_VIEW):?>
	<div class="md-footer default">
		<a class="waves-effect waves-teal btn-flat cancel_modal">Cancel</a>
		<button class="btn btn-success " id="save_experience" value="<?php echo BTN_SAVE ?>"><?php echo BTN_SAVE ?></button>
	</div>
<?php endif;?>

<script>
$(function (){
	<?php if($action != ACTION_ADD){ ?>
		$('.input-field label').addClass('active');
  	<?php } ?>
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
				process_url = $base_url + 'main/pds_record_changes_requests/process_work_experience';
			<?php else: ?>
				process_url = $base_url + 'main/pds_work_experience_info/process_work_experience';
			<?php endif; ?>
		  	button_loader('save_experience', 1);
		  	var option = {
					url  : process_url,
					data : data,
					success : function(result){
						if(result.status)
						{
							notification_msg("<?php echo SUCCESS ?>", result.message);
							modal_work_experience.closeModal();
							load_datatable('pds_work_experience_table', '<?php echo PROJECT_MAIN ?>/pds_work_experience_info/get_work_experience_list',false,0,0,true);
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
})
</script>