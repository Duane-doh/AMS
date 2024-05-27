<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Code_library_model extends Main_Model {

	public function __construct() {
		parent:: __construct();
		$this->tbl_sss = DOH_PTIS_TABLE_SSS;
	
	}

	public $db_core           	 = DB_CORE;
	public $db_main           	 = DB_MAIN;
	public $tbl_sys_param 		 = 'sys_param';
	public $tbl_sys_param_type	 = 'sys_param_type';
	public $tbl_organizations	 = 'organizations';
	public $tbl_data_dictionary	 = 'param_data_dictionary';
	public $tbl_users 			 = 'users';
	public $tbl_param_office_types  = "param_office_types";

	/*----- START GET LIST HR -----*/
	public function get_academic_honor_list($aColumns, $bColumns, $params, $table, $where, $multiple = TRUE)
	{
		try
		{
			$fields 		= str_replace(" , ", " ", implode(", ", $aColumns));
			$cColumns 		= array("academic_honor_name", "active_flag");
			$sWhere 		= $this->filtering($cColumns, $params, FALSE);
			$sOrder 		= $this->ordering($bColumns, $params);
			$sLimit 		= $this->paging($params);
			$filter_str 	= $sWhere["search_str"];
			$filter_params 	= $sWhere["search_params"];
			
			$query = <<<EOS
				SELECT $fields 
				FROM $table
				$filter_str
				GROUP BY academic_honor_id
				$sOrder
				$sLimit
EOS;
			RLog::debug($query);
			$stmt = $this->query($query, $filter_params, $multiple);
		
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

	public function get_compensation_list($aColumns, $bColumns, $params)
	{
		try
		{
			$fields 		= str_replace(" , ", " ", implode(", ", $aColumns));
			
			$params["IF(active_flag = 'Y', 'Active', 'Inactive')"] = $params["active_flag"];
				
			$sWhere			= $this->filtering($bColumns, $params, FALSE);
			$sOrder 		= $this->ordering($bColumns, $params);
			$sLimit 		= $this->paging($params);
			$filter_str 	= $sWhere["search_str"];
			$filter_params 	= $sWhere["search_params"];
			
			$query = <<<EOS
				SELECT SQL_CALC_FOUND_ROWS $fields 
				FROM $this->tbl_param_compensations A
				$filter_str
				GROUP BY A.compensation_id
				$sOrder
				$sLimit
EOS;
			RLog::debug($query);
			$stmt = $this->query($query, $filter_params, true);
		
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

	public function get_deduction_type_list($aColumns, $bColumns, $params, $table=NULL, $where=array(), $multiple = TRUE)
	{
		try
		{
			$fields 		= str_replace(" , ", " ", implode(", ", $aColumns));
			
			$params["IF(A.active_flag = 'Y', 'Active', 'Inactive')"] = $params["active_flag"];
			
			
			$sWhere 		= $this->filtering($bColumns, $params, FALSE);
			$sOrder 		= $this->ordering($bColumns, $params);
			$sLimit 		= $this->paging($params);
			$filter_str 	= $sWhere["search_str"];
			$filter_params 	= $sWhere["search_params"];

			$query = <<<EOS
				SELECT SQL_CALC_FOUND_ROWS $fields
				FROM $this->tbl_param_deductions A
				LEFT JOIN $this->tbl_param_multipliers B ON A.multiplier_id = B.multiplier_id
				LEFT JOIN $this->tbl_param_frequencies C ON A.frequency_id = C.frequency_id
				LEFT JOIN $this->tbl_param_remittance_types D ON A.remittance_type_id = D.remittance_type_id

				$filter_str	
				GROUP BY A.deduction_id
				$sOrder
				$sLimit			
EOS;
			RLog::debug($query);
			$stmt = $this->query($query, $filter_params, $multiple);
		
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

	public function get_education_degree_list($aColumns, $bColumns, $params, $table, $where=array(), $multiple = TRUE)
	{
		try
		{
			$fields 		= str_replace(" , ", " ", implode(", ", $aColumns));
			
			$params["IF(active_flag = 'Y', 'Active', 'Inactive')"] = $params["active_flag"];
			
			$sWhere 		= $this->filtering($bColumns, $params, FALSE);
			$sOrder 		= $this->ordering($bColumns, $params);
			$sLimit 		= $this->paging($params);
			$filter_str 	= $sWhere["search_str"];
			$filter_params 	= $sWhere["search_params"];
			
			$query = <<<EOS
				SELECT SQL_CALC_FOUND_ROWS $fields 
				FROM $table
				$filter_str
				GROUP BY degree_id
				$sOrder
				$sLimit
EOS;
			RLog::debug($query);
			$stmt = $this->query($query, $filter_params, $multiple);
		
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

	public function get_educational_level_list($aColumns, $bColumns, $params, $table, $where, $multiple = TRUE)
	{
		try
		{
			$fields 		= str_replace(" , ", " ", implode(", ", $aColumns));
			
			$params["IF(active_flag = 'Y', 'Active', 'Inactive')"] = $params["active_flag"];
			
			$sWhere 		= $this->filtering($bColumns, $params, FALSE);
			$sOrder 		= $this->ordering($bColumns, $params);
			$sLimit 		= $this->paging($params);
			$filter_str 	= $sWhere["search_str"];
			$filter_params 	= $sWhere["search_params"];
			
			$query = <<<EOS
				SELECT SQL_CALC_FOUND_ROWS $fields 
				FROM $table
				$filter_str
				GROUP BY educ_level_id
				$sOrder
				$sLimit
EOS;
			RLog::debug($query);
			$stmt = $this->query($query, $filter_params, $multiple);
		
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

	public function get_eligibility_list($aColumns, $bColumns, $params, $table, $where=array(), $multiple = TRUE)
	{
		try
		{
			$fields 		= str_replace(" , ", " ", implode(", ", $aColumns));
			
			$params["IF(active_flag = 'Y', 'Active', 'Inactive')"] = $params["active_flag"];
				
			$sWhere 		= $this->filtering($bColumns, $params, FALSE);
			$sOrder 		= $this->ordering($bColumns, $params);
			$sLimit 		= $this->paging($params);
			$filter_str 	= $sWhere["search_str"];
			$filter_params 	= $sWhere["search_params"];
			
			$query = <<<EOS
				SELECT SQL_CALC_FOUND_ROWS $fields 
				FROM $table
				$filter_str
				GROUP BY eligibility_type_id
				$sOrder
				$sLimit
EOS;
			RLog::debug($query);
			$stmt = $this->query($query, $filter_params, $multiple);
		
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

	public function get_employment_status_list($aColumns, $bColumns, $params, $table, $where, $multiple = TRUE)
	{
		try
		{
			$fields 		= str_replace(" , ", " ", implode(", ", $aColumns));
			
			$params["IF(active_flag = 'Y', 'Active', 'Inactive')"] = $params["active_flag"];
			
			$sWhere 		= $this->filtering($bColumns, $params, FALSE);
			$sOrder 		= $this->ordering($bColumns, $params);
			$sLimit 		= $this->paging($params);
			$filter_str 	= $sWhere["search_str"];
			$filter_params 	= $sWhere["search_params"];
			
			$query = <<<EOS
				SELECT SQL_CALC_FOUND_ROWS $fields 
				FROM $table
				$filter_str
				GROUP BY employment_status_id
				$sOrder
				$sLimit
EOS;
			RLog::debug($query);
			$stmt = $this->query($query, $filter_params, $multiple);
		
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

	public function get_branch_list($aColumns, $bColumns, $params, $table, $where, $multiple = TRUE)
	{
		try
		{
			$fields 		= str_replace(" , ", " ", implode(", ", $aColumns));

			$params["IF(active_flag = 'Y', 'Active', 'Inactive')"] = $params["active_flag"];
			
			$sWhere 		= $this->filtering($bColumns, $params, FALSE);
			$sOrder 		= $this->ordering($bColumns, $params);
			$sLimit 		= $this->paging($params);
			$filter_str 	= $sWhere["search_str"];
			$filter_params 	= $sWhere["search_params"];
			
			$query = <<<EOS
				SELECT SQL_CALC_FOUND_ROWS $fields 
				FROM $table
				$filter_str
				GROUP BY branch_id
				$sOrder
				$sLimit
EOS;
			RLog::debug($query);
			$stmt = $this->query($query, $filter_params, $multiple);
		
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

	public function get_separation_mode_list($aColumns, $bColumns, $params, $table, $where, $multiple = TRUE)
	{
		try
		{
			$fields 		= str_replace(" , ", " ", implode(", ", $aColumns));
			
			$params["IF(active_flag = 'Y', 'Active', 'Inactive')"] = $params["active_flag"];
				
			$sWhere 		= $this->filtering($bColumns, $params, FALSE);
			$sOrder 		= $this->ordering($bColumns, $params);
			$sLimit 		= $this->paging($params);
			$filter_str 	= $sWhere["search_str"];
			$filter_params 	= $sWhere["search_params"];
			
			$query = <<<EOS
				SELECT SQL_CALC_FOUND_ROWS $fields 
				FROM $table
				$filter_str
				GROUP BY separation_mode_id
				$sOrder
				$sLimit
EOS;
			RLog::debug($query);
			$stmt = $this->query($query, $filter_params, $multiple);
		
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

	public function get_personnel_movement_list($aColumns, $bColumns, $params, $table, $where = array(), $multiple = TRUE)
	{
		try
		{
			$fields 		= str_replace(" , ", " ", implode(", ", $aColumns));
			
			$params["IF(needs_appointment = 'Y', 'Yes', 'No')"] = $params["needs_appointment"];
			$params["IF(needs_office_order = 'Y', 'Yes', 'No')"] = $params["needs_office_order"];
			$params["IF(active_flag = 'Y', 'Active', 'Inactive')"] = $params["active_flag"];
					
			$sWhere 		= $this->filtering($bColumns, $params, FALSE);
			$sOrder 		= $this->ordering($bColumns, $params);
			$sLimit 		= $this->paging($params);
			$filter_str 	= $sWhere["search_str"];
			$filter_params 	= $sWhere["search_params"];
			
			$query = <<<EOS
				SELECT SQL_CALC_FOUND_ROWS $fields 
				FROM $table
				$filter_str
				GROUP BY personnel_movement_id
				$sOrder
				$sLimit
EOS;
			RLog::debug($query);
			$stmt = $this->query($query, $filter_params, $multiple);
		
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

	public function get_plantilla_list($aColumns, $bColumns, $params, $table, $where, $multiple = TRUE)
	{
	try
		{
			$val           = array();
			$date          = date('Y-m-d');
			/* For Advanced Filters */
			$params["IF(A.active_flag = 'Y', 'Active', 'Inactive')"] = $params["active_flag"];
			$cColumns      = array('A-plantilla_code', 'B-position_name', 'D-name', "IF(A.active_flag = 'Y', 'Active', 'Inactive')");
			$fields        = str_replace(" , ", " ", implode(", ", $aColumns));
			
			$sWhere        = $this->filtering($cColumns, $params, FALSE);
			$sOrder        = $this->ordering($bColumns, $params);
			$sLimit        = $this->paging($params);
			
			$filter_str    = $sWhere["search_str"];
			$filter_params = $sWhere["search_params"];

			$query = <<<EOS

				SELECT SQL_CALC_FOUND_ROWS $fields
				FROM $this->tbl_param_plantilla_items A
				LEFT JOIN $this->tbl_param_positions B ON A.position_id = B.position_id
				JOIN $this->tbl_param_offices C ON A.office_id = C.office_id
				LEFT JOIN $this->db_core.$this->tbl_organizations D ON C.org_code = D.org_code
				LEFT JOIN $this->tbl_param_plantilla_items F ON A.parent_plantilla_id = F.plantilla_id 

				LEFT JOIN $this->tbl_param_offices H ON H.office_id = A.division_id
				LEFT JOIN $this->db_core.$this->tbl_organizations I ON H.org_code = I.org_code

				$filter_str
				$sOrder
				$sLimit
EOS;
			RLog::debug($query);
			$val = array_merge($val,$filter_params);
			$stmt = $this->query($query, $val, TRUE);
		
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

	public function get_parent_plantilla_name($id = '')
	{
		try
		{
			$val = array();
			$where = '';
			if( ! empty($id))
			{
				$key = $this->get_hash_key('A.plantilla_id');

				$where = ' where '. $key . '!= ?';
				$val[] = $id;
			}
			
			$query = <<<EOS
				SELECT B.position_name, A.plantilla_id, A.plantilla_code, D.name
				FROM $this->tbl_param_plantilla_items A
				LEFT JOIN $this->tbl_param_positions B ON A.position_id = B.position_id
				JOIN $this->tbl_param_offices C ON A.office_id = C.office_id
				LEFT JOIN $this->db_core.$this->tbl_organizations D ON C.org_code = D.org_code
				$where

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

	public function get_offices($where_office = '')
	{
		try
		{
			$condition = $where_office;
			$query = <<<EOS
				SELECT B.name, A.office_id, A.org_code, B.org_code, B.org_parent, D.office_id as parent_id
				FROM $this->db_main.$this->tbl_param_offices A
				LEFT JOIN $this->db_core.$this->tbl_organizations B ON A.org_code = B.org_code
				LEFT JOIN $this->db_main.$this->tbl_param_office_types C ON A.office_type_id = C.office_type_id
				LEFT JOIN  $this->db_main.$this->tbl_param_offices D ON D.org_code = B.org_parent
				WHERE $condition
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
	public function get_division($where_division = '')
	{
		try
		{
			$condition = $where_division;
			$query = <<<EOS
				SELECT B.name, A.office_id, A.org_code, B.org_code, B.org_parent, D.office_id as parent_id
				FROM $this->db_main.$this->tbl_param_offices A
				LEFT JOIN $this->db_core.$this->tbl_organizations B ON A.org_code = B.org_code
				LEFT JOIN $this->db_main.$this->tbl_param_office_types C ON A.office_type_id = C.office_type_id
				LEFT JOIN  $this->db_main.$this->tbl_param_offices D ON D.org_code = B.org_parent
				WHERE $condition
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

	public function get_position_list($aColumns, $bColumns, $params, $tables, $where = array(), $multiple = TRUE)
	{
		try
		{
			$fields 		= str_replace(" , ", " ", implode(", ", $aColumns));
			
			$params["IF(A.active_flag = 'Y', 'Active', 'Inactive')"] = $params["active_flag"];
			$cColumns 		= array("A-position_name", "B-position_level_name", "C-position_class_level_name", "IF(A.active_flag = 'Y', 'Active', 'Inactive')");
			$sWhere			= $this->filtering($cColumns, $params, FALSE);
			$sOrder			= $this->ordering($bColumns, $params);
			$sLimit			= $this->paging($params);
			$filter_str		= $sWhere["search_str"];
			$filter_params	= $sWhere["search_params"];
			
			$query = <<<EOS
				SELECT SQL_CALC_FOUND_ROWS $fields
				FROM $this->tbl_param_positions A
				LEFT JOIN $this->tbl_param_position_levels B ON A.position_level_id = B.position_level_id
				LEFT JOIN $this->tbl_param_position_class_levels C ON A.position_class_id = C.position_class_level_id

				$filter_str
				GROUP BY A.position_id
				$sOrder
				$sLimit
EOS;
			RLog::debug($query);
			$stmt = $this->query($query, $filter_params, $multiple);

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

	public function get_salary_grade()
	{
		try
		{
			
			$query = <<<EOS
				SELECT 0 salary_grade
				UNION
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

	public function get_salary_step()
	{
		try
		{
			
			$query = <<<EOS
				SELECT 0 salary_step
				UNION
				SELECT salary_step  FROM 
				$this->tbl_param_salary_schedule where effectivity_date =(
					SELECT MAX(effectivity_date)  FROM $this->tbl_param_salary_schedule
					 WHERE effectivity_date < NOW()) group by salary_step
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

	public function get_salary_schedule($aColumns, $bColumns, $params, $table, $where, $multiple = TRUE)
	{
		try
		{

			$fields 		= str_replace(" , ", " ", implode(", ", $aColumns));
			$cColumns 		= array("effectivity_date", "active_flag");
			$sWhere 		= $this->filtering($cColumns, $params, FALSE);
			$sOrder 		= $this->ordering($bColumns, $params);
			$sLimit 		= $this->paging($params);
			$filter_str 	= $sWhere["search_str"];
			$filter_params 	= $sWhere["search_params"];
			
			$query = <<<EOS
				SELECT $fields 
				FROM $table
				$filter_str
				GROUP BY effectivity_date, other_fund_flag, active_flag
				$sOrder
				$sLimit
EOS;
			
			$stmt = $this->query($query, $val, FALSE);
						
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

	public function get_school_list($aColumns, $bColumns, $params, $table, $where=array(), $multiple = TRUE)
	{
		try
		{
			$fields 		= str_replace(" , ", " ", implode(", ", $aColumns));
			$params["IF(active_flag = 'Y', 'Active', 'Inactive')"] = $params["active_flag"];
			
			$sWhere 		= $this->filtering($bColumns, $params, FALSE);
			$sOrder 		= $this->ordering($bColumns, $params);
			$sLimit 		= $this->paging($params);
			$filter_str 	= $sWhere["search_str"];
			$filter_params 	= $sWhere["search_params"];
			
			$query = <<<EOS
				SELECT SQL_CALC_FOUND_ROWS $fields 
				FROM $table
				$filter_str
				GROUP BY school_id
				$sOrder
				$sLimit
EOS;
			RLog::debug($query);
			$stmt = $this->query($query, $filter_params, $multiple);
		
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
	/*----- END GET LIST HR -----*/

	/*----- START GET LIST TIME & ATTENDANCE -----*/
	public function get_leave_type_list($aColumns, $bColumns, $params, $table, $where=array(), $multiple = TRUE)
	{
		try
		{
			$fields 		= str_replace(" , ", " ", implode(", ", $aColumns));
			$params["IF(deduct_bal_leave_type_id = '2', 'Vacation Leave', 'Not Applicable')"]	= $params["deduct_bal_leave_type_id"];
			$params["IF(cert_flag = 'Y', 'Yes', 'No')"] 										= $params["cert_flag"];
			$params["IF(built_in_flag = 'Y', 'Yes', 'No')"] 									= $params["built_in_flag"];
			$params["IF(active_flag = 'Y', 'Active', 'Inactive')"] 								= $params["active_flag"];
			
			
			$sWhere 		= $this->filtering($bColumns, $params, FALSE);
			$sOrder 		= $this->ordering($bColumns, $params);
			$sLimit 		= $this->paging($params);
			$filter_str 	= $sWhere["search_str"];
			$filter_params 	= $sWhere["search_params"];
			
			$query = <<<EOS
				SELECT SQL_CALC_FOUND_ROWS $fields 
				FROM $table
				$filter_str
				GROUP BY leave_type_id
				$sOrder
				$sLimit
EOS;
			RLog::debug($query);
			$stmt = $this->query($query, $filter_params, $multiple);
		
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
	
	public function get_leave_type_name($table,$leave_type){
		try{
			$fields = array("*");
			$where = array('leave_type_name' => $leave_type);
			return $this->select_one($fields, $table, $where);
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

	public function get_holiday_type_list($aColumns, $bColumns, $params, $table, $where=array(), $multiple = TRUE)
	{
		try
		{
			$params["IF(active_flag = 'Y', 'Active', 'Inactive')"] = $params["active_flag"];
			
			$fields 		= str_replace(" , ", " ", implode(", ", $aColumns));
			$sWhere 		= $this->filtering($bColumns, $params, FALSE);
			$sOrder 		= $this->ordering($bColumns, $params);
			$sLimit 		= $this->paging($params);
			$filter_str 	= $sWhere["search_str"];
			$filter_params 	= $sWhere["search_params"];
			
			$query = <<<EOS
				SELECT SQL_CALC_FOUND_ROWS $fields 
				FROM $table
				$filter_str
				GROUP BY holiday_type_id
				$sOrder
				$sLimit
EOS;
			RLog::debug($query);
			$stmt = $this->query($query, $filter_params, $multiple);
		
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

	public function get_work_calendar_list($aColumns, $bColumns, $params, $table, $where, $multiple = TRUE)
	{
		try
		{
			if(!EMPTY($params['holiday_date']))
			{
				$params["DATE_FORMAT(holiday_date, '%Y/%m/%d')"] = $params['holiday_date'];
			}
			
			$fields 		= str_replace(" , ", " ", implode(", ", $aColumns));
			$cColumns 		= array("title", "description", "DATE_FORMAT(holiday_date, '%Y/%m/%d')");
			$sWhere 		= $this->filtering($cColumns, $params, FALSE);
			$sOrder 		= $this->ordering($bColumns, $params);
			$sLimit 		= $this->paging($params);
			$filter_str 	= $sWhere["search_str"];
			$filter_params 	= $sWhere["search_params"];
			
			$query = <<<EOS
				SELECT SQL_CALC_FOUND_ROWS $fields 
				FROM $table
				$filter_str
				$sOrder
				$sLimit
EOS;
			RLog::debug($query);
			$stmt = $this->query($query, $filter_params, $multiple);
		
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
public function get_work_schedule_list($aColumns, $bColumns, $params, $table, $where, $multiple = TRUE)
	{
		try
		{
			$fields 		= str_replace(" , ", " ", implode(", ", $aColumns));
			$cColumns 		= array("work_schedule_name", "active_flag");
			$sWhere 		= $this->filtering($cColumns, $params, FALSE);
			$sOrder 		= $this->ordering($bColumns, $params);
			$sLimit 		= $this->paging($params);
			$filter_str 	= $sWhere["search_str"];
			$filter_params 	= $sWhere["search_params"];
			
			$query = <<<EOS
				SELECT SQL_CALC_FOUND_ROWS $fields 
				FROM $table
				$filter_str
				$sOrder
				$sLimit
EOS;
			RLog::debug($query);
			$stmt = $this->query($query, $filter_params, $multiple);
		
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
	public function get_work_calendar_info($aColumns)
	{
		try
		{

			$query = <<<EOS
				SELECT
				WC.work_calendar_id, WC.title, WC.description, WC.holiday_date, HT.color_code
				FROM $this->tbl_param_work_calendar WC
				JOIN $this->tbl_param_holiday_types HT ON WC.holiday_type_id = HT.holiday_type_id
				
EOS;
			$stmt = $this->query($query);
		
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
	/*----- END GET LIST TIME & ATTENDANCE -----*/

	/*----- START GET LIST PAYROLL -----*/
	public function get_bank_list($aColumns, $bColumns, $params, $table, $where, $multiple = TRUE)
	{
		try
		{
			$params["IF(active_flag = 'Y', 'Active','Inactive')"] = $params['active_flag'];
			
			$fields 		= str_replace(" , ", " ", implode(", ", $aColumns));
			$cColumns 		= array("A-bank_name", "A-branch_code", "A-account_no", "B-fund_source_name", "A-active_flag");
			$sWhere 		= $this->filtering($cColumns, $params, FALSE);
			$sOrder 		= $this->ordering($bColumns, $params);
			$sLimit 		= $this->paging($params);
			$filter_str 	= $sWhere["search_str"];
			$filter_params 	= $sWhere["search_params"];
			
			$query = <<<EOS
				SELECT SQL_CALC_FOUND_ROWS $fields 
				FROM $this->tbl_param_banks A
				LEFT JOIN $this->tbl_param_fund_sources B ON A.fund_source_id = B.fund_source_id

				$filter_str
				GROUP BY A.bank_id
				$sOrder
				$sLimit			
EOS;
			RLog::debug($query);
			$stmt = $this->query($query, $filter_params, $multiple);
		
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
	/*----- START GET LIST PAYROLL -----*/
	public function get_bir_list($aColumns, $bColumns, $params, $table, $where, $multiple = TRUE)
	{
		try
		{
			$params["IF(active_flag = 'Y', 'Active', 'Inactive')"] = $params["active_flag"];
			
			$fields 		= str_replace(" , ", " ", implode(", ", $aColumns));
			if( ISSET( $params['effective_date'] ) )
			{
				$params["DATE_FORMAT(effective_date, '%Y/%m/%d')"] = $params['effective_date'];
			}
			$cColumns 		= array("DATE_FORMAT(effective_date, '%Y/%m/%d')", "tax_table_flag", "active_flag");
			$sWhere 		= $this->filtering($cColumns, $params, FALSE);
			$sOrder 		= $this->ordering($bColumns, $params);
			$sLimit 		= $this->paging($params);
			$filter_str 	= $sWhere["search_str"];
			$filter_params 	= $sWhere["search_params"];
			
			$query = <<<EOS
				SELECT SQL_CALC_FOUND_ROWS $fields 
				FROM $this->tbl_param_bir

				$filter_str
				GROUP BY bir_id
				$sOrder
				$sLimit			
EOS;
			RLog::debug($query);
			$stmt = $this->query($query, $filter_params, $multiple);
		
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

	public function get_voucher_list($aColumns, $bColumns, $params, $table, $where, $multiple = TRUE)
	{
		try
		{
			$params["IF(active_flag = 'Y', 'Active', 'Inactive')"] = $params["active_flag"];
			
			$fields 		= str_replace(" , ", " ", implode(", ", $aColumns));
			//$cColumns 		= array("voucher_name","active_flag");
			$sWhere			= $this->filtering($bColumns, $params, FALSE);
			$sOrder 		= $this->ordering($bColumns, $params);
			$sLimit 		= $this->paging($params);
			$filter_str 	= $sWhere["search_str"];
			$filter_params 	= $sWhere["search_params"];
			
			$query = <<<EOS
				SELECT SQL_CALC_FOUND_ROWS $fields 
				FROM $table
				$filter_str
				GROUP BY voucher_id
				$sOrder
				$sLimit
EOS;
			RLog::debug($query);
			$stmt = $this->query($query, $filter_params, $multiple);
		
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

	public function get_philhealth_list($aColumns, $bColumns, $params, $table, $where, $multiple = TRUE)
	{
		try
		{
			$params["IF(active_flag = 'Y', 'Active', 'Inactive')"] = $params["active_flag"];
			
			if( ISSET( $params['effectivity_date'] ) )
			{
				$params["DATE_FORMAT(effectivity_date, '%Y/%m/%d')"] = $params['effectivity_date'];
			}
			// 			if($params['effectivity_date']) $params['effectivity_date'] = format_date($params['effectivity_date'], "Y-m-d");
			$fields 		= str_replace(" , ", " ", implode(", ", $aColumns));
			$cColumns 		= array("DATE_FORMAT(effectivity_date, '%Y/%m/%d')", "active_flag");
			$sWhere			= $this->filtering($cColumns, $params, FALSE);
			$sOrder 		= $this->ordering($bColumns, $params);
			$sLimit 		= $this->paging($params);
			$filter_str 	= $sWhere["search_str"];
			$filter_params 	= $sWhere["search_params"];
				
			$query = <<<EOS
				SELECT SQL_CALC_FOUND_ROWS $fields
				FROM $table
				$filter_str
				GROUP BY effectivity_date
				$sOrder
				$sLimit
EOS;
			RLog::debug($query);
			$stmt = $this->query($query, $filter_params, $multiple);
		
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
	
	//START GET SSS LIST TABLE
	public function get_sss_list($params, $aColumns, $bColumns, $table, $sWhere, $multiple = TRUE)
	{
		try
		{
			$params["IF(active_flag = 'Y', 'Active', 'Inactive')"] = $params["active_flag"];
			
			$fields 			= str_replace(" , ", " ", implode(", ", $aColumns));
			if( ISSET( $params['effectivity_date'] ) )
				$params["DATE_FORMAT(effectivity_date, '%Y/%m/%d')"] = $params['effectivity_date'];
				
			$cColumns 			= array("DATE_FORMAT(effectivity_date, '%Y/%m/%d')", "active_flag");
			$sWhere 			= $this->filtering($cColumns, $params, FALSE);
			$sOrder 			= $this->ordering($bColumns,$params);
			$sLimit 			= $this->paging($params);
			$filter_str 		= $sWhere["search_str"];
			$filter_params 		= $sWhere["search_params"];

			$record['trial1'] 	= "try1";
			$record['trial2'] 	= "try2";
			$number['id1'] 		= "1";
			$number['id2'] 		= "2";
			$query 				= <<<EOS
				SELECT SQL_CALC_FOUND_ROWS $fields 
				FROM $table
				$filter_str
				GROUP BY effectivity_date
				$sOrder
				$sLimit 
EOS;
			$stmt 				= $this->query($query, $filter_params, $multiple);

			return $stmt;

		}
		catch(PDOException $e)
		{
			throw $e;
		}
	}
	public function get_pagibig_list($aColumns, $bColumns, $params, $table, $where, $multiple = TRUE)
	{
		try
		{
			$params["IF(active_flag = 'Y', 'Active', 'Inactive')"] = $params["active_flag"];
			if( ISSET( $params['effectivity_date'] ) )
			{
				$params["DATE_FORMAT(effectivity_date, '%Y/%m/%d')"] = $params['effectivity_date'];
			}
// 			if($params['effectivity_date']) $params['effectivity_date'] = format_date($params['effectivity_date'], "Y-m-d");
			$fields 		= str_replace(" , ", " ", implode(", ", $aColumns));
			$cColumns 		= array("DATE_FORMAT(effectivity_date, '%Y/%m/%d')", "active_flag");
			$sWhere			= $this->filtering($cColumns, $params, FALSE);
			$sOrder 		= $this->ordering($bColumns, $params);
			$sLimit 		= $this->paging($params);
			$filter_str 	= $sWhere["search_str"];
			$filter_params 	= $sWhere["search_params"];
			
			$query = <<<EOS
				SELECT SQL_CALC_FOUND_ROWS $fields 
				FROM $table
				$filter_str
				GROUP BY effectivity_date
				$sOrder
				$sLimit
EOS;
			RLog::debug($query);
			$stmt = $this->query($query, $filter_params, $multiple);
		
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

	public function get_gsis_list($aColumns, $bColumns, $params, $table, $where, $multiple = TRUE)
	{
		try
		{
			$params["IF(active_flag = 'Y', 'Active', 'Inactive')"] = $params["active_flag"];
			if( ISSET( $params['effective_date'] ) )
			{
				$params["DATE_FORMAT(effective_date, '%Y/%m/%d')"] = $params['effective_date'];
			}
			$fields 		= str_replace(" , ", " ", implode(", ", $aColumns));
			$cColumns 		= array("DATE_FORMAT(effective_date, '%Y/%m/%d')", "active_flag");
			$sWhere			= $this->filtering($cColumns, $params, FALSE);
			$sOrder 		= $this->ordering($bColumns, $params);
			$sLimit 		= $this->paging($params);
			$filter_str 	= $sWhere["search_str"];
			$filter_params 	= $sWhere["search_params"];
			
			$query = <<<EOS
				SELECT SQL_CALC_FOUND_ROWS $fields 
				FROM $table
				$filter_str
				GROUP BY effective_date
				$sOrder
				$sLimit
EOS;
			RLog::debug($query);
			$stmt = $this->query($query, $filter_params, $multiple);
		
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

	public function get_gsis_details_list($gsis_id)
	{
		try
		{
			$filter_params = array($gsis_id);
			$query = <<<EOS
				SELECT *
				FROM $this->tbl_param_gsis_details
				WHERE gsis_id = ?

EOS;
			$stmt = $this->query($query, $filter_params, FALSE);
		
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

	/*----- END GET LIST PAYROLL -----*/

	/*----- START GET LIST SYSTEM -----*/
	public function get_sys_param_list($aColumns, $bColumns, $params, $table, $where, $multiple = TRUE)
	{
		try
		{
			$fields 		= str_replace(" , ", " ", implode(", ", $aColumns));
			$cColumns 		= array("sys_param_type", "sys_param_name", "sys_param_value", "built_in_flag");
			$sWhere 		= $this->filtering($cColumns, $params, FALSE);
			$sOrder 		= $this->ordering($bColumns, $params);
			$sLimit 		= $this->paging($params);
			$filter_str 	= $sWhere["search_str"];
			$filter_params 	= $sWhere["search_params"];
			
			$query = <<<EOS
				SELECT SQL_CALC_FOUND_ROWS $fields
				from $this->db_core.$this->tbl_sys_param 

				$filter_str	
				GROUP BY sys_param_id
				$sOrder
				$sLimit			
EOS;
			RLog::debug($query);
			$stmt = $this->query($query, $filter_params, $multiple);
		
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

	public function get_check_list($aColumns, $bColumns, $params, $table, $where, $multiple = TRUE)
	{
		try
		{
			$fields 		= str_replace(" , ", " ", implode(", ", $aColumns));
			$cColumns 		= array("check_list_code","check_list_description", "active_flag");
			$sWhere 		= $this->filtering($cColumns, $params, FALSE);
			$sOrder 		= $this->ordering($bColumns, $params);
			$sLimit 		= $this->paging($params);
			$filter_str 	= $sWhere["search_str"];
			$filter_params 	= $sWhere["search_params"];
			
			$query = <<<EOS
				SELECT SQL_CALC_FOUND_ROWS $fields
				FROM $this->t$this->tbl_param_checklists

				$filter_str	
				GROUP BY check_list_id
				$sOrder
				$sLimit			
EOS;
			RLog::debug($query);
			$stmt = $this->query($query, $filter_params, $multiple);
		
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

	public function get_supp_doc_type_list($aColumns, $bColumns, $params, $table, $where, $multiple = TRUE)
	{
		try
		{
			$fields 		= str_replace(" , ", " ", implode(", ", $aColumns));
			$cColumns 		= array("supp_doc_type_name", "active_flag");
			$sWhere 		= $this->filtering($cColumns, $params, FALSE);
			$sOrder 		= $this->ordering($bColumns, $params);
			$sLimit 		= $this->paging($params);
			$filter_str 	= $sWhere["search_str"];
			$filter_params 	= $sWhere["search_params"];
			
			$query = <<<EOS
				SELECT SQL_CALC_FOUND_ROWS $fields 
				FROM $table
				$filter_str	
				GROUP BY supp_doc_type_id
				$sOrder
				$sLimit			
EOS;
			RLog::debug($query);
			$stmt = $this->query($query, $filter_params, $multiple);
		
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

	public function get_checklist_supp_doc_list($check_list_id)
	{
		try
		{
			$filter_params = array($check_list_id);
			$query = <<<EOS
				SELECT *
				FROM $this->tbl_param_checklist_docs
				WHERE check_list_id = ?

EOS;
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
	/*----- END GET LIST SYSTEM -----*/

	public function get_training_list($aColumns, $bColumns, $params, $table, $where, $multiple = TRUE)
	{
		try
		{
			$fields 		= str_replace(" , ", " ", implode(", ", $aColumns));
			$cColumns 		= array("training_name", "active_flag");
			$sWhere 		= $this->filtering($cColumns, $params, FALSE);
			$sOrder 		= $this->ordering($bColumns, $params);
			$sLimit 		= $this->paging($params);
			$filter_str 	= $sWhere["search_str"];
			$filter_params 	= $sWhere["search_params"];
			
			$query = <<<EOS
				SELECT SQL_CALC_FOUND_ROWS $fields 
				FROM $table
				$filter_str
				GROUP BY training_id
				$sOrder
				$sLimit
EOS;
			RLog::debug($query);
			$stmt = $this->query($query, $filter_params, $multiple);
		
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

	public function get_examination_list($aColumns, $bColumns, $params, $table, $where, $multiple = TRUE)
	{
		try
		{
			$fields 		= str_replace(" , ", " ", implode(", ", $aColumns));
			$cColumns 		= array("examination_name", "active_flag");
			$sWhere 		= $this->filtering($cColumns, $params, FALSE);
			$sOrder 		= $this->ordering($bColumns, $params);
			$sLimit 		= $this->paging($params);
			$filter_str 	= $sWhere["search_str"];
			$filter_params 	= $sWhere["search_params"];
			
			$query = <<<EOS
				SELECT SQL_CALC_FOUND_ROWS $fields 
				FROM $table
				$filter_str
				GROUP BY examination_id
				$sOrder
				$sLimit
EOS;
			RLog::debug($query);
			$stmt = $this->query($query, $filter_params, $multiple);
		
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

	public function get_occupational_header_list($aColumns, $bColumns, $params, $table, $where, $multiple = TRUE)
	{
		try
		{
			$fields 		= str_replace(" , ", " ", implode(", ", $aColumns));
			$cColumns 		= array("occupational_header_name", "active_flag");
			$sWhere 		= $this->filtering($cColumns, $params, FALSE);
			$sOrder 		= $this->ordering($bColumns, $params);
			$sLimit 		= $this->paging($params);
			$filter_str 	= $sWhere["search_str"];
			$filter_params 	= $sWhere["search_params"];
			
			$query = <<<EOS
				SELECT SQL_CALC_FOUND_ROWS $fields 
				FROM $table
				$filter_str
				GROUP BY occupational_header_id
				$sOrder
				$sLimit
EOS;
			RLog::debug($query);
			$stmt = $this->query($query, $filter_params, $multiple);
		
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

	public function get_fund_source_list($aColumns, $bColumns, $params, $table, $where, $multiple = TRUE)
	{
		try
		{
			$params["IF(active_flag = 'Y', 'Active', 'Inactive')"] = $params["active_flag"];
			
			$fields 		= str_replace(" , ", " ", implode(", ", $aColumns));
			$sWhere 		= $this->filtering($bColumns, $params, FALSE);
			$sOrder 		= $this->ordering($bColumns, $params);
			$sLimit 		= $this->paging($params);
			$filter_str 	= $sWhere["search_str"];
			$filter_params 	= $sWhere["search_params"];
			
			$query = <<<EOS
				SELECT SQL_CALC_FOUND_ROWS $fields 
				FROM $table
				$filter_str
				GROUP BY fund_source_id
				$sOrder
				$sLimit
EOS;
			RLog::debug($query);
			$stmt = $this->query($query, $filter_params, $multiple);
		
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
	
	public function get_remittance_payee_list($aColumns, $bColumns, $params, $table, $where, $multiple = TRUE)
	{
		try
		{
			$params["IF(active_flag = 'Y', 'Active', 'Inactive')"] = $params["active_flag"];
			
			$fields 		= str_replace(" , ", " ", implode(", ", $aColumns));
			//$cColumns 		= array("remittance_payee_name", "active_flag");
			$sWhere 		= $this->filtering($bColumns, $params, FALSE);
			$sOrder 		= $this->ordering($bColumns, $params);
			$sLimit 		= $this->paging($params);
			$filter_str 	= $sWhere["search_str"];
			$filter_params 	= $sWhere["search_params"];
				
			$query = <<<EOS
				SELECT SQL_CALC_FOUND_ROWS $fields
				FROM $table
				$filter_str
				GROUP BY remittance_payee_id
				$sOrder
				$sLimit
EOS;
			RLog::debug($query);
			$stmt = $this->query($query, $filter_params, $multiple);
	
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

	// public function get_responsibility_center_list($aColumns, $bColumns, $params, $table, $where, $multiple = TRUE)
	// ====================== jendaigo : start : remove var $table ============= //
	public function get_responsibility_center_list($aColumns, $bColumns, $params, $where, $multiple = TRUE)
	// ====================== jendaigo : start : remove var $table ============= //
	{
		try
		{
			// $params["IF(active_flag = 'Y', 'Active', 'Inactive')"] = $params["active_flag"];
			
			// ====================== jendaigo : start : declaration for filtering ============= //
			$params["IF(A.active_flag = 'Y', 'Active', 'Inactive')"] = $params["active_flag"];
			$params["A.responsibility_center_code"] 				 = $params["responsibility_center_code"];
			// ====================== jendaigo : end : declaration for filtering ============= //

			$fields 		= str_replace(" , ", " ", implode(", ", $aColumns));
			//$cColumns 		= array("remittance_payee_name", "active_flag");
			$sWhere 		= $this->filtering($bColumns, $params, FALSE);
			$sOrder 		= $this->ordering($bColumns, $params);
			$sLimit 		= $this->paging($params);
			$filter_str 	= $sWhere["search_str"];
			$filter_params 	= $sWhere["search_params"];

			/* $query = <<<EOS
				SELECT SQL_CALC_FOUND_ROWS $fields
				FROM $table
				$filter_str
				$sOrder
				$sLimit
EOS;
*/
// ====================== jendaigo : start : include prexc code tables ============= //
$query = <<<EOS
				SELECT SQL_CALC_FOUND_ROWS $fields
				FROM $this->tbl_param_responsibility_centers A
				JOIN $this->tbl_param_responsibility_prexc_codes B 
				  ON A.responsibility_center_code = B.responsibility_center_code
				LEFT JOIN $this->tbl_param_prexc_codes C 
				  ON B.prexc_code_id = C.prexc_code_id
				$filter_str
				$sOrder
				$sLimit
EOS;

// ====================== jendaigo : start : include prexc code tables ============= //
			RLog::debug($query);
			$stmt = $this->query($query, $filter_params, $multiple);
			
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
	
	// ====================== jendaigo : start : get uacs codes ============= //
	public function get_uacs_code_list($aColumns, $bColumns, $params, $where, $multiple = TRUE)
	{
		try
		{
			$params["IF(A.active_flag = 'Y', 'Active', 'Inactive')"] = $params["active_flag"];

			$fields 		= str_replace(" , ", " ", implode(", ", $aColumns));
			$sWhere 		= $this->filtering($bColumns, $params, FALSE);
			$sOrder 		= $this->ordering($bColumns, $params);
			$sLimit 		= $this->paging($params);
			$filter_str 	= $sWhere["search_str"];
			$filter_params 	= $sWhere["search_params"];

$query = <<<EOS
				SELECT SQL_CALC_FOUND_ROWS $fields
				FROM $this->tbl_param_uacs_object_codes A
				JOIN $this->tbl_param_uacs_object_types B 
				  ON A.uacs_object_type_id = B.uacs_object_type_id
				$filter_str
				$sOrder
				$sLimit
EOS;

			RLog::debug($query);
			$stmt = $this->query($query, $filter_params, $multiple);
			
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
	// ====================== jendaigo : end : get uacs codes ============= //
	
	public function get_office_list($aColumns, $bColumns, $params, $table, $where, $multiple = TRUE)
	{
		try
		{
			$fields 		= str_replace(" , ", " ", implode(", ", $aColumns));
			$cColumns 		= array("B-name", "A-active_flag");
			$sWhere 		= $this->filtering($cColumns, $params, FALSE);
			$sOrder 		= $this->ordering($bColumns, $params);
			$sLimit 		= $this->paging($params);
			$filter_str 	= $sWhere["search_str"];
			$filter_params 	= $sWhere["search_params"];

			$query = <<<EOS
				SELECT SQL_CALC_FOUND_ROWS $fields 
				FROM $this->tbl_param_offices A
				LEFT JOIN $this->db_core.$this->tbl_organizations B ON A.org_code = B.org_code

				$filter_str
				GROUP BY A.office_id
				$sOrder
				$sLimit
EOS;
			RLog::debug($query);
			$stmt = $this->query($query, $filter_params, $multiple);
		
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

	public function get_salary_grade_list($aColumns, $bColumns, $params, $table, $where, $multiple = TRUE)
	{
		try
		{
			$fields 		= str_replace(" , ", " ", implode(", ", $aColumns));
			$cColumns 		= array("salary_grade_name", "active_flag");
			$sWhere 		= $this->filtering($cColumns, $params, FALSE);
			$sOrder 		= $this->ordering($bColumns, $params);
			$sLimit 		= $this->paging($params);
			$filter_str 	= $sWhere["search_str"];
			$filter_params 	= $sWhere["search_params"];
			
			$query = <<<EOS
				SELECT SQL_CALC_FOUND_ROWS $fields 
				FROM $table
				$filter_str
				GROUP BY salary_grade_id
				$sOrder
				$sLimit
EOS;
			RLog::debug($query);
			$stmt = $this->query($query, $filter_params, $multiple);
		
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

	public function get_salary_grade_steps_list($aColumns, $bColumns, $params)
	{
		try
		{
			
			if(!EMPTY($params['effectivity_date']))
			{
				$params["DATE_FORMAT(effectivity_date, '%Y/%m/%d')"] = $params['effectivity_date'];
			}

			$fields 		= str_replace(" , ", " ", implode(", ", $aColumns));
			
			$params["IF(other_fund_flag = 'Y', 'Yes', 'No')"] = $params["other_fund_flag"];
			$params["IF(active_flag = 'Y', 'Active', 'Inactive')"] = $params["active_flag"];

			$sWhere			= $this->filtering($bColumns, $params, FALSE);
			$sOrder 		= $this->ordering($bColumns, $params);
			$sLimit 		= $this->paging($params);
			$filter_str 	= $sWhere["search_str"];
			$filter_params 	= $sWhere["search_params"];
			
			$query = <<<EOS
				SELECT SQL_CALC_FOUND_ROWS $fields
				FROM $this->tbl_param_salary_schedule
				$filter_str
				GROUP BY effectivity_date, other_fund_flag, active_flag
				$sOrder
				$sLimit
EOS;
			RLog::debug($query);
			$stmt = $this->query($query, $filter_params);
			
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


	public function get_appointment_status_list($aColumns, $bColumns, $params, $table, $where, $multiple = TRUE)
	{
		try
		{
			$fields 		= str_replace(" , ", " ", implode(", ", $aColumns));
			$cColumns 		= array("appointment_status_name", "active_flag");
			$sWhere 		= $this->filtering($cColumns, $params, FALSE);
			$sOrder 		= $this->ordering($bColumns, $params);
			$sLimit 		= $this->paging($params);
			$filter_str 	= $sWhere["search_str"];
			$filter_params 	= $sWhere["search_params"];
			
			$query = <<<EOS
				SELECT SQL_CALC_FOUND_ROWS $fields 
				FROM $table
				$filter_str
				$sOrder
				$sLimit
EOS;
			RLog::debug($query);
			$stmt = $this->query($query, $filter_params, $multiple);
		
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

	public function get_request_status_list($aColumns, $bColumns, $params, $table, $where, $multiple = TRUE)
	{
		try
		{
			$fields 		= str_replace(" , ", " ", implode(", ", $aColumns));
			$cColumns 		= array("request_status_name", "active_flag");
			$sWhere 		= $this->filtering($cColumns, $params, FALSE);
			$sOrder 		= $this->ordering($bColumns, $params);
			$sLimit 		= $this->paging($params);
			$filter_str 	= $sWhere["search_str"];
			$filter_params 	= $sWhere["search_params"];
			
			$query = <<<EOS
				SELECT SQL_CALC_FOUND_ROWS $fields 
				FROM $table
				$filter_str
				$sOrder
				$sLimit
EOS;
			RLog::debug($query);
			$stmt = $this->query($query, $filter_params, $multiple);
		
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

	public function get_designation_list($aColumns, $bColumns, $params, $table, $where, $multiple = TRUE)
	{
		try
		{
			$fields 		= str_replace(" , ", " ", implode(", ", $aColumns));
			$cColumns 		= array("designation_name", "active_flag");
			$sWhere 		= $this->filtering($cColumns, $params, FALSE);
			$sOrder 		= $this->ordering($bColumns, $params);
			$sLimit 		= $this->paging($params);
			$filter_str 	= $sWhere["search_str"];
			$filter_params 	= $sWhere["search_params"];
			
			$query = <<<EOS
				SELECT SQL_CALC_FOUND_ROWS $fields 
				FROM $table
				$filter_str
				GROUP BY designation_id
				$sOrder
				$sLimit
EOS;
			RLog::debug($query);
			$stmt = $this->query($query, $filter_params, $multiple);
		
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

	public function get_nature_employment_list($aColumns, $bColumns, $params, $table, $where, $multiple = TRUE)
	{
		try
		{
			$fields 		= str_replace(" , ", " ", implode(", ", $aColumns));
			$cColumns 		= array("nature_employment_name", "active_flag");
			$sWhere 		= $this->filtering($cColumns, $params, FALSE);
			$sOrder 		= $this->ordering($bColumns, $params);
			$sLimit 		= $this->paging($params);
			$filter_str 	= $sWhere["search_str"];
			$filter_params 	= $sWhere["search_params"];
			
			$query = <<<EOS
				SELECT SQL_CALC_FOUND_ROWS $fields 
				FROM $table
				$filter_str
				GROUP BY nature_employment_id
				$sOrder
				$sLimit
EOS;
			RLog::debug($query);
			$stmt = $this->query($query, $filter_params, $multiple);
		
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

	public function get_appointment_type_list($aColumns, $bColumns, $params, $table, $where, $multiple = TRUE)
	{
		try
		{
			$fields 		= str_replace(" , ", " ", implode(", ", $aColumns));
			$cColumns 		= array("appointment_type_name", "active_flag");
			$sWhere 		= $this->filtering($cColumns, $params, FALSE);
			$sOrder 		= $this->ordering($bColumns, $params);
			$sLimit 		= $this->paging($params);
			$filter_str 	= $sWhere["search_str"];
			$filter_params 	= $sWhere["search_params"];
			
			$query = <<<EOS
				SELECT SQL_CALC_FOUND_ROWS $fields 
				FROM $table
				$filter_str
				GROUP BY appointment_type_id
				$sOrder
				$sLimit
EOS;
			RLog::debug($query);
			$stmt = $this->query($query, $filter_params, $multiple);
		
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


	public function get_replacement_reason_list($aColumns, $bColumns, $params, $table, $where, $multiple = TRUE)
	{
		try
		{
			$fields 		= str_replace(" , ", " ", implode(", ", $aColumns));
			$cColumns 		= array("replacement_reason_name", "active_flag");
			$sWhere 		= $this->filtering($cColumns, $params, FALSE);
			$sOrder 		= $this->ordering($bColumns, $params);
			$sLimit 		= $this->paging($params);
			$filter_str 	= $sWhere["search_str"];
			$filter_params 	= $sWhere["search_params"];
			
			$query = <<<EOS
				SELECT SQL_CALC_FOUND_ROWS $fields 
				FROM $table
				$filter_str
				GROUP BY replacement_reason_id
				$sOrder
				$sLimit
EOS;
			RLog::debug($query);
			$stmt = $this->query($query, $filter_params, $multiple);
		
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

	public function get_remittance_type_list($aColumns, $bColumns, $params, $table, $where, $multiple = TRUE)
	{
		try
		{
			$params["IF(active_flag = 'Y', 'Active', 'Inactive')"] = $params["active_flag"];
			
			$fields 		= str_replace(" , ", " ", implode(", ", $aColumns));
			//$cColumns 		= array("remittance_type_name", "active_flag");
			$sWhere 		= $this->filtering($bColumns, $params, FALSE);
			$sOrder 		= $this->ordering($bColumns, $params);
			$sLimit 		= $this->paging($params);
			$filter_str 	= $sWhere["search_str"];
			$filter_params 	= $sWhere["search_params"];
			
			$query = <<<EOS
				SELECT SQL_CALC_FOUND_ROWS $fields 
				FROM $table
				$filter_str
				GROUP BY remittance_type_id
				$sOrder
				$sLimit
EOS;
			RLog::debug($query);
			$stmt = $this->query($query, $filter_params, $multiple);
		
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

	public function plantilla_appointment_status()
	{
		try
		{
			$val 	= array();
			$flag   = "active_flag";

			$query = <<<EOS
				SELECT appointment_status_name, appointment_status_id
				FROM $this->tbl_param_appointment_status
				WHERE (appointment_status_name = 'Contractual' OR  appointment_status_name = 'Regular') AND active_flag = 'Y'
EOS;
			RLog::debug($query);
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

	public function delete_code_library($table, $where)
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
	
	public function get_code_library_data($fields, $table, $where = array(), $multiple = TRUE, $order_by = array(), $group_by = array(), $limit = NULL)
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
	

	public function get_sss_details($where){
		try
		{
			$fields = array('*');
			$details = $this->select_all($fields,$this->tbl_sss,$where);
			
			return $details;			
		}
		catch(PDOException $e)
		{
			throw $e;
		}

	}

	public function insert_code_library($table, $fields, $return_id = FALSE)
	{
		try
		{
			return $this->insert_data($table, $fields, $return_id);

		}
		catch(PDOException $e)
		{
			throw $e;
		}
	}

	public function update_code_library($table, $fields, $where)
	{
		try
		{

			$this->update_data($table, $fields, $where);
			return TRUE;

		}
		catch (PDOException $e)
		{
			throw $e;
		}
	}

	public function get_dropdown_list($aColumns, $bColumns, $params, $table, $where, $multiple = TRUE)
	{
		try
		{
			$fields 		= str_replace(" , ", " ", implode(", ", $aColumns));
			$cColumns 		= array("A-dropdown_name","A-table_name", "A-created_date");
			$sWhere 		= $this->filtering($cColumns, $params, FALSE);
			$sOrder 		= $this->ordering($bColumns, $params);
			$sLimit 		= $this->paging($params);
			$filter_str 	= $sWhere["search_str"];
			$filter_params 	= $sWhere["search_params"];
			
			$query = <<<EOS
				SELECT SQL_CALC_FOUND_ROWS $fields
				FROM $this->tbl_param_dropdown A
				LEFT JOIN $this->tbl_download_template_dtl B
				ON B.dropdown = A.dropdown_id

				$filter_str	
				GROUP BY A.dropdown_id
				$sOrder
				$sLimit			
EOS;
			RLog::debug($query);
			$stmt = $this->query($query, $filter_params, $multiple);
		
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

	public function get_employees_with_leaves($date)
	{
		try
		{
			$val = array($date, LEAVE_FILE_LEAVE);
			$query = <<<EOS
				SELECT
				A.*, C.*
				FROM $this->tbl_employee_leave_details A
				JOIN  $this->tbl_employee_work_schedules B ON A.employee_id = B.employee_id AND ISNULL(B.end_date)
				JOIN $this->tbl_param_work_schedules C ON B.work_schedule_id = C.work_schedule_id
				WHERE ?
				BETWEEN leave_start_date AND leave_end_date
				AND leave_transaction_type_id = ?
EOS;
			$stmt 	= $this->query($query, $val);
			return $stmt;
			
		}
		catch (PDOException $e)
		{
			$this->rlog($e);
		}
		
		catch(Exception $e)
		{
			$this->rlog($e);
		}
	}

	public function get_remittance_type_deduction($val)
	{
		try
		{
			$fields	= "deduction_id, file_name";
			$query 	= <<<EOS
				SELECT $fields
				FROM $this->tbl_param_remittance_type_deductions 
				WHERE remittance_type_id = ?
EOS;
			RLog::debug($query);
			$stmt = $this->query($query, $val);
			
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

	public function get_code_library_details($select_fields, $tables, $where)
	{
		try
		{


			$fields = (!empty($select_fields)) ? $select_fields : array("*");
			
			$stmt = $this->select_all($fields, $tables, $where);
			
		
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

	/*----- END GET LIST SYSTEM -----*/
	


	public function get_bir_tax_table($where)
	{
		try
		{
	
			$where_arr	= $this->get_where_statement($where);
			$where 		= $where_arr['where'];
			$val		= $where_arr['val'];
	
			$query = <<<EOS
				SELECT
					sys_param_name,
					sys_param_value
				FROM $this->db_core.$this->tbl_sys_param
				$where
EOS;
	
			$stmt 	= $this->query($query, $val);
			return $stmt;
	
		}
		catch (PDOException $e)
		{
			$this->rlog($e);
		}
	
		catch(Exception $e)
		{
			$this->rlog($e);
		}
	}
	public function get_remittance_file_uploads($val)
	{
		try
		{
			$query = <<<EOS
				SELECT
					sys_param_name,
					sys_param_value
				FROM $this->db_core.$this->tbl_sys_param
				WHERE sys_param_type = ?
EOS;
			$stmt 	= $this->query($query, $val);
			return $stmt;
			
		}
		catch (PDOException $e)
		{
			$this->rlog($e);
		}
		
		catch(Exception $e)
		{
			$this->rlog($e);
		}
	}

	public function get_multiplier_list($where_multiplier)
	{
		try
		{
			$condition = $where_multiplier;
			$val 	   = array(MULTIPLIER_TAXABLE_INCOME);
			$query = <<<EOS
				SELECT *
				FROM $this->tbl_param_multipliers
				WHERE multiplier_id != ?				
				$condition
EOS;
	
			$stmt 	= $this->query($query, $val, TRUE);
			return $stmt;
	
		}
		catch (PDOException $e)
		{
			$this->rlog($e);
		}
	
		catch(Exception $e)
		{
			$this->rlog($e);
		}
	}

	public function compensation_filtered_length($aColumns, $bColumns, $params, $table)
	{
		try
		{
			$this->get_compensation_list($aColumns, $bColumns, $params, $table);
			
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

	public function deduction_filtered_length($aColumns, $bColumns, $params, $table)
	{
		try
		{
			$this->get_deduction_type_list($aColumns, $bColumns, $params, $table);
			
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

	public function educ_degree_filtered_length($aColumns, $bColumns, $params, $table)
	{
		try
		{
			$this->get_education_degree_list($aColumns, $bColumns, $params, $table);
			
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

	public function educ_level_filtered_length($aColumns, $bColumns, $params, $table)
	{
		try
		{
			$this->get_educational_level_list($aColumns, $bColumns, $params, $table);
			
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

	public function eligibility_filtered_length($aColumns, $bColumns, $params, $table)
	{
		try
		{
			$this->get_eligibility_list($aColumns, $bColumns, $params, $table);
			
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

	public function emp_status_filtered_length($aColumns, $bColumns, $params, $table)
	{
		try
		{
			$this->get_employment_status_list($aColumns, $bColumns, $params, $table);
			
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

	public function branch_filtered_length($aColumns, $bColumns, $params, $table)
	{
		try
		{
			$this->get_branch_list($aColumns, $bColumns, $params, $table);
			
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

	public function separation_mode_filtered_length($aColumns, $bColumns, $params, $table)
	{
		try
		{
			$this->get_separation_mode_list($aColumns, $bColumns, $params, $table);
			
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

	public function personnel_movt_filtered_length($aColumns, $bColumns, $params, $table)
	{
		try
		{
			$this->get_personnel_movement_list($aColumns, $bColumns, $params, $table);
			
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

	public function plantilla_filtered_length($aColumns, $bColumns, $params, $table)
	{
		try
		{
			$this->get_plantilla_list($aColumns, $bColumns, $params, $table, TRUE);
			
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

	public function position_filtered_length($aColumns, $bColumns, $params, $table)
	{
		try
		{
			$this->get_position_list($aColumns, $bColumns, $params, $table);
			
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

	public function salary_sched_filtered_length($aColumns, $bColumns, $params, $table)
	{
		try
		{
			$this->get_salary_grade_steps_list($aColumns, $bColumns, $params, $table);
			
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

	public function school_filtered_length($aColumns, $bColumns, $params, $table)
	{
		try
		{
			$this->get_school_list($aColumns, $bColumns, $params, $table);
			
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

	public function leave_type_filtered_length($aColumns, $bColumns, $params, $table)
	{
		try
		{
			$this->get_leave_type_list($aColumns, $bColumns, $params, $table);
			
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

	public function holiday_type_filtered_length($aColumns, $bColumns, $params, $table)
	{
		try
		{
			$this->get_holiday_type_list($aColumns, $bColumns, $params, $table);
			
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

	public function work_calendar_filtered_length($aColumns, $bColumns, $params, $table)
	{
		try
		{
			$this->get_work_calendar_list($aColumns, $bColumns, $params, $table);
			
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
	
	//Not to accept the same name input
	public function get_duplicated_name($table,$colname,$colval){
		try{
			$fields = array("*");
			$where = array($colname => $colval);
			return $this->select_one($fields, $table, $where);
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

	public function get_active_salary_schedules($other_fund_flag, $effective_date)
	{
		try
		{
			$val = array(YES, $other_fund_flag, $effective_date);
			$query 	= <<<EOS
				SELECT 
				effectivity_date, other_fund_flag
				FROM $this->tbl_param_salary_schedule
				WHERE active_flag = ?
				AND other_fund_flag = ?
				AND effectivity_date != ?
				GROUP BY effectivity_date
EOS;
			
			$stmt = $this->query($query, $val, FALSE);
						
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
	public function get_signatories_list($aColumns, $bColumns, $params, $table, $where, $multiple = TRUE)
	{
		try
		{
			$fields 		= str_replace(" , ", " ", implode(", ", $aColumns));
			
			$params["IF(sys_code_flags = 'Y', 'Active', 'Inactive')"] = $params["sys_code_flags"];
			$params["IF(signatory_type_flags = 'Y', 'Active', 'Inactive')"] = $params["signatory_type_flags"];
			
			$sWhere 		= $this->filtering($bColumns, $params, FALSE);
			$sOrder 		= $this->ordering($bColumns, $params);
			$sLimit 		= $this->paging($params);
			$filter_str 	= $sWhere["search_str"];
			$filter_params 	= $sWhere["search_params"];
			
			$query = <<<EOS
				SELECT SQL_CALC_FOUND_ROWS $fields
				FROM $this->tbl_param_report_signatories

				$filter_str	
				$sOrder
				$sLimit			
EOS;
			RLog::debug($query);
			$stmt = $this->query($query, $filter_params, $multiple);
		
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
	
	public function get_other_deduction_details($id)
	{
		try
		{
			$val = array($id);
// 			$where = '';
// 			if(isset($id))
// 			{
// 				$key = $this->get_hash_key('A.deduction_id');
				
// 				$where = ' where '. $key . '= ?';
// 				$val[] = $id;
// 			}
			
			$query = <<<EOS
				SELECT A.*, B.employee_other_ded_dtl_id
				FROM $this->tbl_param_other_deduction_details A
				LEFT JOIN $this->tbl_employee_deduction_other_details B ON A.other_deduction_detail_id = B.other_deduction_detail_id
				where A.deduction_id = ?
				GROUP BY A.other_deduction_detail_id
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
	//end

	/*----- davcorrea START GET COMPUTATION TABLE DAYS -----*/
	public function get_computation_table_list($aColumns, $bColumns, $params, $table, $where=array(), $multiple = TRUE)
	{
		try
		{
			$fields 		= str_replace(" , ", " ", implode(", ", $aColumns));
			$params["IF(active_flag = 'Y', 'Active', 'Inactive')"] 								= $params["active_flag"];
			
			
			$sWhere 		= $this->filtering($bColumns, $params, FALSE);
			$sOrder 		= $this->ordering($bColumns, $params);
			$sLimit 		= $this->paging($params);
			$filter_str 	= $sWhere["search_str"];
			$filter_params 	= $sWhere["search_params"];
			
			$query = <<<EOS
				SELECT SQL_CALC_FOUND_ROWS $fields 
				FROM $table A
				LEFT JOIN param_computation_table_type B ON A.computation_table_type_id = B.computation_table_type_id
				$filter_str
				GROUP BY  computation_table_id
				$sOrder
				$sLimit
EOS;
			RLog::debug($query);
			$stmt = $this->query($query, $filter_params, $multiple);
		
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
	public function comp_table_list_filtered_length($aColumns, $bColumns, $params, $table)
	{
		try
		{
			$this->get_computation_table_list($aColumns, $bColumns, $params, $table);
			
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
	public function get_computation_table_detail_list($aColumns, $bColumns, $params, $table, $where=array(), $multiple = TRUE)
	{
		try
		{
			$fields 		= str_replace(" , ", " ", implode(", ", $aColumns));
			$params["IF(active_flag = 'Y', 'Active', 'Inactive')"] 								= $params["active_flag"];
			$val = array($where);
			$key 	= $this->get_hash_key('computation_table_id');
			$sWhere 		= $this->filtering($bColumns, $params, FALSE);
			$sOrder 		= $this->ordering($bColumns, $params);
			$sLimit 		= $this->paging($params);
			$filter_str 	= $sWhere["search_str"];
			$filter_params 	= $sWhere["search_params"];
			
			$query = <<<EOS
				SELECT SQL_CALC_FOUND_ROWS $fields 
				FROM $table
				WHERE $key = ?
				$filter_str
				$sOrder
				$sLimit
EOS;
			RLog::debug($query);
			$stmt = $this->query($query,$val, TRUE);
		
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
	public function comp_table_detail_list_filtered_length($aColumns, $bColumns, $params, $table, $where)
	{
		try
		{
			$this->get_computation_table_detail_list($aColumns, $bColumns, $params, $table, $where);
			
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
	// END
	

}

/* End of file Code_library_model.php */
/* Location: ./application/modules/main/controllers/Code_library_model.php */