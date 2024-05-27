<form id="deduction_type_payroll_form">
	<input type="hidden" name="id" id="id" value="<?php echo !EMPTY($id) ? $id : NULL?>">
	<input type="hidden" name="salt" id="salt" value="<?php echo !EMPTY($salt) ? $salt : NULL?>">
	<input type="hidden" name="token" id="token" value="<?php echo !EMPTY($token) ? $token : NULL?>">
	<input type="hidden" name="action" id="action" value="<?php echo !EMPTY($action) ? $action : NULL?>">
	<input type="hidden" name="module" id="module" value="<?php echo !EMPTY($module) ? $module : NULL?>">

	<div class="form-float-label">
		<div class="row">
			<div class="col s12">
				<div class="input-field">
					<input disabled type="text" class="validate"  name="deduction_name" id="deduction_name" value="<?php echo !EMPTY($deduction[0]['deduction_name']) ? $deduction[0]['deduction_name'] : NULL ?>"/>
					<label class="active" for="deduction_name">Deduction Name</label>
				</div>
			</div>
		</div>
		
		<?php foreach ($payroll_types as $payroll):?>
			<div class="row">
				<div class="col s12">
					<div class="input-field">
						<input type="hidden" name="payroll_type_id_<?php echo $payroll['payroll_type_id']?>" id="payroll_type_id_<?php echo $payroll['payroll_type_id']?>" value="<?php echo !EMPTY($payroll['payroll_type_id']) ? $payroll['payroll_type_id'] : NULL?>">
						<label for="payout_num_<?php echo $payroll['payroll_type_id']?>" class="active"><?php echo $payroll['payroll_type_name']?></label>
						<select id="payout_num_<?php echo $payroll['payroll_type_id']?>" name="payout_num_<?php echo $payroll['payroll_type_id']?>[]" class="selectize validate" multiple <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>>
							<option value="">Select Payout Count</option>
							<!--marvin-->
							<option value="1">1st Cut-off</option>
							<option value="2">2nd Cut-off</option>
							<!--asiagate
							<option value="1">1st Pay In</option>
							<option value="2">2nd Pay In</option>-->
						</select>
					</div>
				</div>
			</div>
		<?php endforeach;?>

	</div>

	<div class="md-footer default">
	  	<?php if($action != ACTION_VIEW):?>
	  		<a class="waves-effect waves-teal btn-flat cancel_modal" id="cancel_deduction_type_payroll">Cancel</a>
		    <button class="btn btn-success" id="save_deduction_type_payroll" value="<?php echo BTN_SAVE ?>"><?php echo BTN_SAVE ?></button>
	  	<?php endif; ?>
	</div>
</form>
<script>

$(function (){

	var payroll_types = <?php echo json_encode($payroll_types);?>;
	

	for(ctr=0;ctr<payroll_types.length;ctr++)
	{
		$('#payout_num_'+payroll_types[ctr]['payroll_type_id']).each( function() {
			if( !$(this).hasClass( 'selectized' ) )
			{
				$(this).selectize({ maxItems: payroll_types[ctr]['payout_count']});
			}
		} );
	}
	
	$('#deduction_type_payroll_form').parsley();
	$('#deduction_type_payroll_form').submit(function(e) {
	    e.preventDefault();
	    
		if ( $(this).parsley().isValid() ) {
			var data = $(this).serialize();
		  	button_loader('save_deduction_type_payroll', 1);
		  	var option = {
					url  : $base_url + 'main/code_library_payroll/deduction_type_payroll/process',
					data : data,
					success : function(result){
						if(result.status)
						{
							notification_msg("<?php echo SUCCESS ?>", result.msg);
							modal_deduction_type_payroll.closeModal();
						}
						else
						{
							notification_msg("<?php echo ERROR ?>", result.msg);
						}	
						
					},
					
					complete : function(jqXHR){
						button_loader('save_deduction_type_payroll', 0);
					}
			};

			General.ajax(option);    
	    }
  	});

	
});

</script>