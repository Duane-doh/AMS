<div class="col l12 m12 s12 right-align p-r-n">
	<div class="input-field inline p-l-md z-input m-t-n m-b-md">
		<?php if($this->permission->check_permission(MODULE_SYSTEM_CL_SIGNATORIES, ACTION_ADD)) :?>
			<button class="btn btn-success  md-trigger" data-modal="modal_signatories" onclick="modal_signatories_init('<?php echo ACTION_ADD; ?>')"><i class="flaticon-add176"></i> Add Signatory</a></button>
		<?php ENDIF; ?>
	</div>
</div>

<div class="pre-datatable filter-left"></div>
<div>
	<table cellpadding="0" cellspacing="0" class="table table-advanced table-layout-auto" id="signatories_table">
	  <thead>
		<tr>
		  <th width="25%">Fullname</th>
		  <th width="25%">Position</th>
		  <th widht="25%">Office</th>
		  <th width="10%">System Types</th>
		  <th width="10%">Signatory Types</th>
		  <th width="10%">Actions</th>

		</tr>
		<tr class="table-filters">
			<td><input name="signatory_name" class="form-filter"></td>
			<td><input name="position_name" class="form-filter"></td>
			<td><input name="office_name" class="form-filter"></td>
			<td><input name="sys_code_flags" class="form-filter"></td>
			<td><input name="signatory_type_flags" class="form-filter"></td>
			
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
