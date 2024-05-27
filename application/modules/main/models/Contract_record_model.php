<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Contract_record_model extends Main_Model {

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

	public function get_employee_list($aColumns, $bColumns, $params)
	{
		try
		{
			$val = array();
			
			/* For Advanced Filters */
			$cColumns = array("A-agency_employee_id", "A-last_name", "A-first_name", "C-office_name", "D-status_name");
						
			$fields = str_replace(" , ", " ", implode(", ", $aColumns));
			
			$sWhere = $this->filtering($cColumns, $params, TRUE);
			$sOrder = $this->ordering($bColumns, $params);
			$sLimit = $this->paging($params);
			
			$filter_str = $sWhere["search_str"];
			$filter_params = $sWhere["search_params"];
			
			$query = <<<EOS
				SELECT SQL_CALC_FOUND_ROWS $fields 
				FROM $this->tbl_employee_personal_info A
				LEFT JOIN $this->tbl_employee_offices B ON A.employee_id = B.employee_id
				LEFT JOIN $this->tbl_param_offices C ON C.office_id = B.office_id
				JOIN $this->tbl_param_employee_status D ON A.pds_status = D.status_id

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

	public function filtered_length($aColumns, $bColumns, $params)
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

	public function get_employee_contract_record($aColumns, $bColumns, $params, $id)
	{
		try
		{
			$val 	= array($id);
			$key 	= $this->get_hash_key('A.employee_id');
			
			/* For Advanced Filters */
			$cColumns 	= array("A-service_start", "A-service_end", "C-position_name", "D-employment_type_name", "E-station");
			$fields 	= str_replace(" , ", " ", implode(", ", $aColumns));
			
			$sWhere = $this->filtering($cColumns, $params, TRUE);
			$sOrder = $this->ordering($bColumns, $params);
			$sLimit = $this->paging($params);
			
			$filter_str 	= $sWhere["search_str"];
			$filter_params 	= $sWhere["search_params"];

			$query = <<<EOS
				SELECT $fields
				FROM $this->tbl_employee_service_record 	A
				JOIN $this->tbl_employee_personal_info 		B  ON A.employee_id 	= B.employee_id
				JOIN $this->tbl_param_positions				C  ON A.position 		= C.position_id
				JOIN $this->tbl_param_employment_status		D  ON A.service_status  = D.employment_status_id
				JOIN $this->tbl_param_offices		 		E  ON A.station 		= E.office_id
				
				WHERE $key = ?

				$filter_str
	        	$sOrder
	        	$sLimit
EOS;
	
			RLog::debug($query);
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

	public function filtered_length_employee_contract_record($aColumns, $bColumns, $params)
	{
		try
		{
			$this->get_employee_contract_record($aColumns, $bColumns, $params);
			
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
	
	
	public function total_length_employee_contract_record()
	{
		try
		{
			$where = array();
			
			$fields = array("COUNT(*) cnt");
			return $this->select_one($fields, $this->tbl_employee_contract_record, $where);
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

	public function get_employee_contract_record_info($id)
	{
		try
		{
			$val 	= array($id);
			$key 	= $this->get_hash_key('A.employee_id');
			$fields = "CONCAT(A.first_name, ' ', A.middle_name, '. ', A.last_name, ' ', A.ext_name) as name, A.agency_employee_id, A.employee_id, C.office_name";

			$query = <<<EOS
				SELECT $fields
				FROM 		$this->tbl_employee_personal_info 	 A
				LEFT JOIN 	$this->tbl_employee_offices B ON B.employee_id = A.employee_id
				LEFT JOIN 	$this->tbl_param_offices C ON C.office_id = B.office_id
				WHERE $key = ?
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

	public function get_param_position($id)
	{
		try
		{
			$condition_active_flag 	= "active_flag = 'Y'";
			$fields 				= "position_id, position_name";
			$query = <<<EOS
				SELECT $fields
				FROM $this->tbl_param_positions
				WHERE $condition_active_flag

		
EOS;
			RLog::debug($query);	
			$stmt = $this->query($query, array(), TRUE);
						
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
	
	public function get_param_employment_type($id)
	{
		try
		{
			$condition_active_flag 	= "active_flag = 'Y'";
			$fields 				= "employment_status_id, employment_status_name";
			$query = <<<EOS
				SELECT $fields
				FROM $this->tbl_param_employment_status
				WHERE $condition_active_flag
EOS;
			RLog::debug($query);	
			$stmt = $this->query($query, array(), True);
						
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

	public function get_param_office($id)
	{
		try
		{
			$condition_active_flag 	= "active_flag = 'Y'";
			$fields = "office_id, office_name, active_flag";

			$query = <<<EOS
				SELECT $fields
				FROM $this->tbl_param_offices
				WHERE $condition_active_flag
EOS;
			RLog::debug($query);		
			$stmt = $this->query($query, array(), True);
				
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

	public function get_param_branch($id)
	{
		try
		{
			$condition_active_flag 	= "active_flag = 'Y'";
			$fields 				= "branch_id, branch_name";
			$query = <<<EOS
				SELECT $fields
				FROM $this->tbl_param_branch
				WHERE $condition_active_flag
EOS;
			$stmt = $this->query($query, array(), True);
			
			RLog::debug($query);			
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

	public function get_param_leave_type($id)
	{
		try
		{
			$condition_active_flag 	= "active_flag = 'Y'";
			$fields 				= "leave_type_id, leave_type_name, active_flag";

			$query = <<<EOS
				SELECT $fields
				FROM $this->tbl_param_leave_types
				where $condition_active_flag
EOS;
	
			RLog::debug($query);
			$stmt = $this->query($query, array(), True);
						
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

	public function get_param_plantilla($aColumns, $bColumns, $params, $table, $where, $multiple = TRUE)
	{
		try
		{
			$val = array();
					
			$query = <<<EOS
				SELECT SQL_CALC_FOUND_ROWS *
				FROM $this->tbl_param_plantilla A
				JOIN $this->tbl_param_positions B ON A.position_id = B.position_id
				JOIN $this->tbl_param_designation C ON A.designation_id = C.designation_id
				JOIN $this->tbl_param_employment_status D ON A.employment_type_id = D.employment_status_id

EOS;
			RLog::debug($query);
			$val = array_merge($val);
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

	public function get_specific_param_plantilla($select_fields, $tables, $where)
	{
		try
		{


			$fields = (!empty($select_fields)) ? $select_fields : array("*");
			
			$stmt = $this->select_one($fields, $tables, $where);
			
		
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

}