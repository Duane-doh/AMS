<!--
Not being used
-->

<h3 class="md-header"><?php echo SUB_MENU_PAYROLL_REPORTS ?></h3>
<table  cellpadding="0" cellspacing="0" class="table table-default table-layout-auto">
  <thead>
	<tr>
	  <th>Name</th>
	  <th width="10%">Action</th>
	</tr>
  </thead>
  <tbody>
  	<tr>
  		<td>Payroll Register</td>
  		<td>
	  		<div class='table-actions'>
	  			<a id="payroll_register" href="#" class='view tooltipped' data-tooltip='View' data-position='bottom' data-delay='50' onclick=''></a>
	  		</div>
	  	</td>
  	</tr>
  	<tr>
  		<td>General Payroll Alphalist</td>
  		<td>
	  		<div class='table-actions'>
	  			<a id="general_payroll_alphalist_report" href="#" class='view tooltipped' data-tooltip='View' data-position='bottom' data-delay='50' onclick=''></a>
	  		</div>
	  	</td>
  	</tr>
  	<tr>
  		<td>General Payroll Alphalist AS</td>
  		<td>
	  		<div class='table-actions'>
	  			<a id="general_payroll_alphalist_report_AS" href="#" class='view tooltipped' data-tooltip='View' data-position='bottom' data-delay='50' onclick=''></a>
	  		</div>
	  	</td>
  	</tr>
  	<tr>
  		<td>Payslip Contractual</td>
  		<td>
	  		<div class='table-actions'>
	  			<a id="payslip_contractual" href="#" class='view tooltipped' data-tooltip='View' data-position='bottom' data-delay='50' onclick=''></a>
	  		</div>
	  	</td>
  	</tr>
  	<tr>
  		<td>Payslip Special Benefits</td>
  		<td>
	  		<div class='table-actions'>
	  			<a id="payslip_special_benefits" href="#" class='view tooltipped' data-tooltip='View' data-position='bottom' data-delay='50' onclick=''></a>
	  		</div>
	  	</td>
  	</tr>
  	<tr>
  		<td>Payslip Regular</td>
  		<td>
	  		<div class='table-actions'>
	  			<a id="payslip_regular" href="#" class='view tooltipped' data-tooltip='View' data-position='bottom' data-delay='50' onclick=''></a>
	  		</div>
	  	</td>
  	</tr>
  </tbody>
</table>
<div class="md-footer default">
	<a class="waves-effect waves-teal btn-flat" id="cancel_service_record">Cancel</a>
</div>
<script>
$(function (){
	$("#cancel_service_record").on("click", function(){
		modalObj.closeModal();
	});
	$("#payroll_report").on('click', function (){
		window.location = $base_url + 'main/payroll/generate_payroll/';

	});
	$('.selectize').selectize();
	$("#cancel_service_record").on("click", function(){
		modalObj.closeModal();
	});

	$('a').click(function(event) {
	var report = $(this).attr("id");
    // alert($(this).attr("id"));
    window.location = $base_url + 'main/reports/generate_reports/' + report;
	});
})
</script>