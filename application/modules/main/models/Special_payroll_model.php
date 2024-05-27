<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Special_payroll_model extends Main_Model {

	public $db_core   = DB_CORE;
	public $tbl_users = "users";
	
	public function __construct() {
		parent:: __construct();
	}
	
	/** 
	* This function retrieves special payroll records based on given parameters. 
	* 
	* @access public 
	* @param array $aColumns Columns included in the result set
	* @param array $bColumns Columns used in ordering the result set
	* @param array $params Criteria used in filtering the result set
	* @return array List of special payroll records
	*/
	public function get_special_payroll_list ($aColumns, $bColumns, $params, $multiple=TRUE)
	{
// 		try {
// 			if ( ! empty($params['E-effective_date']))
// 			{
// 				$dt = new DateTime($params['E-effective_date']);
// 				$params['E-effective_date'] = $dt->format('Y-m-d');
// 			}
// 		} catch (Exception $e)
// 		{
// 		}		
		
		$stmt = array();
		
		try
		{
			if ( ! empty($params['E-effective_date']))
			{
// 				$dt = new DateTime($params['E-effective_date']);
// 				$params['E-effective_date'] = $dt->format('Y-m-d');
				$params["DATE_FORMAT(E-effective_date, '%Y/%m/%d')"] = $params['E-effective_date'];
			}
			$val = array(0 => PAYOUT_TYPE_FLAG_SPECIAL);
			
			$fields = str_replace(" , ", " ", implode(", ", $aColumns));
			
			$cColumns = array("D-compensation_name", "DATE_FORMAT(E-effective_date, '%Y/%m/%d')", "F-payout_status_name");
			$fields = str_replace(" , ", " ", implode(", ", $aColumns));			
			
			$sWhere = $this->filtering($cColumns, $params, TRUE);
			$sOrder = $this->ordering($bColumns, $params);
			$sLimit = $this->paging($params);
			
			$filter_str = $sWhere["search_str"];
			$filter_params = $sWhere["search_params"];

			$query = <<<EOS
				SELECT SQL_CALC_FOUND_ROWS $fields 
					FROM $this->tbl_payout_summary A LEFT JOIN $this->db_core.$this->tbl_users U ON A.processed_by = U.user_id
					LEFT JOIN $this->tbl_param_compensations D ON A.compensation_id = D.compensation_id 
					LEFT JOIN $this->tbl_payout_header B ON B.payroll_summary_id = A.payroll_summary_id
					LEFT JOIN $this->tbl_payout_details C ON C.payroll_hdr_id = B.payroll_hdr_id,
					$this->tbl_payout_summary_dates E,
					$this->tbl_param_payout_status F
					
				WHERE E.payout_summary_id = A.payroll_summary_id
					AND A.payout_status_id = F.payout_status_id
					AND A.payout_type_flag = ? 
					
				$filter_str
				
				GROUP BY compensation_id, effective_date, process_start_date, process_end_date, processed_by
				
				$sOrder
				$sLimit

EOS;
			
			$val = array_merge($val,$filter_params);
			
			$stmt['data'] = $this->query($query, $val, $multiple);
			$stmt['filtered_length'] = $this->_get_filtered_length();
						
			return $stmt;
		}	
		catch (PDOException $e)
		{
			$this->rlog_error($e);
		}
		catch (Exception $e)
		{			
			$this->rlog_error($e);
		}
	}
	
	/** 
	* This function returns the number of special payroll records.
	* 
	* @access public 
	* @return integer Number of records found
	*/
	/*
	public function total_length()
	{
		try
		{
			$where = array('payout_type_flag' => PAYOUT_TYPE_FLAG_SPECIAL); // S for Special Payroll
			
			$fields = array("COUNT(DISTINCT payroll_summary_id) cnt");
			return $this->select_one($fields, $this->tbl_payout_summary, $where);
		}
		catch (PDOException $e)
		{
			$this->rlog_error($e);
		}
		catch (Exception $e)
		{			
			$this->rlog_error($e);
		}	
	}
	*/
	
	/** 
	* This function retrieves special payroll record based on given parameters. 
	* 
	* @access public 
	* @param array $params Criteria used in filtering the result set
	* @return array Special Payroll record
	*/	
	public function get_special_payroll ($params)
	{
		$val = array();
		
		try
		{
			$fields 	= array("A.*", "B.effective_date");
			$key		= $this->get_hash_key('A.payroll_summary_id');
			
			$tables		= array(
					'main'	=> array(
					'table'		=> $this->tbl_payout_summary,
					'alias'		=> 'A'
				),
				'table1'	=> array(
					'table'		=> $this->tbl_payout_summary_dates,
					'alias'		=> 'B',
					'type'		=> 'LEFT JOIN',
					'condition'	=> 'B.payout_summary_id = A.payroll_summary_id'
				)
			);

			$where 			= array();
			$where[$key]	= $params['summary_id'];
			
			$val			= $this->select_one($fields, $tables, $where);

		}	
		catch (PDOException $e)
		{
			$this->rlog_error($e);
		}
		catch (Exception $e)
		{			
			$this->rlog_error($e);
		}
		
		return $val;
	}
	
	/**
	 * This function retrieves compensation type record.
	 * @param integer $compensation_type_id
	 * @return array Compensation Type record 
	 */
	public function get_compensation_type($compensation_type_id, $multiple=FALSE)
	{
		$compensation_type = array();
		
		try
		{
			$query = <<<EOS
				SELECT A.* 
				FROM 
				$this->tbl_param_compensations A
				WHERE (compensation_id = ? OR parent_compensation_id = ?) 
					AND employee_flag = ? AND active_flag = ?
EOS;
			
			$val = array (0 => $compensation_type_id, 1 => $compensation_type_id, 2 => YES, 3 => YES);
			
			$compensation_type = $this->query($query, $val, $multiple);
		}
		catch (PDOException $e)
		{
			$message = $e->getMessage();
			RLog::error($message);
			
			throw $e;
		}
		catch (Exception $e)
		{
			$message = $e->getMessage();
			RLog::error($message);
			
			throw $e;
		}
		
		return $compensation_type;
	}	
	
	/** 
	* This function retrieves special payroll of personnel based on given parameters. 
	* 
	* @access public 
	* @param array $params Criteria used in filtering the result set
	* @return array Special Payroll record of personnel
	*/	
	public function get_special_payroll_personnel ($params)
	{
		$val = array();
		
		try
		{
			$fields 	= array("A.*", "B.*");
			$key		= $this->get_hash_key('A.payroll_hdr_id');
			
			$tables		= array(
				'main'	=> array(
					'table'		=> $this->sppayroll->tbl_payout_header,
					'alias'		=> 'A'
				),
				'table1'	=> array(
					'table'		=> $this->sppayroll->tbl_payout_details,
					'alias'		=> 'B',
					'type'		=> 'LEFT JOIN',
					'condition'	=> 'B.payroll_hdr_id = A.payroll_hdr_id'
				)
			);

			$where 				= array();
			$where[$key]		= $params['payout_hdr_id'];
			
			$val				= $this->select_one($fields, $tables, $where);
						
		}	
		catch (PDOException $e)
		{
			$this->rlog_error($e);
		}
		catch (Exception $e)
		{			
			$this->rlog_error($e);
		}
		
		return $val;
	}	
	
	/**
	 * This function retrieves the personnel with given commpensation.
	 * @param integer $compensation_type_id
	 * @param date $covered_date
	 * @returns array List of personnels with the given compensation
	 */
	public function get_employee_compensation($compensation_type_id, $covered_date)
	{
		$personnels = NULL;
		
		try
		{
			$fields 	= array("A.employee_id", "A.compensation_id",
							"CONCAT(B.first_name, ' ', B.last_name, ' ', B.ext_name) employee_name", 
							"C.employ_plantilla_id", "C.employ_office_name", "C.employ_position_name", 
							"C.employ_salary_grade", "C.employ_salary_step", "C.employ_monthly_salary"
							);
			
			$tables		= array(
				'main'	=> array(
					'table'		=> $this->tbl_employee_compensations,
					'alias'		=> 'A'
				),
				'table1'	=> array(
					'table'		=> $this->tbl_employee_personal_info,
					'alias'		=> 'B',
					'type'		=> 'LEFT JOIN',
					'condition'	=> 'B.employee_id = A.employee_id'
				),
				'table2'=> array(
					'table'		=> $this->tbl_employee_work_experiences,
					'alias'		=> 'C',
					'type'		=> 'LEFT JOIN',
					'condition'	=> 'C.employee_id = B.employee_id AND C.active_flag = \'Y\''
				)
			);

			$where 						= array();
			$where['A.compensation_id']	= $compensation_type_id;
			$where['A.start_date']		= array($covered_date, array("<="));
			$where['A.end_date']		= array($covered_date, array(">="));
			$order_by					= array("A.employee_id" => 'ASC');
			$personnels 				= $this->common->get_data($fields, $tables, $where, TRUE, $order_by);
		}
		catch (PDOException $e)
		{
			$message = $e->getMessage();
			RLog::error($message);
			
			throw $e;
		}
		catch (Exception $e)
		{
			$message = $e->getMessage();
			RLog::error($message);
			
			throw $e;
		}
		
		return $personnels;
	}	
	

	/** 
	* This function retrieves all personnel included in the special payroll. 
	* 
	* @access public 
	* @param array $aColumns Columns included in the result set
	* @param array $bColumns Columns used in ordering the result set
	* @param array $params Criteria used in filtering the result set
	* @return array List of special payroll records
	*/
	public function get_special_payroll_personnel_list ($id, $aColumns, $bColumns, $params, $multiple=TRUE)
	{
		$stmt = array();
		
		try
		{
			$val = array(0 => $id);
			
			$fields = str_replace(" , ", " ", implode(", ", $aColumns));
			
			$cColumns = array('a-employee_name', 'a-office_name', 'a-position_name', 'a-tenure_in_months', 'a-perf_rating', 
							'a-basic_amount', 'b-base_rate', 'b-compensation_amount', 'b-deduction_amount');
			$fields = str_replace(' , ', ' ', implode(', ', $aColumns));

			$key	= $this->get_hash_key('a.payroll_summary_id');
			
			$sWhere = $this->filtering($cColumns, $params, TRUE);
			$sOrder = $this->ordering($bColumns, $params);
			$sLimit = $this->paging($params);
			
			$filter_str 	= $sWhere["search_str"];
			$filter_params	= $sWhere["search_params"];
			
			$query = <<<EOS
				SELECT SQL_CALC_FOUND_ROWS $fields 
				FROM 
				$this->tbl_payout_header a
				JOIN $this->tbl_payout_details b ON b.payroll_hdr_id = a.payroll_hdr_id
				WHERE 
				$key = ?
				$filter_str
				
				GROUP BY a.employee_id
				
				$sOrder
				$sLimit
EOS;
			
			$val = array_merge($val,$filter_params);
			
			$stmt['data'] = $this->query($query, $val, $multiple);
			$stmt['filtered_length'] = $this->_get_filtered_length();			
						
			return $stmt;
		}	
		catch (PDOException $e)
		{
			$this->rlog_error($e);
		}
		catch (Exception $e)
		{			
			$this->rlog_error($e);
		}
	}	
	
	
	/** 
	* This function returns the number of records filtered by given parameters. 
	* 
	* @access private 
	* @param array $aColumns Columns included in the result set
	* @param array $bColumns Columns used in ordering the result set
	* @param array $params Criteria used in filtering the result set
	* @return integer Number of records found
	*/	
	private function _get_filtered_length()
	{
		try
		{
			$query = <<<EOS
				SELECT FOUND_ROWS() cnt
EOS;
	
			$stmt = $this->query($query, NULL, FALSE);
		
			return $stmt;
		}
		catch (PDOException $e)
		{
			$this->rlog_error($e);
		}
		catch (Exception $e)
		{			
			$this->rlog_error($e);
		}	
	}
	
	/**
	 * This function retrieves the personnels with given commpensation and tenure requirement.
	 * @param integer $compensation_type_id
	 * @param integer $tenure_rqmt_val 
	 * @param date $covered_date
	 * @param array $period_params (tenure_period_from, tenure_period_to, rating_period_from, rating_period_to
	 * 
	 * @return List of personnel with given commpensation and tenure requirement.
	 */
	public function get_personnel_tenure($compensation_type_id, $covered_date, $employment_type_tenure, $included_employees=array(), $period_params=array()) 
	{
		try 
		{
			$tenure_period_from_date = NULL;
			$tenure_period_to_date = NULL;
			$rating_period_from_date = NULL;
			$rating_period_to_date = NULL;
			if ( !EMPTY($period_params))
			{
				$tenure_period_from_date = $period_params['tenure_period_from'];
				$tenure_period_to_date = $period_params['tenure_period_to'];
				
				$rating_period_from_date = $period_params['rating_period_from'];
				$rating_period_to_date = $period_params['rating_period_to'];				
			}
			
			$val	= array();
			/*
			$fields 	= array("A.employee_id", "A.compensation_id", "A.num_days",
							"CONCAT_WS(' ',CONCAT(B.last_name, ','),B.first_name,B.ext_name) employee_name", 
							"C.employ_plantilla_id", "C.employ_office_id", "C.employ_office_name", "C.employ_position_name", 
							"C.employ_salary_grade", "C.employ_salary_step", "C.employ_monthly_salary",
							"C.separation_mode_id",
							"IFNULL(D.tenure_months, 0) tenure_in_months", "IFNULL(F.tenure_months_year, 0) tenure_months_year", 
							"E.rating perf_rating", "E.rating_description perf_rating_description",
							'F.parent_compensation_id', 'F.inherit_parent_id_flag', 'G.anniv_emp_date'
							);
			*/
			// ====================== jendaigo : start : change name format ============= //
			$fields 	= array("A.employee_id", "A.compensation_id", "A.num_days",
							"CONCAT(B.last_name, ', ', B.first_name, IF(B.ext_name='', '', CONCAT(' ', B.ext_name)), IF((B.middle_name='NA' OR B.middle_name='N/A' OR B.middle_name='-' OR B.middle_name='/'), '', CONCAT(' ', B.middle_name))) employee_name", 
							"C.employ_plantilla_id", "C.employ_office_id", "C.employ_office_name", "C.employ_position_name", 
							"C.employ_salary_grade", "C.employ_salary_step", "C.employ_monthly_salary",
							"C.separation_mode_id",
							"IFNULL(D.tenure_months, 0) tenure_in_months", "IFNULL(F.tenure_months_year, 0) tenure_months_year", 
							"E.rating perf_rating", "E.rating_description perf_rating_description",
							'F.parent_compensation_id', 'F.inherit_parent_id_flag', 'G.anniv_emp_date'
							);
			// ====================== jendaigo : end : change name format ============= //
			
			$fields = str_replace(" , ", " ", implode(", ", $fields));
			
			$ctr = 0;
			$val[$ctr] = YES;

			$tenure_table	= <<<EOS
				(SELECT e.employee_id, 
						SUM( TIMESTAMPDIFF(MONTH, DATE_SUB(e.employ_start_date, INTERVAL 1 DAY), 
							IFNULL(e.employ_end_date, ?) ) ) tenure_months
					FROM $this->tbl_employee_work_experiences e
					GROUP BY employee_id) D ON D.employee_id = A.employee_id
EOS;
			$val[++$ctr] = ($tenure_period_to_date == NULL ? $covered_date : $tenure_period_to_date);
			
			$tenure_table_year	= <<<EOS
				(SELECT f.employee_id, 
						SUM( TIMESTAMPDIFF(MONTH, DATE_SUB(f.employ_start_date, INTERVAL 1 DAY), 
							IFNULL(f.employ_end_date, ?) ) ) tenure_months_year
					FROM $this->tbl_employee_work_experiences f
					WHERE f.employ_start_date BETWEEN ? AND ?
					AND f.employ_end_date BETWEEN ? AND ?
					GROUP BY employee_id) F ON F.employee_id = A.employee_id
EOS;
			$val[++$ctr] = ($tenure_period_to_date == NULL ? $covered_date : $tenure_period_to_date);
			$val[++$ctr] = ($tenure_period_from_date == NULL ? $covered_date : $tenure_period_from_date);
			$val[++$ctr] = ($tenure_period_to_date == NULL ? $covered_date : $tenure_period_to_date);
			$val[++$ctr] = ($tenure_period_from_date == NULL ? $covered_date : $tenure_period_from_date);
			$val[++$ctr] = ($tenure_period_to_date == NULL ? $covered_date : $tenure_period_to_date);
				
			$val[++$ctr] = $rating_period_from_date;
			$val[++$ctr] = $rating_period_to_date;
			$val[++$ctr] = $compensation_type_id;
			$val[++$ctr] = $covered_date;
			$val[++$ctr] = $covered_date;
			$val[++$ctr] = $covered_date;

			$filter_str	= '';

			if (! empty($included_employees))
			{
				$filter_str .= " AND A.employee_id IN (NULL";
				foreach ($included_employees as $e)
				{
					$filter_str .= ',?';
					$val[++$ctr] = $e;
				}
				
				
				$filter_str .= ") ";
			}
			
			$query = <<<EOS
				SELECT $fields 
				FROM $this->tbl_employee_compensations A
				JOIN $this->tbl_employee_personal_info B ON B.employee_id = A.employee_id 
				JOIN $this->tbl_employee_work_experiences C ON C.employee_id = B.employee_id AND C.active_flag = ? 

				LEFT JOIN $tenure_table
				
				LEFT JOIN $tenure_table_year
					
				LEFT JOIN $this->tbl_employee_performance_evaluations E ON E.employee_id = B.employee_id 
					AND E.evaluation_start_date = ? AND E.evaluation_end_date = ?

				LEFT JOIN $this->tbl_param_compensations F ON A.compensation_id = F.compensation_id
				
				JOIN (SELECT e.employee_id, MIN(e.employ_start_date) anniv_emp_date 
					FROM $this->tbl_employee_work_experiences e
					WHERE e.employ_type_flag IN ('$employment_type_tenure')
					GROUP BY e.employee_id) G ON G.employee_id = B.employee_id
					
				WHERE A.compensation_id = ? AND A.start_date <= ?  
				AND (IFNULL(A.end_date, ?) >= ?) 
				
				$filter_str
								
				ORDER BY A.employee_id ASC
EOS;
			$val	= $this->query($query, $val, TRUE);
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
		
		return $val;
	}
	
	public function get_personnel_with_monetize($compensation_type_id, $payout_summary_id, $included_employees, $period_params=array()) 
	{
		try 
		{
			$val	= array();
			$fields 	= array("A.employee_id", "$compensation_type_id compensation_id", "C.leave_earned_used num_days",
							"CONCAT_WS(' ',CONCAT(A.last_name, ','),A.first_name,A.ext_name) employee_name", 
							"B.employ_plantilla_id", "B.employ_office_id", "B.employ_office_name", "B.employ_position_name", 
							"B.employ_salary_grade", "B.employ_salary_step", "B.employ_monthly_salary",
							"B.separation_mode_id",
							"0 tenure_in_months", "0 tenure_months_year", 
							"NULL perf_rating", "NULL perf_rating_description",
							"NULL parent_compensation_id", "'NA' inherit_parent_id_flag", "NULL anniv_emp_date"
							);
			$fields = str_replace(" , ", " ", implode(", ", $fields));
			
			$ctr = 0;
			$val[$ctr] = YES;
			$val[++$ctr] = YES;
			$val[++$ctr] = NO;
			$val[++$ctr] = $payout_summary_id;

			$filter_str	= '';
			$filter_str .= "A.employee_id IN (NULL";
				foreach ($included_employees as $e)
				{
					$filter_str .= ',?';
					$val[++$ctr] = $e;
				}
			$filter_str .= ") ";
			
			$query = <<<EOS
				SELECT $fields 
				FROM $this->tbl_employee_personal_info A 
				JOIN $this->tbl_employee_work_experiences B ON B.employee_id = A.employee_id AND B.active_flag = ? 
				JOIN (
					SELECT employee_id, SUM(leave_earned_used) leave_earned_used 
					FROM $this->tbl_employee_leave_details 
					WHERE monetize_flag = ? and (paid_flag = ? OR payout_summary_id = ?)
					GROUP BY employee_id
				) C ON C.employee_id = A.employee_id
				
				WHERE  
				
				$filter_str
								
				ORDER BY A.employee_id ASC
EOS;
			$val	= $this->query($query, $val, TRUE);
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
		
		return $val;
	}	
	
	
	public function get_covered_salary_schedule($covered_period_from, $covered_period_to, $other_fund_flag=NULL)
	{
		try
		{

			$fields	= array('effectivity_date', 'MONTH(effectivity_date) effective_month', 'YEAR(effectivity_date) effective_year', 
							'salary_grade', 'salary_step', 'amount', 'other_fund_flag', 'active_flag');
			$table	= $this->tbl_param_salary_schedule;

			$where 	= array();
			$where['effectivity_date']	= array(array($covered_period_from, $covered_period_to), array('BETWEEN'));
			if ( ! empty($other_fund_flag))
				$where['other_fund_flag']	= $other_fund_flag;

			$order_by	= array('effectivity_date'=>'ASC', 'active_flag'=>'ASC', 'other_fund_flag'=>'ASC', 'salary_grade'=>'ASC', 'salary_step'=>'ASC');
			
			return $this->select_all($fields, $table, $where, $order_by);

		}
		catch (Exception $e)
		{
			RLog::error($e->getMessage());
			throw $e;
		}
	}
	
	public function get_table_employee_list($compensation_type_id, $params)
	{
		try
		{
			if(!EMPTY($params['employee_name']))
				// $params["CONCAT_WS(' ',CONCAT(b.last_name, ','),b.first_name,b.middle_name,b.ext_name)"] = $params['employee_name'];
				// ====================== jendaigo : start : change name format ============= //
				$params["CONCAT(b.last_name, ', ', b.first_name, IF(b.ext_name='', '', CONCAT(' ', b.ext_name)), IF((b.middle_name='NA' OR b.middle_name='N/A' OR b.middle_name='-' OR b.middle_name='/'), '', CONCAT(' ', b.middle_name)))"] = $params['employee_name'];
				// ====================== jendaigo : end : change name format ============= //
				
			$curr_date = date('Y-m-d');
			$val       = array(YES, $compensation_type_id, $compensation_type_id, $curr_date, $curr_date, $curr_date);
			/*
			$fields    = "a.employee_id, b.agency_employee_id, 
						CONCAT_WS(' ',CONCAT(b.last_name, ','),b.first_name,b.middle_name,b.ext_name) employee_name, 
						e.office_id,
						f.name as office_name,
						g.employment_status_name,
						c.separation_mode_id";
			
			// For Advanced Filters
			$cColumns = array("b-agency_employee_id", "CONCAT_WS(' ',CONCAT(b.last_name, ','),b.first_name,b.middle_name,b.ext_name)", 
						"f-name", "g-employment_status_name", "e-office_id");
			*/
			
			// ====================== jendaigo : start : change name format ============= //			
			$fields    = "a.employee_id, b.agency_employee_id, 
						CONCAT(b.last_name, ', ', b.first_name, IF(b.ext_name='', '', CONCAT(' ', b.ext_name)), IF((b.middle_name='NA' OR b.middle_name='N/A' OR b.middle_name='-' OR b.middle_name='/'), '', CONCAT(' ', b.middle_name))) employee_name, 
						e.office_id,
						f.name as office_name,
						g.employment_status_name,
						c.separation_mode_id";
						
			$cColumns = array("b-agency_employee_id", "CONCAT(b.last_name, ', ', b.first_name, IF(b.ext_name='', '', CONCAT(' ', b.ext_name)), IF((b.middle_name='NA' OR b.middle_name='N/A' OR b.middle_name='-' OR b.middle_name='/'), '', CONCAT(' ', b.middle_name)))", 
						"f-name", "g-employment_status_name", "e-office_id");
			// ====================== jendaigo : end : change name format ============= //
			
			$sWhere = $this->filtering($cColumns, $params, TRUE);
			$sLimit = $this->paging($params);
			
			$filter_str    = $sWhere["search_str"];
			$filter_params = $sWhere["search_params"];			

			$query = <<<EOS
				SELECT  SQL_CALC_FOUND_ROWS $fields 

				FROM $this->tbl_employee_compensations a
				JOIN $this->tbl_employee_personal_info b ON b.employee_id = a.employee_id
				JOIN $this->tbl_employee_work_experiences c ON c.employee_id = b.employee_id AND c.active_flag = ?
				JOIN $this->tbl_param_compensations d ON a.compensation_id = d.compensation_id
				LEFT JOIN $this->tbl_param_offices e ON c.employ_office_id = e.office_id 
				LEFT JOIN $this->db_core.$this->tbl_organizations f ON f.org_code = e.org_code
				LEFT JOIN $this->tbl_param_employment_status g ON c.employment_status_id = g.employment_status_id
				
				WHERE (a.compensation_id = ? OR d.parent_compensation_id = ?) AND a.start_date <= ?
				 
				AND (IFNULL(a.end_date, ?) >= ?)
				
				$filter_str
				               
				GROUP BY a.employee_id
				
				ORDER BY employee_name ASC
				
				$sLimit
EOS;
	
			$val  = array_merge($val, $filter_params);
			$stmt = array();
			$stmt['data']            = $this->query($query, $val, TRUE);
			$stmt['filtered_length'] = $this->_get_filtered_length();

			return $stmt;

		}
		catch (PDOException $e)
		{
			$this->rlog_error($e);
		}
		catch (Exception $e)
		{			
			$this->rlog_error($e);
		}
	}
	
	public function get_table_monetize_employee_list($hash_payout_summary_id, $params=array())
	{
		try
		{
			if(!EMPTY($params['employee_name']))
				$params["CONCAT_WS(' ',CONCAT(b.last_name, ','),b.first_name,b.ext_name)"] = $params['employee_name'];			
			
			$val 	= array(YES, YES, NO, $hash_payout_summary_id);
			$fields = "b.employee_id, b.agency_employee_id, 
						CONCAT_WS(' ',CONCAT(b.last_name, ','),b.first_name,b.ext_name) employee_name,
						e.office_id,
						f.name as office_name,
						g.employment_status_name,
						c.separation_mode_id,
						f.leave_detail_id";
			
			/* For Advanced Filters */
			$cColumns = array("b-agency_employee_id", "CONCAT_WS(' ',CONCAT(b.last_name, ','),b.first_name,b.ext_name)", 
						"f-name", "g-employment_status_name", "e-office_id");
			
			$sWhere = $this->filtering($cColumns, $params, TRUE);
			$sLimit = $this->paging($params);
			
			$filter_str = $sWhere["search_str"];
			$filter_params = $sWhere["search_params"];

			$filter_payout	= $this->get_hash_key('payout_summary_id');

			$query = <<<EOS
				SELECT  SQL_CALC_FOUND_ROWS $fields 

				FROM $this->tbl_employee_personal_info b
				JOIN $this->tbl_employee_work_experiences c ON c.employee_id = b.employee_id AND c.active_flag = ?
				LEFT JOIN $this->tbl_param_offices e ON c.employ_office_id = e.office_id 
				LEFT JOIN $this->db_core.$this->tbl_organizations f ON f.org_code = e.org_code
				LEFT JOIN $this->tbl_param_employment_status g ON c.employment_status_id = g.employment_status_id
				JOIN (
					SELECT employee_id, GROUP_CONCAT(leave_detail_id) leave_detail_id FROM $this->tbl_employee_leave_details 
					WHERE monetize_flag = ? and (paid_flag = ? OR $filter_payout = ?)
					GROUP BY employee_id
				) f ON f.employee_id = b.employee_id
				
				WHERE 1=1
				
				$filter_str
				               
				GROUP BY b.employee_id
				
				ORDER BY employee_name ASC
				
				$sLimit
EOS;
	
			$val = array_merge($val,$filter_params);
			$stmt['data'] = $this->query($query, $val, TRUE);
			$stmt['filtered_length'] = $this->_get_filtered_length();

			return $stmt;

		}
		catch (PDOException $e)
		{
			$this->rlog_error($e);
		}
		catch (Exception $e)
		{			
			$this->rlog_error($e);
		}
	}	
	
	public function get_all_table_employee_list($compensation_id, $hash_payout_summary_id=0, $monetize_flag=NO)
	{
		try
		{
			$val 	= array();
			$fields = "a.employee_id, b.separation_mode_id";
			
			if ($monetize_flag === YES)
			{
				$filter_payout	= $this->get_hash_key('a.payout_summary_id');
				$query = <<<EOS
					SELECT  $fields 
					FROM $this->tbl_employee_leave_details a
					JOIN $this->tbl_employee_work_experiences b ON a.employee_id = b.employee_id AND b.active_flag = ?
					WHERE a.monetize_flag = ? AND (a.paid_flag = ? OR $filter_payout = ?)
					GROUP BY employee_id
EOS;
				$val 	= array(YES, YES, NO, $hash_payout_summary_id);
			}
			else
			{
				$query = <<<EOS
					SELECT  $fields 
					FROM $this->tbl_employee_compensations a
					JOIN $this->tbl_employee_work_experiences b ON a.employee_id = b.employee_id
					JOIN $this->tbl_param_compensations c ON a.compensation_id = c.compensation_id 
					WHERE b.active_flag = ?
						AND (a.compensation_id = ? OR c.parent_compensation_id = ?)
					GROUP BY employee_id
EOS;
				$val 	= array(YES, $compensation_id, $compensation_id);
			}
			$stmt = $this->query($query, $val, TRUE);
						
			return $stmt;

		}
		catch (PDOException $e)
		{
			$this->rlog_error($e);
		}
		catch (Exception $e)
		{			
			$this->rlog_error($e);
		}
	}	
}