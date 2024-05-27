<form id="voucher_form">
	<input type="hidden" name="id" id="id" value="<?php echo !EMPTY($id) ? $id : NULL?>">
	<input type="hidden" name="salt" id="salt" value="<?php echo !EMPTY($salt) ? $salt : NULL?>">
	<input type="hidden" name="token" id="token" value="<?php echo !EMPTY($token) ? $token : NULL?>">
	<input type="hidden" name="action" id="action" value="<?php echo !EMPTY($action) ? $action : NULL?>">
	<input type="hidden" name="module" id="module" value="<?php echo !EMPTY($module) ? $action : NULL?>">

	<div class="form-float-label">
		<div class="row">
		  <div class="col s12">
			<div class="input-field">
				<label class="<?php echo $action == ACTION_EDIT ? 'active' :'' ?>" for="voucher_name">Voucher Name<span class="required">*</span></label>
				<input type="text" class="validate" required="" aria-required="true" name="voucher_name" id="voucher_name" value="<?php echo isset($voucher_info['voucher_name']) ? $voucher_info['voucher_name'] : NULL?>"/>
			</div>
		  </div>
		</div> 
		<div class='row switch p-md b-b-n'>
		    <label>
		        Inactive
		        <input name='active_flag' type='hidden'   value='N'>
		        <input name='active_flag' type='checkbox'   value='Y' <?php echo $action == ACTION_ADD ? 'checked' :'' ?> <?php echo ($voucher_info['active_flag'] == "Y") ? "checked" : "" ?>> 
		        <span class='lever'></span>Active
		    </label>
		</div>
	</div>
	<div class="md-footer default">
		<a class="waves-effect waves-teal btn-flat cancel_modal" id="cancel_voucher">Cancel</a>
		<button class="btn btn-success " id="save_voucher" value="<?php echo BTN_SAVE ?>"><?php echo BTN_SAVE ?></button>
	</div>
</form>
<script>
$(function (){
	$('#voucher_form').parsley();
	$('#voucher_form').submit(function(e) {
	    e.preventDefault();
	    
		if ( $(this).parsley().isValid() ) {
			var data = $(this).serialize();
		  	button_loader('save_voucher', 1);
		  	var option = {
					url  : $base_url + 'main/code_library_payroll/voucher/process',
					data : data,
					success : function(result){
						if(result.status)
						{
							notification_msg("<?php echo SUCCESS ?>", result.msg);
							$("#cancel_voucher").trigger('click');
							load_datatable('voucher_table', '<?php echo PROJECT_MAIN ?>/code_library_payroll/voucher/get_voucher_list',false,0,0,true);
						}
						else
						{
							notification_msg("<?php echo ERROR ?>", result.msg);
						}	
						
					},
					
					complete : function(jqXHR){
						button_loader('save_voucher', 0);
					}
			};

			General.ajax(option);    
	    }
  	});
})
</script>