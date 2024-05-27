<?php

// SYSTEM AUTHENTICATION
$lang['invalid_login']               = "Sorry the login was incorrect. Please try again.";
$lang['username_required']           = "Please provide your username.";
$lang['password_required']           = "Please provide your password.";
$lang['email_required']              = "Please provide your email.";
//NCOCAMPO:	ADDED FOR LOGIN	:START
$lang['agree_required']              = "Please agree to the Data Privacy Statement and DOH Privacy Policy.";
//NCOCAMPO:	ADDED FOR LOGIN	:END
$lang['confirm_password']            = "Please confirm your password.";
$lang['contact_admin']               = "There is something wrong with your account, please contact the system's administrator.";
$lang['system_error']                = "There seems to be a problem with the system or your internet connection, please try again later.";
$lang['reset_password']              = "An instruction on how to reset your password has been sent to your email.";
$lang['email_exist']                 = "Sorry, but your email is already registered with our system. Please use another email and try again.";
// davcorrea : added error message : START
// $lang['invalid_action']              = "Sorry, but your current action is invalid.";
$lang['invalid_action']              = "Sorry, but your current action is invalid. </br> An error occurred while processing your request. Kindly log-in your account.";
// davcorrea : END
$lang['err_invalid_request']         = "Sorry, but your current request is invalid.";
$lang['password_reset']              = "Your new password has been reset, you may now login using your new password.";
$lang['signup_success']              = "Your registration has been sent to the site administrator for validation and approval.  Please check your email for further instructions.";
$lang['pending_account']             = "Sorry, your account is awaiting approval by the site administrator. Once approved or denied you will receive an email notice.";
$lang['account_blocked']             = "Sorry, this account has been blocked. </br>Please contact the Personnel Administrative Division, AS to unblock your account.";
//davcorrea : added error message for inactive
$lang['account_inactive']            = "<b>Your account is no longer active</b> </br> Please coordinate with the Personnel Administrative Division, AS.";
$lang['account_expired']             = "Sorry, this account has already expired. Please contact the site administrator to renew your account.";
$lang['login_attempt_ban']			 = "Sorry, the number of your attempts exceeds to 3. Your account will be banned for %s";
$lang['login_attempt_block']		 = "Sorry, this account has been locked for security purposes. Please contact the system administrator.";
$lang['password_change']             = "Your new password has been changed, you may now login using your new password.";
$lang['personal_info_constraint']    = "Password must not be related on any of your complete name.";
// ACTIONS MESSAGES
$lang['data_saved']                  = "The record was successfully saved.";
$lang['data_updated']                = "The record was successfully updated.";
$lang['data_deleted']                = "The record was successfully deleted.";
$lang['data_processed']              = "The record was successfully processed.";
$lang['data_long_processing']        = "The record is processed.";

$lang['data_not_saved']              = "An error occurred while saving the record. Please try again later.";
$lang['data_not_updated']            = "An error occurred while updating the record. Please try again later.";
$lang['data_not_deleted']            = "An error occurred while deleting the record. Please try again later.";
$lang['data_not_processed']          = "An error occurred while processing the data. Please try again later.";

// SYSTEM MESSAGES
$lang['confirm_error']               = "There was an error on your password confirmation, please try again.";
$lang['parent_delete_error']         = "Record with dependency cannot be deleted.";
$lang['member_admin_delete_error']   = "This action cannot be performed because this member is assigned as the initiative manager.";
$lang['detail_view_error']           = "Parent table is empty, the following detail can't be viewed.";
$lang['detail_delete_error']         = "Parent table is empty, the following detail can't be deleted.";
$lang['detail_save_error']           = "Parent table is empty, the following detail can't be saved.";
$lang['data_empty']                  = "No matching records found.";
$lang['is_required']                 = "%s is required.";
$lang['invalid_data']                = "Invalid data for %s";

// PERMISSION MESSAGE
$lang['err_unauthorized_add']        = "Sorry, you don't have permission to add this record. Please contact the system's administrator.";
$lang['err_unauthorized_edit']       = "Sorry, you don't have permission to edit this record. Please contact the system's administrator.";
$lang['err_unauthorized_delete']     = "Sorry, you don't have permission to delete this record. Please contact the system's administrator.";
$lang['err_unauthorized_save']       = "Sorry, you don't have permission to save this record. Please contact the system's administrator.";
$lang['err_unauthorized_view']       = "Sorry, you don't have permission to view the record. Please contact the system's administrator.";
$lang['err_unauthorized_access']     = "Sorry, you don't have permission to access the page. Please contact the system's administrator.";
$lang['err_unauthorized_process']    = "Sorry, you don't have permission to process this record.";

//AUDIT TRAIL 
$lang['audit_trail_add']             = "%s has been added";
$lang['audit_trail_update']          = "%s has been updated";
$lang['audit_trail_delete']          = "%s has been deleted";

$lang['audit_trail_update_specific'] = "%s has been updated for %s";
$lang['audit_trail_add_specific']    = "%s has been added for %s";
$lang['audit_trail_delete_specific'] = "%s has been deleted for %s";

$lang['save_record_changes']         = "Record changes was successfully saved.";
$lang['request_prohibited']          = "Requested action is prohibited. Previous request for this record is still unprocessed.";

//FILE DATA MESSAGE
$lang['invalid_file_data']           = "Invalid file data";
$lang['invalid_file_data_line']      = "Invalid data on line number %s. File name: (%s)";
$lang['invalid_file_line']      	 = "Invalid data on line number %s.";

//SYSTEM VALIDATION MESSAGE
$lang['invalid_evaluation_date']     = "Sorry, but your evaluation date is already registered with our system. Please use another Evaluation Date and try again.";
$lang['effectivity_date_exist']      = "Sorry, but your effectivity date is already registered with our system. Please use another effectivity date and try again.";
$lang['new_active_fund']             = "Record was successfully saved. Other fund has been unchecked from previous effectivity date.";

$lang['invalid_employee_date']     = "Sorry, but your Start Date is conflict with the other work experience. Please use another date and try again.";

$lang['no_record_to_process']		 = "No record to process.";

$lang['sys_param_not_defined']		 = "System Parameter is not properly defined.";

$lang['param_not_defined']	 		 = "Some parameters are not properly defined.";
$lang['param_dates_not_defined']	 = "Parameter dates are not properly defined.";
$lang['unable_continue_record_submitted'] = "Unable to continue.  Record is already submitted.";

$lang['payroll_deduction_not_included'] = "The amount of Php %s is not deducted.";

$lang['employee_no_attendance'] 	= "Selected employee has no attendance record.";

// MYSQL Error
$lang['23000-1451']		= "Foreign key constraint error: %s";
$lang['23000-1048']		= "Data error: %s";

// REMITTANCES ERROR MESSAGES
$lang['no_remittance_to_process'] = "No record to process. Please contact the system administrator.";
$lang['no_deduction']             = "No found deductions. Please contact the system administrator.";
$lang['overlapped_err']           = "There is an on-going remittance of this type with the same coverage.";

$lang['process_request_notif'] = "A request is for your approval.";
$lang['process_request_approved'] = "Your request has been approved.";
$lang['process_request_rejected'] = "Your request has been rejected.";

$lang['process_payroll_notif'] = "A payroll transaction is waiting for your action.";
$lang['process_payroll_approved'] = "Payroll has been approved.";
$lang['process_payroll_rejected'] = "Payroll has been rejected.";

// REMITTANCE NOTIFICATION MESSAGES
$lang['remittance_process'] = "A remittance transaction is waiting for your action.";
$lang['remittance_on_going'] = "An on-going remittance transaction is waiting for your action.";
$lang['remittance_remit'] = "Your remittance transaction has been remitted.";

// VOUCHER NOTIFICATION MESSAGES
$lang['process_voucher_notif'] = "A voucher transaction is waiting for your action.";
$lang['process_voucher_approved'] = "Voucher has been approved.";
$lang['process_voucher_rejected'] = "Voucher has been rejected.";

// PDS NOTIFICATION MESSAGES
$lang['date_range'] 		= "<b>End Date</b> should not be earlier than <b>Start Date</b>.";
$lang['invalid_start_date'] = "<b>Start Date</b> should not be earlier than active work experience.";
$lang['overlapped_date'] 	= "Entered date range overlapped other existing work experience.";
$lang['not_applicable'] 	= " is required. If Not Applicable, put <b>NA</b>.";
$lang['generate_id'] 		= "<b>Agency Employee ID</b> already exists. Try checking the box to generate new ID.";

// CL - PAYROLL - COMPENSATION
$lang['regular_pay_cnt'] 	= "Select two payout count for <b>Regular Payroll</b>.";

// CL - HR - SALARY SCHEDULE
$lang['err_max_grade_step'] = "Entered <b>Salary Grade</b> or <b>Step Increment</b> is less than the maximum record in work experience";
$lang['err_diff_year']  	= "Can only set to active if <b>Effective Year</b> is equal to current year";
$lang['other_fund_flag']  	= "There can only be one active salary schedule per other fund flag.";

// Payroll - GENERAL PAYROLL -
$lang['err_change_payroll_status']	= "Remittance for this payroll is already generated. You can no longer change the status.";

// CUSTOM: LEAVE APPLICATION
$lang['err_approved_leave'] = "Sorry, you don't have permission to approve the leave application. Only the assigned <b>Immediate Supervisor</b> is allowed. Please contact the System's Administrator.";