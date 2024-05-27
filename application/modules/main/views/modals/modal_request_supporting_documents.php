<form id="record_changes">
<div class="panel row">
	
	<div class="row m-n m-t-md">
		<?php if($module != MODULE_PORTAL_MY_REQUESTS AND $action != ACTION_VIEW):?>
		<div class="col s12 m-b-md">
			<?php 
				$salt			= gen_salt();
				$token_add		= in_salt(DEFAULT_ID . '/' . ACTION_ADD  . '/' . $module . '/' .$id, $salt);
				$url_add 		= ACTION_ADD."/".DEFAULT_ID ."/".$token_add."/".$salt."/".$module. '/' .$id;
			?>
			<a id="supporting_documents" href='javascript:;' class='btn btn-success md-trigger pull-right' data-modal='modal_add_supporting_document' data-position='bottom' data-delay='50' onclick="modal_add_supporting_document_init('<?php echo isset($url_add ) ? $url_add  :''?>')"><span class="m-l-xs white-text">Add Supporting Document</span></a>
		</div>
		<?php endif;?>
		<div class="col s12">
			<div class="pre-datatable filter-left"></div>
					<diV>
		<table class="table table-advanced table-layout-auto" id = "table_request_supporting_documents">
			<thead>
				<tr>
					<th width="15%">Date Received</th>
					<th width="35%">Supporting Document Type</th>
					<th width="35%">Remarks</th>
					<th width="15%">Actions</th>
				</tr>
				  <tr class="table-filters">
				  	<td><input name="A-date_received" class="form-filter"></td>
			        <td><input name="B-supp_doc_type_name" class="form-filter"></td>
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
	</div>
		</div>
	</div>
</div>
</form>
<div class="md-footer default">
	<a class="waves-effect waves-teal btn-flat cancel_modal none">Cancel</a>
</div>