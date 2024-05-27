<div class="pre-datatable filter-left"></div>
<div class="p-t-lg">
	<table cellpadding="0" cellspacing="0" class="table table-advanced table-layout-auto" id="table_payout_employee">
	  	<thead>
		<tr>
		  <th width="15%">Employee Number</th>
		  <th width="20%">Employee Name</th>
		  <th width="20%">Office</th>
		  <th width="10%">Status</th>
		  <th width="20%">Actions</th>
		</tr>
		<tr class="table-filters">
			<td><input name="C-agency_employee_id" class="form-filter"></td>
			<td><input name="B-employee_name" class="form-filter"></td>
			<td><input name="B-office_name" class="form-filter"></td>
			<td><input name="E-employment_status_name" class="form-filter"></td>
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

<script type="text/javascript">
function delete_payroll_employee(e_data)
{
	$('#confirm_modal').confirmModal({
		topOffset : 0,
		onOkBut : function() {
			$('#tab_content').isLoading();
			
			$.post($base_url + 'main/payroll_general_tab/delete_payroll_employee/'+e_data, function(result)
			{
				if(result.status)
				{
					notification_msg("<?php echo SUCCESS ?>", result.msg);
					var $data = e_data.split('/');
					var vars = '<?php echo ACTION_PROCESS ?>/' + $data[1] + '/' + $data[3] + '/' + $data[4] + '/' + $data[5] + '/' + $data[6];
					load_datatable('table_payout_employee', '<?php echo PROJECT_MAIN ?>/payroll_general_tab/get_payout_employee_list/'+vars,false,0,0,true)
				}
				else
				{
					notification_msg("<?php echo ERROR ?>", result.msg);
				}

				$('#tab_content').isLoading("hide");
			}, 'json');	
		},
		onCancelBut : function() {},
		onLoad : function() {
			$('.confirmModal_content h4').html('Are you sure you want to remove the selected employee from this payout ?');	
		},
		onClose : function() {}
	});
}

function print_mra(employee_id, attendance_period_hdr_id){
    
	var data = 'mra_attendance_period='+attendance_period_hdr_id+'&mra_employee[]='+employee_id;
	window.open($base_url + 'main/payroll_quick_links/print_employee_mra?' + data, '_blank');
   
}
</script>