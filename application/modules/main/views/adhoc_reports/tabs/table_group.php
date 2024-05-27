<div class="col l12 m5 s7 right-align m-b-n-l-lg p-r-n">
	<div  class="input-field inline p-l-md z-input m-n">
	<button class="btn btn-success  md-trigger" data-modal="modal_table_group" onclick="modal_table_group_init('<?php echo ACTION_ADD; ?>')"> Add <?php echo TAB_TABLE_GROUP; ?></button>
	</div>
</div>

<div class="pre-datatable filter-left"></div>
<div class="p-t-xl">
	<table cellpadding="0" cellspacing="0" class="table table-advanced table-layout-auto" id="table_group_table">
		<thead>
			<tr>
				<th width="35%">Group Name</th>
				<th width="20%">Created Date</th>
				<th width="30%">Created By</th>
				<th width="15%">Actions</th>
			</tr>
			<tr class="table-filters">
				<td><input name="A-group_name" class="form-filter"></td>
				<td><input name="A-created_date" class="form-filter"></td>
				<td><input name="name" class="form-filter"></td>
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
