<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Workflow_steps_model extends SYSAD_Model {
                
	var $stage_table = "process_stages";
	var $step_table = "process_steps";
	var $sysad_db = DB_CORE;
	
	public function get_all_steps($process_id)
	{
		try
		{	
			$query = <<<EOS
				SELECT A.*
				FROM $this->sysad_db.$this->step_table A
				JOIN $this->sysad_db.$this->stage_table B ON A.process_stage_id = B.process_stage_id AND A.process_id = B.process_id
				WHERE B.process_id = ?
				GROUP BY A.process_stage_id
				ORDER BY B.process_stage_id, A.process_step_id
EOS;

			$stmt = $this->query($query, array($process_id));
			
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
	
	public function get_workflow_steps($process_id)
	{
		try
		{	
			$query = <<<EOS
				SELECT A.*, GROUP_CONCAT(DISTINCT B.process_step_id ORDER BY B.process_step_id) steps_id, GROUP_CONCAT(DISTINCT B.name ORDER BY B.process_step_id SEPARATOR ', ') steps_name, GROUP_CONCAT(DISTINCT B.status_id ORDER BY B.process_step_id) steps_status
				FROM $this->sysad_db.$this->stage_table A
				LEFT JOIN $this->sysad_db.$this->step_table B ON A.process_stage_id = B.process_stage_id AND A.process_id = B.process_id
				WHERE A.process_id = ?
				GROUP BY A.process_stage_id
				ORDER BY A.process_stage_id
EOS;

			$stmt = $this->query($query, array($process_id));
			
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
	
	public function insert_workflow_steps($params)
	{
		try
		{
			$process_id = $params["process_id"];
			$steps = $this->get_workflow_steps($process_id);
			foreach ($steps as $step):
				$stage_id = $step["process_stage_id"];
				
				for($i=1; $i<=$params["step_cnt"][$stage_id]; $i++){
					if(!EMPTY($params["step_name"][$stage_id][$i])){
						$val = array();
						$val["process_stage_id"] = $stage_id;
						$val["process_id"] = $process_id;
						$val["name"] = $params["step_name"][$stage_id][$i];
						$val["status_id"] = $params["step_status"][$stage_id][$i];
						
						$this->insert_data($this->step_table, $val);
					}	
				}	
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
	
	public function update_workflow_steps($params)
	{
		try
		{
			$this->_delete_workflow_steps($params["process_id"]);
			$this->insert_workflow_steps($params);
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
	
	private function _delete_workflow_steps($process_id)
	{
		try
		{	
			$where = array();
			
			$where["process_id"] = $process_id;
			$this->delete_data($this->step_table, $where);
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