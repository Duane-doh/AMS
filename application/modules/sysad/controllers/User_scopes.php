<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_scopes extends SYSAD_Controller {
	
	private $module = MODULE_USER;
	
	public function __construct()
	{
		parent::__construct();
		
		$this->load->model('user_scopes_model', 'scopes', TRUE);
	}
	
	public function form($action = NULL, $id = NULL, $token = NULL, $salt = NULL)
	{	
		try
		{
			$data = array();
			$resources = array();

			if(EMPTY($action) OR EMPTY($id) OR EMPTY($token) OR EMPTY($salt))
			{
				throw new Exception($this->lang->line('invalid_action'));
			}
			if($token != in_salt($id . '/' . $action , $salt))
			{
				throw new Exception($this->lang->line('err_unauthorized_access'));
			}

			$data['action']       = $action;
			$data['id']           = $id;
			$data['salt']         = $salt;
			$data['token']        = $token;

			
			$resources['load_css'] = array(CSS_SELECTIZE);
			$resources['load_js']  = array(JS_SELECTIZE);

			$field                = array('system_code','system_name');
			$where                = array();
			$where['system_code'] = array($value = array(CODE_HR,CODE_PAYROLL,CODE_TA), array("IN"));
			$order_by             = array('sort_order_num'=>'asc');
			$table                = $this->scopes->tbl_systems;
			$data['systems']      = $this->scopes->get_general_data($field, $table, $where, TRUE,$order_by);
			
			$field                = array('CONCAT(fname," ",mname," ",lname) as fullname','username','email','photo');
			$where                = array();
			$key                  = $this->get_hash_key('user_id');
			$where[$key]          = $id;
			$table                = $this->scopes->tbl_users;
			$data['user_info']    = $this->scopes->get_general_data($field, $table, $where, FALSE);

			/*BREADCRUMBS*/
			$breadcrumbs 			= array();
			$key					= "User Scopes"; 
			$breadcrumbs[$key]		= PROJECT_CORE."/user_scopes";
			set_breadcrumbs($breadcrumbs, FALSE);
			$this->template->load('user_scopes', $data, $resources);
		}
		catch(Exception $e)
		{
			echo $e->getMessage();
		}
	}
	
	
	public function get_user_scopes()
	{
		try
		{
			$html    = '';
			$params  = get_params();
			$user_id = $params['id'];
			$system  = $params['system'];
			
		
			/*GET MODULES*/
			$field                         = array('A.module_id','A.module_name','A.level','A.parent_module_id','GROUP_CONCAT(CAST( B.office_id as CHAR)) as assigned_office','GROUP_CONCAT(CAST( B.office_id as CHAR)) as assigned_user');
			$where                         = array();
			if(!EMPTY($system) && $system  != 'all')
			{				
				$where['system_code']      = $system;
			}
			$where['with_office_restrict'] = 1;
			$md5_user_id                           = $this->get_hash_key('B.user_id');
			$order_by                      = array('sort_order' => 'asc');
			$group_by                      = array('A.module_id');
			$tables = array(
				'main'	=> array(
					'table'		=> $this->scopes->tbl_modules,
					'alias'		=> 'A',
				),
				't1'	=> array(
					'table'		=> $this->scopes->tbl_user_offices,
					'alias'		=> 'B',
					'type'		=> 'left join',
					'condition'	=> 'A.module_id = B.module_id AND '.$md5_user_id ." = '" . $user_id."'",
				)
			);		
			$modules                       = $this->scopes->get_general_data($field, $tables, $where, TRUE, $order_by, $group_by);
			
			/*GET OFFICES*/
			$field                = array('office_id','short_name');
			$where                = array();
			$where['active_flag'] = YES;
			$tables = array(
				'main'	=> array(
					'table'		=> $this->scopes->db_main.'.'.$this->scopes->tbl_param_offices,
					'alias'		=> 'A',
				),
				't1'	=> array(
					'table'		=> $this->scopes->tbl_organizations,
					'alias'		=> 'B',
					'type'		=> 'join',
					'condition'	=> 'B.org_code = A.org_code',
				)
			);		
			$order_by             = array('B.short_name'=>'asc');
			$offices      = $this->scopes->get_general_data($field, $tables, $where, TRUE, $order_by);
			
			//================================================================marvin================================================================
			//get user accounts
			$field 				= array('user_id','username');
			$where 				= array();
			$where['status_id'] = 1;
			$tables = array(
				'main' 	=> array(
					'table' => $this->scopes->tbl_users,
					'alias' => 'A',
				)
			);
			$order_by             = array('A.username'=>'asc');
			$users = $this->scopes->get_general_data($field, $tables, $where, TRUE, $order_by);
			//================================================================marvin================================================================

			$field       = array('GROUP_CONCAT(role_code) as roles');
			$where       = array();
			$key         = $this->get_hash_key('user_id');
			$where[$key] = $user_id;
			$table       = $this->scopes->tbl_user_roles;
			$roles       = $this->scopes->get_general_data($field, $table, $where, FALSE);
			$user_roles = explode(',', $roles['roles']);

			$header      = '';
			$indent      = 0;
			$curr_parent = 0;
			foreach($modules as $key => $val):

				/*CHECK USER'S MODULE PERMISSION*/
				$result = $this->check_user_permission($val['module_id'],$user_roles,ACTION_VIEW);
				RLog::info('LINE 128 =>'.$result );
				if ($result) :

					if($header != $val['system_name']):
						$html .= '<tr style="background: #6F7D95;">';
							$html .= '<td colspan="4">';
								$html .= '<p class="white-text font-xs font-semibold text-uppercase m-n">'.$val['system_name'].'</p>';
							$html .= '</td>';
							
							$header = $val['system_name'];
						$html .= '</tr>';
					endif;
					
					$indent = $val['level'] * 30;
					
					//================================================================marvin================================================================
					//change office to user scope for dtr and leaves base on roles
					if(in_array('AO', $user_roles) OR in_array('IMMSUP', $user_roles) OR in_array('LVAPPOFF', $user_roles))
					{
						switch($val['module_id'])
						{
							case 51:
								$assigned_user_id   = explode(',', $val['assigned_user']);
								$action_arr = $this->_construct_user($val['module_id'],$users, $assigned_user_id);
								break;
								
							case 53:
								$assigned_user_id   = explode(',', $val['assigned_user']);
								$action_arr = $this->_construct_user($val['module_id'],$users, $assigned_user_id);
								break;
								
							case 125:
								$assigned_user_id   = explode(',', $val['assigned_user']);
								$action_arr = $this->_construct_user($val['module_id'],$users, $assigned_user_id);
								break;
								
							default:
								$assigned_office_id   = explode(',', $val['assigned_office']);
								$action_arr = $this->_construct_office($val['module_id'],$offices, $assigned_office_id);
						}
					}
					else
					{
						$assigned_office_id   = explode(',', $val['assigned_office']);
						$action_arr = $this->_construct_office($val['module_id'],$offices, $assigned_office_id);
					}
					//================================================================marvin================================================================
					
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
							$html .= '<select class="selectize" name="module_office['.$val['module_id'].'][]" id="module_office_'.$val['module_id'].'" multiple '.$disabled.'>';
							$html .= $action_arr['html'];
						$html .= '</select>';
					$html .= '</td>';
					
					
					$html .= '</tr>';
				endif;
			endforeach;
			if(!EMPTY($html))
			{
				echo $html;
			}
			else
			{
				echo "<tr>
				  		<td colspan='4' style='text-align:center;'>No modules available for the selected system. </td>
				  	</tr>";
			}			
			
		}
		catch(Exception $e)
		{
			echo $e->getMessage();
		}
	}
	
	private function _construct_office($module_id, $offices, $assigned_office_id)
	{
		$html    = '';
		$checked = false;
		$html .= '<option value=""></option>';
		foreach($offices as $key => $office):
			$selected = (in_array($office['office_id'], $assigned_office_id)) ? 'selected' : '';
			$checked  = (in_array($office['office_id'], $assigned_office_id)) ? true : $checked;
			
			$html .= '<option value="'.$office['office_id'].'" '.$selected.'>'.$office['short_name'].'</option>';
		endforeach;
		
		return array('html' => $html, 'checked' => $checked);
	}
	
	//================================================================marvin================================================================
	//contruct user scope html
	private function _construct_user($module_id, $users, $assigned_user_id)
	{
		$html    = '';
		$checked = false;
		$html .= '<option value=""></option>';
		foreach($users as $key => $user):
			$selected = (in_array($user['user_id'], $assigned_user_id)) ? 'selected' : '';
			$checked  = (in_array($user['user_id'], $assigned_user_id)) ? true : $checked;
			
			$html .= '<option value="'.$user['user_id'].'" '.$selected.'>'.$user['username'].'</option>';
		endforeach;
		
		return array('html' => $html, 'checked' => $checked);
	}
	//================================================================marvin================================================================
	
	public function save()
	{
		try
		{
			$msg    = '';
			$status = 0;
			$params = get_params();
			$action = $params['action'];
			$id     = $params['id'];
			$token  = $params['token'];
			$salt   = $params['salt'];
			
			if(EMPTY($action) OR EMPTY($id) OR EMPTY($token) OR EMPTY($salt))
			{
				throw new Exception($this->lang->line('invalid_action'));
			}
			if($token != in_salt($id . '/' . $action , $salt))
			{
				throw new Exception($this->lang->line('err_unauthorized_access'));
			}
			// SERVER VALIDATION
			$this->_validate($params);
			

			
			// BEGIN TRANSACTION
			SYSAD_Model::beginTransaction();

			$field       = array('user_id');
			$where       = array();
			$key         = $this->get_hash_key('user_id');
			$where[$key] = $id;
			$table       = $this->scopes->tbl_users;
			$user_info   = $this->scopes->get_general_data($field, $table, $where, FALSE);

			
			
			
			$field       = array('user_id');
			$where       = array();
			$key         = $this->get_hash_key('user_id');
			$where[$key] = $id;
			$table       = $this->scopes->tbl_user_offices;
			$prev_detail[]   = $this->scopes->get_general_data($field, $table, $where, TRUE);
			
			
			$this->scopes->delete_user_scopes($id, $params['system_filter']);
			
			
			if($params['check'])
			{
				$fields = array() ;
				foreach($params['check'] as $key => $val):
					if(ISSET($params['module_office'][$key])):

						foreach ($params['module_office'][$key] as $office_id) {

							$fields[] = array(
										'office_id' => $office_id,
										'user_id'   => $user_info['user_id'],
										'module_id' => $key
							);
						}				
						
					endif;
					
				endforeach;
				$table = $this->scopes->tbl_user_offices;
				$this->scopes->insert_general_data($table,$fields,FALSE);
			}		

			
			// GET THE DETAIL AFTER UPDATING THE RECORD
			$audit_schema[]	= Base_Model::$schema_core;			
			$audit_table[]	= $this->pm->tbl_user_offices;			
			$audit_action[] = AUDIT_UPDATE;
			$curr_detail[] = $fields;
			
			// ACTIVITY TO BE LOGGED ON THE AUDIT TRAIL
			$activity = "%s has been updated";
			$activity = sprintf($activity, 'User Scopes');
			
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
			$msg	= 'User scopes successfully saved.';
			
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
		if( ! ISSET($params['system_filter']) || EMPTY($params['system_filter'])) throw new Exception('System is required!');
				
		if(ISSET($params['check'])):
			foreach($params['check'] as $key => $val):
				if(! ISSET($params['module_office'][$key])):
					
					$field              = array('module_name');
					$where              = array();					
					$where['module_id'] = $key;
					$table              = $this->scopes->tbl_modules;		
					$modules            = $this->scopes->get_general_data($field, $table, $where, false);
							
					// throw new Exception('Offices for <b>'.$modules['module_name'].'</b> is required!');
					throw new Exception('Offices / Users for <b>'.$modules['module_name'].'</b> is required!');
				endif;	
			endforeach;
		endif;

	}

	public function check_user_permission($module_id,$user_roles =NULL, $button_action = NULL){
	
		try
		{
			
			$permissions = $this->scopes->get_permission_access($module_id, $button_action);		
			
			if(!EMPTY($permissions))
			{
				
				RLog::info('RESULT ROLES :'.json_encode($roles));
				RLog::info('USER ROLES :'.json_encode($user_roles));
				RLog::info('USER PERMISSIONS :'.json_encode($permissions));
				
				if(!EMPTY($user_roles)){
		
					foreach($permissions as $permission):
						$role_code = $permission['role_code'];
							
						if(in_array($role_code, $user_roles))
							return TRUE;
					endforeach;
				}
			}
			return FALSE;			
		}
		catch(PDOException $e)
		{
			throw new PDOException($e->getMessage());
		}
		catch(Exception $e)
		{
			throw new Exception($e->getMessage());
		}	
	}
	
}


/* End of file User_scopes.php */
/* Location: ./application/modules/sysad/controllers/User_scopes.php */