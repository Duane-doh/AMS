<?php if($action != ACTION_VIEW) : ?>
<div class="col l12 m12 s12 right-align p-r-n">
	<div class="input-field inline p-l-md z-input m-t-n m-b-md">
		<?php 
			$salt			= gen_salt();
			$token_add	= in_salt(DEFAULT_ID . '/' . ACTION_ADD  . '/' . $module, $salt);
			$url_add 		= ACTION_ADD."/".DEFAULT_ID ."/".$token_add."/".$salt."/".$module;
		?>
	  	<a class="btn btn-success  md-trigger " data-modal="modal_profession" onclick="modal_profession_init('<?php echo $url_add; ?>')"><i class="flaticon-add176"></i> Add <?php echo SUB_MENU_PROFESSION; ?></a>
	</div>
</div>
<?php ENDIF; ?>
<div class="pre-datatable filter-left"></div>
<div>
	<table cellpadding="0" cellspacing="0" class="table table-advanced table-layout-auto" id="profession_table">
		  <thead>
			<tr>
			  <th width="20%">Profession Type</th>
			  <th width="2%">Actions</th>
			</tr>
			<tr class="table-filters">
				<td><input name="profession_name" class="form-filter"></td>
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