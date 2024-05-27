<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Roles extends SYSAD_Controller {
	
	private $module = MODULE_ROLE;
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('roles_model', 'roles');
		$this->load->model('systems_model', 'systems');
	}
	
	public function index()
	{	
		$data = $resources = array();
		
		$resources['load_css'] = array(CSS_DATATABLE, CSS_MODAL_COMPONENT, CSS_SELECTIZE);
		$resources['load_js'] = array(JS_DATATABLE, JS_SELECTIZE,'tableExport','jquery.base64','html2canvas','jspdf/libs/sprintf','jspdf/jspdf','jspdf/libs/base64');
		$resources['datatable'] = array('table_id' => 'role_table', 'path' => 'sysad/roles/get_role_list','advanced_filter' => true);
		/*BREADCRUMBS*/
		$breadcrumbs 			= array();
		$key					= "User Management"; 
		$breadcrumbs[$key]		= PROJECT_CORE."/roles";
		$key					= "Roles"; 
		$breadcrumbs[$key]		= PROJECT_CORE."/roles";
		set_breadcrumbs($breadcrumbs, TRUE);
		$this->template->load('roles', $data, $resources);
	}
	
	
	public function get_role_list()
	{
		$params = get_params();
		$cnt = 0;
	
		$aColumns = array("a.role_code", "a.role_name", "IF(a.built_in_flag = 1, 'Yes', 'No') built_in", "GROUP_CONCAT(c.system_name SEPARATOR '<br>') systems");
		$bColumns = array("role_code", "role_name", "built_in", "systems");
	
		$roles = $this->roles->get_role_list($aColumns, $bColumns, $params);
		$iTotal = $this->roles->total_length();
		$iFilteredTotal = $this->roles->filtered_length($aColumns, $bColumns, $params);
	
		$output = array(
			"sEcho" => intval($_POST['sEcho']),
			"iTotalRecords" => $iTotal["cnt"],
			"iTotalDisplayRecords" => $iFilteredTotal["cnt"],
			"aaData" => array()
		);
	
		foreach ($roles as $aRow):
			$cnt++;
			$row = array();
			$action = "";
		
			$role_code = $aRow["role_code"];
			$id = base64_url_encode($role_code);
			$salt = gen_salt();
			$token = in_salt($role_code, $salt);			
			$url = $id."/".$salt."/".$token;
			
			$users_cnt = $this->roles->get_users_count($role_code);
			$ctr = $users_cnt['cnt'];
			
			$delete_action = ($ctr > 0) ? 'alert_msg("'.ERROR.'", "'.$this->lang->line("parent_delete_error").'")' : 'content_delete("role", "'.$id.'")';
			
			for ( $i=0 ; $i<count($bColumns) ; $i++ )
			{
				$row[] = $aRow[ $bColumns[$i] ];
			}
			
			$action = "<div class='table-actions'>";

			if($aRow["built_in"] == "No"){
				//if(($this->permission->check_permission(MODULE_ROLE, ACTION_EDIT))) :
					$action .= "<a href='javascript:;' class='md-trigger edit tooltipped' data-tooltip='Edit' data-position='bottom' data-delay='50' data-modal='modal_roles' onclick=\"modal_init('".$url."')\"></a>";
				//endif;

				//if (($this->permission->check_permission(MODULE_ROLE, ACTION_DELETE))) : 	  
					$action .= "<a href='javascript:;' onclick='" . $delete_action. "' class='delete tooltipped' data-tooltip='Delete' data-position='bottom' data-delay='50'></a>";
				//endif;
			}
			$action .= '</div>';
			
			if($cnt == count($roles)){
				$action.= "<script src='". base_url() . PATH_JS."modalEffects.js' type='text/javascript'></script>";
				$action.= "<script src='". base_url() . PATH_JS."classie.js' type='text/javascript'></script>";
				$action.= "<script>$(function(){ $('.tooltipped').tooltip({delay: 50});	});</script>";
			}
				
			$row[] = $action;
				
			$output['aaData'][] = $row;
		endforeach;
	
		echo json_encode( $output );
	}
	
	
	public function modal($id = NULL, $salt = NULL, $token = NULL){
		
		try{
			$data = array();
			$resources = array();
			
			if(!IS_NULL($id)){
				$id = base64_url_decode($id);
				
				// CHECK IF THE SECURITY VARIABLES WERE CORRUPTED OR INTENTIONALLY EDITED BY THE USER
				check_salt($id, $salt, $token);
				
				$data["role"] = $this->roles->get_role_details($id);
				$system_roles = $this->roles->get_system_roles($id);
				
				if($system_roles){
					for ($i=0; $i<count($system_roles); $i++){
						$system_arr[] = $system_roles[$i]["system_code"]; 
					}
					$resources['multiple']['system_role'] = $system_arr;
				}
				
			}
			$data["systems"] = $this->systems->get_systems();
			$resources['load_css'] = array(CSS_SELECTIZE);
			$resources['load_js'] = array(JS_SELECTIZE, 'popModal.min');
			
			$this->load->view("modals/roles", $data);
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
				
			// SERVER VALIDATION
			$this->_validate($params, $action);
			
			// GET SECURITY VARIABLES
			$id	= filter_var($params['id'], FILTER_SANITIZE_STRING);
			$salt = $params['salt'];
			$token = $params['token'];
	
			// CHECK IF THE SECURITY VARIABLES WERE CORRUPTED OR INTENTIONALLY EDITED BY THE USER
			check_salt($id, $salt, $token);
			
			// BEGIN TRANSACTION
			SYSAD_Model::beginTransaction();
			
			$audit_table[]	= $this->roles->tbl_roles;
			$audit_schema[]	= Base_Model::$schema_core;
				
			if(EMPTY($id))
			{
				$audit_action[]	= AUDIT_INSERT;
				
				$prev_detail[]	= array();
				
				$id = $this->roles->insert_role($params);
				$msg = $this->lang->line('data_saved');
				
				// GET THE DETAIL AFTER INSERTING THE RECORD
				$curr_detail[] = array($this->roles->get_role_details($id));	
				
				// ACTIVITY TO BE LOGGED ON THE AUDIT TRAIL
				$activity = "%s has been added";
			}
			else
			{
				$audit_action[]	= AUDIT_UPDATE;
				
				// GET THE DETAIL FIRST BEFORE UPDATING THE RECORD
				$prev_detail[] = array($this->roles->get_role_details($id));
				
				$this->roles->update_role($params);
				$msg = $this->lang->line('data_updated');
				
				// GET THE DETAIL AFTER UPDATING THE RECORD
				$curr_detail[] = array($this->roles->get_role_details($id));
				
				// ACTIVITY TO BE LOGGED ON THE AUDIT TRAIL
				$activity = "%s has been updated";
				
			}
			
			$activity = sprintf($activity, $params['role_name']);
	
			// LOG AUDIT TRAIL
			$this->audit_trail->log_audit_trail(
				$activity, 
				$this->module, 
				$prev_detail, 
				$curr_detail, 
				$audit_action, 
				$audit_table,
				$audit_schema
			);
			
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
	
	
	private function _validate($params, $action = NULL)
	{
		if($action == AUDIT_INSERT){
			if(EMPTY($params['role_code']))
				throw new Exception('Role code is required.');
		}	
			
		if(EMPTY($params['role_name']))
			throw new Exception('Role is required.');	
	}
	
	public function delete_role($id = NULL, $salt = NULL, $token = NULL)
	{
		try
		{
			$flag = 0;
			$params	= get_params();
				
			$action = AUDIT_DELETE;
	
			// CHECK IF THE SECURITY VARIABLES WERE CORRUPTED OR INTENTIONALLY EDITED BY THE USER
			$id = base64_url_decode($params['param_1']);
				
			// BEGIN TRANSACTION
			SYSAD_Model::beginTransaction();
			
			$audit_action[]	= AUDIT_DELETE;
			$audit_table[]	= $this->roles->tbl_roles;
			$audit_schema[]	= Base_Model::$schema_core;
	
			// GET THE DETAIL FIRST BEFORE UPDATING THE RECORD
			$prev_detail[] = array($this->roles->get_role_details($id));
			
			$this->roles->delete_role($id);
			$msg = $this->lang->line('data_deleted');
				
			// GET THE DETAIL AFTER UPDATING THE RECORD
			$curr_detail[] = array($this->roles->get_role_details($id));
				
			// ACTIVITY TO BE LOGGED ON THE AUDIT TRAIL
			$activity = "%s has been deleted";
			$activity = sprintf($activity, $prev_detail[0][0]['role_name']);
	
			// LOG AUDIT TRAIL
			$this->audit_trail->log_audit_trail(
				$activity, 
				$this->module, 
				$prev_detail, 
				$curr_detail, 
				$audit_action, 
				$audit_table,
				$audit_schema
			);
				
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
			"flag" 		=> $flag,
			"msg" 		=> $msg,
			"reload" 	=> 'datatable',
			"table_id" 	=> 'role_table',
			"path"		=> PROJECT_CORE . '/roles/get_role_list/'
		);
	
		echo json_encode($info);
	}
	
}


/* End of file users.php */
/* Location: ./application/modules/sysad/controllers/users.php */