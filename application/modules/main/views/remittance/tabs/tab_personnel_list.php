
<div class="row">
    <div class="col s8 m8 l8">
        &nbsp;
    </div>
	<div class="col s4 m4 l4 right-align">
	    <div class="row form-vertical form-styled form-basic">
	        <div class="input-field col s4">
	            <label class="label">Total Amount : </label>
	        </div>
	        <div class="input-field col s8">
	            <label class="label left" id="remittance_total_amount">&#8369; 0</label>
	        </div>
	    </div>   
	</div>
</div>

<div class="pre-datatable filter-left"></div>
<div class="p-t-lg">
	<table cellpadding="0" cellspacing="0" class="table table-advanced table-layout-auto" id="table_personnel_list">
	  	<thead>
		<tr>
		  <th width="15%">Employee Number</th>
		  <th width="25%">Employee Name</th>
		  <th width="20%">Office</th>
		  <th width="15%">Employment Status</th>
		  <th width="10%">Total Amount</th>
		  <th width="10%">Action</th>
		</tr>
		<tr class="table-filters">
			<td><input name="D-agency_employee_id" class="form-filter"></td>
			<td><input name="fullname" class="form-filter"></td>
			<td><input name="H-name" class="form-filter"></td>
			<td><input name="G-employment_status_name" class="form-filter"></td>
			<td><input name="amount" class="form-filter"></td>
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
$(document).ready(function(){

	get_total_amount(0);

});

$('#office_filter').change(function(){

	get_total_amount($(this).val());
    
});
	
$('#office_filter_div').removeClass('none');

function get_total_amount(id)
{
	var data = 'office_id='+id; data += '&remittance_id=<?php echo $id ?>';
	var option = {
		url  : $base_url + '<?php echo PROJECT_MAIN ?>/payroll_remittance/get_total_amount',
		data : data,
		type : 'json',
		success : function(result){
			var amount = 0;
			if(result.status)
			{
				amount = result.total_amount;
			}
			$('#remittance_total_amount').html('&#8369; '+amount);
		}
};

General.ajax(option);    
}

</script>