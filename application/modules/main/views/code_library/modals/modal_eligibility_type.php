<form id="eligibility_type_form">
	<input type="hidden" name="id" id="id" value="<?php echo !EMPTY($id) ? $id : NULL?>">
	<input type="hidden" name="salt" id="salt" value="<?php echo !EMPTY($salt) ? $salt : NULL?>">
	<input type="hidden" name="token" id="token" value="<?php echo !EMPTY($token) ? $token : NULL?>">
	<input type="hidden" name="action" id="action" value="<?php echo !EMPTY($action) ? $action : NULL?>">
	<input type="hidden" name="module" id="module" value="<?php echo !EMPTY($module) ? $action : NULL?>">

	<div class="form-float-label">
		<div class="row">
		  <div class="col s12">
			<div class="input-field">
				<input type="text" class="validate" required name="eligibility_type_code" id="eligibility_type_code" value="<?php echo isset($eligibility_info['eligibility_type_code']) ? $eligibility_info['eligibility_type_code'] : NULL; ?>" <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>/>
		   		<label class="<?php echo $action == ACTION_EDIT ? 'active' :'' ?>" for="eligibility_type_code">Civil Service Eligibility Code<span class="required">*</span></label>
			</div>
		  </div>
		</div>
		<div class="row">
		  <div class="col s12">
			<div class="input-field">
				<input type="text" class="validate" required name="eligibility_type_name" id="eligibility_type_name" value="<?php echo isset($eligibility_info['eligibility_type_name']) ? $eligibility_info['eligibility_type_name'] : NULL; ?>" <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>/>
		   		<label class="<?php echo $action == ACTION_EDIT ? 'active' :'' ?>" for="eligibility_type_name">Civil Service Eligibility Name<span class="required">*</span></label>
			</div>
		  </div>
		</div>
		<div class="row">
			<div class="col s12">
				<div class="input-field">
					<label for="eligibility_level_name" class="active">Civil Service Eligibility Level</label>
					<select id="eligibility_level_name" name="eligibility_level_name" class="selectize" placeholder="Select Eligibility Level" <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>>
						<option value="">Select Eligibility Level</option>
						<?php if (!EMPTY($eligibility_level_name)): ?>
							<?php foreach ($eligibility_level_name as $level): ?>
								<option value="<?php echo $level['eligibility_level_id']?>"><?php echo strtoupper($level['eligibility_level_name']) ?></option>
							<?php endforeach;?>
						<?php endif;?>
					</select>
				</div>
		 	</div>
		</div>
		<div class="row">
			<div class="col s12">
				<div class="input-field">
					<label for="eligibility_type_flag" class="active">Civil Service Eligibility Type</label>
					<select id="eligibility_type_flag" name="eligibility_type_flag" class="selectize" placeholder="Select Eligibility Type" <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>>
					 <option value="">Select Eligibility Type</option>
					 <?php if (!EMPTY($eligibility_type_flag)): ?>
						<?php foreach ($eligibility_type_flag as $type): ?>
							<option value="<?php echo $type['sys_param_value']?>"><?php echo strtoupper($type['sys_param_name']) ?></option>
						<?php endforeach;?>
					<?php endif;?>
					</select>
				</div>
		 	</div>
		</div>
		<div class='row switch p-md b-b-n'>
		    <label>
		        Inactive
		        <input name='active_flag' type='checkbox'   value='Y' <?php echo ($eligibility_info['active_flag'] == "Y") ? "checked" : "" ?> <?php echo $action == ACTION_ADD ? 'checked' :'' ?> <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>> 
		        <span class='lever'></span>Active
		    </label>
		</div>
	</div>
	<div style="height: 50px;"></div>
	<div class="md-footer default">
	  	<?php if($action != ACTION_VIEW):?>
	  		<a class="waves-effect waves-teal btn-flat cancel_modal">Cancel</a>
		    <button class="btn btn-success " id="save_eligibility" value="<?php echo BTN_SAVE ?>"><?php echo BTN_SAVE ?></button>
	  	<?php endif; ?>
	</div>
</form>
<script>
$(function (){
	$('#eligibility_type_form').parsley();
	$('#eligibility_type_form').submit(function(e) {
	    e.preventDefault();
	    
		if ( $(this).parsley().isValid() ) {
			var data = $(this).serialize();
		  	button_loader('save_eligibility', 1);
		  	var option = {
					url  : $base_url + 'main/code_library_hr/eligibility/process',
					data : data,
					success : function(result){
						if(result.status)
						{
							notification_msg("<?php echo SUCCESS ?>", result.msg);
							modal_eligibility_type.closeModal();
							load_datatable('eligibility_table', '<?php echo PROJECT_MAIN ?>/code_library_hr/eligibility/get_eligibility_list',false,0,0,true);
						}
						else
						{
							notification_msg("<?php echo ERROR ?>", result.msg);
						}	
						
					},
					
					complete : function(jqXHR){
						button_loader('save_eligibility', 0);
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