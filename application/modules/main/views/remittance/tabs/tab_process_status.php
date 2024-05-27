<div class="p-t-lg">
<form id="remittance_status_form">
	<input type="hidden" name="id" id="id" value="<?php echo !EMPTY($id) ? $id : NULL?>">
	<input type="hidden" name="salt" id="salt" value="<?php echo !EMPTY($salt) ? $salt : NULL?>">
	<input type="hidden" name="token" id="token" value="<?php echo !EMPTY($token) ? $token : NULL?>">
	<input type="hidden" name="action" id="action" value="<?php echo !EMPTY($action) ? $action : NULL?>">
	<input type="hidden" name="module" id="module" value="<?php echo !EMPTY($module) ? $module : NULL?>">
	<div class="col s6">
		<div class="form-float-label" style="border-top: solid 1px #D5D5D5">
			<div class="row">
				<div class="col s12">
					<div class="input-field">
					 <label for="remittance_type_id" class="active">Remittance Type</label>
					 <input disabled type="text" id="remittance_type_id" value="<?php echo (!EMPTY($remittance_info['remittance_type_name']) ? $remittance_info['remittance_type_name'] : '') ?>">
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col s6">
					<div class="input-field">
					 	<input disabled id="deduction_start_date" type="text" value="<?php echo (!EMPTY($remittance_info['deduction_start_date']) ? format_date($remittance_info['deduction_start_date'], 'F d, Y') : '') ?>" />
			   			<label for="deduction_start_date" class="active">Deduction Start</label>
					</div>
				</div>
				<div class="col s6">
					<div class="input-field">
					 	<input disabled id="deduction_end_date" type="text" value="<?php echo (!EMPTY($remittance_info['deduction_end_date']) ? format_date($remittance_info['deduction_end_date'], 'F d, Y') : '') ?>" />
			   			<label for="deduction_end_date" class="active">Deduction End</label>
					</div>
				</div>
			</div>
		</div>	
	</div>
	<div class="col s6">
		<div class="form-float-label" style="border-top: solid 1px #D5D5D5">
			<div class="row">
				<div class="col s12">
					<div class="input-field">
					 <label for="remittance_status_id" class="active">Status <span class="required">*</span></label>
					 <select id="remittance_status_id" name="remittance_status_id" required class="selectize" placeholder="Select status...">
						<option></option>
						<?php
						foreach($remittance_status AS $r)
						{	
							if($r['remitted_flag'] == 0 OR $remittance_info['remittance_status_id'] == REMITTANCE_REMITTED)  
								echo '<option value="' . $r['remittance_status_id'] . '">' . $r['remittance_status_name'] . '</option>';
						}
						?>		
				 	 </select>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col s12">
					<div class="input-field">
					 	<textarea class="materialize-textarea" id="remarks" name="remarks" type="text" value="" <?php echo (isset($remittance_info['remittance_status_id']) AND $remittance_info['remittance_status_id'] == REMITTANCE_REMITTED)? 'disabled':''?>></textarea>
					 	<label for="remarks" class="active">Remarks</label>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php if ($remittance_info['remittance_status_id'] != REMITTANCE_REMITTED):?>
	<div class="col s12 p-r-sm p-t-md">
		<button class="btn btn-success right" id="save_remittance_status" value="<?php echo BTN_PROCESS ?>"><?php echo BTN_PROCESS ?></button>
	</div>
	<?php endif;?>
</form>
</div>
<script>

	$(function (){
	$('#remittance_status_form').parsley();
	$('#remittance_status_form').submit(function(e) {
	    e.preventDefault();
	    
		if ( $(this).parsley().isValid() ) {
			var data = $(this).serialize();
		  	button_loader('save_remittance_status', 1);
		  	var option = {
					url  : $base_url + '<?php echo PROJECT_MAIN ?>/payroll_remittance/process_remittance_status',
					data : data,
					success : function(result){
						if(result.status)
						{
							notification_msg("<?php echo SUCCESS ?>", result.msg);
							$('#link_remittance_process').trigger('click');
						}
						else
						{
							notification_msg("<?php echo ERROR ?>", result.msg);
						}	
						
					},
					
					complete : function(jqXHR){
						button_loader('save_remittance_status', 0);
					}
			};

			General.ajax(option);    
	    }
  	});
  	
})

<?php if(ISSET($remittance_info['remittance_status_id']) AND $remittance_info['remittance_status_id'] == REMITTANCE_REMITTED) : ?>
 $('span.required').addClass('hide');
 $('#remittance_status_id').attr('disabled','');
<?php endif; ?>


$('#office_filter_div').addClass('none');

</script>