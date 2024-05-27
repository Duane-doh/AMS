<style>
  p{ font-size:12px; font-family:arial;margin:5px 0;}
</style>
<div style="width:85%; margin:0 auto;">
  <div style="background:#f2c65d; padding:20px 30px;"><img src="<?php echo base_url() . PATH_IMAGES ?>logo_new2.png" height="40" alt="logo" /></div>
  <div style="background:#f6f6f6; padding:30px;">
	<p style="font-size:14px; font-family:'Open Sans', arial; margin-bottom:20px;">Dear <?php echo $name ?>,</p>
	<p style="font-size:14px; font-family:'Open Sans', arial;margin:10px 0; line-height:25px;">
	Thank you for registering to the <?php echo $system_name ?>! Your registration awaits approval by the site administrator. 
	Once approved or denied, we will notify you again through email.</p>

	<p style="font-size:14px; font-family:'Open Sans', arial;margin:10px 0; line-height:25px;">
	  Some providers may mark our emails as spam, so make sure to check your spam or junk folder.
	</p>
	<p style="margin-top:30px; font-size:14px; font-family:'Open Sans', arial; line-height:25px;">
	  Sincerely,<br/>The <?php echo $system_name ?> Team
	</p>	
  </div>
</div>
