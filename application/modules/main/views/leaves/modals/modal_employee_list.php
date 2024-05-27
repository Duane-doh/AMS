
<form id="role_form">
	<div class="form-float-label">
	  <div class="row m-n">
	    <div class="col s12">
		  <table cellpadding="0" cellspacing="0" class="table table-default table-layout-auto" id="employee_list_leave">
		  <thead>
			<tr>
			  <th width="30%">Employee No</th>
			  <th width="30%">Employee Name</th>
			  <th width="20%">Office</th>
			  <th width="20%">Balance</th>
			</tr>
		  </thead>
			  <tbody>
			  	
			  </tbody>
		  </table>
	    </div>
	  </div>
	</div>
  <div class="md-footer default">
		<a class="waves-effect waves-teal btn-flat cancel_modal" id="cancel_service_record">Cancel</a>
	  <?php //if($this->permission->check_permission(MODULE_ROLE, ACTION_SAVE)):?>
	    <button class="btn btn-success  green" id="save_service_record" value="<?php echo BTN_SAVE ?>"><?php echo BTN_SAVE ?></button>
	  <?php //endif; ?>

	</div>
</form>
<script>
$(function (){
	
})
</script>