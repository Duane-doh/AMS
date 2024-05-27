<form id="process_voucher_form">
	<input type="hidden" name="id"  id="id" value="<?php echo $id ?>"/>
	<input type="hidden" name="salt" value="<?php echo $salt ?>"/>
	<input type="hidden" name="token" value="<?php echo $token ?>"/>
	<input type="hidden" name="action" value="<?php echo $action ?>"/>
	<input type="hidden" name="module" value="<?php echo $module ?>"/>	
		<div class="form-float-label">
			
			<div class="row">
				<div class="col s12">
					<div class="input-field">
					  	<label for="payout_status" class="active">Payout Status <span class="required"> * </span></label>
						 <select id="payout_status"  name="payout_status" class="selectize" placeholder="Select Payout Status " required>
							<option value="">Select Payout Status </option>	
							 <?php if (!EMPTY($payout_status)): ?>
								<?php foreach ($payout_status as $status): ?>
									<option value="<?php echo $status['payout_status_id'] ?>"><?php echo $status['payout_status_name'] ?></option>
								<?php endforeach;?>
							<?php endif;?>		
					 	 </select>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col s12">
					<div class="input-field">
					 	<textarea name="remarks" class="materialize-textarea" id="remarks" ></textarea>
			   			<label for="remarks">Remarks</label>
					</div>
				</div>
			</div>
		</div>
	<div class="md-footer default m-t-xl">
		<a class="btn-flat cancel_modal">Cancel</a>
	    <button id="save_voucher" class="btn btn-success ">Save</button>
	</div>
</form>
<script>
$(document).ready(function(){
	$('#process_voucher_form').parsley();
	jQuery(document).off('submit', '#process_voucher_form');
	jQuery(document).on('submit', '#process_voucher_form', function(e){
	    e.preventDefault();
	    
		if ( $(this).parsley().isValid() ) {
			var data = $('#process_voucher_form').serialize();
		  	button_loader('save_voucher', 1);
		  	var option = {
					url  : $base_url + 'main/voucher_process/process_voucher',
					data : data,
					success : function(result){
						if(result.status)
						{
							notification_msg("<?php echo SUCCESS ?>", result.message);
							modal_process_voucher.closeModal();
							var post_data = {
											'payroll_summary_id':$('#id').val()
								};
							load_datatable('table_voucher_history', '<?php echo PROJECT_MAIN ?>/voucher_process/get_voucher_history_list',false,0,0,true,post_data);
						}
						else
						{
							notification_msg("<?php echo ERROR ?>", result.message);
						}	
						
					},
					
					complete : function(jqXHR){
						button_loader('save_voucher', 0);
					}
			};

			General.ajax(option);    
	    }
  	});
	});
</script>
	