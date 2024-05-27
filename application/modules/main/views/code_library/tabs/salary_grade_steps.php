<div class="col l12 m5 s7 right-align m-b-n-l-lg">
	<button class="btn btn-success btn-success md-trigger" data-modal="modal_salary_grade_steps" onclick="modal_salary_grade_steps_init('<?php echo ACTION_ADD; ?>')">Add <?php echo CODE_LIBRARY_SALARY_GRADE_STEPS; ?></a></button>
</div>
<div class="pre-datatable filter-left">
	<table cellpadding="0" cellspacing="0" class="table table-advanced table-layout-auto" id="salary_grade_steps_table">
	  <thead>
		<tr>
		  <th width="25%">Effectivity Date</th>
		  <th width="25%">Grade Count</th>
		  <th width="25%">Steps Count</th>
		  <th width="2%">Actions</th>
		</tr>
		<tr class="table-filters">
			<td><input name="effectivity_date" class="form-filter"></td>
			<td><input name="grade_count" class="form-filter"></td>
			<td><input name="steps_counts" class="form-filter"></td>
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
