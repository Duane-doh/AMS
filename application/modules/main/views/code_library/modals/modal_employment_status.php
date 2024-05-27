<form id="employment_status_form">
	<input type="hidden" name="id" id="id" value="<?php echo !EMPTY($id) ? $id : NULL?>">
	<input type="hidden" name="salt" id="salt" value="<?php echo !EMPTY($salt) ? $salt : NULL?>">
	<input type="hidden" name="token" id="token" value="<?php echo !EMPTY($token) ? $token : NULL?>">
	<input type="hidden" name="action" id="action" value="<?php echo !EMPTY($action) ? $action : NULL?>">
	<input type="hidden" name="module" id="module" value="<?php echo !EMPTY($module) ? $action : NULL?>">

	<div class="form-float-label">
		<div class="row">
		  <div class="col s12">
			<div class="input-field">
				<input type="text" class="validate" required name="employment_status_name" id="employment_status_name" value="<?php echo isset($employment_status_info['employment_status_name']) ? $employment_status_info['employment_status_name'] : NULL?>"  <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>/>
		   		<label class="<?php echo $action == ACTION_EDIT ? 'active' :'' ?>" for="employment_status_name">Employment Status Name<span class="required">*</span></label>
			</div>
		  </div>
		</div>
		<div class="row">
		  <div class="col s12">
			<div class="input-field">
				<input type="text" class="validate" required name="employment_status_code" id="employment_status_code" value="<?php echo isset($employment_status_info['employment_status_code']) ? $employment_status_info['employment_status_code'] : NULL?>"  <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>/>
		   		<label class="<?php echo $action == ACTION_EDIT ? 'active' :'' ?>" for="employment_status_code">Employment Status Code<span class="required">*</span></label>
			</div>
		  </div>
		</div>
		<div class="row">			
			<div class="col s12">
				<div class="input-field">
					<label for="employment_type" class="active">Employment Status Type</label>
					<select id="employment_type" name="employment_type[]" multiple class="selectize validate" placeholder="Select Employment Status Type" <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>>
					 <option value="">Select Parent Compensation</option>
					 <?php if (!EMPTY($employment_type)): ?>
						<?php foreach ($employment_type as $type): ?>
							<option value="<?php echo $type['employment_type_code']?>"><?php echo strtoupper($type['employment_type_name']) ?></option>
						<?php endforeach;?>
					<?php endif;?>
					</select>
				</div>
			</div>
		</div>
		<div class='row switch p-md b-b-n'>
		    <label>
		        Inactive
		        <input name='active_flag' type='checkbox'   value='Y' <?php echo ($employment_status_info['active_flag'] == "Y") ? "checked" : "" ?> <?php echo $action == ACTION_ADD ? 'checked' :'' ?> <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>> 
		        <span class='lever'></span>Active
		    </label>
		</div><br><br><br><br>
	</div>
	<div class="md-footer default">
	  	<?php if($action != ACTION_VIEW):?>
	  		<a class="waves-effect waves-teal btn-flat cancel_modal">Cancel</a>
		    <button class="btn btn-success " id="save_employment_status" value="<?php echo BTN_SAVE ?>"><?php echo BTN_SAVE ?></button>
	  	<?php endif; ?>
	</div>
</form>

<script>
$(function (){

	$('#employment_status_form').parsley();
	$('#employment_status_form').submit(function(e) {
	    e.preventDefault();
	    
		if ( $(this).parsley().isValid() ) {
			var data = $(this).serialize();
		  	button_loader('save_employment_status', 1);
		  	var option = {
					url  : $base_url + 'main/code_library_hr/employment_status/process',
					data : data,
					success : function(result){
						if(result.status)
						{
							notification_msg("<?php echo SUCCESS ?>", result.msg);
							modal_employment_status.closeModal();
							load_datatable('employment_status_table', '<?php echo PROJECT_MAIN ?>/code_library_hr/employment_status/get_employment_status_list',false,0,0,true);
						}
						else
						{
							notification_msg("<?php echo ERROR ?>", result.msg);
						}	
						
					},
					
					complete : function(jqXHR){
						button_loader('save_employment_status', 0);
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