<?php 
$id            = "";
$lname         = "";
$fname         = "";
$mname         = "";
$suffix        = "";// davcorrea : add suffix 
$nickname      = "";
$female        = "checked";
$male          = "";
$email         = "";
$job_title     = "";
$contact_no    = "";
$mobile_no     = "";
$username      = "";
$photo         = "";
$pw_note       = "";
$status        = "checked";
$cancel        = "";
$send          = "checked";
$page_title    = "Add New User";
$page_subtitle = ""; // Create a new user account
//===============NCOCAMPO: ADD DTR_AO, LEAVE_AO AND IMM_SUP TO USER_MGMT: START 11/15/2023===============
$dtr_ao				 = "";
$leave_ao				 = ""; 
$imm_sup				 = "";
//===============NCOCAMPO: ADD DTR_AO, LEAVE_AO AND IMM_SUP TO USER_MGMT: END 11/15/2023===============

if(ISSET($user)){
	$page_title    = "Edit User";
	$page_subtitle = "Update user account information";
	
	$id            = (!EMPTY($user["user_id"]))? $user["user_id"] : "";
	$lname         = (!EMPTY($user["lname"]))? $user["lname"] : "";
	$fname         = (!EMPTY($user["fname"]))? $user["fname"] : "";
	$mname         = (!EMPTY($user["mname"]))? $user["mname"] : "";
	$suffix         = (!EMPTY($user["suffix"]))? $user["suffix"] : ""; // davcorrea : add suffix 
	$nickname      = (!EMPTY($user["nickname"]))? $user["nickname"] : "";
	$female        = ($user["gender"] == FEMALE)? "checked" : "";
	$male          = ($user["gender"] == MALE)? "checked" : "";
	$email         = (!EMPTY($user["email"]))? $user["email"] : "";
	$job_title     = (!EMPTY($user["job_title"]))? $user["job_title"] : "";
	$contact_no    = (!EMPTY($user["contact_no"]))? $user["contact_no"] : "";
	$mobile_no     = (!EMPTY($user["mobile_no"]))? $user["mobile_no"] : "";
	$username      = (!EMPTY($user["username"]))? $user["username"] : "";
//===============NCOCAMPO: ADD DTR_AO, LEAVE_AO AND IMM_SUP TO USER_MGMT: START 11/15/2023===============
	$dtr_ao     	 = (!EMPTY($user["dtr_ao"]))? $user["dtr_ao"] : "";
	$leave_ao     	 = (!EMPTY($user["leave_ao"]))? $user["leave_ao"] : "";
	$imm_sup     	 = (!EMPTY($user["imm_sup"]))? $user["imm_sup"] : "";
//===============NCOCAMPO: ADD DTR_AO, LEAVE_AO AND IMM_SUP TO USER_MGMT: START 11/15/2023===============
	$photo         = (!EMPTY($user["photo"]))? $user["photo"] : "";
	$status        = ($user["status_id"] == ACTIVE)? "checked" : "";
	$status_label  = ($user["status_id"] == BLOCKED)? "Blocked" : "Inactive";
	$pw_note       = "Type in a new password below to reset / change current password.";
	$send          = $cancel = "";
}

$salt  = gen_salt();
$token = in_salt($id, $salt);
?>

<!-- START CONTENT -->
    <section id="content" class="p-t-n m-t-n ">
        
        <!--breadcrumbs start-->
        <div id="breadcrumbs-wrapper" class=" grey lighten-3">
          <div class="container">
            <div class="row">
              <div class="col s12 m12 l12"> 
              	<h5 class="breadcrumbs-title"><?php echo $page_title ?></h5>
                <span><?php echo $page_subtitle ?></span>
                <ol class="breadcrumb m-n p-b-sm">
                    <?php get_breadcrumbs();?>
                </ol>
               
              </div>
            </div>
          </div>
        </div>
        <!--breadcrumbs end-->
        <!--start container-->
        <div class="container">
          <div class="section panel p-lg">
      <!--start section-->
          	<ul class="collapsible panel m-t-lg" data-collapsible="expandable">
			  <li>
			
				  <form id="user_form" name="user_form" class="form-vertical form-styled  m-t-lg" autocomplete="off">
				    <input type="hidden" name="user_id" id="user_id" value="<?php echo $id ?>">
				    <input type="hidden" name="salt" value="<?php echo $salt ?>">
				    <input type="hidden" name="token" value="<?php echo $token ?>">
				    <input type="hidden" name="image" id="avatar" value="<?php echo $photo ?>"/>
					<input class="none" type="password" />

				    <div class="form-basic">
				    	<?php if(is_null($user)):?>
				    	<div class="row m-b-n">
				 		    <div class="col s1">&nbsp;</div>
						    <div class="col s10">
							  <div class="row m-b-n">
				  			    <div class="col s3 none">
				 				  <div class="input-field">
								  	<input type="checkbox" class="labelauty" id="pds_flag" value="<?php echo ACTIVE ?>" data-labelauty="Get Data From PDS|Data From PDS"/>
								  </div>
							    </div>
							    <div class="col s6" id="div_pds_employee">
								  <div class="input-field">
								     <label for="pds_employee" class="active ">PDS Employees</label>
									  <select name="pds_employee" id="pds_employee" class="selectize" placeholder="Select Employee" data-parsley-required="true" data-parsley-validation-threshold="0" data-parsley-trigger="keyup">
									    <option value="">Select Employee</option>
									    <?php foreach ($employees as $pds): ?>
									    	<option value="<?php echo $pds["employee_id"]?>"><?php echo ucfirst($pds["first_name"])." ". ucfirst($pds["last_name"])." - ". $pds["agency_employee_id"]?></option>
									    <?php endforeach; ?>
									  </select>
								  </div>
							    </div>
							  </div>
						    </div>
						    <div class="col s1">&nbsp;</div>
					  	</div>
					  <?php endif;?>
					  <div class="row m-b-n">
			 		    <div class="col s1">&nbsp;</div>
					    <div class="col s10">
						  <div class="row m-b-n">
							<!-- davcorrea: 11/06/2023 : Add suffix : START -->
							<!-- <div class="col s5"> -->
			  			    <div class="col s4">
								<!-- END -->
			 				  <div class="input-field">
							    <input type="text" name="lname" id="lname" data-parsley-required="true" data-parsley-validation-threshold="0" data-parsley-trigger="keyup" value="<?php echo $lname ?>"/>
							    <label for="lname" class="user_label">Last Name</label>
							  </div>
						    </div>
						    <div class="col s4">
							  <div class="input-field">
							    <input type="text" name="fname" id="fname" data-parsley-required="true" data-parsley-validation-threshold="0" data-parsley-trigger="keyup" value="<?php echo $fname ?>"/>
							    <label for="fname" class="user_label">First Name</label>
							  </div>
						    </div>
							<!-- davcorrea: 11/06/2023 : Add suffix : START -->
							<!-- <div class="col s3"> -->
						    <div class="col s2">
								<!-- END -->
							  <div class="input-field">
							    <input type="text" name="mname" id="mname" value="<?php echo $mname ?>"/>
							    <label for="mname" class="user_label">Middle Initial</label>
							  </div>
						    </div>
							<!-- davcorrea: 11/06/2023 : Add suffix : START -->
						    <div class="col s2">
							  <div class="input-field">
							    <input type="text" name="suffix" id="suffix" value="<?php echo $suffix ?>"/>
							    <label for="suffix" class="user_label">Suffix</label>
							  </div>
						    </div>
							<!-- END -->
						  </div>
					    </div>
					    <div class="col s1">&nbsp;</div>
					  </div>
					  <div class="row m-b-n">
					    <div class="col s1">&nbsp;</div>
					    <div class="col s5 p-t-md p-r-lg">
			 			  <div class="input-field">
						    <input type="text" name="nickname" id="nickname" value="<?php echo $nickname ?>"/>
						    <label for="nickname" class="user_label">Nickname</label>
						  </div>
					    </div>
					    <div class="col s2 p-t-md">
						  <div class="input-field">
						    <input type="radio" class="labelauty" name="gender" id="user_gender_male" value="<?php echo MALE ?>" data-labelauty="Male" <?php echo $male ?>/>
							<label for="user_gender_male" class="active">Gender</label>
					      </div>
					    </div>
					    <div class="col s3 p-t-md">
						  <div class="input-field">
						    <input type="radio" class="labelauty" name="gender" id="user_gender_female" value="<?php echo FEMALE ?>" data-labelauty="Female" <?php echo $female ?>/>
					      </div>
					    </div>
					    <div class="col s1">&nbsp;</div>
					  </div>
					  <div class="row m-t-md m-b-n">
					    <div class="col s1">&nbsp;</div>
						<div class="col s10">
						  <h5 class="form-header">Contact Information</h5>
						  <div class="help-text black-text">All supplied contact numbers will only be used as reference and not for public viewing.</div>
						</div>
					    <div class="col s1">&nbsp;</div>
					  </div>
					  <div class="row">
					    <div class="col s1">&nbsp;</div>
						<div class="col s5">
						  <div class="input-field">
						    <input type="text" name="contact_no" id="contact_no" value="<?php echo $contact_no ?>" data-parsley-trigger="keyup" data-parsley-pattern="^[0-9-()+ ]+$" data-parsley-pattern-message="Telephone no. must contain parenthesis '()', dash '-', or numeric values only"/>
						    <label for="contact_no" class="user_label">Telephone No.</label>
						  </div>
						</div>
						<div class="col s5">
						  <div class="input-field">
						    <div class="input-group">
						      <div class="input-group-addon">+ 63</div>
						      <input type="text" name="mobile_no" id="mobile_no" value="<?php echo $mobile_no ?>" data-parsley-required="false" />
						      <label for="mobile_no" class="user_label">Mobile No.</label>
						    </div>
						  </div>
						</div>
					    <div class="col s1">&nbsp;</div>
					  </div>
					  <div class="row">
					    <div class="col s1">&nbsp;</div>
						<div class="col s6">
						  <label class="m-t-n-xs block" style="margin-bottom:8px;">Department/Agency</label>
						  <select name="org" id="org" class="selectize" placeholder="Select Agency" data-parsley-required="true" data-parsley-validation-threshold="0" data-parsley-trigger="keyup">
						    <option value="">Select Agency</option>
						    <?php foreach ($orgs as $org): ?>
						    	<option value="<?php echo $org["org_code"]?>"><?php echo $org["office"]?></option>
						    <?php endforeach; ?>
						  </select>
						</div>
						<div class="col s4">
						  <div class="input-field">
						    <input type="text" name="job_title" id="job_title" value="<?php echo $job_title ?>"/>
						    <label for="job_title" class="user_label">Job Title</label>
						  </div>
						</div>
					    <div class="col s1">&nbsp;</div>
					  </div>
					  
					  <div class="row">
					    <div class="col s1">&nbsp;</div>
						<div class="col s10">
						  <h5 class="form-header">Display Image</h5>
						  <div class="help-text black-text">Select and upload your latest photo to help others recognize this account.</div>
						  <div id="avatar_upload">Select File</div>
						</div>
					    <div class="col s1">&nbsp;</div>
					  </div>
					  
					  <div class="row m-b-n">
					    <div class="col s1">&nbsp;</div>
						<div class="col s10">
						  <h5 class="form-subtitle">Account Details</h5>
					    </div>
					    <div class="col s1">&nbsp;</div>
					  </div>
					  <div class="row">
					    <div class="col s1">&nbsp;</div>
						<div class="col s10">
						  <div class="input-field">
						    <!--<input type="email" name="email" id="email" data-parsley-required="true" data-parsley-validation-threshold="0" data-parsley-trigger="keyup" data-parsley-type="email" value="<?php //echo $email ?>"/>-->
							<!--marvin-->
						    <input type="email" name="email" id="email" data-parsley-required="false" data-parsley-validation-threshold="0" data-parsley-trigger="keyup" data-parsley-type="email" value="<?php echo $email ?>"/>
						    <label for="email" class="user_label">Email Address</label>
							<div class="help-text black-text">Supply a valid email address for your log in and to receive notifications from the system (e.g.forget password).</div>
					      </div>
					    </div>
					    <div class="col s1">&nbsp;</div>
					  </div>
					  <div class="row">
					    <div class="col s1">&nbsp;</div>
						<div class="col s10">
						  <div class="input-field">
						    <!-- <input type="text" readonly name="username" id="username" data-parsley-required="true" data-parsley-validation-threshold="0" data-parsley-trigger="keyup" value="<?php //echo $username ?>"/> -->
						    <input type="text" name="username" id="username" data-parsley-required="true" data-parsley-validation-threshold="0" data-parsley-trigger="keyup" value="<?php echo $username ?>"/>
						    <label for="username" class="user_label">Username</label>
							<div class="help-text black-text">Enter a unique username for this account.</div>
					      </div>
					    </div>
					    <div class="col s1">&nbsp;</div>
					  </div>
					  <div class="row">
					    <div class="col s1">&nbsp;</div>
					    <div class="col s4">
						  <div class="input-field">
						    <!--<input type="password" name="password" id="password" <?php if(!ISSET($user)){ ?> data-parsley-required="true" data-parsley-validation-threshold="0" data-parsley-trigger="keyup" <?php } ?>/>-->
							<!--marvin-->
						    <input type="password" name="password" id="password" <?php if(!ISSET($user)){ ?> data-parsley-required="true" data-parsley-validation-threshold="0" data-parsley-trigger="keyup" <?php } ?> disabled />
						    <label for="password" class="user_label">Password</label>
							<div class="help-text"><?php echo $pw_note ?></div>
					      </div>
						</div>
					    <div class="col s4">
						  <div class="input-field">
						    <!--<input type="password" name="confirm_password" id="confirm_password" <?php if(!ISSET($user)){ ?> data-parsley-required="true" data-parsley-validation-threshold="0" data-parsley-trigger="keyup" <?php } ?>/>-->
							<!--marvin-->
						    <input type="password" name="confirm_password" id="confirm_password" <?php if(!ISSET($user)){ ?> data-parsley-required="true" data-parsley-validation-threshold="0" data-parsley-trigger="keyup" <?php } ?> disabled />
							
							<!--marvin-->
						    <label for="confirm_password" class="user_label">Confirm Password</label>
					      </div>
						</div>
						<!--
					    <div class="col s1">&nbsp;</div>
						-->
						<!--marvin-->
						<?php if(!is_null($user)): ?>
						<div class="col s3">
						  <div class="input-field">
						    <button type="button" class="btn" id="btn_reset">Reset Password</button>
					      </div>
					    </div>
						<!--marvin-->
						<?php endif; ?>
					  </div>
					  <div class="row">
					    <div class="col s1">&nbsp;</div>
						<div class="col s6">
						  <div class="input-field">
						    <select name="role[]" id="role" class="selectize" placeholder="Select User Role" multiple data-parsley-required="true" data-parsley-validation-threshold="0" data-parsley-trigger="keyup">
						      <option value="">Select User Role</option>
						      <?php foreach ($roles as $role): ?>
								<option value="<?php echo $role["role_code"] ?>"><?php echo $role["role_name"] ?></option>
						      <?php endforeach; ?>
						    </select>
						    <label for="role" class="active">Role</label>
							<div class="help-text m-t-sm black-text">Assign role/s to this account</div>
					      </div>
					    </div>
						<div class="col s2">
						  <div class="input-field">
						    <input type="checkbox" class="labelauty" name="status" id="status" value="<?php echo ACTIVE ?>" data-labelauty="<?php echo $status_label;?>|Active" <?php echo $status ?>/>
						    <label for="status" class="active">Status</label>
					      </div>
					    </div>
					  </div>

					  <!-- ===============NCOCAMPO: ADD DTR_AO, LEAVE_AO AND IMM_SUP TO USER_MGMT:START 11/15/2023===============-->
					  <div class="row">
					    <div class="col s1">&nbsp;</div>
								<div class="col s10">
						 			 <div class="input-field">
						    		<input type="text" name="dtr_ao" id="dtr_ao" data-parsley-required="false" data-parsley-validation-threshold="0" data-parsley-trigger="keyup"  value="<?php echo $dtr_ao ?>" disabled/>
						   		 <label for="dtr_ao" class="user_label">DTR Approving Officers</label>
					      	</div>
					    	</div>
					  </div>					  
					  <div class="row">
					  	<div class="col s1">&nbsp;</div>
					    	<div class="col s10">
						 			<div class="input-field">
						    		<input type="text" name="imm_sup" id="imm_sup" data-parsley-required="false" data-parsley-validation-threshold="0" data-parsley-trigger="keyup" value="<?php echo $imm_sup ?>" disabled/>
						   		 <label for="imm_sup" class="user_label">Immediate Supervisor</label>
					      	</div>
					    	</div>
					  </div>
					  <div class="row">
					  	<div class="col s1">&nbsp;</div>
								<div class="col s10">
						 			<div class="input-field">
						    		<input type="text" name="leave_ao" id="leave_ao" data-parsley-required="false" data-parsley-validation-threshold="0" data-parsley-trigger="keyup" value="<?php echo $leave_ao ?>" disabled/>
						   		 <label for="leave_ao" class="user_label">Leave Approving Officers</label>
					      	</div>
					    	</div>
					  </div>
					  <!-- ===============NCOCAMPO: ADD DTR_AO, LEAVE_AO AND IMM_SUP TO USER_MGMT: END 11/15/2023===============-->
					  
					  <?php if(!ISSET($user)){ ?>
					  <div class="row m-b-n">
					    <div class="col s1">&nbsp;</div>
						<div class="col s10">
						  <h5 class="form-subtitle">Welcome Email</h5>
					    </div>
					    <div class="col s1">&nbsp;</div>
					  </div>
					  <div class="row m-b-n">
					    <div class="col s1">&nbsp;</div>
					    <div class="col s4 p-t-md">
						  <div class="input-field">
							<input type="radio" name="send_email" id="user_send_email" class="labelauty" data-labelauty="Send a 'Welcome' email to this user" <?php echo $send ?> value="1"/>
							<label for="user_send_email" class="active">Send Email</label>
					      </div>
					    </div>
					    <div class="col s5 p-t-md">
						  <div class="input-field">
						    <input type="radio" name="send_email" id="user_cancel_email" class="labelauty" data-labelauty="Don't send a 'Welcome!' email to this user" value="0" <?php echo $cancel ?> />
					      </div>
					    </div>
					    <div class="col s2">&nbsp;</div>
					  </div>
					  <?php } ?>
					  
				    </div>
				    <div class="panel-footer right-align">
					  <a href="<?php echo base_url() . PROJECT_CORE ?>/users" class="waves-effect waves-teal btn-flat">Cancel</a>
					  <button class="btn " id="save_user" name="action" type="submit" value="Save">Save</button>
					</div>
				  </form>
			  </li>
			</ul>
      <!--end section-->              
          </div>
        </div>
        <!--end container-->

    </section>
<!-- END CONTENT -->

<script type="text/javascript">
  $(function(){
	var $id = $('#user_id').val();	
		
	$('#user_form').parsley();
	$('#user_form').submit(function(e) {
		e.preventDefault();

		//marvin
		$("#password").attr('disabled', false);
		$("#confirm_password").attr('disabled', false);
		
	  if ( $(this).parsley().isValid() ) {
	    var data = $(this).serialize();
	  
	    button_loader('save_user', 1);
		
		
	    $.post("<?php echo base_url() . PROJECT_CORE ?>/users/process/", data, function(result) {
		  if(result.flag == 0){
			 
			//marvin
			$("#password").attr('disabled', true);
			$("#confirm_password").attr('disabled', true);
			
		    notification_msg("<?php echo ERROR ?>", result.msg);
		    button_loader('save_user', 0);
		  } else {
		    notification_msg("<?php echo SUCCESS ?>", result.msg);
		    button_loader("save_user",0);
		  
		    window.location.href= "<?php echo base_url() . PROJECT_CORE ?>/users/";
		  }
	    }, 'json');       
	  }
	});
	$('#pds_flag').off('change');
	$('#pds_flag').on('change', function(e) {
		
		if($(this).is(':checked')){
           $('#div_pds_employee').removeClass('none');
        }
		else
		{
			$('#div_pds_employee').addClass('none');
		}
	});
	$('#pds_employee').off('change');
	$('#pds_employee').on('change', function(e) {
		var employee_id = $('#pds_employee').val();
		if(employee_id != ""){

			var data = {"employee_id":employee_id};
		  	$.post("<?php echo base_url() . PROJECT_CORE ?>/users/get_pds_personal_info/",data, function(result) {
				$('#lname').val(result.last_name);
				$('#fname').val(result.first_name);
				$('#mname').val(result.middle_name);
				$('#suffix').val(result.ext_name); //ncocampo
				$('#contact_no').val(result.telephone);
				$('#mobile_no').val(result.mobile);
				$('#email').val(result.email);
				$('#username').val(result.user_name);
				$('#org')[0].selectize.setValue(result.office);
				
				//marvin
				$("#password").val(result.agency_employee_id);
				$("#confirm_password").val(result.agency_employee_id);

				if(result.gender_code === "F")
				{
					$('#user_gender_female').attr('checked',true);
				}
				else if(result.gender_code === "M")
				{
					$('#user_gender_male').attr('checked',true);
				}
				else
				{
					$('#user_gender_female').attr('checked',false);
					$('#user_gender_male').attr('checked',false);
				}
				if(result.account_status == "1")
				{
					$('#status').attr('checked',true);
				}
				else
				{
					$('#status').attr('checked',true);
				}
				
				$('.user_label').addClass('active');
		  	}, 'json');           
        }
		else
		{
			$('#lname').val("");
			$('#fname').val("");
			$('#mname').val("");
			$('#suffix').val("");
			$('#contact_no').val("");
			$('#mobile_no').val("");
			$('#email').val("");
			$('#username').val("");
			$('#org').val("");
			$('#user_gender_female').attr('checked',false);
			$('#user_gender_male').attr('checked',false);
			$('.user_label').removeClass('active');
		}
	});

  });
  
  //marvin
	$("#btn_reset").click(function(){
		$("#password").attr("disabled", false);
		$("#confirm_password").attr("disabled", false);
	});
</script>
