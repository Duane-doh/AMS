<div class="col l12 m5 s7 right-align m-b-n-l-lg">
<?php if($action_id != ACTION_VIEW) : ?>
	  <button class="btn btn-success  md-trigger green" data-modal="modal_office" onclick="modal_office_init('<?php echo ACTION_ADD; ?>')">Add <?php echo CODE_LIBRARY_OFFICE; ?></a></button>
<?php ENDIF; ?>
</div>
<div class="pre-datatable filter-left">
	<table cellpadding="0" cellspacing="0" class="table table-advanced table-layout-auto" id="office_table">
	  <thead>
		<tr>
		  <th width="20%">Office Name</th>
		  <th width="20%">Cluster</th>
		  <th width="10%">Status</th>		  
		  <th width="5%">Actions</th>
		</tr>
		<tr class="table-filters">
			<td><input name="B-name" class="form-filter"></td>
			<td><input name="C-cluster_name" class="form-filter"></td>
			<td><input name="A-active_flag" class="form-filter"></td>
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
<script type="text/javascript">
</script>