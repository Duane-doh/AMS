<form id="payslip_employee_details">
<input type="hidden" id="payroll_id" value="<?php echo $id;?>">
<div class="row p-lg p-t-n p-b-n">
	<div class="col s12 p-sm"> 
		<div class="col s2">
			<label class="font-md">Total Net Pay:</label>	
		</div>
		<div class="col s3">
			<label class="font-md"><b>&#8369; <?php echo number_format($payroll_info['net_pay'],2) ?></b></label>
		</div>
	</div>
	<div class='col s12 p-sm'>
		<div class="col s2">
			<label class="font-md">Effectivity Date:</label>	
		</div>
		<div class='col s3'>
			<select id="effective_date" class="selectize" placeholder="Effectivity Date">
			 	<option></option>
			 	<?php
			 		foreach($effective_date AS $e) {
			 			echo '<option value="' . $e . '">' . format_date($e,'F d, Y') . '</option>';
			 		}
			 	?>
		 	</select>
	 	</div>
	</div>
</div>

<div class="row p-r-lg p-l-lg">	
	<div class="pre-datatable filter-left"></div>
	<div class="p-t-md">
		<table cellpadding="0" cellspacing="0" class="table table-advanced table-layout-auto" id="table_payslip_history_details_compensations">
			<thead> 
				<tr>
					<th width="20%">Compensation Type</th>
					<th width="20%">Amount</th>
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
	</div>
</div>

<div class="row p-r-lg p-l-lg">	
	<div class="pre-datatable filter-left"></div>
	<div class="p-t-md">
		<table cellpadding="0" cellspacing="0" class="table table-advanced table-layout-auto" id="table_payslip_history_details_deductions">
			<thead> 
				<tr>
					<th width="20%">Deduction Type</th>
					<th width="20%">Employee Share</th>
					<th width="20%">Employer Share</th>
					<th width="10%">Payment Count</th>
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
	</div>
</div>

</form>

<script>
	$('#effective_date').on('change', function(){
		var payroll_id	= $('#payroll_id').val();
		var effective_date = $('#effective_date').val();
		load_datatable('table_payslip_history_details_compensations', 'main/compensation/get_employee_payslip_history_details?compensation='+true+'&payroll_id='+payroll_id+'&effective_date='+effective_date);
		$('#table_payslip_history_details_compensations_filter').hide();
		load_datatable('table_payslip_history_details_deductions', 'main/compensation/get_employee_payslip_history_details?deduction='+true+'&payroll_id='+payroll_id+'&effective_date='+effective_date);
		$('#table_payslip_history_details_deductions_filter').hide();
	});

</script>