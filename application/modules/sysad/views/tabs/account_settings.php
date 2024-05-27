<?php 
  $salt = gen_salt();
  $token = in_salt($this->session->userdata('user_id'), $salt);
?>

<div class="row">
  <div class="col l10 m12 s12">
	<form id="account_settings_form" class="m-t-lg">
	  <input type="hidden" name="id" value="<?php echo $this->session->userdata('user_id') ?>"/>
	  <input type="hidden" name="salt" value="<?php echo $salt ?>">
	  <input type="hidden" name="token" value="<?php echo $token ?>">
	  
	  <input type="hidden" name="system_logo" id="system_logo" value="<?php echo get_setting(GENERAL, "system_logo") ?>"/>
	  <input type="hidden" name="system_favicon" id="system_favicon" value="<?php echo get_setting(GENERAL, "system_favicon") ?>"/>
	  
	  <div class="form-basic">
		<div id="account" class="scrollspy table-display input-field white box-shadow">
		  <div class="table-cell bg-dark p-lg valign-top" style="width:25%">
			<label class="label mute">Account</label>
			<p class="caption m-t-sm white-text">Control the methods of adding new users to LGU 360.</p>
		  </div>
		  <div class="table-cell p-lg valign-top">
			<div class="row m-b-n">
			  <div class="col s12">
				<div class="p-b-md">
				  <h6>Who can register accounts?</h6>
				  <div class="help-text">Allow the users to create an account for themselves or require only the administrator to manually create all the user accounts in the site.</div>
				</div>
				
				<div class="row">
				  <div class="col l6 m6 s12">
					<input type="radio" class="labelauty" checked name="account_creator" id="account_administrator" value="<?php echo ADMINISTRATOR ?>" data-labelauty="Only the administrator can register accounts"/>
				  </div>
				  <div class="col l6 m6 s12">
					<input type="radio" class="labelauty" name="account_creator" id="account_visitor" value="<?php echo VISITOR ?>" data-labelauty="Visitors can self-register with admin approval"/>
				  </div>
				</div>				
			  </div>
			</div>
		  </div>
		</div>
		
		<div id="login_security" class="scrollspy table-display m-t-lg input-field white box-shadow">
		  <div class="table-cell bg-dark p-lg valign-top" style="width:25%">
			<label class="label mute">Login</label>
			<p class="caption m-t-sm white-text">Login using your email or username and limit the number of invalid login attempts to protect the system from such attacks.</p>
		  </div>
		  <div class="table-cell p-lg valign-top">
			<div class="row m-b-n">
			  <div class="col s9">
				<div class="p-b-md">
				  <h6>Login Attempts</h6>
				  <div class="help-text">The maximum number of login failures a user is allowed before blocking their account from the site.</div>
				</div>
			  </div>
			  <div class="col s3 p-t-lg">
				<input type="text" name="login_attempts" id="login_attempts" value="<?php echo get_setting(LOGIN, "login_attempts") ?>" />
			  </div>
			</div>
				
			<div class="p-b-md p-t-md">
			  <h6>Login via</h6>
			  <div class="help-text">Users can use either their email address or their username when logging in.</div>
			  
			  <div class="row">
				<div class="col s6">
				  <input type="radio" class="labelauty" checked name="login_via" id="login_via_username" value="<?php echo VIA_USERNAME ?>" data-labelauty="Never login using username|Login via username"/>
				  <input type="radio" class="labelauty" checked name="login_via" id="login_via_email" value="<?php echo VIA_EMAIL ?>" data-labelauty="Never login using email address|Login via email address"/>
				  <input type="radio" class="labelauty" checked name="login_via" id="login_via_username_email" value="<?php echo VIA_USERNAME_EMAIL ?>" data-labelauty="Never login using username & email|Login via username & email address"/>
				</div>
			  </div>
			</div>
		  </div>
		</div>
		
		<div id="password" class="scrollspy table-display m-t-lg input-field white box-shadow">
		  <div>
		    <div class="table-cell bg-dark p-lg valign-top" style="width:25%">
			  <label class="label mute">Password</label>
			  <p class="caption m-t-sm white-text">Configure user password expiration and set constraints to meet specific security regulations.</p>
		    </div>
		    
			<div class="table-cell p-lg valign-top">
			  <div class="row">
			    <div class="col s12">
				  <div class="p-b-lg">
				    <h6>Constraints</h6>
				    <div class="help-text">Enforce restrictions on user passwords by defining password policies before a user password change will be accepted.</div>
				  </div>
				
				  <div class="row p-md p-b-n m-b-n bg-light-blue">
				    <div class="col s9">
				      <label class="label m-b-sm">Uppercase</label>
				      <div class="help-text">Password must contain the specified minimum number of uppercase letters.</div>
				    </div>
				    <div class="col s3">
				      <input id="constraint_uppercase" name="constraint_uppercase" type="text" class="validate bg-white" value="<?php echo get_setting(PASSWORD_CONSTRAINTS, "constraint_uppercase") ?>"/>
				    </div>
				  </div>
				  <div class="row p-md p-b-n m-b-n bg-light-blue">
				    <div class="col s9">
				      <label class="label m-b-sm">Lowercase</label>
				      <div class="help-text">Password must contain the specified minimum number of lowercase letters.</div>
				    </div>
				    <div class="col s3">
				      <input id="constraint_lowercase" name="constraint_lowercase" type="text" class="validate bg-white" value="<?php echo get_setting(PASSWORD_CONSTRAINTS, "constraint_lowercase") ?>"/>
				    </div>
				  </div>
				  <div class="row p-md p-b-n m-b-n">
				    <div class="col s9">
				      <label class="label m-b-sm">Digit</label>
				      <div class="help-text">Password must contain the specified minimum number of digits.</div>
				    </div>
				    <div class="col s3">
				      <input class="validate" id="constraint_digit" data-parsley-required="true" data-parsley-validation-threshold="0" data-parsley-type="integer" data-parsley-trigger="keyup" name="constraint_digit" type="text" value="<?php echo get_setting(PASSWORD_CONSTRAINTS, "constraint_digit") ?>"/>
				    </div>
				  </div>
				  <div class="row p-md p-b-n m-b-n">
				    <div class="col s9">
				      <label class="label m-b-sm">Any of these symbols (= ? < > @ # $ * !)</label>
				      <div class="help-text">Password must contain the specified minimum number of symbols.</div>
				    </div>
				    <div class="col s3">
				      <input class="validate" id="constraint_symbols" data-parsley-required="true" data-parsley-validation-threshold="0" data-parsley-type="integer" data-parsley-trigger="keyup" name="constraint_symbols" type="text" value="<?php echo get_setting(PASSWORD_CONSTRAINTS, "constraint_symbols") ?>"/>
				    </div>
				  </div>
				  <div class="row p-md p-b-n m-b-n bg-light-blue">
				    <div class="col s9">
				      <label class="label m-b-sm">Length</label>
				      <div class="help-text">Password length must be equal to or longer than the specified minimum length.</div>
				    </div>
				    <div class="col s3">
				      <input id="constraint_length" name="constraint_length" type="text" class="validate bg-white" value="<?php echo get_setting(PASSWORD_CONSTRAINTS, "constraint_length") ?>"/>
				    </div>
				  </div>
				  <div class="row p-md p-b-n m-b-n">
				    <div class="col s9">
				      <label class="label m-b-sm">History</label>
				      <div class="help-text">Password must not match any of the user's previous <small class="font-bold text-underline">X</small> passwords.</div>
				    </div>
				    <div class="col s3">
				      <input id="constraint_history" name="constraint_history" type="text" class="validate" value="<?php echo get_setting(PASSWORD_CONSTRAINTS, "constraint_history") ?>"/>
				    </div>
				  </div>
				
				  <div class="p-b-md m-t-lg p-t-sm">
				    <h6>Password Expiry Settings</h6>
				    <div class="help-text">Password expiration is disabled by default. It must be enabled to force password expiration periodically.</div>
				  </div>
				
				  <div class="row p-md p-b-n m-b-n bg-light-blue">
				    <div class="col s9">
				      <label class="label m-b-sm">Enable Password Expiration</label>
				      <div class="help-text">Allow passwords to expire after a specified time.</div>
				    </div>
				    <div class="col s3">
				      <input type="checkbox" class="labelauty" name="password_expiry" id="password_expiry" value="1" data-labelauty="Disabled|Enabled" onclick="toggle('password_expiry', 'password_expiry_duration')"/>
				    </div>
				  </div>
				
				  <div id="password_expiry_duration" style="display:none">
				    <div class="row p-md p-b-n m-b-n">
				      <div class="col s9">
				        <label class="label m-b-sm">Duration</label>
				        <div class="help-text">Length of time for which a password is valid.</div>
				      </div>
				      <div class="col s2">
 				        <input type="text" name="password_duration" id="password_duration" value="<?php echo get_setting(PASSWORD_EXPIRY, "password_duration") ?>"/>
				      </div>
				      <div class="col s1 p-n p-t-md">
				        <span class="font-bold">days</span>
				      </div>
				    </div>
				    <div class="row p-md p-b-n m-b-n bg-light-blue">
				      <div class="col s9">
				        <label class="label m-b-sm">Reminder</label>
				        <div class="help-text">Notifications will be sent out <small class="font-bold text-underline">X</small> days before the expiration of password. Leaving this field empty won't send any reminders to the user.</div>
				      </div>
				      <div class="col s2">
				        <input type="text" name="password_reminder" id="password_reminder" value="<?php echo get_setting(PASSWORD_EXPIRY, "password_reminder") ?>"/>
				      </div>
				      <div class="col s1 p-n p-t-md">
				        <span class="font-bold">days</span>
				      </div>
				    </div>
				  </div>
			    </div>
			  </div>
		    </div>
		  </div>
		
		  <div class="panel-footer right-align">
		    <div class="input-field inline m-n">
			  <button class="btn  bg-success" type="submit" id="save_account_settings" value="<?php echo BTN_SAVING ?>"><?php echo BTN_SAVE ?></button>
		    </div>
		  </div>
		</div>
	  </div>
	</form>
  </div>
  
  <div class="col l2 hide-on-med-and-down">
	<div class="pinned m-t-lg">
	  <ul class="section table-of-contents">
		<li><a href="#account">Account</a></li>
		<li><a href="#login_security">Login Security</a></li>
		<li><a href="#password">Password</a></li>
	  </ul>
	</div>
  </div>
</div>

<script>
$(function(){
	$("#account_<?php echo strtolower(get_setting(ACCOUNT, "account_creator")) ?>").prop("checked", true);
	$("#login_via_<?php echo strtolower(get_setting(LOGIN, "login_via")) ?>").prop("checked", true);

	<?php if(get_setting(PASSWORD_EXPIRY, "password_expiry") == 1){ ?>
		$("#password_expiry").prop("checked", true);
	<?php } ?>
	
	toggle('password_expiry', 'password_expiry_duration');
	
	$('#account_settings_form').parsley();
	
	$('#account_settings_form').submit(function(e) {
        e.preventDefault();
        if ( $(this).parsley().isValid() ) {
		  var data = $(this).serialize();
	  
		  button_loader('save_account_settings', 1);
		  $.post("<?php echo base_url() . PROJECT_CORE ?>/account_settings/process", data, function(result){
			Materialize.toast(result.msg, 3000, '', function(){
			  button_loader('save_account_settings', 0);
			  location.reload(); 
			});
		  }, 'json');       
        }
    });
});

function toggle(id, content_id){
  if($("#" + id).is(':checked')){
    $('#' + content_id).fadeIn('slow').show();
  } else {
    $('#' + content_id).fadeOut('slow').hide();
  }
}
</script>