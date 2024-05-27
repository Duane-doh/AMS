<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Settings_model extends SYSAD_Model {
	
	var $settings_tbl = "site_settings";
	var $sys_param_tbl = "sys_param";
                
	public function __construct() {
		parent::__construct(); 
	}
        
	public function get_settings_value($setting_type)
	{
		try
		{				
			$where = array();
				
			$fields = array("setting_name", "setting_value");
			$where["setting_type"] = $setting_type;
			
			return $this->select_all($fields, $this->settings_tbl,  $where);
		}
		catch(PDOException $e)
		{
			throw new PDOException($e->getMessage());
		}
	
	}	
	
	public function get_specific_setting($setting_type, $setting_name)
	{
		try
		{
			$where = array();
			
			$fields = array("setting_value");
			$where["setting_type"] = $setting_type;
			$where["setting_name"] = $setting_name;
				
			return $this->select_one($fields, $this->settings_tbl, $where);
		}
		catch(PDOException $e)
		{
			throw new PDOException($e->getMessage());
		}
	}
	
	public function get_pass_error_msg()
	{
		try
		{
			$result = $this->get_settings_value(PASSWORD_CONSTRAINTS);
			$pass_constraints = array();
			foreach ($result as $row)
				$pass_constraints[$row['setting_name']] = $row['setting_value'];
			if(intval($pass_constraints[PASS_CONS_LENGTH]) !== 0) $err[] = 'Password field must be composed of at least ' . $pass_constraints[PASS_CONS_LENGTH] . ' character(s). ';
			if(intval($pass_constraints[PASS_CONS_DIGIT]) !== 0) $err[] = 'Containing at least ' . $pass_constraints[PASS_CONS_DIGIT] . ' digit(s).';
			if(intval($pass_constraints[PASS_CONS_UPPERCASE]) !== 0) $err[] = 'Containing at least ' . $pass_constraints[PASS_CONS_UPPERCASE] . ' uppercase letter(s).';
			if(intval($pass_constraints[PASS_CONS_LOWERCASE]) !== 0) $err[] = 'Containing at least ' . $pass_constraints[PASS_CONS_LOWERCASE] . ' lowercase letter(s).';
			if(intval($pass_constraints[PASS_CONS_SYMBOL]) !== 0) $err[] = 'Containing at least ' . $pass_constraints[PASS_CONS_SYMBOL] . ' in any of these symbols( = ? @ # $ * ! ).';
			return implode('<br /> - ',$err);
			
			}
		catch(PDOException $e)
		{
			throw $e;
		}
		catch(Exception $e)
		{
			throw $e;
		}
	}
	
	public function validate_password($password)
	{
		try
		{
			$msg = $this->get_pass_error_msg();
			
			$constraints = $this->settings_model->get_settings_value(PASSWORD_CONSTRAINTS);
			$pass_const = array();
			
			foreach ($constraints as $row)
				$pass_const[$row['setting_name']] = $row['setting_value'];
			
			$data['pass_err'] = $this->settings_model->get_pass_error_msg();
			$pass_length = $pass_const[PASS_CONS_LENGTH];
			$upper_length = $pass_const[PASS_CONS_UPPERCASE];
			$digit_length = $pass_const[PASS_CONS_DIGIT];
			
			$pass_count = strlen($password);
			$upper_count = strlen(preg_replace('/[^A-Z]+/', "", $password));
			$digit_count = strlen(preg_replace('/[^0-9]+/', "", $password));
			if
			(
				$pass_count < intval($pass_length) 
				|| 
				($upper_count < intval($upper_length) || intval($upper_length) <= 0)
				|| 
				($digit_count < intval($digit_length) || intval($digit_length) <= 0)
			)
			{
				return $msg;
	   		}
   			return TRUE;
   		}
   		catch(PDOException $e)
   		{
   			throw $e;
   		}
   		catch(Exception $e)
   		{
   			throw $e;
   		}
		
	}
   	
	public function get_sysparam_value($sys_param_type)
	{
		try
		{
			$fields = array('*');
			
			$where  = array();
			$where['sys_param_type'] = $sys_param_type;
		
			return $this->select_one($fields, $this->sys_param_tbl, $where);
		}
		catch(PDOException $e)
		{
			$this->rlog_error($e);
			
			throw $e;
		}
		catch(Exception $e)
		{
			$this->rlog_error($e);
			
			throw $e;			
		}
	}     
}

/* End of file settings_model.php */
/*/application/models/settings_model.php*/
