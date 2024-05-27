<form id="leave_type_form">
	<input type="hidden" name="id" id="id" value="<?php echo !EMPTY($id) ? $id : NULL?>">
	<input type="hidden" name="salt" id="salt" value="<?php echo !EMPTY($salt) ? $salt : NULL?>">
	<input type="hidden" name="token" id="token" value="<?php echo !EMPTY($token) ? $token : NULL?>">
	<input type="hidden" name="action" id="action" value="<?php echo !EMPTY($action) ? $action : NULL?>">
	<input type="hidden" name="module" id="module" value="<?php echo !EMPTY($module) ? $module : NULL?>">
	<input type="hidden" name="leave_id" id="leave_id" value="<?php echo !EMPTY($leave_id) ? $leave_id : NULL?>">
	<div class="form-float-label">
		<div class="row">
			<div class="col s12">
				<div class="input-field">
					<label class="<?php echo $action == ACTION_EDIT ? 'active' :'' ?>" for="leave_type_name">Leave Type Name<span class="required">*</span></label>
					<input type="text" class="validate" required name="leave_type_name" id="leave_type_name" value="<?php echo isset($leave_type_info['leave_type_name']) ? $leave_type_info['leave_type_name'] : NULL?>" <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>/>
				</div>
			</div>
		</div>
		<div class='row'>
			<div class="col s12">
				<div class="input-field">
					<label class="active" for="deduction_leave_type">Deduction Leave Type<span class="required">*</span></label>
					<select id="deduction_leave_type" name="deduction_leave_type" class="selectize" required placeholder="Select Deduction Leave Type" <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>>
						<option value="">Select Attendance Status</option>
						<?php //if (!EMPTY($deduct_leave_type)): ?>
						<option <?php if($deduct_leave_type == 0){ echo"selected"; } ?> value="<?php echo LEAVE_TYPE_NA; ?>">NOT APPLICABLE</option>
						<option <?php echo (!empty($deduct_leave_type)) ? $deduct_leave_type == 1 ? "selected" : "" : "" ; ?> value="<?php echo LEAVE_TYPE_SICK; ?>">SICK LEAVE</option>
						<option <?php echo (!empty($deduct_leave_type)) ? $deduct_leave_type == 2 ? "selected" : "" : "" ; ?>  value="<?php echo LEAVE_TYPE_VACATION; ?>">VACATION LEAVE</option>	
						<?php //endif;?>
				</select>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col s6">
				<div class='switch p-md'>
					Include In Certification<span class="required">*</span><br><br>
				    <label>
				        No
				        <input name='include_certification' type='checkbox'   value='Y' <?php echo ($include_cert == "Y") ? "checked" : "" ?> <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>> 
				        <span class='lever'></span>Yes
				    </label>
				</div>
			</div>
			<div class="col s6">
				<div class='switch p-md'>
					Built In Flag<span class="required">*</span><br><br>
				    <label>
				        No
				        <input name='built_in_flag' type='checkbox'   value='Y' <?php echo ($built_in_flag == "Y") ? "checked" : "" ?> <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>> 
				        <span class='lever'></span>Yes
				    </label>
				</div>
			</div>
		</div>
		<div class="row">
			<!--<div class="col s12">
				<div class="input-field">
					<label class="active" for="sort_num">Sort Number<span class="required">*</span></label>
					<input type="text" class="validate" required name="sort_num" id="sort_num" value="<?php //echo $sort_num?>" <?php //echo $action == ACTION_VIEW ? 'disabled' :'' ?>/>
				</div>
			</div>-->
			<!-- marvin : start : include type of day -->
			<div class="col s6">
				<div class="input-field">
					<label class="active" for="sort_num">Sort Number<span class="required">*</span></label>
					<input type="text" class="validate" required name="sort_num" id="sort_num" value="<?php echo $sort_num?>" <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>/>
				</div>
			</div>
			<div class="col s6">
				<div class='switch p-md'>
					Nature of Deduction<span class="required">*</span><br><br>
					<input type="radio" id="calendar_days" class="with-gap" name="nature_of_deduction" value="1" <?php echo $leave_type_info['nature_of_deduction'] == 1 ? 'checked' : ''; ?> <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?> />
					<label for="calendar_days">Calendar Days</label>
					<input type="radio" id="working_days" class="with-gap" name="nature_of_deduction" value="2" <?php echo $leave_type_info['nature_of_deduction'] == 2 ? 'checked' : ''; ?> <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?> />
					<label for="working_days">Working Days</label>
				</div>
			</div>
			<!-- marvin : end : include type of day -->
		</div>
		<div class='row switch p-md b-b-n'>
		    <label>
	        Inactive
		        <input name='active_flag' type='checkbox'   value='Y' <?php echo ($active_flag == "Y") ? "checked" : "" ?>  <?php echo $action == ACTION_ADD ? 'checked' :'' ?> <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>> 
		        <span class='lever'></span>Active
		    </label>
		</div>	
	</div>

	<div class="md-footer default">
	  	<?php if($action != ACTION_VIEW):?>
	  		<a class="waves-effect waves-teal btn-flat cancel_modal">Cancel</a>
		    <button class="btn btn-success " id="save_leave_type" value="<?php echo BTN_SAVE ?>"><?php echo BTN_SAVE ?></button>
	  		
	  	<?php endif; ?>
	</div>
</form>

<script>
$(function (){
	$('#leave_type_form').parsley();
	$('#leave_type_form').submit(function(e) {
	    e.preventDefault();
	    
		if ( $(this).parsley().isValid() ) {
			var data = $(this).serialize();
		  	button_loader('save_leave_type', 1);
		  	var option = {
					url  : $base_url + 'main/code_library_ta/leave_type/process',
					data : data,
					success : function(result){
						if(result.status)
						{
							notification_msg("<?php echo SUCCESS ?>", result.msg);
							modal_leave_type.closeModal();
							load_datatable('leave_type_table', '<?php echo PROJECT_MAIN ?>/code_library_ta/leave_type/get_leave_type_list',false,0,0,true);

							}
						else
						{
							notification_msg("<?php echo ERROR ?>", result.msg);
						}	
						
					},
					
					complete : function(jqXHR){
						button_loader('save_leave_type', 0);
					}
			};

			General.ajax(option);    
	    }
  	});

  	<?php if($action != ACTION_ADD){ ?>
		$('.input-field label').addClass('active');
  	<?php } ?>
})
</script>