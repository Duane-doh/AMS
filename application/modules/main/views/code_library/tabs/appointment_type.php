<?php
	$data_id = 'appointment_type/' . $action_id;
?>
<div class="col l12 m5 s7 right-align m-b-n-l-lg">
<?php if($action_id != ACTION_VIEW) : ?>
	  <button class="btn btn-success  md-trigger green" data-modal="modal_appointment_type" onclick="modal_appointment_type_init('<?php echo ACTION_ADD; ?>')">Add <?php echo CODE_LIBRARY_APPOINTMENT_TYPE; ?></a></button>
<?php ENDIF; ?>
</div>


<div class="pre-datatable filter-left">
	<table cellpadding="0" cellspacing="0" class="table table-advanced table-layout-auto" id="appointment_type_table">
	  <thead>
		<tr>
		  <th width="25%">Appointment Type Name</th>
		  <th width="25%">Status</th>
		  <th width="2%">Actions</th>
		</tr>
		<tr class="table-filters">
			<td><input name="appointment_type_name" class="form-filter"></td>
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