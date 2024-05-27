<?php if($action != ACTION_VIEW) : ?>
<div class="col l12 m5 s7 right-align m-b-n-l-lg">
	<div class="input-field inline z-input">
		<?php 
   			$salt			= gen_salt();
   			$token_add		= in_salt(DEFAULT_ID . '/' . ACTION_ADD  . '/' . $module, $salt);
			$url_add 		= ACTION_ADD."/".DEFAULT_ID ."/".$token_add."/".$salt."/".$module;
   		?>
<!-- 		<a class="btn btn-success  md-trigger" data-modal="modal_supporting_documents" onclick="modal_supporting_documents_init('<?php echo ACTION_ADD ?>')"><i class="flaticon-add176"></i>  Add Supporting Document</a>
 -->	</div>
</div>
<?php ENDIF; ?>
<div class="pre-datatable filter-left"></div>
<div>
	<table cellpadding="0" cellspacing="0" class="table table-advanced dataTable table-layout-auto" id="table_supporting_documents">
	  <thead>
		<tr>
		  <th width="20%">Document Type</th>
		  <th width="20%">Date Received</th>
		   <th width="20%">Remarks</th>
		  <th width="2%">Actions</th>
		</tr>
		<tr class="table-filters">
				<td><input name="A-relation_first_name" class="form-filter"></td>
				<td><input name="A-relation_last_name" class="form-filter"></td>
				<td><input name="A-relation_birth_date" class="form-filter"></td>
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