<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Orgs_model extends SYSAD_Model {
                
	var $org_table                           = "organizations";
	var $user_table                          = "users";
	public $db_main                          = DB_MAIN;
	public $tbl_param_offices                = "param_offices";
	public $tbl_param_office_types           = "param_office_types";
	public $tbl_param_responsibility_centers = "param_responsibility_centers";
	//START : davcorrea: reflect office name change in employees work experience table
	public $tbl_employee_work_experiences    = 'employee_work_experiences';
	//====================END=========================
	
	public function __construct() {
		parent::__construct(); 
	}		
	
	
	public function get_org_details($org_code)
	{
		try
		{
			$where = array();
			
			$fields = array("*");
			$where["A.org_code"] = $org_code;
			$tables = array(
				'main'	=> array(
					'table'		=> $this->org_table,
					'alias'		=> 'A',
				),
				't2'	=> array(
					'table'		=> $this->db_main.".".$this->tbl_param_offices,
					'alias'		=> 'B',
					'type'		=> 'join',
					'condition'	=> 'A.org_code = B.org_code',
				)
			);
				
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
	public function get_rcc_list()
	{
		try
		{
			$query = <<<EOS
				SELECT responsibility_center_code, responsibility_center_desc
				FROM $this->db_main.$this->tbl_param_responsibility_centers
EOS;
	
			$stmt = $this->query($query, array(),TRUE);
			
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
			// $cColumns = array('a-name', 'b-name','a-website','a-email');
			// NCOCAMPO:ADDED 'c-active_flag' 10/25/2023: START
			$params["IF(c.active_flag = 'Y', 'active', 'inactive')"] = $params["active_flag"];
			$cColumns = array('a-name', 'b-name','a-website','a-email', "IF(c.active_flag = 'Y', 'active', 'inactive')");
			// NCOCAMPO:ADDED 'c-active_flag' 10/25/2023: END
			$fields = str_replace(" , ", " ", implode(", ", $aColumns));
			$sWhere = $this->filtering($cColumns, $params, FALSE);
			$sOrder = $this->ordering($bColumns, $params);
			$sLimit = $this->paging($params);
			
			$filter_str = $sWhere["search_str"];
			$filter_params = $sWhere["search_params"];
		
// 			$query = <<<EOS
// 				SELECT SQL_CALC_FOUND_ROWS $fields
// 				FROM $this->org_table a
// 					LEFT JOIN $this->org_table b ON a.org_parent = b.org_code
// 				$filter_str
// 	        	$sOrder
// 	        	$sLimit
// EOS;
// NCOCAMPO: JOIN tbl_param_offices to org_table:START
	$query = <<<EOS
				SELECT SQL_CALC_FOUND_ROWS $fields
				FROM $this->org_table a
					LEFT JOIN $this->org_table b ON a.org_parent = b.org_code
					LEFT JOIN $this->db_main.$this->tbl_param_offices c ON a.org_code = c.org_code					
				$filter_str
	        	$sOrder
	        	$sLimit
EOS;
// NCOCAMPO: JOIN tbl_param_offices to org_table:END
	
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
			
			// ====================== jendaigo : start : uppercase values ============= //
			$val 				  = array_map('strtoupper', $val);
			// ====================== jendaigo : end : uppercase values ============= //
			
			$val["email"] 		  = filter_var($params['email'], FILTER_SANITIZE_EMAIL);
			$val["phone"]	      = filter_var($params['tel_no'], FILTER_SANITIZE_STRING);
			$val["fax"] 		  = filter_var($params['fax_no'], FILTER_SANITIZE_STRING);
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
			
			$val["org_parent"] 	  = ! EMPTY($params['parent_org_code']) ? filter_var($params['parent_org_code'], FILTER_SANITIZE_STRING) : NULL;
			$val["short_name"] 	  = filter_var($params['org_short_name'], FILTER_SANITIZE_STRING);
			$val["name"] 		  = filter_var($params['org_name'], FILTER_SANITIZE_STRING);
			
			// ====================== jendaigo : start : uppercase values ============= //
			$val 				  = array_map('strtoupper', $val);
			// ====================== jendaigo : end : uppercase values ============= //
			
			$val["website"] 	  = filter_var($params['website'], FILTER_SANITIZE_URL);
			$val["email"] 		  = filter_var($params['email'], FILTER_SANITIZE_EMAIL);
			$val["phone"]	      = filter_var($params['tel_no'], FILTER_SANITIZE_STRING);
			$val["fax"] 		  = filter_var($params['fax_no'], FILTER_SANITIZE_STRING);
			$val["modified_by"]	  = $this->session->userdata("user_id");
			$val["modified_date"] = date('Y-m-d H:i:s');
				
			$this->update_data($this->org_table, $val, $where);
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
	
			$this->delete_data($this->db_main.'.'.$this->tbl_param_offices, $where);
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
	public function get_general_data($fields, $table, $where = array(), $multiple = TRUE, $order_by = array(), $group = array(), $limit = NULL)
	{
		try{
	
			if($multiple)
			{
				return $this->select_all($fields, $table, $where, $order_by, $group, $limit);
			
			}
			else
			{
				return $this->select_one($fields, $table, $where, $order_by, $group, $limit);
			}
		}
		catch (PDOException $e){
			throw $e;
		}
	}
	public function insert_general_data($table, $params, $return_id = FALSE)
	{
		try
		{
			return $this->insert_data($table, $params, $return_id);
	
		}
		catch(PDOException $e)
		{
			throw $e;
		}
	}
	public function update_general_data($tables, $params, $where)
	{
		try
		{
			return $this->update_data($tables, $params, $where);
	
		}
		catch(PDOException $e)
		{
			throw $e;
		}
	}
	public function delete_general_data($table,$where)
	{
		try
		{
			return $this->delete_data($table, $where);
	
		}
		catch(PDOException $e)
		{
			throw $e;
		}
	}		
}