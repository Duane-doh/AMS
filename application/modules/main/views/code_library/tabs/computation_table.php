<div class="col l12 m12 s12 right-align p-r-n">
	<div class="input-field inline p-l-md z-input m-t-n m-b-md">
		<?php if($this->permission->check_permission(MODULE_TA_COMPUTATION_TABLE, ACTION_ADD)) :?>
			<button class="btn btn-success  md-trigger" data-modal="modal_computation_table_detail" onclick="modal_computation_table_detail_init('<?php echo ACTION_ADD; ?>')"><i class="flaticon-add176"></i> Add <?php echo CODE_LIBRARY_COMPUTATION_TABLE; ?></a></button>
		<?php endif; ?>
	</div>
</div>
<h1><?php echo $status; ?></h1>
<div class="pre-datatable filter-left"></div>
<div>
	<table cellpadding="0" cellspacing="0" class="table table-advanced table-layout-auto" id="computation_table">
		<thead class="teal white-text">
			<tr>
				<th width="25%">Start Date</th>
				<th width="25%">End Date</th>
				<th width="25%">Type Name</th>				
				<th width="15%">Status</th>
				<th width="10%">Actions</th>
			</tr>
			<tr class="table-filters">
				<td><input name="A.start_date" class="form-filter"></td>
				<td><input name="B.end_date" class="form-filter"></td>
				<td><input name="type_name" class="form-filter"></td>
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

<script>
	function hidepaneldelay() 
   {
	   $('#computation_table_processing').hide();
   }   						
	function hidepanel() {    
		$('#computation_table_processing').hide();					
		setTimeout(hidepaneldelay, 2000);							
 		}

</script>
