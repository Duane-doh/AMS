<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Permissions_model extends SYSAD_Model {

	var $param_scope_table        = "param_scopes";
	var $system_table             = "systems";
	var $action_table             = "actions";
	var $module_table             = "modules";
	var $module_action_table      = "module_actions";
	var $module_scope_table       = "module_scopes";
	var $module_action_role_table = "module_action_roles";
	var $module_scope_role_table  = "module_scope_roles";
	var $system_role_table        = "system_roles";
	var $role_table               = "roles";
	var $sys_param_table          = "sys_param";
	
	
	public function __construct() {
		parent::__construct(); 
	}		
	
	public function get_module_action_scopes($app_id=NULL)
	{
		try 
		{
			$where = ( ! EMPTY($app_id) && $app_id != 'all') ? ' AND a.system_code = \''.filter_var($app_id, FILTER_SANITIZE_STRING).'\'' : ''; 
			
			$sql   =<<<EOS
			SELECT a.sort_order, f.system_name, a.parent_module_id,
				   a.module_id,a.system_code,a.level,
				   IF(a.parent_module_id IS NOT NULL, LPAD(a.module_name, CHAR_LENGTH(a.module_name) + 5, SPACE(5)), a.module_name) module_name,
				   GROUP_CONCAT(DISTINCT b.module_action_id) as available_action_per_module, GROUP_CONCAT(DISTINCT c.name) as action_name,
				   GROUP_CONCAT(DISTINCT d.scope_id) as available_scope_per_module, GROUP_CONCAT(DISTINCT e.scope) as scope_name
				FROM $this->module_table a
					/* JOIN TO GET THE AVAILABLE ACTION PER MODULE */
					LEFT JOIN $this->module_action_table b
					ON   a.module_id = b.module_id
					LEFT JOIN $this->action_table c
					ON   b.action_id = c.action_id
					/* JOIN TO GET THE AVAILABLE ACTION PER MODULE */
					LEFT JOIN $this->module_scope_table d
					ON   a.module_id = d.module_id
					LEFT JOIN $this->param_scope_table 	e
					ON   d.scope_id = e.scope_id
					/* JOIN TO GET SYSTEM NAME */
					JOIN $this->system_table f
					ON   a.system_code = f.system_code

				WHERE a.sort_order <> 0 AND a.enabled_flag = 1
				$where										
			GROUP BY a.module_id
			ORDER BY a.sort_order 
EOS;
			$stmt = $this->query($sql);
				
			return $stmt;
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
	
	
	public function get_modules($module_id=NULL, $app_id=NULL)
	{
		try
		{
			$where  = array();
			$fields = array('module_id', 'system_code', 'module_name');
			
			if(! EMPTY($module_id)):
				$where['module_id']  = filter_var($module_id, FILTER_SANITIZE_NUMBER_INT);
			endif;
			
			if(! EMPTY($app_id) && $app_id != 'all'):
				$where['system_code'] = filter_var($app_id, FILTER_SANITIZE_STRING);
			endif;
		
			return $this->select_all($fields, $this->module_table, $where);
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
	
	public function get_role_action($role_code, $group_concat=false, $system_code = NULL)
	{
		try
		{
			$role_code   = filter_var($role_code, FILTER_SANITIZE_STRING);
			if($group_concat) {
				$db = static::get_connection();
				$system_code = filter_var($system_code, FILTER_SANITIZE_STRING);
				$val = array($role_code,$system_code);

				$query = <<<EOS
					SELECT GROUP_CONCAT(module_action_id) module_action_id, role_code
					FROM $this->module_action_role_table 
					WHERE role_code = ? AND 
					module_action_id IN (SELECT 
									            module_action_id
									        FROM
									            $this->module_action_table
									        WHERE
									            module_id IN (SELECT 
												                    module_id
												                FROM
												                    $this->module_table
												                WHERE
												                    system_code = ?
												          		)
										);
EOS;
				
				$stmt = $db->prepare($query);
				$stmt->execute($val);
				return $stmt->fetch(PDO::FETCH_ASSOC);

			} else {
				$where              = array();
				$fields             = array("module_action_id, role_code");
				$where["role_code"] = $role_code;
				return $this->select_all($fields, $this->module_action_role_table, $where);
			}

			
			
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
	
	public function get_role_scope($role_code, $group_concat=false)
	{
		try
		{
			$role_code 	=  filter_var($role_code, FILTER_SANITIZE_STRING);;
			
			$fields 	= ($group_concat) ? array("scope_id, role_code, module_id") : array("scope_id, role_code, module_id");
			
			$stmt = $this->select_all($fields, $this->module_scope_role_table, array('role_code' => $role_code), array(), array("scope_id, role_code, module_id"));
			
			if($group_concat){
				$return = array();
				
				foreach($stmt as $key => $val):
					$return[$val['module_id']] = $val['scope_id'];
				endforeach;
				
				return $return;
			} else {
				return $stmt;
			}
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
	
	public function delete_action_roles($role_code, $system_code)
	{
		try
		{			
			$db = static::get_connection();
			
			$where = "";
			$val = array();
			$val[] = $role_code;
			
			if($system_code != 'all'){
				$where = " AND C.system_code = ? ";
				$val[] = $system_code;
			}
			
			$query = <<<EOS
				DELETE A FROM $this->module_action_role_table A 
				JOIN $this->module_action_table B ON A.module_action_id = B.module_action_id
				JOIN $this->module_table C ON B.module_id = C.module_id
				WHERE A.role_code = ?
				$where
EOS;
			self::rlog_info('QUERY ' . $query);
			self::rlog_info('VALUES ' . var_export($val, TRUE));
			
			$stmt = $db->prepare($query);
			$stmt->execute($val);
			
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
	
	public function delete_scope_roles($role_code, $system_code)
	{
		try
		{	
			$db = static::get_connection();
			
			$where = "";
			$val = array();
			$val[] = $role_code;
			
			if($system_code != 'all'){
				$where = " AND B.system_code = ? ";
				$val[] = $system_code;
			}
			
			$query = <<<EOS
				DELETE A FROM $this->module_scope_role_table A
				JOIN $this->module_table B ON A.module_id = B.module_id
				WHERE A.role_code = ?
				$where
EOS;
			self::rlog_info('QUERY ' . $query);
			self::rlog_info('VALUES ' . var_export($val, TRUE));
			
			$stmt = $db->prepare($query);
			$stmt->execute($val);
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
	
	public function insert_action_roles($module_actions, $role_code)
	{
		try {
			$db = static::get_connection();
			$fields = '(module_action_id, role_code)';
			$values = '';
			$val    = array();
			
			foreach($module_actions as $key => $mod_val):
				foreach($mod_val as $k => $v):
					$values .= '(?, ?), ';
					$val[]   = $v;
					$val[]   = $role_code;
				endforeach;
			endforeach;
			
			$values = rtrim($values, ', ');
			$query  = <<<EOS
					INSERT INTO $this->module_action_role_table
					$fields
					VALUES
					$values
EOS;
			self::rlog_info('QUERY ' . $query);
			self::rlog_info('VALUES ' . var_export($val, TRUE));
			
			$stmt = $db->prepare($query);
			$stmt->execute($val);
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
	
	public function insert_scope_roles($module_scopes, $role_code)
	{
		try {
			$db = static::get_connection();
			$fields = '(module_id, scope_id, role_code)';
			$values = '';
			$val    = array();
				
			foreach($module_scopes as $key => $mod_val):
				foreach($mod_val as $k => $v):
					$values .= '(?, ?, ?), ';
					$val[]   = $key;
					$val[]   = $v;
					$val[]   = $role_code;
				endforeach;
			endforeach;
			
			$values = rtrim($values, ', ');
			$query  = <<<EOS
					INSERT INTO $this->module_scope_role_table
					$fields
					VALUES
					$values
EOS;
			self::rlog_info('QUERY ' . $query);
			self::rlog_info('VALUES ' . var_export($val, TRUE));
			
			$stmt = $db->prepare($query);
			$stmt->execute($val);
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
	

	public function get_system_roles($system_code)
	{
		try
		{
			$val = array($system_code);
			
			$query = <<<EOS
				SELECT A.role_code value, B.role_name text
				FROM $this->system_role_table A, $this->role_table B
				WHERE A.role_code = B.role_code
				AND A.system_code = ?
				ORDER BY A.role_code ASC
EOS;
			$stmt = $this->query($query, $val);
				
			return $stmt;
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
	
	public function get_module($org_code)
	{
		try
		{
			$where = array();
			
			$fields = array("org_code", "short_name", "name", "website", "email", "phone", "fax", "org_parent");
			$where["org_code"] = $org_code;
				
			return $this->select_one($fields, $this->org_table, $where);
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
	
	
	public function get_orgs()
	{
		try
		{
			$query = <<<EOS
				SELECT org_code, IF(org_parent IS NOT NULL, CONCAT("&emsp;&emsp;", name), name) office
				FROM $this->org_table
				GROUP BY org_code, org_parent
EOS;

			$stmt = $this->query($query);
			
			return $stmt;
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
	
	
	public function get_other_orgs($exclude)
	{
		try
		{
			$query = <<<EOS
				SELECT org_code value, name text
				FROM $this->org_table
				WHERE org_code != ?
EOS;
			$stmt = $this->query($query, array($exclude));
			
			return $stmt;
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
	
	public function get_org_list($aColumns, $bColumns, $params)
	{
		try
		{
			$fields = str_replace(" , ", " ", implode(", ", $aColumns));
		
			$sWhere = $this->filtering($bColumns, $params, FALSE);
			$sOrder = $this->ordering($bColumns, $params);
			$sLimit = $this->paging($params);
			
			$filter_str = $sWhere["search_str"];
			$filter_params = $sWhere["search_params"];
		
			$query = <<<EOS
				SELECT SQL_CALC_FOUND_ROWS $fields
				FROM $this->org_table a
					LEFT JOIN $this->org_table b ON a.org_code = b.org_parent
				$filter_str
	        	$sOrder
	        	$sLimit
EOS;
	
			$stmt = $this->query($query, $filter_params);
			
			return $stmt;
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
	
	
	public function filtered_length($aColumns, $bColumns, $params)
	{
		try
		{
			$this->get_org_list($aColumns, $bColumns, $params);
		
			$query = <<<EOS
				SELECT FOUND_ROWS() cnt
EOS;
			$stmt = $this->query($query, NULL, FALSE);
			
			return $stmt;
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
	
	
	public function total_length()
	{
		try
		{
			$fields = array("COUNT(org_code) cnt");
			
			return $this->select_one($fields, $this->org_table);
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
	
	
	public function insert_org($params)
	{
		try
		{
			$val = array();

			$val["org_code"]     = filter_var($params['org_code'], FILTER_SANITIZE_STRING);
			
			if(! EMPTY($params['parent_org_code']))
				$val["org_parent"] 	= filter_var($params['parent_org_code'], FILTER_SANITIZE_STRING);
			
			$val["short_name"] 	  = filter_var($params['org_short_name'], FILTER_SANITIZE_STRING);
			$val["name"] 		  = filter_var($params['org_name'], FILTER_SANITIZE_STRING);
			$val["website"] 	  = filter_var($params['website'], FILTER_SANITIZE_URL);
			$val["email"] 		  = filter_var($params['email'], FILTER_SANITIZE_EMAIL);
			$val["phone"]	      = filter_var($params['tel_no'], FILTER_SANITIZE_STRING);
			$val["fax"] 		  = filter_var($params['fax_no'], FILTER_SANITIZE_STRING);
			$val["location_code"] = $this->session->userdata("location_code");
			$val["created_by"]    = $this->session->userdata("user_id");
			$val["created_date"]  = date('Y-m-d H:i:s');
				
			$this->insert_data($this->org_table, $val);
				
			return $val['org_code'];
				
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
	
	
	public function update_org($params, $org_code)
	{
		try
		{
			$val   = array();
			$where = array();
				
			$where["org_code"] 	  = filter_var($org_code, FILTER_SANITIZE_STRING);
			$val["org_code"]      = filter_var($params['org_code'], FILTER_SANITIZE_STRING);
				
			if(! EMPTY($params['parent_org_code']))
				$val["org_parent"] 	= filter_var($params['parent_org_code'], FILTER_SANITIZE_STRING);
				
			$val["short_name"] 	  = filter_var($params['org_short_name'], FILTER_SANITIZE_STRING);
			$val["name"] 		  = filter_var($params['org_name'], FILTER_SANITIZE_STRING);
			$val["website"] 	  = filter_var($params['website'], FILTER_SANITIZE_URL);
			$val["email"] 		  = filter_var($params['email'], FILTER_SANITIZE_EMAIL);
			$val["phone"]	      = filter_var($params['tel_no'], FILTER_SANITIZE_STRING);
			$val["fax"] 		  = filter_var($params['fax_no'], FILTER_SANITIZE_STRING);
			$val["modified_by"]	  = $this->session->userdata("user_id");
			$val["modified_date"] = date('Y-m-d H:i:s');
				
			$this->update_data($this->org_table, $val, $where);
			
			return $params['org_code'];
				
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
	
	public function delete_org($id)
	{
		try
		{
			$where = array();
	
			$where['org_code'] = $id;
	
			$this->delete_data($this->org_table, $where);
	
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
	
	public function get_users_count($id)
	{
		try
		{
			$where = array();
				
			$fields = array("COUNT(*) cnt");
			$where["org_code"] = $id;
	
			return $this->select_one($fields, $this->user_table, $where);
	
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
	public function get_scope($module_id, $role)
	{
		try
		{
			$val = array(
				$module_id, 
				$role
			);
			
			$query =<<<EOS
				SELECT * FROM $this->module_scope_role_table
				WHERE module_id = ? AND role_code = ? 
EOS;
		
			$stmt = $this->query($query, $val, FALSE);
			
			return $stmt;
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
	
	public function get_module_scope_roles($module_id)
	{
		try
		{
			$val = array($module_id);
				
			$query =<<<EOS
				SELECT GROUP_CONCAT(role_code) role_code FROM $this->module_scope_role_table
				WHERE module_id = ?
EOS;
			$stmt = $this->db->query($query, $val, FALSE);
			
			return $stmt;
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
	public function get_permission_access($module_id, $button_action = NULL)
	{
		try
		{
			$where = "";
			$val = array($module_id);
			
			if(!IS_NULL($button_action)){
				$where = " AND B.action_id = ?";
				$val[] = $button_action;
			}
				
			$query = <<<EOS
				SELECT A.role_code 
				FROM module_action_roles A, module_actions B 
				WHERE A.module_action_id = B.module_action_id 
				AND B.module_id = ? 
				$where
EOS;
					
			$stmt = $this->query($query, $val);
			
			return $stmt;
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