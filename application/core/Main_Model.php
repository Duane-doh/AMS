<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Main_Model extends Base_Model {
	
	
	protected static $dsn                             = DB_MAIN;
	
	const MODULE                                      = PROJECT_MAIN;
	
	//FOR DB MAIN
	public $DB_MAIN                                   = DB_MAIN;
	//FOR DB CORE
	public $DB_CORE                                   = DB_CORE;
	
	public $tbl_associated_accounts                   = 'associated_accounts';
	
	public $tbl_cut_off_references                    = 'cut_off_references';
	
	public $tbl_dtr_upload_file_authorized_roles      = 'dtr_upload_file_authorized_roles';
	public $tbl_dtr_upload_file_dtl                   = 'dtr_upload_file_dtl';
	public $tbl_dtr_upload_file_hdr                   = 'dtr_upload_file_hdr';
	public $tbl_dtr_upload_file_sub_hdr               = 'dtr_upload_file_sub_hdr';
	public $tbl_deduction_statutory                   = 'deduction_statutory';
	
	public $tbl_employee_addresses                    = 'employee_addresses';
	public $tbl_employee_adjustments                  = 'employee_adjustments';
	public $tbl_employee_attendance                   = 'employee_attendance';
	public $tbl_employee_compensation_schedule        = 'employee_compensation_schedule';
	public $tbl_employee_compensations                = 'employee_compensations';
	public $tbl_employee_compensation_details         = 'employee_compensation_details'; //jendaigo : include compensation details
	public $tbl_employee_contacts                     = 'employee_contacts';
	public $tbl_employee_declaration                  = 'employee_declaration';
	public $tbl_employee_deductions                   = 'employee_deductions';
	public $tbl_employee_deduction_details            = 'employee_deduction_details';
	public $tbl_employee_deduction_other_details      = 'employee_deduction_other_details';
	public $tbl_employee_deduction_detail_details     = 'employee_deduction_detail_details'; //jendaigo : include deduction detail details
	public $tbl_employee_deduction_paid_count_details = 'employee_deduction_paid_count_details'; //jendaigo : include deduction paid count details
	public $tbl_employee_dependents                   = 'employee_dependents';
	public $tbl_employee_educations                   = 'employee_educations';
	public $tbl_employee_eligibility                  = 'employee_eligibility';
	public $tbl_employee_identifications              = 'employee_identifications';
	public $tbl_employee_identification_details       = 'employee_identification_details'; //jendaigo : include deduction detail details
	public $tbl_employee_responsibility_codes         = 'employee_responsibility_codes'; //jendaigo : include employee responsibility code
	public $tbl_employee_leave_balances               = 'employee_leave_balances';
	public $tbl_employee_leave_details                = 'employee_leave_details';
	public $tbl_employee_returned_leaves              = 'employee_returned_leaves'; // davcorrea : include returned leaves breakdown
	public $tbl_employee_offices                      = 'employee_offices';
	public $tbl_employee_other_info                   = 'employee_other_info';
	public $tbl_employee_pds_checklist                = "employee_pds_checklist";
	
	
	public $tbl_employee_personal_info                = 'employee_personal_info';
	public $tbl_employee_questions                    = 'employee_questions';
	public $tbl_employee_references                   = 'employee_references';
	public $tbl_employee_relations                    = 'employee_relations';
	public $tbl_employee_service_record               = 'employee_service_record';
	public $tbl_employee_trainings                    = 'employee_trainings';
	public $tbl_employee_voluntary_works              = 'employee_voluntary_works';
	public $tbl_employee_work_experiences             = 'employee_work_experiences';
	public $tbl_employee_work_experience_details      = 'employee_work_experience_details';
	public $tbl_employee_work_schedules               = 'employee_work_schedules';
	public $tbl_employee_professions           		  = 'employee_professions';
	public $tbl_param_employment_status               = 'param_employment_status';
	public $tbl_employee_supporting_docs              = 'employee_supporting_docs';
	
	public $tbl_employee_performance_evaluations      = 'employee_performance_evaluations';
	public $tbl_employee_position_description      	= 'employee_position_description';
	
	public $tbl_gsis_loans_dtl                        = 'gsis_loans_dtl';
	public $tbl_gsis_loans_hdr                        = 'gsis_loans_hdr';
	public $tbl_leave_monthly_credits                 = 'leave_monthly_credits';
	
	public $tbl_param_academic_honors                 = 'param_academic_honors';
	public $tbl_param_address_region                  = "param_address_region";
	public $tbl_param_address_municipality            = "param_address_municipality";
	public $tbl_param_address_province                = "param_address_province";
	public $tbl_param_address_types                   = 'param_address_types';
	public $tbl_param_agency_numbers                  = 'param_agency_numbers';
	public $tbl_param_adjustment                      = 'param_adjustment';
	public $tbl_param_appointment_status              = 'param_appointment_status';
	public $tbl_param_appointment_type                = 'param_appointment_type';
	public $tbl_param_attendance_status               = 'param_attendance_status';
	public $tbl_param_banks                           = 'param_banks';
	public $tbl_param_bir                             = 'param_bir';
	public $tbl_param_bir_details                     = 'param_bir_details';
	public $tbl_param_compensations                   = 'param_compensations';
	public $tbl_param_compensation_detail_types		  = 'param_compensation_detail_types'; //jendaigo : include compensation detail types
	public $tbl_param_blood_type                      = 'param_blood_type';
	public $tbl_param_checklists                      = 'param_checklists';
	public $tbl_param_checklist_docs                  = 'param_checklist_docs';
	public $tbl_param_cities                          = 'param_cities';
	public $tbl_param_citizenships                    = 'param_citizenships';
	public $tbl_param_civil_status                    = 'param_civil_status';
	public $tbl_param_cluster                         = 'param_cluster';
	public $tbl_param_computation_table        		  = 'param_computation_table'; //davcorrea : include parameter computation table days
	public $tbl_param_computation_table_detail        = 'param_computation_table_detail'; //davcorrea : include parameter computation table days
	public $tbl_param_computation_table_type		  = 'param_computation_table_type'; //davcorrea : include parameter computation table days
	public $tbl_param_contact_types                   = 'param_contact_types';
	public $tbl_param_cut_off                         = 'param_cut_off';
	public $tbl_param_deadline_schedule               = 'param_deadline_schedule';
	public $tbl_param_deductions                      = 'param_deductions';
	public $tbl_param_deduction_type                  = 'param_deduction_type';
	public $tbl_param_deduction_detail_types          = 'param_deduction_detail_types'; //jendaigo : include parameter deduct detail types
	
	public $tbl_param_dependent_relationship          = 'param_dependent_relationship';
	public $tbl_param_designation                     = 'param_designations';
	public $tbl_param_dtr_actions                     = 'param_dtr_actions';
	public $tbl_param_education_degrees               = 'param_education_degrees';
	public $tbl_param_educational_levels              = 'param_educational_levels';
	public $tbl_param_eligibility_types               = 'param_eligibility_types';
	public $tbl_param_eligibility_levels              = 'param_eligibility_levels'; //ncocampo : include parameter eligibility levels
	public $tbl_param_employment_types                = 'param_employment_types';
	public $tbl_param_examination                     = 'param_examination';
	public $tbl_param_frequencies                     = 'param_frequencies';
	public $tbl_param_function                        = 'param_function';
	public $tbl_param_fund_sources                    = 'param_fund_sources';
	public $tbl_param_gender                          = 'param_gender';
	public $tbl_param_government_branches             = 'param_government_branches';
	public $tbl_param_gsis                            = 'param_gsis';
	public $tbl_param_gsis_details                    = 'param_gsis_details';
	public $tbl_param_gsis_loan_type                  = 'param_gsis_loan_type';
	public $tbl_param_hdmf_type                       = 'param_hdmf_type';
	public $tbl_param_holiday_types                   = 'param_holiday_types';
	
	public $tbl_param_identification_types            = 'param_identification_types';
	public $tbl_param_insurance_coverage_type         = 'param_insurance_coverage_type';
	public $tbl_param_leave_transaction_types         = 'param_leave_transaction_types';
	public $tbl_param_leave_types                     = 'param_leave_types';
	public $tbl_param_legend_type                     = 'param_legend_type';
	public $tbl_param_multipliers                     = 'param_multipliers';
	public $tbl_param_nature_appointment              = 'param_nature_appointment';
	public $tbl_param_nature_employment               = 'param_nature_employment';
	public $tbl_param_occupational_header             = 'param_occupational_header';
	public $tbl_param_offices                         = 'param_offices';
	public $tbl_param_other_deduction_details         = 'param_other_deduction_details';
	public $tbl_param_other_info_types                = 'param_other_info_types';
	public $tbl_param_pagibig                         = 'param_pagibig';
	public $tbl_param_report_paper_size               = 'param_report_paper_size';
	public $tbl_param_pay_step                        = 'param_pay_step';
	public $tbl_param_pds_status                      = 'param_pds_status';
	public $tbl_param_perf_eval_classification_fields = 'param_perf_eval_classification_fields';
	public $tbl_param_performance_rating              = 'param_performance_rating';
	public $tbl_param_personnel_movements             = 'param_personnel_movements';
	public $tbl_param_philhealth                      = 'param_philhealth';
	public $tbl_param_plantilla_items                 = 'param_plantilla_items';
	public $tbl_param_plantilla_levels                = 'param_plantilla_levels'; //ncocampo : include parameter plantilla levels
	public $tbl_param_position_class_levels           = 'param_position_class_levels';
	public $tbl_param_position_levels                 = 'param_position_levels';
	public $tbl_param_positions                       = 'param_positions';
	public $tbl_param_position_duties	              = 'param_position_duties';
	public $tbl_param_professions                     = 'param_professions';
	public $tbl_param_provinces                       = 'param_provinces';
	public $tbl_param_questions                       = 'param_questions';
	public $tbl_param_regions                         = 'param_regions';
	public $tbl_param_relation_types                  = 'param_relation_types';
	public $tbl_param_remittance_payees               = 'param_remittance_payees';
	public $tbl_param_remittance_types                = 'param_remittance_types';
	public $tbl_param_replacement_reason              = 'param_replacement_reason';
	public $tbl_param_request_certification_types     = 'param_request_certification_types';
	public $tbl_param_request_sub_status              = 'param_request_sub_status';
	public $tbl_param_request_sub_types               = 'param_request_sub_types';
	public $tbl_param_request_status                  = 'param_request_status';
	public $tbl_param_request_types                   = 'param_request_types';
	public $tbl_param_salary                          = 'param_salary';
	public $tbl_param_salary_grade                    = 'param_salary_grade';
	public $tbl_param_salary_schedule                 = 'param_salary_schedule';
	public $tbl_param_schools                         = 'param_schools';
	public $tbl_param_separation_modes                = 'param_separation_modes';
	public $tbl_param_service_legend                  = 'param_service_legend';
	public $tbl_param_dropdown                        = 'param_dropdown';
	public $tbl_param_dropdown_conditions             = 'param_dropdown_conditions';
	
	public $tbl_param_supporting_document_types       = 'param_supporting_document_types';
	public $tbl_param_status                          = 'param_status';
	public $tbl_param_relation_employment_status      = "param_relation_employment_status";
	public $tbl_param_leave_intervals                 = "param_leave_intervals";
	
	public $tbl_param_task_status                     = 'param_task_status';
	public $tbl_param_trainings                       = 'param_trainings';
	public $tbl_param_voucher                         = 'param_voucher';
	public $tbl_param_work_calendar                   = 'param_work_calendar';
	public $tbl_param_work_schedules                  = 'param_work_schedules';
	public $tbl_param_report_signatories              = 'param_report_signatories';
	
	public $tbl_payout_header                         = 'payout_header';
	public $tbl_payout_summary                        = 'payout_summary';
	public $tbl_payout_details                        = 'payout_details';
	public $tbl_payout_summary_dates                  = 'payout_summary_dates';
	public $tbl_payout_history						  = 'payout_history';
	public $tbl_param_voucher_status                  = 'param_voucher_status';
	public $tbl_payout_employee                       = 'payout_employee';
	
	public $tbl_philhealth_premium_contribution_table = 'philhealth_premium_contribution_table';

	public $tbl_remittances 						  = 'remittances';
	public $tbl_remittance_details					  = 'remittance_details';
	public $tbl_remittance_history					  = 'remittance_history';
	public $tbl_remittance_upload					  = 'remittance_upload';
	public $tbl_param_remittance_type_deductions      = 'param_remittance_type_deductions';
	public $tbl_param_remittance_status               = 'param_remittance_status';
	public $tbl_param_remittance_files                = 'Param_remittance_files';

	public $tbl_requests                              = 'requests';
	public $tbl_requests_employee_addresses           = 'requests_employee_addresses';
	public $tbl_requests_employee_contacts            = 'requests_employee_contacts';
	public $tbl_requests_employee_declaration         = 'requests_employee_declaration';
	public $tbl_requests_employee_educations          = 'requests_employee_educations';
	public $tbl_requests_employee_eligibility         = 'requests_employee_eligibility';
	public $tbl_requests_employee_identifications     = 'requests_employee_identifications';
	public $tbl_requests_employee_other_info          = 'requests_employee_other_info';
	public $tbl_requests_employee_personal_info       = 'requests_employee_personal_info';
	public $tbl_requests_employee_references          = 'requests_employee_references';
	public $tbl_requests_employee_relations           = 'requests_employee_relations';
	public $tbl_requests_employee_trainings           = 'requests_employee_trainings';
	public $tbl_requests_employee_voluntary_works     = 'requests_employee_voluntary_works';
	public $tbl_requests_employee_work_experiences    = 'requests_employee_work_experiences';
	public $tbl_requests_employee_professions    	  = 'requests_employee_professions';
	public $tbl_requests_employee_service_record      = 'requests_employee_service_record';
	public $tbl_requests_employee_questions           = 'requests_employee_questions';
	public $tbl_requests_certifications               = 'requests_certifications';
	public $tbl_requests_leaves                       = 'requests_leaves';
	public $tbl_requests_leave_details                = 'requests_leave_details';
	public $tbl_requests_employee_attendance          = 'requests_employee_attendance';
	public $tbl_requests_sub                          = 'requests_sub';
	public $tbl_requests_tasks                        = 'requests_tasks';
	public $tbl_rejected_forced_leave                        = 'rejected_forced_leave';
	
	public $tbl_salary_table_dtl                      = 'salary_table_dtl';
	public $tbl_salary_table_hdr                      = 'salary_table_hdr';
	public $tbl_vouchers                      		  = 'vouchers';
	
	public $tbl_users                                 = 'users';
	public $tbl_param_terminal                        = 'param_terminal';
	public $tbl_dtr_upload_hdr                        = 'dtr_upload_hdr';
	public $tbl_dtr_upload_sub                        = 'dtr_upload_sub';
	public $tbl_dtr_temp_upload_data                  = 'dtr_temp_upload_data';
	public $tbl_attendance_period_hdr                 = 'attendance_period_hdr';
	public $tbl_attendance_period_dtl                 = 'attendance_period_dtl';
	public $tbl_attendance_period_summary			  = 'attendance_period_summary';
	public $tbl_param_payroll_status                  = 'param_payroll_status';
	public $tbl_param_rates                           = 'param_rates';
	public $tbl_payroll_attendance_amount             = 'payroll_attendance_amount';
	public $tbl_param_payroll_types                   = 'param_payroll_types';
	public $tbl_param_payroll_type_status_offices     = 'param_payroll_type_status_offices';
	public $tbl_param_attendance_period_status        = 'param_attendance_period_status';
	
	public $tbl_param_payout_status					  = 'param_payout_status';
	public $tbl_param_compensation_prorated			  = 'param_compensation_prorated';
	
	public $tbl_employee_tenure			  			  = 'employee_tenure';
	public $tbl_employee_longevity_pay				  = 'employee_longevity_pay';
	public $tbl_employee_loyalty_cash_awards		  = 'employee_loyalty_cash_awards';
	public $tbl_param_payroll_compensations			  = 'param_payroll_compensations';
	public $tbl_param_payroll_deductions			  = 'param_payroll_deductions';
	public $tbl_param_responsibility_centers		  = 'param_responsibility_centers';
	// ====================== jendaigo : start : include new tables for responsibility code reference ============= //
	public $tbl_param_prexc_codes					  = 'param_prexc_codes';
	public $tbl_param_responsibility_prexc_codes	  = 'param_responsibility_prexc_codes';
	public $tbl_param_uacs_object_types				  = 'param_uacs_object_types';
	public $tbl_param_uacs_object_codes				  = 'param_uacs_object_codes';
	// ====================== jendaigo : end : include new tables for responsibility code reference ============= //
	
	// TABLES USED FOR DATA DOWNLOADS
	public $tbl_data_dictionary                       = 'param_data_dictionary';
	public $tbl_download_template_hdr                 = 'data_download_template_hdr';
	public $tbl_download_template_dtl                 = 'data_download_template_dtl';
	public $tbl_download_history_hdr                  = 'data_download_history_hdr';
	public $tbl_download_history_dtl                  = 'data_download_history_dtl';
	// TABLES USED FOR TABLE GROUP MODULE
	public $tbl_table_group_hdr                       = 'table_group_hdr';
	public $tbl_table_group_dtl                       = 'table_group_dtl';
	public $tbl_table_group_condition                 = 'table_group_condition';
	
	public $tbl_sys_param                             = 'sys_param';
	public $tbl_organizations                         = 'organizations';
	
	// FORM 2316
	public $tbl_form_2316_header					  = 'form_2316_header';
	public $tbl_form_2316_dependents				  = 'form_2316_dependents';
	public $tbl_form_2316_employers					  = 'form_2316_employers';
	public $tbl_form_2316_details				  	  = 'form_2316_details';
	public $tbl_form_2316_monthly_details		  	  = 'form_2316_monthly_details';
	
	public $tbl_param_form_2316						  = 'param_form_2316';
	public $tbl_param_tax_exempt_code				  = 'param_tax_exempt_code';
	
	// FORM 2307
	public $tbl_form_2307_header					  = 'form_2307_header';
	public $tbl_form_2307_details				  	  = 'form_2307_details';
	public $tbl_form_2307_monthly_details			  = 'form_2307_monthly_details';	

	
	// MODULES AND ACTIONS
	public $tbl_param_scopes	  			     	  = 'param_scopes';
	public $tbl_systems          					  = 'systems';
	public $tbl_actions								  = 'actions';
	public $tbl_modules			  					  = 'modules';
	public $tbl_module_actions 	  					  = 'module_actions';
	public $tbl_module_scopes 	 					  = 'module_scopes';
	public $tbl_module_action_roles					  = 'module_action_roles';
	public $tbl_module_scope_roles 					  = 'module_scope_roles';
	public $tbl_system_roles  	 					  = 'system_roles';
	public $tbl_roles 								  = 'roles';
	public $tbl_user_offices						  = 'user_offices';
	public $tbl_user_roles                            = 'user_roles'; //davcorrea : include user roles

}