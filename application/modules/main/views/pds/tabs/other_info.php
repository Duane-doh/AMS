<?php if($action != ACTION_VIEW) : ?>
<div class="col l12 m12 s12 right-align p-r-n">
	<div class="input-field inline p-l-md z-input m-t-n m-b-md">
		<?php 
			$employee_id 	= $id;
			$salt			= gen_salt();
			$token_add		= in_salt(DEFAULT_ID . '/' . ACTION_ADD  . '/' . $module, $salt);
			$url_add 		= ACTION_ADD."/".DEFAULT_ID ."/".$token_add."/".$salt."/".$module."/".$employee_id;
		?>
	 	<a class="btn btn-success  md-trigger" data-modal="modal_other_information" onclick="modal_other_information_init('<?php echo $url_add; ?>')"><i class="flaticon-add176"></i> Add Other Info</a>
	</div>
</div>
<?php ENDIF; ?>
<div class="pre-datatable filter-left"></div>
<div>
	<table cellpadding="0" cellspacing="0" class="table table-advanced table-layout-auto" id="pds_other_info_table">
		<thead>
			<tr>
				<th width="45%">Information Detail</th>
				<th width="40%">Information Type</th>
				<th width="15%">Actions</th>
			</tr>
			<tr class="table-filters">
				<td><input name="A-others_value" class="form-filter"></td>
				<td><input name="B-other_info_type_name" class="form-filter"></td>
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
