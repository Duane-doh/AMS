<form id="employee_leave_form">
	<div class="section panel">
	    <div class="col s12 p-r-md">
	    	<?php if($action != ACTION_VIEW):?>
	      	<button class="btn btn-success md-trigger pull-right" type="button" data-modal="modal_add_employee_leave_type" onclick="modal_add_employee_leave_type_init('<?php echo $action."/".$id."/".$token."/".$salt."/".$module ?>')">Add Leave</button>
	   		<?php endif?>
	    </div>
	</div>
	<div class="form-float-label">
	  <div class="row m-n">
	    <div class="col s12">
	    	<div class="pre-datatable filter-left"></div>
	    	<diV>
		  <table cellpadding="0" cellspacing="0" class="table table-advanced table-layout-auto" id="table_employee_leave_list">
		  <thead>
			<tr>
			  <th width="40%">Leave Type</th>
			  <th width="20%">Balance</th>
			  <th width="20%">Pending</th>
			  <th width="20">Actions</th>
			</tr><tr class="table-filters">
			<td><input name="A-leave_type_name" class="form-filter"></td>
			<td><input name="B-leave_balance" class="form-filter"></td>
			<td><input name="pending_leave" class="form-filter"></td>
			<td class="table-actions">
				<a href="javascript:;" class="tooltipped filter-submit" data-tooltip="Filter" data-position="top" data-delay="50"><i class="flaticon-filter19"></i></a>
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
	</div>
</form>