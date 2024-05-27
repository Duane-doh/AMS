<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migrate_user_model extends SYSAD_Model {
                
	var $user_table       = "users";
	var $status_table     = "param_status";
	var $user_roles_table = "user_roles";
	var $org_table        = "organizations";
	var $roles_table      = "roles";
	
	var $user_mig_table   = "user_mig";

	public $tbl_employee_personal_info = "employee_personal_info";
	public $tbl_employee_work_experiences = "employee_work_experiences";
	public $tbl_param_offices = "param_offices";
	public $tbl_employee_contacts  = "employee_contacts";
	public $tbl_associated_accounts  = "associated_accounts";
	public $db_main                    = DB_MAIN;

	
	public function get_migrated_users()
	{
		try
		{
			$query = <<<EOS
				SELECT * FROM $this->user_table WHERE agency_employee_id IS NOT NULL AND username != 'superuser'
EOS;

			RLog::info($query);
			
			$stmt = $this->db->prepare($query);
			$stmt->execute();
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$return = array();
			foreach ($result as $row)
			{

				RLog::error("Update password of {$row['username']} with password {$row['agency_employee_id']}");
				
				$this->_update_password($row['username'], $row['agency_employee_id']);
				
				$this->_insert_associated_account($row['employee_id'], $row['user_id'], $row['agency_employee_id']);
				
				$roles = array('PERSONNEL'); // personnel only
				$this->_insert_user_roles($roles, $row['user_id']);
				
			}
				
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

	private function _update_password($username, $password)
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
			
			$where["username"] = $username;
			
			$this->update_data('users', $val, $where);
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
	
	private function _insert_associated_account($employee_id, $user_id, $agency_employee_id)
	{
		try
		{		
			
			$fields                			= array();
			$fields["employee_id"] 			= $employee_id;
			$fields["user_id"]     			= $user_id;
			$fields["agency_employee_id"]   = $agency_employee_id;

			$table                 			= 'doh_ptis_module.associated_accounts';
			
			$this->insert_data($table, $fields, FALSE);
			
			return TRUE;
			
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
	
	private function _insert_user_roles($roles, $user_id)
	{
		
		try
		{
			foreach ($roles as $role)
			{
				$params	= array();
				$params["user_id"] = filter_var($user_id, FILTER_SANITIZE_NUMBER_INT);
				$params["role_code"] = filter_var($role, FILTER_SANITIZE_STRING);
				
				$this->insert_data($this->user_roles_table, $params);
				
			}
				
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
	
	
	
	public function insert_user($params)
	{
		try
		{		
			$val = array();
			
			$salt = gen_salt(TRUE);
					
			$val["lname"] = filter_var($params['lname'], FILTER_SANITIZE_STRING);
			$val["fname"] = filter_var($params['fname'], FILTER_SANITIZE_STRING);
			$val["mname"] = filter_var($params['mname'], FILTER_SANITIZE_STRING);
			$val["nickname"] = ISSET($params['nickname']) ? filter_var($params['nickname'], FILTER_SANITIZE_STRING) : NULL;
			$val["gender"] = filter_var($params['gender'], FILTER_SANITIZE_STRING);
			$val["contact_no"] = ISSET($params['contact_no']) ? filter_var($params['contact_no'], FILTER_SANITIZE_NUMBER_INT) : NULL;
			$val["mobile_no"] = ISSET($params['mobile_no']) ? filter_var($params['mobile_no'], FILTER_SANITIZE_NUMBER_INT) : NULL;
			$val["email"] = filter_var($params['email'], FILTER_SANITIZE_STRING);
			$val["org_code"] = filter_var($params["org"], FILTER_SANITIZE_STRING);
			$val["job_title"] = filter_var($params['job_title'], FILTER_SANITIZE_STRING);
			$val["photo"] = !EMPTY($params['image']) ? filter_var($params['image'], FILTER_SANITIZE_STRING) : NULL;
			$val["status_id"] = ISSET($params["status"]) ? filter_var($params["status"], FILTER_SANITIZE_NUMBER_INT) : INACTIVE;			  
			$val["password"] = in_salt($params["password"], $salt, TRUE);
			$val["raw_password"] = base64_url_encode($params["password"]);
			$val["salt"] = $salt;
			$val["created_by"] = $this->session->userdata("user_id");
			$val["created_date"] = date('Y-m-d H:i:s');
			
			if(ISSET($params["username"]))
				$val["username"] = filter_var($params["username"], FILTER_SANITIZE_STRING);
			
			if(ISSET($params["send_email"]))
			  $val["mail_flag"] = filter_var($params["send_email"], FILTER_SANITIZE_NUMBER_INT);
			
			$user_id = $this->insert_data($this->user_table, $val, TRUE);
			
			if(!EMPTY($user_id) && !EMPTY($params["role"]))
				$this->__insert_user_roles($params["role"], $user_id);
			
			return $user_id;
			
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
	
	
	public function update_user($params)
	{
		try
		{
			$val = array();
			$where = array();

			$user_id = filter_var($params["user_id"], FILTER_SANITIZE_NUMBER_INT);
			
			$val["lname"] = filter_var($params['lname'], FILTER_SANITIZE_STRING);
			$val["fname"] = filter_var($params['fname'], FILTER_SANITIZE_STRING);
			$val["mname"] = filter_var($params['mname'], FILTER_SANITIZE_STRING);
			$val["nickname"] = filter_var($params['nickname'], FILTER_SANITIZE_STRING);
			$val["gender"] = filter_var($params['gender'], FILTER_SANITIZE_STRING);
			$val["contact_no"] = filter_var($params['contact_no'], FILTER_SANITIZE_NUMBER_INT);
			$val["mobile_no"] = filter_var($params['mobile_no'], FILTER_SANITIZE_NUMBER_INT);
			$val["email"] = filter_var($params['email'], FILTER_SANITIZE_STRING);
 			$val["org_code"] = filter_var($params["org"], FILTER_SANITIZE_STRING);
			$val["job_title"] = filter_var($params['job_title'], FILTER_SANITIZE_STRING);
			$val["photo"] = !EMPTY($params['image']) ? filter_var($params['image'], FILTER_SANITIZE_STRING) : NULL;
			$val["status_id"] = ISSET($params["status"]) ? filter_var($params["status"], FILTER_SANITIZE_NUMBER_INT) : INACTIVE;
			$val["modified_by"]	= $this->session->userdata("user_id");
			if($val['status_id']) {
				$query = <<<EOS
				SELECT status_id FROM $this->user_table WHERE user_id = ? AND status_id = ?
EOS;
	
				$stmt = $this->query($query, array($user_id, BLOCKED), FALSE);

				if(!EMPTY($stmt)) $val['attempts'] = 0;
			}

			if(!EMPTY($params["password"])){
				$salt = gen_salt(TRUE);
				$val["password"] = in_salt($params["password"], $salt, TRUE);
				$val["raw_password"] = base64_url_encode($params["password"]);
				$val["salt"] = $salt;
			}
			
			if(ISSET($params["username"]))
				$val["username"] = filter_var($params["username"], FILTER_SANITIZE_STRING);
			
			$where["user_id"] = $user_id;
				
			$this->update_data($this->user_table, $val, $where);
			
			if(ISSET($params["role"]) && !EMPTY($params["role"])){
				$this->delete_data($this->user_roles_table, $where);
				$this->__insert_user_roles($params["role"], $user_id);
			}
			
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

	

	public function validate_current_password($id){
		
		try
		{
			$where = array();
			
			$fields = array("raw_password");
			$where["user_id"] = $id;
			
			return $this->select_one($fields, $this->user_table, $where);
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
	
	
	public function get_user_salt(){
	
		try
		{
			$where = array();
	
			$fields = array("salt");
			$where["user_id"] = $this->session->userdata('user_id');
	
			return $this->select_one($fields, $this->user_table, $where);
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
	
	public function update_password_status($duration)
	{
		try
		{
			if(intval($duration) <= 0) return TRUE;
			
			$query = <<<EOS
			UPDATE `users` SET status_id = ? WHERE status_id = ? AND (DATE_ADD(DATE(modified_date), INTERVAL ? DAY) > DATE(NOW()));
EOS;
			$stmt = $this->db->prepare($query);
			$stmt->execute(array(EXPIRED, ACTIVE, $duration));
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
	
	
	/*
	 * Accepts setting type as parameter
	 * returns array with settting name as keys
	 * 
	 */
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
	
	public function get_email_params()
	{
		try
		{
			$smtp = SYS_PARAM_TYPE_SMTP;
			$query = <<<EOS
			SELECT * FROM sys_param WHERE sys_param_type = '{$smtp}'
EOS;
			$stmt = $this->db->prepare($query);
			$stmt->execute();
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$return = array();
			foreach ($result as $row)
				$return[$row['sys_param_name']] = $row['sys_param_value'];
				
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

	public function get_pds_employee_list($employee_id = NULL)
	{
		try
		{
			$val = array();
			$where = "";
			$multiple = TRUE;
			if(isset($employee_id ))
			{
				$key 	= $this->get_hash_key('A.employee_id');
				$where = " WHERE ".$key." = ?";
				$val[] = $employee_id;
				$multiple = FALSE;
			}
			$query = <<<EOS
				SELECT *
				FROM $this->db_main.$this->tbl_employee_personal_info A
				$where
EOS;
		
			$stmt = $this->query($query, $val, $multiple);
			
			return $stmt;
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

	public function get_pds_employee_contacts($employee_id)
	{
		try
		{
			$val = array($employee_id);
			$key 	= $this->get_hash_key('employee_id');
			$query = <<<EOS
				SELECT *
				FROM $this->db_main.$this->tbl_employee_contacts
				WHERE $key = ?
EOS;
		
			$stmt = $this->query($query, $val, TRUE);
			
			return $stmt;
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
	public function check_username($username)
	{
		try
		{
			$val = array($username);
			$query = <<<EOS
				SELECT *
				FROM $this->user_table
				WHERE username = ?
EOS;
		
			$stmt = $this->query($query, $val, FALSE);
			
			return $stmt;
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



	public function get_pds_employee_office($employee_id)
	{
		try
		{
			$key 	= $this->get_hash_key('A.employee_id');
			$val 	= array($employee_id, YES);
			$query 	= <<<EOS
				SELECT B.org_code
				FROM $this->db_main.$this->tbl_employee_work_experiences A
				LEFT JOIN $this->db_main.$this->tbl_param_offices B ON A.employ_office_id = B.office_id
				WHERE $key = ?  
				AND A.active_flag = ?
EOS;
	
			$stmt = $this->query($query, $val, FALSE);
		
			return $stmt;
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
}