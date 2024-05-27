<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Change_password extends CI_Controller {

	private $module = MODULE_USER;

	public function __construct() 
	{
		parent::__construct();
		$this->load->model(SYSAD.'/users_model', 'users', TRUE);
	}
		
	
	public function modal(){
		
		$data = $resources = array();
		$pass_const = $this->users->get_settings_arr(PASSWORD_CONSTRAINTS);

		$data['pass_err'] = $this->settings_model->get_pass_error_msg();
		
		$data['pass_length'] = $pass_const[PASS_CONS_LENGTH];
		$data['upper_length'] = $pass_const[PASS_CONS_UPPERCASE];
		$data['digit_length'] = $pass_const[PASS_CONS_DIGIT];
		$data['lower_length'] = $pass_const[PASS_CONS_LOWERCASE];
		$data['symbol_length'] = $pass_const[PASS_CONS_SYMBOL];
		$data['pass_hist'] = $pass_const[PASS_CONS_HISTORY];

		$this->load->view("modals/change_password",$data);
		$this->load_resources->get_resource($resources);
	}
	
		
	public function process(){
	
		$flag = 0;
		$msg = "";
		
		try
		{
		
			$params	= get_params();

			$username = filter_var($params['username'], FILTER_SANITIZE_STRING);
	
			$user_info = $this->auth_model->get_active_user($username, BY_USERNAME);
			
			$password = strtolower(preg_replace('/[^A-Za-z]/', '', $params['new_password']));
			
			if($password === strtolower($user_info['fname']) OR $password === strtolower($user_info['lname']) OR $password === strtolower($user_info['mname']) OR $password === strtolower($user_info['nickname'])) throw new Exception($this->lang->line('personal_info_constraint'));
			
			$user_id = intval($user_info['user_id']);

			if(!EMPTY($params['pass_hist'])) {
				switch (intval($params['pass_hist'])) {
					case 1:
						if($params['new_password'] === base64_url_decode($user_info['raw_password'])) throw new Exception("Your new password must not be equal to your previous password.");
						
						break;
					
					default:
						$user_history = $this->users->get_pass_history($user_id);
						if(!EMPTY($user_history)) {
							// COMPARE FIRST THE CURRENT PASSWORD FROM DB
							if($params['new_password'] === base64_url_decode($user_info['raw_password'])) throw new Exception("Your new password must not be equal to your previous password."); 
						
							for ($i=0; $i < intval($params['pass_hist']) - 1; $i++) {
								if($params['new_password'] === base64_url_decode($user_history[$i]['raw_password'])) throw new Exception("Your new password must not be equal to your previous password(s).");
							}
						}
						break;
				}
			}
			if(EMPTY($user_info)) throw new Exception($this->lang->line('contact_admin'));

			// BEGIN TRANSACTION
			$this->db->beginTransaction();
			
			$params['user_id'] = $user_id;

			$this->users->update_user_pass($params);

			$this->db->commit();

			$msg = $this->lang->line('password_change');
			$flag = 1;
	
		}
		catch(PDOException $e)
		{
			// IF THE TRANSACTION IS NOT SUCCESSFUL, ROLLBACK ALL CHANGES
			if($this->db->inTransaction())
				$this->db->rollBack();
				
			$msg = $e->getMessage();
		}
		catch(Exception $e)
		{
			// IF THE TRANSACTION IS NOT SUCCESSFUL, ROLLBACK ALL CHANGES
			if($this->db->inTransaction())
				$this->db->rollBack();
				
			$msg = $e->getMessage();
		}		
	
		$result = array(
			"flag" => $flag,
			"msg" => $msg
		);
	
		echo json_encode($result);
	}
	
	
	private function _send_reset_password($username, $email){
	
		try
		{
			$email_data = array();
			$template_data = array();
	
			$salt = gen_salt(TRUE);
			$system_title = get_setting(GENERAL, "system_title");
				
			// required parameters for the email template library
			$email_data["from_email"] = get_setting(GENERAL, "system_email");
			$email_data["from_name"] = $system_title;
			$email_data["to_email"] = array($email);
			$email_data["subject"] = $system_title.' - Reset Password';
				
			// additional set of data that will be used by a specific template
			$template_data["email"] = $email;
			$template_data["system_name"] = $system_title;
			$template_data["username"] = $username;
			$template_data["salt"] = $salt;
				
			$this->email_template->send_email_template($email_data, "emails/reset_password", $template_data);
	
			return $salt;
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
	
	
	public function reset($email, $salt){
	
		$msg = "";
		$data = array();
	
		try
		{
			
			if(EMPTY($email) OR EMPTY($salt))
				throw new Exception($this->lang->line('invalid_action'));
	
			
			$email 	= base64_url_decode($email);
			
			$salt 	= base64_url_decode($salt);
			
			// CHECK IF A USER'S PASSWORD SALT HAS BEEN SUCCESSFULLY RESET THROUGH THE EMAIL RECEIVED
			$cnt = $this->auth_model->get_reset_salt($email, $salt);
	
			if($cnt == 0) throw new Exception($this->lang->line('invalid_action'));
	
		}
		catch(PDOException $e)
		{
			$msg = $e->getMessage();
		}
		catch(Exception $e)
		{
			$msg = $e->getMessage();
		}
		
		$data["id"]	= in_salt($email, $salt, TRUE);
		$data["key"] = $salt;
		$data["msg"] = $msg;
	
		$this->load->view('reset_password', $data);
	
	}
	
	
	public function update()
	{
		$flag = 0;
		$msg = "";
	
		try
		{
			$params = get_params();
	
			$this->_check_reset_fields($params);
			$id	= $params["id"];
			$key = $params["key"];
			$password = $params["password"];
	
			$info = $this->auth_model->get_active_user_reset_salt($key);
			
			if(EMPTY($info)) throw new Exception($this->lang->line('invalid_action'));
	
			$email = $info["email"];
	
			if($id != in_salt($email, $key, TRUE)) throw new Exception($this->lang->line('invalid_action'));
	
			$this->auth_model->update_password($email, $password);
	
			$msg = sprintf($this->lang->line('password_reset'), "<a href='".base_url()."'>here</a>");
			$flag = 1;
	
		}
		catch(PDOException $e)
		{
			$msg = $e->getMessage();
		}
		catch(Exception $e)
		{
			$msg = $e->getMessage();
		}
		
		$result = array(
			"msg" => $msg,
			"flag" => $flag
		);
	
		echo json_encode($result);
	}
	
	
	private function _check_reset_fields($params)
	{
		if(!ISSET($params["id"]) OR EMPTY($params["id"])) throw new Exception($this->lang->line('invalid_action'));
		if(!ISSET($params["key"]) OR EMPTY($params["key"])) throw new Exception($this->lang->line('invalid_action'));
		if(!ISSET($params["password"]) OR EMPTY($params["password"])) throw new Exception($this->lang->line('password_required'));
		if(!ISSET($params["password2"]) OR EMPTY($params["password2"])) throw new Exception($this->lang->line('confirm_password'));
		if($params["password"] != $params["password2"]) throw new Exception($this->lang->line('confirm_error'));
	}
	
	public function validate_password($front_end = TRUE)
	{
		$flag = 1;
		try
		{
			$params = get_params();
			$password = filter_var($params['password'], FILTER_SANITIZE_STRING);
			
			$this->authenticate->sign_in($params['username'], $password, FALSE, TRUE);
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


/* End of file forgot_password.php */
/* Location: ./application/controllers/forgot_password.php */