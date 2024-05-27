<form id="sys_param_form">
	<input type="hidden" name="id" id="id" value="<?php echo !EMPTY($id) ? $id : NULL?>">
	<input type="hidden" name="salt" id="salt" value="<?php echo !EMPTY($salt) ? $salt : NULL?>">
	<input type="hidden" name="token" id="token" value="<?php echo !EMPTY($token) ? $token : NULL?>">
	<input type="hidden" name="action" id="action" value="<?php echo !EMPTY($action) ? $action : NULL?>">
	<input type="hidden" name="module" id="module" value="<?php echo !EMPTY($module) ? $action : NULL?>">

	<div class="form-float-label">
		<div class="row">
			<div class="col s12">
				<div class="input-field">
					<label for="sys_param_type" class="active">System Parameter Type<span class="required">*</span></label>
					<select id="sys_param_type" name="sys_param_type" class="selectize" placeholder="Select System Parameter Type" required <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>>
						<option value="">Select System Parameter Type</option>
						<?php if (!EMPTY($sys_param_type)): ?>
							<?php foreach ($sys_param_type as $sys): ?>
									<option value="<?php echo $sys['sys_param_type']?>"><?php echo $sys['sys_param_type'] ?></option>
							<?php endforeach;?>
						<?php endif;?>
					</select>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col s12">
				<div class="input-field">
					<label for="sys_param_name">System Parameter Name<span class="required">*</span></label>
					<input type="text" class="validate" required name="sys_param_name" id="sys_param_name" value="<?php echo isset($sys_param_info['sys_param_name']) ? $sys_param_info['sys_param_name'] : NULL?>" <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?> <?php echo $action == ACTION_EDIT ? 'active' :'' ?>/>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col s12">
				<div class="input-field">
					<label class="<?php echo $action == ACTION_EDIT ? 'active' :'' ?>" for="sys_param_value">System Parameter Value<span class="required">*</span></label>
					<input type="text" class="validate" required name="sys_param_value" id="sys_param_value" value="<?php echo isset($sys_param_info['sys_param_value']) ? $sys_param_info['sys_param_value'] : NULL?>" <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>/>
				</div>
			</div>
		</div>
	</div>
	<div class="md-footer default">
		<?php if($action != ACTION_VIEW):?>
	  		<a class="waves-effect waves-teal btn-flat cancel_modal">Cancel</a>
		    <button class="btn btn-success " id="save_sys_param" value="<?php echo BTN_SAVE ?>"><?php echo BTN_SAVE ?></button>
	  	<?php endif; ?>
	</div>
</form>
<script>
	$(function (){
		$('#sys_param_form').parsley();
		$('#sys_param_form').submit(function(e) {
			e.preventDefault();

			if ( $(this).parsley().isValid() ) {
				var data = $(this).serialize();
				button_loader('save_sys_param', 1);
				var option = {
					url  : $base_url + 'main/code_library_system/sys_param/process',
					data : data,
					success : function(result){
						if(result.status)
							{
								notification_msg("<?php echo SUCCESS ?>", result.msg);
								modal_sys_param.closeModal();
								load_datatable('sys_param_table', '<?php echo PROJECT_MAIN ?>/code_library_system/sys_param/get_sys_param_list',false,0,0,true);
							}
							else
							{
								notification_msg("<?php echo ERROR ?>", result.msg);
							}	
					},

					complete : function(jqXHR){
					button_loader('save_sys_param', 0);
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