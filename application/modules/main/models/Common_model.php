<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Common_model extends Main_Model {
	
	public $db_core   = DB_CORE;
	
	public function __construct() {
		parent:: __construct();
	
	}	
	
	/**
	 * 
	 * This is a helper function that retrieves data based on given parameters.
	 * @param array $fields
	 * @param array $table
	 * @param array $where
	 * @param boolean $multiple
	 * @param array $order_by
	 * @param array $group_by
	 * @param string $limit
	 */
	public function get_general_data($fields, $table, $where = array(), $multiple = TRUE, $order_by = array(), $group_by = array(), $limit = NULL)
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
	
	/**
	 * 
	 * This is a helper function that retrieves data based on given parameters.
	 * @param array $fields
	 * @param array $table
	 * @param array $where
	 * @param boolean $multiple
	 * @param array $order_by
	 * @param array $group_by
	 * @param string $limit
	 */
	public function get_general_data_group_concat($fields, $table, $where = array(), $multiple = TRUE, $order_by = array(), $group_by = array(), $limit = NULL)
	{
		try
		{
			//TODO: SET SESSION FOR GROUP_CONCAT_MAX_LEN
			// $stmt = $this->query("SET SESSION group_concat_max_len = 10240", NULL, NULL); //jendaigo: update value
			$stmt = $this->query("SET SESSION group_concat_max_len = ".GROUP_CONCAT_MAX_LENGTH, NULL, NULL);
						
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
	
	/** 
	* This function inserts record to given table. 
	* 
	* @access public 
	* @param mixed $table Table where the record is to be saved
	* @param array $fields Field values
	* @param array $return_id 
	* @return 
	*/
	public function insert_general_data($table, $fields, $return_id = FALSE,  $on_dup_update = FALSE, $id = FALSE)
	{
		try
		{
			return $this->insert_data($table, $fields, $return_id, $on_dup_update, $id);

		}
		catch(PDOException $e)
		{
			RLog::error($e->getMessage());
			throw $e;
		}
	}

	/** 
	* This function updates specified record. 
	* 
	* @access public 
	* @param mixed $tables Table where the record is to be saved
	* @param array $params
	* @param array $where 
	* @return 
	*/
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
	
	/** 
	* This function deletes specified record. 
	* 
	* @access public 
	* @param mixed $tables Table where the record is to be saved
	* @param array $where 
	* @return 
	*/	
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

	/** 
	* This function returns the number of records.
	* 
	* @access public 
	* @return integer Number of records found
	*/
	public function get_total_length($table, $field, $where)
	{
		try
		{
			$fields = array(" COUNT(DISTINCT $field) cnt ");
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

	
	/**
	 * This function returns the sys_param value based on given sys_param_type/
	 * @param $sys_param_type string or array
	 */
	public function get_sys_param_value($sys_param_type, $multiple=FALSE)
	{
		try {
			$field                     	= array('sys_param_type', 'sys_param_value');
			$table                     	= DB_CORE.'.'.$this->tbl_sys_param;
			if (is_array($sys_param_type))
				$where['sys_param_type']= array($sys_param_type, array('IN'));
			else
				$where['sys_param_type']= $sys_param_type;
			
			$where['active_flag']    	= YES;
			$sys_params					= $this->get_general_data($field, $table, $where, $multiple);
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
		
		return $sys_params;
	}
	
	/**
	 * This function returns the office ID of the employee
	 * @param $employee_id string Unique ID of the employee
	 * @param $hash_employee_id boolean TRUE if the given $employee_id is hashed 
	 */	
	public function get_employee_office($employee_id, $hash_employee_id=FALSE,$module_id = NULL)
	{
		try {

			$field                  = array('employ_office_id office_id');
			$table                  = $this->tbl_employee_work_experiences;

			if(!EMPTY($module_id))
			{
				$where       = array();
				$where['module_id'] = $module_id;
				$employee_office = $this->get_general_data(array('use_admin_office'), DB_CORE.'.'.$this->tbl_modules, $where, FALSE);
				if($employee_office['use_admin_office'] > 0)
				{
					$field                  = array('admin_office_id office_id');
				}
			}
			
			if ($hash_employee_id)
			{
				$where       = array();
				$key         = $this->get_hash_key('employee_id');
				$where[$key] = $employee_id;
			}
			else
			{
				$where['employee_id']	= $employee_id;
			}
			$where['active_flag']   = YES;
			
			$employee_office = $this->get_general_data($field, $table, $where, FALSE);
			
			RLog::info($employee_office);
			
			return $employee_office;
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
	
	public function get_sys_param_by_type($sys_param_types)
	{
		try 
		{
			$fields 	= array('sys_param_type', 'GROUP_CONCAT(sys_param_value) sys_param_value');
			$table		= $this->db_core . '.' . $this->tbl_sys_param;

			$where						= array();
			$where['sys_param_type'] 	= array($sys_param_types, array('IN'));
			$group_by					= array('sys_param_type');

			return $this->select_all($fields, $table, $where, array(), $group_by);
		}
		catch (Exception $e)
		{
			RLog::error($e->getMessage());
			throw $e;
		}
	}	

	public function get_report_signatories($module, $signatory_type_flag = null )
	{
		try 
		{
			$fields                     	= array('report_signatory_id', 'signatory_name');
			$table                     		= $this->tbl_param_report_signatories;
			$where 							= array();
			$where['sys_code_flags']		= array( '%' . $module . '%', array("LIKE"));
			if($signatory_type_flag)
			{
				$where['signatory_type_flags']	= array( '%' . $signatory_type_flag . '%', array("LIKE"));
			}
			$signatories					= $this->get_general_data($fields, $table, $where, TRUE);
		}
		catch (PDOException $e)
		{
			$this->rlog_error($e);
		}
		catch (Exception $e)
		{			
			$this->rlog_error($e);
		}
		
		return $signatories;
	}

	public function get_report_signatory_details($report_signatory_id)
	{
		try 
		{
			$fields                     	= array('report_signatory_id', 'signatory_name', 'position_name', 'office_name');
			$table                     		= $this->tbl_param_report_signatories;
			$where 							= array();
			$where['report_signatory_id']	= $report_signatory_id;
			$signatories					= $this->get_general_data($fields, $table, $where, FALSE);
		}
		catch (PDOException $e)
		{
			$this->rlog_error($e);
		}
		catch (Exception $e)
		{			
			$this->rlog_error($e);
		}
		
		return $signatories;
	}
	
	
}