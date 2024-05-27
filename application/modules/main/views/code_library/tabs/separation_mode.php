<div class="col l12 m12 s12 right-align p-r-n">
	<div class="input-field inline p-l-md z-input m-t-n m-b-md">
		<?php if($this->permission->check_permission(MODULE_HR_CL_MODE_SEPARATION, ACTION_ADD)) :?>
			<button class="btn btn-success  md-trigger green" data-modal="modal_separation_mode" onclick="modal_separation_mode_init('<?php echo ACTION_ADD; ?>')"><i class="flaticon-add176"></i> Add <?php echo CODE_LIBRARY_SEPARATION_MODE; ?></a></button>
		<?php endif; ?>
	</div>
</div>

<div class="pre-datatable filter-left"></div>
<div>
	<table cellpadding="0" cellspacing="0" class="table table-advanced table-layout-auto" id="separation_mode_table">
	  <thead>
		<tr>
		  <th width="25%">Mode of Separation Name</th>
		  <th width="25%">Status</th>
		  <th width="2%">Actions</th>
		</tr>
		<tr class="table-filters">
			<td><input name="separation_mode_name" class="form-filter"></td>
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