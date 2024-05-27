<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Users extends SYSAD_Controller {

	private $module = MODULE_USER;
	
	public function __construct()
	{
		parent::__construct();
		
		$this->load->model('users_model', 'users', TRUE);
		
		//=====================================marvin=====================================
		//add user roles
		$this->load->model('roles_model', 'roles', TRUE);
		//=====================================marvin=====================================
	}
	
	public function index()
	{	
		$data = $resources = array();
		
		$resources['load_css'] = array(CSS_DATATABLE);
		$resources['load_js'] = array(JS_DATATABLE);
		$resources['datatable'][] = array('table_id' => 'user_table', 'path' => 'sysad/users/get_user_list');
		
		/* For Advanced Filters */
			$resources['datatable'] = array('table_id' => 'user_table', 'path' => 'sysad/users/get_user_list',
				'advanced_filter' => true);
		
		/*BREADCRUMBS*/
		$breadcrumbs 			= array();
		$key					= "User Management"; 
		$breadcrumbs[$key]		= PROJECT_CORE."/users";
		$key					= "Users"; 
		$breadcrumbs[$key]		= PROJECT_CORE."/users";
		set_breadcrumbs($breadcrumbs, TRUE);
		$this->template->load('users', $data, $resources);
	}
	
	
	public function get_user_list()
	{
		$params = get_params();
		// $aColumns = array("A.user_id", "A.nickname", "A.username", "A.fname","A.lname", "A.email", "B.status", "A.photo");
		// $bColumns = array("username", "fname", "lname", "email", "roles", "status");
		// ====================== jendaigo : start : include grp_roles in the query ============= //
		$aColumns = array("A.user_id", "A.nickname", "A.username", "A.fname","A.lname", "A.email", "GROUP_CONCAT(C.role_code SEPARATOR ' ') grp_roles", "B.status", "A.photo");
		$bColumns = array("username", "fname", "lname", "email", "grp_roles", "status");
		// ====================== jendaigo : start : include grp_roles in the query ============= //

	
		$users = $this->users->get_user_list($aColumns, $bColumns, $params);
		
		//=====================================marvin=====================================
		//get user roles
		$user_roles = array();
		foreach($users as $key => $val)
		{
			$users[$key]['temp_roles'] = $this->roles->get_user_roles($val['user_id']);
			for($i=0; $i<count($users[$key]['temp_roles']); $i++)
			{
				$users[$key]['roles'] .= $users[$key]['temp_roles'][$i]['role_code'] . '<br>';
			}
			unset($users[$key]['temp_roles']);
		}
		//=====================================marvin=====================================
		
		$iTotal = $this->users->total_length();
		$iFilteredTotal = $this->users->filtered_length($aColumns, $bColumns, $params);
	
		$output = array(
			"sEcho" => intval($_POST['sEcho']),
			"iTotalRecords" => $iTotal["cnt"],
			"iTotalDisplayRecords" => $iFilteredTotal["cnt"],
			"aaData" => array()
		);
		$cnt = 0;
		$permission_edit = $this->permission->check_permission(MODULE_USER, ACTION_EDIT);
		$permission_delete = $this->permission->check_permission(MODULE_USER, ACTION_DELETE);
		foreach ($users as $aRow):
			$cnt++;
			$row = array();
			$action = "<div class='table-actions'>";
		
			$user_id = $aRow["user_id"];
			$id = base64_url_encode($user_id);
			$salt = gen_salt();
			$token = in_salt($user_id, $salt);
			$url = $id."/".$salt."/".$token;

			$scope_id    = $this->hash($aRow["user_id"]);
			$scope_salt  = gen_salt();
			$scope_token = in_salt($scope_id  . '/' . ACTION_EDIT, $scope_salt);
			$scope_url   = ACTION_EDIT."/".$scope_id ."/".$scope_token."/".$scope_salt;

			$delete_action = 'content_delete("user","'.$id.'")';
			$img_src = (@getimagesize(base_url() . PATH_USER_UPLOADS . $aRow["photo"])) ? PATH_USER_UPLOADS . $aRow["photo"] : PATH_IMAGES . "avatar.jpg";
						
			for ( $i=0 ; $i<count($bColumns) ; $i++ )
			{
				$avatar = ($i == 0) ? '<img class="avatar" width="20" height="20" src="'.base_url(). $img_src.'" /> ' : '';
				$row[] =  $avatar . $roles . $aRow[ $bColumns[$i] ];
			}
			
			
			if($this->permission->check_permission(MODULE_USER, ACTION_EDIT))
				$action .= "<a href='javascript:;' class='gear tooltipped' data-tooltip='Edit Office Scope' data-position='bottom' data-delay='50' onclick=\"content_form('user_scopes/form/".$scope_url."', '".PROJECT_CORE."')\"></a>";
			
			if($this->permission->check_permission(MODULE_USER, ACTION_EDIT))
				$action .= "<a href='javascript:;' class='edit tooltipped' data-tooltip='Edit' data-position='bottom' data-delay='50' onclick=\"content_form('users/form/".$url."', '".PROJECT_CORE."')\"></a>";
			
			if($this->permission->check_permission(MODULE_USER, ACTION_DELETE))
				$action .= "<a href='javascript:;' onclick='".$delete_action."' class='delete tooltipped' data-tooltip='Delete' data-position='bottom' data-delay='50'></a>";
			
			$action .= "</div>";
			
			if($cnt == count($users))
				$action.= "<script>$(function(){ $('.tooltipped').tooltip({delay: 50});	});</script>";
			
			$row[] = $action;
				
			$output['aaData'][] = $row;
		endforeach;
	
		echo json_encode( $output );
	}
	
	public function form($id = NULL, $salt = NULL, $token = NULL)
	{
		try
		{
			$data = array();
			$resources = array();
			
			$this->load->model('orgs_model', 'orgs', TRUE);
			$this->load->model('roles_model', 'roles', TRUE);
				
		  	$data['orgs'] = $this->orgs->get_orgs();
			$data['roles'] = $this->roles->get_roles();

			
			$data['employees'] 			= $this->users->get_pds_employee_list();

			
			if(!IS_NULL($id)){
				$id = base64_url_decode($id);
				
				// CHECK IF THE SECURITY VARIABLES WERE CORRUPTED OR INTENTIONALLY EDITED BY THE USER
				check_salt($id, $salt, $token);
				
				$user = $this->users->get_user_details($id);
				// davcorrea : 11/06/2023 : include suffix :START
				$suffix = $this->users->get_user_suffix($id);
				$user['suffix'] = $suffix['ext_name'];
				// END

				//===============NCOCAMPO: ADD DTR_AO, LEAVE_AO AND IMM_SUP TO USER_MGMT: 11/15/2023 START===============
				$dtr_ao = $this->users->get_dtr_ao($id);
				$user['dtr_ao'] = $dtr_ao['dtr_ao'];


				$leave_ao =$this->users->get_leave_ao($id);
				$user['leave_ao'] = $leave_ao['leave_ao'];


				$imm_sup =$this->users->get_imm_sup($id);
				$user['imm_sup'] = $imm_sup['imm_sup'];

				//===============NCOCAMPO: ADD DTR_AO, LEAVE_AO AND IMM_SUP TO USER_MGMT: 11/15/2023 END ===============
				$data['user'] = $user;

				$user_roles = $this->roles->get_user_roles($id);
				for ($i=0; $i<count($user_roles); $i++){
					$user_roles_arr[] = $user_roles[$i]["role_code"]; 
				}
				
				if(!EMPTY($user["org_code"]))
					$resources['single'] = array('org' => $user["org_code"]);
				
				if(!EMPTY($user_roles_arr))
					$resources['multiple'] = array('role' => $user_roles_arr);
			}	
			
			$resources['load_css'] = array(CSS_LABELAUTY, CSS_SELECTIZE, CSS_UPLOAD);
			$resources['load_js'] = array(JS_LABELAUTY, JS_SELECTIZE, JS_UPLOAD);
			$resources['upload'] = array(
				array('id' => 'avatar', 'path' => PATH_USER_UPLOADS, 'allowed_types' => 'jpeg,jpg,png,gif', 'show_progress' => 1, 'show_preview' => 1)
			);

			/*BREADCRUMBS*/
			$breadcrumbs 			= array();
			if(isset($id))
			{
				$key					= "Edit User"; 
			}
			else
			{
				$key					= "Add User"; 
			}
			
			$breadcrumbs[$key]		= PROJECT_CORE."/users/form";
			set_breadcrumbs($breadcrumbs, FALSE);
			$this->template->load('forms/users', $data, $resources);
		
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
				
			$action = (EMPTY($params['user_id']))? AUDIT_INSERT : AUDIT_UPDATE;
	
			// SERVER VALIDATION
			$this->_validate($params, $action);
	
			// GET SECURITY VARIABLES
			$id		= filter_var($params['user_id'], FILTER_SANITIZE_NUMBER_INT);
			$salt	= $params['salt'];
			$token	= $params['token'];
			
			$name = $params['fname']. ' ' . $params['lname'];
	
			// CHECK IF THE SECURITY VARIABLES WERE CORRUPTED OR INTENTIONALLY EDITED BY THE USER
			check_salt($id, $salt, $token);
			
			$user_id 		= ($action == AUDIT_INSERT) ? 0 : $id;
			$email_exist	= $this->_validate_email($params['email'], $user_id);

			
			SYSAD_Model::beginTransaction();
			
			$audit_table[]	= $this->users->tbl_users;
			$audit_schema[]	= Base_Model::$schema_core;
			$audit_action[]	= $action;
			
			if(!$email_exist && EMPTY($id)){
				
				$prev_detail[]	= array();
				
				$id = $this->users->insert_user($params);
				$msg = $this->lang->line('data_saved');
				if(isset($params['pds_employee']))
				{
					// $this->users->insert_associated_account($params['pds_employee'],$id);
					
					//marvin
					$this->users->insert_associated_account($params['pds_employee'],$id,$params['password']);
				}
				// GET THE DETAIL AFTER INSERTING THE RECORD
				$curr_detail[] = $this->users->get_specific_user($id);	
				
				// ACTIVITY TO BE LOGGED ON THE AUDIT TRAIL
				$activity = "%s has been added";
				$activity = sprintf($activity, $name);				
			
			}else if($email_exist && ($id)){
								
				// GET THE DETAIL FIRST BEFORE UPDATING THE RECORD
				$prev_detail[] = $this->users->get_specific_user($id);
				
				$this->users->update_user($params);
				$msg = $this->lang->line('data_updated');
				
				// GET THE DETAIL AFTER UPDATING THE RECORD
				$curr_detail[] = $this->users->get_specific_user($id);
				
				// ACTIVITY TO BE LOGGED ON THE AUDIT TRAIL
				$activity = "%s has been updated";
				$activity = sprintf($activity, $name);				
				
			}else{				
				throw new Exception($this->lang->line('email_exist'));
			}
			
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
			if(!EMPTY($id) && ($params["send_email"]))
				$flag = $this->_send_welcome_email($id);
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
		if(EMPTY($params['lname']))
			throw new Exception('Last name is required.');
			
		if(EMPTY($params['fname']))
			throw new Exception('First name is required.');
	
		// if(EMPTY($params['email']))
			// throw new Exception('Email is required.');			
		
		if(EMPTY($params['org']))
			throw new Exception('Department/Agency is required.');
	
		if(ISSET($params['role']) AND EMPTY($params['role']))
			throw new Exception('Role is required.');
	
		if($action == AUDIT_INSERT){
			if(EMPTY($params['password']))
				throw new Exception('Password is required.');
				
			if(EMPTY($params['confirm_password']))
				throw new Exception('Please confirm password.');
				
			if($params['password'] != $params['confirm_password'])
				throw new Exception('Password did not match.');
		}
	}
	
	private function _validate_email($email, $id)
	{
		// try
		// {
			// $exist_flag = $this->users->check_email_exist($email, $id);			
			// return $exist_flag['email_exist'];
		// }
		
		// catch(Exception $e)
		// {
			// throw new Exception($e->getMessage());
		// }		
		
		//marvin
		if($email != "" OR $email != null)
		{
			try
			{
				$exist_flag = $this->users->check_email_exist($email, $id);			
				return $exist_flag['email_exist'];
			}
			
			catch(Exception $e)
			{
				throw new Exception($e->getMessage());
			}		
		}
	}
	
	public function delete_user()
	{
		try
		{
			$flag = 0;
			$params	= get_params();
	
			// CHECK IF THE SECURITY VARIABLES WERE CORRUPTED OR INTENTIONALLY EDITED BY THE USER
			$id = base64_url_decode($params['param_1']);
	
			// BEGIN TRANSACTION
			SYSAD_Model::beginTransaction();
			
			$audit_action[]	= AUDIT_UPDATE;
			$audit_table[]	= $this->users->tbl_users;
			$audit_schema[]	= Base_Model::$schema_core;
				
	
			// GET THE DETAIL FIRST BEFORE UPDATING THE RECORD
			$prev_detail[] = $this->users->get_user_details($id);
				
			$params['status_id'] = filter_var(DELETED, FILTER_SANITIZE_STRING);
			$params['user_id'] = filter_var($id, FILTER_SANITIZE_STRING);
			
			$this->users->update_status($params);
			
			$msg = $this->lang->line('data_deleted');
	
			// GET THE DETAIL AFTER UPDATING THE RECORD
			$curr_detail[] = $this->users->get_user_details($id);
			
			// ACTIVITY TO BE LOGGED ON THE AUDIT TRAIL
			$activity = "%s has been deleted";
			$activity = sprintf($activity, $prev_detail['fname'] . ' ' . $prev_detail['lname']);
			
			// LOG AUDIT TRAIL
			$this->audit_trail->log_audit_trail(
				$activity, 
				$this->module, 
				$prev_detail, 
				$curr_detail, 
				$audit_action, 
				$audit_table
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
	
	private function _send_welcome_email($id)
	{	
		try
		{
			$user_detail = $this->users->get_user_details($id);
			$created_by = $this->users->get_user_details($user_detail['created_by']);
			
			$flag = 0;
			$email_data = array();
			$template_data = array();
	
			$salt = gen_salt(TRUE);
			$system_title = get_setting(GENERAL, "system_title");
				
			// required parameters for the email template library
			$email_data["from_email"] = get_setting(GENERAL, "system_email");
			$email_data["from_name"] = $system_title;
			$email_data["to_email"] = array($user_detail['email']);
			$email_data["subject"] = 'New User Account';
				
			// additional set of data that will be used by a specific template
			$template_data["email"] = $user_detail['email'];
			$template_data["password"] = base64_url_encode($user_detail['password']);
			$template_data["raw_password"] = base64_url_decode($user_detail['raw_password']);
			$template_data["reason"] = $user_detail['reason'];
			$template_data["name"] = $user_detail['fname'] . ' ' . $user_detail['lname'];
			$template_data["created_by"] = $created_by['fname'] . ' ' . $created_by['lname'];
			$template_data["system_name"] = $system_title;
				
			$this->email_template->send_email_template($email_data, "emails/welcome_message", $template_data);
			//$flag = 1;
			$flag = $this->email->print_debugger();
			
			return $flag;
		}
		catch(Exception $e)
		{
			echo $e->getMessage();
		}
	
	}
	public function get_pds_personal_info()
	{
		try
		{

			$employee_details          = array() ;
			$params                    = get_params();
			$employee_id               = $this->hash($params['employee_id']);
			
			$employee_details          = $this->users->get_pds_employee_list($employee_id);
			$employee_office 		   = $this->users->get_pds_employee_office($employee_id);

			$employee_details['office'] = $employee_office['org_code'];

			$fname = strtolower(str_replace(' ', '', $employee_details ['first_name']));
			$lname = strtolower(str_replace(' ', '', $employee_details ['last_name']));
			$user_name = $fname.".".$lname;
			$check_user_name = $user_name;
			$exist = true;
			$x = 0;
			while($exist)
			{
				$exist         = $this->users->check_username($check_user_name);
				if($exist)
				{
					$x++;
					$check_user_name = 	$user_name.$x;
				}
				else
				{
					$employee_details['user_name'] = $check_user_name;
				}
			}


			$contacts         = $this->users->get_pds_employee_contacts($employee_id);
			if($contacts)
			{
				foreach($contacts as $contact)
				{
					switch($contact['contact_type_id'])
					{
						case MOBILE_NUMBER:
							$employee_details['mobile'] = $contact['contact_value'];
						break; 
						case EMAIL:
							$employee_details['email'] = $contact['contact_value'];
						break; 
						case PERMANENT_NUMBER:
							$employee_details['telephone'] = $contact['contact_value'];
						break; 
					}
				}
			}
		
		}
		catch(PDOException $e){
			$message = $e->getMessage();
			RLog::error($message);
		}
		catch(Exception $e)
		{
			$message = $e->getMessage();
			RLog::error($message);
		}
		echo json_encode($employee_details);
	}
}

/* End of file Users.php */
/* Location: ./application/modules/sysad/controllers/Users.php */