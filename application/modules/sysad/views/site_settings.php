<?php 
  $salt = gen_salt();
  $token = in_salt($this->session->userdata('user_id'), $salt);
?>

<div class="row">
  <div class="col l10 m12 s12">
	<form id="site_settings_form" class="m-t-lg">
	  <input type="hidden" name="id" value="<?php echo $this->session->userdata('user_id') ?>"/>
	  <input type="hidden" name="salt" value="<?php echo $salt ?>">
	  <input type="hidden" name="token" value="<?php echo $token ?>">
	  
	  <input type="hidden" name="system_logo" id="system_logo" value="<?php echo get_setting(GENERAL, "system_logo") ?>"/>
	  <input type="hidden" name="system_favicon" id="system_favicon" value="<?php echo get_setting(GENERAL, "system_favicon") ?>"/>
	  
	  <div class="form-basic">
		<div id="site-info" class="scrollspy table-display input-field white box-shadow">
		  <div class="table-cell bg-dark p-lg valign-top" style="width:25%">
			<label class="label mute">Site Information</label>
			<p class="caption m-t-sm white-text">Control how your site is displayed, such as the title, tagline, description, and system email address.</p>
		  </div>
		  <div class="table-cell p-lg valign-top">
			<div class="row">
			  <div class="col s12">
				<div class="p-b-md">
				  <div class="input-field">
					<input id="system_title" name="system_title" type="text" class="validate" value="<?php echo get_setting(GENERAL, "system_title") ?>"/>
					<label for="system_title">Site Title</label>
					<div class="help-text">The site title is the name of your site or business. It generally appears in the title bar of a web browser, login page, or in the header located at the upper left corner of your site.</div>
				  </div>
				</div>
				<div class="p-b-md">
				  <div class="input-field">
					<input id="system_tagline" name="system_tagline" type="text" class="validate" value="<?php echo get_setting(GENERAL, "system_tagline") ?>"/>
					<label for="system_tagline">Tagline</label>
					<div class="help-text">The tag line is a secondary heading that displays near the site title or logo of your login page.</div>
				  </div>
				</div>
				<div class="p-b-md">
				  <div class="input-field">
					<textarea id="system_description" name="system_description" class="materialize-textarea"><?php echo get_setting(GENERAL, "system_description") ?></textarea>
					<label for="system_description">Site Description</label>
					<div class="help-text">The site description is a short bio or information about your site.</div>
				  </div>
				</div>
				<div class="p-b-md">
				  <div class="input-field">
					<input id="system_email" name="system_email" type="email" class="validate" value="<?php echo get_setting(GENERAL, "system_email") ?>"/>
					<label for="system_email">System Email</label>
					<div class="help-text">The system email is the "from" and "reply-to" address in automated e-mails sent during registration, password requests, and other notifications.</div>
				  </div>
				</div>
			  </div>
			</div>
		  </div>
		</div>
		<div id="site-logo" class="scrollspy table-display input-field white m-t-lg box-shadow">
		  <div class="table-cell bg-dark p-lg valign-top" style="width:25%">
			<label class="label mute">Logo &amp; Favicon</label>
			<p class="caption m-t-sm white-text">Set your logo and favicon to add branding to your site.</p>
		  </div>
		  <div class="table-cell p-lg valign-top">
			<div class="row">
			  <div class="col s12">
				<div class="p-b-md">
				  <h6>Site Logo</h6>
				  <div class="help-text">It is recommended that you use a logo with a transparent background <small>(.png file extension)</small>. This logo will appear on the upper left corner of the screen and login page.</div>
				  
				  <div class="avatar-container lg" style="width:100%;">
					<div class="avatar-action">
					  <a href="#" id="system_logo_upload" class="tooltipped m-r-sm" data-position="bottom" data-delay="50" data-tooltip="Upload"><i class="flaticon-upload111"></i></a>
					</div>
					<img id="system_logo_src" src="<?php echo base_url() . PATH_SETTINGS_UPLOADS . get_setting(GENERAL, "system_logo") ?>" class="m-b-md">
				  </div>
				</div>
				<div class="p-b-md">
				  <h6>Favicon</h6>
				  <div class="help-text">Image file type must be .ico. This icon will appear on your web browser's tab.</div>
				  
				  <div class="avatar-container md p-t-lg p-b-lg" style="width:100%;">
					<div class="avatar-action">
					  <a href="#" id="system_favicon_upload" class="tooltipped m-r-sm" data-position="bottom" data-delay="50" data-tooltip="Upload"><i class="flaticon-upload111"></i></a>
					</div>
					<div class="truncate" style="width:150px; margin:0 auto; background:#fff; padding:10px; border-radius:10px 10px 0 0; box-shadow: 0 -3px 4px 0 #e3e3e3;">
					  <img id="system_favicon_src" src="<?php echo base_url() . PATH_SETTINGS_UPLOADS . get_setting(GENERAL, "system_favicon") ?>">
					  <span id="favico_preview_title" class="m-l-xs font-thin">Asiagate Networks, Inc.</span>
					</div>
				  </div>
				</div>
			  </div>
			</div>
		  </div>
		</div>
		<div id="site-layout" class="scrollspy table-display input-field white m-t-lg box-shadow">
		  <div class="table-cell bg-dark p-lg valign-top" style="width:25%">
			<label class="label mute">Layout</label>
			<p class="caption m-t-sm white-text">Manage how your header and sidebar menu is displayed.</p>
		  </div>
		  <div class="table-cell p-lg valign-top">
			<h5>Sidebar Menu</h5>
			<div class="row">
			  <div class="col l6 m6 s12">
				<input type="radio" class="labelauty" name="sidebar_menu" id="layout_collapsed" value="slide-nav" data-labelauty="Collapsed"/>
				<ul class="list square">
				  <li>Collapsed menubar</li>
				  <li>Full-height menubar</li>
				  <li>Fixed header</li>
				</ul>
			  </div>
			  <div class="col l6 m6 s12">
				<input type="radio" class="labelauty" name="sidebar_menu" id="layout_expanded" value="" data-labelauty="Expanded"/>
				<ul class="list square">
				  <li>Expanded menubar</li>
				  <li>Full-height menubar</li>
				  <li>Fixed header</li>
				</ul>
			  </div>
			</div>
			<h5 class="m-t-lg">Header</h5>
			<div class="row">
			  <div class="col l6 m6 s12">
				<input type="radio" class="labelauty" name="header" id="header_default" value="default" data-labelauty="Normal"/>
				<ul class="list square">
				  <li>Default theme color <strong>(Logo section)</strong></li>
				  <li>White header</li>
				</ul>
			  </div>
			  <div class="col l6 m6 s12">
				<input type="radio" class="labelauty" name="header" id="header_inverse" value="inverse" data-labelauty="Inverted"/>
				<ul class="list square">
				  <li>Default theme color <strong>(Logo section and header)</strong></li>
				</ul>
			  </div>
			</div>
		  </div>
		</div>
		<div id="site-skin" class="scrollspy table-display input-field white m-t-lg box-shadow">
		  <div>
		    <div class="table-cell bg-dark p-lg valign-top" style="width:25%">
			  <label class="label mute">Skins</label>
			  <p class="caption m-t-sm white-text">Customize your site's colour scheme to fit the styling and branding you desire.</p>
		    </div>
		    <div class="table-cell p-lg valign-top">
			  <div class="row" style="width:95%; margin:0 auto;">
			    <div class="col l3 m4 s6">
				  <input type="radio" class="labelauty" name="skins" id="skin_default" value="default" data-labelauty="Default"/>
			    </div>
			    <div class="col l3 m4 s6">
				  <input type="radio" class="labelauty" name="skins" id="skin_red" value="red" data-labelauty="Red"/>
			    </div>
			    <div class="col l3 m4 s6">
				  <input type="radio" class="labelauty" name="skins" id="skin_green" value="green" data-labelauty="Green"/>
			    </div>
			    <div class="col l3 m4 s6">
				  <input type="radio" class="labelauty" name="skins" id="skin_orange" value="orange" data-labelauty="Orange"/>
			    </div>
			    <div class="col l3 m4 s6">
				  <input type="radio" class="labelauty" name="skins" id="skin_lime" value="lime" data-labelauty="Lime"/>
			    </div>
			    <div class="col l3 m4 s6">
				  <input type="radio" class="labelauty" name="skins" id="skin_violet" value="violet" data-labelauty="Violet"/>
			    </div>
			    <div class="col l3 m4 s6">
				  <input type="radio" class="labelauty" name="skins" id="skin_blue" value="blue" data-labelauty="Blue"/>
			    </div>
			    <div class="col l3 m4 s6">
				  <input type="radio" class="labelauty" name="skins" id="skin_pink" value="pink" data-labelauty="Pink"/>
			    </div>
			  </div>
		    </div>
		  </div>
		  <div class="panel-footer right-align">
		    <div class="input-field inline m-n">
			  <button class="btn  bg-success" type="button" id="save_site_settings" value="Save">Save</button>
		    </div>
		  </div>
		</div>
	  </div>
	</form>
  </div>
  <div class="col l2 hide-on-med-and-down">
	<div class="pinned m-t-lg">
	  <ul class="section table-of-contents">
		<li><a href="#site-info">Site Information</a></li>
		<li><a href="#site-logo">Logo &amp; Favicon</a></li>
		<li><a href="#site-layout">Layout</a></li>
		<li><a href="#site-skin">Skins</a></li>
	  </ul>
	</div>
  </div>
</div>

<script>
$(function(){
	$("#site_settings_form #site-info input").each(function(){
		if( $(this).val().length > 0 ) {
			var id = $(this).attr("id");
			$("label[for='"+ id +"']").addClass("active");
		}
	})
	
	<?php if(get_setting(LAYOUT, "sidebar_menu") == 'slide-nav'){ ?>
	  $("#layout_collapsed").prop("checked", true);
	<?php }else{ ?>
	  $("#layout_expanded").prop("checked", true);
	<?php } ?>
	
	$("#skin_<?php echo get_setting(THEME, "skins") ?>").prop("checked", true);
	$("#header_<?php echo get_setting(LAYOUT, "header") ?>").prop("checked", true);
	
	$("#save_site_settings").on("click", function(){
	  var data = $("#site_settings_form").serialize();
	  
	  button_loader('save_site_settings', 1);
	  $.post("<?php echo base_url() . PROJECT_CORE ?>/site_settings/process", data, function(result){
		Materialize.toast(result.msg, 3000, '', function(){
		  button_loader('save_site_settings', 0);
		  location.reload(); 
		});
	  }, 'json');
	});
});
</script>