<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Payroll_form_2316 extends Main_Model {

	private $permission_module = MODULE_PAYROLL_GENERAL_PAYROLL;
	private $db_core   = DB_CORE;
	private $payroll_common;
	
	public function __construct() {
		parent:: __construct();

		$this->load->model('payroll_tax_model', 'payroll_tax');
		$this->load->model('payroll_process_model', 'payroll_proc');
		$this->load->model('common_model', 'common');
		
		$this->permission_module = MODULE_PAYROLL_GENERAL_PAYROLL;
		
		$this->payroll_common = modules::load('main/payroll_common');
	}
	
	public function compute_withholding_tax($tax_table_flag, $payout_date, $payroll_summary_id, $param_data, $employees=NULL)
	{
		RLog::info("Payroll_form_2316.compute_withholding_tax [$payout_date][$payroll_summary_id]");
		
		$dt_pay_obj = new DateTime($payout_date);
		$pay_year	= $dt_pay_obj->format('Y');
		$pay_month	= ((int) $dt_pay_obj->format('m'));
		
		$deductions_dtl = array();
		
		try
		{
			$tax_details 		= $this->payroll_tax->get_tax_details($payroll_summary_id, $pay_year, $pay_month, $employees);

			// get BIR code
			$sys_param_type		= array(PARAM_DEDUCTION_ST_BIR);
			$sys_param_values	= $this->common->get_sys_param_value($sys_param_type, FALSE);
			$sys_param_bir 		= (isset($sys_param_values['sys_param_value']) ? $sys_param_values['sys_param_value'] : NULL);
			
			// get BIR PK
			$sys_param_values	= $this->common->get_general_data(array('deduction_id'), 
									$this->common->tbl_param_deductions, 
									array('deduction_code' => $sys_param_bir), 
									FALSE);
			$sys_param_bir_id	= $sys_param_values['deduction_id'];
			
			$remaining_months = 1;
			if ($tax_table_flag == TAX_ANNUALIZED) // divide by remaining months if annualized
				$remaining_months = WORKING_MONTHS - $pay_month + 1;

			//RLog::info("Before tax loop: [$remaining_months][$pay_month]");
			foreach ($tax_details as $tax)
			{
				$annual_wt_amount = $tax['tax_due'] - $tax['total_tax_withheld'];
				$monthly_wt_amount = round( ($annual_wt_amount/$remaining_months), 2, PHP_ROUND_HALF_UP);
				if ($monthly_wt_amount < 0)
					$monthly_wt_amount = 0;
				
				//RLog::info("monthly_wt_amount [".$tax['employee_id']."] [$remaining_months] [".$tax['tax_due']."] [$monthly_wt_amount]");
				
				// update form_2316_details tax withheld
				$this->payroll_tax->update_form_2316_tax_withheld($tax['form_2316_id'], $pay_year, $pay_month, $monthly_wt_amount);

				$deductions_dtl[$tax[KEY_PAYROLL_HDR_ID]][]	=	array(
					KEY_EMPLOYEE_ID			=> $tax['employee_id'],
					KEY_PAYROLL_HDR_ID 		=> $tax['payroll_hdr_id'],
					KEY_DEDUCTION_ID 		=> $sys_param_bir_id,
					KEY_RAW_DEDUCTION_ID	=> $sys_param_bir_id,
					KEY_EFFECTIVE_DATE 		=> '',
					KEY_AMOUNT				=> $monthly_wt_amount,
					KEY_EMPLOYER_AMOUNT		=> NULL,
					KEY_REFERENCE_TEXT		=> NULL,
					KEY_PRIORITY_NUM		=> 0
				);

			}			
			// END: Get deduction distribution
		}
		catch (Exception $e)
		{
			RLog::error($e->getMessage());
			throw $e;
		}

		return $deductions_dtl;
	}
	
	/** 
	* This function constructs BIR Form 2316 (either to compute for net taxable income OR insert to form_2316_* table. 
	* 
	* @access public
	* @param $tax_table_flag 			ANNUALIZED or MONTHLY 2316
	* @param $payout_date 				Payroll payout date (from)
	* @param $payroll_summary_id 		Payroll summary ID
	* @param $included_employees 		Array of Employee IDs 
	* @param $sys_param_stat_approved	Status code of payout (for filtering of actual payout; NULL if no filter) 
	* @param $save 						TRUE if result data is to be saved to form_2316_* tables; FALSE if return result without saving
	* @param $project_tax				TRUE if computation of unpaid compensations should be projected
	* @param $monthly_only				TRUE if result data is to be saved on form_2316_monthly_details (and form_2316_header) only
	* @return mixed 					boolean if $save is TRUE OR array if $save is FALSE [ array('<emp_id>' => array(<net_tax_income>, <tax_due>, <tax_withheld>)) ] 
	*/
	public function construct_form_2316($tax_table_flag, $payout_date, $unsaved_deductions=NULL, $payroll_summary_id=NULL, $included_employees=array(), 
			$sys_param_stat_approved=NULL, $save=FALSE, $project_tax=FALSE, $monthly_only=FALSE, $mwe_denominator=0.00, $separated_flag=NO)
	{
		$dt_pay_obj	= new DateTime($payout_date);
		$year		= $dt_pay_obj->format('Y');
		$pay_month	= ((int) $dt_pay_obj->format('m'));
		
		RLog::info("construct_form_2316 [$tax_table_flag] [$mwe_denominator] [$payout_date] [$year] [$pay_month] [$payroll_summary_id] [$sys_param_stat_approved] [$save] [$project_tax] [$monthly_only] [$mwe_denominator]");		
		RLog::info($included_employees);
		
		try
		{
			$sys_params = $this->payroll_proc->get_sys_gen_params();
			$sys_param_union_dues_id	= $sys_params[PARAM_DEDUCTION_ID_CNA];
			
			// get unsaved stat deductions
			$employee_unsaved_stat_union = $this->_get_unsaved_stat_deductions($unsaved_deductions, $sys_param_union_dues_id);
			$employee_unsaved_deduct = isset($employee_unsaved_stat_union['unsaved_stat']) ? $employee_unsaved_stat_union['unsaved_stat'] : NULL;
			$employee_unsaved_union  = isset($employee_unsaved_stat_union['unsaved_union']) ? $employee_unsaved_stat_union['unsaved_union'] : NULL;
			//RLog::error("===UNSAVED DEDUCTIONS===");
			//RLog::error($employee_unsaved_stat_union);
			
			// get BIR code
			$sys_param_type		= array(PARAM_DEDUCTION_ST_BIR);
			$sys_param_values	= $this->common->get_sys_param_value($sys_param_type, FALSE);
			$sys_param_bir 		= (isset($sys_param_values['sys_param_value']) ? $sys_param_values['sys_param_value'] : NULL);
			
			// get BIR PK
			$sys_param_values	= $this->common->get_general_data(array('deduction_id'), $this->common->tbl_param_deductions, array('deduction_code' => $sys_param_bir), FALSE);
			$sys_param_bir_id	= $sys_param_values['deduction_id'];
			
			$employees 	= array();
			$employee_pay_hdr = array();
			
			$employment_type_tenure = $this->payroll_proc->get_doh_employ_type();
			if (empty($employment_type_tenure))
				throw new Exception('DOH Employment Type: ' . $this->lang->line('param_not_defined'));
				
			$emp_data = array();
			if ( ! is_null($payroll_summary_id) && isset($sys_param_bir))
				$emp_data = $this->payroll_tax->get_emp_pay_info_by_payout($payroll_summary_id, $employment_type_tenure, $included_employees);
			else
			{
				$employees = $included_employees;
				$emp_data = $this->payroll_tax->get_emp_pay_info_by_employee($employees, $employment_type_tenure, $payout_date, $separated_flag);
			}
			
			foreach ($emp_data as $emp)
			{
				if ( ! is_null($payroll_summary_id) && isset($sys_param_bir))
					$employees[] = $emp['employee_id'];
					
				$employee_pay_hdr[$emp['employee_id']][0] = (isset($emp['salary_grade']) ? $emp['salary_grade'] : NULL); 
				$employee_pay_hdr[$emp['employee_id']][1] = (isset($emp['pay_step']) ? $emp['pay_step'] : NULL);
				$employee_pay_hdr[$emp['employee_id']][2] = (isset($emp['anniv_emp_date']) ? $emp['anniv_emp_date'] : NULL);
				$employee_pay_hdr[$emp['employee_id']][3] = (isset($emp['payroll_hdr_id']) ? $emp['payroll_hdr_id'] : NULL);
				$employee_pay_hdr[$emp['employee_id']][4] = (isset($emp['basic_amount']) ? $emp['basic_amount'] : NULL);
			}
			//RLog::info('== debug emp data ===');
			//RLog::info($employees);
			
			if (empty($employees))
				return array(); //no selected employees return an empty array (form_2316_ids)
			
			$payout_details = array();
			
			// insert to form_2316
			$actual_payouts = $this->payroll_tax->get_actual_payout($payout_date, $employees, $sys_param_stat_approved, $separated_flag);
			
			// original
			//$sys_params = $this->payroll_proc->get_sys_gen_params();
			
			// get union dues amount
			$sys_param_union_dues_id	= $sys_params[PARAM_DEDUCTION_ID_CNA];
			if ( ! empty($sys_param_union_dues_id))
			{
				$field		= array('amount');
				$table      = $this->common->tbl_param_deductions;
				$where      = array('deduction_id' => $sys_param_union_dues_id);
				$amount_union_dues	= $this->common->get_general_data($field, $table, $where, FALSE);
				$amount_union_dues	= $amount_union_dues['amount'];
			}	
			
			//get taxable benefits if project_tax = TRUE
			$taxable_benefits 	= array();
			$prorated_rates 	= array();
			$stat_deduction_tables = array();
			$employee_taxable_benefits = array();
			if($project_tax)
			{
				$taxable_benefits = $this->payroll_tax->get_taxable_benefits($included_employees);
				
				foreach ($taxable_benefits as $key=>$emp_val)
				{
					$emp_id			= $emp_val['employee_id'];
					$comp_ids		= explode(',', $emp_val['compensation_ids']);
					$comp_codes		= explode(',', $emp_val['compensation_codes']);
					$comp_type_flags= explode(',', $emp_val['compensation_type_flags']);
					$amounts 		= explode(',', $emp_val['amounts']);
					$mult_ids 		= explode(',', $emp_val['multiplier_ids']);
					$rates 			= explode(',', $emp_val['rates']);
					$prorated_flags = explode(',', $emp_val['pro_rated_flags']);
					$freq_ids		= explode(',', $emp_val['frequency_ids']);
					$taxable_amounts= explode(',', $emp_val['taxable_amounts']);
					
					$emp_vals = array('compensation_ids'		=>	$comp_ids,
									'compensation_codes'		=>	$comp_codes,
									'compensation_type_flags'	=>	$comp_type_flags,
									'amounts'					=>	$amounts,
									'multiplier_ids'			=>	$mult_ids,
									'rates'						=>	$rates,
									'pro_rated_flags'			=>	$prorated_flags,
									'frequency_ids'				=>	$freq_ids,
									'taxable_amounts'			=>	$taxable_amounts);
					$employee_taxable_benefits[$emp_id] = $emp_vals;
				}
				
				// get prorated values
				if ($prorated_flags != PRORATE_NA)
				{
					$field		= array('compensation_id', 'from_val', 'to_val', 'percentage') ;
					$table      = $this->common->tbl_param_compensation_prorated;
					$where      = array();
					$order_by	= array('compensation_id' => 'ASC', 'from_val' => 'ASC'); 
					$values		= $this->common->get_general_data($field, $table, $where, TRUE, $order_by);
					
					foreach ($values as $key => $val)
					{
						$prorated_rates[$val['compensation_id']][] = $val;
					}
				}
				
				// statutory deduction table
				$gsis_table 		= $this->payroll_common->get_gsis_table($payout_date);
				$pagibig_table 		= $this->payroll_common->get_pagibig_table($payout_date);
				$philhealth_table 	= $this->payroll_common->get_philhealth_table($payout_date);
				
				$stat_deduction_tables['GSIS'] 		= $gsis_table;
				$stat_deduction_tables['PAGIBIG'] 	= $pagibig_table;
				$stat_deduction_tables['PHILHEALTH']= $philhealth_table;
			}

			// get mapping of compensation and form 2316 items
			$param_2316 = $this->common->get_general_data(array('*'), $this->common->tbl_param_form_2316, array());
			$param_2316_map = array();
			$form_2316 = array();
			
			$key_delim = '|';
			foreach($param_2316 as $p)
			{
				$key = $p['type'] . $key_delim . $p['item_code'];
				$param_2316_map[$key][] = $p['detail_id'];
			}

			// get sys_param
			$sys_param_type		= array(PARAM_COMPENSATION_BASIC_SALARY,  
								PARAM_AMOUNT_TAXABLE_BENEFIT_CAP, PARAM_AMOUNT_PERSONAL_EXEMPTION, 
								PARAM_AMOUNT_PERSONAL_EXEMPTION_DEPENDENT, PARAM_NUM_PERSONAL_EXEMPTION_DEPENDENT,
								PARAM_TAX_WITHHELD_PREV_EMPLOYER, PARAM_WORKING_MONTHS,
								PARAM_TAX_HUSBAND_EXCEPTION, PARAM_TAX_WIFE_EXCEPTION,
								PARAM_TAX_PREV_EMPLOYER_TIN, PARAM_TAX_PREV_EMPLOYER_NAME,
								PARAM_TAX_PREV_EMPLOYER_ADDR, PARAM_TAX_PREV_EMPLOYER_INCOME,
								PARAM_TAX_PREV_EMPLOYER_WITHHELD, PARAM_DEDUCTION_ST_BIR_ID,
								PARAM_COMPENSATION_ID_LONGEVITY_PAY);
			$sys_param_values = $this->common->get_sys_param_value($sys_param_type, TRUE);
			$sys_param_basic_salary = NULL;
			
			$sys_param_taxable_benefit_cap = NULL;
			$sys_param_personal_exempt = NULL;
			$sys_param_personal_exempt_dep = NULL;
			$sys_param_personal_exempt_dep_num = NULL;
			
			$sys_param_months_in_year = NULL;
			
			$sys_param_bir = NULL;
			$sys_param_husband_exception = NULL;
			$sys_param_wife_exception = NULL;
			$sys_param_prev_emp_tin = NULL;
			$sys_param_prev_emp_name = NULL;
			$sys_param_prev_emp_address = NULL; 
			$sys_param_prev_emp_income = NULL;
			$sys_param_prev_emp_tax = NULL;
			
			$sys_param_longe_comp_id = NULL;
			
			foreach ($sys_param_values as $value)
			{
				switch ($value['sys_param_type'])
				{
					case PARAM_COMPENSATION_BASIC_SALARY:
						$sys_param_basic_salary = $value['sys_param_value'];
						break;
					case PARAM_AMOUNT_TAXABLE_BENEFIT_CAP:
						$sys_param_taxable_benefit_cap = $value['sys_param_value'];
						break;
					case PARAM_AMOUNT_PERSONAL_EXEMPTION:
						$sys_param_personal_exempt = $value['sys_param_value'];
						break;
					case PARAM_AMOUNT_PERSONAL_EXEMPTION_DEPENDENT:
						$sys_param_personal_exempt_dep = $value['sys_param_value'];
						break;
					case PARAM_NUM_PERSONAL_EXEMPTION_DEPENDENT:
						$sys_param_personal_exempt_dep_num = $value['sys_param_value'];
						break;
					case PARAM_WORKING_MONTHS:
						$sys_param_months_in_year = $value['sys_param_value'];
						break;

					case PARAM_DEDUCTION_ST_BIR_ID:
						$sys_param_bir = $value['sys_param_value'];
						break;
					case PARAM_TAX_HUSBAND_EXCEPTION:
						$sys_param_husband_exception = $value['sys_param_value'];
						break;
					case PARAM_TAX_WIFE_EXCEPTION:
						$sys_param_wife_exception = $value['sys_param_value'];
						break;
					case PARAM_TAX_PREV_EMPLOYER_TIN:
						$sys_param_prev_emp_tin = $value['sys_param_value'];
						break;
					case PARAM_TAX_PREV_EMPLOYER_NAME:
						$sys_param_prev_emp_name = $value['sys_param_value'];
						break;
					case PARAM_TAX_PREV_EMPLOYER_ADDR:
						$sys_param_prev_emp_address = $value['sys_param_value']; 
						break;
					case PARAM_TAX_PREV_EMPLOYER_INCOME:
						$sys_param_prev_emp_income = $value['sys_param_value'];
						break;
					case PARAM_TAX_PREV_EMPLOYER_WITHHELD:
						$sys_param_prev_emp_tax = $value['sys_param_value'];
						break;
					case PARAM_COMPENSATION_ID_LONGEVITY_PAY:
						$sys_param_longe_comp_id = $value['sys_param_value'];
						break;						
				}
			}
			
			$tax_param_dependents = $this->_get_tax_param_dependents();
			$tax_dep_civil_status		= array();
			$tax_dep_employment_status	= array();
			$tax_dep_relation_type		= array();
			$tax_dep_age_limit			= 0;
			if( ! empty($tax_param_dependents))
			{
				$tax_dep_civil_status 		= (empty($tax_param_dependents[PARAM_TAX_DEPENDENT_CIVIL_STATUS]) ? $tax_dep_civil_status : $tax_param_dependents[PARAM_TAX_DEPENDENT_CIVIL_STATUS]);
				$tax_dep_employment_status 	= (empty($tax_param_dependents[PARAM_TAX_DEPENDENT_EMPLOYMENT_STATUS]) ? $tax_dep_employment_status : $tax_param_dependents[PARAM_TAX_DEPENDENT_EMPLOYMENT_STATUS]);
				$tax_dep_relation_type 		= (empty($tax_param_dependents[PARAM_TAX_DEPENDENT_RELATION_TYPE]) ? $tax_dep_relation_type : $tax_param_dependents[PARAM_TAX_DEPENDENT_RELATION_TYPE]);
				$tax_dep_age_limit 			= (empty($tax_param_dependents[PARAM_TAX_DEPENDENT_RELATION_TYPE]) ? 0 : $tax_param_dependents[PARAM_TAX_DEPENDENT_AGE_LIMIT]);
				
				$tax_dep_civil_status 		= explode(',' , $tax_dep_civil_status);
				$tax_dep_employment_status 	= explode(',' , $tax_dep_employment_status);
				$tax_dep_relation_type 		= explode(',' , $tax_dep_relation_type);
			}
			
			// check if husband or wife claims tax
			$employee_tax_claim_exempt	= $this->payroll_tax->get_employee_tax_exemption($sys_param_bir, $sys_param_husband_exception, $sys_param_wife_exception, $included_employees);
			
			$employ_tax_values			= array('claim_exempt', 'civil_status_id', 'gender_code');
			$employee_tax_claim_exempt	= set_key_value($employee_tax_claim_exempt, 'employee_id', $employ_tax_values, TRUE);
			//RLog::error($employee_tax_claim_exempt);
			
			// throw exception if at least one of the variables is not defined
			if( ! isset($sys_param_basic_salary) OR ! isset($sys_param_bir) 
					OR ! isset($sys_param_taxable_benefit_cap) OR ! isset($sys_param_personal_exempt) 
					OR ! isset($sys_param_personal_exempt_dep) OR ! isset($sys_param_personal_exempt_dep_num) 
					OR ! isset($sys_param_months_in_year) 
					OR empty($tax_dep_civil_status) OR empty($tax_dep_employment_status)
					OR empty($tax_dep_relation_type) OR empty($tax_dep_age_limit) )
				throw new Exception('Form 2316: ' . $this->lang->line('sys_param_not_defined'));

			$cnt = 0;
			$form_2316_ids = array();
			$prev_employee_id = 0;
			$save_data = FALSE;
			
			// employees' basic salary payout
			$employee_salary_payout = array();
			$employee_actual_payout = array();
			//$employee_actual_longe	= array();
			foreach ($actual_payouts AS $dtl)
			{
				$save_data 	 = TRUE;
				
				$employee_id = $dtl['employee_id'];

				$type 	= (isset($dtl['compensation_id']) && $dtl['compensation_id'] > 0) ? PAYOUT_DETAIL_TYPE_COMPENSATION : PAYOUT_DETAIL_TYPE_DEDUCTION;
				$key_id = (isset($dtl['compensation_id']) && $dtl['compensation_id'] > 0) ? $dtl['compensation_id'] :  $dtl['deduction_id'];

				$arr = array();
				if ( ! empty($employee_actual_payout[$employee_id][$type]))
					$arr = $employee_actual_payout[$employee_id][$type]; 

				$arr[$key_id] = (empty($arr[$key_id]) ? 0 : $arr[$key_id]) + ($type == PAYOUT_DETAIL_TYPE_COMPENSATION ? $dtl['compensation_amount'] : $dtl['deduction_amount']);
				$employee_actual_payout[$employee_id][$type] = $arr;
				
				if ($dtl['compensation_id'] == $sys_param_longe_comp_id)
				{
					$employee_actual_payout[$employee_id]['lp_payout_hdr_id'] = explode(',', $dtl['payout_hdr_ids']);
					//RLog::error("Actual Longe Pay Amount [{$employee_actual_payout[$employee_id]['lp_amount']}]");
				}
				
				/*
				 * ADD BY JAB LAZIM
				 */
				$address_val = explode(',', $dtl['address_value']);
				$zipcode_val = explode(',', $dtl['postal_number']);

				$payout_details[$employee_id]['year'] 						= $year;
				$payout_details[$employee_id]['employee_id'] 				= $employee_id;
				$payout_details[$employee_id]['employee_name'] 				= $dtl['employee_name'];
				$payout_details[$employee_id]['employee_tin']				= isset($dtl['employee_tin']) ? $dtl['employee_tin'] : '-'; 
				$payout_details[$employee_id]['rdo_code']					= isset($dtl['rdo_code']) ? $dtl['rdo_code'] : '-';
				$payout_details[$employee_id]['registered_address']			= !empty($address_val) ? $address_val[0] : '-';
				$payout_details[$employee_id]['registered_addr_zip_code']	= !empty($zipcode_val) ? $zipcode_val[0] : '-';
				$payout_details[$employee_id]['local_address']				= !empty($address_val) ? $address_val[1] : '-';
				$payout_details[$employee_id]['local_addr_zip_code']		= !empty($zipcode_val) ? $zipcode_val[1] : '-';
				$payout_details[$employee_id]['foreign_address']			= isset($dtl['foreign_address']) ? $dtl['foreign_address'] : '-';
				$payout_details[$employee_id]['foreign_addr_zip_code']		= isset($dtl['foreign_addr_zip_code']) ? $dtl['foreign_addr_zip_code'] : '-';
				$payout_details[$employee_id]['birth_date']					= isset($dtl['birth_date']) ? $dtl['birth_date'] : '0000-00-00';
				$payout_details[$employee_id]['telephone_num']				= isset($dtl['telephone_num']) ? $dtl['telephone_num'] : '-';
				$payout_details[$employee_id]['exemption_status']			= isset($dtl['exemption_status']) ? $dtl['exemption_status'] : '-';
				$payout_details[$employee_id]['wife_claim_exemption']		= isset($dtl['wife_claim_exemption']) ? $dtl['wife_claim_exemption'] : YES;
				$payout_details[$employee_id]['mwe_flag']					= NO;
				
				$param_2316_key_map_arr = $this->_map_form_2316_details($type, $key_id, $param_2316_map, $key_delim);
				
				//RLog::info("param_2316_key_map [$type][$key_id]");
				//RLog::info($param_2316_key_map_arr);
				
				foreach ($param_2316_key_map_arr as $param_2316_key_map)
				{
					if ( count($param_2316_key_map) == 2 )
					{
						// type|key
						$param_2316_key = $param_2316_key_map[1];
						
						if ($param_2316_key_map[0] == PAYOUT_DETAIL_TYPE_COMPENSATION)
						{
							$key_amount = $dtl['compensation_amount'];
							
							$taxable_amount_cap	= $dtl['taxable_amount'];
							
							if ($taxable_amount_cap > 0 && $taxable_amount_cap < $key_amount )
								$key_amount = $key_amount - $taxable_amount_cap;
						}
						else
							$key_amount = $dtl['deduction_amount'];

						if ($taxable_amount_cap > 0 AND $param_2316_key != ITEM_13_MONTH_PAY)
						{
							$form_2316[$employee_id][$param_2316_key] += $taxable_amount_cap;
						}
						else
						{
							if ( ! isset($form_2316[$employee_id][$param_2316_key]) )
								$form_2316[$employee_id][$param_2316_key] = $key_amount;
							else 
								$form_2316[$employee_id][$param_2316_key] += $key_amount;						
						}
						
						if ($param_2316_key == ITEM_TAXABLE_BASIC_SALARY)
						{
							if ( ! isset($employee_salary_payout[$employee_id]))
								$employee_salary_payout[$employee_id] = array();
							
							$employee_salary_payout[$employee_id]['basic_amount'] = (empty($dtl['basic_amount']) ? 0 : $dtl['basic_amount']);						
						}

						
						if ($param_2316_key == ITEM_SALARY_OTHER_COMPENSATION)
						{
							if ($dtl['payout_type_flag'] != PAYOUT_TYPE_FLAG_SPECIAL)
							{
								if ( ! isset($employee_salary_payout[$employee_id]))
									$employee_salary_payout[$employee_id] = array();
	
								$employee_salary_payout[$employee_id]['other_payroll_compensation'][$dtl['compensation_id']] = 
										(empty($dtl['compensation_unit_amount']) ? 0 : $dtl['compensation_unit_amount']);
							}
						}
						
					}
				}
				
				//RLog::error ('S: form_2316_header ['.$prev_employee_id.']['.$employee_id.'] ['.$employee_salary_payout[$employee_id]['other_payroll_compensation'].']');
				if ($prev_employee_id > 0 && $prev_employee_id != $employee_id)
				{
					// check if there are unsaved statutory deductions
					$prev_payroll_hdr_id = $employee_pay_hdr[$prev_employee_id][3];
					if ( ! isset($form_2316[$prev_employee_id][ITEM_STAT_EMPLOYEE_SHARE]))
						$form_2316[$prev_employee_id][ITEM_STAT_EMPLOYEE_SHARE] = 0;
					
					// check if employee is MWE
					if ($sys_params[PARAM_MWE_COMPUTE_FLAG] == YES)
					{
						$mwe_daily_rate = $sys_params[PARAM_MWE_RATE];
						$emp_monthly_basic_salary = $employee_pay_hdr[$prev_employee_id][4];
						
						$mwe_equiv_monthly_rate = round( (($mwe_daily_rate * $mwe_denominator) / WORKING_MONTHS), 2, PHP_ROUND_HALF_UP );
						
						if ($mwe_equiv_monthly_rate >= $emp_monthly_basic_salary)
						{
							$payout_details[$prev_employee_id]['mwe_flag'] 	= YES;
							$form_2316[$prev_employee_id][KEY_MWE_FLAG]		= TRUE;
						}
						else
							$form_2316[$prev_employee_id][KEY_MWE_FLAG] = FALSE;
					}
					
					/*
					if ($employee_id == 112043 OR $employee_id == 110033)
					{
						RLog::error("MWE FLAG [{$form_2316[$prev_employee_id][KEY_MWE_FLAG]}] [$mwe_equiv_monthly_rate] [$mwe_daily_rate] [$mwe_denominator] [$emp_monthly_basic_salary]");
						RLog::info("==UNSAVED STAT DEDUCTIONS 2 A: [$employee_id] [$pay_month] [$prev_employee_id] [".$form_2316[$prev_employee_id][ITEM_STAT_EMPLOYEE_SHARE]."] [{$employee_unsaved_deduct[$prev_payroll_hdr_id]}] [".$form_2316[$prev_employee_id][ITEM_UNION_DUES]."] [{$employee_unsaved_union[$prev_payroll_hdr_id]}]");
					}
					*/
					
					//RLog::error("UNSAVED STAT DEDUCTIONS [{$form_2316[$prev_employee_id][KEY_MWE_FLAG]}] [{$employee_unsaved_deduct[$prev_payroll_hdr_id]}]");
	
					if ( ! empty($employee_unsaved_deduct[$prev_payroll_hdr_id]))
						$form_2316[$prev_employee_id][ITEM_STAT_EMPLOYEE_SHARE] += $employee_unsaved_deduct[$prev_payroll_hdr_id];
					if ( ! empty($employee_unsaved_union[$prev_payroll_hdr_id]))
						$form_2316[$prev_employee_id][ITEM_UNION_DUES] = $employee_unsaved_union[$prev_payroll_hdr_id];
					else
					{
						if ($form_2316[$prev_employee_id][ITEM_UNION_DUES] > 0)
							$form_2316[$prev_employee_id][ITEM_UNION_DUES] = $amount_union_dues;
					}
					/*
					if ($employee_id == 112043 OR $employee_id == 110033)
						RLog::info("==UNSAVED STAT DEDUCTIONS 2 B: [$employee_id] [$pay_month] [$prev_employee_id] ["
							.$form_2316[$prev_employee_id][ITEM_STAT_EMPLOYEE_SHARE]."] [{$employee_unsaved_deduct[$prev_payroll_hdr_id]}] ["
							.$form_2316[$prev_employee_id][ITEM_UNION_DUES]."] [{$employee_unsaved_union[$prev_payroll_hdr_id]}]");					
					*/
					
					$prev_emp_data = $this->_get_previous_employer_data($prev_employee_id, $sys_param_bir, $sys_param_prev_emp_name, $sys_param_prev_emp_address, $sys_param_prev_emp_income, $sys_param_prev_emp_tax);
					$employee_dependents = $this->_get_employee_exempt_dependents($prev_employee_id, $payout_date, $tax_dep_civil_status, $tax_dep_employment_status, $tax_dep_relation_type, $tax_dep_age_limit, 
							$sys_param_husband_exception, $sys_param_wife_exception, $sys_param_personal_exempt_dep_num, $employee_tax_claim_exempt);
							
					$form_2316_ids = $this->_save_form_2316_header($form_2316_ids, $prev_employee_id, $payout_details[$prev_employee_id], $employee_dependents, $save);
					
					if (! $form_2316[$prev_employee_id][KEY_MWE_FLAG])
					{
						$form_2316 = $this->_set_taxable_benefits($prev_employee_id, $dt_pay_obj, $pay_month, 
							$employee_pay_hdr[$prev_employee_id],  
							$form_2316, $employee_salary_payout[$prev_employee_id], 
							$project_tax, ($project_tax ? $employee_taxable_benefits[$prev_employee_id] : array()), 
							$prorated_rates, $stat_deduction_tables, 
							$sys_params, $param_2316_map, $employee_actual_payout[$prev_employee_id]);
					}
							
					$form_2316 = $this->_set_other_details($prev_employee_id, $form_2316, $prev_emp_data, $employee_dependents, $sys_param_personal_exempt_dep_num, $sys_param_personal_exempt_dep, $sys_param_personal_exempt);
					
					$save_data = FALSE;
				}
				
				$prev_employee_id = $employee_id;
			}
			
			if ($save_data)
			{
				// check if there are unsaved statutory deductions
				$prev_payroll_hdr_id = $employee_pay_hdr[$prev_employee_id][3];
				if ( ! isset($form_2316[$prev_employee_id][ITEM_STAT_EMPLOYEE_SHARE]))
					$form_2316[$prev_employee_id][ITEM_STAT_EMPLOYEE_SHARE] = 0;
				
				// check if employee is MWE
				if ($sys_params[PARAM_MWE_COMPUTE_FLAG] == YES)
				{
					$mwe_daily_rate = $sys_params[PARAM_MWE_RATE];
					$emp_monthly_basic_salary = $employee_pay_hdr[$prev_employee_id][4];
					
					$mwe_equiv_monthly_rate = round( (($mwe_daily_rate * $mwe_denominator) / WORKING_MONTHS), 2, PHP_ROUND_HALF_UP );
					
					if ($mwe_equiv_monthly_rate >= $emp_monthly_basic_salary)
					{
						$payout_details[$prev_employee_id]['mwe_flag'] 	= YES;
						$form_2316[$prev_employee_id][KEY_MWE_FLAG] 	= TRUE;
					}
					else
						$form_2316[$prev_employee_id][KEY_MWE_FLAG] = FALSE;
				}
				/*
				if ($employee_id == 112043 OR $employee_id == 110033)
				{
					RLog::error("MWE FLAG [{$form_2316[$prev_employee_id][KEY_MWE_FLAG]}] [$mwe_equiv_monthly_rate] [$mwe_daily_rate] [$mwe_denominator] [$emp_monthly_basic_salary]");
					RLog::info("==UNSAVED STAT DEDUCTIONS 2 A: [$employee_id] [$pay_month] [$prev_employee_id] [".$form_2316[$prev_employee_id][ITEM_STAT_EMPLOYEE_SHARE]."] [{$employee_unsaved_deduct[$prev_payroll_hdr_id]}] [".$form_2316[$prev_employee_id][ITEM_UNION_DUES]."] [{$employee_unsaved_union[$prev_payroll_hdr_id]}]");
				}
				*/

				if ( ! empty($employee_unsaved_deduct[$prev_payroll_hdr_id]))
					$form_2316[$prev_employee_id][ITEM_STAT_EMPLOYEE_SHARE] += $employee_unsaved_deduct[$prev_payroll_hdr_id];
				if ( ! empty($employee_unsaved_union[$prev_payroll_hdr_id]))
					$form_2316[$prev_employee_id][ITEM_UNION_DUES] = $employee_unsaved_union[$prev_payroll_hdr_id];
				else
				{
					if ($form_2316[$prev_employee_id][ITEM_UNION_DUES] > 0)
						$form_2316[$prev_employee_id][ITEM_UNION_DUES] = $amount_union_dues;
				}

				/*
				if ($employee_id == 112043 OR $employee_id == 110033)
					RLog::info("==UNSAVED STAT DEDUCTIONS 2 B: [$employee_id] [$pay_month] [$prev_employee_id] ["
						.$form_2316[$prev_employee_id][ITEM_STAT_EMPLOYEE_SHARE]."] [{$employee_unsaved_deduct[$prev_payroll_hdr_id]}] ["
						.$form_2316[$prev_employee_id][ITEM_UNION_DUES]."] [{$employee_unsaved_union[$prev_payroll_hdr_id]}]");
				*/					
				
				$prev_emp_data = $this->_get_previous_employer_data($prev_employee_id, $sys_param_bir, $sys_param_prev_emp_name, $sys_param_prev_emp_address, $sys_param_prev_emp_income, $sys_param_prev_emp_tax);
				$employee_dependents = $this->_get_employee_exempt_dependents($prev_employee_id, $payout_date, $tax_dep_civil_status, $tax_dep_employment_status, $tax_dep_relation_type, $tax_dep_age_limit, 
						$sys_param_husband_exception, $sys_param_wife_exception, $sys_param_personal_exempt_dep_num, $employee_tax_claim_exempt);
						
				$form_2316_ids = $this->_save_form_2316_header($form_2316_ids, $prev_employee_id, $payout_details[$prev_employee_id], $employee_dependents, $save);
				
				if (! $form_2316[$prev_employee_id][KEY_MWE_FLAG])
				{
					$form_2316 = $this->_set_taxable_benefits($prev_employee_id, $dt_pay_obj, $pay_month, 
						$employee_pay_hdr[$prev_employee_id],  
						$form_2316, $employee_salary_payout[$prev_employee_id], 
						$project_tax, ($project_tax ? $employee_taxable_benefits[$prev_employee_id] : array()), 
						$prorated_rates, $stat_deduction_tables, 
						$sys_params, $param_2316_map, $employee_actual_payout[$prev_employee_id]);
				}
						
				$form_2316 = $this->_set_other_details($prev_employee_id, $form_2316, $prev_emp_data, $employee_dependents, $sys_param_personal_exempt_dep_num, $sys_param_personal_exempt_dep, $sys_param_personal_exempt);
			}
			
			if ($save)
				$this->payroll_tax->insert_form_2316($form_2316_ids, $form_2316, $tax_table_flag, $payout_date, $year, $pay_month, $monthly_only);
			
		} catch ( PDOException $e ) {
			RLog::error($e->getMessage() . " [$prev_employee_id]");
			throw $e;
		} catch ( Exception $e ) {
			RLog::error($e->getMessage() . " [$prev_employee_id]");
			throw $e;
		}
		
		return $form_2316_ids;
	}
	
	private function _save_form_2316_header($form_2316_ids, $employee_id, $payout_details, $employee_dependents=array(), $save=FALSE)
	{
		if( ! $save) return;
		
		try
		{
			$table = $this->common->tbl_form_2316_header;
			
			$payout_details['exemption_status']		= $employee_dependents[KEY_TAX_EXEMPT_CODE];
			$payout_details['wife_claim_exemption']	= $employee_dependents[KEY_WIFE_CLAIM_EXEMPT];
			$form_2316_ids[$employee_id] = $this->common->insert_general_data($table, $payout_details, TRUE, TRUE, 'form_2316_id');						
			RLog::info('E: form_2316_header ['.$employee_id.']['.$form_2316_ids[$employee_id].']');
			
			// save dependents
			$this->_save_employee_dependents($employee_id, $form_2316_ids[$employee_id], $employee_dependents[KEY_DEPENDENTS]);
			
			return $form_2316_ids;
		} catch ( Exception $e ) {
			RLog::error($e->getMessage() . " [$employee_id]");
			throw $e;
		}
	}
	
	
	/*
	 * $salary_payout_num			contains total paid basic salary amount and number of payout (distinct summary_id)
	 * $employee_taxable_benefits	contains taxable benefit records (comma-separated values for each data eg. compensation_ids, compensation_codes)
	 * $prorated_rates				contains prorated rates for all compensations (where pro_rated_flag = 'Y')
	 * $stat_deduction_tables 		contains GSIS, PHILHEALTH, PAG-IBIG tables
	 * $employee_actual_payout		contains paid compensation and deductions
	 */
	private function _set_taxable_benefits($employee_id, $dt_obj, $pay_month, $employee_pay_hdr, $form_2316, $salary_payout, 
			$project_tax=FALSE, $employee_taxable_benefits=array(), $prorated_rates=array(), $stat_deduction_tables=array(), 
			$sys_params=array(), $param_2316_map=array(), $employee_actual_payout=array())
	{
		//RLog::info('S: _set_taxable_benefits ['.$employee_id.']['.$salary_payout['basic_amount'].']');
		RLog::info('S: _set_taxable_benefits ['.$employee_id.']['.$pay_month.']');
		
		$salary_grade 	= isset($employee_pay_hdr[0]) ? $employee_pay_hdr[0] : 0;
		$pay_step 		= isset($employee_pay_hdr[1]) ? $employee_pay_hdr[1] : 1;
		$anniv_emp_date	= isset($employee_pay_hdr[2]) ? $employee_pay_hdr[2] : date('Y-m-d');
		
		$sys_param_taxable_benefit_cap	= $sys_params[PARAM_AMOUNT_TAXABLE_BENEFIT_CAP];
		$sys_param_months_in_year		= $sys_params[PARAM_WORKING_MONTHS];
		$sys_param_working_days			= $sys_params[PARAM_WORKING_DAYS];
		
		$year_end_bonus	= 0;
		$projected_deductions	= 0;
		$basic_salary			= (empty($salary_payout['basic_amount']) ? 0 : $salary_payout['basic_amount']);
		
		if ($project_tax)
		{
			$remaining_months = ($sys_param_months_in_year - $pay_month);
			
			if (isset($form_2316[$employee_id][ITEM_TAXABLE_BASIC_SALARY]))
			{
				// S: BASIC SALARY and ADJUSTMENTS
				if ($project_tax)
				{
					$payout_amount = $form_2316[$employee_id][ITEM_TAXABLE_BASIC_SALARY];
					
					//project basic salary
					$projected_basic_salary = ($basic_salary * $remaining_months) + $payout_amount;
					
					RLog::info("*** PROJECTED BASIC SALARY ORIG [$employee_id][$basic_salary][$remaining_months][$payout_amount][$projected_basic_salary] ***");
		
					$form_2316[$employee_id][ITEM_TAXABLE_BASIC_SALARY] = $projected_basic_salary;
					
					//RLog::info("projected salary: [$employee_id][$basic_salary][$payout_amount][$pay_month][$remaining_months]");
					RLog::info($form_2316[$employee_id][ITEM_TAXABLE_BASIC_SALARY]);
				}
				// E: BASIC SALARY and ADJUSTMENTS
			}
		
			// S: OTHER TAXABLE BENEFITS AND STATUTORY DEDUCTIONS
			//RLog::error('before _project');
			//RLog::error($employee_actual_payout);
			$year_end_bonus	= $this->_project_other_taxable_benefits($employee_id, $dt_obj, $pay_month, $basic_salary,  
				$employee_taxable_benefits, $employee_actual_payout, $salary_grade, $pay_step, $anniv_emp_date, $prorated_rates, $sys_params);

			$deduction = array();
			$deduction['employee_id'] 			= $employee_id;
			$deduction['employ_monthly_salary'] = $basic_salary;
			$payout_date = $dt_obj->format('Y-m-d');
			
			//RLog::error("FORM 2316 ITEM_STAT_EMPLOYEE_SHARE [{$form_2316[$employee_id][ITEM_STAT_EMPLOYEE_SHARE]}]");
			
			$monthly_union_dues = (isset($form_2316[$employee_id][ITEM_UNION_DUES]) ? $form_2316[$employee_id][ITEM_UNION_DUES] : 0);
			
			$projected_deductions = $this->_project_statutory_deductions($deduction, $payout_date, $pay_month, $stat_deduction_tables, $monthly_union_dues);
			$projected_deductions += (empty($form_2316[$employee_id][ITEM_STAT_EMPLOYEE_SHARE]) ? 0 : $form_2316[$employee_id][ITEM_STAT_EMPLOYEE_SHARE]);
			$form_2316[$employee_id][ITEM_STAT_EMPLOYEE_SHARE] = $projected_deductions;
			/*
			if ($employee_id == 112043 OR $employee_id == 110033)
			{
				RLog::info("FORM 2316 ITEM_UNION_DUES [$employee_id] [$monthly_union_dues] [{$form_2316[$employee_id][ITEM_UNION_DUES]}] [$projected_deductions]");
			}
			*/			
			
			// E: OTHER TAXABLE BENEFITS
			
			// S: NON-TAXABLE SALARIES AND OTHER FORMS OF COMPENSATION
			if (isset($form_2316[$employee_id][ITEM_SALARY_OTHER_COMPENSATION]))
			{
				// S: salaries and other compensations (eg. PERA, LAUNDRY)
					$other_compensations 		= $salary_payout['other_payroll_compensation'];
					//RLog::error("PROJECT OTHER COMPENSATIONS !");
					//RLog::error($other_compensations);
					$other_compensation_amount 	= 0;
					foreach($other_compensations as $oth_com_amt)
					{
						$other_compensation_amount += $oth_com_amt;
					}
					$projected_other_compensation = ($other_compensation_amount * $remaining_months);
					
					//RLog::error("*** PROJECTED OTHER PAYROLL COMPENSATION ORIG [$employee_id][{$form_2316[$employee_id][ITEM_SALARY_OTHER_COMPENSATION]}][$remaining_months][$other_compensation_amount][$projected_other_compensation] ***");
		
					$form_2316[$employee_id][ITEM_SALARY_OTHER_COMPENSATION] = $form_2316[$employee_id][ITEM_SALARY_OTHER_COMPENSATION] + $projected_other_compensation;
				// E: salaries and other compensations (eg. PERA, LAUNDRY)
			}			
			
			/*
			$paid_count				= (empty($salary_payout['paid_count']) ? 0 : $salary_payout['paid_count']);
			$project_month			= WORKING_MONTHS - $pay_month;
			$other_comp				= empty($form_2316[$employee_id][ITEM_SALARY_OTHER_COMPENSATION]) ? 0 : $form_2316[$employee_id][ITEM_SALARY_OTHER_COMPENSATION];
			$monthly_other_comp		= ($paid_count != 0) ? ($other_comp / $paid_count) : 0;  
			$projected_other_comp 	= $other_comp + ($monthly_other_comp * $project_month); 
			$form_2316[$employee_id][ITEM_other_comp] = $projected_other_comp;
			*/
			// E: NON-TAXABLE SALARIES AND OTHER FORMS OF COMPENSATION
			
		} // if ($project_tax)
		
		// S: BASIC SALARY and ADJUSTMENTS
		if (isset($form_2316[$employee_id][ITEM_TAXABLE_BASIC_SALARY]))
		{
			if (isset($form_2316[$employee_id][ITEM_STAT_EMPLOYEE_SHARE]))
				$form_2316[$employee_id][ITEM_TAXABLE_BASIC_SALARY] -= $form_2316[$employee_id][ITEM_STAT_EMPLOYEE_SHARE];

			if (isset($form_2316[$employee_id][ITEM_BASIC_SALARY_DEDUCTIONS]))
				$form_2316[$employee_id][ITEM_TAXABLE_BASIC_SALARY] -= $form_2316[$employee_id][ITEM_STAT_EMPLOYEE_SHARE];
		}
		else
		{
			if (isset($form_2316[$employee_id][ITEM_STAT_EMPLOYEE_SHARE]))
				$form_2316[$employee_id][ITEM_TAXABLE_BASIC_SALARY] = $form_2316[$employee_id][ITEM_STAT_EMPLOYEE_SHARE] * -1;

			if (isset($form_2316[$employee_id][ITEM_BASIC_SALARY_DEDUCTIONS]))
				$form_2316[$employee_id][ITEM_TAXABLE_BASIC_SALARY] = $form_2316[$employee_id][ITEM_STAT_EMPLOYEE_SHARE] * -1;			
		}
		
		RLog::info("*** PROJECTED BASIC SALARY FINAL [{$form_2316[$employee_id][ITEM_TAXABLE_BASIC_SALARY]}] ***");
		// E: BASIC SALARY and ADJUSTMENTS
		
		
		// S: MONTH_PAY_13 (Taxable benefits)
		//RLog::info("1: FORM 2316 ITEM_STAT_MONTH_13 [{$form_2316[$employee_id][ITEM_13_MONTH_PAY]}]");
		$year_end_bonus += isset($form_2316[$employee_id][ITEM_13_MONTH_PAY]) ? $form_2316[$employee_id][ITEM_13_MONTH_PAY] : 0;
		$taxable_13_month_pay = $year_end_bonus - $sys_param_taxable_benefit_cap;
		//RLog::info("2: FORM 2316 ITEM_STAT_MONTH_13 [$year_end_bonus] [$taxable_13_month_pay]");
		
		if ($taxable_13_month_pay > 0)
		{
			$form_2316[$employee_id][ITEM_13_MONTH_PAY] = $sys_param_taxable_benefit_cap;
			$form_2316[$employee_id][ITEM_TAXABLE_13_MONTH_PAY] = $taxable_13_month_pay;
		}
		else
		{
			$form_2316[$employee_id][ITEM_13_MONTH_PAY] = $year_end_bonus;
		}
		
		RLog::info("3: FORM 2316 ITEM_STAT_MONTH_13 [{$form_2316[$employee_id][ITEM_TAXABLE_13_MONTH_PAY]}]");
		// E: MONTH_PAY_13 (Taxable benefits)
		
		//RLog::info('E: _set_taxable_benefits');
		
		return $form_2316;
	}
	
	private function _project_other_taxable_benefits($employee_id, $dt_obj, $pay_month, $basic_salary, $employee_taxable_benefits, $employee_actual_payout, 
			$salary_grade, $pay_step, $anniv_emp_date, $prorated_rates, $sys_params=array())
	{
		$projected_month_pay_13 = 0;
		$orig_pay_month = $pay_month;
		//$pay_month 		+= 1;
		
		try
		{
			$compensation_ids 		= $employee_taxable_benefits['compensation_ids'];
			$compensation_codes		= $employee_taxable_benefits['compensation_codes'];
			$compensation_type_flags= $employee_taxable_benefits['compensation_type_flags'];
			$amounts 				= $employee_taxable_benefits['amounts'];
			$multiplier_ids 		= $employee_taxable_benefits['multiplier_ids'];
			$rates 					= $employee_taxable_benefits['rates'];
			$pro_rated_flags 		= $employee_taxable_benefits['pro_rated_flags'];
			$frequency_ids 			= $employee_taxable_benefits['frequency_ids'];
			$taxable_amounts		= $employee_taxable_benefits['taxable_amounts'];
			$payout_hdr_ids			= $employee_actual_payout['lp_payout_hdr_id'];
			/*
			RLog::error("PAID COMPENSATION:");
			RLog::error($employee_actual_payout);
			
			RLog::error("PROJECTING THE FF: ");
			RLog::error($compensation_codes);
			*/
			if (count($compensation_ids) != count($compensation_codes)
					OR count($compensation_ids) != count($compensation_type_flags)
					OR count($compensation_ids) != count($amounts)
					OR count($compensation_ids) != count($multiplier_ids)
					OR count($compensation_ids) != count($rates)
					OR count($compensation_ids) != count($pro_rated_flags)
					OR count($compensation_ids) != count($frequency_ids)
					OR count($compensation_ids) != count($taxable_amounts))
				throw new Exception('Compensation Data: ' . $this->lang->line('sys_param_not_defined'));
				
			for ($c=0; $c<count($compensation_ids); $c++)
			{
				$comp_id 		= $compensation_ids[$c];
				
				//TODO project differential ?
				if ($comp_id == $sys_params[PARAM_COMPENSATION_ID_SALDIFFL] 
						|| $comp_id == $sys_params[PARAM_DEDUCTION_ID_GSISDIFFL] 
						|| $comp_id == $sys_params[PARAM_COMPENSATION_ID_HAZDIFFL]
						|| $comp_id == $sys_params[PARAM_COMPENSATION_ID_LONGEDIFFL])
					continue;
				
				$comp_code 			= $compensation_codes[$c];
				$comp_type_flag		= $compensation_type_flags[$c];
				$amount 			= $amounts[$c];
				$mult_id 			= $multiplier_ids[$c];
				$rate 				= $rates[$c];
				$prorated_flag 		= $pro_rated_flags[$c];
				$freq_id 			= $frequency_ids[$c];
				$taxable_amount_cap	= (empty($taxable_amounts[$c]) ? 0 : $taxable_amounts[$c]);
				$payout_hdr_id		= $payout_hdr_ids[0];
				
				//RLog::error("PROJECTING TAXABLE COMPENSATION: [$pay_month] ");
				//RLog::error($comp_code);
				
				if ($freq_id == FREQUENCY_ANNUAL OR $freq_id == FREQUENCY_ONE_TIME) // if compensation is annual and already given, go to next compensation
				{
					if ( isset($employee_actual_payout[PAYOUT_DETAIL_TYPE_COMPENSATION][$comp_id]) )
					{
						//RLog::error("{$comp_code} IS ALREADY PAID ...");
						continue;
					}
					
					$pay_month = $orig_pay_month;
				}
				else
				{
					$pay_month = $orig_pay_month + 1;
				}
				
				$cmpnstn = array();
				$cmpnstn['compensation_id']		= $comp_id;
				$cmpnstn['compensation_code']	= $comp_code;
				$cmpnstn['frequency_id'] 		= $freq_id;
				$cmpnstn['amount'] 				= $amount;
				$cmpnstn['multiplier_id']		= $mult_id;
				$cmpnstn['rate']				= $rate;
				$cmpnstn['tenure_rqmt_flag']	= TENURE_RQMT_NA; // do not check tenure if projected 
				$cmpnstn['tenure_rqmt_val'] 	= 0;
				$cmpnstn['employee_id'] 		= $employee_id;
				$cmpnstn['pro_rated_flag'] 		= $prorated_flag;
				$cmpnstn['employ_monthly_salary']= $basic_salary;
				
				$cmpnstn['salary_grade'] 		= $salary_grade;
				$cmpnstn['pay_step'] 			= $pay_step;
				$cmpnstn['anniv_emp_date'] 		= $anniv_emp_date;
				
				$cmpnstn['project_amount'] 			= YES;
				
				$ret_amount = array();
				for ($m=$pay_month; $m<=WORKING_MONTHS; $m++)
				{
					$mo					= str_pad($m, 2, '0', STR_PAD_LEFT);
				    $covered_date_from	= $dt_obj->format("Y-$mo-01");
				    $covered_date_to	= $dt_obj->format('Y-m-t');
				    
					switch ($comp_type_flag)
					{
						case COMPENSATION_TYPE_FLAG_FIXED:
							#FIXED AMOUNT
							$param_dates = array();
							$covered_date_from	= $dt_obj->format("Y-$orig_pay_month-01");

							$fixed_amount_arr	= $this->payroll_common->compute_amount_with_frequency($cmpnstn, $covered_date_from, FALSE, $param_dates);
							$ret_amount[$comp_id][KEY_AMOUNT] = $fixed_amount_arr[KEY_AMOUNT];
						break;
						
						case COMPENSATION_TYPE_FLAG_VARIABLE:
							#VARIABLE AMOUNT
							$ret_amount[$comp_id][KEY_AMOUNT] = $this->payroll_common->compute_variable_compensation_type($cmpnstn);
						break;
						
						case COMPENSATION_TYPE_FLAG_SYSTEM:
							if ($comp_id == '10')
							{
								//$ret_amount[$comp_id][KEY_AMOUNT] = empty($employee_actual_payout[$employee_id]['lp_amount']) ? 0.00 : $employee_actual_payout[$employee_id]['lp_amount'];
								$ret_amount[$comp_id][KEY_AMOUNT] = $this->payroll_tax->get_payout_amount($payout_hdr_id, 10);
								//RLog::error("PROJECT compensation [$comp_id] [{$ret_amount[$comp_id][KEY_AMOUNT]}]");
								continue;
							}
							
							
							#SYSTEM GENERATED AMOUNT
							$payroll_type_vars = array(KEY_SALARY_FREQ_FLAG => SALARY_MONTHLY, 
									KEY_MWE_DENOMINATOR => 0.00);							
							$ret_amount	= $this->payroll_common->get_system_generated_amount($cmpnstn, NULL, $covered_date_from, $covered_date_to, NULL, $sys_params, array(), $payroll_type_vars);
						break;
					}
					
					if ($cmpnstn['pro_rated_flag'] != PRORATE_NA)
					{
						$working_days = $sys_params[PARAM_WORKING_DAYS];
						$ret_amount[$comp_id] = $this->payroll_common->compute_prorated_value($cmpnstn, $ret_amount[$comp_id][KEY_AMOUNT], $working_days, $salary_grade, $prorated_rates[$comp_id]);
					}
					
					if ($taxable_amount_cap > 0 && $taxable_amount_cap < $ret_amount[$comp_id][KEY_AMOUNT] )
						$ret_amount[$comp_id][KEY_AMOUNT] = $ret_amount[$comp_id][KEY_AMOUNT] - $taxable_amount_cap;
					
					//RLog::error('S: _set_taxable_benefits 2 ['.$employee_id.']['.$comp_id.']['.$cmpnstn['compensation_code'].']['.$mo.']['.$freq_id.']['.$ret_amount[$comp_id][KEY_AMOUNT].']');
					$projected_month_pay_13 += $ret_amount[$comp_id][KEY_AMOUNT];
					/*
					if ($m==$pay_month OR $m==WORKING_MONTHS)
					{
						RLog::error("1: Projecting for last month [{$cmpnstn['compensation_code']}] [$mo] [{$ret_amount[$comp_id][KEY_AMOUNT]}] [$projected_month_pay_13]...");
					}
					*/
					if ($freq_id == FREQUENCY_DAILY OR $freq_id == FREQUENCY_ANNUAL OR $freq_id == FREQUENCY_ONE_TIME) // if annual, one time, or daily, no need to loop remaining months
						break;
				}
				
				//RLog::info("Projecting [$employee_id] [{$cmpnstn['compensation_code']}] [$pay_month] [$projected_month_pay_13] [{$ret_amount[$comp_id][KEY_AMOUNT]}]...");
			} // for ($c=0; $c<count($compensation_ids); $c++)		
		}
		catch (Exception $e)
		{
			RLog::error($e->getMessage());
			throw $e;
		}
		
		return $projected_month_pay_13;
	}
	
	private function _project_statutory_deductions($deduction, $payout_date, $pay_month, $stat_deduction_tables, $monthly_union_dues=0)
	{
		$projected_deductions = 0;
		
		try
		{
			$gsis_table			= $stat_deduction_tables['GSIS'];
			$pagibig_table 		= $stat_deduction_tables['PAGIBIG'];
			$philhealth_table 	= $stat_deduction_tables['PHILHEALTH'];
			
			$project_month		= WORKING_MONTHS - $pay_month;
			
			//RLog::error("_project_statutory_deductions [$payout_date] [$pay_month] [$project_month]");
			
			$share_amounts = array();
			$share_amounts = $this->payroll_common->compute_gsis($payout_date, $deduction, $gsis_table, TRUE);
			if ( ! empty($share_amounts['personal_share']))
			{
				//RLog::error("_project_statutory_deductions GSIS [".$share_amounts['personal_share']."]");
				$share_amounts['personal_share'] = $share_amounts['personal_share'] * $project_month;
				$projected_deductions += $share_amounts['personal_share']; 
			}
			
			$share_amounts = array();
			$share_amounts = $this->payroll_common->compute_pagibig($payout_date, $deduction, $pagibig_table);
			if ( ! empty($share_amounts['personal_share']))
			{
				//RLog::error("_project_statutory_deductions PAGIBIG [".$share_amounts['personal_share']."]");
				$share_amounts['personal_share'] = $share_amounts['personal_share'] * $project_month;
				$projected_deductions += $share_amounts['personal_share']; 
			}

			$share_amounts = array();
			$share_amounts = $this->payroll_common->compute_philhealth($payout_date, $deduction, $philhealth_table);
			if ( ! empty($share_amounts['personal_share']))
			{
				//RLog::error("_project_statutory_deductions PHILHEALTH [".$share_amounts['personal_share']."]");
				$share_amounts['personal_share'] = $share_amounts['personal_share'] * $project_month;
				$projected_deductions += $share_amounts['personal_share']; 
			}
			
			if ($monthly_union_dues > 0)
			{
				//RLog::info("Pay month [$monthly_union_dues] [$pay_month] [$project_month]");
				//RLog::error("_project_statutory_deductions UNION [".$monthly_union_dues."]");
				$projected_deductions += $monthly_union_dues * $project_month;
			}
			
			//RLog::error("_project_statutory_deductions TOTAL [".$projected_deductions."]");
		}
		catch (Exception $e)
		{
			RLog::error($e->getMessage());
			throw $e;
		}
		
		return $projected_deductions;
	}

	private function _set_other_details($employee_id, $form_2316, $prev_emp_data, $employee_dependents, $sys_param_personal_exempt_dep_num, $sys_param_personal_exempt_dep, $sys_param_personal_exempt)
	{
		// PREVIOUS EMPLOYER
		if (isset($prev_emp_data['prev_emp_taxable_income']))
			$form_2316[$employee_id][ITEM_TAXABLE_INCOME_PREVIOUS_EMPLOYER] = $prev_emp_data['prev_emp_taxable_income'];
		if (isset($prev_emp_data['prev_emp_tax_withheld']))
			$form_2316[$employee_id][ITEM_TAX_WITHHELD_PREVIOUS_EMPLOYER] = $prev_emp_data['prev_emp_tax_withheld'];
			
		// PERSONAL EXEMPTIONS
		$num_dependents = COUNT($employee_dependents[KEY_DEPENDENTS]); 
		if ($num_dependents > $sys_param_personal_exempt_dep_num)
		{
			$num_dependents = $sys_param_personal_exempt_dep_num;
		}
		$personal_exemption_amount	= ($sys_param_personal_exempt_dep * $num_dependents);
		$personal_exemption_amount	= $personal_exemption_amount + $sys_param_personal_exempt;
		
		$form_2316[$employee_id][ITEM_TOTAL_PERSONAL_EXEMPTION] = $personal_exemption_amount;

		return $form_2316;
	}
	

	private function _get_previous_employer_data($employee_id, $sys_param_bir, $sys_param_prev_emp_name, $sys_param_prev_emp_address, 
				$sys_param_prev_emp_income, $sys_param_prev_emp_tax)
	{
		$previous_employer_data['prev_emp_name'] = '';
		$previous_employer_data['prev_emp_address'] = '';
		$previous_employer_data['prev_emp_taxable_income'] = 0;
		$previous_employer_data['prev_emp_tax_withheld'] = 0;
		
		$sys_param_previous_employer = array( $sys_param_prev_emp_name, $sys_param_prev_emp_address, 
				$sys_param_prev_emp_income, $sys_param_prev_emp_tax);
		try
		{
			// START: deduct tax paid from previous employer
			$paid_tax_amount = 0;
			$field = array('B.other_deduction_detail_id', 'B.other_deduction_detail_value value');
			$tables					= array(
				'main'	=> array(
					'table'		=> $this->payroll_tax->tbl_employee_deductions,
					'alias'		=> 'A'
				),
				'table1'=> array(
					'table'		=> $this->payroll_tax->tbl_employee_deduction_other_details,
					'alias'		=> 'B',
					'type'		=> 'JOIN',
					'condition'	=> 'B.employee_deduction_id = A.employee_deduction_id'
				),
				'table2'=> array(
					'table'		=> $this->payroll_tax->tbl_param_deductions,
					'alias'		=> 'C',
					'type'		=> 'JOIN',
					'condition'	=> 'A.deduction_id = C.deduction_id'
				)
			);
			
			$where = array();
			$where['A.employee_id'] = $employee_id;
			$where['B.other_deduction_detail_id'] = array($sys_param_previous_employer, array('IN'));
			$where['C.deduction_code'] = $sys_param_bir;
			$prev_emp_data = $this->common->get_general_data($field, $tables, $where);
			
			foreach ($prev_emp_data as $data)
			{
				switch ($data['other_deduction_detail_id'])
				{
					case $sys_param_prev_emp_name:
						$previous_employer_data['prev_emp_name'] = $data['value'];
						break;
					case $sys_param_prev_emp_address:
						$previous_employer_data['prev_emp_address'] = $data['value'];
						break;
					case $sys_param_prev_emp_income:
						$previous_employer_data['prev_emp_taxable_income'] = $data['value'];
						break;
					case $sys_param_prev_emp_tax:
						$previous_employer_data['prev_emp_tax_withheld'] = $data['value'];
						break;
				}
			}			
		}
		catch (Exception $e)
		{
			RLog::error($e->getMessage());
			throw $e;
		}
		
		return $previous_employer_data;
	}
	
	private function _get_employee_exempt_dependents($employee_id, $payout_date, $tax_dep_civil_status, $tax_dep_employment_status, $tax_dep_relation_type, $tax_dep_age_limit, 
			$sys_param_husband_exception, $sys_param_wife_exception, $sys_param_personal_exempt_dep_num, $employee_tax_claim_exempt=array())
	{
		$dependents 		= array();
		$tax_claim_flag		= FALSE;
		$tax_exempt_code 	= TAX_EXEMPT_STAT_SINGLE;
		$wife_claim_exempt 	= NO;
		
		$employee_dependents = array(KEY_DEPENDENTS => $dependents, KEY_TAX_EXEMPT_CODE => $tax_exempt_code, KEY_WIFE_CLAIM_EXEMPT => $wife_claim_exempt);
		
		// check if employee shall claim the tax exemption
		if (isset($employee_tax_claim_exempt[$employee_id]))
		{
			$employee_tax_claim_exempt	= $employee_tax_claim_exempt[$employee_id];
			$emp_gender_code 			= isset($employee_tax_claim_exempt['gender_code']) ? $employee_tax_claim_exempt['gender_code'] : NULL;
			$emp_civil_status_id		= isset($employee_tax_claim_exempt['civil_status_id']) ? $employee_tax_claim_exempt['civil_status_id'] : 0;
			$claim_exempt				= isset($employee_tax_claim_exempt['claim_exempt']) ? $employee_tax_claim_exempt['claim_exempt'] : 0;

			switch ($emp_civil_status_id)
			{
				case CIVIL_STATUS_SINGLE:
					$tax_exempt_code	= TAX_EXEMPT_STAT_SINGLE;
					$tax_claim_flag 	= TRUE;
					break;
				case CIVIL_STATUS_MARRIED:
					$tax_exempt_code	= TAX_EXEMPT_STAT_MARRIED;
					if ($emp_gender_code == MALE AND $claim_exempt == $sys_param_husband_exception)
						$tax_claim_flag = TRUE;
					else if ($emp_gender_code == FEMALE AND $claim_exempt == $sys_param_wife_exception)
						$tax_claim_flag = TRUE;
					else
						$tax_claim_flag = TRUE;
						
					if ($claim_exempt == $sys_param_wife_exception)
						$wife_claim_exempt = YES;
					
					break;
			}
		}
		
		if ( ! $tax_claim_flag)
		{
			//RLog::error("no tax claim [$tax_exempt_code] [$wife_claim_exempt] [$claim_exempt] [$sys_param_wife_exception]");
			$employee_dependents = array(KEY_DEPENDENTS => $dependents, KEY_TAX_EXEMPT_CODE => $tax_exempt_code, KEY_WIFE_CLAIM_EXEMPT => $wife_claim_exempt);
			return $employee_dependents;
		}
		
		try
		{
			// throw new Exception('Form 2316: ' . $this->lang->line('sys_param_not_defined'));
			if( empty($tax_dep_civil_status) OR empty($tax_dep_employment_status) 
					OR empty($tax_dep_relation_type) OR empty($tax_dep_age_limit) )
				RLog::info('Form 2316: ' . $this->lang->line('sys_param_not_defined'));
			/*
			else
				$params['bir_flag'] 	= YES;
			*/
			
			$params = array();
			$params['employee_id']	= $employee_id;
			$params['payout_date'] 	= $payout_date;
			$dependents 			= $this->payroll_tax->get_employee_dependents($params);
			
			if (count($dependents) == 0)
			{
				//RLog::error("no dependents [$tax_exempt_code] [$wife_claim_exempt]");
				$employee_dependents = array(KEY_DEPENDENTS => $dependents, KEY_TAX_EXEMPT_CODE => $tax_exempt_code, KEY_WIFE_CLAIM_EXEMPT => $wife_claim_exempt);
				return $employee_dependents;
			}			

			// get number of dependents eligible for tax exemption
			for($i = count($dependents) - 1; $i >= 0; $i--)
			{
				$d = $dependents[$i];
				$age 			= $d['age'];
				$pwd_flag 		= $d['pwd_flag'];
				$deceased_flag 	= $d['deceased_flag'];
				$civil_status_id= $d['relation_civil_status_id'];
				$emp_status_id	= $d['relation_employment_status_id'];
				$rel_type_id	= $d['relation_type_id'];
				
				if ($deceased_flag == YES)
				{
					unset($dependents[$i]);
					continue;
				}
				if ($pwd_flag == YES)
				{
					continue;
				}
				if ($age >= $tax_dep_age_limit)
				{
					unset($dependents[$i]);
					continue;
				}
				else
				{
					if ( ! in_array($rel_type_id, $tax_dep_relation_type) )
					{
						unset($dependents[$i]);
						continue;
					}
					
					continue;
				}
				
				if ( ! in_array($civil_status_id, $tax_dep_civil_status) )
				{
					unset($dependents[$i]);
					continue;
				}
				if (! in_array($emp_status_id, $tax_dep_employment_status) )
				{
					unset($dependents[$i]);
					continue;
				}
					
				if ( ! in_array($rel_type_id, $tax_dep_relation_type) )
				{
					unset($dependents[$i]);
					continue;
				}
			}
			
			// get tax exempt status of employee
			$dep_cnt = count($dependents);
			switch($dep_cnt)
			{
				case 0:	break;
				case 1: 
				case 2: 
				case 3: 
				case 4: 
					$tax_exempt_code = $tax_exempt_code . $dep_cnt;
					break;
				default: 
					$tax_exempt_code = $tax_exempt_code . $sys_param_personal_exempt_dep_num;
					break;
			}			
			
		}
		catch (Exception $e)
		{
			RLog::error($e->getMessage());
			throw $e;
		}
		
		//RLog::error("with dependents [$tax_exempt_code] [$wife_claim_exempt] [".count($dependents)."]");
		$employee_dependents = array(KEY_DEPENDENTS => $dependents, KEY_TAX_EXEMPT_CODE => $tax_exempt_code, KEY_WIFE_CLAIM_EXEMPT => $wife_claim_exempt);
		return $employee_dependents;
	}

	private function _get_tax_param_dependents()
	{
		$tax_param_dependents = array();
		
		try
		{
			$sys_param_types = array(PARAM_TAX_DEPENDENT_CIVIL_STATUS, PARAM_TAX_DEPENDENT_EMPLOYMENT_STATUS, PARAM_TAX_DEPENDENT_RELATION_TYPE, PARAM_TAX_DEPENDENT_AGE_LIMIT);
			$tax_param_dependents = $this->common->get_sys_param_by_type($sys_param_types);
			if( ! empty($tax_param_dependents))
				$tax_param_dependents = set_key_value($tax_param_dependents, 'sys_param_type', 'sys_param_value', FALSE);			
		}
		catch (Exception $e)
		{
			RLog::error($e->getMessage());
			throw $e;
		}
		
		return $tax_param_dependents;
	}	
	
	private function _save_employee_dependents($employee_id, $form_2316_id, $dependents)
	{
		try
		{
			$table = $this->common->tbl_form_2316_dependents;
			
			// delete data for given form_2316_id
			$where['form_2316_id']		= $form_2316_id;
			$this->common->delete_general_data($table, $where);			
			foreach ($dependents as $dep)
			{
				$fields = array();
				$fields['form_2316_id']			= $form_2316_id;
				$fields['employee_relation_id']	= $dep['employee_relation_id'];
				$fields['dependent_name']		= $dep['relation_last_name']  . 
													( empty($dep['relation_ext_name']) ? ', ' : (' ' . $dep['relation_ext_name'] . ', ') )  . 
													$dep['relation_first_name'] . ' ' . $dep['relation_middle_name'];
				$fields['birth_date']			= ( empty($dep['relation_birth_date']) ? NULL : $dep['relation_birth_date'] );
				
				// then insert new values
				$this->common->insert_general_data($table, $fields, FALSE, FALSE);
			}
		}
		catch (Exception $e)
		{
			RLog::error($e->getMessage());
			throw $e;
		}
	}	
	
	private function _map_form_2316_details($type, $id, $param_2316_map, $key_delim)
	{
		$map_key = array();
		foreach ($param_2316_map as $key => $val)
		{
			if (in_array($id, $val))
			{
				$key = explode($key_delim, $key);
				if ($key[0] == $type)
				{
					$map_key[] = $key;
				}
			}
		}
		
		return $map_key;
	}

	private function _get_unsaved_stat_deductions($unsaved_deductions, $sys_param_union_dues_id)
	{
		if (empty($unsaved_deductions)) return array();
		try
		{
			$where = array();
			$where['item_code'] = array(array(ITEM_STAT_EMPLOYEE_SHARE, ITEM_UNION_DUES), array("IN"));
			//$where['item_code'] = ITEM_STAT_EMPLOYEE_SHARE;
			$stat_deductions	= $this->common->get_general_data(array('GROUP_CONCAT(detail_id) stat_deduct_ids'), 
									$this->common->tbl_param_form_2316, 
									$where, 
									FALSE);
			$stat_deductions	= $stat_deductions['stat_deduct_ids'];
			$stat_deductions	= explode(",", $stat_deductions);
			
			RLog::error($stat_deductions);

			$unsaved_stat_union	= array();
			$unsaved_stat 		= array();
			$unsaved_union 		= array();

			foreach($unsaved_deductions as $pay_hdr_id => $ddctn)
			{
				foreach($ddctn as $k => $v)
				{
					$deduction_id = $v['deduction_id'];
					if ( ! empty($v['raw_deduction_id']))
						$deduction_id = $v['raw_deduction_id'];

					if (in_array($deduction_id, $stat_deductions))
					{
						if (empty($unsaved_stat[$pay_hdr_id]))
							$unsaved_stat[$pay_hdr_id] = 0;
						if (empty($unsaved_union[$pay_hdr_id]))
							$unsaved_union[$pay_hdr_id] = 0;

						$unsaved_stat[$pay_hdr_id] += $v['amount'];
						if ($v['deduction_id'] == $sys_param_union_dues_id) // if UKKS
							$unsaved_union[$pay_hdr_id] += $v['amount'];
					}
				}	
			}			
			
			$unsaved_stat_union['unsaved_stat'] 	= $unsaved_stat;
			$unsaved_stat_union['unsaved_union'] 	= $unsaved_union;
			
			return $unsaved_stat_union;
		}
		catch (Exception $e)
		{
			RLog::error($e->getMessage());
			throw $e;
		}
	}	
	
}

/* End of file Payroll_form_2316.php */
/* Location: ./application/modules/main/controllers/Payroll_form_2316.php */