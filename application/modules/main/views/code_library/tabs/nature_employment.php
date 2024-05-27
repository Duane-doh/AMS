<?php
	$data_id = 'nature_employment/' . $action_id;
?>
<div class="col l12 m5 s7 right-align m-b-n-l-lg">
<?php if($action_id != ACTION_VIEW) : ?>
	  <button class="btn btn-success  md-trigger green" data-modal="modal_nature_employment" onclick="modal_nature_employment_init('<?php echo ACTION_ADD; ?>')">Add <?php echo CODE_LIBRARY_NATURE_EMPLOYMENT; ?></a></button>
<?php ENDIF; ?>
</div>
<div class="pre-datatable filter-left">
	<table cellpadding="0" cellspacing="0" class="table table-advanced table-layout-auto" id="nature_employment_table">
	  <thead>
		<tr>
		  <th width="25%">Nature of Employment Name</th>
		  <th width="25%">Status</th>
		  <th width="2%">Actions</th>
		</tr>
		<tr class="table-filters">
			<td><input name="nature_employment_name" class="form-filter"></td>
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