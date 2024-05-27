<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sign_up extends CI_Controller {

	private $module = MODULE_USER;
	
	public function __construct() 
	{
		parent::__construct();
		
		$this->load->model(PROJECT_CORE . '/users_model', 'users', TRUE);
		$this->load->model(PROJECT_CORE . '/orgs_model', 'orgs', TRUE);
	}	
	
	public function modal()
	{
		
		$data = array();
		$resources = array();
		
		$resources["load_css"] = array('jquery-labelauty', 'selectize.default');
		$resources["load_js"] = array('jquery-labelauty', 'selectize');
		
		$data['orgs'] = $this->orgs->get_orgs();
		
		$this->load->model('settings_model');
		$constraints = $this->settings_model->get_settings_value(PASSWORD_CONSTRAINTS);
		$pass_const = array();
		
		foreach ($constraints as $row)
			$pass_const[$row['setting_name']] = $row['setting_value'];
		
		
		$data['pass_err'] = $this->settings_model->get_pass_error_msg();
		
		$data['pass_length'] = $pass_const[PASS_CONS_LENGTH];
		$data['upper_length'] = $pass_const[PASS_CONS_UPPERCASE];
		$data['digit_length'] = $pass_const[PASS_CONS_DIGIT];
		$data['lower_length'] = $pass_const[PASS_CONS_LOWERCASE];
		$data['symbol_length'] = $pass_const[PASS_CONS_SYMBOL];
		
		$this->load->view("modals/sign_up", $data);
		$this->load_resources->get_resource($resources);
	}
	
	public function process()
	{
		try
		{
			$flag = 0;
			$mail_flag = 0;
			$params	= get_params();
			$params["status"] = PENDING;
	
			// SERVER VALIDATION
			$this->_validate($params);
			
			// GET SECURITY VARIABLES
			$salt = $params['salt'];
			$token = $params['token'];
			
			// CHECK IF THE SECURITY VARIABLES WERE CORRUPTED OR INTENTIONALLY EDITED BY THE USER
			check_salt(PROJECT_NAME, $salt, $token);
			$email_exist = $this->_validate_email($params['email']);
			
			if($email_exist)
				throw new Exception($this->lang->line('email_exist'));
				
			// BEGIN TRANSACTION
			SYSAD_Model::beginTransaction();
		
			$audit_action[]	= AUDIT_INSERT;
			$audit_table[]	= $this->users->tbl_users;
			$audit_schema[]	= Base_Model::$schema_core;
			$prev_detail[]	= array();
			
			$id = $this->users->insert_user($params);
				
			$msg = $this->lang->line('signup_success');
				
			// GET THE DETAIL AFTER INSERTING THE RECORD
			$curr_detail[] = $this->users->get_user_details($id);
				
			// ACTIVITY TO BE LOGGED ON THE AUDIT TRAIL
			$activity = "%s has signed up";
			$activity = sprintf($activity, $params['fname'] . ' ' . $params['lname']);
								
			$this->audit_trail->log_audit_trail(
				$activity,
				$this->module,
				$prev_detail,
				$curr_detail,
				$audit_action,
				$audit_table
			);
		
			SYSAD_Model::commit();
				
			$mail_flag = $this->_send_sign_up_email($curr_detail);
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
			"mail_sent" => $mail_flag
		);
	
		echo json_encode($info);
	
	}
	
	private function _validate($params)
	{
		if(EMPTY($params['lname']))
			throw new Exception('Last name is required.');
			
		if(EMPTY($params['fname']))
			throw new Exception('First name is required.');
	
		if(EMPTY($params['mname']))
			throw new Exception('Middle initial is required.');
	
		if(EMPTY($params['email'])){
			throw new Exception('Email is required.');
		}
	
		if(EMPTY($params['org']))
			throw new Exception('Agency is required.');
	
		// if(EMPTY($params['username']))
			// throw new Exception('Username is required.');
	
		if(EMPTY($params['password']))
			throw new Exception('Password is required.');
		else
		{
			$this->load->model('settings_model');
			$val_result = $this->settings_model->validate_password($params['password']);
			if(!is_bool($val_result) && $val_result !== TRUE)
				throw new Exception($val_result);
		}
	
		if(!EMPTY($params['password']) && EMPTY($params['confirm_password']))
			throw new Exception('Please confirm password.');
	
		if($params['password'] != $params['confirm_password'])
			throw new Exception('Password did not match.');
	
	}
	
	private function _send_sign_up_email($user_details)
	{	
		try
		{
			$flag = 0;
			$email_data = array();
			$template_data = array();
	
			$salt = gen_salt(TRUE);
			$system_title = get_setting(GENERAL, "system_title");
			$system_email = get_setting(GENERAL, "system_email");
				
			// required parameters for the email template library
			$email_data["from_email"] = $system_email;
			$email_data["from_name"] = $system_title;
			$email_data["to_email"] = array($user_details['email']);
			$email_data["subject"] = 'Your Pending Registration';
				
			// additional set of data that will be used by a specific template
			$template_data["email"] = $user_details['email'];
			$template_data["system_name"] = $system_title;
			$template_data["name"] = $user_details['fname'] . ' ' . $user_details['lname'];
				
			$flag = $this->email_template->send_email_template($email_data, "emails/sign_up", $template_data);
			//$flag = 1;
			
			return $flag;
		}
		catch(Exception $e)
		{
			throw new Exception($e->getMessage());
		}
	
	}
	
	private function _validate_email($email)
	{
		try
		{
			$exist_flag = $this->users->check_email_exist($email);
			
			return $exist_flag['email_exist'];
		}
		
		catch(Exception $e)
		{
			throw new Exception($e->getMessage());
		}
		
	}
		
}


/* End of file sign_up.php */
/* Location: ./application/controllers/sign_up.php */