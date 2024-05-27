<div class="col l12 m12 s12 right-align p-r-n">
	<div class="input-field inline p-l-md z-input m-t-n m-b-md">
		<?php if($this->permission->check_permission(MODULE_PAYROLL_CL_UACS_OBJECT, ACTION_ADD)) :?>
			<button class="btn btn-success  md-trigger green" data-modal="modal_uacs_code" onclick="modal_uacs_code_init('<?php echo ACTION_ADD; ?>')"><i class="flaticon-add176"></i> Add <?php echo CODE_LIBRARY_UACS_OBJECT; ?></a></button>
		<?php endif; ?>
	</div>
</div>

<div class="pre-datatable filter-left"></div>
<div>
	<table cellpadding="0" cellspacing="0" class="table table-advanced table-layout-auto" id="uacs_code_table">
	  <thead>
		<tr>
		  <th width="48%">Account Title</th>
		  <th width="15%">UACS Object Code</th>
		  <th width="15%">Type</th>
		  <th width="12%">Status</th>
		  <th width="10%">Actions</th>
		</tr>
		<tr class="table-filters">
			<td><input name="account_title" class="form-filter"></td>
			<td><input name="uacs_object_code" class="form-filter"></td>
			<td><input name="uacs_object_type" class="form-filter"></td>
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