<form id="request_certificate_form">
	<input type="hidden" name="id" id="id" value="<?php echo !EMPTY($id) ? $id : NULL?>">
	<input type="hidden" name="salt" id="salt" value="<?php echo !EMPTY($salt) ? $salt : NULL?>">
	<input type="hidden" name="token" id="token" value="<?php echo !EMPTY($token) ? $token : NULL?>">
	<input type="hidden" name="action" id="action" value="<?php echo !EMPTY($action) ? $action : NULL?>">
	<input type="hidden" name="module" id="module" value="<?php echo !EMPTY($module) ? $action : NULL?>">

<div class="form-float-label p-r-md">
	<div class="row b-t b-light-gray">
	  <div class="col s12">
		<div class="input-field">
		  	<label for="cert_type" class="active">Certification For <span class="required">*</span></label>
			 <select id="cert_type" name="cert_type" <?php echo $action==ACTION_VIEW ? 'disabled' : '' ?> class="selectize required " placeholder="Select Request">
				<option value="">Select Request Certificate Type</option>
				 <?php if (!EMPTY($certificate_type_list)): ?>
					<?php foreach ($certificate_type_list as $cert_type): ?>
						<option value="<?php echo $cert_type['request_sub_type_id'] ?>"><?php echo strtoupper($cert_type['request_sub_type_name']) ?></option>
					<?php endforeach;?>
				<?php endif;?>
			 </select>
		</div>
	  </div>
	  
	</div>
	<div class="row m-n">
	  <div class="col s12">
		<div class="input-field">
			<label for="purpose">Purpose<span class="required">*</span></label>
			<textarea class="materialize-textarea" name="purpose" ></textarea>
		</div>
	  </div>
	</div>

</div>
<div class="right-align m-b-sm p-r-sm p-t-md">
		<a class="waves-effect waves-teal btn-flat" id="cancel_request">CANCEL</a>
	<?php //IF ($action_id != ACTION_VIEW): ?>
		<button class="btn btn-success" id="submit_request" type="submit" name="action">SUBMIT</button>
	<?php //ENDIF; ?>
</div>
</form>
<script>
$(function (){
	$('#request_certificate_form').parsley();
	$('#request_certificate_form').submit(function(e) {
	    e.preventDefault();
	    
		if ( $(this).parsley().isValid() ) {
			var data = $(this).serialize();
		  	button_loader('submit_request', 1);
		  	var option = {
					url  : $base_url + 'main/compensation/process_certificate_request',
					data : data,
					success : function(result){
						if(result.status)
						{
							notification_msg("<?php echo SUCCESS ?>", result.message);
							$("#cancel_request").trigger('click');
						}
						else
						{
							notification_msg("<?php echo ERROR ?>", result.message);
						}	
						
					},
					
					complete : function(jqXHR){
						button_loader('submit_request', 0);
					}
			};

			General.ajax(option);    
	    }
  	});
})
</script>