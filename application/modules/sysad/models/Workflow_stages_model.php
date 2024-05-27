<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Workflow_stages_model extends SYSAD_Model {
                
	var $stage_table = "process_stages";
	var	$stage_roles_table = "process_stage_roles";
	var	$roles_table = "roles";
	var $sysad_db = DB_CORE;
	
	public function get_workflow_stage($process_id)
	{
		try
		{	
			$query = <<<EOS
				SELECT A.*, GROUP_CONCAT(DISTINCT B.role_code) roles, GROUP_CONCAT(DISTINCT C.role_name SEPARATOR ', ') role_name
				FROM $this->sysad_db.$this->stage_table A
				JOIN $this->sysad_db.$this->stage_roles_table B ON A.process_stage_id = B.process_stage_id AND B.process_id = ?
				JOIN $this->sysad_db.$this->roles_table C ON B.role_code = C.role_code
				WHERE A.process_id = ?
				GROUP BY A.process_stage_id
				ORDER BY A.process_stage_id;
EOS;

			$stmt = $this->query($query, array($process_id, $process_id));
			
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
	
	public function save_workflow_stages($params)
	{
		try
		{
			$process_id = $params["process_id"];
			
			$this->_delete_stages($process_id);
			$this->_delete_stage_roles($process_id);
			
			for($i=1; $i<=$params["num_stages"]; $i++){
				$val = array();
				$val["name"] = $params["stage_name"][$i];
				$val["process_id"] = $process_id;
				$val["process_stage_id"] = 0;
				$this->insert_data($this->stage_table, $val);
				$this->_insert_stage_roles($params["stage_role"][$i], $i, $process_id);
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
	
	private function _insert_stage_roles($stage_roles, $process_stage_id, $process_id)
	{
		try
		{
			foreach ($stage_roles as $role):
				$val = array();
				$val["process_id"] = $process_id;
				$val["process_stage_id"] = $process_stage_id;
				$val["role_code"] = $role;
				
				$this->insert_data($this->stage_roles_table, $val);
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
	
	private function _delete_stages($process_id)
	{
		try
		{
			$where = array();
			
			$where['process_id'] = $process_id;
				
			$this->delete_data($this->stage_table, $where);
				
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
	
	private function _delete_stage_roles($process_id)
	{
		try
		{
			$where = array();
			
			$where['process_id'] = $process_id;
				
			$this->delete_data($this->stage_roles_table, $where);
				
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