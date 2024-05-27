<form id="add_computation_table_form">
	<input type="hidden" name="id" id="id" value="<?php echo !EMPTY($id) ? $id : NULL?>">
	<input type="hidden" name="salt" id="salt" value="<?php echo !EMPTY($salt) ? $salt : NULL?>">
	<input type="hidden" name="token" id="token" value="<?php echo !EMPTY($token) ? $token : NULL?>">
	<input type="hidden" name="action" id="action" value="<?php echo !EMPTY($action) ? $action : NULL?>">
	<input type="hidden" name="computation_table_id" id="computation_table_id" value="<?php echo !EMPTY($computation_table_id) ? $computation_table_id : NULL?>">
	<div class="form-float-label">
		<div class="row">
			<div class="col s12">
				<div class="input-field">
					<label class="<?php echo $action == ACTION_ADD ? 'active' :'' ?>" for="effectivity_date">Effective Date<span class="required">*</span></label>
					<input type="text" class="datepicker" required name="effectivity_date" id="effectivity_date" />
				</div>
			</div>
			</div>
		<div class="row">
			<div class="col s12">
				<div class="input-field">
					<label class="<?php echo $action == ACTION_ADD ? 'active' :'' ?>" for="type_name">Type Name<span class="required">*</span></label>
					<input type="text" class="validate" required name="type_name" id="type_name"  />
				</div>
			</div>
			</div>
		<div class="row">
			<div class="col s12">
				<div class="input-field">
					<label class="<?php echo $action == ACTION_ADD ? 'active' :'' ?>" for="num_of_details">Number of fields<span class="required">*</span></label>
					<input type="text" class="validate" required name="num_of_details" id="num_of_details" />
				</div>
			</div>
		</div>


	<div class="md-footer default">
	  	<?php if($action != ACTION_VIEW):?>
	  		<a class="waves-effect waves-teal btn-flat cancel_modal">Cancel</a>
		    <button class="btn btn-success ">SAVE</button>
	  		
	  	<?php endif; ?>
	</div>
</form>

<script>


$(function (){
	$('#add_computation_table_form').parsley();
	$('#add_computation_table_form').submit(function(e) {
	    e.preventDefault();
	    
		if ( $(this).parsley().isValid() ) {
			var data = $(this).serialize();
		  	button_loader('save_day', 1);
		  	var option = {
					url  : $base_url + 'main/code_library_ta/computation_table/add_details',
					data : data,
					success : function(result){
						if(result.status)
						{
							notification_msg("<?php echo SUCCESS ?>", result.msg);
							modal_add_computation_table_detail.closeModal();

							}
						else
						{
							notification_msg("<?php echo ERROR ?>", result.msg);
						}	
						
					},
					
					complete : function(jqXHR){
						button_loader('save_day', 0);
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