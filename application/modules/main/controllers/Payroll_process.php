<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Payroll_process extends Main_Controller {
	
	private $permission_module = MODULE_PAYROLL_GENERAL_PAYROLL;
	private $form2316;
	private $payroll_common;
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('payroll_process_model', 'payroll_process');
		$this->load->model('common_model', 'common');
		
		$this->permission_module 	= MODULE_PAYROLL_GENERAL_PAYROLL;
		
		$this->form2316 			= modules::load('main/payroll_form_2316');
		$this->form2307 			= modules::load('main/payroll_form_2307');
		
		$this->payroll_common 		= modules::load('main/payroll_common');
	}
	
	public function save_payroll($valid_data, $params, $log_audit_trail=TRUE)
	{
		$payroll_summary_id = 0;
		try
		{
			$info				= array();
			$status 			= FALSE;
			$msg 				= $this->lang->line('data_not_saved');

			Main_Model::beginTransaction();
			
			$payroll_summary_id = $this->_save_payroll($valid_data, $params, $log_audit_trail);

			Main_Model::commit();
			$msg 				= $this->lang->line('data_saved');
			$status 			= TRUE;			
		}
		catch(Exception $e)
		{
			Main_Model::rollback();
			$msg 				= $e->getMessage();
			RLog::error($msg);
		}
		
		$info['msg']				= $msg;
		$info['status']				= $status;
		$info['payroll_summary_id']	= $payroll_summary_id;

		return $info;		
	}
	
	private function _save_payroll($valid_data, $params, $log_audit_trail=TRUE, $selected_employees=NULL)
	{
		try
		{
			if ($log_audit_trail)
			{
				// PREPARE DATA FOR AUDIT TRAIL
				$table              = $this->common->tbl_payout_summary;
				$audit_table[]      = $table;
				$audit_schema[]     = Base_Model::$schema_core;
				$audit_module       = $this->permission_module;
			}
			
			//INSERT PAYROLL SUMMARY
			$payroll_summary								= array();
			$payroll_summary['payout_type_flag']			= PAYOUT_TYPE_FLAG_REGULAR;
			$payroll_summary['bank_id']						= $valid_data['bank_id'];
			$payroll_summary['attendance_period_hdr_id']	= $valid_data['payroll_period'];
			$payroll_summary['process_start_date']			= date('Y-m-d H:i:s');
			$payroll_summary['processed_by']				= $this->session->user_id;
			$payroll_summary['certified_by']				= $valid_data['certified_correct_by_id'];
			$payroll_summary['approved_by']					= $valid_data['approved_by_id'];
			$payroll_summary['certified_cash_by']			= $valid_data['certified_cash_by_id'];
			$payroll_summary['payout_status_id']			= PARAM_PAYOUT_STATUS_INITIAL;
			
			$table 											= $this->common->tbl_payout_summary;
			
			// SET DETAIL FOR AUDIT TRAIL
			$curr_detail[]  		= array($payroll_summary);
			$audit_action_type 		= AUDIT_INSERT;
			
			$payroll_summary_id 	= NULL;
			
			if (isset($params['id']) && $params['action'] == ACTION_EDIT)
			{
				$audit_action_type 	= AUDIT_UPDATE;
				
				// get payroll_hdr_id
				// $field 				= array('payroll_summary_id', 'payout_status_id');
				$field 				= array('payroll_summary_id', 'payout_status_id', 'attendance_period_hdr_id'); //jendaigo: include getting of attendance_period_hdr_id
				$key   				= $this->get_hash_key ('payroll_summary_id');
				$where 				= array();
				$where[$key] 		= $params['id'];
				$table 				= $this->common->tbl_payout_summary;
				$payout_summary_rec = $this->common->get_general_data($field, $table, $where, FALSE);
				if ( ! isset($payout_summary_rec))
					throw new Exception ( $this->lang->line ( 'err_invalid_request' ) );
					
				if ($payout_summary_rec['payout_status_id'] != PARAM_PAYOUT_STATUS_INITIAL)
					throw new Exception ($this->lang->line ( 'unable_continue_record_submitted' ));

				$payroll_summary_id = $payout_summary_rec['payroll_summary_id'];
				$prev_detail[]		= $payout_summary_rec;

				// UPDATE DEDUCTION PAID COUNT [DEDUCT PAID COUNT]
				$this->payroll_process->update_deduction_paid_count($payroll_summary_id, $selected_employees, NULL, FALSE);				
				
				// if EDIT, clear data first before re-computation				
				$this->clear_payroll_data($params['id'], $selected_employees);
				
				// update summary table
				$where				= array('payroll_summary_id' => $payroll_summary_id);
				$this->common->update_general_data($table, $payroll_summary, $where);
			}
			else
			{
				$prev_detail[]		= array();
				$payroll_summary_id = $this->common->insert_general_data($table, $payroll_summary, TRUE);				
			}
			
			$audit_action[]  	= $audit_action_type;

			$where				= array();
			
			// INSERT PAYROLL SUMMARY DATES
			$table 											= $this->common->tbl_payout_summary_dates;
			$payroll_summary_dates							= array();
			$payroll_summary_dates['payout_summary_id']		= $payroll_summary_id;
			
			RLog::info('Valid Data Payout Count: ' . $valid_data['payout_count']);
			for ($pd=1; $pd<=$valid_data['payout_count']; $pd++)
			{
				$payout_date_key = 'payout_date_' . $pd;
				if ( ! isset($valid_data[$payout_date_key]))
					throw new Exception ($this->lang->line ( 'param_dates_not_defined' ));
				
				$payroll_summary_dates['payout_date_num']	= $pd;
				$payroll_summary_dates['effective_date']	= $valid_data[$payout_date_key];
				$this->common->insert_general_data($table, $payroll_summary_dates, TRUE);
				
				// SET DETAIL FOR AUDIT TRAIL
				if ($log_audit_trail)
				{
					$audit_table[]  = $table;
					$audit_action[]	= $audit_action_type;
					$curr_detail[]  = array($payroll_summary_dates);
				}				
			}
			
			// UPDATE ATTENDANCE PERIOD STATUS TO BEING PROCESSED
			$this->payroll_process->update_attendance_period($payroll_summary['attendance_period_hdr_id'], ATTENDANCE_PERIOD_PROCESSED);
			
			// ACTIVITY TO BE LOGGED ON THE AUDIT TRAIL
			if ($log_audit_trail)
			{			
				$activity = "%s has been added";
				
				if ($params['action'] == ACTION_EDIT)
					$activity = "%s has been updated";
				
				$activity = sprintf($activity, 'General Payroll');
				
				$this->payroll_common->log_audit_trail($activity, $audit_module, $prev_detail, $curr_detail, $audit_action, $audit_table, $audit_schema);
			}

		}
		catch(Exception $e)
		{
			RLog::error($e->getMessage());
			throw $e;
		}
		
		return $payroll_summary_id;
	}

	/*
	 * This function does the computation for general payroll.
	 * @param valid_data			Validated parameters (including the payout dates)
	 * @param params				All paramaters submitted from front-end
	 * @param selected_employees 	Selected employees, used by re-compute payroll by employee
	 */
	public function process_payroll($valid_data, $params, $selected_employees=NULL)
	{
		RLog::info('*** START: Inside Payroll_process ... ***' . '['.date('Y-m-d h:i:s').']');
		RLog::info($selected_employees);
		
		$payroll_summary_id 	= 0;
		
		try
		{
			$info				= array();
			$status 			= FALSE;
			$msg 				= $this->lang->line('data_not_saved');
			
			// ====================== jendaigo : start : get premium id ============= //
			$sys_params 		= $this->payroll_process->get_sys_gen_params();
			$prem_id			= $sys_params[PARAM_COMPENSATION_ID_PREMIUM];
			// ====================== jendaigo : start : get premium id ============= //
			
			// $field 				= array('payroll_summary_id', 'payout_status_id');
			$field 				= array('payroll_summary_id', 'payout_status_id', 'attendance_period_hdr_id'); //jendaigo : get attendance_period_hdr_id
			$key   				= $this->get_hash_key ('payroll_summary_id');
			$where 				= array();
			$where[$key] 		= $params['id'];
			$table 				= $this->common->tbl_payout_summary;
			$payout_summary_rec = $this->common->get_general_data($field, $table, $where, FALSE);
			
			$payroll_summary_id = $payout_summary_rec['payroll_summary_id'];
			
			$payout_remittance = $this->payroll->get_payroll_remittance(array($payroll_summary_id));
			if(!EMPTY($payout_remittance)) {
				$msg	= $this->lang->line('err_change_payroll_status');
				$status = FALSE;
			}
			else
			{
				Main_Model::beginTransaction();
	
				// PREPARE DATA FOR AUDIT TRAIL
				$table              = $this->payroll_process->tbl_payout_summary;
				$audit_table[]      = $table;
				$audit_schema[]     = Base_Model::$schema_core;
				$audit_module       = $this->permission_module;
				// GET THE EMPLOYEE ID OF THE CURRENT USER
				$where                                     	= array();
				$where[$this->get_hash_key('employee_id')] 	= $this->session->userdata('user_pds_id');
				$employee_info                             	= $this->common->get_general_data(array('employee_id'), $this->common->tbl_employee_personal_info, $where, FALSE);
				
				$payroll_summary_id 						= $this->_save_payroll($valid_data, $params, FALSE, $selected_employees);
				if (empty($payroll_summary_id))
					throw new Exception($this->lang->line('data_not_saved'));

				//IF PROCESS ALL EMPLOYEES FOR PAYROLL TYPE, get selected employees from payout_employees where include_flag = 'Y' 
				//ELSE, use selected_employees variable
				$payout_employees 		= array();
				if (is_null($selected_employees))
					$payout_employees 		= $this->payroll_process->get_payout_employees($payroll_summary_id); 
				else
				{
					if (is_array($selected_employees))
						$payout_employees['employees'] = implode(',', $selected_employees);
					else
						$payout_employees['employees'] = $selected_employees;
				}

				RLog::info('==========S1: EMPLOYEES ==========');
				RLog::info($payout_employees);
				RLog::info('==========E1: EMPLOYEES ==========');
				
				//GET PAYROLL TYPE DETAILS(Period, Payout, Employment status, etc...)
				$payroll_period_details		= $this->payroll_process->get_payroll_period_by_type($valid_data['payroll_period']);
				
				$offices 					= explode(',', $payroll_period_details['office_id']);
				$employment_status 			= explode(',', $payroll_period_details['employment_status_id']);
				$minimum_monthly_amount		= $payroll_period_details['min_monthly_payout'];
				$mwe_denominator			= $payroll_period_details['mwe_denominator'];
				
				if (empty($offices) || empty($employment_status))
					throw new Exception('Payroll Type Offices/Status: ' . $this->lang->line('param_not_defined'));
				
				//INSERT PAYROLL HEADER
				$included_employees			= array();
				if ( ! empty($payout_employees) && ! empty($payout_employees['employees']))
					$included_employees 		= explode(',', $payout_employees['employees']);
				else
					throw new Exception('Employees: ' . $this->lang->line('param_not_defined'));	
		
				// $this->payroll_process->insert_payroll_header($payroll_summary_id, $employment_status, $offices, $included_employees);
				// ====================== jendaigo : start : include attendance_period_hdr_id ============= //
				$this->payroll_process->insert_payroll_header($payroll_summary_id, $employment_status, $offices, $included_employees, $payout_summary_rec['attendance_period_hdr_id']);
				// ====================== jendaigo : end : include attendance_period_hdr_id ============= //
				
				RLog::info('==========S2: EMPLOYEES ==========');
				RLog::info($selected_employees);
				RLog::info('==========E2: EMPLOYEES ==========');			
	
	
				// GET EMPLOYMENT TYPE WITH TENURE
				$employment_type_tenure = $this->payroll_process->get_doh_employ_type();
				if (empty($employment_type_tenure))
					throw new Exception('DOH Employment Type: ' . $this->lang->line('param_not_defined'));
				
				// GET DAY 1 OF PAYOUT MONTH 
				$dte 			= new DateTime($valid_data['payout_date_1']);
				if ($payroll_period_details['salary_frequency_flag'] === SALARY_MONTHLY)
				{
					$pay_start_date		= $dte->format('Y-m-01');
					$pay_end_date		= $dte->format('Y-m-t');
				}
				else
				{
					$pay_start_date		= $payroll_period_details['date_from'];
					$pay_end_date		= $payroll_period_details['date_to'];			
				}
				
				//GET AND INSERT COMPENSATIONS
				$table 									= $this->payroll_process->tbl_payout_details;
				$valid_data['salary_frequency_flag']	= $payroll_period_details['salary_frequency_flag'];
				$valid_data['payout_count']				= $payroll_period_details['payout_count'];
				
				$payroll_period_details['payout_month_dte1']	= $pay_start_date;
				$payroll_period_details['payout_month_dte2']	= $pay_end_date;
				
				//RLog::error("BEFORE GET_COMPENSATIONS [$salary_frequency_flag]");
				//RLog::error($payroll_period_details);

				$compensations 							= $this->get_compensations($payroll_summary_id, $payroll_period_details, $employment_type_tenure, $employment_status, $offices, $valid_data, $selected_employees);

				$header_total_amounts = array();
				
				if( ! empty($compensations))
				{
					$this->common->insert_general_data($table, $compensations);
					
					foreach ($compensations as $cmpnstn)
					{
						$payroll_hdr_id = $cmpnstn['payroll_hdr_id'];
						$amount 		= $cmpnstn['amount'];
						
						$header_total_amounts[$payroll_hdr_id]['total_income'] = ( empty($header_total_amounts[$payroll_hdr_id]['total_income']) ? $amount : 
							($header_total_amounts[$payroll_hdr_id]['total_income'] + $amount) ); 
					}
				}
				
				///GET AND INSERT DEDUCTIONS
				$deductions 			= $this->get_deductions($payroll_summary_id, $payroll_period_details, $employment_status, $offices, $valid_data, $selected_employees);

				// COMPUTE WITHHOLDING TAX
				$sys_param_stat_approved	= NULL;
	
				// S: transferred above
				//$dte 				= new DateTime($valid_data['payout_date_1']);
				//$pay_start_date	= $dte->format('Y-m-01');
				// E: transferred above
				$employees_with_tax			= array();
				
				$deduction_taxes 			= array();
				if ($payroll_period_details['tax_table_flag'] == TAX_MONTHLY_2307)
					// $deduction_taxes 			= array(DEDUC_BIR_EWT, DEDUC_BIR_VAT
					$deduction_taxes 			= array(DEDUC_BIR_EWT, DEDUC_BIR_VAT, DEDUC_BIR_EIGHT); //jendaigo : include 8% Income Tax Rate
				else
				{
					$deduction_taxes 			= DEDUC_BIR;
				}			

				// START: get employees with tax
				$tmp_employees_with_tax 	= $this->payroll_process->get_employee_with_deduction($pay_start_date, $included_employees, $deduction_taxes);

				//RLog::info('S1: ===included employees=');
				//RLog::info($tmp_employees_with_tax);
				
				foreach($tmp_employees_with_tax as $key=>$emp)
				{
					$employees_with_tax[] 		= $emp['employee_id'];
				}
			
				//RLog::info('S2: ===included employees=');
				//RLog::info($employees_with_tax);
				// END: get employees with tax

				if (! empty($employees_with_tax))
				{
					if ($payroll_period_details['tax_table_flag'] == TAX_ANNUALIZED)
					{
						$payout_dte_idx 		= 'payout_date_' . (empty($valid_data['payout_count']) ? 1 : $valid_data['payout_count']);
						$base_payout_date 		= $valid_data[$payout_dte_idx];
						$dte 					= new DateTime($base_payout_date);
						$base_payout_date		= $dte->format('Y-m-d');
						//unset($dte);
						
						$form_2316_ids 			= $this->form2316->construct_form_2316($payroll_period_details['tax_table_flag'], $base_payout_date, $deductions, $payroll_summary_id, $employees_with_tax, $sys_param_stat_approved, TRUE, TRUE, TRUE, $mwe_denominator);
						$monthly_taxes 			= $this->form2316->compute_withholding_tax(TAX_ANNUALIZED, $base_payout_date, $payroll_summary_id, $valid_data, $employees_with_tax);
					}
					else if ($payroll_period_details['tax_table_flag'] == TAX_MONTHLY_2316)
					{
						$form_2316_ids 			= $this->form2316->construct_form_2316($payroll_period_details['tax_table_flag'], $valid_data['payout_date_1'], $deductions, $payroll_summary_id, $employees_with_tax, $sys_param_stat_approved, TRUE, FALSE, TRUE, $mwe_denominator);
						$monthly_taxes 			= $this->form2316->compute_withholding_tax(TAX_MONTHLY_2316, $valid_data['payout_date_1'], $payroll_summary_id, $valid_data, $employees_with_tax);
					}
					else if ($payroll_period_details['tax_table_flag'] == TAX_MONTHLY_2307)
					{
						$payout_dte_idx 		= 'payout_date_' . (empty($valid_data['payout_count']) ? 1 : $valid_data['payout_count']);
						
						$base_payout_date 		= $valid_data[$payout_dte_idx];
						$dte 					= new DateTime($base_payout_date);
						$payout_year			= $dte->format('Y');
						$payout_month			= $dte->format('m');
						//$monthly_taxes = $this->form2307->compute_withholding_tax_2307($payout_year, $payout_month, $payroll_summary_id, $valid_data, $employees_with_tax);
						// $monthly_taxes 			= $this->form2307->construct_form_2307($payout_year, $dte, $payroll_summary_id, $employees_with_tax, $sys_param_stat_approved, TRUE);
						
						// ====================== jendaigo : start : include pay_start_date and pay_end_date ============= //
						$monthly_taxes 			= $this->form2307->construct_form_2307($payout_year, $dte, $payroll_summary_id, $employees_with_tax, $sys_param_stat_approved, $pay_start_date, $pay_end_date, TRUE);
						// ====================== jendaigo : end : include pay_start_date and pay_end_date ============= //

						if (empty($deductions))
						{
							$deductions 		= $monthly_taxes;
							$monthly_taxes 		= array();
						}
						else if (count($deductions) < count($monthly_taxes))
						{
							$oth_deductions		= $deductions; 
							$deductions 		= $monthly_taxes;
							$monthly_taxes		= $oth_deductions;
						}
					}
					else
					{
						throw new Exception('TAX TABLE FLAG: ' . $this->lang->line('sys_param_not_defined'));
					}
				}

				//UPDATE HEADER TOTAL INCOME, TOTAL DEDUCTIONS, NET PAY
				$payout_for_the_month		= array();
				if ( ! empty($deductions))
				{
					// check if more than one payroll computation
					// needs to check previous payout for the same month
					$monthly_payroll_count	= $payroll_period_details['monthly_payroll_count'];
					if ($monthly_payroll_count > 1)
					{
						$pay_year 				= $dte->format('Y');
						$pay_month				= intval($dte->format('m'));
						$payout_for_the_month	= $this->payroll_process->get_payout_for_the_month(PAYOUT_DETAIL_TYPE_DEDUCTION, $base_payout_date, $pay_year, $pay_month, 
							$payroll_summary_id,  $payroll_period_details['payroll_type_id'],
							$included_employees);
						
						//RLog::error('-- DEDUCTIONS: payout for the month --');
						//RLog::error($payout_for_the_month);
					}

					$deductions 			= $this->_check_split_deductions($deductions, $monthly_taxes, $monthly_payroll_count, $valid_data, $header_total_amounts, $minimum_monthly_amount, $payout_for_the_month);

					foreach ($deductions as $ddctn)
					{
						$payroll_hdr_id 											= $ddctn['payroll_hdr_id'];
						$amount 													= $ddctn['amount'];
						$header_total_amounts[$payroll_hdr_id]['total_deductions'] 	= (empty($header_total_amounts[$payroll_hdr_id]['total_deductions']) ? 0 
																					: $header_total_amounts[$payroll_hdr_id]['total_deductions']);
						$header_total_amounts[$payroll_hdr_id]['total_deductions'] += $amount;
					}
					$table 			= $this->payroll_process->tbl_payout_details;

					//RLog::error($deductions);
					$this->common->insert_general_data($table, $deductions);
				}
				
				if( ! empty($header_total_amounts))
				{

					foreach ($header_total_amounts as $hdr_id => $hdr_amounts)
					{
						$total_income 					= (isset($hdr_amounts['total_income']) ? $hdr_amounts['total_income'] : 0.00);
						$total_deductions 				= (isset($hdr_amounts['total_deductions']) ? $hdr_amounts['total_deductions'] : 0.00);
						$net_pay 						= $total_income - $total_deductions; 
						
						$table 							= $this->payroll_process->tbl_payout_header;
						$fields 						= array();
						$fields['total_income'] 		= $total_income;
						$fields['total_deductions'] 	= $total_deductions;
						$fields['net_pay'] 				= $net_pay;
						$where 							= array('payroll_hdr_id' => $hdr_id);
						$this->common->update_general_data($table, $fields, $where);
					}
				}

				// UPDATE DEDUCTION PAID COUNT [ADD PAID COUNT]
				$this->payroll_process->update_deduction_paid_count($payroll_summary_id, $selected_employees, $payroll_period_details['payout_month_dte2'], TRUE);
				
				// POPULATE DATA FOR PAYOUT HISTORY
				$hist_data						= array();
				$hist_data['payout_summary_id']	= $payroll_summary_id;
				$hist_data['payout_status_id']	= PARAM_PAYOUT_STATUS_INITIAL;
				$hist_data['hist_date']        	= date('Y-m-d H:i:s');
				$hist_data['action_id']      	= ACTION_PROCESS;
				$hist_data['employee_id']      	= $employee_info['employee_id'];
				
				// INSERT DATA TO PAYOUT HISTORY
				$this->common->insert_general_data($this->common->tbl_payout_history, $hist_data);
	
				// SET DETAIL FOR AUDIT TRAIL
				$audit_table 		= array();
				$audit_action		= array();
				$audit_action[] 	= AUDIT_PROCESS;
				$prev_detail 		= array(0);
				$curr_detail 		= array();			
				
				// ACTIVITY TO BE LOGGED ON THE AUDIT TRAIL
				$activity 			= "%s has been processed";
				$activity 			= sprintf($activity, 'General Payroll');
	
				$this->payroll_common->log_audit_trail($activity, $audit_module, $prev_detail, $curr_detail, $audit_action, $audit_table, $audit_schema);		
				
				Main_Model::commit();
				$msg 				= $this->lang->line('data_long_processing');
				$status 			= TRUE;
			}
		}
		catch(PDOException $e)
		{
			$msg = '';
			Main_Model::rollback();
			$pdo_err 	= get_pdo_message($e->errorInfo);
			
			if ( ! empty($pdo_err))
				$msg 	= sprintf($this->lang->line($pdo_err[0]), $pdo_err[1]);
				
			if (empty($msg))
				$msg 	= $e->getMessage();
				
			$status		= FALSE;
			RLog::error($msg);
		}
		catch(Exception $e)
		{
			Main_Model::rollback();
			$msg 		= $e->getMessage();
			$status		= FALSE;
			RLog::error($msg);
		}
		
		RLog::info('*** END: Inside Payroll_process ... ***' . '['.date('Y-m-d h:i:s').']');

		$info['msg']				= $msg;
		$info['status']				= $status;
		$info['payroll_summary_id']	= $payroll_summary_id;

		return $info;
	}

	/*
	 * @param $payroll_summary_id	Unique ID of this Payout
	 * @param $payroll_period_details Contains Attendance period date from/to, monthly payroll count
	 * @param $employment_status	Employment status covered in this Payout (refer to param_payroll_type_status_offices)
	 * @param $offices				Offices covered in this Payout (refer to param_payroll_type_status_offices)
	 * @param $param_data			Parameters supplied in the Entry screen (added salary_frequency_flag)
	 * @param selected_employee		Employees selected for re-processing of payroll
	 */
	public function get_compensations($payroll_summary_id, $payroll_period_details, $employment_type_tenure, $employment_status, $offices, $param_data, $selected_employees=NULL)
	{
		RLog::info('-- ie
		--');
		RLog::info($selected_employees);
		
		try
		{
			$covered_date_from			= $payroll_period_details['payout_month_dte1'];
			$covered_date_to			= $payroll_period_details['payout_month_dte2'];
			$salary_frequency_flag		= $payroll_period_details['salary_frequency_flag'];
			$payout_count_per_comp		= $payroll_period_details['payout_count'];
			$monthly_payroll_count		= $payroll_period_details['monthly_payroll_count'];
			$payroll_type_id			= $payroll_period_details['payroll_type_id'];
			
			// START: Get compensation distribution
			$table 						= $this->payroll_process->tbl_param_payroll_compensations;
			$fields						= array('compensation_id', 'GROUP_CONCAT(payout_date_num separator \',\') payout_date_num');
			$where 						= array('payroll_type_id' => $param_data['payroll_type']);
			$order_by 					= array('compensation_id' => 'ASC', 'payout_date_num' => 'ASC');
			$group_by 					= array('compensation_id');
			$compensation_payout_nums 	= $this->common->get_general_data_group_concat($fields, $table, $where, TRUE, $order_by, $group_by);
			$compensation_payout_nums 	= set_key_value($compensation_payout_nums, 'compensation_id', 'payout_date_num');
			// END: Get compensation distribution
			
			if (count($compensation_payout_nums) == 0)
				throw new Exception('PAYOUT NUM for all compensation: ' . $this->lang->line('sys_param_not_defined'));
			
			// payout dates
			$payout_dates 				= array();
			$pay_date_cnt 				= 1;
			
			while ($pay_date_cnt <= $param_data['payout_count'])
			{
				$payout_date_key 			= 'payout_date_' . $pay_date_cnt;
				if (isset($param_data[$payout_date_key]))
					$payout_dates[] 		= $param_data[$payout_date_key];
				else
					break;
				
				$pay_date_cnt++;
			}
				
			// $compensations 				= $this->payroll_process->get_payout_compensations($payroll_summary_id, $covered_date_to, $employment_type_tenure, $employment_status, $offices, $selected_employees);
			// ====================== jendaigo : start : include variable covered_date_from ============= //
			$compensations 				= $this->payroll_process->get_payout_compensations($payroll_summary_id, $covered_date_to, $employment_type_tenure, $employment_status, $offices, $selected_employees, $covered_date_from);
			// ====================== jendaigo : end : include variable covered_date_from ============= //
			
			// check if more that one payroll computation
			// needs to check previous payout for the same month
			$payout_for_the_month		= array();
			$use_attendance_count		= FALSE;
			if ($monthly_payroll_count > 1)
			{
				$use_attendance_count 		= TRUE;
			}
			
			$dtl_count 				= 0;
			$compensation_dtl		= array();
			
			$sys_params 			= $this->payroll_process->get_sys_gen_params();
			$filed_leave_spl 		= $this->payroll_process->get_special_privilege_leave($payroll_period_details['date_from'], $payroll_period_details['date_to'], $selected_employees);

			// ====================== jendaigo : start : get employees with premium compensation ============= //
			$premium_employees = array();
			foreach ($compensations as $cmpnstn) 
			{
				if($cmpnstn['report_short_code'] == $sys_params[PARAM_COMPENSATION_PREMIUM])
					$premium_employees[$cmpnstn['employee_id']] = $cmpnstn;
			}
			// ====================== jendaigo : end : get employees with premium compensation ============= //

			foreach ($compensations as $cmpnstn) 
			{
				RLog::info('Computing compensation of [' . $cmpnstn['employee_id'] . '] ['.$cmpnstn['compensation_id'].'] ['.$cmpnstn['inherit_parent_id_flag'].'] ['.$cmpnstn['parent_compensation_id'].'] ['.$cmpnstn['anniv_emp_date'].']');
				
				$payout_nums 		= isset($compensation_payout_nums[$cmpnstn['compensation_id']]) ? $compensation_payout_nums[$cmpnstn['compensation_id']] : NULL;
				if ( ! isset ($payout_nums))
					throw new Exception('PAYOUT NUM for Compensation: ' . $cmpnstn['compensation_id'] . ' ' . $this->lang->line('sys_param_not_defined'));

				$payout_nums 		= explode(',', $payout_nums);
				
				$compensation_id 	= $cmpnstn['compensation_id'];
							
				// get attendance count (used by basic salary deduction, prorate by days present)
				$attendance_count	= $this->payroll_common->get_employee_attendance($cmpnstn['employee_id'], $param_data['payroll_period'], $filed_leave_spl);

				$prorated_rates 	= array();
				if ($cmpnstn['pro_rated_flag'] != PRORATE_NA)
				{
					$field          = array('compensation_id', 'from_val', 'to_val', 'percentage', 'separated_flag') ;
					$table          = $this->common->tbl_param_compensation_prorated;
					$where          = array('compensation_id' => $compensation_id);
					$order_by		= array('from_val' => 'ASC');
					$order_by		= array('from_val' => 'ASC');
					$prorated_rates	= $this->common->get_general_data($field, $table, $where, TRUE, $order_by);
				}				
				
				$ret_amount 		= array();
			
				switch ($cmpnstn['compensation_type_flag']) 
				{
					case COMPENSATION_TYPE_FLAG_FIXED:
						#FIXED AMOUNT
						$param_dates 							= array();
						$param_dates['compensation_start_date'] = $covered_date_from;
						$param_dates['compensation_end_date']	= $covered_date_to;
						
						$tenure_rqmt = array();
						$tenure_rqmt['tenure_rqmt_flag']		= $cmpnstn['tenure_rqmt_flag'];
						$tenure_rqmt['tenure_rqmt_val']			= $cmpnstn['tenure_rqmt_val'];
						
						$cmpnstn['project_amount'] 				= NO;

						$payout_date_var = $payout_dates[0];
						if (DAYS_PRESENT_PAYROLL_PERIOD == 1)
							$payout_date_var = $payout_dates[0];
						else
							$payout_date_var = $payroll_period_details['date_from'];
						
						$fixed_amount_arr						= $this->payroll_common->compute_amount_with_frequency($cmpnstn, $payout_date_var, TRUE, $param_dates, 
																	$attendance_count, $param_data['payroll_period'], $use_attendance_count);
						$ret_amount[$compensation_id] 			= $fixed_amount_arr;
					break;
					
					case COMPENSATION_TYPE_FLAG_VARIABLE:
						#VARIABLE AMOUNT
						// $ret_amount[$compensation_id][KEY_AMOUNT]	= $this->payroll_common->compute_variable_compensation_type($cmpnstn, $covered_date_to, $attendance_count);
						
						// ====================== jendaigo : start : exclude compensation premium ============= //
						if($cmpnstn['report_short_code'] != $sys_params[PARAM_COMPENSATION_PREMIUM])
							$ret_amount[$compensation_id][KEY_AMOUNT]	= $this->payroll_common->compute_variable_compensation_type($cmpnstn, $covered_date_to, $attendance_count);							
						// ====================== jendaigo : end : exclude compensation premium ============= //
					break;
					
					case COMPENSATION_TYPE_FLAG_SYSTEM:
						#SYSTEM GENERATED AMOUNT

						$covered_date_to_sys 					= $covered_date_to; 
						if ($cmpnstn['compensation_code'] == $sys_params[PARAM_COMPENSATION_LONGEVITY_PAY])
							$covered_date_to_sys 				= $param_data[$payout_date_key];
						
						$cmpnstn['payroll_type_id']			= $payroll_type_id;
						
						$payroll_type_vars 					= array(KEY_SALARY_FREQ_FLAG => $salary_frequency_flag, 
								KEY_MWE_DENOMINATOR => 0.00, KEY_PAYROLL_TYPE_ID => $payroll_type_id);
						
						// $ret_amount							= $this->payroll_common->get_system_generated_amount($cmpnstn, $payroll_summary_id, $covered_date_from, $covered_date_to_sys, 
							// $param_data['payroll_period'], $sys_params, $attendance_count, $payroll_type_vars);
			
						// ====================== jendaigo : start : include premium compensation details ============= //
						$ret_amount							= $this->payroll_common->get_system_generated_amount($cmpnstn, $payroll_summary_id, $covered_date_from, $covered_date_to_sys, 
							$param_data['payroll_period'], $sys_params, $attendance_count, $payroll_type_vars, $premium_employees[$cmpnstn['employee_id']]);
						// ====================== jendaigo : end : include premium compensation details ============= //

					break;
				}

				if ($cmpnstn['pro_rated_flag'] != PRORATE_NA)
				{
					$tenure_in_months		= $cmpnstn['tenure_rqmt_val'];
					$salary_grade			= $cmpnstn['employ_salary_grade'];
					
					$tenure_num 			= $tenure_in_months;
					if ($cmpnstn['pro_rated_flag'] == PRORATE_DAY)
					{
						$working_days 		= $sys_params[PARAM_WORKING_DAYS];
						$lwop_count 		= ( ! empty($attendance_count[ATTENDANCE_STATUS_ABSENT]) ? $attendance_count[ATTENDANCE_STATUS_ABSENT] : 0);
						$lwop_count 		+= ( ! empty($attendance_count[ATTENDANCE_STATUS_LEAVE_WOP]) ? $attendance_count[ATTENDANCE_STATUS_LEAVE_WOP] : 0);
						$tenure_num 		= $working_days - $lwop_count;
					}

					$ret_amount[$compensation_id] = $this->payroll_common->compute_prorated_value($cmpnstn, $ret_amount[$compensation_id][KEY_AMOUNT], $tenure_num, $salary_grade, $prorated_rates);
				}

				// split amount
				$return_split_arr 			= $this->split_amount($cmpnstn, $payout_dates, $payout_nums, $ret_amount, $compensation_dtl, $dtl_count);
				$compensation_dtl			= $return_split_arr[KEY_DETAILS];
				$dtl_count					= $return_split_arr[KEY_DETAIL_COUNT];
				
				$dtl_count++;
			
			}	

			return $compensation_dtl;
		}
		catch(PDOException $e)
		{
			RLog::error($e->getMessage());
			throw $e;
		}
		catch(Exception $e)
		{
			RLog::error($e->getMessage());
			throw $e;
		}
	}
	
	public function split_amount($cmpnstn, $payout_dates, $payout_nums, $ret_amount, $compensation_dtl, $dtl_count)
	{
		try
		{
			RLog::info("split_amount compensation [".$cmpnstn['compensation_id']."]");
			
			$payout_num_count 	= count($payout_nums);
			
			// $key = compensation_id
			$payout_deductions 	= array();
			$tmp_amount			= 0.00;
			$tmp_less_amount	= 0.00;
			$tmp_orig_amount	= 0.00;
			
			foreach($ret_amount as $key => $amount)
			{
				/*
				RLog::info("S: loop amounts ... [".$cmpnstn['compensation_id']."]");
				RLog::info($amount);
				RLog::info("E: loop amounts ... [".$cmpnstn['compensation_id']."]");
				*/
				
				if ($key == KEY_DEDUCTION)
				{
					$payout_deductions 		= $amount[KEY_AMOUNT];
					continue;
				}
				
				if ( ! isset($amount[KEY_LESS_AMOUNT]) )
					$amount[KEY_LESS_AMOUNT] = 0.00;
				if ( ! isset($amount[KEY_ORIG_AMOUNT]) )
					$amount[KEY_ORIG_AMOUNT] = $amount[KEY_AMOUNT];
				
				$amounts			= array();
				$total_amount 		= 0;
				$total_less_amount 	= 0;
				//$total_orig_amount 	= 0;
				
				if ($payout_num_count > 1)
				{
					$div 				= $payout_num_count - 1;
					$tmp_amount 		= round(($amount[KEY_AMOUNT]/$payout_num_count), 2);
					$tmp_less_amount 	= round(($amount[KEY_LESS_AMOUNT]/$payout_num_count), 2);
					//$tmp_orig_amount 	= round(($amount[KEY_ORIG_AMOUNT]/$payout_num_count), 2);
					
					for($i=0; $i<$div; $i++)
					{
						$amounts[$i][KEY_AMOUNT] 		= $tmp_amount;
						$amounts[$i][KEY_LESS_AMOUNT] 	= $tmp_less_amount;
						$amounts[$i][KEY_ORIG_AMOUNT] 	= $tmp_orig_amount;
						$total_amount 		+= $tmp_amount;
						$total_less_amount 	+= $tmp_less_amount;
						//$total_orig_amount 	+= $tmp_orig_amount;
					}
					
					$amounts[$div][KEY_AMOUNT] 		= $amount[KEY_AMOUNT] - $total_amount;
					$amounts[$div][KEY_LESS_AMOUNT] = $amount[KEY_LESS_AMOUNT] - $total_less_amount;
					//$amounts[$div][KEY_ORIG_AMOUNT] = $amount[KEY_ORIG_AMOUNT] - $total_orig_amount;

				}
				else
				{
					$amounts[0][KEY_AMOUNT] 		= $amount[KEY_AMOUNT];
					$amounts[0][KEY_LESS_AMOUNT] 	= $amount[KEY_LESS_AMOUNT];
					$amounts[0][KEY_ORIG_AMOUNT] 	= $amount[KEY_AMOUNT] + $amount[KEY_LESS_AMOUNT];
				}
				
				for($i=0; $i<$payout_num_count; $i++)
				{
					$payout_num = $payout_nums[$i] - 1;
					
					$compensation_dtl[$dtl_count]	=	array(
						KEY_PAYROLL_HDR_ID 		=> $cmpnstn['payroll_hdr_id'],
						KEY_COMPENSATION_ID		=> ( ($cmpnstn['inherit_parent_id_flag'] == YES && ! empty($cmpnstn['parent_compensation_id']) 
														? $cmpnstn['parent_compensation_id'] : $key) ),
						KEY_RAW_COMPENSATION_ID	=> $key,
						KEY_EFFECTIVE_DATE 		=> $payout_dates[$payout_num],
						KEY_AMOUNT				=> $amounts[$i][KEY_AMOUNT],
						KEY_ORIG_AMOUNT			=> $amounts[$i][KEY_AMOUNT] + $amounts[$i][KEY_LESS_AMOUNT],
						KEY_LESS_AMOUNT			=> $amounts[$i][KEY_LESS_AMOUNT] * -1
					);
					$dtl_count++;
				}
			}

			$return_arr = array(
				KEY_DETAILS 			=> $compensation_dtl, 
				KEY_DETAIL_COUNT 		=> $dtl_count, 
				KEY_DETAIL_DEDUCTION 	=> $payout_deductions
			);

			return $return_arr;
		}
		catch(Exception $e)
		{
			RLog::error($e->getMessage());
			throw $e;
		}
	}



	public function get_deductions($payroll_summary_id, $payroll_period_details, $employment_status, $offices, $param_data, $selected_employees=NULL)
	{
		try
		{
			$covered_date_from		= $payroll_period_details['payout_month_dte1'];
			$covered_date_to		= $payroll_period_details['payout_month_dte2'];
			$monthly_payout_count	= $payroll_period_details['payout_count'];
			$monthly_payroll_count	= $payroll_period_details['monthly_payroll_count'];

			$payout_date			= $param_data['payout_date_1'];
			$dte 					= new DateTime($payout_date);
			$payout_date			= $dte->format('Y-m-d');
			$payout_month			= $dte->format('m');
			$deduct_date			= $dte->format('Y-m-01');
			
			// START: get sys param for statutory deduction codes
			$sys_param_type	= array(
				PARAM_DEDUCTION_ST_BIR, 
				PARAM_DEDUCTION_ST_GSIS, 
				PARAM_DEDUCTION_ST_PAGIBIG,
				PARAM_DEDUCTION_ST_PHILHEALTH, 
				PARAM_DEDUCTION_ST_SSS,  
				PARAM_DEDUCTION_ST_BIR_EWT, 
				PARAM_DEDUCTION_ST_BIR_VAT, 
				PARAM_DEDUCTION_ST_BIR_EIGHT, //jendaigo : include parameter for 8% Income Tax
				PARAM_DEDUCTION_ST_PHILHEALTH_QTR,
				PARAM_COMPENSATION_ID_BASIC_SALARY, 
				PARAM_COMPENSATION_ID_BASIC_SALARY_ADJUSTMENT,
				PARAM_DEDUCTION_ST_GSIS_ECF_ID
			);
			$sys_param_values 		= $this->common->get_sys_param_value($sys_param_type, TRUE);
			$sys_param_loc 			= array();
			foreach ($sys_param_values as $value)
			{
				$sys_param_loc[$value['sys_param_type']] = $value['sys_param_value'];
			}

			// throw exception if at least one of the variables is not defined
			if( ! isset($sys_param_loc[PARAM_DEDUCTION_ST_BIR]) OR ! isset($sys_param_loc[PARAM_DEDUCTION_ST_GSIS]) 
					OR ! isset($sys_param_loc[PARAM_DEDUCTION_ST_PAGIBIG])
					OR (! isset($sys_param_loc[PARAM_DEDUCTION_ST_PHILHEALTH]) AND ! isset($sys_param_loc[PARAM_DEDUCTION_ST_PHILHEALTH_QTR])) 
					OR ! isset($sys_param_loc[PARAM_COMPENSATION_ID_BASIC_SALARY]) )
				throw new Exception('Statutory Deductions: ' . $this->lang->line('sys_param_not_defined'));
			// END: get sys param for deduction codes
			
			// START: GSIS, PHILHEALTH, PAGIBIG Tables
			$gsis_table 		= $this->payroll_common->get_gsis_table($covered_date_to);
			$pagibig_table 		= $this->payroll_common->get_pagibig_table($covered_date_to);
			$philhealth_table 	= $this->payroll_common->get_philhealth_table($covered_date_to);
			$sss_table 			= $this->payroll_common->get_sss_table($covered_date_to);
			// END: GSIS, PHILHEALTH, PAGIBIG Tables

			/*-----DEDUCTION----*/
			// $deductions 		= $this->payroll_process->get_payout_deductions($payroll_summary_id, $deduct_date, $employment_status, $offices, $sys_param_loc, $selected_employees);
			// ====================== jendaigo : start : change deduct_date to attendance period end_date and add var covered_date_from============= //
			$deductions 		= $this->payroll_process->get_payout_deductions($payroll_summary_id, $covered_date_to, $employment_status, $offices, $sys_param_loc, $selected_employees, $covered_date_from);
			// ====================== jendaigo : end : change deduct_date to attendance period end_date and add var covered_date_from ============= //

			// ====================== jendaigo : start : employ_type_flag validation ============= //
			$employ_type_flag 			= array();
			$employ_type_flag_error 	= array();
			$err_msg            		= '';
			$employ_type_flag[] 		= PAYROLL_TYPE_FLAG_ALL;

			foreach ($deductions as $key => $ddctn)
			{	
				switch ($ddctn['we_employ_type_flag']) 
				{
					case DOH_GOV_APPT:
					case DOH_GOV_NON_APPT:
						$employ_type_flag[] = PAYROLL_TYPE_FLAG_REG;
						break;
					case DOH_JO:
						$employ_type_flag[] = PAYROLL_TYPE_FLAG_JO;
						break;
				}
				
				if (!in_array($ddctn['employ_type_flag'], $employ_type_flag))
				{
					if (!in_array($ddctn['deduction_code'], $employ_type_flag_error['deduction_code']))
						$employ_type_flag_error['deduction_code'][] =  $ddctn['deduction_code'];
					if (!in_array($ddctn['fullname'], $employ_type_flag_error['fullname']))
						$employ_type_flag_error['fullname'][] =  $ddctn['fullname'];
				}
			}
			
			if(!empty($employ_type_flag_error))
			{
				$err_msg['deduction_code'] = implode(", ",$employ_type_flag_error['deduction_code']);
				$err_msg['employee_list'] = implode("<br>",$employ_type_flag_error['fullname']);
				throw new Exception('Please remove Deduction(s):<br>' . $err_msg['deduction_code'] . '.<br><br>' . $err_msg['employee_list']);
			}
			// ====================== jendaigo : end : employ_type_flag validation ============= //

			$deductions_dtl		= array();

			foreach ($deductions as $ddctn)
			{
				RLog::info('Computing deductions for : ['.$ddctn['employee_id'].']['.$ddctn['deduction_id'].']['.$ddctn['deduction_code'].']  ['.$ddctn['inherit_parent_id_flag'].'] ['.$ddctn['parent_deduction_id'].']');

				$deduct 			= TRUE;
				if ($ddctn['payment_count'] > 0 && $ddctn['payment_count'] <= $ddctn['paid_count'])
					$deduct = FALSE;

				$employee_amount 	= 0.00;
				$employer_amount 	= 0.00;
				
				$with_ecf 			= FALSE;
				
				if ($ddctn['statutory_flag'] === YES OR $ddctn['deduction_type_flag'] === DEDUCTION_TYPE_FLAG_SYSTEM)
				{
					#STATUTORY AMOUNT OR SYSTEM-COMPUTED					
					
					// FORM 2316 (tax) is computed after all compensations/deductions
					if ($sys_param_loc[PARAM_DEDUCTION_ST_BIR] == $ddctn['deduction_code'] 
							OR $sys_param_loc[PARAM_DEDUCTION_ST_BIR_EWT] == $ddctn['deduction_code']
							OR $sys_param_loc[PARAM_DEDUCTION_ST_BIR_VAT] == $ddctn['deduction_code']
							OR $sys_param_loc[PARAM_DEDUCTION_ST_BIR_EIGHT] == $ddctn['deduction_code']) //jendaigo : include parameter for 8% Income Tax
						continue;
						
					$share_amounts = array();
					switch ($ddctn['deduction_code'])
					{
						case $sys_param_loc[PARAM_DEDUCTION_ST_GSIS]:
							$use_basic_amount 	= (FLAG_USE_BASIC_AMOUNT === YES);
							$share_amounts 		= $this->payroll_common->compute_gsis($covered_date_to, $ddctn, $gsis_table, $use_basic_amount);
							if ( (! empty($share_amounts[KEY_PERSONAL_ECF])) OR (! empty($share_amounts[KEY_GOVERNMENT_ECF])) )
								$with_ecf = TRUE; 
							break;
						case $sys_param_loc[PARAM_DEDUCTION_ST_PAGIBIG]:
							$share_amounts 		= $this->payroll_common->compute_pagibig($covered_date_to, $ddctn, $pagibig_table); 
							break;
						case $sys_param_loc[PARAM_DEDUCTION_ST_PHILHEALTH]:
						case $sys_param_loc[PARAM_DEDUCTION_ST_PHILHEALTH_QTR]: 
							$share_amounts 		= $this->payroll_common->compute_philhealth($covered_date_to, $ddctn, $philhealth_table);
							break;
						case $sys_param_loc[PARAM_DEDUCTION_ST_SSS]: 
							$share_amounts 		= $this->payroll_common->compute_sss($covered_date_to, $ddctn, $sss_table);
							break;
					}
				}
				else
				{
					switch ($ddctn['deduction_type_flag'])
					{
						case DEDUCTION_TYPE_FLAG_FIXED:
							#FIXED AMOUNT
							$employee_amount	= $ddctn['amount'];
						break;
						
						case DEDUCTION_TYPE_FLAG_VARIABLE:
							#VARIABLE AMOUNT
							$employee_amount	= $this->_compute_variable_deduction_type($ddctn, $covered_date_to);
						break;
						
						case DEDUCTION_TYPE_FLAG_SCHEDULED:
							#SCHEDULED AMOUNT
							$employee_amount	= $this->_compute_scheduled_deduction($ddctn, $deduct_date);	
						break;
					}
					$share_amounts 		= array('personal_share' => $employee_amount);
				}

				$deduct_amounts 	= $this->_compute_deduction_share_frequency($ddctn, $share_amounts, $payout_month, $sys_param_loc);
				$employee_amount	= (isset($deduct_amounts[KEY_PERSONAL_SHARE]) ? $deduct_amounts[KEY_PERSONAL_SHARE] : 0);
				$employer_amount	= (isset($deduct_amounts[KEY_GOVERNMENT_SHARE]) ? $deduct_amounts[KEY_GOVERNMENT_SHARE] : 0);
			
				RLog::info('get_deductions deduct ? ['.$ddctn['employee_id'].'] ['.$ddctn['deduction_id'].'] ['.$deduct.'] ['.$employee_amount.']');
				
				$deductions_dtl[$ddctn[KEY_PAYROLL_HDR_ID]][]	=	array(
					KEY_EMPLOYEE_ID 		=> $ddctn['employee_id'],
					KEY_PAYROLL_HDR_ID 		=> $ddctn['payroll_hdr_id'],
					KEY_DEDUCTION_ID 		=> ( ($ddctn['inherit_parent_id_flag'] == YES && ! empty($ddctn['parent_deduction_id']) ? 
													$ddctn['parent_deduction_id'] :  $ddctn['deduction_id']) ),
					KEY_RAW_DEDUCTION_ID	=>  $ddctn['deduction_id'],					
					KEY_EFFECTIVE_DATE 		=> '',
					KEY_AMOUNT				=> $employee_amount,
					KEY_EMPLOYER_AMOUNT		=> $employer_amount,
					KEY_REFERENCE_TEXT		=> $ddctn['deduction_references'],
					KEY_PRIORITY_NUM		=> $ddctn['priority_num']
				);

				if ($with_ecf)
				{
					$deductions_dtl[$ddctn[KEY_PAYROLL_HDR_ID]][]	=	array(
						KEY_EMPLOYEE_ID 		=> $ddctn['employee_id'],
						KEY_PAYROLL_HDR_ID 		=> $ddctn['payroll_hdr_id'],
						KEY_DEDUCTION_ID 		=> ( ($ddctn['inherit_parent_id_flag'] == YES && ! empty($ddctn['parent_deduction_id']) ? 
														$ddctn['parent_deduction_id'] :  $sys_param_loc[PARAM_DEDUCTION_ST_GSIS_ECF_ID]) ),
						KEY_RAW_DEDUCTION_ID	=> $sys_param_loc[PARAM_DEDUCTION_ST_GSIS_ECF_ID],					
						KEY_EFFECTIVE_DATE 		=> '',
						KEY_AMOUNT				=> $deduct_amounts[KEY_PERSONAL_ECF],
						KEY_EMPLOYER_AMOUNT		=> $deduct_amounts[KEY_GOVERNMENT_ECF],
						KEY_REFERENCE_TEXT		=> $ddctn['deduction_references'],
						KEY_PRIORITY_NUM		=> $ddctn['priority_num']
					);
				}
			}

			return $deductions_dtl;
		}
		catch(PDOException $e)
		{
			RLog::error($e->getMessage());
			throw $e;
		}
		catch(Exception $e)
		{
			RLog::error($e->getMessage());
			throw $e;
		}
	}
	
	/**
	 * This helper function handles the computation of deduction amount based on its frequency.
	 */
	private function _compute_deduction_share_frequency($ddctn, $share_amounts, $payout_month, $sys_param_loc)
	{
		try
		{
			$employee_amount	= (isset($share_amounts[KEY_PERSONAL_SHARE]) ? $share_amounts[KEY_PERSONAL_SHARE] : 0);
			$employer_amount	= (isset($share_amounts[KEY_GOVERNMENT_SHARE]) ? $share_amounts[KEY_GOVERNMENT_SHARE] : 0);
			$employee_ecf_amount	= (isset($share_amounts[KEY_PERSONAL_ECF]) ? $share_amounts[KEY_PERSONAL_ECF] : 0);
			$employer_ecf_amount	= (isset($share_amounts[KEY_GOVERNMENT_ECF]) ? $share_amounts[KEY_GOVERNMENT_ECF] : 0);
			
			if ($ddctn['employer_share_flag'] != YES)
			{
				$employee_amount	= $employee_amount + $employer_amount;
				$employer_amount	= 0;
				
				$employee_ecf_amount	= $employee_ecf_amount + $employer_ecf_amount;
				$employer_ecf_amount	= 0;
			}			
			
			$frequency_id 		= intval($ddctn['frequency_id']); 
			if ($frequency_id <= FREQUENCY_MONTHLY)
			{
				$share_amounts 		= array(KEY_PERSONAL_SHARE => $employee_amount, KEY_GOVERNMENT_SHARE => $employer_amount);
				if ($employee_ecf_amount > 0 OR $employer_ecf_amount > 0)
				{
					$share_amounts[KEY_PERSONAL_ECF]	= $employee_ecf_amount;
					$share_amounts[KEY_GOVERNMENT_ECF]	= $employer_ecf_amount;
				}
				
				return $share_amounts;
			}
			
			$payout_month 		= intval($payout_month);
			
			$pay_month_num		= 0;
			$multiplier			= 1;
			
			switch($ddctn['frequency_id'])
			{
				case FREQUENCY_QUARTERLY: //quarterly
					$pay_month_num	= get_month_num_in_quarter($payout_month);
					$multiplier		= 3;
					break;
					
				case FREQUENCY_ANNUAL: //annual
					$pay_month_num	= $payout_month;
					$multiplier		= 12;
					break;
					
				case FREQUENCY_SEMI_ANNUAL: //semi-annual
					$pay_month_num	= get_month_num_in_half_year($payout_month);
					$multiplier		= 6;
					break;
					
				default: //others
					break;					
			}
			
			if ($ddctn['month_pay_num'] == $pay_month_num)
			{
				$employee_amount	= $employee_amount * $multiplier;
				$employer_amount	= $employer_amount * $multiplier;
				
				$employee_ecf_amount	= $employee_ecf_amount * $multiplier;
				$employer_ecf_amount	= $employer_ecf_amount * $multiplier;
			}
			else
			{
				$employee_amount	= 0;
				$employer_amount	= 0;

				$employee_ecf_amount	= 0;
				$employer_ecf_amount	= 0;
			}
			
			$share_amounts 		= array(KEY_PERSONAL_SHARE => $employee_amount, KEY_GOVERNMENT_SHARE => $employer_amount);
			if ($employee_ecf_amount > 0)
				$share_amounts[KEY_PERSONAL_ECF]	= $employee_ecf_amount;
			if ($employer_ecf_amount > 0)
				$share_amounts[KEY_GOVERNMENT_ECF]	= $employer_ecf_amount;
			
			//RLog::error("E: _compute_deduction_frequency [{$ddctn['month_pay_num']}] [$pay_month_num] [$multiplier] [{$ddctn['employer_share_flag']}]");
			//RLog::error($share_amounts);			
			
			return $share_amounts;
		}
		catch(Exception $e)
		{
			RLog::error($e->getMessage());
			throw $e;
		}
	}
	
	/**
	 * This helper function handles the computation for deduction with variable amount.
	 * @param array $deduction Contains employee deduction record
	 * @param covered_date Date covered
	 * @return variable amount
	 */
	private function _compute_variable_deduction_type($deduction, $covered_date=NULL)
	{
		RLog::info('START GP: _compute_variable_deduction_type ['.$covered_date.']');
		
		$amount 		= 0.00;
		
		try
		{
			$employee_id 		= $deduction['employee_id'];
			
			$deduction_type_id	= $deduction['deduction_id'];
			$multiplier_id      = $deduction['multiplier_id'];
			$multiplier_rate    = $deduction['rate'];
			$multiplier_rate    = (($multiplier_rate == NULL ? 0.00 : $multiplier_rate) / ONE_HUNDRED);
			
			// get amount
			$amount = 0.00;
			switch ($multiplier_id) {
				case MULTIPLIER_BASIC_SALARY:
					$amount = $deduction['employ_monthly_salary'];
					break;
				/*
				 * SGT: commented 2016-11-14
				case MULTIPLIER_TAXABLE_INCOME:
					$p_employee          = array($employee_id);
					$proj_taxable_income = $this->payroll_tax->compute_taxable_income($p_employee, $covered_date);
					$amount              = $proj_taxable_income[$employee_id];
					$amount              = ROUND( ($amount/12), 2);
					break;
				*/
			}
			
			$amount 			= round(($amount * $multiplier_rate), 2);
		}
		catch (Exception $e)
		{
			$message = $e->getMessage();
			RLog::error($message);
			
			throw $e;
		}
		
		RLog::info('END GP: _compute_variable_deduction_type');
		
		return $amount;
	}	
	
	
	private function _compute_scheduled_deduction($deduction, $covered_date=NULL)
	{
		RLog::info('START GP: _compute_scheduled_deduction ['.$covered_date.']');
		
		$amount = 0.00;
		
		try
		{					
			$paid_bkdown_counts 	= explode(',', $deduction['paid_bkdown_count']); //jendaigo: include paid_bkdown_count from table employee_deduction_detail_details
			$payment_bkdown_counts 	= explode(',', $deduction['payment_bkdown_count']);
			$payment_bkdown_amounts = explode(',', $deduction['payment_bkdown_amount']);
			//$payment_bkdown_amounts = ( isset($deduction['payment_bkdown_amount'];) ? explode(',', $deduction['payment_bkdown_amount'];) : NULL);

			$payment_bkdown_count = 0;
			for ($j=0; $j<count($payment_bkdown_counts); $j++)
			{
				if (isset($payment_bkdown_counts[$j]))
				{
					$payment_bkdown_count += $payment_bkdown_counts[$j];
					// if ($deduction['paid_count'] < $payment_bkdown_count)
					// {
						// $amount = $payment_bkdown_amounts[$j];
						// break;
					// }
					// ====================== jendaigo : start : include condition for overpay, pagibig and mp2 ============= //
					if($deduction['deduction_id'] == DEDUC_OVERPAY_JO
						OR $deduction['deduction_id'] == DEDUC_HMDF1_JO
						OR $deduction['deduction_id'] == DEDUC_HMDF2_JO)
					{
						if ($paid_bkdown_counts[$j] < $payment_bkdown_counts[$j])
							$amount += $payment_bkdown_amounts[$j];
					}
					else
					{
						if ($deduction['paid_count'] < $payment_bkdown_count)
						{
							$amount = $payment_bkdown_amounts[$j];
							break;
						}
					}
					// ====================== jendaigo : start : include condition for overpay, pagibig and mp2 ============= //
				}
			}

		}
		catch (Exception $e)
		{
			$message = $e->getMessage();
			RLog::error($message);
			
			throw $e;
		}
		
		RLog::info('END GP: _compute_scheduled_deduction ['.$amount.']');
		
		return $amount;		
	}
	
	
	public function clear_payroll_data($id, $selected_employees=NULL)
	{
		try
		{
			$hash_sql_id 	= $this->get_hash_key('payroll_summary_id'); 
			
			// get payroll hdr id
			$fields 		= array('GROUP_CONCAT(payroll_hdr_id SEPARATOR \',\') payroll_hdr_ids');
			$table 			= $this->common->tbl_payout_header;
			$where			= array($hash_sql_id => $id);
			if ( ! empty($selected_employees))
			{
				if (is_array($selected_employees))
					$where['employee_id']	= array($selected_employees, array('IN'));
				else
					$where['employee_id']	= $selected_employees;
			}			
			$payroll_hdrs 		= $this->common->get_general_data_group_concat($fields, $table, $where, FALSE);


			if (isset($payroll_hdrs))
			{
				$payroll_hdrs 	= explode(',', $payroll_hdrs['payroll_hdr_ids']);

				// payout details
				$table 			= $this->common->tbl_payout_details;
				$where 			= array('payroll_hdr_id' => array($payroll_hdrs, array('IN')));
				$this->common->delete_general_data($table, $where);
				
				// payout header
				$table 			= $this->common->tbl_payout_header;
				$where			= array($hash_sql_id => $id);
				if ( ! empty($selected_employees))
					$where['payroll_hdr_id'] = array($payroll_hdrs, array('IN'));

				$this->common->delete_general_data($table, $where);
			}

			if (empty($selected_employees))
			{
				// payout summary_dates
				$table 			= $this->common->tbl_payout_summary_dates;
				$where			= array($this->get_hash_key('payout_summary_id') => $id);
				$this->common->delete_general_data($table, $where);
			}
		}
		catch (PDOException $e)
		{
			RLog::error($e->getMessage());
			throw $e;
		}
	}
	
	private function _split_deductions($deductions_per_hdr, $monthly_taxes, $param_data, $header_total_income, $minimum_monthly_amount=0)
	{
		try
		{
			// START: Get deduction distribution
			$table 					= $this->payroll_process->tbl_param_payroll_deductions;
			$fields					= array('deduction_id', 'GROUP_CONCAT(payout_date_num separator \',\') payout_date_num');
			$where 					= array('payroll_type_id' => $param_data['payroll_type']);
			$order_by 				= array('deduction_id' => 'ASC');
			$group_by 				= array('deduction_id');
			$deduction_payout_nums 	= $this->common->get_general_data($fields, $table, $where, TRUE, $order_by, $group_by);
			$deduction_payout_nums 	= set_key_value($deduction_payout_nums, 'deduction_id', 'payout_date_num');
			
			if (count($deduction_payout_nums) == 0)
				throw new Exception('PAYOUT NUM for all Deductions: ' . $this->lang->line('sys_param_not_defined'));			
			
			$table 						= $this->payroll_process->tbl_param_deductions;
			$fields						= array('deduction_id', 'priority_num');
			$order_by 					= array('priority_num' => 'DESC');
			$deduction_priority_nums 	= $this->common->get_general_data($fields, $table, NULL, TRUE, $order_by);
			$deduction_priority_nums 	= set_key_value($deduction_priority_nums, 'deduction_id', 'priority_num');
			// END: Get deduction distribution

			// GET payout dates
			$payout_dates	 			= array();
			$pd 						= 1;
			while ($pd <= $param_data['payout_count'])
			{
				$payout_date_key 	= 'payout_date_' . $pd;
				if (isset($param_data[$payout_date_key]))
					$payout_dates[] = $param_data[$payout_date_key];
				else
					break;
				
				$pd++;
			}

			$deductions_dtl 		= array();

			$cnt 					= 0;
			
			$header_total_deduction = array();

			foreach ($deductions_per_hdr as $hdr_id => $deductions)
			{
				// Merge tax and other deductions
				if (isset($monthly_taxes[$hdr_id]))
				{
					$monthly_taxes[$hdr_id][0]['priority_num'] 	= $deduction_priority_nums[$monthly_taxes[$hdr_id][0]['deduction_id']];
					$deductions 								= array_merge($deductions, $monthly_taxes[$hdr_id]);
					
					usort($deductions, build_sorter('priority_num'));
				}

				// Sort deductions (from priority to least priority)
				$skip_deduction_hdr = array();
				foreach ($deductions as $ddctn)
				{
					$payout_nums = isset($deduction_payout_nums[$ddctn['deduction_id']]) ? $deduction_payout_nums[$ddctn['deduction_id']] : NULL;
					if ( ! isset ($payout_nums))
						throw new Exception('PAYOUT NUM for Deduction: ['.$ddctn['deduction_id'].']' . $this->lang->line('sys_param_not_defined'));
	
					$payout_nums 		= explode(',', $payout_nums);
					$payout_num_count 	= count($payout_nums);
					
					// Splitting of amount
					$total_amount_ps 					= 0;
					$total_amount_gs 					= 0;
					
					$employee_amount 					= $ddctn['amount'];
					$employer_amount 					= (isset($ddctn['employer_amount']) ? $ddctn['employer_amount'] : 0);
					
					$payroll_hdr_id 					= $ddctn['payroll_hdr_id'];
					
					$amounts 							= array();
					$amounts[0][KEY_PERSONAL_SHARE] 	= 0;
					$amounts[0][KEY_GOVERNMENT_SHARE] 	= 0;						
					
					if ($payout_num_count > 1)
					{
						$div 			= $payout_num_count - 1;
						$tmp_amount_ps 	= round(($employee_amount/$payout_num_count), 2);
						$tmp_amount_gs 	= round(($employer_amount/$payout_num_count), 2);
						
						for($i=0; $i<$div; $i++)
						{
							$amounts[$i][KEY_PERSONAL_SHARE] 	= $tmp_amount_ps;
							$amounts[$i][KEY_GOVERNMENT_SHARE] 	= $tmp_amount_gs;
							
							$total_amount_ps += $tmp_amount_ps;
							$total_amount_gs += $tmp_amount_gs;
						}
		
						$amounts[$div][KEY_PERSONAL_SHARE] 		= $employee_amount - $total_amount_ps;
						$amounts[$div][KEY_GOVERNMENT_SHARE] 	= $employer_amount - $total_amount_gs;
					}
					else
					{
						$amounts[0][KEY_PERSONAL_SHARE] 		= $employee_amount;
						$amounts[0][KEY_GOVERNMENT_SHARE] 		= $employer_amount;							
					}
		
					for($i=0; $i<$payout_num_count; $i++)
					{
						$payout_num 			= $payout_nums[$i] - 1;
						
						$deductions_dtl[$cnt]	=	array(
							KEY_PAYROLL_HDR_ID 		=> $payroll_hdr_id,
							KEY_DEDUCTION_ID 		=> $ddctn['deduction_id'],
							KEY_RAW_DEDUCTION_ID	=> $ddctn['deduction_id'],
							KEY_EFFECTIVE_DATE 		=> $payout_dates[$payout_num],
							KEY_AMOUNT				=> $amounts[$i][KEY_PERSONAL_SHARE],
							KEY_EMPLOYER_AMOUNT		=> $amounts[$i][KEY_GOVERNMENT_SHARE],
							KEY_REFERENCE_TEXT		=> $ddctn['reference_text'],
							KEY_REFERENCE_DEDUCTIONS=> $remarks_deduction,
							KEY_INCLUDE_FLAG		=> YES
						);
						$cnt++;
					}
						
					$cnt++;
				}
				
			}
			
			return $deductions_dtl;
			
		}
		catch (Exception $e)
		{
			RLog::error($e->getMessage());
			throw $e;
		}
	}	
	
	private function _check_split_deductions($deductions_per_hdr, $monthly_taxes, $monthly_payroll_count, $param_data, $header_total_income, $minimum_monthly_amount=0, $payout_for_the_month=array())
	{
		try
		{	
			// START: Get deduction distribution
			$table 						= $this->payroll_process->tbl_param_payroll_deductions;
			$fields						= array('deduction_id', 'GROUP_CONCAT(payout_date_num separator \',\') payout_date_num');
			$where 						= array('payroll_type_id' => $param_data['payroll_type']);
			$order_by 					= array('deduction_id' => 'ASC');
			$group_by 					= array('deduction_id');
			$deduction_payout_nums 		= $this->common->get_general_data($fields, $table, $where, TRUE, $order_by, $group_by);
			$deduction_payout_nums 		= set_key_value($deduction_payout_nums, 'deduction_id', 'payout_date_num');
			if (count($deduction_payout_nums) == 0)
				throw new Exception('PAYOUT NUM for all Deductions: ' . $this->lang->line('sys_param_not_defined'));			
			
			$table 						= $this->payroll_process->tbl_param_deductions;
			$fields						= array('deduction_id', 'priority_num');
			$order_by 					= array('priority_num' => 'DESC');
			$deduction_priority_nums 	= $this->common->get_general_data($fields, $table, NULL, TRUE, $order_by);
			$deduction_priority_nums 	= set_key_value($deduction_priority_nums, 'deduction_id', 'priority_num');
			// END: Get deduction distribution

			// GET payout dates
			$payout_dates 				= array();
			$pd 						= 1;
			while ($pd <= $param_data['payout_count'])
			{
				$payout_date_key 	= 'payout_date_' . $pd;
				if (isset($param_data[$payout_date_key]))
					$payout_dates[] = $param_data[$payout_date_key];
				else
					break;
				
				$pd++;
			}

			$deductions_dtl 			= array();

			$cnt 						= 0;
			$header_total_deduction 	= array();

			foreach ($deductions_per_hdr as $hdr_id => $deductions)
			{
				// Merge tax and other deductions
				if (isset($monthly_taxes[$hdr_id]))
				{
					$monthly_taxes[$hdr_id][0]['priority_num'] 	= $deduction_priority_nums[$monthly_taxes[$hdr_id][0]['deduction_id']];
					$deductions 								= array_merge($deductions, $monthly_taxes[$hdr_id]);

					usort($deductions, build_sorter('priority_num'));
				}

				// Sort deductions (from priority to least priority)
				$skip_deduction_hdr 	= array();

				foreach ($deductions as $ddctn)
				{

					$payout_nums = isset($deduction_payout_nums[$ddctn['deduction_id']]) ? $deduction_payout_nums[$ddctn['deduction_id']] : NULL;
		
					if ( ! isset ($payout_nums))
						throw new Exception('PAYOUT NUM for Deduction: ['.$ddctn['deduction_id'].']' . $this->lang->line('sys_param_not_defined'));
	
					$payout_nums 		= explode(',', $payout_nums);
					$payout_num_count 	= count($payout_nums);
					
					// Splitting of amount
					$total_amount_ps 	= 0;
					$total_amount_gs 	= 0;
					
					$employee_amount 	= $ddctn['amount'];
					$employer_amount 	= (isset($ddctn['employer_amount']) ? $ddctn['employer_amount'] : 0);
					
					//RLog::error("Checking JO [$monthly_payroll_count] [{$ddctn['deduction_id']}] [$payout_num_count]");
					//RLog::error($payout_for_the_month);

				if ($monthly_payroll_count > 1)
					{
						if ($ddctn['deduction_id'] == DEDUC_BIR_EWT OR $ddctn['deduction_id'] == DEDUC_BIR_VAT)
						{
							// if bir, should not divide
						}
						else if ($ddctn['deduction_id'] == 3)
						{
							// if pag-ibig, one payout only
							//RLog::error("PAYOUT DAY FORMAT: [$payout_dates[0]]");
							$dte_day_obj = new DateTime($payout_dates[0]);
							$fmt_date_day = $dte_day_obj->format('d');
							$fmt_date_day = (int) $fmt_date_day;
							//RLog::error("PAYOUT DAY: [$fmt_date_day]");

							if ($fmt_date_day <= 15)
							{
								// $employee_amount = 0;
								// $employer_amount = 0;
								continue; //jendaigo: do not include in saving payout details
							}
							
						}
						// ====================== jendaigo : start   : additional condition ============= //
						else if ($ddctn['deduction_id'] == DEDUC_HMDF2_JO)
						{
							$dte_day_obj = new DateTime($payout_dates[0]);
							$fmt_date_day = $dte_day_obj->format('d');
							$fmt_date_day = (int) $fmt_date_day;

							if ($fmt_date_day > 15)
							{
								// $employee_amount = 0;
								// $employer_amount = 0;
								continue;  //jendaigo: do not include in saving payout details
							}
							
						}
						else if ($ddctn['deduction_id'] == DEDUC_OVERPAY_JO)
						{
							// if overpay, should not divide
						}
						// ====================== jendaigo : end   : additional condition ============= //
						else
						{
							$payout_key					= $ddctn['employee_id'] . '-' . $ddctn['deduction_id'];
							$payout_month_data 			= $payout_for_the_month[$payout_key];
							if ( ! empty($payout_for_the_month))
							{
								$actual_payroll_count	= $payout_month_data['payroll_count'];
								$paid_orig_amount		= $payout_month_data['orig_amount'];
								$paid_employer_amount	= $payout_month_data['employer_amount'];
							}
							else
							{
								$actual_payroll_count 	= isset($actual_payroll_count) ? $actual_payroll_count : 0;	
								$paid_orig_amount 		= 0;
								$paid_employer_amount	= 0;
							}
							
							$with_pay = FALSE;
							foreach ($payout_nums as $pay_num)
							{
								//if ($ddctn['deduction_id'] == 8) RLog::error("Checking JO payout [$pay_num] [$actual_payroll_count]");
								if ($pay_num == ($actual_payroll_count+1))
								{
									if ($payout_num_count > 1)
									{
										// if last payroll for the month, deduct total from full amount
										// else use full amount / $monthly_payroll_count
										RLog::info("check monthly payroll counts [$payout_key] [$monthly_payroll_count] [$actual_payroll_count] [$paid_orig_amount] [$paid_employer_amount]");
										if ($actual_payroll_count == ($monthly_payroll_count-1))
										{
											$employee_amount	= $employee_amount - $paid_orig_amount;
											$employer_amount	= $employer_amount - $paid_employer_amount;
										}
										else
										{
											RLog::info("TEST DIV [$payout_key] [$payout_num_count] [$employee_amount / $monthly_payroll_count]");
											$employee_amount	= round(($employee_amount / $monthly_payroll_count), 2);
											$employer_amount	= round(($employer_amount / $monthly_payroll_count), 2);											
										}
									}
									
									$with_pay = TRUE;
									
									break;
								}
							}
							
							if (!$with_pay)
							{
								$employee_amount	= 0;
								$employer_amount	= 0;
							}
						}

						// reset $payout_num_count to 1
						$payout_num_count	= 1;
						$payout_nums 		= array(1);
					}
					
					
					$payroll_hdr_id 	= $ddctn['payroll_hdr_id'];
					
					$header_total_deduction[$payroll_hdr_id] = (isset($header_total_deduction[$payroll_hdr_id])) 
																		? $header_total_deduction[$payroll_hdr_id] : 0;
	
					$temp_net_pay 		= $header_total_income[$payroll_hdr_id]['total_income'] 
									- ($header_total_deduction[$payroll_hdr_id] + $employee_amount);
									
					//RLog::info('check min amount: ' . $payroll_hdr_id . ' ['.$header_total_income[$payroll_hdr_id]['total_income'].'] ['.$temp_net_pay.'] ['.$employee_amount.']');
									
					$remarks_deduction 	= NULL;
					
					// Validate minimum monthly take home pay
					if ( $employee_amount != 0 AND 
							($temp_net_pay < $minimum_monthly_amount OR in_array($payroll_hdr_id, $skip_deduction_hdr)) )
					{
						//RLog::info('Minimum monthly take home pay is already reached. ['.$ddctn['deduction_id'].']');
						//$remarks_deduction = 'The amount of Php' . number_format($employee_amount, 2) . ' is not deducted.';
						$remarks_deduction 		= $this->lang->line('payroll_deduction_not_included');
						$remarks_deduction 		= sprintf($remarks_deduction, number_format($employee_amount, 2));
						
						$skip_deduction_hdr[] 	= $payroll_hdr_id;
						
						$deductions_dtl[$cnt]	=	array(
							'payroll_hdr_id' 	=> $payroll_hdr_id,
							'deduction_id' 		=> $ddctn['deduction_id'],
							'raw_deduction_id'	=> $ddctn['deduction_id'],
							'effective_date' 	=> $payout_dates[0],
							'amount'			=> 0,
							'orig_amount'		=> $employee_amount,
							'employer_amount'	=> 0,
							'reference_text'	=> $ddctn['reference_text'],
							'remarks_deduction'	=> $remarks_deduction,
							'include_flag'		=> NO
						);
						$cnt++;						
					}
					else 
					{
						$header_total_deduction[$payroll_hdr_id] += $employee_amount;
						
						$amounts = array();
						$amounts[0][KEY_PERSONAL_SHARE] 	= 0;
						$amounts[0][KEY_GOVERNMENT_SHARE] 	= 0;						
						
						if ($payout_num_count > 1)
						{
							$div 			= $payout_num_count - 1;
							$tmp_amount_ps 	= round(($employee_amount/$payout_num_count), 2);
							$tmp_amount_gs 	= round(($employer_amount/$payout_num_count), 2);
							
							for($i=0; $i<$div; $i++)
							{
								$amounts[$i][KEY_PERSONAL_SHARE] 	= $tmp_amount_ps;
								$amounts[$i][KEY_GOVERNMENT_SHARE] 	= $tmp_amount_gs;
								
								$total_amount_ps += $tmp_amount_ps;
								$total_amount_gs += $tmp_amount_gs;
							}
			
							$amounts[$div][KEY_PERSONAL_SHARE] 		= $employee_amount - $total_amount_ps;
							$amounts[$div][KEY_GOVERNMENT_SHARE] 	= $employer_amount - $total_amount_gs;
						}
						else
						{
							$amounts[0][KEY_PERSONAL_SHARE] 		= $employee_amount;
							$amounts[0][KEY_GOVERNMENT_SHARE] 		= $employer_amount;							
						}

						for($i=0; $i<$payout_num_count; $i++)
						{
							$payout_num 			= $payout_nums[$i] - 1;
							$deductions_dtl[$cnt]	=	array(
								KEY_PAYROLL_HDR_ID 		=> $payroll_hdr_id,
								KEY_DEDUCTION_ID 		=> $ddctn['deduction_id'],
								KEY_RAW_DEDUCTION_ID	=> $ddctn['raw_deduction_id'],
								KEY_EFFECTIVE_DATE 		=> $payout_dates[$payout_num],
								KEY_AMOUNT				=> $amounts[$i][KEY_PERSONAL_SHARE],
								KEY_ORIG_AMOUNT			=> $amounts[$i][KEY_PERSONAL_SHARE],
								KEY_EMPLOYER_AMOUNT		=> $amounts[$i][KEY_GOVERNMENT_SHARE],
								KEY_REFERENCE_TEXT		=> $ddctn['reference_text'],
								KEY_REFERENCE_DEDUCTIONS=> $remarks_deduction,
								KEY_INCLUDE_FLAG		=> YES
							);
							$cnt++;
						}						
					}
						
					$cnt++;
				}
				
			}

			return $deductions_dtl;
			
		}
		catch (Exception $e)
		{
			RLog::error($e->getMessage());
			throw $e;
		}
	}
	
}

/* End of file Payroll_process.php */
/* Location: ./application/modules/main/controllers/Payroll_process.php */