<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Workflow_stages extends SYSAD_Controller {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('roles_model', 'rm', TRUE);
		$this->load->model('workflow_process_model', 'wp', TRUE);
		$this->load->model('workflow_stages_model', 'ws', TRUE);
	}
	
	public function tab($process_id)
	{
		try{
			$data = array();
			$resources = array();
			
			$data["process_id"] = $process_id;

			$resources['load_css'] = array(CSS_SELECTIZE);
			$resources['load_js'] = array(JS_SELECTIZE);
			
			$data["roles"] = $this->rm->get_roles();
			
			$process_id = base64_url_decode($process_id);
			$data["wp"] = $this->wp->get_workflow_process($process_id);
			$data["ws"] = $this->ws->get_workflow_stage($process_id);
			
			$this->load_resources->get_resource($resources);
			$this->load->view('tabs/workflow_stages', $data);
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
			
			$params["process_id"] = $id;
			$this->ws->save_workflow_stages($params);
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
	
	private function _validate($params)
	{
		if(EMPTY($params["num_stages"]))
			throw new Exception("Number of stages defined in a process is required.");	
		
		for($i=1; $i<=$params["num_stages"]; $i++){
			if(EMPTY($params["stage_name"][$i]))
				throw new Exception("Name of stage ".$i." is required.");	
			if(EMPTY($params["stage_role"][$i]))
				throw new Exception("Roles for stage ".$i." is required.");	
		}
	}
}