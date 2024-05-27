<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sys_param_model extends SYSAD_Model {
	
	var $sys_param_table = "sys_param";
	var $status_table = "param_status";
	var $param_request_status_table = "param_request_status";

	public $db_main		= DB_MAIN;
	public function get_sys_param($params)
	{
		try
		{		
			if(!EMPTY($params["where"])){	
				$where = array();
				foreach ($params["where"] as $k => $v):
					$where[$k] = $v;
				endforeach;
			}
			
			if($params["multiple"])
				return $this->select_all($params["fields"], $this->sys_param_table, $where);
			else
				return $this->select_one($params["fields"], $this->sys_param_table, $where);
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
	
	public function get_param_status()
	{
		try
		{		
			return $this->select_all(array('*'), $this->status_table);
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
	public function get_requests_status()
	{
		try
		{		
		
			$table = $this->db_main.".".$this->param_request_status_table;
			return $this->select_all(array('request_status_id as status_id','request_status_name as status'), $table);
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


/**
 * End of file : sys_param_model.php
 * Location : application/modules/sysad/models
 */