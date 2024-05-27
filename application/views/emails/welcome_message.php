<div style="width:85%; margin:0 auto;">
  <div style="background:#f2c65d; padding:20px 30px;"><img src="<?php echo base_url() . PATH_IMAGES ?>logo_new2.png" height="40" alt="logo" /></div>
  <div style="background:#f6f6f6; padding:30px;">
	<p style="font-size:14px; font-family:'Open Sans', arial; margin-bottom:20px;">Hi <?php echo $name ?>,</p>
	<p style="font-size:14px; font-family:'Open Sans', arial;margin:10px 0 20px; line-height:25px;">
	  A user account has been created for you in the <?php echo $system_name ?> by <?php echo $created_by ?>. Please use the following credentials to log in.
	</p>
	<div style="border:1px solid #E3DEB5; background:#FBF7DC; border-radius:2px; padding:15px; word-break:keep-all; margin-top:20px;">
	  <p style="font-size:14px; font-family:'Open Sans', arial;margin:10px 0; line-height:25px; word-break:keep-all;">
		<strong>Email</strong>&nbsp;&nbsp;&nbsp;<?php echo $email ?><br/>
		<strong>Password</strong>&nbsp;&nbsp;&nbsp;<?php echo $raw_password ?>
	  </p>
	  <p style="font-size:14px; font-family:'Open Sans', arial;margin:10px 0 0; line-height:25px;">
		We recommend that you change your password after logging in for security purposes.
	  </p>	  
	</div>
	<p style="margin-top:20px; font-size:14px; font-family:'Open Sans', arial; line-height:25px;">Sincerely,<br/>The <?php echo $system_name ?> Team</p>	
	<p style="font-size:13px; color:#999; text-shadow: 0 0 2px #fff; font-family:'Open Sans', arial;margin:30px 0 0; line-height:25px;">
	  This email is sent automatically by the system, please do not reply directly. 
	</p>
  </div>
</div>
