<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Payroll_common extends Main_Controller {
	
	private $permission_module = MODULE_PAYROLL_GENERAL_PAYROLL;
	private $form2316;
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('common_model');
		$this->load->model('payroll_process_model');
		
		$this->permission_module = MODULE_PAYROLL_GENERAL_PAYROLL;
	}
	
	
	/*
	 * @param $compensation			array with keys 'employee_id' (not null), 'compensation_id' (not null), 'compensation_code' (not null), 'tenure_rqmt_flag', 'tenure_rqmt_val'
	 * @param $payout_summary_id	Payout Summary ID
	 * @param $covered_date_from 	If General Payroll, Attendance Period Date From; If Special Payroll, Covered Period From
	 * @param $covered_date_to 		If General Payroll, Attendance Period Date To; If Special Payroll, Covered Period To
	 * @param $attendance_period_id	If General Payroll, Attendance Period Header ID; If Special Payroll, NULL
	 * @param $sys_params			Compensation code for system-generated values
	 * @param $salary_frequency_flag
	 */
	// SGT 20161207: removed 13th month pay (this is YEB for govt)
	public function get_system_generated_amount($compensation, $payout_summary_id=NULL, $covered_date_from=NULL, $covered_date_to=NULL, 
			$attendance_period_id=NULL, $sys_params=array(), $attendance_count=array(), $payroll_type_vars=array(), 
			$salary_schedules=array(), $gsis_table=array(), $prorated_rates=array())
	{
		RLog::info('Payroll_common.get_system_generated_amount');
		try
		{
			// throw exception if at least one of the variables is not defined
			if( empty($sys_params[PARAM_COMPENSATION_BASIC_SALARY]) OR empty($sys_params[PARAM_COMPENSATION_BASIC_SALARY_DEDUCTION]) 
					OR empty($sys_params[PARAM_COMPENSATION_ID_BASIC_SALARY_ADJUSTMENT]) OR empty($sys_params[PARAM_COMPENSATION_ID_BASIC_SALARY])
					OR empty($sys_params[PARAM_COMPENSATION_ID_BASIC_SALARY_DEDUCTIONS])
					OR empty($sys_params[PARAM_COMPENSATION_LONGEVITY_PAY])
					OR empty($sys_params[PARAM_COMPENSATION_LOYALTY]) OR empty($sys_params[PARAM_WORKING_MONTHS])
					OR empty($sys_params[PARAM_WORKING_DAYS])
					OR empty($sys_params[PARAM_COMPENSATION_CODE_CNA]) )
				throw new Exception('System-generate Amount: ' . $this->lang->line('sys_param_not_defined'));
			
			$amount = array();
			$employee_id 		= $compensation['employee_id'];
			$compensation_id 	= $compensation['compensation_id'];
			$compensation_code	= $compensation['compensation_code'];
			$anniv_emp_date		= $compensation['anniv_emp_date'];
			$payroll_hdr_id		= $compensation['payroll_hdr_id'];
			
			switch ($compensation_code) 
			{
				case $sys_params[PARAM_COMPENSATION_BASIC_SALARY]:
					if(empty($sys_params[PARAM_WORKING_HOURS]))
						throw new Exception('BASIC SALARY WORK HOURS: ' . $this->lang->line('sys_param_not_defined'));
						
					$salary_frequency_flag	= isset($payroll_type_vars[KEY_SALARY_FREQ_FLAG]) ? $payroll_type_vars[KEY_SALARY_FREQ_FLAG] : NULL;
					$payroll_type_id		= isset($payroll_type_vars[KEY_PAYROLL_TYPE_ID]) ? $payroll_type_vars[KEY_PAYROLL_TYPE_ID] : 0;
					
					if ( empty($salary_frequency_flag))
						throw new Exception('Salary Frequency Flag: ' . $this->lang->line('param_not_defined'));						
					
					//GET EMPLOYEE BASIC SALARY
					if ($salary_frequency_flag == SALARY_DAILY)
					{
						$amount = $this->_get_basic_salary_daily($employee_id, $covered_date_from, $covered_date_to, $salary_frequency_flag, 
									$compensation_id, $sys_params[PARAM_COMPENSATION_ID_BASIC_SALARY_ADJUSTMENT], 
									$attendance_count, $payroll_hdr_id, $payroll_type_id);						
					}
					else
					{
						$amount = $this->_get_basic_salary_monthly($employee_id, $covered_date_from, $covered_date_to, $salary_frequency_flag, 
									$compensation_id, $sys_params[PARAM_COMPENSATION_ID_BASIC_SALARY_ADJUSTMENT], $attendance_count, 
									$payroll_hdr_id, $payroll_type_id);
					}
					RLog::info('S: BASIC SALARY %%% ' . $employee_id);
					RLog::info($amount);
					RLog::info('E: BASIC SALARY %%%');
				break;
				
				case $sys_params[PARAM_COMPENSATION_LONGEVITY_PAY]:
					//Longevity Pay
					
					if(empty($sys_params[PARAM_COMPENSATION_LONGEVITY_PAY_RATE]) OR empty($sys_params[PARAM_COMPENSATION_LONGEVITY_MIN_TENURE]) )
						throw new Exception('LONGEVITY PAY: ' . $this->lang->line('sys_param_not_defined'));
					
					// $covered_date_to = payroll period end date 
					$employee_comp = $this->compute_longevity_pay($employee_id, $anniv_emp_date, $covered_date_to, $sys_params);
					if (isset($employee_comp))
					{
						RLog::info('SYS_PARAM_LONGEVITY: ['.$employee_id.'] [' . ( ! empty($employee_comp[$employee_id]) ? $employee_comp[$employee_id] : 0 ) . ']');
						$amount[$compensation_id][KEY_AMOUNT] = empty($employee_comp[$employee_id]) ? 0.00 : $employee_comp[$employee_id];
					}
				break;
					
				case $sys_params[PARAM_COMPENSATION_LOYALTY]:
					//Loyalty Cash Award
					if( empty($sys_params[PARAM_LCA_AUTH_LWOP_FIRST]) OR empty($sys_params[PARAM_LCA_AUTH_LWOP_NEXT])
							OR empty($sys_params[PARAM_LCA_MILESTONE_YEAR_FIRST]) OR empty($sys_params[PARAM_LCA_MILESTONE_YEAR_NEXT])
							OR empty($sys_params[PARAM_LCA_MILESTONE_FIRST_AMOUNT]) OR empty($sys_params[PARAM_LCA_MILESTONE_NEXT_AMOUNT])
							OR empty($sys_params[PARAM_LCA_EFFECTIVE_START_DATE]) )
						throw new Exception('System-generate Amount [LCA]: ' . $this->lang->line('sys_param_not_defined'));
					
					$employee_comp = $this->compute_loyalty_cash_award($employee_id, $anniv_emp_date, $payout_summary_id, $covered_date_to, $sys_params, $attendance_period_id);
					if (isset($employee_comp))
					{
						RLog::info('SYS_PARAM_LOYALTY: [' . $employee_comp[$employee_id] . ']');
						$amount[$compensation_id][KEY_AMOUNT] 		= empty($employee_comp[$employee_id]) ? 0 : $employee_comp[$employee_id];
						$amount[$compensation_id][KEY_ORIG_AMOUNT]	= $amount[$compensation_id][KEY_AMOUNT];
					}
				break;
				
				case $sys_params[PARAM_COMPENSATION_CODE_CNA]:
					RLog::info('S: CNA');
					// Collective Negotiation Agreement
					$employee_comp = $this->compute_cna($employee_id, $covered_date_from, $covered_date_to, $sys_params);
					if (isset($employee_comp))
					{
						//RLog::error('SYS_PARAM_CNA');
						//RLog::error($employee_comp[$employee_id]);
						$amount[$compensation_id][KEY_ORIG_AMOUNT]	= (isset($employee_comp[$employee_id][KEY_ORIG_AMOUNT]) 
																			? $employee_comp[$employee_id][KEY_ORIG_AMOUNT]  
																			: $amount[$compensation_id][KEY_AMOUNT] );
						$amount[$compensation_id][KEY_AMOUNT]		= empty($employee_comp[$employee_id][KEY_AMOUNT]) ? 0 : $employee_comp[$employee_id][KEY_AMOUNT];
						$amount[$compensation_id][KEY_LESS_AMOUNT]	= $amount[$compensation_id][KEY_ORIG_AMOUNT] - $amount[$compensation_id][KEY_AMOUNT];
					}
					RLog::info('E: CNA');
				break;
				
				
				// DIFFERENTIAL
				case $sys_params[PARAM_COMPENSATION_CODE_SALDIFFL]:
					RLog::info('S: SALARY DIFFERENTIAL (Basic Salary and GSIS) ['.$compensation_id.']');

					$salary_grade 	= $compensation['salary_grade'];
					$pay_step 		= $compensation['pay_step'];
					$employee_comp	= $this->compute_differential_salary($employee_id, $salary_grade, $pay_step, $covered_date_from, $covered_date_to, $sys_params, $salary_schedules, $gsis_table);
					
					if (isset($employee_comp[$employee_id]))
					{
						RLog::info('SYS_PARAM_DIFF_SAL: [' . $employee_comp[$employee_id][$compensation_id] . ']');
						$amount = empty($employee_comp[$employee_id]) ? 0 : $employee_comp[$employee_id];
					}
					RLog::info('E: SALARY DIFFERENTIAL (Basic Salary and GSIS)');
				break;

				case $sys_params[PARAM_COMPENSATION_CODE_HAZDIFFL]:
					RLog::info('S: HAZARD PAY DIFFERENTIAL');
					
					$salary_grade 	= $compensation['salary_grade'];
					$pay_step 		= $compensation['pay_step'];
					$employee_comp = $this->compute_differential_hazard($employee_id, $salary_grade, $pay_step, $covered_date_from, $covered_date_to, $sys_params,
						$salary_schedules, $compensation['pro_rated_flag'], $prorated_rates);
					if (isset($employee_comp))
					{
						RLog::info('SYS_PARAM_DIFF_HAZ: [' . $employee_comp[$employee_id] . ']');
						//$amount = empty($employee_comp[$employee_id]) ? 0 : $employee_comp[$employee_id];
						$amount[$compensation_id][KEY_AMOUNT] = empty($employee_comp[$employee_id][$compensation_id][KEY_AMOUNT]) ? 0 
							: $employee_comp[$employee_id][$compensation_id][KEY_AMOUNT];
						$amount[$compensation_id][KEY_ORIG_AMOUNT] = empty($employee_comp[$employee_id][$compensation_id][KEY_ORIG_AMOUNT]) ? 0  
								: $employee_comp[$employee_id][$compensation_id][KEY_ORIG_AMOUNT];
						$amount[$compensation_id][KEY_LESS_AMOUNT] = empty($employee_comp[$employee_id][$compensation_id][KEY_LESS_AMOUNT]) ? 0  
								: $employee_comp[$employee_id][$compensation_id][KEY_LESS_AMOUNT];
					}
					RLog::info('E: HAZARD PAY DIFFERENTIAL');
				break;
				
				case $sys_params[PARAM_COMPENSATION_CODE_LONGEDIFFL]:
					RLog::info('S: LONGEVITY PAY DIFFERENTIAL');
					
					$salary_grade 	= $compensation['salary_grade'];
					$pay_step 		= $compensation['pay_step'];
					$anniv_emp_date	= $compensation['anniv_emp_date'];
					$employee_comp 	= $this->compute_differential_longevity($employee_id, $salary_grade, $pay_step, $anniv_emp_date, $covered_date_from, $covered_date_to, $sys_params, 
						$salary_schedules);
					if (isset($employee_comp))
					{
						$amount[$compensation_id][KEY_AMOUNT] = empty($employee_comp[$employee_id][$compensation_id][KEY_AMOUNT]) ? 0  
								: $employee_comp[$employee_id][$compensation_id][KEY_AMOUNT];
						$amount[$compensation_id][KEY_ORIG_AMOUNT] = empty($employee_comp[$employee_id][$compensation_id][KEY_ORIG_AMOUNT]) ? 0  
								: $employee_comp[$employee_id][$compensation_id][KEY_ORIG_AMOUNT];
						$amount[$compensation_id][KEY_LESS_AMOUNT] = empty($employee_comp[$employee_id][$compensation_id][KEY_LESS_AMOUNT]) ? 0  
								: $employee_comp[$employee_id][$compensation_id][KEY_LESS_AMOUNT];
					}
					RLog::info('E: LONGEVITY PAY DIFFERENTIAL');
				break;
			}
			
			return 	$amount;
		}
		catch(PDOException $e)
		{
			throw $e;
		}
		catch(Exception $e)
		{
			throw $e;
		}

	}	

	private function _get_basic_salary_monthly($employee_id, $covered_date_from, $covered_date_to, $salary_frequency_flag, 
			$id_basic_salary, $id_basic_salary_adj, $attendance_count, $payroll_hdr_id, $payroll_type_id=0)
	{
		try
		{
			//TODO
			$working_days			= 22;
			$working_hours			= 8;
			
			$basic_monthly_amount	= 0.00;
			$basic_salary_amount	= 0.00;
			$basic_adjust_amount	= 0.00;
			
			$daily_rate				= 0.00;
			$daily_rate_bs			= 0.00;
			$daily_rate_bsa			= 0.00;
			
			$month_work_days		= 0;
			$worked_days			= 0;
			
			$where				= array();
			$key				= 'employee_id';
			$where[$key]		= $employee_id;
			$key2				= 'active_flag';
			$where[$key2]		= YES;
			
			$round_to			= 4;
			
			// basic salary
			$fields				= array('employ_salary_grade', 'employ_salary_step', 'IFNULL(employ_monthly_salary, 0) employ_monthly_salary');
			$emp_salary_rec		= $this->common_model->get_general_data($fields, $this->common_model->tbl_employee_work_experiences, $where, FALSE);
			$basic_adjust_amount= (isset($emp_salary_rec['employ_monthly_salary']) ? $emp_salary_rec['employ_monthly_salary'] : 0.00);
			
			
			$where				= array();
			$key				= 'salary_grade';
			$where[$key]		= (isset($emp_salary_rec['employ_salary_grade']) ? $emp_salary_rec['employ_salary_grade'] : 0);
			$key				= 'salary_step';
			$where[$key]		= (isset($emp_salary_rec['employ_salary_step']) ? $emp_salary_rec['employ_salary_step'] : 0);
			$key				= 'other_fund_flag';
			$where[$key]		= NO;
			$key				= 'active_flag';
			$where[$key]		= YES;
			
			$fields				= array('IFNULL(amount, 0) amount');
			$emp_salary_rec		= $this->common_model->get_general_data($fields, $this->common_model->tbl_param_salary_schedule, $where, FALSE);

			$basic_monthly_amount	= $basic_adjust_amount;
			if ( empty($emp_salary_rec))
			{
				$basic_salary_amount	= $basic_adjust_amount;
				$basic_adjust_amount	= 0.00;
			}
			else
			{
				$basic_salary_amount	= (isset($emp_salary_rec['amount']) ? $emp_salary_rec['amount'] : 0.00);
				$basic_adjust_amount 	= $basic_adjust_amount - $basic_salary_amount;				
			}
			
			$daily_rate_bs	= ROUND(($basic_salary_amount / $working_days), $round_to);
			$daily_rate_bsa	= ROUND(($basic_adjust_amount / $working_days), $round_to);
			$daily_rate		= ROUND(($basic_monthly_amount / $working_days), $round_to);

			// Using Basic Salary and Basic Salary Adjustment amounts, compute for deductions (attendance)
			$total_attendance_deduction = $this->_get_basic_salary_deduction_per_compensation($attendance_count, $employee_id, 
						$basic_monthly_amount, $basic_salary_amount, $basic_adjust_amount, 
						$daily_rate, $daily_rate_bs, $daily_rate_bsa, 
						$id_basic_salary, $id_basic_salary_adj, $working_hours, $salary_frequency_flag);
			
			$salary_sub_arr	= array(KEY_AMOUNT => 0.00, KEY_LESS_AMOUNT => 0.00, KEY_ORIG_AMOUNT => 0.00);
			$salary_amounts	= array($id_basic_salary => $salary_sub_arr, $id_basic_salary_adj => $salary_sub_arr);
			
			// basic salary
			$less_amount= $total_attendance_deduction[$id_basic_salary];
			$net_amount	= ROUND( ($basic_salary_amount - $less_amount), 4); 
			$salary_sub_arr	= array(KEY_AMOUNT => $net_amount, KEY_LESS_AMOUNT => $less_amount, KEY_ORIG_AMOUNT => $basic_salary_amount);
			$salary_amounts[$id_basic_salary]	= $salary_sub_arr;
			// basic salary adjustment
			$less_amount= $total_attendance_deduction[$id_basic_salary_adj];
			$net_amount	= ROUND( ($basic_adjust_amount - $less_amount), 4);
			$salary_sub_arr	= array(KEY_AMOUNT => $net_amount, KEY_LESS_AMOUNT => $less_amount, KEY_ORIG_AMOUNT => $basic_adjust_amount);
			$salary_amounts[$id_basic_salary_adj]	= $salary_sub_arr;
			
			$total_absent_count = intval(isset($total_attendance_deduction[KEY_ABSENT_DAYS]) ? $total_attendance_deduction[KEY_ABSENT_DAYS] : 0);
			
			return $salary_amounts;
		}
		catch(PDOException $e)
		{
			throw $e;
		}
		catch(Exception $e)
		{
			throw $e;
		}
	}
	
	
	private function _get_basic_salary_daily($employee_id, $covered_date_from, $covered_date_to, $salary_frequency_flag, 
			$id_basic_salary, $id_basic_salary_adj, $attendance_count, $payroll_hdr_id, $payroll_type_id=0)
	{
		try
		{
			//TODO
			$working_days			= 22;
			$working_hours			= 8;
			
			$basic_monthly_amount	= 0.00;
			
			$daily_rate				= 0.00;
			
			$month_work_days		= 0;
			$worked_days			= 0;
			
			$where				= array();
			$key				= 'employee_id';
			$where[$key]		= $employee_id;
			$key2				= 'active_flag';
			$where[$key2]		= YES;
			
			$round_to			= 4;
			
			// basic salary
			$fields				= array('employ_salary_grade', 'employ_salary_step', 'IFNULL(employ_monthly_salary, 0) employ_monthly_salary');
			$emp_salary_rec		= $this->common_model->get_general_data($fields, $this->common_model->tbl_employee_work_experiences, $where, FALSE);
			$basic_monthly_amount= (isset($emp_salary_rec['employ_monthly_salary']) ? $emp_salary_rec['employ_monthly_salary'] : 0.00);
			
			$month_work_days = $this->compute_working_days_in_month($covered_date_from, $covered_date_to, FALSE, FALSE);
			 
			$daily_rate		= ROUND(($basic_monthly_amount / $month_work_days), $round_to);
			
			// get working days in a period
			$worked_days 			= $this->compute_working_days($covered_date_from, $covered_date_to, FALSE, FALSE);
			$basic_salary_amount	= $worked_days * $daily_rate;

			// Using Basic Salary and Basic Salary Adjustment amounts, compute for deductions (attendance)
			$total_attendance_deduction = $this->_get_basic_salary_deduction_per_compensation($attendance_count, $employee_id, 
						$basic_monthly_amount, $basic_monthly_amount, 0, 
						$daily_rate, $daily_rate, 0, 
						$id_basic_salary, $id_basic_salary_adj, $working_hours, $salary_frequency_flag);
			
			$salary_sub_arr	= array(KEY_AMOUNT => 0.00, KEY_LESS_AMOUNT => 0.00, KEY_ORIG_AMOUNT => 0.00);
			$salary_amounts	= array($id_basic_salary => $salary_sub_arr);
			
			$less_amount= $total_attendance_deduction[$id_basic_salary];
			$net_amount	= ROUND( ($basic_salary_amount - $less_amount), 4); 
			$salary_sub_arr	= array(KEY_AMOUNT => $net_amount, KEY_LESS_AMOUNT => $less_amount, KEY_ORIG_AMOUNT => $basic_salary_amount);
			$salary_amounts[$id_basic_salary]	= $salary_sub_arr;
			
			$total_absent_count = intval(isset($total_attendance_deduction[KEY_ABSENT_DAYS]) ? $total_attendance_deduction[KEY_ABSENT_DAYS] : 0);
			
			// for additional payout_header columns
			$work_day_param = array();
			$work_day_param[KEY_MONTH_WORK_DAYS]= $month_work_days;
			$work_day_param[KEY_WORKED_DAYS]	= $worked_days - $total_absent_count;
			$daily_rate = ROUND( ($basic_monthly_amount/$month_work_days), 4);
			$work_day_param[KEY_DAILY_RATE]		= $daily_rate;
			
			$this->payroll_process_model->update_payout_work_day_param($payroll_hdr_id, $work_day_param);
			$prev_payroll_work_day_param = $this->payroll_process_model->get_previous_work_day_param($covered_date_from, $payroll_type_id, $payroll_hdr_id, $employee_id);
			
			// if with previous payroll
			if ( ! empty ($prev_payroll_work_day_param))
			{
				RLog::error("prev_payroll_work_day_param NOT EMPTY");
				RLog::error($prev_payroll_work_day_param);
				
				$prev_daily_rate	= $prev_payroll_work_day_param['daily_rate'];
				$prev_worked_days	= $prev_payroll_work_day_param['worked_days'];
				
				$bs_adj_per_day	= $work_day_param[KEY_DAILY_RATE] - $prev_daily_rate;
				$bs_adj_period	= $prev_worked_days * $bs_adj_per_day;
				
				$salary_sub_arr = (isset($salary_amounts[$id_basic_salary_adj]) ? $salary_amounts[$id_basic_salary_adj] : 0);
				
				$bs_adj_orig_amount	= $salary_sub_arr[KEY_ORIG_AMOUNT];
				$bs_adj_amount		= $salary_sub_arr[KEY_AMOUNT];
				
				$bs_adj_orig_amount	+= $bs_adj_period;
				$bs_adj_amount		+= $bs_adj_period;
				
				RLog::error("bs_adj_per_day: [$prev_worked_days] [$bs_adj_per_day] [$bs_adj_period] [$bs_adj_orig] [{$salary_sub_arr[KEY_AMOUNT]}]");
				
				if ($bs_adj_amount != 0)
				{
					$salary_sub_arr	= array(KEY_AMOUNT => ROUND($bs_adj_amount, 4), KEY_LESS_AMOUNT => $salary_sub_arr[KEY_LESS_AMOUNT], 
							KEY_ORIG_AMOUNT => ROUND($bs_adj_orig_amount, 4));
					$salary_amounts[$id_basic_salary_adj] = $salary_sub_arr;
				}
			}
			
			return $salary_amounts;
		}
		catch(PDOException $e)
		{
			throw $e;
		}
		catch(Exception $e)
		{
			throw $e;
		}
	}	
	
	private function _get_basic_salary_deduction_per_compensation($attendance_count, $employee_id, 
						$basic_monthly_amount, $basic_salary_amount, $basic_adjust_amount, 
						$daily_rate, $daily_rate_bs, $daily_rate_bsa, 
						$id_basic_salary, $id_basic_salary_adj, $working_hours, $salary_frequency_flag)
	{
		$round_to_2	= 2;
		$round_to_4	= 4;
		$round_to_6	= 6;
		
		try
		{
			// S: Compute Daily Rate
			$hour_rate		= $daily_rate / $working_hours;
			$hour_rate_bs	= $daily_rate_bs / $working_hours;
			$hour_rate_bsa	= $daily_rate_bsa / $working_hours;

			$hour_rate		= ROUND($hour_rate, $round_to_4);
			$hour_rate_bs	= ROUND($hour_rate_bs, $round_to_4);
			$hour_rate_bsa	= ROUND($hour_rate_bsa, $round_to_4);			
			
			$min_rate		= $hour_rate / 60;
			$min_rate_bs	= $hour_rate_bs / 60;
			$min_rate_bsa	= $hour_rate_bsa / 60;

			$min_rate		= ROUND($min_rate, $round_to_4);
			$min_rate_bs	= ROUND($min_rate_bs, $round_to_4);
			$min_rate_bsa	= ROUND($min_rate_bsa, $round_to_4);
			
			//RLog::error("RATES [$daily_rate] [$hour_rate] [$min_rate]");

			$total_absent_amount_bs			= 0;
			$total_tardiness_amount_bs		= 0;
			$total_leave_wop_amount_bs		= 0;
			$total_leave_hd_wop_amount_bs	= 0;
			$total_absent_amount_bsa		= 0;
			$total_tardiness_amount_bsa		= 0;
			$total_leave_wop_amount_bsa		= 0;
			$total_leave_hd_wop_amount_bsa	= 0;
			
			$total_absent_count				= 0;
			
			$ta_day = 0;
			$ta_hr	= 0;
			$ta_min = 0;
			
			if ( ! empty($attendance_count[ATTENDANCE_STATUS_ABSENT]))
			{
				$ta_day	+= $attendance_count[ATTENDANCE_STATUS_ABSENT];
				
				$total_absent_amount		= ($attendance_count[ATTENDANCE_STATUS_ABSENT] * $daily_rate);
				$total_absent_amount_bs		= ($attendance_count[ATTENDANCE_STATUS_ABSENT] * $daily_rate_bs);
				$total_absent_amount_bsa	= ($attendance_count[ATTENDANCE_STATUS_ABSENT] * $daily_rate_bsa);
				$total_absent_count++;
			}
				
			if ( ! empty($attendance_count[ATTENDANCE_STATUS_LEAVE_WOP]))
			{
				$ta_day	+= $attendance_count[ATTENDANCE_STATUS_LEAVE_WOP];
				
				$total_leave_wop_amount 	= ($attendance_count[ATTENDANCE_STATUS_LEAVE_WOP] * $daily_rate);
				$total_leave_wop_amount_bs 	= ($attendance_count[ATTENDANCE_STATUS_LEAVE_WOP] * $daily_rate_bs);
				$total_leave_wop_amount_bsa = ($attendance_count[ATTENDANCE_STATUS_LEAVE_WOP] * $daily_rate_bsa);
				$total_absent_count++;
			}
				
			if ( ! empty($attendance_count[ATTENDANCE_STATUS_LEAVE_HD_WOP])) // half-day
			{
				$ta_hr	+= $attendance_count[ATTENDANCE_STATUS_LEAVE_HD_WOP];
				
				$total_leave_hd_wop_amount		= ($attendance_count[ATTENDANCE_STATUS_LEAVE_HD_WOP] * $hour_rate);
				$total_leave_hd_wop_amount_bs	= ($attendance_count[ATTENDANCE_STATUS_LEAVE_HD_WOP] * $hour_rate_bs);
				$total_leave_hd_wop_amount_bsa	= ($attendance_count[ATTENDANCE_STATUS_LEAVE_HD_WOP] * $hour_rate_bsa);
				$total_absent_count += 0.5;
			}
			
			
			
			$total_tardiness_amount_hr	= 0;
			$total_tardiness_amount_min = 0;
			if ($salary_frequency_flag == SALARY_DAILY)
			{
				$basic_monthly_amount = $basic_salary_amount + $basic_adjust_amount;
				
				if ( ! empty($attendance_count[KEY_TARDINESS_HR])) // late hours
				{
					$time_hr = $attendance_count[KEY_TARDINESS_HR];
					if($time_hr >= 8)
					{
						$hr_in_days	= floor($time_hr / 8);
						
						$ta_day += $hr_in_days;
						
						$time_hr 		= $time_hr - ($hr_in_days * 8);
						
						$total_absent_amount += ($hr_in_days * $daily_rate);
					}					
					
					$total_tardiness_amount_hr	= $time_hr * $hour_rate;
					
					$ta_hr += $time_hr;
				}
				
				if ( ! empty($attendance_count[KEY_TARDINESS_MIN])) // late min
				{
					$time_min = $attendance_count[KEY_TARDINESS_MIN];
					if($time_min >= 60)
					{
						$min_in_hr	= floor($time_min / 60);
						
						$ta_hr += $min_in_hr;
						
						$time_min 	= $time_min - ($min_in_hr * 60);
						
						$total_tardiness_amount_hr += ($min_in_hr * $hour_rate);
					}					
					
					$total_tardiness_amount_min 	= $time_min * $min_rate;
					
					$ta_min += $time_min;
				}
				
				if($ta_hr >= 8)
				{
					$hr_in_days	= floor($ta_hr/ 8);
					
					$ta_day += $hr_in_days;
					
					$ta_hr = $ta_hr - ($hr_in_days * 8);
				}
				
				
				//RLog::error("DEDUCT TA: [$ta_day] [$ta_hr]  [$ta_min]");
				
				/*
				$deduct_day	=	ROUND( ($total_absent_amount + $total_leave_wop_amount), $round_to_2);
				
				$deduct_hr	=	ROUND( ($total_leave_hd_wop_amount + $total_tardiness_amount_hr), $round_to_2);
				
				$deduct_min	=	ROUND( $total_tardiness_amount_min, $round_to_2);
				*/
				
				$deduct_day	=	ROUND( ($ta_day * $daily_rate), $round_to_2);
				$deduct_hr	=	ROUND( ($ta_hr * $hour_rate), $round_to_2);
				$deduct_min	=	ROUND( ($ta_min * $min_rate), $round_to_2);
			}
			
			//$deduction_bs = $total_absent_amount_bs + $total_tardiness_amount_hr_bs + $total_tardiness_amount_min_bs + $total_leave_wop_amount_bs + $total_leave_hd_wop_amount_bs;
			//$deduction_bsa = $total_absent_amount_bsa + $total_tardiness_amount_hr_bsa + $total_tardiness_amount_min_bsa + $total_leave_wop_amount_bsa + $total_leave_hd_wop_amount_bsa;

			$deduction_bs 	= 0.00;
			$deduction_bsa	= 0.00;
			if ($salary_frequency_flag == SALARY_DAILY)
			{
				//$deduction_bs = ROUND($total_absent_amount + $total_leave_wop_amount + $total_leave_hd_wop_amount + $total_tardiness_amount_hr + $total_tardiness_amount_min, $round_to_2);
				
				$deduction_bs = $deduct_day + $deduct_hr + $deduct_min;
				/*
				if  ($deduction_bs > $basic_salary_amount)
				{
					$deduction_bs = $basic_salary_amount;
					$deduction_bsa= $deduction_bs - $basic_salary_amount;
				}
				*/
				//$deduction_full = $deduct_day + $deduct_hr + $deduct_min;
				//RLog::error("DAILY DEDUCTION ADJUST 1 [$deduction_full] = [$deduction_bs] + [$deduction_bsa]");
				
				//$deduct_adj		= $deduction_full - ($deduction_bs + $deduction_bsa); 
				//$deduction_bsa	+= $deduct_adj;
	
				//RLog::error("DAILY DEDUCTION ADJUST 2 [$deduct_adj] [$deduction_bs] [$deduction_bsa]");		
				
				//RLog::error("DAILY DEDUCTION ADJUST 1 [$deduction_bs]");
			}
			else
			{
				$total_ut_wop_amount_hr_bs	= ($attendance_count[KEY_LWOP_HOURS] * $hour_rate_bs);
				$total_ut_wop_amount_hr_bsa	= ($attendance_count[KEY_LWOP_HOURS] * $hour_rate_bsa);
				
				$total_ut_wop_amount_mm_bs	= ($attendance_count[KEY_LWOP_MINS] * $min_rate_bs);
				$total_ut_wop_amount_mm_bsa	= ($attendance_count[KEY_LWOP_MINS] * $min_rate_bsa);
				
				
				
				$deduction_bs = ROUND( ($total_absent_amount_bs + $total_leave_wop_amount_bs + $total_leave_hd_wop_amount_bs
									+ $total_ut_wop_amount_hr_bs + $total_ut_wop_amount_mm_bs), $round_to_4);
				$deduction_bsa = ROUND( ($total_absent_amount_bsa + $total_leave_wop_amount_bsa + $total_leave_hd_wop_amount_bsa
									+ $total_ut_wop_amount_hr_bsa + $total_ut_wop_amount_mm_bsa), $round_to_4);
				
				//RLog::error("AAA [{$attendance_count[KEY_LWOP_MINS]}][$total_ut_wop_amount_hr_bsa][$total_ut_wop_amount_mm_bsa]"); 
			}

			$total_attendance_deduction = array(
				$id_basic_salary => $deduction_bs, 
				$id_basic_salary_adj => $deduction_bsa, 
				KEY_ABSENT_DAYS => $total_absent_count);
			
			return $total_attendance_deduction;
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


	private function _get_basic_salary_deduction($attendance_count, $employee_id, $basic_salary, $covered_date_from, $covered_date_to, $salary_frequency_flag, 
			$working_days, $working_hours, $id_basic_salary, $id_basic_salary_adj)	
	{
		try
		{
			//GET EMPLOYEE SALARY RATE & EMPLOYMENT TYPE
			$monthly_basic_salary = ( (empty($basic_salary[$id_basic_salary]) ? 0.00 : $basic_salary[$id_basic_salary] ) 
					+ (empty($basic_salary[$id_basic_salary_adj]) ? 0.00 : $basic_salary[$id_basic_salary_adj]) );

			// S: Compute Daily Rate
			$daily_rate = 0.00;
			$hour_rate	= 0.00;		
			if ($salary_frequency_flag == SALARY_DAILY)
			{
				$month_work_days = $this->compute_working_days($covered_date_from, $covered_date_to, FALSE, FALSE);
				$daily_rate	= $monthly_basic_salary / $month_work_days; 
			}
			else
			{
				$daily_rate	= $monthly_basic_salary / $working_days;
			}
			// E: Compute Daily Rate
			
			$hour_rate	= $daily_rate / $working_hours;

			$total_absent_amount			= 0;
			$total_tardiness_amount			= 0;
			$total_leave_wop_amount			= 0;
			$total_leave_hd_wop_amount		= 0;
			
			if ( ! empty($attendance_count[ATTENDANCE_STATUS_ABSENT]))
				$total_absent_amount 		= ($attendance_count[ATTENDANCE_STATUS_ABSENT] * $daily_rate);
				
			if ( ! empty($attendance_count[ATTENDANCE_STATUS_REGULAR_DAY])) // late
				$total_tardiness_amount 	= ($attendance_count[ATTENDANCE_STATUS_REGULAR_DAY] * $hour_rate);
				
			if ( ! empty($attendance_count[ATTENDANCE_STATUS_LEAVE_WOP]))
				$total_leave_wop_amount 	= ($attendance_count[ATTENDANCE_STATUS_LEAVE_WOP] * $daily_rate);
				
			if ( ! empty($attendance_count[ATTENDANCE_STATUS_LEAVE_HD_WOP])) // half-day
				$total_leave_hd_wop_amount 	= ($attendance_count[ATTENDANCE_STATUS_LEAVE_HD_WOP] * $hour_rate);


			$total_attendance_deduction = $total_absent_amount + $total_tardiness_amount + $total_leave_wop_amount + $total_leave_hd_wop_amount;
			$total_attendance_deduction = ($total_attendance_deduction > 0 ? ($total_attendance_deduction * -1) : $total_attendance_deduction);
			return $total_attendance_deduction;
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
	
	
	public function get_employee_attendance($employee_id, $attendance_period_hdr_id, $filed_leave_spl=array())
	{
		$attendance_count = array(ATTENDANCE_STATUS_ABSENT => 0, ATTENDANCE_STATUS_REGULAR_DAY => 0, 
								ATTENDANCE_STATUS_LEAVE_WOP => 0, ATTENDANCE_STATUS_LEAVE_HD_WOP =>0,
								PARAM_ATTENDANCE_STAT_WITH_TENURE => 0,
								KEY_TARDINESS_HR => 0, KEY_TARDINESS_MIN => 0, 
								KEY_SUBS_DEDUCT_COUNT => 0,
								KEY_LWOP_HOURS => 0, KEY_LWOP_MINS=> 0
							);
		try
		{
			//GET ATTENDANCE STATUS FOR WITHOUT PAY
			$table 			= DB_CORE.'.'.$this->common_model->tbl_sys_param;
			$where			= array();
			$key			= 'sys_param_type';
			$where[$key]	= array(array(PARAM_ATTENDANCE_STAT_ABSENT, PARAM_ATTENDANCE_STAT_DAYS_PRESENT), array('IN'));
			$field			= array('GROUP_CONCAT(sys_param_value SEPARATOR \',\') stat_wop ');
			$stat_wop		= $this->common_model->get_general_data($field, $table, $where, FALSE);
			if (empty($stat_wop))
				return 0;
			else
				$stat_wop = $stat_wop['stat_wop'];				
			
			//GET ATTENDANCE
			//$table 			= $this->payroll_process_model->tbl_attendance_period_dtl;
			$table			= array(
								'main'	=> array(
										'table'		=>  $this->payroll_process_model->tbl_attendance_period_dtl,
										'alias'		=> 'A'
								),
								'table1'	=> array(
										'table'		=>  $this->payroll_process_model->tbl_attendance_period_summary,
										'alias'		=> 'B',
										'type'		=> 'LEFT JOIN',
										'condition'	=> 'A.attendance_period_hdr_id = B.attendance_period_hdr_id AND A.employee_id = B.employee_id'
								)
							);
			
			$where			= array();
			$key			= 'A.attendance_period_hdr_id';
			$where[$key]	= $attendance_period_hdr_id;
			$key			= 'A.employee_id';
			$where[$key]	= $employee_id;
			$key			= 'A.attendance_status_id';
			$where[$key] 	= array(explode(',', $stat_wop), array('IN'));
			$order_by		= array('A.attendance_date' => 'ASC');
			$fields			= array('A.attendance_status_id', 'IFNULL(A.basic_hours, 0) basic_hours', 
								'IFNULL(A.tardiness, 0) tardiness', 'IFNULL(A.tardiness_hr, 0) tardiness_hr', 'IFNULL(A.tardiness_min, 0) tardiness_min',
								'IFNULL(A.undertime, 0) undertime', 'IFNULL(A.undertime_hr, 0) undertime_hr', 'IFNULL(A.undertime_min, 0) undertime_min',
								'IFNULL(B.lwop_hours, 0) lwop', 'IFNULL(B.lwop_ut_hr, 0) lwop_hr', 'IFNULL(B.lwop_ut_min, 0) lwop_min'
							);
			$attendance 	= $this->common_model->get_general_data($fields, $table, $where, TRUE, $order_by);

			$total_days_present			= 0;
			$total_absent_count			= 0;
			$total_tardiness_count		= 0;
			$total_tardiness_hr_count	= 0;
			$total_tardiness_min_count	= 0;
			$total_leave_wp_count		= 0;
			$total_leave_wop_count		= 0;
			$total_leave_hd_wop_count	= 0;
			$total_subs_deduct_count	= 0;
			$total_lwop_lt_ut_hr		= 0; // LWOP due to late and undertime (hr)
			$total_lwop_lt_ut_min		= 0; // LWOP due to late and undertime (min)

			foreach ($attendance as $time_record) 
			{
				switch ($time_record['attendance_status_id']) 
				{
					case ATTENDANCE_STATUS_ABSENT:
						$total_absent_count++;
						$total_subs_deduct_count++;
						break;

					case ATTENDANCE_STATUS_REGULAR_DAY: // late
						$total_tardiness_count 		+= $time_record['tardiness'];
						$total_tardiness_count 		+= $time_record['undertime'];
						$total_tardiness_hr_count 	+= $time_record['tardiness_hr'];
						$total_tardiness_min_count 	+= $time_record['tardiness_min'];
						$total_tardiness_hr_count 	+= $time_record['undertime_hr'];
						$total_tardiness_min_count 	+= $time_record['undertime_min'];
						$total_days_present++;

						//$total_undertime_day	= $time_record['tardiness'] + $time_record['undertime'];
						$total_undertime_day	= $time_record['tardiness_hr'] + $time_record['undertime_hr'];
						$total_undertime_day	+= ROUND( (($time_record['tardiness_min'] + $time_record['undertime_min']) / 60), 2 );
						if ($total_undertime_day >= 8.00)
							$total_subs_deduct_count++;
						else if ($total_undertime_day >= 4.00)
							$total_subs_deduct_count += 0.5;

						break;
						
					case ATTENDANCE_STATUS_LEAVE_WP:
						$total_leave_wp_count++;
						$total_subs_deduct_count++;
						break;						

					case ATTENDANCE_STATUS_LEAVE_WOP:
						$total_leave_wop_count++;
						$total_subs_deduct_count++;
						break;

					case ATTENDANCE_STATUS_LEAVE_HD_WP: //half-day with pay
						$total_days_present++;
						$total_subs_deduct_count += 0.5;
					break;						
						
					case ATTENDANCE_STATUS_LEAVE_HD_WOP: //half-day without pay
						$total_leave_hd_wop_count += $time_record['basic_hours'];
						$total_days_present++;
						$total_subs_deduct_count += 0.5;
					break;
				}
				
				$total_lwop_lt_ut_hr	= $time_record['lwop_hr'];
				$total_lwop_lt_ut_min	= $time_record['lwop_min'];
				
			}
			
			if ( ! empty($filed_leave_spl) AND ! empty($filed_leave_spl[$employee_id]))
			{
				$filed_emp_leave_spl = $filed_leave_spl[$employee_id]['leave_earned_used'];
				if ($total_subs_deduct_count > 0)
					$total_subs_deduct_count -= $filed_emp_leave_spl;
				$total_subs_deduct_count = ($total_subs_deduct_count < 0 ? 0 : $total_subs_deduct_count);
			}
			
			$attendance_count = array(ATTENDANCE_STATUS_ABSENT 		=> $total_absent_count, 
									ATTENDANCE_STATUS_REGULAR_DAY 		=> $total_tardiness_count, 
									ATTENDANCE_STATUS_LEAVE_WP 			=> $total_leave_wp_count,
									ATTENDANCE_STATUS_LEAVE_WOP 		=> $total_leave_wop_count, 
									ATTENDANCE_STATUS_LEAVE_HD_WOP 		=> $total_leave_hd_wop_count,
									PARAM_ATTENDANCE_STAT_WITH_TENURE 	=> $total_days_present,
									KEY_TARDINESS_HR					=> $total_tardiness_hr_count,
									KEY_TARDINESS_MIN					=> $total_tardiness_min_count,
									KEY_SUBS_DEDUCT_COUNT				=> $total_subs_deduct_count,
									KEY_LWOP_HOURS						=> $total_lwop_lt_ut_hr,
									KEY_LWOP_MINS						=> $total_lwop_lt_ut_min
								);

			return $attendance_count;
		}
		catch(Exception $e)
		{
			RLog::error($e->getMessage());
			throw $e;
		}
		
	}	

	
	public function compute_longevity_pay($employees, $anniv_emp_date, $effective_date, $sys_params, $new_salary=0, $save=TRUE)
	{
		RLog::info('START: compute_longevity_pay_tenure');
		RLog::info($employees);
		
		$employee_lp = array();
		
		try 
		{
			//get tenure of personnel
			if (isset($employees))
			{
				$sys_param_working_days			= $sys_params[PARAM_WORKING_DAYS];
				$sys_param_longevity_pay_rate	= $sys_params[PARAM_COMPENSATION_LONGEVITY_PAY_RATE];
				$sys_param_min_tenure_in_months	= $sys_params[PARAM_COMPENSATION_LONGEVITY_MIN_TENURE];
				$min_tenure_in_years			= intval($sys_param_min_tenure_in_months/12); 

				if (is_null($effective_date))
					$effective_date = date('Y-m-d');
				
				$d = new DateTime($effective_date);
				$effective_date = $d->format('Y-m-d');
				$effective_last_date = $d->format('Y-m-t');

				$employ_type_tenure	= $this->payroll_process_model->get_govt_svc_employ_type(FALSE);
				$employee_tenure 	= $this->payroll_process_model->get_employee_tenure_for_longevity($effective_date, $employees, $employ_type_tenure);
				
				$add_year = 0;
				$eff_ra_dte_obj	= new DateTime(PARAM_LONGE_PAY_START);
				
				$prev_et				= array();
				$employ_start_date		= NULL;
				$total_pay_amount 		= 0.00;
				$curr_lp_num			= 0;
				$curr_eff_date			= NULL;
				$curr_tenure_eff_date	= NULL;
				$curr_pay_amount		= 0.00;
				$curr_total_amount		= 0.00;
				
				$lp_amount_arr 		= array();
				
				$rec_lp_num 	= 0;
				$rec_eff_date	= NULL;				
				
				foreach($employee_tenure as $emp_tenure)
				{
					if ( ! empty($prev_et) AND $emp_tenure['employee_id'] != $prev_et['employee_id'])
					{
						// CHECK current month for previous employee
						$prev_et['employ_start_date'] = $effective_last_date;
						$add_year = intval($min_tenure_in_years);
						$employ_start_dte_obj	= new DateTime($curr_eff_date);
						$employ_start_dte_obj->modify('+'.$add_year.' years');
						
						//RLog::error("ADD YEAR 2 [$curr_lp_num] [$rec_lp_num] [$add_year] [$employ_start_date] [" . $employ_start_dte_obj->format('Y-m-d') . "] [{$emp_tenure['employ_start_date']}]");
						
						$return = $this->_insert_longevity_pay($prev_et, $employ_start_dte_obj, $curr_lp_num, $curr_eff_date, $lp_amount_arr, $d, $sys_param_min_tenure_in_months, $sys_param_longevity_pay_rate, $save);
						
						$total_pay_amount = (isset($lp_amount_arr['total_amount']) ? $lp_amount_arr['total_amount'] : $total_pay_amount);
						
						if ($total_pay_amount > 0.00)
							$employee_lp[$prev_et['employee_id']] = round($total_pay_amount, 2);
						else
							$employee_lp[$prev_et['employee_id']] = $curr_total_amount;				
						// CHECK current month
						
						
						// clear variables for the new employee
						$employ_start_date	= NULL;
						$total_pay_amount	= 0.00;
						$curr_lp_num		= 0;
						$curr_eff_date		= NULL;
						
						$lp_amount_arr		= array();
						
						$rec_lp_num 	= 0;
						$rec_eff_date	= NULL;						
						
						$prev_et = array();
					}
					
					if (empty($prev_et))
					{
						$employ_start_date	= isset($emp_tenure['employ_start_date']) ? $emp_tenure['employ_start_date'] : NULL;
						$lp_nums			= isset($emp_tenure['lp_num']) ? $emp_tenure['lp_num'] : 0;
						$eff_dates			= isset($emp_tenure['effective_date']) ? $emp_tenure['effective_date'] : NULL;
						$tenure_eff_dates	= isset($emp_tenure['tenure_effective_date']) ? $emp_tenure['tenure_effective_date'] : NULL;
						$pay_amounts		= isset($emp_tenure['pay_amount']) ? $emp_tenure['pay_amount'] : NULL;
						$total_lp_amounts	= isset($emp_tenure['total_amount']) ? $emp_tenure['total_amount'] : 0;
						
						if ($lp_nums != 0)
						{
							$curr_lp_num	= explode(',', $lp_nums);
							$curr_lp_num	= $curr_lp_num[0];
						}
						if ( ! empty($eff_dates))
						{
							$curr_eff_date	= explode(',', $eff_dates);
							$curr_eff_date	= $curr_eff_date[0];
						}
						if ( ! empty($tenure_eff_dates))
						{
							$curr_tenure_eff_date	= explode(',', $tenure_eff_dates);
							$curr_tenure_eff_date	= $curr_tenure_eff_date[0]; 
						}
						if ( ! empty($pay_amounts))
						{
							$curr_pay_amount	= explode(',', $pay_amounts);
							$curr_pay_amount	= $curr_pay_amount[0]; 
						}
						if ( ! empty($total_lp_amounts))
						{
							$curr_total_amount	= explode(',', $total_lp_amounts);
							$curr_total_amount	= $curr_total_amount[0];
						}
						
						$lp_amount_arr = array(
							'pay_amount' => $curr_pay_amount,
							'total_amount' => $curr_total_amount
						);
						
						$rec_lp_num 	= 0;
						$rec_eff_date	= NULL;
					}
					
					$amount_arr = array();
					
					$employ_start_dte_obj	= new DateTime($employ_start_date);
					$emp_start_dte_obj		= new DateTime($emp_tenure['employ_start_date']);

					// A PHW in the service as of the effectivity of R.A. No. 7305 on April 17, 1992, 
					// shall be granted the first Longevity Pay on the day after reaching the first 5 years as PHW 
					if ($eff_ra_dte_obj > $employ_start_dte_obj)
						$employ_start_date	= $eff_ra_dte_obj->format('Y-m-d');
					
					// compute ETD
					$add_year = (($curr_lp_num+1) * intval($min_tenure_in_years));
					$employ_start_dte_obj	= new DateTime($employ_start_date);
					$employ_start_dte_obj->modify('+'.$add_year.' years');
					
					//RLog::error("ADD YEAR 1 [$curr_lp_num] [$rec_lp_num] [$add_year] [$employ_start_date] [" . $employ_start_dte_obj->format('Y-m-d') . "] [{$emp_tenure['employ_start_date']}]");
					
					if ($curr_lp_num == 0 OR $curr_lp_num > $rec_lp_num)
					{
						if ($employ_start_dte_obj > $emp_start_dte_obj)
						{
							unset($employ_start_dte_obj);
							$prev_et	= $emp_tenure;
							
							$employee_lp[$emp_tenure['employee_id']] = $curr_total_amount;
							
							continue;
						}
					}
					
					$return = $this->_insert_longevity_pay($emp_tenure, $employ_start_dte_obj, $rec_lp_num, $rec_eff_date, $lp_amount_arr, $d, $sys_param_min_tenure_in_months, $sys_param_longevity_pay_rate, $save);
					
					$rec_lp_num		= $return['CURR_LP_NUM'];
					$rec_eff_date	= $return['CURR_EFF_DATE'];
					$lp_amount_arr	= $return['AMOUNT_ARR'];
					
					if ($rec_lp_num > $curr_lp_num)
					{
						$curr_lp_num 	= $rec_lp_num;
						$curr_eff_date	= $rec_eff_date;
					}
						
					$total_pay_amount = (isset($lp_amount_arr['total_amount']) ? $lp_amount_arr['total_amount'] : $total_pay_amount);
					
					if ($total_pay_amount > 0.00)
						$employee_lp[$emp_tenure['employee_id']] = round($total_pay_amount, 2);
					else
						$employee_lp[$emp_tenure['employee_id']] = $curr_total_amount;
						
					$prev_et	= $emp_tenure;
				}
				
				// CHECK current month
				$emp_tenure['employ_start_date'] = $effective_last_date;
				$add_year = intval($min_tenure_in_years);
				$employ_start_dte_obj	= new DateTime($curr_eff_date);
				$employ_start_dte_obj->modify('+'.$add_year.' years');
				
				//RLog::error("ADD YEAR 3 [$curr_lp_num] [$curr_eff_date] [$add_year] [$employ_start_date] [" . $employ_start_dte_obj->format('Y-m-d') . "] [{$emp_tenure['employ_start_date']}]");
				
				$return = $this->_insert_longevity_pay($emp_tenure, $employ_start_dte_obj, $curr_lp_num, $curr_eff_date, $lp_amount_arr, $d, $sys_param_min_tenure_in_months, $sys_param_longevity_pay_rate, $save);
				$rec_lp_num		= $return['CURR_LP_NUM'];
				$rec_eff_date	= $return['CURR_EFF_DATE'];
				$lp_amount_arr	= $return['AMOUNT_ARR'];
				
				$total_pay_amount = (isset($lp_amount_arr['total_amount']) ? $lp_amount_arr['total_amount'] : $total_pay_amount);
				
				if ($total_pay_amount > 0.00)
					$employee_lp[$emp_tenure['employee_id']] = round($total_pay_amount, 2);
				else
					$employee_lp[$emp_tenure['employee_id']] = $curr_total_amount;				
				// CHECK current month
				
			}
			
			/*
			RLog::info('-- S: employee longevity pay --');
				RLog::info($employee_lp);
			RLog::info('-- E: employee longevity pay --');			
			*/
		}
		catch (Exception $e)
		{
			RLog::error($e->getMessage());
			throw $e;
		}
		
		return $employee_lp;

		RLog::info('END: compute_longevity_pay_tenure');
	}	
	


	private function _insert_longevity_pay($emp_tenure, $new_etd_dte_obj, $curr_lp_num, $curr_eff_date,  
						$lp_amount_arr, $pay_eff_date_obj, $sys_param_min_tenure_in_months, $sys_param_longevity_pay_rate, $save)
	{
		$return = array('CURR_LP_NUM' => $curr_lp_num, 'CURR_EFF_DATE' => $curr_eff_date, 'AMOUNT_ARR' => array());
		$amount_arr = array();
		
		try
		{
			$emp_etd			= $new_etd_dte_obj->format('Y-m-d');
			$emp_tenure_dte_obj = new DateTime($emp_tenure['employ_start_date']);
			
			if ($curr_lp_num > 0) // if employee is already receiving longevity pay
			{
				$emp_tenure_dte_obj = new DateTime($emp_tenure['employ_start_date']);
				
				// new milestone
				//RLog::error("new milestone? ... [{$emp_tenure_dte_obj->format('Y-m-d')}] >= [{$new_etd_dte_obj->format('Y-m-d')}] [$curr_lp_num] [$curr_eff_date]");
				
				$emp_tenure['lp_num']			= $curr_lp_num;
				$emp_tenure['effective_date']	= $curr_eff_date;
				
				$emp_tenure['pay_amount']		= $lp_amount_arr['pay_amount'];
				$emp_tenure['total_amount']		= $lp_amount_arr['total_amount'];
				
				if ($emp_tenure_dte_obj  >= $new_etd_dte_obj)
				{
					$amount_arr = $this->_get_longevity_total_amount($emp_tenure, $sys_param_longevity_pay_rate, FALSE);

					if ( intval($new_etd_dte_obj->format('d')) > 1 )
					{
						$new_pay_amount	= $amount_arr['raw_amount'];
						$amount_arr		= $this->_get_longevity_total_amount_prorated($emp_tenure, $new_pay_amount, $pay_eff_date_obj->format('Y'), $sys_param_longevity_pay_rate);
					}
					
					$curr_lp_num	+= 1;
					$curr_eff_date	= $emp_etd;
					$emp_tenure['tenure_effective_date'] = $emp_tenure['employ_start_date']; 
					
					//RLog::error("NEW MILESTONE !!! ESD [{$emp_tenure['employ_start_date']}] ETD [$emp_etd] ET LP [{$emp_tenure['lp_num']}] CURR LP NUM[$curr_lp_num] CURR EFF DATE [$curr_eff_date]");
					
					if ($save)
						$amount_arr	= $this->payroll_process_model->insert_longevity_milestone($curr_eff_date, $curr_lp_num, $emp_tenure, $amount_arr, FALSE, FALSE);
						
					//RLog::error("INSERT NEW MILESTONE AMOUNT_ARR:");
					//RLog::error($amount_arr);
				}
				else
				{
					//RLog::error("UPDATE LONGE PAY ? [{$emp_tenure['employ_monthly_salary']}] != [{$emp_tenure['basic_amount']}]  [{$emp_tenure['lp_num']}] [{$emp_tenure['effective_date']}] [{$emp_tenure['employ_start_date']}]");
					
					// updated basic salary
					if ($emp_tenure['employ_monthly_salary'] != $emp_tenure['basic_amount'])
					{
						$amount_arr = $this->_get_longevity_total_amount($emp_tenure, $sys_param_longevity_pay_rate, TRUE);
						
						if ($save)
							$amount_arr = $this->payroll_process_model->insert_longevity_milestone($curr_eff_date, $curr_lp_num, $emp_tenure, $amount_arr, TRUE, FALSE);
							
						//RLog::error("UPDATE AMOUNT_ARR:");
						//RLog::error($amount_arr);
					}
					else
					{
						//RLog::error("NO CHANGES IN LONGE ...");
					}
				}
			}
			else
			{
				if ($emp_tenure_dte_obj  >= $new_etd_dte_obj)
				{
					// first milestone
					$amount_arr 	= $this->_get_longevity_total_amount($emp_tenure, $sys_param_longevity_pay_rate, FALSE);
					$curr_lp_num 	+= 1;
					$curr_eff_date	= $emp_etd;
					$emp_tenure['tenure_effective_date'] = $emp_etd; 
					
					RLog::error("FIRST MILESTONE !!! ESD [{$emp_tenure['employ_start_date']}] ETD [$emp_etd] ET LP [{$emp_tenure['lp_num']}] CURR LP NUM[$curr_lp_num] CURR EFF DATE [$curr_eff_date]");
					
					if ($save)
						$amount_arr = $this->payroll_process_model->insert_longevity_milestone($curr_eff_date, $curr_lp_num, $emp_tenure, $amount_arr, FALSE, TRUE);
	
					//RLog::error("FIRST MILESTONE AMOUNT_ARR:");
					//RLog::error($amount_arr);
				}
			}
		}
		catch (Exception $e)
		{
			RLog::error($e->getMessage());
			throw $e;			
		}
		
		if ( ! empty($amount_arr))
			$return = array('CURR_LP_NUM' => $curr_lp_num, 'CURR_EFF_DATE' => $curr_eff_date, 'CURR_TENURE_EFF_DATE' => $amount_arr['lp_tenure_effective_date'], 'AMOUNT_ARR' => $amount_arr);
		
		
		return $return;
	}
	
	private function _get_longevity_total_amount($param, $longevity_pay_rate, $same_lp_num=TRUE)
	{
		try
		{
			//RLog::error("_get_longevity_total_amount [$longevity_pay_rate] [$same_lp_num]");
			//RLog::error($param);
			
			$amount = array('raw_amount' => 0.00, 'total_amount' => 0.00);
			
			$raw_lp_amount		= 0.00;
			$total_lp_amount	= 0.00;
			if ($same_lp_num)
			{
				// new monthly salary: update current LP record in employee_longevity_pay
				$raw_lp_amount		= round( ($param['employ_monthly_salary'] * $longevity_pay_rate), 2, PHP_ROUND_HALF_UP);
				$old_base_lp_amount	= $param['total_amount'] - $param['pay_amount'];
				$total_lp_amount	= $old_base_lp_amount + $raw_lp_amount;
			}
			else
			{
				$raw_lp_amount		= round( ($param['employ_monthly_salary'] * $longevity_pay_rate), 2, PHP_ROUND_HALF_UP);
				$total_lp_amount	= $param['total_amount'] + $raw_lp_amount;
			}
			
			$amount['raw_amount']	= $raw_lp_amount;
			$amount['total_amount']	= $total_lp_amount;
			
			return $amount;
		}
		catch (Exception $e)
		{
			RLog::error($e->getMessage());
			throw $e;
		}
	}
	
	private function _get_longevity_total_amount_prorated($param, $new_pay_amount, $effective_year, $longevity_pay_rate)
	{
		try
		{
			
			//RLog::error("_get_longevity_total_amount_prorated [$new_pay_amount] [$effective_year] [$longevity_pay_rate]");
			//RLog::error($param);
			
			$old_pay_amount = $param['pay_amount'];
			
			$employ_start_date = $param['employ_start_date'];

			$d = new DateTime($employ_start_date);
			$employ_start_day = ((int)$d->format('d') - 1);
			
			$d = new DateTime($d->format("$effective_year-m-d"));
			$no_days_in_month = (int)$d->format('t');
			
			$old_rate_day = $employ_start_day;
			$new_rate_day = $no_days_in_month - $old_rate_day;
			
			//RLog::info("_get_longevity_total_amount_prorated [".$param['employee_id']."] [$employ_start_day] [$no_days_in_month] [$old_rate_day] [$new_rate_day]");
			
			$amount = array('raw_amount' => 0.00, 'total_amount' => 0.00);
			
			$total_lp_amount 		= ( ($old_pay_amount / $no_days_in_month) * $old_rate_day ) + 
									( ($new_pay_amount / $no_days_in_month) * $new_rate_day );

			$amount['raw_amount']	= $total_lp_amount;
									
			$total_lp_amount		= $param['total_amount'] + $total_lp_amount;
			
			$amount['total_amount']	= $total_lp_amount;
			
			return $amount;
		}
		catch (Exception $e)
		{
			RLog::error($e->getMessage());
			throw $e;
		}
	}	
	
	/*
	 * This function computes the Loyalty Cash Award of an employee.
	 * @param integer	Employee Unique ID
	 * @param integer	Payout Summary Unique ID
	 * @param date 		End date of covered period
	 * @param integer	Attendance Period Unique ID
	 * @param array		System Parameters (refer to get_sys_gen_params())
	 */
	public function compute_loyalty_cash_award($employee_id, $anniv_emp_date, $payout_summary_id=NULL, $covered_date_to=NULL, $sys_params, $attendance_period_hdr_id=NULL)
	{
		RLog::info("START: compute_loyalty_cash_award [$employee_id] [$payout_summary_id] [$anniv_emp_date] [$covered_date_to] [".$sys_params[PARAM_LCA_EFFECTIVE_START_DATE]."]");
		if ( empty($employee_id))
			throw new Exception('Compute Loyalty Cash Award [Employee]: ' . $this->lang->line('param_not_defined'));
		
		$employee_lca 				= array();
		$employee_lca[$employee_id] = 0;
		
		$LOGGING_ON = FALSE;

		try 
		{
			// get employees that have been given LCA
			$frmt_dte = new DateTime($covered_date_to);
			$effective_year = $frmt_dte->format('Y');
			unset($frmt_dte);
			
			$employee_with_lca_year = $this->payroll_process_model->get_employee_with_lca($employee_id, $effective_year);
			$arr_val				= array('milestone_year', 'csv_milestone_years', 'csv_deferred_dates', 'csv_award_years', 'csv_effective_dates');
			$employee_with_lca_year = set_key_value($employee_with_lca_year, 'employee_id', $arr_val, TRUE);
			
			$employee_effective_dates	= array();
			$employee_deferred_dates	= array();
			$employee_award_years		= array();
			
			$employee_with_lca_rec = $employee_with_lca_year[$employee_id];
			
			if (! empty($employee_with_lca_rec['csv_milestone_years']))
			{
				$employee_deferred_milestone= explode(',', $employee_with_lca_rec['csv_milestone_years']);
				$employee_effective_date 	= explode(',', $employee_with_lca_rec['csv_effective_dates']);
				$employee_deferred_date 	= explode(',', $employee_with_lca_rec['csv_deferred_dates']);
				$employee_award_year 		= explode(',', $employee_with_lca_rec['csv_award_years']);
				foreach ($employee_deferred_milestone as $k=>$v)
				{
					$employee_deferred_dates[$v] = $employee_deferred_date[$k];
					$employee_award_years[$v] = $employee_award_year[$k];
					$employee_effective_dates[$v] = $employee_effective_date[$k];
				}
				unset($employee_deferred_milestone);
				unset($employee_effective_date);
				unset($employee_deferred_date);
				unset($employee_award_year);
			}
			
			$milestone_year = (empty($employee_with_lca_rec['milestone_year']) ? 0 : $employee_with_lca_rec['milestone_year']);
			unset($employee_with_lca_rec);

			if ($LOGGING_ON) {
				RLog::info('S: EMPLOYEE WITH LCA:');
					RLog::info($employee_with_lca_year);
					RLog::info($employee_award_years);
				RLog::info('E: EMPLOYEE WITH LCA:');
			}

			$covered_period		= array();
			$covered_period[] 	= $sys_params[PARAM_LCA_EFFECTIVE_START_DATE];
			$covered_period[] 	= $covered_date_to;
			
			$employee_work_exp 	= $this->payroll_process_model->get_employee_work_experiences($employee_id, $covered_period);
			$no_month_years 	= 0;
			$total_lwop 		= 0;
			$milestone_lwop 	= array();
			$last_emp_work_id 	= NULL;
			
			$sys_param_work_months					= $sys_params[PARAM_WORKING_MONTHS];
			$sys_param_lca_auth_lwop_first			= $sys_params[PARAM_LCA_AUTH_LWOP_FIRST];
			$sys_param_lca_auth_lwop_next			= $sys_params[PARAM_LCA_AUTH_LWOP_NEXT];
			$sys_param_lca_milestone_year_first		= $sys_params[PARAM_LCA_MILESTONE_YEAR_FIRST];
			$sys_param_lca_milestone_year_next		= $sys_params[PARAM_LCA_MILESTONE_YEAR_NEXT];
			$sys_param_lca_milestone_first_amount	= $sys_params[PARAM_LCA_MILESTONE_FIRST_AMOUNT];
			$sys_param_lca_milestone_next_amount	= $sys_params[PARAM_LCA_MILESTONE_NEXT_AMOUNT];
			
			$total_milestone	= $sys_param_lca_milestone_year_first;
			$govt_svc_flag		= YES;
			
			foreach ($employee_work_exp as $work)
			{
				$no_months 		= $work['no_months'];
				$service_lwop 	= $work['service_lwop'];
				$govt_svc_flag 	= $work['govt_service_flag'];
				
				if ($govt_svc_flag == YES) // sum all leave without pay for all government service
				{
					$total_lwop 		+= $service_lwop;
					$last_emp_work_id 	= $work['employee_work_experience_id'];
				}
				else // if private company, reset count of years
				{
					$no_month_years = 0;
					$total_lwop 	= 0;
					continue;
				}
				
				$no_month_years += round($no_months / $sys_param_work_months, 4);
				if ($total_milestone <= ceil($no_month_years))
				{
					if ($LOGGING_ON) 	RLog::info("LWOP MILESTONE [$employee_id] [$total_milestone] [$total_lwop]");
					
					$milestone_lwop[$total_milestone] 	= $total_lwop;
					$total_lwop 						= 0;

					$total_milestone += $sys_param_lca_milestone_year_next;
				}
				
				if ($LOGGING_ON) 	RLog::info("LOOP WORK EXPERIENCE [$employee_id] [$total_milestone] [$no_month_years] [$no_months] [$total_lwop] [$govt_svc_flag]");
			}
			$no_month_years = round($no_month_years, 0);
			if ($LOGGING_ON) {
				RLog::info("S: work no of years [$employee_id] [$govt_svc_flag] [$last_emp_work_id]");
					RLog::info("[$no_month_years]");
					RLog::info($milestone_lwop);
				RLog::info("E: work no of years [$employee_id] [$govt_svc_flag] [$last_emp_work_id]");
			}
			
			// if no of years is greater than or equal initial milestone, check if LCA is already given for the milestone
			$total_amount 		= 0;
			if ($no_month_years >= $sys_param_lca_milestone_year_first)
			{
				$no_years 			= $sys_param_lca_milestone_year_first;
				$total_milestone 	= (empty($milestone_year) ? $no_years : $milestone_year);
				
				$dte_covered_date_to= new DateTime($covered_date_to);
				
				while ($no_month_years >= $total_milestone)
				{
					$deferred_flag 			= FALSE;
					$grant_deferred_flag 	= FALSE;
					
					if ($LOGGING_ON)  RLog::info("[$employee_id] MY > NMY = $milestone_year >= $no_month_years [$effective_year] [{$employee_award_years[$milestone_year]}] [{$employee_deferred_dates[$milestone_year]}]");
					if ( ! empty($employee_deferred_dates[$milestone_year]))
					{
						// if deferred and deferred_up_to_date is <= $covered_date_to, grant LCA
						$dte_deferred_up_to_date 	= new DateTime($employee_deferred_dates[$milestone_year]);
						$award_year					= $employee_award_years[$milestone_year];

						if ( ! empty($dte_deferred_up_to_date))
						{
							if ($LOGGING_ON)  RLog::info("[$employee_id] GRANT DEFERRED? [{$dte_deferred_up_to_date->format('Y-m-d')}] [{$dte_covered_date_to->format('Y-m-d')}] [$effective_year] [$award_year]");
							if ( $dte_deferred_up_to_date <= $dte_covered_date_to AND
									(empty($award_year) OR $effective_year == $award_year) )
							{
								$grant_deferred_flag	= TRUE;
								$total_amount 			+= ($milestone_year == $sys_param_lca_milestone_year_first ? $sys_param_lca_milestone_first_amount : 
															$sys_param_lca_milestone_next_amount);
								if ($LOGGING_ON)  RLog::info("GRANT DEFERRED: [$employee_id] [{$milestone_year}] [$no_years] [$total_amount]");
							}
						}

						if ($grant_deferred_flag)
						{
							$award_year = $dte_covered_date_to->format('Y');
							if ($LOGGING_ON)  RLog::info("INSERT DEFERRED TO LCA TABLE [$employee_id][$total_milestone][$last_emp_work_id][{$employee_effective_dates[$milestone_year]}][$payout_summary_id][$deferred_flag][$no_month_years][$no_years]");
							$this->_insert_employee_loyalty($employee_id, $total_milestone, $last_emp_work_id, $employee_effective_dates[$milestone_year], $award_year, $payout_summary_id, $deferred_flag, $grant_deferred_flag);
						}
						else
						{
							$employee_lca[$employee_id] = 0;
							return $employee_lca;
						}
						
						$no_month_years = $no_month_years - $milestone_year;
						$no_years 		= $sys_param_lca_milestone_year_next;
						
						continue;

					}

					if ($no_years == $sys_param_lca_milestone_year_first)
					{
						// check if initial LWOP is within the authorized number, currently set at 50
						$no_lwop = empty($milestone_lwop[$no_years]) ? 0 : $milestone_lwop[$no_years];
						if ($no_lwop > $sys_param_lca_auth_lwop_first)
						{
							if ($LOGGING_ON)  RLog::info("First LCA [$employee_id] Auth LWOP is not within limit");
							$deferred_flag = TRUE;
						}
					}
					else
					{
						// check if succeeding LWOP is within the authorized number, currently set at 25
						$no_lwop = empty($milestone_lwop[$no_years]) ? 0 : $milestone_lwop[$no_years];
						if ($no_lwop > $sys_param_lca_auth_lwop_next)
						{
							if ($LOGGING_ON)  RLog::info("Succeeding LCA [$employee_id] Auth LWOP is not within limit");
							
							$no_month_years = $no_month_years - $milestone_year;
							$no_years 		= $sys_param_lca_milestone_year_next;
							
							$deferred_flag = TRUE;							
						}
					}
					
					$no_month_years = $no_month_years - $milestone_year;
					$no_years 		= $sys_param_lca_milestone_year_next;					

					if ($deferred_flag)
						$total_amount = 0;
					else
					{
						if ($no_month_years >= $total_milestone)
						{						
							$total_amount += ($total_milestone == $sys_param_lca_milestone_year_first ? $sys_param_lca_milestone_first_amount : 
											$sys_param_lca_milestone_next_amount);
						}
					}
					
					// insert into employee_loyalty_cash_awards table
					if ($total_milestone > $milestone_year)
					{
						// compute ETD
						$anniv_dte_obj	= new DateTime($anniv_emp_date);
						$anniv_dte_obj->modify('+'.$total_milestone.' years');
						$emp_etd		= $anniv_dte_obj->format('Y-m-d');
						unset($anniv_dte_obj);
						
						RLog::info("[$total_milestone] ANNIV: [$anniv_emp_date] ETD: [$emp_etd]");						
						
						$award_year = $dte_covered_date_to->format('Y');
						$this->_insert_employee_loyalty($employee_id, $total_milestone, $last_emp_work_id, $emp_etd, $award_year, $payout_summary_id, $deferred_flag, $grant_deferred_flag);
						if ($LOGGING_ON)  RLog::info("INSERT TO LCA TABLE [$employee_id][$total_milestone][$no_month_years][$no_years][$deferred_flag]");
					}
					
					$total_milestone += $no_years;
				}
				
				$employee_lca[$employee_id] = $total_amount;

			}
			else
				return $employee_lca[$employee_id] = 0;
		}
		catch (Exception $e)
		{
			$message = $e->getMessage();
			RLog::error($message);
			
			throw $e;
		}

		RLog::info("END: compute_loyalty_cash_award [$employee_id] [".$employee_lca[$employee_id]."]");		
		return $employee_lca;
	}
	
	public function compute_cna($employee_id, $covered_date_from, $covered_date_to, $sys_params)
	{
		$employee_cna = array();
		try
		{
			if(empty($sys_params[PARAM_DEDUCTION_ID_CNA]) OR empty($sys_params[PARAM_COMPENSATION_CNA_AMOUNT])
					OR empty($sys_params[PARAM_COMPENSATION_CNA_MEMBER_RATE])  OR empty($sys_params[PARAM_COMPENSATION_CNA_NON_MEMBER_RATE]) )
				throw new Exception('CNA: ' . $this->lang->line('sys_param_not_defined'));			
			
			$deduction_id 		= $sys_params[PARAM_DEDUCTION_ID_CNA];
			$cna_base_amount 	= $sys_params[PARAM_COMPENSATION_CNA_AMOUNT];
			$member_rate 		= $sys_params[PARAM_COMPENSATION_CNA_MEMBER_RATE];
			$non_member_rate 	= $sys_params[PARAM_COMPENSATION_CNA_NON_MEMBER_RATE];

			// check if employee pays for CNA			
			$fields = array('start_date', 'payment_count');
			$table	= $this->common_model->tbl_employee_deductions;
			$where['deduction_id'] = $deduction_id;
			$where['employee_id'] = $employee_id;
			$cna_deduction = $this->common_model->get_general_data($fields, $table, $where, FALSE);
			
			$employee_cna[$employee_id][KEY_ORIG_AMOUNT] = $cna_base_amount;
			
			if (empty($cna_deduction))
				$employee_cna[$employee_id][KEY_AMOUNT] = $cna_base_amount * $non_member_rate;
			else
				$employee_cna[$employee_id][KEY_AMOUNT] = $cna_base_amount * $member_rate;
			
		}
		catch (Exception $e)
		{
			RLog::error($e->getMessage());
			throw $e;
		}
		
		return $employee_cna;
	}
	
	private function _get_monthly_salary($salary_grade, $pay_step, $paid_month, $paid_year, $paid_amount, $salary_schedules)
	{
		try
		{
			$mo = $paid_month;
			$sal_sched_y = 0;
			$sal_sched_n = 0;
			while ($mo > 0)
			{
				$check_key	= $paid_year . '-' . $mo . '-' . $salary_grade . '-' . $pay_step;
				$sal_scheds = ( ! empty($salary_schedules[$check_key]) ? $salary_schedules[$check_key] : array() );
				
				$sal_sched_y = ( ! empty($sal_scheds[YES]) ? $sal_scheds[YES] : $sal_sched_y ) ;
				$sal_sched_n = ( ! empty($sal_scheds[NO]) ? $sal_scheds[NO] : $sal_sched_n ) ;
				if ( ! empty($sal_sched_y) &&  ! empty($sal_sched_n))
					break;
					
				$mo--;
			}
			
			return ( ! empty($sal_sched_y) ? $sal_sched_y : $sal_sched_n );
		} catch (Exception $e)
		{
			RLog::error($e->getMessage());
			throw $e;
		}
	}

	public function compute_differential_salary($employee_id, $salary_grade, $pay_step, $covered_date_from, $covered_date_to, $sys_params, $salary_schedules, $gsis_table)
	{
		RLog::info("START: compute_differential_salary [$employee_id] [$salary_grade][$pay_step]");
		if ( empty($sys_params[PARAM_COMPENSATION_ID_SALDIFFL]) || empty($sys_params[PARAM_DEDUCTION_ID_GSISDIFFL])
				 || empty($sys_params[PARAM_DEDUCTION_ID_GSIS]) )
			throw new Exception('Salary Differential ID: ' . $this->lang->line('param_not_defined'));
			
		if ( empty($employee_id) || empty($salary_grade) || empty($pay_step) || empty($salary_schedules) )
			throw new Exception('Salary Differential [Employee/Salary Grade]: ' . $this->lang->line('param_not_defined'));

		$sal_diff_id = $sys_params[PARAM_COMPENSATION_ID_SALDIFFL];
		$gsis_diff_id = $sys_params[PARAM_DEDUCTION_ID_GSISDIFFL];
		
		$employee_comp = array();
		$employee_comp[$employee_id][$sal_diff_id][KEY_AMOUNT] 	= 0;
		$employee_comp[$employee_id]['deduction'][$gsis_diff_id]= 0;
		
		try
		{
			// differential: salary
			$compensation 	= array($sys_params[PARAM_COMPENSATION_ID_BASIC_SALARY], $sys_params[PARAM_COMPENSATION_ID_BASIC_SALARY_ADJUSTMENT]);
			$covered_period = array($covered_date_from, $covered_date_to);
			$paid_salaries 	= $this->payroll_process_model->get_actual_payout_by_month($employee_id, $covered_period, $compensation, NULL, TRUE);
			
			$diff_amt_salary = 0;
			$monthly_salary	 = array();
			if ( ! empty($paid_salaries))
			{
				foreach ($paid_salaries as $paid_salary)
				{
					$paid_month		= $paid_salary['effective_month'];
					$paid_year		= $paid_salary['effective_year'];
					$paid_amount	= $paid_salary['amount'];

					$new_salary = $this->_get_monthly_salary($salary_grade, $pay_step, $paid_month, $paid_year, $paid_amount, $salary_schedules);
					$diff_amt_salary += ( $new_salary - $paid_amount );

					$key = $paid_year . '-' . $paid_month;
					$monthly_salary[$key] = $new_salary;
				}
			}

			$diff_amt_salary = ($diff_amt_salary < 0 ? 0 : $diff_amt_salary);
			$employee_comp[$employee_id][$sal_diff_id][KEY_AMOUNT] = $diff_amt_salary;
			$employee_comp[$employee_id][$sal_diff_id][KEY_ORIG_AMOUNT] = $diff_amt_salary;
			$employee_comp[$employee_id][$sal_diff_id][KEY_LESS_AMOUNT] = 0;

			
			// differential: GSIS
			$deduction		= array($sys_params[PARAM_DEDUCTION_ID_GSIS]);
			$covered_period = array($covered_date_from, $covered_date_to);
			$paid_deductions= $this->payroll_process_model->get_actual_payout_by_month($employee_id, $covered_period, NULL, $deduction, TRUE);			
			
			$diff_amt_gsis = 0;
			if ( ! empty($paid_deductions))
			{
				foreach ($paid_deductions as $paid_gsis)
				{
					$paid_month		= $paid_gsis['effective_month'];
					$paid_year		= $paid_gsis['effective_year'];
					$paid_amount	= $paid_gsis['amount'];
					
					$key = $paid_year . '-' . $paid_month;
					$base_salary = ( ! empty($monthly_salary[$key]) ? $monthly_salary[$key] : 0 );
					
					$params = array();
					$params['employ_monthly_salary']= $base_salary;
					$params['employee_id']			= $employee_id;
					$share_amounts 					= $this->compute_gsis($covered_date_to, $params, $gsis_table, TRUE);
					
					$diff_amt_gsis += ( ! empty($share_amounts[KEY_PERSONAL_SHARE]) ? ($share_amounts[KEY_PERSONAL_SHARE] - $paid_amount) : 0 );
				}
			}			
			
			$employee_comp[$employee_id]['deduction'][$gsis_diff_id] = $diff_amt_gsis;
		}
		catch (Exception $e)
		{
			RLog::error($e->getMessage());
			throw $e;
		}

		RLog::info("END: compute_differential_salary [$employee_id] [".$employee_comp[$employee_id][$sal_diff_id]."]");		
		return $employee_comp;
	}
	
	public function compute_differential_hazard($employee_id, $salary_grade, $pay_step, $covered_date_from, $covered_date_to, $sys_params, 
			$salary_schedules, $pro_rated_flag, $prorated_rates=array())
	{
		RLog::info("START: compute_differential_hazard [$employee_id] [$salary_grade] [$pay_step] [$pro_rated_flag] ");
		if ( empty($sys_params[PARAM_COMPENSATION_ID_HAZDIFFL]) || empty($sys_params[PARAM_COMPENSATION_ID_HAZARD_PAY]) )
			throw new Exception('Hazard Differential ID: ' . $this->lang->line('param_not_defined'));		
		if ( empty($employee_id) || empty($salary_grade) || empty($pay_step) )
			throw new Exception('Compute Hazard Pay Differential [Employee/Salary Grade]: ' . $this->lang->line('param_not_defined'));
			
		$haz_diff_id	= $sys_params[PARAM_COMPENSATION_ID_HAZDIFFL];
		$haz_pay_id		= $sys_params[PARAM_COMPENSATION_ID_HAZARD_PAY];
			
		$employee_comp = array();
		$employee_comp[$employee_id][$haz_diff_id][KEY_AMOUNT] = 0;
		
		try 
		{
			$compensation 	= array($haz_pay_id);
			$covered_period = array($covered_date_from, $covered_date_to);
			$paid_amounts 	= $this->payroll_process_model->get_actual_payout_by_month($employee_id, $covered_period, $compensation, NULL, TRUE);
			
			$diff_amt = 0;
			if ( ! empty($paid_amounts))
			{
				foreach ($paid_amounts as $paid_amt)
				{
					$paid_month		= $paid_amt['effective_month'];
					$paid_year		= $paid_amt['effective_year'];
					$paid_amount	= $paid_amt['amount'];

					$new_salary 	= $this->_get_monthly_salary($salary_grade, $pay_step, $paid_month, $paid_year, $paid_amount, $salary_schedules);
					
					$params = array();
					$params['compensation_id'] = $haz_pay_id;
					$params['pro_rated_flag'] = $pro_rated_flag;
					$new_hazard_pay = $this->compute_prorated_value($params, $new_salary, 0, $salary_grade, $prorated_rates);					

					//NOTE: $new_hazard_pay = array(KEY_AMOUNT => 0.00, KEY_LESS_AMOUNT => 0.00, KEY_ORIG_AMOUNT => 0.00);
					$diff_amt += ( $new_hazard_pay[KEY_AMOUNT] - $paid_amount );
				}
			}

			$diff_amt = ($diff_amt < 0 ? 0 : $diff_amt);
			$employee_comp[$employee_id][$haz_diff_id][KEY_AMOUNT] = $diff_amt;
			$employee_comp[$employee_id][$haz_diff_id][KEY_ORIG_AMOUNT] = $diff_amt;
			$employee_comp[$employee_id][$haz_diff_id][KEY_LESS_AMOUNT] = 0;
		}
		catch (Exception $e)
		{
			$message = $e->getMessage();
			RLog::error($message);
			
			throw $e;
		}

		RLog::info("END: compute_differential_hazard [$employee_id] [".$employee_comp[$employee_id][$haz_diff_id]."]");		
		return $employee_comp;
	}

	public function compute_differential_longevity($employee_id, $salary_grade, $pay_step, $anniv_emp_date, $covered_date_from, $covered_date_to, $sys_params, 
			$salary_schedules)
	{
		RLog::info("START: compute_differential_longevity [$employee_id] [$salary_grade] [$pay_step] [$anniv_emp_date] [".$sys_params[PARAM_COMPENSATION_ID_LONGEDIFFL]."] [".$sys_params[PARAM_COMPENSATION_ID_LONGEVITY_PAY]."]");
		if ( empty($sys_params[PARAM_COMPENSATION_ID_LONGEDIFFL]) || empty($sys_params[PARAM_COMPENSATION_ID_LONGEVITY_PAY]) )
			throw new Exception('Longevity Differential ID: ' . $this->lang->line('param_not_defined'));		
		if ( empty($employee_id) || empty($salary_grade) || empty($pay_step) )
			throw new Exception('Compute Longevity Pay Differential [Employee/Salary Grade]: ' . $this->lang->line('param_not_defined'));
			
		$longe_diff_id	= $sys_params[PARAM_COMPENSATION_ID_LONGEDIFFL];
		$longe_pay_id	= $sys_params[PARAM_COMPENSATION_ID_LONGEVITY_PAY];
			
		$employee_comp = array();
		$employee_comp[$employee_id][$longe_diff_id][KEY_AMOUNT] = 0;
		
		try 
		{
			$compensation 	= array($longe_pay_id);
			$covered_period = array($covered_date_from, $covered_date_to);
			$paid_amounts 	= $this->payroll_process_model->get_actual_payout_by_month($employee_id, $covered_period, $compensation, NULL, TRUE);
			$computed_diff = $this->_compute_monthly_longe_diff($employee_id,$covered_date_from, $covered_date_to);
			$diff_amt = 0;
			if ( ! empty($paid_amounts))
			{
				foreach ($paid_amounts as $paid_amt)
				{
					$paid_month   = $paid_amt['effective_month'];
					$paid_year    = $paid_amt['effective_year'];
					$paid_amount  = $paid_amt['amount'];
					$longe_amount = $computed_diff[$paid_year][$paid_month];

					if($longe_amount > $paid_amount)
					$diff_amt += ( $longe_amount - $paid_amount );
								
				}
			}
			
			$diff_amt = ($diff_amt < 0 ? 0 : $diff_amt);
			$employee_comp[$employee_id][$longe_diff_id][KEY_AMOUNT] = $diff_amt;
			$employee_comp[$employee_id][$longe_diff_id][KEY_ORIG_AMOUNT] = $diff_amt;
			$employee_comp[$employee_id][$longe_diff_id][KEY_LESS_AMOUNT] = 0;
		}
		catch (Exception $e)
		{
			$message = $e->getMessage();
			RLog::error($message);
			
			throw $e;
		}

		RLog::info("END: compute_differential_longevity [$employee_id] [".$employee_comp[$employee_id][$longe_diff_id][KEY_AMOUNT]."]");
		return $employee_comp;
	}
	private function _compute_monthly_longe_diff($employee_id, $start_date, $end_date)
	{
		try
		{
			$longe_history = $this->payroll_process_model->get_emp_longe_pay_history($employee_id,$start_date);

			$active_month  = date('Ym',strtotime($start_date));
			$end_month     = date('Ym',strtotime($end_date));
			$active_date   = $start_date;

			$longe_months  = array();
			$ctr           = 0;
			while ( $active_month <= $end_month) {
				$ctr++;
				$month            = date('n',strtotime($active_date) );
				$year             = date('Y',strtotime($active_date) );
				$month_start_date = ($ctr == 1) ? date('Y-m-d',strtotime($active_date)) : date('Y-m',strtotime($active_date)).'-01';
				$month_end_date   = ($active_month == $end_month) ? date('Y-m-d',strtotime($end_date)) : date('Y-m-t',strtotime($active_date));
				$longe_months[$year][$month] = $this->_compute_monthly_longe($month_start_date, $month_end_date,$longe_history);

				$active_date  = date('Y-m-d',strtotime('+1 month' , strtotime ( $active_date ) ) );	
				$active_month = date('Ym',strtotime($active_date) );	
			}
			return $longe_months;
		}
		catch (Exception $e)
		{
			$message = $e->getMessage();
			RLog::error($message);
			
			throw $e;
		}
	}
	private function _compute_monthly_longe($start_date, $end_date,$longe_history)
	{
		try
		{
			$amount = 0;
			
			foreach ($longe_history as $key => $value) {

				if($value['start_date'] <= $start_date and $value['end_date'] >= $start_date  and $value['start_date'] <= $end_date and $value['end_date'] >= $end_date)
				{
					$amount = $value['amount'];
				}
				else{
					if($value['start_date'] <= $start_date and $value['end_date'] >= $start_date)
					{
						$month_days = date('t',strtotime($start_date));
						$start_day = date('d',strtotime($value['start_date'])) - 1;
						$amount += ($value['amount'] / $month_days) * $start_day;
					}
					if($value['start_date'] <= $end_date and $value['end_date'] >= $end_date)
					{
						$month_days = date('t',strtotime($end_date));
						$start_day = date('d',strtotime($value['start_date']));
						$no_days = ($month_days - $start_day) + 1;
						$amount += ($value['amount'] / $month_days) * $no_days;
					}
				}
			}
			return $amount;
		}
		catch (Exception $e)
		{
			$message = $e->getMessage();
			RLog::error($message);
			
			throw $e;
		}
	}

	private function _insert_employee_loyalty($employee_id, $milestone_year, $last_emp_work_id, $anniv_emp_date, $award_year, $payout_summary_id, $deferred_flag, $grant_deferred_flag=FALSE)
	{
		try
		{
			if ($grant_deferred_flag AND empty($award_year))
				throw new Exception('Award Year: ' . $this->lang->line('param_not_defined'));
			
			// insert into employee_loyalty_cash_awards table
			$table = $this->common_model->tbl_employee_loyalty_cash_awards;
			$fields = array();
			$fields['employee_id'] 					= $employee_id;
			$fields['milestone_year'] 				= $milestone_year;
			$fields['employee_work_experience_id'] 	= $last_emp_work_id;
			$fields['effective_date'] 				= $anniv_emp_date;
			$fields['payroll_summary_id'] 			= $payout_summary_id;
			

			if (!$deferred_flag)
				$fields['award_year'] 	= $award_year;

			if ($deferred_flag && ! $grant_deferred_flag)
			{
				$next_year 		= intval($award_year + 1);
				$next_year_dte	= "$next_year-01-01";
				$fields['deferred_up_to_date'] = $next_year_dte;
			}
			if ($grant_deferred_flag)
			{
				$fields['award_year'] 	= $award_year;
			}
			unset($dt);
			
			$this->common_model->insert_general_data($table, $fields, FALSE, TRUE, FALSE);
		}
		catch (Exception $e)
		{
			$message = $e->getMessage();
			RLog::error($message);
			
			throw $e;
		}
	}	
	
	
	/*
	 * Returns amount multiplied by frequency
	 * @param $compensation array with keys 'frequency_id' (not null), 'amount' (not null), 'employee_id' (nullable),  
	 * 							'tenure_rqmt_flag' (nullable), 'tenure_rqmt_val' (nullable)
	 * @param $attendance_count array with keys ATTENDANCE_STATUS_ABSENT, ATTENDANCE_STATUS_REGULAR_DAY, ATTENDANCE_STATUS_LEAVE_WP,  
	 * 											ATTENDANCE_STATUS_LEAVE_WOP, ATTENDANCE_STATUS_LEAVE_HD_WOP, PARAM_ATTENDANCE_STAT_WITH_TENURE
	 * @return $amount_arr array with keys 'amount' for the compensation amount and 'less_amount' for attendance deductions and 'orig_amount' for original amount   
	 */
	public function compute_amount_with_frequency($compensation, $payout_date, $use_param_dates, $param_dates=array(), 
			$attendance_count=array(), $attendance_period_hdr_id=NULL, $use_attendance_count=FALSE)
	{
		//RLog::error("==compute_amount_with_frequency use attendance count [$use_attendance_count]==");
		//RLog::error($attendance_count);
		
		$compensation_freq = isset($compensation['frequency_id']) ? $compensation['frequency_id'] : NULL;
		if (empty($compensation_freq))
			throw new Exception('FREQUENCY ID: ' . $this->lang->line('sys_param_not_defined'));
		$amount			= isset($compensation['amount']) ? $compensation['amount'] : NULL;
		$orig_amount	= $amount;
		$net_amount		= $amount;
		$less_amount	= 0.00;
		
		$working_hours	= WORKING_HOURS_DAY;
		$working_days	= WORKING_DAYS_MONTH;
		$months_in_year = MONTH_YEAR;
		$quarter 		= QUARTER_YEAR;
		$annual 		= ANNUAL_YEAR;
		
		$leave_absent_days	= 0;
		
		$remaining_months = 1;
		
		// if based on days present
		$tenure_rqmt_flag 	= isset($compensation['tenure_rqmt_flag']) ? $compensation['tenure_rqmt_flag'] : '';
		$tenure_rqmt_val 	= isset($compensation['tenure_rqmt_val']) ? $compensation['tenure_rqmt_val'] : 0;
		$project_amt_flag	= isset($compensation['project_amount']) ? $compensation['project_amount'] : NO;
		if ( $tenure_rqmt_flag == TENURE_RQMT_DAYS_PRESENT )
		{
			if ( !empty($attendance_count))
			{
				// if requirement is days present, number of working days should be based on the payout date
				//$working_days		= $attendance_count[PARAM_ATTENDANCE_STAT_WITH_TENURE];
				if ($use_attendance_count)
				{
					$working_days		= $attendance_count[PARAM_ATTENDANCE_STAT_WITH_TENURE];
				}
				else
				{
					$working_days 		= $this->compute_working_days_in_month($payout_date, $payout_date, FALSE, FALSE);
				}
				/*
				$leave_absent_days	= $attendance_count[ATTENDANCE_STATUS_ABSENT] + $attendance_count[ATTENDANCE_STATUS_LEAVE_WOP] 
										+ $attendance_count[ATTENDANCE_STATUS_LEAVE_WP];
				*/
				$leave_absent_days	= $attendance_count[KEY_SUBS_DEDUCT_COUNT];
			}
			else
			{
				$working_days = 0;
			}
		}
		else if ($use_param_dates) // assumption: effective date is always the first of the month
		{
			if ( ! isset($param_dates['compensation_start_date']) && ! isset($param_dates['compensation_end_date']))
				throw new Exception($this->lang->line('param_dates_not_defined'));
			
			$p_start_date	= $param_dates['compensation_start_date'];
			$p_end_date		= $param_dates['compensation_end_date'];
			$dt 			= new DateTime($p_start_date);
			$start_month	= $dt->format('n');
	
			$dt 			= new DateTime($p_end_date);
			$end_month		= $dt->format('n');
			
			//RLog::error("USE PARAM DATES [$project_amt_flag] [$p_start_date][$start_month] [$p_end_date][$end_month]");
			
			$remaining_months 	= ($end_month - $start_month) + 1;
		}
		else if ($project_amt_flag == YES AND $compensation_freq == FREQUENCY_DAILY)
		{
			// if projection of annual value and DAYS PRESENT, use base month 
			$working_days 	= $this->compute_working_days_in_month($payout_date, $payout_date, FALSE, FALSE);
			
			$dt 				= new DateTime($payout_date);
			$month				= $dt->format('n');
			$remaining_months 	= ($months_in_year - $month);
		}
		else
		{
			$dt 			= new DateTime($payout_date);
			$month			= $dt->format('n');
			$remaining_months = ($months_in_year - $month) + 1;
		}
		
		//RLog::error('compute_amount_with_frequency: ['.$compensation['compensation_id'].'] ' . $compensation_freq . '['.$remaining_months.'] ['.$working_days.'] ['.$leave_absent_days.']' );
		
		switch($compensation_freq)
		{
			case FREQUENCY_DAILY: //daily
				/*
				if ($compensation['compensation_id'] == 15)
					RLog::error("1 FREQUENCY DAILY [$working_days] [$remaining_months] [$leave_absent_days]");
				*/
				$orig_amount	= $amount * $working_days * $remaining_months;
				$less_amount 	= $amount * $leave_absent_days * $remaining_months;
				$net_amount 	= $orig_amount - $less_amount;
				$net_amount		= ($net_amount < 0 ? 0 : $net_amount);

				break;
			case FREQUENCY_MONTHLY: //monthly
				$net_amount 	= $amount * $remaining_months;

				// FOR PERA
				$less_amount	= 0;
				if ( $tenure_rqmt_flag == TENURE_RQMT_DEDUCT_LWOP )
				{
					$lwop_days		= $attendance_count[ATTENDANCE_STATUS_LEAVE_WOP];
					$lwop_days		+= $attendance_count[ATTENDANCE_STATUS_ABSENT];
					$peramt			= ROUND($orig_amount/22, 4);
					$less_amount	= ROUND(($peramt * $lwop_days), 2);
					$net_amount		= $orig_amount - $less_amount;
					
					//RLog::error("TENURE_RQMT_DEDUCT_LWOP [$lwop_days] [$peramt] [$less_amount] [$net_amount]");
				}
				
				break;
			default: // annual or one time
				$net_amount 	= $amount;
				break;					
		}
		/*
		if($compensation['compensation_id'] == 25 AND $project_amt_flag == YES)
			RLog::error("1: PROJECT DAYS PRESENT PAYOUT DATE: [$payout_date] [$working_days] [$remaining_months] [$amount] [$net_amount]");		
		*/
		$amount_arr = array(KEY_AMOUNT => 0.00, KEY_LESS_AMOUNT => 0.00, KEY_ORIG_AMOUNT => 0.00);
		$amount_arr[KEY_AMOUNT] 		= $net_amount;
		$amount_arr[KEY_LESS_AMOUNT] 	= $less_amount;
		$amount_arr[KEY_ORIG_AMOUNT] 	= $orig_amount;
		return $amount_arr;
	}

	/**
	 * This helper function handles the computation for compensation with variable amount.
	 * @param array $compensation Contains employee compensation record
	 * @param covered_date Date covered
	 * @return variable amount
	 */
	public function compute_variable_compensation_type($compensation, $covered_date=NULL, $attendance_count=array())
	{
		RLog::info('START GP: compute_variable_compensation_type ['.$covered_date.']');
		
		$amount = 0.00;
		
		try
		{
			$employee_id = $compensation['employee_id'];
			
			// START: check if with tenure requirement
			$tenure_rqmt_flag 	= isset($compensation['tenure_rqmt_flag']) ? $compensation['tenure_rqmt_flag'] : '';
			$tenure_rqmt_val 	= isset($compensation['tenure_rqmt_val']) ? $compensation['tenure_rqmt_val'] : 0;
			$leave_absent_days	= 0;
			if ( $tenure_rqmt_flag == TENURE_RQMT_DAYS_PRESENT AND ! empty($attendance_count) )
			{
				$leave_absent_days	= $attendance_count[KEY_SUBS_DEDUCT_COUNT];
				//RLog::error("compute_variable_compensation_type [$tenure_rqmt_flag] [$tenure_rqmt_val] [$leave_absent_days]");
				
				if ($tenure_rqmt_val <= $leave_absent_days)
					return 0.00;
			}
			// END: check if with tenure requirement
			
			$compensation_type_id = $compensation['compensation_id'];
			$multiplier_id        = $compensation['multiplier_id'];
			$multiplier_rate      = $compensation['rate'];
			$multiplier_rate      = (($multiplier_rate == NULL ? 0.00 : $multiplier_rate) / ONE_HUNDRED);
			
			// get amount
			$amount = 0.00;
			switch ($multiplier_id) {
				case MULTIPLIER_BASIC_SALARY:
					$amount = $compensation['employ_monthly_salary'];
					break;
			}
			
			$amount = round(($amount * $multiplier_rate), 2);
		}
		catch (Exception $e)
		{
			$message = $e->getMessage();
			RLog::error($message);
			
			throw $e;
		}
		
		RLog::info("END GP: _compute_variable_compensation_type [$compensation_type_id] [$employee_id] [$amount]");
		
		return $amount;
	}	
	
	/*
	 * @param array		Compensation record
	 * @param double	Amount
	 * @param integer 	Tenure number, either be in months or in days (used if prorate is computed using tenure)
	 * @param integer	Salary Grade (used if prorate is computed using salary grade)
	 * @param array		Pro-rated records
	 * 
	 */
	public function compute_prorated_value($compensation_type, $amount, $tenure_num, $salary_grade, $prorated_rates=array(), $separated_flag=FALSE)
	{
		RLog::info("Payroll_common.compute_prorated_value [{$compensation_type['compensation_id']}] [$amount] [$tenure_num] [$salary_grade] [$separated_flag]");
		
		$orig_amount	= $amount;
		$net_amount  	= $amount;
		
		switch ($compensation_type['pro_rated_flag'])
		{
			case PRORATE_TENURE:
				$tenure_rqmt_val	= $compensation_type['tenure_rqmt_val'];

				RLog::info("1 F: TENURE RQMT vs EMPLOYEE TENURE [$tenure_rqmt_val] [$tenure_num]");
				if ($tenure_rqmt_val == 0)
					break;
			
				if ($separated_flag OR $tenure_num < $tenure_rqmt_val)
					$net_amount = $this->_get_prorated_amount($amount, $tenure_num, $prorated_rates);
				
				break;
				
			case PRORATE_SALARY_GRADE:
				RLog::info("2 F: EMPLOYEE SALARY GRADE [$salary_grade]");
				$net_amount = $this->_get_prorated_amount($amount, $salary_grade, $prorated_rates);
				$orig_amount = $net_amount;
				break;
				
			case PRORATE_DAY:
				RLog::info("3 F: EMPLOYEE DAY [$tenure_num]");
				$net_amount = $this->_get_prorated_amount($amount, $tenure_num, $prorated_rates);
				break;
				
			case PRORATE_NA:
				break;
				
			default:
				throw new Exception(sprintf($this->lang->line('invalid_data'), 'Pro-rate Flag'));
				break;
		}
		
		$amount_arr = array(KEY_AMOUNT => 0.00, KEY_LESS_AMOUNT => 0.00, KEY_ORIG_AMOUNT => 0.00);
		$amount_arr[KEY_AMOUNT] 		= $net_amount;
		$amount_arr[KEY_LESS_AMOUNT] 	= $orig_amount - $net_amount;
		$amount_arr[KEY_ORIG_AMOUNT] 	= $orig_amount;
		return $amount_arr;
	}
	
	private function _get_prorated_amount($amount, $var, $prorated_rates)
	{
		try
		{
			foreach ($prorated_rates as $rate)
			{
				$from 	= $rate['from_val'];
				$to 	= $rate['to_val'];
				
				if(is_between_num($var, $from, $to))
				{
					$amount =  $amount * $rate['percentage'];
					break;
				}
			}
		}
		catch (Exception $e)
		{
			RLog::error($e->getMessage());
			throw $e;
		}
		
		return $amount;
	}

	
	
	public function compute_gsis($date_to, $params, $gsis_table, $use_basic_amount=FALSE)
	{
		if ( empty($date_to) OR empty($params['employee_id']))
			throw new Exception('GSIS: ' . $this->lang->line('param_not_defined'));
		if ($use_basic_amount AND ! isset($params['employ_monthly_salary']))
			throw new Exception('Basic Salary: ' . $this->lang->line('param_not_defined'));
		else if (! $use_basic_amount AND ! isset($params['salary_paid']))
		{
			RLog::error("Actual Salary [{$params['employee_id']}] [{$params['salary_paid']}] " . $this->lang->line('param_not_defined'));
			throw new Exception('Actual Salary: ' . $this->lang->line('param_not_defined'));
		}
		
			
		$share_amounts[KEY_PERSONAL_SHARE]	= 0.00;
		$share_amounts[KEY_GOVERNMENT_SHARE]= 0.00;
		
		if ($use_basic_amount)
			$monthly_salary = $params['employ_monthly_salary'];
		else
			$monthly_salary = $params['salary_paid'];
		
		//RLog::info("compute_gsis: [{$params['employee_id']}] [{$params['salary_paid']}] [{$params['employ_monthly_salary']}]");
		
		// Regular
		$r_personal_share = $monthly_salary * $gsis_table['r_personal_share'];
		$r_govt_share = $monthly_salary * $gsis_table['r_govt_share'];
		if ( ! empty($gsis_table['r_max_govt_share']) && $gsis_table['r_max_govt_share'] > 0.00 )
			$r_govt_share = ( $r_govt_share > $gsis_table['r_max_govt_share'] ? $gsis_table['r_max_govt_share'] : $r_govt_share );
		
		// Employees Compensation Fund
		$ecf_personal_share = $monthly_salary * $gsis_table['ecf_personal_share'];
		$ecf_govt_share = $monthly_salary * $gsis_table['ecf_govt_share'];
		if ( ! empty($gsis_table['ecf_max_govt_share']) && $gsis_table['ecf_max_govt_share'] > 0.00 )
			$ecf_govt_share = ( $ecf_govt_share > $gsis_table['ecf_max_govt_share'] ? $gsis_table['ecf_max_govt_share'] : $ecf_govt_share );
		
		//$share_amounts[KEY_PERSONAL_SHARE] = round( ($r_personal_share + $ecf_personal_share), 2, PHP_ROUND_HALF_UP);
		$share_amounts[KEY_PERSONAL_SHARE]	= round( $r_personal_share, 2, PHP_ROUND_HALF_UP);
		$share_amounts[KEY_PERSONAL_ECF] 	= round( $ecf_personal_share, 2, PHP_ROUND_HALF_UP);
		$share_amounts[KEY_GOVERNMENT_SHARE]= round( $r_govt_share, 2, PHP_ROUND_HALF_UP);
		$share_amounts[KEY_GOVERNMENT_ECF] 	= round( $ecf_govt_share, 2, PHP_ROUND_HALF_UP);
		
		return $share_amounts;
	}

	public function compute_pagibig($date_to, $params, $pagibig_table)
	{
		if ( empty($date_to) OR empty($params['employee_id']) OR (! isset($params['employ_monthly_salary'])) )
			throw new Exception('PAGIBIG: ' . $this->lang->line('param_not_defined'));
		
		$share_amounts[KEY_PERSONAL_SHARE] = 0.00;
		$share_amounts[KEY_GOVERNMENT_SHARE] = 0.00;

		$monthly_salary = $params['employ_monthly_salary'];
		foreach ($pagibig_table as $table)
		{
			if (is_between_num($monthly_salary, $table['salary_range_from'], $table['salary_range_to']))
			{
				$max_salary_base= ($monthly_salary > $table['max_salary_range'] ? $table['max_salary_range'] : $monthly_salary);
				$employee_share	= $table['employee_rate'];
				$employer_share	= $table['employer_rate'];
				
				$share_amounts[KEY_PERSONAL_SHARE]		= round( ($max_salary_base * $employee_share), 2, PHP_ROUND_HALF_UP);
				$share_amounts[KEY_GOVERNMENT_SHARE]	= round( ($max_salary_base * $employer_share), 2, PHP_ROUND_HALF_UP);
				
				RLog::info("_compute_pagibig [".$params['employee_id']."][$max_salary_base][$employee_share][$employer_share] [".$share_amounts[KEY_PERSONAL_SHARE]."]");
				
				break;
			}
		}
		
		return $share_amounts;
		
	}
	
	public function compute_philhealth($date_to, $params, $philhealth_table)
	{
		if ( empty($date_to) OR empty($params['employee_id']) OR ( ! isset($params['employ_monthly_salary'])) )
			throw new Exception('PHILHEALTH: ' . $this->lang->line('param_not_defined'));
		
		$share_amounts[KEY_PERSONAL_SHARE] = 0.00;
		$share_amounts[KEY_GOVERNMENT_SHARE] = 0.00;

		$monthly_salary = $params['employ_monthly_salary'];
		foreach ($philhealth_table as $table)
		{
			if (is_between_num($monthly_salary, $table['salary_range_from'], $table['salary_range_to']))
			{
				$max_salary_base= $table['salary_base'];
				$employee_share	= $table['employee_share'];
				$employer_share	= $table['employer_share'];
				
				$share_amounts[KEY_PERSONAL_SHARE] = $employee_share;
				$share_amounts[KEY_GOVERNMENT_SHARE] = $employer_share;
				
				break;
			}
		}
		
		return $share_amounts;
	}
	
	public function compute_sss($date_to, $params, $sss_table)
	{
		if ( empty($date_to) OR empty($params['employee_id']) OR ( ! isset($params['employ_monthly_salary'])) )
			throw new Exception('SSS: ' . $this->lang->line('param_not_defined'));
		
		$share_amounts[KEY_PERSONAL_SHARE] = 0.00;
		$share_amounts[KEY_GOVERNMENT_SHARE] = 0.00;

		$monthly_salary = $params['employ_monthly_salary'];
		foreach ($sss_table as $table)
		{
			if (is_between_num($monthly_salary, $table['salary_range_from'], $table['salary_range_to']))
			{
				$max_salary_base= $table['salary_base'];
				$employee_share	= $table['employee_share'];
				$employer_share	= $table['employer_share'];
				
				$share_amounts[KEY_PERSONAL_SHARE] = $employee_share;
				$share_amounts[KEY_GOVERNMENT_SHARE] = $employer_share;
				
				break;
			}
		}
		
		return $share_amounts;
	}

	
	public function process_payroll_approval($payroll_summary, $payout_dates, $status_id, $gp_flag=YES)
	{
		try
		{
			$tax_table_flag 			= $payroll_summary['tax_table_flag'];
			if ($gp_flag == NO)
				$tax_table_flag 		= TAX_ANNUALIZED;
			$payroll_summary_id			= $payroll_summary['payroll_summary_id'];
			$attendance_period_hdr_id	= $payroll_summary['attendance_period_hdr_id']; 
			$payout_type_flag			= $payroll_summary['payout_type_flag'];
			$mwe_denominator			= $payroll_summary['mwe_denominator'];
			
			$payout_count 		= count($payout_dates);
			$payout_date_from	= $payout_dates[0];
			$payout_date_to		= $payout_dates[0];
			if ($gp_flag == YES)
				$payout_date_to = $payout_dates[$payout_count-1];
			else
			{
				$d = new DateTime($payout_date_from);
				$payout_date_to = $d->format('Y-m-t');
			}
			
			$field = array('payout_status_id');
			$table = $this->common_model->tbl_param_payout_status;
			$where = array('approved_flag' => 1, 'payout_flag' => 'A');
			$stat_approved = $this->common_model->get_general_data($field, $table, $where, FALSE);
			$stat_approved = (isset($stat_approved['payout_status_id']) ? $stat_approved['payout_status_id'] : NULL);
			RLog::info("-- process_payroll_approval [$tax_table_flag] [$mwe_denominator] [$payout_date_from] [$payroll_summary_id] [$attendance_period_hdr_id] [$status_id] [$stat_approved]");
			
			$new_period_status_id = ATTENDANCE_PERIOD_PROCESSED;
			if (isset($stat_approved) && $stat_approved === $status_id)
			{
				/*
				if ($gp_flag == YES AND $payout_type_flag == PAYOUT_TYPE_FLAG_REGULAR)
				{
					// UPDATE DEDUCTION PAID COUNT
					$this->payroll_process_model->update_deduction_paid_count($payroll_summary_id, NULL, $payout_date_to);
				}
				*/
				
				// RE-COMPUTE FORM 2316
				$this->form2316 = modules::load('main/payroll_form_2316');
				$this->form2316->construct_form_2316(
					$tax_table_flag, 
					$payout_date_to, 
					NULL, // unsaved deductions
					$payroll_summary_id, 
					NULL, // $included_employees 
					NULL, // $sys_param_stat_approved
					TRUE, // $save
					TRUE, // $project_tax
					FALSE, // $monthly_only
					$mwe_denominator // MWE denominator
					);
				
				$new_period_status_id = ATTENDANCE_PERIOD_COMPLETED;
			} 
			
			if ($gp_flag == YES)
			{
				// UPDATE ATTENDANCE PERIOD STATUS TO COMPLETED
				$this->payroll_process_model->update_attendance_period($attendance_period_hdr_id, $new_period_status_id);
			}
		} catch (Exception $e) {
			RLog::error($e->getMessage());
			throw $e;
		}
	}			
	

	public function get_gsis_table($date_to)
	{
		$return_table['r_personal_share'] = 0.00;
		$return_table['r_govt_share'] = 0.00;
		$return_table['r_max_govt_share'] = 0.00;
		$return_table['ecf_personal_share'] = 0.00;
		$return_table['ecf_govt_share'] = 0.00;
		$return_table['ecf_max_govt_share'] = 0.00;
		
		// select GSIS table
		$gsis_table = $this->payroll_process_model->get_gsis_table($date_to);
		
		$personal_share_pct = 0.00;
		$govt_share_pct = 0.00;
		$max_govt_share_amt = 0.00;
		
		$done_regular = FALSE;
		$done_ecf = FALSE;
		foreach ($gsis_table as $gsis)
		{
			if ($gsis['insurance_type_flag'] == GSIS_ECF)
			{
				$return_table['ecf_personal_share']	= $gsis[KEY_PERSONAL_SHARE];
				$return_table['ecf_govt_share'] 	= $gsis[KEY_GOVERNMENT_SHARE];
				$return_table['ecf_max_govt_share'] = $gsis[KEY_MAX_GOVERNMENT_SHARE];
				
				$done_ecf = TRUE;
			}
			else
			{
				$return_table['r_personal_share']	= $gsis[KEY_PERSONAL_SHARE];
				$return_table['r_govt_share'] 		= $gsis[KEY_GOVERNMENT_SHARE];
				$return_table['r_max_govt_share']	= $gsis[KEY_MAX_GOVERNMENT_SHARE];
				
				$done_regular = TRUE;
			}
			
			if ($done_regular && $done_ecf)
				break;
		}
		
		
		return $return_table;
	}
	
	public function get_pagibig_table($date_to)
	{
		$table = $this->payroll_process_model->get_pagibig_table($date_to);
		
		return $table;
	}

	public function get_philhealth_table($date_to)
	{
		$table = $this->payroll_process_model->get_philhealth_table($date_to);
		
		return $table;
	}

	public function get_sss_table($date_to)
	{
		$table = $this->payroll_process_model->get_sss_table($date_to);
		
		return $table;
	}
	
	/*
	 * compute working days given date range
	 */
	public function compute_working_days($date_from, $date_to, $include_holiday=FALSE, $include_rest_day=FALSE)
	{
		RLog::info("S: compute working days [$date_from] [$date_to] [$include_holiday] [$include_rest_day]");
		
	    $working_days = array(1, 2, 3, 4, 5); # date format = N (1 = Monday, ...)
	    if ($include_rest_day)
	    	$working_days = array(1, 2, 3, 4, 5, 6, 7);

		// get holidays between dates
		$holiday_days = array();
		if ( ! $include_holiday)
		{
			$fields = array('GROUP_CONCAT(holiday_date SEPARATOR \',\') as holidays');
			$table	= $this->common_model->tbl_param_work_calendar;
			$where 	= array();
			$where['holiday_date'] = array(array($date_from, $date_to), array('BETWEEN'));
			$holiday_days = $this->common_model->get_general_data($fields, $table, $where, FALSE);
			if (empty($holiday_days['holidays']))
				$holiday_days = array();
			else
				$holiday_days = explode(',', $holiday_days['holidays']);
		}
		
	    $from 	= new DateTime($date_from);
	    $to 	= new DateTime($date_to);
	    $to->modify('+1 day');
	    
	    $interval	= new DateInterval('P1D');
	    $periods	= new DatePeriod($from, $interval, $to);
	
	    $days = 0;
	    foreach ($periods as $period)
	    {
	        if (!in_array($period->format('N'), $working_days)) continue;
	        if (!$include_holiday && in_array($period->format('Y-m-d'), $holiday_days)) continue;
	        //if (!$include_holiday && in_array($period->format('*-m-d'), $holiday_days)) continue;
	        $days++;
	    }
	    
	    RLog::info("E: compute working days [$date_from] [$date_to] [$include_holiday] [$include_rest_day] [$days]");
	    
	    return $days;		
	}
	
	/*
	 * compute working days in a month
	 */
	public function compute_working_days_in_month($date_from, $date_to, $include_holiday=FALSE, $include_rest_day=FALSE)
	{
		RLog::info("S: compute_working_days_in_month [$date_from] [$date_to] [$include_holiday] [$include_rest_day]");
		
	    $from	= new DateTime($date_from);
	    $to		= new DateTime($date_to);

	    $date_from	= $from->format('Y-m-01');
	    $date_to	= $from->format('Y-m-t');
	    
	    RLog::info("E: compute_working_days_in_month [$date_from] [$date_to]");

	    return $this->compute_working_days($date_from, $date_to, $include_holiday, $include_rest_day);
	}
	
	/*
	 * compute working days in a month
	 */
	public function compute_working_days_by_period($date_from, $date_to, $include_holiday=FALSE, $include_rest_day=FALSE)
	{
		RLog::info("S: compute_working_days_by_period [$date_from] [$date_to] [$include_holiday] [$include_rest_day]");
		
	    $from	= new DateTime($date_from);
	    $to		= new DateTime($date_to);

	    $date_from	= $from->format('Y-m-d');
	    $date_to	= $to->format('Y-m-d');
	    
	    RLog::info("E: compute_working_days_by_period [$date_from] [$date_to]");

	    return $this->compute_working_days($date_from, $date_to, $include_holiday, $include_rest_day);
	}	
	
	/*
	 * NOTIFICATIONS
	 */
	public function insert_payout_notifications($module_id, $payroll_summary_id, $payout_status_id, $processed_by, $gp_flag=TRUE)
	{
		try
		{
			$params	= $this->payroll_process_model->get_notification_params($module_id, $payout_status_id);
			$this->load->model('notifications_model', 'notification');

			$notif_params				= array();
			$notif_params['module_id']	= $module_id;
			
			$notif_msg		= '';
			if ($params['approved_flag'] == 1)
			{
			 	$notif_msg = $this->lang->line('process_payroll_approved');
			 	$notif_params['notify_users'] = $processed_by;
			}
		 	else
		 	{
				$notif_msg = $this->lang->line('process_payroll_notif');
				$notif_params['notify_roles']	= $params['notify_roles'];
				$notif_params['notified_by']	= $processed_by;
		 	}
		 	$title = '';
			switch ($module_id){
				case MODULE_PAYROLL_GENERAL_PAYROLL:
					$title = SUB_MENU_GENERAL_PAYROLL;
					break;
				case MODULE_PAYROLL_SPECIAL_PAYROLL: 
					$title = SUB_MENU_SPECIAL_PAYROLL;
					break;
				default: 
					$title = SUB_MENU_PAYROLL;
					break;
			}
		 	$notif_params['notification'] 	= $notif_msg;
		 	$notif_params['title']			= $title;
		 	
		 	// construct record_link
			$salt			= gen_salt();
			$id 			= $this->hash($payroll_summary_id);
			$token_process 	= in_salt($id  . '/' . ACTION_PROCESS  . '/' . $module_id, $salt);
			
			if ($gp_flag)
				$url_process 	= "main/payroll/display_payroll_process/".ACTION_PROCESS."/".$id ."/".$token_process."/".$salt."/".$module_id;
			else
				$url_process 	= "main/special_payroll/display_special_payroll_process/".ACTION_PROCESS."/".$id ."/".$token_process."/".$salt."/".$module_id;

			$notif_params['record_link']	= $url_process;
			
			$this->notification->insert_notification($notif_params);
		}
		catch(Exception $e)
		{
			RLog::error($e->getMessage());
			throw($e);
		}
	}
	
	/*
	 * AUDIT TRAIL
	 */
	public function log_audit_trail($activity, $audit_module, $prev_detail, $curr_detail, $audit_action, $audit_table, $audit_schema)
	{
		try
		{
			// LOG AUDIT TRAIL
			$this->audit_trail->log_audit_trail(
				$activity, 
				$audit_module, 
				$prev_detail, 
				$curr_detail, 
				$audit_action, 
				$audit_table,
				$audit_schema
			);
		}
		catch(Exception $e)
		{
			Main_Model::rollback();
			RLog::error($e->getMessage());
			
			throw($e);
		}
	}	
	
}
	
	
/* End of file Payroll_common.php */
/* Location: ./application/modules/main/controllers/Payroll_common.php */