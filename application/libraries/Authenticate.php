<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Authenticate {
	
	public function __construct()
	{
		$this->CI =& get_instance();
		$exemption = array("css", "common", "sign_up", "forgot_password", "change_password","account_cron");
				
		if(!in_array($this->CI->router->fetch_class(), $exemption))
			$this->check_user();
	}
	
	public function check_user()
	{				
		// CHECK IF SESSION EXISTS
		$authenticated = ($this->CI->session->has_userdata('user_id') === TRUE)? 1 : 0;
				
		if($this->CI->router->fetch_class() != "auth")
		{				
			if(!$authenticated)			
				header('location: '.base_url());
		}
		else
		{	
			if($authenticated AND $this->CI->router->fetch_method() != "sign_out")
				header('location: '.base_url().HOME_PAGE);
		}						
	}	
	
	
	public function sign_in($username, $password, $salted , $verify_pass_only = FALSE)
	{
		try 
			{
			$flag = 0;
			$user_info = $this->CI->auth_model->get_active_user($username);
			$login_params = $this->CI->auth_model->get_login_params();
			$user_work_exp = $this->CI->auth_model->validate_active_work_experience($user_info['user_id']);
			// if($user_work_exp['employ_end_date'])
			// {
			// 	$date_now = strtotime(date('Y-m-d'));
			// 	$last_day = strtotime($user_work_exp['employ_end_date']);
			// 	$days_diff = ($date_now - $last_day) / (60 * 60 * 24);
			// 	if($days_diff > 15)
			// 	{
			// 		// $this->CI->auth_model->update_status_inactive($user_info['user_id']);
			// 		throw new Exception($this->CI->lang->line('account_inactive'));
			// 	}
			
			// }
			
			$allowed_val = array(BLOCKED, ACTIVE, EXPIRED, INACTIVE);
			if(intval($user_info['attempts']) == intval($login_params['FAILED_LOGIN_ATTEMPT_NUM_BAN']) AND !EMPTY($user_info['allow_login_after_date'])) {

				$time = new DateTime($user_info['allow_login_after_date']);
				$time->add(new DateInterval('PT' . (intval($login_params['FAILED_LOGIN_ATTEMPT_BAN_PERIOD'])) . 'M'));
				$stamp = $time->format('Y-m-d H:i:s');
				if($stamp > date('Y-m-d H:i:s'))
				throw new Exception('This account is still banned and will be ready to use ' . get_date_format($stamp) . '.');
			}
			if(EMPTY($user_info) && !in_array($user_info['status_id'], $allowed_val))
				throw new Exception($allowed_val);
		
			
			if($user_info['status_id'] == INACTIVE)
				throw new Exception($this->CI->lang->line('account_inactive'));

			if($user_info['status_id'] == BLOCKED)
				throw new Exception($this->CI->lang->line('account_blocked'));

			if($user_info['status_id'] == EXPIRED AND !$verify_pass_only)
				throw new Exception($this->CI->lang->line('account_expired'));
			// ENCRYPT THE PASSWORD 
			$password = ($salted)? $password : in_salt($password, $user_info["salt"], TRUE);
			if($password != $user_info['password'])
			{
				$this->CI->auth_model->update_attempts($user_info["user_id"], $user_info["attempts"]);

				if(intval($user_info['attempts']) + 1 == intval($login_params['FAILED_LOGIN_ATTEMPT_NUM_BAN'])) {
					// UPDATE THE DURATION OF USER'S BAN
					$this->CI->auth_model->update_user_data($user_info["user_id"]);
					$e_message = sprintf($this->CI->lang->line('login_attempt_ban'), $login_params['FAILED_LOGIN_ATTEMPT_BAN_PERIOD'] . ' minutes.');
				} elseif(intval($user_info['attempts']) + 1 == intval($login_params['FAILED_LOGIN_ATTEMPT_NUM_LOCK'])) {
					// UPDATE THE USER'S STATUS TO BLOCKED
					$e_message = sprintf($this->CI->lang->line('login_attempt_block'));
				}
				else
					$e_message = ($verify_pass_only) ? 'Incorrect Password.' : $this->CI->lang->line('invalid_login');
				throw new Exception($e_message);
			}
			if($verify_pass_only === TRUE) return TRUE;
			
			if($user_info['status_id'] == PENDING)
				throw new Exception($this->CI->lang->line('pending_account'));

			// GET AND CHECK USER ROLES	
			$user_roles	= $this->CI->auth_model->get_user_roles($user_info["user_id"], $user_info["attempts"]);
			if(EMPTY($user_roles))
				throw new Exception($this->CI->lang->line('contact_admin'));
			
			// GET THE USERS SYSTEMS
			$user_systems = $this->CI->auth_model->get_user_systems($user_info["user_id"], $user_info["attempts"]);
			if(EMPTY($user_systems))
				throw new Exception($this->CI->lang->line('contact_admin'));


			// SET THE USER INFO IN SESSION VARIABLES
			$arr = array(
				"user_id" => $user_info["user_id"],	
				"username" => $user_info["username"],
				"photo" => $user_info["photo"],
				"name" => $user_info["name"],
				"job_title" => $user_info["job_title"],
				"location_code" => $user_info["location_code"],
				"org_code" => $user_info["org_code"]
			);			
			$this->CI->session->set_userdata($arr);
			
			// SET USER ROLES IN SESSION VARIABLES
			$roles = array();
			foreach($user_roles as $role):
				$roles[] = $role['role_code'];
			endforeach;
				 			
			$this->CI->session->set_userdata('user_roles', $roles);

			$systems_code = array();
			$systems_name = array();
			foreach($user_systems as $systems):
				$systems_code[] = $systems['system_code'];
				$systems_name[] = $systems['system_name'];
			endforeach;
				 			
			$this->CI->session->set_userdata('system_code', $systems_code);
			$this->CI->session->set_userdata('system_name', $systems_name);
			
			// START: Added by SGT for PTIS
			// SET OFFICES ASSIGNED TO USER, IF ANY
			$user_offices = $this->CI->auth_model->get_user_offices($user_info["user_id"]);
			$assigned_offices = array();

			RLog::info('S: ASSIGNED OFFICES FOR USER ID ['.$user_info["user_id"].']');
			
			$assigned_offices = set_key_value($user_offices, 'module_id', 'offices');

			RLog::info($assigned_offices);
			RLog::info('E: ASSIGNED OFFICES FOR USER ID ['.$user_info["user_id"].']');

			if( ! EMPTY($assigned_offices))
				$this->CI->session->set_userdata('user_offices', $assigned_offices);
			// END: Added by SGT for PTIS
				
			// CHECK IF SESSION EXISTS
			if($this->CI->session->has_userdata('user_id') === FALSE)
				throw new Exception($this->CI->lang->line('system_error'));
							
		}
		catch(Exception $e)
		{
			throw new Exception($e->getMessage());
		}	
	}
	
	
	public function sign_out()
	{
		try {
			// DESTROY ALL SESSIONS
			$this->CI->session->sess_destroy();
			
			// CHECK IF SESSION_ID WAS DESTROYED		
			if($this->CI->session->has_userdata('user_id') === FALSE)
				throw new Exception($this->CI->lang->line('system_error'));									
		}
		catch(Exception $e)
		{
			throw new Exception($e->getMessage());
		}
		
	}
	
}