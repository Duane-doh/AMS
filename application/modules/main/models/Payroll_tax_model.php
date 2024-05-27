<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Payroll_tax_model extends Main_Model {

	private $db_core   = DB_CORE;
	
	public function __construct() {
		parent:: __construct();
	
	}
	
	public function get_actual_payout($payout_date, $employees=NULL, $sys_param_stat_approved=NULL, $separated_flag=NO)
	{
		$actual_payout = array();
		try
		{
			// get RDO code
			$val	= array(PARAM_DOH_RDO_CODE);
			$query 	= <<<EOS
				SELECT IFNULL(j.sys_param_value, '0') rdo_code FROM $this->DB_CORE.$this->tbl_sys_param j 
				WHERE j.sys_param_type = ?			
EOS;
			$rdo = $this->query($query, $val, FALSE);
			
			$rdo_code = NULL;
			if (! empty($rdo))
				$rdo_code = $rdo['rdo_code'];
			
			
			$val	= array(RESIDENTIAL_ADDRESS, PERMANENT_ADDRESS, RESIDENTIAL_ADDRESS, PERMANENT_ADDRESS,
						$rdo_code, TIN_TYPE_ID, PERMANENT_NUMBER, $payout_date, $payout_date); 
						// $rdo_code, YES, TIN_TYPE_ID, PERMANENT_NUMBER, $payout_date);

			$query 	= <<<EOS
				SELECT 	b.employee_id,
						CONCAT_WS(' ',CONCAT(f.last_name, ','),f.first_name,f.middle_name,f.ext_name) employee_name,
						c.raw_compensation_id compensation_id,
						c.raw_deduction_id deduction_id,
						GROUP_CONCAT(DISTINCT a.payroll_summary_id ORDER BY c.effective_date DESC) payout_summary_ids,
						GROUP_CONCAT(DISTINCT b.payroll_hdr_id ORDER BY c.effective_date DESC) payout_hdr_ids,
						a.payout_type_flag,
						SUM(IFNULL(IF(c.compensation_id IS NOT NULL
										AND c.compensation_id > 0,
									c.amount,
									0),
								0)) compensation_amount,
						SUM(IFNULL(IF(c.deduction_id IS NOT NULL
										AND c.deduction_id > 0,
									c.amount,
									0),
								0)) deduction_amount,
								
						IF(c.compensation_id IS NOT NULL
										AND c.compensation_id > 0,
									c.amount,
								0) compensation_unit_amount,

						COUNT(DISTINCT a.payroll_summary_id) payout_num,
						GROUP_CONCAT(DISTINCT MONTH(c.effective_date)
							ORDER BY c.effective_date
							SEPARATOR ',') payout_months,
						d.employ_monthly_salary basic_amount,
						f.birth_date,
						e.identification_value employee_tin,
						(SELECT 
								GROUP_CONCAT(address_value)
							FROM
								$this->tbl_employee_addresses
							WHERE
								employee_id = d.employee_id
									AND address_type_id IN (?, ?)) AS address_value,
						(SELECT 
								GROUP_CONCAT(postal_number)
							FROM
								$this->tbl_employee_addresses
							WHERE
								employee_id = d.employee_id
									AND address_type_id IN (?, ?)) AS postal_number,
						h.contact_value telephone_num,
						? AS rdo_code,
						i.taxable_amount
				FROM $this->tbl_payout_summary a
				JOIN $this->tbl_payout_header b ON b.payroll_summary_id = a.payroll_summary_id 
				JOIN $this->tbl_payout_details c ON c.payroll_hdr_id = b.payroll_hdr_id 
				JOIN $this->tbl_employee_work_experiences d ON d.employee_id = b.employee_id  
				LEFT JOIN $this->tbl_employee_identifications e ON e.employee_id = b.employee_id AND e.identification_type_id = ? 
				JOIN $this->tbl_employee_personal_info f ON f.employee_id = b.employee_id 
				LEFT JOIN $this->tbl_employee_contacts h ON h.employee_id = b.employee_id AND h.contact_type_id = ? 
				LEFT JOIN $this->tbl_param_compensations i ON i.compensation_id = c.raw_compensation_id AND c.raw_compensation_id IS NOT NULL 

				WHERE c.effective_date <= ?  AND YEAR(c.effective_date) = YEAR(?)
EOS;

			if ( ! empty($employees) && is_array($employees))
			{
				$emp_filter = '';
				foreach($employees as $emp)
				{
					if ( ! empty($emp_filter))
						$emp_filter .= ',';
					
					$emp_filter .= '?';
					$val[] = $emp;
				}				
				
				$query 	.= " AND b.employee_id IN ( $emp_filter )";
			}
			
			if ( ! is_null($sys_param_stat_approved))
			{
				$query .= " AND a.payout_status_id = ? ";
				$val[] = $sys_param_stat_approved;
			}
			
			if ($separated_flag == YES)
			{
				$query .= " AND d.separation_mode_id IS NOT NULL AND d.employ_end_date = ? ";
				$val[] = $payout_date;
			}
			else
			{
				$query .= " AND d.active_flag = ? ";
				$val[] = YES;
			}
			
			$query	.= " GROUP BY employee_id, compensation_id, c.raw_deduction_id, ";
				$query	.= " d.employ_monthly_salary, employee_tin, contact_value ";
			$query	.= " ORDER BY employee_id ASC ";

			$actual_payout 	= $this->query($query, $val, TRUE);
			
			//RLog::error("GET ACTUAL PAYOUT !!!");
			//RLog::error($query);
			//RLog::error($val);
						
			return $actual_payout;			
		}
		catch (PDOException $e)
		{
			RLog::error($e->getMessage());
			throw $e;
		}
		catch (Exception $e)
		{
			RLog::error($e->getMessage());
			throw $e;
		}
	}

	
	public function get_tax_details($payroll_summary_id, $year, $month=0, $employees=NULL)
	{
		try
		{
			$tbl_form_2316_dtl = ($month > 0 ? $this->tbl_form_2316_monthly_details : $this->tbl_form_2316_details);
			
			$fields = array('a.employee_id', 'a.form_2316_id', 'c.payroll_hdr_id', 'IFNULL(b.tax_due, 0) tax_due', 'IFNULL(b.total_tax_withheld, 0) total_tax_withheld');
			
			$tables	= array(
				'main'	=> array(
					'table'		=> $this->tbl_form_2316_header,
					'alias'		=> 'a'
				),
				'table1'=> array(
					'table'		=> $tbl_form_2316_dtl,
					'alias'		=> 'b',
					'type'		=> 'JOIN',
					'condition'	=> 'b.form_2316_id = a.form_2316_id'
				),
				'table2'=> array(
					'table'		=> $this->tbl_payout_header,
					'alias'		=> 'c',
					'type'		=> 'JOIN',
					'condition'	=> 'a.employee_id = c.employee_id'
				)
			);

			$where 				= array();
			$key 				= 'a.year';
			$where[$key]		= $year;
			$key 				= 'c.payroll_summary_id';
			$where[$key]		= $payroll_summary_id;

			if ($month > 0)
			{
				$where['b.year']	= $year;				
				$where['b.month']	= $month;	
			}
			
			if ( ! is_null($employees))
			{
				$key 			= 'a.employee_id';
				if (is_array($employees))
				{
					$where[$key]= array($employees, array('IN'));
				}
				else
				{
					$where[$key]= $employees;
				}
			}
			
			$order_by			= array("a.employee_id" => 'ASC');
			
			return $this->select_all($fields, $tables, $where, $order_by);			
			
		} 
		catch (Exception $e)
		{
			throw $e;
		}
	}
	
	/*
	 * This function selects all taxable benefits of given employees.
	 */
	public function get_taxable_benefits($employees=array())
	{
		try 
		{
			//TODO: SET SESSION FOR GROUP_CONCAT_MAX_LEN
			//$stmt = $this->query("SET SESSION group_concat_max_len = " . GROUP_CONCAT_MAX_LENGTH, NULL, NULL);			
			
			//$fields	= array('b.employee_id', 'a.compensation_id', 'a.compensation_code', 'a.compensation_name', 'a.frequency_id');
			$fields	= array('b.employee_id', 
					'GROUP_CONCAT(a.compensation_id ORDER BY a.compensation_id SEPARATOR \',\') compensation_ids',
					'GROUP_CONCAT(a.compensation_code ORDER BY a.compensation_id SEPARATOR \',\') compensation_codes',
					'GROUP_CONCAT(a.compensation_type_flag ORDER BY a.compensation_id SEPARATOR \',\') compensation_type_flags', 
					'GROUP_CONCAT(IFNULL(a.amount, 0) ORDER BY a.compensation_id SEPARATOR \',\') amounts',
					'GROUP_CONCAT(IFNULL(a.multiplier_id, 0) ORDER BY a.compensation_id SEPARATOR \',\') multiplier_ids',
					'GROUP_CONCAT(IFNULL(a.rate, 0) ORDER BY a.compensation_id SEPARATOR \',\') rates',
					'GROUP_CONCAT(IFNULL(a.pro_rated_flag, \'NA\') ORDER BY a.compensation_id SEPARATOR \',\') pro_rated_flags',
					'GROUP_CONCAT(a.frequency_id ORDER BY a.compensation_id SEPARATOR \',\') frequency_ids',
					'GROUP_CONCAT(a.taxable_amount ORDER BY a.compensation_id SEPARATOR \',\') taxable_amounts');
			$tables	= array(
				'main'	=> array(
					'table'		=> $this->tbl_param_compensations,
					'alias'		=> 'a'
				),
				'table1'=> array(
					'table'		=> $this->tbl_employee_compensations,
					'alias'		=> 'b',
					'type'		=> 'JOIN',
					'condition'	=> 'b.compensation_id = a.compensation_id'
				)
			);
			$where						= array();
			$where['a.taxable_flag']	= YES;
			$where['a.employee_flag']	= YES;
			$where['a.active_flag']		= YES;
			$where['a.basic_salary_flag'] = NO;
			
			if ( ! empty($employees))
			{
				$where['b.employee_id']	= array($employees, array('IN'));
			}
			$order_by	= array('a.frequency_id' => 'ASC', 'a.compensation_id' => 'ASC', 'b.employee_id' => 'ASC');
			$group_by	= array('b.employee_id');
			
			return $this->select_all($fields, $tables, $where, $order_by, $group_by);

		} catch (Exception $e)
		{
			RLog::error($e->getMessage());
			throw $e;			
		}
	}
	
	public function insert_form_2316($form_2316_ids, $payout_details, $tax_table_flag=TAX_ANNUALIZED, $bir_effective_date, $year, $month, $monthly_only=FALSE)
	{
		try
		{
			$fields											= array();

			
			RLog::info('insert_form_2316 ...');
			RLog::info($form_2316_ids);
			// summary and detailed amounts
			
			$test_ctr = 0;
			foreach ($form_2316_ids as $employee_id => $form_2316_id)
			{
				$details 									= $payout_details[$employee_id];
				$mwe_flag									= $details[KEY_MWE_FLAG]; 
	
				$field										= array();
				$field['form_2316_id'] 						= $form_2316_id;

				// non-taxable / exempt
				$field['month_pay_13'] 						= (isset($details['month_pay_13']) ? $details['month_pay_13'] : 0);
				$field['deminimis'] 						= (isset($details['deminimis']) ? $details['deminimis'] : 0);
				$field['statutory_employee_share'] 			= (isset($details['statutory_employee_share']) ? $details['statutory_employee_share'] : 0);
				$field['salary_other_compensation'] 		= (isset($details['salary_other_compensation']) ? $details['salary_other_compensation'] : 0);
				$field['total_exempt_income'] 				= $field['month_pay_13'] + $field['deminimis'] 
																+ $field['statutory_employee_share']
																+ $field['salary_other_compensation'];
				if ($mwe_flag)
				{
					$field['mwe_basic_salary'] 				= (isset($details[ITEM_MWE_BASIC_SALARY]) ? $details[ITEM_MWE_BASIC_SALARY] : 0);
					$field['mwe_hazard_pay'] 				= (isset($details[ITEM_MWE_HAZARD_PAY]) ? $details[ITEM_MWE_HAZARD_PAY] : 0);
					$field['total_exempt_income'] 			+= $field['mwe_basic_salary'] + $field['mwe_hazard_pay'];
					$field['basic_salary'] 					= 0;
					$field['hazard_pay'] 					= 0;
				}
				else 
				{
					$field['mwe_basic_salary'] 				= 0;
					$field['mwe_hazard_pay'] 				= 0;
					$field['basic_salary'] 					= (isset($details['basic_salary']) ? $details['basic_salary'] : 0);
					$field['hazard_pay'] 					= (isset($details['hazard_pay']) ? $details['hazard_pay'] : 0);
				}

				// taxable
				$field['representation'] 					= (isset($details['representation']) ? $details['representation'] : 0);
				$field['transportation'] 					= (isset($details['transportation']) ? $details['transportation'] : 0);
				$field['cost_of_living_allowance'] 			= (isset($details['cost_of_living_allowance']) ? $details['cost_of_living_allowance'] : 0);
				$field['fixed_housing_allowance'] 			= (isset($details['fixed_housing_allowance']) ? $details['fixed_housing_allowance'] : 0);
				
				if (isset($details['others_taxable']) && $details['others_taxable'] > 0)
				{
					$field['others_taxable_name_47a'] 		= 'Other taxable income';
					$field['others_taxable_47a'] 			= $details['others_taxable'];
				}
				else
				{
					$field['others_taxable_name_47a'] 		= NULL;
					$field['others_taxable_47a'] 			= NULL;				
				}
				
				$field['commission'] 						= (isset($details['commission']) ? $details['commission'] : 0);
				$field['profit_sharing'] 					= (isset($details['profit_sharing']) ? $details['profit_sharing'] : 0);
				$field['fees'] 								= (isset($details['fees']) ? $details['fees'] : 0);
				$field['taxable_month_pay_13'] 				= (isset($details['taxable_month_pay_13']) ? $details['taxable_month_pay_13'] : 0);
				$field['overtime_pay'] 						= (isset($details['overtime_pay']) ? $details['overtime_pay'] : 0);

				if (isset($details['other_taxable_supp']) && $details['other_taxable_supp'] > 0)
				{
					$field['others_taxable_name_54a'] 		= 'Other taxable supplementary';;
					$field['others_taxable_54a'] 			= $details['other_taxable_supp'];					
				}
				else
				{
					$field['others_taxable_name_54a'] 		= NULL;
					$field['others_taxable_54a'] 			= NULL;					
				}
				
				$field['total_taxable_income'] 				= $field['basic_salary'] 
																//+ $field['representation'] + $field['transportation']
																+ $field['cost_of_living_allowance'] + $field['fixed_housing_allowance']
																+ $field['commission'] + $field['profit_sharing']
																+ $field['fees'] + $field['taxable_month_pay_13']
																+ $field['hazard_pay'] + $field['overtime_pay']
																+ $field['others_taxable_47a'] + $field['others_taxable_54a'];
				
				
				$field['gross_income_present_employer'] 	= $field['total_exempt_income'] + $field['total_taxable_income'];
				$field['total_non_tax_exemption']			= $field['total_exempt_income'];
				$field['taxable_income_present_employer'] 	= $field['total_taxable_income'];
				$field['taxable_income_previous_employer']	= (isset($details['taxable_income_previous_employer']) ? $details['taxable_income_previous_employer'] : 0);
				$field['gross_taxable_income']				= $field['taxable_income_present_employer'] + $field['taxable_income_previous_employer'];
				$field['total_personal_exemption']			= (isset($details['total_personal_exemption']) ? $details['total_personal_exemption'] : 0);
				$field['health_insurance_paid']				= (isset($details['health_insurance_paid']) ? $details['health_insurance_paid'] : 0);
				if ($tax_table_flag == TAX_ANNUALIZED)
				{
					$field['net_taxable_income'] 			= $field['gross_taxable_income'] - $field['total_personal_exemption'] - $field['health_insurance_paid'];
				}
				else {
					$field['net_taxable_income'] 			= $field['gross_taxable_income'] - $field['health_insurance_paid'];
				}

				$field['tax_due']							= 0.00;
				if ($field['net_taxable_income'] < 0)
					$field['net_taxable_income']			= 0.00;
				else
					$field['tax_due'] 						= $this->_get_tax_due($tax_table_flag, $bir_effective_date, $form_2316_id, $field['net_taxable_income']);					
				
				$field['tax_withheld_present_employer'] 	= (isset($details['tax_withheld_present_employer']) ? $details['tax_withheld_present_employer'] : 0);
				$field['tax_withheld_previous_employer'] 	= (isset($details['tax_withheld_previous_employer']) ? $details['tax_withheld_previous_employer'] : 0);
				$field['total_tax_withheld'] 				= $field['tax_withheld_present_employer'] + $field['tax_withheld_previous_employer'];				
				
				if ( ! $monthly_only)
					$fields['annual'][] = $field;
				
				$field['year']	= $year;
				$field['month']	= $month;
				$fields['monthly'][] = $field;
				
				$test_ctr++;
				if ($test_ctr > 500)
				{
					if ( ! $monthly_only)
						$this->insert_data($this->tbl_form_2316_details, $fields['annual'], FALSE, TRUE);
						
					$this->insert_data($this->tbl_form_2316_monthly_details, $fields['monthly'], FALSE, TRUE);

					// reset values
					$fields		= array();				
					$test_ctr	= 0;
				}
			}
			
			if ( ! $monthly_only)
				$this->insert_data($this->tbl_form_2316_details, $fields['annual'], FALSE, TRUE);
				
			$this->insert_data($this->tbl_form_2316_monthly_details, $fields['monthly'], FALSE, TRUE);				

			return TRUE;
		}
		catch (PDOException $e)
		{
			RLog::error($e->getMessage() . " [$employee_id]");
			throw $e;
		}
		catch (Exception $e)
		{
			RLog::error($e->getMessage() . " [$employee_id]");
			throw $e;
		}
	}
	
	private function _get_tax_due($tax_table_flag, $bir_effective_date, $form_2316_id, $taxable_income, $limit_one=FALSE)
	{
		$tax_due = 0;
		
		try
		{
			if ( ! isset($bir_effective_date))
				$bir_effective_date = date('Y-m-d');
			
			//RLog::error("S: Payroll_model._compute_tax_due [$tax_table_flag][$bir_effective_date] [$form_2316_id] [$taxable_income] [$limit_one]");
			
			// get tax table
			$exempt_stat_code = NULL;
			if ($tax_table_flag == TAX_MONTHLY_2316)
			{
				// get number of dependents
				$field	= array("COUNT(1) dep_cnt");
				$table	= $this->tbl_form_2316_dependents;
				$where	= array('form_2316_id' => $form_2316_id);
				$dep_cnt	= $this->select_one($field, $table, $where);
				$dep_cnt	= (! empty($dep_cnt)) ? $dep_cnt['dep_cnt'] : 0;
				
				//RLog::error("--DEPENDENTS: [$form_2316_id] [$dep_cnt]");
				
				switch($dep_cnt)
				{
					case 0:	$exempt_stat_code = TAX_EXEMPT_STAT_SME; break;
					case 1: $exempt_stat_code = TAX_EXEMPT_STAT_SME1; break;
					case 2: $exempt_stat_code = TAX_EXEMPT_STAT_SME2; break;
					case 3: $exempt_stat_code = TAX_EXEMPT_STAT_SME3; break;
					case 4: $exempt_stat_code = TAX_EXEMPT_STAT_SME4; break;
					default: $exempt_stat_code = TAX_EXEMPT_STAT_SME4; break; // for more than 4 children
				}
				
			}
			$bir_table = $this->get_bir_table($tax_table_flag, $bir_effective_date, $exempt_stat_code, $taxable_income, $limit_one);
			
			if ( ! isset($bir_table) OR count($bir_table) == 0)
			{
				throw new Exception($this->lang->line('sys_param_not_defined'));
			}
			
			// compute tax based on taxable income
			foreach ($bir_table as $table)
			{
				$table['max_amount'] = ($table['max_amount'] == 0 ? $taxable_income : $table['max_amount']);
				if (is_between_num($taxable_income, $table['min_amount'], $table['max_amount']))
				{
					//RLog::info('-- bracket used: ['.$form_2316_id.'] ['.$taxable_income.'] ['.$table['min_amount'].'] ['.$table['max_amount'].']');
					
					$tax_amount = $table['tax_amount'];
					$tax_rate = $table['tax_rate'];
					
					// get annual tax
					$tax_due = round( (($taxable_income - $table['min_amount']) * $tax_rate), 2, PHP_ROUND_HALF_UP);
					$tax_due += $tax_amount;
					
					//RLog::info("S: Payroll_general_tab._compute_tax_due [$bir_effective_date] [$form_2316_id] [$tax_due]");
					
					break;
				}
			}
			
			return $tax_due;
		}
		catch (Exception $e)
		{
			RLog::error($e->getMessage());
			throw $e;
		}

	}
	
	public function update_form_2316_tax_withheld($form_2316_id, $year, $month, $monthly_tax=0)
	{
		try
		{
			$val = array($form_2316_id, $year, $month);
			$query = <<<EOS
				UPDATE $this->tbl_form_2316_monthly_details a
					SET a.tax_withheld_present_employer = (a.tax_withheld_present_employer + $monthly_tax),
						a.total_tax_withheld = (a.tax_withheld_present_employer + a.tax_withheld_previous_employer)
				WHERE form_2316_id = ? AND year = ? AND month = ?
EOS;
			$stmt = $this->query($query, $val, NULL);
		
			return $stmt;
		}
		catch (PDOException $e)
		{
			RLog::error($e->getMessage());
			throw $e;
		}
	}	

	
	/**
	 * This function retrieves all qualified dependents of given employee.
	 * @access public
	 * @param $params
	 * @param $order_by
	 */	
	public function get_employee_dependents($params)
	{
		try 
		{
			$current_date		= ( empty($params['payout_date']) ? date('Y-m-d') :  $params['payout_date'] );
			
			$fields 			= array('employee_relation_id', 'employee_id', 'relation_type_id', 
									'relation_first_name', 'relation_middle_name', 'relation_last_name',
									'relation_ext_name', 'relation_gender_code', 'relation_birth_date',
									'relation_civil_status_id', 'relation_employment_status_id', 
									'pwd_flag', 'deceased_flag',
									'TIMESTAMPDIFF(YEAR, relation_birth_date, \''.$current_date.'\') age');

			$table				= $this->tbl_employee_relations;

			$where				= array();
			IF (isset($params['employee_id']))
			{
				$key 			= 'employee_id';
				IF (is_array($params['employee_id']))
				{
					$where[$key]	= array($params['employee_id'], array('IN'));
				}
				ELSE
				{
					$where[$key]	= $params['employee_id'];
				}
			}
			IF (isset($params['bir_flag']))
			{
				$key 			= 'bir_flag';
				$where[$key]	= $params['bir_flag'];
			}
			IF (isset($params['gsis_flag']))
			{
				$key 			= 'gsis_flag';
				$where[$key]	= $params['gsis_flag'];
			}
			IF (isset($params['pagibig_flag']))
			{
				$key 			= 'pagibig_flag';
				$where[$key]	= $params['pagibig_flag'];
			}
			IF (isset($params['philhealth_flag']))
			{
				$key 			= 'philhealth_flag';
				$where[$key]	= $params['philhealth_flag'];
			}

			return $this->select_all($fields, $table, $where);
		}
		catch (Exception $e)
		{
			RLog::error($e->getMessage());
			throw $e;
		}
	}
	
	public function get_tax_param_dependents()
	{
		try 
		{
			$fields 	= array('sys_param_type', 'GROUP_CONCAT(sys_param_value) sys_param_value');
			$table		= $this->db_core . '.' . $this->tbl_sys_param;

			$where						= array();
			$tax_param					= array(PARAM_TAX_DEPENDENT_CIVIL_STATUS, PARAM_TAX_DEPENDENT_EMPLOYMENT_STATUS, PARAM_TAX_DEPENDENT_RELATION_TYPE, PARAM_TAX_DEPENDENT_AGE_LIMIT);
			$where['sys_param_type'] 	= array($tax_param, array('IN'));
			$group_by					= array('sys_param_type');

			return $this->select_all($fields, $table, $where, array(), $group_by);
		}
		catch (Exception $e)
		{
			RLog::error($e->getMessage());
			throw $e;
		}
	}

	public function get_bir_table($tax_table_flag, $effective_date, $exempt_stat_code=NULL, $taxable_income=0, $limit_one=FALSE)
	{
		$data = array();
		try
		{
			$fields = array('bir_id');
			$table = $this->tbl_param_bir;
			$where['active_flag'] = YES;
			$where['tax_table_flag'] = $tax_table_flag;
			$where['effective_date'] = array($effective_date, array('<='));
			$order_by = array('effective_date' => 'ASC');
			$limit = 'LIMIT 1';
			$bir = $this->select_one($fields, $table, $where, $order_by, NULL, $limit);

			if (isset($bir))
			{
				$fields = array('bir_id', 'IFNULL(min_amount, 0) min_amount', 'IFNULL(max_amount, 0) max_amount', 
						'IFNULL(tax_amount, 0) tax_amount', 'IFNULL(tax_rate, 0) tax_rate', 'exempt_status_code', 'vat_flag');
				$table = $this->tbl_param_bir_details;
				$where = array();
				$where['bir_id'] = $bir['bir_id'];
				if ( ! empty($exempt_stat_code))
				{
					$where['exempt_status_code'] = $exempt_stat_code;
				}
				$order_by	= array('min_amount' => 'DESC');
				$limit		= '';
				if ($limit_one && $taxable_income > 0)
				{
					$where['min_amount'] = array(array($taxable_income), array('<='));
					$limit = 'LIMIT 1';
				}

				$data = $this->select_all($fields, $table, $where, $order_by, NULL, $limit);

			}
						
			return $data;
		}	
		catch (Exception $e)
		{			
			$this->rlog_error($e);
		}
	}

	public function get_form_2316_by_ids($val, $form_2316_ids=NULL, $included_employees=NULL, $year=0, $month=0, $monthly_only=FALSE)
	{
		try
		{
			if ($form_2316_ids == NULL && $included_employees == NULL)
				throw new Exception('Form 2316 ID: ' . $this->lang->line('param_not_defined'));
			if ( ! empty($included_employees) && empty($year))
				throw new Exception('Form 2316 Year: ' . $this->lang->line('param_not_defined'));
				
			$dtl_table = ($monthly_only ? $this->common_model->tbl_form_2316_monthly_details : $this->common_model->tbl_form_2316_details);
			
			$field = array('DISTINCT a.form_2316_id', 'a.employee_id', 
					'b.tax_due', 'b.total_tax_withheld', 
					'(b.tax_due - b.total_tax_withheld) tax_diff');
			$tables	= array(
				'main'	=> array(
					'table'		=> $this->common_model->tbl_form_2316_header,
					'alias'		=> 'a'
				),
				'table1'=> array(
					'table'		=> $dtl_table,
					'alias'		=> 'b',
					'type'		=> 'JOIN',
					'condition'	=> 'b.form_2316_id = a.form_2316_id'
				)
			);				
				
			$where = array();
			if ( ! empty($form_2316_ids))
				$where['a.form_2316_id'] = array($form_2316_ids, array('IN'));
			if ( ! empty($included_employees))
			{
				$where['a.employee_id'] = array($included_employees, array('IN'));
				$where['a.year'] = $year;
			}
			if ($monthly_only)
				$where['b.month'] = $month;
				
			$form_2316 = $this->common_model->get_general_data($field, $tables, $where);
				
			if ( ! empty($form_2316))
			{
				$is_val_arr = is_array($val);
				return set_key_value($form_2316, 'employee_id', $val, $is_val_arr);
			}
			else
				return array();
		}	
		catch (Exception $e)
		{
			RLog::error($e->getMessage());
			throw $e;
		}
	}

	
	public function get_emp_pay_info_by_payout($payroll_summary_id, $employment_type_tenure, $selected_employees=array())
	{
		try
		{
			$val 	= array($payroll_summary_id);
			$fields = "A.payroll_hdr_id, A.employee_id, A.salary_grade, A.pay_step, B.anniv_emp_date, A.basic_amount";
			
			$query = <<<EOS
				SELECT  $fields 
				FROM $this->tbl_payout_header A
				JOIN (SELECT b.employee_id, MIN(b.employ_start_date) anniv_emp_date 
					FROM $this->tbl_employee_work_experiences b
					WHERE b.employ_type_flag IN ('$employment_type_tenure')
					GROUP BY b.employee_id) B ON B.employee_id = A.employee_id
				WHERE A.payroll_summary_id = ?
EOS;

			if( ! empty($selected_employees))
			{
				$query .= " AND A.employee_id IN (NULL";
					foreach($selected_employees as $k=>$e)
					{
						$query .= ",?";
						$val[] = $e;
					}
				$query .= ")";
			}

			$stmt = $this->query($query, $val, TRUE);
						
			return $stmt;
		}
		catch (PDOException $e)
		{
			$this->rlog_error($e);
			throw $e;
		}
		catch (Exception $e)
		{			
			$this->rlog_error($e);
			throw $e;
		}
	}

	public function get_emp_pay_info_by_employee($employees, $employment_type_tenure, $payout_date, $separated_flag=NO)
	{
		try
		{
			$tbl_anniv_emp 	= <<<EOS
					(SELECT B.employee_id, MIN(B.employ_start_date) anniv_emp_date 
					FROM $this->tbl_employee_work_experiences B
					WHERE B.employ_type_flag IN ('$employment_type_tenure')
					GROUP BY B.employee_id)
EOS;
			
			
			$field = array('DISTINCT A.employee_id', 'A.employ_salary_grade salary_grade', 'A.employ_salary_grade pay_step', 'B.anniv_emp_date', 'A.employ_monthly_salary basic_amount');
			$tables	= array(
				'main'	=> array(
					'table'		=> $this->tbl_employee_work_experiences,
					'alias'		=> 'A'
				),
				'table1'=> array(
					'table'		=> $tbl_anniv_emp,
					'alias'		=> 'b',
					'type'		=> 'JOIN',
					'condition'	=> 'A.employee_id = B.employee_id'
				)
			);
			
			$where = array();
			$group_by	= NULL;
			$order_by	= NULL;
			if ($separated_flag == YES)
			{
				$where['A.employ_end_date'] = array(array($payout_date), array('<='));
				$group_by	= array('A.employee_id');
				$order_by	= array('A.employee_id' => 'DESC');
			}
			else
				$where['A.active_flag'] = YES;
			$where['A.employee_id'] = array($employees, array('IN'));

			return $this->select_all($field, $tables, $where, $order_by, $group_by);
		}
		catch (PDOException $e)
		{
			$this->rlog_error($e);
			throw $e;
		}
		catch (Exception $e)
		{			
			$this->rlog_error($e);
			throw $e;
		}
	}	

	public function get_employee_2307_header_info($payroll_summary_id, $pay_dte_obj, $included_employees=array())
	{
		try
		{
			$pay_year	= $pay_dte_obj->format('Y');
			$pay_month	= $pay_dte_obj->format('m');
			
			// get RDO code
			// $val	= array(PARAM_DOH_RDO_CODE, PARAM_COMPENSATION_ID_BASIC_SALARY, PARAM_COMPENSATION_ID_BASIC_SALARY_ADJUSTMENT);
			
			// $query 	= <<<EOS
				// SELECT sys_param_type, IFNULL(j.sys_param_value, '0') sys_param_value FROM $this->DB_CORE.$this->tbl_sys_param j 
				// WHERE j.sys_param_type IN (?, ?, ?)	
// EOS;

			// ====================== jendaigo : start : include COMPENSATION_ID_PREMIUM ============= //
			$val	= array(PARAM_DOH_RDO_CODE, PARAM_COMPENSATION_ID_BASIC_SALARY, PARAM_COMPENSATION_ID_BASIC_SALARY_ADJUSTMENT, PARAM_COMPENSATION_ID_PREMIUM);
			
			$query 	= <<<EOS
				SELECT sys_param_type, IFNULL(j.sys_param_value, '0') sys_param_value FROM $this->DB_CORE.$this->tbl_sys_param j 
				WHERE j.sys_param_type IN (?, ?, ?, ?)			
EOS;
			// ====================== jendaigo : end : include COMPENSATION_ID_PREMIUM ============= //
			
			$sys_params = $this->query($query, $val, TRUE);
			
			$rdo_code 		= NULL;
			$salary_code 	= NULL;
			$salary_adj_code= NULL;
			
			if (! empty($sys_params))
			{
				foreach ($sys_params as $sp)
				{
					if ($sp['sys_param_type'] == PARAM_DOH_RDO_CODE)
						$rdo_code 		= $sp['sys_param_value'];
					else if ($sp['sys_param_type'] == PARAM_COMPENSATION_ID_BASIC_SALARY)
						$salary_code 	= $sp['sys_param_value'];
					else if ($sp['sys_param_type'] == PARAM_COMPENSATION_ID_BASIC_SALARY_ADJUSTMENT)
						$salary_adj_code= $sp['sys_param_value'];
					// ====================== jendaigo : start : include COMPENSATION_ID_PREMIUM ============= //
					else if ($sp['sys_param_type'] == PARAM_COMPENSATION_ID_PREMIUM)
						$salary_prem= $sp['sys_param_value'];
					// ====================== jendaigo : end : include COMPENSATION_ID_PREMIUM ============= //
				}
			}
			
			// $val	= array(NO, RESIDENTIAL_ADDRESS, PERMANENT_ADDRESS, RESIDENTIAL_ADDRESS, PERMANENT_ADDRESS, 
						// $rdo_code, YES, TIN_TYPE_ID, PERMANENT_NUMBER,
						// $pay_year, $pay_month, $salary_code, $salary_adj_code,
						// $pay_year, $pay_month, $salary_code, $salary_adj_code,
						// $payroll_summary_id);
			// ====================== jendaigo : start : include salary_prem ============= //			
			$val	= array(NO, RESIDENTIAL_ADDRESS, PERMANENT_ADDRESS, RESIDENTIAL_ADDRESS, PERMANENT_ADDRESS, 
						$rdo_code, YES, TIN_TYPE_ID, PERMANENT_NUMBER,
						$pay_year, $pay_month, $salary_code, $salary_adj_code, $salary_prem,
						$pay_year, $pay_month, $salary_code, $salary_adj_code, $salary_prem,
						$payroll_summary_id);
			// ====================== jendaigo : end : include salary_prem ============= //			
/*
			$query 	= <<<EOS
				SELECT A.payroll_hdr_id, A.employee_id, A.employee_name, IFNULL(A.basic_amount, 0) basic_amount, F.actual_income, F.num_payout,
					G.monthly_actual_amount,
					B.birth_date,
					IFNULL(C.other_info_type_id, ?) professional_flag,
					D.identification_value employee_tin,
					(SELECT 
							GROUP_CONCAT(address_value)
						FROM
							$this->tbl_employee_addresses
						WHERE
							employee_id = d.employee_id
								AND address_type_id IN (?, ?)) AS address_value,
					(SELECT 
							GROUP_CONCAT(postal_number)
						FROM
							$this->tbl_employee_addresses
						WHERE
							employee_id = d.employee_id
								AND address_type_id IN (?, ?)) AS postal_number,
					E.contact_value telephone_num,
					? as rdo_code
				FROM 
					payout_header A JOIN employee_personal_info B ON A.employee_id = B.employee_id
					LEFT JOIN (
						SELECT C.employee_id, C.other_info_type_id FROM employee_other_info C 
							JOIN param_other_info_types D ON C.other_info_type_id = D.other_info_type_id 
								AND D.info_professional_flag = ? GROUP BY C.employee_id
						) C ON C.employee_id = B.employee_id
					LEFT JOIN employee_identifications D ON D.employee_id = A.employee_id AND D.identification_type_id = ? 
					LEFT JOIN employee_contacts E ON E.employee_id = A.employee_id AND E.contact_type_id = ?
					
					LEFT JOIN (
						SELECT y.payroll_hdr_id, SUM(y.amount) actual_income, COUNT(DISTINCT y.payroll_hdr_id) num_payout 
						FROM payout_details y 
						WHERE YEAR(y.effective_date) = ? AND MONTH(y.effective_date) = ?
							AND y.compensation_id IN (?, ?)
						GROUP BY payroll_hdr_id
					) F ON F.payroll_hdr_id = A.payroll_hdr_id
					
					LEFT JOIN (
						SELECT z.employee_id, SUM(y.amount) monthly_actual_amount
						FROM payout_details y JOIN payout_header z ON y.payroll_hdr_id = z.payroll_hdr_id
						WHERE YEAR(y.effective_date) = ? AND MONTH(y.effective_date) = ?
							AND y.compensation_id IN (?, ?)
						GROUP BY z.employee_id
					) G ON G.employee_id = A.employee_id
					
				WHERE
					A.payroll_summary_id = ?
EOS;
*/
			// ====================== jendaigo : start : include premium ============= //
			$query 	= <<<EOS
				SELECT A.payroll_hdr_id, A.employee_id, A.employee_name, IFNULL(A.basic_amount, 0) basic_amount, F.actual_income, F.num_payout,
					G.monthly_actual_amount,
					B.birth_date,
					IFNULL(C.other_info_type_id, ?) professional_flag,
					D.identification_value employee_tin,
					(SELECT 
							GROUP_CONCAT(address_value)
						FROM
							$this->tbl_employee_addresses
						WHERE
							employee_id = d.employee_id
								AND address_type_id IN (?, ?)) AS address_value,
					(SELECT 
							GROUP_CONCAT(postal_number)
						FROM
							$this->tbl_employee_addresses
						WHERE
							employee_id = d.employee_id
								AND address_type_id IN (?, ?)) AS postal_number,
					E.contact_value telephone_num,
					? as rdo_code
				FROM 
					payout_header A JOIN employee_personal_info B ON A.employee_id = B.employee_id
					LEFT JOIN (
						SELECT C.employee_id, C.other_info_type_id FROM employee_other_info C 
							JOIN param_other_info_types D ON C.other_info_type_id = D.other_info_type_id 
								AND D.info_professional_flag = ? GROUP BY C.employee_id
						) C ON C.employee_id = B.employee_id
					LEFT JOIN employee_identifications D ON D.employee_id = A.employee_id AND D.identification_type_id = ? 
					LEFT JOIN employee_contacts E ON E.employee_id = A.employee_id AND E.contact_type_id = ?
					
					LEFT JOIN (
						SELECT y.payroll_hdr_id, SUM(y.amount) actual_income, COUNT(DISTINCT y.payroll_hdr_id) num_payout 
						FROM payout_details y 
						WHERE YEAR(y.effective_date) = ? AND MONTH(y.effective_date) = ?
							AND y.compensation_id IN (?, ?, ?)
						GROUP BY payroll_hdr_id
					) F ON F.payroll_hdr_id = A.payroll_hdr_id
					
					LEFT JOIN (
						SELECT z.payroll_hdr_id, z.employee_id, SUM(y.amount) monthly_actual_amount
						FROM payout_details y JOIN payout_header z ON y.payroll_hdr_id = z.payroll_hdr_id
						WHERE YEAR(y.effective_date) = ? AND MONTH(y.effective_date) = ?
							AND y.compensation_id IN (?, ?, ?)
						GROUP BY z.payroll_hdr_id
					) G ON G.payroll_hdr_id = A.payroll_hdr_id
					
				WHERE
					A.payroll_summary_id = ?
EOS;
			// ====================== jendaigo : end : include premium ============= //
			
			if ( ! empty($included_employees) && is_array($included_employees))
			{
				$emp_filter = '';
				foreach($included_employees as $emp)
				{
					if ( ! empty($emp_filter))
						$emp_filter .= ',';
					
					$emp_filter .= '?';
					$val[] = $emp;
				}				
				
				$query 	.= " AND A.employee_id IN ( $emp_filter )";
			}
			
			//RLog::error($query);
			
			$stmt 	= $this->query($query, $val, TRUE);
						
			return $stmt;
		}
		catch (PDOException $e)
		{
			$this->rlog_error($e);
			throw $e;
		}
		catch (Exception $e)
		{			
			$this->rlog_error($e);
			throw $e;
		}
	}
	
	public function get_form_2307_ids($year, $quarter, $included_employees=array())
	{
		try
		{
			$fields = array('form_2307_id', 'employee_id');
			$table	= $this->tbl_form_2307_header;
			
			$where = array();
			$where['year'] 		= $year;
			$where['quarter'] 	= $quarter;
			if ( ! empty($included_employees) && is_array($included_employees))
				$where['employee_id'] 	= array($included_employees, array('IN'));
			
			
			$group_by			= array('year', 'quarter', 'employee_id');
			
			$form_2307 = $this->select_all($fields, $table, $where, NULL, $group_by);
			
			return set_key_value($form_2307, 'employee_id', 'form_2307_id', FALSE);
		}
		catch (PDOException $e)
		{
			$this->rlog_error($e);
			throw $e;
		}
		catch (Exception $e)
		{			
			$this->rlog_error($e);
			throw $e;
		}
	}
	
	public function get_employee_tax_exemption($sys_param_bir_id, $sys_param_husband_exception, $sys_param_wife_exception, $included_employees=array())
	{
		try
		{
			$val	= array($sys_param_bir_id, $sys_param_husband_exception, $sys_param_wife_exception);

			$query 	= <<<EOS
				SELECT A.employee_id, A.civil_status_id, A.gender_code,
					C.other_deduction_detail_id AS claim_exempt 
				FROM employee_personal_info A 
					JOIN employee_deductions B ON B.employee_id = A.employee_id AND B.deduction_id = ?
					LEFT JOIN employee_deduction_other_details C ON C.employee_deduction_id = B.employee_deduction_id 
						AND C.other_deduction_detail_id IN (?, ?)
EOS;

			if ( ! empty($included_employees) && is_array($included_employees))
			{
				$emp_filter = '';
				foreach($included_employees as $emp)
				{
					if ( ! empty($emp_filter))
						$emp_filter .= ',';
					
					$emp_filter .= '?';
					$val[] = $emp;
				}				
				
				$query 	.= " AND A.employee_id IN ( $emp_filter )";
			}
			
			$query	.= " GROUP BY A.employee_id  ";

			$stmt 	= $this->query($query, $val, TRUE);
						
			return $stmt;
		}
		catch (PDOException $e)
		{
			RLog::error($e->getMessage());
			throw $e;
		}
		catch (Exception $e)
		{
			RLog::error($e->getMessage());			
			throw $e;
		}
	}
	
	public function get_form_2307_info($payroll_summary_id, $year, $month, $included_employees=array())
	{
		try
		{
			$val = array($payroll_summary_id, $year, $month, $year);
			
			$query 	= <<<EOS
				SELECT A.employee_id, A.professional_flag, B.payroll_hdr_id, C.tax_withheld_2307, C.tax_withheld_2306
				FROM $this->tbl_form_2307_header A
					JOIN $this->tbl_payout_header B ON B.employee_id = A.employee_id AND B.payroll_summary_id = ?
					JOIN $this->tbl_form_2307_monthly_details C ON C.form_2307_id = A.form_2307_id AND C.year = ? AND C.month = ?
				WHERE A.year = ?
EOS;

			if ( ! empty($included_employees) && is_array($included_employees))
			{
				$emp_filter = '';
				foreach($included_employees as $emp)
				{
					if ( ! empty($emp_filter))
						$emp_filter .= ',';
					
					$emp_filter .= '?';
					$val[] = $emp;
				}				
				
				$query 	.= " AND A.employee_id IN ( $emp_filter )";
			}
			
			$stmt 	= $this->query($query, $val, TRUE);
						
			return $stmt;
		}
		catch (PDOException $e)
		{
			$this->rlog_error($e);
			throw $e;
		}
		catch (Exception $e)
		{			
			$this->rlog_error($e);
			throw $e;
		}
	}

	public function get_payout_amount($payout_hdr_id, $compensation_id)
	{
		$payout_amount = 0;
		try
		{
			$val	= array($payout_hdr_id);
			
			$query 	= <<<EOS
				SELECT SUM(amount) total_amount 
				FROM payout_details 
				WHERE payroll_hdr_id = ? 
EOS;

			if (isset($compensation_id))
			{
				$query .= " AND compensation_id = ? ";
				$val[] = $compensation_id;
			}
			
			$query .= ' GROUP BY payroll_hdr_id ';			
			
			$actual_payout = $this->query($query, $val, FALSE);
			
			//RLog::error("GET PAYOUT AMOUNT LONGE [$query] [$payout_hdr_id][$compensation_id]");
			//RLog::error($actual_payout);
			
			if (! empty($actual_payout))
				$payout_amount = $actual_payout['total_amount'];
				
		}
		catch (PDOException $e)
		{
			$this->rlog_error($e);
			throw $e;
		}
		catch (Exception $e)
		{			
			$this->rlog_error($e);
			throw $e;
		}

		return $payout_amount;
	}	
	
	// ====================== jendaigo : start : include getting of payroll data ============= //
	public function get_payroll_data($fields, $table, $where = array(), $multiple = TRUE, $order_by = array(), $group_by = array(), $limit = NULL)
	{
		try
		{
			if($multiple)
			{
				return $this->select_all($fields, $table, $where, $order_by, $group_by, $limit);
			}
			else
			{
				return $this->select_one($fields, $table, $where, $order_by, $group_by, $limit);
			}
		}
		catch(PDOException $e)
		{
			throw $e;
		}
	}
	// ====================== jendaigo : start : include getting of payroll data ============= //
}