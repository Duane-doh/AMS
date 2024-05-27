<div class="col l12 m12 s12 right-align p-r-n">
	<div class="input-field inline p-l-md z-input m-t-n m-b-md">
		<?php if($this->permission->check_permission(MODULE_HR_CL_ELIGIBILITY, ACTION_ADD)) :?>
			<button class="btn btn-success  md-trigger green" data-modal="modal_eligibility_type" onclick="modal_eligibility_type_init('<?php echo ACTION_ADD; ?>')"><i class="flaticon-add176"></i> Add Civil Service Eligibility</a></button>
		<?php endif; ?>
	</div>
</div>

<div class="pre-datatable filter-left"></div>
<div>
	<table cellpadding="0" cellspacing="0" class="table table-advanced table-layout-auto" id="eligibility_table">
	  <thead>
		<tr>
			<th width="25%">Civil Service Eligibility Code</th>
		  	<th width="25%">Civil Service Eligibility Name</th>
		 	<th width="25%">Status</th>
		 	<th width="2%">Actions</th>
		</tr>
		<tr class="table-filters">
			<td><input name="eligibility_type_code" class="form-filter"></td>
			<td><input name="eligibility_type_name" class="form-filter"></td>
			<td><input name="active_flag" class="form-filter"></td>
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
