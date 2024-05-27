<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Roles_model extends SYSAD_Model {
                
	var $role_table = "roles";
	var $user_roles_table = "user_roles";
	var $system_roles_table = "system_roles";
	var $system_table = "systems";
	
	public function __construct()
	{
		parent::__construct();
	}
	
	public function get_role_details($role_code)
	{
		try
		{
			$fields 			= array("role_code", "role_name");
			
			$where 				= array();
			$where["role_code"] = $role_code;
			
			return $this->select_one($fields, $this->role_table, $where);
		}
		catch(PDOException $e)
		{
			$this->rlog_error($e);
			
			throw $e;
		}
		catch(Exception $e)
		{
			$this->rlog_error($e);
			
			throw $e;			
		}	
	}	
	
	
	public function get_roles()
	{
		try
		{
			
			$fields = array("role_code", "role_name");
			
			$order_by 				= array();
			$order_by["role_name"]	= "ASC";
			
			return $this->select_all($fields, $this->role_table, NULL, $order_by);
		}
		catch(PDOException $e)
		{
			$this->rlog_error($e);
			
			throw $e;
		}
		catch(Exception $e)
		{
			$this->rlog_error($e);
			
			throw $e;			
		}				
	}
		
	public function get_system_roles($role_code)
	{
		try
		{
			
			$fields 			= array("*");
			
			$where 				= array();
			$where["role_code"] = $role_code;
			
			return $this->select_all($fields, $this->system_roles_table, $where);
		}
		catch(PDOException $e)
		{
			$this->rlog_error($e);
				
			throw $e;
		}
		catch(Exception $e)
		{
			$this->rlog_error($e);
				
			throw $e;
		}				
	}
	
	
	public function get_user_roles($user_id)
	{
		try
		{
				
			$fields = array("role_code");
			
			$where 				= array();
			$where["user_id"]	= $user_id;
			
			$order_by 				= array();
			$order_by["role_code"]	= "ASC";
			
			return $this->select_all($fields, $this->user_roles_table, $where, $order_by);
		}
		catch(PDOException $e)
		{
			$this->rlog_error($e);
			
			throw $e;
		}
		catch(Exception $e)
		{
			$this->rlog_error($e);
			
			throw $e;			
		}	
	}
	
	
	public function get_role_list($aColumns, $bColumns, $params)
	{
		try
		{
			if(!EMPTY($params['built_in_flag']))
				$params["IF(a-built_in_flag = 1, 'Yes', 'No')"] = $params['built_in_flag'];
			$cColumns = array("a-role_code", "a-role_name","IF(a-built_in_flag = 1, 'Yes', 'No')","c-system_name");
			
			$fields = str_replace(" , ", " ", implode(", ", $aColumns));
			
			$sWhere = $this->filtering($cColumns, $params, FALSE);
			$sOrder = $this->ordering($bColumns, $params);
			$sLimit = $this->paging($params);
			
			$filter_str = $sWhere["search_str"];
			$filter_params = $sWhere["search_params"];
		
			
			$query = <<<EOS
				SELECT SQL_CALC_FOUND_ROWS $fields 
				FROM $this->role_table a
				LEFT JOIN $this->system_roles_table b
				ON a.role_code = b.role_code
				LEFT JOIN $this->system_table c
				ON b.system_code = c.system_code
				$filter_str
				GROUP BY a.role_code
	        	$sOrder
	        	$sLimit
EOS;
			$stmt = $this->query($query, $filter_params);
		
			return $stmt;
		}
		catch(PDOException $e)
		{
			$this->rlog_error($e);
			
			throw $e;
		}
		catch(Exception $e)
		{
			$this->rlog_error($e);
			
			throw $e;			
		}	
	}	
	
	
	public function filtered_length($aColumns, $bColumns, $params)
	{
		try
		{
			$this->get_role_list($aColumns, $bColumns, $params);
	
			$query = <<<EOS
				SELECT FOUND_ROWS() cnt
EOS;
	
			$stmt = $this->query($query, NULL, FALSE);
			
			return $stmt;
		}
		catch(PDOException $e)
		{
			$this->rlog_error($e);
			
			throw $e;
		}
		catch(Exception $e)
		{
			$this->rlog_error($e);
			
			throw $e;			
		}	
	}
	
	
	public function total_length()
	{
		try
		{
			$fields = array("COUNT(role_code) cnt");
				
			return $this->select_one($fields, $this->role_table);
		}
		catch(PDOException $e)
		{
			$this->rlog_error($e);
			
			throw $e;
		}
		catch(Exception $e)
		{
			$this->rlog_error($e);
			
			throw $e;			
		}	
	}
	
	
	public function insert_role($params)
	{
		try
		{
			
			$role_code = filter_var($params['role_code'], FILTER_SANITIZE_STRING);
			
			$val 					= array();
			$val["role_code"] 		= $role_code;
			$val["role_name"] 		= filter_var($params['role_name'], FILTER_SANITIZE_STRING);
			$val["created_by"] 		= $this->session->userdata("user_id");
			$val["created_date"]	= date('Y-m-d H:i:s');
			
			$this->insert_data($this->role_table, $val);
			
			if(!EMPTY($role_code) && !EMPTY($params["system_role"]))
				$this->__insert_system_roles($params["system_role"], $role_code);
			
			return $role_code;
			
		}
		catch(PDOException $e)
		{
			$this->rlog_error($e);
			
			throw $e;
		}
		catch(Exception $e)
		{
			$this->rlog_error($e);
			
			throw $e;			
		}
	}
	
	
	public function update_role($params)
	{
		try
		{
			
			$val 				= array();
			$val["role_name"] 	= filter_var($params['role_name'], FILTER_SANITIZE_STRING);
			$val["modified_by"]	= $this->session->userdata("user_id");
			
			$where 				= array();
			$where["role_code"] = filter_var($params["id"], FILTER_SANITIZE_STRING);
			
			$this->update_data($this->role_table, $val, $where);
			
			if(!EMPTY($params["id"]) && !EMPTY($params["system_role"])){
				$this->delete_data($this->system_roles_table, $where);
				$this->__insert_system_roles($params["system_role"], $params["id"]);
			}
		}
		catch(PDOException $e)
		{
			$this->rlog_error($e);
			
			throw $e;
		}
		catch(Exception $e)
		{
			$this->rlog_error($e);
			
			throw $e;			
		}
	}
	
	public function delete_role($id)
	{
		try
		{
			$this->delete_data($this->role_table, array('role_code' => $id));				
		}
		catch(PDOException $e)
		{
			$this->rlog_error($e);
				
			throw $e;
		}
		catch(Exception $e)
		{
			$this->rlog_error($e);
				
			throw $e;
		}
	}
	
	private function __insert_system_roles($system_roles, $role_code)
	{
		try
		{
			foreach ($system_roles as $system_role):
			
				$params					= array();
				$params["system_code"] 	= filter_var($system_role, FILTER_SANITIZE_STRING);
				$params["role_code"] 	= filter_var($role_code, FILTER_SANITIZE_STRING);
				
				$this->insert_data($this->system_roles_table, $params);
			endforeach;
			
		}
		catch(PDOException $e)
		{
			$this->rlog_error($e);
				
			throw $e;
		}
		catch(Exception $e)
		{
			$this->rlog_error($e);
				
			throw $e;
		}
	}
	
	public function get_users_count($id)
	{
		try
		{	
			$fields = array("COUNT(*) cnt");
			$where	= array("role_code" => $id);
				
			return $this->select_one($fields, $this->user_roles_table, $where);
	
		}
		catch(PDOException $e)
		{
			$this->rlog_error($e);
				
			throw $e;
		}
		catch(Exception $e)
		{
			$this->rlog_error($e);
				
			throw $e;
		}
	}
	
}