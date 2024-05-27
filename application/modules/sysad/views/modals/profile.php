<?php 
$id	= (!EMPTY($user["user_id"]))? $user["user_id"] : "";
$lname = (!EMPTY($user["lname"]))? $user["lname"] : "";
$fname = (!EMPTY($user["fname"]))? $user["fname"] : "";
$mname = (!EMPTY($user["mname"]))? $user["mname"] : "";
$nickname = (!EMPTY($user["nickname"]))? $user["nickname"] : "";
$female = ($user["gender"] == FEMALE)? "checked" : "";
$male = ($user["gender"] == MALE)? "checked" : "";
$email = (!EMPTY($user["email"]))? $user["email"] : "";
$job_title = (!EMPTY($user["job_title"]))? $user["job_title"] : "";
$contact_no = (!EMPTY($user["contact_no"]))? $user["contact_no"] : "";
$mobile_no = (!EMPTY($user["mobile_no"]))? $user["mobile_no"] : "";
//$username = (!EMPTY($user["username"]))? $user["username"] : "";
$photo = (!EMPTY($user["photo"]))? $user["photo"] : "";
$img_src = (!EMPTY($user["photo"]))? PATH_USER_UPLOADS . $user["photo"] : PATH_IMAGES . "avatar.jpg";
$status = $user["status_id"];
$org	= $org["org_code"];
$org_name = $user["org_name"];

$pw_note = "Type in a new password below to reset / change current password.";

$salt = gen_salt();
$token	= in_salt($id, $salt);
?>
<form id="profile_form">
  <div class="table-display">
    <div class="table-cell valign-top profile-banner" style="width:30%;">
	  <div class="avatar">
	    <div class="avatar-wrapper">
		  <img id="profile_img" src="<?php echo base_url() . PATH_USER_UPLOADS . $this->session->photo ?>" />
		  <a href="#" id="profile_photo" class="m-r-sm">Edit Photo</a>
	    </div>
	  </div>
	  <div class="profile-detail center-align">
	    <h5><?php echo $this->session->name ?></h5>
	  </div>
	  <div class="profile-detail">
	  	<p>Username: <?php echo $user['username']; ?></p>
	  	<p>Roles:</p>
	    <ul>
	    <?php foreach ($roles as $role):?>
	  		<li style="color: white; text-align: left;"><?php echo $role['role_name'];?></li>
	  	<?php endforeach;?>
	  	</ul>
	  </div>
    </div>
    <div class="table-cell valign-top" style="width:60%;">
	  <input type="hidden" name="user_id" value="<?php echo $id ?>">
	  <input type="hidden" name="salt" value="<?php echo $salt ?>">
	  <input type="hidden" name="token" value="<?php echo $token ?>">
	  <input type="hidden" name="status" value="<?php echo $status ?>">
	  <input type="hidden" name="image" id="user_image" value="<?php echo $photo ?>"/>
	  <input type="hidden" name="organization" id="organization" value="<?php echo $org ?>"/>
	  
	  <div class="form-float-label">
		<div class="row m-n">
		  <div class="col s4">
			<div class="input-field">
			  <input type="text" data-parsley-required="true" data-parsley-validation-threshold="0" data-parsley-trigger="keyup" name="lname" id="lname" value="<?php echo $lname ?>"/>
			  <label for="lname">Last Name</label>
			</div>
		  </div>
		  <div class="col s5">
			<div class="input-field">
			  <input type="text" data-parsley-required="true" data-parsley-validation-threshold="0" data-parsley-trigger="keyup" name="fname" id="fname" value="<?php echo $fname ?>"/>
			  <label for="fname">First Name</label>
			</div>
		  </div>
		  <div class="col s3">
			<div class="input-field">
			  <input type="text" name="mname" id="mname" value="<?php echo $mname ?>"/>
			  <label for="mname">Middle Initial</label>
			</div>
		  </div>
		</div>
		
		<div class="row m-n">
		  <div class="col s4">
			<div class="input-field p-b-lg">
			  <input type="text" name="nickname" id="nickname" value="<?php echo $nickname ?>"/>
			  <label for="lname m-b-md">Nickname</label>
			</div>
		  </div>
		  <div class="col s8 p-t-md">
			<div class="row m-b-n">
			  <div class="col s6 p-l-n">
				<label for="profile_gender_male" class="active m-b-sm block">Gender</label>
				<input type="radio" class="labelauty" name="gender" id="profile_gender_male" value="<?php echo MALE ?>" data-labelauty="Male" <?php echo $male ?>/>
			  </div>
			  <div class="col s6">
				<label class=" m-b-sm block">&nbsp;</label>
				<input type="radio" class="labelauty" name="gender" id="profile_gender_female" value="<?php echo FEMALE ?>" data-labelauty="Female" <?php echo $female ?>/>
			  </div>
			</div>
		  </div>   
		</div>
		
		<div class="row m-n">
		  <div class="col s6">
			<div class="input-field">
			  <input type="text" name="contact_no" id="contact_no" value="<?php echo $contact_no ?>"/>
			  <label for="contact_no">Telephone No.</label>
			</div>
		  </div>
		  <div class="col s6">
			<div class="input-field">
			  <input type="text" name="mobile_no" id="mobile_no" value="<?php echo $mobile_no ?>"/>
			  <label for="mobile_no">Mobile No.</label>
			</div>
		  </div>
		</div>
		
		<div class="row m-n">
		  <div class="col s7 p-t-md">
			<div class="input-field">
				<div class="input-field">
				  <input type="text" name="org_name" id="org_name" value="<?php echo $org_name ?>" readonly="readonly"/>
				  <label for="job_title">Department/Agency</label>
				</div>
			</div>
		  </div>
		  <div class="col s5">
			<div class="input-field">
			  <input type="text" name="job_title" id="job_title" value="<?php echo $job_title ?>" readonly="readonly"/>
			  <label for="job_title">Job Title</label>
			</div>
		  </div>
		</div>
		
		<div class="row m-n">
		  <div class="col s12">
			<div class="input-field">
			  <input type="email" name="email" id="email" data-parsley-required="true" data-parsley-validation-threshold="0" data-parsley-type="email" data-parsley-trigger="keyup" value="<?php echo $email ?>"/>
			  <label for="email">Email Address</label>
			</div>
		  </div>
		</div>
		
		<div class="row m-n">
		  <div class="col s4">
			<div class="input-field">
			  <input type="password" name="current_password" id="current_password" <?php if(!ISSET($user)){ ?> class="validate" required="" aria-required="true" <?php } ?>/>
			  <label for="current_password">Current Password</label>
			</div>
		  </div>
		  <div class="col s4">
			<div class="input-field">
			  <input type="password" name="password" disabled id="new_password" 
			  data-parsley-trigger="keyup" 
			   data-parsley-pass=""
			  />
			  <label for="password">New Password</label>
			</div>
		  </div>
		  <div class="col s4">
			<div class="input-field">
			  <input type="password" name="confirm_password" disabled id="confirm_password" 
			  data-parsley-equalto="#new_password" 
			  data-parsley-trigger="keyup" 
			  data-parsley-pass=""
			  />
			  <label for="confirm_password">Confirm Password</label>
			</div>
		  </div>
		</div>
	  </div>
    </div>
  </div>
  <div class="md-footer default">
	<a class="waves-effect waves-teal btn-flat" id="cancel_profile">Cancel</a>
	<?php //if($this->permission->check_permission(MODULE_ROLE, ACTION_SAVE)):?>
	<button type="submit" class="btn " id="save_profile" value="<?php echo BTN_SAVE ?>"><?php echo BTN_SAVE ?></button>
	<?php //endif; ?>
  </div>
</form>

<script type="text/javascript">
$(function(){
  <?php if(ISSET($user)){ ?>
	$('.input-field label').addClass('active');
  <?php } ?>
  
  $("#cancel_profile").on("click", function(){
	profilemodalObj.closeModal();
  });
  
  $("input:disabled").closest(".col").addClass("disabled");
  
  $('#current_password').blur(function() {
	var password = $(this).val();
	var data = {password:password};
	if(password != ''){
	  $(this).addClass('loading');
	  
	  $.get("<?php echo base_url() . PROJECT_CORE ?>/profile/validate_password", data, function(result){
		if(result.flag == 0){
		  $("#new_password, #confirm_password").closest(".col").addClass("disabled");
		  $('#new_password, #confirm_password, #save_profile').prop('disabled',true);
		  $('#current_password').addClass('error-loading').removeClass('loading success-loading');
		}else{
		  $("#new_password, #confirm_password").closest(".col").removeClass("disabled");
		  $('#new_password, #confirm_password, #save_profile').prop('disabled',false);
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
  
  $('#profile_form').parsley();
  $('#profile_form').submit(function(e) {
    e.preventDefault();
    
	if ( $(this).parsley().isValid() ) {
	  var data = $(this).serialize();
	  
	  button_loader('save_profile', 1);
	  $.post("<?php echo base_url() . PROJECT_CORE ?>/profile/process/", data, function(result) {
		if(result.flag == 0){
		  notification_msg("<?php echo ERROR ?>", result.msg);
		  button_loader('save_profile', 0);
		} else {
		  notification_msg("<?php echo SUCCESS ?>", result.msg);
		  button_loader("save_profile",0);
		  
		  $("#dropdown-account .account-name a").text(result.name);
		  $("#dropdown-account .account-name small").text(result.job_title);
		  
		  var avatar = (result.image != "") ? "<?php echo base_url() . PATH_USER_UPLOADS?>" + result.image : "<?php echo base_url() . PATH_IMAGES ?>avatar.jpg";
		  
		  $("#top_bar_avatar").attr("src", avatar);
		  
		  $("#modal_profile").removeClass("md-show");
		}
	  }, 'json');       
    }
  });
  
  	var uploadObj = $("#profile_photo").uploadFile({
		url: $base_url + "upload/",
		fileName: "file",
		allowedTypes:"jpeg,jpg,png,gif",
		acceptFiles:"*",	
		dragDrop:false,
        multiple: false,
		maxFileCount: 1,
		allowDuplicates: true,
		duplicateStrict: false,
        showDone: false,
		showAbort: false,
        showProgress: false,
		showPreview: false,
		returnType:"json",	
		formData: {"dir":"<?php echo PATH_USER_UPLOADS; ?>"},		
		uploadFolder:$base_url + "<?php echo PATH_USER_UPLOADS; ?>",
		onSelect: function(files){
			$(".avatar-wrapper .ajax-file-upload").hide();
		},
		onSuccess:function(files,data,xhr){ 
			var avatar = $base_url + "<?php echo PATH_USER_UPLOADS?>" + data;
			$("#profile_img").attr("src", avatar);
			
			$('#user_image').val((data));
			$('.avatar-wrapper .ajax-file-upload-progress').hide();
			$(".avatar-wrapper .ajax-file-upload-red").html("<i class='flaticon-recycle69'></i>");
		},
		showDelete:true,
		deleteCallback: function(data,pd)
		{
			for(var i=0;i<data.length;i++)
			{
				$.post($base_url + "upload/delete/",{op:"delete",name:data[i],dir:"<?php echo PATH_USER_UPLOADS; ?>"},
				function(resp, textStatus, jqXHR)
				{ 
					$(".avatar-wrapper .ajax-file-upload-error").fadeOut();	
					$('#user_image').val(''); 
					var avatar = $base_url + "<?php echo PATH_IMAGES?>template_doh_images/avatar.jpg";
					$("#profile_img").attr("src", avatar);
				});
			 }      
			pd.statusbar.hide();
			$(".avatar-wrapper .ajax-file-upload").css("display","");
		},
		onLoad:function(obj)
		{
			$.ajax({
				cache: true,
				url: $base_url + "upload/existing_files/",
				dataType: "json",
				data: { dir: '<?php echo PATH_USER_UPLOADS ?>', file: $('#user_image').val()} ,
				success: function(data) 
				{
					for(var i=0;i<data.length;i++)
					{
						obj.createProgress(data[i]);
					}	
					
					if(data.length > 0){
					  $(".avatar-wrapper .ajax-file-upload").hide();
					  $('.avatar-wrapper .ajax-file-upload-progress').hide();
					  $(".avatar-wrapper .ajax-file-upload-red").html("<i class='flaticon-recycle69'></i>");
					}else{
					  var avatar = $base_url + "<?php echo PATH_IMAGES?>template_doh_images/avatar.jpg";
					  $("#profile_img").attr("src", avatar);
					}
				}
			});
		}
	});	
});
</script>