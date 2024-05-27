<form id="form_other_info">
	<input type="hidden" name="id" value="<?php echo $id ?>"/>
	<input type="hidden" name="salt" value="<?php echo $salt ?>"/>
	<input type="hidden" name="token" value="<?php echo $token ?>"/>
	<input type="hidden" name="action" value="<?php echo $action ?>"/>
	<input type="hidden" name="module" value="<?php echo $module ?>"/>
	<input type="hidden" name="emp_id" id="emp_id" value="<?php echo $employee_id ?>"/>
	<div class="form-float-label">
	  <div class="row m-n">
	    <div class="col s12">
		  <div class="input-field">
		   	<label for="other_info_type_id" class="active">Information Type<span class="required">*</span></label>
			<select id="other_info_type_id" required name="other_info_type_id" <?php echo $action ==ACTION_VIEW ? 'disabled' : '' ?> class="selectize" placeholder="Select Information Type">
				<option value="">Select Information Type</option>
				<?php if (!EMPTY($info_types)): ?>
					<?php foreach ($info_types as $type): ?>
						<!-- MARVIN : EXCLUDE DESIGNATION, PROFESSIONAL AND TITLE : START -->
						<?php if(!in_array($type['other_info_type_id'], array(4,5,7))): ?>
						<option value="<?php echo $type['other_info_type_id'] ?>"><?php echo strtoupper($type['other_info_type_name']) ?></option>
						<?php endif; ?>
						<!-- MARVIN : EXCLUDE DESIGNATION, PROFESSIONAL AND TITLE : END -->
					<?php endforeach;?>
				<?php endif;?>
			</select>
		  </div>
	    </div>
	  </div>
	  <div class="row m-n">
	  	<div class="col s12">
		  <div class="input-field">
		   	<textarea type="textarea" class="materialize-textarea" required name="others_value" id="others_value" value="<?php echo isset($other_info['others_value'])? $other_info['others_value']:"" ?>" <?php echo $action==ACTION_VIEW ? 'disabled' : '' ?>/>
		    <label for="others_value">Information Details<span class="required">*</span></label>
		  </div>
	    </div>
	  </div>
	</div>	
</form>
<?php if($action != ACTION_VIEW):?>
<div class="md-footer default">
	<a class="waves-effect waves-teal btn-flat cancel_modal">Cancel</a>
    <button class="btn btn-success " id="save_other_info" value="<?php echo BTN_SAVE ?>"><?php echo BTN_SAVE ?></button>
</div>
<?php endif;?>
<script>

var edit_cnt = 0;


$('#other_info_type_id').change(function() {
   	var val   			= $(this).val();
	var params = [];
	params     = {select_id : val};
	$.post($base_url+"<?php echo PROJECT_MAIN."/pds_other_information_info/get_other_info_flag"?>",params, function(result) {

		if(result.with_info_flag == "Y"){	
			if(result.info_professional_flag == "Y"){
				var emp_id  = $('#emp_id').val();
				var param 	= [];
				param     	= {id : emp_id};
				$.post($base_url+"<?php echo PROJECT_MAIN."/pds_other_information_info/get_employee_eligibility"?>", param, function(result) {						
					$('#others_value').val(result.eligibility);					
					<?php if($action != ACTION_ADD): ?>
						if(edit_cnt == 0) {
							var others_value = "<?php echo trim(str_replace(PHP_EOL, ' ', $other_info['others_value']));?>" ;
							$('#others_value').val(others_value);
							edit_cnt++;
						}
					<?php endif?>
				}, 'json');
				$('#others_value').attr('readonly', false);
				$('.input-field label').addClass('active');
				
			}
			else 
			{			
				$('#others_value').attr('readonly', false);
				$('#others_value').val('');	
				<?php if($action != ACTION_ADD): ?>
					if(edit_cnt == 0) {
						$('#others_value').val('<?php echo $other_info["others_value"]?>');
						edit_cnt++;
					}
				<?php endif?>
			}	
			
		} 
		else 
		{
			$('#others_value').val('NA');
				$('#others_value').attr('readonly', true);
			$('.input-field label').addClass('active');
			<?php if($action != ACTION_ADD): ?>
			if(edit_cnt == 0) {
				$('#others_value').val('<?php echo $other_info["others_value"]?>');
				edit_cnt++;
			}
			<?php endif?>
		}	
	}, 'json');
});

$(function (){
	<?php if($action != ACTION_ADD){ ?>
		$('.input-field label').addClass('active');
  	<?php } ?>
	$('#form_other_info').parsley();
	jQuery(document).off('click', '#save_other_info');
	jQuery(document).on('click', '#save_other_info', function(e){	
		$("#form_other_info").trigger('submit');
	});

 	jQuery(document).off('submit', '#form_other_info');
	jQuery(document).on('submit', '#form_other_info', function(e){
	    e.preventDefault();
	    
		if ( $(this).parsley().isValid() ) {
			var data = $('#form_other_info').serialize();
			var process_url = "";
			<?php if($module == MODULE_PERSONNEL_PORTAL):?>
				process_url = $base_url + 'main/pds_record_changes_requests/process_other_info';
			<?php else: ?>
				process_url = $base_url + 'main/pds_other_information_info/process';
			<?php endif; ?>
		  	button_loader('save_other_info', 1);
		  	var option = {
					url  : process_url,
					data : data,
					success : function(result){
						if(result.status)
						{
							notification_msg("<?php echo SUCCESS ?>", result.message);
							modal_other_information.closeModal();
							load_datatable('pds_other_info_table', '<?php echo PROJECT_MAIN ?>/pds_other_information_info/get_other_info_list',false,0,0,true);
						}
						else
						{
							notification_msg("<?php echo ERROR ?>", result.message);
						}	
						
					},
					
					complete : function(jqXHR){
						button_loader('save_other_info', 0);
					}
			};

			General.ajax(option);    
	    }
  	});
})
</script>
