<form id="check_list_form">
	<input type="hidden" name="id" id="id" value="<?php echo !EMPTY($id) ? $id : NULL?>">
	<input type="hidden" name="salt" id="salt" value="<?php echo !EMPTY($salt) ? $salt : NULL?>">
	<input type="hidden" name="token" id="token" value="<?php echo !EMPTY($token) ? $token : NULL?>">
	<input type="hidden" name="action" id="action" value="<?php echo !EMPTY($action) ? $action : NULL?>">
	<input type="hidden" name="module" id="module" value="<?php echo !EMPTY($module) ? $action : NULL?>">
	
	<div class="form-float-label">
		<div class="row">
			<div class="col s12">
				<div class="input-field">
					<label class="<?php echo $action == ACTION_EDIT ? 'active' :'' ?>" for="check_list_name">Checklist Name<span class="required">*</span></label>
					<input type="text" class="validate" required name="check_list_name" id="check_list_name" value="<?php echo !EMPTY($check_list_info['check_list_code']) ? $check_list_info['check_list_code'] : NULL?>" <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>/>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col s12">
				<div class="input-field">
					<label for="check_list_type" class="active">Checklist Type<span class="required">*</span></label>
					<select id="check_list_type" required name="check_list_type[]" class="selectize" placeholder="Select Checklist Type" multiple <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>>
						<option value="">Select Checklist Type</option>
						<?php if (!EMPTY($supporting_document_type_name)): ?>
							<?php foreach ($supporting_document_type_name as $suppdoc): ?>
									<option value="<?php echo $suppdoc['supp_doc_type_id']?>"><?php echo strtoupper($suppdoc['supp_doc_type_name']) ?></option>
							<?php endforeach;?>
						<?php endif;?>
					</select>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col s12">
				<div class="input-field">
					<label class="<?php echo $action == ACTION_EDIT ? 'active' :'' ?>" for="check_list_description">Checklist Description<span class="required">*</span></label>
					<input type="text" class="validate" required name="check_list_description" id="check_list_description" value="<?php echo !EMPTY($check_list_info['check_list_description']) ? $check_list_info['check_list_description'] : NULL?>" <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>/>
				</div>
			</div>
		</div>
		<div class="row b-b-n">
			<div class='switch p-md'>
				<label>
					Inactive
					<input name='active_flag' type='checkbox'   value='Y' <?php echo ($check_list_info['active_flag'] == "Y") ? "checked" : "" ?> <?php echo $action == ACTION_ADD ? 'checked' :'' ?> <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>> 
					<span class='lever'></span>Active
				</label>
			</div>
		</div>
	</div>
	<div class="md-footer default">
		<?php if($action != ACTION_VIEW):?>
	  		<a class="waves-effect waves-teal btn-flat cancel_modal">Cancel</a>
		    <button class="btn btn-success " id="save_check_list" value="<?php echo BTN_SAVE ?>"><?php echo BTN_SAVE ?></button>
	  	<?php endif; ?>
	</div>
</form>
<script>
$(function (){
	$('#check_list_form').parsley();
	$('#check_list_form').submit(function(e) {
	    e.preventDefault();
	    
		if ( $(this).parsley().isValid() ) {
			var data = $(this).serialize();
		  	button_loader('save_check_list', 1);
		  	var option = {
					url  : $base_url + 'main/code_library_system/check_list/process',
					data : data,
					success : function(result){
						if(result.status)
						{
							notification_msg("<?php echo SUCCESS ?>", result.msg);
							modal_check_list.closeModal();
							load_datatable('check_list_table', '<?php echo PROJECT_MAIN ?>/code_library_system/check_list/get_check_list',false,0,0,true);
						}
						else
						{
							notification_msg("<?php echo ERROR ?>", result.msg);
						}	
						
					},
					
					complete : function(jqXHR){
						button_loader('save_check_list', 0);
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