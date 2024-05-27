
<section id="content" class="p-t-n m-t-n ">
    
    <!--breadcrumbs start-->
    <div id="breadcrumbs-wrapper" class=" grey lighten-3"> 
      <div class="container">
        <div class="row">
         <div class="col s6 m6 l6">
				<h5 class="breadcrumbs-title">Time & Attendance Reports</h5>
				<ol class="breadcrumb m-n p-b-sm">
					<?php get_breadcrumbs();?>
				</ol>
			</div>
        </div>
      </div>
    </div>
    <!--breadcrumbs end-->
    
    <!--start container-->
    <div class="container">
    <div class="section panel p-lg">
  <!--start section-->
	      	
    <form id="form_reports" name="form_reports" class="form-vertical form-styled form-basic">
	<div class="row">
		<div class="col s8 m-b-n-lg">
			<ul class="collapsible panel" data-collapsible="expandable">
				<li>
					<div class="collapsible-header active teal white-text">Report Description</div>
					<div class="collapsible-body teal lighten-5" style="min-height:185px">
							
							<div class="row" id="report_div">
						    <div class="input-field col s3">
						      <label class="label position-right">Report <span class="required"> * </span></label>
						    </div>
						    <div class="col s8 p-t-md">
						      
									<select class="selectize" id="reports_ta" name="reports" placeholder="Select report..." required="true">
										<option>Select Report...</option>
										<?php if($this->permission->check_permission(MODULE_TA_REPORTS_DAILY_TIME_RECORD)) :?>
										<option value="<?php echo REPORTS_TA_DAILY_TIME_RECORD ?>">DAILY TIME RECORD</option>
										<?php endif; ?>
										<?php if($this->permission->check_permission(MODULE_TA_REPORTS_LEAVE_APPLICATION)) :?>
										<option value="<?php echo REPORTS_TA_LEAVE_APPLICATION ?>">APPLICATION FOR LEAVE</option>
										<?php endif; ?>
										<?php if($this->permission->check_permission(MODULE_TA_LEAVE_CARD)) :?>
										a<option value="<?php echo REPORTS_TA_LEAVE_CARD ?>">LEAVE CARD</option>
										<?php endif; ?>
										<?php if($this->permission->check_permission(MODULE_TA_MR_ATTENDANCE)) :?>
										a<option value="<?php echo REPORTS_TA_MONTHLY_ATTENDANCE ?>">MONTHLY REPORT OF ATTENDANCE</option>
										<?php endif; ?>
										<?php if($this->permission->check_permission(MODULE_TA_MR_ATTENDANCE)) :?>
										a<option value="<?php echo REPORTS_TA_NO_WORK_SCHED_LIST ?>">LIST OF EMPLOYEES WITH NO WORK SCHEDULE</option>
										<?php endif; ?>
										<?php if($this->permission->check_permission(MODULE_TA_REPORTS_LEAVE_AVAILMENT)) :?>
										<option value="<?php echo REPORTS_TA_LEAVE_BALANCE_STATEMENT ?>">STATEMENT OF LEAVE BALANCES</option>
										<?php endif; ?>
										<?php if($this->permission->check_permission(MODULE_TA_REPORTS_LEAVE_CREDIT_CERT)) :?>
										<option value="<?php echo REPORTS_TA_LEAVE_CREDIT_CERT ?>">CERTIFICATE OF LEAVE CREDITS (MONTHLY)</option>
										<?php endif; ?>
										<?php if($this->permission->check_permission(MODULE_TA_REPORTS_LEAVE_WITHOUT_PAY_CERT)) :?>
										<option value="<?php echo REPORTS_TA_LEAVE_WITHOUT_PAY_CERT ?>">CERTIFICATE OF LEAVE WITHOUT PAY</option>
										<?php endif; ?>
										<?php if($this->permission->check_permission(MODULE_HR_REPORT_COE_WITH_COMPENSATIONS)) :?>
										<option value="<?php echo REPORT_COE_WITH_COMPENSATIONS ?>">COE WITH COMPENSATIONS</option>
										<?php endif; ?>
										<?php if($this->permission->check_permission(MODULE_HR_REPORT_COE_WITHOUT_COMPENSATIONS)) :?>
										<option value="<?php echo REPORT_COE_WITHOUT_COMPENSATIONS ?>">COE WITHOUT COMPENSATIONS</option>
										<?php endif; ?>
									 </select>						

						      
						        <!-- ANOTHER <OPTION> GOES HERE -->
						     
						      <!-- END STRUCTURE -->
						    </div>
						</div>

						<!-- LIST OF OFFICES WITHOUT DATE -->
						<div id="office_list_div" class="row none">
						   <div class="input-field col s3">
						     	<label class="label position-right">Office<span class="required"> * </span></label>
						    </div>
						    <div class="col s8 p-t-md">
								<select id="office_list" name="office_list" class="selectize" placeholder="Select Office">
										<option value="">Select Offices...</option>
										<?php if (!EMPTY($offices_list)): ?>
											<?php foreach ($offices_list as $o): ?>
												<option value="<?php echo $o['office_id']?>"><?php echo $o['name'] ?></option>
											<?php endforeach;?>
										<?php endif;?>
								</select>
						    </div>
						</div>

						<!-- LIST OF EMPLOYEES -->
						<div class="row none" id="employee_div">
						    <div class="input-field col s3">
						      	<label class="label position-right">Employee <span class="required"> * </span></label>
						    </div>
						    <div class="col s8 p-t-md">
								<select id="employee" name="employee" class="selectize" placeholder="Select Employee">
									<option value="">Select Employee...</option>
									<?php if (!EMPTY($employees)): ?>
										<?php foreach ($employees as $emp): ?>
											<?php 
											if (!EMPTY($emp['first_name']) AND !EMPTY($emp['last_name'])): ?>
												<option value="<?php echo $emp['employee_id']?>"><?php echo $emp['employee_name'] ?></option>
											<?php endif;?>
										<?php endforeach;?>
									<?php endif;?>
								</select>
						    </div>
						</div>
						
						<!-- LIST OF OFFICES USING office_id AS VALUE -->
						<div id="office_filtered_div" class="row none">
						   <div class="input-field col s3">
						     	<label class="label position-right">Office<span class="required"> * </span></label>
						    </div>
						    <div class="col s8 p-t-md">
								<select id="office_filtered" name="office_filtered" class="selectize" placeholder="Select Office">
										<option value="">Select Offices...</option>
										<?php if (!EMPTY($offices_list)): ?>
											<?php foreach ($offices_list as $o): ?>
												<option value="<?php echo $o['office_id']?>"><?php echo $o['name'] ?></option>
											<?php endforeach;?>
										<?php endif;?>
								</select>
						    </div>
						</div>

						<!-- LIST OF OFFICES USING office_id AS VALUE -->
						<div id="payroll_type_div" class="row none">
						   <div class="input-field col s3">
						     	<label class="label position-right">Payroll Type<span class="required"> * </span></label>
						    </div>
						    <div class="col s8 p-t-md">
								<select id="payroll_type_work_sched" name="payroll_type_work_sched" class="selectize" placeholder="Select Payroll Type">
										<option value="">Select Offices...</option>
										<?php if (!EMPTY($payroll_types)): ?>
											<?php foreach ($payroll_types as $o): ?>
												<option value="<?php echo $o['payroll_type_id']?>"><?php echo $o['payroll_type_name'] ?></option>
											<?php endforeach;?>
										<?php endif;?>
								</select>
						    </div>
						</div>
						
						<!-- LIST OF FILTERED EMPLOYEES BY OFFICE -->
						<div class="row none" id="employee_filtered_div">
						    <div class="input-field col s3">
						      	<label class="label position-right">Employee <span class="required"> * </span></label>
						    </div>
						    <div class="col s8 p-t-md">
								<select id="employee_filtered" name="employee_filtered" class="selectize" placeholder="Select Employee">
									<option value="">Select Employee...</option>
								</select>
						    </div>
						</div>
									
						

						<div id="leave_request_div" class="row none">
							   <div class="input-field col s3">
							    	<label class="label position-right">Leave Request <span class="required"> * </span></label>
							    </div>
							    <div class="col s8 p-t-md">
									<select id="leave_request" name="leave_request" class="selectize" placeholder="Select Leave Request">
										<option value="">Select Leave Request...</option>
									</select>
							    </div>
						</div>
						<div id="mra_period_div" class="row none">
							   <div class="input-field col s3">
							    	<label class="label position-right">Attendance Period <span class="required"> * </span></label>
							    </div>
							    <div class="col s8 p-t-md">
									<select id="mra_attendance_period" name="mra_attendance_period" class="selectize" placeholder="Select Attendance Period">
										<option value="">Select Attendance Period...</option>
									</select>
							    </div>
						</div>
						<div id="mra_office_div" class="row none">
							   <div class="input-field col s3">
							    	<label class="label position-right">Office</label>
							    </div>
							    <div class="col s8 p-t-md">
									<select id="mra_office" name="mra_office[]" class="selectize" placeholder="Select Office" multiple>
										<option value="">Select Office...</option>
									</select>
							    </div>
						</div>
						<div id="mra_employee_div" class="row none">
							   <div class="input-field col s3">
							    	<label class="label position-right">Employees</label>
							    </div>
							    <div class="col s8 p-t-md">
									<select id="mra_employee" name="mra_employee[]" class="selectize" placeholder="Select Employees" multiple>
										<option value="">Select Employees...</option>
									</select>
							    </div>
						</div>
						<!-- DATE RANGE -->
						<div id="date_range_div" class="row none">
						   	<div class="input-field col s3">
						      	<label class="label position-right">Date Range <span class="required"> * </span></label>
						    </div>
						    <div class="col s4 p-t-md">
								<input type="text" class="validate datepicker_start" name="date_range_from" id="date_range_from" placeholder="YYYY/MM/DD"
										onkeypress="format_identifications('<?php echo DATE_FORMAT ?>',this.value,event,'date_range_from')">
						    </div>
						    <div class="col s4 p-t-md">
								<input type="text" class="validate datepicker_end" name="date_range_to" id="date_range_to" placeholder="YYYY/MM/DD"
										onkeypress="format_identifications('<?php echo DATE_FORMAT ?>',this.value,event,'date_range_to')">
						    </div>
						</div>
						<!-- DATE YEAR AND MONTH -->
						<div id="year_month_div" class="row none">
						   	<div class="input-field col s3">
						      	<label class="label position-right">Year and Month <span class="required"> * </span></label>
						    </div>
						    <div class="col s4 p-t-md">
								<?php echo create_years("2010",date('Y'),"year_select",date('Y')) ?>
						    </div>
						    <div class="col s4 p-t-md">
								<?php echo create_months($id = 'month_select',date('m'),false) ?>
						    </div>
						</div>
						<!-- PREPARED BY -->
						<div class="row none" id="prepared_by_div">
						    <div class="input-field col s3">
						      	<label class="label position-right">Prepared By <span class="required"> * </span></label>
						    </div>
						    <div class="col s8 p-t-md">
								<select id="prepared_by" name="prepared_by" class="selectize" placeholder="Select Employee">
									<option value="">Select Employee...</option>
									<?php if (!EMPTY($prepared_by)): ?>
										<?php foreach ($prepared_by as $emp): ?>
											<option value="<?php echo $emp['report_signatory_id']?>"><?php echo $emp['signatory_name'] ?></option>
										<?php endforeach;?>
									<?php endif;?>
								</select>
						    </div>
						</div>
						<!-- Certified BY -->
						<div class="row none" id="certified_by_div">
						    <div class="input-field col s3">
						      	<label class="label position-right">Certified By <span class="required"> * </span></label>
						    </div>
						    <div class="col s8 p-t-md">
								<select id="certified_by" name="certified_by" class="selectize" placeholder="Select Employee">
									<option value="">Select Employee...</option>
									<?php if (!EMPTY($certified_by)): ?>
										<?php foreach ($certified_by as $emp): ?>
											<option value="<?php echo $emp['report_signatory_id']?>"><?php echo $emp['signatory_name'] ?></option>
										<?php endforeach;?>
									<?php endif;?>
								</select>
						    </div>
						</div>
						<!-- Approved By -->
						<div class="row none" id="approved_by_div">
						    <div class="input-field col s3">
						      	<label class="label position-right">Approved By <span class="required"> * </span></label>
						    </div>
						    <div class="col s8 p-t-md">
								<select id="approved_by" name="approved_by" class="selectize" placeholder="Select Employee">
									<option value="">Select Employee...</option>
									<?php if (!EMPTY($approved_by)): ?>
										<?php foreach ($approved_by as $emp): ?>
											<option value="<?php echo $emp['report_signatory_id']?>"><?php echo $emp['signatory_name'] ?></option>
										<?php endforeach;?>
									<?php endif;?>
								</select>
						    </div>
						</div>
						<!-- TRACKING CODE -->
						<div id="tracking_code_div" class="row none">
							<div class="input-field col s3">
						      	<label class="label position-right">Tracking Code</label>
						    </div>
							<div class="col s8 p-t-md">
								<input id="tracking_code" name="tracking_code" type="text" value="">
							</div>
						</div>

						</div>
					</div>
				</li>
			</ul>
		
		<div class="col s4 m-b-n-lg">
			<ul class="collapsible panel" data-collapsible="expandable">
				<li>
					<div class="collapsible-header active teal white-text">File Type</div>
						<div class="collapsible-body p-b-md p-t-md teal lighten-5">
							<div class="form-basic">
								<div class="row m-n">
									<div class="col s6 p-n center">
										<input type="radio" class="labelauty" name="format" id="report_type_pdf" value="pdf" data-labelauty="PDF" checked/>
									</div>
									<div class="col s6 p-n center">
										<input type="radio" class="labelauty" name="format" id="report_type_excel" value="excel" data-labelauty="Excel"/>
									</div>
								</div>	
							</div>
							<div class="panel-footer right-align m-t-md teal lighten-5">
								<button id="generate_report" href="#" class="btn btn-success"><i class="flaticon-gear33"></i> Generate</button>
							</div>
						</div>
				</li>
			</ul>
		</div>
	</div>
</form>


      <!--end section-->              
          </div>
        </div>
        <!--end container-->

    </section>
<!-- END CONTENT -->
<script type="text/javascript">
$(function(){	
	$("#generate_report").on('click', function (){
		$('#form_reports').parsley('destroy');

		if ( $('#form_reports').parsley().isValid() ) {
			$('#form_reports').submit(function(e) {
				e.stopImmediatePropagation();
		    	e.preventDefault();	
				var id    	= '';
				var path    = '';
				
					var report  = $("#reports_ta").val();

					if(report == "")
					{
						notification_msg("<?php echo ERROR ?>", "<b>Report </b> is required.");
						return false;
					}
					switch(report)
					{
						
						case '<?php echo REPORTS_TA_DAILY_TIME_RECORD; ?>':
							var office_id		= $('#office_filtered').val();
							var employee		= $('#employee_filtered').val();
							var date_range_from = $('#date_range_from').val();
							var date_range_to   = $('#date_range_to').val();
							//var office_id       = $('#office_list').val();
							if(employee == "" && office_id == "")
							{
								notification_msg("<?php echo ERROR ?>", "Please select <b>Office</b> or <b>Employee </b> is required.");
								return false;
							}
							if(date_range_from == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Date From </b> is required.");
								return false;
							}
							if(date_range_to == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Date To </b> is required.");
								return false;
							}

						break;

						case '<?php echo REPORTS_TA_LEAVE_APPLICATION; ?>':

							var employee        = $('#employee').val();
							var leave_request = $('#leave_request').val();
							if(employee == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Employee </b> is required.");
								return false;
							}
							if(leave_request == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Leave Request </b> is required.");
								return false;
							}
						break;

						case '<?php echo REPORTS_TA_LEAVE_CARD; ?>':

							var employee        = $('#employee').val();
							var tracking_code      = $('#tracking_code').val();
							if(employee == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Employee </b> is required.");
								return false;
							}
							// if(tracking_code == "")
							// { 
							// 	notification_msg("<?php echo ERROR ?>", "<b>Tracking Code </b> is required.");
							// 	return false;
							// }	

						break;

						case '<?php echo REPORTS_TA_MONTHLY_ATTENDANCE; ?>':

							var attendance_period = $('#mra_attendance_period').val();
							var office            = $('#mra_office').val();
							var employee          = $('#employee').val();
							var prepared_by       = $('#prepared_by').val();
							var certified_by      = $('#certified_by').val();
							var tracking_code      = $('#tracking_code').val();

							if(attendance_period == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Attendance Period </b> is required.");
								return false;
							}
							if(office == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Office </b> is required.");
								return false;
							}
							if(prepared_by == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Prepared By </b> is required.");
								return false;
							}
							if(certified_by == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Certified By </b> is required.");
								return false;
							}
							// if(tracking_code == "")
							// { 
							// 	notification_msg("<?php echo ERROR ?>", "<b>Tracking Code </b> is required.");
							// 	return false;
							// }	

						break;
						case '<?php echo REPORTS_TA_LEAVE_BALANCE_STATEMENT; ?>':

							var prepared_by       = $('#prepared_by').val();
							var approved_by       = $('#approved_by').val();
							var office_id 		  = $('#office_list').val();

							if(prepared_by == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Prepared By </b> is required.");
								return false;
							}
							if(approved_by == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Approved By </b> is required.");
								return false;
							}
							if(office_id == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Office </b> is required.");
								return false;
							}

						break;

						case '<?php echo REPORTS_TA_LEAVE_CREDIT_CERT; ?>':
							var office				= $('#office_filtered').val();
							var employee			= $('#employee_filtered').val();
							var certified_by       = $('#certified_by').val();
							
							if(office == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Office</b> is required.");
								return false;
							}
							if(employee == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Employee</b> is required.");
								return false;
							}
							if(certified_by == "")
							{ 
								notification_msg("<?php echo ERROR ?>", "<b>Certified By </b> is required.");
								return false;
							}
						break; 

						case '<?php echo REPORTS_TA_LEAVE_WITHOUT_PAY_CERT; ?>':
							var office				= $('#office_filtered').val();
							var employee			= $('#employee_filtered').val();
							var date_range_from		= $('#date_range_from').val();
							var date_range_to		= $('#date_range_to').val();
							var tracking_code      = $('#tracking_code').val();
							var certified_by       = $('#certified_by').val();
							
							if(office == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Office</b> is required.");
								return false;
							}
							if(employee == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Employee</b> is required.");
								return false;
							}
							if(date_range_from == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Date Range From</b> is required.");
								return false;
							}
							if(date_range_to == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Date Range To</b> is required.");
								return false;
							}	
							if(certified_by == "")
							{ 
								notification_msg("<?php echo ERROR ?>", "<b>Certified By </b> is required.");
								return false;
							}
						break;

						case '<?php echo REPORT_COE_WITH_COMPENSATIONS ?>':
						case '<?php echo REPORT_COE_WITHOUT_COMPENSATIONS ?>':
							var employee           = $('#employee').val();	
							var tracking_code      = $('#tracking_code').val();
							var certified_by       = $('#certified_by').val();
							if(employee == "")
							{ 
								notification_msg("<?php echo ERROR ?>", "<b>Employee </b> is required.");
								return false;
							}	
							// if(tracking_code == "")
							// { 
							// 	notification_msg("<?php echo ERROR ?>", "<b>Tracking Code </b> is required.");
							// 	return false;
							// }	
							if(certified_by == "")
							{ 
								notification_msg("<?php echo ERROR ?>", "<b>Certified By </b> is required.");
								return false;
							}			
						break;
						case '<?php echo REPORTS_TA_NO_WORK_SCHED_LIST; ?>':
							var payroll_type_work_sched	= $('#payroll_type_work_sched').val();
							var date_range_from = $('#date_range_from').val();
							var date_range_to   = $('#date_range_to').val();
							if(payroll_type_work_sched == "")
							{
								notification_msg("<?php echo ERROR ?>", "Please select <b>Payroll Type</b> is required.");
								return false;
							}
							if(date_range_from == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Date From </b> is required.");
								return false;
							}
							if(date_range_to == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Date To </b> is required.");
								return false;
							}

						break;

					}

				var format = "";

				if($("#report_type_pdf").is(":checked"))
				{
					format = "pdf";
				}
				else
				{
					format = "excel";
				}
				window.open($base_url + 'main/reports_ta/reports_ta/generate_reports/' + format + '/' + report + '/?' + $('#form_reports').serialize(), '_blank');
				

  			});
	    }

	});
	
	$('#reports_ta').change(function() {
		$('#salary_schedule_div').addClass('none');
		$('#office_list_div').addClass('none');
		$('#employee_div').addClass('none');
		$('#date_range_div').addClass('none');
		$('#leave_request_div').addClass('none');
		$('#mra_period_div').addClass('none');
		$('#mra_office_div').addClass('none');
		$('#mra_employee_div').addClass('none');
		$('#prepared_by_div').addClass('none');
		$('#certified_by_div').addClass('none');
		$('#approved_by_div').addClass('none');
		$('#office_filtered_div').addClass('none');
		$('#employee_filtered_div').addClass('none');
		$('#year_month_div').addClass('none');
		$('#tracking_code_div').addClass('none');


		var selected = $(this).val();
		switch(selected)
		{
			case '<?php echo REPORTS_TA_DAILY_TIME_RECORD; ?>': 
				$('#office_filtered_div').removeClass('none');
				$('#employee_filtered_div').removeClass('none');
				// $('#employee_div').removeClass('none');
				// $('#office_list_div').removeClass('none');
				$('#date_range_div').removeClass('none');
			break;

			case '<?php echo REPORTS_TA_LEAVE_APPLICATION; ?>': 
				$('#employee_div').removeClass('none');
				$('#leave_request_div').removeClass('none');

			break;

			case '<?php echo REPORTS_TA_LEAVE_CARD; ?>':
				$('#employee_div').removeClass('none');		
				$('#tracking_code_div').removeClass('none');	
			break;
			
			case '<?php echo REPORTS_TA_MONTHLY_ATTENDANCE; ?>':
				$('#tracking_code_div').removeClass('none');
				$('#mra_period_div').removeClass('none');
				$('#mra_office_div').removeClass('none');
				$('#mra_employee_div').removeClass('none');	

				$('#prepared_by_div').removeClass('none');
				$('#certified_by_div').removeClass('none');

				var attendance_period = $("#mra_attendance_period")[0].selectize;
				
				$.post($base_url + 'main/reports_ta/reports_ta/get_mra_attendance_period/', null, function(result)
				{
					attendance_period.clear();
					attendance_period.clearOptions();

				  	if(result.flag == 1){
						attendance_period.load(function(callback) {
							callback(result.list);
						});
				 	}				  
				}, 'json');
			break;

			case '<?php echo REPORTS_TA_LEAVE_BALANCE_STATEMENT; ?>':
				$('#prepared_by_div').removeClass('none');
				$('#approved_by_div').removeClass('none');				
				$('#office_list_div').removeClass('none');
				$('#tracking_code_div').removeClass('none');
			break;
		/*	case '<?php echo REPORTS_TA_LEAVE_BALANCE_STATEMENT; ?>':

			break;*/

			case '<?php echo REPORTS_TA_LEAVE_CREDIT_CERT; ?>':
				$('#office_filtered_div').removeClass('none');
				$('#employee_filtered_div').removeClass('none');
				$('#tracking_code_div').removeClass('none');
				$('#certified_by_div').removeClass('none');
			
			break;

			case '<?php echo REPORTS_TA_LEAVE_WITHOUT_PAY_CERT; ?>':
				$('#tracking_code_div').removeClass('none');				
				$('#office_filtered_div').removeClass('none');
				$('#employee_filtered_div').removeClass('none');
				$('#date_range_div').removeClass('none');
				$('#certified_by_div').removeClass('none');
				
			break;

			case '<?php echo REPORT_COE_WITH_COMPENSATIONS ?>': 
			case '<?php echo REPORT_COE_WITHOUT_COMPENSATIONS ?>': 
				$('#employee_div').removeClass('none');
				$('#tracking_code_div').removeClass('none');
				$('#certified_by_div').removeClass('none');
			break;

			case '<?php echo REPORTS_TA_NO_WORK_SCHED_LIST; ?>': 
				$('#payroll_type_div').removeClass('none');
				$('#date_range_div').removeClass('none');
			break;

		}
	});
	$('#employee,#reports_ta').on( "change", function() {
		var report  = $("#reports_ta").val();
  		var leave_request = $("#leave_request")[0].selectize;
		var data	= $('#form_reports').serialize();
		if($('#employee').val() != '' && report == '<?php echo REPORTS_TA_LEAVE_APPLICATION ?>')
		{
			$.post($base_url + 'main/reports_ta/reports_ta/get_leave_requests/', data, function(result)
			{
				leave_request.removeItem( $("#employee").val());
				leave_request.clear();
				leave_request.clearOptions();

			  	if(result.flag == 1){
					leave_request.load(function(callback) {
						callback(result.list);
					});
			 	}				  
			}, 'json');
		}
  	});
	$('#mra_attendance_period').on( "change", function() {
		var attendance_period  = $(this).val();

		var mra_office = $("#mra_office")[0].selectize;
		var data       = {'attendance_period':attendance_period};
		mra_office.clear();
		mra_office.clearOptions();

		if(attendance_period != '')
		{
			$.post($base_url + 'main/reports_ta/reports_ta/get_mra_office/', data, function(result)
			{
			  	if(result.flag == 1){
					mra_office.load(function(callback) {
						callback(result.list);
					});
			 	}				  
			}, 'json');
		}
  	});
	$('#mra_office').on( "change", function() {
		var mra_office  = $(this).val();

		var mra_employee = $("#mra_employee")[0].selectize;
		var data	= $('#form_reports').serialize();
		
		mra_employee.clear();
		mra_employee.clearOptions();

		if(mra_office != '')
		{
			$.post($base_url + 'main/reports_ta/reports_ta/get_mra_employee/', data, function(result)
			{
			  	if(result.flag == 1){
					mra_employee.load(function(callback) {
						callback(result.list);
					});
			 	}				  
			}, 'json');
		}
  	});

  	$('#payroll_type').on( "change", function() {
		var payroll_type  = $(this).val();

		var payroll_period = $("#payroll_period")[0].selectize;
		var data       = {'payroll_type':payroll_type};
		payroll_period.clear();
		payroll_period.clearOptions();

		if(payroll_type != '')
		{
			$.post($base_url + 'main/reports_ta/reports_ta/get_payroll_period/', data, function(result)
			{payroll_periodoffice_list_div
			  	if(result.flag == 1){
					payroll_period.load(function(callback) {
						callback(result.list);
					});
			 	}				  
			}, 'json');
		}
  	});
});

function init_employee_selectize(options)
{
	$('#employee')[0].selectize.destroy();
	$('#employee').html(options).selectize();
	
	$('#employee_row').removeClass('none');
	// $('#employee').attr('required','true');
}

$('#office_filtered').on("change", function() {
	var office_id  = $(this).val();
	var report  = $("#reports_ta").val(); //filter without job order davcorrea 10/03/2023
	//FOR GENERAL PAYSLIP EMPLOYEE
    load_selectize({
    	url: $base_url+'main/reports_ta/reports_ta/get_employee_by_office/',
    	// data: {office_id: office_id},
    	data: {office_id: office_id, report: report}, //add report davcorrea 10/03/2023
		target: 'employee_filtered'
	});
	
});


</script>