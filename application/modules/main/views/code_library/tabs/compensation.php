<?php
	$data_id = 'compensation/' . $action_id;
?>
<!-- <div class="col l12 m5 s7 right-align m-b-n-l-lg">

</div>
 -->

<div class="col l12 m12 s12 right-align p-r-n">
    <div class="input-field inline p-l-md z-input m-t-n-md m-b-xs">
      	<?php if($action_id != ACTION_VIEW) : ?>
	  		<button class="btn btn-success  md-trigger green" data-modal="modal_compensation" onclick="modal_compensation_init('<?php echo ACTION_ADD; ?>')">Add <?php echo CODE_LIBRARY_COMPENSATION;?></a></button>
		<?php endif; ?>
	</div>
</div>

<div class="pre-datatable filter-left"></div>
	<div>
		<table cellpadding="0" cellspacing="0" class="table table-advanced table-layout-auto" id="compensation_table">
		  <thead>
			<tr>
			  <th width="20%">Compensation Name</th>
			  <th width="20%">Compensation Code</th>
			  <th width="15%">Status</th>
			  <th width="5%">Actions</th>
			</tr>
			<tr class="table-filters">
				<td><input name="A-compensation_name" class="form-filter"></td>
				<td><input name="A-compensation_code" class="form-filter"></td>
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
