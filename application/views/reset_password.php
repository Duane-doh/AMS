<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title><?php echo get_setting(GENERAL, "system_title"); ?></title>
	<link rel="icon" type="image/png" href="<?php echo base_url().PATH_IMAGES.get_setting(GENERAL, "favicon"); ?>" />
	<link rel="stylesheet" href="<?php echo base_url().PATH_CSS ?>style.css">
  <link rel="stylesheet" href="<?php echo base_url().PATH_CSS ?>custom.css">
  <link rel="stylesheet" href="<?php echo base_url().PATH_CSS ?>parsley.css" type="text/css" />
  <link rel="stylesheet" href="<?php echo base_url().PATH_CSS ?>skin_<?php echo get_setting(THEME, "skins") ?>.css">
  <link rel="stylesheet" href="<?php echo base_url().PATH_CSS ?>materialize.css"  media="screen,projection"/>
  <link rel="stylesheet" type="text/css" href="<?php echo base_url().PATH_CSS ?>flaticon.css">
  <link rel="stylesheet" type="text/css" href="<?php echo base_url().PATH_CSS ?>material_icons.css">
  <link rel="stylesheet" type="text/css" href="<?php echo base_url().PATH_CSS ?>custom.css">
  <link type="text/css" href="<?php echo base_url().PATH_CSS ?>jquery.jscrollpane.css" rel="stylesheet" media="all" />
  <link type="text/css" href="<?php echo base_url().PATH_CSS ?>component.css" rel="stylesheet" media="all" />
  <link type="text/css" href="<?php echo base_url().PATH_CSS ?>popModal.css" rel="stylesheet" media="all" />
	
	 <script src="<?php echo base_url().PATH_JS ?>jquery-2.1.1.min.js"></script>
  <script src="<?php echo base_url().PATH_JS ?>jquery-ui.min.js" type="text/javascript"></script>
</head>

<body>
  <section id="wrapper" class="m-t-xl green lighten-4 p-lg"style="width:30%;height:340px;margin: auto;border-radius: 5px;">
	<div id="logo">
	<img style="width:50px;height:50px;" class="round " src="<?php echo base_url().PATH_IMAGES."doh_logo.png" ?>"/>
	<h6 class=" m-t-n-lg m-l-lg p-b-sm p-l-lg">Personnel Transaction Information System</h6>
	</div>
	
	<div id="login-wrapper" >
		<form id="reset_password_form" autocomplete="off">
			<input type="hidden" name="id" value="<?php echo $id ?>">
        	<input type="hidden" name="key" value="<?php echo $key ?>">
        	
			<div class="form-float-label m-t-lg">
			  	<div class="row m-n">
				    	<div class="col s12">
					  		<div class="input-field">
					    	<label for="password">New Password</label>
							<input type="password" name="password" id="password" class="input-rounded p-l-sm" data-parsley-required="true" data-parsley-minlength="8" data-parsley-equalto="#password2" data-parsley-trigger="keyup"/>
						</div>
					</div>
				</div>
			</div>
			<div class="form-float-label m-b-lg">
			  	<div class="row m-n">
				    	<div class="col s12">
					  		<div class="input-field">
					    	<label for="password2">Confirm Password</label>
							<input type="password" name="password2" id="password2" class="input-rounded p-l-sm" data-parsley-required="true" data-parsley-minlength="8" data-parsley-equalto="#password" data-parsley-trigger="keyup"/>
						</div>
					</div>
				</div>
			</div>
			<div class="form-group">
				<input type="submit" name="reset_password_btn" id="reset_password_btn" class="btn" value="Update Password"/>
			</div>
		</form>
	</div>
  </section>
  
  <!-- Alert -->
  <div class="notify success none"></div>
  <div class="notify error none"></div>
  
</body>
<script src="<?php echo base_url().PATH_JS ?>parsley.min.js" type="text/javascript"></script>

<script src="<?php echo base_url().PATH_JS ?>popModal.min.js" type="text/javascript"></script>

<script src="<?php echo base_url().PATH_JS ?>common.js" type="text/javascript"></script>
  <!-- PLATFORM SCRIPT -->
  <script src="<?php echo base_url().PATH_JS ?>materialize.js"></script>
  
  <script src="<?php echo base_url().PATH_JS ?>parsley.min.js" type="text/javascript"></script>
  <script src="<?php echo base_url().PATH_JS ?>collapsible-menu.js"></script>
  <!-- END PLATFORM SCRIPT -->
<script type="text/javascript">
$(function(){

	var $base_url = "<?php echo base_url() ?>";

	<?php if(!EMPTY($msg)){ ?>
	
		$(".notify.error").html("<?php echo $msg ?>");
		$(".notify.error").notifyModal({
			duration : -1
		});

		$("#reset_password_btn").attr("disabled", true);
		setTimeout(function(){ window.location = $base_url; }, 3000);
	
	<?php } else { ?>
	
		var	$reset_password	= $("#reset_password_form");
		
		$reset_password.parsley().subscribe('parsley:form:success', function (formInstance) {		
			formInstance.submitEvent.preventDefault();
				
			var data = $reset_password.serialize();
	 
			$.post($base_url + "forgot_password/update/", data, function(result) {
	
				if(result.flag == 1){
					alert_msg("<?php echo SUCCESS ?>", result.msg);
					setTimeout(function(){ window.location = $base_url; }, 5000);
				} else {
					alert_msg("<?php echo ERROR ?>", result.msg);
				}			  						
			}, 'json');		
		});
		
	<?php } ?>	
	
});
</script>

</html> 