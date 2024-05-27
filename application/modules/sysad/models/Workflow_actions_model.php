<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Workflow_actions_model extends SYSAD_Model {
                
	var $stage_table = "process_stages";
	var $step_table = "process_steps";
	var $action_table = "process_actions";
	var $status_table = "param_status";
	var $sysad_db = DB_CORE;
	
	public function get_workflow_actions($process_id)
	{
		try
		{	
			$query = <<<EOS
				SELECT DISTINCT A.*, B.process_step_id steps_id, B.name steps_name, GROUP_CONCAT(DISTINCT D.status ORDER BY B.process_step_id) steps_status,
				GROUP_CONCAT(DISTINCT C.process_action_id ORDER BY C.process_action_id) actions_id, GROUP_CONCAT(DISTINCT C.name ORDER BY C.process_action_id) actions_name, 
				GROUP_CONCAT(DISTINCT IF(ISNULL(C.proceeding_step), 0, C.proceeding_step) ORDER BY C.process_action_id) proceeding_steps,
				GROUP_CONCAT(DISTINCT C.message ORDER BY C.process_action_id) message,
				GROUP_CONCAT(C.update_db_flag ORDER BY C.process_action_id) update_db_flag
				FROM $this->sysad_db.$this->stage_table A
				LEFT JOIN $this->sysad_db.$this->step_table B ON A.process_stage_id = B.process_stage_id AND A.process_id = B.process_id
				LEFT JOIN $this->sysad_db.$this->action_table C ON B.process_step_id = C.process_step_id AND B.process_id = C.process_id
				JOIN $this->sysad_db.$this->status_table D ON B.status_id = D.status_id
				WHERE A.process_id = ?
				GROUP BY B.process_step_id
				ORDER BY A.process_stage_id, B.process_step_id;
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
	
	public function save_workflow_actions($params)
	{
		try
		{
			$process_id = $params["process_id"];
			$this->_delete_workflow_actions($process_id);
			
			$actions = $this->get_workflow_actions($process_id);
			foreach ($actions as $action):
				$stage_id = $action["process_stage_id"];
				$steps = explode(",",$action["steps_id"]);
				
				if(!EMPTY($steps)){
					for($i=0; $i<count($steps); $i++){
						$step_id = $steps[$i];
						
						for($j=1; $j<=$params["action_cnt"][$stage_id][$step_id]; $j++){
							if(!EMPTY($params["action_name"][$stage_id][$step_id][$j])){
								$val = array();
								$val["process_id"] = $process_id;
								$val["process_step_id"] = $step_id;
								$val["name"] = $params["action_name"][$stage_id][$step_id][$j];
								$val["message"] = $params["message"][$stage_id][$step_id][$j];
								
								if(!EMPTY($params["step_id"][$stage_id][$step_id][$j]))
									$val["proceeding_step"] = $params["step_id"][$stage_id][$step_id][$j];
								
								if(ISSET($params["update_db_flag"][$stage_id][$step_id][$j]))
									$val["update_db_flag"] = $params["update_db_flag"][$stage_id][$step_id][$j];
								
								$this->insert_data($this->action_table, $val);
							}
						}
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
	
	private function _delete_workflow_actions($process_id)
	{
		try
		{	
			$where = array();
			
			$where["process_id"] = $process_id;
			$this->delete_data($this->action_table, $where);
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