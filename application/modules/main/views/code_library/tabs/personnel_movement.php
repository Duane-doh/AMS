<div class="col l12 m12 s12 right-align p-r-n">
	<div class="input-field inline p-l-md z-input m-t-n m-b-md">
		<?php if($this->permission->check_permission(MODULE_HR_CL_PERSONNEL_MOVEMOVENT, ACTION_ADD)) :?>
			<button class="btn btn-success  md-trigger green" data-modal="modal_personnel_movement" onclick="modal_personnel_movement_init('<?php echo ACTION_ADD; ?>')"><i class="flaticon-add176"></i> Add <?php echo CODE_LIBRARY_PERSONNEL_MOVEMENT; ?></a></button>
		<?php endif; ?>
	</div>
</div>

<div class="pre-datatable filter-left"></div>
<div>
	<table cellpadding="0" cellspacing="0" class="table table-advanced table-layout-auto" id="personnel_movement_table">
	  <thead>
		<tr>
		  	<th width="20%">Personnel Movement</th>
		  	<th width="20%">Needs Appointment?</th>
		  	<th width="20%">Needs Office Order?</th>
		  	<th width="20%">Status</th>
		  	<th width="2%">Actions</th>
		</tr>
		<tr class="table-filters">
			<td><input name="personnel_movement_name" class="form-filter"></td>
			<td><input name="needs_appointment" class="form-filter"></td>
			<td><input name="needs_office_order" class="form-filter"></td>
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