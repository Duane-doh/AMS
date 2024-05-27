
<form id="remittance_attachment_form">
	<input type="hidden" name="id" id="id" value="<?php echo !EMPTY($id) ? $id : NULL?>">
	<input type="hidden" name="salt" id="salt" value="<?php echo !EMPTY($salt) ? $salt : NULL?>">
	<input type="hidden" name="token" id="token" value="<?php echo !EMPTY($token) ? $token : NULL?>">
	<input type="hidden" name="action" id="action" value="<?php echo !EMPTY($action) ? $action : NULL?>">
	<input type="hidden" name="module" id="module" value="<?php echo !EMPTY($module) ? $module : NULL?>">
	<div class="form-float-label">
	<div class="row m-n field-multi-attachment">
		<div class="col s12">
		 
			<div id="attachment_upload">Select File</div>
		</div>
	</div>
	  <div class="row m-n m-t-md p-t-sm p-b-sm">
	    <div class="col s12">
	    	<div class="pre-datatable filter-left"></div>
	    	 	<div>
				  <table cellpadding="0" cellspacing="0" class="table table-advanced table-layout-auto" id="table_remittance_attachment">
				  	<thead>
						<tr>
							<th width="10%">Date Uploaded</th>
							<th width="30%">File Name</th>
							<th width="20%">Uploaded By</th>
							<th width="20%">Actions</th>
						</tr>
						<tr class="table-filters">
							<td><input name="A-date_uploaded" class="form-filter"></td>
							<td><input name="A-file_name" class="form-filter"></td>
							<td><input name="uploader" class="form-filter"></td>
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
  <div class="md-footer default">
		<a class="waves-effect waves-teal btn-flat cancel_modal" id="cancel_service_record">Cancel</a>
	</div>
</form>
<script>
$(function (){
	var file_size = 50;
	 file_size = file_size*1024*1024;
	var uploadObj = $("#attachment_upload").uploadFile({
		url: $base_url + "upload/",
		fileName: "file",
		allowedTypes:"*",
		acceptFiles:"*",	
		dragDrop: true,
		multiple: false,
		allowDuplicates: false,
		duplicateStrict: true,
		maxFileSize: file_size,
		showDone: true,
		showProgress: false,
		showPreview: false,
		showDelete:false,
		returnType:"json",	
		formData: {"dir":"<?php echo PATH_REMITTANCE_ATTACHMENT?>"},
		uploadFolder:$base_url + "<?php echo PATH_REMITTANCE_ATTACHMENT?>",
		onSuccess:function(files,data,xhr){

			var file_names = {
								'file_name' : data[0],
								'action' 	: $('#action').val(),
								'id' 		: $('#id').val(),
								'token' 	: $('#token').val(),
								'salt' 		: $('#salt').val(),
								'module' 	: $('#module').val()
							};
			var option = {
					url  : $base_url + '<?php echo PROJECT_MAIN ?>/payroll_remittance/process_remittance_attachment_upload',
					data : file_names,
					success : function(result){
						if(result.status)
						{
							notification_msg("<?php echo SUCCESS ?>", result.msg);
							var post_data = {
											'remittance_id':$('#id').val()
								};
							load_datatable('table_remittance_attachment', '<?php echo PROJECT_MAIN ?>/payroll_remittance/get_remittance_attachment_list',false,0,0,true,post_data);
						}
						else
						{
							notification_msg("<?php echo ERROR ?>", result.msg);
						}	
						$('.ajax-file-upload-green').trigger('click');
					}
					
			};

			General.ajax(option);  
			
		}
	});
})
</script>