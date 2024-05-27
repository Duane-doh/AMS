<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Site_settings extends SYSAD_Controller {

	private $module = MODULE_SITE_SETTINGS;
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('site_settings_model', 'settings', TRUE);
	}
	
	public function index()
	{
		try{
			$resources = array();

			$resources['load_css'] = array(CSS_LABELAUTY, CSS_UPLOAD);
			$resources['load_js'] = array(JS_LABELAUTY, JS_UPLOAD);
			$resources['upload'] = array(
				array('id' => 'system_logo', 'path' => PATH_SETTINGS_UPLOADS, 'allowed_types' => 'jpeg,jpg,png,gif', 'default_img_preview' => 'image_preview.png', 'page' => 'site_settings'),
				array('id' => 'system_favicon', 'path' => PATH_SETTINGS_UPLOADS, 'allowed_types' => 'ico', 'default_img_preview' => 'default_favicon.png', 'page' => 'site_settings')
			);
			
			$this->load->view('tabs/site_settings');
			$this->load_resources->get_resource($resources);
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
			
			// BEGIN TRANSACTION
			SYSAD_Model::beginTransaction();
			
			$fields = $this->settings->get_site_settings(SITE_APPEARANCE);
			
			foreach($fields as $field):
			
				$audit_action[]	= AUDIT_UPDATE;
				$audit_table[]	= $this->settings->tbl_site_settings;
				$audit_schema[]	= Base_Model::$schema_core;
				
			
				// GET THE DETAIL FIRST BEFORE UPDATING THE RECORD
				$prev_detail[] = $this->settings->get_site_settings(SITE_APPEARANCE, $field['setting_type'], $field['setting_name']);
				  
				$this->settings->update_settings($field['setting_type'], $params, $field['setting_name']);
					 
				// GET THE DETAIL AFTER UPDATING THE RECORD
				$curr_detail[] = $this->settings->get_site_settings(SITE_APPEARANCE, $field['setting_type'], $field['setting_name']);			
			endforeach;
			
			// ACTIVITY TO BE LOGGED ON THE AUDIT TRAIL
			$activity = "%s has been updated";
			$activity = sprintf($activity, "Site settings");
			
			$this->audit_trail->log_audit_trail(
				$activity,
				$this->module,
				$prev_detail,
				$curr_detail,
				$audit_action,
				$audit_table,
				$audit_schema
			);
				
			
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


/* End of file Site_settings.php */
/* Location: ./application/modules/budget/controllers/Site_settings.php */