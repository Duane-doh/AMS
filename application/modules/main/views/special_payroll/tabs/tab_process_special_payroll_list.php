<form id="special_payroll_list_form">

	<input type="hidden" name="id" id="id" value="<?php echo !EMPTY($id) ? $id : NULL?>">
	<input type="hidden" name="salt" id="salt" value="<?php echo !EMPTY($salt) ? $salt : NULL?>">
	<input type="hidden" name="token" id="token" value="<?php echo !EMPTY($token) ? $token : NULL?>">
	<input type="hidden" name="action" id="action" value="<?php echo !EMPTY($action) ? $action : NULL?>">
	<input type="hidden" name="module" id="module" value="<?php echo !EMPTY($module) ? $module : NULL?>">

	<div class="row p-t-sm">
		<button class="btn btn-success right" id="save_payout_details" value="<?php echo BTN_SAVE ?>"><?php echo BTN_SAVE ?></button>
	</div>
	
	<div class="panel row">
		<div class="row m-n m-t-md">
			<div class="col s12">
				<div class="pre-datatable filter-left"></div>
				<div>
					<table class="table table-advanced table-layout-auto" id = "table_process_special_payroll_list">
						<thead>
							<tr>
								<th width="20%">Employee</th>
								<th width="30%">Office</th>
								<th width="20%">Position</th>
								<th width="1%">Tenure/Aggregated Tenure (in months)</th>
								<th width="5%">Rating</th>
								<th width="5%">Basic Amount</th>
								<th width="5%">Rate</th>
								<th width="5%">Compensation Amount</th>
								<th width="5%">Tax Amount</th>
								<th width="10%">Action</th>
							</tr>
							  <tr class="table-filters">
							  	<td><input name="a-employee_name" class="form-filter"></td>
						        <td><input name="a-office_name" class="form-filter"></td>
						        <td><input name="a-position_name" class="form-filter"></td>
						        <td><input name="a-tenure_in_months" class="form-filter"></td>
						        <td><input name="a-perf_rating" class="form-filter"></td>
						        <td><input name="a-basic_amount" class="form-filter"></td>
						        <td><input name="b-base_rate" class="form-filter"></td>
						        <td><input name="b-compensation_amount" class="form-filter"></td>
						        <td><input name="b-deduction_amount" class="form-filter"></td>
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
	</div>
</form>
	
<script>
$( document ).ready(function() {

	$('#special_payroll_list_form').parsley();
	$('#special_payroll_list_form').submit(function(e) {
	    e.preventDefault();
	    
		if ( $(this).parsley().isValid() ) {
			var data = $(this).serialize();
		  	button_loader('save_payout_details', 1);
		  	var option = {
					url  : $base_url + 'main/special_payroll_tab/save_payout_details',
					data : data,
					success : function(result){
						if(result.status)
						{
							notification_msg("<?php echo SUCCESS ?>", result.msg);
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