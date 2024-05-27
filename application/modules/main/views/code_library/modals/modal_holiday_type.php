<form id="holiday_type_form">
	<input type="hidden" name="id" id="id" value="<?php echo !EMPTY($id) ? $id : NULL?>">
	<input type="hidden" name="salt" id="salt" value="<?php echo !EMPTY($salt) ? $salt : NULL?>">
	<input type="hidden" name="token" id="token" value="<?php echo !EMPTY($token) ? $token : NULL?>">
	<input type="hidden" name="action" id="action" value="<?php echo !EMPTY($action) ? $action : NULL?>">
	<input type="hidden" name="module" id="module" value="<?php echo !EMPTY($module) ? $action : NULL?>">
	
	<div class="form-float-label">
		<div class="row">
		  <div class="col s12">
			<div class="input-field">
				<input type="text" class="validate" required name="holiday_type_name" id="holiday_type_name" value="<?php echo isset($holiday_type_info['holiday_type_name']) ? $holiday_type_info['holiday_type_name'] : NULL; ?>" <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>/>
		   		<label class="<?php echo $action == ACTION_EDIT ? 'active' :'' ?>" for="holiday_type_name">Holiday Type Name<span class="required">*</span></label>
			</div>
		  </div>
		</div>
		<div class="row">
		  <div class="col s6">
				<div class="input-field">
					<label for="group_color" class="active">Color<span class="required">*</span></label>
					<input type="text" id="group_color" name="group_color" value="<?php echo isset($holiday_type_info['color_code']) ? $holiday_type_info['color_code'] : NULL; ?>" class="m-t-sm m-b-sm center" readonly placeholder="Select color" data-parsley-required="true" data-parsley-trigger="keyup" data-parsley-maxlength="45" style="height: 35px;" <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>>
				</div>
			</div>
			<div class="col s6">
				<div class="input-field">
					<label for="attendance_status" class="active">Attendance Status<span class="required">*</span></label>
					<select id="attendance_status" name="attendance_status" class="selectize" required placeholder="Select Attendance Status" <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>>
						<option value="">Select Attendance Status</option>
						<?php if (!EMPTY($attendance_status)): ?>
							<?php foreach ($attendance_status as $status): ?>
								<option value="<?php echo $status['attendance_status_id'] ?>"><?php echo strtoupper($status['attendance_status_name']) ?></option>
							<?php endforeach;?>
						<?php endif;?>
					</select>
				</div>
			</div>
		</div>
		<div class='row switch p-md b-b-n'>
		    <label>
		        Inactive
		        <input name='active_flag' type='checkbox'   value='Y' <?php echo ($holiday_type_info['active_flag'] == "Y") ? "checked" : "" ?> <?php echo $action == ACTION_ADD ? 'checked' :'' ?> <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>> 
		        <span class='lever'></span>
		        Active
		    </label><br><br><br><br><br><br><br><br><br><br>
		</div>
	</div>
	<div class="md-footer default">
	  	<?php if($action != ACTION_VIEW):?>
	  		<a class="waves-effect waves-teal btn-flat cancel_modal">Cancel</a>
		    <button class="btn btn-success " id="save_holiday_type" value="<?php echo BTN_SAVE ?>"><?php echo BTN_SAVE ?></button>
	  	<?php endif; ?>
	</div>
</form>
<script>
$(function (){
	Group.colorpick();
	$('#holiday_type_form').parsley();
	$('#holiday_type_form').submit(function(e) {
	    e.preventDefault();
	    
		if ( $(this).parsley().isValid() ) {
			var data = $(this).serialize();
		  	button_loader('save_holiday_type', 1);
		  	var option = {
					url  : $base_url + 'main/code_library_ta/holiday_type/process',
					data : data,
					success : function(result){
						if(result.status)
						{
							notification_msg("<?php echo SUCCESS ?>", result.msg);
							modal_holiday_type.closeModal();
							load_datatable('holiday_type_table', '<?php echo PROJECT_MAIN ?>/code_library_ta/holiday_type/get_holiday_type_list',false,0,0,true);
						}
						else
						{
							notification_msg("<?php echo ERROR ?>", result.msg);
						}	
						
					},
					
					complete : function(jqXHR){
						button_loader('save_holiday_type', 0);
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