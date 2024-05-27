<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Workflow_actions extends SYSAD_Controller {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('workflow_process_model', 'wp', TRUE);
		$this->load->model('workflow_steps_model', 'ws', TRUE);
		$this->load->model('workflow_actions_model', 'wa', TRUE);
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
			$data["steps"] = $this->ws->get_all_steps($process_id);
			$data["wp"] = $this->wp->get_workflow_process($process_id);
			$data["wa"] = $this->wa->get_workflow_actions($process_id);
			
			$data["ws"] = $this->ws->get_workflow_steps($process_id);
			$data["wstage"] = $this->wstage->get_workflow_stage($process_id);
			$data["status"] = $this->sp->get_requests_status();
			
			$resources['load_css'] = array(CSS_SELECTIZE, CSS_LABELAUTY);
			$resources['load_js'] = array(JS_SELECTIZE, JS_LABELAUTY);
			
			$this->load_resources->get_resource($resources);
			$this->load->view('tabs/workflow_actions', $data);
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
			$this->wa->save_workflow_actions($params);
			$msg = $this->lang->line('data_saved');
			
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