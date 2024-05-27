<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Compensation_model extends Main_Model {

	public $db_core           = DB_CORE;
	public $tbl_organizations = "organizations";
	
	public $tbl_process_stage_roles = "process_stage_roles";
	public $tbl_actions             = "actions"; 
	public $tbl_process_stages      = "process_stages";
	public $tbl_process_steps       = "process_steps";


	public function get_benefit_list($aColumns, $bColumns, $params, $table, $where, $multiple = TRUE)
	{
		try
		{
			$val = array();
			$val[] = 'N';
			/* For Advanced Filters */
			if(ISSET($params['active_flag'])) {
				if(strtolower($params['active_flag']) == 'active') 
					$params['active_flag'] = 'Y';
				if(strtolower($params['active_flag']) == 'inactive')
					$params['active_flag'] = 'N';
			}

			$cColumns = array("compensation_name", "active_flag");
						
			$fields = str_replace(" , ", " ", implode(", ", $aColumns));
			
			$sWhere = $this->filtering($cColumns, $params, TRUE);
			$sOrder = $this->ordering($bColumns, $params);
			$sLimit = $this->paging($params);
			
			$filter_str = $sWhere["search_str"];
			$filter_params = $sWhere["search_params"];
			
			$query = <<<EOS
				SELECT SQL_CALC_FOUND_ROWS $fields 
				FROM param_compensations
				WHERE basic_salary_flag = ?
				$filter_str
	        	$sOrder
	        	$sLimit
EOS;

			$val = array_merge($val,$filter_params);
			
			$stmt = $this->query($query, $val, TRUE);
						
			return $stmt;
		}	
		catch (PDOException $e)
		{
			RLog::error($message);
			throw new Exception($e->getMessage() . ' --Line 43.');
		}
		catch (Exception $e)
		{			
			RLog::error($message);
			throw new Exception($e->getMessage());
		}

	}
	public function get_compensation_history_list($aColumns, $bColumns, $params, $multiple=TRUE)
	{
		try
		{

			$val              = array();
			$employee_id  	  = $params['employee_id'];
			$key              = $this->get_hash_key('B.employee_id');

			$fields        = str_replace(" , ", " ", implode(", ", $aColumns));

			if(ISSET($params['A-effective_date'])) 
			{
				$params["DATE_FORMAT(A-effective_date, '%Y/%m/%d')"] = $params['A-effective_date'];
			}
			$cColumns      = array("C-compensation_name", "DATE_FORMAT(A-effective_date, '%Y/%m/%d')", "A-amount");
			$sWhere        = $this->filtering($cColumns, $params, TRUE);
			$sOrder        = $this->ordering($bColumns, $params);
			$sLimit        = $this->paging($params);
			$filter_str    = $sWhere["search_str"];
			$filter_params = $sWhere["search_params"];
			$val[]         = $employee_id;
			$query = <<<EOS
				SELECT SQL_CALC_FOUND_ROWS $fields 
				FROM payout_details A 
				JOIN payout_header B ON A.payroll_hdr_id = B.payroll_hdr_id
				JOIN param_compensations C ON A.compensation_id = C.compensation_id
				WHERE $key = ? AND A.effective_date IS NOT NULL 
				$filter_str
				$sOrder
				$sLimit
EOS;
			$stmt = $this->query($query, array_merge($val,$filter_params), $multiple);
		
			return $stmt;
		}	
		catch (PDOException $e)
		{
			RLog::error($message);
			throw new Exception($e->getMessage() . ' --Line 91.');
		}
		catch (Exception $e)
		{			
			RLog::error($message);
			throw new Exception($e->getMessage());
		}
	}

	public function employee_payslip_history_list($aColumns, $bColumns, $params)
	{
		try
		{
			$val           = array();
			$employee_id   = $params['employee_id'];
			$key           = $this->get_hash_key('A.employee_id');
			
			$fields        = str_replace(" , ", " ", implode(", ", $aColumns));
			if(ISSET($params['B-effective_date']))
			{
				$params["DATE_FORMAT(B-effective_date, '%Y/%m/%d')"] = $params['B-effective_date'];
			}
			$cColumns      = array("DATE_FORMAT(B-effective_date, '%Y/%m/%d')", "SUM(A-total_income)", "SUM(A-total_deductions)", "SUM(A-net_pay)");
			$sWhere        = $this->filtering($cColumns, $params, TRUE);
			$sOrder        = $this->ordering($bColumns, $params);
			$sLimit        = $this->paging($params);
			$filter_str    = $sWhere["search_str"];
			$filter_params = $sWhere["search_params"];
			$val[]         = $employee_id;
			$query = <<<EOS
				SELECT SQL_CALC_FOUND_ROWS $fields 
				FROM $this->tbl_payout_header A
			        LEFT JOIN
			    (SELECT payroll_hdr_id, CONCAT(YEAR(B.effective_date), '/', LPAD(MONTH(B.effective_date), 2, '0')) date_month_year, effective_date 
					FROM $this->tbl_payout_details B 

					GROUP BY payroll_hdr_id, date_month_year) B
						ON B.payroll_hdr_id = A.payroll_hdr_id
				AND $key = ?
				$filter_str
				GROUP BY date_month_year
				$sOrder
				$sLimit
EOS;

			$stmt = $this->query($query,array_merge($val, $filter_params));
		
			return $stmt;
		}	
		catch (PDOException $e)
		{
			throw new Exception($e->getMessage() . ' --Line 135.');
		}
		catch (Exception $e)
		{			
			throw new Exception($e->getMessage());
		}
	}

	public function employee_payslip_history_details($aColumns, $bColumns, $params, $group_by="")
	{
		try
		{
			$add_where   = '';
			$cColumns    = array();
			$param_table = '';

			
			if((ISSET($params['effective_date'])) && (!EMPTY($params['effective_date'])))
			{
				$add_where .= ' AND B.effective_date = "' . $params['effective_date'] .'"';
			}
			
			if(ISSET($params['deduction']))
			{
				$add_where .= ' AND B.compensation_id IS NULL';
				$cColumns  = array("D.deduction_name", "D.amount", "B.employer_amount");
				$param_table = 'INNER JOIN param_deductions AS D ON B.deduction_id = D.deduction_id';
			}
			if(ISSET($params['compensation']))
			{
				$add_where .= ' AND B.deduction_id IS NULL';
				$cColumns  = array("D.compensation_name", "D.amount");
				$param_table = 'INNER JOIN param_compensations AS D ON B.compensation_id = D.compensation_id';
			}
			$val             = array();
			$payroll_hdr_id  = $params['payroll_id'];
			$key             = $this->get_hash_key('A.payroll_hdr_id');
			
			$fields          = str_replace(" , ", " ", implode(", ", $aColumns));
			$sWhere          = $this->filtering($cColumns, $params, FALSE);
			$sOrder          = $this->ordering($bColumns, $params);
			$sLimit          = $this->paging($params);
			$filter_str      = $sWhere["search_str"];
			$filter_params   = $sWhere["search_params"];
			$filter_params[] = $payroll_hdr_id;
			
			$query = <<<EOS
				SELECT $fields 
				FROM payout_header AS A
				LEFT JOIN payout_details AS B ON A.payroll_hdr_id = B.payroll_hdr_id
				LEFT JOIN payout_summary AS C ON A.payroll_summary_id = C.payroll_summary_id
				$param_table
				WHERE $key = ?
				$add_where
				$filter_str
				$group_by
				$sOrder
				$sLimit
EOS;
			$stmt = $this->query($query, $filter_params);
		
			return $stmt;
		}	
		catch (PDOException $e)
		{
			throw new Exception($e->getMessage() . ' --Line 195.');
		}
		catch (Exception $e)
		{			
			throw new Exception($e->getMessage());
		}
	}



	public function get_benefit_employee_list($aColumns, $bColumns, $params)
	{

		try
		{
			$val    = array();
			$val[]  = 'Y';
			$val[]  = $params['compensation_id'];
			$fields = str_replace(" , ", " ", implode(", ", $aColumns));

			$params["CONCAT(PI.last_name,if(PI.ext_name='','',CONCAT(' ',PI.ext_name)),\", \",PI.first_name,\" \",LEFT(PI.middle_name,1), '.')"] = $params['full_name'];

			if(!EMPTY($params['start_date']))
				$params['EC-start_date'] = format_date($params['start_date'],'Y-m-d');

			if(!EMPTY($params['end_date']))
				$params['EC-end_date'] = format_date($params['end_date'],'Y-m-d');

			$cColumns        = array("PI-agency_employee_id", "CONCAT(PI.last_name,if(PI.ext_name='','',CONCAT(' ',PI.ext_name)),\", \",PI.first_name,\" \",LEFT(PI.middle_name,1), '.')", "WE-employ_office_name",  "EC-start_date", "EC-end_date");
			$sWhere          = $this->filtering($cColumns, $params, TRUE);
			$sOrder          = $this->ordering($bColumns, $params);
			$sLimit          = $this->paging($params);
			$filter_str      = $sWhere["search_str"];
			$filter_params   = $sWhere["search_params"];
			$key             = $this->get_hash_key('EC.compensation_id');
			
			$query = <<<EOS
				SELECT SQL_CALC_FOUND_ROWS $fields 
				FROM employee_compensations AS EC
				LEFT JOIN param_compensations AS PB ON PB.compensation_id = EC.compensation_id
				LEFT JOIN $this->tbl_employee_personal_info AS PI ON PI.employee_id = EC.employee_id
				JOIN $this->tbl_employee_work_experiences AS WE ON PI.employee_id = WE.employee_id AND WE.active_flag = ?
				WHERE $key = ?
				$filter_str
				GROUP BY EC.compensation_id, PI.agency_employee_id, fullname, EC.start_date, EC.end_date
				$sOrder
				$sLimit
EOS;

			return $this->query($query, array_merge($val,$filter_params), TRUE);
		
		}	
		catch (PDOException $e)
		{
			throw new Exception($e->getMessage() . ' --Line 233.');
		}
		catch (Exception $e)
		{			
			throw new Exception($e->getMessage());
		}
	}
	
	public function employees_filtered_length($aColumns, $bColumns, $params)
	{
		try
		{
			$this->get_benefit_employee_list($aColumns, $bColumns, $params);
			
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
	public function employees_total_length($compensation_id)
	{
		try
		{
			$where = array();
			
			$fields = array("COUNT(*) cnt");
			$key = $this->get_hash_key('compensation_id');
			$where[$key] = $compensation_id;

			return $this->select_one($fields, $this->tbl_employee_compensations, $where);
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
	//DISPLAY ALL THE EMPLOYEE INSIDE THE BENEFITS
	public function get_personnel_benefit_list($aColumns, $bColumns, $params, $table, $where, $multiple = TRUE)
	{
		try
		{

			$fields = str_replace(" , ", " ", implode(", ", $aColumns));
			$cColumns = array("PB-compensation_name", "EC-start_date",  "PF-frequency_name", "PB-taxable");
			$sWhere = $this->filtering($cColumns, $params, FALSE);
			$sOrder = $this->ordering($bColumns, $params);
			$sLimit = $this->paging($params);
			$filter_str = $sWhere["search_str"];
			$filter_params = $sWhere["search_params"];
			
			$query = <<<EOS
				SELECT $fields 
				FROM $table AS EC
				LEFT JOIN param_compensations AS PB ON PB.compensation_id = EC.compensation_id
				JOIN param_frequency AS PF ON PF.frequency_id = PB.frequency_id
				$filter_str
				GROUP BY EC.employee_id
				$sOrder
				$sLimit
EOS;
			RLog::debug($query);
			$stmt = $this->query($query, $filter_params, $multiple);
		
			return $stmt;
		}	
		catch (PDOException $e)
		{
			throw new Exception($e->getMessage() . ' --Line 272.');
		}
		catch (Exception $e)
		{			
			throw new Exception($e->getMessage());
		}
	}

	public function get_employee_list($aColumns, $bColumns, $params, $module_id)
	{
		try
		{
			/* For Advanced Filters */
			if(!EMPTY($params['fullname']))
			$params["CONCAT(A.last_name,\" \",ifnull(A.ext_name,''),\", \",A.first_name,\" \",LEFT(A.middle_name,1), '.')"] = $params['fullname'];
			$cColumns      = array("A-agency_employee_id", "CONCAT(A.last_name,\" \",ifnull(A.ext_name,''),\", \",A.first_name,\" \",LEFT(A.middle_name,1), '.')", "E-name", "D-employment_status_name");
			
			$fields        = str_replace(" , ", " ", implode(", ", $aColumns));
			
			$sWhere        = $this->filtering($cColumns, $params, FALSE);

			$sOrder        = $this->ordering($bColumns, $params);
			$group_by	   = 'GROUP BY A.employee_id, A.agency_employee_id, fullname, office_name, D.employment_status_name, B.employ_office_id';
			$sLimit        = $this->paging($params);
			
			$filter_str    = $sWhere["search_str"];
			$filter_params = $sWhere["search_params"];

			$add_where     = '';
			if(!EMPTY($params['C-office_id'])) {
				$office_list = $this->get_office_child('', $params['C-office_id']);
				$add_where   = (EMPTY($filter_str) ? 'WHERE C.office_id IN (' . implode(',', $office_list) . ')' : ' AND C.office_id IN (' . implode(',', $office_list) . ')');
			}
			
			//marvin
			//added user_offices_scope
			$user_scopes['human_resources'] 		= isset($_SESSION['user_offices'][9]) ? $_SESSION['user_offices'][9] : '';
			$user_scopes['personal_data_sheets'] 	= isset($_SESSION['user_offices'][10]) ? $_SESSION['user_offices'][10] : '';
			$user_scopes['performance_evaluation'] 	= isset($_SESSION['user_offices'][11]) ? $_SESSION['user_offices'][11] : '';
			$user_scopes['time_and_attendance'] 	= isset($_SESSION['user_offices'][49]) ? $_SESSION['user_offices'][49] : '';
			$user_scopes['attendance_logs'] 		= isset($_SESSION['user_offices'][50]) ? $_SESSION['user_offices'][50] : '';
			$user_scopes['daily_time_record'] 		= isset($_SESSION['user_offices'][51]) ? $_SESSION['user_offices'][51] : '';
			$user_scopes['leaves'] 					= isset($_SESSION['user_offices'][53]) ? $_SESSION['user_offices'][53] : '';
			$user_scopes['payroll'] 				= isset($_SESSION['user_offices'][61]) ? $_SESSION['user_offices'][61] : '';
			$user_scopes['general_payroll'] 		= isset($_SESSION['user_offices'][63]) ? $_SESSION['user_offices'][63] : '';
			$user_scopes['special_payroll'] 		= isset($_SESSION['user_offices'][64]) ? $_SESSION['user_offices'][64] : '';
			$user_scopes['voucher'] 				= isset($_SESSION['user_offices'][65]) ? $_SESSION['user_offices'][65] : '';
			$user_scopes['remittance'] 				= isset($_SESSION['user_offices'][66]) ? $_SESSION['user_offices'][66] : '';
			$user_scopes['compensation'] 			= isset($_SESSION['user_offices'][12]) ? $_SESSION['user_offices'][12] : '';
			$user_scopes['deductions'] 				= isset($_SESSION['user_offices'][13]) ? $_SESSION['user_offices'][13] : '';
			
			if(empty($filter_str))
			{
				if(empty($add_where))
				{
					$add_where .= 'WHERE C.office_id IN('.$user_scopes['compensation'].')';
				}
			}
			else
			{
				$add_where .= 'AND C.office_id IN('.$user_scopes['compensation'].')';
			}
			//end

			$query = <<<EOS
				SELECT SQL_CALC_FOUND_ROWS $fields 
				FROM $this->tbl_employee_personal_info A
				JOIN $this->tbl_employee_work_experiences B ON A.employee_id = B.employee_id AND B.active_flag ='Y' AND B.employ_office_id IS NOT NULL
				LEFT JOIN $this->tbl_param_offices C ON C.office_id = B.employ_office_id
				LEFT JOIN $this->tbl_param_employment_status D ON B.employment_status_id = D.employment_status_id
				LEFT JOIN $this->db_core.$this->tbl_organizations E ON C.org_code = E.org_code
				
				$filter_str
				$add_where
				$group_by
	        	$sOrder
	        	$sLimit
EOS;
			
			$stmt = $this->query($query, $filter_params);
						
			return $stmt;
		}		
		catch (PDOException $e)
		{
			throw new Exception($e->getMessage() . ' --Line 324.');
		}
		catch (Exception $e)
		{			
			throw new Exception($e->getMessage());
		}
	}

	public function get_personnel_list($params)
	{
		try
		{
			$id              = array($params['compensation_id']);
			$compensation_id = $this->get_hash_key('compensation_id');

			if(!EMPTY($params['employee']))
			{
				$add_where = " AND B.employee_id IN (".$params['employee'] .")";
			}
			if($params['position'])
			{
				$add_where .= " AND B.employ_position_id = ".$params['position'];
			}
			if($params['office'])
			{
				$add_where .= " AND B.employ_office_id = ".$params['office'];
			}
			if($params['salary_grade'])
			{
				$add_where .= " AND B.employ_salary_grade = ".$params['salary_grade'];
			}
			if($params['designation'])
			{
				$add_where .= ' AND A.employee_id IN (SELECT 
														employee_id
														FROM employee_other_info 
														WHERE others_value = "' . $params['designation'] . '"
													)';
			}
			if(!EMPTY($params['rating']))
			{
				$add_where .= $this->_get_rating_param($params['rating']);
			}
			if(!EMPTY($params['employ_type_flag']))
			{
				$add_where .= ' AND B.employ_type_flag = "' . $params['employ_type_flag'] . '"';
			}
			if($params['coop_member'])
			{
				$in_not_in = "";
				
				if ($params['coop_member'] == YES)
				{
					$in_not_in = "IN";
				}else{
					$in_not_in = "NOT IN";
				}
				
				$add_where .= ' AND A.employee_id ' . $in_not_in .  ' ( SELECT A.employee_id FROM employee_deductions A
														JOIN param_deductions B ON A.deduction_id = B.deduction_id
														WHERE B.deduction_code = "UKK"
													 )';
			}
			
			if($params['action'] != ACTION_ADD)
			{
				$condition = 'IN';
			}
			else
			{
				$condition = 'NOT IN';
			}
			// EXCLUDING THE EMPLOYEES THAT IS IN THE CURRENT LIST
			$selected_employees = '';
			if(!EMPTY($params['employee_list'])) {
				if($params['action'] != ACTION_ADD)
				{
					$selected_employees = 'AND employee_id NOT IN (' . $params['employee_list'] . ')';
				}
				else
				{
					$selected_employees = 'OR employee_id IN (' . $params['employee_list'] . ')';
				}
			}
/*
			$query = <<<EOS
				SELECT A.employee_id,CONCAT(A.last_name, if(A.ext_name='','',CONCAT(' ', A.ext_name)),", ",A.first_name," ",LEFT(A.middle_name,1), '.') as fullname, A.agency_employee_id
				FROM $this->tbl_employee_personal_info A
				JOIN $this->tbl_employee_work_experiences B ON A.employee_id = B.employee_id AND B.active_flag = 'Y' AND B.employ_type_flag IN ('AP','WP','JO')
				LEFT JOIN $this->tbl_employee_performance_evaluations C
				ON A.employee_id = C.employee_id
				WHERE A.employee_id $condition 
				(SELECT employee_id FROM employee_compensations WHERE $compensation_id = ? $selected_employees) 
				AND B.active_flag = 'Y'
				$add_where
				
				GROUP BY employee_id

				ORDER BY CONCAT(A.last_name,if(A.ext_name='','',CONCAT(' ',A.ext_name)),", ",A.first_name," ",LEFT(A.middle_name,1), '.') ASC;

EOS;
*/
			// ====================== jendaigo : start : change name format ============= //
			$query = <<<EOS
				SELECT A.employee_id,CONCAT(A.last_name, ', ', A.first_name, IF(A.ext_name='', '', CONCAT(' ', A.ext_name)), IF((A.middle_name='NA' OR A.middle_name='N/A' OR A.middle_name='-' OR A.middle_name='/'), '', CONCAT(' ', LEFT(A.middle_name, 1), '. '))) as fullname, A.agency_employee_id
				FROM $this->tbl_employee_personal_info A
				JOIN $this->tbl_employee_work_experiences B ON A.employee_id = B.employee_id AND B.active_flag = 'Y' AND B.employ_type_flag IN ('AP','WP','JO')
				LEFT JOIN $this->tbl_employee_performance_evaluations C
				ON A.employee_id = C.employee_id
				WHERE A.employee_id $condition 
				(SELECT employee_id FROM employee_compensations WHERE $compensation_id = ? $selected_employees) 
				AND B.active_flag = 'Y'
				$add_where
				
				GROUP BY employee_id

				ORDER BY CONCAT(A.last_name, ', ', A.first_name, IF(A.ext_name='', '', CONCAT(' ', A.ext_name)), IF((A.middle_name='NA' OR A.middle_name='N/A' OR A.middle_name='-' OR A.middle_name='/'), '', CONCAT(' ', LEFT(A.middle_name, 1), '. '))) ASC;

EOS;
			// ====================== jendaigo : end : change name format ============= //
			
			return $this->query($query, $id, TRUE);
		}	
		catch (PDOException $e)
		{
			throw new Exception($e->getMessage() . ' --Line 389.');
		}
		catch (Exception $e)
		{			
			throw new Exception($e->getMessage());
		}	
	}


	public function get_specific_personnel_list($params)
	{
		try
		{
			$val             = array($params['id']);
			$employee_id     = $this->get_hash_key('A.employee_id');
			$compensation_id = $this->get_hash_key('compensation_id');
			$selected_where  = "";
			if($params['personnel_name'])
			{
				$selected_employees = "(";
				$x = 0;
				foreach($params['personnel_name'] as $employee)
				{
					$x++;
					$selected_employees .= "'".$employee."'";
					if($x < count($params['personnel_name']))
					{
						$selected_employees .= ",";
					}
				}
				$selected_employees .= ")";
				$selected_where = " AND ".$employee_id." IN ".$selected_employees;
			}

			$query = <<<EOS
				SELECT A.employee_id,CONCAT(A.first_name," ",A.middle_name," ",A.last_name," ",IF(A.ext_name IS NULL,'',A.ext_name)) as fullname,A.agency_employee_id
				FROM 
				$this->tbl_employee_personal_info A
				
				WHERE 1 = 1
				$selected_where
EOS;
	
			$stmt = $this->query($query, $val, TRUE);

			return $stmt;
		}	
		catch (PDOException $e)
		{
			throw new Exception($e->getMessage() . ' --Line 448.');
		}
		catch (Exception $e)
		{			
			throw new Exception($e->getMessage());
		}	
	}

	public function filtered_length($aColumns, $bColumns, $params, $module_id)
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
	
	
	public function total_length()
	{
		try
		{
			$table = array(
				'main' => array(
					'table' => $this->tbl_employee_personal_info,
					'alias' => 'A'
				),
				't1' => array(
					'table' => $this->tbl_employee_work_experiences,
					'alias' => 'B',
					'type' => 'JOIN',
					'condition' => 'A.employee_id = B.employee_id'
				)
			);

			$fields = array("COUNT(*) cnt");
			
			$where = array('B.active_flag' => YES);

			return $this->select_one($fields, $table, $where);
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

	public function get_compensation_data($fields, $table, $where = array(), $multiple = TRUE, $order_by = array(), $group_by = array(), $limit = NULL)
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

	public function insert_compensation($table, $fields, $return_id = FALSE)
	{
		try
		{
			return $this->insert_data($table, $fields, $return_id);

		}	
		catch (PDOException $e)
		{
			throw new Exception($e->getMessage() . ' --Line 638.');
		}
		catch (Exception $e)
		{			
			throw new Exception($e->getMessage());
		}
	}

	public function update_compensation($table, $fields, $where)
	{
		try
		{
			$this->update_data($table, $fields, $where);
			return TRUE;

		}	
		catch (PDOException $e)
		{
			throw new Exception($e->getMessage() . ' --Line 655.');
		}
		catch (Exception $e)
		{			
			throw new Exception($e->getMessage());
		}
	}

	public function delete_compensation($table, $where)
	{
		try
		{
			return $this->delete_data($table, $where);

		}
		catch(PDOException $e)
		{
			throw $e;
		}
	}

	public function get_salary_grade()
	{
		try
		{
			
			$query = <<<EOS
				SELECT salary_grade  FROM 
				$this->tbl_param_salary_schedule where effectivity_date =(
					SELECT MAX(effectivity_date)  FROM $this->tbl_param_salary_schedule
					 where effectivity_date < NOW()) group by salary_grade
EOS;
	
			$stmt = $this->query($query, NULL, TRUE);
		
			return $stmt;
		}	
		catch (PDOException $e)
		{
			throw new Exception($e->getMessage() . ' --Line 694.');
		}
		catch (Exception $e)
		{			
			throw new Exception($e->getMessage());
		}	
	}

	public function get_initial_task($process_id)
	{
		try
		{
			
			$val = array($process_id);
			
			$query = <<<EOS
				select A.name,B.process_id,B.process_stage_id,B.process_step_id
				from $this->db_core.$this->tbl_process_stages A 
				JOIN $this->db_core.$this->tbl_process_steps B ON A.process_id = B.process_id
				WHERE A.process_id = ?
EOS;
	
			return $this->query($query, $val,FALSE);
		}	
		catch (PDOException $e)
		{
			throw new Exception($e->getMessage() . ' --Line 722.');
		}
		catch (Exception $e)
		{			
			throw new Exception($e->getMessage());
		}	
	}

	public function get_employee_info($id)
	{
		try
		{
			$val 	= array($id, 'Y');
			$key 	= $this->get_hash_key('A.employee_id');
			/*
			$fields = "A.employee_id, 
						A.agency_employee_id, 
						CONCAT(A.last_name, IF(A.ext_name='','',CONCAT(' ', A.ext_name)), ', ', A.first_name, ' ',LEFT(A.middle_name,1), '.') as fullname, 
						ifnull(B.employ_office_name,E.name) AS name, 
						ifnull(B.employ_position_name, D.position_name) AS position_name, 
						B.employ_office_id";
			*/
			// ====================== jendaigo : start : change name format ============= //
			$fields = "A.employee_id, 
						A.agency_employee_id, 
						CONCAT(A.last_name, ', ', A.first_name, IF(A.ext_name='', '', CONCAT(' ', A.ext_name)), IF((A.middle_name='NA' OR A.middle_name='N/A' OR A.middle_name='-' OR A.middle_name='/'), '', CONCAT(' ', LEFT(A.middle_name, 1), '. '))) as fullname, 
						ifnull(B.employ_office_name,E.name) AS name, 
						ifnull(B.employ_position_name, D.position_name) AS position_name, 
						B.employ_office_id";
			// ====================== jendaigo : end : change name format ============= //
			
			$query = <<<EOS
				SELECT SQL_CALC_FOUND_ROWS $fields 
				FROM $this->tbl_employee_personal_info A
				LEFT JOIN $this->tbl_employee_work_experiences B ON A.employee_id = B.employee_id 
				LEFT JOIN $this->tbl_param_offices C ON B.employ_office_id = C.office_id 
				LEFT JOIN $this->tbl_param_positions D ON B.employ_position_id = D.position_id
				LEFT JOIN $this->db_core.$this->tbl_organizations E ON C.org_code = E.org_code
				WHERE $key = ?
				AND B.active_flag = ?
				ORDER BY B.employee_work_experience_id DESC;
EOS;
	
			return $this->query($query, $val, FALSE);
				
		}	
		catch (PDOException $e)
		{
			throw new Exception($e->getMessage() . ' --Line 754.');
		}
		catch (Exception $e)
		{			
			throw new Exception($e->getMessage());
		}
	}

	public function get_remove_personnel_list($params)
	{
		try
		{
			$val            = array($params['id']);
			$employee_id    = $this->get_hash_key('A.employee_id');
			$compensation_id  = $this->get_hash_key('compensation_id');
			$existing_where = "";
			$office_where = "";
			$position_where = "";
			$salary_grade_where = "";
			if($params['employee_list'])
			{
				$selected_employees = "(";
				$x = 0;
				foreach($params['employee_list'] as $employee)
				{
					$x++;
					$selected_employees .= "'".$employee."'";
					if($x < count($params['employee_list']))
					{
						$selected_employees .= ",";
					}
				}
				$selected_employees .= ")";
				$existing_where = " AND ".$employee_id." NOT IN ".$selected_employees;
			}
			if($params['position'])
			{
				
				$position_where = " AND B.employ_position_id = ".$params['position'];
			}
			if($params['office'])
			{
				
				$office_where = " AND B.employ_office_id = ".$params['office'];
			}
			if($params['salary_grade'])
			{
				
				$salary_grade_where = " AND B.employ_salary_grade = ".$params['salary_grade'];
			}

			$query = <<<EOS
				SELECT A.employee_id,CONCAT(A.first_name," ",A.middle_name," ",A.last_name," ",IF(A.ext_name IS NULL,'',A.ext_name)) as fullname,A.agency_employee_id
				FROM 
				$this->tbl_employee_personal_info A
				JOIN $this->tbl_employee_work_experiences B  ON A.employee_id = B.employee_id AND B.active_flag = 'Y'
				
				WHERE A.employee_id IN 
				(select employee_id from $this->tbl_employee_compensations where $compensation_id = ? )
				$existing_where
				$position_where
				$office_where
				$salary_grade_where
EOS;
	
			$stmt = $this->query($query, $val, TRUE);
		
			return $stmt;
		}	
		catch (PDOException $e)
		{
			throw new Exception($e->getMessage() . ' --Line 826.');
		}
		catch (Exception $e)
		{			
			throw new Exception($e->getMessage());
		}	
	}

	private function _get_rating_param($rating) 
	{
		try
		{
			$add_where = '';

			$query = <<<EOS
					SELECT rating_min_value, rating_max_value 
					FROM param_performance_rating 
					WHERE rating_id IN ($rating);
EOS;
			$ratings = $this->query($query);
			$add_where .= ' AND (';
			if(!EMPTY($ratings)) {
				foreach ($ratings as $key => $r) {
					$add_where .= ($key > 0 ? ' OR' : '') . ' C.rating BETWEEN ' . $r['rating_min_value'] . ' AND ' . $r['rating_max_value'];
				}
			}
			$add_where .= ')';

			return $add_where;		
		}
		catch(PDOException $e)
		{

		}
		catch(Exception $e)
		{

		}
	}

	public function payslip_history_total_length()
	{
		try
		{
			$table = array(
				'main' => array(
					'table' => $this->tbl_payout_details,
					'alias' => 'A'
				),
				't1' => array(
					'table' => $this->tbl_payout_header,
					'alias' => 'B',
					'type' => 'LEFT JOIN',
					'condition' => 'B.payroll_hdr_id = A.payroll_hdr_id'
				)
			);

			$fields 	   = array("COUNT(*) cnt");
			$employee_id   = $params['employee_id'];
			$key           = $this->get_hash_key('B.employee_id');
			$where 		   = array($key => $employee_id);

			return $this->select_one($fields, $table, $where);
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

	public function payslip_history_filtered_length($aColumns, $bColumns, $params)
	{
		try
		{
			$this->employee_payslip_history_list($aColumns, $bColumns, $params);
			
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
	
	
	public function compensation_history_total_length()
	{
		try
		{
			$table = array(
					'main' => array(
							'table' => $this->tbl_payout_details,
							'alias' => 'A'
					),
					't1' => array(
							'table' => $this->tbl_payout_header,
							'alias' => 'B',
							'type' => 'JOIN',
							'condition' => 'B.payroll_hdr_id = A.payroll_hdr_id'
					),
					't2' => array(
							'table' => $this->tbl_param_compensations,
							'alias'	=> 'C',
							'type'	=> 'JOIN',
							'condition'	=> 'A.compensation_id = C.compensation_id'
					)
			);
	
			$fields 	   = array("COUNT(*) cnt");
			$employee_id   = $params['employee_id'];
			$key           = $this->get_hash_key('B.employee_id');
			$where 		   = array($key => $employee_id);
	
			return $this->select_one($fields, $table, $where);
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
	
	public function compensation_history_filtered_length($aColumns, $bColumns, $params)
	{
		try
		{
			$this->get_compensation_history_list($aColumns, $bColumns, $params);
				
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

	// ====================== jendaigo : start : include condition for employee compensation ============= //
	public function get_employee_compensations($params, $aRow)
	{
		try
		{
			$field 	= array("B.compensation_name");
			$tables	= array(
						'main'	=> array(
							'table'		=> $this->tbl_employee_compensations,
							'alias'		=> 'A'
						),
						't1'=> array(
							'table'		=> $this->tbl_param_compensations,
							'alias'		=> 'B',
							'type'		=> 'JOIN',
							'condition'	=> 'A.compensation_id = B.compensation_id'
						),
						't2'=> array(
							'table'		=> $this->tbl_attendance_period_hdr,
							'alias'		=> 'C',
							'type'		=> 'JOIN',
							'condition'	=> 'A.start_date <= C.date_from'
						),
						't3'=> array(
							'table'		=> $this->tbl_payout_summary,
							'alias'		=> 'D',
							'type'		=> 'JOIN',
							'condition'	=> 'C.attendance_period_hdr_id = D.attendance_period_hdr_id'
						)
					);
			$where							= array();
			$key            				= $this->get_hash_key('A.employee_id');
			$where[$key]					= $params['employee_id'];
			$report_short_codes				= array('BS', 'PREM');
			$where['B.report_short_code']	= array($report_short_codes, array('IN'));
			$where['D.payroll_summary_id']	= $aRow['payroll_summary_id'];
			$employee_compensations			= $this->compensation->get_compensation_data($field, $tables, $where, TRUE);

			foreach ($employee_compensations as $employee_compensation):
				$compensation_name = empty($compensation_name) ? $employee_compensation['compensation_name'] : $compensation_name.' + '.$employee_compensation['compensation_name'];
			endforeach;
	
			return strtoupper($compensation_name);
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
	// ====================== jendaigo : end : include condition for employee compensation ============= //
	
	// ====================== jendaigo : start : employee bank account encoding ============= //
	public function get_bank_acc_list($aColumns, $bColumns, $params)
	{
		try
		{
			$id			   = $params['employee_id'];
			$id_type	   = BANKACCT_TYPE_ID ;
			$val           = array($id, $id_type, "N");
			$key           = $this->get_hash_key('A.employee_id');

			if(!EMPTY($params['A-identification_value']))
			$params['A-identification_value'] = str_replace('-', '', $params['A-identification_value']);

			/* For Advanced Filters */
			$cColumns      = array("C-start_date", "C-end_date", "A-identification_value", "C-remarks");
			$fields        = str_replace(" , ", " ", implode(", ", $aColumns));
			
			$sWhere        = $this->filtering($cColumns, $params, TRUE);
			$sOrder        = $this->ordering($bColumns, $params);
			$sLimit        = $this->paging($params);
			
			$filter_str    = $sWhere["search_str"];
			$filter_params = $sWhere["search_params"];
			
			$query = <<<EOS
				SELECT SQL_CALC_FOUND_ROWS $fields 
				FROM $this->tbl_employee_identifications A
				LEFT JOIN $this->tbl_param_identification_types B ON A.identification_type_id = B.identification_type_id
				LEFT JOIN $this->tbl_employee_identification_details C ON A.employee_identification_id = C.employee_identification_id
				WHERE $key = ? AND B.identification_type_id = ? AND B.builtin_flag = ?

				$filter_str
	        	$sOrder
	        	$sLimit
EOS;

			$stmt = $this->query($query, $val, TRUE);
		
			return $stmt;
		}	
		catch (PDOException $e)
		{
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}
		catch (Exception $e)
		{			
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}	
	}

	public function bank_acc_filtered_length($aColumns, $bColumns, $params)
	{
		try
		{
			$this->get_bank_acc_list($aColumns, $bColumns, $params);
			
			$query = <<<EOS
				SELECT FOUND_ROWS() cnt
EOS;
	
			$stmt = $this->query($query, NULL, FALSE);
		
			return $stmt;
		}
		catch (PDOException $e)
		{
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}
		catch (Exception $e)
		{			
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}	
	}
	
	public function bank_acc_total_length()
	{
		try
		{
			$id	= $this->session->userdata("pds_employee_id");
			$where = array();
			
			$fields = array("COUNT(*) cnt");
			$key 	= $this->get_hash_key('employee_id');
			$where[$key ] = $id;
			
			return $this->select_one($fields, $this->tbl_employee_identifications, $where);
		}
		catch (PDOException $e)
		{
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}
		catch (Exception $e)
		{			
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}		
	}
	// ====================== jendaigo : end : query for employee bank account encoding ============= //
	
	// ====================== jendaigo : start : employee responsibility code encoding ============= //
	public function get_responsibility_code_list($aColumns, $bColumns, $params)
	{
		try
		{
			$id			   = $params['employee_id'];
			$val           = array($id);
			$key           = $this->get_hash_key('A.employee_id');

			/* For Advanced Filters */
			$cColumns      = array("A-start_date", "A-end_date", "A-responsibility_center_code", "B-responsibility_center_desc", "A-remarks");
			$fields        = str_replace(" , ", " ", implode(", ", $aColumns));
			
			$sWhere        = $this->filtering($cColumns, $params, TRUE);
			$sOrder        = $this->ordering($bColumns, $params);
			$sLimit        = $this->paging($params);
			
			$filter_str    = $sWhere["search_str"];
			$filter_params = $sWhere["search_params"];
			if(!empty($filter_params))
				$val[] = $filter_params[0];
			
			$query = <<<EOS
				SELECT SQL_CALC_FOUND_ROWS $fields 
				  FROM $this->tbl_employee_responsibility_codes A
				  JOIN $this->tbl_param_responsibility_centers B
				    ON A.responsibility_center_code = B.responsibility_center_code
				 WHERE $key = ?
				$filter_str
	        	$sOrder
	        	$sLimit
EOS;

			$stmt = $this->query($query, $val, TRUE);
			return $stmt;
		}	
		catch (PDOException $e)
		{
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}
		catch (Exception $e)
		{			
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}	
	}

	public function responsibility_code_filtered_length($aColumns, $bColumns, $params)
	{
		try
		{
			$this->get_responsibility_code_list($aColumns, $bColumns, $params);
			
			$query = <<<EOS
				SELECT FOUND_ROWS() cnt
EOS;
	
			$stmt = $this->query($query, NULL, FALSE);
		
			return $stmt;
		}
		catch (PDOException $e)
		{
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}
		catch (Exception $e)
		{			
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}	
	}
	
	public function responsibility_code_total_length()
	{
		try
		{
			$id	= $this->session->userdata("pds_employee_id");
			$where = array();
			
			$fields = array("COUNT(*) cnt");
			$key 	= $this->get_hash_key('employee_id');
			$where[$key ] = $id;
			
			return $this->select_one($fields, $this->tbl_employee_responsibility_codes, $where);
		}
		catch (PDOException $e)
		{
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}
		catch (Exception $e)
		{			
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}		
	}
	// ====================== jendaigo : end : query for employee responsibility code encoding ============= //	
	
}