<?php
	if($contacts['contact_value'])
	{
		if ($contacts['contact_type_id'] == MOBILE_NUMBER2)
		{
			$con = format_identifications($contacts['contact_value'], CELLPHONE_FORMAT);
		}
		else
		{
			$con = $contacts['contact_value'];
		}		
	}
?>

<form id="form_contact">
	<input type="hidden" name="id" value="<?php echo $id ?>"/>
	<input type="hidden" name="salt" value="<?php echo $salt ?>"/>
	<input type="hidden" name="token" value="<?php echo $token ?>"/>
	<input type="hidden" name="action" value="<?php echo $action ?>"/>
	<input type="hidden" name="module" value="<?php echo $module ?>"/>

	<!-- OLD VALUES-->
	<input type="hidden" name="action" value="<?php echo $action ?>"/>
	<input type="hidden" name="module" value="<?php echo $module ?>"/>
	<!-- /OLD VALUES -->

	<div class="form-float-label">
	  <div class="row m-n">
	    <div class="col s12">
		  <div class="input-field">
		  	<label for="contact_type" class="active">Contact Type<span class="required">*</span></label>
			<select id="contact_type" name="contact_type" class="selectize" placeholder="Select Contact Type" required>
				  <option value="">Select Contact Type </option>
				 <?php if (!EMPTY($contact_types)): ?>
					<?php foreach ($contact_types as $type): ?>
						<option value="<?php echo $type['contact_type_id'] ?>"><?php echo strtoupper($type['contact_type_name']) ?></option>
					<?php endforeach;?>
				<?php endif;?>
			</select>
	      </div>
	    </div>
	    
	  </div>
	   <div class="row m-n">
	   	 <div class="col s12">
		  <div class="input-field">
		    <input id="contact_value" name="contact_value" required value="<?php echo $con ?>" type="text" class="validate">
		    <label for="contact_value">Contact<span class="required">*</span></label>
		  </div>
	    </div>
	   </div>
	  </div>
	</div>
<div class="md-footer default">
	<a class="waves-effect waves-teal btn-flat  cancel_modal">Cancel</a>
    <button class="btn btn-success " type="button" id="save_contact" value="<?php echo BTN_SAVE ?>"><?php echo BTN_SAVE ?></button>
</div>
</form>
<script>

$('#contact_type').on("change", function(){	
	$('#contact_value').attr('type', 'text');
	$('#contact_value').removeAttr('onkeypress');	

	var value = $(this).val();
	if(value == '<?php echo MOBILE_NUMBER2 ?>') {		
		$('#contact_value').attr('onkeypress','format_identifications("<?php echo CELLPHONE_FORMAT ?>",this.value,event,"contact_value")');
	}
	if(value == '<?php echo EMAIL2 ?>') {	
		$('#contact_value').attr('type', 'email');
	}
});

$(function (){
	<?php if($action != ACTION_ADD){ ?>
		$('.input-field label').addClass('active');
  	<?php } ?>
	$('#form_contact').parsley();
	jQuery(document).off('click', '#save_contact');
	jQuery(document).on('click', '#save_contact', function(e){	
		$("#form_contact").trigger('submit');
	});

 	jQuery(document).off('submit', '#form_contact');
	jQuery(document).on('submit', '#form_contact', function(e){
	    e.preventDefault();
	    
		if ( $(this).parsley().isValid() ) {
			var data = $('#form_contact').serialize();
			var process_url = "";
			<?php if($module == MODULE_PERSONNEL_PORTAL):?>
				process_url = $base_url + 'main/pds_record_changes_requests/process_contact';
			<?php else: ?>
				process_url = $base_url + 'main/pds_contact_info/process_contact';
			<?php endif; ?>
		  	button_loader('save_contact', 1);
		  	var option = {
					url  : process_url,
					data : data,
					success : function(result){
						if(result.status)
						{
							notification_msg("<?php echo SUCCESS ?>", result.message);
							modal_contact_info.closeModal();
							load_datatable('contacts_table', '<?php echo PROJECT_MAIN ?>/pds_contact_info/get_contact_list',false,0,0,true);
						}
						else
						{
							notification_msg("<?php echo ERROR ?>", result.message);
						}	
						
					},
					
					complete : function(jqXHR){
						button_loader('save_contact', 0);
					}
			};

			General.ajax(option);    
	    }
  	});
})
</script>