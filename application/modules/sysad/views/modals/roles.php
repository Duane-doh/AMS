<?php 
$role_code = "";
$role_name = "";
$disabled = "";
$header = "Create a new Role";
if(ISSET($role)){
	$role_code = (!EMPTY($role["role_code"]))? $role["role_code"] : "";
	$role_name = (!EMPTY($role["role_name"]))? $role["role_name"] : "";
	$disabled = "disabled";
	$header = "Update Role";
}

$salt = gen_salt();
$token = in_salt($role_code, $salt);
?>	  
<form id="role_form">
	<input type="hidden" name="id" value="<?php echo $role_code ?>">
	<input type="hidden" name="salt" value="<?php echo $salt ?>">
	<input type="hidden" name="token" value="<?php echo $token ?>">
	<div class="form-float-label">
	  <div class="row m-n">
	    <div class="col s4">
		  <div class="input-field">
		    <input type="text" class="validate" required="" aria-required="true" name="role_code" id="role_code" value="<?php echo $role_code ?>" <?php echo $disabled ?>/>
		    <label for="role_code">Code</label>
	      </div>
	    </div>
	    <div class="col s8">
		  <div class="input-field">
		    <input type="text" class="validate" required="" aria-required="true" name="role_name" id="role_name" value="<?php echo $role_name ?>"/>
		    <label for="role_name">Name</label>
	      </div>
	    </div>
	  </div>
	  <div class="row m-n">
	    <div class="col s12">
		  <div class="input-field">
		    <label class="active" for="system_role">System</label>
		    <select name="system_role[]" id="system_role" class="selectize" placeholder="Select System" multiple>
			  <option value="">Select System</option>
			  <?php foreach($systems as $system): ?>
				<option value="<?php echo $system["system_code"] ?>"><?php echo $system["system_name"] ?></option>				  
			  <?php endforeach; ?>
			</select>
		  </div>
	    </div>
	  </div>
	</div>
	<div class="md-footer default">
	  <?php //if($this->permission->check_permission(MODULE_ROLE, ACTION_SAVE)):?>
	    <button class="btn " id="save_role" value="<?php echo BTN_SAVE ?>"><?php echo BTN_SAVE ?></button>
	  <?php //endif; ?>
	  <a class="waves-effect waves-teal btn-flat cancel_modal" id="cancel_role">Cancel</a>
	</div>
</form>
<script>
$(function(){
  //help_text("role_form");
  
  $('#role_form').parsley();
  $('#role_form').submit(function(e) {
    e.preventDefault();
    
	if ( $(this).parsley().isValid() ) {
	  var data = $(this).serialize();
	  
	  button_loader('save_role', 1);
	  $.post("<?php echo base_url() . PROJECT_CORE ?>/roles/process/", data, function(result) {
		if(result.flag == 0){
		  notification_msg("<?php echo ERROR ?>", result.msg);
		  button_loader('save_role', 0);
		} else {
		  notification_msg("<?php echo SUCCESS ?>", result.msg);
		  button_loader("save_role",0);
		  modalObj.closeModal();
		  
		  load_datatable('role_table', '<?php echo PROJECT_CORE ?>/roles/get_role_list/');
		}
	  }, 'json');       
    }
  });
  
  <?php if(ISSET($role)){ ?>
	$('.input-field label').addClass('active');
  <?php } ?>
})
</script>