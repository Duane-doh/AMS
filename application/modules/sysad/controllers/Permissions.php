<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Permissions extends SYSAD_Controller {
	
	private $module = MODULE_PERMISSION;
	
	public function __construct()
	{
		parent::__construct();
		
		$this->load->model('roles_model', 'rm', TRUE);
		$this->load->model('systems_model', 'sm', TRUE);
		$this->load->model('permissions_model', 'pm', TRUE);
	}
	
	public function index()
	{	
		$data = $resources = array();
		
		$data['roles']   	   = $this->rm->get_roles();
		$data['systems'] 	   = $this->sm->get_systems();
		$resources['load_css'] = array(CSS_SELECTIZE);
		$resources['load_js']  = array(JS_SELECTIZE);
		/*BREADCRUMBS*/
		$breadcrumbs 			= array();
		$key					= "User Management"; 
		$breadcrumbs[$key]		= PROJECT_CORE."/permissions";
		$key					= "Permissions"; 
		$breadcrumbs[$key]		= PROJECT_CORE."/permissions";
		set_breadcrumbs($breadcrumbs, TRUE);
		$this->template->load('permissions', $data, $resources);
	}
	
	
	public function get_permission()
	{
		try
		{
			$html    = '';
			$params  = get_params();
			$role    = $params['role'];
			$system  = $params['system'];

			$modules     = $this->pm->get_module_action_scopes($system);
			
			/* GET ROLE ACTIONS */
			$action      = $this->pm->get_role_action($role, true, $system);
			
			/* GET ROLE SCOPE */
			$scope       = $this->pm->get_role_scope($role, true);
		
			$role_action = explode(',', $action['module_action_id']); 
			
			$header      = '';
			$indent      = 0;
			$curr_parent = 0;
			foreach($modules as $key => $val):

				if($header != $val['system_name']):
					$html .= '<tr style="background: #6F7D95;">';
						$html .= '<td colspan="4">';
							$html .= '<p class="white-text font-xs font-semibold text-uppercase m-n">'.$val['system_name'].'</p>';
						$html .= '</td>';
						
						$header = $val['system_name'];
					$html .= '</tr>';
				endif;
				
				/*if( EMPTY($val['parent_module_id']) ):
					$indent 	 = 0;
					$curr_parent = $val['parent_module_id'];
				else:
					$indent      = ($curr_parent != $val['parent_module_id']) ? $indent + 20 : $indent;
					$curr_parent = ($curr_parent != $val['parent_module_id']) ? $val['parent_module_id'] : $curr_parent;
				endif;*/
				$indent = $val['level'] * 30;
				$avail_action_id   = explode(',', $val['available_action_per_module']);
				$avail_action_name = explode(',', $val['action_name']);
				$avail_scope_id    = explode(',', $val['available_scope_per_module']);
				$avail_scope_name  = explode(',', $val['scope_name']);
				
				
				$action_arr = $this->_construct_action($val['module_id'], $role_action, $avail_action_id, $avail_action_name);
				$scope_html = ( ! EMPTY($val['available_scope_per_module'])) ?  $this->_construct_scope($val['module_id'], $scope, $avail_scope_id, $avail_scope_name) : '<td></td>';
				$disabled   = ( ! $action_arr['checked']) ? 'disabled' : '';
				
				$checked    = ($action_arr['checked']) ? 'checked' : '';
				
				$html .= '<tr>';
				$html .= '<td>';
					if( EMPTY($val['parent_module_id']) ):
						$html .= '<input type="checkbox" name="check['.$val['module_id'].']" id="check_'.$val['module_id'].'" class="ind_checkbox filled-in" '.$checked.'/>
							  <label for="check_'.$val['module_id'].'"></label>';
					endif;
				$html .= '</td>';

				$html .= '<td style="padding-left: '.$indent.'px;" >';
					
					if( !EMPTY($val['parent_module_id']) ):
						$html .= '<div class="inline valign-top"><input type="checkbox" name="check['.$val['module_id'].']" id="check_'.$val['module_id'].'" class="ind_checkbox filled-in" '.$checked.'/>
							  <label for="check_'.$val['module_id'].'"></label></div>';
					endif;
					$html .= $val['module_name'];
				$html .= '</td>';
				
				$html .= '<td>';
					$html .= '<select class="selectize" name="module_actions['.$val['module_id'].'][]" id="module_action_'.$val['module_id'].'" multiple '.$disabled.'>';
						$html .= $action_arr['html'];
					$html .= '</select>';
				$html .= '</td>';
				
				if( ! EMPTY($val['available_scope_per_module']) ):
					$html .= '<td>';
						$html .= '<select class="selectize" name="module_scopes['.$val['module_id'].'][]" id="module_scope_'.$val['module_id'].'" '.$disabled.'>';
							$html .= $scope_html;
						$html .= '</select>';
					$html .= '</td>';
				endif;
				
				$html .= '</tr>';
			endforeach;
			
			echo $html;
			
		}
		catch(Exception $e)
		{
			echo $e->getMessage();
		}
	}
	
	private function _construct_action($module_id, $role_action, $avail_action_id, $avail_action_name)
	{
		$html    = '';
		$checked = false;
		$html .= '<option value=""></option>';
		foreach($avail_action_id as $key => $ai):
			$selected = (in_array($ai, $role_action)) ? 'selected' : '';
			$checked  = (in_array($ai, $role_action)) ? true : $checked;
			
			$html .= '<option value="'.$ai.'" '.$selected.'>'.$avail_action_name[$key].'</option>';
		endforeach;
		
		return array('html' => $html, 'checked' => $checked);
	}
	
	private function _construct_scope($module_id, $scope, $avail_scope_id, $avail_scope_name)
	{
		$html = '';
		$html .= '<option value="">Select Scope</option>';
		
		foreach($avail_scope_id as $key => $as):
				$selected = (ISSET($scope[$module_id]) && $scope[$module_id] == $as) ? 'selected' : '';
			$html .= '<option value="'.$as.'" '.$selected.'>'.$avail_scope_name[$key].'</option>';
		endforeach;
		
		return $html;
	}
	
	public function save()
	{
		try
		{
			$msg    = '';
			$status = 0;
			$params = get_params();
			
			// SERVER VALIDATION
			$this->_validate($params);
			
			// BEGIN TRANSACTION
			SYSAD_Model::beginTransaction();
			
			$audit_schema[]	= Base_Model::$schema_core;
			$audit_schema[]	= Base_Model::$schema_core;
			
			$audit_table[]	= $this->pm->tbl_module_scope_roles;
			$audit_table[]	= $this->pm->tbl_module_action_roles;
			
			$audit_action[] = AUDIT_UPDATE;
			$audit_action[] = AUDIT_UPDATE;
			
			// GET THE DETAIL FIRST BEFORE UPDATING THE RECORD
			$prev_detail[]  = $this->pm->get_role_scope($params['role_filter']);
			$prev_detail[]	= $this->pm->get_role_action($params['role_filter']);
			
			$this->pm->delete_action_roles($params['role_filter'], $params['system_filter']);
			$this->pm->delete_scope_roles($params['role_filter'], $params['system_filter']); 
			
			$this->pm->insert_action_roles($params['module_actions'], $params['role_filter']);
			if(! EMPTY($params['module_scopes']))
				$this->pm->insert_scope_roles($params['module_scopes'],  $params['role_filter']);
			
			// GET THE DETAIL AFTER UPDATING THE RECORD
			$curr_detail[] = $this->pm->get_role_scope($params['role_filter']);
			$curr_detail[] = $this->pm->get_role_action($params['role_filter']);
			
			// ACTIVITY TO BE LOGGED ON THE AUDIT TRAIL
			$activity = "%s has been updated";
			$activity = sprintf($activity, 'Permissions');
			
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
			$status = 1;
			$msg	= 'Permission successfully saved.';
			
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
		
		echo json_encode(array('msg' => $msg, 'status' => $status));
	}
	
	private function _validate($params)
	{		
		if( ! ISSET($params['role_filter']) || EMPTY($params['role_filter'])) throw new Exception('Role is required!');
		
		if( ! ISSET($params['module_actions']) || EMPTY($params['module_actions'])) throw new Exception('No modules and actions defined!');
		
		if(ISSET($params['check'])):
			foreach($params['check'] as $key => $val):
				if(! ISSET($params['module_actions'][$key])):
					$modules = $this->pm->get_modules($key);
					
					throw new Exception('Actions for '.$modules[0]['module_name'].' is required!');
				endif;
				
				if( ISSET($params['module_scopes'][$key]) && EMPTY($params['module_scopes'][$key][0])):
				
					$modules = $this->pm->get_modules($key);
						
					throw new Exception('Scope for '.$modules[0]['module_name'].' is required!');
				endif;
			endforeach;
		endif;

	}
	
	public function reset_options($system_code)
	{		
		$list = $this->pm->get_system_roles($system_code);
		
		echo json_encode($list);
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
			$resources = array();
		
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
				//if(($this->permission->check_permission(ROLE_MODULE, ACTION_EDIT))) :
					$action .= "<a href='javascript:;' class='md-trigger edit tooltipped' data-tooltip='Edit' data-position='bottom' data-delay='50' data-modal='modal_roles' onclick=\"modal_init('".$url."')\"></a>";
				//endif;

				//if (($this->permission->check_permission(ROLE_MODULE, ACTION_DELETE))) : 	  
					$action .= "<a href='javascript:;' onclick='" . $delete_action. "' class='delete tooltipped' data-tooltip='Delete' data-position='bottom' data-delay='50'></a>";
				//endif;
			}
			$action .= '</div>';
			
			if($cnt == count($roles)){
				$action.= "<script>$(function(){ $('.tooltipped').tooltip({delay: 50});	});</script>";
				$resources['load_js'] = array('modalEffects','classie');
				$action.= $this->load_resources->get_resource($resources, TRUE);
			}
				
			$row[] = $action;
				
			$output['aaData'][] = $row;
		endforeach;
	
		echo json_encode( $output );
	}
}


/* End of file Permissions.php */
/* Location: ./application/modules/sysad/controllers/Permissions.php */