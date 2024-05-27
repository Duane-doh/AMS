<form id="role_form">
	<div class="form-float-label">
	  <div class="row m-n">
	    <div class="col s12">
		  <table cellpadding="0" cellspacing="0" class="table table-advanced table-layout-auto" id="pagibig_info_table">
		  <thead>
			<tr>
			  <th width="15%">Effectivity Date</th>
			  <th width="15%">Salary Range From</th>
			  <th width="15%">Salary Range To</th>
			  <th width="15%">Employee Rate</th>
			  <th width="15%">Employer Rate</th>
			  <th width="10%">Status</th>
			  <th width="15">Actions</th>
			</tr>
			<tr class="table-filters">
			<td><input name="effectivity_date" class="form-filter"></td>
			<td><input name="salary_range_from" class="form-filter"></td>
			<td><input name="salary_range_to" class="form-filter"></td>
			<td><input name="employee_rate" class="form-filter"></td>
			<td><input name="employer_rate" class="form-filter"></td>
			<td><input name="active_flag" class="form-filter"></td>
			<td class="table-actions">
				<a href="javascript:;" class="tooltipped filter-submit" data-tooltip="Submit" data-position="top" data-delay="50"><i class="flaticon-filter19"></i></a>
				<a href="javascript:;" class="tooltipped filter-cancel" data-tooltip="Reset" data-position="top" data-delay="50"><i class="flaticon-circle100"></i></a>
			</td>
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