<form id="form_reference">
	<input type="hidden" name="id" value="<?php echo $id ?>"/>
	<input type="hidden" name="salt" value="<?php echo $salt ?>"/>
	<input type="hidden" name="token" value="<?php echo $token ?>"/>
	<input type="hidden" name="action" value="<?php echo $action ?>"/>
	<input type="hidden" name="module" value="<?php echo $module ?>"/>
	<div class="form-float-label">
	  <div class="row m-n">
	    <div class="col s12">
		  <div class="input-field">
		    <input type="text" class="validate" required="" aria-required="true" name="reference_full_name" id="reference_full_name" value="<?php echo isset($reference['reference_full_name'])? $reference['reference_full_name']:"" ?>"/>
		    <label for="reference_full_name">Full Name<span class="required">*</span></label>
	      </div>
	    </div>
	  </div>
	  <div class="row m-n">
	    <div class="col s12">
		  <div class="input-field">
		   	<input type="text" class="validate" required="" aria-required="true" name="reference_address" id="reference_address" value="<?php echo isset($reference['reference_address'])? $reference['reference_address']:"" ?>"/>
		    <label for="reference_address">Address<span class="required">*</span></label>
		  </div>
	    </div>
	  </div>
	  <div class="row m-n">
	    <div class="col s12">
		  <div class="input-field">
		   	<input type="text" class="validate" required="" aria-required="true" name="reference_contact_info" id="reference_contact_info"
		    
		   	value="<?php echo !EMPTY($reference['reference_contact_info']) ? $reference['reference_contact_info'] :'' ?>"
									type="text" class="validate"
									onkeydown="return ( event.shiftKey || event.ctrlKey || event.altKey 
										                    || (47<event.keyCode 
									&& event.keyCode<58 && event.shiftKey==false) || (95
								<event.keyCode && event.keyCode<106) || (event.keyCode==8) ||
								(event.keyCode==9) || (event.keyCode>34 && event.keyCode<40) ||
								(event.keyCode==46) || (event.keyCode==173) || (event.charCode <
								44 && event.charCode > 39))">


		    <label for="reference_contact_info">Telephone Number<span class="required">*</span></label>
		  </div>
	    </div>
	</div>
	</div>	
</form>
<?php if($action != ACTION_VIEW):?>
<div class="md-footer default">
	<a class="waves-effect waves-teal btn-flat cancel_modal">Cancel</a>
    <button class="btn btn-success " id="save_reference" value="<?php echo BTN_SAVE ?>"><?php echo BTN_SAVE ?></button>
</div>
<?php endif;?>
<script>
$(function (){
	<?php if($action != ACTION_ADD){ ?>
		$('.input-field label').addClass('active');
  	<?php } ?>
	$('#form_reference').parsley();
	jQuery(document).off('click', '#save_reference');
	jQuery(document).on('click', '#save_reference', function(e){	
		$("#form_reference").trigger('submit');
	});

 	jQuery(document).off('submit', '#form_reference');
	jQuery(document).on('submit', '#form_reference', function(e){
	    e.preventDefault();
	    
		if ( $(this).parsley().isValid() ) {
			var data = $('#form_reference').serialize();
			var process_url = "";
			<?php if($module == MODULE_PERSONNEL_PORTAL):?>
				process_url = $base_url + 'main/pds_record_changes_requests/process_reference';
			<?php else: ?>
				process_url = $base_url + 'main/pds_references_info/process';
			<?php endif; ?>
		  	button_loader('save_reference', 1);
		  	var option = {
					url  : process_url,
					data : data,
					success : function(result){
						if(result.status)
						{
							notification_msg("<?php echo SUCCESS ?>", result.message);
							modal_references.closeModal();
							load_datatable('pds_references_table', '<?php echo PROJECT_MAIN ?>/pds_references_info/get_reference_list',false,0,0,true);
						}
						else
						{
							notification_msg("<?php echo ERROR ?>", result.message);
						}	
						
					},
					
					complete : function(jqXHR){
						button_loader('save_reference', 0);
					}
			};

			General.ajax(option);    
	    }
  	});
})
</script>

