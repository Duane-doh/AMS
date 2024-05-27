<form id="personnel_movement_form">
	<input type="hidden" name="id" id="id" value="<?php echo !EMPTY($id) ? $id : NULL?>">
	<input type="hidden" name="salt" id="salt" value="<?php echo !EMPTY($salt) ? $salt : NULL?>">
	<input type="hidden" name="token" id="token" value="<?php echo !EMPTY($token) ? $token : NULL?>">
	<input type="hidden" name="action" id="action" value="<?php echo !EMPTY($action) ? $action : NULL?>">
	<input type="hidden" name="module" id="module" value="<?php echo !EMPTY($module) ? $action : NULL?>">

	<div class="form-float-label">
		<div class="row">
		  <div class="col s12">
			<div class="input-field">
				<input type="text" class="validate" required name="personnel_movement_name" id="personnel_movement_name" value="<?php echo isset($personnel_movement_info['personnel_movement_name']) ? $personnel_movement_info['personnel_movement_name'] : NULL?>" <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>/>
		   		<label class="<?php echo $action == ACTION_EDIT ? 'active' :'' ?>" for="personnel_movement_name">Personnel Movement Name<span class="required">*</span></label>
			</div>
		  </div>
		</div>
		<div class="row">
		  <div class="col s12">
			<div class="input-field">
				<input type="text" class="validate" required name="replacement_reason" id="replacement_reason" value="<?php echo isset($personnel_movement_info['replacement_reason']) ? $personnel_movement_info['replacement_reason'] : NULL?>"  <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>/>
		   		<label class="<?php echo $action == ACTION_EDIT ? 'active' :'' ?>" for="replacement_reason">Replacement Reason<span class="required">*</span></label>
			</div>
		  </div>
		</div>		
		<div class="row">
			<div class="col s6">
				<div class='switch p-md'>
					Needs Appointment?<br><br>
				    <label>
				        No
				        <input name='needs_appointment' type='checkbox'   value='Y' <?php echo ($personnel_movement_info['needs_appointment'] == "Y") ? "checked" : "" ?> <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>> 
				        <span class='lever'></span>Yes
				    </label>
				</div>
			</div>
			<div class="col s6">
				<div class='switch p-md'>
					Needs Office Order?<br><br>
				    <label>
				    	No
				        <input name='needs_office_order' type='checkbox'   value='Y' <?php echo ($personnel_movement_info['needs_office_order'] == "Y") ? "checked" : "" ?> <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>> 
				        <span class='lever'></span>Yes
				    </label>
				</div>
			</div>
		</div>
		<div class='row switch p-md b-b-n'>
		    <label>
		        Inactive
		        <input name='active_flag' type='checkbox'   value='Y' <?php echo ($personnel_movement_info['active_flag'] == "Y") ? "checked" : "" ?> <?php echo $action == ACTION_ADD ? 'checked' :'' ?> <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>> 
		        <span class='lever'></span>Active
		    </label>
		</div>
	</div>
	<div class="md-footer default">
	  	<?php if($action != ACTION_VIEW):?>
	  		<a class="waves-effect waves-teal btn-flat cancel_modal">Cancel</a>
		    <button class="btn btn-success " id="save_personnel_movement" value="<?php echo BTN_SAVE ?>"><?php echo BTN_SAVE ?></button>
	  	<?php endif; ?>
	</div>
</form>

<script>
$(function (){

	$('#personnel_movement_form').parsley();
	$('#personnel_movement_form').submit(function(e) {
	    e.preventDefault();

		if ( $(this).parsley().isValid() ) {
			var data = $(this).serialize();
		  	button_loader('save_personnel_movement', 1);
		  	var option = {
					url  : $base_url + 'main/code_library_hr/personnel_movement/process',
					data : data,
					success : function(result){
						if(result.status)
						{
							notification_msg("<?php echo SUCCESS ?>", result.msg);
							modal_personnel_movement.closeModal();
							load_datatable('personnel_movement_table', '<?php echo PROJECT_MAIN ?>/code_library_hr/personnel_movement/get_personnel_movement_list', false,0,0,true);
						}
						else
						{
							notification_msg("<?php echo ERROR ?>", result.msg);
						}	
						
					},
					
					complete : function(jqXHR){
						button_loader('save_personnel_movement', 0);
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