<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Auth_model extends SYSAD_Model {
	
	var $user_tbl                  = "users";
	var $role_tbl                  = "user_roles";
	var $notifications_tbl         = "notifications";
	var $table_associated_accounts = "associated_accounts";
	var $table_module_action_roles = "module_action_roles";
	var $table_module_actions      = "module_actions";
	var $table_modules             = "modules";
	var $table_systems             = "systems";
	var $audit_trail_table 		   = "audit_trail"; 		//jendaigo: include audit trail table
	var $audit_trail_detail_table  = "audit_trail_detail";  //jendaigo: include audit trail details table

	public function get_active_user($search_term)
	{
		try{

			$login_via	= get_setting(LOGIN, "login_via");
			$where		= array();
			
			$fields = array("user_id", "username", "email", "password", "raw_password", "salt", "status_id", "CONCAT(fname, ' ', lname) name", "photo", "job_title", "location_code", "org_code", "attempts", "allow_login_after_date", "fname", "mname", "lname", "nickname");
			
			switch($login_via){
				case 'USERNAME_EMAIL':
					//$where["OR"] = array(BY_USERNAME => $search_term, BY_EMAIL => $search_term);
					
					$where[BY_USERNAME]	= array($search_term, array("=", 'OR'));
					$where[BY_EMAIL]	= $search_term;
					
				break;
				
				default:
					$search_by = strtolower($login_via);
					$where[$search_by] = $search_term;
				break;
				
			}	
			
			$where["status_id"] = array($value = array(BLOCKED, ACTIVE, EXPIRED, INACTIVE), array("IN"));;
			$where["salt"] 		= "IS NOT NULL";
			$where["password"]	= "IS NOT NULL";
			 
			return $this->select_one($fields, $this->user_tbl, $where);
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
	public function get_active_user_reset_salt($search_term)
	{
		try{
	
			
			$where		= array();
				
			$fields = array("user_id", "username", "email", "password", "salt", "status_id", "CONCAT(fname, ' ', lname) name", "photo", "job_title", "location_code", "org_code");
				
			$where["reset_salt"]	= $search_term;
				
			$where["status_id"] = ACTIVE;
			$where["salt"] 		= "IS NOT NULL";
			$where["password"]	= "IS NOT NULL";
	
			return $this->select_one($fields, $this->user_tbl, $where);
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
	
	public function get_user_roles($user_id)
	{
		try{
			
			$where = array();
			
			$fields = array("role_code");
			$where["user_id"] = $user_id;
			
			return $this->select_all($fields, $this->role_tbl,$where);
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
	
	public function update_reset_salt($salt, $username)
	{
		try{
			
			$val = array();
			$where = array();
				
			$val["reset_salt"] = $salt;
				
			$where["username"] = $username;
				
			$this->update_data($this->user_tbl, $val, $where);
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
	
	
	public function get_reset_salt($email, $salt)
	{
		try{
			
			$where = array();
				
			$fields = array("COUNT(user_id) cnt");
			$where["email"] = $email;
			$where["reset_salt"] = $salt;
			$where["status_id"] = ACTIVE;
				
			$result = $this->select_one($fields, $this->user_tbl, $where);
			
			return $result["cnt"];
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
	
	public function update_password($email, $password)
	{
	
		try{
			
			$val = array();
			$where = array();
			
			// ENCRYPT THE PASSWORD
			$salt = gen_salt(TRUE);
			$password = in_salt($password, $salt, TRUE);
			
			$val["password"] = $password;
			$val["salt"] = $salt;
			$val["reset_salt"] = '';
			
			$where["email"] = $email;
			
			$this->update_data($this->user_tbl, $val, $where);
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
	
	public function update_status($username)
	{
	
		try{
				
			$val = array();
			$where = array();
				
			$val["status_id"] = ACTIVE;
				
			$where["OR"] = array("username" => $username, "email" => $username);
			$where['status_id'] = APPROVED;
				
			$this->update_data($this->user_tbl, $val, $where);
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
	
	public function update_attempts($user_id , $attempts)
	{
		try 
		{
			if(!$this->db->inTransaction()) $this->db->beginTransaction(); 
			$login_max = get_setting(LOGIN, 'login_attempts');
			$query = <<<EOS
			UPDATE 	{$this->user_tbl} set attempts = (attempts + 1), modified_date = modified_date
			WHERE user_id = ?;
EOS;
			$stmt = $this->db->prepare($query);
			$stmt->execute(array($user_id));
			if(intval($login_max) != 0 && (intval($login_max) <= intval($attempts) + 1))
			{
				$blocked = BLOCKED;
				// $query = <<<EOS
				// UPDATE 	{$this->user_tbl} set status_id = '{$blocked}', modified_date = modified_date
				// WHERE user_id = ?;
// EOS;
				$query = <<<EOS
				UPDATE 	{$this->user_tbl} set status_id = '{$blocked}', modified_date = now()
				WHERE user_id = ?;
EOS;
				$stmt = $this->db->prepare($query);
				$stmt->execute(array($user_id));
				
				// ====================== jendaigo : start : audit trail for blocked employees ============= //
				//GET PREVIOUS USER DETAILS
				$fields 			= array("user_id", "username", "CONCAT(lname, ', ', fname, IF((mname='NA' OR mname='N/A' OR mname='-' OR mname='/'), '', CONCAT(' ', LEFT(mname, 1), '.'))) as fullname", "status_id", "attempts", "modified_date");
				$table 			  	= $this->user_tbl;
				$where            	= array();
				$where['user_id'] 	= $user_id;
				$prev_details		= $this->select_one($fields, $table, $where);

				//AUDIT TRAIL
				$user_id	= $prev_details['user_id'];
				$username	= $prev_details['username'];
				$module_id	= MODULE_USER;
				$activity	= "Employee ".$prev_details['fullname']." status blocked.";;
				$ip_address	= $_SERVER['REMOTE_ADDR'];
				$user_agent	= $this->input->user_agent();
				
				$val		= array();
				$val		= array($user_id, $username, $module_id, $activity, $ip_address, $user_agent);
				
				$query = <<<EOS
				INSERT INTO	{$this->audit_trail_table} (user_id, username, module_id, activity, ip_address, user_agent)
					 VALUES (?, ?, ?, ?, ?, ?);
EOS;
				$stmt = $this->db->prepare($query);
				$stmt->execute($val);
				$id = $this->db->lastInsertId();

				//AUDIT TRAIL DETAILS
				$audit_table 			= $this->user_tbl;
				$audit_trail_id			= $id;
				$trail_schema			= DB_CORE;
				$action					= AUDIT_UPDATE;
				$field_status_id		= $audit_table."status_id";
				$prev_status_id			= $prev_details['status_id'];
				$curr_status_id			= $blocked;
				$field_attempts			= $audit_table."attempts";
				$prev_attempts			= $prev_details['attempts'];
				$curr_attempts			= $attempts+1;
				$field_modified_date	= $audit_table."modified_date";
				$prev_modified_date		= $prev_details['modified_date'];
				$curr_modified_date		= date('Y-m-d H:i:s');
				
				$val		= array();
				$val		= array($audit_trail_id, $trail_schema, $action, $field_status_id, $prev_status_id, $curr_status_id, 
									$audit_trail_id, $trail_schema, $action, $field_attempts, $prev_attempts, $curr_attempts,
									$audit_trail_id, $trail_schema, $action, $field_modified_date, $prev_modified_date, $curr_modified_date);
				
				$query = <<<EOS
				INSERT INTO	{$this->audit_trail_detail_table} (audit_trail_id, trail_schema, action, field, prev_detail, curr_detail)
					 VALUES (?, ?, ?, ?, ?, ?), (?, ?, ?, ?, ?, ?), (?, ?, ?, ?, ?, ?);
EOS;
				$stmt = $this->db->prepare($query);
				$stmt->execute($val);
				// ====================== jendaigo : end : audit trail for blocked employees ============= //
			}
			if($this->db->inTransaction()) $this->db->commit();
		}
		catch(PDOException $e)
		{
			if($this->db->inTransaction()) $this->db->rollBack();
			$this->rlog_error($e);
			throw $e;
		}
		catch(Exception $e)
		{
			if($this->db->inTransaction()) $this->db->rollBack();
			$this->rlog_error($e);
			throw $e;
		}
	}

	/*ADDED BY RUEL FOR PTIS ONLY
	  GET USER PDS ACCOUNT
	*/
	public function get_user_pds_account($user_id)
	{
		try{
			
			$where = array();
			
			$fields = array("employee_id");
			$where["user_id"] = $user_id;
			
			return $this->select_one($fields, DB_MAIN.'.'.$this->table_associated_accounts,$where);
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
	
	/*ADDED BY AYRAH FOR PTIS ONLY
	  GET USER SYSTEMS FOR DIFFERENT DASHBOARD
	*/
	public function get_user_systems($user_id)
	{
		try
		{

			$fields = array("D.system_code, E.system_name");

			$tables = array(
				'main'	=> array(
					'table'		=> $this->tbl_user_roles,
					'alias'		=> 'A',
				),
				't1'	=> array(
					'table'		=> $this->tbl_module_action_roles,
					'alias'		=> 'B',
					'type'		=> 'left join',
					'condition'	=> 'A.role_code = B.role_code',
				),
				't2'	=> array(
					'table'		=> $this->tbl_module_actions,
					'alias'		=> 'C',
					'type'		=> 'left join',
					'condition'	=> 'B.module_action_id = C.module_action_id',
				),
				't3'	=> array(
					'table'		=> $this->tbl_modules,
					'alias'		=> 'D',
					'type'		=> 'left join',
					'condition'	=> 'C.module_id = D.module_id',
				),
				't4'	=> array(
					'table'		=> $this->tbl_systems,
					'alias'		=> 'E',
					'type'		=> 'left join',
					'condition'	=> 'D.system_code = E.system_code',
				)
			);

			$where                  = array();
			$where['user_id']       = $user_id;
			$where['D.system_code'] = array($value = array('SYSAD'), array("NOT IN"));
			$order_by               = array("E.sort_order_num" => 'ASC');
			$group                  = array("D.system_code");

			return $this->select_all($fields, $tables, $where, $order_by, $group, FALSE);
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
	
	/*ADDED BY SGT FOR 
	  Returns offices assigned to user (comma-separated) 
	*/
	public function get_user_offices($user_id)
	{
		try
		{

			$fields = array("module_id, GROUP_CONCAT(office_id SEPARATOR ',') offices");

			$table = $this->tbl_user_offices;

			$where                  = array();
			$where['user_id']       = $user_id;
			$group_by				= array('module_id');

			return $this->select_all($fields, $table, $where, NULL, $group_by);
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

	/*ADDED BY JAB FOR 
	  Returns param names for login attempts
	*/
	public function get_login_params()
	{
		try
		{

			$fields = array();
			$fields[] = "(SELECT sys_param_value FROM sys_param WHERE sys_param_name = 'FAILED_LOGIN_ATTEMPT_NUM_BAN') AS 'FAILED_LOGIN_ATTEMPT_NUM_BAN'";
			$fields[] = "(SELECT sys_param_value FROM sys_param WHERE sys_param_name = 'FAILED_LOGIN_ATTEMPT_NUM_LOCK') AS 'FAILED_LOGIN_ATTEMPT_NUM_LOCK'";
			$fields[] = "(SELECT sys_param_value FROM sys_param WHERE sys_param_name = 'FAILED_LOGIN_ATTEMPT_BAN_PERIOD') AS 'FAILED_LOGIN_ATTEMPT_BAN_PERIOD'";

			$table = $this->tbl_sys_param;

			$where                   = array();
			$where['sys_param_type'] = 'FAILED_LOGIN_ATTEMPT';

			return $this->select_one($fields, $table, $where);
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

	public function update_user_data($user_id)
	{
		try 
		{
			if(!$this->db->inTransaction()) $this->db->beginTransaction(); 
			$query = <<<EOS
				UPDATE 	{$this->user_tbl} set allow_login_after_date = NOW(), modified_date = modified_date
				WHERE user_id = ?;
EOS;
			$stmt = $this->db->prepare($query);
			$stmt->execute(array($user_id));

			if($this->db->inTransaction()) $this->db->commit();
		}
		catch(PDOException $e)
		{
			if($this->db->inTransaction()) $this->db->rollBack();
			$this->rlog_error($e);
			throw $e;
		}
		catch(Exception $e)
		{
			if($this->db->inTransaction()) $this->db->rollBack();
			$this->rlog_error($e);
			throw $e;
		}
	}

	public function get_settings_arr($setting_type)
	{
		try
		{
			$this->load->model('settings_model');
			$result = $this->settings_model->get_settings_value($setting_type);
			$return = array();
			foreach ($result as $row)
				$return[$row['setting_name']] = $row['setting_value'];
			
			return $return;
		} 
		catch (PDOException $e)
		{
			$this->rlog_error($e);
		}
		catch (Exception $e)
		{			
			$this->rlog_error($e);
		}
	}

	public function update_password_status($user_id, $duration)
	{
		try
		{
			if(intval($duration) <= 0) return TRUE;
			
			$query = <<<EOS
			UPDATE {$this->user_tbl} SET status_id = ? WHERE user_id = ? AND (DATE_ADD(DATE(modified_date), INTERVAL ? DAY) > DATE(NOW()));
EOS;
			
			$stmt = $this->db->prepare($query);
			$stmt->execute(array(EXPIRED, $user_id, $duration));
		} 
		catch (PDOException $e)
		{
			$this->rlog_error($e);
		}
		catch (Exception $e)
		{			
			$this->rlog_error($e);
		}
	}

	public function validate_active_work_experience($user_id)
	{
		try{
			
			
			$fields = array("B.employ_end_date");
			$employ_types = array('AP','JO','WP');
			$tables = array(
				'main'	=> array(
					'table'		=> 'doh_ptis_module.associated_accounts',
					'alias'		=> 'A',
				),
				't1'	=> array(
					'table'		=> 'doh_ptis_module.employee_work_experiences',
					'alias'		=> 'B',
					'type'		=> 'join',
					'condition'	=> 'A.employee_id = B.employee_id AND B.employ_start_date = (SELECT MAX(employ_start_date) FROM doh_ptis_module.employee_work_experiences WHERE employee_id = B.employee_id)',
				)
			);

			$where                  = array();
			$where['A.user_id']       = $user_id;
			// $where['D.system_code'] = array($value = array('SYSAD'), array("NOT IN"));

			return $this->select_one($fields, $tables, $where);
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

	public function update_status_inactive($user_id)
	{
	
		try{
				
			$val = array();
			$where = array();
				
			$val["status_id"] = INACTIVE;
				
			$where["user_id"] = $user_id;
				
			$this->update_data($this->user_tbl, $val, $where);
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

	public function get_active_user_via_email($search_term)
	{
		try{
			$where		= array();
			
			$fields = array("user_id", "username", "email", "password", "raw_password", "salt", "status_id", "CONCAT(fname, ' ', lname) name", "photo", "job_title", "location_code", "org_code", "attempts", "allow_login_after_date", "fname", "mname", "lname", "nickname");
		
			$where[BY_EMAIL]	= $search_term;
			$where["status_id"] = array($value = array(BLOCKED, ACTIVE, EXPIRED, INACTIVE), array("IN"));;
			$where["salt"] 		= "IS NOT NULL";
			$where["password"]	= "IS NOT NULL";
			 
			return $this->select_one($fields, $this->user_tbl, $where);
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
/* End of file auth_model.php */
/*/application/models/auth_model.php*/