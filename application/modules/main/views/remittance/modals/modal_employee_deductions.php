<form id="remittance_form">
	<input type="hidden" name="remittance_id" id="remittance_id" value="<?php echo !EMPTY($remittance_id) ? $remittance_id : NULL?>">
	<input type="hidden" name="employee_id" id="employee_id" value="<?php echo !EMPTY($employee_id) ? $employee_id : NULL?>">
	<input type="hidden" name="salt" id="salt" value="<?php echo !EMPTY($salt) ? $salt : NULL?>">
	<input type="hidden" name="token" id="token" value="<?php echo !EMPTY($token) ? $token : NULL?>">
	<input type="hidden" name="action" id="action" value="<?php echo !EMPTY($action) ? $action : NULL?>">
	<input type="hidden" name="module" id="module" value="<?php echo !EMPTY($module) ? $module : NULL?>">
	<div class="form-float-label">
	    <div class="p-sm p-t-md">
			<div class="col s12 p-t-sm form-basic">
				<table class="striped table-default" id="details_table">
					<thead class="teal white-text">
						<tr>
							<td width = "25%" class="font-semibold">Deduction</span></td>
							<td width = "20%" class="font-semibold">Effectivity Date</span></td>
							<td width = "20%" class="font-semibold">Reference</td>
							<td width = "15%" class="font-semibold">Amount</td>
							<td width = "20%" class="font-semibold">Remarks</td>
						</tr>
					</thead>
					<tbody id="div_rows">
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<div class="md-footer default p-t-n">
		<?php if($action != ACTION_VIEW):?>
				<a class="waves-effect waves-teal btn-flat cancel_modal">Cancel</a>
				<button class="btn btn-success" id="save_payout_details" value="<?php echo BTN_SAVE ?>"><?php echo BTN_SAVE ?></button>
		<?php endif; ?>
	</div>   
</form>     


<script type="text/javascript">
$(document).ready(function(){
	var payout_details = <?php echo json_encode($deduction_list) ?>;
	var action = <?php echo $action ?>;
	var result = '';
	for(var i=0 ; i < payout_details.length; i++)
	{
		result+= '<tr>';
		result+='<td><p class="p-n">' + payout_details[i]['deduction_name'] + '</p></td>';
		result+='<td><p class="center p-n">' + payout_details[i]['effective_date'] +'</p></td>';
		result+='<td><p class="p-n">' + (payout_details[i]['reference_text'] != null ? payout_details[i]['reference_text'] : '') + '</p></td>';
		result+='<td>' + ( action == <?php echo ACTION_VIEW ?> ? ('<p class="right p-n">'+number_format(payout_details[i]['amount'],2)+'</p>') : ('<input style="text-align: right" type="text" class="validate number" required="" aria-required="true" name="amounts[]" value="'+payout_details[i]['amount']+'"><input style="text-align: right" type="hidden" class="validate" required="" aria-required="true" name="orig_amounts[]" value="'+payout_details[i]['orig_amount']+'">')) + '</td>';
		result+='<td>' + ( action == <?php echo ACTION_VIEW ?> ? ('<p class="p-n">' + (payout_details[i]['remarks'] != null ? payout_details[i]['remarks'] : '') +'</p>') :('<input style="text-left: right" type="text" aria-required="true" name="remarks[]" value="'+ (payout_details[i]['remarks'] != null ? payout_details[i]['remarks'] : '') +'">')) + '</td>';
		result+='<input type="hidden" name="payout_dtl_ids[]" value="' + payout_details[i]['payroll_dtl_id'] + '">';
		result+='</tr>';

	}
	$('#div_rows').html(result);
	$('input.number').number(true, 2);
});
$(function (){

	$('#remittance_form').parsley();
	$('#remittance_form').submit(function(e) {
	    e.preventDefault();
	    
		if ( $(this).parsley().isValid() ) {
			var data = $(this).serialize();
		  	button_loader('save_payout_details', 1);
		  	var option = {
					url  : $base_url + 'main/payroll_remittance/save_payout_details',
					data : data,
					success : function(result){
						if(result.status)
						{
							notification_msg("<?php echo SUCCESS ?>", result.msg);
							$('.cancel_modal').trigger('click');
							$('.tabs > li > #employee_list').trigger('click');
						}
						else
						{
							notification_msg("<?php echo ERROR ?>", result.msg);
						}	
						
					},
					
					complete : function(jqXHR){
						button_loader('save_payout_details', 0);
					}
			};

			General.ajax(option);
	    }
	});

});

</script>