<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Workflow_steps extends SYSAD_Controller {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('workflow_process_model', 'wp', TRUE);
		$this->load->model('workflow_steps_model', 'ws', TRUE);
		$this->load->model('workflow_stages_model', 'wstage', TRUE);
		$this->load->model('sys_param_model', 'sp', TRUE);
	}
	
	public function tab($process_id)
	{
		try{
			
			$data = array();
			$resources = array();
			
			$data["process_id"] = $process_id;
			
			$process_id = base64_url_decode($process_id);
			$data["wp"] = $this->wp->get_workflow_process($process_id);
			$data["ws"] = $this->ws->get_workflow_steps($process_id);
			$data["wstage"] = $this->wstage->get_workflow_stage($process_id);
			$data["status"] = $this->sp->get_requests_status();
			
			$resources['load_css'] = array(CSS_SELECTIZE);
			$resources['load_js'] = array(JS_SELECTIZE);
			
			$this->load_resources->get_resource($resources);
			$this->load->view('tabs/workflow_steps', $data);
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
			
			// GET SECURITY VARIABLES
			$id	= (!EMPTY($params['id']))? base64_url_decode($params['id']) : "";
			$salt = $params['salt'];
			$token = $params['token'];
	
			// CHECK IF THE SECURITY VARIABLES WERE CORRUPTED OR INTENTIONALLY EDITED BY THE USER
			check_salt($id, $salt, $token);
			
			// BEGIN TRANSACTION
			SYSAD_Model::beginTransaction();
			
			$params["process_id"] = $id;
			$steps = $this->ws->get_workflow_steps($id);
			$steps_id = array();
			
			foreach ($steps as $step):
				$arr = (!EMPTY($step["steps_id"]))? explode(",",$step["steps_id"]) : array();
				$steps_id = array_merge($steps_id, $arr);
			endforeach;
				
			if(EMPTY($steps_id))
			{
				$this->ws->insert_workflow_steps($params);
				$msg = $this->lang->line('data_saved');
			}
			else
			{
				$this->ws->update_workflow_steps($params);
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
	
		echo json_encode($info);
	
	}

}