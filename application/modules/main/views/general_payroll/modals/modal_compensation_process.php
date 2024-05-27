<!--  
General Payroll List -> Process -> Compensation List (tab) -> Edit 
-->

<form id="payroll_process_form">

	<input type="hidden" name="id" id="id" value="<?php echo !EMPTY($id) ? $id : NULL?>">
	<input type="hidden" name="id2" id="id2" value="<?php echo !EMPTY($id2) ? $id2 : NULL?>">
	<input type="hidden" name="salt" id="salt" value="<?php echo !EMPTY($salt) ? $salt : NULL?>">
	<input type="hidden" name="token" id="token" value="<?php echo !EMPTY($token) ? $token : NULL?>">
	<input type="hidden" name="action" id="action" value="<?php echo !EMPTY($action) ? $action : NULL?>">
	<input type="hidden" name="module" id="module" value="<?php echo !EMPTY($module) ? $module : NULL?>">

	<div class="form-float-label">
		<div class="row p-md">
			<span class="font-lg font-spacing-15"><?php echo !empty($compensation_name) ? $compensation_name : '';?></span>
		</div>
		  <div>
			<div class="pre-datatable filter-left"></div>
		    <div class="col s12">
			  <table cellpadding="0" cellspacing="0" class="table table-advanced table-layout-auto" id="table_compensation_process">
				  <thead>
					<tr>
						<th width="5%">Employee Number</th>
						<th width="10%">Employee Name</th>
						<th width="15%">Office</th>
						<th width="10%">Position</th>
						<th width="10%">Payout Date</th>
						<th width="10%">Total Amount</th>
						<th width="10%">Original Amount</th>
						<th width="5%">Adjust<br/>Amount</th>
						<th width="15%">Remarks</th>
						<th width="5%">Actions</th>
					</tr>
					<tr class="table-filters">
						<td><input name="E-agency_employee_id" class="form-filter"></td>
						<td><input name="B-employee_name" class="form-filter"></td>
						<td><input name="B-office_name" class="form-filter"></td>
						<td><input name="B-position_name" class="form-filter"></td>
						<td><input name="C-effective_date" class="form-filter"></td>
						<td><input name="C-amount" class="form-filter"></td>
						<td><input name="C-orig_amount" class="form-filter"></td>
						<td><input name="C-less_amount" class="form-filter"></td>
						<td><input name="C-remarks_compensation" class="form-filter"></td>
						<td class="table-actions">
							<a href="javascript:;" class="tooltipped filter-submit" data-tooltip="Submit" data-position="top" data-delay="50"><i class="flaticon-filter19"></i></a>
							<a href="javascript:;" class="tooltipped filter-cancel" data-tooltip="Reset" data-position="top" data-delay="50"><i class="flaticon-circle100"></i></a>
						</td>
					</tr>
				  </thead>
				 	<tbody>
				  	</tbody>
			  	</table>
		    </div>
		</div>
	</div>
	<div class="md-footer default p-t-sm">
		<?php if($action != ACTION_VIEW) : ?>
			<a class="waves-effect waves-teal btn-flat cancel_modal" id="cancel_payout_details">Cancel</a>
			<button type="button" class="btn btn-success right" id="save_payout_details" value="<?php echo BTN_SAVE ?>"><?php echo BTN_SAVE ?></button>
		<?php endif;?>
	</div>
</form>

<script>
$( document ).ready(function() {

	$('#save_payout_details').click(function() {
		    
			if ( $('#payroll_process_form').parsley().isValid() ) {
				var data = $('#payroll_process_form').serialize();
			  	button_loader('save_payout_details', 1);
			  	var option = {
						url  : $base_url + 'main/payroll_general_tab/save_payout_detail_by_type',
						data : data,
						success : function(result){
							if(result.status)
							{
								notification_msg("<?php echo SUCCESS ?>", result.msg);
								load_datatable('table_compensation_process', '<?php echo $url ?>',false,0,0,true);
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