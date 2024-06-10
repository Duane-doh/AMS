<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0755);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('FOPEN_READ', 'rb');
define('FOPEN_READ_WRITE', 'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE', 'ab');
define('FOPEN_READ_WRITE_CREATE', 'a+b');
define('FOPEN_WRITE_CREATE_STRICT', 'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/*
|--------------------------------------------------------------------------
| Display Debug backtrace
|--------------------------------------------------------------------------
|
| If set to TRUE, a backtrace will be displayed along with php errors. If
| error_reporting is disabled, the backtrace will not display, regardless
| of this setting
|
*/
define('SHOW_DEBUG_BACKTRACE', TRUE);

/*
|--------------------------------------------------------------------------
| Exit Status Codes
|--------------------------------------------------------------------------
|
| Used to indicate the conditions under which the script is exit()ing.
| While there is no universal standard for error codes, there are some
| broad conventions.  Three such conventions are mentioned below, for
| those who wish to make use of them.  The CodeIgniter defaults were
| chosen for the least overlap with these conventions, while still
| leaving room for others to be defined in future versions and user
| applications.
|
| The three main conventions used for determining exit status codes
| are as follows:
|
|    Standard C/C++ Library (stdlibc):
|       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
|       (This link also contains other GNU-specific conventions)
|    BSD sysexits.h:
|       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
|    Bash scripting:
|       http://tldp.org/LDP/abs/html/exitcodes.html
|
*/
define('EXIT_SUCCESS', 0); // no errors
define('EXIT_ERROR', 1); // generic error
define('EXIT_CONFIG', 3); // configuration error
define('EXIT_UNKNOWN_FILE', 4); // file not found
define('EXIT_UNKNOWN_CLASS', 5); // unknown class
define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
define('EXIT_USER_INPUT', 7); // invalid user input
define('EXIT_DATABASE', 8); // database error
define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code

/*
|--------------------------------------------------------------------------
| DIRECTORY and PATH
|--------------------------------------------------------------------------
|
| These constants are used when working with directory and file naming
|
*/
define('DS', DIRECTORY_SEPARATOR);
define('DOCUMENT_ROOT', dirname($_SERVER['SCRIPT_FILENAME']).DS);


/*
|--------------------------------------------------------------------------
| GENERAL CONSTANTS
|--------------------------------------------------------------------------
|
| These constants are used by the whole project/product
|
*/
	/*
	|---------------------------------------------------------------------
	| STATIC PATH
	|---------------------------------------------------------------------
	| These constants are used when defining the folder path of your css,
	| js, images and file upload
	*/
	define('PATH_CSS', 'static/css/');
	define('PATH_JS', 'static/js/');
	define('PATH_IMAGES', 'static/images/');
	define('PATH_UPLOADS', 'uploads/');
	define('PATH_USER_UPLOADS', 'uploads/users/');
	define('PATH_SETTINGS_UPLOADS', 'uploads/settings/');
	define('PATH_FILE_UPLOADS', 'uploads/files/');
	define('PATH_IMG', 'static/images/');
	define('PATH_PDS_UPLOADS', 'uploads/pds/');
	define('PATH_BIOMETRIC_UPLOADS', 'uploads/biometric/');
	define('PATH_PDS_UPLOAD_ERROR_LOGS', 'uploads/pds_error_logs/');
	define('PATH_BIOMETRIC_UPLOAD_ERROR_LOGS', 'uploads/biometric_error_logs/');
	define('PATH_REMITTANCE_ATTACHMENT', 'uploads/remittance/');
	define('PATH_DBF_REPORT', 'uploads/dbf/');

	/*
	|---------------------------------------------------------------------
	| CSS AND JS
	|---------------------------------------------------------------------
	| These are the frequently used CSS and JS in
	| resources(load_css and load_js)
	*/

		/* CHECKBOX / RADIO BUTTON */
		define('CSS_LABELAUTY', 'jquery-labelauty');
		define('JS_LABELAUTY', 'jquery-labelauty');

		/* DATATABLE */
		define('CSS_DATATABLE', 'jquery.dataTables');
		define('JS_DATATABLE', 'jquery.dataTables.min');

		/* DATE PICKER / TIME PICKER */
		define('CSS_DATETIMEPICKER', 'jquery.datetimepicker');
		define('JS_DATETIMEPICKER', 'jquery.datetimepicker');

		/* DROPDOWN / SELECT FIELD */
		define('CSS_SELECTIZE', 'selectize.default');
		define('JS_SELECTIZE', 'selectize');

		/* MODAL */
		define('CSS_MODAL_COMPONENT', 'component');
		define('JS_MODAL_CLASSIE', 'classie');
		define('JS_MODAL_EFFECTS', 'modalEffects');

		/* SCROLL */
		define('CSS_PRETTIFY', 'prettify');
		define('JS_PRETTIFY', 'prettify');
		define('JS_SLIMSCROLL', 'jquery.slimscroll');

		/* TABS */
		define('CSS_TABS', 'easy-responsice-tabs');
		define('JS_TABS', 'easyResponsiveTabs');

		/* UPLOAD */
		define('CSS_UPLOAD', 'uploadfile');
		define('JS_UPLOAD', 'jquery.uploadfile');

		/* TEXT EDITOR */
		define('JS_EDITOR', 'ckeditor/ckeditor');

		/* CALENDAR */
		define('CSS_CALENDAR', 'fullcalendar');
		define('JS_CALENDAR', 'fullcalendar');
		define('JS_CALENDAR_MOMENT', 'moment.min');

		/* RATINGS (STARS) */
		define('CSS_RATINGS', 'jquery.rateyo.min');
		define('JS_RATINGS', 'jquery.rateyo.min');

		/* COLOR PICKER */
		define('CSS_COLORPICKER', 'colorpicker/colorpicker');
		define('JS_COLORPICKER', 'colorpicker/colorpicker');
		define('JS_COLORGROUP', 'group');
		
		/* NUMBER INPUT */
		define('JS_NUMBER', 'jquery.number.min');





		

	/*
	|---------------------------------------------------------------------
	| LENGTH OF SALT
	|---------------------------------------------------------------------
	| Control the length of salt used for security purposes
	*/
	define('SALT_LENGTH', 15);

	/*
	|---------------------------------------------------------------------
	| SYSTEM STATUS
	|---------------------------------------------------------------------
	*/
	define('SYSTEM_ON', 1);

	/*
	|---------------------------------------------------------------------
	| PERMISSION ACTIONS
	|---------------------------------------------------------------------
	| These constants are used when defining the action to be made in
	| permission
	*/
	define('ACTION_SAVE', 1);
	define('ACTION_ADD', 2);
	define('ACTION_EDIT', 3);
	define('ACTION_DELETE', 4);
	define('ACTION_VIEW', 5);
	define('ACTION_PRINT', 6);
	define('ACTION_LOCK', 7);
	define('ACTION_UPLOAD', 8);
	define('ACTION_PROCESS', 9);
	define('ACTION_CANCEL', 10);
	define('ACTION_APPROVE', 11);
	define('ACTION_REJECT', 12);
	define('ACTION_DOWNLOAD', 13);
	define('ACTION_VIEW_LIST', 14);
	define('ACTION_HISTORY', 15);
	define('ACTION_REVIEW', 20);
	define('ACTION_REMIT', 21);
	define('ACTION_PAYMENT', 22);
	define('ACTION_UPDATE_2316', 23);
	define('ACTION_PROCESS_ALL', 24);
	define('ACTION_ASSIGN', 25);

	/*
	|---------------------------------------------------------------------
	| BUTTON ACTIONS
	|---------------------------------------------------------------------
	| These constants are used as button labels
	*/
	define('BTN_LOG_IN', 'Log In');
	define('BTN_SIGN_UP', 'Sign Up');
	define('BTN_CREATE_ACCOUNT', 'Create Account');
	define('BTN_SAVE', 'Save');
	define('BTN_ADD', 'Add');
	define('BTN_UPDATE', 'Update');
	define('BTN_DELETE', 'Delete');
	define('BTN_CANCEL', 'Cancel');
	define('BTN_CLOSE', 'Close');
	define('BTN_POST', 'Post');
	define('BTN_DOWNLOAD', 'Download');
	define('BTN_PROCESS', 'Process');

	/*
	|---------------------------------------------------------------------
	| BUTTON VERBS
	|---------------------------------------------------------------------
	| These constants are used for replacing the loading text in the
	| button after pressing it
	*/
	define('BTN_SIGNING_UP', 'Signing up');
	define('BTN_CREATING_ACCOUNT', 'Creating Account');
	define('BTN_LOGGING_IN', 'Logging in');
	define('BTN_EMAILING', 'Sending email');
	define('BTN_POSTING', 'Posting');
	define('BTN_SAVING', 'Saving');
	define('BTN_UPDATING', 'Updating');
	define('BTN_DELETING', 'Deleting');
	define('BTN_UPLOADING', 'Uploading');

	/*
	|---------------------------------------------------------------------
	| SETTINGS LOCATION
	|---------------------------------------------------------------------
	| These constants are the sub-menus of site settings module
	*/
	define('AUTHENTICATION', 'AUTHENTICATION');
	define('SITE_APPEARANCE', 'SITE_APPEARANCE');

	/*
	|---------------------------------------------------------------------
	| SETTINGS TYPE
	|---------------------------------------------------------------------
	| These constants are the main sections of site settings module
	*/
	define('GENERAL', 'GENERAL');
	define('LAYOUT', 'LAYOUT');
	define('THEME', 'THEME');
	define('ACCOUNT', 'ACCOUNT');
	define('PASSWORD_CONSTRAINTS', 'PASSWORD_CONSTRAINTS');
	define('PASSWORD_EXPIRY', 'PASSWORD_EXPIRY');
	define('LOGIN', 'LOGIN');

	/*
	|---------------------------------------------------------------------
	| SEARCH USER USING THE FOLLOWING PARAMETERS
	|---------------------------------------------------------------------
	| These constants are used as a parameter for searching a specific
	| user in get_active_user() function
	*/
	define('BY_USERNAME', 'username');
	define('BY_EMAIL', 'email');
	define('BY_RESET_SALT', 'reset_salt');

	/*
	|---------------------------------------------------------------------
	| USER STATUS
	|---------------------------------------------------------------------
	| These status are used for identifying the status of the user
	*/
	define('ACTIVE', '1');
	define('PENDING', '2');
	define('INACTIVE', '3');
	define('APPROVED', '4');
	define('DISAPPROVED', '5');
	define('DELETED', '6');
	define('BLOCKED', '7');
	define('DRAFT', '8');
	define('EXPIRED', '9');

	/*
	|---------------------------------------------------------------------
	| WORK TYPE
	|---------------------------------------------------------------------
	| These status are used for identifying the type of work of the user 
	*/
	define('PRIVATE_WORK', 'PR');
	define('NON_DOH_GOV', 'OG');
	define('DOH_GOV_APPT', 'AP');
	define('DOH_GOV_NON_APPT', 'WP');
	define('DOH_JO', 'JO');

	/*
	|---------------------------------------------------------------------
	| ALERT TYPE
	|---------------------------------------------------------------------
	| These types are used for identifying the notification class
	| used by notifyModal
	*/
	define('SUCCESS', 'success');
	define('ERROR', 'error');


	/*
	|---------------------------------------------------------------------
	| SUB MENU
	|---------------------------------------------------------------------
	| THESE CONSTANTS ARE USED TO HIGHLIGHT THE ACTIVE MENU
	*/
	define('SUB_MENU_DASHBOARD', 'Dashboard');
	define('SUB_MENU_HR', 'Human Resources');
	define('SUB_MENU_PDS', 'Personal Data Sheet');
	define('SUB_MENU_PERSONAL_INFO', 'Personal Information');
	define('SUB_MENU_IDENTIFICATION', 'Identification');
	define('SUB_MENU_CONTACT_INFO', 'Contact Information');
	define('SUB_MENU_FAMILY','Family');
	define('SUB_MENU_EDUCATION', 'Education');
	Define('SUB_MENU_GOVERNMENT_EXAM','Government Exam');
	Define('SUB_MENU_WORK_EXPERIENCE', 'Work Experience');
	Define('SUB_MENU_PROFESSION', 'Profession');
	Define('SUB_MENU_VOLUNTARY_WORK', 'Voluntary Work');
	Define('SUB_MENU_TRAININGS', 'Trainings');
	Define('SUB_MENU_OTHER_INFO', 'Other Information');
	Define('SUB_MENU_QUESTIONNAIRE', 'Questionnaire');
	define('SUB_MENU_REFERENCES', 'References');
	define('SUB_MENU_DECLARATION', 'Declaration');
	define('SUB_MENU_SR', 'Service Record');
	define('SUB_MENU_SR_LIST', 'Employee Service Record List');
	define('SUB_MENU_CR', 'Contract Record');
	define('SUB_MENU_CR_LIST', 'Employee Contract Record List');
	define('SUB_MENU_COMPENSATION', 'Compensations');
	define('SUB_MENU_COMPENSATION_PACKAGE', 'Compensation Package');
	define('SUB_MENU_SALARY', 'Salary');
	define('SUB_MENU_BENEFITS', 'Benefits');
	define('SUB_MENU_LOANS', 'Loans');
	define('SUB_MENU_OTHERS', 'Others');
	define('SUB_MENU_DEDUCTIONS', 'Deductions');
	define('SUB_MENU_DTR', 'Daily Time Record');
	define('SUB_MENU_PDS_UPLOAD', 'Upload PDS');
	define('SUB_MENU_STATUTORY', 'Statutory');
	define('SUB_MENU_TIME_ATTENDANCE', 'Time & Attendance');
	define('SUB_MENU_BIO_LOGS_UPLOAD', 'Attendance Logs');
	define('SUB_MENU_DAILY_TIME_RECORD', 'Daily Time Record');
	define('SUB_MENU_ATTENDANCE_PERIOD', 'Attendance Period');
	define('SUB_MENU_LEAVE', 'Leaves');
	define('SUB_MENU_OVERTIME', 'Overtime');
	define('SUB_MENU_REPORTS', 'Reports');
	define('SUB_MENU_PAYROLL', 'Payroll');
	define('SUB_MENU_GENERAL_PAYROLL', 'General Payroll');
	define('SUB_MENU_SPECIAL_PAYROLL', 'Special Payroll');
	define('SUB_MENU_VOUCHER_PAYROLL', 'Employee Voucher');
	define('SUB_MENU_REMITTANCE_PAYROLL', 'Remittances');
	define('SUB_MENU_CODE_LIBRARY', 'Code Library');
	define('SUB_MENU_ADHOC_REPORTS', 'Adhoc Reports');
	define('SUB_MENU_PAYROLL_REPORTS', 'Payroll Reports');
	define('SUB_MENU_PERFORMANCE_EVALUATION', 'Performance Evaluation');

	

	define('CONTACT_INFO_ADDRESS', 'Address');
	define('CONTACT_INFO_NUMBER', 'Contact Number');
	define('OTHER_INFO_SKILL', 'Skill');
	define('OTHER_INFO_RECOGNITION', 'Recognition');
	define('OTHER_INFO_MEMBERSHIP', 'Membership');

	define('PDS_PERSONAL_INFO', 'personal_info');
	define('PDS_IDENTIFICATION', 'identification');
	define('PDS_CONTACT_INFO', 'contact_info');
	define('PDS_FAMILY', 'family');
	define('PDS_EDUCATION', 'education');
	define('PDS_GOVERNMENT_EXAM', 'government_exam');
	define('PDS_WORK_EXPERIENCE', 'work_experience');
	define('PDS_PROFESSION', 'profession');
	define('PDS_VOLUNTARY_WORK', 'voluntary_work');
	define('PDS_TRAININGS', 'trainings');
	define('PDS_OTHER_INFORMATION', 'other_info');
	define('PDS_QUESTIONNAIRE', 'questionnaire');
	define('PDS_REFERENCES', 'references');
	define('PDS_DECLARATION', 'declaration');
	define('PDS_SUPPORTING_DOCUMENTS', 'supporting_documents');
	define('PDS_RECORD_CHANGES', 'record_changes');
	define('PDS_CHECK_LIST', 'check_list');


	define('CP_BENEFITS', 'employee_benefits');
	define('CP_SALARY', 'employee_salary');

	define('COMPENSATION_BENEFITS', 'compensation_benefits');
	define('COMPENSATION_SALARY', 'compensation_salary');

	define('PROCESS_DTR', 'process_dtr');

	define('REPORT_DOH_COOP_SUMMARY_DEDUCTION', 'DOH_coop_summary_deduction');
	define('REPORT_DOH_COOPERATIVE_SHARE', 'DOH_cooperative_share');
	define('REPORT_DATABASE_REPORT_ATM_ALPHALIST_REPORT', 'Database_Report_ATM_Alphalist_Report');
	define('REPORT_GSISI_UMID_CAS_ADV_LOAN', 'GSIS_UMID_cas_adv_loan');
	define('REPORT_GSIS_CONSO_LOAN', 'GSIS_conso_loan');
	define('REPORT_GSIS_EDUC_ASSISTANCE', 'GSIS_educ_assistance');
	define('REPORT_GSIS_OFFICE_OF_THE_SECRETARY', 'GSIS_office_of_the_secretary');
	define('REPORT_KSS_PORMA', 'KSS_PORMA');
	define('REPORT_KSS_PORMA_SERTIPIKASYON', 'KSS_sertipikasyon');
	define('REPORT_LAND_BANK_OF_THE_PHILIPPINES_LOAN', 'Land_Bank_of_the_Philippines_Loan');
	define('REPORT_PAGIBIG_MCRE', 'PAGIBIG_MCRE');
	define('REPORT_PAGIBIG_CALAMITY_LOAN', 'PAGIBIG_calamity_loan');
	define('REPORT_PAGIBIG_HOUSING_LOAN', 'PAGIBIG_housing_loan');
	define('REPORT_PAGIBIG_MULTI_PURPOSE_LOAN', 'PAGIBIG_multi_purpose_loan');
	define('REPORT_PHILHEALTH_CONTRIBUTION', 'PHILHEALTH_contribution');
	define('REPORT_PHILHEALTH_OFFICE_OF_THE_SECRETARY', 'PHILHEALTH_office_of_the_secretary');
	define('REPORT_RAI_PART1', 'RAI_part1');
	define('REPORT_RAI_PART2', 'RAI_part2');
	define('REPORT_UKKS', 'UKKS');
	define('REPORT_APPLICATION_FOR_LEAVE', 'application_for_leave');
	define('REPORT_DISALLOWANCE', 'disallowance');
	define('REPORT_EMPLOYERS_CERTIFICATE_OF_COMPENSATION_PAYMENT_TAX_WITHHELD', 'employers_certificate_of_compensation_payment_tax_withheld');
	define('REPORT_GENERAL_PAYROLL_ALPHALIST_REPORT', 'general_payroll_alphalist_report');
	define('REPORT_GENERAL_PAYROLL_ALPHALIST_REPORT_AS', 'general_payroll_alphalist_report_AS');
	define('REPORT_GENERAL_PAYROLL_COVER_SHEET_1', 'general_payroll_cover_sheet_1');
	define('REPORT_GENERAL_PAYROLL_COVER_SHEET_2', 'general_payroll_cover_sheet_2');
	define('REPORT_MEETING_AND_DATA_GATHERING_FOR_PERSONNEL_TRANSACTION_AND_INFORMATION_SYSTEM', 'meeting_and_data_gathering_for_personnel_transaction_and_information_system');
	define('REPORT_NATIONAL_HOME_MORTGAGE_FINANCE', 'national_home_mortgage_finance');
	define('REPORT_PAYROLL_REGISTER', 'payroll_register');
	define('REPORT_PAYSLIP_CONTRACTUAL', 'payslip_contractual');
	define('REPORT_PAYSLIP_REGULAR', 'payslip_regular');
	define('REPORT_PAYSLIP_SPECIAL_BENEFITS', 'payslip_special_benefits');
	define('REPORT_PHILIPPINES_NATIONAL_AIDS_COUNCIL', 'philippines_national_aids_council');
	define('REPORT_REGULAR_WITHHOLDING_TAX', 'regular_withholding_tax');
	define('REPORT_REMITTANCE_LIST_OF_LIFE_AND_RETIREMENT_CONTRIBUTION', 'remittance_list_of_life_and_retirement_contribution');
	define('REPORT_REMITTANCE_LIST_OF_EMPLOYEES_COMPENSATION_INSURANCE_PREMIUM', 'remittance_list_of_employees_compensation_insurance_premium');
	define('REPORT_REMITTANCE_OF_HOUSING_LOANS', 'remittance_of_housing_loans');
	define('REPORT_REMITTANCE_OF_INSURANCE_PREMIUMS_INSURANCE_LOANS', 'remittance_of_insurance_premiums_insurance_loans');
	define('REPORT_REMITTANCE_OF_SALARY_POLICY_CALAMITY_EMERGENCY_LOANS', 'remittance_of_salary_policy_calamity_emergency_loans');
	define('REPORT_TAX_REPORT', 'tax_report');

	
	
	

	//HUMAN RESOURCES REPORT
	define('REPORT_SERVICE_RECORD', 'service_record');
	define('REPORT_APPOINTMENT_CERTIFICATE', 'KSS_PORMA');
	define('REPORT_ASSUMPTION_TO_DUTY', 'assumption_to_duty');//NCOCAMPO 01/11/2024
	define('REPORT_OFFICE', 'list_office');
	define('REPORT_POSITION_LEVEL', 'list_position');
	define('REPORT_POSITION_TITLE', 'list_position_title');
	define('REPORT_CLASS', 'list_class');
	define('REPORT_SALARY_GRADE', 'list_salary_grade');
	define('REPORT_BIRTH_DATE', 'list_birth_date');
	define('REPORT_AGE', 'list_age');
	define('REPORT_GENDER', 'list_gender');
	define('REPORT_PROFESSION', 'list_profession');
	define('REPORT_EMPLOYMENT_STATUS', 'list_employment_status');
	define('REPORT_BENEFIT_ENTITLEMENT', 'list_benefit_entitlement');
	define('REPORT_SERVICE_LENGTH', 'list_service_length');
	define('REPORT_MONTHLY_ACCESSION', 'monthly_report_on_accession');
	define('REPORT_MONTHLY_SEPARATION', 'monthly_report_on_separation');
	define('REPORT_PSIPOP_PLANTILLA', 'PSIPOP_plantilla');
	define('REPORT_PERSONNEL_MOVEMENT', 'personnel_movement');
	define('REPORT_NDHRHIS_FILE', '');
	define('REPORT_PERSONAL_DATA_SHEET', 'pds_report');
	define('REPORT_RETIREES', 'number_of_retirees');
	define('REPORT_RESIGNED_EMPLOYEES', 'number_of_resigned_employees');
	define('REPORT_DROPPED_EMPLOYEES', 'number_of_dropped_employees');
	define('REPORT_PROMOTED_EMPLOYEES', 'number_of_promoted_employees');
	define('REPORT_ENTITLEMENT_LONGEVITY_PAY', 'entitlement_longevity_pay');
	define('REPORT_FILLED_UNFILLED_POSITION', 'filled_unfilled_position');
	define('REPORT_TRANSFEREES', 'transferees');
	define('REPORT_GSIS_CERTIFICATE_CONTRIBUTION', 'gsis_certificate_of_contribution');
	define('REPORT_PHILHEALTH_CERTIFICATE_CONTRIBUTION', 'philhealth_certificate_of_contribution');
	define('REPORT_PAGIBIG_CERTIFICATE_CONTRIBUTION', 'pagibig_certificate_of_contribution');
	define('REPORT_CERTIFICATE_LAST_SALARY_RECEIVED', '');
	define('REPORT_CERTIFICATE_EMPLOYMENT_COMPENSATION', '');
	define('REPORT_CERTIFICATE_EMPLOYMENT_DUTIES_RESPONSIBILITIES', '');
	define('REPORT_NOTICE_SALARY_ADJUSTMENT', 'notice_of_salary_adjustment');
	define('REPORT_NOTICE_SALARY_ADJUSTMENT_COMPULSORY_RETIREMENT', 'notice_of_salary_adjustment_compulsory_retirement'); //ncocampo 05/06/2024
	define('REPORT_NOTICE_SALARY_STEP_INCREMENT', 'notice_of_salary_step_increment');
	define('REPORT_NOTICE_STEP_INCREMENT', 'notice_of_step_increment');
	define('REPORT_NOTICE_SALARY_INCREMENT', '');
	define('REPORT_NOTICE_LONGEVITY_PAY', 'notice_longevity_pay');
	define('REPORT_NOTICE_LONGEVITY_PAY_INCREASE', 'notice_longevity_pay_increase');
	define('REPORT_PRIME_HRM_ASSESSMENT', 'prime_hrm_assessment');
	define('REPORT_PHILHEALTH_MEMBERSHIP_FORM', 'philhealth_membership_form');
	define('REPORT_GSIS_MEMBERSHIP_FORM', 'gsis_membership_form');
	define('REPORT_PAGIBIG_MEMBERSHIP_FORM', 'pagibig_membership_form');
	define('REPORT_COE_WITH_COMPENSATIONS', 'coe_with_compensations');
	define('REPORT_COE_WITHOUT_COMPENSATIONS', 'coe_without_compensations');
	define('REPORT_POSITION_DESCRIPTION', 'position_description');

	//TIME AND ATTENDANCE REPORT
	define('REPORTS_TA_DAILY_TIME_RECORD', 'ta_daily_time_record');
	define('REPORTS_TA_LEAVE_APPLICATION', 'ta_leave_aplication');
	define('REPORTS_TA_LEAVE_CARD', 'ta_leave_card');
	define('REPORTS_TA_MONTHLY_ATTENDANCE', 'ta_monthly_attendance');
	define('REPORTS_TA_LEAVE_BALANCE_STATEMENT', 'ta_leave_balance_statement');
	define('REPORTS_TA_LEAVE_CREDIT_CERT', 'ta_leave_credit_cert');
	define('REPORTS_TA_LEAVE_WITHOUT_PAY_CERT', 'ta_leave_without_pay_cert');

	define('REPORTS_TA_NO_WORK_SCHED_LIST', 'ta_no_work_sched_list');
	
	define('PAYROLL_REPORTS', 'Payroll Reports');
	define('ATTENDANCE_CORRECTION_REASONS', 'Attendance Correction Reasons');

	define('REPORT_MONTHLY_SALARY_SCHEDULE', 'monthly_salary_schedule');

	
	//PAYROLL REPORTS
	define('REPORT_GENERAL_PAYROLL_SUMMARY', 'general_payroll_cover_sheet');
	define('REPORT_GENERAL_PAYROLL_SUMMARY_GRAND_TOTAL', 'general_payroll_summary_grand_total');
	define('REPORT_GENERAL_PAYROLL_PER_OFFICE', 'general_payroll_summary_per_office'); 
	define('REPORT_GENERAL_PAYROLL_ALPHALIST_PER_OFFICE', 'general_payroll_alpha_list_per_office');
	define('REPORT_SPECIAL_PAYROLL_COVER_SHEET', 'special_payroll_cover_sheet');
	define('REPORT_SPECIAL_PAYROLL_SUMMARY_GRAND_TOTAL', 'special_payroll_summary_grand_total'); 
	define('REPORT_SPECIAL_PAYROLL_SUMMARY_PER_OFFICE', 'special_payroll_summary_per_office');
	define('REPORT_SPECIAL_PAYROLL_ALPHA_LIST_PER_OFFICE', 'special_payroll_alpha_list_per_office');
	define('REPORT_GENERAL_PAYSLIP_FOR_REGULARS_AND_NONCAREERS', 'general_payslip_for_regulars_and_non_careers');
	define('REPORT_GENERAL_FOR_CONSTRACTS_OF_SERVICE', 'general_Payslip_for_contracts_of_service');
	define('REPORT_SPECIAL_PAYSLIP_FOR_REGULARS_AND_NONCAREERS', 'special_payslip_for_regulars_and_non_careers');
	define('REPORT_BANK_PAYROLL_REGISTER', 'bank_payroll_register');
	define('REPORT_ATM_ALPHA_LIST', 'atm_alpha_list');
	define('REPORT_ATM_ALPHA_LIST2', 'atm_alpha_list2');
	define('REPORT_REMITTANCE_SUMMARY_GRAND_TOTAL', 'remittance_summary_grand_total');
	define('REPORT_REMITTANCE_SUMMARY_PER_OFFICE', 'remittance_summary_per_office');
	define('REPORT_REMITTANCE_LIST_PER_OFFICE', 'remittance_list_per_office');
	define('REPORT_CONSOLIDATED_REMITTANCE_SUMMARY_PER_OFFICE', 'consolidated_remittance_summary_per_office');
	define('REPORT_CONSOLIDATED_REMITTANCE_LIST_PER_OFFICE', 'consolidated_remittance_list_per_office');
	define('REPORT_GSIS_CONTRIBUTIONS_REMITTANCE_FILE_FOR_UPLOADING', 'gsis_contributions_remittance_file_for_uploading');
	define('REPORT_PHILHEALTH_CONTRIBUTIONS_REMITTANCE_FILE_FOR_UPLOADING', 'philhealth_contributions_remittance_file_for_uploading');
	define('REPORT_PAGIBIG_CONTRIBUTIONS_REMITTANCE_FILE_FOR_UPLOADING', 'pagibig_contributions_remittance_file_for_uploading');
	define('REPORT_PAGIBIG_DEDUCTIONS_REMITTANCE_FILE_FOR_UPLOADING', 'pagibig_deductions_remittance_file_for_uploading');
	define('REPORT_BIR_TAX_PAYMENTS', 'bir_tax_payments');
	define('REPORT_DOH_COOP_REMITTANCE_FILE', 'doh_coop_remittance_file');
	define('REPORT_BIR_1601C_MONTHLY_REPORT_OF_TAX_WITHHELD', 'bir_1601c');
	define('REPORT_REPORT_BIR_2316_CERTIFICATE_OF_COMPENSATION_PAYMENT', 'bir_2316_certificate_of_compensation_payment_tax_withheld'); 
	define('REPORT_BIR_ALPHALIST', 'bir_alphalist');
	define('REPORT_BIR_ALPHALIST_WITH_PREVIOUS_EMPLOYER', 'bir_alphalist_with_previous_employer'); 
	define('REPORT_BIR_ALPHALIST_TERMINATED_BEFORE_YEAR_END', 'bir_alphalist_terminated_before_year_end'); 
	define('REPORT_YEAR_END_ADJUSTMENT_REPORT_PER_OFFFICE', 'year_end_adjustment_report_per_office');
	define('REPORT_DISBURSEMENT_VOUCHER', 'disbursement_voucher');
	define('REPORT_ENGAS_FILE_FOR_UPLOADING', 'eNGAS_file_for_uploading');
	define('REPORT_BIR_2307_CERTIFICATE_OF_CREDITABLE_TAX_WITHHELD_AT_SOURCE', 'bir_2307_certificate_of_creditable_tax_withheld_at_source');
	define('REPORT_EMPLOYEES_PAID_BY_VOUCHER', 'employees_paid_by_voucher');
	define('REPORT_BIR_2306_CERTIFICATE_OF_FINAL_TAX_WITHHELD_AT_SOURCE', 'bir_2306_certificate_of_final_tax_withheld_at_source');
	define('REPORT_EMPLOYEES_NOT_INCLUDED_IN_PAYROLL', 'employees_not_included_in_payroll');
	define('REPORT_COOP_REMITTANCE', 'coop_remittance');
	define('REPORT_BIR_2305_CERTIFICATE_OF_UPDATE', 'bir_2305_certificate_of_update');
	define('REPORT_RESPONSIBILITY_CODE_PER_OFFICE', 'responsibility_code_per_office');
	define('REPORT_GENERAL_PAYROLL_ALPHALIST_FOR_JO', 'general_payroll_alpha_list_for_jo');
	define('REPORT_EXPANDED_WITHHOLDING_TAX_AND_GOVERNMENT_MONEY_PAYMENT', 'expanded_withholding_tax_and_government_money_payment');
	
	//HUMAN RESOURCES CODE LIBRARY
	define('CODE_LIBRARY_ACADEMIC_HONOR', 'Academic Honor');
	define('CODE_LIBRARY_COMPENSATION', 'Compensation Type');
	define('CODE_LIBRARY_DEDUCTION_TYPE', 'Deduction Type');
	define('CODE_LIBRARY_EDUCATION_DEGREE', 'Education Degree');
	define('CODE_LIBRARY_EDUCATIONAL_LEVEL', 'Educational Level');
	define('CODE_LIBRARY_ELIGIBILITY_TITLE', 'Eligibility');
	define('CODE_LIBRARY_EMPLOYMENT_STATUS', 'Employment Status'); 	
	define('CODE_LIBRARY_BRANCH', 'Government Branch');
	define('CODE_LIBRARY_SEPARATION_MODE', 'Mode of Separation');
	define('CODE_LIBRARY_PERSONNEL_MOVEMENT', 'Personnel Movement');
	define('CODE_LIBRARY_PLANTILLA', 'Plantilla');
	define('CODE_LIBRARY_POSITION', 'Position');
	define('CODE_LIBRARY_SALARY_SCHEDULE', 'Salary Schedule');
	define('CODE_LIBRARY_SCHOOL', 'School');

	//TIME & ATTENDANCE CODE LIBRARY
	define('CODE_LIBRARY_LEAVE_TYPE', 'Leave Type');
	define('CODE_LIBRARY_COMPUTATION_TABLE', 'Computation Table'); // davcorrea : Computation Table var declaration
	define('CODE_LIBRARY_HOLIDAY_TYPE', 'Holiday Type');
	define('CODE_LIBRARY_WORK_CALENDAR', 'Work Calendar');

	//PAYROLL CODE LIBRARY
	define('CODE_LIBRARY_BANK', 'Bank');
	define('CODE_LIBRARY_BIR_TABLE', 'BIR Table');
	define('CODE_LIBRARY_FUND_SOURCE', 'Fund Source');
	define('CODE_LIBRARY_GSIS_TABLE', 'GSIS Table');
	define('CODE_LIBRARY_PAGIBIG_TABLE', 'Pag-ibig Table');
	define('CODE_LIBRARY_PHILHEALTH_TABLE', 'Philhealth Table');
	define('CODE_LIBRARY_SSS_TABLE', 'SSS Table');
	define('CODE_LIBRARY_REMITTANCE_TYPE', 'Remittance Type');
	define('CODE_LIBRARY_REMITTANCE_PAYEE', 'Remittance Payee');
	define('CODE_LIBRARY_RESPONSIBILITY_CENTER', 'Responsibility Center');
	define('CODE_LIBRARY_UACS_OBJECT', 'UACS Object'); //jendaigo: uacs object var declaration

	//SYSTEMS CODE LIRARY;
	define('CODE_LIBRARY_SYSTEM_PARAMETER', 'System Parameter');
	define('CODE_LIBRARY_DOCUMENT_CHECKLIST', 'Checklist');
	define('CODE_LIBRARY_DROPDOWN', 'Dropdown');
	define('CODE_LIBRARY_SUPP_DOC_TYPE', 'Supporting Document');
	define('CODE_LIBRARY_SIGNATORIES', 'Signatories');


	define('CODE_LIBRARY_OFFICE', 'Office');
	define('CODE_LIBRARY_SALARY_GRADE_STEPS', 'Salary Grade & Steps');
	define('CODE_LIBRARY_DESIGNATION', 'Designation');
	define('CODE_LIBRARY_APPOINTMENT_TYPE', 'Appointment Type');
	define('CODE_LIBRARY_APPOINTMENT_STATUS', 'Appointment Status');
	define('CODE_LIBRARY_REQUEST_STATUS', 'Request Status');
	define('CODE_LIBRARY_VOUCHER', 'Voucher');
	define('CODE_LIBRARY_WORKFLOW', 'Workflow');

	define('STATUTORY_BIR', 'BIR');
	define('STATUTORY_PAGIBIG', 'Pag-ibig');
	define('STATUTORY_PHILHEALTH', 'Philhealth');
	define('STATUTORY_SSS','SSS');
	define('STATUTORY_BUDGET', 'Budget');
	define('STATUTORY_GSIS', 'GSIS');

	define('TAB_EMPLOYEE_LIST', 'Employee List');
	define('TAB_COMPENSATION_TYPE', 'Compensation Type');

	//ADHOC REPORTS TAB
	define('TAB_TABLE_GROUP', 'Table Group');
	define('TAB_DATA_DOWNLOAD', 'Data Download');

	/*
	|---------------------------------------------------------------------
	| Basic Input fields Icon
	|---------------------------------------------------------------------
	| THESE CONSTANTS ARE USED TO HIGHLIGHT THE ACTIVE MENU
	*/
	define('ICON_BENEFITS', 'card_giftcard');
	define('ICON_BANK', 'account_balance');
	define('ICON_TYPE', 'check_circle');
	/*

	/*
	|---------------------------------------------------------------------
	| PDS STATUS
	|---------------------------------------------------------------------
	| THESE CONSTANTS ARE USED TO HIGHLIGHT THE ACTIVE MENU
	*/
	define('PDS_FOR_APPROVAL', 1);
	define('PDS_PENDING', 2);
	define('PDS_APPROVED', 3);
	define('PDS_APPLICATION_STAGE', 4);

	/*
	|---------------------------------------------------------------------
	| DEFAULT ID VALUE
	|---------------------------------------------------------------------
	| This constant is used as the DEFAULT ID in security variables
	| (action, id token, salt).
	*/
	define('DEFAULT_ID', 0.1);

	/*
	 |---------------------------------------------------------------------
	 | CODE LIBRARY
	 |---------------------------------------------------------------------
	 | THESE CONSTANTS ARE USED TO HIGHLIGHT THE ACTIVE MENU
	 */
	define('CODE_LIBRARY_HUMAN_RESOURCES', "human_resources");
	define('CODE_LIBRARY_ATTENDANCE', "attendance");
	define('CODE_LIBRARY_PAYROLL', "payroll");
	define('CODE_LIBRARY_SYSTEM', "system");
	
	/*
	 |---------------------------------------------------------------------
	 | ADDRESS TYPES
	 |---------------------------------------------------------------------
	 | THESE CONSTANTS ARE USED FOR EMPLOYEE PDS ADDRESS
	 */
	define('RESIDENTIAL_ADDRESS', 1);
	define('PERMANENT_ADDRESS', 2);

	/*
	 |---------------------------------------------------------------------
	 | IDENTIFICATION TYPES
	 |---------------------------------------------------------------------
	 | THESE CONSTANTS ARE USED FOR EMPLOYEE PDS IDENTIFICATION
	 */
	define('TIN_TYPE_ID', 1);
	define('SSS_TYPE_ID', 2);
	define('GSIS_TYPE_ID', 3);
	define('PAGIBIG_TYPE_ID', 4);
	define('PHILHEALTH_TYPE_ID', 5);
	define('BANKACCT_TYPE_ID', 11);
	define('CRN_TYPE_ID', 12);
	
	/*
	 |---------------------------------------------------------------------
	 | CONTACT TYPES
	 |---------------------------------------------------------------------
	 | THESE CONSTANTS ARE USED FOR EMPLOYEE PDS CONTACT INFORMATION
	 */
	define('PERMANENT_NUMBER', 1);
	define('EMAIL', 2);
	define('RESIDENTIAL_NUMBER', 3);	
	define('MOBILE_NUMBER', 4);	
	define('MOBILE_NUMBER2', 5);	
	define('EMAIL2', 6);


	/*
	 |---------------------------------------------------------------------
	 | PDS OTHERS TAB TYPES
	 |---------------------------------------------------------------------
	 | THESE CONSTANTS ARE USED FOR EMPLOYEE PDS OTHERS TAB
	 */
	define('OTHER_SKILLS', 1);
	define('OTHER_RECOGNITION', 2);
	define('OTHER_ASSOCIATION', 3);	

	/*
	 |---------------------------------------------------------------------
	 | PDS EDUCATIONAL BACKGROUND/ EDUCATION LEVEL TYPES
	 |---------------------------------------------------------------------
	 | THESE CONSTANTS ARE USED FOR EMPLOYEE PDS EDUCATIONAL BACKGROUND
	 */
	define('LEVEL_ELEMENTARY', 17);
	define('LEVEL_SECONDARY', 18);
	define('LEVEL_COLLEGE', 19);	
	define('LEVEL_VOCATIONAL', 20);	
	define('LEVEL_GRADUATE', 21);	
	define('LEVEL_MASTERAL', 22);	
	define('LEVEL_DOCTORAL', 23);	
	define('LEVEL_OTHERS', 24);	

	/*
	 |---------------------------------------------------------------------
	 | PDS FAMILY RELATIONSHIPS TYPES
	 |---------------------------------------------------------------------
	 | THESE CONSTANTS ARE USED FOR ADDRESS
	 */
	define('FAMILY_FATHER', 1);
	define('FAMILY_MOTHER', 2);
	define('FAMILY_BROTHER', 3);
	define('FAMILY_SISTER', 4);
	define('FAMILY_GUARDIAN', 5);
	define('FAMILY_SPOUSE', 6);
	define('FAMILY_CHILD', 7);
	/*
	 |---------------------------------------------------------------------
	 | ELIGIBILITY TYPES
	 |---------------------------------------------------------------------
	 | THESE CONSTANTS ARE USED FOR EMPLOYEE PDS CIVIL SERVICE ELIGIBILITY
	 */
	define('BOARD_BAR_EXAM', 2);

	/*
	 |---------------------------------------------------------------------
	 | REQUEST TYPES
	 |---------------------------------------------------------------------
	 */
	define('REQUEST_PDS_RECORD_CHANGES', 1);
	define('REQUEST_LEAVE_APPLICATION', 2);
	define('REQUEST_CERTIFICATE_EMPLOYMENT', 3);
	define('REQUEST_SERVICE_RECORD', 4);
	define('REQUEST_CERTIFICATE_CONTRIBUTION', 5);
	define('REQUEST_MANUAL_ADJUSTMENT', 6);
	define('REQUEST_DEDUCTION_RECORD_CHANGES', 7);
	define('REQUEST_PAYSLIP', 8);
	/*
	 |---------------------------------------------------------------------
	 | REQUEST TYPES - WORKFLOWS
	 |---------------------------------------------------------------------
	 */
	define('REQUEST_WORKFLOW_PDS_RECORD_CHANGES', 1);
	define('REQUEST_WORKFLOW_LEAVE_APPLICATION', 2);
	define('REQUEST_WORKFLOW_CERTIFICATE_EMPLOYMENT', 3);
	define('REQUEST_WORKFLOW_SERVICE_RECORD', 4);
	define('REQUEST_WORKFLOW_CERTIFICATE_CONTRIBUTION', 5);
	define('REQUEST_WORKFLOW_MANUAL_ADJUSTMENT', 6);
	define('REQUEST_WORKFLOW_DEDUCTION_RECORD_CHANGES', 0);	
	define('REQUEST_WORKFLOW_PAYSLIP', 0);
	/*
	 |---------------------------------------------------------------------
	 | SUB-REQUEST TYPES
	 |---------------------------------------------------------------------
	 */

	define('TYPE_REQUEST_PDS_PERSONAL_INFO', 1);
	define('TYPE_REQUEST_PDS_IDENTIFICATION', 2);
	define('TYPE_REQUEST_PDS_ADDRESS_INFO', 3);
	define('TYPE_REQUEST_PDS_CONTACT_INFO', 4);
	define('TYPE_REQUEST_PDS_FAMILY_INFO', 5);
	define('TYPE_REQUEST_PDS_EDUCATION', 6);
	define('TYPE_REQUEST_PDS_ELIGIBILITY', 7);
	define('TYPE_REQUEST_PDS_WORK_EXPERIENCE', 8);
	define('TYPE_REQUEST_PDS_PROFESSION', 9);
	define('TYPE_REQUEST_PDS_VOLUNTARY_WORK', 10);
	define('TYPE_REQUEST_PDS_TRAININGS', 11);
	define('TYPE_REQUEST_PDS_OTHER_INFO', 12);
	define('TYPE_REQUEST_PDS_QUESTIONNAIRE', 13);
	define('TYPE_REQUEST_PDS_REFERENCES', 14);
	define('TYPE_REQUEST_PDS_DECLARATION', 15);
	define('TYPE_REQUEST_LA_COMMUTATION_REQUESTED', 16);
	define('TYPE_REQUEST_LA_COMMUTATION_NOT_REQUESTED', 17);
	define('TYPE_REQUEST_COE_WITH_BENEFITS_LIST', 18);
	define('TYPE_REQUEST_COE_WITHOUT_BENEFITS_LIST', 19);
	define('TYPE_REQUEST_COE_SERVICE_RECORD', 21);
	define('TYPE_REQUEST_LA_MONETIZATION', 27);

	
	define('TYPE_REQUEST_COC_GSIS', 22);
	define('TYPE_REQUEST_COC_PHILHEALTH', 23);
	define('TYPE_REQUEST_COC_PAGIBIG', 24);
	define('TYPE_REQUEST_COC_TAX_WITH_HELD', 25);
	define('TYPE_REQUEST_MANUAL_ADJUSTMENT', 26);
	define('TYPE_REQUEST_MANUAL_ADJUSTMENT_OFFICIAL_BUSINESS', 28);
/*
	define('TYPE_REQUEST_DRC_BIR', 1);
	define('TYPE_REQUEST_DRC_PHILHEALTH', 2);
	define('TYPE_REQUEST_DRC_PAGIBIG', 3);
	define('TYPE_REQUEST_DRC_GSIS', 4);
	define('TYPE_REQUEST_COE_WORK_EXPERIENCE', 11);
	define('TYPE_REQUEST_PAYSLIP', 12);*/
	
	/*
	 |---------------------------------------------------------------------
	 | REQUEST STATUS
	 |---------------------------------------------------------------------
	 */
	define('REQUEST_NEW', 1);
	define('REQUEST_PENDING', 2);
	define('REQUEST_ONGOING', 3);
	define('REQUEST_CANCELLED', 4);
	define('REQUEST_APPROVED', 5);
	define('REQUEST_REJECTED', 6);

	/*
	 |---------------------------------------------------------------------
	 | SUB REQUEST STATUS
	 |---------------------------------------------------------------------
	 */
	define('SUB_REQUEST_NEW', 1);
	define('SUB_REQUEST_APPROVED', 2);
	define('SUB_REQUEST_REJECTED', 3);
	/*
	 |---------------------------------------------------------------------
	 | TASK STATUS
	 |---------------------------------------------------------------------
	 */
	define('TASK_NOT_YET_STARTED', 1);
	define('TASK_ONGOING', 2);
	define('TASK_DONE', 3);
	/*
	 |---------------------------------------------------------------------
	 | LEAVE TYPES
	 |---------------------------------------------------------------------
	 */
	define('LEAVE_TYPE_NA', 0);
	define('LEAVE_TYPE_SICK', 1);
	define('LEAVE_TYPE_VACATION', 2);
	define('LEAVE_TYPE_MATERNITY', 3);
	define('LEAVE_TYPE_PATERNITY', 4);
	define('LEAVE_TYPE_STUDY', 5);
	define('LEAVE_TYPE_SINGLE_PARENT', 6);
	define('LEAVE_TYPE_FORCED', 7);
	define('LEAVE_TYPE_SPECIAL_PRIVILEGE', 8);
	define('LEAVE_TYPE_SPECIAL_EMERGENCY_CALAMITY', 11);
	define('LEAVE_TYPE_SPECIAL_BENEFITS_WOMEN', 12);
	define('LEAVE_TYPE_VAWC', 14);
	define('LEAVE_TYPE_REHABILITATION_PRIVILEGE', 15);
	define('LEAVE_TYPE_ADOPTION', 16);
	/*
	 |---------------------------------------------------------------------
	 | LEAVE TRANSACTION TYPES
	 |---------------------------------------------------------------------
	 */
	define('LEAVE_INITIAL_BALANCE', 1);
	define('LEAVE_CREDIT_LEAVE', 2);
	define('LEAVE_REVERSE_LEAVE', 3);
	define('LEAVE_FILE_LEAVE', 4);
	define('LEAVE_COMMUTATION', 5);
	define('LEAVE_DEDUCTION', 6);
	/*
	 |---------------------------------------------------------------------
	 | CHECK LIST TYPES
	 |---------------------------------------------------------------------
	 */
	define('CHECKLIST_PDS', 1);/*
	 |---------------------------------------------------------------------
	 | LEAVE INTERVALS
	 |---------------------------------------------------------------------
	 */
	define('LEAVE_WHOLE_DAY', 1);
	define('LEAVE_TWO_HOUR_MORNING', 2);
	define('LEAVE_TWO_HOUR_AFTERNOON', 3);
	define('LEAVE_HALF_DAY_MORNING', 4);
	define('LEAVE_HALF_DAY_AFTERNOON', 5);
	define('LEAVE_THREE_QUARTER_MORNING', 6);
	define('LEAVE_THREE_QUARTER_AFTERNOON', 7);
	/*
	 |---------------------------------------------------------------------
	 | ATTENDANCE PERIOD STATUS
	 |---------------------------------------------------------------------
	 */
	define('ATTENDANCE_PERIOD_PROCESSING', 1);
	define('ATTENDANCE_PERIOD_PROCESSED', 2);
	define('ATTENDANCE_PERIOD_COMPLETED', 3);	
	/*
	 |---------------------------------------------------------------------
	 | ATTENDANCE PERIOD DETAIL STATUS
	 |---------------------------------------------------------------------
	 */
	define('ATTENDANCE_STATUS_REGULAR_DAY', 1);
	define('ATTENDANCE_STATUS_REST_DAY', 2);
	define('ATTENDANCE_STATUS_LEAVE_WP', 3);
	define('ATTENDANCE_STATUS_LEAVE_WOP', 4);
	define('ATTENDANCE_STATUS_ABSENT', 5);
	define('ATTENDANCE_STATUS_REGULAR_HOLIDAY', 6);
	define('ATTENDANCE_STATUS_SPECIAL_HOLIDAY', 7);
	define('ATTENDANCE_STATUS_LEAVE_HD_WP', 8);
	define('ATTENDANCE_STATUS_LEAVE_HD_WOP', 9);
	/*
	 |---------------------------------------------------------------------
	 | SYSTEM CODES
	 |---------------------------------------------------------------------
	 */
	define('CODE_HR', 'HR');
	define('CODE_PAYROLL', 'PAYROLL');
	define('CODE_PORTAL', 'PORTAL');
	define('CODE_SYSAD', 'SYSAD');
	define('CODE_TA', 'TA');

	/*
	 |---------------------------------------------------------------------
	 | SYSTEM CODES
	 |---------------------------------------------------------------------
	 */
	define('PREPARED_BY', 'PREPARED_BY');
	define('CERTIFIED_BY', 'CERTIFIED_BY');
	define('APPROVED_BY', 'APPROVED_BY');
	define('CASH_AVAILABLE_BY', 'CASH_AVAILABLE_BY');
	
	/*
	 |---------------------------------------------------------------------
	 | SYSTEM CODES
	 |---------------------------------------------------------------------
	 */
	define('REGULAR_PERMANENT', 'R');
	define('JOB_ORDER', 'C');
	/*
	|---------------------------------------------------------------------
	| AUDIT TRAIL ACTIONS
	|---------------------------------------------------------------------
	| These constants are used when defining the action made in the process
	*/
	define('AUDIT_INSERT', 'INSERT');
	define('AUDIT_UPDATE', 'UPDATE');
	define('AUDIT_DELETE', 'DELETE');
	define('AUDIT_PROCESS', 'PROCESS');

	/*
	|---------------------------------------------------------------------
	| GENDER
	|---------------------------------------------------------------------
	*/
	define('FEMALE', 'F');
	define('MALE', 'M');

	/*
	|---------------------------------------------------------------------
	| ANONYMOUS ACCOUNT
	|---------------------------------------------------------------------
	| This anonymous account is used as the default user_id in the audit
	| trail when a guest manually registers in the system
	*/
	define('ANONYMOUS_ID', 0);
	define('ANONYMOUS_USERNAME', 'anonymous');

	/*
	|---------------------------------------------------------------------
	| ACCOUNT CREATOR
	|---------------------------------------------------------------------
	*/
	define('ADMINISTRATOR', 'ADMINISTRATOR');
	define('VISITOR', 'VISITOR');

	/*
	|---------------------------------------------------------------------
	| LOGIN VIA
	|---------------------------------------------------------------------
	| These constants are used for identifying which method is used
	| when logging in the system
	*/
	define('VIA_USERNAME', 'USERNAME');
	define('VIA_EMAIL', 'EMAIL');
	define('VIA_USERNAME_EMAIL', 'USERNAME_EMAIL');

	/*
	|---------------------------------------------------------------------
	| MODAL EFFECT
	|---------------------------------------------------------------------
	| Define the default transition when opening the modal window.
	| See http://tympanus.net/Development/ModalWindowEffects/ for demo
	| 1 - Fade In and Scale
	| 2 - Slide In (Right)
	| 3 - Slide In (Bottom)
	| 4 - Newspaper
	| 5 - Fall
	| 6 - Side Fall
	| 7 - Sticky Up
	| 8 - 3D Flip (Horizontal)
	| 9 - 3D Flip (Vertical)
	| 10 - 3D Sign
	| 11 - Superscaled
	| 12 - Just Me
	| 13 - 3D Slit
	| 14 - 3D Rotate Bottom
	| 15 - 3D Rotate in Left
	| 16 - Blur
	| 17 - Let Me In
	| 18 - Make Way
	| 19 - Slid from Top
	*/
	define('MODAL_EFFECT', 1);

	/*
	|---------------------------------------------------------------------
	| TYPE OF EXPORTED FILE
	|---------------------------------------------------------------------
	| These constants are used to indicate the type of file to be exported
	*/
	define('EXPORT_PDF', 'pdf');
	define('EXPORT_EXCEL', 'xls');
	define('EXPORT_DOCUMENT', 'doc');

	/*
	|---------------------------------------------------------------------
	| TYPE OF SYSTEM PARAMETERS
	|---------------------------------------------------------------------
	| These parameters are used when working with system emails
	*/
	define('SYS_PARAM_TYPE_SMTP', 'SMTP');

	/*
	|---------------------------------------------------------------------
	| LIMIT OF ITEMS SHOWN AND USED BY JSCROLL
	|---------------------------------------------------------------------
	| Control the default number of items to be shown before loading the
	| next set of content
	*/
	define('ITEM_LIMIT', 5);

	/*
	|---------------------------------------------------------------------
	| NODE JS SERVER PATH : NEEDED FOR LINUX ONLY
	|---------------------------------------------------------------------
	*/
	if(ISSET($_SERVER['HTTP_HOST'])) // for CLI Request
		define('NODEJS_SERVER', 'http://'.$_SERVER['HTTP_HOST'].':8000/');

/*
|--------------------------------------------------------------------------
| CHANGE ALL CONSTANTS BELOW DEPENDING ON THE PROJECT
|--------------------------------------------------------------------------
*/
	/*
	|---------------------------------------------------------------------
	| PROJECT NAME
	|---------------------------------------------------------------------
	| Used for encryption_key and sess_cookie_name. Naming of constants
	| should contain the project or company name and avoid long values
	| with spaces.
	*/
	define('PROJECT_NAME', 'PTIS');
	define('PROJECT_CODE', 'doh_ptis');

	/*
	|---------------------------------------------------------------------
	| PROJECTS
	|---------------------------------------------------------------------
	| Define all project names included in your system.
	*/
	define('SYSAD', 'SYSAD');
	define('MAIN', 'MAIN');
	/*
	|---------------------------------------------------------------------
	| PROJECT FOLDER
	|---------------------------------------------------------------------
	| These constants are used when defining the project folder path of
	| your controller in the hyperlink or function.
	*/
	define('PROJECT_CORE', 'sysad');
	define('PROJECT_MAIN', 'main');
	/*
	|---------------------------------------------------------------------
	| SYSTEMS DATABASE
	|---------------------------------------------------------------------
	| Define the name of database/s used in the system.
	*/
	define('DB_CORE', PROJECT_CODE . '_core');
	define('DB_MAIN', PROJECT_CODE . '_module');
	/*
	|---------------------------------------------------------------------
	| HOMEPAGE
	|---------------------------------------------------------------------
	| Define the landing page after login.
	| Note that every project can have its own homepage.
	*/
	define('HOME_PAGE', PROJECT_MAIN.'/dashboard/get_dashboard/PORTAL');

	/*
	|---------------------------------------------------------------------
	| PROJECT MODULES
	|---------------------------------------------------------------------
	| Declare all the modules in your project.
	*/
		define('MODULE_DASHBOARD', 1);
		define('MODULE_PERSONNEL_PORTAL', 2);
		define('MODULE_PORTAL_MY_REQUESTS', 3);
		define('MODULE_PORTAL_PERSONAL_DATA_SHEET', 4);
		define('MODULE_PORTAL_PERFORMANCE_EVALUATION', 5);
		define('MODULE_PORTAL_COMPENSATION', 6);
		define('MODULE_PORTAL_DEDUCTIONS', 7);
		define('MODULE_PORTAL_DAILY_TIME_RECORD', 8);
		define('MODULE_HUMAN_RESOURCES', 9);
		define('MODULE_HR_PERSONAL_DATA_SHEET', 10);
		define('MODULE_HR_PERFORMANCE_EVALUATION', 11);
		define('MODULE_HR_COMPENSATION', 12);
		define('MODULE_HR_DEDUCTIONS', 13);
		define('MODULE_HR_CODE_LIBRARY', 14);
		define('MODULE_HR_CL_ACADEMIC_HONORS', 15);
		define('MODULE_HR_CL_COMPENSATION_TYPE', 16);
		define('MODULE_HR_CL_DEDUCTION_TYPE', 17);
		define('MODULE_HR_CL_EDUCATIONAL_DEGREE', 18);
		define('MODULE_HR_CL_ELIGIBILITY', 19);
		define('MODULE_HR_CL_EMPLOYMENT_STATUS', 20);
		define('MODULE_HR_CL_GOVERNMENT_BRANCH', 21);
		define('MODULE_HR_CL_MODE_SEPARATION', 22);
		define('MODULE_HR_CL_PERSONNEL_MOVEMOVENT', 23);
		define('MODULE_HR_CL_EDUCATIONAL_LEVEL', 24);
		define('MODULE_HR_CL_PLANTILLA', 25);
		define('MODULE_HR_CL_POSITION', 26);
		define('MODULE_HR_CL_SALARY_SCHEDULE', 27);
		define('MODULE_HR_CL_SCHOOL', 28);
		define('MODULE_HR_CL_TRAINING', 29);
		define('MODULE_HR_REPORTS', 30);
		define('MODULE_HR_REPORT_SERVICE_RECORD', 31);
		define('MODULE_HR_REPORT_APPOINTMENT_CERTIFICATE', 32);
		define('MODULE_HR_REPORT_ASSUMPTION_TO_DUTY', 1010);//NCOCAMPO 01/11/2024
		define('MODULE_HR_REPORT_LNE_OFFICE', 33);
		define('MODULE_HR_REPORT_LNE_POSITION_TITLE', 34);
		define('MODULE_HR_REPORT_LNE_CLASS', 35);
		define('MODULE_HR_REPORT_LNE_SALARY_GRADE', 36);
		define('MODULE_HR_REPORT_LNE_BIRTH_DATES', 37);
		define('MODULE_HR_REPORT_LNE_AGE', 38);
		define('MODULE_HR_REPORT_LNE_GENDER', 39);
		define('MODULE_HR_REPORT_LNE_PROFESSION', 40);
		define('MODULE_HR_REPORT_LNE_EMPLOYMENT_STATUS', 41);
		define('MODULE_HR_REPORT_LNE_ENTITLEMENT_LONGEVITY', 42);
		define('MODULE_HR_REPORT_LNE_ENTITLEMENT_LOYALTY', 43);
		define('MODULE_HR_REPORT_LNE_LENGTH_SERVICE', 44);
		define('MODULE_HR_REPORT_MR_ACCESSION', 45);
		define('MODULE_HR_REPORT_MR_SEPARATION', 46);
		define('MODULE_HR_REPORT_PSIPOP', 47);
		define('MODULE_HR_REPORT_PERSONNEL_MOVEMENT', 48);
		define('MODULE_TIME_AND_ATTENDANCE', 49);
		define('MODULE_TA_ATTENDANCE_LOGS', 50);
		define('MODULE_TA_DAILY_TIME_RECORD',51);
		define('MODULE_TA_ATTENDANCE_PERIOD', 52);
		define('MODULE_TA_LEAVES', 53);
		define('MODULE_TA_CODE_LIBRARY', 54);
		define('MODULE_TA_LEAVE_TYPE', 55);
		define('MODULE_TA_WORK_CALENDAR', 56);
		define('MODULE_TA_HOLIDAY_TYPE', 57);
		define('MODULE_TA_COMPUTATION_TABLE', 1009); //davcorrea 10/10/2023 added modules for code library
		define('MODULE_TA_REPORTS', 58);
		define('MODULE_TA_LEAVE_CARD', 59);
		define('MODULE_TA_MR_ATTENDANCE', 60);
		define('MODULE_PAYROLL', 61);
		define('MODULE_PAYROLL_PERSONNEL_BANK_ACCOUNTS', 62);
		define('MODULE_PAYROLL_GENERAL_PAYROLL', 63);
		define('MODULE_PAYROLL_SPECIAL_PAYROLL', 64);
		define('MODULE_PAYROLL_VOUCHER', 65);
		define('MODULE_PAYROLL_REMITTANCE', 66);
		define('MODULE_PAYROLL_CODE_LIBRARY', 67);
		define('MODULE_PAYROLL_CL_BANK_BRANCH', 68);
		define('MODULE_PAYROLL_CL_BIR_TABLE', 69);
		define('MODULE_PAYROLL_CL_GSIS', 70);
		define('MODULE_PAYROLL_CL_PHILHEALTH', 71);
		define('MODULE_PAYROLL_CL_PAGIBIG', 72);
		define('MODULE_PAYROLL_CL_SSS', 1002);
		define('MODULE_PAYROLL_CL_FUND_SOURCE', 73);
		define('MODULE_PAYROLL_CL_REMITTANCE_TYPE', 74);
		define('MODULE_PAYROLL_CL_REMITTANCE_PAYEE', 182);
		define('MODULE_PAYROLL_CL_RESPONSIBILITY_CENTER', 1007);
		define('MODULE_PAYROLL_CL_UACS_OBJECT', 1008); //jendaigo: uacs object id declaration
		define('MODULE_PAYROLL_REPORTS', 75);
		define('MODULE_PAYROLL_REPORT_GENERAL_PAYROLL_REPORTS', 76);
		define('MODULE_PAYROLL_REPORT_GENERAL_COVER_SHEET', 77);
		define('MODULE_PAYROLL_REPORT_GENERAL_SUMMARY_GRAND_TOTAL', 78);
		define('MODULE_PAYROLL_REPORT_GENERAL_SUMMARY_PER_OFFICE', 79);
		define('MODULE_PAYROLL_REPORT_GENERAL_ALPHA_LIST_PER_OFFICE', 80);
		define('MODULE_PAYROLL_REPORT_SPECIAL_PAYROLL_REPORTS', 81);
		define('MODULE_PAYROLL_REPORT_SPECIAL_COVER_SHEET', 82);
		define('MODULE_PAYROLL_REPORT_SPECIAL_SUMMARY_GRAND_TOTAL', 83);
		define('MODULE_PAYROLL_REPORT_SPECIAL_SUMMARY_PER_OFFICE', 84);
		define('MODULE_PAYROLL_REPORT_SPECIAL_ALPHA_LIST_PER_OFFICE', 85);
		define('MODULE_PAYROLL_REPORT_PAYSLIPS', 86);
		define('MODULE_PAYROLL_REPORT_GENERAL_PAYSLIP_EMPLOYEES', 87);
		define('MODULE_PAYROLL_REPORT_GENERAL_PAYSLIP_JOB_ORDER', 88);
		define('MODULE_PAYROLL_REPORT_SPECIAL_PAYSLIP_EMPLOYEES', 89);
		define('MODULE_PAYROLL_REPORT_SPECIAL_PAYSLIP_JOB_ORDER', 90);
		define('MODULE_PAYROLL_REPORT_BANK_REPORTS', 91);
		define('MODULE_PAYROLL_REPORT_PAYROLL_REGISTER', 92);
		define('MODULE_PAYROLL_REPORT_BANK_PAYROLL_ATM_ALPHA_LIST', 93);
		define('MODULE_PAYROLL_REPORT_BANK_PAYROLL_ATM_ALPHA_LIST2', 1007);
		define('MODULE_PAYROLL_REPORT_REMITTANCE_REPORTS', 94);
		define('MODULE_PAYROLL_REPORT_REMITTANCE_SUMMARY_GRAND_TOTAL', 95);
		define('MODULE_PAYROLL_REPORT_REMITTANCE_SUMMARY_OFFICE', 96);
		define('MODULE_PAYROLL_REPORT_REMITTANCE_LIST_OFFICE', 97);
		define('MODULE_PAYROLL_REPORT_CONSOLIDATED_REMITTANCE_SUMMARY_OFFICE', 98);
		define('MODULE_PAYROLL_REPORT_CONSOLIDATED_REMITTANCE_LIST_OFFICE', 99);
		define('MODULE_PAYROLL_REPORT_REMITTANCE_FILES_UPLOADING', 100);
		define('MODULE_PAYROLL_REPORT_RFU_GSIS_CONTRIBUTIONS', 101);
		define('MODULE_PAYROLL_REPORT_RFU_PHILHEALTH_CONTRIBUTIONS', 102);
		define('MODULE_PAYROLL_REPORT_RFU_PAGIBIG_CONTRIBUTIONS', 103);
		define('MODULE_PAYROLL_REPORT_RFU_BIR_TAX_PAYMENTS', 104);
		define('MODULE_PAYROLL_REPORT_CERTIFICATE_CONTRIBUTIONS', 105);
		define('MODULE_PAYROLL_REPORT_COC_GSIS', 106);
		define('MODULE_PAYROLL_REPORT_COC_PHILHEALTH', 107);
		define('MODULE_PAYROLL_REPORT_COC_PAGIBIG', 108);
		define('MODULE_PAYROLL_REPORT_BIR_REPORTS', 109);
		define('MODULE_PAYROLL_REPORT_BIR_1601_C', 110);
		define('MODULE_PAYROLL_REPORT_BIR_2316', 111);
		define('MODULE_PAYROLL_REPORT_BIR_ALPHALIST', 112);
		define('MODULE_PAYROLL_REPORT_BIR_ALPHALIST_PREVIOUS_EMPLOYER', 113);
		define('MODULE_PAYROLL_REPORT_BIR_ALPHALIST_TERMINATED', 114);
		define('MODULE_PAYROLL_REPORT_BIR_ALPHALIST_MINIMUM_WAGE', 115);
		define('MODULE_PAYROLL_REPORT_YEAREND_ADJUSTMENT_REPORT_OFFICE', 116);
		define('MODULE_PAYROLL_REPORT_ENGAS_FILE_FOR_UPLOADING', 117);
		define('MODULE_PAYROLL_REPORT_BIR_2307', 180);
		define('MODULE_PAYROLL_REPORT_EMPLOYEES_PAID_BY_VOUCHER', 181);
		define('MODULE_PAYROLL_CL_COMPENSATION_TYPE', 184);
		define('MODULE_PAYROLL_CL_DEDUCTION_TYPE', 185);
		define('MODULE_PAYROLL_REPORT_BIR_2306', 183);
		define('MODULE_PAYROLL_REPORT_EMPLOYEES_NOT_INCLUDED_IN_PAYROLL', 186);
		define('MODULE_PAYROLL_REPORT_COOP_REMITTANCE', 1000);
		define('MODULE_PAYROLL_REPORT_BIR_2305', 1001);
		define('MODULE_PAYROLL_REPORT_RESPONSIBILITY_CODE_PER_OFFICE', 1004);
		define('MODULE_PAYROLL_REPORT_GENERAL_PAYROLL_ALPHALIST_FOR_JO', 1003);
		define('MODULE_PAYROLL_REPORT_EXPANDED_WITHHOLDING_TAX_AND_GOVERNMENT_MONEY_PAYMENT', 1005);
		define('MODULE_USER_MANAGEMENT', 118);
		define('MODULE_USER', 119);
		define('MODULE_ROLE', 120);
		define('MODULE_PERMISSION', 121);
		define('MODULE_ORGANIZATION', 122);
		define('MODULE_AUDI_TRAIL', 123);
		define('MODULE_SYSTEM', 124);
		define('MODULE_REQUESTS_APPROVALS', 125);
		define('MODULE_FILE', 126);
		define('MODULE_ADHOC_REPORTS', 127);
		define('MODULE_CODE_LIBRARY', 128);
		define('MODULE_WORKFTLOW', 129);
		define('MODULE_SYSTEM_CL_CHECKLISTS', 130);
		define('MODULE_SYSTEM_CL_DROPDOWN', 130);
		define('MODULE_SYSTEM_CL_SUPPORTING_DOCUMENT', 130);
		define('MODULE_SYSTEM_CL_SIGNATORIES', 187);
		define('MODULE_SYSTEM_CL_SYSTEM_PARAMETER', 131);
		define('MODULE_TA_WORK_SCHEDULE', 132);
		define('MODULE_TA_REPORTS_DAILY_TIME_RECORD', 133);
		define('MODULE_TA_REPORTS_LEAVE_APPLICATION', 134);
		define('MODULE_TA_REPORTS_LEAVE_AVAILMENT', 135);
		define('MODULE_TA_REPORTS_LEAVE_CREDIT_CERT', 136);
		define('MODULE_TA_REPORTS_LEAVE_WITHOUT_PAY_CERT', 137);	
			
		define('MODULE_HR_REPORT_LNE_BENEFIT_ENTITLEMENT', 151);
		define('MODULE_HR_REPORT_LNE_POSITION_LEVEL', 152);
		define('MODULE_HR_REPORT_LNE_DROPPED_FROM_ROLL', 153);
		define('MODULE_HR_REPORT_LNE_PROMOTED', 154);
		define('MODULE_HR_REPORT_LNE_RESIGNED', 155);
		define('MODULE_HR_REPORT_LNE_RETIRED', 156);
		define('MODULE_HR_REPORT_FILLED_UNFILLED_POSITIONS', 157);
		define('MODULE_HR_REPORT_PDS', 158);
		define('MODULE_HR_REPORT_RAI_I', 159);
		define('MODULE_HR_REPORT_RAI_II', 160);
		define('MODULE_HR_REPORT_TRANSFEREE', 161);
		define('MODULE_HR_REPORT_PRIME_HRM', 162);
		define('MODULE_HR_REPORT_ENTITLEMENT_LONGEVITY_PAY', 163);
		define('MODULE_HR_REPORT_NOTICE_SALARY_ADJUSTMENT', 164);
		define('MODULE_HR_REPORT_NOTICE_SALARY_ADJUSTMENT_COMPULSORY_RETIREMENT', 1012);
		define('MODULE_HR_REPORT_NOTICE_SALARY_STEP_INCREMENT', 165);
		define('MODULE_HR_REPORT_NOTICE_STEP_INCREMENT', 166);
		define('MODULE_HR_REPORT_NOTICE_LONGEVITY_PAY', 167);
		define('MODULE_HR_REPORT_NOTICE_LONGEVITY_PAY_INCREASE', 168);
		define('MODULE_HR_REPORT_GSIS_CERTIFICATE_CONTRIBUTION', 169);
		define('MODULE_HR_REPORT_PHILHEALTH_CERTIFICATE_CONTRIBUTION', 170);
		define('MODULE_HR_REPORT_PAGIBIG_CERTIFICATE_CONTRIBUTION', 171);
		define('MODULE_HR_REPORT_GSIS_MEMBERSHIP_FORM', 172);
		define('MODULE_HR_REPORT_PAGIBIG_MEMBERSHIP_FORM', 173);
		define('MODULE_HR_REPORT_PHILHEALTH_MEMBERSHIP_FORM', 174);
		define('MODULE_HR_REPORT_COE_WITH_COMPENSATIONS', 175);
		define('MODULE_HR_REPORT_COE_WITHOUT_COMPENSATIONS', 176);
		define('MODULE_HR_REPORT_NDHRHIS_UPLOAD', 177);
		
		// SETTINGS
		define('MODULE_SYSTEM_ACCOUNT_SETTINGS', 999);
	/*
	 |---------------------------------------------------------------------
	 | PASSWORD CONSTRAINTS
	 |---------------------------------------------------------------------
	 |
	 */

	define('PASS_CONS_DIGIT', 'constraint_digit');
	define('PASS_CONS_HISTORY', 'constraint_history');
	define('PASS_CONS_LENGTH', 'constraint_length');
	define('PASS_CONS_UPPERCASE', 'constraint_uppercase');
	define('PASS_CONS_LOWERCASE', 'constraint_lowercase');
	define('PASS_CONS_SYMBOL', 'constraint_symbols');


	/*
	 |---------------------------------------------------------------------
	 | PASSWORD EXPIRY
	 |---------------------------------------------------------------------
	 |
	 */
	define('PASS_EXP_DURATION', 'password_duration');
	define('PASS_EXP_EXPIRY', 'password_expiry');
	define('PASS_EXP_REMINDER', 'password_reminder');


	/*
	 |---------------------------------------------------------------------
	 | PASSWORD EXPIRY
	 |---------------------------------------------------------------------
	 |
	 */

	 define('Y', 'Active');
	 define('N', 'Inactive');

	 define('YES', 'Y');
	 define('NO', 'N');
	 define('NA', 'NA');
	 
	 //CONSTANT BIOMETRIC FILE STATUS
	 define('BIOMETRIC_RAW_DATA', 1);
	 define('BIOMETRIC_PROCESSED', 2);
	 
	 //CONSTANT ATTENDANCE STATUS
	 define('REGULAR_DAY', 1);
	 define('REST_DAY', 2);
	 define('LEAVE_WP', 3);
	 define('LEAVE_WOP', 4);
	 define('ABSENT', 5);
	 define('REGULAR_HOLIDAY', 6);
	 define('SPECIAL_HOLIDAY', 7);
	 define('LEAVE_HD_WP', 8);
	 define('LEAVE_HD_WOP', 9);
	 define('OFFICIAL_BUSINESS', 10);

	 //CONSTANT EMPLOYMENT TYPE
	 define('EMPLOYMENT_PERMANENT', 1);
	 define('EMPLOYMENT_CONTRACTUAL', 2);

	 //RATE
	 define('OVERTIME_RATE', 1);
	 define('NIGHT_DIFF_RATE', 2);
	 define('REGULAR_HOLIDAY_RATE', 3);
	 define('SPECIAL_HOLIDAY_RATE', 4);
	 define('DAY_OFF_RATE', 5);
	 define('DAY_OFF_OT_RATE', 6);
	 define('SPECIAL_HOLIDAY_OT_RATE', 7);
	 define('REGULAR_HOLIDAY_OT_RATE', 8);
	 
	 
	 //COMPENSATION TYPE FLAG
	 define('COMPENSATION_TYPE_FLAG_FIXED', 'F');
	 define('COMPENSATION_TYPE_FLAG_VARIABLE', 'V');
	 define('COMPENSATION_TYPE_FLAG_SYSTEM', 'S');
	 
	 //DEDUCTION TYPE FLAG
	 define('DEDUCTION_TYPE_FLAG_FIXED', 'F');
	 define('DEDUCTION_TYPE_FLAG_VARIABLE', 'V');
	 define('DEDUCTION_TYPE_FLAG_SCHEDULED', 'S');
	 define('DEDUCTION_TYPE_FLAG_SYSTEM', 'ST');

	 // OTHER DEDUCTION DETAILS
	 define('DEDUCTION_DETAIL_DROPDOWN', 'DR');
	 define('DEDUCTION_DETAIL_CHAR', 'C');
	 define('DEDUCTION_DETAIL_NUMBER', 'N');	 
	 define('DEDUCTION_DETAIL_DATE', 'D');
	 define('DEDUCTION_DETAIL_YES_NO', 'YN');
	 
	 //PAYOUT TYPE FLAG
	 define('PAYOUT_TYPE_FLAG_REGULAR', 'R');
	 define('PAYOUT_TYPE_FLAG_SPECIAL', 'S');
	 define('PAYOUT_TYPE_FLAG_VOUCHER', 'V');
	 define('ONE_HUNDRED', 100);
	 
	 // STATUS
	 define('PAYOUT_STATUS_FOR_PROCESSING', 1);
	 define('PAYOUT_STATUS_FOR_REVIEW', 2);
	 define('PAYOUT_STATUS_FOR_APPROVAL', 3);
	 define('PAYOUT_STATUS_APPROVED', 4);
	 define('PAYOUT_STATUS_PAID', 5);
	 define('PAYOUT_STATUS_RETURN', 6);
	 // STATUS
	 define('VOUCHER_STATUS_FOR_PROCESSING', 1);
	 define('VOUCHER_STATUS_PROCESSED', 2);
	 define('VOUCHER_STATUS_PAID', 3);

	 // DASHBOARD USER ROLES
	 define('USER_ROLES', 0);
	 
	 // MULTIPLIER
	 define('MULTIPLIER_BASIC_SALARY', 1);
	 define('MULTIPLIER_TAXABLE_INCOME', 2);
	 
	 // SYS_PARAM TYPE
	 define('PARAM_EMPLOY_TYPE_WITH_TENURE', 'EMPLOY_TYPE_WITH_TENURE');
	 define('PARAM_EMPLOY_TYPE_WITH_TENURE_OG', 'EMPLOY_TYPE_WITH_TENURE_OG');
	 define('PARAM_ATTENDANCE_STAT_WITH_TENURE', 'ATTENDANCE_STAT_WITH_TENURE');
	 define('PARAM_WORKING_DAYS', 'WORKING_DAYS');
	 define('PARAM_WORKING_HOURS', 'WORKING_HOURS');
	 define('PARAM_WORKING_MONTHS', 'WORKING_MONTHS');
	 define('PARAM_COMPENSATION_BASIC_SALARY', 'COMPENSATION_BASIC_SALARY');
	 define('PARAM_COMPENSATION_BASIC_SALARY_PREMIUM', 'COMPENSATION_BASIC_SALARY_PREMIUM'); //jendaigo: code for compensation basic salary premium
	 define('PARAM_COMPENSATION_LONGEVITY_PAY', 'COMPENSATION_LONGEVITY_PAY');
	 define('PARAM_COMPENSATION_LOYALTY', 'COMPENSATION_LOYALTY');
	 define('PARAM_COMPENSATION_BASIC_SALARY_DEDUCTION', 'COMPENSATION_BASIC_SALARY_DEDUCTION');
	 define('PARAM_COMPENSATION_PREMIUM', 'COMPENSATION_PREMIUM'); //jendaigo: code for compensation premium
	 
	 define('PARAM_COMPENSATION_ID_BASIC_SALARY', 'COMPENSATION_ID_BASIC_SALARY');
	 define('PARAM_COMPENSATION_ID_BASIC_SALARY_ADJUSTMENT', 'COMPENSATION_ID_BASIC_SALARY_ADJUSTMENT');
	 define('PARAM_COMPENSATION_ID_BASIC_SALARY_DEDUCTIONS', 'COMPENSATION_ID_BASIC_SALARY_DEDUCTIONS');
	 define('PARAM_COMPENSATION_ID_PREMIUM', 'COMPENSATION_ID_PREMIUM'); //jendaigo: id for compensation premium
	 
	 define('PARAM_AMOUNT_TAXABLE_BENEFIT_CAP', 'AMOUNT_TAXABLE_BENEFIT_CAP');
	 define('PARAM_AMOUNT_PERSONAL_EXEMPTION', 'AMOUNT_PERSONAL_EXEMPTION'); 
	 define('PARAM_AMOUNT_PERSONAL_EXEMPTION_DEPENDENT', 'AMOUNT_PERSONAL_EXEMPTION_DEPENDENT');
	 define('PARAM_NUM_PERSONAL_EXEMPTION_DEPENDENT', 'NUM_PERSONAL_EXEMPTION_DEPENDENT');
	 define('PARAM_FREQUENCY_ONE_TIME_ONLY', 'FREQUENCY_ONE_TIME_ONLY');
	 define('PARAM_MONETIZATION_FACTOR', 'MONETIZATION_FACTOR');
	 define('PARAM_TAX_WITHHELD_PREV_EMPLOYER', 'TAX_WITHHELD_PREV_EMPLOYER');
	 define('PARAM_GROSS_TAXABLE_INCOME_PREV_EMPLOYER', 'GROSS_TAXABLE_INCOME_PREV_EMPLOYER');
	 define('PARAM_PAYROLL_STAT_APPROVED', 'PAYROLL_STAT_APPROVED');
	 define('PARAM_DOH_CENTRAL_OFFICE', 'DOH_CENTRAL_OFFICE');
	 define('PARAM_DOH', 'DOH');
	 
	 define('PARAM_COMPENSATION_LONGEVITY_PAY_RATE', 'COMPENSATION_LONGEVITY_PAY_RATE');
	 define('PARAM_COMPENSATION_LONGEVITY_MIN_TENURE', 'COMPENSATION_LONGEVITY_MIN_TENURE');
	 
	 define('PARAM_DEDUCTION_ST_BIR', 'DEDUCTION_ST_BIR');
	 define('PARAM_DEDUCTION_ST_GSIS', 'DEDUCTION_ST_GSIS');
	 define('PARAM_DEDUCTION_ST_PAGIBIG', 'DEDUCTION_ST_PAGIBIG');
	 define('PARAM_DEDUCTION_ST_PHILHEALTH', 'DEDUCTION_ST_PHILHEALTH');
	 define('PARAM_DEDUCTION_ST_BIR_ID', 'DEDUCTION_ST_BIR_ID');
	 
	 define('PARAM_DEDUCTION_ST_BIR_EWT', 'DEDUCTION_ST_BIR_EWT');
	 define('PARAM_DEDUCTION_ST_BIR_EWT_ID', 'DEDUCTION_ST_BIR_EWT_ID');
	 define('PARAM_DEDUCTION_ST_BIR_VAT', 'DEDUCTION_ST_BIR_VAT');
	 define('PARAM_DEDUCTION_ST_BIR_VAT_ID', 'DEDUCTION_ST_BIR_VAT_ID');
	 
	 define('PARAM_DEDUCTION_ST_BIR_EIGHT', 'DEDUCTION_ST_BIR_EIGHT');			//jendaigo : code for BIR 8% Income Tax
	 define('PARAM_DEDUCTION_ST_BIR_EIGHT_ID', 'DEDUCTION_ST_BIR_EIGHT_ID'); 	//jendaigo : id for BIR 8% Income Tax
	 define('PARAM_DEDUCTION_ST_BIR_EIGHT_ATC', 'DEDUCTION_ST_BIR_EIGHT_ATC'); 	//jendaigo : ATC for BIR 8% Income Tax
	 
	 define('PARAM_DEDUCTION_ST_PHILHEALTH_QTR', 'DEDUCTION_ST_PHILHEALTH_QTR');
	 define('PARAM_DEDUCTION_ST_SSS', 'DEDUCTION_ST_SSS');
	 
	 define('PARAM_DEDUCTION_ST_GSIS_ECF_ID', 'DEDUCTION_ST_GSIS_ECF_ID');
	 
	 define('PARAM_PAYOUT_STATUS_INITIAL', 1);
	 
	 define('PARAM_LCA_AUTH_LWOP_FIRST', 'LCA_AUTH_LWOP_FIRST');
	 define('PARAM_LCA_AUTH_LWOP_NEXT', 'LCA_AUTH_LWOP_NEXT');
	 define('PARAM_LCA_MILESTONE_YEAR_FIRST', 'LCA_MILESTONE_YEAR_FIRST');
	 define('PARAM_LCA_MILESTONE_YEAR_NEXT', 'LCA_MILESTONE_YEAR_NEXT');
	 define('PARAM_LCA_MILESTONE_FIRST_AMOUNT', 'LCA_MILESTONE_FIRST_AMOUNT');
	 define('PARAM_LCA_MILESTONE_NEXT_AMOUNT', 'LCA_MILESTONE_NEXT_AMOUNT');
	 define('PARAM_LCA_EFFECTIVE_START_DATE', 'LCA_EFFECTIVE_START_DATE');
	 
	 define('PARAM_TAX_HUSBAND_EXCEPTION', 'TAX_HUSBAND_EXCEPTION');
	 define('PARAM_TAX_WIFE_EXCEPTION', 'TAX_WIFE_EXCEPTION');
	 define('PARAM_TAX_PREV_EMPLOYER_TIN', 'TAX_PREV_EMPLOYER_TIN');
	 define('PARAM_TAX_PREV_EMPLOYER_NAME', 'TAX_PREV_EMPLOYER_NAME');
	 define('PARAM_TAX_PREV_EMPLOYER_ADDR', 'TAX_PREV_EMPLOYER_ADDR');
	 define('PARAM_TAX_PREV_EMPLOYER_INCOME', 'TAX_PREV_EMPLOYER_INCOME');
	 define('PARAM_TAX_PREV_EMPLOYER_WITHHELD', 'TAX_PREV_EMPLOYER_WITHHELD');
	 
	 define('PARAM_COMPENSATION_YEAR_END', 'COMPENSATION_YEAR_END');
	 define('PARAM_COMPENSATION_ID_YEAR_END', 'COMPENSATION_ID_YEAR_END');
	 
	 define('PARAM_COMPENSATION_CODE_CNA', 'COMPENSATION_CODE_CNA');
	 define('PARAM_DEDUCTION_ID_CNA', 'DEDUCTION_ID_CNA');
	 define('PARAM_COMPENSATION_CNA_AMOUNT', 'COMPENSATION_CNA_AMOUNT');
	 define('PARAM_COMPENSATION_CNA_MEMBER_RATE', 'COMPENSATION_CNA_MEMBER_RATE');
	 define('PARAM_COMPENSATION_CNA_NON_MEMBER_RATE', 'COMPENSATION_CNA_NON_MEMBER_RATE');
	 
	 define('PARAM_ATTENDANCE_STAT_WOP', 'ATTENDANCE_STAT_WOP');
	 define('PARAM_ATTENDANCE_STATUS_REGULAR_DAY', 'ATTENDANCE_STATUS_REGULAR_DAY');
	 define('PARAM_ATTENDANCE_STAT_ABSENT', 'ATTENDANCE_STAT_ABSENT');
	 define('PARAM_ATTENDANCE_STAT_DAYS_PRESENT', 'ATTENDANCE_STAT_DAYS_PRESENT');
	 
	 define('PARAM_COMPENSATION_CODE_SALDIFFL', 'COMPENSATION_CODE_SALDIFFL');
	 define('PARAM_COMPENSATION_CODE_HAZDIFFL', 'COMPENSATION_CODE_HAZDIFFL');
	 define('PARAM_COMPENSATION_CODE_LONGEDIFFL', 'COMPENSATION_CODE_LONGEDIFFL');
	 
	 define('PARAM_COMPENSATION_ID_SALDIFFL', 'COMPENSATION_ID_SALDIFFL');
	 define('PARAM_COMPENSATION_ID_HAZDIFFL', 'COMPENSATION_ID_HAZDIFFL');
	 define('PARAM_COMPENSATION_ID_LONGEDIFFL', 'COMPENSATION_ID_LONGEDIFFL');
	 define('PARAM_COMPENSATION_ID_HAZARD_PAY', 'COMPENSATION_ID_HAZARD_PAY');
	 define('PARAM_COMPENSATION_ID_LONGEVITY_PAY', 'COMPENSATION_ID_LONGEVITY_PAY');
	 define('PARAM_DEDUCTION_ID_GSISDIFFL', 'DEDUCTION_ID_GSISDIFFL');
	 define('PARAM_DEDUCTION_ID_GSIS', 'DEDUCTION_ID_GSIS');
	 
	 define('PARAM_COMPENSATION_ID_TAX_REFUND_ANNUAL', 'COMPENSATION_ID_TAX_REFUND_ANNUAL');
	 define('PARAM_COMPENSATION_CODE_TAX_REFUND_ANNUAL', 'COMPENSATION_CODE_TAX_REFUND_ANNUAL');
	 
	 define('PARAM_COMPENSATION_ID_TAX_REFUND_MONTHLY', 'COMPENSATION_ID_TAX_REFUND_MONTHLY');
	 define('PARAM_COMPENSATION_CODE_TAX_REFUND_MONTHLY', 'COMPENSATION_CODE_TAX_REFUND_MONTHLY');	 
	 
	 define('PARAM_TAX_DEPENDENT_CIVIL_STATUS', 'TAX_DEPENDENT_CIVIL_STATUS');
	 define('PARAM_TAX_DEPENDENT_EMPLOYMENT_STATUS', 'TAX_DEPENDENT_EMPLOYMENT_STATUS');
	 define('PARAM_TAX_DEPENDENT_RELATION_TYPE', 'TAX_DEPENDENT_RELATION_TYPE');
	 define('PARAM_TAX_DEPENDENT_AGE_LIMIT', 'TAX_DEPENDENT_AGE_LIMIT');
	 
	 define('PARAM_DOH_RDO_CODE', 'DOH_RDO_CODE');
	 
	 define('PARAM_MWE_COMPUTE_FLAG', 'MWE_COMPUTE_FLAG');
	 define('PARAM_MWE_RATE', 'MWE_RATE');
	 
	 define('PARAM_RESERVED_LOWERCASE_WORDS', 'RESERVED_LOWERCASE_WORDS');
	 define('PARAM_RESERVED_UPPERCASE_WORDS', 'RESERVED_UPPERCASE_WORDS');
	 
	 // TENURE REQUIREMENT
	 define('TENURE_RQMT_TENURE', 'T');
	 define('TENURE_RQMT_DAYS_PRESENT', 'DP');
	 define('TENURE_RQMT_DEDUCT_LWOP', 'DDP');
	 define('TENURE_RQMT_NA', 'NA');
	 
	 // GSIS TYPE
	 define('GSIS_REG', 'R');
	 define('GSIS_ECF', 'ECF');


	 // REMITTANCE STATUS
	 define('REMITTANCE_FOR_REMITTANCE', 1);
	 define('REMITTANCE_PROCESSING', 2);
	 define('REMITTANCE_REMITTED', 3);
	 
	 // Payroll Signatory Code
	 define('PARAM_PAYROLL_CERTIFIED_BY', 'PAYROLL_CERTIFIED_BY');
	 define('PARAM_PAYROLL_APPROVED_BY', 'PAYROLL_APPROVED_BY');
	 define('PARAM_PAYROLL_CA_CERTIFIED_BY', 'PAYROLL_CA_CERTIFIED_BY');
	 
	 // TA Signatory Code
	 define('PARAM_TA_CERTIFIED_BY', 'TA_CERTIFIED_BY');
	 define('PARAM_TA_APPROVED_BY', 'TA_APPROVED_BY');

	 // FORM 2316
	 define('ITEM_13_MONTH_PAY', 'month_pay_13'); //37 and 51 (in excess of cap)
	 define('ITEM_DEMINIMIS', 'deminimis'); // 38
	 define('ITEM_SALARY_OTHER_COMPENSATION', 'salary_other_compensation'); // ITEM 40
	 define('ITEM_REPRESENTATION', 'representation'); // 43
	 define('ITEM_TRANSPORTATION', 'transportation'); // 44
	 define('ITEM_COST_LIVING_ALLOWANCE', 'cost_of_living_allowance'); // 45 
	 define('ITEM_FIXED_HOUSING_ALLOWANCE', 'fixed_housing_allowance'); // 46
	 define('ITEM_OTHER_TAXABLE', 'others_taxable'); // 47
	 define('ITEM_COMMISSION', 'commission'); // 48 
	 define('ITEM_PROFIT_SHARING', 'profit_sharing'); // 49
	 define('ITEM_FEES', 'fees'); // 50
	 define('ITEM_HAZARD_PAY', 'hazard_pay'); // 52
	 define('ITEM_OVERTIME_PAY', 'overtime_pay'); // 53
	 define('ITEM_OTHER_TAXABLE_SUPP', 'other_taxable_supp'); // 54
	 define('ITEM_STAT_EMPLOYEE_SHARE', 'statutory_employee_share'); // 39
	 define('ITEM_UNION_DUES', 'union_dues'); // 39
	 define('ITEM_TAXABLE_13_MONTH_PAY', 'taxable_month_pay_13'); // 51
	 define('ITEM_TAXABLE_BASIC_SALARY', 'basic_salary'); // 42
	 define('ITEM_BASIC_SALARY_DEDUCTIONS', 'basic_salary_deductions'); // 42
	 
	 define('ITEM_GROSS_INCOME_PRESENT_EMPLOYER', 'gross_income_present_employer'); // 21
	 define('ITEM_TAX_WITHHELD_PRESENT_EMPLOYER', 'tax_withheld_present_employer'); // 30-A
	 
	 define('ITEM_TAXABLE_INCOME_PREVIOUS_EMPLOYER', 'taxable_income_previous_employer'); // 24	 
	 define('ITEM_TAX_WITHHELD_PREVIOUS_EMPLOYER', 'tax_withheld_previous_employer'); // 30-B
	 
	 define('ITEM_TOTAL_PERSONAL_EXEMPTION', 'total_personal_exemption'); // 26
	 
	 define('ITEM_TAX_DUE', 'tax_due'); // 30-A
	 define('ITEM_MWE_BASIC_SALARY', 'mwe_basic_salary'); // 32
	 define('ITEM_MWE_HAZARD_PAY', 'mwe_hazard_pay'); // 35
	 
	 define('TAX_ANNUALIZED', 'ANNUAL');
	 define('TAX_MONTHLY_2316', 'MONTHLY-2316');
	 define('TAX_MONTHLY_2307', 'MONTHLY-2307');
	 
	 // PAYOUT STATUS FLAG
	 define('PAYOUT_STATUS_FLAG_ALL', 'A');
	 
	 // FORM 2307
	 define('EWT_DESCRIPTION_PROFESSIONAL', 'Professional fees');
	 define('EWT_DESCRIPTION_NON_PROFESSIONAL', 'Non-professional fees');
	 define('NATURE_DESCRIPTION_2306', 'Persons exempt from VAT under Sec. 109 (v) (Creditable) Government Withholding Agent');
	 define('ATC_CODE_2306', 'WB080');
	 
	 // PAYOUT DETAIL
	 define('PAYOUT_DETAIL_TYPE_COMPENSATION', 'C');
	 define('PAYOUT_DETAIL_TYPE_DEDUCTION', 'D');

	 // PRO-RATED FLAG
	 define('PRORATE_NA', 'NA');
	 define('PRORATE_TENURE', 'T');
	 define('PRORATE_SALARY_GRADE', 'SG');
	 define('PRORATE_DAY', 'DP');
	 
	 // SALARY FREQUENCY FLAG
	 define('SALARY_MONTHLY', 'MONTHLY');
	 define('SALARY_DAILY', 'DAILY');	 
	 
 	/* MAIN ORG ID */
	define( 'MAIN_ORG_ID', 1 );	
	
	/*CIVIL STATUS ID*/
	define('CIVIL_STATUS_SINGLE', 1);
	define('CIVIL_STATUS_MARRIED', 2);
	define('CIVIL_STATUS_ANNULLED', 3);
	define('CIVIL_STATUS_WIDOW_ER', 4);
	define('CIVIL_STATUS_LEGALLY_SEPERATED', 5);

/*EMPLOYMENT STATUS ID*/
	define('EMPLOYMENT_STATUS_PERMANENT', 84);
	define('EMPLOYMENT_STATUS_TEMPORARY', 89);
	define('EMPLOYMENT_STATUS_COTERMINOUS', 81);
	define('EMPLOYMENT_STATUS_CASUAL', 80);
	define('EMPLOYMENT_STATUS_CONTRACTUAL', 78);
	define('EMPLOYMENT_STATUS_JO', 118);

	/*POSITION CLASS LEVEL ID*/
	define('POS_CLASS_LEVEL1', 1);
	define('POS_CLASS_LEVEL2', 2);
	define('POS_CLASS_LEVEL2_EXECUTIVE', 3);
	define('POS_CLASS_LEVEL3', 4);
	
	/*EMPLOYMENT STATUS NAME*/
	define('EMPLOYMENT_STATUS_NAME_PERMANENT', 'Permanent');
	define('EMPLOYMENT_STATUS_NAME_REGULAR', 'Regular');
	define('EMPLOYMENT_STATUS_NAME_CASUAL', 'Casual');
	define('EMPLOYMENT_STATUS_NAME_CONTRACTUAL', 'Contractual');
	
	/*DOH ADDRESS*/
	define('DOH_ADDRESS_BUILDING', 'DOH_ADDRESS_BUILDING');
	define('DOH_ADDRESS_STREET', 'DOH_ADDRESS_STREET');
	define('DOH_ADDRESS_SUBDIVISION', 'DOH_ADDRESS_SUBDIVISION');
	define('DOH_ADDRESS_BARANGAY', 'DOH_ADDRESS_BARANGAY');
	define('DOH_ADDRESS_MUNICITY', 'DOH_ADDRESS_MUNICITY');
	define('DOH_ADDRESS_ZIP_CODE', 'DOH_ADDRESS_ZIP_CODE');
	
	/*DOH TIN*/
	define('DOH_TIN', 'DOH_TIN');
	define('DOH_HMDF', 'DOH_HMDF');
	
	/*NOT APPLICABLE*/
	define('NOT_APPLICABLE', 'NA');
	define('N_A', 'N/A');
	define('NONE', 'NONE');

	
	/* TAX EXEMPT CODE FOR MONTHLY 2316 */
	define('TAX_EXEMPT_STAT_SME', 'S/ME');
	define('TAX_EXEMPT_STAT_SME1', 'ME1/S1');
	define('TAX_EXEMPT_STAT_SME2', 'ME2/S2');
	define('TAX_EXEMPT_STAT_SME3', 'ME3/S3');
	define('TAX_EXEMPT_STAT_SME4', 'ME4/S4');
	
	define('TAX_EXEMPT_STAT_SINGLE', 'S');
	define('TAX_EXEMPT_STAT_MARRIED', 'ME');
		
	
	// PRO RATED FLAG
	define('PRO_RATED_FLAG_NA', 'NA');
	define('PRO_RATED_FLAG_TENURE', 'T');
	define('PRO_RATED_FLAG_SALARY_GRADE', 'SG');
	define('PRO_RATED_FLAG_DAYS_PRESENT', 'DP');

	define('CONTACT_SEPARATOR', '-');
	define('TELEPHONE_FORMAT', '3x3x4x'.CONTACT_SEPARATOR);
	define('CELLPHONE_FORMAT', '4x3x4x'.CONTACT_SEPARATOR);
	define('DATE_FORMAT', '4x2x2x/');
	
	// Frequencies
	define('FREQUENCY_DAILY', 1);
	define('FREQUENCY_WEEKLY', 2);
	define('FREQUENCY_MONTHLY', 3);
	define('FREQUENCY_QUARTERLY', 4);
	define('FREQUENCY_SEMI_ANNUAL', 5);
	define('FREQUENCY_ANNUAL', 6);
	define('FREQUENCY_ONE_TIME', 7);

	// DEDUCTIONS
	define('DEDUC_BIR', '1');
	define('DEDUC_GSIS', '2');
	define('DEDUC_PAGIBIG', '3');
	define('DEDUC_PHILHEALTH', '4');
	define('DEDUC_ECIP', '9');

	// REMITTANCE TYPES
	define('REMITTANCE_GSIS_INSURANCE', '1');
	define('REMITTANCE_GSIS_LOAN', '2');
	define('REMITTANCE_DOH_COOP', '3');
	define('REMITTANCE_WITHHOLDING_TAX', 1);
	define('REMITTANCE_PAGIBIG', '5');
	define('REMITTANCE_PHILHEALTH', '6');
	define('REMITTANCE_PAGIBIG_LOAN', '7');

	// GOVERNMENT BRANCH
	define('BRANCH_NATIONAL', '8');
	
	// PAYROLL AMOUNT KEYS
	define('KEY_AMOUNT', 'amount'); // net amount
	define('KEY_LESS_AMOUNT', 'less_amount'); // deduction due to absences
	define('KEY_ORIG_AMOUNT', 'orig_amount'); // gross amount
	define('KEY_DEDUCTION', 'deduction'); // deduction
	define('KEY_PAYROLL_HDR_ID', 'payroll_hdr_id'); // payroll header id
	define('KEY_COMPENSATION_ID', 'compensation_id'); // compensation id
	define('KEY_RAW_COMPENSATION_ID', 'raw_compensation_id'); // raw compensation id
	define('KEY_EFFECTIVE_DATE', 'effective_date'); // effective date
	define('KEY_DETAILS', 'details');
	define('KEY_DETAIL_COUNT', 'detail_count');
	define('KEY_DETAIL_DEDUCTION', 'detail_deduction');
	
	define('KEY_PERSONAL_SHARE', 'personal_share');
	define('KEY_GOVERNMENT_SHARE', 'government_share');
	define('KEY_MAX_GOVERNMENT_SHARE', 'max_government_share');
	define('KEY_PERSONAL_ECF', 'personal_ecf');
	define('KEY_GOVERNMENT_ECF', 'government_ecf');
	
	define('KEY_DEPENDENTS', 'dependents');
	define('KEY_TAX_EXEMPT_CODE', 'tax_exempt_code');
	define('KEY_WIFE_CLAIM_EXEMPT', 'wife_claim_exempt');
	
	define('KEY_MWE_FLAG', 'mwe_flag'); // flag if employee is MWE
	define('KEY_MWE_RATE', 'mwe_rate');
	define('KEY_MWE_DENOMINATOR', 'mwe_denominator');
	define('KEY_SALARY_FREQ_FLAG', 'salary_freq_flag');
	
	define('KEY_EMPLOYEE_ID', 'employee_id');
	define('KEY_DEDUCTION_ID', 'deduction_id');
	define('KEY_RAW_DEDUCTION_ID', 'raw_deduction_id');
	define('KEY_EMPLOYER_AMOUNT', 'employer_amount');
	define('KEY_REFERENCE_TEXT', 'reference_text');
	define('KEY_PRIORITY_NUM', 'priority_num');
	define('KEY_INCLUDE_FLAG', 'include_flag');
	define('KEY_REFERENCE_DEDUCTIONS', 'remarks_deduction');

	// OTHERS PROFESSION
	define('PROFESSION_OTHERS', '3');
	
	
	// OTHER DEDUCTION DETAILS IDS
	define('PREV_EMPLOYER_TIN', 3);
	define('PREV_EMPLOYER_NAME', 4);
	define('PREV_EMPLOYER_ADDRESS', 5);
	
	// BIT_TAX_TABLE
	define('BIR_TAX_TABLE', 'BIR_TAX_TABLE');

	/*TIME FLAG FOR employee_attendance TABLE*/
	 define('FLAG_TIME_IN', 'TI');
	 define('FLAG_BREAK_OUT', 'BO');
	 define('FLAG_BREAK_IN', 'BI');
	 define('FLAG_TIME_OUT', 'TO');
	 
	// SYS PARAM DEDUCTION ID
	define('SYS_PARAM_TYPE_DEDUC_ID' , 'REPORT_GSIS_CONTRIB_REMIT_LIST');
	
	// SYS PARAM LEAVE TYPES
	define('SYS_PARAM_TYPE_LEAVE_TYPE_REGULAR' , 'LEAVE_TYPE_REGULAR');
	define('SYS_PARAM_TYPE_LEAVE_TYPE_SPECIAL' , 'LEAVE_TYPE_SPECIAL');

	// SYS PARAM REPORT GENERAL PAY ALPHALIST PER OFFICE PARAM
	define('REPORT_GENPAY_ALPHALIST_OFFICE', 'REPORT_GENPAY_ALPHALIST_OFFICE');
	
	// OFFICE TYPES
	define('OFFICE_TYPE_OFFICE' , 2);
	
	// SYS PARAM PAYROLL TYPES
	define('SYS_PARAM_TYPE_PAYROLL_TYPE_REGULAR', 'PAYROLL_TYPE_REGULAR');
	define('SYS_PARAM_TYPE_PAYROLL_TYPE_NON_CAREER', 'PAYROLL_TYPE_NON_CAREER');
	define('SYS_PARAM_TYPE_PAYROLL_TYPE_JOB_ORDER', 'PAYROLL_TYPE_JOB_ORDER');
	define('SYS_PARAM_TYPE_DEDUCTION_ST_BIR_EWT_ID', 'DEDUCTION_ST_BIR_EWT_ID');
	define('SYS_PARAM_TYPE_DEDUCTION_ST_BIR_VAT_ID', 'DEDUCTION_ST_BIR_VAT_ID');
	define('SYS_PARAM_TYPE_PAYROLL_TYPE_CONSULTANT', 'PAYROLL_TYPE_CONSULTANT');
	define('SYS_PARAM_TYPE_PAYROLL_TYPE_INTERN', 'PAYROLL_TYPE_INTERN');
	define('SYS_PARAM_TYPE_REMITTANCE_FILE_UPLOAD', 'REMITTANCE_FILE_UPLOAD');
	
	// GROUP_CONCAT LIMIT
	define('GROUP_CONCAT_MAX_LENGTH' , 20480);
	
	// TABLE PARAMS
	define('DOH_PTIS_TABLE_SSS','param_sss');
	
	// PAYROLL TYPE FLAG
	define('PAYROLL_TYPE_FLAG_ALL', 'ALL');
	define('PAYROLL_TYPE_FLAG_REG', 'REG');
	define('PAYROLL_TYPE_FLAG_JO', 'JO');
	define('PAYROLL_TYPE_FLAG_GIP', 'GIP');
	
	// START OF LONGEVITY PAY CHECK
	 define('LONGE_PAY_CHECK_START_YEAR', 1992);
	 define('LONGE_PAY_CHECK_START_MONTH', 4);
	 define('LONGE_PAY_CHECK_START_DAY', 17);
	 define('LONGE_PAY_MILESTONE_NUM_YEAR', 5);
	 
	// START OF BIR-EWT / BIR-VAT
	 define('DEDUC_BIR_EWT', 6);
	 define('DEDUC_BIR_VAT', 7);
	 define('DEDUC_PHIC_QTR', 8);
	 
	// ====================== jendaigo : start : include new constants ============= //
	define('DEDUC_BIR_EIGHT', 100);
	define('DEDUC_OTHER_TIN', 179);
	define('DEDUC_OTHER_TAX_CODE', 192);
	
	// START OF PHIC
	define('DEDUC_PHIC', 98);
	
	// START OF SSS
	define('DEDUC_SSS', 99);
	
	// START OF PAG-IBIG - JO
	define('DEDUC_HMDF1_JO', 3);
	define('DEDUC_HMDF2_JO', 101);
	
	// START OF OVERPAY - JO
	define('DEDUC_OVERPAY_JO', 97);
	
	// START OF UNDERPAY - JO
	define('COMP_UNDERPAY_JO', 1004);
	// ====================== jendaigo : end : include new constants ============= //
	 
	// EMPLOYEE RELATIONS FLAGS
	 define('BIR_FLAG', 'bir_flag');
	 define('GSIS_FLAG', 'gsis_flag');
	 define('PAGIBIG_FLAG', 'pagibig_flag');
	 define('PHEALTH_FLAG', 'philhealth_flag');
	 
	 define('WORKING_MONTHS', 12);
	 
	// OTHER INFO TYPE ID
	define('OTHER_INFO_TYPE_TITLE', 7);
	define('OTHER_INFO_TYPE_PROFESSIONAL', 5);
	
	// REMITTANCES REPORT SIGNATORY
	define('PARAM_REM_PREPARED_BY', 'REM_PREPARED_BY');
	define('PARAM_REM_CERTIFIED_BY', 'REM_CERTIFIED_BY');

	// STEP INCREMENT REASON CODE
	define('SALARY_INCR_CAUSE_PG', 'PG');
	define('SALARY_INCR_CAUSE_MI', 'MI');
	
	// EMPLOYMENT STATUS
	define('EMPLOYMENT_STATUS_UNEMPLOYED', 1);
	define('EMPLOYMENT_STATUS_EMPLOYED_LOCALLY', 2);
	define('EMPLOYMENT_STATUS_EMPLOYED_ABROAD', 3);
	define('EMPLOYMENT_STATUS_ENGAGE_IN_BUSINESS', 4);
	
	// FOR SUBSISTENCE DEDUCTION
	define('KEY_SUBS_DEDUCT_COUNT', 'KEY_SUBS_DEDUCT_COUNT');
	
	// FOR WORK DAY PARAM
	define('KEY_MONTH_WORK_DAYS', 'KEY_MONTH_WORK_DAYS');
	define('KEY_WORKED_DAYS', 'KEY_WORKED_DAYS');
	define('KEY_ABSENT_DAYS', 'KEY_ABSENT_DAYS');
	define('KEY_DAILY_RATE', 'KEY_DAILY_RATE');
	define('KEY_PERIOD_PAYROLL_COUNT', 'KEY_PERIOD_PAYROLL_COUNT');
	define('KEY_PAYROLL_TYPE_ID', 'KEY_PAYROLL_TYPE_ID');
	define('KEY_TARDINESS_HR', 'KEY_TARDINESS_HR');
	define('KEY_TARDINESS_MIN', 'KEY_TARDINESS_MIN');
	
	// LONGEVITY PAY
	define('PARAM_LONGE_PAY_START', "1992-04-17");
	
	// CERTIFICATE OF CONTRIBUTION
	define('PARAM_CERT_PAGIBIG_CODE', 'CERT_PAGIBIG_CODE');
	
	// SYS PARAM ALPHALIST FOR JO DEDUCTION GROUPS
	define('SYS_PARAM_TYPE_ALPHALIST_JO_DED1', 'ALPHALIST_JO_DED1');
	define('SYS_PARAM_TYPE_ALPHALIST_JO_DED2', 'ALPHALIST_JO_DED2');
	define('SYS_PARAM_TYPE_ALPHALIST_JO_DED3', 'ALPHALIST_JO_DED3'); //jendaigo: JO deduction group 3
	
	// SYS PARAM DEFAULT UACS FOR JO
	define('SYS_PARAM_TYPE_REPORT_UACS_JO', 'REPORT_UACS_JO'); //jendaigo : parameter for uacs jo
	
	// GSIS - use salary net of attendance deductions
	define('FLAG_USE_BASIC_AMOUNT', YES);

	// USE ADMIN OFFICE
	define('USE_ADMIN_OFFICE', YES);

	// COMPENSATION TYPE 
	define('COMPENSATION_PERA', 'PERA');

	// REMIT TYPE FLAG 
	define('REMIT_ALL', 'ALL');
	define('REMIT_PERSONAL_SHARE', 'PS');
	define('REMIT_GOVT_SHARE', 'GS');
	
	define('DEFAULT_PHILHEALTH_REMITTANCE_TEMPLATE', PAYOUT_TYPE_FLAG_VOUCHER);

	//FORMAT
	define('PARAM_FORMAT_MP2', 'DEDUCTION_ST_MP2_FORMAT'); //jendaigo: format for mp2 savings account no.

	// TRANSFER FLAG 
	define('TRANSFER_IN', 'IN');
	define('TRANSFER_OUT', 'OUT');
	define('MOVT_TRANSFER_PROMOTION', 'MOVT_TRANSFER_PROMOTION');


	define('WHOLE_YEAR_WORKING_DAYS', 249);
	
	define('WORKING_DAYS_MONTH', 22);
	define('WORKING_HOURS_DAY', 8);
	define('MONTH_YEAR', 12);
	define('QUARTER_YEAR', 4);
	define('ANNUAL_YEAR', 1);
	
	define('DAYS_PRESENT_PAYROLL_PERIOD', 1);  // 0 - Attendance Period; 1 - Payroll Period
	
	// LWOP
	define('KEY_LWOP', 'KEY_LWOP');
	define('KEY_LWOP_HOURS', 'KEY_LWOP_HOURS');
	define('KEY_LWOP_MINS', 'KEY_LWOP_MINS');

	// ELIGIBILITY TYPE FLAG
	define('ELIGIBILITY_TYPE_FLAG_RA', 'RA');	
	define('ELIGIBILITY_TYPE_FLAG_CSPD', 'CSPD');	//NCOCAMPO - ADD CSPD

	// NEW PDS
	define('CITIZENSHIP_BASIS', 'CITIZENSHIP_BASIS');
	define('CITIZENSHIP_BASIS_BIRTH', 1);
	define('CITIZENSHIP_BASIS_NATURALIZATION', 2);	
	define('CITIZENSHIP_FILIPINO', 43);	

	// CSV UPLOADING
	define('ATTENDANCE_LOG_FORMAT', 'ATTENDANCE_LOG_FORMAT');

	//davcorrea: START : define  var user roles
	// ROLE NAME
	define('PERSONNEL', 'PERSONNEL');
	define('SUPER_ADMIN', 'SUPER ADMIN');
	define('SUPER_USER', 'SUPER USER');
	//davcorrea : END

	// define TA computation table types davcorrea
	define('TA_COMP_TABLE_MINUTE', 1);
	define('TA_COMP_TABLE_HOUR', 2);
	define('TA_COMP_TABLE_DAY', 3);
	define('TA_COMP_TABLE_MONTH', 4);
	define('TA_COMP_TABLE_VLWOP', 5);

	// define signatory titles

	define('CHAIRPERSON', 'C');
	define('VICE_CHAIRPERSON', 'VC');

/* End of file constants.php */
/* Location: ./application/config/constants.php */