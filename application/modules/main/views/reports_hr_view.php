
<section id="content" class="p-t-n m-t-n ">
    
    <!--breadcrumbs start-->
    <div id="breadcrumbs-wrapper" class=" grey lighten-3"> 
      <div class="container">
        <div class="row">
         <div class="col s6 m6 l6">
         		<h5 class="breadcrumbs-title">Human Resources Reports</h5>
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
							<div class="row" id="categories">
							    <div class="input-field col s3">
							      	<label class="label position-right">Category <span class="required"> * </span></label>
							    </div>
							    <div class="col s8 p-t-md">
									<select class="selectize" id="category" name="category" placeholder="Select category...">
										<option value="">Select category...</option>
										<option value="D">DEMOGRAPHICS</option>
										<option value="OS">ORGANIZATIONAL STRUCTURE</option>
										<option value="WAB">WELFARE AND BENEFITS</option>
							    	</select>
							    </div>
							</div>
							<div class="row">
						    <div class="input-field col s3">
						      <label class="label position-right">Report <span class="required"> * </span></label>
						    </div>
						    <div class="col s8 p-t-md">
									<select class="selectize" id="reports_hr" name="reports" placeholder="Select report..." required="true">
								        <option>Select Report...</option>
									</select>
								
						    </div>
						</div>
						<div id="probationary_div" class="row none">
							<div class="input-field col s3">
								<label class="label position-right">Under Probationary? <span class="required"> * </span></label>
							</div>
							<div class="col s8 p-t-md">
								<select class="selectize" id="probationary" name="probationary" placeholder="Yes/No">
									<option value="">Select Status</option>
									<option value="Y">YES</option>
									<option value="N">NO</option>
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
									<option value="">Select Employee</option>
									<?php if (!EMPTY($employees)): ?>
										<?php foreach ($employees as $emp): ?>
											<option value="<?php echo $emp['employee_id']?>"><?php echo $emp['employee_name'] ?></option>
										<?php endforeach;?>
									<?php endif;?>
								</select>
						    </div>
						</div>
<!-- ncocampo -->
						<div class="row none" id="reg_employee_div">
						    <div class="input-field col s3">
						      	<label class="label position-right">Employee <span class="required"> * </span></label>
						    </div>
						    <div class="col s8 p-t-md">
								<select id="reg_employee" name="reg_employee" class="selectize" placeholder="Select Employee">
									<option value="">Select Employee</option>
									<?php if (!EMPTY($reg_employees)): ?>
										<?php foreach ($reg_employees as $emp): ?>
											<option value="<?php echo $emp['employee_id']?>"><?php echo $emp['reg_employee_name'] ?></option>
										<?php endforeach;?>
									<?php endif;?>
								</select>
						    </div>
						</div>
						<div class="row none" id="nosa_cr_employee_div">
						    <div class="input-field col s3">
						      	<label class="label position-right">Employee <span class="required"> * </span></label>
						    </div>
						    <div class="col s8 p-t-md">
								<select id="nosa_cr_employee" name="nosa_cr_employee" class="selectize" placeholder="Select Employee">
									<option value="">Select Employee</option>
									<?php if (!EMPTY($nosa_employees)): ?>
										<?php foreach ($nosa_employees as $emp): ?>
											<option value="<?php echo $emp['employee_id']?>"><?php echo $emp['nosa_employee_name'] ?></option>
										<?php endforeach;?>
									<?php endif;?>
								</select>
						    </div>
						</div>
<!-- ncocampo -->


						<div class="row none" id="step_incr_div">
						    <div class="input-field col s3">
						      	<label class="label position-right">Employee <span class="required"> * </span></label>
						    </div>
						    <div class="col s8 p-t-md">
								<select id="step_incr" name="step_incr" class="selectize" placeholder="Select Employee">									
									<option value="">Select Employee</option>
									
								</select>
						    </div>
						</div>
						<!-- LIST OF EMPLOYEES WITH LONGEVITY PAY -->
						<div class="row none" id="employee_longevity_div">
						    <div class="input-field col s3">
						      	<label class="label position-right">Employee <span class="required"> * </span></label>
						    </div>
						    <div class="col s8 p-t-md">
								<select id="employee_longevity" name="employee_longevity" class="selectize" placeholder="Select Employee">
									<option value="">Select Employee</option>
									<?php if (!EMPTY($employee_longevity)): ?>
										<?php foreach ($employee_longevity as $el): ?>
											<option value="<?php echo $el['employee_id']?>"><?php echo $el['employee_name'] ?></option>
										<?php endforeach;?>
									<?php endif;?>
								</select>
						    </div>
						</div>
						<!-- LIST OF POSITION LEVEL -->
						<div id="position_div" class="row none">
						   	<div class="input-field col s3">
						     	<label class="label position-right">Position Level<span class="required"> * </span></label>
						    </div>
						    <div class="col s8 p-t-md">
								<select id="position_level" name="position" class="selectize" placeholder="Select Position">
									<option value="">Select Position Level</option>
										<?php if (!EMPTY($position_level)): ?>
											<?php foreach ($position_level as $pos): ?>
												<option value="<?php echo $pos['position_level_id']?>"><?php echo $pos['position_level_name'] ?></option>
											<?php endforeach;?>
										<?php endif;?>
								</select>
						    </div>
						</div>
						<!-- LIST OF POSITIONS TITLE-->
						<div id="position_title_div" class="row none">
						   	<div class="input-field col s3">
						     	<label class="label position-right">Position Title<span class="required"> * </span></label>
						    </div>
						    <div class="col s8 p-t-md">
								<select id="position_title" name="position_title" class="selectize" placeholder="Select Title">
								</select>
						    </div>
						</div>
						<!-- LIST OF CLASSES -->
						<div id="class_div" class="row none">
						  	<div class="input-field col s3">
						     	<label class="label position-right">Class <span class="required"> * </span></label>
						    </div>
						    <div class="col s8 p-t-md">
								<select id="class" name="class" class="selectize" placeholder="Select Class">
									<option value="">Select Class</option>
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
						    	<label class="label position-right">Salary Grade <span class="required"> * </span></label>
						    </div>
						    <div class="col s8 p-t-md">
								<select id="salary_grade" name="salary_grade" class="selectize" placeholder="Select Salary Grade">
									<option value="">Select Salary Grade</option>
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
						    	<label class="label position-right">Birth Month <span class="required"> * </span></label>
						    </div>
						    <div class="col s8 p-t-md">
								<select class="selectize" id="birth_month" name="birth_month" placeholder="Select Month">
									<option value="">Select Month</option>
									<option value="01">JANUARY</option>
									<option value="02">FEBRUARY</option>
									<option value="03">MARCH</option>
									<option value="04">APRIL</option>
									<option value="05">MAY</option>
									<option value="06">JUNE</option>
									<option value="07">JULY</option>
									<option value="08">AUGUST</option>
									<option value="09">SEPTEMBER</option>
									<option value="10">OCTOBER</option>
									<option value="11">NOVEMBER</option>
									<option value="12">DECEMBER</option>
						    	</select>
						    </div>
						</div>
						<!-- LIST OF MONTH AND YEAR -->
						<div id="month_year_div" class="row none">
						   	<div class="input-field col s3">
						    	<label class="label position-right">Month <span class="required"> * </span></label>
						    </div>
						    <div class="col s3 p-t-md">
								<?php echo create_months('month', null, false)?>
						    </div>
						    <div class="input-field col s2">
						    	<label class="label position-right">Year<span class="required"> * </span></label>
						    </div>
						    <div class="col s3 p-t-md">
								<?php echo create_years('2000', date('Y'), 'year')?>
						    </div>
						</div>

						<!-- LIST OF MONTH AND YEAR -->
						<div id="year_div" class="row none">
						    <div class="input-field col s3">
						    	<label class="label position-right">Year<span class="required"> * </span></label>
						    </div>
						    <div class="col s3 p-t-md">
								<?php echo create_years('2000', date('Y'), 'years')?>
						    </div>
						</div>
						<!-- AGE RANGE -->
						<div id="age_div" class="row none">
							   <div class="input-field col s3">
							    	<label class="label position-right">Age Range <span class="required"> * </span></label>
							    </div>
							    <div class="col s2 p-t-md">
									<input type="number" name="age_from" class="validate number" id="age_from" min="0" placeholder="from...">
							    </div>
							    <div class="col s2 p-t-md">
									<input type="number" class="validate number" name="age_to" id="age_to" min="0" placeholder="to...">
							    </div>
						</div>
						<!-- LIST OF GENDERS -->
						<div id="gender_div" class="row none">
							   <div class="input-field col s3">
							    	<label class="label position-right">Gender <span class="required"> * </span></label>
							    </div>
							    <div class="col s8 p-t-md">
									<select id="gender" name="gender" class="selectize" placeholder="Select Gender">
										<option value="">Select Gender</option>
											<?php if (!EMPTY($genders)): ?>
												<?php foreach ($genders as $gender): ?>
													<option value="<?php echo $gender['gender_code']?>"><?php echo $gender['gender'] ?></option>
												<?php endforeach;?>
											<?php endif;?>
									</select>
							    </div>
						</div>
						<!-- STATUS -->
						<div id="transfer_div" class="row none">
						   <div class="input-field col s3">
						    	<label class="label position-right">Transfer Movement <span class="required"> * </span></label>
						    </div>
						    <div class="col s8 p-t-md">
								<select id="transfer" name="transfer" class="selectize" placeholder="Select Transfer Movement">
									<option value="">Select Transfer Movement</option> 
									<option value="<?php echo TRANSFER_IN?>">TRANSFER IN</option>
									<option value="<?php echo TRANSFER_OUT?>">TRANSFER OUT</option>
									<option value="<?php echo MOVT_TRANSFER_PROMOTION?>">TRANSFER WITH PROMOTION</option>
								</select>
						    </div>
						</div>
						<!-- LIST OF PROFESSION -->
						<div id="profession_div" class="row none">
						   <div class="input-field col s3">
						    	<label class="label position-right">Profession <span class="required"> * </span></label>
						    </div>
						    <div class="col s8 p-t-md">
								<select id="profession" name="profession" class="selectize" placeholder="Select Profession">
									<option value="">Select Profession</option> 
										<?php if (!EMPTY($professions)): ?>
											<?php foreach ($professions as $prof): ?>
												<option value="<?php echo $prof['profession_id']?>"><?php echo $prof['profession_name'] ?></option>
											<?php endforeach;?>
										<?php endif;?>
								</select>
						    </div>
						</div>
						<!-- LIST OF EMPLOYMENT STATUS -->
						<div id="employment_status_div" class="row none">
						   <div class="input-field col s3">
						    	<label class="label position-right">Employment Status <span class="required"> * </span></label>
						    </div>
						    <div class="col s8 p-t-md">
								<select id="employment_status" name="employment_status" class="selectize" placeholder="Select Employment Status">
										<option value="">Select Employment Status</option> 
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
						    	<label class="label position-right">Benefit Type <span class="required"> * </span></label>
						    </div>
						    <div class="col s8 p-t-md">
								<select id="benefit_type" name="benefit_type" class="selectize" placeholder="Select Benefit Type">
									<option value="">Select Benefit Type</option> 
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
						    	<label class="label position-right">Length of Service<span class="required"> * </span><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(month)</label>
						    </div>
						    <div class="col s2 p-t-md">
								<input class="validate number"  type="number" name="service_length_from" id="service_length_from" min="0" placeholder="from...">
						    </div>
						    <div class="col s2 p-t-md">
								<input class="validate number"  type="number" name="service_length_to" id="service_length_to" min="0" placeholder="to...">
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

						<!-- STATUS -->
						<div id="status_div" class="row none">
						   <div class="input-field col s3">
						    	<label class="label position-right">Status <span class="required"> * </span></label>
						    </div>
						    <div class="col s8 p-t-md">
								<select id="status" name="status" class="selectize" placeholder="Select Status">
									<option value="">Select Status</option>
									<option value="<?php echo REGULAR_PERMANENT?>">REGULAR/PERMANENT</option>
									<option value="<?php echo JOB_ORDER?>">JOB ORDER</option>
								</select>
						    </div>
						</div>
						<!-- DATE RANGE -->
						<div id="date_range_div" class="row none">
						   	<div class="input-field col s3">
						      	<label class="label position-right">Period <span class="required"> * </span></label>
						    </div>
						    <div class="col s4 p-t-md">
								<input type="text" class="validate datepicker_start" name="date_range_from" id="date_range_from" placeholder="YYYY/MM/DD" autocomplete="off"
										onkeypress="format_identifications('<?php echo DATE_FORMAT ?>',this.value,event,'date_range_from')">
						    </div>
						    <div class="col s4 p-t-md">
								<input type="text" class="validate datepicker_end" name="date_range_to" id="date_range_to" placeholder="YYYY/MM/DD" autocomplete="off"
										onkeypress="format_identifications('<?php echo DATE_FORMAT ?>',this.value,event,'date_range_to')">
						    </div>
						</div>
						<!-- SALARY GRADE -->
						<div id="salary_grade_rai_div" class="row none">
							<div class="input-field col s3">
								<label class="label position-right">Salary Grade<span class="required"> * </span></label>
							</div>
							<div class="col s8 p-t-md">
								<select class="selectize" id="salary_grade_rai" name="salary_grade_rai" placeholder="Select Salary Grade">
									<option value="">Select Status</option>
									<option value="X">18 and below</option>
									<option value="Y">19 - 25</option>
									<option value="Z">26 and above</option>
								</select>
							</div>
						</div>	

						<!-- MILESTONE -->
						<div id="milestone_div" class="row none">
							<div class="input-field col s3">
						      	<label class="label position-right">Milestone<span class="required"> * </span></label>
						    </div>
							<div class="col s8 p-t-md">
								<select id="milestone" name="milestone" class="selectize" placeholder="Select Milestone">
								</select>
							</div>
						</div>

						<!-- LIST OF OFFICES -->
						<div id="office_div" class="row none">
						   <div class="input-field col s3">
						     	<label class="label position-right">Office</label>
						    </div>
						    <div class="col s8 p-t-md">
								<select id="office" name="office" class="selectize" placeholder="Select Office">
										<option value="">Select Offices...</option>
										<?php if (!EMPTY($offices_list)): ?>
											<?php foreach ($offices_list as $o): ?>
												<option value="<?php echo $o['office_id']?>"><?php echo $o['name'] ?></option>
											<?php endforeach;?>
										<?php endif;?>
								</select>
						    </div>
						</div>

						<!-- LIST OF WP EMPLOYEES -->
						<div class="row none" id="salary_adjustment_div">
						    <div class="input-field col s3">
						      	<label class="label position-right">Employee <span class="required"> * </span></label>
						    </div>
						    <div class="col s8 p-t-md">
								<select id="salary_adjusted" name="salary_adjusted" class="selectize" placeholder="Select Employee">									
									<option value="">Select Employee</option>
									
								</select>
						    </div>
						</div>

						<!-- LIST OF SALARY ADJUSTMENT DATE -->
						<div class="row none" id="salary_adj_date_div">
						    <div class="input-field col s3">
						      	<label class="label position-right">Effectivity Date <span class="required"> * </span></label>
						    </div>
						    <div class="col s8 p-t-md">
								<select id="salary_adj_date" name="salary_adj_date" class="selectize" placeholder="Select Effectivity Date">									
									<option value="">Select Employee</option>
									
								</select>
						    </div>
						</div>

						<!-- DATE -->
						<div id="date_div" class="row none">
						    <div class="input-field col s3">
						      	<label class="label position-right">Date <span class="required"> * </span></label>
						    </div>
						    <div class="col s8 p-t-md">
								<input type="text" class="validate datepicker " name="date" id="date" placeholder="YYYY/MM/DD"
										onkeypress="format_identifications('<?php echo DATE_FORMAT ?>',this.value,event,'date')">
						    </div>
						</div>		

						<!-- PREPARED BY -->
						<div class="row none" id="prep_by_div">
						    <div class="input-field col s3">
						      	<label class="label position-right">Prepared By<span class="required"> * </span></label>
						    </div>
						    <div class="col s8 p-t-md">
								<select id="prep_by" name="prep_by" class="selectize" placeholder="Select Employee">
									<option value="">Select Employee</option> 
										<?php if (!EMPTY($prepared_by)): ?>
											<?php foreach ($prepared_by as $prep): ?>
												<option value="<?php echo $prep['report_signatory_id']?>"><?php echo $prep['signatory_name'] ?></option>
											<?php endforeach;?>
										<?php endif;?>
								</select>
						    </div>
						</div>

						<!-- REVIEWED BY -->
						<div class="row none" id="reviewed_by_div">
						    <div class="input-field col s3">
						      	<label class="label position-right">Reviewed By/<br>Approved By<span class="required"> * </span></label>
						    </div>
						    <div class="col s8 p-t-md">
								<select id="reviewed_by" name="reviewed_by" class="selectize" placeholder="Select Employee">
									<option value="">Select Employee</option> 
										<?php if (!EMPTY($reviewed_by)): ?>
											<?php foreach ($reviewed_by as $rev): ?>
												<option value="<?php echo $rev['report_signatory_id']?>"><?php echo $rev['signatory_name'] ?></option>
											<?php endforeach;?>
										<?php endif;?>
								</select>
						    </div>
						</div>

						<!-- CERTIFIED BY -->
						<div class="row none" id="cert_by_div">
						    <div class="input-field col s3">
						      	<label class="label position-right">Certified By<span class="required"> * </span></label>
						    </div>
						    <div class="col s8 p-t-md">
								<select id="cert_by" name="cert_by" class="selectize" placeholder="Select Employee">
									<option value="">Select Employee</option> 
										<?php if (!EMPTY($certified_by)): ?>
											<?php foreach ($certified_by as $cert): ?>
												<option value="<?php echo $cert['report_signatory_id']?>"><?php echo $cert['signatory_name'] ?></option>
											<?php endforeach;?>
										<?php endif;?>
								</select>
						    </div>
						</div>

						<!-- RAI SIGNATORIES -->
						<div class="row none" id="rai_hrmo_div">
						    <div class="input-field col s3">
						      	<label class="label position-right">HRMO<span class="required"> * </span></label>
						    </div>
						    <div class="col s8 p-t-md">
								<select id="rai_hrmo" name="rai_hrmo" class="selectize" placeholder="Select Employee">
									<option value="">Select Employee</option> 
										<?php if (!EMPTY($reviewed_by)): ?>
											<?php foreach ($reviewed_by as $rev): ?>
												<option value="<?php echo $rev['report_signatory_id']?>"><?php echo $rev['signatory_name'] ?></option>
											<?php endforeach;?>
										<?php endif;?>
								</select>
						    </div>
						</div>

						<!-- CERTIFIED BY -->
						<div class="row none" id="rai_authorized_official_div">
						    <div class="input-field col s3">
						      	<label class="label position-right">Agency Head or<br>Authorized Official<span class="required"> * </span></label>
						    </div>
						    <div class="col s8 p-t-md">
								<select id="authorized_official" name="authorized_official" class="selectize" placeholder="Select Employee">
									<option value="">Select Employee</option> 
										<?php if (!EMPTY($certified_by)): ?>
											<?php foreach ($certified_by as $cert): ?>
												<option value="<?php echo $cert['report_signatory_id']?>"><?php echo $cert['signatory_name'] ?></option>
											<?php endforeach;?>
										<?php endif;?>
								</select>
						    </div>
						</div>


						<!-- position description signatories -->
						<!-- <div class="none" id="pos_descr_signatories">
							<div class="row">
							    <div class="input-field col s3">
							      	<label class="label position-right">Signatory 1<span class="required"> * </span></label>
							    </div>
							    <div class="col s8 p-t-md">
									<select id="pos_descr_signatory_1" name="pos_descr_signatory_1" class="selectize" placeholder="Select Employee">
										<option value="">Select Employee</option> 
											<?php if (!EMPTY($signatories)): ?>
												<?php foreach ($signatories as $sig): ?>
													<option value="<?php //echo $sig['report_signatory_id']?>"><?php //echo $sig['signatory_name'] ?></option>
												<?php endforeach;?>
											<?php endif;?>
									</select>
							    </div>
							</div>
							<div class="row">
							    <div class="input-field col s3">
							      	<label class="label position-right">Signatory 2<span class="required"> * </span></label>
							    </div>
							    <div class="col s8 p-t-md">
									<select id="pos_descr_signatory_2" name="pos_descr_signatory_2" class="selectize" placeholder="Select Employee">
										<option value="">Select Employee</option> 
											<?php if (!EMPTY($signatories)): ?>
												<?php foreach ($signatories as $sig): ?>
													<option value="<?php //echo $sig['report_signatory_id']?>"><?php //echo $sig['signatory_name'] ?></option>
												<?php endforeach;?>
											<?php endif;?>
									</select>
							    </div>
							</div>
							<div class="row">
							    <div class="input-field col s3">
							      	<label class="label position-right">Signatory 3<span class="required"> * </span></label>
							    </div>
							    <div class="col s8 p-t-md">
									<select id="pos_descr_signatory_3" name="pos_descr_signatory_3" class="selectize" placeholder="Select Employee">
										<option value="">Select Employee</option> 
											<?php if (!EMPTY($signatories)): ?>
												<?php foreach ($signatories as $sig): ?>
													<option value="<?php //echo $sig['report_signatory_id']?>"><?php //echo $sig['signatory_name'] ?></option>
												<?php endforeach;?>
											<?php endif;?>
									</select>
							    </div>
							</div>
						</div> -->
						<!-- KSS SIGNATORIES -->
						<div class="none" id="signatories">
							<div class="row">
							    <div class="input-field col s3">
							      	<label class="label position-right">Appointing/Officer/<br>Authority<span class="required"> * </span></label>
							    </div>
							    <div class="col s8 p-t-md">
									<select id="signatory_1" name="signatory_1" class="selectize" placeholder="Select Employee">
										<option value="">Select Employee</option> 
											<?php if (!EMPTY($signatories)): ?>
												<?php foreach ($signatories as $sig): ?>
													<option value="<?php echo $sig['report_signatory_id']?>"><?php echo $sig['signatory_name'] ?></option>
												<?php endforeach;?>
											<?php endif;?>
									</select>
							    </div>
							</div>
							<div class="row">
							    <div class="input-field col s3">
							      	<label class="label position-right">HRMO<span class="required"> * </span></label>
							    </div>
							    <div class="col s8 p-t-md">
									<select id="signatory_2" name="signatory_2" class="selectize" placeholder="Select Employee">
										<option value="">Select Employee</option> 
											<?php if (!EMPTY($signatories)): ?>
												<?php foreach ($signatories as $sig): ?>
													<option value="<?php echo $sig['report_signatory_id']?>"><?php echo $sig['signatory_name'] ?></option>
												<?php endforeach;?>
											<?php endif;?>
									</select>
							    </div>
							</div>
							<div class="row">
							    <div class="input-field col s3">
							      	<label class="label position-right">Chairperson, HRMPSB/<br> Placement Committee</label>
							    </div>
							    <div class="col s8 p-t-md">
									<select id="signatory_3" name="signatory_3" class="selectize" placeholder="Select Employee">
										<option value="">Select Employee</option> 
											<?php if (!EMPTY($signatories)): ?>
												<?php foreach ($signatories as $sig): ?>
													<option value="<?php echo $sig['report_signatory_id']?>"><?php echo $sig['signatory_name'] ?></option>
												<?php endforeach;?>
											<?php endif;?>
									</select>
							    </div>
							</div>
						</div>
						<!-- APPOINTING OFFICER AND HRMO -->
						<div id="appointing_officer_div" class="row none">
							<div class="input-field col s3">
							  <label class="label position-right">Appointing/Officer/<br>Authority<span class="required"> * </span></label>
							</div>
							<div class="col s8 p-t-md">
								<select class="selectize" id="appointing_officer" name="appointing_officer" placeholder="Select Employee">
									<option value="">Select Employee</option>
									<?php if (!EMPTY($signatories)): ?>
												<?php foreach ($signatories as $sig): ?>
													<option value="<?php echo $sig['report_signatory_id']?>"><?php echo $sig['signatory_name'] ?></option>
												<?php endforeach;?>
											<?php endif;?>
								</select>
							</div>
						</div>
						<div id="hrmo_div" class="row none">
							<div class="input-field col s3">
							  <label class="label position-right">HRMO<span class="required"> * </span></label>
							</div>
							<div class="col s8 p-t-md">
								<select class="selectize" id="hrmo" name="hrmo" placeholder="Select Employee">
									<option value="">Select Employee</option>
									<?php if (!EMPTY($signatories)): ?>
												<?php foreach ($signatories as $sig): ?>
													<option value="<?php echo $sig['report_signatory_id']?>"><?php echo $sig['signatory_name'] ?></option>
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

						<div id="signatory_title_div" class="row none">
						   <div class="input-field col s3">
						    	<label class="label position-right">Chairperson Signatory Title </label>
						    </div>
						    <div class="col s8 p-t-md">
								<select id="signatory_title" name="signatory_title" class="selectize" placeholder="Select Signatory Title">
									<option value="">Select Signatory Title</option>
									<option value="<?php echo CHAIRPERSON?>">Chairperson, HRMPSB/Placement Committee</option>
									<option value="<?php echo VICE_CHAIRPERSON?>">Vice-Chairperson, HRMPSB/Placement Committee</option>
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
$(function(){	
	$("#generate_report").on('click', function (){
		$('#form_reports').parsley('destroy');

		if ( $('#form_reports').parsley().isValid() ) {
			$('#form_reports').submit(function(e) {
				e.stopImmediatePropagation();
		    	e.preventDefault();	
				var id    	= '';
				var path    = '';
 
					var report  = $("#reports_hr").val();
   					if(report == "")
					{
						notification_msg("<?php echo ERROR ?>", "<b>Report </b> is required.");
						return false;
					}
					switch(report)
					{
						case 'RAI_part1':
							var office            = '';
							var date_from_raw 	  = $('#date_range_from').val();
							var date_to_raw 	  = $('#date_range_to').val();
							var date_from 		  = dateFormat(date_from_raw, 'yyyy-mm-dd');
							var date_to 		  = dateFormat(date_to_raw, 'yyyy-mm-dd');
							var salary_grade_rai  = $('#salary_grade_rai').val();
							var date      		  = date_from + 'A' + date_to;	
							var cert_by           = $('#authorized_official').val();
							var reviewed_by       = $('#rai_hrmo').val();
							var tracking_code     = $('#tracking_code').val();
							if(office == '')
							{
								var office = 'A';
							}
							if(date_from_raw == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Date From </b> is required.");
								return false;
							}
							if(date_to_raw == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Date To </b> is required.");
								return false;
							}
							if(date_to_raw < date_from_raw)
							{
								notification_msg("<?php echo ERROR ?>", "<b>Date To</b> should not be earlier than <b>Date From</b>.");
								return false;
							}
							if(salary_grade_rai == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Salary Grade</b> is required.");
								return false;
							}
							if(reviewed_by == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>HRMO</b> is required.");
								return false;
							}
							if(cert_by == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Agency Head or Authorized Official</b> is required.");
								return false;
							}
							path                  = cert_by + '/' + office + '/' + date + '/' + salary_grade_rai + '/' + reviewed_by + '/' + tracking_code;
						break;
						// case 'RAI_part2':
						// 	var office            = $('#office').val();
						// 	var date_from_raw 	  = $('#date_range_from').val();
						// 	var date_to_raw 	  = $('#date_range_to').val();
						// 	var date_from 		  = dateFormat(date_from_raw, 'yyyy-mm-dd');
						// 	var date_to 		  = dateFormat(date_to_raw, 'yyyy-mm-dd');
						// 	var date      		  = date_from + 'A' + date_to;	
						// 	var cert_by           = $('#cert_by').val();
						// 	var prep_by           = $('#prep_by').val();
						// 	var reviewed_by       = $('#reviewed_by').val();
						// 	var tracking_code     = $('#tracking_code').val();
						// 	if(office == '')
						// 	{
						// 		var office = 'A';
						// 	}
						// 	if(date_from_raw == "")
						// 	{
						// 		notification_msg("<?php echo ERROR ?>", "<b>Date From </b> is required.");
						// 		return false;
						// 	}
						// 	if(date_to_raw == "")
						// 	{
						// 		notification_msg("<?php echo ERROR ?>", "<b>Date To </b> is required.");
						// 		return false;
						// 	}
						// 	if(date_to_raw < date_from_raw)
						// 	{
						// 		notification_msg("<?php echo ERROR ?>", "<b>Date To</b> should not be earlier than <b>Date From</b>.");
						// 		return false;
						// 	}
						// 	if(cert_by == "")
						// 	{
						// 		notification_msg("<?php echo ERROR ?>", "<b>Certified by </b> is required.");
						// 		return false;
						// 	}
						// 	if(prep_by == "")
						// 	{
						// 		notification_msg("<?php echo ERROR ?>", "<b>Prepared by </b> is required.");
						// 		return false;
						// 	}
						// 	if(reviewed_by == "")
						// 	{
						// 		notification_msg("<?php echo ERROR ?>", "<b>Certified by </b> is required.");
						// 		return false;
						// 	}
						// 	path                  = cert_by + '/' + office + '/' + date + '/' + prep_by + '/' + reviewed_by + '/' + tracking_code;
						// break;
					case 'RAI_part2':
							// var office            = $('#office').val();
							var employee        	= $('#reg_employee').val();
							// var cert_by           = $('#cert_by').val();
							var reviewed_by       = $('#reviewed_by').val();
							var tracking_code     = $('#tracking_code').val();
							

							if(employee == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Employee </b> is required.");
								return false;
							}
							if(reviewed_by == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Reviewed By/Approved By </b> is required.");
								return false;
							}
							// path                  = cert_by + '/' + office + '/' + date + '/' + employee + '/' + reviewed_by + '/' + tracking_code;
							path                  = employee + '/' + null + '/' + null + '/' + reviewed_by + '/' + null + '/' + null;
						break;
						case '<?php echo REPORT_PERSONNEL_MOVEMENT ?>':
							var office            = $('#office').val();
							var date_from_raw 	  = $('#date_range_from').val();
							var date_to_raw 	  = $('#date_range_to').val();
							var date_from 		  = dateFormat(date_from_raw, 'yyyy-mm-dd');
							var date_to 		  = dateFormat(date_to_raw, 'yyyy-mm-dd');
							var date      		  = date_from + 'A' + date_to;	
							var cert_by           = $('#cert_by').val();
							var prep_by           = $('#prep_by').val();
							var tracking_code     = $('#tracking_code').val();
							if(office == '')
							{
								var office = 'A';
							}	
							if(date_from_raw == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Date From </b> is required.");
								return false;
							}
							if(date_to_raw == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Date To </b> is required.");
								return false;
							}
							if(date_to_raw < date_from_raw)
							{
								notification_msg("<?php echo ERROR ?>", "<b>Date To</b> should not be earlier than <b>Date From</b>.");
								return false;
							}	
							if(cert_by == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Certified by </b> is required.");
								return false;
							}
							if(prep_by == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Prepared by </b> is required.");
								return false;
							}
							path                  = cert_by + '/' + office + '/' + date + '/' + prep_by + '/' + null + '/' + tracking_code;;
						break;
						case '<?php echo REPORT_SERVICE_RECORD ?>':
							var employee          	= $('#employee').val();
							var cert_by           	= $('#cert_by').val();
							var tracking_code       = $('#tracking_code').val();
							if(employee == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Employee </b> is required.");
								return false;
							}
							if(cert_by == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Certified by </b> is required.");
								return false;
							}
							path                = employee + '/' + cert_by + '/' + null + '/' + null + '/' + null + '/' + tracking_code;
						break;						
						case '<?php echo REPORT_APPOINTMENT_CERTIFICATE ?>':
							var probationary    = $('#probationary').val();
							var employee        = $('#reg_employee').val();
							var signatory_1     = $('#signatory_1').val();
							var signatory_2     = $('#signatory_2').val();	
							var signatory_3		= $('#signatory_3').val();
							var signatory_title	= $('#signatory_title').val();
							if(probationary == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Probationary </b> is required.");
								return false;
							}
							if(employee == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Employee </b> is required.");
								return false;
							}
							if(signatory_1 == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Signatory 1 </b> is required.");
								return false;
							}
							if(signatory_2 == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Signatory 2 </b> is required.");
								return false;
							}
							// if(signatory_3 == "")
							// {
							// 	notification_msg("<?php //echo ERROR ?>", "<b>Signatory 3 </b> is required.");
							// 	return false;
							// }
							path                  = employee + '/' + null + '/' + signatory_1 + '/' + signatory_2 + '/' + signatory_3 + '/' + null;
						break;

						//ncocampo
						case '<?php echo REPORT_ASSUMPTION_TO_DUTY ?>':
							var employee        = $('#reg_employee').val();
							var signatory_1     = $('#appointing_officer').val();
							var signatory_2     = $('#hrmo').val();	
							if(employee == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Employee </b> is required.");
								return false;
							}
							if(signatory_1 == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Appointing/Officer/Authority </b> is required.");
								return false;
							}
							if(signatory_2 == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>HRMO </b> is required.");
								return false;
							}
							path                  = employee + '/' + null + '/' + signatory_1 + '/' + signatory_2 + '/' + signatory_3 + '/' + null;
						break;
						//01/11/2024


						case '<?php echo REPORT_PERSONAL_DATA_SHEET ?>':
							var employee          = $('#employee').val();
							var tracking_code     = $('#tracking_code').val();
							if(employee == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Employee </b> is required.");
								return false;
							}
							path                  = employee + '/' + null + '/' + null;
						break;
						case '<?php echo REPORT_POSITION_DESCRIPTION ?>':
							var employee        = $('#employee').val();
							var tracking_code   = $('#tracking_code').val();
							var cert_by         = $('#cert_by').val();
							var reviewed_by     = $('#reviewed_by').val();
							
							if(employee == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Employee </b> is required.") + '/' + null + '/' + null + '/' + tracking_code;
								return false;
							}
							if(cert_by == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Signatory 1 </b> is required.");
								return false;
							}
							if(reviewed_by == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Signatory 2 </b> is required.");
								return false;
							}
							path                  = employee + '/' + null + '/' + null + '/' + cert_by + '/' + reviewed_by + '/' + tracking_code;
						break;
						case '<?php echo REPORT_OFFICE ?>':
							var office          = $('#office').val();
							var date_from_raw 	= $('#date_range_from').val();
							var date_to_raw 	= $('#date_range_to').val();
							var date_from 		= dateFormat(date_from_raw, 'yyyy-mm-dd');
							var date_to 		= dateFormat(date_to_raw, 'yyyy-mm-dd');
							var date      		= date_from + 'A' + date_to;	
							var tracking_code   = $('#tracking_code').val();	
							var status   		= $('#status').val();
							if(status == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Status </b> is required.");
								return false;
							}
							if(office == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Office </b> is required.");
								return false;
							}		
							if(date_from_raw == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Date From </b> is required.");
								return false;
							}
							if(date_to_raw == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Date To </b> is required.");
								return false;
							}
							if(date_to_raw < date_from_raw)
							{
								notification_msg("<?php echo ERROR ?>", "<b>Date To</b> should not be earlier than <b>Date From</b>.");
								return false;
							}	
							path                  = null + '/' + office + '/' + date + '/' + status + '/' + null + '/' + tracking_code;
						break;
						case '<?php echo REPORT_POSITION_LEVEL ?>':
							var position_level_id = $('#position_level').val();
							var office            = $('#office').val();
							var date_from_raw 	  = $('#date_range_from').val();
							var date_to_raw 	  = $('#date_range_to').val();
							var date_from 		  = dateFormat(date_from_raw, 'yyyy-mm-dd');
							var date_to 		  = dateFormat(date_to_raw, 'yyyy-mm-dd');
							var date      		  = date_from + 'A' + date_to;	
							var tracking_code     = $('#tracking_code').val();	
							var status   		  = $('#status').val();
							if(status == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Status </b> is required.");
								return false;
							}
							if(office == '')
							{
								var office = 'A';
							}
							if(position_level_id == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Position Level </b> is required.");
								return false;
							}	
							if(date_from_raw == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Date From </b> is required.");
								return false;
							}
							if(date_to_raw == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Date To </b> is required.");
								return false;
							}
							if(date_to_raw < date_from_raw)
							{
								notification_msg("<?php echo ERROR ?>", "<b>Date To</b> should not be earlier than <b>Date From</b>.");
								return false;
							}						
							path                  = position_level_id + '/' + office + '/' + date + '/' + status + '/' + null + '/' + tracking_code;
						break;
						case '<?php echo REPORT_POSITION_TITLE ?>':
							var position_title    = $('#position_title').val();
							var office            = $('#office').val();
							var date_from_raw 	  = $('#date_range_from').val();
							var date_to_raw 	  = $('#date_range_to').val();
							var date_from 		  = dateFormat(date_from_raw, 'yyyy-mm-dd');
							var date_to 		  = dateFormat(date_to_raw, 'yyyy-mm-dd');
							var date      		  = date_from + 'A' + date_to;	
							var tracking_code     = $('#tracking_code').val();	
							var status   		  = $('#status').val();
							if(status == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Status </b> is required.");
								return false;
							}
							if(office == '')
							{
								var office = 'A';
							}
							if(position_title == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Position Level </b> is required.");
								return false;
							}
							if(date_from_raw == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Date From </b> is required.");
								return false;
							}
							if(date_to_raw == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Date To </b> is required.");
								return false;
							}
							if(date_to_raw < date_from_raw)
							{
								notification_msg("<?php echo ERROR ?>", "<b>Date To</b> should not be earlier than <b>Date From</b>.");
								return false;
							}							
							path                  = position_title + '/' + office + '/' + date + '/' + status + '/' + null + '/' + tracking_code;
						break;
						case '<?php echo REPORT_CLASS ?>':
							var class_id        = $('#class').val();
							var office          = $('#office').val();
							var date_from_raw 	= $('#date_range_from').val();
							var date_to_raw 	= $('#date_range_to').val();
							var date_from 		= dateFormat(date_from_raw, 'yyyy-mm-dd');
							var date_to 		= dateFormat(date_to_raw, 'yyyy-mm-dd');
							var date      		= date_from + 'A' + date_to;	
							var tracking_code   = $('#tracking_code').val();	
							var status   		= $('#status').val();
							if(status == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Status </b> is required.");
								return false;
							}
							if(office == '')
							{
								var office = 'A';
							}
							if(class_id == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Class </b> is required.");
								return false;
							}	
							if(date_from_raw == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Date From </b> is required.");
								return false;
							}
							if(date_to_raw == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Date To </b> is required.");
								return false;
							}
							if(date_to_raw < date_from_raw)
							{
								notification_msg("<?php echo ERROR ?>", "<b>Date To</b> should not be earlier than <b>Date From</b>.");
								return false;
							}						
							path                  = class_id + '/' + office + '/' + date + '/' + status + '/' + null + '/' + tracking_code;
						break;
						case '<?php echo REPORT_SALARY_GRADE ?>':
							var salary_grade    = $('#salary_grade').val();
							var status    		= $('#status').val();
							var office          = $('#office').val();
							var date_from_raw 	= $('#date_range_from').val();
							var date_to_raw 	= $('#date_range_to').val();
							var date_from 		= dateFormat(date_from_raw, 'yyyy-mm-dd');
							var date_to 		= dateFormat(date_to_raw, 'yyyy-mm-dd');
							var date      		= date_from + 'A' + date_to;
							var tracking_code   = $('#tracking_code').val();	
							if(office == '')
							{
								var office = 'A';
							}

							if(salary_grade == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Salary Grade </b> is required.");
								return false;
							}	
							if(status == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Status</b> is required.");
								return false;
							}	
							if(date_from_raw == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Date From </b> is required.");
								return false;
							}
							if(date_to_raw == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Date To </b> is required.");
								return false;
							}
							if(date_to_raw < date_from_raw)
							{
								notification_msg("<?php echo ERROR ?>", "<b>Date To</b> should not be earlier than <b>Date From</b>.");
								return false;
							}
							path                  = salary_grade + '/' + office + '/' + date + '/' + status + '/' + null + '/' + tracking_code;
						break;
						case '<?php echo REPORT_BIRTH_DATE ?>':
							var birth_month 	= $('#birth_month').val();
							var office      	= $('#office').val();
							var date_from_raw 	= $('#date_range_from').val();
							var date_to_raw 	= $('#date_range_to').val();
							var date_from 		= dateFormat(date_from_raw, 'yyyy-mm-dd');
							var date_to 		= dateFormat(date_to_raw, 'yyyy-mm-dd');
							var date      		= date_from + 'A' + date_to;
							var tracking_code   = $('#tracking_code').val();	
							var status   		= $('#status').val();
							if(status == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Status </b> is required.");
								return false;
							}
							if(office == '')
							{
								var office = 'A';
							}
							if(birth_month == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Birth Month </b> is required.");
								return false;
							}	
							if(date_from_raw == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Date From </b> is required.");
								return false;
							}
							if(date_to_raw == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Date To </b> is required.");
								return false;
							}
							if(date_to_raw < date_from_raw)
							{
								notification_msg("<?php echo ERROR ?>", "<b>Date To</b> should not be earlier than <b>Date From</b>.");
								return false;
							}
							path                  = birth_month + '/' + office + '/' + date + '/' + status + '/' + null + '/' + tracking_code;
						break;
						case '<?php echo REPORT_AGE ?>':
							var age_range 		= $('#age_from').val() + '-' + $('#age_to').val();
							var office    		= $('#office').val();
							var date_from_raw 	= $('#date_range_from').val();
							var date_to_raw 	= $('#date_range_to').val();
							var date_from 		= dateFormat(date_from_raw, 'yyyy-mm-dd');
							var date_to 		= dateFormat(date_to_raw, 'yyyy-mm-dd');
							var date      		= date_from + 'A' + date_to;
							var age_from  		= $('#age_from').val();
							var age_to    		= $('#age_to').val();
							var tracking_code   = $('#tracking_code').val();	
							var status   		= $('#status').val();
							if(status == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Status </b> is required.");
								return false;
							}
							if(office == '')
							{
								var office = 'A';
							}
							if(age_from == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Age From </b> is required.");
								return false;
							}	
							if(age_to == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Age To </b> is required.");
								return false;
							}
							if(date_from_raw == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Date From </b> is required.");
								return false;
							}
							if(date_to_raw == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Date To </b> is required.");
								return false;
							}
							if(date_to_raw < date_from_raw)
							{
								notification_msg("<?php echo ERROR ?>", "<b>Date To</b> should not be earlier than <b>Date From</b>.");
								return false;
							}	
							path                  = age_range + '/' + office + '/' + date + '/' + status + '/' + null + '/' + tracking_code;
						break;
						case '<?php echo REPORT_GENDER ?>':
							var gender            = $('#gender').val();
							var status            = $('#status').val();
							var office            = $('#office').val();
							var date_from_raw 	  = $('#date_range_from').val();
							var date_to_raw 	  = $('#date_range_to').val();
							var date_from 		  = dateFormat(date_from_raw, 'yyyy-mm-dd');
							var date_to 		  = dateFormat(date_to_raw, 'yyyy-mm-dd');
							var date      		  = date_from + 'A' + date_to;
							var tracking_code     = $('#tracking_code').val();
							if(office == '')
							{
								var office = 'A';
							}
							if(gender == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Gender </b> is required.");
								return false;
							}
							if(status == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Status </b> is required.");
								return false;
							}
							if(date_from_raw == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Date From </b> is required.");
								return false;
							}
							if(date_to_raw == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Date To </b> is required.");
								return false;
							}
							if(date_to_raw < date_from_raw)
							{
								notification_msg("<?php echo ERROR ?>", "<b>Date To</b> should not be earlier than <b>Date From</b>.");
								return false;
							}
							path                  = gender + '/' + office + '/' + date + '/' + status + '/' + null + '/' + tracking_code;
						break;
						case '<?php echo REPORT_PROFESSION ?>':
							var profession        = $('#profession').val();
							var office            = $('#office').val();
							var date_from_raw 	  = $('#date_range_from').val();
							var date_to_raw 	  = $('#date_range_to').val();
							var date_from 		  = dateFormat(date_from_raw, 'yyyy-mm-dd');
							var date_to 		  = dateFormat(date_to_raw, 'yyyy-mm-dd');
							var date      		  = date_from + 'A' + date_to;
							var tracking_code     = $('#tracking_code').val();	
							var status   		  = $('#status').val();
							if(status == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Status </b> is required.");
								return false;
							}
							if(office == '')
							{
								var office = 'A';
							}
							if(profession == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Profession </b> is required.");
								return false;
							}
							if(date_from_raw == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Date From </b> is required.");
								return false;
							}
							if(date_to_raw == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Date To </b> is required.");
								return false;
							}
							if(date_to_raw < date_from_raw)
							{
								notification_msg("<?php echo ERROR ?>", "<b>Date To</b> should not be earlier than <b>Date From</b>.");
								return false;
							}	
							path                  = profession + '/' + office + '/' + date + '/' + status + '/' + null + '/' + tracking_code;
						break;
						case '<?php echo REPORT_EMPLOYMENT_STATUS ?>':
							var employment_status = $('#employment_status').val();
							var office            = $('#office').val();
							var date_from_raw 	  = $('#date_range_from').val();
							var date_to_raw 	  = $('#date_range_to').val();
							var date_from 		  = dateFormat(date_from_raw, 'yyyy-mm-dd');
							var date_to 		  = dateFormat(date_to_raw, 'yyyy-mm-dd');
							var date      		  = date_from + 'A' + date_to;
							var tracking_code     = $('#tracking_code').val();	
							var status   		  = $('#status').val();
							if(office == '')
							{
								var office = 'A';
							}
							if(employment_status == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Employment Status </b> is required.");
								return false;
							}	
							if(date_from_raw == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Date From </b> is required.");
								return false;
							}
							if(date_to_raw == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Date To </b> is required.");
								return false;
							}
							if(date_to_raw < date_from_raw)
							{
								notification_msg("<?php echo ERROR ?>", "<b>Date To</b> should not be earlier than <b>Date From</b>.");
								return false;
							}
							path                  = employment_status + '/' + office + '/' + date + '/' + null + '/' + null + '/' + tracking_code;
						break;
						case '<?php echo REPORT_BENEFIT_ENTITLEMENT ?>':
							var benefit_type      = $('#benefit_type').val();
							var office            = $('#office').val();
							var date_from_raw 	  = $('#date_range_from').val();
							var date_to_raw 	  = $('#date_range_to').val();
							var date_from 		  = dateFormat(date_from_raw, 'yyyy-mm-dd');
							var date_to 		  = dateFormat(date_to_raw, 'yyyy-mm-dd');
							var date      		  = date_from + 'A' + date_to;
							var tracking_code     = $('#tracking_code').val();	
							var status   		  = $('#status').val();
							if(status == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Status </b> is required.");
								return false;
							}
							if(office == '')
							{
								var office = 'A';
							}
							if(benefit_type == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Benefit Type </b> is required.");
								return false;
							}		
							if(date_from_raw == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Date From </b> is required.");
								return false;
							}
							if(date_to_raw == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Date To </b> is required.");
								return false;
							}
							if(date_to_raw < date_from_raw)
							{
								notification_msg("<?php echo ERROR ?>", "<b>Date To</b> should not be earlier than <b>Date From</b>.");
								return false;
							}	
							path                  = benefit_type + '/' + office + '/' + date + '/' + status + '/' + null + '/' + tracking_code;
						break;
						case '<?php echo REPORT_SERVICE_LENGTH ?>':
							var service_length    = $('#service_length_from').val() + '-' + $('#service_length_to').val();
							var office            = $('#office').val();
							var date_raw 	  	  = $('#date').val();
							var date 		  	  = dateFormat(date_raw, 'yyyy-mm-dd');
							var tracking_code     = $('#tracking_code').val();
							var status   		  = $('#status').val();
							if(status == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Status </b> is required.");
								return false;
							}
							if(office == '')
							{
								var office = 'A';
							}
							if(service_length == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Service Length </b> is required.");
								return false;
							}	
							if(date_raw == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Date </b> is required.");
								return false;
							}
							path                  = service_length + '/' + office + '/' + date + '/' + status + '/' + null + '/' + tracking_code;
						break;
						case '<?php echo REPORT_RETIREES ?>':
							var office          = $('#office').val();
							var date_from_raw 	= $('#date_range_from').val();
							var date_to_raw 	= $('#date_range_to').val();
							var date_from 		= dateFormat(date_from_raw, 'yyyy-mm-dd');
							var date_to 		= dateFormat(date_to_raw, 'yyyy-mm-dd');
							var date      		= date_from + 'A' + date_to;
							var tracking_code   = $('#tracking_code').val();
							if(office == '')
							{
								var office = 'A';
							}
							if(date_from_raw == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Date From </b> is required.");
								return false;
							}
							if(date_to_raw == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Date To </b> is required.");
								return false;
							}
							if(date_to_raw < date_from_raw)
							{
								notification_msg("<?php echo ERROR ?>", "<b>Date To</b> should not be earlier than <b>Date From</b>.");
								return false;
							}						
							path                  = null + '/' + office + '/' + date + '/' + null + '/' + null + '/' + tracking_code;
						break;
						case '<?php echo REPORT_RESIGNED_EMPLOYEES ?>':
							var office          = $('#office').val();
							var date_from_raw 	= $('#date_range_from').val();
							var date_to_raw 	= $('#date_range_to').val();
							var date_from 		= dateFormat(date_from_raw, 'yyyy-mm-dd');
							var date_to 		= dateFormat(date_to_raw, 'yyyy-mm-dd');
							var date      		= date_from + 'A' + date_to;
							var tracking_code   = $('#tracking_code').val();	
							if(office == '')
							{
								var office = 'A';
							}	
							if(date_from_raw == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Date From </b> is required.");
								return false;
							}
							if(date_to_raw == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Date To </b> is required.");
								return false;
							}
							if(date_to_raw < date_from_raw)
							{
								notification_msg("<?php echo ERROR ?>", "<b>Date To</b> should not be earlier than <b>Date From</b>.");
								return false;
							}					
							path                  = null + '/' + office + '/' + date + '/' + null + '/' + null + '/' + tracking_code;
						break;
						case '<?php echo REPORT_PROMOTED_EMPLOYEES ?>':
							var office          = $('#office').val();
							var date_from_raw 	= $('#date_range_from').val();
							var date_to_raw 	= $('#date_range_to').val();
							var date_from 		= dateFormat(date_from_raw, 'yyyy-mm-dd');
							var date_to 		= dateFormat(date_to_raw, 'yyyy-mm-dd');
							var date      		= date_from + 'A' + date_to;
							var tracking_code   = $('#tracking_code').val();
							if(office == '')
							{
								var office = 'A';
							}
							if(date_from_raw == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Date From </b> is required.");
								return false;
							}
							if(date_to_raw == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Date To </b> is required.");
								return false;
							}
							if(date_to_raw < date_from_raw)
							{
								notification_msg("<?php echo ERROR ?>", "<b>Date To</b> should not be earlier than <b>Date From</b>.");
								return false;
							}						
							path                  = null + '/' + office + '/' + date + '/' + null + '/' + null + '/' + tracking_code;
						break;
						case '<?php echo REPORT_DROPPED_EMPLOYEES ?>':
							var office          = $('#office').val();
							var date_from_raw 	= $('#date_range_from').val();
							var date_to_raw 	= $('#date_range_to').val();
							var date_from 		= dateFormat(date_from_raw, 'yyyy-mm-dd');
							var date_to 		= dateFormat(date_to_raw, 'yyyy-mm-dd');
							var date      		= date_from + 'A' + date_to;
							var tracking_code   = $('#tracking_code').val();
							if(office == '')
							{
								var office = 'A';
							}
							if(date_from_raw == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Date From </b> is required.");
								return false;
							}
							if(date_to_raw == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Date To </b> is required.");
								return false;
							}
							if(date_to_raw < date_from_raw)
							{
								notification_msg("<?php echo ERROR ?>", "<b>Date To</b> should not be earlier than <b>Date From</b>.");
								return false;
							}		
							path                  = null + '/' + office + '/' + date + '/' + null + '/' + null + '/' + tracking_code;
						break;
						case '<?php echo REPORT_TRANSFEREES ?>':
							var office          = $('#office').val();
							var date_from_raw 	= $('#date_range_from').val();
							var date_to_raw 	= $('#date_range_to').val();
							var date_from 		= dateFormat(date_from_raw, 'yyyy-mm-dd');
							var date_to 		= dateFormat(date_to_raw, 'yyyy-mm-dd');
							var date      		= date_from + 'A' + date_to;
							var tracking_code   = $('#tracking_code').val();
							var transfer   		= $('#transfer').val();

							if(office == '')
							{
								var office = 'A';
							}	
							if(date_from_raw == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Date From </b> is required.");
								return false;
							}
							if(date_to_raw == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Date To </b> is required.");
								return false;
							}
							if(date_to_raw < date_from_raw)
							{
								notification_msg("<?php echo ERROR ?>", "<b>Date To</b> should not be earlier than <b>Date From</b>.");
								return false;
							}	
							if(transfer == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Transfer Movement </b> is required.");
								return false;
							}							
							path                  = transfer + '/' + office + '/' + date + '/' + null + '/' + null + '/' + tracking_code;
						break;
						case '<?php echo REPORT_NOTICE_SALARY_ADJUSTMENT ?>':
							var office            = $('#office').val();
							var employee       	  = $('#salary_adjusted').val();
							var date       	  	  = $('#salary_adj_date').val();
							var effectivity_date  = dateFormat(date, 'yyyy-mm-dd');
							if(employee == '')
							{
								var employee = 'A';
							}
							var cert_by       	  = $('#cert_by').val();
							var tracking_code     = $('#tracking_code').val();
							if(office == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Office </b> is required.");
								return false;
							}
							if(cert_by == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Certified by </b> is required.");
								return false;
							}
							if(date == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Effectivity Date</b> is required.");
								return false;
							}
							path                  = employee + '/' + office + '/' + effectivity_date + '/' + cert_by + '/' + null + '/' + tracking_code;
						break;
						//ncocampo 05/06/2024
						case '<?php echo REPORT_NOTICE_SALARY_ADJUSTMENT_COMPULSORY_RETIREMENT ?>':
							var employee       	  = $('#nosa_cr_employee').val();
							var cert_by       	  = $('#cert_by').val();
							var tracking_code     = $('#tracking_code').val();
							if(employee == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Employee </b> is required.");
								return false;
							}	
							if(cert_by == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Certified by </b> is required.");
								return false;
							}
							path                  = employee + '/' + cert_by + '/' + null+ '/' + null + '/' + null + '/' + tracking_code;
						break;
						//ncocampo 05/06/2024
						case '<?php echo REPORT_NOTICE_SALARY_STEP_INCREMENT ?>':
							var employee       	  = $('#step_incr').val();
							var cert_by       	  = $('#cert_by').val();
							var tracking_code     = $('#tracking_code').val();
							if(employee == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Employee </b> is required.");
								return false;
							}	
							if(cert_by == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Certified by </b> is required.");
								return false;
							}
							path                  = employee + '/' + cert_by + '/' + null+ '/' + null + '/' + null + '/' + tracking_code;
						break;
						case '<?php echo REPORT_NOTICE_STEP_INCREMENT ?>':
							var employee       	  = $('#step_incr').val();
							var cert_by       	  = $('#cert_by').val();
							var tracking_code     = $('#tracking_code').val();
							if(employee == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Employee </b> is required.");
								return false;
							}	
							if(cert_by == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Certified by </b> is required.");
								return false;
							}
							path                  = employee + '/' + cert_by + '/' + null+ '/' + null + '/' + null + '/' + tracking_code;
						break;
						case '<?php echo REPORT_NOTICE_LONGEVITY_PAY ?>':
							var employee          = $('#employee_longevity').val();
							var cert_by       	  = $('#cert_by').val();
							var tracking_code     = $('#tracking_code').val();
							if(employee == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Employee </b> is required.");
								return false;
							}	
							if(cert_by == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Certified by </b> is required.");
								return false;
							}
							path                  = employee + '/' + cert_by + '/' + null + '/' + null + '/' + null + '/' + tracking_code;
						break;
						case '<?php echo REPORT_NOTICE_LONGEVITY_PAY_INCREASE ?>':
							var employee          = $('#employee_longevity').val();
							var cert_by       	  = $('#cert_by').val();
							var tracking_code     = $('#tracking_code').val();
							var milestone     	  = $('#milestone').val();
							if(employee == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Employee </b> is required.");
								return false;
							}	
							if(cert_by == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Certified by </b> is required.");
								return false;
							}
							if(milestone == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Milestone </b> is required.");
								return false;
							}
							path                  = employee + '/' + cert_by + '/' + milestone + '/' + null + '/' + null + '/' + tracking_code;
						break;
						case '<?php echo REPORT_MONTHLY_ACCESSION ?>':
							var cert_by           = $('#cert_by').val();
							var reviewed_by       = $('#reviewed_by').val();
							var office            = $('#office').val();
							var date_from_raw 	  = $('#date_range_from').val();
							var date_to_raw 	  = $('#date_range_to').val();
							var date_from 		  = dateFormat(date_from_raw, 'yyyy-mm-dd');
							var date_to 		  = dateFormat(date_to_raw, 'yyyy-mm-dd');
							var date      		  = date_from + 'A' + date_to;
							var tracking_code     = $('#tracking_code').val();		
							if(cert_by == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Certified by </b> is required.");
								return false;
							}	
							if(reviewed_by == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Reviewed by </b> is required.");
								return false;
							}
							if(office == '')
							{
								var office = 'A';
							}
							if(date_from_raw == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Date From </b> is required.");
								return false;
							}
							if(date_to_raw == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Date To </b> is required.");
								return false;
							}
							if(date_to_raw < date_from_raw)
							{
								notification_msg("<?php echo ERROR ?>", "<b>Date To</b> should not be earlier than <b>Date From</b>.");
								return false;
							}		
							path                  = cert_by + '/' + office + '/' + date + '/' + null + '/' + reviewed_by + '/' + tracking_code;
						break;
						case '<?php echo REPORT_MONTHLY_SEPARATION ?>':
							var cert_by           = $('#cert_by').val();
							var reviewed_by       = $('#reviewed_by').val();
							var office            = $('#office').val();
							var date_from_raw 	  = $('#date_range_from').val();
							var date_to_raw 	  = $('#date_range_to').val();
							var date_from 		  = dateFormat(date_from_raw, 'yyyy-mm-dd');
							var date_to 		  = dateFormat(date_to_raw, 'yyyy-mm-dd');
							var date      		  = date_from + 'A' + date_to;
							var tracking_code     = $('#tracking_code').val();	
							var status   		  = $('#status').val();
							if(status == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Status </b> is required.");
								return false;
							}	
							if(cert_by == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Certified by </b> is required.");
								return false;
							}
							if(reviewed_by == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Reviewed by </b> is required.");
								return false;
							}
							if(office == '')
							{
								var office = 'A';
							}
							if(date_from_raw == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Date From </b> is required.");
								return false;
							}
							if(date_to_raw == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Date To </b> is required.");
								return false;
							}
							if(date_to_raw < date_from_raw)
							{
								notification_msg("<?php echo ERROR ?>", "<b>Date To</b> should not be earlier than <b>Date From</b>.");
								return false;
							}		
							path                  = cert_by + '/' + office + '/' + date + '/' + status + '/' + reviewed_by + '/' + tracking_code;
						break;

						case '<?php echo REPORT_FILLED_UNFILLED_POSITION ?>':
							var tracking_code   = $('#tracking_code').val();	
							var office     		= $('#office').val();	
							if(office == '')
							{
								var office = 'A';
							}						
							path 				= null + '/' + office + '/' + null + '/' + null + '/' + null + '/' + tracking_code;
						break;

						case '<?php echo REPORT_PRIME_HRM_ASSESSMENT ?>':
							var tracking_code   = $('#tracking_code').val();	
							var date_from_raw 	= $('#date_range_from').val();
							var date_to_raw 	= $('#date_range_to').val();
							var date_from 		= dateFormat(date_from_raw, 'yyyy-mm-dd');
							var date_to 		= dateFormat(date_to_raw, 'yyyy-mm-dd');
							var date      		= date_from + 'A' + date_to;
							
							if(date_from_raw == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Date From </b> is required.");
								return false;
							}
							if(date_to_raw == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Date To </b> is required.");
								return false;
							}
							if(date_to_raw < date_from_raw)
							{
								notification_msg("<?php echo ERROR ?>", "<b>Date To</b> should not be earlier than <b>Date From</b>.");
								return false;
							}			
							path = null + '/' + null + '/' + date + '/' + null + '/' + null + '/' + tracking_code;
						break;

						case '<?php echo REPORT_ENTITLEMENT_LONGEVITY_PAY ?>':
							var office            = $('#office').val();	
							var tracking_code     = $('#tracking_code').val();		
							if(office == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Office </b> is required.");
								return false;
							}						
							path = null + '/' + office + '/' + null + '/' + null + '/' + null + '/' + tracking_code;
						break;

						case '<?php echo REPORT_PSIPOP_PLANTILLA ?>':
							var office            = $('#office').val();		
							var cert_by       	  = $('#cert_by').val();
							var reviewed_by       = $('#reviewed_by').val();	
							var years              = $('#years').val();	
							var tracking_code     = $('#tracking_code').val();	
							if(office == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Office </b> is required.");
								return false;
							}	
							if(years == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Year </b> is required.");
								return false;
							}
							if(cert_by == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Certified by </b> is required.");
								return false;
							}
							if(reviewed_by == "")
							{
								notification_msg("<?php echo ERROR ?>", "<b>Reviewed by </b> is required.");
								return false;
							}
							path                  = cert_by + '/' + office + '/' + years + '/' + reviewed_by + '/' + null + '/' + tracking_code;
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
				
				window.open($base_url + 'main/reports_hr/reports_hr/generate_reports/' + format + '/' + report + '/' + path + '/?' + $('#form_reports').serialize(), '_blank');
				 
  			});
	    }

	});

	$('#reports_hr').change(function() {
		$('#employee_div').addClass('none');
		$('#salary_adjustment_div').addClass('none');
		$('#salary_adj_date_div').addClass('none');
		$('#step_incr_div').addClass('none');
		$('#employee_longevity_div').addClass('none');
		$('#office_div').addClass('none');
		$('#position_div').addClass('none');
		$('#position_title_div').addClass('none');
		$('#class_div').addClass('none');
		$('#salary_grade_div').addClass('none');
		$('#salary_grade_rai_div').addClass('none');
		$('#gender_div').addClass('none');
		$('#status_div').addClass('none');
		$('#transfer_div').addClass('none');
		$('#profession_div').addClass('none');
		$('#employment_status_div').addClass('none');
		$('#benefit_type_div').addClass('none');
		$('#birth_month_div').addClass('none');
		$('#service_length_div').addClass('none');
		$('#age_div').addClass('none');
		$('#date_range_div').addClass('none');
		$('#cert_by_div').addClass('none');
		$('#prep_by_div').addClass('none');
		$('#reviewed_by_div').addClass('none');
		$('#month_year_div').addClass('none');
		$('#year_div').addClass('none');
		$('#tracking_code_div').addClass('none');
		$('#milestone_div').addClass('none');
		$('#date_div').addClass('none');
		$('#signatories').addClass('none');
		$('#signatory_title_div').addClass('none');
		$('#tracking_code').val("");
		$('#reg_employee_div').addClass('none');
		$('#nosa_cr_employee_div').addClass('none');
		$('#probationary_div').addClass('none');
		$('#appointing_officer_div').addClass('none');
		$('#hrmo_div').addClass('none');
		$('#rai_authorized_official_div').addClass('none');
		$('#rai_hrmo_div').addClass('none');
		$('#office').attr('name', 'office');
		$('#office').prop('multiple', false);
		$('#office')[0].selectize.destroy();
		$('#office').selectize();
		$('#office')[0].selectize.clear();

		var selected = $(this).val();
		
		switch(selected)
		{
			case '<?php echo REPORT_SERVICE_RECORD ?>': 
				$('#tracking_code_div').removeClass('none');
				$('#employee_div').removeClass('none');
				$('#cert_by_div').removeClass('none');
			break;

			case '<?php echo REPORT_PERSONAL_DATA_SHEET ?>': 
				$('#employee_div').removeClass('none');
			break;
			case '<?php echo REPORT_POSITION_DESCRIPTION ?>':
				$('#tracking_code_div').removeClass('none');
				$('#employee_div').removeClass('none');
				$('#cert_by_div').removeClass('none');
				$('#reviewed_by_div').removeClass('none');
			break;
			
			case '<?php echo REPORT_APPOINTMENT_CERTIFICATE ?>':
				$('#reg_employee_div').removeClass('none');//ncocampo
				$('#probationary_div').removeClass('none');//ncocampo
				$('#signatories').removeClass('none');
				$('#signatory_title_div').removeClass('none');
				break;

			//ncocampo==============================
			case '<?php echo REPORT_ASSUMPTION_TO_DUTY ?>':
				$('#reg_employee_div').removeClass('none');//ncocampo
				$('#appointing_officer_div').removeClass('none');
				$('#hrmo_div').removeClass('none');
			break;
			//01/11/2024================================

			// case '<?php echo REPORT_RAI_PART2 ?>': 
			// 	$('#tracking_code_div').removeClass('none');
			// 	$('#office_div').removeClass('none');
			// 	$('#date_range_div').removeClass('none');
			// 	$('#cert_by_div').removeClass('none');
			// 	$('#prep_by_div').removeClass('none');
			// 	$('#reviewed_by_div').removeClass('none');
			// break;

			case '<?php echo REPORT_RAI_PART2 ?>': 
				$('#tracking_code_div').removeClass('none');
				$('#reg_employee_div').removeClass('none');//02/12/2024
				// $('#cert_by_div').removeClass('none');
				$('#reviewed_by_div').removeClass('none');

			break;

			case '<?php echo REPORT_RAI_PART1 ?>': 
				$('#tracking_code_div').removeClass('none');
				$('#office_div').removeClass('none');
				$('#date_range_div').removeClass('none');
				$('#rai_authorized_official_div').removeClass('none');
				$('#rai_hrmo_div').removeClass('none');
				$('#salary_grade_rai_div').removeClass('none');
				$('#office').attr('name', 'office[]');
				$('#office').prop('multiple', true);
				$('#office')[0].selectize.destroy();
				$('#office').selectize();
				$('#office')[0].selectize.clear();
			break;

			case '<?php echo REPORT_PERSONNEL_MOVEMENT ?>': 
				$('#tracking_code_div').removeClass('none');
				$('#office_div').removeClass('none');
				$('#date_range_div').removeClass('none');
				$('#cert_by_div').removeClass('none');
				$('#prep_by_div').removeClass('none');
			break;

			case '<?php echo REPORT_OFFICE ?>': 
				$('#tracking_code_div').removeClass('none');
				$('#office_div').removeClass('none');
				$('#date_range_div').removeClass('none'); 
				$('#status_div').removeClass('none');
			break;

			case '<?php echo REPORT_POSITION_LEVEL ?>': 
				$('#tracking_code_div').removeClass('none');
				$('#office_div').removeClass('none');
				$('#date_range_div').removeClass('none');
				$('#position_div').removeClass('none');
				$('#status_div').removeClass('none');
			break;

			case '<?php echo REPORT_POSITION_TITLE ?>': 
				$('#tracking_code_div').removeClass('none');
				$('#status_div').removeClass('none');
				$('#office_div').removeClass('none');
				$('#date_range_div').removeClass('none');
				$('#position_title_div').removeClass('none');
				$('#position_title')[0].selectize.destroy();
				var result = '<option value="">Select Position Title</option>';
				var positions = <?php echo json_encode($positions) ?>;

				for(var i=0 ; i < positions.length; i++)
				{
						
					result += '<option value="' + positions[i]['position_id'] + '">' + positions[i]['position_name'] + '</option>';
						
				}
				$('#position_title').html(result).selectize();
			break;

			case '<?php echo REPORT_CLASS ?>': 
				$('#tracking_code_div').removeClass('none');
				$('#office_div').removeClass('none');
				$('#date_range_div').removeClass('none');
				$('#class_div').removeClass('none');
				$('#status_div').removeClass('none');
			break;

			case '<?php echo REPORT_SALARY_GRADE ?>': 
				$('#tracking_code_div').removeClass('none');
				$('#office_div').removeClass('none');
				$('#date_range_div').removeClass('none');
				$('#salary_grade_div').removeClass('none');
				$('#status_div').removeClass('none');
			break;

			case '<?php echo REPORT_BIRTH_DATE ?>': 
				$('#tracking_code_div').removeClass('none');
				$('#office_div').removeClass('none');
				$('#date_range_div').removeClass('none');
				$('#birth_month_div').removeClass('none');
				$('#status_div').removeClass('none');
			break;

			case '<?php echo REPORT_AGE ?>': 
				$('#tracking_code_div').removeClass('none');
				$('#office_div').removeClass('none');
				$('#date_range_div').removeClass('none');
				$('#age_div').removeClass('none');
				$('#status_div').removeClass('none');
			break;

			case '<?php echo REPORT_GENDER ?>': 
				$('#tracking_code_div').removeClass('none');
				$('#office_div').removeClass('none');
				$('#date_range_div').removeClass('none');
				$('#gender_div').removeClass('none');
				$('#status_div').removeClass('none');
			break;

			case '<?php echo REPORT_PROFESSION ?>': 
				$('#tracking_code_div').removeClass('none');
				$('#office_div').removeClass('none');
				$('#date_range_div').removeClass('none');
				$('#profession_div').removeClass('none');
				$('#status_div').removeClass('none');
			break;

			case '<?php echo REPORT_EMPLOYMENT_STATUS ?>': 
				$('#tracking_code_div').removeClass('none');
				$('#office_div').removeClass('none');
				$('#date_range_div').removeClass('none');
				$('#employment_status_div').removeClass('none');
			break;

			case '<?php echo REPORT_BENEFIT_ENTITLEMENT ?>': 
				$('#tracking_code_div').removeClass('none');
				$('#office_div').removeClass('none');
				$('#date_range_div').removeClass('none');
				$('#benefit_type_div').removeClass('none');
				$('#status_div').removeClass('none');
			break;

			case '<?php echo REPORT_SERVICE_LENGTH ?>': 
				$('#tracking_code_div').removeClass('none');
				$('#office_div').removeClass('none');
				$('#date_div').removeClass('none');
				$('#service_length_div').removeClass('none');
				$('#status_div').removeClass('none');
			break;

			case '<?php echo REPORT_RETIREES ?>': 
				$('#tracking_code_div').removeClass('none');
				$('#office_div').removeClass('none');
				$('#date_range_div').removeClass('none');
			break;

			case '<?php echo REPORT_FILLED_UNFILLED_POSITION ?>': 
				$('#tracking_code_div').removeClass('none');
				$('#office_div').removeClass('none');
			break;

			case '<?php echo REPORT_RESIGNED_EMPLOYEES ?>': 
				$('#tracking_code_div').removeClass('none');
				$('#office_div').removeClass('none');				
				$('#date_range_div').removeClass('none');
			break;

			case '<?php echo REPORT_DROPPED_EMPLOYEES ?>': 
				$('#tracking_code_div').removeClass('none');
				$('#office_div').removeClass('none');
				$('#date_range_div').removeClass('none');
			break;

			case '<?php echo REPORT_PROMOTED_EMPLOYEES ?>': 
				$('#tracking_code_div').removeClass('none');
				$('#office_div').removeClass('none');				
				$('#date_range_div').removeClass('none');
			break;

			case '<?php echo REPORT_ENTITLEMENT_LONGEVITY_PAY ?>': 
				$('#tracking_code_div').removeClass('none');
				$('#office_div').removeClass('none');		
			break;

			case '<?php echo REPORT_PRIME_HRM_ASSESSMENT ?>': 
				$('#tracking_code_div').removeClass('none');
				$('#date_range_div').removeClass('none');
			break;

			case '<?php echo REPORT_TRANSFEREES ?>': 
				$('#office_div').removeClass('none');
				$('#date_range_div').removeClass('none');
				$('#tracking_code_div').removeClass('none');
				$('#transfer_div').removeClass('none');
			break;

			case '<?php echo REPORT_NOTICE_SALARY_ADJUSTMENT ?>': 
				$('#tracking_code_div').removeClass('none');
				$('#cert_by_div').removeClass('none');				
				$('#office_div').removeClass('none');		
				$('#salary_adjustment_div').removeClass('none');

			  	$('#salary_adj_date_div').removeClass('none');
				$('#salary_adj_date')[0].selectize.destroy();
				var result = '<option value="">Select Effectivity Date</option>';
				var dates = <?php echo json_encode($salary_adj_dates) ?>;

				for(var i=0 ; i < dates.length; i++)
				{
						
					result += '<option value="' + dates[i]['effectivity_date'] + '">' + dates[i]['salary_adjustment_date'] + '</option>';
						
				}
				$('#salary_adj_date').html(result).selectize();

			break;
				//ncocampo 05/06/2024
			case '<?php echo REPORT_NOTICE_SALARY_ADJUSTMENT_COMPULSORY_RETIREMENT ?>': 
				$('#tracking_code_div').removeClass('none');
				$('#cert_by_div').removeClass('none');
				$('#nosa_cr_employee_div').removeClass('none');
			break;
				//ncocampo 05/06/2024


			case '<?php echo REPORT_NOTICE_SALARY_STEP_INCREMENT ?>': 
				$('#tracking_code_div').removeClass('none');
				$('#cert_by_div').removeClass('none');

				$('#step_incr_div').removeClass('none');
				$('#step_incr')[0].selectize.destroy();
				var result = '<option value="">Select Employee</option>';
				var employees = <?php echo json_encode($step_incr) ?>;

				for(var i=0 ; i < employees.length; i++)
				{
						
					result += '<option value="' + employees[i]['employee_id'] + '">' + employees[i]['employee_name'] + '</option>';
						
				}
				$('#step_incr').html(result).selectize();
			break;

			case '<?php echo REPORT_NOTICE_STEP_INCREMENT ?>': 
				$('#tracking_code_div').removeClass('none');
				$('#cert_by_div').removeClass('none');

				$('#step_incr_div').removeClass('none');
				$('#step_incr')[0].selectize.destroy();
				var result = '<option value="">Select Employee</option>';
				var employees = <?php echo json_encode($step_incr) ?>;

				for(var i=0 ; i < employees.length; i++)
				{
						
					result += '<option value="' + employees[i]['employee_id'] + '">' + employees[i]['employee_name'] + '</option>';
						
				}
				$('#step_incr').html(result).selectize();
			break;

			case '<?php echo REPORT_NOTICE_LONGEVITY_PAY; ?>': 
				$('#tracking_code_div').removeClass('none');
				$('#employee_longevity_div').removeClass('none');
				$('#cert_by_div').removeClass('none');
			break;

			case '<?php echo REPORT_NOTICE_LONGEVITY_PAY_INCREASE; ?>': 
				$('#tracking_code_div').removeClass('none');
				$('#employee_longevity_div').removeClass('none');
				$('#milestone_div').removeClass('none');
				$('#cert_by_div').removeClass('none');
			break;
			
			case '<?php echo REPORT_PSIPOP_PLANTILLA; ?>': 
				$('#tracking_code_div').removeClass('none');
				$('#office_div').removeClass('none');
				$('#year_div').removeClass('none');
				$('#cert_by_div').removeClass('none');
				$('#reviewed_by_div').removeClass('none');
			break;

			case '<?php echo REPORT_MONTHLY_ACCESSION; ?>': 
				$('#tracking_code_div').removeClass('none');
				$('#office_div').removeClass('none');
				$('#date_range_div').removeClass('none');
				$('#cert_by_div').removeClass('none');
				$('#reviewed_by_div').removeClass('none');
			break;

			case '<?php echo REPORT_MONTHLY_SEPARATION; ?>': 
				$('#status_div').removeClass('none');
				$('#tracking_code_div').removeClass('none');
				$('#office_div').removeClass('none');
				$('#date_range_div').removeClass('none');
				$('#cert_by_div').removeClass('none');
				$('#reviewed_by_div').removeClass('none');
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
						<?php if($this->permission->check_permission(MODULE_HR_REPORT_LNE_AGE)) :?>
							'value' : '<?php echo REPORT_AGE?>', 
							'text' : 'LIST AND NUMBER OF EMPLOYEES BY AGE'
						<?php endif; ?>
					},
					{
						<?php if($this->permission->check_permission(MODULE_HR_REPORT_LNE_BENEFIT_ENTITLEMENT)) :?>
							'value' : '<?php echo REPORT_BENEFIT_ENTITLEMENT?>', // DONE
							'text' : 'LIST AND NUMBER OF EMPLOYEES BY BENEFIT ENTITLEMENT'
						<?php endif; ?>
					},
					{
						<?php if($this->permission->check_permission(MODULE_HR_REPORT_LNE_BIRTH_DATES)) :?>
							'value' : '<?php echo REPORT_BIRTH_DATE?>', // DONE
							'text' : 'LIST AND NUMBER OF EMPLOYEES BY BIRTH MONTH'
						<?php endif; ?>
					},
					{
						<?php if($this->permission->check_permission(MODULE_HR_REPORT_LNE_CLASS)) :?>
							'value' : '<?php echo REPORT_CLASS?>', // DONE
							'text' : 'LIST AND NUMBER OF EMPLOYEES BY CLASS'
						<?php endif; ?>
					},
					{
						<?php if($this->permission->check_permission(MODULE_HR_REPORT_LNE_EMPLOYMENT_STATUS)) :?>
							'value' : '<?php echo REPORT_EMPLOYMENT_STATUS?>', // DONE
							'text' : 'LIST AND NUMBER OF EMPLOYEES BY EMPLOYMENT STATUS (PERMANENT, TEMPORARY, CONTRACTUAL AND CO-TERMINUS, ETC.)'
						<?php endif; ?>
					},
					{
						<?php if($this->permission->check_permission(MODULE_HR_REPORT_LNE_GENDER)) :?>
							'value' : '<?php echo REPORT_GENDER?>', // DONE
							'text' : 'LIST AND NUMBER OF EMPLOYEES BY GENDER'
						<?php endif; ?>
					},
					{
						<?php if($this->permission->check_permission(MODULE_HR_REPORT_LNE_LENGTH_SERVICE)) :?>
							'value' : '<?php echo REPORT_SERVICE_LENGTH?>', // DONE
							'text' : 'LIST AND NUMBER OF EMPLOYEES BY LENGTH OF SERVICE'
						<?php endif; ?>
					},
					{
						<?php if($this->permission->check_permission(MODULE_HR_REPORT_LNE_OFFICE)) :?>
							'value' : '<?php echo REPORT_OFFICE?>',  // DONE
							'text' : 'LIST AND NUMBER OF EMPLOYEES BY OFFICE'
						<?php endif; ?>
					},
					{
						<?php if($this->permission->check_permission(MODULE_HR_REPORT_LNE_POSITION_LEVEL)) :?>
							'value' : '<?php echo REPORT_POSITION_LEVEL?>', // DONE
							'text' : 'LIST AND NUMBER OF EMPLOYEES BY POSITION LEVEL'
						<?php endif; ?>
					},
					{
						<?php if($this->permission->check_permission(MODULE_HR_REPORT_LNE_POSITION_TITLE)) :?>
							'value' : '<?php echo REPORT_POSITION_TITLE?>', // DONE
							'text' : 'LIST AND NUMBER OF EMPLOYEES BY POSITION TITLE'
						<?php endif; ?>
					},
					{
						<?php if($this->permission->check_permission(MODULE_HR_REPORT_LNE_PROFESSION)) :?>
							'value' : '<?php echo REPORT_PROFESSION?>', // DONE
							'text' : 'LIST AND NUMBER OF EMPLOYEES BY PROFESSION'
						<?php endif; ?>
					},
					{
						<?php if($this->permission->check_permission(MODULE_HR_REPORT_LNE_SALARY_GRADE)) :?>
							'value' : '<?php echo REPORT_SALARY_GRADE?>', // DONE
							'text' : 'LIST AND NUMBER OF EMPLOYEES BY SALARY GRADE'
						<?php endif; ?>
					},
					{
						<?php if($this->permission->check_permission(MODULE_HR_REPORT_LNE_DROPPED_FROM_ROLL)) :?>
							'value' : '<?php echo REPORT_DROPPED_EMPLOYEES ?>', // DONE
							'text' : 'NUMBER OF EMPLOYEES DROPPED FROM THE ROLL'
						<?php endif; ?>
					},
					{
						<?php if($this->permission->check_permission(MODULE_HR_REPORT_LNE_PROMOTED)) :?>
							'value' : '<?php echo REPORT_PROMOTED_EMPLOYEES ?>', // DONE
							'text' : 'NUMBER OF PROMOTED EMPLOYEES'
						<?php endif; ?>
					},
					{
						<?php if($this->permission->check_permission(MODULE_HR_REPORT_LNE_RESIGNED)) :?>
							'value' : '<?php echo REPORT_RESIGNED_EMPLOYEES ?>', // DONE
							'text' : 'NUMBER OF RESIGNED EMPLOYEES' 
						<?php endif; ?>
					},
					{
						<?php if($this->permission->check_permission(MODULE_HR_REPORT_LNE_RETIRED)) :?>
							'value' : '<?php echo REPORT_RETIREES ?>', // DONE
							'text' : 'NUMBER OF RETIREES'
						<?php endif; ?>
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
						<?php if($this->permission->check_permission(MODULE_HR_REPORT_SERVICE_RECORD)) :?>
							'value' : '<?php echo REPORT_SERVICE_RECORD; ?>', // DONE
							'text' : 'SERVICE RECORD'
						<?php endif; ?>
					},
					{
						<?php if($this->permission->check_permission(MODULE_HR_REPORT_APPOINTMENT_CERTIFICATE)) :?>
							'value' : '<?php echo REPORT_APPOINTMENT_CERTIFICATE ?>', //DONE
							'text' : 'APPOINTMENT (KSS PORMA BLG. 33)'
						<?php endif; ?>
					},
					//ncocampo
					{
						<?php if($this->permission->check_permission(MODULE_HR_REPORT_ASSUMPTION_TO_DUTY)) :?>
							'value' : '<?php echo REPORT_ASSUMPTION_TO_DUTY ?>', //DONE
							'text' : 'CERTIFICATE OF ASSUMPTION TO DUTY AND OATH OF OFFICE'
						<?php endif; ?>
					},
					//01/11/2024
					{
						<?php if($this->permission->check_permission(MODULE_HR_REPORT_MR_ACCESSION)) :?>
							'value' : '<?php echo REPORT_MONTHLY_ACCESSION ?>', // NO QUERY BUT HAVE TEMPLATE
							'text' : 'MONTHLY REPORT ON ACCESSION'
						<?php endif; ?>
					},
					{
						<?php if($this->permission->check_permission(MODULE_HR_REPORT_MR_SEPARATION)) :?>
							'value' : '<?php echo REPORT_MONTHLY_SEPARATION ?>', // NO QUERY BUT HAVE TEMPLATE
							'text' : 'MONTHLY REPORT ON SEPARATION'
						<?php endif; ?>
					}, 
					{
						<?php if($this->permission->check_permission(MODULE_HR_REPORT_FILLED_UNFILLED_POSITIONS)) :?>
							'value' : '<?php echo REPORT_FILLED_UNFILLED_POSITION ?>',// DONE
							'text' : 'REPORT ON FILLED AND UNFILLED POSITIONS'
						<?php endif; ?>
					},
					{
						<?php if($this->permission->check_permission(MODULE_HR_REPORT_PSIPOP)) :?>
							'value' : '<?php echo REPORT_PSIPOP_PLANTILLA?>', // DONE
							'text' : 'PERSONAL SERVICES ITEMIZATION AND PLANTILLA OF PERSONNEL (PSIPOP)'
						<?php endif; ?>
					},
					{
						<?php if($this->permission->check_permission(MODULE_HR_REPORT_PERSONNEL_MOVEMENT)) :?>
							'value' : '<?php echo REPORT_PERSONNEL_MOVEMENT?>', // DONE
							'text' : 'PERSONNEL MOVEMENT (PART I and PART II)'
						<?php endif; ?>
					},
					{
						<?php if($this->permission->check_permission(MODULE_HR_REPORT_NDHRHIS_UPLOAD)) :?>
							'value' : '<?php echo REPORT_NDHRHIS_FILE ?>', // NO QUERY NO TEMPLATE
							'text' : 'NDHRHIS FILE FOR UPLOADING'
						<?php endif; ?>
					},
					{
							'value' : '<?php echo REPORT_PERSONAL_DATA_SHEET ?>', // DONE
							'text' : 'PERSONAL DATA SHEET'
					},
					{
						<?php if($this->permission->check_permission(MODULE_HR_REPORT_RAI_I)) :?>
							'value' : '<?php echo REPORT_RAI_PART1 ?>', // DONE
							'text' : 'REPORT ON APPOINTMENT ISSUED PART I'
						<?php endif; ?>
					},
					{
						<?php if($this->permission->check_permission(MODULE_HR_REPORT_RAI_II)) :?>
							'value' : '<?php echo REPORT_RAI_PART2 ?>', // DONE
							'text' : 'REPORT ON APPOINTMENT ISSUED PART II'
						<?php endif; ?>
					},
					{
						<?php if($this->permission->check_permission(MODULE_HR_REPORT_TRANSFEREE)) :?>
							'value' : '<?php echo REPORT_TRANSFEREES ?>',// DONE
							'text' : 'TRANSFEREE/S IN AND OUT'
						<?php endif; ?>
					},
					{
						<?php if($this->permission->check_permission(MODULE_HR_REPORT_PRIME_HRM)) :?>
							'value' : '<?php echo REPORT_PRIME_HRM_ASSESSMENT ?>', // NO QUERY BUT HAVE TEMPLATE
							'text' : 'PRIME-HRM ASSESSMENT REPORT (AGENCY PROFILE)'
						<?php endif; ?>
					},
					{
						<?php //if($this->permission->check_permission(REPORT_POSITION_DESCRIPTION)) :?>
							'value' : '<?php echo REPORT_POSITION_DESCRIPTION ?>', // NO QUERY BUT HAVE TEMPLATE
							'text' : 'POSITION DESCRIPTION'
						<?php //endif; ?>
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
						<?php if($this->permission->check_permission(MODULE_HR_REPORT_ENTITLEMENT_LONGEVITY_PAY)) :?>
							'value' : '<?php echo REPORT_ENTITLEMENT_LONGEVITY_PAY ?>',//DONE
							'text' : 'ENTITLEMENT OF LONGEVITY PAY'
						<?php endif; ?>
					},
					{
						<?php if($this->permission->check_permission(MODULE_HR_REPORT_NOTICE_SALARY_ADJUSTMENT)) :?>
							'value' : '<?php echo REPORT_NOTICE_SALARY_ADJUSTMENT ?>',//DONE
							'text' : 'NOTICE OF SALARY ADJUSTMENT'
						<?php endif; ?>
					},	
					//ncocampo 5/06/2024
					{
						<?php if($this->permission->check_permission(MODULE_HR_REPORT_NOTICE_SALARY_ADJUSTMENT_COMPULSORY_RETIREMENT)) :?>
							'value' : '<?php echo REPORT_NOTICE_SALARY_ADJUSTMENT_COMPULSORY_RETIREMENT ?>',//DONE
							'text' : 'NOTICE OF SALARY ADJUSTMENT (COMPULSORY RETIREMENT)'
						<?php endif; ?>
					},
					//ncocampo 5/06/2024	
					{
						<?php if($this->permission->check_permission(MODULE_HR_REPORT_NOTICE_SALARY_STEP_INCREMENT)) :?>
							'value' : '<?php echo REPORT_NOTICE_SALARY_STEP_INCREMENT ?>',//DONE
							//'text' : 'NOTICE OF SALARY STEP INCREMENT'
							'text' : 'NOTICE OF SALARY STEP INCREMENT (MASTERAL)' //jendaigo: modify dropdown label
						<?php endif; ?>
					},		
					{
						<?php if($this->permission->check_permission(MODULE_HR_REPORT_NOTICE_STEP_INCREMENT)) :?>
							'value' : '<?php echo REPORT_NOTICE_STEP_INCREMENT ?>',//DONE
							//'text' : 'NOTICE OF STEP INCREMENT'
							'text' : 'NOTICE OF STEP INCREMENT (LENGTH OF SERVICE)'//jendaigo: modify dropdown label
						<?php endif; ?>
					},			
					{ 
						<?php if($this->permission->check_permission(MODULE_HR_REPORT_NOTICE_LONGEVITY_PAY)) :?>
							'value' : '<?php echo REPORT_NOTICE_LONGEVITY_PAY ?>',//DONE
							'text' : 'NOTICE OF LONGEVITY PAY'
						<?php endif; ?>
					},			
					{
						<?php if($this->permission->check_permission(MODULE_HR_REPORT_NOTICE_LONGEVITY_PAY_INCREASE)) :?>
							'value' : '<?php echo REPORT_NOTICE_LONGEVITY_PAY_INCREASE ?>',//DONE
							'text' : 'NOTICE OF LONGEVITY PAY INCREASE'
						<?php endif; ?>
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

	//GET THE MILESTONE - AJAX
	$('#employee_longevity').on( "change", function() {
		var employee_longevity  = $(this).val();

		var milestone 	= $("#milestone")[0].selectize;
		var data        = {'employee_longevity':employee_longevity};
		milestone.clear();
		milestone.clearOptions();

		if(employee_longevity != '')
		{
			$.post($base_url + 'main/reports_hr/notice_longevity_pay_increase/get_milestone/', data, function(result)
			{
			  	if(result.flag == 1){
					milestone.load(function(callback) {
						callback(result.list);
					});
			 	}				  
			}, 'json');
		}
  	});

	//GET EMPLOYEES WITH SALARY ADJUSTMENT - AJAX
	$('#office').on( "change", function() {
		var office  = $(this).val();

		var salary_adjusted 	= $("#salary_adjusted")[0].selectize;
		var data        		= {id:office};

		salary_adjusted.clear();
		salary_adjusted.clearOptions();

		if(office != '')
		{
			$.post($base_url + 'main/reports_hr/notice_of_salary_adjustment/get_employees/', data, function(result)
			{
			  	if(result.flag == 1){
					salary_adjusted.load(function(callback) {
						callback(result.list);
					});
			 	}				  
			}, 'json');
		}
  	});
});
</script>
