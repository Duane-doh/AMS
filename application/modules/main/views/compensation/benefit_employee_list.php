<div class="col s12 p-r-sm right-align">
	<div class="input-field inline p-l-md">
		<button class="btn btn-success  md-trigger" type="button" data-modal="modal_benefits_filters_by" onclick="modal_benefits_filters_by_init('<?php echo ACTION_ADD."/".$employee_id ?>')"><i class="flaticon-add175"></i> Add <?php echo SUB_MENU_BENEFITS; ?></button>
	</div>
</div>
<div class="pre-datatable filter-left"></div>
<div>
	<table cellpadding="0" cellspacing="0" class="table table-advanced table-layout-auto" id="table_benefit_employee_list">
		<thead> 
			<tr>
				<th width="20%">Employee ID</th>
				<th width="20%">Name</th>
				<th width="20%">Start Date</th>
				<th width="10%">Action</th>
			</tr>
			<tr class="table-filters">
				<td><input name="employee_id" class="form-filter"></td>
				<td><input name="employee_name" class="form-filter"></td>
				<td><input name="start_date" class="form-filter"></td>
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


