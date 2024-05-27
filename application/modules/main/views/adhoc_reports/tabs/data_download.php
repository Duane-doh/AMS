<div class="col l12 m5 s7 right-align m-b-n-l-lg p-r-n">
	<div  class="input-field inline p-l-md z-input m-n">
	<button class="btn btn-success  md-trigger" data-modal="modal_data_download" onclick="modal_data_download_init('<?php echo ACTION_ADD; ?>')">Add <?php echo TAB_DATA_DOWNLOAD; ?></button>
	</div>
</div>

<div class="pre-datatable filter-left"></div>
<div class="p-t-xl">
	<table cellpadding="0" cellspacing="0" class="table table-advanced table-layout-auto" id="data_download_table">
		<thead>
			<tr>
				<th width="25%"><center>Data Download Name</center></th>
				<th width="25%"><center>Last Download Date</center></th>
				<th width="15%"><center>Status</center></th>
				<th width="15%"><center>Actions</center></th>
			</tr>
			<tr class="table-filters">
				<td><input name="A-name" class="form-filter"></td>
				<td><input name="last_download" class="form-filter"></td>
				<td>
				</td>
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
