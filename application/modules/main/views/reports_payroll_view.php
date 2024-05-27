
<!-- START CONTENT -->
<section id="content" class="p-t-n m-t-n ">
    
    <!--breadcrumbs start-->
    <div id="breadcrumbs-wrapper" class=" grey lighten-3"> 
      <div class="container">
        <div class="row">
         <div class="col s6 m6 l6">
				<h5 class="breadcrumbs-title">Payroll Reports</h5>
				<ol class="breadcrumb m-n p-b-sm">
					<?php get_breadcrumbs();?>
				</ol>
			</div>
        </div>
      </div>
    </div>
    <!--breadcrumbs end-->
    
    <!--start container-->
    <div class="container p-t-n">
    <div class="section panel p-lg">
  <!--start section-->
	      	
    <form id="form_reports" name="form_reports" class="form-vertical form-styled form-basic">
	<div class="row">
		<div class="col s8 m-b-n-lg">
			<ul class="collapsible panel" data-collapsible="expandable">
				<li>
					<div class="collapsible-header active teal white-text">Report Description</div>
					<div class="collapsible-body teal lighten-5" style="min-height:150px">
							
						<div class="row" id="report_div">
						    <div class="input-field col s3">
						      <label class="label position-right">Report <span class="required"> * </span></label>
						    </div>
						    <div class="col s8 p-t-md">
									<select class="selectize"  id="reports_p" name="reports" placeholder="Select report..." required="true">
								       	<option value="">Select a keyword...</option>
								        <!-- <option value="<?php //echo REPORT_MONTHLY_SALARY_SCHEDULE ?>">Monthly Salary Schedule</option>
								        <option value="<?php //echo REPORT_GSIS_CERTIFICATE_CONTRIBUTIONS ?>">GSIS Certificate of Contribution</option> -->
										<?php if($this->permission->check_permission(MODULE_PAYROLL_REPORT_GENERAL_COVER_SHEET)) :?>
											<option value="<?php echo REPORT_GENERAL_PAYROLL_SUMMARY ?>">General Payroll Cover Sheet</option>
										<?php endif; ?>
										<?php if($this->permission->check_permission(MODULE_PAYROLL_REPORT_GENERAL_SUMMARY_GRAND_TOTAL)) :?>
											<option value="<?php echo REPORT_GENERAL_PAYROLL_SUMMARY_GRAND_TOTAL ?>">General Payroll Summary Grand Total</option>
										<?php endif; ?>
										<?php if($this->permission->check_permission(MODULE_PAYROLL_REPORT_GENERAL_SUMMARY_PER_OFFICE)) :?>
											<option value="<?php echo REPORT_GENERAL_PAYROLL_PER_OFFICE ?>">General Payroll Summary per Office</option>
										<?php endif; ?>
										<?php if($this->permission->check_permission(MODULE_PAYROLL_REPORT_GENERAL_ALPHA_LIST_PER_OFFICE)) :?>
											<option value="<?php echo REPORT_GENERAL_PAYROLL_ALPHALIST_PER_OFFICE ?>">General Payroll Alpha List for Regular</option>
										<?php endif; ?>
										<?php if($this->permission->check_permission(MODULE_PAYROLL_REPORT_GENERAL_PAYROLL_ALPHALIST_FOR_JO)) :?>
											<option value="<?php echo REPORT_GENERAL_PAYROLL_ALPHALIST_FOR_JO ?>">General Payroll Alpha List for JO</option>
										<?php endif; ?>		
										<?php if($this->permission->check_permission(MODULE_PAYROLL_REPORT_SPECIAL_COVER_SHEET)) :?>
											<option value="<?php echo REPORT_SPECIAL_PAYROLL_COVER_SHEET ?>">Special Payroll Cover Sheet</option>
										<?php endif; ?>
										<!-- <option value="<?php echo REPORT_SPECIAL_PAYROLL_SUMMARY_GRAND_TOTAL ?>">Special Payroll Summary Grand Total</option> -->
										<?php if($this->permission->check_permission(MODULE_PAYROLL_REPORT_SPECIAL_SUMMARY_PER_OFFICE)) :?>
											<option value="<?php echo REPORT_SPECIAL_PAYROLL_SUMMARY_PER_OFFICE ?>">Special Payroll Summary per Office</option> 
										<?php endif; ?>
										<?php if($this->permission->check_permission(MODULE_PAYROLL_REPORT_SPECIAL_ALPHA_LIST_PER_OFFICE)) :?>
											<option value="<?php echo REPORT_SPECIAL_PAYROLL_ALPHA_LIST_PER_OFFICE ?>">Special Payroll Alpha List per Office</option>
										<?php endif; ?>
										<?php if($this->permission->check_permission(MODULE_PAYROLL_REPORT_GENERAL_PAYSLIP_EMPLOYEES)) :?>
											<option value="<?php echo REPORT_GENERAL_PAYSLIP_FOR_REGULARS_AND_NONCAREERS ?>">General Payslip for Regulars and Non-careers</option>
										<?php endif; ?>
										<?php if($this->permission->check_permission(MODULE_PAYROLL_REPORT_GENERAL_PAYSLIP_JOB_ORDER)) :?>
											<option value="<?php echo REPORT_GENERAL_FOR_CONSTRACTS_OF_SERVICE ?>">General Payslip for Contracts of Service</option>
										<?php endif; ?>
										<?php if($this->permission->check_permission(MODULE_PAYROLL_REPORT_SPECIAL_PAYSLIP_EMPLOYEES)) :?>
											<option value="<?php echo REPORT_SPECIAL_PAYSLIP_FOR_REGULARS_AND_NONCAREERS ?>">Special Payslip for Regulars and Non-careers</option>
										<?php endif; ?>
										<?php if($this->permission->check_permission(MODULE_PAYROLL_REPORT_PAYROLL_REGISTER)) :?>
											<option value="<?php echo REPORT_BANK_PAYROLL_REGISTER ?>">Bank Payroll Register</option>
										<?php endif; ?>
										<?php if($this->permission->check_permission(MODULE_PAYROLL_REPORT_BANK_PAYROLL_ATM_ALPHA_LIST)) :?>
											<option value="<?php echo REPORT_ATM_ALPHA_LIST ?>">ATM Alpha List</option>
										<?php endif; ?>
										<?php if($this->permission->check_permission(MODULE_PAYROLL_REPORT_BANK_PAYROLL_ATM_ALPHA_LIST2)) :?>
											<option value="<?php echo REPORT_ATM_ALPHA_LIST2 ?>">ATM Alpha List 2</option>
										<?php endif; ?>
										<?php if($this->permission->check_permission(MODULE_PAYROLL_REPORT_REMITTANCE_SUMMARY_GRAND_TOTAL)) :?>
											<option value="<?php echo REPORT_REMITTANCE_SUMMARY_GRAND_TOTAL ?>">Remittance Summary per Office</option>
										<?php endif; ?>
										<?php if($this->permission->check_permission(MODULE_PAYROLL_REPORT_REMITTANCE_SUMMARY_OFFICE)) :?>
											<option value="<?php echo REPORT_REMITTANCE_SUMMARY_PER_OFFICE ?>">Remittance Summary Grand Total</option>
										<?php endif; ?>
										<?php if($this->permission->check_permission(MODULE_PAYROLL_REPORT_REMITTANCE_LIST_OFFICE)) :?>
											<option value="<?php echo REPORT_REMITTANCE_LIST_PER_OFFICE ?>">Remittance List</option>
										<?php endif; ?>
										<?php if($this->permission->check_permission(MODULE_PAYROLL_REPORT_CONSOLIDATED_REMITTANCE_SUMMARY_OFFICE)) :?>
											<option value="<?php echo REPORT_CONSOLIDATED_REMITTANCE_SUMMARY_PER_OFFICE ?>">Consolidated Remittance Summary</option>
										<?php endif; ?>
										<?php if($this->permission->check_permission(MODULE_PAYROLL_REPORT_CONSOLIDATED_REMITTANCE_LIST_OFFICE)) :?>
											<option value="<?php echo REPORT_CONSOLIDATED_REMITTANCE_LIST_PER_OFFICE ?>">Consolidated Remittance List</option>
										<?php endif; ?>
										<?php if($this->permission->check_permission(MODULE_PAYROLL_REPORT_RFU_GSIS_CONTRIBUTIONS)) :?>
											<option value="<?php echo REPORT_GSIS_CONTRIBUTIONS_REMITTANCE_FILE_FOR_UPLOADING ?>">GSIS Contributions Remittance File for Uploading</option>
										<?php endif; ?>
										<?php if($this->permission->check_permission(MODULE_PAYROLL_REPORT_RFU_PHILHEALTH_CONTRIBUTIONS)) :?>
											<option value="<?php echo REPORT_PHILHEALTH_CONTRIBUTIONS_REMITTANCE_FILE_FOR_UPLOADING ?>">Philhealth Contributions Remittance File for Uploading</option>
										<?php endif; ?>
										<?php if($this->permission->check_permission(MODULE_PAYROLL_REPORT_RFU_PAGIBIG_CONTRIBUTIONS)) :?>
											<option value="<?php echo REPORT_PAGIBIG_CONTRIBUTIONS_REMITTANCE_FILE_FOR_UPLOADING ?>">Pag-ibig Contributions Remittance File (MCRF) for Uploading</option>
										<?php endif; ?>
										<option value="<?php echo REPORT_PAGIBIG_DEDUCTIONS_REMITTANCE_FILE_FOR_UPLOADING ?>">Pag-ibig Deductions Remittance File for Uploading</option>
 										<?php if($this->permission->check_permission(MODULE_PAYROLL_REPORT_RFU_BIR_TAX_PAYMENTS)) :?>
 											<option value="<?php echo REPORT_BIR_TAX_PAYMENTS ?>">BIR Tax Payments</option>
										<?php endif; ?>
										<?php if($this->permission->check_permission(MODULE_PAYROLL_REPORT_BIR_1601_C)) :?>
											<option value="<?php echo REPORT_BIR_1601C_MONTHLY_REPORT_OF_TAX_WITHHELD ?>">BIR 1601-C (Monthly Report of Tax Withheld)</option>
										<?php endif; ?>
										<?php if($this->permission->check_permission(MODULE_PAYROLL_REPORT_BIR_2316)) :?>
											<option value="<?php echo REPORT_REPORT_BIR_2316_CERTIFICATE_OF_COMPENSATION_PAYMENT ?>">BIR 2316 (Certificate of Compensation Payment/ Tax Withheld)</option>
										<?php endif; ?>
										<?php if($this->permission->check_permission(MODULE_PAYROLL_REPORT_BIR_ALPHALIST)) :?>
											<option value="<?php echo REPORT_BIR_ALPHALIST ?>">BIR Alphalist</option>
										<?php endif; ?>
										<!-- <option value="<?php //echo REPORT_BIR_ALPHALIST_WITH_PREVIOUS_EMPLOYER ?>">BIR Alphalist with Previous Employer</option>
										<option value="<?php //echo REPORT_BIR_ALPHALIST_TERMINATED_BEFORE_YEAR_END ?>">BIR Alphalist Terminated before year end</option> -->
										<?php if($this->permission->check_permission(MODULE_PAYROLL_REPORT_YEAREND_ADJUSTMENT_REPORT_OFFICE)) :?>
											<option value="<?php echo REPORT_YEAR_END_ADJUSTMENT_REPORT_PER_OFFFICE ?>">Year-end Adjustment Report per Office</option>
										<?php endif; ?>
										<option value="<?php echo REPORT_DISBURSEMENT_VOUCHER ?>">Disbursement Voucher</option>
										<?php if($this->permission->check_permission(MODULE_PAYROLL_REPORT_ENGAS_FILE_FOR_UPLOADING)) :?>
											<option value="<?php echo REPORT_ENGAS_FILE_FOR_UPLOADING ?>">eNGAS File for Uploading</option>	 						
										<?php endif; ?>
										<?php if($this->permission->check_permission(MODULE_PAYROLL_REPORT_COC_GSIS)) :?>
									     	<option value="<?php echo REPORT_GSIS_CERTIFICATE_CONTRIBUTION ?>">GSIS CERTIFICATE OF CONTRIBUTION</option>
										<?php endif; ?>
										<?php if($this->permission->check_permission(MODULE_PAYROLL_REPORT_COC_PAGIBIG)) :?>
											<option value="<?php echo REPORT_PAGIBIG_CERTIFICATE_CONTRIBUTION ?>">PAG-IBIG CERTIFICATE OF CONTRIBUTION</option>
										<?php endif; ?>							
										<?php if($this->permission->check_permission(MODULE_PAYROLL_REPORT_COC_PHILHEALTH)) :?>
											<option value="<?php echo REPORT_PHILHEALTH_CERTIFICATE_CONTRIBUTION ?>">PHILHEALTH CERTIFICATE OF CONTRIBUTION</option>
										<?php endif; ?>			
										<?php if($this->permission->check_permission(MODULE_HR_REPORT_GSIS_MEMBERSHIP_FORM)) :?>
											<option value="<?php echo REPORT_GSIS_MEMBERSHIP_FORM ?>">GSIS MEMBER REGISTRATION FORM</option>
										<?php endif; ?>					
										<?php if($this->permission->check_permission(MODULE_HR_REPORT_PAGIBIG_MEMBERSHIP_FORM)) :?>
											<option value="<?php echo REPORT_PAGIBIG_MEMBERSHIP_FORM ?>">PAG-IBIG MEMBER REGISTRATION FORM</option>
										<?php endif; ?>					
										<?php if($this->permission->check_permission(MODULE_HR_REPORT_PHILHEALTH_MEMBERSHIP_FORM)) :?>
											<option value="<?php echo REPORT_PHILHEALTH_MEMBERSHIP_FORM ?>">PHILHEALTH MEMBER REGISTRATION FORM</option>
										<?php endif; ?>
										<?php if($this->permission->check_permission(MODULE_PAYROLL_REPORT_BIR_2307)) :?>
											<option value="<?php echo REPORT_BIR_2307_CERTIFICATE_OF_CREDITABLE_TAX_WITHHELD_AT_SOURCE ?>">BIR 2307 (CERTIFICATE OF CREDITABLE TAX WITHHELD)</option>
										<?php endif; ?>
										<?php if($this->permission->check_permission(MODULE_PAYROLL_REPORT_EMPLOYEES_PAID_BY_VOUCHER)) :?>
											<option value="<?php echo REPORT_EMPLOYEES_PAID_BY_VOUCHER ?>">Employees Paid by Voucher</option>
										<?php endif; ?>
										<?php if($this->permission->check_permission(MODULE_PAYROLL_REPORT_BIR_2306)) :?>
											<option value="<?php echo REPORT_BIR_2306_CERTIFICATE_OF_FINAL_TAX_WITHHELD_AT_SOURCE ?>">BIR 2306 (Certificate of Final Tax Withheld At Source)</option>
										<?php endif; ?>
										<?php if($this->permission->check_permission(MODULE_PAYROLL_REPORT_EMPLOYEES_NOT_INCLUDED_IN_PAYROLL)) :?>
											<option value="<?php echo REPORT_EMPLOYEES_NOT_INCLUDED_IN_PAYROLL ?>">Employees Not Included In Payroll</option>
										<?php endif; ?>
										<?php if($this->permission->check_permission(MODULE_PAYROLL_REPORT_BIR_2305)) :?>
											<option value="<?php echo REPORT_BIR_2305_CERTIFICATE_OF_UPDATE ?>">BIR 2305 (Certificate of Update of Exemption and of Employer’s and Employee’s Information)</option>
										<?php endif; ?>
										<?php if($this->permission->check_permission(MODULE_PAYROLL_REPORT_RESPONSIBILITY_CODE_PER_OFFICE)) :?>
											<option value="<?php echo REPORT_RESPONSIBILITY_CODE_PER_OFFICE ?>">Responsibility Code per Office</option>
										<?php endif; ?>
										<?php if($this->permission->check_permission(MODULE_PAYROLL_REPORT_GENERAL_PAYROLL_ALPHALIST_FOR_JO)) :?>
											<option value="<?php echo REPORT_GENERAL_PAYROLL_ALPHALIST_FOR_JO ?>">General Payroll Alphalist for JO</option>
										<?php endif; ?>			
										<?php if($this->permission->check_permission(MODULE_PAYROLL_REPORT_EXPANDED_WITHHOLDING_TAX_AND_GOVERNMENT_MONEY_PAYMENT)) :?>
											<option value="<?php echo REPORT_EXPANDED_WITHHOLDING_TAX_AND_GOVERNMENT_MONEY_PAYMENT?>">Expanded Withholding Tax and Government Money Payment</option>
										<?php endif; ?>	
								    </select>
						      <!-- END STRUCTURE -->
						    </div>
						</div>
						
						<!-- LIST OF OFFICES WITHOUT DATE -->
						<div id="office_list_div" class="row none">
						   <div class="input-field col s3">
						     	<label class="label position-right">Office<span class="required" id="office_list_span"> * </span></label>
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
						
						<!-- LIST OF COMPENSATION TYPE LIST -->
						<div id="bank_div" class="row none">
						   <div class="input-field col s3">
						     	<label class="label position-right">Bank<span class="required"> * </span></label>
						    </div>
						    <div class="col s8 p-t-md">
								<select id="bank" name="bank" class="selectize" placeholder="Select Bank">
										<option value="">Select Bank...</option>
										<?php if (!EMPTY($bank)): ?>
											<?php foreach ($bank as $b): ?>
												<option value="<?php echo $b['bank_id']?>"><?php echo $b['bank_name'] ?></option>
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
						
						<!-- LIST OF SPECIAL COMPENSATION TYPE LIST -->
						<div id="compensation_type_special_div" class="row none">
						   <div class="input-field col s3">
						     	<label class="label position-right">Compensation Type<span class="required"> * </span></label>
						    </div>
						    <div class="col s8 p-t-md">
								<select id="compensation_special_type" name="compensation_special_type" class="selectize" placeholder="Select Compensation Type">
										<option value="">Select Compensation Type...</option>
										<?php if (!EMPTY($compensation_types_special)): ?>
											<?php foreach ($compensation_types_special as $c): ?>
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
						
						<!-- LIST OF FILTERED EMPLOYEES BY OFFICE -->
						<div class="row none" id="employee_filtered_div">
						    <div class="input-field col s3">
						      	<label class="label position-right">Employee <span class="required" id="employee_span"> * </span></label>
						    </div>
						    <div class="col s8 p-t-md">
								<select id="employee_filtered" name="employee_filtered" class="selectize" placeholder="Select Employee">
									<option value="">Select Employee...</option>
								</select>
						    </div>
						</div>
						
						<div id="remittance_type_div" class="row none">
							<div class="input-field col s3">
						     	<label class="label position-right">Remittance Types<span class="required"> * </span></label>
						    </div>
						    <div class="col s8 p-t-md">
						    	<select id="remittance_type" name="remittance_type" class="selectize" placeholder="Select Remittance Type">
										<option></option> 
										<?php if (!EMPTY($remittance_type)): ?>
											<?php foreach ($remittance_type as $rem): ?>
												<option value="<?php echo $rem['remittance_type_id']?>"><?php echo $rem['remittance_type_name']?></option>
											<?php endforeach;?>
										<?php endif;?>
								</select>
						    </div>
						</div>
						<div id="remittance_type_multiple_div" class="row none">
							<div class="input-field col s3">
						     	<label class="label position-right">Remittance Types<span class="required"> * </span></label>
						    </div>
						    <div class="col s8 p-t-md">
						    	<select id="remittance_type_multiple" name="remittance_type_multiple[]" class="selectize" placeholder="Select Remittance Type" multiple >
										<option></option> 
										<?php if (!EMPTY($remittance_type)): ?>
											<?php foreach ($remittance_type as $rem): ?>
												<option value="<?php echo $rem['remittance_type_id']?>"><?php echo $rem['remittance_type_name']?></option>
											<?php endforeach;?>
										<?php endif;?>
								</select>
						    </div>
						</div>
						<div id="remittance_period_div" class="row none">
						   <div class="input-field col s3">
						    	<label class="label position-right">Remittance Period <span class="required"> * </span></label>
						    </div>
						    <div class="col s8 p-t-md">
								<select id="remittance_period" name="remittance_period" class="selectize" placeholder="Select Remittance Period...">
									<option></option>
									<?php 
										if(!EMPTY($remittance_period)) :
											foreach ($remittance_period as $key => $value) {
												echo '<option value="' . $value['remittance_id'] . '">' . $value['remittance_period'] . '</option>';
											}

										endif;
									?>
								</select>
						    </div>
						    <input type="hidden" name="remittance_period_text" id="remittance_period_text"/>
						</div>
						
						<div id="remittance_period_bir_tax_payment_div" class="row none">
						   <div class="input-field col s3">
						    	<label class="label position-right">Remittance Period Tax Payment<span class="required"> * </span></label>
						    </div>
						    <div class="col s8 p-t-md">
								<select id="remittance_period_bir_tax_payment" name="remittance_period_bir_tax_payment" class="selectize" placeholder="Select Remittance Period...">
									<option></option>
									<?php 
										if(!EMPTY($remittance_period_bir)) :
											foreach ($remittance_period_bir as $key => $value) {
												echo '<option value="' . $value['remittance_id'] . '">' . $value['remittance_period'] . '</option>';
											}

										endif;
									?>
								</select>
						    </div>
						</div>

						<!-- LIST OF DEDUCTION TYPE LIST -->
						<div id="deduction_type_multi_div" class="row none">
						   <div class="input-field col s3">
						     	<label class="label position-right">Deduction Type<span class="required"> * </span></label>
						    </div>
						    <div class="col s7 p-t-md">
								<select id="deduction_type_multi" name="deduction_type_multi[]" class="selectize" placeholder="Select Deduction Type" multiple="multiple">
										<option value="">Select Deduction Type...</option>
								</select>
						    </div>
						    <div class="col s2 p-t-md">
								<input type="checkbox" class="labelauty" id="select_all_deduction" value="Y" data-labelauty ="Filtered|All" checked />
						    </div>
						</div>
						<div id="contrib_deduction_type_div" class="row none">
						   <div class="input-field col s3">
						     	<label class="label position-right">Deduction Type<span class="required"> * </span></label>
						    </div>
						    <div class="col s8 p-t-md">
								<select id="contrib_deduction_type" name="contrib_deduction_type" class="selectize" placeholder="Select Deduction Type">
										<option value="">Select Deduction Type...</option>
								</select>
						    </div>
						</div>
						<!-- MONTHLY SALARY SCHEDULE EFFECTIVE DATE LIST -->
						<div id="salary_schedule_div" class="row none">
						   	<div class="input-field col s3">
						     	<label class="label position-right">Effective list date</label>
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
						     	<label class="label position-right">Effectivity Date</label>
						    </div>
						    <div class="col s8 p-t-md">
								<select id="payout_effective_date" name="payout_effective_date" class="selectize" placeholder="Select Effective Date">
										<option value="">Select Effective Date</option> 
										<?php if (!EMPTY($payout_effective_date)): ?>
											<?php foreach ($payout_effective_date as $peffdate): ?>
												<option value="<?php echo $peffdate['effective_date']?>"><?php echo format_date($peffdate['effective_date']) ?></option>
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
								<input type="text" class="validate datepicker_start center" name="date_range_from" id="date_range_from" placeholder="YYYY/MM/DD"
										onkeypress="format_identifications('<?php echo DATE_FORMAT ?>',this.value,event,'date_range_from')">
						    </div>
						    <div class="col s4 p-t-md">
								<input type="text" class="validate datepicker_end center" name="date_range_to" id="date_range_to" placeholder="YYYY/MM/DD"
										onkeypress="format_identifications('<?php echo DATE_FORMAT ?>',this.value,event,'date_range_to')">
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
						
						<!-- SELECT PAYROLL TYPE FOR REMITTANCES -->
						<div id="payroll_type_rem_div" class="row none">
							<div class="input-field col s3">
						     	<label class="label position-right">Payroll Types<span class="required"> * </span></label>
						    </div>
						    <div class="col s8 p-t-md">

						    	<select id="payroll_type_rem" name="payroll_type_rem[]" class="selectize" placeholder="Select Payroll Types" multiple="multiple">
										<option value="">Select Payroll Types</option> 
										<?php if (!EMPTY($payroll_types)): ?>
											<?php foreach ($payroll_types as $pt): ?>
												<option value="<?php echo $pt['payroll_type_id']?>"><?php echo $pt['payroll_type_name']?></option>
											<?php endforeach;?>
										<?php endif;?>
								</select>
						    </div>
						</div>
						<!-- SELECT PAYROLL TYPE -->
						<div id="payout_type_div" class="row none">
							<div class="input-field col s3">
						     	<label class="label position-right">Payout Types<span class="required"> * </span></label>
						    </div>
						    <div class="col s8 p-t-md">
						    	<select id="payout_type" name="payout_type[]" class="selectize" placeholder="Select Payout Types" multiple="multiple">
										<option value="">Select Payout Types</option> 
										<option value="<?php echo PAYOUT_TYPE_FLAG_REGULAR?>">General Payroll</option>
										<option value="<?php echo PAYOUT_TYPE_FLAG_SPECIAL?>">Special Payroll</option>
										<option value="<?php echo PAYOUT_TYPE_FLAG_VOUCHER?>">Voucher</option>
								</select>
						    </div>
						</div>
						<div id="payroll_period_div" class="row none">
						   <div class="input-field col s3">
						    	<label class="label position-right">Attendance Period <span class="required"> * </span></label>
						    </div>
						    <div class="col s8 p-t-md">
								<select id="payroll_period" name="payroll_period" class="selectize" placeholder="Select Attendance Period">
									<option value="">Select Attendance Period...</option>
								</select>
						    </div>
						    <input type="hidden" name="payroll_period_text" id="payroll_period_text"/>
						</div>
						
						<div id="payroll_period_not_filtered_div" class="row none">
						   <div class="input-field col s3">
						    	<label class="label position-right">Attendance Period <span class="required"> * </span></label>
						    </div>
						    <div class="col s8 p-t-md">
								<select id="payroll_period_not_filtered" name="payroll_period_not_filtered" class="selectize" placeholder="Select Attendance Period">
									<option value="">Select Attendance Period</option> 
										<?php if (!EMPTY($payroll_period)): ?>
											<?php foreach ($payroll_period as $pr): ?>
												<option value="<?php echo $pr['attendance_period_hdr_id']?>"><?php echo $pr['payroll_period']?></option>
											<?php endforeach;?>
										<?php endif;?>
								</select>
						    </div>
						</div>
						<div id="payout_date_div" class="row none">
							<div class="input-field col s3">
						      	<label class="label position-right">Payout Dates <span class="required"> * </span></label>
						    </div>
							<div class="col s8 p-t-md">
								<select id="payout_date" name="payout_date" class="selectize" placeholder="Select Payout Date">
									<option value="">Select Payout Date</option> 
								</select>
							</div>
						</div>
						<div id="alphalist_batch_no_div" class="row none">
							<div class="input-field col s3">
						      	<label class="label position-right">Batch No.</label>
						    </div>
							<div class="col s8 p-t-md">
								<input id="alphalist_batch_no" name="alphalist_batch_no" type="text" value="">
							</div>
						</div>
						<div id="check_hash_div" class="row none">
							<div class="input-field col s3">
						      	<label class="label position-right">Total Check Field Hash </label>
						    </div>
							<div class="col s8 p-t-md">
								<input id="check_hash" name="check_hash" type="text" value="">
							</div>
						</div>
						<div id="voucher_div" class="row none">
						   <div class="input-field col s3">
						    	<label class="label position-right">Voucher <span class="required"> * </span></label>
						    </div>
						    <div class="col s8 p-t-md">
								<select id="voucher" name="voucher" class="selectize" placeholder="Select Voucher">
									<option value="">Select Voucher...</option>
								</select>
						    </div>
						</div>
						<!-- LIST OF YEAR AND MONTH -->
						<div id="month_year_div" class="row none">
							<div class="input-field col s3">
						    	<label class="label position-right">Year<span class="required"> * </span></label>
						    </div>
						    <div class="col s8 p-t-md">
								<?php echo create_years('2000', date('Y'), 'year', date('Y'), FALSE, TRUE)?>
								
						    </div>
						   	<div class="input-field col s3">
						    	<label class="label position-right">Month <span id="month_span" class="required"> * </span></label>
						    </div>
						    <div class="col s8 p-t-md">
								<?php echo create_months('month', null, false)?>
						    </div>
						    
						    <input type="hidden" name="month_text" id="month_text"/>
						</div>
						<!-- LIST OF YEAR -->
						<div id="year_div" class="row none">
							<div class="input-field col s3">
						    	<label class="label position-right">Year<span class="required"> * </span></label>
						    </div>
						    <div class="col s8 p-t-md">
								<?php echo create_years('2000', date('Y'), 'year_only', date('Y'), FALSE, TRUE)?>
						    </div>
						</div>
						<!-- LIST OF BIRTH MONTH -->
						<div id="quarter_div" class="row none">
						   	<div class="input-field col s3">
						    	<label class="label position-right">Quarter<span class="required"> * </span></label>
						    </div>
						    <div class="col s8 p-t-md">
								<select class="selectize" id="quarter" name="quarter" placeholder="Select Quarter">
									<option value="">Select Quarter</option>
									<option value="1">1st Quarter</option>
									<option value="2">2nd Quarter</option>
									<option value="3">3rd Quarter</option>
									<option value="4">4th Quarter</option>
						    	</select>
						    </div>
						</div>
						<!-- FILTERED BY PAYROLL PERIOD -->
						<!-- LIST OF OFFICES FOR GENERAL PAYSLIP-->
						<div id="office_gen_pay_div" class="row none">
						   <div class="input-field col s3">
						     	<label class="label position-right">Office<span class="required"> * </span></label>
						    </div>
						    <div class="col s8 p-t-md">
								<select id="office_gen_pay" name="office_gen_pay" class="selectize" placeholder="Select Office">
										<option value="">Select Offices...</option>
								</select>
						    </div>
						</div>
						<!-- LIST OF OFFICES FOR SPECIAL PAYSLIP-->
						<div id="office_spe_pay_div" class="row none">
						   <div class="input-field col s3">
						     	<label class="label position-right">Office<span class="required"> * </span></label>
						    </div>
						    <div class="col s8 p-t-md">
								<select id="office_spe_pay" name="office_spe_pay" class="selectize" placeholder="Select Office">
										<option value="">Select Offices...</option>
										<?php if (!EMPTY($offices_list)): ?>
											<?php foreach ($offices_list as $o): ?>
												<option value="<?php echo $o['office_id']?>"><?php echo $o['name'] ?></option>
											<?php endforeach;?>
										<?php endif;?>
								</select>
						    </div>
						</div>
						<!-- LIST OF EMPLOYEES FOR GENERAL/SPECIAL PAYSLIP -->
						<div class="row none" id="employee_gen_pay_div">
						    <div class="input-field col s3">
						      	<label class="label position-right">Employee</label>
						    </div>
						    <div class="col s8 p-t-md">
								<select id="employee_gen_pay" name="employee_gen_pay" class="selectize" placeholder="Select Employee">
									<option value="">Select Employee...</option>
								</select>
						    </div>
						</div>

						<!-- CERTIFIED BY -->
						<div class="row none" id="cert_by_div">
						    <div class="input-field col s3">
						      	<label class="label position-right">Certified By <span class="required"> * </span></label>
						    </div>
						    <div class="col s8 p-t-md">
								<select id="cert_by" name="cert_by" class="selectize" placeholder="Select Employee">
								</select>
						    </div>
						</div>

						<!-- PREPARED BY -->
						<div class="row none" id="prep_by_div">
						    <div class="input-field col s3">
						      	<label class="label position-right">Prepared By <span class="required"> * </span></label>
						    </div>
						    <div class="col s8 p-t-md">
								<select id="prep_by" name="prep_by" class="selectize" placeholder="Select Employee">
								</select>
						    </div>
						</div>
						
						<!-- REMITTANCE PREPARED BY -->
						<div class="row none" id="rem_prepared_by_div">
						    <div class="input-field col s3">
						      	<label class="label position-right">Prepared By <span class="required"> * </span></label>
						    </div>
						    <div class="col s8 p-t-md">
								<select id="rem_prepared_by" name="rem_prepared_by" class="selectize" placeholder="Select Employee">
									<option value="">Select Employee...</option>
									<?php if (!EMPTY($rem_prepared_by)): ?>
										<?php foreach ($rem_prepared_by as $emp): ?>
											<option value="<?php echo $emp['report_signatory_id']?>"><?php echo $emp['signatory_name'] ?></option>
										<?php endforeach;?>
									<?php endif;?>
								</select>
						    </div>
						</div>
						
						<!-- REMITTANCE CERTIFIED BY -->
						<div class="row none" id="rem_certified_by_div">
						    <div class="input-field col s3">
						      	<label class="label position-right">Certified By <span class="required"> * </span></label>
						    </div>
						    <div class="col s8 p-t-md">
								<select id="rem_certified_by" name="rem_certified_by" class="selectize" placeholder="Select Employee">
									<option value="">Select Employee...</option>
									<?php if (!EMPTY($rem_certified_by)): ?>
										<?php foreach ($rem_certified_by as $emp): ?>
											<option value="<?php echo $emp['report_signatory_id']?>"><?php echo $emp['signatory_name'] ?></option>
										<?php endforeach;?>
									<?php endif;?>
								</select>
						    </div>
						</div>

						<!-- START: PAYROLL CA SIGNATORIES -->
						<div class="row none" id="signatory_ca1_div">
						    <div class="input-field col s3">
						      	<label class="label position-right">First Signatory</label>
						    </div>
						    <div class="col s8 p-t-md">
								<select id="signatory_ca1" name="signatory_ca1" class="selectize" placeholder="Select Signatory">
									<option value="">Select Signatory...</option>
								</select>
						    </div>
						</div>

						<div class="row none" id="signatory_ca2_div">
						    <div class="input-field col s3">
						      	<label class="label position-right">Second Signatory</label>
						    </div>
						    <div class="col s8 p-t-md">
								<select id="signatory_ca2" name="signatory_ca2" class="selectize" placeholder="Select Signatory">
									<option value="">Select Signatory...</option>
								</select>
						    </div>
						</div>

						<!-- START: PAYROLL COVER SHEET SIGNATORIES -->
						<div class="row none" id="signatory_a_div">
						    <div class="input-field col s3"> 
						    	<!--modesto updated label with signatory's office-->
                      <label class="label position-right">First Signatory (PAD)</label>
						    </div>
						    <div class="col s8 p-t-md">
								<select id="signatory_a" name="signatory_a" class="selectize" placeholder="Select Signatory">
									<option value="">Select Signatory...</option>
									<?php if (!EMPTY($payroll_signatories)): ?>
										<?php foreach ($payroll_signatories as $emp): ?>
											<option value="<?php echo $emp['report_signatory_id']?>"><?php echo $emp['signatory_name'] ?></option>
										<?php endforeach;?>
									<?php endif;?>
								</select>
						    </div>
						</div>
						<div class="row none" id="signatory_b_div">
						    <div class="input-field col s3">
						    	<!--modesto updated label with signatory's office-->
                      <label class="label position-right">Second Signatory (AS-OD)</label>
						    </div>
						    <div class="col s8 p-t-md">
								<select id="signatory_b" name="signatory_b" class="selectize" placeholder="Select Signatory">
									<option value="">Select Signatory...</option>
									<?php if (!EMPTY($payroll_signatories)): ?>
										<?php foreach ($payroll_signatories as $emp): ?>
											<option value="<?php echo $emp['report_signatory_id']?>"><?php echo $emp['signatory_name'] ?></option>
										<?php endforeach;?>
									<?php endif;?>
								</select>
						    </div>
						</div>
						<div class="row none" id="signatory_c_div">
						    <div class="input-field col s3">
						      	<!--modesto updated label with signatory's office-->
                      <label class="label position-right">Third Signatory (CASHIER)</label>
						    </div>
						    <div class="col s8 p-t-md">
								<select id="signatory_c" name="signatory_c" class="selectize" placeholder="Select Signatory">
									<option value="">Select Signatory...</option>
									<?php if (!EMPTY($payroll_signatories)): ?>
										<?php foreach ($payroll_signatories as $emp): ?>
											<option value="<?php echo $emp['report_signatory_id']?>"><?php echo $emp['signatory_name'] ?></option>
										<?php endforeach;?>
									<?php endif;?>
								</select>
						    </div>
						</div>
						<div class="row none" id="signatory_d_div">
						    <div class="input-field col s3">
						      	<!--modesto updated label with signatory's office-->
                      <label class="label position-right">Fourth Signatory (ACCTG.)</label>
						    </div>
						    <div class="col s8 p-t-md">
								<select id="signatory_d" name="signatory_d" class="selectize" placeholder="Select Signatory">
									<option value="">Select Signatory...</option>
									<?php if (!EMPTY($payroll_signatories)): ?>
										<?php foreach ($payroll_signatories as $emp): ?>
											<option value="<?php echo $emp['report_signatory_id']?>"><?php echo $emp['signatory_name'] ?></option>
										<?php endforeach;?>
									<?php endif;?>
								</select>
						    </div>
						</div>
						<!-- END: PAYROLL COVER SHEET SIGNATORIES -->
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
								<div class="m-n">
									<div class="col s6 p-n center file_type">
										<input type="radio" class="labelauty" name="format" id="report_type_pdf" value="pdf" data-labelauty="PDF" checked/>
									</div>
									<div class="col s6 p-n center file_type">
										<input type="radio" class="labelauty" name="format" id="report_type_excel" value="excel" data-labelauty="Excel"/>
									</div>
								</div>	
							</div>
							<div class="dat_file_label none">
								<div class="m-n">
								<i>Generate report via DAT File.</i>
								</div>	
							</div>
							<div class="panel-footer right-align m-t-md teal lighten-5">
								<button id="generate_report" href="#" class="btn btn-success" value="Preparing Report"><i class="flaticon-gear33"></i> Generate</button>
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
	// $('#reports_p').selectize({
	//     sortField: {
	//         field: 'text',
	//         direction: 'asc'
	//     }
	// });
	$("#generate_report").on('click', function (){
		$('#form_reports').parsley('destroy');

		// SET VALUE FOR month_text
		$('#month_text').val($("#month").text()); 

		// SET VALUE FOR remittance_period_text
		$('#remittance_period_text').val($("#remittance_period").text()); 

		// SET VALUE FOR payroll_period_text
		$('#payroll_period_text').val($("#payroll_period").text()); 
		

		if ( $('#form_reports').parsley().isValid() ) {
			$('#form_reports').submit(function(e) {
				e.stopImmediatePropagation();
		    	e.preventDefault();	
				var id    	= '';

					var report  = $("#reports_p").val();

					if(report == "")
					{
						notification_msg("<?php echo ERROR ?>", "<b>Report </b> is required.");
						return false;
					}
					switch(report)
					{
						
						case '<?php echo REPORT_GENERAL_PAYROLL_SUMMARY_GRAND_TOTAL ?>':

							var payroll_type       = $('#payroll_type').val();
							var payroll_period     = $('#payroll_period').val();

							if(payroll_type == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Payroll Type</b> is required.");
								return false;
							}
							if(payroll_period == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Payroll Period</b> is required.");
								return false;
							}

						break;

						

						case '<?php echo REPORT_GENERAL_PAYROLL_PER_OFFICE ?>':

							var office 			   = $('#office_list').val();
							var payroll_type       = $('#payroll_type').val();
							var payroll_period     = $('#payroll_period').val();

							if(office == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Office</b> is required.");
								return false;
							}
							if(payroll_type == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Payroll Type</b> is required.");
								return false;
							}
							if(payroll_period == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Payroll Period</b> is required.");
								return false;
							}


						break;

						case 'monthly_salary_schedule':



						break;

						case '<?php echo REPORT_CONSOLIDATED_REMITTANCE_LIST_PER_OFFICE; ?>':
							var remittance_type = $('#remittance_type').val();
							var payroll_type = $('#payroll_type_rem').val();
							var year = $('#year').val();
							var month = $('#month').val();
							var prepared_by	= $('#rem_prepared_by').val();
							var certified_by = $('#rem_certified_by').val();
							
							if(remittance_type == "")
							{
								notification_msg("<?php echo ERROR ?>", "Please select <b>Remittance Type</b>.");
								return false;
							}
							if(payroll_type == null)
							{
								notification_msg("<?php echo ERROR ?>", "<b>Payroll Type</b> is required.");
								return false;
							}
							if(year == "")
							{
								notification_msg("<?php echo ERROR ?>", "Please select <b>Year</b>.");
								return false;
							}
							if(month == "")
							{
								notification_msg("<?php echo ERROR ?>", "Please select <b>Month</b>.");
								return false;
							}
							if(prepared_by == "")
							{
								notification_msg("<?php echo ERROR ?>", "Please select <b>Prepared By</b>.");
								return false;
							}

							if(certified_by == "")
							{
								notification_msg("<?php echo ERROR ?>", "Please select <b>Certified By</b>.");
								return false;
							}

						break;

						case '<?php echo REPORT_CONSOLIDATED_REMITTANCE_SUMMARY_PER_OFFICE?>':
							var remittance_type = $('#remittance_type').val();
							var deduction_type = $('#deduction_type_multi').val();
							var payroll_type = $('#payroll_type_rem').val();
							var year = $('#year').val();
							var month = $('#month').val();
							var prepared_by	= $('#rem_prepared_by').val();
							var certified_by = $('#rem_certified_by').val();

							if(remittance_type == "")
							{
								notification_msg("<?php echo ERROR ?>", "Please select <b>Remittance Type</b>.");
								return false;
							}
							if(deduction_type == null)
							{
								notification_msg("<?php echo ERROR ?>", "Please select <b>Deduction Type</b>.");
								return false;
							}
							if(payroll_type == null)
							{
								notification_msg("<?php echo ERROR ?>", "<b>Payroll Type</b> is required.");
								return false;
							}
							if(year == "")
							{
								notification_msg("<?php echo ERROR ?>", "Please select <b>Year</b>.");
								return false;
							}
							if(month == "")
							{
								notification_msg("<?php echo ERROR ?>", "Please select <b>Month</b>.");
								return false;
							}
							if(prepared_by == "")
							{
								notification_msg("<?php echo ERROR ?>", "Please select <b>Prepared By</b>.");
								return false;
							}

							if(certified_by == "")
							{
								notification_msg("<?php echo ERROR ?>", "Please select <b>Certified By</b>.");
								return false;
							}

						break;

						case 'special_payroll_summary_grand_total':

						

						break;
						case '<?php echo REPORT_DISBURSEMENT_VOUCHER; ?>':
							var employee	= $('#employee').val();
							var voucher		= $('#voucher').val();

							if(employee == "")
							{
								notification_msg("<?php echo ERROR ?>", "Please select <b>Employee</b>.");
								return false;
							}
							if(voucher == "")
							{
								notification_msg("<?php echo ERROR ?>", "Please select <b>Voucher</b>.");
								return false;
							}

						break;

						case '<?php echo REPORT_GSIS_CONTRIBUTIONS_REMITTANCE_FILE_FOR_UPLOADING; ?>':
							var payroll_type = $('#payroll_type_rem').val();
							var year = $('#year').val();
							var month = $('#month').val();

							if(payroll_type == null)
							{
								notification_msg("<?php echo ERROR ?>", "<b>Payroll Type</b> is required.");
								return false;
							}
							if(year == "")
							{
								notification_msg("<?php echo ERROR ?>", "Please select <b>Year</b>.");
								return false;
							}
							if(month == "")
							{
								notification_msg("<?php echo ERROR ?>", "Please select <b>Month</b>.");
								return false;
							}
						break;

						case '<?php echo REPORT_PAGIBIG_CONTRIBUTIONS_REMITTANCE_FILE_FOR_UPLOADING; ?>':
							var date_range_from     = $('#date_range_from').val();
							var date_range_to       = $('#date_range_to').val();
							var payroll_type        = $('#payroll_type_rem').val();
							var rem_certified_by        = $('#rem_certified_by').val();
							
							if(date_range_from == "")
							{
								notification_msg("<?php echo ERROR ?>", "Please select <b>Date From</b>.");
								return false;
							}
							if(date_range_to == "")
							{
								notification_msg("<?php echo ERROR ?>", "Please select <b>Date To</b>.");
								return false;
							}
							if(payroll_type == null)
							{
								notification_msg("<?php echo ERROR ?>", "<b>Payroll Type</b> is required.");
								return false;
							}
							if(rem_certified_by == '')
							{
								notification_msg("<?php echo ERROR ?>", "<b>Certified By</b> is required.");
								return false;
							}
						break;

						case '<?php echo REPORT_PAGIBIG_DEDUCTIONS_REMITTANCE_FILE_FOR_UPLOADING; ?>':
							var date_range_from	= $('#date_range_from').val();
							var date_range_to	= $('#date_range_to').val();
							var payroll_type	= $('#payroll_type_rem').val();

							if(date_range_from == "")
							{
								notification_msg("<?php echo ERROR ?>", "Please select <b>Date From</b>.");
								return false;
							}
							if(date_range_to == "")
							{
								notification_msg("<?php echo ERROR ?>", "Please select <b>Date To</b>.");
								return false;
							}
							if(payroll_type == null)
							{
								notification_msg("<?php echo ERROR ?>", "<b>Payroll Type</b> is required.");
								return false;
							}
						break;

						case '<?php echo REPORT_DOH_COOP_REMITTANCE_FILE; ?>':
							var date_range_from     = $('#date_range_from').val();
							var date_range_to       = $('#date_range_to').val();
							var payroll_type       = $('#payroll_type_rem').val();
							var prepared_by	= $('#rem_prepared_by').val();
							var certified_by = $('#rem_certified_by').val();
							
							if(date_range_from == "")
							{
								notification_msg("<?php echo ERROR ?>", "Please select <b>Date From</b>.");
								return false;
							}
							if(date_range_to == "")
							{
								notification_msg("<?php echo ERROR ?>", "Please select <b>Date To</b>.");
								return false;
							}
							if(payroll_type == null)
							{
								notification_msg("<?php echo ERROR ?>", "<b>Payroll Type</b> is required.");
								return false;
							}
							if(prepared_by == "")
							{
								notification_msg("<?php echo ERROR ?>", "Please select <b>Prepared By</b>.");
								return false;
							}

							if(certified_by == "")
							{
								notification_msg("<?php echo ERROR ?>", "Please select <b>Certified By</b>.");
								return false;
							}
						break;

						case '<?php echo REPORT_PHILHEALTH_CONTRIBUTIONS_REMITTANCE_FILE_FOR_UPLOADING; ?>':
							var payroll_type = $('#payroll_type_rem').val();
							var year = $('#year').val();
							var month = $('#month').val();
							var prepared_by	= $('#rem_prepared_by').val();
							var payout_type	= $('#payout_type').val();

							if(payroll_type == null)
							{
								notification_msg("<?php echo ERROR ?>", "<b>Payroll Type</b> is required.");
								return false;
							}
							if(year == "")
							{
								notification_msg("<?php echo ERROR ?>", "Please select <b>Year</b>.");
								return false;
							}
							if(month == "")
							{
								notification_msg("<?php echo ERROR ?>", "Please select <b>Month</b>.");
								return false;
							}
							if(payout_type == "")
							{
								notification_msg("<?php echo ERROR ?>", "Please select <b>Payout Type</b>.");
								return false;
							}
							if(prepared_by == "")
							{
								notification_msg("<?php echo ERROR ?>", "Please select <b>Prepared By</b>.");
								return false;
							}
						break;

						case '<?php echo REPORT_GENERAL_PAYSLIP_FOR_REGULARS_AND_NONCAREERS; ?>':
							var payroll_type = $('#payroll_type').val();
							var payroll_period = $('#payroll_period').val();
							var office = $('#office_gen_pay').val();

							if(payroll_type == "")
							{
								notification_msg("<?php echo ERROR ?>", "Please select <b>Payroll Types</b>.");
								return false;
							}
							if(payroll_period == "")
							{
								notification_msg("<?php echo ERROR ?>", "Please select <b>Payroll Period</b>.");
								return false;
							}
							if(office == "")
							{
								notification_msg("<?php echo ERROR ?>", "Please select <b>Office</b>.");
								return false;
							}
						break;

						case '<?php echo REPORT_SPECIAL_PAYSLIP_FOR_REGULARS_AND_NONCAREERS; ?>':
							var compensation_type = $('#compensation_special_type').val();
							var year = $('#year').val();
							var month = $('#month').val();
							var office = $('#office_spe_pay').val();

							if(compensation_type == "")
							{
								notification_msg("<?php echo ERROR ?>", "Please select <b>Compensation Type</b>.");
								return false;
							}
							if(year == "")
							{
								notification_msg("<?php echo ERROR ?>", "Please select <b>Year</b>.");
								return false;
							}
							if(month == "")
							{
								notification_msg("<?php echo ERROR ?>", "Please select <b>Month</b>.");
								return false;
							}
							if(office == "")
							{
								notification_msg("<?php echo ERROR ?>", "Please select <b>Office</b>.");
								return false;
							}
						break;

						case '<?php echo REPORT_REPORT_BIR_2316_CERTIFICATE_OF_COMPENSATION_PAYMENT; ?>':
							var office = $('#office_list').val();
							var employee = $('#employee_filtered').val();
							var year = $('#year').val();
							var month = $('#month').val();
							var signatory_ca1 = $('#signatory_ca1').val();
							var signatory_ca2 = $('#signatory_ca2').val();
							

							if(office == "")
							{
								notification_msg("<?php echo ERROR ?>", "Please select <b>Office</b>.");
								return false;
							}
							if(employee == "")
							{
								notification_msg("<?php echo ERROR ?>", "Please select <b>Employee</b>.");
								return false;
							}
							if(year == "")
							{
								notification_msg("<?php echo ERROR ?>", "Please select <b>Year</b>.");
								return false;
							}
							if(signatory_ca1 == "")
							{
								notification_msg("<?php echo ERROR ?>", "Please select <b>First Signatory</b>.");
								return false;
							}
							if(signatory_ca2 == "")
							{
								notification_msg("<?php echo ERROR ?>", "Please select <b>Second Signatory</b>.");
								return false;
							}
							
						break;

						case '<?php echo REPORT_SPECIAL_PAYROLL_ALPHA_LIST_PER_OFFICE; ?>':
							var office = $('#office_list').val();
							var compensation_type = $('#compensation_special_type').val();
							var year = $('#year').val();
							var month = $('#month').val();

							if(office == "")
							{
								notification_msg("<?php echo ERROR ?>", "Please select <b>Office</b>.");
								return false;
							}
							if(compensation_type == "")
							{
								notification_msg("<?php echo ERROR ?>", "Please select <b>Compensation Type</b>.");
								return false;
							}
							if(year == "")
							{
								notification_msg("<?php echo ERROR ?>", "Please select <b>Year</b>.");
								return false;
							}
							if(month == "")
							{
								notification_msg("<?php echo ERROR ?>", "Please select <b>Month</b>.");
								return false;
							}
						break;
						
						case '<?php echo REPORT_REMITTANCE_SUMMARY_PER_OFFICE; ?>':
							var office = $('#office_list').val();
							var remittance_period = $('#remittance_period').val();
							var payroll_type = $('#payroll_type_rem').val();
							var prepared_by	= $('#rem_prepared_by').val();
							var certified_by = $('#rem_certified_by').val();
							var remittance_type_multiple = $('#remittance_type_multiple').val();

							if(remittance_type_multiple == "")
							{
								notification_msg("<?php echo ERROR ?>", "Please select <b>Remittance Type</b>.");
								return false;
							}
							// if(office == "")
							// {
							// 	notification_msg("<?php echo ERROR ?>", "Please select <b>Office</b>.");
							// 	return false;
							// }
							if(remittance_period == "")
							{
								notification_msg("<?php echo ERROR ?>", "Please select <b>Remittance Period</b>.");
								return false;
							}
							if(payroll_type == null)
							{
								notification_msg("<?php echo ERROR ?>", "<b>Payroll Type</b> is required.");
								return false;
							}
							if(prepared_by == "")
							{
								notification_msg("<?php echo ERROR ?>", "Please select <b>Prepared By</b>.");
								return false;
							}

							if(certified_by == "")
							{
								notification_msg("<?php echo ERROR ?>", "Please select <b>Certified By</b>.");
								return false;
							}
						break;

						case '<?php echo REPORT_SPECIAL_PAYROLL_SUMMARY_PER_OFFICE; ?>':
							var compensation_type = $('#compensation_special_type').val();
							var year = $('#year').val();
							var month = $('#month').val();
							var prep_by = $('#prep_by').val();

							if(compensation_type == "")
							{
								notification_msg("<?php echo ERROR ?>", "Please select <b>Compensation Type</b>.");
								return false;
							}
							if(prep_by == "")
							{
								notification_msg("<?php echo ERROR ?>", "Please select <b>Prepared By</b>.");
								return false;
							}
							if(year == "")
							{
								notification_msg("<?php echo ERROR ?>", "Please select <b>Year</b>.");
								return false;
							}
							if(month == "")
							{
								notification_msg("<?php echo ERROR ?>", "Please select <b>Month</b>.");
								return false;
							}
						break;

						case '<?php echo REPORT_REMITTANCE_SUMMARY_GRAND_TOTAL; ?>':
							var remittance_type = $('#remittance_type').val();
							var deduction_type = $('#deduction_type_multi').val();
							var payroll_type = $('#payroll_type_rem').val();
							var year = $('#year').val();
							var month = $('#month').val();
							var prepared_by	= $('#rem_prepared_by').val();
							var certified_by = $('#rem_certified_by').val();

							if(remittance_type == "")
							{
								notification_msg("<?php echo ERROR ?>", "Please select <b>Remittance Type</b>.");
								return false;
							}
							if(deduction_type == null)
							{
								notification_msg("<?php echo ERROR ?>", "Please select <b>Deduction Type</b>.");
								return false;
							}
							if(payroll_type == null)
							{
								notification_msg("<?php echo ERROR ?>", "<b>Payroll Type</b> is required.");
								return false;
							}
							if(year == "")
							{
								notification_msg("<?php echo ERROR ?>", "Please select <b>Year</b>.");
								return false;
							}
							if(month == "")
							{
								notification_msg("<?php echo ERROR ?>", "Please select <b>Month</b>.");
								return false;
							}
							if(prepared_by == "")
							{
								notification_msg("<?php echo ERROR ?>", "Please select <b>Prepared By</b>.");
								return false;
							}

							if(certified_by == "")
							{
								notification_msg("<?php echo ERROR ?>", "Please select <b>Certified By</b>.");
								return false;
							}
						break;

						case '<?php echo REPORT_BIR_1601C_MONTHLY_REPORT_OF_TAX_WITHHELD; ?>':
							var year = $('#year').val();
							var month = $('#month').val();

							if(year == "")
							{
								notification_msg("<?php echo ERROR ?>", "Please select <b>Year</b>.");
								return false;
							}
							if(month == "")
							{
								notification_msg("<?php echo ERROR ?>", "Please select <b>Month</b>.");
								return false;
							}
						break;
						
						case '<?php echo REPORT_YEAR_END_ADJUSTMENT_REPORT_PER_OFFFICE; ?>':
							var office = $('#office_list').val();
							var year = $('#year_only').val();
							var prepared_by	= $('#rem_prepared_by').val();
							var certified_by = $('#rem_certified_by').val();
							
							if(prepared_by == "")
							{
								notification_msg("<?php echo ERROR ?>", "Please select <b>Prepared By</b>.");
								return false;
							}
							if(certified_by == "")
							{
								notification_msg("<?php echo ERROR ?>", "Please select <b>Certified By</b>.");
								return false;
							}
							if(office == "")
							{
								notification_msg("<?php echo ERROR ?>", "Please select <b>Office</b>.");
								return false;
							}
							if(year == "")
							{
								notification_msg("<?php echo ERROR ?>", "Please select <b>Year</b>.");
								return false;
							}
						break;

						case '<?php echo REPORT_ATM_ALPHA_LIST; ?>':
							var bank               = $('#bank').val();							
							var payroll_type       = $('#payroll_type').val();
							var payroll_period     = $('#payroll_period').val();
							var payout_date        = $('#payout_date').val();

							if(bank == "")
							{
								notification_msg("<?php echo ERROR ?>", "Please select <b>Bank</b>.");
								return false;
							}
							if(payroll_type == "")
							{
								notification_msg("<?php echo ERROR ?>", "Please select <b>Payroll Types</b>.");
								return false;
							}
							if(payroll_period == "")
							{
								notification_msg("<?php echo ERROR ?>", "Please select <b>Payroll Period</b>.");
								return false;
							}
							if(payout_date == "")
							{
								notification_msg("<?php echo ERROR ?>", "Please select <b>Payout Date</b>.");
								return false;
							}
						break;
						
						case '<?php echo REPORT_ATM_ALPHA_LIST2; ?>':
							var bank               = $('#bank').val();							
							var payroll_type       = $('#payroll_type').val();
							var payroll_period     = $('#payroll_period').val();

							if(bank == "")
							{
								notification_msg("<?php echo ERROR ?>", "Please select <b>Bank</b>.");
								return false;
							}
							if(payroll_type == "")
							{
								notification_msg("<?php echo ERROR ?>", "Please select <b>Payroll Types</b>.");
								return false;
							}
							if(payroll_period == "")
							{
								notification_msg("<?php echo ERROR ?>", "Please select <b>Payroll Period</b>.");
								return false;
							}
						break;

						case '<?php echo REPORT_BANK_PAYROLL_REGISTER; ?>':
							var bank	= $('#bank').val();
							var payroll_type = $('#payroll_type').val();
							var payroll_period = $('#payroll_period').val();
							var payout_date        = $('#payout_date').val();

							if(bank == "")
							{
								notification_msg("<?php echo ERROR ?>", "Please select <b>Bank</b>.");
								return false;
							}
							if(payroll_type == "")
							{
								notification_msg("<?php echo ERROR ?>", "Please select <b>Payroll Types</b>.");
								return false;
							}
							if(payroll_period == "")
							{
								notification_msg("<?php echo ERROR ?>", "Please select <b>Payroll Period</b>.");
								return false;
							}
							if(payout_date == "")
							{
								notification_msg("<?php echo ERROR ?>", "Please select <b>Payout Date</b>.");
								return false;
							}
						break;

						case '<?php echo REPORT_GENERAL_FOR_CONSTRACTS_OF_SERVICE; ?>':
							var office = $('#office_list').val();
							var year = $('#year').val();
							var month = $('#month').val();
							
							if(office == "")
							{
								notification_msg("<?php echo ERROR ?>", "Please select <b>Office</b>.");
								return false;
							}
							if(year == "")
							{
								notification_msg("<?php echo ERROR ?>", "Please select <b>Year</b>.");
								return false;
							}
							if(month == "")
							{
								notification_msg("<?php echo ERROR ?>", "Please select <b>Month</b>.");
								return false;
							}
						break;
						case '<?php echo REPORT_GSIS_CERTIFICATE_CONTRIBUTION ?>':
							var employee     	= $('#employee').val();
							var date_range_from = $('#date_range_from').val();
							var date_range_to   = $('#date_range_to').val();
							var prep_by 		= $('#prep_by').val();
							var cert_by   		= $('#cert_by').val();
							if(employee == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Employee </b> is required.");
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
							if(cert_by == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Certified By </b> is required.");
								return false;
							}		
							if(prep_by == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Prepared By </b> is required.");
								return false;
							}	
						break;
						case '<?php echo REPORT_PHILHEALTH_CERTIFICATE_CONTRIBUTION ?>':
							var employee 	    = $('#employee').val();
							var date_range_from = $('#date_range_from').val();
							var date_range_to   = $('#date_range_to').val();
							var prep_by 		= $('#prep_by').val();
							var cert_by   		= $('#cert_by').val();
							if(employee == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Employee </b> is required.");
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
							if(cert_by == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Certified By </b> is required.");
								return false;
							}		
							if(prep_by == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Prepared By </b> is required.");
								return false;
							}	
						break;
						case '<?php echo REPORT_PAGIBIG_CERTIFICATE_CONTRIBUTION ?>':
							var employee        = $('#employee').val();
							var date_range_from = $('#date_range_from').val();
							var date_range_to   = $('#date_range_to').val();
							var prep_by 		= $('#prep_by').val();
							var cert_by   		= $('#cert_by').val();
							if(employee == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Employee </b> is required.");
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
							if(cert_by == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Certified By </b> is required.");
								return false;
							}		
							if(prep_by == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Prepared By </b> is required.");
								return false;
							}	
						break;
						case '<?php echo REPORT_GSIS_MEMBERSHIP_FORM ?>':
							var employee          = $('#employee').val();
							if(employee == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Employee </b> is required.");
								return false;
							}
							path                  = employee + '/' + null + '/' + null;
						break;
						case '<?php echo REPORT_PHILHEALTH_MEMBERSHIP_FORM ?>':
							var employee          = $('#employee').val();
							if(employee == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Employee </b> is required.");
								return false;
							}
							path                  = employee + '/' + null + '/' + null;
						break;
						case '<?php echo REPORT_PAGIBIG_MEMBERSHIP_FORM ?>':
							var employee          = $('#employee').val();
							if(employee == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Employee </b> is required.");
								return false;
							}
							path                  = employee + '/' + null + '/' + null;
						break;
						case '<?php echo REPORT_BIR_2307_CERTIFICATE_OF_CREDITABLE_TAX_WITHHELD_AT_SOURCE; ?>':
							var office = $('#office_list').val();
							var employee = $('#employee_filtered').val();
							var year = $('#year_only').val();
							var quarter = $('#quarter').val();
							

							if(office == "")
							{
								notification_msg("<?php echo ERROR ?>", "Please select <b>Office</b>.");
								return false;
							}
							if(employee == "")
							{
								notification_msg("<?php echo ERROR ?>", "Please select <b>Employee</b>.");
								return false;
							}
							if(year == "")
							{
								notification_msg("<?php echo ERROR ?>", "Please select <b>Year</b>.");
								return false;
							}

							if(quarter == "")
							{
								notification_msg("<?php echo ERROR ?>", "Please select <b>Quarter</b>.");
								return false;
							}
							
						break;

						case '<?php echo REPORT_EMPLOYEES_PAID_BY_VOUCHER; ?>':
							var year 			= $('#year').val();
							var month 			= $('#month').val();
							var prep_by 		= $('#prep_by').val();
							var cert_by   		= $('#cert_by').val();

							if(year == "")
							{
								notification_msg("<?php echo ERROR ?>", "Please select <b>Year</b>.");
								return false;
							}
							if(month == "")
							{
								notification_msg("<?php echo ERROR ?>", "Please select <b>Month</b>.");
								return false;
							}
							if(cert_by == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Certified By </b> is required.");
								return false;
							}		
							if(prep_by == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Prepared By </b> is required.");
								return false;
							}

						break;

						case '<?php echo REPORT_BIR_2306_CERTIFICATE_OF_FINAL_TAX_WITHHELD_AT_SOURCE; ?>':
							var office = $('#office_list').val();
							var employee = $('#employee_filtered').val();
							var date_range_from = $('#date_range_from').val();
							var date_range_to = $('#date_range_to').val();


							if(office == "")
							{
								notification_msg("<?php echo ERROR ?>", "Please select <b>Office</b>.");
								return false;
							}
							if(employee == "")
							{
								notification_msg("<?php echo ERROR ?>", "Please select <b>Employee</b>.");
								return false;
							}
							if(date_range_from == "")
							{
								notification_msg("<?php echo ERROR ?>", "Please select <b>Date From</b>.");
								return false;
							}
							if(date_range_to == "")
							{
								notification_msg("<?php echo ERROR ?>", "Please select <b>Date To</b>.");
								return false;
							}
							
						break;

						case '<?php echo REPORT_GENERAL_PAYROLL_SUMMARY; ?>':
							var payroll_period  = $('#payroll_period').val();

							if(payroll_period == "")
							{
								notification_msg("<?php echo ERROR ?>", "Please select <b>Payroll Period</b>.");
								return false;
							}
						break;

						case '<?php echo REPORT_GENERAL_PAYROLL_ALPHALIST_PER_OFFICE; ?>':
							//var office = $('#office_list').val();
							var payroll_type = $('#payroll_type').val();
							var payroll_period = $('#payroll_period').val();

// 							if(office == "")
// 							{
								//notification_msg("<?php //echo ERROR ?>", "Please select <b>Office</b>.");
// 								return false;
// 							}
							if(payroll_type == "")
							{
								notification_msg("<?php echo ERROR ?>", "Please select <b>Payroll Type</b>.");
								return false;
							}
							if(payroll_period == "")
							{
								notification_msg("<?php echo ERROR ?>", "Please select <b>Payroll Period</b>.");
								return false;
							}
						break;

						case '<?php echo REPORT_SPECIAL_PAYROLL_COVER_SHEET; ?>':
							var compensation_type = $('#compensation_special_type').val();
							var year = $('#year').val();
							var month = $('#month').val();

							if(compensation_type == "")
							{
								notification_msg("<?php echo ERROR ?>", "Please select <b>Compensation Type</b>.");
								return false;
							}
							if(year == "")
							{
								notification_msg("<?php echo ERROR ?>", "Please select <b>Year</b>.");
								return false;
							}
							if(month == "")
							{
								notification_msg("<?php echo ERROR ?>", "Please select <b>Month</b>.");
								return false;
							}
						break;

						case '<?php echo REPORT_REMITTANCE_LIST_PER_OFFICE; ?>':
							//var office = $('#office_list').val();

							var deduction_type = $('#deduction_type_multi').val();
							var remittance_type = $('#remittance_type').val();
							var payroll_type  = $('#payroll_type_rem').val();
							var year = $('#year').val();
							var month = $('#month').val();
							var prepared_by	= $('#rem_prepared_by').val();
							var certified_by = $('#rem_certified_by').val();

							/*if(office == "")
							{
								notification_msg("<?php echo ERROR ?>", "Please select <b>Office</b>.");
								return false;
							}
							*/
							if(deduction_type == null)
							{
								notification_msg("<?php echo ERROR ?>", "Please select <b>Deduction Type</b>.");
								return false;
							}
							if(remittance_type == "")
							{
								notification_msg("<?php echo ERROR ?>", "Please select <b>Remittance Type</b>.");
								return false;
							}
							
							if(payroll_type == null)
							{
								notification_msg("<?php echo ERROR ?>", "<b>Payroll Type</b> is required.");
								return false;
							}
							
							if(year == "")
							{
								notification_msg("<?php echo ERROR ?>", "Please select <b>Year</b>.");
								return false;
							}
							
							if(month == "")
							{
								notification_msg("<?php echo ERROR ?>", "Please select <b>Month</b>.");
								return false;
							}

							if(prepared_by == "")
							{
								notification_msg("<?php echo ERROR ?>", "Please select <b>Prepared By</b>.");
								return false;
							}

							if(certified_by == "")
							{
								notification_msg("<?php echo ERROR ?>", "Please select <b>Certified By</b>.");
								return false;
							}
						break;

						case '<?php echo REPORT_EMPLOYEES_NOT_INCLUDED_IN_PAYROLL; ?>':
							var payroll_type = $('#payroll_type').val();
							var payroll_period = $('#payroll_period').val();
							var office = $('#office_list').val();

							if(payroll_type == "")
							{
								notification_msg("<?php echo ERROR ?>", "Please select <b>Payroll Types</b>.");
								return false;
							}
							if(payroll_period == "")
							{
								notification_msg("<?php echo ERROR ?>", "Please select <b>Payroll Period</b>.");
								return false;
							}
							
						break;

						case '<?php echo REPORT_COOP_REMITTANCE; ?>':
							var office = $('#office_list').val();
							var deduction_type = $('#deduction_type').val();
							var remittance_type = $('#remittance_type').val();
							var payroll_type = $('#payroll_type_rem').val();
							var year = $('#year').val();
							var month = $('#month').val();
							var prepared_by	= $('#rem_prepared_by').val();
							var certified_by = $('#rem_certified_by').val();
							
							if(office == "")
							{
								notification_msg("<?php echo ERROR ?>", "Please select <b>Office</b>.");
								return false;
							}

							if(deduction_type == "")
							{
								notification_msg("<?php echo ERROR ?>", "Please select <b>Deduction Type</b>.");
								return false;
							}
							
							if(remittance_type == "")
							{
								notification_msg("<?php echo ERROR ?>", "Please select <b>Remittance Type</b>.");
								return false;
							}

							if(payroll_type == null)
							{
								notification_msg("<?php echo ERROR ?>", "<b>Payroll Type</b> is required.");
								return false;
							}
							
							if(year == "")
							{
								notification_msg("<?php echo ERROR ?>", "Please select <b>Year</b>.");
								return false;
							}
							if(month == "")
							{
								notification_msg("<?php echo ERROR ?>", "Please select <b>Month</b>.");
								return false;
							}

							if(prepared_by == "")
							{
								notification_msg("<?php echo ERROR ?>", "Please select <b>Prepared By</b>.");
								return false;
							}

							if(certified_by == "")
							{
								notification_msg("<?php echo ERROR ?>", "Please select <b>Certified By</b>.");
								return false;
							}
						break;
						
						case '<?php echo REPORT_BIR_2305_CERTIFICATE_OF_UPDATE; ?>':
							var office = $('#office_list').val();
							var employee = $('#employee_filtered').val();
							var year = $('#year_only').val();
							

							if(office == "")
							{
								notification_msg("<?php echo ERROR ?>", "Please select <b>Office</b>.");
								return false;
							}
							if(employee == "")
							{
								notification_msg("<?php echo ERROR ?>", "Please select <b>Employee</b>.");
								return false;
							}
							if(year == "")
							{
								notification_msg("<?php echo ERROR ?>", "Please select <b>Year</b>.");
								return false;
							}

						break;

						case '<?php echo REPORT_RESPONSIBILITY_CODE_PER_OFFICE; ?>':
							var office = $('#office_list').val();
							var payroll_type = $('#payroll_type').val();
							var payroll_period = $('#payroll_period').val();

							if(office == "")
							{
								notification_msg("<?php echo ERROR ?>", "Please select <b>Office</b>.");
								return false;
							}
							if(payroll_type == "")
							{
								notification_msg("<?php echo ERROR ?>", "Please select <b>Payroll Types</b>.");
								return false;
							}
							if(payroll_period == "")
							{
								notification_msg("<?php echo ERROR ?>", "Please select <b>Payroll Period</b>.");
								return false;
							}
							
						break;

						case '<?php echo REPORT_BIR_TAX_PAYMENT; ?>':
							var office     			= $('#office_list').val();
							var remittance_period	= $('#remittance_period_bir_tax_payment').val();
							var prepared_by	= $('#rem_prepared_by').val();
							var certified_by = $('#rem_certified_by').val();
							
							if(prepared_by == "")
							{
								notification_msg("<?php echo ERROR ?>", "Please select <b>Prepared By</b>.");
								return false;
							}
							if(certified_by == "")
							{
								notification_msg("<?php echo ERROR ?>", "Please select <b>Certified By</b>.");
								return false;
							}
							if(office == "")
							{
								notification_msg("<?php echo ERROR ?>", "Please select <b>Office</b>.");
								return false;
							}
							
							if(remittance_period == "")
							{
								notification_msg("<?php echo ERROR ?>", "Please select <b>Remittance Period</b>.");
								return false;
							}
						break;


						case '<?php echo REPORT_GENERAL_PAYROLL_ALPHALIST_FOR_JO; ?>':
							//var office = $('#office_list').val();
							var payroll_period = $('#payroll_period').val();

							// if(office == "")
							// {
							// 	notification_msg("<?php echo ERROR ?>", "Please select <b>Office</b>.");
							// 	return false;
							// }
							if(payroll_period == "")
							{
								notification_msg("<?php echo ERROR ?>", "Please select <b>Payroll Period</b>.");
								return false;
							}
							
						break;
						case '<?php echo REPORT_EXPANDED_WITHHOLDING_TAX_AND_GOVERNMENT_MONEY_PAYMENT; ?>':
							//var office = $('#office_list').val();
							var payroll_period = $('#payroll_period').val();

							// if(office == "")
							// {
							// 	notification_msg("<?php echo ERROR ?>", "Please select <b>Office</b>.");
							// 	return false;
							// }
							if(payroll_period == "")
							{
								notification_msg("<?php echo ERROR ?>", "Please select <b>Payroll Period</b>.");
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

			 	if(report == '<?php echo REPORT_ATM_ALPHA_LIST; ?>' && format =="excel")
			 	{
			 		/*
			 		 *THIS IS FOR THE BANK DBF FILE
			 		 */
			 		 button_loader('generate_report',1);
			 		var data = $('#form_reports').serialize();
			 		$.post($base_url + 'main/reports_payroll/atm_alpha_list/create_report_dbf', data, function(result) {
					  	if(result.status) {
							window.open($base_url +'<?php echo PATH_DBF_REPORT ?>'+result.filename,'_blank');
					 	}	
					 	else{
					 		notification_msg("<?php echo ERROR ?>", result.msg);
					 	}	
					 	button_loader('generate_report',0);		  
					}, 'json');
			 	}
			 	else if(report == '<?php echo REPORT_ATM_ALPHA_LIST2; ?>')
			 	{
			 		/*
			 		 *THIS IS FOR THE BANK DBF FILE
			 		 */
			 		 button_loader('generate_report',1);
			 		var data = $('#form_reports').serialize();
			 		$.post($base_url + 'main/reports_payroll/atm_alpha_list2/create_report_dbf', data, function(result) {
					  	if(result.status) {
							window.open($base_url +'<?php echo PATH_DBF_REPORT ?>'+result.filename,'_blank');
					 	}	
					 	else{
					 		notification_msg("<?php echo ERROR ?>", result.msg);
					 	}	
					 	button_loader('generate_report',0);		  
					}, 'json');
			 	}
			 	else
			 	{
			 		window.open($base_url + 'main/reports_payroll/reports_payroll/generate_reports/' + format + '/' + report + '/?' + $('#form_reports').serialize());
			 	}
			 	

  			});
	    }

	});

	$('#reports_p').change(function() {

		$('.collapsible-body .row').not('#report_div').addClass('none');
		$('.file_type').removeClass('none');
		$('.dat_file_label').addClass('none');
		$('#employee_span').show();
		$('#office_list_span').show();
		$('#tracking_code_div').removeClass('none');
		

		//get selected value`
		var selected = $(this).val();
		switch(selected)
		{
			case '<?php echo REPORT_GENERAL_PAYROLL_SUMMARY; ?>':
				$('#payroll_type_div').removeClass('none');
				$('#payroll_period_div').removeClass('none');
				$('#signatory_a_div').removeClass('none');
				$('#signatory_b_div').removeClass('none');
				$('#signatory_c_div').removeClass('none');
				$('#signatory_d_div').removeClass('none');
				//$('#payroll_period_not_filtered_div').removeClass('none');
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
				$('#compensation_type_special_div').removeClass('none');
				$('#month_year_div').removeClass('none');
				
				$('#signatory_a_div').removeClass('none');
				$('#signatory_b_div').removeClass('none');
				$('#signatory_c_div').removeClass('none');
				$('#signatory_d_div').removeClass('none');
			break;

			case '<?php echo REPORT_SPECIAL_PAYROLL_SUMMARY_GRAND_TOTAL; ?>':
				$('#compensation_type_div').removeClass('none');
			break;

			case '<?php echo REPORT_SPECIAL_PAYROLL_ALPHA_LIST_PER_OFFICE; ?>':
				$('#month_year_div').removeClass('none');
				$('#compensation_type_special_div').removeClass('none');
				$('#office_list_div').removeClass('none');
			break;

			case '<?php echo REPORT_GENERAL_PAYSLIP_FOR_REGULARS_AND_NONCAREERS; ?>':
				$('#payroll_type_div').removeClass('none');
				$('#payroll_period_div').removeClass('none');
				$('#office_gen_pay_div').removeClass('none');
				$('#employee_gen_pay_div').removeClass('none');
			break;

			case '<?php echo REPORT_GENERAL_FOR_CONSTRACTS_OF_SERVICE; ?>':
				$('#month_year_div').removeClass('none');
				$('#office_list_div').removeClass('none');
				$('#employee_filtered_div').removeClass('none');
				$('#employee_span').hide();
			break;

			case '<?php echo REPORT_SPECIAL_PAYSLIP_FOR_REGULARS_AND_NONCAREERS; ?>':
				$('#compensation_type_special_div').removeClass('none');
				$('#month_year_div').removeClass('none');
				$('#office_spe_pay_div').removeClass('none');
				$('#employee_gen_pay_div').removeClass('none');
			break;

			case '<?php echo REPORT_BANK_PAYROLL_REGISTER; ?>':
				$('#bank_div').removeClass('none');
				$('#payroll_type_div').removeClass('none');	
				$('#payroll_period_div').removeClass('none');
				$('#payout_date_div').removeClass('none');
				$('#signatory_a_div').removeClass('none');
				$('#signatory_b_div').removeClass('none');
				$('#signatory_c_div').removeClass('none');
				$('#check_hash_div').removeClass('none');
				$('#alphalist_batch_no_div').removeClass('none');
			break;

			case '<?php echo REPORT_ATM_ALPHA_LIST; ?>':
				$('#bank_div').removeClass('none');
				$('#payroll_type_div').removeClass('none');
				$('#payroll_period_div').removeClass('none');
				$('#alphalist_batch_no_div').removeClass('none');
				$('#payout_date_div').removeClass('none');
				$('#signatory_a_div').removeClass('none');
				$('#signatory_b_div').removeClass('none');
				$('#signatory_c_div').removeClass('none');

				$('#tracking_code_div').addClass('none');

			break;
			
			case '<?php echo REPORT_ATM_ALPHA_LIST2; ?>':
				$('#bank_div').removeClass('none');
				$('#payroll_type_div').removeClass('none');
				$('#payroll_period_div').removeClass('none');

				$('#tracking_code_div').addClass('none');

			break;

			case '<?php echo REPORT_REMITTANCE_SUMMARY_GRAND_TOTAL; ?>':
				$('#remittance_type_div').removeClass('none');
				$('#deduction_type_multi_div').removeClass('none');
				$('#payroll_type_rem_div').removeClass('none');
				$('#month_year_div').removeClass('none');
				$('#rem_prepared_by_div').removeClass('none');
				$('#rem_certified_by_div').removeClass('none');
			break;

			case '<?php echo REPORT_REMITTANCE_SUMMARY_PER_OFFICE; ?>':
				var remittance_period = $("#remittance_period")[0].selectize;
				var data       = 'remittance_type_id=';
				remittance_period.clear();
				remittance_period.clearOptions();

				if(remittance_type != '')
				{
					$.post($base_url + 'main/reports_payroll/reports_payroll/get_remittance_period', data, function(result) {
					  	if(result.flag == 1) {
							remittance_period.load(function(callback) {
								callback(result.list);
							});
					 	}				  
					}, 'json');
				}
				$('#remittance_type_multiple_div').removeClass('none');
				$('#remittance_period_div').removeClass('none');
				$('#payroll_type_rem_div').removeClass('none');
				$('#office_list_div').removeClass('none');
				$('#rem_prepared_by_div').removeClass('none');
				$('#rem_certified_by_div').removeClass('none');
			break;

			case '<?php echo REPORT_REMITTANCE_LIST_PER_OFFICE; ?>':
				$('#remittance_type_div').removeClass('none');
				$('#deduction_type_multi_div').removeClass('none');
				$('#payroll_type_rem_div').removeClass('none');
				$('#month_year_div').removeClass('none');
				$('#office_list_div').removeClass('none');
				$('#rem_prepared_by_div').removeClass('none');
				$('#rem_certified_by_div').removeClass('none');
			break;
			
			case '<?php echo REPORT_CONSOLIDATED_REMITTANCE_SUMMARY_PER_OFFICE; ?>':
				$('#office_list_div').removeClass('none');
				$('#remittance_type_div').removeClass('none');
				$('#deduction_type_multi_div').removeClass('none');
				$('#payroll_type_rem_div').removeClass('none');
				$('#month_year_div').removeClass('none');
				$('#rem_prepared_by_div').removeClass('none');
				$('#rem_certified_by_div').removeClass('none');
			break;

			case '<?php echo REPORT_CONSOLIDATED_REMITTANCE_LIST_PER_OFFICE; ?>':
				$('#office_list_div').removeClass('none');
				$('#remittance_type_div').removeClass('none');
				$('#payroll_type_rem_div').removeClass('none');
				$('#month_year_div').removeClass('none');
				$('#rem_prepared_by_div').removeClass('none');
				$('#rem_certified_by_div').removeClass('none');
			break;

			case '<?php echo REPORT_GSIS_CONTRIBUTIONS_REMITTANCE_FILE_FOR_UPLOADING; ?>':
				$('#payroll_type_rem_div').removeClass('none');
				$('#month_year_div').removeClass('none');
			break;

			case '<?php echo REPORT_PHILHEALTH_CONTRIBUTIONS_REMITTANCE_FILE_FOR_UPLOADING; ?>':
				$('#payroll_type_rem_div').removeClass('none');
				$('#month_year_div').removeClass('none');
				$('#rem_prepared_by_div').removeClass('none');
				$('#payout_type_div').removeClass('none');
			break;

			case '<?php echo REPORT_PAGIBIG_CONTRIBUTIONS_REMITTANCE_FILE_FOR_UPLOADING; ?>':
				$('#payroll_type_rem_div').removeClass('none');
				$('#date_range_div').removeClass('none');
				$('#rem_certified_by_div').removeClass('none');
				$('#office_list_div').removeClass('none');
			break;
			
			case '<?php echo REPORT_PAGIBIG_DEDUCTIONS_REMITTANCE_FILE_FOR_UPLOADING; ?>':
				$('#payroll_type_rem_div').removeClass('none');
				$('#date_range_div').removeClass('none');
			break;

			case '<?php echo REPORT_BIR_TAX_PAYMENTS; ?>':
				$('#remittance_period_bir_tax_payment_div').removeClass('none');
				$('#office_list_div').removeClass('none');
				$('#rem_prepared_by_div').removeClass('none');
				$('#rem_certified_by_div').removeClass('none');
			break;

			case '<?php echo REPORT_DOH_COOP_REMITTANCE_FILE; ?>':
				$('#date_range_div').removeClass('none');
				$('#payroll_type_rem_div').removeClass('none');
				$('#rem_prepared_by_div').removeClass('none');
				$('#rem_certified_by_div').removeClass('none');
			break;

			case '<?php echo REPORT_BIR_1601C_MONTHLY_REPORT_OF_TAX_WITHHELD; ?>':
				$('#month_year_div').removeClass('none');
			break;

			case '<?php echo REPORT_REPORT_BIR_2316_CERTIFICATE_OF_COMPENSATION_PAYMENT; ?>':
				$('#month_year_div').removeClass('none');
				$('#office_list_div').removeClass('none');
				$('#employee_filtered_div').removeClass('none');
				$('#signatory_ca1_div').removeClass('none');
				$('#signatory_ca2_div').removeClass('none');
				$('#signatory_ca1')[0].selectize.destroy();
				$('#signatory_ca2')[0].selectize.destroy();
				var result = '<option value="">Select Signatory...</option>';
				var employees = <?php echo json_encode($signatory_ca) ?>;

				for(var i=0 ; i < employees.length; i++)
				{
						
					result += '<option value="' + employees[i]['report_signatory_id'] + '">' + employees[i]['signatory_name'] + '</option>';
						
				}
				$('#signatory_ca1').html(result).selectize();
				$('#signatory_ca2').html(result).selectize();
			break;

			case '<?php echo REPORT_BIR_ALPHALIST; ?>':
				// $('.file_type').addClass('none');
				// $('.dat_file_label').removeClass('none');
				$('#year_div').removeClass('none');
			break;

			case '<?php echo REPORT_BIR_ALPHALIST_WITH_PREVIOUS_EMPLOYER; ?>':
				$('#date_range_div').removeClass('none');
			break;

			case '<?php echo REPORT_BIR_ALPHALIST_TERMINATED_BEFORE_YEAR_END; ?>':
				$('#date_range_div').removeClass('none');
			break;

			case '<?php echo REPORT_YEAR_END_ADJUSTMENT_REPORT_PER_OFFFICE; ?>':
				$('#office_list_div').removeClass('none');
				$('#year_div').removeClass('none');
				$('#rem_prepared_by_div').removeClass('none');
				$('#rem_certified_by_div').removeClass('none');
			break;

			case '<?php echo REPORT_DISBURSEMENT_VOUCHER; ?>':
				$('#employee_div').removeClass('none');
				$('#voucher_div').removeClass('none');
				$('#signatory_a_div').removeClass('none');
				$('#signatory_b_div').removeClass('none');
				$('#signatory_c_div').removeClass('none');
			break;

			case '<?php echo REPORT_ENGAS_FILE_FOR_UPLOADING; ?>':
				$('#payroll_type_div').removeClass('none');
				$('#payroll_period_div').removeClass('none');
			break;

			case '<?php echo REPORT_MONTHLY_SALARY_SCHEDULE; ?>': 
				$('#salary_schedule_div').removeClass('none');
			break;

			case '<?php echo REPORT_SPECIAL_PAYROLL_SUMMARY_PER_OFFICE; ?>': 
				$('#compensation_type_special_div').removeClass('none');
				$('#month_year_div').removeClass('none');
				$('#prep_by_div').removeClass('none');
				$('#prep_by')[0].selectize.destroy();
				var result_prep = '<option value="">Select Employee</option>';
				var prepared_by = <?php echo json_encode($prepared_by) ?>;

				for(var i=0 ; i < prepared_by.length; i++)
				{
						
					result_prep += '<option value="' + prepared_by[i]['report_signatory_id'] + '">' + prepared_by[i]['signatory_name'] + '</option>';
						
				}		
				$('#prep_by').html(result_prep).selectize();
			break;

			case '<?php echo REPORT_GSIS_CERTIFICATE_CONTRIBUTION; ?>': 
				$('#employee_div').removeClass('none');
				$('#date_range_div').removeClass('none');
				$('#cert_by_div').removeClass('none');
				$('#prep_by_div').removeClass('none');
				$('#contrib_deduction_type_div').removeClass('none');
				$('#cert_by')[0].selectize.destroy();
				$('#prep_by')[0].selectize.destroy();
				var result = '<option value="">Select Employee</option>';
				var signatories = <?php echo json_encode($signatories) ?>;

				for(var i=0 ; i < signatories.length; i++)
				{
						
					result += '<option value="' + signatories[i]['report_signatory_id'] + '">' + signatories[i]['signatory_name'] + '</option>';
						
				}

				var result_prep = '<option value="">Select Employee</option>';
				var prepared_by = <?php echo json_encode($prepared_by) ?>;

				for(var i=0 ; i < prepared_by.length; i++)
				{
						
					result_prep += '<option value="' + prepared_by[i]['report_signatory_id'] + '">' + prepared_by[i]['signatory_name'] + '</option>';
						
				}
				$('#cert_by').html(result).selectize();				
				$('#prep_by').html(result_prep).selectize();

				load_selectize({
		        	url: $base_url+'main/reports_payroll/reports_payroll/get_deduction_types_by_contribution/',
		        	data: {remittance_payee: 'GSIS'},
					target: 'contrib_deduction_type'
				});
			break;

			case '<?php echo REPORT_PHILHEALTH_CERTIFICATE_CONTRIBUTION; ?>': 
				$('#employee_div').removeClass('none');
				$('#date_range_div').removeClass('none');
				$('#cert_by_div').removeClass('none');
				$('#prep_by_div').removeClass('none');
				$('#cert_by')[0].selectize.destroy();
				$('#prep_by')[0].selectize.destroy();
				var result = '<option value="">Select Employee</option>';
				var signatories = <?php echo json_encode($signatories) ?>;

				for(var i=0 ; i < signatories.length; i++)
				{
						
					result += '<option value="' + signatories[i]['report_signatory_id'] + '">' + signatories[i]['signatory_name'] + '</option>';
						
				}

				var result_prep = '<option value="">Select Employee</option>';
				var prepared_by = <?php echo json_encode($prepared_by) ?>;

				for(var i=0 ; i < prepared_by.length; i++)
				{
						
					result_prep += '<option value="' + prepared_by[i]['report_signatory_id'] + '">' + prepared_by[i]['signatory_name'] + '</option>';
						
				}
				$('#cert_by').html(result).selectize();				
				$('#prep_by').html(result_prep).selectize();
			break;

			case '<?php echo REPORT_PAGIBIG_CERTIFICATE_CONTRIBUTION; ?>': 
				$('#employee_div').removeClass('none');
				$('#date_range_div').removeClass('none');
				$('#cert_by_div').removeClass('none');
				$('#prep_by_div').removeClass('none');
				$('#cert_by')[0].selectize.destroy();
				$('#prep_by')[0].selectize.destroy();
				var result = '<option value="">Select Employee</option>';
				var signatories = <?php echo json_encode($signatories) ?>;

				for(var i=0 ; i < signatories.length; i++)
				{
						
					result += '<option value="' + signatories[i]['report_signatory_id'] + '">' + signatories[i]['signatory_name'] + '</option>';
						
				}

				var result_prep = '<option value="">Select Employee</option>';
				var prepared_by = <?php echo json_encode($prepared_by) ?>;

				for(var i=0 ; i < prepared_by.length; i++)
				{
						
					result_prep += '<option value="' + prepared_by[i]['report_signatory_id'] + '">' + prepared_by[i]['signatory_name'] + '</option>';
						
				}
				$('#cert_by').html(result).selectize();				
				$('#prep_by').html(result_prep).selectize();
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

			case '<?php echo REPORT_BIR_2307_CERTIFICATE_OF_CREDITABLE_TAX_WITHHELD_AT_SOURCE; ?>': 
				$('#year_div').removeClass('none');
				$('#quarter_div').removeClass('none');
				$('#office_list_div').removeClass('none');
				$('#employee_filtered_div').removeClass('none');
			break;

			case '<?php echo REPORT_EMPLOYEES_PAID_BY_VOUCHER; ?>': 
				$('#month_year_div').removeClass('none');
				$('#office_list_div').removeClass('none');
				$('#office_list_span').hide();
				$('#cert_by_div').removeClass('none');
				$('#prep_by_div').removeClass('none');
				$('#cert_by')[0].selectize.destroy();
				$('#prep_by')[0].selectize.destroy();
				var result = '<option value="">Select Employee</option>';
				var signatories = <?php echo json_encode($signatories) ?>;

				for(var i=0 ; i < signatories.length; i++)
				{
						
					result += '<option value="' + signatories[i]['report_signatory_id'] + '">' + signatories[i]['signatory_name'] + '</option>';
						
				}

				var result_prep = '<option value="">Select Employee</option>';
				var prepared_by = <?php echo json_encode($prepared_by) ?>;

				for(var i=0 ; i < prepared_by.length; i++)
				{
						
					result_prep += '<option value="' + prepared_by[i]['report_signatory_id'] + '">' + prepared_by[i]['signatory_name'] + '</option>';
						
				}
				$('#cert_by').html(result).selectize();				
				$('#prep_by').html(result_prep).selectize();
			break;

			case '<?php echo REPORT_BIR_2306_CERTIFICATE_OF_FINAL_TAX_WITHHELD_AT_SOURCE; ?>': 
				$('#office_list_div').removeClass('none');
				$('#employee_filtered_div').removeClass('none');
				$('#date_range_div').removeClass('none');
			break;

			case '<?php echo REPORT_EMPLOYEES_NOT_INCLUDED_IN_PAYROLL; ?>':
				$('#office_list_div').removeClass('none');
				$('#payroll_type_div').removeClass('none');
				$('#payroll_period_div').removeClass('none');
			break;

			case '<?php echo REPORT_COOP_REMITTANCE; ?>':
				$('#remittance_type_div').removeClass('none');
				$('#payroll_type_rem_div').removeClass('none');
				$('#month_year_div').removeClass('none');
				$('#office_list_div').removeClass('none');
				$('#rem_prepared_by_div').removeClass('none');
				$('#rem_certified_by_div').removeClass('none');
				$('#deduction_type_div').removeClass('none');
			break;

			case '<?php echo REPORT_BIR_2305_CERTIFICATE_OF_UPDATE; ?>': 
				$('#year_div').removeClass('none');
				$('#office_list_div').removeClass('none');
				$('#employee_filtered_div').removeClass('none');
			break;

			case '<?php echo REPORT_RESPONSIBILITY_CODE_PER_OFFICE; ?>':
				$('#office_list_div').removeClass('none');
				$('#payroll_type_div').removeClass('none');
				$('#payroll_period_div').removeClass('none');
			break;

			case '<?php echo REPORT_GENERAL_PAYROLL_ALPHALIST_FOR_JO; ?>':
				$('#office_list_div').removeClass('none');
				$('#payroll_period_div').removeClass('none');
				get_payroll_period_for_jo();
			break;
			
			case '<?php echo REPORT_EXPANDED_WITHHOLDING_TAX_AND_GOVERNMENT_MONEY_PAYMENT; ?>':
				$('#office_list_div').removeClass('none');
				$('#payroll_period_div').removeClass('none');
				get_payroll_period_for_jo();
			break;

			default:
				$('#tracking_code_div').addClass('none');
			break;
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
			$.post($base_url + 'main/reports_payroll/reports_payroll/get_payroll_period/', data, function(result)
			{
			  	if(result.flag == 1){
					payroll_period.load(function(callback) {
						callback(result.list);
					});
			 	}				  
			}, 'json');
		}
		
		//FOR GENERAL PAYSLIP OFFICE
        load_selectize({
        	url: $base_url+'main/reports_payroll/reports_payroll/get_payslip_office/',
        	data: {payroll_type: payroll_type},
			target: 'office_gen_pay'
		});
		
  	});

  	$('#payroll_type_rem').on( "change", function() {
		var payroll_type  = $(this).val();

		var payroll_period = $("#payroll_period")[0].selectize;
		var data       = {'payroll_type':payroll_type};
		payroll_period.clear();
		payroll_period.clearOptions();

		if(payroll_type != '')
		{
			$.post($base_url + 'main/reports_payroll/reports_payroll/get_payroll_period/', data, function(result)
			{
			  	if(result.flag == 1){
					payroll_period.load(function(callback) {
						callback(result.list);
					});
			 	}				  
			}, 'json');
		}
		
		//FOR GENERAL PAYSLIP OFFICE
        load_selectize({
        	url: $base_url+'main/reports_payroll/reports_payroll/get_payslip_office/',
        	data: {payroll_type: payroll_type},
			target: 'office_gen_pay'
		});
		
  	});

  	$('#remittance_type').on( "change", function() {
  		var selected = $('#reports_p').val();
		var remittance_type  = $(this).val();
		var conso = 0;
		if(selected == '<?php echo REPORT_CONSOLIDATED_REMITTANCE_LIST_PER_OFFICE; ?>')
			conso = 1;
		var remittance_period = $("#remittance_period")[0].selectize;
		var data       = 'remittance_type_id=' + remittance_type;
		remittance_period.clear();
		remittance_period.clearOptions();

		if(remittance_type != '')
		{
			$.post($base_url + 'main/reports_payroll/reports_payroll/get_remittance_period/'+conso, data, function(result) {
			  	if(result.flag == 1) {
					remittance_period.load(function(callback) {
						callback(result.list);
					});
			 	}				  
			}, 'json');
		}

		load_selectize({
        	url: $base_url+'main/reports_payroll/reports_payroll/get_deduction_types_by_remittance/',
        	data: {remittance_type: remittance_type},
			target: 'deduction_type_multi'
		},function() {select_all_deduction()});
		
  	});
  	$('#payroll_period').on("change", function() {
		var period_id  = $(this).val();
  		var selected = $('#reports_p').val();
  		if((selected == '<?php echo REPORT_ATM_ALPHA_LIST; ?>' || selected == '<?php echo REPORT_ATM_ALPHA_LIST2; ?>' || selected == '<?php echo REPORT_BANK_PAYROLL_REGISTER; ?>') && period_id != '')
  		{
  			load_selectize({
	        	url: $base_url+'main/reports_payroll/reports_payroll/get_payout_dates/',
	        	data: {period_id: period_id},
				target: 'payout_date'
			});
  		}
       
		
  	});
  	$('#employee').on( "change", function() {
  		var report_type = $('#reports_p').val();
  		if(report_type == '<?php echo REPORT_DISBURSEMENT_VOUCHER; ?>')
  		{
  			var employee  = $(this).val();

			var voucher = $("#voucher")[0].selectize;
			var data       = {'employee':employee};
			voucher.clear();
			voucher.clearOptions();

			if(employee != '')
			{
				$.post($base_url + 'main/reports_payroll/disbursement_voucher/get_voucher_dropdown/', data, function(result)
				{
				  	if(result.flag == 1){
						voucher.load(function(callback) {
							callback(result.list);
						});
				 	}				  
				}, 'json');
			}
  		}
		
  	});
  	$('#office_gen_pay').on( "change", function() {
		var office_id  = $(this).val();

		//FOR GENERAL PAYSLIP EMPLOYEE
        load_selectize({
        	url: $base_url+'main/reports_payroll/reports_payroll/get_employee_by_office/',
        	data: {office_id: office_id},
			target: 'employee_gen_pay'
		});
		
  	});
  	$('#office_spe_pay').on("change", function() {
		var office_id  = $(this).val();

		//FOR GENERAL PAYSLIP EMPLOYEE
        load_selectize({
        	url: $base_url+'main/reports_payroll/reports_payroll/get_employee_by_office/',
        	data: {office_id: office_id},
			target: 'employee_gen_pay'
		});
		
  	});
  	
  	$('#month').on("change", function(){
		$('#month_text').val($(this).text());  	  	
  	});

  	$('#reports_p').on("change", function(){
		var report = $(this).val();
		if(report == "<?php echo REPORT_REPORT_BIR_2316_CERTIFICATE_OF_COMPENSATION_PAYMENT;?>" || report == "<?php echo REPORT_YEAR_END_ADJUSTMENT_REPORT_PER_OFFFICE;?>")
		{
			$('#month_span').hide();
		}
		else
		{
			$('#month_span').show();
		}
  	});

  	$('#office_list').on("change", function() {
		var office_id  = $(this).val();
		//FOR GENERAL PAYSLIP EMPLOYEE
        load_selectize({
        	url: $base_url+'main/reports_payroll/reports_payroll/get_employee_by_office/',
        	data: {office_id: office_id},
			target: 'employee_filtered'
		});
		
  	});
	
  	$('#remittance_period').on("change", function(){
		$('#remittance_period_text').val($(this).text());
  	});

  	$('#payroll_period').on("change", function(){
		$('#payroll_period_text').val($(this).text());
  	});

  	function get_payroll_period_for_jo()
  	{
  	  	var payroll_type = <?php echo $payroll_type_jo;?>;
  		var payroll_period = $("#payroll_period")[0].selectize;
		var data       = {'payroll_type':payroll_type};
		payroll_period.clear();
		payroll_period.clearOptions();

		if(payroll_type != '')
		{
			$.post($base_url + 'main/reports_payroll/reports_payroll/get_payroll_period/', data, function(result)
			{
			  	if(result.flag == 1){
					payroll_period.load(function(callback) {
						callback(result.list);
					});
			 	}				  
			}, 'json');
		}
  	}
  	$('#select_all_deduction').on("change", function(){
		select_all_deduction();
  	});
});
function select_all_deduction()	{
	
	var deduction_type_multi = $("#deduction_type_multi")[0].selectize;

	
	if($('#select_all_deduction').is(":checked"))
	{
		$.each(deduction_type_multi.options, function (key, val) {
		  	deduction_type_multi.addItems(val.value,true);
		});
	}
	else
	{
		deduction_type_multi.clear();
	}
}
</script>