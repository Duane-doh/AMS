<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Workflow_process_model extends SYSAD_Model {
                
	var $process_table = "process";
	
	public function get_workflow_process($process_id)
	{
		try
		{	
			$where = array();
			
			$fields = array("*");
			$where['process_id'] = $process_id;
			
			return $this->select_one($fields, $this->process_table, $where);
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
	
	public function insert_workflow_process($params)
	{
		try
		{
			$val = array();
			
			$val["name"] = $params["process_name"];
			$val["description"] = $params["process_desc"];
			$val["num_stages"] = $params["num_stages"];
			$val["created_by"] = $this->session->user_id;
			$val["created_date"] = date('Y-m-d H:i:s');
			
			return $this->insert_data($this->process_table, $val, TRUE);
			
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
	
	public function update_workflow_process($params)
	{
		try
		{
			$val = array();
			$where = array();
			
			$val["name"] = $params["process_name"];
			$val["description"] = $params["process_desc"];
			$val["num_stages"] = $params["num_stages"];
			$val["modified_by"] = $this->session->user_id;
			$val["modified_date"] = date('Y-m-d H:i:s');
			
			$where["process_id"] = $params["process_id"];
			
			$this->update_data($this->process_table, $val, $where);
			
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
	
	public function delete_workflow_process($process_id)
	{
		try
		{
			$where = array();
				
			$where['process_id'] = $process_id;
				
			$this->delete_data($this->process_table, $where);
				
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