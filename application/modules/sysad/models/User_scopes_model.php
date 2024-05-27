<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_scopes_model extends SYSAD_Model {

	public $db_core           = DB_CORE;
	public $db_main           = DB_MAIN;
	public $tbl_users         = "users";
	public $tbl_user_offices  = "user_offices";
	public $tbl_modules       = "modules";
	public $tbl_systems       = "systems";
	public $tbl_organizations = "organizations";
	public $tbl_param_offices = "param_offices";
	public $tbl_user_roles    = "user_roles";

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

	public function delete_user_scopes($user_id, $system_code)
	{
		try
		{			
			$db = static::get_connection();
			$key = $this->get_hash_key('A.user_id');
			
			$where = "";
			$val = array();
			$val[] = $user_id;
			
			if($system_code != 'all'){
				$where = " AND B.system_code = ? ";
				$val[] = $system_code;
			}
			
			$query = <<<EOS
				DELETE A FROM $this->tbl_user_offices A 
				JOIN $this->tbl_modules B ON A.module_id = B.module_id
				WHERE $key = ?
				$where
EOS;
			self::rlog_info('QUERY ' . $query);
			self::rlog_info('VALUES ' . var_export($val, TRUE));
			
			$stmt = $db->prepare($query);
			$stmt->execute($val);
			
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

	public function get_permission_access($module_id, $button_action = NULL)
	{
		try
		{
			$where = "";
			$val = array($module_id);
			
			if(!IS_NULL($button_action)){
				$where = " AND B.action_id = ?";
				$val[] = $button_action;
			}
				
			$query = <<<EOS
				SELECT A.role_code 
				FROM module_action_roles A
				JOIN module_actions B ON A.module_action_id = B.module_action_id 
				WHERE  B.module_id = ? 
				$where
EOS;
					
			$stmt = $this->query($query, $val);
			
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
}