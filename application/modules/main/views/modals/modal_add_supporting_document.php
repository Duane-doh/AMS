<form id="form_supporting_document">
	<input type="hidden" name="id" id="id" value="<?php echo $id ?>"/>
	<input type="hidden" name="salt" id="salt" value="<?php echo $salt ?>"/>
	<input type="hidden" name="token" id="token" value="<?php echo $token ?>"/>
	<input type="hidden" name="action" id="action" value="<?php echo $action ?>"/>
	<input type="hidden" name="module" id="module" value="<?php echo $module ?>"/>
	<input type="hidden" name="request_id" id="request_id" value="<?php echo $request_id ?>"/>

	<div class="form-float-label">
		<div class="row m-n">
			<div class="col s6">
				<div class="input-field">
					<label for="document_type_id" class="active">Document Type<span class="required"> * </span></label>
					<select id="document_type_id" name="document_type_id" required="" class="selectize" placeholder="Select Document Type" <?php echo $action == ACTION_VIEW ? 'disabled' : '' ?>>
						<option value="">Select Document Type</option>
						<?php if (!EMPTY($document_types)): ?>
							<?php foreach ($document_types as $type): ?>
									<option value="<?php echo $type['supp_doc_type_id'] ?>"><?php echo $type['supp_doc_type_name'] ?></option>
							<?php endforeach;?>
						<?php endif;?>
					</select>
				</div>
			</div>
			<div class="col s6">
				<div class="input-field">
					<input id="date_received" name="date_received" required="" value="<?php echo isset($document['date_received'])? $document['date_received'] : '' ?>" type="text" class="validate datepicker" <?php echo $action == ACTION_VIEW ? 'disabled' : '' ?>>
					<label class="<?php echo $action != ACTION_ADD ? 'active' : '' ?>" for="date_received">Date Received<span class="required"> * </span></label>
				</div>
			</div>
		</div>		
		<div class="row m-n">
			<div class="col s12">
				<div class="input-field">
					<label for="remarks" class="<?php echo $action != ACTION_ADD ? 'active' : '' ?>">Remarks<span class="required"> * </span></label>
					<textarea type="text" name="remarks" id="remarks" class="materialize-textarea" <?php echo $action == ACTION_VIEW ? 'disabled' : '' ?>><?php echo isset($document['remarks']) ? $document['remarks'] : "" ?></textarea>				
				</div>
			</div>
		</div>
	</div>
<?php if($action != ACTION_VIEW):?>
<div class="md-footer default">
	<a class="waves-effect waves-teal btn-flat cancel_modal">Cancel</a>
    	<button class="btn btn-success " id="save_document" value="<?php echo BTN_SAVE ?>"><?php echo BTN_SAVE ?></button>
</div>
<?php endif; ?>
</form>
<script>
$(function (){
	$('#form_supporting_document').parsley();
		jQuery(document).off('submit', '#form_supporting_document');
	jQuery(document).on('submit', '#form_supporting_document', function(e){
	    e.preventDefault();
	    
		if ( $(this).parsley().isValid() ) {
			var data = $('#form_supporting_document').serialize();
			
		  	button_loader('save_document', 1);
		  	var option = {
					url  : $base_url + 'main/requests/process_supporting_document',
					data : data,
					success : function(result){
						if(result.status)						
						{
							notification_msg("<?php echo SUCCESS ?>", result.message);
							modal_add_supporting_document.closeModal();
							var post_data = {

								'request_id'	 : $('#request_id').val(),
								'request_action' : $('#action').val(),
								'request_module' : $('#module').val()
							};
							load_datatable('table_request_supporting_documents', '<?php echo PROJECT_MAIN ?>/requests/get_supporting_documents_list',false,0,0,true,post_data);
						}
						else
						{
							notification_msg("<?php echo ERROR ?>", result.message);
						}	
						
					},
					
					complete : function(jqXHR){
						button_loader('save_document', 0);
					}
			};

			General.ajax(option);    
	    }
  	});
})
</script>