<?php if($action != ACTION_VIEW) : ?>
<div class="col l12 m5 s7 right-align">
	<div class="input-field inline">
		<button class="btn btn-success  md-trigger" data-modal="modal_supporting_documents" onclick="modal_supporting_documents_init('<?php echo ACTION_ADD ?>')"> Add Supporting Document</a></button>
	</div>
</div>
<?php ENDIF; ?>
<div class="pre-datatable filter-left"></div>
<div>
	<table cellpadding="0" cellspacing="0" class="table table-advanced dataTable table-layout-auto" id="table_supporting_documents">
	  <thead>
		<tr>
		  <th width="20%">Document Type</th>
		  <th width="20%">Date Received</th>
		  <th width="20%">Remarks</th>
		  <th width="20%">Actions</th>
		</tr>
		<tr class="table-filters">
				<td><input name="A-relation_first_name" class="form-filter"></td>
				<td><input name="A-relation_last_name" class="form-filter"></td>
				<td><input name="A-relation_birth_date" class="form-filter"></td>
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