<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Users_model extends SYSAD_Model {
                
	var $user_table       = "users";
	var $status_table     = "param_status";
	var $user_roles_table = "user_roles";
	var $org_table        = "organizations";
	var $roles_table      = "roles";
	var $user_offices_table = "user_offices"; //NCOCAMPO add user_offices_table

	public $tbl_employee_personal_info = "employee_personal_info";
	public $tbl_employee_work_experiences = "employee_work_experiences";
	public $tbl_param_offices = "param_offices";
	public $tbl_employee_contacts  = "employee_contacts";
	public $tbl_associated_accounts  = "associated_accounts";
	public $db_main                    = DB_MAIN;
	
	public function get_user_list($aColumns, $bColumns, $params)
	{
		try
		{
			$val = array(PENDING, APPROVED, DISAPPROVED, DELETED, ANONYMOUS_ID);
			
			// $cColumns = array("A.user_id", "A.nickname", "A.email", "B.status", "CONCAT(A.fname, ' ', A.lname)");

			/* For Advanced Filters */
			// $cColumns = array("A-username", "A-fname", "A-lname", "A-email", "B-status");
			// ====================== jendaigo : start : include filtering of role_code ============= //
			$cColumns = array("A-username", "A-fname", "A-lname", "A-email", "C-role_code", "B-status");
			// ====================== jendaigo : end : include filtering of role_code ============= //
			
			$fields = str_replace(" , ", " ", implode(", ", $aColumns));
			
			$sWhere = $this->filtering($cColumns, $params, TRUE);
			$sOrder = $this->ordering($bColumns, $params);
			$sLimit = $this->paging($params);
			
			$filter_str = $sWhere["search_str"];
			$filter_params = $sWhere["search_params"];

			/*
			$query = <<<EOS
				SELECT SQL_CALC_FOUND_ROWS $fields 
				FROM $this->user_table A, $this->status_table B
				WHERE A.status_id = B.status_id 
				AND A.status_id NOT IN (?,?,?,?)
				AND user_id != ?
				$filter_str
	        	$sOrder
	        	$sLimit
EOS;
			*/
			// ====================== jendaigo : start : include user_role_table in the query ============= //
			$query = <<<EOS
				SELECT SQL_CALC_FOUND_ROWS $fields 
				FROM $this->user_table A 
				JOIN $this->status_table B
				  ON A.status_id = B.status_id
				JOIN $this->user_roles_table C
				  ON A.user_id = C.user_id
				  AND C.user_id NOT IN (					
					SELECT user_id FROM user_roles WHERE 
				  role_code IN ('SUPER_USER', 'SUPER_ADMIN')
					AND user_id IN (SELECT user_id FROM user_roles WHERE user_id = C.user_id HAVING COUNT(*) = 2) )
				WHERE A.status_id NOT IN (?,?,?,?)
				AND A.user_id != ?
				$filter_str
				GROUP BY username
	        	$sOrder
	        	$sLimit
EOS;
			// ====================== jendaigo : start : include user_role_table in the query ============= //
			$val = array_merge($val,$filter_params);
			//  echo"<pre>";print_r($query);print_r($val);die();
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
	
	public function get_user_details($user_id)
	{
		try
		{
// 			$query = <<<EOS
// 				SELECT A.*, GROUP_CONCAT(B.role_code SEPARATOR ',') roles, C.name org_name
// 				FROM $this->user_table A
// 				JOIN $this->user_roles_table B ON  A.user_id = B.user_id
// 				LEFT JOIN $this->org_table C ON A.org_code = C.org_code
// 				WHERE A.user_id = ?
// EOS;

//=================NCOCAMPO:IF DEPARTMENT/AGENCY = NULL: GET THE ACTIVE WORK EXPERIENCE OFFICE :START 11/15/2023=================
$query = <<<EOS
				SELECT A.*, GROUP_CONCAT(B.role_code SEPARATOR ',') roles, F.employ_office_name org_name, A.org_code, H.contact_value email

				FROM $this->user_table A
				JOIN $this->user_roles_table B ON  A.user_id = B.user_id
				LEFT JOIN $this->org_table C ON A.org_code = C.org_code
				LEFT JOIN $this->db_main.$this->tbl_associated_accounts D
					ON D.user_id = A.user_id
				LEFT JOIN $this->db_main.$this->tbl_employee_personal_info E
					ON E.employee_id = D.employee_id
				LEFT JOIN $this->db_main.$this->tbl_employee_work_experiences F 
					ON  F.employee_id = E.employee_id
				LEFT JOIN $this->db_main.$this->tbl_param_offices G
					ON F.employ_office_id = G.office_id 
				LEFT JOIN $this->db_main.$this->tbl_employee_contacts H
					ON E.employee_id = H.employee_id
				WHERE A.user_id = ? AND F.active_flag = 'Y' AND H.contact_type_id = '2'
EOS;
//=================NCOCAMPO:F DEPARTMENT/AGENCY = NULL: GET THE ACTIVE WORK EXPERIENCE OFFICE : END 11/15/2023=================	
			$stmt = $this->query($query, array($user_id), FALSE);
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

	// davcorrea : 11/06/2023 : include suffix :START
	public function get_user_suffix($user_id)
	{
		try
		{
			$query = <<<EOS
				SELECT B.ext_name 
				FROM doh_ptis_module.$this->tbl_associated_accounts A 
				JOIN doh_ptis_module.$this->tbl_employee_personal_info B ON  A.employee_id = B.employee_id
				WHERE A.user_id = ?
EOS;
			$stmt = $this->query($query, array($user_id), FALSE);
			
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
	// END

	//===============NCOCAMPO: ADD DTR_AO, LEAVE_AO AND IMM_SUP TO USER_MGMT: START 11/15/2023===============
	public function get_dtr_ao($user_id)
	{
		try
		{
			$query = <<<EOS
				SELECT IFNULL(GROUP_CONCAT(DISTINCT CONCAT(F.fname,' ', F.lname) SEPARATOR ', '), 'No assigned Approving Officer')dtr_ao 
				FROM $this->user_table A
				JOIN doh_ptis_module.$this->tbl_associated_accounts B
					ON B.user_id = A.user_id
				LEFT JOIN doh_ptis_module.$this->tbl_employee_personal_info C
					ON C.employee_id = B.employee_id
				LEFT JOIN doh_ptis_module.$this->tbl_associated_accounts D
					ON D.employee_id = C.employee_id
				LEFT JOIN $this->user_offices_table E
					ON D.user_id = E.office_id 
				LEFT JOIN $this->user_table F	
					ON F.user_id = E.user_id
				LEFT JOIN $this->user_roles_table G
					ON F.user_id = G.user_id
				WHERE A.user_id = ? AND E.module_id = '51' AND F.status_id = '1' 
				AND NOT EXISTS(
					SELECT 1
					FROM user_roles G
					WHERE F.user_id = G.user_id 
					AND (G.role_code = 'SUPER_USER' OR G.role_code = 'SUPER_ADMIN')
					)
				AND EXISTS(
					SELECT 1
					FROM user_roles G
					WHERE F.user_id = G.user_id 
					AND G.role_code = 'AO'
					)
EOS;
			$stmt = $this->query($query, array($user_id), FALSE);
			
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
	public function get_leave_ao($user_id)
	{
		try
		{
			$query = <<<EOS
				SELECT IFNULL(GROUP_CONCAT(DISTINCT CONCAT(F.fname,' ', F.lname) SEPARATOR ', '), 'No assigned Approving Officer') leave_ao 
				FROM $this->user_table A
				JOIN doh_ptis_module.$this->tbl_associated_accounts B
					ON B.user_id = A.user_id
				LEFT JOIN doh_ptis_module.$this->tbl_employee_personal_info C
					ON C.employee_id = B.employee_id
				LEFT JOIN doh_ptis_module.$this->tbl_associated_accounts D
					ON D.employee_id = C.employee_id
				LEFT JOIN $this->user_offices_table E
					ON D.user_id = E.office_id 
				LEFT JOIN $this->user_table F	
					ON F.user_id = E.user_id
				LEFT JOIN $this->user_roles_table G
					ON F.user_id = G.user_id
				WHERE A.user_id = ? AND G.role_code = 'LVAPPOFF' AND F.status_id = '1' AND E.module_id = '53'
				AND NOT EXISTS(
					SELECT 1
					FROM user_roles G
					WHERE F.user_id = G.user_id 
					AND (G.role_code = 'SUPER_USER' OR G.role_code = 'SUPER_ADMIN')
					)
				AND EXISTS(
					SELECT 1
					FROM user_roles G
					WHERE F.user_id = G.user_id 
					AND G.role_code = 'LVAPPOFF'
					)
EOS;
			$stmt = $this->query($query, array($user_id), FALSE);
			
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

	public function get_imm_sup($user_id)
	{
		try
		{
			$query = <<<EOS
				SELECT IFNULL(GROUP_CONCAT(DISTINCT CONCAT(F.fname,' ', F.lname) SEPARATOR ', '), 'No assigned Approving Officer') imm_sup 
				FROM $this->user_table A
				JOIN doh_ptis_module.$this->tbl_associated_accounts B
					ON B.user_id = A.user_id
				LEFT JOIN doh_ptis_module.$this->tbl_employee_personal_info C
					ON C.employee_id = B.employee_id
				LEFT JOIN doh_ptis_module.$this->tbl_associated_accounts D
					ON D.employee_id = C.employee_id
				LEFT JOIN $this->user_offices_table E
					ON D.user_id = E.office_id 
				LEFT JOIN $this->user_table F	
					ON F.user_id = E.user_id
				LEFT JOIN $this->user_roles_table G
					ON F.user_id = G.user_id
				WHERE A.user_id = ? 
				AND G.role_code = 'IMMSUP' 
				AND F.status_id = '1' 
				AND E.module_id = '53'
				AND NOT EXISTS(
					SELECT 1
					FROM user_roles G
					WHERE F.user_id = G.user_id 
					AND (G.role_code = 'SUPER_USER' OR G.role_code = 'SUPER_ADMIN')
					)
EOS;
			$stmt = $this->query($query, array($user_id), FALSE);
			
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
//===============NCOCAMPO: ADD DTR_AO, LEAVE_AO AND IMM_SUP TO USER_MGMT: END 11/15/2023===============
	public function filtered_length($aColumns, $bColumns, $params)
	{
		try
		{
			$this->get_user_list($aColumns, $bColumns, $params);
			
			$query = <<<EOS
				SELECT FOUND_ROWS() cnt
EOS;
	
			$stmt = $this->query($query, NULL, FALSE);
		
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
	
	
	public function total_length()
	{
		try
		{
			$where = array();
			
			$fields = array("COUNT(user_id) cnt");
			
			$where["status_id"]["!="] = INACTIVE;
			$where["user_id"]["!="] = ANONYMOUS_ID;
			
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
	
	public function get_specific_user($id)
	{
		try
		{
			$where = array();
			$fields = array("*");
			$where["user_id"] = $id;
				
			return $this->select_all($fields, $this->user_table, $where);
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
	private function __insert_user_roles($roles, $user_id)
	{
		
		try
		{
			foreach ($roles as $role):
				$params	= array();
				$params["user_id"] = filter_var($user_id, FILTER_SANITIZE_NUMBER_INT);
				$params["role_code"] = filter_var($role, FILTER_SANITIZE_STRING);
				
				$this->insert_data($this->user_roles_table, $params);
				
			endforeach;
				
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
	
	public function update_status($params)
	{
		try
		{
			$val = array();
			$where = array();
			
			$val["status_id"] = filter_var($params['status_id'], FILTER_SANITIZE_NUMBER_INT);
			$val["reason"] = ISSET($params['reason']) ? $params['reason'] : NULL;
			$val["modified_by"]	= $this->session->userdata("user_id");
			
			$where['user_id'] = $params['user_id'];
			
			$this->update_data($this->user_table, $val, $where);
			
			if(ISSET($params["role"]) && !EMPTY($params["role"])){
				$this->__insert_user_roles($params["role"], $params['user_id']);
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
	
	
	public function check_email_exist($email, $id = 0)
	{
		try
		{
			$val = array();
			$val[] = $email;
			$val[] = DISAPPROVED;
			$val[] = DELETED;
			$where = "";
			
			if($id){
				$where.=" AND user_id = ? 
				  OR (SELECT IF(
					EXISTS(SELECT email FROM users
						WHERE email = ? AND status_id NOT IN(?,?)), 0, 1))";
				$val[] = $id;
				$val[] = $email;
				$val[] = DISAPPROVED;
				$val[] = DELETED;
			}
			
			$query = <<<EOS
				SELECT IF(
				EXISTS(
				  SELECT email FROM users
				  WHERE email =  ? AND status_id NOT IN(?,?) 
				  $where
				), 1, 0) email_exist
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
	
	public function get_user_account_history($user_id , $limit = 0)
	{
		try
		{
			$query = "";
			$val = array(
				$user_id,
				$user_id
			);
			
			$limit_by = ($limit === 0) ? "" : " LIMIT " .$limit;
			
			$query = <<<EOS
				SELECT * FROM users WHERE user_id = ?
				UNION
				SELECT * FROM user_history WHERE user_id = ? ORDER BY modified_date DESC
				$limit_by
EOS;
			$stmt = $this->query($query, $val);
			
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
	
	public function check_password_history($user_id, $password)
	{
		try
		{
			//$password = filter_var($password, FILTER_SANITIZE_STRING);
			$x = get_setting(PASSWORD_CONSTRAINTS, PASS_CONS_HISTORY);
			$user_history = $this->get_user_account_history($user_id, $x);
			foreach ($user_history as $user_info)
			{
				// ENCRYPT THE PASSWORD
				$hashed_password = base64_url_decode($user_info["raw_password"]);
				
				if($password == $hashed_password)
				{
					throw new Exception('Password must not match any of the user\'s previous ' .$x . ' passwords.');
				}
			}
		} 
		catch(PDOException $e)
		{
			self::rlog_error($e);
			
			throw $e;
		}
		catch(Exception $e)
		{
			self::rlog_error($e);
			
			throw $e;			
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
	
	public function get_accounts_to_remind($duration)
	{
		try
		{
			if(intval($duration) <= 0) return TRUE;
			
			$val = array(
				ACTIVE, 
				$duration
			);
			
			$query = <<<EOS
			SELECT user_id, username,DATEDIFF(DATE(NOW()),DATE(modified_date)) as remaining, CONCAT(fname, ' ',substr(mname, 1,1) , '. ', lname) as full_name, gender, email 
			FROM users WHERE status_id = ? AND (DATEDIFF(DATE(NOW()),DATE(modified_date)) <= ?)
EOS;
			$stmt = $this->query($query, $val);
			
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
			/*=================== marvin : start : remove existing user select ===================*/
			else
			{
				$where = " WHERE A.employee_id NOT IN(SELECT employee_id FROM $this->db_main.$this->tbl_associated_accounts)";
			}
			/*=================== marvin : end : remove existing user select ===================*/
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

	public function insert_associated_account($employee_id,$user_id,$password)
	{
		try
		{		
			$fields               			= array();
			$fields["employee_id"] 			= $employee_id;
			$fields["user_id"]     			= $user_id;
			
			//marvin
			$fields["agency_employee_id"]	= $password;

			$table                 			= $this->db_main.".".$this->tbl_associated_accounts;
			
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

	public function get_pass_history($user_id=NULL){
		try
		{


			$query = <<<EOS
				SELECT DISTINCT raw_password
				FROM $this->user_history_table
				WHERE user_id = ?
				ORDER BY modified_date DESC;
EOS;
			return $this->query($query, array($user_id));
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

	public function update_user_pass($params)
	{
		try
		{
			$user_id = $params['user_id'];
			$salt = gen_salt(TRUE);
			$val = array();
			$val["password"] = in_salt($params["new_password"], $salt, TRUE);
			$val["raw_password"] = base64_url_encode($params["new_password"]);
			$val["salt"] = $salt;
			$val["modified_by"]	= $user_id;
			$val['status_id'] = ACTIVE;

			$where = array("user_id" => $user_id);

			$this->update_data($this->user_table, $val, $where);
		
			
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
	
	public function get_specific_role_code($role_code)
	{
		try
		{
			$query = <<<EOS
				SELECT role_name
				FROM $this->roles_table
				WHERE role_code = ?
EOS;
	
			$stmt = $this->query($query, array($role_code), FALSE);
				
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