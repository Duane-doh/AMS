<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Profile extends SYSAD_Controller {

	private $module = MODULE_USER;
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('users_model', 'users', TRUE);
		$this->load->model('orgs_model', 'orgs', TRUE);
	}
	
	
	public function modal(){
		
		try{
			$data = array();
			
			$id = $this->session->user_id;
			$role_codes	= $this->session->user_roles;
			
			$roles	= array();
			foreach ($role_codes as $role_code)
			{
				$roles[] = $this->users->get_specific_role_code($role_code);
			}
			$data['roles']	= $roles;
			
			
			// check_salt($id, $salt, $token);
			
			$user = $this->users->get_user_details($id);

			$data['user'] = $user;			
			$data['orgs'] = $this->orgs->get_orgs();
			
			$resources['load_css'] = array(CSS_LABELAUTY);
			$resources['load_js'] = array(JS_LABELAUTY);
			
			if(!EMPTY($user["org_code"]))
				$resources['single'] = array('organization' => $user["org_code"]);
			
			$pass_const = $this->users->get_settings_arr(PASSWORD_CONSTRAINTS);
			
			$data['pass_err'] = $this->settings_model->get_pass_error_msg();
			
			$data['pass_length'] = $pass_const[PASS_CONS_LENGTH];
			$data['upper_length'] = $pass_const[PASS_CONS_UPPERCASE];
			$data['digit_length'] = $pass_const[PASS_CONS_DIGIT];
			$data['lower_length'] = $pass_const[PASS_CONS_LOWERCASE];
			$data['symbol_length'] = $pass_const[PASS_CONS_SYMBOL];
			
			
			$this->load->view("modals/profile", $data);
			$this->load_resources->get_resource($resources);
		}
		catch(Exception $e)
		{
			echo $e->getMessage();
		}
	}
		
	public function process()
	{
		$flag	= 0;
		$msg	= "";
		try
		{
			$params	= get_params();
			$name = "";
			
			// SERVER VALIDATION
			$this->_validate($params, $action);
			// GET SECURITY VARIABLES
			$id	= filter_var($params['user_id'], FILTER_SANITIZE_NUMBER_INT);
			$salt = $params['salt'];
			$token = $params['token'];
			$current_password = $params['current_password'];
			
			// CHECK IF THE SECURITY VARIABLES WERE CORRUPTED OR INTENTIONALLY EDITED BY THE USER
			check_salt($id, $salt, $token);
			// BEGIN TRANSACTION
			SYSAD_Model::beginTransaction();
			
			$audit_action[]	= AUDIT_UPDATE;
			$audit_table[]	= $this->users->tbl_users;
			$audit_schema[]	= Base_Model::$schema_core;
			
			// GET THE DETAIL FIRST BEFORE UPDATING THE RECORD
			$prev_detail[] = $this->users->get_specific_user($id);
			
			$this->users->update_user($params);
			$msg = $this->lang->line('data_updated');
			
			// GET THE DETAIL AFTER UPDATING THE RECORD
			$curr_detail[] = $this->users->get_specific_user($id);
			
			$name = $params['fname'] . ' ' . $params['lname'];
			// ACTIVITY TO BE LOGGED ON THE AUDIT TRAIL
			$activity = "Profile of %s has been updated.";
			$activity = sprintf($activity, $name);

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
			
			$arr = array(
				"photo" => $params['image'],
				"job_title" => $params['job_title'],
				"name" => $name
			);			
			$this->session->set_userdata($arr);
			
		}
		catch(PDOException $e)
		{
			SYSAD_Model::rollback();
		
			$msg = $this->rlog_error($e, TRUE);
			
			$flag = 0;
			$msg = $e->getMessage();
		}
		catch(Exception $e)
		{
			SYSAD_Model::rollback();
			
			$msg = $this->rlog_error($e, TRUE);
			
			$flag = 0;
			$msg = $e->getMessage();
		}		
	
		$info = array(
			"flag" => $flag,
			"msg" => $msg,
			"image" => $params['image'],
			"job_title" => $params['job_title'],
			"name" => $name
		);
	
		echo json_encode($info);
	
	}
	
	private function _validate($params, $action = NULL)
	{
		try
		{
			if(EMPTY($params['lname']))
				throw new Exception('Last name is required.');
				
			if(EMPTY($params['fname']))
				throw new Exception('First name is required.');
		
			if(EMPTY($params['mname']))
				throw new Exception('Middle initial is required.');
		
			if(EMPTY($params['email']))
				throw new Exception('Email is required.');
			
// 			if(EMPTY($params['org']))
// 				throw new Exception('Agency is required.');
			
			
			if(!EMPTY($params['current_password'])){
				if(!EMPTY($params['password']))
				{
					if(EMPTY($params['confirm_password']))
						throw new Exception('Confirm password is required.');
						
					$this->users->check_password_history($this->session->userdata('user_id'), $params['confirm_password']);
				}
				else
				{
					throw new Exception('Password is required.');
				}
			}
		}
		catch(Exception $e)
		{
			throw $e;
		}
		
	}
	
	public function validate_password($password = NULL, $front_end = TRUE)
	{
		$flag = 1;
		try
		{
			if(!EMPTY($password)) $password = filter_var($password, FILTER_SANITIZE_STRING);
			else {
				$params = get_params();
				$password = filter_var($params['password'], FILTER_SANITIZE_STRING);
			}
			$this->authenticate->sign_in($this->session->userdata('username'), $password, FALSE, TRUE);
		}
		catch(Exception $e)
		{
			$flag = 0;
		}
		$info = array(
				"flag" => $flag
		);
		
		if($front_end)
			echo json_encode($info);
		else 
			return $flag;
	}
}