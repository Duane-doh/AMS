<form id="form_profession">
	<input type="hidden" name="id" value="<?php echo $id ?>"/>
	<input type="hidden" name="salt" value="<?php echo $salt ?>"/>
	<input type="hidden" name="token" value="<?php echo $token ?>"/>
	<input type="hidden" name="action" value="<?php echo $action ?>"/>
	<input type="hidden" name="module" value="<?php echo $module ?>"/>

	<div class="form-float-label">
		<div class="row m-n">
			<div class="col s12">
				<div class="input-field">
					<label for="profession_id" class="active">Profession Type<span class="required">*</span></label>
					<select id="profession_id" name="profession_id" class="selectize" required placeholder="Select profession Type">
						<option value="">Select Profession Type</option>
						<?php if (!EMPTY($profession_type)): ?>
							<?php foreach ($profession_type as $type): ?>
									<option value="<?php echo $type['profession_id'] ?>"><?php echo strtoupper($type['profession_name']) ?></option>
							<?php endforeach;?>
						<?php endif;?>
					</select>
				</div>
			</div>
		</div>
	</div>

	<div class="none" id="others_div">
		<div class="form-float-label">
			<div class="row">
				<div class="col s12">
					<div class="input-field">
						<input type="text" class="validate" name="others_specify" id="others_specify" value="<?php echo isset($profession['others_specify']) ? $profession['others_specify'] : NULL; ?>"/>
						<label class="" for="others_specify">Please Specify<span class="required">*</span></label>
					</div>
				</div>
			</div>
		</div>
	</div>
</form>
<?php if($action != ACTION_VIEW):?>
<div class="md-footer default">
	<a class="waves-effect waves-teal btn-flat cancel_modal">Cancel</a>
    <button class="btn btn-success " type="button" id="save_profession" value="<?php echo BTN_SAVE ?>"><?php echo BTN_SAVE ?></button>
</div>

<?php endif;?>
<script>
$('#profession_id').on( "change", function() {
	var selected = $(this).val();

	if (selected == '<?php echo $other_id?>') 
	{
		$('#others_div').removeClass('none');
   		$('#others_specify').prop('required', true);
	}
	else
	{
		$('#others_div').addClass('none');
   		$('#others_specify').prop('required', false);
	}
});

$(function (){
	<?php if($action != ACTION_ADD){ ?>
		$('.input-field label').addClass('active');
  	<?php } ?>

	$('#form_profession').parsley();
	jQuery(document).off('click', '#save_profession');
	jQuery(document).on('click', '#save_profession', function(e){	
		$("#form_profession").trigger('submit');
	});

 	jQuery(document).off('submit', '#form_profession');
	jQuery(document).on('submit', '#form_profession', function(e){
	    e.preventDefault();
	    
		if ( $(this).parsley().isValid() ) {
			var data = $('#form_profession').serialize();
			var process_url = "";
			<?php if($module == MODULE_PERSONNEL_PORTAL):?>
				process_url = $base_url + 'main/pds_record_changes_requests/process_profession';
			<?php else: ?>
				process_url = $base_url + 'main/pds_profession_info/process';
			<?php endif; ?>
		  	button_loader('save_profession', 1);
		  	var option = {
					url  : process_url,
					data : data,
					success : function(result){
						if(result.status)
						{
							notification_msg("<?php echo SUCCESS ?>", result.message);
							modal_profession.closeModal();
							load_datatable('profession_table', '<?php echo PROJECT_MAIN ?>/pds_profession_info/get_profession_list',false,0,0,true);
						}
						else
						{
							notification_msg("<?php echo ERROR ?>", result.message);
						}	
						
					},
					
					complete : function(jqXHR){
						button_loader('save_profession', 0);
					}
			};

			General.ajax(option);    
	    }
  	});
})
</script>