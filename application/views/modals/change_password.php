<form id="change_password_form" class="default p-lg">
	<input type="hidden" name="username" id="username" value=""/>
	<input type="hidden" name="pass_hist" id="pass_hist" value="<?php echo $pass_hist ?>"/>
	
	<div class="password-box">Your password has been expired. Enter new password to continue...</div>
	<div class="p m-t-sm">
	  <div class="form-float-label">
	  	<div class="row m-n">
	    	<div class="col s12">
		  		<div class="input-field">
		    	<label for="current_password">Current Password</label>
   		 		<input type="password" 
   		 			name="current_password" 
   		 			id="current_password"/>
 				</div>
 			</div>
 		</div>
	  	<div class="row m-n">
	    	<div class="col s12">
		  		<div class="input-field">
		    	<label for="new_password">New Password</label>
   		 		<input type="password" 
   		 			name="new_password" 
   		 			id="new_password"
			  		data-parsley-trigger="keyup" 
			  		data-parsley-pass=""/>
 				</div>
 			</div>
 		</div>
	  	<div class="row m-n">
	    	<div class="col s12">
		  		<div class="input-field">
		    	<label for="repeat_new_password">Repeat New Password</label>
   		 		<input type="password" 
   		 			name="confirm_password" 
   		 			id="confirm_password" 
   		 			data-parsley-equalto="#new_password" 
			  		data-parsley-trigger="keyup" 
			  		data-parsley-pass=""/>
 				</div>
 			</div>
 		</div>
	  </div>	
	</div>		  
	<div class="text-right m-t-md pull-right">
		<a class="waves-effect waves-teal btn-flat cancel_modal">Cancel</a>
	  	<button type="submit" class="btn" id="change_password_btn" name="change_password_btn" value="<?php echo BTN_UPDATING ?>">Change Password</button>
	</div>
	
</form>

<script>
$(function(){
	var $base_url = $("#base_url").val();
		$home_page = $("#home_page").val();

	$('#username').val($('#icon_username').val());
	$('#current_password').blur(function() {
	var password = $(this).val();
	var username = $('#username').val();
	var data = {password:password, username:username};
	if(password != ''){
	  $(this).addClass('loading');
	  
	  $.get("<?php echo base_url()?>change_password/validate_password", data, function(result){
		if(result.flag == 0){
		  $("#new_password, #confirm_password").closest(".col").addClass("disabled");
		  $('#new_password, #confirm_password, #change_password_btn').prop('disabled',true);
		  $('#current_password').addClass('error-loading').removeClass('loading success-loading');
		}else{
		  $("#new_password, #confirm_password").closest(".col").removeClass("disabled");
		  $('#new_password, #confirm_password, #change_password_btn').prop('disabled',false);
		  $('#current_password').addClass('success-loading').removeClass('loading error-loading');
		}
	  }, 'json');
	  
	  
	}else{
		$(this).removeClass('loading error-loading success-loading');
		$('#save_profile').prop('disabled',false);
	}
	
  });

	window.ParsleyValidator.addValidator('pass', 
	    function (input, data_val) {
	 	var input_copy = input;
	    var input_count = input.length;
	    var upper_count = input_copy.replace(/[^A-Z]/g, "").length;
	    var lower_count = input_copy.replace(/[^a-z]/g, "").length;
	    var digit_count = input_copy.replace(/[^0-9]/g, "").length;
	    var symbol_count = input_copy.replace(/[^=?@#$*!]/g, "").length;
	    <?php if(intval($pass_length) > 0){?>

			if(input_count < parseInt(<?php echo $pass_length;?>) && parseInt(<?php echo $pass_length;?>) != 0){
				return false;
	   		}

	   		if(upper_count < parseInt(<?php echo $upper_length;?>) && parseInt(<?php echo $upper_length;?>) != 0){
		   		return false;
	   		}

	   		if(digit_count < parseInt(<?php echo $digit_length;?>) && parseInt(<?php echo $digit_length;?>) != 0){
				return false;
	   		}

	   		if(lower_count < parseInt(<?php echo $lower_length;?>) && parseInt(<?php echo $lower_length;?>) != 0){
				return false;
	   		}

	   		if(symbol_count < parseInt(<?php echo $symbol_length;?>) && parseInt(<?php echo $symbol_length;?>) != 0){
				return false;
	   		}
	   		
	    <?php }?>
	    return true;
    }).addMessage('en', 'pass', '<?php echo $pass_err;?>');

	var $base_url = "<?php echo base_url() ?>";
		$change_password = $("#change_password_form");

	$change_password.parsley().subscribe('parsley:form:success', function (formInstance) {
		formInstance.submitEvent.preventDefault();
		
		var data = $change_password.serialize();
		button_loader("change_password_btn", 1);
		$.post($base_url + "change_password/process", data, function(result) {
			if(result.flag == 1){
				$("#modal_change_password").removeClass("md-show");
				notification_msg("<?php echo SUCCESS ?>", result.msg);

			} else {
				notification_msg("<?php echo ERROR ?>", result.msg);
				button_loader("change_password_btn", 0);
			}					  				
		}, 'json');
	});
	$(".cancel_modal").on("click", function(){
      $('#modal_change_password').removeClass("md-show");
    });
});
</script>