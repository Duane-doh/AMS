<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Workflow_process extends SYSAD_Controller {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('workflow_process_model', 'wp', TRUE);
	}
	
	public function tab($process_id)
	{
		try{
			$data = array();
			
			$data["process_id"] = $process_id;
			$process_id = base64_url_decode($process_id);
			$data["wp"] = $this->wp->get_workflow_process($process_id);
			
			$this->load->view("tabs/workflow_process", $data);
		}
		catch(Exception $e)
		{
			echo $e->getMessage();
		}
	}
	
	public function process()
	{
		try
		{
			$flag = 0;
			$params	= get_params();
			
			// SERVER VALIDATION
			$this->_validate($params);
			
			// GET SECURITY VARIABLES
			$id	= (!EMPTY($params['id']))? base64_url_decode($params['id']) : "";
			$salt = $params['salt'];
			$token = $params['token'];
	
			// CHECK IF THE SECURITY VARIABLES WERE CORRUPTED OR INTENTIONALLY EDITED BY THE USER
			check_salt($id, $salt, $token);
	
			// BEGIN TRANSACTION
			SYSAD_Model::beginTransaction();
				
			if(EMPTY($id))
			{
				$process_id = $this->wp->insert_workflow_process($params);
				$process_id = base64_url_encode($process_id);
				$msg = $this->lang->line('data_saved');
			}
			else
			{
				$params["process_id"] = $id;
				$this->wp->update_workflow_process($params);
				$msg = $this->lang->line('data_updated');
			}
			
			SYSAD_Model::commit();
			$flag = 1;
			
		}
		catch(PDOException $e)
		{
			SYSAD_Model::rollback();

			$msg = $this->rlog_error($e, TRUE);
		}
		catch(Exception $e)
		{
			SYSAD_Model::rollback();
			
			$msg = $this->rlog_error($e, TRUE);
		}
	
		$info = array(
			"flag" => $flag,
			"msg" => $msg
		);
		
		if(ISSET($process_id))
			$info["process_id"] = $process_id;
	
		echo json_encode($info);
	
	}
	
	public function delete_workflow_process()
	{
		try
		{
			$flag = 0;
			$params	= get_params();
	
			// BEGIN TRANSACTION
			SYSAD_Model::beginTransaction();
	
			$this->wp->delete_workflow_process($params["process_id"]);
			$msg = $this->lang->line('data_deleted');
	
			SYSAD_Model::commit();
			$flag = 1;
	
		}
		catch(PDOException $e)
		{
			SYSAD_Model::rollback();

			$msg = $this->rlog_error($e, TRUE);
		}
		catch(Exception $e)
		{
			SYSAD_Model::rollback();
			
			$msg = $this->rlog_error($e, TRUE);
		}
	
		$info = array(
			"flag" => $flag,
			"msg" => $msg
		);
	
		echo json_encode($info);
	
	}
	
	private function _validate($params)
	{
		if(EMPTY($params["process_name"]))
			throw new Exception("Process name is required.");	
		if(EMPTY($params["num_stages"]))
			throw new Exception("Number of stages is required.");	
		if(EMPTY($params["process_desc"]))
			throw new Exception("Process description is required.");	

	}
}