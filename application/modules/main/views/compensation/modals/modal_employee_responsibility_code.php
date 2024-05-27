	<form id="responsibility_code_form">
	<input type="hidden" name="id" value="<?php echo $id ?>"/>
	<input type="hidden" name="salt" value="<?php echo $salt ?>"/>
	<input type="hidden" name="token" value="<?php echo $token ?>"/>
	<input type="hidden" name="action" value="<?php echo $action ?>"/>
	<input type="hidden" id="module" name="module" value="<?php echo $module ?>"/>
	<input type="hidden" id="employee_id" name="employee_id" value="<?php echo $employee_id ?>"/>

	<div class="form-float-label">
		<div class="row m-n">
			<div class="col s12">
				<div class="input-field">
					<label for="responsibility_desc" class="active">Responsibility Description<span class="required">*</span></label>
					<select id="responsibility_desc" name="responsibility_desc" class="selectize" placeholder="Select Responsibility Description" required <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>>
					 <option value="">Select Responsibility Description</option>
					 <?php if (!EMPTY($responsibility_codes)): ?>
						<?php foreach ($responsibility_codes as $rccode): ?>
							<option value="<?php echo $rccode['responsibility_center_desc']?>" <?php echo ($employee_responsibility_code['responsibility_center_code'] == $rccode['responsibility_center_code'] ? ' selected' : '')?> ><?php echo strtoupper($rccode['responsibility_center_desc']) ?></option>
						<?php endforeach;?>
					<?php endif;?>
					</select>
				</div>
			</div>
		</div>
		<div class="row m-n">
			<div class="col s12">
				<div class="input-field" class="active">
					<input type="text" name="responsibility_code" id="responsibility_code" class="validate" value="<?php echo isset($employee_responsibility_code['responsibility_center_code']) ? $employee_responsibility_code['responsibility_center_code'] : NULL?>" readOnly/>			
					<label for="responsibility_code" class="<?php echo $action==ACTION_EDIT ? 'active' : 'active' ?>">Responsibility Code</label>
				  </div>

			</div>
		</div>
		<div class="row m-n">
			<div class="col s6">
				<div class="input-field">
					<label for="effective_date" class="<?php echo $action==ACTION_EDIT ? 'active' : 'active' ?>">Effectivity Date<span class="required">*</span></label>
					<input type="text" class="validate datepicker" required name="effective_date" id="effective_date" value="<?php echo isset($employee_responsibility_code['start_date']) ? format_date($employee_responsibility_code['start_date']) : NULL?>" <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?> onkeypress="format_identifications('<?php echo DATE_FORMAT ?>',this.value,event,'effective_date')"/>
				</div>
			</div>
		</div>
		<div class="row m-n">
			<div class="col s12">
				<div class="input-field" class="active">
					<textarea type="text" name="remarks" id="remarks" class="validate materialize-textarea" maxlength="255"><?php echo $employee_responsibility_code['remarks']; ?></textarea>				
					<label for="remarks" class="<?php echo $action==ACTION_EDIT ? 'active' : 'active' ?>">Remarks<span class="required">*</span></label>
				  </div>

			</div>
		</div>
	</div>
</form>

<?php if($action != ACTION_VIEW):?>
<div class="md-footer default">
	<a class="waves-effect waves-teal btn-flat cancel_modal">Cancel</a>
    	<button class="btn btn-success " type="button" id="save_responsibility_code" value="<?php echo BTN_SAVE ?>"><?php echo BTN_SAVE ?></button>
</div>
<?php endif; ?>
<script>



$(function (){
	/*
	 * THE PURPOSE OF THE 'FIRST_SEEN' IS TO PREVENT
	 * THE JQUERY TO CLEAR THE VALUE OF IDENTIFICATION ON EDIT PROCESS
	 */
	var first_seen = true;
	
	<?php if($action == ACTION_ADD){ ?>
		$('#effective_date').datepicker('destroy');
		$('#effective_date').attr("autocomplete", "off");
	<?php } ?>

	$('#responsibility_code_form').parsley();
	jQuery(document).off('click', '#save_responsibility_code');
	jQuery(document).on('click', '#save_responsibility_code', function(e){	
		$("#responsibility_code_form").trigger('submit');
	});

 	jQuery(document).off('submit', '#responsibility_code_form');
	jQuery(document).on('submit', '#responsibility_code_form', function(e){
	    e.preventDefault();
	    
		if ( $(this).parsley().isValid() ) {
			var data = $('#responsibility_code_form').serialize();
			var process_url = "";
			<?php if($module == MODULE_HR_COMPENSATION):?>
				process_url = $base_url + 'main/Compensation/process_employee_responsibility_code';
			<?php endif; ?>
		  	button_loader('save_responsibility_code', 1);
		  	var option = {
					url  : process_url,
					data : data,
					success : function(result){
						
						if(result.status)
						{
							notification_msg("<?php echo SUCCESS ?>", result.msg);
							modal_employee_responsibility_code.closeModal();
							var post_data = {
											'employee_id':$('#employee_id').val(),
											'module':$('#module').val()
								};
							load_datatable('table_employee_responsibility_code', '<?php echo PROJECT_MAIN ?>/compensation/get_employee_responsibility_code',false,0,0,true,post_data)
						}
						else
						{
							notification_msg("<?php echo ERROR ?>", result.msg);
						}	
						
					},
					
					complete : function(jqXHR){
						button_loader('save_responsibility_code', 0);
					}
			};

			General.ajax(option);    
	    }
  	});
	
	
var responsibility_desc = $('#responsibility_desc').selectize();
var responsibility_codes = <?php echo json_encode($responsibility_codes) ?>;

responsibility_desc.selectize().on('change', function() {
	
	responsibility_desc = $('#responsibility_desc').val();
	
	for(var i=0; i < responsibility_codes.length; i++)
	{
		if(responsibility_codes[i]['responsibility_center_desc'] == responsibility_desc)
			$("#responsibility_code").val(responsibility_codes[i]['responsibility_center_code']);  
	}
});
	
<?php if($action == ACTION_VIEW){ ?>
		$('label .required').addClass('none');
		$('.validate').attr('disabled','');
<?php } ?>

})
</script>