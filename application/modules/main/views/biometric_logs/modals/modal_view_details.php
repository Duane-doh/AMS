<?php $data_id = 'modal_dtr_upload/' . ACTION_ADD . '/' . $id; ?>
<form id="dtr_temp_sub">
	<div class="form-float-label">
		<table cellpadding="0" cellspacing="0" class="table table-advanced table-layout-auto" id="dtr_temp_sub_list">
	  		<thead>
	            <tr>
				<th width="30%">File Name</th>
				<th width="20%">Terminal Code</th>
				<th width="30%">Uploaded Date</th>
				<th width="20%">Action</th>
	            </tr>
	            <tr class="table-filters">
	              <td><input name="B-file_name" class="form-filter"></td>
	              <td><input name="C-terminal_code" class="form-filter"></td>
	              <td><input name="A-date_uploaded" class="form-filter"></td>
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
	<div class="md-footer default">
		<a class="waves-effect waves-teal btn-flat cancel_modal" id="cancel_bank">Cancel</a>
	 </div>
</form>
<script>
$(function(){ 
	$('.tooltipped').tooltip({delay: 50});	
});
</script>