<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Performance_evaluation_model extends Main_Model {

	public $db_core            = DB_CORE;
	public $tbl_organizations  = "organizations";

	public function __construct() {
		parent:: __construct();
	
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

	public function filtered_length($aColumns, $bColumns, $params, $module_id=NULL)
	{
		try
		{
			$this->get_employee_list($aColumns, $bColumns, $params, $module_id);
			
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

	public function get_employee_list($aColumns, $bColumns, $params, $module_id=NULL)
	{
		try
		{
			/*$val   = array();
			$val[] = 'Y';*/
			/* For Advanced Filters */
			if(!EMPTY($params['fullname']))
			$params["CONCAT(A-last_name, IF(A-ext_name='','',CONCAT(' ', A-ext_name)), ', ', A-first_name, ' ',A-last_name)"] = $params['fullname'];
			$cColumns      = array("A-agency_employee_id", "CONCAT(A-last_name, IF(A-ext_name='','',CONCAT(' ', A-ext_name)), ', ', A-first_name, ' ',A-last_name)", "E-name", "D-employment_status_name");

			$fields        = str_replace(" , ", " ", implode(", ", $aColumns));
			
			$sWhere        = $this->filtering($cColumns, $params, FALSE);
			$sOrder        = $this->ordering($bColumns, $params);
			$group_by      = "GROUP BY A.employee_id, A.agency_employee_id,fullname, E.name, D.employment_status_name";
			$sLimit        = $this->paging($params);
			
			$filter_str    = $sWhere["search_str"];
			$filter_params = $sWhere["search_params"];
			
			$add_where     = '';
			if(!EMPTY($params['C-office_id'])) {
				$office_list = $this->get_office_child('', $params['C-office_id']);
				$add_where   = (EMPTY($filter_str) ? 'WHERE C.office_id IN (' . implode(',', $office_list) . ')' : ' AND C.office_id IN (' . implode(',', $office_list) . ')');
			}
			$work_experienxe_office_id = 'B.employ_office_id';
			if(!EMPTY($module_id))
			{
				$where              = array();
				$where['module_id'] = $module_id;
				$employee_office    = $this->get_general_data(array('use_admin_office'), DB_CORE.'.'.$this->tbl_modules, $where, FALSE);
				if($employee_office['use_admin_office'] > 0)
				{
					$work_experienxe_office_id = 'B.admin_office_id';
				}
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
					$add_where .= 'WHERE C.office_id IN('.$user_scopes['performance_evaluation'].')';
				}
			}
			else
			{
				$add_where .= 'AND C.office_id IN('.$user_scopes['performance_evaluation'].')';
			}
			//end
			
			$query = <<<EOS
				SELECT SQL_CALC_FOUND_ROWS $fields 
				FROM $this->tbl_employee_personal_info A
				JOIN $this->tbl_employee_work_experiences B ON A.employee_id = B.employee_id AND B.active_flag ='Y'
				LEFT JOIN $this->tbl_param_offices C ON $work_experienxe_office_id = C.office_id 
				LEFT JOIN $this->tbl_param_employment_status D ON B.employment_status_id = D.employment_status_id
				LEFT JOIN $this->db_core.$this->tbl_organizations E ON C.org_code = E.org_code

				$filter_str
				$add_where
				$group_by
	        	$sOrder
	        	$sLimit
EOS;

			$stmt = $this->query($query, $filter_params);
			
			//$stmt = $this->query($query, $val, TRUE);
						
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

	public function get_employee_performance_evaluation_list($aColumns, $bColumns, $params, $id)
	{
		try
		{
			$val           = array($id);
			$key           = $this->get_hash_key('employee_id');
			if(!EMPTY($params['evaluation_start_date']))
			{
				$params["DATE_FORMAT(A-evaluation_start_date, '%Y/%m/%d')"] = $params['evaluation_start_date'];
			}
			if(!EMPTY($params['evaluation_end_date']))
			{
				$params["DATE_FORMAT(A-evaluation_end_date, '%Y/%m/%d')"] = $params['evaluation_end_date'];
			}
			
			$cColumns      = array("DATE_FORMAT(A-evaluation_start_date, '%Y/%m/%d')", "DATE_FORMAT(A-evaluation_end_date, '%Y/%m/%d')", "A-rating", "A-rating_description", "A-remarks", "B-classification_field_name");
			
			$fields        = str_replace(" , ", " ", implode(", ", $aColumns));
			
			$sWhere        = $this->filtering($cColumns, $params, TRUE);
			$sOrder        = $this->ordering($bColumns, $params);
			$sLimit        = $this->paging($params);
			
			$filter_str    = $sWhere["search_str"];
			$filter_params = $sWhere["search_params"];
			
			$query = <<<EOS
				SELECT SQL_CALC_FOUND_ROWS $fields 
				FROM $this->tbl_employee_performance_evaluations A
				LEFT JOIN $this->tbl_param_perf_eval_classification_fields B ON A.classification_field_id = B.classification_field_id

				WHERE $key = ?
				
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

	public function evaluation_filtered_length($aColumns, $bColumns, $params, $id)
	{
		try
		{
			$this->get_employee_performance_evaluation_list($aColumns, $bColumns, $params, $id);
			
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

	public function evaluation_total_length($id)
	{
		try
		{
			$where       = array();
			$key         = $this->get_hash_key('employee_id');
			$where[$key] = $id;
			$fields      = array("COUNT(*) cnt");
			return $this->select_one($fields, $this->tbl_employee_performance_evaluations, $where);
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
	public function get_employee_info($id)
	{
		try
		{
			$val 	= array($id, 'Y');
			$key 	= $this->get_hash_key('A.employee_id');
			
			// $fields = "A.employee_id, A.agency_employee_id, CONCAT(A.last_name, if(A.ext_name='','',concat(' ',A.ext_name)), ', ', A.first_name, ' ',LEFT(A.middle_name,1), '.') as fullname, E.name, D.position_name";
			// ====================== jendaigo : start : change name format ============= //
			$fields = "A.employee_id, A.agency_employee_id, CONCAT(A.last_name, ', ', A.first_name, IF(A.ext_name='', '', CONCAT(' ', A.ext_name)), IF((A.middle_name='NA' OR A.middle_name='N/A' OR A.middle_name='-' OR A.middle_name='/'), '', CONCAT(' ', LEFT(A.middle_name,1), '.'))) as fullname, E.name, D.position_name";
			// ====================== jendaigo : end : change name format ============= //
			
			$query = <<<EOS
				SELECT SQL_CALC_FOUND_ROWS $fields 
				FROM $this->tbl_employee_personal_info A
				LEFT JOIN $this->tbl_employee_work_experiences B ON A.employee_id = B.employee_id 
				LEFT JOIN $this->tbl_param_offices C ON B.employ_office_id = C.office_id 
				LEFT JOIN $this->tbl_param_positions D ON B.employ_position_id = D.position_id
				LEFT JOIN $this->db_core.$this->tbl_organizations E ON C.org_code = E.org_code
				WHERE $key = ?
				AND (B.active_flag = ? OR B.employ_end_date IS NULL OR 1 = 1)
				ORDER BY B.employee_work_experience_id DESC;
EOS;
	
			$stmt = $this->query($query, $val, False);
						
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

	public function get_latest_rating($field, $table, $id, $start_date, $end_date)
	{
		try
		{
			$val = array($id, $start_date, $end_date);
			$key = $this->get_hash_key('employee_perf_eval_id');
			$query = <<<EOS
				SELECT SQL_CALC_FOUND_ROWS $field 
				FROM $table
				WHERE $key = ? AND (evaluation_start_date <= ? AND evaluation_start_date < ?)
EOS;
	
			RLog::debug($query);
			$val = array_merge($val);
			$stmt = $this->query($query, $val, False);
						
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