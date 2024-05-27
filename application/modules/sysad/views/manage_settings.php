<div class="page-title">
  <ul id="breadcrumbs">
	<li><a href="#">Home</a></li>
	<li><a href="#" class="active">Manage Settings</a></li>
  </ul>
  <div class="row m-b-n">
	<div class="col s12 p-r-n">
	  <h5>Manage Settings
		<span>Lorem ipsum dolor sit amet, nunc vestibular.</span>
	  </h5>
	</div>
  </div>
</div>

<div class="tabs-wrapper">
  <div style="width:40%">
    <ul class="tabs">
	  <li class="tab col s3"><a href="#tab_site_settings" onclick="load_index('tab_site_settings', 'site_settings', '<?php echo PROJECT_CORE ?>')">Site</a></li>
	  <li class="tab col s3"><a class="active" href="#tab_account" onclick="load_index('tab_account_settings', 'account_settings', '<?php echo PROJECT_CORE ?>')">Authentication</a></li>
    </ul>
  </div>
</div>

  <div id="tab_site_settings" class="tab-content col s12"></div>
  <div id="tab_account_settings" class="tab-content col s12"></div>
  
<script type="text/javascript">
$(function(){
	set_active_tab('<?php echo PROJECT_CORE ?>');
});
</script>
