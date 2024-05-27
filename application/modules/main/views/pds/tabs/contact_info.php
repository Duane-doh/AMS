<?php 
	$salt			= gen_salt();
	$token_add		= in_salt(DEFAULT_ID . '/' . ACTION_ADD  . '/' . $module, $salt);
	$url_add 		= ACTION_ADD."/".DEFAULT_ID ."/".$token_add."/".$salt."/".$module;
?>
<div class="col l12 m12 s12 right-align p-r-n">
	<?php if($action != ACTION_VIEW) : ?>
	<div class="input-field inline p-l-md z-input m-t-n m-b-md">
		<a class="btn btn-success  md-trigger" data-modal="modal_address_info" onclick="modal_address_info_init('<?php echo $url_add?>')"><i class="flaticon-add176"></i> Add <?php echo CONTACT_INFO_ADDRESS; ?></a>
	</div>
<?php endif; ?>
</div>
<div class="pre-datatable filter-left"></div>
<div class="p-b-lg">
	<table cellpadding="0" cellspacing="0" class="table table-advanced table-layout-auto" id="address_table">
		<thead>
			<tr>
			  <th width="20%">Address Type</th>
			  <th width="20%">Address</th>
			  <th width="20%">Zip Code</th>
			  <th width="10%">Actions</th>
			</tr>
			<tr class="table-filters">
				<td><input name="B-address_type_name" class="form-filter"></td>
				<td><input name="A-address_value" class="form-filter"></td>
				<td><input name="A-postal_number" class="form-filter"></td>
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
<div class="col l12 m12 s12 right-align p-r-n">
	<?php if($action != ACTION_VIEW) : ?>
		<div class="input-field inline p-l-md z-input m-t-n m-b-md">
			<a class="btn btn-success  md-trigger" data-modal="modal_contact_info" onclick="modal_contact_info_init('<?php echo $url_add?>')"><i class="flaticon-add176"></i>  Add Contact</a>
		</div>
	<?php endif; ?>	
</div>
<div class="pre-datatable filter-left"></div>
<div>
<table cellpadding="0" cellspacing="0" class="table table-advanced table-layout-auto" id="contacts_table">
	<thead>
		<tr>
		  <th width="40%">Contact Type</th>
		  <th width="40%">Contact Number</th>
		  <th width="20%">Actions</th>
		</tr>
		<tr class="table-filters">
				<td><input name="B-contact_type_name" class="form-filter"></td>
				<td><input name="A-contact_value" class="form-filter"></td>
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