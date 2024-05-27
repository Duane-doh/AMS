<form id="forgot_password_form" class="default p-lg">
	<input type="hidden" name="msg_post_as" id="msg_post_as" value="Send Message"/>
	<input type="hidden" name="msg_recipient[]" id="msg_recipient" value=""/>
	
	<div class="password-box">Enter the email registered with your account and we'll send you the link where you can change your password.</div>
	<div class="p m-t-sm">
	  <div class="form-float-label">
	  	<div class="row m-n">
		    	<div class="col s12">
			  		<div class="input-field">
			    	<label for="email">Email</label>
	   		 		<input type="text" name="email" id="email" data-parsley-required="true" data-parsley-maxlength="255" data-parsley-type="email" data-parsley-trigger="keyup"/>
	 				</div>
	 			</div>
	 		</div>
	  </div>			  
	  <div class="text-right m-t-md pull-right">
		<a class="waves-effect waves-teal btn-flat cancel_modal">Cancel</a>
		<button type="submit" class="btn red" id="forgot_password_btn" name="forgot_password_btn" value="<?php echo BTN_EMAILING ?>">Reset Password</button></div>
	</div>
	
</form>

<script>
$(function(){
	
	var $base_url = "<?php echo base_url() ?>";
		$forgot_password = $("#forgot_password_form");

	$forgot_password.parsley().subscribe('parsley:form:success', function (formInstance) {
		formInstance.submitEvent.preventDefault();
		
		var data = $forgot_password.serialize();
		button_loader("forgot_password_btn", 1);
		$.post($base_url + "forgot_password/request_reset/", data, function(result) {
			if(result.flag == 1){
				$("#modal_forgot_password").removeClass("md-show");
				notification_msg("<?php echo SUCCESS ?>", result.msg);
			} else {
				notification_msg("<?php echo ERROR ?>", result.msg);
				button_loader("forgot_password_btn", 0);
			}					  				
		}, 'json');
	});
	$(".cancel_modal").on("click", function(){
      $('#modal_forgat_password').removeClass("md-show");
    });
		
});
</script>