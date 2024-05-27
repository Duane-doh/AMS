<?php 
$salt = gen_salt();
$token	= in_salt(PROJECT_NAME, $salt);

?>
<div class="center-align">
  <img src="<?php echo base_url().PATH_IMAGES ?>logo_login.png" class="logo"/>
  <h5>Becoming a Member is Easy!</h5>
  <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit</p>
</div>

<form id="sign_up_form" style="margin-top:30px;">
  <input type="hidden" name="salt" value="<?php echo $salt ?>">
  <input type="hidden" name="token" value="<?php echo $token ?>">
  <input class="none" type="password" />
  
  <div class="form-basic">
    <div class="row" style="margin-bottom:20px;">
      <div class="col s3">&nbsp;</div>
      <div class="col s2 right-align" style="padding:0;">
		<input type="radio" class="labelauty" name="gender" id="gender_male" value="<?php echo MALE ?>" checked />
      </div>
	  <div class="col s2 center-align" style="padding-top:20px;"><small style="font-weight:600;">OR</small></div>
	  <div class="col s2 left-align" style="padding:0 0 0 8px;">
		<input type="radio" class="labelauty" name="gender" id="gender_female" value="<?php echo FEMALE ?>"/>
      </div>
      <div class="col s3">&nbsp;</div>
    </div>
	
	<div class="row" style="margin-bottom:0;">
      <div class="col s1">&nbsp;</div>
	  <div class="col s4">
	    <div class="input-field">
		  <input type="text" name="lname" id="lname" data-parsley-required="true" data-parsley-validation-threshold="0" data-parsley-trigger="keyup"/>
		  <label for="lname">Last Name</label>
	    </div>
	  </div>
	  <div class="col s4">
	    <div class="input-field">
		  <input type="text" name="fname" id="fname" data-parsley-required="true" data-parsley-validation-threshold="0" data-parsley-trigger="keyup"/>
		  <label for="fname">First Name</label>
	    </div>
	  </div>
	  <div class="col s2">
	    <div class="input-field">
		  <input type="text" name="mname" id="mname"/>
		  <label for="mname">M.I.</label>
	    </div>
	  </div>
	  <div class="col s1">&nbsp;</div>
    </div>
    
	<div class="row" style="margin-bottom:0;">
      <div class="col s1">&nbsp;</div>
	  <div class="col s7">
	    <label class="label" style="display:block; margin-bottom:5px;">Organization</label>
	    <select name="org" id="org" class="selectize" placeholder="Select Organization">
		  <option value="">Select Agency</option>
		  <?php foreach ($orgs as $org): ?>
			<option value="<?php echo $org["org_code"]?>"><?php echo $org["office"]?></option>
		  <?php endforeach; ?>
	    </select>
      </div>
	  <div class="col s3" style="padding-top:8px;">
	    <div class="input-field">
		  <input type="text" name="job_title" id="job_title"/>
		  <label for="job_title">Position</label>
	    </div>
      </div>
      <div class="col s1">&nbsp;</div>
    </div>
	
	<div class="row" style="margin-bottom:0;">
      <div class="col s1">&nbsp;</div>
	  <div class="col s10">
	    <div class="input-field">
		  <input type="email" name="email" id="email" data-parsley-required="true" data-parsley-validation-threshold="0" data-parsley-type="email" data-parsley-trigger="keyup"/>
		  <label for="email">Email Address</label>
	    </div>
      </div>
      <div class="col s1">&nbsp;</div>
    </div>
	
	<div class="row">
      <div class="col s1">&nbsp;</div>
	  <div class="col s5">
	    <div class="input-field">
		  <input type="text" name="username" id="username" data-parsley-required="true" data-parsley-validation-threshold="0" data-parsley-trigger="keyup"/>
		  <label for="username">Username</label>
	    </div>
      </div>
	  <div class="col s5">
	    <div class="input-field">
	    <input type="password" name="password" 
	    id="password" 
	    data-parsley-required-message="<?php echo $pass_err;?>" 
	    data-parsley-required="true" 
	    data-parsley-validation-threshold="0"
	    data-parsley-pass=""
	    data-parsley-trigger="keyup"/>
	    
	    
		  <label for="password">Password</label>
	    </div>
      </div>
      <div class="col s1">&nbsp;</div>
    </div>
	<div class="row">
      <div class="col s1">&nbsp;</div>
      <div class="col s10">
	    <button type="submit" class="btn waves-effect waves-light" id="create_account" value="<?php echo BTN_CREATING_ACCOUNT ?>"><?php echo BTN_CREATE_ACCOUNT ?></button>
	  </div>
      <div class="col s1">&nbsp;</div>
    </div>
	
  </div>
</form>

<script type="text/javascript">
$(function(){

	window.ParsleyValidator.addValidator('pass', 
		    function (input, data_val) {
		 	var input_copy = input;
		    var input_count = input.length;
		    var upper_count = input_copy.replace(/[^A-Z]/g, "").length;
	    	var lower_count = input_copy.replace(/[^a-z]/g, "").length;
		    var digit_count = input_copy.replace(/[^0-9]/g, "").length;
	    	var symbol_count = input_copy.replace(/[^=?@#$*!]/g, "").length;
	    	
		    <?php if(intval($pass_length) > 0){?>
		   		if(input_count < parseInt(<?php echo $pass_length;?>) || upper_count < parseInt(<?php echo $upper_length;?>) || digit_count < parseInt(<?php echo $digit_length;?>) || lower_count < parseInt(<?php echo $lower_length;?>) || symbol_count < parseInt(<?php echo $symbol_length;?>)){
					return false;
		   		}
		   		return true;
		    <?php }?>
		    }).addMessage('en', 'pass', '<?php echo $pass_err;?>');
	
	$('#sign_up_form').parsley();
	
	$('#sign_up_form').submit(function(e) {
        e.preventDefault();
        if ( $(this).parsley().isValid() ) {
		  var data = $(this).serialize();
	  
		  button_loader('create_account', 1);
		  $.post("<?php echo base_url() ?>sign_up/process/", data, function(result){
			if(result.flag == 0){
				notification_msg("<?php echo ERROR ?>", result.msg);
				button_loader('create_account', 0);
			} else {
				notification_msg("<?php echo SUCCESS ?>", result.msg);
			    modalObj.closeModal();
			}
		  }, 'json');       
        }
    });

	
    
});
</script>