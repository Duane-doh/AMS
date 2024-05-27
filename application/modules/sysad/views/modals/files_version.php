<?php 
$file_id = $file['file_id'];
$file_name = 'Version ' . $file['version'] .' &raquo; '.$file['file_name'];

$salt = gen_salt();
$token = in_salt($file_id, $salt);
?>
<form id="upload_file_version_form">
  <div class="notification info m-n" style="border-radius:0;">
    <p>
	  <label class="font-semibold text-uppercase dark block">File to replace</label>
	  <?php echo $file_name;?>
    </p>
  </div>
  
  <div class="table-display">
	<div class="table-cell valign-top p-md" style="width:60%;">
	  <input type="hidden" name="file_version" id="file_version" value="" />
	  <input type="hidden" name="file_id" value="<?php echo $file_id ?>" />
	  <input type="hidden" name="salt" value="<?php echo $salt ?>" />
	  <input type="hidden" name="token" value="<?php echo $token ?>" />
	  
	  <div class="form-basic">
	    <div class="row">
	   	  <div class="col s12">
		    <div class="input-field">
		   	  <label class="active">Upload a File</label>
			  <div class="help-text m-t-md">Select and upload the latest version of this attachment.</div>
			  <a href="#" id="file_version_upload" class="tooltipped m-r-sm" data-position="bottom" data-delay="50" data-tooltip="Upload">Choose a file to upload</a>
			</div>
		  </div>
		</div>
	  </div>
	</div>
	
	<div class="table-cell valign-top b-l" style="border-color:#e2e7e7!important; width:40%; position:relative;">
	  <div class="form-float-label">
		<div class="row m-n b-l-n b-t-n">
		  <div class="col s12">
			<div class="input-field">
			  <label>Description</label>
			  <textarea class="materialize-textarea" name="file_version_description" id="file_version_description"></textarea>
			</div>
		  </div>
		</div>
	  </div>
	  
	  <div class="p-t-md p-b-md p-l-sm p-r-sm m-b-lg">
	    <input type="checkbox" name="minor_revision_flag" id="minor_revision_flag" class="labelauty" data-labelauty="I have made minor revisions only" value="1"/>
	  </div>
	  <div class="md-footer default">
		<?php //if($this->permission->check_permission(MODULE_ROLE, ACTION_SAVE)):?>
		<button type="submit" class="btn " id="save_upload_file_version" value="<?php echo BTN_SAVE ?>"><?php echo BTN_SAVE ?></button>
		<?php //endif; ?>
		<a class="btn-flat p-r-n p-l-md" id="cancel_upload_file_version">Cancel</a>
	  </div>
    </div>
  </div>
</form>

<script type="text/javascript">
$(function(){	
  $("#cancel_upload_file_version").on("click", function(){
	modalObj.closeModal();
  });
  
  $('#upload_file_version_form').parsley();
  $('#upload_file_version_form').submit(function(e) {
    e.preventDefault();
    
	if ( $(this).parsley().isValid() ) {
	  var data = $(this).serialize();
	  
	  button_loader('save_upload_file_version', 1);
	  $.post("<?php echo base_url() . PROJECT_CORE ?>/files/insert_file_version/", data, function(result) {
		if(result.flag == 0){
		  notification_msg("<?php echo ERROR ?>", result.msg);
		  button_loader('save_upload_file_version', 0);
		} else {
		  notification_msg("<?php echo SUCCESS ?>", result.msg);
		  button_loader("save_upload_file_version",0);
		  
		  $("#modal_upload_file_version").removeClass("md-show");
		  window.location.reload();
		}
	  }, 'json');       
    }
  });
});
</script>