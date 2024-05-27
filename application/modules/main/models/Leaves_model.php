<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Leaves_model extends Main_Model {
                
	public $db_core           = DB_CORE;
	public $tbl_organizations = "organizations";
	
	public function get_employee_list($aColumns, $bColumns, $params)
	{
		try
		{
			$val = array();
			
			/* For Advanced Filters */
			if(!EMPTY($params['fullname']))
				$params["CONCAT(A-first_name,' ',A-last_name)"] = $params['fullname'];


			$cColumns = array("A-agency_employee_id", "CONCAT(A-first_name,' ',A-last_name)", "E-name", "D-employment_status_name");
						
			$fields = str_replace(" , ", " ", implode(", ", $aColumns));
			
			$sWhere = $this->filtering($cColumns, $params, FALSE);
			$sOrder = $this->ordering($bColumns, $params);
			$sLimit = $this->paging($params);
			
			$filter_str = $sWhere["search_str"];
			$filter_params = $sWhere["search_params"];
			
			$query = <<<EOS
				SELECT SQL_CALC_FOUND_ROWS $fields 
				FROM $this->tbl_employee_personal_info A
				LEFT JOIN $this->tbl_employee_work_experiences B ON A.employee_id = B.employee_id AND B.active_flag ='Y'
				LEFT JOIN $this->tbl_param_offices C ON C.office_id = B.employ_office_id
				LEFT JOIN $this->tbl_param_employment_status D ON B.employment_status_id = D.employment_status_id
				LEFT JOIN $this->db_core.$this->tbl_organizations E ON C.org_code = E.org_code

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
			$this->rlog_error($e);
		}
		catch (Exception $e)
		{			
			$this->rlog_error($e);
		}
	}
	public function employee_filtered_length($aColumns, $bColumns, $params)
	{
		try
		{
			$this->get_employee_list($aColumns, $bColumns, $params);
			
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
	
	
	public function employee_total_length()
	{
		try
		{
			$where = array();
			
			$fields = array("COUNT(*) cnt");
			return $this->select_one($fields, $this->tbl_employee_personal_info, $where);
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
	
	public function get_leave_type_list($aColumns, $bColumns, $params)
	{
		try
		{
			$val = array();
			/* For Advanced Filters */
			
			$cColumns = array("leave_type_name","active_flag");
						
			$fields = str_replace(" , ", " ", implode(", ", $aColumns));
			
			$sWhere = $this->filtering($cColumns, $params, FALSE);
			$sOrder = $this->ordering($bColumns, $params);
			$sLimit = $this->paging($params);
			
			$filter_str = $sWhere["search_str"];
			$filter_params = $sWhere["search_params"];
			
			$query = <<<EOS
				SELECT SQL_CALC_FOUND_ROWS $fields 
				FROM $this->tbl_param_leave_types
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
			$this->rlog_error($e);
		}
		catch (Exception $e)
		{			
			$this->rlog_error($e);
		}
	}
	public function leave_type_filtered_length($aColumns, $bColumns, $params)
	{
		try
		{
			$this->get_leave_type_list($aColumns, $bColumns, $params);
			
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
	
	
	public function leave_type_total_length()
	{
		try
		{
			$where = array();
			
			$fields = array("COUNT(*) cnt");
			return $this->select_one($fields, $this->tbl_param_leave_types, $where);
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

	public function employee_get_leave_type_list($aColumns, $bColumns, $params)
	{
		try
		{
			$request_sub_type = TYPE_REQUEST_LA_COMMUTATION_NOT_REQUESTED;
			$val              = array(REQUEST_NEW,REQUEST_PENDING,REQUEST_ONGOING,$params['employee_id']);
			$key              = $this->get_hash_key('B.employee_id');
			/* For Advanced Filters */
				if(!EMPTY($params['pending_leave']))
			$params["SUM(IF(E-request_sub_status_id = 1 AND F-no_of_days IS NOT NULL, F-no_of_days,0))"] = $params['pending_leave'];
			$cColumns = array("A-leave_type_name", "B-leave_balance","SUM(IF(E-request_sub_status_id = 1 AND F-no_of_days IS NOT NULL, F-no_of_days,0))");
						
			$fields = str_replace(" , ", " ", implode(", ", $aColumns));
			
			$sWhere = $this->filtering($cColumns, $params, TRUE);
			$sOrder = $this->ordering($bColumns, $params);
			$sLimit = $this->paging($params);
			
			$filter_str = $sWhere["search_str"];
			$filter_params = $sWhere["search_params"];
			$query = <<<EOS
				SELECT SQL_CALC_FOUND_ROWS $fields 
				FROM $this->tbl_param_leave_types A
				JOIN $this->tbl_employee_leave_balances B ON A.leave_type_id = B.leave_type_id
				LEFT JOIN $this->tbl_requests D ON B.employee_id = D.employee_id AND D.request_status_id IN ( ?, ?, ?)
				LEFT JOIN $this->tbl_requests_sub E ON D.request_id = E.request_id AND E.request_sub_type_id = $request_sub_type
				LEFT JOIN $this->tbl_requests_leaves F ON F.request_sub_id = E.request_sub_id AND A.leave_type_id = F.leave_type_id
				WHERE $key = ?
				$filter_str
				group by A.leave_type_id, B.leave_balance
	        	$sOrder
	        	$sLimit
EOS;

			$val = array_merge($val,$filter_params);
			
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

	public function employee_leave_type_filtered_length($aColumns, $bColumns, $params)
	{
		try
		{
			$this->employee_get_leave_type_list($aColumns, $bColumns, $params);
			
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
	
	public function employee_leave_type_total_length()
	{
		try
		{
			$where = array();
			
			$fields = array("COUNT(*) cnt");
			return $this->select_one($fields, $this->tbl_param_leave_types, $where);
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
	public function get_leave_history_list($aColumns, $bColumns, $params)
	{
		try
		{
			$val           = array($params['employee_id'],$params['leave_type_id']);
			$employee_id   = $this->get_hash_key('A.employee_id');
			$leave_type_id = $this->get_hash_key('A.leave_type_id');
			/* For Advanced Filters */
			if(!EMPTY($params['transaction_date']))
				$params["DATE_FORMAT(A-leave_transaction_date,'%M %d, %Y')"] = $params['transaction_date'];
			if(!EMPTY($params['effective_date']))
				$params["DATE_FORMAT(A-effective_date,'%M %d, %Y')"] = $params['effective_date'];
			$cColumns      = array("DATE_FORMAT(A-leave_transaction_date,'%M %d, %Y')","DATE_FORMAT(A-effective_date,'%M %d, %Y')", "B-leave_transaction_type_name","A-leave_earned_used");
			
			$fields        = str_replace(" , ", " ", implode(", ", $aColumns));
			
			$sWhere        = $this->filtering($cColumns, $params, TRUE);
			$sOrder        = $this->ordering($bColumns, $params);
			$sLimit        = $this->paging($params);
			
			$filter_str    = $sWhere["search_str"];
			$filter_params = $sWhere["search_params"];
			
			$query = <<<EOS
				SELECT SQL_CALC_FOUND_ROWS $fields 
				FROM $this->tbl_employee_leave_details A
				JOIN $this->tbl_param_leave_transaction_types B ON A.leave_transaction_type_id = B.leave_transaction_type_id
				WHERE $employee_id = ?
				AND $leave_type_id = ?
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
			$this->rlog_error($e);
		}
		catch (Exception $e)
		{			
			$this->rlog_error($e);
		}
	}
	public function leave_history_filtered_length($aColumns, $bColumns, $params)
	{
		try
		{
			$this->get_leave_history_list($aColumns, $bColumns, $params);
			
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
	
	
	public function leave_history_total_length($params)
	{
		try
		{
			$where                  = array();
			
			$fields                 = array("COUNT(*) cnt");
			$where['employee_id']   = $params['employee_id'];
			$where['leave_type_id'] = $params['leave_type_id'];
			return $this->select_one($fields, $this->tbl_employee_leave_details, $where);
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

	public function leave_type_employee_list($aColumns, $bColumns, $params)
	{
		try
		{
			$request_sub_type = TYPE_REQUEST_LA_COMMUTATION_NOT_REQUESTED;
			$val              = array($params['leave_type_id']);
			$key              = $this->get_hash_key('B.leave_type_id ');
			/* For Advanced Filters */
			if(!EMPTY($params['fullname']))			
			$params["CONCAT(A-first_name,' ',A-last_name)"] = $params['fullname'];
			if(!EMPTY($params['pending_leave']))			
			$params["SUM(IF(E-request_sub_status_id = 1 AND F-no_of_days IS NOT NULL, F-no_of_days,0))"] = $params['pending_leave'];
			$cColumns      = array("A-agency_employee_id","CONCAT(A-first_name,' ',A-last_name)","J-name", "B-leave_balance","SUM(IF(E-request_sub_status_id = 1 AND F-no_of_days IS NOT NULL, F-no_of_days,0))");
			
			$fields        = str_replace(" , ", " ", implode(", ", $aColumns));
			$sWhere        = $this->filtering($cColumns, $params, FALSE);
			
			$sOrder        = $this->ordering($bColumns, $params);
			$sLimit        = $this->paging($params);
			
			$filter_str    = $sWhere["search_str"];
			$filter_params = $sWhere["search_params"];

			//remove add_where in sql query
			// $add_where     = '';
			// if(!EMPTY($params['H-office_id'])) {
				// $office_list = '';
				// $office_list = $this->get_office_child($office_list, $params['H-office_id']);
				// $add_where   = (EMPTY($filter_str) ? 'WHERE H.office_id IN (' . implode(',', $office_list) . ')' : ' AND H.office_id IN (' . implode(',', $office_list) . ')');
			// }
			
			//====================================================marvin====================================================
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
			
			//add filter
			if(empty($filter_str))
			{
				if(isset($params['H-office_id']))
				{
					if(empty($params['H-office_id']))
					{
						$filter_str .= 'WHERE H.office_id IN('.$user_scopes['leaves'].')';						
					}
					else
					{
						$filter_str .= 'WHERE LOWER(H.office_id) = '.$params['H-office_id'].' AND H.office_id IN('.$user_scopes['leaves'].')';
					}
				}
				else
				{
					if(empty($params['K-user_id']))
					{
						$filter_str .= 'WHERE K.user_id IN('.$user_scopes['leaves'].')';			
					}
					else
					{
						$filter_str .= 'WHERE LOWER(K.user_id) = '.$params['K-user_id'].' AND K.user_id IN('.$user_scopes['leaves'].')';
					}
				}
			}
			else
			{
				if(isset($params['H-office_id']))
				{
					if(empty($params['H-office_id']))
					{
						$filter_str .= 'AND H.office_id IN('.$user_scopes['leaves'].')';						
					}
					else
					{
						$filter_str .= 'AND LOWER(H.office_id) = '.$params['H-office_id'].' AND H.office_id IN('.$user_scopes['leaves'].')';
					}
				}
				else
				{
					if(empty($params['K-user_id']))
					{
						$filter_str .= 'AND K.user_id IN('.$user_scopes['leaves'].')';						
					}
					else
					{
						$filter_str .= 'AND LOWER(K.user_id) = '.$params['K-user_id'].' AND K.user_id IN('.$user_scopes['leaves'].')';
					}
				}
			}
			//====================================================marvin====================================================
			
			$query = <<<EOS
				SELECT SQL_CALC_FOUND_ROWS $fields 
				FROM $this->tbl_employee_personal_info A
				JOIN $this->tbl_employee_leave_balances B ON $key = ? AND A.employee_id = B.employee_id
				LEFT JOIN $this->tbl_requests D ON B.employee_id = D.employee_id
				LEFT JOIN $this->tbl_requests_sub E ON D.request_id = E.request_id AND E.request_sub_type_id = $request_sub_type
				LEFT JOIN $this->tbl_requests_leaves F ON F.request_sub_id = E.request_sub_id AND B.leave_type_id = F.leave_type_id
				LEFT JOIN $this->tbl_employee_work_experiences G ON A.employee_id = G.employee_id AND G.active_flag ='Y'
				LEFT JOIN $this->tbl_param_offices H ON G.employ_office_id = H.office_id 
				LEFT JOIN $this->db_core.$this->tbl_organizations J ON H.org_code = J.org_code
				
				/*add tbl_users*/
				LEFT JOIN $this->tbl_associated_accounts K ON A.employee_id = K.employee_id

				$filter_str
				/*remove add_where*/
				/*$add_where*/
				group by A.employee_id,J.name,B.leave_balance
	        	$sOrder
	        	$sLimit
EOS;

			$val = array_merge($val,$filter_params);

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
	public function leave_type_employee_list_filtered_length($aColumns, $bColumns, $params)
	{
		try
		{
			$this->leave_type_employee_list($aColumns, $bColumns, $params);
			
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
	
	
	public function leave_type_employee_list_total_length()
	{
		try
		{
			$where = array();
			
			$fields = array("COUNT(*) cnt");
			return $this->select_one($fields, $this->tbl_param_leave_types, $where);
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
	public function get_general_data($fields, $table, $where = array(), $multiple = TRUE, $order_by = array(), $group = array(), $limit = NULL)
	{
		try{
	
			if($multiple)
			{
				return $this->select_all($fields, $table, $where, $order_by, $group, $limit);
			
			}
			else
			{
				return $this->select_one($fields, $table, $where, $order_by, $group, $limit);
			}
		}
		catch (PDOException $e){
			throw $e;
		}
	}
	public function insert_general_data($table, $params, $return_id = FALSE)
	{
		try
		{
			return $this->insert_data($table, $params, $return_id);
	
		}
		catch(PDOException $e)
		{
			throw $e;
		}
	}
	public function update_general_data($tables, $params, $where)
	{
		try
		{
			return $this->update_data($tables, $params, $where);
	
		}
		catch(PDOException $e)
		{
			throw $e;
		}
	}
	public function delete_general_data($table,$where)
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
			$this->rlog_error($e);
		}
		catch (Exception $e)
		{			
			$this->rlog_error($e);
		}	
	}
	public function get_employee_leave_types($id)
	{
		try
		{
			$val = array($id);
			$key 	= $this->get_hash_key('employee_id');
			$query = <<<EOS
				SELECT *  FROM 
				$this->tbl_param_leave_types 
				WHERE leave_type_id  NOT IN (select leave_type_id from $this->tbl_employee_leave_balances where $key = ?)
				AND active_flag = 'Y'
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
	
	public function get_add_personnel_list($params)
	{
		try
		{
			$val                = array($params['id']);
			/*START: USER/OFFICE SCOPE*/
			$where_office_scope = '';
			$work_experience_office_id = 'B.employ_office_id';
			$where                     = array();
			$where['module_id']        = MODULE_TA_LEAVES;
			$employee_office           = $this->get_general_data(array('use_admin_office'), DB_CORE.'.'.$this->tbl_modules, $where, FALSE);
			if($employee_office['use_admin_office'] > 0)
			{
				$work_experience_office_id = 'B.admin_office_id';
				$where_office_scope = 'AND B.admin_office_id IN (SELECT office_id FROM '.$this->db_core.'.'.$this->tbl_user_offices.' WHERE user_id = ? AND module_id =  ?)';
				$val[] = $this->session->userdata('user_id');
				$val[] = MODULE_TA_LEAVES;
			}

			/*END: USER/OFFICE SCOPE*/
			$employee_id        = $this->get_hash_key('A.employee_id');
			$leave_type_id      = $this->get_hash_key('leave_type_id');
			$existing_where     = "";
			$office_where       = "";
			$position_where     = "";
			$salary_grade_where = "";
			if($params['employee_list'])
			{

				$selected_employees = "(";
				$x = 0;
				foreach($params['employee_list'] as $employee)
				{
					$x++;
					$selected_employees .= " ? ";
					$val[] = $employee;

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
				
				$position_where = " AND B.employ_position_id = ? ";
				$val[] = $params['position'];
			}
			if($params['office'])
			{
				
				$office_where = " AND ".$work_experience_office_id." = ? ";
				$val[] = $params['office'];
			}
			if($params['salary_grade'])
			{
				
				$salary_grade_where = " AND B.employ_salary_grade = ?";
				$val[] = $params['salary_grade'];
			}

// 			$query = <<<EOS
// 				SELECT DISTINCT A.employee_id,CONCAT(A.first_name," ",A.middle_name," ",A.last_name," ",IF(A.ext_name IS NULL,'',A.ext_name)) as fullname,A.agency_employee_id
// 				FROM 
// 				$this->tbl_employee_personal_info A
// 				JOIN $this->tbl_employee_work_experiences B  ON A.employee_id = B.employee_id AND B.active_flag = 'Y'
				
// 				WHERE A.employee_id NOT IN 
// 				(select employee_id from $this->tbl_employee_leave_balances where $leave_type_id = ? )
				
// 				$where_office_scope
// 				$existing_where
// 				$position_where
// 				$office_where
// 				$salary_grade_where
// EOS;

// ================davcorrea: START : remove JO from leave employee list : 11/08/2023=================
$query = <<<EOS
SELECT DISTINCT A.employee_id,CONCAT(A.first_name," ",A.middle_name," ",A.last_name," ",IF(A.ext_name IS NULL,'',A.ext_name)) as fullname,A.agency_employee_id
FROM 
$this->tbl_employee_personal_info A
JOIN $this->tbl_employee_work_experiences B  ON A.employee_id = B.employee_id AND B.active_flag = 'Y' AND B.employ_type_flag != 'JO'


WHERE A.employee_id NOT IN 
(select employee_id from $this->tbl_employee_leave_balances where $leave_type_id = ? )

$where_office_scope
$existing_where
$position_where
$office_where
$salary_grade_where
EOS;

// ====================END====================================================================
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
	public function get_specific_personnel_list($params)
	{
		try
		{
			$val            = array();
			$employee_id    = $this->get_hash_key('A.employee_id');
			$leave_type_id  = $this->get_hash_key('A.leave_type_id');
			$selected_where = "";
			if($params['personnel_name'])
			{
				$selected_employees = "(";
				$x = 0;
				foreach($params['personnel_name'] as $employee)
				{
					$x++;
					$selected_employees .= " ? ";
					$val[] = $employee;
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
			$this->rlog_error($e);
		}
		catch (Exception $e)
		{			
			$this->rlog_error($e);
		}	
	}
	public function get_remove_personnel_list($params)
	{
		try
		{
			

			$val                = array($params['id']);

			/*START: USER/OFFICE SCOPE*/
			$where_office_scope = '';
			$work_experience_office_id = 'B.employ_office_id';
			$where                     = array();
			$where['module_id']        = MODULE_TA_LEAVES;
			$employee_office           = $this->get_general_data(array('use_admin_office'), DB_CORE.'.'.$this->tbl_modules, $where, FALSE);
			if($employee_office['use_admin_office'] > 0)
			{
				$work_experience_office_id = 'B.admin_office_id';
				$where_office_scope = 'AND B.admin_office_id IN (SELECT office_id FROM '.$this->db_core.'.'.$this->tbl_user_offices.' WHERE user_id = ? AND module_id =  ?)';
				$val[] = $this->session->userdata('user_id');
				$val[] = MODULE_TA_LEAVES;
			}

			/*END: USER/OFFICE SCOPE*/


			$employee_id        = $this->get_hash_key('A.employee_id');
			$leave_type_id      = $this->get_hash_key('leave_type_id');
			$existing_where     = "";
			$office_where       = "";
			$position_where     = "";
			$salary_grade_where = "";
			if($params['employee_list'])
			{
				$selected_employees = "(";
				$x = 0;
				foreach($params['employee_list'] as $employee)
				{
					$x++;
					$selected_employees .= " ? ";
					$val[] = $employee;
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
				
				$position_where = " AND B.employ_position_id = ? ";
				$val[] = $params['position'];
			}
			if($params['office'])
			{
				
				$office_where = " AND ".$work_experience_office_id." = ? ";
				$val[] = $params['office'];
			}
			if($params['salary_grade'])
			{
				
				$salary_grade_where = " AND B.employ_salary_grade = ? ";
				$val[] = $params['salary_grade'];
			}

			$query = <<<EOS
				SELECT A.employee_id,CONCAT(A.first_name," ",A.middle_name," ",A.last_name," ",IF(A.ext_name IS NULL,'',A.ext_name)) as fullname,A.agency_employee_id
				FROM 
				$this->tbl_employee_personal_info A
				JOIN $this->tbl_employee_work_experiences B  ON A.employee_id = B.employee_id AND B.active_flag = 'Y'
				
				WHERE A.employee_id IN 
				(select employee_id from $this->tbl_employee_leave_balances where $leave_type_id = ? )

				$where_office_scope
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
			$this->rlog_error($e);
		}
		catch (Exception $e)
		{			
			$this->rlog_error($e);
		}	
	}

	public function get_leave_monthly_credit_dropdown($leave_type_id)
	{
		try
		{
			$val                = array($leave_type_id,$leave_type_id,$leave_type_id);
			$leave_type_key      = $this->get_hash_key('leave_type_id');
			

			$query = <<<EOS
				
				SELECT 
				IF(DATE_FORMAT(NOW(),'%m%Y') NOT IN (select leave_year_month from $this->tbl_leave_monthly_credits where $leave_type_key = ? ),CONCAT(DATE_FORMAT(NOW(),'%m%Y'),'-',DATE_FORMAT(NOW(),'%M %Y')),null) as yearmonth_one,
				IF(DATE_FORMAT(DATE_SUB(NOW(),INTERVAL 1 MONTH),'%m%Y') NOT IN (select leave_year_month from $this->tbl_leave_monthly_credits where $leave_type_key = ? ),CONCAT(DATE_FORMAT(DATE_SUB(NOW(),INTERVAL 1 MONTH),'%m%Y'),'-',DATE_FORMAT(DATE_SUB(NOW(),INTERVAL 1 MONTH),'%M %Y')),null) as yearmonth_two,
				IF(DATE_FORMAT(DATE_SUB(NOW(),INTERVAL 2 MONTH),'%m%Y') NOT IN (select leave_year_month from $this->tbl_leave_monthly_credits where $leave_type_key = ?),CONCAT(DATE_FORMAT(DATE_SUB(NOW(),INTERVAL 2 MONTH),'%m%Y'),'-',DATE_FORMAT(DATE_SUB(NOW(),INTERVAL 2 MONTH),'%M %Y')),null) as yearmonth_three

EOS;
	
			$stmt = $this->query($query, $val, FALSE);
		
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

	public function get_leave_monthly_credit_employess($leave_type_id,$month_year)
	{
		try
		{
			$val                = array($month_year,LEAVE_FILE_LEAVE,$leave_type_id);
			$leave_type_key      = $this->get_hash_key('A.leave_type_id');
			

			$query = <<<EOS
				
				SELECT 
				A.employee_id,
				A.leave_type_id,
				A.leave_balance,
				SUM(IF(DATE_FORMAT(B.leave_start_date,'%m%Y') = ? ,B.leave_wop,0)) as total_lwop
				FROM 
				$this->tbl_employee_leave_balances A
				LEFT JOIN $this->tbl_employee_leave_details B 
				ON A.employee_id = B.employee_id 
				AND B.leave_transaction_type_id = ?
				JOIN $this->tbl_employee_work_experiences C ON A.employee_id = C.employee_id AND C.active_flag = 'Y'
				WHERE $leave_type_key = ?
				GROUP BY A.employee_id
EOS;
	
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
	public function get_employee_late($employee_id,$month_year)
	{
		try
		{
			$val                = array($employee_id,$month_year);			

			$query = <<<EOS
				
				SELECT 
				    SUM(tardiness) / 8 AS total_late,
				    SUM(if(attendance_status_id = 5,8,undertime)) / 8 AS total_und
				FROM
				    $this->tbl_attendance_period_dtl
				WHERE
				    employee_id = ?
				        AND DATE_FORMAT(attendance_date, '%m%Y') = ?
EOS;
	
			$stmt = $this->query($query, $val, FALSE);
		
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
}