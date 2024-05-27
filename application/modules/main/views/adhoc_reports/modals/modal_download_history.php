
<form id="download_template_form">
	<input type="hidden" name="download_history_id" id="download_history_id" value="<?php echo !EMPTY($id) ? $id : NULL?>">
	<div class="p-sm">
		<div class="col s12 p-t-sm">
			<table class="table table-advanced table-layout-auto" id="download_history_table">
				<thead>
					<tr>
						<th width = "20%" class="font-semibold">Table Name</th>
						<th width = "20%" class="font-semibold">Field Name</th>
						<th width = "20%" class="font-semibold">Start Value</th>
						<th width = "20%" class="font-semibold">End Value</th>
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
		</div>
	</div>

	<div class="md-footer default">
		<a class="waves-effect waves-teal btn-flat cancel_modal">Cancel</a>
	</div>
</form>

<script type="text/javascript">
	
load_datatable('download_history_table', '<?php echo PROJECT_MAIN ?>/adhoc_reports/get_download_history_list',false,0,0,true,{'download_history_id' : $('#download_history_id').val()});

</script>