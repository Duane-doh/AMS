<div class="col l12 m12 s12 right-align p-r-n">
	<div class="input-field inline p-l-md z-input m-t-n m-b-md">
		<?php if($this->permission->check_permission(MODULE_PAYROLL_CL_BANK_BRANCH, ACTION_ADD)) :?>
			<button class="btn btn-success  md-trigger" data-modal="modal_bank" onclick="modal_bank_init('<?php echo ACTION_ADD; ?>')"><i class="flaticon-add176"></i> Add <?php echo CODE_LIBRARY_BANK; ?></a></button>
		<?php endif; ?>
	</div>
</div>

<div class="pre-datatable filter-left"></div>
<div>
	<table cellpadding="0" cellspacing="0" class="table table-advanced table-layout-auto" id="bank_table">
	  <thead>
		<tr>
			<th width="20%">Bank Name</th>
			<th width="10%">Branch Code</th>
			<th width="20%">Account No.</th>
			<th width="20%">Fund Source</th>
			<th width="20%">Status</th>
			<th width="10%">Actions</th>
		</tr>
		<tr class="table-filters">
			<td><input name="A-bank_name" class="form-filter"></td>
			<td><input name="A-branch_code" class="form-filter"></td>
			<td><input name="A-account_no" class="form-filter"></td>
			<td><input name="B-fund_source_name" class="form-filter"></td>
			<td><input name="A-active_flag" class="form-filter"></td>
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
