<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Employee_work_schedule_model extends Main_Model {
     
	public $db_core              = DB_CORE;
	public $tbl_organizations    = "organizations";
	public function get_work_schedule_list($aColumns, $bColumns, $params)
	{
		try
		{
			$user_pds_id  = $params['employee_id'];
			$key 	= $this->get_hash_key('A.employee_id');
			$val = array($user_pds_id );
			
			/* For Advanced Filters */
			$cColumns = array("A-start_date","A-end_date","B-work_schedule_name");
						
			$fields = str_replace(" , ", " ", implode(", ", $aColumns));
			
			$sWhere = $this->filtering($cColumns, $params, TRUE);
			$sOrder = $this->ordering($bColumns, $params);
			$sLimit = $this->paging($params);
			
			$filter_str = $sWhere["search_str"];
			$filter_params = $sWhere["search_params"];
			
			$query = <<<EOS
				SELECT SQL_CALC_FOUND_ROWS $fields 
				FROM $this->tbl_employee_work_schedules A
				JOIN $this->tbl_param_work_schedules B ON A.work_schedule_id = B.work_schedule_id
				WHERE $key  = ?
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
	public function work_schedule_filtered_length($aColumns, $bColumns, $params)
	{
		try
		{
			$this->get_work_schedule_list($aColumns, $bColumns, $params);
			
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
	public function work_schedule_total_length($employee_id)
	{
		try
		{

			$key         = $this->get_hash_key('employee_id');
			$val         = array($employee_id );
			

			$query = <<<EOS
				SELECT COUNT(*) cnt
				FROM $this->tbl_employee_work_schedules
				WHERE $key  = ?
				
EOS;

			$stmt = $this->query($query, $val, false);
						
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
	
}