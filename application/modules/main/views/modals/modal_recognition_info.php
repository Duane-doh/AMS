<h3 class="md-header"><?php echo SUB_MENU_OTHER_INFO; ?></h3>
<form id="role_form">
	<div class="form-float-label">
	  <div class="row m-n">
	    <div class="col s12">
		  <div class="input-field">
		   	<input type="text" class="validate" required="" aria-required="true" name="role_name" id="role_name" value="<?php echo $role_name ?>"/>
		    <label for="role_name">Non-Academic Distinctions / Recognition</label>
		  </div>
	    </div>
	  </div>
	  <div class="row m-n">
	    <div class="col s12">
		  <div class="input-field">
		   	<input type="text" class="validate" required="" aria-required="true" name="role_name" id="role_name" value="<?php echo $role_name ?>"/>
		    <label for="role_name">Non-Academic Distinctions / Recognition Value</label>
		  </div>
	    </div>
	</div>
	</div>	
</b>
	<div class="md-footer default">
		<a class="waves-effect waves-teal btn-flat" id="cancel_pds_identification">Cancel</a>
	  <?php //if($this->permission->check_permission(MODULE_ROLE, ACTION_SAVE)):?>
	    <button class="btn " id="save_service_record" value="<?php echo BTN_SAVE ?>"><?php echo BTN_SAVE ?></button>
	  <?php //endif; ?>
	</div>
</form>
<script>
$(function (){
	$('.selectize').selectize();
	$("#cancel_pds_identification").on("click", function(){
		modalObj.closeModal();
	});
})
</script>