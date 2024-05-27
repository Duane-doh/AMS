
<div class="col s6 right-align m-b-n-l-lg">
	  <div class="btn-group"></div>
	  <div class="input-field inline z-input">
 		<button class="btn btn-success waves-effect waves-light md-trigger" data-modal="modal_deduction" onclick="modal_deduction_init()">Add Employees</a></button>
	  </div>
</div>

<div class="pre-datatable filter-left"></div>
<div>
	<table cellpadding="0" cellspacing="0" class="table table-advanced table-layout-auto" id="table_deduction_type_employee">
	  <thead>
		<tr>
		   	<th width="15%">Personnel Number</th>
			<th width="15%">Personnel Name</th>
			<th width="15%">Date Started</th>
			<th width="10%">Status</th>
			<th width="10%">Actions</th>
		</tr>
		<tr class="table-filters">
			<td><input name="employee_no" class="form-filter"></td>
			<td><input name="employee" class="form-filter"></td>
			<td><input name="date_started" class="form-filter"></td>
			<td><input name="status" class="form-filter"></td>
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



