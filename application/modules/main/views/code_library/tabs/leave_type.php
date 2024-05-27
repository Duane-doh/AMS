<div class="col l12 m12 s12 right-align p-r-n">
	<div class="input-field inline p-l-md z-input m-t-n m-b-md">
		<?php if($this->permission->check_permission(MODULE_TA_LEAVE_TYPE, ACTION_ADD)) :?>
			<button class="btn btn-success  md-trigger" data-modal="modal_leave_type" onclick="modal_leave_type_init('<?php echo ACTION_ADD; ?>')"><i class="flaticon-add176"></i> Add <?php echo CODE_LIBRARY_LEAVE_TYPE; ?></a></button>
		<?php endif; ?>
	</div>
</div>
<h1><?php echo $status; ?></h1>
<div class="pre-datatable filter-left"></div>
<div>
	<table cellpadding="0" cellspacing="0" class="table table-advanced table-layout-auto" id="leave_type_table">
		<thead>
			<tr>
				<th width="15%">Leave Type Name</th>
				<th width="15%">Deduction From Leave Type</th>
				<th width="15%">Include in Certification</th>
				<th width="15%">Built-in Flag</th>		  		  		  		  		  		  		  		  
				<th width="15%">Status</th>
				<th width="5%">Actions</th>
			</tr>
			<tr class="table-filters">
				<td><input name="leave_type_name" class="form-filter"></td>
				<td><input name="deduct_bal_leave_type_id" class="form-filter"></td>
				<td><input name="cert_flag" class="form-filter"></td>
				<td><input name="built_in_flag" class="form-filter"></td>
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
