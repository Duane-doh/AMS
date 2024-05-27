<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Payroll_process_model extends Main_Model {

	private $db_core;
	
	public function __construct() {
		parent:: __construct();
	
		$this->db_core = DB_CORE;
	}
	
	// public function get_payout_compensations($payroll_summary_id, $date_to, $employment_type_tenure, $employment_status, $offices, $selected_employees=NULL)
	// ====================== jendaigo : start : include variable date_from ============= //
	public function get_payout_compensations($payroll_summary_id, $date_to, $employment_type_tenure, $employment_status, $offices, $selected_employees=NULL, $date_from)
	// ====================== jendaigo : end : include variable date_from ============= //
	{
		$compensations = array();

		try
		{
			$yes = YES;

			$tbl_anniv_emp 	= <<<EOS
					(SELECT e.employee_id, MIN(e.employ_start_date) anniv_emp_date 
					FROM $this->tbl_employee_work_experiences e
					WHERE e.employ_type_flag IN ('$employment_type_tenure')
					GROUP BY e.employee_id)
EOS;
			
			/*$fields = array('d.payroll_hdr_id', 'a.compensation_id', 'a.employee_id', 
				'b.frequency_id', 'b.compensation_code', 'b.compensation_type_flag', 'b.amount', 'b.multiplier_id', 'b.rate',
				'b.tenure_rqmt_flag', 'b.tenure_rqmt_val', 'b.taxable_flag', 'b.pro_rated_flag',
				'c.employ_salary_grade', 'c.employ_monthly_salary', 'a.start_date', 'a.end_date',
				'b.parent_compensation_id', 'b.inherit_parent_id_flag', 'IFNULL(e.anniv_emp_date, a.start_date) anniv_emp_date');
			*/
			// ====================== jendaigo : start : include report_short_code ============= //
			$fields = array('d.payroll_hdr_id', 'a.compensation_id', 'a.employee_id', 'b.frequency_id', 
				'b.compensation_code', 'b.report_short_code', 'b.compensation_type_flag', 'b.amount', 'b.multiplier_id', 'b.rate',
				'b.tenure_rqmt_flag', 'b.tenure_rqmt_val', 'b.taxable_flag', 'b.pro_rated_flag',
				'c.employ_salary_grade', 'c.employ_monthly_salary', 'a.start_date', 'a.end_date',
				'b.parent_compensation_id', 'b.inherit_parent_id_flag', 'IFNULL(e.anniv_emp_date, a.start_date) anniv_emp_date');
			// ====================== jendaigo : end   : include report_short_code ============= //
			/*
			$tables	= array(
				'main'	=> array(
					'table'		=> $this->tbl_employee_compensations,
					'alias'		=> 'a'
				),
				'table1'=> array(
					'table'		=> $this->tbl_param_compensations,
					'alias'		=> 'b',
					'type'		=> 'JOIN',
					'condition'	=> 'a.compensation_id = b.compensation_id'
				),
				'table2'=> array(
					'table'		=> $this->tbl_employee_work_experiences,
					'alias'		=> 'c',
					'type'		=> 'JOIN',
					'condition'	=> 'a.employee_id = c.employee_id AND c.active_flag = \'' . $yes . '\''
				),
				'table3'=> array(
					'table'		=> $this->tbl_payout_header,
					'alias'		=> 'd',
					'type'		=> 'JOIN',
					'condition'	=> 'a.employee_id = d.employee_id'
				),
				'table4'=> array(
					'table'		=> $tbl_anniv_emp,
					'alias'		=> 'e',
					'type'		=> 'LEFT JOIN',
					'condition'	=> 'e.employee_id = d.employee_id'
				)
			);
			*/
			// ====================== jendaigo : start : delete work exp active flag ============= //
			$tables	= array(
				'main'	=> array(
					'table'		=> $this->tbl_employee_compensations,
					'alias'		=> 'a'
				),
				'table1'=> array(
					'table'		=> $this->tbl_param_compensations,
					'alias'		=> 'b',
					'type'		=> 'JOIN',
					'condition'	=> 'a.compensation_id = b.compensation_id'
				),
				'table2'=> array(
					'table'		=> $this->tbl_employee_work_experiences,
					'alias'		=> 'c',
					'type'		=> 'JOIN',
					'condition'	=> 'a.employee_id = c.employee_id'
				),
				'table3'=> array(
					'table'		=> $this->tbl_payout_header,
					'alias'		=> 'd',
					'type'		=> 'JOIN',
					'condition'	=> 'a.employee_id = d.employee_id'
				),
				'table4'=> array(
					'table'		=> $tbl_anniv_emp,
					'alias'		=> 'e',
					'type'		=> 'LEFT JOIN',
					'condition'	=> 'e.employee_id = d.employee_id'
				)
			);
			// ====================== jendaigo : end : delete work exp active flag ============= //

			$where 				= array();
			$key 				= 'd.payroll_summary_id';
			$where[$key]		= $payroll_summary_id;
			$key 				= 'b.general_payroll_flag';
			$where[$key]		= YES;
			$key 				= 'IF(a.start_date IS NULL, current_date(), a.start_date)';
			$where[$key]		= array($date_to, array("<="));
			$key 				= 'IF(a.end_date IS NULL, current_date(), a.end_date)';
			$where[$key]		= array($date_to, array(">="));
			
			// ====================== jendaigo : start : include work exp date range to payroll date ============= //
			$key 				= 'IF(c.employ_start_date IS NULL, current_date(), c.employ_start_date)';
			$where[$key]		= array($date_to, array("<="));
			$key 				= 'IF(c.employ_end_date IS NULL, current_date(), c.employ_end_date)';
			$where[$key]		= array($date_from, array(">="));
			// ====================== jendaigo : end : include work exp date range to payroll date ============= //
			
			$key				= 'c.employment_status_id';
			$where[$key] 		= array($employment_status, array("IN"));
			$key 				= 'c.employ_office_id';
			$where[$key] 		= array($offices, array("IN"));
		
			if ( ! empty($selected_employees))
			{
				if (is_array($selected_employees))
					$where['d.employee_id']	= array($selected_employees, array('IN'));
				else
					$where['d.employee_id']	= $selected_employees;
			}			
			
			$order_by			= array("d.payroll_hdr_id" => 'ASC');
			$compensations 		= $this->select_all($fields, $tables, $where, $order_by);

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
		
		return $compensations;
	}
	
	// public function get_payout_deductions($payroll_summary_id, $covered_date_to, $employment_status, $offices, $sys_param=array(), $selected_employees=NULL)
	// ====================== jendaigo : start : include var covered_date_from ============= //
	public function get_payout_deductions($payroll_summary_id, $covered_date_to, $employment_status, $offices, $sys_param=array(), $selected_employees=NULL, $covered_date_from)
	// ====================== jendaigo : start : include var covered_date_from ============= //
	{
		$deductions = array();
		
		try
		{
			$comp_id_basic_salary 		= (isset($sys_param[PARAM_COMPENSATION_ID_BASIC_SALARY]) ? $sys_param[PARAM_COMPENSATION_ID_BASIC_SALARY] : 1);
			$comp_id_basic_salary_adj	= (isset($sys_param[PARAM_COMPENSATION_ID_BASIC_SALARY_ADJUSTMENT]) ? $sys_param[PARAM_COMPENSATION_ID_BASIC_SALARY_ADJUSTMENT] : 2);
			
			/*
			$val = array(YES, YES, $payroll_summary_id, $comp_id_basic_salary, $comp_id_basic_salary_adj, 
						$payroll_summary_id, $covered_date_to);
			*/
			
			/*
			IFNULL(E.payment_count, 0) paid_count,
			SELECT p2.employee_id, p1.deduction_id, COUNT(DISTINCT p2.payroll_summary_id) payment_count
					FROM payout_details p1, payout_header p2
					WHERE p1.payroll_hdr_id = p2.payroll_hdr_id AND p1.deduction_id IS NOT NULL
						AND p1.amount > 0
					GROUP BY employee_id, deduction_id 
			 */			
			
			/*			
			$query = <<<EOS
				SELECT D.payroll_hdr_id, A.deduction_id, 
					A.employee_id, B.deduction_code, B.statutory_flag, B.frequency_id, 
					B.month_pay_num, B.deduction_type_flag, B.employer_share_flag, IFNULL(B.amount, 0) amount, 
					B.multiplier_id, B.rate, B.priority_num, C.employ_monthly_salary, IFNULL(A.payment_count, 0) payment_count, 
					IFNULL(A.paid_count, 0) paid_count, 
					GROUP_CONCAT(F.payment_count ORDER BY F.employee_deduction_detail_id separator ',') payment_bkdown_count, 
					GROUP_CONCAT(F.amount ORDER BY F.employee_deduction_detail_id separator ',') payment_bkdown_amount, 
					GROUP_CONCAT(DISTINCT G.other_deduction_detail_value separator ',') deduction_references, 
					H.salary_paid, B.parent_deduction_id, B.inherit_parent_id_flag
				FROM employee_deductions A
				JOIN param_deductions B ON A.deduction_id = B.deduction_id
					JOIN employee_work_experiences C ON A.employee_id = C.employee_id AND C.active_flag = ?  
					JOIN payout_header D ON A.employee_id = D.employee_id 
 
					LEFT JOIN employee_deduction_details F ON F.employee_deduction_id  = A.employee_deduction_id 
					LEFT JOIN employee_deduction_other_details G ON G.employee_deduction_id  = A.employee_deduction_id
					AND G.other_deduction_detail_id IN (
						SELECT pg.other_deduction_detail_id FROM param_other_deduction_details pg 
						WHERE pk_flag = ?)
				
					LEFT JOIN (SELECT z.payroll_hdr_id, SUM(z.amount) salary_paid FROM payout_details z 
							JOIN payout_header x ON z.payroll_hdr_id  = x.payroll_hdr_id 
								AND x.payroll_summary_id = ?
							WHERE z.compensation_id IN (?, ?) GROUP BY z.payroll_hdr_id) H 
						ON H.payroll_hdr_id = D.payroll_hdr_id
				
				WHERE D.payroll_summary_id = ?
					AND IF(A.start_date IS NULL, current_date(), A.start_date) <= ?  
EOS;
			*/
			/* ====================== jendaigo : start : 
			$var include another $cover_date_to for deduction_detail_details start_date checking and $covered_date_from for work exp checking
			$query include employ_type_flag , fullname and paid_bkdown_count 
			============= */

			$val = array(YES, $payroll_summary_id, $comp_id_basic_salary, $comp_id_basic_salary_adj, 
						$payroll_summary_id, $covered_date_to, $covered_date_to, $covered_date_from, $covered_date_to); 
						
			$query = <<<EOS
				SELECT D.payroll_hdr_id, A.deduction_id, 
					A.employee_id, B.deduction_code, B.statutory_flag, B.frequency_id, 
					B.month_pay_num, B.deduction_type_flag, B.employ_type_flag, B.employer_share_flag, IFNULL(B.amount, 0) amount, 
					B.multiplier_id, B.rate, B.priority_num, C.employ_type_flag we_employ_type_flag, C.employ_monthly_salary, IFNULL(A.payment_count, 0) payment_count, 
					IFNULL(A.paid_count, 0) paid_count, 
					GROUP_CONCAT(F.payment_count ORDER BY F.employee_deduction_detail_id separator ',') payment_bkdown_count, 
					GROUP_CONCAT(F.amount ORDER BY F.employee_deduction_detail_id separator ',') payment_bkdown_amount, 
					GROUP_CONCAT(DISTINCT G.other_deduction_detail_value separator ',') deduction_references, 
					H.salary_paid, B.parent_deduction_id, B.inherit_parent_id_flag,
					CONCAT(I.last_name,if(I.ext_name='','',CONCAT(' ',I.ext_name)),", ",I.first_name," ",LEFT(I.middle_name,1), '.') as fullname,
					
					GROUP_CONCAT(J.paid_count ORDER BY J.employee_deduction_detail_detail_id separator ',') paid_bkdown_count
					
				FROM employee_deductions A
				JOIN param_deductions B ON A.deduction_id = B.deduction_id
					JOIN employee_work_experiences C ON A.employee_id = C.employee_id
					JOIN payout_header D ON A.employee_id = D.employee_id 
 
					LEFT JOIN employee_deduction_details F ON F.employee_deduction_id  = A.employee_deduction_id 
					LEFT JOIN employee_deduction_other_details G ON G.employee_deduction_id  = A.employee_deduction_id
					AND G.other_deduction_detail_id IN (
						SELECT pg.other_deduction_detail_id FROM param_other_deduction_details pg 
						WHERE pk_flag = ?)
				
					LEFT JOIN (SELECT z.payroll_hdr_id, SUM(z.amount) salary_paid FROM payout_details z 
							JOIN payout_header x ON z.payroll_hdr_id  = x.payroll_hdr_id 
								AND x.payroll_summary_id = ?
							WHERE z.compensation_id IN (?, ?) GROUP BY z.payroll_hdr_id) H 
						ON H.payroll_hdr_id = D.payroll_hdr_id
					
					LEFT JOIN employee_personal_info I ON I.employee_id  = A.employee_id 

					LEFT JOIN employee_deduction_detail_details J ON F.employee_deduction_detail_id  = J.employee_deduction_detail_id 
					
				WHERE D.payroll_summary_id = ?
					AND IF(A.start_date IS NULL, current_date(), A.start_date) <= ?  
					AND IF(J.start_date IS NULL, current_date(), J.start_date) <= ?  
					AND F.payment_count > J.paid_count
					AND (IFNULL(C.employ_end_date, CURRENT_DATE) >= ? AND C.employ_start_date <= ?)
EOS;
			/* ====================== jendaigo : end : 
			$var include another $cover_date_to for deduction_detail_details start_date checking and $covered_date_from for work exp checking
			$query include employ_type_flag , fullname and paid_bkdown_count 
			============= */

			if ( ! empty($selected_employees))
			{
				if (is_array($selected_employees))
				{
					$emp_filter = '';
					foreach($selected_employees as $emp)
					{
						if ( ! empty($emp_filter))
							$emp_filter .= ',';
						
						$emp_filter .= '?';
						$val[] = $emp;
					}				
					
					$query 	.= " AND D.employee_id IN ( $emp_filter )";
				}
				else
				{
					$query 	.= " AND D.employee_id = ? ";
					$val[] = $selected_employees;
				}
			}			
			
			$query	.= " GROUP BY payroll_hdr_id, deduction_id, employee_id, deduction_code, statutory_flag, frequency_id, month_pay_num, 
								deduction_type_flag, employer_share_flag, amount, multiplier_id, rate, priority_num, 
								employ_monthly_salary, payment_count, paid_count
						ORDER BY D.payroll_hdr_id ASC, B.priority_num DESC ";

			$deductions = $this->query($query, $val, TRUE);

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
		
		return $deductions;
	}	
	
	public function get_payout_deductions_orig($payroll_summary_id, $covered_date_to, $employment_status, $offices, $selected_employees=NULL)
	{
		$deductions = array();
		
		try
		{
			//TODO: SET SESSION FOR GROUP_CONCAT_MAX_LEN
			//$stmt = $this->query("SET SESSION group_concat_max_len = " . GROUP_CONCAT_MAX_LENGTH, NULL, NULL);			
			
			//COUNT(p1.effective_date) payment_count
			//AND p1.effective_date <= $covered_date_to
			$table_paid	= <<<EOS
					(SELECT p2.employee_id, p1.deduction_id, COUNT(DISTINCT p2.payroll_summary_id) payment_count
					FROM payout_details p1, payout_header p2
					WHERE p1.payroll_hdr_id = p2.payroll_hdr_id AND p1.deduction_id IS NOT NULL
						AND p1.amount > 0
					GROUP BY employee_id, deduction_id) 
EOS;

			$table_salary_paid = <<<EOS
					(SELECT z.payroll_hdr_id, SUM(IFNULL(z.amount, 0)) salary_paid FROM payout_details z 
						JOIN payout_header x ON z.payroll_hdr_id  = x.payroll_hdr_id 
							AND x.payroll_summary_id = ?
						WHERE z.compensation_id IN (?, ?) GROUP BY z.payroll_hdr_id)
EOS;

			$fields 	= array('D.payroll_hdr_id', 'A.deduction_id', 'A.employee_id', 'B.deduction_code', 'B.statutory_flag',
						'B.frequency_id', 'B.month_pay_num', 'B.deduction_type_flag', 'B.employer_share_flag', 
						'IFNULL(B.amount, 0) amount', 'B.multiplier_id', 'B.rate', 'B.priority_num',
						'C.employ_monthly_salary', 'IFNULL(A.payment_count, 0) payment_count', 'IFNULL(E.payment_count, 0) paid_count',
						'GROUP_CONCAT(F.payment_count ORDER BY F.employee_deduction_detail_id separator \',\') payment_bkdown_count',
						'GROUP_CONCAT(F.amount ORDER BY F.employee_deduction_detail_id separator \',\') payment_bkdown_amount',
						'GROUP_CONCAT(DISTINCT G.other_deduction_detail_value separator \',\') deduction_references',
						'H.salary_paid'
					);
			
			$tables		= array(
				'main'	=> array(
					'table'		=> $this->tbl_employee_deductions,
					'alias'		=> 'A'
				),
				'table1'=> array(
					'table'		=> $this->tbl_param_deductions,
					'alias'		=> 'B',
					'type'		=> 'JOIN',
					'condition'	=> 'A.deduction_id = B.deduction_id'
				),
				'table2'=> array(
					'table'		=> $this->tbl_employee_work_experiences,
					'alias'		=> 'C',
					'type'		=> 'JOIN',
					'condition'	=> 'A.employee_id = C.employee_id AND C.active_flag = \'' . YES . '\''
				),
				'table3'=> array(
					'table'		=> $this->tbl_payout_header,
					'alias'		=> 'D',
					'type'		=> 'JOIN',
					'condition'	=> 'A.employee_id = D.employee_id'
				),
				'table4'=> array(
					'table'		=> $table_paid,
					'alias'		=> 'E',
					'type'		=> 'LEFT JOIN',
					'condition'	=> 'E.employee_id = A.employee_id AND E.deduction_id = B.deduction_id'
				),
				'table5'=> array(
					'table'		=> $this->tbl_employee_deduction_details,
					'alias'		=> 'F',
					'type'		=> 'LEFT JOIN',
					'condition'	=> 'F.employee_deduction_id  = A.employee_deduction_id'
				),
				'table6'=> array(
					'table'		=> $this->tbl_employee_deduction_other_details,
					'alias'		=> 'G',
					'type'		=> 'LEFT JOIN',
					'condition'	=> 'G.employee_deduction_id  = A.employee_deduction_id
									AND G.other_deduction_detail_id IN (
										SELECT pg.other_deduction_detail_id FROM param_other_deduction_details pg 
										WHERE pk_flag = \'' . YES . '\')'
				),
				'table7'=> array(
					'table'		=> $table_salary_paid,
					'alias'		=> 'H',
					'type'		=> 'LEFT JOIN',
					'condition'	=> 'H.payroll_hdr_id = D.payroll_hdr_id'
				)
			);

			$where 				= array();
			$key 				= 'D.payroll_summary_id';
			$where[$key]		= $payroll_summary_id;
			
			$key 				= 'IF(A.start_date IS NULL, current_date(), A.start_date)';
			$where[$key]		= array($covered_date_to, array("<="));
			
			$key 				= 'C.employment_status_id';
			$where[$key] 		= array($employment_status, array("IN"));
			$key 				= 'C.employ_office_id';
			$where[$key] 		= array($offices, array('IN'));
			
			if ( ! empty($selected_employees))
			{
				if (is_array($selected_employees))
					$where['D.employee_id']	= array($selected_employees, array('IN'));
				else
					$where['D.employee_id']	= $selected_employees;
			}			
			
			$order_by			= array('D.payroll_hdr_id' => 'ASC', 'B.priority_num' => 'DESC');
			//$group_by			= array('payroll_hdr_id', 'deduction_id', 'employee_id');
			
			$group_by			= array('payroll_hdr_id', 'deduction_id', 'employee_id', 'deduction_code', 'statutory_flag', 'frequency_id', 
									'month_pay_num', 'deduction_type_flag', 'employer_share_flag', 'amount', 
									'multiplier_id', 'rate', 'priority_num', 'employ_monthly_salary', 'payment_count', 'paid_count');
			
			$deductions 		= $this->select_all($fields, $tables, $where, $order_by, $group_by);
			
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
		
		return $deductions;
	}

	public function get_employee_tenure_for_longevity($effective_date, $employees=NULL, $employ_type_tenure=array())
	{
		$employee_tenure = array();
		try 
		{
			//TODO: SET SESSION FOR GROUP_CONCAT_MAX_LEN
			//$stmt = $this->query("SET SESSION group_concat_max_len = " . GROUP_CONCAT_MAX_LENGTH, NULL, NULL);			
			
			$fields 			= array('a.employee_id',
									'a.employ_start_date', 
									'a.employ_monthly_salary', 
									'a.employ_plantilla_id', 
									'a.employ_position_id', 
									'a.employ_salary_grade', 
									'a.employ_salary_step', 
									'TIMESTAMPDIFF(MONTH, MIN(c.employ_start_date), DATE_ADD(MAX(IFNULL(c.employ_end_date,\''.$effective_date.'\')), INTERVAL 1 DAY)) AS tenure_months',
									'GROUP_CONCAT(DISTINCT IFNULL(b.lp_num, 0) ORDER BY b.lp_num DESC, b.effective_date DESC) lp_num', 
									'GROUP_CONCAT(DISTINCT IFNULL(b.basic_amount, 0) ORDER BY b.lp_num DESC, b.effective_date DESC) basic_amount',
									'GROUP_CONCAT(DISTINCT IFNULL(b.pay_amount, 0) ORDER BY b.lp_num DESC, b.effective_date DESC) pay_amount',
									'GROUP_CONCAT(DISTINCT IFNULL(b.total_amount, 0) ORDER BY b.lp_num DESC, b.effective_date DESC) total_amount',
									'GROUP_CONCAT(DISTINCT b.effective_date ORDER BY b.lp_num DESC, b.effective_date DESC) effective_date',
									'GROUP_CONCAT(DISTINCT b.tenure_effective_date ORDER BY b.lp_num DESC, b.tenure_effective_date DESC) tenure_effective_date',
								);

			$tables				= array(
				'main'	=> array(
					'table'		=> $this->tbl_employee_work_experiences,
					'alias'		=> 'a'
				),
				'table1'=> array(
					'table'		=> $this->tbl_employee_longevity_pay,
					'alias'		=> 'b',
					'type'		=> 'LEFT JOIN',
					'condition'	=> 'b.employee_id = a.employee_id AND b.active_flag = \''.YES.'\''
				),
				'table2'	=> array(
					'table'		=> $this->tbl_employee_work_experiences,
					'alias'		=> 'c',
					'type'		=> 'JOIN',
					'condition'	=> 'c.employee_id = a.employee_id AND c.employ_type_flag = a.employ_type_flag'
				)
			);

			$where							= array();
			
			$where['a.employ_start_date']	= array($effective_date, array('<='));
			if ( ! empty($employ_type_tenure))
				$where['a.employ_type_flag']	= array($employ_type_tenure, array('IN'));

			if ( ! empty($employees))
			{
				$key 			= 'a.employee_id';
				if (is_array($employees))
					$where[$key]	= array($employees, array('IN'));
				else
					$where[$key]	= $employees;
			}

			$group_by = array('employee_id', 'employ_start_date', 'employ_monthly_salary', 'employ_plantilla_id', 
							'employ_position_id', 'employ_salary_grade', 'employ_salary_step');
			$order_by = array('employee_id' => 'ASC', 'employ_start_date' => 'ASC');
			$employee_tenure 	= $this->select_all($fields, $tables, $where, $order_by, $group_by);
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
		
		return $employee_tenure;		
	}	

	public function get_employee_for_longevity($employee_id=NULL, $effective_year=NULL, $payout_summary_id=NULL)
	{
		try
		{
			$val = array(LONGE_PAY_CHECK_START_YEAR, LONGE_PAY_CHECK_START_YEAR, 
						LONGE_PAY_CHECK_START_YEAR, LONGE_PAY_CHECK_START_MONTH, 
						LONGE_PAY_CHECK_START_YEAR, LONGE_PAY_CHECK_START_DAY,
						);
			
			//AND IF(B.curr_lp_date IS NULL, ?, B.curr_lp_date) BETWEEN A.employ_start_date AND A.employ_end_date
			$query = <<<EOS
				SELECT A.employee_id, 
					GROUP_CONCAT(A.employ_monthly_salary ORDER BY a.employ_start_date DESC) employ_monthly_salary, 
					GROUP_CONCAT(IFNULL(A.employ_plantilla_id, 0) ORDER BY a.employ_start_date DESC) employ_plantilla_id, 
					GROUP_CONCAT(IFNULL(A.employ_position_id, 0) ORDER BY a.employ_start_date DESC) employ_position_id, 
					GROUP_CONCAT(IFNULL(A.employ_salary_grade, 0) ORDER BY a.employ_start_date DESC) employ_salary_grade, 
					GROUP_CONCAT(IFNULL(A.employ_salary_step, 0) ORDER BY a.employ_start_date DESC) employ_salary_step, 
					GROUP_CONCAT(IFNULL(A.employ_start_date, 0) ORDER BY a.employ_start_date DESC) employ_start_date, 
					IF (YEAR(MIN(A.employ_start_date)) < ?, ?, YEAR(MIN(A.employ_start_date))) start_year,
					IF (YEAR(MIN(A.employ_start_date)) < ?, ?, MONTH(MIN(A.employ_start_date))) start_month,
					IF (YEAR(MIN(A.employ_start_date)) < ?, ?, DAY(MIN(A.employ_start_date))) start_day,
					YEAR(MIN(A.employ_start_date)) employ_start_year,
					MONTH(MIN(A.employ_start_date)) employ_start_month,
					DAY(MIN(A.employ_start_date)) employ_start_day, 
					B.curr_lp_date,
					IFNULL(B.lp_num, 0) lp_num,
					IFNULL(B.basic_amount, 0) basic_amount,
					IFNULL(B.pay_amount, 0) pay_amount,
					IFNULL(B.total_amount, 0) total_amount
				FROM employee_work_experiences A
					LEFT JOIN (SELECT B.employee_id, MAX(B.effective_date) curr_lp_date, MAX(B.lp_num) lp_num, 
							MAX(B.basic_amount) basic_amount, MAX(B.pay_amount) pay_amount, MAX(B.total_amount) total_amount
							FROM employee_longevity_pay B GROUP BY employee_id) B 
						ON A.employee_id = B.employee_id
				WHERE A.employ_type_flag IN ('AP', 'WP', 'OG') 
EOS;

			$with_where = FALSE;
			if ( ! empty($employee_id))
			{
				$query	.= ' AND A.employee_id = ? ';
				$val[] 	= $employee_id;
			}
			
			$query .= ' GROUP BY employee_id ';

			return $this->query($query, $val, TRUE);
		}
		catch(Exception $e)
		{
			RLog::error($e->getMessage());
			throw $e;
		}
	}	

	public function get_employee_with_lca($employee_id=NULL, $effective_year=NULL, $payout_summary_id=NULL)
	{
		try
		{
			$val = array();
			
			$query = <<<EOS
				SELECT employee_id, 
					MAX(milestone_year) milestone_year, 
					GROUP_CONCAT(milestone_year ORDER BY milestone_year) csv_milestone_years,
					GROUP_CONCAT(effective_date ORDER BY milestone_year) csv_effective_dates, 
					GROUP_CONCAT(deferred_up_to_date ORDER BY milestone_year) csv_deferred_dates,
					GROUP_CONCAT(award_year ORDER BY milestone_year) csv_award_years
				FROM employee_loyalty_cash_awards
				WHERE 1=1 
EOS;

			$with_where = FALSE;
			if ( ! empty($employee_id))
			{
				$query	.= ' AND employee_id = ? ';
				$val[] 	= $employee_id;
			}

			if ( ! empty($payout_summary_id))
			{
				$query .= ' AND payroll_summary_id <> ? ';
				$val[] 	= $payout_summary_id;
			}

			if ( ! empty($effective_year))
			{
				$query .= ' AND YEAR(effective_date) <> ? ';
				$val[] 	= $effective_year;
			}
			
			$query .= ' GROUP BY employee_id ';

			return $this->query($query, $val, TRUE);
		}
		catch(Exception $e)
		{
			RLog::error($e->getMessage());
			throw $e;
		}
	}

	/**
	 * This function retrieves summarized work experience of an employee.
	 * @access public
	 * @param $employee_id
	 * @param $covered_period array with keys 'start_date', 'end_date'
	 */	
	public function get_employee_work_experiences($employee_id, $covered_period=array())
	{
		RLog::info("get_employee_work_experiences [$employee_id]");
			RLog::info($covered_period);
		if (empty($employee_id))
			throw new Exception('EMPLOYEE WORK EXPERIENCE: ' . $this->lang->line('param_not_defined'));
		
		$employee_work_exp = array();
		try 
		{
			$fields = array('employee_work_experience_id', 'employ_start_date', 'employ_end_date',
					'PERIOD_DIFF(EXTRACT(YEAR_MONTH FROM IFNULL(employ_end_date, NOW())), 
						EXTRACT(YEAR_MONTH FROM employ_start_date)) no_months', 
					'govt_service_flag', 'IFNULL(employ_office_id, 0) employ_office_id', 
					'IFNULL(service_lwop, 0) service_lwop',
					'active_flag');
			$table	= $this->tbl_employee_work_experiences;
			$where	= array();
			$where['employee_id'] = $employee_id;
			
			if ( ! empty($covered_period))
				$where['employ_start_date'] = array($covered_period, array('BETWEEN'));

			$order_by = array('employ_start_date' => 'ASC');
			$employee_work_exp = $this->select_all($fields, $table, $where, $order_by);	
		}
		catch (Exception $e)
		{
			RLog::error($e->getMessage());
			throw $e;
		}
		
		return $employee_work_exp;
	}

	public function get_actual_payout_by_month($employee_id=NULL, $covered_period=array(), $compensation=array(), $deduction=array(), $multiple=TRUE)
	{
		$sys_params = array();
		
		try
		{
			$fields = array('a.employee_id', 'MONTH(b.effective_date) as effective_month', 
						'YEAR(b.effective_date) as effective_year', 'SUM(IFNULL(b.amount, 0)) amount');
			$tables	= array(
				'main'	=> array(
					'table'		=> $this->tbl_payout_header,
					'alias'		=> 'a'
				),
				'table1'=> array(
					'table'		=> $this->tbl_payout_details,
					'alias'		=> 'b',
					'type'		=> 'JOIN',
					'condition'	=> 'b.payroll_hdr_id = a.payroll_hdr_id'
				)
			);
			
			$where = array();
			if ( ! empty($employee_id))
				$where['a.employee_id'] = $employee_id;

			if ( ! empty($compensation))
				$where['b.compensation_id'] = array($compensation, array('IN'));

			if ( ! empty($deduction))
				$where['b.deduction_id'] = array($deduction, array('IN'));

			if ( ! empty($covered_period))
				$where['b.effective_date'] = array($covered_period, array('BETWEEN'));

			
			$group_by = array('employee_id', 'effective_year', 'effective_month');

			if ($multiple)
				return $this->select_all($fields, $tables, $where, NULL, $group_by);
			else
				return $this->select_one($fields, $tables, $where, NULL, $group_by);
		}
		catch(Exception $e)
		{
			RLog::error($e->getMessage());
			throw $e;
		}
	}

	/**
	 * This function retrieves number of days an employee is present or with pay within given attendance period.
	 * @access public
	 * @param $attendance_period_hdr_id
	 * @param $employee_id
	 */	
	public function get_employee_days_present($attendance_period_hdr_id=NULL, $covered_period=array(), $employee_id=NULL)
	{
		if (empty($attendance_period_hdr_id) && empty($covered_period))
			throw new Exception('ATTENDANCE PERIOD/COVERAGE: ' . $this->lang->line('sys_param_not_defined'));
		
		$employee_days_present = array();
		try 
		{
			// get attendance status with pay
			$where['sys_param_type']	= PARAM_ATTENDANCE_STAT_WITH_TENURE;
			$where['active_flag']    	= YES;
			$field	= array('sys_param_value');
			$table  = DB_CORE.'.'.$this->tbl_sys_param;
			$sys_param_values = $this->select_all($field, $table, $where);
			
			$status_with_pay = array();
			foreach ($sys_param_values as $stat)
			{
				$status_with_pay[] = $stat['sys_param_value'];
			}
			
			$fields = array('employee_id', 'COUNT(DISTINCT attendance_date) days_present');
			$table	= $this->tbl_attendance_period_dtl;
			$where	= array();
			$where['attendance_status_id'] = array($status_with_pay, array('IN'));
			
			if ( ! empty($attendance_period_hdr_id))
				$where['attendance_period_hdr_id'] = $attendance_period_hdr_id;
			else
			{
				$where['attendance_date'] = array($covered_period, array('BETWEEN'));
			}

			if ( ! empty($employee_id))
			{
				$where['employee_id'] = $employee_id;
				$employee_days_present = $this->select_one($fields, $table, $where);
			}
			else
			{
				$group_by = array('employee_id');
				$employee_days_present = $this->select_all($fields, $table, $where, NULL, $group_by);	
			}
		}
		catch (Exception $e)
		{
			RLog::error($e->getMessage());
			throw $e;
		}
		
		return $employee_days_present;
	}

	public function get_payroll_period_by_type($payroll_period_id)
	{
		try
		{
			//TODO: SET SESSION FOR GROUP_CONCAT_MAX_LEN
			//$stmt = $this->query("SET SESSION group_concat_max_len = " . GROUP_CONCAT_MAX_LENGTH, NULL, NULL);			
			
			$where					= array();
			$key					= 'attendance_period_hdr_id';
			$where[$key]			= $payroll_period_id;
			$fields 				= array( 'a.date_from', 'a.date_to', 'a.payroll_type_id', 
										'b.tax_table_flag', 'b.salary_frequency_flag', 'b.payout_count', 'b.min_monthly_payout', 
										'b.monthly_payroll_count', 'b.mwe_denominator',
										'group_concat(distinct(c.employment_status_id) separator \',\') as employment_status_id', 
										'group_concat(distinct(c.office_id) separator \',\') as office_id');
			
			$tables					= array(
				'main'	=> array(
					'table'		=> $this->payroll_process->tbl_attendance_period_hdr,
					'alias'		=> 'a'
				),
				'table1'=> array(
					'table'		=> $this->payroll_process->tbl_param_payroll_types,
					'alias'		=> 'b',
					'type'		=> 'JOIN',
					'condition'	=> 'a.payroll_type_id = b.payroll_type_id'
				),
				'table2'=> array(
					'table'		=> $this->payroll_process->tbl_param_payroll_type_status_offices,
					'alias'		=> 'c',
					'type'		=> 'JOIN',
					'condition'	=> 'b.payroll_type_id = c.payroll_type_id'
				)
			);

			return $this->select_one($fields, $tables, $where);
		}
		catch (PDOException $e)
		{
			RLog::error($e->getMessage());
			throw $e;
		}
	}
	
	// public function insert_payroll_header($payroll_summary_id, $employment_status, $offices, $included_employees)
	// ====================== jendaigo : start : include attendance_period_hdr_id ============= //
	public function insert_payroll_header($payroll_summary_id, $employment_status, $offices, $included_employees, $attendance_period_hdr_id)
	// ====================== jendaigo : end : include attendance_period_hdr_id ============= //
	{
		try
		{
			// $select_fields 	= array($payroll_summary_id, "B.employee_id", "CONCAT_WS(' ',CONCAT(B.last_name, ','),B.first_name,' ',B.middle_name,' ',B.ext_name) employee_name", 
								// "A.employ_plantilla_id", "A.employ_office_id", "IFNULL(A.employ_office_name, '') employ_office_name", 
								// "IFNULL(A.employ_position_name, '') employ_position_name", 
								// "A.employ_salary_grade", "IFNULL(A.employ_salary_step, 1) employ_salary_step", "IFNULL(A.employ_monthly_salary, 0) employ_monthly_salary", "0");
			// ====================== jendaigo : start : change name format ============= //
			$select_fields 	= array($payroll_summary_id, "B.employee_id", "CONCAT(B.last_name, ', ', B.first_name, IF(B.ext_name='', '', CONCAT(' ', B.ext_name)), IF((B.middle_name='NA' OR B.middle_name='N/A' OR B.middle_name='-' OR B.middle_name='/'), '', CONCAT(' ', B.middle_name))) employee_name", 
								"A.employ_plantilla_id", "A.employ_office_id", "IFNULL(A.employ_office_name, '') employ_office_name", 
								"IFNULL(A.employ_position_name, '') employ_position_name", 
								"A.employ_salary_grade", "IFNULL(A.employ_salary_step, 1) employ_salary_step", "IFNULL(A.employ_monthly_salary, 0) employ_monthly_salary", "0");
			// ====================== jendaigo : end : change name format ============= //
			
			$insert_table	= $this->tbl_payout_header;
			$select_where	= array();
			$select_where['A.employment_status_id'] = $employment_status;
			$select_where['A.employ_office_id'] 	= $offices;
			if ( ! empty($included_employees))
				$select_where['B.employee_id'] 		= $included_employees;
			
			$val		= array(); 
			$fields 	= str_replace(" , ", " ", implode(", ", $select_fields));
			// $where 		= ' WHERE A.active_flag = \'' . YES . '\''; 
			$where 		= ' WHERE C.attendance_period_hdr_id = \'' . $attendance_period_hdr_id . '\''; //jendaigo : include attendance_period_hdr_id
			foreach ($select_where as $column_name => $value) 
			{
				$column_value 	= str_replace(" , ", " ", implode(", ", $value));
				$where 			.= ' AND ' .  $column_name . ' IN(' . $column_value. ')';
			}
/*
			$query = <<<EOS
				INSERT INTO $insert_table 
				(payroll_summary_id, employee_id, employee_name, plantilla_item_number, office_id, office_name, position_name, 
						salary_grade, pay_step, basic_amount, tenure_in_months)
					SELECT $fields 
					FROM $this->tbl_employee_work_experiences A
					JOIN $this->tbl_employee_personal_info B
					ON A.employee_id = B.employee_id
					$where
EOS;
*/
// ====================== jendaigo : start : include tbl_attendance_period_hdr ============= //
$query = <<<EOS
				INSERT INTO $insert_table 
				(payroll_summary_id, employee_id, employee_name, plantilla_item_number, office_id, office_name, position_name, 
						salary_grade, pay_step, basic_amount, tenure_in_months)
					SELECT $fields 
					FROM $this->tbl_employee_work_experiences A
					JOIN $this->tbl_employee_personal_info B
					ON A.employee_id = B.employee_id
					JOIN $this->tbl_attendance_period_hdr C
					ON ( A.employ_start_date <= C.date_to AND
						 IFNULL(A.employ_end_date, current_date) >= C.date_from)
					$where
EOS;

// ====================== jendaigo : end : include tbl_attendance_period_hdr ============= //
			$stmt = $this->query($query, NULL, NULL);
			
			$param_office_type = OFFICE_TYPE_OFFICE;
			$query = <<<EOS
				UPDATE payout_header A 
				SET A.office_id = IFNULL(get_parent_office(A.office_id, $param_office_type), A.office_id)
				WHERE A.payroll_summary_id  = ?
EOS;
			$upd = $this->query($query, array($payroll_summary_id), NULL);
						
			$query = <<<EOS
				UPDATE payout_header A 
					JOIN param_offices B ON A.office_id = B.office_id
					JOIN $this->db_core.$this->tbl_organizations C ON B.org_code = C.org_code
				SET A.office_name = C.name
				WHERE A.payroll_summary_id  = ?
EOS;
			$upd = $this->query($query, array($payroll_summary_id), NULL);
			
			return $stmt;
		}
		catch (PDOException $e)
		{
			RLog::error($e->getMessage());
			throw $e;
		}
	}
	
	public function update_payroll_hdr_pay($payroll_hdr_id)
	{
		try
		{
			$key = $this->get_hash_key('a.payroll_hdr_id');
			$where = " WHERE $key = '$payroll_hdr_id' ";
			
			$query = <<<EOS
				UPDATE $this->tbl_payout_header a
					SET a.total_income = (SELECT SUM(IF(b.compensation_id IS NOT NULL, b.amount, 0)) FROM $this->tbl_payout_details b 
							WHERE b.payroll_hdr_id = a.payroll_hdr_id ),
						a.total_deductions = (SELECT SUM(IF(b.deduction_id IS NOT NULL, b.amount, 0)) FROM $this->tbl_payout_details b 
							WHERE b.payroll_hdr_id = a.payroll_hdr_id ),
						a.net_pay = (a.total_income - a.total_deductions)
					$where
EOS;
			$stmt = $this->query($query, NULL, NULL);
		
			return $stmt;
		}
		catch (PDOException $e)
		{
			RLog::error($e->getMessage());
			throw $e;
		}
	}	
	
	public function insert_longevity_milestone($lp_eff_date, $lp_num, $param, $lp_amounts, $same_lp_num=FALSE, $first=TRUE)
	{
		try
		{
			//RLog::error("insert longevity milestone orig: LP EFF DTE [$lp_eff_date] ET EFF DTE [{$param['effective_date']}] CURR LP [$lp_num] SAME LP? [$same_lp_num] FIRST ? [$first]");
			
			$raw_lp_amount		= (isset($lp_amounts['raw_amount']) ? $lp_amounts['raw_amount'] : 0.00);
			$total_lp_amount	= (isset($lp_amounts['total_amount']) ? $lp_amounts['total_amount'] : 0.00);
			
			$fields = array();
			
			$fields['lp_num'] 					= $lp_num;
			$fields['effective_date'] 			= $lp_eff_date;
			$fields['tenure_effective_date']	= $param['employ_start_date'];
			if ( ! $first) // if not first LP record
			{
				// update other employee rec to 'N'
				$field['active_flag'] 	= NO;
				
				$where['employee_id'] 			= $param['employee_id'];
				$where['lp_num'] 				= $param['lp_num'];
				$where['effective_date']		= $param['effective_date'];
				$where['active_flag']			= YES;
				//$where['tenure_effective_date']	= $param['tenure_effective_date'];
				$table = $this->tbl_employee_longevity_pay;
				
				$this->update_data($table, $field, $where);
			}
			
			$fields['employee_id'] 			= $param['employee_id'];
			$fields['plantilla_id']			= $param['employ_plantilla_id'];
			$fields['position_id']			= $param['employ_position_id'];
			$fields['salary_grade']			= $param['employ_salary_grade'];
			$fields['salary_step']			= $param['employ_salary_step'];
			$fields['basic_amount'] 		= $param['employ_monthly_salary'];
			$fields['pay_amount'] 			= $raw_lp_amount;
			$fields['total_amount'] 		= $total_lp_amount;
			$fields['active_flag'] 			= YES;
			
			$table = $this->tbl_employee_longevity_pay;
			
			$return_arr = array('pay_amount' => $raw_lp_amount, 'total_amount' => $total_lp_amount,
					'lp_num' => $fields['lp_num'], 
					'lp_effective_date' => $fields['effective_date'],
					'lp_tenure_effective_date' => $fields['tenure_effective_date']);
			
			$return_id = $this->insert_data($table, $fields, TRUE, TRUE);
			
			//RLog::info('S: employee_longevity_pay return id: [' . $fields['employee_id'] . '] ['.$return_id.'] ['.$return_arr['total_amount'].']' );
			
			return $return_arr;
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
	
	public function update_longevity_milestone($effective_date, $param, $longevity_pay_rate)
	{
		try
		{
			// new monthly salary: update current LP record in employee_longevity_pay
			$raw_lp_amount		= round( ($param['employ_monthly_salary'] * $longevity_pay_rate), 2, PHP_ROUND_HALF_UP);
			$old_base_lp_amount	= $param['total_amount'] - $param['pay_amount'];
			$total_lp_amount	= $old_base_lp_amount + $raw_lp_amount;
			
			// update to other employee rec to 'N'
			$fields['effective_date']	= $effective_date;
			$fields['basic_amount']		= $param['employ_monthly_salary'];
			$fields['pay_amount']		= $raw_lp_amount;
			$fields['total_amount']		= $total_lp_amount;
			$fields['active_flag']		= YES;
			
			$where['employee_id'] = $param['employee_id'];
			$where['lp_num'] = $param['lp_num'];
			$table = $this->tbl_employee_longevity_pay;
			
			$this->update_data($table, $fields, $where);
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
		
		return $total_lp_amount;
	}	

	
	public function get_payout_employees($payroll_summary_id, $employee=NULL)
	{
		try
		{
			//TODO: SET SESSION FOR GROUP_CONCAT_MAX_LEN
			//$stmt = $this->query("SET SESSION group_concat_max_len = " . GROUP_CONCAT_MAX_LENGTH, NULL, NULL);
			
			$field = array('GROUP_CONCAT(employee_id SEPARATOR \',\') employees');
			$where = array();
			$where['payroll_summary_id'] = $payroll_summary_id;
			$where['included_flag'] = YES;
			
			if ( ! is_null($employee))
			{
				if (is_array($employee))
					$where['employee_id'] = array($employee, array('IN'));
				else
					$where['employee_id'] = $employee;
			}
			
			$table = $this->common->tbl_payout_employee;
			return $this->common->get_general_data($field, $table, $where, FALSE);
		}
		catch (PDOException $e)
		{
			RLog::error($e->getMessage());
			throw $e;
		}
	}
	
	public function update_attendance_period($payroll_period_id, $new_status_id)
	{
		try
		{
			$table = $this->tbl_attendance_period_hdr;
			$fields	= array('period_status_id' => $new_status_id);
			$where = array('attendance_period_hdr_id' => $payroll_period_id);

			$this->update_data($table, $fields, $where);
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
	
	public function update_deduction_paid_count($payroll_summary_id, $selected_employees=array(), $effective_date=NULL, $add_flag=TRUE)
	{
		// NOTE $effective_date IS UNUSED
		try
		{
			/*
			$val = array($payroll_summary_id);
			
			$csv_selected_employees = "";
			if ( ! empty($selected_employees))
			{
				if (is_array($selected_employees))
				{
					$emp_filter = '';
					foreach($selected_employees as $emp)
					{
						if ( ! empty($emp_filter))
							$emp_filter .= ',';
						
						$emp_filter .= '?';
						$val[] = $emp;
					}				
					
					$csv_selected_employees	.= " AND B.employee_id IN ( $emp_filter )";
				}
				else
				{
					$csv_selected_employees	.= " AND B.employee_id = ? ";
					$val[] = $selected_employees;
				}
			}

			$val[] = NO;
			$update_clause = " A2.paid_count = (A3.paid_count+1), A3.paid_count = (A3.paid_count+1) ";
			if ( ! $add_flag)
				$update_clause = " A3.paid_count = (IF(A3.paid_count <= 0, 0, A3.paid_count-1)) ";
			
				$query = <<<EOS
					UPDATE 
						(SELECT B.payroll_hdr_id, B.employee_id, IFNULL(A.raw_deduction_id, A.deduction_id) A1_deduction_id, SUM(A.amount) amount 
							FROM payout_details A
								JOIN payout_header B ON A.payroll_hdr_id = B.payroll_hdr_id AND B.payroll_summary_id = ?
										$csv_selected_employees
								JOIN param_deductions C ON A.raw_deduction_id = C.deduction_id AND C.statutory_flag = ?
							WHERE A.deduction_id IS NOT NULL
							GROUP BY employee_id, A1_deduction_id
							HAVING amount > 0) A1
							
							JOIN payout_details A2 ON A2.deduction_id = A1.A1_deduction_id AND A2.payroll_hdr_id = A1.payroll_hdr_id
							JOIN employee_deductions A3 ON A3.employee_id = A1.employee_id AND A3.deduction_id = A1.A1_deduction_id
					
					SET $update_clause
EOS;

			$stmt = $this->query($query, $val, NULL);

			return $stmt;
			*/
			// ====================== jendaigo : start : modify paid count update ============= //
			//GET ATTENDANCE PERIOD HDR DETAILS
			$field = array('a.attendance_period_hdr_id, b.date_to, b.payroll_type_id, c.effective_date');
			$table = $this->payroll_process->tbl_attendance_period_hdr;
			$tables					= array(
				'main'	=> array(
					'table'		=> $this->payroll_process->tbl_payout_summary,
					'alias'		=> 'a'
				),
				't1'	=> array(
					'table'		=> $this->payroll_process->tbl_attendance_period_hdr,
					'alias'		=> 'b',
					'type'		=> 'JOIN',
					'condition'	=> 'b.attendance_period_hdr_id = a.attendance_period_hdr_id'
				),
				't2'	=> array(
					'table'		=> $this->payroll_process->tbl_payout_summary_dates,
					'alias'		=> 'c',
					'type'		=> 'JOIN',
					'condition'	=> 'c.payout_summary_id = a.payroll_summary_id'
				)
			);

			$where = array();
			$where['a.payroll_summary_id'] 	= $payroll_summary_id;
			
			$attendance_period = $this->common->get_general_data($field, $tables, $where, FALSE);

			//GET PAYOUT DETAILS
			$field = array('a.employee_id', 'b.payroll_dtl_id', 'b.paid_count AS payroll_dtl_paid_count', 
							'b.deduction_id', 'c.deduction_type_flag', 'd.paid_count', 'd.employee_deduction_id');
			$tables					= array(
				'main'	=> array(
					'table'		=> $this->payroll_process->tbl_payout_header,
					'alias'		=> 'a'
				),
				't1'	=> array(
					'table'		=> $this->payroll_process->tbl_payout_details,
					'alias'		=> 'b',
					'type'		=> 'JOIN',
					'condition'	=> 'b.payroll_hdr_id = a.payroll_hdr_id'
				),
				't2'	=> array(
					'table'		=> $this->payroll_process->tbl_param_deductions,
					'alias'		=> 'c',
					'type'		=> 'JOIN',
					'condition'	=> 'c.deduction_id = b.raw_deduction_id'
				),
				't3'	=> array(
					'table'		=> $this->payroll_process->tbl_employee_deductions,
					'alias'		=> 'd',
					'type'		=> 'JOIN',
					'condition'	=> 'd.deduction_id = c.deduction_id AND
									d.employee_id = a.employee_id ',
				)
			);
			
			$where = array();
			$where['a.payroll_summary_id'] 	= $payroll_summary_id;
			$where['c.statutory_flag'] 		= NO;
			
			$payout_dtls = $this->common->get_general_data($field, $tables, $where, TRUE);

			$field = array('GROUP_CONCAT(DISTINCT d.deduction_id) as deduction_ids');
			$emp_ded_dtls = $this->common->get_general_data($field, $tables, $where, FALSE);

			$field = array('GROUP_CONCAT(DISTINCT d.employee_deduction_id) as employee_deduction_id');
			$where['c.deduction_type_flag'] = DEDUCTION_TYPE_FLAG_SCHEDULED;
			$payout_ded_dtls = $this->common->get_general_data($field, $tables, $where, FALSE);

			//GET EMPLOYEE DEDUCTION DETAILS
			$fields = array('a.employee_deduction_id, a.payment_count AS payment_count_dtl, b.employee_deduction_detail_detail_id, b.paid_count AS paid_count_dtl');
			
			$tables					= array(
				'main'	=> array(
					'table'		=> $this->payroll_process->tbl_employee_deduction_details,
					'alias'		=> 'a'
				),
				't1'	=> array(
					'table'		=> $this->payroll_process->tbl_employee_deduction_detail_details,
					'alias'		=> 'b',
					'type'		=> 'INNER JOIN',
					'condition'	=> 'b.employee_deduction_detail_id = a.employee_deduction_detail_id AND
									IF(b.start_date IS NULL, current_date(), b.start_date) <= "'.$attendance_period['date_to'].'" ',
				)
			);
			
			$where = array();
			$emp_ded_id_filter 					= explode(',', $payout_ded_dtls['employee_deduction_id']);
			$where['a.employee_deduction_id']	= array($emp_ded_id_filter, array('IN'));

			$employee_deduc_dtls = $this->common->get_general_data($fields, $tables, $where, TRUE);

			foreach($employee_deduc_dtls as $key_dtl => $employee_deduc_dtl)
			{
				$employee_ded_dtls[$employee_deduc_dtl['employee_deduction_id']][] = $employee_deduc_dtl;

				if(! $add_flag)
				{
					//GET EMPLOYEE DEDUCTION PAID COUNT DETAILS
					$fields = array('GROUP_CONCAT(DISTINCT attendance_period_hdr_id) as attendance_period_hdr_ids');
					$table = $this->payroll_process->tbl_employee_deduction_paid_count_details;
					
					$where = array();
					$where['employee_deduction_detail_detail_id']	= $employee_deduc_dtl['employee_deduction_detail_detail_id'];

					$employee_deduc_paid_count_dtls[$employee_deduc_dtl['employee_deduction_detail_detail_id']] = $this->common->get_general_data($fields, $table, $where, FALSE);
				}
			}

			//GET DEDUCTION PAYOUT DETAILS
			$dte_day_obj 		= new DateTime($attendance_period['effective_date']);
			$fmt_date_day 		= $dte_day_obj->format('d');
			$fmt_date_day 		= (int) $fmt_date_day;
			$payout_date_num 	= ($fmt_date_day > 15 ? 1 : 2);

			$table 						= $this->payroll_process->tbl_param_payroll_deductions;
			$fields						= array('deduction_id', 'payout_date_num');
			$where 						= array();
			$where['payroll_type_id'] 	= $attendance_period['payroll_type_id'];
			$emp_ded_dtls_filter 		= explode(',', $emp_ded_dtls['deduction_ids']);
			$where['deduction_id']		= array($emp_ded_dtls_filter, array('IN'));
			$where['payout_date_num']	= $payout_date_num;
			
			$deduction_payout_nums 		= $this->common->get_general_data($fields, $table, $where, TRUE);
			$deduction_payout_nums 		= set_key_value($deduction_payout_nums, 'deduction_id', 'payout_date_num');

			//UPDATE PAID COUNT
			if($payout_dtls)
			{
				foreach($payout_dtls as $key => $payout_dtl)
				{
					$payout_nums = isset($deduction_payout_nums[$payout_dtl['deduction_id']]) ? $deduction_payout_nums[$payout_dtl['deduction_id']] : NULL;

					if($payout_nums != null)
					{	
						$add_count = (! $add_flag) ? -1 : 1;
						if($payout_dtl['deduction_type_flag'] == DEDUCTION_TYPE_FLAG_SCHEDULED)
						{
							foreach($employee_ded_dtls[$payout_dtl['employee_deduction_id']] as $key_dtl => $employee_ded_dtl)
							{
								if((!$add_flag AND (in_array($attendance_period['attendance_period_hdr_id'],( explode(',', $employee_deduc_paid_count_dtls[$employee_ded_dtl['employee_deduction_detail_detail_id']]['attendance_period_hdr_ids']) ) ))) OR ($add_flag AND $employee_ded_dtl['payment_count_dtl'] > $employee_ded_dtl['paid_count_dtl']))	
								{
									//UPDATE TABLE EMPLOYEE DEDUCTION DETAIL DETAILS PAID COUNT
									$table 	= $this->tbl_employee_deduction_detail_details;
									$fields = array();

									$fields	= array('paid_count' => $employee_ded_dtl['paid_count_dtl']+$add_count);
									$where 	= array();
									$where 	= array('employee_deduction_detail_detail_id' => $employee_ded_dtl['employee_deduction_detail_detail_id']);

									$this->update_data($table, $fields, $where);

									//INSERT/DELETE TABLE EMPLOYEE DEDUCTION PAID COUNT DETAILS
									$table = $this->tbl_employee_deduction_paid_count_details;
									$fields = array();
									$fields['employee_deduction_detail_detail_id']	= $employee_ded_dtl['employee_deduction_detail_detail_id'];
									$fields['attendance_period_hdr_id'] 			= $attendance_period['attendance_period_hdr_id'];

									$where 	= array();
									$where 	= $fields;

									if($add_flag)
										$this->insert_data($table, $fields, TRUE, TRUE);
									else
										$this->delete_data($table, $where);
									
									$total_paid_count += $add_count; 
								}
							}
						}

						$add_count = ($total_paid_count) ? $total_paid_count : $add_count;
						$total_paid_count = 0;

						//UPDATE TABLE EMPLOYEE DEDUCTIONS PAID COUNT
						$table 	= $this->tbl_payout_details;
						$fields = array();
						$fields	= array('paid_count' => $add_count);
						$where 	= array();
						$where 	= array('payroll_dtl_id' => $payout_dtl['payroll_dtl_id']);

						$this->update_data($table, $fields, $where);

						//UPDATE TABLE EMPLOYEE DEDUCTIONS PAID COUNT
						$table 	= $this->tbl_employee_deductions;
						$fields = array();
						$fields	= array('paid_count' => $payout_dtl['paid_count']+$add_count);
						$where 	= array();
						$where 	= array('employee_deduction_id' => $payout_dtl['employee_deduction_id']);

						$this->update_data($table, $fields, $where);
					}
				}
			}
			// ====================== jendaigo : end : modify paid count update ============= //
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
	
	public function update_monetize_leave_details($payroll_summary_id, $paid_flag, $leave_detail_id_csv=NULL, $remove_flag=FALSE)
	{
		try
		{
			if (empty($payroll_summary_id) AND empty($leave_detail_id_csv))
				throw new Exception('Update Monetize Leave Details: ' . $this->lang->line('param_not_defined'));

			$upd_table 	= $this->tbl_employee_leave_details;
			$upd_fields	= array();
			$upd_fields['paid_flag']		= $paid_flag;
			
			$upd_where 	= array();
			if ($remove_flag)
			{
				$upd_fields['payout_summary_id']= NULL;
				$upd_where['payout_summary_id'] = $payroll_summary_id;
			}
			else
			{
				$upd_fields['payout_summary_id']= $payroll_summary_id;
				$leave_detaild_ids = explode(',', $leave_detail_id_csv);
				$upd_where['leave_detail_id'] 	= array($leave_detaild_ids, array('IN'));
			}
			
			$this->update_data($upd_table, $upd_fields, $upd_where);
		} 
		catch (Exception $e)
		{
			RLog::error($e->getMessage());
			throw $e;
		}
	}
	
	public function get_notification_params($module_id, $payout_status_id)
	{
		try
		{
			//SET SESSION FOR GROUP_CONCAT_MAX_LEN
			//$stmt = $this->query("SET SESSION group_concat_max_len = " . GROUP_CONCAT_MAX_LENGTH, NULL, NULL);			
			
			$field = array('action_id', 'approved_flag', 'return_flag');
			$where = array('payout_status_id' => $payout_status_id);
			$table = $this->tbl_param_payout_status;
			$payout_stat_rec = $this->select_one($field, $table, $where);			
			
			$val = array($module_id, $payout_stat_rec['action_id']);
			
			$query = <<<EOS
				SELECT GROUP_CONCAT(role_code) notify_roles
				FROM $this->db_core.$this->tbl_module_action_roles A JOIN $this->db_core.$this->tbl_module_actions B 
					ON A.module_action_id = B.module_action_id 
						AND B.module_id = ? AND B.action_id = ?
EOS;
			$data = $this->query($query, $val, FALSE);

			$payout_stat_rec['notify_roles'] = $data['notify_roles'];
			return $payout_stat_rec;
		}
		catch (Exception $e)
		{
		}
	}

	
	public function get_payout_for_the_month($comp_ded_flag=PAYOUT_DETAIL_TYPE_COMPENSATION, $base_payout_date, $year, $month, 
			$payroll_summary_id, $payroll_type_id,
			$included_employees=NULL)
	{
		try
		{
			$val = array($payroll_summary_id, $payroll_type_id, $base_payout_date, $year, $month);
			
			$query = '';
			if ($comp_ded_flag == PAYOUT_DETAIL_TYPE_COMPENSATION)
			{
				//SELECT B.employee_id, CONCAT('C', A.compensation_id) comp_ded_id,
				$query = <<<EOS
					SELECT B.employee_id, COUNT(DISTINCT C.payroll_summary_id) payroll_count, A.compensation_id comp_ded_id,
						SUM(A.amount) pay_amount, SUM(A.orig_amount) orig_amount, SUM(IFNULL(A.employer_amount, 0)) employer_amount 
					FROM payout_details A JOIN payout_header B ON A.payroll_hdr_id = B.payroll_hdr_id
						JOIN payout_summary C ON B.payroll_summary_id = C.payroll_summary_id AND C.payroll_summary_id != ? 
						JOIN attendance_period_hdr D ON C.attendance_period_hdr_id = D.attendance_period_hdr_id AND D.payroll_type_id = ?
						JOIN (SELECT payout_summary_id, YEAR(effective_date) pay_year, MONTH(effective_date) pay_month FROM payout_summary_dates
								WHERE effective_date <= ?
								GROUP BY payout_summary_id, pay_year, pay_month) E 
							ON E.payout_summary_id = C.payroll_summary_id AND E.pay_year = ? AND E.pay_month = ?
					WHERE A.compensation_id IS NOT NULL
EOS;
			}
			else
			{
				$query = <<<EOS
					SELECT B.employee_id, COUNT(DISTINCT C.payroll_summary_id) payroll_count, A.deduction_id comp_ded_id,
						SUM(A.amount) pay_amount, SUM(A.orig_amount) orig_amount, SUM(IFNULL(A.employer_amount, 0)) employer_amount 
					FROM payout_details A JOIN payout_header B ON A.payroll_hdr_id = B.payroll_hdr_id
						JOIN payout_summary C ON B.payroll_summary_id = C.payroll_summary_id AND C.payroll_summary_id != ?
						JOIN attendance_period_hdr D ON C.attendance_period_hdr_id = D.attendance_period_hdr_id AND D.payroll_type_id = ?
						JOIN (SELECT payout_summary_id, YEAR(effective_date) pay_year, MONTH(effective_date) pay_month FROM payout_summary_dates
								WHERE effective_date <= ?
								GROUP BY payout_summary_id, pay_year, pay_month) E 
							ON E.payout_summary_id = C.payroll_summary_id AND E.pay_year = ? AND E.pay_month = ?
					WHERE A.deduction_id IS NOT NULL
EOS;
			}

			if ( ! empty($included_employees))
			{
				if (is_array($included_employees))
				{
					$emp_filter = '';
					foreach($included_employees as $emp)
					{
						if ( ! empty($emp_filter))
							$emp_filter .= ',';
						
						$emp_filter .= '?';
						$val[] = $emp;
					}				
					
					$query 	.= " AND B.employee_id IN ( $emp_filter )";
				}
				else
				{
					$query 	.= " AND B.employee_id = ? ";
					$val[] = $included_employees;
				}
			}
			
			$query .= ' GROUP BY employee_id, comp_ded_id';
			
			//RLog::error($query);
			//RLog::error($val);

			$values = $this->query($query, $val, TRUE);
			
			/*
			$arr_key 	= array('employee_id');
			$arr_val 	= array('comp_ded_id' => 'orig_amount');
			$return_arr = set_key_value($values, $arr_key, $arr_val, FALSE);
			*/
			$arr_key 	= array('employee_id', 'comp_ded_id');
			$arr_val 	= array('payroll_count', 'orig_amount', 'pay_amount', 'employer_amount');
			$return_arr = set_key_value($values, $arr_key, $arr_val, TRUE);
			
			return $return_arr;
		}
		catch(Exception $e)
		{
			RLog::error($e->getMessage());
			throw $e;
		}
	}

	public function get_employee_with_deduction($start_date, $included_employees=array(), $deductions=array())
	{
		try
		{
			$val = array($start_date);
			
			$query = <<<EOS
				SELECT DISTINCT A.employee_id FROM employee_deductions A 
				WHERE A.start_date <= ?
EOS;
			
			if ( ! empty($included_employees))
			{
				if (is_array($included_employees))
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
				else
				{
					$query 	.= " AND A.employee_id = ? ";
					$val[] = $included_employees;
				}
			}
			
			if ( ! empty($deductions))
			{
				if (is_array($deductions))
				{
					$ded_filter = '';
					foreach($deductions as $ded)
					{
						if ( ! empty($ded_filter))
							$ded_filter .= ',';
						
						$ded_filter .= '?';
						$val[] = $ded;
					}				
					
					$query 	.= " AND A.deduction_id IN ( $ded_filter )";
				}
				else
				{
					$query 	.= " AND A.deduction_id = ? ";
					$val[] = $deductions;
				}
			}			
			
			$data = $this->query($query, $val, TRUE);
						
			return $data;
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
	
	public function update_payout_work_day_param($payroll_hdr_id, $work_day_param)
	{
		try
		{
			$val = array($work_day_param[KEY_MONTH_WORK_DAYS], $work_day_param[KEY_WORKED_DAYS], $work_day_param[KEY_DAILY_RATE], $payroll_hdr_id);
			
			$query = <<<EOS
				UPDATE $this->tbl_payout_header 
					SET month_work_days = ?, worked_days = ?, daily_rate = ?
				WHERE payroll_hdr_id = ?
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

	
	public function get_previous_work_day_param($date_from, $payroll_type_id, $payroll_hdr_id, $employee_id)
	{
		try
		{
			$dt = new DateTime($date_from);
			$pay_year = intval($dt->format('Y'));
			$pay_month = intval($dt->format('m'));
			
			$val = array($pay_year, $pay_month, $payroll_type_id, $payroll_hdr_id, $employee_id);
			
				$query = <<<EOS
					SELECT A.payroll_hdr_id, month_work_days, worked_days, daily_rate 
					FROM payout_header A JOIN payout_summary B ON A.payroll_summary_id = B.payroll_summary_id 
						JOIN attendance_period_hdr C ON B.attendance_period_hdr_id = C.attendance_period_hdr_id
							AND YEAR(C.date_from) = ? AND MONTH(C.date_from) = ?
					WHERE C.payroll_type_id = ? 
						AND A.payroll_hdr_id != ?
						AND A.employee_id = ?
EOS;
			
			//RLog::error($query);
			//RLog::error($val);

			$values = $this->query($query, $val, FALSE);
			//RLog::error($values);
			
			return $values;
		}
		catch(Exception $e)
		{
			RLog::error($e->getMessage());
			throw $e;
		}
	}

	public function get_special_privilege_leave($date_from, $date_to, $selected_employees=array())
	{
		try
		{
			$val = array($date_from, $date_to, LEAVE_TYPE_SPECIAL_PRIVILEGE, LEAVE_FILE_LEAVE);
			
			$query = <<<EOS
				SELECT employee_id, 
					SUM(IFNULL(leave_earned_used, 0)) leave_earned_used,
					SUM(IFNULL(leave_wop, 0)) leave_wop
				FROM employee_leave_details 
				WHERE 
					leave_start_date >= ? 
					AND leave_start_date <= ?
					AND leave_type_id = ? 
					AND leave_transaction_type_id = ? 
EOS;

			if ( ! empty($selected_employees))
			{
				if (is_array($selected_employees))
				{
					$emp_filter = '';
					foreach($selected_employees as $emp)
					{
						if ( ! empty($emp_filter))
							$emp_filter .= ',';
						
						$emp_filter .= '?';
						$val[] = $emp;
					}				
					
					$query 	.= " AND employee_id IN ( $emp_filter )";
				}
				else
				{
					$query 	.= " AND employee_id = ? ";
					$val[] = $selected_employees;
				}
			}
			
			$query .= ' GROUP BY employee_id';
			
			//RLog::error($query);
			//RLog::error($val);

			$filed_spl = $this->query($query, $val, TRUE);
			$filed_spl = set_key_value($filed_spl, 'employee_id', array('leave_earned_used', 'leave_wop'), TRUE);
			
			return $filed_spl;
		}
		catch(Exception $e)
		{
			RLog::error($e->getMessage());
			throw $e;
		}		
		
	}
	public function get_emp_longe_pay_history($employee_id,$start_date)
	{
		try
		{
			$val = array($start_date,$employee_id);
			
			$query = <<<EOS
				SELECT 
				A.effective_date start_date,
				ifnull(B.effective_date - INTERVAL 1 DAY,DATE_FORMAT(NOW(),'%Y-%m-%d')) end_date,
				A.total_amount amount
				FROM 
				$this->tbl_employee_longevity_pay A
				LEFT JOIN $this->tbl_employee_longevity_pay B ON A.employee_id = B.employee_id 
				AND B.effective_date = 
				(SELECT min(C.effective_date) 
					FROM $this->tbl_employee_longevity_pay C 
					WHERE A.employee_id = C.employee_id and C.effective_date > A.effective_date
				)
				WHERE 
				ifnull(B.effective_date - INTERVAL 1 DAY,DATE_FORMAT(NOW(),'%Y-%m-%d')) >= ?
				AND
				A.employee_id = ?
EOS;

			$stmt = $this->query($query, $val, TRUE);
			
			return $stmt;
		}
		catch(Exception $e)
		{
			RLog::error($e->getMessage());
			throw $e;
		}		
		
	}
	

	/*
	 * START: PARAMETER DATA
	 */
	public function get_sys_gen_params()
	{
		$sys_params = array();
		
		try
		{
			$fields = array('sys_param_type', 'sys_param_value');
			$table = $this->db_core.'.'.$this->tbl_sys_param;
			$where = array();
			$sys_param_types = array(PARAM_COMPENSATION_BASIC_SALARY,  
					PARAM_COMPENSATION_BASIC_SALARY_DEDUCTION, PARAM_COMPENSATION_ID_BASIC_SALARY_ADJUSTMENT, 
					PARAM_COMPENSATION_ID_BASIC_SALARY_DEDUCTIONS,
					PARAM_COMPENSATION_LONGEVITY_PAY, PARAM_COMPENSATION_LOYALTY, 
					PARAM_WORKING_MONTHS, PARAM_WORKING_DAYS, PARAM_WORKING_HOURS,
					PARAM_COMPENSATION_ID_BASIC_SALARY, PARAM_AMOUNT_TAXABLE_BENEFIT_CAP,
					PARAM_COMPENSATION_LONGEVITY_PAY_RATE, PARAM_COMPENSATION_LONGEVITY_MIN_TENURE,
					PARAM_LCA_AUTH_LWOP_FIRST, PARAM_LCA_AUTH_LWOP_NEXT, 
					PARAM_LCA_MILESTONE_YEAR_FIRST, PARAM_LCA_MILESTONE_YEAR_NEXT,
					PARAM_LCA_MILESTONE_FIRST_AMOUNT, PARAM_LCA_MILESTONE_NEXT_AMOUNT,
					PARAM_LCA_EFFECTIVE_START_DATE, PARAM_COMPENSATION_CODE_CNA,
					PARAM_DEDUCTION_ID_CNA, PARAM_COMPENSATION_CNA_AMOUNT,
					PARAM_COMPENSATION_CNA_MEMBER_RATE, PARAM_COMPENSATION_CNA_NON_MEMBER_RATE,
					PARAM_COMPENSATION_CODE_SALDIFFL, PARAM_COMPENSATION_CODE_HAZDIFFL, 
					PARAM_COMPENSATION_ID_HAZARD_PAY, PARAM_COMPENSATION_CODE_LONGEDIFFL,
					PARAM_COMPENSATION_ID_SALDIFFL, PARAM_DEDUCTION_ID_GSISDIFFL,
					PARAM_COMPENSATION_ID_HAZDIFFL, PARAM_COMPENSATION_ID_LONGEDIFFL,
					PARAM_COMPENSATION_ID_LONGEVITY_PAY, PARAM_DEDUCTION_ID_GSIS,
					PARAM_COMPENSATION_ID_TAX_REFUND_ANNUAL, PARAM_COMPENSATION_CODE_TAX_REFUND_ANNUAL,
					PARAM_COMPENSATION_ID_TAX_REFUND_MONTHLY, PARAM_COMPENSATION_CODE_TAX_REFUND_MONTHLY,
					PARAM_DEDUCTION_ST_BIR_ID, PARAM_MWE_COMPUTE_FLAG, PARAM_MWE_RATE,
					PARAM_COMPENSATION_ID_PREMIUM, PARAM_COMPENSATION_PREMIUM); //jendaigo : include premium id and code
			$where['sys_param_type']= array($sys_param_types, array('IN'));			

			$sys_param_values = $this->select_all($fields, $table, $where);

			foreach ($sys_param_values as $value)
			{
				$sys_params[$value['sys_param_type']] = $value['sys_param_value'];
			}
		}
		catch(Exception $e)
		{
			$this->rlog_error($e);
			throw $e;
		}

		return $sys_params;
	}
	
	public function get_gsis_table($effective_date)
	{
		try
		{
			$val = array(YES, $effective_date);
			
			$query = <<<EOS
				SELECT a.insurance_type_flag, 
					a.effective_date, 
					IFNULL(a.personal_share, 0) personal_share, 
					IFNULL(a.government_share, 0) government_share, 
					IFNULL(a.max_government_share, 0) max_government_share
				FROM param_gsis a
				WHERE 
				a.active_flag = ?
				AND a.effective_date <= ?
				ORDER BY effective_date DESC
EOS;
			$data = $this->query($query, $val, TRUE);
						
			return $data;
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
	
	public function get_pagibig_table($effective_date)
	{
		try
		{
			$val = array(YES);
			
			$query = <<<EOS
				SELECT IFNULL(salary_range_from, 0) salary_range_from, IFNULL(salary_range_to, 0) salary_range_to, 
					IFNULL(max_salary_range, 0) max_salary_range,
					IFNULL(employee_rate, 0) employee_rate, IFNULL(employer_rate, 0) employer_rate  
				FROM param_pagibig 
				WHERE active_flag = ?
				ORDER BY salary_range_from
EOS;
			$data = $this->query($query, $val, TRUE);
						
			return $data;
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
	
	public function get_philhealth_table($effective_date)
	{
		try
		{
			$val = array(YES);
			
			$query = <<<EOS
				SELECT * FROM param_philhealth WHERE active_flag = ?
				ORDER BY salary_range_from
EOS;
			$data = $this->query($query, $val, TRUE);
						
			return $data;
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
	
	public function get_sss_table($effective_date)
	{
		try
		{
			$val = array(YES);
			
			$query = <<<EOS
				SELECT * FROM param_sss WHERE active_flag = ?
				ORDER BY salary_range_from
EOS;
			$data = $this->query($query, $val, TRUE);
						
			return $data;
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
	
	public function get_doh_employ_type()
	{
		try
		{
			$table	= DB_CORE.'.'.$this->tbl_sys_param;
			$val	= array(PARAM_EMPLOY_TYPE_WITH_TENURE, YES);
			$query = <<<EOS
				SELECT GROUP_CONCAT(sys_param_value) sys_param_value FROM $table
				WHERE sys_param_type = ? AND active_flag = ?
EOS;
			$employ_type_tenure = $this->query($query, $val, FALSE);
			
			$employ_type_str = '';
			if ( ! empty($employ_type_tenure))
			{
				$employ_type_tenure	= explode(',' , $employ_type_tenure['sys_param_value']);
				$employ_type_tenure	= implode("','", $employ_type_tenure);
			}
			
			return $employ_type_tenure;
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

	public function get_govt_svc_employ_type($csv_flag=FALSE)
	{
		try
		{
			$table	= DB_CORE.'.'.$this->tbl_sys_param;
			//$val	= array(PARAM_EMPLOY_TYPE_WITH_TENURE, PARAM_EMPLOY_TYPE_WITH_TENURE_OG, YES);
			$val	= array(PARAM_EMPLOY_TYPE_WITH_TENURE, YES);
			$query = <<<EOS
				SELECT GROUP_CONCAT(sys_param_value) sys_param_value FROM $table
				WHERE sys_param_type = ? AND active_flag = ?
EOS;
			$employ_type_tenure = $this->query($query, $val, FALSE);
			
			$employ_type_str = '';
			if ( ! empty($employ_type_tenure))
			{
				$employ_type_tenure	= explode(',' , $employ_type_tenure['sys_param_value']);
				if ($csv_flag)
					$employ_type_tenure	= implode("','", $employ_type_tenure);
			}
			
			return $employ_type_tenure;
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
	 * END: PARAMETER DATA
	 */

}