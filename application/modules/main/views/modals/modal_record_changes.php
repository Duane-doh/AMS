<form id="record_changes">
<div class="panel row">
	
	<div class="row m-n m-t-md">
		<div class="col s12">
			<div class="pre-datatable filter-left"></div>
					<diV>
		<table class="table table-advanced table-layout-auto" id = "table_request_changes">
			<thead>
				<tr>
					<th width="5%"></th>
					<th width="35%">Request Sub Type</th>
					<th width="30%">Request Action</th>
					<th width="30%">Remarks</th>
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

<script>
	function include_sub_request(action, id, token, salt, module, request_sub_id, this_type){
	
			if($(this_type).is(":checked"))
			{
				var type = "approve";
			}
			else
			{
				var type = "reject";
			}
			var data = {
				'process_action': type,
				'action'		: action,
				'id'			: id,
				'token'			: token,
				'salt'			: salt,
				'module'		: module
			};
		  	var option = {
					url  :  $base_url + 'main/requests/process_subrequest',
					data : data,
					success : function(result){
						if(result.status)
						{
							notification_msg("<?php echo SUCCESS ?>", result.message);		
							$(this_type).attr('checked', true);								
						}
						else
						{
							notification_msg("<?php echo ERROR ?>", result.message);
							$('#check_'+request_sub_id).prop('checked', true);	
						}	
						
					},
					
					complete : function(jqXHR){
					}
			};

			General.ajax(option); 
  	}
</script>