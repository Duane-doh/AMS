<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Forgot_password extends CI_Controller {

	public function __construct() 
	{
		parent::__construct();
	}
		
	
	public function modal(){
		
		$this->load->view("modals/forgot_password");
	}
	
		
	public function request_reset(){
	
		$flag = 0;
		$msg = "";
		
		try
		{
		
			$params	= get_params();
			$email = filter_var($params['email'], FILTER_SANITIZE_EMAIL);
	
			if(EMPTY($email)) throw new Exception($this->lang->line('email_required'));
	
			$user_info = $this->auth_model->get_active_user($email, BY_EMAIL);
			if(EMPTY($user_info)) throw new Exception($this->lang->line('contact_admin'));
	
			$username = $user_info['username'];
	
			// SEND RESET PASSWORD INSTRUCTION
			$salt = $this->_send_reset_password($username, $email);
	
			// BEGIN TRANSACTION
			$this->db->beginTransaction();
			
			$this->auth_model->update_reset_salt($salt, $username);
			
			$this->db->commit();
	
			$flag = 1;
			$msg = $this->lang->line('reset_password');
	
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
		
}


/* End of file forgot_password.php */
/* Location: ./application/controllers/forgot_password.php */