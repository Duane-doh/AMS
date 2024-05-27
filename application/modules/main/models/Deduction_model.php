<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Deduction_model extends Main_Model {

	public $db_core           = DB_CORE;
	public $tbl_organizations = "organizations";

	public $tbl_process_stage_roles = "process_stage_roles";
	public $tbl_actions             = "actions"; 
	public $tbl_process_stages      = "process_stages";
	public $tbl_process_steps       = "process_steps";

	public $tbl_param_regions    = 'param_regions';
	public $tbl_param_provinces  = 'param_provinces';
	public $tbl_param_municities = 'param_municities';
	public $tbl_param_barangays  = 'param_barangays';
	public $tbl_param_genders    = 'param_genders';


	public function __construct() {
		parent:: __construct();
	
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
					$add_where .= 'WHERE C.office_id IN('.$user_scopes['deductions'].')';
				}
			}
			else
			{
				$add_where .= 'AND C.office_id IN('.$user_scopes['deductions'].')';
			}
			//end

			$query = <<<EOS
				SELECT SQL_CALC_FOUND_ROWS $fields 
				FROM $this->tbl_employee_personal_info A
				JOIN $this->tbl_employee_work_experiences B ON A.employee_id = B.employee_id AND B.active_flag ='Y'
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
			$this->rlog_error($e);
		}
		catch (Exception $e)
		{			
			$this->rlog_error($e);
		}
	} 
	
	public function get_personnel_list($params)
	{
		try
		{
			$id              = array($params['deduction_id']);
			$deduction_id 	 = $this->get_hash_key('deduction_id');

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
			if($params['rating'])
			{
				$add_where .= $this->_get_rating_param($params['rating']);
			}

			$field 					 = array("employ_type_flag") ;
			$table					 = $this->tbl_param_deductions;
			$where					 = array();
			$key    				 = $this->get_hash_key('deduction_id');
			$where[$key]			 = $params['deduction_id'];
			$deduction_info          = $this->get_deduction_data($field, $table, $where, FALSE);
			
			switch ($deduction_info['employ_type_flag']) {
				case PAYROLL_TYPE_FLAG_ALL:
					// $employ_type_flag = array(DOH_GOV_APPT,DOH_GOV_NON_APPT, DOH_JO);
					$add_where .= ' AND B.employ_type_flag IN ("' . DOH_GOV_APPT .'","'. DOH_GOV_NON_APPT .'","'. DOH_JO . '")';
					break;
				
				case PAYROLL_TYPE_FLAG_REG:
					$employ_type_flag = array(DOH_GOV_APPT,DOH_GOV_NON_APPT);
					$add_where .= ' AND B.employ_type_flag IN ("' . DOH_GOV_APPT .'","'. DOH_GOV_NON_APPT . '")';
					break;

				case PAYROLL_TYPE_FLAG_JO:
					$employ_type_flag = array(DOH_JO);
					$add_where .= ' AND B.employ_type_flag IN ("' . DOH_JO . '")';
					break;
			}


			if($params['action'] != ACTION_ADD)
			{
				$condition = 'IN';
			}
			else
			{
				$condition = 'NOT IN';
			}

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
				SELECT A.employee_id,CONCAT(A.last_name,if(A.ext_name='','',CONCAT(' ',A.ext_name)),", ",A.first_name," ",LEFT(A.middle_name,1), '.') as fullname, A.agency_employee_id
				FROM $this->tbl_employee_personal_info A
				JOIN $this->tbl_employee_work_experiences B ON A.employee_id = B.employee_id AND B.active_flag = 'Y'
				LEFT JOIN $this->tbl_employee_performance_evaluations C
				ON A.employee_id = C.employee_id
				WHERE A.employee_id $condition 
				(SELECT employee_id FROM employee_deductions WHERE $deduction_id = ? $selected_employees)
				
				$add_where

				GROUP BY employee_id
				ORDER BY CONCAT(A.last_name,if(A.ext_name='','',CONCAT(' ',A.ext_name)),", ",A.first_name," ",A.middle_name, '.') ASC;
EOS;
*/
			// ====================== jendaigo : start : change name format ============= //
			$query = <<<EOS
				SELECT A.employee_id,CONCAT(A.last_name, ', ', A.first_name, IF(A.ext_name='', '', CONCAT(' ', A.ext_name)), IF((A.middle_name='NA' OR A.middle_name='N/A' OR A.middle_name='-' OR A.middle_name='/'), '', CONCAT(' ', LEFT(A.middle_name, 1), '. '))) as fullname, A.agency_employee_id
				FROM $this->tbl_employee_personal_info A
				JOIN $this->tbl_employee_work_experiences B ON A.employee_id = B.employee_id AND B.active_flag = 'Y'
				LEFT JOIN $this->tbl_employee_performance_evaluations C
				ON A.employee_id = C.employee_id
				WHERE A.employee_id $condition 
				(SELECT employee_id FROM employee_deductions WHERE $deduction_id = ? $selected_employees)
				
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

	public function get_deduction_list($aColumns, $bColumns, $params)
	{
		try
		{
			$val   = array();
			$val[] = NO;
			/* For Advanced Filters */

			$cColumns      = array("deduction_name");
			
			$fields        = str_replace(" , ", " ", implode(", ", $aColumns));
			
			$sWhere        = $this->filtering($cColumns, $params, TRUE);
			$sOrder        = $this->ordering($bColumns, $params);
			$sLimit        = $this->paging($params);
			
			$filter_str    = $sWhere["search_str"];
			$filter_params = $sWhere["search_params"];
			
			$query = <<<EOS
				SELECT SQL_CALC_FOUND_ROWS $fields 
				FROM $this->tbl_param_deductions
				WHERE statutory_flag = ?
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

	public function get_deduction_personnel_list($aColumns, $bColumns, $params, $table, $where, $multiple = TRUE)
	{

		try
		{

			$val    = array();
			$val[]  = 'Y';
			$val[]  = $params['deduction_id'];
			$fields = str_replace(" , ", " ", implode(", ", $aColumns));
			$params["CONCAT(PI.last_name,if(PI.ext_name='','',CONCAT(' ',PI.ext_name)),\", \",PI.first_name,\" \",LEFT(PI.middle_name,1), '.')"] = $params['full_name'];
				

			$cColumns        = array("PI-agency_employee_id", "CONCAT(PI.last_name,if(PI.ext_name='','',CONCAT(' ',PI.ext_name)),\", \",PI.first_name,\" \",LEFT(PI.middle_name,1), '.')", "WE-employ_office_name",  "ED-start_date");
			$sWhere          = $this->filtering($cColumns, $params, TRUE);
			$sOrder          = $this->ordering($bColumns, $params);
			$sLimit          = $this->paging($params);
			$filter_str      = $sWhere["search_str"];
			$filter_params   = $sWhere["search_params"];
			$key             = $this->get_hash_key('ED.deduction_id');

			$query = <<<EOS
				SELECT SQL_CALC_FOUND_ROWS $fields 
				FROM employee_deductions AS ED
				JOIN param_deductions AS PD ON PD.deduction_id = ED.deduction_id
				LEFT JOIN $this->tbl_employee_personal_info AS PI ON PI.employee_id = ED.employee_id
				JOIN $this->tbl_employee_work_experiences AS WE ON PI.employee_id = WE.employee_id AND WE.active_flag = ?
				WHERE $key = ?
				$filter_str
				GROUP BY ED.deduction_id, PI.agency_employee_id, fullname, ED.start_date
				$sOrder
				$sLimit 
EOS;
			RLog::debug($query);
			$stmt = $this->query($query, array_merge($val,$filter_params), $multiple);
		
			return $stmt;
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
				
	public function get_statutory_list($aColumns, $bColumns, $params)
	{

		try
		{

			$val              = array();
			$add_where 		  = '';
			$employee_id  	  = $params['employee_id'];
			$key              = $this->get_hash_key('ED.employee_id');

			$fields = str_replace(" , ", " ", implode(", ", $aColumns));

			$employ_type_flag = $this->_get_employ_type_flag($employee_id);
			if($params['ED-start_date'])
			{
				$params["DATE_FORMAT(ED-start_date, '%Y/%m/%d')"] = $params['ED-start_date'];
			}
		
			$cColumns      = array("PD-deduction_name", "DATE_FORMAT(ED-start_date, '%Y/%m/%d')", "PF-frequency_name");
			$sWhere        = $this->filtering($cColumns, $params, TRUE);
			$sOrder        = $this->ordering($bColumns, $params);
			$sLimit        = $this->paging($params);
			$filter_str    = $sWhere["search_str"];
			$filter_params = $sWhere["search_params"];
			$val[]         = YES;

			if(!EMPTY($employ_type_flag))
			$add_where     = 'AND PD.employ_type_flag IN ("' . implode('","', $employ_type_flag)  . '")';
			$val[]         = $employee_id;


			$query = <<<EOS
				SELECT $fields 
				FROM employee_deductions AS ED 
				JOIN param_deductions AS PD ON PD.deduction_id = ED.deduction_id
				JOIN param_frequencies AS PF ON PF.frequency_id = PD.frequency_id
				WHERE PD.statutory_flag = ? $add_where AND $key = ?
				$filter_str
				GROUP BY ED.employee_deduction_id, ED.deduction_id, PD.deduction_name, ED.start_date, PF.frequency_name
				$sOrder
				$sLimit
EOS;
			$stmt = $this->query($query, array_merge($val,$filter_params));
		
			return $stmt;
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

	public function get_other_deduction_list($aColumns, $bColumns, $params, $table, $where, $multiple = TRUE)
	{ 

		try
		{

			$val              = array();
			$add_where 		  = '';
			$employee_id  	  = $params['employee_id'];
			$key              = $this->get_hash_key('ED.employee_id');

			$employ_type_flag = $this->_get_employ_type_flag($employee_id);

			$fields = str_replace(" , ", " ", implode(", ", $aColumns));

			if($params['ED-start_date']) 
				$params["DATE_FORMAT(ED-start_date, '%Y/%m/%d')"] = $params['ED-start_date'];
// 				$params['ED-start_date'] = format_date($params['ED-start_date'],'Y-m-d');
			
			$cColumns      = array("PD-deduction_name", "DATE_FORMAT(ED-start_date, '%Y/%m/%d')", "PD-deduction_type_flag", "PF-frequency_name");
			$sWhere        = $this->filtering($cColumns, $params, TRUE);
			$sOrder        = $this->ordering($bColumns, $params);
			$sLimit        = $this->paging($params);
			$filter_str    = $sWhere["search_str"];
			$filter_params = $sWhere["search_params"];
			$val[]         = NO;
			if(!EMPTY($employ_type_flag))
				$add_where     = 'AND PD.employ_type_flag IN ("' . implode('","', $employ_type_flag)  . '")';
			$val[]         = $employee_id;
			
			$query = <<<EOS
				SELECT SQL_CALC_FOUND_ROWS $fields 
				FROM employee_deductions AS ED 
				JOIN param_deductions AS PD ON PD.deduction_id = ED.deduction_id
				LEFT JOIN param_frequencies AS PF ON PF.frequency_id = PD.frequency_id
				WHERE PD.statutory_flag = ? $add_where AND $key = ?
				$filter_str
				GROUP BY ED.employee_deduction_id, PD.deduction_name, ED.start_date, PD.deduction_type_flag, PF.frequency_name
				$sOrder
				$sLimit
EOS;
			$stmt = $this->query($query, array_merge($val,$filter_params), $multiple);

			return $stmt;
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

	public function get_deduction_history_list($aColumns, $bColumns, $params, $table, $where, $multiple = TRUE)
	{
		try
		{	
			$employee_id = $params['employee_id'];
			$key         = $this->get_hash_key('PH.employee_id');
			$val         = array();
			$fields      = str_replace(" , ", " ", implode(", ", $aColumns));
			if(!EMPTY($params['PD-effective_date'])) 
			{
				$params["DATE_FORMAT(PD-effective_date, '%Y/%m/%d')"] = $params['PD-effective_date'];
			}

			$cColumns      	= array("PC-deduction_name", "DATE_FORMAT(PD-effective_date, '%Y/%m/%d')", "PD-amount");
			$sWhere        	= $this->filtering($cColumns, $params, TRUE);
			$sOrder        	= $this->ordering($bColumns, $params);
			$sLimit        	= $this->paging($params);
			$filter_str		= $sWhere ["search_str"];
			$filter_params 	= $sWhere ["search_params"];
			$val[]         	= $employee_id;
			$query = <<<EOS
				SELECT SQL_CALC_FOUND_ROWS $fields 
				FROM payout_header AS PH 
				LEFT JOIN payout_summary AS PS ON PS.payroll_summary_id = PH.payroll_summary_id
				LEFT JOIN payout_details AS PD ON PD.payroll_hdr_id = PH.payroll_hdr_id
				JOIN employee_deductions AS ED ON ED.deduction_id = PD.deduction_id
				JOIN param_deductions AS PC ON PC.deduction_id = ED.deduction_id
				WHERE $key = ?
				$filter_str
				GROUP BY PC.deduction_name, PD.effective_date, PD.amount, PD.deduction_id
				$sOrder
				$sLimit
EOS;
			$stmt = $this->query($query, array_merge($val, $filter_params), $multiple);
			return $stmt;
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

	public function get_personnel_dependents_list($aColumns, $bColumns, $params)
	{
		try
		{

			$key             = $this->get_hash_key('employee_id');
			
			$fields          = str_replace(" , ", " ", implode(", ", $aColumns));
			$cColumns        = array("CONCAT(relation_last_name, if(relation_ext_name='','',CONCAT(' ',relation_ext_name)), ', ', relation_first_name, ' ', LEFT(relation_middle_name,1), '.')", "relation_birth_date");
			$sWhere          = $this->filtering($cColumns, $params, TRUE);
			$sOrder          = $this->ordering($bColumns, $params);
			$sLimit          = $this->paging($params);
			$filter_str      = $sWhere["search_str"];
			$filter_params   = $sWhere["search_params"];
			$filter_params[] = $params['employee_id'];
			
			$query = <<<EOS
				SELECT $fields 
				FROM $this->tbl_employee_relations
				WHERE $key = ? 
				$filter_str
				$sOrder
				$sLimit
EOS;

			RLog::debug($query);
			$stmt = $this->query($query, $filter_params, TRUE);
		
			return $stmt;
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
	
	
	public function total_length($table=NULL, $where=array(), $order_by=array(), $group_by=array())
	{
		try
		{
			
			$fields = array("COUNT(*) cnt");
	
			return $this->select_one($fields, $table, $where, $order_by, $group_by);
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


	public function get_deduction_data($fields, $table, $where = array(), $multiple = TRUE, $order_by = array(), $group_by = array(), $limit = NULL)
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

	public function insert_deduction($table, $fields, $return_id = FALSE)
	{
		try
		{
			return $this->insert_data($table, $fields, $return_id);

		}
		catch(PDOException $e)
		{
			$this->rlog_error($e);
			throw new Exception($this->lang->line('data_not_saved'));
		}
	}

	public function update_deduction($table, $fields, $where)
	{
		try
		{
			$this->update_data($table, $fields, $where);
			return TRUE;

		}
		catch (PDOException $e)
		{
			$this->rlog_error($e);
			throw new Exception($this->lang->line('data_not_updated'));
		}
	}

	public function delete_deduction($table, $where)
	{
		try
		{
			return $this->delete_data($table, $where);

		}
		catch(PDOException $e)
		{
			$this->rlog_error($e);
			throw new Exception($this->lang->line('data_not_deleted'));
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
		catch(PDOException $e)
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
			$this->rlog_error($e);
		}
		catch (Exception $e)
		{			
			$this->rlog_error($e);
		}
	}

	public function get_salary_grade()
	{
		try
		{
			 
			$query = <<<EOS
				SELECT salary_grade   FROM 
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


	public function get_remove_personnel_list($params)
	{
		try
		{
			$val            = array($params['id']);
			$employee_id    = $this->get_hash_key('A.employee_id');
			$deduction_id  = $this->get_hash_key('deduction_id');
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
				(select employee_id from $this->tbl_employee_deductions where $deduction_id = ? )
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
	
	public function get_deduction_details($select_fields, $tables, $where)
	{
		try
		{


			$fields = (!empty($select_fields)) ? $select_fields : array("*");
			
			return $this->select_all($fields, $tables, $where);
			
		
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

	private function _get_employ_type_flag($employee_id)
	{
		try {
			$where = array();
			$key = $this->get_hash_key('employee_id');
			$where[$key] = $employee_id;
			$where['active_flag'] = YES;
			$employment_flag = $this->get_deduction_data(array('employ_type_flag'), $this->tbl_employee_work_experiences, $where, FALSE);
			
			$employ_type_flag = array();
			$employ_type_flag[] = PAYROLL_TYPE_FLAG_ALL;
			switch ($employment_flag['employ_type_flag']) 
			{
				case DOH_GOV_APPT:
				case DOH_GOV_NON_APPT:
					$employ_type_flag[] = PAYROLL_TYPE_FLAG_REG;
					break;
				
				case DOH_JO:
					$employ_type_flag[] = PAYROLL_TYPE_FLAG_JO;
					$employ_type_flag[] = PAYROLL_TYPE_FLAG_REG; //jendaigo include floating deduction from plantilla
					break;
			}
		}
		catch(PDOException $e)
		{

		}
		catch(Exception $e)
		{

		}
		return $employ_type_flag;
	}
	
}
	