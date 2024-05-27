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
					<tbody>
						<tr>
							<td>Document 1</td>
						</tr>
						<tr>
							<td>Document 2</td>
						</tr>
						<tr>
							<td>Document 3</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<div class="md-footer default">
		<button class="waves-effect waves-teal btn cancel_modal" >Cancel</a>
		<?php //if($this->permission->check_permission(MODULE_ROLE, ACTION_SAVE)):?>
		<!-- <button class="btn " id="save_service_record" value="<?php echo BTN_SAVE ?>"><?php echo BTN_SAVE ?></button> -->
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
				url  : $base_url + 'main/service_record/process_employee_service_record',
				data : data,
				success : function(result){
				if(result.status)
				{
					modal_service_record.closeModal();
					load_datatable('table_employee_service_record', '<?php echo PROJECT_MAIN ?>/service_record/get_employee_service_record/',false,0,0,true);
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
