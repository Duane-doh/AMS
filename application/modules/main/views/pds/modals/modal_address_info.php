<form id="form_address">
	<input type="hidden" name="id" value="<?php echo $id ?>"/>
	<input type="hidden" name="salt" value="<?php echo $salt ?>"/>
	<input type="hidden" name="token" value="<?php echo $token ?>"/>
	<input type="hidden" name="action" value="<?php echo $action ?>"/>
	<input type="hidden" name="module" value="<?php echo $module ?>"/>
	
	<div class="form-float-label">
		<div class="row m-n">
			<div class="col s6">
				<div class="input-field">
					<label for="address_type_id" class="active">Address Type<span class="required">*</span></label>
					<select id="address_type_id" name="address_type_id" class="selectize" placeholder="Select Address Type" required>
						<option value="">Select Address Type</option>
						<?php if (!EMPTY($address_types)): ?>
							<?php foreach ($address_types as $type): ?>
									<option value="<?php echo $type['address_type_id'] ?>"><?php echo strtoupper($type['address_type_name']) ?></option>
							<?php endforeach;?>
						<?php endif;?>
					</select>
				</div>
			</div>
			<div class="col s6">
				<div class="input-field">
					<input id="postal_number" name="postal_number" onkeypress="return isNumberKey(event)"
							value="<?php echo !EMPTY($address_info['postal_number']) ? $address_info['postal_number'] : "" ?>" type="text" class="validate">
					<label for="postal_number">Zip Code</label>
				</div>
			</div>
		</div>
		<div class="row m-n">
			<div class="col s12">
				<div class="input-field">
					<label for="municipality_residential" class="active">Barangay/Municipality/Province/Region<span class="required">*</span></label>
					<select tabindex="19" id="municipality_residential" name="municipality_residential" required class="selectize" placeholder="Select Barangay/Municipality/Province/Region">
						<option value="">Select Municipality</option>
						<?php
										echo $address_value;
									?>
					</select>
				</div>
			</div>
		</div>
		<div class="row m-n">
			<div class="col s12">
				<div class="input-field">
					<label for="address_value" class="active">Address<span class="required">*</span></label>
					<input placeholder="House Number or Lot/Blk/Phase, Streetname" type="text" name="address_value" id="address_value" required class="validate" value="<?php echo !EMPTY($address_info['address_value']) ? $address_info['address_value'] : "" ?>">				
				</div>
			</div>
		</div>
	</div>
	<div class="md-footer default">
		<a class="waves-effect waves-teal btn-flat cancel_modal">Cancel</a>
		<button class="btn btn-success " type="button" id="save_address" value="<?php echo BTN_SAVE ?>"><?php echo BTN_SAVE ?></button>	  
	</div>
</form>
<script>
	// ALLOWS NUMERIC INPUT ONLY
	function isNumberKey(evt)
	{
	    var charCode = (evt.which) ? evt.which : event.keyCode
	    if (charCode != 43 && charCode > 31 && (charCode < 48 || charCode > 57))
	        return false;
	    return true;
	}

$(function (){
	<?php if($action != ACTION_ADD){ ?>
		$('.input-field label').addClass('active');
  	<?php } ?>
	jQuery(document).off('change', '#region');
	jQuery(document).on('change', '#region', function(e){	

		var province = $("#province")[0].selectize;
		var data = {'region_code': $(this).val()};
		
		province.disable();
		$.post($base_url + 'main/pds_contact_info/get_province/', data, function(result)
		{
			province.removeItem( $("#region").val());
			province.clear();
			province.clearOptions();

			province.load(function(callback) {
				callback(result.list);
				province.enable();

				<?php if(ISSET($address['province_code'])){ ?>
					$("#province")[0].selectize.setValue('<?php echo $address["province_code"] ?>');				
				<?php } ?>
			});
		 				  
		}, 'json');
	});
	jQuery(document).off('change', '#province');
	jQuery(document).on('change', '#province', function(e){	
		
		var municipality = $("#municipality")[0].selectize;
		var data = {'province_code': $(this).val()};
		
		municipality.disable();
		$.post($base_url + 'main/pds_contact_info/get_municipality/', data, function(result)
		{
			municipality.removeItem( $("#province").val());
			municipality.clear();
			municipality.clearOptions();

			municipality.load(function(callback) {
				callback(result.list);
				municipality.enable();

				<?php if(ISSET($address['municity_code'])){ ?>
					$("#municipality")[0].selectize.setValue('<?php echo $address["municity_code"] ?>');				
				<?php } ?>
			});
		 				  
		}, 'json');
	});
	$('#form_address').parsley();
	jQuery(document).off('click', '#save_address');
	jQuery(document).on('click', '#save_address', function(e){	
		$("#form_address").trigger('submit');
	});

 	jQuery(document).off('submit', '#form_address');
	jQuery(document).on('submit', '#form_address', function(e){
	    e.preventDefault();
	    
		if ( $(this).parsley().isValid() ) {
			var data = $('#form_address').serialize();
			var process_url = "";
			<?php if($module == MODULE_PERSONNEL_PORTAL):?>
				process_url = $base_url + 'main/pds_record_changes_requests/process_address';
			<?php else: ?>
				process_url = $base_url + 'main/pds_contact_info/process_address';
			<?php endif; ?>
		  	button_loader('save_address', 1);
		  	var option = {
					url  : process_url,
					data : data,
					success : function(result){
						if(result.status)
						{
							notification_msg("<?php echo SUCCESS ?>", result.message);
							modal_address_info.closeModal();
							load_datatable('address_table', '<?php echo PROJECT_MAIN ?>/pds_contact_info/get_address_list',false,0,0,true);
						}
						else
						{
							notification_msg("<?php echo ERROR ?>", result.message);
						}	
						
					},
					
					complete : function(jqXHR){
						button_loader('save_address', 0);
					}
			};

			General.ajax(option);    
	    }
  	});
})
</script>