<form id="form_education">
	<input type="hidden" name="id" value="<?php echo $id ?>"/>
	<input type="hidden" name="salt" value="<?php echo $salt ?>"/>
	<input type="hidden" name="token" value="<?php echo $token ?>"/>
	<input type="hidden" name="action" value="<?php echo $action ?>"/>
	<input type="hidden" name="module" value="<?php echo $module ?>"/>
	<div class="form-float-label">
		<div class="row m-n">
		    <div class="col s6">
			  	<div class="input-field">
				    <label for="level" class="active">Level<span class="required">*</span></label>
					<select id="level" name="level" required <?php echo $action==ACTION_VIEW ? 'disabled' : '' ?> class="selectize" placeholder="Select Level">
						<option value="">Select Level</option>
						<?php if (!EMPTY($education_level)): ?>
							<?php foreach ($education_level as $level): ?>
								<option value="<?php echo $level['educ_level_id'] ?>"><?php echo $level['educ_level_name'] ?></option>
							<?php endforeach;?>
						<?php endif;?>
					</select>
		      	</div>
		   	</div>
		    <div class="col s6">
			  	<div class="input-field">
				    <label for="degree_course" class="active">Degree Course<span class="required">*</span></label>
					<select id="degree_course" name="degree_course" <?php echo $action==ACTION_VIEW ? 'disabled' : '' ?> class="selectize" placeholder="Select Degree Course" required>
						<option value="">Select Degree/Course</option>
						<?php if (!EMPTY($degrees)): ?>
							<?php foreach ($degrees as $degree): ?>
								<option value="<?php echo $degree['degree_id'] ?>"><?php echo $degree['degree_name'] ?></option>
							<?php endforeach;?>
						<?php endif;?>
					</select>
			  	</div>
	   		</div>
	  	</div>

		<div class="row m-n">
		    <div class="col s4">
			  	<div class="input-field">
				   	<label for="start_year" class="active" required>Year Started<span class="required">*</span></label>
				   	<?php echo create_years('1900', date('Y'), 'start_year', $education['start_year'])?>
			  	</div>
		    </div>
		    <div class="col s4">
			  	<div class="input-field">
				   	<label for="end_year" class="active" required id="label"><?php echo ($education['year_graduated_flag'] == "Y")? "Year Graduated ":"Year Ended" ?><span class="required">*</span></label>
				   	<?php echo create_years('1900', date('Y'), 'end_year', $education['end_year'])?>
			  	</div>
		    </div>
			<div class="col s4">
			  	<div class="input-field">
				    <input name='year_graduated_flag' type='hidden' value='N'>
			   		<input type="checkbox" class="labelauty" name="year_graduated_flag" id="year_graduated_flag" value="Y" data-labelauty="Tag as Graduated|Graduated" <?php echo ($education['year_graduated_flag'] == "Y")? " checked ":"" ?><?php echo ($action == ACTION_VIEW) ? ' disabled ' : '' ?>/>			
		    	</div>
			</div>
		</div>

		<div class="row m-n">
		    <div class="col s6">
			  	<div class="input-field">
				   	<label for="school_name" class="active">Name of School<span class="required">*</span></label>
						<select id="school_name" name="school_name" <?php echo $action==ACTION_VIEW ? 'disabled' : '' ?> class="selectize" placeholder="Select School" required>
						<option value="">Select School</option>
						<?php if (!EMPTY($schools)): ?>
							<?php foreach ($schools as $school): ?>
								<option value="<?php echo $school['school_id'] ?>"><?php echo $school['school_name'] ?></option>
							<?php endforeach;?>
						<?php endif;?>
					</select>
			  	</div>
		    </div>
		    <div class="col s6">
			  	<div class="input-field">
				   	<input class="validate" type="text" name="highest_level" id="highest_level" value="<?php echo isset($education['highest_level'])? $education['highest_level'] : '' ?>"<?php echo $action==ACTION_ADD ? 'required' : '' ?><?php echo ($education['year_graduated_flag'] == "Y")? "disabled":"required" ?>/>
				    <label for="highest_level">Highest Grade/Level/Units Earned<span id="required" class="required"><?php echo $action==ACTION_ADD ? '*' : '' ?><?php echo ($education['year_graduated_flag'] == "N") ? '*' : '' ?></span></label>
			  	</div>
		    </div>
		</div>

		<div class="row m-n">
		    <div class="col s12">
			 	<div class="input-field">
			   		<label for="educ_honors_received">Scholarship/Academic Honors Received</label>
			   		<input type="text" class="validate" name="educ_honors_received" id="educ_honors_received" value="<?php echo isset($education['academic_honor'])? $education['academic_honor'] : '' ?>" <?php echo $action==ACTION_VIEW ? 'disabled' : '' ?>/>
			  	</div>
		    </div>
		</div>
		<div class='row switch p-md b-b-n'>
			Relevant<br><br>
			<label>
				No
				<input name='relevance_flag' type='checkbox' value='Y' <?php echo ($education['relevance_flag'] == "Y") ? "checked" : "" ?> <?php echo $action == ACTION_ADD ? 'checked' :'' ?> <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>> 
				<span class='lever'></span>Yes
			</label>
		</div>
	</div><br><br><br><br><br><br><br><br>

	<?php if($action != ACTION_VIEW):?>
	<div class="md-footer default">
		<a class="waves-effect waves-teal btn-flat cancel_modal">Cancel</a>	  
	    <button class="btn btn-success " type="button" id="save_education" value="<?php echo BTN_SAVE ?>"><?php echo BTN_SAVE ?></button>
	</div>
	<?php endif; ?>
</form>
<script>

// $('#level').on( "change", function() {
// 	var selected = $(this).val();

// 	if (selected == '<?php echo LEVEL_ELEMENTARY?>' || selected == '<?php echo LEVEL_SECONDARY?>') 
// 	{		
// 		$('#degree_course')[0].selectize.destroy();
// 		$('#degree_course').attr("disabled", true);
// 		$('#degree_course').selectize();
// 		$("#degree_course")[0].selectize.clear();
// 		$('#degree_course').prop('required', false);
// 	}
// 	else 
// 	{		
// 		$('#degree_course')[0].selectize.destroy();
// 		<?php if($action != ACTION_VIEW): ?>
// 		$('#degree_course').attr("disabled", false);
// 		<?php endif;?>
// 		$('#degree_course').selectize();
// 		$("#degree_course")[0].selectize.clear();
// 		$('#degree_course').prop('required', true);
// 	}
// });

/*===== jendaigo : start : include degree_course validation =====*/

$("#level").change(function(){
	
	select = $('#degree_course').selectize();
	selectSizeControl = select[0].selectize;
	selectSizeControl.lock();

	switch($(this).val())
	{
		case '17':
			selectedValue = selectSizeControl.setValue('10004');
			break;
		case '18':
			selectedValue = selectSizeControl.setValue('10005');
			break;
		default:
			selectedValue = selectSizeControl.setValue();
			selectSizeControl.unlock();
	}
	
});

/*===== jendaigo : end : include degree_course validation =====*/

$("#year_graduated_flag").change(function() {
    var required_yr = '<span style="color: red">*</span>';
    if ($('#year_graduated_flag').is(":checked")) 
    {
    	$('#required').empty().append('');
    	$('#label').html("Year Graduated ");
    	$('#label').append(required_yr);
       	$('#highest_level').prop('required', false);
		$('#highest_level').attr("disabled", true);
		$('#highest_level').val('');
    } 
    else 
    {
    	$('#required').empty().append('*');
    	$('#label').html("Year Ended ");
    	$('#label').append(required_yr);
       	$('#highest_level').prop('required', true);
		$('#highest_level').attr("disabled", false);
    }
});

$(function (){
	<?php if($action != ACTION_ADD){ ?>
		$('.input-field label').addClass('active');
  	<?php } ?>
	$('#form_education').parsley();
	jQuery(document).off('click', '#save_education');
	jQuery(document).on('click', '#save_education', function(e){	
		$("#form_education").trigger('submit');
	});

 	jQuery(document).off('submit', '#form_education');
	jQuery(document).on('submit', '#form_education', function(e){
	    e.preventDefault();
	    
		if ( $(this).parsley().isValid() ) {
			var data = $('#form_education').serialize();
			var process_url = "";
			<?php if($module == MODULE_PERSONNEL_PORTAL):?>
				process_url = $base_url + 'main/pds_record_changes_requests/process_education';
			<?php else: ?>
				process_url = $base_url + 'main/pds_education_info/process';
			<?php endif; ?>
		  	button_loader('save_education', 1);
		  	var option = {
					url  : process_url,
					data : data,
					success : function(result){
						if(result.status)
						{
							notification_msg("<?php echo SUCCESS ?>", result.message);
							modal_education.closeModal();
							load_datatable('pds_education_table', '<?php echo PROJECT_MAIN ?>/pds_education_info/get_education_list',false,0,0,true);
						}
						else
						{
							notification_msg("<?php echo ERROR ?>", result.message);
						}	
						
					},
					
					complete : function(jqXHR){
						button_loader('save_education', 0);
					}
			};

			General.ajax(option);    
	    }
  	});

	<?php if($action == ACTION_VIEW){ ?>
		$('#start_year').attr("disabled", true);
		$('#end_year').attr("disabled", true);
		$('#highest_level').attr("disabled", true);
		$('#degree_course').attr("disabled", true);
  	<?php } ?>

})
</script>