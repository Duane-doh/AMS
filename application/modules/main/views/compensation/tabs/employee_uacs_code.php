<?php if($action != ACTION_VIEW) : ?>
<div class="col l12 m12 s12 right-align p-r-n">
	<div class="input-field inline p-l-md z-input m-t-n m-b-md">
		<?php 
			$salt      = gen_salt();
			$token_add = in_salt(DEFAULT_ID . '/' . ACTION_ADD  . '/' . $module, $salt);
			$url_add   = ACTION_ADD."/".DEFAULT_ID ."/".$token_add."/".$salt."/".$module."/".$employee_id;
		?>
	  	<a class="btn btn-success  md-trigger " data-modal="modal_employee_uacs_code" onclick="modal_employee_uacs_code_init('<?php echo $url_add; ?>')"><i class="flaticon-add176"></i> Add UACS Code</a>
	</div>
</div>
<?php ENDIF; ?>
<div class="pre-datatable filter-left"></div>
<div>
	<table cellpadding="0" cellspacing="0" class="table table-advanced table-layout-auto" id="table_employee_uacs_code">
	  <thead>
		<tr>
		  <th width="10%">Start Date</th>
		  <th width="10">End Date</th>
		  <th width="20%">UACS Object Code</th>
		  <th width="30%">Account Title</th>
		  <th width="20%">Remarks</th>
		  <th width="10%">Actions</th>
		</tr>
		<tr class="table-filters">
			<td><input name="A-start_date" class="form-filter"></td>
			<td><input name="A-end_date" class="form-filter"></td>
			<td><input name="A-uacs_object_code" class="form-filter"></td>
			<td><input name="B-account_title" class="form-filter"></td>
			<td><input name="A-remarks" class="form-filter"></td>
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