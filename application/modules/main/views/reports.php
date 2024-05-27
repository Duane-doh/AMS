<?php $data_id = 'modal_dtr_upload/' . ACTION_ADD; ?>
	
<!-- START CONTENT -->
<section id="content" class="p-t-n m-t-n ">
    
    <!--breadcrumbs start-->
    <div id="breadcrumbs-wrapper" class=" grey lighten-3"> 
      <div class="container">
        <div class="row">
         <div class="col s6 m6 l6">
				<ol class="breadcrumb m-n p-b-sm">
					<?php get_breadcrumbs();?>
				</ol>
				<h5 class="breadcrumbs-title"><?php echo SUB_MENU_REPORTS; ?></h5>
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
							<div class="row <?php echo (isset($page) and $page == 'attendance' OR $page == 'payroll') ? 'none':''?>" id="categories">
							    <div class="input-field col s3">
							      	<label class="label position-right">Category</label>
							    </div>
							    <div class="col s8 p-t-md">
									<select class="selectize" id="category" name="category" placeholder="Select category...">
										<option value="">Select category...</option>
										<option value="D">DEMOGRAPHICS</option>
										<option value="OS">ORGANIZATIONAL STRUCTURE</option>
										<option value="WAB">WELFARE AND BENEFITS</option>
										<option value="M">MEMBERSHIPS</option>
							    	</select>
							    </div>
							</div>
							<div class="row">
						    <div class="input-field col s3">
						      <label class="label position-right">Report <span class="required"> * </span></label>
						    </div>
						    <div class="col s8 p-t-md">
						      	<?php if($page == CODE_LIBRARY_HUMAN_RESOURCES) : ?>
									<select class="selectize" id="reports_hr" name="reports" placeholder="Select report..." required="true">
								        <option>Select Report...</option>
									</select>
								<?php endif;?>

								<?php if($page == CODE_LIBRARY_ATTENDANCE):?>
									<select class="selectize" id="reports_ta" name="reports" placeholder="Select report..." required="true">
										<option>Select Report...</option>
										<option value="<?php echo REPORTS_TA_DAILY_TIME_RECORD ?>">Daily Time Record</option>
										<option value="<?php echo REPORTS_TA_LEAVE_APPLICATION ?>">Application for Leave</option>
										<option value="<?php echo REPORTS_TA_LEAVE_CARD ?>">Leave Card</option>
										<option value="<?php echo REPORTS_TA_MONTHLY_ATTENDANCE ?>">Monthly Report of Attendance</option>
										<option value="<?php echo REPORTS_TA_LEAVE_AVAILMENT ?>">Report on the Availment of VL, SL, SPL and others</option>
										<option value="<?php echo REPORTS_TA_LEAVE_CREDIT_CERT ?>">Certificate of Leave Credits (Monthly)</option>
										<option value="<?php echo REPORTS_TA_LEAVE_WITHOUT_PAY_CERT ?>">Certificate of Leave Without Pay</option>
									 </select>
								<?php endif;?>
       
								<?php if($page == CODE_LIBRARY_PAYROLL):?>	
									<select class="selectize"  id="reports_p" name="reports" placeholder="Select report..." required="true">
								       	<option value="">Select a keyword...</option>
								        <!-- <option value="<?php //echo REPORT_MONTHLY_SALARY_SCHEDULE ?>">Monthly Salary Schedule</option>
								        <option value="<?php //echo REPORT_GSIS_CERTIFICATE_CONTRIBUTIONS ?>">GSIS Certificate of Contribution</option> -->
										<option value="<?php echo REPORT_GENERAL_PAYROLL_SUMMARY ?>">General Payroll Cover Sheet</option>
										<option value="<?php echo REPORT_GENERAL_PAYROLL_SUMMARY_GRAND_TOTAL ?>">General Payroll Summary Grand Total</option>
										<option value="<?php echo REPORT_GENERAL_PAYROLL_PER_OFFICE ?>">General Payroll Summary per Office</option>
										<option value="<?php echo REPORT_GENERAL_PAYROLL_ALPHALIST_PER_OFFICE ?>">General Payroll Alpha List per Office</option>
										<option value="<?php echo REPORT_SPECIAL_PAYROLL_COVER_SHEET ?>">Special Payroll Cover Sheet</option>
										<option value="<?php echo REPORT_SPECIAL_PAYROLL_SUMMARY_GRAND_TOTAL ?>">Special Payroll Summary Grand Total</option>
										<option value="<?php echo REPORT_SPECIAL_PAYROLL_SUMMARY_PER_OFFICE ?>">Special Payroll Summary per Office</option>
										<option value="<?php echo REPORT_SPECIAL_PAYROLL_ALPHA_LIST_PER_OFFICE ?>">Special Payroll Alpha List per Office</option>
										<option value="<?php echo REPORT_GENERAL_PAYSLIP_FOR_REGULARS_AND_NONCAREERS ?>">General Payslip for Regulars and Non-careers</option>
										<option value="<?php echo REPORT_GENERAL_FOR_CONSTRACTS_OF_SERVICE ?>">General Payslip for Contracts of Service</option>
										<option value="<?php echo REPORT_SPECIAL_PAYSLIP_FOR_REGULARS_AND_NONCAREERS ?>">Special Payslip for Regulars and Non-careers</option>
										<option value="<?php echo REPORT_BANK_PAYROLL_REGISTER ?>">Bank Payroll Register</option>
										<option value="<?php echo REPORT_ATM_ALPHA_LIST ?>">ATM Alpha List</option>
										<option value="<?php echo REPORT_REMITTANCE_SUMMARY_GRAND_TOTAL ?>">Remittance Summary Grand Total</option>
										<option value="<?php echo REPORT_REMITTANCE_SUMMARY_PER_OFFICE ?>">Remittance Summary per Office</option>
										<option value="<?php echo REPORT_REMITTANCE_LIST_PER_OFFICE ?>">Remittance List per Office</option>
										<option value="<?php echo REPORT_CONSOLIDATED_REMITTANCE_SUMMARY_PER_OFFICE ?>">Consolidated Remittance Summary per Office</option>
										<option value="<?php echo REPORT_CONSOLIDATED_REMITTANCE_LIST_PER_OFFICE ?>">Consolidated Remittance List per Office</option>
										<option value="<?php echo REPORT_GSIS_CONTRIBUTIONS_REMITTANCE_FILE_FOR_UPLOADING ?>">GSIS Contributions Remittance File for Uploading</option>
										<option value="<?php echo REPORT_PHILHEALTH_CONTRIBUTIONS_REMITTANCE_FILE_FOR_UPLOADING ?>">Philhealth Contributions Remittance File for Uploading</option>
										<option value="<?php echo REPORT_PAGIBIG_CONTRIBUTIONS_REMITTANCE_FILE_FOR_UPLOADING ?>">Pag-ibig Contributions Remittance File for Uploading</option>
										<option value="<?php echo REPORT_BIR_TAX_PAYMENTS ?>">BIR Tax Payments</option>
										<option value="<?php echo REPORT_DOH_COOP_REMITTANCE_FILE ?>">DOH Coop Remittance File</option>
										<option value="<?php echo REPORT_BIR_1601C_MONTHLY_REPORT_OF_TAX_WITHHELD ?>">BIR 1601-C (Monthly Report of Tax Withheld)</option>
										<option value="<?php echo REPORT_REPORT_BIR_2316_CERTIFICATE_OF_COMPENSATION_PAYMENT ?>">BIR 2316 (Certificate of Compensation Payment/ Tax Withheld)</option>
										<option value="<?php echo REPORT_BIR_ALPHALIST ?>">BIR Alphalist</option>
										<option value="<?php echo REPORT_BIR_ALPHALIST_WITH_PREVIOUS_EMPLOYER ?>">BIR Alphalist with Previous Employer</option>
										<option value="<?php echo REPORT_BIR_ALPHALIST_TERMINATED_BEFORE_YEAR_END ?>">BIR Alphalist Terminated before year end</option>
										<option value="<?php echo REPORT_YEAR_END_ADJUSTMENT_REPORT_PER_OFFFICE ?>">Year-end Adjustment Report per Office</option>
										<option value="<?php echo REPORT_DISBURSEMENT_VOUCHER ?>">Disbursement Voucher</option>
										<option value="<?php echo REPORT_ENGAS_FILE_FOR_UPLOADING ?>">eNGAS File for Uploading</option>	 	 	
								    </select>	
								<?php endif;?>

								<?php if($page == CODE_LIBRARY_SYSTEM):?>
										
								<?php endif;?>
						      
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
												<option value="<?php echo $o['org_code']?>"><?php echo $o['name'] ?></option>
											<?php endforeach;?>
										<?php endif;?>
								</select>
						    </div>
						</div>

						<!-- LIST OF COMPENSATION TYPE LIST -->
						<div id="compensation_type_div" class="row none">
						   <div class="input-field col s3">
						     	<label class="label position-right">Compensation Type<span class="required"> * </span></label>
						    </div>
						    <div class="col s8 p-t-md">
								<select id="compensation_type" name="compensation_type" class="selectize" placeholder="Select Compensation Type">
										<option value="">Select Compensation Type...</option>
										<?php if (!EMPTY($compensation_types)): ?>
											<?php foreach ($compensation_types as $c): ?>
												<option value="<?php echo $c['compensation_id']?>"><?php echo $c['compensation_name'] ?></option>
											<?php endforeach;?>
										<?php endif;?>
								</select>
						    </div>
						</div>

						<!-- LIST OF DEDUCTION TYPE LIST -->
						<div id="deduction_type_div" class="row none">
						   <div class="input-field col s3">
						     	<label class="label position-right">Deduction Type<span class="required"> * </span></label>
						    </div>
						    <div class="col s8 p-t-md">
								<select id="deduction_type" name="deduction_type" class="selectize" placeholder="Select Deduction Type">
										<option value="">Select Deduction Type...</option>
										<?php if (!EMPTY($deduction_types)): ?>
											<?php foreach ($deduction_types as $dt): ?>
												<option value="<?php echo $dt['deduction_id']?>"><?php echo $dt['deduction_name'] ?></option>
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
											<option value="<?php echo $emp['employee_id']?>"><?php echo $emp['employee_name'] ?></option>
										<?php endforeach;?>
									<?php endif;?>
								</select>
						    </div>
						</div>

						<!-- LIST OF WP EMPLOYEES -->
						<div class="row none" id="wp_employee_div">
						    <div class="input-field col s3">
						      	<label class="label position-right">Employee</label>
						    </div>
						    <div class="col s8 p-t-md">
								<select id="wp_employee" name="wp_employee" class="selectize" placeholder="Select Employee">
									<?php if (!EMPTY($wp_employees)): ?>
										<?php foreach ($wp_employees as $wp): ?>
											<option value="<?php echo $wp['employee_id']?>"><?php echo $wp['employee_name'] ?></option>
										<?php endforeach;?>
									<?php endif;?>
								</select>
						    </div>
						</div>
						<!-- LIST OF EMPLOYEES WITH LONGEVITY PAY -->
						<div class="row none" id="employee_longevity_div">
						    <div class="input-field col s3">
						      	<label class="label position-right">Employee</label>
						    </div>
						    <div class="col s8 p-t-md">
								<select id="employee_longevity" name="employee_longevity" class="selectize" placeholder="Select Employee">
									<?php if (!EMPTY($employee_longevity)): ?>
										<?php foreach ($employee_longevity as $el): ?>
											<option value="<?php echo $el['employee_id']?>"><?php echo $el['employee_name'] ?></option>
										<?php endforeach;?>
									<?php endif;?>
								</select>
						    </div>
						</div>
						<!-- LIST OF POSITIONS -->
						<div id="position_div" class="row none">
						   	<div class="input-field col s3">
						     	<label class="label position-right">Position</label>
						    </div>
						    <div class="col s8 p-t-md">
								<select id="position_level" name="position" class="selectize" placeholder="Select Position">
										<?php if (!EMPTY($position_level)): ?>
											<?php foreach ($position_level as $pos): ?>
												<option value="<?php echo $pos['position_level_id']?>"><?php echo $pos['position_level_name'] ?></option>
											<?php endforeach;?>
										<?php endif;?>
								</select>
						    </div>
						</div>
						<!-- LIST OF CLASSES -->
						<div id="class_div" class="row none">
						  	<div class="input-field col s3">
						     	<label class="label position-right">Class</label>
						    </div>
						    <div class="col s8 p-t-md">
								<select id="class" name="class" class="selectize" placeholder="Select Class">
										<?php if (!EMPTY($classes)): ?>
											<?php foreach ($classes as $cl): ?>
												<option value="<?php echo $cl['position_class_level_id']?>"><?php echo $cl['position_class_level_name'] ?></option>
											<?php endforeach;?>
										<?php endif;?>
								</select>
						    </div>
						</div>
						<!-- LIST OF SALARY GRADES -->
						<div id="salary_grade_div" class="row none">
						   	<div class="input-field col s3">
						    	<label class="label position-right">Salary Grade</label>
						    </div>
						    <div class="col s8 p-t-md">
								<select id="salary_grade" name="salary_grade" class="selectize" placeholder="Select Salary Grade">
										<?php if (!EMPTY($salary_grades)): ?>
											<?php foreach ($salary_grades as $grade): ?>
												<option value="<?php echo $grade['salary_grade']?>"><?php echo $grade['salary_grade'] ?></option>
											<?php endforeach;?>
										<?php endif;?>
								</select>
						    </div>
						</div>
						<!-- LIST OF BIRTH MONTH -->
						<div id="birth_month_div" class="row none">
						   	<div class="input-field col s3">
						    	<label class="label position-right">Birth Month</label>
						    </div>
						    <div class="col s8 p-t-md">
								<select class="selectize" id="birth_month" name="birth_month" placeholder="Select month...">
									<option value="01">January</option>
									<option value="02">February</option>
									<option value="03">March</option>
									<option value="04">April</option>
									<option value="05">May</option>
									<option value="06">June</option>
									<option value="07">July</option>
									<option value="08">August</option>
									<option value="09">September</option>
									<option value="10">October</option>
									<option value="11">November</option>
									<option value="12">December</option>
						    	</select>
						    </div>
						</div>
						<!-- ENTITLEMENT -->
						<div id="entitlement_div" class="row none">
						   	<div class="input-field col s3">
						    	<label class="label position-right">Entitlement</label>
						    </div>
						    <div class="col s8 p-t-md">
								<select class="selectize" id="entitlements" name="entitlements" placeholder="Select entitlement...">
									<option value="COMPENSATION_LONGEVITY">Longevity Pay</option>
									<option value="COMPENSATION_LOYALTY">Loyalty Pay</option>
						    	</select>
						    </div>
						</div>
						<!-- AGE RANGE -->
						<div id="age_div" class="row none">
							   <div class="input-field col s3">
							    	<label class="label position-right">Age Range</label>
							    </div>
							    <div class="col s2 p-t-md">
									<input type="number" name="age_from" id="age_from" min="0" placeholder="from...">
							    </div>
							    <div class="col s2 p-t-md">
									<input type="number" name="age_to" id="age_to" min="0" placeholder="to...">
							    </div>
						</div>
						<!-- LIST OF GENDERS -->
						<div id="gender_div" class="row none">
							   <div class="inputcompensation_type-field col s3">
							    	<label class="label position-right">Gender</label>
							    </div>
							    <div class="col s8 p-t-md">
									<select id="gender" name="gender" class="selectize" placeholder="Select Gender">
											<?php if (!EMPTY($genders)): ?>
												<?php foreach ($genders as $gender): ?>
													<option value="<?php echo $gender['gender_code']?>"><?php echo $gender['gender'] ?></option>
												<?php endforeach;?>
											<?php endif;?>
									</select>
							    </div>
						</div>
						<!-- LIST OF PROFESSION -->
						<div id="profession_div" class="row none">
						   <div class="input-field col s3">
						    	<label class="label position-right">Profession</label>
						    </div>
						    <div class="col s8 p-t-md">
								<select id="profession" name="profession" class="selectize" placeholder="Select Profession">
										<?php if (!EMPTY($professions)): ?>
											<?php foreach ($professions as $prof): ?>
												<option value="<?php echo $prof['degree_id']?>"><?php echo $prof['degree_name'] ?></option>
											<?php endforeach;?>
										<?php endif;?>
								</select>
						    </div>
						</div>
						<!-- LIST OF EMPLOYMENT STATUS -->
						<div id="employment_status_div" class="row none">
						   <div class="input-field col s3">
						    	<label class="label position-right">Employment Status</label>
						    </div>
						    <div class="col s8 p-t-md">
								<select id="employment_status" name="employment_status" class="selectize" placeholder="Select Employment Status">
										<?php if (!EMPTY($employment_status)): ?>
											<?php foreach ($employment_status as $status): ?>
												<option value="<?php echo $status['employment_status_id']?>"><?php echo $status['employment_status_name'] ?></option>
											<?php endforeach;?>
										<?php endif;?>
								</select>
						    </div>
						</div>
						<!-- LIST OF BENEFITS -->
						<div id="benefit_type_div" class="row none">
						   	<div class="input-field col s3">
						    	<label class="label position-right">Benefit Type</label>
						    </div>
						    <div class="col s8 p-t-md">
								<select id="benefit_type" name="benefit_type" class="selectize" placeholder="Select Benefit Type">
										<?php if (!EMPTY($benefit_types)): ?>
											<?php foreach ($benefit_types as $benefit): ?>
												<option value="<?php echo $benefit['compensation_id']?>"><?php echo $benefit['compensation_name'] ?></option>
											<?php endforeach;?>
										<?php endif;?>
								</select>
						    </div>
						</div>
						<!-- SERVICE LENGTH -->
						<div id="service_length_div" class="row none">
						  	<div class="input-field col s3">
						    	<label class="label position-right">Length of Service (month)</label>
						    </div>
						    <div class="col s2 p-t-md">
								<input class="number" type="number" name="service_length_from" id="service_length_from" min="0" placeholder="from...">
						    </div>
						    <div class="col s2 p-t-md">
								<input class="number" type="number" name="service_length_to" id="service_length_to" min="0" placeholder="to...">
						    </div>
						</div>						
						<!-- LIST OF OFFICES -->
						<div id="office_div" class="row none">
						   <div class="input-field col s3">
						     	<label class="label position-right">Office</label>
						    </div>
						    <div class="col s8 p-t-md">
								<select id="office" name="office" class="selectize" placeholder="Select Office">
										<?php if (!EMPTY($parent_offices)): ?>
											<?php foreach ($parent_offices as $po): ?>
												<option value="<?php echo $po['org_code']?>"><?php echo $po['name'] ?></option>
											<?php endforeach;?>
										<?php endif;?>
								</select>
						    </div>
						    <div class="input-field col s3">
						      	<label class="label position-right">Date</label>
						    </div>
						    <div class="col s8 p-t-md">
								<input type="text" class="validate datepicke r " name="date" id="date">
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
							
						<!-- MONTHLY SALARY SCHEDULE EFFECTIVE DATE LIST -->
						<div id="salary_schedule_div" class="row none">
						   	<div class="input-field col s3">
						     	<label class="label position-right">Effective list dateT</label>
						    </div>
						    <div class="col s8 p-t-md">
								<select id="effectivity_date" name="effectivity_date" class="selectize" placeholder="Select Effective Date">
										<option value="">Select Effectivity Date</option> 
										<?php if (!EMPTY($effectivity_date)): ?>
											<?php foreach ($effectivity_date as $effdate): ?>
												<option value="<?php echo $effdate['effectivity_date'] . '-' . $effdate['other_fund_flag'] ?>"><?php echo $effdate['effectivity_date'] . ($effdate['other_fund_flag'] == 'Y' ? ' - (Other Fund Source)' : '')?></option>
											<?php endforeach;?>
										<?php endif;?>
								</select>
						    </div>
						</div>
						<!-- PAYOUT DETAILS EFFECTIVE DATE LIST -->
						<div id="payout_effective_date_div" class="row none">
						   	<div class="input-field col s3">
						     	<label class="label position-right">Effective Date</label>
						    </div>
						    <div class="col s8 p-t-md">
								<select id="payout_effective_date" name="payout_effective_date" class="selectize" placeholder="Select Effective Date">
										<option value="">Select Effective Date</option> 
										<?php if (!EMPTY($payout_effective_date)): ?>
											<?php foreach ($payout_effective_date as $peffdate): ?>
												<option value="<?php echo $peffdate['effective_date']?>"><?php echo $peffdate['effective_date']?></option>
											<?php endforeach;?>
										<?php endif;?>
								</select>
						    </div>
						</div>

						<!-- DATE RANGE -->
						<div id="date_range_div" class="row none">
						   	<div class="input-field col s3">
						      	<label class="label position-right">Date Range <span class="required"> * </span></label>
						    </div>
						    <div class="col s4 p-t-md">
								<input type="text" class="validate datepicker_start" name="date_range_from" id="date_range_from" placeholder="From">
						    </div>
						    <div class="col s4 p-t-md">
								<input type="text" class="validate datepicker_end" name="date_range_to" id="date_range_to" placeholder="To">
						    </div>
						</div>


						<!-- SELECT PAYROLL TYPE -->
						<div id="payroll_type_div" class="row none">

							<div class="input-field col s3">
						     	<label class="label position-right">Payroll Types<span class="required"> * </span></label>
						    </div>


						    <div class="col s8 p-t-md">

						    	<select id="payroll_type" name="payroll_type" class="selectize" placeholder="Select Payroll Types">
										<option value="">Select Payroll Types</option> 
										<?php if (!EMPTY($payroll_types)): ?>
											<?php foreach ($payroll_types as $pt): ?>
												<option value="<?php echo $pt['payroll_type_id']?>"><?php echo $pt['payroll_type_name']?></option>
											<?php endforeach;?>
										<?php endif;?>
								</select>
						    </div>

						</div>
						<div id="payroll_period_div" class="row none">
						   <div class="input-field col s3">
						    	<label class="label position-right">Payroll Period <span class="required"> * </span></label>
						    </div>
						    <div class="col s8 p-t-md">
								<select id="payroll_period" name="payroll_period" class="selectize" placeholder="Select Payroll Period">
									<option value="">Select Payroll Period...</option>
								</select>
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
var page = '<?php echo $page ?>';
$(function(){	
	$("#generate_report").on('click', function (){
		$('#form_reports').parsley('destroy');

	    var radios = document.getElementsByName('format');

		for (var i = 0, length = radios.length; i < length; i++) {
		    if (radios[i].checked) {
		        // do whatever you want with the checked radio
		        var format = radios[i].value;

		        // only one radio can be logically checked, don't check the rest
		        break;
		    }
		}
		if ( $('#form_reports').parsley().isValid() ) {
			$('#form_reports').submit(function(e) {
		    	e.preventDefault();	
				var id    	= '';
				var path    = '';
				if(page == 'human_resources') {
 
					var report  = $("#reports_hr").val();
   
					switch(report)
					{
						case 'RAI_part1':
						case 'RAI_part2':
						case '<?php echo REPORT_PERSONNEL_MOVEMENT ?>':
							var office            = $('#office').val();
							var date              = $('#date').val();
							path                  = null + '/' + office + '/' + dateFormat(date, 'yyyy-m-d');
						break;
						case '<?php echo REPORT_SERVICE_RECORD ?>':
							var employee          = $('#employee').val();
							path                  = employee + '/' + null + '/' + null;
						break;
						case '<?php echo REPORT_GSIS_MEMBERSHIP_FORM ?>':
							var employee          = $('#employee').val();
							path                  = employee + '/' + null + '/' + null;
						break;
						case '<?php echo REPORT_PHILHEALTH_MEMBERSHIP_FORM ?>':
							var employee          = $('#employee').val();
							path                  = employee + '/' + null + '/' + null;
						break;
						case '<?php echo REPORT_PAGIBIG_MEMBERSHIP_FORM ?>':
							var employee          = $('#employee').val();
							path                  = employee + '/' + null + '/' + null;
						break;
						case '<?php echo REPORT_APPOINTMENT_CERTIFICATE ?>':
							var employee          = $('#employee').val();
							path                  = employee + '/' + null + '/' + null;
						break;
						//NCOCAMPO
						case '<?php echo REPORT_ASSUMPTION_TO_DUTY ?>':
							var employee          = $('#employee').val();
							path                  = employee + '/' + null + '/' + null;
						break;
						//01/11/2024
						case '<?php echo REPORT_PERSONAL_DATA_SHEET ?>':
							var employee          = $('#employee').val();
							path                  = employee + '/' + null + '/' + null;
						break;
						case '<?php echo REPORT_OFFICE ?>':
							var office            = $('#office').val();
							var date              = $('#date').val();
							path                  = null + '/' + office + '/' + dateFormat(date, 'yyyy-m-d');
						break;
						case '<?php echo REPORT_POSITION_TITLE ?>':
							var position_level_id = $('#position_level').val();
							var office            = $('#office').val();
							var date              = $('#date').val();							
							path                  = position_level_id + '/' + office + '/' + dateFormat(date, 'yyyy-m-d');
						break;
						case '<?php echo REPORT_CLASS ?>':
							var class_id          = $('#class').val();
							var office            = $('#office').val();
							var date              = $('#date').val();							
							path                  = class_id + '/' + office + '/' + dateFormat(date, 'yyyy-m-d');
						break;
						case '<?php echo REPORT_SALARY_GRADE ?>':
							var salary_grade      = $('#salary_grade').val();
							var office            = $('#office').val();
							var date              = $('#date').val();							
							path                  = salary_grade + '/' + office + '/' + dateFormat(date, 'yyyy-m-d');
						break;
						case '<?php echo REPORT_BIRTH_DATE ?>':
							var birth_month       = $('#birth_month').val();
							var office            = $('#office').val();
							var date              = $('#date').val();
							path                  = birth_month + '/' + office + '/' + dateFormat(date, 'yyyy-m-d');
						break;
						case '<?php echo REPORT_AGE ?>':
							var age_range         = $('#age_from').val() + '-' + $('#age_to').val();
							var office            = $('#office').val();
							var date              = $('#date').val();
							path                  = age_range + '/' + office + '/' + dateFormat(date, 'yyyy-m-d');
						break;
						case '<?php echo REPORT_GENDER ?>':
							var gender            = $('#gender').val();
							var office            = $('#office').val();
							var date              = $('#date').val();
							path                  = gender + '/' + office + '/' + dateFormat(date, 'yyyy-m-d');
						break;
						case '<?php echo REPORT_PROFESSION ?>':
							var profession        = $('#profession').val();
							var office            = $('#office').val();
							var date              = $('#date').val();
							path                  = profession + '/' + office + '/' + dateFormat(date, 'yyyy-m-d');
						break;
						case '<?php echo REPORT_EMPLOYMENT_STATUS ?>':
							var employment_status = $('#employment_status').val();
							var office            = $('#office').val();
							var date              = $('#date').val();
							path                  = employment_status + '/' + office + '/' + dateFormat(date, 'yyyy-m-d');
						break;
						case '<?php echo REPORT_BENEFIT_ENTITLEMENT ?>':
							var benefit_type      = $('#benefit_type').val();
							var office            = $('#office').val();
							var date              = $('#date').val();
							path                  = benefit_type + '/' + office + '/' + dateFormat(date, 'yyyy-m-d');
						break;
						case '<?php echo REPORT_SERVICE_LENGTH ?>':
							var service_length    = $('#service_length_from').val() + '-' + $('#service_length_to').val();
							var office            = $('#office').val();
							var date              = $('#date').val();
							path                  = service_length + '/' + office + '/' + dateFormat(date, 'yyyy-m-d');
						break;
						case '<?php echo REPORT_RETIREES ?>':
							var office            = $('#office').val();
							var date              = $('#date').val();							
							path                  = null + '/' + office + '/' + dateFormat(date, 'yyyy-m-d');
						break;
						case '<?php echo REPORT_RESIGNED_EMPLOYEES ?>':
							var office            = $('#office').val();
							var date              = $('#date').val();							
							path                  = null + '/' + office + '/' + dateFormat(date, 'yyyy-m-d');
						break;
						case '<?php echo REPORT_DROPPED_EMPLOYEES ?>':
							var office            = $('#office').val();
							var date              = $('#date').val();
							path                  = null + '/' + office + '/' + dateFormat(date, 'yyyy-m-d');
						break;
						case '<?php echo REPORT_TRANSFEREES ?>':
							var office            = $('#office').val();
							var date              = $('#date').val();							
							path                  = null + '/' + office + '/' + dateFormat(date, 'yyyy-m-d');
						break;
						case '<?php echo REPORT_NOTICE_SALARY_ADJUSTMENT ?>':
							var employee       	  = $('#wp_employee').val();
							path                  = employee + '/' + null + '/' + null;
						break;
						case '<?php echo REPORT_NOTICE_STEP_INCREMENT ?>':
							var employee       	  = $('#wp_employee').val();
							path                  = employee + '/' + null + '/' + null;
						break;
						case '<?php echo REPORT_GSIS_CERTIFICATE_CONTRIBUTION ?>':
							var employee_id     = $('#employee').val();
							var date_range_from = $('#date_range_from').val();
							var date_range_to   = $('#date_range_to').val();
							path                  = employee_id + '/' + dateFormat(date_range_from, 'yyyy-m-d') + '/' + dateFormat(date_range_to, 'yyyy-m-d');
						break;
						case '<?php echo REPORT_PHILHEALTH_CERTIFICATE_CONTRIBUTION ?>':
							var employee_id     = $('#employee').val();
							var date_range_from = $('#date_range_from').val();
							var date_range_to   = $('#date_range_to').val();
							path                  = employee_id + '/' + dateFormat(date_range_from, 'yyyy-m-d') + '/' + dateFormat(date_range_to, 'yyyy-m-d');
						break;
						case '<?php echo REPORT_PAGIBIG_CERTIFICATE_CONTRIBUTION ?>':
							var employee_id     = $('#employee').val();
							var date_range_from = $('#date_range_from').val();
							var date_range_to   = $('#date_range_to').val();
							path                  = employee_id + '/' + dateFormat(date_range_from, 'yyyy-m-d') + '/' + dateFormat(date_range_to, 'yyyy-m-d');
						break;
						case '<?php echo REPORT_NOTICE_LONGEVITY_PAY ?>':
							var employee          = $('#employee_longevity').val();
							path                  = employee + '/' + null + '/' + null;
						break;
						case '<?php echo REPORT_NOTICE_LONGEVITY_PAY_INCREASE ?>':
							var employee          = $('#employee_longevity').val();
							path                  = employee + '/' + null + '/' + null;
						break;
						case '<?php echo REPORT_MONTHLY_ACCESSION ?>':
							var office            = $('#office').val();
							var date              = $('#date').val();							
							path                  = null + '/' + office + '/' + dateFormat(date, 'yyyy-m-d');
						break;
						case '<?php echo REPORT_MONTHLY_SEPARATION ?>':
							var office            = $('#office_list').val();						
							path                  = null + '/' + office + '/' + dateFormat(date, 'yyyy-m-d');
						break;
					}
				} 

				if (page == 'attendance') {
					var report  = $("#reports_ta").val();

					if(report == "")
					{
						notification_msg("<?php echo ERROR ?>", "<b>Report </b> is required.");
						return false;
					}
					switch(report)
					{
						
						case '<?php echo REPORTS_TA_DAILY_TIME_RECORD; ?>':

							var employee        = $('#employee').val();
							var date_range_from = $('#date_range_from').val();
							var date_range_to   = $('#date_range_to').val();
							if(employee == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Employee </b> is required.");
								return false;
							}
							if(date_range_from == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Date Range From </b> is required.");
								return false;
							}
							if(date_range_to == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Date Range To </b> is required.");
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
							if(employee == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Employee </b> is required.");
								return false;
							}

						break;

						case '<?php echo REPORTS_TA_MONTHLY_ATTENDANCE; ?>':

							var attendance_period = $('#mra_attendance_period').val();
							var office            = $('#mra_office').val();
							var employee          = $('#employee').val();
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

						break;
					}	

				}
				if (page == 'payroll') {

					var report  = $("#reports_p").val();

					if(report == "")
					{
						notification_msg("<?php echo ERROR ?>", "<b>Report </b> is required.");
						return false;
					}
					switch(report)
					{
						
						case 'general_payroll_summary_grand_total':

							var payroll_period       = $('#payroll_period').val();

							if(payroll_period == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Payroll Period </b> is required.");
								return false;
							}

						break;

						

						case 'general_payroll_summary_per_office':

						var payroll_type = $('#payroll_type').val();

						var payroll_period       = $('#payroll_period').val();

						break;

						case 'monthly_salary_schedule':

							var effectivity_date  = $('#effectivity_date').val();
							//console.log('1');
							path                  = effectivity_date;

						break;

						case 'consolidated_remittance_list_per_office':


							var param = $('#deduction_type').val();
							var office = $('#office_list').val();
							var date = $('payout_effective_date').val();

							path                  = param + '/' + office + '/' + dateFormat(date, 'yyyy-m-d');

						break;

						case 'consolidated_remittance_summary_per_office':


							
							var office = $('#office_list').val();

							path                  = null + '/' + office;

						break;

						case 'special_payroll_summary_grand_total':

						var compensation_type        = $('#compensation_type').val();

						break;
					}	
				}

				if (page == 'system') {

				}
			if (page == 'attendance') {
			 	window.location = $base_url + 'main/reports/generate_reports/' + format + '/' + report + '/?' + $('#form_reports').serialize();	
			}
			else if(page == 'payroll') {
			 	window.location = $base_url + 'main/reports/generate_reports/' + format + '/' + report + '/?' + $('#form_reports').serialize();	
			}
			else
			{
				 window.location = $base_url + 'main/reports/generate_reports/' + format + '/' + report + '/' + path;
			}

  			});
	    }

	});

	$('#reports_hr').change(function() {
		$('#employee_div').addClass('none');
		$('#wp_employee_div').addClass('none');
		$('#employee_longevity_div').addClass('none');
		$('#office_div').addClass('none');
		$('#position_div').addClass('none');
		$('#class_div').addClass('none');
		$('#salary_grade_div').addClass('none');
		$('#gender_div').addClass('none');
		$('#profession_div').addClass('none');
		$('#employment_status_div').addClass('none');
		$('#benefit_type_div').addClass('none');
		$('#birth_month_div').addClass('none');
		$('#service_length_div').addClass('none');
		$('#age_div').addClass('none');
		$('#entitlement_div').addClass('none');
		$('#date_range_div').addClass('none');


		//get selected value`
		var selected = $(this).val();
		switch(selected)
		{
			case '<?php echo REPORT_SERVICE_RECORD ?>': 
				$('#employee_div').removeClass('none');
			break;

			case '<?php echo REPORT_PERSONAL_DATA_SHEET ?>': 
				$('#employee_div').removeClass('none');
			break;

			case '<?php echo REPORT_APPOINTMENT_CERTIFICATE ?>':
				$('#employee_div').removeClass('none');
			break;

			//NCOCAMPO
			case '<?php echo REPORT_ASSUMPTION_TO_DUTY ?>':
				$('#employee_div').removeClass('none');
			break;
			//01/11/2024

			case '<?php echo REPORT_RAI_PART2 ?>': 
				$('#office_div').removeClass('none');
			break;

			case '<?php echo REPORT_RAI_PART1 ?>': 
				$('#office_div').removeClass('none');
			break;

			case '<?php echo REPORT_PERSONNEL_MOVEMENT ?>': 
				$('#office_div').removeClass('none');
			break;

			case '<?php echo REPORT_OFFICE ?>': 
				$('#office_div').removeClass('none'); 
			break;

			case '<?php echo REPORT_POSITION_TITLE ?>': 
				$('#office_div').removeClass('none');
				$('#position_div').removeClass('none');
			break;

			case '<?php echo REPORT_CLASS ?>': 
				$('#office_div').removeClass('none');
				$('#class_div').removeClass('none');
			break;

			case '<?php echo REPORT_SALARY_GRADE ?>': 
				$('#office_div').removeClass('none');
				$('#salary_grade_div').removeClass('none');
			break;

			case '<?php echo REPORT_BIRTH_DATE ?>': 
				$('#office_div').removeClass('none');
				$('#birth_month_div').removeClass('none');
			break;

			case '<?php echo REPORT_AGE ?>': 
				$('#office_div').removeClass('none');
				$('#age_div').removeClass('none');
			break;

			case '<?php echo REPORT_GENDER ?>': 
				$('#office_div').removeClass('none');
				$('#gender_div').removeClass('none');
			break;

			case '<?php echo REPORT_PROFESSION ?>': 
				$('#office_div').removeClass('none');
				$('#profession_div').removeClass('none');
			break;

			case '<?php echo REPORT_EMPLOYMENT_STATUS ?>': 
				$('#office_div').removeClass('none');
				$('#employment_status_div').removeClass('none');
			break;

			case '<?php echo REPORT_BENEFIT_ENTITLEMENT ?>': 
				$('#office_div').removeClass('none');
				$('#benefit_type_div').removeClass('none');
			break;

			case '<?php echo REPORT_SERVICE_LENGTH ?>': 
				$('#office_div').removeClass('none');
				$('#service_length_div').removeClass('none');
			break;

			case '<?php echo REPORT_RETIREES ?>': 
				$('#office_div').removeClass('none');
			break;

			case '<?php echo REPORT_RESIGNED_EMPLOYEES ?>': 
				$('#office_div').removeClass('none');
			break;

			case '<?php echo REPORT_DROPPED_EMPLOYEES ?>': 
				$('#office_div').removeClass('none');
			break;

			case '<?php echo REPORT_PROMOTED_EMPLOYEES ?>': 
				$('#office_div').removeClass('none');
			break;

			case '<?php echo REPORT_ENTITLEMENT_LONGEVITY_PAY ?>': 
			break;

			case '<?php echo REPORT_TRANSFEREES ?>': 
				$('#office_div').removeClass('none');
			break;

			case '<?php echo REPORT_NOTICE_SALARY_ADJUSTMENT ?>': 
				$('#wp_employee_div').removeClass('none');
			break;

			case '<?php echo REPORT_NOTICE_STEP_INCREMENT ?>': 
				$('#wp_employee_div').removeClass('none');
			break;

			case '<?php echo REPORT_NOTICE_LONGEVITY_PAY; ?>': 
				$('#employee_longevity_div').removeClass('none');
			break;

			case '<?php echo REPORT_NOTICE_LONGEVITY_PAY_INCREASE; ?>': 
				$('#employee_longevity_div').removeClass('none');
			break;
			
			case '<?php echo REPORT_GSIS_CERTIFICATE_CONTRIBUTION; ?>': 
				$('#employee_div').removeClass('none');
				$('#date_range_div').removeClass('none');
			break;

			case '<?php echo REPORT_PHILHEALTH_CERTIFICATE_CONTRIBUTION; ?>': 
				$('#employee_div').removeClass('none');
				$('#date_range_div').removeClass('none');
			break;

			case '<?php echo REPORT_PAGIBIG_CERTIFICATE_CONTRIBUTION; ?>': 
				$('#employee_div').removeClass('none');
				$('#date_range_div').removeClass('none');
			break;

			case '<?php echo REPORT_GSIS_MEMBERSHIP_FORM; ?>': 
				$('#employee_div').removeClass('none');
			break;

			case '<?php echo REPORT_PHILHEALTH_MEMBERSHIP_FORM; ?>': 
				$('#employee_div').removeClass('none');
			break;

			case '<?php echo REPORT_PAGIBIG_MEMBERSHIP_FORM; ?>': 
				$('#employee_div').removeClass('none');
			break;

			case '<?php echo REPORT_MONTHLY_ACCESSION; ?>': 
				$('#office_div').removeClass('none');
			break;

			case '<?php echo REPORT_MONTHLY_SEPARATION; ?>': 
				$('#office_list_div').removeClass('none');
			break;
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


		var selected = $(this).val();
		switch(selected)
		{
			case '<?php echo REPORTS_TA_DAILY_TIME_RECORD; ?>': 
				$('#employee_div').removeClass('none');
				$('#date_range_div').removeClass('none');
			break;

			case '<?php echo REPORTS_TA_LEAVE_APPLICATION; ?>': 
				$('#employee_div').removeClass('none');
				$('#leave_request_div').removeClass('none');

			break;

			case '<?php echo REPORTS_TA_LEAVE_CARD; ?>':
				$('#employee_div').removeClass('none');			
			break;
			
			case '<?php echo REPORTS_TA_MONTHLY_ATTENDANCE; ?>':

				$('#mra_period_div').removeClass('none');
				$('#mra_office_div').removeClass('none');
				$('#mra_employee_div').removeClass('none');	

				var attendance_period = $("#mra_attendance_period")[0].selectize;
				
				$.post($base_url + 'main/reports/get_mra_attendance_period/', null, function(result)
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

			case '<?php echo REPORTS_TA_LEAVE_AVAILMENT; ?>':

			break;

			case '<?php echo REPORTS_TA_LEAVE_CREDIT_CERT; ?>':

				
			break;

			case '<?php echo REPORTS_TA_LEAVE_WITHOUT_PAY_CERT; ?>':

				
			break;

		}
	});
	$('#reports_p').change(function() {

		$('#salary_schedule_div').addClass('none');
		$('#office_list_div').addClass('none');
		$('#employee_div').addClass('none');
		$('#date_range_div').addClass('none');
		$('#office_list').addClass('none');
		$('#compensation_type_div').addClass('none');
		$('#deduction_type_div').addClass('none');
		$('#payout_effective_date_div').addClass('none');
		$('#payroll_type_div').addClass('none');

		$('#payroll_period_div').addClass('none');

		//get selected value`
		var selected = $(this).val();
		switch(selected)
		{

			case '<?php echo REPORT_GENERAL_PAYROLL_SUMMARY; ?>':
				
			break;

			case '<?php echo REPORT_GENERAL_PAYROLL_SUMMARY_GRAND_TOTAL; ?>':
				$('#payroll_type_div').removeClass('none');
				$('#payroll_period_div').removeClass('none');
			break;

			case '<?php echo REPORT_GENERAL_PAYROLL_PER_OFFICE; ?>':
				$('#payroll_type_div').removeClass('none');
				$('#payroll_period_div').removeClass('none');
				$('#office_list_div').removeClass('none');
			break;

			case '<?php echo REPORT_GENERAL_PAYROLL_ALPHALIST_PER_OFFICE; ?>':
				$('#payroll_type_div').removeClass('none');
				$('#payroll_period_div').removeClass('none');
				$('#office_list_div').removeClass('none');
			break;

			case '<?php echo REPORT_SPECIAL_PAYROLL_COVER_SHEET; ?>':
				
			break;

			case '<?php echo REPORT_SPECIAL_PAYROLL_SUMMARY_GRAND_TOTAL; ?>':
				$('#compensation_type_div').removeClass('none');
			break;

			case '<?php echo REPORT_CONSOLIDATED_REMITTANCE_SUMMARY_PER_OFFICE; ?>':
				$('#payout_effective_date_div').removeClass('none');
			break;

			case '<?php echo REPORT_SPECIAL_PAYROLL_ALPHA_LIST_PER_OFFICE; ?>':
				$('#payroll_type_div').removeClass('none');
				$('#payroll_period_div').removeClass('none');
				$('#office_list_div').removeClass('none');
			break;

			case '<?php echo REPORT_GENERAL_PAYSLIP_FOR_REGULARS_AND_NONCAREERS; ?>':
				$('#date_range_div').removeClass('none');
			break;

			case '<?php echo REPORT_GENERAL_FOR_CONSTRACTS_OF_SERVICE; ?>':
				$('#date_range_div').removeClass('none');
			break;

			case '<?php echo REPORT_SPECIAL_PAYSLIP_FOR_REGULARS_AND_NONCAREERS; ?>':
				$('#date_range_div').removeClass('none');
			break;

			case '<?php echo REPORT_BANK_PAYROLL_REGISTER; ?>':
				$('#date_range_div').removeClass('none');
			break;

			case '<?php echo REPORT_ATM_ALPHA_LIST; ?>':
				$('#date_range_div').removeClass('none');
			break;

			case '<?php echo REPORT_REMITTANCE_SUMMARY_GRAND_TOTAL; ?>':
				$('#date_range_div').removeClass('none');
			break;

			case '<?php echo REPORT_REMITTANCE_SUMMARY_PER_OFFICE; ?>':
				$('#date_range_div').removeClass('none');
			break;

			case '<?php echo REPORT_REMITTANCE_LIST_PER_OFFICE; ?>':
				$('#date_range_div').removeClass('none');
			break;
			
			case '<?php echo REPORT_CONSOLIDATED_REMITTANCE_SUMMARY_PER_OFFICE; ?>':
				$('#office_list_div').removeClass('none');
			break;

			case '<?php echo REPORT_CONSOLIDATED_REMITTANCE_LIST_PER_OFFICE; ?>':
				$('#office_list_div').removeClass('none');
				$('#deduction_type_div').removeClass('none');
				$('#payout_effective_date_div').removeClass('none');
			break;

			case '<?php echo REPORT_GSIS_CONTRIBUTIONS_REMITTANCE_FILE_FOR_UPLOADING; ?>':
				$('#date_range_div').removeClass('none');
			break;

			case '<?php echo REPORT_PHILHEALTH_CONTRIBUTIONS_REMITTANCE_FILE_FOR_UPLOADING; ?>':
				$('#date_range_div').removeClass('none');
			break;

			case '<?php echo REPORT_PAGIBIG_CONTRIBUTIONS_REMITTANCE_FILE_FOR_UPLOADING; ?>':
				$('#date_range_div').removeClass('none');
			break;

			case '<?php echo REPORT_BIR_TAX_PAYMENTS; ?>':
				$('#date_range_div').removeClass('none');
			break;

			case '<?php echo REPORT_DOH_COOP_REMITTANCE_FILE; ?>':
				$('#date_range_div').removeClass('none');
			break;

			case '<?php echo REPORT_BIR_1601C_MONTHLY_REPORT_OF_TAX_WITHHELD; ?>':
				$('#date_range_div').removeClass('none');
			break;

			case '<?php echo REPORT_REPORT_BIR_2316_CERTIFICATE_OF_COMPENSATION_PAYMENT; ?>':
				$('#date_range_div').removeClass('none');
			break;

			case '<?php echo REPORT_BIR_ALPHALIST; ?>':
				$('#date_range_div').removeClass('none');
			break;

			case '<?php echo REPORT_BIR_ALPHALIST_WITH_PREVIOUS_EMPLOYER; ?>':
				$('#date_range_div').removeClass('none');
			break;

			case '<?php echo REPORT_BIR_ALPHALIST_TERMINATED_BEFORE_YEAR_END; ?>':
				$('#date_range_div').removeClass('none');
			break;

			case '<?php echo REPORT_YEAR_END_ADJUSTMENT_REPORT_PER_OFFFICE; ?>':
				$('#date_range_div').removeClass('none');
			break;

			case '<?php echo REPORT_DISBURSEMENT_VOUCHER; ?>':
				$('#date_range_div').removeClass('none');
			break;

			case '<?php echo REPORT_ENGAS_FILE_FOR_UPLOADING; ?>':
				$('#date_range_div').removeClass('none');
			break;

			case '<?php echo REPORT_MONTHLY_SALARY_SCHEDULE; ?>': 
				$('#salary_schedule_div').removeClass('none');
			break;


		}
	});
	$('#category').on( "change", function() {
  		var category = $(this).val();

  		switch(category)
  		{
  			case 'D':
	  			var reports_hr = $("#reports_hr")[0].selectize;
				var report_list = [
					{
						'value' : '<?php echo REPORT_AGE?>', // DONE
						'text' : 'LIST AND NUMBER OF EMPLOYEES BY AGE'
					},
					{
						'value' : '<?php echo REPORT_BENEFIT_ENTITLEMENT?>', // DONE
						'text' : 'LIST AND NUMBER OF EMPLOYEES BY BENEFIT ENTITLEMENT'
					},
					{
						'value' : '<?php echo REPORT_BIRTH_DATE?>', // DONE
						'text' : 'LIST AND NUMBER OF EMPLOYEES BY BIRTH DATE'
					},
					{
						'value' : '<?php echo REPORT_CLASS?>', // DONE
						'text' : 'LIST AND NUMBER OF EMPLOYEES BY CLASS'
					},
					{
						'value' : '<?php echo REPORT_EMPLOYMENT_STATUS?>', // DONE
						'text' : 'LIST AND NUMBER OF EMPLOYEES BY EMPLOYMENT STATUS (PERMANENT, TEMPORARY, CONTRACTUAL AND CO-TERMINUS, ETC.)'
					},
					{
						'value' : '<?php echo REPORT_GENDER?>', // DONE
						'text' : 'LIST AND NUMBER OF EMPLOYEES BY GENDER'
					},
					{
						'value' : '<?php echo REPORT_SERVICE_LENGTH?>', // DONE
						'text' : 'LIST AND NUMBER OF EMPLOYEES BY LENGTH OF SERVICE'
					},
					{
						'value' : '<?php echo REPORT_OFFICE?>',  // DONE
						'text' : 'LIST AND NUMBER OF EMPLOYEES BY OFFICE'
					},
					{
						'value' : '<?php echo REPORT_POSITION_TITLE?>', // DONE
						'text' : 'LIST AND NUMBER OF EMPLOYEES BY POSITION LEVEL'
					},
					{
						'value' : '<?php echo REPORT_PROFESSION?>', // DONE
						'text' : 'LIST AND NUMBER OF EMPLOYEES BY PROFESSION'
					},
					{
						'value' : '<?php echo REPORT_SALARY_GRADE?>', // DONE
						'text' : 'LIST AND NUMBER OF EMPLOYEES BY SALARY GRADE'
					},
					{
						'value' : '<?php echo REPORT_DROPPED_EMPLOYEES ?>', // DONE
						'text' : 'NUMBER OF EMPLOYEES DROPPED FROM THE ROLL'
					},
					{
						'value' : '<?php echo REPORT_PROMOTED_EMPLOYEES ?>', // DONE
						'text' : 'NUMBER OF PROMOTED EMPLOYEES'
					},
					{
						'value' : '<?php echo REPORT_RESIGNED_EMPLOYEES ?>', // DONE
						'text' : 'NUMBER OF RESIGNED EMPLOYEES' 
					},
					{
						'value' : '<?php echo REPORT_RETIREES ?>', // DONE
						'text' : 'NUMBER OF RETIREES'
					}
					];

				reports_hr.clear();
				reports_hr.clearOptions();

				reports_hr.load(function(callback) {
					callback(report_list);
				});
				$("#reports_hr").selectize();
  			break;

  			case 'OS': 
	  			var reports_hr = $("#reports_hr")[0].selectize;
				var report_list = [
					{
						'value' : '<?php echo REPORT_SERVICE_RECORD; ?>', // DONE
						'text' : 'SERVICE RECORD'
					},
					{
						'value' : '<?php echo REPORT_APPOINTMENT_CERTIFICATE ?>', //DONE
						'text' : 'APPOINTMENT CERTIFICATE (KSS PORMA BLG. 33)'
					},
					//NCOCAMPO
					{
						'value' : '<?php echo REPORT_ASSUMPTION_TO_DUTY ?>', //DONE
						'text' : 'CERTIFICATE OF ASSUMPTION TO DUTY AND OATH OF OFFICE'
					},
					//01/11/2024
					{
						'value' : '<?php echo REPORT_MONTHLY_ACCESSION ?>', // NO QUERY BUT HAVE TEMPLATE
						'text' : 'MONTHLY REPORT ON ACCESSION'
					},
					{
						'value' : '<?php echo REPORT_MONTHLY_SEPARATION ?>', // NO QUERY BUT HAVE TEMPLATE
						'text' : 'MONTHLY REPORT ON SEPARATION'
					}, 
					{
						'value' : '<?php echo REPORT_FILLED_UNFILLED_POSITION ?>',// DONE
						'text' : 'REPORT ON FILLED AND UNFILLED POSITIONS'
					},
					{
						'value' : '<?php echo REPORT_PSIPOP_PLANTILLA?>', // DONE
						'text' : 'PERSONAL SERVICES ITEMIZATION AND PLANTILLA OF PERSONNEL (PSIPOP)'
					},
					{
						'value' : '<?php echo REPORT_PERSONNEL_MOVEMENT?>', // DONE
						'text' : 'PERSONNEL MOVEMENT (PART I and PART II)'
					},
					{
						'value' : '<?php echo REPORT_NDHRHIS_FILE ?>', // NO QUERY NO TEMPLATE
						'text' : 'NDHRHIS FILE FOR UPLOADING'
					},
					{
						'value' : '<?php echo REPORT_PERSONAL_DATA_SHEET ?>', // DONE
						'text' : 'PERSONAL DATA SHEET'
					},
					{
						'value' : '<?php echo REPORT_RAI_PART1 ?>', // DONE
						'text' : 'REPORT ON APPOINTMENT ISSUED PART I'
					},
					{
						'value' : '<?php echo REPORT_RAI_PART2 ?>', // DONE
						'text' : 'REPORT ON APPOINTMENT ISSUED PART II'
					},
					{
						'value' : '<?php echo REPORT_TRANSFEREES ?>',// DONE
						'text' : 'TRANSFEREE/S IN AND OUT'
					},
					{
						'value' : '<?php echo REPORT_PRIME_HRM_ASSESSMENT ?>', // NO QUERY BUT HAVE TEMPLATE
						'text' : 'PRIME-HRM ASSESSMENT REPORT (AGENCY PROFILE)'
					}

					];

				reports_hr.clear();
				reports_hr.clearOptions();

				reports_hr.load(function(callback) {
					callback(report_list);
				});
				$("#reports_hr").selectize();
  			break;

  			case 'WAB':
	  			var reports_hr = $("#reports_hr")[0].selectize;
				var report_list = [
					{
						'value' : '<?php echo REPORT_ENTITLEMENT_LONGEVITY_PAY ?>',//DONE
						'text' : 'ENTITLEMENTOF LONGEVITY PAY'
					},
					{
						'value' : '<?php echo REPORT_NOTICE_SALARY_ADJUSTMENT ?>',//DONE
						'text' : 'NOTICE OF SALARY ADJUSTMENT'
					},		
					{
						'value' : '<?php echo REPORT_NOTICE_STEP_INCREMENT ?>',//DONE
						'text' : 'NOTICE OF STEP INCREMENT'
					},			
					{ 
						'value' : '<?php echo REPORT_NOTICE_LONGEVITY_PAY ?>',//DONE
						'text' : 'NOTICE OF LONGEVITY PAY'
					},			
					{
						'value' : '<?php echo REPORT_NOTICE_LONGEVITY_PAY_INCREASE ?>',//DONE
						'text' : 'NOTICE OF LONGEVITY PAY INCREASE'
					},		
					{
						'value' : '<?php echo REPORT_GSIS_CERTIFICATE_CONTRIBUTION ?>',//DONE
						'text' : 'GSIS CERTIFICATE OF CONTRIBUTION'
					},		
					{
						'value' : '<?php echo REPORT_PHILHEALTH_CERTIFICATE_CONTRIBUTION ?>',//DONE
						'text' : 'PHILHEALTH CERTIFICATE OF CONTRIBUTION'
					},		
					{
						'value' : '<?php echo REPORT_PAGIBIG_CERTIFICATE_CONTRIBUTION ?>',//DONE
						'text' : 'PAG-IBIG CERTIFICATE OF CONTRIBUTION'
					}				
					];

				reports_hr.clear();
				reports_hr.clearOptions();

				reports_hr.load(function(callback) {
					callback(report_list);
				});
				$("#reports_hr").selectize();
  			break;

  			case 'M':
	  			var reports_hr = $("#reports_hr")[0].selectize;
				var report_list = [	
					{
						'value' : '<?php echo REPORT_GSIS_MEMBERSHIP_FORM ?>',
						'text' : 'GSIS MEMBER REGISTRATION FORM'
					},
					{
						'value' : '<?php echo REPORT_PAGIBIG_MEMBERSHIP_FORM ?>',
						'text' : 'PAG-IBIG MEMBER REGISTRATION FORM'
					},
					{
						'value' : '<?php echo REPORT_PHILHEALTH_MEMBERSHIP_FORM ?>',
						'text' : 'PHILHEALTH MEMBER REGISTRATION FORM'
					}			
					];

				reports_hr.clear();
				reports_hr.clearOptions();

				reports_hr.load(function(callback) {
					callback(report_list);
				});
				$("#reports_hr").selectize();
  			break;
  		}

  	});
	$('#employee').on( "change", function() {
		var report  = $("#reports_ta").val();
		if(report == 'ta_leave_aplication')
		{
	  		var leave_request = $("#leave_request")[0].selectize;
			var data	= $('#form_reports').serialize();
			
			$.post($base_url + 'main/reports/get_leave_requests/', data, function(result)
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
			$.post($base_url + 'main/reports/get_mra_office/', data, function(result)
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
			$.post($base_url + 'main/reports/get_mra_employee/', data, function(result)
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
			$.post($base_url + 'main/reports/get_payroll_period/', data, function(result)
			{payroll_period
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


</script>