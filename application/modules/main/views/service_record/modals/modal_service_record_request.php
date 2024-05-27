<form id="service_record_request_form">
	<input type="hidden" name="id" id="id" value="<?php echo !EMPTY($id) ? $id : NULL?>">
	<input type="hidden" name="salt" id="salt" value="<?php echo !EMPTY($salt) ? $salt : NULL?>">
	<input type="hidden" name="token" id="token" value="<?php echo !EMPTY($token) ? $token : NULL?>">
	<input type="hidden" name="action" id="action" value="<?php echo !EMPTY($action) ? $action : NULL?>">
	<input type="hidden" name="module" id="module" value="<?php echo !EMPTY($module) ? $module : NULL?>">

	<div class="scroll-pane" style="height: 300px">
		<div class="row p-md">
			<div class="col s12">
				<table class="striped table-default">
					<thead class="green white-text">
						<tr>
							<td width = "20%" class="font-semibold">Action Type</td>
							<td width = "70" class="font-semibold">Request Count</td>
							<td width = "10%"></td>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>Add</td>
							<td>1</td>
							<td>
								<div class="table-actions"><a href='javascript:;' onclick='' class='delete tooltipped' data-tooltip='Delete' data-position='bottom' data-delay='50'></a></div>
							</td>
						</tr>
						<tr>
							<td>Update</td>
							<td>2</td>
							<td>
								<div class="table-actions"><a href='javascript:;' onclick='' class='delete tooltipped' data-tooltip='Delete' data-position='bottom' data-delay='50'></a></div>
							</td>
						</tr>
						<tr>
							<td>Delete</td>
							<td>3</td>
							<td>
								<div class="table-actions"><a href='javascript:;' onclick='' class='delete tooltipped' data-tooltip='Delete' data-position='bottom' data-delay='50'></a></div>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<div class="md-footer default">
		<a class="waves-effect waves-teal btn-flat cancel_modal">Cancel</a>
		<?php //if($this->permission->check_permission(MODULE_ROLE, ACTION_SAVE)):?>
		<!--  <button class="btn " id="save_service_record" value="<?php echo BTN_SAVE ?>"><?php echo BTN_SAVE ?></button> -->
		<a  id="save_service_record" class="btn md-trigger" data-modal='modal_service_record_document' data-position='bottom' data-delay='50' onclick="modal_service_record_document_init()">Save</a>
		<?php //endif; ?>
	</div>	
</form>

<script>
$(function (){
	<?php if($action != ACTION_ADD){ ?>
		$('.input-field label').addClass('active');
  	<?php } ?>


	$('#service_record_request_form').parsley();
	$('#service_record_request_form').submit(function(e) {
	    e.preventDefault();
	    
		if ( $(this).parsley().isValid() ) {
			var data = $(this).serialize();
		  	button_loader('save_service_record', 1);
		  	var option = {
					url  : $base_url + 'main/service_record/process_service_record_request',
					data : data,
					success : function(result){
						if(result.status)
						{
							modal_service_record_request.closeModal();
							//load_datatable('table_employee_service_record', '<?php echo PROJECT_MAIN ?>/service_record/get_employee_service_record/',false,0,0,true);
						}
						else
						{
							notification_msg("<?php echo ERROR ?>", result.message);
						}	
					},
					complete : function(jqXHR){
						button_loader('save_service_record', 0);
					}
			};

			General.ajax(option);    
	    }
  	});
})
</script>
