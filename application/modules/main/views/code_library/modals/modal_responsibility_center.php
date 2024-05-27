<form id="responsibility_center_form">
	<input type="hidden" name="id" id="id" value="<?php echo !EMPTY($id) ? $id : NULL?>">
	<input type="hidden" name="salt" id="salt" value="<?php echo !EMPTY($salt) ? $salt : NULL?>">
	<input type="hidden" name="token" id="token" value="<?php echo !EMPTY($token) ? $token : NULL?>">
	<input type="hidden" name="action" id="action" value="<?php echo !EMPTY($action) ? $action : NULL?>">
	<input type="hidden" name="module" id="module" value="<?php echo !EMPTY($module) ? $action : NULL?>">

	<div class="form-float-label">
		<div class="row">
		  <div class="col s12">
			<div class="input-field">
				<input type="text" class="validate" required name="responsibility_center_desc" id="responsibility_center_desc" value="<?php echo isset($responsibility_center_info['responsibility_center_desc']) ? $responsibility_center_info['responsibility_center_desc'] : NULL?>" <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>/>
		   		<label class="<?php echo $action == ACTION_EDIT ? 'active' :'' ?>" for="responsibility_center_desc">Responsibility Center Description<span class="required">*</span></label>
			</div>
		  </div>
		</div>
		<div class="row">
		  <div class="col s12">
			<div class="input-field">
				<input type="text" class="validate" required name="responsibility_center_code" id="responsibility_center_code" value="<?php echo isset($responsibility_center_info['responsibility_center_code']) ? $responsibility_center_info['responsibility_center_code'] : NULL?>" <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>/>
		   		<label class="<?php echo $action == ACTION_EDIT ? 'active' :'' ?>" for="responsibility_center_code">Responsibility Center Code<span class="required">*</span></label>
			</div>
		  </div>
		</div>
		<!-- ===================== jendaigo : start : include prexc code selection ============= -->
		<div class="row">
		 	<div class="col s12">
				<div class="input-field">
					<label for="prexc_code" class="active">Prexc Code<span class="required">*</span></label>
					<select id="prexc_code" name="prexc_code" class="selectize" placeholder="Select Prexc Code" required<?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>>
					 <option value="">Select Prexc Code</option>
					 <?php if (!EMPTY($prexc_code_info)): ?>
						<?php foreach ($prexc_code_info as $pcode): ?>
							<option value="<?php echo $pcode['prexc_code_id']?>" <?php echo ($responsibility_center_info['prexc_code_id'] == $pcode['prexc_code_id'] ? ' selected' : '')?> ><?php echo strtoupper($pcode['prexc_code']) ?></option>
						<?php endforeach;?>
					<?php endif;?>
					</select>
				</div>
			</div>
		</div>
		<!-- ===================== jendaigo : end : include prexc code selection ============= -->
		<div class='row switch p-md b-b-n'>
		    <label>
		        Inactive
		        <input name='active_flag' type='checkbox' value='Y' <?php echo ($responsibility_center_info['active_flag'] == "Y") ? "checked" : "" ?> <?php echo $action == ACTION_ADD ? 'checked' :'' ?> <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>> 
		        <span class='lever'></span>Active
		    </label>
		</div>
	</div>
	<div class="md-footer default">
	  	<?php if($action != ACTION_VIEW):?>
	  		<a class="waves-effect waves-teal btn-flat cancel_modal">Cancel</a>
		    <button class="btn btn-success " id="save_responsibility_center" value="<?php echo BTN_SAVE ?>"><?php echo BTN_SAVE ?></button>
	  	<?php endif; ?>
	</div>
</form>

<script>
$(function (){

	$('#responsibility_center_form').parsley();
	$('#responsibility_center_form').submit(function(e) {
	    e.preventDefault();

		if ( $(this).parsley().isValid() ) {
			var data = $(this).serialize();
		  	button_loader('save_responsibility_center', 1);
		  	var option = {
					url  : $base_url + 'main/code_library_payroll/responsibility_center/process',
					data : data,
					success : function(result){
						if(result.status)
						{
							notification_msg("<?php echo SUCCESS ?>", result.msg);
							modal_responsibility_center.closeModal();
							load_datatable('responsibility_center_table', '<?php echo PROJECT_MAIN ?>/code_library_payroll/responsibility_center/get_responsibility_center_list', false,0,0,true);
						}
						else
						{
							notification_msg("<?php echo ERROR ?>", result.msg);
						}	
						
					},
					
					complete : function(jqXHR){
						button_loader('save_responsibility_center', 0);
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