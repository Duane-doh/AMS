<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Payroll_model extends Main_Model {

	public $db_core           = DB_CORE;
	public $tbl_organizations = 'organizations';
	
	public function __construct() {
		parent:: __construct();
	
	}

	public function get_payroll_list($aColumns, $bColumns, $params, $multiple = TRUE)
	{
		/*
		try {
			if ( ! empty($params['b-date_from']))
			{
				$dt = new DateTime($params['b-date_from']);
				$params['b-date_from'] = $dt->format('Y-m-d');
			}
			if ( ! empty($params['b-date_to']))
			{
				$dt = new DateTime($params['b-date_to']);
				$params['b-date_to'] = $dt->format('Y-m-d');
			}
		} catch (Exception $e)
		{
		}
		*/
		
		try
		{
			$val = array(PAYOUT_TYPE_FLAG_REGULAR);
			
			/* For Advanced Filters */
			// $cColumns = array("c-payroll_type_name", "d-bank_name", "DATE_FORMAT(b-date_from, '%Y/%m/%d')", "DATE_FORMAT(b-date_to, '%Y/%m/%d')", "e-payout_status_name");
			//marvin : include remarks for batching : start
			$cColumns = array("c-payroll_type_name", "d-bank_name", "DATE_FORMAT(b-date_from, '%Y/%m/%d')", "DATE_FORMAT(b-date_to, '%Y/%m/%d')", "e-payout_status_name", "b-remarks");
			//marvin : include remarks for batching : end
			
			if ( ! empty($params['b-date_from']))
			{
// 				$dt = new DateTime($params['b-date_from']);
// 				$params['b-date_from'] = $dt->format('Y-m-d');
				$params["DATE_FORMAT(b-date_from, '%Y/%m/%d')"] = $params['b-date_from'];
			}
			if ( ! empty($params['b-date_to']))
			{
// 				$dt = new DateTime($params['b-date_to']);
// 				$params['b-date_to'] = $dt->format('Y-m-d');
				$params["DATE_FORMAT(b-date_to, '%Y/%m/%d')"] = $params['b-date_to'];
			}
			
			$fields = str_replace(" , ", " ", implode(", ", $aColumns));
			
			$sWhere = $this->filtering($cColumns, $params, TRUE);
			$sOrder = $this->ordering($bColumns, $params);
			$sLimit = $this->paging($params);
			
			$filter_str = $sWhere["search_str"];
			$filter_params = $sWhere["search_params"];
			
			$query = <<<EOS
				SELECT SQL_CALC_FOUND_ROWS $fields 
				FROM
				$this->tbl_payout_summary a  
				JOIN $this->tbl_attendance_period_hdr b ON b.attendance_period_hdr_id = a.attendance_period_hdr_id 
				JOIN $this->tbl_param_payroll_types c ON b.payroll_type_id=c.payroll_type_id 
				JOIN $this->tbl_param_banks d ON a.bank_id = d.bank_id 
				JOIN $this->tbl_param_payout_status e ON a.payout_status_id = e.payout_status_id
				WHERE 
				a.payout_type_flag = ?

				$filter_str
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
	* This function retrieves payroll details based on given parameters. 
	* 
	* @access public 
	* @param array $params Criteria used in filtering the result set
	* @return array Payroll record
	*/	
	public function get_payroll_record ($params)
	{
		$val = array();
		
		try
		{
			$fields 	= array('a.payroll_summary_id', 'a.payout_type_flag', 'a.bank_id',
							'a.attendance_period_hdr_id', 'a.compensation_id', 'f.compensation_code',
							'a.tenure_period_start_date', 'a.tenure_period_end_date', 'a.processed_by',  
							'a.certified_by', 'a.approved_by', 'a.certified_cash_by', 'a.payout_status_id', 
							'GROUP_CONCAT(b.payout_date_num separator \',\') payout_date_nums', 
							'GROUP_CONCAT(b.effective_date separator \',\') effective_dates',
							'c.payroll_type_id', 'd.payout_status_name',
							'YEAR(c.date_to) payout_year',
							'e.tax_table_flag', 'e.payout_count', 'e.mwe_denominator',
							'd.action_id');
			$key		= $this->get_hash_key('a.payroll_summary_id');
			
			$tables		= array(
				'main'	=> array(
					'table'		=> $this->tbl_payout_summary,
					'alias'		=> 'a'
				),
				'table1'	=> array(
					'table'		=> $this->tbl_payout_summary_dates,
					'alias'		=> 'b',
					'type'		=> 'LEFT JOIN',
					'condition'	=> 'b.payout_summary_id = a.payroll_summary_id'
				),
				'table2'	=> array(
					'table'		=> $this->tbl_attendance_period_hdr,
					'alias'		=> 'c',
					'type'		=> 'LEFT JOIN',
					'condition'	=> 'a.attendance_period_hdr_id = c.attendance_period_hdr_id'
				),
				'table3'	=> array(
					'table'		=> $this->tbl_param_payout_status,
					'alias'		=> 'd',
					'type'		=> 'JOIN',
					'condition'	=> 'a.payout_status_id = d.payout_status_id'
				),
				'table4'	=> array(
					'table'		=> $this->tbl_param_payroll_types,
					'alias'		=> 'e',
					'type'		=> 'LEFT JOIN',
					'condition'	=> 'c.payroll_type_id = e.payroll_type_id'
				),
				'table5'	=> array(
					'table'		=> $this->tbl_param_compensations,
					'alias'		=> 'f',
					'type'		=> 'LEFT JOIN',
					'condition'	=> 'f.compensation_id = a.compensation_id'
				)
			);
			
			$group_by	= array('payroll_summary_id');

			$where 				= array();
			$where[$key]		= $params['id'];
			
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
	
	public function get_payroll_remittance($payout_summary_id) {
		try
		{
			$query = <<<EOS
				SELECT *
				FROM payout_header A
					JOIN payout_details B ON B.payroll_hdr_id = A.payroll_hdr_id
					JOIN remittance_details C ON C.payroll_dtl_id = B.payroll_dtl_id
					WHERE A.payroll_summary_id = ?
					GROUP BY A.payroll_summary_id
EOS;
			$stmt = $this->query($query, $payout_summary_id, FALSE);
			
			return $stmt;
		}
		catch(PDOException $e) {
			$msg = $e->getMessage();
		}
		catch(Exception $e)
		{
			$msg = $e->getMessage();
		}
	}

	public function get_payout_employee_list($aColumns, $bColumns, $id, $params, $multiple = TRUE)
	{
		try
		{
			$val = array(0 => YES, 1 => $id);
			
			/* For Advanced Filters */
			$cColumns = array('C-agency_employee_id', 'B-employee_name', 'B-office_id', 'B-office_name', 'E-employment_status_name');
						
			$fields = str_replace(" , ", " ", implode(", ", $aColumns));
			
			$sWhere = $this->filtering($cColumns, $params, TRUE);
			$sOrder = $this->ordering($bColumns, $params);
			$sLimit = $this->paging($params);
			
			$filter_summary_id = $this->get_hash_key('A.payroll_summary_id') . ' = ?';
			
			$filter_str = $sWhere["search_str"];
			$filter_params = $sWhere["search_params"];
			
			$query = <<<EOS
				SELECT SQL_CALC_FOUND_ROWS $fields 
				FROM
				$this->tbl_payout_summary A 
				LEFT JOIN $this->tbl_payout_header B ON B.payroll_summary_id = A.payroll_summary_id
				JOIN $this->tbl_employee_personal_info C ON B.employee_id = C.employee_id
				JOIN $this->tbl_employee_work_experiences D ON D.employee_id = C.employee_id AND D.active_flag = ?
				JOIN $this->tbl_param_employment_status E ON D.employment_status_id = E.employment_status_id
				WHERE 

				$filter_summary_id
				
				$filter_str
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
	
	public function get_payout_employee_benefit_list($aColumns, $bColumns, $cColumns, $summary_id, $params, $compensation_id=NULL, $multiple = TRUE)
	{
		try
		{
			$val 	= array();

			$fields = str_replace(" , ", " ", implode(", ", $aColumns));
			
			$sWhere = $this->filtering($cColumns, $params, TRUE);
			$sOrder = $this->ordering($bColumns, $params);
			$sLimit = $this->paging($params);
			
			$filter_pk_id = $this->get_hash_key('A.payroll_summary_id') . ' = ?';
			$val[0] = $summary_id;
			if ($compensation_id)
			{
				$filter_pk_id .= ' AND ' . $this->get_hash_key('C.compensation_id') . ' = ?';
				$val[1] = $compensation_id;				
			}
			
			
			$filter_str = $sWhere["search_str"];
			$filter_params = $sWhere["search_params"];
			
			$query = <<<EOS
				SELECT SQL_CALC_FOUND_ROWS DISTINCT $fields 
				FROM
				$this->tbl_payout_summary A 
				LEFT JOIN $this->tbl_payout_header B ON B.payroll_summary_id = A.payroll_summary_id
				JOIN $this->tbl_payout_details C ON C.payroll_hdr_id = B.payroll_hdr_id
				JOIN $this->tbl_param_compensations D ON C.compensation_id = D.compensation_id
				JOIN $this->tbl_employee_personal_info E ON B.employee_id = E.employee_id
				WHERE 

				$filter_pk_id
				
				$filter_str
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
	
	public function get_payout_employee_deduction_list($aColumns, $bColumns, $cColumns, $summary_id, $params, $deduction_id=NULL, $multiple = TRUE)
	{
		try
		{
			$val 	= array();

			$fields = str_replace(" , ", " ", implode(", ", $aColumns));
			
			$sWhere = $this->filtering($cColumns, $params, TRUE);
			$sOrder = $this->ordering($bColumns, $params);
			$sLimit = $this->paging($params);
			
			$filter_pk_id = $this->get_hash_key('A.payroll_summary_id') . ' = ?';
			$val[0] = $summary_id;
			if ($deduction_id)
			{
				$filter_pk_id .= ' AND ' . $this->get_hash_key('C.deduction_id') . ' = ?';
				$val[1] = $deduction_id;				
			}
			
			$filter_str = $sWhere["search_str"];
			$filter_params = $sWhere["search_params"];
			
			$query = <<<EOS
				SELECT SQL_CALC_FOUND_ROWS DISTINCT $fields 
				FROM
				$this->tbl_payout_summary A 
				LEFT JOIN $this->tbl_payout_header B ON B.payroll_summary_id = A.payroll_summary_id
				JOIN $this->tbl_payout_details C ON C.payroll_hdr_id = B.payroll_hdr_id
				JOIN $this->tbl_param_deductions D ON C.deduction_id = D.deduction_id
				JOIN $this->tbl_employee_personal_info E ON B.employee_id = E.employee_id
				WHERE 

				$filter_pk_id
				
				$filter_str
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
	
	public function get_total_payout_detail_count($summary_id, $compensation_flag=TRUE)
	{
		try
		{
			$fields = array('COUNT(DISTINCT c.compensation_id) cnt');
			if (!$compensation_flag)
				$fields = array('COUNT(DISTINCT c.deduction_id) cnt');
				
			$tables					= array(
				'main'	=> array(
					'table'		=> $this->tbl_payout_summary,
					'alias'		=> 'a'
				),
				'table1'=> array(
					'table'		=> $this->tbl_payout_header,
					'alias'		=> 'b',
					'type'		=> 'JOIN',
					'condition'	=> 'a.payroll_summary_id = b.payroll_summary_id'
				),
				'table2'=> array(
					'table'		=> $this->tbl_payout_details,
					'alias'		=> 'c',
					'type'		=> 'JOIN',
					'condition'	=> 'b.payroll_hdr_id = c.payroll_hdr_id'
				)
			);

			$where	= array();
			$where[$this->get_hash_key('a.payroll_summary_id')] = $summary_id;
			
			return $this->select_one($fields, $tables, $where);
		}
		catch (Exception $e)
		{			
			$this->rlog_error($e);
		}
	}	


	public function get_employee_payout_details ($id)
	{
		$val = array();
		
		try
		{
			$fields 	= array('A.net_pay', 'B.payroll_dtl_id', 'B.compensation_id', 'C.compensation_name', 'B.deduction_id', "IF(E.payment_count IS NOT NULL,CONCAT(D.deduction_name,' (',B.paid_count,' / ',E.payment_count,') '),D.deduction_name) as deduction_name", 'B.sys_flag', 
							'IFNULL(B.amount, 0) amount','IFNULL(B.orig_amount, 0) orig_amount','IFNULL(B.less_amount, 0) less_amount', 'DATE_FORMAT(B.effective_date, \'%Y/%m/%d\') payout_date', 
							'IFNULL(B.remarks_compensation, \'\') remarks_compensation', 'IFNULL(B.remarks_deduction, \'\') remarks_deduction');
			$key1		= $this->get_hash_key('A.payroll_hdr_id');
			
			$tables		= array(
				'main'	=> array(
					'table'		=> $this->tbl_payout_header,
					'alias'		=> 'A'
				),
				'table1'	=> array(
					'table'		=> $this->tbl_payout_details,
					'alias'		=> 'B',
					'type'		=> 'LEFT JOIN',
					'condition'	=> 'B.payroll_hdr_id = A.payroll_hdr_id'
				),
				'table2'	=> array(
					'table'		=> $this->tbl_param_compensations,
					'alias'		=> 'C',
					'type'		=> 'LEFT JOIN',
					'condition'	=> 'C.compensation_id = B.compensation_id'
				),
				'table3'	=> array(
					'table'		=> $this->tbl_param_deductions,
					'alias'		=> 'D',
					'type'		=> 'LEFT JOIN',
					'condition'	=> 'D.deduction_id = B.raw_deduction_id'
				),
				'table4'	=> array(
					'table'		=> $this->tbl_employee_deductions,
					'alias'		=> 'E',
					'type'		=> 'LEFT JOIN',
					'condition'	=> 'B.deduction_id = E.deduction_id AND A.employee_id = E.employee_id AND E.payment_count > 0'
				)
			);

			$where 			= array();
			$where[$key1]	= $id;
			$order_by		= array('D.deduction_name' => 'ASC', 'C.compensation_name' => 'ASC');
			
			$val			= $this->select_all($fields, $tables, $where, $order_by);

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
	* This function retrieves payroll total. 
	* 
	* @access public 
	* @param $id payout summary id
	* @param $field_id_name field name of secondary key (compensation_id OR deduction_id)
	* @param $field_id_val value of the secondary key (ID for either compensation OR deduction)
	* @return total amount (amount for either compensation OR deduction)
	*/	
	public function get_payout_total ($id, $field_id_name, $field_id_val)
	{
		$val = array();
		
		try
		{
			$fields 	= array("SUM(IFNULL(C.amount, 0)) amount");
			$key1		= $this->get_hash_key('A.payroll_summary_id');
			$key2		= $this->get_hash_key($field_id_name);
			
			$tables		= array(
				'main'	=> array(
					'table'		=> $this->tbl_payout_summary,
					'alias'		=> 'A'
				),
				'table1'	=> array(
					'table'		=> $this->tbl_payout_header,
					'alias'		=> 'B',
					'type'		=> 'LEFT JOIN',
					'condition'	=> 'B.payroll_summary_id = A.payroll_summary_id'
				),
				'table2'	=> array(
					'table'		=> $this->tbl_payout_details,
					'alias'		=> 'C',
					'type'		=> 'LEFT JOIN',
					'condition'	=> 'C.payroll_hdr_id = B.payroll_hdr_id'
				)
			);

			$where 			= array();
			$where[$key1]	= $id;
			$where[$key2]	= $field_id_val;
			
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

	public function get_detail_flag_options($details_flag, $payroll_hdr_id)
	{
		$options = array();
		
		$filter	= $this->get_hash_key('payroll_hdr_id');
		
		$table = $this->tbl_param_compensations;
		$field = 'compensation_id';
		$parent= 'parent_compensation_id';
		$others= "AND general_payroll_flag = '".YES."'";
		$order = 'compensation_name';
		
		if ($details_flag == 'D')
		{
			$table = $this->tbl_param_deductions;
			$field = 'deduction_id';
			$parent= 'parent_deduction_id';
			$others= '';
			$order = 'deduction_name';
		}
		
		try
		{
			$val = array ($payroll_hdr_id);
			$query = <<<EOS
					SELECT payroll_hdr_id FROM $this->tbl_payout_header WHERE $filter = ?
EOS;
			$pay_hdr_id = $this->query($query, $val, FALSE);

			if ( ! empty($pay_hdr_id))
			{
				$val = array (YES, $pay_hdr_id['payroll_hdr_id']);
				
				$query = <<<EOS
					SELECT * 
					FROM $table 
					WHERE 
					$parent IS NULL
					$others
					AND active_flag = ?
					AND $field NOT IN (
						SELECT IFNULL($field, 0) FROM $this->tbl_payout_details 
						WHERE 
						payroll_hdr_id = ?
					)
	
					ORDER BY $order
					
					
EOS;
				$options = $this->query($query, $val, TRUE);
			}
		}
		catch (PDOException $e)
		{
			$message = $e->getMessage();
			RLog::error($message);
		}
		catch (Exception $e)
		{
			$message = $e->getMessage();
			RLog::error($message);
		}
		
		return $options;
	}
	
	public function get_signatories($signatory_code)
	{
		$signatories = array();
		try
		{
			$fields	= array("A.employee_id", "CONCAT_WS(' ',CONCAT(A.last_name, ','),A.first_name,A.ext_name) employee_name");
			
			$tables	= array(
				'main'	=> array(
					'table'		=> $this->tbl_employee_personal_info,
					'alias'		=> 'A'
				),
				'table1'	=> array(
					'table'		=> $this->db_core.'.'.$this->tbl_sys_param,
					'alias'		=> 'B',
					'type'		=> 'JOIN',
					'condition'	=> 'B.sys_param_value = A.agency_employee_id'
				)
			);

			$where					= array();
			$where['sys_param_type']= $signatory_code;
			
			$signatories			= $this->select_all($fields, $tables, $where);

		}
		catch (PDOException $e)
		{
			$message = $e->getMessage();
			RLog::error($message);
		}
		catch (Exception $e)
		{
			$message = $e->getMessage();
			RLog::error($message);
		}
		
		return $signatories;
	}
	
	
	public function get_payout_history_list($aColumns, $bColumns, $id, $params, $multiple = TRUE)
	{
		try
		{
			$val = array(0 => $id);
			
			/* For Advanced Filters */
			$cColumns = array('B-processed_by', 'C-payout_status_name', 'A-hist_date', 'A-remarks');

			$fields = str_replace(" , ", " ", implode(", ", $aColumns));
			
			$sWhere = $this->filtering($cColumns, $params, TRUE);
			$sOrder = $this->ordering($bColumns, $params);
			$sLimit = $this->paging($params);
			
			$filter_summary_id = $this->get_hash_key('A.payout_summary_id') . ' = ?';
			$filter_str = $sWhere["search_str"];
			$filter_params = $sWhere["search_params"];
			
			$query = <<<EOS
				SELECT SQL_CALC_FOUND_ROWS $fields 
				FROM
				$this->tbl_payout_history A, 
				$this->tbl_employee_personal_info B, 
				$this->tbl_param_payout_status C
				where 
				A.employee_id = B.employee_id
				and A.payout_status_id = C.payout_status_id
				and

				$filter_summary_id
				
				$filter_str
				$sOrder
				$sLimit
				
EOS;
			$val = array_merge($val,$filter_params);
			
			$stmt = $this->query($query, $val, $multiple);
						
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
	
	public function get_table_employee_list($payroll_type_id, $payroll_period_id, $params)
	{
		try
		{
			if(!EMPTY($params['employee_name']))
				// $params["CONCAT_WS(' ',CONCAT(a.last_name, ','),a.first_name,a.middle_name,a.ext_name)"] = $params['employee_name'];			
				// ====================== jendaigo : start : change name format ============= //
				$params["CONCAT(a.last_name, ', ', a.first_name, IF(a.ext_name='', '', CONCAT(' ', a.ext_name)), IF((a.middle_name='NA' OR a.middle_name='N/A' OR a.middle_name='-' OR a.middle_name='/'), '', CONCAT(' ', a.middle_name)))"] = $params['employee_name'];			
				// ====================== jendaigo : end : change name format ============= //
			
			/*
			$val 	= array($payroll_period_id, YES, $payroll_type_id, $payroll_type_id);
			$fields = "a.employee_id, a.agency_employee_id,c.office_id, 
					CONCAT_WS(' ',CONCAT(a.last_name, ','),a.first_name,a.middle_name,a.ext_name) employee_name, 
					e.name as office_name, f.employment_status_name, b.separation_mode_id,
					IFNULL(g.working_hours, 0) worked_hours";
			
			// For Advanced Filters
			$cColumns = array("a-agency_employee_id", "CONCAT_WS(' ',CONCAT(a.last_name, ','),a.first_name,a.middle_name,a.ext_name)", 
				"e-name", "f-employment_status_name", "c-office_id");
			*/
			// ====================== jendaigo : start : modify $val and change name format ============= //
			$val 	= array($payroll_period_id, $payroll_period_id, $payroll_type_id, $payroll_type_id);
			
			$fields = "a.employee_id, a.agency_employee_id,c.office_id, 
					CONCAT(a.last_name, ', ', a.first_name, IF(a.ext_name='', '', CONCAT(' ', a.ext_name)), IF((a.middle_name='NA' OR a.middle_name='N/A' OR a.middle_name='-' OR a.middle_name='/'), '', CONCAT(' ', a.middle_name))) employee_name, 
					e.name as office_name, f.employment_status_name, b.separation_mode_id,
					IFNULL(g.working_hours, 0) worked_hours";
			
			$cColumns = array("a-agency_employee_id", "CONCAT(a.last_name, ', ', a.first_name, IF(a.ext_name='', '', CONCAT(' ', a.ext_name)), IF((a.middle_name='NA' OR a.middle_name='N/A' OR a.middle_name='-' OR a.middle_name='/'), '', CONCAT(' ', a.middle_name)))", 
				"e-name", "f-employment_status_name", "c-office_id");
			// ====================== jendaigo : end : modify $val and change name format ============= //
			
			$sWhere        = $this->filtering($cColumns, $params, TRUE);
			//$sOrder      = $this->ordering($bColumns, $params);
			$sLimit        = $this->paging($params);
			
			$filter_str    = $sWhere["search_str"];
			$filter_params = $sWhere["search_params"];	

			$module_id = $params['module_id'];	
			$work_experienxe_office_id = 'b.employ_office_id';
			if(!EMPTY($module_id))
			{
				$where              = array();
				$where['module_id'] = $module_id;
				$employee_office    = $this->get_payroll_data(array('use_admin_office'), DB_CORE.'.'.$this->tbl_modules, $where, FALSE);
				if($employee_office['use_admin_office'] > 0)
				{
					$work_experienxe_office_id = 'b.admin_office_id';
				}
			}
/*
			$query = <<<EOS
				SELECT  SQL_CALC_FOUND_ROWS $fields 
				FROM $this->tbl_employee_personal_info a
				JOIN $this->tbl_employee_work_experiences b ON a.employee_id = b.employee_id 
				LEFT JOIN $this->tbl_param_offices c ON $work_experienxe_office_id = c.office_id 
				LEFT JOIN $this->db_core.$this->tbl_organizations e ON c.org_code = e.org_code				
				LEFT JOIN $this->tbl_param_employment_status f ON b.employment_status_id = f.employment_status_id
				LEFT JOIN $this->tbl_attendance_period_summary g ON g.employee_id = a.employee_id AND g.attendance_period_hdr_id = ?
				WHERE b.active_flag = ?
				AND b.employment_status_id IN(
					 SELECT 
					  DISTINCT employment_status_id
					 FROM 
					 $this->tbl_param_payroll_type_status_offices 
					 WHERE payroll_type_id = ?
				) 
				AND b.employ_office_id IN(
					SELECT 
					 DISTINCT office_id 
					FROM $this->tbl_param_payroll_type_status_offices 
					WHERE payroll_type_id = ?
				)
				
				$filter_str
				
				GROUP BY employee_id
				ORDER BY employee_name asc, employ_start_date desc
				
				$sLimit				
EOS;
*/
// ====================== jendaigo : start : include work exp date range based on attendance_period_hdr ============= //
			$query = <<<EOS
				SELECT  SQL_CALC_FOUND_ROWS $fields 
				FROM $this->tbl_employee_personal_info a
				JOIN $this->tbl_employee_work_experiences b ON a.employee_id = b.employee_id 
				LEFT JOIN $this->tbl_param_offices c ON $work_experienxe_office_id = c.office_id 
				LEFT JOIN $this->db_core.$this->tbl_organizations e ON c.org_code = e.org_code				
				LEFT JOIN $this->tbl_param_employment_status f ON b.employment_status_id = f.employment_status_id
				LEFT JOIN $this->tbl_attendance_period_summary g ON g.employee_id = a.employee_id AND g.attendance_period_hdr_id = ?
				LEFT JOIN $this->tbl_attendance_period_hdr h ON h.attendance_period_hdr_id =  ?
				WHERE (IFNULL(b.employ_end_date, CURRENT_DATE) >= h.date_from AND b.employ_start_date <= h.date_to)
				AND b.employment_status_id IN(
					 SELECT 
					  DISTINCT employment_status_id
					 FROM 
					 $this->tbl_param_payroll_type_status_offices 
					 WHERE payroll_type_id = ?
				) 
				AND b.employ_office_id IN(
					SELECT 
					 DISTINCT office_id 
					FROM $this->tbl_param_payroll_type_status_offices 
					WHERE payroll_type_id = ?
				)
				
				$filter_str
				
				GROUP BY employee_id
				ORDER BY employee_name asc, employ_start_date desc
				
				$sLimit				
EOS;
// ====================== jendaigo : end : include work exp date range based on attendance_period_hdr ============= //
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
	
	public function get_all_table_employee_list($payroll_type_id, $payroll_period_id)
	{
		try
		{
			$fields = "a.employee_id, b.separation_mode_id, IFNULL(c.working_hours, 0) worked_hours";
			// $val 	= array($payroll_period_id, YES, $payroll_type_id, $payroll_type_id);
/*
			$query = <<<EOS
				SELECT  $fields 
				FROM $this->tbl_employee_personal_info a
				JOIN $this->tbl_employee_work_experiences b ON a.employee_id = b.employee_id
				LEFT JOIN $this->tbl_attendance_period_summary c ON c.employee_id = b.employee_id AND c.attendance_period_hdr_id = ? 
				WHERE b.active_flag = ?
				AND b.employment_status_id IN(
					 SELECT 
					  DISTINCT employment_status_id
					 FROM 
					 param_payroll_type_status_offices 
					 WHERE payroll_type_id = ?
				) 
				AND b.employ_office_id IN(
					SELECT 
					 DISTINCT office_id 
					FROM param_payroll_type_status_offices 
					WHERE payroll_type_id = ?
				)
				GROUP BY employee_id
EOS;
*/
			// ====================== jendaigo : start : include getting work exp based on period effectivity date ============= //
			$val 	= array($payroll_period_id, $payroll_period_id, $payroll_type_id, $payroll_type_id);
			$query = <<<EOS
				SELECT  $fields 
				FROM $this->tbl_employee_personal_info a
				JOIN $this->tbl_employee_work_experiences b ON a.employee_id = b.employee_id
				LEFT JOIN $this->tbl_attendance_period_summary c ON c.employee_id = b.employee_id AND c.attendance_period_hdr_id = ? 
				LEFT JOIN $this->tbl_attendance_period_hdr d ON d.attendance_period_hdr_id =  ?
				WHERE (IFNULL(b.employ_end_date, CURRENT_DATE) >= d.date_from AND b.employ_start_date <= d.date_to)
				AND b.employment_status_id IN(
					 SELECT 
					  DISTINCT employment_status_id
					 FROM 
					 param_payroll_type_status_offices 
					 WHERE payroll_type_id = ?
				) 
				AND b.employ_office_id IN(
					SELECT 
					 DISTINCT office_id 
					FROM param_payroll_type_status_offices 
					WHERE payroll_type_id = ?
				)
				GROUP BY employee_id
EOS;
			// ====================== jendaigo : end : include getting work exp based on period effectivity date ============= //

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
	
	/*
	 * $payroll_summary_id is a hashed value
	 */
	public function get_payroll_period($payroll_type_id, $payroll_summary_id)
	{
		try
		{
			$val 		= array(PAYOUT_TYPE_FLAG_REGULAR, $payroll_type_id);
			// $fields 	= "a.attendance_period_hdr_id, a.payroll_type_id, a.date_from, a.date_to, a.period_status_id";
			// marvin : add remarks for batching : start
			$fields 	= "a.attendance_period_hdr_id, a.payroll_type_id, a.date_from, a.date_to, a.period_status_id, a.remarks";
			// marvin : add remarks for batching : end
			$order_by 	= 'ORDER BY date_from DESC';
			
			$not_used 	= '';
			if ( empty($payroll_summary_id))
			{
				$not_used .= 'AND a.period_status_id = ?';
				$val[] = ATTENDANCE_PERIOD_PROCESSING;
			}
			else
			{
				$not_used .= 'AND (a.period_status_id = ? ';
				$not_used .= 'OR ';
				$not_used .= $this->get_hash_key('b.payroll_summary_id');
				$not_used .= ' = ?)';
				$val[] = ATTENDANCE_PERIOD_PROCESSING;
				$val[] = $payroll_summary_id;
			}
			
			$query = <<<EOS
				SELECT  $fields 
				FROM $this->tbl_attendance_period_hdr a
				LEFT JOIN $this->tbl_payout_summary b ON b.attendance_period_hdr_id = a.attendance_period_hdr_id
					AND b.payout_type_flag = ?
				WHERE a.payroll_type_id = ?
				
				$not_used
					
				$order_by
EOS;

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

	public function get_payroll_data($fields, $table, $where = array(), $multiple = TRUE, $order_by = array(), $group_by = array(), $limit = NULL)
	{
		try
		{
			//TODO: SET SESSION FOR GROUP_CONCAT_MAX_LEN
			//$stmt = $this->query("SET SESSION group_concat_max_len = " . GROUP_CONCAT_MAX_LENGTH, NULL, NULL);			
			
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

}