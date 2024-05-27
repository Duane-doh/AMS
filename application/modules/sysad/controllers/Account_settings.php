<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Account_settings extends SYSAD_Controller {

	private $module = MODULE_SYSTEM_ACCOUNT_SETTINGS;
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('site_settings_model', 'settings', TRUE);
	}
	
	public function index()
	{
		try{
			$resources = array();

			$resources['load_css'] = array(CSS_LABELAUTY);
			$resources['load_js'] = array(JS_LABELAUTY);
			
			$this->template->load('tabs/account_settings', $data, $resources);
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
			$action = AUDIT_UPDATE;
			// SERVER VALIDATION
			// $this->_validate($params);
	
			// BEGIN TRANSACTION
			SYSAD_Model::beginTransaction();
			
			$fields = $this->settings->get_site_settings(AUTHENTICATION);
			foreach($fields as $field):
			
			
				// $audit_action[]	= AUDIT_INSERT;
				// $audit_table[]	= $this->settings->tbl_site_settings;
				// $audit_schema[]	= Base_Model::$schema_core;
			
				// GET THE DETAIL FIRST BEFORE UPDATING THE RECORD
			  	// $prev_detail[] = $this->settings->get_site_settings(AUTHENTICATION, $field['setting_type'], $field['setting_name']);
			  	$this->settings->update_settings($field['setting_type'], $params, $field['setting_name']);
					 
				// GET THE DETAIL AFTER UPDATING THE RECORD
				// $curr_detail[] = $this->settings->get_site_settings(AUTHENTICATION, $field['setting_type'], $field['setting_name']);
			
			endforeach;
			
			// ACTIVITY TO BE LOGGED ON THE AUDIT TRAIL
			// $activity = "%s has been updated";
			// $activity = sprintf($activity, "Settings");
			
			// LOG AUDIT TRAIL
			// die;
			// $this->audit_trail->log_audit_trail(
			// 	$activity,
			// 	$this->module,
			// 	$prev_detail,
			// 	$curr_detail,
			// 	$audit_action,
			// 	$audit_table,
			// 	$audit_schema
			// );			
			
			$msg = $this->lang->line('data_updated');
			
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