<div class="col l12 m5 s7 right-align m-b-n-l-lg">
	<div class="input-field inline p-l-md z-input">
		<?php if($this->permission->check_permission(MODULE_HR_CL_ACADEMIC_HONORS, ACTION_ADD)) :?>
			<button class="btn btn-success  md-trigger green" data-modal="modal_academic_honor" onclick="modal_academic_honor_init('<?php echo ACTION_ADD; ?>')">Add <?php echo CODE_LIBRARY_ACADEMIC_HONOR; ?></a></button>
		<?php endif; ?>
	</div>
</div>

<div class="pre-datatable filter-left "></div>
<div>
	<table cellpadding="0" cellspacing="0" class="table table-advanced table-layout-auto" id="academic_honor_table">
		<thead>
			<tr>
				<th width="10%">Academic Honor Name</th>
				<th width="10%">Status</th>
				<th width="2%">Actions</th>
			</tr>
			<tr class="table-filters">
				<td><input name="academic_honor_name" class="form-filter"></td>
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
