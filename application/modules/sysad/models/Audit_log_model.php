<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Audit_log_model extends SYSAD_Model {
                
	var $audit_table = "audit_trail";
	var $audit_detail_table = "audit_trail_detail";
	var $user_table = "users";
	var $module_table = "modules";
	
	public function __construct() {
		parent::__construct(); 
	}		
	
	
	public function get_audit_log($audit_log_id)
	{
		try
		{
			$query =<<<EOS
				SELECT A.*, DATE_FORMAT(activity_date,'%m/%d/%Y %r') activity_date, CONCAT(B.fname, ' ', B.lname) name, C.module_name
				FROM $this->audit_table A, $this->user_table B, $this->module_table C
				WHERE A.user_id = B.user_id
				AND A.module_id = C.module_id
				AND A.audit_trail_id = ?
EOS;
			$stmt = $this->query($query, array($audit_log_id), FALSE);
			
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
	
	public function get_audit_log_details($audit_log_id)
	{
		try
		{
			$where = array();
			
			$fields = array("*");
			$where["audit_trail_id"] = $audit_log_id;
				
			return $this->select_all($fields, $this->audit_detail_table, $where);
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
	
	public function get_audit_log_list($aColumns, $bColumns, $params)
	{
		try
		{
			if(!EMPTY($params['fullname']))
				$params["CONCAT(B-fname,' ',B-lname)"] = $params['fullname'];
			
			$cColumns = array("CONCAT(B-fname,' ',B-lname)", "C-module_name", "A-activity", "A-activity_date", "A-ip_address");
			
			$fields = str_replace(" , ", " ", implode(", ", $aColumns));
		
			$sWhere = $this->filtering($cColumns, $params, TRUE);
			$sOrder = $this->ordering($bColumns, $params);
			$sLimit = $this->paging($params);
			
			$filter_str = $sWhere["search_str"];
			$filter_params = $sWhere["search_params"];
			
			if(ISSET($params['system_code']) AND $params['system_code'] != "0"  ){
				$val = array($params['system_code']);
				$val = array_merge($val,$filter_params);
				$filter_sys_code = " AND C.system_code = ? ";
			}else{
				$filter_sys_code = "";
				$val = $filter_params;
			}
			
			$query = <<<EOS
				SELECT SQL_CALC_FOUND_ROWS $fields
				FROM $this->audit_table A, $this->user_table B, $this->module_table C
				WHERE A.user_id = B.user_id
				AND A.module_id = C.module_id
				$filter_sys_code
				$filter_str
	        	$sOrder
	        	$sLimit
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
	
	
	public function filtered_length($aColumns, $bColumns, $params)
	{
		try
		{
			$this->get_audit_log_list($aColumns, $bColumns, $params);
		
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
			$fields = array("COUNT(audit_trail_id) cnt");
			
			return $this->select_one($fields, $this->audit_table);
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